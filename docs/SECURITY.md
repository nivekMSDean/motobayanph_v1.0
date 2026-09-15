# MotoBayan PH — Security Design and Hardening Notes

## 1. Security objectives

The storefront handles account data, addresses, carts, orders, customer uploads and eventually payment-provider interactions. The security goals are to preserve confidentiality of customer/admin data, prevent unauthorized state changes, protect sessions, limit abuse of exposed endpoints, keep secrets out of source control and make browser behavior safer by default.

This document describes controls in the remodeled codebase. It is **not** a penetration-test report or a guarantee that the application is vulnerability-free.

## 2. Trust boundaries

| Boundary | Main risks | Primary controls |
|---|---|---|
| Browser -> Laravel | CSRF, session fixation, credential stuffing, malformed input | CSRF middleware, session rotation, request validation, throttles |
| Browser -> uploads | executable/malicious files, oversized files, traversal | extension/MIME validation, 10 MB cap, filename/type checks, sanitized storage folder |
| Laravel -> database | injection, excessive DB permissions | Eloquent/query bindings, server-side validation, least-privilege DB user in production |
| Laravel -> plugins/providers | secret leakage, untrusted callbacks, supply chain | disabled demo payments, env secrets, plugin review, HTTPS, provider signature verification where supported |
| Admin -> application | account takeover, destructive actions | authenticated admin middleware, CSRF, secure session configuration; add MFA/restricted access in production |
| Deployment -> source/config | exposed keys and debug data | blank example APP_KEY, `.env` separation, production checklist |

## 3. Controls implemented by this remodel

### Authentication and session handling

- Successful shop login now calls `session()->regenerate()` before continuing, reducing session-fixation risk.
- Logout is now a **POST** route rather than a GET side effect.
- Logout forms contain Laravel CSRF tokens.
- Logout invalidates session data and rotates the CSRF token.
- Login attempts are rate-limited by normalized email + source IP.
- Registration is rate-limited by source IP.
- Password-recovery operations are rate-limited by email + source IP.
- Session lifetime defaults to 120 minutes and session encryption is configurable, defaulting to enabled in this fork.
- Session SameSite behavior is environment-configurable and defaults to `lax`.

### CSRF protection

Storefront and admin middleware groups already include Laravel CSRF verification. State-changing forms and AJAX calls must continue to send the CSRF token. Converting logout to POST ensures logout follows this model.

### Upload hardening

`UploadRequest` now enforces:

- a file is required;
- a 10 MB maximum file size (`max:10240`);
- allowed types: `jpg`, `jpeg`, `png`, `gif`, `webp`, `mp4`;
- safe filename validation;
- restricted `type` folder names (`alpha_dash` plus secondary validation).

`FileController` sanitizes the folder type once and falls back to `default`. Upload requests are throttled. Uploaded files should still be served from a location where the web server cannot execute them as PHP/scripts.

### HTTP response headers

`App\Http\Middleware\SecurityHeaders` adds:

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- HSTS in production when the incoming request is HTTPS
- optional Content Security Policy (CSP)

CSP is **disabled by default** because third-party BeikeShop payment/plugins can require extra script/frame/connect origins. Enable and tune it only after testing the exact production plugin set.

### Secret handling

- The example `APP_KEY` is blank so a deployment must generate its own key (`php artisan key:generate`).
- Seeded Stripe/PayPal credentials were removed and the sample payment integrations are disabled.
- `.env.example` contains placeholder/sample values only.
- Real payment, mail, database and API secrets must never be placed in seeders or committed files.

### Diagnostic exposure

The public `/test` route that printed a server-side file path was removed.

### Browser/frontend modernization

- Bootstrap dropdown/tooltip attributes in live Blade views were updated from legacy `data-toggle`/`data-target`/`data-dismiss` names to Bootstrap 5 `data-bs-*` names.
- The storefront viewport no longer disables user zoom.
- ElementUI locale loading no longer uses `document.write()`.

## 4. Threat scenarios

### Credential stuffing
An attacker repeatedly tries reused email/password pairs. The shop-login rate limiter reduces request volume per email/IP. Production should also add provider/WAF rate controls, monitoring, strong password rules and preferably MFA for administrator accounts.

### Cross-site request forgery
An attacker tricks a logged-in user into issuing an unwanted state change. Laravel CSRF verification is present on the web/shop/admin middleware groups, and logout is now POST + CSRF rather than GET.

### Session fixation
An attacker causes a victim to authenticate with a known pre-authentication session ID. The session identifier is regenerated after successful login.

### Malicious file upload
An attacker uploads a script disguised as media or uses a crafted path. Request validation, allowed MIME/extensions, size limits, filename checks and folder sanitization reduce risk. Web-server configuration must additionally prevent script execution from upload directories.

### Clickjacking
A malicious site embeds the store in a frame. `X-Frame-Options: SAMEORIGIN` blocks cross-origin framing in supporting browsers; the optional CSP also contains `frame-ancestors 'self'`.

### Leaked payment credentials
Secrets committed in seed data can be harvested from source history. This remodel removes demo credentials and leaves payment plugins disabled until deployment-specific secrets are supplied outside source control.

## 5. Production checklist

Before going live:

- [ ] Use HTTPS only and redirect HTTP to HTTPS.
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`.
- [ ] Generate a unique `APP_KEY`; do not reuse the example from any fork/archive.
- [ ] Set `SESSION_SECURE_COOKIE=true` and retain HttpOnly/SameSite protections.
- [ ] Configure trusted production hostnames and reverse-proxy addresses.
- [ ] Give the database account only the permissions the application needs.
- [ ] Keep `.env`, database dumps, logs and backups outside the public web root.
- [ ] Disable script execution in `public/upload` (or move uploads to object storage/private media delivery).
- [ ] Review every installed plugin and remove unused plugins.
- [ ] Add only real payment credentials through deployment secrets/environment variables.
- [ ] Confirm provider webhooks verify signatures/authenticity before changing order/payment state.
- [ ] Enable CSP after testing all payment, analytics, media and plugin origins.
- [ ] Configure a Redis/database queue worker under a process supervisor if queues are used.
- [ ] Centralize application/security logs and alert on repeated login/reset/upload failures.
- [ ] Schedule database and media backups; test restoration.
- [ ] Run dependency scanning (`composer audit`, `npm audit`) in CI with network access.
- [ ] Run automated tests and a staging regression pass for account, cart, checkout and admin flows.
- [ ] Perform an authorized vulnerability assessment / penetration test before handling real customer/payment data.

## 6. CSP rollout guidance

Start with a staging deployment and inventory all required origins. The default example policy in `config/security.php` is intentionally conservative but still permits inline styles/scripts for compatibility. A mature production policy should progressively remove `'unsafe-inline'` using nonces/hashes after inline scripts are refactored.

To enable after validation:

```dotenv
SECURITY_CSP_ENABLED=true
```

Customize `SECURITY_CSP_POLICY` for the exact payment, analytics, CDN and image domains in use.

## 7. Dependencies and supply chain

The project currently retains Laravel Mix because the BeikeShop theme/plugin asset pipeline is built around `webpack.mix.js` and Blade `mix()` calls. For a future migration, move the core theme to Vite as one tested change set, then update plugin/theme asset contracts together. Do not mix half-migrated build systems in production without regression tests.

The repository lock file previously referenced the `registry.npmmirror.com` mirror. This remodel changes those resolved URLs to the canonical npm registry to reduce an unnecessary environment-specific dependency for future installs.

## 8. Security testing targets

Recommended high-value tests for this store:

1. Authorization checks for order/account/RMA object identifiers.
2. CSRF on every state-changing web action.
3. Session rotation and invalidation behavior.
4. Login/password-reset enumeration and throttle bypass attempts.
5. File upload MIME confusion, double extension, traversal and executable-upload tests.
6. Price/cart/order tampering (client-side price changes must not control server totals).
7. Checkout race conditions and duplicate payment callbacks.
8. Plugin asset/path traversal and plugin authorization boundaries.
9. Stored/reflected XSS in product/admin-managed rich text.
10. Admin role/permission enforcement and plugin installation controls.

## 9. Residual risks

- Third-party plugins can introduce their own routes, scripts, remote origins and vulnerabilities.
- File MIME/type validation is not malware scanning.
- The default theme contains legacy JavaScript dependencies that require ongoing maintenance.
- This remodel has not exercised a real payment provider or webhook configuration.
- Security headers alone do not replace output encoding, authorization or server hardening.
- No live penetration test was performed as part of this source remodel.
