# Roadmap item 2 — Prepare the Laravel 10 application

**Historical checkpoint:** this document records the completed Laravel 10 preparation that preceded Batch 3. Batch 3 has since upgraded the application through Laravel 11 to Laravel 12; see [`LARAVEL_UPGRADE_PLAN.md`](LARAVEL_UPGRADE_PLAN.md) for current versions, verification results, and remaining notes. The Laravel 10 advisory count below describes the earlier checkpoint and is no longer the current audit result.

## Work completed in this preparation pass

- PHP 8.4.26 remains the supported local baseline (`composer.json` is `>=8.2 <8.5`). Composer validation and platform checks pass.
- `php artisan test` works: Collision is aligned to 7.12.0 with PHPUnit 10.5.66. The full synthetic suite passes 21 tests / 131 assertions with PHP warnings visible.
- Production frontend compilation succeeds with the existing Mix 4 / Vue 2 stack. It emits outdated Browserslist data and Tailwind 1 purge-mode warnings; generated tracked bundles were restored after the check.
- All 730 routes register. The authenticated admin members screen now returns HTTP 200; member import maps the supplied CSV headings, constrains duplicate/referral checks to the current church, skips incomplete/duplicate rows, and reports row counts. The dynamic church SMTP transport and message-header hooks now use Laravel 10's Symfony Mailer APIs; no mail was sent during verification.
- Composer constraints and lock were updated for patched Dompdf and Media Library releases; the vulnerable sitemap/browser-crawler chain was removed and sitemap output now uses known public routes. See `COMPOSER_DEPENDENCY_INVENTORY.md` for current versions and risks.
- The current `composer audit` reports four advisories in Laravel 10 plus abandoned Doctrine Annotations. Laravel security remediation requires the separate framework-major upgrade. Do not treat this branch as production security-ready.

## Remaining release gates

This pass improves and verifies a reproducible Laravel 10 baseline; it does not certify the entire church product or guarantee no future errors. The suite now checks representative two-church isolation, payment amount/currency matching, and installer rerun refusal. Remaining release gates include signed provider callbacks, concurrent payment idempotency, first-run installer flow in a separate environment, consent-aware email/SMS/push, CI, and mail-tracking disposition. The custom per-church transport uses Symfony Mailer, but MailTracker references point to a package absent from Composer. Refresh the audit and resolve the Laravel advisories through item 3 before production.

## Goal

Create a reproducible, tested checkpoint on the current framework major before attempting Laravel 11. Keep Laravel 10, Vue/build-chain changes, the church data-model redesign, and the Laravel Excel major upgrade as separate efforts.

## Starting facts

- Laravel 10.50.3; PHP 8.4.26 is the supported local runtime in this checkout.
- `composer validate --no-check-publish` and `composer check-platform-reqs` pass on PHP 8.4.26.
- The default PHPUnit suite passes against the synthetic `churchcms_baseline_20261015` schema.
- PHP 8.5 is not supported by the currently locked PhpSpreadsheet 1.x stack.
- The checkout contains preserved, uncommitted changes from earlier work. Review and identify ownership before dependency changes; do not reset or accidentally combine them.
- The previously observed admin member-list rendering defect and broken `UsersImport` have been fixed and tested; remaining unverified product paths are listed in `BASELINE_SMOKE_CHECKLIST.md`.

## Ordered workstreams

### 2.0 Freeze the starting point

1. Review `git status` and each existing diff, including untracked files.
2. Identify which changes belong to the earlier FCM/PHP compatibility work and which are baseline documentation/tests.
3. Keep the synthetic baseline database and backup separate from source control.
4. Record a clean, reviewable starting revision before dependency edits. Do not commit secrets, `.env`, local dumps, or generated vendor/build output.

**Deliverable:** an agreed, reproducible source checkpoint with the intended changes clearly separated.

### 2.1 Stabilize development and test commands

1. Keep PHP 8.4 as this project's explicit runtime and record required extensions.
2. Resolve the `php artisan test` Collision/PHPUnit version mismatch within supported Laravel 10 constraints, or document direct PHPUnit as the canonical runner until resolved.
3. Make the baseline suite safe for repeat runs; keep its explicit synthetic-database guard and rollback behavior.
4. Establish repeatable Composer and frontend build commands, and note required Node/Yarn versions without changing the frontend stack yet.

**Deliverable:** one documented command sequence for installing dependencies, validating platform requirements, running PHP checks, and building the existing frontend.

### 2.2 Inventory Composer dependencies

For every direct Composer dependency, record its installed/locked version, purpose, where it is used, Laravel 10/PHP 8.4 compatibility, maintenance status, and any replacement or removal proposal. Include dev dependencies and important transitive packages that constrain framework/PHP upgrades.

Classify each as:

- keep during Laravel 10 preparation;
- safe patch/minor update within the declared major;
- update required before Laravel 11;
- remove only after confirming no runtime, command, test, or deployment use;
- defer because it needs a separate feature migration.

Pay particular attention to the observed Collision/PHPUnit mismatch, Laravel Excel/PhpSpreadsheet PHP ceiling, Sanctum, Laratrust, Passport-related code, Dusk, Google/Firebase packages, filesystem adapters, and payment/SMS integrations.

**Deliverable:** a dependency matrix with evidence and a small, ordered update set. Do not use broad `composer update` or ignore platform requirements.

### 2.3 Inventory Laravel integration points and compatibility risks

Map the code that is most likely to break in framework upgrades:

- custom and auto-discovered service providers;
- route providers, route middleware aliases/groups, authentication guards, policies, and authorization middleware;
- exception handling, validation, session, cache, queue, scheduler, mail, filesystem, and logging configuration;
- Eloquent casts, scopes, events/observers, model boot methods, factories, and migrations;
- notifications, API resources, Sanctum tokens, and any Passport or JWT integration;
- custom commands/installers and provider SDK adapters;
- package overrides, macros, published vendor views/config, and Composer scripts.

Search for deprecated framework APIs and PHP 8.4 warnings while preserving current behavior. Record exact file locations, examples, severity, and whether a finding blocks Laravel 11.

**Deliverable:** a compatibility ledger linked to tests or a reproducible example for each high-risk finding.

### 2.4 Stabilize baseline behavior on Laravel 10

1. Repair or explicitly quarantine the admin member-list rendering defect and update its characterization test accordingly.
2. Repair the member import implementation before claiming import coverage; use synthetic CSV/XLSX fixtures, row-level assertions, and rollback/cleanup.
3. Add representative tests for missing baseline journeys: church-boundary enforcement, donation reconciliation in provider sandbox/fake mode, and consent-aware mail/SMS/push queues.
4. Keep installer checks isolated; test first-run and rerun behavior only against a disposable schema and assert it cannot silently overwrite an existing church.
5. Keep payments and external communications mocked or sandboxed. Never use live credentials in baseline tests.

**Deliverable:** a regression suite that passes on the current Laravel 10 baseline and distinguishes application failures from framework-upgrade failures.

### 2.5 Apply only controlled Laravel 10 dependency changes

1. Update one dependency group at a time, staying inside current major versions unless a blocker has a separately approved migration plan.
2. Regenerate `composer.lock` intentionally; review the complete lockfile diff and package scripts.
3. After each change, run Composer validation/platform checks, the PHP test suite, route registration, the synthetic-database smoke suite, and frontend build if relevant.
4. Recheck backup/restore and migration status when a package changes schema behavior.
5. Keep PHP 8.5 support and Laravel Excel 4.x/PhpSpreadsheet 5.x out of this workstream.

**Deliverable:** a reviewed Laravel 10 dependency checkpoint with no unexplained lockfile changes.

## Exit criteria for item 2

- The starting changes are reviewed and the checkpoint is reproducible.
- Composer validation and platform checks pass on PHP 8.4 with the committed lockfile.
- The canonical test command runs without the current Collision/PHPUnit mismatch, or the direct PHPUnit command is explicitly the supported project runner and CI uses it.
- Dependency and framework-integration inventories are complete enough to identify Laravel 11 blockers.
- The frontend build and route registration pass on the same checkpoint.
- Baseline admin/import defects are fixed or explicitly accepted as blockers with tests that do not disguise them as passing behavior.
- Required smoke paths have repeatable regression checks; no live payment or communication services are called.
- Backup and restore instructions remain valid for the synthetic schema; live-data backups remain a separate operational gate.

These criteria were used to begin item 3. The Laravel 10 → 11 → 12 upgrade is now complete; refer to the Batch 3 verification record in `LARAVEL_UPGRADE_PLAN.md`.
