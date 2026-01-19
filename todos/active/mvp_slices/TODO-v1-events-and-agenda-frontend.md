# TODO (V1): Events & Agenda — Frontend (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Deliver the agenda browse + event detail UX aligned to backend contracts (no check‑in in MVP).

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-backend.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`

---

## A) UI + Controller Tasks

### A1) Browse/search
- [ ] ⚪ Events list/search works end-to-end (paged, filters, past toggle).
- [ ] ⚪ Search scope mirrors backend (`title`, `content`, `artists.display_name`, `venue.display_name`).
- [ ] ⚪ Radius selector uses tenant settings bounds (`map_ui.radius.min/default/max`).
- [ ] ⚪ Use backend CRUD payloads as the source for form fields (create/edit/manage events).

### A1.1) Endpoint usage notes (implement as acceptance requirements)
- [ ] ⚪ Browse uses `GET /api/v1/agenda` with filters: `search`, `categories[]`, `tags[]`, `taxonomy[]`, `past_only`, `confirmed_only`, `origin_lat`, `origin_lng`, `max_distance_meters`.
- [ ] ⚪ Detail uses `GET /api/v1/events/{event_id|slug}`; 24‑hex strings are treated as ObjectIds.
- [ ] ⚪ Realtime uses `GET /api/v1/events/stream` (SSE). On reconnect or missing `Last-Event-ID`, refresh page 1 from `/agenda`.
- [ ] ⚪ Tenant management uses `/api/v1/events` CRUD with `publication`, `venue_id`, `artist_ids`, `type` object (no `location` field).
- [ ] ⚪ Distance rendering uses `latitude`/`longitude` from event DTO (derived from venue profile).

### A2) Event detail
- [ ] ⚪ Event detail renders venue + artists summaries.
- [ ] ⚪ No participants section (artists only in MVP).
- [ ] ⚪ Remove manual confirm‑presence CTA; confirmation only via invite acceptance.
- [ ] ⚪ Invite actions available (send/accept/decline) with credited acceptance selector.

---

## B) Acceptance Criteria
- [ ] ⚪ Users can browse events and open event detail.
- [ ] ⚪ Invite actions are available from event detail and do not duplicate invites.

---

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
