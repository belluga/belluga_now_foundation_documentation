# TODO (V1): Telemetry + Push Notifications (Backend)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (source of truth)  
**Objective:** Deliver backend contracts for telemetry config, push message CRUD, and secure payload delivery.

---

## Scope
- Define and deliver the plug'n'play push package: CRUD, data fetch, actions, metrics, and delivery pipeline.
- Implement package-owned routes with host-controlled path strings.
- Provide tenant push settings and credentials management, including FCM HTTP v1 delivery.
- Enforce delivery lifecycle (accepted/delivered/opened) and metrics aggregation.
- Implement quota-check endpoint and transactional single-recipient send.
- Enforce audience eligibility via host-provided contract (domain-agnostic).pi/v1/settings/push/cred
- Provide single-credential per tenant (upsert), removing dependency on `firebase_credentials_id` in settings.
- Use a single upsert endpoint for credentials (`PUT /api/v1/settings/push/credentials`) and block multiple legacy credentials (409).
- Require `firebase` public config and `push.enabled` in tenant push settings updates.
- Provide a tenant push status endpoint that reports configuration state.
- Default `max_ttl_days` to 7 when omitted.
- Document the push activation flow in the project README (service account, tenant settings, status endpoint).

## Out of Scope
- Flutter UI behavior, deep links, or telemetry client wiring.

## Definition of Done
- [x] ✅ Push message config endpoint defined: `GET /api/v1/push/messages/{push_message_id}`.
- [x] ✅ Push message data endpoint defined: `GET /api/v1/push/messages/{push_message_id}/data`.
- [x] ✅ Push message CRUD endpoints defined (create/update/list/archive).
- [x] ✅ Push message schema documented (config + data payload).
- [x] ✅ Push message access rules enforced (401/404 behavior, anonymous token allowed for data fetch).
- [x] ✅ Push message TTL/validity and `active` flag honored; inactive/expired returns `ok=false`.
- [x] ✅ Push message delete semantics enforced (hard delete only if not delivered).
- [x] ✅ Package migration lives inside `belluga_push_handler` and is loaded via the package service provider.
- [x] ✅ Tenant migration runner includes package migration paths (config-driven) so tenant DBs include push collections.
- [x] ✅ Package registers push routes while host app retains control of the path strings.
- [x] ✅ Package manages tenant push credentials and performs real FCM delivery (plug'n'play).
- [x] ✅ FCM credentials stored in `push_credentials` and linked via tenant settings (encrypted fields).
- [x] ✅ Real FCM HTTP v1 client implemented and wired via `FcmClientContract`.
- [x] ✅ Delivery fan-out jobs use multicast batching and retry/backoff; partial failures logged.
- [x] ✅ `push_delivery_logs` collection implemented and used for error tracking.
- [x] ✅ Quota-check endpoint implemented (policy-backed decision payload).
- [x] ✅ Transactional single-recipient send endpoint implemented with tenant-scoped `user_id`/`email` resolution.
- [x] ✅ Audience eligibility enforced via host contract (domain-agnostic).
- [x] ✅ Package README expanded with full examples, integration guidance, and implementation notes.
- [x] ✅ Environment payload includes telemetry + firebase + push settings (single `/api/v1/environment` call).
- [x] ✅ Delivery lifecycle and metrics defined and stored.
- [x] ✅ Backend tests cover auth, access, TTL/active, CRUD, cross-tenant access.
- [x] ✅ Sync `laravel-app` submodule to the latest upstream `dev` commit that contains this implementation.
- [x] ✅ Production‑Ready Tenant push settings require `firebase` and `push.enabled` (no `firebase_credentials_id`).
- [x] ✅ Production‑Ready Validation errors clearly report missing required Firebase/push settings fields (no `firebase_credentials_id`).
- [x] ✅ Tenant push status endpoint returns `not_configured | pending_tests | active`.
- [x] ✅ Tenant push settings default `max_ttl_days` to 7 when omitted.
- [x] ✅ Production‑Ready Push settings validated end-to-end with real tenant credentials and device.
- [x] ✅ Production‑Ready Push delivery test confirms status flips to `active`.
- [x] ✅ Production‑Ready README includes step-by-step instructions to enable push + Firebase (credentials + settings + status check).
- [x] ✅ Production‑Ready Temporary FCM delivery logging verified (or deemed unnecessary after validation).
- [x] ✅ Production‑Ready Single-credential flow: settings no longer require `firebase_credentials_id`; credentials upsert via `PUT` and only one credential is used per tenant.

## Validation Steps
- [x] ✅ Feature tests for CRUD endpoints (create/list/update/archive/delete).
- [x] ✅ Auth tests: 401 without token, 404 without access.
- [x] ✅ Data fetch returns `ok=false` for inactive/expired message.
- [x] ✅ Cross-tenant access blocked for landlord tokens without tenant access.
- [x] ✅ Device registration/unregister tests (upsert, rotation, removal).
- [x] ✅ Audience enforcement tests for `all`, `users`, `event` qualifiers.
- [x] ✅ Actions endpoint tests (required fields, idempotency, anonymous auth).
- [x] ✅ Metrics aggregation tests (unique counts, per-button, per-step).
- [x] ✅ Job scheduling tests (immediate send vs scheduled).
- [x] ✅ Tenant settings update tests (types + max TTL).
- [x] ✅ Route registration is loaded from package route file while paths are defined by host config.
- [x] ✅ Tenant push credentials lifecycle validated (create/update/read).
- [x] ✅ Real FCM delivery validated via concrete FCM client implementation.
- [x] ✅ FCM batching uses expected limits; partial failures persist to delivery logs.
- [x] ✅ Quota-check endpoint returns expected payload for allowed/blocked cases.
- [x] ✅ Transactional send respects `transactional` type and tenant scoping (user_id/email).
- [x] ✅ Audience eligibility tests cover host-provided contract outcomes (allow/deny).
- [x] ✅ Confirm `laravel-app` submodule commit matches upstream `dev` with push/telemetry changes.
- [x] ✅ Production‑Ready `PATCH /api/v1/settings/push` returns 422 when required Firebase/push fields are missing (no `firebase_credentials_id`).
- [x] ✅ Production‑Ready `PATCH /api/v1/settings/push` accepts payload when required fields are present (no `firebase_credentials_id`).
- [x] ✅ `GET /api/v1/settings/push/status` returns `not_configured` when required settings are missing.
- [x] ✅ `GET /api/v1/settings/push/status` returns `pending_tests` when configuration exists but no push delivery logs exist.
- [x] ✅ `GET /api/v1/settings/push/status` returns `active` when configuration exists and a delivery log with `accepted` exists.
- [x] ✅ `PATCH /api/v1/settings/push` without `max_ttl_days` persists `max_ttl_days = 7`.
- [x] ✅ Production‑Ready Send a test push to a real device and verify:
  - `FCM` token registration succeeds (`/api/v1/push/register`).
  - `/api/v1/settings/push/status` transitions `pending_tests` → `active`.
  - `/api/v1/push/messages/{push_message_id}/actions` records `delivered` + `opened`.
- [x] ✅ Production‑Ready README instructions validated against the live endpoints.
- [x] ✅ Production‑Ready Tests updated for single-credential behavior (PUT upsert + no `firebase_credentials_id` requirement).
- [x] ✅ Production‑Ready `PUT /api/v1/settings/push/credentials` upserts the single credential (200 on update, 201 on first create).
- [x] ✅ Production‑Ready Multiple legacy credentials return 409 and do not attempt delivery until resolved.

## Implementation Tasks (Remaining)
- [x] ✅ Define audience eligibility contract, default allow-all binding, and integration in data/send flows.
- [x] ✅ Ensure host app can override eligibility logic (documentation + binding example).
- [x] ✅ Define `push_credentials` model + migration with encrypted fields (project_id, client_email, private_key).
- [x] ✅ Extend tenant settings schema to store `firebase_credentials_id`.
- [x] ✅ Implement FCM HTTP v1 client (`FcmClientContract`) using tenant credentials.
- [x] ✅ Add delivery jobs: fan-out, batching (multicast), retry/backoff, and delivery log persistence.
- [x] ✅ Add `push_delivery_logs` collection with error details and status per batch/token.
- [x] ✅ Implement quota-check endpoint (policy decision payload).
- [x] ✅ Implement transactional single-recipient send endpoint (`transactional` only, eligibility enforced).
- [x] ✅ Enforce audience eligibility in fetch/send flows via host contract.
- [x] ✅ Add support for optional `fcm_options` and map it into FCM payload (validation + normalization).
- [x] ✅ Enforce `fcm_options` size caps and allowed keys based on FCM documentation; define conservative caps when FCM lacks limits.
- [x] ✅ Implement tenant-scoped push message routes (reuse controllers/services without duplication).
- [x] ✅ Define tenant-specific push abilities (separate from account abilities) and enforce at controller boundary.
- [x] ✅ Implement scope context resolution in controllers; services accept scope and avoid repeated checks.
- [x] ✅ Implement tenant credentials endpoints secured by `tenant-push-credentials:*` abilities.
- [x] ✅ Add tenant push abilities to `config/abilities.php`.
- [x] ✅ Update tenant migration runner to use configurable migration paths (include push package migrations) and document in package README.
- [x] ✅ Register tenant push message routes in the package route file.
- [x] ✅ Define endpoints to manage credentials (create/update/read) and enforce abilities.
- [x] ✅ Update package README with new plug'n'play details (credentials, delivery, quota-check, eligibility).
- [x] ✅ Add/extend tests to cover the new flows.
- [x] ✅ Hardening tests step: execute all “Hardening Gaps + Tests to Add” before final sign-off.
- [x] ✅ Close Gap 1 (not_found): add `/data` not-found test and map to reason `not_found`.
- [x] ✅ Production‑Ready Close Gap 2 (actions beyond clicked): ensure tests for opened/dismissed/step_viewed/delivered + validation cases.
- [x] ✅ Close Gap 3 (permission matrix): 401/403 tests for account + tenant CRUD, and landlord without tenant access.
- [x] ✅ Close Gap 4 (cross-tenant isolation): block CRUD/data/actions/credentials cross-tenant.
- [x] ✅ Close Gap 5 (plan policy enqueue): canSend false blocks schedule/dispatch; quota decision surfaced.
- [x] ✅ Close Gap 6 (FCM options limits/overrides): title/body caps + platform override behavior.
- [x] ✅ Close Gap 7 (external action validation): missing URL + invalid open_mode -> 422.
- [x] ✅ Update `laravel-app` submodule pointer to the upstream `dev` commit containing the backend implementation.
- [x] ✅ Update tenant push settings validation to require `firebase_credentials_id`.
- [x] ✅ Update tenant push settings validation to require `firebase` public config.
- [x] ✅ Update tenant push settings validation to require `push.enabled`.
- [x] ✅ Update/extend tests for tenant settings validation errors.
- [x] ✅ Add push status endpoint in tenant settings routes (`/api/v1/settings/push/status`).
- [x] ✅ Define push status computation rules (config present + delivery log evidence).
- [x] ✅ Add status derivation using delivery logs (accepted = active).
- [x] ✅ Update tests for push status endpoint states.
- [x] ✅ Add defaulting logic for `max_ttl_days` when omitted.
- [x] ✅ Production‑Ready Execute live push settings + test delivery on tenant device.
- [x] ✅ Production‑Ready Update README with push activation flow and payload examples (after test validation).
- [x] ✅ Adjust credential endpoints to upsert single tenant credential; update settings validation to remove `firebase_credentials_id` requirement; update `PushCredentialService` to select the single credential.
- [x] ✅ Remove `firebase_credentials_id` from tenant push settings payloads (request/response) and model casts.
- [x] ✅ Replace credential endpoints with `PUT /api/v1/settings/push/credentials` (no `:id`) and update tests accordingly.
- [x] ✅ Enforce single-credential rule: if multiple legacy credentials exist, return 409 and require cleanup.
- [x] ✅ Production‑Ready Verify FCM HTTP v1 response logs and resolve accepted_count=0 root cause.

## Provisional Notes
- Push activation instructions are deferred until real-device testing confirms behavior.
- If testing reveals missing data or unexpected status transitions, update settings validation and status rules before finalizing README steps.

## Test Plan

**Test Mandate**
- [x] ✅ Prefer transparent failures over false positives; no workaround-only changes to force green tests.

**Hardening Tests (Required)**
- [x] ✅ Production‑Ready Close remaining coverage gaps with explicit tests before final sign-off.

**Credentials & Settings**
- [x] ✅ Production‑Ready Tenant settings store and return `firebase` public config + `push.enabled` (no `firebase_credentials_id`).
- [x] ✅ `push_credentials` create/update/read works and fields are encrypted.
- [x] ✅ Credential endpoints return 201/200 and 422/403 as expected.

**FCM Client + Delivery**
- [x] ✅ FCM HTTP v1 client sends payload with expected structure.
- [x] ✅ Delivery job batches tokens (respect max batch size).
- [x] ✅ Partial failures write `push_delivery_logs` entries and do not block other tokens.
- [x] ✅ Accepted metrics update from FCM response.
- [x] ✅ `fcm_options` are accepted/validated and included in payload.
- [x] ✅ `fcm_options` rejects invalid keys/sizes (422).

**Quota Check**
- [x] ✅ Quota-check endpoint returns allowed payload (limit/used/remaining).
- [x] ✅ Quota-check endpoint returns blocked payload with reason.
- [x] ✅ Quota-check invalid input returns 422.

**Transactional Send**
- [x] ✅ Reject non-transactional message types.
- [x] ✅ Accept `user_id` and `email` targets (tenant-scoped).
- [x] ✅ Enforce eligibility for transactional target.
- [x] ✅ Enforce Sanctum + `push-messages:send`.

**Audience Eligibility**
- [x] ✅ Contract allows eligible audience.
- [x] ✅ Contract denies ineligible audience.
- [x] ✅ Host override binding is honored.

**Tenant Routes**
- [x] ✅ Tenant CRUD endpoints enforce tenant abilities and scope.
- [x] ✅ Tenant `/data` and `/actions` respect eligibility contract.
- [x] ✅ Tenant transactional `/send` enforced and scoped.

**Tenant Credentials**
- [x] ✅ Credential endpoints require `tenant-push-credentials:*`.
- [x] ✅ Tenant credential endpoints are tenant-scoped (no cross-tenant access).
- [x] ✅ Package README reviewed for completeness and accuracy (examples verified).

**Auth & Access**
- [x] ✅ 401 for all push endpoints without token (CRUD, data, actions).
- [x] ✅ 403 for tokens without required abilities.
- [x] ✅ Cross-tenant denial for landlord tokens without tenant access.
- [x] ✅ Anonymous token accepted for `/data` and `/actions` (not CRUD).
- [x] ✅ Landlord ability gates for `push-settings:update` and `push-messages:send`.

**CRUD & Validation**
- [x] ✅ Create message validates required fields, `internal_name` uniqueness per account, and `expires_at` ≤ 30 days.
- [x] ✅ Update message blocked when sent/archived (if enforced).
- [x] ✅ List messages scoped to account with filters (status/type).
- [x] ✅ Delete message hard-delete only if not delivered/sent; otherwise archive/deactivate.
- [x] ✅ Audience validation rules (`users` requires `user_ids`; `event` requires `event_id` + qualifier).

**/data Fetch**
- [x] ✅ `ok=true` returns payload when active and unexpired.
- [x] ✅ `ok=false` for inactive or expired.
- [x] ✅ Not found returns `ok=false`.
- [x] ✅ Audience enforcement for `all`, `users`, `event` qualifiers (via eligibility contract).

**Actions Endpoint**
- [x] ✅ `step_index` required for all actions; `button_key` required for `clicked`.
- [x] ✅ Idempotency key prevents duplicate aggregation.
- [x] ✅ Anonymous token accepted.
- [x] ✅ Aggregates update: per-button counts, per-step counts, unique counts.

**Delivery & Jobs**
- [x] ✅ Immediate delivery enqueues fan-out job on create.
- [x] ✅ Scheduled delivery defers to job at `scheduled_at`.
- [x] ✅ `accepted` updates from mocked FCM response.
- [x] ✅ Delivery logs have no TTL index (retention forever).

**Settings**
- [x] ✅ Tenant settings update includes `push_message_types` and max TTL.
- [x] ✅ Settings scoped per tenant.

**Device Tokens**
- [x] ✅ Register upserts by `device_id`, updates token, preserves other devices.
- [x] ✅ Unregister removes device by `device_id`.

## Hardening Gaps + Tests to Add

1) Data not-found behavior (`/data`)
- [x] ✅ Test: `GET /push/messages/{missing}/data` returns `ok=false` + `reason=not_found`.

2) Actions coverage beyond `clicked`
- [x] ✅ Test: `opened` records metrics (counts/unique).
- [x] ✅ Test: `dismissed` records metrics (counts/unique).
- [x] ✅ Test: `step_viewed` records per-step counts.
- [x] ✅ Test: `delivered` records metrics.
- [x] ✅ Test: missing `step_index` returns 422.
- [x] ✅ Test: `clicked` without `button_key` returns 422.

3) Permission matrix (systematic 401/403)
- [x] ✅ Test: 401 for account CRUD without token.
- [x] ✅ Test: 401 for tenant CRUD without token.
- [x] ✅ Test: 403 for account CRUD without required abilities.
- [x] ✅ Test: 403 for tenant CRUD without required abilities.
- [x] ✅ Test: landlord user without tenant access cannot read tenant push resources.

4) Cross-tenant isolation
- [x] ✅ Test: tenant A cannot access tenant B message `/data` or `/actions`.
- [x] ✅ Test: tenant A cannot read/update/delete tenant B message CRUD.
- [x] ✅ Test: tenant A cannot access tenant B credentials index/show/update/delete.

5) Plan policy at enqueue
- [x] ✅ Test: `PushPlanPolicyContract::canSend` false blocks schedule/dispatch on create.
- [x] ✅ Test: quota-check decision payload surfaced on create (if implemented).

6) FCM options limits/overrides
- [x] ✅ Test: notification title/body length caps enforced (422).
- [x] ✅ Test: platform overrides (`android.notification`, `apns.payload.aps.alert`) honored.

7) External button action validation
- [x] ✅ Test: `external` action missing URL returns 422.
- [x] ✅ Test: invalid `open_mode` returns 422.

## Decisions
- Push payload delivery: FCM payload contains `push_message_id` only; client fetches payload from API.
- Push message config endpoint: `GET /api/v1/push/messages/{push_message_id}`.
- Push message data endpoint: `GET /api/v1/push/messages/{push_message_id}/data`.
- Push message CRUD endpoints:
  - `POST /api/v1/push/messages`
  - `GET /api/v1/push/messages`
  - `PATCH /api/v1/push/messages/{push_message_id}`
  - `DELETE /api/v1/push/messages/{push_message_id}`
- Delete semantics: allow hard delete only if message has not been delivered; once sent, only archive/deactivate.
- Send semantics: create triggers delivery; immediate delivery dispatches on create. Jobs orchestrate async fan-out and delivery pipeline.
- Push payload fetch auth transport: `Authorization: Bearer <token>` header (never query params).
- Push payload fetch auth: bearer token required (anonymous token allowed).
- Push payload fetch authorization: 401 when no token, 404 when token lacks access to message.
- Push payload fetch response: structured JSON payload (no stringification required).
- Push payload TTL/validity: controlled by push settings; backend returns `ok=false` when expired/invalid.
- Push payload `active` state: backend decides whether to return payload; inactive returns `ok=false`.
- Audience evaluation happens at `/data` fetch time; segments are out of scope for V1.
- V1 audience types: `all`, `users`, `event`.
- Event audience qualifier is required and evaluated dynamically at fetch: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`.
- Push messages are partner-scoped by default; tenant-scoped messages exist for tenant owners.
- Message `type` supports partner-defined custom types; tenants can define custom types that partners use.
- Partner messages store `partner_id`; tenant messages rely on tenant database context (no `tenant_id` field).
- Message type definitions live under Tenant Settings (shared settings hub).
- Template variable defaults are configured per message by the creator.
- Delivery metrics recorded via per-user actions in a separate collection; transactions update aggregates.
- Metrics should include per-button breakdowns and unique user counts.
- Actions endpoint required for V1: `POST /api/v1/push/messages/{push_message_id}/actions`.
- Metrics should include per-step view counts (step index or step key).
- Actions payload should include the current step for `dismissed` and `clicked` to measure step drop-off.
- Step index is required for all action records (use `0` for single-step messages).
- Laravel push handling will be delivered as a standalone package.
- Push package scope includes device token management (register/unregister/rotation) plus message CRUD/data/actions, audience evaluation, and metrics aggregation.
- Settings remain in the main project; packages extend settings with their own sections/traits/data.
- Package location: keep in this repo until fully working, then extract to its own repository.
- Package name: `belluga_push_handler`.
- Package owns its migrations; host app loads them via `loadMigrationsFrom`.
- Package owns route registration; host app controls path strings via config defaults (no hardcoded paths in package).
- Plug'n'play package mandate: package handles tenant push settings, credential storage, and real delivery.
- Credentials: store in a dedicated `push_credentials` collection referenced by tenant settings.
- Credential security: app-level encryption now; design for KMS later.
- FCM client strategy: HTTP v1 only.
- Service account source: store minimal fields (project_id, client_email, private_key) in encrypted fields.
- Delivery pipeline: package owns fan-out + send + retry/backoff.
- Rate limiting: package enforces tenant throttles; account quotas enforced via `PushPlanPolicyContract`.
- Failure logging: separate `push_delivery_logs` collection plus aggregate metrics.
- Multi-project support: one Firebase project per tenant in v1.
- Settings surface: `push_enabled`, `firebase_public_config`, `throttles`, `push_message_types`, `push_message_routes`.
- Defaults/seeding: package provides defaults; host opts in via config flag.
- FCM flexibility: keep core fields (title/body/image) and add optional `fcm_options` for advanced FCM features, validated at send time.
- Quota check: add a dedicated quota-check endpoint that calls policy and returns a rich quota decision payload (allowed/limit/used/remaining). Use it for confirmation dialogs before enqueue.
- Delivery batching: use FCM bulk/multicast delivery in jobs to minimize requests; document batch size and partial failure handling.
- Audience eligibility is delegated to a host-provided contract (zero domain knowledge in package).
- Audience rules (opt-in, events, TTL) are implemented by the host app via the eligibility contract.
- Transactional single-recipient send:
  - Endpoint: `POST /api/v1/accounts/{account_slug}/push/messages/{push_message_id}/send`
  - Only allowed when `message.type = transactional`.
  - Resolve recipient by `user_id` or `email` (tenant-scoped, normalized).
  - Requires Sanctum + `push-messages:send`.
  - Enforce eligibility via host contract for transactional targets.
- Audience eligibility contract must be defined and bound by host; package provides allow-all default.
- Event TTL should be enforced by host eligibility logic (package stays domain-agnostic).
- PushPlanPolicyContract stays as-is (no extra context payload); message + account + audience size are sufficient.
- Credential management: tenant-only endpoints guarded by a dedicated, restrictive permission.
- Credential upsert updates the existing record (preserve id). If multiple credentials exist, return 409 and require cleanup.
- Settings payloads remove `firebase_credentials_id` from both request and response.
- FCM options: accept optional `fcm_options` object; no extra required fields at creation beyond core templates. Validate only when provided, per FCM platform rules.
- Audience eligibility contract proposal:
  - Interface: `PushAudienceEligibilityContract`
  - Method: `isEligible(AccountUser $user, PushMessage $message, array $audience, array $context = []): bool`
  - Context may include `tenant_id`, `account_id`, `now`, `audience_size`, `message_type`.
- Quota-check endpoint (account-scoped):
  - `GET /api/v1/accounts/{account_slug}/push/quota-check`
  - Requires Sanctum + `push-messages:send`
  - Query: `audience_size`, `message_type`, `push_message_id` (optional)
  - Returns decision payload (allowed/limit/current_used/requested/remaining_after/period/reason).
- Credential endpoints (tenant-scoped):
  - `PUT /api/v1/settings/push/credentials`
- FCM options mapping:
  - Default mapping: `title_template`/`body_template` populate `fcm_options.notification` when no notification override is provided.
  - Override rule: `fcm_options.notification` replaces defaults; no duplication.
  - Platform overrides: `fcm_options.android.notification` and `apns.payload.aps.alert` override generic notification per platform.
- Push message `/data` response payload is normalized to `push_handler` DTO shape:
  - Top-level `title`, `body`, `allowDismiss`, `layoutType`, `onClickLayoutType`, `image`, `steps`, `buttons`.
  - Buttons use `routeType` + `routeInternal`/`routeExternal` (no nested `action` object in the delivered payload).
- Credential endpoint payloads (tenant-only):
  - Upsert: `{ "project_id": "...", "client_email": "...", "private_key": "..." }` (all required; stored encrypted).
  - Read: `{ "id": "...", "project_id": "...", "client_email": "...", "created_at": "...", "updated_at": "..." }` (never return private_key).
- Delivery log schema:
  - `push_message_id`, `batch_id`, `token_hash`, `status` (`accepted|failed`), `error_code`, `error_message`, `provider_message_id`, `created_at`.
- Quota-check response schema:
  - `allowed`, `limit`, `current_used`, `requested`, `remaining_after`, `period`, `reason` (nullable string).
- Delivery log retention: keep indefinitely for MVP (no TTL or cap).
- Credential endpoints response codes: follow Laravel defaults and existing project patterns (201 on create, 200 on update/read; 422 validation; 403 permission).
- FCM options validation: mirror FCM HTTP v1 schema/allowed keys; enforce size caps per FCM limits when defined, otherwise apply conservative security caps.
- Plan policy hook: define a `PushPlanPolicyContract` in the package; default allow-all when no host binding exists. Host app can enable later to enforce account plan quotas during send jobs.
- Routing map:
  - Account routes (partner/account users): push message CRUD + `/data` + `/actions`.
  - Tenant routes: tenant push settings endpoint(s) stored in tenant database.
  - Landlord routes: tenant settings management (types, max TTL, global push settings).
- Tenant push messages mirror account routes with tenant-specific abilities; scope resolved once in controllers and passed to services.
- Tenant route surface mirrors account routes:
  - `POST /api/v1/push/messages`
  - `GET /api/v1/push/messages`
  - `GET /api/v1/push/messages/{push_message_id}`
  - `PATCH /api/v1/push/messages/{push_message_id}`
  - `DELETE /api/v1/push/messages/{push_message_id}`
  - `GET /api/v1/push/messages/{push_message_id}/data`
  - `POST /api/v1/push/messages/{push_message_id}/actions`
  - `POST /api/v1/push/messages/{push_message_id}/send` (transactional)
- Unique metrics: same user performing the same action on the same context (event/invite/etc) counts once.
- Message templates support declared variables; creators define variables and defaults (e.g., `value: "user.first_name"`, `default: ""`) and then reference variables in content.
- `internal_name` must be unique per Account.
- Abilities follow existing `resource:action` pattern and are split by actor:
  - Landlord users (tenant settings authority):
    - `push-settings:update` (types, max TTL, global push settings)
    - `push-messages:send` (send on behalf of an Account)
  - Account users (partners; account-scoped):
    - `push-messages:read`, `push-messages:create`, `push-messages:update`, `push-messages:delete`, `push-messages:send`
    - Permissions scoped per account role (existing account role permissions apply)
  - Tenant users (app owner; tenant-scoped):
    - `tenant-push-messages:read`, `tenant-push-messages:create`, `tenant-push-messages:update`, `tenant-push-messages:delete`, `tenant-push-messages:send`
    - `tenant-push-credentials:read`, `tenant-push-credentials:update` (create/update/delete)
- Partner = Account; each Account can have multiple Account Users with role-scoped permissions.
- TTL policy: message `expires_at` is required; server enforces max TTL from tenant settings (default 30 days); `ttl_minutes` derived and validated.
- Delivery metrics split into:
  - `accepted` (FCM accepted)
  - `delivered` (device receipt, client-reported)
  - `opened` (recipient action; data fetch ok=true or explicit action)
- `accepted` is updated from the FCM send response (fan-out job).
- `delivered` is updated from client callbacks (FCM onMessage/onBackgroundMessage).
- `/actions` accepts anonymous tokens and authenticates via bearer token when present.
- Frontend is responsible for preventing duplicate action submissions; backend allows repeated actions but aggregates unique metrics separately.
- Actions idempotency: client generates `idempotency_key` per action and submits it; backend uses it to de-duplicate processing.
- Jobs: fan-out and scheduling follow Laravel defaults (queued jobs, scheduled dispatch for `scheduled_at`, async send on create).
- Telemetry integrations are stored as an array of integration objects compatible with `event_tracker_handler`:
  - `type` (string, required; `mixpanel`, `firebase`, `webhook`)
  - `events` (array of strings, required)
  - `token` (string, required when `type = mixpanel`)
  - `url` (string, required when `type = webhook`)
- `/api/v1/environment` returns `telemetry` in the same `event_tracker_handler` shape (no additional mapping required by Flutter).

## Questions to Close
-- none --

## References
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/todos/active/TODO-v1-telemetry-and-push-frontend.md`

---

## Backend Requirements (V1)

### B1) Device registration
- [x] ✅ Implement `POST /api/v1/push/register` in upstream:
  - [x] ✅ accept `{ device_id, platform, push_token }`
  - [x] ✅ associate token with authenticated user + tenant
- [x] ✅ support anonymous + authenticated states for the same user object
- [x] ✅ Optional `DELETE /api/v1/push/unregister` in upstream
- [x] ✅ Handle token rotation idempotently

### B2) Notification policies (tenant settings)
- [x] ✅ Return which notification categories are enabled and any throttles (tenant settings)
- [x] ✅ Keep backend authoritative; Flutter should not implement quota rules beyond UX

### B2.1) Tenant admin management (config storage)
- [x] ✅ Provide a Tenant Admin area (not Landlord Admin) where landlord users with tenant access can manage:
  - [x] ✅ Mixpanel project token per tenant
  - [x] ✅ Firebase project options per tenant (public config only)
- [x] ✅ Persist configs per tenant and expose them through a single environment/bootstrap payload (no parallel config calls)

### B3) Notification payload contract (deep linking)
- [x] ✅ Provide routing data for the client fetch payload:
  - `tenant_id`
  - `type`: `invite_received | event_reminder | invite_status_changed | ...`
  - `event_id` (if applicable)
  - `invite_id` or `invite_code` (if applicable)
  - optional `inviter_principal` summary for display

### B4) Environment payload (single call; public-safe)
This config must be merged into the existing `/api/v1/environment` response (no parallel calls).

```json
{
  "tenant_id": "tenant_123",
  "telemetry": [
    {
      "type": "mixpanel",
      "token": "public_token_here",
      "events": [
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
    }
  ],
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

---

## Push Message Schema (V1)

**PushMessage (config)**
- `id` (ObjectId)
- `partner_id` (ObjectId, required for partner messages)
- `internal_name` (string, required, max 120, unique per account)
- `title_template` (string, max 255)
- `body_template` (string, max 1000)
- `type` (string, tenant-defined type key)
- `active` (bool, default true)
- `status` (enum)
- `audience` (object)
  - `type` (enum)
  - `user_ids` (array<ObjectId>, required if `users`)
  - `event_id` (ObjectId, required if `event`)
  - `event_qualifier` (enum, required if `event`)
- `delivery` (object)
  - `scheduled_at` (datetime, optional)
  - `expires_at` (datetime, optional)
  - `ttl_minutes` (int, optional)
- `payload_template` (object)
  - `layoutType` (enum)
  - `onClickLayoutType` (enum, optional)
  - `allowDismiss` (string: `"true"` / `"false"`)
  - `image` (optional `{ path, width?, height? }`)
  - `steps` (array of `{ title, body?, image? }`)
  - `buttons` (array of `{ label, action, color? }`)
    - `action` (object)
      - `type` (enum: `route`, `external`)
      - `route_key` (string, required if `route`)
      - `path_parameters` (object, required if `route`; must include all `path_params` keys with non-empty values)
      - `query_parameters` (object, optional; validated against route `query_params`)
      - `url` (string, required if `external`)
      - `open_mode` (enum: `in_app`, `external`, optional)
- `template_defaults` (object, per message variable defaults)
- `metrics` (object)
  - `sent_count` (int)
  - `accepted_count` (int)
  - `delivered_count` (int)
  - `opened_count` (int)
  - `clicked_count` (int)
  - `dismissed_count` (int)
  - `unique_opened_count` (int)
  - `unique_clicked_count` (int)
  - `unique_dismissed_count` (int)
  - `step_view_counts` (map<int,int>)
  - `button_click_counts` (map<string,int>)
- `created_at`, `updated_at`, `sent_at`, `archived_at`

**Field Definitions**
- `status`: `draft`, `scheduled`, `sent`, `archived`
- `audience.type`: `all`, `users`, `event`
- `audience.event_qualifier`: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`
- `payload_template.layoutType`: `fullScreen`, `bottomModal`, `popup`, `actionButton`, `snackBar`
- `payload_template.onClickLayoutType`: `fullScreen`, `bottomModal`, `popup`, `actionButton`, `snackBar`
- `payload_template.buttons.action.type`: `route`, `external`
- `payload_template.buttons.action.open_mode`: `in_app`, `external`

**Available Routes (Tenant Settings)**
- `key` (string, unique within tenant settings)
- `path` (string, uses `:param` tokens, e.g. `/agenda/evento/:slug`)
- `path_params` (array, derived from `path`; stored for UI/validation)
- `query_params` (object, `key: validation_rule`, Laravel-style validation rules)

---

## Settings Schema (V1)

**Tenant Settings**
- `push_message_types` (array)
  - `key` (string, unique within tenant settings)
  - `label` (string)
  - `description` (string, optional)
  - `default_audience_type` (enum: `all`, `users`, `event`, optional)
  - `default_event_qualifier` (enum, optional)
  - `throttles` (object, optional)
- `push_message_routes` (array)
  - `key` (string, unique within tenant settings)
  - `path` (string, includes `:param` tokens)
  - `path_params` (array, derived from `path`)
  - `query_params` (object, `key: validation_rule`)

**Field Definitions**
- `default_event_qualifier`: `event.confirmed`, `event.invited`, `event.all`, `event.sent_invites`

---

## Push Message Data Response (V1)

```json
{
  "ok": true,
  "push_message_id": "string",
  "payload": { "..." : "push_handler payload" }
}
```

Payload is normalized to `push_handler` DTO keys (`title`, `body`, `allowDismiss`, `layoutType`, `onClickLayoutType`, `image`, `steps`, `buttons` with `routeType/routeInternal/routeExternal`).

When invalid/inactive:
```json
{ "ok": false, "reason": "inactive" }
```

**Field Definitions**
- `reason`: `inactive`, `expired`, `not_found`

---

## Push Message Actions (V1)

**Endpoint**
- `POST /api/v1/push/messages/{push_message_id}/actions`

**Action Payload**
- `action` (enum)
- `step_index` (int, required for all actions; use `0` for single-step messages)
- `button_key` (string, required for `clicked`)
- `device_id` (string, optional)
- `metadata` (object, optional)

**Field Definitions**
- `action`: `opened`, `clicked`, `dismissed`, `step_viewed`, `delivered` (`delivered` is device receipt)
