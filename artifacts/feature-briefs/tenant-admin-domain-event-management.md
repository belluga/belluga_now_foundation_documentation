# Feature Brief: Tenant Admin Domain + Event Management Improvements

## Artifact Role
- **Why this brief exists now:** The request spans multiple surfaces (tenant domains + event admin filtering) and crosses modules, so a brief is needed to keep the tactical TODO bounded to one story slice.
- **What this brief is not:** canonical module doc, project constitution, system roadmap, tactical TODO, or implementation authority.

## Source Idea / Request
- Add tenant domain management, improve tenant-admin event management, and enable event list filtering by venue or related account profile. Remove hardcoded profile-type assumptions.

## Problem / Desired Outcome
- **Problem:** Tenant admins cannot manage tenant domains from the admin UI, and event management filtering is missing a venue/related-account-profile filter path. Previous hardcoded profile-type references (e.g., artists) are incompatible with dynamic account profile types.
- **Desired outcome:** Tenant admins can manage active tenant domains and can filter event management views by venue or related account profiles without hardcoded profile-type keys. Documentation reflects the dynamic account profile model.
- **Why now:** Event admin management improvements are blocked without tenant domain management and proper filters; hardcoded profile-type assumptions are a known blocker.

## Constraints / Non-Goals
- **Constraints:** Keep changes aligned with tenant-admin scope rules and existing API prefixes (`/admin/api/v1`). Preserve dynamic account profile type registry semantics (no new hardcoded profile keys).
- **Non-goals:** No new tenant-public event filters beyond venue/related account profile; no redesign of tenant domain resolution or landlord admin flows; no changes to event public detail contracts beyond current module decisions.

## Canonical Touchpoints
- **Constitution impact:** possible — project constitution file is currently missing; any cross-module invariant updates must be handed off.
- **Roadmap impact:** possible — if new backend work requires follow-on client updates beyond this slice.
- **Primary module candidates:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/events_module.md`

## Evidence / References
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `laravel-app/app/Http/Controllers/Tenants/Admin/DomainController.php`
- `laravel-app/app/Services/TenantDomainManagementService.php`
- `flutter-app/lib/features/tenant_admin/settings/**`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Where tenant domain list/read should live in tenant-admin settings (new section vs existing app links). | Affects UI placement, controller state, and settings navigation. | Tenant admin settings module defines technical integrations sections; no domain management section yet. | resolve now (dedicated settings route) |
| `AMB-02` | Event management filter UX placement and payload shape for venue vs related account profile. | Affects API query params, UI filters, and repository contracts. | Events module lists filter baselines but not tenant-admin filter UX. | resolve now |
| `AMB-03` | Project constitution missing. | Cross-module invariant updates cannot be persisted without the constitution. | `foundation_documentation/project_constitution.md` not present. | carry as TODO assumption |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Tenant admin can list/create/remove active tenant domains in settings. | `tenant_admin_module` | `events_module` (none) | Admin UI shows active domains; API supports list/create/delete with validation and tests. | Flutter unit tests + Laravel feature tests. | `create-now` | None | Cross-stack (Laravel + Flutter + docs). |
| `ST-02` | Tenant admin event management supports filtering by venue or related account profile. | `events_module` | `tenant_admin_module` | Event admin list filters by venue/related account profile with documented contract and UI filter. | Backend request tests + admin UI behavior validation. | `defer` | Depends on ST-01 sequencing for admin settings baseline. | Cross-stack (Events + tenant admin UI). |

## Retire This Brief When
- The active tactical TODO is approved and no longer needs this brief for bounded scope.
