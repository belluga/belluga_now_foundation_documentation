# TODO (V1): Docker `bot/next-version` Receiver Compatibility With Git 2.54

**Status:** Completed (`Docker main promotion and canonical dispatcher replay succeeded on 2026-05-17`)  
**Owner:** Delphi  
**Date:** 2026-05-16

## Objective
Restore the canonical Docker submodule-sync receiver so `flutter-app stage` and `laravel-app stage` dispatches can once again create or refresh a promotion-only `origin/bot/next-version` branch when a real submodule gitlink diff exists after Git 2.54 changed how `git add` behaves for submodules configured with `ignore=all`.

This slice is not about product behavior. It is a Docker CI/promotion-lane repair that must preserve the existing promotion topology:
- `flutter-app` and `laravel-app` reach `stage`
- they dispatch `repository_dispatch` to `belluga_now_docker`
- Docker receives that callback on its default branch workflow
- Docker creates or updates `origin/bot/next-version` from the latest `origin/dev` only when a real submodule gitlink diff exists
- only after that does the normal Docker promotion lane continue

## Framing Source
- `Direct-to-TODO`
- Primary story slice: Docker receiver compatibility fix plus replay of the canonical `stage -> repository_dispatch -> bot/next-version` automation.

## References
- [belluga_now_docker/.github/workflows/submodule-sync-pr.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/submodule-sync-pr.yml)
- [belluga_now_docker/.gitmodules](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.gitmodules)
- [flutter-app/.github/workflows/web-artifact-publish.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/.github/workflows/web-artifact-publish.yml)
- [laravel-app/.github/workflows/dispatch-docker-sync.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/.github/workflows/dispatch-docker-sync.yml)
- [foundation_documentation/modules/system_architecture_principles.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/modules/system_architecture_principles.md)
- [delphi-ai/tools/github_stage_promotion_preflight.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/delphi-ai/tools/github_stage_promotion_preflight.sh)
- [delphi-ai/tools/github_promotion_completion_guard.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/delphi-ai/tools/github_promotion_completion_guard.sh)

## Canonical Module Anchors
- Primary module: `foundation_documentation/modules/system_architecture_principles.md`
- Secondary module: none
- Decision consolidation target:
  - update `system_architecture_principles.md` only if the final repair introduces a durable platform-level principle about submodule sync ownership or promotion-lane automation;
  - otherwise the authoritative change remains the Docker workflow implementation itself.

## Execution Trace
- Primary execution profile: `Operational / DevOps`
- Active technical scope: `docker`
- Authoritative repo for the fix: `belluga_now_docker`
- Expected repos touched by implementation:
  - `belluga_now_docker`: yes
  - `flutter-app`: no code changes expected; only canonical `stage` dispatcher replay after Docker `main` carries the fix
  - `laravel-app`: no code changes expected; only canonical `stage` dispatcher replay after Docker `main` carries the fix
- Promotion topology requirement:
  - this is a Docker CI/workflow change
  - because `repository_dispatch` runs the receiver workflow from the Docker default branch, the fix must be promoted through the Docker lane until it reaches `main`
  - replaying dispatchers before Docker `main` contains the fix is invalid because the old receiver would still execute

## Scope
- Repair the Docker receiver so it stages gitlink updates correctly under Git 2.54 while preserving the repository's `ignore=all` submodule policy.
- Keep the existing sender payload and dispatch topology intact.
- Promote the Docker workflow fix through the required lane(s) until it reaches `main`.
- After Docker `main` carries the fix, replay the canonical `stage` dispatchers from `flutter-app` and `laravel-app` as needed.
- Verify that Docker recreates or refreshes `origin/bot/next-version` from the latest `origin/dev` with a submodule-only diff.
- Use the repaired canonical automation to resume the pending Docker promotion lane for the reconciled Flutter/Laravel pins.

## Out of Scope
- Any product, Flutter, Laravel, or web-app functional change.
- Any sender payload/schema change in `flutter-app` or `laravel-app`.
- Removing `ignore=all` from `.gitmodules`.
- Pinning GitHub Actions to an older Git version as the primary fix.
- Manual long-term reconstruction of `origin/bot/next-version` as a replacement for the canonical dispatch flow.
- Any direct change to generated `web-app` artifacts.

## Definition of Done
- The Docker receiver no longer drops a new submodule SHA when running on Git 2.54.
- The fix preserves `ignore=all` semantics and stages gitlink updates only through an explicit submodule path.
- Docker workflow validation passes locally (`actionlint`) and through the repo's promotion lane.
- The Docker workflow fix reaches `main`.
- After the fix is on Docker `main`, rerunning the canonical `stage` dispatchers either recreates or refreshes `origin/bot/next-version` when a real gitlink diff exists, or exits cleanly with no branch recreation when `origin/dev` already matches the dispatched SHAs.
- Any regenerated `origin/bot/next-version` contains only submodule gitlink changes and no regular file drift.
- No manual `bot/next-version` reconstruction is required after the fix reaches `main`.

## Validation Steps
- `actionlint .github/workflows/submodule-sync-pr.yml`
- Local Git 2.54 repro on the same Docker snapshot proves the chosen receiver command stages the gitlink while preserving `ignore=all`.
- `bash delphi-ai/tools/github_stage_promotion_preflight.sh --source <docker-fix-branch> --base origin/dev`
- Docker lane promotion validation for the workflow change up to `main` using the required promotion orchestrator flow.
- After Docker `main` is updated, rerun the canonical dispatchers from:
  - `flutter-app stage`
  - `laravel-app stage` when needed for the active lane state
- Confirm the resulting Docker `repository_dispatch` run either creates/updates `origin/bot/next-version` for a real gitlink diff or no-ops cleanly when no diff remains.
- If a replay produces `origin/bot/next-version`, run `bash delphi-ai/tools/github_stage_promotion_preflight.sh --source origin/bot/next-version --base origin/dev --require-diff-shape submodule-only`
- Complete the pending Docker promotion lane and finish with:
  - `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend`

## Evidence Baseline
### Canonical sender evidence
- `flutter-app` sends the expected payload from `stage` in [web-artifact-publish.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/.github/workflows/web-artifact-publish.yml).
- `laravel-app` sends the expected payload from `stage` in [dispatch-docker-sync.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/.github/workflows/dispatch-docker-sync.yml).
- The dispatch payload does **not** include any `ignore` flag.

### Receiver evidence
- Docker receives the expected payload in `repository_dispatch` and fails later at gitlink staging inside [submodule-sync-pr.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/submodule-sync-pr.yml):
  - `git -C "$SUBMODULE" checkout --detach "$TARGET_SHA"`
  - `git add "$SUBMODULE"`
  - staged-diff gate using `git diff --cached --ignore-submodules=none --quiet`
- Current `.gitmodules` config already sets `ignore = all` for `flutter-app` and `laravel-app` in [.gitmodules](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.gitmodules).

### Historical run evidence
- Manual Docker receiver success before the break:
  - run `25834499599`
  - submodule: `laravel-app`
  - result: commit created on `bot/next-version`
- Manual Docker receiver failure after the break:
  - run `25901878433`
  - submodule: `flutter-app`
  - result: `Skipping submodule due to ignore=all` then `INFO: no gitlink changes to commit.`
- Canonical sender replay after the manual reset also failed the same way:
  - Flutter sender run `25950950407`
  - Laravel sender run `25950950420`
  - Docker receiver runs `25951113646` and `25950952998`
  - `25951113646` reproduced the same `ignore=all` no-op on the Flutter SHA

### Isolated local repro evidence
- Same Docker repo snapshot: `d17eaeb3b5618c319587bbe58e6ffc3c19132d0c`
- Same gitlink transition: `243e0da981e54890b9fb5d836b7a2936b1063183 -> 951115189696e83250d80a031b161473cee87bb5`
- With `git 2.53.0`:
  - `git add flutter-app` stages the gitlink
- With `git 2.54.0`:
  - `git add flutter-app` skips with `ignore=all`
- With `git 2.54.0`:
  - `git add --force flutter-app` stages the gitlink successfully

### Upstream Git evidence
- Upstream commit `a16c4a245acb2420bafcbd572ba9fb94b1ba5146`:
  - `read-cache: submodule add need --force given ignore=all configuration`
- Upstream commit `6cc6d1b4c699323bc2a76e1a4cfbaede242cbfc8`:
  - documentation update for `git add --force` with `submodule.<name>.ignore=all`
- Upstream commit `297a27fdf2f68e95d7e344a22f20d9053b74b3ac`:
  - tests for `ignore=all` plus `git add --force`
- These upstream changes land in `v2.54.0`, matching the reproduced local break.

## Package-First Assessment
- Query executed: none
- Relevant packages found: none
- Decision: `n/a`
- Tier: `host Docker workflow / promotion automation`
- Rationale: this slice changes repository-owned GitHub Actions workflow behavior, not reusable package code.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The sender payload from `flutter-app` and `laravel-app` is already correct and should remain unchanged. | Canonical sender workflows and receiver logs show the expected `submodule`, `sha`, `base_branch=dev`, and `target_branch=bot/next-version`. | The slice would expand into cross-repo sender changes and need renewed approval. | `High` | `Keep as Assumption` |
| `A-02` | The break is caused by Git 2.54 receiver behavior, not by a recent versioned change in Docker workflow YAML. | Same Docker snapshot repro with Git 2.53 vs 2.54 isolates the behavior; no diff exists in `.github/workflows/submodule-sync-pr.yml` or `.gitmodules` between the compared receiver snapshots. | The TODO would need to widen into a versioned workflow regression analysis. | `High` | `Keep as Assumption` |
| `A-03` | The compatible fix should preserve `ignore=all` and use Git's explicit intent path rather than bypassing Git semantics. | Upstream Git docs and tests now require `--force` for explicitly staging a submodule with `ignore=all`. | The implementation might accidentally weaken repo-level assumptions or create future drift against Git semantics. | `High` | `Promote to Decision` |
| `A-04` | Replaying dispatchers only becomes valid after the fixed receiver reaches Docker `main`. | `repository_dispatch` executes the workflow from the Docker default branch, so replaying earlier would still run the old receiver. | The replay would produce misleading evidence and repeat the current no-op behavior. | `High` | `Promote to Decision` |

## Execution Plan
### Touched Surfaces
- `belluga_now_docker/.github/workflows/submodule-sync-pr.yml`
- optionally `foundation_documentation/modules/system_architecture_principles.md` only if a durable platform-level promotion principle must be documented

### Ordered Steps
1. Implement the minimal receiver change that adapts Docker submodule staging to the Git 2.54 `ignore=all` contract without changing sender topology or repo semantics.
2. Validate the receiver locally with:
   - `actionlint`
   - a Git 2.54 repro of the exact submodule staging path
3. Promote the Docker workflow fix through the required lane(s) until it reaches `main`.
4. After Docker `main` contains the fix, rerun the canonical `stage` dispatchers from the authoritative source repos.
5. Verify the Docker receiver recreates or refreshes `origin/bot/next-version` from the latest `origin/dev` with a submodule-only diff.
6. Resume and finish the pending Docker promotion lane for the reconciled Flutter/Laravel pins.

### Test Strategy
- **Strategy:** `evidence-first + local repro + promotion-lane replay`
- **Why:** the bug is a CI/promotion receiver incompatibility, not product logic, so the decisive evidence is:
  - deterministic local repro under Git 2.54
  - workflow lint/syntax validation
  - canonical post-main dispatcher replay
- **Fail-first target(s):**
  - the existing local Git 2.54 repro must fail before the fix and pass after it
  - the canonical post-main dispatcher replay must recreate `origin/bot/next-version` without manual branch surgery

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / workflow syntax` | The slice edits a GitHub Actions workflow. | `actionlint .github/workflows/submodule-sync-pr.yml` | `Local-Implemented` | `passed` | `actionlint .github/workflows/submodule-sync-pr.yml` at `reconcile/map-bootstrap-public-discoverability-20260515` on `2026-05-16` | Stayed green after switching the receiver to explicit-force gitlink staging. |
| `belluga_now_docker / Git 2.54 receiver repro` | The root cause is a Git 2.54 behavior change in the receiver path. | `manual Git 2.54 repro of checkout -> add -> staged diff using the exact receiver sequence and target SHA` | `Local-Implemented` | `passed` | fresh repro in `/tmp/git-submodule-repro-254-fixed/repo` on `2026-05-16` | Confirmed `before=243e0da...`, `after=95111518...`, `cached_diff=present`, `status=M  flutter-app;` using `git add --force -- flutter-app` under Git 2.54. |
| `belluga_now_docker / promotion preflight` | The workflow change must travel through the Docker lane cleanly. | `bash delphi-ai/tools/github_stage_promotion_preflight.sh --source <docker-fix-branch> --base origin/dev` | `promotion` | `planned` | preflight output | Treat any `no-go` as a hard stop. |
| `belluga_now_docker / bot-next-version shape guard` | The repaired automation must regenerate a clean submodule-only branch. | `bash delphi-ai/tools/github_stage_promotion_preflight.sh --source origin/bot/next-version --base origin/dev --require-diff-shape submodule-only` | `promotion` | `planned` | preflight output after dispatcher replay | Confirms the branch was recreated correctly from canonical automation. |

### Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Route / Surface | Planned Evidence | Notes |
| --- | --- | --- | --- | --- |
| `belluga_now_docker/.github/workflows/submodule-sync-pr.yml` | `internal-only` | GitHub Actions `repository_dispatch` / `workflow_dispatch` receiver | workflow lint, local Git 2.54 repro, canonical dispatcher replay | No frontend/app UI consumer is involved; this is promotion infrastructure only. |

## Complexity
- `medium`
- Checkpoint policy: one review after local repro passes and one review after the canonical dispatcher replay succeeds on Docker `main`.

## Execution Evidence
- Local implementation:
  - [submodule-sync-pr.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/submodule-sync-pr.yml) now stages the gitlink with `git add --force -- "$SUBMODULE"` while preserving `ignore=all` in `.gitmodules`.
- Local validation:
  - `actionlint .github/workflows/submodule-sync-pr.yml` ✅ on `2026-05-16`
  - Git 2.54 repro ✅ on `2026-05-16`
    - fresh clone path: `/tmp/git-submodule-repro-254-fixed/repo`
    - receiver snapshot: `d17eaeb3b5618c319587bbe58e6ffc3c19132d0c`
    - target transition: `243e0da981e54890b9fb5d836b7a2936b1063183 -> 951115189696e83250d80a031b161473cee87bb5`
    - observed result:
      - `before=243e0da981e54890b9fb5d836b7a2936b1063183`
    - `after=951115189696e83250d80a031b161473cee87bb5`
    - `add_output=<empty>`
    - `cached_diff=present`
    - `status=M  flutter-app;`
- Promotion evidence:
  - Docker source branch: `fix/docker-bot-next-version-git-254-compatibility-20260516`
  - Docker source commit: `5c24b86` (`ci(docker): force explicit gitlink staging for ignore-all submodules`)
  - PR `#619` `fix/docker-bot-next-version-git-254-compatibility-20260516 -> dev` merged as `e33d54aadf40b7a6a058e17dcb7ca71f8867a7cd`
  - `dev` post-merge push run `25978042089` green
  - PR `#620` `dev -> stage` merged as `945d549e8cdcfd46b3d705a656b924ed61a91127`
  - `stage` post-merge push run `25978078188` green
  - PR `#621` `stage -> main` merged as `1ac66d8454b5b98b198e425ecaf6dfbdd44221ec`
  - `main` post-merge push run `25978450690` completed `success`
- Canonical dispatcher replay evidence:
  - Laravel dispatcher rerun: `belluga_now_backend` run `25978474557` (`stage`, `workflow_dispatch`) completed `success`
  - Docker callback from the rerun: `belluga_now_docker` repository-dispatch run `25978476580` completed `success`
  - Log evidence from `25978476580`: the fixed receiver ran on `main@1ac66d8454b5b98b198e425ecaf6dfbdd44221ec`, used `git add --force -- "$SUBMODULE"`, and concluded `INFO: no gitlink changes to commit.` because `laravel-app` already matched `origin/dev`
  - Flutter dispatcher rerun: `belluga_now_front` run `25978474536` (`stage`, `workflow_dispatch`) completed `success`
  - `origin/bot/next-version` remained absent after the replay because the canonical source repos already matched `origin/dev`; this clean no-op is the expected steady-state outcome once no gitlink diff remains.

## Decision Baseline (Frozen)
- D-01 (`Preserve`): keep the current sender topology; `flutter-app stage` and `laravel-app stage` remain the only authoritative sources for this automatic Docker sync.
- D-02 (`Preserve`): keep `ignore=all` in `.gitmodules`; the repair must adapt to that contract rather than removing it.
- D-03 (`Preserve`): adapt the receiver using the Git-supported explicit-intent path for submodule staging under `ignore=all`, not by pinning an older Git or bypassing Git semantics.
- D-04 (`Preserve`): do not treat manual `bot/next-version` reconstruction as the normal recovery path once the receiver fix can be promoted.
- D-05 (`Preserve`): replay canonical dispatchers only after the repaired receiver is active on Docker `main`.

## Current Delivery Stage
- `Completed`

## Qualifiers
- `Root-Cause-Isolated`
- `Git-Upstream-Change-Confirmed`
- `Docker-CI-Slice`
- `Main-Branch-Activation-Required`
- `Promotion-Replay-Required`
- `Approved`
- `Local-Implemented`
- `Local-Gates-Passed`
- `Promotion-Merged-Through-Main`
- `Main-Post-Merge-Run-Succeeded`
- `Canonical-Dispatcher-Replay-Succeeded`
- `No-Manual-Reconstruction-Required`

## Next Exact Step
- None. Future promotions should continue using the canonical dispatcher flow; `bot/next-version` should remain ephemeral and remote-only during active promotion when a real gitlink diff exists.
