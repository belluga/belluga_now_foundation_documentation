# Store Release Wave 2A Home Favorites Refresh Audit Package

## Package Metadata

- **Package type:** bounded independent triple-audit package
- **Created:** 2026-04-29
- **Governing TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-home-favorites-refresh-regression.md`
- **Implementation repo:** `flutter-app`
- **Implementation branch:** `orchestration/store-release-wave2-social-consumer-gaps-20260429`
- **Docs branch:** `docs/store-release-wave2-social-consumer-gaps-20260429`
- **Scope:** Flutter Home Favorites refresh after app-side favorite/unfavorite mutation
- **Zero-backward rule:** favorites are first-production release behavior. Do not request compatibility with pre-release favorite stream/cache/API behavior unless the finding identifies an independent launch risk such as security, data loss, tenant isolation, integrity, or release regression.
- **Device policy:** ADB/device smoke is intentionally deferred to the consolidated Wave 2D phase.

## Audit Objective

Determine whether the local fix correctly closes the user QA regression where favoriting in the app does not refresh the Home Favorites section, without violating repository-owned state boundaries or creating avoidable performance/test-quality debt.

## Changed Source Files

- `lib/infrastructure/repositories/account_profiles_repository.dart`
- `test/infrastructure/repositories/account_profiles_repository_test.dart`

## Related Existing Consumer Tests Included In Verification

- `test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart`
- `test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart`

## Implementation Summary

- `AccountProfilesRepository` now accepts or resolves an optional `FavoriteRepositoryContract`.
- After a successful `toggleFavorite` backend persistence path and telemetry record, `AccountProfilesRepository` calls `refreshFavoriteResumes()` on the canonical favorites repository.
- Home Favorites already consumes `FavoriteRepositoryContract.favoriteResumesStreamValue`; the mutation path now invalidates/refreshes that source instead of patching Home UI locally.
- No Home widget cache, sibling-controller relay, route restart, local duplicate source-of-truth, or forced UI reload was introduced.
- Round 01 test-quality feedback strengthened the regression fake: favorite-resume refreshes now read from the same fake favorite backend mutated by `favoriteAccountProfile` / `unfavoriteAccountProfile`, with operation-order assertions and failed-persistence no-refresh coverage.
- Claude CLI review then identified a valid rollback-boundary issue: favorite-resume refresh failure was inside the same `try/catch` as backend persistence. The implementation now separates persistence rollback from post-persistence Home refresh and telemetry failures.

## Fail-First Evidence

- New test: `toggleFavorite refreshes canonical favorite resumes consumed by Home after mutations`.
- RED result after fixture correction: the test failed with `Expected: <1> Actual: <0>` for `fetchFavoriteResumesCallCount`, proving the mutation path did not refresh the canonical Home-consumed favorite stream.
- GREEN result after implementation: same test passed and covers both favorite and unfavorite transitions.
- Round 01 resolution added a failed-persistence test: if favorite persistence fails, the optimistic local favorite rolls back and no canonical Home favorite refresh is emitted.
- Claude CLI resolution added a refresh-failure test: if Home favorite-resume refresh throws after backend persistence succeeds, the canonical favorite id remains locally selected and the backend mutation is not rolled back.

## Validation Evidence

| Lane | Evidence | Result |
| --- | --- | --- |
| Focused favorite regression | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart --plain-name "toggleFavorite"` | Passed 2026-04-29: favorite/unfavorite refresh order, persistence-failure no-refresh, and refresh-failure no-rollback coverage |
| Focused Wave 2A suite | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | Passed 2026-04-29: 28 tests |
| Analyzer | `fvm dart analyze --format machine` | Passed 2026-04-29, no diagnostics after Claude rollback-boundary fix |
| Diff hygiene | `git diff --check` | Passed 2026-04-29 |
| Web build | `bash scripts/build_web.sh ../web-app dev` | Passed 2026-04-29 after Claude rollback-boundary fix; `web-app` is derived output and not committed |
| Source-owned Playwright/browser lane | Repository scan found no source-owned Playwright runner under `flutter-app` (`tools/` absent; no `web_app_tests` or navigation smoke script). | Not applicable / unavailable |

## Frontend / Consumer Matrix

| Producer / Contract Surface | Consumer Surface | Evidence | Status |
| --- | --- | --- | --- |
| Favorite mutation invalidates canonical favorite-resume stream | Home Favorites section consumes `FavoriteRepositoryContract.favoriteResumesStreamValue` | New repository regression test plus existing Home Favorites controller/widget tests in focused suite | Implemented and locally passed |
| Backend/API producer surface | n/a | This package does not add or change backend endpoints, payloads, schemas, settings namespaces, webhooks, or jobs. | Not triggered |
| Admin/operator/web-app producer surface | n/a | This package changes Flutter app source only; `web-app` build output is derived and not committed. | Not triggered |

## Known Deferred Evidence

- Final ADB/manual proof remains queued for Wave 2D: favorite in app, return to Home, verify item appears/updates without restart; unfavorite and verify removal/update.
- Claude CLI auxiliary review is separate from this package. Per user instruction, it is a gate only when available and returning substantive findings.

## Prior Round Resolution Context

- Round 01 summary: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-01/round-summary.md`
- Round 01 resolution: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-01/resolution.md`
- Round 02 summary: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-02/round-summary.md`
- Round 02 resolution: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-02/resolution.md`
- Round 03 summary: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-03/round-summary.md`
- Round 03 resolution: `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-03/resolution.md`
- Claude CLI review: `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-review-20260429.md`
- Claude CLI resolution: `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-resolution-20260429.md`
- Claude CLI final re-review: `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-final-20260429.md`
- `TQA-01` was resolved by strengthening the test fake and adding failure coverage.
- Claude `BLOCK-1` was resolved by separating persistence rollback from Home refresh and telemetry error handling.
- `ELEGANCE-LOW-001` is accepted non-blocking debt for future favorite-domain normalization if more mutation surfaces appear.
- `TQA-02` is accepted non-blocking operational debt for this local implementation audit; CI evidence remains required before production-ready promotion.

## Reviewer Instructions

- Evaluate only this bounded package and the changed files listed above.
- Classify findings using the triple-audit gate: `blocking`, `accepted-debt`, or `out-of-scope`.
- Do not request backward compatibility for pre-release favorite behavior.
- Treat ADB/device smoke absence as deferred by orchestration, not by itself a blocker, unless the automated evidence cannot prove the targeted non-device behavior.
