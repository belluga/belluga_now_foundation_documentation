# TODO: Fast Follow - Map Event Invite Hydration Readiness

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The v0.2.0+8 promotion review found one user-visible residual risk in the public map deck event actions: the invite CTA now correctly preserves the selected occurrence once an `EventModel` is hydrated, but it currently fails closed with `Detalhes do evento ainda não estão prontos para convite.` when the user reaches the action before hydration completes.

This was triaged out of the current release as non-blocking because the primary occurrence-preserving invite/share flows are green, but it remains a real fast-follow usability issue and must be handled explicitly.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-fast-follow-map-event-invite-hydration-readiness`
- **Why this is the right current slice:** this is a bounded continuation of the current event-invite cutover focused on the pre-hydrated map-deck action state, not a broader invite-system redesign.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the issue was found in promotion review and is narrow enough to execute as a direct tactical TODO.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns the first-tap readiness contract for map-deck event invite/share actions.
- It must preserve the selected occurrence identity and the approved web-to-app/invite boundaries.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the intended pre-hydration UX contract for the map-deck event invite CTA and decide whether the fix is disable-until-ready, optimistic preload, or canonical route-backed continuation.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** this TODO was opened from the v0.2.0+8 promotion review as a real but non-blocking fast-follow slice.
- **Exit condition:** supported first-tap flows no longer expose a stale “not ready” invite path and runtime evidence proves the selected occurrence is preserved.

## Scope
- [ ] Define the canonical pre-hydration contract for event invite/share actions in the map deck.
- [ ] Ensure the invite/share CTA either remains unavailable until safe or resolves through a supported continuation path without losing the selected occurrence.
- [ ] Add regression coverage for the pre-hydrated state and the hydrated occurrence-preserving state.

## Out of Scope
- [ ] Reworking the overall invite product, recipient eligibility, or authenticated acceptance lifecycle.
- [ ] Changing the approved web-to-app promotion policy beyond what this first-tap readiness fix strictly requires.

## Definition of Done
- [ ] The map-deck event invite/share CTA does not expose a broken first-tap state in supported flows.
- [ ] The selected occurrence remains preserved in the eventual invite/share continuation.
- [ ] Widget/integration coverage proves both the pre-hydrated and hydrated paths.

## Validation Steps
- [ ] Run focused widget/controller tests for map-deck event actions.
- [ ] Run device or browser runtime evidence for the affected action path, depending on the final contract.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `root:<pending>`, `flutter-app:<pending>`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `map-event-invite-hydration-readiness` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** CTA-state handling, preload timing, route/continuation adjustments needed to preserve occurrence, and test/runtime evidence updates.
- **Must update or split the TODO:** changes to invite semantics, backend mutation contracts, or broader map action architecture unrelated to this first-tap readiness issue.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** the slice is narrow and user-visible, centered on one CTA contract and its regression coverage.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant-public invite and map action sections`
- **Module decision consolidation targets (required):**
  - `invite CTA readiness / occurrence-preserving continuation sections`

## Decisions (Resolved Before Freeze)
- [ ] `D-01` This TODO is routed from the v0.2.0+8 promotion review as `follow-up-fast-follow`, not `release-blocker`.

## Questions To Close
- [ ] Is the better UX to disable the CTA until hydration is complete, or to allow a route-backed continuation that finishes hydration after the tap without surfacing an error?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The issue is real but narrow to the map-deck pre-hydration state. | v0.2.0+8 review sweep: hydrated path is covered and green; pre-hydrated path currently fails closed. | The TODO would need to widen into a broader event invite continuation slice. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart`
- `flutter-app/test/presentation/tenant/map/screens/map_screen/widgets/poi_details_deck_test.dart`
- `foundation_documentation/modules/flutter_client_experience_module.md`

### Ordered Steps
1. Freeze the supported pre-hydration CTA contract.
2. Implement the chosen readiness behavior while preserving occurrence identity.
3. Extend tests and capture runtime evidence on the chosen lane.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the current release already added regression coverage for the hydrated occurrence-preserving path; this follow-up should extend that suite to cover the first-tap readiness contract.

### Runtime / Rollout Notes
- No rollout flag expected. This is a fast-follow usability correction on top of the already-cut-over invite path.
