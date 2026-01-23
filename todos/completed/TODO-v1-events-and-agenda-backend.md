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
- Tenant CRUD for events (create/update/delete/list)
- Invite‑driven confirmation only (no check‑in)
- Geo radius defaults driven by tenant settings (`map_ui.radius`)
- Event location is always derived from the venue Account Profile (no standalone event location)

## Out of Scope (MVP)
- `/api/v1/events/{event_id}/check-in` behavior and rules (deferred to vnext)
- Participants or actions in event DTO
- Agenda summary endpoint (`/agenda/summary`)
- Online event location rules

---

## A) Backend Tasks

### A1) Agenda/events endpoints (MOD-201)
- [x] ✅ Production‑Ready Implement `/api/v1/agenda` with page-based pagination + filters (search, past_only, confirmed_only, geo).
- [x] ✅ Production‑Ready Implement `/api/v1/events/{event_id}` detail payload aligned with agenda DTO (no participants/actions).
- [x] ✅ Production‑Ready Implement `/api/v1/events/stream` SSE (created/updated/deleted deltas aligned with filters).

### A2) Confirmed-only logic (MVP)
- [x] ✅ Production‑Ready `confirmed_only=true` means **invite acceptance only** (no check‑in in MVP).
- [x] ✅ Production‑Ready Ensure `is_confirmed` + `total_confirmed` reflect invite acceptance state.

### A3) Search scope (MVP)
- [x] ✅ Production‑Ready Search matches `title`, `content`, `location`, `artists[].display_name`, `venue.display_name` (case-insensitive).

### A4) Geo defaults (MVP)
- [x] ✅ Production‑Ready If `origin_lat/lng` provided, apply `max_distance_meters` using tenant settings:  
  `settings.map_ui.radius.{min_km,default_km,max_km}` (converted to meters).
- [x] ✅ Production‑Ready If geo filter yields no matches, fall back to unfiltered list.

### A5) DTO field alignment (MVP)
- [x] ✅ Production‑Ready `artists[].display_name` (Account Profile display name).
- [x] ✅ Production‑Ready `venue.display_name` (Account Profile display name).
- [x] ✅ Production‑Ready `artists[].id` and `venue.id` are **account_profile_id**.
- [x] ✅ Production‑Ready Remove `participants` and `actions` from payloads (MVP).

### A6) Tenant Event CRUD (MVP)
- [x] ✅ Production‑Ready Implement `GET /api/v1/events` (tenant management list).
- [x] ✅ Production‑Ready Implement `POST /api/v1/events` (tenant create/publish).
- [x] ✅ Production‑Ready Implement `PATCH /api/v1/events/{event_id}` (tenant update).
- [x] ✅ Production‑Ready Implement `DELETE /api/v1/events/{event_id}` (tenant delete/archive).
- [x] ✅ Production‑Ready Validate venue/artist IDs as Account Profiles (artist type for lineup).
- [x] ✅ Production‑Ready Expand venue/artists into summary blocks stored on the event.

### A7) Publication lifecycle (MVP)
- [x] ✅ Production‑Ready Event uses `publication` object:
  - `status`: `published|publish_scheduled|draft|ended`
  - `publish_at`: ISO-8601 (optional)
- [x] ✅ Production‑Ready Agenda + SSE include only events where:
  - `publication.status = published`
  - `publish_at <= now` (if missing, fallback to `created_at`)
- [x] ✅ Production‑Ready Drafts + scheduled are excluded from `/agenda` and `/events/stream`.
- [x] ✅ Production‑Ready `ended` is **manual** in MVP.
- [x] ✅ Production‑Ready Add hourly job to promote `publish_scheduled -> published`.

### A8) Event location derived from venue (MVP)
- [x] ✅ Production‑Ready Remove `location` from event store/update requests and DTOs.
- [x] ✅ Production‑Ready Enforce venue profile location on event creation (reject if missing).
- [x] ✅ Production‑Ready Remove `latitude/longitude` and `geo_location` overrides from event payloads.
- [x] ✅ Production‑Ready Derive `geo_location` exclusively from venue profile location.
- [x] ✅ Production‑Ready Update agenda search to drop event `location`.
- [x] ✅ Production‑Ready Update tests + contracts to reflect the removal.

---

## Decisions (Locked)
- Event lookup supports **slug or 24‑char ObjectId** (return both `id` and `slug`).
- **Participants removed** from MVP; **artists only** for lineup.
- **Actions removed** from MVP.
- **Confirmed_only = invite acceptance** (check‑in deferred).
- **Geo defaults** come from tenant settings (`map_ui.radius`).
- **Search scope** includes venue + artists display names.
- **Publication lifecycle**:
  - `publication.status` + `publication.publish_at` (published|publish_scheduled|draft|ended).
  - Hourly job flips `publish_scheduled -> published` when `publish_at <= now`.
  - `ended` is manual in MVP.

---

## Success Criteria
- Contracts implemented exactly as documented in `endpoints_mvp_contracts.md`.
- Agenda + event detail deliver invite arrays and confirmation state without actions/participants.
- Geo filters and search behave per scope above.
- SSE stream emits event deltas aligned to active filters.
