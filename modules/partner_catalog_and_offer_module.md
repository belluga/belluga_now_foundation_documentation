# Documentation: Account Profile Catalog & Offer Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Overview

The Account Profile Catalog & Offer module (MOD-304) maintains the canonical representation of **account profiles** (restaurants, artists, guides, merchants) that operate within a tenant. It exposes the offer graph consumed by the Map & POI module, Tenant Home Composer, and Agenda Planner. The module sits between account-facing tooling (future Account Profile Workspace) and consumer experiences, enforcing validation, media standards, and availability lifecycles.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/partner_admin_module.md`
  - `foundation_documentation/modules/events_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-user-account-profile-area.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-static-assets-media-parity-with-account-profiles.md`

---

## 2. Principles

1. **Value Objects Everywhere:** Every textual or media attribute is wrapped in value objects (`AccountProfileNameValue`, `HeroImageValue`, `OfferPriceValue`) stored within the module so Flutter and Laravel layers never juggle raw primitives.
2. **Availability Windows:** Offers declare explicit `available_windows` objects (dates, days of week, time ranges) to guarantee map and agenda projections can reason about current vs. future availability.
3. **Geo-Safe Modeling:** Account profile locations and POIs rely on normalized `geo_shapes` with both `lat/long` and `geohash` representations to align with the multi-tenant map stack.
4. **Decoupled Media Storage:** Media metadata lives in this module, but binary assets are uploaded to landlord-managed storage buckets. Documents store signed URLs plus invariants (resolution, aspect ratio).

---

## 3. Core Collections

### 3.1 `account_profiles`
```json
{
  "_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "legal_name": "String",
  "display_name": "String",
  "account_profile_type": "String",
  "tagline": "String",
  "description": "String",
  "media": {
    "hero_image": "String",
    "logo": "String",
    "gallery": ["String"]
  },
  "contact_channels": [
    { "type": "String", "value": "String", "is_verified": "Boolean" }
  ],
  "location": {
    "address": "String",
    "lat": "Number",
    "lng": "Number",
    "geohash": "String"
  },
  "badges": ["String"],
  "verification_flags": ["String"],
  "created_at": "Date",
  "updated_at": "Date"
}
```

### 3.2 `offers`
```json
{
  "_id": "ObjectId()",
  "account_profile_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "name": "String",
  "category": "String",
  "subcategories": ["String"],
  "pricing`: {
    "currency": "String",
    "amount": "Number",
    "pricing_model": "String"
  },
  "availability_windows": [
    {
      "start_at": "Date",
      "end_at": "Date",
      "days_of_week": ["String"],
      "time_ranges": [ { "start": "String", "end": "String" } ]
    }
  ],
  "poi_link": "ObjectId()",
  "status": "String",
  "created_at": "Date",
  "updated_at": "Date"
}
```

### 3.3 `account_profile_dashboards`
Aggregated data served to authenticated account operators once the workspace launches. Stores metrics, insights references, and invite stats.

---

## 4. Interfaces

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/account_profiles` | GET | Tenant-public list constrained to favoritable + `visibility='public'` account profiles. |
| `/api/v1/account_profiles/near` | GET | Tenant-public distance-ordered account profiles for Discovery nearby surfaces (`is_favoritable=true` + `is_poi_enabled=true` + `visibility='public'`, nearest-first). |
| `/api/v1/account_profiles/{account_profile_slug}` | GET | Detailed account profile summary for consumer experiences via direct slug lookup (`is_active=true` + `visibility='public'` + favoritable type), including query-only ordered `agenda_occurrences` for agenda continuity. |
| `/api/v1/offers` | GET | Offer catalog filtered by account profile, category, availability window. |
| `/api/v1/offers/{offerId}` | PATCH | Admin/account operator operation to update descriptions or windows (behind auth). |

**Events**
* `account_profile.created`, `account_profile.updated`, `offer.published`, `offer.unavailable`, `offer.window.expired`.

---

## 5. Dependencies

* **Map & POI Module:** Consumes account profile + offer data to render map markers.
* **Commercial Engine (external):** Provides pricing references when offers tie to real inventory or booking flows.
* **Multidimensional Insights Service:** Supplies badge thresholds (e.g., “Top Account Profile of the Week”) that update `badges`.

---

## 6. Roadmap

* **Phase 5:** Aligns with Flutter FCX-02 to serve mocked account profile feeds.
* **Phase 10:** Provides account-profile-driven home compositions and aggregated insights to Tenant Home Composer.
* **Phase 12:** Powers the Account Profile Workspace module, reusing the same schema for account profile CRUD operations.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `PCO-01` | Approved | Account Profile is the canonical public identity layer for account-managed entities. | Keeps consumer and admin views aligned on one source. | Sections `1`, `3.1` |
| `PCO-02` | Approved | Offer availability uses explicit windows; map/agenda must consume those windows. | Enables deterministic time-based discovery behavior. | Sections `2`, `3.2` |
| `PCO-03` | Approved | Media metadata remains in catalog domain while binary storage is externalized. | Avoids tight infra coupling in domain contracts. | Section `2` |
| `PCO-04` | Approved | Public account-profile consumers must render canonical type `visual` from the profile-type registry; `mode=image` remains image-backed outside the map for `avatar`, `cover`, or canonical `type_asset`, and only falls back when the required media is missing or invalid. | Eliminates local hardcoded type visuals and keeps public identity semantics consistent across detail/list/hero fallback surfaces. | Sections `1`, `4` |
| `PCO-05` | Approved | Tenant-public account-profile detail exposes ordered `agenda_occurrences` as a query-only projection derived from future/live published event occurrences; repeated `event_id` values remain distinct when `occurrence_id` differs, and the projection is never stored on `account_profiles`. | Allows the public detail route to materialize `Agenda` directly from occurrence-first data without turning the profile aggregate into an event-owned persistence surface or collapsing multiple future occurrences of the same event. | Sections `1`, `4` |
| `PCO-06` | Approved | Account-profile public identity uses surface-specific media precedence: hero/discovery backgrounds use `cover > avatar > type visuals`, compact rows use `avatar > cover > type visuals`, and shared identity-avatar blocks use the real avatar when present and otherwise fall back to the canonical `type visual` avatar surface. When a real avatar exists, the `type visual` becomes a badge overlay on that avatar instead of a textual label/chip. | Keeps discovery, nearby, home favorites previews, and partner detail semantically consistent while preserving the distinction between surface media, personal/avatar identity, and type identity. | Sections `1`, `4`, `7` |
| `PCO-07` | Approved | Public/runtime account-profile type metadata is bootstrap-driven and additive: `label` remains the singular compatibility alias, while `labels.singular` / `labels.plural` are the canonical display fields for identity and grouped-category surfaces. | Allows shared account-profile/UI consumers to stop improvising singular/plural labels while keeping runtime reads cheap and tenant-admin source-of-truth aligned. | Sections `1`, `4`, `7` |
| `PCO-08` | Approved | Tenant-public account-profile detail uses the shared safe-back policy: when no previous route exists, `/parceiro/:slug` falls back to `/descobrir`; when history exists, the real previous route still wins. | Keeps direct-open partner detail resilient while preserving normal in-app source continuity from discovery, home, map, and event-linked profile flows. | Sections `1`, `4`, `7` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-vnext-tenant-user-account-profile-area.md` | Account/profile scope and contracts | In progress | `1.1`, `3`, `7` | Main stream for account profile domain hardening. |
| `TODO-v1-account-profile-ui.md` | CRUD/form contract parity with backend | In progress | `4`, `7` | Ensures UI flows follow canonical catalog payloads. |
| `TODO-v1-tenant-public-safe-back-navigation.md` | Shared tenant-public partner-detail back/fallback policy | Completed | `4`, `7` | Freezes `/parceiro/:slug -> /descobrir` when root-opened; archived from `active` during the 2026-04-09 MVP TODO cleanup after delivery confirmation. |
