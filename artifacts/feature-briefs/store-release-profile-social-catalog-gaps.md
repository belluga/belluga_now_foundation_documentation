# Feature Brief: Store Release Profile, Social, And Catalog Gaps

## Artifact Role
- **Why this brief exists now:** manual QA identified several Store Release gaps that are related from the user's release perspective but cross different ownership surfaces: authenticated `/profile`, event invite sharing, and tenant-admin Account Profile Type metadata.
- **What this brief is not:** this is not a canonical module document, tactical implementation authority, project constitution, roadmap item, or approval by itself.

## Source Idea / Request
- Profile matched list includes the current user.
- Matched/profile screen opens empty before matched people load; it needs an explicit loading state.
- Profile name did not persist after a new login; the remaining editable `/profile` fields must persist to the user's own Account Profile and rehydrate after a new session.
- Authenticated profile settings need Store Release closure:
  - radius uses the same widget/pattern as Home without the preference-save message;
  - location selection allows map selection;
  - phone comes from phone login, is saved at login, is not editable, and backend protects immutability;
  - email field is removed;
  - `Visibilidade` and `Alterar Senha` menus are removed;
  - header invite/social metrics come from backend;
  - photo update flow must be verified and persist correctly.
- Event share link/icon may use a default icon but must generate an invite.
- Account Profile Type PLURAL settings are not displayed.

## Problem / Desired Outcome
- **Problem:** release-critical profile/social surfaces still carry pre-release assumptions: self can appear as a matched person, loading is ambiguous, remaining editable profile fields are not yet guaranteed to persist on the user's Account Profile across sessions, phone/email/password UI conflicts with phone-OTP, profile metrics risk hardcoded values, event sharing may not create canonical invites, and tenant-admin plural labels are not visible where type settings are edited.
- **Desired outcome:** close the Store Release profile/social/catalog QA gaps with explicit backend contracts, Flutter UI states, and item-specific tests/runtime validation.
- **Why now:** these gaps affect launch trust, invite conversion, and admin-configured display metadata in the current Android Store Release lane.

## Constraints / Non-Goals
- **Constraints:** keep phone-OTP as the tenant-public app auth baseline; do not reintroduce tenant-public email/password behavior; preserve contact/friends semantics already owned by the social TODO; use canonical tenant/backend origin contracts rather than browser-derived shortcuts; keep source-owned tests for web/browser paths.
- **Non-goals:** generic account workspace expansion, broad people discovery, privacy settings UI, password auth restoration, a new social graph model, or production promotion by this brief.

## Canonical Touchpoints
- **Constitution impact:** none expected; existing first-production and route/scope rules cover the work.
- **Roadmap impact:** none expected; this is Store Release tactical closure.
- **Primary module candidates:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/account_profile_catalog_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`

## Evidence / References
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Authenticated `/profile` reflects the release identity model, real persistence contract, and social metrics correctly. | `flutter_client_experience_module` | `invite_and_social_loop_module`, `onboarding_flow_module` | `/profile` excludes self from matched/social people lists, shows loading, removes email/password/visibility affordances, renders read-only verified phone, uses shared radius/location controls, persists remaining editable fields to the user's Account Profile across session re-entry, persists avatar updates, and displays backend social metrics. | Flutter profile widget/controller tests, Laravel profile/auth tests for phone immutability and profile persistence contract, ADB profile smoke if widget/backend evidence is insufficient. | `update-existing` | Existing profile TODO is active and should absorb this instead of duplicating profile authority. | Current primary tactical slice. |
| `ST-02` | Event detail share icon generates a canonical invite/share code for the selected occurrence. | `invite_and_social_loop_module` | `events_module`, `flutter_client_experience_module` | Event share entrypoint calls invite-share generation with concrete `occurrence_id`, preserves auth/handoff boundaries, and does not become a generic external share-only affordance. | Flutter event detail/share tests, Laravel invite share tests, Playwright/ADB route smoke where needed. | `create-now` | Must stay aligned with occurrence-scoped invite identity and authenticated mutation boundaries. | Separate TODO because it mutates invite/event behavior, not profile settings. |
| `ST-03` | Tenant-admin Account Profile Type edit/create surfaces display and persist plural label settings. | `tenant_admin_module` | `account_profile_catalog_module`, `flutter_client_experience_module` | Account Profile Type form shows singular/plural label settings, DTO/repository payloads preserve `labels.plural`, and runtime consumers can read it from bootstrap/admin readback. | Flutter tenant-admin profile type tests, Laravel account profile type controller/DTO tests, Playwright admin mutation if route/runtime evidence is required. | `create-now` | Must preserve `label` compatibility alias while canonical `labels.singular/plural` remains source of display truth. | Separate TODO because it is admin/catalog metadata. |

## Current Tactical Recommendation
- Run one T6 orchestration plan with three bounded TODOs:
  - `T6-PROFILE`: update existing `TODO-v1-screen-user-profile-polish.md`.
  - `T6-EVENT-SHARE`: create a focused event-share-invite TODO.
  - `T6-PLURAL`: create a focused Account Profile Type plural settings TODO.
- Reason for split: the stories have different owners, tests, backend contracts, and failure modes. Keeping them in one mega-TODO would blur phone immutability, invite generation, and admin label metadata.

## Retire This Brief When
- The T6 TODO set and orchestration plan are approved, implemented, and each TODO has delivery evidence or a documented blocker.
