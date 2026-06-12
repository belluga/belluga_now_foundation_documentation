# TODO: Post-Release Account Profile Favorite Auth Gate Crash and Edge-Case Investigation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
During `v0.2.0+8` release-close review, a customer report indicated that the app could close when tapping a favorite action as an anonymous user. A parallel runtime investigation did not reproduce the crash deterministically on the connected Android device, but it identified a plausible weak spot: the anonymous favorite/auth-wall path could fail inside async telemetry or login redirect boundaries.

A speculative hardening patch was briefly introduced:

- swallow async telemetry failures inside `AuthWallTelemetry.trackTriggered()`;
- swallow `AppPromotionModal.show(...)` / `context.router.replacePath(...)` failures inside the shared account-profile favorite auth gate;
- add tests proving "no crash" when those failures are injected.

That patch was intentionally discarded from the `v0.2.0+8` release branch because it is not a root-cause fix. It turns potential failures into silent no-op behavior and does not prove why the app would close on a real device.

This follow-up exists to investigate the real failure mode and establish a canonical fix only if runtime evidence proves one is needed.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-favorite-auth-gate-crash-investigation`
- **Why this is the right current slice:** the report is concrete, but the current evidence is still inconclusive; the right owner is a bounded investigation + hardening slice rather than speculative release-branch mutation.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user explicitly rejected speculative release-blocker handling and requested this be preserved as follow-up/hardening with investigation included.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns investigation of anonymous favorite/auth-wall crash or forced-close behavior across account-profile favorite entry points.
- It owns determination of whether the defect is:
  - `reproducible_runtime_crash`
  - `non-crash navigation failure`
  - `telemetry-side async error`
  - `platform/device-specific edge case`
  - `unable_to_reproduce_with_current_evidence`
- It may absorb targeted Flutter fixes, focused tests, and device/runtime instrumentation needed to close the real cause.
- It does **not** reopen the v0.2.0+8 favorite auth contract, promotion modal policy, or login-routing semantics unless the investigation proves one of those boundaries is the actual defect source.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the reproduction matrix and the authoritative favorite/auth-wall surfaces that must be exercised under device/runtime evidence.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** the owner is opened from a real customer/runtime report, but no root cause has yet been proven.
- **Exit condition:** either a reproducible root cause is fixed with evidence, or the report is reduced to a bounded non-reproducible finding with sufficient instrumentation and rationale.

## Scope
- [ ] Reproduce the reported anonymous favorite crash/forced-close behavior on real runtime surfaces using ADB/logcat-grade evidence.
- [ ] Exercise every shared account-profile favorite entry point that uses the common auth gate:
  - account profile detail
  - discovery cards
  - immersive event linked-profile lists
  - nested/grouped linked-profile tabs
- [ ] Classify whether failures happen in telemetry, modal/promotion flow, router redirect, pending-action replay, or another shared boundary.
- [ ] If a real defect exists, implement the narrowest canonical fix at the shared boundary rather than per-screen patching.
- [ ] Add focused tests that prove the actual failure mode and the final fix.

## Out of Scope
- [ ] Changing web-vs-app favorite auth-wall product policy.
- [ ] Redesigning favorite CTA copy or modal visuals.
- [ ] Broad telemetry resilience work unrelated to favorite auth flow.
- [ ] Swallowing boundary failures without first proving that is the correct architectural answer.

## Definition of Done
- [ ] The reported favorite/auth-wall failure is either reproducibly fixed or conclusively reduced to a non-reproducible finding with captured device/runtime evidence.
- [ ] Any confirmed defect is fixed at the shared boundary, not duplicated across screens.
- [ ] Tests cover the real failure mode instead of only speculative injected faults.
- [ ] The final conclusion is recorded so future release reviews do not reintroduce the discarded speculative patch as a pseudo-fix.

## Validation Steps
- [ ] Capture device/runtime evidence with `adb logcat` or equivalent while exercising anonymous favorite entry points.
- [ ] Add fail-first focused Flutter tests only after the real failure mode is identified.
- [ ] Run focused Flutter tests for the touched shared auth-wall surfaces after the real fix lands.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<pending>`, `flutter-app:<pending>`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `account-profile favorite auth gate crash and edge-case investigation` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** device/runtime instrumentation, shared auth-gate fixes, focused tests, and narrow documentation updates needed to freeze the real conclusion.
- **Must update or split the TODO:** broader auth-wall redesign, telemetry platform program, or favorite-domain contract changes outside the reproduced failure path.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Customer-reported crashes require hostile runtime verification, not only local unit/widget confidence. | `flutter-app`, ADB/device evidence, focused tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** the surface is shared but bounded; the main uncertainty is causality, not breadth.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `shared favorite auth-wall boundary`
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`

## Decisions (Resolved Before Freeze)
- [ ] `D-01` This TODO is routed from the `v0.2.0+8` release-close review as `follow-up-hardening`, not `release-blocker`.
- [ ] `D-02` The discarded speculative patch must not be reintroduced unless runtime evidence proves that swallowing those exact failures is the correct architectural fix.

## Questions To Close
- [ ] Is the reported failure a real process crash, a Flutter uncaught async exception, or a navigation/runtime no-op that only appears as "app closed" from the user perspective?
- [ ] Does the failure reproduce only on one favorite entry point or only through the shared account-profile favorite auth gate?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The real defect, if it exists, is shared-boundary behavior rather than one isolated screen implementation. | Current favorite surfaces already converge on the shared account-profile favorite auth gate. | The work would need to split into a narrower surface-specific owner. | `High` | `Keep as Assumption` |
| `A-02` | The discarded speculative patch is not sufficient release-quality evidence because it only proves silent absorption of injected failures. | Code review of the reverted patch showed no root-cause proof and no deterministic runtime reproduction. | If runtime evidence later proves those failures are the real defect, the patch must be reintroduced in a stricter, better-specified form. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/presentation/shared/favorites/account_profile_favorite_auth_gate.dart`
- `flutter-app/lib/application/telemetry/auth_wall_telemetry.dart`
- `flutter-app/lib/presentation/tenant_public/partners/**`
- `flutter-app/lib/presentation/tenant_public/discovery/**`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- focused Flutter tests for the shared auth-wall surfaces

### Ordered Steps
1. Freeze the reproduction matrix and exact favorite entry points.
2. Capture device/runtime evidence and classify the failure mode.
3. Add fail-first focused tests for the real failure mode.
4. Implement the narrowest shared-boundary fix if a real defect is proven.
5. Promote the final conclusion into module truth or explicitly archive the non-reproducible result.

### Test Strategy
- **Strategy:** `evidence-first`
- **Why:** the rejected patch proved that speculative fault injection can generate false confidence without matching the real runtime failure.
- **Fail-first target(s) (when required):** the first deterministically reproducible runtime failure mode.

### Runtime / Rollout Notes
- No rollout should occur from speculation alone.
- ADB/runtime proof is authoritative for this slice.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Root instruction source for downstream TODO work. | Root-cause-first delivery and explicit scope boundaries. | Shipping speculative crash fixes as if they were canonical. | This owner stays investigation-first until evidence proves a real defect path. |
| `r0/wf-docker-todo-driven-execution-method/SKILL.md` | Governs routing of non-blocking review findings into explicit TODO owners. | Follow-up ownership and approval discipline. | Leaving the report as chat-only memory. | The finding is preserved as a bounded hardening owner. |
| `foundation_documentation/todos/README.md` | Governs `follow-up-hardening` placement. | Correct lane semantics under `active/post_release_hardening/hardening/`. | Treating an unproven crash as current release blocker or burying it as by-design. | This TODO remains post-release hardening until stronger evidence changes classification. |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Favorite auth-wall behavior is a shared public-client boundary, not per-screen local policy. | Shared boundary ownership. | Reintroducing screen-local auth-wall patches. | Any final fix must stay centralized. |
