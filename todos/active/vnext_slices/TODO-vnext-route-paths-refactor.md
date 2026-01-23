# TODO (VNext): Route Paths Refactor (Explicit Admin/Public Prefixes)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Platform + Laravel + Client Teams  
**Objective:** Refactor route paths to make admin/public scopes explicit (breaking change) in a phased rollout, while also splitting project route extensions by scope:
1) Boilerplate update → 2) bring into current project → 3) project-specific routes.

---

## A) Scope
- Introduce explicit **admin** vs **public** path prefixes where appropriate.
- Preserve tenant/domain separation while making scope visible in the URL.
- Split project extension routes into explicit scope files (tenant public/admin, landlord public/admin).
- Execute in three phases:
  - **Phase 1 (Boilerplate update):** adjust boilerplate routes and docs (including extension file split).
  - **Phase 2 (Adopt in project):** bring boilerplate changes into current project.
  - **Phase 3 (Project-specific):** update project-specific route files and consumers.

---

## B) Decisions (Already Agreed)
- **Domain vs auth are separate concerns (do not mix):**  
  Domain determines which route sets are even reachable. Auth/abilities determine whether the caller can access a reachable endpoint.  
  Avoid encoding “public/admin” meaning in hostnames or vice‑versa.
- **Domain-driven scope:**  
  Landlord routes are served on the **main domain**; tenant routes are served on **tenant subdomains/domains**.  
  Path alone does not determine scope; domain + middleware does.
- **User access matrix (abilities still required):**
  - **Landlord user:** can access landlord + tenant‑admin + tenant‑non‑admin + account routes (subject to abilities).
  - **Account user:** can access tenant‑non‑admin + account routes only (subject to abilities).
- **Tenant non-admin naming:**  
  We will not introduce a new “landlord‑available” label. Tenant non‑admin routes are simply tenant routes under `/api/v1/...`; user type (landlord vs account) is enforced via auth/abilities.
- **Tenant Admin prefix:**  
  Tenant admin endpoints move to `/admin/api/v1/...` on tenant domains.
- **Tenant Public prefix:**  
  Tenant public endpoints remain at `/api/v1/...`.
- **Account admin remains account-scoped:**  
  Account routes are already admin and stay under `/api/v1/accounts/{account_slug}/...` (tenant domain).
- **Public access for account-owned objects:**  
  Public read access is exposed through **tenant public** endpoints with account filters (no account-public routes).
- **Leak prevention is mandatory:**  
  We must add automated tests ensuring **non-public profiles never leak** via public routes.
  - Add tests for tenant‑created on‑behalf events: appear in target account admin only (no cross‑account leakage).
- **Project route extensions must be split by scope:**  
  Separate files for tenant public/admin and landlord public/admin.
- **Landlord Public exists:**  
  Public landlord routes stay on `/api/v1/...` (main domain).  
  Includes at minimum `/api/v1/environment` and basic public diagnostics (e.g., `/api/v1/check`, `/health` if present).  
  Branding assets are now delivered via environment (no separate landlord branding public API).
- **Events routing (account + tenant):**
  - **Tenant Public** exposes cross‑account reads:  
    `GET /api/v1/events`, `GET /api/v1/events/{event_id}`, `GET /api/v1/agenda`, `GET /api/v1/events/stream`.
  - **Tenant Admin** (landlord user) can CRUD cross‑account events, and can create on behalf of an account:  
    `POST/PATCH/DELETE /admin/api/v1/events...`, `GET /admin/api/v1/events/stream`.
  - **Account Admin** (account user) can CRUD only their own events:  
    `GET/POST/PATCH/DELETE /api/v1/accounts/{account_slug}/events...`.
  - **Filters:**  
    `account_id` → returns all profiles managed by the account;  
    `account_profile_id` → returns only that profile’s events.
  - **Ownership rule:** tenant‑created on‑behalf events are owned by the target account and **must only appear** in that account’s admin scope (never other accounts).

---

## C) Pending Decisions
_All pending items resolved; proceed to Phase 1 tasks._

---

## D) Tasks
### Process guardrails
- [x] ✅ Production-Ready Document domain vs auth separation + access matrix in the Laravel route creation workflows.

### Phase 1 — Boilerplate update
- [x] ✅ Production-Ready Define the target URL map for all scopes (landlord/tenant/account; admin/public).
- [x] ✅ Production-Ready Update route registration in boilerplate `bootstrap/app.php` and base route files.
- [x] ✅ Production-Ready Update boilerplate tests and middleware expectations.
- [ ] ⚪ Update boilerplate documentation (`system_roadmap.md`, modules, endpoint contracts).
- [x] ✅ Production-Ready Split project extension files by scope (boilerplate):
  - `project_tenant_public_api_v1.php`
  - `project_tenant_admin_api_v1.php`
  - `project_landlord_admin_api_v1.php`
  - `project_landlord_public_api_v1.php` (empty if no routes yet)
  - Keep `project_account_api_v1.php` as-is for now.
- [x] ✅ Production-Ready Wire new extension files in boilerplate `bootstrap/app.php`.
- [ ] ⚪ Add boilerplate tests to ensure public routes never leak private profiles.

### Phase 2 — Adopt in current project
- [x] ✅ Production-Ready Merge boilerplate `dev` (routes‑refactor) into the project branch (traceable history).  
  _Already contained in `account-profile-implementation` via upstream merge (upstream/dev @ 766e328)._
- [x] ✅ Production-Ready Remove tenant‑maybe routes from main domain (main domain must be landlord‑only).
- [x] ✅ Production-Ready Ensure landlord public `/api/v1/environment` lives in `project_landlord_public_api_v1.php`.
- [ ] ⚪ Update project tests and middleware expectations (if merge introduces deltas).
- [ ] ⚪ Update client integrations (Flutter/web) for core endpoints.
- [ ] ⚪ Confirm public route filters align with Account Profile privacy rules.

**Phase 2 Validation Notes**
- `php artisan route:list` ran via Docker; output stored at `/tmp/current_project_route_list.txt` (paths unchanged).
- Full Laravel test suite ran in Docker: **690 tests passed (2563 assertions)** in ~285s.

### Phase 3 — Project-specific routes
- [x] ✅ Production-Ready Enforce domain-scoped routing in `bootstrap/app.php` (main domain → landlord, tenant domains → tenant/admin/account).
- [x] ✅ Production-Ready Update `project_*_api_v1.php` routes to match new path strategy.
- [x] ✅ Production-Ready Split project extension files by scope (current project) to mirror boilerplate.
- [x] ✅ Production-Ready Update project-specific client calls and docs.

### Migration Tutorial
- [x] ✅ Production-Ready Create a simple route migration guide (old paths → new paths + domain scope notes).  
  _Location: `foundation_documentation/todos/active/vnext_slices/route_paths_migration_guide.md`_

**Phase 3 Validation Notes**
- Tenant routes are now blocked on the main domain; landlord routes are restricted to the main domain.
- `php artisan route:list` ran via Docker; output stored at `/tmp/current_project_route_list.txt`.
- Tenant admin routes now require landlord users (account users are blocked by landlord validation).

---

## E) Definition of Done
- [ ] ⚪ Boilerplate updated with explicit path strategy.
- [ ] ⚪ Current project aligned to boilerplate changes.
- [ ] ⚪ Project-specific routes updated.
- [ ] ⚪ All clients updated to new paths.
- [ ] ⚪ Tests green and public/admin boundaries verified.
