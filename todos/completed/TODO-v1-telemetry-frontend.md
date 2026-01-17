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
- Ensure initial screen_view tracking starts on web (Navigator 2.0) even without a route transition.
- Track timed durations for event_opened, invite_opened, poi_opened, and section_viewed.
- Include screen_context (route name/path/params) on in-screen events.
- Align curated screen events (e.g., map_opened) with route-level tracking to avoid duplication.
- Add map telemetry for filters/search/region navigation/directions interactions.
- Ensure the map entrypoint emits map telemetry (filters/search/poi/directions/ride-share).
- Add automated tests for map telemetry logging (MapScreenController).
- Track app init events (frontend only).
- Track app lifecycle events for all states (frontend only), coalescing rapid transitions.
- Filter app_lifecycle on web (mobile-only signal).
- Trigger invite_opened when the top invite changes (centralized stream listener).
- Track Mixpanel timed events for screen_view durations (time_event + track).
- Flush active timed events on non-active lifecycle states to avoid losing durations.
- Move timed-event lifecycle flushing to the telemetry plugin with platform-specific observers (web + mobile) via conditional exports.
- Include location context on all events (lat/lng + accuracy + timestamp) when permission is granted and location is fresh.
- Standardize custom webhook telemetry envelope and include identity_merge payloads.
- Enrich custom webhook device context on web with browser/OS/userAgent/viewport metadata.
- Track section visibility events (view_content) when section headers become visible in scrollable screens.
- Add tenant environment config under `telemetry.location_freshness_minutes` to control location freshness (default 5 minutes).
- Track backend context + identity consistency work in a separate TODO to unblock telemetry validation.
- Add production telemetry logs on web to trace screen_view/timed event start/finish and confirm route observer firing.
- Prevent telemetry timed event finish/flush from blocking UI interactions (web + mobile); log queue failures with error detail.
- On web, disable NavigatorObserver screen_view timing to avoid duplicate timers; rely on AppRouter listener only.
- Harden timed event insert-id generation for web (fallback when Random.secure is unavailable) and log start failures to avoid silent drops.
- Log a deployment/version marker on app init to confirm the running build in production.

## Out of Scope
- Push routing and invite flow UX (tracked in push TODO).
- Backend telemetry ingestion changes beyond invite/presence event emission.
- Backend invite/presence telemetry emission (tracked in `TODO-v1-invites-implementation.md`).

## Definition of Done
- [x] ✅ Anonymous tracking identifies/merges on login.
- [x] ✅ Required properties populated where applicable (`event_id`, `inviter_kind`, `inviter_id`, `partner_profile_id`, `source`).
- [x] ✅ `event_tracker_handler` supports idempotency (`$insert_id`) and exposes delivery outcomes.
- [x] ✅ `track_all=true` bypasses the `events` list for Mixpanel + webhook trackers.
- [x] ✅ Frontend no longer logs `invite_received` when opening invite flow.
- [x] ✅ Frontend no longer logs state-changing invite/presence events.
- [x] ✅ Mixpanel alias+identify runs once when user transitions from anonymous to authenticated.
- [x] ✅ Mixpanel alias skip when distinct ID already equals authenticated user ID.
- [x] ✅ Route-level screen_view tracking emits for all routes (including overlays) once per transition.
- [x] ✅ Web initial screen_view tracking starts on first render (no navigation required). (2026-01-15)
- [x] ✅ screen_view uses Mixpanel time_event and emits duration.
- [x] ✅ event_opened uses Mixpanel time_event and emits duration.
- [x] ✅ invite_opened uses Mixpanel time_event and emits duration.
- [x] ✅ poi_opened uses Mixpanel time_event and emits duration.
- [x] ✅ section_viewed uses Mixpanel time_event and emits duration.
- [x] ✅ Web build loads Mixpanel JS so mixpanel_flutter can emit events.
- [x] ✅ Timed events flush on non-active lifecycle states.
- [x] ✅ Timed-event lifecycle flushing is implemented in the plugin with web/mobile observers. (2026-01-15)
- [x] ✅ In-screen events include screen_context (route name/path/params).
- [x] ✅ Curated screen events are deduplicated or removed where route tracking covers the same screen.
- [x] ✅ Map telemetry logs filter, search, region, and directions interactions in the map flow.
- [x] ✅ Map telemetry tests cover search/filter/poi/directions/ride-share logging.
- [x] ✅ Custom webhook payload uses the unified telemetry envelope (type/context/payload).
- [x] ✅ Custom webhook emits identity_merge payload when alias is triggered.
- [x] ✅ Web custom webhook payload includes browser/device metadata (user agent, platform, browser name, viewport). (2026-01-15)
- [x] ✅ App init events emitted with tenant/user properties. (2026-01-14)
- [x] ✅ App lifecycle events coalesce rapid transitions and include `state` + `sequence`.
- [x] ✅ app_lifecycle is disabled on web (mobile-only emission).
- [x] ✅ invite_opened fires on every top invite change (stacked invites).
- [x] ✅ App background/foreground events removed in favor of app_lifecycle. (2026-01-14)
- [x] ✅ Location context included on all events when permission granted (lat/lng/accuracy/timestamp).
- [x] ✅ Section view events emitted on header visibility with section_title + position_index + screen_context.
- [x] ✅ Telemetry uses tenant-configured location freshness threshold (default 5 minutes, under `telemetry.location_freshness_minutes`). (2026-01-13)
- [x] ✅ Production web telemetry logs emit for screen_view timed start/finish and route observer transitions.
- [x] ✅ Telemetry finish/flush is fire-and-forget (non-blocking) on all platforms and logs queue failures.
- [x] ✅ Web uses AppRouter-only screen_view timing (no NavigatorObserver duplication).
- [x] ✅ Timed event start succeeds on web (insert-id fallback) and logs failures if they occur.
- [x] ✅ App init logs a version marker in production builds for deployment verification.
- [x] ✅ Telemetry validation steps completed.

## Validation Steps
- [x] ✅ Verify Mixpanel events appear for invite and event funnels (with `tenant_id` and `user_id` when authenticated).
- [x] ✅ Verify anonymous events are attributed to the authenticated user after login (identify/merge).
- [x] ✅ Verify frontend UI telemetry does not emit state-changing invite/presence events (no duplication).
- [x] ✅ Verify Mixpanel distinct ID is stitched (alias) and future events use authenticated ID.
- [x] ✅ Verify Mixpanel alias runs once per user/device (no repeated alias on subsequent launches).
- [x] ✅ Verify Mixpanel alias is skipped when distinct ID already equals user ID.
- [x] ✅ Verify screen_view fires for all routes (including overlays) once per transition.
- [x] ✅ Verify web initial screen_view starts on first render and emits duration on lifecycle flush.
- [x] ✅ Verify screen_view duration appears in Mixpanel via time_event.
- [x] ✅ Verify event_opened duration appears in Mixpanel via time_event.
- [x] ✅ Verify invite_opened duration appears in Mixpanel via time_event.
- [x] ✅ Verify poi_opened duration appears in Mixpanel via time_event.
- [x] ✅ Verify section_viewed duration appears in Mixpanel via time_event.
- [x] ✅ Verify timed events emit when app becomes inactive/paused/hidden/detached.
- [x] ✅ Verify web lifecycle observer flushes timed events on tab hide/blur/pagehide.
- [x] ✅ Verify map telemetry captures filters/search/region/directions interactions with non-empty properties.
- [x] ✅ Verify webhook payload uses the unified envelope and includes identity_merge when users merge.
- [x] ✅ Verify web webhook payload includes browser/device metadata (user agent, platform, browser name, viewport).
- [x] ✅ Verify `track_all=true` emits events that are not listed in `events`.
- [x] ✅ Verify app_lifecycle emits one event per transition burst with `state` + `sequence`.
- [x] ✅ Verify web does not emit app_lifecycle events.
- [x] ✅ Verify stacked invites emit invite_opened for each new top card.
- [x] ✅ Verify app_background/app_foreground no longer emit.
- [x] ✅ Verify location context appears on events when permission is granted (lat/lng/accuracy/timestamp).
- [x] ✅ Verify section view events fire when section headers become visible.
- [x] ✅ Verify location freshness uses tenant settings default (5 minutes) when not provided. (2026-01-13)
- [x] ✅ Verify production logs show screen_view timing start/finish for web navigation.
- [x] ✅ Verify UI actions (close modal, reject invite) complete even when telemetry queue fails or retries.
- [x] ✅ Verify web screen_view duration fires once per route change (no duplicates).
- [x] ✅ Verify web timed event start no longer fails silently (see logs or Mixpanel durations).
- [x] ✅ Verify app init prints the deployment/version marker in production console logs.

## Decisions
- Telemetry remains non-optimistic; events fire only after success responses.
- `invite_received` is emitted by the backend delivery pipeline, not by the UI stack.
- State-changing invite/presence events are emitted by the backend; frontend tracks UI behavior only.
- Mixpanel merge uses `alias(newUserId, anonymousUserId)` once, then `identify(newUserId)`.
- Distinct IDs for backend-emitted events use `user_id` (anonymous users still have a user id).
- Location freshness threshold comes from `telemetry.location_freshness_minutes` in the environment payload (default 5 minutes).
- screen_context schema: `route_name`, `route_type`, `is_overlay`, `route_params` (JSON-safe).
- Section visibility events use `eventName: section_viewed` with `section_title`, `position_index`, and `screen_context`.
- Mixpanel validation uncovered mock/production ID drift; we are resolving it in `TODO-v1-backend-context-and-identity.md` before final telemetry signoff.
- Resume does not emit `app_foreground`; app_lifecycle covers lifecycle transitions.
- `app_lifecycle` emits once per transition burst with `state` (final) + `sequence` (ordered states).
- `app_lifecycle` is emitted only on mobile; web is filtered to reduce low-signal noise.
- invite_opened timing is driven by the top invite stream change (not per-method).
- Transition burst window: 400ms.
- screen_view durations use Mixpanel time_event; plugin remains agnostic (app supplies labels).
- Timed events to implement: event_opened, invite_opened, poi_opened, section_viewed.
- Non-active lifecycle states for timing flush: inactive, paused, hidden, detached.
- Lifecycle flushing lives inside the telemetry plugin; the app only registers the observer.
- Custom webhook device context is platform-aware: web uses browser metadata; mobile keeps device model/brand fields.

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-and-push.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-backend-context-and-identity.md`
