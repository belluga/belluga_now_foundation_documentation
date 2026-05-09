# PACED Subagent Review Merge: final_review

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-public-auth-password-reset-risk-final-review-dispatch-20260508T030556Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not advance RR-AUTH-04 or the auth tranche from this final-review lane until the closure gates are recorded, the public-auth abuse controls are corrected, and the remaining route-governance posture is either fixed or explicitly accepted as bounded debt.`

## Merged Findings
### F-0426E503 [high] Closure-gate evidence is still missing
- **Reviewers:** RR-AUTH-04-final-review-no-context
- **Category:** `residual_risk`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `rr-auth-04-closure-gates-must-be-recorded`
- **Suggested action:** Finish and record the remaining audit-floor lanes, triple audit, Claude comparison, and delivery guards before closure.
- **Rationale:** The package still lacked recorded critique, security, verification-debt, test-quality, triple-audit, Claude comparison, and final orchestration guard outcomes. That alone blocks any closure claim regardless of the implementation state.

### F-53AA6C69 [medium] Tenant-public password governance still depends on route middleware
- **Reviewers:** RR-AUTH-04-final-review-no-context
- **Category:** `architecture`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `tenant-public-password-governance-boundary`
- **Suggested action:** Either centralize the governance boundary or explicitly accept the route-local posture as bounded debt with a deterministic drift guard.
- **Rationale:** The slice proves effective fail-closed behavior, but the current contract still relies on route middleware composition rather than a single application-owned boundary. That leaves some structural fragility in the final governance surface.

### F-070D6ED5 [medium] Public-auth throttling still carries fail-open and IP-only residual risk
- **Reviewers:** RR-AUTH-04-final-review-no-context
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `public-auth-throttling-must-be-fail-closed-and-subject-aware`
- **Suggested action:** Correct the public-auth throttle posture first, then rerun the final-review lane against the corrected baseline.
- **Rationale:** At review time the package still described public-auth throttling in a way that could fail open on limiter backend errors and key primarily on caller IP instead of the credential subject. That keeps the abuse-control posture below closure grade.

## Reviewer Summaries
### RR-AUTH-04-final-review-no-context
- **Assessment:** RR-AUTH-04 is not closure-ready on this round. Closure-gate evidence is still missing, tenant-public password governance still depends on route middleware, and the public-auth throttling posture still carries fail-open and IP-only residual risk at review time.
- **Recommended path:** `Do not advance RR-AUTH-04 or the auth tranche from this final-review lane until the closure gates are recorded, the public-auth abuse controls are corrected, and the remaining route-governance posture is either fixed or explicitly accepted as bounded debt.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] RR-AUTH-04-FR-001 Closure-gate evidence is still missing: The package still lacked recorded critique, security, verification-debt, test-quality, triple-audit, Claude comparison, and final orchestration guard outcomes. That alone blocks any closure claim regardless of the implementation state.
  - [medium] RR-AUTH-04-FR-002 Tenant-public password governance still depends on route middleware: The slice proves effective fail-closed behavior, but the current contract still relies on route middleware composition rather than a single application-owned boundary. That leaves some structural fragility in the final governance surface.
  - [medium] RR-AUTH-04-FR-003 Public-auth throttling still carries fail-open and IP-only residual risk: At review time the package still described public-auth throttling in a way that could fail open on limiter backend errors and key primarily on caller IP instead of the credential subject. That keeps the abuse-control posture below closure grade.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

