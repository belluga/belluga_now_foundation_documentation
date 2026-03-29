# TODO (V1): Screen Polish - Account Profile Detail

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the account profile detail screen visual hierarchy (hero, tabs/modules, CTAs, state feedback) while preserving current behavior/contracts.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

## Scope (Single Screen)
- Keep current detail route/contract behavior and module structure.
- Improve hero, tabs/modules, and CTA visual hierarchy.
- Improve loading/empty/error/content state clarity.

## Out of Scope
- Backend/API/schema changes.
- New detail modules/capabilities outside approved MVP.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; profile-detail APIs/contracts remain unchanged.
- `D-02`: Existing detail behavior semantics and route entry behavior are preserved.
- `D-03`: Keep title and taxonomy outside the card area, preserving the approved composition.
- `D-04`: Social metrics badges are not part of this MVP polish and remain deferred to vNext.
- `D-05`: Hero, tabs/modules, and CTAs may be polished visually without changing module semantics.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Keep existing base page as canonical detail UI.
- [ ] ⚪ Ensure header + taxonomy remain above tabs for all supported types.
- [ ] ⚪ Polish hero/tabs/modules/CTA hierarchy.
- [ ] ⚪ Improve loading/empty/error/content states.
- [ ] ⚪ Validate mobile breakpoints and text overflow.
- [ ] ⚪ Ensure entrypoints from discovery/events/invites remain behavior-compatible.

## Acceptance Criteria
- [ ] ⚪ Detail hierarchy is clearer while preserving current module semantics.
- [ ] ⚪ Header/taxonomy placement and CTA affordances remain explicit on all supported profile types.
- [ ] ⚪ Entry points from discovery/events/invites remain coherent and regression-free.
- [ ] ⚪ No navigation or behavior regressions are introduced.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Manual smoke confirms state handling and cross-entrypoint continuity.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: detail screen content states (loading/empty/error/content).
- [ ] ⚪ Manual smoke: event/invite entrypoints into detail.
- [ ] ⚪ Manual smoke: mobile breakpoint validation.
- [ ] ⚪ Manual smoke: header/taxonomy placement across supported profile types.
