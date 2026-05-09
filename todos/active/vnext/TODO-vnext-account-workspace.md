# Title
Account Workspace (Accounts + Events + Assets)

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

**Authority note (2026-04-17):** this TODO is the single deferred owner for authenticated account-workspace delivery referenced by the V1 web-to-app policy. Post-MVP workspace event management, memberships/team management, and invite metrics dashboards should be tracked here rather than duplicated in `TODO-vnext-parking-lot.md`.

## Context
The full Account Workspace is explicitly post-MVP. The current MVP tenant/admin capability is limited to Account + Account Profile creation (see `foundation_documentation/todos/active/vnext/TODO-vnext-tenant-user-account-profile-area.md`). Current invite contracts already freeze `Account Profile` as the canonical recipient surface, so future workspace memberships/permissions must layer acting-user authority on top of that identity instead of redefining invite ownership around raw `User`.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this TODO keeps one durable owner for post-MVP workspace delivery instead of scattering the boundary across parking-lot notes or adjacent account/profile TODOs.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the workspace boundary is already explicit and deferred; this file exists to preserve that owner until implementation becomes current.

## Contract Boundary
- This TODO defines the deferred workspace delivery boundary for accounts, events, assets, branding, memberships/team management, and workspace-scoped dashboards.
- Adjacent deferred TODOs such as account claim flow or future capability-specific profile evolution may evolve in parallel, but they do not replace this TODO as the primary owner for authenticated account-workspace delivery.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Planning-Ready`, `Deferred-Owner`
- **Next exact step:** keep this TODO as the single post-MVP workspace owner and open dedicated execution slices only when workspace delivery becomes current.

## Scope
- [ ] Define workspace access/permissions and team-management boundaries.
- [ ] Define workspace-owned account, asset, event, and branding operations.
- [ ] Define the authenticated workspace navigation/pages and dashboard expectations.
- [ ] Preserve the MVP boundary: Account Workspace remains post-MVP and must not absorb current store-release scope.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `foundation_documentation:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Account Workspace deferred program owner | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] MVP tenant/admin Account + Account Profile creation flows that already belong to the current V1 boundary.
- [ ] User claim/ownership transition logic owned by `TODO-vnext-account-claim-flow.md`.
- [ ] Generic profile-type expansion is not a separate deferred owner anymore; future expansion should be capability-first and tied to concrete feature TODOs.
- [ ] Forcing all workspace child capabilities into one execution/approval cycle.

**Owners:** Backend Team + Delphi (Flutter)  
**Objective:** Deliver the Account Workspace (post‑MVP) for managing accounts, events, assets, tenant branding, memberships/team management, and workspace-scoped operational dashboards such as invite metrics. MVP only includes the Tenant User Area for Account + Account Profile creation.

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

## Definition of Done

- [ ] ⚪ Account workspace users can manage accounts, assets, and events within permissions.
- [ ] ⚪ Account workspace users can edit branding information (About/logo/icon/colors).
- [ ] ⚪ Account workspace users can access team-management and invite-metrics surfaces under the same authenticated workspace authority.
