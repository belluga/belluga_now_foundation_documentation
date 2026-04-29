# T2 Phone OTP Auth And Contact Match Review Packet

Derived package for independent audit. This file is non-authoritative; canonical decisions remain in the TODO and module docs.

## Scope
- TODO: `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- Delivery slice: Belluga tenant-public phone-first OTP cutover with backend-owned queued webhook delivery, anonymous-to-authenticated merge, and contact-match continuity.
- Explicit business decision consumed: OTP/WhatsApp delivery is webhook-based for now, dispatched by a queued job, with tenant settings for WhatsApp and optional OTP-specific URLs.

## Backend Changes
- Added `POST /api/v1/auth/otp/challenge` and `POST /api/v1/auth/otp/verify`.
- Added tenant model/migration for `phone_otp_challenges`.
- Added challenge indexes:
  - `idx_phone_otp_challenges_phone_status_expiry_v1` over normalized phone + status + expiry for active-challenge lookup;
  - `idx_phone_otp_challenges_phone_hash_v1` for hash-oriented lookup/audit paths;
  - `idx_phone_otp_challenges_created_at_v1` for operational inspection.
- Added backend OTP orchestration:
  - server-side phone normalization;
  - one active challenge per phone with resend cooldown;
  - TTL expiry;
  - max attempts;
  - verified phone materialization on `AccountUser.phones` / `phone_hashes`;
  - anonymous identity merge via existing `AnonymousIdentityMerger`;
  - token issuance through `TenantScopedAccessTokenService`;
  - telemetry events `otp_challenge_started`, `otp_verified`, and `auth_merge_completed`.
- Added outbound integration settings namespace:
  - `outbound_integrations.whatsapp.webhook_url`;
  - `outbound_integrations.otp.webhook_url`;
  - `outbound_integrations.otp.use_whatsapp_webhook`;
  - `outbound_integrations.otp.delivery_channel`;
  - `outbound_integrations.otp.ttl_minutes`;
  - `outbound_integrations.otp.resend_cooldown_seconds`;
  - `outbound_integrations.otp.max_attempts`.
- Added `DeliverPhoneOtpWebhookJob` as `ShouldQueue` + `TenantAware`, with retry/backoff and HTTP delivery service.

## Flutter Changes
- Added OTP challenge/verify methods to auth backend and repository contracts.
- Added typed DAO responses for OTP challenge and verification.
- Added domain `AuthPhoneOtpChallenge`.
- Added repository flow that:
  - requests OTP challenge;
  - verifies OTP;
  - passes the stored anonymous user id as `anonymous_user_ids`;
  - persists the returned token/user id;
  - merges telemetry identity after authenticated upgrade.
- Updated tenant-public auth controller to own phone/OTP form controllers, form keys, OTP step state, challenge state, loading/disabled state, and validation.
- Updated tenant-public auth UI to show phone OTP instead of email/password/signup on tenant-public context.
- Landlord/admin login remains through the existing landlord sheet; the tenant-public email/password/signup entry is no longer exposed in the tenant-public login surface.

## Documentation Changes
- `modules/flutter_client_experience_module.md`: documents OTP endpoints, anonymous identity endpoint, backend-owned queued webhook delivery, and Flutter provider-agnostic responsibility.
- `modules/onboarding_flow_module.md`: documents phone OTP upgrade and anonymous identity merge handoff.
- `modules/invite_and_social_loop_module.md`: documents OTP verify as adjacent identity endpoint and phone-hash materialization timing.

## Validation Evidence
- Laravel fail-first:
  - `php artisan test tests/Feature/Auth/TenantPhoneOtpAuthTest.php`
  - Initial expected failure: `Class "App\Models\Tenants\PhoneOtpChallenge" not found`.
- Laravel OTP feature:
  - `php artisan test tests/Feature/Auth/TenantPhoneOtpAuthTest.php`
  - Result: 6 passed, 31 assertions.
  - Exact contact-match continuity coverage: `test_phone_otp_verification_promotes_identity_and_materializes_contact_match_hash` verifies OTP verification stores normalized phone, materializes `phone_hashes`, records anonymous merge source id, and resolves the verified user through `ContactImportService` using the phone hash.
  - Exact TTL coverage: `test_phone_otp_verify_rejects_expired_challenge`.
  - Exact cooldown coverage: `test_phone_otp_challenge_enforces_resend_cooldown`.
  - Exact fallback coverage: `test_phone_otp_challenge_can_use_whatsapp_webhook_when_otp_url_is_not_configured`.
- Laravel webhook delivery unit:
  - `php artisan test tests/Unit/Application/Auth/PhoneOtpWebhookDeliveryServiceTest.php`
  - Result: 1 passed, 1 assertion.
- Laravel tenant-aware queue:
  - `php artisan test tests/Unit/Queue/TenantAwareQueueJobsTest.php`
  - Result: 1 passed, 10 assertions.
- Laravel settings kernel focused:
  - `php artisan test tests/Feature/Settings/SettingsKernelControllerTest.php --filter '/(test_settings_schema_endpoint_returns_registered_namespaces|test_settings_values_endpoint_returns_namespace_values)/'`
  - Result: 2 passed, 34 assertions.
- Laravel formatter:
  - `php vendor/bin/pint --test <changed-files>`
  - Result before final new unit: 21 files passed.
  - New webhook unit formatted with `php vendor/bin/pint tests/Unit/Application/Auth/PhoneOtpWebhookDeliveryServiceTest.php`.
- Flutter fail-first:
  - `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`
  - Initial expected failures: missing `AuthPhoneOtpChallenge`, missing `AuthPhoneOtpStep`, and missing repository injection/methods.
- Flutter focused:
  - `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart test/infrastructure/repositories/auth_repository_signup_test.dart`
  - Result: 7 tests passed.
- Flutter analyzer:
  - `fvm dart analyze --format machine`
  - Result: exit code 0, no analyzer output.

## Deliberate Exclusions
- ADB/device integration was deferred by orchestration policy to the final consolidated phase because the connected-device environment is resource-sensitive.
- Real external webhook provider delivery was not executed; delivery was validated with Laravel HTTP fakes and queue assertions. Live provider/template approval remains release-readiness, not Flutter-owned behavior.
- Authenticated web QR bootstrap remains out of scope; tenant-public web auth gates continue to use promotion handoff.

## Post-Review Gap Closure - 2026-04-29
- Added the missing tenant-admin frontend consumer for `outbound_integrations`: hub entry, technical integrations section, repository contract methods, request encoder, response decoder, controller save flow, and widget coverage.
- Updated `modules/tenant_admin_module.md` with the outbound webhook settings contract and decision `TAD-14`, requiring release-critical backend settings namespaces to have an explicit tenant-admin consumer or explicit backend-only waiver.
- Added gap-analysis artifact: `foundation_documentation/artifacts/store-release-backend-front-gap-analysis-20260429.md`.

Validation:

```bash
fvm flutter test \
  test/infrastructure/repositories/tenant_admin_settings_repository_test.dart \
  test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart
```

Result: `77/77` tests passed.

```bash
fvm dart analyze --format machine
```

Result: exit code `2` because of pre-existing T2/T3 OTP/invite architecture debts; no diagnostics remain in the outbound webhook settings files.

## Review Focus
- Elegance: verify old tenant-public email/password/signup paths are not still exposed as release behavior and the OTP flow is not duplicating identity rules outside existing services.
- Performance: verify challenge lookup/indexing, job delivery/retry, and contact-match continuity do not introduce unbounded scans or synchronous outbound delivery.
- Test quality: verify coverage is adequate for challenge dispatch, OTP verify, cooldown/TTL, webhook delivery, anonymous merge, contact hash matching, Flutter controller/UI cutover, and analyzer contract.
