# TODO (Store Release): CORS Ownership Unification (Definitive)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because browser/runtime parity needed a single canonical CORS owner. The implementation is now closed: Laravel owns CORS canonically, and Nginx no longer injects or normalizes ACAO.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Local-Implemented (`implementation and local validation closed on 2026-04-19; canonical promotion is still pending`)
**Owners:** DevOps + Laravel API + Flutter Platform

## Objective
Eliminate CORS drift and duplicated headers by establishing a **single canonical CORS owner** for API responses in all environments.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** `Promote the reconciled branch to dev, then update the promotion evidence and advance the TODO to Lane-Promoted.`

## Ecosystem Impact Analysis
- **Current classification:** `Project-Local`
- **Why:** this is a downstream runtime/API ownership correction for Belluga's Laravel + Nginx stack and its published tenant/browser behavior.
- **Reuse doctrine note:** the ownership split lesson may inform future ingress/API guardrails, but this slice is not a reusable package extraction candidate.

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

## Execution Lane Tracking
- **Local implementation branches:** `laravel-app:orchestrator/store-release-precritical-laravel`, `belluga_now_docker:orchestrator/store-release-precritical-root`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Laravel-owned CORS canon + edge pass-through | `orchestrator/store-release-precritical-laravel` + `orchestrator/store-release-precritical-root` | `not-published` | `not-published` | `not-published` | `Merged into reconciliation branch; dedicated CORS tests green, published build smoke green, awaiting canonical promotion` |

## Promotion-Lane Note
The temporary split owner model is removed. Canonical ownership now sits in Laravel, and the edge only forwards requests without competing CORS headers. This TODO now lives in `promotion_lane/` because no further implementation work remains, but the canonical promotion path (`dev -> stage -> main`) is still pending.
