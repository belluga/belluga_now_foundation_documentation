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

## Out of Scope
- Detailed implementation steps (tracked in the split TODOs).

## Definition of Done
- [x] ✅ Backend telemetry emits Mixpanel + webhook for implemented Laravel endpoints (below).
- [x] ✅ Backend telemetry emitter bypasses event filtering when `track_all=true`.
- [x] ✅ Telemetry settings accept `track_all=true` and both backend + frontend honor it.
- [ ] ⚪ Tenant telemetry settings updated to include backend events (Mixpanel + webhook).

## Validation Steps
- [ ] ⚪ Backend telemetry events appear for implemented endpoints with correct `distinct_id` and required properties.
- [x] ✅ `track_all=true` emits events even when `events` list is empty or missing.
 - [x] ✅ Telemetry settings validation accepts `track_all=true` when `events` is empty or missing.
- [ ] ⚪ Tenant telemetry settings list includes the backend events and emits to Mixpanel + webhook.

## Decisions
- Split into backend and frontend TODOs; keep this file as the index.
- Backend telemetry targets only endpoints that exist today; planned endpoints remain in their owning TODOs.
- `track_all=true` bypasses explicit event lists for Mixpanel + webhook trackers.

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
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/system_roadmap.md`

---

The detailed scope, requirements, and decisions now live in the backend and frontend TODOs.
