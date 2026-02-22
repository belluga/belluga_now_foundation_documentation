# TODO (V1): Tenant Admin Full-Screen Sheet Forms

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-15

## Objective
Adopt a consistent Material 3-aligned admin form pattern where create/edit flows open as full-screen sheet-like surfaces (close affordance, no shell app bar, no bottom nav), while preserving current controller/repository architecture and route contracts.

## Scope
- Replace current admin form chrome (app bars) with a shared full-screen sheet scaffold.
- Keep form flows route-based (not ad-hoc popup state), but animate/present from bottom to emulate full-screen bottom sheet behavior.
- Ensure shell chrome remains hidden for form routes.
- Apply pattern to all tenant-admin create/edit forms.
- Keep form actions, validation, and controller ownership unchanged.

## Decisions
- [x] ✅ Production‑Ready Keep route-based navigation and avoid `showModalBottomSheet` for core CRUD forms.
- [x] ✅ Production‑Ready Use shared scaffold as the single source for full-screen sheet form chrome.
- [x] ✅ Production‑Ready Keep close affordance explicit with safe discard UX parity to current behavior.

## Plan
### Phase 1 — Shared Form Shell
- [x] ✅ Production‑Ready Refactor `TenantAdminFormScaffold` to full-screen sheet visual pattern (handle + title + close action, no app bar).
- [x] ✅ Production‑Ready Ensure keyboard insets and width constraints remain stable.
- [x] ✅ Production‑Ready Ensure form content bottom padding respects SafeArea insets (gesture/nav area) in addition to keyboard insets.

### Phase 2 — Route Presentation
- [x] ✅ Production‑Ready Configure tenant-admin form routes to slide from bottom (sheet-like transition) while preserving route names/contracts.
- [x] ✅ Production‑Ready Regenerate router artifacts.

### Phase 3 — Form Migration Coverage
- [x] ✅ Production‑Ready Apply new scaffold semantics across account/profile/organization/profile-type/static-profile-type/taxonomy/static-asset forms.
- [x] ✅ Production‑Ready Remove redundant per-screen app bar/leading controls now provided by shared scaffold.

### Phase 4 — Validation
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready Run targeted unit/integration tests impacted by form chrome and route transitions.
- [x] ✅ Production‑Ready Add ephemeral regression coverage for shell chrome hiding on root-stack form routes (`integration_test/ephemeral_admin_form_sheet_chrome_test.dart`).
- [x] ✅ Production‑Ready Re-run `flutter-clean-code-audit` and close with clean status or explicit exceptions.

## Definition of Done
- [x] ✅ Production‑Ready All admin create/edit forms follow full-screen sheet pattern consistently.
- [x] ✅ Production‑Ready Form routes open with bottom-up transition and close predictably.
- [x] ✅ Production‑Ready No architecture violations introduced (controllers own state/effects; screens stay UI).
- [x] ✅ Production‑Ready Analyze/tests pass for impacted scope.
