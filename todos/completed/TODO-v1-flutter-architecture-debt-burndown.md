# TODO (V1): Flutter Architecture Debt Burndown

**Status:** Completed (`custom_lint green` delivered)
**Owner:** Delphi
**Date:** 2026-03-23
**Complexity:** `big`

## Status Legend
- `- [ ] ⚪ Pending`
- `- [ ] 🟡 Provisional`
- `- [ ] 🟧 Local-Implemented`
- `- [ ] 🟣 Lane-Promoted`
- `- [x] ✅ Production-Ready`

## Delivery Stages
- [x] ✅ Production-Ready **Provisional**
- [x] ✅ Production-Ready **Local-Implemented**
- [x] ✅ Production-Ready **Lane-Promoted** (`n/a` for this TODO scope)
- [x] ✅ Production-Ready **Production-Ready**

## Context
Recent architecture hardening introduced new custom-lint rules and contract shifts
in controllers/repositories. Non-integration suites are currently red due to
contract drift in tests/mocks, and the lint debt lane must now be completed with
all rules active and tracked as a living cleanliness checklist.

## Goal
Establish a deterministic V1 execution lane to:
1. restore non-integration test green,
2. checkpoint the current branch state via commit/push,
3. enforce and clean all active custom-lint architectural findings (including temporarily disabled rules),
4. deliver a fresh local web build for manual validation.

## References
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `tool/belluga_custom_lint/docs/rules.md`
- `foundation_documentation/todos/completed/TODO-v1-admin-discovery-map-small-fixes.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-telemetry-architecture-review.md`

## Applicable Rule/Workflow Sources
- `delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md`

## Canonical Module Anchors
- **Primary module:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary modules/contracts:**
  - `foundation_documentation/domain_entities.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `tool/belluga_custom_lint/docs/rules.md`
- **Decision consolidation targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1 Domain Rules`, `2.1.1 Presentation DI Matrix`)
  - `tool/belluga_custom_lint/docs/rules.md` (rule catalog + treatment)

## Execution Lane Tracking
- **Local implementation branches:** `flutter-app:feature/v1-admin-discovery-map-small-fixes-followup`
- **Promotion lane path:** `n/a`
- **Lane-promoted threshold for this TODO:** `n/a`
- **Production-ready threshold for this TODO:** `n/a`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `WS-01` test recovery | `feature/v1-admin-discovery-map-small-fixes-followup@710a38bb` | `n/a` | `n/a` | `n/a` | `🟧 Local-Implemented` |
| `WS-02` checkpoint push | `feature/v1-admin-discovery-map-small-fixes-followup@7078b7f8` | `n/a` | `n/a` | `n/a` | `🟧 Local-Implemented` |
| `WS-03` lint cleanup | `feature/v1-admin-discovery-map-small-fixes-followup@cfdb9a8c` | `n/a` | `n/a` | `n/a` | `🟧 Local-Implemented` |
| `WS-04` local web handoff | `feature/v1-admin-discovery-map-small-fixes-followup@1c6e0892` | `n/a` | `n/a` | `n/a` | `✅ Production-Ready` |

## Rolling Execution Snapshot
- **Latest checkpoint pushed:** `cfdb9a8c` pushed to `origin/feature/v1-admin-discovery-map-small-fixes-followup` (later promoted through Flutter PR `#155 -> #156 -> #157`).
- **Current architecture wave (2026-03-24):**
  - canonicalized DTO-domain conversion via `DTO.toDomain()` in active repositories,
  - removed pass-through mappers (`user/thumb/artist/invite`) where no longer adding behavior,
  - updated mapper consumers/tests to avoid legacy `mapXDto` wrappers,
  - added and enabled `dto_mapper_pass_through_forbidden` custom-lint rule,
  - hardened mapper rule semantics: in `lib/infrastructure/dal/dto/mappers/**`, any method receiving DTO/primitives and returning domain payload now triggers lint (`DTO->Domain` allowed only in DTO `toDomain()`).
  - started explicit `domain_primitive_field_forbidden` burndown wave (no suppression): `InviteInviter`/`InviteContactMatch` migrated to ValueObjects, `ArtistResume.genres` migrated to `ArtistGenreValue`, `TelemetryContextSettings` constructor normalized to VO-only, and `AttributeModel` removed from domain layer (moved to presentation widgets model).
- **All rules active:** `domain_primitive_field_forbidden`, `repository_raw_payload_map_forbidden`, and `repository_raw_transport_typing_forbidden` were re-enabled in `analysis_options.yaml`.
- **Latest lint baseline (full sweep):**
  - Previous baseline marked clean with `fvm dart run custom_lint --no-watch` was invalidated on 2026-03-24 (this flag behaved as a silent no-op in the current workspace).
  - Canonical run command for this wave: `fvm dart run custom_lint` (without `--no-watch`).
  - Current canonical output: `0 findings` total.
    - `domain_primitive_field_forbidden`: `0`
    - `multi_public_class_file_warning`: `0` (`fixed`)
- **Latest non-integration validation (wave):**
  - `fvm flutter test -r compact` = `All tests passed` (`/tmp/flutter_test_non_integration_latest.log`)
  - `cd tool/belluga_custom_lint && dart test` = `All tests passed` (`/tmp/dart_test_custom_lint_latest.log`)
  - `cd packages/belluga_form_validation && fvm flutter test` = `All tests passed` (`/tmp/flutter_test_form_validation_latest.log`)
- **Rules cleaned to zero in current wave:** achieved in canonical run and treated as delivered baseline.
- **Remaining dirty rule set:** `none`.
- **Current wave delta (2026-03-24, post-reset):**
  - `multi_public_class_file_warning` resolved by splitting `experience_fields.dart` and `account_profile_fields.dart` into one-public-class-per-file with barrel exports.
  - map-domain VO hardening started (`MapNavigationTarget`, `MapRegionDefinition`, `DirectionsInfo`, `RideShareOption`) and all touched files pass targeted `flutter analyze`.
- **Active wave (2026-03-23):** expanded pagination lint to forbid `pageSize/perPage/limit` in controllers and migrated controller->repository pagination intents to backend/repository defaults (`completed locally`).
- **WS-04 local web deploy evidence:** `bash scripts/build_web.sh ../web-app dev --clean-output` = `success` (known wasm dry-run warnings only).

## Scope
- Fix current failing **non-integration** tests in app/test suites.
- Stop and escalate only if the fix requires:
  - architectural contract break,
  - objective rule workaround/suppression,
  - deliberate non-adherent exception.
- After test-green, create commit/push in the current feature branch if branch conditions are valid:
  - not detached,
  - branch tracks remote,
  - branch is suitable for continued feature delivery.
- Enable all custom-lint architecture rules currently temporarily disabled (including boundary rules under active rollout).
- Resolve all resulting findings in iterative waves.
- After each wave/rule family, rerun non-integration validations before advancing.
- Finish with `build_web.sh` local deploy handoff for manual QA.

## Out Of Scope
- Promotion lane actions (`dev/stage/main`) in this TODO.
- Integration test execution as a delivery blocker in this cycle.
- New architectural exceptions or suppressions without explicit user approval.

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** section-by-section
- **Why this level:** touches tests, lint engine rollout, branch checkpointing, and
  iterative cross-rule cleanup where one fix can regress another rule.

## Rule Inventory (Custom Lint)

### Temporarily Disabled in Root Config (must be re-enabled in WS-03)
- `domain_primitive_field_forbidden`
- `repository_raw_payload_map_forbidden`
- `repository_raw_transport_typing_forbidden`

### Full Rule Set (Plugin Canonical)
- `ui_getit_non_controller_forbidden`
- `ui_direct_repository_service_resolution_forbidden`
- `ui_cross_feature_controller_resolution_forbidden`
- `module_scoped_controller_dispose_forbidden`
- `ui_streamvalue_ownership_forbidden`
- `ui_dto_import_forbidden`
- `domain_dto_dependency_forbidden`
- `domain_json_factory_forbidden`
- `domain_primitive_field_forbidden`
- `ui_future_stream_builder_forbidden`
- `ui_navigator_usage_forbidden`
- `ui_navigation_after_await_forbidden`
- `ui_build_side_effects_forbidden`
- `ui_controller_ownership_forbidden`
- `ui_streamvalue_builder_null_check_forbidden`
- `repository_json_parsing_forbidden`
- `repository_model_stream_lifecycle_methods_required`
- `repository_model_streamvalue_nullable_required`
- `repository_registration_scope_enforced`
- `repository_registration_lifecycle_enforced`
- `repository_service_catch_return_fallback_forbidden`
- `repository_raw_payload_map_forbidden`
- `repository_raw_transport_typing_forbidden`
- `service_json_parsing_forbidden`
- `repository_inline_dto_to_domain_mapper_forbidden`
- `dto_mapper_pass_through_forbidden`
- `module_direct_getit_registration_forbidden`
- `global_ui_controller_naming_forbidden`
- `controller_buildcontext_dependency_forbidden`
- `controller_direct_navigation_forbidden`
- `controller_repository_async_model_fetch_forbidden`
- `controller_repository_pagination_arguments_forbidden`
- `controller_streamvalue_model_ownership_forbidden`
- `route_page_must_live_in_routes_folder`
- `route_path_param_requires_resolver_route`
- `screen_controller_resolution_pattern_required`
- `ui_route_param_hydration_forbidden`
- `multi_public_class_file_warning`
- `multi_widget_file_warning`
- `tenant_canonical_domain_required`
- `integration_anonymous_auth_identified_login_forbidden`

## Rule Cleanliness Protocol (Mandatory)
- A rule is only `🟩 Clean` when the latest `fvm dart run custom_lint` has **zero findings** for that rule.
- If a later change reintroduces findings for a previously clean rule, it must be flipped back to `🟥 Dirty` immediately.
- After each correction wave, rerun non-integration validations and update the checklist before moving to the next wave.
- Delivery is blocked unless all rules in this TODO are `🟩 Clean`.

## Rule Cleanliness Checklist (Living)
> 2026-03-24 note: this checklist was previously marked clean from an invalid `--no-watch` run. It is now being recalibrated against canonical runs (`fvm dart run custom_lint`) during this burndown wave.
- [x] `ui_getit_non_controller_forbidden` — `🟩 Clean`
- [x] `ui_direct_repository_service_resolution_forbidden` — `🟩 Clean`
- [x] `ui_cross_feature_controller_resolution_forbidden` — `🟩 Clean`
- [x] `module_scoped_controller_dispose_forbidden` — `🟩 Clean`
- [x] `ui_streamvalue_ownership_forbidden` — `🟩 Clean`
- [x] `ui_dto_import_forbidden` — `🟩 Clean`
- [x] `domain_dto_dependency_forbidden` — `🟩 Clean`
- [x] `domain_json_factory_forbidden` — `🟩 Clean`
- [x] `domain_primitive_field_forbidden` — `🟩 Clean`
- [x] `ui_future_stream_builder_forbidden` — `🟩 Clean`
- [x] `ui_navigator_usage_forbidden` — `🟩 Clean`
- [x] `ui_navigation_after_await_forbidden` — `🟩 Clean`
- [x] `ui_build_side_effects_forbidden` — `🟩 Clean`
- [x] `ui_controller_ownership_forbidden` — `🟩 Clean`
- [x] `ui_streamvalue_builder_null_check_forbidden` — `🟩 Clean`
- [x] `repository_json_parsing_forbidden` — `🟩 Clean`
- [x] `repository_model_stream_lifecycle_methods_required` — `🟩 Clean`
- [x] `repository_model_streamvalue_nullable_required` — `🟩 Clean`
- [x] `repository_registration_scope_enforced` — `🟩 Clean`
- [x] `repository_registration_lifecycle_enforced` — `🟩 Clean`
- [x] `repository_service_catch_return_fallback_forbidden` — `🟩 Clean`
- [x] `repository_raw_payload_map_forbidden` — `🟩 Clean`
- [x] `repository_raw_transport_typing_forbidden` — `🟩 Clean`
- [x] `service_json_parsing_forbidden` — `🟩 Clean`
- [x] `repository_inline_dto_to_domain_mapper_forbidden` — `🟩 Clean`
- [x] `dto_mapper_pass_through_forbidden` — `🟩 Clean`
- [x] `module_direct_getit_registration_forbidden` — `🟩 Clean`
- [x] `global_ui_controller_naming_forbidden` — `🟩 Clean`
- [x] `controller_buildcontext_dependency_forbidden` — `🟩 Clean`
- [x] `controller_direct_navigation_forbidden` — `🟩 Clean`
- [x] `controller_repository_async_model_fetch_forbidden` — `🟩 Clean`
- [x] `controller_repository_pagination_arguments_forbidden` — `🟩 Clean`
- [x] `controller_streamvalue_model_ownership_forbidden` — `🟩 Clean`
- [x] `route_page_must_live_in_routes_folder` — `🟩 Clean`
- [x] `route_path_param_requires_resolver_route` — `🟩 Clean`
- [x] `screen_controller_resolution_pattern_required` — `🟩 Clean`
- [x] `ui_route_param_hydration_forbidden` — `🟩 Clean`
- [x] `multi_public_class_file_warning` — `🟩 Clean`
- [x] `multi_widget_file_warning` — `🟩 Clean`
- [x] `tenant_canonical_domain_required` — `🟩 Clean`
- [x] `integration_anonymous_auth_identified_login_forbidden` — `🟩 Clean`

## Plan Review Gate (Big)

### Architecture
- Primary risk: fixing tests by muting contracts instead of aligning code and mocks.
- Required behavior: adapt tests/mocks to new canonical controller/repository contracts.

### Code Quality
- Primary risk: cascading quick-fixes in tests masking real regressions.
- Required behavior: minimal, objective, contract-faithful corrections.

### Tests
- Required baseline: all non-integration suites executed and passing.
- Required sequence:
  1. app tests (`fvm flutter test`),
  2. custom lint package tests (`dart test` in `tool/belluga_custom_lint`),
  3. package tests (`packages/belluga_form_validation`).

### Performance
- Lint cleanup must not introduce redundant fetch/rebuild behavior.
- Any refactor touching repository/controller flow must preserve StreamValue ownership rules.

### Security
- No weakening of route/auth/tenant boundaries during test/lint cleanup.

## Issue Cards

**Issue ID:** `PRG-01`  
**Severity:** High  
**Evidence:** current non-integration test failures rooted in test/mock contract drift (`initialLoadingLabelStreamValue`).  
**Why now:** no reliable baseline for subsequent lint cleanup without green tests.

Options:
- **A (Recommended):** align tests/mocks to current contracts, keep architecture intact.
- **B:** revert production contract to satisfy legacy tests.
- **C:** suppress failing tests temporarily.

Decision: choose **A**; **B/C** are blocked unless explicitly approved.

**Issue ID:** `PRG-02`  
**Severity:** High  
**Evidence:** temporarily disabled lint rules allow hidden architectural debt to persist.

Options:
- **A (Recommended):** reactivate all targeted rules and clean findings in iterative, validated waves.
- **B:** keep temporary disables and defer cleanup.
- **C:** activate only partial subset.

Decision: choose **A**.

## Failure Modes & Edge Cases
- Test green only in subset suites while another non-integration suite regresses.
- Rule reactivation exposes false positives; must calibrate rule, not suppress finding.
- Cleanup introduces architecture drift (controller owning non-local canonical state, repository bypass).

## Uncertainty Register
- **Assumptions:** current failing tests are contract-drift, not runtime product defects.
- **Unknowns:** exact volume of findings once all rules are active.
- **Confidence:** Medium.

## Decision Baseline (Frozen)
- `D-01` Restore non-integration test green before any broad lint cleanup.
- `D-02` If a test fix requires rule workaround or architecture regression, stop and request alignment.
- `D-03` After non-integration green, checkpoint via commit+push to proper feature branch.
- `D-04` Enable all temporarily disabled custom-lint architecture rules for this cleanup cycle.
- `D-05` Resolve findings in incremental waves; rerun non-integration tests after each wave.
- `D-06` No architecture-breaking workaround is allowed without explicit approval.
- `D-07` Final handoff requires successful `build_web.sh`.

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `flutter_client_experience_module.md` testing discipline |
| `D-02` | Aligned | Preserve | `tool/belluga_custom_lint/docs/rules.md` (`SEM EXCEÇÃO`) |
| `D-03` | Aligned | Preserve | branch-lane governance and existing feature flow |
| `D-04` | Aligned | Preserve | architecture debt burndown objective |
| `D-05` | Aligned | Preserve | deterministic validation checkpoint policy |
| `D-06` | Aligned | Preserve | no suppression/no workaround policy |
| `D-07` | Aligned | Preserve | local deploy verification gate |

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `FCX-03` | Flutter consumes backend contracts through repositories/adapters | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1.1`) |
| `FCX-04` | Transport boundary ownership is strict (DAO/DTO ingress) | `Preserve` | `foundation_documentation/domain_entities.md` (`Flutter domain boundary`) |
| `2.1.1 DI Matrix` | UI/controller/repository ownership rules are canonical | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` (`2.1.1`) |

## Tasks

### WS-01 Non-Integration Test Recovery
- [x] ✅ Production-Ready Fix failing non-integration tests until full suite passes.
- [x] ✅ Production-Ready Escalate immediately if any required fix violates architecture/rule objectivity.

### WS-02 Branch Checkpoint
- [x] ✅ Production-Ready Validate current branch suitability (attached, tracking, feature lane).
- [x] ✅ Production-Ready Commit and push current local state to remote feature branch.

### WS-03 Full Rule Reactivation + Cleanup
- [x] ✅ Production-Ready Reactivate all temporarily disabled custom-lint architecture rules.
- [x] ✅ Production-Ready Resolve findings wave-by-wave with objective adherent fixes.
- [x] ✅ Production-Ready After each wave, run non-integration validations to prevent inherited regressions.
- [x] ✅ Production-Ready Escalate any unavoidable architecture conflict before continuing (`no conflict required in this wave`).

### WS-04 Local Web Handoff
- [x] ✅ Production-Ready Execute `build_web.sh` successfully (`bash scripts/build_web.sh ../web-app dev --clean-output`).
- [x] ✅ Production-Ready Provide manual QA checklist handoff after local deploy.

## Validation Steps
- `fvm flutter test`
- `cd tool/belluga_custom_lint && dart test`
- `cd packages/belluga_form_validation && fvm flutter test`
- `fvm dart run custom_lint`
- `bash build_web.sh`

## Definition of Done
- [x] Non-integration suites are green (`flutter test`, custom lint package tests, form validation package tests).
- [x] Current feature branch checkpoint commit is pushed to remote.
- [x] Temporarily disabled rules are re-enabled and all custom-lint rules are `🟩 Clean`.
- [x] Rule cleanliness checklist reflects final real state (no stale clean marks).
- [x] `build_web.sh` succeeds and manual QA handoff is provided.

## Decision Adherence Validation (To Fill Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `fvm flutter test -r compact` (`/tmp/flutter_test_non_integration_latest.log`) |  |
| `D-02` | `Adherent` | `no rule suppression/workaround introduced in this wave` |  |
| `D-03` | `Adherent` | `checkpoint pushed at cfdb9a8c` |  |
| `D-04` | `Adherent` | `analysis_options.yaml + rule catalog active` |  |
| `D-05` | `Adherent` | `flutter test + custom_lint package tests + form_validation tests rerun` |  |
| `D-06` | `Adherent` | `no exception requested` |  |
| `D-07` | `Adherent` | `bash scripts/build_web.sh ../web-app dev --clean-output` | `known wasm dry-run warnings only` |

## Module Decision Consistency Validation (To Fill Before Delivery)
| Module Decision Ref | Planned Handling | Delivery Status (`Preserved`/`Superseded (Approved)`/`Regression`) | Evidence | Notes |
| --- | --- | --- | --- | --- |
| `FCX-03` | `Preserve` | `Preserved` | `repository/controller boundaries enforced by lint sweep` |  |
| `FCX-04` | `Preserve` | `Preserved` | `raw transport typing/payload map rules clean` |  |
| `2.1.1 DI Matrix` | `Preserve` | `Preserved` | `controller ownership + DI rules clean` |  |

## Delivery Confidence Gate (Required Before `✅ Production-Ready`)
- [x] **Lane promotion evidence complete:** `n/a` for this TODO scope (no promotion lane execution).
- [x] **Runtime impact classified:** `medium` (controller/repository/lint rule cleanup can impact behavior).
- [x] **Operational checks run (if runtime-impacting):**
  - [x] `fvm flutter test` (non-integration) green
  - [x] `fvm dart run custom_lint` green with all rules active
  - [x] `bash scripts/build_web.sh ../web-app dev --clean-output` successful
  - [x] manual smoke checklist handed off for local web validation
- [x] **Evidence artifacts recorded:** command outputs and checklist status in this TODO.
- [x] **Confidence stated:** `High`
- [x] **Release readiness outcome:** `Ready for closure (custom lint green delivered).`

## Module Consolidation Gate (Required Before Completion)
- [x] Canonical module docs updated with stable decisions and rule governance outcomes from this TODO.
- [x] Relevant rule/treatment updates consolidated in `tool/belluga_custom_lint/docs/rules.md`.
- [x] TODO/module cross-links updated if any decision is superseded or reframed.

## Manual QA Checklist (WS-04 Handoff)
1. Open local web bundle served from `../web-app`.
2. Validate Home initial agenda phased loading labels:
   - `Encontrando sua localização...`
   - `Buscando eventos perto de você...`
3. Navigate Home -> Map -> Home and confirm agenda cache restores immediately (no false-empty flicker before first fetch).
4. In admin account segmentation, validate backend search behavior:
   - `thales` returns matches
   - partial text filtering behavior is consistent with current backend policy
5. Open discovery/map filters and validate category chips load without infinite loading.
6. Open event/admin flows touched in this wave and confirm no analyzer/lint regressions in runtime paths.

## Approval Gate
Implementation starts only after explicit user confirmation token: **`APROVADO`**.
