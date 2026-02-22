# TODO (V1): Tenant Admin Settings — M3 Visual Pass (Noticeable UI Update)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Deliver a clearly noticeable visual redesign for `TenantAdminSettingsScreen` under strict Material 3 rules, while preserving the existing settings fields and backend contracts.

## Scope
- Flutter-only visual/UI updates in `tenant_admin/settings` and shared admin widgets used by that screen.
- Keep **no colored app bar** rule.
- Introduce stronger visual hierarchy using M3 components/tokens:
  - Page heading block with stronger typography and spacing.
  - Section cards with distinct elevation/shape, clearer subheaders, and better separation.
  - Consistent row presentation for editable fields (more obvious tappable/edit affordance).
  - Better visual treatment for branding image slots (preview container + action grouping + slot labels).
  - Improve spacing rhythm and responsive composition so the screen feels intentionally redesigned.
- Preserve all existing fields, edit flows, and repository/controller contracts.

## Out of scope
- Backend/API contract changes.
- New settings domains/fields.
- Navigation IA changes.

## Decisions
- [x] ✅ Production‑Ready Keep top app bar neutral M3 surface; no gradient/colored app bar.
- [x] ✅ Production‑Ready Keep current field-level edit semantics and save actions (no contract or flow behavior changes).
- [x] ✅ Production‑Ready Visual pass targets settings only in this slice; wider admin rollout is follow-up.

## Plan
### Phase 1 — Visual Hierarchy Refresh
- [x] ✅ Production‑Ready Redesign settings page intro/header area for stronger hierarchy.
- [x] ✅ Production‑Ready Apply more distinctive section-card styling and section spacing.

### Phase 2 — Row + Field Visual Improvements
- [x] ✅ Production‑Ready Refine editable row visuals to feel clearly interactive and consistent.
- [x] ✅ Production‑Ready Refine branding image slot visual blocks (labels, preview framing, action placement).

### Phase 3 — Responsive + M3 Consistency
- [x] ✅ Production‑Ready Verify mobile and wide layout spacing/constraints maintain a coherent look.
- [x] ✅ Production‑Ready Ensure all visuals remain M3-compliant and avoid custom colored app bar patterns.

### Phase 4 — Validation
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`

## Definition of done
- [x] ✅ Production‑Ready Settings screen has a visibly updated layout/style that is immediately noticeable.
- [x] ✅ Production‑Ready No colored app bar is introduced.
- [x] ✅ Production‑Ready Existing field contracts/behaviors remain intact.
- [x] ✅ Production‑Ready Analyze and targeted settings tests are green.

## Validation steps
1. Run `fvm flutter analyze`.
2. Run `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`.
3. Manual visual check in settings screen (mobile) confirms noticeable redesign with M3 neutrality.

## Delivery notes
- Added a stronger neutral M3 intro hierarchy block (title + contextual chips) at the top of settings.
- Upgraded settings section cards with iconized headers, stronger shape/border treatment, and clearer spacing rhythm.
- Redesigned editable rows to interactive container rows (ink response + tonal edit affordance) for clearer hierarchy.
- Redesigned branding image slot blocks with framed previews, ratio badges, grouped actions, and slot-level loading overlays.
- Updated settings widget test expectation to account for tenant name appearing in both intro and snapshot contexts.
