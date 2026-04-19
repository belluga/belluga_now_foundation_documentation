# TODO (Store Release): CORS Ownership Unification (Definitive)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because browser/runtime parity needed a single canonical CORS owner. The implementation is now closed: Laravel owns CORS canonically, and Nginx no longer injects or normalizes ACAO.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed (`implementation and validation closed on 2026-04-19`)  
**Current delivery stage:** `Completed`
**Next exact step:** `n/a`
**Owners:** DevOps + Laravel API + Flutter Platform

## Objective
Eliminate CORS drift and duplicated headers by establishing a **single canonical CORS owner** for API responses in all environments.

## Delivered Scope
- [x] Define one CORS owner for API routes (`/api/*`, `/admin/api/*`, account-scoped routes).
- [x] Remove split-responsibility behavior so upstream PHP owns CORS and Nginx no longer injects or normalizes headers.
- [x] Guarantee browser compatibility for credentialed requests without using a wildcard plus credentials.
- [x] Add automated verification that API responses return exactly one `Access-Control-Allow-Origin` value.

## Out of Scope
- New product features.
- Tenant settings UX changes.

## Definitive Strategy (Mandate)
1. Backend/API layer becomes canonical owner for API CORS policy (preferred final state).
2. Nginx keeps routing, TLS, cache, and preflight pass-through responsibilities only.
3. CORS policy is explicit and route-aware:
   - Include both `/api/*` and `/admin/api/*` paths in canonical config.
   - Use explicit allowed origins or controlled patterns; never emit conflicting wildcard + credentialed origin.
4. Edge/proxy layers must not append competing CORS headers.
5. Introduce regression checks (curl or automated tests) asserting:
   - no duplicate `Access-Control-Allow-Origin` headers,
   - expected behavior for GET + OPTIONS,
   - parity on landlord and tenant hosts.

## Acceptance Criteria
- [x] API CORS ownership is singular and documented.
- [x] No endpoint returns duplicated ACAO headers.
- [x] Browser requests for tenant admin settings work without preflight/CORS ambiguity.
- [x] Verification evidence captured in artifacts.

## Validation Evidence
- Laravel now owns CORS in `laravel-app/config/cors.php` with explicit allowlist/patterns and `supports_credentials=true`.
- `docker/nginx/local.conf.template` and `docker/nginx/prod.conf.template` no longer hide upstream CORS headers or inject ACAO/OPTIONS behavior.
- Dedicated CORS coverage passes for landlord, tenant public, tenant admin, and unlisted-origin responses.
- Consolidated reconciliation validation also passed on 2026-04-19:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Security/ApiCorsOwnershipTest.php --stop-on-failure` -> `4 passed`
  - `./scripts/build_web.sh ../web-app dev` -> published the reconciled bundle used for browser validation
  - `NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `5 passed`
- Final sequential validation passed twice after fixture repair and tenant bootstrap stabilization.

## Closure Note
The temporary split owner model is removed. Canonical ownership now sits in Laravel, and the edge only forwards requests without competing CORS headers.
