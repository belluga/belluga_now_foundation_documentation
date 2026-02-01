# Documentation: Tenant Admin Screens
**Version:** 1.0

## 1. Overview
This document defines the tenant admin UI surfaces used to manage **Contas**, **Organizações**, and **Tipos de Perfil** for a tenant. The UI is built on top of the `tenant_admin_module.md` contracts and must enforce the domain rules in `domain_entities.md`, especially the bound **Account + Account Profile** creation flow and the **Profile Type Registry**.

**Material 3 mandate:** all tenant-admin screens use Material 3 components, spacing, and navigation. List views use cards + consistent empty states. Forms are grouped into cards with clear section titles.

**Navigation pattern:** 
- Wide layouts use `NavigationRail`.
- Narrow layouts use `NavigationBar`.
- A shared shell AppBar is shown on list screens; full-screen flows (create/edit/detail, map picker) use their own AppBar.

---

## 2. Screens

### 2.1 Tenant Admin Dashboard
**Purpose:** Entry point for tenant admins.  
**Primary actions:**
- Navigate to **Contas** (account + profile bound flow).
- Navigate to **Organizações**.
- Navigate to **Tipos de Perfil** (registry CRUD).

**Constraints:**
- Only **Contas** is exposed as the account/profile entry point; standalone profile screens are not shown.
- Dashboard uses Material 3 cards for quick actions.

---

### 2.2 Contas — List
**Purpose:** List existing tenant accounts with filters and access to create.  
**Primary actions:**
- Create new Conta (bound Account + Profile form).
- Open account detail.

**UI pattern:**
- M3 segmented filter by ownership.
- Card list with leading avatar icon.
- FAB for creation.

---

### 2.3 Contas — Create (Bound Account + Profile)
**Purpose:** Single form to create **Account** and its **Account Profile**.  
**Required fields:**
- Account: name, document (cpf/cnpj).
- Account Profile: profile_type, display_name.
**Optional media fields:**
- Account Profile: avatar (image upload), cover (image upload). Saved as public URLs.

**UI pattern:**
- Material 3 cards for account data, media, and location.
- Single primary "Salvar conta" CTA at the bottom.

**POI-enabled location rule:**
- If the selected `profile_type` has `capabilities.is_poi_enabled=true`, the form must require `location.lat` and `location.lng`.
- When POI-enabled, the UI must expose a **Map Pick** action to select coordinates.

**Map Pick behavior:**
- Opens a map picker with a single pin.
- User taps to place/move the pin; confirm returns `lat/lng`.
- Returned coordinates populate the `location` fields in the form.

**Validation:**
- Block submission if POI-enabled and location is missing.
- Surface inline validation near the location fields.
- Accept image uploads for avatar/cover with size + mime limits defined in the backend.

---

### 2.4 Conta — Detail (Account + Profile)
**Purpose:** Show a single account with its bound profile.  
**Primary actions:**
- If profile exists: Edit profile.
- If profile does not exist: Create profile for the account.

**UI pattern:**
- M3 cards for account summary and profile summary.
- Inline media preview (avatar + cover) when available.

---

### 2.5 Perfil — Edit (Account Profile)
**Purpose:** Edit the profile attached to an account.  
**Primary actions:**
- Update profile type, display name, and location (when POI-enabled).
- Update avatar/cover (auto-save on selection).

**UI pattern:**
- M3 cards for data, images, and location.
- Primary "Salvar alteracoes" CTA at the bottom.

---

### 2.6 Organizações — List / Create / Detail
**Purpose:** Manage optional organization grouping.  
**Primary actions:**
- Create organization.
- View list.
- View detail.

**UI pattern:**
- M3 card list and FAB on list screen.
- M3 card form for create.
- M3 detail card for read-only view.

---

### 2.7 Tipos de Perfil — List / Create / Edit / Delete
**Purpose:** Manage the **Profile Type Registry** for the tenant.  
**Primary actions:**
- Create new type (type, label, allowed_taxonomies, capabilities).
- Edit existing types.
- Delete type after confirmation.

**UI pattern:**
- M3 card list with contextual menu actions.
- M3 card form for create/edit.

**Notes:**
- Registry entries are stored in the tenant collection `account_profile_types`.
- Types control the POI-enabled requirement in the account creation flow.

---

## 3. Data Dependencies
- `tenant_admin_module.md` for REST contracts.
- `domain_entities.md` for Account/Profile relationships and profile type definitions.
- `environment.profile_types` for runtime registry list.

---

## 4. Validation Checklist
- POI-enabled type requires location and Map Pick path works.
- Account/Profile creation remains a single flow (no standalone profile create).
- Profile Type Registry UI is functional end-to-end (list/create/edit/delete).
