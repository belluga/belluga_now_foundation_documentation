# TODO-v1 Home Favorites Query Contract

## Scope
- Establish the ideal data contract for Home favorites so the section stops issuing its own duplicate upcoming agenda fetch on initial load.
- Define whether favorites should consume a dedicated projection, shared home snapshot, or lazy follow-up query.

## Out Of Scope
- Implementing the contract in this TODO.
- `my events` query redesign.

## Problem Statement
- `FavoritesSectionController` currently fetches upcoming events directly on Home initialization just to reorder favorites.
- This duplicates agenda traffic and ties favorites ordering to an eager network call.

## Canonical Contract Decision (Proposal)
- Use a dedicated Favorites Preview projection (tenant-auth) instead of piggybacking on `/agenda` or a Home snapshot.
- Endpoint proposal: `GET /api/v1/favorites` returns account-profile favorites only and is ordered server-side by `next_occurrence_at` ascending, then `display_name`.
- Each favorite entry must include at minimum: `account_profile_id`, `slug`, `display_name`, `profile_type`, `avatar_url`, `next_occurrence_at` (nullable), `next_event_id` (nullable), `next_occurrence_id` (nullable).
- The pinned tenant favorite remains client-owned and is not part of the backend payload.

## Tactical Implementation Path
- Backend: implement the Favorites Preview projection with `next_occurrence_at` computed from the nearest upcoming occurrence tied to each favorited account profile.
- Flutter: extend `FavoritePreviewDTO` (or add a new DTO) to carry `next_occurrence_at` + `profile_type`, and remove `ScheduleRepository.fetchUpcomingEvents()` from `FavoritesSectionController` in favor of server ordering or `next_occurrence_at` sorting.
- Mocks: add deterministic `next_occurrence_at` values to mock favorites so Home ordering is stable without agenda fetch.

## Documentation Hooks (Required Before Implementation)
- Add the favorites endpoint contract under `foundation_documentation/endpoints_mvp_contracts.md` (Home + Discovery).
- Register the endpoint in `foundation_documentation/system_roadmap.md` with status `Defined`.

## Definition Of Done
- Canonical contract decision documented.
- Tactical implementation path defined.
- Validation strategy defined.

## Validation Steps
- Review Home composition and request graph.
- Define target backend/client contract and expected tests.
- Validate Home Favorites no longer triggers a duplicate upcoming agenda fetch on initial load.
