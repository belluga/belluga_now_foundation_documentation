# TODO (V1): Push Settings -> Settings Kernel Migration

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Active (Phase #1 completed; Phase #2 pending)
**Owners:** Backend Team
**Objective:** Complete Push migration to `belluga_settings` as the canonical settings runtime, validating kernel behavior in a real high-traffic module before Events capabilities depend on it.

---

## Scope
- Migrate Push settings read/write flows from legacy `TenantPushSettings` usage to settings-kernel namespace semantics.
- Preserve current tenant/landlord auth and ability boundaries.
- Keep API behavior stable where possible; any intentional change must be documented before implementation.

---

## Out of Scope
- Push message delivery/business flow redesign.
- Flutter integration changes.
- Events capability implementation.

---

## Standards/Exception Reference (Locked)
- Settings kernel authoritative contract:
  - `foundation_documentation/todos/completed/TODO-v1-settings-kernel-package.md`
- PATCH payload/clear semantics baseline (already converged):
  - direct payload
  - omitted keys unchanged
  - nullable-only clear with `null`
- Validation gate:
  - full Laravel suite in Docker is mandatory for phase completion.

---

## Phase Split (Canonical)

### Phase #1 (Simple, Execute Now)
- Migrate the core `push` namespace consumption to settings kernel:
  - `push.enabled`
  - `push.throttles`
  - `push.max_ttl_days`
- Keep existing push-specific endpoints as compatibility wrappers while internals persist/load through settings kernel.
- Ensure Push services that currently read legacy model use kernel-backed data path for these fields.
- Add/adjust tests to prove parity and kernel usage for this namespace.

### Phase #2 (Follow-up Migration)
- Migrate remaining push settings surfaces to kernel-backed semantics:
  - `firebase`
  - `telemetry`
  - `push.message_routes`
  - `push.message_types`
  - derived status flows affected by these settings
- Remove remaining legacy model-centric settings dependencies/wrappers after cutover.
- Harden with regression coverage for tenant + landlord on-behalf flows.

---

## Execution Snapshot (2026-02-26)
- Phase #1 delivered with kernel bridge adoption for `push` namespace core fields.
- Implemented `PushSettingsKernelBridge` in push package:
  - reads via `SettingsStoreContract` for `tenant/push` namespace
  - writes via `SettingsKernelService::patchNamespace(...)`
- Migrated Phase #1 endpoints/services to kernel-backed path:
  - `TenantPushSettingsController`
  - `TenantPushSettingsAdminController`
  - `TenantPushEnableController`
  - `TenantPushDisableController`
  - `TenantPushStatusController` (enabled check via kernel push namespace)
  - `PushDeliveryService` (`max_ttl_days` resolved from kernel namespace)
- Added feature validation:
  - `PushMessageFlowTest::testTenantPushSettingsPatchIsVisibleInKernelValuesEndpoint`
- Validation gate executed:
  - targeted tests passed
  - full Laravel suite in Docker passed (`790 passed`, `2869 assertions`).

---

## Pending Decisions (To Iterate)
- [ ] ⚪ `P1-01` Compatibility wrappers policy in Phase #1:
  - keep wrappers returning same payload shape vs expose kernel canonical payload directly.
- [ ] ⚪ `P1-02` Sequence inside Phase #2:
  - migrate `firebase/telemetry` first vs `route_types/message_types` first.
- [ ] ⚪ `P1-03` Legacy `TenantPushSettings` model fate:
  - remove in Phase #2 completion vs keep as read-only facade.

---

## Tasks

### Phase #1 Tasks
- [x] ✅ Production-Ready Map current usage points of `TenantPushSettings` for `push.enabled|throttles|max_ttl_days`.
- [x] ✅ Production-Ready Implement kernel-backed read path for Phase #1 fields in Push services/controllers.
- [x] ✅ Production-Ready Implement kernel-backed write path for Phase #1 fields in existing push settings update flows.
- [x] ✅ Production-Ready Keep endpoint contracts stable while routing persistence to settings kernel.
- [x] ✅ Production-Ready Add/adjust tests proving:
  - Phase #1 fields persist in `settings` root doc under namespace `push`
  - existing push endpoints remain behavior-compatible
  - kernel and push wrappers remain consistent.

### Phase #2 Tasks
- [ ] ⚪ Migrate `firebase` settings reads/writes to kernel.
- [ ] ⚪ Migrate `telemetry` settings reads/writes to kernel.
- [ ] ⚪ Migrate `message_routes` + `message_types` reads/writes to kernel.
- [ ] ⚪ Remove/neutralize remaining legacy settings-only paths in push package.
- [ ] ⚪ Add full regression coverage for all migrated settings endpoints and derived status logic.

---

## Validation Steps
- [x] ✅ Production-Ready Phase #1 targeted tests (push + settings suites) pass.
- [x] ✅ Production-Ready `php artisan test` full Laravel suite in Docker (mandatory at Phase #1 completion).
- [ ] ⚪ Phase #2 targeted tests (including firebase/telemetry/route_types/message_types) pass.
- [ ] ⚪ `php artisan test` full Laravel suite in Docker (mandatory at Phase #2 completion).

---

## Definition of Done
- [x] ✅ Production-Ready Phase #1 complete: `push` namespace core fields are kernel-backed with compatibility maintained.
- [ ] ⚪ Phase #2 complete: all push settings surfaces are kernel-backed and legacy settings coupling removed or explicitly documented.
- [ ] ⚪ Full Laravel suite passes after each phase gate.
- [ ] ⚪ Foundation documentation reflects final migration state.

---

## Decision Log
- `P1-00`: Created to execute migration in two phases, prioritizing low-risk/high-signal kernel adoption first.
