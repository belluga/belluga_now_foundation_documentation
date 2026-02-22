# TODO (V1): DevOps Single-Gate Lane Promotion (Authoritative Flow)
**Version:** 1.1
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Active
**Owners:** DevOps + Platform Team
**Objective:** Keep one authoritative CI/CD flow for `dev`, `stage`, and `main`, with `belluga_now_docker` as the single promotion gate and exact-SHA validation as the release contract.

## Scope
- Repositories:
  - `belluga_now_docker`
  - `belluga_now_front` (Flutter source)
  - `belluga_now_web` (published web bundle)
  - `belluga_now_backend` (Laravel source)
- Lanes:
  - `dev`
  - `stage`
  - `main`

## Non-Negotiable Rules
1. Docker is the only orchestration gate for lane promotion.
2. Stage and main promotion are exact-SHA based (no tag-gated policy).
3. Promotion validation uses AND across all required repos (never OR).
4. During docker PR phase, source promotion PRs may be opened/updated, but must not be auto-merged.
5. Docker promotion PR must not be mergeable until every pinned SHA is already promoted to the target lane (or an allowed more-advanced lane):
   - Target `stage`: SHA must exist on `origin/stage` or `origin/main`.
   - Target `main`: SHA must exist on `origin/main` only.
6. Lane policy is snapshot-based for PR preparation, but strict for merge:
   - Preparation (opening/updating source PRs): expected SHA must exist on the source lane (`dev` for `dev->stage`, `stage` for `stage->main`) or already be promoted (no-op).
   - Merge gate (docker PR mergeable): expected SHA must satisfy Rule 5.
7. Already-promoted exact SHA is a success case (no-op), not a failure.
8. Promotion is snapshot-based: expected SHA must exist on the source lane, but is not required to be the current source-lane tip.
9. Source promotion required checks must rerun when PR body is edited (for `Expected SHA` lock correction), via `pull_request.edited`.
10. Push preflight on target lanes is strict:
    - `stage` push accepts SHA on `stage|main`.
    - `main` push accepts SHA on `main` only.
11. Lane gates are strict for promotions: `stage` only accepts PRs from `dev`; `main` only accepts PRs from `stage` (no bot sync branches to stage/main).
12. Exact-SHA promotion merge strategy is `--merge` only; `--squash`/`--rebase` are forbidden because they rewrite commit identity.
13. Lane-promotion PR broker concurrency is lane-scoped (`source_repo + head + base`), not SHA-scoped, to avoid stale SHA overwrite races.
14. Source promotion PR head may advance, but it must still contain the expected SHA as an ancestor at merge time.
15. Docker promotion PR must be blocked while any source promotion PR is not merge-ready (`mergeStateStatus != CLEAN`), including failing/pending checks or draft state.
16. Source repos must callback docker after promotion-PR CI completion so docker PR checks are automatically rerun (no manual rerun loop).
17. Web artifacts must be pinned-SHA correct (feature parity guarantee):
    - `web-app/build_metadata.json.flutter_git_sha` must match the pinned `flutter-app` SHA (short/long SHA tolerated).
18. Web artifacts must be runtime-config correct (domain/host guarantee):
    - `web-app/index.html` must contain a valid `window.__LANDLORD_HOST__` injection.
    - The injected `__LANDLORD_HOST__` must match the hostname derived from the pinned lane defines:
      - `flutter-app/config/defines/dev.json` when validating docker `dev`
      - `flutter-app/config/defines/stage.json` when validating docker `stage`
      - `flutter-app/config/defines/main.json` when validating docker `main`
    - `build_metadata.json.source_branch` is treated as diagnostic provenance only (not a hard gate), because the runtime behavior is determined by injected host + pinned flutter SHA.
19. Docker gate failures must be actionable and explicit:
    - Every failure must name the submodule, pinned SHA, required lane(s), and, when available, the source promotion PR URL to merge.
20. CI cost control for Flutter web publish:
    - PRs targeting `dev` must not run full web artifact build/publish validation.
    - Pushes to `dev` may publish web artifacts, but must not duplicate the same Flutter test suite already validated in PR.

## End-to-End Flow

### A) New Version Creation on `dev`
1. A Flutter change is merged into `belluga_now_front:dev`.
2. Flutter pipeline compiles web output and creates a branch in `belluga_now_web` containing generated bundle and `build_metadata.json`.
3. Flutter pipeline opens a PR to `belluga_now_web:dev`.
4. If web PR checks pass, that PR auto-merges to `web:dev`.
5. A merge on `web:dev` triggers docker submodule sync for lane `dev`.
6. Docker sync updates:
   - `web-app` gitlink to the merged web SHA.
   - `flutter-app` gitlink to `build_metadata.json.flutter_git_sha` (the exact Flutter SHA that produced the web bundle).
7. A merge on `laravel:dev` triggers docker submodule sync for lane `dev`, updating `laravel-app` gitlink.
8. Result: `docker:dev` holds an explicit pinned set (`flutter`, `web`, `laravel`) that represents the candidate release state.

### B) Promotion Preparation on Docker PR
Applicable for both mappings:
- `docker dev -> stage`
- `docker stage -> main`

1. Open docker promotion PR for the target mapping.
2. Docker workflow opens or updates source promotion PRs for `flutter`, `web`, and `laravel` using real lane heads (`dev -> stage`, `stage -> main`) and writes an explicit `Expected SHA` lock in PR metadata.
3. Source promotion PRs run their own checks, but are not merged in this phase.
4. Docker PR checks validate:
   - lane policy
   - submodule alignment
   - web/flutter metadata compatibility
   - exact pinned SHA CI status (green) for all required repos
   - existence on proper source lane (with no-op exceptions described above)
   - expected SHA is present on source lane (snapshot model)
   - source promotion PRs are merge-ready before docker PR is mergeable
   - pinned SHAs are already promoted to the target lane (Rule 5) before docker PR is mergeable
5. Docker PR becomes mergeable only if all required checks pass.
6. If source PR checks complete after docker preflight already failed, source callback triggers docker check rerun automatically.

### C) Promotion Execution on Docker Merge
1. Merge docker promotion PR into target lane (`stage` or `main`).
2. Deployment runs from docker pinned SHAs on the target lane.
3. Optional safety net: post-merge automation may still attempt to merge source promotion PRs, but should be a no-op because Rule 5 guaranteed promotion before docker merge.

## Worked Examples

### Example 1: `dev -> stage`
1. `docker:dev` pins:
   - `flutter = F10`
   - `web = W20` (metadata says `flutter_git_sha = F10`)
   - `laravel = L30`
2. Open docker PR `dev -> stage`.
3. Docker opens/updates source PRs `dev -> stage` in flutter/web/laravel.
4. Docker checks require:
   - preparation: `F10/W20/L30` exist on `dev` (or already in `stage|main`)
   - merge gate: `F10/W20/L30` exist on `stage|main`
   - web runtime-config: `W20/index.html` injects `window.__LANDLORD_HOST__` for the `stage` lane host (derived from pinned `flutter-app/config/defines/stage.json`)
   - all three SHAs have green CI for the exact commit
5. Operator merges the source PRs first (flutter/web/laravel).
6. Docker PR becomes mergeable only after the source promotions land and the lane gates pass.

### Example 2: `stage -> main`
1. `docker:stage` pins:
   - `flutter = F10`
   - `web = W20`
   - `laravel = L30`
2. Open docker PR `stage -> main`.
3. Docker checks require:
   - preparation: expected SHA exists on `stage` (or already exists on `main` as no-op)
   - merge gate: expected SHA exists on `main` only
   - web runtime-config: `W20/index.html` injects `window.__LANDLORD_HOST__` for the `main` lane host (derived from pinned `flutter-app/config/defines/main.json`)
   - exact SHA green checks for all required repos
4. Operator merges the source PRs first (flutter/web/laravel).
5. Docker PR becomes mergeable only after the source promotions land and the lane gates pass.

## Explicit Non-Goals (to avoid drift)
1. No direct autonomous lane promotion from source repo merges alone.
2. No OR semantics for repo validation.
3. No promotion based only on branch-head green status when exact pinned SHA differs.

## Implementation Checklist
- [ ] 🟡 Provisional — Adjusted docker lane-promotion broker to open/update real lane PRs (`dev->stage`, `stage->main`) and inject `Expected SHA` lock metadata; pending CI validation.
- [ ] 🟡 Provisional — Adjusted docker lane-promotion broker to stop enabling auto-merge during docker PR phase; pending CI validation.
- [x] ✅ Production‑Ready — Docker PR phase enforces exact-SHA green checks for flutter/web/laravel with AND semantics.
- [x] ✅ Production‑Ready — Docker PR phase enforces lane-aware SHA existence policy (`dev->stage` and `stage->main`).
- [ ] 🟡 Provisional — Added docker post-merge workflow step that triggers source PR merge attempts only after docker merge; pending CI validation.
- [ ] 🟡 Provisional — Added post-merge source PR merge gate enforcing exact SHA match + green checks; pending CI validation.
- [ ] 🟡 Provisional — Restricted post-merge source PR merge strategy to `--merge` only and added post-merge ancestor verification for exact SHA; pending CI validation.
- [ ] 🟡 Provisional — Added source PR drift protection via `Expected SHA` lock + execution-time ancestry check on real lane PRs; pending CI validation.
- [ ] 🟡 Provisional — Extended no-op allowance into post-merge execution (already present in preflight checks); pending CI validation.
- [x] ✅ Production‑Ready — Web sync updates both `web` and related `flutter` gitlinks in docker from metadata.
- [ ] 🟡 Provisional — Adjusted web/laravel lane-auto-promotion triggers so promotion remains docker-orchestrated; pending CI validation.
- [ ] 🟡 Provisional — Disabled direct docker submodule-sync PR creation for `stage/main` lanes; only `dev` sync PRs are allowed, pending CI validation.
- [ ] 🟡 Provisional — Serialized lane-promotion broker runs per lane (`source_repo/head/base`) to prevent stale SHA lock overwrites; pending CI validation.
- [ ] 🟡 Provisional — Added docker preflight gate that blocks docker promotion PR while any source promotion PR is not merge-ready (`mergeStateStatus=CLEAN` required); pending CI validation.
- [ ] 🟡 Provisional — Added source-repo callback (`repository_dispatch`) to rerun docker promotion PR checks automatically when source PR CI finishes; pending CI validation.
- [ ] 🟡 Provisional — Flutter publish workflow skips full web build on PRs targeting `dev`; pending CI validation.
- [ ] 🟡 Provisional — Flutter publish workflow avoids duplicate Flutter tests on `push` to `dev` while keeping build+publish; pending CI validation.
- [ ] 🟡 Provisional — Flutter publish workflow dispatches `submodule-updated` to `belluga_now_docker` for `submodule=flutter-app` on `push` to `dev`, so docker pin sync has the authoritative Flutter SHA without depending on web-app dispatch.
- [ ] 🟡 Provisional — Docker submodule sync workflow accepts `flutter-app` as a supported submodule target for `repository_dispatch` (`submodule-updated`), while preserving `dev`-only sync PR behavior.

## Definition of Done
1. This file is the single canonical CI/CD flow reference for lane promotion.
2. Docker promotion PR cannot merge unless all required exact SHAs are validated and green.
3. Source promotion PRs are prepared during docker PR phase against exact pinned SHAs, and must be merged before docker promotion PR becomes mergeable.
4. Stage and main follow the same exact-SHA orchestration model.
5. If an exact SHA was already promoted in a more advanced lane, execution records success as no-op (never false-fails).

## Provisional Notes
1. Current provisional items are implemented in workflow/scripts but not yet promoted to Production-Ready.
2. Upgrade criteria to `✅ Production‑Ready`:
   - `dev -> stage` docker PR run confirms source PR preparation uses exact pinned SHA and no auto-merge.
   - Docker merge to `stage` confirms post-merge source PR execution merges exact-SHA green candidates and records no-op success where applicable.
   - `stage -> main` repeats the same validations.
   - No autonomous source-repo push trigger reintroduces lane promotion dispatch outside docker orchestration.

## Operator Checklist (One-Screen)
- [x] Merge CI/CD fix branches into `dev` (`docker`, `flutter`, `web`, `laravel`, `foundation_documentation`).
Expected reaction: lane-promotion logic is live on `dev` with real lane PRs and `Expected SHA` lock checks.
- [x] Close stale source promotion PRs created with `bot/promote-*` heads.
Expected reaction: no obsolete promotion PR remains open for this run.
- [x] Close stale docker promotion PR `dev -> stage` (if still open).
Expected reaction: promotion run starts from a clean state.

- [ ] Open a fresh docker PR `dev -> stage`.
Expected reaction: docker preflight validates lane policy + exact-SHA CI + metadata compatibility.
- [ ] Confirm source PRs are created/updated as real lane PRs (`dev -> stage`) in `flutter`, `web`, and `laravel`.
Expected reaction: each source PR body contains `- Expected SHA: <40-char-sha>`.
- [ ] Confirm source PR head SHA contains the `Expected SHA` lock commit.
Expected reaction: source lane policy checks pass without `bot/promote-*` branch errors and without requiring lane tip freeze.
- [ ] Confirm source PRs are not auto-merged during docker PR phase.
Expected reaction: source PRs remain open until docker promotion merge.
- [ ] Merge the source promotion PRs first (`flutter`, `web`, `laravel`) for the target mapping.
Expected reaction: each pinned SHA becomes an ancestor of the target lane branch in the source repos.
- [ ] Confirm callback-driven rerun works after source PR checks complete.
Expected reaction: docker PR preflight reruns automatically (without manual rerun) and only turns green when all source PRs are merge-ready.
- [ ] Merge docker PR `dev -> stage`.
Expected reaction: docker deploys stage from lane-correct pinned SHAs.
- [ ] Verify stage deploy starts only after `promote_source_repos` success.
Expected reaction: `promote_source_repos` is green before `deploy_stage`.

- [ ] Open a fresh docker PR `stage -> main`.
Expected reaction: same exact-SHA preparation behavior for main promotion.
- [ ] Confirm source PRs are real lane PRs (`stage -> main`) with matching `Expected SHA` lock and head containing the locked SHA.
Expected reaction: source lane policy + snapshot SHA lock checks pass.
- [ ] Merge docker PR `stage -> main`.
Expected reaction: docker deploys production from lane-correct pinned SHAs.
- [ ] Verify production deploy starts only after `promote_source_repos` success.
Expected reaction: `promote_source_repos` is green before `deploy_main`.

- [ ] Final gate: no promotion used temporary `bot/promote-*` head branches.
- [ ] Final gate: no autonomous source push created lane promotion outside docker orchestration.
- [ ] Final gate: promote provisional items above to `✅ Production‑Ready` after successful run.
