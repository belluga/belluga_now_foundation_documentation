# T3 Review Packet Round 04 - Minimal Contacts, Favorites, And Friends MVP

**Artifact type:** derived review packet, non-authoritative
**Created:** 2026-04-28
**Governing TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
**Prior round resolutions:**
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/resolution.md`

## Round 04 Scope

This packet freezes the T3 delta after resolving round 03 blockers. Review only the T3 release slice and recorded prior resolutions. Do not expand into T4 or the later consolidated ADB phase.

Resolved round 03 blockers now in scope:

- `InviteablePeopleService::recipientForUserId` now mirrors canonical Account Profile inviteability: if a personal profile exists, it must pass `profileIsInviteable`; only active users without a personal profile remain eligible for the legacy no-profile fallback with `receiver_account_profile_id=null`.
- `InviteMutationService::edgeBelongsToReceiver` now denies canonical profile-keyed edges to actors who only match stale/legacy `receiver_user_id`; when an edge has `receiver_account_profile_id`, the acting user must resolve to the same profile.
- Backend tests now prove unrelated legacy `receiver_user_id` actors cannot accept or decline a profile-keyed invite, while the canonical profile owner can still accept it.
- Backend tests now prove direct `receiver_user_id` and stale `contact_hash` recipient paths suppress non-inviteable personal profiles while preserving the no-profile legacy fallback.
- The contact-group widget test now exercises create, rename dialog, edit-members bottom sheet, and delete through screen controls with visible state assertions.
- The rename dialog now owns its `TextEditingController` inside a stateful dialog widget, fixing the disposal lifecycle bug exposed by the expanded widget test.

Still intentionally out of this packet:

- ADB/device contact-permission smoke. This remains deferred to the consolidated ADB phase by the approved orchestration plan.
- T4 funnel metrics, T5 phone OTP, T6 web-to-app gate, and public account profile polish. Those have independent TODO gates.

## Changed Surfaces To Inspect

### Laravel

- `laravel-app/app/Application/Social/InviteablePeopleService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`
- `laravel-app/tests/Feature/Invites/StoreReleaseSocialGraphTest.php`

### Flutter

- `flutter-app/lib/presentation/tenant_public/invites/screens/contact_group_management/contact_group_management_screen.dart`
- `flutter-app/test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart`

### Documentation / Gate Artifacts

- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/resolution.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-03.md`

## Validation Evidence

- `docker compose exec -T app ./vendor/bin/pint --test app/Application/Social/InviteablePeopleService.php packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 3 PHP files changed for round 03 resolution.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Favorites/FavoritesControllerTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 52 tests, 358 assertions.
- `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/contact_group_management/controllers/contact_group_management_controller_test.dart test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/widgets/invite_share_relation_filter_chips_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`
  - Result: passed on 2026-04-28.
  - Coverage: 20 Flutter tests.
- `fvm dart analyze --format machine`
  - Result: passed on 2026-04-28 with no diagnostics.
- `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --path laravel-app/app/Application/Social/InviteablePeopleService.php --path laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
  - Result: no high or medium findings.

## Review Questions

1. Are all round 03 blockers resolved with no remaining old/new split between canonical Account Profile recipient identity and legacy user/contact-hash paths?
2. Does the stricter profile-keyed authorization preserve the intended no-profile legacy fallback without allowing stale `receiver_user_id` actors to act on profile-keyed invites?
3. Do the backend tests prove both the positive canonical-owner path and the negative mismatched-actor/non-inviteable-profile paths?
4. Does the contact-group widget test now prove visible/refreshed screen behavior for create, rename, edit-members, and delete?
5. Did the dialog lifecycle fix resolve the screen wiring issue without moving business state into the widget layer?
