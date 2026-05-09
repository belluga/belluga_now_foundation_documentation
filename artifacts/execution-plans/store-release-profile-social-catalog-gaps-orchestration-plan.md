# Store Release Profile, Social, And Catalog Gaps Orchestration Plan

## Artifact Identity
- **Artifact type:** `orchestration_execution_plan`
- **Status:** `Approved`
- **Created:** `2026-05-01`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Approval token required before execution:** `APROVADO`
- **Approval evidence:** user replied `APROVADO` on 2026-05-02 after the T6 manual-QA addendum and refreshed matrix review. Later device/runtime QA reopened the profile persistence rows; this plan remains the active T6 authority but is no longer delivery-clean.

## Authority Boundary
- The governing TODOs define **WHAT** must be delivered and what counts as done.
- This plan defines **HOW** the reopened T6 work will be sequenced, tested, audited, reconciled, and consolidated with the active Store Release plans.
- If this plan conflicts with a governing TODO, the TODO wins and this plan must be corrected before execution.
- Implementation is authorized by the recorded `APROVADO`; remaining blockers must still be represented honestly in the traceability matrix and final delivery evidence.
- This plan does not reopen contact materialization, OTP auth, Discovery public/private boundaries, dynamic app-link domain pipeline, or T5 occurrence-taxonomy work except as regression context.

## Governing TODO Set
| ID | TODO | Role in Plan | Start Eligibility |
| --- | --- | --- | --- |
| `T6-PROFILE` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-v1-screen-user-profile-polish.md` | Authenticated `/profile` release closure: field matrix, no `Pessoas`, no `Alterado`, radius/location, phone immutability, backend metrics, persistence/readback, avatar persistence, and safe save failures. | approved and executing |
| `T6-EVENT-SHARE` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-event-share-invite-entrypoint.md` | Event detail share icon/link generates canonical occurrence-scoped invite/share code. | approved and locally/runtime validated |
| `T6-PLURAL` | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-account-profile-type-plural-settings-display.md` | Tenant-admin Account Profile Type plural label display/persistence/readback. | approved and locally/runtime validated |

## Acceptance Traceability Matrix
| Requirement ID | Source TODO / Criterion | Implementation Owner | Required Artifact / UI Marker | Implementation Evidence | Test Evidence | Runtime / Web Evidence | Status |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `T6-PROFILE-01` | Profile TODO :: `/profile` no longer renders the `Pessoas` section. | Flutter profile worker | `/profile`, `Pessoas` | `profile_screen.dart` no longer composes the section. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | dedicated ADB lane exists but is currently blocked by Flutter tool attach instability | `passed` |
| `T6-PROFILE-02` | Profile TODO :: `/profile` no longer renders the header badge `Alterado`. | Flutter profile worker | `Alterado`, profile header | `ProfileHeader` no longer exposes the badge text. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | dedicated ADB lane exists but is currently blocked by Flutter tool attach instability | `passed` |
| `T6-PROFILE-03` | Profile TODO :: Radius control matches Home behavior, but with a visible `50 km` maximum and no preference-save copy. | Flutter profile worker | radius, `50 km`, `/profile` | profile radius consumer alignment landed in `/profile`. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | ADB radius interaction smoke blocked by repeated `flutter drive` websocket attach crash | `blocked` |
| `T6-PROFILE-04` | Profile TODO :: Location selection can be driven through a map-based picker path. | Flutter profile worker | location, map picker, `/profile` | map-picker entrypoint wiring is present in `/profile`. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | ADB location-selection smoke blocked by repeated `flutter drive` websocket attach crash | `blocked` |
| `T6-PROFILE-05` | Profile TODO :: Verified phone is saved from login/OTP, displayed read-only, and protected from normal profile mutation in backend tests. | Laravel identity/profile worker + Flutter profile worker | phone, read-only, mutation rejection | read-only phone UI and backend mutation guard are in place. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | fresh login/profile device proof still pending; T2 runtime reuse not yet promoted into this plan | `blocked` |
| `T6-PROFILE-06` | Profile TODO :: Email field, `Visibilidade`, and `Alterar Senha` are absent from Store Release `/profile`. | Flutter profile worker | email, `Visibilidade`, `Alterar Senha` | legacy fields/menus removed from the screen. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | n/a | `passed` |
| `T6-PROFILE-07` | Profile TODO :: Profile header invite/social metrics are backend-backed and tested. | Laravel metrics worker + Flutter profile worker | metrics, profile header | summary/metrics payload is backend-owned. | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php`; `fvm flutter test test/infrastructure/repositories/self_profile_repository_test.dart` | device render proof blocked with the `/profile` ADB lane | `blocked` |
| `T6-PROFILE-08` | Profile TODO :: Every field that remains editable on `/profile` persists to the authenticated user's own Account Profile and rehydrates with the same values after route reopen and a fresh login/session. | Flutter profile worker + Laravel profile worker | `/profile`, persistence, relogin, readback | local implementation exists but runtime QA reproduced failure on the real save path for edited name/avatar. | prior focused Flutter/Laravel suites are now classified as false-green for closure until the real save contract is covered. | device/runtime QA reopened this row on current builds; fresh-session proof is not merely blocked, it is functionally failing today. | `reopened` |
| `T6-PROFILE-09` | Profile TODO :: The persisted profile name does not preload from the phone number when no explicit Account Profile name exists. | Flutter profile worker + Laravel profile worker | display name, phone fallback | self-profile display-name contract no longer falls back to phone. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/user/dtos/self_profile_dto_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | runtime fresh-login spot check remains coupled to the blocked `/profile` ADB lane | `blocked` |
| `T6-PROFILE-10` | Profile TODO :: Avatar/photo update persistence is verified or repaired. | Flutter profile worker + Laravel media/profile worker | avatar, `/profile` | avatar failure copy is mapped safely, but runtime QA still reports that the mutation does not persist. | prior focused Flutter/repository tests are insufficient for closure until the real save path is proven. | current device/runtime behavior reports the avatar save still failing to persist. | `reopened` |
| `T6-PROFILE-11` | Profile TODO :: Save failures never leak raw backend errors and do not leave false-saved UI state. | Flutter profile worker + Laravel profile worker | save failure, error, avatar, `/profile` | safe error mapping and local-state rollback are implemented. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/user/dtos/self_profile_dto_test.dart` | ADB failure-path proof remains blocked by the `/profile` lane instability | `passed` |
| `T6-PROFILE-12` | Profile TODO :: Focused Flutter and Laravel tests pass; analyzer and runtime evidence are recorded as required by the T6 plan. | profile validation worker | analyzer, runtime, `/profile` | profile reconciliation evidence exists, but the save-path coverage is now known to be incomplete. | focused Flutter/Laravel suites plus `fvm dart analyze --format machine` passed, but the profile save rows are false-green. | runtime evidence is not just blocked; device QA contradicts the current local closure. | `reopened` |
| `T6-PROFILE-13` | Profile TODO :: Flutter automated profile tests cover the visible field matrix, removal of `Pessoas`, removal of `Alterado`, field/menu removals, read-only phone, `50 km` radius cap, map-location behavior, editable-field persistence/readback, and avatar flow. | Flutter profile worker | `/profile`, `Pessoas`, `Alterado`, `50 km`, loading, empty | profile widget/controller suite updated. | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` | n/a | `passed` |
| `T6-PROFILE-14` | Profile TODO :: Laravel automated tests cover profile phone immutability, Account Profile persistence/readback, metrics read contract, and the consumed verified-phone contract from login/OTP. | Laravel identity/profile worker | endpoint, schema, migration, phone immutability, persistence, metrics | backend profile/identity implementation updated. | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` | n/a | `passed` |
| `T6-PROFILE-15` | Profile TODO :: Focused repository/DTO tests cover self-profile payload changes, including display-name and error-mapping behavior. | Flutter profile worker | DTO, repository, payload, error | self-profile DTO/repository implementation updated. | `fvm flutter test test/infrastructure/repositories/self_profile_repository_test.dart test/infrastructure/user/dtos/self_profile_dto_test.dart` | n/a | `passed` |
| `T6-PROFILE-16` | Profile TODO :: ADB/runtime smoke is required for `/profile` visible field matrix, `50 km` radius interaction, map-based location selection, backend metrics render, editable-field rehydrate after relogin, avatar persistence, and friendly failure behavior. | profile QA runtime worker | device, runtime, `/profile`, `50 km`, map, error | the old harness crash is no longer the only blocker; real device QA now also proves that save persistence is still broken. | supporting widget/repository/API lanes remain green but are insufficient for closure. | runtime lane is reopened by functional failure, not only harness instability. | `reopened` |
| `T6-PROFILE-17` | Profile TODO :: The `phone saved from login` row closes only with explicit T2 OTP evidence reuse or a fresh login->profile runtime proof in the current T6 wave. | profile QA runtime worker | login, OTP, readback, device | unchanged; still requires explicit runtime proof. | Laravel + Flutter support tests are green | no fresh login->profile runtime proof recorded in this wave yet | `blocked` |
| `T6-EVENT-SHARE-01` | Event share TODO :: Event detail share icon/link generates or reuses a canonical invite share code. | Flutter event/invite worker + Laravel invite worker | share icon, `/invites/share` | event-detail share path is wired through the invite factory/repository flow. | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php` | reused Android continuation proof from `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | `passed` |
| `T6-EVENT-SHARE-02` | Event share TODO :: Selected `occurrence_id` is passed through the share-generation path. | Flutter event/invite worker + Laravel invite worker | selected occurrence, `occurrence_id` | occurrence-scoped share contract is preserved. | same focused Flutter/Laravel invite suites as above | reused Android continuation proof from the occurrence-target migration lane | `passed` |
| `T6-EVENT-SHARE-03` | Event share TODO :: Repeated taps are bounded and do not leave stale loading state. | Flutter event/invite worker | loading, retry, share icon | bounded in-flight loading/retry state remains in the share controller/factory path. | focused Flutter controller/widget loading/retry tests in the event-share suite | reused Android loading-state continuation proof from the occurrence-target migration lane | `passed` |
| `T6-EVENT-SHARE-04` | Event share TODO :: Auth/web-to-app boundaries are preserved. | Flutter event/invite worker + web boundary worker | web, browser, handoff, `/agenda/evento/:slug` | share boundary alignment kept. | Laravel invite tests + `event_share_boundary.spec.js` | Playwright web boundary proof passed on `https://guarappari.belluga.space` | `passed` |
| `T6-EVENT-SHARE-05` | Event share TODO :: Generated share output preserves canonical invite attribution and route/deep-link target intent. | Flutter event/invite worker + Laravel invite worker | route, deep-link, output, `/invites/share` | canonical share output wiring preserved for route/deep-link continuation. | focused Flutter/Laravel route/deep-link tests | reused Android navigation continuation proof + Playwright web route handoff boundary proof | `passed` |
| `T6-EVENT-SHARE-06` | Event share TODO :: Focused Flutter/Laravel tests and required runtime evidence are recorded. | event-share validation worker | runtime, analyzer, share icon | event-share reconciliation evidence recorded in the governing TODO and runner artifacts. | focused Flutter/Laravel suites + analyzer | Playwright runtime captured; Android proof reused from source-owned occurrence-target migration device lane | `passed` |
| `T6-EVENT-SHARE-07` | Event share TODO :: Flutter automated event detail/share test covers selected occurrence and loading/error behavior. | Flutter event/invite worker | selected occurrence, loading, error | event-detail share implementation covered by unit/widget suites. | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart` with selected occurrence, loading, and error assertions | reused Android device continuation proof from `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | `passed` |
| `T6-EVENT-SHARE-08` | Event share TODO :: Laravel automated invite-share test covers occurrence-scoped generation if backend behavior changes or needs regression proof. | Laravel invite worker | endpoint, invite-share, occurrence, schema | backend invite-share endpoint/schema regression coverage reused. | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php` | n/a | `passed` |
| `T6-EVENT-SHARE-09` | Event share TODO :: ADB runtime evidence proves the authenticated app event-share CTA generates the canonical invite flow without duplicate/stuck state. | event-share QA runtime worker | device, runtime, authenticated app, loading | source-owned Android continuation proof remains the runtime reference for the occurrence-scoped share flow and loading-state reset. | focused supporting tests are green | `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` evidence reused from the occurrence-target migration lane | `passed` |
| `T6-PLURAL-01` | Plural TODO :: Account Profile Type create/edit UI displays plural label settings. | tenant-admin catalog worker | `labels.plural`, `/admin/profile-types/*` | plural field wiring landed in the tenant-admin form. | `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart --plain-name "renders and hydrates plural label field"` | Playwright admin mutation passed | `passed` |
| `T6-PLURAL-02` | Plural TODO :: Plural labels are sent, persisted, decoded, and shown on readback. | tenant-admin catalog worker + Laravel catalog worker | payload, readback, `labels.plural` | DTO/repository/backend round-trip preserved. | focused Flutter form/DTO tests + `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural` | Playwright admin mutation passed | `passed` |
| `T6-PLURAL-03` | Plural TODO :: Runtime/bootstrap consumers can read the saved plural label without falling back to singular when explicit plural exists. | tenant-admin catalog worker + Flutter runtime worker | bootstrap, grouped category, plural readback | runtime/bootstrap consumer alignment kept. | focused Flutter consumer/readback tests in the plural suite | Playwright browser/admin mutation readback on `https://guarappari.belluga.space` confirms the saved plural label is available to the visible runtime path | `passed` |
| `T6-PLURAL-04` | Plural TODO :: Singular compatibility alias remains intact. | tenant-admin catalog worker + Laravel catalog worker | label, singular compatibility alias | compatibility alias preserved. | focused Flutter/Laravel tests | Playwright admin mutation readback passed | `passed` |
| `T6-PLURAL-05` | Plural TODO :: Focused Flutter/Laravel tests and required runtime evidence are recorded. | plural validation worker | runtime, browser, admin | plural reconciliation evidence recorded in the governing TODO. | focused Flutter/Laravel suites + analyzer | Playwright browser/admin mutation capture recorded on `https://guarappari.belluga.space` | `passed` |
| `T6-PLURAL-06` | Plural TODO :: Flutter tenant-admin form tests cover plural field visibility and payload. | tenant-admin catalog worker | form, payload, `labels.plural` | form implementation covered. | focused Flutter form tests | Playwright browser/admin mutation exercises the visible create/edit form payload path | `passed` |
| `T6-PLURAL-07` | Plural TODO :: Flutter DTO/repository tests cover plural label encode/decode/readback. | tenant-admin catalog worker | DTO, repository, readback | DTO/repository implementation covered. | focused Flutter DTO/repository tests | Playwright browser/admin mutation readback confirms the visible admin/runtime path consumes the saved plural label | `passed` |
| `T6-PLURAL-08` | Plural TODO :: Flutter bootstrap/runtime consumer tests cover plural label readback when explicit plural is present. | Flutter runtime worker | bootstrap, runtime, readback | consumer alignment covered. | focused Flutter consumer tests | Playwright browser/admin mutation plus runtime readback confirms the visible runtime/bootstrap path sees the saved plural label | `passed` |
| `T6-PLURAL-09` | Plural TODO :: Laravel tests cover validation/storage/readback if backend changes are required. | Laravel catalog worker | endpoint, schema, migration, storage, readback | backend profile-type endpoint/schema/storage readback is covered; no additive migration was required on this branch. | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural` | n/a | `passed` |
| `T6-VALIDATION-01` | Profile TODO :: `fvm dart analyze --format machine` passes after Flutter changes. | Flutter validation worker | analyzer | reconciliation branch analyzer clean | `fvm dart analyze --format machine` | n/a | `passed` |
| `T6-VALIDATION-02` | Profile TODO :: Laravel safe runner/formatter gates pass for backend changes. | Laravel validation worker | safe runner, formatter | backend gates clean | serial `run_laravel_tests_safe.sh` executions for touched suites | n/a | `passed` |
| `T6-VALIDATION-03` | Event share TODO :: Playwright runtime evidence proves the anonymous web share boundary preserves promotion handoff and does not perform trust mutation when the share surface is web-visible. | web boundary worker | Playwright, web, browser, mutation | web boundary implementation stable | `event_share_boundary.spec.js` | browser-facing runtime proof on `https://guarappari.belluga.space` | `passed` |
| `T6-VALIDATION-04` | Plural TODO :: Playwright/admin mutation evidence is recorded for the Account Profile Type browser-visible create/edit flow. | tenant-admin QA worker | Playwright, admin, browser, form | admin mutation flow wired | `tenant_admin_profile_type_plural.mutation.spec.js` | browser-facing runtime proof on `https://guarappari.belluga.space` | `passed` |

## Spec Deviation Ledger
| Source TODO / Criterion | Original Requirement | Proposed Deviation | Approval Evidence | Status |
| --- | --- | --- | --- | --- |
| `none` | `No spec deviations approved.` | `n/a` | `n/a` | `n/a` |

## Dependency Graph
- `T6-PROFILE` can run in parallel with `T6-PLURAL` after initial code inventory because they touch different Flutter route/admin surfaces.
- `T6-EVENT-SHARE` depends on preserving current occurrence-scoped invite/share contracts from prior Store Release plans.
- `T6-PROFILE` backend phone immutability depends on the phone-OTP identity baseline but does not modify OTP challenge/verify semantics.
- Final validation must run after all three TODOs reconcile on one orchestration branch.

## Orchestration Topology
- **Base branch / commit:** current consolidated Store Release branch state after the T5 agenda/map/icon round and the latest T3 social-core repairs.
- **Orchestrator reconciliation branch:** reuse/create `orchestration/store-release-profile-social-catalog-gaps-20260501`.
- **Principal checkout policy:** principal checkout stays on the reconciliation branch for analyzer, Laravel safe runner, web build, Playwright, ADB, and guards.
- **Worker branches/worktrees:** after approval, use disjoint worker slices only if needed by ownership: profile, event-share, plural-settings, backend profile/metrics.
- **Build artifact policy:** generated `web-app` remains validation output and is not committed as source unless a promotion plan explicitly owns it.

## Workstreams
| Workstream | Ownership Boundary | Inputs / Dependencies | Output Checkpoint | Worker-Local Validation |
| --- | --- | --- | --- | --- |
| `WS-T6-A Inventory And Fail-First` | source inventory for profile, event share, plural settings, and backend routes | governing TODOs + module docs | fail-first tests or explicit rationale | `rg` inventory and failing focused tests |
| `WS-T6-B Profile Closure` | Flutter `/profile`, self-profile repository/DTO, Laravel profile/identity/metrics | `T6-PROFILE` | profile closure checkpoint | focused Flutter profile tests + Laravel profile/auth tests |
| `WS-T6-C Event Share Invite` | Flutter event detail/share action, invite repository/factory, Laravel invite share tests | `T6-EVENT-SHARE` | event-share checkpoint | focused Flutter event/share tests + Laravel invite tests |
| `WS-T6-D Profile Type Plural Settings` | tenant-admin Account Profile Type form, DTO/repository, Laravel profile type read/write | `T6-PLURAL` | plural-settings checkpoint | focused Flutter tenant-admin tests + Laravel profile type tests |
| `WS-T6-E Runtime And Audit` | Playwright/ADB for item-specific runtime behavior required by the matrix | all workstreams | runtime/audit checkpoint | Playwright after web build, required ADB smokes, audit runs |
| `WS-T6-F Reconciliation And Evidence` | merge/reconcile, module docs, TODO evidence matrices, guards | all workstreams | consolidated T6 checkpoint | analyzer, safe runner, TODO guards, orchestration guard |

## Execution Ownership Ledger
| Workstream | Implementation Owner | Orchestrator Code Scope | Worker Checkpoint Evidence | Reconciliation Evidence |
| --- | --- | --- | --- | --- |
| `WS-T6-A Inventory And Fail-First` | `Profile inventory worker` | `reconciliation-only` | inventory notes + fail-first test diffs | orchestrator reconciles approved fail-first matrix |
| `WS-T6-B Profile Closure` | `Profile closure worker` | `merge-conflict-only` | focused Flutter/Laravel profile checkpoint | orchestrator reruns analyzer, safe runner, and runtime lanes |
| `WS-T6-C Event Share Invite` | `Event share worker` | `merge-conflict-only` | focused Flutter/Laravel event-share checkpoint | orchestrator reruns Playwright/ADB share validation |
| `WS-T6-D Profile Type Plural Settings` | `Plural settings worker` | `merge-conflict-only` | focused Flutter/Laravel plural checkpoint | orchestrator reruns Playwright admin mutation validation |
| `WS-T6-E Runtime And Audit` | `QA runtime worker` | `reconciliation-only` | runtime matrix artifacts + audit outputs | orchestrator consolidates evidence into TODOs/plan |

## Execution Waves
Waves are internal orchestration controls, not routine user feedback gates. After approval, the orchestrator advances autonomously between waves and only stops for a mandatory decision, scope change, TODO conflict, blocker, or validation waiver.

### Wave 0 - Approval And Preflight
- Wait for explicit `APROVADO`.
- Verify context/readiness and branch state.
- Ingest Flutter/Laravel/test rules relevant to touched surfaces.
- **Gate to next wave:** approval recorded and touched surfaces/rules loaded.

### Wave 1 - Inventory And Fail-First Tests
- Inventory `/profile`, event-share, and plural-settings surfaces.
- Add fail-first tests for every behavior named in the governing TODOs or record a concrete non-testable rationale.
- **Gate to next wave:** fail-first test targets exist for behavior-changing items.

### Wave 2 - Parallel Implementation
- Implement `/profile` closure and backend metrics/phone protections plus Account Profile persistence/readback.
- Implement event-share invite generation path.
- Implement Account Profile Type plural field display/persistence/readback.
- **Gate to next wave:** focused worker-local tests pass.

### Wave 3 - Reconciliation And Focused Validation
- Reconcile workstreams into the orchestration branch.
- Run focused Flutter suites, Laravel safe runner suites, `fvm dart analyze --format machine`, and formatter gates.
- Update module docs for durable contract changes.
- **Gate to next wave:** local validation is green or blockers are explicit.

### Wave 4 - Runtime, Audit, And Consolidation
- Build/publish web if browser-visible surfaces changed.
- Run required Playwright and ADB lanes for item-specific runtime proof.
- Run required audit lanes.
- Complete TODO evidence matrices and rerun guards.
- **Gate to completion:** each governing TODO has item-specific evidence and remaining Store Release blockers are explicitly represented.

## Consolidated Validation Matrix
| Area | Required Evidence | Runtime Target | Owner |
| --- | --- | --- | --- |
| Profile UI/controller | no `Pessoas`, no `Alterado`, absent legacy fields/menus, read-only phone, `50 km` radius cap, map picker, metrics render, editable-field rehydrate, avatar state, save-failure safety | local Flutter tests + required ADB profile smoke | `WS-T6-B` |
| Profile backend | phone immutability, verified-phone-from-login contract, profile summary/metrics payload, Account Profile persistence/readback, avatar persistence, save-failure contract as needed | Laravel safe runner/container | `WS-T6-B` |
| Event share | selected occurrence id, invite-share generation, bounded loading/retry, web/app boundary | Flutter tests + Laravel tests + required ADB app-share proof + required Playwright web-boundary proof | `WS-T6-C` |
| Profile type plural | plural field display/save/readback, DTO/repository/backend persistence, compatibility alias | Flutter tests + Laravel tests + required Playwright admin mutation | `WS-T6-D` |
| Analyzer/formatters | `fvm dart analyze --format machine`; Laravel formatter/safe runner | local toolchain/container | `WS-T6-F` |
| Audit/guards | test-quality audit, architecture review, TODO completion guards, orchestration delivery guard | deterministic local guard | `WS-T6-F` |

## Consolidated Delivery Evidence
| Area | Required Evidence | Status | Evidence Artifact / Command | Owner |
| --- | --- | --- | --- | --- |
| Profile UI/controller | no `Pessoas`, no `Alterado`, absent legacy fields/menus, read-only phone, `50 km` radius cap, map picker, metrics render, editable-field rehydrate, avatar state, save-failure safety | blocked | `fvm flutter test test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/infrastructure/repositories/self_profile_repository_test.dart`; `integration_test/feature_profile_surface_contract_test.dart` harness fixed, but repeated `flutter drive` runs on `192.168.15.2:5555` still crashed in Flutter tool websocket attach (`flutter_10.log`, `flutter_11.log`) before runtime assertions completed | `WS-T6-B` |
| Profile backend | phone immutability, verified-phone-from-login contract, profile summary/metrics payload, Account Profile persistence/readback, avatar persistence, save-failure contract as needed | blocked | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/ApiV1TenantMeTest.php` passed locally, but final delivery stays blocked until the paired `/profile` device/runtime proof is complete | `WS-T6-B` |
| Event share | selected occurrence id, invite-share generation, bounded loading/retry, web/app boundary | passed | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php`; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line`; reused Android continuation proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | `WS-T6-C` |
| Profile type plural | plural field display/save/readback, DTO/repository/backend persistence, compatibility alias | passed | `fvm flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart --plain-name "renders and hydrates plural label field"`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --filter=plural`; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/tenant_admin_profile_type_plural.mutation.spec.js --reporter=line` | `WS-T6-D` |
| Analyzer/formatters | `fvm dart analyze --format machine`; Laravel formatter/safe runner | passed | `fvm dart analyze --format machine`; serial `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh ...` executions for the touched Laravel suites | `WS-T6-F` |
| Audit/guards | test-quality audit, architecture review, TODO completion guards, orchestration delivery guard | passed | `python3 delphi-ai/tools/todo_completion_guard.py ...` returned `go` for T3, T5, T6 event-share, and T6 plural; `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md --require-approved` returned `go`; current orchestration delivery guard remains correctly blocked only by the `/profile` traceability/runtime rows | `WS-T6-F` |

## Runtime Freshness Evidence
- Branch and commit:
  - `flutter-app` branch `orchestration/store-release-agenda-card-polish-occurrence-taxonomy-20260501`, commit `13aa264846e09d997b824f5639ca816ec5c167d8`
  - `laravel-app` branch `orchestration/store-release-agenda-card-polish-occurrence-taxonomy-20260501`, commit `2cec9e9f97035bb3b80711ae3b2546a601dc5e27`
- Build artifact and provenance:
  - browser-facing Guarappari validations used the published bundle provenance `__WEB_BUILD_SHA__=13aa2648`, matching the current `flutter-app` commit prefix for this reconciliation branch.
- Served runtime targets:
  - browser-facing runtime target `https://guarappari.belluga.space`
  - Android device target `192.168.15.2:5555` for invite-share surface smoke and reused occurrence-target continuation proof
  - Laravel local container runtime through the canonical safe runner for backend evidence
- Freshness / proof:
  - Playwright/browser specs and device-runner artifacts in this packet were recorded against the above branch/commit pair and served targets; the published web build SHA matches the current Flutter reconciliation commit prefix, and the device/browser evidence is tied to the same governing branch via the refreshed suite-reference and runner-progress artifacts.

## Risk / Conflict Controls
- Do not implement before `APROVADO`.
- Do not reintroduce tenant-public email/password or password-management UI.
- Do not make phone editable through `/profile`; phone changes require future OTP/reverification scope.
- Do not derive canonical backend/social metrics in Flutter.
- Do not allow `/profile` to fall back to the phone number as the visible display name.
- Do not keep local avatar/name optimistic state when the save failed.
- Do not change invite recipient identity or occurrence-scope semantics while wiring event share.
- Do not hardcode profile types to test plural labels or inviteability.
- Do not claim T6 solves contact materialization/performance blockers owned by the existing social plan unless that governing TODO is explicitly updated and validated.

## Approval Request
- **Requested approval:** Reply `APROVADO` to authorize this T6 orchestration plan.
- **Execution authorized by approval:** create/reuse T6 orchestration branches/worktrees, add fail-first tests, implement the three governing TODOs, run focused/browser/device/audit guards, update module docs, and consolidate evidence with the current Store Release plans.
- **Execution not authorized by approval:** production promotion, unrelated contact materialization/OTP/deep-link/discovery work, generated `web-app` source commits, broad account workspace expansion, or password/email auth restoration.
