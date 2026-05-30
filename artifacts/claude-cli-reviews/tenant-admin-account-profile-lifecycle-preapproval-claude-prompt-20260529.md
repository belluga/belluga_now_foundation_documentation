# Claude CLI Review Prompt: Tenant-Admin Account/Profile Lifecycle Integrity

You are an independent reviewer with no prior context. Do not edit files. Do not run commands. Read only the files listed below, using Read/Grep if needed.

## Goal
Review the current pre-approval TODO contract and audit-loop evidence for remaining blockers before renewed `APROVADO`.

## Files To Read
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-package-20260529-round03.md`
- `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-merge-20260529-round03.md`
- `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/resolution.md`
- `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-02/resolution.md`
- `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/resolution.md`
- `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/round-summary.md`

## Review Lenses
- Data integrity and tenant/account/profile lifecycle correctness.
- Transaction/atomicity requirements.
- Repair command safety and fail-closed predicates.
- Force-delete and soft-delete test adequacy.
- Playwright cleanup contract adequacy.
- Consumer-surface coverage.
- Whether the audit-loop clean outcome is defensible.

## Output Format
Return Markdown only:

1. `## Findings`
   - Findings first, ordered by severity.
   - If no findings, say `No blocking findings.`
   - For each finding include severity, evidence path, risk, and recommended contract change.
2. `## Approval Readiness`
   - One of: `ready_for_aprovado`, `ready_with_nonblocking_notes`, `not_ready`.
3. `## Notes`
   - Short residual-risk notes, if any.

Do not implement. Do not suggest broad redesign outside the TODO boundary.
