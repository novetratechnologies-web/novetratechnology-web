# Composer dependency inventory — Laravel 10 baseline

Snapshot: 2026-10-15, lockfile after the controlled baseline update. This is a usage/upgrade inventory, not a claim that every feature is covered by tests. `composer.lock` is the source of exact versions.

## Runtime dependencies

| Package | Locked | Purpose / evidence | Preparation decision |
|---|---:|---|---|
| `barryvdh/laravel-dompdf` | 3.1.2 | PDF generation wrapper; used by PDF/export flows | Updated with Dompdf 3.1.6 to address reported renderer advisories; PDF output/layout still needs representative visual regression coverage |
| `botman/botman` | 2.8.11 | Chatbot framework | Keep; test bot webhooks before major upgrade |
| `botman/driver-web` | 1.5.3 | Botman web driver | Keep with Botman |
| `darkaonline/l5-swagger` | 8.6.5 | OpenAPI/Swagger UI integration | Keep; pulls abandoned `doctrine/annotations`; assess a swagger-php/attribute migration |
| `google/auth` | 1.55.1 | Google OAuth service-account access; used by FCM HTTP v1 client | Keep; payload construction covered, no live delivery |
| `google/cloud-language` | 0.31.3 | Google Cloud Language integration | Keep; provider behavior not exercised |
| `google/cloud-storage` | 1.51.0 | Google Cloud Storage integration | Keep; provider behavior not exercised |
| `guzzlehttp/guzzle` | 7.15.5 | HTTP client and webhooks | Keep; test external request adapters with fakes |
| `laracasts/presenter` | 0.2.9 | Presenter traits on Eloquent models | Legacy integration; inventory presenter call sites before Laravel-major work |
| `laravel/framework` | 10.50.3 | Application framework | Current baseline; declared `^10.0`; Laravel advisories remain unresolved on this major |
| `laravel/sanctum` | 3.3.3 | API tokens / SPA auth | Keep; current guard smoke test covers unauthenticated device logout only |
| `laravel/tinker` | 2.11.1 | CLI REPL | Keep; dev/operator tool |
| `laravel/ui` | 4.6.3 | Legacy auth scaffolding | Keep during baseline; assess removal after frontend redesign |
| `lcobucci/jwt` | 5.6.1 | JWT signing/parsing | Keep only while call sites remain; distinguish from Sanctum and document token ownership |
| `league/csv` | 9.28.0 | CSV template/export | Keep; member import now uses Laravel Excel and row mapping |
| `league/flysystem-aws-s3-v3` | 3.35.3 | S3 storage adapter | Keep; test with fake/local storage |
| `maatwebsite/excel` | 3.1.70 | Spreadsheet imports/exports | Keep in Laravel 10 baseline; PhpSpreadsheet 1.x constrains PHP 8.5; isolate its later major migration |
| `nckg/laravel-impersonate` | 4.0.1 | Admin impersonation middleware | Keep; security-sensitive authorization workflow needs dedicated tests |
| `santigarcor/laratrust` | 7.2.1 | Roles/permissions and gates | Keep; admin permissions are central and require role-matrix testing |
| `simplesoftwareio/simple-qrcode` | 4.2.0 | QR code generation | Keep; confirm wrapper compatibility when upgrading Laravel |
| `spatie/laravel-activitylog` | 4.12.3 | Audit trail | Keep; tests should verify sensitive actions create records |
| `spatie/laravel-medialibrary` | 11.23.9 | File/media storage on models | Updated to patched release compatible with Laravel 10; existing usages are limited to media model traits and page attachment upload; conversion, upload, and cleanup behavior still needs integration coverage |
| `stripe/stripe-php` | 20.3.1 | Stripe payment SDK | Keep; use provider sandbox/fakes; never validate reconciliation with live keys |
| `tijsverkoyen/css-to-inline-styles` | 2.4.0 | Inline email CSS | Keep; validate rendered email templates |
| `titasgailius/search-relations` | 1.0.6 | Eloquent relation search | Keep if call sites remain; integration test search filters |
| `twilio/sdk` | 6.44.4 | SMS provider SDK | Keep; consent and delivery behavior require fake-provider tests |

## Development dependencies

| Package | Locked | Purpose / decision |
|---|---:|---|
| `barryvdh/laravel-debugbar` | 3.16.5 | Local debugging; ensure disabled in production |
| `fakerphp/faker` | 1.24.1 | Synthetic test/demo data |
| `filp/whoops` | 2.18.6 | Local error pages; never expose debug pages in production |
| `laravel/dusk` | 7.13.0 | Browser automation; no Dusk workflow currently gates the baseline |
| `laravel/legacy-factories` | 1.4.2 | Legacy factories; convert to class factories before removing |
| `mockery/mockery` | 1.6.15 | Mocking external services |
| `nunomaduro/collision` | 7.12.0 | Laravel test CLI integration; updated from 6.x so Artisan test command matches PHPUnit 10 |
| `phpunit/phpunit` | 10.5.66 | Test runner; project XML migrated to PHPUnit 10 |

## Controlled updates and security findings

The controlled dependency update retained Laravel 10 and updated Dompdf (`barryvdh/laravel-dompdf` 3.1.2 / `dompdf/dompdf` 3.1.6) and Media Library 11.23.9 with Spatie Image 3.9.7. The Sitemap 6 / Crawler 7 / Browsershot 3 chain was removed after source usage review; `sitemap:generate` now writes XML for nine explicitly named public routes without fetching remote pages. Collision 7.12.0 and PHPUnit 10.5.66 remain aligned. No broad update was used. `composer validate --no-check-publish` and `composer check-platform-reqs` pass on PHP 8.4.

`composer audit` on this snapshot reports four advisories, all in Laravel framework 10.50.3, plus abandoned `doctrine/annotations`:

- `laravel/framework` 10.50.3: the current advisories include CVE-2026-48019 affecting Laravel `<11.0`; others affect newer branches and are reported by the current advisory feed. A security-supported framework major is required to clear the Laravel 10 findings; item 3 must be treated as a security-driven upgrade gate.
- `doctrine/annotations` is abandoned and required by L5 Swagger 8.6.5. Plan an OpenAPI attribute migration/version upgrade.

Source also refers to `jdavidbakr/laravel-mail-tracker` event classes and listeners, but that package is absent from both the Composer manifest and installed lock. The old SwiftMailer transport was replaced with Symfony SMTP and its message headers were preserved; open/click tracking is not active or verified until the missing integration is deliberately restored or replaced.

Do not deploy this dependency snapshot as a security-clean baseline. Refresh `composer audit` as the very first step in item 3 and address Laravel and Doctrine findings before production use.
