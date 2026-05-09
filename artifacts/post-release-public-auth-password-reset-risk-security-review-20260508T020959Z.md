# RR-AUTH-04 Security Adversarial Review

## Findings

The corrected RR-AUTH-04 baseline closes the earlier single-use token race and landlord risk-matrix coverage defects. I reran the focused Laravel RR-AUTH-04 suite on the current baseline and it passed with `104 passed`, `619 assertions`, so the findings below are the remaining material gaps around the hardened reset core rather than stale objections.

| Issue ID | Severity | Validation | Finding | Evidence | Exploit Path | Blast Radius | Recommended Fix | Verification / Follow-up |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `RR-AUTH-04-SEC-01` | `high` | `validated_from_code` | Password reset does not revoke existing bearer/session authenticators, so recovery does not fully evict a prior compromise. | `laravel-app/app/Application/Profiles/TenantProfileService.php:113-139` and `laravel-app/app/Application/Profiles/LandlordProfileService.php:66-89` update the password after token consumption but never revoke tokens or sessions. Explicit token revocation exists only on manual logout in `laravel-app/app/Application/Auth/AccountAuthenticationService.php:49-60` and `laravel-app/app/Application/Auth/LandlordAuthenticationService.php:45-56`. `laravel-app/config/sanctum.php:49` leaves token expiration `null`. | An attacker who already holds a valid Sanctum token can stay authenticated after the victim completes a public password reset, including on landlord/admin surfaces. | Tenant-public account sessions and landlord/admin sessions. | Revoke all active tokens/sessions for the recovered principal as part of the reset transaction, then require fresh authentication on subsequent requests. Pair that with a user-facing reset notification. | Add tenant and landlord regression tests that prove an old bearer token fails immediately after password reset, and that a recovery notification is emitted. |
| `RR-AUTH-04-SEC-02` | `medium` | `validated_from_code` | Public password-login throttling is still keyed to the caller identity/IP, not to the target account, so distributed password spraying can bypass the new route ceilings. | `laravel-app/app/Http/Middleware/ApiSecurityHardening.php:473-479` builds the rate-limit key from `level + domain + identity`, and unauthenticated identity resolves to client IP in `:666-679`. Public password-login routes are exposed at `laravel-app/config/api_security.php:87-141` with only route RPM ceilings. OWASP Authentication guidance says failed-login counters should be associated with the account rather than the source IP. | A botnet or rotating-proxy attacker can spread guesses for one landlord account, or any tenant where password auth is explicitly enabled, across many IPs without tripping a single account-bound counter. | Landlord public login by default, plus any tenant that explicitly enables password login. | Add an account- or credential-subject-based failed-attempt counter with progressive backoff or temporary lockout, while retaining IP and route-domain throttles as secondary controls. | Add a multi-IP password-spray regression that proves the same target account hits a shared failure budget even when source IPs change. |
| `RR-AUTH-04-SEC-03` | `medium` | `reproduced_by_existing_test` | Rate-governing still fails open when the limiter backend is unhealthy, leaving public-auth routes unthrottled during cache/Redis degradation. | `laravel-app/config/api_security.php:251-256` defaults `fail_closed_on_backend_error` to `false`. `laravel-app/app/Http/Middleware/ApiSecurityHardening.php:500-524` logs the backend error and continues unless that flag is enabled. The focused rerun passed `tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`, including `test_rate_limiter_backend_error_fails_open_by_default`. | During a cache/rate-limiter outage, attackers can send unthrottled login, reset-token, reset, OTP, and anonymous-identity traffic on the protected routes. | All public auth routes covered by `ApiSecurityHardening`, including tenant and landlord password surfaces. | Make public-auth and other abuse-sensitive domains fail closed on limiter-backend errors, or split them onto a dedicated highly-available limiter path with an explicit fail-safe fallback. | Add a deterministic guard that forbids fail-open defaults for named public-auth domains unless the governing TODO records an explicit waiver. |
| `RR-AUTH-04-SEC-04` | `low` | `validated_from_code` | Generic pre-auth telemetry and auth-failure logging remain incomplete outside the tenant reset-token issuance path. | `laravel-app/app/Http/Api/v1/Controllers/ProfileControllerTenant.php:72-84` emits generic pre-auth telemetry for tenant reset-token issuance, and the focused rerun passed `tests/Feature/Tenants/PasswordRegistrationControllerTest.php::test_password_token_requests_emit_generic_pre_auth_telemetry`. But `laravel-app/app/Http/Api/v1/Controllers/ProfileControllerLandlord.php:47-68` emits no equivalent telemetry for landlord reset issuance/use, and failed logins in `laravel-app/app/Http/Api/v1/Controllers/AuthControllerAccount.php:25-40` plus `laravel-app/app/Http/Api/v1/Controllers/AuthControllerLandlord.php:22-37` return directly without auth-failure telemetry/logging. OWASP Logging guidance expects authentication successes and failures to be logged for abuse detection. | Password spraying, credential-stuffing, or reset abuse on landlord and failed-login paths can remain hard to detect until a separate hard block is hit. | Detection and incident-response coverage across tenant and landlord public auth flows. | Emit generic pre-auth telemetry or security logs for landlord reset issuance/use and for failed password-auth attempts, keyed to hashed target identifiers plus route domain. | Add parity tests asserting auth-failure and landlord reset telemetry/logging envelopes without leaking raw account identifiers. |

## Security Risk Level

`high`

The corrected baseline materially improved replay/race behavior inside the reset-token core, but the remaining public-auth posture is still high risk because password recovery does not evict compromised long-lived tokens and the route-rate controls still have bypass and fail-open conditions.

## Attack Simulation Decision

`recommended`

I did not run live exploit traffic beyond the existing safe Laravel regression suite. A local attack-simulation pass is still recommended before promotion for two paths: stale-token persistence after password reset and multi-IP password spraying against landlord login or any tenant that explicitly enables password auth.

## Attack Surfaces Reviewed

- Tenant-public password routes: `/api/v1/auth/login`, `/api/v1/auth/register/password`, `/api/v1/auth/password_token`, `/api/v1/auth/password_reset`
- Landlord public password routes: `/admin/api/v1/auth/login`, `/admin/api/v1/auth/password_token`, `/admin/api/v1/auth/password_reset`
- Shared reset-token lifecycle in `laravel-app/app/Application/Auth/PasswordResetTokenService.php`
- Tenant and landlord reset orchestration in `TenantProfileService` and `LandlordProfileService`
- Public-auth rate-governing and abuse controls in `laravel-app/app/Http/Middleware/ApiSecurityHardening.php` and `laravel-app/config/api_security.php`
- Generic pre-auth telemetry paths in `ProfileControllerTenant`, `ProfileControllerLandlord`, `AuthControllerAccount`, and `AuthControllerLandlord`
- Long-lived bearer-token/session behavior via Sanctum config and the auth services

## Threat-Intel Sources Used

- OWASP Forgot Password Cheat Sheet
  - https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet.html
  - Used for reset-token lifecycle expectations, uniform reset handling, per-account reset-abuse controls, reset notifications, and session invalidation after recovery.
- OWASP Authentication Cheat Sheet
  - https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html
  - Used for account-scoped login throttling guidance and generic authentication-response posture.
- OWASP Session Management Cheat Sheet
  - https://cheatsheetseries.owasp.org/cheatsheets/Session_Management_Cheat_Sheet.html
  - Used for reauthentication/session-integrity expectations after password reset and account-recovery events.
- OWASP Logging Cheat Sheet
  - https://cheatsheetseries.owasp.org/cheatsheets/Logging_Cheat_Sheet.html
  - Used for auth success/failure logging and multi-IP abuse detection expectations.
- NIST SP 800-63B-4
  - https://pages.nist.gov/800-63-4/sp800-63b.html
  - Used for throttling expectations on subscriber accounts, recovery notifications, and authenticator invalidation after compromise.

## Residual Security Risk

- The corrected baseline does appear to have fixed the earlier RR-AUTH-04 replay/race concern inside `PasswordResetTokenService`: token consumption is now an atomic `deleteOne` on `slot_key + token_lookup_hash + expires_at`, the focused suite passed, and the old concurrency objection is no longer supported by the current code.
- Tenant-scope reset isolation is materially improved and currently evidenced by the passing focused suite, including scope-separated cooldowns and token slots in `PasswordResetTokenServiceTest`.
- Uniform timing resistance for existent versus nonexistent reset requests remains unproven. The new work-factor padding is a real improvement, but the hit path still performs DB mutation and event dispatch while the miss path does not, so a local timing harness remains advisable if promotion requires hard enumeration-resistance proof.
- RR-AUTH-04 should not be promoted as security-clean until the four findings above are either fixed or explicitly waived by the approval authority.

## Promotion Candidates

- Add a reusable Delphi security test pattern that requires password-reset completion to revoke all prior bearer tokens/sessions and prove stale-token failure.
- Promote a deterministic guard or checklist item that public password-login throttling must include an account- or credential-subject dimension, not only IP/domain buckets.
- Promote a deterministic guard that public-auth domains cannot default to fail-open limiter behavior without an explicit TODO-local waiver.
- Add a reusable checklist item for tenant/landlord parity on generic pre-auth telemetry and auth-failure logging.
