# TODO (V1): Canonical Multi-Tenant Routing (Tenant + Tenant Admin)
**Version:** 1.2
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed (Superseded)
**Owners:** Flutter Team
**Objective:** Establish canonical URL routing where tenant context is explicit in the address and every tenant/tenant-admin screen can be opened and refreshed from URL-only state (no hidden in-memory dependencies).

## Completion Notes (2026-02-24)
- This TODO is superseded by host/domain-based canonical scope routing already delivered in:
  - `foundation_documentation/todos/completed/TODO-v1-environment-scope-reorganization.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- The specific strategy in this file (`/t/:tenantSlug` canonical path migration) is no longer aligned with the approved model.
- Remaining valid work moved to a focused active TODO:
  - `foundation_documentation/todos/completed/TODO-v1-route-url-only-hydration-hardening.md`
- Decision summary:
  - Keep tenant identity resolution by host/domain for `tenant_public` and `tenant_admin`.
  - Keep `/workspace` and `/workspace/{account_slug}` for `account_workspace`.
  - Continue hardening URL-only route hydration without introducing tenant slug path namespaces.

## Scope
- Enforce route contract rule in tenant + tenant-admin:
  - required data must be in `path`/`query`, or
  - argument becomes optional and is resolved from an ID/slug present in URL.
- Define canonical tenant-scoped URL schema including explicit tenant identifier (`tenantSlug`) in address.
- Ensure tenant selection/tenant context is hydratable from URL (`tenantSlug`) before feature controllers load.
- Keep in-app navigation behavior unchanged (allow optimistic data passing), but never as correctness dependency.
- Add focused route/deep-link coverage for create/edit/detail flows in both scopes.

## Out of Scope
- Backend/API contract changes.
- Visual redesign.
- Non-routing feature work.

## Inventory (Current Findings)
### A) Tenant context missing from canonical URL
- Tenant admin shell currently starts at `/admin` (no tenant slug/domain in path).
- Tenant app routes are global (`/home`, `/agenda`, `/mapa`, `/profile`, etc.) and do not encode tenant identity in the path.
- Consequence: a shared URL cannot deterministically resolve the intended tenant when opened from landlord/shared host contexts.

### B) Tenant-admin required args not in URL
- `TenantAdminTaxonomyTermsRoute`: requires `taxonomyName` (not in URL).
- `TenantAdminTaxonomyTermCreateRoute`: requires `taxonomyName` (not in URL).
- `TenantAdminTaxonomyTermEditRoute`: requires `taxonomyName` (not in URL).
- `TenantAdminTaxonomyTermDetailRoute`: requires `taxonomyName` (not in URL).

### C) Domain objects required in route constructor (not URL-resolvable)
- `TenantAdminTaxonomyEditRoute`: requires `taxonomy`.
- `TenantAdminTaxonomyTermEditRoute`: requires `term`.
- `TenantAdminTaxonomyTermDetailRoute`: requires `term`.
- `TenantAdminProfileTypeDetailRoute`: requires `definition`.
- `TenantAdminProfileTypeEditRoute`: requires `definition`.
- `TenantAdminStaticProfileTypeDetailRoute`: requires `definition`.
- `TenantAdminStaticProfileTypeEditRoute`: requires `definition`.

### D) Tenant routes with non-URL constructor dependencies
- `InviteShareRoute`: requires `InviteModel invite`.
- `PoiDetailsRoute`: requires `CityPoiModel poi`.

### E) Already URL-safe examples (ID/slug path params)
- `TenantAdminAccountDetailRoute` (`accountSlug` path).
- `TenantAdminOrganizationDetailRoute` (`organizationId` path).
- `TenantAdminStaticAssetDetailRoute` / `EditRoute` (`assetId` path).
- `EventDetailRoute` (`slug` path).
- `PartnerDetailRoute` (`slug` path).

## Plan
- [ ] ⚪ Pending Define and document canonical tenant-scoped path schema (`/t/:tenantSlug/...`) for both tenant and tenant-admin stacks.
- [ ] ⚪ Pending Add route-level hydration of tenant context from `tenantSlug` into canonical selected-tenant state before child features execute.
- [ ] ⚪ Pending Migrate tenant-admin entry paths from `/admin/...` to slugged canonical paths.
- [ ] ⚪ Pending Migrate tenant app primary paths from global paths (`/home`, `/agenda`, etc.) to slugged canonical paths.
- [ ] ⚪ Pending Add compatibility redirects from legacy non-slug paths to canonical slugged paths when resolvable.
- [ ] ⚪ Pending Convert non-URL required args (`taxonomyName`, `definition`, `term`, `taxonomy`, `invite`, `poi`) to optional fast-path args resolved from URL IDs/slugs/query.
- [ ] ⚪ Pending Ensure detail/edit screens render explicit loading/error/not-found when deep-linked without preloaded objects.
- [ ] ⚪ Pending Keep existing in-app navigation passing optional objects/labels for first-frame UX only.
- [ ] ⚪ Pending Add/update route tests for tenant context parsing + URL-only sufficiency.
- [ ] ⚪ Pending Add integration test coverage for:
  - landlord -> tenant-admin deep-link with slug,
  - tenant route deep-link with slug,
  - refresh persistence without fallback state injection.

## Definition of Done
- [ ] ⚪ Pending Every tenant-admin and tenant route can render from URL-only state (path/query) after direct open/refresh.
- [ ] ⚪ Pending Canonical URL contains tenant identifier for tenant-scoped flows.
- [ ] ⚪ Pending No tenant/tenant-admin route requires non-URL constructor args for correctness.
- [ ] ⚪ Pending Existing in-app navigation remains functional (no UX regression).
- [ ] ⚪ Pending Route/deep-link tests cover both scopes with refresh-safe assertions.

## Validation Steps
- [ ] ⚪ Pending `fvm flutter analyze`
- [ ] ⚪ Pending `fvm flutter test test/application/router/tenant_admin_route_path_params_test.dart`
- [ ] ⚪ Pending Add/run focused route tests for tenant slug canonicalization.
- [ ] ⚪ Pending Add/run focused widget/integration tests for deep-link refresh on routes that previously required non-URL args.
- [ ] ⚪ Pending Manual web validation on `belluga.space`:
  - open canonical slug URL directly,
  - refresh browser,
  - confirm same tenant context and data.

## Notes
- This TODO supersedes the original taxonomy-only deep-link framing.
- Core issue is not only missing path params in detail routes, but missing tenant identity in canonical URLs for shared-host navigation/deep-linking.
