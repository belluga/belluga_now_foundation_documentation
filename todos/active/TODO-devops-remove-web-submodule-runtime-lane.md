# TODO (V1): Remove `web-app` Submodule from Docker and Enforce Runtime-Lane Web
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Active
**Owners:** DevOps + Platform Team

## Objective
Eliminate `web-app` as a promotable/pinned gitlink from `belluga_now_docker`.
Promotion contracts remain source-based (`flutter-app` + `laravel-app`), and web runtime/provenance is resolved per lane (`stage`/`main`) from `belluga_now_web` remote refs.

## Scope
- Repository: `belluga_now_docker`
- Files:
  - `.gitmodules`
  - `docker-compose.yml`
  - `.github/workflows/orchestration-ci-cd.yml`
  - `.github/workflows/submodule-sync-pr.yml`
  - `.github/scripts/verify_environment_ci.sh`
  - `.github/scripts/check_web_flutter_metadata.sh`
  - `.github/scripts/check_deployed_web_provenance.sh`
  - `.github/scripts/deploy_stage_over_ssh.sh`
  - `.github/scripts/rollback_over_ssh.sh`
  - `.github/scripts/collect_remote_deploy_diagnostics.sh`
  - `.github/scripts/handle_source_promotion_status_callback.sh`
  - `.github/scripts/check_promotion_lane.sh` (if lane bot-pattern is now outdated)

## Out of Scope
- Functional application changes in Flutter/Laravel.
- New promotion lanes or governance redesign.
- Disabling provenance checks on `stage`/`main`.

## Decision Lock
1. `web-app` submodule is removed from docker repo (`.gitmodules` + workspace checkout expectations).
2. No CI/CD rule may treat web SHA as promotable contract across lanes.
3. `stage`/`main` remain fail-closed on web provenance and flutter compatibility.
4. `dev` preflight must not hard-block due to legacy/stale web metadata pin mismatch.
5. Sync/promotion guardrails remain strict for source contracts (`flutter-app`, `laravel-app`) and infra PR isolation.
6. Navigation smoke jobs must remain enabled by obtaining test/runtime artifacts without `web-app` submodule pinning.

## Implementation Plan
- [ ] ⚪ Remove `web-app` from `.gitmodules` and adjust scripts expecting initialized `web-app` submodule.
- [ ] ⚪ Update `docker-compose.yml` web mount strategy so runtime no longer depends on repo submodule path.
- [ ] ⚪ Update preflight scripts to stop resolving `WEB_SHA` via `git ls-tree HEAD web-app`.
- [ ] ⚪ Keep `check_web_flutter_metadata.sh` lane-runtime validation using remote lane refs (not gitlink).
- [ ] ⚪ Keep `check_deployed_web_provenance.sh` fail-closed on `stage/main` (`source_branch == lane`, flutter SHA compatible).
- [ ] ⚪ Adjust stage/main deploy + rollback scripts to persist/restore effective runtime web SHA without submodule pin dependency.
- [ ] ⚪ Keep PR guardrails so only `bot/next-version` can alter source gitlinks (`flutter-app`, `laravel-app`).
- [ ] ⚪ Update orchestration workflow steps that currently rely on local `web-app` checkout for smoke tests.

## Definition of Done
1. `web-app` no longer appears as submodule in docker repo.
2. Docker promotion checks use only source contracts (`flutter-app`, `laravel-app`) for promotion gating.
3. `stage`/`main` deployments still fail on real web provenance mismatch and trigger rollback.
4. Dev preflight no longer fails for stale pinned web premise.
5. CI preflight/deploy jobs remain green with no hidden fallback masking errors.

## Validation Steps
1. `git config -f .gitmodules --get-regexp '^submodule\..*\.path$'` does not contain `web-app`.
2. `bash .github/scripts/check_web_flutter_metadata.sh dev` returns advisory/pass (no hard fail on legacy pin mismatch premise).
3. `bash .github/scripts/check_web_flutter_metadata.sh stage` and `main` remain strict pass/fail by runtime provenance.
4. PR preflight (`orchestration-ci-cd`) on `dev`:
   - infra-only PR passes,
   - non-bot source gitlink edits fail.
5. Push/deploy on `stage` and `main`:
   - provenance success path passes,
   - forced provenance mismatch fails and triggers rollback.

## Assumptions
- `belluga_now_web` remains authoritative source for runtime web artifacts by lane branch.
- Existing secrets/tokens already allow remote fetch/validation for lane runtime web resolution.
