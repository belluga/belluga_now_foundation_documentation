# TODO (V1): Tenant Admin — Map Filters Editor UI

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Completed (Production‑Ready; manual smoke confirmed on 2026-03-13)  
**Owners:** Flutter Team (+ backend settings schema support)  
**Objective:** Enable tenant-admin editing of map filter catalog (label + image + order) under `map_ui`, reusing the existing tenant-admin image ingestion/crop pipeline with square `1024x1024` constraints.

---

## References
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`

## Canonical Module Anchors
- **Primary module:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary modules/contracts:**
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Promotion targets after delivery:**
  - `foundation_documentation/modules/tenant_admin_module.md` (local-preferences contract and settings payload)
  - `foundation_documentation/modules/map_poi_module.md` (`map_ui` filter catalog contract)
  - `foundation_documentation/endpoints_mvp_contracts.md` (`GET/PATCH settings` + `/map/filters` payload notes)
  - `foundation_documentation/system_roadmap.md` (status tracking if backend schema exposure changes)

## Scope
- Add tenant-admin local-preferences UI section to manage map filter catalog entries.
- Each filter entry supports:
  - stable filter key (`category` key used by map contract)
  - display label
  - image selection via existing tenant-admin image source/crop flow
  - list order by visual order (no separate order field)
- Enforce square image crop/prepare constraints (`1:1`, `1024x1024`) through existing ingestion service slot behavior.
- Persist catalog under `map_ui` settings namespace using the settings-kernel update path.

## Out of Scope
- Web-app changes.
- New map marker visual redesign.
- SSE for map filters.
- Full map public filter rendering refactor in this TODO (tracked in map frontend TODO).

## Complexity Classification + Checkpoint Policy
- **Complexity:** `medium`
- **Checkpoint policy:**
  1. Contract + settings shape alignment
  2. Controller/repository/domain wiring
  3. Local-preferences UI integration + image pipeline
  4. Tests + analyzer + adherence validation

## Plan Review Gate (Medium)

### Issue Cards

#### Issue ID: TAF-01
- **Severity:** High
- **Evidence:** `laravel-app/packages/belluga/belluga_map_pois/src/MapPoisServiceProvider.php` (`map_ui` fields currently do not register a filter catalog path)
- **Why now:** Without registered settings field path, `PATCH /admin/api/v1/settings/values/map_ui` rejects unknown keys (`422`), blocking persistence.
- **Option A:** Keep UI-only draft without persistence.
  - Effort: low
  - Risk: high
  - Blast radius: medium
  - Maintenance burden: high
- **Option B (Recommended):** Add `map_ui.filters` registered field (array) and persist via canonical settings-kernel endpoint.
  - Effort: medium
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Create a separate ad-hoc endpoint for map filters.
  - Effort: high
  - Risk: medium
  - Blast radius: high
  - Maintenance burden: high

#### Issue ID: TAF-02
- **Severity:** High
- **Evidence:** `lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_visual_identity_screen.dart`; `lib/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service.dart`
- **Why now:** New filter images must follow the same admin upload/crop UX and size constraints.
- **Option A:** Manual URL input only (no image pipeline reuse).
  - Effort: low
  - Risk: medium
  - Blast radius: low
  - Maintenance burden: medium
- **Option B (Recommended):** Reuse image source sheet + crop sheet + ingestion pipeline (square 1024x1024).
  - Effort: medium
  - Risk: low
  - Blast radius: medium
  - Maintenance burden: low
- **Option C:** Introduce a new custom uploader flow.
  - Effort: high
  - Risk: medium
  - Blast radius: medium
  - Maintenance burden: high

#### Issue ID: TAF-03
- **Severity:** Medium
- **Evidence:** `foundation_documentation/modules/tenant_admin_module.md` section `3.6` currently lists only `map_ui.radius` + `map_ui.default_origin` for local preferences.
- **Why now:** Module docs and endpoint contracts must include new map filter catalog shape before Flutter implementation.
- **Option A:** Implement code first and postpone docs.
  - Effort: low
  - Risk: high
  - Blast radius: medium
  - Maintenance burden: high
- **Option B (Recommended):** Update module/contracts docs first, then implement.
  - Effort: medium
  - Risk: low
  - Blast radius: low
  - Maintenance burden: low
- **Option C:** Keep behavior undocumented (temporary).
  - Effort: low
  - Risk: high
  - Blast radius: high
  - Maintenance burden: high

### Failure Modes & Edge Cases
- Empty catalog submission removes all filter entries unintentionally.
- Duplicate filter keys in list causing ambiguous mapping.
- Invalid/missing image selection when user saves.
- Save flow overwrites unrelated `map_ui` keys (`radius`, `default_origin`, windows).
- Tenant switch while editing local-preferences draft.

### Uncertainty Register
- **Assumptions:**
  - Backend accepts `map_ui.filters` once schema path is registered.
  - Existing image ingestion service output remains within practical settings payload limits for configured catalog size.
- **Unknowns:**
  - Whether `/api/v1/map/filters` should immediately echo this catalog metadata in the same slice.
  - Final set of canonical filter keys expected by tenant ops for MVP.
- **Confidence:** `medium`

## Decision Baseline (Frozen)
- `D-01`: Persist tenant map filter catalog in `settings.map_ui.filters` as an ordered list of entries (order is list order).
- `D-02`: Each filter entry includes at minimum `key`, `label`, and `image_uri`.
- `D-03`: Filter image acquisition uses the existing tenant-admin source/crop pipeline with square `1024x1024` constraints.
- `D-04`: Save/read continues through settings-kernel namespace endpoint (`/admin/api/v1/settings/values/map_ui`) with merge-safe payload handling.
- `D-05`: Local-preferences remains the owner surface for map-ui operational tuning (`default_origin`, radius bounds, and filter catalog).

## Module Coherence Gate (Pre-Implementation)
| Decision ID | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | `Supersede` | `Supersede` | `foundation_documentation/modules/tenant_admin_module.md` section `3.6` currently omits `map_ui.filters`. |
| `D-02` | `Supersede` | `Supersede` | `foundation_documentation/endpoints_mvp_contracts.md` map settings schema currently lacks filter catalog entry fields. |
| `D-03` | `Aligned` | `Preserve` | Existing tenant-admin image ingestion flow is canonical in settings visual-identity/account/static-asset screens. |
| `D-04` | `Aligned` | `Preserve` | `foundation_documentation/endpoints_mvp_contracts.md` settings-kernel PATCH semantics and `namespace=map_ui`. |
| `D-05` | `Supersede` | `Supersede` | `foundation_documentation/modules/tenant_admin_module.md` local-preferences description is currently narrower than intended scope. |

## Module Decision Consistency Matrix (Planned)
| Module Decision ID | Planned Handling | Evidence | Notes |
| --- | --- | --- | --- |
| `TAD-04` | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` canonical fallback origin under `map_ui.default_origin` | New filter catalog extends same namespace without changing fallback origin contract. |
| `MAP-01` | `Preserve` | `foundation_documentation/modules/map_poi_module.md` (`map_pois` read model source of truth) | Catalog config only affects filter metadata/UX; POI inventory source remains backend projection. |
| `MAP-03` | `Out of Scope` | `foundation_documentation/modules/map_poi_module.md` stacking rules | This TODO does not alter stacking semantics. |

---

## A) Tenant Admin Settings Tasks

### A1) Domain/repository contract
- [x] ✅ Production‑Ready Define tenant-admin map filter catalog model under `map_ui` settings domain.
- [x] ✅ Production‑Ready Extend settings repository parsing/serialization for `map_ui.filters` with merge-safe behavior.

### A2) Controller orchestration
- [x] ✅ Production‑Ready Add controller-managed draft state for filter catalog list CRUD + reorder.
- [x] ✅ Production‑Ready Add controller helpers for image selection/crop/prepare using existing ingestion service.

### A3) Local-preferences UI
- [x] ✅ Production‑Ready Add “Filtros do mapa” section under `/admin/settings/local-preferences` (scope `tenant_admin`).
- [x] ✅ Production‑Ready Provide add/edit/remove/reorder actions for catalog entries.
- [x] ✅ Production‑Ready Reuse image source sheet + crop sheet and show preview for each filter image.

### A4) Persistence
- [x] ✅ Production‑Ready Persist catalog with `saveMapUiSettings` flow without dropping existing `map_ui` keys.
- [x] ✅ Production‑Ready Show remote success/error via existing settings status panel contract.

---

## B) Documentation & Contract Sync
- [x] ✅ Production‑Ready Update `tenant_admin_module.md` local-preferences contract to include map filter catalog editing.
- [x] ✅ Production‑Ready Update `map_poi_module.md` + `endpoints_mvp_contracts.md` to document filter catalog fields under `map_ui` and `/map/filters` metadata linkage.
- [x] ✅ Production‑Ready Update roadmap tracking if endpoint contract status changes.

---

## C) Definition of Done
- [x] ✅ Production‑Ready Tenant-admin local-preferences supports full CRUD + reorder for map filter catalog entries.
- [x] ✅ Production‑Ready Filter image selection uses existing tenant-admin image upload/crop UX and enforces square `1024x1024` output.
- [x] ✅ Production‑Ready Save flow persists catalog to `map_ui` namespace without regressing `default_origin`/radius keys.
- [x] ✅ Production‑Ready Docs/contracts reflect the delivered settings payload shape.

---

## D) Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm dart run custom_lint`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- [x] ✅ Production‑Ready Manual smoke: tenant-admin `/admin/settings/local-preferences` add/edit/remove/reorder filter entries and save successfully.

---

## Delivery Notes
- Backend dependency was delivered in `laravel-app` (`map_ui.filters` settings schema + `POST /admin/api/v1/media/map-filter-image`) with API/feature coverage.
- Flutter targets only canonical contracts (`/admin/api/v1/settings/values/map_ui` + `/admin/api/v1/media/map-filter-image`) with adapter/unit coverage; no runtime mock fallback was introduced.

---

## Decision Adherence Validation (To Fill Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `lib/domain/tenant_admin/tenant_admin_settings.dart:93`; `lib/presentation/tenant_admin/settings/controllers/tenant_admin_settings_controller.dart:392`; `lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_local_preferences_section.dart:204` | Ordered list CRUD + reorder flow is controller-owned and persisted under `map_ui.filters`. |
| `D-02` | `Adherent` | `lib/domain/tenant_admin/tenant_admin_settings.dart:23`; `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart:383`; `../foundation_documentation/endpoints_mvp_contracts.md:196` | Each item uses `key/label/image_uri` in domain, parser, and contract docs. |
| `D-03` | `Adherent` | `lib/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service.dart:336`; `lib/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet.dart:110`; `lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_local_preferences_screen.dart:163` | Map filter slot reuses canonical source/crop pipeline with `1:1` target spec (`1024x1024`). |
| `D-04` | `Adherent` | `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart:57`; `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart:937`; `test/infrastructure/repositories/tenant_admin_settings_repository_test.dart:116` | Namespace PATCH remains `/settings/values/map_ui` with merge-safe flattened payload. |
| `D-05` | `Adherent` | `lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_local_preferences_screen.dart:282`; `../foundation_documentation/modules/tenant_admin_module.md:178` | Local-preferences remains owner of map operational tuning and filter catalog editor. |

## Module Decision Consistency Validation (Delivery)
| Module Decision ID | Status (`Preserved`/`Superseded (Approved)`/`Regression`) | Evidence | Notes |
| --- | --- | --- | --- |
| `TAD-04` | `Preserved` | `lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_local_preferences_section.dart:119`; `../foundation_documentation/modules/tenant_admin_module.md:178` | `default_origin` workflow preserved while extending `map_ui` with filters. |
| `MAP-01` | `Preserved` | `../foundation_documentation/modules/map_poi_module.md:271`; `lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart:355` | Catalog metadata extension does not alter POI read-model source of truth. |
| `MAP-03` | `Preserved` | `../foundation_documentation/modules/map_poi_module.md:305` | No stack behavior changes were introduced in this TODO. |
