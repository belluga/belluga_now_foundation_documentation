# T3 Review Packet Round 03 - Minimal Contacts, Favorites, And Friends MVP

**Artifact type:** derived review packet, non-authoritative
**Created:** 2026-04-28
**Governing TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
**Prior round resolutions:**
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/resolution.md`

## Round 03 Scope

This packet freezes the T3 delta after resolving round 02 blockers. Review only the T3 release slice and the recorded round 01/02 resolutions. Do not expand into T4 or the later consolidated ADB phase.

Resolved round 02 blockers now in scope:

- `POST /contacts/import` match payload generation is batched through `InviteablePeopleService::contactMatchPayloadsFor`, avoiding per-matched-user profile/capability resolution in the hot mutation path.
- `InviteMutationService` now authorizes accept/decline by the acting user while scoping canonical recipient lifecycle checks by `receiver_account_profile_id` when the acting user resolves to that recipient profile.
- Credited-winner lookup, invite acceptance supersession, direct-confirmation supersession, and pending-candidate closure now use the canonical account-profile recipient scope whenever available.
- `InviteShareService` now materializes share edges with `receiver_account_profile_id`, reuses existing share edges by account-profile recipient scope, and determines share materialization state from profile-keyed credited winners.
- Backend feature tests now prove account-profile keyed acceptance supersession, direct-confirmation supersession, share materialization, share reuse, and share state when legacy `receiver_user_id` is intentionally mismatched.
- Flutter group-management tests now use stateful repository doubles and assert refreshed controller/screen state after create, rename, member update, and delete.

Still intentionally out of this packet:

- ADB/device contact-permission smoke. This remains deferred to the consolidated ADB phase by the approved orchestration plan.
- T4 funnel metrics, T5 phone OTP, T6 web-to-app gate, and public account profile polish. Those have independent TODO gates.
- Claude CLI review content. The round 02 Claude attempt timed out without output and is recorded separately at `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-02.md`; retry remains a non-canonical auxiliary gate before final T3 closure.

## Changed Surfaces To Inspect

### Laravel

- `laravel-app/app/Application/Social/InviteablePeopleService.php`
- `laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
- `laravel-app/tests/Feature/Invites/StoreReleaseSocialGraphTest.php`

### Flutter

- `flutter-app/test/presentation/tenant/invites/screens/contact_group_management/controllers/contact_group_management_controller_test.dart`
- `flutter-app/test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart`

### Documentation / Gate Artifacts

- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/resolution.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-02.md`

## Validation Evidence

- `docker compose exec -T app ./vendor/bin/pint --test packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php app/Application/Social/InviteablePeopleService.php app/Integration/Invites/InviteIdentityGatewayAdapter.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 5 PHP files.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Favorites/FavoritesControllerTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 50 tests, 338 assertions.
- `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/contact_group_management/controllers/contact_group_management_controller_test.dart test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/widgets/invite_share_relation_filter_chips_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`
  - Result: passed on 2026-04-28.
  - Coverage: 20 Flutter tests.
- `fvm dart analyze --format machine`
  - Result: passed on 2026-04-28 with no diagnostics.
- `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --path laravel-app/app/Application/Social/InviteablePeopleService.php --path laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
  - Result: no high or medium findings.

## Review Questions

1. Are the round 02 blockers resolved without leaving an old/new split between `receiver_user_id` and `receiver_account_profile_id` in the canonical recipient lifecycle?
2. Is the contact-import matching path now bounded/batched enough for the hot mutation gate?
3. Do the added backend tests prove account-profile keyed acceptance, supersession, direct confirmation, and share materialization lifecycle behavior rather than only direct invite creation?
4. Do the updated Flutter group-management tests prove visible/refreshed state after CRUD mutations rather than only repository call intent?
5. Are any remaining concerns valid but non-blocking debt appropriate for the consolidated ADB/device phase or VNext package extraction?
