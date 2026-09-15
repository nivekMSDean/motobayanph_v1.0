# MotoBayan PH Remodel Notes

## What changed

### Brand and storefront

- New identity: **MotoBayan PH**.
- Proposed website name: **MotoBayan.ph** (availability not asserted).
- New navy/red/yellow rider-oriented design system.
- Original MotoBayan logo, favicon, hero artwork, category cards, brand marks, service icons and 12 product illustrations.
- Tagline/hero direction: daily commuting, maintenance and touring for Filipino riders.
- PHP pricing and Philippines-focused seed settings.
- Accessible viewport restored (user zoom is no longer disabled).
- Added ARIA labels/empty decorative image alt text to important header controls.

### Demo catalog

The original fashion demo data was replaced with motorcycle-specific sample data:

- 12 products (helmet, gloves, shocks, brake kit, spark plug, LED headlight, chain set, rain jacket, air filter, 10W-40 oil, disc alarm and top case).
- 7 motorcycle categories.
- 8 fictional parts brands.
- PHP currency.
- Philippines/Cavite seed context.
- Flat shipping example and rider-focused search keywords.

### Code modernization

- Updated live Bootstrap 4-style `data-toggle`, `data-target` and `data-dismiss` attributes to Bootstrap 5 `data-bs-*` equivalents.
- Removed `document.write()` from ElementUI locale loading.
- Removed the public `/test` debug route.
- Added a dedicated `motobayan.scss` override layer.
- Kept Laravel Mix for compatibility with the existing BeikeShop plugin/theme contract; a full Vite migration should be handled separately with end-to-end asset regression testing.

### Security changes

- Login, password recovery, registration and upload throttles.
- Session ID regeneration after login.
- POST + CSRF logout with session invalidation.
- 10 MB upload cap and cleaned duplicate sanitization logic.
- Security response headers and opt-in CSP.
- Blank example application key.
- Removed seeded payment credentials and disabled sample payment methods.
- Safer, configurable session defaults.

## Original upstream preserved

The remodel intentionally preserves BeikeShop's license file, generator metadata and conditional powered-by/attribution behavior. The ecommerce core remains BeikeShop/Laravel; MotoBayan PH is the custom storefront/data layer built on top of it.

## Main files to review

| Area | Files |
|---|---|
| Brand visuals | `public/image/motobayan/` |
| Brand styles | `resources/beike/shop/default/css/motobayan.scss`, `public/build/beike/shop/default/css/motobayan.css` |
| Storefront layout | `themes/default/layout/master.blade.php`, `themes/default/layout/header.blade.php` |
| Home/footer/menu seed | `database/seeders/ThemeSeeder.php` |
| Catalog demo data | `BrandsSeeder.php`, `CategoriesSeeder.php`, `ProductsSeeder.php`, `CurrenciesSeeder.php` |
| Store config | `database/seeders/SettingsSeeder.php`, `.env.example` |
| Security middleware/config | `app/Http/Middleware/SecurityHeaders.php`, `config/security.php` |
| Auth/upload hardening | `beike/Shop/Routes/shop.php`, login/logout/file controllers and `UploadRequest.php` |
| Architecture/security docs | `docs/ARCHITECTURE.md`, `docs/SECURITY.md` |

## Build note

The uploaded source archive did not contain the full compiled storefront bundle or `public/mix-manifest.json`. A local `npm ci` attempt in the remodel environment could not finish because the lock file was pointing at an unreachable npm mirror. The brand-specific CSS is therefore included as a directly loadable prebuilt file, while the normal full asset bundle should still be built during deployment after dependencies are installed.

Recommended deployment build:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run production
php artisan optimize
```

Then verify the generated Mix manifest and storefront assets before switching traffic.
