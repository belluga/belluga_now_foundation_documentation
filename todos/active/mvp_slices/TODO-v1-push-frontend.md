# TODO (V1): Push Notifications (Frontend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter)  
**Objective:** Finalize remaining push UX and routing gaps for V1.

---

## Scope
- Push tap routing for invite payloads.
- Silent invite list update driven by push payload (no extra backend refresh).
- Add a push listener stream in `push_handler` that emits fetched payload data.
- Push validation steps (tap handling, registration checks).

## Out of Scope
- Telemetry identity stitching and Mixpanel delivery guarantees.
- Backend push fan-out or payload schema changes.

## Definition of Done
- [x] ✅ Production‑Ready Invite push tap routes into invite flow.
- [x] ✅ Production‑Ready Push tap does not duplicate routes when already on target event.
- [x] ✅ Production‑Ready Invite accept UX handles offline attempt (enqueue + toast + reconcile).
- [x] ✅ Production‑Ready Invite accept remains non-optimistic (events fire after success response).
- [x] ✅ Production‑Ready No extra “processing” state introduced for invite flows.
- [ ] ⚪ Silent invite list update uses payload data (no list refetch).
- [ ] ⚪ `push_handler` emits raw push data merged with fetched payload data (payload replaces `data`).
- [ ] ⚪ Push validation steps completed.

## Validation Steps
- [ ] ⚪ Smoke test: receive a push notification and confirm tap handling resolves the correct in-app surface.
- [ ] ⚪ Verify logs show Firebase init, token acquisition, and `/api/v1/push/register` success.
- [ ] ⚪ App registers push device with anonymous token when user is not logged in.
- [ ] ⚪ Push registration payload uses backend-supported platform values.

## Decisions
- Keep invite acceptance non-optimistic; emit events only after success.
- Use enqueue + toast for offline invite acceptance.
- Invite push routing uses `invite={{invite_id}}` query param; open invite flow and surface the invite at top of the stack.
- If invite not found or expired, ignore and show the stack normally.
- Do not show toast on push receipt; rely on push handler settings for UI.
- Use the existing in-app retry queue for network instability (no new queue system).
- Push stream emits the raw push payload merged with fetched payload data (replace `data` with fetched payload).
