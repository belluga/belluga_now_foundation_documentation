# TODO (V1): Map Initial Origin Bootstrap

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Production-Ready. The slice was promoted into `origin/main`; this archived file remains historical evidence of the original delivery plus the 2026-06-08 archival catch-up that accepted the missing non-`main` runtime packet as explicit historical verification debt.  
**Owner:** Delphi  
**Date:** 2026-05-15

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `main-green`, `historical-archival-catchup`, `runtime-waiver-approved`, `Code-Reconciled`, `Automated-Gates-Passed`, `Map-Bootstrap`
- **Next exact step:** historical evidence only; any newly observed map-bootstrap defect must open a new TODO.

## Approval
- **Approved by:** explicit user `APROVADO` in the 2026-05-15 implementation thread; archival closeout approval from the explicit 2026-06-08 request to move already promoted TODOs to `completed`.
- **Approval date:** `2026-05-15`, `2026-06-08`
- **Approval scope:** original implementation plus archival closeout with explicit historical runtime-evidence debt.

## Objective
Establish the public map bootstrap so the first render opens from the tenant `settings.map_ui.default_origin`, hands off once to the canonical resolved user origin during the initial bootstrap, and fails closed when the tenant default origin is missing.

## Framing Source
- `Direct-to-TODO`
- Primary story slice: public map first-render camera bootstrap plus one-time canonical-origin handoff.

## References
- [foundation_documentation/modules/map_poi_module.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/modules/map_poi_module.md)
- [flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart)
- [flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/map_layers.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/map_layers.dart)
- [flutter-app/lib/infrastructure/services/location_origin_service.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/services/location_origin_service.dart)
- [flutter-app/lib/infrastructure/repositories/city_map_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/city_map_repository.dart)

## Canonical Module Anchors
- Primary module: `foundation_documentation/modules/map_poi_module.md`
- Secondary module: none
- Decision consolidation target: update `map_poi_module.md` if the final implementation formalizes bootstrap semantics beyond the current tenant-default contract.

## Execution Trace
- Primary execution profile: `Operational / Coder`
- Active technical scope: `flutter`
- Branch lane already prepared:
  - `belluga_now_docker`: `fix/map-initial-origin-bootstrap-20260515`
  - `flutter-app`: `fix/map-initial-origin-bootstrap-20260515`
  - `laravel-app`: `fix/map-initial-origin-bootstrap-20260515`
- Flutter lane bootstrap commit already published: `933a8779` (`🔖 chore: bump app version to 0.0.1+6`)

## Cross-TODO Orchestration
- Sibling tactical TODO: [TODO-v1-account-profile-type-public-capability-admin-ui.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/promotion_lane/TODO-v1-account-profile-type-public-capability-admin-ui.md)
- Orchestration role: `Wave 1 / active lane owner`
- Primary sequencing principle:
  - execute this slice first on the already-bootstrapped map lane
  - keep the public-discoverability slice as a separate approval conversation and separate delivery slice
- Sequencing decision:
  - serial execution approved on `2026-05-15`
  - this TODO is the approved first slice in the two-TODO sequence
- Dependency map:
  - no functional dependency on the public-discoverability slice
  - both slices touch `flutter-app`, so default orchestration is serial to avoid silently multiplexing a map-specific branch
- Branch rule:
  - do not absorb the public-discoverability slice into `fix/map-initial-origin-bootstrap-20260515` without explicit renewed user approval for mixed-lane execution
- Approval rule:
  - this TODO keeps its own `APROVADO` gate even though it is part of the broader two-slice orchestration

## Scope
- Remove the public-map first-render dependency on the hardcoded fallback center.
- Use `settings.map_ui.default_origin` as the only allowed initial camera center during map bootstrap.
- When canonical origin resolution reaches a user-owned origin during the first bootstrap, recenter the map once automatically.
- Preserve the current “camera memory” behavior after the user moves the map and the screen remains alive.
- Surface an existing-style error when the tenant has no configured default origin.
- Add focused Flutter regression coverage for bootstrap center selection and the missing-default-origin failure path.

## Out of Scope
- Any backend or snapshot-pipeline change.
- Any change to return-to-map camera persistence semantics after the first bootstrap.
- Any new fallback center, technical default, or hardcoded lat/lng substitute.
- Broad map UX refactors unrelated to initial bootstrap.

## Definition of Done
- The public map no longer opens from the hardcoded Guarapari center.
- First render opens from tenant `default_origin`.
- Canonical origin handoff auto-recenters only during the first bootstrap and does not reset the later live-screen camera memory behavior.
- Missing tenant `default_origin` produces an explicit visible error instead of silently falling back.
- Focused Flutter regression tests cover the bootstrap center contract and the missing-config failure path.

## Validation Steps
- `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`
- `fvm dart analyze --format machine`
- Manual smoke:
  - first map open with tenant default origin before canonical user location resolves
  - one-time recenter when canonical user origin arrives
  - return to the still-live map screen preserves the last camera position
  - tenant without `default_origin` shows explicit error

## Package-First Assessment
- Query executed:
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --search "map"`
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --search "location"`
- Relevant packages found:
  - `[Local][Laravel] belluga/belluga_map_pois` — not adopted for this slice because the TODO is Flutter bootstrap behavior only and backend/package changes are explicitly out of scope
- READMEs read: none
- Decision: local implementation in the existing Flutter map bootstrap surfaces
- Tier: `host Flutter implementation`
- Rationale: the required behavior is a presentation/bootstrap contract change inside existing Flutter controller/widget/repository boundaries, not a reusable package gap

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The map can render before canonical user origin resolves, so bootstrap needs a deterministic pre-resolution camera source. | `map_screen.dart` initializes asynchronously while `MapLayers` can render with `initialCenter`; prior diagnosis already confirmed this path. | The bug would need a different entry-point diagnosis and the TODO scope would need recalibration. | `High` | `Keep as Assumption` |
| `A-02` | Tenant `default_origin` is already available in the Flutter environment/bootstrap path and does not require backend changes for this slice. | Existing reads through `app_data_dto.dart` and `location_origin_service.dart`; backend/snapshot work is explicitly out of scope. | The slice would block on a producer-surface change and require TODO/plan expansion. | `High` | `Keep as Assumption` |
| `A-03` | Existing camera memory can be preserved if the bootstrap recenter is limited to a one-time pre-user-handoff contract. | Current requirement freeze `D-03` and `D-04`; existing bug analysis isolated the issue to initial bootstrap rather than post-pan memory. | The controller state model would need a broader contract change and renewed approval. | `Medium` | `Keep as Assumption` |
| `A-04` | The missing-`default_origin` failure can reuse an existing visible error path without inventing a fallback center. | User explicitly approved error semantics and rejected fallback; current app already has error-display patterns. | The slice would need a dedicated new error presentation contract. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/map_layers.dart`
- `flutter-app/lib/infrastructure/repositories/city_map_repository.dart`
- `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`

### Ordered Steps
1. Add or tighten fail-first coverage around initial center selection, one-time bootstrap handoff, and missing-default-origin failure.
2. Implement bootstrap state changes in the map controller/repository/widget path without changing later camera memory semantics.
3. Rerun the targeted Flutter test commands and analyzer gate.
4. Reconcile the worker checkpoint into the principal `reconcile/*` checkout.
5. Run final manual runtime smoke for the four approved bootstrap behaviors on the reconciled state.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the slice is a user-visible bootstrap contract change with bounded regression surfaces
- **Fail-first target(s):**
  - `test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
  - `test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`

### Flow Evidence Planning Matrix
| ID | Criterion | Flow-impact reason | Platform parity | Required runtime lane | Mutation requirement | Real-backend requirement | Planned evidence | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `FE-01` | First map open uses tenant `default_origin` before canonical location resolves. | User-visible first-render camera behavior. | `shared-android-web` | `manual runtime smoke` | `no` | `yes` | targeted controller/widget tests plus manual first-open smoke | Requires a tenant with configured `default_origin`. |
| `FE-02` | Canonical user origin recenters only once during bootstrap. | User-visible handoff behavior. | `shared-android-web` | `manual runtime smoke` | `no` | `yes` | targeted controller/widget tests plus manual one-time-handoff smoke | Must not regress later panning memory. |
| `FE-03` | Returning to the still-live map preserves the last camera position. | Existing live-screen memory is a preserved user-flow contract. | `shared-android-web` | `manual runtime smoke` | `no` | `yes` | targeted tests plus manual return-to-live-screen smoke | Runtime lane proves no behavioral reset. |
| `FE-04` | Missing `default_origin` shows explicit error. | User-visible failure mode. | `shared-android-web` | `manual runtime smoke` | `no` | `yes` | targeted controller/widget tests plus manual missing-config smoke | No fallback center is allowed. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / targeted map controller regression` | Wave 1 changes controller bootstrap semantics. | `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart` | `Local-Implemented` | `passed` | `worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532; reconciled re-run passed on 2026-05-15 at flutter-app@b33b7d55` | Reconciled-state rerun completed after Wave 2 landed. |
| `flutter-app / targeted map widget regression` | Wave 1 changes initial camera wiring at the widget layer. | `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart` | `Local-Implemented` | `passed` | `worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532; reconciled re-run passed on 2026-05-15 at flutter-app@b33b7d55` | Reconciled-state rerun completed after Wave 2 landed. |
| `flutter-app / Flutter architecture analyzer gate` | Wave 1 touches Flutter production and test surfaces. | `fvm dart analyze --format machine` | `Local-Implemented` | `passed` | `worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532; reconciled analyzer passed on 2026-05-15 at flutter-app@b33b7d55` | Shared analyzer rerun executed on the final reconciled Flutter checkout. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `ARCH-MAP-01` | `Execution Evidence` | Promoted Flutter map bootstrap commit remains ancestor of `origin/main`, and `origin/main` still carries explicit bootstrap regression coverage for missing `default_origin`, one-time user-origin handoff, and initial-center resolution. | `ancestry+test review` | `git -C flutter-app merge-base --is-ancestor cfe6db19 origin/main`; `git -C flutter-app show origin/main:test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`; `git -C flutter-app show origin/main:test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart` | `origin/main source history` | `passed` | `origin/main` still includes the exact bootstrap controller/widget regression scenarios captured by the promoted slice. |
| `ARCH-MAP-02` | `Canonical Module Anchors` | Canonical map documentation on `origin/main` already carries the tenant `map_ui.default_origin` contract consumed by the promoted bootstrap behavior. | `doc review` | `git -C foundation_documentation show origin/main:modules/map_poi_module.md` | `foundation origin/main docs` | `passed` | The default-origin contract is no longer stranded only inside the tactical TODO. |
| `ARCH-MAP-03` | `Flow Evidence Planning Matrix` | The original non-`main` browser/runtime packet was never captured before the lane later reached `origin/main`; archival catch-up now accepts that as explicit historical verification debt instead of pretending promotion is still pending. | `waiver` | `Execution Evidence blocker notes; approval evidence: explicit 2026-06-08 user request to move already promoted TODOs to completed after code-promotion investigation.` | `historical archival closeout` | `waived` | Approval recorded for documentation-only archival catch-up; this does not claim the missing packet existed. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new code promotion package is being opened; confirm this move only reconciles a stale TODO with code already promoted to `origin/main`. | `n/a` | `git -C flutter-app merge-base --is-ancestor cfe6db19 origin/main` | `none` | No fresh PR/Copilot surface exists for this documentation-only move. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `wf-docker-todo-closeout-promotion-method` | Avoid leaving a code-promoted TODO stranded in `promotion_lane/` after the final lane threshold is already complete. | `passed` | `2026-06-08 status audit; main ancestry check; origin/main map bootstrap regression review` | `no p1 or p2 findings` | Archival keeps the missing runtime packet explicit as historical debt instead of mislabeling the TODO as still awaiting promotion. |

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/workflows/docker/todo-closeout-promotion-method.md` | This is a closeout-path correction, not new implementation. | Same governing TODO through the final archival move; explicit `move-completed` disposition. | Leaving a delivered/main-promoted TODO stranded in `promotion_lane/`. | Archive the file to `completed/` with explicit historical debt notes. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The missing non-`main` runtime packet is real debt. | Residual debt remains explicit and traceable. | Silently pretending the runtime packet existed or reopening code scope without need. | Record the runtime gap as an approved archival waiver, not as a live promotion blocker. |

## Execution Evidence
- Worker checkpoint: `31e0ed83540dc4d5869584e8389bc2773f890532` (`fix(map): wave1 bootstrap tenant default origin`) on `/home/elton/Dev/repos/belluga-ecosystem/_worktrees/flutter-wave1-map-bootstrap-20260515`
- Principal reconcile commit: `cfe6db19` in `flutter-app` on `reconcile/map-bootstrap-public-discoverability-20260515`
- Reconciled automated validation:
  - `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart` ✅
  - `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart` ✅
  - `fvm dart analyze --format machine` ✅
- Historical runtime closeout note:
  - The canonical non-`main` runtime/browser packet was never captured on `2026-05-15` because reconcile preflight blocked before browser execution.
  - The promoted Flutter commit `cfe6db19` later reached `origin/main`; on `2026-06-08` this archival catch-up accepted the missing packet as explicit historical verification debt rather than a live promotion blocker.
  - Current `origin/main` still carries explicit controller/widget regression coverage for the bootstrap behaviors that originally lacked browser evidence.

## Complexity
- `medium`
- Checkpoint policy: one consolidated review before delivery.

## Decision Baseline (Frozen)
- D-01 (`Preserve`): first render of the public map must use tenant `default_origin`.
- D-02 (`Preserve`): the bootstrap path must not use any hardcoded fallback center.
- D-03 (`Preserve`): when canonical user origin arrives during the first bootstrap, the map may recenter automatically once.
- D-04 (`Preserve`): after the first bootstrap, existing camera memory semantics must remain unchanged.
- D-05 (`Preserve`): missing tenant `default_origin` is a visible configuration error, not a fallback scenario.

## TODO Closeout Disposition
- **Disposition:** `move-completed`
- **Disposition reason:** the promoted Flutter bootstrap commit is already an ancestor of `origin/main`, and `origin/main` still carries the exact bootstrap regression coverage for the scenarios that lacked the original non-`main` browser packet. The 2026-06-08 archival catch-up explicitly accepts that missing packet as historical verification debt instead of leaving the TODO stranded in `promotion_lane/`.
- **Post-commit/push status:** `completed`
- **Next path/status action:** file archived at `foundation_documentation/todos/completed/TODO-v1-map-initial-origin-bootstrap.md`
