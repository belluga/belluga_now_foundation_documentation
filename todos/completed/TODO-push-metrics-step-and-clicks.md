# TODO (V1): Push Metrics — Step Views & Clicks

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter + Laravel)  
**Objective:** Ensure step view counts, button click counts, and unique click metrics are recorded for push messages, and centralize push-specific fetching/handling inside the `push_handler` plugin where appropriate.

---

## Scope
- Trace how `step_view_counts`, `button_click_counts`, `clicked_count`, and `unique_clicked_count` are recorded in the backend.
- Validate that the Flutter client sends the expected action payloads (step viewed, button clicked) with idempotency keys.
- Fix any missing action reporting in Flutter or the Laravel handler so metrics populate.
- Remove `opened` action recording from push message fetch; rely on presenter-driven `opened`.
- Add debug logging in Flutter (push_handler) for action reports (`opened`, `step_viewed`, `clicked`, `dismissed`) with payload fields needed to validate metrics.
- [x] ✅ Production‑Ready Delegate push message fetching/parsing to `push_handler` (avoid duplicating plugin logic in app code).
- [x] ✅ Production‑Ready Audit the Flutter app for push-specific logic that should live in `push_handler`, and relocate or wrap it as needed.
- [x] ✅ Production‑Ready Audit existing `push_handler` orchestration/presentation features and remove duplicate app-level push orchestration/presentation where the plugin already covers it.
- [x] ✅ Production‑Ready Move Firebase Messaging listener/initial message handling into `push_handler` so the app only wires callbacks/configuration.
- [x] ✅ Production‑Ready Remove push-layer access to secure storage and any parallel auth services; push must read a single `userToken` from `AuthRepository` (anonymous users still have `userToken`).
- [x] ✅ Production‑Ready Move Firebase initialization out of `PushCoordinator` into the application initialization flow (ApplicationContract with platform-specific wiring).
- [x] ✅ Production‑Ready Remove `AnonymousAuthService` and any `anonymousToken` persistence; anonymous users still use `userToken`.
- [x] ✅ Production‑Ready Remove the shared `StorageKeys` dumping ground by relocating key ownership to repositories:
  - `userToken` owned by `AuthRepository`.
  - `deviceId` owned by `AuthRepository` (user identity lifecycle).
  - `apiBaseUrl` owned by `AppDataRepository`.
  - `tenantId` owned by `TenantRepository`.
- [x] ✅ Production‑Ready Remove `PushCoordinator` entirely; app should rely on `push_handler` plug’n’play coordination.
- [x] ✅ Production‑Ready Replace `routeResolver` + `navigatorKey` with a `navigationResolver` callback so navigation remains app‑owned and the plugin stays agnostic.
- [x] ✅ Production‑Ready Add a top‑level background handler in `push_handler` and register it from the repository init (no main.dart wiring).
- [x] ✅ Production‑Ready Stop parsing `MessageData` directly from FCM payload in `PushHandler`; emit raw messages and let the repository fetch/parse from API.
- [x] ✅ Production‑Ready Persist minimal push transport config in `push_handler` so background entrypoint can rehydrate and report delivery.
- [x] ✅ Production‑Ready Flush the background delivery queue when the app returns to foreground (lifecycle resume), not only on initial init.
- [x] ✅ Production‑Ready Auto-present queued background messages on app resume (foreground) as if just received, skipping if expired.
- [x] ✅ Production‑Ready Remove queued background entries when the message is presented in foreground to avoid duplicate opens.
- [x] ✅ Production‑Ready Add an integration test in the Flutter app to validate queue flush + auto-present on resume.
- [x] ✅ Production‑Ready Ensure the integration test dismisses the auto-presented UI so queue flushing can complete.
- [x] ✅ Production‑Ready Expand push_handler tests to cover all layouts, button actions, step_viewed events, and navigationResolver behavior for maximal confidence.

## Out of Scope
- Changing analytics definitions or introducing new metrics.
- Reworking push delivery logic.
- Refactoring unrelated app networking/services.

## Definition of Done
- [x] ✅ Production‑Ready Step view counts increment when users progress through steps.
- [x] ✅ Production‑Ready Button click counts and unique clicks increment when users tap a push action.
- [x] ✅ Production‑Ready Push message fetch/parsing flows through `push_handler` (no duplicate app-layer parsing).
- [x] ✅ Production‑Ready Push-specific responsibilities are consolidated into `push_handler` with clear app-facing API.
- [x] ✅ Production‑Ready App no longer registers Firebase Messaging listeners directly; `push_handler` owns listener + initial message wiring.
- [x] ✅ Production‑Ready `PushCoordinator` no longer initializes Firebase or touches secure storage; it relies on `AuthRepository.userToken` only.
- [x] ✅ Production‑Ready `AnonymousAuthService` removed; anonymous identity handled by `AuthRepository`.
- [x] ✅ Production‑Ready `StorageKeys` removed or trimmed to repo‑owned keys (no cross-domain dumping).
- [x] ✅ Production‑Ready `PushCoordinator` removed; push init + coordination handled solely by `push_handler`.
- [x] ✅ Production‑Ready Plugin accepts `navigationResolver` and no longer requires `navigatorKey` or `routeResolver`.
- [x] ✅ Production‑Ready Background handler is top‑level inside `push_handler` and registered during repository init.
- [x] ✅ Production‑Ready FCM foreground handler no longer parses `MessageData` directly; repository handles fetch/parse.
- [x] ✅ Production‑Ready Background entrypoint rehydrates transport config and reports delivery without main isolate.
- [x] ✅ Production‑Ready Background delivery queue is flushed on app resume (foreground), not just cold start.
- [x] ✅ Production‑Ready Background queued messages are auto-presented on app resume when not expired.
- [x] ✅ Production‑Ready Foreground presentation clears queued entries (no double-present).
- [x] ✅ Production‑Ready Integration test validates resume auto-present + queue clearing behavior.
- [x] ✅ Production‑Ready Test suite covers all layouts (popup, bottomModal, snackBar, actionButton), steps, and button click reporting.

## Validation Steps
- [x] ✅ Production‑Ready Send a push with multiple steps and buttons, then verify metrics update for step views and button clicks.
- [x] ✅ Production‑Ready Trigger a push fetch and confirm it uses `push_handler` in logs or tracing.
- [x] ✅ Production‑Ready Fetching message data no longer records `opened`; presenter is the sole source for `opened`.
- [x] ✅ Production‑Ready Debug console shows action report logs with action, step_index, button_key, and idempotency_key.
- [x] ✅ Production‑Ready Confirm `PushCoordinator` no longer uses `FirebaseMessaging.onMessage*` or `getInitialMessage`.
- [x] ✅ Production‑Ready Confirm Firebase init happens in Application init flow (not in PushCoordinator) and push still works.
- [x] ✅ Production‑Ready Verify `AuthRepository` persists `userToken` + `deviceId` and no other repo reads those keys directly.
- [x] ✅ Production‑Ready Confirm no app code references `PushCoordinator`.
- [x] ✅ Production‑Ready Confirm `navigationResolver` is used for all push navigation actions without direct navigator access.
- [x] ✅ Production‑Ready Confirm background delivery report works without main.dart handler.
- [x] ✅ Production‑Ready Validate queued background deliveries are flushed after reopening the app (resume).
- [x] ✅ Production‑Ready Validate queued background messages auto-present on resume and are skipped when expired.
- [x] ✅ Production‑Ready Integration test dismisses the auto-presented UI and confirms queue flush completes.

## Decisions
- Use existing action types; do not introduce new metric categories.
- Move all push transport responsibilities into `push_handler` (message fetch, device register/unregister, action reporting, token/permission handling, background delivery reporting).
- Rely on presenter-driven `opened`; remove server-side `opened` creation in fetch controllers.

## Questions to Close
- None.

## References
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `/home/elton/Dev/repos/flutter-packages/push_handler/lib`
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushMetricsService.php`
