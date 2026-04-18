# Branch Reconciliation Matrix

## Artifact Identity
- **Artifact kind:** `branch_reconciliation_matrix`
- **Authoritative:** `false`
- **Date:** `2026-04-18`
- **Source evidence:** repo-local branch audits (`branch_rebaseline_preflight.sh`, `git cherry -v`, `git log --left-right --cherry-mark --oneline`)

## Purpose

Record the current branch-relevance picture without executing cleanup. This artifact is assistive only: it does not merge, delete, or rebaseline branches by itself.

## Repository Baselines

| Repository | Normal merge target | Current branch | Workspace state | Summary |
| --- | --- | --- | --- | --- |
| `belluga_now_docker` | `origin/dev` | `fix/docker-tenant-public-shell-root-routing` | dirty (`?? ".github (3).zip"`, `?? .github/workflows.zip`, `?? .tmp_worktrees/`) | No active blocking branches, but current local branch and repair residue should not be cleaned while the workspace is dirty. |
| `flutter-app` | `origin/dev` | `feature/home-agenda-radius-button-behavior` | clean | Contains real blocking branches; cleanup/rebaseline must wait for explicit classification. |
| `laravel-app` | `origin/dev` | `feature/public-web-branding-fallback-og-hardening` | clean | No blocking branches from the audit; current feature branch is already represented on mainline. |
| `foundation_documentation` | `origin/main` | `feat/tenant-admin-domain-management` | clean | Documentation repo uses `main` authority; multiple unmerged doc branches still contain relevant commits. |

## Classification Legend

- `integrate`: unique branch content should be reviewed for merge/cherry-pick or direct incorporation into current canonical work.
- `preserve-for-later`: unique branch intent exists and should not be deleted, but it should not be merged blindly into current mainline either.
- `cleanup-later`: branch appears safe/stale, but cleanup should happen only after the current workspace is clear and the recommendation is rechecked.
- `already-safe`: branch content is already represented on the current authority line or in a superset branch.

## Repository Detail

### 1. `belluga_now_docker`

| Branch | Evidence | Classification | Recommendation | Notes |
| --- | --- | --- | --- | --- |
| `fix/docker-tenant-public-shell-root-routing` | Safe-local-cleanup candidate from preflight; upstream is gone; unique commits are submodule-pin sync commits (`2fe1c03`, `0da80a9`). | `cleanup-later` | Clear the dirty workspace first, then decide whether this branch still needs a fresh PR. If not, delete it after rechecking against `origin/dev`. | Do not conflate untracked local artifacts with authoritative branch value. |
| `repair/bot-next-version-20260416` | Safe-local-cleanup candidate; unique commit `2fe1c03` matches the current branch's pin-sync line. | `cleanup-later` | Treat as stale local repair residue unless a current promotion-lane recovery needs it. Recheck before deleting. | This is not a normal long-lived delivery branch. |

### 2. `flutter-app`

| Branch | Evidence | Classification | Recommendation | Notes |
| --- | --- | --- | --- | --- |
| `fix/main-promotion-blockers-stage` | Unique commits against `origin/dev`: `349e3a2d Track agenda radius changes`, `8e429e4a Harden agenda radius promotion blockers`. | `integrate` | Review these commits against the current agenda-radius behavior and either cherry-pick/supersede them explicitly in a fresh branch or close them with written rationale. Do not delete. | This is the strongest unresolved code branch from the Flutter audit. |
| `rollback/dev-invalid-radius-promotion-20260416` | Contains unique revert `406b566e Revert "Merge pull request #227..."` plus the original radius-tracking commit in the comparison set. | `preserve-for-later` | Keep until the team explicitly decides whether the dev rollback history is still relevant or fully superseded. | Rollback branches are evidence-bearing branches, not automatic cleanup targets. |
| `rollback/stage-invalid-radius-promotion-20260416` | Contains unique revert `5aeaecec Revert "Merge pull request #228..."` plus the original radius-tracking commit in the comparison set. | `preserve-for-later` | Keep until stage rollback intent is either superseded by later fixes or deliberately retired. | Same treatment as the dev rollback branch. |
| `feature/home-agenda-radius-button-behavior` | Marked as a remote cleanup candidate by preflight. | `already-safe` | No additional reconciliation action needed unless later review discovers missing behavior. | The branch may still be deleted later, but it is not a blocker. |
| `origin/feature/public-web-branding-fallback-and-location-back` | Marked as a remote cleanup candidate by preflight. | `already-safe` | Treat as historical/cleanup-only. | No current blocker signal from the audit. |

### 3. `laravel-app`

| Branch | Evidence | Classification | Recommendation | Notes |
| --- | --- | --- | --- | --- |
| `feature/public-web-branding-fallback-og-hardening` | Preflight outcome `ready`; remote branch is a cleanup candidate, not a blocker. | `already-safe` | No immediate reconciliation action required. | Later cleanup may remove the branch after normal confirmation. |

### 4. `foundation_documentation`

| Branch | Evidence | Classification | Recommendation | Notes |
| --- | --- | --- | --- | --- |
| `origin/feat/canonical-route-back-policies` | Unique commits against `origin/main`: `59aedbe`, `0eb67e2`. | `integrate` | Fold the canonical navigation-governance changes into the next docs PR to `main` or supersede them explicitly if the new authority line already covers them. | Relevant, unmerged documentation work. |
| `origin/feat/tenant-admin-domain-management` | Unique commits against `origin/main`: `f185b71`, `dfba8e7`, `369a37c`. | `integrate` | Review and merge/cherry-pick the still-relevant domain-management and event-party documentation into the current docs authority line. | Current working branch is already on this line. |
| `origin/feat/tenant-admin-event-parties-blocker` | Its unique commit `369a37c` is already contained in `origin/feat/tenant-admin-domain-management`. | `already-safe` | Treat as a narrower superseded branch once the superset branch is reconciled. | Keep only if the narrower branch is still needed for review context. |
| `origin/feat/events-implementation` | No unique branch-side commits remained against `origin/main` in the comparison. | `already-safe` | No current reconciliation action required. | Historical line only. |
| `origin/feat/events-ticketing-planning-baseline` | No unique branch-side commits remained against `origin/main` in the comparison. | `already-safe` | No current reconciliation action required. | Historical line only. |

## Recommended Next Moves

1. Restore top-level project authority first (`project_constitution.md` + touched docs).
2. Reconcile the `foundation_documentation` `integrate` branches into the docs mainline before attempting broad branch cleanup.
3. Review `flutter-app` `fix/main-promotion-blockers-stage` and the rollback branches as an explicit release-history decision, not as cleanup noise.
4. Leave actual cleanup/rebaseline execution to a later repo-specific pass after current documentation changes are reviewed and workspaces are clean.
