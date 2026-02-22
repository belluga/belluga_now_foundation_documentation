# Documentation: Tenant Admin Screens
**Version:** 1.0

## 1. Overview
This document defines the tenant admin UI surfaces used to manage **Contas**, **Organizações**, and **Tipos de Perfil**, and **Taxonomias** for a tenant. The UI is built on top of the `tenant_admin_module.md` contracts and must enforce the domain rules in `domain_entities.md`, especially the bound **Account + Account Profile** creation flow and the **Profile Type Registry**, and the **Taxonomy Registry**.

**Material 3 mandate:** all tenant-admin screens use Material 3 components, spacing, and navigation. List views use cards + consistent empty states. Forms are grouped into cards with clear section titles.
**Top AppBar rule:** tenant-admin list/shell app bars must stay neutral Material 3 surfaces (`surface`) with standard elevation behavior; no colored/gradient app bars.

**Navigation pattern:** 
- Wide layouts use `NavigationRail`.
- Narrow layouts use `NavigationBar`.
- A shared shell AppBar is shown on list screens; full-screen flows (create/edit/detail, map picker) use their own AppBar.

**Screen/Form separation rule (admin baseline):**
- Screens compose layout/sections/navigation only.
- Controllers own mutable state, validation, async orchestration, and operation-busy `StreamValue`s.
- Field edits happen via bottom sheets or full-screen forms; API contracts remain unchanged.

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
  - Image sources: **Do dispositivo** or **Da web (URL)**.
  - UX rule: both sources must show a busy/loading indicator during ingestion and then open the same crop flow:
    - Avatar crop: fixed 1:1
    - Cover crop: fixed 16:9
  - URL ingestion rule: the client must not persist the URL; it must ingest bytes via the backend proxy and then upload the resulting file as usual.
**Taxonomias:**
- Select taxonomy terms from the registry (no free text).
- Term options are filtered by `applies_to=account_profile` **and** the selected profile type's `allowed_taxonomies`.

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
  - Image sources: **Do dispositivo** or **Da web (URL)** with the same busy indicator + crop UX described in **2.3**.
**Taxonomias:**
- Edit taxonomy terms from the registry (no free text).
- Term options are filtered by `applies_to=account_profile` **and** the selected profile type's `allowed_taxonomies`.

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

### 2.8 Taxonomias — List
**Purpose:** Manage taxonomy registry entries that classify Account Profiles, Static Assets, and Events.  
**Primary actions:**
- Create taxonomy (slug, name, applies_to, icon, color).
- Edit taxonomy.
- Delete taxonomy.
- Open **Terms** for a selected taxonomy.

**UI pattern:**
- M3 card list with contextual menu actions.
- M3 card form for create/edit.
- Link or CTA to **Terms** list (next screen).

**Notes:**
- Taxonomy entries are stored in `taxonomies`.
- `icon` is a Material icon name string.
- `color` is HEX `#RRGGBB`.

---

### 2.9 Taxonomias — Terms (for a taxonomy)
**Purpose:** Manage terms for a selected taxonomy.  
**Primary actions:**
- Create term (slug, name).
- Edit term.
- Delete term.

**UI pattern:**
- M3 card list with contextual menu actions.
- M3 card form for create/edit.

**Notes:**
- Terms are stored in `taxonomy_terms` and scoped to a taxonomy via `taxonomy_id`.

---
### 2.10 Configurações — Canonical Pattern Baseline
**Purpose:** Establish the reusable admin-wide interaction baseline with strict M3 styling and controller-owned state.

**UI pattern:**
- Local preferences, visual identity, technical integrations, and environment snapshot are rendered as explicit section cards.
- **Hub (`/admin/settings`) is summary + navigation only**: cards show concise read-only status and entrypoints.
- Hub cards do **not** render edit-form controls (segmented controls, sliders, editable field shells) and do **not** use section-level `Configurar` pill buttons.
- Sections are UI composition only; operation state (submitting/loading/busy by field/slot) is controller-owned.
- No colored top app bar accents are used in settings; hierarchy comes from M3 typography, spacing, and card sections.
- Navigation affordance in the hub is card/row tap (or chevron), keeping a single action hierarchy per section.

**Route strategy (multi-screen):**
- `/admin/settings` → Hub de Configurações (navegação + resumo).
- `/admin/settings/local-preferences` → Preferências locais.
- `/admin/settings/visual-identity` → Identidade visual (branding).
- `/admin/settings/technical-integrations` → Integrações técnicas.
- `/admin/settings/environment-snapshot` → Snapshot do environment (somente leitura).

**Interaction pattern:**
- Mutable fields in edit contexts open field-edit sheets where appropriate.
- Long operations (e.g., media ingestion/upload) expose busy states from controller streams and disable only affected actions.
- Existing backend payloads/contracts for Firebase, Push, Telemetry, and Branding are preserved.
- Branding seed colors in Settings use a proper color picker UI (not free text only), while controller/repository contracts keep `#RRGGBB` values.
- Settings branding state is canonicalized in repository-owned `StreamValue` (single source of truth); controllers delegate this stream to screens/widgets and only hydrate form controllers from canonical updates.
- Branding save flow must re-read persisted tenant branding from tenant environment endpoint (`{tenant-domain}/api/v1/environment`) and republish the canonical stream; post-save UI reflection cannot depend on local echo/fallback values.
- Branding read failures must remain explicit (error state/banner) and must not be masked by hardcoded/default seed colors.
- Tenant-admin scoped theme must derive primary/secondary accents from Environment `theme_data_settings` (same source used by tenant theme), with landlord-only surface/layout overrides.
- Selected tenant context is owned by a shared repository contract (`TenantAdminSelectedTenantRepositoryContract`) so any tenant-admin controller can consume the same selected-tenant state and derived tenant origin/base URL.
- Detail routes must retain explicit parent-child context (`Configurações > subseção`) in shell header hierarchy.
- For non-root list routes that keep shell chrome, header must expose breadcrumb context and a back affordance on mobile to return to the previous in-shell page.
- Settings subroutes (`/admin/settings/*`, except hub) should hide the shell global header and render a scoped section app bar (section title + back) inside the content surface.

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





## 5. Canonical Account Sync Rule
- Account entity state for tenant-admin list/detail/form flows must come from repository canonical `StreamValue` streams.
- Account edit/create forms issue repository commands only; they must not manually synchronize canonical account state between controllers.
- Detail screens derive account state from repository watch streams (prefer stable `id`; allow slug fallback only while identity is unresolved).
- Account updates must propagate to list/detail consumers without forced full reload.
