# Title
Location Permission Back Button Fix

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Role Note
- This TODO is a localized regression slice under the broader back-navigation architecture front.
- App-wide back-governance ownership now lives in `foundation_documentation/todos/completed/TODO-v1-canonical-back-navigation-governance-cutover.md` plus the promoted `flutter_client_experience_module.md` contract (`FCX-07`).
- This file must not be read as the owner of the general back-navigation policy.

## Context
The tenant-public location-permission boundary screen has one inconsistent back behavior: the visible app back button does not close the screen, while device back and browser back already behave as expected. This breaks the shared tenant-public back contract and creates a dead control in a boundary surface.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** This is a bounded regression fix on one boundary screen with localized router/guard impact and explicit fail-first validation.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** The scope is one concrete regression with a known runtime symptom, bounded code surface, and no initiative-level decomposition need.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** Prepare fail-first Flutter coverage for the visible back button path, then implement the route-closing fix after explicit `APROVADO`.

## Scope
- [ ] Make the visible back button on the location-permission screen close the boundary flow correctly.
- [ ] Preserve the existing behavior of device back and browser back.
- [ ] Preserve the existing guard outcomes for `granted`, `continueWithoutLocation`, and `cancelled`.
- [ ] Avoid introducing double-resolution, double-pop, or duplicate guard completion.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<planned>`, `belluga_now_docker:<n/a>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Visible back button fix on location-permission boundary | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] Changing the no-history fallback destination for the location-permission boundary.
- [ ] Refactoring unrelated tenant-public back-policy flows.
- [ ] Altering browser/device back behavior that is already correct.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** screen-level route-closing contract cleanup, guard-facing callback semantics cleanup, and direct regression tests for this boundary flow.
- **Must update or split the TODO:** any change to the approved tenant-public safe-back matrix or any broader route-governance redesign.

## Definition of Done
- [ ] The visible back button closes the location-permission boundary flow in the guarded path.
- [ ] Device back and browser back still behave as they do today.
- [ ] The guarded cancel path still resolves exactly once.
- [ ] Flutter tests cover the corrected path and protect against regression.

## Validation Steps
- [ ] `fvm dart test test/presentation/shared/location_permission/screens/location_permission_screen_test.dart`
- [ ] `fvm dart test test/application/router/guards/any_location_route_guard_test.dart`
- [ ] `fvm dart test test/application/router/guards/live_location_route_guard_test.dart`
- [ ] `fvm dart analyze --format machine`

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| none | n/a | `healthy` | `2026-04-14` | local code/test inspection | n/a |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | n/a | n/a | `n/a` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** The code surface is small, but the behavior is runtime-sensitive because it crosses screen logic, route guards, and shared boundary-dismiss semantics.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/map_poi_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` safe-back / tenant-public boundary sections if the frozen contract needs clarification
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md` safe-back contract sections only if implementation reveals contract drift; otherwise no module doc change

## Decision Pending (Resolve Before Freeze)
- [ ] `D-01` Should the screen separate “notify guard result” from “close current route” as two explicit steps instead of treating `onResult` as route closure?

## Decisions (Resolved Before Freeze)
- [ ] `D-01` The fix must preserve the current boundary outcome contract and only repair the visible back button close path.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `FCX-06` | Tenant-public direct-entry routes use one shared safe-back policy and only fall back when no history exists. | `Preserve` | `flutter_client_experience_module.md`, safe-back contract + [boundary_route_dismissal.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/application/router/support/boundary_route_dismissal.dart:28) |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-01` The visible back button must end in the same semantic cancel outcome as the other back surfaces without adding duplicate route resolution.

## Questions To Close
- [ ] none

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The visible back bug comes from `_finishFlow()` returning immediately after `onResult`, without closing the current route. | [location_permission_screen.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart:254) | The fix point moves deeper into guard or router integration. | `High` | `Keep as Assumption` |
| `A-02` | The current tests do not prove the guarded visible-back path closes exactly once. | [location_permission_screen_test.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/test/presentation/shared/location_permission/screens/location_permission_screen_test.dart:247) | We may already have stronger coverage than expected and can narrow the fail-first test set. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `flutter-app/lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart`
- `flutter-app/test/presentation/shared/location_permission/screens/location_permission_screen_test.dart`
- potentially `flutter-app/test/application/router/guards/any_location_route_guard_test.dart`
- potentially `flutter-app/test/application/router/guards/live_location_route_guard_test.dart`

### Ordered Steps
1. Add fail-first coverage for the visible back path in the guarded `onResult` scenario.
2. Refine the screen close contract so result notification and route closure are not conflated.
3. Rerun focused Flutter tests, then analyzer.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** This is a regression fix with a narrow, reproducible behavioral contract.
- **Fail-first target(s) (when required):** the visible back path on `LocationPermissionScreen` when `onResult` is present.

### Runtime / Rollout Notes
- No migrations. No backend/runtime deploy coupling expected.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `NAV-01`
  - **Severity:** `high`
  - **Evidence:** [location_permission_screen.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart:257)
  - **Why it matters now:** The visible back affordance becomes a dead control in the guarded flow.
  - **Option A (Recommended):** separate result notification from route closure and make the screen own the close semantics explicitly.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** patch the guard callbacks to close the route after receiving `cancelled`.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep relying on device/browser back.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `local`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] Double-resolution when the same cancel action is emitted twice.
- [ ] Pop duplication when the current route is closed both by the screen and by a guard callback side effect.

### Residual Unknowns / Risks
- [ ] The exact close primitive may differ between guarded and standalone uses of the screen.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** n/a
- **Opinion count:** `0`
- **Package mode:** `n/a`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `n/a`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `McClintock` | keep the fix in the screen and separate result notification from close semantics | `neutral` | `improves` | `improves` | `Integrated` | no-context critique in session on `2026-04-14` |

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `recommended`
- **Why this decision:** Medium task with runtime-sensitive navigation semantics.
- **Impact signals in scope:** `runtime/queue/realtime/ingress`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `symptom`, `screen`, `guard`, `boundary helper`, `current tests`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `n/a`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `correctness|elegance|risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** Root cause and double-resolution risk were confirmed; fix should remain in screen contract.
- **Resolution ledger:** use the machine-checkable table below when findings exist
- | Finding ID | Resolution (`Integrated|Challenged|Deferred`) | Usefulness (`useful|noise|mixed|unknown`) | Formalizable (`yes|partial|no|unknown`) | Candidate Rule Level (`paced|project|none|unknown`) | Candidate Rule ID | Rationale / Evidence |
- | --- | --- | --- | --- | --- | --- | --- |
- | `EXT-NAV-01` | `Integrated` | `useful` | `yes` | `project` | `n/a` | Confirms root cause and blocks naïve “always close after callback” fix. |
- **Evidence / reference:** `McClintock` no-context critique, 2026-04-14
- **Waiver authority / reference (required if waived):** `n/a`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `flutter-architecture-adherence` | Flutter presentation/router boundary is in scope | controller/router ownership and centralized navigation policy | ad hoc widget-local navigation hacks | load after approval |
| `flutter-smell-async-navigation` | cancel/result closure crosses async/navigation boundaries | deterministic ownership of navigation after callback | navigation after await without owned contract | load after approval |
| `test-creation-standard` | regression coverage will change | fail-first regression protection | retrofit-only weak assertions | load after approval |
