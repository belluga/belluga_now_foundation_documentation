# Touched Branch Suite Reference

Generated at (UTC): <YYYY-MM-DDTHH:MM:SSZ>
Branch (flutter-app): `<branch-name>`
Branch (laravel-app): `<branch-name-or-n/a>`

## Laravel - Targeted Suites

Runner:
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh <tests...>`

Files:
- `<tests/Feature/...Test.php>`

## Flutter - Unit/Widget (Touched Scope)

Runner:
- `fvm flutter test <tests...>`

Files:
- `<test/..._test.dart>`

## Flutter - Integration (Touched Scope)

Runner (WSL/device):
- `FLUTTER_INTEGRATION_USE_DDS=true INTEGRATION_DEFINE_FILE=<define-file> bash ./tool/run_integration_test_wsl.sh <integration_test_file>`

Files:
- `<integration_test/..._test.dart>`

## Supporting Runtime Defines Used in This Run

Define file used:
- `<absolute-or-repo-path-to-define-file>`

Expected keys:
- `LANDLORD_DOMAIN`
- `E2E_EXPECTED_TENANT_MAIN_DOMAIN`
- `E2E_EXPECTED_ENV_TYPE`
- `E2E_REQUIRE_TENANT_MAIN_DOMAIN`

## Progress Tracker Link

- `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md`

## Notes

- Keep this file aligned with the exact tests executed in the run.
- For partial runs, list only the targeted files and mark that in the section title (for example: `Touched Scope`).
- Always add a `Suite reference` line near the top of `test-run-progress.md` pointing to this file.
