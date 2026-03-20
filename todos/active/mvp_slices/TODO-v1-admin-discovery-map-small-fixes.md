# TODO (V1): Admin + Discovery + Map Small Fixes

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team, Laravel Team  
**Objective:** Fix small-but-blocking MVP issues across Tenant Admin, Discovery, and Map before release freeze.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Scope
- Fix Tenant Admin account/profile search behavior.
- Allow editing account/profile type for unmanaged entities in Tenant Admin.
- Remove hardcoded map icon/color behavior and make visuals configurable (or use generic fallback strategy).
- Fix Discovery list truncation so users can access the full expected dataset.
- Execute a small visual polish pass on affected screens.

## Out of Scope
- New major IA/UX redesign for admin, map, or discovery.
- Net-new MVP capabilities unrelated to these defects.
- Backend schema redesign for map POI core model.

---

## A) Workstream: Admin Search Not Working Properly

### Tasks
- [ ] ⚪ Reproduce and document failing search paths (Account list, Account Profile list/detail selectors).
- [ ] ⚪ Verify request/query propagation from Flutter filters/search to backend endpoints.
- [ ] ⚪ Fix search criteria application and result refresh lifecycle.
- [ ] ⚪ Ensure clear-search restores baseline dataset correctly.

### Acceptance Criteria
- [ ] ⚪ Search by name/identifier returns expected matches.
- [ ] ⚪ Search state is consistent after pagination/reload.
- [ ] ⚪ Empty/loading/error states remain correct while searching.

---

## B) Workstream: Edit Type for Unmanaged Accounts/Profiles

### Tasks
- [ ] ⚪ Define exact eligibility rule for editing type (`unmanaged` only).
- [ ] ⚪ Add edit flow in Tenant Admin UI for unmanaged account/profile type.
- [ ] ⚪ Wire update persistence and refresh list/detail projections after save.
- [ ] ⚪ Keep managed/tenant-owned guardrails explicit in UI and backend validation.

### Acceptance Criteria
- [ ] ⚪ Unmanaged entities can update type successfully.
- [ ] ⚪ Ineligible entities are blocked with clear feedback.
- [ ] ⚪ Updated type is reflected consistently in list/detail/filter surfaces.

---

## C) Workstream: Map Icon/Color Hardcoding Removal

### Tasks
- [ ] ⚪ Identify hardcoded icon/color mappings currently applied in map filter/marker UI.
- [ ] ⚪ Replace key-based hardcoding with configurable source (catalog/settings payload).
- [ ] ⚪ Define fallback strategy when icon/color metadata is absent (generic, non-keyed fallback).
- [ ] ⚪ Ensure runtime refresh reflects admin-config changes without code edits.

### Acceptance Criteria
- [ ] ⚪ Map filter/icon visuals are driven by configuration, not hardcoded category keys.
- [ ] ⚪ Color behavior is consistent and does not regress selected-state contrast.
- [ ] ⚪ Fallback visuals are stable and deterministic.

---

## D) Workstream: Discovery Shows Only Few Items

### Tasks
- [ ] ⚪ Audit current Discovery fetch/pagination limits and identify truncation source.
- [ ] ⚪ Align list loading with canonical dataset expectations (pagination/infinite scroll or complete fetch by contract).
- [ ] ⚪ Ensure filter/search interactions do not silently drop valid items.
- [ ] ⚪ Validate interaction with favorites state and profile-type registry filtering.

### Acceptance Criteria
- [ ] ⚪ Discovery displays full expected dataset for the active query/filter context.
- [ ] ⚪ No silent cap at low item count.
- [ ] ⚪ Scrolling/loading behavior is predictable and stable.

---

## E) Workstream: Visual Improvements (Targeted)

### Tasks
- [ ] ⚪ Apply targeted polish on affected screens (spacing, hierarchy, contrast, state clarity).
- [ ] ⚪ Review selected/unselected visual states in map/discovery actions.
- [ ] ⚪ Review admin form/list visual consistency after functional fixes.

### Acceptance Criteria
- [ ] ⚪ No visual regressions in affected components.
- [ ] ⚪ Interaction states are visually clear.
- [ ] ⚪ Layout remains stable on common mobile breakpoints.

---

## F) Validation and Test Plan
- [ ] ⚪ Add/adjust unit/widget tests for admin search and unmanaged type edit flows.
- [ ] ⚪ Add/adjust tests for discovery completeness/pagination behavior.
- [ ] ⚪ Add/adjust map UI tests for configurable icon/color behavior and fallback path.
- [ ] ⚪ Run targeted regression suite for Home/Discovery/Map/Admin impacted surfaces.

---

## G) Definition of Done
- [ ] ⚪ Admin search works correctly in all affected Tenant Admin flows.
- [ ] ⚪ Unmanaged type edit is available and guarded correctly.
- [ ] ⚪ Map icon/color rendering is configuration-driven (or generic fallback), without hardcoded category coupling.
- [ ] ⚪ Discovery no longer truncates to a small subset unexpectedly.
- [ ] ⚪ Visual polish pass delivered with no regressions.
- [ ] ⚪ Tests updated and passing for touched areas.


# Manual edition fornew issues found #

* Fallback image for events on the card. But not on the Event Screen. We should decide if we will have a fallback or not.