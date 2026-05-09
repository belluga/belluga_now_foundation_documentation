# TODO (V1): Screen Polish - Events Screen

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Superseded / Completed
**Owners:** Flutter Team
**Objective:** Historical screen-polish placeholder for the tenant-public events screen.

## Superseded Reclassification Note
- 2026-04-28: User confirmed this TODO was not delivered directly, but later Store Release improvements made it unnecessary as a standalone active blocker.
- This TODO is archived as `superseded` rather than claimed as direct delivery. The active Store Release lane should not carry this broad visual-polish placeholder anymore.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/modules/events_module.md`

## Scope (Single Screen)
- Improve events list/card visual hierarchy.
- Improve app-bar/filter readability and affordance.
- Improve empty/loading/error states visual quality.

## Out of Scope
- Event API/query contract changes.
- New events feature capabilities.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; event APIs and query contracts remain unchanged.
- `D-02`: Existing filter semantics and current event list behavior are preserved.
- `D-03`: Card data priority remains event-first (title/time/location/metadata), with readability improvements only.
- `D-04`: Pagination and list continuation behavior remain unchanged.
- `D-05`: Event-card navigation entrypoints remain exactly as current behavior.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Polish events card spacing, typography, metadata hierarchy.
- [ ] ⚪ Polish events header/filter affordance and readability.
- [ ] ⚪ Improve empty/loading/error presentation.
- [ ] ⚪ Validate mobile breakpoints and scroll continuity.
- [ ] ⚪ Ensure list-to-detail navigation flow remains stable after visual changes.

## Acceptance Criteria
- [ ] ⚪ Events list/cards are visually cleaner with clearer title/time/location hierarchy.
- [ ] ⚪ Header/filter controls are easier to read and use with no behavior changes.
- [ ] ⚪ Empty/loading/error states are visually distinct and informative.
- [ ] ⚪ No regressions in filter logic, pagination, or detail navigation.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Manual smoke covers filter, pagination, and detail entrypoint continuity.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: list rendering, filters, and pagination.
- [ ] ⚪ Manual smoke: empty/loading/error states.
- [ ] ⚪ Manual smoke: event detail navigation entrypoints.
- [ ] ⚪ Manual smoke: return-from-detail preserves list context.
