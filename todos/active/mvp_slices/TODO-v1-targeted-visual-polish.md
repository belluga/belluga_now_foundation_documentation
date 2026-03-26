# TODO (V1): Targeted Visual Polish (Sign In + Sign Up + Main Profile)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Owners:** Flutter Team
**Objective:** Execute a focused visual polish pass on auth + profile entry surfaces only (`sign in`, `sign up`, main `Perfil`) without changing behavior/contracts.
**Promotion lane path:** `dev -> stage -> main`

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-tenant-user-account-profile-area.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/todos/completed/TODO-v1-admin-discovery-map-small-fixes.md`

---

## Scope
- Apply targeted polish on the `sign in` screen (spacing, hierarchy, contrast, state clarity).
- Apply targeted polish on the `sign up` screen (spacing, hierarchy, contrast, state clarity).
- Apply targeted polish on the main `Perfil` screen (spacing, hierarchy, contrast, state clarity).
- Keep all behavior/contracts unchanged.

## Out of Scope
- Admin/discovery/map polish streams.
- Account Profile area create/manage flows and event-management surfaces.
- Net-new IA/UX redesign.
- Backend contract changes.
- Functional behavior changes unrelated to visual clarity.

---

## Promotion Evidence (Required)
| Workstream | Local Branch / Commit | PR to `dev` | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Visual Polish | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `⚪ Pending` |

---

## Tasks
- [ ] ⚪ Apply targeted polish on the `sign in` screen.
- [ ] ⚪ Apply targeted polish on the `sign up` screen.
- [ ] ⚪ Apply targeted polish on the main `Perfil` screen.
- [ ] ⚪ Run targeted visual regression pass on auth/profile flows.

## Acceptance Criteria
- [ ] ⚪ No visual regressions on `sign in`, `sign up`, and main `Perfil`.
- [ ] ⚪ Interaction states are visually clear on auth/profile surfaces.
- [ ] ⚪ Layout remains stable on common mobile breakpoints for auth/profile surfaces.
- [ ] ⚪ No net-new Account Profile area management/event UI is introduced.

## Definition of Done
- [ ] ⚪ Visual polish pass delivered for `sign in`, `sign up`, and main `Perfil` with no regressions.
