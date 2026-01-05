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

## Out of Scope
- Push routing and invite flow UX (tracked in push TODO).
- Backend telemetry ingestion changes.

## Definition of Done
- [ ] ⚪ Anonymous tracking identifies/merges on login.
- [ ] ⚪ Required properties populated where applicable (`event_id`, `inviter_kind`, `inviter_id`, `partner_id`, `source`).
- [ ] ⚪ `event_tracker_handler` supports idempotency (`$insert_id`) and exposes delivery outcomes.
- [ ] ⚪ Telemetry validation steps completed.

## Validation Steps
- [ ] ⚪ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [ ] ⚪ Verify anonymous events are attributed to the authenticated user after login (identify/merge).

## Decisions
- Telemetry remains non-optimistic; events fire only after success responses.
