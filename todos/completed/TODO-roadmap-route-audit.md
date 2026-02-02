# TODO (V1): System Roadmap Route Audit
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** - [x] ✅ Production-Ready  
**Owners:** Docs  
**Objective:** Align `system_roadmap.md` endpoint statuses with actual Laravel route registrations in `laravel-app/`.

---

## References
- `laravel-app/bootstrap/app.php`
- `laravel-app/routes/api/*.php`
- `laravel-app/packages/belluga/belluga_push_handler/routes/push_handler.php`
- `foundation_documentation/system_roadmap.md`

---

## Decisions (Proposed)
- Only update `foundation_documentation/system_roadmap.md` in this pass.
- Status mapping:
  - Route exists → set to **Implemented** (unless already **Tested & Ready** and tests are explicitly known).
  - No route found → set to **Defined** (if contract exists) or keep **Planned** when roadmap already marks it as such.
- Add a short note per endpoint when status changes to explain the route evidence (e.g., file + prefix).

## Questions To Close
- None.

---

## A) Scope
- Audit route presence in `laravel-app` (tenant public, tenant admin, landlord, account, and push handler package routes).
- Update endpoint statuses + notes in `foundation_documentation/system_roadmap.md` to match route presence.

## B) Out of Scope
- Editing code, migrations, or tests.
- Updating other documentation modules (tenant_admin_module, endpoints_mvp_contracts, domain_entities).

## C) Definition of Done
- `system_roadmap.md` reflects actual route presence for all listed endpoints.
- Each changed endpoint includes a brief note of route evidence.

## D) Validation Steps
- `rg`/`sed` review of `laravel-app/routes/api` and `packages/belluga/belluga_push_handler/routes/push_handler.php` to confirm route presence.

---

## Execution Notes (2026-02-02)
- Updated `foundation_documentation/system_roadmap.md` to align endpoint paths and statuses with actual route registrations.
