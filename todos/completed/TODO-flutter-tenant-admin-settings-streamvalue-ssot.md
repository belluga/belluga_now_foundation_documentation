# TODO (V1): Tenant Admin Settings SSoT via Repository StreamValue

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Flutter Team  
**Objective:** Ensure tenant-admin settings always load real tenant data and reflect persisted updates by making repository-owned `StreamValue` the single source of truth consumed via controller delegation.

---

## A) Scope
- Establish repository-owned `StreamValue` for tenant branding data used by tenant-admin settings.
- Make settings controller delegate read streams from repository (controller can keep form controllers for editing, but canonical data source must be repository stream).
- On first load in tenant scope, fetch real tenant branding from tenant environment endpoint and publish to repository stream.
- On branding save, persist to backend and rehydrate from tenant environment source (no synthetic/fixed fallback values).
- Update settings hub/visual identity consumers to render data sourced from controller-delegated repository stream.
- Expose request/read failures clearly (do not mask with fallback values pretending success).

## B) Out of Scope
- No UI redesign/theme revamp beyond data-binding changes required for this SSoT flow.
- No backend endpoint contract changes.
- No landlord-wide data model redesign outside tenant-admin settings scope.

## C) Applicable Rules & Workflows
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/workflows/flutter/create-controller-method.md`
- `delphi-ai/workflows/flutter/create-repository-method.md`
- `delphi-ai/workflows/flutter/create-screen-method.md`

## D) Tasks
- [x] ✅ Production‑Ready Update tenant-admin settings repository contract/implementation to expose canonical branding `StreamValue` and publish fetched values to it.
- [x] ✅ Production‑Ready Remove fixed branding color fallback behavior that can mask backend/read errors.
- [x] ✅ Production‑Ready Refactor settings controller to delegate branding stream from repository and apply form hydration from this canonical stream.
- [x] ✅ Production‑Ready Ensure save flow re-fetches tenant environment branding after successful update and updates canonical stream.
- [x] ✅ Production‑Ready Update settings screens/widgets that preview branding to consume controller-delegated canonical stream (instead of stale/local-only values).
- [x] ✅ Production‑Ready Update foundation docs for tenant-admin settings data flow (`foundation_documentation/screens/modulo_tenant_admin.md`) before/with code changes.
- [x] ✅ Production‑Ready Add/adjust focused tests (repository + controller + settings screen expectations) to assert real-load + post-save rehydration behavior without fallback masking.

## E) Definition of Done
- [x] ✅ Production‑Ready First load in tenant-admin settings shows real tenant branding values returned by tenant endpoint.
- [x] ✅ Production‑Ready Save branding persists and UI reflects reloaded persisted values from tenant source.
- [x] ✅ Production‑Ready Canonical tenant branding state lives in repository `StreamValue`; controller exposes delegated stream to UI.
- [x] ✅ Production‑Ready No hardcoded color fallback used to fake successful tenant branding reads.
- [x] ✅ Production‑Ready Targeted tests pass and analyzer is clean.

## F) Validation
- [x] ✅ Production‑Ready `fvm flutter test --reporter expanded test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test --reporter expanded test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [ ] ⚪ Pending Optional device check: `fvm flutter test integration_test/feature_admin_settings_branding_persistence_test.dart -d <adb-device> --flavor guarappari --dart-define=...` (not requested in this pass).

## G) Validation Run Notes
- WSL-safe pre-test cleanup executed by moving `build/test_cache` to:
  - `foundation_documentation/artifacts/tmp/manual-test-cache/test_cache_settings_ssot_20260221_105720`
- All scoped tests and analyzer passed with zero issues.
