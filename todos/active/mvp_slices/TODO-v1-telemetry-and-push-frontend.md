# TODO (V1): Telemetry + Push Notifications (Frontend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter)  
**Objective:** Ship V1 telemetry and push handling with deep links and reliable retry behavior.

---

## Scope
- Flutter implementation for V1 telemetry + push handling using `push_handler` and `event_tracker_handler`.
- Deep link handler for invite/event routing based on fetched push data.
- Telemetry event emission (non-optimistic) with tenant/user metadata.
- Offline retry policy and in-app queue integration for telemetry + invite flows.

## Out of Scope
- Laravel endpoints or server-side fan-out logic.
- Security hardening for analytics configuration (deferred).

## Definition of Done
- [ ] ⚪ Push token registration flow implemented in Flutter using `push_handler`.
- [ ] ⚪ Deep link handler implemented to route push payloads to invite/event surfaces.
- [ ] ⚪ Mixpanel events emitted via `event_tracker_handler` with tenant/user metadata when available.
- [ ] ⚪ Event triggers are non-optimistic (fire only after confirmed success responses).
- [ ] ⚪ Anonymous tracking enabled; identify + merge occurs on login.
- [ ] ⚪ Telemetry offline policy implemented (short retry window via reusable in-app job queue).
- [ ] ⚪ `event_tracker_handler` updated to support idempotency and queue-aware delivery outcomes.
- [ ] ⚪ Reusable in-app job queue adopted for invite send/accept flows during transient network instability.
- [ ] ⚪ Push data fetch uses `/api/v1/push/messages/{push_message_id}/data` with bearer token.
- [ ] ⚪ Client handles `ok=false` from push data fetch (no action taken).
- [ ] ⚪ Client reports push actions via `POST /api/v1/push/messages/{push_message_id}/actions`.
- [ ] ⚪ Client reports `delivered` on push receipt using FCM `onMessage`/`onBackgroundMessage`.

## Validation Steps
- [ ] ⚪ Smoke test: receive a push notification and confirm tap handling resolves the correct in-app surface.
- [ ] ⚪ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [ ] ⚪ Verify anonymous events are attributed to the authenticated user after login (identify/merge).

## Decisions
- Deep link handler routes to invite and event surfaces based on fetched push payload.
- Telemetry events are non-optimistic; fire only after confirmed success.
- Anonymous tracking enabled; identify/merge on login.
- Offline policy: short retry window (max 10s), memory-only, show toast notice, track offline occurrences.
- Retry backoff: 0s → 2s → 4s → 4s (cap at 10s total).
- Queue choice: `smart_queue` with `MemoryStore` for reusable in-app jobs (invite actions + tracking).
- Push data fetch auth transport: `Authorization: Bearer <token>` header (never query params).
- Push data fetch response: structured JSON payload (no stringification required).
- Client behavior when fetch returns `ok=false`: treat as no-op.
- Push actions reporting includes `action`, `step_index`, optional `button_key`, and optional `device_id`.
- `delivered` is emitted on receipt via FCM callbacks (foreground/background).
- Mixpanel should observe all relevant user behaviors beyond the minimum funnel list.
- Event detail open trigger: fire when data is loaded (not first paint).
- Invite acceptance source of truth: backend database; client emits events only after confirmed success response.
- Telemetry events are non-optimistic; fire only after confirmed success responses.
- Record `latency_ms` on telemetry events by measuring API call duration and attaching it to the success/failure event.
- Trigger matrix (non-optimistic):
  - `invite_received`: fire when invite list sync includes new invite (after API response).
  - `invite_opened`: fire when invite detail data loads successfully.
  - `invite_accept_selected_inviter`: fire when inviter selection is confirmed and API response succeeds.
  - `invite_accepted`: fire after backend confirms acceptance (success response).
  - `invite_declined`: fire after backend confirms decline (success response).
  - `event_opened`: fire when event detail data loads successfully.
  - `event_confirmed_presence`: fire after backend confirms presence (success response).
  - `map_opened`: fire after map data loads successfully.
  - `poi_opened`: fire after POI detail data loads successfully.
  - `favorite_artist_toggled`: fire after backend confirms toggle (success response).

## Questions to Close
- Define when each Mixpanel event fires (screen load, CTA tap, success response).

## References
- `foundation_documentation/todos/active/TODO-v1-telemetry-and-push-backend.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/system_roadmap.md`

---

## Flutter Requirements

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

## Telemetry Requirements (Mixpanel)

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
