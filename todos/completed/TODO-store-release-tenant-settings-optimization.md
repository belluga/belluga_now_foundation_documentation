# TODO (Completed): Store Release - Tenant Settings Read-Path Materialization

**Completed note (2026-05-06):** the broad materialization goal of this TODO is already delivered. Healthy tenant `/api/v1/environment` reads are snapshot-backed, rebuild/version/fallback behavior exists, and the remaining residual found in review was downgraded to a deferred micro-optimization (`deferred_micro_optimizations/TODO-environment-app-domain-lookup-deduplication.md`). This file remains only as audit history.

## Closure Status
- **Status:** `Completed`
- **Disposition:** `Delivered and retained for audit history`

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Reclassified on:** `2026-04-18`
- **Previous lane:** `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-settings-optimization.md`
- **Why this moved into store release:** app bootstrap and tenant-admin settings now depend on hot read paths that should not keep expanding through live aggregation during release hardening.
- **Post-release hardening reclassification:** on `2026-04-30`, this TODO remained active but moved out of the current Android release gate into `active/post_release_hardening/`. Execute after release unless a new explicit business decision promotes it back into the release gate.

## Context
`belluga_settings` and the canonical settings-kernel endpoints already exist. The remaining gap is not missing settings infrastructure; it is that high-frequency tenant read paths still assemble runtime payloads live from multiple sources. That is acceptable as a baseline, but not as the launch-ready read model for store release hardening.

This TODO exists to freeze the canonical read-model direction: keep settings kernel and tenant/domain records as sources of truth, but serve hot consumers from a derived materialized snapshot so `/api/v1/environment` and related admin/bootstrap paths do not grow in query count or aggregation complexity. The optimization goal is internal read-path convergence, not public-contract reduction: the approved first rollout must preserve the current `/api/v1/environment` payload shape while changing how that payload is assembled behind the scenes.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this is one bounded architectural slice: define the derived snapshot model, rebuild triggers, read-path ownership, and rollout boundary before implementation orchestration begins.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the problem is already sharply framed by existing kernel infrastructure and known hot-read consumers.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Historical)
- **Final delivery stage:** `Completed`
- **Historical qualifiers:** `Post-Release-Hardening`, `Cross-Stack`
- **Closure rationale:** snapshot-backed environment/bootstrap delivery, rebuild triggers, fallback behavior, and parity evidence are now present in the repo; the remaining landlord-host `app_domain` double-lookup was reclassified as a low-priority deferred micro-optimization rather than an open implementation gap in this slice.

## Scope
- [ ] Define one canonical derived tenant snapshot/read model for hot settings/bootstrap consumers.
- [ ] Keep `belluga_settings`, tenant records, registries, and related upstream documents as the only source-of-truth writers; the snapshot is derived only.
- [ ] Preserve the current `/api/v1/environment` public contract in the first rollout; do not remove or relocate currently exposed environment fields as part of this optimization slice.
- [ ] Materialize the full approved environment/bootstrap payload for the narrow rollout boundary rather than a settings-only subset, so the hot read stops re-aggregating branding, registry, and domain contributors independently.
- [ ] Define rebuild triggers, atomic replace semantics, and failure handling for snapshot refresh.
- [ ] Define the thin request-sensitive overlay that still resolves host/origin-dependent fields without rehydrating the full upstream aggregation graph.
- [ ] Freeze cache/version/freshness semantics for both backend and client consumers.
- [ ] Define minimum observability for rebuild latency, failures, and stale-snapshot detection.
- [ ] Bound the first rollout to the approved hot-read consumers for this release lane.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Materialized tenant settings/environment read model + rebuild path + hot-read consumers | `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863` | `laravel-app: PR #158 (merged to dev 2026-04-21); flutter-app: PR #236 (merged to dev 2026-04-20)` | `<pending>` | `n/a` | `Reopened; implementation not demonstrated` |

## Retroactive Audit Finding (2026-04-22)

- **Audit outcome:** `reopened`
- **Reason:** the prior `Lane-Promoted` marker was not substantiated by criterion-level evidence and the TODO still has open Scope, DoD, Validation, Plan Review, and residual-risk items.
- **Recovered evidence:** there is tenant-admin diagnostic UI around an environment snapshot surface and the roadmap marks `/api/v1/environment` as implemented, but those artifacts do not prove the materialized read-model contract.
- **Blocking gap:** inspection did not find sufficient evidence of a dedicated materialized/versioned snapshot, rebuild triggers, atomic replace semantics, freshness/version metadata, fail-soft last-valid fallback, or live-vs-snapshot parity validation.
- **Required closure before promotion:** implement or prove the derived snapshot path, rebuild/fallback/parity tests, observability evidence, and a Completion Evidence Matrix with one row per DoD/Validation criterion.

## Out of Scope
- [ ] Replacing canonical settings-kernel write ownership with the snapshot/read model.
- [ ] Full tenant-admin settings IA redesign.
- [ ] Unbounded optimization of every settings-related read path in the repo unless that scope is explicitly approved.
- [ ] Infra/platform changes unrelated to the read-model problem itself.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** derived snapshot schema, rebuild triggers/jobs, atomic replace semantics, read-side route/repository changes for the approved consumers, thin request-sensitive overlay logic, observability, and contract/docs updates required for the same release conversation.
- **Must update or split the TODO:** public `/environment` contract reduction, new settings namespaces, unrelated settings UI redesign, or broad repo-wide performance work beyond the approved hot-read boundary.

## Definition of Done
- [ ] One canonical derived snapshot/read model exists for the approved hot-read consumers.
- [ ] Source-of-truth ownership remains explicit: settings kernel + upstream tenant/domain records write; snapshot only derives.
- [ ] The current `/api/v1/environment` contract remains backward-compatible in the first rollout even though its backing read path changes.
- [ ] The approved `/environment` slice reads from a complete materialized bootstrap snapshot rather than reassembling settings-only fragments plus live registry/branding/domain reads.
- [ ] Rebuild trigger catalog, failure handling, and freshness/version semantics are frozen.
- [ ] Request-sensitive fields are finalized through a thin overlay that does not recreate the previous live aggregation cost.
- [ ] Minimum observability is explicit enough to catch stale or failing rebuilds before promotion.
- [ ] The first rollout boundary is explicit so orchestration does not balloon into every settings consumer at once.

## Validation Steps
- [ ] Manual doc review against `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` and `foundation_documentation/todos/completed/TODO-v1-tenant-settings-unification.md`.
- [ ] Repo/doc inventory of current hot-read consumers (`/api/v1/environment`, tenant-admin settings bootstrap/schema/values consumers, other runtime bootstrap paths touched by this slice).
- [ ] Manual contract review against `foundation_documentation/system_roadmap.md` and `foundation_documentation/modules/tenant_admin_module.md`.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `none required for backlog capture` | This TODO is being normalized as planning/execution truth only. | `healthy` | `2026-04-20` | Local repository + documentation inspection | `n/a` |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Hot-read contract changes and fail-soft behavior need explicit performance/regression-oriented test review. | `laravel-app`, `flutter-app`, tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `full Plan Review Gate before execution approval + one post-validation checkpoint`
- **Why this level:** the architectural direction is cohesive, but the slice touches runtime bootstrap, tenant-scoped reads, async rebuild behavior, and public consumer contracts.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`
  - `foundation_documentation/system_roadmap.md`
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

## Decisions (Resolved Before Freeze)
- [x] `D-TSO-00` `belluga_settings`, tenant records, and other upstream configuration documents remain the only source-of-truth writers; the new snapshot/read model is strictly derived and must never become a competing write surface. Module refs: `TODO-v1-settings-kernel-package.md`, `project_constitution.md`.
- [x] `D-TSO-01` The first rollout boundary is intentionally narrow: materialize only `/api/v1/environment` plus tenant-admin bootstrap/settings hot reads in this orchestration cycle. Do not absorb every current settings-related consumer in the same slice. Module refs: `system_roadmap.md`, `tenant_admin_module.md`, `ARCH-TSO-02`.
- [x] `D-TSO-02` The materialized read model must live as a dedicated derived tenant-scoped document/collection, not by overloading the canonical settings root document with mixed write-model + read-model responsibilities. Module ref: `No Prior Decision`.
- [x] `D-TSO-03` Snapshot rebuild must be asynchronous, event-driven on upstream config changes, and applied through atomic replace/version bump semantics. Module refs: current TODO objective + settings-kernel source-of-truth model.
- [x] `D-TSO-04` Read paths must fail soft: if rebuild fails, consumers continue serving the last valid snapshot while alerting operators and recording rebuild failure evidence. Module ref: `No Prior Decision`.
- [x] `D-TSO-05` There is no arbitrary backend freshness TTL on the canonical snapshot. Freshness is governed by rebuild triggers plus explicit `version`/`built_at` metadata; clients may keep cache-first + async-refresh behavior against versioned payloads. Module refs: `/api/v1/environment` bootstrap posture in `system_roadmap.md` + current TODO objective.
- [x] `D-TSO-06` Minimum observability includes rebuild duration, rebuild failures, snapshot version, `built_at`, and stale-snapshot detection signals. Module ref: `No Prior Decision`.
- [x] `D-TSO-07` Upstream trigger catalog for rebuild includes at least tenant branding/public-web metadata, `map_ui`, telemetry/firebase/push settings, profile-type registry, and any other approved bootstrap payload contributors for the chosen rollout boundary. Module refs: `system_roadmap.md`, `tenant_admin_module.md`, `submodule_laravel-app_summary.md`.
- [x] `D-TSO-08` The first rollout must preserve the current `/api/v1/environment` public contract. This slice optimizes payload assembly behind the contract and must not force cross-app/client field removal or endpoint decomposition now. Module refs: `system_roadmap.md`, `flutter_client_experience_module.md`, Flutter bootstrap contract tests.
- [x] `D-TSO-09` For the approved `/api/v1/environment` boundary, the snapshot must materialize the full bootstrap payload used today, not only settings namespaces. That approved snapshot scope includes tenant identity/branding/public-web metadata, telemetry/firebase/push config, `profile_types`, `settings.map_ui`, and canonical domain/app-domain contributors required by the current contract. Module refs: `system_roadmap.md`, `EnvironmentResolverService.php`, current Flutter DTO contract.
- [x] `D-TSO-10` Request-sensitive fields that depend on the current host/origin may still be finalized at read time, but only through a thin overlay over the derived snapshot. That overlay must not rehydrate the full upstream aggregation graph; it should resolve only host-bound values such as final domain/origin and origin-dependent public URLs. Module refs: `EnvironmentResolverService.php`, `tenant_route_guard.dart`, current environment URL normalization behavior.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `TAD-04` | Tenant map/agenda fallback origin is tenant-owned config under `settings.map_ui.default_origin`. | `Preserve` | `foundation_documentation/modules/tenant_admin_module.md` |
| `No Prior Decision` | No canonical materialized read-model rule currently exists for hot tenant settings/bootstrap consumers. | `Supersede (Intentional)` | Current TODO framing + `system_roadmap.md` hot-read endpoint inventory |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-TSO-00` Source-of-truth writes stay in settings kernel/upstream tenant documents; snapshot is derived only.
- [x] `D-TSO-01` Initial rollout boundary is `/api/v1/environment` plus tenant-admin bootstrap/settings hot reads only.
- [x] `D-TSO-02` Snapshot/read model lives in a dedicated derived document/collection.
- [x] `D-TSO-03` Rebuild is async, event-driven, and atomic.
- [x] `D-TSO-04` Reads fail soft to the last valid snapshot when rebuild fails.
- [x] `D-TSO-05` Freshness is trigger/version-driven, not arbitrary-TTL driven on the backend.
- [x] `D-TSO-06` Minimum observability is mandatory.
- [x] `D-TSO-07` Trigger catalog must cover the chosen rollout boundary explicitly.
- [x] `D-TSO-08` The current `/api/v1/environment` contract is preserved in the first rollout.
- [x] `D-TSO-09` The approved `/environment` snapshot scope is full bootstrap payload, not settings-only.
- [x] `D-TSO-10` Host/origin-sensitive fields are resolved via a thin overlay over the snapshot, not full live aggregation.
- [x] `D-TSO-11` The no-TTL model requires a canonical tenant-scoped full-rebuild repair path for missing snapshots, persistent version drift, dropped-trigger suspicion, or rebuild failure, with before/after recovery evidence captured explicitly.
- [x] `D-TSO-12` The first rollout is behavior-preserving behind the existing public contract; backing-read-path changes do not count as a public-contract change unless payload shape or externally observable semantics change.
- [x] `D-TSO-13` Snapshot cutover is not approval-clean without explicit parity validation between current live output and snapshot-backed output across representative host/origin contexts plus last-valid fallback behavior.

## Questions To Close
- [ ] Does tenant-admin `environment-snapshot` become a direct consumer of the same derived document or a diagnostics view over the same rebuild metadata?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `/api/v1/environment` and tenant-admin settings/bootstrap routes are the dominant hot-read consumers that justify this slice. | `foundation_documentation/system_roadmap.md`; `foundation_documentation/modules/tenant_admin_module.md`; current TODO framing | The rollout boundary will need to change before implementation planning. | `Medium` | `Keep as Assumption` |
| `A-02` | Settings-kernel source-of-truth surfaces are already stable enough that introducing a derived read model does not require another kernel redesign first. | `TODO-v1-settings-kernel-package.md`; `TODO-v1-tenant-settings-unification.md`; `system_roadmap.md` | This TODO would need to shrink back toward kernel stabilization instead of read-path materialization. | `High` | `Keep as Assumption` |
| `A-03` | Async rebuild orchestration can be introduced without redefining tenant settings UI or broad infrastructure architecture in the same slice. | Current TODO framing + release-hardening objective | The work would need a larger architecture-mode decision, not a bounded store-release slice. | `Medium` | `Keep as Assumption` |
| `A-04` | The remaining request-sensitive overlay can stay thin enough that the first rollout still removes the material live aggregation cost from `/api/v1/environment`. | Current resolver code shape + approved decision to preserve the public contract while changing only the backing read path. | The slice would need either a deeper contract split or a broader runtime redesign. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/**`
- `foundation_documentation/system_roadmap.md`
- `laravel-app/app/**`
- `laravel-app/routes/**`
- `laravel-app/tests/**`
- `flutter-app/lib/infrastructure/**`

### Ordered Steps
1. Inventory the exact hot-read consumers inside the approved first rollout boundary (`/api/v1/environment` + tenant-admin bootstrap/settings hot reads).
2. Freeze the current `/api/v1/environment` contract inventory and the behavior-preserving contract stance for the first rollout so optimization does not imply silent payload/semantic drift.
3. Define the derived snapshot schema, ownership boundary, and rebuild trigger catalog for that boundary, including the full approved bootstrap payload rather than a settings-only subset.
4. Define atomic rebuild, fail-soft read behavior, freshness/version metadata, and the thin host/origin-sensitive overlay that remains outside the stored snapshot.
5. Freeze the canonical full-rebuild repair contract for missed triggers, dropped events, missing snapshots, and rebuild failures, including escalation conditions and operator-visible recovery evidence.
6. Add explicit parity-validation coverage comparing current live output and snapshot-backed output across approved host/origin contexts and last-valid fallback behavior before cutover is treated as safe.
7. Map approved consumers to the new snapshot so they stop live aggregation work on hot paths while preserving the current public contract.
8. Promote the decision set into module/docs surfaces and package bounded implementation tracks for orchestration.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** read-model regressions often stay invisible until runtime unless query/read-path, fallback, and stale-snapshot behavior are asserted explicitly.
- **Fail-first target(s) (when required):** Laravel feature/integration coverage for rebuild triggers, canonical full-rebuild recovery, last-valid snapshot fallback, and contract-parity between current/live and snapshot-backed outputs across representative host/origin contexts; Flutter/backend-consumer parsing tests where payloads become versioned/derived.

### Runtime / Rollout Notes
- Rebuild operations must not block tenant reads.
- Snapshot replacement must be atomic.
- The first rollout preserves the current `/api/v1/environment` payload shape; optimization happens behind the contract.
- Host/origin-sensitive values may be finalized at read time, but only through a thin overlay over the derived snapshot.
- Missed-trigger or rebuild-failure suspicion must converge on the canonical tenant-scoped full-rebuild repair path instead of leaving the system indefinitely on stale last-known-good data.
- Consumer rollout should remain boundary-driven so performance work does not become an unbounded repo sweep.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [ ] Architecture
- [ ] Code Quality
- [ ] Tests
- [ ] Performance
- [ ] Security
- [ ] Elegance
- [ ] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-TSO-01`
  - **Severity:** `high`
  - **Evidence:** current hot-read consumers already include `/api/v1/environment` and tenant-admin settings/bootstrap surfaces, while the settings kernel is a write-model/kernel foundation rather than a dedicated read-model optimization layer.
  - **Why it matters now:** if the project keeps expanding live aggregation on launch-critical reads, release hardening will accumulate hidden query growth and runtime coupling instead of converging toward a stable read model.
  - **Option A (Recommended):** Introduce one derived materialized read model for the approved hot-read boundary while keeping source-of-truth ownership separate.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Keep optimizing each hot read locally with more live aggregation/caching tweaks and no canonical derived snapshot.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `mixed`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Accept current live aggregation growth until post-release.
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `regresses`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

- **Issue ID:** `ARCH-TSO-02`
  - **Severity:** `medium`
  - **Evidence:** without a frozen rollout boundary, "tenant settings optimization" can expand into every consumer, every namespace, and unrelated UI/runtime work.
  - **Why it matters now:** orchestration cannot stay bounded if the first slice is not explicit.
  - **Option A (Recommended):** approve a narrow first boundary for the materialized read model, then expand intentionally in later slices.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** attempt full consumer coverage in one cycle.
    - **Effort:** `high`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `mixed`
    - **Structural soundness impact:** `mixed`
  - **Option C (Do Nothing):** leave boundary implicit and decide while implementing.
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] The derived snapshot becomes a second write surface instead of a read-only projection.
- [ ] Rebuild failure blocks tenant reads instead of serving the last valid snapshot.
- [ ] Dropped events, missing trigger coverage, or persistent version drift leave the system stale with no deterministic repair path.
- [ ] The first slice silently expands beyond the approved hot-read boundary.
- [ ] Snapshot-backed output diverges from the current `/api/v1/environment` result on a host/origin variant and the cutover proceeds without an explicit parity gate catching it.
- [ ] Consumer payloads diverge because some hot paths still aggregate live state while others use the snapshot.
- [ ] The implementation materializes only `settings.*` and leaves `profile_types`, branding, or domain contributors live, producing only partial impact on `/api/v1/environment`.
- [ ] Request-sensitive overlay logic grows into a second hidden aggregator and erodes most of the expected read-path gain.

### Residual Unknowns / Risks
- [ ] The exact route-level inventory inside the approved narrow boundary is not yet frozen in one authoritative list.
- [ ] The final role of tenant-admin `environment-snapshot` still needs technical closure.
- [ ] The precise split between snapshot-owned fields and thin overlay-owned host/origin fields still needs implementation-level freeze.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `correctness`, `performance`, `structural-soundness`

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo <todo-path> [--json-output <artifact-path>]`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `5aedb7d7bd6d` (`2026-04-20`)

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-checks the TODO complexity section. |
| `blast_radius` | `cross-stack` | Laravel runtime + Flutter/bootstrap consumers are both affected. |
| `behavioral_change_or_bugfix` | `yes` | Read-path behavior changes materially even if source-of-truth writes stay the same. |
| `changes_public_contract` | `no` | First rollout is explicitly behavior-preserving behind the existing payload contract; sourcing changes stay internal to the read path. |
| `touches_auth_or_tenant` | `yes` | Tenant-scoped bootstrap/settings consumers are central to the slice. |
| `touches_runtime_or_infra` | `yes` | Async rebuild jobs/runtime freshness behavior are in scope. |
| `touches_tests` | `yes` | Read-path, rebuild, and fallback tests are mandatory. |
| `critical_user_journey` | `yes` | `/api/v1/environment` and related bootstrap reads are user-visible when this hardening slice is scheduled. |
| `release_or_promotion_critical` | `no` | Reclassified to post-release hardening on 2026-04-30; not a blocker for the current Android release gate. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-TSO-01` is high severity. |
| `explicit_three_lane_request` | `no` | Triple external audit is not explicitly required right now. |

### Derived Audit Floor
- `Critique`: `required` before `APROVADO` via `wf-docker-independent-critique-method`.
- `Security review`: `required` before completion via `security-adversarial-review`.
- `Performance/concurrency`: `required` via `wf-docker-performance-concurrency-validation-method`.
- `Verification debt`: `required` before completion via `verification-debt-audit`.
- `Test-quality audit`: `required` before completion via `wf-docker-independent-test-quality-audit-method`.
- `Final review`: `required` before completion via `wf-docker-independent-final-review-method`.
- `Triple review`: `required` before completion via `audit-protocol-triple-review` and additive only; it does not replace critique.

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** The TEACH audit floor classified this TODO as expanded-risk because it touches tenant/runtime-sensitive hot reads, public contracts, and a critical release bootstrap path.
- **Impact signals in scope:** `cross-module blast radius`, `public contract/schema/api`, `runtime/queue/realtime/ingress`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `assumptions preview`, `execution plan summary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `pending post-implementation`
- **Critique lenses:** `correctness`, `performance`, `structural-soundness`, `risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** integrated three critique corrections before execution approval:
  - froze the canonical full-rebuild repair path for the no-TTL snapshot model (`D-TSO-11`; ordered step `5`)
  - aligned the audit baseline with the approved behavior-preserving contract stance (`D-TSO-12`; trigger `changes_public_contract=no`)
  - made contract-parity validation an explicit pre-cutover gate (`D-TSO-13`; ordered step `6`)
- **Evidence / reference:** `.delphi_orchestration/orch-20260420/reviews/tenant-settings/critique/merge.md`
