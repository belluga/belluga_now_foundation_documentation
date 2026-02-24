# Documentation: Landlord App Module (V1)
**Version:** 1.1

## 1. Objective
Define the canonical landlord experience in V1 with explicit scope boundaries and host-aware routing rules.

## 2. Canonical Scope Model
- `EnvironmentType` is binary: `landlord | tenant`.
- Canonical governance source:
  - `foundation_documentation/policies/scope_subscope_governance.md`
- On **landlord host**:
  - `/` => `site_public` (Landlord App landing surface).
  - `/admin` => `landlord_area` (tenant selection + landlord operations).
- On **tenant host/subdomain**:
  - landlord routes are invalid and must not open landlord surfaces.

### 2.1 Route/Scope Matrix
| Route | Host Context | EnvironmentType | Main Scope | Subscope | Surface |
|---|---|---|---|---|---|
| `/` | Landlord | `landlord` | `site_public` | n/a | Public landing + login CTA. |
| `/admin` | Landlord | `landlord` | `landlord_area` | n/a | Tenant list + landlord operations. |
| `/home` | Landlord | `landlord` | `landlord_area` | n/a | Historical path normalized to `/admin`. |
| `/landlord` | Landlord | `landlord` | `landlord_area` | n/a | Historical path normalized to `/admin`. |

## 3. Route Policy
- Canonical landlord entry is `/` (`site_public`).
- Historical landlord paths:
  - `/home` => `/admin`
  - `/landlord` => `/admin`
- Guard rule:
  - `LandlordRouteGuard` allows landlord surfaces only on landlord context (host or `EnvironmentType.landlord`).
  - Tenant hosts must fallback to tenant canonical root when landlord routes are attempted.

## 4. UI Surfaces (V1)
### 4.1 Site Public (`/`)
- Public landing with project positioning and landlord login CTA.
- This is public-facing and may contain one or multiple sections/screens in future slices.

### 4.2 Landlord Area (`/admin`)
- Initial screen: tenant list (tenants accessible to landlord identity).
- Tenant selection action: redirect-link to selected tenant primary domain `/admin`.
- The redirect-link does not perform cross-domain SSO in this phase.

### 4.3 Login/Admin CTAs
- Show `Login as Landlord` when landlord session is not active.
- Show `Open Admin Area` only when:
  - landlord session is valid; and
  - admin mode is `AdminMode.landlord`.

## 5. Identity and Auth Boundaries
- `landlord_area` uses landlord identity principal.
- Redirect from landlord area to tenant domain `/admin` remains landlord-principal based.
- Cross-domain session reuse is out of scope in this phase; tenant-domain auth may prompt again.

## 6. Mobile Flavor Rule
- Mobile landlord flavor boots in `EnvironmentType.landlord` and defaults to `landlord_area`.
- No host inference is required on mobile flavor startup.

## 7. Integrations
- `LandlordAuthRepositoryContract`
- `AdminModeRepositoryContract`
- `LandlordTenantsRepositoryContract`
- `TenantAdminShellRoute` (redirect-link target on tenant domains)

## 8. Minimum Validation
- `fvm flutter analyze` with no issues.
- Landlord home/scope tests validate:
  - login CTA visibility without landlord session.
  - admin CTA visibility with valid landlord session + landlord mode.
  - tenant list rendering.
  - tenant-host blocking for landlord routes.
