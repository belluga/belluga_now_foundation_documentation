# TODO (V1): Backend Wiring Consolidation (Hybrid Migration)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Completed
**Owners:** Flutter Team
**Objective:** Consolidate backend wiring in `ModuleSettings` behind `BackendContract`, remove implicit backend fallback during bootstrap, and preserve explicit startup connectivity UX.

---

## Scope
- Keep `BackendContract` as the runtime aggregate for backend access.
- Keep backend context ownership inside `BackendContract` and remove standalone context ownership patterns.
- Ensure bootstrap fails fast when backend is unavailable (no hidden fallback tenant/app data).
- Preserve explicit startup retry UX with deterministic connectivity messaging.

---

## Completion Summary
- [x] ✅ Production-Ready `ModuleSettings` registers backend access through `BackendContract` and keeps test override hooks (`backendBuilderForTest`) in place.
- [x] ✅ Production-Ready Backend context is owned/set through `BackendContract` during bootstrap.
- [x] ✅ Production-Ready App bootstrap fails fast when backend fetch fails (no silent fallback state).
- [x] ✅ Production-Ready Startup retry UX includes "Conectando..." and delayed offline hint ("Parece que você está sem conexão à internet.").
- [x] ✅ Production-Ready Historical active-lane file content was recovered and archived after accidental wipe in active lane (`27a74bd`).

---

## Validation Evidence
- `lib/application/router/modular_app/module_settings.dart`
  - backend registration and test override entrypoint (`backendBuilderForTest`, `BackendContract` registration, context set via `setContext`).
- `lib/infrastructure/repositories/app_data_repository.dart`
  - bootstrap fetch path uses backend fetch + no fallback seed path.
- `test/infrastructure/repositories/app_data_repository_fail_fast_test.dart`
  - explicit fail-fast assertion when backend is unavailable.
- `lib/main.dart`
  - startup error/retry UX and delayed offline connectivity hint messaging.
- `foundation_documentation/todos/completed/TODO-remove-tenant-backend-fallback.md`
  - explicit closure evidence for fallback removal stream.

---

## References
- `foundation_documentation/todos/completed/TODO-v1-backend-context-and-identity.md`
- `foundation_documentation/todos/completed/TODO-remove-tenant-backend-fallback.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-module-settings-refactor.md`
