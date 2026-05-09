# TODO (Deferred Micro Optimization): Environment App-Domain Lookup Deduplication

**Status:** Active  
**Priority:** Low  
**Owners:** Laravel Team  
**Class:** Deferred Micro Optimization

## Context
The broad tenant environment/materialization work appears effectively delivered: healthy tenant `/api/v1/environment` reads are snapshot-backed and no longer re-aggregate the full environment payload on the hot path.

One small inefficiency remains in the **landlord-host mobile bootstrap** path:
- request: `GET /api/v1/environment`
- host: landlord host
- header: `X-App-Domain`

In that path, tenant resolution by `app_domain` is performed twice:
1. validation-time existence check in `EnvironmentRequest`
2. resolver-time tenant lookup in `EnvironmentResolverService`

This is a real extra lookup, but it is not currently a correctness issue and does not appear to be causing user-visible latency.

## Evidence
- [EnvironmentRequest.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Http/Api/v1/Requests/EnvironmentRequest.php)
  - `withValidator()` calls `TenantAppDomainResolverService::findTenantByIdentifier(...)`
- [EnvironmentResolverService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Environment/EnvironmentResolverService.php)
  - `resolve()` falls back to `locateTenant(...)` when no current tenant exists
- [TenantAppDomainResolverService.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Tenants/TenantAppDomainResolverService.php)
  - performs the actual resolver query path
- [bootstrap/app.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/bootstrap/app.php)
  - confirms tenant-domain requests and landlord-host requests use different middleware groups

## Why This Is Deferred
- The healthy environment read path is already materially optimized by the snapshot model.
- This duplicated lookup affects only a narrower landlord-host app bootstrap variant.
- The remaining work is a micro-optimization, not a missing architecture slice.

## Scope
- [ ] Decide the canonical request-local reuse strategy for resolved `app_domain` tenant identity.
- [ ] Remove duplicate `findTenantByIdentifier(...)` execution from the landlord-host app bootstrap path.
- [ ] Preserve current request validation semantics (`Unknown app_domain.` remains explicit).
- [ ] Keep the change request-local and low-risk; do not reopen the broader environment snapshot design.

## Out of Scope
- [ ] Reworking the environment snapshot architecture.
- [ ] Reclassifying or reopening the broad tenant-settings materialization TODO.
- [ ] Broader tenant-resolution redesign unless this optimization exposes a real correctness issue.

## Candidate Approaches
- Reuse the resolved tenant from request validation by storing it on the request and consuming it in the resolver.
- Move the existence check fully into one canonical path if error semantics can be preserved without double work.
- Add a request-scoped memo around `findTenantByIdentifier(...)` if that keeps the contract simplest.

## Acceptance Criteria
- [ ] The landlord-host `X-App-Domain` bootstrap path resolves the tenant once per request.
- [ ] `Unknown app_domain.` behavior remains unchanged for invalid identifiers.
- [ ] No regression is introduced in tenant-domain bootstrap or landlord environment fallback.

## Notes
- Do **not** use this TODO to smuggle in invalidation/correctness work.
- If evidence grows that stale or missing snapshot inputs can survive landlord settings changes, that belongs back in `post_release_hardening`, not here.
