# TODO (VNext): Proximity Preferences and "My Location" Origin
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-03-31

## Objective
Establish the VNext stream that moves Home proximity preferences from device-local persistence to identity-backed settings and introduces a first-class **"Minha localização"** setting owned by the user Profile area.

This setting must allow the user to choose between:
- using real-time device location, or
- selecting a manual point on the map as the reference origin.

The product goal is to support scenarios such as a user who is **planning a future trip to the city** and wants to see nearby events and Home agenda results relative to the place they intend to visit, not only their current live location.

## References
- `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/map_poi_module.md`

## Scope
- Define a backend/DB-backed preference model for proximity settings.
- Persist proximity preferences for both:
  - authenticated users, and
  - anonymous/device-bound identities.
- Define the Profile-owned surface where the user can view and edit the active location-origin preference.
- Introduce a canonical **"Minha localização"** setting with two modes:
  - `live_device_location`
  - `manual_map_location`
- Define the stored shape for the manual location origin:
  - latitude/longitude
  - optional label or place summary for UI reuse
  - timestamps/versioning as needed for traceability
- Define how `max_distance_meters` is persisted as a selected user preference without changing tenant-configured bounds/defaults.
- Define merge behavior so anonymous proximity preferences survive identity upgrade/account creation.
- Establish the first consumer surface for this feature as **Home Agenda/Home events only**.

## Out of Scope
- Replacing the V1 local-only persistence path immediately.
- The new V1 Home-only automatic fixed-reference fallback when the user is outside the tenant-city boundary; that behavior is now tracked in `foundation_documentation/todos/completed/TODO-v1-home-location-origin-reference-mode.md`.
- Applying the same persisted location-origin behavior to Discovery, Map, or generic Event Search in this slice.
- Full trip-planning product flows beyond proximity settings.
- New public route/screen IA outside the settings and Home-affecting flow.

## Definition of Done
- V1 vs VNext boundary is explicit:
  - V1 remains local/device persistence only.
  - VNext introduces backend/DB-backed identity settings.
- The location-origin modes and persistence semantics are documented clearly enough to drive backend and Flutter implementation later.
- The initial rollout boundary is explicit: **Home only first**, broader geo-surface adoption deferred.

## Validation Steps
- Manual doc review against `TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` to ensure no V1 text implies backend-backed persistence already exists.
- Manual doc review against `map_poi_module.md` to ensure origin semantics remain coherent with existing radius/reference-point rules.

## Decisions
- V1 local/device-only radius persistence is preserved as the current MVP behavior.
- VNext proximity preferences must be identity-backed and survive anonymous-to-authenticated merge.
- The user-facing editor for these preferences belongs to the Profile area, not to the Home header/info dialog.
- The user must be able to choose whether “my location” means:
  - live device location, or
  - a manually selected place on the map.
- The user must be able to prefer a fixed/manual reference location even when the current live device location is inside the tenant-supported range; V1 automatic classification must not become the permanent VNext constraint.
- A manually selected place is a valid primary origin for Home proximity experiences; this is not a fallback-only mode.
- The first rollout affects **Home only**; other geo consumers stay on their current rules until a dedicated follow-up promotes shared behavior.
- VNext still owns the broader user-controlled origin model (especially manual map-picked reference origin and backend-backed persistence).
- Persisted user preference for selected radius remains distinct from tenant-configured min/default/max bounds.
- When `user_fixed_location` is active, we may evaluate a direct toggle/action from the map snackbar/banner to switch back to live location without routing through the full settings editor.
- VNext should move location-origin user-facing copy/messages out of hardcoded Flutter strings and into environment/backend-provided contract fields, while preserving a deterministic Flutter fallback path during rollout or partial-backend adoption.

## Open Decisions To Close Later
- Canonical backend owner/package for user proximity settings.
- Whether anonymous settings live on the anonymous identity document itself or in a dedicated settings aggregate linked to that identity.
- Precision/privacy policy for stored manual coordinates.
- Whether live-device mode stores only the mode flag or also a last-known resolved origin snapshot for continuity.
- Sync strategy between local cache and backend-backed preference state.
