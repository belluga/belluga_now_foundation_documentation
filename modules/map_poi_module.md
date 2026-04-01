# Map & POI Module

## 1. Overview

This document outlines the architecture and data synchronization strategy for the Map and Points of Interest (POI) module. The module is responsible for displaying an interactive map to the user, populated with various points of interest such as restaurants, beaches, attractions, and time-sensitive events.

### 1.1 Canonical Anchors

- Events canonical module/contract:
  - `foundation_documentation/modules/events_module.md`
  - `laravel-app/packages/belluga/belluga_events/README.md`
- Tactical delivery references:
  - `foundation_documentation/todos/completed/TODO-v1-map-backend.md`
  - `foundation_documentation/todos/completed/TODO-v1-events-capability-map-poi.md`

### 1.2 Scope and Route Ownership (V1)

Primary ownership for this module is tenant runtime with explicit internal-only guard routes.

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/mapa` | tenant domain | `tenant` | `tenant_public` | n/a | tenant public session |
| `/mapa/poi` | tenant domain | `tenant` | `tenant_public` | n/a | tenant public session |
| `/location/permission` | tenant domain | `tenant` | `tenant_public` | n/a | location guard fallback route |

## 2. Current Runtime Implementation

The active Flutter map runtime is already Laravel-backed and query-driven:
- `CityMapRepository` resolves POIs and filters from Laravel HTTP services (`/api/v1/map/pois`, `/api/v1/map/filters`).
- Stack expansion uses the same POI endpoint with `stack_key`.
- Route hydration uses query params on both `/mapa` and `/mapa/poi` (`poi` + optional `stack`).
- Initial POI focus is condition-gated by observed map readiness events before moving camera.
- Deep-link initialization order is priority-driven: when `poi` query is present, the controller resolves/selects/queues POI focus first, while filters/default list/location refresh continue in parallel.

**Deep-Link Order Priority (current decision):**
- **Priority target:** the first meaningful map movement must honor explicit URL intent (`poi`) before non-blocking startup flows (filter catalog refresh, default list refresh, location refresh completion).
- **Motivation:** reduce perceived delay in direct-open/refresh deep links where POI exists but camera movement was delayed by unrelated startup requests.
- **Guardrail:** this is orchestration-only; it does not change route scope/host policy, does not introduce in-memory correctness dependencies, and keeps deterministic fallback (`POI do link não foi encontrado.`) when typed lookup cannot resolve.

**Shared Location Contract.** As part of FCX-02, the main Flutter application owns a `LocationRepository` + `UserLocationService` pair that lives in the domain layer. Controllers are the only consumers of repositories, so the service is injected into controllers, which then pass the user’s coordinates to downstream repositories (Map, Agenda, Task/Reminder). No repository is allowed to call another repository directly; when features need multiple data sources, controllers compose the calls or rely on lightweight domain services. This keeps dependency arrows pointing inward (controllers → repositories) and prevents caching or network responsibilities from leaking between repos.

**Location Permission Gate (Guard).** All screens whose behavior depends on current user location (e.g., the Map POI viewport, “nearby”/distance-ranked lists, and any future “near me” actions) are protected by dedicated guards. If location services are disabled or permission is not granted, navigation redirects to the single canonical public location gate at `/location/permission`, which:
- Explains why location is required (nearby venues, distance sorting, “search this area”).
- Offers a primary CTA to request permission (when possible) or open settings (when denied forever).
- Offers a CTA to open system location settings when the device-level service is disabled.

**Cached Location Mode (Non-Live).** When live location is blocked (service off / permission denied) but the app has a previously captured location, the client should remain usable:
- Show a “not live” screen explaining we’re using a possibly outdated location (with timestamp).
- Allow the user to continue using cached location for “nearby” ordering and map centering, while offering a CTA to re-enable live location.

## 3. Architecture Baseline: Server-Centric, Real-Time Ready

The module baseline is server-centric for geospatial search and projection reads, with realtime-ready contract surfaces. Backend owns query truth (`$geoNear`/viewport filters, visibility windows, typed references), while Flutter owns route/state orchestration and resilient fallback behavior for guarded/internal flows.

The realtime channel (`/api/v1/map/pois/stream`) remains defined and compatible with this model, but MVP execution stays polling/list-query first.

### 3.1. On-Demand Data Fetching (HTTP REST API)

The primary mechanism for fetching POIs will be an on-demand process driven by the user's interaction with the map. The client will request data based on the map's viewport and selected filters (including a **radius filter** expressed via `max_distance_meters`), and the server will handle all heavy lifting for geospatial queries. MongoDB's `$geoNear` aggregation already returns the calculated distance in meters, so every POI payload must include a `distance_meters` field.

**Radius semantics:** The radius filter is always anchored around a reference point (current user location by default, or a manually selected center supplied through the initial filter payload). While the user pans the map, the reference point does **not** change automatically; we continue to query “POIs within X meters of the reference point.” If the user wants to search the newly centered area, we surface a “Search this area” button — pressing it resets the reference point to the new center and reissues the radius-constrained fetch. This keeps “Max 10 km” intentions consistent regardless of map movement. The client caches the results of these calls to ensure smooth performance and provide a degree of offline functionality.

**Tenant settings (map_ui):** Radius defaults and bounds are configured via nested tenant settings:
```json
map_ui: {
  radius: { min_km: 1, default_km: 5, max_km: 50 },
  default_origin: { lat: Number, lng: Number, label: String? }, // optional
  poi_time_window_days: { past: 1, future: 30 }, // optional
  events: { default_duration_hours: 3 }, // optional
  filters: [ // optional; ordered list used by /map/filters decoration
    { key: "culture", label: "Cultura", image_uri: "https://.../culture.png" }
  ]
}
```
If tenant settings are missing, the defaults above apply. `default_origin` is used as the initial origin when user location is unavailable.

### 3.2. Real-Time Updates (SSE)

For instant updates like moving POIs and live offers, a persistent SSE connection will be used. The backend will push delta events to subscribed clients, which will update the UI in real-time without a full refresh.

### 3.3. User Interface and Interaction

#### 3.3.1. Filtering
Filtering is server-query driven (`categories`, `source`, `types`, `taxonomy`, `tags`, `search`, `max_distance_meters`) and controlled by controller-owned state.

**FAB filters (current Flutter behavior):**
- FAB category actions are built dynamically from `/api/v1/map/filters` categories (`label` + optional `image_uri`), including tenant-admin decoration via `settings.map_ui.filters`.
- Each category can ship a normalized backend `query` payload (`source`, `types[]`, `categories[]`, `taxonomy[]`, `tags[]`); tapping a FAB applies this payload directly.
- If a category has no explicit query payload, the fallback behavior applies category key filtering (`categories=[key]`).
- Tapping an already-active FAB clears filters (toggle-off behavior).
- `Limpar filtros` appears whenever category/taxonomy filters are active.
- While map loading/filter reload is in flight, FAB interactions are locked to prevent overlapping filter requests.

**Deep-link filter payload support (contract):**
- Controllers may accept initial filter payloads from upstream surfaces.
- When provided, first-frame UI must reflect active filters and issue the corresponding server query deterministically.

#### 3.3.2. POI Details Card & Actions
When a user taps a POI, a details card will appear with "Details", "Share", and "Route" buttons.

#### 3.3.3. Core UI Logic and Polish
-   **Visual Stacking Order:** To meet business goals, POIs must be rendered in a specific vertical order. The map client must render markers with a z-index based on a `priority` field in the POI data model (e.g., Sponsors on top, then Live Events, then other Events, then all other POIs).
-   **Deselection Logic:** The POI details card must close automatically if the user clicks on the map outside the card or begins to drag the map, signifying a loss of focus.
-   **Mouseover Effect (Web):** On the web platform, hovering over a POI marker should increase its z-index to bring it to the front.

## 4. API Contract (Current Runtime + Pending Additions)

The runtime already consumes REST APIs for on-demand queries and defines SSE compatibility surfaces for future realtime adoption. POI payloads include `priority` to control visual stacking order.

### 3.4 POI Type Registry & Navigation
- **Normalized IDs/Slugs:** Every custom object (poi, event, artist) exposes `id`, `slug`, and `type`.
- **Current map route hydration:** Flutter map routes hydrate by query key (`poi=<ref_type>:<ref_id>`; fallback to raw `id`) plus optional `stack`, not by slug path params.
- **Navigation keys:** `ref_type + ref_id` is the current canonical map deep-link key. `slug/ref_path` remain relevant for detail/share surfaces.
- **POI Type Registry:** `poi_types` defines how to map external sources into normalized POIs without per-request pipelines.
  ```json
  {
    "slug": "restaurant",
    "label": "Restaurante",
    "kind": "venue|restaurant|beach|historic|generic",
    "pipeline": {
      "source": "collection_or_view",
      "match_template": { /* whitelisted predicates, e.g., bbox, term_ids */ },
      "projection": { "id": "$_id", "name": "$name", "lat": "$lat", "lng": "$lng", "address": "$address" },
      "field_map": { "lat": "location.lat", "lng": "location.lng" },
      "cache_ttl_seconds": 900,
      "max_page_size": 100
    },
    "route": { "name": "poi_detail", "params": ["slug"] }
  }
  ```
- **Lookup Flow:** Pipelines are only used upstream to produce/update normalized POI documents. Reads (agenda, map, event detail) never run pipelines. Current map lookup resolves by typed reference (`ref_type/ref_id`) through loaded payload + optional stack expansion and, when needed, deterministic single-POI lookup (`/map/pois/lookup`).

### 3.5 Custom Objects & Taxonomies
- **Custom Object Types:** `poi`, `event`, `artist`. All share the normalized shape `{ id, slug, type }` for routing and linking. Detail/share flows can stay slug-driven, while map deep-link hydration remains typed-reference (`ref_type + ref_id`) driven.
- **Taxonomies/Terms:** `taxonomies` define `{ id, slug, name, applies_to: [poi|event|artist] }`; `terms` carry `{ id, slug, name, taxonomy_id }`. Object-term links use `{ object_type, object_id, term_id }`.
- **Usage:**
  - POIs own their terms (e.g., `cuisine`, `ambience`, `vibe`).
  - Artists own `genres` terms.
  - Events may supply `tags`; if empty, clients derive chips from linked artist genres (and, optionally, POI terms if exposed).
- **Navigation:** Detail actions are resolved from `type` + `slug` using a registry map, not IDs. Backend must ensure slug uniqueness per type and return both slug and id for robustness.

### 3.6 Materialized `map_pois` (Projection Records, Optional Time Anchor)

For V1, we treat `map_pois` as a **materialized projection/read model** used by the map experience. Whenever an Account Profile, Event, or Static Asset is created/updated (by landlord/tenant admins; memberships are deferred), we write/update a linked `map_pois` record in the same logical transaction.

Key properties:
- `map_pois` is the map projection (geometry + category + tags + priority + deep-link reference).
- For `ref_type=static`, `map_pois.category` is derived from `static_profile_types.map_category` (fallback to `static_assets.profile_type`). `static_assets.categories[]` is legacy metadata and must not drive map categorization.
- `map_pois.visual` is projection-owned and resolved from type-level `poi_visual` + item media (`avatar_url`/`cover_url`) when applicable.
- The record may carry optional `active_window_start_at` + `active_window_end_at` (nullable). We do **not** store `visible_from`/`visible_until`. Visibility windows are computed at query time using backend-owned tenant settings and the **user timezone** stored on the user profile.
- Account Profile/Static Profile types control POI projection via capabilities. When `is_poi_enabled=false`, existing `map_pois` for that type are hard-deleted.

**Reference linkage (required):**
```json
{
  "ref_type": "account_profile|event|static",
  "ref_id": "ObjectId() | null"
}
```

**Time anchor (optional):**
```json
{
  "active_window_start_at": "Date | null",
  "active_window_end_at": "Date | null"
}
```

Query-time window policy (backend settings example):
- include time-windowed POIs where `active_window_start_at <= endOfDay(now + future_window_days)`
- and `active_window_end_at >= startOfDay(now - past_window_days)`
- POIs without `active_window_*` are always eligible (subject to `is_active`, viewport, and filters)

This ensures future events/campaigns do not appear immediately when created, while still allowing the backend to tune visibility without rewriting data.

**POI visual snapshot contract (required):**
```json
{
  "visual": {
    "mode": "icon|image",
    "icon": "string?",
    "color": "#RRGGBB?",
    "image_uri": "https://...?",
    "source": "type_definition|item_media"
  }
}
```

- `mode=icon` requires `icon + color`.
- `mode=image` requires `image_uri`.
- Invalid/unresolvable visual inputs must clear/omit `visual`; Flutter applies one generic fallback marker path.

**Projection refresh triggers (required):**
- Item media change (`avatar_url`/`cover_url`) -> full POI re-materialization for affected refs.
- Item type change (`profile_type`) -> full POI re-materialization for affected refs.
- Type visual change (`poi_visual`) -> full POI re-materialization for all affected refs.
- Type `is_poi_enabled=true` -> full POI re-materialization for affected refs.
- Type `is_poi_enabled=false` -> hard-delete affected POI projections.

**Temporary Static POIs (VNext):** Static Assets may be marked as temporary (`is_temporary`) with a date range. A background job flips `is_active` based on the window. Map queries always filter by `is_active` only (no time logic at query time). In MVP, `is_active` is managed manually.

#### Example: “Promotional Coupon” Custom Object (POI-enabled)

Scenario: An account profile wants a time-bound promotion (“Pague 1, leve 2” de cerveja). This can be modeled as a POI-enabled custom object type (it may be “account-profile-like” from the map’s point of view, but it is not required to be an Account Profile record).

Custom object fields (example):
```json
{
  "type": "promotional_coupon",
  "title": "Pague 1, leve 2 (Cerveja)",
  "details": "Válido das 18h às 22h, somente hoje.",
  "account_profile_id": "ObjectId()",
  "starts_at": "Date",
  "ends_at": "Date",
  "location": { "type": "Point", "coordinates": [-40.498383, -20.673067] },
  "tags": ["cerveja", "promo", "happy-hour"],
  "coupon_code": "String"
}
```

Projection into `map_pois` on save (example):
```json
{
  "ref_type": "custom_object",
  "ref_id": "ObjectId()",
  "category": "sponsor",
  "name": "Pague 1, leve 2 (Cerveja)",
  "description": "Válido das 18h às 22h, somente hoje.",
  "location": { "type": "Point", "coordinates": [-40.498383, -20.673067] },
  "tags": ["cerveja", "promo", "happy-hour"],
  "priority": 10,
  "is_active": true,
  "active_window_start_at": "2025-12-13T18:00:00Z",
  "active_window_end_at": "2025-12-13T21:00:00Z"
}
```

Notes:
- The POI exists as a projection; the canonical coupon object holds the full details and validation rules.
- `active_window_*` defaults to `event.date_time_start` and `event.date_time_end` (or `start + settings.events.default_duration_hours`).

### 3.7 Same-Spot POIs (Stacking, Deduplication, and Performance)

Multiple POIs can share the same coordinates (e.g., venue + promotion + event + nearby beach marker snapping to the same point). We handle this explicitly so UX and metrics remain deterministic.

#### A) Model-level rules
- `ref_type + ref_id` is the identity; never duplicate the same reference as multiple POIs.
- Use `priority` as the primary sort key (higher number = higher priority).
- Use a stable tiebreaker (e.g., `ref_type` precedence + `ref_id`) to keep ordering deterministic across refreshes.

#### B) Stacking vs Clustering (Two Scenarios We Must Support)

We explicitly support both scenarios:

1. **Exact same location (stacking):** multiple POIs intentionally share the *same* point (e.g., a venue, an event at that venue, and a time-bound promotional coupon).
2. **Near same location (clustering):** POIs are near each other but not identical (zoomed-out map density).

For V1, we implement (1) as the default and defer (2) unless density demands it.

#### C) Coordinate Normalization (Required for Exact-Key Stacks)

To make “exact same location” deterministic across writes, the backend must normalize coordinates when persisting `map_pois`:
- Store `location.coordinates` rounded to a fixed precision (5 decimals).
- Derive an `exact_key` from the normalized coordinates (string or separate fields), e.g. `"lat,lng"`.

This avoids float noise causing two logically identical points to fail stacking.

#### D) API strategy (Recommended: Backend Returns Stacks)

Backend returns “stacks” for same-spot POIs; each stack has a top POI and a count.

**V1 stack grouping:**
- Group by `exact_key` only (same normalized coordinates).

**VNext clustering (optional):**
- Also compute `cluster_key` based on zoom-dependent precision (e.g., geohash/H3/S2 or bucket rounding).
- Group by `cluster_key` at low zoom levels to reduce payload and marker density, while preserving sub-stacks by `exact_key` inside a cluster when presenting the deck.

Response shape (example):
```json
{
  "stack_key": "String",
  "center": { "lat": "Number", "lng": "Number" },
  "top_poi": { /* POI */ },
  "stack_count": "Number",
  "items": [ /* POIs ordered by priority */ ]
}
```

#### E) UX rules
- The marker shows the top POI (highest priority) and a badge like “+3”.
- Tapping opens a selector/deck:
  - items ordered by `priority` then stable tiebreaker
  - selecting an item pins it for that session (until map refresh or user clears selection)

#### F) Query performance considerations
- Keep 2dsphere index on `location`.
- Filter first by viewport + category/tags; only then apply stacking/grouping.
- For V1, group by `exact_key` (normalized coordinates). For VNext clustering, group by `cluster_key` derived from zoom.

### 4.1. REST API (On-Demand Queries)

1.  **Primary POI Endpoint:** `GET /api/v1/map/pois`
    -   Purpose: return POIs within the current viewport and/or within a max distance anchored around a reference point (typically the user’s current position).
    -   Parameters (query string):
        - `ne_lat`, `ne_lng`, `sw_lat`, `sw_lng` (optional but recommended): current map viewport bounds.
        - `origin_lat`, `origin_lng` (optional): reference point for `$geoNear` / distance ordering; if omitted, backend may derive from viewport center.
        - `max_distance_meters` (optional): radius filter anchored to origin; when present, backend must compute `distance_meters`.
        - `categories[]`, `source`, `types[]`, `tags[]`, `taxonomy[]`, `search` (optional): filters and free-text matching (taxonomy entries are `{type, value}` pairs).
          - `source`: canonical source selector (`event|account_profile|static`).
          - `types[]`: dynamic source-type slugs mapped from projection field `source_type`.
        - `sort` (optional): `priority`, `distance`, `time_to_event`.
    -   Backend enforcement:
        - Use MongoDB geospatial queries (`$geoNear` and/or `$geoWithin`) as the authoritative source of “nearby” truth.
        - When `origin_lat/lng` is provided, return `distance_meters` for each POI.
        - Apply time-window filters for `active_window_*` using backend-owned tenant settings (future/past **days**). The client should not hardcode visibility windows.
    -   Response fields: **stack groups** keyed by `stack_key`, each with `center`, `stack_count`, and a `top_poi` payload. The `top_poi.updated_at` field is required for polling cache validation. `top_poi.visual` must follow the projection visual contract (`mode`, `icon/color` or `image_uri`, `source`). `tags[]` and `taxonomy_terms[]` are **filter-only** and are not returned in this payload. When sorting by `distance`, backend orders by ascending distance while still honoring priority tiers (sponsors > live events > others).
    -   Deep-link contract note: this endpoint is viewport/origin scoped and does not guarantee global `poi`-only resolution for arbitrary `ref_type/ref_id` links.
2.  **Nearby Card List Endpoint:** `GET /api/v1/map/near`
    -   Purpose: return a distance-ordered list of POI cards, paginated (default 10/page), with richer fields for navigation.
    -   Parameters (query string):
        - `origin_lat`, `origin_lng` (required).
        - `max_distance_meters` (optional).
        - `categories[]`, `tags[]`, `taxonomy[]`, `search` (optional).
        - `page`, `page_size` (optional; default 10).
    -   Response fields: `ref_type`, `ref_id`, `ref_slug`, `ref_path` (`/{ref_type}/{ref_slug}`), `title`, `subtitle?`, `category`, `location`, `distance_meters`, `updated_at`, `avatar_url?`, `cover_url?`, `visual?`, `badge?`, `time_start?`, `time_end?`, plus `tags[]` and `taxonomy_terms[]`.
3.  **Filter Discovery Endpoint:** `GET /api/v1/map/filters`
    -   Returns all available categories and their associated tags to dynamically build the filter UI.
    -   Category payload can be decorated by `settings.map_ui.filters` (tenant-admin managed):
        - `label` override per key;
        - optional `image_uri`;
        - optional marker override (`override_marker`, `marker_override.mode`, `marker_override.icon`, `marker_override.color`, `marker_override.image_uri`);
        - normalized `query` payload (`source`, `types[]`, `categories[]`, `taxonomy[]`, `tags[]`) used by Flutter when applying a category.
        - configured list ordering first, with configured entries retained even when `count = 0`.
    -   Taxonomy catalog is sourced from POI taxonomy aggregations and applied as advanced filters when needed.
4.  **POI Lookup Endpoint:** `GET /api/v1/map/pois/lookup`
    -   Purpose: deterministic lookup for a single POI by canonical typed reference, independent of viewport/origin.
    -   Parameters (query string):
        - `ref_type` (required)
        - `ref_id` (required)
    -   Response fields: canonical POI payload compatible with map deep-link hydration (`ref_type`, `ref_id`, `ref_slug`, `ref_path`, `location`, `updated_at`, `visual?`, `stack_key?`, `stack_count?`).
    -   Status: implemented and covered by feature tests (`MapPoisControllerTest`) for successful typed lookup + deterministic not-found behavior.

### 4.2. SSE API (Real-Time Events)

The client will connect to an SSE endpoint and subscribe to events for the visible map area.
-   **Server pushes events:** `poi.created`, `poi.updated`, `poi.deleted`.
    - **Endpoint:** `GET /api/v1/map/pois/stream` (filters match `/api/v1/map/pois`).

## 5. Roadmap and Strategic Decisions

### 5.1. Phased Rollout
-   **v0.1 (Lean MVP):** The initial launch will focus on the core B2C experience, primarily listing events and static POIs. The full real-time architecture will be built, but the features may not be exposed in the UI.
-   **v1.1 (Fast-Follow):** Advanced real-time features like "moving POIs" and "live offers" will be fully enabled in the UI.

### 5.2. Unified Codebase
-   The "Account Workspace" (Account Profile management) or landlord functionality for managing POIs and offers will not be a separate application. It will be a different mode or build flavor within the main Flutter codebase, ensuring efficiency and code reuse.

### 5.3. Implementation Roadmap
-   **Phase 1 (Complete):** Laravel-backed runtime wired for map POIs + filters + stack expansion.
-   **Phase 2 (Complete):** Dynamic FAB category filters from backend catalog (`/map/filters`) with tenant-admin decoration and controller-owned lock/reload behavior.
-   **Phase 3 (Complete):** URL-only route hydration hardening (`poi + stack` routes + internal-only fallbacks + deep-link order-priority focus behavior).
-   **Phase 4 (Complete):** Backend typed single-POI lookup (`/map/pois/lookup`) delivered to close global `poi`-only deep-link resolution.
-   **Phase 5 (Deferred MVP):** SSE stream adoption for map deltas (`/map/pois/stream`), keeping polling/list endpoints as source of truth in MVP.

## 6. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `MAP-01` | Approved | `map_pois` is a materialized projection/read model; source domains publish projection updates. | Keeps map queries fast and decoupled from source collections. | Section `3.6` |
| `MAP-02` | Approved | Event linkage uses typed reference (`place_ref`/`ref_type+ref_id`), not legacy direct venue ownership assumptions. | Aligns map integration with current Events contract. | Sections `1.1`, `3.4`, `3.6` |
| `MAP-03` | Approved | Same-spot POIs are deterministic via normalized coordinates and stack grouping. | Avoids marker jitter and duplicate-point instability. | Section `3.7` |
| `MAP-04` | Approved | Visibility windows are backend-owned and timezone-aware; clients do not hardcode time windows. | Consistent POI visibility and lower client drift risk. | Sections `3.6`, `4.1` |
| `MAP-05` | Approved | Global `poi`-only deep links resolve through backend single-POI typed lookup (`ref_type` + `ref_id`) independent of viewport/origin list payloads. | Eliminates false not-found for valid POIs outside initial map payload windows. | Section `4.1` |
| `MAP-06` | Approved | Deep-link startup gives URL POI intent (`poi`) higher orchestration priority than non-blocking startup refreshes; POI focus is prepared early and applied once map-ready conditions are met. | Reduces time-to-focus for direct-open/refresh links without changing architecture boundaries or fallback semantics. | Section `2` |
| `MAP-07` | Approved | POI marker visuals are type-driven (`poi_visual`) and consolidated into projection-owned `map_pois.visual`; clients consume projection snapshot directly. | Removes runtime hardcoded visual coupling and keeps marker behavior deterministic across clients. | Sections `3.6`, `4.1` |
| `MAP-08` | Approved | Disabling `is_poi_enabled` for a type hard-deletes affected projections; enabling or visual changes trigger full re-materialization. | Keeps projection state coherent with type capability/visual source of truth. | Section `3.6` |

## 7. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-v1-map-backend.md` | Map package extraction and backend contract ownership | Production-Ready | `1.1`, `3.6`, `4`, `6` | Package ownership complete (`belluga_map_pois`), including internal rebuild command. |
| `TODO-v1-map-frontend.md` | Flutter map UX + filter/stacking consumption | In progress | `3.3`, `4.1`, `5` | Client contract alignment stream. |
| `TODO-v1-map-icon-color-config.md` | Type-driven POI visuals + filter marker override + hard-delete/rematerialization contract | In progress | `3.6`, `4.1`, `6` | Local implementation and test coverage delivered; lane promotion pending. |
| `TODO-v1-route-url-only-hydration-hardening.md` | URL-only route hydration + internal-only fallback hardening | Production-Ready | `4.1`, `6` | `poi + stack` + `poi`-only deterministic lookup delivered end-to-end. |
| `TODO-v1-events-capability-map-poi.md` | Events capability decisions for POI projection | Promoted | `1.1`, `3.6`, `6` | Completed and promoted into module baseline. |
