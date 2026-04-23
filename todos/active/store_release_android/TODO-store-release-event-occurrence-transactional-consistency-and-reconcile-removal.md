# TODO (Store Release): Event Occurrence Transactional Consistency and Reconcile Removal

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Active
**Owners:** Laravel Team
**Objective:** Remove the periodic global occurrence reconcile scheduler as a normal consistency mechanism and guarantee `Event` + `EventOccurrence` consistency through canonical transaction-owned domain writes, with explicit repair tools only where operationally necessary.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`
- **Source intake:** `foundation_documentation/artifacts/tmp/event_occurrence_persistence_loss_2026-04-23.md`
- **Dependency role:** depends on `foundation_documentation/todos/active/store_release_android/TODO-store-release-laravel-job-scheduler-canonical-guardrails.md`; once that pre-blocker lands, this TODO blocks `foundation_documentation/todos/active/store_release_android/TODO-store-release-event-multi-occurrence-ux-and-authoring-model.md` final reconciliation and runtime acceptance.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `C-01-blocker`
- **Why this is the right current slice:** Runtime investigation found a likely domain-consistency defect, not a mere UI bug: occurrence-scoped `programming_items` and related-profile data can be degraded by the periodic reconcile path. This must be fixed at the domain consistency layer before the final multi-occurrence acceptance pass can be trusted.
- **Direct-to-TODO rationale:** The product decision is closed: this periodic scheduler should not be the normal consistency mechanism. The remaining work is implementation-local architecture, test strategy, and execution sequencing.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Approved blocker for SR-D final reconciliation, but execution starts only after the recurring Job/Scheduler guardrail TODO lands. The current periodic scheduler reads every tenant event and rebuilds occurrences from an incomplete shape, so final multi-occurrence acceptance cannot be considered trustworthy until this TODO lands.`
- **Next exact step:** Land the recurring Job/Scheduler guardrail TODO first, then add fail-first rollback and scheduler-removal tests, and then replace periodic occurrence reconcile with transaction-owned domain consistency and explicit/manual repair entry points only.

## Package-First Assessment

- **Status:** completed before orchestration.
- **Queries run:** `event occurrence`
- **Relevant packages found:** no separate proprietary package was returned by the package registry query; the canonical ownership remains the existing `belluga_events` package.
- **Decision:** implement inside `laravel-app/packages/belluga/belluga_events` and its host bootstrap/tests.
- **Rationale:** this is an aggregate-consistency correction inside the existing Events package boundary, not a new cross-project package surface.

## Constraint Notes

- **Active constraint:** `SR-D final acceptance is blocked by domain consistency risk.`
- **Constraint rationale:** `A scheduled reconcile currently runs every 15 minutes across all tenants/events and reconstructs occurrence payloads from only start/end fields before calling the same sync writer that persists occurrence-scoped fields such as programming and own related profiles.`
- **Clearance path:** `Land this TODO, rerun the multi-occurrence runtime matrix, then return SR-D to final reconciliation.`
- **Owner / source:** 2026-04-23 runtime investigation + code-path audit.
- **Last confirmed truth:** `EventManagementService` already performs canonical create/update/delete through tenant transactions and calls occurrence sync inside that transaction. `PublishScheduledEventsJob` also performs publication mirroring transactionally. The scheduler entry `events:occurrences:reconcile`, however, still scans all tenant events every fifteen minutes and uses `EventOccurrenceReconciliationService`, which currently resolves occurrence rows from `EventOccurrence` using only `date_time_start` and `date_time_end`. That partial shape is then sent into `EventOccurrenceSyncService::syncFromEvent(...)`, which persists `own_event_parties`, `own_linked_account_profiles`, and `programming_items` from the incoming occurrence payload. This means the periodic reconcile path is both expensive and semantically unsafe for occurrence-scoped data.`

## Scope

- [x] Decide that `Event` + `EventOccurrence` consistency is transaction-owned domain behavior, not periodic scheduler-owned repair behavior.
- [x] Remove `events:occurrences:reconcile` from the normal scheduler bootstrap.
- [x] Preserve canonical transaction-owned create/update/delete behavior for Event aggregate writes.
- [x] Preserve transaction-owned publication mirroring for scheduled publication transitions.
- [x] Ensure occurrence-scoped fields such as `own_event_parties`, `own_linked_account_profiles`, and `programming_items` are never degraded by background repair paths.
- [x] Require any remaining repair/rebuild entry point to be explicit/manual or directly targeted, never a blind full-tenant periodic sweep.
- [x] Require repair/rebuild flows to call the same canonical domain service/path used by normal writes, not a parallel partial-shape writer.
- [x] Add tests that prove atomic rollback on mid-flight failure for Event/EventOccurrence writes.
- [x] Add tests that prove scheduler removal for `events:occurrences:reconcile`.
- [x] Add tests that prove occurrence-scoped programação and related-profile data survive the corrected consistency model.
- [x] Add structural tests that prove the corrected domain path executes through shared transaction ownership rather than ad hoc best-effort write ordering.

## Out of Scope

- [x] Removing legitimate asynchronous jobs that own other concerns remains out of scope.
- [x] `MapPoi` projection jobs remain out of scope except where tests must prove they are not this blocker.
- [x] Telemetry, queue monitoring, and abuse-signal pruning remain out of scope.
- [x] Public/admin UI redesign remains out of scope; this TODO is a domain-consistency blocker.
- [x] Changing event detail/list contracts beyond what is necessary to preserve already-approved behavior remains out of scope.

## Canonical Decisions

- [x] `D-TX-01` `Event` + `EventOccurrence` consistency is canonical domain behavior and must be guaranteed by transaction-owned writes, not by a periodic global reconcile sweep.
- [x] `D-TX-02` The periodic scheduler entry `events:occurrences:reconcile` is not an approved steady-state mechanism and must be removed from normal scheduler bootstrap.
- [x] `D-TX-03` If a repair/rebuild path still exists after this TODO, it must be explicit/manual or event-targeted only. It must never run as a blind full-tenant periodic sweep.
- [x] `D-TX-04` Repair/rebuild paths must call the same canonical domain write path used by normal Event mutations. Partial-shape writes that reconstruct occurrences "their own way" are forbidden.
- [x] `D-TX-05` Occurrence-scoped fields including `own_event_parties` and `programming_items` are canonical occurrence data and must persist unchanged unless explicitly mutated by an approved write path.
- [x] `D-TX-06` Delivery proof for this TODO must include both semantic atomicity tests (rollback on failure) and structural proof that the approved domain path owns the transaction boundary.

## Definition of Done

- [x] `events:occurrences:reconcile` no longer exists in the scheduled runtime bootstrap.
- [x] Event create/update/delete consistency with occurrence mirrors is fully owned by transaction-backed domain writes.
- [x] Scheduled publication mirroring remains transaction-backed and does not depend on periodic occurrence reconcile.
- [x] No periodic background path can degrade or erase occurrence-scoped `programming_items`.
- [x] No periodic background path can degrade or erase occurrence-scoped own related profiles.
- [x] Any remaining repair entry point is explicit/manual or targeted and reuses canonical domain write logic.
- [x] Tests prove rollback when Event/EventOccurrence writes fail mid-flight.
- [x] Tests prove the scheduler no longer registers the removed reconcile job.
- [x] SR-D can depend on this TODO as the blocker for final runtime acceptance.

## Validation Steps

- [x] Laravel feature/integration test for Event create rollback when occurrence sync fails mid-flight.
- [x] Laravel feature/integration test for Event update rollback when occurrence sync fails mid-flight.
- [x] Laravel feature/integration test for Event delete rollback when occurrence soft-delete sync fails mid-flight.
- [x] Laravel feature/integration test for scheduled publication rollback when occurrence publication mirror fails mid-flight.
- [x] Laravel scheduler bootstrap test proving `events:occurrences:reconcile` is absent while legitimate remaining jobs still exist.
- [x] Laravel feature tests proving occurrence-scoped `programming_items` and `own_event_parties` survive normal write/read cycles after reconcile removal.
- [x] Laravel structural/service tests proving the approved Event/EventOccurrence write path executes through shared transaction ownership.
- [x] Laravel negative tests proving no background reconcile sweep remains able to rewrite all events opportunistically.

## Required Test Matrix

| Item ID | Requirement / Gap | Positive Validation | Negative / Regression Validation | Required Suites | Final Runtime Evidence |
| --- | --- | --- | --- | --- | --- |
| `TX-01` | Remove periodic reconcile scheduler. | `schedule:list` no longer contains `events:occurrences:reconcile`. | Remaining legitimate jobs such as scheduled publication and map projection jobs must still be present. | Laravel scheduler bootstrap tests. | Not user-visible; backend suite evidence is sufficient. |
| `TX-02` | Create is atomic. | Inject a deterministic failure after `Event` mutation begins but before occurrence sync completes; neither `events` nor `event_occurrences` persist partial state. | A partial `Event` without matching occurrence state is a failure. | Laravel integration/feature tests with transaction-capable Mongo runtime. | Not user-visible; backend suite evidence is sufficient. |
| `TX-03` | Update is atomic. | Inject a deterministic failure after `Event` changes are applied in-memory but before occurrence sync completes; stored `Event` and occurrences remain at the previous persisted state. | Updated `Event` with stale/degraded occurrences is a failure. | Laravel integration/feature tests. | Not user-visible; backend suite evidence is sufficient. |
| `TX-04` | Delete is atomic. | Inject a deterministic failure before occurrence soft-delete mirror completes; event and occurrences remain undeleted. | Partially deleted aggregate state is a failure. | Laravel integration/feature tests. | Not user-visible; backend suite evidence is sufficient. |
| `TX-05` | Scheduled publication mirror is atomic. | Inject a deterministic failure during publication mirroring; event publication state and occurrence publication mirrors remain unchanged. | Event published alone while occurrence mirrors remain stale is a failure. | Laravel integration/feature tests. | Not user-visible; backend suite evidence is sufficient. |
| `TX-06` | Occurrence-scoped programação survives corrected model. | After normal writes, reads and follow-up operations preserve `programming_items` exactly. | Any background or repair path erasing `programming_items` is a failure. | Laravel CRUD/detail tests. | Supports SR-D; final public/admin runtime evidence will be recut in SR-D after this blocker lands. |
| `TX-07` | Occurrence-scoped own related profiles survive corrected model. | After normal writes, reads and follow-up operations preserve `own_event_parties` and derived own profiles exactly. | Any background or repair path erasing occurrence-owned profiles is a failure. | Laravel CRUD/detail tests. | Supports SR-D; final public/admin runtime evidence will be recut in SR-D after this blocker lands. |
| `TX-08` | Transaction ownership is structurally explicit. | Service-level tests prove the approved write path executes through shared transaction ownership. | Ad hoc path-specific write ordering without shared transaction ownership is a regression. | Laravel unit/service tests. | Not user-visible; backend suite evidence is sufficient. |

## Decision Baseline (Frozen Before Implementation)

- [x] The resolved `D-TX-*` decisions above are frozen for execution. Implementation must remove the periodic occurrence reconcile scheduler, keep aggregate consistency in transaction-owned domain paths, preserve explicit/manual repair only where necessary, and prove both semantic atomicity and structural transaction ownership through tests.

## Orchestration Readiness

- **Ready for orchestration:** `yes`
- **Implementation blocker:** `foundation_documentation/todos/active/store_release_android/TODO-store-release-laravel-job-scheduler-canonical-guardrails.md`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** after the recurring Job/Scheduler guardrail TODO lands, add fail-first scheduler-removal and rollback tests, then refactor the Event/EventOccurrence domain write path to one shared transaction-owned consistency path, then recut SR-D runtime evidence.
- **Sequencing note:** this TODO runs immediately after the recurring Job/Scheduler guardrail TODO and must complete before the final SR-D acceptance/polish pass. It is a blocker, not an optional hardening follow-up.
