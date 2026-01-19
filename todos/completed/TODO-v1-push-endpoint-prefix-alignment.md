# TODO (V1): Push Endpoint Prefix Alignment (Flutter)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter)
**Objective:** Align Flutter push transport endpoints with Laravel `/api/v1` routes by fixing the base URL/prefix mismatch.

---

## References
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/completed/TODO-v1-push-delivery-consolidated.md`
- Laravel routes: `laravel-app/packages/belluga/belluga_push_handler/routes/push_handler.php`

---

## Scope
- Update Flutter push transport base URL to include `/v1` so `push/register`, `push/unregister`, and push message endpoints resolve to `/api/v1/...`.
- Confirm other Laravel-backed adapters already append `/v1` and remain unchanged.
- Add temporary runtime logging to capture the exact push registration URL + auth header presence (no token value).
- Re-run integration tests or device run to capture the log output, then remove the temporary logging.

## Out of Scope
- Any Laravel route changes or config overrides.
- Push feature expansion, payload schema changes, or new endpoints.
- Refactors outside push transport path resolution.

## Tasks
- [x] ✅ Production‑Ready Identify the current push transport base URL assembly path.
- [x] ✅ Production‑Ready Update the push transport config to use `{baseUrl}/v1/` (keeping other adapters intact).
- [x] ✅ Production‑Ready Verify push endpoints resolve to `/api/v1/push/*`.
- [x] ✅ Production‑Ready Add temporary logging/interceptor to print resolved push register URL + auth header presence.
- [x] ✅ Production‑Ready Run Flutter unit/widget tests and targeted integration test(s) or device run to capture the log output.
- [x] ✅ Production‑Ready Remove temporary logging once the URL is confirmed.

## Definition of Done
- Push registration hits `/api/v1/push/register` from Flutter.
- No regression in other `/v1` paths.
- Tests: `fvm flutter test` passes; integration test(s) run to completion (or document external blockers).
- Temporary logging removed after confirmation.

## Validation Steps
- Run `fvm flutter test`.
- Run `fvm flutter test integration_test/feature_map_event_filter_actions_test.dart -d <device> --flavor guarappari`.
