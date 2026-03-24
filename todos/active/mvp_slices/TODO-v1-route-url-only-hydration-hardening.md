# TODO (V1): URL-Only Route Hydration Hardening (Tenant + Tenant Admin)
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Active
**Owners:** Flutter Team
**Objective:** Eliminate route correctness dependencies on in-memory constructor objects/labels for routes that must be deep-link-safe, and enforce deterministic fallback behavior for intentional internal-only routes, while preserving the current host/domain-based scope model.

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
- Ensure every route classified as `URL-Hydratable (Current Contract)` can resolve required data from URL ids/slugs/query on first load.
- Add explicit loading/error/not-found states for deep-link entry when preloaded objects are absent in `URL-Hydratable` routes.
- Add/adjust tests for direct-open and refresh behavior according to each route class (`URL-Hydratable` vs `Internal-Only` fallback).

## Out of Scope
- Any migration to `/t/:tenantSlug` path namespaces.
- New subscope creation or scope boundary changes.
- Cross-domain login handoff implementation.
- Backend contract redesign (unless a minimal additive read endpoint is strictly required and approved).
- Any route contract evolution not explicitly listed in `Decision Baseline (Frozen)` as `Contract-Change Required`.

## Current Findings (Route Constructors Still Requiring Non-URL Objects/Labels) — Audit Snapshot 2026-03-24 (Revalidated)
### Shared/system routes
- `LocationPermissionRoute`: requires `LocationPermissionState initialState`.
- `LocationNotLiveRoute`: requires `LocationPermissionState blockerState`.

### Tenant routes
- `InviteShareRoute`: requires `InviteModel invite`.
- `PoiDetailsRoute`: requires `CityPoiModel poi`.

### Tenant admin routes
- `TenantAdminEventEditRoute`: requires `TenantAdminEvent event`.
- `TenantAdminEventTypeEditRoute`: requires `TenantAdminEventType type`.

### Confirmed Progress Already in Code
- Tenant-admin profile/static-profile/taxonomy/term routes are now URL-first and no longer require non-URL object/name constructor args.
- Tenant-admin path params are encoded and validated in `test/application/router/tenant_admin_route_path_params_test.dart` (accounts, profile types, static profile types, taxonomies, terms, workspace).
- Resolvers for profile/static-profile/taxonomy/term hydration are registered in `TenantAdminModule` and consumed via `ResolverRoute`.
- `TenantAdminStaticAssetDetailRoute` and `TenantAdminStaticAssetEditRoute` are already URL-hydratable from `assetId` path param via `orElse` route-args fallback.
- Revalidation against current `app_router.gr.dart` confirms the remaining required non-URL domain/state args are restricted to shared location routes + invite share + POI details + tenant-admin event edit routes.

## Coverage Contract (System-Wide)
- Every route in generated router with required non-URL args must be classified in one of these implementation buckets:
  - `URL-Hydratable (Current Contract)`: path/query already carries identifiers needed for hydration.
  - `Internal-Only`: route is intentionally not deep-link-safe and must document guard/fallback behavior when opened without args.
  - `Contract-Change Required`: current path/query lacks required identifiers; route contract must evolve before URL-only hydration.
- End-state route classes remain:
  - `URL-Hydratable`
  - `Internal-Only`
- No unclassified route with required non-URL args is allowed.
- Any new route introducing required non-URL args must update this TODO matrix (or successor policy) before merge.

### Decision Baseline (Frozen, 2026-03-20)
| Route | Required Non-URL Args (Current) | URL Identifiers Already in Path/Query | Implementation Bucket | End-State Class | Owner | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `LocationPermissionRoute` | `initialState` | none | `Internal-Only` | `Internal-Only` | Flutter Team | Guard-owned route; absent args must never crash (fallback deterministic). |
| `LocationNotLiveRoute` | `blockerState` (+ optional metadata) | none | `Internal-Only` | `Internal-Only` | Flutter Team | Guard-owned route; absent args must never crash (fallback deterministic). |
| `InviteShareRoute` | `invite` | none | `Internal-Only` | `Internal-Only` | Flutter Team | Canonical deep-link is `/invite?code=...`; `/convites/compartilhar` is internal-only with fallback. |
| `PoiDetailsRoute` | `poi` | none (`/mapa/poi`) | `Contract-Change Required` | `URL-Hydratable` | Flutter Team | Route must add URL identifier (`poiId` or canonical slug/ref) before resolver hydration. |
| `TenantAdminEventEditRoute` | `event` | none (`/events/edit`) | `Internal-Only` | `Internal-Only` | Flutter Team | Keep internal-only in V1; contract change optional future track. |
| `TenantAdminEventTypeEditRoute` | `type` | none (`/events/types/edit`) | `Internal-Only` | `Internal-Only` | Flutter Team | Keep internal-only in V1; contract change optional future track. |

## Coherence Scan (2026-03-24)
- `app_router.gr.dart` confirms the matrix is exhaustive for required non-URL args in current generated contracts.
- Tenant-admin currently registers profile/static-profile/taxonomy/term resolvers and routes consume them through `ResolverRoute`.
- Taxonomy feature currently has two list screens (`TenantAdminTaxonomyTermsScreen` and `TenantAdminTaxonomyTermsListScreen`); router points to `TenantAdminTaxonomyTermsScreen`. Route hardening and tests must use the routed screen as canonical.
- Path-param test (`test/application/router/tenant_admin_route_path_params_test.dart`) is aligned with URL-first profile/static-profile/taxonomy constructors.
- `PoiDetailsRoute` currently lacks URL identifier and map repository contract lacks single-POI fetch by id/slug, so URL-only hydration for POI is blocked until route contract evolution.
- Internal-only routes listed above still require route args and still need explicit absent-args fallback + focused test coverage.

## Plan
- [x] ✅ Production‑Ready Revalidated matrix completeness against current generated router (`app_router.gr.dart`) on 2026-03-20.
- [x] ✅ Production‑Ready Confirmed target disposition for every affected route and froze baseline matrix (2026-03-20).
- [x] ✅ Production‑Ready Ran coherence scan from routed pages/controllers/repositories/tests and documented blocking dependencies.
- [x] ✅ Production‑Ready Refactored profile/static-profile/taxonomy route constructors to URL-first signatures (no required non-URL objects/labels).
- [x] ✅ Production‑Ready Added resolver/controller hydration for profile-type and taxonomy routes (`profileType`, `taxonomyId`, `termId`).
- [ ] ⚪ Pending Add deterministic loading/error/not-found states for deep-link and refresh entry in URL-hydratable routes.
- [ ] ⚪ Pending Add deterministic fallback behavior for all `Internal-Only` routes when args are absent.
- [ ] ⚪ Pending Execute route contract evolution for `PoiDetailsRoute` (identifier in URL + repository/resolver support) before URL-hydratable hardening.
- [ ] ⚪ Pending Keep in-app navigation passing optional objects for first-frame UX only (never as correctness requirement).
- [ ] ⚪ Pending Add/adjust route tests for URL-only sufficiency.
- [ ] ⚪ Pending Add/adjust widget/integration tests for direct-open + refresh on affected routes.
- [ ] ⚪ Pending Re-run web navigation validation to guarantee no regression on canonical scope roots (`/`, `/admin`, `/workspace`, `/workspace/{account_slug}`).

## Definition of Done
- [ ] ⚪ Pending Routes classified as `URL-Hydratable` render correctly from URL-only state after direct-open and refresh.
- [ ] ⚪ Pending No affected route requires non-URL constructor args for correctness.
- [ ] ⚪ Pending Optional object passing remains only a performance/UX optimization.
- [ ] ⚪ Pending Any intentional `Internal-Only` exception has explicit guard/fallback and test coverage.
- [ ] ⚪ Pending Existing host/domain scope routing behavior remains unchanged.
- [ ] ⚪ Pending Automated tests cover the hardened route matrix.

## Validation Steps
- [ ] ⚪ Pending `fvm flutter analyze`
- [ ] ⚪ Pending `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [ ] ⚪ Pending Add/run a generated-router contract check that fails on unclassified required non-URL args.
- [ ] ⚪ Pending Update/add tests for profile-type/taxonomy routes validating deep-link refresh without preloaded objects.
- [x] ✅ Production‑Ready Updated path-param tests to URL-first constructor signatures for profile/taxonomy routes.
- [ ] ⚪ Pending Add/run focused tests for shared location routes (`/location/permission`, `/location/not-live`) absent-args behavior.
- [ ] ⚪ Pending Add/run focused tests for internal-only edit routes (`TenantAdminEventEditRoute`, `TenantAdminEventTypeEditRoute`) absent-args fallback behavior.
- [ ] ⚪ Pending Add/run focused tests for `InviteShareRoute` internal-only absent-args fallback behavior.
- [ ] ⚪ Pending Add/run focused tests for `PoiDetailsRoute` URL-hydration (after route contract evolution).
- [ ] ⚪ Pending Run web navigation tests from Flutter source of truth (`tools/flutter/web_app_tests/navigation.spec.js`) after web build/publish cycle.

## References
- Superseded strategy archive:
  - `foundation_documentation/todos/completed/TODO-v1-canonical-multi-tenant-routing.md`
- Canonical scope policy:
  - `foundation_documentation/policies/scope_subscope_governance.md`
- Canonical scope implementation baseline:
  - `foundation_documentation/todos/completed/TODO-v1-environment-scope-reorganization.md`

## Applicable Rules / Workflows
- `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`

## Approval Gate
- 2026-03-20: TODO revalidated against current generated router; matrix completeness confirmed.
- 2026-03-20: Route disposition baseline frozen (`URL-Hydratable (Current Contract)` vs `Internal-Only` vs `Contract-Change Required`) and coherence scan applied.
