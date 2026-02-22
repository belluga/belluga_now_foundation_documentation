# TODO (V1): Tenant Admin Settings — Hierarchy Strategy Parity Pass

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Realign Admin Settings to the approved **hierarchy strategy** expressed by the reference, while preserving Material 3 (no colored app bar), existing contracts/fields, and the approved multi-screen route strategy.

## Scope
- Flutter (`flutter-app`) presentation-only changes in Tenant Admin Settings.
- Keep multi-screen routes already created; change only the **hub hierarchy/composition**.
- Apply hierarchy strategy (not fixed section mandates):
  - Strong top context/header row (title + tenant context action).
  - Grouped concern cards with clear visual priority and concise preview data.
  - Explicit CTA per concern to open dedicated edit screens.
  - Balanced spacing/radius/typography for fast scan and decision-making.
- CTAs navigate to existing dedicated routes:
  - local-preferences
  - visual-identity
  - technical-integrations
  - environment-snapshot (kept available via action/entry, but not forced as a fourth large hub card).

## Out of scope
- API or domain contract changes.
- Controller ownership changes that affect behavior/contracts.
- Reverting the multi-screen route architecture.

## Decisions
- [x] ✅ Production‑Ready Keep Material 3 neutral app bar (`surface`), no colored app bar.
- [x] ✅ Production‑Ready The reference defines hierarchy strategy, not fixed section contracts.
- [x] ✅ Production‑Ready Dedicated screens remain the full edit surfaces; hub is curated preview + entrypoint.

## Concrete Mapping (Current Elements)
- Hub role:
  - Decision surface only (preview + CTA), not full editing surface.
- Top context/header:
  - Keep title/context row with tenant context action; preserve neutral M3 surface.
- Concern group 1: `Preferências` (high priority, local)
  - Preview/inline controls from existing state:
    - `themeModeStreamValue` segmented control.
    - `maxRadiusMetersStreamValue` value + slider.
  - CTA: `Configurar` → `TenantAdminSettingsLocalPreferencesRoute`.
- Concern group 2: `Identidade Visual` (medium-high, branding)
  - Preview from existing state (no contract change):
    - Primary/secondary seed colors (`brandingPrimarySeedColorController`, `brandingSecondarySeedColorController`) with visual swatches.
    - Optional compact brightness indicator from `brandingBrightnessStreamValue`.
  - CTA: `Configurar` → `TenantAdminSettingsVisualIdentityRoute`.
- Concern group 3: `Integrações Técnicas` (medium, operations)
  - Preview from existing state (compact rows):
    - Firebase summary (project id).
    - Push limits summary.
    - Telemetry summary (`telemetrySnapshotStreamValue` integration/count indicators).
  - CTA: `Configurar` → `TenantAdminSettingsTechnicalIntegrationsRoute`.
- Secondary diagnostics:
  - Keep `Environment Snapshot` as secondary compact entry (not mandatory as a primary large concern card).
  - CTA: `Ver detalhes`/`Configurar` → `TenantAdminSettingsEnvironmentSnapshotRoute`.

## Plan
### Phase 1 — Hub Composition Refactor
- [x] ✅ Production‑Ready Remove current intro card/chips/summary and generic hub navigation cards.
- [x] ✅ Production‑Ready Build a strategy-aligned hierarchy: top context + grouped concern cards + concise previews.
- [x] ✅ Production‑Ready Keep existing values/controls wired to controller streams, without changing contracts.
- [x] ✅ Production‑Ready Apply concrete mapping: `Preferências` inline preview, `Identidade Visual` color preview, `Integrações Técnicas` compact summaries, `Environment Snapshot` secondary entry.

### Phase 2 — Navigation Wiring in Hub
- [x] ✅ Production‑Ready Add explicit `Configurar` CTA per section to navigate to dedicated route.
- [x] ✅ Production‑Ready Ensure no route/contract regression in shell selected tab behavior.

### Phase 3 — Validation
- [x] ✅ Production‑Ready Update settings widget tests for hub expectations.
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`

## Definition of done
- [x] ✅ Production‑Ready Hub visuals follow the approved hierarchy strategy and interaction intent.
- [x] ✅ Production‑Ready Multi-screen strategy remains active (hub + dedicated routes).
- [x] ✅ Production‑Ready No colored app bar introduced.
- [x] ✅ Production‑Ready Existing field behavior/contracts preserved.
- [x] ✅ Production‑Ready Analyzer/tests pass for touched scope.

## Validation steps
1. Open Config (hub) and visually verify hierarchy: top context, grouped concern cards, clear CTA affordance.
2. Tap each `Configurar` CTA and confirm navigation to its dedicated screen.
3. Run analyze and targeted settings tests.
