# Title
Post-Release Hardening: Phone OTP Concurrency and Idempotency Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Created on:** `2026-05-04`
- **Source trigger:** promotion-lane PR review on `stage -> main`
- **Why this exists:** main-lane review surfaced real concurrency/idempotency weaknesses in the phone OTP flow. They do not currently justify stopping the release cut, but they are not noise and must be hardened immediately after publication.
- **Release-gate status:** not a blocker for the current promotion unless a deeper probe demonstrates cross-account issuance, tenant-isolation failure, or a broader auth-compromise path.

## Context
The delivered phone OTP flow is functionally green and already promoted through the Android store-release lane, but the main-promotion review surfaced three concurrency-sensitive weaknesses inside `TenantPhoneOtpAuthService`:

- successful OTP verification is not consumed with an atomic `pending -> verified` transition before token issuance,
- invalid OTP attempt counting is a read-modify-write path that can undercount concurrent failures and delay lockout,
- challenge replacement / resend cooldown enforcement can race under parallel challenge requests for the same phone.

These are real auth hardening gaps. The current evidence does not show tenant crossover or wrong-user token issuance; the risk is duplicate side effects and weakened brute-force / cooldown semantics under burst concurrency. That puts this work in immediate post-release hardening rather than the current release blocker lane.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-phone-otp-concurrency-hardening`
- **Why this is the right current slice:** the defect family is already tightly scoped to one service and one auth contract.
- **Direct-to-TODO rationale:** safe. The issue set is concrete and came from review evidence, not open product discovery.

## Contract Boundary
- This TODO owns concurrency and idempotency hardening for phone OTP `challenge` and `verify`.
- It owns backend semantics, tests, and evidence for duplicate-request behavior.
- It may require small Flutter/client contract notes only if backend response behavior changes.
- It does not own broader auth redesign, MFA strategy, or delivery-channel expansion.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Auth`, `Concurrency`, `Idempotency`, `Security`
- **Next exact step:** reproduce the `challenge` and `verify` race paths under real concurrent requests, freeze the invariant and policy per path, then implement atomic state transitions with regression coverage.

## References
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php`

## Review Findings Frozen From Main Promotion
- **Confirmed defect candidate 01:** concurrent successful `verify` requests can both observe `status=pending` and mint more than one valid token for the same challenge before the challenge is marked `verified`.
- **Confirmed defect candidate 02:** concurrent invalid `verify` requests can overwrite the `attempts` counter and weaken lockout thresholds.
- **Confirmed defect candidate 03:** concurrent `challenge` requests for the same phone can both supersede/create active challenges and send duplicate OTPs despite resend cooldown intent.

## Scope
- [ ] Define and freeze the canonical concurrency policy for:
  - OTP `challenge`
  - OTP `verify` success path
  - OTP `verify` invalid-code path
- [ ] Make OTP verification consume the challenge atomically before credentials are issued.
- [ ] Make invalid-attempt counting and lock transition atomic under concurrent bad-code retries.
- [ ] Make challenge issuance / resend cooldown enforcement serialize correctly per phone.
- [ ] Add deterministic regression coverage for each race path.
- [ ] Run real concurrent request probes and record invariant results.

## Out of Scope
- [ ] Replacing phone OTP as the current auth entrypoint.
- [ ] General auth UX redesign.
- [ ] Push, email, or other non-OTP auth follow-ups.

## Dependencies & Sequencing
- [ ] `DEP-01` Use the canonical concurrency validation workflow and probe helper against a safe environment.
- [ ] `DEP-02` Decide whether atomicity is enforced with conditional updates, find-and-update semantics, uniqueness constraints, or a combination.
- [ ] `DEP-03` If response semantics change, update the owning Flutter/auth contract docs before closure.

## Definition of Done
- [ ] Concurrent successful verify requests cannot mint more than one effective challenge consumption.
- [ ] Concurrent invalid verify requests cannot undercount attempts or skip the intended lock threshold.
- [ ] Concurrent challenge requests cannot bypass resend cooldown or create more than the allowed active effect for the phone.
- [ ] Regression tests prove the protected invariants.
- [ ] Residual concurrency risk is explicitly documented.

## Validation Steps
- [ ] Run targeted backend tests for OTP challenge/verify concurrency semantics.
- [ ] Run real concurrent probes with the canonical helper at multiple levels (`5`, `10`, `20`) and capture status/latency output.
- [ ] Validate domain invariants from persisted `PhoneOtpChallenge` and issued-token outcomes, not only HTTP status codes.
- [ ] Reconfirm that no cross-account or tenant-isolation issue exists after the hardening.

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Why:** the defect family is tightly scoped, but the fix needs atomic persistence semantics plus real concurrency evidence instead of ordinary feature tests.
