# Title
Post-Release Hardening: Phone OTP Concurrency and Idempotency

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Created on:** `2026-05-04`
- **Split on:** `2026-05-06`
- **Source trigger:** promotion-lane PR review on `stage -> main`
- **Why this exists:** main-lane review surfaced real concurrency/idempotency weaknesses in the phone OTP flow even though the delivered user-facing path is functionally green.
- **Release-gate status:** not a blocker for the current promotion unless a deeper probe demonstrates cross-account issuance, tenant-isolation failure, or broader auth-compromise behavior.

## Context
The delivered phone OTP flow is functionally green and already promoted through the Android store-release lane, but the main-promotion review surfaced three concurrency-sensitive weaknesses inside `TenantPhoneOtpAuthService`:

- successful OTP verification is not consumed with an atomic `pending -> verified` transition before token issuance,
- invalid OTP attempt counting is a read-modify-write path that can undercount concurrent failures and delay lockout,
- challenge replacement / resend cooldown enforcement can race under parallel challenge requests for the same phone.

These are real auth hardening gaps. The current evidence does not show tenant crossover or wrong-user token issuance; the risk is duplicate side effects and weakened brute-force / cooldown semantics under burst concurrency. That puts this work in immediate post-release hardening rather than the current release blocker lane.

The Google Play reviewer-access slice that originally shared this TODO has already been delivered and moved to completed audit history. This active TODO now owns only the remaining concurrency/idempotency hardening work.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-phone-otp-concurrency-hardening`
- **Why this is the right current slice:** the remaining defect family is tightly scoped to one auth contract and one backend service boundary.
- **Direct-to-TODO rationale:** safe. The issue set is concrete and came from review evidence, not open product discovery.

## Contract Boundary
- This TODO owns concurrency and idempotency hardening for phone OTP `challenge` and `verify`.
- It owns the backend semantics, tests, and evidence for duplicate-request behavior.
- It may require small Flutter/client contract notes only if backend auth response behavior changes outside the current API contract.
- It does not own reviewer-access UX/settings, broader auth redesign, MFA strategy, or delivery-channel expansion.

## Drift Guardrail Requirement
- This TODO belongs to the broader auth/identity drift family even though its execution slice is narrow.
- Before remediation is treated as complete, execution must freeze:
  - the violated concurrency/idempotency rule,
  - the replacement invariant rule,
  - and the strongest objective PACED guardrail available so equivalent auth race-condition drift does not silently recur.
- The currently observed race windows (`challenge` and `verify`) must become the validation fixtures for that guardrail so the rule is proven against the real failure shape that triggered this TODO.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Auth`, `Concurrency`, `Idempotency`, `Security`
- **Next exact step:** reproduce the `challenge` and `verify` race paths under real concurrent requests, freeze the invariant per path, then implement the atomicity/serialization changes behind fail-first coverage.

## References
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/completed/TODO-post-release-phone-otp-play-review-access.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-post-release-docker-rollback-runtime-web-fidelity.md`
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
- [ ] Google Play reviewer-access design, settings, or UI; that slice is already delivered and retained in completed audit history.
- [ ] Replacing phone OTP as the current auth entrypoint.
- [ ] General auth UX redesign.
- [ ] Push, email, or other non-OTP auth follow-ups.

## Dependencies & Sequencing
- [ ] `DEP-01` Use the canonical concurrency validation workflow and probe helper against a safe environment.
- [ ] `DEP-02` Decide whether atomicity is enforced with conditional updates, find-and-update semantics, uniqueness constraints, or a combination.
- [ ] `DEP-03` If response semantics change, update the owning Flutter/auth contract docs before closure.

## Decision Baseline
- [x] `D-01` OTP verification must become atomic before any effective credential issuance or session creation.
- [x] `D-02` Invalid-attempt counting and lock thresholds must be race-safe.
- [x] `D-03` Challenge issuance and resend cooldown must serialize per phone identity.
- [x] `D-04` The reviewer-access slice was split out as delivered audit history and is no longer owned by this active TODO.

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

## Local CI-Equivalent Suite Matrix
This TODO is not ready for `Local-Implemented`, promotion-lane movement, or any promotable claim until every in-scope row below has been executed locally and passed on the final execution state. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / Laravel CI` | OTP concurrency/idempotency is a backend-owned runtime contract. | `bash -lc 'cd laravel-app && composer run architecture:guardrails && APP_ENV=testing APP_URL=http://nginx APP_HOST=nginx APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= APP_FAKER_LOCALE=pt_BR DB_CONNECTION_LANDLORD=landlord DB_CONNECTION_TENANTS=tenant DB_URI=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_LANDLORD=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_TENANTS=mongodb://localhost:27017/tenants_test?replicaSet=rs0&directConnection=true DB_DATABASE=landlord_test DB_DATABASE_LANDLORD=landlord_test DB_DATABASE_TENANTS=tenants_test php artisan test --fail-on-warning --display-warnings'` | `Local-Implemented` | `planned` | `laravel-app/.github/workflows/ci.yml` mirrored locally | Must mirror the repo-owned CI workflow, not only targeted feature tests. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Why:** the defect family is tightly scoped, but the fix needs atomic persistence semantics, real concurrency evidence, and invariant proof under burst request behavior.
