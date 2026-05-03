# Derived Review Packet — T1 Web-to-App Conversion Gate

**Artifact type:** derived/bounded review package, non-authoritative.  
**Generated for:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`  
**Scope:** T1 only. Do not review T2/T3/T4 or unrelated foundation-doc TODO moves in this package.  
**Date:** 2026-04-28

---

## Frozen Contract

T1 closes the non-ADB part of the Store Release Android web-to-app conversion gate:

1. `/baixe-o-app` must render the real app-promotion/store handoff experience, not the pre-MVP tester waitlist by default.
2. Web promotion/open-app handoff must preserve valid continuation intent beyond invite-only:
   - invite landing with valid `code` canonicalizes to `/invite` + `code`;
   - public detail/allowed tenant-public paths preserve route intent;
   - auth-owned app continuation paths such as `/profile` preserve app intent for native auth continuation;
   - invalid, external, backend-owned, or blocked web paths fall back to `/`.
3. `/open-app` must keep explicit `platform_target=android|ios` behavior.
4. Anonymous app favorites must no longer be forced through auth in discovery, public account-profile detail, and immersive linked-profile entrypoints.
5. ADB/device/store/deferred install validation is intentionally deferred to the final consolidated ADB phase.

Canonical anchors:
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`

---

## Files In Review

Review only the current working-tree diff for these Flutter files:

- `lib/application/router/support/route_redirect_path.dart`
- `lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart`
- `lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`
- `lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart`
- `test/application/router/support/route_redirect_path_test.dart`
- `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`
- `test/presentation/shared/widgets/app_promotion_dialog_test.dart`
- `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart`
- `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`

Suggested local inspection command:

```bash
git diff -- \
  lib/application/router/support/route_redirect_path.dart \
  lib/presentation/shared/promotion/screens/app_promotion_screen/controllers/app_promotion_screen_controller.dart \
  lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart \
  lib/presentation/tenant_public/partners/controllers/account_profile_detail_controller.dart \
  lib/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller.dart \
  test/application/router/support/route_redirect_path_test.dart \
  test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart \
  test/presentation/shared/widgets/app_promotion_dialog_test.dart \
  test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart \
  test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart \
  test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart
```

---

## Implementation Summary

- `route_redirect_path.dart`
  - Added allowlisted continuation normalization for promotion handoff.
  - Blocks absolute URLs, scheme-relative URLs, promotion boundary loops, blocked non-detail routes, and backend/admin-style paths by falling back to `/`.
  - Rejects external invite-shaped URLs before share-code canonicalization.
  - Bounds `/auth/login?redirect=...` unwrapping by redirect length and nested unwrap depth.
  - Preserves event detail `occurrence` query and map `poi`/`stack` query.
  - Preserves `/profile` and `/convites/compartilhar` as auth-owned app-continuation paths for open-app handoff.
  - Unwraps `/auth/login?redirect=...` to the nested valid app-continuation path for app handoff.

- `app_promotion_screen_controller.dart`
  - Default hardcoded promotion experience changed from `testerWaitlist` to `appDownload`.
  - Test-only override still supports tester waitlist coverage.

- Favorite controllers
  - Removed auth gating from favorite toggles in discovery, public account-profile detail, and immersive linked-profile favorite action.
  - Attendance/check-in and other restricted action auth gates remain unchanged.

---

## Validation Evidence

Fail-first check:
- The updated focused tests failed before implementation on redirect preservation, `/profile` app continuation, default `appDownload`, discovery anonymous favorite, and account-profile anonymous favorite assertions.
- Round 01 audit follow-up tests failed before the fix on external invite redirects and over-nested auth redirect unwrap behavior.

Final checks:

```bash
fvm flutter test \
  test/application/router/support/route_redirect_path_test.dart \
  test/presentation/shared/widgets/app_promotion_dialog_test.dart \
  test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart \
  test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart \
  test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart \
  test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart
```

Result: `106/106` tests passed.

```bash
fvm dart analyze --format machine
```

Result: exit code `0`, no analyzer output.

Round 01 audit resolution:
- `foundation_documentation/artifacts/t1-web-to-app-review-packet-triple-audit-20260428T142017Z/round-01/resolution.md`

---

## Known Deferred Evidence

- No ADB/device/store install validation was run in this T1 non-ADB phase.
- Real Play Store install, deferred deep-link capture, and first-open restore are reserved for the consolidated final ADB session in the orchestration plan.

## Post-Review Gap Closure - 2026-04-29

The follow-up backend/frontend gap audit found that Flutter had widened promotion redirect support, but backend `/open-app` attribution and deferred resolution still behaved invite-first. This was closed as part of the same promotion lane.

Additional files in review:

- `../laravel-app/packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php`
- `../laravel-app/packages/belluga/belluga_deep_links/src/Application/DeferredDeepLinkResolverService.php`
- `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`
- `../laravel-app/tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php`
- `lib/domain/repositories/deferred_link_capture_result.dart`
- `lib/domain/repositories/value_objects/deferred_link_repository_contract_values.dart`
- `lib/domain/repositories/value_objects/deferred_link_target_path_value.dart`
- `lib/infrastructure/dal/dto/deferred_link/deferred_link_resolution_dto.dart`
- `lib/infrastructure/dal/dao/laravel_backend/deferred_links_backend/laravel_deferred_link_backend.dart`
- `lib/infrastructure/repositories/deferred_link_repository.dart`
- `lib/application/startup/app_startup_plan_resolver.dart`
- `lib/presentation/shared/init/screens/init_screen/controllers/init_screen_controller.dart`
- `test/infrastructure/repositories/deferred_link_repository_test.dart`
- `test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart`

Additional validation:

```bash
docker compose exec -T \
  -e DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0' \
  -e DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0' \
  -e DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0' \
  app php artisan test \
  tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php \
  tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php
```

Result: `9` tests passed, `52` assertions.

```bash
docker compose exec -T app php vendor/bin/pint --test \
  packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php \
  packages/belluga/belluga_deep_links/src/Application/DeferredDeepLinkResolverService.php \
  tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php \
  tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php
```

Result: `4` files passed.

```bash
fvm flutter test \
  test/infrastructure/repositories/deferred_link_repository_test.dart \
  test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart \
  test/application/router/support/route_redirect_path_test.dart \
  test/presentation/shared/widgets/app_promotion_dialog_test.dart
```

Result: `45/45` tests passed.

---

## Review Instructions

Return findings first, ordered by severity. Classify each as:

- `blocking`: must be fixed before T1 can pass this gate;
- `accepted-debt`: valid but non-blocking for this T1 gate;
- `out-of-scope`: outside this T1 package.

Focus on:
- correctness/security of redirect allowlisting and app-continuation preservation;
- whether default app-promotion behavior is safely covered;
- whether anonymous favorite entrypoints are aligned without weakening restricted actions;
- whether test evidence is strong enough for this non-ADB phase;
- architecture drift in touched controllers/routes/tests.
