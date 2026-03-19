# TODO-v1 Home My Events Query Contract

**Status:** Active (code gap confirmed on 2026-03-19)

## Scope
- Establish the ideal query contract for Home `my events` so the section stops piggybacking on the generic public agenda feed.
- Define the canonical backend/client contract for confirmed user events in Home.

## Out Of Scope
- Implementing the contract in this TODO.
- Favorites/home agenda pagination behavior.

## Problem Statement
- `UserEventsRepository.fetchMyEvents()` currently calls the public upcoming agenda feed and filters locally by confirmed IDs.
- This creates redundant Home requests and couples `my events` to the wrong feed semantics.

## Canonical Contract Decision (Proposal)
- Use `/api/v1/agenda` with `confirmed_only=1` as the canonical Home "my events" query (occurrence-first, same schema as agenda).
- `confirmed_only=1` requires authentication; anonymous identities must receive `401 auth_required` or an empty list (decision to be enforced consistently).
- `confirmed_only=1` ignores geo distance filtering; origin may be used only to compute optional `distance_meters`, not to exclude confirmed items.
- Home requests should use `past_only=0` and a small `page_size` (default 10) to return upcoming + happening-now confirmed events only.

## Tactical Implementation Path
- Backend: implement `confirmed_only` filtering in `/api/v1/agenda` and `/api/v1/events/stream` based on active attendance commitments, and document the parameter.
- Flutter: update `UserEventsRepository.fetchMyEvents()` to call `ScheduleRepository.getEventsPage(confirmedOnly: true)` and map results to `VenueEventResume`; remove the dependency on `fetchUpcomingEvents()`.
- Keep `GET /api/v1/events/attendance/confirmed` for ID-only caching (agenda filters and invite logic), but stop using it to assemble Home "my events".

## Documentation Hooks (Required Before Implementation)
- Add `confirmed_only` to the agenda contract in `foundation_documentation/endpoints_mvp_contracts.md`.
- Register the "My Events" query contract update in `foundation_documentation/system_roadmap.md` with status `Defined`.

## Definition Of Done
- Canonical contract decision documented.
- A tactical implementation path defined for Flutter and, if needed, Laravel.
- Validation strategy defined.

## Validation Steps
- Review Home composition and request graph.
- Define target backend/client contract and expected tests.
- Validate Home `my events` no longer piggybacks on the generic agenda request.

## 2026-03-19 Code Reality Check
- `fetchMyEvents()` still piggybacks on public upcoming agenda data and filters locally:
  - `flutter-app/lib/infrastructure/repositories/user_events_repository.dart`
- Backend currently provides only confirmed IDs (`GET /api/v1/events/attendance/confirmed`), not a dedicated Home "my events" projection payload:
  - `flutter-app/lib/infrastructure/dal/dao/laravel_backend/user_events_backend/laravel_user_events_backend.dart`
  - `laravel-app/routes/api/project_tenant_public_api_v1.php`
  - `laravel-app/app/Http/Api/v1/Controllers/EventAttendanceController.php`
- Conclusion: this TODO is **not** just documentation drift; implementation work is still required to remove Home piggybacking on generic agenda fetches.
