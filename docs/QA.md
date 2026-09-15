# MotoBayan PH — QA Record

## Checks completed in the remodel environment

- PHP syntax lint passed for all PHP files modified by the remodel.
- `package.json` and `package-lock.json` parse as valid JSON.
- Rewritten catalog/theme/settings seeders pass PHP syntax validation.
- MotoBayan image assets referenced by the demo data are present under `public/image/motobayan/`.
- Seed settings contain no Stripe/PayPal credential values and payment plugins are disabled by default.
- `/test` diagnostic route is removed.
- Store logout is POST-only and storefront logout controls submit CSRF-protected forms.
- Login contains session ID regeneration.
- Logout contains session invalidation and CSRF token regeneration.
- Store login, password-recovery, registration and upload routes contain named rate limiters.
- Upload validation includes a 10 MB maximum size.
- Live storefront Blade views no longer contain Bootstrap 4 `data-toggle`, `data-target` or `data-dismiss` attributes.
- Customer-facing BeikeShop fallback title strings in the default theme were replaced with MotoBayan PH fallbacks.
- `package-lock.json` resolved URLs were normalized from the environment-specific npmmirror host to the canonical npm registry.

## Runtime/build limitation

The uploaded source archive does not include PHP `vendor/`, Node `node_modules/`, or a complete compiled storefront bundle/`mix-manifest.json`. The environment also could not finish dependency retrieval from the configured npm registry, so these checks were **not** claimed as completed here:

- full `npm ci && npm run production` asset build;
- `composer install` / Laravel boot;
- migrations/seeding against a live database;
- browser-based checkout/account regression tests;
- `composer audit` / `npm audit` with current registry data;
- penetration testing.

Run those steps in CI/staging with normal package-registry/network access before production use.

## Suggested staging smoke test

1. Install PHP and Node dependencies.
2. Create `.env`, generate a unique `APP_KEY`, and configure the test database.
3. Build frontend assets.
4. Install/migrate/seed the application.
5. Verify home page, category pages, product pages and PHP pricing.
6. Register/login/logout and confirm session behavior.
7. Add/remove/update cart products and complete guest/authenticated checkout with a safe test payment provider.
8. Verify password reset throttling and email flow.
9. Exercise allowed and blocked file uploads.
10. Verify admin catalog/order/settings pages and confirm no sample payment secrets are present.
11. Confirm response headers over staging HTTPS.
12. Run automated dependency/security scanning and an authorized application security test.
