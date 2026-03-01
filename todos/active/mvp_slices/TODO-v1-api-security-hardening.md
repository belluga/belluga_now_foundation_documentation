# TODO (V1): API Security Hardening (Platform Baseline)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Backend Team / Platform Security
**Objective:** Establish a platform-wide API security hardening baseline (rate limiting, anti-abuse controls, replay protection, challenge/recovery policy, and abuse-signal governance) that all packages consume consistently.

> Origin: extracted from ticketing `TKT-41` to avoid domain-local security policy drift and keep API security decisions centralized inside MVP execution.

---

## Scope
- Define global API anti-abuse policy model and inheritance hierarchy.
- Define threshold profiles (for example `strict|balanced|lenient`) and where they apply.
- Define replay/idempotency baseline across critical mutation endpoints.
- Define challenge/soft-block/hard-block lifecycle and recovery windows.
- Define abuse-signal audit/privacy policy (what is captured, retention, masking, and access).
- Define package integration contract so domains (ticketing, settings, events, future modules) consume the same guard semantics.

---

## Out of Scope
- Product-specific commercial rules (pricing, promotions, entitlement transitions).
- Provider/gateway-specific fraud systems.
- Frontend anti-bot UX implementation details.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-checkout-package-integration.md`
- `foundation_documentation/project_mandate.md`

---

## Pending Decisions (Proposed Here, Pending Validation)
- [ ] 🟡 Provisional `SEC-01` Global policy ownership and contract model.
  - Proposed decision:
    - Security hardening baseline is platform-owned and consumed through shared contracts/policies by all domains.
  - Validation gate:
    - No package defines conflicting threshold universe for shared API entrypoints.

- [ ] 🟡 Provisional `SEC-02` Threshold profile model and hierarchy.
  - Proposed decision:
    - Canonical profiles are centrally defined; hierarchy is `system_default -> tenant_override -> context_override`.
    - Context override cannot weaken below global minimum controls.
  - Validation gate:
    - Effective profile resolution is deterministic and auditable.

- [ ] 🟡 Provisional `SEC-03` Replay/idempotency baseline.
  - Proposed decision:
    - Critical mutation endpoints require idempotency key + replay-window validation.
    - Deterministic rejection/error contract for replayed/expired/malformed requests.
  - Validation gate:
    - Replay simulations do not duplicate side-effects.

- [ ] 🟡 Provisional `SEC-04` Challenge/block lifecycle.
  - Proposed decision:
    - Define soft-block and hard-block transitions, cooldown windows, and recovery criteria.
    - Policy must avoid breaking legitimate retries.
  - Validation gate:
    - False-positive handling path is explicit and operationally supportable.

- [ ] 🟡 Provisional `SEC-05` Abuse-signal privacy and retention.
  - Proposed decision:
    - Define minimal signal schema, retention TTLs, masking/pseudonymization rules, and access policy.
  - Validation gate:
    - Auditability and privacy requirements are both satisfied.

- [ ] 🟡 Provisional `SEC-06` Cross-domain integration contract.
  - Proposed decision:
    - Expose a stable contract (for example `AbuseGuardContract`) and canonical error taxonomy for all modules.
  - Validation gate:
    - Ticketing/events/settings can consume the same guard contract without local policy drift.

---

## Tasks
- [ ] ⚪ Define canonical anti-abuse policy schema and settings namespaces.
- [ ] ⚪ Define and document profile resolution algorithm and override constraints.
- [ ] ⚪ Implement shared guard contract + middleware/service wiring.
- [ ] ⚪ Implement replay/idempotency verifier and deterministic error mapping.
- [ ] ⚪ Implement challenge/block lifecycle engine and recovery logic.
- [ ] ⚪ Implement abuse-signal store, retention jobs, and access controls.
- [ ] ⚪ Provide package integration guide and migration notes for ticketing/events/checkout.
- [ ] ⚪ Synchronize README and foundation docs with final baseline.

---

## Validation Steps
- [ ] ⚪ Cross-domain contract tests (ticketing + events + settings) against the same guard semantics.
- [ ] ⚪ Replay/idempotency stress tests on critical mutation endpoints.
- [ ] ⚪ Rate-limit/challenge tests validating abuse mitigation without unacceptable false positives.
- [ ] ⚪ Privacy/audit tests validating retention and access controls.
- [ ] ⚪ `php artisan test` (full Laravel suite).

---

## Definition of Done
- [ ] ⚪ A single platform security baseline exists and is referenced by domain packages.
- [ ] ⚪ Threshold, replay, challenge, and privacy policies are deterministic and documented.
- [ ] ⚪ Ticketing `TKT-41` advanced hardening is closed via this TODO (with traceability note).
- [ ] ⚪ Documentation and tests prove no cross-package policy drift.

---

## Decision Log
- `SEC-00`: Decided. API security hardening is platform-wide and extracted from ticketing-local pending decision `TKT-41`.
- `SEC-01..SEC-06`: Proposed in this TODO and pending validation before implementation.
