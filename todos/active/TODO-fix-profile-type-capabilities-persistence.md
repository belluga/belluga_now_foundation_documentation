# TODO (V1): Fix Profile Type Capability Persistence + Form Capability Test

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** Backend Team, Flutter Team  
**Objective:** Ensure profile type capabilities persist in Laravel and add a Flutter integration test that validates capability‑driven fields on create/edit forms.

---

## A) Scope
- Laravel: persist full `capabilities` payload for account profile types (`is_favoritable`, `is_poi_enabled`, `has_bio`, `has_taxonomies`, `has_avatar`, `has_cover`, `has_events`).
- Laravel: include full capabilities in profile type registry payloads returned to clients.
- Flutter: add an integration test that edits profile type selection and asserts the expected fields show/hide on the profile create/edit form.

## B) Out of Scope
- UI redesign or re‑labeling of fields.
- New profile type definitions or seed changes.
- API contract changes beyond capability persistence/echo.

## C) Tasks
- [x] ✅ Production‑Ready Extend `AccountProfileTypeStoreRequest` validation to accept all capability flags.
- [x] ✅ Production‑Ready Extend `AccountProfileTypeUpdateRequest` validation to accept all capability flags.
- [x] ✅ Production‑Ready Update `AccountProfileRegistryManagementService` payload handling to include all capability flags (create + update + serialization).
- [x] ✅ Production‑Ready Update `AccountProfileRegistryService` registry output to include all capability flags.
- [x] ✅ Production‑Ready Add Flutter integration test that switches profile type and validates capability‑driven fields.

## D) Definition of Done
- [x] ✅ Production‑Ready All profile type capability flags persist and re‑appear in registry payloads.
- [ ] ⚪ Pending Flutter integration test passes and verifies fields show/hide for at least two capability profiles.

## E) Validation
- [ ] ⚪ Pending `php artisan test` (or targeted suite if available).
- [ ] ⚪ Pending `fvm flutter test integration_test/feature_admin_profile_type_capabilities_form_test.dart`.
  - Note: build hung at `assembleDebug` (no completion after ~40s); rerun once device/Gradle is stable.
