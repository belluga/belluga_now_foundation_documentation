# Title
Deferred Commerce Authority Framing

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The foundation authority reconciliation already normalized the main current-state module surfaces. The remaining local drift is smaller and more specific: `transaction_bridge_module.md` still reads like a current runtime authority even though the current code-backed commerce surface is split across active Ticketing and Checkout planning streams, while adjacent invite analytics wording still implies a standalone analytics/CRM module that the current authority model explicitly treats as capability-first.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/foundation-authority-and-branch-reconciliation.md`
- **Primary story ID:** `ST-04D`
- **Why this is the right current slice:** it retires one last future-vs-current authority ambiguity without opening any new implementation front.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** `n/a`

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: it may absorb small wording changes in directly adjacent module surfaces when needed to keep commerce/analytics authority framing coherent.
- If execution reveals a need to redesign commerce schemas, checkout contracts, or workspace capability structure, split that into another TODO instead of inflating this cleanup slice.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Docs-Validated`
- **Next exact step:** checkpoint and push this deferred commerce authority framing slice.

## Scope
- [x] Update `transaction_bridge_module.md` so it is explicitly documented as a future planning surface rather than a current runtime authority.
- [x] Align `transaction_bridge_module.md` with the current Ticketing/Checkout authority picture without redesigning the underlying future commerce boundary.
- [x] Update the adjacent analytics wording in `invite_and_social_loop_module.md` so it refers to future account/workspace analytics capability rather than a standalone analytics/CRM module.
- [x] Keep code, active payment implementation, and broader capability redesign out of scope.

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
| Deferred commerce authority framing | `docs/foundation-authority-reconciliation@working-tree` | `n/a` | `n/a` | `<pending>` | `local-implemented` |

## Out of Scope
- [ ] Do not implement checkout, booking, or payment runtime behavior.
- [ ] Do not redesign ticketing/checkout package contracts.
- [ ] Do not rewrite domain entities or rename every historical `offering` / `transaction` reference in one pass.
- [ ] Do not turn future workspace analytics into a current module boundary.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** authority notes, current-vs-future posture wording, adjacent reference cleanup, and small wording updates that remove fake current-runtime implications.
- **Must update or split the TODO:** schema redesign, route/API redesign, or package-boundary changes.

## Definition of Done
- [x] `transaction_bridge_module.md` no longer reads like a current code-backed runtime authority.
- [x] The current Ticketing/Checkout authority picture is acknowledged without collapsing future commerce planning.
- [x] Adjacent invite analytics wording no longer implies a standalone current analytics/CRM module.

## Validation Steps
- [x] `git -C foundation_documentation diff --check`
- [x] `rg -n "Account Analytics/CRM module|mock phases without a live payment processor" foundation_documentation/modules/transaction_bridge_module.md foundation_documentation/modules/invite_and_social_loop_module.md`
- [x] `rg -n "planning surface|planning-only|Ticketing and Checkout program streams|TODO-vnext-checkout-package-integration.md" foundation_documentation/modules/transaction_bridge_module.md`
- [x] Manual readback confirms the touched docs clearly separate current authority from deferred planning.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | This is a bounded documentation authority cleanup slice. | selected module docs only | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** documentation-only authority framing over two local surfaces

## Ecosystem Impact Analysis
- **Current TODO classification:** `Project-Local`
- **Why:** this slice only reconciles Belluga Now/Bóora! documentation authority and does not alter any reusable package boundary itself.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/transaction_bridge_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/account_profile_analytics_capability.md`
  - `foundation_documentation/modules/account_workspace_module.md`
- **Planned decision promotion targets (module sections):**
  - authority note / overview / roadmap / adjacent analytics wording only
- **Module decision consolidation targets (required):**
  - the touched modules themselves; do not promote to constitution unless a real project-level rule is uncovered

## Decision Pending (Resolve Before Freeze)
- [x] `none`

## Decisions (Resolved Before Freeze)
- [x] `D-01` `transaction_bridge_module.md` may remain as a planning surface, but it must stop implying current code-backed authority when that authority already lives elsewhere. (`No Prior Decision`)
- [x] `D-02` Future analytics for account operators remains capability-first and subordinate to source modules plus `account_workspace` unless implementation later proves a standalone boundary. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `events_module.md §3.2` | Ticketing domain moved to dedicated package/program and remains external to Events core. | `Preserve` | `foundation_documentation/modules/events_module.md` |
- | `account_profile_analytics_capability.md §1-§3` | Analytics remains capability-first, not a standalone current runtime authority. | `Preserve` | `foundation_documentation/modules/account_profile_analytics_capability.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] This slice is authority framing only, not commerce redesign.
- [x] Current code-backed ticketing/checkout authority outranks older generic commerce-module wording.
- [x] Future analytics capability must remain subordinate to source modules and `account_workspace`.

## Questions To Close
- [x] Is there current code-backed evidence for Transaction Bridge runtime endpoints in the codebase?
  No. Targeted code search across Laravel/Flutter/Web found no current `/api/v1/bookings`, `/api/v1/transactions`, `booking_reservations`, or `transaction_ledger` implementation surfaces.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Reframing the Transaction Bridge doc can stay purely documentary without changing any current code-facing contract. | The current code search found no runtime implementation surface for the documented endpoints. | Stop and split if current runtime authority is discovered during readback. | `High` | `Keep as Assumption` |
| `A-02` | The invite analytics wording can be normalized locally without reopening invite metrics design. | The current drift is naming/authority framing, not metric semantics. | Stop and split if the wording change reveals a deeper ownership conflict. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/transaction_bridge_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/account_profile_analytics_capability.md` (readback only unless a local wording fix is required)
- `foundation_documentation/modules/account_workspace_module.md` (readback only unless a local wording fix is required)
- this TODO file

### Ordered Steps
1. Freeze the bounded future-vs-current framing scope.
2. Update the Transaction Bridge module so it reads as a future planning surface aligned with current Ticketing/Checkout authority.
3. Normalize the adjacent invite analytics wording.
4. Re-read the related analytics/workspace docs for semantic coherence.
5. Validate with `diff --check`, targeted `rg`, and manual readback.

### Test Strategy
- **Strategy:** `not-applicable`
- **Why:** documentation authority cleanup only
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
