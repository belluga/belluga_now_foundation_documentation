# TODO (VNext): Tenant User Area — Account Profile Area
**Role note (2026-04-18):** this file preserves the V1 scope freeze and historical planning context for the tenant/admin account-profile area. It is not the primary post-MVP owner for account workspace delivery, operator claim flow, or profile-type expansion.
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-01-17

## Objective
Establish the VNext delivery stream for the tenant/admin Account Profile area (account/profile/organization/event-management surfaces), while preserving the MVP boundary where V1 auth/profile work is restricted to the phone-OTP auth lane and the main `Perfil` screen.

## References
- `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-admin-data-layer-rebuild.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`

## Scope
- Document the VNext delivery scope for tenant/admin Account Profile area work.
- Preserve current MVP behavior; no expansion of account/profile/organization management surfaces in V1.
- Align with the current store-release auth/profile lanes: only the phone-OTP auth cutover and main `Perfil` polish are in MVP scope.
- Keep this TODO as the canonical V1 scope-freeze/support note for this area while dedicated VNext owner TODOs carry the deferred implementation fronts.

## Out of Scope
- Tenant/admin Account Profile area implementation in V1.
- New or expanded flows for Accounts, Account Profiles, Organizations, and events under profile/admin area.
- Full memberships/roles system.
- Self‑serve user onboarding for account operators.
- **User claim flow for unmanaged accounts (post‑MVP).**
- **User-created additional business accounts (post‑MVP).**
- Account workspace dashboards.
- Flutter implementation expansion for tenant/admin area in V1.

## Definition of Done
- V1 scope freeze is explicit and consistent with related MVP TODOs.
- VNext ownership is explicit for deferred Account Profile area work.
- No V1 execution is requested for tenant/admin Account Profile area beyond scope documentation.

## Validation Steps
- Manual doc review: ensure no MVP TODO still implies tenant/admin Account Profile area implementation for V1.

## Decisions
- MVP does **not** include tenant/admin Account Profile area delivery.
- MVP tenant-public auth/profile surface is only the phone-OTP auth lane and main `Perfil`.
- MVP access is admin/tenant only (no memberships).
- Account Profiles must be linked to Accounts at creation.
- Backend endpoint definitions live in `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`.
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

## Primary Deferred Owners
- Account workspace and account-profile-area delivery: `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`.
- Operator claim/user-owned expansion: `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`.
- Future profile evolution is capability-first and should open under concrete feature/capability TODOs rather than under a generic profile-type expansion owner.

> The sections below are retained as historical design and contract notes for VNext planning. They are not active MVP implementation scope.

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
  - If anonymous, `Perfil` shows CTA “Entrar”.
  - Login screen includes a discreet **“Entrar como Admin”** link.
  - “Entrar como Admin” routes to Landlord login (`/admin/api/v1/auth/login`).

## Execution Plan (First Steps)
- [x] Done Draft list/detail/create screen flows for Accounts, Account Profiles, and Organizations.
- [x] Done Confirm endpoints, payloads, and access rules against `foundation_documentation/todos/completed/TODO-v1-account-profile-implementation.md`.
- [x] Done Define data model mapping for Account + Account Profile + Organization (UI shapes for list/detail/create).
- [x] Done Replace bottom nav "Menu" with "Perfil"; wire phone-auth entry/verification and anonymous merge flow (verified auth merges anonymous profile).
- [x] Done Add **mode switcher** in Profile/Settings (user/admin) with full navigation reset.
- [x] Done Wire **Landlord login** (`/admin/api/v1/auth/login`) and session hydration (`token_validate`, `me`).
- [x] Done Persist both tokens; ensure only one active at a time; show banner + badge in Admin mode.
- [x] Done Build **TenantAdminShell** with guards (landlord token + `CheckTenantAccess`).
- [x] Done Create admin routes: Accounts list/detail/create.
- [x] Done Create admin routes: Account Profiles list/detail/create (linked to Account).
- [x] Done Create admin routes: Organizations list/create/select (optional on Account creation).
- [x] Done Implement ownership_state filters + segmented lists (tenant_owned, unmanaged, user_owned).
- [x] Done Add empty/error states for each list and create flow validation feedback.
- [x] Done Ensure event management routes are stubbed or hidden until ready.

## Merge Stabilization (tenant-area)
- [x] ✅ Production-Ready Remove legacy auth fallback wrapper in `lib/infrastructure/dal/dao/laravel_backend/auth_backend/auth_backend.dart` (strict contract only).
- [ ] ⚪ Deferred to VNext: Resolve remaining analyzer errors after merging tenant-area into dev.
- [ ] ⚪ Deferred to VNext: Run `fvm flutter analyze` and confirm a clean result.

## UI Data Model Mapping (Tenant Admin UI)

### Account (list/detail/create)
- id: string
- slug: string (routing key)
- name: string
- document: { type: string, number: string }
- ownership_state: tenant_owned | unmanaged | user_owned (derived)
- organization_id: string | null
- created_at: datetime
- updated_at: datetime

### Account Profile (list/detail/create)
- id: string
- account_id: string
- profile_type: string (from registry)
- display_name: string
- slug: string (server-generated, immutable)
- capabilities: { is_favoritable: bool, is_poi_enabled: bool }
- location: { lat: number, lng: number, address?: string, status?: string } | null
- taxonomy_terms: string[] | null
- bio: string | null
- avatar_url: string | null
- cover_url: string | null
- created_at: datetime
- updated_at: datetime

### Organization (list/detail/create)
- id: string
- name: string
- slug: string | null
- created_at: datetime
- updated_at: datetime

### UI Notes
- Account list uses slug for detail routes; show ownership_state segments.
- Account Profile create requires account_id + profile_type + display_name; location required when is_poi_enabled.
- Organization is optional; unmanaged accounts must be standalone (no org).

## Auth + Mode Switching Checklist

- Landlord login UI entrypoint: "Entrar como Admin" links to `/admin/api/v1/auth/login`.
- Session hydration on app start for both tokens; only one is active.
- Token validate: call `/admin/api/v1/auth/token_validate` for landlord sessions.
- Load landlord profile via `/admin/api/v1/me` when admin mode is active.
- Mode switch triggers full navigation reset into TenantAdminShell or TenantAppShell.
- Admin mode UI chrome: persistent banner + admin badge + "Exit Admin Mode" action.
- Guard admin routes by landlord token + `CheckTenantAccess`.
- On landlord token expiry: prompt re-login; if declined, fall back to user mode.

## Screen Flows (Tenant Admin UI)

### Accounts
- List: segmented by ownership_state; tap row -> Account Detail.
- Create: name + document; optional org selection; submit -> Account Detail.
- Detail: show fields + linked Account Profile summary; CTA to create profile if missing.

### Account Profiles
- List: filtered by account_id; show display_name + profile_type; tap -> Profile Detail.
- Create: require account_id, profile_type, display_name; if is_poi_enabled, require location.
- Detail: show core fields; edit basic fields; link back to Account.

### Organizations
- List: show name + slug; tap -> Organization Detail.
- Create: name (required), slug optional; submit -> Organization Detail.
- Detail: show fields; CTA to view linked Accounts (filtered list).

## Admin Routing Shell (TenantAdminShell)

### Route Map
- /admin (root) -> TenantAdminShell (guarded)
- /admin/accounts -> AccountsList
- /admin/accounts/create -> AccountCreate
- /admin/accounts/:accountSlug -> AccountDetail
- /admin/accounts/:accountSlug/profiles -> AccountProfilesList
- /admin/accounts/:accountSlug/profiles/create -> AccountProfileCreate
- /admin/profiles/:accountProfileId -> AccountProfileDetail
- /admin/organizations -> OrganizationsList
- /admin/organizations/create -> OrganizationCreate
- /admin/organizations/:organizationId -> OrganizationDetail

### Shell Rules
- Guard entry by landlord token + CheckTenantAccess.
- On guard failure, redirect to Admin Login (or fallback to user mode if token expired).
- Admin shell owns header/banner, mode badge, and exit action.
- Deep links must respect active mode; if user mode active, prompt switch.

## Empty + Error States (Tenant Admin UI)

### Accounts
- Empty list: show CTA to create account.
- Detail not found: show 404 + back to list.
- Create validation: surface 422 field errors for name/document.

### Account Profiles
- Empty list for account: show CTA to create profile.
- Create validation: 422 errors for missing display_name/profile_type; require location when POI.
- Detail not found: show 404 + back to account detail.

### Organizations
- Empty list: show CTA to create organization (optional step).
- Create validation: 422 errors for missing name.
- Detail not found: show 404 + back to list.

### Auth/Access
- Unauthorized (401/403): show admin login prompt or fallback to user mode.
- Token expired: prompt re-login; if declined, exit admin mode.

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
Track in `foundation_documentation/todos/active/vnext/TODO-vnext-account-claim-flow.md`.

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
