# Documentation: Submodule Summary - flutter-app
**Version:** 1.0

## 1. Analyzed Version

* **Submodule Name:** `flutter-app`
* **Commit Hash:** `f3fd0391fdc8c89261164f44ed30989481377f3a`
* **Analysis Date:** `2026-03-01`

*Purpose: This document summarizes the key architectural aspects of the specified submodule version relevant to the main ecosystem.*

---

## 2. Core Dependencies & Technologies

* `Flutter (via FVM)`: Primary UI toolkit; commands run through `fvm flutter …`.
* `auto_route`: Declarative, code-generated navigation and guard management.
* `get_it`: DI container used for controller/repository/service wiring.
* `get_it_modular_with_auto_route`: Submodule-style route aggregation + ModuleScope pattern.
* `dio`: HTTP client used by Laravel-backed adapters (e.g., environment/app branding fetch).
* `sentry_flutter`: Error + performance reporting (bootstrapped in `lib/main.dart`).
* `flutter_map` / `latlong2`: Map rendering and geo primitives for the tenant map experience.
* `stream_value`: Reactive state holder for controller-owned state → widgets.
* `value_object_pattern`: Value-object layer used for UI/domain primitives (IDs, icons, URIs, etc.).

---

## 3. Structural Patterns

* **Overall Structure:** Layered boundaries (`application`, `domain`, `infrastructure`, `presentation`) with feature-first presentation modules (`tenant/*`, `landlord/*`, `prototypes/*`).
* **Key Patterns:**
    * Controller-owned state via `StreamValue`; widgets act as (mostly) pure renderers.
    * Repository contracts live in `lib/domain/repositories/*`; implementations in `lib/infrastructure/repositories/*`.
    * DAL split between DTOs (`lib/infrastructure/dal/dto/*`) and backend adapters (`lib/infrastructure/dal/dao/*`), with mocks as the default data source.
    * Navigation is defined via AutoRoute and assembled through modular route providers (`lib/application/router/modular_app/*`).

### 3.1 Canonical Presentation Ownership Model (Governance)
Canonical governance source:
- `foundation_documentation/policies/scope_subscope_governance.md`
- This policy is mandatory reading before any Flutter route/module/screen ownership change.

Required ownership model for route/screen surfaces:
- `site_public` and `landlord_area`: landlord-owned presentation surfaces.
- `tenant_public`: tenant-owned public/home surfaces.
- `tenant_admin`: tenant admin surfaces on tenant domains.
- `account_workspace`: tenant subscope (`/workspace`, `/workspace/{account_slug}`) for account-user/admin workspace concerns.
- Shared UI utilities/components are allowed only in explicit shared paths and cannot silently redefine scope ownership.

Governance constraints:
- New route/screen work must be attributed to one canonical scope/subscope from the policy.
- Do not create new subscope folders/keys without explicit decision + policy update.
- Legacy placement guidance (for example historical `presentation/prototypes` usage) is non-authoritative for new route/screen ownership.

---

## 4. Ecosystem Configuration Points

* **Configuration Method:** DI wiring through GetIt + module registration (`ModuleSettings`); backend adapters default to mock implementations with targeted Laravel-backed adapters for environment/bootstrap.
* **Key Variables/Files:**
    * `.fvmrc` / `.fvm/fvm_config.json`: Flutter SDK pinning.
    * `pubspec.yaml`: Declares third-party packages and Flutter SDK constraints.
    * `analysis_options.yaml`: Lints/analysis rules expected to stay clean.
    * `lib/application/router/modular_app/module_settings.dart`: Global registrations, backend selection, and submodule route initialization.
    * `lib/application/configurations/belluga_constants.dart`: Derives API base/admin URLs from `AppData` (bootstrapped at startup).
    * `foundation_documentation/` (symlink): Shared domain/roadmap source of truth.

---

## 5. Architectural Principle Alignment

* **P-1 (Domain-First, Schema-Second):** Partially Aligned — Domain entities and value objects exist, but partner subtype vocabulary (e.g., influencer/curator) and engagement metrics still need stricter documentation traceability in foundation docs.
* **P-3 (API-Centric Ecosystem):** Partially Aligned — The app bootstraps against a Laravel-backed environment endpoint, while most feature data remains mock-backed to unblock UI/flow development ahead of finalized API delivery.
* **P-8 (Explicit Schemas):** Partially Aligned — Significant DTO coverage exists (app_data, schedule, invites, profile, map), but mock databases (notably account_profiles/discovery) still encode schema expectations implicitly.
* **Appendix A (Flutter Tenets):** Partially Aligned — Feature-first organization and controller/stream patterns are present; continued tightening is needed to keep DTOs out of widgets and keep screen logic consistently controller-driven.

---

## 6. Key Integration Points / API Surface (If Applicable)

* **API Prefix/Base:** `https://{AppData.hostname}/api` (derived at runtime from the environment bootstrap payload).
* **Primary Endpoints/Modules (Current Consumer Shape):** App environment/bootstrap (branding + theme), schedule/events, invites, favorites, account_profiles/discovery, profile, map POIs (mixed: bootstrap via Laravel adapter; others primarily mock-backed).
* **Authentication Method:** Presentational auth flows exist, but repository/backends are currently mock-focused; secure storage is available for future token/session persistence.

---

## 7. Notes & Observations

* Environment/bootstrap is implemented via a Laravel-backed adapter (`GET /api/v1/environment` + `X-App-Domain` header for mobile resolution) and must remain aligned with the backend payload keys used for branding (e.g., `main_logo_light_url`, `main_icon_dark_url`, `theme_data_settings`).
* Account profiles/discovery continues to rely on local mock databases that embed subtype and engagement expectations; these should be treated as prototype contracts and mirrored in foundation documentation to prevent Flutter ↔ Laravel drift.
* `ModuleSettings` supports test-time backend builders, enabling targeted tests to swap mock/real adapters without changing production wiring.
* Architecture adherence refactor in progress: move repository/infrastructure usage out of screens/widgets into controllers, remove DTO types from UI, and ensure presentation logic remains controller-owned (no API contract changes intended).
* DTO factories removed from Flutter domain models; mapping is centralized in infrastructure DTO mappers (invites, schedule, user, thumb, partner).
* Hard‑NO cleanup queued (FCX‑06): remove GetIt usage from widgets/screens, eliminate Future/StreamBuilder usage in presentation, replace direct Navigator calls with router/controller-driven navigation, split multi‑widget files, and purge DTO dependencies from the domain layer.
* Profile module dependency wiring will be tightened to ensure `LandlordLoginController` is always registered when Profile routes are loaded (prevents GetIt resolution failures in device tests).
* Architecture cleanup will introduce domain-level contracts for `AppDataRepository`, `PoiRepository`, `PushPresentationGate`, and `TelemetryQueue`, plus domain-owned `PoiQuery` to remove infrastructure/DAL dependencies from controllers.
* Partner detail mocks will be lifted into domain projections/services so controllers consume only domain types (no DTOs or DAL classes in presentation).
* Partner audio playback now routes through a domain-level `AudioPlayerServiceContract`, with the mock implementation registered in `DiscoveryModule` and controllers consuming the contract (no infrastructure dependencies in presentation).
* Integration tests updated to use domain `PoiQuery` and `PartnerProfileConfigBuilder`; device checklist shows all integration tests green after these updates.
* Tenant-admin account sync pattern established: account list/detail/form flows consume repository-owned canonical streams; detail controllers derive account state via repository watch (stable `id` first, slug fallback only while unresolved), avoiding manual cross-controller synchronization.
* Tenant-admin image ingestion: device file import and web URL import share the same crop UX (avatar 1:1, cover 16:9) and run through the canonical crop/normalize/upload pipeline. Flutter Web URL import relies on an authenticated backend proxy (`/admin/api/v1/media/external-image`) to avoid CORS/hotlink failures.
