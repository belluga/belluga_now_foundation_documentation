# Pre-Approval Critique Package: Tenant-Admin Account/Profile Lifecycle Integrity

## Review Goal
Review the planned TODO before renewed `APROVADO`. Findings must lead, ordered by severity. Do not implement. Focus on correctness, performance, elegance, structural soundness, tenant/auth risk, and operational repair safety.

## TODO Under Review
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`

## Runtime Evidence
- Local tenant `guarappari` has `57` accounts total and `45` live accounts without an active profile.
- `29` missing-profile accounts have `playwright-*` prefixes; additional families include `pw-sr-d-*` and `runtime-invite-account`.
- Reported example:
  - account `playwright-cover-1779940401325` created at `2026-05-28 03:53:21`;
  - related profile created at `2026-05-28 03:53:22`;
  - related profile soft-deleted at `2026-05-28 03:53:57`;
  - account remains live as `tenant_owned`.

## Root Cause Summary
- Tenant-admin account creation still uses the centralized transactional endpoint `POST /admin/api/v1/account_onboardings`.
- The corruption is caused by destructive cleanup and permissive direct profile deletion, not by partial account/profile creation.
- Playwright mutation specs create `tenant_owned` accounts through onboarding, then cleanup by calling `DELETE /admin/api/v1/account_profiles/{id}` only.
- `AccountProfileManagementService::delete()` and `forceDelete()` currently do not guard against removing the last active profile from a live account.

## Frozen/Planned Decisions
- `D-01`: Direct Account Profile deletion must not leave a live account with zero active profiles.
- `D-02`: Account aggregate deletion remains the valid destructive lifecycle for deleting accounts and their profiles together.
- `D-03`: Backend guard must apply at service level so all controller paths and harness calls share the invariant.
- `D-04`: Approved local repair policy must be executed only through backend-owned command/service, never direct DB mutation.
- `D-05`: Affected Playwright mutation cleanup must use invariant-safe aggregate cleanup for profiles/accounts created by onboarding.
- `D-06`: Validation must prove both prevention of new corruption and repair of current local corruption.

## Pending Decisions For Approval
- `D-04`: Recommended repair policy is dry-run first; delete known test-seed account aggregates through backend service/API when safe; restore the latest soft-deleted profile only for non-test accounts that are otherwise safe; skip/report ambiguous rows.
- `D-08`: Recommended Playwright cleanup depth is all current `account_onboardings` cleanup sites under `tools/flutter/web_app_tests`, not only the reported cover test.

## Scope Boundary
Inside scope:
- backend regression tests for last-profile direct delete rejection;
- backend regression tests proving aggregate account delete remains valid;
- service-level guard for `delete` and `forceDelete`;
- backend-owned audit/repair command/service with dry-run and execute modes;
- local `guarappari` repair through that backend path only;
- Playwright cleanup replacement for onboarding-created accounts;
- source-scan and targeted mutation evidence;
- module doc update only if current lifecycle invariant is insufficient.

Outside scope:
- new tenant-admin account creation UX;
- reintroducing standalone tenant-admin profile-create remediation UI;
- direct database repair;
- production/stage repair without separate approval;
- broad lifecycle redesign unrelated to missing-profile prevention/repair.

## Planned Touched Surfaces
- `laravel-app/app/Application/AccountProfiles/AccountProfileManagementService.php`
- `laravel-app/app/Application/Accounts/AccountManagementService.php` if aggregate-delete bypass context is needed
- `laravel-app/routes/console.php` or a dedicated Laravel application service for audit/repair command
- `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php`
- `laravel-app/tests/Feature/Accounts/AccountControllerTest.php`
- `tools/flutter/web_app_tests/account_profile_detail.spec.js`
- `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`
- `tools/flutter/web_app_tests/ensure_public_taxonomy_validation_fixture.cjs`
- `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`
- module docs only if current invariant is not explicit enough

## Execution Plan Summary
1. Reconfirm current invalid local rows with a dry audit query.
2. Add failing Laravel feature test for direct deletion of the last active profile.
3. Add Laravel regression test proving account aggregate delete remains valid.
4. Implement service-level delete guard and any explicit aggregate-delete bypass context.
5. Add audit/repair service/command with dry-run and execute modes.
6. Update Playwright cleanup helpers to cleanup onboarding-created accounts via aggregate lifecycle.
7. Run focused Laravel tests.
8. Run Playwright cleanup source scan and targeted mutation shard where feasible.
9. Run repair dry-run, execute local repair, then after dry-run proving zero invalid rows or explicit residuals.
10. Fill delivery evidence and required assurance gates before closeout.

## Material Issue Cards
- `ARCH-01` high: direct profile delete can corrupt live tenant-admin account state. Recommended resolution is backend service guard plus aggregate-owned destructive lifecycle.
- `DATA-01` high: local `guarappari` has `45` live accounts without active profiles. Recommended resolution is backend-owned dry-run/execute repair command with safe policy, not ad hoc DB mutation.

## Assumptions
- `A-01`: corruption was caused by profile delete after transactional onboarding, not partial create. Confidence high.
- `A-02`: blocking last-profile direct delete will not break valid aggregate account delete if account delete has an explicit service boundary. Confidence medium.
- `A-03`: test-seed missing-profile accounts may be safely deleted from local runtime when prefix and ownership evidence match known harness families. Confidence medium.
- `A-04`: backend command/service can restore/delete without direct DB mutation. Confidence high.

## Edge Cases To Pressure-Test
- Account has multiple active profiles: direct delete may be allowed only if at least one active profile remains.
- Account itself is being deleted: aggregate lifecycle must not be blocked by the profile guard.
- Profile is already soft-deleted: force-delete policy must not make repair impossible.
- Profile type used by a soft-deleted profile was also deleted by test cleanup: repair command must skip/report.
- Test-seed account has public data or linked events: aggregate deletion must cascade safely or skip/report.
- Non-test missing-profile account has multiple soft-deleted profiles: repair must skip/report instead of guessing.

## Required Critique Output
Return:
- Findings first, ordered by severity.
- For each finding: evidence, risk, recommended change to TODO/plan, and whether it blocks approval.
- A short approval-readiness verdict: `ready_for_aprovado`, `ready_with_changes`, or `not_ready`.
- No implementation.
