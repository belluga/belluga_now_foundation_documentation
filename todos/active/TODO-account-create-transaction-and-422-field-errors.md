# TODO (V1): Account Create Transaction + Field‑Level 422 Errors

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Draft  
**Owners:** Backend Team, Flutter Team  
**Objective:** Ensure account creation is transactional (account + profile) and display 422 validation errors on their respective fields in the UI.

---

## A) Scope
- Flutter: map 422 validation errors to field‑level UI errors (and summary where appropriate).
- Laravel: create Account + Account Profile in a single transaction; rollback account if profile creation fails.

## B) Out of Scope
- UI redesign of account/profile forms.
- Changes to account/profile schemas beyond transactional safety.

## C) Tasks
- [ ] ⚪ Pending Capture a 422 payload from account create to confirm error shape and field names.
- [ ] ⚪ Pending Flutter: map 422 errors to form fields (by backend field name).
- [ ] ⚪ Pending Laravel: wrap account + profile creation in a transaction.
- [ ] ⚪ Pending Laravel: return a consistent error when profile creation fails (rollback ensured).
- [ ] ⚪ Pending Add/extend tests for transactional behavior (account + profile) and 422 field mapping if feasible.

## D) Definition of Done
- [ ] ⚪ Pending 422 errors appear under their corresponding fields.
- [ ] ⚪ Pending Account creation never leaves an account without a profile.

## E) Validation
- [ ] ⚪ Pending Reproduce 422 and verify field‑level error display.
- [ ] ⚪ Pending Account create failure (forced) does not persist account record.
