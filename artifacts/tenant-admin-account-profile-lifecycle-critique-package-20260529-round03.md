# Pre-Approval Critique Package Round 03: Tenant-Admin Account/Profile Lifecycle Integrity

## Review Goal
Verify that the remaining round 02 approval blockers are resolved. Do not implement. Report only remaining blockers before renewed `APROVADO`.

## Related Artifacts
- TODO: `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- Round 01 resolution: `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/resolution.md`
- Round 02 summary: `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-02/round-summary.md`
- Round 02 resolution: `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-02/resolution.md`

## Baseline Facts
- Tenant-admin account creation still uses centralized transactional onboarding.
- Local corruption was caused by direct profile deletion / Playwright cleanup after onboarding-created accounts, not by partial create.
- Local tenant `guarappari` had `45` live accounts without active profiles before repair.
- No production/test code implementation or data repair is authorized yet.

## Round 02 Blockers Resolved
- `R02-BLOCKER-001`: repair aggregate deletion is now fail-closed. Linked-data/relation checks must affirmatively pass. Skipped, unsupported, ambiguous, or non-passing checks must skip/report the account.
- `TQ-R02-01`: forceDelete validation now requires two rejected mutation cases:
  - last-active-profile forceDelete attempt;
  - already-soft-deleted/restorable-profile purge attempt.
  Each must assert response contract and unchanged persisted state proving no permanent purge occurred.

## Current Approval Contract
- `D-04`: Repair policy is dry-run first. Execute requires explicit local tenant scope and confirmation. Known test-seed aggregate deletion is allowed only for live `tenant_owned` accounts in the target tenant whose slug/prefix matches an approved harness family and whose linked-data checks affirmatively pass. Non-test restore is allowed only when exactly one latest soft-deleted profile is restorable and its profile type exists. Ambiguous rows, skipped/unsupported relation checks, and any linked-data check that does not affirmatively pass are skipped/reported.
- `D-09`: Last-profile `delete()` and `forceDelete()` guard must be atomic under concurrent direct deletes, using account-keyed locking/transaction or equivalent conditional mutation.
- `D-10`: The only last-profile bypass is a named `AccountAggregateDeletionBoundary` owned by account aggregate deletion service flow. Direct profile callers cannot opt into it.
- `D-11`: Repair command is local-only by default, dry-run by default, tenant-explicit, bounded/chunked, idempotent, structured, and refuses production/stage or missing confirmation unless a separate TODO approves that environment.
- `D-12`: Account-profile delete semantic changes require a Frontend / Consumer Matrix for admin UI, Playwright harness, and CLI/operator repair surfaces before implementation.
- `D-13`: Force-delete semantics distinguish active-profile removal, already-soft-deleted profile purge, aggregate account deletion, and repair/restoration workflows. The last restorable profile for a live account cannot be permanently purged outside the aggregate boundary.
- `D-14`: Onboarding-created accounts must be cleaned up through one canonical Playwright helper using captured account identifiers or session-owned metadata, never list/page discovery or profile-only deletion.

## Repair Predicates
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

## Validation Gates
- Direct `delete()` last-profile rejection test with response contract and unchanged persisted state.
- Direct `forceDelete()` rejection tests for both last-active-profile removal and already-soft-deleted/restorable-profile purge attempts, with response-contract assertions and unchanged persisted-state assertions for each rejected mutation.
- Concurrent direct delete regression proving zero-active-profile orphaning cannot occur.
- Account aggregate delete test proving only the named boundary can bypass last-profile protection.
- Repair command tests for dry-run/execute parity, safe test-seed delete, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion.
- Playwright source scan proving onboarding-created account cleanup uses the canonical helper and no profile-only cleanup remains.
- Deterministic targeted Playwright mutation evidence: `NAV_WEB_SHARD=account-profiles bash tools/flutter/run_web_navigation_smoke.sh mutation` plus any affected direct specs not covered by that shard, or explicit blocked status.
- Post-validation backend invariant query proving no live local `guarappari` account lacks an active profile except approved residuals.

## Reviewer Question
Does any blocker remain before renewed `APROVADO`, or is the planning contract ready for approval with implementation still blocked until `todo_authority_guard.py` passes?
