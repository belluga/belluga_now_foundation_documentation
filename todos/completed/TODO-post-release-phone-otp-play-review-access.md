# TODO (Completed): Post-Release Hardening - Phone OTP Play Review Access

**Completed note (2026-05-06):** this slice was delivered. The released system now supports Google Play review access through the canonical phone-auth path using tenant settings, hash-only persistence, and dedicated backend/runtime coverage. This file remains only as audit history.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Status
- **Status:** `Completed`
- **Disposition:** `Delivered and retained for audit history`

## Context
The Android store-release lane needed a reusable reviewer-access contract that would let Google Play authenticate into app surfaces without depending on live SMS delivery or a reviewer-owned phone number. The accepted direction was not a generic production bypass; it was a tightly-scoped review-only contract governed through the canonical phone-auth model.

This slice originally lived inside the broader phone OTP post-release hardening TODO. It has now been split out because the reviewer-access contract is already delivered while the remaining OTP concurrency/idempotency work is still pending.

## Delivered Contract
- Reviewer access resolves through the normal phone-auth identity flow; no parallel review-only identity path was introduced.
- The dedicated review phone and reusable code hash are stored in canonical settings, not hardcoded in source.
- Settings persistence is hash-only; cleartext code is helper input only.
- The admin UI exposes:
  - editable review phone
  - cleartext helper field for review code
  - explicit `Gerar hash` action
  - read-only hash field
  - persisted save flow that stores only the hash-backed settings payload
- Non-allowlisted phones cannot use the review path.
- Disabled review users cannot authenticate through the review path.
- The review path remains bound to the resolved tenant/user identity instead of authorizing an arbitrary phone/user pair.

## Evidence
- Backend runtime:
  - `laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php`
  - `laravel-app/app/Integration/Settings/PhoneOtpReviewAccessSettingsNamespaceRegistrar.php`
- Backend feature coverage:
  - `laravel-app/tests/Feature/Auth/TenantPhoneOtpAuthTest.php`
  - `laravel-app/tests/Feature/Settings/SettingsKernelControllerTest.php`
- Flutter/admin runtime:
  - `flutter-app/lib/infrastructure/repositories/tenant_admin/tenant_admin_settings_repository.dart`
  - `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_phone_otp_review_access_section.dart`
- Flutter/admin coverage:
  - `flutter-app/test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
  - `flutter-app/test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`

## Decision Record
- The review-access contract uses a dedicated allowlisted review phone.
- A generic bypass for arbitrary phones is rejected.
- Persisted settings remain hash-only.
- The review phone flows through canonical auth identity resolution.
- Revocation occurs by disabling the resolved review user.

## Store Release Relationship
This slice was delivered as part of the post-release Android/store-review hardening wave. The remaining phone OTP TODO now owns only concurrency and idempotency work.
