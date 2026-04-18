# Title
Module Authority Follow-Up Sweep

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The first authority-reconciliation slice restored `project_constitution.md`, aligned the top-level authority docs, and classified the current branch topology. That slice explicitly stayed bounded and deferred the broader module/TODO cleanup. The next required step is a bounded follow-up sweep across the module docs and nearby summary surfaces that still carry stale lane references, missing-authority assumptions, or terminology/topology drift relative to the now-reconciled top-level authority.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-04`
- **Why this is the right current slice:** the top-level authority package is now checkpointed, so the next highest-value work is to remove module-level drift that would otherwise reintroduce stale assumptions during future implementation.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation.
- If execution reveals a second independent cleanup front large enough to justify a separate approval/risk conversation, split it into another TODO instead of inflating this one.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** checkpoint this bounded module sweep and keep the broader account-profile module-family decision as a separate future slice.

## Scope
- [x] Sweep the selected module docs and nearby summary surfaces that most directly depend on the restored top-level authority.
- [x] Remove stale active-lane references and "constitution missing" style drift in the touched files.
- [x] Align touched module wording to the reconciled canonical model without forcing broad legacy filename/module renames in the same slice.
- [x] Capture any larger unresolved cleanup front as a separate follow-up TODO instead of leaving it implicit.

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
| Selected module/summaries authority sweep | `docs/foundation-authority-reconciliation@<checkpoint-pending>` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not perform broad legacy module-file renames in this slice.
- [ ] Do not reopen route-path/product-copy decisions that are already deferred to dedicated VNext TODOs.
- [ ] Do not absorb Flutter/Laravel runtime behavior changes into this documentation sweep.
- [ ] Do not sweep every completed TODO or every module in one pass if the drift is not directly tied to current authority use.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** targeted edits across selected module docs, summaries, and local cross-references needed to keep the touched authority surfaces coherent.
- **Must update or split the TODO:** broad repository-wide terminology retirement, route-contract redesign, or code/runtime changes outside documentation authority surfaces.

## Definition of Done
- [x] Selected touched module docs and summary surfaces no longer rely on retired active-lane structures or missing-authority assumptions.
- [x] Touched docs use wording consistent with the restored top-level authority and current canonical model.
- [x] Remaining larger cleanup fronts are named explicitly as follow-up TODOs when they do not fit this bounded slice.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "constitution missing|mvp_slices|mvp_closure|pre_mvp_|todos/active/(mvp_slices|mvp_closure|pre_mvp_)" foundation_documentation/modules foundation_documentation/submodule_flutter-app_summary.md foundation_documentation/submodule_laravel-app_summary.md`
- [x] Targeted `rg` on touched files confirms no newly reintroduced stale lane/path references.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | If the bounded sweep resolves into isolated low-risk doc edits only, execution can continue as an operational documentation pass. | `foundation_documentation/modules/*.md`, summary docs | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** this slice is documentation-only and intentionally limited to selected module/summaries surfaces rather than a repo-wide cleanup.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this sweep reconciles Bóora!/Belluga Now project authority surfaces after the constitution restoration. It does not define a reusable package boundary by itself.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- **Planned decision promotion targets (module sections):**
  - touched module overview / canonical-anchor / ledger sections as needed
- **Module decision consolidation targets (required):**
  - the touched module docs themselves; if project-level rules emerge, promote them separately instead of hiding them here

## Decision Pending (Resolve Before Freeze)
- [x] `D-01` Whether this slice should stop at stale lane/authority cleanup only, or also normalize the most local terminology/topology drift in the same touched files when that drift is directly blocking coherence.

## Decisions (Resolved Before Freeze)
- [x] `D-01` This slice may normalize terminology/topology wording only when it is local to the touched module/summaries and does not require a broad module rename or route-contract redesign. This was used to update selected module/summaries wording while leaving filename-level legacy renames, public route aliases, and larger terminology retirement to dedicated VNext follow-up. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `feature-brief ST-04` | Module- and TODO-level stale references require a dedicated follow-up slice after project authority restoration. | `Preserve` | `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Keep this slice bounded to selected authority-relevant module/summaries cleanup.

## Questions To Close
- [x] Which touched files still act as practical entry points for future implementation and therefore deserve priority in this first module sweep?
  Priority set used in this slice: `account_profile_catalog_module.md`, `tenant_admin_module.md`, and the submodule summaries most likely to steer future implementation context.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The highest-value next cleanup is in modules/summaries directly referenced by the restored top-level authority. | `project_constitution.md` module map + earlier authority review loop | The slice should be re-scoped before edits begin. | `High` | `Keep as Assumption` |
| `A-02` | Broad legacy renames remain too large for this slice and should stay deferred. | Existing VNext partner-terminology TODO + prior bounded-slice decision | This TODO would need to split or stop. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/submodule_flutter-app_summary.md`
- `foundation_documentation/submodule_laravel-app_summary.md`
- this TODO file

### Ordered Steps
1. Audit the selected files for stale lane references, missing-authority assumptions, and localized terminology/topology drift.
2. Edit only the files that are directly implicated by that audit.
3. Re-run targeted validation and classify anything broader as a separate follow-up front.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation authority sweep only
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
