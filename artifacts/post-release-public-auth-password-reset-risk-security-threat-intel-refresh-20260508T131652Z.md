# RR-AUTH-04 Security Threat-Intel Refresh - 20260508T131652Z

## Scope

- **Governing TODO:** `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-public-auth-password-reset-and-risk-matrix-hardening.md`
- **Baseline under review:** reopened RR-AUTH-04 clean-baseline packet
- **Purpose:** refresh current primary-source guidance for public auth and password-reset hardening before the reopened security review lane closes.

## Primary Sources Reviewed

1. OWASP Forgot Password Cheat Sheet
   - `https://cheatsheetseries.owasp.org/cheatsheets/Forgot_Password_Cheat_Sheet.html`
2. OWASP Authentication Cheat Sheet
   - `https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html`
3. NIST SP 800-63B (current `800-63-4` web publication)
   - `https://pages.nist.gov/800-63-4/sp800-63b.html`
4. Laravel password reset documentation
   - `https://laravel.com/docs/10.x/passwords`

## Relevant Guidance Extracted

- OWASP forgot-password guidance still requires:
  - consistent account-existence messaging
  - consistent response timing
  - single-use, expiring reset tokens
  - no account-state change before a valid token is presented
  - rate-limit or equivalent protections against automated reset abuse
- OWASP authentication guidance still treats password-reset and password-recovery responses as enumeration-sensitive and explicitly warns against quick-exit timing discrepancies.
- NIST SP 800-63B still requires password establishment/change flows to compare the entire candidate secret against a blocklist of commonly used, expected, or compromised passwords, with explicit rejection guidance and rate-limited failure handling.
- Laravel password-reset guidance still highlights trusted-host handling as especially important for password reset functionality because host header values can influence generated absolute URLs.

## RR-AUTH-04 Relevance Assessment

### Reset timing / enumeration

- The reopened `PasswordResetFlowService` plus `PasswordResetTokenService::attemptConsumeForUser(...)` and `rejectInvalidResetAttempt(...)` align with the OWASP requirement to avoid quick-exit divergence between missing-user and wrong-token paths.
- The existing RR-AUTH-04 endpoint tests already preserve generic outward reset failure behavior; the reopened unit tests add explicit regression proof for the shared rejection boundary.

### Token lifecycle

- The current reset-token service already enforces single-slot issuance, expiry-bound consumption, and single-use deletion semantics, which remains aligned with OWASP’s reset-token expectations.
- The reopened shared flow removes tenant/landlord orchestration drift that could otherwise have let those semantics diverge over time.

### Password policy

- The new `CanonicalPasswordRules` + `CommonBreachedPasswordRule` bring RR-AUTH-04 into alignment with the NIST requirement to reject known common/compromised passwords during password establishment and change flows.
- The present implementation uses a curated denylist floor rather than an external breach API. That is acceptable for the bounded hardening slice because the requirement is a blocklist floor, not a specific provider integration, but future password-hardening expansion could justify a larger corpus.

### Abuse controls / risk matrix

- RR-AUTH-04 already ships explicit route-domain risk-matrix entries and subject-aware throttling ceilings for public password/reset endpoints.
- The reopened structural route/middleware guardrail is consistent with OWASP’s requirement to protect password-reset request surfaces from excessive automated submissions.

### Trusted-host note

- Laravel’s trusted-host reminder is still relevant to password reset safety.
- This reopen did not change host generation or trusted-host middleware; no new delta was required inside RR-AUTH-04, but the reminder remains valid as an adjacent platform invariant rather than an open RR-AUTH-04 blocker.

## Outcome

- No new external guidance was found that reopens RR-AUTH-04 beyond the already implemented debt-elimination corrections.
- The reopened baseline materially improves alignment with current OWASP and NIST password-reset/password-policy guidance.
- The fresh security review lane should still challenge the implementation for concrete bounded blockers, but this threat-intel refresh does not reveal a missing mandatory correction inside the frozen RR-AUTH-04 scope.
