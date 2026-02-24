# TODO (V1): URL-Only Route Hydration Hardening (Tenant + Tenant Admin)
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Active
**Owners:** Flutter Team
**Objective:** Remove remaining route correctness dependencies on in-memory constructor objects/labels so direct-open and refresh work from URL-only state, while preserving the current host/domain-based scope model.

## Canonical Baseline (Already Decided)
- Tenant context is resolved by host/domain (not by `/t/:tenantSlug`).
- Main scopes remain:
  - landlord host: `/` -> `site_public`, `/admin` -> `landlord_area`
  - tenant host: `/` -> `tenant_public`, `/admin` -> `tenant_admin`
- Tenant subscope routes remain:
  - `/workspace` -> account workspace root mode
  - `/workspace/{account_slug}` -> account workspace scoped mode

## Scope
- Perform a full-system route contract audit from generated router (`app_router.gr.dart`) to cover every route, not only tenant-admin.
- Convert non-URL required route args to optional fast-path args (for in-app UX) with URL-first correctness.
- Ensure every affected screen can resolve required data from URL ids/slugs/query on first load.
- Add explicit loading/error/not-found states for deep-link entry when preloaded objects are absent.
- Add/adjust tests for direct-open and refresh behavior for affected routes.

## Out of Scope
- Any migration to `/t/:tenantSlug` path namespaces.
- New subscope creation or scope boundary changes.
- Cross-domain login handoff implementation.
- Backend contract redesign (unless a minimal additive read endpoint is strictly required and approved).

## Current Findings (Route Constructors Still Requiring Non-URL Objects/Labels)
### Shared/system routes
- `LocationPermissionRoute`: requires `LocationPermissionState initialState`.
- `LocationNotLiveRoute`: requires `LocationPermissionState blockerState`.

### Tenant routes
- `InviteShareRoute`: requires `InviteModel invite`.
- `PoiDetailsRoute`: requires `CityPoiModel poi`.

### Tenant admin routes
- `TenantAdminProfileTypeDetailRoute`: requires `TenantAdminProfileTypeDefinition definition`.
- `TenantAdminProfileTypeEditRoute`: requires `TenantAdminProfileTypeDefinition definition`.
- `TenantAdminStaticProfileTypeDetailRoute`: requires `TenantAdminStaticProfileTypeDefinition definition`.
- `TenantAdminStaticProfileTypeEditRoute`: requires `TenantAdminStaticProfileTypeDefinition definition`.
- `TenantAdminTaxonomyEditRoute`: requires `TenantAdminTaxonomyDefinition taxonomy`.
- `TenantAdminTaxonomyTermsRoute`: requires `taxonomyName`.
- `TenantAdminTaxonomyTermCreateRoute`: requires `taxonomyName`.
- `TenantAdminTaxonomyTermEditRoute`: requires `taxonomyName` and `TenantAdminTaxonomyTermDefinition term`.
- `TenantAdminTaxonomyTermDetailRoute`: requires `taxonomyName` and `TenantAdminTaxonomyTermDefinition term`.

## Coverage Contract (System-Wide)
- Every route in generated router must be classified in one of two classes:
  - `URL-Hydratable`: required constructor data is path/query resolvable (or optional fast-path arg only).
  - `Internal-Only`: route is intentionally not deep-link-safe and must document guard/fallback behavior when opened without args.
- No unclassified route with required non-URL args is allowed.
- Any new route introducing required non-URL args must update this TODO matrix (or successor policy) before merge.

## Plan
- [ ] ⚪ Pending Add an explicit route classification matrix (all non-URL required args from `app_router.gr.dart`) with owner + target disposition (`URL-Hydratable` or `Internal-Only`).
- [ ] ⚪ Pending Document URL-first contract per affected route (required path/query ids + optional fast-path args).
- [ ] ⚪ Pending Refactor route args and resolvers so non-URL objects/labels are optional.
- [ ] ⚪ Pending Implement controller/repository hydration for each affected route using URL identifiers.
- [ ] ⚪ Pending Add deterministic loading/error/not-found states for deep-link and refresh entry.
- [ ] ⚪ Pending Keep in-app navigation passing optional objects for first-frame UX only.
- [ ] ⚪ Pending For `Internal-Only` routes, enforce deterministic fallback (redirect/back) when required args are absent.
- [ ] ⚪ Pending Add/adjust route tests for URL-only sufficiency.
- [ ] ⚪ Pending Add/adjust widget/integration tests for direct-open + refresh on affected routes.
- [ ] ⚪ Pending Re-run web navigation validation to guarantee no regression on canonical scope roots (`/`, `/admin`, `/workspace`, `/workspace/{account_slug}`).

## Definition of Done
- [ ] ⚪ Pending Affected routes render correctly from URL-only state after direct-open and refresh.
- [ ] ⚪ Pending No affected route requires non-URL constructor args for correctness.
- [ ] ⚪ Pending Optional object passing remains only a performance/UX optimization.
- [ ] ⚪ Pending Any intentional `Internal-Only` exception has explicit guard/fallback and test coverage.
- [ ] ⚪ Pending Existing host/domain scope routing behavior remains unchanged.
- [ ] ⚪ Pending Automated tests cover the hardened route matrix.

## Validation Steps
- [ ] ⚪ Pending `fvm flutter analyze`
- [ ] ⚪ Pending `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [ ] ⚪ Pending Add/run a generated-router contract check that fails on unclassified required non-URL args.
- [ ] ⚪ Pending Add/run focused tests for `InviteShareRoute` and `PoiDetailsRoute` URL-only entry.
- [ ] ⚪ Pending Add/run focused tests for taxonomy/profile-type route deep-link refresh without preloaded objects.
- [ ] ⚪ Pending Add/run focused tests for shared location routes (`/location/permission`, `/location/not-live`) absent-args behavior.
- [ ] ⚪ Pending Run web navigation tests from Flutter source of truth (`tools/flutter/web_app_tests/navigation.spec.js`) after web build/publish cycle.

## References
- Superseded strategy archive:
  - `foundation_documentation/todos/completed/TODO-v1-canonical-multi-tenant-routing.md`
- Canonical scope policy:
  - `foundation_documentation/policies/scope_subscope_governance.md`
- Canonical scope implementation baseline:
  - `foundation_documentation/todos/completed/TODO-v1-environment-scope-reorganization.md`
