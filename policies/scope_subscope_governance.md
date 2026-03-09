# Policy: Scope and Subscope Governance
**Version:** 1.0
**Status:** Authoritative

## 1. Purpose
Define the canonical scope/subscope model for routes, modules, and screens across Flutter, Laravel, and Delphi governance assets.

This policy is the authoritative source for V1 scope boundaries and must be referenced by any document or workflow that defines route ownership, module ownership, screen placement, or guard behavior.

## 2. Canonical Definitions

### 2.1 EnvironmentType
- `EnvironmentType` is binary and fixed in V1:
  - `landlord`
  - `tenant`

### 2.2 Main Scope
A **main scope** is a top-level runtime area owned by an `EnvironmentType`.

Approved V1 main scopes:
- `site_public`
- `landlord_area`
- `tenant_public`
- `tenant_admin`

### 2.3 Subscope
A **subscope** is a bounded area inside one environment, used for specific internal responsibilities without creating a new `EnvironmentType` or main scope.

Approved V1 subscope:
- `account_workspace`

## 3. Canonical Ownership Matrix

| EnvironmentType | Scope Type | Scope Key | Canonical Purpose |
|---|---|---|---|
| `landlord` | Main scope | `site_public` | Public landlord/site surface. |
| `landlord` | Main scope | `landlord_area` | Landlord operations and tenant selection. |
| `tenant` | Main scope | `tenant_public` | Tenant public/home experience. |
| `tenant` | Main scope | `tenant_admin` | Tenant administration surface (landlord-identity guarded on tenant domain). |
| `tenant` | Subscope | `account_workspace` | Account-user workspace root and account-scoped admin surface. |

## 4. Canonical Route Resolution (V1)

### 4.1 Host-aware Web Resolution
- On landlord domain:
  - `/` => `site_public`
  - `/admin` => `landlord_area`
- On tenant domain/subdomain:
  - `/` => `tenant_public`
  - `/admin` => `tenant_admin`
  - `/workspace` => `account_workspace` root mode
  - `/workspace/{account_slug}` => `account_workspace` account-scoped mode

### 4.2 Historical URL Normalization
- Landlord host:
  - `/home` => `/admin`
  - `/landlord` => `/admin`
- Tenant host:
  - `/home` => `/`
  - `/landlord` => `/`

### 4.3 Mobile Flavor Defaults
- Mobile landlord flavor default main scope: `landlord_area`.
- Mobile tenant flavor default main scope: `tenant_public`.

## 5. Transition and Handoff Policy (V1)
- Landlord -> tenant admin transition is URL redirect-link based:
  - source: landlord-area tenant list,
  - target: selected tenant-domain `/admin`.
- Cross-domain SSO/session reuse is not required in V1.
- If tenant-domain auth is required, login fallback is valid and expected.

## 6. Module and Screen Ownership Contract
Any authoritative module/screen documentation that defines routes or UI surfaces must explicitly state:
- primary main scope ownership,
- any subscope touchpoints,
- route-to-scope mapping for each relevant route.

If a module spans more than one scope/subscope, include an explicit **Route/Subscope Matrix** section.

### 6.1 Route/Subscope Matrix Template
| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
|---|---|---|---|---|---|
| `/example` | landlord/tenant | `landlord`/`tenant` | `<main_scope>` | `<subscope or n/a>` | `<guard + identity principal>` |

## 7. Governance Rules

### 7.1 No New Subscope Without Explicit Decision
New subscopes are forbidden unless explicitly approved and documented in:
- this policy, and
- the active tactical TODO decision log.

### 7.2 Authoritative vs Historical Interpretation
- Authoritative: `foundation_documentation/` canonical docs + Delphi rules/workflows/skills aligned to this policy.
- Historical only: archived/completed TODOs and temporary notes.
- In conflicts, authoritative sources win.

### 7.3 Derived `web-app` Boundary
- `web-app` is a derived/compiled repository surface in this ecosystem context.
- Route/navigation test source files must be authored in source-owned locations (for example `tools/flutter/web_app_tests/`) and executed through a dedicated runner outside `web-app`.
- Direct `web-app` test authoring is prohibited for canonical route-governance changes.

## 8. Compliance Checklist (Minimum)
- Scope terms use canonical vocabulary (`EnvironmentType`, main scope, subscope).
- No document implies extra subscopes beyond approved V1 set.
- Multi-scope modules include route/scope matrix.
- Route/screen governance assets reference this policy path.
