# TODO (V1): Discovery Query Hardening (Radius Priority + Near + Live Now)

**Status:** Completed — production-ready.
**Owners:** Delphi (Flutter + Laravel)
**Complexity:** `medium`
**Checkpoint Policy:** one implementation checkpoint + final adherence review.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/accounts_module.md`

## Context
User approved current Discovery UI and requested hardening in three areas:
1. Home radius must follow **user preference first**, otherwise **tenant default** (no hardcoded fallback behavior).
2. Discovery `Próximos a você` must always source from the dedicated near query, never from generic Discovery cache; results must be nearest-first and exclude profiles without POI eligibility.
3. `Tocando agora` must be backed by a real query for events happening now with involved artists.

## Scope
- Flutter + Laravel implementation for the three issues above.
- Add tests across layers to cover root causes and prevent regressions.

Out of scope:
- New admin UX.
- Non-discovery redesign.

---

## Decision Baseline (Frozen)
- `D-01` Radius resolution priority is **user preference > tenant default**.
- `D-02` Discovery near query remains real backend GEO query; no local fallback to paged/all-profile Discovery cache and no hardcode.
- `D-03` Account Profiles near aggregation parsing must support Mongo aggregate row IDs robustly.
- `D-04` `Tocando agora` must come from event-occurrence data (currently-live window), including artists in response.
- `D-05` Changes require regression tests in Flutter and Laravel covering failure paths and happy paths.
- `D-06` `Próximos a você` eligibility is POI-backed only; profiles without POI eligibility must not be returned by the near surface.
- `D-07` Near results must be rendered in backend distance order (nearest first); Flutter must not reshuffle or substitute generic cached profiles.
- `D-08` V1 geo truth for `Próximos a você` remains `account_profiles.location` via indexed backend geospatial query; `POI-backed only` is a server-side eligibility filter and does not replace the distance source.

## Plan
- [x] ✅ Production‑Ready — Radius priority
  - [x] ✅ Production‑Ready — Update radius source-of-truth initialization so user preference is honored first.
  - [x] ✅ Production‑Ready — Ensure Home uses resolved preference/default without hardcoded behavior.
  - [x] ✅ Production‑Ready — Add/update Flutter tests for radius priority and request payload.
- [x] ✅ Production‑Ready — Near endpoint hardening
  - [x] ✅ Production‑Ready — Remove Flutter repository fallback that reuses generic paged/all-profile cache for `Próximos a você`; the surface must fetch the dedicated near endpoint.
  - [x] ✅ Production‑Ready — Invert existing Flutter tests that currently lock cache reuse and replace them with dedicated-near-source assertions.
  - [x] ✅ Production‑Ready — Fix ID extraction/parsing in Laravel near query aggregation result handling.
  - [x] ✅ Production‑Ready — Tighten backend near-query eligibility so only POI-backed profiles are returned, ordered nearest-first.
  - [x] ✅ Production‑Ready — Add Laravel tests that cover aggregate payload variants (`id` and `_id`), POI-only eligibility, and distance ordering.
  - [x] ✅ Production‑Ready — Add/adjust Flutter integration/unit tests validating near list display path, preserving backend order, and proving generic Discovery cache items are never substituted into `Próximos a você`.
- [x] ✅ Production‑Ready — Live now query
  - [x] ✅ Production‑Ready — Define/implement backend query contract for events happening now including artists.
  - [x] ✅ Production‑Ready — Wire Flutter Discovery `Tocando agora` to this query.
  - [x] ✅ Production‑Ready — Add Laravel + Flutter tests for now-window behavior and artists projection.

## Validation
- [x] ✅ Production‑Ready — Laravel targeted test suite for account profiles near + events live-now.
- [x] ✅ Production‑Ready — Flutter targeted tests for Discovery controller + Home radius behavior.
- [x] ✅ Production‑Ready — Manual smoke on Discovery web build: `Perto de você` rendered from the dedicated near query in nearest-first order; `live_now_only` request was emitted successfully on Discovery; current tenant/date returned no visible `Tocando agora` cards. Home radius priority remains covered by automated tests.

## Decision Adherence Validation
- `D-01`: Adherent — Home radius default now resolves `user preference > tenant default` in `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart:817-829`, with proof in `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart:76-100`.
- `D-02`: Adherent — `syncDiscoveryNearbyAccountProfiles()` now always fetches the dedicated near source in `lib/domain/repositories/account_profiles_repository_contract.dart:136-152`; regression proof in `test/infrastructure/repositories/account_profiles_repository_test.dart:285-342`.
- `D-03`: Adherent — aggregate-row `_id/id` parsing is covered in `tests/Unit/Application/Accounts/AccountProfileQueryServiceTest.php:16-42`.
- `D-04`: Adherent — backend live-now filtering is implemented in `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php:387-405`, and Laravel/Flutter proofs live in `tests/Feature/Events/AgendaAndEventsControllerTest.php:163-209` plus `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:399-668`.
- `D-05`: Adherent — regression coverage is green on both stacks: Laravel (`./scripts/delphi/run_laravel_tests_safe.sh ...`) and Flutter (`fvm flutter test test/infrastructure/repositories/account_profiles_repository_test.dart`, `fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`, `fvm flutter test test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`).
- `D-06`: Adherent — backend near eligibility now intersects favoritable + POI-enabled types in `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php:80-83` and `:437-447`, with feature proof in `laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php:247-326`.
- `D-07`: Adherent — backend near ordering remains nearest-first and Flutter preserves that order without cache substitution, proven in `laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php:311-325`, `test/infrastructure/repositories/account_profiles_repository_test.dart:285-342`, and `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart:224-279`.
- `D-08`: Adherent — V1 geo truth remains `account_profiles.location` via `$geoNear` in `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php:117-151`; POI-backed-only is enforced only as registry eligibility in `:437-447`.

## Manual Validation Evidence
- 2026-03-31 web smoke on `https://guarappari.belluga.space/descobrir`: `Perto de você` rendered `Ika Poke`, `Perin`, `Carvoeiro`, `Casa Marracini`, `Maratimbas`, `Esquinas` in ascending displayed distance (`397m`, `550m`, `679m`, `753m`, `4.9km`, `7.1km`), with no generic cache/fallback cards visible.
- 2026-03-31 Playwright network capture on `https://guarappari.belluga.space/descobrir`: `GET /api/v1/account_profiles/near?...` returned `200`, and `GET /api/v1/agenda?...live_now_only=1...` returned `200`.
- 2026-03-31 Discovery UI smoke: current tenant/date produced no visible `Tocando agora` section despite the successful `live_now_only` request, so absence was consistent with current data rather than a broken query surface.
