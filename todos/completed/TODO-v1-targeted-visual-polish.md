# TODO (V1): Tenant Public UI Polish (Auth + Profile + Events + Discovery + Invite + Invite Friends + Account Profile)

**Superseded note (2026-04-17):** this parent polish umbrella no longer owns active execution. Its scope is now carried by the concrete per-screen TODOs under `foundation_documentation/todos/active/store_release_android/`, with discovery already closed in `foundation_documentation/todos/completed/TODO-v1-screen-discovery-polish.md`. The former sign-in/sign-up child was later superseded by the phone-OTP auth cutover lane.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed
**Owners:** Flutter Team
**Objective:** Deliver a focused Flutter-only UI polish sprint for tenant public surfaces that currently have low visual quality, without backend/API contract changes.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one implementation checkpoint + final decision-adherence review before delivery.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/tenant_admin_module.md` (for auth/profile boundaries only)

## References
- `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-events-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-discovery-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-invite-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-public-account-profile-detail-polish.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/completed/TODO-v1-admin-discovery-map-small-fixes.md`

---

## Scope (Flutter-only)
- Historical note: the former **Sign in / Sign up** polish slice was superseded by the phone-OTP auth cutover TODO.
- Polish the **Profile screen** for both signed-in and signed-out states.
- Polish the **Events screen** (list/cards/header/filter visual clarity).
- Polish the **Account Profile Discovery screen**.
- Polish the **Invite screen**.
- Historical note: the former **Invite Friends screen/flow** polish slice was superseded by the minimal friends/favorites MVP feature lane.
- Polish the **Account Profile detail screen**.
- Do not implement a Discovery Hero textual block in MVP.
- Drive Discovery hierarchy through top editorial sections (`Tocando agora` and `Perto de você`), filter chips, and grid composition.
- Keep route ownership in `EnvironmentType=tenant`, `main scope=tenant_public`.
- Preserve existing behavior/contracts unless a visual bug requires a safe UI-only correction.

## Out of Scope
- Backend/API changes, new endpoints, or payload contract changes.
- Tenant-admin/account-workspace management features.
- New product capabilities (this is not a feature expansion stream).
- Large IA redesign or route restructuring.
- Importing hardcoded color palettes from Stitch references.

---

## Rule/Workflow Sources
- `delphi-ai/main_instructions.md`
- `delphi-ai/skills/rule-docker-shared-core-instructions-always-on/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-project-mandate-always-on/SKILL.md`
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-architecture-always-on/SKILL.md`

## Decision Baseline (Frozen)
- `D-01`: This sprint is Flutter-only and must not require backend/API changes.
- `D-02`: In-scope surfaces are limited to seven tenant-public UI areas listed in Scope.
- `D-03`: Auth, invite, discovery, events, and account-profile flows keep existing behavioral semantics; only UX/presentation quality changes.
- `D-04`: No tenant-admin/account-workspace surface expansion in this MVP stream.
- `D-05`: Controller-first architecture remains mandatory (no repository/service calls from widgets/screens).
- `D-06`: Visual states must cover loading/empty/error/content where applicable.
- `D-07`: Delivery requires `fvm flutter analyze` + targeted UI smoke/flow validation.
- `D-08`: Discovery must not include a standalone Hero textual block in MVP; hierarchy comes from sections (`Tocando agora` + `Perto de você`) and feed composition.
- `D-09`: Stitch references drive hierarchy/composition only; colors must come from current app theme tokens (`ThemeData`), not fixed design hex values.
- `D-10`: Discovery feed heading above chips must be `Descubra`; chips stay directly below this heading with single-select behavior.
- `D-11`: `Tocando agora` must render as highlighted card; when more than one live item exists, it becomes a carousel using reusable carousel primitives already adopted in tenant_public/home. MVP rendering is artist-driven: keep the section hidden when live-now payload has no artists.
- `D-12`: Entering search mode hides `Tocando agora` and `Perto de você`, leaving the results grid as the primary content.
- `D-13`: Discovery loading must avoid full-screen flicker/resets during search/filter changes; preserve stable layout with scoped loading indicators.
- `D-14`: Search-active mode must hide `Descubra` heading + category chips. With an empty query, keep the base discovery grid visible and start filtering only after the user types.
- `D-15`: Tenant-home agenda event cards may append an end-time label only when the event has an explicit backend-provided end timestamp; inferred/calculated fallback end times must not be surfaced as factual schedule text.

## Plan Review Gate (Medium)

### Issue Card P-01 — Visual inconsistency across tenant-public surfaces
- Severity: `medium`
- Why now: this is the stated product-quality pain and directly affects first impressions.
- Option A: polish only auth/profile.
- Option B (recommended): polish all seven listed tenant-public surfaces in one bounded sprint.
- Option C: defer polish and keep current UI.
- Effort/Risk/Blast Radius:
  - A: lower effort, medium residual risk, partial UX gain.
  - B: medium effort, controlled risk, broad UX gain.
  - C: zero effort, high product-quality risk.

### Issue Card P-02 — Scope creep into feature work
- Severity: `high`
- Why now: seven-surface polish can drift into behavior/contract changes.
- Option A (recommended): strict guardrails (Flutter-only, no endpoint/contract change).
- Option B: allow small behavior changes opportunistically.
- Option C: split into multiple future TODOs first.
- Effort/Risk/Blast Radius:
  - A: medium effort, low architecture risk.
  - B: medium-high effort, high regression risk.
  - C: lower immediate risk, slower delivery.

### Issue Card P-03 — Regressions from broad UI touchpoints
- Severity: `medium`
- Why now: many screens touched in one stream.
- Option A (recommended): pair each UI area with targeted validation checklist and final smoke.
- Option B: rely only on manual spot-checking.
- Option C: defer until a full redesign cycle.
- Effort/Risk/Blast Radius:
  - A: medium effort, lower regression risk.
  - B: low effort, higher regression risk.
  - C: no short-term effort, quality issue remains.

## Failure Modes & Edge Cases
- Signed-out and signed-in profile states drifting visually or functionally.
- Invite flow CTA hierarchy changes reducing completion clarity.
- Discovery/Event cards regressing on small screens.
- Account Profile detail visual updates obscuring route/action affordances.

## Uncertainty Register
- Assumptions: existing controllers/routes are sufficiently stable for visual-only updates.
- Unknowns: specific edge-case UI states not covered by current fixtures/tests.
- Confidence: `medium`.

---

## Promotion Evidence (Required)
| Workstream | Local Branch / Commit | PR to `dev` | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Tenant Public UI Polish | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |

---

## Per-Screen Split (Authoritative)
- [x] ✅ Historical only `TODO-v1-screen-signin-signup-polish.md` (superseded by `TODO-store-release-phone-otp-auth-and-contact-match.md`)
- [ ] ⚪ `TODO-v1-screen-user-profile-polish.md` (authenticated user-profile screen)
- [ ] ⚪ `TODO-v1-screen-events-polish.md` (events screen)
- [x] ✅ Production-Ready `TODO-v1-screen-discovery-polish.md` (account profile discovery screen)
- [ ] ⚪ `TODO-v1-screen-invite-polish.md` (invite screen)
- [x] ✅ Historical only `TODO-v1-screen-invite-friends-polish.md` (superseded by `TODO-store-release-minimal-friends-and-favorites-mvp.md`)
- [ ] ⚪ `TODO-v1-screen-public-account-profile-detail-polish.md` (public account profile detail screen)

---

## Tasks
- [ ] ⚪ Keep this parent TODO synchronized with child per-screen TODO statuses.
- [ ] ⚪ Ensure all seven child TODOs finish with no backend/API contract changes.
- [ ] ⚪ Consolidate cross-screen visual consistency decisions after each child TODO approval.
- [ ] ⚪ Keep the tenant-home agenda event-card schedule line factual: append end time only when the event carries an explicit end timestamp, never from inferred fallback duration.
- [ ] ⚪ Run final cross-screen regression pass once all child TODOs are `✅ Production-Ready`.

## Acceptance Criteria
- [ ] ⚪ All seven per-screen TODOs are completed and linked with evidence.
- [ ] ⚪ No backend/API contract changes were introduced in the split execution.
- [ ] ⚪ Cross-screen visual language is coherent and theme-driven.
- [ ] ⚪ Tenant-home agenda event-card time labels remain factual and do not present inferred end times as if they were persisted event data.
- [ ] ⚪ Critical tenant-public navigation paths remain regression-free.

## Definition of Done
- [ ] ⚪ Parent TODO reflects final status from all seven child TODOs.
- [ ] ⚪ Decision adherence is recorded against `D-01..D-14`.
- [ ] ⚪ `fvm flutter analyze` is clean for the consolidated result.
- [ ] ⚪ Final manual smoke checklist across seven screens is recorded.
