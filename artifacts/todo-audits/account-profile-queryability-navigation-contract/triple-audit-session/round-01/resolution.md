# Triple Audit Round 01 Resolution

Derived artifact. Non-authoritative. Record Delphi adjudication, resolution decisions, validation evidence, and remaining blockers before opening another audit round.

## Status

Choose one when recording with `record-resolution`:

- `resolved`: all material findings were fixed and required validation passed.
- `accepted-debt`: remaining findings are explicitly accepted as non-blocking debt with owner/rationale.
- `blocked`: required evidence or fixes are still blocked; `next-round` must not proceed.

## Adjudication

- The lane recommendation conflict is not material. `approve`, `advance`, and `CLOSE_WITH_DEBT` all agree there is no blocking release risk inside the bounded TODO.
- Delphi re-read the cited code/tests and reran the browser diagnostic after fixing the admin-selector runtime spec. Several audit findings were evidence gaps rather than product/code gaps and are resolved below by explicit citations.
- Remaining items are accepted as non-blocking debt because they request stronger future evidence or wider CI/product hardening, not a required code change for this bounded delivery.

## Resolution Matrix

| Finding | Decision | Resolution / Rationale | Evidence |
| --- | --- | --- | --- |
| `F-A66A477B` | `resolved` | `AccountProfileNestedGroupService` is not a second ad hoc capability authority. It resolves queryable/publicly-navigable type sets through `AccountProfileTypeSetProvider` and pushes member filtering into `publicProfilesById(...)->whereIn('profile_type', $queryableTypes)`. | `laravel-app/app/Application/AccountProfiles/AccountProfileNestedGroupService.php:183-198,364-449` |
| `F-8C460CCD` | `resolved` | Guard allowlist entries are not free-form. The guard enforces non-empty `owner` and `rationale`, reports allowlisted findings with the audit checklist, fails on raw unallowlisted queries, and fails on stale baseline keys. | `laravel-app/scripts/account_profile_queryability_guardrails.php:77-132,321-373`; `laravel-app/tests/Unit/Guardrails/AccountProfileQueryabilityGuardrailsTest.php:20-106` |
| `F-BEB44173` | `resolved` | Queryability filtering is pushed into query predicates, not post-hydration list filtering, across the bounded paths: admin candidate filters, public paginate, nested public group hydration, physical-host resolution, and event-party resolution all issue `whereIn('profile_type', ...)` at the DB layer. | `laravel-app/app/Application/AccountProfiles/AccountProfileTypeSetProvider.php:17-85`; `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php:61-108`; `laravel-app/app/Application/AccountProfiles/AccountProfileNestedGroupService.php:364-380`; `laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php:44-58,128-139,322-354` |
| `F-5CA2CC60` | `accepted-debt` | The stale-reference browser path is intentionally a mutation-gated diagnostic. The bounded delivery requires the local canonical harness with `NAV_RUNTIME_DB_MUTATION_ALLOWED=1`, and that evidence now passed after the spec fix. Wiring the same mutation gate as standard CI coverage is valid follow-up hardening, but it is not a blocking gap for this local TODO closeout. | `NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='QRY-RUNTIME' bash tools/flutter/run_web_navigation_smoke.sh mutation` -> `1 passed (1.3m)` |
| `F-9E74B1B9` | `accepted-debt` | The audit requested stronger query-shape proof such as query-log assertions. Code inspection confirms DB-layer push-down in the bounded paths, so the remaining request is stronger future evidence, not a current correctness/performance blocker. | Same code citations as `F-BEB44173`; focused Laravel suite passed. |
| `F-1AD6269D` | `accepted-debt` | The guard currently enforces entry completeness but does not assert an explicit numeric ceiling on allowlist size. That is worthwhile guard hardening, but the current allowlist is bounded and every entry is owned/rationalized. | `laravel-app/scripts/account_profile_queryability_guardrails.php:394-485`; guard script runtime pass; guardrail unit tests pass. |
| `F-68144284` | `accepted-debt` | The browser/runtime issue that triggered this TODO is closed, but the map detail widget still contains fallback route derivation from `refSlug/refPath` when hydrated profile data is unavailable. Current map controller tests cover hydrated/tap flows; removing or further gating that fallback belongs to a follow-up hardening slice, not this bounded closeout. | `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/widgets/poi_details_deck.dart:490-505,596-624,785-803`; `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart` marker-tap coverage |
| `F-B6246A4B` | `accepted-debt` | Favorites snapshot builder now derives navigability from `AccountProfileTypeSetProvider::isPubliclyNavigable`, but there is not yet a query-log style proof for that enrichment path. The current favorites controller regression tests passed; stronger query-bounding evidence is future hardening. | `laravel-app/app/Integration/Favorites/AccountProfileFavoriteSnapshotBuilder.php:15-88`; `flutter-app/test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart` |
| `F-8750021B` | `accepted-debt` | Local/served bundle hash verification was executed and matched, but the hash comparison remains a manual verification step rather than a reusable CI script. Non-blocking operational debt. | Local hash `db070aee...`; served hash matched in bounded package. |
| `TQA-R01-001` | `accepted-debt` | Same adjudication as `F-5CA2CC60`: the mutation-gated browser diagnostic is intentionally local/harness-driven today. | Canonical local harness pass recorded above. |
| `TQA-R01-002` | `resolved` | The payload boundary assertions already exist. Feature tests assert explicit `can_open_public_detail` / `public_detail_path` values on both account-profile and event public payloads for navigable and non-navigable profiles. | `laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php:549-613,2547-2551`; `laravel-app/tests/Feature/Events/EventCrudControllerTest.php:4797-4804` |
| `TQA-R01-003` | `resolved` | The guardrail unit tests are not shallow smoke only. They cover the real repository allowlist-report path, the negative unallowlisted raw-query path, and the stale/mismatched reviewed-baseline path. | `laravel-app/tests/Unit/Guardrails/AccountProfileQueryabilityGuardrailsTest.php:20-106` |
| `TQA-R01-004` | `resolved` | The map controller suite still exercises marker-tap flows directly, including selected-POI hydration, async loading, and tap-trigger behavior. The earlier note in the bounded package referred to one corrected test path, not the absence of marker-tap coverage overall. | `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart:2038-2798,3694-3748,5511-5921` |
| `TQA-R01-005` | `accepted-debt` | Browser-level admin selector coverage now exists inside the diagnostic spec and passed after hardening the selector interactions, but it remains a mutation-gated diagnostic rather than a standalone always-on web spec. The authoritative backend contract is already covered by Laravel feature tests. | `tools/flutter/web_app_tests/account_profile_queryability_runtime.diagnostic.spec.js`; `laravel-app/tests/Feature/Events/EventCrudControllerTest.php:948-1007` |

## Validation Evidence

- Commands run:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Guardrails/AccountProfileQueryabilityGuardrailsTest.php`
  - `docker compose exec -T app php /var/www/scripts/account_profile_queryability_guardrails.php`
  - `cd flutter-app && fvm flutter test --no-pub test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/presentation/tenant_public/discovery/widgets/discovery_account_profile_visuals_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart`
  - `cd flutter-app && fvm flutter test --no-pub test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart --plain-name "account profile poi"`
  - `cd flutter-app && fvm dart analyze <bounded touched files>`
  - `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`
  - `docker compose restart nginx`
  - `NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='QRY-RUNTIME' bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Passed/failed/blocked gates:
  - All bounded local backend/flutter/browser gates passed.
  - Triple-audit contradiction adjudicated as non-material; no blocking finding remains.
- Runtime/navigation evidence:
  - Admin selector runtime now proves the hidden non-queryable profile cannot be found through the normal event group selector.
  - Public event runtime proves the hidden profile is absent from the tab, the visible public profile navigates, and the visible non-navigable participant remains on the event detail route.

## Open Blockers

- `none` if fully resolved.

## Accepted Non-Blocking Debt

- Mutation-gated browser diagnostic is still a local/harness gate rather than standard CI coverage. Owner: current TODO follow-up / future CI lane hardening.
- Guardrail suite does not yet assert an explicit upper bound on allowlist entry count. Owner: current TODO follow-up / PACED guard hardening.
- Map POI partner fallback still derives from `refSlug/refPath` when hydrated profile data is absent. Owner: future public-map hardening slice.
- Favorites snapshot path lacks explicit query-log style evidence, though behavior is covered and navigability is centralized. Owner: future performance-evidence hardening.
- Bundle-hash verification is still a manual operational check. Owner: future deploy verification automation.

## Next Audit Package Requirements

- Include this resolution artifact in the next bounded package.
- Include any accepted-debt decisions so the next no-context reviewers can distinguish unresolved gaps from explicitly accepted risk.
- Do not open the next round while status is `blocked`.
