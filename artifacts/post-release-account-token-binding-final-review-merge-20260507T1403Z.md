# PACED Subagent Review Merge: final_review

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-account-token-binding-final-review-dispatch-20260507T1403Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Block RR-AUTH-03 closure. First require all authenticated account-prefixed routes that depend on `Account::current()` to pass through the full `account` middleware group or an equivalent fail-closed token-binding guard, add regression coverage for the currently bypassing push message data/action routes, then rerun the audit escalation guard and downstream required audit gates before TODO completion or archive.`

## Merged Findings
### F-F256800E [high] Authenticated account-prefixed push routes bypass the token account-binding guard
- **Reviewers:** rr-auth-03-final-review
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move those push message data/action routes onto the full `account` middleware group or add an equivalent fail-closed account-token binding middleware, then add feature tests proving missing and wrong-account bearer tokens are rejected on those endpoints.
- **Rationale:** `routes/api/packages/project_account_api_v1/push_handler.php` defines `GET /push/messages/{push_message_id}/data` and `POST /push/messages/{push_message_id}/actions` under `auth:sanctum` but applies only `InitializeAccount::class`, not the `account` middleware group. Because the new binding check lives in `CheckUserAccess`, these routes can establish `Account::current()` without rejecting missing or mismatched bearer-token `account_id`, and they also do not activate the `ACCOUNT_SCOPED_AUTH_CONTEXT_KEY` that makes `AccountUser::tokenCan()` revalidate live current-account permissions. This contradicts the replacement rule that account-scoped routes reject missing/mismatched token account context before copied abilities can authorize across accounts.

### F-7298E535 [medium] Final-review closure authority is procedurally premature because the audit escalation guard remains pending
- **Reviewers:** rr-auth-03-final-review
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Rerun and record the audit escalation guard output before treating any final-review or triple-audit result as authoritative for TODO closure.
- **Rationale:** The governing TODO records `guard_status: pending`, `guard_outcome: not_run`, and explicitly says not to trust critique, final-review, test-quality, triple-review, or delivery-side audit decisions until `audit_escalation_guard.py` is rerun against the populated matrix and the derived floor is recorded. That means this final review can provide findings, but it cannot yet satisfy the TODO-local audit floor for closure.

## Reviewer Summaries
### rr-auth-03-final-review
- **Assessment:** Closure should not proceed. The core hybrid token-binding implementation is directionally sound for routes that use the `account` middleware, but a referenced account-prefixed route package still exposes authenticated account-context routes that only run `InitializeAccount`, bypassing `CheckUserAccess` and therefore bypassing the new token `account_id` binding and live permission revalidation guard. The audit-floor metadata is also still recorded as pending, so this review should be treated as advisory until the deterministic audit escalation guard is rerun and recorded.
- **Recommended path:** `Block RR-AUTH-03 closure. First require all authenticated account-prefixed routes that depend on `Account::current()` to pass through the full `account` middleware group or an equivalent fail-closed token-binding guard, add regression coverage for the currently bypassing push message data/action routes, then rerun the audit escalation guard and downstream required audit gates before TODO completion or archive.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] RR-AUTH-03-FR-001 Authenticated account-prefixed push routes bypass the token account-binding guard: `routes/api/packages/project_account_api_v1/push_handler.php` defines `GET /push/messages/{push_message_id}/data` and `POST /push/messages/{push_message_id}/actions` under `auth:sanctum` but applies only `InitializeAccount::class`, not the `account` middleware group. Because the new binding check lives in `CheckUserAccess`, these routes can establish `Account::current()` without rejecting missing or mismatched bearer-token `account_id`, and they also do not activate the `ACCOUNT_SCOPED_AUTH_CONTEXT_KEY` that makes `AccountUser::tokenCan()` revalidate live current-account permissions. This contradicts the replacement rule that account-scoped routes reject missing/mismatched token account context before copied abilities can authorize across accounts.
  - [medium] RR-AUTH-03-FR-002 Final-review closure authority is procedurally premature because the audit escalation guard remains pending: The governing TODO records `guard_status: pending`, `guard_outcome: not_run`, and explicitly says not to trust critique, final-review, test-quality, triple-review, or delivery-side audit decisions until `audit_escalation_guard.py` is rerun against the populated matrix and the derived floor is recorded. That means this final review can provide findings, but it cannot yet satisfy the TODO-local audit floor for closure.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

