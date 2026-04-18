# Title
Foundation Authority and Branch Reconciliation Baseline

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The current Delphi method has moved ahead of this project's documentation and governance posture. The most material symptom is that `foundation_documentation/project_constitution.md` is still missing, while top-level docs and TODOs continue to rely on scattered assumptions, stale lane references, and repeated "constitution missing" caveats. At the same time, the current branch topology includes real unmerged work in `flutter-app` and `foundation_documentation`, so cleanup or rebaseline work cannot proceed safely without an explicit classification step.
This authority-reconciliation slice now also absorbs the PACED Level 0 re-baselining requirements that were introduced after the original approval: explicit ecosystem reuse analysis, TODO-linked session memory identity, and deterministic readiness for `Ecosystem Impact Analysis`.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** restoring project-level authority and freezing the branch-relevance inventory gives the project a safe baseline for future work without trying to normalize every stale module reference in one pass.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Blocked`
- **Next exact step:** review the restored authority docs and branch matrix on `foundation_documentation:feat/tenant-admin-domain-management`, then decide the separate Operational/DevOps remediation and later branch-action follow-up.

## Scope
- [x] Author `foundation_documentation/project_constitution.md` from the current canonical evidence and current Delphi/PACED baseline.
- [x] Normalize only the top-level authority surfaces touched by this slice so they stop depending on a missing constitution or retired active-lane references.
- [x] Record a repo-specific branch reconciliation matrix for `belluga_now_docker`, `flutter-app`, `laravel-app`, and `foundation_documentation`, classifying relevant unmerged branches without executing cleanup.
- [x] Identify the follow-up slices required for deeper module/TODO sweeps and any later safe branch cleanup execution.
- [x] Audit the current active TODO landscape against the PACED ecosystem reuse doctrine and record which active capabilities are package candidates versus project-local.
- [x] Rebaseline session identity and session-memory governance so this session is explicitly subordinate to this active TODO.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Blocker Notes (Required if `Qualifiers` includes `Blocked`)
- **Blocker:** broader project recalibration is still not closable because the setup doctor reports a separate manual-remediation track for the nginx storage-alias invariant, and the branch matrix now requires explicit follow-up decisions before cleanup work starts.
- **Why blocked now:** this authority-restoration slice is locally implemented, but the repo cannot yet be treated as fully normalized and cleanup-ready.
- **What unblocks it:** a separate Operational/DevOps remediation decision for the storage-alias invariant plus explicit follow-up decisions on the `integrate` / `preserve-for-later` branches recorded in the branch matrix.
- **Owner / source:** Operational/DevOps follow-up from `project_recalibration_doctor.sh` plus user/reviewer decisions on the branch matrix.
- **Last confirmed truth:** `project_constitution.md` now exists; top-level authority docs are updated; `flutter-app` still has real blocking rollback/release-history branches; `foundation_documentation` still has relevant unmerged doc branches against `origin/main`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `foundation_documentation:feat/tenant-admin-domain-management`
- **Promotion lane path:** `repo-specific: root/flutter/laravel use dev -> stage; foundation_documentation uses main`
- **Lane-promoted threshold for this TODO:** `repo-specific`
- **Production-ready threshold for this TODO:** `repo-specific`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Constitution + touched authority docs | `feat/tenant-admin-domain-management@working-tree` | `n/a` | `n/a` | `pending` | `local-implemented` |
| Branch reconciliation matrix | `feat/tenant-admin-domain-management@working-tree` | `n/a` | `n/a` | `pending` | `local-implemented` |

## Out of Scope
- [ ] Do not run safe local cleanup, remote deletion, or repo rebaseline execution in this TODO.
- [ ] Do not absorb a full module-by-module stale-reference sweep into this slice.
- [ ] Do not change Flutter/Laravel product/runtime behavior.
- [ ] Do not silently fix the nginx storage-alias invariant unless that Operational/DevOps track is explicitly folded into this TODO.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** authoring the constitution, updating touched top-level authority docs, recording branch evidence, and making small supporting edits needed to keep those specific docs internally coherent.
- **Must update or split the TODO:** broader module/TODO legacy-reference sweeps, branch cleanup execution, runtime/CI repairs, or any code-path change in Flutter/Laravel/Docker.

## Definition of Done
- [x] `foundation_documentation/project_constitution.md` exists and reflects the current project-level authority model, repo map, cross-module rules, systemic invariants, and strategic framing.
- [x] Touched authority docs no longer rely on a missing constitution or retired active-lane references.
- [x] A branch reconciliation matrix exists with repo-specific merge targets and explicit recommendations for the current unmerged branches.
- [x] Follow-up work that does not fit this slice is captured as separate TODO recommendations instead of being left implicit.
- [x] `project_constitution.md` includes the `Ecosystem Alignment & Reuse Doctrine` section with current reuse candidates.
- [x] This TODO includes an `Ecosystem Impact Analysis` section that classifies the current slice and records adjacent package-candidate lanes.
- [x] The active session is traceably linked to this TODO and stale aggregate session-memory redundancy is reduced to a pointer-only role.

## Validation Steps
- [x] `test -f foundation_documentation/project_constitution.md`
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "todos/active/(mvp_slices|mvp_closure|pre_mvp_)" foundation_documentation/README.md foundation_documentation/system_roadmap.md foundation_documentation/submodule_flutter-app_summary.md foundation_documentation/submodule_laravel-app_summary.md`
- [x] Re-run the repo-specific branch evidence commands used for this TODO and confirm the recorded matrix still matches the current branch state.
- [x] `bash delphi-ai/tools/verify_context.sh`
- [x] `test -f foundation_documentation/sessions/session_20260418-foundation-authority-rebaseline_memory.md`

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `local git remotes / branch truth` | Branch classification depends on current remote state and merge targets. | `healthy` | `2026-04-18` | `branch_rebaseline_preflight.sh`, `git cherry -v`, and `git branch -r` | Re-run classification commands before delivery if execution is delayed materially. |
| `project setup readiness helper` | The setup doctor currently marks the repo as not fully calibrated. | `degraded` | `2026-04-18` | `project_recalibration_doctor.sh` | Track the storage-alias invariant as a separate manual-remediation front unless explicitly folded into this TODO. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-devops`, `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-devops` | Manual-remediation and later safe branch cleanup remain operational concerns. | `docker/`, root repo branch state, readiness scripts | `planned` |
| `strategic-cto` | `operational-coder` | Any later module-by-module stale-reference sweep should run as a separate operational slice after authority restoration. | `foundation_documentation/modules/*.md`, active TODOs, app repos | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice is cross-stack and strategic, but bounded to authority docs plus branch classification rather than full repository normalization.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why this slice stays local:** branch baselines, constitution wording, local lane governance, session-memory rebasing, and repository-specific reconciliation rules are tied to the Bóora!/Belluga Now project topology and cannot be extracted into a shared package without leaking project authority semantics.
- **Planning consequence:** the reuse doctrine changes how future features are evaluated, but the current authority-and-memory normalization slice remains local documentation/governance work rather than a package candidate.

| Active TODO / Capability | Classification | Reuse Potential | Evidence | Current Target |
| --- | --- | --- | --- | --- |
| `TODO-vnext-foundation-authority-and-branch-reconciliation.md` | `Project-Local` | `low` | Repo-specific branch baselines, project constitution recovery, and session-memory rebasing are local governance surfaces. | `remain in foundation_documentation` |
| `TODO-v1-tenant-admin-domain-management.md` | `Project-Local` | `low` | Tenant-domain admin UI/API behavior is tied to this project's current admin information architecture and tenant posture. | `remain in project modules` |
| `store_release_android/*` + `fast_follow_required/*` release lanes | `Project-Local` | `low` | Release gating, auth/deep-link closure, and store publication work are product/release-specific rather than ecosystem contracts. | `remain in project release lanes` |
| `TODO-vnext-belluga-form-validation-package-hardening-and-publish.md` | `Package candidate` | `high` | TODO already targets a stable reusable validation boundary and possible external/internal publish path. | `shared Flutter package boundary` |
| `TODO-vnext-connections-package.md` | `Package candidate` | `high` | TODO explicitly defines `belluga_connections` as a dedicated Laravel package. | `laravel-app/packages/belluga/belluga_connections` |
| `TODO-v1-ticketing-package-integration.md` | `Package candidate` | `high` | TODO explicitly establishes ticketing as a dedicated Laravel package with capability splits. | `laravel-app/packages/belluga/belluga_ticketing` |
| `TODO-vnext-belluga-media-canonical-image-flow-hardening.md` | `Package candidate` | `medium-high` | TODO hardens all Laravel image flows around `belluga_media` and explicit wrappers. | `laravel-app/packages/belluga/belluga_media` |

## Session Identity & Memory Rebaseline
- **Active session ID:** `20260418-foundation-authority-rebaseline`
- **Session memory artifact:** `foundation_documentation/sessions/session_20260418-foundation-authority-rebaseline_memory.md`
- **TODO linkage status:** `linked via session_memory_manager.py init`
- **Durable transpositions completed:** active-lane continuity remains canonical in `project_constitution.md`; release-lane continuity remains in the specific release TODOs; reuse doctrine decisions are recorded in this TODO and in `project_constitution.md`.
- **Aggregate-memory cleanup:** `foundation_documentation/artifacts/session-memory.md` is reduced to a pointer-only helper and no longer carries stale tactical continuity for unrelated TODOs.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/project_constitution.md` → `2. Authority Model`
  - `foundation_documentation/project_constitution.md` → `3. Ecosystem Alignment & Reuse Doctrine`
  - `foundation_documentation/project_constitution.md` → `5. Cross-Module Rules`
  - `foundation_documentation/project_constitution.md` → `6. Systemic Invariants`
  - `foundation_documentation/project_constitution.md` → `8. Module Map`
  - `foundation_documentation/system_roadmap.md` → documentation integrity / reconciliation framing
- **Module decision consolidation targets (required):**
  - `foundation_documentation/project_constitution.md` sections listed above because this slice is project-level authority work, not a single-module contract change

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` The missing `foundation_documentation/project_constitution.md` will be restored as the project-level authority surface instead of leaving project rules scattered across TODO assumptions and module caveats. (`No Prior Decision`)
- [x] `D-02` The first reconciliation slice is limited to constitution + touched top-level authority docs + branch classification; a full legacy-reference sweep is a follow-up slice. (`No Prior Decision`)
- [x] `D-03` No branch will be force-deleted, rebased, or implicitly dismissed in this TODO; branch outcomes are limited to documented classification and recommendations. (`No Prior Decision`)
- [x] `D-04` Branch baselines remain repo-specific: `origin/dev` for root/flutter/laravel and `origin/main` for `foundation_documentation`. (`No Prior Decision`)
- [x] `D-05` The storage-alias readiness failure is a tracked blocker, not permission to skip authority normalization planning or to silently absorb runtime work into this slice. (`No Prior Decision`)
- [x] `D-06` Under the PACED ecosystem reuse mandate, this authority/session-memory/branch-reconciliation slice is explicitly `Project-Local`, while adjacent package-first TODOs are recorded separately in the reuse audit. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `No Prior Decision` | `project_constitution.md` is absent, so project-level rules are being carried implicitly by roadmap/module/TODO notes. | `Supersede (Intentional)` | Missing file + repeated TODO assumptions referencing the absence |
- | `system_roadmap.md#2.1` | Documentation integrity currently highlights missing web-app summary sync, but not the missing constitution or broader authority drift. | `Supersede (Intentional)` | `foundation_documentation/system_roadmap.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Restore `project_constitution.md` as the authoritative project-level rule surface.
- [x] `D-02` Keep this slice bounded to authority restoration and branch classification.
- [x] `D-03` Branch handling in this slice is documentation and recommendation only.
- [x] `D-04` Use repo-specific merge targets when classifying branch relevance.
- [x] `D-05` Keep the storage-alias readiness failure explicit as a blocker/parallel track.
- [x] `D-06` Keep the ecosystem reuse audit explicit, but classify this slice itself as project-local rather than forcing package extraction.

## Questions To Close
- [ ] Should the nginx storage-alias invariant remediation stay as a separate Operational/DevOps TODO, or do you want it folded into this slice?
- [ ] For branches classified as relevant but partially superseded, should the preferred outcome be `merge/cherry-pick` or `document-and-close` unless code/docs are still missing on the mainline?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing canonical docs provide enough evidence to author the first usable `project_constitution.md` without inventing new product truth. | `project_mandate.md`, `domain_entities.md`, `system_roadmap.md`, scope policy, module docs, and branch/setup audits are present. | Constitution authoring would become speculative and require a separate strategic discovery pass. | `Medium` | `Keep as Assumption` |
| `A-02` | Legacy references to `mvp_slices` / `mvp_closure` in top-level authority docs are drift, not current execution truth. | `foundation_documentation/todos/README.md` explicitly marks those lane names as legacy and no longer part of the active directory model. | The touched docs would need to preserve those references and this slice would narrow further. | `High` | `Promote to Decision` |
| `A-03` | `flutter-app` rollback branches represent real unmerged branch intent that must be classified before cleanup, even if they are not meant to be merged directly. | `branch_rebaseline_preflight.sh` flagged them as blockers and `git cherry -v` shows unique revert commits. | Branch cleanup could proceed more aggressively in a later slice. | `High` | `Keep as Assumption` |
| `A-04` | `foundation_documentation` should be audited against `origin/main`, not `origin/dev`. | `git branch -r` shows `origin/HEAD -> origin/main`; the generic dev-based helper failed there. | Branch relevance in the docs repo would need to be recalculated before delivery. | `High` | `Promote to Decision` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/project_mandate.md`
- `foundation_documentation/domain_entities.md`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/README.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/submodule_laravel-app_summary.md`
- `foundation_documentation/artifacts/branch-reconciliation-matrix-2026-04-18.md`
- `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-foundation-authority-and-branch-reconciliation.md`

### Ordered Steps
1. Freeze the branch and setup evidence that defines the current drift/problem statement.
2. Author `project_constitution.md` from the Delphi template using only current canonical project evidence.
3. Update the touched top-level authority docs and summaries so they reference the restored constitution and current active-lane model.
4. Record a branch reconciliation matrix that captures the currently relevant root/flutter/docs branches and explicit recommendations.
5. Identify the follow-up normalization slices that remain out of scope for this TODO.
6. Run targeted validation commands and prepare delivery notes.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** this slice changes project authority documentation and branch-governance inventory, not runtime behavior.
- **Fail-first target(s) (when required):** `n/a`

### Runtime / Rollout Notes
- `n/a`

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `PLAN-01`
  - **Severity:** `high`
  - **Evidence:** `rg` shows stale lane references across many files, but the user request and this TODO cannot safely absorb a full-repo sweep.
  - **Why it matters now:** mixing authority restoration with a full legacy sweep would create an unbounded TODO and muddy approval.
  - **Option A (Recommended):** restore constitution + touched authority docs now, then open follow-up slices for deeper module/TODO cleanup.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** try to normalize all stale references found by search in the same slice.
    - **Effort:** `high`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `mixed`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep relying on missing-constitution caveats and ad hoc TODO references.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

- **Issue ID:** `PLAN-02`
  - **Severity:** `medium`
  - **Evidence:** the generic branch preflight helper works for repos anchored on `origin/dev`, but `foundation_documentation` uses `origin/main`.
  - **Why it matters now:** a single merge-target assumption would misclassify branch relevance and could lead to wrong cleanup recommendations.
  - **Option A (Recommended):** keep branch classification repo-specific and record the different baselines explicitly in the reconciliation matrix.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** force the docs repo into a dev-style audit anyway.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** skip branch classification for `foundation_documentation`.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `mixed`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] Restore a constitution that duplicates module contracts instead of capturing project-level rules only.
- [ ] Normalize docs against the wrong active-lane model and introduce new stale references.
- [ ] Treat rollback branches as cleanup candidates before their intent is classified.

### Residual Unknowns / Risks
- [ ] Some old branch content may already be partly represented on current mainline branches, requiring judgment between merge, cherry-pick, or documentation-only carry-forward.
- [ ] The storage-alias readiness blocker may still need a separate TODO before the repo can be declared fully recalibrated.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `n/a`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` |

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `not_needed`
- **Why this decision:** the slice is bounded documentation/governance work with explicit evidence and does not yet cross into runtime implementation.
- **Impact signals in scope:** `none`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `n/a`
- **Critique isolation mode:** `n/a`
- **Subagent mandate (when available):** `no`
- **Canonical multi-lane audit protocol (when required):** `n/a`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `n/a`
- **Critique status:** `not_run`
- **Findings summary:** `none`
- **Evidence / reference:** `n/a`
- **Waiver authority / reference (required if waived):** `n/a`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `wf-docker-delphi-project-setup-method` | This is a downstream project recalibration task. | Explicit drift visibility and safe handoff into normal work. | Treating setup drift as implicit background noise. | Keep blocker recording explicit while executing the slice. |
| `wf-docker-feature-framing-method` | The original request was medium/big and ambiguous. | Bounded story slicing before TODO execution. | Collapsing all reconciliation work into one unbounded contract. | Keep follow-up sweeps separate from this first slice. |
| `wf-docker-todo-driven-execution-method` | Project-doc changes require a tactical TODO and `APROVADO`. | No implementation before approval and frozen decisions. | Ad hoc documentation edits. | Request approval before touching canonical authority docs. |
| `branch-rebaseline-preflight` | Branch cleanup/rebaseline decisions must not hide unmerged work. | Repo-specific blocker reporting and safe cleanup boundaries. | Blind cleanup or single-baseline branch assumptions. | Limit this slice to branch classification only. |

## Decision Adherence Validation (Mandatory Before Delivery)
- | Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
- | --- | --- | --- | --- |
- | `D-01` | `Adherent` | `foundation_documentation/project_constitution.md` | Project-level authority is now restored in a dedicated canonical file. |
- | `D-02` | `Adherent` | `foundation_documentation/project_constitution.md`, `foundation_documentation/README.md`, `foundation_documentation/system_roadmap.md`, `foundation_documentation/submodule_flutter-app_summary.md`, `foundation_documentation/submodule_laravel-app_summary.md` | Only the bounded top-level authority surfaces were updated; no broad module/TODO sweep was absorbed. |
- | `D-03` | `Adherent` | `foundation_documentation/artifacts/branch-reconciliation-matrix-2026-04-18.md` | Branch handling stayed at the classification/recommendation level only. |
- | `D-04` | `Adherent` | `foundation_documentation/project_constitution.md`, `foundation_documentation/system_roadmap.md`, `foundation_documentation/artifacts/branch-reconciliation-matrix-2026-04-18.md` | Repo-specific merge-target baselines are now explicit in canonical/supporting docs. |
- | `D-05` | `Adherent` | `foundation_documentation/artifacts/tmp/project-setup-report.txt`, this TODO `Blocker Notes` + `External Dependency Readiness` | The storage-alias readiness failure remains explicit and out of scope for silent absorption. |
- | `D-06` | `Adherent` | This TODO `Ecosystem Impact Analysis`, `foundation_documentation/project_constitution.md#3-ecosystem-alignment--reuse-doctrine` | Reuse doctrine is now explicit without misclassifying this local governance slice as a package candidate. |

## Module Decision Consistency Validation (1-1 Mandatory Before Delivery)
- | Module Decision Ref | Planned Handling | Delivery Status (`Preserved|Superseded (Approved)|Regression`) | Evidence | Notes |
- | --- | --- | --- | --- | --- |
- | `No Prior Decision` | `Supersede (Intentional)` | `Superseded (Approved)` | `foundation_documentation/project_constitution.md` | The missing constitution gap is now replaced by an explicit project-level authority surface. |
- | `system_roadmap.md#2.1` | `Supersede (Intentional)` | `Superseded (Approved)` | `foundation_documentation/system_roadmap.md` | Documentation integrity framing now reflects constitution authority, current lane model, and repo-specific branch baselines. |

## Security Risk Assessment (Mandatory Before Delivery)
- **Risk level:** `low`
- **Why this risk level:** the slice changes documentation and branch-governance inventory only.
- **Attack surface in scope:** `repository governance / documentation`
- **Attack simulation decision:** `not_needed`
- **Review evidence:** `n/a`
- **Residual security risk:** `none`

## Performance & Concurrency Risk Assessment (Mandatory Before Delivery)
- **Policy schema version:** `pcv-1`
- **Global sensitivity level:** `none`
- **Why this level:** no runtime path, query path, or async execution behavior changes are planned in this slice.
- **Current delivery stage at review time:** `Local-Implemented`

| Lane ID | Lane | Trigger Result | Trigger Severity | Trigger Reason Code | Gate Deadline | Minimum Evidence Rule | State | Residual Risk | Uncertainty Reason Code |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `endpoint-performance-scrutiny` | `not_needed` | `low` | `EPS-DATA-PATH-CHANGED` | `before_local_implemented` | `EPS-E1` | `not_applicable` | `none` | `none` |
| `FRC` | `frontend-race-condition-validation` | `not_needed` | `low` | `FRC-LIFECYCLE-ASYNC-EFFECT` | `before_local_implemented` | `FRC-POLICY` | `not_applicable` | `none` | `none` |
| `BCI` | `backend-concurrency-idempotency-validation` | `not_needed` | `low` | `BCI-EXACT-ONCE-SEMANTICS` | `before_local_implemented` | `BCI-POLICY` | `not_applicable` | `none` | `none` |
| `RLS` | `runtime-load-stress-validation` | `not_needed` | `low` | `RLS-CACHE-INDEX-SENSITIVE-PATH-CHANGED` | `before_local_implemented` | `RLS-E1` | `not_applicable` | `none` | `none` |
