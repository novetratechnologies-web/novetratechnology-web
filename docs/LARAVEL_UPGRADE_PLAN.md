# Laravel and PHP upgrade plan

## Current baseline

- Laravel framework: 12.70.0 (`laravel/framework: ^12.0`)
- Laravel Excel: 3.1.70 (`maatwebsite/excel: ^3.1`)
- PhpSpreadsheet: 1.30.7
- PHP 8.4.26 installed at `C:\tools\php84` for this project; PHP 8.5 remains installed separately at `C:\tools\php`.

PhpSpreadsheet 1.30.7 declares PHP `<8.5`, so Composer's platform check fails on PHP 8.5. The application has many existing Excel import/export workflows. Do not force-install the current dependency set on PHP 8.5 or swap spreadsheet engines before those workflows have been inventoried.

## Immediate compatibility decision

The current application supports PHP `>=8.2 <8.5`. Use PHP 8.4 for local development and deployment until the framework and spreadsheet stack are upgraded. PHP 8.5 remains installed side by side; switch the terminal's PHP path per project rather than replacing the existing PHP installation. PHP 8.4 uses a separate `php.ini` copied from the existing setup so the enabled extensions are available in both runtimes.

In a PowerShell terminal for this project, select PHP 8.4 for that terminal session with:

```powershell
$env:Path = "C:\tools\php84;$env:Path"
php -v
```

This does not change the system-wide PATH. Open a new terminal to return to the normal PHP selection.

## Staged upgrade sequence

### 1. Establish a safe baseline

- Create a dedicated branch and preserve all current user changes.
- Record the current lockfile, environment requirements, database version, queue/cache/session drivers, and deployment configuration.
- Capture the current application behavior with smoke coverage for authentication, permissions, member records, attendance, giving, imports/exports, notifications, and the installer.
- Back up a representative database and verify a restore before schema changes.

**Status — completed 2026-10-15 for a synthetic local baseline.** See `docs/UPGRADE_BASELINE.md`, `docs/BASELINE_SMOKE_CHECKLIST.md`, and `docs/DATABASE_BACKUP_RESTORE.md`. Known application failures and the behaviors not safe or configured to exercise are listed there; this checkpoint does not claim production readiness.

### 2. Prepare the Laravel 10 application

Prepare a reproducible, tested Laravel 10/PHP 8.4 checkpoint before changing framework majors. Follow the ordered workstreams and exit gates in [`docs/LARAVEL10_PREPARATION.md`](LARAVEL10_PREPARATION.md).

**Known progress:** PHP 8.4 checks, Composer alignment, a 730-route registration check, frontend production build, dependency inventory, compatibility ledger, warning-visible HTTP smoke tests, cross-church list/export coverage, payment amount/currency validation and reference idempotency, installer rerun refusal, and the Symfony mail transport migration are complete. The full suite passes 21 tests / 131 assertions. Four Laravel framework advisories and abandoned Doctrine annotations remain; signed payment callbacks, complete channel-consent coverage, fresh installer flow, CI, MailTracker disposition, and broader tenant isolation remain release gates. Review and commit the intended working tree before section 3; do not treat the branch as production-ready.

### 3. Upgrade one framework major at a time

- Upgrade Laravel 10 to 11 using the official guide and its dependency requirements.
- Resolve code and package incompatibilities, then run the regression checks and inspect migration diffs.
- Upgrade Laravel 11 to 12 as a separate checkpoint, repeat the same compatibility and data-safety review, and only then proceed.
- Do not combine framework, Vue/build-chain, and database-model redesigns in one change set.

**Status — completed 2026-10-15.** The project is now on Laravel 12.70.0, PHP 8.4.26, Sanctum 4.3.3, Laratrust 8.5.5, Dusk 8.7.2, and PHPUnit 11.5.57. The required Laratrust v8 changes were applied to the user model and permission middleware. Laravel 11.57.0 and Laravel 12.70.0 were each checked at their own checkpoint: 21 tests / 131 assertions passed on both. The final Laravel 12 state also passes Composer validation and platform checks, registers all 730 routes, and has no reported security advisories. All migrations report as applied in the synthetic baseline database; no migrations were run against the original empty `churchcms` database. See the Batch 3 verification record below.

**Remaining dependency note:** Composer still reports the transitive `doctrine/annotations` package as abandoned. It has no current security advisory; replace it through a separate L5 Swagger / OpenAPI compatibility change and verify generated API docs before calling that cleanup complete. The existing Excel 3.1 / PhpSpreadsheet 1.30 stack remains deliberately unchanged for Batch 4.

**Support planning:** Laravel 12 bug fixes ended August 13, 2026; security fixes are scheduled through February 24, 2027. Treat Laravel 13 as the next priority before production rollout, and use the [Laravel upgrade guides](https://laravel.com/docs/12.x/upgrade) and [release support table](https://laravel.com/docs/12.x/releases) when scheduling it.

#### Batch 3 verification record

- Laravel 11 checkpoint: framework 11.57.0, Collision 8.5.0, PHPUnit 10.5.66; `php artisan test` passed (21 tests / 131 assertions).
- Laravel 12 final checkpoint: framework 12.70.0, Collision 8.9.5, PHPUnit 11.5.57; `php artisan test` passed (21 tests / 131 assertions).
- `composer validate --no-check-publish`, `composer check-platform-reqs`, `composer audit`, `php artisan route:list`, `php artisan migrate:status`, and `git diff --check` completed. Results: valid manifest, PHP 8.4 platform passes, zero security advisories, 730 routes, all synthetic-baseline migrations applied, no whitespace errors. Composer flags one abandoned transitive package as described above.
- Changes made for framework compatibility: Laravel 11/12 compatible Composer constraints and lockfile; Laratrust's user trait changed to `HasRolesAndPermissions`; custom permission middleware now extends Laratrust's `Permission` middleware.
- Migration-status and tests used only `churchcms_baseline_20261015`. No schema migration was run in this upgrade pass.

### 4. Upgrade spreadsheets after Laravel 12

- Upgrade Laravel Excel from 3.1 to 4.x and PhpSpreadsheet from 1.x to 5.x.
- Update import/export method return types and any direct PhpSpreadsheet API usage required by the v4 migration guide.
- Verify each existing import/export format, headings, date/number formatting, validation, error reporting, and large-file behavior with representative files.
- Remove the temporary PHP `<8.5` cap only after Composer confirms every required package supports the selected PHP 8.5 release.

**Review completed 2026-10-15; implementation not started.** The detailed dependency and application inventory, known import correctness issues, PHP 8.5 gate, and test sequence are in [`docs/BATCH4_EXCEL_UPGRADE_REVIEW.md`](BATCH4_EXCEL_UPGRADE_REVIEW.md). The review found four import classes, one export class, and the corresponding admin import/export controllers. Batch 4 should fix and cover the unsafe import paths as part of the migration so the stricter v4 method contracts do not merely expose existing silent failures.

### 5. Release gates

- `composer validate` and platform requirements pass on the intended production PHP version.
- All automated checks and the documented manual smoke paths pass.
- Database backup and restore are verified; migration rollback or recovery instructions are documented.
- Security review covers authentication, authorization, church data boundaries, uploads, and queued communications.
- Deploy to a staging environment and verify imports/exports before production rollout.

## Decision record

Use PHP 8.4 as the project runtime. Batch 3 now ends on Laravel 12, with a follow-up framework-support review scheduled before Laravel 12 security fixes end. Keep Laravel Excel 3.1 for now to preserve spreadsheet behavior and handle its major migration as Batch 4. Reassess OpenSpout only if the spreadsheet upgrade is blocked or requirements favor a smaller import/export feature set.
