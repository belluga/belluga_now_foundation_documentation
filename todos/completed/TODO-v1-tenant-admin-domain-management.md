# TODO (V1): Tenant Admin Domain Management (List/Create/Delete/Restore)

**Superseded note (2026-04-18):** this standalone domain-management TODO is no longer the canonical authority for the tenant-admin domains slice. The real V1 delivery was absorbed by `foundation_documentation/todos/completed/TODO-v1-tenant-admin-domain-management-and-events-ops.md`, which intentionally narrowed the approved tenant-domain scope to active-domain `list/create/delete` and deferred deleted-domain `restore/force-delete` out of the V1 lane. Media-library or gallery reuse was never part of this TODO; shared admin picker reuse is already closed in `foundation_documentation/todos/completed/TODO-vnext-tenant-admin-media-library-reuse.md`, while future gallery capability remains separate.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant admins need to manage tenant domains from the admin settings surface. Today only write/delete exists in backend without a read/list contract, and Flutter has no domain management UI.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/tenant-admin-domain-event-management.md`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** Enables tenant-admin settings to manage active domains and unblocks future tenant-admin improvements without crossing into event management filtering.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** n/a

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Completed`
- **Qualifiers:** `Superseded-By-Canonical-Lane`, `Historical-Reference`
- **Next exact step:** None. Archived to `todos/completed` on `2026-04-18`.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Superseded (absorbed by canonical tenant-admin domains + events lane)
**Owners:** Flutter Team, Laravel Team, Documentation
**Objective:** Preserve the historical narrower domain-management-only slice that existed before the canonical tenant-admin delivery was re-baselined into the broader `tenant-admin-domain-management-and-events-ops` authority.

## Scope
- [ ] Add tenant-admin API read contract to list active + deleted domains (`GET /admin/api/v1/domains`) with status and tests.
- [ ] Add Flutter tenant-admin settings domain management UI (list/create/delete/restore) wired to backend.
- [ ] Update tenant-admin documentation to reflect domain management contracts and UI placement.
- [ ] Enforce tenant-admin auth/abilities on domain endpoints.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:feat/tenant-admin-domain-management`, `laravel-app:feat/tenant-admin-domain-management`, `foundation_documentation:feat/tenant-admin-domain-management`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Domain management API + UI + docs | `pending` | `pending` | `pending` | `n/a` | `pending` |

## Out of Scope
- [ ] No event management filtering changes (tracked in a separate TODO).
- [ ] No changes to tenant resolution logic or landlord admin flows.
- [ ] No tenant-public domain management UI.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Minor UI layout adjustments inside tenant-admin settings, input validation alignment, and minor service-level refactors that stay within domain management scope.
- **Must update or split the TODO:** Event management filtering work, new tenant resolution behavior, or any additional settings area unrelated to domain management.

## Definition of Done
- [ ] `GET /admin/api/v1/domains` returns active + deleted domains with deterministic ordering (`deleted_at` asc, `created_at` desc), `page` + `per_page`, and `status`.
- [ ] Tenant-admin settings UI lists domains with status and allows create/delete/restore with success/error handling.
- [ ] Backend feature tests cover list/create/delete/restore behaviors for tenant domains, including status and ability enforcement.
- [ ] Flutter tests cover repository encoding/decoding for domains and controller state transitions.
- [ ] `foundation_documentation/modules/tenant_admin_module.md` reflects the domain management contract and UI placement.

## Validation Steps
- [ ] Laravel: `php artisan test --filter TenantDomainControllerTest`
- [ ] Flutter: `fvm dart test test/features/tenant_admin/settings` (or narrower test targets added by this TODO)
- [ ] Flutter analyzer: `fvm dart analyze --format machine`

## External Dependency Readiness (Required When External Systems Matter)
- n/a

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `none`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `<from>` | `<to>` | <reason> | <paths/surfaces> | <planned|active|completed> |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** Cross-stack changes (Laravel + Flutter + docs) with new contract surface and UI behavior.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs (if any):** `foundation_documentation/modules/events_module.md` (context only; no contract changes planned)
- **Planned decision promotion targets (module sections):**
  - `tenant_admin_module.md` → Settings routes + domain management contract updates
  - `tenant_admin_module.md` → Route/Subscope Matrix update for `/admin/settings/domains`
- **Module decision consolidation targets (required):**
  - `tenant_admin_module.md` → Settings routes + domain management contract updates

## Decisions (Resolved Before Freeze)
- [ ] `D-01` Dedicated `/admin/settings/domains` route for domain management so settings hub stays lean and domain management has a focused surface. (No Prior Decision)
- [ ] `D-02` List API includes deleted domains with `status` to enable restore from the list UI. (No Prior Decision)
- [ ] `D-03` Domain list uses `page` + `per_page` (default 15), ordered by `deleted_at` asc then `created_at` desc; `status` enum values `active|deleted`. (No Prior Decision)
- [ ] `D-04` Domain endpoints require `auth:sanctum` + `CheckTenantAccess` with abilities `tenant-domains:read` (GET) and `tenant-domains:update` (POST/DELETE/restore/force-delete). (No Prior Decision)

## Decision Pending (Resolve Before Freeze)
- [ ] `none`

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `tenant_admin_module.md#3.6` | Settings hub + technical integrations routing | `Preserve` | `tenant_admin_module.md §3.6` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Tenant-admin domain management is a dedicated settings route: `/admin/settings/domains`.
- [x] `D-02` Domain list API returns active + deleted domains with `status` and supports restore actions in UI.
- [x] `D-03` Domain list uses `page` + `per_page` (default 15), ordered by `deleted_at` asc then `created_at` desc; `status` enum values `active|deleted`.
- [x] `D-04` Domain endpoints require `auth:sanctum` + `CheckTenantAccess` with abilities `tenant-domains:read` (GET) and `tenant-domains:update` (POST/DELETE/restore/force-delete).

## Questions To Close
- [ ] Confirm whether missing `project_constitution.md` should be created by Strategic / CTO-Tech-Lead before cross-module invariants are updated.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Tenant domain management is owned by tenant-admin settings and does not require landlord-area UX changes. | `tenant_admin_module.md` settings routing; existing `/admin/api/v1/domains` controller. | Would require additional routes/screens and a new TODO scope. | `Medium` | `Keep as Assumption` |
| `A-02` | `project_constitution.md` is missing; any cross-module invariants will require a Strategic handoff rather than direct edits. | `foundation_documentation/` listing lacks `project_constitution.md`. | Need a constitution add/update before proceeding with cross-module invariants. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `laravel-app/app/Http/Api/v1/Controllers/DomainController.php`
- `laravel-app/app/Application/Tenants/TenantDomainManagementService.php`
- `laravel-app/routes/api/tenant_api_v1.php`
- `laravel-app/tests/Feature/Tenants/TenantDomainControllerTest.php`
- `flutter-app/lib/features/tenant_admin/settings/**`
- `foundation_documentation/modules/tenant_admin_module.md`

### Ordered Steps
1. Ingest applicable rules/workflows and create feature branches.
2. Add `GET /admin/api/v1/domains` endpoint + service method to list active + deleted domains with pagination, ordering, and status.
3. Add auth/ability middleware for domain endpoints (`tenant-domains:read|update`).
4. Extend Laravel tests for list contract + soft-delete + ability enforcement.
5. Add Flutter domain models/repository contract + controller state for domain list + create/delete/restore.
6. Add dedicated settings route `/admin/settings/domains` per D-01 placement decision.
7. Update tenant-admin module documentation (routes, auth, and contract sections + route matrix).
8. Run targeted tests and analyzer.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** The feature spans backend + UI; add focused tests after shaping the contract.
- **Fail-first target(s) (when required):** n/a (feature addition; no regression baseline test exists).

### Runtime / Rollout Notes
- n/a

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `PLAN-01`
  - **Severity:** `medium`
  - **Evidence:** `Independent critique flagged missing pagination/status enum, auth/ability policy, and route matrix updates.`
  - **Why it matters now:** `Avoids unbounded payloads, weak access control, and scope-governance drift.`
  - **Option A (Recommended):** `Integrate critique findings into scope/DoD/plan (pagination, status enum, abilities, route matrix updates).`
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
