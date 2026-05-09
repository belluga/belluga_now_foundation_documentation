# Store Release Wave 2A Invite Share Regression Audit Package

## Package Metadata

- **Package type:** bounded independent triple-audit package
- **Created:** 2026-04-29
- **Governing TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- **Implementation repo:** `flutter-app`
- **Implementation branch:** `orchestration/store-release-wave2-social-consumer-gaps-20260429`
- **Docs branch:** `docs/store-release-wave2-social-consumer-gaps-20260429`
- **Scope:** Flutter `/convites/compartilhar` consumer gap: sharing CTA stuck at `Gerando...` and missing explicit friends/inviteables refresh action
- **Zero-backward rule:** invites, favorites, friends, contact groups, and contact-match inviteable behavior are first-production release capabilities. Do not request compatibility with pre-release invite/friend/favorite data shapes or UI contracts unless the finding identifies an independent launch risk such as security, data loss, tenant isolation, integrity, or release regression.
- **Device policy:** ADB/device contact-permission and share smoke are intentionally deferred to the consolidated Wave 2D phase.

## Audit Objective

Determine whether the local Flutter consumer fix correctly bounds share-code loading state, exposes a user-visible friends/inviteables refresh action, and preserves controller-owned state and race safety on `/convites/compartilhar`.

## Changed Source Files

- `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart`
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_footer.dart`
- `test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart`
- `test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`

## Co-Resident Branch Changes Outside This Package

- This package intentionally audits only the `/convites/compartilhar` reopened consumer gap.
- The same implementation branch also contains Home Favorites changes in:
  - `lib/infrastructure/repositories/account_profiles_repository.dart`
  - `test/infrastructure/repositories/account_profiles_repository_test.dart`
- Those files are governed and audited separately by `foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/package.md`.
- Claude CLI noted this as a non-blocking scope-declaration gap; this section is the explicit traceability correction.

## Implementation Summary

- `InviteShareScreenController` now owns two explicit loading streams:
  - `isShareCodeLoadingStreamValue`
  - `isInviteablesRefreshingStreamValue`
- Share-code generation is guarded by `_isShareCodeLoading`; failures clear loading state and leave `shareCodeStreamValue` null so UI can show retry.
- `reloadShareCode()` provides a controller-owned retry path.
- Friends/inviteables refresh is exposed via `refreshFriends()` with `_isInviteablesRefreshing` duplicate-refresh guard.
- `/convites/compartilhar` now renders a visible `Atualizar lista de amigos` action and disables it while refresh is in flight.
- `InviteShareFooter` now distinguishes three CTA states:
  - `Gerando...` only while share-code generation is in flight;
  - `Tentar novamente` when generation failed or no URI exists after loading;
  - `Compartilhar` when a share URI is available.
- Widgets render controller-owned state and trigger controller intents only; no widget-local source-of-truth was introduced.

## Fail-First Evidence

- Controller RED compile gap before implementation: tests referenced missing `isInviteablesRefreshingStreamValue`, `isShareCodeLoadingStreamValue`, and `reloadShareCode`.
- After implementation, controller and widget tests prove the behavior that was previously unavailable:
  - refresh is user-triggered and reloads backend-computed inviteables;
  - duplicate refresh is dropped while one refresh is in flight;
  - share-code failure clears `Gerando...`;
  - retry transitions from `Tentar novamente` to `Compartilhar`.

## Validation Evidence

| Lane | Evidence | Result |
| --- | --- | --- |
| Invite controller refresh/race/loading tests | `fvm flutter test test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart` | Passed in focused Wave 2A suite 2026-04-29 |
| Invite widget refresh/retry tests | `fvm flutter test test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | Passed in focused Wave 2A suite 2026-04-29 |
| Focused Wave 2A suite | `fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | Passed 2026-04-29: 26 tests |
| Analyzer | `fvm dart analyze --format machine` | Passed 2026-04-29, no diagnostics |
| Diff hygiene | `git diff --check` | Passed 2026-04-29 |
| Web build | `bash scripts/build_web.sh ../web-app dev` | Passed 2026-04-29; `web-app` is derived output and not committed |
| Source-owned Playwright/browser lane | Repository scan found no source-owned Playwright runner under `flutter-app` (`tools/` absent; no `web_app_tests` or navigation smoke script). | Not applicable / unavailable |

## Frontend / Consumer Matrix

| Producer / Contract Surface | Consumer Surface | Evidence | Status |
| --- | --- | --- | --- |
| Invite share-code generation result | `/convites/compartilhar` footer CTA | Controller and widget tests prove failed generation clears `Gerando...`, exposes retry, and successful retry reaches `Compartilhar`. | Implemented and locally passed |
| Inviteables/friends repository fetch | `/convites/compartilhar` list and refresh action | Controller and widget tests prove `Atualizar lista de amigos` refetches inviteables, updates visible list, and guards duplicate refresh. | Implemented and locally passed |
| Backend/API producer surface | n/a | This package does not add or change backend endpoints, payloads, schemas, settings namespaces, webhooks, or jobs. It only consumes existing invite repository contracts. | Not triggered |
| Admin/operator/web-app producer surface | n/a | This package changes Flutter app source only; `web-app` build output is derived and not committed. | Not triggered |

## Known Deferred Evidence

- Final ADB/manual proof remains queued for Wave 2D: open `/convites/compartilhar`, verify share CTA leaves `Gerando...` after success/error/retry, tap `Atualizar lista de amigos`, and verify the visible list updates.
- Native share-sheet behavior remains final-device evidence because it depends on platform integration.
- Claude CLI auxiliary review is separate from this package. Per user instruction, it is a gate only when available and returning substantive findings.

## Independent Review Outcomes

- Triple audit Round 01: zero findings across elegance, performance, and test-quality lanes; non-material recommended-path conflict adjudicated resolved in `foundation_documentation/artifacts/store-release-wave2-invite-share-regression-audit-20260429/triple-audit/round-01/resolution.md`.
- Claude CLI review: `foundation_documentation/artifacts/claude-cli-reviews/W2A-invite-share-regression-claude-review-20260429.md`; no blocking findings.
- Accepted non-blocking notes from Claude:
  - co-resident Home files are governed by the separate Home package;
  - share message date formatting should be visually checked in the deferred native share-sheet smoke;
  - concurrent `reloadShareCode()` guard lacks a dedicated test but is low probability and the primary retry flow is covered.

## Reviewer Instructions

- Evaluate only this bounded package and the changed files listed above.
- Classify findings using the triple-audit gate: `blocking`, `accepted-debt`, or `out-of-scope`.
- Do not request backward compatibility for pre-release invite/friend/favorite behavior.
- Treat ADB/device smoke absence as deferred by orchestration, not by itself a blocker, unless the automated evidence cannot prove the targeted non-device behavior.
