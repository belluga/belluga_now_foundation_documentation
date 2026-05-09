# Triple Audit Round 03 Resolution

Derived artifact. Non-authoritative. Record Delphi adjudication, resolution decisions, validation evidence, and remaining blockers before opening another audit round.

## Status

Choose one when recording with `record-resolution`:

- `resolved`: all material findings were fixed and required validation passed.
- `accepted-debt`: remaining findings are explicitly accepted as non-blocking debt with owner/rationale.
- `blocked`: required evidence or fixes are still blocked; `next-round` must not proceed.

## Adjudication

- Lane recommendations were additive. Test-quality was clean; elegance and performance each found one valid high-severity release risk.
- `ELEGANCE-R03-001` was valid: mixed update payloads with existing identified occurrences and a new unidentified occurrence still allowed index fallback, so a new row inserted before existing rows could bind to an existing document.
- `PERF-R03-001` was valid: occurrence-id filtering was contractually present, but still entered after broad geo/search pipeline stages.
- Both findings were fixed in Laravel code and covered by focused regression tests.

## Resolution Matrix

| Finding | Decision | Resolution / Rationale | Evidence |
| --- | --- | --- | --- |
| `ELEGANCE-R03-001` | `resolved` | `EventOccurrenceSyncService` and `EventManagementService` now disable index fallback whenever the incoming update payload contains any occurrence identity. Unidentified rows in mixed payloads create new occurrence documents instead of consuming existing rows by index. New occurrence slug generation now avoids existing and claimed slugs, so inserting before existing occurrences does not collide with preserved slugs. | `test_event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows`; focused safe runner passed 6 tests / 43 assertions. |
| `PERF-R03-001` | `resolved` | `EventQueryService` now folds occurrence-id matching into the initial non-geo `$match` and into `$geoNear.query` for geo agenda/stream pipelines. The later occurrence-id match was removed because the id predicate is now part of the earliest executable predicate. | `test_agenda_filters_by_occurrence_ids_with_geo_parameters`, `test_agenda_filters_by_occurrence_ids_with_search_parameters`, and `test_occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages`; focused safe runner passed 6 tests / 43 assertions. |

## Validation Evidence

- Commands run:
  - `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
  - `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
  - `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
  - `docker compose exec -T app php -l tests/Feature/Events/AgendaAndEventsControllerTest.php`
  - `docker compose exec -T app php -l tests/Feature/Events/EventCrudControllerTest.php`
  - `docker compose exec -T app ./vendor/bin/pint packages/belluga/belluga_events/src/Application/Events/EventQueryService.php packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php packages/belluga/belluga_events/src/Application/Events/EventManagementService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php`
  - `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_occurrence_ids_without_walking_unrelated_events|agenda_filters_by_occurrence_ids_with_geo_parameters|agenda_filters_by_occurrence_ids_with_search_parameters|occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows'`
- Passed/failed/blocked gates:
  - PHP lint passed for all five touched PHP files.
  - Pint passed after applying style fixes.
  - Focused Laravel safe runner passed: 6 tests, 43 assertions.
- Runtime/navigation evidence:
  - No Playwright/ADB evidence is required for these two round-03 findings. They are backend query/persistence contracts covered by real Laravel feature tests.

## Open Blockers

- `none`

## Accepted Non-Blocking Debt

- `none`

## Next Audit Package Requirements

- Include this resolution artifact and the focused test evidence in the next bounded package.
- Ask the next reviewers to verify that round-03 performance/elegance findings are actually closed and not replaced by another unbounded occurrence identity or query path.
