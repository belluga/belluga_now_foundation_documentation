# TODO (V1): Tenant Admin Settings — Multi-Screen Strategy (Hub + Dedicated Flows)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Establish the missing multi-screen strategy for Admin Settings using a clear hub-and-subscreens architecture, while preserving existing fields/contracts and strict Material 3 rules (no colored app bar).

## Scope
- Flutter (`flutter-app`) + foundation documentation updates.
- Introduce a settings hub screen and route-first split:
  - `Settings Hub` (navigation + summary cards)
  - `Local Preferences Screen`
  - `Visual Identity Screen` (branding)
  - `Technical Integrations Screen` (firebase/push/telemetry)
  - `Environment Snapshot Screen` (read-only)
- Keep current payloads/contracts unchanged.
- Keep controller-owned state and no screen-owned business logic.
- Keep neutral M3 app bars and section/card hierarchy.

## Out of scope
- Backend API/contract changes.
- New settings fields/domains.
- Changes outside tenant-admin settings routing and related presentation/controller wiring.

## Decisions
- [x] ✅ Production‑Ready Multi-screen strategy will be route-driven (not tabs inside a single long screen).
- [x] ✅ Production‑Ready Existing settings operations are reused; no contract or endpoint changes.
- [x] ✅ Production‑Ready Hub screen is the entrypoint; legacy all-in-one screen will be retired/redirected.

## Plan
### Phase 1 — Documentation Baseline
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_tenant_admin.md` with settings route map + hub/subscreen responsibilities.
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/tenant_admin_module.md` with settings route decomposition and ownership model.

### Phase 2 — Route + Screen Decomposition
- [x] ✅ Production‑Ready Add settings sub-routes and route pages for hub + dedicated screens.
- [x] ✅ Production‑Ready Split current settings UI into dedicated screens with shared section widgets.
- [x] ✅ Production‑Ready Ensure shell route still points to the settings hub as primary destination.

### Phase 3 — Controller/State Wiring
- [x] ✅ Production‑Ready Keep settings controller as state owner; each screen consumes only relevant streams/controllers.
- [x] ✅ Production‑Ready Remove residual coupling from old monolithic screen composition.

### Phase 4 — Validation
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- [x] ✅ Production‑Ready Add/update screen tests for the settings hub and at least one dedicated subscreen.

## Definition of done
- [x] ✅ Production‑Ready Settings follows an explicit multi-screen strategy (hub + dedicated flows).
- [x] ✅ Production‑Ready No colored app bar introduced.
- [x] ✅ Production‑Ready Existing field behavior/contracts preserved.
- [x] ✅ Production‑Ready Analyzer/tests pass for touched scope.

## Validation steps
1. Run `fvm flutter analyze`.
2. Run updated settings widget tests.
3. Manual navigation smoke check: `Config` opens hub and allows navigation to each dedicated settings screen.
