# TODO (V1): Tenant Admin Material 3 Redesign

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter) + Product  
**Objective:** Redesign the entire tenant-admin flow to adhere strictly to Material 3 while preserving all existing functionality.

---

## References
- `foundation_documentation/screens/modulo_tenant_admin.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/domain_entities.md`
- Flutter screens under `flutter-app/lib/presentation/tenant_admin/**`

---

## Scope (MVP)
- Apply Material 3 compliant layouts, widgets, and theming across **all tenant-admin screens**:
  - Accounts list/create/detail (account + profile view).
  - Account profile create/edit.
  - Profile types list/create/edit.
  - Organizations list/create/detail (if present in UI).
  - Admin shell/dashboard navigation.
- Preserve all existing behaviors and flows (CRUD, routing, validation, uploads, map picker).
- Maintain current architecture separation (controllers vs screens), no new business logic in UI.

## Out of Scope
- Functional changes to backend/API.
- New features beyond visual/material compliance.
- Non-admin (tenant) app redesign.

---

## Decisions to Close
- [x] ✅ Production‑Ready Navigation pattern: NavigationRail (wide) + NavigationBar (narrow) with shell AppBar on list routes.
- [x] ✅ Production‑Ready AppBar actions: shell AppBar for list routes; forms use primary FilledButton at the bottom.
- [x] ✅ Production‑Ready Standard form density: Material 3 default spacing with card section headers.
- [x] ✅ Production‑Ready Card/list presentation: sectioned cards for detail + forms, card list for collections.

---

## A) Design System & Layout
- [x] ✅ Production‑Ready Audit all tenant-admin screens for non-M3 widgets/styles.
- [x] ✅ Production‑Ready Apply Material 3 typography, color roles, shapes, elevations.
- [x] ✅ Production‑Ready Standardize Scaffold, AppBar, FAB, and navigation patterns.
- [x] ✅ Production‑Ready Ensure consistent spacing, padding, and section headers.

## B) Screens
- [x] ✅ Production‑Ready Accounts list + account detail (profile embedded view) redesigned.
- [x] ✅ Production‑Ready Account create (with embedded profile create) redesigned.
- [x] ✅ Production‑Ready Account profile create/edit redesigned with image pickers and map picker.
- [x] ✅ Production‑Ready Profile types list/create/edit redesigned.
- [x] ✅ Production‑Ready Organizations list/create/detail redesigned (if in admin flow).
- [x] ✅ Production‑Ready Admin shell/dashboard redesigned.

## C) UX States
- [ ] 🟡 Provisional Loading, empty, and error states standardized with M3 components.
  - Provisional notes: Verified in accounts, profile types, organizations, and location picker. Needs manual review of every admin screen.
- [ ] 🟡 Provisional Confirmation and success feedback standardized (SnackBar/Dialogs).
  - Provisional notes: Existing SnackBar usage preserved; confirm consistency during manual smoke.

## D) Documentation
- [x] ✅ Production‑Ready Update `foundation_documentation/screens/modulo_tenant_admin.md` to reflect new UI patterns.

---

## Validation Steps
- [ ] ⚪ Run `fvm flutter analyze` (must be clean).
- [ ] ⚪ Manual smoke: navigate through every admin screen and verify all actions still work.

---

## Definition of Done
- [ ] ⚪ All tenant-admin screens fully aligned with Material 3 guidelines.
- [ ] ⚪ No regression in admin functionality.
- [ ] ⚪ Documentation updated for tenant-admin screens.
- [ ] ⚪ `fvm flutter analyze` clean.
