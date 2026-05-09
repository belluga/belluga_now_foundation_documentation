# Title
Current-State Module Authority Alignment

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Note
- **Closed on:** `2026-04-18`
- **Closure reason:** the current-state authority alignment objective was materially delivered during the foundation authority reconciliation on `docs/foundation-authority-reconciliation`; this file remains as the historical record for that sub-slice.

## Context
The top-level authority package is restored, the account-profile module-family topology is frozen, and the canonical authority rename checkpoint is complete. The remaining drift is no longer about missing constitutional authority or legacy filenames; it is now about selected module docs still describing older planning phases, pre-rename authority assumptions, or conceptual shapes that no longer match the current pre-MVP/runtime-backed state.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-04B`
- **Why this is the right current slice:** the next highest-value follow-up is to keep the most implementation-steering module docs aligned with the current code-backed project state before the store-delivery and VNext TODOs continue using them as authority.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb targeted edits in the selected module docs when those edits clarify current authority, retire stale phase/lane wording, or separate current runtime from deferred capability planning.
- If execution reveals a broader redesign front (for example large schema re-authoring, route-policy changes, or code-symbol normalization), split that into another TODO instead of inflating this one.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** perform final readback, then checkpoint this current-state module alignment slice.

## Scope
- [x] Align `flutter_client_experience_module.md` with the current lane model and current canonical entity/runtime framing.
- [x] Align `account_profile_catalog_module.md` with the already-landed canonical rename and current account-profile authority posture.
- [x] Align `map_poi_module.md` so current runtime authority is separated from older conceptual planning residue that no longer reflects the main source entities.
- [x] Read back `events_module.md` and `tenant_admin_module.md` to ensure cross-module references remain coherent after the edits above; edit them only if a local correction is required.
- [x] Keep public route aliases such as `/parceiro/:slug` unchanged in this slice.

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
| Current-state module authority alignment | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not rename public product routes or aliases here.
- [ ] Do not perform Flutter/Laravel runtime/code-symbol refactors here.
- [ ] Do not re-open package/extraction strategy debates for this slice.
- [ ] Do not rewrite every module doc in one pass; keep the slice limited to the selected current-state authority surfaces.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** targeted current-state authority wording fixes, lane/path cleanup, roadmap/phase cleanup local to touched module docs, and small cross-module wording corrections needed to preserve coherence.
- **Must update or split the TODO:** public route policy changes, code/runtime changes, or broad terminology retirement beyond the touched module authority surfaces.

## Definition of Done
- [x] The selected module docs no longer present pre-rename authority assumptions, retired active-lane paths, or phase-era planning residue as if it were current authority.
- [x] Touched module docs distinguish clearly between current runtime-backed authority and deferred capability planning.
- [x] Cross-module readback confirms the touched docs remain coherent with `project_constitution.md`, `domain_entities.md`, and the current code-backed route/runtime surfaces.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "mvp_slices|Phase [0-9]+|later module-family rename slice|future rename pending|custom_object|live offers|poi_types" foundation_documentation/modules/flutter_client_experience_module.md foundation_documentation/modules/account_profile_catalog_module.md foundation_documentation/modules/map_poi_module.md -g '!foundation_documentation/todos/completed/TODO-vnext-current-state-module-authority-alignment.md'`
- [x] Targeted code/doc readback confirms the touched contracts still match the current runtime-backed routes/endpoints they claim to govern.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | The slice is documentation-only but requires precise current-state alignment against implemented Flutter/Laravel routes/contracts. | selected module docs, constitution/domain readback | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** this slice is still documentation-only, but it crosses multiple implementation-steering module docs and requires evidence-based alignment against current code-backed contracts.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice aligns Bóora!/Belluga Now module authority to the current downstream project state. It may improve future ecosystem guidance, but the concrete module wording and runtime references are project-specific.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/domain_entities.md`
- **Planned decision promotion targets (module sections):**
  - touched module overview / anchor / roadmap / decision sections
- **Module decision consolidation targets (required):**
  - the touched module docs first; only promote to constitution if a project-level rule is actually uncovered

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` This slice may retire stale phase-era wording and pre-rename authority notes when the replacement wording is already supported by the current constitution and code-backed runtime surface. (`No Prior Decision`)
- [x] `D-02` Public/product aliases such as `/parceiro/:slug` remain out of scope even when the surrounding module wording is normalized. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `project_constitution.md §4-§8` | Current authority is organized around current runtime-backed modules plus explicit capability-first planning for not-yet-implemented fronts. | `Preserve` | `foundation_documentation/project_constitution.md` |
- | `domain_entities.md §2-§5` | Strategic umbrella labels such as `offering` and legacy `partner` language must not be treated as already-canonical current entities. | `Preserve` | `foundation_documentation/domain_entities.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] The slice is about current-state authority alignment, not conceptual redesign.
- [x] Current runtime-backed module truth outranks older phase-planning wording inside touched module docs.
- [x] Deferred capability planning may remain documented, but it must be marked explicitly as deferred and must not masquerade as current runtime authority.

## Questions To Close
- [x] Which module drifts are local enough to fix now without opening a broader redesign front?
  The currently confirmed local drifts are phase/lane residue in `flutter_client_experience_module.md`, pre-rename authority residue in `account_profile_catalog_module.md`, and conceptual current-state drift in `map_poi_module.md`. `events_module.md` and `tenant_admin_module.md` are readback surfaces unless a local correction becomes necessary.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The selected current-state drifts can be corrected without changing public route policy or runtime code. | The identified issues are wording/authority drift, not code contract breaks. | The slice must split and stop before implementation-policy changes. | `High` | `Keep as Assumption` |
| `A-02` | `events_module.md` will likely need readback only, not a broad rewrite. | The current audit found stronger drift in Flutter/catalog/map docs than in Events. | Events may need to join the touched edit set if cross-module wording proves inconsistent. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/project_constitution.md`
- `foundation_documentation/domain_entities.md`
- this TODO file

### Ordered Steps
1. Freeze the bounded scope for current-state authority alignment.
2. Edit the selected module docs to retire local current-state drift only.
3. Re-read adjacent module docs and current code-backed contracts for coherence.
4. Validate with `diff --check`, targeted `rg`, and manual readback.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation authority alignment only
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
