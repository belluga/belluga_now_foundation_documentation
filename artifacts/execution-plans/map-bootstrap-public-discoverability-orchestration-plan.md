# Orchestration Execution Plan: Map Bootstrap + Public Discoverability

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Approved`
- **Created:** `2026-05-15`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`

## Authority Boundary
- Governing TODOs define **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the orchestrator will sequence the two approved tactical slices as a serial wave.
- If this plan conflicts with a governing TODO, stop and update the TODO or this plan before execution.
- This plan does not create a new backlog authority, tactical TODO, or third implementation slice.
- Requirement wording in the governing TODOs remains literal.
- Workstreams are derived from the exact DoD, validation, and frozen decision rows in the two governing TODOs.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `PLAN-MAP` | `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-initial-origin-bootstrap.md` | `independent / Wave 1` | `can start after orchestration approval plus renewed map TODO approval` |
| `PLAN-PUBLIC` | `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-type-public-capability-admin-ui.md` | `independent / Wave 2 follow-on` | `blocked by Wave 1 closure on the active map lane or explicit lane-multiplexing approval` |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `PLAN-MAP-DOD-01` | `The public map no longer opens from the hardcoded Guarapari center.` | `Wave1-Map-Worker` | `public map initial camera bootstrap` | `controller/repository bootstrap path updated to remove hardcoded startup center` | `targeted map regression tests in flutter-app` | `shared-android-web map first-open smoke on approved runtime target` | `planned` |
| `PLAN-MAP-DOD-02` | `First render opens from tenant default_origin.` | `Wave1-Map-Worker` | `first-render map center` | `map bootstrap uses tenant default origin state` | `controller/widget coverage for initial center selection` | `shared-android-web first render smoke before canonical location resolves` | `planned` |
| `PLAN-MAP-DOD-03` | `Canonical origin handoff auto-recenters only during the first bootstrap and does not reset the later live-screen camera memory behavior.` | `Wave1-Map-Worker` | `one-time bootstrap recenter contract` | `one-time handoff state in controller/widget path` | `targeted controller/widget regression coverage` | `shared-android-web handoff smoke plus preserved live-screen camera smoke` | `planned` |
| `PLAN-MAP-DOD-04` | `Missing tenant default_origin produces an explicit visible error instead of silently falling back.` | `Wave1-Map-Worker` | `map error state` | `fail-closed bootstrap error path` | `controller/widget regression coverage for missing configuration` | `shared-android-web missing-config smoke` | `planned` |
| `PLAN-MAP-DOD-05` | `Focused Flutter regression tests cover the bootstrap center contract and the missing-config failure path.` | `Wave1-Map-Worker` | `targeted Flutter tests` | `test updates in scoped map test files` | `targeted flutter test commands from TODO` | `n/a` | `planned` |
| `PLAN-MAP-VAL-01` | ``fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`` | `Wave1-Map-Worker` | `controller test suite` | `updated controller tests committed on Wave 1 lane` | `exact TODO command must pass` | `n/a` | `planned` |
| `PLAN-MAP-VAL-02` | ``fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`` | `Wave1-Map-Worker` | `widget test suite` | `updated widget tests committed on Wave 1 lane` | `exact TODO command must pass` | `n/a` | `planned` |
| `PLAN-MAP-VAL-03` | ``fvm dart analyze --format machine`` | `Wave1-Map-Worker` | `flutter analyzer gate` | `Wave 1 files analyzer-clean` | `exact analyzer command must pass` | `n/a` | `planned` |
| `PLAN-MAP-VAL-04` | `first map open with tenant default origin before canonical user location resolves` | `Wave1-Map-Worker` | `public map first-open journey` | `bootstrap implementation present` | `supporting targeted tests pass` | `shared-android-web manual journey on approved runtime target` | `planned` |
| `PLAN-MAP-VAL-05` | `one-time recenter when canonical user origin arrives` | `Wave1-Map-Worker` | `bootstrap handoff journey` | `one-time recenter state machine present` | `supporting targeted tests pass` | `shared-android-web manual journey on approved runtime target` | `planned` |
| `PLAN-MAP-VAL-06` | `return to the still-live map screen preserves the last camera position` | `Wave1-Map-Worker` | `live-screen camera memory journey` | `no regression to existing memory behavior` | `supporting targeted tests pass` | `shared-android-web manual journey on approved runtime target` | `planned` |
| `PLAN-MAP-VAL-07` | `tenant without default_origin shows explicit error` | `Wave1-Map-Worker` | `missing-config journey` | `explicit visible error path present` | `supporting targeted tests pass` | `shared-android-web manual journey on approved runtime target` | `planned` |
| `PLAN-MAP-D-01` | `D-01: first render of the public map must use tenant default_origin.` | `Wave1-Map-Worker` | `bootstrap contract` | `implementation preserves frozen decision` | `targeted map tests` | `shared-android-web first-open smoke` | `planned` |
| `PLAN-MAP-D-02` | `D-02: the bootstrap path must not use any hardcoded fallback center.` | `Wave1-Map-Worker` | `bootstrap contract` | `hardcoded startup fallback removed` | `targeted map tests` | `shared-android-web first-open smoke` | `planned` |
| `PLAN-MAP-D-03` | `D-03: when canonical user origin arrives during the first bootstrap, the map may recenter automatically once.` | `Wave1-Map-Worker` | `bootstrap contract` | `single-handoff logic present` | `targeted map tests` | `shared-android-web handoff smoke` | `planned` |
| `PLAN-MAP-D-04` | `D-04: after the first bootstrap, existing camera memory semantics must remain unchanged.` | `Wave1-Map-Worker` | `bootstrap contract` | `existing camera memory path preserved` | `targeted map tests` | `shared-android-web return-to-live-screen smoke` | `planned` |
| `PLAN-MAP-D-05` | `D-05: missing tenant default_origin is a visible configuration error, not a fallback scenario.` | `Wave1-Map-Worker` | `bootstrap contract` | `fail-closed error path preserved` | `targeted map tests` | `shared-android-web missing-config smoke` | `planned` |
| `PLAN-PUBLIC-DOD-01` | `Tenant-admin profile type create/edit screens expose a public-discovery toggle backed by capabilities.is_publicly_discoverable.` | `Wave2-Public-Worker` | `tenant-admin public-discovery toggle` | `domain/DTO/controller/form screen wired to existing backend capability` | `targeted tenant-admin tests in flutter-app` | `shared-android-web tenant-admin manual save/readback smoke` | `planned` |
| `PLAN-PUBLIC-DOD-02` | `is_favoritable cannot remain enabled when public discovery is disabled.` | `Wave2-Public-Worker` | `favoritable dependency toggle` | `controller/form dependency logic clears child capability` | `targeted controller/form tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-DOD-03` | `Re-enabling public discovery does not silently re-enable favorites; favorites remain operator-controlled once the parent toggle is on again.` | `Wave2-Public-Worker` | `favoritable dependency toggle` | `controller/form dependency logic keeps child opt-in manual` | `targeted controller/form tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-DOD-04` | `Flutter profile type parsing and request encoding preserve is_publicly_discoverable.` | `Wave2-Public-Worker` | `DTO and request encoding` | `tenant-admin capability round-trip implemented` | `targeted DTO/controller/form tests` | `tenant-admin save + reload smoke` | `planned` |
| `PLAN-PUBLIC-DOD-05` | `No new public-visibility flag is introduced; the UI uses the existing backend-backed capability only.` | `Wave2-Public-Worker` | `existing capability name` | `implementation stays on is_publicly_discoverable without alias` | `code review plus targeted tests` | `n/a` | `planned` |
| `PLAN-PUBLIC-DOD-06` | `The tenant-admin module docs list is_publicly_discoverable in the profile type admin request/response schemas and field definitions.` | `Wave2-Public-Worker` | `tenant_admin_module documentation` | `module contract updated in root docs` | `doc diff review` | `n/a` | `planned` |
| `PLAN-PUBLIC-DOD-07` | `Focused regression tests cover the capability dependency and disabled-state UI behavior.` | `Wave2-Public-Worker` | `tenant-admin test coverage` | `scoped tests updated in flutter-app` | `exact TODO commands must pass` | `n/a` | `planned` |
| `PLAN-PUBLIC-VAL-01` | ``fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`` | `Wave2-Public-Worker` | `DTO test suite` | `DTO capability tests updated` | `exact TODO command must pass` | `n/a` | `planned` |
| `PLAN-PUBLIC-VAL-02` | ``fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`` | `Wave2-Public-Worker` | `controller test suite` | `controller dependency tests updated` | `exact TODO command must pass` | `n/a` | `planned` |
| `PLAN-PUBLIC-VAL-03` | ``fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`` | `Wave2-Public-Worker` | `form screen test suite` | `form dependency tests updated` | `exact TODO command must pass` | `n/a` | `planned` |
| `PLAN-PUBLIC-VAL-04` | ``fvm dart analyze --format machine`` | `Wave2-Public-Worker` | `flutter analyzer gate` | `Wave 2 files analyzer-clean` | `exact analyzer command must pass` | `n/a` | `planned` |
| `PLAN-PUBLIC-VAL-05` | `edit a profile type with public discovery off and confirm favoritable is cleared/disabled` | `Wave2-Public-Worker` | `tenant-admin dependency journey` | `dependency UI implemented` | `supporting targeted tests pass` | `shared-android-web tenant-admin manual journey on approved runtime target` | `planned` |
| `PLAN-PUBLIC-VAL-06` | `turn public discovery on and confirm favoritable becomes selectable again without auto-opting-in` | `Wave2-Public-Worker` | `tenant-admin dependency journey` | `dependency UI implemented` | `supporting targeted tests pass` | `shared-android-web tenant-admin manual journey on approved runtime target` | `planned` |
| `PLAN-PUBLIC-VAL-07` | `save and reload the profile type to confirm the capability persists` | `Wave2-Public-Worker` | `tenant-admin save/readback journey` | `request encoding and DTO readback implemented` | `supporting targeted tests pass` | `shared-android-web tenant-admin manual journey on approved runtime target` | `planned` |
| `PLAN-PUBLIC-D-01` | `D-01: the tenant-admin account profile type form must expose the existing is_publicly_discoverable capability; no new public flag may be introduced for this slice.` | `Wave2-Public-Worker` | `existing capability name` | `UI uses only is_publicly_discoverable` | `targeted tenant-admin tests` | `shared-android-web tenant-admin manual smoke` | `planned` |
| `PLAN-PUBLIC-D-02` | `D-02: is_favoritable depends on is_publicly_discoverable exactly like is_reference_location_enabled depends on is_poi_enabled.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `controller/form dependency mirrors existing POI pattern` | `targeted tenant-admin tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-D-03` | `D-03: when public discovery is turned off, is_favoritable must be forced to false.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `child reset logic present` | `targeted tenant-admin tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-D-04` | `D-04: when public discovery is off, the is_favoritable control must be visibly disabled with dependency copy instead of remaining silently interactive.` | `Wave2-Public-Worker` | `favoritable disabled state` | `disabled switch plus copy present` | `targeted tenant-admin tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-D-05` | `D-05: turning public discovery back on must not auto-enable favorites.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `re-enable path stays manual` | `targeted tenant-admin tests` | `shared-android-web tenant-admin dependency smoke` | `planned` |
| `PLAN-PUBLIC-D-06` | `D-06: the Flutter admin domain, DTO, and request encoder must round-trip is_publicly_discoverable.` | `Wave2-Public-Worker` | `domain DTO request encoder` | `round-trip implementation present` | `targeted tenant-admin tests` | `tenant-admin save + reload smoke` | `planned` |
| `PLAN-PUBLIC-D-07` | `D-07: the canonical tenant-admin module docs must be updated to expose the public capability in the admin contract.` | `Wave2-Public-Worker` | `tenant_admin_module documentation` | `module contract updated` | `doc diff review` | `n/a` | `planned` |
| `PLAN-PUBLIC-OOS-01` | `Out of Scope: Changing backend public discovery semantics, seeding policy, or migration defaults.` | `Wave2-Public-Worker` | `migration n/a` | `Wave 2 stays on Flutter admin wiring and canonical docs only` | `code review confirms no backend migration or migration-default change` | `n/a` | `planned` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `PLAN-MAP` and `PLAN-PUBLIC` are functionally independent.
- `PLAN-PUBLIC` is execution-dependent on `PLAN-MAP` only because both slices touch `flutter-app` and the current Wave 1 lane is already bootstrapped and version-bumped.
- Wave 2 must start from the refreshed post-Wave-1 baseline unless the user explicitly approves mixed-lane execution.

## Orchestration Topology
- **Base branch / commit:** `fix/map-initial-origin-bootstrap-20260515` at root `1bead89`, `flutter-app` `933a8779`, `laravel-app` `785da21`, and `foundation_documentation` `98203a8` for Wave 1 planning.
- **Orchestrator reconciliation branch:** `reconcile/map-bootstrap-public-discoverability-20260515`
- **Principal checkout policy:** `the principal checkouts are the authoritative local surfaces for reconciliation and final validation`
- **Runtime-facing source checkouts:** `belluga_now_docker`, `flutter-app`, `laravel-app`, and `foundation_documentation`
- **Worker branches / worktrees:** `Wave 1 worker worktree: /home/elton/Dev/repos/belluga-ecosystem/_worktrees/flutter-wave1-map-bootstrap-20260515 on branch fix/map-initial-origin-bootstrap-20260515; Wave 2 derives fresh follow-on worker lane(s) after Wave 1 closure`
- **Derived artifact repos:** `web-app remains a generated runtime artifact only if later browser validation requires a published bundle`

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-01 Map bootstrap contract` | `flutter-app map controller, widget, repository bootstrap surfaces` | `PLAN-MAP DoD, validation, and D-01..D-05` | `Wave 1 map bootstrap checkpoint` | `map controller test, map widget test, flutter analyze` |
| `WS-02 Map runtime acceptance` | `Wave 1 runtime evidence only` | `WS-01 complete` | `Wave 1 runtime evidence packet` | `manual shared-android-web smoke on approved runtime target` |
| `WS-03 Public discoverability contract` | `flutter-app tenant-admin domain, DTO, request encoder, controller` | `PLAN-PUBLIC DoD, validation, and D-01..D-07` | `Wave 2 capability checkpoint` | `DTO test, controller test, flutter analyze` |
| `WS-04 Public discoverability UI + docs` | `flutter-app tenant-admin form screen plus root tenant_admin_module documentation` | `WS-03 complete` | `Wave 2 UI/doc checkpoint` | `form screen test, doc diff review, manual tenant-admin save/readback smoke` |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-01 Map bootstrap contract` | `Wave1-Map-Worker` | `none beyond reconciliation or merge-conflict handling` | `Wave 1 code diff plus targeted tests and analyzer` | `orchestrator verifies targeted tests and runtime smoke before closure` |
| `WS-02 Map runtime acceptance` | `Wave1-Map-Worker` | `reconciliation-only` | `manual runtime journey notes plus any required publish evidence` | `orchestrator records final Wave 1 acceptance evidence` |
| `WS-03 Public discoverability contract` | `Wave2-Public-Worker` | `none beyond reconciliation or merge-conflict handling` | `Wave 2 code diff plus DTO/controller tests and analyzer` | `orchestrator verifies targeted tests before UI/doc closure` |
| `WS-04 Public discoverability UI + docs` | `Wave2-Public-Worker` | `reconciliation-only` | `form test, doc update, manual tenant-admin save/readback notes` | `orchestrator records final Wave 2 acceptance evidence` |

## Execution Waves
Waves are orchestrator-owned internal control checkpoints, not routine user feedback gates.
After approval, the orchestrator advances autonomously between waves and stops only for a mandatory user decision, scope change, governing TODO conflict, real blocker, or explicit validation waiver.

### Wave 0 - Preflight / Approval
- Freeze this orchestration plan against the two governing TODOs.
- Keep execution serial: Wave 1 on the active map lane, Wave 2 on a fresh follow-on lane.
- Do not dispatch implementation until this plan and the active governing TODO gate are explicitly approved.
- **Gate to next wave:** orchestration plan is approved and Wave 1 map TODO is re-approved for execution.

### Wave 1 - Map bootstrap implementation
- Execute `WS-01` and `WS-02` only.
- Keep scope bounded to `TODO-v1-map-initial-origin-bootstrap.md`.
- Do not start the public-discoverability slice during this wave.
- **Gate to next wave:** Wave 1 code, targeted tests, analyzer, and runtime smoke are complete and the Wave 1 lane is ready to hand off or close.

### Wave 2 - Public discoverability follow-on
- Derive a fresh follow-on lane from the updated baseline after Wave 1.
- Execute `WS-03` and `WS-04` only.
- Keep the implementation bound to the existing backend capability name `is_publicly_discoverable`.
- **Gate to completion:** Wave 2 code, targeted tests, analyzer, runtime smoke, and tenant-admin module doc update are complete.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| `Wave 1 map targeted regression` | `map controller test, map widget test, flutter analyze` | `worker-local then principal checkout` | `Wave1-Map-Worker` |
| `Wave 1 map runtime acceptance` | `shared-android-web manual smoke for first open, one-time handoff, preserved camera, missing-config error` | `approved runtime target serving the active Wave 1 lane` | `Wave1-Map-Worker` |
| `Wave 2 public capability targeted regression` | `DTO test, controller test, form screen test, flutter analyze` | `worker-local then principal checkout` | `Wave2-Public-Worker` |
| `Wave 2 public capability runtime acceptance` | `shared-android-web manual tenant-admin dependency and save/readback smoke` | `approved runtime target serving the active Wave 2 lane` | `Wave2-Public-Worker` |
| `Wave 2 canonical docs` | `tenant_admin_module diff review against the existing backend capability contract` | `root documentation checkout` | `Wave2-Public-Worker` |

## CI-Equivalent Local Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Applies To (`worker-local|reconciliation|pre-promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / targeted map regression` | `Wave 1 changes map bootstrap controller and widget behavior` | `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart && fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart` | `worker-local` | `planned` | `exact TODO commands for Wave 1` | `Wave1-Map-Worker` |
| `flutter-app / targeted tenant-admin regression` | `Wave 2 changes tenant-admin DTO, controller, and form behavior` | `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart && fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart && fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart` | `worker-local` | `planned` | `exact TODO commands for Wave 2` | `Wave2-Public-Worker` |
| `flutter-app / analyzer` | `Both waves touch Flutter production and test surfaces` | `fvm dart analyze --format machine` | `worker-local` | `planned` | `exact analyzer command from both TODOs` | `Wave1-Map-Worker for Wave 1, Wave2-Public-Worker for Wave 2` |
| `root docs / canonical contract review` | `Wave 2 updates tenant_admin_module documentation` | `manual module diff review against backend capability contract` | `pre-promotion` | `planned` | `tenant_admin_module diff review notes` | `Wave2-Public-Worker` |

## Risk / Conflict Controls
- Shared `flutter-app` ownership is the main orchestration risk; the plan avoids hidden overlap by forcing serial waves.
- Wave 1 already owns the active version-bumped lane; Wave 2 must not piggyback on that branch without explicit user approval.
- The orchestrator must not introduce a new public-visibility alias; `is_publicly_discoverable` is the only accepted capability name for Wave 2.
- Wave 1 must not reintroduce any startup fallback center; missing `default_origin` remains a visible error.
- The orchestrator remains sequencing, reconciliation, and evidence owner only; implementation ownership stays with the wave-specific worker role named in this plan.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this orchestration plan.
- **Execution authorized by approval:** `Wave 1 map bootstrap execution on the active lane, followed by Wave 2 only after Wave 1 closure and Wave 2-specific approval alignment.`
- **Execution not authorized by approval:** `mixed-lane execution of Wave 2 on the active map branch, spec deviations, backend semantic changes, or any third slice outside the two governing TODOs.`
- **Autonomy rule:** once approved, the orchestrator advances through the serial waves without requesting feedback between waves unless a mandatory decision, blocker, or waiver appears.

## Plan Completion Guard
- **Command:** `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/map-bootstrap-public-discoverability-orchestration-plan.md`
- **Required before approval/execution:** `Overall outcome: go`

## Delivery Guard
- **Command:** `python3 delphi-ai/tools/orchestration_delivery_guard.py --plan foundation_documentation/artifacts/execution-plans/map-bootstrap-public-discoverability-orchestration-plan.md --require-approved`
- **Required before local implementation or delivery completion claim:** `Overall outcome: go`
