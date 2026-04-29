# Store Release Wave 2A Invite / Occurrence / Contact / Presence Audit Package

## Scope
- TODOs:
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
  - `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- Reopened QA symptoms:
  - Presence confirmation and invite-adjacent participation relationships must be occurrence-scoped.
  - Invite share CTA moved from `Gerando...` to retry because share-code generation used an invalid occurrence target.
  - Explicit friends/contact refresh did not expose a newly added device contact.
- Release premise:
  - Zero backward compatibility for first-production invites, favorites, friends, contact groups, contact-match inviteables, event-target invites, nullable occurrence targets, and `receiver_user_id` invite targeting.

## Implementation Summary
- Laravel attendance confirmation now requires `occurrence_id`, stores/lists `confirmed_occurrence_ids`, and scopes direct-confirmation invite supersession to the same occurrence.
- Laravel agenda/events confirmed-only filtering uses confirmed occurrence IDs instead of parent event IDs.
- Laravel direct invite and share-code request validation require `target_ref.occurrence_id`.
- Laravel invite target resolution, duplicate detection, credited-winner lookup, share-code creation/materialization/acceptance, and feed projection paths carry concrete occurrence identity and fail closed without it.
- Laravel direct invite request payloads reject `receiver_user_id`; direct invite responses and inviteable payloads expose release identity through `receiver_account_profile_id` and `user_id` only. Authenticated share materialization now requires a concrete account-profile recipient identity; `receiver_user_id` remains internal actor/audit metadata only.
- Laravel contact import persistence now uses bounded bulk upsert keyed by importing user and contact hash.
- Laravel personal-profile bootstrap now checks for an existing personal profile instead of treating any existing `account_roles` as proof that the personal profile exists. This fixes the post-QA contact-refresh path where a role-bearing phone user could match by hash but be suppressed from `contact_match` inviteables because no personal inviteable profile was materialized.
- Flutter user-events contracts/repositories/controllers now track confirmed occurrence IDs and compare selected occurrence identity.
- Flutter user-events repository now fails loudly when the confirmed-attendance endpoint returns stale `event_ids`/missing `confirmed_occurrence_ids`, preserving the last known confirmed state instead of silently clearing it.
- Flutter event-to-invite factory sends `event.selectedOccurrenceId` for share-code generation instead of a date string.
- Flutter received-invite detail now filters same-event pending invites by selected occurrence and renders the invite occurrence date/time in the visible invite card.
- Flutter invite share refresh merges newly imported contact matches with backend inviteables, hashes Brazilian phone variants in local and country-code forms, chunks expanded contact-import payloads to the backend cap, and surfaces explicit refresh failure without clearing current inviteables.
- Flutter invite repository/backend contracts now use typed DAL request DTOs for share-code creation, direct invite send, and contact import; raw transport maps are assembled at the Laravel backend adapter boundary.
- Flutter invite-share widget coverage now uses an occurrence-backed invite fixture and asserts retry calls share-code generation with the selected `occurrence_id`, closing the false-green path where `Tentar novamente` rendered without any backend call.
- Foundation docs now state occurrence-first check-in/reservation/outcome contracts; currently implemented runtime participation code is free attendance confirmation only, with physical check-in/reservation/no-show/manual outcomes deferred.

## Touched Surfaces
- Flutter: invite repository/controller, typed invite backend request DTOs, user-events repository/backend, event detail/search/home/profile confirmed-state consumers, event DTO invite projection, push/decoder paths, and focused tests.
- Laravel: attendance confirmation, invite mutation/share/feed/contact-import services, invite validation, target resolution, agenda confirmed-only filtering, account-profile recipient identity, and feature tests.
- Foundation docs/artifacts: occurrence identity, zero-backward-compatibility premise, recipient identity, TODO evidence, and triple-audit resolution artifacts.

## Frontend / Consumer Matrix
| Producer Surface | Consumer | Evidence / Waiver |
| --- | --- | --- |
| `POST /events/{event_id}/attendance/confirm`, `POST /events/{event_id}/attendance/unconfirm`, `GET /events/attendance/confirmed` | Flutter user-events repository, event detail/search/home/profile controllers | Flutter focused suite passed; Laravel attendance feature tests passed; stale `event_ids` response shape now throws instead of clearing state. |
| Agenda/events `confirmed_only` filtering | Flutter home agenda/my-events/search/profile status rendering | Flutter focused suite passed; Laravel agenda/events test covered confirmed occurrence IDs. |
| `POST /invites` direct invite target/recipient contract | Flutter invite share controller/repository | Flutter focused suite passed; Laravel invite feature tests passed. |
| `POST /invites/share` share-code target contract | Flutter event detail -> invite factory -> invite share controller | Flutter focused suite passed; Laravel invite/share feature tests passed. |
| `GET /invites` feed/read model occurrence context | Flutter event detail received-invite card and controller | Laravel feed test asserts `target_ref.occurrence_id`, `event_date`, and location; Flutter controller/widget tests assert same-event different-occurrence filtering and visible occurrence date/time. |
| Contact import/inviteables refresh | Flutter invite share controller/repository | Flutter controller test proves refresh merge; repository test proves Brazil phone hash variants. |
| Check-in/reservation/no-show/manual attendance outcome contracts | No active runtime surface in this release slice | Explicit VNext/deferred waiver; docs corrected to occurrence-first. |
| Claude CLI auxiliary review | Gate only when CLI returns concrete output | Initial result became stale after fixes; final rerun timed out with empty output, so it is not a gate result. |

## Validation Evidence
- Laravel:
  - `bash scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Events/EventAttendanceControllerTest.php`
  - Result: `55 passed (409 assertions)` after final round-03 evidence refresh.
  - Round-02 delta: `bash scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/InvitesFlowTest.php`
  - Result: `34 passed (273 assertions)`.
- Flutter:
  - `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/infrastructure/dal/laravel_user_events_backend_test.dart test/infrastructure/dal/dao/invites/invites_response_decoder_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_origin_flow_test.dart test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart`
  - Result: `51 passed`.
  - Post DTO split: `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart`
  - Result: `23 passed`.
  - Round-02 delta: `fvm flutter test test/infrastructure/dal/laravel_user_events_backend_test.dart test/infrastructure/repositories/user_events_repository_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
  - Result: `37 passed`.
  - Round-03 expanded consumer delta: `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/infrastructure/dal/laravel_user_events_backend_test.dart test/infrastructure/repositories/user_events_repository_test.dart test/infrastructure/dal/dao/invites/invites_response_decoder_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`
  - Result: `78 passed`.
- Analyzer:
  - `fvm dart analyze --format machine`
  - Result: passed after final round-03 fixture/docs refresh.
- Web build:
  - `bash scripts/build_web.sh ../web-app dev`
  - Result: passed; derived bundle emitted to `../web-app`.
- Triple audit round 01:
  - Result: `needs_adjudication` from additive high findings.
  - Resolution: `resolved`; see `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-01/resolution.md`.
- Triple audit round 02:
  - Result: `needs_adjudication` from received-invite/feed occurrence-context and stale confirmed-occurrence consumer findings.
  - Resolution: `resolved`; see `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-02/resolution.md`.
- Triple audit round 03:
  - Result: `needs_adjudication` from one test-quality finding that final ADB/device smoke remains required before release closure.
  - Resolution: accepted as a final promotion gate, not a local code blocker; see `foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-03/resolution.md`.
- Hygiene:
  - `git diff --check` passed in Flutter, Laravel, and foundation docs after final evidence refresh.
- Contract scans:
  - No remaining Flutter references to old confirmed event APIs or event-keyed sent invite APIs.
  - No remaining Flutter `receiver_user_id` invite payload/decoder fallback.
  - Remaining Laravel `receiver_user_id` references are internal actor/feed/audit ownership, tests that intentionally corrupt internal fields for adversarial proof, or request validation prohibiting the old input field.
- Post-QA contact refresh root-cause evidence:
  - Read-only ADB diagnostics confirmed `com.guarappari.app` had `READ_CONTACTS` granted and the Android contacts provider contained `Bruna` with `+55 27 99886-9802`.
  - RED test: `./scripts/delphi/run_laravel_tests_safe.sh --filter=test_contact_import_matches_phone_user_that_already_has_non_personal_account_role tests/Feature/Invites/StoreReleaseSocialGraphTest.php` failed with `No query results for model [App\Models\Tenants\AccountProfile]` before the bootstrap fix.
  - GREEN focused test: same command passed after the fix.
  - Expanded Laravel regression: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPhoneOtpAuthTest.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php`
  - Result: `58 passed (419 assertions)`.

## Known Deferred Runtime Evidence
- ADB/device smoke remains deferred to the consolidated final device phase because the environment is resource-sensitive:
  - Generate/share invite for a selected occurrence and verify CTA leaves `Gerando...`.
  - Refresh device contacts and verify newly matched contact appears.
  - Confirm/accept one occurrence of a multi-occurrence event and verify another occurrence remains distinct.

## Audit Questions
1. Does any launch path still persist or act on event-scoped or nullable-occurrence invite/presence identity?
2. Does any public request/response surface still expose `receiver_user_id` as invite targeting identity?
3. Do the tests prove the actual QA regressions rather than only fallback behavior?
4. Are there missing frontend/admin/operator consumers for changed backend producer surfaces?
5. Are any remaining concerns material blockers under the zero-backward-compatibility premise?
