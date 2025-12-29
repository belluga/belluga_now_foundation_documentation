# TODO (V1): Telemetry (Mixpanel) + Push Notifications

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team (source of truth) + Delphi (Flutter)  
**Objective:** Ship V1 with measurable funnels and reliable invite/event notifications.

---

## Scope
- Flutter-only implementation for V1 telemetry + push handling using `push_handler` and `event_tracker_handler`.
- Update this TODO with clarified backend expectations; Laravel implementation is deferred to the backend team.
- Implement a deep link handler for push payload routing.

## Out of Scope
- Implementing Laravel endpoints or server-side fan-out logic.
- Security hardening decisions for analytics configuration (deferred).

## Definition of Done
- [ ] ⚪ Push token registration flow implemented in Flutter using `push_handler`.
- [ ] ⚪ Deep link handler implemented to route push payloads to invite/event surfaces.
- [ ] ⚪ Mixpanel (or telemetry) events are emitted via `event_tracker_handler` with tenant/user metadata when available.
- [ ] ⚪ Event triggers are non-optimistic (fire only after confirmed success responses).
- [ ] ⚪ Anonymous tracking enabled; identify + merge occurs on login.
- [ ] ⚪ Telemetry offline policy defined and implemented (short retry window via reusable in-app job queue).
- [ ] ⚪ `event_tracker_handler` updated to support idempotency and queue-aware delivery outcomes.
- [ ] ⚪ Reusable in-app job queue adopted for invite send/accept flows during transient network instability.

## Validation Steps
- [ ] ⚪ Smoke test: receive a push notification and confirm tap handling resolves the correct in-app surface.
- [ ] ⚪ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [ ] ⚪ Verify anonymous events are attributed to the authenticated user after login (identify/merge).

## Decisions
- Flutter implementation now; Laravel implementation deferred (doc updates OK).
- Add a deep link handler for push payload routing.
- Telemetry events are non-optimistic; fire only on confirmed success.
- Anonymous tracking enabled; identify/merge on login.
- Security posture for telemetry config deferred for a deeper review after initial setup.
- Offline policy: short retry window (max 10s), memory-only, show toast notice, track offline occurrences.
- Retry backoff: 0s → 2s → 4s → 4s (cap at 10s total).
- Update `event_tracker_handler` to expose idempotency keys and a delivery outcome (success/failure) for queue integrations.
- Queue choice: `smart_queue` with `MemoryStore` for reusable in-app jobs (invite actions + tracking).
- Mixpanel token is public and must be delivered via environment payload (client-visible).
- Firebase config is tenant-aware and public-only (no server keys).
- Invite flows are online-first; offline handling is for transient instability (no long-lived offline state).

## Questions to Close
- (Closed) Retry backoff steps set.

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/system_roadmap.md`

---

## A) What Push Is For (V1)

Minimum notifications:
- Invite received (high priority)
- Event reminder for confirmed attendance (if scheduling is available)

Required behaviors:
- Deep link into the app to the correct surface (invite context or event detail).
- Respect tenant-level notification policies provided by the backend.

## Push Policy Summary (MVP)
- No channel filtering in V1; users receive all push types by default.
- Backend uses server-side fan‑out for event reminders and favorites audiences.

---

## B) Backend Requirements

### B1) Device registration (Upstream/Boilerplate)
- [ ] ⚪ Implement `POST /api/v1/push/register` in upstream:
  - [ ] ⚪ accept `{ device_id, platform, push_token }`
  - [ ] ⚪ associate token with authenticated user + tenant
  - [ ] ⚪ support anonymous + authenticated states for the same user object
- [ ] ⚪ Optional `DELETE /api/v1/push/unregister` in upstream
- [ ] ⚪ Handle token rotation idempotently

### B2) Notification policies (tenant settings)
- [ ] ⚪ Return which notification categories are enabled and any throttles (tenant settings)
- [ ] ⚪ Keep backend authoritative; Flutter should not implement quota rules beyond UX

### B2.1) Tenant admin management (config storage)
- [ ] ⚪ Provide a Tenant Admin area (not Landlord Admin) where landlord users with tenant access can manage:
  - [ ] ⚪ Mixpanel project token per tenant
  - [ ] ⚪ Firebase project options per tenant (public config only)
- [ ] ⚪ Persist configs per tenant and expose them through a single environment/bootstrap payload (no parallel config calls)

### B3) Notification payload contract (deep linking)
Payload must include enough data to route:
- `tenant_id`
- `type`: `invite_received | event_reminder | invite_status_changed | ...`
- `event_id` (if applicable)
- `invite_id` or `invite_code` (if applicable)
- optional `inviter_principal` summary for display

### B4) Environment payload (single call; public-safe)
This config must be merged into the existing `/api/v1/environment` response (no parallel calls).
Flutter must extend AppData parsing to include these sections.

```json
{
  "tenant_id": "tenant_123",
  "telemetry": {
    "mixpanel_token": "public_token_here",
    "enabled_events": [
      "invite_received",
      "invite_opened",
      "invite_accept_selected_inviter",
      "invite_accepted",
      "invite_declined",
      "event_opened",
      "event_confirmed_presence",
      "map_opened",
      "poi_opened",
      "favorite_artist_toggled"
    ]
  },
  "firebase": {
    "apiKey": "PUBLIC_API_KEY",
    "appId": "1:1234567890:android:abcdef123456",
    "projectId": "tenant-project-id",
    "messagingSenderId": "1234567890",
    "storageBucket": "tenant-project-id.appspot.com"
  },
  "push": {
    "enabled": true,
    "types": [
      "invite_received",
      "event_reminder"
    ],
    "throttles": {
      "event_reminder_max_per_day": 3
    }
  }
}
```

### B5) Default environment values (Flutter dev only)
- [ ] ⚪ Flutter team must provide default values for local dev while backend is offline.
- [ ] ⚪ Implementation must include a code TODO warning to remove defaults before production release.

---

## C) Flutter Requirements

### C1) Push bootstrap
- [ ] ⚪ Register token on startup/login, and re-register on rotation
- [ ] ⚪ Re-register on login to bind anonymous → authenticated state using existing backend token flow
- [ ] ⚪ Route notification taps into:
  - [ ] ⚪ invite flow (received invites)
  - [ ] ⚪ event detail (event reminders)

### C2) UX
- [ ] ⚪ If user is already on the target event, update in-place state rather than pushing duplicate routes
- [ ] ⚪ Use non-optimistic triggers for invite acceptance (emit after success response)
- [ ] ⚪ When offline accept is attempted, apply local accept state, enqueue sync job, show toast notice, and reconcile on response
- [ ] ⚪ Do not introduce a separate “processing” state for invites; rely on toast + short retry window

---

## D) Mixpanel Requirements

### D1) Initialization
- [ ] ⚪ Prefer backend-provided configuration (tenant-aware token/keys)
- [ ] ⚪ Plan anonymous-to-authenticated identity stitching (even if deferred)
- [ ] ⚪ Use anonymous tracking and merge on login (identify + alias/merge as supported)
- [ ] ⚪ Queue telemetry jobs and retry within the short window (do not buffer Mixpanel HTTP requests directly)

### D2) Event taxonomy (minimum)
- [ ] ⚪ Track invites funnel:
  - [ ] ⚪ `invite_received`
  - [ ] ⚪ `invite_opened`
  - [ ] ⚪ `invite_accept_selected_inviter`
  - [ ] ⚪ `invite_accepted`
  - [ ] ⚪ `invite_declined`
- [ ] ⚪ Track events funnel:
  - [ ] ⚪ `event_opened`
  - [ ] ⚪ `event_confirmed_presence`
- [ ] ⚪ Track discovery/navigation:
  - [ ] ⚪ `map_opened`
  - [ ] ⚪ `poi_opened`
  - [ ] ⚪ `favorite_artist_toggled`

### D2.1) Trigger moments (must define)
- [ ] ⚪ Define when each Mixpanel event fires (screen load, CTA tap, success response).
- [ ] ⚪ Confirm source of truth for invite acceptance events (client vs backend).
- [ ] ⚪ Confirm event detail open trigger (screen first paint vs data loaded).

### D3) Required properties (attach when available)
- [ ] ⚪ Include required properties (when available):
  - `tenant_id` (always)
  - `user_id` (when authenticated)
  - `event_id` (when applicable)
  - `inviter_kind` + `inviter_id` (when applicable)
  - `partner_id` (when applicable)
  - `source` (screen/route name)

### D4) `event_tracker_handler` updates (required for queue)
- [ ] ⚪ Add idempotency support for Mixpanel (e.g., `$insert_id`) and expose the event id in payloads.
- [ ] ⚪ Return a delivery outcome (success/failure) from `logEvent` so the queue can retry appropriately.

---

## E) Acceptance Criteria

- [ ] ⚪ Invite received push arrives and routes correctly into the app
- [ ] ⚪ Mixpanel shows end-to-end invite funnel with consistent identifiers
