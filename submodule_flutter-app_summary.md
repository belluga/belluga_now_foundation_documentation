# Documentation: Submodule Summary - flutter-app
**Version:** 1.0

## 1. Analyzed Version

* **Submodule Name:** `flutter-app`
* **Commit Hash:** `9cfc7fb266b786ebe1de7a50439d12c3dbd100cf`
* **Analysis Date:** `2026-03-07`

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
    * DAL split between DTOs (`lib/infrastructure/dal/dto/*`) and backend adapters (`lib/infrastructure/dal/dao/*`), with Laravel adapters as the runtime source.
    * Navigation is defined via AutoRoute and assembled through modular route providers (`lib/application/router/modular_app/*`).

### 3.1 Canonical Presentation Ownership Model (Governance)
Canonical governance source:
- `foundation_documentation/project_constitution.md`
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

* **Configuration Method:** DI wiring through GetIt + module registration (`ModuleSettings`); runtime backend adapters are Laravel-only, while test overrides remain explicit/injected.
* **Key Variables/Files:**
    * `.fvmrc` / `.fvm/fvm_config.json`: Flutter SDK pinning.
    * `pubspec.yaml`: Declares third-party packages and Flutter SDK constraints.
    * `analysis_options.yaml`: Lints/analysis rules expected to stay clean.
    * `lib/application/router/modular_app/module_settings.dart`: Global registrations, backend selection, and submodule route initialization.
    * `lib/application/configurations/belluga_constants.dart`: Derives API base/admin URLs from `AppData` (bootstrapped at startup).
    * `foundation_documentation/project_constitution.md`: Project-level cross-stack authority and repo-boundary rules.
    * `foundation_documentation/` (symlink): Shared domain/roadmap source of truth.

---

## 5. Architectural Principle Alignment

* **P-1 (Domain-First, Schema-Second):** Partially Aligned — Domain entities and value objects exist, but legacy profile-subtype vocabulary in the Flutter layer (for example older influencer/curator naming) and engagement metrics still need stricter documentation traceability in foundation docs.
* **P-3 (API-Centric Ecosystem):** Aligned — Runtime flows are Laravel-backed and bootstrapped through `/api/v1/environment`, with no runtime mock fallback.
* **P-8 (Explicit Schemas):** Partially Aligned — Significant DTO coverage exists (app_data, schedule, invites, profile, map); remaining gaps are contract documentation depth and adapter completeness, not runtime mock databases.
* **Appendix A (Flutter Tenets):** Partially Aligned — Feature-first organization and controller/stream patterns are present; continued tightening is needed to keep DTOs out of widgets and keep screen logic consistently controller-driven.

---

## 6. Key Integration Points / API Surface (If Applicable)

* **API Prefix/Base:** `AppData.mainDomainValue.value.resolve('/api')` (canonical tenant-scoped origin after bootstrap).
* **Primary Endpoints/Modules (Current Consumer Shape):** App environment/bootstrap (branding + theme), schedule/events, invites, favorites, account_profiles/discovery, profile, map POIs (Laravel-backed runtime adapters).
* **Authentication Method:** Presentational auth flows are backed by Laravel runtime adapters; secure storage supports token/session persistence.

---

## 7. Notes & Observations

* Environment/bootstrap is implemented via a Laravel-backed adapter (`GET /api/v1/environment` + `X-App-Domain` header for mobile resolution) and must remain aligned with the backend payload keys used for branding (e.g., `main_logo_light_url`, `main_icon_dark_url`, `theme_data_settings`).
* Account profiles/discovery runtime now follows account/account-profile contracts and must consume Laravel-backed repositories only; no runtime mock dataset fallback is allowed.
* `ModuleSettings` supports test-time backend builders, enabling targeted tests to swap mock/real adapters without changing production wiring.
* Architecture adherence refactor in progress: move repository/infrastructure usage out of screens/widgets into controllers, remove DTO types from UI, and ensure presentation logic remains controller-owned (no API contract changes intended).
* DTO factories removed from Flutter domain models; mapping is centralized in infrastructure DTO mappers (invites, schedule, user, thumb, and legacy pre-normalization account-profile detail surfaces).
* Custom lint coverage now also enforces domain `fromJson/fromMap` prohibition, domain primitive-field warnings, repository/service JSON parsing boundaries, repository inline DTO->Domain mapper prohibition, repository raw transport typing prohibition (`dynamic`/`Map<String, dynamic>` in repositories), and repository raw payload map boundary prohibition (`Map<String, Object?>` in repositories; currently branch-delta lane while root config remains disabled), plus `multi_public_class_file_warning` under `lib/**`.
* Hard‑NO cleanup queued (FCX‑06): remove non-controller/cross-feature GetIt resolution from widgets/screens, eliminate Future/StreamBuilder usage in presentation, replace direct Navigator calls with router/controller-driven navigation, split multi‑widget files, and purge DTO dependencies from the domain layer.
* Profile module dependency wiring will be tightened to ensure `LandlordLoginController` is always registered when Profile routes are loaded (prevents GetIt resolution failures in device tests).
* Architecture cleanup will introduce domain-level contracts for `AppDataRepository`, `PoiRepository`, `PushPresentationGate`, and `TelemetryQueue`, plus domain-owned `PoiQuery` to remove infrastructure/DAL dependencies from controllers.
* Legacy mock content providers and legacy profile-detail audio playback are removed from MVP runtime scope; discovery/profile details remain account/account-profile driven.
* Integration tests updated to use domain `PoiQuery` and the current legacy code symbol `PartnerProfileConfigBuilder`; device checklist shows all integration tests green after these updates.
* `packages/belluga_form_validation/` is now the canonical internal Flutter package for reusable form-validation behavior: transport-agnostic `422` failure modeling, `field|group|global` target resolution, theme-dependent default widgets, and anchor/scroll helpers.
* Tenant-admin account sync pattern established: account list/detail/form flows consume repository-owned canonical streams; detail controllers derive account state via repository watch (stable `id` first, slug fallback only while unresolved), avoiding manual cross-controller synchronization.
* Tenant-admin account creation now uses a dedicated `TenantAdminAccountCreateController`, while `TenantAdminAccountsController` is list-only; both are registered as route-local factories so list/create no longer share controller state.
* Tenant-admin manual create now uses a unified onboarding request (`POST /admin/api/v1/account_onboardings`) and consumes a composite onboarding result object (`account + account_profile`) in one success flow.
* Tenant-admin standalone profile create route is no longer a remediation path; account-detail missing-profile state is rendered as invariant-broken data requiring backend repair/audit.
* Tenant-admin image ingestion: device file import and web URL import share the same crop UX (avatar 1:1, cover 16:9) and run through the canonical crop/normalize/upload pipeline. Flutter Web URL import relies on an authenticated backend proxy (`/admin/api/v1/media/external-image`) to avoid CORS/hotlink failures.
* Tenant-admin web origin rule tightened: canonical tenant host must come from the selected tenant/main domain, while local browser-facing web validation must use the actual public/browser origin (`belluga.space` / tenant subdomain) rather than leaking internal ingress ports like `:8043` into tenant-admin URLs.
* Landlord/admin access is now strictly landlord-context owned: tenant scope cannot reach landlord routes, `Entrar como Admin` is landlord-only, and `TenantAdminShellRoute` stays tenant-gated until an accessible tenant is resolved/selected.
* Home agenda regression bundle delivered: nullable artist/friend avatars no longer collapse the visible agenda feed, eager auto-pagination was removed from the visible Home path, and tenant-admin archived filter serialization was corrected.
* Tenant-admin settings parsing now treats empty `map_ui` namespace payloads as an empty namespace instead of leaking sibling settings into `PATCH /admin/api/v1/settings/values/map_ui`; the persistence path is covered by repository + integration tests.
* Invite flow now uses preview-first share-code bootstrap on `/invite?code=...` (same `InviteFlowRoute` surface): unauthenticated entry resolves `previewShareCode`, while authenticated entry must first `materializeShareCode` into a canonical invite edge and only then render the standard invite decision UI/feed.
* Invite entry routes now use explicit split guards: `/invite` is `TenantRouteGuard`-only (preview-first), while `/convites` and `/convites/compartilhar` remain `TenantRouteGuard` + `AuthRouteGuard`; auth redirect normalization preserves the original deep-link query (`code`) for login/signup round-trip.
* Platform deep-link readiness is versioned for guarappari: Android App Links + iOS Universal Links, with backend-hosted `/.well-known/*` payloads resolved from typed app domains (`app_android`/`app_ios`) + tenant `settings.app_links` credentials; regression tests validate manifest/entitlements, nginx precedence, and absence of static file shadowing.
