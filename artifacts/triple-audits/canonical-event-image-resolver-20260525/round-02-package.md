# Triple Audit Package Round 02: Canonical Event Image Resolver

- **Artifact kind:** `bounded_triple_audit_package`
- **Authoritative:** `false`
- **TODO:** `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-canonical-event-image-resolver-all-public-surfaces.md`
- **Laravel branch:** `fix/canonical-event-image-resolver-20260525`
- **Scope:** Backend-only Laravel canonical event image resolver for public event read payloads.

## Round 01 Outcome

Round 01 was not clean.

- Elegance: clean, no findings.
- Performance: `PERF-001` high, account-profile public agenda path fetched all matching future occurrences before formatting.
- Test Quality: `TQ-001` medium, account-profile public agenda endpoint evidence was integration-present but semantically thin.

Resolution artifact:

- `foundation_documentation/artifacts/triple-audits/canonical-event-image-resolver-20260525/triple-audit-session/round-01/resolution.md`

Resolution status recorded with the session runner: `resolved`.

## Delta Since Round 01

- `AccountProfileAgendaOccurrencesService::forProfile()` now applies `InputConstraints::PUBLIC_PAGE_SIZE_MAX` before `get()` and before calling `EventQueryService::formatEvents()`.
- `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` now asserts the public account-profile endpoint returns the Event cover for `agenda_occurrences.0.thumb.data.url` and `hero_image_url`, while the Venue cover remains present and does not win.
- `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` now includes an endpoint-level cap test proving `data.agenda_occurrences` is limited to `InputConstraints::PUBLIC_PAGE_SIZE_MAX`.
- The final focused canonical image suite was rerun after the Round 01 fixes and passed.

## Current Changed Files

Laravel app:

- `app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`
- `tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `tests/Feature/Events/EventCrudControllerTest.php`
- `tests/Unit/Events/EventHeroImageResolverTest.php`
- `tests/Unit/Events/EventQueryServiceTest.php`
- `tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php`

Foundation evidence:

- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-canonical-event-image-resolver-all-public-surfaces.md`
- `foundation_documentation/artifacts/triple-audits/canonical-event-image-resolver-20260525/triple-audit-session/round-01/resolution.md`
- `foundation_documentation/artifacts/triple-audits/canonical-event-image-resolver-20260525/round-02-package.md`

## Current Diff Summary

`git -C laravel-app diff --stat`:

```text
 .../AccountProfileAgendaOccurrencesService.php     |  11 +-
 .../src/Application/Events/EventQueryService.php   | 104 ++++++++++-
 .../AccountProfilesControllerTest.php              |  82 ++++++++
 .../Events/AgendaAndEventsControllerTest.php       | 206 +++++++++++++++++++++
 tests/Feature/Events/EventCrudControllerTest.php   |   7 +-
 tests/Unit/Events/EventHeroImageResolverTest.php   | 148 +++++++++++++++
 tests/Unit/Events/EventQueryServiceTest.php        |   3 +
 .../CanonicalImageResolutionGuardrailTest.php      |  34 ++++
 8 files changed, 578 insertions(+), 17 deletions(-)
```

## Implementation Summary

- `EventQueryService` injects `EventHeroImageResolver`.
- `fetchAgenda()` formats list items through `formatEvents()`.
- `formatEvents()` materializes the already bounded list slice and batch-loads parent `Event` records for occurrence payloads.
- `formatEvent()` accepts optional parent Event context.
- For occurrence payloads with parent context, `thumb` is normalized from the parent Event.
- For occurrence payloads with parent context, linked-profile image fallback uses parent Event `event_parties`; stale occurrence snapshots do not let Venue media win before Event/Profile images.
- `formatEvent()` and `formatEventDetail()` set top-level `hero_image_url` through the canonical resolver.
- Account-profile agenda occurrences call `formatEvents()` on a bounded result set.
- Guardrail test requires public event image payload providers to delegate to `EventHeroImageResolver`: `EventQueryService`, `PublicWebMetadataService`, and `InviteTargetReadAdapter`.

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Implementation Decision | Evidence |
| --- | --- | --- | --- |
| `GET /api/v1/agenda` event card payload | Flutter/web card consumers | Backend sends correct `thumb` compatibility payload and `hero_image_url`; no Flutter change in approved scope. | Agenda feature tests assert exact Event cover, linked Account Profile cover/avatar, and Venue-not-winning behavior for single and multiple occurrences. |
| `GET /api/v1/events/{event}?occurrence={occurrence}` | Public detail/hero | Backend sends resolver-produced `hero_image_url` and Event cover `thumb`. | Event detail feature test asserts `data.hero_image_url === data.thumb.data.url`. |
| Public web metadata/OG | HTML metadata consumers | Existing metadata adapter remains resolver-backed. | PublicWebMetadataShell selected tests passed and guardrail checks `PublicWebMetadataService`. |
| Invite preview image | Invite consumers | Existing invite adapter remains resolver-backed. | Invite preview test passed and guardrail checks `InviteTargetReadAdapter`. |
| Account profile public agenda occurrences | Account profile public page | Consumer uses `formatEvents()` for the same parent-context canonical path, now on a public bounded result set. | AccountProfilesController tests assert exact Event cover, Venue-not-winning behavior, and public page-size cap. |

Backend-only scope was explicitly approved by the user. Flutter fallback behavior remains defensive and is not the root fix.

## RED Evidence

- Initial agenda RED failed with `thumb.data.url` null for the production-equivalent single-occurrence stale snapshot.
- Guardrail RED identified that `EventQueryService.php` did not delegate to `EventHeroImageResolver`.
- Final review RED for linked profile fallback failed because agenda selected `single-parent-venue-cover.jpg` instead of `single-parent-profile-cover.jpg`; fixed by using parent Event `event_parties` for occurrence image fallback.

## Current Test Coverage

Behavioral coverage includes:

- Single-occurrence stale `thumb` returns parent Event cover.
- Multi-occurrence stale `thumb` returns parent Event cover.
- Single-occurrence missing Event cover returns parent linked Account Profile cover before Venue.
- Multi-occurrence missing Event cover and profile cover returns parent linked Account Profile avatar before Venue.
- Resolver fallback matrix covers Event cover, Account Profile cover, Account Profile avatar, Venue cover, Venue hero, Venue avatar, Venue logo, null, and non-venue event-party metadata.
- Detail payload asserts `hero_image_url` equals Event cover `thumb`.
- Account-profile public endpoint asserts Event cover wins over Venue media and `hero_image_url` matches.
- Account-profile public endpoint asserts the occurrence list is capped at `InputConstraints::PUBLIC_PAGE_SIZE_MAX`.
- Guardrail asserts public event image payload providers delegate to `EventHeroImageResolver`.

## Validation Evidence After Round 01 Fixes

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php
```

Result: `2 tests, 16 assertions`.

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php
```

Result: `4 tests, 24 assertions`.

```bash
docker compose exec -T app ./vendor/bin/pint --test app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php packages/belluga/belluga_events/src/Application/Events/EventQueryService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php
```

Result: `PASS`, 8 files.

```bash
docker compose exec -T app composer run architecture:guardrails
```

Result: `[ARCH-GUARDRAILS] PASS - no architecture violations found.`

```bash
bash delphi-ai/tools/test_quality_audit.sh --path laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php --path laravel-app/tests/Feature/Events/EventCrudControllerTest.php --path laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php --path laravel-app/tests/Unit/Events/EventHeroImageResolverTest.php --path laravel-app/tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php --path laravel-app/tests/Unit/Events/EventQueryServiceTest.php
```

Result: exit 2, `Outcome heuristic: medium`; no hard bypass markers, no test-only support route, no no-exception-only assertions. Remaining warnings are existing suite-wide legacy patterns rather than new coverage gaps in this fix.

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='EventHeroImageResolverTest|CanonicalImageResolutionGuardrailTest|EventQueryServiceTest|AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_single_occurrence_uses_parent_linked_profile_cover_before_venue_when_event_cover_is_missing|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_uses_parent_linked_profile_avatar_before_venue_when_cover_candidates_are_missing|EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties|PublicWebMetadataShellTest::test_event_public_route_injects_event_metadata_with_event_party_profile_cover_fallback|PublicWebMetadataShellTest::test_event_public_route_prefers_linked_account_profiles_image_over_artists_projection|PublicWebMetadataShellTest::test_event_public_route_ignores_legacy_artists_projection_for_event_image_resolution'
```

Result: `20 tests, 90 assertions`.

## Audit Questions For Round 02

1. Elegance: after the Round 01 fixes, is event image selection centralized enough for the affected public backend surfaces, or is there still a divergent public path?
2. Performance: is every current public list/detail/metadata/invite path bounded and free of N+1/fetch-all behavior introduced by the parent Event lookup?
3. Test quality: does the current evidence catch the production regression and the Round 01 account-profile gaps, including distinct Event/Profile/Venue URLs and single/multiple occurrence cases?

## Known Boundaries

- No Flutter code changed.
- No media storage/upload/crop behavior changed.
- No auth, tenant, or route authorization policy changed.
- Production-domain probe is promotion/deploy evidence, not local implementation evidence.
