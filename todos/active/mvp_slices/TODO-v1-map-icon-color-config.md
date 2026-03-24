# TODO (V1): Map Icon/Color Config-Driven Refactor

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team, Laravel Team
**Objective:** Remove map icon/color hardcoding and establish a configuration-driven visual contract for map filters/markers.
**Promotion lane path:** `dev -> stage -> main`

---

## References
- `foundation_documentation/todos/completed/TODO-v1-admin-discovery-map-small-fixes.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

---

## Scope
- Replace key-based icon/color hardcoding in map marker/filter surfaces.
- Define a canonical source for icon/color metadata (settings/catalog payload).
- Define deterministic fallback behavior when metadata is absent.
- Ensure runtime refresh reflects admin config changes.

## Out of Scope
- Branding color picker modal behavior (already handled in admin/discovery small fixes TODO).
- Broad map UX redesign beyond icon/color architecture.

---

## Promotion Evidence (Required)
| Workstream | Local Branch / Commit | PR to `dev` | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Map Icon/Color Refactor | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |

---

## Tasks
- [ ] ⚪ Identify hardcoded icon/color mappings currently applied in map filter/marker UI.
- [ ] ⚪ Replace key-based hardcoding with configurable source (catalog/settings payload).
- [ ] ⚪ Define fallback strategy when icon/color metadata is absent (generic, non-keyed fallback).
- [ ] ⚪ Ensure runtime refresh reflects admin-config changes without code edits.
- [ ] ⚪ Add/adjust map UI tests for configurable icon/color behavior and fallback path.

## Acceptance Criteria
- [ ] ⚪ Map filter/icon visuals are driven by configuration, not hardcoded category keys.
- [ ] ⚪ Color behavior is consistent and does not regress selected-state contrast.
- [ ] ⚪ Fallback visuals are stable and deterministic.
- [ ] ⚪ Tests cover configured and fallback paths.

## Definition of Done
- [ ] ⚪ Map icon/color rendering is configuration-driven (or generic fallback), without hardcoded category coupling.
