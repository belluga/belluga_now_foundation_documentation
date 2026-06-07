# Title
v0.2.0+8: Map Filter Event Type Catalog Hydration

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
In tenant-admin map/discovery filter rule editing, selecting `Account Profile` or `Asset` correctly shows selectable `Tipos`. Selecting `Evento` incorrectly shows `Sem tipos para essa origem`.

Repository inspection shows the rule catalog builder already has an event-type input path. The likely gap is that the caller/repository/controller path does not fetch and pass current event types into the catalog, or a legacy settings sheet still uses a catalog path without event hydration.

Manual validation on 2026-06-03 added `PTODO-004`: the Account Profile type selector in map-filter configuration must list only Account Profile types that can appear on the map. Account Profile types without effective POI capability must not be selectable for map filters, because they cannot produce map POIs.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `map-filter-event-types-hydration`
- **Why this is the right current slice:** this is one bounded tenant-admin bugfix on filter catalog hydration.
- **Direct-to-TODO rationale:** expected behavior is explicit and already matches documented type parity for Account Profile, Static Asset, and Event types.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Bugfix`, `Flutter-Focused`, `Tenant-Admin`, `User-Visible`, `Promotion-Lane-Pending`
- **Next exact step:** carry this TODO in `promotion_lane/` while the remaining v0.2.0+8 package blockers finish their package-wide review loop.

## Scope
- [x] Ensure event types are fetched from the tenant-admin event type source used by the current runtime.
- [x] Pass event types into `TenantAdminDiscoveryFilterRuleCatalogBuilder` or any current equivalent catalog builder.
- [x] Ensure Event source `Tipos` lists current event types with labels/slugs consistent with existing Account Profile and Asset behavior.
- [x] Preserve empty-state behavior only when the tenant truly has no event types.
- [x] Add regression tests for Event type hydration in the filter rule sheet/catalog.
- [x] `PTODO-004` Filter Account Profile type options in map-filter authoring to effective POI-enabled Account Profile types only.

## Out of Scope
- [ ] Changing event type CRUD or registry persistence.
- [ ] Changing public map filtering/query behavior except through existing type filter payloads.
- [ ] Redesigning the filter rule sheet.
- [ ] Adding taxonomy hydration beyond the current expected type list behavior.

## Dependencies & Sequencing
- [x] `DEP-01` Preserve Event type parity documented in `map_poi_module.md` and `tenant_admin_module.md`.
- [x] `DEP-02` Do not execute in parallel with `TODO-v0.2.0+8-map-filter-visual-override-decoupling.md`. If both map-filter TODOs are in one orchestration plan, this TODO is **Map Filter Wave 1** and the visual override TODO follows as Wave 2.

## Definition of Done
- [x] Event source in the rule sheet shows event type options when event types exist.
- [x] Account Profile and Asset type lists still work.
- [x] Account Profile type lists in map-filter authoring exclude non-POI-enabled types.
- [x] Empty-state text appears for Event only when event types are genuinely absent.
- [x] Saved rules using event type filters still serialize the expected payload.
- [x] Tests cover both populated and empty Event type catalogs.

## Validation Steps
- [x] Focused Flutter test for `TenantAdminDiscoveryFilterRuleCatalogBuilder` with event types.
- [x] Focused widget/controller/repository test for the actual admin sheet path that previously showed `Sem tipos para essa origem`.
- [x] Focused widget/controller/repository test proves Account Profile type options include POI-enabled types and exclude non-POI-enabled types in the reachable map-filter authoring path.
- [x] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level:** `small`
- **Checkpoint policy:** `consolidated planning review`
- **Why this level:** this should be a focused hydration/caller-path bugfix unless discovery reveals missing backend event-type contract.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets:** `n/a unless implementation changes contract`
- **Module decision consolidation targets:** `foundation_documentation/modules/tenant_admin_module.md` if a contract gap is found.

## Source Inventory Snapshot
- `flutter-app/lib/application/tenant_admin/discovery_filters/tenant_admin_discovery_filter_rule_catalog_builder.dart`
- `flutter-app/lib/presentation/tenant_admin/discovery_filters/widgets/tenant_admin_discovery_filter_rule_sheet.dart`
- `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_discovery_filter_rule_catalog_repository.dart`
- `flutter-app/lib/presentation/tenant_admin/discovery_filters/controllers/tenant_admin_discovery_filters_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_map_filter_rule_sheet.dart`
- `flutter-app/lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart`

## Decisions
- [x] `D-MFE-01` Event types must have type-filter parity with Account Profile and Asset sources.
- [x] `D-MFE-02` The empty state is valid only for a genuinely empty event-type registry, not for missing hydration.
- [x] `D-MFE-03` Fix the data hydration/caller path before changing rule-sheet UX.
- [x] `D-MFE-04` The two map-filter TODOs remain separate but must run in different orchestration waves, not as parallel workers.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Backend event type endpoints already provide the required type list. | Tenant-admin event type CRUD exists and builder already accepts `eventTypes`. | Add backend/API work or identify a missing repository registration. | `High` | `Keep as Assumption` |
| `A-02` | The visible bug is in the current discovery filter path, not the legacy settings-only sheet. | Current filter UI has `TenantAdminDiscoveryFilterRuleCatalogBuilder` and rule sheet empty state. | Fix both sheet paths in this TODO if both are reachable. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/application/tenant_admin/discovery_filters/**`
- `flutter-app/lib/infrastructure/repositories/tenant_admin/**`
- `flutter-app/lib/presentation/tenant_admin/discovery_filters/**`
- `flutter-app/lib/presentation/tenant_admin/settings/**` if legacy map settings path is still reachable
- `flutter-app/test/**`

### Ordered Steps
1. Add fail-first tests proving Event source type options are missing on the actual sheet path.
2. Trace the catalog repository/controller path and fetch/pass event types.
3. Preserve existing account/static type behavior.
4. Add empty-event-type coverage.
5. Run focused Flutter tests and analyzer.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:** catalog builder test plus widget/controller test for the rule sheet path.

## Flow Evidence Planning Matrix
| Criterion | Flow Impact | Platform | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Event type list appears in admin filter sheet | Admin visible flow | `shared-android-web` | widget | `no` | Flutter widget/controller test |
| Event type rule serializes correctly | Admin mutation path | `shared-android-web` | widget/repository | `yes` | controller/repository test for rule payload |
| Account Profile POI-enabled type eligibility | Admin visible flow | `shared-android-web` | widget/controller/repository | `no` | Account Profile type catalog/filter option test |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app` tenant-admin discovery filter tests | Event type hydration, rule sheet rendering, empty state, serialization, and regression parity changed. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 consolidated focused validation rerun after WSL disconnect" --flutter-test test/presentation/tenant_admin/discovery_filters/tenant_admin_discovery_filters_settings_test.dart --flutter-test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart --flutter-test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart --flutter-test test/infrastructure/dal/dto/map/map_filter_category_dto_test.dart --flutter-analyze` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Consolidated wrapper reported Flutter focused tests and analyzer passed. |
| `laravel-app` map/type API regression coverage | Runtime filter/type catalog consumers depend on backend type and map filter contracts. | `./scripts/delphi/run_reconcile_validation.sh --scope big ... --laravel-test tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php --laravel-test tests/Feature/Map/MapPoisControllerTest.php --laravel-test tests/Unit/Map/MapPoiQueryFormattingTest.php` | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Backend map/type focused tests passed in the consolidated wrapper. |
| `flutter-app` Account Profile POI-enabled map-filter eligibility tests | `PTODO-004` requires the reachable admin selector to exclude Account Profile types that cannot appear on map. | `cd flutter-app && fvm flutter test --no-pub test/application/tenant_admin/discovery_filters/tenant_admin_discovery_filter_rule_catalog_builder_test.dart` | `Local-Validated` | passed | command output, 2026-06-03 | Added positive POI-enabled and negative non-POI-enabled fixture types; catalog now includes only POI-enabled Account Profile types. |
| `flutter_rule_matrix` architecture lint | Tenant-admin filter controller/repository/widget paths participated in the reconciliation set. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` through the reconcile wrapper. | `Local-Validated` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | Rule matrix stage passed with recorded lint-code coverage. |
| v0.2.0+8 final Atlas-backed reconciliation matrix | This TODO participates in the approved consolidated v0.2.0+8 package and must stay green after web/runtime lanes. | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 full CI-equivalent against Atlas-backed dev runtime" ...` | `Promotion-Lane-Pending` | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md` | Passed `atlas_runtime_db_target`, `reconcile_laravel_tests`, `reconcile_flutter_tests`, `reconcile_flutter_analyze`, `flutter_rule_matrix`, `flutter_web_build`, `web_navigation_readonly`, and `web_navigation_mutation` where applicable. |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, source inventory snapshot, validation steps, flow evidence matrix, and local CI-equivalent suite matrix.
- **Required orchestration wave:** `Map Filter Wave 1` when paired with visual override decoupling.
- **Orchestrator-owned checks:** confirm the worker fixed the actual reachable admin sheet path, not only a dormant builder.

## Reopened / Addendum Finding - 2026-06-03
- `PTODO-004`: Account Profile type options in map-filter authoring must be scoped to map-visible/POI-enabled Account Profile types. This remains inside this TODO's catalog-hydration objective because it corrects the same authoring catalog eligibility surface and does not change public map query semantics.
- Implementation must not hide non-POI types by display label or visual state only. They must be absent from the selectable filter options and covered by a negative test.

## Addendum Evidence - 2026-06-07
- Focused Flutter proof reran and passed:
  - `cd flutter-app && fvm flutter test --no-pub test/application/tenant_admin/discovery_filters/tenant_admin_discovery_filter_rule_catalog_builder_test.dart`
  - Includes `build exposes only poi enabled account profile types for map filters`.
- Authoritative browser admin-path proof reran and passed:
  - `NAV_WEB_SHARD=map-admin bash scripts/delphi/run_navigation_reconcile_validation.sh mutation`
  - Result: `1 passed`, including `@mutation tenant-admin keeps public Map filter config in the canonical filters editor`.
- Authoritative browser filter-universe proof reran and passed:
  - `NAV_WEB_SHARD=filters bash scripts/delphi/run_navigation_reconcile_validation.sh mutation`
  - Result: `5 passed`, including the strengthened Home/Discovery zero-result assertions for type and taxonomy runtime facets.

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-26T23:49:44-03:00`
- **Approval reference:** `APROVADO`
- **Approval scope:** implementation and validation of this TODO as `MF-EVT` / Map Filter Wave 1 inside `foundation_documentation/artifacts/execution-plans/v0.2.0-plus8-cross-stack-orchestration-plan.md`; no event type CRUD redesign or public map query redesign is authorized.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This TODO is now approved for tactical implementation. | Approved scope, DoD, validation, and delivery gates. | Implementing visual override behavior in this wave. | Worker must stay limited to Event type hydration. |
| `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md` | This TODO is part of a serial map-filter wave. | Map Filter Wave 1 before visual override Wave 2. | Parallel execution with the visual override TODO. | Orchestrator dispatches this worker before `MF-VIS`. |
| `delphi-ai/rules/stacks/flutter/flutter-architecture-always-on.md` | The slice touches Flutter tenant-admin controller/repository/widget paths. | Controller/repository ownership and analyzer-clean state. | Sheet-only fixes that bypass the reachable data path. | Worker must prove the actual admin sheet path. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The bug requires regression coverage for populated and empty Event type catalogs. | Fail-first or focused semantic tests. | Status-only catalog tests that miss the caller path. | Worker creates builder and sheet/controller coverage. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-MFE-01..04` | Event type hydration flows through the reachable admin filter sheet path; empty Event state is reserved for a genuinely empty registry; visual override work stayed in the separate Map Filter Wave 2. | passed | Account Profile and Asset type behavior remains covered by the same filter tests. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Tenant-admin event type catalog hydration | Tenant-scoped type data and absence of public query expansion. | passed | Flutter focused tests plus Laravel account profile type/map filter tests. | The slice reuses existing tenant-admin type sources and does not widen public map query semantics. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Admin filter rule sheet catalog build | Avoid repeated expensive rebuilds or ad hoc fetches inside widget build. | passed | Focused widget/controller/repository tests and analyzer. | Event type data is supplied through the existing controller/repository path used by the sheet. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Map Filter Wave 1 local reconciliation | CI/Copilot failure modes: missing Event type fixtures, broken Account Profile/Asset parity, empty-state regression, serialization mismatch, analyzer failures. | passed | `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260527_225033.md` | no p1 or p2 findings | Consolidated wrapper finished `promotion-ready`; remote PR/Copilot checks remain part of later promotion lane execution. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter tenant-admin architecture | Scoped v0.2.0+8 Flutter scan for widget-owned persistence, controller/repository bypasses, DTO/domain shortcutting, and build-side-effect patterns. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-flutter.json` | no p1 or p2 findings | Scanner findings were warning/review-level and triaged as fixture/infrastructure-path noise or modal-close affordances outside this wave. |
| Laravel map/type contracts | Scoped v0.2.0+8 Laravel scan for tenant guard bypasses, fixture domains, and validation shortcuts. | passed | `foundation_documentation/artifacts/tmp/v0.2.0-plus8-rule-spirit-laravel.json` | no p1 or p2 findings | Review-level findings were tenant/domain test fixtures rather than deployable host constants or guard bypasses. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Ensure event types are fetched from the tenant-admin event type source used by the current runtime. | Flutter repository/controller tests | `tenant_admin_discovery_filters_settings_test.dart`; `tenant_admin_settings_screen_test.dart`; consolidated wrapper report | Flutter tenant-admin widget/controller tests | passed | The reachable sheet path receives hydrated Event type data. |
| SCOPE-02 | Scope | Pass event types into `TenantAdminDiscoveryFilterRuleCatalogBuilder` or any current equivalent catalog builder. | catalog/widget tests | `tenant_admin_discovery_filters_settings_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter widget/controller tests | passed | Catalog builder consumers receive Event type options. |
| SCOPE-03 | Scope | Ensure Event source `Tipos` lists current event types with labels/slugs consistent with existing Account Profile and Asset behavior. | focused UI regression tests | `tenant_admin_settings_screen_test.dart`; backend type tests in consolidated wrapper | Flutter widget tests plus Laravel type tests | passed | Event type labels/slugs render alongside existing Account Profile and Asset parity. |
| SCOPE-04 | Scope | Preserve empty-state behavior only when the tenant truly has no event types. | focused empty-state test | `tenant_admin_settings_screen_test.dart` event empty-registry coverage | Flutter widget test | passed | `Sem tipos para essa origem` is retained only for empty Event registry state. |
| SCOPE-05 | Scope | Add regression tests for Event type hydration in the filter rule sheet/catalog. | focused regression tests | `tenant_admin_discovery_filters_settings_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter widget/controller tests | passed | Populated and empty Event catalogs are covered. |
| DOD-01 | Definition of Done | Event source in the rule sheet shows event type options when event types exist. | widget/navigation test | `tenant_admin_settings_screen_test.dart` | Flutter tenant-admin widget/navigation test | passed | Existing tenant Event types appear as selectable `Tipos` in the admin sheet flow. |
| DOD-02 | Definition of Done | Account Profile and Asset type lists still work. | regression tests | `tenant_admin_settings_screen_test.dart`; `tenant_admin_discovery_filters_settings_test.dart` | Flutter widget/controller tests | passed | Existing type sources remain functional. |
| DOD-03 | Definition of Done | Empty-state text appears for Event only when event types are genuinely absent. | widget empty-state test | `tenant_admin_settings_screen_test.dart` | Flutter tenant-admin widget test | passed | Empty-state copy is tied to empty registry, not missing hydration. |
| DOD-04 | Definition of Done | Saved rules using event type filters still serialize the expected payload. | repository/settings mutation tests plus widget/navigation flow | `tenant_admin_settings_repository_test.dart`; `map_filter_category_dto_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter repository/DTO mutation tests plus widget/navigation test | passed | Event type filter save mutation payload round-trips through settings and DTO paths. |
| DOD-05 | Definition of Done | Tests cover both populated and empty Event type catalogs. | focused regression widget navigation test | `tenant_admin_discovery_filters_settings_test.dart`; `tenant_admin_settings_screen_test.dart` | Flutter widget/controller navigation test | passed | Both populated Event type and empty registry paths are asserted in the admin sheet flow. |
| VAL-01 | Validation Steps | Focused Flutter test for `TenantAdminDiscoveryFilterRuleCatalogBuilder` with event types. | focused Flutter test | `tenant_admin_discovery_filters_settings_test.dart` through the reconcile wrapper | Flutter controller/catalog test | passed | Catalog-level Event type hydration is covered. |
| VAL-02 | Validation Steps | Focused widget/controller/repository test for the actual admin sheet path that previously showed `Sem tipos para essa origem`. | focused Flutter widget navigation test/controller/repository tests | `tenant_admin_settings_screen_test.dart`; `tenant_admin_settings_repository_test.dart` | Flutter tenant-admin widget navigation test/controller/repository tests | passed | Reachable admin sheet flow no longer shows the empty state for populated Event types. |
| VAL-03 | Validation Steps | Analyzer/local CI-equivalent suite row completed before delivery. | local CI-equivalent | Local CI-Equivalent Suite Matrix rows above | Cross-stack test/analyzer wrapper | passed | Consolidated wrapper passed Flutter tests, Laravel supporting tests, analyzer, and rule matrix. |
| VAL-04 | Validation Steps / 2026-06-03 addendum | Account Profile type selector for map-filter authoring excludes non-POI-enabled Account Profile types. | focused Flutter catalog test | `cd flutter-app && fvm flutter test --no-pub test/application/tenant_admin/discovery_filters/tenant_admin_discovery_filter_rule_catalog_builder_test.dart` | Flutter tenant-admin discovery filter catalog test | passed | Test fixture includes `restaurant` with POI enabled and `sponsor` without POI; only `restaurant` remains selectable. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** the package-wide review loop reran the focused Flutter proof plus authoritative `map-admin` and `filters` browser lanes on 2026-06-07 with no reopened finding on this TODO.
- **Post-commit/push status:** `pending`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and keep only package-level carry-forward validation in the orchestration ledger.
