# TODO (V1): Events & Agenda (Browse, Detail, Presence)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Deliver the core events experience: browse/search, event detail, invite flows, and presence confirmation.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `foundation_documentation/system_roadmap.md`

---

## A) Backend Tasks

### A1) Agenda/events endpoints (MOD-201)
- [ ] ⚪ Ensure `/api/v1/agenda` contract is implemented or mocked (page-based pagination + filters + geo + confirmed_only) per `foundation_documentation/endpoints_mvp_contracts.md`
- [ ] ⚪ Ensure `/api/v1/events/stream` SSE emits delta events aligned with active filters (created/updated/deleted)
- [x] ✅ Remove `actions` from event DTO/contracts/mocks (no actions in MVP)
- [x] ✅ Remove `participants` from event DTO/contracts/mocks (artists only in MVP)
- [x] ✅ Update docs/contracts to remove `participants` (agenda module + endpoints contract)
- [ ] ⚪ Ensure event detail includes:
  - [ ] ⚪ `id`, `slug`, `type`, `title`, `content`, `location`
  - [ ] ⚪ `date_time_start`, `date_time_end` (or default duration)
  - [ ] ⚪ venue + artists projections
  - [ ] ⚪ invite-related arrays (`received_invites`, `sent_invites`, `friends_going`)

### A2) Presence confirmation
- [ ] ⚪ Confirm presence endpoint `/api/v1/events/{event_id}/check-in` (mock or implemented)
- [ ] ⚪ Ensure responses update confirmed/presence state deterministically

---

## B) Flutter Tasks

### B1) Browse/search
- [ ] ⚪ Events list/search works end-to-end (paged, filters, past toggle where supported)

### B2) Event detail
- [ ] ⚪ Event detail renders venue + artists summaries
- [x] ✅ Remove participants section from event detail UI (artists only)
- [ ] ⚪ Invite actions available (send/accept/decline) with credited acceptance selector (from invites TODO)
- [ ] ⚪ Confirm presence flow available and updates UI state

---

## C) Acceptance Criteria

- [ ] ⚪ Users can browse events, open event detail, and confirm presence
- [ ] ⚪ Invite actions are available from event detail and do not duplicate invites

---

## Answers Found (Mocks + Docs)

**Agenda contract (docs + mock):**
- `/agenda` is page-based with `page`, `page_size`, `past_only`, `search`, `confirmed_only`, optional geo (`origin_lat/lng`, `max_distance_meters`).
- “Happening now” uses `date_time_start <= now < date_time_end`, defaulting to `date_time_start + 3h` when end is missing.
- Sorting: upcoming/now ascending by `date_time_start`, past descending.
- Mock uses **50km** radius default; if no geo matches, fallback to unfiltered list.
- Mock search matches **title + content + location + artists[].name** (case-insensitive).

**Event DTO fields (mock):**
- `id` is a stable 24-char hex id; `slug` is a stable string (seed id).
- `venue` object includes `id`, `display_name`, `tagline`, `slug`, `logo_url`, `hero_image_url`.
- `received_invites`, `sent_invites`, `friends_going` are already surfaced in mock payloads.
- `participants` has been removed from MVP (artists only).

**Endpoint contracts already documented (endpoints_mvp_contracts.md):**
- `GET /agenda`, `GET /events/{event_id}`, `POST /events/{event_id}/check-in`, `GET /events/stream` SSE.
- Event detail includes `type`, `venue`, `artists`, invite arrays.

---

## Open Questions (Need Decision)

### Cluster A — Identity/Linkage (decide together)
- [x] ✅ **Event lookup identifier (Final):** `GET /events/{event_id}` accepts **slug or id**.  
  **Rule:** treat as id only if it is a **24‑char hexadecimal** ObjectId; otherwise treat as slug.  
  **Response:** always include both `id` and `slug`.
- [ ] ⚪ **Venue/Artist linkage:** Should `venue.id` and `artists[].id` be `account_profile_id` (preferred) or `account_id`?
- [x] ✅ **Venue/Artist linkage (Final):** Use `account_profile_id` for `venue.id` and `artists[].id` since profiles are the primary partner-facing entities.
- [x] ✅ **Participants (Final):** **Removed from MVP.**  
  Use **artists only** for lineup. Event creation must select **artists from Account Profiles with `profile_type=artist`**.  
  Participants may return post‑MVP with more granular roles.
- [x] ✅ **Event actions (Final):** **Do not use `actions` in MVP.**  
  Remove `actions` from DTO + backend contracts; no UI usage.  
  Note: this feature may not return post‑MVP.

### Cluster B — Presence/Confirmation (decide together)
- [x] ✅ **Confirmed_only logic (Final):** **Invite acceptance only.**  
  MVP does **not** handle check-in; `confirmed_only=true` filters by accepted invites.
- [x] ✅ **Presence rules (Deferred to VNext):** Check-in rules are deferred.  
  See `TODO-vnext-event-checkin.md` for geofence/time-window decisions.

### Cluster C — Feed Behavior (independent)
- [x] ✅ **Search fields (Final):** Search across `title`, `content`, `location`, `artists[].display_name`, **and `venue.display_name`**.
- [x] ✅ **Geo radius defaults (Final):** Agenda defaults and bounds are **tenant‑settings driven** (`map_ui.radius.{min_km,default_km,max_km}`).  
  The app reads tenant settings to build initial/default/max radius values.

### Cluster D — Summary Endpoint (depends on A/C)
- [x] ✅ **Event summary endpoint (Final):** **Not needed in MVP.**  
  The agenda calendar stripe/summary UI is unused; no `/agenda/summary` endpoint for MVP.  
  Cleanup tasks should remove unused summary DTOs/repository methods/UI remnants.
