# TODO (V1): Push CTA Validation + Inline Selector Gating

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active  
**Owners:** Flutter App, push_handler Plugin  
**Objective:** Ensure push onboarding CTA enablement reflects selection requirements and text validators, with regression tests in the plugin and correct validator wiring in the app.

---

## Scope
- Update push_handler question/selector CTA gating:
  - `single_select` only enables CTA after exactly one selection.
  - `text` uses the step validator result (non-null string disables CTA).
  - `selector` with `selection_ui = inline` enables CTA only when `min_selected` is satisfied.
- Add/update StepConfig accessors needed for validator + selection UI.
- Add/adjust plugin widget tests for the CTA gating cases above.
- Verify Flutter app validator wiring so `required_text` returns a String error when invalid (ex: empty) and `null` when valid.
- Update onboarding documentation to reflect validator-based gating for push steps before code changes.

## Out of Scope
- Backend payload/schema changes.
- New selector layouts or selection_mode semantics beyond CTA gating.
- Telemetry changes.

---

## Tasks (Execution Checklist)

### Documentation
- [x] ✅ Production-Ready Update onboarding module docs to document validator-based CTA gating for push steps.

### push_handler Plugin
- [x] ✅ Production-Ready Adjust question/selector CTA enablement rules (single_select, text validator, inline selector min).
- [x] ✅ Production-Ready Add StepConfig accessors for `validator` and `selection_ui` (if missing).
- [x] ✅ Production-Ready Add tests:
  - single_select does not enable CTA without a selection.
  - text + validator keeps CTA disabled on empty input.
  - inline selector only enables CTA after `min_selected`.

### Flutter App
- [x] ✅ Production-Ready Verify `required_text` validator behavior and wiring (error string on invalid, null on valid).

---

## Definition of Done
- CTA enablement matches the required rules for single_select, text validators, and inline selectors.
- push_handler tests cover the new gating behavior.
- App validator returns correct values and is not ignored by the push flow.
- Documentation updated before code changes.

## Validation Steps
- Run push_handler tests covering the new cases.
- Run targeted Flutter app tests (or add one) if wiring changes are required.
