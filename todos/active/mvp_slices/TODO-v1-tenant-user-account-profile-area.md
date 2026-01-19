# TODO (V1): Tenant User Area — Account + Account Profile Creation
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Deliver the MVP **frontend-only** tenant user area that allows creation of Accounts and Account Profiles, and links profiles to accounts. This enables admin-assigned operators to bootstrap account profile identities (partner label) without full memberships. The UI must respect `ownership_state` and optional Organization grouping.

## Scope
- Document the **tenant user area** flows for:
  - Create Account
  - Create Account Profile and attach it to an Account
  - View existing Accounts/Profiles (basic list/detail)
- Document **Landlord (tenant admin) authentication** for mobile usage.
- Optional: create or select an **Organization** when creating tenant-owned accounts (grouping only).
- Separate listings by `ownership_state` (tenant_owned vs unmanaged vs user_owned).
- Reference required endpoint contracts and payloads (admin/tenant routes) defined in the Account Profile Implementation TODO.
- Document MVP access rules (landlord/tenant admins only; no memberships yet).
- Align with Account Profile implementation TODO.

## Out of Scope
- Full memberships/roles system.
- Self‑serve user onboarding for account operators.
- **User claim flow for unmanaged accounts (post‑MVP).**
- **User-created additional business accounts (post‑MVP).**
- Account workspace dashboards.
- Flutter UI implementation details.

## Definition of Done
- Flows and endpoint contracts for Account + Account Profile creation are documented.
- Access control rules are explicit (admin-assigned in MVP).
- Cross-references to Account Profile implementation are present.
- Mobile admin UI + routing strategy is documented.

## Validation Steps
- Manual doc review: ensure creation flows are documented and match contracts.

## Decisions
- MVP access is admin/tenant only (no memberships).
- Account Profiles must be linked to Accounts at creation.
- Backend endpoint definitions live in `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-implementation.md`.
- Account = 1 Profile; personal profiles are user-owned and created at user creation (no self-serve upgrade in MVP).
- `ownership_state` is the single **conceptual** flag (derived in MVP): `tenant_owned`, `unmanaged`, `user_owned`.
- Organizations are optional; unmanaged accounts must be standalone (no org).
- User claim flow for unmanaged accounts and user-created additional business accounts are **post‑MVP**.
- Tenant Admin is allowed in **mobile** via Landlord User auth.
- Landlord auth is **separate** from tenant/user auth; use independent tokens.
- Landlord login: `POST /admin/api/v1/auth/login` (already in backend).
- Landlord session validation: `GET /admin/api/v1/auth/token_validate`.
- Landlord profile: `GET /admin/api/v1/me`.
- Tenant Admin shell/routes appear **only** when landlord token is active.
- Tenant admin requests still hit `/api/v1/*` but require Landlord abilities + `CheckTenantAccess` (Landlord User is **not** an Account User).
- Mobile supports **mode switching** between user and landlord tokens, but **only one mode is active at a time**.
- Switching modes performs a **full navigation reset** into the target shell (no mixed history).
- UI must clearly indicate the active mode (banner + badge) and allow “Exit Admin Mode”.

## Routing & UI Strategy (Mobile Admin Mode)
- Add a **mode switcher** under profile/settings:
  - `Modo Usuário` (tenant user token)
  - `Modo Admin` (landlord token)
- Persist both tokens, but only one is active; all actions use the active token.
- When admin mode is active:
  - Show a **persistent banner**: “Modo Admin — Tenant X”.
  - Show an **Admin badge** on avatar/header.
  - Route to `TenantAdminShell` (Accounts, Account Profiles, Organizations).
- When user mode is active:
  - Route to `TenantAppShell` (normal tenant experience).
- If landlord token expires or `CheckTenantAccess` fails:
  - Prompt for re‑login; if declined, fall back to user mode.
- Bottom navigation **Menu → Perfil**:
  - If anonymous, `Perfil` shows CTA “Entrar / Criar conta”.
  - Login screen includes a discreet **“Entrar como Admin”** link.
  - “Entrar como Admin” routes to Landlord login (`/admin/api/v1/auth/login`).

## Execution Plan (First Steps)
- [ ] ⚪ Pending Replace bottom nav “Menu” with “Perfil”; wire login/register and **anonymous merge** flow (register merges anonymous profile).
- [ ] ⚪ Pending Wire **Landlord login** (`/admin/api/v1/auth/login`) and session hydration.
- [ ] ⚪ Pending Build **management routing shell** (TenantAdminShell + guards).
- [ ] ⚪ Pending Create routes + wiring for **Account / Account Profile management**.
- [ ] ⚪ Pending Create routes + wiring for **Event management**.

## Questions to Close
- None.

---

## Pending Decisions (to Close Before Implementation)

### D1) MVP Account Profile types
**Decision (Final):** Limit MVP to `artist` and `venue`; defer other types to post‑MVP.

### D2) Account create payload (minimum fields)
**Decision (Final):** use boilerplate payload — `name` + `document { type, number }` required.  
**MVP ownership_state is derived** (no required field in payload).  
No account‑type model in MVP.

### D3) Account Profile create payload (minimum fields)
**Decision (Final):** `account_id`, `profile_type`, `display_name` required.  
- **Conditional:** `location {lat,lng}` is **required** only when `profile_type` is POI‑enabled (`capabilities.is_poi_enabled = true`).  
- **Optional (create or patch):** `taxonomy_terms[]`, `bio`, `avatar_url`, `cover_url`.  
`slug` generated server‑side.

### D4) Operator linkage in MVP
**Decision:** **Deferred to VNext.**  
Track in `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-claim-flow.md`.

### D5) Admin vs tenant route prefix
**Decision (Final):** Use tenant‑scoped `/api/v1/*` routes for tenant management; restrict access to landlord users via abilities.

### D6) Listing/filters for tenant UI
**Decision (Final):**  
- `GET /api/v1/accounts` (no global search in MVP).  
- `GET /api/v1/account_profiles?account_id=...` plus **optional filters**:
  - `profile_type`
  - `origin_lat`, `origin_lng`, `max_distance_meters` (geo‑near filtering)

### D7) Access control for tenant user area
**Decision (Final):** Landlord user only (tenant scope; no memberships yet).

### D8) Validation + error behavior
**Decision (Final):** Required‑field validation errors (`422`); `404` on invalid `account_id`.
