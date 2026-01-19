# Documentation: Submodule Summary - laravel-app
**Version:** 1.0

## 1. Analyzed Version

* **Submodule Name:** `laravel-app`
* **Commit Hash:** `a34e9d5a8ad81e4ef37a93f01f1fe26620678848`
* **Analysis Date:** `2026-01-19`

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
* **Primary Endpoints/Modules:** Tenant auth, anonymous identity, environment/branding, accounts/users/roles, **organizations**, **account profiles**, **account profile types**, **agenda + events (list/detail/stream + admin CRUD)**, push device registration, landlord admin routes.
* **Authentication Method:** Laravel Sanctum tokens with abilities; wildcard abilities are sanitized/expanded in auth services.

---

## 7. Notes & Observations

* Tenant resolution uses `DomainTenantFinder` with MongoDB database switching (`SwitchMongoTenantDatabaseTask`).
* Anonymous identity flow is implemented with fingerprint-based idempotency and tenant-driven ability/TTL policy.
* Project-specific API route files are optional and additive, enabling downstream extensions without removing boilerplate routes.
* **Account Profile domain:** new `account_profiles` collection with 1:1 profile per account, registry-driven `profile_type` validation, and POI-aware location enforcement.
* **Organization grouping:** new `organizations` collection with tenant‑scoped CRUD and optional linking to accounts.
* **Geo query:** `GET /api/v1/account_profiles/geo` uses `$geoNear` with optional origin/distance and profile type filters.
* **Agenda + Events:** new `events` collection with agenda feed (`/api/v1/agenda`), detail (`/api/v1/events/{event_id}`), SSE stream (`/api/v1/events/stream`), and tenant CRUD (`/api/v1/events`). Event publication is managed via `publication.status` + `publication.publish_at` with an hourly job to promote scheduled events. Event payloads use native BSON arrays (no model array casts), derive geo from venue profile location (no standalone event location), and project venue/artist summaries from Account Profiles.
* **Account Profile BSON:** `AccountProfile` no longer casts `location` or `taxonomy_terms` to arrays, preserving MongoDB BSON for geo indexes and taxonomy payloads.
* **Bootstrap on register:** password registration now ensures a personal account + profile via `AccountProfileBootstrapService`.
* Tenant push credentials are now single-credential only (upsert via `PUT /api/v1/settings/push/credentials`); multiple credentials return 409 until cleaned up.
* Tenant push settings no longer accept or return `firebase_credentials_id`; configuration relies on a single stored credential plus `firebase` public config.
