# TODO (V1): Stage-Driven Source Sync Directly to `bot/next-version` (PR -> `dev`)
**Version:** 2.2
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Completed
**Owners:** DevOps + Platform Team
**Objective:** Stabilize submodule sync governance with a simple, auditable flow: source repos dispatch only from `stage`, docker sync lands only in `bot/next-version -> dev` (manual merge), and non-bot PRs cannot alter source gitlinks.

## Scope (Today)
- `belluga_now_front` (`flutter-app`)
- `belluga_now_backend` (`laravel-app`)
- `belluga_now_docker`

## Out of Scope (Today)
- Removing `web-app` submodule.
- Architecture redesign across repositories.
- Functional app changes.

## Decision Lock (Simple Plan)
1. `flutter-app` and `laravel-app` stop automatic dispatch from `dev`.
2. Dispatch happens on merge/push to `stage` and explicitly targets docker branch `bot/next-version` (with base `dev`).
3. Docker sync always updates fixed branch `bot/next-version` and opens/updates one PR to `dev`.
4. That PR remains manual (no auto-merge).
5. On PRs targeting docker `dev`, gitlink changes for source sync (`flutter-app`, `laravel-app`, `web-app`) are allowed only when `head == bot/next-version`.
6. Infra PRs can continue normally, but cannot carry source gitlink mutations.
7. Promotion contracts remain source-based (`flutter-app` + `laravel-app`).
8. Existing stage/main runtime/provenance guardrails remain intact (no weakening).

## Exact Changes (File-by-File)

### A) `belluga_now_front` (`flutter-app`)
  - [x] ✅ Production-Ready `flutter-app/.github/workflows/web-artifact-publish.yml`
  - Change docker dispatch trigger condition from `dev` to `stage`.
  - Keep `submodule=flutter-app`.
  - Set dispatch payload `target_branch=bot/next-version`.
  - Set dispatch payload `base_branch=dev`.
  - Set `source_branch=stage` in payload.

### B) `belluga_now_backend` (`laravel-app`)
- [x] ✅ Production-Ready `laravel-app/.github/workflows/dispatch-docker-sync.yml`
  - Restrict automatic trigger to `push` on `stage` (manual dispatch remains).
  - Set dispatch payload `target_branch=bot/next-version`.
  - Set dispatch payload `base_branch=dev`.
  - Set `source_branch=stage` in payload.

### C) `belluga_now_docker`
- [x] ✅ Production-Ready `.github/workflows/submodule-sync-pr.yml`
  - Replace dynamic bot branch (`bot/submodule-sync-*`) with fixed `bot/next-version`.
  - Accept dispatch payload `target_branch/base_branch`; default to `bot/next-version` + `dev`.
  - Keep automatic sync base restricted to `dev`.
  - Keep single long-lived PR (`bot/next-version -> dev`) updated in place.
  - Remove superseded PR cleanup logic tied to dynamic branch names.

- [x] ✅ Production-Ready `.github/scripts/check_submodule_gitlink_guardrail.sh` (new)
  - For PRs targeting `dev`, detect gitlink changes in `flutter-app`, `laravel-app`, `web-app`.
  - Fail unless `head == bot/next-version`.

- [x] ✅ Production-Ready `.github/workflows/orchestration-ci-cd.yml`
  - Invoke `check_submodule_gitlink_guardrail.sh` in preflight (PR context).

## Definition of Done
1. Source sync no longer mutates docker `dev` directly.
2. Only `bot/next-version` can carry source gitlink updates into docker `dev`.
3. Infra PRs to docker `dev` stay unblocked when they do not modify gitlinks.
4. Stage-dispatch to docker `dev` works for both flutter and laravel source repos.

## Validation Steps
1. `flutter-app`: push to `stage` dispatches `submodule=flutter-app`, `target_branch=bot/next-version`, `base_branch=dev`, `source_branch=stage`.
2. `laravel-app`: push to `stage` dispatches `submodule=laravel-app`, `target_branch=bot/next-version`, `base_branch=dev`, `source_branch=stage`.
3. Docker sync updates `bot/next-version` and creates/updates one PR into `dev`.
4. Docker PR to `dev` from non-bot branch with gitlink diff fails guardrail.
5. Docker PR to `dev` from non-bot branch without gitlink diff passes guardrail.
6. Merging `bot/next-version -> dev` remains manual and auditable.

## Execution Notes (2026-02-22)
- Applied in working trees:
  - `belluga_now_docker`
  - `belluga_now_front`
  - `belluga_now_backend`
- Guardrail behavior validated locally:
  - Non-bot PR (`head != bot/next-version`) with gitlink diff on base `dev` fails.
  - Bot PR (`head = bot/next-version`) with gitlink diff on base `dev` passes.
  - Non-PR context skips as expected.
- YAML/shell sanity checks executed:
  - `git diff --check` clean for root + touched submodules.
  - `bash -n .github/scripts/check_submodule_gitlink_guardrail.sh` passed.

## Assumptions
- `DOCKER_SYNC_TARGET_REPO` and dispatch tokens are configured in source repos.
- Branch protection on docker `dev` requires PR merge (no direct push bypass).

## Closure Evidence (2026-02-23)
- `flutter-app/.github/workflows/web-artifact-publish.yml` dispatches to docker only on `stage` with payload:
  - `target_branch=bot/next-version`
  - `base_branch=dev`
  - `source_branch=stage`
- `laravel-app/.github/workflows/dispatch-docker-sync.yml` dispatches only on `stage` with the same payload contract.
- `belluga_now_docker/.github/workflows/submodule-sync-pr.yml` enforces fixed sync target `bot/next-version` and `dev` base for automatic sync.
- `belluga_now_docker/.github/scripts/check_submodule_gitlink_guardrail.sh` is wired in orchestration preflight and blocks non-bot gitlink mutations on PRs targeting `dev`.
