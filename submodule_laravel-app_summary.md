# Documentation: Submodule Summary - laravel-app
**Version:** 1.0

## 1. Analyzed Version

* **Submodule Name:** `laravel-app`
* **Commit Hash:** `c48025640dce3b6edb5376de88d523e25f3ef2b5`
* **Analysis Date:** `2026-03-01`

*Purpose: This document summarizes the key architectural aspects of the specified submodule version relevant to the main ecosystem.*

---

## 2. Core Dependencies & Technologies

* `laravel/framework` (^12.0): API framework.
* `mongodb/laravel-mongodb` (^5.4): MongoDB ODM/driver integration.
* `spatie/laravel-multitenancy` (^4.0): Tenant resolution and switching.
* `laravel/sanctum` (^4.0): Stateless token authentication.
* `spatie/laravel-data` (^4.17): DTO and serialization tooling.
* `intervention/image` (^3.11): Image processing for assets.

---

## 3. Structural Patterns

* **Overall Structure:** MVC with app services and domain helpers; API controllers under `app/Http/Api/v1/Controllers`.
* **Key Patterns:** Multi-tenant middleware groups (`landlord`, `tenant`, `tenant-maybe`, `account`), service layer in `app/Application`, domain utilities in `app/Domain`.
* **Routing:** API routes are registered in `bootstrap/app.php` with explicit prefixes for `initialize`, `tenant`, `tenant-maybe`, `landlord`, and `accounts/{account_slug}`.
* **Project Extension:** Optional project route files (`routes/api/project_*.php`) are additive; boilerplate routes remain exposed.

---

## 4. Ecosystem Configuration Points

* **Configuration Method:** `.env` + config files under `config/`.
* **Key Variables/Files:**
    * `config/database.php`: MongoDB connections (`mongodb`, `landlord`, `tenant`).
    * `DB_URI`, `DB_URI_LANDLORD`, `DB_URI_TENANTS`: MongoDB connection strings.
    * `config/multitenancy.php`: `DomainTenantFinder`, `SwitchMongoTenantDatabaseTask`, tenant/landlord connections.
    * `config/sanctum.php`: Sanctum token settings.
    * `bootstrap/app.php`: API route prefixes, middleware groups, and project route registration.

---

## 5. Architectural Principle Alignment

* **P-3 (API-Centric Ecosystem):** Aligned. Headless API with explicit route groups.
* **P-8 (Explicit Schemas):** Partially Aligned. Request validation exists; endpoint schemas still require consolidation in foundation docs.
* **P-11 (Stateless Authentication):** Aligned. Sanctum token-based auth with abilities.
* **P-12 (Resource-Oriented Naming):** Partially Aligned. Resource naming present; some routes remain workflow-specific.
* **P-18 (Ingress Configuration Parity):** Partially Aligned. Route prefixes are explicit; ingress parity depends on documentation discipline.

---

## 6. Key Integration Points / API Surface (If Applicable)

* **API Prefix/Base:** `/api/v1`, `/admin/api/v1`, `/api/v1/initialize`, `/api/v1/accounts/{account_slug}`.
* **Primary Endpoints/Modules:** Tenant auth, anonymous identity, environment/branding, accounts/users/roles, **organizations**, **account profiles**, **account profile types**, **agenda + events (list/detail/stream + admin CRUD)**, **map POIs + filters + near**, **static assets (tenant-admin CRUD)**, **settings kernel (schema/values/namespace patch in tenant + landlord + landlord-on-behalf tenant scopes)**, push device registration, landlord admin routes.
* **Media ingestion (tenant-admin):** `POST /admin/api/v1/media/external-image` (authenticated + `CheckTenantAccess`) proxies external image URLs into raw bytes with SSRF + size limits to support Flutter Web URL import without CORS/hotlink failures.
* **Authentication Method:** Laravel Sanctum tokens with abilities; wildcard abilities are sanitized/expanded in auth services.

### 6.1 Scope/Subscope Ownership Contract for Client-Facing Routes
Canonical governance source:
- `foundation_documentation/policies/scope_subscope_governance.md`
- This policy is mandatory reading before changing route/module contracts consumed by Flutter/Web clients.

Contract expectations exposed to Flutter/Web clients:
- `EnvironmentType` remains binary: `landlord` or `tenant`.
- Main scopes consumed by clients:
  - landlord host: `site_public` (`/`), `landlord_area` (`/admin`)
  - tenant host: `tenant_public` (`/`), `tenant_admin` (`/admin`)
- Tenant subscope consumed by clients:
  - `account_workspace`: `/workspace`, `/workspace/{account_slug}`
- Tenant resolution for `tenant_admin` is host/domain based (no tenant path parameter in canonical V1 entry).
- Landlord -> tenant admin transition is URL redirect-link based in V1; cross-domain SSO is optional and not required.
- No new subscope may be introduced in route/module contracts without explicit governance decision and policy update.

---

## 7. Notes & Observations

* Tenant resolution uses `DomainTenantFinder` with MongoDB database switching (`SwitchMongoTenantDatabaseTask`).
* Anonymous identity flow is implemented with fingerprint-based idempotency and tenant-driven ability/TTL policy.
* Project-specific API route files are optional and additive, enabling downstream extensions without removing boilerplate routes.
* **Account Profile domain:** new `account_profiles` collection with 1:1 profile per account, registry-driven `profile_type` validation, and POI-aware location enforcement.
* **Profile type registry (V1):** default registry is flat (no `parent_type`), with `personal`, `artist`, `venue`, `restaurant`, and `experience_provider`; `personal` is not favoritable by default.
* **Organization grouping:** new `organizations` collection with tenant‑scoped CRUD and optional linking to accounts.
* **Geo query:** `/admin/api/v1/account_profiles/geo` removed from tenant admin routes; superseded by `/api/v1/map/pois`.
* **Agenda + Events:** new `events` collection with agenda feed (`/api/v1/agenda`), detail (`/api/v1/events/{event_id}`), SSE stream (`/api/v1/events/stream`), and tenant CRUD (`/api/v1/events`). Event publication is managed via `publication.status` + `publication.publish_at` with an hourly job to promote scheduled events. Event payloads use native BSON arrays (no model array casts), canonical `location + place_ref`, and project venue/artist summaries from Account Profiles when references are resolvable.
* **Map POIs package ownership:** map runtime is package-owned under `packages/belluga/belluga_map_pois` (routes, model, projection/query services, jobs, listeners, migration). Projection Jobs sync POIs for Events, Account Profiles, and Static Assets; internal rebuild command available via `php artisan map-pois:rebuild` (source-scoped, no public rebuild API). `/api/v1/map/pois` returns marker stacks, `/api/v1/map/near` returns rich cards, `/api/v1/map/filters` returns catalog filters; `/api/v1/map/pois/stream` remains deferred (no route registered).
* **Settings kernel package:** `belluga_settings` now owns shared settings persistence lifecycle (tenant + landlord migrations for `settings`), generic schema/value endpoints (`GET /settings/schema`, `GET /settings/values`, `PATCH /settings/values/{namespace}`), and namespace registry contracts used by core + push modules.
* **PATCH contract convergence:** canonical settings PATCH semantics are enforced (direct object payload, field-presence merge, nullable-only clear with `null`, deterministic `422` for non-nullable `null`) with audit tracked in `foundation_documentation/artifacts/settings-patch-convergence-audit-v1.md`.
* **Static Assets:** tenant-admin CRUD under `/admin/api/v1/static_assets`, stored in `static_assets` collection and projected into `map_pois` as `ref_type=static`.
* **Static profile types:** new `static_profile_types` registry parallels account profile types, governing page/POI capabilities for static assets.
* **Static asset pages:** public read endpoint returns static asset page payloads by id or slug; static assets reuse the shared profile page schema (display name, media, content, taxonomy).
* **Account Profile BSON:** `AccountProfile` no longer casts `location` or `taxonomy_terms` to arrays, preserving MongoDB BSON for geo indexes and taxonomy payloads.
* **Bootstrap on register:** password registration now ensures a personal account + profile via `AccountProfileBootstrapService`.
* Tenant push credentials are now single-credential only (upsert via `PUT /api/v1/settings/push/credentials`); multiple credentials return 409 until cleaned up.
* Tenant push settings no longer accept or return `firebase_credentials_id`; configuration relies on a single stored credential plus `firebase` public config.
