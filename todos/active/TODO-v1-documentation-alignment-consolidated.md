# TODO: Documentation Alignment (Consolidated)
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Consolidate and align documentation updates from this session into a single source-of-truth TODO, covering partner/account profile decisions, push/profile requirements, invite audience rules, MVP admin-assigned issuance, and the “metrics data now, dashboards later” stance.

## Scope
- Consolidate the recent documentation decisions into one TODO and remove the session-specific TODOs that were created for individual updates.
- Ensure all affected documentation reflects:
  - **Account Profile** (generic) as 1:N under Account.
  - `inviter_principal.kind = user|account_profile`.
  - Account Profile invites are allowed in MVP and are **admin-assigned** (no memberships yet).
  - User invites are limited to contacts/installed users; account profiles can target favorites/followers.
  - Push message creation requires `account_profile_id` (account + tenant routes).
  - **Invite metrics data is captured in MVP**; dashboards/aggregation UI are deferred.
- Align remaining `partner_id` / `partner_profile_id` references to `account_profile_id` where they represent partner profiles (invites, share codes, roadmap, and active TODOs).
- Replace any lingering “partner-issued invites deferred” language with “account_profile invites allowed; dashboards deferred.”
- Make it explicit that `account_profile.location` is optional and only geo-enabled profiles are indexed/queried.
- Document the “user upgrades to influencer” path as: create Account + AccountProfile + link operator (admin-assigned in MVP; self-serve later).
- Make it explicit that the **AccountProfile** model is intended to live in the boilerplate (generic, project-defined types).
- Resolve merge conflict markers in `foundation_documentation/system_roadmap.md` and reconcile endpoint status notes.
- Align partner terminology to Account/Account Profile where required:
  - Update `endpoints_mvp_contracts.md` Account + Partner Data Strategy to confirm generic AccountProfile in boilerplate.
  - Update `flutter_client_experience_module.md` to prefer Account/AccountProfile terms; note Partner is a tenant-facing label (future label system).
  - Rename roadmap Phase 12 to **Account Workspace** (includes Account Profile Management).
  - Update `invite_and_social_loop_module.md` to use Account plan/quota naming (post‑MVP); MVP = account user invite flow only.
  - Update `domain_entities.md` to clarify Partner is a label for AccountProfile (tenant-configured; deferred).
- Standardize partner-facing endpoints to `/api/v1/account_profiles` (replace `/partners`) and align related links/contracts (`/account_profile_links`).

## Task Checklist
- [x] ✅ Production‑Ready Align all module docs with AccountProfile 1:N + boilerplate positioning.
- [x] ✅ Production‑Ready Ensure invite rules + inviter principal kind are consistent everywhere.
- [x] ✅ Production‑Ready Ensure push message creation requires `account_profile_id` on account + tenant routes.
- [x] ✅ Production‑Ready Replace remaining `partner_id`/`partner_profile_id` references with `account_profile_id` where they represent profiles (active docs only).
- [x] ✅ Production‑Ready Make optional geo location rule explicit in profile context.
- [x] ✅ Production‑Ready Document user → influencer upgrade path (Account + AccountProfile + operator link).
- [x] ✅ Production‑Ready Confirm metrics stance: data capture now, dashboards later.
- [x] ✅ Production‑Ready Remove/verify session-specific TODOs are consolidated and deleted.
- [x] ✅ Production‑Ready Resolve `foundation_documentation/system_roadmap.md` merge conflicts and confirm endpoint statuses are consistent with current contracts.
- [x] ✅ Production‑Ready Align partner terminology to Account/Account Profile per the decisions above.
- [x] ✅ Production‑Ready Standardize partner-facing endpoint paths to `/api/v1/account_profiles` and align related link endpoints/contracts.
- Remove/replace the following session-created TODOs:
  - `foundation_documentation/todos/completed/TODO-v1-partner-profile-push-contract.md`
  - `foundation_documentation/todos/completed/TODO-v1-invite-audience-constraints.md`
  - `foundation_documentation/todos/completed/TODO-v1-invite-profile-consistency-cleanup.md`
  - `foundation_documentation/todos/active/TODO-v1-invite-metrics-collection-now.md`

## Out of Scope
- Backend/Laravel implementation changes.
- Flutter client changes.
- Designing dashboards/analytics UI or membership systems.

## Definition of Done
- A single consolidated TODO remains active/completed documenting the above decisions.
- The listed session-specific TODO files are removed.
- Documentation remains aligned with the decisions listed in Scope.
- No unresolved COMMENT blocks remain in this TODO.

## Validation Steps
- Manual doc review: ensure key docs reflect the consolidated decisions.
- Verify the listed session-specific TODO files no longer exist.

## Decisions
- Consolidate to a single documentation-alignment TODO for this session.
- Remove the session-specific TODO files listed above after consolidation.
- Metrics data is captured in MVP; dashboards/aggregation UI are deferred.

## Questions to Close
- None.

## Progress So Far
- Consolidated this session’s documentation updates under this single TODO.
- Removed the session-specific TODOs listed in Scope.
- Documentation reflects account_profile 1:N, inviter principal kind, invite audience eligibility, admin-assigned MVP issuance, push profile requirement, and metrics capture now / dashboards later.
- Replaced remaining `partner_id`/`partner_profile_id` references with `account_profile_id` in active docs (invites, share codes, roadmap, TODOs).
- Partner terminology alignment completed across strategy, roadmap, Flutter module, invite quotas, and domain entities.
- Endpoint paths standardized to `account_profiles` and related link endpoints.
- Added explicit account_profile optional geolocation rule, the user→influencer upgrade flow, and the boilerplate AccountProfile requirement to system architecture principles.
- Resolved `system_roadmap.md` duplicate environment row and aligned map/event notes to account profiles.
- Replaced remaining Partner Workspace terminology with Account Profile Workspace in policy and admin module docs.
- Created implementation TODO: `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-implementation.md`.
