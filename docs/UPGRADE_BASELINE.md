# Upgrade baseline — 2026-10-15

## Repository checkpoint

- Working branch: `codex/php84-laravel-upgrade-baseline`
- Branch base: `c7db4aa` (`Church wizard install changes (#6)`)
- The branch was created with existing staged/unstaged/untracked work left intact. The working tree is not clean, so review and commit the intended baseline changes before starting a framework-major upgrade.
- PHP 8.4.26 is installed at `C:\tools\php84`; PHP 8.5 remains at `C:\tools\php`.

## Locked application versions

- PHP constraint: `>=8.2 <8.5`
- Laravel: 10.50.3
- Laravel Excel: 3.1.70
- PhpSpreadsheet: 1.30.7
- Google Auth: 1.55.1
- Dompdf wrapper/engine: 3.1.2 / 3.1.6
- Spatie Media Library/Image: 11.23.9 / 3.9.7

Working-tree SHA-256 fingerprints (these include current local edits; they are not a clean committed release baseline):

- `composer.json`: `D39F3047666215857B3413AC08FABD06854369463799557AE96B35F4FAA2FA3F`
- `composer.lock`: `D0C153B4360D515C0F3F836C0EF16F0AB0B0D58B1E23B91A880D603F4C8C5ACA`
- `package-lock.json`: `8BB0C026E1F6BD88304CFCAB0E289CEC2A1B3C55FF3C3C98EB4A90832A712D5D`
- `yarn.lock`: `DBD4AFF5F3F2422FD89E85A096802752FDA76C7F45468021EF7AC3D83E292A68`

`composer validate --no-check-publish` and `composer check-platform-reqs` pass under PHP 8.4.26. The device logout route boots with Sanctum authentication. Baseline request/behavior checks are in `tests/Feature/BaselineSmokeTest.php`; see `docs/BASELINE_SMOKE_CHECKLIST.md` for verified paths and remaining release gates.

## Local configuration observations

- `.env` selects MySQL at `127.0.0.1:3306`, database `churchcms_baseline_20261015` so the local app opens the migrated synthetic demo system. The originally requested empty `churchcms` schema remains untouched.
- The local environment is set to `APP_ENV=local`, `APP_DEBUG=true`; do not use these settings in production.
- Cache and sessions use files; queues run synchronously; uploads use local storage.
- No Docker/Compose, Procfile, or tracked CI workflow was found; deployment configuration is not defined in this checkout.
- The canonical runner is `php artisan test` after moving Collision to 7.12.0 for PHPUnit 10. The PHPUnit XML uses the PHPUnit 10 schema.
- The authenticated admin member-list crash was fixed by providing a string favicon fallback when the setting is absent. The member import was rewritten to map CSV rows explicitly, scope duplicate/referral checks to the signed-in church, and report inserted/skipped rows instead of swallowing import errors.
- The complete baseline run passed 21 tests / 131 assertions after removing global PHP notice/warning suppression and fixing the surfaced login audit and missing church-location errors. Full results and uncovered areas are recorded in `BASELINE_SMOKE_CHECKLIST.md`.
- The latest Composer audit reports four Laravel framework advisories and abandoned `doctrine/annotations`. Dompdf and Media Library vulnerabilities were addressed with compatible updates; the vulnerable sitemap/browser-crawler chain was removed after replacing its command with route-based XML generation.
- A project-specific backup/restore runbook and verified synthetic restore are recorded in `docs/DATABASE_BACKUP_RESTORE.md`.

## Database and restore status

The user's `churchcms` database remains an empty schema with zero tables; no migrations or seeders were run against it. Two isolated scratch schemas were created for baseline work: `churchcms_baseline_20261015` and `churchcms_restore_20261015`. All 94 project migrations plus the package migration are applied on the source scratch schema, core/demo data was seeded, and the restore schema was verified to have the same 94 tables, 95 migration records, and representative sample row counts. Since that restore snapshot, the new donation-reference unique-index migration was applied only to the source scratch schema, bringing it to 96 migration records; the restore schema and external SQL backup remain at the earlier 95-record state and will apply the migration when used with the updated code. The server reports version `26.7.0` (`MySQL Community Server - GPL`). MySQL client tools are installed at `C:\Program Files\MySQL\MySQL Server 26.7\bin` (not on the default PATH).

A synthetic-only backup is saved outside the repository at `D:\ChurchCMS-Backups\churchcms-baseline-20261015.sql` (12,722,442 bytes; SHA-256 `971D337B67FF44C6081B090D1519046B9D6515EDDA16E46CB5BFF4752C27667A`). It was restored into `churchcms_restore_20261015`, and table and representative row counts matched. It contains synthetic demo records only; it is not a backup of the original `churchcms` database or any live church data.

The backup/restore and baseline test setup for roadmap item 1 is complete. Do not run the install guide's destructive reset example against `churchcms`. Before an actual framework-major upgrade, review and commit the intended current changes so the checkpoint can be reproduced; do not include local credentials or the external backup.

## Gate to continue

- Before framework-major work: review and commit the intended preserved worktree changes on this branch.
- Before production/live-data work: establish least-privilege database credentials, protect backup storage, and take a separate approved, protected backup; the verified backup here is synthetic only.
- Before production or item 3 completion: upgrade Laravel to a security-supported major, replace abandoned Doctrine annotations, exercise first install in an isolated environment, verify signed provider callbacks and concurrency-safe giving idempotency, complete consent-aware email/SMS/push checks, and add reproducible CI. The green baseline suite cannot guarantee there will never be another application error.
