# Application baseline smoke checks

Run automated checks only against the disposable synthetic baseline schema. The suite refuses any database whose name is not `churchcms_baseline_*`.

```powershell
$env:Path = "C:\tools\php84;$env:Path"
$env:DB_DATABASE = "churchcms_baseline_20261015"
$env:BASELINE_SMOKE_DATABASE = "churchcms_baseline_20261015"
php artisan test
```

## Latest focused results — 2026-10-15

Environment: PHP 8.4.26, Laravel 10.50.3, MySQL Community Server 26.7.0. The complete suite passed 21 tests / 131 assertions against synthetic records after removing global PHP warning suppression. No live payment, email, SMS, or push was sent.

| Area | Result | Limit |
|---|---|---|
| Application boot | Public home and guest registration form return HTTP 200 | Broader route-by-route browser workflows remain outside this suite |
| Guest identity | Invalid requests are rejected; a valid guest profile was created inside a rolled-back transaction | Does not exercise email verification delivery |
| Admin member page | Authorized admin receives HTTP 200; anonymous redirects; ordinary member is forbidden | Other admin screens and permission combinations need coverage |
| Member import | Valid CSV-shaped row maps to a member profile; duplicate mobile and incomplete rows are counted as skipped; test transaction rolls back | Real CSV file parsing/size limits and large-file behavior are not covered |
| Member export | Headings and church/role scope verified | Other export formats and filters are not covered |
| Attendance | Matching synthetic attendee import marks the row present inside a rolled-back transaction | Does not cover unmatched/duplicate/error rows |
| Giving | Paystack, Flutterwave, and Stripe result matching checks provider amount and currency; duplicate references are not recorded twice by web/member verification | Tests use representative provider payloads, not sandbox credentials. Signed callbacks and concurrent duplicate races remain release gates |
| Notifications | FCM HTTP v1 Android/APNs payload constructed without sending | Delivery, retries, channel consent, email/SMS opt-out are not covered |
| Church email transport | Custom mailing-list SMTP mailer resolves to Symfony SMTP transport | No SMTP connection or actual message delivery was attempted |
| Installer | Invalid input is rejected; initialized installations refuse reruns; church/admin/profile creation is transactional; submitted payloads are not logged | Fresh first-run filesystem/database flow needs a separate disposable schema and isolated app copy; not run against the active baseline |
| Mobile API | Unauthenticated device revocation is rejected by Sanctum | Login, registration, cross-church API isolation and successful token revocation need coverage |
| Church boundary | User import lookups, member exports, and admin donation lists are constrained to the authenticated church; two-church fixtures verify export/list isolation | Representative coverage only; all routes, jobs, APIs, storage paths, and notification recipients still need review |

## Sample data

The disposable schema contains 94 tables and 96 migration records, with synthetic demo users/profiles, events, attendance, donations, posts, pages, groups, funds, prayer requests and FAQs. It contains no real church member or financial information. The donation-reference uniqueness migration ran only against this named baseline schema.

## Safety

- The original `churchcms` schema remains separate and empty; tests assert the connection points to `churchcms_baseline_*`.
- Registration, import and attendance writes in tests are wrapped in transactions and rolled back.
- Provider credentials are not used by the test suite.
- See `DATABASE_BACKUP_RESTORE.md` for the synthetic-only backup and restore procedure.
