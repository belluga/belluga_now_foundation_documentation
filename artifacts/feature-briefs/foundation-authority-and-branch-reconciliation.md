# Feature Brief: Foundation Authority and Branch Reconciliation

## Artifact Role
- **Why this brief exists now:** the request combines Delphi-method recalibration, missing project authority surfaces, stale foundation-documentation references, and multi-repo unmerged branch review. That is too broad for one safe tactical TODO without first decomposing the work into bounded slices.
- **What this brief is not:** canonical project authority, a replacement for `project_constitution.md`, `system_roadmap.md`, module docs, a merge plan, or implementation authority.

## Source Idea / Request
- Reconcile the project with the current Delphi baseline, especially the `foundation_documentation/` repository, while preserving the latest accepted decisions and not losing relevant updates that still live on unmerged branches.

## Problem / Desired Outcome
- **Problem:** the project is running with material governance drift. `foundation_documentation/project_constitution.md` is missing, top-level docs still carry stale lane/authority references, and the current branch topology includes real unmerged work that cannot be cleaned up blindly.
- **Desired outcome:** restore a current project-level authority baseline, document which unmerged branches still matter, and bound the follow-up normalization work so future implementation can proceed without rediscovering the same drift.
- **Why now:** `project_recalibration_doctor.sh` classified the repository as `manual-remediation-required`, and ongoing work is already depending on implicit constitutional decisions that are not formally written down.

## Constraints / Non-Goals
- **Constraints:** preserve the latest approved project decisions; do not force-delete, rebase, or quietly discard unmerged branches; keep repo-specific merge targets explicit (`origin/dev` for root/flutter/laravel, `origin/main` for `foundation_documentation`); record the environment-readiness blocker exposed by the storage-alias invariant failure.
- **Non-goals:** full module-by-module documentation sweep in one pass; product/runtime code changes in Flutter or Laravel; remote branch deletion; branch cleanup execution before the relevant content is classified.

## Canonical Touchpoints
- **Constitution impact:** `yes` — `foundation_documentation/project_constitution.md` must be restored so project-level rules stop living only in TODO assumptions and scattered module notes.
- **Roadmap impact:** `yes` — `foundation_documentation/system_roadmap.md` should reflect the current authority/reconciliation posture instead of implying stale backlog lanes or missing-summary gaps as the only integrity issue.
- **Primary module candidates:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`

## Evidence / References
- `foundation_documentation/artifacts/tmp/project-setup-report.txt`
- `foundation_documentation/artifacts/tmp/project-normalization-packet.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/README.md`
- root branch preflight (`belluga_now_docker`)
- `flutter-app` branch preflight (`feature/home-agenda-radius-button-behavior` + rollback branches)
- `laravel-app` branch preflight
- `foundation_documentation` repo branch audit against `origin/main`
- targeted `rg` results showing legacy `mvp_slices` / `mvp_closure` lane references and repeated notes that `project_constitution.md` is absent

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Whether the nginx storage-alias invariant failure should be absorbed into the same reconciliation slice. | The setup report treats it as manual remediation, but the user asked primarily for documentation and branch reconciliation. | `project_recalibration_doctor.sh` marked `manual-remediation-required` because the storage alias invariant is missing in Docker nginx templates. | `carry as TODO assumption` |
| `AMB-02` | Which legacy documentation references should be normalized in the first slice versus deferred to later module sweeps. | The foundation repo contains many stale references; trying to fix them all at once would create an unbounded TODO. | `rg` shows old `mvp_slices` / `mvp_closure` references across modules, artifacts, and completed TODOs. | `resolve now` |
| `AMB-03` | How to classify branch relevance without using one merge target for every repo. | `foundation_documentation` uses `origin/main`, while the code repos use `origin/dev`; applying one policy would misclassify branches. | Root/flutter/laravel branch preflight uses `origin/dev`; `foundation_documentation` remote head is `origin/main`. | `resolve now` |
| `AMB-04` | Whether relevant branch content should be merged, cherry-picked, or only carried forward into documentation. | The user wants to keep relevant updates without losing the latest decisions, but some branches may already be partially superseded. | `flutter-app` rollback branches contain real revert commits not in `origin/dev`; `foundation_documentation` feature branches have doc-only deltas not yet in `main`. | `carry as TODO assumption` |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Clear the explicit manual-remediation blocker from the setup report so recalibration can close cleanly. | `docker runtime / readiness` | `foundation_documentation` | Environment-readiness report no longer fails on the storage-alias invariant. | `scripts/verify_environment.sh` and `project_recalibration_doctor.sh` no longer report the blocker. | `defer` | Separate Operational/DevOps decision; not the main user ask. | Keep visible, but do not silently absorb it into doc normalization. |
| `ST-02` | Restore project-level authority by creating `project_constitution.md` and aligning top-level foundation docs to the current Delphi baseline. | `project authority / foundation_documentation` | `system_roadmap`, top-level summaries, selected module anchors | The repo has a current constitution and the touched top-level docs stop depending on missing authority surfaces or retired lane paths. | `test -f foundation_documentation/project_constitution.md`; targeted `rg` on touched docs shows no stale lane-path references; doc diffs are coherent. | `create-now` | Requires bounded scope discipline; should not expand into a full module sweep. | This is the first delivery slice for the user request. |
| `ST-03` | Produce a repo-specific branch reconciliation matrix so relevant unmerged work is preserved deliberately. | `branch governance / repo inventory` | `foundation_documentation`, `flutter-app`, root repo, `laravel-app` | Each relevant branch is classified as `integrate`, `preserve-for-later`, `cleanup-later`, or `already-safe`, with rationale. | Recorded matrix references branch-audit evidence and commit IDs. | `merge-with-other` | Depends on ST-02 staying bounded; no cleanup execution in the same slice. | This supports ST-02 because branch evidence informs what should become canonical. |
| `ST-04` | Sweep module- and TODO-level stale references that still point to retired lane structures or missing authority docs. | `foundation_documentation/modules/*.md` | active/completed TODOs, summaries | Touched modules/TODOs no longer reference obsolete lane paths or “constitution missing” as a standing assumption. | Targeted `rg` on selected files returns no stale references. | `split-further` | Needs a dedicated follow-up slice after project authority is restored. | Too broad for the first reconciliation pass. |
| `ST-05` | Execute safe local branch cleanup / rebaseline after relevant content is classified. | `repo branch topology` | root repo, `flutter-app`, `foundation_documentation` | Safe cleanup is executed only for already-classified branches, with no blocker branches lost. | Branch preflight returns `ready`, cleanup output is explicit, and repo baselines are updated intentionally. | `defer` | Must wait until ST-03 decisions are frozen. | This is execution, not the first authority slice. |

## Retire This Brief When
- The active tactical TODO for `ST-02` exists, with `ST-03` explicitly merged into its bounded scope as supporting branch evidence rather than a separate unbounded initiative.
