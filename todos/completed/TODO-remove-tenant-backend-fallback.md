# TODO (V1): Remove Tenant Backend Fallback (Fail Fast)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Flutter Team  
**Objective:** Eliminate tenant bootstrap fallback behavior so the app fails fast when the tenant backend is unavailable.

---

## A) Scope
- Remove the tenant bootstrap fallback used when `/api/v1/environment` (or equivalent app data fetch) fails.
- Ensure app initialization surfaces the failure and does **not** silently load a boilerplate tenant.
- Document the remaining fallback removals as an MVP follow‑up task (UI/data fallbacks).

## B) Out of Scope
- Removing UI/data fallbacks beyond tenant bootstrap (images, map POIs, discovery labels, etc.).
- Designing an offline UX (screen/snack). This is tracked separately in MVP TODO.

## C) Tasks
- [x] ✅ Production‑Ready Remove `kLocalEnvironmentFallback` usage in `AppDataRepository` and fail fast on backend fetch errors.
- [x] ✅ Production‑Ready Validate app init fails clearly when tenant backend is down (no boilerplate tenant).
- [x] ✅ Production‑Ready Add MVP todo item for removing remaining fallbacks (UI/data) + offline flow (`todos/active/mvp_slices/TODO-v1-backend-wiring-consolidation.md`, section `C4`).

## D) Definition of Done
- [x] ✅ Production‑Ready App bootstrap does **not** load boilerplate tenant when backend is unreachable.
- [x] ✅ Production‑Ready App startup fails fast with a clear error path (no hidden fallback).
- [x] ✅ Production‑Ready MVP todo updated with a follow‑up task for removing remaining fallbacks.

## E) Validation
- [x] ✅ Production‑Ready Simulate tenant backend down; app must not proceed with fallback tenant.

## Validation Results (2026-02-21)
- Added: `test/infrastructure/repositories/app_data_repository_fail_fast_test.dart`
- Verified with:
  - `fvm flutter test --reporter expanded test/infrastructure/repositories/app_data_repository_fail_fast_test.dart`
- Assertion guarantees:
  - `AppDataRepository.init()` throws when backend fetch fails.
  - `AppDataRepository` does not seed `appData` after failure (no hidden fallback state).
