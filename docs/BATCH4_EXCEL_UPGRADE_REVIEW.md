# Batch 4 — Spreadsheet stack upgrade review

**Reviewed:** 2026-10-15
**Status:** review complete; migration not started.

## Goal

Upgrade Laravel Excel 3.1 to 4.x and PhpSpreadsheet 1.x to 5.x. Keep this isolated from the Vue/build-chain work, broader data-model changes, and the next Laravel framework major upgrade.

## Compatibility and decision

- The project is on Laravel 12.70.0 and PHP 8.4.26, which meet Laravel Excel 4's documented minimums.
- Laravel Excel 4.0.4 is the current package version found during this review. Its package metadata requires PHP 8.3+, Laravel 12 or 13, and PhpSpreadsheet 5.9+. The official migration guide documents new native return types on import/export methods and recommends checking direct PhpSpreadsheet API usage against versions 2 through 5.
- `composer.json` currently caps PHP below 8.5 because PhpSpreadsheet 1.30 does not support it. Do not remove that cap until Composer resolves the complete application under PHP 8.5 and `composer check-platform-reqs` passes there.
- Laravel Excel 4 is the recommended path; no spreadsheet engine replacement is proposed. The package's official guide says its published configuration has no required key changes, but the project's own `config/excel.php` still needs comparison during implementation.

Sources: [Laravel Excel 4 upgrade guide](https://docs.laravel-excel.com/4.x/getting-started/upgrade.html), [Laravel Excel 4 installation requirements](https://docs.laravel-excel.com/4.x/getting-started/installation.html), [Laravel Excel package metadata](https://packagist.org/packages/maatwebsite/excel).

## Application usage found

The application uses Laravel Excel in four import classes and one export class:

- `app/Imports/UsersImport.php` — member CSV/XLSX import with heading rows; used by the admin member import.
- `app/Imports/AttendanceImport.php` — event attendance import.
- `app/Imports/SummaryImport.php` — attendance summary import.
- `app/Imports/SubscribersImport.php` — mailing-list subscriber import.
- `app/Exports/UsersExport.php` — church-scoped member export.
- Controllers call `Excel::import(...)` for imports and `Excel::store(...)` for member exports.

No direct `PhpSpreadsheet` imports or custom spreadsheet event listeners were found in the application source during the initial scan. Re-run the search before changing dependencies.

## Source changes expected for Laravel Excel 4

- Add required return types to import/export contract methods. `UsersImport::collection()` already returns `void`; the other collection imports need explicit `: void`. `UsersExport::collection()` should return `Illuminate\Support\Collection`, and `headings()` already returns `array`.
- Confirm `SubscribersImport::batchSize()` is actually enabled through the relevant concern. It currently declares the method without the `WithBatchInserts` interface.
- Review all custom callbacks and returned Excel values for newly enforced signatures. No direct PhpSpreadsheet usage was found, so its API changes appear limited at this time.
- Fix and cover import behavior while editing these paths: `AttendanceImport` and `SummaryImport` can dereference missing users/attendance rows and silently swallow exceptions; `SummaryImport` also needs church-scoped user matching. `SubscribersImport` has an uninitialized inserted counter and catches/logs failures rather than reporting them. These are existing correctness and data-boundary issues that could obscure upgrade regressions.

## Required implementation sequence

1. Preserve the Laravel 12 dependency lock and synthetic database backup; confirm the app still uses `churchcms_baseline_20261015`.
2. Add/import representative synthetic fixtures for CSV and XLSX member, attendance, summary, and subscriber workflows; assert inserted/skipped/error results and church boundaries.
3. Update only Laravel Excel and PhpSpreadsheet constraints first. Resolve the full dependency graph with PHP 8.4; do not use `--ignore-platform-reqs`.
4. Update method signatures and any verified direct API breakages; preserve existing headings, data formats, and export filenames.
5. Run focused import/export tests after each workflow, then the full test suite, route registration, Composer validation/platform checks, security audit, and production asset build.
6. Test the resulting lock under PHP 8.5. Raise the PHP constraint only to the range supported by every production dependency.
7. Verify CSV/XLSX/XLS behavior, date and number formatting, validation and skipped-row reporting, large-file memory/queue behavior, exports, and tenant isolation. Record rollback steps before deployment.

## Exit criteria

- All four import workflows and the member export pass representative CSV/XLSX tests.
- Import failures are visible and actionable; malformed or cross-church rows cannot silently mutate data.
- `composer validate`, platform checks on supported PHP versions, full tests, route checks, and security audit pass.
- Composer no longer pins PhpSpreadsheet 1.x; the PHP 8.5 constraint is widened only if the complete package set supports it.
- A migration note and tested rollback/recovery plan are recorded before staging deployment.
