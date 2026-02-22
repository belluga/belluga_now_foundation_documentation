# TODO (V1): Landlord Admin Reference Visual Rebuild

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Rebuild landlord/admin visuals from zero using the reference `faisal-kabir/flutter-admin-panel` visual strategy, restricted to landlord scope, with a clearly visible UI change and no contract/field changes.

## Scope
- Apply reference-inspired visual system to landlord/admin scope only:
  - `lib/presentation/tenant_admin/shell/**`
  - `lib/presentation/tenant_admin/settings/**`
  - landlord admin shared widgets under `lib/presentation/tenant_admin/shared/widgets/**` used by these surfaces.
- Re-enable external package usage (`/home/elton/Dev/repos/flutter-packages/belluga_admin_ui`) as source of reusable settings primitives.
- Implement settings multi-screen strategy:
  - hub screen + dedicated views (`local_preferences`, `visual_identity`, `technical_integrations`, `environment_snapshot`).
  - keep same existing fields/contracts and forms.
- Add stable `ValueKey` identifiers for hub hierarchy/actions and update tests to assert keys instead of label text.

## Out of scope
- Any visual change outside landlord/admin (`lib/presentation/tenant_admin/**`).
- Domain/API/repository contract changes.
- New fields or backend behavior changes.

## Decisions
- [x] ✅ Production‑Ready Material 3 only; no colored app bar.
- [x] ✅ Production‑Ready Landlord shell owns admin-scoped visual theme wrapper; tenant app theme remains untouched.
- [x] ✅ Production‑Ready External package path dependency:
  - `belluga_admin_ui -> ../../../flutter-packages/belluga_admin_ui`
- [x] ✅ Production‑Ready Tests for settings hub hierarchy/actions must use `Key` selectors.

## Plan
### Phase 1 — Landlord Visual System Base
- [x] ✅ Production‑Ready Add landlord-admin scoped theme/scaffold treatment in `TenantAdminShellScreen` (header surface, workspace surface, nav surface rhythm).
- [x] ✅ Production‑Ready Keep mobile/desktop behavior intact and avoid tenant-scope leakage.

### Phase 2 — Settings Structure + Multi-Screen Strategy
- [x] ✅ Production‑Ready Refactor `TenantAdminSettingsScreen` into view-driven structure (`hub`, `localPreferences`, `visualIdentity`, `technicalIntegrations`, `environmentSnapshot`).
- [x] ✅ Production‑Ready Add dedicated settings route pages for each view and wire route names in shell/module.
- [x] ✅ Production‑Ready Keep form contracts and existing fields unchanged.

### Phase 3 — External Package Primitives (One Widget Per File)
- [x] ✅ Production‑Ready Ensure package exports and uses:
  - `TenantAdminHubCardShell`
  - `TenantAdminHubActionPill`
  - `TenantAdminHubIntegrationRow`
  - `TenantAdminHubColorHexRow`
  - `TenantAdminSettingsSection`
- [x] ✅ Production‑Ready Update settings hub to consume these package widgets (no local duplicates).

### Phase 4 — Keys + Test Hardening
- [x] ✅ Production‑Ready Add stable keys for hub cards, integration rows, and action buttons.
- [x] ✅ Production‑Ready Update `tenant_admin_settings_screen_test.dart` to assert keys, not labels, for hierarchy/actions.

### Phase 5 — Validation
- [x] ✅ Production‑Ready Run `fvm flutter pub get`.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.

## Definition of done
- [x] ✅ Production‑Ready Landlord shell + settings have clearly different visual hierarchy from the current baseline.
- [x] ✅ Production‑Ready Settings follow hub + dedicated screen strategy without changing contracts/fields.
- [x] ✅ Production‑Ready Reusable settings primitives come from external package path dependency.
- [x] ✅ Production‑Ready Hub hierarchy tests are key-based and pass.
- [x] ✅ Production‑Ready Analyzer and target test suite pass.

## Validation steps
1. Verify `pubspec.yaml` contains `belluga_admin_ui` path dependency to external package.
2. Verify shell/settings visual hierarchy changed materially in landlord scope.
3. Verify dedicated settings routes render expected view segments.
4. Verify key-based test assertions for hub hierarchy/actions.
5. Verify `pub get`, `analyze`, and target tests all pass.
