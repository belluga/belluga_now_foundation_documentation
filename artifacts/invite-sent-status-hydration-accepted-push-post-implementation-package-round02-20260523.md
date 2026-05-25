# Post-Implementation Audit Package Round 02: Invite Sent Status Hydration and Accepted Push Presentation

## Scope
- TODO: `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-sent-status-hydration-and-accepted-push-presentation.md`
- Stack: Laravel + Flutter.
- Delivery type: bugfix, cross-stack, production-visible invite state/push/profile metrics.
- Round 02 reason: Round 01/Claude found unresolved load/test risk around same-key in-flight dedupe and additional evidence gaps. The current package includes the dedupe implementation, filtered-refresh merge fix, compound index migration, widget CTA coverage, terminal-status Flutter coverage, and refreshed validation evidence.

## User-Visible Contract
- Sent invite state is canonical backend state, scoped by authenticated inviter and occurrence.
- Flutter may optimistically mark sent invites pending after send, but opening an occurrence/invite surface hydrates canonical status from backend.
- `invite_accepted` updates sender-side sent invite state by `receiver_account_profile_id` / `accepted_by_account_profile_id`.
- `invite_accepted` foreground presentation must be visible and invite-specific, not the generic Push Handler screen flow.
- Notification tap for `invite_accepted` opens the event occurrence destination, not the received-invite flow.
- Profile social metrics represent sender-side `invites_sent` and accepted sent invites, not received pending invites or own attendance confirmations.
- Pending invites are not a supersession cause. A receiver can have multiple pending invites for the same event/occurrence, especially from different inviters; `superseded` is reserved for already-confirmed outcomes (`other_invite_credited` or `direct_confirmation`).

## Frontend / Consumer Matrix
| Producer surface | Consumer | Consumer implementation | Evidence |
| --- | --- | --- | --- |
| Laravel `GET /api/v1/invites/sent-statuses` | Flutter invites repository, invite share controller, immersive event detail controller | Implemented through `InvitesBackendContract.fetchSentInviteStatuses`, `InvitesRepository.refreshSentInvitesForOccurrence`, and controller occurrence-scoped calls. | `laravel_invites_backend_test.dart`, `invites_repository_test.dart`, `invite_share_screen_controller_test.dart`, `immersive_event_detail_controller_test.dart` |
| Laravel `/me` `social_score.invites_sent` and `social_score.invites_accepted` | Flutter profile DTO/controller/header | Implemented through `SelfProfileDto`, `SelfProfile`, `ProfileScreenController`, and profile header metrics. | `MeProfileSocialMetricsTest.php`, `self_profile_dto_test.dart`, `profile_screen_test.dart` |
| FCM data payload `invite_accepted` with accepted recipient identity | Flutter push runtime/repository/presenter | Implemented through `InvitePushPayloadDecoder.decodeAcceptedSentInvite`, `InvitesRepository.applyInvitePushPayload`, `InvitePushRuntimeCoordinator`, `InviteAwarePushMessagePresenter`, and app startup invite tap seeding. | `invites_repository_push_payload_test.dart`, `invite_push_runtime_coordinator_test.dart`, `invite_aware_push_message_presenter_test.dart`, `push_handler_wiring_test.dart` |

## Round 01 Findings and Current Resolution
| Finding | Current handling |
| --- | --- |
| Elegance: filtered sent-status refresh replaced the entire occurrence cache. | Resolved. Filtered refresh now merges returned statuses with existing occurrence cache by canonical recipient key instead of dropping non-filtered recipients. Covered by `refreshSentInvitesForOccurrence merges filtered status without dropping other recipients`. |
| Performance: sent-status endpoint lacked an explicit compound index for authenticated inviter + event + occurrence ordering. | Resolved. Added migration `2026_05_23_000300_add_sent_status_inviter_occurrence_index.php`; Laravel performance guard test checks the migration source and direct occurrence-scoped query. |
| Test Quality: duplicate invite card CTA disablement had only indirect controller evidence. | Resolved. Added widget test `sent pending and accepted invite cards disable duplicate invite CTA`. |
| Claude BLK-1: missing same-key in-flight dedupe for `refreshSentInvitesForOccurrence`. | Resolved. `InvitesRepository` now keeps `_activeSentStatusRefreshes` keyed by occurrence + stable recipient filter hash. Covered by `refreshSentInvitesForOccurrence dedupes same-key in-flight requests`, including reversed filter order. |
| Claude NBL-1 / Test Quality TQA-BLK-001: user-id fallback could violate canonical account-profile matching. | Resolved for mutation matching and regression-protected. `_matchesSentInviteRecipient` now matches only non-empty `accountProfileId`; push without account-profile id cannot mutate an unrelated existing sent status. Covered by `applyInvitePushPayload ignores accepted push without account profile match`. |
| Claude NBL-1: sent-status cross-tenant isolation needed explicit endpoint evidence. | Resolved. Added `test_sent_invite_statuses_reject_cross_tenant_account_token`, using a real tenant-scoped bearer token to prove primary tenant status read succeeds and the same token against another tenant is rejected without `data`. |
| Round 02 Test Quality TQA-R02-BLK-001: Flutter did not prove declined/superseded terminal sent statuses preserve actionability and summary semantics. | Resolved. Flutter `InviteStatus` now models `expired`, `superseded`, and `suppressed`; DAO/Event DTO decoders preserve them; invite-share card disables repeat CTA for non-null sent statuses; share/event summaries count only visible pending/accepted buckets and filter hidden terminal statuses. Covered by repository, controller, and widget tests for `superseded` preservation and summary/CTA behavior. |

## Laravel Implementation Summary
- Added sent-status read service/controller inside `belluga_invites`.
- Added host profile projection adapter for bounded recipient identity/avatar projection.
- Added route inside the existing tenant public invite route group guarded by `auth:sanctum` and `CheckTenantAccess`.
- Sent-status endpoint rejects client-controlled inviter identity, requires `occurrence_id`, treats `event_id` as consistency-only, and validates event/occurrence mismatch.
- Status matrix covers pending/accepted/declined and hidden terminal statuses.
- Added compound index for sent-status lookup by authenticated sender and occurrence ordering.
- `/me` now exposes sender-side social metrics from `PrincipalSocialMetric` while preserving pending invite counters separately.

## Flutter Implementation Summary
- Added backend request/contract/decoder for sent-status hydration.
- `InvitesRepository.refreshSentInvitesForOccurrence` fetches and stores canonical statuses by occurrence.
- Same-key in-flight sent-status refreshes are deduped by occurrence id plus sorted recipient filter hash.
- Filtered status refreshes merge into the occurrence cache instead of replacing unrelated recipients.
- Invite share and immersive event detail controllers refresh sent statuses only for the current occurrence, not at global post-auth bootstrap.
- `invite_accepted` payload parsing lives in DAO/push decoder; repository consumes typed payload and updates/upserts accepted sent status idempotently.
- `InvitePushRuntimeCoordinator` refreshes only the affected sent-invite occurrence on accepted push and routes accepted taps to the event occurrence path.
- `InviteAwarePushMessagePresenter` shows an invite-specific foreground SnackBar for simple invite push copy while still skipping generic Push Handler presentation.
- `ApplicationContract` seeds startup override from the initial invite push tap before push repository initialization completes.
- Profile DTO/domain/controller/header now render sender-side sent/accepted invite metrics with outlined/filled invite icons.
- `InviteStatus` now preserves backend terminal statuses (`expired`, `superseded`, `suppressed`) instead of defaulting them to `pending`.
- Invite share and event-detail summaries filter hidden terminal statuses and count only visible pending/accepted buckets so `superseded` cannot inflate pending counts.
- Invite share cards disable repeat CTA for canonical non-null sent statuses, including declined and confirmed/superseded rows, while keeping `superseded` semantically tied to confirmation instead of "already invited".
- Laravel sent-status coverage now explicitly proves competing pending invites for the same receiver/occurrence from different inviters remain `pending` with no `supersession_reason`; only a later credited acceptance/direct confirmation can make another invite `superseded`.

## Current Validation Evidence
- Laravel sent-status focused suite, sequential safe runner: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/InvitesFlowTest.php --filter='sent_invite_statuses|authenticated_inviter_can_fetch_pending_and_accepted'` -> `7 passed (76 assertions)`.
- Laravel pending/supersession clarification suite, sequential safe runner: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/InvitesFlowTest.php --filter='sent_invite_statuses_are_scoped_to_authenticated_inviter|accepting_one_invite_closes_duplicate_candidates'` -> `2 passed (24 assertions)`.
- Laravel profile metrics suite, sequential safe runner: `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Auth/MeProfileSocialMetricsTest.php` -> `4 passed (16 assertions)`.
- Flutter terminal-status fail-first evidence: same focused package initially failed compilation because `InviteStatus.superseded` did not exist; after implementation, the focused blocker subset (`invites_repository_test.dart`, `invite_share_screen_controller_test.dart`, `invite_share_screen_test.dart`) passed with `80 passed`.
- Flutter focused suite: `fvm flutter test test/infrastructure/user/dtos/self_profile_dto_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/dal/laravel_invites_backend_test.dart test/application/auth/post_auth_identity_hydration_coordinator_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/application/push/invite_push_runtime_coordinator_test.dart test/infrastructure/services/push/invite_aware_push_message_presenter_test.dart test/infrastructure/services/push/push_handler_wiring_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart` -> `139 passed`.
- Flutter analyzer plugin recovery: `bash ./scripts/reset_analyzer_state.sh` -> `done`.
- Flutter official analyzer gate after reset: `fvm dart analyze --format machine` -> exit `0`, no diagnostics.
- Flutter explicit stale-editor-warning check: `fvm dart analyze --format machine lib/domain/user/self_profile.dart` -> exit `0`, no diagnostics. This resolves the stale editor warning for `domain_primitive_field_forbidden` in `SelfProfile`.
- Flutter format gate for touched Flutter implementation/tests: `fvm dart format --set-exit-if-changed lib/domain/schedule/invite_status.dart lib/domain/invites/projections/friend_resume_with_status.dart lib/infrastructure/dal/dao/invites/invites_response_decoder.dart lib/infrastructure/dal/dto/schedule/event_dto.dart lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_friend_card.dart lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_summary.dart lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart test/infrastructure/repositories/invites_repository_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` -> exit `0`.
- Laravel style gate for touched invite feature test: `docker compose exec -T app ./vendor/bin/pint --test tests/Feature/Invites/InvitesFlowTest.php` -> pass.
- Diff hygiene: `git diff --check` in `laravel-app`, `flutter-app`, and `foundation_documentation` -> exit `0`.

## Known Harness Note
- Running the two Laravel focused suites in parallel produced Mongo database-drop/index races (`database is in the process of being dropped`, `index not found with name [path_1]`). Both suites pass sequentially with the canonical safe runner. Treat the parallel failure as harness misuse, not product evidence.

## Open Gate Classification for Auditors
- Real-device invite acceptance proof and full CI-equivalent execution are promotion/delivery gates. They are not claimed complete by this package unless explicitly added later to the TODO evidence matrix.
- Cold-start OS notification tap has source-level app startup seeding coverage in `push_handler_wiring_test`; if auditors require physical terminated-state ADB proof, classify that as device promotion evidence, not a remaining local code blocker, unless a concrete code path is missing.

## Audit Questions
- Elegance: Does this preserve canonical boundaries: DAO raw payload parsing, repository state ownership, controller/presenter UI responsibility, and backend invite package ownership?
- Performance: Does sent-status hydration stay bounded to occurrence + authenticated inviter + optional recipient filter, with compound index support, no global post-auth scans, no page-walking, no N+1 identity projection, and no duplicate same-key in-flight refreshes?
- Test Quality: Do the tests catch the original production regressions: restart losing sent status, pending/accepted counts wrong, duplicate invite buttons active, accepted push not perceived, accepted tap going to wrong route, profile metrics using wrong counters, and dedupe/load amplification?
