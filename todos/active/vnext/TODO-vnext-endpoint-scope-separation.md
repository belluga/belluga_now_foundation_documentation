# TODO (VNext): Endpoint Scope Separation (Tenant Public vs Tenant Admin vs Account Admin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Platform + Laravel Team  
**Objective:** Formalize scope boundaries so public account content is served via Tenant Public routes, while CRUD remains Account Admin, and tenant-admin views remain explicit.

---

## A) Scope
- Establish a clear scope matrix for **Landlord Admin**, **Tenant Admin**, **Tenant Public**, **Account Admin**.
- For every Account-owned object that is public, expose it in **Tenant Public** (read-only) and **Account Admin** (CRUD).
- Ensure any admin/private route remains isolated to Tenant Admin or Account Admin contexts.

---

## B) Decisions to Confirm
- [ ] ⚪ Define the canonical **scope matrix** (who can list/read/create/update/delete per object type).
- [ ] ⚪ Confirm which Account objects require a **public projection** (events, store items, profiles, etc.).
- [ ] ⚪ Confirm whether Tenant Admin should expose any **read-only** public projection (optional).

---

## C) Tasks
- [ ] ⚪ Document the **scope matrix** in `foundation_documentation/modules/` (API + entities) and `system_roadmap.md`.
- [ ] ⚪ Update Laravel endpoint-creation rules to require explicit scope placement (Tenant Public vs Tenant Admin vs Account Admin).
- [ ] ⚪ Add public endpoints in `project_public_api_v1.php` for Account objects that are publicly queryable.
- [ ] ⚪ Add automated tests to prevent leakage of private profiles/objects on public routes.
- [ ] ⚪ Update Flutter + frontend docs to consume public endpoints for discovery.

---

## D) Definition of Done
- [ ] ⚪ Scope matrix documented and referenced by endpoint rules.
- [ ] ⚪ Public endpoints exist only in Tenant Public routes (no admin leakage).
- [ ] ⚪ Admin CRUD only in Tenant Admin / Account Admin.
- [ ] ⚪ Regression tests cover public/private separation.
