# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-security-dispatch-20260508T030556Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `unknown`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the fail-open limiter behavior and subject-scoped throttling gap before closure, then either implement or explicitly accept the remaining broader policy debts with rationale and reopen triggers.`

## Merged Findings
### F-7BD32840 [high] Public auth rate limiting fails open when the limiter backend errors
- **Reviewers:** RR-AUTH-04-security-no-context
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `public-auth-rate-limiter-fail-closed`
- **Suggested action:** Make in-scope public-auth routes fail closed on limiter backend failure and add live route coverage that proves the 503-style closed posture.
- **Rationale:** At review time the package did not prove fail-closed behavior when the rate-limiter backend was unavailable. That means an infrastructure-side limiter fault could silently remove the primary abuse-control boundary from public auth and password-reset endpoints.

### F-C50D4CFE [high] Failed-attempt throttling is still IP-centric instead of subject scoped
- **Reviewers:** RR-AUTH-04-security-no-context
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `public-auth-subject-scoped-throttling`
- **Suggested action:** Add subject-aware throttling for public password/reset surfaces so the effective limit follows the credential subject rather than only the caller IP.
- **Rationale:** The bounded package still relied on IP-oriented throttling for public password flows. That leaves the abuse ceiling vulnerable to alias rotation, shared-network false positives, and weak subject-level protection for the actual credential target.

### F-0CEAD641 [medium] Timing discrepancies still exist across login and reset issuance paths
- **Reviewers:** RR-AUTH-04-security-no-context
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `public-auth-timing-uniformity`
- **Suggested action:** Record timing-uniformity as explicit residual debt or add bounded work-factor/response-uniformity coverage before treating the public-auth abuse surface as fully closed.
- **Rationale:** The current package did not prove a sufficiently uniform timing contract across login and reset issuance outcomes. That leaves residual oracle surface for subject-existence and credential-state inference even though telemetry and user-facing payloads were normalized.

### F-881D3994 [medium] Password establishment and reset still lack breached or common-password blocking
- **Reviewers:** RR-AUTH-04-security-no-context
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `public-password-blocklist-policy`
- **Suggested action:** Either introduce a bounded blocklist policy for password creation and reset or record it as explicit non-blocking follow-up debt outside RR-AUTH-04 closure logic.
- **Rationale:** The bounded RR-AUTH-04 slice hardened token lifecycle and abuse controls, but it did not add a breached-password or common-password rejection policy at password creation and reset time. That leaves a known password-quality control outside the current safety baseline.

## Reviewer Summaries
### RR-AUTH-04-security-no-context
- **Assessment:** The bounded RR-AUTH-04 package was not security-closure-grade on this round. Two high-risk abuse-control findings remained open in the current baseline at review time, and two broader password-policy/timing debts were still unaddressed.
- **Recommended path:** `Resolve the fail-open limiter behavior and subject-scoped throttling gap before closure, then either implement or explicitly accept the remaining broader policy debts with rationale and reopen triggers.`
- **Performance:** `unknown`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] SEC-RRAUTH04-001 Public auth rate limiting fails open when the limiter backend errors: At review time the package did not prove fail-closed behavior when the rate-limiter backend was unavailable. That means an infrastructure-side limiter fault could silently remove the primary abuse-control boundary from public auth and password-reset endpoints.
  - [high] SEC-RRAUTH04-002 Failed-attempt throttling is still IP-centric instead of subject scoped: The bounded package still relied on IP-oriented throttling for public password flows. That leaves the abuse ceiling vulnerable to alias rotation, shared-network false positives, and weak subject-level protection for the actual credential target.
  - [medium] SEC-RRAUTH04-003 Password establishment and reset still lack breached or common-password blocking: The bounded RR-AUTH-04 slice hardened token lifecycle and abuse controls, but it did not add a breached-password or common-password rejection policy at password creation and reset time. That leaves a known password-quality control outside the current safety baseline.
  - [medium] SEC-RRAUTH04-004 Timing discrepancies still exist across login and reset issuance paths: The current package did not prove a sufficiently uniform timing contract across login and reset issuance outcomes. That leaves residual oracle surface for subject-existence and credential-state inference even though telemetry and user-facing payloads were normalized.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

