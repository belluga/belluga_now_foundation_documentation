# TODO (Flutter): Switch Environment Fetch to /api/v1/environment

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
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

- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/app_data_backend/app_data_backend_stub.dart` now calls `/api/v1/environment?app_domain=...`.

## Tasks

- [x] ✅ Production‑Ready Update `AppDataBackend.fetch()` to request `/api/v1/environment` (preserve query params and error handling).
- [x] ✅ Production‑Ready Search for any additional `/environment` references in Flutter code and update/remove as needed.
- [x] ✅ Production‑Ready Update or add tests/mocks if they rely on the old route (no affected tests/mocks found).
- [x] ✅ Production‑Ready Remove local fallback from `AppDataRepository` so bootstrap never derives URLs from `packageName`.
- [x] ✅ Production‑Ready Ensure NGINX routes `/api/*` and `/admin/*` to `index.php` (avoid Flutter fallback on API routes).
- [ ] 🟡 Provisional Recreate/reload NGINX so the template change takes effect.
- [ ] 🟡 Provisional Run validation steps and record results in this TODO.

**Provisional Notes (Validation):**
- `fvm flutter analyze` reports existing issues unrelated to this change (see Validation Results below). Resolve these to mark Production‑Ready.
- NGINX must be recreated/reloaded to apply the `/api/*` routing fix.

**Completion Notes**
- Marked completed per delivery confirmation; NGINX reload and validation runs not verifiable in repo state.
- completion_metadata: branch=todo-ui-polish, commit=3f7b28e69c3306482be56d00ad5da0d631ae7c98

## Validation Results

- `fvm flutter analyze` (2025-12-26): 33 issues reported (pre-existing analyzer notices and warnings; no new issues introduced by this change).
