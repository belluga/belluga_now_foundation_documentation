# TODO (V1): Events & Agenda — Frontend (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Deliver the agenda browse + event detail UX aligned to backend contracts (no check‑in in MVP).

---

## References
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-backend.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`

---

## A) UI + Controller Tasks

### A1) Browse/search
- [ ] ⚪ Events list/search works end-to-end (paged, filters, past toggle).
- [ ] ⚪ Search scope mirrors backend (`title`, `content`, `artists.display_name`, `venue.display_name`).
- [ ] ⚪ Radius selector uses tenant settings bounds (`map_ui.radius.min/default/max`).

### A1.1) Endpoint usage notes (implement as acceptance requirements)
- [ ] ⚪ Browse uses `GET /api/v1/agenda` with filters: `search`, `categories[]`, `tags[]`, `taxonomy[]`, `past_only`, `confirmed_only`, `origin_lat`, `origin_lng`, `max_distance_meters`.
- [x] ✅ Detail uses `GET /api/v1/events/{event_id|slug}`; 24‑hex strings are treated as ObjectIds.
- [x] ✅ Realtime uses `GET /api/v1/events/stream` (SSE). On reconnect or missing `Last-Event-ID`, refresh page 1 from `/agenda`.
- [x] ✅ Distance rendering uses `latitude`/`longitude` from event DTO (derived from venue profile).
- [x] ✅ Map uses schedule events for **current date only** and filters by location radius.
- [ ] ⚪ Agenda/event requests include `Authorization: Bearer <AuthRepository.userToken>` and `Accept: application/json`; event stream includes `Authorization` header.
- [ ] ⚪ Fix analyzer error from missing `InviteFilter` import in Home agenda empty state.

### A2) Event detail
- [x] ✅ Event detail renders venue + artists summaries.
- [x] ✅ No participants section (artists only in MVP).
- [x] ✅ Remove manual confirm‑presence CTA; confirmation only via invite acceptance.
- [x] ✅ Invite actions available (send/accept/decline) with credited acceptance selector.

---

## B) Acceptance Criteria
- [ ] ⚪ Users can browse events and open event detail.
- [ ] ⚪ Invite actions are available from event detail and do not duplicate invites.
- [ ] ⚪ Empty agenda states render a clear zero‑state message (no crash/blank screen).

---

## C) Out of Scope
- Tenant admin event CRUD UI (managed by separate tenant admin TODO).

## D) Definition of Done
- [ ] ⚪ Agenda list + detail flows are wired to `/api/v1/agenda` and `/api/v1/events/{event_id|slug}` with the documented filters.
- [ ] ⚪ SSE subscription to `/api/v1/events/stream` resyncs by reloading page 1 when `Last-Event-ID` is missing.
- [ ] ⚪ Map event panel lists only today’s events within the active location radius.
- [ ] ⚪ Agenda zero‑state is contextual (default vs filtered/search).

## E) Validation Steps
- [x] ✅ `fvm flutter analyze`
- [ ] ⚪ Manual smoke: agenda list, search, open detail, toggle past/confirmed filters, verify artists/venue rendering.
- [ ] ⚪ Manual smoke: disconnect/reconnect SSE and confirm list refresh.

## Decisions (Locked)
- Participants removed from MVP; artists only.
- Actions removed from MVP.
- Confirmed_only uses invite acceptance; no check‑in.
- Geo defaults driven by tenant settings.
- Search scope includes venue + artist display names.

## Backend constraints to respect
- `publication` is required on event create; `publish_scheduled` requires `publish_at`.
- `type.icon` and `type.color` must be strings if provided (omit when unknown).
- `past_only`/`confirmed_only` should be sent as `1/0` to avoid boolean validation edge cases.
- Event creation fails if venue profile lacks a `location` (GeoJSON); venue must be POI‑enabled and have location set.
