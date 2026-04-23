# TODO (Store Release): Laravel Job/Scheduler Canonical Guardrails

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Active
**Owners:** Laravel Team
**Objective:** Establish and enforce canonical background-execution guardrails so recurring Jobs/Schedulers never own hidden business rules, never mutate aggregates outside approved domain services, and never run broad full-scan selection in steady-state runtime.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `direct session decision 2026-04-23`
- **Dependency role:** first execution slice for the current Store Release follow-up wave; this TODO must land before `foundation_documentation/todos/active/store_release_android/TODO-store-release-event-occurrence-transactional-consistency-and-reconcile-removal.md` and before final recut of the remaining Store Release usability TODOs.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `C-01-pre-blocker`
- **Why this is the right current slice:** A concrete runtime bug exposed a broader architectural risk: recurring background paths can currently hide non-canonical read/mutation rules and broad scans that no product flow sees until data degrades. Before resolving individual blockers, the system needs a frozen guardrail for how Jobs/Schedulers are allowed to read and mutate domain state.
- **Direct-to-TODO rationale:** The product/architecture direction is closed in-session: recurring background execution must be orchestration-only, must use canonical Application/Domain ownership, and must not rely on full sweeps. Remaining work is execution-local audit, refactor, and enforcement.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Approved pre-blocker for the current Store Release follow-up wave. Hidden background-read/write violations may still exist outside the already identified Event occurrence reconcile defect, so this guardrail TODO executes before blocker-specific fixes.`
- **Next exact step:** Inventory recurring Jobs/Schedulers, add fail-first structural coverage for forbidden patterns, move any hidden selection/mutation rules into canonical Application services, and refactor recurring runtime paths to comply before starting blocker-specific repair TODOs.

## Package-First Assessment

- **Status:** completed before orchestration.
- **Queries run:** `job scheduler guardrails`, `laravel background mutation`, `selection service`
- **Relevant packages found:** no new package boundary is required; ownership remains inside existing Laravel host/application services plus affected packages such as `belluga_events`, `belluga_map_pois`, and any other module with recurring background execution.
- **Decision:** implement guardrails in the existing Laravel application/package boundaries and enforce them through shared tests/scripts rather than by introducing a new package.
- **Rationale:** this is an ownership and enforcement correction across existing background execution paths, not a new product capability.

## Constraint Notes

- **Active constraint:** `The current architecture still allows recurring background paths to hide business rules and broad scans outside canonical controller/domain paths.`
- **Constraint rationale:** `Controllers already tend to delegate to Query/Management services, but recurring jobs are inconsistent: one path already scans every tenant Event and rebuilds occurrence writes from its own partial shape; other jobs are closer to the approved pattern but still keep mutation or selection semantics inside the job class.`
- **Clearance path:** `Freeze and implement the guardrails, fix every violation uncovered by the inventory, then proceed to the transaction-consistency blocker and the remaining functional TODOs.`
- **Owner / source:** 2026-04-23 architectural review after occurrence-programming persistence investigation.
- **Last confirmed truth:** `The current recurring scheduler inventory mixes good and bad patterns. `AgendaController`, `AccountProfilesController`, and `DiscoveryFiltersController` already show the intended thin-controller shape by delegating to canonical Query/Management/Catalog services. `RefreshExpiredEventMapPoisJob` is closer to the approved background shape because it selects only expired records and delegates to projection services. `EventOccurrenceReconciliationService`, however, performs a full tenant sweep and rebuilds occurrence writes from its own partial shape. `PublishScheduledEventsJob` narrows selection correctly, but still keeps the publication mutation path inside the job instead of calling an explicit canonical command/service path. The guard must therefore cover both hidden write ownership and hidden read-selection ownership.`

## Scope

- [x] Freeze the architectural rule that recurring Jobs/Schedulers are orchestration-only and must not own hidden business read/write rules.
- [x] Audit all recurring scheduler entries and recurring queued jobs that mutate or perform domain-critical reads in tenant runtime.
- [x] Classify each recurring path as `compliant`, `needs-selection-refactor`, `needs-mutation-refactor`, `needs-both`, or `manual-repair-only`.
- [x] Move background selection rules into canonical `Application/**` query/reader/selection services or existing canonical query services where appropriate.
- [x] Move background mutation rules into canonical `Application/**` management/command/projection/domain services.
- [x] Require recurring background mutation flows to use the same canonical domain write path as first-class product mutations whenever they touch the same aggregate.
- [x] Forbid recurring scheduler full scans over aggregate collections; recurring runtime selection must be narrow, criteria-based, and stream/chunk based where iteration is required.
- [x] Allow full scans only for explicit manual repair/backfill commands, never for steady-state scheduler runtime, and require cursor/chunk semantics plus explicit documentation when they exist.
- [x] Add enforcement so new recurring Jobs/Schedulers cannot reintroduce direct aggregate mutation or hidden full-scan selection inside `Jobs/`/`Console/`.
- [x] Fix every currently discovered violation that this audit surfaces, not just the already known Event occurrence reconcile case.
- [x] Route the Event occurrence reconcile defect and any similar aggregate-consistency defects through the follow-up blocker TODOs after this guardrail lands.

## Out of Scope

- [x] Eliminating legitimate async processing as a category remains out of scope; this TODO governs ownership and selection semantics, not the existence of queues.
- [x] One-off/local operational scripts outside application runtime remain out of scope unless promoted into recurring scheduler/runtime behavior.
- [x] Public/admin UI behavior changes remain out of scope except where runtime bugs discovered by this audit force downstream blocker TODOs.
- [x] Replacing every specialized reader with one shared generic query abstraction remains out of scope. The requirement is canonical ownership, not forced class unification.

## Canonical Decisions

- [x] `D-JG-01` A recurring Job/Scheduler is an orchestration surface only. It may own timing, retry/backoff, chunk/cursor iteration, dispatch, and telemetry, but it must not own hidden domain/business rules.
- [x] `D-JG-02` Any recurring background mutation of domain state must execute through a canonical Application/Domain management/command/projection service. Direct aggregate mutation semantics inside `Jobs/` or scheduler closures are forbidden.
- [x] `D-JG-03` Transaction ownership belongs to the canonical write path, not to the recurring job as an ad hoc implementation detail.
- [x] `D-JG-04` Recurring background reads must use canonical Application-level query/reader/selection ownership. They do not have to reuse the exact same class as HTTP controllers, but they cannot hide business rules in job-private helpers or scheduler-local query code.
- [x] `D-JG-05` Recurring scheduler/runtime paths must not full-scan aggregate collections in steady state. Selection must be criteria-based, operationally narrow, and stream/chunk based where iteration is needed.
- [x] `D-JG-06` Full scans are allowed only in explicit manual repair/backfill commands, never in recurring scheduler runtime, and even there must use cursor/chunk semantics plus documented rationale.
- [x] `D-JG-07` Any hidden violation uncovered by the guardrail audit is part of this TODO's implementation scope; it must not be deferred silently as an "unrelated" invisible issue.

## Definition of Done

- [x] Every recurring scheduler entry and recurring queued job that mutates or performs domain-critical reads is inventoried and classified.
- [x] No recurring job or scheduler path mutates an aggregate through job-owned business logic or direct write semantics outside canonical Application/Domain ownership.
- [x] No recurring scheduler path performs a broad `all()/get()` style full sweep over aggregate collections as steady-state behavior.
- [x] Canonical selection ownership exists for each recurring background path that needs non-trivial selection logic.
- [x] Canonical mutation ownership exists for each recurring background path that needs non-trivial mutation logic.
- [x] Any explicit manual repair/backfill path is clearly separated from recurring scheduler runtime and documented as such.
- [x] Structural guard coverage exists so new recurring job/scheduler code cannot reintroduce hidden mutation or hidden full-scan selection patterns unnoticed.
- [x] The transaction-consistency blocker TODO and the remaining Store Release TODOs can proceed on top of this frozen guardrail baseline.

## Validation Steps

- [x] Laravel recurring-background inventory test or script captures all scheduled recurring commands and recurring queued jobs in scope.
- [x] Structural enforcement test/script fails when `Jobs/` or recurring scheduler closures contain forbidden direct aggregate write patterns.
- [x] Structural enforcement test/script fails when recurring background runtime introduces forbidden broad-scan patterns without an approved manual-repair classification.
- [x] Focused tests prove compliant recurring paths delegate selection to canonical readers/selection services rather than job-private business-rule helpers.
- [x] Focused tests prove compliant recurring mutation paths delegate to canonical management/command/projection services.
- [x] The known Events recurring violations are either fixed here or routed explicitly into the immediately following blocker TODO with the guardrail already enforced.

## Required Test Matrix

| Item ID | Requirement / Gap | Positive Validation | Negative / Regression Validation | Required Suites | Final Runtime Evidence |
| --- | --- | --- | --- | --- | --- |
| `JG-01` | Inventory recurring background runtime. | Test/script enumerates recurring scheduler commands and recurring queued jobs in scope. | Missing inventory row for a recurring path is a failure. | Laravel architecture/inventory script or test. | Not user-visible; inventory evidence is sufficient. |
| `JG-02` | No hidden mutation ownership in recurring jobs. | Recurring mutation jobs delegate to canonical Application/Domain services. | Direct aggregate mutation or job-owned business-write rules inside `Jobs/` or recurring scheduler closures fail the guard. | Structural enforcement test/script + focused unit tests. | Not user-visible; backend suite evidence is sufficient. |
| `JG-03` | No hidden selection ownership in recurring jobs. | Recurring selection logic lives in canonical Application query/reader/selection services. | Job-private helpers or scheduler-local business-rule selection fail the guard. | Structural enforcement test/script + focused unit tests. | Not user-visible; backend suite evidence is sufficient. |
| `JG-04` | No steady-state broad full scans in recurring runtime. | Recurring paths select only narrow candidate sets and iterate via cursor/chunk where needed. | Broad `get()/all()` aggregate sweeps in recurring runtime fail the guard. | Structural enforcement test/script + focused job/service tests. | Not user-visible; backend suite evidence is sufficient. |
| `JG-05` | Manual repair/backfill lane is explicit. | Any allowed full-scan repair path is manual-only, documented, and chunk/cursor based. | A recurring scheduler using the same path is a failure. | Laravel command/service tests. | Not user-visible; backend suite evidence is sufficient. |
| `JG-06` | Existing hidden violations are surfaced and fixed. | The audit output classifies each violation and the code/doc/test evidence closes it. | Any discovered hidden violation left implicit or undocumented is a failure. | Audit artifact + focused Laravel suites. | Not user-visible; backend suite evidence is sufficient. |

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-JG-*` decisions above are frozen for execution. Recurring Jobs/Schedulers must remain orchestration-only; canonical Application/Domain services own both business selection and mutation semantics; recurring steady-state full scans are forbidden; hidden violations uncovered by the audit must be fixed in this same TODO.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** build the recurring background inventory plus structural fail-first guard, then refactor/fix every violation found in recurring runtime, then hand off the Events aggregate-specific repair to the transaction-consistency blocker TODO.
- **Sequencing note:** this TODO executes first in the current Store Release follow-up wave. No blocker-specific or functional TODO should start implementation before this guardrail baseline lands.
