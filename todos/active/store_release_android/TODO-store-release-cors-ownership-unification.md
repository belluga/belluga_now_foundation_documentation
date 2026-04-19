# TODO (Store Release): CORS Ownership Unification (Definitive)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because browser/runtime parity is still guarded by a temporary split model. Current Nginx templates still hide upstream CORS headers and inject response headers directly, so ownership has not yet converged to one canonical layer.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Active  
**Owners:** DevOps + Laravel API + Flutter Platform

## Objective
Eliminate CORS drift and duplicated headers by establishing a **single canonical CORS owner** for API responses in all environments.

## Scope
- Define one CORS owner for API routes (`/api/*`, `/admin/api/*`, account-scoped routes).
- Remove split-responsibility behavior (upstream PHP + Nginx both injecting CORS).
- Guarantee browser compatibility for credentialed requests.
- Add automated verification that API responses return exactly one `Access-Control-Allow-Origin` value.

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
- API CORS ownership is singular and documented.
- No endpoint returns duplicated ACAO headers.
- Browser requests for tenant admin settings work without preflight/CORS ambiguity.
- Verification evidence captured in artifacts.

## Notes
- Current hotfix may keep Nginx as temporary response normalizer (`fastcgi_hide_header`), but VNext must remove this temporary split and converge to canonical ownership.
