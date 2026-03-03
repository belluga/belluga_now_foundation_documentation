# TODO (V1): Backend Wiring Consolidation (Hybrid Migration)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Consolidate the granular backend wiring in `ModuleSettings._registerBackend` to support hybrid migration while preserving the existing `BackendContract` usage where needed.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-events-and-agenda-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

---

## A) Scope
- Keep `BackendContract` as the main aggregate while routing **partners/accountProfiles**, **agenda/events**, **app data**, and **tenant** backends through it.
- Maintain hybrid mode (mock ↔ live) where explicitly configured; avoid accidental fallback in production paths.
- Ensure DI wiring remains consistent across modules and repositories.

---

## B) Decisions to Confirm
- [x] ✅ Production-Ready BackendContract remains the single router; granular routing happens inside it (per-domain live/mock selection).
- [x] ✅ Production-Ready Mock backends allowed only via explicit debug override; release builds must force live.
- [x] ✅ Production-Ready Consolidate `AppDataBackendContract` + `TenantBackendContract` under `BackendContract` (single router entrypoint).
- [x] ✅ Production-Ready BackendContract should own and expose backend context (remove separate `BackendContext` singleton).

---

## C) Tasks

### C1) Consolidate backend wiring
- [x] ✅ Production-Ready Route `Partners/AccountProfiles` + `Agenda/Events` through `BackendContract` (live/mock selection).
- [x] ✅ Production-Ready Repositories resolve `partners` + `schedule` via `BackendContract` (no direct mock fallback).
- [x] ✅ Production-Ready Move `AppDataBackendContract` + `TenantBackendContract` behind `BackendContract` and remove direct DI for them.
- [x] ✅ Production-Ready Update `_registerBackend` to register only `BackendContract` (no per-domain backend contracts).
- [x] ✅ Production-Ready Move `BackendContext` ownership into `BackendContract` and stop registering it separately.

### C2) Repository alignment
- [x] ✅ Production-Ready Confirm `ModuleSettings` test hooks still allow override injection after app-data/tenant centralization.
- [x] ✅ Production-Ready Update repositories/clients to read context from `BackendContract` instead of `GetIt` singleton.

### C3) Validation
- [x] ✅ Production-Ready `fvm flutter analyze`
- [ ] ⚪ Targeted smoke test of agenda + partner discovery data flow (manual if no tests).
- [ ] ⚪ Validate app data + tenant fetch flows still resolve through `BackendContract`.
- [x] ✅ Production-Ready Validate backend HTTP layers consume context via `BackendContract`.

### C4) No fallback + offline UX
- [ ] ⚪ Remove any implicit backend fallback in production paths; hard-fail when backend is unavailable.
- [ ] ⚪ Implement explicit offline UX (screens/snacks) for backend-down scenarios, per flow.
- [ ] ⚪ Add connectivity snack: start with "Conectando…" and if it persists switch to "Parece que você está sem conexão à internet.".

---

## D) Definition of Done
- [x] ✅ Production-Ready `ModuleSettings` registers only `BackendContract` for backend access (no direct app-data/tenant DI).
- [x] ✅ Production-Ready App data + tenant access routed through `BackendContract`.
- [ ] ⚪ No unexpected mock fallback in live paths.
- [x] ✅ Production-Ready Analyzer is clean.
- [x] ✅ Production-Ready `BackendContext` is no longer a standalone singleton; `BackendContract` owns the context.
