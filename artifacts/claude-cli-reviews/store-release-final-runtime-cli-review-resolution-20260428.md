# Store Release Final Runtime Claude Review Resolution

**Artifact kind:** `claude_cli_review_resolution`  
**Created:** 2026-04-28  
**Input review:** `foundation_documentation/artifacts/claude-cli-reviews/store-release-final-runtime-cli-review-20260428.md`

## Adjudication

The Claude CLI returned a substantive final-runtime review. Most findings confirmed already-known final release-readiness boundaries rather than local implementation blockers:

- `BLK-01` OTP provider readiness: valid `Production-Ready` dependency, already represented as external readiness for the queued webhook/provider path.
- `BLK-02` live OTP device/backend/provider smoke: valid `Production-Ready` runtime gap; current ADB evidence only proves phone-OTP UI/redirect with a fake challenge.
- `BLK-03` external telemetry sink/query readback: valid `Production-Ready` T4 dependency; local T4 gate remains honest but not final release closure.

One finding was a new actionable local gap:

- `GAP-01` legacy tenant-public email/password quarantine was accepted as a real Store Release blocker before promotion because the T2 TODO still had the validation step unchecked and the Laravel tenant-public password routes were still publicly reachable when Belluga effective auth was `phone_otp`.

## Resolution

`GAP-01` was resolved in Laravel by adding `EnsureTenantPublicAuthMethod` route middleware and applying it to tenant-public password auth surfaces:

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register/password`
- `POST /api/v1/auth/password_token`
- `POST /api/v1/auth/password_reset`
- `PATCH /api/v1/profile/password`

The route still exists for the generic platform, but when the effective tenant-public auth methods exclude `password`, it now returns an `auth_method` validation error before controller/form-request execution. This preserves the upstream generic auth-governance rule while enforcing the Belluga Store Release `phone_otp` behavior.

## Evidence

- `bash scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`
  - Result: passed, `7` tests / `21` assertions.
- `docker compose exec -T app ./vendor/bin/pint --test app/Http/Middleware/EnsureTenantPublicAuthMethod.php routes/api/public_tenant_maybe_api_v1.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php`
  - Result: passed.

## Triple Audit vs Claude Relevance

The triple audit was more relevant for code-structure blockers inside each bounded TODO, especially T3's ownership-vs-eligibility split and T1/T4 implementation correctness. The Claude final-runtime review was more useful as a consolidated governance/readiness pass after ADB evidence existed: it found one local checklist/enforcement gap that the per-TODO triple audits did not name.

Net result: no change of product direction. The only local blocker found by Claude is resolved; the remaining Claude blockers are release-readiness gates, not local implementation blockers.
