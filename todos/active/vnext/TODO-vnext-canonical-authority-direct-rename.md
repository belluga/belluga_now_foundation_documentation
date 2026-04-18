# Title
Canonical Authority Direct Rename

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The module-family topology decision is now frozen: `partner_admin` is the future `account_workspace`, `partner_catalog_and_offer` remains the real public account-profile catalog authority, and `partner_analytics` is capability-first by default. The remaining problem is documentation topology drift: the legacy file names still leak obsolete authority language even where the canonical successor is already clear.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/account-profile-module-family-reconciliation.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the topology decision is already frozen, so the next step is to move the canonical documentation surfaces to their new names instead of continuing to preserve legacy file names in active authority.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb the file renames, internal title/authority-note updates, and reference rewrites required to keep the renamed authority surfaces coherent.
- If execution reveals a broader documentation taxonomy change (for example introducing a new `capabilities/` tree), split that into another TODO instead of expanding this one.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** perform the final consistency readback, then checkpoint the slice.

## Scope
- [x] Rename `partner_admin_module.md` directly to `account_workspace_module.md`.
- [x] Rename `partner_catalog_and_offer_module.md` directly to `account_profile_catalog_module.md`.
- [x] Rename `partner_analytics_module.md` directly to `account_profile_analytics_capability.md`.
- [x] Update current authority docs, active TODOs, and reference links so they point to the new canonical file names.
- [x] Preserve the already-frozen semantic rules:
  - `account_workspace` is a real future authority front,
  - `offer/commercial` remains capability-first by default,
  - `analytics` remains capability-first by default.

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
| Canonical authority direct rename | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not change public product routes/copy such as `/parceiro/:slug` here.
- [ ] Do not perform code symbol/runtime implementation renames in Flutter or Laravel here.
- [ ] Do not introduce a new documentation tree/taxonomy for capabilities in this slice.
- [ ] Do not reopen the capability-first rule or the `partner_admin -> account_workspace` decision.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** direct file renames, path/reference rewrites, title/authority-note updates, and small wording corrections needed to keep renamed docs coherent.
- **Must update or split the TODO:** route policy redesign, implementation/code renames, or a broader doc taxonomy migration beyond the renamed files and their references.

## Definition of Done
- [x] The three legacy authority files are renamed to their canonical successor names.
- [x] Current authority docs and other active TODOs no longer depend on the old `partner_*` file names.
- [x] The renamed docs still express the frozen topology rules correctly.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "partner_admin_module\\.md|partner_analytics_module\\.md|partner_catalog_and_offer_module\\.md" foundation_documentation/project_constitution.md foundation_documentation/modules foundation_documentation/todos/active foundation_documentation/artifacts/feature-briefs -g '!foundation_documentation/todos/active/vnext/TODO-vnext-canonical-authority-direct-rename.md'`
- [x] `ls foundation_documentation/modules`
- [x] Manual readback of the renamed docs confirms `account_workspace` stays a module/front and `offer`/`analytics` stay capability-first by default.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | Direct documentation renames and path rewrites are a bounded operational execution slice after the topology decision freeze. | module docs, constitution, active TODOs, feature brief | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice is documentation-only, but it renames multiple canonical files and requires broad reference rewrites without breaking authority continuity.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this rename normalizes Bóora!/Belluga Now documentation authority surfaces. The naming may inform broader ecosystem language later, but the concrete file names and successor docs are downstream project concerns.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/account_workspace_module.md`
  - `foundation_documentation/modules/account_profile_analytics_capability.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/project_constitution.md`
- **Planned decision promotion targets (module sections):**
  - constitution module map / major-modules section
  - renamed module/capability docs themselves
- **Module decision consolidation targets (required):**
  - this TODO first; then the renamed authority files and constitution references

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Keep the renamed analytics planning file in `foundation_documentation/modules/` for now with a capability-oriented filename. Do not introduce a broader documentation taxonomy move in this slice. (`No Prior Decision`)
- [x] `D-02` Rewrite completed/ephemeral TODO references to the renamed authority docs when needed to preserve link continuity after the file moves. This does not reopen historical decisions; it only prevents broken references. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `ST-01 topology freeze` | `partner_admin -> account_workspace`; `offer`/`analytics` capability-first by default. | `Preserve` | `foundation_documentation/todos/active/vnext/TODO-vnext-account-profile-module-family-topology-decision.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] Canonical direct rename is allowed when the successor authority is already clear.
- [x] Capability-first planning docs may remain in `modules/` temporarily when a broader taxonomy move is out of scope.
- [x] Historical references may be path-normalized in the same slice when required to preserve renamed-file continuity.

## Questions To Close
- [x] Which renamed references must stay historical only, and which should move immediately to the new file names to preserve live authority continuity?
  Live authority links and active reference paths move immediately to the canonical filenames. Historical framing may still name the retired `partner_*` fronts when it is clarifying decision history, but it should not point readers to obsolete live authority files.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Renaming the three files now is less risky than preserving legacy names for another slice because their successor semantics are already frozen. | ST-01 decision + user direction to rename directly. | The slice should stop and narrow to fewer files. | `High` | `Keep as Assumption` |
| `A-02` | Updating completed TODO references in the same slice is acceptable if needed to prevent broken links, even though those TODOs are historical. | The file rename would otherwise leave dead references to moved authority files. | Keep rewrite scope limited to live authority docs only and accept historical broken refs temporarily. | `Medium` | `Promote to Decision` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/account_workspace_module.md`
- `foundation_documentation/modules/account_profile_analytics_capability.md`
- renamed successor files under `foundation_documentation/modules/`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/artifacts/feature-briefs/account-profile-module-family-reconciliation.md`
- active TODOs that reference these files

### Ordered Steps
1. Freeze the two remaining execution decisions for this slice (`capability doc placement`, `historical reference rewrite policy`).
2. Rename the three files to canonical successors.
3. Update internal titles/authority notes so the renamed docs read as the new canonical surfaces.
4. Rewrite references in constitution, active TODOs, feature brief, and any additional docs needed to preserve link continuity.
5. Validate with `rg`, `diff --check`, and manual readback.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation rename/restructure only
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
