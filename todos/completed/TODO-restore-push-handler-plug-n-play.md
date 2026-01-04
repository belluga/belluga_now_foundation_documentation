# TODO (V1): Restore Push Handler Plug'n'Play (User Token)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter + push_handler)  
**Objective:** Reconstruct missing push_handler behavior from recovered files/logs while keeping the plugin plug'n'play and treating anonymous users as the same `user_token` flow.

---

## Scope
- Compare recovered `push_handler` files against current repository state and identify missing behavior required for plug'n'play background handling and delivery reporting.
- Ensure `push_handler` uses only `AuthRepository.userToken` (no separate anonymous token or secure storage access for auth).
- Ensure push init happens after `AuthRepository` can provide a `userToken` (anonymous or authenticated).
- Validate background entrypoint rehydrates persisted transport config and reports delivery without main isolate wiring.
- Restore or reintroduce any missing tests that validate background queue flush + auto-present behavior if they were removed.
- Add non-E2E tests to increase confidence in the anonymous token + push registration flow (repository + transport layer).

## Out of Scope
- Backend push delivery changes (Laravel package behavior, FCM credential storage).
- Telemetry event taxonomy changes or new analytics definitions.
- Refactors unrelated to push handler initialization or background delivery reporting.

## Definition of Done
- [x] ✅ Production‑Ready `push_handler` remains plug'n'play: app only provides `transportConfig`, `navigationResolver`, and background callback; no extra wiring in `main.dart`.
- [x] ✅ Production‑Ready `AuthRepository.userToken` is the sole token used for push registration (anonymous == user_token).
- [x] ✅ Production‑Ready App init guarantees `userToken` is available before push handler initialization.
- [x] ✅ Production‑Ready Background entrypoint can report delivery using persisted config, without main isolate state.
- [x] ✅ Production‑Ready Any recovered tests needed for background queue flush and auto-present are restored and passing.
- [x] ✅ Production‑Ready New tests cover anonymous token issuance + registration orchestration without E2E.

## Progress
- [x] ✅ Production‑Ready Created `PushTransportConfigurator` in Flutter app to centralize config and rely only on `AuthRepository.userToken`.
- [x] ✅ Production‑Ready Application init now uses `PushTransportConfigurator` for push handler setup.
- [x] ✅ Production‑Ready AuthRepository now issues an anonymous identity when no `userToken` is present and stores the returned token in `user_token`.
- [x] ✅ Production‑Ready Anonymous identity HTTP call wired via `LaravelAuthBackend` with mock fallback.
- [x] ✅ Production‑Ready Added unit tests covering anonymous token issuance and stored-token skip behavior.

### Provisional Notes
- None.

## Validation Steps
- [x] ✅ Production‑Ready Confirm `push_handler` init does not require navigator key or route resolver.
- [x] ✅ Production‑Ready Verify push registration uses `userToken` in logs (token length only).
- [x] ✅ Production‑Ready Receive a push in background and confirm delivery is reported when app is terminated.
- [x] ✅ Production‑Ready Resume app and verify queued background deliveries auto-present and clear from the queue.
- [x] ✅ Production‑Ready Unit/integration tests pass for new anonymous token + register flow coverage.

## Decisions
- Anonymous token is not a separate flow; it is the same `user_token`.
- App code must not read secure storage for auth tokens; push handler consumes `AuthRepository.userToken`.

## References
- Removed restored_files references after cleanup.
- `foundation_documentation/todos/active/TODO-push-metrics-step-and-clicks.md`
- `flutter-app/lib/application/application_contract.dart`
- `/home/elton/Dev/repos/flutter-packages/push_handler/lib`
