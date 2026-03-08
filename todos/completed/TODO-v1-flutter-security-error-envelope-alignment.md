# TODO (V1): Flutter Security Error Envelope Alignment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Flutter Team / Platform API Contract
**Objective:** Align Flutter shared form/error handling with the API security hardening envelope so security rejections are rendered with deterministic, user-readable messages while preserving `422` inline validation behavior.

---

## Canonical Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/endpoints_mvp_contracts.md`
- **Secondary:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-api-security-hardening.md`
- **Reference:** `foundation_documentation/todos/completed/TODO-flutter-forms-422-validation-wrapper.md`

---

## Scope
- Extend shared Flutter error handling package (`packages/belluga_form_validation`) with a typed API failure model for non-`422` responses.
- Parse API security hardening envelope fields when present: `code`, `message`, `retry_after`, `correlation_id`, `cf_ray_id`.
- Preserve existing `422` behavior: keep `FormValidationFailure` and inline field/group/global rendering unchanged.
- Update tenant-admin repository resolver(s) to consume shared package parsing utilities instead of ad-hoc parsing where applicable.
- Add/adjust unit tests to lock the new parsing and rendering behavior.

## Out of Scope
- Backend API contract changes.
- Route/controller architecture redesign.
- New UX components beyond current error banners/messages.

---

## Complexity & Checkpoint Policy
- **Complexity:** `small`
- **Checkpoint policy:** consolidated review before delivery (single checkpoint).

---

## Decision Baseline (Frozen)
- **D-01:** Keep `422` transport-agnostic validation handling exactly as the canonical form-validation path (`FormValidationFailure`).
- **D-02:** Introduce a typed non-`422` API failure carrying `statusCode`, `errorCode`, `message`, `retryAfter`, `correlationId`, `cfRayId` (when present), with stable `toString()` for existing UI consumers.
- **D-03:** Keep controller behavior unchanged (`FormValidationFailure` catch remains dedicated; non-validation errors flow through existing generic branch) while improving message quality via typed failure formatting.

## Module Coherence Gate
- **D-01** | Module Coherence: `Aligned` | Change Intent: `Preserve`
  - Evidence: `foundation_documentation/modules/flutter_client_experience_module.md` (`2.2 Success/Failure Handling`) + completed forms wrapper TODO.
- **D-02** | Module Coherence: `Aligned` | Change Intent: `Preserve`
  - Evidence: `foundation_documentation/endpoints_mvp_contracts.md` (security rejections include machine-readable codes + tracing metadata).
- **D-03** | Module Coherence: `Aligned` | Change Intent: `Preserve`
  - Evidence: existing tenant-admin controller split for validation vs operational errors.

---

## Tasks
- [x] ✅ Production‑Ready Add typed API failure model and parser in `packages/belluga_form_validation`.
- [x] ✅ Production‑Ready Expose parsing utility from package public API.
- [x] ✅ Production‑Ready Refactor tenant-admin validation/error resolver to reuse package parser.
- [x] ✅ Production‑Ready Update module documentation success/failure contract to include security envelope compatibility.
- [x] ✅ Production‑Ready Add/adjust tests for `422` compatibility and security envelope parsing (`403`, `409`, `429`, `422-idempotency`).

---

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter test packages/belluga_form_validation/test`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_accounts_repository_test.dart test/infrastructure/repositories/tenant_admin_account_profiles_repository_test.dart test/presentation/tenant_admin/shared/tenant_admin_error_state_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_organizations_repository_test.dart test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/infrastructure/repositories/tenant_admin_static_assets_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter analyze`

---

## Definition of Done
- [x] ✅ Production‑Ready Security hardening response envelope is parsed into structured Flutter failures without losing metadata.
- [x] ✅ Production‑Ready Existing `422` inline validation UX remains unchanged.
- [x] ✅ Production‑Ready Tenant-admin operational errors show deterministic user-facing text for rate-limit/origin/idempotency rejection cases.
- [x] ✅ Production‑Ready Documentation reflects canonical Flutter-side error contract.

---

## Decision Adherence Validation
| Decision ID | Status | Evidence |
|---|---|---|
| D-01 | Adherent | `flutter-app/packages/belluga_form_validation/lib/src/failures/form_api_failure_parsers.dart` keeps `422` mapped to `FormValidationFailure`; repository tests preserve field/global behavior. |
| D-02 | Adherent | `flutter-app/packages/belluga_form_validation/lib/src/failures/form_api_failure.dart` + `.../form_api_failure_parsers.dart` implement typed non-`422` failure with `retryAfter`, `correlationId`, `cfRayId`, and stable string representation. |
| D-03 | Adherent | Controllers unchanged; repositories emit typed exceptions through existing flow (`tenant_admin_validation_failure_resolver.dart`), and tenant-admin banner mapping is improved in `tenant_admin_error_state.dart`. |
