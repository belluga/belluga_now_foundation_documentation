# Pre-Approval Critique Package Round 02: Tenant-Admin Account/Profile Lifecycle Integrity

## Review Goal
Review whether round 01 audit blockers were resolved in the TODO contract before renewed `APROVADO`. Findings must lead, ordered by severity. Do not implement. Focus on remaining approval blockers only.

## Related Artifacts
- TODO: `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- Round 01 summary: `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/round-summary.md`
- Round 01 resolution: `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/resolution.md`
- Pre-approval critique merge: `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-merge-20260529.md`

## Baseline Facts
- Tenant-admin account creation still uses centralized transactional onboarding.
- Local corruption was caused by direct profile deletion / Playwright cleanup after onboarding-created accounts, not by partial create.
- Local tenant `guarappari` had `45` live accounts without active profiles before repair.
- No production/test code implementation or data repair is authorized yet.

## Round 01 Audit Outcome
Round 01 returned `not_ready` across pre-approval critique, elegance, performance, and test-quality lanes. Delphi adjudicated the recommendations as additive:
- require atomic last-profile protection under concurrent direct deletes;
- define exact safe repair predicates and fail-closed command semantics;
- name the aggregate account deletion bypass boundary;
- add consumer-surface coverage;
- centralize Playwright cleanup through one canonical helper;
- require forceDelete, repair branch, real-backend, and CI-equivalent validation gates.

## Hardened Decisions For Approval
- `D-04`: Repair policy is dry-run first. Execute requires explicit local tenant scope and confirmation. Known test-seed aggregate deletion is allowed only for live `tenant_owned` accounts in the target tenant whose slug/prefix matches an approved harness family and whose linked-data checks affirmatively pass. Non-test restore is allowed only when exactly one latest soft-deleted profile is restorable and its profile type exists. Ambiguous rows, skipped/unsupported relation checks, and any linked-data check that does not affirmatively pass are skipped/reported.
- `D-09`: Last-profile `delete()` and `forceDelete()` guard must be atomic under concurrent direct deletes, using account-keyed locking/transaction or equivalent conditional mutation.
- `D-10`: The only last-profile bypass is a named `AccountAggregateDeletionBoundary` owned by account aggregate deletion service flow. Direct profile callers cannot opt into it.
- `D-11`: Repair command is local-only by default, dry-run by default, tenant-explicit, bounded/chunked, idempotent, structured, and refuses production/stage or missing confirmation unless a separate TODO approves that environment.
- `D-12`: Account-profile delete semantic changes require a Frontend / Consumer Matrix for admin UI, Playwright harness, and CLI/operator repair surfaces before implementation.
- `D-13`: Force-delete semantics distinguish active-profile removal, already-soft-deleted profile purge, aggregate account deletion, and repair/restoration workflows. The last restorable profile for a live account cannot be permanently purged outside the aggregate boundary.
- `D-14`: Onboarding-created accounts must be cleaned up through one canonical Playwright helper using captured account identifiers or session-owned metadata, never list/page discovery or profile-only deletion.

## Hardened Repair Predicates
Execute-mode repair must:
- require explicit tenant argument and local environment;
- default to dry-run;
- require explicit execute confirmation;
- process bounded/chunked indexed account/profile queries;
- be idempotent;
- report tenant, account id/slug, profile ids, action, policy branch, skipped reason, dry-run/executed mode, and residual counts.

Safe test-seed aggregate delete is allowed only when:
- account is live and `tenant_owned`;
- tenant matches the explicit tenant argument;
- slug/prefix is one of the approved harness families: `playwright-*`, `pw-sr-d-*`, `runtime-invite-account`;
- linked-data and relation checks affirmatively prove no non-test business/public data risk.

Safe non-test restore is allowed only when:
- exactly one soft-deleted profile exists for the live account;
- its profile type exists and is active;
- no active profile appeared after account-keyed locking;
- restore is reported with profile id and policy branch.

Skip/report is required for:
- foreign tenant;
- deleted account;
- existing active profile after lock;
- multiple soft-deleted profiles;
- missing/deleted profile type;
- linked-data ambiguity;
- unsupported relation checks;
- skipped relation checks;
- any linked-data check that does not affirmatively pass;
- non-approved test prefix or unknown source.

## Hardened Test/Validation Gates
- Direct `delete()` last-profile rejection test with response contract and unchanged persisted state.
- Direct `forceDelete()` rejection tests for both last-active-profile removal and already-soft-deleted/restorable-profile purge attempts, with response-contract assertions and unchanged persisted-state assertions for each rejected mutation.
- Concurrent direct delete regression proving zero-active-profile orphaning cannot occur.
- Account aggregate delete test proving only the named boundary can bypass last-profile protection.
- Repair command tests for dry-run/execute parity, safe test-seed delete, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion.
- Playwright source scan proving onboarding-created account cleanup uses the canonical helper and no profile-only cleanup remains.
- Deterministic targeted Playwright mutation evidence: `NAV_WEB_SHARD=account-profiles bash tools/flutter/run_web_navigation_smoke.sh mutation` plus any affected direct specs not covered by that shard, or explicit blocked status.
- Post-validation backend invariant query proving no live local `guarappari` account lacks an active profile except approved residuals.

## Frontend / Consumer Matrix Summary
- Admin UI must either not expose profile-only deletion for the last profile or surface backend rejection without offering standalone profile-create remediation.
- Playwright harness must use the canonical cleanup helper and account aggregate lifecycle.
- Repair command is CLI/operator-only and local-only by default; no frontend consumer is expected.

## Reviewer Question
Given the hardened TODO/package and round 01 resolution, are there any remaining blockers before renewed `APROVADO`, or is the contract ready for approval with implementation still blocked until `todo_authority_guard.py` passes?
