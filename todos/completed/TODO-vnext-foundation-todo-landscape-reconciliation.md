# Title
Foundation TODO Landscape Reconciliation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Note
- **Closed on:** `2026-04-18`
- **Closure reason:** the TODO-landscape reconciliation objective was materially delivered during the foundation authority reconciliation on `docs/foundation-authority-reconciliation`; this file remains as the historical audit record for that sub-slice.

## Context
The current `foundation_documentation/todos/` landscape is no longer fully coherent. `store_release_android/` and `fast_follow_required/` already express the intended near-term lanes, but `vnext/` has grown into a mixed surface containing real deferred programs, temporary reconciliation slices, support registries, one-off bug TODOs, and files with inconsistent naming/template posture. This creates avoidable noise when deciding what is current execution authority versus what is just backlog visibility.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-04`
- **Why this is the right current slice:** after structural authority reconciliation, the next docs-only normalization front is the TODO topology itself.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb safe taxonomy/naming/guide updates and limited TODO normalization when those changes clearly improve lane clarity without reopening product scope.
- If execution reveals the need to re-author many program TODOs individually, split that into follow-up slices rather than inflating this audit.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Docs-Validated`
- **Next exact step:** checkpoint the TODO-landscape audit and guide updates on `docs/foundation-authority-reconciliation`, then treat any further `vnext` regrouping as dedicated follow-up slices only when overlap is explicit.

## Scope
- [x] Audit active TODO topology across `store_release_android/`, `fast_follow_required/`, and `vnext/`.
- [x] Record where `vnext/` mixes distinct categories such as program owners, support registries, sub-slices, and one-off regressions.
- [x] Improve lane guidance and grouping rules so `store_release_android/` and `fast_follow_required/` stay clearer than `vnext/`.
- [x] Apply safe normalization changes that do not require runtime/code validation outside `foundation_documentation`.

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
| TODO landscape audit and normalization | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not open implementation work in `flutter-app`, `laravel-app`, or root repo.
- [ ] Do not rewrite every active TODO into one unified template in this slice.
- [ ] Do not retire active program TODOs only because they are deferred.
- [ ] Do not merge distinct product/program fronts unless the overlap is explicit and documentary-only.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** audit artifact creation, README guidance updates, naming cleanup, and explicit classification of support-registry versus owner TODOs.
- **Must update or split the TODO:** broad re-authoring of many unrelated TODOs or cross-repo execution planning.

## Definition of Done
- [x] The active TODO landscape has an explicit audited classification by lane/program role.
- [x] The main sources of `vnext/` ambiguity are recorded with concrete recommendations.
- [x] Safe consistency improvements are applied directly in `foundation_documentation`.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `find foundation_documentation/todos/active -maxdepth 2 -type f | sort`
- [x] Manual readback confirms the updated TODO guidance and touched files reflect the intended lane clarity.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | This is a bounded foundation-documentation TODO topology cleanup. | `foundation_documentation/todos/**`, supporting artifacts | `executed; strategic-cto + operational-coder scope checks returned in-scope for README, audit artifact, and this TODO` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** taxonomy/governance cleanup only inside one repository

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice improves Belluga Now foundation-documentation backlog clarity only; it does not change reusable package boundaries.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/todos/README.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/todos/completed/TODO-store-release-android.md`
  - `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`
  - `foundation_documentation/todos/active/vnext/TODO-vnext-parking-lot.md`
- **Planned decision promotion targets (module sections):**
  - TODO guide and the dedicated audit artifact for this slice
- **Module decision consolidation targets (required):**
  - TODO guide + audit artifact first; individual program TODOs only when a local normalization is clearly safe

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` `store_release_android/` and `fast_follow_required/` are the near-term authoritative lanes and should be preserved as clearer than `vnext/`. (`No Prior Decision`)
- [x] `D-02` `vnext/` may contain deferred work, but it should not silently mix program owners, support registries, and malformed one-off files without explicit classification. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `todos/README.md` | `store_release_android`, `fast_follow_required`, and `vnext` are the only active lanes. | `Preserve` | TODO guide |
- | `TODO-store-release-android.md` + `TODO-fast-follow-obligatory.md` | Release/follow-up sequencing already has dedicated owner TODOs. | `Preserve` | active lane orchestrators |

## Decision Baseline (Frozen Before Implementation)
- [x] This slice is about TODO clarity and topology, not product reprioritization.
- [x] `vnext/` may be grouped and normalized, but not flattened into one undifferentiated backlog blob.
- [x] Support registries and one-off anomalies should be made explicit when they remain active.

## Questions To Close
- [x] Which active TODOs are true program owners versus support/backlog noise?
  This is the main output of the audit and will be recorded explicitly in the audit artifact.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The main TODO-landscape inconsistencies can be improved without rewriting every active file. | The biggest issues are lane guidance, grouping clarity, and a few obvious anomalies. | Split into a larger re-authoring program if individual TODOs prove too inconsistent to classify lightly. | `High` | `Keep as Assumption` |
| `A-02` | At least some `vnext/` clutter can be handled as taxonomy/governance normalization rather than product reprioritization. | Audit shows support registries, sub-slices, and malformed naming mixed together. | Stop and narrow the slice if findings depend on business reprioritization instead of documentation clarity. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/README.md`
- `foundation_documentation/artifacts/todo-landscape-review-2026-04-18.md`
- selected active TODOs only where a local normalization is clearly safe
- this TODO file

### Ordered Steps
1. Audit active TODOs by lane and role.
2. Record the findings and recommended grouping/consolidation.
3. Apply the safe normalization changes inside `foundation_documentation`.
4. Validate with `diff --check`, file inventory, and manual readback.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation/topology cleanup only
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
