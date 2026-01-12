# TODO (V1): Telemetry (Frontend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter)  
**Objective:** Close telemetry identity, payload, and delivery gaps for V1.

---

## Scope
- Anonymous-to-authenticated identity stitching.
- Required telemetry properties consistency.
- `event_tracker_handler` delivery outcomes and idempotency support.
- Telemetry validation steps (Mixpanel verification).
- Backend emits `invite_received` on delivery (not from UI stack).
- Add Mixpanel alias/identify merge on login (anonymous → authenticated).
- Wire auth transition listener to trigger Mixpanel merge once per device/user.
- Persist Mixpanel merge completion (avoid repeated alias on subsequent launches).
- Track top-level route screen views (AutoRoute observer allowlist).
- Align curated screen events (e.g., map_opened) with route-level tracking to avoid duplication.

## Out of Scope
- Push routing and invite flow UX (tracked in push TODO).
- Backend telemetry ingestion changes (excluding `invite_received` delivery emission).

## Definition of Done
- [ ] ⚪ Anonymous tracking identifies/merges on login.
- [ ] ⚪ Required properties populated where applicable (`event_id`, `inviter_kind`, `inviter_id`, `partner_id`, `source`).
- [ ] ⚪ `event_tracker_handler` supports idempotency (`$insert_id`) and exposes delivery outcomes.
- [ ] ⚪ Backend emits `invite_received` once per delivery (not per UI stack render).
- [ ] ⚪ Frontend no longer logs `invite_received` when opening invite flow.
- [ ] ⚪ Mixpanel alias+identify runs once when user transitions from anonymous to authenticated.
- [ ] ⚪ Route-level screen_view tracking emits only for top-level routes (allowlist).
- [ ] ⚪ Curated screen events are deduplicated or removed where route tracking covers the same screen.
- [ ] ⚪ Telemetry validation steps completed.

## Validation Steps
- [ ] ⚪ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [ ] ⚪ Verify anonymous events are attributed to the authenticated user after login (identify/merge).
- [ ] ⚪ Verify `invite_received` fires once per delivered invite (no stack-based duplication).
- [ ] ⚪ Verify Mixpanel distinct ID is stitched (alias) and future events use authenticated ID.
- [ ] ⚪ Verify Mixpanel alias runs once per user/device (no repeated alias on subsequent launches).
- [ ] ⚪ Verify route screen_view fires for top-level routes only (home/map/invites/schedule/profile).

## Decisions
- Telemetry remains non-optimistic; events fire only after success responses.
- `invite_received` is emitted by the backend delivery pipeline, not by the UI stack.
- Mixpanel merge uses `alias(newUserId, anonymousUserId)` once, then `identify(newUserId)`.
