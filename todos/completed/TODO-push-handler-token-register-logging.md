# TODO (V1): Push Handler Token Register Logging

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter/push_handler)  
**Objective:** Add structured debug logs around token acquisition and device registration so we can verify whether register is firing and why it may skip after reinstall.

---

## Scope
- Log when push init starts token flow (pre‑`getToken()`).
- Log token retrieval result (success/empty/error) without printing the raw token (use hash or length only).
- Log when register request is sent (device_id + platform + token hash).
- Log when register request is skipped and why (e.g., missing token, missing auth token, previously cached state).

## Out of Scope
- Changing registration logic or auth flow.
- Adding telemetry/metrics beyond debug logs.
- Altering backend contracts.

## Definition of Done
- [x] ✅ Production‑Ready Logs exist for token acquisition start, token value presence, and register attempt.
- [x] ✅ Production‑Ready Logs include device_id, platform, and token length (never raw token).
- [x] ✅ Production‑Ready Logs include explicit skip reasons.

## Validation Steps
- [x] ✅ Production‑Ready Fresh install: logs show token acquired and register request fired.
- [x] ✅ Production‑Ready Reinstall: logs show either token acquired + register OR explicit skip reason.

## Decisions
- Never log raw push tokens; use token length only.

## Implementation Notes
- Logs added in `push_handler_repository_contract.dart` for token flow start, token presence, refresh, and register attempts.

## Questions to Close
- None.
