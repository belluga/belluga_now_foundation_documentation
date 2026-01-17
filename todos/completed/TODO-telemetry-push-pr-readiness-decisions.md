# TODO — Telemetry/Push PR Readiness Decisions

**Purpose:** Capture the decision points raised during the `telemetry-and-push` vs `mvp` review so we can approve, defer, or scope follow-up tasks before opening a PR.

**context:** Review of `flutter-app` branch `telemetry-and-push` compared to `mvp`.

**scope:** Decision-making plus execution of approved PR-readiness tasks for telemetry/push (including plugin release prep and dependency alignment).

**out_of_scope:** New feature work unrelated to telemetry/push, backend changes, and actual pub publishing.

---

## Decision Log (to resolve before PR)
- [x] ✅ Production‑Ready — Laravel test suite passes in Docker (`php artisan test`).
- [x] ✅ Production‑Ready — API path resolution: keep `BackendContext.baseUrl = https://{host}/api` and use **relative** `v1/...` paths (no leading slash) across:
  - `lib/infrastructure/dal/dao/backend_context.dart`
  - `lib/infrastructure/dal/dao/laravel_backend/auth_backend/auth_backend.dart`
  - `lib/infrastructure/services/push/push_option_source_resolver.dart`
  - `lib/infrastructure/services/http/laravel_map_poi_http_service.dart`
- [x] ✅ Production‑Ready — Map POI endpoint contract decision delegated to `foundation_documentation/todos/active/mvp_slices/TODO-v1-map.md` (see decision note there).
- [x] ✅ Production‑Ready — Anonymous identity bootstrap behavior: retry with bounded attempts (transient connectivity), then fail hard after retries; follow-up tasks tracked in `foundation_documentation/todos/active/mvp_slices/TODO-v1-telemetry-and-push.md`.
- [x] ✅ Production‑Ready — Telemetry route params: deferred beyond the current release; no action required for this PR.
- [x] ✅ Production‑Ready — Documentation sync: update `foundation_documentation/endpoints_mvp_contracts.md` and `foundation_documentation/system_roadmap.md` for:
  - `/api/v1/auth/token_validate`
  - `telemetry`, `telemetry_context`, `firebase`, and `push` fields in the `/environment` payload.
- [x] ✅ Production‑Ready — Plugin code evaluation: reviewed `push_handler` and `event_tracker_handler` codebases (including native registration notes) and updated issues; current versions are safe to publish and consume via pub.
- [x] ✅ Production‑Ready — Push handler API path strategy: keep base URL `/api/v1` and switch to **relative** endpoint paths (no leading slash) when calling:
  - `/push/register`
  - `/push/messages/{id}/data`
  - `/push/messages/{id}/actions`
- [x] ✅ Production‑Ready — Push handler publish hygiene: add `.pubignore` (or equivalent) to prevent local env files and generated artifacts from being published.
- [x] ✅ Production‑Ready — Push handler background isolate storage: guard `FlutterSecureStorage` access in background; catch failures and fallback to queue/no-op without crashing the handler.
- [x] ✅ Production‑Ready — Event tracker init resilience: tolerate partial tracker init failures; catch per‑tracker errors and keep healthy trackers running.
- [x] ✅ Production‑Ready — Submodule documentation alignment: defer summary refresh until adjustments are finished and merged to `mvp`.
- [x] ✅ Production‑Ready — Foundation docs submodule state: skip alignment; do not adjust submodule state for this PR.
- [x] ✅ Production‑Ready — `pubspec.yaml` local `dependency_overrides`: remove overrides **after plugin tasks + tests**, and before PR.

---

**definition_of_done:** Each decision above is marked `✅ Production‑Ready` or moved to a new tactical TODO with scope + owner; PR readiness outcome is explicit.

**validation_steps:** Manual review with Delphi + owner; confirm all decision statuses are updated and any follow-up TODOs exist.
**validation_steps:** Laravel tests pass in Docker (`docker compose exec app php artisan test`).

---

## Task Backlog (Derived from Decisions)
- [x] ✅ Production‑Ready — Diagnose Laravel `InitializationControllerTest` failure in Docker (MongoDB count assertion mismatch).
- [x] ✅ Production‑Ready — Rerun `docker compose exec app php artisan test` until green after fixes.
- [x] ✅ Production‑Ready — Implement bounded retry strategy for `issueAnonymousIdentity` failures (transient connectivity) and fail hard after exhaustion.
- [x] ✅ Production‑Ready — Document retry delays + max attempts for the anonymous identity bootstrap.
- [x] ✅ Production‑Ready — Add unit tests covering retry success + exhaustion failure.
- [x] ✅ Production‑Ready — Add `.pubignore` to `push_handler` so `local.properties`, generated registrants, and env exports do not ship in the published package.
- [x] ✅ Production‑Ready — Update `push_handler` endpoints to use relative paths (no leading slash) for register/unregister, payload fetch, and action reporting.
- [x] ✅ Production‑Ready — Guard background storage access in `push_handler` (catch `MissingPluginException`/storage failures; fallback to queue/no-op).
- [x] ✅ Production‑Ready — Update `event_tracker_handler` init to catch per‑tracker failures and proceed with healthy trackers.
- [x] ✅ Production‑Ready — Run plugin test suites (push_handler + event_tracker_handler) after plugin fixes.
- [x] ✅ Production‑Ready — Prepare `push_handler` release: finalize `CHANGELOG.md` (move `Unreleased` to `0.2.0`), bump `pubspec.yaml` to `0.2.0`, run `flutter pub publish --dry-run` (dry-run warns about dirty git state).
- [x] ✅ Production‑Ready — Prepare `event_tracker_handler` release: add `0.2.0` notes to `CHANGELOG.md`, bump `pubspec.yaml` to `0.2.0`, run `flutter pub publish --dry-run` (dry-run warns about dirty git state).
- [x] ✅ Production‑Ready — Remove `dependency_overrides` from `flutter-app/pubspec.yaml` after plugin tasks + tests complete.
- [x] ✅ Production‑Ready — Update `flutter-app/pubspec.yaml` to depend on `push_handler` and `event_tracker_handler` `^0.2.0` after publishing.
- [x] ✅ Production‑Ready — Update `foundation_documentation/endpoints_mvp_contracts.md`:
  - [x] ✅ Production‑Ready — Add `/api/v1/auth/token_validate` contract (token validation response schema).
  - [x] ✅ Production‑Ready — Extend `/api/v1/environment` payload to include `telemetry`, `telemetry_context`, `firebase`, and `push` sections.
- [x] ✅ Production‑Ready — Update `foundation_documentation/system_roadmap.md`:
  - [x] ✅ Production‑Ready — Add `/api/v1/auth/token_validate` with status and notes.
  - [x] ✅ Production‑Ready — Note `/api/v1/environment` payload extensions and status.
