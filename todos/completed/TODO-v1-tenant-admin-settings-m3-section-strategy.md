# TODO (V1): Tenant Admin Settings — M3 Section Strategy + Screen/Form Separation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Establish the canonical Tenant Admin settings architecture pattern to be reused admin-wide, with strict Material 3 behavior and explicit separation of concerns:
- **No colored app bar** (M3 surface top app bar only).
- Section-oriented strategy model (Preferences / Visual Identity / Technical Integrations).
- Screen-only composition; controllers/strategy objects own state, validation, and async orchestration.

## Scope
- Flutter (`flutter-app`) and foundation documentation updates required for this architecture baseline.
- Refactor `tenant_admin/settings` to:
  - Keep M3 top app bar neutral (`surface`) with no gradient/colored header.
  - Introduce reusable section widgets for settings cards/rows/actions aligned with M3 tokens.
  - Move non-ephemeral screen state from `TenantAdminSettingsScreen` into controller-owned `StreamValue` state.
  - Preserve existing backend contracts and field payloads (Firebase, Push, Telemetry, Branding).
- Document this settings pattern as the admin-wide reference in foundation docs.

## Out of scope
- Backend endpoint/contract changes.
- Navigation IA changes outside current admin shell destinations.
- Full migration of every admin module in this slice (this task defines and implements the baseline in Settings first).

## Decisions
- [x] ✅ Production‑Ready Enforce strict Material 3: top app bar must use neutral surface styling; no colored/gradient app bars.
- [x] ✅ Production‑Ready Keep existing settings fields and request/response contracts unchanged.
- [x] ✅ Production‑Ready Use settings as the canonical first implementation; remaining admin modules adopt the same pattern in follow-up slices.

## Plan
### Phase 1 — Documentation Baseline
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_tenant_admin.md` with the M3-only top bar rule and screen/form/controller separation for settings.
- [x] ✅ Production‑Ready Update `foundation_documentation/modules/tenant_admin_module.md` with the admin-wide adoption note (settings as canonical pattern).

### Phase 2 — Settings Architecture Refactor
- [x] ✅ Production‑Ready Refactor `TenantAdminSettingsController` to own UI-operational busy states currently managed by screen-local mutable maps.
- [x] ✅ Production‑Ready Refactor `TenantAdminSettingsScreen` to consume controller-owned streams and keep screen code UI-composition only.
- [x] ✅ Production‑Ready Introduce/standardize reusable M3 settings section widgets under `presentation/tenant_admin/shared/widgets/` and apply to settings sections.

### Phase 3 — M3 Conformance Check
- [x] ✅ Production‑Ready Verify tenant-admin shell app bar path keeps neutral M3 surface styling and no custom colored top app bar is introduced by this slice.
- [x] ✅ Production‑Ready Ensure settings visual hierarchy remains card/section based (not hero/color-header based).

### Phase 4 — Validation
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- [x] ✅ Production‑Ready `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`

## Definition of done
- [x] ✅ Production‑Ready Settings screen follows M3 with no colored app bar usage.
- [x] ✅ Production‑Ready Settings screen no longer owns non-ephemeral operational mutable state (`setState` map for async busy control removed from screen).
- [x] ✅ Production‑Ready Controller owns the settings interaction state through `StreamValue`.
- [x] ✅ Production‑Ready Shared section primitives are used in settings and documented as the admin-wide baseline.
- [x] ✅ Production‑Ready Foundation documentation reflects the new standard.
- [x] ✅ Production‑Ready Analyze/tests in validation steps are green (or blockers are explicitly documented).

## Validation steps
1. Run `fvm flutter analyze`.
2. Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.
3. Run `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`.
4. Manual smoke check: admin settings top app bar remains neutral M3 (surface), no colored header introduced.

## Delivery notes
- Added shared settings section primitive at `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_settings_section.dart`.
- Removed screen-local branding busy map from settings screen and moved operation state to controller `StreamValue<bool>` per branding slot.
- Updated tenant-admin shell app bar to explicit neutral M3 surface styling (`surface`, no tinted/colored top bar styling).
