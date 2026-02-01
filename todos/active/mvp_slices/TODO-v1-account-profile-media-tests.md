# Documentation: TODO — Account Profile Media Tests
**Version:** 1.0
**Status:** Active

## 1. Objective
Establish deterministic Laravel tests for Account Profile media handling (avatar/cover) that validate insert, update/replace, and remove flows while verifying returned URLs are tenant-domain based. Fix the 422 error observed when uploading a new image to a profile that already has one.

## 2. Scope
- Add/extend feature tests to cover:
  - **Insert** avatar and cover during profile creation.
  - **Update/replace** avatar and cover on an existing profile (previous file removed).
  - **Remove** avatar and cover explicitly (clear URLs and delete stored files).
  - Validate URLs are tenant-domain routes and respond successfully (200) for insert/update.
- Update API request/media logic only if tests reveal regressions (ex: update with existing media returning 422).

## 3. Out of Scope
- Flutter client changes.
- Storage driver refactors or CDN setup.
- Image transformations (resize/crop).

## 4. Decisions
- **Remove semantics:** `remove_avatar` / `remove_cover` booleans clear URL + delete stored files.
- **URL validation:** tests will GET the returned URL to ensure it serves successfully.
- **Tenant resolution context:** tests use host-based tenant resolution (web), no `X-App-Domain`.

## 5. Risks / Open Questions
- Ensure update flow accepts multipart replacement when media already exists (no false 422).
- Ensure deletion + replacement does not leave orphan files.

## 6. Definition of Done
- Tests exist for insert, update/replace, and remove flows.
- Tests assert:
  - URL is tenant-domain path.
  - URL is reachable (200) for insert/update.
  - URL cleared + file removed on remove.
- Update replacement flow passes (no 422).
- Full Laravel test suite passes in Docker.

## 7. Validation Steps
- Run: `php artisan test` in Docker (full suite).
- Confirm upload/replace/remove responses include correct URLs.
- Confirm storage files created/removed as expected.

## 8. Implementation Plan
- Extend `tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` (or add focused media test file) for insert/update/remove.
- If a failure appears (422 on update with existing media), fix `AccountProfileMediaService` and/or request validation accordingly.
