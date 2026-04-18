# Title
VNext Backlog Role and Ownership Consolidation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The active `vnext/` lane is intentionally large, but several files still blur different kinds of authority: primary deferred program owners, support registries, historical scope-freeze notes, parking-lot capture, and temporary reconciliation sub-slices. The previous TODO-landscape audit froze that finding at the lane level. This slice applies only the safe follow-through inside the touched `vnext` TODOs themselves so readers stop mistaking supporting notes for parallel program owners.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** the ambiguity is now narrow and documentary: selected active TODOs need explicit role notes and ownership wording cleanup, not product replanning.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the work is fully bounded to active `vnext` TODO authority wording inside `foundation_documentation/todos/**`.

## Contract Boundary
- This TODO defines **WHAT** must be clarified in the touched `vnext` TODOs and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb additional touched-`vnext` ownership-note cleanup only when the overlap is explicit and no module/artifact/policy rewrite is required.
- If the work would require reprioritizing deferred product scope, moving active TODOs to `completed/`, or rewriting many unrelated TODOs into the newest template, stop and split follow-up work instead.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Docs-Validated`
- **Next exact step:** checkpoint the touched `vnext` ownership clarifications on `docs/foundation-authority-reconciliation`, then treat any broader regrouping or merge/delete action as separate follow-up work.

## Scope
- [x] Clarify which touched `vnext` TODOs are primary deferred owners versus supporting/non-owner surfaces.
- [x] Remove duplicate-owner wording where a touched TODO already defers to a more specific active owner.
- [x] Refresh touched reconciliation-owner wording so the foundation reconciliation cluster reads as one umbrella owner with subordinate sub-slices.
- [x] Keep all edits inside `foundation_documentation/todos/**`.

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
| Touched `vnext` ownership clarification | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not reprioritize deferred product/program scope.
- [ ] Do not merge or delete active TODO files solely to reduce file count.
- [ ] Do not rewrite the whole `vnext/` lane into the newest template.
- [ ] Do not change module docs, roadmap docs, or non-TODO artifacts in this slice.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** role notes, authority-note cleanup, duplicate-owner wording cleanup, and branch/evidence wording refresh in touched `vnext` TODOs.
- **Must update or split the TODO:** lane-wide archival/move actions, product reprioritization, or changes outside `foundation_documentation/todos/**`.

## Definition of Done
- [x] The touched `vnext` TODOs no longer imply duplicate primary ownership where a dedicated owner already exists.
- [x] Support/backlog/parking-lot surfaces explicitly read as non-owner or subordinate surfaces.
- [x] The foundation-reconciliation cluster reads as one umbrella owner plus subordinate reconciliation sub-slices.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "Authority note|Role note|scope-freeze|support registry|umbrella owner|primary deferred owner" foundation_documentation/todos/active/vnext`
- [x] Manual readback confirms the touched TODOs now express the intended ownership boundary.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | This is a bounded TODO-authority clarification slice inside `foundation_documentation/todos/**`. | `foundation_documentation/todos/active/vnext/**` | `executed; touched surfaces remained inside vnext TODO files only` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** ownership wording cleanup only inside active `vnext` TODO files

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice clarifies Belluga Now backlog authority only; it does not alter reusable package boundaries.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/todos/README.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/todos/active/vnext/TODO-vnext-foundation-authority-and-branch-reconciliation.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-test-hardening-program.md`
  - `foundation_documentation/todos/completed/TODO-v1-canonical-back-navigation-governance-cutover.md`
- **Planned decision promotion targets (module sections):**
  - touched TODOs only in this slice
- **Module decision consolidation targets (required):**
  - the touched `vnext` TODO files themselves, because this slice changes tactical authority wording only

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` When a touched `vnext` file is only support or historical context, it must not read like a parallel primary owner. (`No Prior Decision`)
- [x] `D-02` The foundation-reconciliation cluster should read as one umbrella owner with subordinate reconciliation sub-slices, not as unrelated equal-priority programs. (`No Prior Decision`)
- [x] `D-03` One-off regression TODOs may coexist with broader governance owners only when the local TODO explicitly defers policy ownership to the broader owner. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `todos/README.md#5) VNext Hygiene` | `vnext` should distinguish `program owner`, `support registry`, `reconciliation sub-slice`, and `parking lot`. | `Preserve` | TODO guide |
- | `TODO-vnext-account-workspace.md` authority note | Account Workspace is already the single deferred owner for authenticated workspace delivery. | `Preserve` | active TODO |

## Decision Baseline (Frozen Before Implementation)
- [x] This slice is about role clarity, not scope reprioritization.
- [x] Touched support notes must defer visibly to dedicated owners when those owners already exist.
- [x] Reconciliation child slices may remain active, but they should read as subordinate sub-slices rather than parallel long-lived programs.

## Questions To Close
- [x] `none`

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The main ownership ambiguity can be reduced with wording changes only in the touched TODOs. | Current overlap is mostly in authority notes and duplicated-owner language, not missing product decisions. | Split a separate regrouping/archival slice if structural moves become necessary. | `High` | `Keep as Assumption` |
| `A-02` | The touched TODO set is enough to clarify the most misleading current overlaps. | The clearest overlaps are already visible in account/admin area, test-hardening, back-navigation, parking-lot, and reconciliation fronts. | Open additional follow-up slices rather than inflating this one. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/active/vnext/TODO-vnext-backlog-role-and-ownership-consolidation.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-foundation-authority-and-branch-reconciliation.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-test-hardening-program.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-test-hardening-defect-backlog.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-parking-lot.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/completed/TODO-v1-canonical-back-navigation-governance-cutover.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-location-permission-back-button-fix.md`

### Ordered Steps
1. Add explicit role/authority notes to the touched owner/support/non-owner TODOs.
2. Remove or soften duplicate-owner wording where a dedicated owner already exists.
3. Refresh the touched reconciliation umbrella TODO so its branch/evidence wording matches the current docs branch.
4. Validate the diff and readback for ownership clarity.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** tactical TODO wording cleanup only
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
