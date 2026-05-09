# Contact Materialization And Performance Audit Package

**Generated:** 2026-05-01  
**TODO:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`  
**Scope:** bounded correction for real registered contacts staying in the auxiliary `Telefone` branch and slow repeated contact access on `/convites/compartilhar`.

## Problem Statement

Manual QA confirmed the device contact `Bruna` (`+55 27 99886-9802`) is already a real registered user, but still appeared only under `Telefone` instead of canonical `Contatos`/`Pessoas`. Repeated access was also slow because the flow could reload/reprocess contacts and repost the same full hash set.

## Implemented Correction

### Laravel

- `app/Domain/Identity/AnonymousIdentityMerger.php`
  - Migrates `contact_hash_directory.importing_user_id` from anonymous source ids to the registered target user during OTP merge.
  - Merges duplicate target/source directory rows by `contact_hash`, preserving matched user, match snapshot, earliest import timestamp, and latest seen timestamp.
- `app/Application/Social/InviteablePeopleService.php`
  - Fetches only rows with non-empty `matched_user_id` for the current viewer before applying the 500-row cap.
  - Prevents old unmatched rows from hiding a later real matched contact.
- `tests/Feature/Auth/TenantPhoneOtpAuthTest.php`
  - Adds regression coverage proving anonymous contact imports remain visible after OTP merge and `/contacts/inviteables` returns the target as `contact_match`.
- `tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
  - Adds regression coverage proving 500 unmatched directory rows do not hide a later matched real contact.

### Flutter

- `lib/infrastructure/repositories/contacts_repository.dart`
  - Stops loading contact photos for invite contact import (`withPhoto: false`), removing unnecessary device-book cost.
- `lib/infrastructure/dal/dao/invites/invite_contact_import_cache*.dart`
  - Adds local-only import-signature cache; stores no raw contact PII remotely.
- `lib/infrastructure/repositories/invites_repository.dart`
  - Builds deterministic hash/type import signature.
  - Skips unchanged fresh full-hash imports for the current tenant/viewer/region.
  - Keeps explicit refresh as forced import.
- `lib/domain/invites/inviteable_recipient.dart` and `lib/infrastructure/dal/dao/invites/invites_response_decoder.dart`
  - Preserve optional backend `contact_hash/contact_type` for inviteable rows.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
  - Uses backend inviteable `contact_hash` values to keep already matched people out of the auxiliary `Telefone` branch even when the import call is skipped by fresh signature.
  - Normal entry uses cached contacts; explicit refresh forces device rescan/reimport.
- Flutter tests prove cache-hit import skip, changed-hash reimport, explicit refresh, cached contact hydration, and backend contact-hash filtering of external phone targets.

### Documentation

- Updated:
  - `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Frontend / Consumer Matrix

| Producer Surface | Consumer | Required Behavior | Evidence | Status |
| --- | --- | --- | --- | --- |
| `POST /auth/otp/verify` anonymous merge side effect | Laravel contact directory + Flutter registered session | Contact imports performed under anonymous app identity remain attached to registered viewer after OTP merge. | `TenantPhoneOtpAuthTest.php` full file, including `test_phone_otp_verification_migrates_anonymous_contact_imports_to_registered_viewer`. | Implemented + tested |
| `GET /contacts/inviteables` | Flutter `/convites/compartilhar` Pessoas list and external `Telefone` filtering | Returns matched contact rows even when old unmatched rows exist; `contact_match` rows may carry `contact_hash/contact_type`. | `StoreReleaseSocialGraphTest.php` full file; `invites_repository_test.dart`; invite share controller tests. | Implemented + tested |
| Flutter local contact import cache | `/convites/compartilhar` controller | Normal opens avoid device reload and unchanged full-hash repost; explicit refresh rescans/reimports; cache keys are scoped by tenant/viewer/region. | `contacts_repository_test.dart`, `invites_repository_test.dart`, invite share controller tests. | Implemented + tested |
| Device contacts | Laravel server | Server receives only `type/hash`; raw contact PII remains local. | Source review and Flutter repository tests asserting hash payloads. | Implemented + tested |

No backend-only waiver is claimed: the changed producer surfaces have Flutter consumer updates and tests.

## Validation Evidence

### Red Tests Before Fix

- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPhoneOtpAuthTest.php --filter=phone_otp_verification_migrates_anonymous_contact_imports_to_registered_viewer`
  - Failed before fix because the directory row remained under anonymous `importing_user_id`.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php --filter=inviteable_contacts_keep_late_contact_matches_visible_after_unmatched_directory_cap`
  - Failed before fix because the late matched target profile was absent from inviteables.

### Passing Tests After Fix

- `fvm flutter test test/infrastructure/repositories/contacts_repository_test.dart test/infrastructure/repositories/invites_repository_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/application/invites/invite_contact_phone_normalization_test.dart`
  - Passed: 35 tests.
- `fvm dart analyze --format machine`
  - Passed clean.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPhoneOtpAuthTest.php`
  - Passed: 12 tests, 70 assertions.
- `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
  - Passed: 15 tests, 103 assertions.

### Device Evidence

- `ANDROID_SERIAL=192.168.15.9:5555 adb shell content query --uri content://com.android.contacts/data/phones --projection display_name:data1:_id | head -n 20`
  - Output included: `Row: 0 display_name=Bruna, data1=+55 27 99886-9802, _id=12`.

## Remaining Gate

Manual/stage retest is still required after rebuild/deploy:

- Confirm the same ADB contact leaves `Telefone` and appears in canonical `Contatos`/`Pessoas`.
- Confirm `/contacts/inviteables` returns it as `contact_match`.
- Confirm repeated opens are fast and do not reload the device book or repost unchanged full hashes.

Promotion remains blocked until that manual/stage evidence passes.
