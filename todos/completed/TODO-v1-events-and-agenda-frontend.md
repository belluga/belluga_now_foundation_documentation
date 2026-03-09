# TODO (V1): Events Delivery (Catalog + Agenda + Tenant Admin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team + Flutter Team
**Objective:** Deliver Events as an independent functionality covering event catalog, agenda surfaces, realtime deltas, and tenant-admin event operations.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-backend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/system_roadmap.md`

---

## A) Ownership Boundary (Locked)
- [x] ✅ Production‑Ready Events owns `/api/v1/agenda`, `/api/v1/events/{event_id|slug}`, `/api/v1/events/stream`, and tenant/admin event CRUD.
- [x] ✅ Production‑Ready Event location follows canonical `location` + typed `place_ref`; `venue` is a projection resolved from `place_ref` when applicable.
- [x] ✅ Production‑Ready Event publication lifecycle is backend-owned (`published|publish_scheduled|draft|ended`).
- [x] ✅ Production‑Ready Events does not own invite transaction state; invite behavior and acceptance ownership are tracked in `TODO-v1-invites-implementation.md`.

---

## B) Backend Track (Events)

### B1) Baseline complete (locked)
- [x] ✅ Production‑Ready Agenda/detail/stream endpoints implemented.
- [x] ✅ Production‑Ready Publication filtering and scheduled publish promotion job implemented.
- [x] ✅ Production‑Ready Venue/artist profile validation implemented for CRUD.
- [x] ✅ Production‑Ready Geo defaults and fallback behavior implemented for agenda queries.

### B2) Open backend hardening
- [x] ✅ Production‑Ready Verify stream filter parity (`taxonomy/categories/tags`) with Flutter query encoding conventions.
- [x] ✅ Production‑Ready Keep event CRUD contract docs in sync with actual payload (`location`, `place_ref`, `artist_ids`, `occurrences`, `publication`; `venue_id` remains prohibited).

Ownership note:
- Invite-coupled `confirmed_only` source-of-truth alignment is explicitly owned by `TODO-v1-invites-implementation.md` (`D) Integration Criteria`).

---

## C) Flutter Track (Events)

### C1) Browse/search/detail
- [x] ✅ Production‑Ready Events list/search works end-to-end (paged, filters, past toggle).
- [x] ✅ Production‑Ready Search scope mirrors backend (`title`, `content`, `artists.display_name`, `venue.display_name`).
- [x] ✅ Production‑Ready Radius selector uses tenant settings bounds (`map_ui.radius.min/default/max`).
- [x] ✅ Production‑Ready Agenda/event requests include `Authorization` + `Accept: application/json`; stream includes `Authorization`.

### C2) SSE reconciliation
- [x] ✅ Production‑Ready SSE query params serialized defensively.
- [x] ✅ Production‑Ready On reconnect without `Last-Event-ID`, force `/agenda` page 1 reload.
- [x] ✅ Production‑Ready Disconnect/reconnect flow verified with deterministic rehydrate assertions.

### C3) Event detail alignment
- [x] ✅ Production‑Ready Venue + artists rendering, participants/actions removed for MVP.

Ownership note:
- Invite action semantics and confirmation source-of-truth are owned by `TODO-v1-invites-implementation.md` (`C1` + `D` sections) and are not blocking closure of this Events TODO.

### C4) Tenant-admin events UI
- [x] ✅ Production‑Ready Replace placeholder events screen with management list/create/edit flow.
- [x] ✅ Production‑Ready Add taxonomy term picker (`applies_to=event`) in event form.
- [x] ✅ Production‑Ready Persist and reload `taxonomy_terms` on create/update.
- [x] ✅ Production‑Ready Add focused tests for taxonomy selection and persistence.

---

## D) Acceptance Criteria
- [x] ✅ Production‑Ready Users can browse events and open detail with backend-aligned payloads.
- [x] ✅ Production‑Ready Event stream reconnect behavior is deterministic and rehydrates from `/agenda`.
- [x] ✅ Production‑Ready Tenant-admin can create/edit events with taxonomy terms persisted.

---

## E) Out of Scope
- `/api/v1/events/{event_id}/check-in` workflows (VNext).
- Invite lifecycle ownership (covered by Invite TODO).
- Invite accept/decline action semantics in event detail (covered by Invite TODO).

---

## F) Definition of Done
- [x] ✅ Production‑Ready Events functionality is stable independent of Invite internals.
- [x] ✅ Production‑Ready Contracts/docs/roadmap are synchronized for Events endpoints.
- [x] ✅ Production‑Ready Validation steps completed or explicitly documented as blocked.

---

## G) Validation Steps
- [x] ✅ Backend event tests exist for agenda/detail/stream + CRUD.
- [x] ✅ Production‑Ready `fvm flutter analyze`.
- [x] ✅ Production‑Ready Agenda filters/search/detail/SSE reconnect validated by integration + controller tests.
- [x] ✅ Production‑Ready Tenant-admin event create/edit taxonomy persistence validated by focused form/controller/repository tests.

Validation Notes (sync update):
- `fvm flutter analyze` clean (latest run in branch).
- Stream taxonomy parity regression test passing:
  - `test/infrastructure/dal/laravel_schedule_backend_test.dart`
- Agenda radius bounds unit coverage passing:
  - `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- Agenda stream reconnect deterministic rehydrate coverage passing:
  - `test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- Radius bounds/default propagation now contract-aligned (`settings.map_ui.radius.min_km/default_km/max_km`) across app bootstrap + agenda controllers:
  - `lib/domain/app_data/app_data.dart`
  - `lib/infrastructure/repositories/app_data_repository.dart`
  - `lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`
  - `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
  - `lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- Focused tenant-admin events tests passing:
  - `test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart`
  - `test/infrastructure/repositories/tenant_admin_events_repository_test.dart`
  - `test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart`
- Device integration flow passing on ADB:
  - `integration_test/feature_admin_dashboard_events_hidden_test.dart`
  - `integration_test/feature_admin_events_soft_delete_visibility_test.dart`
- Agenda integration flow passing on ADB (`--flavor belluga`):
  - `integration_test/feature_agenda_filters_regression_test.dart`
    - validates past toggle + invite filters + search by `title/content/artists/venue`.
  - `integration_test/feature_safe_area_and_agenda_appbar_test.dart`
- Event detail behavior validated:
  - `test/presentation/tenant/schedule/screens/event_detail_screen/event_detail_screen_test.dart`
  - `test/presentation/tenant/schedule/screens/event_detail_screen/controllers/event_detail_controller_test.dart`
- Ownership split applied:
  - Invite-owned items moved out of Events TODO scope and anchored in `TODO-v1-invites-implementation.md`.
