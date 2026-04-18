# TODO (VNext): Account Workspace (Accounts + Events + Assets)

**Authority note (2026-04-17):** this TODO is the single deferred owner for authenticated account-workspace delivery referenced by the V1 web-to-app policy. Post-MVP workspace event management, memberships/team management, and invite metrics dashboards should be tracked here rather than duplicated in `TODO-vnext-parking-lot.md`.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**MVP Note:** The MVP tenant/admin capability is limited to Account + Account Profile creation (see `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`). The Account Workspace is post‑MVP.
**Future workspace constraint:** current invite contracts already freeze `Account Profile` as the canonical recipient surface. Workspace memberships/permissions must layer acting-user authority on top of that identity instead of redefining invite ownership around raw `User`.
**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Deliver the Account Workspace (post‑MVP) for managing accounts, events, assets, tenant branding, memberships/team management, and workspace-scoped operational dashboards such as invite metrics. MVP only includes the Tenant User Area for Account + Account Profile creation.

---

## References
- `foundation_documentation/modules/account_workspace_module.md`
- `foundation_documentation/todos/completed/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`

## Account + Account Profile Context (VNext)
- Account remains generic; Account Profiles are **1:1** with Accounts (single profile per account).
- Account Profiles store domain fields like `profile_type`, `tags`, and taxonomy terms (multi‑taxonomy: e.g., Venue can have `cuisines` and `music_genres`).
- Discovery uses an aggregation over Account + Account Profile; admin CRUD remains in the tenant/admin route file.

---

## A) Backend Tasks

### A1) Account access + permissions (Deferred)
- [ ] ⚪ Implement account memberships with roles + permission flags (draft in `foundation_documentation/modules/account_workspace_module.md`)
- [ ] ⚪ Expose tenant workspace team-management flows on top of those memberships/permission contracts.
- [ ] ⚪ Future workspace constraint: workspace permissions must preserve `Account Profile` as the canonical invite recipient surface and must not collapse recipient identity into raw `User`.
- [ ] ⚪ Future workspace constraint: do not assume invite response authority is identical to invite issuance authority; memberships may need a distinct permission for responding on behalf of an Account Profile.

### A2) Tenant branding management
- [ ] ⚪ Allow tenant admin to edit About, logo, icon, and branding colors.

### A2.1) Temporary Static POIs (VNext)
- [ ] ⚪ Add `is_temporary` and date range fields for Static Assets.
- [ ] ⚪ Background job toggles `is_active` based on the date window.
- [ ] ⚪ Map queries filter by `is_active` only (no time logic at query time).
- [ ] ⚪ MVP fallback: `is_active` managed manually.

### A3) Accounts + assets management
- [ ] ⚪ CRUD accounts (including unmanaged accounts).
- [ ] ⚪ CRUD StaticAssets (landlord-managed assets within tenant scope).

### A4) Audit + Unmanaged lifecycle
- [ ] ⚪ Track `created_by` / `updated_by` + `*_by_type` on managed entities.
- [ ] ⚪ Emit `action_audit_log` entries for create/update/delete actions.
- [ ] ⚪ Ensure `ownership_state` is enforced (`tenant_owned`, `unmanaged`, `user_owned`) with clear transitions.

---

## B) Flutter Tasks

### B1) Account Workspace navigation entry
- [ ] ⚪ Provide a Web Authenticated entrypoint for tenant/admin mode (landlord user / tenant admin)
- [ ] ⚪ Gate with appropriate auth/role guard

### B2) Workspace pages (minimum)
- [ ] ⚪ Tenant/Admin Home
- [ ] ⚪ Accounts management (list + create + edit)
- [ ] ⚪ Assets management (StaticAssets CRUD)
- [ ] ⚪ Events management (create/edit/delete)
- [ ] ⚪ Tenant branding management (About/logo/icon/colors)
- [ ] ⚪ Invite metrics / operational dashboards for authenticated workspace users.
- [ ] ⚪ Plan/Limits view (read-only; shows quotas and reset times)

### B3) Event form UX requirements
- [ ] ⚪ Venue selector lists accessible venue account profiles.
- [ ] ⚪ Artist selector lists accessible artist account profiles.
- [ ] ⚪ Both selectors include shortcut to create a new Artist or Venue.

---

## C) Acceptance Criteria

- [ ] ⚪ Account workspace users can manage accounts, assets, and events within permissions.
- [ ] ⚪ Account workspace users can edit branding information (About/logo/icon/colors).
- [ ] ⚪ Account workspace users can access team-management and invite-metrics surfaces under the same authenticated workspace authority.
