# T3 Review Packet Round 05 - Minimal Contacts, Favorites, And Friends MVP

**Artifact type:** derived review packet, non-authoritative
**Created:** 2026-04-28
**Governing TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
**Prior round resolutions:**
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/resolution.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-04/resolution.md`

## Round 05 Scope

This packet freezes the T3 delta after resolving round 04. Review only the T3 release slice and recorded prior resolutions. Do not expand into T4 or the later consolidated ADB phase.

Resolved round 04 blocker now in scope:

- `InviteIdentityGatewayContract` now exposes `resolveUserRecipientOwnership` separately from eligibility-aware `resolveUserRecipient`.
- `InviteablePeopleService::recipientIdentityForUserId` resolves the acting user's personal Account Profile identity independent of profile-type inviteability.
- `InviteablePeopleService::recipientForUserId` remains eligibility-aware for new direct `receiver_user_id` and `contact_hash` recipient creation; if a personal profile exists and is not inviteable, it returns null.
- `InviteMutationService` uses ownership resolution for existing profile-keyed accept/decline authorization, direct-confirmation supersession, receiver-scope fallback, and profile-scoped lifecycle checks.
- `InviteShareService` uses ownership resolution for share materialization lookup/state/persistence so existing or explicit share-code flows are not denied solely because the user's profile later stops being inviteable.
- Backend tests now prove the canonical owner can act on an existing profile-keyed invite after inviteability changes, while stale legacy `receiver_user_id` actors still cannot act and new direct/contact-hash recipient creation remains suppressed for non-inviteable profiles.

Still intentionally out of this packet:

- ADB/device contact-permission smoke. This remains deferred to the consolidated ADB phase by the approved orchestration plan.
- T4 funnel metrics, T5 phone OTP, T6 web-to-app gate, and public account profile polish. Those have independent TODO gates.

## Changed Surfaces To Inspect

### Laravel

- `laravel-app/app/Application/Social/InviteablePeopleService.php`
- `laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php`
- `laravel-app/packages/belluga/belluga_invites/src/Contracts/InviteIdentityGatewayContract.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
- `laravel-app/tests/Feature/Invites/StoreReleaseSocialGraphTest.php`

### Documentation / Gate Artifacts

- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-04/resolution.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-04.md`

## Validation Evidence

- `docker compose exec -T app ./vendor/bin/pint --test app/Application/Social/InviteablePeopleService.php app/Integration/Invites/InviteIdentityGatewayAdapter.php packages/belluga/belluga_invites/src/Contracts/InviteIdentityGatewayContract.php packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 6 PHP files changed for round 04 resolution.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Favorites/FavoritesControllerTest.php`
  - Result: passed on 2026-04-28.
  - Coverage: 52 tests, 358 assertions.
- `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --path laravel-app/app/Application/Social/InviteablePeopleService.php --path laravel-app/app/Integration/Invites/InviteIdentityGatewayAdapter.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php --path laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
  - Result: no high or medium findings.
- Flutter note: no Flutter files changed after the round 04 widget/analyzer pass. Latest Flutter targeted suite and `fvm dart analyze --format machine` passed before this backend-only delta.

## Review Questions

1. Does the separation between recipient eligibility and recipient ownership close `ELEGANCE-R04-001` without reopening the R03 stale-actor bypass?
2. Are new `receiver_user_id` and `contact_hash` recipient creation paths still eligibility-aware and suppressed for non-inviteable personal profiles?
3. Are existing profile-keyed accept/decline, direct confirmation, receiver-scope fallback, and share materialization paths now keyed by stable ownership rather than current inviteability?
4. Do the tests prove both sides of the split: owner can act after inviteability changes; stale legacy actor cannot; new legacy recipient creation is still suppressed?
5. Is any remaining issue blocking for T3, or only non-blocking debt for the consolidated ADB phase / VNext extraction?
