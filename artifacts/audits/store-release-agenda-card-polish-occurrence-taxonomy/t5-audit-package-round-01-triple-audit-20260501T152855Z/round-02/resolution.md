# Triple Audit Round 02 Resolution

Derived artifact. Non-authoritative. Record Delphi adjudication, resolution decisions, validation evidence, and remaining blockers before opening another audit round.

## Status

Choose one when recording with `record-resolution`:

- `resolved`: all material findings were fixed and required validation passed.
- `accepted-debt`: remaining findings are explicitly accepted as non-blocking debt with owner/rationale.
- `blocked`: required evidence or fixes are still blocked; `next-round` must not proceed.

## Adjudication

- Lane recommendations were additive, not contradictory in implementation terms.
- `ELEGANCE-R02-001` was valid: occurrence identity was preserved for omitted payload resolution but persistence still upserted by `occurrence_index`.
- `PERF-R02-001` was valid: Pending-only Event Search could keep paging unfiltered agenda results while looking for locally matched pending occurrences.
- Both findings were fixed in the implementation branch and covered by focused Flutter/Laravel regression tests.

## Resolution Matrix

| Finding | Decision | Resolution / Rationale | Evidence |
| --- | --- | --- | --- |
| `ELEGANCE-R02-001` | `resolved` | Occurrence identity is now carried through event normalization as `occurrence_id` / `occurrence_slug`. `EventOccurrenceSyncService` resolves existing occurrence documents by id or slug before falling back to index for new rows, temporarily moves resolved documents to negative indexes to avoid `(event_id, occurrence_index)` collisions during reorder, preserves slugs, and soft-deletes by active document ids instead of active indexes. | Laravel regression `test_event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity`; expanded Laravel safe runner passed 14 tests / 121 assertions. |
| `PERF-R02-001` | `resolved` | Pending-only Event Search now uses bounded occurrence-id filtering end to end. `EventSearchScreenController` derives pending occurrence ids, short-circuits when none exist, disables pending-only auto-page walking, and passes `occurrenceIds` through the repository/backend contracts. Laravel `/agenda` accepts `occurrence_ids` and filters occurrence documents by id. | Flutter tests `pending invite filter uses occurrence ids and does not auto-page unrelated agenda batches`, `pending invite filter with no pending occurrences does not query agenda`, repository/backend serialization tests, and Laravel `test_agenda_filters_by_occurrence_ids_without_walking_unrelated_events`. |

## Validation Evidence

- Commands run:
  - `fvm flutter test test/presentation/shared/icons/map_marker_icon_catalog_test.dart test/presentation/tenant_admin/shared/widgets/tenant_admin_map_marker_icon_picker_field_test.dart test/presentation/shared/widgets/tenant_public_web_desktop_frame_test.dart test/presentation/tenant_public/widgets/upcoming_event_card_test.dart test/presentation/tenant_public/widgets/event_live_now_card_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/event_poi_detail_card_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_content_resolver_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/widgets/event_programming_section_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/my_events_carousel_card_test.dart test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_request_encoder_test.dart test/infrastructure/dal/dao/tenant_admin/tenant_admin_events_response_decoder_test.dart test/infrastructure/dal/dto/schedule/event_dto_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart test/infrastructure/repositories/schedule_repository_test.dart test/infrastructure/dal/laravel_schedule_backend_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
  - `fvm dart analyze --format machine`
  - `./scripts/delphi/run_laravel_tests_safe.sh --filter='agenda_filters_by_effective_event_taxonomy_terms|agenda_taxonomy_filter_uses_effective_occurrence_taxonomy_overrides|agenda_filters_by_occurrence_ids_without_walking_unrelated_events|event_create_persists_programming_item_end_time|event_create_persists_occurrence_taxonomy_override|event_update_occurrence_payload_preserves_omitted_owned_profiles_taxonomy_and_programming|event_update_occurrence_payload_clears_owned_profiles_taxonomy_and_programming_with_explicit_empty_arrays|event_update_reordered_occurrences_preserves_owned_payloads_by_occurrence_identity|event_create_rejects_unbounded_total_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_unbounded_unique_occurrence_taxonomy_terms_before_resolver_work|event_create_rejects_programming_item_end_time|event_create_rejects_occurrence_taxonomy|event_create_persists_occurrence_owned_profiles_and_programming_location_profile|public_event_detail_selects_occurrence'`
  - `docker compose exec -T app ./vendor/bin/pint --test ...`
  - `docker compose exec -T app php scripts/architecture_guardrails.php`
- Passed/failed/blocked gates:
  - Flutter focused suite passed: 296 tests.
  - Official Flutter analyzer passed with no diagnostics.
  - Laravel focused safe runner passed: 14 tests, 121 assertions.
  - Laravel Pint check and architecture guard passed.
- Runtime/navigation evidence:
  - No additional Playwright/ADB evidence is required for these two round-02 fixes. They are contract/query/controller behaviors covered by focused Flutter/Laravel tests.

## Open Blockers

- `none`

## Accepted Non-Blocking Debt

- `none`

## Next Audit Package Requirements

- Include this resolution artifact and the focused test evidence in the next bounded package.
- Ask the next reviewers to verify that the two resolved high findings are actually closed and that the added occurrence-id query path does not introduce a new contract or performance issue.
