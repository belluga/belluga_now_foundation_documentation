# TODO — DevOps Single-Gate Lane Promotion (Authoritative Flow)

## Objective
Keep one authoritative CI/CD flow for `dev`, `stage`, and `main`, with `belluga_now_docker` as the single promotion gate and exact-SHA validation as the release contract.

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
5. Source PR merge automation is allowed only after docker merge and only for exact-SHA green candidates.
6. Lane policy is lane-aware:
   - `dev -> stage`: expected SHA must exist on `dev`, or already exist on `stage`/`main` (unchanged no-op).
   - `stage -> main`: expected SHA must exist on `stage`, or already exist on `main` (unchanged no-op).
7. Already-promoted exact SHA is a success case (no-op), not a failure.

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
2. Docker workflow opens or updates source promotion PRs for `flutter`, `web`, and `laravel` with the same lane mapping, each one tied to the exact docker-pinned SHA (not branch head).
3. Source promotion PRs run their own checks, but are not merged in this phase.
4. Docker PR checks validate:
   - lane policy
   - submodule alignment
   - web/flutter metadata compatibility
   - exact pinned SHA CI status (green) for all required repos
   - existence on proper source lane (with no-op exceptions described above)
5. Docker PR becomes mergeable only if all required checks pass.

### C) Promotion Execution on Docker Merge
1. Merge docker promotion PR into target lane (`stage` or `main`).
2. Post-merge docker workflow attempts to merge source promotion PRs.
3. Each source PR is merged only if:
   - checks are green
   - PR content matches the exact SHA expected by docker pinned state for that lane
4. If the exact SHA is already present in the target or a more advanced lane branch, mark that repo as successful no-op for this promotion.
5. If any repo is neither mergeable exact-SHA+green nor already-promoted no-op, that repo merge is blocked.
6. Deployment runs from docker pinned SHAs on the target lane.

## Worked Examples

### Example 1: `dev -> stage`
1. `docker:dev` pins:
   - `flutter = F10`
   - `web = W20` (metadata says `flutter_git_sha = F10`)
   - `laravel = L30`
2. Open docker PR `dev -> stage`.
3. Docker opens/updates source PRs `dev -> stage` in flutter/web/laravel.
4. Docker checks require:
   - `F10` exists on `dev` (or already in `stage`/`main`)
   - `W20` exists on `dev` (or already in `stage`/`main`)
   - `L30` exists on `dev` (or already in `stage`/`main`)
   - all three SHAs have green CI for the exact commit
5. After docker PR merge, docker attempts source PR merges for the exact `F10/W20/L30`.
6. If `W20` is already on `web:stage`, docker marks `web` as successful no-op and continues.

### Example 2: `stage -> main`
1. `docker:stage` pins:
   - `flutter = F10`
   - `web = W20`
   - `laravel = L30`
2. Open docker PR `stage -> main`.
3. Docker checks require:
   - expected SHA exists on `stage`, or already exists on `main` (no-op)
   - exact SHA green checks for all required repos
4. After docker PR merge, source PR merge automation runs only for exact `F10/W20/L30`.
5. If `L30` is already on `laravel:main`, docker marks `laravel` as successful no-op and continues.

## Explicit Non-Goals (to avoid drift)
1. No direct autonomous lane promotion from source repo merges alone.
2. No OR semantics for repo validation.
3. No promotion based only on branch-head green status when exact pinned SHA differs.

## Implementation Checklist
- [ ] 🟡 Provisional — Adjusted docker lane-promotion broker to open/update source promotion PRs bound to the exact docker-pinned SHA (not branch head); pending CI validation.
- [ ] 🟡 Provisional — Adjusted docker lane-promotion broker to stop enabling auto-merge during docker PR phase; pending CI validation.
- [x] ✅ Production‑Ready — Docker PR phase enforces exact-SHA green checks for flutter/web/laravel with AND semantics.
- [x] ✅ Production‑Ready — Docker PR phase enforces lane-aware SHA existence policy (`dev->stage` and `stage->main`).
- [ ] 🟡 Provisional — Added docker post-merge workflow step that triggers source PR merge attempts only after docker merge; pending CI validation.
- [ ] 🟡 Provisional — Added post-merge source PR merge gate enforcing exact SHA match + green checks; pending CI validation.
- [ ] 🟡 Provisional — Added source PR drift reconciliation via fixed promotion branch + forced SHA reset + execution-time head check; pending CI validation.
- [ ] 🟡 Provisional — Extended no-op allowance into post-merge execution (already present in preflight checks); pending CI validation.
- [x] ✅ Production‑Ready — Web sync updates both `web` and related `flutter` gitlinks in docker from metadata.
- [ ] 🟡 Provisional — Adjusted web/laravel lane-auto-promotion triggers so promotion remains docker-orchestrated; pending CI validation.

## Definition of Done
1. This file is the single canonical CI/CD flow reference for lane promotion.
2. Docker promotion PR cannot merge unless all required exact SHAs are validated and green.
3. Source promotion PRs are prepared during docker PR phase against exact pinned SHAs, and executed only after docker merge.
4. Stage and main follow the same exact-SHA orchestration model.
5. If an exact SHA was already promoted in a more advanced lane, execution records success as no-op (never false-fails).

## Provisional Notes
1. Current provisional items are implemented in workflow/scripts but not yet promoted to Production-Ready.
2. Upgrade criteria to `✅ Production‑Ready`:
   - `dev -> stage` docker PR run confirms source PR preparation uses exact pinned SHA and no auto-merge.
   - Docker merge to `stage` confirms post-merge source PR execution merges exact-SHA green candidates and records no-op success where applicable.
   - `stage -> main` repeats the same validations.
   - No autonomous source-repo push trigger reintroduces lane promotion dispatch outside docker orchestration.
