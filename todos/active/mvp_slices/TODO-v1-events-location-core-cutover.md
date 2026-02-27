# TODO (V1): Events Core Location Cutover (Before Capabilities)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team
**Objective:** Establish canonical Events core location model (`location` + `place_ref`) before concrete capability execution, keeping `map_poi` as optional projection behavior.

---

## Scope
- Introduce canonical event `location` object at core model level.
- Introduce canonical `place_ref` shape (`type` + `id` + optional `metadata`) following typed-ref pattern used in event parties.
- Support `location.mode` values for non-physical events (`online`) and mixed cases (`hybrid`).
- Update Event write/read flow and occurrence mirror sync to persist/return the new core location model.
- Update agenda/stream serialization and query paths to use canonical location data while preserving existing behavior.
- Keep `map_poi` capability boundary explicit: discovery projection config (e.g., `discovery_scope`) is capability concern, not core location concern.
- Update tests and package README/docs to reflect the cutover.

---

## Out of Scope
- Concrete capability implementation (`limits`, `pricing_fees`, `inventory`, `map_poi`, `qr_checkin`, `combo`, `participant_student_binding`).
- Frontend form/UX contract changes.
- External map/recommendation engines beyond current POI projection boundaries.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md`.
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-capability-map-poi.md`.

---

## Pending Decisions (To Iterate)
- [x] ✅ Production‑Ready `LOC-01` Core location is not a capability.
  - Rule: location is a first-class Event core concern.
  - Rule: if behavior is mandatory for all events, it belongs to core, not capability.
- [x] ✅ Production‑Ready `LOC-02` Canonical reference shape uses typed ref pattern.
  - Decided `place_ref` shape: `{ type: string, id: string, metadata?: object }`.
  - Boundary: host applications map project-specific place entities (`venue`, `school_campus`, `classroom`, etc.) via adapters.
- [x] ✅ Production‑Ready `LOC-03` Location mode supports online and hybrid events.
  - Decided values: `physical`, `online`, `hybrid`.
  - Rule: `online` events do not require physical address fields.
- [x] ✅ Production‑Ready `LOC-04` `map_poi` remains optional capability over core location.
  - Rule: map/discovery configuration (including regional discovery coverage) belongs to `map_poi`, not core location.
  - Rule: online events can later project to map through capability-level `discovery_scope`.
- [x] ✅ Production‑Ready `LOC-05` Input hard-cutover strategy for event create/update contract.
  - Decided strategy: hard-cutover to canonical write contract (`location` + optional `place_ref`).
  - Rule: legacy `venue_id` is removed from event create/update request contract.
- [x] ✅ Production‑Ready `LOC-06` Core `location` minimum required fields per mode.
  - `physical`: requires `place_ref` with valid typed reference (`type`, `id`).
  - `online`: requires `location.online` payload and does not require physical address/coordinates.
  - `hybrid`: requires both physical (`place_ref`) and online (`location.online`) payload blocks.
- [x] ✅ Production‑Ready `LOC-07` Search/filter compatibility when event has no physical coordinates.
  - Geo-driven agenda/stream filters return only events with valid geographic basis.
  - Online-only events without geo basis are excluded from geo queries until `map_poi.discovery_scope` is active.

---

## Tasks
- [x] ✅ Production‑Ready Apply approved location contract decisions (`LOC-05..LOC-07`) across requests/services/query paths/tests.
- [x] ✅ Production‑Ready Refactor event request validation for canonical location contract.
- [x] ✅ Production‑Ready Refactor event management service and resolver boundaries to persist canonical location/place_ref.
- [x] ✅ Production‑Ready Refactor occurrence sync/query serialization to use canonical location data.
- [x] ✅ Production‑Ready Update map projection integration boundaries to consume new core fields without moving map semantics into core.
- [x] ✅ Production‑Ready Update/expand Events and Map tests for physical/online/hybrid scenarios.
- [x] ✅ Production‑Ready Update package README and roadmap/contracts documentation.

---

## Validation Steps
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [x] ✅ Production‑Ready Additional tests for mode-specific validation (`physical|online|hybrid`) and typed `place_ref`.
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite).

---

## Definition of Done
- [x] ✅ Production‑Ready Event/occurrence canonical location contract is implemented and validated.
- [x] ✅ Production‑Ready Typed `place_ref` pattern is active and decoupled from host concrete models.
- [x] ✅ Production‑Ready Online/hybrid flows are supported without forcing physical address.
- [x] ✅ Production‑Ready `map_poi` remains optional capability with clean boundary from core location.
- [x] ✅ Production‑Ready Docs/tests are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- This TODO is a pre-capability gate: concrete capability implementation starts only after this cutover is complete.
- Delivered in this iteration with strict LOC-05/06/07 adherence:
  - hard-cutover write contract (`location` + `place_ref`, legacy `venue_id` prohibited)
  - mode enforcement (`physical|online|hybrid`)
  - geo filter behavior without non-geo fallback
  - full Laravel suite green after cutover

---

## Decision Log
- `LOC-01`: Decided. Event location is core model concern, not capability.
- `LOC-02`: Decided. Use typed reference for place (`place_ref.type` + `place_ref.id` + optional metadata).
- `LOC-03`: Decided. Core location modes are `physical|online|hybrid`.
- `LOC-04`: Decided. `map_poi` remains optional capability and owns discovery projection semantics (e.g., `discovery_scope`), not core location fields.
- `LOC-05`: Decided. Event write contract is hard-cutover to canonical `location` + optional `place_ref`; legacy `venue_id` is removed.
- `LOC-06`: Decided. Minimum required payload by mode: `physical` => `place_ref`; `online` => `location.online`; `hybrid` => both.
- `LOC-07`: Decided. Geo queries include only events with valid geographic basis; online-only without geo remains out until `map_poi.discovery_scope` projection is enabled.
