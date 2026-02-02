# TODO (V1): Remove Tenant Backend Fallback (Fail Fast)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
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
- [ ] ⚪ Pending Remove `kLocalEnvironmentFallback` usage in `AppDataRepository` and fail fast on backend fetch errors.
- [ ] ⚪ Pending Validate app init fails clearly when tenant backend is down (no boilerplate tenant).
- [ ] ⚪ Pending Add MVP todo item for removing remaining fallbacks (UI/data) + offline flow.

## D) Definition of Done
- [ ] ⚪ Pending App bootstrap does **not** load boilerplate tenant when backend is unreachable.
- [ ] ⚪ Pending App startup fails fast with a clear error path (no hidden fallback).
- [ ] ⚪ Pending MVP todo updated with a follow‑up task for removing remaining fallbacks.

## E) Validation
- [ ] ⚪ Pending Simulate tenant backend down; app must not proceed with fallback tenant.
