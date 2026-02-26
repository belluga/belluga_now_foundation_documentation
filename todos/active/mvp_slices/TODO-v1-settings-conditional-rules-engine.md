# TODO (V1): Settings Conditional Rules Engine (ACF-Inspired)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team
**Objective:** Establish a deterministic conditional-rules engine for `belluga_settings` so schema-driven UI can render and enable fields/groups using declarative rules (`visible_if`, `enabled_if`) with stable technical references.

---

## Scope
- Define V1 conditional DSL contract for settings schema (`visible_if`, `enabled_if`).
- Implement validation for syntax, operator correctness, and target reference integrity.
- Implement deterministic evaluator service for backend-side testing and contract verification.
- Add normalization rules so condition payloads are canonical and stable for clients.
- Add robust test coverage (unit + feature) and include full Laravel suite as phase gate.

---

## Out of Scope
- Flutter renderer implementation (deferred to Flutter stream after Events Phase #3 completion).
- Capability-specific business rules (ticketing, inventory, qr_checkin, etc.).
- Dynamic/custom operator plugins in V1 (operator set is fixed).

---

## Standards/Exception Reference (Locked)
- Parent stream:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-settings-kernel-package.md` (`S1-14`, `S1-16`).
- Events foundation alignment:
  - `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-package-phase-3.md` (`D3-12`, `D3-14`, `D3-15`).
- Multitenancy requirement:
  - `config/multitenancy.php` tenant-scoped execution model for test/migration routines.

---

## Pending Decisions (Design Inputs)
- [x] ✅ Production‑Ready `CR-01` DSL shape:
  - Conditions use `groups[]` (OR).
  - Each group uses `rules[]` (AND).
- [x] ✅ Production‑Ready `CR-02` Reference target:
  - Rule operands target technical node `id` (or canonical technical path), never labels.
- [x] ✅ Production‑Ready `CR-03` V1 operator set:
  - `equals`, `not_equals`, `in`, `not_in`, `exists`, `gt`, `gte`, `lt`, `lte`.
- [x] ✅ Production‑Ready `CR-04` Contract fields:
  - `visible_if` and `enabled_if` share the same DSL/evaluator contract.
- [x] ✅ Production‑Ready `CR-05` Evaluation surface:
  - Backend validates and exposes declarative DSL; frontend evaluates `visible_if`/`enabled_if` for dynamic rendering.
  - Backend remains authoritative for tenant capability gating and endpoint behavior contracts.
- [x] ✅ Production‑Ready `CR-06` Missing dependency behavior:
  - Unresolved references must fail schema validation with explicit error (`422` contract violation).
  - No fallback evaluation to `false` for invalid references.
- [x] ✅ Production‑Ready `CR-07` Numeric/date coercion policy:
  - Type comparison is strict by field definition.
  - No implicit coercion between string/number/date/bool in rule evaluation or validation.
- [x] ✅ Production‑Ready `CR-08` Ordering policy:
  - Preserve authored order for `groups[]` and `rules[]`.
  - Backend normalization must not reorder expressions.
- [x] ✅ Production‑Ready `CR-09` Defensive limits:
  - `max_groups_per_expression = 10`.
  - `max_rules_per_group = 10`.
  - `max_total_rules_per_expression = 50`.
  - `max_condition_payload_bytes = 16384` (16 KB).
  - Limit violations must fail with explicit `422` contract error.

---

## Canonical DSL (V1)
```json
{
  "visible_if": {
    "groups": [
      {
        "rules": [
          { "field_id": "events.mode", "operator": "equals", "value": "advanced" },
          { "field_id": "events.stock_enabled", "operator": "equals", "value": true }
        ]
      },
      {
        "rules": [
          { "field_id": "events.role", "operator": "in", "value": ["admin", "manager"] }
        ]
      }
    ]
  },
  "enabled_if": {
    "groups": [
      {
        "rules": [
          { "field_id": "events.is_locked", "operator": "equals", "value": false }
        ]
      }
    ]
  }
}
```

---

## Tasks
- [ ] ⚪ Define PHP contracts/value objects for conditional rules:
  - `ConditionExpression`, `ConditionGroup`, `ConditionRule`, `ConditionOperator`.
- [ ] ⚪ Implement schema validator integration:
  - reject invalid operators
  - reject invalid/missing `field_id` targets
  - enforce operator/value compatibility by target field type
- [ ] ⚪ Implement evaluator service:
  - deterministic OR-of-AND reference evaluation
  - strict boolean output for both `visible_if` and `enabled_if`
  - scope is validator/test/reference behavior (not mandatory runtime endpoint evaluation)
- [ ] ⚪ Implement canonical normalization:
  - preserve authored ordering for `groups` and `rules` (freeze policy)
  - remove ambiguous payload forms
- [ ] ⚪ Add defensive limits:
  - enforce `max_groups_per_expression = 10`
  - enforce `max_rules_per_group = 10`
  - enforce `max_total_rules_per_expression = 50`
  - enforce `max_condition_payload_bytes = 16384` (16 KB)
  - return explicit `422` errors on limit violations
- [ ] ⚪ Integrate with schema endpoint contract:
  - return conditional metadata in canonical format
  - ensure compatibility with `schema_version` and stable node IDs
- [ ] ⚪ Document evaluator behavior for all operators and target types.
- [ ] ⚪ Update foundation docs and Laravel submodule summary after delivery.

---

## Validation Steps
- [ ] ⚪ `php artisan test` (full Laravel suite; mandatory gate).
- [ ] ⚪ Unit tests: DSL parser/validator/evaluator for all operators.
- [ ] ⚪ Unit tests: OR-of-AND behavior with positive/negative/mixed scenarios.
- [ ] ⚪ Unit tests: invalid reference/operator/value combinations fail deterministically.
- [ ] ⚪ Feature tests: `GET settings/schema` returns canonical conditional metadata.
- [ ] ⚪ Feature tests: conditional metadata remains stable across label/i18n/order changes.
- [ ] ⚪ Regression tests: invalid condition payloads cannot be registered by packages.

---

## Definition of Done
- [ ] ⚪ Conditional rules DSL is stable, documented, and versioned under settings schema contract.
- [ ] ⚪ Validator blocks invalid condition structures and invalid target references.
- [ ] ⚪ Evaluator is deterministic and fully covered by unit tests.
- [ ] ⚪ Schema endpoint exposes canonical conditional metadata compatible with stable node IDs.
- [ ] ⚪ Full Laravel suite passes after integration.

---

## Decision Log
- `CR-01`: Decided. DSL uses OR-of-AND structure (`groups[]` + `rules[]`).
  - Rationale: aligns with ACF-style field-condition model and keeps authoring predictable.
- `CR-02`: Decided. Conditions reference technical identifiers only.
  - Rationale: display labels and i18n are mutable; technical IDs are stable contracts.
- `CR-03`: Decided. V1 operator set is fixed and explicit.
  - Rationale: limits ambiguity and keeps evaluator predictable/maintainable.
- `CR-04`: Decided. `visible_if` and `enabled_if` share one evaluator contract.
  - Rationale: reduces complexity and avoids divergent semantics between visibility and enablement.
- `CR-05`: Decided. UI condition evaluation is frontend-driven.
  - Rule: backend returns canonical DSL and validates contract integrity.
  - Rule: frontend evaluates `visible_if`/`enabled_if` for fluid dynamic rendering.
  - Rule: backend continues enforcing tenant capability/runtime constraints independently from UI conditions.
- `CR-06`: Decided. Invalid condition references fail fast.
  - Rule: unknown or unresolved `field_id` must be rejected in schema validation (`422`).
  - Rule: evaluator must never silently accept unresolved references.
- `CR-07`: Decided. Comparison typing is strict.
  - Rule: operator/value compatibility follows declared field type.
  - Rule: no implicit coercion (for example string-to-number/date) is allowed in V1.
- `CR-08`: Decided. Keep authored expression ordering.
  - Rule: evaluator processes `groups[]` and `rules[]` in authored order.
  - Rule: backend canonicalization does not reorder conditions.
- `CR-09`: Decided. Apply bounded complexity limits to the DSL.
  - Rule: enforce fixed limits for groups/rules/payload size (`10/10/50/16KB`).
  - Rule: reject limit violations with explicit `422` contract errors.
