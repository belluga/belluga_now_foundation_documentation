# TODO (V1): Events Package Phase 2 (True Decoupling)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
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

## Tasks
- [ ] ⚪ Define contracts in package and replace direct `App\\...` imports in core services.
- [ ] ⚪ Add domain events for event lifecycle changes.
- [ ] ⚪ Implement app-layer listeners/adapters and service bindings.
- [ ] ⚪ Route map POI sync through listener/job pipeline.
- [ ] ⚪ Remove transitional compatibility dependencies no longer needed.

---

## Validation Steps
- [ ] ⚪ `php artisan test tests/Feature/Events/AgendaAndEventsControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/Events/EventCrudControllerTest.php`.
- [ ] ⚪ `php artisan test tests/Feature/Map/MapPoisControllerTest.php`.
- [ ] ⚪ Add/update targeted tests for adapter binding and lifecycle side effects.

---

## Definition of Done
- [ ] ⚪ Package core has no direct imports from host `App\\...` for domain logic.
- [ ] ⚪ All side effects are exercised through contracts/events/listeners.
- [ ] ⚪ Contracts/docs/roadmap synchronized with decoupled architecture.
