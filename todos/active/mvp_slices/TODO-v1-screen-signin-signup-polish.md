# TODO (V1): Screen Polish - Sign In / Sign Up

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Polish the tenant-public authentication entry flow (`Sign in` + `Sign up`) with clearer hierarchy, spacing, and state feedback while preserving current behavior/contracts.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Scope (Single Screen)
- Improve visual hierarchy and CTA clarity in sign-in and sign-up screens.
- Improve loading, validation, empty, and backend-error state presentation.
- Ensure mobile responsiveness and keyboard-safe layout behavior.

## Out of Scope
- Any backend/API/auth contract changes.
- New auth capabilities (MFA, social login, recovery redesign).

## Decision Baseline (Frozen)
- `D-01`: This TODO is visual-only in Flutter; auth endpoints, payloads, and server validation contracts remain unchanged.
- `D-02`: Route behavior and screen entrypoints for sign-in/sign-up are preserved exactly as current tenant-public flow.
- `D-03`: Mandatory fields and validation semantics stay intact; polish can improve readability/order only.
- `D-04`: Submit CTA must lock only during in-flight submission and return to prior enabled/disabled semantics after response.
- `D-05`: Layout must remain keyboard-safe and overflow-safe on common mobile widths.
- `D-06`: Colors come from theme tokens; no hardcoded Stitch palette.
- `D-07`: Controller-first architecture remains mandatory.

## Tasks
- [ ] ⚪ Refine sign-in visual hierarchy (title, fields, CTA, helper copy).
- [ ] ⚪ Refine sign-up visual hierarchy (title, fields, CTA, helper copy).
- [ ] ⚪ Improve validation/error readability for field and form-level errors.
- [ ] ⚪ Improve loading state feedback and disable/reenable UX transitions.
- [ ] ⚪ Validate layout behavior on common mobile breakpoints.
- [ ] ⚪ Ensure no change in auth submission contracts and navigation outcomes.

## Acceptance Criteria
- [ ] ⚪ Sign-in and sign-up both show clear visual hierarchy without changing field semantics.
- [ ] ⚪ Validation and backend-error messages are more readable while preserving existing behavior.
- [ ] ⚪ Loading/disabled-CTA transitions are explicit and do not allow duplicate submissions.
- [ ] ⚪ No auth routing or response-handling regressions are introduced.

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Manual smoke for happy + failure paths is recorded for both screens.
- [ ] ⚪ Visual polish does not require API/backend changes.

## Validation Steps
- [ ] ⚪ Manual smoke: sign-in happy path.
- [ ] ⚪ Manual smoke: sign-up happy path.
- [ ] ⚪ Manual smoke: invalid credentials/validation error states.
- [ ] ⚪ Manual smoke: loading/disabled CTA behavior.
- [ ] ⚪ Manual smoke: keyboard open/close and small-width overflow behavior.
