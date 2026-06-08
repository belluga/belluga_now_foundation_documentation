# Triple Audit Package: Canonical Event Image Resolver

- **Artifact kind:** `bounded_triple_audit_package`
- **Authoritative:** `false`
- **TODO:** `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-canonical-event-image-resolver-all-public-surfaces.md`
- **Laravel branch:** `fix/canonical-event-image-resolver-20260525`
- **Scope:** Backend-only Laravel canonical event image resolver for public event read payloads.

## Production Bug

Production public detail was corrected, but event cards fed by `GET /api/v1/agenda` still received stale occurrence payloads:

- Event id: `6a147bd30a65fb8d0f0d00d8`
- Occurrence id: `6a147bd30a65fb8d0f0d00d9`
- Agenda payload had `thumb: null`
- Agenda payload had Venue media, so UI fallback chose the Venue image instead of the Event cover.

The user requirement is backend canonicalization: every backend surface that needs an Event image must use `EventHeroImageResolver`; Flutter/client fallback is out of implementation scope.

## Changed Files

Laravel app:

- `app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `tests/Feature/Events/EventCrudControllerTest.php`
- `tests/Unit/Events/EventHeroImageResolverTest.php`
- `tests/Unit/Events/EventQueryServiceTest.php`
- `tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php`

Foundation evidence:

- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-canonical-event-image-resolver-all-public-surfaces.md`

## Implementation Summary

- `EventQueryService` now injects `EventHeroImageResolver`.
- `fetchAgenda()` formats list items via `formatEvents()` instead of direct per-item `formatEvent()`.
- `formatEvents()` materializes the bounded page slice and batch-loads parent `Event` records for occurrence payloads.
- `formatEvent()` accepts optional parent Event context.
- For occurrence payloads, `thumb` is normalized from the parent Event when parent context is available.
- For occurrence payloads, event image linked-profile fallbacks use parent Event `event_parties` when parent context is available, so stale occurrence snapshots do not cause Venue to win.
- `formatEvent()` and `formatEventDetail()` add top-level `hero_image_url` through `withCanonicalHeroImage()`.
- Account-profile agenda occurrences now call `EventQueryService::formatEvents()` so they use the same batched parent-context resolver path.
- A guardrail test requires public event image providers to delegate to `EventHeroImageResolver`: `EventQueryService`, `PublicWebMetadataService`, and `InviteTargetReadAdapter`.

## Key Code Anchors

- Resolver dependency injection: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:45`
- Agenda list formatting: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:73`
- Batched list formatter: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:1226`
- Parent Event thumb for occurrence payloads: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:1262`
- Parent Event linked-profile fallback source for occurrence payloads: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:1268`
- Canonical image assignment: `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:2322`
- Account profile agenda consumer: `app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php:55`
- Public provider delegation guardrail: `tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php:64`

## Diff Summary

`git -C laravel-app diff --stat`:

```text
 .../AccountProfileAgendaOccurrencesService.php     |   9 +-
 .../src/Application/Events/EventQueryService.php   | 104 ++++++++++-
 .../Events/AgendaAndEventsControllerTest.php       | 206 +++++++++++++++++++++
 tests/Feature/Events/EventCrudControllerTest.php   |   7 +-
 tests/Unit/Events/EventHeroImageResolverTest.php   | 148 +++++++++++++++
 tests/Unit/Events/EventQueryServiceTest.php        |   3 +
 .../CanonicalImageResolutionGuardrailTest.php      |  34 ++++
 7 files changed, 494 insertions(+), 17 deletions(-)
```

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Implementation Decision | Evidence |
| --- | --- | --- | --- |
| `GET /api/v1/agenda` event card payload | Flutter/web card consumers | Backend sends correct `thumb` compatibility payload and `hero_image_url`; no Flutter code change in approved scope. | Agenda feature tests assert exact Event cover, linked Account Profile cover/avatar, and Venue-not-winning behavior. |
| `GET /api/v1/events/{event}?occurrence={occurrence}` | Public detail/hero | Backend sends resolver-produced `hero_image_url` and Event cover `thumb`. | Event detail feature test asserts `data.hero_image_url === data.thumb.data.url`. |
| Public web metadata/OG | HTML metadata consumers | Existing metadata adapter remains resolver-backed. | PublicWebMetadataShell selected tests passed and guardrail checks `PublicWebMetadataService`. |
| Invite preview image | Invite consumers | Existing invite adapter remains resolver-backed. | Invite preview test passed and guardrail checks `InviteTargetReadAdapter`. |
| Account profile public agenda occurrences | Account profile public page | Consumer switched to `formatEvents()` for same parent-context canonical image path. | AccountProfilesController selected test passed. |

Backend-only scope was explicitly approved by the user. Flutter fallback behavior remains defensive and is not the root fix.

## RED Evidence

- Initial agenda RED failed with `thumb.data.url` null for the production-equivalent single-occurrence stale snapshot.
- Guardrail RED identified that `EventQueryService.php` did not delegate to `EventHeroImageResolver`.
- Final review RED for linked profile fallback failed because agenda selected `single-parent-venue-cover.jpg` instead of `single-parent-profile-cover.jpg`; fixed by using parent Event `event_parties` for occurrence image fallback.

## Test Coverage

New/updated behavioral coverage includes:

- Single-occurrence stale `thumb` returns parent Event cover.
- Multi-occurrence stale `thumb` returns parent Event cover.
- Single-occurrence missing Event cover returns parent linked Account Profile cover before Venue.
- Multi-occurrence missing Event cover and profile cover returns parent linked Account Profile avatar before Venue.
- Resolver fallback matrix covers Event cover, Account Profile cover, Account Profile avatar, Venue cover, Venue hero, Venue avatar, Venue logo, null, and non-venue event-party metadata.
- Detail payload asserts `hero_image_url` equals Event cover `thumb`.
- Guardrail asserts public event image payload providers delegate to `EventHeroImageResolver`.

## Validation Run

Commands completed locally:

```bash
docker compose exec -T app ./vendor/bin/pint --test app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php packages/belluga/belluga_events/src/Application/Events/EventQueryService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php
```

Result: `PASS`, 7 files.

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='EventHeroImageResolverTest|CanonicalImageResolutionGuardrailTest|EventQueryServiceTest|AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image|AgendaAndEventsControllerTest::test_agenda_single_occurrence_uses_parent_linked_profile_cover_before_venue_when_event_cover_is_missing|AgendaAndEventsControllerTest::test_agenda_multi_occurrence_uses_parent_linked_profile_avatar_before_venue_when_cover_candidates_are_missing|EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale|AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences|PublicWebMetadataShellTest::test_event_public_route_injects_event_metadata_with_event_party_profile_cover_fallback|PublicWebMetadataShellTest::test_event_public_route_prefers_linked_account_profiles_image_over_artists_projection|PublicWebMetadataShellTest::test_event_public_route_ignores_legacy_artists_projection_for_event_image_resolution'
```

Result: `17 tests, 76 assertions`.

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php tests/Feature/Events/AgendaAndEventsControllerTest.php
```

Result: `54 tests, 211 assertions`.

```bash
./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='test_share_preview_resolves_without_authentication' tests/Feature/Invites/InvitesFlowTest.php
```

Result: `1 test, 9 assertions`.

```bash
docker compose exec -T app composer run architecture:guardrails
```

Result: `[ARCH-GUARDRAILS] PASS - no architecture violations found.`

```bash
git -C laravel-app diff --check
git -C foundation_documentation diff --check
```

Result: no output, exit 0.

## Local Audits Already Run

- Test quality audit: heuristic `medium` due existing legacy `Sanctum::actingAs`, status assertions, and mocks in large test files; no hard bypass markers, no test-only support routes, no no-exception-only assertions. New tests assert exact semantic image URLs and resolver delegation.
- Verification debt audit: `Outcome heuristic: none`, inline code TODO debt classification `none`.
- TODO authority guard: `go`.
- TODO completion guard: `go`.

## Audit Questions For External Reviewers

1. Elegance: does this implementation truly centralize event image selection in `EventHeroImageResolver`, or does any public backend path still contain a divergent image selection behavior that could drift?
2. Performance: is the batched parent Event lookup for agenda/profile occurrence lists bounded and safe, or does any path introduce N+1, fetch-all, or high-cardinality in-memory work?
3. Test quality: do the new tests catch the production regression and the fallback matrix strongly enough, including single/multiple occurrences and distinct Event/Profile/Venue URLs?

## Known Boundaries

- No Flutter code changed.
- No media storage/upload/crop behavior changed.
- No auth, tenant, or route authorization policy changed.
- Production-domain probe is promotion/deploy evidence, not local implementation evidence.
