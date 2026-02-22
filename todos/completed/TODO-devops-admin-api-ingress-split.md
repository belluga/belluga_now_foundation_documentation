# TODO (DEVOPS): Split `/admin` SPA Routes from `/admin/api` Backend Routes

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Delphi  
**Date:** 2026-02-17

## Objective
Establish ingress behavior where only `/admin/api/*` reaches Laravel, while `/admin/*` is reserved for Flutter SPA routing.

## Scope
- Nginx ingress templates:
  - `docker/nginx/local.conf.template`
  - `docker/nginx/prod.conf.template`
- Replace broad `/admin/` Laravel forwarding with `/admin/api/` forwarding.
- Add exact-match handling for `/admin/api` (no trailing slash) so it does not fall through to SPA `location /`.
- Keep `/api/*` and other existing Laravel route forwards unchanged.

## Out of Scope
- Laravel route prefix changes (`/admin/api/v1` remains unchanged).
- Flutter route or repository contract changes.
- Cloud/edge provider rules outside repository-managed nginx templates.
- Foundation module/roadmap contract rewrites (no API contract change in this task).

## Decisions
- [x] ✅ Production‑Ready Keep Laravel admin API base path as `/admin/api/v1`.
- [x] ✅ Production‑Ready Reserve `/admin/*` for Flutter web admin UI navigation.
- [x] ✅ Production‑Ready Handle exact `/admin/api` in nginx to avoid accidental SPA fallback.

## Definition of Done
- [x] ✅ Production‑Ready `docker/nginx/local.conf.template` forwards only `/admin/api/*` (plus exact `/admin/api`) to Laravel.
- [x] ✅ Production‑Ready `docker/nginx/prod.conf.template` forwards only `/admin/api/*` (plus exact `/admin/api`) to Laravel.
- [x] ✅ Production‑Ready No broad `location ^~ /admin/` block remains in those templates.
- [x] ✅ Production‑Ready Repository environment verification script passes after change.

## Validation Steps
- [x] ✅ Production‑Ready `bash scripts/verify_environment.sh`
- [x] ✅ Production‑Ready `rg -n "location \^~ /admin/|location \^~ /admin/api/|location = /admin/api" docker/nginx/local.conf.template docker/nginx/prod.conf.template`
- [x] ✅ Production‑Ready `docker compose config`

## Validation Results
- `bash scripts/verify_environment.sh` passed, including Docker config checks and nginx invariants.
- `rg` confirmed only `location = /admin/api` and `location ^~ /admin/api/` remain in both nginx templates (no broad `/admin/` forwarding block).
- `docker compose config` returned `OK`.

## Rules/Workflows Applied
- `rule-docker-docker-runtime-ingress-model-decision`
- `wf-docker-update-runtime-and-ingress-method`
- `rule-docker-shared-todo-driven-execution-model-decision`
- `wf-docker-todo-driven-execution-method`
