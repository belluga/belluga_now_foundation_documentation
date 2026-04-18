# TODO (V1): Screen Polish - Invite

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the tenant-public invite screen/decision flow visuals while preserving invite contract and behavior semantics.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Scope (Single Screen)
- Improve invite hero/header visual hierarchy.
- Improve action CTA prominence and state clarity.
- Improve loading/empty/error states and transition polish.

## Out of Scope
- Invite API/contract changes.
- New invite feature capabilities.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; invite endpoints/contracts remain unchanged.
- `D-02`: Existing invite decision semantics (`accept/decline` and current follow-up behavior) are preserved.
- `D-03`: This screen stays focused on invite decision context; contact-picking/share mechanics remain in invite-friends flow.
- `D-04`: CTA hierarchy must prioritize the primary decision action without changing underlying behavior.
- `D-05`: Loading/error/result feedback remains explicit and behavior-compatible with current controller flow.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Polish invite hero and top chrome visual hierarchy.
- [ ] ⚪ Polish primary/secondary CTAs and spacing.
- [ ] ⚪ Improve loading/empty/error visual states.
- [ ] ⚪ Validate responsiveness and text overflow behavior.
- [ ] ⚪ Ensure invite decision transitions remain stable and deterministic.

## Acceptance Criteria
- [ ] ⚪ Invite context (who/what/when) is visually clearer at first glance.
- [ ] ⚪ Primary and secondary decision CTAs are visually unambiguous.
- [ ] ⚪ Loading/error/result states are explicit and readable.
- [ ] ⚪ No regression in invite decision flow behavior.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Manual smoke covers both decision outcomes and error/retry states.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: invite decision flow (accept/decline/next-step states).
- [ ] ⚪ Manual smoke: loading/error states.
- [ ] ⚪ Manual smoke: responsive layout behavior.
- [ ] ⚪ Manual smoke: overflow and long-text handling in hero/context area.
