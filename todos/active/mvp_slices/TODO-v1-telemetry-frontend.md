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
- Support `track_all=true` in telemetry settings (bypass explicit event lists).
- Frontend emits only UI behavior events (no state-changing invite/presence events).
- Add Mixpanel alias/identify merge on login (anonymous → authenticated).
- Wire auth transition listener to trigger Mixpanel merge once per device/user.
- Persist Mixpanel merge completion (avoid repeated alias on subsequent launches).
- Guard Mixpanel alias when distinct ID already equals the authenticated user ID.
- Track screen_view for all routes (including overlays); emit once per transition.
- Include screen_context (route name/path/params) on in-screen events.
- Align curated screen events (e.g., map_opened) with route-level tracking to avoid duplication.
- Add map telemetry for filters/search/region navigation/directions interactions.
- Ensure the map entrypoint emits map telemetry (filters/search/poi/directions/ride-share).
- Add automated tests for map telemetry logging (MapScreenController).
- Track app init and app background events (frontend only).
- Include location context on all events (lat/lng + accuracy + timestamp) when permission is granted and location is fresh.
- Standardize custom webhook telemetry envelope and include identity_merge payloads.
- Track section visibility events (view_content) when section headers become visible in scrollable screens.

## Out of Scope
- Push routing and invite flow UX (tracked in push TODO).
- Backend telemetry ingestion changes beyond invite/presence event emission.
- Backend invite/presence telemetry emission (tracked in `TODO-v1-invites-implementation.md`).

## Definition of Done
- [x] ✅ Anonymous tracking identifies/merges on login.
- [x] ✅ Required properties populated where applicable (`event_id`, `inviter_kind`, `inviter_id`, `partner_id`, `source`).
- [x] ✅ `event_tracker_handler` supports idempotency (`$insert_id`) and exposes delivery outcomes.
- [x] ✅ `track_all=true` bypasses the `events` list for Mixpanel + webhook trackers.
- [x] ✅ Frontend no longer logs `invite_received` when opening invite flow.
- [x] ✅ Frontend no longer logs state-changing invite/presence events.
- [x] ✅ Mixpanel alias+identify runs once when user transitions from anonymous to authenticated.
- [x] ✅ Mixpanel alias skip when distinct ID already equals authenticated user ID.
- [ ] ⚪ Route-level screen_view tracking emits for all routes (including overlays) once per transition.
- [ ] ⚪ In-screen events include screen_context (route name/path/params).
- [x] ✅ Curated screen events are deduplicated or removed where route tracking covers the same screen.
- [x] ✅ Map telemetry logs filter, search, region, and directions interactions in the map flow.
- [x] ✅ Map telemetry tests cover search/filter/poi/directions/ride-share logging.
- [x] ✅ Custom webhook payload uses the unified telemetry envelope (type/context/payload).
- [x] ✅ Custom webhook emits identity_merge payload when alias is triggered.
- [x] ✅ App init + app background events emitted with tenant/user properties.
- [ ] ⚪ Location context included on all events when permission granted (lat/lng/accuracy/timestamp).
- [ ] ⚪ Section view events emitted on header visibility with section_title + position_index + screen_context.
- [ ] ⚪ Telemetry validation steps completed.

## Validation Steps
- [x] ✅ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [x] ✅ Verify anonymous events are attributed to the authenticated user after login (identify/merge).
- [x] ✅ Verify frontend UI telemetry does not emit state-changing invite/presence events (no duplication).
- [x] ✅ Verify Mixpanel distinct ID is stitched (alias) and future events use authenticated ID.
- [x] ✅ Verify Mixpanel alias runs once per user/device (no repeated alias on subsequent launches).
- [x] ✅ Verify Mixpanel alias is skipped when distinct ID already equals user ID.
- [ ] ⚪ Verify screen_view fires for all routes (including overlays) once per transition.
- [x] ✅ Verify map telemetry captures filters/search/region/directions interactions with non-empty properties.
- [x] ✅ Verify webhook payload uses the unified envelope and includes identity_merge when users merge.
- [x] ✅ Verify `track_all=true` emits events that are not listed in `events`.
- [ ] ⚪ Verify app init and app background events appear once per lifecycle change.
- [ ] ⚪ Verify location context appears on events when permission is granted (lat/lng/accuracy/timestamp).
- [ ] ⚪ Verify section view events fire when section headers become visible.

## Decisions
- Telemetry remains non-optimistic; events fire only after success responses.
- `invite_received` is emitted by the backend delivery pipeline, not by the UI stack.
- State-changing invite/presence events are emitted by the backend; frontend tracks UI behavior only.
- Mixpanel merge uses `alias(newUserId, anonymousUserId)` once, then `identify(newUserId)`.
- Distinct IDs for backend-emitted events use `user_id` (anonymous users still have a user id).

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-and-push.md`
