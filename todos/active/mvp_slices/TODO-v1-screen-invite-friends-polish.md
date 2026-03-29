# TODO (V1): Screen Polish - Invite Friends

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the invite-friends screen/flow UI for better readability and completion clarity while preserving behavior.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Scope (Single Screen/Flow)
- Improve list/search/share visual hierarchy for invite-friends.
- Improve CTA/feedback states during selection/share operations.
- Improve empty/loading/error state communication.

## Out of Scope
- Contacts/invite API contract changes.
- New share mechanics beyond current flow.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; contacts/invite APIs and payload contracts remain unchanged.
- `D-02`: Existing invite-friends flow semantics are preserved (search, select, confirm/share).
- `D-03`: Selection state and CTA state must stay consistent (selected count and action availability).
- `D-04`: Contact permission-denied and empty-results states remain explicit and actionable.
- `D-05`: This flow remains distinct from invite-decision screen; no accept/decline decision UI is introduced here.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Polish invite-friends list/search/share hierarchy.
- [ ] ⚪ Polish selection states and primary CTA clarity.
- [ ] ⚪ Improve loading/empty/error visual states.
- [ ] ⚪ Validate breakpoints and keyboard-safe behavior.
- [ ] ⚪ Ensure selection-to-share transition remains predictable with no semantic changes.

## Acceptance Criteria
- [ ] ⚪ Contact list/search/selection hierarchy is visually clearer and easier to complete.
- [ ] ⚪ Selected-state feedback and primary CTA readiness are explicit.
- [ ] ⚪ Permission-denied, empty, loading, and error states are clear and actionable.
- [ ] ⚪ No behavior regression in selection/share path.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Manual smoke covers search, select, share, permission-denied, and empty states.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: contacts list/search/selection flow.
- [ ] ⚪ Manual smoke: share CTA and result feedback states.
- [ ] ⚪ Manual smoke: empty/loading/error states.
- [ ] ⚪ Manual smoke: permission-denied contact state and recovery guidance.
