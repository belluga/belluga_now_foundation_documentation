# TODO (V1): Screen Polish - Invite

**Status legend:** `- [x] ⚪ Pending` · `- [ ] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Apply a simple, bounded visual edit to the tenant-public invite screen/decision flow while preserving invite contract and behavior semantics.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Simple-Edit`, `Store-Release-Candidate`, `No-Contract-Change`
- **Next exact step:** define the small visual adjustment set for the invite decision screen and execute it without broadening into invite contracts, contact picking, grouping, onboarding, or web-to-app policy changes.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Scope (Single Screen)
- Apply only simple visual adjustments to the invite hero/header hierarchy.
- Improve primary/secondary CTA clarity only where the current layout is visibly weak.
- Keep loading/empty/error state treatment lightweight and local to existing UI states.
- Keep this TODO as a small edit; do not turn it into the invite/social-loop MVP workstream.

## Out of Scope
- Invite API/contract changes.
- New invite feature capabilities.
- Contact picking, contact groups, inviteable list composition, friends/favorites, or external-share behavior.
- `/convites/compartilhar` operational regressions such as sharing CTA stuck on `Gerando...` or missing friends-list refresh action; those are owned by `TODO-store-release-minimal-friends-and-favorites-mvp.md`, not this visual-only polish TODO.
- Onboarding, anonymous identity, Auth Wall, or web-to-app promotion policy changes.
- Backend, route, schema, controller, or repository behavior changes unless a trivial test-only adjustment is required by the UI edit.

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; invite endpoints/contracts remain unchanged.
- `D-02`: Existing invite decision semantics (`accept/decline` and current follow-up behavior) are preserved.
- `D-03`: This screen stays focused on invite decision context; contact-picking/share mechanics remain in invite-friends flow.
- `D-04`: CTA hierarchy must prioritize the primary decision action without changing underlying behavior.
- `D-05`: Loading/error/result feedback remains explicit and behavior-compatible with current controller flow.
- `D-06`: Theme-driven colors only.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Freeze the small visual adjustment list before implementation.
- [ ] ⚪ Polish invite hero and top chrome visual hierarchy.
- [ ] ⚪ Polish primary/secondary CTAs and spacing.
- [ ] ⚪ Improve loading/empty/error visual states.
- [ ] ⚪ Validate responsiveness and text overflow behavior.
- [ ] ⚪ Ensure invite decision transitions remain stable and deterministic.

## Acceptance Criteria
- [ ] ⚪ The final change remains a small visual edit with no invite contract or flow expansion.
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
