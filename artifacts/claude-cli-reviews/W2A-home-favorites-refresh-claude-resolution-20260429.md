# W2A Home Favorites Refresh Claude Review Resolution

## Source Review

- Review artifact: `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-review-20260429.md`
- Decision: Claude returned `CONDITIONAL BLOCK`.
- Blocking finding: `BLOCK-1` identified that `refreshFavoriteResumes()` was inside the same `try/catch` used for backend favorite persistence, so a post-persistence Home refresh failure could incorrectly roll back local favorite state.

## Delphi Decision

Accepted as a real gate finding. Even though the default `FavoriteRepositoryContract.refreshFavoriteResumes()` catches fetch failures, the repository boundary was too broad: only backend persistence failure should trigger rollback.

## Resolution

- `AccountProfilesRepository.toggleFavorite` now has separate error boundaries:
  - backend favorite/unfavorite persistence failure rolls back optimistic local state and stops;
  - Home favorite-resume refresh failure is logged and does not roll back a persisted favorite mutation;
  - telemetry failure is logged and does not roll back a persisted favorite mutation.
- Added test coverage:
  - `toggleFavorite keeps persisted state when Home favorite resume refresh fails`
  - Existing persistence-failure coverage remains: `toggleFavorite does not refresh Home favorite resumes when persistence fails`

## Validation

- `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart --plain-name "toggleFavorite"`: passed 2026-04-29, 4 tests.
- Focused Wave 2A suite: passed 2026-04-29, 28 tests.
- `fvm dart analyze --format machine`: passed 2026-04-29, no diagnostics.
- `bash scripts/build_web.sh ../web-app dev`: passed 2026-04-29; `web-app` output is derived and not committed.

## Remaining Claude Findings

- `DEBT-1` invite share files not declared in the Home package: non-blocking traceability note. Invite share has its own bounded audit package and TODO evidence.
- `DEBT-2` static GetIt resolution order: accepted non-blocking risk under current DI registration assumptions; not a release blocker.
- `DEBT-3` telemetry awaited before Home refresh: resolved beyond the requested minimum by moving Home refresh before telemetry and isolating telemetry failure from rollback.
