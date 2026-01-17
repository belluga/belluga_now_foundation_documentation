# TODO (V1): Telemetry (Mixpanel) + Push Notifications (Index)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team (source of truth) + Delphi (Flutter)  
**Objective:** Track the split V1 telemetry + push work across backend and frontend TODOs.

---

## Scope
- Split the initiative into backend + frontend TODOs and keep this file as the index.
- Define backend telemetry for already-implemented Laravel endpoints (Mixpanel + webhook).
- Support `track_all=true` in telemetry settings (bypass explicit event lists).
- Update backend telemetry filtering + settings validation to honor `track_all` (events list optional).
- Update tenant telemetry settings with the backend event list (deferred).
- Track the backend context + identity consistency fix needed to complete telemetry validation.

## Out of Scope
- Detailed implementation steps (tracked in the split TODOs).

## Definition of Done
- [x] ✅ Backend telemetry emits Mixpanel + webhook for implemented Laravel endpoints (below).
- [x] ✅ Backend telemetry emitter bypasses event filtering when `track_all=true`.
- [x] ✅ Telemetry settings accept `track_all=true` and both backend + frontend honor it.
- [x] ✅ Tenant telemetry settings updated to include backend events (Mixpanel + webhook). (2026-01-13)
- [x] ✅ Production backend defaults to Laravel auth; mocks only via DI overrides to prevent fake telemetry identities. (2026-01-14)
- [ ] ⚪ Environment payload exposes telemetry config as an object (`telemetry.trackers` + `telemetry.location_freshness_minutes`).

## Validation Steps
- [ ] ⚪ Backend telemetry events appear for implemented endpoints with correct `distinct_id` and required properties.
- [x] ✅ `track_all=true` emits events even when `events` list is empty or missing.
 - [x] ✅ Telemetry settings validation accepts `track_all=true` when `events` is empty or missing.
- [ ] ⚪ Tenant telemetry settings list includes the backend events and emits to Mixpanel + webhook.

## Decisions
- Split into backend and frontend TODOs; keep this file as the index.
- Backend telemetry targets only endpoints that exist today; planned endpoints remain in their owning TODOs.
- `track_all=true` bypasses explicit event lists for Mixpanel + webhook trackers.
- Environment payload now shapes telemetry as `{trackers: [...], location_freshness_minutes: 5}` for client consumption.
- Mixpanel validation exposed mock/production ID drift; we are resolving it in `TODO-v1-backend-context-and-identity.md` to unblock telemetry and push verification.

## Questions to Close
- Remaining decisions are tracked in the backend and frontend TODOs.

---

## Backend Telemetry (Implemented Endpoints)
**Owner:** Backend Team  
**Delivery rule:** emit after successful state change, not on read-only endpoints.

### Shared requirements
- `distinct_id` = `user_id` (actor).
- Mixpanel `$insert_id` = idempotency key.
- Webhook uses unified envelope (`type/context/payload`) with `tenant.id` + `user.id`.
- Required base properties: `tenant_id`, `user_id`, `source=api`.

### Tenant API (auth/profile/tenant management)
- `anonymous_identity_created` → `POST /api/v1/anonymous/identities`
  - properties: `user_id`, `identity_state`
- `auth_login_succeeded` → `POST /api/v1/auth/login`
  - properties: `user_id`, `device_name`, `auth_scope=tenant`, `account_id?`
- `auth_logout` → `POST /api/v1/auth/logout`
  - properties: `user_id`, `device_name?`, `all_devices`, `auth_scope=tenant`
- `auth_password_registered` → `POST /api/v1/auth/register/password`
  - properties: `user_id`
- `auth_password_token_generated` → `POST /api/v1/auth/password_token`
  - properties: `user_id`
- `auth_password_reset` → `POST /api/v1/auth/password_reset`
  - properties: `user_id`
- `profile_updated` → `PATCH /api/v1/profile`
  - properties: `user_id`, `changed_fields[]`
- `profile_password_updated` → `PATCH /api/v1/profile/password`
  - properties: `user_id`
- `profile_email_added` / `profile_email_removed` → `PATCH/DELETE /api/v1/profile/emails`
  - properties: `user_id`, `emails_count`
- `profile_phone_added` / `profile_phone_removed` → `PATCH/DELETE /api/v1/profile/phones`
  - properties: `user_id`, `phones_count`
- `domain_created` / `domain_deleted` / `domain_restored` / `domain_force_deleted` → `/api/v1/domains/*`
  - properties: `domain_id`
- `app_domain_added` / `app_domain_removed` → `POST/DELETE /api/v1/appdomains`
  - properties: `domain`
- `tenant_role_created` / `tenant_role_updated` / `tenant_role_deleted` / `tenant_role_restored` / `tenant_role_force_deleted` → `/api/v1/roles/*`
  - properties: `role_id`
- `tenant_user_managed` → `POST/DELETE /api/v1/tenant-users`
  - properties: `target_user_id`, `action=create|update|delete`
- `tenant_user_deleted` / `tenant_user_restored` / `tenant_user_force_deleted` → `/api/v1/users/*`
  - properties: `target_user_id`
- `account_created` / `account_updated` / `account_deleted` / `account_restored` / `account_force_deleted` → `/api/v1/accounts/*`
  - properties: `account_id`
- `account_user_role_attached` / `account_user_role_removed` → `/api/v1/accounts/{account_slug}/users/{user_id}/roles/{role_id}`
  - properties: `account_id`, `target_user_id`, `role_id`
- `tenant_branding_updated` → `POST /api/v1/branding/update`
  - properties: `tenant_id`

### Account API (account-scoped users/roles/credentials)
- `account_user_created` / `account_user_updated` / `account_user_deleted` → `/api/v1/accounts/{account_slug}/users/*`
  - properties: `account_id`, `target_user_id`
- `account_user_credential_added` / `account_user_credential_removed` → `/api/v1/accounts/{account_slug}/users/{user_id}/credentials/*`
  - properties: `account_id`, `target_user_id`, `credential_id`
- `account_role_created` / `account_role_updated` / `account_role_deleted` / `account_role_restored` / `account_role_force_deleted` → `/api/v1/accounts/{account_slug}/roles/*`
  - properties: `account_id`, `role_id`

### Admin (Landlord) API
- Deferred until landlord telemetry settings exist; add to landlord TODO once settings are defined.

## References
- `foundation_documentation/todos/completed/TODO-v1-push-delivery-consolidated.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-backend-context-and-identity.md`
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
- [x] ✅ Production‑Ready — Implement `POST /api/v1/push/register` in upstream:
  - [x] ✅ Production‑Ready — accept `{ device_id, platform, push_token }`
  - [x] ✅ Production‑Ready — associate token with authenticated user + tenant
- [x] ✅ Production‑Ready — Optional `DELETE /api/v1/push/unregister` in upstream
- [x] ✅ Production‑Ready — Handle token rotation idempotently

### B2) Notification policies (tenant settings)
- [x] ✅ Production‑Ready — Return which notification categories are enabled and any throttles (tenant settings)
- [x] ✅ Production‑Ready — Keep backend authoritative; Flutter should not implement quota rules beyond UX

### B3) Notification payload contract (deep linking)
Payload must include enough data to route:
- `tenant_id`
- `type`: `invite_received | event_reminder | invite_status_changed | ...`
- `event_id` (if applicable)
- `invite_id` or `invite_code` (if applicable)
- optional `inviter_principal` summary for display

---

## C) Flutter Requirements

### C1) Push bootstrap
- [x] ✅ Production‑Ready — Register token on startup/login, and re-register on rotation
- [x] ✅ Production‑Ready — Route notification taps into:
  - [x] ✅ Production‑Ready — invite flow (received invites)
  - [x] ✅ Production‑Ready — event detail (event reminders)

### C2) UX
- [x] ✅ Production‑Ready — If user is already on the target event, skip duplicate navigation (treat in-place state as sufficient).

---

## D) Mixpanel Requirements

### D1) Initialization
- [x] ✅ Production‑Ready — Prefer backend-provided configuration (tenant-aware token/keys)
- [x] ✅ Production‑Ready — Plan anonymous-to-authenticated identity stitching (even if deferred)

### D2) Event taxonomy (minimum)
- [x] ✅ Production‑Ready — Invite telemetry events delegated to `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`.
- [x] ✅ Production‑Ready — Track events funnel:
  - [x] ✅ Production‑Ready — `event_opened`
  - [x] ✅ Production‑Ready — `event_confirmed_presence` delegated to event implementation TODO.
- [x] ✅ Production‑Ready — Track discovery/navigation:
  - [x] ✅ Production‑Ready — `map_opened` covered by screen_view on map route.
  - [x] ✅ Production‑Ready — `poi_opened`
  - [x] ✅ Production‑Ready — `favorite_artist_toggled`

### D2.1) Trigger moments (must define)
- [x] ✅ Production‑Ready — Define when each Mixpanel event fires (screen load, CTA tap, success response).
- [x] ✅ Production‑Ready — Confirm event detail open trigger (screen first paint vs data loaded).
- [x] ✅ Production‑Ready — Invite trigger moments delegated to `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`.

### D3) Required properties (attach when available)
- [x] ✅ Production‑Ready — Include required properties (when available):
  - `tenant_id` (always)
  - `user_id` (when authenticated)
  - `event_id` (when applicable)
  - `partner_profile_id` (when applicable)
  - `source` (screen/route name)
- [x] ✅ Production‑Ready — Invite-specific properties delegated to `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`.

---

## E) Acceptance Criteria

- [x] ✅ Production‑Ready — Invite telemetry + push acceptance criteria delegated to `foundation_documentation/todos/active/mvp_slices/TODO-v1-invites-implementation.md`.

---

## F) Documentation Sync (Foundation Docs)
See `foundation_documentation/todos/active/TODO-telemetry-push-pr-readiness-decisions.md` for the derived documentation tasks.
