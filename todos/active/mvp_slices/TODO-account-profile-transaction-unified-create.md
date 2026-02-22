# TODO (V1): Unified Account + Profile Create Transaction

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** Laravel Team + Flutter Team  
**Objective:** Establish a single canonical create flow for tenant-admin account onboarding that persists `account + account_profile` atomically on backend and returns field-level validation errors compatible with Flutter forms.

---

## A) Scope (This TODO)
- Define one canonical backend create endpoint for tenant-admin onboarding that receives required account/profile payload.
- Execute account + profile persistence inside one backend transaction boundary.
- Guarantee failure semantics:
  - no partial persistence when one side fails,
  - `422` validation map returned by field (`errors`) for Flutter field binding,
  - deterministic error contract for non-validation failures.
- Update Flutter repository/controller create flow to consume only this unified create endpoint.

## B) Out of Scope
- Full redesign of account/profile UI.
- Broader admin route refactors unrelated to create flow.
- Generic Flutter 422 form wrapper (tracked separately).

## C) Decisions to Close
- [ ] ⚪ Pending Direct-create endpoint policy after unified flow is live:
  - Option A: keep endpoints for non-admin/internal use with explicit scope guards.
  - Option B: block tenant-admin access to direct creates and enforce unified endpoint only.

## D) Tasks
- [ ] ⚪ Pending Specify canonical request/response contract for unified create (required fields and field keys).
- [ ] ⚪ Pending Implement backend orchestration service with transaction semantics.
- [ ] ⚪ Pending Implement endpoint + validation + structured `422` field errors.
- [ ] ⚪ Pending Update Flutter data layer to call unified endpoint for account create.
- [ ] ⚪ Pending Remove/disable old two-step create usage in tenant-admin flow.
- [ ] ⚪ Pending Align documentation/contracts in `foundation_documentation/` for the new canonical flow.

## E) Definition of Done
- [ ] ⚪ Pending Tenant-admin account creation uses a single backend call.
- [ ] ⚪ Pending Backend guarantees atomic persistence (no orphan account / no orphan profile).
- [ ] ⚪ Pending Validation failures return field-level `422.errors` consumable by Flutter.
- [ ] ⚪ Pending Flutter tenant-admin no longer performs two-step `account then profile` create for this flow.

## F) Validation
- [ ] ⚪ Pending Backend test: success path persists both documents.
- [ ] ⚪ Pending Backend test: forced profile failure rolls back account creation.
- [ ] ⚪ Pending Backend test: invalid payload returns structured `422` field map.
- [ ] ⚪ Pending Flutter integration/manual validation: create succeeds with single call and shows backend field errors when invalid.

## G) Notes
- This TODO is intentionally separate from Flutter form wrapper work:
  - `foundation_documentation/todos/active/mvp_slices/TODO-flutter-forms-422-validation-wrapper.md`.
