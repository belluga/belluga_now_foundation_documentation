# TODO: Push Integration Tests (Flutter App)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Increase confidence in push bootstrap + registration behavior without E2E/device tests.

---

## Scope
- Add unit tests for `PushTransportConfigurator` to validate:
  - `baseUrl` uses `BellugaConstants.api.baseUrl`.
  - `tokenProvider` returns `null` when `AuthRepositoryContract.userToken` is empty and returns the token when set.
  - `deviceIdProvider` delegates to `AuthRepositoryContract.getDeviceId`.
  - `enableDebugLogs` matches `kDebugMode`.
- Add a small test seam (if needed) to validate push initialization wiring without changing runtime behavior.
  - Example seam: inject a repository factory or allow overriding the push registration method in tests.
- Add unit tests for push initialization wiring (non-web path):
  - Confirms `PushHandlerRepositoryDefault.init()` is invoked with a config built from `PushTransportConfigurator`.
  - Confirms `platformResolver` returns `BellugaConstants.settings.platform`.
- Add unit tests for the web guard:
  - When `kIsWeb` is true, push registration is skipped and does not attempt to init the repository.

## Out of Scope
- Any E2E/device or Firebase integration tests.
- Backend/Laravel tests.
- New push features or API changes.

## Definition of Done
- [x] ✅ Production‑Ready Unit tests cover the `PushTransportConfigurator` behaviors listed in scope.
- [x] ✅ Production‑Ready Push initialization wiring is testable via a minimal seam with no runtime change.
- [x] ✅ Production‑Ready Tests confirm repository `init()` is called for non-web paths and skipped on web.
- [x] ✅ Production‑Ready Tests pass via `fvm flutter test` in `flutter-app`.

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter test` in `flutter-app` passes.

## Decisions
- Prefer a minimal test seam (factory/override) rather than refactoring `ApplicationContract` architecture.
- Keep tests strictly unit-level; mock repositories and platform flags.
