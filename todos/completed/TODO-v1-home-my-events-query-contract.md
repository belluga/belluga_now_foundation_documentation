# TODO-v1 Home My Events Query Contract

**Status:** Completed (implementation completed and merged on 2026-03-19)

## Delivery Closure Note (2026-03-19)
- This TODO was delivered during the MVP favorites/my-events implementation cycle and merged to the Flutter `dev` lane via PR #135.
- Home `my events` now consumes the `confirmed_only` contract path as defined here.
- This document remains as historical implementation record; follow-up changes must be tracked in a new TODO.

## Scope
- Establish the ideal query contract for Home `my events` so the section stops piggybacking on the generic public agenda feed.
- Define the canonical backend/client contract for confirmed user events in Home.
- Define the required Flutter adaptation so Home consumes the `confirmed_only` agenda format directly.

## Out Of Scope
- Net-new behavior beyond the delivered V1 `confirmed_only` contract scope.
- Favorites/home agenda pagination behavior.

## Problem Statement
- `UserEventsRepository.fetchMyEvents()` currently calls the public upcoming agenda feed and filters locally by confirmed IDs.
- This creates redundant Home requests and couples `my events` to the wrong feed semantics.

## Canonical Contract Decision (Proposal)
- Use `/api/v1/agenda` with `confirmed_only=1` as the canonical Home "my events" query (occurrence-first, same schema as agenda).
- `confirmed_only=1` requires authentication context; anonymous identities return an empty list (`200`, `has_more=false`) for Home safety.
- `confirmed_only=1` ignores geo distance filtering; origin may be used only to compute optional `distance_meters`, not to exclude confirmed items.
- Home requests should use `past_only=0` and a small `page_size` (default 10) to return upcoming + happening-now confirmed events only.

## Tactical Implementation Path
- Backend: implement `confirmed_only` filtering in `/api/v1/agenda` and `/api/v1/events/stream` based on active attendance commitments, and document the parameter.
- Flutter: update `UserEventsRepository.fetchMyEvents()` to call `ScheduleRepository.getEventsPage(confirmedOnly: true)` and map occurrence-first agenda payloads to `VenueEventResume`; remove the dependency on `fetchUpcomingEvents()`.
- Flutter: keep Home `my events` rendering strictly sourced from the `confirmed_only` response path, including anonymous empty-list behavior (`200`, `has_more=false`).
- Keep `GET /api/v1/events/attendance/confirmed` for ID-only caching (agenda filters and invite logic), but stop using it to assemble Home "my events".

## Documentation Hooks (Required Before Implementation)
- Add `confirmed_only` to the agenda contract in `foundation_documentation/endpoints_mvp_contracts.md`.
- Register the "My Events" query contract update in `foundation_documentation/system_roadmap.md` with status `Defined`.

## Definition Of Done
- Canonical contract decision documented.
- A tactical implementation path defined for Flutter and, if needed, Laravel.
- Validation strategy defined.
- Frontend consumption adjustments are explicitly defined for the `confirmed_only` contract.

## Validation Steps
- Review Home composition and request graph.
- Define target backend/client contract and expected tests.
- Validate Home `my events` no longer piggybacks on the generic agenda request.
- Validate Flutter consumes the `confirmed_only` payload shape end-to-end (including anonymous empty-list behavior) without fallback local filtering from public agenda results.

## 2026-03-19 Delivery Check
- `fetchMyEvents()` now uses the schedule page contract with `confirmedOnly: true` and no longer piggybacks on generic upcoming agenda filtering:
  - `flutter-app/lib/infrastructure/repositories/user_events_repository.dart`
- Contract-path behavior is covered in Home origin-flow tests for `confirmed_only` usage and pagination expectations:
  - `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_origin_flow_test.dart`
- Conclusion: this TODO is delivered; remaining future refinements should be tracked as separate scope.
