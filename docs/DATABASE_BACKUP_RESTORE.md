# ChurchCMS database backup and restore runbook

## Scope and safety

- Use this before framework, schema, or production-data changes.
- Never restore production member/giving data locally without approval and appropriate protection. Prefer anonymized representative data.
- Store backups outside the repository in a Windows-account-protected folder. Do not commit backups or credentials.
- Restore only to a new disposable database. Never restore over `churchcms` or a production schema.
- The `churchcms` database in this workspace is empty. Baseline verification used seeded synthetic records from `churchcms_baseline_20261015`; the resulting dump is not a live-church backup.

## Prerequisites

- Install compatible MySQL client tools or use MySQL Workbench.
- Confirm the intended source database in `.env` without displaying or sharing its password.
- Choose a restricted backup folder outside the repository, check available disk space, and ensure it is not synced to a public/shared location.

## Backup

Export structure and data for only the intended schema to a timestamped SQL file. Use a protected MySQL option file or password prompt; never include a password in a command, script, or committed file. Use a consistent snapshot for transactional tables.

Record timestamp, app commit, PHP/database versions, schema, file size, and SHA-256. A zero-byte or schema-only dump is not a complete backup if the source has records.

## Restore verification

1. Create a new disposable schema with a distinct name such as `churchcms_restore_check_YYYYMMDD`.
2. Import into that schema only; double-check the destination name.
3. Compare table count, migration state, and selected non-sensitive row counts.
4. Point a separate local app process at the restored schema and run the smoke checks in `BASELINE_SMOKE_CHECKLIST.md` with test accounts and fake/sandbox providers.
5. Record results and errors. Delete the disposable schema only after confirming its exact name and that the verification is complete.

## Verified synthetic baseline — 2026-10-15

- Source schema: `churchcms_baseline_20261015`.
- Restore target: `churchcms_restore_20261015`.
- The verified backup/restore snapshot has 94 tables and 95 migration records. After that snapshot, one donation-reference uniqueness migration was applied to the source schema only; it now has 96 migration records. The restore target and external backup remain at 95 until the application applies pending migrations. Selected baseline row counts match the verified snapshot: 162 users/profiles, 35 events, 1 attendance session, 1 attendee, 1 legacy attendance row, 1 donation, 8 galleries, 6 bulletins, 11 sermons, 22 posts, 22 pages, 4 groups, 20 funds, 49 prayers, and 26 FAQs.
- Backup file: `D:\ChurchCMS-Backups\churchcms-baseline-20261015.sql` (12,722,442 bytes).
- SHA-256: `971D337B67FF44C6081B090D1519046B9D6515EDDA16E46CB5BFF4752C27667A`.
- The original `churchcms` schema was checked separately and still has zero tables.
- MySQL tools are installed under `C:\Program Files\MySQL\MySQL Server 26.7\bin` but are not on the default PATH.

This verifies the procedure for synthetic baseline data only. Before a live-data upgrade, take a separate approved backup and apply the church's access, privacy, and retention controls to its storage.
