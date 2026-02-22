# TODO (DEVOPS): Optional Cloudflared Tunnel for Local Environment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Delphi  
**Date:** 2026-02-17

## Objective
Reintroduce Cloudflare Tunnel support for **local development only** as an optional runtime profile, with per-developer credentials kept out of version control.

## Scope
- Docker runtime (`docker-compose.yml`):
  - Add optional `cloudflared` service under a dedicated profile (no impact on default local flow).
  - Route tunnel traffic to local `nginx` service on the internal Docker network.
- Local config hygiene:
  - Introduce tracked template for tunnel variables.
  - Ensure real tunnel secret/config file is ignored by git.
- Developer UX:
  - Add explicit commands to bring stack up/down with optional local tunnel.
  - Keep current no-tunnel flow as the default recommendation.
- Documentation:
  - Update root README local section with optional Cloudflared setup.
  - Update DevOps roadmap note to reflect optional (not mandatory) tunnel strategy.

## Out of Scope
- Stage/main/prod tunnel changes.
- Cloudflare account provisioning automation.
- Laravel API contract changes.
- Flutter lane define contract changes.

## Decisions
- [x] ✅ Production‑Ready Cloudflared remains **optional** and isolated via profile (`local-tunnel`).
- [x] ✅ Production‑Ready Tunnel credentials are developer-managed in local untracked file(s); repository stores only `.example` template(s).
- [x] ✅ Production‑Ready Default local commands remain tunnel-free unless developer explicitly enables the tunnel profile.
- [x] ✅ Production‑Ready Makefile exposes a convenience target for local+tunnel while preserving existing targets.

## Definition of Done
- [x] ✅ Production‑Ready `docker-compose.yml` includes optional `cloudflared` service/profile without affecting current default stack.
- [x] ✅ Production‑Ready `.env.local.tunnel.example` is tracked with required keys documented.
- [x] ✅ Production‑Ready `.gitignore` excludes developer tunnel secret/config file(s).
- [x] ✅ Production‑Ready README documents:
  - default local flow (no tunnel),
  - optional tunnel flow (with profile),
  - where developer stores tunnel token/config.
- [x] ✅ Production‑Ready DevOps roadmap text no longer states “tunnel-free only”; instead documents optional local tunnel.
- [x] ✅ Production‑Ready `docker compose config` passes for:
  - default local profile (`local-db`),
  - local tunnel profile (`local-db,local-tunnel`) when developer file is present.

## Validation Steps
- [x] ✅ Production‑Ready `COMPOSE_PROFILES=local-db docker compose config` succeeds (baseline unchanged).
- [x] ✅ Production‑Ready With local tunnel env-file present, `COMPOSE_PROFILES=local-db,local-tunnel docker compose config` succeeds.
- [ ] 🟡 Provisional `COMPOSE_PROFILES=local-db,local-tunnel docker compose up -d --build` starts `cloudflared` + current local services.
- [ ] 🟡 Provisional `docker compose logs cloudflared` shows successful tunnel startup (or clear actionable error if token invalid).
- [x] ✅ Production‑Ready Existing no-tunnel commands continue working as-is.

## Validation Results
- `COMPOSE_PROFILES=local-db docker compose config` passed after introducing optional `cloudflared` profile.
- `COMPOSE_PROFILES=local-db,local-tunnel docker compose --env-file .env --env-file .env.local.tunnel.example config` passed.
- `cloudflared` runtime startup/log validation remains provisional because it depends on a developer-provided valid token in `.env.local.tunnel`.

## Rules/Workflows Applied
- `rule-docker-docker-runtime-ingress-model-decision`
- `wf-docker-update-runtime-and-ingress-method`
- `rule-docker-shared-todo-driven-execution-model-decision`
