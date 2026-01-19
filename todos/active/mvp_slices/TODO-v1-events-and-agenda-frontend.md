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
- [ ] ⚪ Search scope mirrors backend (`title`, `content`, `location`, `artists.display_name`, `venue.display_name`).
- [ ] ⚪ Radius selector uses tenant settings bounds (`map_ui.radius.min/default/max`).

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

