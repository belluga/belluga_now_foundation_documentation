# Title
Foundation Legacy `pitch/` And `screens/` Retirement

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The documentation reconciliation stabilized `modules/` and `policies/` as the current canonical authority, while `pitch/` and `screens/` remained as older support trees. The user explicitly wants those two folders removed now and, if screen-oriented documentation returns later, it should be rebuilt through a more integrated Stitch-aware approach rather than preserving the current legacy tree.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this is one bounded documentation-governance cleanup slice: retire two legacy folders, rewrite the small set of still-live references that would become misleading, and leave future screen-authoring strategy for a separate decision.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user gave an explicit bounded instruction, the scope is documentation-only, and no separate decomposition artifact is needed to understand or execute the change safely.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb the reference rewrites and small authority-note adjustments required so removing `pitch/` and `screens/` does not leave active or misleading documentation behind.
- If execution reveals that a broader documentation taxonomy redesign is needed (for example introducing a formal Stitch-native screen-doc system), that must open as a separate TODO instead of being smuggled into this cleanup.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `none`
- **Next exact step:** checkpoint and push the retired-legacy-folder cleanup on `docs/foundation-authority-reconciliation`.

## Scope
- [x] Remove `foundation_documentation/pitch/`.
- [x] Remove `foundation_documentation/screens/`.
- [x] Rewrite active or still-live references that would otherwise point to removed files.
- [x] Normalize the small set of non-canonical support docs that still depend directly on those paths when leaving them untouched would create broken or misleading references after folder removal.
- [x] Keep canonical authority centered on `project_constitution.md`, `policies/*.md`, and `modules/*.md`.

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
| Legacy folder retirement (`pitch/` + `screens/`) | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not invent the replacement screen-authoring strategy in this slice.
- [ ] Do not recreate `screens/` under another name during this cleanup.
- [ ] Do not reopen module contracts that are already canonical in `modules/*.md` or `policies/*.md`.
- [ ] Do not perform unrelated feature/backlog cleanup beyond references directly needed for this retirement.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** deleting the two legacy folders, rewriting still-live path references, and updating small repo-operational notes so the current authority topology remains coherent.
- **Must update or split the TODO:** any attempt to design a new Stitch documentation system, revive prototype/mock-authority flows, or perform broad cleanup of unrelated historical TODO prose.

## Definition of Done
- [x] `pitch/` is removed from `foundation_documentation/`.
- [x] `screens/` is removed from `foundation_documentation/`.
- [x] Active/current documentation no longer depends on files under those retired paths.
- [x] Canonical repo guidance no longer implies `screens/` or `pitch/` are active authority surfaces.
- [x] A repo-wide search confirms no remaining live references require those folders to exist.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "foundation_documentation/pitch/|foundation_documentation/screens/|screens/modulo_|pitch/pitch" foundation_documentation/README.md foundation_documentation/project_constitution.md foundation_documentation/system_roadmap.md foundation_documentation/modules foundation_documentation/mock_roadmap.md foundation_documentation/todos/active --glob '!foundation_documentation/todos/active/vnext/TODO-vnext-foundation-legacy-pitch-and-screens-retirement.md'`
- [x] `find foundation_documentation -maxdepth 2 -type d | sort`
- [x] Manual readback confirms `README.md` and touched current docs no longer imply those removed trees are current authority.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `n/a` | `n/a` | `n/a` | `n/a` | `n/a` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** bounded documentation-only cleanup, but it still needs a deliberate reference rewrite so deletion does not leave active authority drift.

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice only retires Bóora!/Belluga Now foundation-doc folders that no longer represent the current project authority. It does not establish a reusable ecosystem-wide documentation pattern by itself.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/project_constitution.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/README.md` canonical-authority + scope notes
  - any touched active TODO that still points to retired legacy screen docs
- **Module decision consolidation targets (required):**
  - this TODO first; then `README.md` and touched current docs that must stop pointing to the retired trees

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` `pitch/` is not a current canonical authority surface and may be removed outright in this slice rather than archived in place. (`No Prior Decision`)
- [x] `D-02` `screens/` is legacy support material, not current canonical authority. It may be removed once active/current references are rewritten so the remaining repo truth lives in `modules/`, `policies/`, and project-level canonical docs. (`No Prior Decision`)
- [x] `D-03` Historical completed TODOs may be path-normalized in a limited way when needed to avoid dead references after folder removal, but this slice does not owe a full prose cleanup of historical records. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `project_constitution.md §2.2 / §6` | Canonical authority is `project_mandate.md`, `domain_entities.md`, `project_constitution.md`, `system_roadmap.md`, `policies/*.md`, and `modules/*.md`; completed TODOs and artifacts are supporting evidence only. | `Preserve` | `foundation_documentation/project_constitution.md` |
- | `flutter_client_experience_module.md` current role | Current Flutter/runtime UI authority already lives in module docs, not in legacy screen-tree docs. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |
- | `tenant_admin_module.md` current role | Tenant-admin current contracts live in the module doc; older `screens/modulo_tenant_admin.md` is no longer the authority source. | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] Removing `pitch/` and `screens/` is the correct current-state direction.
- [x] Live/current references must be rewritten in the same slice so deletion does not create active authority drift.
- [x] Historical cleanup stays bounded; only the references that materially matter for coherent retirement are touched.

## Questions To Close
- [x] Should these trees be archived in place or removed now?
  Remove now. A future screen-documentation model, if needed, will be reintroduced intentionally rather than preserved through the current legacy structure.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `policies/` is active canonical authority, while `screens/` is not. | `README.md` and `project_constitution.md` list `policies/*.md` and `modules/*.md` as canonical surfaces; `screens/*.md` is absent from that list. | The cleanup would need a strategic authority decision first. | `High` | `Keep as Assumption` |
| `A-02` | `pitch/` has no current canonical dependency chain. | Repo scan found no active/canonical references outside the folder itself. | `pitch/` would need migration to another canonical surface before deletion. | `High` | `Keep as Assumption` |
| `A-03` | The only active still-live dependency on `screens/` is limited enough to rewrite in this slice, while most remaining references are historical/completed TODO material. | Repo scan shows active `TODO-vnext-parking-lot.md` plus legacy support docs/historical TODOs as the remaining path users. | The slice would need to widen or split into a broader historical-normalization pass. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/README.md`
- `foundation_documentation/mock_roadmap.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-parking-lot.md`
- selected completed TODOs only when a direct path-normalization is needed for coherent retirement
- `foundation_documentation/pitch/**`
- `foundation_documentation/screens/**`

### Ordered Steps
1. Rewrite the still-live references that point directly into `screens/` or `pitch/`.
2. Normalize any small support docs whose direct path references would become misleading immediately after retirement.
3. Remove `foundation_documentation/pitch/`.
4. Remove `foundation_documentation/screens/`.
5. Run repo-wide validation searches and a manual readback of touched current docs.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation-only retirement / reference cleanup
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
- **Issue ID:** `DOC-RETIRE-01`
  - **Severity:** `medium`
  - **Evidence:** active and historical repo references still point to `foundation_documentation/screens/**`.
  - **Why it matters now:** deleting the folders without rewriting the small live reference set would leave active documentation drift and dead paths.
  - **Option A (Recommended):** rewrite still-live references and only the minimum historical paths needed for coherent retirement, then remove the folders.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** keep the folders temporarily and only mark them as legacy.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** leave both trees in place.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`, because it removes the dead authority surfaces without inflating this slice into a broader taxonomy redesign.

### Failure Modes & Edge Cases
- [ ] A still-live doc keeps a removed path and becomes misleading after the folders are gone.
- [ ] A historical TODO is over-edited and accidentally stops reflecting its original decision context.

### Residual Unknowns / Risks
- [ ] Some completed TODOs may still mention removed paths textually after this slice; that is acceptable as long as current authority no longer depends on those trees and the limited historical rewrites done here are enough to avoid confusing live references.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `n/a`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `n/a`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` | `n/a` |

## Independent No-Context Critique Gate (Required for `big`; conditional for `medium/high-impact`)
- **Critique decision:** `not_needed`
- **Why this decision:** bounded documentation-only cleanup with no product/runtime contract change
- **Impact signals in scope:** `none`
- **Package mode:** `n/a`
- **Package minimum contents:** `n/a`
- **Critique isolation mode:** `n/a`
- **Subagent mandate (when available):** `no`
- **Canonical multi-lane audit protocol (when required):** `n/a`
- **Audit session / round evidence (when protocol used):** `n/a`
- **Critique lenses:** `n/a`
- **Critique status:** `not_run`
- **Findings summary:** `none`
- **Evidence / reference:** `n/a`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/skills/rule-docker-shared-project-mandate-always-on/SKILL.md` | This slice changes project-specific documentation authority and must stay aligned with the current mandate and foundational docs. | Keep `project_mandate.md`, `project_constitution.md`, `modules/*.md`, and `policies/*.md` as the current authority hierarchy. | Do not let retired legacy folders continue to imply authority after the cleanup. | Every touched current doc must be checked against the canonical authority model before the legacy folders are deleted. |
| `delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md` | This is the active tactical TODO execution path for a bounded documentation cleanup slice. | Keep work inside the approved contract, reference cleanup, and folder retirement only. | Do not widen into a new screen-authoring system or a broad historical rewrite pass. | Execute the scoped cleanup, validate it, then checkpoint and close the slice cleanly. |
| `foundation_documentation/project_constitution.md` | The constitution is the local authority for canonical documentation topology and active authority surfaces. | Preserve `policies/*.md` and `modules/*.md` as canonical authority; preserve the current module/capability boundaries already frozen. | Do not reopen documentation-topology strategy beyond retiring `pitch/` and `screens/`. | The cleanup is valid only because the current authority already lives outside those legacy folders. |
| `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder` | Profile scope check remains required before execution even for documentation-only slices. | Keep execution limited to the docs repo surfaces declared by this TODO. | Do not treat unrelated workspace drift as justification to widen the slice. | The command returned `review required`; current execution remained bounded to `foundation_documentation`, while an unrelated dirty path exists in the Flutter repo root and was left untouched. |

## Decision Adherence Validation (Mandatory Before Delivery)
- | Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
- | --- | --- | --- | --- |
- | `D-01` | `Adherent` | `README.md`, retired `pitch/` tree removed | `pitch/` was removed outright and no current docs still depend on it. |
- | `D-02` | `Adherent` | `README.md`, `TODO-vnext-parking-lot.md`, retired `screens/` tree removed | current authority now lives in `modules/`/`policies/`; live references to `screens/` were removed before deletion. |
- | `D-03` | `Adherent` | `mock_roadmap.md`; no active/current refs remain in validation search | historical cleanup stayed bounded; only the paths needed for coherent retirement were normalized. |

## Module Decision Consistency Validation (1-1 Mandatory Before Delivery)
- | Module Decision Ref | Planned Handling | Delivery Status (`Preserved|Superseded (Approved)|Regression`) | Evidence | Notes |
- | --- | --- | --- | --- | --- |
- | `project_constitution.md §2.2 / §6` | `Preserve` | `Preserved` | `README.md`; validation search returned no live refs to retired trees | canonical authority remains `policies/*.md` + `modules/*.md` and did not regress. |
- | `flutter_client_experience_module.md current role` | `Preserve` | `Preserved` | no changes to module contract; only legacy tree retirement + current-doc cleanup | the cleanup did not reassign Flutter authority back to a legacy screen-doc tree. |
- | `tenant_admin_module.md current role` | `Preserve` | `Preserved` | `TODO-vnext-parking-lot.md`; no current refs still require `screens/modulo_tenant_admin.md` | tenant-admin authority remains in the module doc, not the retired `screens/` tree. |
