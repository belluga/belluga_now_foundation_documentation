# TODO (MVP): Local Dev Domain Bootstrap Without Cloudflare

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed (Superseded by local tunnel strategy)
**Owner:** Delphi
**Date:** 2026-02-08


## Completion Notes (2026-02-21)
- This TODO was closed after product/devops decision changed: local development with tunnel is accepted for the current operating model.
- The remaining “no-tunnel runtime proof” is no longer a gate.
- Related reference: `foundation_documentation/todos/completed/TODO-devops-local-cloudflared-optional.md`.
- If needed later, local no-tunnel validation can be reopened as a separate targeted TODO.

## Objective
Establish a production-safe Flutter bootstrap strategy that allows full local development against the local Docker/Laravel stack without Cloudflare, while avoiding `.env` runtime dependencies inside Flutter (web/mobile compatible).

## Scope
- Flutter (`flutter-app`):
  - Implement deterministic bootstrap origin resolution for app environment fetch (`/api/v1/environment`) on mobile/desktop.
  - Keep current production behavior intact by default.
  - Replace implicit probing/fallback behavior with compile-time lane define files (`--dart-define-from-file`).
  - Support local development defaulting to `dev` lane values, with optional untracked local override file.
- Documentation (`belluga_now_docker`):
  - Add a local-dev README section with exact commands for running Docker and Flutter without Cloudflare.
  - Document lane file strategy (`dev/stage/main`) and local override usage.
- Runtime/DevOps (`belluga_now_docker`):
  - Remove Cloudflare tunnel runtime dependency from local stack (`docker-compose.yml`, `.env.example`, README references).
  - Keep stage/main as hosted URLs only (no local tunnel fallback).
  - Rebuild Docker images and restart only the final required containers.

## Out of Scope
- Cloudflare staging/production setup changes.
- Route/API contract changes in Laravel.
- CI integration tests orchestration in this task.
- Tenant seed automation in Laravel.

## Decisions
- [x] ✅ Production‑Ready Use **code-based runtime bootstrap resolution** in Flutter (not `.env`).
- [x] ✅ Production‑Ready Use **deterministic compile-time defines** via `--dart-define-from-file`; no runtime `.env`.
- [x] ✅ Production‑Ready Keep lane-specific define files: `dev`, `stage`, `main`.
- [x] ✅ Production‑Ready Local runs default to `dev` values with optional gitignored local override file.
- [x] ✅ Production‑Ready Remove implicit bootstrap probing/magic behavior from Flutter bootstrap code.
- [x] ✅ Production‑Ready Remove landlord fallback default and fail fast with meaningful error when landlord/bootstrap origin is missing.
- [x] ✅ Production‑Ready Add setup docs in root `README.md` for team onboarding.

## Definition of Done
- [x] ✅ Production‑Ready N/A (superseded): explicit no-tunnel runtime proof is no longer a release gate.
- [x] ✅ Production‑Ready Production default domain behavior remains unchanged.
- [x] ✅ Production‑Ready Root README includes a tested local workflow (Docker + Flutter web/mobile).
- [x] ✅ Production‑Ready Lane define files (`dev/stage/main`) are present and documented.
- [x] ✅ Production‑Ready Local override file pattern is present (`.example` tracked + real file ignored).
- [x] ✅ Production‑Ready Optional local overrides are documented via lane/local file flow.
- [x] ✅ Production‑Ready `fvm flutter analyze` passes for changed Flutter scope.
- [x] ✅ Production‑Ready `docker-compose.yml` no longer contains `cloudflared` service/profile.
- [x] ✅ Production‑Ready `.env.example` no longer requires `CLOUDFLARE_TUNNEL_TOKEN`.
- [x] ✅ Production‑Ready README has no tunnel onboarding/runtime steps.
- [x] ✅ Production‑Ready Docker stack rebuilt and running only required services (`app`, `worker`, `scheduler`, `nginx`, plus optional local Mongo profile when requested).

## Validation Steps
- [x] ✅ Start local stack without `staging` profile and confirm API reachable at local origin.
- [x] ✅ N/A (superseded): no-tunnel runtime flow is not required by the current local strategy.
- [x] ✅ Run Flutter using lane define file (`--dart-define-from-file=config/defines/dev.json`) and verify compile-time values apply.
- [x] ✅ Run Flutter using local override file and verify override values apply.
- [x] ✅ Run `fvm flutter analyze` in `flutter-app`.
- [x] ✅ Sanity-check README commands from a clean shell session.
- [x] ✅ `docker compose config` succeeds after tunnel removal.
- [x] ✅ `docker compose up -d --build` succeeds for final stack.
- [x] ✅ `docker compose ps` shows only target containers and no `cloudflared`.

## Validation Results
- `COMPOSE_PROFILES=local-db docker compose up -d --build app nginx mongo mongo-init` succeeded after fixing local runtime storage permissions.
- `curl -I http://localhost:8081/api/v1/environment` returned `200`.
- `curl "http://localhost:8081/api/v1/environment?app_domain=com.guarappari.app"` returned tenant payload with local host-based domain data.
- `fvm flutter analyze lib/application/configurations/belluga_constants.dart lib/infrastructure/dal/dao/laravel_backend/app_data_backend/app_data_backend_stub.dart` returned `No issues found!`.
- Lane define files created under `flutter-app/config/defines/` and CI updated to consume `config/defines/<lane>.json`.
- Local override pattern established with `flutter-app/config/defines/local.override.example.json` + gitignored `local.override.json`.
- `fvm flutter test --no-pub --dart-define-from-file=config/defines/dev.json test/application/application_contract_test.dart` passed.
- `fvm flutter test --no-pub --dart-define-from-file=config/defines/dev.json --dart-define-from-file=config/defines/local.override.json test/application/application_contract_test.dart` passed.
- `./tool/with_lane_defines.sh dev test --no-pub test/application/application_contract_test.dart` passed.
- `docker compose config` passed and generated config contains no `cloudflared` service.
- `docker compose --profile local-db --profile production down --remove-orphans` removed legacy profile containers (including previous `cloudflared` container).
- `COMPOSE_PROFILES= docker compose up -d --build app worker scheduler nginx` rebuilt and started final runtime containers.
- `docker compose ps` confirms only `app`, `worker`, `scheduler`, `nginx` are running for this project.

## Provisional Notes
- Previous provisional no-tunnel runtime checks were retired by strategy decision.

## Notes
- This task intentionally avoids introducing Flutter `.env` files to preserve web/mobile compatibility and CI consistency.
- Local tenant resolution still depends on backend data (`app_domain`, tenant/domain records) and may require existing seeded data.
