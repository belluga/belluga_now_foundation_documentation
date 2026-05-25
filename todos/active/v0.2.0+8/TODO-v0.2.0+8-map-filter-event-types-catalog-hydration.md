# Title
VNext: Map Filter Event Type Catalog Hydration

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
In tenant-admin map/discovery filter rule editing, selecting `Account Profile` or `Asset` correctly shows selectable `Tipos`. Selecting `Evento` incorrectly shows `Sem tipos para essa origem`.

Repository inspection shows the rule catalog builder already has an event-type input path. The likely gap is that the caller/repository/controller path does not fetch and pass current event types into the catalog, or a legacy settings sheet still uses a catalog path without event hydration.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `map-filter-event-types-hydration`
- **Why this is the right current slice:** this is one bounded tenant-admin bugfix on filter catalog hydration.
- **Direct-to-TODO rationale:** expected behavior is explicit and already matches documented type parity for Account Profile, Static Asset, and Event types.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Bugfix`, `Flutter-Focused`, `Tenant-Admin`, `User-Visible`
- **Next exact step:** run TODO refinement, identify the exact caller path, add fail-first coverage, and request `APROVADO`.

## Scope
- [ ] Ensure event types are fetched from the tenant-admin event type source used by the current runtime.
- [ ] Pass event types into `TenantAdminDiscoveryFilterRuleCatalogBuilder` or any current equivalent catalog builder.
- [ ] Ensure Event source `Tipos` lists current event types with labels/slugs consistent with existing Account Profile and Asset behavior.
- [ ] Preserve empty-state behavior only when the tenant truly has no event types.
- [ ] Add regression tests for Event type hydration in the filter rule sheet/catalog.

## Out of Scope
- [ ] Changing event type CRUD or registry persistence.
- [ ] Changing public map filtering/query behavior except through existing type filter payloads.
- [ ] Redesigning the filter rule sheet.
- [ ] Adding taxonomy hydration beyond the current expected type list behavior.

## Dependencies & Sequencing
- [ ] `DEP-01` Preserve Event type parity documented in `map_poi_module.md` and `tenant_admin_module.md`.
- [ ] `DEP-02` Do not execute in parallel with `TODO-v0.2.0+8-map-filter-visual-override-decoupling.md`. If both map-filter TODOs are in one orchestration plan, this TODO is **Map Filter Wave 1** and the visual override TODO follows as Wave 2.

## Definition of Done
- [ ] Event source in the rule sheet shows event type options when event types exist.
- [ ] Account Profile and Asset type lists still work.
- [ ] Empty-state text appears for Event only when event types are genuinely absent.
- [ ] Saved rules using event type filters still serialize the expected payload.
- [ ] Tests cover both populated and empty Event type catalogs.

## Validation Steps
- [ ] Focused Flutter test for `TenantAdminDiscoveryFilterRuleCatalogBuilder` with event types.
- [ ] Focused widget/controller/repository test for the actual admin sheet path that previously showed `Sem tipos para essa origem`.
- [ ] Analyzer/local CI-equivalent suite row completed before delivery.

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

## Local CI-Equivalent Suite Matrix
| Repo | CI Surface | Local Command | Required Before Delivery |
| --- | --- | --- | --- |
| `flutter-app` | analyzer + focused tests | `fvm dart analyze --format machine` and focused `fvm flutter test ...` | `yes` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, source inventory snapshot, validation steps, flow evidence matrix, and local CI-equivalent suite matrix.
- **Required orchestration wave:** `Map Filter Wave 1` when paired with visual override decoupling.
- **Orchestrator-owned checks:** confirm the worker fixed the actual reachable admin sheet path, not only a dormant builder.

## Completion Evidence Matrix
| Criterion | Evidence | Status | Notes |
| --- | --- | --- | --- |
| DoD + validation rows | `pending` | `planned` | Fill before any delivery claim. |
