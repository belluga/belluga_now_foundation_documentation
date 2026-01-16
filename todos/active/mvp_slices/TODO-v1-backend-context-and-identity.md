# TODO (V1): Backend Context + Identity Consistency (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter)  
**Objective:** Centralize backend connection context after environment bootstrap and guarantee stable identity usage across telemetry and future API actions without losing mock swap flexibility.

---

## Scope
- Establish a shared `BackendContext` (base URLs, shared Dio, auth headers/interceptors) built after `AppDataRepository.init()`.
- Keep `BackendContract` as the swap point while letting individual adapters opt into `BackendContext`.
- Migrate Laravel-backed adapters (auth, push options, map POI, etc.) to read from `BackendContext` instead of `BellugaConstants` directly.
- Ensure mock adapters remain opt-in via DI overrides and never leak random or invalid IDs into production telemetry.

## Out of Scope
- Changing Laravel API responses or adding new endpoints.
- Refactoring mock data stores beyond adapter wiring.
- Removing `BackendContract` entirely.

## Definition of Done
- [x] ✅ Production backend defaults to Laravel auth; mocks only via DI overrides to prevent fake identity usage. (2026-01-14)
- [x] ✅ `BackendContext` registered after environment bootstrap and used by Laravel adapters. (2026-01-14)
- [x] ✅ Laravel adapters no longer construct their own base URLs; they use the shared context when available. (2026-01-14)
- [ ] ⚪ Mixpanel distinct IDs remain stable across app relaunches, and no mock IDs appear in telemetry or API calls.

## Validation Steps
- [ ] ⚪ Reinstall app, confirm `user_id` persists across relaunch and matches `account_users._id`.
- [ ] ⚪ Mixpanel events retain the same `distinct_id` across multiple launches of the same install.
- [ ] ⚪ Auth bootstrap fails fast if the backend is unreachable (no mock IDs generated).

## Decisions
- Triggered by Mixpanel validation: mock/production ID drift corrupted telemetry and could affect future API workflows (invites, confirmations). We are resolving this now to unblock telemetry/push validation safely.

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-and-push.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-frontend.md`
