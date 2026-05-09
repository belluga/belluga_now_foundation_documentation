# Store Release Usability Quality Hardening - Post-Resolution Rerun

## Audit Scope

Compare the current `orchestrator/store-release-usability-wave` state against `dev` across:

- Docker/root repository.
- `flutter-app`.
- `laravel-app`.

The product behavior has already been externally validated; this audit is focused on implementation quality, performance risk, security/data-safety risk, test adequacy, and maintainability after a long reconciliation wave.

## Diff Artifacts

- Docker/root status: `docker-status.txt`
- Flutter status: `flutter-status.txt`
- Laravel status: `laravel-status.txt`
- Docker/root diff: `diffs/docker-current-complete.patch`
- Flutter diff: `diffs/flutter-vs-dev.patch`
- Laravel diff: `diffs/laravel-vs-dev.patch`
- File/status summaries are in the adjacent `*.stat` and `*.files` artifacts.

## Validation Evidence

- Flutter affected unit/widget suite: `703` tests passed.
- Laravel affected suite: `298 passed (1746 assertions)`.
- Flutter analyzer: `fvm dart analyze --format machine` exited `0`.
- Web build: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev` completed and published the dev bundle to `../web-app`.
- Playwright readonly navigation: `9 passed (3.3m)`.
- Playwright mutation navigation: canonical rerun passed cleanly with `19 passed (11.4m)`.
- Focused flaky investigation: `NAV-APD-09` passed in isolation with `1 passed (26.5s)` after the previous full run reported it as flaky.
- Android/ADB integration: blocked by environment because no Android device/emulator was attached.

## Prior Audit Findings Resolved

- `PERF-POST-01`: event programming profile fanout is capped per item. The public programming UI renders at most 4 profile chips/images per row plus a `+N perfil/perfis` overflow chip, guarded by widget tests.
- `PERF-POST-02` and `ELEGANCE-001`: programming item keys no longer rely only on `item.time`; duplicate-time rows are keyed with item index and covered by tests.
- `TQ-RERUN-001`: Laravel affected suite was executed through the canonical safe runner.
- `TQ-RERUN-002`: Flutter affected unit/widget suite was executed, not only focused tests.
- `TQ-RERUN-003`: the brittle source-grep backend performance guard was replaced by an observable test that spies the batch taxonomy loader and verifies one batch call for multiple taxonomies.

## Additional Defects Found During Validation

- The orphan Map POI cleanup expectation was attached to the wrong job. The test now targets `CleanupOrphanedMapPoisJob(['event'])`; expired-event refresh remains scoped to expired active projections.
- Event deletion logging referenced `EventOccurrence` from the wrong namespace in `EventManagementService`; the model import was corrected and the delete test now passes.

## Required Auditor Focus

- Identify remaining high-risk performance paths, especially fanout/N+1 access in public detail, discovery filters, taxonomy batch loading, and map/event projection reconciliation.
- Identify any mutation path that bypasses canonical domain/application services, transactions, or aggregate ownership.
- Check that browser navigation evidence maps to visible behavior and is not only unit-level confidence.
- Check whether test code contains brittle selectors, coordinate-click workarounds, or retry-dependent behavior that can hide real regressions.
- Check if new package-first filter abstractions are cleanly bounded or leak surface-specific rules into generic packages.
- Check if rich text rendering/sanitization and large-field limits are safe against unsafe HTML, payload blowups, and user-visible formatting regressions.
