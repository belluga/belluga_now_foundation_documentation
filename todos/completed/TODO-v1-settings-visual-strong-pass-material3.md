# TODO (V1): Landlord Admin Visual System Adoption (Material 3)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Apply the reference repo visual system across the **landlord/admin scope only** (starting with Settings and shared landlord scaffolds), using Material 3 and preserving existing contracts/fields/routes.

## Scope
- Apply reference-inspired visual language to landlord admin surfaces only:
  - `lib/presentation/tenant_admin/shell/**`
  - `lib/presentation/tenant_admin/settings/**`
  - landlord admin shared widgets/scaffolds under `lib/presentation/tenant_admin/shared/widgets/**` used by admin screens.
- Update reusable package widgets (external `belluga_admin_ui`) used by landlord settings.
- Keep current data fields, contracts, and route split unchanged.
- Enforce clear hierarchy strategy in settings and landlord shell (context header, grouped cards, actionable rows).

## Out of scope
- Any API/domain/controller behavior changes.
- Route additions/removals.
- New form fields or contract changes.
- Any visual/theme/scaffold change in tenant/mobile end-user modules outside `lib/presentation/tenant_admin/**`.

## Decisions
- [x] ✅ Production‑Ready Material 3 only, with `surface*` / `outline*` / `primary` tokens (no custom colored app bar).
- [x] ✅ Production‑Ready Hub keeps existing three strategic groups (Preferências, Identidade Visual, Integrações Técnicas) as strategy anchors, not immutable content mandates.
- [x] ✅ Production‑Ready Package remains source of truth for reusable hub primitives.
- [x] ✅ Production‑Ready Theme/scaffold refactor is restricted to landlord/admin scope; tenant app theme remains untouched.
- [x] ✅ Production‑Ready UI tests should target stable `Key`s for landlord settings hierarchy/actions instead of text labels.

## Plan
### Phase 1 — Landlord Shell + Settings Hierarchy
- [x] ✅ Production‑Ready Apply reference-inspired landlord shell visual scaffold (surface blocks, spacing rhythm, hierarchy) without colored app bar.
- [x] ✅ Production‑Ready Show contextual intro/header in Settings hub view (title/description + tenant context chips).
- [x] ✅ Production‑Ready Increase card emphasis and compositional rhythm in Settings hub (spacing, borders, radii, CTA clarity).
- [x] ✅ Production‑Ready Refine theme segment + map radius block composition for better readability.

### Phase 2 — Package Visual Primitives
- [x] ✅ Production‑Ready Update `TenantAdminHubCardShell` visual density/shape to match strengthened hierarchy.
- [x] ✅ Production‑Ready Update `TenantAdminHubActionPill` and `TenantAdminHubIntegrationRow` for clearer CTA + row rhythm.
- [x] ✅ Production‑Ready Keep `TenantAdminSettingsSection` compatible and aligned to the same visual system.

### Phase 3 — Validation
- [x] ✅ Production‑Ready Run `fvm flutter pub get`.
- [x] ✅ Production‑Ready Run `fvm flutter analyze`.
- [x] ✅ Production‑Ready Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.

## Definition of done
- [x] ✅ Production‑Ready Landlord shell + Settings show clearly different visual hierarchy from prior baseline.
- [x] ✅ Production‑Ready Material 3 mandate respected (no colored app bar introduced).
- [x] ✅ Production‑Ready Existing fields/contracts remain unchanged.
- [x] ✅ Production‑Ready No tenant/mobile module visual regression outside landlord/admin scope.
- [x] ✅ Production‑Ready Analyzer and target settings test pass.

## Validation steps
1. Manually verify landlord shell + settings hierarchy matches reference strategy (without colored app bar).
2. Verify package primitives are the source of settings visual language.
3. Confirm no edits in tenant/mobile non-admin presentation files.
4. Validate `pub get`, `analyze`, and target settings test.

## Outcome notes
- Landlord shell now uses an admin-scoped visual theme and scaffold hierarchy (`surface` containers, dedicated header, workspace surface), with no colored app bar.
- Settings hub now always exposes contextual header + strategic cards with stronger visual rhythm and quick snapshot entry.
- External package primitives were updated (`HubCardShell`, `ActionPill`, `IntegrationRow`, `ColorHexRow`, `SettingsSection`) and propagated to landlord settings.
- Settings hub tests now rely on stable `Key`s for hierarchy/actions instead of label-only assertions.
