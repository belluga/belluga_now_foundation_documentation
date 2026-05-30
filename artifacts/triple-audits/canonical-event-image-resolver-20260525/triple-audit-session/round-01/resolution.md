# Triple Audit Round 01 Resolution

Derived artifact. Non-authoritative. Record Delphi adjudication, resolution decisions, validation evidence, and remaining blockers before opening another audit round.

## Status

`resolved`: all material findings were fixed and required validation passed.

## Adjudication

The lane recommendations were additive, not contradictory for delivery purposes.
Elegance reported no structural blocker. Performance identified a blocking
public-runtime fetch-all risk in the account-profile agenda path. Test Quality
identified endpoint-specific evidence that was weaker than the shared formatter
coverage, classified by the reviewer as non-blocking but still valid to tighten.

Both `PERF-001` and `TQ-001` were fixed before opening the next round.

## Resolution Matrix

| Finding | Decision | Resolution / Rationale | Evidence |
| --- | --- | --- | --- |
| `PERF-001` | `resolved` | `AccountProfileAgendaOccurrencesService::forProfile()` now applies `InputConstraints::PUBLIC_PAGE_SIZE_MAX` before `get()` and before passing occurrences to `EventQueryService::formatEvents()`. This keeps the parent Event lookup bounded for the public account-profile agenda surface. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences\|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` passed: 2 tests, 16 assertions. Focused account-profile surface suite passed: 4 tests, 24 assertions. |
| `TQ-001` | `resolved` | The account-profile public endpoint test now sets distinct Event cover and Venue cover URLs, forces stale occurrence media, and asserts `data.agenda_occurrences.0.thumb.data.url` plus `hero_image_url` resolve to the Event cover while Venue media remains present but does not win. A cap test proves the public page-size bound at the same endpoint. | Focused account-profile surface suite passed: 4 tests, 24 assertions. Final focused canonical image suite passed: 20 tests, 90 assertions. |

## Validation Evidence

- Commands run:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`
  - `docker compose exec -T app ./vendor/bin/pint --test app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php packages/belluga/belluga_events/src/Application/Events/EventQueryService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php`
  - `docker compose exec -T app composer run architecture:guardrails`
  - `bash delphi-ai/tools/test_quality_audit.sh --path laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php --path laravel-app/tests/Feature/Events/EventCrudControllerTest.php --path laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php --path laravel-app/tests/Unit/Events/EventHeroImageResolverTest.php --path laravel-app/tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php --path laravel-app/tests/Unit/Events/EventQueryServiceTest.php`
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='EventHeroImageResolverTest|CanonicalImageResolutionGuardrailTest|EventQueryServiceTest|AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_single_occurrence_uses_parent_linked_profile_cover_before_venue_when_event_cover_is_missing|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_uses_parent_linked_profile_avatar_before_venue_when_cover_candidates_are_missing|EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties|PublicWebMetadataShellTest::test_event_public_route_injects_event_metadata_with_event_party_profile_cover_fallback|PublicWebMetadataShellTest::test_event_public_route_prefers_linked_account_profiles_image_over_artists_projection|PublicWebMetadataShellTest::test_event_public_route_ignores_legacy_artists_projection_for_event_image_resolution'`
- Passed/failed/blocked gates:
  - Account-profile targeted: passed, 2 tests, 16 assertions.
  - Account-profile focused surface suite: passed, 4 tests, 24 assertions.
  - Pint: passed, 8 files.
  - Architecture guardrails: passed.
  - Test quality audit: exit 2 / medium heuristic; no hard bypass markers, no test-only support route, no no-exception-only assertions; remaining warnings are legacy suite-wide patterns not introduced by this fix.
  - Final focused canonical image suite: passed, 20 tests, 90 assertions.
- Runtime/navigation evidence: backend-only public payload contract fix; no frontend runtime route changed.

## Open Blockers

- `none`

## Accepted Non-Blocking Debt

- `none`

## Next Audit Package Requirements

- Include this resolution artifact in the next bounded package.
- Include the account-profile limit/test delta and final focused validation evidence.
