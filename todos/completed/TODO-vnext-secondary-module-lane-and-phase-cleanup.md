# Title
Secondary Module Lane And Phase Cleanup

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Note
- **Closed on:** `2026-04-18`
- **Closure reason:** the secondary-module cleanup objective was materially delivered during the foundation authority reconciliation on `docs/foundation-authority-reconciliation`; this file remains as the historical record for that sub-slice.

## Context
The main authority package, module-family rename, and current-state alignment for the primary implementation-steering modules are now checkpointed. The remaining drift identified by the latest review sits in secondary modules that still point at retired lane folders (`mvp_slices`), non-existent evidence paths (`concluded_but_active`), or old roadmap framing (`FCX-02`, `Phase N`) as if those were still current authority surfaces.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-04C`
- **Why this is the right current slice:** these secondary docs are not the top authority surfaces for the current store-delivery TODOs, but they can still reintroduce stale assumptions during follow-up work if their lane/evidence framing is not normalized now.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb targeted wording/path updates in the selected secondary modules when those updates retire stale lane/phase/evidence references without redesigning the underlying module contracts.
- If execution reveals broader contract redesign or new module-boundary disputes, split that into another TODO instead of inflating this cleanup slice.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Docs-Validated`
- **Next exact step:** checkpoint and push this secondary-module cleanup slice, then move the reconciliation audit to the next authority front.

## Scope
- [x] Update `task_and_reminder_module.md` so tactical links and roadmap framing stop depending on `mvp_slices` and `Phase N` wording.
- [x] Update `onboarding_flow_module.md` and `invite_and_social_loop_module.md` so invite-implementation references point to the completed/current authority path.
- [x] Update `agenda_and_action_planner_module.md` and `tenant_home_composer_module.md` so roadmap sections no longer present old `FCX-0x` / `Phase N` framing as current authority.
- [x] Replace any selected stale evidence links that point to retired or non-existent active paths (for example `concluded_but_active`) with current canonical evidence paths.
- [x] Keep product/runtime behavior, public aliases, and deeper contract redesign out of scope.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `foundation_documentation:docs/foundation-authority-reconciliation`
- **Promotion lane path:** `main`
- **Lane-promoted threshold for this TODO:** `main`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Secondary module lane/phase cleanup | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not redesign module boundaries or runtime ownership in this slice.
- [ ] Do not rename public routes or revisit `/parceiro/:slug`.
- [ ] Do not perform Flutter/Laravel code changes.
- [ ] Do not repo-wide replace every historical `concluded_but_active` or `Phase N` reference; keep the cleanup bounded to the selected module authority surfaces.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** selected module anchor-path corrections, roadmap wording cleanup, and small local wording changes needed so deferred capabilities are clearly marked as deferred rather than phase-era current plans.
- **Must update or split the TODO:** broad artifact-history cleanup, route/product policy changes, or code/runtime changes.

## Definition of Done
- [x] The selected secondary modules no longer point to retired `mvp_slices` paths or stale/non-existent active evidence paths.
- [x] The selected roadmap sections no longer depend on `FCX-0x` / `Phase N` framing as if it were current authority.
- [x] Deferred capabilities mentioned in touched modules remain documented, but are framed as deferred continuation rather than obsolete phase-era commitments.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "active/mvp_slices|Phase [0-9]+|concluded_but_active" foundation_documentation/modules/task_and_reminder_module.md foundation_documentation/modules/onboarding_flow_module.md foundation_documentation/modules/invite_and_social_loop_module.md foundation_documentation/modules/agenda_and_action_planner_module.md foundation_documentation/modules/tenant_home_composer_module.md foundation_documentation/modules/flutter_client_experience_module.md`
- [x] `rg -n "FCX-0[12]" foundation_documentation/modules/task_and_reminder_module.md foundation_documentation/modules/invite_and_social_loop_module.md foundation_documentation/modules/agenda_and_action_planner_module.md foundation_documentation/modules/tenant_home_composer_module.md`
- [x] Manual readback confirms the touched modules still make semantic sense after removing the stale lane/phase framing.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | This is a bounded documentation cleanup slice over selected module authority surfaces. | selected module docs only | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice is documentation-only and targets selected stale lane/phase/evidence references without reopening larger authority decisions.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice cleans up Belluga Now/Bóora! documentation authority surfaces only. The stale lane/evidence paths are local project residue, not a reusable package boundary.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/task_and_reminder_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/project_constitution.md`
- **Planned decision promotion targets (module sections):**
  - touched tactical anchor / roadmap / decision evidence sections only
- **Module decision consolidation targets (required):**
  - the touched modules themselves; do not promote to constitution unless a real project-level rule is uncovered

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` This slice may replace stale evidence links with completed/current canonical evidence when the previous active path no longer exists. (`No Prior Decision`)
- [x] `D-02` Old phase-era roadmap bullets may be rewritten into current/deferred posture summaries when the underlying capability intent remains valid but the original phase numbering is no longer authoritative. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `project_constitution.md §6` | Active tactical lanes are `store_release_android`, `fast_follow_required`, and `vnext`; historical lane names must not be reintroduced into new authority surfaces. | `Preserve` | `foundation_documentation/project_constitution.md` |
- | current module docs | Deferred capabilities may stay documented, but they must not be framed as obsolete phase-era authority. | `Preserve` | touched modules in this slice |

## Decision Baseline (Frozen Before Implementation)
- [x] This slice is about stale lane/phase/evidence cleanup only.
- [x] Completed TODO paths must replace stale active-lane paths when the completed TODO is already the real canonical evidence.
- [x] Removing obsolete phase numbering must not erase legitimate deferred capability intent.

## Questions To Close
- [x] Which stale references are local enough to fix now without opening a broader redesign front?
  The currently confirmed local fixes are `mvp_slices` invite-TODO links in three modules, phase-era roadmap wording in four modules, and a stale `concluded_but_active` evidence path in `agenda_and_action_planner_module.md` plus adjacent `flutter_client_experience_module.md`.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The selected stale references can be cleaned up without changing any module's actual runtime contract. | The identified drifts are path/roadmap/evidence wording, not contract mismatches. | The slice must stop and split before redesigning contracts. | `High` | `Keep as Assumption` |
| `A-02` | The completed invite TODO is now the correct authority reference for invite-implementation evidence in the selected modules. | The completed TODO exists and the old `mvp_slices` path is retired. | Another canonical evidence surface must be chosen before editing links. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/task_and_reminder_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- this TODO file

### Ordered Steps
1. Freeze the bounded cleanup scope.
2. Update stale tactical paths and roadmap/evidence wording in the selected modules.
3. Re-read the touched sections for semantic coherence.
4. Validate with `diff --check`, targeted `rg`, and manual readback.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation cleanup only
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
