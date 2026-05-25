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
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Bugfix`, `Cross-Stack`, `User-Visible`, `Contract-Correction`
- **Next exact step:** run TODO refinement, freeze test targets, and request `APROVADO` before implementation.

## Scope
- [ ] Allow tenant-admin filter visual controls to be edited even when `override_marker=false`.
- [ ] Persist/read `marker_override` or equivalent filter visual metadata independently from `override_marker`.
- [ ] Render tenant-admin filter catalog preview/button using the configured visual even when marker override is off.
- [ ] Render tenant-public map filter buttons/chips using the configured filter visual even when marker override is off.
- [ ] Keep marker/item visual override gated by `override_marker=true`.
- [ ] Preserve existing fallback behavior when no filter visual is configured.
- [ ] Add tests that prove filter visual display and marker override application are separate behaviors.

## Out of Scope
- [ ] Adding new icon catalogs beyond currently supported visual modes.
- [ ] Changing POI type visual precedence outside custom filter visual consumption.
- [ ] Reworking map filter query semantics.
- [ ] Changing event/account/static type registry visuals except as needed for this read/write contract.

## Dependencies & Sequencing
- [ ] `DEP-01` Must preserve `MAP-07` / `MAP-10` type visual semantics in `foundation_documentation/modules/map_poi_module.md`.
- [ ] `DEP-02` Should execute before broad map-filter UI polish so later work sees the corrected contract.
- [ ] `DEP-03` Do not execute in parallel with `TODO-v0.2.0+8-map-filter-event-types-catalog-hydration.md`. If both map-filter TODOs are in one orchestration plan, this TODO is **Map Filter Wave 2** after event-type hydration lands and validates.

## Definition of Done
- [ ] Admin can configure icon/color/image for a filter while marker override is off.
- [ ] Reopening the admin screen shows the configured visual still present while `override_marker=false`.
- [ ] Admin filter preview uses the configured visual regardless of override state.
- [ ] Public map filter button/chip uses the configured visual regardless of override state.
- [ ] POI markers/items use the configured visual only when `override_marker=true`.
- [ ] Backend and Flutter DTO/domain code preserve the visual payload independently from the override boolean.
- [ ] Tests fail before the fix and pass after the fix for both readback and runtime rendering semantics.

## Validation Steps
- [ ] Laravel/API test for `/api/v1/map/filters` proving `marker_override` (or the replacement filter visual field) is exposed when `override_marker=false`.
- [ ] Flutter tenant-admin test proving visual controls are available, save/readback works, and preview renders while override is off.
- [ ] Flutter tenant-public map test proving filter button visual uses configured visual while marker rendering does not override POI markers when override is off.
- [ ] Analyzer/local CI-equivalent suite row completed before delivery.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Visual contract correction affects admin persistence and public rendering. | `flutter-app`, `laravel-app`, tests | `planned` |

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
| Repo | CI Surface | Local Command | Required Before Delivery |
| --- | --- | --- | --- |
| `flutter-app` | analyzer + focused tests | `fvm dart analyze --format machine` and focused `fvm flutter test ...` | `yes` |
| `laravel-app` | Laravel feature tests | project safe Laravel test runner for map filter tests | `yes` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO file, canonical module anchors, source inventory snapshot, flow evidence matrix, frontend/consumer matrix, and local CI-equivalent suite matrix.
- **Required orchestration wave:** `Map Filter Wave 2` when paired with event-type hydration.
- **Orchestrator-owned checks:** ensure the implementation preserves the split between filter-button visual and marker/item override before accepting worker output.

## Completion Evidence Matrix
| Criterion | Evidence | Status | Notes |
| --- | --- | --- | --- |
| DoD + validation rows | `pending` | `planned` | Fill before any delivery claim. |
