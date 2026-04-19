# TODO (Store Release): CORS Ownership Unification (Definitive)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because browser/runtime parity needed a single canonical CORS owner. The implementation is validated and promoted through `dev`: Laravel owns CORS canonically, and Nginx no longer injects or normalizes ACAO, but `stage`/`main` promotion remains open.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [x] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`
**Status:** Lane-Promoted. The canonical CORS ownership change is implemented, validated, and promoted through `dev`, but this TODO must stay active until the same slice is promoted through `stage` and the later `main` follow-up is real.
**Owners:** DevOps + Laravel API + Flutter Platform

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Browser-Compatibility`
- **Next exact step:** promote the already-dev-merged Laravel/root slices through `stage`, rerun the CORS verification suite against the published `stage` hosts, and leave `main` explicit as pending until that promotion actually starts.

## Objective
Eliminate CORS drift and duplicated headers by establishing a **single canonical CORS owner** for API responses in all environments.

## Execution Lane Tracking
- **Local implementation branches:** `laravel-app:worker/store-release-cors-laravel`, `belluga_now_docker:worker/store-release-cors-root`, `foundation_documentation:worker/store-release-cors-docs`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Backend canonical CORS ownership | `worker/store-release-cors-laravel@c3c91cea8a6b` | https://github.com/belluga/belluga_now_backend/pull/156 | `<pending>` | `<pending>` | `🟣 Lane-Promoted to dev on 2026-04-19 via PR #156; Laravel is the canonical CORS owner, but stage/main promotion is still pending.` |
| Edge/Nginx CORS ownership removal | `worker/store-release-cors-root@ab63990766d9` | https://github.com/belluga/belluga_now_docker/pull/501 | `<pending>` | `<pending>` | `🟣 Lane-Promoted to dev on 2026-04-19 via PR #501; Nginx no longer injects ACAO in dev, but stage/main promotion is still pending.` |
| Documentation authority | `worker/store-release-cors-docs@0fae56791abf` | `n/a (foundation docs repo is not promoted through dev/stage/main lanes)` | `n/a` | `n/a` | `Local docs authority was updated on 2026-04-19; the remaining promotion work for this TODO is the downstream stage/main follow-up tracked above.` |

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

## Current State Note
The temporary split owner model is removed in the delivered implementation. Canonical ownership now sits in Laravel, and the edge only forwards requests without competing CORS headers; what remains is lane promotion and published verification beyond `dev`.
