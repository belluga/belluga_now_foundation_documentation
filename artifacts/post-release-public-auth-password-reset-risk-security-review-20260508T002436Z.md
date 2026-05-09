# RR-AUTH-04 Security Adversarial Review

## Findings

| ID | Severity | Finding | Evidence | Exploitability | Fix guidance |
| --- | --- | --- | --- | --- | --- |
| `RR-AUTH-04-SEC-01` | `medium` | Password reset token consumption is not atomic, so the same valid token can plausibly win more than one concurrent reset request. | `laravel-app/app/Application/Auth/PasswordResetTokenService.php:62-85` reads, validates, and deletes in separate statements. The password mutation happens afterward in `laravel-app/app/Application/Profiles/TenantProfileService.php:97-111` and `laravel-app/app/Application/Profiles/LandlordProfileService.php:54-66`. | `plausible_not_reproduced`. Any actor holding a valid token can race two reset submissions; both requests can pass validation before either delete lands, producing last-write-wins password state and breaking the claimed single-use invariant. | Make token consumption atomic with a compare-and-delete or transactional state transition keyed to the specific live token record, and add a concurrent reset regression test. |
| `RR-AUTH-04-SEC-02` | `medium` | The reset-token request and reset-password flows still leak account existence through timing even though the JSON message is generic. | Missing-user branches return early in `laravel-app/app/Application/Profiles/TenantProfileService.php:84-95` and `:97-105`, and in `laravel-app/app/Application/Profiles/LandlordProfileService.php:41-52` and `:54-62`. Existing-user branches do materially more work, including `random_bytes`, `Hash::make`, DB writes, and `Hash::check` in `laravel-app/app/Application/Auth/PasswordResetTokenService.php:31-59` and `:62-85`. Response bodies stay generic in `laravel-app/app/Http/Api/v1/Controllers/ProfileControllerTenant.php:72-89` and `laravel-app/app/Http/Api/v1/Controllers/ProfileControllerLandlord.php:47-54`. | `plausible_not_reproduced`. A remote attacker can sample latency across repeated requests and separate existing from nonexistent emails on password-enabled surfaces. | Equalize the work factor for hit and miss paths or move issuance to an async side channel, then add a timing-regression harness for existent vs nonexistent accounts. |
| `RR-AUTH-04-SEC-03` | `medium` | Reset issuance is only IP-throttled; there is no per-account cooldown, so distributed clients can flood a victim inbox and continuously invalidate the latest token. | Public tenant reset issuance stays exposed at `laravel-app/routes/api/public_tenant_maybe_api_v1.php:91-107`. The explicit risk-matrix cap for `/api/v1/auth/password_token` is only `10` requests per minute in `laravel-app/config/api_security.php:102-117`. `laravel-app/app/Http/Middleware/ApiSecurityHardening.php:457-482` keys rate limiting by `level + identity`, and `:648-679` resolves unauthenticated identity to client IP only. `laravel-app/app/Application/Auth/PasswordResetTokenService.php:36-46` deletes the prior token before creating a new one. | `high`. A botnet or rotating-proxy attacker can repeatedly request resets for one target email, invalidating each newly issued token and generating sustained reset spam without needing mailbox access. | Add a hashed target-identifier cooldown and issuance ceiling independent of source IP, and keep the generic response body while logging per-account abuse state. |
| `RR-AUTH-04-SEC-04` | `medium` | RR-AUTH-04 hardened only the tenant-public password routes; equivalent landlord public password routes still inherit ambient defaults and are outside the new deterministic coverage. | Explicit auth domains added in `laravel-app/config/api_security.php:63-117` are all `tenant_public_*`. Public landlord password endpoints remain reachable in `laravel-app/routes/api/tenant_api_v1.php:26-37` and `laravel-app/routes/api/landlord_api_v1.php:32-43`. The guardrail only requires the tenant-public auth domains in `laravel-app/scripts/architecture_guardrails.php:1348-1360`. | `medium`. If those landlord/admin-auth surfaces are internet reachable, attackers get the generic L2 profile instead of the tighter route-specific ceilings now applied to tenant-public reset and login flows. | Register explicit landlord public auth/login/reset domains with route-specific ceilings and extend the guardrail/test contract to require them. |

## Security Risk Level

`high`

The combined exposure stays high because the reviewed slice governs internet-facing password-auth and reset paths, and the current baseline still has replay-race, enumeration, and abuse-control gaps.

## Attack Simulation Decision

`recommended`

Static adversarial review was completed; live exploit execution was not run in this artifact. A local race harness for token reuse and a timing-symmetry harness for existent vs nonexistent emails should run before promotion.

## Attack Surfaces Reviewed

- Tenant-public password auth routes: `/api/v1/auth/login`, `/api/v1/auth/register/password`, `/api/v1/auth/password_token`, `/api/v1/auth/password_reset`
- Tenant-public OTP and anonymous identity rate-governed neighbors: `/api/v1/auth/otp/challenge`, `/api/v1/auth/otp/verify`, `/api/v1/anonymous/identities`
- Landlord public password auth routes in both reviewed route surfaces: `/auth/login`, `/auth/password_token`, `/auth/password_reset`
- Shared `password_reset_tokens` lifecycle in `PasswordResetTokenService`
- API abuse/risk-matrix enforcement in `ApiSecurityHardening` and `config/api_security.php`
- Deterministic auth-risk guardrail coverage in `scripts/architecture_guardrails.php`

## Threat-Intel Sources Used

- OWASP Forgot Password Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet.html
- OWASP Authentication Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html

## Residual Security Risk

- The public-auth throttling layer still fails open when the rate-limiter backend is unhealthy by default: `laravel-app/config/api_security.php:226-230` and `laravel-app/app/Http/Middleware/ApiSecurityHardening.php:483-505`.
- I did not observe session/token revocation or user-facing post-reset notification in the reviewed reset paths; that remains a recovery-hardening gap even after RR-AUTH-04.
- RR-AUTH-04 should not be promoted as security-clean until the four findings above are resolved or explicitly waived by the approval authority.

## Promotion Candidates

- Extend the deterministic API-security guardrail so every public password-auth surface, not only `tenant_public_*`, must have named risk-matrix coverage.
- Add a reusable security test pattern for atomic single-use token consumption under concurrency.
- Add a reusable security test pattern or checklist item for timing-symmetric forgot-password responses and per-account reset throttling.
