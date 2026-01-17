# TODO (VNext): Account Workspace (Accounts + Events + Assets)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**MVP Note:** The MVP tenant/admin capability is limited to Account + Account Profile creation (see `foundation_documentation/todos/active/mvp_slices/TODO-v1-tenant-user-account-profile-area.md`). The Account Workspace is post‑MVP.
**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Deliver the Account Workspace (post‑MVP) for managing accounts, events, assets, and tenant branding. MVP only includes the Tenant User Area for Account + Account Profile creation.

---

## References
- `foundation_documentation/modules/partner_admin_module.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- Deferred items: `foundation_documentation/todos/active/TODO-vnext-parking-lot.md`

## Account + Account Profile Context (VNext)
- Account remains generic; Account Profiles are 1:N siblings linked by `account_id`.
- Account Profiles store domain fields like `type`, `tags`, and taxonomy terms (multi‑taxonomy: e.g., Venue can have `cuisines` and `music_genres`).
- Discovery uses an aggregation over Account + Account Profile; admin CRUD remains in the tenant/admin route file.

---

## A) Backend Tasks

### A1) Account access + permissions (Deferred)
- [ ] ⚪ Implement account memberships with roles + permission flags (draft in `foundation_documentation/modules/partner_admin_module.md`)

### A2) Tenant branding management
- [ ] ⚪ Allow tenant admin to edit About, logo, icon, and branding colors.

### A3) Accounts + assets management
- [ ] ⚪ CRUD accounts (including unmanaged accounts).
- [ ] ⚪ CRUD StaticAssets (landlord-managed assets within tenant scope).

### A4) Audit + Unmanaged lifecycle
- [ ] ⚪ Track `created_by` / `updated_by` + `*_by_type` on managed entities.
- [ ] ⚪ Emit `action_audit_log` entries for create/update/delete actions.
- [ ] ⚪ Ensure `is_managed` toggles when account access is granted/removed.

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
- [ ] ⚪ Plan/Limits view (read-only; shows quotas and reset times)

### B3) Event form UX requirements
- [ ] ⚪ Venue selector lists accessible venue account profiles.
- [ ] ⚪ Artist selector lists accessible artist account profiles.
- [ ] ⚪ Both selectors include shortcut to create a new Artist or Venue.

---

## C) Acceptance Criteria

- [ ] ⚪ Account workspace users can manage accounts, assets, and events within permissions.
- [ ] ⚪ Account workspace users can edit branding information (About/logo/icon/colors).
