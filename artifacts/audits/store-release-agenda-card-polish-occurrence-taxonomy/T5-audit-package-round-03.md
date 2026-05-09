# T5 Audit Package Round 03 - Agenda Card Polish And Occurrence Taxonomy

Derived artifact. Non-authoritative. This is a refreshed bounded package after round 02 findings were fixed.

## Scope

This package covers the approved T5 Store Release slice:

- Public agenda/card polish in Flutter: Account Profile chip compression as `e mais X`, stable card trailing action slot, Home Agenda invite/radius chrome, and `às` for explicit time ranges.
- Backend Events contract changes: optional `programming_items[].end_time`, occurrence-owned taxonomy overrides, validation against the parent event category, effective occurrence taxonomy filtering, pending occurrence-id agenda filtering, and occurrence identity preservation during reorder updates.
- Flutter admin/public consumers for those backend contracts, including the tenant-admin occurrence editor taxonomy field.
- Replacement of the app `BooraIcons` font asset with the uploaded 55-icon font and tenant-admin icon picker coverage.
- Tenant-public web desktop-frame exception for the Map route family only.

Out of scope: contacts/friends materialization, invite lifecycle fixes outside the EventSearch status filter contract, favorite refresh, deep-link host/domain rules, public/private discovery rules, and production promotion.

## Delta Since Round 02

Round 02 produced two high findings. Both were treated as blocking and fixed:

- `PERF-R02-001`: Pending-only Event Search no longer page-walks unfiltered agenda pages. The controller derives pending occurrence ids, short-circuits when none exist, disables pending-only auto-paging, and passes `occurrenceIds` through Flutter repository/backend contracts into Laravel `/agenda` as `occurrence_ids`.
- `ELEGANCE-R02-001`: occurrence update persistence now resolves existing occurrence documents by `occurrence_id` / `occurrence_slug` before index fallback, uses temporary negative indexes to avoid unique-index reorder collisions, preserves slugs, and soft-deletes by active document ids.

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Visible Route / Action | DTO / Encoder / Decoder Path | Evidence | Waiver |
| --- | --- | --- | --- | --- | --- |
| Laravel `programming_items[].end_time` validation/projection | Flutter tenant-admin and tenant-public | Admin event form programming item authoring; public event detail programming list | `TenantAdminEventsRequestEncoder`, `TenantAdminEventsResponseDecoder`, `EventDto`, `EventProgrammingItem` | Laravel feature tests; Flutter DTO/admin/widget tests; public programming widget tests | none |
| Laravel occurrence `taxonomy_terms` write field and `own_taxonomy_terms` read field | Flutter tenant-admin | Event occurrence editor sheet taxonomy chips | `TenantAdminEventOccurrence`, request encoder, response decoder, `TenantAdminEventsController`, `TenantAdminEventOccurrenceEditorSheet` | Flutter controller/admin form tests including `authors occurrence taxonomy overrides from the date editor`; Laravel feature tests | none |
| Laravel effective occurrence taxonomy filtering | Flutter public agenda/search consumers using existing taxonomy query shape | Home/Search agenda filters | existing schedule/event repositories and DTOs; backend query service | Laravel agenda filter tests and Flutter filter/controller tests | none |
| Laravel `/agenda` `occurrence_ids` filter | Flutter EventSearch pending invite status filter | EventSearch status action `Convites` | `EventSearchScreenController`, `ScheduleRepositoryContract`, `ScheduleBackendContract`, `LaravelScheduleBackend`, `AgendaIndexRequest`, `EventQueryService` | Flutter controller/repository/backend serialization tests; Laravel `test_agenda_filters_by_occurrence_ids_without_walking_unrelated_events` | none |
| Event occurrence identity in update payload | Laravel occurrence sync and Flutter full-form saves | Tenant-admin event edit with reordered dates | `TenantAdminEventsRequestEncoder`, `EventManagementService`, `EventOccurrenceSyncService` | Laravel reorder preservation regression; Flutter encoder includes occurrence identity | none |
| Boora icon font/catalog | Flutter tenant-admin icon picker and map marker visual resolver | Tenant admin icon picker; map marker visuals | `BooraIcons`, `MapMarkerIconToken`, `TenantAdminMapMarkerIconPickerField` | Flutter catalog/widget tests for 55 icons and aliases | none |
| Tenant-public desktop frame route allowlist | Flutter web public shell | `/mapa` and POI map route | `TenantPublicWebDesktopFrame` | Flutter route/layout widget tests proving Map/Poi full width and non-map routes constrained | no Playwright needed for this structure-only wrapper assertion |

## Key Implementation Files

Flutter:

- `lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`
- `lib/domain/repositories/schedule_repository_contract.dart`
- `lib/infrastructure/services/schedule_backend_contract.dart`
- `lib/infrastructure/repositories/schedule_repository.dart`
- `lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`
- `lib/presentation/tenant_admin/events/widgets/tenant_admin_event_occurrence_editor_sheet.dart`
- `lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder.dart`
- `lib/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder.dart`
- `lib/presentation/shared/widgets/tenant_public_web_desktop_frame.dart`
- `lib/application/icons/boora_icons.dart`
- `lib/presentation/shared/icons/map_marker_icon_catalog.dart`

Laravel:

- `packages/belluga/belluga_events/src/Http/Api/v1/Requests/AgendaIndexRequest.php`
- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventOccurrencePayloadSnapshotService.php`
- `packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventWriteRules.php`
- `packages/belluga/belluga_events/src/Support/Validation/EventPayloadFanoutGuard.php`
- `packages/belluga/belluga_events/src/Support/Validation/InputConstraints.php`

## Validation Evidence

Flutter:

- `fvm flutter test test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart` -> passed.
- `fvm flutter test test/presentation/shared/icons/map_marker_icon_catalog_test.dart test/presentation/tenant_admin/shared/widgets/tenant_admin_map_marker_icon_picker_field_test.dart test/presentation/shared/widgets/tenant_public_web_desktop_frame_test.dart test/presentation/tenant_public/widgets/upcoming_event_card_test.dart test/presentation/tenant_public/widgets/event_live_now_card_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/event_poi_detail_card_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_content_resolver_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_programming_section_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/my_events_carousel_card_test.dart test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` -> 296 passed.
- `fvm dart analyze --format machine` -> exited 0 with no diagnostics.

Laravel:

- `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_occurrence_ids_without_walking_unrelated_events|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity'` -> 2 passed, 17 assertions.
- `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_effective_event_taxonomy_terms|agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides|agenda_filters_by_occurrence_ids_without_walking_unrelated_events|event_create_persists_programming_item_end_time|event_create_persists_occurrence_taxonomy_override|event_update_occurrence_payload_preserves_omitted_owned_profiles_taxonomy_and_programming|event_update_occurrence_payload_clears_owned_profiles_taxonomy_and_programming_with_explicit_empty_arrays|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_create_rejects_unbounded_total_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_unbounded_unique_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_programming_item_end_time|event_create_rejects_occurrence_taxonomy|event_create_persists_occurrence_owned_profiles_and_programming_location_profile|public_event_detail_selects_occurrence'` -> 14 passed, 121 assertions.
- `docker compose exec -T app ./vendor/bin/pint --test <6 touched PHP files>` -> pass.
- `docker compose exec -T app php scripts/architecture_guardrails.php` -> pass.

Mechanical checks:

- `git diff --check` in `flutter-app`, `laravel-app`, and `foundation_documentation` -> pass before round-02 fixes; rerun pending after this package update.

## Review Focus

Elegance should verify that occurrence identity is now the canonical persistence path for existing occurrence updates, with index fallback only for new occurrences, and that the occurrence taxonomy UI field sits in the appropriate tenant-admin occurrence editor boundary without bypassing controller/domain ownership.

Performance should verify that pending-only EventSearch is bounded by server-side occurrence-id filtering and cannot reintroduce client-side page walking; also re-check that taxonomy filtering and fanout guards remain bounded.

Test quality should verify that the two round-02 fixes have behavior-specific tests that would fail on the prior implementation, that the occurrence taxonomy UI field is covered through real widget/controller flow, and that no test relies on hardcoded profile types such as `venue`.

## Expected Audit Output

Each lane should return JSON compatible with the triple audit schema, with findings classified as release-blocking only when they create concrete correctness, architecture, performance, or evidence risk for the approved T5 scope.
