# TODO: DevOps CORS Hotfix + Web Navigation Validation (belluga.space)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Type:** Maintenance/Regression Fix  
**Owners:** DevOps + Flutter Platform

## Scope
- Apply only the immediate CORS hotfix already proposed: hide upstream CORS headers in Nginx PHP location to prevent duplicate ACAO on API GET responses.
- Keep existing route structure and contracts unchanged.
- Build Flutter web bundle and run real Playwright navigation tests in `web-app` against `belluga.space` / tenant host.

## Out of Scope
- Broad CORS policy redesign in Laravel.
- Cloudflare product-level rule redesign.
- New APIs or payload changes.

## Evidence Baseline
- Current responses on `/api/v1/environment` show duplicated `Access-Control-Allow-Origin` values (`*` + specific origin), causing browser ambiguity while Postman succeeds.
- Direct origin tests against local Nginx (`127.0.0.1:8081` with `Host`) reproduce duplication, confirming this is inside our stack.

## Definition of Done
- [x] ✅ Production‑Ready Nginx templates (`docker/nginx/local.conf.template`, `docker/nginx/prod.conf.template`) hide upstream CORS headers in PHP handler.
- [x] ✅ Production‑Ready Curl checks confirm a single ACAO for GET on both landlord and tenant domains.
- [x] ✅ Production‑Ready Flutter web bundle compiles successfully (FVM flow).
- [x] ✅ Production‑Ready Playwright navigation suite passes against real mapped domains with no runtime/request failures.
- [x] ✅ Production‑Ready Brief result report captured for handoff.

## Validation Steps
1. `curl -si -H 'Origin: https://belluga.space' https://guarappari.belluga.space/api/v1/environment | grep -i 'access-control-allow-origin'`
2. `curl -si -H 'Origin: https://belluga.space' https://belluga.space/api/v1/initialize | grep -i 'access-control-allow-origin'`
3. `./scripts/build_web.sh` (from `flutter-app`)
4. `npm ci && NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' npm run test:navigation` (from `web-app`)

## Applicable Rules/Workflows
- `rule-docker-shared-todo-driven-execution-model-decision`
- `rule-docker-docker-runtime-ingress-model-decision`
- `workflows/docker/update-runtime-and-ingress-method.md`
- `test-orchestration-suite`

## Execution Notes
- Nginx rebuilt and reloaded after template update (`docker compose up -d --build nginx`).
- Verified GET CORS headers now return a single origin on:
  - `https://guarappari.belluga.space/api/v1/environment`
  - `https://belluga.space/api/v1/initialize`
- Verified local-origin path (`http://127.0.0.1:8081` with tenant `Host`) also returns single ACAO.
- Web navigation validated in `web-app/tests/navigation.spec.js` against real domains.
