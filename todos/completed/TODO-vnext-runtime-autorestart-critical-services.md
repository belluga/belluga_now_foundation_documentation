# TODO (VNext): Auto-Restart Critical Runtime Services After Host Reboot

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owner:** Delphi
**Date:** 2026-02-25

## Goal
Ensure critical origin services (`app` and `nginx`) restart automatically after host reboot so the public origin recovers without manual intervention.

## Context / Evidence
- Recent production incident showed host came back but only `worker`/`scheduler` restarted.
- `docker-compose.yml` currently defines `restart: unless-stopped` for `worker` and `scheduler`, but not for `app` and `nginx`.
- Missing `app`/`nginx` restart policy caused Cloudflare `521` until manual service start.

## Scope
- Update `docker-compose.yml`:
  - Add `restart: unless-stopped` to service `app`.
  - Add `restart: unless-stopped` to service `nginx`.
- Validate compose syntax after modification.
- Apply runtime update on current production host so protection is active now, not only in future deploys.

## Out of Scope
- Changing restart policy for other services beyond current intended behavior.
- Reworking deployment topology, systemd units, or orchestrator migration.
- Firewall/network policy changes.

## Decisions
- Use `restart: unless-stopped` (matches existing policy used by `worker` and `scheduler`).
- Keep policy symmetric for `app` + `nginx` to preserve backend + ingress availability on reboot.
- Apply immediate server-side container restart-policy update and recreate `app/nginx` from compose to persist via project config.

## Definition of Done
- [x] ✅ Production‑Ready `docker-compose.yml` includes `restart: unless-stopped` for `app` and `nginx`.
- [x] ✅ Production‑Ready Compose config validation passes.
- [x] ✅ Production‑Ready Production host has running `app` and `nginx` with restart policy `unless-stopped`.
- [x] ✅ Production‑Ready Origin remains reachable after service refresh.

## Validation Steps
- [x] ✅ Production‑Ready `docker compose config >/dev/null`
- [x] ✅ Production‑Ready `rg -n "^\s*restart:\s*unless-stopped" docker-compose.yml`
- [x] ✅ Production‑Ready Remote check: `docker inspect -f '{{.Name}} {{.HostConfig.RestartPolicy.Name}}' belluga_now_docker-app-1 belluga_now_docker-nginx-1`
- [x] ✅ Production‑Ready Remote check: `docker compose ps` + `curl -I http://127.0.0.1` on server.
