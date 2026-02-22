# TODO (V1): Flutter Forms 422 Validation Wrapper (Reusable Pipeline)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** Flutter Team  
**Objective:** Establish a reusable Flutter form-validation wrapper/pipeline that renders backend `422` errors inline per field, starting at Tenant Admin Account Create.  
**Execution order:** (1) implement pipeline, (2) immediately add automated tests for per-field error rendering.

---

## A) Scope (This TODO)
- Implement reusable `422` validation handling in Flutter tenant-admin forms:
  - preserve backend `errors` map as structured data,
  - expose field-level errors from controller via `StreamValue`,
  - bind field-level messages to `InputDecoration.errorText`,
  - keep non-field failures in global feedback (banner/snackbar).
- First adoption target: `TenantAdminAccountCreateScreen` + `TenantAdminAccountsController`.
- Support backend keys exactly as returned (including dotted paths such as `document.type`).

## B) Out of Scope (Explicitly removed from this TODO)
- Backend transaction/orchestration (`account + profile`) changes.
- Laravel endpoint additions/contract changes.
- Route-level architecture changes.
- UI redesign beyond wiring inline error display.

## C) Implementation Tasks (Phase 1)
- [ ] ⚪ Pending Create shared typed validation failure (e.g., `TenantAdminValidationException`) with:
  - `statusCode`
  - `message`
  - `Map<String, List<String>> fieldErrors`
- [ ] ⚪ Pending Update repository error parsing to keep structured `422.errors` (no flatten-to-string fallback for validation path).
- [ ] ⚪ Pending Add controller-level reusable form error state + helpers:
  - `errorFor(fieldPath)`
  - `clearField(fieldPath)`
  - `clearAllFieldErrors()`
- [ ] ⚪ Pending Apply pipeline to Account Create:
  - map backend field keys to form fields,
  - render inline field errors,
  - keep submit/global error for non-validation failures.
- [ ] ⚪ Pending Clear only the edited field error on value change (do not clear unrelated field errors).
- [ ] ⚪ Pending Document reuse pattern for next forms (Accounts Edit, Profiles, Static Assets).

## D) Test Tasks (Phase 2, immediately after Phase 1)
- [ ] ⚪ Pending Add controller test: when repository returns `422` with field map, controller exposes expected per-field errors and global message behavior.
- [ ] ⚪ Pending Add widget test for Account Create: inline `errorText` appears on the correct inputs for `422` payload.
- [ ] ⚪ Pending Add widget test: editing one field clears only that field error.

## E) Definition of Done
- [ ] ⚪ Pending Account Create displays backend `422` errors inline per field (not only global snackbar/banner).
- [ ] ⚪ Pending Validation error processing is reusable and controller-driven (no ad-hoc widget parsing).
- [ ] ⚪ Pending Non-validation errors remain global.
- [ ] ⚪ Pending Automated tests cover field mapping + selective clear-on-edit behavior.

## F) Validation
- [ ] ⚪ Pending Manual: reproduce backend `422` in Account Create and verify correct inline field mapping.
- [ ] ⚪ Pending Manual: edit one invalid field and verify only its error is cleared.
- [ ] ⚪ Pending Automated: controller + widget tests from Phase 2 passing.

## G) Notes
- Backend already returns standard `422` field structure; this TODO is strictly about Flutter consumption/rendering.
- Backend transaction scope lives in `foundation_documentation/todos/active/mvp_slices/TODO-account-profile-transaction-unified-create.md`.
