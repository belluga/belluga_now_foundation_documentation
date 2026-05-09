# TODO (VNext): ModuleSettings Refactor & Bootstrap Separation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Split `ModuleSettings` responsibilities into focused bootstrap components to reduce coupling and improve testability.

---

## A) Scope
- Extract non-module concerns from `ModuleSettings` (DI, repositories, context, push wiring, route param helpers).
- Keep `ModuleSettings` as a thin orchestrator that registers modules and invokes bootstrap registrars.
- Preserve existing runtime behavior and initialization order.

---

## B) Proposed Structure (Draft)
- `BackendRegistrar`: BackendContract + routing policy + backend context setup.
- `RepositoryRegistrar`: register + init repositories (app data, auth, tenant, telemetry).
- `PushRegistrar`: push handlers, resolvers, configurators.
- `RouteParamResolver`: route parameter helper(s) for deep-link/event slug resolution.

---

## C) Tasks
- [ ] ⚪ Map current `ModuleSettings` responsibilities to registrars and confirm target file locations.
- [ ] ⚪ Implement registrars and update `ModuleSettings` to delegate.
- [ ] ⚪ Update tests/DI overrides (ModuleSettings constructor hooks) to match new structure.
- [ ] ⚪ `fvm flutter analyze`
- [ ] ⚪ Smoke test app bootstrap (auth, tenant, app data, push, schedule).

---

## D) Definition of Done
- [ ] ⚪ `ModuleSettings` only orchestrates registrars + module registration.
- [ ] ⚪ Bootstrap order preserved; no behavior regressions.
- [ ] ⚪ Analyzer clean; targeted smoke verification complete.
