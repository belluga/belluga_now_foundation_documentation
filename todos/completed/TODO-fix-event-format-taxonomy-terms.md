# TODO (V1): Add taxonomy_terms to EventQueryService format payload
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** ✅ Production-Ready  
**Owners:** Backend Team  
**Objective:** Ensure event API responses include `taxonomy_terms` from the event document so taxonomy assignments are visible to clients.

---

## References
- `laravel-app/app/Application/Events/EventQueryService.php`
- `laravel-app/app/Models/Tenants/Event.php`
- `tests/Feature/Events/EventCrudControllerTest.php`

---

## A) Scope
- Add `taxonomy_terms` to `EventQueryService::formatEvent()` output.
- Normalize the taxonomy array using the existing helper(s).
- Re-run `tests/Feature/Events/EventCrudControllerTest.php`.

---

## B) Out of Scope
- Changes to taxonomy validation rules or taxonomy registry definitions.
- Additional event schema changes beyond `taxonomy_terms` formatting.
- Updating Flutter clients or docs outside test expectations.

---

## C) Definition of Done
- Event responses include `taxonomy_terms` as an array of `{type, value}` items.
- Event CRUD tests pass in Docker.

---

## D) Validation Steps
- `docker compose exec -T app php artisan test tests/Feature/Events/EventCrudControllerTest.php`

---

## E) Execution Notes
- `EventQueryService::formatEvent()` now includes `taxonomy_terms`.
- Full test suite (`php artisan test`) passes after fixing event update routing to use the route param event id.
