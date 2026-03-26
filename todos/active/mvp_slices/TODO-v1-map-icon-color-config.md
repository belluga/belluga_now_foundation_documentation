# TODO (V1): Map Icon/Color Config-Driven Refactor

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team, Laravel Team
**Objective:** Remove map icon/color hardcoding and establish a type-driven + projection-consolidated visual contract for map POIs, with filter visuals kept configuration-driven.
**Promotion lane path:** `dev -> stage -> main`

---

## References
- `foundation_documentation/todos/completed/TODO-v1-admin-discovery-map-small-fixes.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/domain_entities.md`

---

## Scope
- Replace key/category-based icon/color hardcoding in map marker/filter surfaces.
- Define POI visual ownership at POI Type definition level (same configuration surface where `is_poi_enabled` is declared).
- Consolidate resolved POI visual data into `map_pois` projection at creation/update time.
- Support two resolved visual strategies for POI markers:
  - `icon + color`
  - `image_uri` mapped from avatar/cover (when configured and available).
- Enforce deterministic projection handling on relevant source/type mutations: `is_poi_enabled=true` uses full re-materialization; `is_poi_enabled=false` uses hard-delete (not partial visual-only patch).
- Keep filter catalog visuals configuration-driven and persisted independently from POI projection visuals.
- Add filter-level marker override capability in filter settings (`override marker` checkbox + override visual config).
- When a filter override is active and valid, apply it to all rendered POI markers for that active filter context.
- Remove runtime hardcoded visual mappings; keep only one generic fallback visual path when POI visual metadata is absent/invalid.

## Out of Scope
- Branding color picker modal behavior (already handled in admin/discovery small fixes TODO).
- Broad map UX redesign beyond icon/color architecture.

---

## Promotion Evidence (Required)
| Workstream | Local Branch / Commit | PR to `dev` | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Map Icon/Color Refactor | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |

---

## Decision Baseline (Frozen)
- `D-01` POI marker visual ownership is type-driven: the same type definition that enables `is_poi_enabled` must define POI visual behavior.
- `D-02` Type-level POI visual contract mode is only `icon|image`; in `icon` mode, `color` is mandatory.
- `D-03` `map_pois` must persist a consolidated/resolved visual snapshot used directly by clients (no runtime visual joins required by Flutter).
- `D-04` Projection updates must use full `map_pois` re-materialization (idempotent upsert) instead of partial field patching.
- `D-05` Mandatory projection handling triggers include at least: item media change, item type change, type visual change, and type `is_poi_enabled` toggle (`true` => full re-materialization, `false` => hard-delete per `D-14`).
- `D-06` Filter visuals remain configuration-driven and persisted independently from POI projection visuals; runtime marker override may temporarily supersede POI marker rendering when active and valid.
- `D-07` Flutter map marker/deck visuals must consume resolved `map_pois` visual fields and use a single generic fallback when visual metadata is absent/invalid.
- `D-08` Filter settings must support marker override with explicit opt-in (`override_marker`), configured per filter.
- `D-09` Filter marker override mode is only `icon|image`; in `icon` mode, `color` is mandatory.
- `D-10` While a filter with valid marker override is active, marker rendering for all filtered POIs uses the override visual; when override is disabled/invalid, marker rendering uses each POI's own resolved visual.
- `D-11` Filter marker override scope is marker-only; deck/header/detail visuals keep POI-owned rendering rules.
- `D-12` Map filters are mutually exclusive in runtime: activating any filter disables all other filters, leaving exactly one active filter context at a time.
- `D-13` In type visual `mode=image`, when mapped media is missing, rendering must use the single generic fallback (not hardcoded category visuals).
- `D-14` When `is_poi_enabled` is disabled for a type, associated `map_pois` projections are hard-deleted (not soft-disabled), with mandatory destructive-action confirmation in UI.

## Contract Specification (Frozen)
### A) Type-Level POI Visual Contract
- POI visual configuration belongs to POI-enabled type definition surfaces (the same surfaces that define `is_poi_enabled`).
- Canonical type-level contract:
  - `poi_visual.mode`: `icon` or `image`
  - `poi_visual.icon`: required when mode is `icon`; ignored when mode is `image`
  - `poi_visual.color`: required when mode is `icon`, format `#RRGGBB`; ignored when mode is `image`
  - `poi_visual.image_source`: required when mode is `image`, allowed values `avatar` or `cover`; ignored when mode is `icon`
- Validation rules:
  - `icon` mode requires valid `icon` and valid `color`.
  - `image` mode requires valid `image_source`.
  - Invalid/incomplete type visual config must not break projection generation; projection clears/omits invalid visual snapshot and client applies generic fallback.

### B) `map_pois` Visual Snapshot Contract
- `map_pois` stores resolved visual output under a dedicated visual snapshot object used directly by clients:
  - `visual.mode`: `icon` | `image`
  - `visual.icon`: required when `visual.mode=icon`; null/absent otherwise
  - `visual.color`: required when `visual.mode=icon`; null/absent otherwise
  - `visual.image_uri`: required when `visual.mode=image`; null/absent otherwise
  - `visual.source`: `type_definition` | `item_media`
- Snapshot behavior:
  - Snapshot is written by projection logic only.
  - Snapshot is treated as read-only by clients.
  - When visual resolution is invalid/unavailable, projection may omit/clear `visual`; client must apply single generic fallback.

### C) POI Visual Resolution Order
- Resolution precedence:
  - if type visual mode is `image` and mapped media exists, resolve `visual.mode=image`.
  - else if type visual mode is `icon` and config is valid, resolve `visual.mode=icon`.
  - else clear/omit `visual` and let client apply the single generic fallback.
- No runtime dependency on filter key/category hardcoded maps for marker/deck visual decisions.

### D) API Exposure Contract
- `GET /api/v1/map/pois` stack payload (`top_poi` + `items[]`) must include `visual` snapshot fields.
- `GET /api/v1/map/pois/lookup` must include the same `visual` snapshot shape.
- `GET /api/v1/map/filters` remains an independent filter catalog contract.
- Base POI marker rendering from `map_pois.visual` must not require runtime filter payload joins.
- Filter payload is required only for active filter marker-override evaluation in runtime UI.

### E) Flutter Consumption Contract
- Flutter marker/deck rendering uses `poi.visual` snapshot as the primary source.
- Category enum/key mappings may remain for classification/filter semantics, but not as visual source of truth.
- If `visual.mode=image`, marker visual uses image rendering path.
- If `visual.mode=icon`, marker visual uses provided icon/color.
- If `visual` is missing/malformed, Flutter applies one generic fallback visual path.

### F) Filter Marker Override Contract
- Filter settings schema extends each catalog filter with marker override data:
  - `override_marker`: boolean (checkbox in filter editor)
  - `marker_override.mode`: `icon` | `image` (required when `override_marker=true`)
  - `marker_override.icon`: required when `mode=icon`
  - `marker_override.color`: required when `mode=icon`, format `#RRGGBB`
  - `marker_override.image_uri`: required when `mode=image` (fixed filter-level image selected in filter settings)
- Validation rules:
  - If `override_marker=false`, `marker_override` may be ignored.
  - If `override_marker=true`, `marker_override` must pass mode-specific validation.
  - Invalid override config must not break map rendering; fallback to POI-owned visual behavior.
- Rendering precedence while a catalog filter is active:
  - if active filter has valid marker override, render all filtered POIs with override visual.
  - else render each POI with its own resolved `map_pois.visual`.
  - if POI visual is absent/invalid, apply the single generic fallback.
- Scope guard:
  - Filter marker override is runtime presentation behavior for active filtering context; it does not mutate the stored `map_pois.visual` snapshot.
  - Override applies to markers only; deck/header/detail surfaces do not inherit filter override visuals.
  - Override evaluation consumes the active filter catalog payload in runtime; it does not change POI projection persistence.

### G) Filter Activation Contract (Frozen)
- Filters are mutually exclusive by design:
  - activating any filter clears every previously active filter state.
  - this applies to catalog filters and other filter dimensions (for example taxonomy/search-derived filter states).
  - there is at most one active filter override source at runtime.
- Override precedence is therefore evaluated only against the single active filter.

### H) POI Disable Deletion Contract (Frozen)
- Disabling `is_poi_enabled` for a type requires hard deletion of existing projections for that type.
- UI must present a destructive confirmation before applying the change:
  - message pattern: `Alerta: vamos deletar <N> projeções de <tipo>.`
  - actions: `Confirmar` and `Cancelar`.
- Delete count `<N>` must reflect current affected projections resolved before confirmation.

## Projection Refresh Policy (Required)
- Any map-relevant source/type mutation must trigger deterministic projection handling for affected POIs.
- Full re-materialization recomputes the complete projection payload, including visual fields, not only changed input fields.
- Mandatory trigger list (minimum):
  - Item media change (`avatar`, `cover`, or mapped media source fields).
  - Item type change.
  - Type visual configuration change (`icon`, `color`, image strategy).
  - Type `is_poi_enabled=true` path uses full re-materialization.
  - Type `is_poi_enabled=false` path uses hard-delete flow (Section H), not re-materialization.
- Additional map-relevant fields (location, status, naming, taxonomy, tags, timing, priority) must continue to trigger re-materialization while `is_poi_enabled=true`.

## Tasks
- [ ] ⚪ Identify and remove hardcoded icon/color mappings currently applied in POI marker/deck/filter UI.
- [ ] ⚪ Define type-level POI visual contract on POI-enabled types (`icon + color` or image strategy).
- [ ] ⚪ Define/implement consolidated `map_pois` visual snapshot fields consumed by Flutter runtime.
- [ ] ⚪ Update module contracts (`map_poi_module.md`, `tenant_admin_module.md`, `flutter_client_experience_module.md`) to include the frozen type/projection/API visual schema.
- [ ] ⚪ Implement projection writer flow with deterministic handling policy: `is_poi_enabled=true` full re-materialization, `is_poi_enabled=false` hard-delete (no partial visual patches).
- [ ] ⚪ Wire mandatory projection handling triggers: item media change, item type change, type visual change, `is_poi_enabled=true` (re-materialize), `is_poi_enabled=false` (hard-delete flow).
- [ ] ⚪ Ensure POI visual updates propagate to map runtime without Flutter code edits per mutation.
- [ ] ⚪ Keep filter catalog visuals configuration-driven; ensure filter rendering remains independent and deterministic.
- [ ] ⚪ Add filter editor marker override UX: `override marker` checkbox + override mode selector (`icon|image`) + mode-specific fields/validation.
- [ ] ⚪ Define and wire filter override payload through tenant-admin settings read/write and `/map/filters` decoration.
- [ ] ⚪ Migrate Flutter marker/deck visual rendering to `map_pois.visual` consumption and remove visual dependency on category hardcoded maps (except one generic fallback path).
- [ ] ⚪ Apply active-filter marker override precedence in map marker rendering.
- [ ] ⚪ Enforce mutually exclusive filter activation in map runtime (single active filter).
- [ ] ⚪ Keep override scope marker-only; deck/header/detail remain POI-driven.
- [ ] ⚪ Add type-disable destructive flow for `is_poi_enabled=false`: fetch affected projection count, show confirmation modal, hard-delete on confirm.
- [ ] ⚪ Add/adjust backend + Flutter tests for:
  - projection visual consolidation paths (`icon + color`, `image_uri`);
  - deterministic projection handling on required triggers (`is_poi_enabled=true` re-materialize, `is_poi_enabled=false` hard-delete);
  - active-filter override precedence paths (`valid override`, `invalid override`, `override disabled`);
  - mutually exclusive filter activation behavior;
  - `mode=image` + missing media -> generic fallback path;
  - `is_poi_enabled=false` hard-delete + confirmation flow (`Confirmar/Cancelar`);
  - single generic fallback behavior.

## Acceptance Criteria
- [ ] ⚪ POI marker/deck visuals are not hardcoded by category keys/enums in Flutter runtime.
- [ ] ⚪ POI visual behavior is defined at POI Type level and consolidated into `map_pois`.
- [ ] ⚪ `map_pois.visual` schema is exposed consistently by `/map/pois` and `/map/pois/lookup`.
- [ ] ⚪ `map_pois` visual snapshot is refreshed via full re-materialization on required non-destructive trigger set; `is_poi_enabled=false` follows hard-delete contract.
- [ ] ⚪ Filter visuals remain configuration-driven and persisted independently from POI projection visuals.
- [ ] ⚪ Filter editor supports marker override opt-in (`override marker`) with validated `icon|image` override config.
- [ ] ⚪ Active filter with valid override re-skins all filtered POI markers; without valid override, markers use POI-owned visuals.
- [ ] ⚪ Filter override affects markers only; deck/header/detail visuals remain POI-owned.
- [ ] ⚪ Only one filter can be active at runtime (mutually exclusive behavior).
- [ ] ⚪ In `mode=image`, missing media resolves to single generic fallback.
- [ ] ⚪ Disabling `is_poi_enabled` shows destructive confirmation with affected projection count and executes hard-delete only on `Confirmar`.
- [ ] ⚪ Color behavior remains consistent and selected-state contrast does not regress.
- [ ] ⚪ Runtime hardcoded map visual mappings are removed; only one deterministic generic fallback remains.
- [ ] ⚪ Tests cover consolidation paths, trigger-driven refresh, and fallback behavior.

## Definition of Done
- [ ] ⚪ Map POI icon/color/image rendering is type-driven and projection-consolidated, with marker-only filter override on a single active filter, hard-delete on POI disable with destructive confirmation, and no hardcoded category coupling in runtime UI except one generic fallback visual.
