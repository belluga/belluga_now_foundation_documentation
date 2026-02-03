# TODO (V1): Sync Map POIs When Scheduled Events Publish
**Version:** 1.0  
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** ✅ Production-Ready  
**Owners:** Backend Team  
**Objective:** Ensure scheduled event publication updates `map_pois` for events promoted by `PublishScheduledEventsJob`.

---

## References
- `laravel-app/app/Jobs/PublishScheduledEventsJob.php`
- `laravel-app/app/Jobs/MapPois/UpsertMapPoiFromEventJob.php`
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`

---

## A) Scope
- Update `PublishScheduledEventsJob` to:
  - load events being promoted (`publication.status=publish_scheduled` and `publish_at <= now`),
  - update their publication status,
  - run map POI projection within the tenant context for each promoted event.
- Extend `EventCrudControllerTest::testPublishScheduledEventsJobPromotesReadyEvents` to assert that a `map_pois` entry exists for promoted events and is absent for future-scheduled events.

---

## B) Out of Scope
- Changes to event schema, validation rules, or publication logic beyond dispatching the projection job.
- Any map payload or filtering changes.

---

## C) Definition of Done
- `PublishScheduledEventsJob` triggers map POI projection for promoted events.
- The scheduled publish test asserts map POI projection results.
- Existing tests remain green.

---

## D) Validation Steps
- `docker compose exec -T app php artisan test tests/Feature/Events/EventCrudControllerTest.php`
