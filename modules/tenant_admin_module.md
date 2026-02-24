# Documentation: Tenant Administration Module

**Version:** 0.1 (Placeholder)  
**Date:** February 28, 2025  
**Authors:** Delphi (Belluga Co-Engineering)

## 1. Purpose

Placeholder for the Tenant Administration interface (`tenant_admin` main scope) where city governments or enterprise tenants manage account profile onboarding, plan assignments, and high-level analytics. In V1, this interface is accessed on tenant domains and guarded by landlord identity principal. This document will be expanded after the tenant-facing app modules are finalized, ensuring admin capabilities align with real consumer workflows.

## 2. Intended Responsibilities

1. **Account Profile Lifecycle Management:** Approve/reject account profile applications, assign plan tiers, manage verification flags.
2. **Account Profile Analytics Overview:** Monitor account profile performance (invites, attendance, revenue) using aggregate data from Account Profile Analytics.
3. **Tenant Configuration:** Define map regions, featured campaigns, rule sets for the Tenant Home Composer, and policy settings (invite quotas, suppression rules).
4. **Compliance & Auditing:** View audit trails (invite Fulfillment steps, attendance confirmations) and respond to data-access requests.
5. **Government/Institutional Reporting:** Generate reports for city stakeholders (tourism impact, local business engagement, account profile mix).

### 2.1 Scope/Subscope Ownership (Authoritative)
- Canonical governance source:
  - `foundation_documentation/policies/scope_subscope_governance.md`
- Primary ownership:
  - `EnvironmentType`: `tenant`
  - main scope: `tenant_admin`
- Secondary touchpoint:
  - subscope: `account_workspace` (explicit transitions only; no ownership transfer of tenant-admin routes).

### 2.2 Route/Subscope Matrix
| Route | Host Context | EnvironmentType | Main Scope | Subscope | Notes |
|---|---|---|---|---|---|
| `/admin` (tenant domain) | Tenant | `tenant` | `tenant_admin` | n/a | Canonical tenant-admin home. |
| `/admin/*` child routes | Tenant | `tenant` | `tenant_admin` | n/a | Tenant-admin modules (accounts/org/catalog/assets/settings). |
| `/workspace` | Tenant | `tenant` | `tenant_public` | `account_workspace` | Adjacent subscope; not a tenant-admin route. |
| `/workspace/{account_slug}` | Tenant | `tenant` | `tenant_public` | `account_workspace` | Account-scoped workspace mode. |

## 3. Flutter Route Map (V1)

Tenant Admin now runs as a landlord-authenticated shell on tenant domains, with tenant selection gating before feature routes are rendered.

### 3.0 Canonical Scope Boundary (Main Scopes + Subscopes)
- `EnvironmentType` is binary (`landlord | tenant`) and is not expanded by UI subscopes.
- `tenant_admin` belongs to tenant environment and is resolved by tenant host/subdomain.
- Canonical main scope routes:
  - landlord host: `/` (`site_public`), `/admin` (`landlord_area`)
  - tenant host: `/` (`tenant_public`), `/admin` (`tenant_admin`)
- `account_workspace` is a tenant-environment subscope outside `tenant_admin`:
  - `/workspace`
  - `/workspace/{account_slug}`
- Historical host-aware paths (URL normalization policy):
  - landlord host: `/home` and `/landlord` normalize to `/admin`
  - tenant host: `/home` and `/landlord` normalize to `/`
- Landlord -> tenant transition is redirect-link based in this phase:
  - source: landlord-area tenant list (`landlord` identity principal)
  - target: tenant-domain `/admin`
  - cross-domain login reuse is not required; tenant-domain landlord login fallback is allowed.

### 3.1 Shell Entry

- `/admin` → `TenantAdminShellRoute` (guarded by `LandlordRouteGuard`)
- Initial child: `TenantAdminDashboardRoute`

### 3.2 Top-Level Navigation Groups

- `Início` → dashboard route (`/admin`)
- `Accounts` → accounts + account profile + organizations routes
- `Catálogo` → profile types + static profile types + taxonomies routes
- `Ativos` → static assets routes
- `Config` → settings route

### 3.3 Child Routes

- `/admin/accounts`
- `/admin/accounts/create`
- `/admin/accounts/location-picker`
- `/admin/accounts/:accountSlug`
- `/admin/accounts/:accountSlug/profiles/create`
- `/admin/accounts/:accountSlug/profiles/:accountProfileId/edit`
- `/admin/organizations`
- `/admin/organizations/create`
- `/admin/organizations/:organizationId`
- `/admin/profile-types`
- `/admin/profile-types/create`
- `/admin/profile-types/:profileType/edit`
- `/admin/static_profile_types`
- `/admin/static_profile_types/create`
- `/admin/static_profile_types/:profileType/edit`
- `/admin/taxonomies`
- `/admin/taxonomies/create`
- `/admin/taxonomies/:taxonomyId/edit`
- `/admin/taxonomies/:taxonomyId/terms`
- `/admin/taxonomies/:taxonomyId/terms/create`
- `/admin/taxonomies/:taxonomyId/terms/:termId/edit`
- `/admin/static_assets`
- `/admin/static_assets/create`
- `/admin/static_assets/:assetId/edit`
- `/admin/settings`
- `/admin/settings/local-preferences`
- `/admin/settings/visual-identity`
- `/admin/settings/technical-integrations`
- `/admin/settings/environment-snapshot`

### 3.4 Mobile UX Rules

- List/shell routes keep admin chrome (app bar + nav bar).
- Create/edit/detail routes run in focused full-screen mode on mobile (shell chrome hidden).
- If only one tenant is available, selection is automatic and the tenant-change affordance is hidden.

### 3.5 Admin UI Architecture Baseline (Settings-first Canonical Pattern)

- **Material 3 top app bar policy:** tenant-admin shell/list app bars use neutral M3 surface styling (no colored/gradient top bars).
- **Separation of concerns policy:**
  - Screens compose sections, layout, and navigation.
  - Controllers own mutable state (`StreamValue`), validation, and async orchestration.
  - Form interactions use field-edit sheets or full-screen forms; widgets/screens do not own operational business state.
- **Adoption policy:** `/admin/settings` is the canonical first implementation of this pattern, and all admin modules (Accounts, Ativos, Taxonomias, Tipos, Organizações) must adopt the same structure in subsequent slices while keeping existing contracts stable.

### 3.6 Settings Multi-Screen Strategy (Hub + Dedicated Flows)

- `/admin/settings` is the **Settings Hub** entrypoint.
- Dedicated settings routes:
  - `/admin/settings/local-preferences` → local device preferences (theme + map radius)
  - `/admin/settings/visual-identity` → branding/visual identity
  - `/admin/settings/technical-integrations` → firebase/push/telemetry
  - `/admin/settings/environment-snapshot` → read-only environment diagnostics
- The settings controller remains the state owner; each settings screen consumes only the relevant state slices and actions.

### 3.7 Selected Tenant State Ownership (Shared Repository)

- Tenant-admin tenant context is owned by a dedicated shared repository:
  - `TenantAdminSelectedTenantRepositoryContract`
- This repository is the canonical source of truth for:
  - available tenant options
  - selected tenant domain
  - selected tenant option object
  - derived tenant-admin base URL for tenant-scoped calls
- `TenantAdminShellController` hydrates this repository from bootstrap tenant hints and landlord tenant listing.
- Any tenant-admin controller that needs tenant context should read it from the shared repository (directly or via compatibility contract).
- Compatibility rule: `TenantAdminTenantScopeContract` remains available as an adapter over the same shared state during migration, so existing repositories/controllers keep behavior without state duplication.
- Tenant-scoped reads/writes must resolve origin from selected tenant scope only; landlord environment fallback is not valid inside tenant-admin scope.

## 4. API Endpoint Definitions

**Scope note:** All endpoints in this module live under `/admin/api/v1` on **tenant domains** and are guarded by `tenant` + `landlord` middleware. This shares the `/admin` prefix with landlord admin routes but does **not** overlap in scope or domain.

### `GET /admin/api/v1/organizations`
List organizations for the tenant.

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "slug": "string",
      "description": "string?",
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /admin/api/v1/organizations`
Create an organization (grouping only in MVP).

**Request Schema**
```json
{
  "name": "string",
  "description": "string?"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `GET /admin/api/v1/organizations/{organization_id}`
Fetch organization detail.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/organizations/{organization_id}`
Update organization (MVP: name/description only).

**Request Schema**
```json
{
  "name": "string",
  "description": "string?"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `DELETE /admin/api/v1/organizations/{organization_id}`
Soft delete organization.

**Response Schema**
```json
{}
```

### `POST /admin/api/v1/organizations/{organization_id}/restore`
Restore organization.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "description": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /admin/api/v1/organizations/{organization_id}/force_delete`
Force delete organization.

**Response Schema**
```json
{}
```

### `GET /admin/api/v1/accounts`
List accounts (tenant-owned + unmanaged + user-owned visibility per admin rules).

**Query Params**
- `page` (optional, integer, default backend value)
- `page_size` (optional, integer, default backend value)
- `ownership_state` (optional): `tenant_owned|unmanaged|user_owned`

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "slug": "string",
      "document": {
        "type": "cpf|cnpj",
        "number": "string"
      },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "organization_id": "string?",
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```
**Notes:** `ownership_state` is derived from account ownership invariants and is required in read responses.

### `POST /admin/api/v1/accounts`
Create an account (tenant admin).

**Request Schema**
```json
{
  "name": "string",
  "document": {
    "type": "cpf|cnpj",
    "number": "string"
  },
  "ownership_state": "tenant_owned|unmanaged"
}
```
`user_owned` is not allowed in this admin create flow.

**Response Schema**
```json
{
  "data": {
    "account": {
      "id": "string",
      "name": "string",
      "slug": "string",
      "document": {
        "type": "cpf|cnpj",
        "number": "string"
      },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "organization_id": "string?",
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    },
    "role": {
      "id": "string",
      "name": "Admin",
      "slug": "admin",
      "permissions": ["*"]
    }
  }
}
```

### `GET /admin/api/v1/accounts/{account_slug}`
Fetch account detail.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "organization_id": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/accounts/{account_slug}`
Update account metadata (name/document only in MVP).

**Request Schema**
```json
{
  "name": "string",
  "document": {
    "type": "cpf|cnpj",
    "number": "string"
  },
  "ownership_state": "tenant_owned|unmanaged?"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "organization_id": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `DELETE /admin/api/v1/accounts/{account_slug}`
Soft delete account.

**Response Schema**
```json
{}
```

### `POST /admin/api/v1/accounts/{account_slug}/restore`
Restore account.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "name": "string",
    "slug": "string",
    "document": {
      "type": "cpf|cnpj",
      "number": "string"
    },
    "organization_id": "string?",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /admin/api/v1/accounts/{account_slug}/force_delete`
Force delete account.

**Response Schema**
```json
{}
```

### `GET /admin/api/v1/account_profile_types`
List profile type registry for the tenant.

**Response Schema**
```json
{
  "data": [
    {
      "type": "string",
      "label": "string",
      "map_category": "string",
      "allowed_taxonomies": ["string"],
      "capabilities": {
        "is_favoritable": true,
        "is_poi_enabled": false
      }
    }
  ]
}
```

### `POST /admin/api/v1/account_profile_types`
Create a profile type registry entry (tenant admin).

**Request Schema**
```json
{
  "type": "string",
  "label": "string",
  "map_category": "string?",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_favoritable": true,
    "is_poi_enabled": false
  }
}
```

**Response Schema**
```json
{
  "data": {
    "type": "string",
    "label": "string",
    "map_category": "string",
    "allowed_taxonomies": ["string"],
    "capabilities": {
      "is_favoritable": true,
      "is_poi_enabled": false
    }
  }
}
```

### `PATCH /admin/api/v1/account_profile_types/{profile_type}`
Update a profile type registry entry (tenant admin).

**Request Schema**
```json
{
  "label": "string?",
  "map_category": "string?",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_favoritable": true,
    "is_poi_enabled": false
  }
}
```

**Response Schema**
```json
{
  "data": {
    "type": "string",
    "label": "string",
    "map_category": "string",
    "allowed_taxonomies": ["string"],
    "capabilities": {
      "is_favoritable": true,
      "is_poi_enabled": false
    }
  }
}
```

### `DELETE /admin/api/v1/account_profile_types/{profile_type}`
Delete a profile type registry entry (tenant admin).

**Response Schema**
```json
{}
```

### `GET /admin/api/v1/static_profile_types`
List static profile type registry for the tenant.

**Response Schema**
```json
{
  "data": [
    {
      "type": "string",
      "label": "string",
      "allowed_taxonomies": ["string"],
      "capabilities": {
        "is_poi_enabled": true,
        "has_bio": true,
        "has_taxonomies": true,
        "has_avatar": true,
        "has_cover": true,
        "has_content": true
      }
    }
  ]
}
```

### `POST /admin/api/v1/static_profile_types`
Create a static profile type registry entry (tenant admin).

**Request Schema**
```json
{
  "type": "string",
  "label": "string",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_poi_enabled": true,
    "has_bio": true,
    "has_taxonomies": true,
    "has_avatar": true,
    "has_cover": true,
    "has_content": true
  }
}
```

**Response Schema**
```json
{
  "data": {
    "type": "string",
    "label": "string",
    "allowed_taxonomies": ["string"],
    "capabilities": {
      "is_poi_enabled": true,
      "has_bio": true,
      "has_taxonomies": true,
      "has_avatar": true,
      "has_cover": true,
      "has_content": true
    }
  }
}
```

### `PATCH /admin/api/v1/static_profile_types/{profile_type}`
Update a static profile type registry entry (tenant admin).

**Request Schema**
```json
{
  "label": "string?",
  "allowed_taxonomies": ["string"],
  "capabilities": {
    "is_poi_enabled": true,
    "has_bio": true,
    "has_taxonomies": true,
    "has_avatar": true,
    "has_cover": true,
    "has_content": true
  }
}
```

**Response Schema**
```json
{
  "data": {
    "type": "string",
    "label": "string",
    "allowed_taxonomies": ["string"],
    "capabilities": {
      "is_poi_enabled": true,
      "has_bio": true,
      "has_taxonomies": true,
      "has_avatar": true,
      "has_cover": true,
      "has_content": true
    }
  }
}
```

### `DELETE /admin/api/v1/static_profile_types/{profile_type}`
Delete a static profile type registry entry (tenant admin).

**Response Schema**
```json
{}
```

**Field Definitions**
- `profile_type_registry.type` (string): unique key for the profile type registry entry (immutable after creation).
- `profile_type_registry.label` (string): human-readable name of the profile type.
- `profile_type_registry.map_category` (string): coarse map bucket used when projecting static assets into `map_pois.category`.
- `profile_type_registry.allowed_taxonomies` (list): list of taxonomy keys allowed for the profile type.
- `profile_type_registry.capabilities.is_favoritable` (bool): whether the profile type can be favorited.
- `profile_type_registry.capabilities.is_poi_enabled` (bool): whether the profile type requires/participates in map POI location.

### `GET /admin/api/v1/taxonomies`
List taxonomies for the tenant (Account Profiles + Static Assets + Events).

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "slug": "string",
      "name": "string",
      "applies_to": ["account_profile", "static_asset", "event"],
      "icon": "mode_subscription",
      "color": "#FFAA00"
    }
  ]
}
```

### `POST /admin/api/v1/taxonomies`
Create a taxonomy.

**Request Schema**
```json
{
  "slug": "string",
  "name": "string",
  "applies_to": ["account_profile", "static_asset", "event"],
  "icon": "mode_subscription",
  "color": "#FFAA00"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "slug": "string",
    "name": "string",
    "applies_to": ["account_profile", "static_asset", "event"],
    "icon": "mode_subscription",
    "color": "#FFAA00"
  }
}
```

### `PATCH /admin/api/v1/taxonomies/{taxonomy_id}`
Update a taxonomy.

**Request Schema**
```json
{
  "slug": "string?",
  "name": "string?",
  "applies_to": ["account_profile", "static_asset", "event"],
  "icon": "mode_subscription",
  "color": "#FFAA00"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "slug": "string",
    "name": "string",
    "applies_to": ["account_profile", "static_asset", "event"],
    "icon": "mode_subscription",
    "color": "#FFAA00"
  }
}
```

### `DELETE /admin/api/v1/taxonomies/{taxonomy_id}`
Delete a taxonomy (also removes its terms).

**Response Schema**
```json
{}
```

### `GET /admin/api/v1/taxonomies/{taxonomy_id}/terms`
List terms for a taxonomy.

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "taxonomy_id": "string",
      "slug": "string",
      "name": "string"
    }
  ]
}
```

### `POST /admin/api/v1/taxonomies/{taxonomy_id}/terms`
Create a taxonomy term.

**Request Schema**
```json
{
  "slug": "string",
  "name": "string"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "taxonomy_id": "string",
    "slug": "string",
    "name": "string"
  }
}
```

### `PATCH /admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}`
Update a taxonomy term.

**Request Schema**
```json
{
  "slug": "string?",
  "name": "string?"
}
```

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "taxonomy_id": "string",
    "slug": "string",
    "name": "string"
  }
}
```

### `DELETE /admin/api/v1/taxonomies/{taxonomy_id}/terms/{term_id}`
Delete a taxonomy term.

**Response Schema**
```json
{}
```

**Field Definitions**
- `taxonomy.slug` (string): unique taxonomy key (used in `taxonomy_terms[].type`).
- `taxonomy.name` (string): display label.
- `taxonomy.applies_to` (list): allowed values `account_profile`, `static_asset`, `event`.
- `taxonomy.icon` (string, optional): Material icon name string (e.g., `mode_subscription`).
- `taxonomy.color` (string, optional): HEX color `#RRGGBB`.

### `GET /admin/api/v1/account_profiles`
List account profiles (optionally filter by `account_id`).

**Query Params**
- `account_id` (optional)
- `ownership_state` (optional): `tenant_owned|unmanaged|user_owned`

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "taxonomy_terms": [
        { "type": "string", "value": "string" }
      ],
      "location": { "lat": 0.0, "lng": 0.0 },
      "ownership_state": "tenant_owned|unmanaged|user_owned",
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /admin/api/v1/account_profiles`
Create account profile (requires `account_id`).

**Request Schema**
```json
{
  "account_id": "string",
  "profile_type": "string",
  "display_name": "string",
  "location": { "lat": 0.0, "lng": 0.0 },
  "taxonomy_terms": [{ "type": "string", "value": "string" }],
  "bio": "string?",
  "avatar_url": "string?",
  "cover_url": "string?",
  "avatar": "file?",
  "cover": "file?"
}
```
**Upload notes:** When sending `avatar`/`cover`, use `multipart/form-data`. The backend stores files and persists the resulting public URLs in `avatar_url`/`cover_url`.
**Notes:** `location` is **required** when the registry marks `profile_type` as `is_poi_enabled=true`.
**Tenant Admin UX:** The bound Account + Profile creation flow must enforce the location requirement for POI-enabled profile types and offer a **Map Pick** action to populate `location.lat`/`location.lng`.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `GET /admin/api/v1/account_profiles/{account_profile_id}`
Fetch account profile detail.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/account_profiles/{account_profile_id}`
Update account profile basic fields.

**Request Schema**
```json
{
  "profile_type": "string?",
  "display_name": "string?",
  "location": { "lat": 0.0, "lng": 0.0 },
  "taxonomy_terms": [{ "type": "string", "value": "string" }],
  "bio": "string?",
  "avatar_url": "string?",
  "cover_url": "string?",
  "avatar": "file?",
  "cover": "file?"
}
```
**Upload notes:** Use `multipart/form-data` when sending `avatar`/`cover`. PATCH updates are partial; only provided fields are modified.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `DELETE /admin/api/v1/account_profiles/{account_profile_id}`
Soft delete account profile.

**Response Schema**
```json
{}
```

### `POST /admin/api/v1/account_profiles/{account_profile_id}/restore`
Restore account profile.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "account_id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "taxonomy_terms": [
      { "type": "string", "value": "string" }
    ],
    "location": { "lat": 0.0, "lng": 0.0 },
    "ownership_state": "tenant_owned|unmanaged|user_owned",
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": null
  }
}
```

### `POST /admin/api/v1/account_profiles/{account_profile_id}/force_delete`
Force delete account profile.

**Response Schema**
```json
{}
```

### `GET /admin/api/v1/account_profiles/geo`
Geo query for POI-enabled profiles.

**Query Params**
- `origin_lat`, `origin_lng` (optional, required for geo distance)
- `max_distance_meters` (optional)
- `profile_type` (optional, repeatable)
- `limit` (optional, default 50)

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "account_id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "taxonomy_terms": [
        { "type": "string", "value": "string" }
      ],
      "location": { "lat": 0.0, "lng": 0.0 },
      "distance_meters": 0.0,
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z"
    }
  ]
}
```

### `GET /admin/api/v1/static_assets`
List static assets (tenant admin).

**Response Schema**
```json
{
  "data": [
    {
      "id": "string",
      "profile_type": "string",
      "display_name": "string",
      "slug": "string",
      "avatar_url": "string?",
      "cover_url": "string?",
      "bio": "string?",
      "content": "string?",
      "tags": ["string"],
      "categories": ["string"],
      "taxonomy_terms": [{ "type": "string", "value": "string" }],
      "location": { "lat": 0.0, "lng": 0.0 },
      "is_active": true,
      "created_at": "2025-01-01T00:00:00Z",
      "updated_at": "2025-01-01T00:00:00Z",
      "deleted_at": "2025-01-01T00:00:00Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "per_page": 15,
  "total": 0
}
```

### `POST /admin/api/v1/static_assets`
Create static asset (tenant admin).

**Request Schema**
```json
{
  "profile_type": "string",
  "display_name": "string",
  "location": { "lat": 0.0, "lng": 0.0 },
  "taxonomy_terms": [{ "type": "string", "value": "string" }],
  "tags": ["string"],
  "bio": "string?",
  "content": "string?",
  "avatar_url": "string?",
  "cover_url": "string?",
  "avatar": "file?",
  "cover": "file?"
}
```
**Upload notes:** When sending `avatar`/`cover`, use `multipart/form-data`. The backend stores files and persists the resulting public URLs in `avatar_url`/`cover_url`.
**Notes:** `location` is **required** when the registry marks `profile_type` as `is_poi_enabled=true`. `slug` is backend-generated and must not be sent by clients. `categories` and `is_active` remain accepted for backward compatibility but are no longer exposed in tenant-admin create/edit forms.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "content": "string?",
    "tags": ["string"],
    "categories": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "location": { "lat": 0.0, "lng": 0.0 },
    "is_active": true,
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `GET /admin/api/v1/static_assets/{asset_id}`
Fetch static asset detail.

**Response Schema**
```json
{
  "data": {
    "id": "string",
    "profile_type": "string",
    "display_name": "string",
    "slug": "string",
    "avatar_url": "string?",
    "cover_url": "string?",
    "bio": "string?",
    "content": "string?",
    "tags": ["string"],
    "categories": ["string"],
    "taxonomy_terms": [{ "type": "string", "value": "string" }],
    "location": { "lat": 0.0, "lng": 0.0 },
    "is_active": true,
    "created_at": "2025-01-01T00:00:00Z",
    "updated_at": "2025-01-01T00:00:00Z",
    "deleted_at": "2025-01-01T00:00:00Z"
  }
}
```

### `PATCH /admin/api/v1/static_assets/{asset_id}`
Update static asset (tenant admin).

**Request Schema:** same as create (partial).  
**Compatibility note:** `categories` and `is_active` are still accepted by backend validation for compatibility, but tenant-admin forms no longer send these fields.
**Response:** same as detail.

### `DELETE /admin/api/v1/static_assets/{asset_id}`
Soft delete static asset.

**Response Schema**
```json
{}
```

### `POST /admin/api/v1/static_assets/{asset_id}/restore`
Restore static asset.

**Response Schema:** same as detail with `deleted_at: null`.

### `DELETE /admin/api/v1/static_assets/{asset_id}/force_delete`
Force delete static asset.

**Response Schema**
```json
{}
```

### `POST /admin/api/v1/media/external-image`
Proxy an external image URL and return raw image bytes for client-side ingestion.

**Purpose:** Flutter Web cannot reliably download arbitrary third-party image URLs due to CORS/hotlink protection. This endpoint performs a server-to-server fetch (with SSRF guardrails) and returns the bytes so the client can run the normal crop/normalize/upload pipeline. The client must **never** persist the original URL as canonical avatar/cover state.

**Auth/Middleware:** `auth:sanctum` + `CheckTenantAccess` + abilities `account-users:create,account-users:update`

**Request Schema**
```json
{
  "url": "string"
}
```

**Response (bytes, not JSON)**
- Status: `200`
- Body: raw image bytes
- Headers:
  - `Content-Type: image/*` (validated)
  - `Cache-Control: no-store`

**Validation/Failure Modes**
- `401`: unauthenticated
- `403`: tenant access denied
- `422`: invalid URL, blocked destination (private/reserved), too large, too many redirects, or non-image response

**Safety Limits (V1)**
- Max bytes: 15MB
- Max redirects: 3
- Scheme: `http|https` only
- Blocks `localhost`, private IPs, and reserved IP ranges (SSRF guardrail)

### `PATCH /admin/api/v1/settings/push`
Update tenant push settings (push-only).

**Request Schema**
```json
{
  "push": {
    "max_ttl_days": 30,
    "throttles": {
      "max_per_minute": 60,
      "max_per_hour": 600
    }
  }
}
```

**Response Schema**
```json
{
  "data": {
    "max_ttl_days": 30,
    "throttles": {
      "max_per_minute": 60,
      "max_per_hour": 600
    }
  }
}
```

### `GET /admin/api/v1/settings/firebase`
Fetch firebase settings.

**Response Schema**
```json
{
  "data": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

### `PATCH /admin/api/v1/settings/firebase`
Update firebase settings.

**Request Schema**
```json
{
  "firebase": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

**Response Schema**
```json
{
  "data": {
    "apiKey": "string",
    "appId": "string",
    "projectId": "string",
    "messagingSenderId": "string",
    "storageBucket": "string"
  }
}
```

### `GET /admin/api/v1/settings/telemetry`
List telemetry integrations.

**Response Schema**
```json
{
  "data": [
    {
      "type": "mixpanel",
      "track_all": false,
      "events": ["string"],
      "token": "string"
    }
  ],
  "available_events": ["string"]
}
```

### `POST /admin/api/v1/settings/telemetry`
Add or update a telemetry integration (upsert by `type`).

**Request Schema**
```json
{
  "type": "mixpanel",
  "track_all": false,
  "events": ["string"],
  "token": "string"
}
```

**Response Schema**
```json
{
  "data": [
    {
      "type": "mixpanel",
      "track_all": false,
      "events": ["string"],
      "token": "string"
    }
  ],
  "available_events": ["string"]
}
```

### `DELETE /admin/api/v1/settings/telemetry/{type}`
Remove a telemetry integration by type.

**Response Schema**
```json
{
  "data": [
    {
      "type": "webhook",
      "track_all": false,
      "events": ["string"],
      "url": "https://example.org/hook"
    }
  ],
  "available_events": ["string"]
}
```

**Field Definitions**
- `telemetry.type` (enum): `mixpanel`, `firebase`, `webhook`
- `telemetry.track_all` (bool): when true, all supported events are emitted and `events` is ignored.
- `telemetry.events` (list): required when `track_all=false`; ignored when `track_all=true`.
- `available_events` (list): backend event names supported for Mixpanel and webhook telemetry.

## 4. Next Steps

Defer detailed schemas and APIs until the core consumer modules are stable. Tenant admin requirements will be inferred from:

- Account Profile Catalog & Offer module (what entities need CRUD).
- Invite & Social Loop module (quota management, attendance metrics).
- Task & Reminder module (outstanding compliance tasks).
- Web-to-App policy constraints (e.g., what channels tenants can enable).
