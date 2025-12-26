# TODO (Flutter): Switch Environment Fetch to /api/v1/environment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Update the Flutter app's environment bootstrap request to hit the API contract endpoint `/api/v1/environment` instead of the legacy web route `/environment`.

---

**scope:** Update the Laravel backend client used by Flutter to call `/api/v1/environment` (preserving current query params such as `app_domain`), and adjust any related mocks/tests or configuration that hardcode `/environment`.  
**out_of_scope:** Backend changes, PWA asset routing (`/manifest.json`, icon/logo paths), or changes to environment response payload shape.  
**definition_of_done:** Flutter uses `/api/v1/environment` for environment bootstrap; no remaining `/environment` references in runtime code; any impacted tests/mocks updated; analyzer remains clean.  
**validation_steps:** `fvm flutter analyze`; run targeted unit tests for app data fetching (or document if not available).

---

## Context

- Backend now exposes the environment contract at `/api/v1/environment` (tenant-maybe route).
- The legacy web route `/environment` has been removed to keep a single minimal response contract.
- PWA assets (`/manifest.json`, icon/logo paths) remain on web routes and should not be moved.

## Known references (update these)

- `flutter-app/lib/infrastructure/services/dal/dao/laravel_backend/app_data_backend/app_data_backend_stub.dart` currently calls `/environment?app_domain=...`.

## Tasks

- [ ] ⚪ Update `AppDataBackend.fetch()` to request `/api/v1/environment` (preserve query params and error handling).
- [ ] ⚪ Search for any additional `/environment` references in Flutter code and update/remove as needed.
- [ ] ⚪ Update or add tests/mocks if they rely on the old route.
- [ ] ⚪ Remove local fallback from `AppDataRepository` so bootstrap never derives URLs from `packageName`.
- [ ] ⚪ Run validation steps and record results in this TODO.
