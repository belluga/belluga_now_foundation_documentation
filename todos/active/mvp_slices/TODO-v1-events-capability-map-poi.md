# TODO (V1): Events Capability - Map POI

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Deliver `map_poi` capability to control event map projection behavior through capability governance, preserving decoupling via Events lifecycle and projection contracts.

---

## Scope
- Implement `map_poi` as a concrete capability in Events capability registry/contracts.
- Add tenant-scoped capability settings and event-level configuration for map projection behavior.
- Enforce effective capability gate for map projection side effects (`tenant_available && event_enabled`).
- Define projection payload contract for occurrence-first map representation.
- Add/adjust migrations/indexes needed for POI read/query/update consistency in tenant scope.
- Add end-to-end tests for gate behavior, projection updates, and disable/reenable semantics.

---

## Out of Scope
- Frontend map rendering/UX changes.
- Generic geospatial recommendation engine.
- Non-events POI domains.
- Ticket-domain implementation (tracked in `TODO-v1-ticketing-package-integration.md`).

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-core.md` (Sections `F`, `G`).
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (capability governance baseline).
- `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md` (settings kernel baseline).

---

## Pending Decisions (To Iterate)
- [x] ✅ Production‑Ready `MPOI-01` Projection ownership boundary: `map_poi` controls discovery/projection behavior only; event canonical location modeling remains in Events core.
- [ ] ⚪ `MPOI-02` Occurrence mapping policy: one POI per occurrence vs consolidated event POI.
- [ ] ⚪ `MPOI-03` Publication coupling: unpublished event/occurrence projection visibility rules.
- [ ] ⚪ `MPOI-04` Soft-delete and reconciliation behavior for stale POIs.
- [x] ✅ Production‑Ready `MPOI-05` Tenant/event payload boundary: core location uses `location`/`place_ref`; `map_poi` stores only projection/discovery configuration.
- [ ] ⚪ `MPOI-06` Radius/filter index strategy and canonical query paths.
- [ ] ⚪ `MPOI-07` Projection idempotency/retry policy under async pipeline failures.
- [x] ✅ Production‑Ready `MPOI-08` Online regional discovery: online events can be discoverable in map feeds through capability-level `discovery_scope` (e.g., circle/polygon), independent from physical address.

---

## Tasks
- [ ] ⚪ Define and approve `map_poi` payload contracts.
- [ ] ⚪ Implement capability handler and register in capability registry.
- [ ] ⚪ Extend settings schema/values/patch flow for tenant map_poi settings.
- [ ] ⚪ Implement runtime gate integration in projection side-effect path.
- [ ] ⚪ Implement `discovery_scope` projection support for online/regional events (`location.mode=online` without requiring address).
- [ ] ⚪ Add/adjust migrations/indexes for projection/read paths.
- [ ] ⚪ Implement/expand tests for projection sync, disable/reenable, and partial updates.
- [ ] ⚪ Sync package README and roadmap/contracts documentation.

---

## Validation Steps
- [ ] ⚪ Capability-specific tests for map_poi gate, persistence, disable/reenable, and partial updates.
- [ ] ⚪ Projection consistency tests for create/update/delete/publication transitions.
- [ ] ⚪ Tenant-scoped migration/index validation for map POI paths.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ `map_poi` capability is integrated with registry/settings/runtime gate.
- [ ] ⚪ Projection behavior is deterministic, idempotent, and validated under retries.
- [ ] ⚪ Disable/reenable is non-destructive and validated.
- [ ] ⚪ Partial update semantics are atomic and validated.
- [ ] ⚪ Docs/contracts are synchronized with delivered behavior.

---

## Implementation Notes (Latest Iteration)
- Aligned rule: `map_poi` is an optional capability and must not own canonical event location fields.
- Aligned boundary:
  - Events core owns `location` semantics (`physical|online|hybrid`) and `place_ref`.
  - `map_poi` owns discovery/projection behavior and optional `discovery_scope`.
- Online regional case is explicitly supported through capability configuration, even when the event has no physical address.

---

## Decision Log
- `MPOI-01`: Decided. `map_poi` is projection/discovery behavior only; canonical location is core Events concern.
- `MPOI-05`: Decided. Payload boundary is split: core `location` vs capability `map_poi` configuration.
- `MPOI-08`: Decided. Online events may project to map via `discovery_scope` (circle/polygon) without physical venue/address.
