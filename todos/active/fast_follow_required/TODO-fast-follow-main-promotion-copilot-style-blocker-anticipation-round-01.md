# TODO (Fast Follow): Main Promotion Copilot-Style Blocker Anticipation Audit Round 01

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Provisional / audit complete. Round `01` audit execution is complete and adjudicated; the confirmed blocker TODOs were resolved through their own lanes and the later `main` promotion completed. This audit TODO remains open only for evidence reconciliation or archival.

## Title
Fast Follow: Main Promotion Copilot-Style Blocker Anticipation Audit Round 01

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- The current `main` promotion attempt must follow the canonical order:
  - `flutter-app` `stage -> main`
  - `laravel-app` `stage -> main`
  - `belluga_now_docker` `stage -> main`
- A Docker-root PR was opened prematurely and failed on submodule lane alignment. That outcome is now treated as expected lane behavior, not as the governing blocker.
- The current real blocker candidate is on Flutter PR `belluga/belluga_now_front#331`, where a review comment identified a route-contract regression in the technical integrations screen deep-open flow.
- Before treating that single review as the only blocker, the user requested a fresh external blocker audit using the same prioritization standard as the promotion skills' Copilot review gate.
- This TODO exists to run that blocker-only audit intentionally and to convert every validated blocker into explicit follow-up TODOs before any code fix begins.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-main-promotion-blocker-anticipation-round-01`
- **Why this is the right current slice:** this is one bounded pre-promotion audit slice with one primary outcome: identify all real `main` blockers early, under a stricter blocker-only review contract, before any remediation work starts.
- **Direct-to-TODO rationale:** the task is already narrow, tactical, and audit-shaped; no separate feature brief is needed.

## Contract Boundary
- This TODO defines the audit contract for **Round 01** only.
- The deliverable of this TODO is:
  - one bounded external audit packet;
  - one executed triple-audit session plus Claude CLI review;
  - one adjudicated blocker ledger;
  - one follow-up tactical TODO per validated blocker cluster.
- This TODO does **not** include fixing blockers.
- No production/code remediation may begin from this TODO alone.

## Delivery Status Canon
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Blocked`, `Fast-Follow`, `Docker`, `Flutter`, `Laravel`, `Main-Promotion-Gate`, `External-Audit-Required`, `Copilot-Style-Blocker-Only`
- **Next exact step:** reconcile closure evidence and archive after the blocker TODOs moved to `completed`.

## Promotion Scope Under Audit
- `belluga/belluga_now_front#331`
- `belluga/belluga_now_backend#219`
- `belluga/belluga_now_docker#737`
- Current `stage` candidate tuple:
  - root `4615cbc94ca9c80a75ec11f9294e82356fe40932`
  - flutter `1b780c30de30bf75965be09cb7eaf1bca798249f`
  - laravel `4fc743828be9a959c0cb5e0314b86bd3312d8229`

## Scope
- [ ] Freeze a bounded audit package for the exact current `stage -> main` candidate set.
- [ ] Run a new triple external audit using blocker-only criteria aligned to the promotion skills' Copilot gate.
- [ ] Run Claude CLI on the same bounded package with the same blocker-only framing.
- [ ] Adjudicate contradictions across the reviewers.
- [ ] Convert every validated blocker cluster into its own tactical TODO under `foundation_documentation/todos/active/fast_follow_required/`.
- [ ] Explicitly classify every finding as `blocking`, `accepted-debt`, or `out-of-scope`.

## Out of Scope
- [ ] Fixing any blocker in this TODO.
- [ ] Reopening already promoted `dev -> stage` slices unless the audit proves a blocker in those exact promoted surfaces.
- [ ] Minor cleanup, style, naming, or opportunistic refactors.
- [ ] Any new promotion attempt to `main` before blocker adjudication is complete.

## Definition of Done
- [ ] The bounded Round 01 audit packet exists and is frozen against the exact current promotion candidate.
- [ ] Triple audit round `01` is executed and recorded.
- [ ] Claude CLI blocker review is executed and recorded on the same packet.
- [ ] Every finding is adjudicated under the blocker-only gate.
- [ ] Every validated blocker cluster has a dedicated follow-up TODO created.
- [ ] The final summary states whether `main` is currently blocked and by which TODOs.

## Validation Steps
- [ ] `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md --json-output <artifact>`
- [ ] `python3 delphi-ai/skills/audit-protocol-triple-review/scripts/triple_audit_session.py start --package <bounded_package_path> --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`
- [ ] Record three no-context audit results (`elegance`, `performance`, `test-quality`) and merge the round.
- [ ] Run Claude CLI against the same packet and store the output in `foundation_documentation/artifacts/tmp/`.
- [ ] `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`

## Execution Lane Tracking
- **Primary execution profile:** `Operational / DevOps`
- **Active technical scope:** `docker`
- **Supporting profiles expected:** `Assurance / Test-Quality`, `Flutter`, `Laravel`
- **Implementation repos expected:** none for this TODO; remediation TODOs will declare their own execution repos after audit closure.

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** no code implementation is expected in this slice, but the audit spans three repos and gates `main` promotion.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary canonical anchors:**
  - `foundation_documentation/project_constitution.md`
  - `/home/elton/Dev/repos/delphi-ai/skills/github-main-promotion-orchestrator/SKILL.md`
  - `/home/elton/Dev/repos/delphi-ai/skills/audit-protocol-triple-review/SKILL.md`
- **Planned decision promotion targets:**
  - blocker follow-up TODOs created from this audit

## Copilot-Style Blocker Gate (Frozen For This Audit)
- Review findings in this order:
  1. `Security / auth / tenant isolation`
  2. `Data-loss / regression / broken contract`
  3. `CI / pipeline false-green risk`
  4. `Flaky or weak tests masking real bugs`
  5. `Minor cleanup`
- Findings in classes `1-4` may block `main`.
- Class `5` must never block this audit round.
- A green CI check does **not** override a pertinent class `1` or `2` finding.
- Every finding must state:
  - exact surface (`repo`, `PR`, file/route/contract path);
  - why it can block `main` now;
  - whether the evidence is direct or inferred;
  - whether it is `blocking`, `accepted-debt`, or `out-of-scope`.

## Decision Baseline (Frozen Before Audit Execution)
- [x] `D-01` This round is blocker-only. The auditors are not being asked for cleanup or polish.
- [x] `D-02` The audit target is the exact current `stage -> main` candidate set, not speculative future code.
- [x] `D-03` Every validated blocker cluster must become its own tactical TODO before any fix starts.
- [x] `D-04` No code correction or promotion retry may begin from this TODO without a separate approval cycle on the resulting blocker TODOs.
- [x] `D-05` The pre-existing Docker lane-alignment failure is treated as expected sequencing behavior, not as the primary blocker under audit unless a reviewer proves a deeper root-contract issue.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current highest-signal blocker candidate is Flutter PR `#331`, not backend `#219`. | Existing review comment + prior no-context audit consensus on the deep-open regression; backend PR currently has green checks and no pertinent review comments. | The fresh audit may surface a backend or root-level blocker of equal or greater severity. | `Medium` | `Keep as Assumption` |
| `A-02` | Running the external audit with Copilot-style blocker priorities will reduce merge-late surprises by filtering out non-blocking noise. | Promotion skills already treat pertinent Copilot P1/P2 comments as blocking even on green CI. | Auditors may still over-report cleanup unless the packet is tightly framed. | `High` | `Keep as Assumption` |
| `A-03` | One TODO per validated blocker cluster is the cleanest way to preserve TODO-driven execution after the audit. | User explicitly requested that each audit round turn into specific TODOs for the blockers found. | A single blocker may span multiple repos and need one multi-repo TODO instead of multiple tiny TODOs. | `Medium` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`
- bounded packet under `foundation_documentation/artifacts/tmp/`
- triple-audit session artifacts under `foundation_documentation/artifacts/tmp/`
- follow-up blocker TODOs under `foundation_documentation/todos/active/fast_follow_required/`

### Ordered Steps
1. Freeze the bounded Round 01 packet from the exact current PRs and promotion-skill context.
2. Start a new triple-audit session from that packet.
3. Dispatch blocker-only packets to `elegance`, `performance`, and `test-quality`.
4. Run Claude CLI against the same packet with the same blocker-only framing.
5. Merge/adjudicate results.
6. Create one tactical TODO per validated blocker cluster.
7. Return the blocker map and stop before any fix.

### Test Strategy
- **Strategy:** `audit-only / no implementation`
- **Why:** this slice exists only to identify and freeze blockers before remediation work starts.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Main-promotion blocker anticipation | Release gate only; no end-user flow is being changed in this TODO | `n/a` | `n/a` | `no` | `no` | bounded audit packet + triple audit + Claude CLI + blocker TODO creation | This TODO performs no user-facing implementation. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `n/a` | This TODO performs no code or workflow implementation; it is an audit-only slice. | `n/a` | `n/a` | `n/a` | `n/a` | CI-equivalent obligations belong to the follow-up blocker TODOs, not to this audit round itself. |

### Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Visible Route / Action | Planned Evidence | Waiver |
| --- | --- | --- | --- | --- |
| `audit findings / blocker ledger` | `internal-only` | `main promotion decision` | TODO ledger + audit artifacts | No product consumer surface is introduced by this TODO. |

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/audit-escalation-guard.json`

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Matches the TODO Complexity section. |
| `blast_radius` | `cross-stack` | The audit spans Flutter, Laravel, Docker root, and promotion-lane sequencing. |
| `behavioral_change_or_bugfix` | `yes` | The audit is explicitly looking for regressions and broken contracts that block `main`. |
| `changes_public_contract` | `no` | This TODO does not implement a contract change; it audits promotion blockers only. |
| `touches_auth_or_tenant` | `yes` | Auth/tenant-isolation findings are explicitly first-class blockers in the review gate. |
| `touches_runtime_or_infra` | `yes` | Docker CI/runtime/promotion surfaces are in scope for blocker anticipation. |
| `touches_tests` | `yes` | Flaky/weak test findings are explicitly part of the blocker taxonomy. |
| `critical_user_journey` | `yes` | Findings may affect admin deep-open and release-critical promotion journeys. |
| `release_or_promotion_critical` | `yes` | This TODO exists purely to gate the `stage -> main` promotion decision. |
| `high_severity_plan_review_issue` | `yes` | A credible blocker candidate is already known on Flutter PR `#331`; the audit exists to confirm and expand blocker discovery. |
| `explicit_three_lane_request` | `yes` | The user explicitly requested the external three-auditor loop plus Claude CLI. |

## Planned Deliverables
- bounded audit packet: `foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/`
- triple-audit session under that same packet root
- one follow-up TODO per validated blocker cluster
- one final blocker ledger summary with:
  - `blocking`
  - `accepted-debt`
  - `out-of-scope`

## Audit Outcome Snapshot
- Triple audit session:
  - `foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/triple-audit-20260521T235900Z/session.json`
- Round `01` merged with status:
  - `needs_adjudication`
- Delphi adjudication recorded at:
  - [resolution.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/triple-audit-20260521T235900Z/round-01/resolution.md:1)
- Confirmed blocker TODOs opened from this round:
  - [TODO-fast-follow-main-promotion-technical-integrations-initial-section-route-contract.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-technical-integrations-initial-section-route-contract.md:1)
  - [TODO-fast-follow-main-promotion-rollback-root-queue-normalization-parity.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-rollback-root-queue-normalization-parity.md:1)
- Accepted non-blocking debt for later Docker-leg revisit:
  - `F-PR737-01` timeout-fragility in `navigation_harness_policy_test.cjs`
- Claude CLI supplemental verdict:
  - [claude-cli-review.json](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/claude-cli-review.json:1)
  - `A=blocking`, `B=debt`, `C=blocking`

## References
- `belluga/belluga_now_front#331`
- `belluga/belluga_now_backend#219`
- `belluga/belluga_now_docker#737`
- `foundation_documentation/artifacts/tmp/main-promotion-docker-only-submodule-lane-blocker-20260521/`
- `foundation_documentation/artifacts/tmp/flutter-main-pr331-initial-section-focus-review-20260521/`
