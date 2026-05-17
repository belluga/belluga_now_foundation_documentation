# EPHEMERAL - Foundation Main Convergence and App Rebaseline

## Classification
- complexity: medium
- checkpoint_policy: consolidated review
- flow: DevOps / Workspace Recovery / Rebaseline Preparation

## Purpose
Preserve the current session context so work can resume safely after compression while we:
- converge `foundation_documentation` back to `main` as the only canonical docs branch;
- rebaseline `belluga_now_docker`, `flutter-app`, and `laravel-app` as execution repositories;
- avoid unnecessary churn on the `foundation_documentation` gitlink in the Docker superproject.

## Explicit Constraint
- Out of scope for this session slice: updating or reconciling the `foundation_documentation` gitlink in `belluga_now_docker`.

## Confirmed Project Rule
- `foundation_documentation` should not remain on ad hoc working branches as a normal operating mode.
- Canonical docs state must be consolidated into `foundation_documentation:main`.
- Ongoing implementation/rebaseline branches belong in:
  - `belluga_now_docker`
  - `flutter-app`
  - `laravel-app`

## Current Branch Snapshot
- `belluga_now_docker`
  - current branch: `dev`
  - canonical branches only: `dev`, `stage`, `main`
- `foundation_documentation`
  - current branch: `main`
  - expected canonical branch: `main`
  - canonical branches only: `main`
- `flutter-app`
  - current branch: `dev`
  - canonical branches only: `dev`, `stage`, `main`
- `laravel-app`
  - current branch: `dev`
  - canonical branches only: `dev`, `stage`, `main`

## Rollback / Healthy-State Investigation Snapshot
- The rollback/healthy-state material we were searching for is preserved in `foundation_documentation:main`, not only in transient branches.
- Confirmed docs in `foundation_documentation:main`:
  - `todos/active/fast_follow_required/TODO-post-release-docker-rollback-runtime-web-fidelity.md`
  - `todos/active/vnext/TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`
- Confirmed supporting artifact in current docs checkout:
  - `artifacts/tmp/post-release-deploy-rollback-safety-claude-review-20260514.md`

## Foundation Convergence Result
The previous `foundation_documentation` branch-only deltas were preserved into `main` with four local commits:
1. `ad45dc3` `docs: add orchestration plan for map and public capability waves`
2. `b97ea05` `docs: record wave evidence and runtime blockers`
3. `a47bfcb` `docs: expose profile type public discoverability`
4. `4d206d5` `docs: record docker bot next-version git 2.54 compatibility`

Additional resolution details:
- `tenant_admin_module.md` was updated by applying only the semantically valid `is_publicly_discoverable` patch on top of current `main`, avoiding regression of newer module content.
- `foundation_documentation` was pushed successfully and is now clean on `main...origin/main`.
- All non-`main` local and remote branches were deleted from `foundation_documentation`.

## Rebaseline Preflight Findings
### `belluga_now_docker`
- Local Docker rebaseline state is now clean on `dev...origin/dev`.
- Resolved local blockers/anomalies:
  - deleted local `bot/submodule-sync-stage-otp-admin-reconcile-20260515`
  - deleted local `reconcile/dev-to-stage-otp-admin-smoke-20260515`
  - deleted local `bot/next-version`
  - deleted local `test/map-public-reentry-readonly-20260517`
  - deleted local patch-equivalent debris:
    - `fix/invite-push-feed-promotion-readiness-20260514`
    - `fix/invite-push-feed-promotion-root-tests-20260515`
    - `fix/otp-auth-admin-stage-mutation-race-20260515`
    - `reconcile/map-bootstrap-public-discoverability-20260515`
- Removed remote stale branches as well:
  - `origin/reconcile/dev-to-stage-otp-admin-smoke-20260515`
  - `origin/fix/docker-bot-next-version-git-254-compatibility-20260516`
  - `origin/fix/docker-stage-submodule-sync-20260515`
  - `origin/fix/map-initial-origin-bootstrap-20260515`
  - `origin/fix/stage-otp-admin-detached-locator-20260515`
  - `origin/fix/stage-web-smoke-app-promotion-readiness-20260512`
- Removed external Docker worktree for `fix/docker-bot-next-version-git-254-compatibility-20260516`.
- Final Docker branch topology is now limited to `dev`, `stage`, and `main` locally and remotely.

### `flutter-app`
- Rebaseline completed to `dev...origin/dev`
- Removed local/remote non-canonical branches:
  - `fix/invite-push-feed-promotion-readiness-20260514`
  - `fix/map-initial-origin-bootstrap-20260515`
  - `promote/invite-push-live-reflection-stage-20260512`
  - local `worker/public-discoverability-wave2-20260515`
- Removed external Flutter worktrees for map bootstrap and public discoverability.
- Final Flutter branch topology is now limited to `dev`, `stage`, and `main` locally and remotely.

### `laravel-app`
- Rebaseline completed to `dev...origin/dev`
- Removed local/remote non-canonical branches:
  - `fix/invite-push-feed-promotion-readiness-20260514`
  - `fix/map-initial-origin-bootstrap-20260515`
  - local `fix/push-topology-hardening-ci-equivalent-20260513`
  - `promote/fcm-http-v1-direct-send-stage-20260512`
- Final Laravel branch topology is now limited to `dev`, `stage`, and `main` locally and remotely.

## Decision Baseline
- D-01: `foundation_documentation` must converge to `main`; docs branch drift is not acceptable as steady-state.
- D-02: Rebaseline execution work belongs to Docker/Flutter/Laravel, not to long-lived docs branches.
- D-03: Do not touch the `foundation_documentation` gitlink in the Docker superproject as part of this slice.
- D-04: When branch truth is ambiguous, validate against the current codebase and keep the newest semantically valid behavior, not necessarily the oldest whole-file variant.
- D-05: `bot/next-version` is pipeline-owned and should not remain as a local working branch outside promotion flow.

## Next Exact Steps
1. If a later session needs historical evidence from pruned branches, recover it from commit history or archived PRs rather than reviving branch sprawl.
2. Keep future promotion-only helper branches ephemeral and remote-scoped only.
3. Continue operational work from the canonical branch sets only:
   - `foundation_documentation`: `main`
   - `belluga_now_docker`: `dev`, `stage`, `main`
   - `flutter-app`: `dev`, `stage`, `main`
   - `laravel-app`: `dev`, `stage`, `main`

## Resume Guidance
If the session is compressed, resume from this exact question:
- "Branch topology has been normalized; what is the next operational change to make on the canonical branches only?"
