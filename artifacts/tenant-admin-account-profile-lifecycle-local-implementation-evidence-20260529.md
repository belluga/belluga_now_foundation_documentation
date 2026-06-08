# Tenant Admin Account/Profile Lifecycle Evidence - 2026-05-29

## Fail-First Evidence
- Command: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest`
- RED target: `AccountProfileLifecycleIntegrityTest::test_direct_profile_delete_rejects_last_active_profile_for_live_account`
- Pre-fix result: failed as expected because direct `DELETE /admin/api/v1/account_profiles/{id}` returned `200` instead of expected `422`.
- Interpretation: the existing backend allowed profile-only cleanup to orphan a live account.

## Backend Green Evidence
- Command: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest`
- Result: PASS, 4 tests, 18 assertions.
- Covered: direct soft delete rejection, active force-delete rejection, already-soft-deleted/restorable force-delete rejection, concurrent direct delete invariant.

- Command: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountMissingProfileRepairCommandTest`
- Result: PASS, 6 tests, 53 assertions.
- Covered: dry-run/execute parity, safe restore, safe test-seed aggregate deletion, linked-data skip, missing profile type skip, no-restorable skip, execute confirmation.

- Command: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountControllerTest`
- Result: PASS, 23 tests, 85 assertions.
- Covered: unmanaged account aggregate delete/force-delete and related profile cleanup through account service boundary.

- Command: `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountOnboardingsControllerTest`
- Result: PASS, 8 tests, 43 assertions.
- Covered: onboarding transaction success and rollback paths.

## Runtime Repair Evidence
- Before execute command: `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --chunk=100`
- Before execute result: scanned 57, invalid 45, would-delete safe test seed 23, residual 22.

- Execute command: `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --execute --confirm=repair-missing-profiles:guarappari --chunk=100`
- Execute result: safe test-seed aggregates repaired through backend service path.

- After execute command: `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --chunk=100`
- After execute result: scanned 34, invalid 22, would-delete 0, residual 22.

- Post-Playwright command: `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --chunk=100`
- Post-Playwright result: scanned 34, invalid 22, would-delete 0, residual 22.
- Interpretation: Playwright reruns did not create new account-without-profile rows.

## Playwright Evidence
- Source scan: `rg -n "deleteAccountProfile\\(" tools/flutter/web_app_tests -S`
- Result: no output.

- Source scan: `rg -n "account_onboardings" tools/flutter/web_app_tests -S`
- Result: onboarding call sites reviewed; affected cleanup sites use captured account slugs and `cleanupOnboardedAccount(s)`.

- Playwright command: `NAV_WEB_SHARD=apd bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Result: PASS, 3 tests, 1.8 min.

- Playwright command: `NAV_WEB_SHARD=admin-final bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Result: PASS, 8 tests, 5.4 min.

- Playwright command: `NAV_WEB_SHARD=occurrences bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Result: PASS, 2 tests, 1.5 min.

- Playwright command: `NAV_WEB_SHARD=invite-session bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Result: PASS, 3 tests, 29.2 s.

- Playwright command: `NAV_WEB_SHARD=occurrence-fab bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Result: selected 1 expected test and failed at `navigation.mutation.event_occurrences.spec.js:3410` because the Como Chegar UI did not expose a button named `Outros`.
- Classification: non-scope UI route-provider assertion failure. The failure occurs after lifecycle-relevant setup/navigation, and post-run repair dry-run proved no new missing-profile rows.

## Static Hygiene
- Command: PHP `php -l` on touched Laravel service/controller/route/test files inside Docker.
- Result: PASS.
- Command: Node `--check` on touched Playwright/helper files.
- Result: PASS.
- Command: `git diff --check`; `git -C laravel-app diff --check`.
- Result: PASS.

## External Review
- Claude CLI artifact: `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-implementation-claude-review-20260529.md`
- Outcome: `ready_for_delivery`.
