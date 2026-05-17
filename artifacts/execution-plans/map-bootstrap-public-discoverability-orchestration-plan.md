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
| `PLAN-MAP` | `foundation_documentation/todos/promotion_lane/TODO-v1-map-initial-origin-bootstrap.md` | `independent / Wave 1` | `implementation complete; remaining closure is promotion-lane runtime evidence` |
| `PLAN-PUBLIC` | `foundation_documentation/todos/promotion_lane/TODO-v1-account-profile-type-public-capability-admin-ui.md` | `independent / Wave 2 follow-on` | `implementation complete; remaining closure is promotion-lane runtime evidence` |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `PLAN-MAP-DOD-01` | `The public map no longer opens from the hardcoded Guarapari center.` | `Wave1-Map-Worker` | `public map initial camera bootstrap` | `public map bootstrap path now derives initial center from the resolved tenant default origin in worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532 and reconcile commit cfe6db19` | `reconciled public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no approved shared-android-web runtime smoke packet was recorded for the public map first-open journey on the reconciled branch` | `blocked` |
| `PLAN-MAP-DOD-02` | `First render opens from tenant default_origin.` | `Wave1-Map-Worker` | `first-render map center` | `public map first-render bootstrap now reads tenant default origin through the controller/bootstrap path in cfe6db19` | `reconciled public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no approved shared-android-web runtime smoke packet was recorded for the exact public map first-render journey before canonical location resolution` | `blocked` |
| `PLAN-MAP-DOD-03` | `Canonical origin handoff auto-recenters only during the first bootstrap and does not reset the later live-screen camera memory behavior.` | `Wave1-Map-Worker` | `one-time bootstrap recenter contract` | `public map bootstrap handoff state landed in worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532 and reconcile commit cfe6db19` | `reconciled public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no approved shared-android-web runtime smoke packet was recorded for the public map handoff and preserved-camera journeys` | `blocked` |
| `PLAN-MAP-DOD-04` | `Missing tenant default_origin produces an explicit visible error instead of silently falling back.` | `Wave1-Map-Worker` | `map error state` | `public map error state landed in worker checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532 and reconcile commit cfe6db19` | `reconciled public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: the public map missing-config journey was not executed on a browser/device runtime because canonical navigation preflight halted before Playwright/manual smoke` | `blocked` |
| `PLAN-MAP-DOD-05` | `Focused Flutter regression tests cover the bootstrap center contract and the missing-config failure path.` | `Wave1-Map-Worker` | `targeted Flutter tests` | `scoped public map bootstrap tests updated in checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532` | `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`, `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`, and `fvm dart analyze --format machine` passed on reconciled flutter-app@b33b7d55 | `runtime blocker is recorded under wave 1 map runtime acceptance; no browser/device public map smoke reached execution` | `passed` |
| `PLAN-MAP-VAL-01` | ``fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`` | `Wave1-Map-Worker` | `controller test suite` | `public map controller test suite updated in checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 1 map runtime acceptance; this row is the controller-suite gate for the public map slice` | `passed` |
| `PLAN-MAP-VAL-02` | ``fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`` | `Wave1-Map-Worker` | `widget test suite` | `public map widget test suite updated in checkpoint 31e0ed83540dc4d5869584e8389bc2773f890532` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 1 map runtime acceptance; this row is the widget-suite gate for the public map slice` | `passed` |
| `PLAN-MAP-VAL-03` | ``fvm dart analyze --format machine`` | `Wave1-Map-Worker` | `flutter analyzer gate` | `public map bootstrap production/test files are analyzer-clean on reconcile` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 1 map runtime acceptance; this row is the analyzer gate for the public map slice` | `passed` |
| `PLAN-MAP-VAL-04` | `first map open with tenant default origin before canonical user location resolves` | `Wave1-Map-Worker` | `public map first-open journey` | `public map bootstrap implementation is present in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no canonical runtime/browser evidence was collected for the exact first-open public map journey` | `blocked` |
| `PLAN-MAP-VAL-05` | `one-time recenter when canonical user origin arrives` | `Wave1-Map-Worker` | `bootstrap handoff journey` | `public map one-time recenter state machine is present in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no canonical runtime/browser evidence was collected for the one-time public map handoff journey` | `blocked` |
| `PLAN-MAP-VAL-06` | `return to the still-live map screen preserves the last camera position` | `Wave1-Map-Worker` | `live-screen camera memory journey` | `public map bootstrap change preserved the controller camera-memory path in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no canonical runtime/browser evidence was collected for the preserved public map camera-memory journey` | `blocked` |
| `PLAN-MAP-VAL-07` | `tenant without default_origin shows explicit error` | `Wave1-Map-Worker` | `missing-config journey` | `public map fail-closed missing-config error path is present in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no canonical runtime/browser evidence was collected for the missing-config public map error journey` | `blocked` |
| `PLAN-MAP-D-01` | `D-01: first render of the public map must use tenant default_origin.` | `Wave1-Map-Worker` | `bootstrap contract` | `public map bootstrap contract now reads tenant default origin through the controller bootstrap path in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no fresh public map runtime smoke packet proves the first-render journey on the reconciled branch` | `blocked` |
| `PLAN-MAP-D-02` | `D-02: the bootstrap path must not use any hardcoded fallback center.` | `Wave1-Map-Worker` | `bootstrap contract` | `public map bootstrap no longer consumes the hardcoded startup center in the first-render path at cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no fresh public map runtime smoke packet proves the exact startup journey on the reconciled branch` | `blocked` |
| `PLAN-MAP-D-03` | `D-03: when canonical user origin arrives during the first bootstrap, the map may recenter automatically once.` | `Wave1-Map-Worker` | `bootstrap contract` | `public map single-handoff logic is present in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no fresh public map runtime smoke packet proves the one-time recenter journey on the reconciled branch` | `blocked` |
| `PLAN-MAP-D-04` | `D-04: after the first bootstrap, existing camera memory semantics must remain unchanged.` | `Wave1-Map-Worker` | `bootstrap contract` | `public map controller keeps post-bootstrap camera-memory state in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no fresh public map runtime smoke packet proves the preserved camera-memory journey on the reconciled branch` | `blocked` |
| `PLAN-MAP-D-05` | `D-05: missing tenant default_origin is a visible configuration error, not a fallback scenario.` | `Wave1-Map-Worker` | `bootstrap contract` | `public map visible configuration-error path is present in cfe6db19` | `supporting public map controller/widget suites passed at flutter-app@b33b7d55` | `blocked: no fresh public map runtime smoke packet proves the missing-config journey on the reconciled branch` | `blocked` |
| `PLAN-PUBLIC-DOD-01` | `Tenant-admin profile type create/edit screens expose a public-discovery toggle backed by capabilities.is_publicly_discoverable.` | `Wave2-Public-Worker` | `tenant-admin public-discovery toggle` | `tenant-admin profile-type public-discovery toggle landed in worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d and reconcile commit b33b7d55` | `reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no approved mutation/browser evidence was recorded for editing the tenant-admin public-discovery toggle on the reconciled branch` | `blocked` |
| `PLAN-PUBLIC-DOD-02` | `is_favoritable cannot remain enabled when public discovery is disabled.` | `Wave2-Public-Worker` | `favoritable dependency toggle` | `tenant-admin favoritable dependency toggle landed in worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d and reconcile commit b33b7d55` | `reconciled controller/form suites passed at flutter-app@b33b7d55` | `blocked: no approved mutation/browser evidence was recorded for the force-clear and disable tenant-admin journey` | `blocked` |
| `PLAN-PUBLIC-DOD-03` | `Re-enabling public discovery does not silently re-enable favorites; favorites remain operator-controlled once the parent toggle is on again.` | `Wave2-Public-Worker` | `favoritable dependency toggle` | `tenant-admin re-enable-without-auto-restore logic landed in worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d and reconcile commit b33b7d55` | `reconciled controller/form suites passed at flutter-app@b33b7d55` | `blocked: no approved mutation/browser evidence was recorded for the tenant-admin re-enable journey` | `blocked` |
| `PLAN-PUBLIC-DOD-04` | `Flutter profile type parsing and request encoding preserve is_publicly_discoverable.` | `Wave2-Public-Worker` | `DTO and request encoding` | `tenant-admin profile-type DTO and request encoding round-trip landed in worker checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d and reconcile commit b33b7d55` | `reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no approved mutation/browser save-and-reload evidence was recorded for the tenant-admin profile-type journey` | `blocked` |
| `PLAN-PUBLIC-DOD-05` | `No new public-visibility flag is introduced; the UI uses the existing backend-backed capability only.` | `Wave2-Public-Worker` | `existing capability name` | `tenant-admin UI, DTO, and request encoder use only capabilities.is_publicly_discoverable in b33b7d55` | `reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; no tenant-admin mutation/browser evidence was collected yet` | `passed` |
| `PLAN-PUBLIC-DOD-06` | `The tenant-admin module docs list is_publicly_discoverable in the profile type admin request/response schemas and field definitions.` | `Wave2-Public-Worker` | `tenant_admin_module documentation` | `tenant_admin request/response schema and field-definition documentation landed in foundation checkpoint 2c83c4cbd676b512d9c667c08f6ada428d67e14e and reconcile commit bcc77d2` | `manual doc diff review passed against the Laravel capability contract` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; schema/doc review passed but no tenant-admin mutation/browser evidence was collected yet` | `passed` |
| `PLAN-PUBLIC-DOD-07` | `Focused regression tests cover the capability dependency and disabled-state UI behavior.` | `Wave2-Public-Worker` | `tenant-admin test coverage` | `scoped tenant-admin capability regression tests updated in checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d` | `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`, `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`, `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`, and `fvm dart analyze --format machine` passed on reconciled flutter-app@b33b7d55 | `runtime blocker is recorded under wave 2 public capability runtime acceptance; no tenant-admin mutation/browser evidence was collected yet` | `passed` |
| `PLAN-PUBLIC-VAL-01` | ``fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`` | `Wave2-Public-Worker` | `DTO test suite` | `tenant-admin DTO test suite updated in checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; this row is the DTO-suite gate for the tenant-admin slice` | `passed` |
| `PLAN-PUBLIC-VAL-02` | ``fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`` | `Wave2-Public-Worker` | `controller test suite` | `tenant-admin controller test suite updated in checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; this row is the controller-suite gate for the tenant-admin slice` | `passed` |
| `PLAN-PUBLIC-VAL-03` | ``fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`` | `Wave2-Public-Worker` | `form screen test suite` | `tenant-admin form-screen test suite updated in checkpoint 09e08c9af89c3ae9573a39ef775efd13f63bdb7d` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; this row is the form-suite gate for the tenant-admin slice` | `passed` |
| `PLAN-PUBLIC-VAL-04` | ``fvm dart analyze --format machine`` | `Wave2-Public-Worker` | `flutter analyzer gate` | `tenant-admin production/test files are analyzer-clean on reconcile` | `passed on reconciled flutter-app@b33b7d55` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; this row is the analyzer gate for the tenant-admin slice` | `passed` |
| `PLAN-PUBLIC-VAL-05` | `edit a profile type with public discovery off and confirm favoritable is cleared/disabled` | `Wave2-Public-Worker` | `tenant-admin dependency journey` | `tenant-admin disable-and-clear dependency journey is implemented in b33b7d55` | `supporting reconciled controller/form suites passed at flutter-app@b33b7d55` | `blocked: no canonical mutation/browser evidence was collected for the exact tenant-admin dependency journey` | `blocked` |
| `PLAN-PUBLIC-VAL-06` | `turn public discovery on and confirm favoritable becomes selectable again without auto-opting-in` | `Wave2-Public-Worker` | `tenant-admin dependency journey` | `tenant-admin re-enable-without-auto-opt-in journey is implemented in b33b7d55` | `supporting reconciled controller/form suites passed at flutter-app@b33b7d55` | `blocked: no canonical mutation/browser evidence was collected for the exact tenant-admin re-enable journey` | `blocked` |
| `PLAN-PUBLIC-VAL-07` | `save and reload the profile type to confirm the capability persists` | `Wave2-Public-Worker` | `tenant-admin save/readback journey` | `tenant-admin request/response persistence path is implemented in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no canonical mutation/browser evidence was collected for the tenant-admin save-and-reload journey` | `blocked` |
| `PLAN-PUBLIC-D-01` | `D-01: the tenant-admin account profile type form must expose the existing is_publicly_discoverable capability; no new public flag may be introduced for this slice.` | `Wave2-Public-Worker` | `existing capability name` | `tenant-admin profile-type form exposes only capabilities.is_publicly_discoverable in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the tenant-admin profile-type edit surface` | `blocked` |
| `PLAN-PUBLIC-D-02` | `D-02: is_favoritable depends on is_publicly_discoverable exactly like is_reference_location_enabled depends on is_poi_enabled.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `tenant-admin dependency toggle pattern mirrors the POI/reference-location pairing in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the tenant-admin dependency pattern` | `blocked` |
| `PLAN-PUBLIC-D-03` | `D-03: when public discovery is turned off, is_favoritable must be forced to false.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `tenant-admin child reset logic is present in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the force-clear tenant-admin journey` | `blocked` |
| `PLAN-PUBLIC-D-04` | `D-04: when public discovery is off, the is_favoritable control must be visibly disabled with dependency copy instead of remaining silently interactive.` | `Wave2-Public-Worker` | `favoritable disabled state` | `tenant-admin favoritable disabled-state copy and control behavior are present in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the visible disabled-state tenant-admin journey` | `blocked` |
| `PLAN-PUBLIC-D-05` | `D-05: turning public discovery back on must not auto-enable favorites.` | `Wave2-Public-Worker` | `dependency toggle pattern` | `tenant-admin re-enable path stays operator-controlled in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the tenant-admin re-enable journey` | `blocked` |
| `PLAN-PUBLIC-D-06` | `D-06: the Flutter admin domain, DTO, and request encoder must round-trip is_publicly_discoverable.` | `Wave2-Public-Worker` | `domain DTO request encoder` | `tenant-admin domain/DTO/request-encoder round-trip is present in b33b7d55` | `supporting reconciled DTO/controller/form suites passed at flutter-app@b33b7d55` | `blocked: no mutation/browser evidence was recorded for the tenant-admin save-and-reload journey` | `blocked` |
| `PLAN-PUBLIC-D-07` | `D-07: the canonical tenant-admin module docs must be updated to expose the public capability in the admin contract.` | `Wave2-Public-Worker` | `tenant_admin_module documentation` | `canonical tenant_admin request/response schema documentation is present in bcc77d2` | `manual doc diff review passed against the Laravel capability contract` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; documentation passed but no tenant-admin mutation/browser evidence was collected yet` | `passed` |
| `PLAN-PUBLIC-OOS-01` | `Out of Scope: Changing backend public discovery semantics, seeding policy, or migration defaults.` | `Wave2-Public-Worker` | `migration n/a` | `Wave 2 remained Flutter admin wiring plus canonical docs only; no backend migration/default change landed between 933a8779 and b33b7d55` | `code review over b33b7d55 + bcc77d2 confirmed no backend migration/default edits` | `runtime blocker is recorded under wave 2 public capability runtime acceptance; no tenant-admin mutation/browser evidence was collected yet` | `passed` |

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
- **Worker branches / worktrees:** `Wave 1 worker worktree: /home/elton/Dev/repos/belluga-ecosystem/_worktrees/flutter-wave1-map-bootstrap-20260515 on branch fix/map-initial-origin-bootstrap-20260515; Wave 2 Flutter worker worktree: /home/elton/Dev/repos/belluga-ecosystem/_worktrees/flutter-wave2-public-discoverability-20260515 on branch worker/public-discoverability-wave2-20260515; Wave 2 foundation worker worktree: /home/elton/Dev/repos/belluga-ecosystem/_worktrees/foundation-wave2-public-discoverability-20260515 on branch worker/public-discoverability-wave2-20260515`
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
| `flutter-app / targeted map regression` | `Wave 1 changes map bootstrap controller and widget behavior` | `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart && fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart` | `reconciliation` | `passed` | `both commands rerun and passed on reconciled flutter-app@b33b7d55 on 2026-05-15` | `Orchestrator` |
| `flutter-app / targeted tenant-admin regression` | `Wave 2 changes tenant-admin DTO, controller, and form behavior` | `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart && fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart && fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart` | `reconciliation` | `passed` | `all three commands rerun and passed on reconciled flutter-app@b33b7d55 on 2026-05-15` | `Orchestrator` |
| `flutter-app / analyzer` | `Both waves touch Flutter production and test surfaces` | `fvm dart analyze --format machine` | `reconciliation` | `passed` | `passed on reconciled flutter-app@b33b7d55 on 2026-05-15` | `Orchestrator` |
| `root docs / canonical contract review` | `Wave 2 updates tenant_admin_module documentation` | `manual module diff review against backend capability contract` | `reconciliation` | `passed` | `tenant_admin_module diff 8992faa..bcc77d2 reviewed against the existing Laravel capability contract on 2026-05-15` | `Orchestrator` |

## Consolidated Delivery Evidence
| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| `wave 1 map targeted regression` | `map controller test, map widget test, flutter analyze` | `passed` | `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`, `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart`, `fvm dart analyze --format machine` on reconciled flutter-app@b33b7d55 | `Orchestrator` |
| `wave 1 map runtime acceptance` | `shared-android-web manual smoke for first open, one-time handoff, preserved camera, missing-config error` | `blocked` | `2026-05-15: ./scripts/delphi/run_navigation_reconcile_validation.sh readonly blocked after temporary local +x unlock for tools/flutter/run_web_navigation_smoke.sh with: app service is not mounted from the principal checkout path: /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app` | `Orchestrator` |
| `wave 2 public capability targeted regression` | `DTO test, controller test, form screen test, flutter analyze` | `passed` | `fvm flutter test test/infrastructure/dal/dto/tenant_admin/tenant_admin_profile_type_dto_test.dart`, `fvm flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart`, `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart`, `fvm dart analyze --format machine` on reconciled flutter-app@b33b7d55 | `Orchestrator` |
| `wave 2 public capability runtime acceptance` | `shared-android-web manual tenant-admin dependency and save/readback smoke` | `blocked` | `No mutation/browser run reached Playwright because the canonical reconcile navigation preflight is blocked on the principal app bind-mount mismatch before runtime execution` | `Orchestrator` |
| `wave 2 canonical docs` | `tenant_admin_module diff review against the existing backend capability contract` | `passed` | `manual review of foundation_documentation/modules/tenant_admin_module.md in reconcile commit bcc77d2 against Laravel capability sources on 2026-05-15` | `Orchestrator` |

## Runtime Freshness Evidence
- **Status:** `blocked`
- **Reconcile branches / commits in scope:** root `reconcile/map-bootstrap-public-discoverability-20260515`; `flutter-app@b33b7d55`; `laravel-app@reconcile/map-bootstrap-public-discoverability-20260515`; `foundation_documentation@bcc77d2`
- **Intended browser-facing targets:** `NAV_LANDLORD_URL=https://belluga.space`, `NAV_TENANT_URL=https://guarappari.belluga.space` from `.env.local.navigation`
- **Freshness proof collected:** none
- **Blocker detail:** canonical preflight `./scripts/delphi/run_navigation_reconcile_validation.sh readonly` halted on 2026-05-15 before browser execution. First failure in repo state: `tools/flutter/run_web_navigation_smoke.sh` lacks executable bit required by the preflight; after a temporary local unlock strictly for diagnosis, the same preflight halted on runtime topology because the Docker `app` service bind mount did not resolve to `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app`.
- **Result:** no published reconciled web bundle provenance (`__WEB_BUILD_SHA__`) or browser/device runtime smoke was captured for this plan.

## Runtime Surface Preflight
- **Principal runtime target already in use:** `https://belluga.space` and `https://guarappari.belluga.space` via `.env.local.navigation`
- **Bind-mount / served-source proof:** `docker compose ps` confirmed `app` and `nginx` are up on 2026-05-15; `docker inspect belluga_now_docker-nginx-1` showed direct mounts from `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app` and `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app`; `docker inspect belluga_now_docker-app-1` exposed Docker Desktop translated bind-mount sources instead of the principal checkout paths, which caused reconcile preflight to block`
- **Navigation env source:** `.env.local.navigation`
- **Auxiliary runtime required?:** `blocked until the canonical reconcile preflight accepts the principal runtime surface; do not substitute an ad hoc browser lane`

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
