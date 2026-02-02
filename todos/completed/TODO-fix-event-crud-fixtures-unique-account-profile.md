# TODO (V1): Fix Event CRUD test fixtures for AccountProfile uniqueness
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** ✅ Production-Ready  
**Owners:** Backend Team  
**Objective:** Stabilize Event CRUD tests by fixing fixture/service mismatches introduced during the controller→service refactor.

---

## References
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `laravel-app/app/Models/Tenants/AccountProfile.php`
- `foundation_documentation/domain_entities.md` (Account Profile 1:1 constraint)

---

## A) Scope
- Fix remaining Event CRUD test failures caused by service/refactor alignment issues.
- Ensure fixtures respect the 1:1 account↔profile constraint.
- Resolve the `event update changes fields` 404 by aligning fixture data with account route validation.
- Re-run `tests/Feature/Events/EventCrudControllerTest.php` in Docker and iterate until green.
- Expose MongoDB port on the host so local GUI tooling can connect for debugging (map 27017).
- Add a temporary, test-only diagnostic to confirm the active tenant DB during the event PATCH path; remove it after diagnosis.

---

## B) Out of Scope
- Changing production account/profile creation behavior.
- Modifying database constraints or account-profile cardinality.
- Altering event validation logic.
- Changing Atlas connection settings or production DB hosts.

---

## C) Definition of Done
- Event CRUD tests pass (no 404/duplicate-key regressions).
- The “venue without location” test still asserts a 422 validation response.
- Temporary diagnostic is removed once tenant context is verified.

---

## D) Validation Steps
- `docker compose exec -T app php artisan test tests/Feature/Events/EventCrudControllerTest.php`
- `docker compose up -d mongo` and verify local tooling can connect via `mongodb://localhost:27017`.

---

## E) Execution Notes
- Fixed duplicate AccountProfile creation by creating a separate Account for the “no location venue”.
- Test run now fails at `event update changes fields` with 404, indicating a separate fixture or routing mismatch unrelated to the uniqueness constraint.
- Patch update still returns 404 even when using DB-fetched event id; likely tenant-context mismatch in request handling (needs deeper investigation).
- Root cause: EventsController received the tenant domain in `$event_id` for account routes; using `$request->route('event_id')` fixes update/show/destroy lookups.
- Event CRUD test suite now passes in Docker after normalizing event id resolution.
- Full test suite (`php artisan test`) passes.
