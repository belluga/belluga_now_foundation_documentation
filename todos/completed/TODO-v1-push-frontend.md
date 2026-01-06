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
- Add a reusable push-aware repository hook for payload-driven updates.
- Push validation steps (tap handling, registration checks).
- Add a push_handler presentation gate/mode to defer UI until app readiness.
- Ensure init stack shows push above InviteFlow/Home without flashing.
- Add race-condition debug logs in app init stack + push_handler presenter.
- Add push_handler de-duplication to avoid replaying queued pushes.
- Ensure only the latest queued push is presented.
- Clear the queue when opening the app via push tap.

## Out of Scope
- Telemetry identity stitching and Mixpanel delivery guarantees.
- Backend push fan-out or payload schema changes.

## Definition of Done
- [x] ✅ Production‑Ready Invite push tap routes into invite flow.
- [x] ✅ Production‑Ready Push tap does not duplicate routes when already on target event.
- [x] ✅ Production‑Ready Invite accept UX handles offline attempt (enqueue + toast + reconcile).
- [x] ✅ Production‑Ready Invite accept remains non-optimistic (events fire after success response).
- [x] ✅ Production‑Ready No extra “processing” state introduced for invite flows.
- [x] ✅ Production‑Ready Silent invite list update uses payload data (no list refetch).
- [x] ✅ Production‑Ready `push_handler` emits raw push data merged with fetched payload data (payload replaces `data`).
- [x] ✅ Production‑Ready Push-aware repository hook is reusable and adopted by invites repo.
- [ ] ⚪ Push validation steps completed.
- [x] ✅ Production‑Ready README documents the manual push validation checklist.
- [x] ✅ Production‑Ready push_handler supports presentation gate/mode while keeping plug'n'play default behavior.
- [x] ✅ Production‑Ready Push display defers until init stack is set (push above InviteFlow/Home).
- [x] ✅ Production‑Ready Debug logs confirm push presentation waits for init stack.
- [x] ✅ Production‑Ready Push presentation de-duplicates per push_message_id and clears queue.
- [x] ✅ Production‑Ready Background queue keeps only the latest push payload.
- [x] ✅ Production‑Ready Queue is cleared when app opens via push tap.

## Validation Steps
- [x] ✅ Production‑Ready Smoke test: receive a push notification and confirm tap handling resolves the correct in-app surface.
- [x] ✅ Production‑Ready Verify logs show Firebase init, token acquisition, and `/api/v1/push/register` success.
- [x] ✅ Production‑Ready App registers push device with anonymous token when user is not logged in.
- [x] ✅ Production‑Ready Push registration payload uses backend-supported platform values.
- [x] ✅ Production‑Ready Silent invite update: a new invite payload updates the invite list without hitting the backend.
- [x] ✅ Production‑Ready Cold start push: push screen does not flash before Home/InviteFlow.
- [x] ✅ Production‑Ready Logs show push presentation gated until init stack is ready.
- [x] ✅ Production‑Ready Cold start does not re-present previously queued push.
- [x] ✅ Production‑Ready Multiple pushes deliver only the latest presentation.

## Decisions
- Keep invite acceptance non-optimistic; emit events only after success.
- Use enqueue + toast for offline invite acceptance.
- Invite push routing uses `invite={{invite_id}}` query param; open invite flow and surface the invite at top of the stack.
- If invite not found or expired, ignore and show the stack normally.
- Do not show toast on push receipt; rely on push handler settings for UI.
- Use the existing in-app retry queue for network instability (no new queue system).
- Push stream emits the raw push payload merged with fetched payload data (replace `data` with fetched payload).
- Payload-driven invite updates accept `invites` (array) or `invite` (single) using `InviteDto` field names; new/updated invites are upserted by `id` and placed at the top of the list.
