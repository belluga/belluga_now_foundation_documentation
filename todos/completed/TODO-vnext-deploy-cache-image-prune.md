# TODO (VNext): Post-deploy Docker Cache/Image Pruning in CI SSH Deploy Flow

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owner:** Delphi
**Date:** 2026-02-25

## Goal
Stabilize disk usage on stage/main servers by pruning stale Docker build cache and unused images after SSH-based deploy/rollback jobs complete successfully.

## Context / Evidence
- Current deploy flow rebuilds containers on every promotion (`docker compose up -d --build --remove-orphans`).
- Production server recently reached high disk pressure due to accumulated Docker cache/layers.
- Manual incident cleanup recovered significant space, indicating missing automatic prune in deploy automation.

## Scope
- Update `.github/scripts/deploy_stage_over_ssh.sh`:
  - Add a safe post-deploy prune step on remote host.
  - Prune BuildKit cache and unused images.
  - Keep prune non-blocking (warn only) so successful deploy is not turned into failure by cleanup issues.
- Update `.github/scripts/rollback_over_ssh.sh`:
  - Apply same prune policy after rollback deployment passes health checks.
- Preserve existing health checks, migration behavior, and rollback semantics.

## Out of Scope
- Pruning Docker volumes.
- Pruning running/stopped containers in ways that could affect active services.
- Changing image build architecture, Dockerfiles, or runtime service topology.
- Altering GitHub Actions job graph/secrets model.

## Decisions
- Use `docker builder prune -af --filter "until=168h"` for stale build cache.
- Use `docker image prune -af --filter "until=168h"` for stale unused images.
- Run cleanup only after health-success path in deploy/rollback.
- Cleanup failures must emit warnings and continue.

## Definition of Done
- [x] ✅ Production‑Ready Deploy SSH script includes non-blocking post-success prune step for build cache and images.
- [x] ✅ Production‑Ready Rollback SSH script includes equivalent non-blocking post-success prune step.
- [x] ✅ Production‑Ready Scripts pass syntax validation (`bash -n`).
- [x] ✅ Production‑Ready Existing deploy health/rollback checks remain intact (no regression in script control flow).

## Validation Steps
- [x] ✅ Production‑Ready `bash -n .github/scripts/deploy_stage_over_ssh.sh`
- [x] ✅ Production‑Ready `bash -n .github/scripts/rollback_over_ssh.sh`
- [x] ✅ Production‑Ready `rg -n "builder prune|image prune" .github/scripts/deploy_stage_over_ssh.sh .github/scripts/rollback_over_ssh.sh`
- [x] ✅ Production‑Ready Manual review confirms prune is called only on success path and guarded with warning-only failure handling.
