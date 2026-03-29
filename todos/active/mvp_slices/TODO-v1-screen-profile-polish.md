# TODO (V1): Screen Polish - Profile (Signed In + Signed Out)

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the tenant-public profile screen for both signed-in and signed-out states without changing profile/auth contracts.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Scope (Single Screen)
- Improve visual hierarchy for signed-in profile state.
- Improve signed-out prompt state and CTA clarity.
- Improve spacing, typography hierarchy, and state transitions.

## Out of Scope
- Backend/API/profile schema changes.
- New profile feature expansion.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; profile/auth contracts remain unchanged.
- `D-02`: Signed-out state keeps current auth CTA destinations and permissions behavior.
- `D-03`: Signed-in state keeps current action semantics (favorites, navigation, and account-linked actions).
- `D-04`: State transitions between signed-out/signed-in/loading/error remain behavior-compatible with current controller flow.
- `D-05`: No new profile capabilities are introduced in this TODO (polish only).
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Polish signed-in profile layout and action hierarchy.
- [ ] ⚪ Polish signed-out empty/prompt state layout and CTAs.
- [ ] ⚪ Ensure loading/empty/error/content states are visually explicit.
- [ ] ⚪ Validate typography/spacing consistency on mobile breakpoints.
- [ ] ⚪ Ensure signed-in/signed-out transition remains stable with no route regressions.

## Acceptance Criteria
- [ ] ⚪ Signed-out profile clearly communicates next action and keeps existing auth entry behavior.
- [ ] ⚪ Signed-in profile exposes primary actions with clearer hierarchy and no semantic changes.
- [ ] ⚪ Loading/error/content states are explicit and readable in both auth states.
- [ ] ⚪ No regression in auth-gated profile actions or navigation.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Both signed-out and signed-in smoke paths are recorded.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: signed-out state and login/signup CTA paths.
- [ ] ⚪ Manual smoke: signed-in state actions and navigation.
- [ ] ⚪ Manual smoke: loading/error state readability.
- [ ] ⚪ Manual smoke: switching auth state and returning to profile screen.
