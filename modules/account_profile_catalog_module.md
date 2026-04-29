# Documentation: Account Profile Catalog Module

**Version:** 1.0  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

**Authority note (2026-04-18):** this document is currently the authority for tenant-public account-profile catalog/detail/discovery contracts. The deferred `offer` concern should be read as a planned capability, not as a separate current runtime authority; whether that capability later stays here or is promoted to its own module is an implementation-time decision.

## 1. Overview

The Account Profile Catalog module (MOD-304) maintains the canonical representation of **account profiles** (restaurants, artists, guides, merchants) that operate within a tenant. In current project authority, this module is the real source for public account-profile catalog/detail/discovery contracts consumed by the Map & POI module, Tenant Home Composer, and Agenda Planner. Deferred offer/commercial wording in this file should be read as capability planning, not as a separate current runtime boundary.

### 1.1 Canonical Anchors

- System/platform references:
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- Cross-module references:
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/events_module.md`
- Tactical TODO streams:
  - `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - `foundation_documentation/todos/completed/TODO-store-release-account-profile-rich-text-fidelity.md`
  - `foundation_documentation/todos/completed/TODO-v1-public-account-profile-discovery-ui.md`
  - `foundation_documentation/todos/completed/TODO-v1-static-assets-media-parity-with-account-profiles.md`

---

## 2. Principles

1. **Value Objects Everywhere:** Current account-profile identity/media attributes should be expressed through dedicated value objects (for example `AccountProfileNameValue`, `HeroImageValue`, and related media/type value objects) so Flutter and Laravel layers do not juggle raw primitives across public/admin surfaces.
2. **Deferred Commercial Capability Rule:** when the future offer/commercial capability is implemented, it must use explicit `available_windows` objects (dates, days of week, time ranges) so map/agenda projections can reason about current vs. future availability without ad hoc local logic.
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
  "profile_type": "String",
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

### 3.2 Historical Deferred Offer Shape (Not Current Runtime Authority)
```json
{
  "_id": "ObjectId()",
  "account_profile_id": "ObjectId()",
  "tenant_id": "ObjectId()",
  "name": "String",
  "category": "String",
  "subcategories": ["String"],
  "pricing": {
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

This schema is retained as deferred capability planning residue. No current Laravel/Flutter runtime authority was found for a dedicated `offers` surface in the present code scan, so the offer/commercial concern remains capability-first until implementation decides whether it stays under this authority or is promoted elsewhere.

### 3.3 Historical Deferred Workspace Dashboard Shape (Not Current Runtime Authority)
Aggregated dashboard data remains a future authenticated workspace-facing read concern. It is retained here only as planning residue until a later `account_workspace` authority absorbs it.

---

## 4. Interfaces

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/v1/account_profiles` | GET | Tenant-public list constrained to favoritable + `visibility='public'` account profiles. |
| `/api/v1/account_profiles/near` | GET | Tenant-public distance-ordered account profiles for Discovery nearby surfaces (`is_favoritable=true` + `is_poi_enabled=true` + `visibility='public'`, nearest-first). |
| `/api/v1/account_profiles/{account_profile_slug}` | GET | Detailed account profile summary for consumer experiences via direct slug lookup (`is_active=true` + `visibility='public'` + favoritable type), including query-only ordered `agenda_occurrences` for agenda continuity. |

**Taxonomy term display snapshots**
- Account Profile read payloads expose structured taxonomy terms as display-ready snapshots: `{type, value, name, taxonomy_name, label?}`.
- `type`, `value`, and flattened `type:value` remain the only query/filter identities. `name`, `taxonomy_name`, and compatibility `label` are read/display metadata and must never become query keys.
- When taxonomy or taxonomy-term display names change, tenant-scoped fanout/backfill must repair persisted/read-model snapshots idempotently. Legacy `{type, value}` documents remain readable through fallback until repaired.

**Account Profile rich text fields**
- `bio` and `content` are independent capability-backed long-form rich-text fields. `profile_types.capabilities.has_bio` controls whether `bio` is rendered/authored, and `profile_types.capabilities.has_content` controls whether `content` is rendered/authored.
- Tenant-public `/parceiro/:slug` keeps one `Sobre` tab/shell. If both fields are present, `bio` renders first under the `Sobre` block label and `content` renders second under the `Conteúdo` block label. If only one field is present, the screen avoids a redundant nested heading and renders only the field body inside the tab.
- Account Profile rich text uses the shared safe subset: `<p>`, `<br>`, `<h1-6>`, `<ul>`, `<ol>`, `<li>`, `<blockquote>`, `<strong>`, `<em>`, `<s>`, plus emoji/plain text. Links, underline, inline code, colors, arbitrary HTML, and embedded media are not part of the Store Release contract.
- Legacy/plain-text values with newline breaks are canonicalized at render time so paragraph breaks and explicit line breaks remain visible.
- Backend persistence validates a dedicated `100KB` sanitized-content cap per field for `bio` and `content`; this does not raise global short-description limits for unrelated fields.

**Events**
* Current runtime authority: `account_profile.created`, `account_profile.updated`.
* Deferred only: `offer.published`, `offer.unavailable`, `offer.window.expired` are historical planning events and are not current runtime authority.

**Deferred only (not current runtime authority)**
- `/api/v1/offers`
- `/api/v1/offers/{offerId}`

### 4.1 Tenant-Public Discovery Listing Contract

**EnvironmentType:** `tenant`  
**Main scope:** `tenant_public`  
**Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/descobrir` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

Discovery runtime behavior for tenant-public account-profile listing is fixed as follows:
- Default discovery hierarchy uses `Tocando agora` + `Perto de você` as the top composition, followed by `Descubra` with registry-driven single-select category chips.
- Entering search mode hides `Tocando agora`, `Perto de você`, and the `Descubra` heading/chip chrome.
- While search mode is active with an empty query, the unfiltered base discovery grid remains visible; text filtering begins only after the user types.
- Discovery-side profile entrypoints continue to launch the canonical public account-profile detail route `/parceiro/:slug`; detail-route behavior is governed separately.

---

## 5. Dependencies

* **Map & POI Module:** Consumes account-profile public identity, location, and canonical type-visual inputs to materialize and render POI-linked discovery/detail entrypoints.
* **Commercial Engine (external, deferred capability):** would provide pricing references when the later offer/commercial capability is implemented.
* **Multidimensional Insights Service:** Supplies badge thresholds (e.g., “Top Account Profile of the Week”) that update `badges`.

---

## 6. Current Authority Posture

* **Current runtime-backed authority:** tenant-public account-profile list, near, and slug-detail contracts are live/current and remain governed here.
* **Current cross-module posture:** public discovery/detail contracts here feed map POI projection/read behavior, event-linked profile navigation, and tenant-home favorites/discovery surfaces.
* **Deferred continuation:** future workspace-facing CRUD, analytics, and commercial capability expansion must reuse this account-profile substrate without being treated as already-current runtime authority.

## 7. Canonical Decision Baseline

| Decision ID | Status | Decision | Impact | Canonical Evidence |
| --- | --- | --- | --- | --- |
| `PCO-01` | Approved | Account Profile is the canonical public identity layer for account-managed entities. | Keeps consumer and admin views aligned on one source. | Sections `1`, `3.1` |
| `PCO-02` | Approved | Offer availability uses explicit windows; map/agenda must consume those windows. | Enables deterministic time-based discovery behavior. | Sections `2`, `3.2` |
| `PCO-03` | Approved | Media metadata remains in catalog domain while binary storage is externalized. | Avoids tight infra coupling in domain contracts. | Section `2` |
| `PCO-04` | Approved | Public account-profile consumers must render canonical type `visual` from the profile-type registry; `mode=image` remains image-backed outside the map for `avatar`, `cover`, or canonical `type_asset`, preserves the configured visual color as an accent input, and only falls back when the required media is missing or invalid. | Eliminates local hardcoded type visuals and keeps public identity semantics consistent across detail/list/hero fallback surfaces. | Sections `1`, `4` |
| `PCO-05` | Approved | Tenant-public account-profile detail exposes ordered `agenda_occurrences` as a query-only projection derived from future/live published event occurrences; repeated `event_id` values remain distinct when `occurrence_id` differs, and the projection is never stored on `account_profiles`. | Allows the public detail route to materialize `Agenda` directly from occurrence-first data without turning the profile aggregate into an event-owned persistence surface or collapsing multiple future occurrences of the same event. | Sections `1`, `4` |
| `PCO-06` | Approved | Account-profile public identity uses surface-specific media precedence: hero/discovery backgrounds use `cover > avatar > type visuals`, compact rows use `avatar > cover > type visuals`, and shared identity-avatar blocks use the real avatar when present and otherwise fall back to the canonical `type visual` avatar surface. When a real avatar exists, the `type visual` becomes a badge overlay on that avatar instead of a textual label/chip. | Keeps discovery, nearby, home favorites previews, and public account-profile detail semantically consistent while preserving the distinction between surface media, personal/avatar identity, and type identity. | Sections `1`, `4`, `7` |
| `PCO-07` | Approved | Public/runtime account-profile type metadata is bootstrap-driven and additive: `label` remains the singular compatibility alias, while `labels.singular` / `labels.plural` are the canonical display fields for identity and grouped-category surfaces. | Allows shared account-profile/UI consumers to stop improvising singular/plural labels while keeping runtime reads cheap and tenant-admin source-of-truth aligned. | Sections `1`, `4`, `7` |
| `PCO-08` | Approved | Tenant-public account-profile detail uses the shared safe-back policy: when no previous route exists, `/parceiro/:slug` falls back to `/descobrir`; when history exists, the real previous route still wins. | Keeps direct-open public account-profile detail resilient while preserving normal in-app source continuity from discovery, home, map, and event-linked profile flows. | Sections `1`, `4`, `7` |
| `PCO-09` | Approved | Tenant-public account-profile discovery search mode hides the top discovery hierarchy chrome (`Tocando agora`, `Perto de você`, `Descubra`, and chips) while preserving the unfiltered base results grid until a non-empty query is entered. | Freezes the approved `/descobrir` search interaction so tactical TODO cleanup and future UI work do not reintroduce prompt-only empty-search behavior. | Sections `4.1`, `7` |
| `PCO-10` | Approved | This file is the canonical current authority for public account-profile contracts after the module-family rename. Deferred `offer`/commercial planning remains capability-first by default and does not become a separate current runtime surface unless later implementation proves that boundary. | Keeps module authority aligned with the renamed canonical surface without accidentally turning deferred commercial planning into current runtime truth. | Sections `1`, `3.2`, `4` |
| `PCO-11` | Approved | Account Profile `bio` and `content` are independent capability-backed long-form rich-text fields rendered inside the public `Sobre` shell with shared safe rich-text subset canonicalization and a dedicated `100KB` sanitized-content cap per field. | Fixes public detail/admin fidelity without turning unrelated short descriptions into page-sized content fields. | Sections `4`, `7` |
| `PCO-12` | Approved | Account Profile taxonomy terms are read/display snapshots using `{type, value, name, taxonomy_name, label?}` while filters stay on machine keys (`type`, `value`, `type:value`). | Prevents slug rendering in public/admin UI without adding runtime taxonomy joins to list/detail reads. | Sections `4`, `7` |

## 8. Tactical TODO Promotion Ledger

| TODO | Purpose | Promotion Status | Promoted Sections | Notes |
| --- | --- | --- | --- | --- |
| `TODO-vnext-tenant-user-account-profile-area.md` | Account/profile scope and contracts | In progress | `1.1`, `3`, `7` | Main stream for account profile domain hardening. |
| `TODO-v1-public-account-profile-discovery-ui.md` | Tenant-public discovery/listing contract and discovery-side CTA polish | Completed | `4`, `4.1`, `7` | Discovery search-mode/listing contract and the remaining V1 polish were accepted as launch-ready; no further Discovery follow-up remains in this lane. |
| `TODO-v1-tenant-public-safe-back-navigation.md` | Shared tenant-public account-profile-detail back/fallback policy | Completed | `4`, `7` | Freezes `/parceiro/:slug -> /descobrir` when root-opened; archived from `active` during the 2026-04-09 MVP TODO cleanup after delivery confirmation. |
| `TODO-store-release-account-profile-rich-text-fidelity.md` | Account Profile `bio`/`content` rich-text fidelity and long-form cap | Promotion Lane | `4`, `7` | Promotes the Store Release contract for independent capability-backed rich-text fields, public `Sobre` rendering, safe subset canonicalization, and `100KB` per-field sanitized-content validation. |
| `TODO-store-release-taxonomy-term-display-snapshots.md` | Taxonomy term display snapshots for account/profile/event/static/map read models | Promotion Lane | `4`, `7` | Promotes display-ready taxonomy snapshots while preserving machine-key filtering and idempotent backfill/fanout. |
