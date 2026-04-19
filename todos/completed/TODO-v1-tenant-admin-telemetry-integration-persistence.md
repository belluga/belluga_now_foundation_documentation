# TODO (V1): Tenant Admin Telemetry Integration Persistence

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Owners:** Flutter Team, Laravel Team
**Objective:** Establish a working tenant-admin telemetry integration flow that saves, reloads, and deletes integrations correctly end-to-end, starting with the current Mixpanel management path.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Next exact step:** Run a final authenticated tenant-admin smoke against the real Mixpanel save/reload/delete flow, then checkpoint/promotion handling.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary:** `foundation_documentation/todos/completed/TODO-vnext-telemetry-architecture-review.md`

## Scope
- Diagnose the current tenant-admin telemetry integration save flow, including the current Mixpanel form.
- Guarantee end-to-end behavior for:
  - initial load,
  - create/save,
  - reload/read-back,
  - update overwrite,
  - delete/clear.
- Ensure optional fields such as webhook URL remain nullable/omittable instead of failing persistence or decode when absent.
- Add deterministic coverage for the backend route/contract and the Flutter repository/controller path.

## Out of Scope
- Broader telemetry architecture redesign beyond what is necessary to make the tenant-admin integration management flow correct and durable.
- New telemetry providers beyond the currently exposed management contract.

## Decision Baseline (Frozen)
- `D-01`: Fixing the Mixpanel lane means fixing the full management flow, not only the current `404`.
- `D-02`: The tenant-admin telemetry screen is only correct when a saved integration survives a fresh reload and rehydrates the same state back into the UI.
- `D-03`: Optional URL/webhook fields may be `null` or absent; invalid optional leaves must be normalized to `null`/omitted instead of leaking invalid values into strict Flutter value objects.
- `D-04`: Flutter strict parsing remains the guardrail; contract drift must be repaired at the backend formatter/storage boundary, not relaxed into silent client-side acceptance.
- `D-05`: The final proof for this lane requires real save/read/delete coverage across Flutter repository/controller and Laravel API persistence, not only unit-level formatting tests.

## Ordered Steps
1. Reproduce the current Mixpanel save flow against the real tenant-admin path and capture the exact failing request/response.
2. Trace the canonical route ownership and persistence path for telemetry integration settings in Laravel.
3. Add fail-first Laravel tests for telemetry integration create/update/read/delete, including nullable/absent optional URL fields.
4. Add fail-first Flutter repository/controller tests proving the same saved integration round-trips back into UI state after reload.
5. Correct the route/formatter/storage path until save, reload, and delete all succeed for Mixpanel.
6. Re-run focused Laravel + Flutter validation and record the stable contract in the touched TODO/module references.

## Acceptance Criteria
- [ ] 🟧 Saving a Mixpanel integration no longer returns `404` or any other transport/runtime error.
- [ ] 🟧 After save, a fresh reload returns the same telemetry integration state.
- [ ] 🟧 Updating an existing telemetry integration persists the new values deterministically.
- [ ] 🟧 Deleting/clearing the telemetry integration removes it from storage and subsequent reads.
- [ ] 🟧 Optional webhook URL behaves correctly when omitted or `null`.
- [ ] 🟧 Flutter strict value objects continue to parse the canonical telemetry payload without workaround logic.

## Validation Steps
- [ ] 🟧 Real or mocked contract test: save Mixpanel integration and verify a successful response.
- [ ] 🟧 Real or mocked contract test: reload telemetry settings and verify the saved Mixpanel state.
- [ ] 🟧 Real or mocked contract test: update Mixpanel integration and verify the persisted overwrite.
- [ ] 🟧 Real or mocked contract test: delete telemetry integration and verify subsequent reads are empty.
- [ ] 🟧 `fvm flutter test` for touched tenant-admin settings repository/controller tests.
- [ ] 🟧 Focused Laravel tests for telemetry integration route/formatter/persistence path.
