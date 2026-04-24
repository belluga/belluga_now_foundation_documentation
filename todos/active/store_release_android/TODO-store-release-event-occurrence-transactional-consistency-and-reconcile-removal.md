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

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Local Laravel validation complete on the current orchestrator branch. SR-D still owes the downstream public/admin runtime recut on top of this backend blocker baseline, but TX0 itself is no longer waiting on scheduler/runtime guardrail work.`
- **Next exact step:** Recut `SR-D` runtime evidence against this landed backend baseline; no further TX0 code work is expected unless that recut uncovers a new defect.

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
- **Last confirmed truth:** `events:occurrences:reconcile` is already absent from recurring scheduler runtime and remains manual-only as `events:occurrences:repair`. The remaining aggregate-consistency defect is now fixed in the Events package: `EventManagementService` and `EventPublicationManagementService` delegate aggregate mutation to `EventAggregateWriteService`; `EventTransactionRunner` is the canonical tenant transaction owner; `EventOccurrencePayloadSnapshotService` is the canonical schedule/occurrence-shape source for update-without-schedule-mutation and for manual repair; and `EventOccurrenceReconciliationService` no longer reconstructs occurrences from `{date_time_start,date_time_end}` only. Manual/repair flows now reuse the same canonical aggregate write path as normal writes, so occurrence-owned `own_event_parties`, `own_linked_account_profiles`, and `programming_items` survive repair/resync instead of being degraded by partial-shape writes.`

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
- **Implementation blocker:** `none`
- **Open product/contract gaps:** `none`
- **First orchestration slice:** hand the landed backend baseline to `SR-D` for final public/admin runtime recut.
- **Sequencing note:** this TODO is the backend blocker that must be in place before the final `SR-D` acceptance/polish pass can be trusted.

## Implementation Evidence

- **Canonical write-path refactor landed:**
  - `laravel-app/packages/belluga/belluga_events/src/Application/Transactions/EventTransactionRunner.php`
  - `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventAggregateWriteService.php`
  - `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventOccurrencePayloadSnapshotService.php`
  - `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
  - `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventPublicationManagementService.php`
  - `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventOccurrenceReconciliationService.php`
- **Key behavioral correction:** manual repair now rehydrates occurrence payloads from the canonical persisted occurrence shape (`own_event_parties` + `programming_items`) and routes the rewrite through the same aggregate write path used by normal event CRUD/publication flows.
- **Fail-first / regression coverage added:**
  - `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
  - `laravel-app/tests/Unit/Events/EventAggregateWriteOwnershipTest.php`
- **Validation evidence captured:**
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)`
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php tests/Feature/Architecture/RecurringBackgroundGuardrailsTest.php tests/Feature/Events/SchedulerBootstrapTest.php` -> `6 passed (64 assertions)`
  - `docker compose exec -T app php artisan schedule:list` -> exit `0`; recurring runtime lists `events:publication:publish_scheduled`, `events:async:monitor`, `map_pois:cleanup_orphaned`, `events:map_pois:refresh_expired`, and `api-security:abuse-signals:prune`, with no `events:occurrences:reconcile`.
- **Harness note:** combined multi-suite Laravel runs still intermittently hit unrelated Mongo migration/index-drop failures (`path_1`, `document_1`, or `database is in the process of being dropped`) before some test bodies start. The isolated reruns listed above are the authoritative TX0 evidence because they exercise the blocker-specific code paths without that harness race.

## Completion Evidence Matrix

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | Definition of Done | `events:occurrences:reconcile` no longer exists in the scheduled runtime bootstrap. | Scheduler bootstrap test + direct console evidence | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/SchedulerBootstrapTest.php --filter='test_console_schedule_registers_current_event_dispatches_and_keeps_ticketing_jobs_removed'` -> `1 passed`; `docker compose exec -T app php artisan schedule:list` -> exit `0` with no `events:occurrences:reconcile` | Laravel scheduler runtime | passed | Confirms recurring runtime exclusion while manual repair command remains registered. |
| `DOD-02` | Definition of Done | Event create/update/delete consistency with occurrence mirrors is fully owned by transaction-backed domain writes. | Feature rollback tests + source ownership test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)`; `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php tests/Feature/Architecture/RecurringBackgroundGuardrailsTest.php tests/Feature/Events/SchedulerBootstrapTest.php` -> `6 passed (64 assertions)` | tenant-safe Laravel integration runtime and Events package source | passed | Shared `EventAggregateWriteService` + `EventTransactionRunner` own create/update/delete mutation consistency. |
| `DOD-03` | Definition of Done | Scheduled publication mirroring remains transaction-backed and does not depend on periodic occurrence reconcile. | Feature rollback test + scheduler guardrails | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)`; `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php tests/Feature/Architecture/RecurringBackgroundGuardrailsTest.php tests/Feature/Events/SchedulerBootstrapTest.php` -> `6 passed (64 assertions)` | tenant-safe Laravel integration runtime and architecture guard lane | passed | Publication now flows through the shared aggregate write path and not through recurring reconcile. |
| `DOD-04` | Definition of Done | No periodic background path can degrade or erase occurrence-scoped `programming_items`. | Feature repair regression test + scheduler evidence | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)`; scheduler evidence from `DOD-01` | tenant-safe Laravel integration runtime and scheduler runtime | passed | Manual repair now preserves `programming_items`, and no recurring scheduler path remains to rewrite them opportunistically. |
| `DOD-05` | Definition of Done | No periodic background path can degrade or erase occurrence-scoped own related profiles. | Feature repair regression test + scheduler evidence | same evidence as `DOD-04` | tenant-safe Laravel integration runtime + scheduler runtime | passed | Manual repair now preserves `own_event_parties` / `own_linked_account_profiles`, and recurring runtime cannot degrade them because reconcile is no longer scheduled. |
| `DOD-06` | Definition of Done | Any remaining repair entry point is explicit/manual or targeted and reuses canonical domain write logic. | Scheduler bootstrap test + ownership test + source inspection | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/SchedulerBootstrapTest.php --filter='test_console_schedule_registers_current_event_dispatches_and_keeps_ticketing_jobs_removed'` -> `1 passed`; `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php` -> `1 passed` | Laravel scheduler/runtime + Events package source | passed | `events:occurrences:repair` remains manual-only, and `EventOccurrenceReconciliationService` delegates to the shared aggregate write service. |
| `DOD-07` | Definition of Done | Tests prove rollback when Event/EventOccurrence writes fail mid-flight. | Feature rollback tests | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)` | tenant-safe Laravel integration runtime via safe runner | passed | All four mid-flight failure lanes now have explicit regression coverage. |
| `DOD-08` | Definition of Done | Tests prove the scheduler no longer registers the removed reconcile job. | Scheduler bootstrap test + direct console evidence | same evidence as `DOD-01` | Laravel scheduler runtime | passed | Both the test and the live console listing show the removed schedule is absent. |
| `DOD-09` | Definition of Done | SR-D can depend on this TODO as the blocker for final runtime acceptance. | TODO/module doc evidence + blocker-specific validation bundle | This TODO `Delivery Status Canon`, `Implementation Evidence`, and `Decision Adherence Validation`; `foundation_documentation/modules/events_module.md`; blocker validation commands listed above | foundation documentation + validated Laravel baseline | passed | TX0 is now `Local-Implemented`; SR-D still owes the downstream runtime recut, but it no longer depends on more TX0 code work. |
| `VAL-01` | Validation Steps | Laravel feature/integration test for Event create rollback when occurrence sync fails mid-flight. | Feature test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter='test_event_create_rolls_back_when_occurrence_sync_fails_mid_flight'` -> `1 passed` | tenant-safe Laravel integration runtime via safe runner | passed | Verifies create rollback against forced sync failure. |
| `VAL-02` | Validation Steps | Laravel feature/integration test for Event update rollback when occurrence sync fails mid-flight. | Feature test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)` | tenant-safe Laravel integration runtime via safe runner | passed | Verifies update rollback against forced sync failure. |
| `VAL-03` | Validation Steps | Laravel feature/integration test for Event delete rollback when occurrence soft-delete sync fails mid-flight. | Feature test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)` | tenant-safe Laravel integration runtime via safe runner | passed | Verifies delete rollback against forced mirror failure. |
| `VAL-04` | Validation Steps | Laravel feature/integration test for scheduled publication rollback when occurrence publication mirror fails mid-flight. | Feature test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)` | tenant-safe Laravel integration runtime via safe runner | passed | Verifies publication rollback against forced mirror failure. |
| `VAL-05` | Validation Steps | Laravel scheduler bootstrap test proving `events:occurrences:reconcile` is absent while legitimate remaining jobs still exist. | Scheduler bootstrap test + direct console evidence | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/SchedulerBootstrapTest.php --filter='test_console_schedule_registers_current_event_dispatches_and_keeps_ticketing_jobs_removed'` -> `1 passed`; `docker compose exec -T app php artisan schedule:list` -> exit `0` | Laravel scheduler runtime | passed | Remaining legitimate jobs are still registered while reconcile is absent. |
| `VAL-06` | Validation Steps | Laravel feature tests proving occurrence-scoped `programming_items` and `own_event_parties` survive normal write/read cycles after reconcile removal. | Feature repair regression test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php [TX0 isolated rollback/repair rerun]` -> `5 passed (27 assertions)` | tenant-safe Laravel integration runtime via safe runner | passed | Confirms both occurrence-scoped fields survive manual repair/resync. |
| `VAL-07` | Validation Steps | Laravel structural/service tests proving the approved Event/EventOccurrence write path executes through shared transaction ownership. | Source/architecture tests | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php tests/Feature/Architecture/RecurringBackgroundGuardrailsTest.php tests/Feature/Events/SchedulerBootstrapTest.php` -> `6 passed (64 assertions)` | Events package source and architecture guard lane | passed | Structural proof now covers shared transaction ownership and repair delegation. |
| `VAL-08` | Validation Steps | Laravel negative tests proving no background reconcile sweep remains able to rewrite all events opportunistically. | Scheduler bootstrap test + direct console evidence + guardrail test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventAggregateWriteOwnershipTest.php tests/Feature/Architecture/RecurringBackgroundGuardrailsTest.php tests/Feature/Events/SchedulerBootstrapTest.php` -> `6 passed (64 assertions)`; `docker compose exec -T app php artisan schedule:list` -> exit `0` | Laravel scheduler/runtime and architecture guard lane | passed | There is no recurring reconcile sweep left in runtime; the remaining repair sweep is explicit/manual and cursor-based only. |

## Decision Adherence Validation

| Decision ID | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-TX-01` | `Adherent` | `EventAggregateWriteService.php`, `EventTransactionRunner.php`, rollback feature tests (`TX-02..TX-05`) | Event/EventOccurrence consistency now lives on one transaction-owned aggregate write path. |
| `D-TX-02` | `Adherent` | `SchedulerBootstrapTest` targeted pass + `php artisan schedule:list` output | Recurring scheduler runtime does not register `events:occurrences:reconcile`. |
| `D-TX-03` | `Adherent` | `routes/console.php` manual `events:occurrences:repair`; scheduler bootstrap targeted pass | Remaining repair entry is explicit/manual only. |
| `D-TX-04` | `Adherent` | `EventOccurrenceReconciliationService.php`, `EventOccurrencePayloadSnapshotService.php`, `EventAggregateWriteOwnershipTest.php` | Repair path no longer reconstructs occurrences from partial shape or bypasses canonical aggregate writes. |
| `D-TX-05` | `Adherent` | `test_manual_occurrence_repair_preserves_occurrence_owned_profiles_and_programming_items` | Occurrence-owned parties and programação survive repair/resync. |
| `D-TX-06` | `Adherent` | rollback feature tests + ownership source test | Delivery proof now includes both semantic rollback coverage and structural write-path ownership proof. |

## Residual Risks

- `SR-D` still needs its downstream public/admin runtime recut to convert this backend blocker from `Local-Implemented` into final slice-level acceptance.
- Combined fresh-process Laravel suite runs remain noisy because of unrelated Mongo migration/index-drop behavior; isolated reruns are currently the reliable local evidence lane for TX0.
- No remaining TX0-local risk is known in the repaired Events aggregate path, but any new issue found by the SR-D recut should be treated as fresh evidence rather than assumed fallout from the old partial-shape repair bug.
