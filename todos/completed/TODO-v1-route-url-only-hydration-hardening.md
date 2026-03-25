# TODO (V1): URL-Only Route Hydration Hardening (Tenant + Tenant Admin)
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Production-Ready (route URL-only hydration closed end-to-end)
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

## Current Findings (Route Constructors Still Requiring Non-URL Objects/Labels) — Audit Snapshot 2026-03-25 (Post-hardening + POI focus fix)
### Remaining required non-URL arg
- None in the hardened matrix scope (`LocationPermissionRoute`, `LocationNotLiveRoute`, `InviteShareRoute`, `TenantAdminEventEditRoute`, `TenantAdminEventTypeEditRoute`, `PoiDetailsRoute`).

### URL-hydratable POI routes now using query parameters
- `/mapa` (`CityMapRoute`) now supports `poi` and `stack` query params.
- `/mapa/poi` (`PoiDetailsRoute`) now supports `poi` and `stack` query params.
- `MapScreenController` resolves initial POI from URL query (`refType:refId`, fallback to `id`) and, when necessary, loads stack items via `stack`.
- Initial POI focus now waits for map readiness via observed `MapEvent` (event-driven) and no longer depends only on camera-size readiness.
- Startup orchestration now gives explicit URL POI intent higher priority: with `poi` query present, POI hydration/focus preparation runs before non-blocking startup refresh completion (`filters`, default list, location refresh), which continue in parallel.
- Deterministic not-found response for direct-open/refresh without resolvable POI query: `POI do link não foi encontrado.`.
- `poi`-only deep links are now globally resolvable by typed lookup (`/api/v1/map/pois/lookup`) when the target POI is outside the initial `/api/v1/map/pois` payload and no `stack` query is provided.

### Internal-only routes now hardened with deterministic absent-args fallback
- `/location/permission` (`LocationPermissionRoute`): `initialState` is optional; absent args default to deterministic denied state.
- `/location/not-live` (`LocationNotLiveRoute`): `blockerState` is optional; absent args default to deterministic denied state.
- `/convites/compartilhar` (`InviteShareRoute`): `invite` is optional; absent args render explicit internal-only fallback + CTA back to `/convites`.
- `/admin/events/edit` (`TenantAdminEventEditRoute`): `event` is optional; absent args render explicit internal-only fallback + CTA back to `/admin/events`.
- `/admin/events/types/edit` (`TenantAdminEventTypeEditRoute`): `type` is optional; absent args render explicit internal-only fallback + CTA back to `/admin/events/types`.

### Confirmed Progress Already in Code
- Tenant-admin profile/static-profile/taxonomy/term routes are URL-first and no longer require non-URL object/name constructor args.
- Tenant-admin path params are encoded and validated in `test/application/router/tenant_admin_route_path_params_test.dart` (accounts, profile types, static profile types, taxonomies, terms, workspace).
- Resolvers for profile/static-profile/taxonomy/term hydration are registered in `TenantAdminModule` and consumed via `ResolverRoute`.
- `TenantAdminStaticAssetDetailRoute` and `TenantAdminStaticAssetEditRoute` are URL-hydratable from `assetId` path param via `orElse` route-args fallback.
- `PoiDetailsRoute` and `CityMapRoute` are now query-parameter routes and no longer depend on required in-memory model constructor args.
- Internal-only fallback behavior for shared location routes + invite share + tenant-admin event edit routes is deterministic and covered by focused tests.
- Lint guard `route_required_non_url_args_forbidden` now enforces this pattern without POI-specific allowlist exceptions.

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

### Decision Baseline (Frozen, 2026-03-20; Updated 2026-03-24)
| Route | Required Non-URL Args (Current) | URL Identifiers Already in Path/Query | Implementation Bucket | End-State Class | Owner | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `LocationPermissionRoute` | optional `initialState` | none | `Internal-Only` | `Internal-Only` | Flutter Team | Guard-owned route; absent args must never crash (fallback deterministic). |
| `LocationNotLiveRoute` | optional `blockerState` (+ optional metadata) | none | `Internal-Only` | `Internal-Only` | Flutter Team | Guard-owned route; absent args must never crash (fallback deterministic). |
| `InviteShareRoute` | optional `invite` | none | `Internal-Only` | `Internal-Only` | Flutter Team | Canonical deep-link is `/invite?code=...`; `/convites/compartilhar` is internal-only with fallback. |
| `PoiDetailsRoute` | none | `poi` + optional `stack` query (`/mapa/poi?poi=...&stack=...`) | `URL-Hydratable (Current Contract)` | `URL-Hydratable` | Flutter Team | Query-based hydration uses existing repository flow (`filteredPois` + `loadStackItems`), no in-memory correctness dependency. |
| `TenantAdminEventEditRoute` | optional `event` | none (`/events/edit`) | `Internal-Only` | `Internal-Only` | Flutter Team | Keep internal-only in V1; contract change optional future track. |
| `TenantAdminEventTypeEditRoute` | optional `type` | none (`/events/types/edit`) | `Internal-Only` | `Internal-Only` | Flutter Team | Keep internal-only in V1; contract change optional future track. |

## Coherence Scan (2026-03-24)
- `app_router.gr.dart` now uses optional route args + `orElse` for the five hardened internal-only routes (`LocationPermissionRoute`, `LocationNotLiveRoute`, `InviteShareRoute`, `TenantAdminEventEditRoute`, `TenantAdminEventTypeEditRoute`).
- `app_router.gr.dart` now exposes query params (`poi`, `stack`) for both `CityMapRoute` and `PoiDetailsRoute`.
- `MapScreen`/`MapScreenController` now consume query params and resolve initial POI deterministically for direct-open/refresh.
- Taxonomy feature currently has two list screens (`TenantAdminTaxonomyTermsScreen` and `TenantAdminTaxonomyTermsListScreen`); router points to `TenantAdminTaxonomyTermsScreen`. Route hardening and tests use the routed screen as canonical.
- Path-param test (`test/application/router/tenant_admin_route_path_params_test.dart`) remains aligned with URL-first profile/static-profile/taxonomy constructors after this hardening.

## Contract-Change Dependency Closure (POI)
- `poi + stack` URL-hydration path remains supported in current Flutter contract.
- `poi`-only global hydration is now closed with backend typed lookup (`GET /api/v1/map/pois/lookup`) by (`ref_type`, `ref_id`), independent of viewport/origin payload preloading.

## Remaining Delivery Track (Backend Contract)
- [x] ✅ Production‑Ready Backend dependency explicitly documented in canonical module/roadmap contracts (`/api/v1/map/pois/lookup`).
- [x] ✅ Production‑Ready Laravel implementation delivered for deterministic single-POI typed lookup (`ref_type`, `ref_id`) independent of viewport/origin.
- [x] ✅ Production‑Ready Laravel feature tests validate lookup success and deterministic not-found behavior.
- [x] ✅ Production‑Ready Flutter fallback now uses backend lookup when `poi` query is typed and not present in current list/stack payload.

## Plan
- [x] ✅ Production‑Ready Revalidated matrix completeness against current generated router (`app_router.gr.dart`) on 2026-03-20.
- [x] ✅ Production‑Ready Confirmed target disposition for every affected route and froze baseline matrix (2026-03-20).
- [x] ✅ Production‑Ready Ran coherence scan from routed pages/controllers/repositories/tests and documented dependencies.
- [x] ✅ Production‑Ready Refactored profile/static-profile/taxonomy route constructors to URL-first signatures (no required non-URL objects/labels).
- [x] ✅ Production‑Ready Added resolver/controller hydration for profile-type and taxonomy routes (`profileType`, `taxonomyId`, `termId`).
- [x] ✅ Production‑Ready Added deterministic loading/error/not-found states for deep-link and refresh entry in URL-hydratable POI flows.
- [x] ✅ Production‑Ready Added deterministic fallback behavior for all `Internal-Only` routes when args are absent.
- [x] ✅ Production‑Ready Executed POI route contract evolution to query-parameter hydration (`poi` + optional `stack`) for URL-only behavior.
- [x] ✅ Production‑Ready Keep in-app navigation passing optional objects for first-frame UX only (never as correctness requirement).
- [x] ✅ Production‑Ready Added/adjusted route tests for URL-only sufficiency, including POI query-parameter routes.
- [x] ✅ Production‑Ready Added/adjusted focused tests for direct-open + refresh-sensitive behavior (router/controller/widget scope).
- [x] ✅ Production‑Ready Add lint guard to block new required non-URL route args outside approved exceptions.
- [x] ✅ Production‑Ready Closed backend dependency for deterministic `poi`-only lookup (without `stack`) and removed POI contract exception.
- [x] ✅ Production‑Ready Re-ran web navigation validation and confirmed no regression on canonical scope roots (`/`, `/admin`, `/workspace`, `/workspace/{account_slug}`).

## Definition of Done
- [x] ✅ Production‑Ready Routes classified as `URL-Hydratable` in this matrix render from URL-only state for direct-open/refresh-sensitive flows.
- [x] ✅ Production‑Ready No affected route in this matrix requires non-URL constructor args for correctness.
- [x] ✅ Production‑Ready Optional object passing remains only a performance/UX optimization.
- [x] ✅ Production‑Ready Any intentional `Internal-Only` exception has explicit guard/fallback and test coverage.
- [x] ✅ Production‑Ready Existing host/domain scope routing behavior remains unchanged.
- [x] ✅ Production‑Ready Automated tests cover the hardened route matrix for this Flutter slice.
- [x] ✅ Production‑Ready POI route matrix has no remaining contract dependency for `poi`-only deterministic deep-link lookup.

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/application/router/map_route_query_params_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/shared/location_permission/routes/location_permission_routes_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant/invites/routes/invite_share_route_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/events/routes/tenant_admin_internal_edit_routes_test.dart`
- [x] ✅ Production‑Ready Add/run a generated-router contract check for required non-URL args (`rg` audit on `app_router.gr.dart`).
- [x] ✅ Production‑Ready `NAV_LANDLORD_URL="https://belluga.space" NAV_TENANT_URL="https://guarappari.belluga.space" bash tools/flutter/run_web_navigation_smoke.sh readonly`
- [x] ✅ Production‑Ready `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php`
- [x] ✅ Production‑Ready `fvm dart run custom_lint` (consolidated final round; no `--no-watch`).

## Execution Evidence (2026-03-25)
- Internal-only fallback implementation:
  - `flutter-app/lib/presentation/shared/location_permission/routes/location_permission_route.dart:19`
  - `flutter-app/lib/presentation/shared/location_permission/routes/location_not_live_route.dart:23`
  - `flutter-app/lib/presentation/tenant_public/invites/routes/invite_share_route.dart:34`
  - `flutter-app/lib/presentation/tenant_admin/events/routes/tenant_admin_event_edit_route.dart:29`
  - `flutter-app/lib/presentation/tenant_admin/events/routes/tenant_admin_event_type_edit_route.dart:29`
- POI query-parameter route hardening:
  - `flutter-app/lib/presentation/tenant_public/map/routes/city_map_route.dart:11`
  - `flutter-app/lib/presentation/tenant_public/map/routes/poi_details_route.dart:11`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart:15`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:103`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:346`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:496`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:511`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:1157`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart:241`
- Generated router optional args + query params:
  - `flutter-app/lib/application/router/app_router.gr.dart:327`
  - `flutter-app/lib/application/router/app_router.gr.dart:575`
  - `flutter-app/lib/application/router/app_router.gr.dart:641`
  - `flutter-app/lib/application/router/app_router.gr.dart:719`
  - `flutter-app/lib/application/router/app_router.gr.dart:827`
  - `flutter-app/lib/application/router/app_router.gr.dart:1226`
  - `flutter-app/lib/application/router/app_router.gr.dart:1295`
- Focused route/controller tests:
  - `flutter-app/test/application/router/map_route_query_params_test.dart:6`
  - `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:833`
  - `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:863`
  - `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:927`
  - `flutter-app/test/presentation/shared/location_permission/routes/location_permission_routes_test.dart:43`
  - `flutter-app/test/presentation/tenant/invites/routes/invite_share_route_test.dart:20`
  - `flutter-app/test/presentation/tenant_admin/events/routes/tenant_admin_internal_edit_routes_test.dart:8`
  - `flutter-app/test/application/router/tenant_admin_route_path_params_test.dart:5`
- Runtime contract diagnosis evidence (POI):
  - Observed `/api/v1/map/pois` initial payload (origin-scoped) not containing `event:rsxupefaxo` for direct-open link `.../mapa/poi?poi=event:rsxupefaxo`, which motivated deterministic typed lookup.
  - Event-driven initial focus gate implemented to avoid missed focus when camera-size readiness signal is absent in web runtime.
  - Startup order-priority orchestration implemented so URL `poi` hydration/focus is prepared before non-blocking refresh completion (filters/list/location) while preserving deterministic fallback semantics.
- Web navigation smoke evidence:
  - `NAV_LANDLORD_URL="https://belluga.space" NAV_TENANT_URL="https://guarappari.belluga.space" bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `4 passed` (landlord + tenant roots/admin/home/landlord + workspace redirects).
- Backend lookup implementation evidence:
  - `laravel-app/routes/api/packages/project_tenant_public_api_v1/map_pois.php:12`
  - `laravel-app/packages/belluga/belluga_map_pois/src/Http/Api/v1/Requests/MapPoiLookupRequest.php:11`
  - `laravel-app/packages/belluga/belluga_map_pois/src/Http/Api/v1/Controllers/MapPoisController.php:29`
  - `laravel-app/packages/belluga/belluga_map_pois/src/Application/MapPoiQueryService.php:158`
  - `laravel-app/tests/Feature/Map/MapPoisControllerTest.php:90`
  - `laravel-app/tests/Feature/Map/MapPoisControllerTest.php:137`
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Map/MapPoisControllerTest.php` result: `12 passed (70 assertions)`.
- Flutter `poi`-only fallback closure evidence:
  - `flutter-app/lib/infrastructure/dal/dao/laravel_backend/map/laravel_map_poi_http_service.dart:81`
  - `flutter-app/lib/infrastructure/repositories/city_map_repository.dart:54`
  - `flutter-app/lib/infrastructure/repositories/poi_repository.dart:77`
  - `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart:351`
  - `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:837`
- New lint guard for future prevention:
  - `flutter-app/tool/belluga_custom_lint/lib/src/rules/route_required_non_url_args_forbidden_rule.dart:10`
  - `flutter-app/tool/belluga_custom_lint/lib/src/plugin.dart:85`
  - `flutter-app/tool/belluga_custom_lint/docs/rules.md:57`

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
- `delphi-ai/skills/wf-laravel-create-api-endpoint-method/SKILL.md`
- `delphi-ai/skills/rule-laravel-shared-tenant-access-guardrails-model-decision/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`

## Approval Gate
- 2026-03-20: TODO revalidated against current generated router; matrix completeness confirmed.
- 2026-03-20: Route disposition baseline frozen (`URL-Hydratable (Current Contract)` vs `Internal-Only` vs `Contract-Change Required`) and coherence scan applied.
- 2026-03-25: Backend typed lookup endpoint + Flutter `poi`-only fallback delivered and validated; contract exception removed.
