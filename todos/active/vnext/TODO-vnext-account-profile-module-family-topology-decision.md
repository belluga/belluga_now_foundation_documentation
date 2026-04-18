# Title
Account Profile Module Family Topology Decision

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The project now has a reconciled top-level authority baseline and a canonical domain-entity model that no longer treats `partner` as a current internal root noun. The remaining ambiguity is module-level: the current `partner_catalog_and_offer_module.md`, `partner_admin_module.md`, and `partner_analytics_module.md` family still encodes older vocabulary and unclear future boundaries across public account-profile/static-asset surfaces, account workspace/admin concerns, and analytics.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/account-profile-module-family-reconciliation.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** the next high-value step is to decide the future module-family topology and canonical names before attempting broad renames, module merges/splits, or code-symbol cleanup.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb local evidence gathering and small supporting doc edits needed to express the topology decision clearly, but it must not silently absorb the later rename/restructure execution slice.
- If execution reveals that deciding topology already requires broad module rewrites or public route/product-copy decisions, stop and split the follow-up rather than inflating this TODO.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** audit the current `partner_*` module family and map each file to one future handling (`preserve/rename/merge/split/retire`).

## Scope
- [ ] Decide the canonical future names and roles for the current `partner_catalog_and_offer_module.md`, `partner_admin_module.md`, and `partner_analytics_module.md` family.
- [ ] Decide whether each current module should be preserved, renamed, merged, split, or retired.
- [ ] Record how this future module family relates to `tenant_admin`, `account_workspace`, public account-profile/static-asset surfaces, and analytics ownership.
- [ ] Leave route-path/public-copy alias decisions and execution-level renames to dedicated follow-up slices.

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
| Module-family topology decision | `docs/foundation-authority-reconciliation@<pending>` | `n/a` | `n/a` | `<pending>` | `pending` |

## Out of Scope
- [ ] Do not execute broad module-file renames in this TODO.
- [ ] Do not decide public path/product-copy changes such as the future of `/parceiro/:slug` here.
- [ ] Do not normalize Flutter/Laravel code symbols in this TODO.
- [ ] Do not reopen the canonical domain-entity model already established in `domain_entities.md`.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** decision tables, handling classifications, and small supporting wording updates needed to express the future module topology clearly.
- **Must update or split the TODO:** file renames, broad cross-reference rewrites, route/copy redesign, or runtime/code migrations.

## Definition of Done
- [ ] The current `partner_*` module family is mapped to an explicit future handling for each file (`preserve/rename/merge/split/retire`).
- [ ] The project has a documented canonical future module-family direction using current internal nouns (`Account`, `Account Profile`, `Profile Type`, `Static Asset`, `Account Workspace`, `Account Profile Analytics`) where applicable.
- [ ] Follow-up execution slices are explicit for rename/restructure work that remains after the decision.

## Validation Steps
- [ ] `git -C foundation_documentation diff --check`
- [ ] `rg -n "partner_catalog_and_offer_module|partner_admin_module|partner_analytics_module|account_workspace" foundation_documentation/project_constitution.md foundation_documentation/modules/*.md foundation_documentation/todos/active/vnext/TODO-vnext-partner-terminology-retirement-and-account-profile-language-normalization.md`
- [ ] Decision output is internally coherent with `domain_entities.md`, `project_constitution.md`, and the selected module docs.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | Rename/restructure execution should happen only after this topology decision is frozen. | module docs, cross-references, possible later code/docs rename fronts | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice is documentation-only, but it is a cross-module semantic decision that can easily sprawl without explicit boundaries.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this topology decision is specific to the current Belluga Now/Bóora! documentation and module family. It may later inform broader naming guidance, but the concrete module map and legacy file family are downstream project concerns.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/partner_catalog_and_offer_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/partner_admin_module.md`
  - `foundation_documentation/modules/partner_analytics_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/project_constitution.md`
- **Planned decision promotion targets (module sections):**
  - touched module overview / canonical-anchor sections
  - `project_constitution.md` module map, if the topology decision becomes stable enough to promote
- **Module decision consolidation targets (required):**
  - the active TODO first; later promotion to touched module docs and constitution after approval/freeze

## Decision Pending (Resolve Before Freeze)
- [ ] `D-01` Should `partner_catalog_and_offer_module` remain one future module, or split into narrower account-profile/static-asset vs offer/transaction concerns?
- [ ] `D-02` Should `partner_admin_module` survive as a separate future module, or fold into `account_workspace` and/or `tenant_admin`?
- [ ] `D-03` Should `partner_analytics_module` survive as its own module, or become an analytics concern subordinate to workspace/admin flows?

## Decisions (Resolved Before Freeze)
- [ ] `none`

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `feature-brief ST-01` | The future topology and naming of the `partner_*` module family is still unresolved. | `Supersede (Intentional)` | `foundation_documentation/artifacts/feature-briefs/account-profile-module-family-reconciliation.md` |

## Decision Baseline (Frozen Before Implementation)
- [ ] Future module-family topology must be decided before broad rename/restructure execution begins.

## Questions To Close
- [ ] Which future module boundaries are semantically real, and which current module files are only historical placeholders?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current `partner_*` family is the correct place to start module reconciliation because it concentrates the largest remaining internal vocabulary/topology drift. | `TODO-vnext-partner-terminology-retirement...`, `project_constitution.md`, and the current module docs all point to this family. | The slice should be re-scoped before decisions are made. | `High` | `Keep as Assumption` |
| `A-02` | Public route alias decisions can remain separate from the module-family decision. | Existing VNext terminology TODO already separates route/copy permanence from internal language cleanup. | The TODO may need to split or block. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/partner_catalog_and_offer_module.md`
- `foundation_documentation/modules/partner_admin_module.md`
- `foundation_documentation/modules/partner_analytics_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-partner-terminology-retirement-and-account-profile-language-normalization.md`
- this TODO file

### Ordered Steps
1. Audit the current `partner_*` family and immediate dependencies against `domain_entities.md` and `project_constitution.md`.
2. Build one explicit handling table for each current module (`preserve/rename/merge/split/retire`).
3. Freeze the topology decision in this TODO before any rename/restructure execution is opened.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation topology decision only
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
