# TODO (V1): Add Push Action idempotency_key From Flutter

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Ensure push action reports include required `idempotency_key` to avoid 422 and unblock delivery/opened tracking.

---

## Scope
- Add `idempotency_key` to push action payloads from `PushApiClient.reportAction`.
- Generate a stable idempotency key per action using FCM `messageId` + action + step index (fallback to `push_message_id` when missing).
- For `clicked` actions, include `button_key` in the idempotency key to avoid collisions across multiple CTAs on the same step.
- Update background delivery reporter to include `idempotency_key`.
- Log push fetch responses/payloads (sanitized) to aid debugging.
- Show push content even if action reporting fails.
- Add tests that assert distinct idempotency keys per button on the same step and stable keys across foreground/background reporting.

## Out of Scope
- Backend validation changes.
- Push message rendering/layout logic.

## Definition of Done
- [x] ✅ Production‑Ready `reportAction` includes `idempotency_key` for all actions.
- [x] ✅ Production‑Ready Background delivery reports succeed (no 422).
- [x] ✅ Production‑Ready Foreground actions succeed (no 422).
- [x] ✅ Production‑Ready Debug logs show payload/response details without blocking UI.
- [x] ✅ Production‑Ready Push UI still renders when action reporting fails.
- [x] ✅ Production‑Ready Clicking multiple buttons on the same step increments distinct click counts (no idempotency collisions).
- [x] ✅ Production‑Ready push_handler tests cover idempotency key generation with and without `button_key`.

## Validation Steps
- [x] ✅ Production‑Ready Send a push: `/actions` returns 200 for delivered/opened.
- [x] ✅ Production‑Ready No 422 errors in device logs.
- [x] ✅ Production‑Ready Push UI still appears when `/actions` fails.
- [x] ✅ Production‑Ready Click multiple buttons on the same step; `button_click_counts` increments per button key.
- [x] ✅ Production‑Ready Run `fvm flutter test` in `push_handler` and confirm idempotency key tests pass.

## Decisions
- Idempotency key format: `action:{message_id|push_message_id}:{action}:{step_index}:{device_id}` (no PII).
- Extend idempotency key to include `button_key` when present (especially `clicked`).
- Debug logs sanitize payloads (no tokens, minimal message fields).

## Questions to Close
- None.

## References
- `flutter-app/lib/infrastructure/services/push/push_api_client.dart`
- `flutter-app/lib/application/push/push_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/push_background_reporter.dart`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushMessageActionRequest.php`
