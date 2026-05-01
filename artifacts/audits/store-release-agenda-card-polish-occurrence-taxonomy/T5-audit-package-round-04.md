# T5 Audit Package Round 04 - Agenda Card Polish And Occurrence Taxonomy

Derived artifact. Non-authoritative. This is a delta-only package after round 03 findings were fixed.

## Scope

Same approved T5 Store Release slice:

- Flutter agenda/card polish, time ranges, Home Agenda status/radius chrome, Boora icon replacement/catalog, Map web full-width exception, and tenant-admin occurrence taxonomy UI.
- Laravel Events programming item `end_time`, occurrence taxonomy overrides, effective occurrence taxonomy filtering, pending occurrence-id agenda filtering, and occurrence identity-safe update semantics.

Out of scope remains contacts/friends materialization, unrelated invite lifecycle fixes, favorite refresh, deep-link host/domain rules, public/private discovery rules, and production promotion.

## Delta Since Round 03

Round 03 produced two high findings. Both were treated as blocking and fixed:

- `PERF-R03-001`: occurrence-id filters now enter the earliest backend predicate. In non-geo agenda/stream pipelines the `_id $in` predicate is combined into the initial `$match`; in geo pipelines it is combined into `$geoNear.query`.
- `ELEGANCE-R03-001`: mixed occurrence update payloads no longer let unidentified new rows bind to existing occurrence documents by index. If any incoming row has occurrence identity, index fallback is disabled for the whole sync/preservation pass. New generated occurrence slugs also avoid existing/claimed slugs when a new row is inserted before existing occurrences.

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Visible Route / Action | DTO / Encoder / Decoder Path | Evidence | Waiver |
| --- | --- | --- | --- | --- | --- |
| Laravel `programming_items[].end_time` validation/projection | Flutter tenant-admin and tenant-public | Admin event form programming item authoring; public event detail programming list | `TenantAdminEventsRequestEncoder`, `TenantAdminEventsResponseDecoder`, `EventDto`, `EventProgrammingItem` | Laravel feature tests; Flutter DTO/admin/widget tests; public programming widget tests | none |
| Laravel occurrence `taxonomy_terms` write field and `own_taxonomy_terms` read field | Flutter tenant-admin | Event occurrence editor sheet taxonomy chips | `TenantAdminEventOccurrence`, request encoder, response decoder, `TenantAdminEventsController`, `TenantAdminEventOccurrenceEditorSheet` | Flutter controller/admin form tests including `authors occurrence taxonomy overrides from the date editor`; Laravel feature tests | none |
| Laravel effective occurrence taxonomy filtering | Flutter public agenda/search consumers using existing taxonomy query shape | Home/Search agenda filters | existing schedule/event repositories and DTOs; backend query service | Laravel agenda filter tests and Flutter filter/controller tests | none |
| Laravel `/agenda` `occurrence_ids` filter | Flutter EventSearch pending invite status filter | EventSearch status action `Convites` | `EventSearchScreenController`, `ScheduleRepositoryContract`, `ScheduleBackendContract`, `LaravelScheduleBackend`, `AgendaIndexRequest`, `EventQueryService` | Flutter controller/repository/backend serialization tests; Laravel occurrence-id tests with no-geo, geo, search, and pipeline-stage assertions | none |
| Event occurrence identity in update payload | Laravel occurrence sync and Flutter full-form saves | Tenant-admin event edit with reordered/inserted dates | `TenantAdminEventsRequestEncoder`, `EventManagementService`, `EventOccurrenceSyncService` | Laravel reorder preservation and insert-before-existing regressions; Flutter encoder includes occurrence identity | none |
| Boora icon font/catalog | Flutter tenant-admin icon picker and map marker visual resolver | Tenant admin icon picker; map marker visuals | `BooraIcons`, `MapMarkerIconToken`, `TenantAdminMapMarkerIconPickerField` | Flutter catalog/widget tests for 55 icons and aliases | none |
| Tenant-public desktop frame route allowlist | Flutter web public shell | `/mapa` and POI map route | `TenantPublicWebDesktopFrame` | Flutter route/layout widget tests proving Map/Poi full width and non-map routes constrained | no Playwright needed for this structure-only wrapper assertion |

## Key Delta Files

Laravel:

- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
- `tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `tests/Feature/Events/EventCrudControllerTest.php`

## Validation Evidence

Round 03 fix evidence:

- `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
- `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `docker compose exec -T app php -l tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `docker compose exec -T app php -l tests/Feature/Events/EventCrudControllerTest.php`
- `docker compose exec -T app ./vendor/bin/pint --test packages/belluga/belluga_events/src/Application/Events/EventQueryService.php packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php packages/belluga/belluga_events/src/Application/Events/EventManagementService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php`
- `docker compose exec -T app php scripts/architecture_guardrails.php`
- `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_occurrence_ids_without_walking_unrelated_events|agenda_filters_by_occurrence_ids_with_geo_parameters|agenda_filters_by_occurrence_ids_with_search_parameters|occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows'` -> 6 passed, 43 assertions.

Expanded T5 Laravel evidence after round 03 fixes:

- `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_effective_event_taxonomy_terms|agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides|agenda_filters_by_occurrence_ids_without_walking_unrelated_events|agenda_filters_by_occurrence_ids_with_geo_parameters|agenda_filters_by_occurrence_ids_with_search_parameters|occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages|event_create_persists_programming_item_end_time|event_create_persists_occurrence_taxonomy_override|event_update_occurrence_payload_preserves_omitted_owned_profiles_taxonomy_and_programming|event_update_occurrence_payload_clears_owned_profiles_taxonomy_and_programming_with_explicit_empty_arrays|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows|event_create_rejects_unbounded_total_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_unbounded_unique_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_programming_item_end_time|event_create_rejects_occurrence_taxonomy|event_create_persists_occurrence_owned_profiles_and_programming_location_profile|public_event_detail_selects_occurrence'` -> 18 passed, 147 assertions.

Prior Flutter evidence remains valid from round 03:

- Focused Flutter suite -> 296 tests passed.
- `fvm dart analyze --format machine` -> exit 0.

Runtime/environment note:

- ADB device is now connected: `192.168.15.9:5555`.
- Local integration-test attempt on Linux was blocked by missing `libsecret-1>=0.18.4`; this is an environment dependency for `flutter_secure_storage_linux`, not a T5 test failure.

## Review Focus

Elegance should verify that mixed identified/unidentified occurrence update payloads cannot consume existing rows by index and that generated slugs remain unique when inserting before existing occurrences.

Performance should verify that occurrence-id filtering is now pushed into the earliest backend predicate for geo, search, and stream paths and that no client/server page-walk path remains in pending-only EventSearch.

Test quality should verify that the round-03 fixes are covered by behavior-specific tests and that no runtime evidence gap remains for this backend contract delta.
