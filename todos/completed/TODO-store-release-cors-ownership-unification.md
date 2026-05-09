# TODO (Store Release): CORS Ownership Unification (Definitive)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because browser/runtime parity needed a single canonical CORS owner. The implementation is now closed: Laravel owns CORS canonically, and Nginx no longer injects or normalizes ACAO.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`
**Status:** Production-Ready. Implementation, promotion, and completion-evidence reconciliation are complete.
**Owners:** DevOps + Laravel API + Flutter Platform

## Objective
Eliminate CORS drift and duplicated headers by establishing a **single canonical CORS owner** for API responses in all environments.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `none`
- **Next exact step:** archive to `completed/`; no active promotion follow-up remains.

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

## Definition of Done
- [x] API CORS ownership is singular and documented.
- [x] No endpoint returns duplicated ACAO headers.
- [x] Browser requests for tenant admin settings work without preflight/CORS ambiguity.
- [x] Verification evidence captured in artifacts.

## Validation Steps
- [x] Laravel automated CORS ownership tests cover landlord, tenant public, tenant admin, and unlisted-origin responses.
- [x] Web/browser navigation smoke proves the published tenant/admin runtime remains compatible after CORS ownership unification.
- [x] Stage promotion guard proves Docker, Flutter, and Laravel are aligned through the stage lane.

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
| Laravel-owned CORS canon + edge pass-through | `orchestrator/store-release-precritical-laravel` + `orchestrator/store-release-precritical-root` | `promoted through consolidated store-release lane` | `stage lane completion guard passed on 2026-04-27` | `n/a for current threshold` | `Production-Ready; completion guard passed and archived to completed` |

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| DOD-01 | Definition of Done | API CORS ownership is singular and documented. | code/test | `laravel-app/config/cors.php`; `docker/nginx/local.conf.template`; `docker/nginx/prod.conf.template`; `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Security/ApiCorsOwnershipTest.php --stop-on-failure` -> `4 passed` | Laravel API and Docker edge templates | passed | Laravel owns API CORS and Nginx forwards without competing ACAO behavior. |
| DOD-02 | Definition of Done | No endpoint returns duplicated ACAO headers. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Security/ApiCorsOwnershipTest.php --stop-on-failure` -> `4 passed`; test coverage includes landlord, tenant public, tenant admin, and unlisted-origin endpoint responses | Laravel API endpoints | passed | The endpoint assertions prove single ACAO ownership across the release API route families. |
| DOD-03 | Definition of Done | Browser requests for tenant admin settings work without preflight/CORS ambiguity. | runtime | `./scripts/build_web.sh ../web-app dev`; `tools/flutter/web_app_tests/navigation.spec.js`; `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `5 passed`; 2026-04-27 stage completion guard passed | Browser `https://belluga.space` and `https://guarappari.belluga.space`; stage lane | passed | The published web bundle and Playwright navigation smoke exercised browser API access after the CORS ownership change. |
| DOD-04 | Definition of Done | Verification evidence captured in artifacts. | doc/test | Validation Evidence section in this TODO records Laravel CORS tests, `./scripts/build_web.sh ../web-app dev`, Playwright `tools/flutter/run_web_navigation_smoke.sh readonly`, and stage lane completion guard output from 2026-04-27 | Foundation TODO evidence | passed | Evidence is recorded in this TODO and connected to the release lane guard. |
| VAL-01 | Validation Steps | Laravel automated CORS ownership tests cover landlord, tenant public, tenant admin, and unlisted-origin responses. | test | Laravel integration test `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Security/ApiCorsOwnershipTest.php --stop-on-failure` -> `4 passed` | Laravel API integration test runtime | passed | The dedicated integration test file covers the CORS ownership matrix named by this criterion. |
| VAL-02 | Validation Steps | Web/browser navigation smoke proves the published tenant/admin runtime remains compatible after CORS ownership unification. | runtime | `./scripts/build_web.sh ../web-app dev`; `tools/flutter/web_app_tests/navigation.spec.js`; `tools/flutter/run_web_navigation_smoke.sh readonly`; `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `5 passed` | Browser `https://belluga.space` and `https://guarappari.belluga.space` | passed | Browser navigation evidence proves the published runtime remains compatible after the CORS ownership correction. |
| VAL-03 | Validation Steps | Stage promotion guard proves Docker, Flutter, and Laravel are aligned through the stage lane. | promotion | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend` returned `Overall outcome: go` on 2026-04-27 | GitHub stage lane | passed | Docker, Flutter, and Laravel stage heads and gitlinks were aligned by the official completion guard. |

## Promotion-Lane Note
The temporary split owner model is removed. Canonical ownership now sits in Laravel, and the edge only forwards requests without competing CORS headers. This TODO is archived in `completed/` because no implementation, promotion, or evidence-reconciliation work remains.
