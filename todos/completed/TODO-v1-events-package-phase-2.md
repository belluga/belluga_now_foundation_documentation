# TODO (V1): Events Package Phase 2 (True Decoupling)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team
**Objective:** Remove app-layer coupling from `belluga_events` internals by replacing direct dependencies with contracts and app-bound adapters.

---

## Scope
- Introduce package contracts for app-specific integrations:
  - taxonomy policy validation
  - venue/artist profile resolution
  - projection side effects (map POI sync)
- Emit package domain events for lifecycle changes (`created`, `updated`, `deleted`).
- Bind app adapters/listeners to package contracts/events.
- Ensure async side effects run after persistence consistency boundaries.

---

## Out of Scope
- Net-new Events API features.
- Invite lifecycle implementation changes.

---

## Standards/Exception Reference (Locked)
- Standards baseline for this phase is defined in:
  - `foundation_documentation/todos/completed/TODO-v1-events-package-core.md` (Section `F`).
- Approved deviations relevant to Phase 2 are defined in:
  - `foundation_documentation/todos/completed/TODO-v1-events-package-core.md` (Section `G`), especially `EX-01`.

---

## Pending Decisions (To Iterate)
- [x] ✅ Production‑Ready `D2-01` Phase boundary for occurrences: keep Phase 2 strictly single-date Event and reserve multi-occurrence model for Phase 3.
- [x] ✅ Production‑Ready `D2-02` Aggregate boundary now vs later: `EventOccurrence` remains a Phase 3 aggregate; Phase 2 stays Event-only at runtime model level.
- [x] ✅ Production‑Ready `D2-03` ID strategy for cross-domain links: keep integrations as `event_id` only in Phase 2; Phase 3 occurrence-scoped contracts use explicit `occurrence_id` (no optional-ID ambiguity).
- [x] ✅ Production‑Ready `D2-04` Publication semantics baseline: publication is Event-level only; publishing an Event publishes all of its occurrences.
- [x] ✅ Production‑Ready `D2-05` Decoupling mechanism: enforce package contracts + app adapters/listeners as the only extension mechanism (no new direct `App\\...` dependencies).

---

## Tasks
- [x] ✅ Production‑Ready Define contracts in package and replace direct `App\\...` imports in core services.
- [x] ✅ Production‑Ready Add domain events for event lifecycle changes.
- [x] ✅ Production‑Ready Implement app-layer listeners/adapters and service bindings.
- [x] ✅ Production‑Ready Route map POI sync through listener/job pipeline.
- [x] ✅ Production‑Ready Remove transitional migration wrappers/dependencies no longer needed.

---

## Validation Steps
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite; mandatory gate for Phase 2 completion).
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [x] ✅ Production‑Ready `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [x] ✅ Production‑Ready Add/update targeted tests for adapter binding and lifecycle side effects.

---

## Definition of Done
- [x] ✅ Production‑Ready Package core has no direct imports from host `App\\...` for domain logic.
- [x] ✅ Production‑Ready All side effects are exercised through contracts/events/listeners.
- [x] ✅ Production‑Ready Contracts/docs/roadmap synchronized with decoupled architecture.

---

## Implementation Notes (Completion)
- Package contracts were expanded to decouple HTTP/context integrations from host app:
  - `EventAccountResolverContract`
  - `EventTenantContextContract`
- Package request validators now use package-local constraints (`Belluga\\Events\\Support\\Validation\\InputConstraints`) instead of `App\\Support\\Validation\\InputConstraints`.
- Host app binds adapters for all Events package contracts (including new account/tenant context adapters) in `AppServiceProvider`.
- Transitional wrappers removed:
  - app-level Event controllers, requests, model, services, and scheduled job wrappers that only extended package classes.
  - project routes and console schedule now reference package classes directly.
- Side-effect pipeline remains package lifecycle event -> app listener -> queued projection job (`UpsertMapPoiFromEventJob` / `DeleteMapPoiByRefJob`).
- No API shape changes were introduced in this phase; roadmap endpoint statuses remain unchanged.

---

## Decision Log
- `D2-01`: Decided. Phase 2 remains single-date at domain/API level; occurrence support is deferred to Phase 3.
  - Implementation note: design new package contracts/events in an occurrence-ready way using explicit occurrence-scoped payloads in Phase 3 (without changing Phase 2 behavior).
- `D2-02`: Decided. `EventOccurrence` is not introduced as a first-class aggregate in Phase 2 runtime/domain model.
  - Design note: Phase 2 contracts/events may include extension-friendly payload structure, but no persistence or public API behavior change for occurrences.
- `D2-03`: Decided. Cross-domain references remain `event_id` in Phase 2.
  - Forward-compatibility note: Phase 3 occurrence-scoped contracts must use explicit `occurrence_id`; Phase 2 flows remain Event-only.
- `D2-04`: Decided. Publication state remains Event-level in Phase 2.
  - Publication note: Event remains the single publication source-of-truth in Phase 3 as well; occurrence publication fields are mirrored/derived only.
- `D2-05`: Decided. Package core must integrate with host app only via contracts + adapters/listeners.
  - Enforcement note: no new direct `App\\...` imports in package domain/application services.
  - Execution note: side effects (map POI sync, projection writes) run via package domain events + queued listeners/jobs after persistence.
