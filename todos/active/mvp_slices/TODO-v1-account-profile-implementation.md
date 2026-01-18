# TODO (V1): Account Profile Implementation (Backend + Contracts)
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Deliver the Account Profile model and required contracts as a **project-specific** (not boilerplate) **1:1 identity layer** under Account, with **optional Organization grouping**. This enables account-profile-facing flows (invites, map, offers, push) without introducing memberships in MVP.

## Scope
- Define Account Profile data model and contracts (generic, project-owned).
- Lock **Account = 1 Profile** and document ownership semantics (`ownership_state`).
- Introduce **Organization (optional)** for grouping accounts of the same real-world entity (tenant orgs, sponsors, hotel groups).
- Update endpoints/contracts where Account Profile is the required context (invites, push, map POIs, offers, discovery).
- Ensure Account Profile optional `location` rule is explicit (geo index only for profiles with location).
- Define admin-assigned operator linkage for MVP (no memberships yet).
- Capture the user→influencer upgrade flow (Account + AccountProfile + operator link).
- Define the **MVP admin/tenant endpoints** required by the Tenant User Area (Account + Account Profile creation, optional Organization creation).

## Out of Scope
- Full memberships/roles system (post‑MVP).
- Dashboard/analytics UI (post‑MVP).
- Flutter UI implementation work.

## Definition of Done
- Account Profile schema and required fields documented in foundation docs.
- **Account = 1 Profile** and `ownership_state` semantics documented (tenant_owned/unmanaged/user_owned).
- **Organization (optional)** documented with minimal MVP scope (grouping only).
- Required API contracts reference `account_profile_id` where applicable.
- Admin-assigned operator linkage is documented for MVP.
- Optional geo location + geo index behavior is documented.
- User→influencer upgrade path is documented.
- MVP admin/tenant endpoints for Account + Account Profile creation (and optional Organization creation) are defined and referenced by the Tenant User Area TODO.

## Validation Steps
- Manual doc review: ensure Account Profile references are consistent across modules and contracts.
- Verify invite/push/map/offers contracts reference `account_profile_id`.
- Verify Tenant User Area endpoints are enumerated in this TODO and match UI flows.

## Decisions
- Account Profile is **project-specific** (not boilerplate) with project-defined `profile_type` values.
- **Account = 1 Profile** (1:1). No 1:N profile lists under a single Account.
- **Organization is optional**, used to group **accounts of the same entity** (tenant orgs, sponsors, hotel groups). Most accounts will not belong to an org.
- `ownership_state` is the **single conceptual flag** for account ownership (derived in MVP):
  - `tenant_owned` (official tenant-owned accounts, may be in org or standalone)
  - `unmanaged` (tenant-managed but not owned; **standalone**, no org)
  - `user_owned` (personal accounts, created with the user; typically standalone in MVP)
- `managed_by` is **derived** from `ownership_state`, not stored.
- Account is the **permission boundary** (roles/ACL). Account Profile actions **require `account_profile_id`** but are authorized via Account membership.
- `location` is optional; only geo-enabled profiles are indexed/queryable.
- MVP operators are admin-assigned (memberships deferred).
- **User-owned accounts (MVP):** only the **auto-created personal** account exists (private by default).
- **Creation timing:** personal Account + Account Profile are created **on first authenticated identification** (login/register), not at anonymous identity creation.
- **Register response:** `POST /api/v1/auth/register/password` must return the same payload as `GET /api/v1/me` (or embed a `me` object) so the client does not need a follow-up request.
- **Personal profile upgrades** (influencer/artist/curator) are **type changes** within the personal tree (no new account). Upgrade flow is **post‑MVP**.
- **User claims & additional business accounts (Post‑MVP):** claiming an unmanaged account or creating additional business accounts is **deferred**. When enabled, claiming transitions `ownership_state` from `unmanaged` → `user_owned` and keeps Account as the permission boundary.
- User→influencer upgrade (when unlocked) creates Account + AccountProfile + operator link if not already present.
- Tenant User Area is **frontend-only**; backend endpoints are defined here.

## Questions to Close
- None.

---

## Pending Decisions (to Close Before Implementation)

### D1) Profile type enum (API canonical values)
**Decision (Final):** **No hardcoded enum in code.** Profile types are defined by a **Profile Type Registry** (hierarchical, WP‑like) sourced from tenant settings. The registry is the sole source of truth.
**Implications:** UI, API validation, and projections must reference the registry, not compile‑time enums.
**Subtopics (Finalized under D1):**
- **Restaurants without events:** Use a child type under `venue` (e.g., `restaurant` with `parent_type=venue`). Do **not** create a separate hard enum. 
- **Shopping centers + markets (“feiras”):** Use child types under `venue` (e.g., `marketplace`, `farmers_market`, `street_market`/`open_air_market`, `mall`/`shopping_center`).
- **Personal vs Business roots:** Maintain separate roots in the registry (e.g., `personal` vs `business`), with influencer/curator/artist under personal and venue/experience_provider under business. Root grouping is a registry concern, not a schema fork.
- **Taxonomies:** Apply taxonomy **directly to the child types** if it should not be available on all venues. Example: `cuisines` applies to `restaurant` and `marketplace`, not to parent `venue`.
- **Inheritance rule:** Child types **inherit** parent taxonomies and capabilities. Children **cannot remove or override** parent behavior; they only add.

### D2) Account Profile required fields
**Decision (Final):** `account_id`, `profile_type`, `display_name`, `slug` (server‑generated, immutable).
**Reasoning:** Matches current UI expectations (`PartnerModel`) and routing by slug while keeping stable URLs.

### D3) Optional fields (MVP)
**Decision (Final):** `avatar_url`, `cover_url`, `bio`, `taxonomy_terms[]` only.
**Notes:** `taxonomy_terms[]` are set by landlord users in MVP (not end users).  
**Deferred (post‑MVP):** `tags[]`, `is_verified` (verification flow).

### D4) Location schema (storage + response)
**Decision (Final):** store **only GeoJSON** for indexing, and **project lat/lng in response** (no duplication).
- **Storage:** `location` as GeoJSON Point (`{ type: "Point", coordinates: [lng, lat] }`) with 2dsphere index.
- **Response:** `location { lat, lng, address?, status? }` derived from GeoJSON.
**Reasoning:** Keeps MongoDB geospatial compatibility while avoiding duplicated lat/lng fields.

### D5) Engagement metrics shape
**Decision (Final):** Defer **all** engagement metrics to post‑MVP.
**Notes:** MVP profile payloads exclude `engagement_metrics`; UI should hide or mark “coming soon”.

### D6) Derived/read‑only fields
**Decision (Final):** `distance_meters` and `upcoming_event_ids` are **query‑only projections** (never stored).
**Reasoning:** Distance comes from `$geoNear`; upcoming events come from event queries filtered by `account_profile_id`.

### D7) Slug rules
**Decision (Final):** slug unique per tenant across all profiles; generated from `display_name` on create.  
**MVP behavior:** treat as **immutable** (no slug update endpoints or flows).  
**VNext note:** consider a dedicated slug update endpoint (audited, rate‑limited, redirect mapping).

### D8) Capabilities flags
**Decision (Final):** `capabilities` is a **data map** derived from the Profile Type Registry (no per‑capability classes in MVP).
- MVP keys: `is_favoritable`, `is_poi_enabled` (defaults from registry).
- **VNext:** introduce a `CapabilityGate` evaluator for complex capabilities (e.g., `can_issue_invites`) that combines profile flags + user abilities + tenant settings + plan limits.
**Reasoning:** Keeps registry flexible while allowing future policy logic without class explosion.

### D9) Audit fields
**Decision (Final):** `created_by`, `updated_by`, `*_by_type` on **Account + Account Profile**.  
**Ownership_state (MVP):** derived (not required in payload); can be persisted later without breaking contracts.
**Reasoning:** Ownership is an account-level rule. Use a single explicit flag to separate tenant_owned vs unmanaged vs user_owned while keeping audit fields consistent.

### D10) Operator linkage in MVP
**Decision:** **Deferred to VNext.**  
Track in `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-claim-flow.md`.

### D11) Profile Type Registry (WP‑like, typed)
**Decision (Final):** Use a typed `profile_type_registry` (like WP custom post types) that defines labels, hierarchy, allowed taxonomies, capabilities, and default UI modules. The Account Profile model remains strongly‑typed (no freeform meta).
**Runtime source:** The registry is **fetched from tenant settings** and cached locally; it is **not** hardcoded in the client or backend.
**Cache/refresh rules (Final):**
- **Cache‑first:** App boot uses cached registry immediately (no blocking).
- **Async refresh:** Registry refresh happens after initialization.
- **No hardcoded fallback:** If refresh fails and **no cache exists**, the app must surface an explicit error and avoid rendering type‑dependent UI. Only **real cached data** is acceptable as fallback.
- **If cache exists:** continue with cached registry and attempt refresh in background.
**Proposed schema (V1 example, hierarchical):**
```json
{
  "profile_type_registry": [
    {
      "type": "artist",
      "label": "Artista",
      "parent_type": null,
      "allowed_taxonomies": ["music_genres"],
      "capabilities": { "is_favoritable": true, "is_poi_enabled": false },
      "default_modules": ["bio", "agenda"]
    },
    {
      "type": "venue",
      "label": "Local",
      "parent_type": null,
      "allowed_taxonomies": ["cuisines", "music_genres", "experiences"],
      "capabilities": { "is_favoritable": true, "is_poi_enabled": true },
      "default_modules": ["about", "location", "agenda"]
    },
    {
      "type": "restaurant",
      "label": "Restaurante",
      "parent_type": "venue",
      "allowed_taxonomies": ["cuisines"],
      "capabilities": { "is_favoritable": true, "is_poi_enabled": true }
    }
  ]
}
```

### D12) Static Assets vs Account Profiles
**Decision:** Keep Static Assets as a separate entity (not an Account Profile type).
**Reasoning:** Static assets are tenant/landlord‑managed POIs without operators or account ownership. They should project into `map_pois` with `ref_type=static` rather than pollute account‑profile identity and invite/favorite semantics.

---

## MVP Admin/Tenant Endpoints (Required by Tenant User Area)

### Organizations (Optional, MVP)
- `GET /api/v1/organizations` — list organizations (tenant scope; landlord user only)
- `POST /api/v1/organizations` — create organization (landlord user only)
- `GET /api/v1/organizations/{id}` — organization detail (landlord user only)

### Accounts
- `GET /api/v1/accounts` — list accounts (tenant scope; landlord user only)
- `POST /api/v1/accounts` — create account (landlord user only)
- `GET /api/v1/accounts/{id}` — account detail (landlord user only)

### Account Profiles
- `GET /api/v1/account_profiles?account_id=...` — list profiles for an account (landlord user only)
- `POST /api/v1/account_profiles` — create account profile (**requires `account_id`**; landlord user only)
- `GET /api/v1/account_profiles/{id}` — profile detail (landlord user only)
- `PATCH /api/v1/account_profiles/{id}` — edit basic fields (landlord user only)

### Optional (if UI needs it)
- `GET /api/v1/account_profile_types` — list supported `profile_type` values (landlord user only)

### MVP Access Rules (Admin/Tenant)
- Landlord user only via tenant‑scoped routes (no memberships yet).

### Payload Minimums (MVP)
- **Organization create:** `name` (required), optional `slug` if needed by tenant.
- **Account create (boilerplate):** `name` (required), `document { type, number }` (required).  
  **MVP ownership_state is derived** (no required field in payload).  
  - `unmanaged`: no operator attached + not in tenant org  
  - `tenant_owned`: attached to tenant org  
  - `user_owned`: auto personal account created on first authentication
- **Account Profile create:** `account_id` (required), `profile_type` (required), `display_name` (required),
  **conditional** `location` `{ lat, lng }` required when `capabilities.is_poi_enabled=true`,
  optional `taxonomy_terms`, `bio`, `avatar_url`, `cover_url`.

### Operator Linkage (MVP)
- If operator assignment is required in MVP, add explicit endpoints for
  `POST /api/v1/account_profile_operators` and `DELETE /api/v1/account_profile_operators/{id}`.
- Otherwise, defer operator linkage endpoints to post‑MVP (documented as admin‑assigned flow only).
