# TODO (V1): Fix EventsController formatManagementEvent Regression
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Draft  
**Owners:** Backend Team  
**Objective:** Restore event create response formatting after refactor by routing through `EventQueryService::formatManagementEvent`.

---

## References
- `laravel-app/app/Http/Api/v1/Controllers/EventsController.php`
- `laravel-app/app/Application/Events/EventQueryService.php`
- `tests/Feature/Events/EventCrudControllerTest.php`

---

## A) Scope
- Update `EventsController::store()` to use `EventQueryService::formatManagementEvent()`.
- Re-run `tests/Feature/Events/EventCrudControllerTest.php`.

---

## B) Out of Scope
- Any changes to event payload schema or validation rules.
- Additional event feature work.

---

## C) Definition of Done
- Event create response uses the service formatter.
- Event CRUD test passes in Docker.

---

## D) Validation Steps
- `docker compose exec -T app php artisan test tests/Feature/Events/EventCrudControllerTest.php`
