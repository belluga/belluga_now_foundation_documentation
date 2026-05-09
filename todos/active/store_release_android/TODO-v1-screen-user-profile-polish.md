# TODO (V1): Screen Polish - User Profile (Authenticated `/profile`)

**Scope authority note (2026-04-17):** this TODO owns only the authenticated user-profile route `/profile`. It does not overlap tenant-public Account Profile discovery or detail polish, which are covered by the completed `TODO-v1-public-account-profile-discovery-ui.md` plus the active `TODO-v1-screen-public-account-profile-detail-polish.md`.

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the authenticated tenant-public user-profile route `/profile` for signed-in users only, without changing profile/auth contracts.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-ui-polish-batch-auth-profile-events-invite.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Terminology
- `User Profile` means the authenticated self/profile route `/profile`; this TODO owns that surface.
- `Public Account Profile` means the tenant-public public identity route `/parceiro/:slug`; it is not owned by this TODO.

## Scope (Single Screen)
- Improve visual hierarchy for signed-in profile state.
- Improve spacing, typography hierarchy, and signed-in state clarity.
- Preserve the existing authenticated route behavior and guard-owned auth boundary.

## Out of Scope
- Backend/API/profile schema changes.
- New profile feature expansion.
- Any signed-out inline profile rendering under `/profile`.
- Account Profile discovery/detail surfaces (`/descobrir`, `/parceiro/:slug`).

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; profile/auth contracts remain unchanged.
- `D-02`: `/profile` remains auth-gated. Anonymous app access keeps the current phone-auth/login behavior and anonymous web access keeps the current app-promotion boundary behavior.
- `D-03`: Signed-in state keeps current action semantics (favorites, navigation, and account-linked actions).
- `D-04`: Loading/error/content states and auth-route handoff remain behavior-compatible with current controller/guard flow.
- `D-05`: No new profile capabilities are introduced in this TODO (polish only).
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Polish signed-in profile layout and action hierarchy.
- [ ] ⚪ Ensure loading/empty/error/content states are visually explicit.
- [ ] ⚪ Validate typography/spacing consistency on mobile breakpoints.
- [ ] ⚪ Ensure authenticated `/profile` routing and guard handoff remain stable with no route regressions.

## Acceptance Criteria
- [ ] ⚪ Signed-in profile exposes primary actions with clearer hierarchy and no semantic changes.
- [ ] ⚪ Loading/error/content states are explicit and readable on the authenticated profile surface.
- [ ] ⚪ No regression in auth-gated profile actions or navigation.
- [ ] ⚪ No signed-out inline profile behavior is introduced under `/profile`.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Authenticated `/profile` smoke path is recorded and auth-boundary behavior is rechecked for regressions.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: signed-in state actions and navigation.
- [ ] ⚪ Manual smoke: loading/error state readability.
- [ ] ⚪ Manual smoke: anonymous app access to `/profile` still resolves through the existing auth/login path.
- [ ] ⚪ Manual smoke: anonymous web access to `/profile` still resolves through the existing app-promotion boundary.
