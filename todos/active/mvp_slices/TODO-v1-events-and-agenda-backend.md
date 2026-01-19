# TODO (V1): Events & Agenda — Backend

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Backend Team (source of truth)  
**Objective:** Deliver the backend contracts and behaviors for Agenda + Event detail with invite‑driven confirmation (no check‑in in MVP).

---

## References
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `foundation_documentation/system_roadmap.md`

---

## Scope (MVP)
- `/api/v1/agenda` (paged feed + filters)
- `/api/v1/events/{event_id}` (event detail)
- `/api/v1/events/stream` (SSE deltas)
- Invite‑driven confirmation only (no check‑in)
- Geo radius defaults driven by tenant settings (`map_ui.radius`)

## Out of Scope (MVP)
- `/api/v1/events/{event_id}/check-in` behavior and rules (deferred to vnext)
- Participants or actions in event DTO
- Agenda summary endpoint (`/agenda/summary`)

---

## A) Backend Tasks

### A1) Agenda/events endpoints (MOD-201)
- [ ] ⚪ Implement `/api/v1/agenda` with page-based pagination + filters (search, past_only, confirmed_only, geo).
- [ ] ⚪ Implement `/api/v1/events/{event_id}` detail payload aligned with agenda DTO (no participants/actions).
- [ ] ⚪ Implement `/api/v1/events/stream` SSE (created/updated/deleted deltas aligned with filters).

### A2) Confirmed-only logic (MVP)
- [ ] ⚪ `confirmed_only=true` means **invite acceptance only** (no check‑in in MVP).
- [ ] ⚪ Ensure `is_confirmed` + `total_confirmed` reflect invite acceptance state.

### A3) Search scope (MVP)
- [ ] ⚪ Search matches `title`, `content`, `location`, `artists[].display_name`, `venue.display_name` (case-insensitive).

### A4) Geo defaults (MVP)
- [ ] ⚪ If `origin_lat/lng` provided, apply `max_distance_meters` using tenant settings:  
  `settings.map_ui.radius.{min_km,default_km,max_km}` (converted to meters).
- [ ] ⚪ If geo filter yields no matches, fall back to unfiltered list.

### A5) DTO field alignment (MVP)
- [ ] ⚪ `artists[].display_name` (Account Profile display name).
- [ ] ⚪ `venue.display_name` (Account Profile display name).
- [ ] ⚪ `artists[].id` and `venue.id` are **account_profile_id**.
- [ ] ⚪ Remove `participants` and `actions` from payloads (MVP).

---

## Decisions (Locked)
- Event lookup supports **slug or 24‑char ObjectId** (return both `id` and `slug`).
- **Participants removed** from MVP; **artists only** for lineup.
- **Actions removed** from MVP.
- **Confirmed_only = invite acceptance** (check‑in deferred).
- **Geo defaults** come from tenant settings (`map_ui.radius`).
- **Search scope** includes venue + artists display names.

---

## Success Criteria
- Contracts implemented exactly as documented in `endpoints_mvp_contracts.md`.
- Agenda + event detail deliver invite arrays and confirmation state without actions/participants.
- Geo filters and search behave per scope above.
- SSE stream emits event deltas aligned to active filters.

