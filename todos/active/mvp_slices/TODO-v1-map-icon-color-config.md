# TODO (V1): Map Icon/Color Config-Driven Refactor

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Dev lane promoted (`PR #163` merged into `dev` and post-merge actions succeeded); `stage/main` promotion pending.
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
- Apply the same type-driven marker contract to Event Types (event marker visual owned by event type definition, projection-consolidated).
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
| Map Icon/Color Refactor | `feature/v1-map-icon-color-config @ 9134aa07` | `https://github.com/belluga/belluga_now_front/pull/163 (merged -> dev @ 6fbe0195)` | `<pending>` | `<pending>` | `🟣 Lane-Promoted (dev)` |

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
- `D-15` Filter editor must expose marker visual configuration via an explicit `Visual` entrypoint (separate from `Regra`), because visual configuration is not discoverable enough when nested inside rule editing.
- `D-16` Filter row must not expose parallel image-only quick actions once canonical visual editing is available; visual state (`override`, `mode`, `icon|color|image_uri`) must be edited in a single canonical surface.
- `D-17` Marker icon persistence must remain string-token based and backward-compatible, but token ownership must be enum-backed in Flutter with stable canonical names and compatibility aliases.
- `D-18` Enum-backed marker icon keys are append-only: existing persisted keys cannot be renamed/removed; future icon-pack/custom-font expansions must add new keys without replacing old ones.
- `D-19` POI type editors (account/static) and filter visual editor must reuse the same icon picker component so visual-token selection behavior is consistent across admin surfaces.
- `D-20` V1/MVP keeps Material Icons as the glyph source; custom icon font is explicitly deferred to VNext.
- `D-21` Current V1 icon catalog is accepted as sufficient for MVP when grouped as: `Geral`, `Gastronomia`, `Cultura`, `Turismo`, `Serviços`, `Comércio`, `Destaque`.
- `D-22` Filter visual editing discoverability is mandatory: `Visual` must be a first-level action in the filter card/menu and cannot be nested under `Regra`.
- `D-23` Icon selection UX must display icon preview + human label; raw storage token/path must stay internal to persistence/transport layers.
- `D-24` Icon-mode visuals must have two explicit colors: `color` (marker background) and `icon_color` (glyph color), for both POI type visuals and filter marker overrides.
- `D-25` Test quality gate: visual regressions are only considered covered when tests assert rendered business outcome (not just payload/status) and include BSON-shaped persistence fixtures for normalization-critical paths.
- `D-26` Event Types follow the same marker-visual contract as Account/Static types for map markers (type-owned icon contract persisted into event `map_pois.visual` snapshot).
- `D-27` Filter Visual editor is incomplete unless icon mode exposes both pickers (`color` + `icon_color`) in the same canonical `Visual` surface.
- `D-28` Selected filter FAB must render from canonical override visual using both colors (`color` as selected background, `icon_color` as glyph color); fallback-only rendering is forbidden when override visual is valid.
- `D-29` POI marker fallback is allowed only when incoming projection visual is absent/invalid; valid persisted/projection visual (`icon`,`color`,`icon_color` or `image_uri`) must render without dropping to generic marker.
- `D-30` Regression closure is invalid without fail-first tests reproducing the bug in runtime-relevant paths (DTO decode + marker/FAB render), not only payload-shape unit assertions.

## Contract Specification (Frozen)
### A) Type-Level POI Visual Contract
- POI visual configuration belongs to POI-enabled type definition surfaces (the same surfaces that define `is_poi_enabled`).
- Canonical type-level contract:
  - `poi_visual.mode`: `icon` or `image`
  - `poi_visual.icon`: required when mode is `icon`; ignored when mode is `image`
  - `poi_visual.color`: required when mode is `icon`, format `#RRGGBB` (marker background); ignored when mode is `image`
  - `poi_visual.icon_color`: required when mode is `icon`, format `#RRGGBB` (icon glyph); ignored when mode is `image`
  - `poi_visual.image_source`: required when mode is `image`, allowed values `avatar` or `cover`; ignored when mode is `icon`
- Validation rules:
  - `icon` mode requires valid `icon`, valid `color`, and valid `icon_color`.
  - `image` mode requires valid `image_source`.
- Invalid/incomplete type visual config must not break projection generation; projection clears/omits invalid visual snapshot and client applies generic fallback.

### A.1) Event Type Marker Contract
- Event types must expose marker visual fields under the same icon semantics used by POI type visuals for map rendering:
  - `icon` (required for icon mode)
  - `color` (required, marker background)
  - `icon_color` (required, icon glyph)
- Event `map_pois` projection must consolidate event-type marker visual into `visual` snapshot (`mode=icon`, `icon`, `color`, `icon_color`, `source=type_definition`) whenever configuration is valid.
- Invalid/incomplete event type marker config must not break projection generation; event projection clears `visual` and client falls back to generic marker path.

### B) `map_pois` Visual Snapshot Contract
- `map_pois` stores resolved visual output under a dedicated visual snapshot object used directly by clients:
  - `visual.mode`: `icon` | `image`
  - `visual.icon`: required when `visual.mode=icon`; null/absent otherwise
  - `visual.color`: required when `visual.mode=icon` (marker background); null/absent otherwise
  - `visual.icon_color`: required when `visual.mode=icon` (icon glyph); null/absent otherwise
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
- If `visual.mode=icon`, marker visual uses provided icon/background `color` + glyph `icon_color`.
- If `visual` is missing/malformed, Flutter applies one generic fallback visual path.

### F) Filter Marker Override Contract
- Filter settings schema extends each catalog filter with marker override data:
  - `override_marker`: boolean (checkbox in filter editor)
  - `marker_override.mode`: `icon` | `image` (required when `override_marker=true`)
  - `marker_override.icon`: required when `mode=icon`
  - `marker_override.color`: required when `mode=icon`, format `#RRGGBB` (marker background)
  - `marker_override.icon_color`: required when `mode=icon`, format `#RRGGBB` (icon glyph)
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

### I) Filter Editor Visual UX Contract (Frozen)
- Filter card actions must include an explicit `Visual` action distinct from `Regra`.
- `Regra` scope is query logic only (`source`, `types`, `taxonomy`).
- `Visual` scope owns marker presentation only (`override_marker`, `marker_override.mode`, `marker_override.icon`, `marker_override.color`, `marker_override.image_uri` and filter `image_uri` when needed).
- `Visual` action must be accessible from first-level card actions (button row and/or overflow menu), not only from nested flows.
- Canonicality rule:
  - no second/parallel image-only edit path outside `Visual`.
  - users must configure marker image/icon in one place.
- Discoverability rule:
  - icon/image mode selection must be visible in first-level visual editor content, not hidden behind nested rule-only flows.

### J) Marker Icon Token Compatibility Contract (Frozen)
- Flutter must centralize marker icon token vocabulary in an enum-backed catalog with:
  - canonical storage key (`storage_key`) per icon;
  - compatibility aliases for legacy/historical keys already persisted.
- Resolver and picker must consume the same catalog.
- Resolver lookup must be based on persisted string value using enum helper resolution (canonical key + aliases), without exposing raw token editing in UI.
- Migration strategy:
  - no rewrite required for existing data;
  - legacy keys remain resolvable through alias mapping;
  - new icon coverage is additive (`append-only` catalog behavior).
- VNext custom font readiness:
  - icon catalog must allow swapping icon glyph source (Material -> custom font) without changing stored keys.
  - follow-up backlog: `foundation_documentation/todos/active/vnext_slices/TODO-vnext-map-marker-icon-catalog-expansion.md`.

### K) Marker Icon Catalog Baseline (MVP)
- Approved MVP groups:
  - `Geral`
  - `Gastronomia`
  - `Cultura`
  - `Turismo`
  - `Serviços`
  - `Comércio`
  - `Destaque`
- MVP sufficiency decision:
  - current Material-based catalog is accepted for launch scope;
  - missing niche domains (for example nightlife/café/transport specific glyphs) are VNext-only additions.

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
- [x] 🟧 Reopen blocker #1: Filter `Visual` icon mode must expose `icon_color` field (in addition to `color`) and persist it through settings contracts.
- [x] 🟧 Reopen blocker #2: Selected filter FAB must use canonical override visual colors (`color` background + `icon_color` glyph) and keep non-selected state unchanged.
- [x] 🟧 Reopen blocker #3: Eliminate unintended POI marker fallback by hardening projection visual parsing/consumption for real transport shapes (including wrapped/BSON-like payload values).
- [x] 🟧 Reopen blocker #4: Replace weak coverage with fail-first regression tests proving the three blockers above (decode + render), then keep them green after fix.
- [x] 🟧 Reopen blocker #5: Enforce create/update projection materialization with `visual` for all sources (`account_profile`, `static_asset`, `event`) in runtime flows (not only manual fixture writes).
- [x] 🟧 Reopen blocker #6: Event Type visual updates must re-materialize every related `event` projection in `map_pois` with non-stale checkpoint semantics.
- [x] 🟧 Reopen blocker #7: Event Type marker visual contract must include `icon_color` end-to-end (admin API, event snapshot, map projection, map payload).
- [x] 🟧 Reopen blocker #8: Strengthen backend tests to validate full source->projection->query chain for the four mandatory refresh triggers (item create/update + type visual change + type POI toggle).
- [x] 🟧 Identify and remove hardcoded icon/color mappings currently applied in POI marker/deck/filter UI.
- [x] 🟧 Define type-level POI visual contract on POI-enabled types (`icon + color` or image strategy).
- [x] 🟧 Define/implement consolidated `map_pois` visual snapshot fields consumed by Flutter runtime.
- [x] 🟧 Update module contracts (`map_poi_module.md`, `tenant_admin_module.md`, `flutter_client_experience_module.md`) to include the frozen type/projection/API visual schema.
- [x] 🟧 Implement projection writer flow with deterministic handling policy: `is_poi_enabled=true` full re-materialization, `is_poi_enabled=false` hard-delete (no partial visual patches).
- [x] 🟧 Wire mandatory projection handling triggers: item media change, item type change, type visual change, `is_poi_enabled=true` (re-materialize), `is_poi_enabled=false` (hard-delete flow).
- [x] 🟧 Ensure POI visual updates propagate to map runtime without Flutter code edits per mutation.
- [x] 🟧 Keep filter catalog visuals configuration-driven; ensure filter rendering remains independent and deterministic.
- [x] 🟧 Add filter editor marker override UX: `override marker` checkbox + override mode selector (`icon|image`) + mode-specific fields/validation.
- [x] 🟧 Define and wire filter override payload through tenant-admin settings read/write and `/map/filters` decoration.
- [x] 🟧 Migrate Flutter marker/deck visual rendering to `map_pois.visual` consumption and remove visual dependency on category hardcoded maps (except one generic fallback path).
- [x] 🟧 Apply active-filter marker override precedence in map marker rendering.
- [x] 🟧 Enforce mutually exclusive filter activation in map runtime (single active filter).
- [x] 🟧 Keep override scope marker-only; deck/header/detail remain POI-driven.
- [x] 🟧 Add type-disable destructive flow for `is_poi_enabled=false`: fetch affected projection count, show confirmation modal, hard-delete on confirm.
- [x] 🟧 Add/adjust backend + Flutter tests for:
  - projection visual consolidation paths (`icon + color`, `image_uri`);
  - deterministic projection handling on required triggers (`is_poi_enabled=true` re-materialize, `is_poi_enabled=false` hard-delete);
  - active-filter override precedence paths (`valid override`, `invalid override`, `override disabled`);
  - mutually exclusive filter activation behavior;
  - `mode=image` + missing media -> generic fallback path;
  - `is_poi_enabled=false` hard-delete + confirmation flow (`Confirmar/Cancelar`);
  - single generic fallback behavior.
- [x] 🟧 Add enum-backed marker icon catalog with stable canonical keys + legacy compatibility aliases.
- [x] 🟧 Add shared marker icon picker widget and reuse it in both POI type editors and filter visual editor.
- [x] 🟧 Split filter card editing actions into explicit `Regra` (query) and `Visual` (marker visual).
- [x] 🟧 Remove conflicting image-only quick actions from filter row after canonical `Visual` surface is active.
- [x] 🟧 Add/adjust Flutter widget tests for `Visual` entrypoint discoverability and canonical image/icon edit flow.
- [x] 🟧 Fix filter card preview visual source so card reflects canonical `Visual` config (`marker_override`) instead of legacy image-only shortcut.
- [x] 🟧 Fix map FAB filter button visual source to prefer filter `Visual` override (icon+color/image) over legacy `image_uri`.
- [x] 🟧 Fix POI visual hydration/parsing/runtime consumption path causing newly created POIs to fall back to generic marker despite valid type visual persistence.
- [x] 🟧 Add regression fix for account profile create media persistence (`avatar`/`cover`) where create currently drops media while update succeeds.
- [x] 🟧 Add/adjust TDD coverage for the four regression tracks above.

## Acceptance Criteria
- [x] 🟧 Filter `Visual` icon mode displays and persists both `color` and `icon_color`.
- [x] 🟧 Selected filter FAB applies canonical override colors (`color` background + `icon_color` glyph) when active.
- [x] 🟧 POI markers no longer fall back to generic marker when valid projection visual is present.
- [x] 🟧 Regression tests fail before fix and pass after fix for the three reopened blockers.
- [x] 🟧 POI marker/deck visuals are not hardcoded by category keys/enums in Flutter runtime.
- [x] 🟧 POI visual behavior is defined at POI Type level and consolidated into `map_pois`.
- [x] 🟧 `map_pois.visual` schema is exposed consistently by `/map/pois` and `/map/pois/lookup`.
- [x] 🟧 `map_pois` visual snapshot is refreshed via full re-materialization on required non-destructive trigger set; `is_poi_enabled=false` follows hard-delete contract.
- [x] 🟧 Filter visuals remain configuration-driven and persisted independently from POI projection visuals.
- [x] 🟧 Filter editor supports marker override opt-in (`override marker`) with validated `icon|image` override config.
- [x] 🟧 Active filter with valid override re-skins all filtered POI markers; without valid override, markers use POI-owned visuals.
- [x] 🟧 Filter override affects markers only; deck/header/detail visuals remain POI-owned.
- [x] 🟧 Only one filter can be active at runtime (mutually exclusive behavior).
- [x] 🟧 In `mode=image`, missing media resolves to single generic fallback.
- [x] 🟧 Disabling `is_poi_enabled` shows destructive confirmation with affected projection count and executes hard-delete only on `Confirmar`.
- [x] 🟧 Color behavior remains consistent and selected-state contrast does not regress.
- [x] 🟧 Runtime hardcoded map visual mappings are removed; only one deterministic generic fallback remains.
- [x] 🟧 Tests cover consolidation paths, trigger-driven refresh, and fallback behavior.
- [x] 🟧 Filter card exposes explicit `Visual` action, separate from `Regra`.
- [x] 🟧 `Regra` no longer owns marker visual configuration fields.
- [x] 🟧 No parallel image-only action remains outside canonical `Visual` editor.
- [x] 🟧 POI type forms and filter visual editor reuse the same icon picker widget.
- [x] 🟧 Marker icon catalog uses stable enum-backed keys with alias compatibility and no persisted-key breakage.
- [x] 🟧 Filter card visual preview is consistent with canonical `Visual` settings and no longer tied to legacy image shortcut semantics.
- [x] 🟧 FAB filter buttons reflect the same canonical visual contract used by runtime marker override resolution.
- [x] 🟧 New POIs materialize/render with resolved type visual (icon+color or image), without unintended fallback-to-default when payload is valid.
- [x] 🟧 Account profile create persists avatar/cover with parity to update behavior.

## Definition of Done
- [x] 🟧 Map POI icon/color/image rendering is type-driven and projection-consolidated, with marker-only filter override on a single active filter, hard-delete on POI disable with destructive confirmation, and no hardcoded category coupling in runtime UI except one generic fallback visual.

## Execution Log (Cycle Updates)
### Cycle 1 — Flutter Marker Visual Base (Local)
- [x] 🟧 Added Flutter DTO/domain support for `map_pois.visual` (`icon|image`) and mapped it into `CityPoiModel`.
- [x] 🟧 Refactored marker rendering to use POI visual snapshot first, with a single generic fallback marker icon/color when visual is missing/invalid.
- [x] 🟧 Removed category-based marker icon/color dependency in `PoiMarker` (runtime marker visuals no longer sourced from category hardcoded map).
- [x] 🟧 Added tests for visual snapshot parsing + marker rendering fallback behavior.
- Test evidence:
  - `flutter test test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart`
  - Result: `All tests passed!` (6 tests).
- Remaining for next cycles:
  - Filter marker override contract in Flutter runtime.
  - Mutual-exclusive filter activation enforcement refinement.
  - Tenant-admin filter/type editors (`override marker`, `poi_visual`) and destructive disable flow.
  - Deck/header visual decoupling completion where still hardcoded.

### Cycle 2 — Filter Marker Override + Mutual Exclusivity (Local)
- [x] 🟧 Added Flutter filter DTO/domain support for `override_marker` + `marker_override` (`icon|image` validation-aware parsing).
- [x] 🟧 Wired marker override propagation through repository -> controller -> map layers -> marker widget.
- [x] 🟧 Enforced marker rendering precedence in runtime: valid filter override -> POI visual -> single generic fallback.
- [x] 🟧 Enforced mutually exclusive runtime behavior for taxonomy toggle flow (taxonomy activation clears catalog filter context; same-token second tap clears filters).
- [x] 🟧 Added tests for marker override DTO parsing, marker precedence behavior, and controller exclusivity behavior.
- Test evidence:
  - `flutter test test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/dal/dto/map/map_filter_category_dto_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
  - Result: `All tests passed!` (34 tests in this run).
- Remaining for next cycles:
  - Tenant-admin filter editor UX for configuring marker override fields.
  - Tenant-admin type forms for `poi_visual` (`icon|image`) and `is_poi_enabled=false` destructive confirmation/count flow.
  - Deck/header/detail visual audit to eliminate remaining hardcoded visual coupling where applicable.

### Cycle 3 — Tenant Admin Filter Override Editor (Local)
- [x] 🟧 Implemented tenant-admin filter editor support for marker override opt-in (`override_marker`) with mode selector (`icon|image`) and mode-specific fields.
- [x] 🟧 Wired override config through tenant-admin settings controller/domain serialization to `/map/filters` decoration contract.
- [x] 🟧 Preserved runtime fallback behavior: invalid override falls back to POI-owned visual.
- Test evidence:
  - `flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
  - Result: `All tests passed!` (73 tests in combined run that included settings paths).

### Cycle 4 — Type `poi_visual` Contract + Projection Impact Preview (Local)
- [x] 🟧 Added Flutter domain contract for type-level POI visual (`icon|image` with `image_source=avatar|cover`).
- [x] 🟧 Wired tenant-admin profile/static type DTO decoding for `poi_visual` and propagated into domain definitions.
- [x] 🟧 Added repository write helpers to send `poi_visual` payloads on type create/update flows.
- [x] 🟧 Added repository + controller methods to read projection impact preview counts:
  - `GET /account_profile_types/{type}/map_poi_projection_impact`
  - `GET /static_profile_types/{type}/map_poi_projection_impact`
- [x] 🟧 Added tests for payload encoding, response decoding, and preview-count delegation.
- Test evidence:
  - `flutter test test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart`
  - Result: `All tests passed!` (40 tests).
- Remaining for next cycles:
  - Type form UX for `poi_visual` editing (`icon|image`, icon color required, image source required).
  - Destructive confirmation flow on `is_poi_enabled=true -> false` using preview count (`Confirmar` / `Cancelar`).
  - Final hardcoded visual cleanup in remaining map surfaces (single fallback policy).

### Cycle 5 — Type Form UX + Destructive Disable Confirmation (Local)
- [x] 🟧 Added POI visual editor in both type forms (account/static):
  - `mode=icon|image`
  - `icon` + `color` path for icon mode
  - `image_source=avatar|cover` path for image mode
- [x] 🟧 Added form-level validation gate when `is_poi_enabled=true` and visual config is invalid.
- [x] 🟧 Implemented destructive confirmation flow for `is_poi_enabled=true -> false` in both forms:
  - fetches projection impact count before submit
  - message contract:
    - account type: `Alerta: vamos deletar <N> projeções de <tipo>.`
    - static type: `Alerta: vamos deletar <N> projeções de <tipo>.`
  - actions: `Confirmar` / `Cancelar`
- [x] 🟧 Save behavior now persists `poi_visual` explicitly on create/update (or `null` when POI gets disabled).
- Test evidence:
  - `flutter test test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart`
  - Result: `All tests passed!` (2 widget tests).

### Cycle 24 — POI Marker Fallback Hardening (Local)
- [x] 🟧 Added fail-first regression tests reproducing default-fallback bug for runtime-relevant legacy payload shapes:
  - `visual/poi_visual` payload without explicit `mode`.
  - hex colors arriving without `#`.
  - hex colors arriving with alpha suffix (8-digit).
- [x] 🟧 Hardened `CityPoiVisualDTO` parser to:
  - infer `mode` (`icon|image`) when omitted but visual intent is clear;
  - normalize legacy/variant hex formats into canonical `#RRGGBB`;
  - preserve strict invalid-case fallback when color/icon is not recoverable.
- [x] 🟧 Confirmed marker rendering consumes normalized visual and no longer drops to generic fallback for valid legacy-shaped payloads.
- Test evidence:
  - fail-first and green-after-fix run:
    - `flutter test test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart`
    - before fix: 3 failing assertions (`visual` null / fallback icon path).
    - after fix: `All tests passed!` (17 tests).
  - contract regression guard:
    - `flutter test test/infrastructure/services/http/laravel_map_poi_http_service_test.dart`
    - Result: `All tests passed!` (4 tests).
  - map flow safety net:
    - `flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant/map/screens/map_screen/widgets/fab_menu_test.dart`
    - Result: `All tests passed!` (31 tests).

### Cycle 6 — Residual Map Visual Hardcode Cleanup (Local)
- [x] 🟧 Refactored `FilteredDeck` visual header rendering to derive icon/color from resolved POI visuals instead of category-theme hardcoded color mapping.
- [x] 🟧 Kept deterministic fallback behavior when no valid POI visual is available.
- [x] 🟧 Removed direct `CityPoiCategory -> color` visual dependency from deck header.
- Verification evidence:
  - `flutter analyze lib/presentation/tenant_public/map/screens/map_screen/widgets/filtered_deck.dart lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart`
  - Result: `No issues found!`
  - `flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart`
  - Result: `All tests passed!` (29 tests in this run).

### Cycle 7 — TODO Status Sync (Local)
- [x] 🟧 Consolidated cycle tracking so Tasks and Acceptance Criteria reflect current local implementation status (Flutter lane).
- [x] 🟧 Kept backend/materialization/docs items explicitly pending to avoid false production-ready signaling.

### Cycle 8 — Backend Projection Contract Hardening (Local)
- [x] 🟧 Added backend coverage for type-driven hard-delete/rematerialization:
  - account profile type `is_poi_enabled=true -> false` hard-deletes related `map_pois`;
  - account profile type `poi_visual` change re-materializes `map_pois.visual`;
  - static profile type `is_poi_enabled=true -> false` hard-deletes related `map_pois`;
  - static profile type `poi_visual` change re-materializes `map_pois.visual`.
- [x] 🟧 Added lookup contract assertion that `/map/pois/lookup` exposes `poi.visual`.
- Test evidence:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/StaticAssets/StaticProfileTypesControllerTest.php tests/Feature/Map/MapPoisControllerTest.php`
  - Result: `39 passed (178 assertions)`.

### Cycle 9 — Contract Docs Sync + Final Validation (Local)
- [x] 🟧 Updated module contracts:
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- [x] 🟧 Aligned static destructive copy to contract (`projeções`) and added marker selected-state contrast assertion.
- Final validation evidence:
  - `flutter test test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/dal/dto/map/map_filter_category_dto_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
  - Result: `All tests passed!` (116 tests).
  - `flutter analyze lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart lib/presentation/tenant_public/map/screens/map_screen/widgets/shared/poi_marker.dart`
  - Result: `No issues found!`.

### Cycle 10 — Clean Code Loop Scan 1 (Local)
- [x] 🟧 Opened the iterative Clean Code hardening loop (`scan -> list findings -> fix one by one -> rescan`).
- Findings registry (scan 1):

| Finding ID | Severity | Area | Evidence | Status |
| --- | --- | --- | --- | --- |
| `CC-01` | `Medium` | Laravel POI visual normalization duplication | `app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`, `app/Application/StaticAssets/StaticProfileTypeRegistryManagementService.php`, `app/Application/AccountProfiles/AccountProfileRegistryService.php`, `app/Application/StaticAssets/StaticProfileTypeRegistryService.php` | `Open` |
| `CC-02` | `Medium` | Laravel map projection ref helper duplication (dispatch/count/split) | `app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`, `app/Application/StaticAssets/StaticProfileTypeRegistryManagementService.php` | `Open` |
| `CC-03` | `Low` | Flutter controller flow duplication (`create/update + includePoiVisual`) | `lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart`, `lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart` | `Open` |
| `CC-04` | `Low` | Flutter destructive confirmation duplication (POI disable dialog) | `lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`, `lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart` | `Open` |

- Loop gate for completion:
  - Continue cycles until no `High` or `Medium` finding remains open.
  - `Low` findings may remain queued for a separate hardening slice after `High/Medium` closure.

### Cycle 11 — Clean Code Loop Fix 1 (Laravel Medium Findings)
- [x] 🟧 Resolved `CC-01` by extracting a shared POI visual normalizer and wiring all registry/management services to it.
  - New shared class: `laravel-app/app/Application/Shared/MapPois/PoiVisualNormalizer.php`.
  - Refactored consumers:
    - `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryService.php`
    - `laravel-app/app/Application/StaticAssets/StaticProfileTypeRegistryService.php`
    - `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`
    - `laravel-app/app/Application/StaticAssets/StaticProfileTypeRegistryManagementService.php`
- [x] 🟧 Resolved `CC-02` by extracting a shared map projection ref helper and removing duplicate count/split/dispatch loops.
  - New shared class: `laravel-app/app/Application/Shared/MapPois/MapPoiProjectionRefService.php`.
  - Refactored consumers:
    - `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`
    - `laravel-app/app/Application/StaticAssets/StaticProfileTypeRegistryManagementService.php`
- [x] 🟧 Adjusted `EnvironmentResolverService` to use DI-backed `AccountProfileRegistryService` (constructor injection) instead of direct `new`.
  - File: `laravel-app/app/Application/Environment/EnvironmentResolverService.php`.

- Findings status update:

| Finding ID | Severity | Status after Cycle 11 |
| --- | --- | --- |
| `CC-01` | `Medium` | `Resolved` |
| `CC-02` | `Medium` | `Resolved` |
| `CC-03` | `Low` | `Open` |
| `CC-04` | `Low` | `Open` |

- Verification evidence:
  - Rescan commands:
    - `rg -n "private function normalizePoiVisual|private function normalizeHexColor" laravel-app/app/Application`
    - `rg -n "countMapPoiRefs|splitMapPoiRefIds|dispatchProfileUpserts|dispatchProfileDeletes|dispatchAssetUpserts|dispatchAssetDeletes" laravel-app/app/Application`
  - Test command:
    - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/StaticAssets/StaticProfileTypesControllerTest.php tests/Feature/Map/MapPoisControllerTest.php tests/Unit/Application/Environment/EnvironmentResolverServiceTest.php`
  - Result: `42 passed (189 assertions)`.

### Cycle 12 — Clean Code Loop Rescan 2 (Gate Check)
- [x] 🟧 Re-ran Clean Code scan after Cycle 11 refactor.
- Gate result:
  - `High` open findings: `0`
  - `Medium` open findings: `0`
  - `Low` open findings: `2` (`CC-03`, `CC-04`)
- Loop status:
  - **Stop condition reached** for this loop (`no high/medium`).
  - Remaining `Low` items are backlog candidates for an optional follow-up hardening slice.

### Cycle 13 — Clean Code Loop Fix 2 (Flutter Low Findings)
- [x] 🟧 Resolved `CC-03` by extracting shared optional-POI request orchestration for type create/update flows and reusing it in both controllers.
  - New shared helper: `flutter-app/lib/presentation/tenant_admin/shared/utils/tenant_admin_type_poi_visual_requests.dart`.
  - Refactored controllers:
    - `flutter-app/lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart`
    - `flutter-app/lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart`
- [x] 🟧 Resolved `CC-04` by extracting a single destructive confirmation helper for POI disable flow and reusing it in both forms.
  - New shared helper: `flutter-app/lib/presentation/tenant_admin/shared/utils/tenant_admin_poi_disable_confirmation.dart`
  - Refactored screens:
    - `flutter-app/lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart`
    - `flutter-app/lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart`
  - Copy consistency normalized via helper (`Confirmação`).

- Findings status update:

| Finding ID | Severity | Status after Cycle 13 |
| --- | --- | --- |
| `CC-01` | `Medium` | `Resolved` |
| `CC-02` | `Medium` | `Resolved` |
| `CC-03` | `Low` | `Resolved` |
| `CC-04` | `Low` | `Resolved` |

- Verification evidence:
  - `flutter test test/presentation/tenant_admin/controllers/tenant_admin_profile_types_controller_test.dart test/presentation/tenant_admin/controllers/tenant_admin_static_profile_types_controller_test.dart test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart`
  - Result: `All tests passed!` (14 tests in this run).
  - `flutter analyze lib/presentation/tenant_admin/shared/utils/tenant_admin_type_poi_visual_requests.dart lib/presentation/tenant_admin/shared/utils/tenant_admin_poi_disable_confirmation.dart lib/presentation/tenant_admin/profile_types/controllers/tenant_admin_profile_types_controller.dart lib/presentation/tenant_admin/static_profile_types/controllers/tenant_admin_static_profile_types_controller.dart lib/presentation/tenant_admin/profile_types/screens/tenant_admin_profile_type_form_screen.dart lib/presentation/tenant_admin/static_profile_types/screens/tenant_admin_static_profile_type_form_screen.dart`
  - Result: `No issues found!`.

### Cycle 14 — Clean Code Loop Final Rescan
- [x] 🟧 Final rescan after low-finding refactor.
- Final gate result:
  - `High` open findings: `0`
  - `Medium` open findings: `0`
  - `Low` open findings: `0`
- Loop status:
  - **Closed** (no open findings at any severity level).

### Cycle 15 — Canonical Visual Entrypoint Decision Freeze (Planning)
- [x] 🟧 Consolidated UX/contract decision to move filter marker visual configuration out of `Regra` into explicit `Visual`.
- [x] 🟧 Consolidated compatibility decision to centralize marker icon tokens in enum-backed catalog with append-only key policy.
- [x] 🟧 Added frozen contracts and pending tasks for:
  - reusable icon picker across type + filter visual editors;
  - removal of conflicting image-only quick actions;
  - VNext custom-font-ready token stability.
- [x] 🟧 Implementation + verification completed in Cycle 17.

### Cycle 16 — Recent Decisions Consolidation Sync (Planning)
- [x] 🟧 Frozen decision that `Visual` must be first-level discoverable in filter card actions (not nested under `Regra`).
- [x] 🟧 Frozen decision that icon picker UX is canonical in both editors and must show icon + label (no raw path/token field for users).
- [x] 🟧 Frozen enum-by-string compatibility contract for marker icon persistence (`canonical key + aliases`, append-only expansion).
- [x] 🟧 Reaffirmed canonicality rule removing parallel image quick-actions outside `Visual`.

### Cycle 17 — Visual/Picker Closure + TDD Evidence (Local)
- [x] 🟧 Closed pending implementation items for enum icon catalog, shared icon picker reuse, explicit `Visual` action, and removal of legacy image quick-actions in filter row.
- [x] 🟧 Added/adjusted widget/unit coverage for:
  - filter row discoverability (`Regra` + `Visual`) with no legacy `Imagem`/`Limpar imagem` quick actions;
  - `Regra` sheet query-only scope;
  - `Visual` sheet canonical icon|image flow with marker override persistence;
  - shared icon picker presence in both POI type forms;
  - enum icon catalog compatibility aliases (`fromStorage`) and key uniqueness.
- [x] 🟧 Updated integration-test fakes/fixtures to match current contracts (profile/static repository update signatures and map filter local-preferences section API), restoring analyzer consistency.
- Validation evidence:
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart test/presentation/shared/icons/map_marker_icon_catalog_test.dart`
  - Result: `All tests passed!` (26 tests).
  - `fvm flutter analyze`
  - Result: `No issues found!`
  - `fvm dart run custom_lint`
  - Result: `No issues found!`
  - `bash scripts/build_web.sh`
  - Result: `Built web bundle successfully at ../web-app` (wasm dry-run warnings only).

### Cycle 18 — Validation/Picker Gap Closure (Local)
- [x] 🟧 Added explicit widget coverage for remaining quality gaps:
  - `Visual` icon-mode validation messaging when override is enabled without icon selection.
  - `Visual` image-mode URL validation messaging (invalid URL rejection).
  - isolated icon picker behavior asserting selected icon writes canonical enum `storage_key`.
- Validation evidence:
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant_admin/profile_types/tenant_admin_profile_type_form_screen_test.dart test/presentation/tenant_admin/static_profile_types/tenant_admin_static_profile_type_form_screen_test.dart test/presentation/shared/icons/map_marker_icon_catalog_test.dart`
  - Result: `All tests passed!` (29 tests).

### Cycle 19 — Manual QA Regressions (Reopened)
- [x] 🟧 Consolidated manual QA findings raised after Cycle 18:
  - `R-01` Filter `Visual` settings persist but filter card preview still renders legacy thumbnail path and does not reflect configured marker visual.
  - `R-02` Map FAB filter buttons still render legacy filter image source instead of configured filter `Visual` override (icon/image).
  - `R-03` New POIs still render fallback/default marker even when type `poi_visual` persists as valid (possible parse/hydration/materialization gap).
  - `R-04` Side-job regression: account profile **create** does not persist `avatar/cover` media while **update** does.
- [x] 🟧 Reopened implementation/test scope for the regressions above before lane promotion.
- Notes:
  - `R-03` may require validating projection payload shape + parser acceptance + runtime consumption end-to-end.
  - `R-04` is intentionally tracked in this TODO as an approved side-job due direct dependency on POI image strategy reliability.

### Cycle 20 — Regressions TDD Loop (Local Completed)
- [x] 🟧 Start strict TDD pass for `R-01..R-04` with explicit `RED -> GREEN -> REFACTOR` gates.
- [x] 🟧 Add failing Flutter widget tests for:
  - filter card preview visual source precedence (`marker_override` -> fallback -> legacy image).
  - map FAB filter visual source precedence (`marker_override` -> legacy image -> fallback icon).
  - icon-mode color rendering for filter visuals (card + FAB) using persisted `marker_override.color`.
- [x] 🟧 Add failing regression tests for POI visual hydration robustness (`R-03`) covering non-happy payload shapes from map backend transport.
- [x] 🟧 Add failing repository tests for onboarding/create media persistence path (`R-04`) including multipart request contract parity with update paths.
- [x] 🟧 Implement `GREEN` fixes (no hardcoded visual reintroduction) for `R-01..R-04`, including icon color propagation.
- [x] 🟧 Execute `REFACTOR` pass in same cycle:
  - remove new duplication introduced by fixes;
  - centralize filter visual resolution helpers for card/FAB parity;
  - keep contracts explicit and deterministic.
- [x] 🟧 Re-run targeted suites + lint/analyze/custom-lint and record evidence.
- [x] 🟧 Cycle 20 implementation notes:
  - Added shared visual resolver `MapMarkerVisualResolver` and reused it across map/admin surfaces.
  - Filter card preview now resolves canonical visual precedence: valid `marker_override` (`icon+color` or `image`) -> legacy image -> generic fallback.
  - FAB category visuals now follow the same precedence and render icon color from `marker_override.color`.
  - `CityPoiVisualDTO` now accepts wrapped scalar shapes in transport payloads (for example `{value: ...}`) to avoid accidental fallback when payload is semantically valid.
  - Create media path hardening: explicit multipart content-type for create endpoints with uploads (`account_profiles` and `account_onboardings`).
- Validation evidence:
  - `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/fab_menu_test.dart test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart`
  - Result: `All tests passed!` (64 tests).
  - `fvm flutter analyze lib/infrastructure/dal/dto/map/city_poi_visual_dto.dart lib/presentation/shared/icons/map_marker_visual_resolver.dart lib/presentation/tenant_public/map/screens/map_screen/widgets/shared/map_marker_icon_resolver.dart lib/presentation/tenant_public/map/screens/map_screen/widgets/fab_menu.dart lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_local_preferences_section.dart lib/infrastructure/repositories/tenant_admin/tenant_admin_account_profiles_repository.dart lib/infrastructure/repositories/tenant_admin/tenant_admin_accounts_repository.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/fab_menu_test.dart test/infrastructure/dal/dto/map/city_poi_dto_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart`
  - Result: `No issues found!`
  - `fvm dart run custom_lint`
  - Result: `No issues found!`

### Cycle 21 — BSON Contract Validation + Gate Recheck (Local)
- [x] 🟧 Validated and kept `MapPoiQueryFormatting::formatVisual` BSON-safe path (normalization for document payloads before mode/icon/color validation) to prevent unintended fallback when persistence contains `BSONDocument`.
- [x] 🟧 Added/validated unit regression for BSON payload handling:
  - `tests/Unit/Map/MapPoiQueryFormattingTest.php::test_format_visual_accepts_bson_document_icon_payload`.
- [x] 🟧 Re-ran Laravel style gate and confirmed clean status (including "no cast-to-array" guard path under current Pint ruleset).
- [x] 🟧 Re-ran map API regression suite + focused Flutter regression suite + web build for manual QA handoff.
- Validation evidence:
  - `docker compose exec -T app php vendor/bin/pint --test`
  - Result: `PASS` (887 files).
  - `../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Map/MapPoiQueryFormattingTest.php tests/Feature/Map/MapPoisControllerTest.php`
  - Result: `PASS` (13 tests in feature suite + 1 BSON unit).
  - `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/fab_menu_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
  - Result: `All tests passed!` (39 tests).
  - `bash scripts/build_web.sh`
  - Result: `Built web bundle successfully at ../web-app` (wasm dry-run warnings only).

### Cycle 22 — Test Hardening Against Visual Fallback Regressions (Local Completed)
- [x] 🟧 Added Laravel unit coverage for `PoiVisualNormalizer` with BSON-shaped input (`BSONDocument`) for icon-mode validation paths.
- [x] 🟧 Added Laravel feature/integration coverage for real chain: type visual persisted -> projection upsert -> `/map/pois` response visual payload (without manually seeding `map_pois.visual`).
- [x] 🟧 Added Flutter widget coverage for transport chain (`Map payload -> DTO parse -> domain visual -> marker icon render`) to prevent false-green tests that instantiate `CityPoiVisual` directly.
- [x] 🟧 Added Flutter create-flow assertion that onboarding forwards `avatarUpload`/`coverUpload` when files are selected.
- [x] 🟧 Ran targeted Laravel + Flutter suites and captured evidence below.
- Frozen test decisions:
  - `D-T01` Regression safety for map markers is invalid unless at least one test traverses persisted type visual through projection and map API response.
  - `D-T02` BSON compatibility for visual payloads must be asserted at normalizer boundary (shared service), not only at map response formatter.
  - `D-T03` Flutter marker assertions must include at least one transport-origin visual payload (DTO path), not only direct model construction.
  - `D-T04` Account create media flow must assert upload object forwarding on submit path (`avatarUpload`/`coverUpload`) to prevent silent create-vs-update parity regressions.
- Cycle 22 implementation evidence:
  - Laravel tests added:
    - `tests/Unit/Map/PoiVisualNormalizerTest.php`
    - `tests/Feature/Map/MapPoisControllerTest.php::test_map_pois_exposes_visual_from_bson_type_projection_chain`
  - Flutter tests added:
    - `test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart::renders icon from transport payload without falling back to generic marker`
    - `test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart::createAccountFromForm forwards avatar and cover uploads`
  - Commands:
    - `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Map/PoiVisualNormalizerTest.php tests/Feature/Map/MapPoisControllerTest.php`
    - Result: `PASS` (15 tests).
    - `docker compose exec -T app sh -lc 'cd /var/www && php vendor/bin/pint --test tests/Unit/Map/PoiVisualNormalizerTest.php tests/Feature/Map/MapPoisControllerTest.php'`
    - Result: `PASS` (2 files).
    - `fvm flutter test test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
    - Result: `All tests passed!` (16 tests).
    - `fvm flutter analyze test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_marker_test.dart test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart`
    - Result: `No issues found!`
    - `fvm dart run custom_lint`
    - Result: `No issues found!`

### Cycle 23 — Contract Gap Diagnosis and Backend Serialization Fix (In Progress)
- [x] 🟧 Root-cause diagnosed for remaining icon-color/runtime drift:
  - `map/pois` response formatter omitted `visual.icon_color`, forcing Flutter fallback to default icon glyph color.
  - `map/filters` configured marker override normalization omitted `marker_override.icon_color`.
  - `map/filters` configured marker override/query parsing accepted only plain arrays, dropping BSON-shaped settings payloads.
- [x] 🟧 Applied backend contract fixes:
  - `MapPoiQueryFormatting::formatVisual` now validates/serializes `icon_color` (default `#FFFFFF` when absent, regex-enforced).
  - `MapPoiQueryService::normalizeConfiguredMarkerOverride` now validates/serializes `icon_color`.
  - `MapPoiQueryService::configuredCategoryMetadata` now normalizes BSON/document payloads for `query` and `marker_override` before validation.
  - Request validation for type visuals now requires `poi_visual.icon_color` in icon mode (`account_profile_types` + `static_profile_types`, store+update).
- [x] 🟧 Strengthened backend tests to prevent false-green regression:
  - `tests/Unit/Map/MapPoiQueryFormattingTest.php` now asserts `icon_color` passthrough.
  - `tests/Feature/Map/MapPoisControllerTest.php` now asserts `visual.icon_color` on lookup/stacks + filter `marker_override.icon_color`.
  - Added `test_map_filters_normalize_bson_marker_override_icon_color` for BSON-shaped settings payloads.
  - Updated profile/static type feature tests for required `poi_visual.icon_color` validation contract.
- [x] 🟧 Updated Flutter regression assertions to reflect canonical two-color icon contract:
  - settings filter-row preview fixture now provides explicit `iconColor`.
  - selected FAB test now asserts configured icon glyph color (non-white path).
- [x] 🟧 Validation rerun completed (Flutter + Laravel gates) and carried through pre-merge verification prior to `PR #163`.

### Cycle 25 — Projection Materialization Reliability (In Progress)
- [x] 🟧 Root-cause evidence captured from tenant artifact dump (`foundation_documentation/artifacts/tenant_guarappari.map_pois.json`):
  - projection docs contain no `visual` snapshot even on recent records;
  - runtime fallback is therefore expected regardless of Flutter marker parser improvements.
- [x] 🟧 Reopened blockers #5-#8 to force hard coverage on:
  - source create/update projection materialization with `visual`;
  - Event Type visual propagation/rematerialization;
  - `icon_color` parity in Event Type contract;
  - full chain validation (`source -> map_pois -> /map/pois`).
- [x] 🟧 RED completed:
  - Added fail-first coverage:
    - `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php::test_account_onboarding_projects_map_poi_with_type_visual_snapshot`
    - `tests/Feature/StaticAssets/StaticAssetsProjectionVisualTest.php::test_static_asset_create_projects_map_poi_with_type_visual_snapshot`
    - `tests/Feature/Events/EventCrudControllerTest.php::test_event_create_projects_map_poi_with_event_type_icon_color_snapshot`
    - `tests/Feature/Events/EventTypesControllerTest.php::test_event_type_update_rematerializes_visual_for_related_event_map_pois`
  - Initial RED failure captured: event projection icon glyph color persisted as fallback `#FFFFFF` instead of configured Event Type `icon_color`.
- [x] 🟧 GREEN completed:
  - Event Type contract extended with `icon_color` across:
    - model persistence (`EventType` fillable),
    - admin requests (`EventTypeStoreRequest`/`EventTypeUpdateRequest`),
    - registry payloads (`EventTypeRegistryService` + management merge/build),
    - event snapshot resolver (`EventManagementService`),
    - event query response payload (`EventQueryService`).
  - Event Type update now triggers projection refresh for all related events:
    - captures related event ids,
    - propagates type snapshot to events/occurrences,
    - dispatches `UpsertMapPoiFromEventJob` for each related event with forced checkpoint.
  - Map POI event upsert path hardened for source-change refresh:
    - `UpsertMapPoiFromEventJob` accepts optional `forcedCheckpoint`,
    - `MapPoiProjectionService::upsertFromEvent` now applies forced checkpoint in stale-write guard calculation.
- [x] 🟧 REFACTOR/validation completed:
  - Targeted suites:
    - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/StaticAssets/StaticAssetsProjectionVisualTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Events/EventTypesControllerTest.php`
      - Result: `PASS` (96 tests, 389 assertions).
    - `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/StaticAssets/StaticProfileTypesControllerTest.php`
      - Result: `PASS` (41 tests, 200 assertions).
  - Style gate:
    - `docker compose exec -T app php vendor/bin/pint --test <changed-files>`
      - Result: `PASS` (13 files).

### Cycle 26 — Dev Promotion Closure (Completed)
- [x] 🟣 Promoted delivery to `dev` via `PR #163` (`feat(map): POI visuals and filter marker override`).
- [x] 🟣 Post-merge actions/workflows completed successfully after merge.
- [x] 🟣 Flutter local branch synced to merged `dev` head (`6fbe0195`).
