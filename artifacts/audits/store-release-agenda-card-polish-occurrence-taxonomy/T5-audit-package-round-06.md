# T5 Audit Package Round 06 - Agenda Card Polish And Occurrence Taxonomy

Derived artifact. Non-authoritative. This is a delta-only package after round 05 findings were fixed.

## Scope

Same approved T5 Store Release slice: Flutter agenda/card polish, Boora icon replacement/catalog, Map web full-width exception, tenant-admin occurrence taxonomy UI, Laravel programming `end_time`, occurrence taxonomy overrides/filtering, pending occurrence-id agenda/stream filtering, and occurrence identity-safe updates.

Out of scope remains contacts/friends materialization, unrelated invite lifecycle fixes, favorite refresh, deep-link host/domain rules, public/private discovery rules, and production promotion.

## Delta Since Round 05

Round 05 produced one elegance finding. It was treated as blocking and fixed:

- `ELEGANCE-R05-001`: update payloads now reject duplicate canonical occurrence targets even when one row uses `occurrence_id` and another row uses that same occurrence's `occurrence_slug`.

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Visible Route / Action | DTO / Encoder / Decoder Path | Evidence | Waiver |
| --- | --- | --- | --- | --- | --- |
| Laravel `programming_items[].end_time` validation/projection | Flutter tenant-admin and tenant-public | Admin event form programming item authoring; public event detail programming list | `TenantAdminEventsRequestEncoder`, `TenantAdminEventsResponseDecoder`, `EventDto`, `EventProgrammingItem` | Laravel feature tests; Flutter DTO/admin/widget tests; public programming widget tests | none |
| Laravel occurrence `taxonomy_terms` write field and `own_taxonomy_terms` read field | Flutter tenant-admin | Event occurrence editor sheet taxonomy chips | `TenantAdminEventOccurrence`, request encoder, response decoder, `TenantAdminEventsController`, `TenantAdminEventOccurrenceEditorSheet` | Flutter controller/admin form tests including `authors occurrence taxonomy overrides from the date editor`; Laravel feature tests | none |
| Laravel effective occurrence taxonomy filtering | Flutter public agenda/search consumers using existing taxonomy query shape | Home/Search agenda filters | existing schedule/event repositories and DTOs; backend query service | Laravel agenda filter tests and Flutter filter/controller tests | none |
| Laravel `/agenda` and `/events/stream` `occurrence_ids` filter | Flutter EventSearch pending invite status filter | EventSearch status action `Convites` | `EventSearchScreenController`, `ScheduleRepositoryContract`, `ScheduleBackendContract`, `LaravelScheduleBackend`, `AgendaIndexRequest`, `EventQueryService` | Flutter controller/repository/backend serialization tests; Laravel occurrence-id tests with no-geo, geo, search, agenda pipeline, stream pipeline, and real stream endpoint assertions | none |
| Event occurrence identity in update payload | Laravel occurrence sync and Flutter full-form saves | Tenant-admin event edit with reordered/inserted dates | `TenantAdminEventsRequestEncoder`, `EventManagementService`, `EventOccurrenceSyncService` | Laravel reorder preservation, insert-before-existing, unique slug, duplicate raw identity, mismatched id/slug, and duplicate canonical target regressions; Flutter encoder includes occurrence identity | none |
| Boora icon font/catalog | Flutter tenant-admin icon picker and map marker visual resolver | Tenant admin icon picker; map marker visuals | `BooraIcons`, `MapMarkerIconToken`, `TenantAdminMapMarkerIconPickerField` | Flutter catalog/widget tests for 55 icons and aliases | none |
| Tenant-public desktop frame route allowlist | Flutter web public shell | `/mapa` and POI map route | `TenantPublicWebDesktopFrame` | Flutter route/layout widget tests proving Map/Poi full width and non-map routes constrained | no Playwright needed for this structure-only wrapper assertion |

## Key Delta Files

Laravel:

- `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `tests/Feature/Events/EventCrudControllerTest.php`

## Validation Evidence

Round 05 fix evidence:

- `docker compose exec -T app php -l packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `docker compose exec -T app php -l tests/Feature/Events/EventCrudControllerTest.php`
- `docker compose exec -T app ./vendor/bin/pint packages/belluga/belluga_events/src/Application/Events/EventManagementService.php tests/Feature/Events/EventCrudControllerTest.php`
- `./scripts/delphi/run_laravel_tests_safe.sh --filter='event_update_rejects_duplicate_occurrence_identity_ids|event_update_rejects_duplicate_occurrence_identity_slugs|event_update_rejects_mismatched_occurrence_identity_pair|event_update_rejects_duplicate_canonical_occurrence_identity_target|event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows'` -> 5 passed, 30 assertions.

Expanded T5 evidence after round 05 fix:

- `docker compose exec -T app ./vendor/bin/pint --test packages/belluga/belluga_events/src/Application/Events/EventManagementService.php tests/Feature/Events/EventCrudControllerTest.php` -> pass.
- `docker compose exec -T app php scripts/architecture_guardrails.php` -> pass.
- `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_effective_event_taxonomy_terms|agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides|agenda_filters_by_occurrence_ids_without_walking_unrelated_events|agenda_filters_by_occurrence_ids_with_geo_parameters|agenda_filters_by_occurrence_ids_with_search_parameters|occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages|event_stream_filters_by_occurrence_ids_without_geo|event_create_persists_programming_item_end_time|event_create_persists_occurrence_taxonomy_override|event_update_occurrence_payload_preserves_omitted_owned_profiles_taxonomy_and_programming|event_update_occurrence_payload_clears_owned_profiles_taxonomy_and_programming_with_explicit_empty_arrays|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows|event_update_rejects_duplicate_occurrence_identity_ids|event_update_rejects_duplicate_occurrence_identity_slugs|event_update_rejects_mismatched_occurrence_identity_pair|event_update_rejects_duplicate_canonical_occurrence_identity_target|event_create_rejects_unbounded_total_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_unbounded_unique_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_programming_item_end_time|event_create_rejects_occurrence_taxonomy|event_create_persists_occurrence_owned_profiles_and_programming_location_profile|public_event_detail_selects_occurrence'` -> 23 passed, 170 assertions.

Prior Flutter evidence remains valid:

- Focused Flutter suite -> 296 tests passed.
- `fvm dart analyze --format machine` -> exit 0 after round 05.

Runtime/environment note:

- ADB device is connected: `192.168.15.9:5555`.
- Local integration-test attempt on Linux was blocked by missing `libsecret-1>=0.18.4`; this is an environment dependency for `flutter_secure_storage_linux`, not a T5 test failure.

## Review Focus

Elegance should verify no unresolved canonical occurrence identity blocker remains, especially duplicate canonical targets via different identity fields.

Performance should verify the canonical target validation remains bounded and does not introduce runtime risk.

Test quality should verify the id-vs-slug alias duplication regression is covered and that the prior round-04 test gaps remain closed.
