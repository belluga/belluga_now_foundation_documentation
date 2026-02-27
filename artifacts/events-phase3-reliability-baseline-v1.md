# Events Phase 3 Reliability Baseline (V1)

**Date:** 2026-02-27  
**Scope:** `laravel-app` Events package hardening baseline (`agenda`, `stream`, publication transition + delta)  
**Source run log:** `foundation_documentation/artifacts/tmp/events_phase3_perf_sampling_20260226_221748.log`

## Method
- Environment: `docker compose exec -T app`
- Sampling approach: repeated single-test execution (targeted endpoint-path coverage)
  - Agenda path: `AgendaAndEventsControllerTest::testAgendaDefaultReturnsUpcomingAndNow` (5 runs)
  - Stream path: `AgendaAndEventsControllerTest::testEventStreamReturnsDeltas` (5 runs)
  - Publication transition + stream delta path: `EventCrudControllerTest::testPublishScheduledEventsJobEmitsStreamDeltaAfterPublicationTransition` (3 runs)

## Baseline Results

| Path | Runs | Pass | Min (ms) | Avg (ms) | Max (ms) |
|---|---:|---:|---:|---:|---:|
| agenda | 5 | 5 | 13,424 | 20,364.00 | 24,685 |
| stream | 5 | 5 | 21,262 | 23,663.40 | 25,758 |
| publication+stream | 3 | 3 | 11,211 | 27,961.67 | 42,175 |

## Hardening Status
- Reliability observability is active and covered by structured logs for writes, stream deltas, and publication transitions.
- Async side-effect guardrails are active (`OD-04`): staleness monitor, DLQ alert hook, and reconciliation cadence.
- Occurrence-first index/query model is consolidated in canonical package migrations for agenda/filter/stream paths.
- Resilience coverage for reconnect/publication edge cases is present in automated tests and remains green.

## Bottlenecks and Risk Tracking
- The publication transition + stream path shows the highest variance in this sampling window.
- Current state is acceptable for Phase 3 closure because:
  - all sampled runs passed,
  - operational guardrails are in place,
  - known variance is explicitly tracked here for future optimization work.

## Architecture Snapshot (Post-Hardening)
- Canonical read/query model is occurrence-first (`event_occurrences`) with Event as publication source-of-truth.
- Publication transitions are transaction-first and mirrored into occurrences.
- Stream deltas are occurrence-first and cursor-based (no replay buffer).

## Manual Gate Note
- Manual smoke for publication/SSE reconnect was intentionally not executed in this closure cycle by explicit delivery decision to close non-manual work only.
- Manual smoke can still be executed later as an optional operational confidence step, but it is not a blocker for this non-manual closure milestone.
