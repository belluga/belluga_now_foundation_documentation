# TODO (V1): Events Delivery (Catalog + Agenda + Tenant Admin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team + Flutter Team
**Objective:** Deliver Events as an independent functionality covering event catalog, agenda surfaces, realtime deltas, and tenant-admin event operations.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-backend.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/system_roadmap.md`

---

## A) Ownership Boundary (Locked)
- [x] ✅ Production‑Ready Events owns `/api/v1/agenda`, `/api/v1/events/{event_id|slug}`, `/api/v1/events/stream`, and tenant/admin event CRUD.
- [x] ✅ Production‑Ready Event location derives from venue Account Profile location (no standalone event location in MVP).
- [x] ✅ Production‑Ready Event publication lifecycle is backend-owned (`published|publish_scheduled|draft|ended`).
- [ ] ⚪ Events must not own invite transaction state; invite data in event payloads is projection/read-only.

---

## B) Backend Track (Events)

### B1) Baseline complete (locked)
- [x] ✅ Production‑Ready Agenda/detail/stream endpoints implemented.
- [x] ✅ Production‑Ready Publication filtering and scheduled publish promotion job implemented.
- [x] ✅ Production‑Ready Venue/artist profile validation implemented for CRUD.
- [x] ✅ Production‑Ready Geo defaults and fallback behavior implemented for agenda queries.

### B2) Open backend hardening
- [ ] ⚪ Keep `confirmed_only` semantics aligned with Invite acceptance source-of-truth once Invite backend is fully live.
- [ ] ⚪ Verify stream filter parity (`taxonomy/categories/tags`) with Flutter query encoding conventions.
- [ ] ⚪ Keep event CRUD contract docs in sync with actual payload (`venue_id`, `artist_ids`, `date_time_start/end`, `publication`).

---

## C) Flutter Track (Events)

### C1) Browse/search/detail
- [ ] ⚪ Events list/search works end-to-end (paged, filters, past toggle).
- [ ] ⚪ Search scope mirrors backend (`title`, `content`, `artists.display_name`, `venue.display_name`).
- [ ] ⚪ Radius selector uses tenant settings bounds (`map_ui.radius.min/default/max`).
- [ ] ⚪ Agenda/event requests include `Authorization` + `Accept: application/json`; stream includes `Authorization`.

### C2) SSE reconciliation
- [x] ✅ Production‑Ready SSE query params serialized defensively.
- [ ] ⚪ On reconnect without `Last-Event-ID`, force `/agenda` page 1 reload.
- [ ] ⚪ Manual smoke: disconnect/reconnect SSE and verify list refresh.

### C3) Event detail alignment
- [x] ✅ Production‑Ready Venue + artists rendering, participants/actions removed for MVP.
- [ ] ⚪ Remove remaining local-only confirmation assumptions after Invite backend acceptance is live.
- [ ] ⚪ Keep event detail actions routed through Invite endpoints for acceptance semantics.

### C4) Tenant-admin events UI
- [ ] ⚪ Replace placeholder events screen with management list/create/edit flow.
- [ ] ⚪ Add taxonomy term picker (`applies_to=event`) in event form.
- [ ] ⚪ Persist and reload `taxonomy_terms` on create/update.
- [ ] ⚪ Add focused tests for taxonomy selection and persistence.

---

## D) Acceptance Criteria
- [ ] ⚪ Users can browse events and open detail with backend-aligned payloads.
- [ ] ⚪ Event stream reconnect behavior is deterministic and rehydrates from `/agenda`.
- [ ] ⚪ Tenant-admin can create/edit events with taxonomy terms persisted.

---

## E) Out of Scope
- `/api/v1/events/{event_id}/check-in` workflows (VNext).
- Invite lifecycle ownership (covered by Invite TODO).

---

## F) Definition of Done
- [ ] ⚪ Events functionality is stable independent of Invite internals.
- [ ] ⚪ Contracts/docs/roadmap are synchronized for Events endpoints.
- [ ] ⚪ Validation steps completed or explicitly documented as blocked.

---

## G) Validation Steps
- [x] ✅ Backend event tests exist for agenda/detail/stream + CRUD.
- [ ] ⚪ `fvm flutter analyze`.
- [ ] ⚪ Manual smoke: agenda filters/search/detail/SSE reconnect.
- [ ] ⚪ Manual smoke: tenant-admin event create/edit taxonomy persistence.
