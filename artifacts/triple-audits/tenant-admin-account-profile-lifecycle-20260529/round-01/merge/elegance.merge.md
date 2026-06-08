# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Revise the TODO/package before renewed APROVADO. The plan is directionally sound, but it needs explicit consumer-surface coverage, a canonical cleanup path, and tighter repair/force-delete invariants before the gate should proceed.`

## Merged Findings
### F-875CD91E [high] Playwright cleanup repair is scoped as many site edits rather than a canonical cleanup path.
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Introduce or reuse one canonical Playwright cleanup helper for onboarding-created accounts, route listed specs through it, and add source-scan evidence that no onboarding cleanup calls DELETE /account_profiles/{id} directly.
- **Rationale:** D-08 expands cleanup to all current account_onboardings cleanup sites and lists many specs individually, but it does not require a shared aggregate-cleanup helper or source-level ban on profile-only cleanup for onboarding-created accounts.

### F-54CEE0F2 [high] Consumer-surface coverage is missing for changed account-profile delete semantics.
- **Reviewers:** elegance
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add an explicit consumer matrix row for account profile deletion semantics, including whether admin UI/test harness consumers are updated, already compatible, or intentionally waived with rationale.
- **Rationale:** The package plans a service-level guard for DELETE /admin/api/v1/account_profiles/{id} so direct deletion of the last active profile is rejected, but it contains no Frontend / Consumer Matrix or backend-only waiver.

### F-4263C570 [medium] forceDelete guard semantics need clearer wording to avoid overblocking or permanent data loss.
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Clarify that forceDelete handling must distinguish active-profile removal, already-soft-deleted profile purge, aggregate account deletion, and repair/restoration workflows.
- **Rationale:** The package says delete and forceDelete must be guarded, while also noting that an already soft-deleted profile must not make repair impossible.

### F-E7C86865 [medium] Repair command safety contract is not explicit enough for tenant-scoped mutation.
- **Reviewers:** elegance
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require explicit tenant scope, dry-run counts by action, execute requiring prior dry-run-compatible criteria, idempotent behavior, and durable skip/report output for ambiguous rows.
- **Rationale:** The package plans a backend-owned dry-run/execute repair command, but does not specify required tenant argument/context, idempotent execute behavior, dry-run output shape, or execute safeguards.

## Reviewer Summaries
### elegance
- **Assessment:** not_ready
- **Recommended path:** `Revise the TODO/package before renewed APROVADO. The plan is directionally sound, but it needs explicit consumer-surface coverage, a canonical cleanup path, and tighter repair/force-delete invariants before the gate should proceed.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-01 Consumer-surface coverage is missing for changed account-profile delete semantics.: The package plans a service-level guard for DELETE /admin/api/v1/account_profiles/{id} so direct deletion of the last active profile is rejected, but it contains no Frontend / Consumer Matrix or backend-only waiver.
  - [high] ELEGANCE-02 Playwright cleanup repair is scoped as many site edits rather than a canonical cleanup path.: D-08 expands cleanup to all current account_onboardings cleanup sites and lists many specs individually, but it does not require a shared aggregate-cleanup helper or source-level ban on profile-only cleanup for onboarding-created accounts.
  - [medium] ELEGANCE-03 Repair command safety contract is not explicit enough for tenant-scoped mutation.: The package plans a backend-owned dry-run/execute repair command, but does not specify required tenant argument/context, idempotent execute behavior, dry-run output shape, or execute safeguards.
  - [medium] ELEGANCE-04 forceDelete guard semantics need clearer wording to avoid overblocking or permanent data loss.: The package says delete and forceDelete must be guarded, while also noting that an already soft-deleted profile must not make repair impossible.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
