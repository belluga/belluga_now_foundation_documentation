# Title
VNext: Map Filter Visual Selection Without Marker Override

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant-admin map filters currently conflate two different concepts:

- the visual used by the filter button/catalog row itself;
- the optional override that applies that visual to POI markers/items when the filter is selected.

The requested behavior is that tenant admins can select a filter icon/color/image even when marker override is off. `override_marker=false` must only mean "do not apply this visual to matching map items." The filter's own button and admin preview still use the configured visual.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `map-filter-visual-override-decoupling`
- **Why this is the right current slice:** this is one bounded map-filter contract correction with clear user-visible behavior and isolated acceptance criteria.
- **Direct-to-TODO rationale:** the request is specific, locally inspectable, and maps to one contract distinction: filter visual metadata must be preserved independently from item marker override semantics.

## Contract Boundary
- This TODO defines **WHAT** must be delivered for the filter visual/marker override split.
- Execution details may change during implementation, but the contract must preserve the distinction between filter-button visual and marker/item override.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Bugfix`, `Cross-Stack`, `User-Visible`, `Contract-Correction`, `Promotion-Lane-Pending`
- **Next exact step:** move this TODO with the validated v0.2.0+8 package into the promotion lane after individual closeout guards and the orchestration checkpoint.

## Scope
- [x] Allow tenant-admin filter visual controls to be edited even when `override_marker=false`.
- [x] Persist/read `marker_override` or equivalent filter visual metadata independently from `override_marker`.
- [x] Render tenant-admin filter catalog preview/button using the configured visual even when marker override is off.
- [x] Render tenant-public map filter buttons/chips using the configured filter visual even when marker override is off.
- [x] Keep marker/item visual override gated by `override_marker=true`.
- [x] Preserve existing fallback behavior when no filter visual is configured.
- [x] Add tests that prove filter visual display and marker override application are separate behaviors.

## Out of Scope
- [ ] Adding new icon catalogs beyond currently supported visual modes.
- [ ] Changing POI type visual precedence outside custom filter visual consumption.
- [ ] Reworking map filter query semantics.
- [ ] Changing event/account/static type registry visuals except as needed for this read/write contract.

## Dependencies & Sequencing
- [x] `DEP-01` Must preserve `MAP-07` / `MAP-10` type visual semantics in `foundation_documentation/modules/map_poi_module.md`.
- [x] `DEP-02` Should execute before broad map-filter UI polish so later work sees the corrected contract.
- [x] `DEP-03` Do not execute in parallel with `TODO-v0.2.0+8-map-filter-event-types-catalog-hydration.md`. If both map-filter TODOs are in one orchestration plan, this TODO is **Map Filter Wave 2** after event-type hydration lands and validates.

## Definition of Done
- [x] Admin can configure icon/color/image for a filter while marker override is off.
- [x] Reopening the admin screen shows the configured visual still present while `override_marker=false`.
- [x] Admin filter preview uses the configured visual regardless of override state.
- [x] Public map filter button/chip uses the configured visual regardless of override state.
- [x] POI markers/items use the configured visual only when `override_marker=true`.
- [x] Backend and Flutter DTO/domain code preserve the visual payload independently from the override boolean.
- [x] Tests fail before the fix and pass after the fix for both readback and runtime rendering semantics.

## Validation Steps
- [x] Laravel/API test for `/api/v1/map/filters` proving `marker_override` (or the replacement filter visual field) is exposed when `override_marker=false`.
- [x] Flutter tenant-admin test proving visual controls are available, save/readback works, and preview renders while override is off.
- [x] Flutter tenant-public map test proving filter button visual uses configured visual while marker rendering does not override POI markers when override is off.
- [x] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Visual contract correction affects admin persistence and public rendering. | `flutter-app`, `laravel-app`, tests | `completed`; consolidated Laravel/API, Flutter admin, Flutter public map, analyzer, and rule-matrix validation passed. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `full Plan Review Gate before APROVADO + post-validation checkpoint`
- **Why this level:** the visible fix is small, but the correct solution crosses admin UI, DTO/domain persistence, backend filter payloads, and public map marker rendering.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/map_poi_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/map_poi_module.md`

## Source Inventory Snapshot
- `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_map_filter_visual_sheet.dart`
- `flutter-app/lib/domain/tenant_admin/settings/tenant_admin_map_filter_catalog_item.dart`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/tenant_admin_settings_response_decoder.dart`
- `flutter-app/lib/domain/map/filters/poi_filter_category.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/shared/map_filter_category_icon.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/map_layers.dart`
- `laravel-app/packages/belluga/belluga_map_pois/src/Application/MapPoiQueryService.php`
- `laravel-app/app/Integration/MapPois/MapPoiSettingsAdapter.php`

## Decisions
- [x] `D-MFV-01` Filter visual metadata is independent from `override_marker`.
- [x] `D-MFV-02` `override_marker=false` only disables applying the visual to map items/markers.
- [x] `D-MFV-03` Admin previews and public filter buttons may use configured filter visuals even when marker override is off.
- [x] `D-MFV-04` Existing fallback visuals remain valid when no custom filter visual is configured.
- [x] `D-MFV-05` The two map-filter TODOs remain separate but must run in different orchestration waves, not as parallel workers.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing `marker_override` can remain the persisted visual shape if its read/write gating is corrected. | Current admin/backend code already has `marker_override` payload shape. | A new field such as `filter_visual` must be introduced and migrated. | `Medium` | `Keep as Assumption` |
| `A-02` | Public marker override paths can continue using the current gated getter while filter-button paths use a new ungated getter. | `poi_filter_category.dart` currently exposes `markerOverrideVisual`; map layers consume it for marker rendering. | Rendering code needs a deeper model refactor. | `High` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/domain/map/**`
- `flutter-app/lib/domain/tenant_admin/settings/**`
- `flutter-app/lib/infrastructure/dal/dao/tenant_admin/**`
- `flutter-app/lib/presentation/tenant_admin/settings/**`
- `flutter-app/lib/presentation/tenant_public/map/**`
- `laravel-app/app/Integration/MapPois/**`
- `laravel-app/packages/belluga/belluga_map_pois/**`
- `flutter-app/test/**`
- `laravel-app/tests/**`

### Ordered Steps
1. Add fail-first tests for admin readback, public filter button visual, and marker override non-application.
2. Decouple read/write normalization so visual metadata survives when `override_marker=false`.
3. Expose/use a filter-button visual accessor that is not gated by marker override.
4. Keep marker/item rendering on the gated override path.
5. Update module docs if the persisted payload contract changes.
6. Run focused tests and local CI-equivalent suites.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** Flutter admin widget/controller tests, Flutter public map widget/domain tests, Laravel map filter payload tests.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Admin can save visual while override is off | Admin mutation/readback | `shared-android-web` | widget + optional Playwright mutation if existing coverage supports it | `yes` | tenant-admin widget/controller test plus API readback test |
| Public button visual changes without marker override | Public map visible flow | `shared-android-web` | widget/navigation | `no` | Flutter map widget test; Playwright readonly if this TODO is promoted to runtime validation |
| Markers are not overridden when override is off | Public map visible flow | `shared-android-web` | widget/navigation | `no` | Flutter map marker/rendering test |

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Visible Action | DTO/Decoder Path | Planned Evidence |
| --- | --- | --- | --- | --- |
| `/api/v1/map/filters` custom filter payload | Flutter public map + admin settings | filter button/preview visual | map filters DTO + tenant-admin settings decoder | Laravel payload test + Flutter parser/render tests |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` map filter API tests | `/api/v1/map/filters` payload semantics and visual metadata preservation changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --laravel-test tests/Feature/Map/MapPoisControllerTest.php --laravel-test tests/Unit/Map/MapPoiQueryFormattingTest.php --laravel-test tests/Api/v1/Tenants/Media/MapFilterImageUploadTest.php` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Laravel map/filter/media tests passed in the consolidated wrapper. |
| `flutter-app` tenant-admin visual tests | Admin visual controls, readback, and preview behavior changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --flutter-test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart --flutter-test test/infrastructure/dal/dao/tenant_admin/tenant_admin_discovery_filters_settings_codec_test.dart` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Admin settings and codec/repository tests passed. |
| `flutter-app` public map rendering tests | Public filter buttons and marker override rendering semantics changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --flutter-test test/presentation/tenant/map/screens/map_screen/widgets/shared/map_filter_category_icon_test.dart --flutter-test test/presentation/tenant/map/screens/map_screen/widgets/map_layers_test.dart --flutter-test test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart --flutter-analyze` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Public map widget tests and analyzer passed. |
| `flutter_rule_matrix` architecture lint | Cross-stack map filter DTO/domain/UI paths participated in the reconciliation set. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` through the reconcile wrapper. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Rule matrix stage passed with recorded lint-code coverage. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, canonical module anchors, source inventory snapshot, flow evidence matrix, frontend/consumer matrix, and local CI-equivalent suite matrix.
- **Required orchestration wave:** `Map Filter Wave 2` when paired with event-type hydration.
- **Orchestrator-owned checks:** ensure the implementation preserves the split between filter-button visual and marker/item override before accepting worker output.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `MF-VIS` / Map Filter Wave 2 inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; execution starts only after `MF-EVT` lands, and no map query redesign or new icon catalog is authorized.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved for tactical implementation. | Approved scope, DoD, validation, and delivery gates. | Changing behavior outside the filter visual/marker override split. | Worker must keep evidence criterion-specific. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO is a serial follow-on to Map Filter Wave 1. | Worker-owned implementation and post-wave reconciliation. | Running in parallel with event-type hydration. | Orchestrator dispatches after `MF-EVT` validation. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Flutter domain, DTO, tenant-admin, and public map UI paths. | DTO-domain projection discipline and analyzer-clean state. | UI-only fixes that lose backend/DTO payload. | Worker must cover admin and public consumers. |
| `delphi-ai/rules/stacks/laravel/shared/todo-driven-execution-model-decision.md` | The slice may touch Laravel map filter API payloads. | API contract and test-backed backend behavior. | Backend changes without Laravel focused tests. | Worker must add `/api/v1/map/filters` coverage. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The contract split can regress independently in admin and public rendering. | Semantic tests for readback and marker gating. | Status-only or aggregate tests. | Worker creates Laravel and Flutter focused tests. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-MFV-01..05` | Visual metadata persists and renders for filter buttons/previews independently from marker override; marker/item override remains gated by `override_marker=true`; Map Filter Wave 2 followed Event hydration Wave 1. | passed | No map query redesign, new icon catalog, or type registry visual redesign was introduced. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Map filter visual payloads | Tenant settings ownership, media/image payload handling, and public API exposure consistency. | passed | Laravel `MapPoisControllerTest`, `MapFilterImageUploadTest`, and Flutter settings repository/codec tests. | The change preserves tenant-scoped filter settings and does not widen query permissions. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Public map filter button and marker rendering | Avoid extra marker recomputation or unbounded per-POI visual lookup when override is off. | passed | `map_filter_category_icon_test.dart`, `map_layers_test.dart`, `poi_marker_test.dart`, analyzer. | Button visual and marker override paths are explicit, so marker override remains gated. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Map Filter Wave 2 local reconciliation | CI/Copilot failure modes: API payload loss, admin readback regression, public button visual regression, marker override leakage, analyzer failures. | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | no p1 or p2 findings | Consolidated wrapper finished `promotion-ready`; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter DTO/domain/UI architecture | Scoped v0.2.0+8 Flutter scan for DTO/domain bypasses, presentation-owned persistence, imperative navigation, and build-side-effect patterns. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-flutter.json` | no p1 or p2 findings | Scanner findings were warning/review-level and triaged as fixture/infrastructure-path noise or modal-close affordances outside this wave. |
| Laravel map filter API and tenant guardrails | Scoped v0.2.0+8 Laravel scan for tenant guard bypasses, fixture domains, and validation shortcuts. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-laravel.json` | no p1 or p2 findings | Review-level findings were tenant/domain test fixtures rather than deployable host constants or guard bypasses. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Allow tenant-admin filter visual controls to be edited even when `override_marker=false`. | tenant-admin widget tests | `tenant_admin_settings_screen_test.dart`; consolidated wrapper report | Flutter tenant-admin widget test | passed | Visual controls remain available while marker override is off. |
| SCOPE-02 | Scope | Persist/read `marker_override` or equivalent filter visual metadata independently from `override_marker`. | API, codec, and repository tests | `MapPoisControllerTest.php`; `tenant_admin_discovery_filters_settings_codec_test.dart`; `tenant_admin_settings_repository_test.dart` | Laravel API tests plus Flutter repository/DTO tests | passed | Visual payload round-trips independently from the override boolean. |
| SCOPE-03 | Scope | Render tenant-admin filter catalog preview/button using the configured visual even when marker override is off. | tenant-admin widget tests | `tenant_admin_settings_screen_test.dart` | Flutter tenant-admin widget test | passed | Admin preview uses the configured visual regardless of override state. |
| SCOPE-04 | Scope | Render tenant-public map filter buttons/chips using the configured filter visual even when marker override is off. | public map widget tests | `map_filter_category_icon_test.dart`; `map_layers_test.dart` | Flutter public map widget/navigation tests | passed | Public filter controls consume the ungated filter visual. |
| SCOPE-05 | Scope | Keep marker/item visual override gated by `override_marker=true`. | public marker rendering tests | `map_layers_test.dart`; `poi_marker_test.dart` | Flutter public map widget tests | passed | Markers/items do not apply filter visual when override is off. |
| SCOPE-06 | Scope | Preserve existing fallback behavior when no filter visual is configured. | public/admin regression tests | `map_filter_category_icon_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter widget tests | passed | Existing fallback visual behavior remains covered. |
| SCOPE-07 | Scope | Add tests that prove filter visual display and marker override application are separate behaviors. | cross-stack focused tests | Laravel map filter tests plus Flutter admin/public map tests in the consolidated wrapper | Cross-stack tests | passed | Readback, preview/button rendering, and marker gating are separately asserted. |
| DOD-01 | Definition of Done | Admin can configure icon/color/image for a filter while marker override is off. | tenant-admin widget/navigation and repository mutation tests | `tenant_admin_settings_screen_test.dart`; `tenant_admin_settings_repository_test.dart` | Flutter tenant-admin widget/navigation test plus repository save mutation test | passed | Admin can save visual metadata while `override_marker=false`. |
| DOD-02 | Definition of Done | Reopening the admin screen shows the configured visual still present while `override_marker=false`. | codec/readback and widget navigation test | `tenant_admin_discovery_filters_settings_codec_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter DTO plus widget navigation test readback | passed | Saved visual metadata is decoded back into the admin screen. |
| DOD-03 | Definition of Done | Admin filter preview uses the configured visual regardless of override state. | tenant-admin widget/navigation test | `tenant_admin_settings_screen_test.dart` | Flutter tenant-admin widget/navigation test | passed | Preview rendering uses the filter visual path. |
| DOD-04 | Definition of Done | Public map filter button/chip uses the configured visual regardless of override state. | public map widget navigation test | `map_filter_category_icon_test.dart`; `map_layers_test.dart` | Flutter public map widget navigation test | passed | Button/chip visuals are not gated by marker override. |
| DOD-05 | Definition of Done | POI markers/items use the configured visual only when `override_marker=true`. | public marker tests | `map_layers_test.dart`; `poi_marker_test.dart` | Flutter public map widget tests | passed | Marker rendering remains on the gated override path. |
| DOD-06 | Definition of Done | Backend and Flutter DTO/domain code preserve the visual payload independently from the override boolean. | API/DTO/repository tests plus public widget navigation test consumer | `MapPoisControllerTest.php`; `map_filter_category_dto_test.dart`; `tenant_admin_discovery_filters_settings_codec_test.dart`; `map_filter_category_icon_test.dart` | Laravel API plus Flutter DTO/repository and widget navigation test | passed | Backend and Flutter preserve the visual payload even when override is off. |
| DOD-07 | Definition of Done | Tests fail before the fix and pass after the fix for both readback and runtime rendering semantics. | regression suite evidence | Focused Laravel/API, Flutter admin, and Flutter public map tests in the consolidated wrapper | Cross-stack regression tests | passed | Tests cover readback, admin preview, public button, and marker gating semantics. |
| VAL-01 | Validation Steps | Laravel/API test for `/api/v1/map/filters` proving `marker_override` (or the replacement filter visual field) is exposed when `override_marker=false`. | Laravel API test plus Flutter widget/navigation consumer test | `tests/Feature/Map/MapPoisControllerTest.php`; `map_filter_category_icon_test.dart` through the reconcile wrapper | Laravel API feature test plus Flutter widget/navigation test | passed | API exposes visual metadata while marker override is off and Flutter consumes it in the filter button flow. |
| VAL-02 | Validation Steps | Flutter tenant-admin test proving visual controls are available, save/readback works, and preview renders while override is off. | Flutter widget navigation test and repository mutation tests | `tenant_admin_settings_screen_test.dart`; `tenant_admin_settings_repository_test.dart`; codec test | Flutter tenant-admin widget navigation test plus repository save mutation tests | passed | Admin controls, persistence, readback, and preview are covered. |
| VAL-03 | Validation Steps | Flutter tenant-public map test proving filter button visual uses configured visual while marker rendering does not override POI markers when override is off. | Flutter public map widget navigation test | `map_filter_category_icon_test.dart`; `map_layers_test.dart`; `poi_marker_test.dart` | Flutter public map widget navigation test | passed | Public button visual and marker override behavior are independently asserted. |
| VAL-04 | Validation Steps | Analyzer/local CI-equivalent suite row completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above | Cross-stack test/analyzer wrapper | passed | Consolidated wrapper passed Laravel tests, Flutter tests, analyzer, and rule matrix. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Disposition reason:** local implementation and validation are complete, but this TODO remains in the active v0.2.0+8 package until promotion-lane movement is performed for the whole approved set.
- **Post-commit/push status:** `pending`
- **Next path/status action:** after individual closeout guards pass and the orchestration checkpoint is committed, move this TODO with the v0.2.0+8 package into `foundation_documentation/todos/promotion_lane/` or update this disposition with any real lane blocker.
