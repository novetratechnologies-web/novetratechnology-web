# Church management system foundation

## Product direction

Build one church operations product with three connected experiences:

- Staff administration for managing people, ministry work, communications, and reporting.
- A public church site with prepared pages and editable content sections.
- A member and guest experience on the web and in Android/iOS apps.

The backend is the source of truth. All experiences use the same people, church, permissions, and communication rules.

## Working assumptions to confirm

1. **Multiple churches:** preserve the existing `church_id` data boundary and treat each church as isolated. A platform-level operator may manage church accounts, but church staff cannot cross church boundaries.
2. **Mobile:** create one Flutter app for Android and iOS. Keep the public website and staff admin web experiences as web applications.
3. **Guest-to-member lifecycle:** one person record moves from guest to member; approval or conversion must not create a duplicate account.
4. **Church-owned content:** staff edit prepared page sections and content; developers own the page layouts and allowed block types.
5. **Communications require consent:** record channel-specific consent and opt-outs before sending bulk email, SMS, or push messages.

## Product roles

- Platform operator: provisions churches and provides technical support.
- Church administrator: configures the church and delegates access.
- Ministry staff: manages only assigned church functions.
- Member: manages permitted personal information and participates in church activities.
- Guest: browses public content, registers interest, and can be invited into member workflows.

Permissions must be checked in the backend for every read, update, export, and bulk action. A hidden menu item is not an access control.

## Foundation work order

### A. Secure and map the current application

- Remove or protect development endpoints that return whole tables.
- Authenticate destructive account actions and bind them to the current user.
- Inventory routes, models, migrations, integrations, uploads, and external service credentials without exposing secrets.
- Map current records to church ownership and identify missing tenant constraints.
- Establish a reviewed migration and backup/restore plan before production data is changed.

### B. Define the canonical data model

Agree on the relationships among churches, people, user accounts, family links, memberships, groups, consent, and communication identities. Separate a person's church record from login credentials so guests and members can share one profile even if their account status changes.

### C. Modernize the supported platform

- Batch 3 is complete on Laravel 12.70.0 and PHP 8.4.26. Laravel 12 bug fixes ended August 13, 2026, and security fixes end February 24, 2027; prioritize Laravel 13 before production rollout.
- Keep PHP 8.4 while the locked Laravel Excel 3.1 / PhpSpreadsheet 1.30 stack requires PHP below 8.5. Move Laravel Excel to its PHP 8.5-compatible major release in Batch 4, after the framework upgrade checkpoint.
- Replace Vue 2 and its legacy build chain with Vue 3 and a maintained build setup.
- Preserve useful existing features while replacing brittle or unsafe flows incrementally.
- Keep the first architecture as a modular monolith with explicit module boundaries and one API.

### D. Build the shared API and identity flows

- Establish versioned API conventions and consistent validation/error responses.
- Support web sessions and mobile bearer tokens with expiration, revocation, and per-device session management.
- Implement invitation, registration, verification, password recovery, guest conversion, and account deactivation.
- Apply church scoping and policy checks centrally; log sensitive staff actions.

## Feature sequence after foundation

1. People, profiles, families, church membership, roles, and permissions.
2. Events, attendance, groups, and volunteer workflows.
3. Prepared public pages, visitor journeys, sermons, and announcements.
4. Giving records, payment reconciliation, and financial exports.
5. Communications center: audience rules, templates, consent, queued delivery, provider adapters, and delivery logs.
6. Member portal and Flutter app using the same API.
7. Reports, dashboards, imports, exports, and operational automation.

## Definition of ready for the next phase

- Each role and church boundary is documented with examples.
- Guest registration and conversion have a defined duplicate-matching rule.
- Core records have agreed identifiers, ownership, retention, and audit requirements.
- Existing data has a migration map and a tested backup/restore procedure.
- The API authentication model and mobile app approach are approved.
- Known data exposure and unauthenticated destructive endpoints are closed.

## Decisions to revisit before implementation expands

- Whether platform operators will provision multiple independent churches or this will be deployed separately per church.
- Whether staff web will remain inside Laravel or move to a separately deployed Vue application.
- Which SMS, email, push, and payment providers are needed for the launch region.
- Which finance, pastoral-care, and data-retention policies the church requires.
