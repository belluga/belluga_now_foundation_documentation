# Store Release Usability Quality Hardening Final Audit Package

Derived artifact. Non-authoritative. Review the current working tree against the `dev`/`origin/dev` baseline for each repository.

## Review Target

- Root/workflow repo: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker`
- Flutter app repo: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app`
- Laravel app repo: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app`
- Foundation docs repo: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation`
- Web bundle repo: `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app`

Use only this package plus local repository inspection. Do not rely on chat context.

## Scope Summary

This is the final quality-hardening pass after the store-release usability wave:

- Multi-occurrence event UI/admin/public flows, including programação, date selector, occurrence-specific cards, detail tabs, and scheduler/job guardrails.
- Public/Home/Discovery filter behavior, including rollback of over-complex filter settings, event-type taxonomy compatibility, non-favoritable profile type exclusion, visual icon/color fidelity, and persisted filter state.
- Rich text fidelity and size limits for Account Profile bio/content and Event descriptions.
- Taxonomy snapshot/display label correctness and account-profile flat taxonomy index optimization.
- Backend performance hardening for event details, public filters, taxonomy snapshot backfill, and package decoupling.
- Navigation validation hardening: no coordinate `mouse.click` fallback in release-gating Playwright specs; semantic/tappable targets are product requirements.

## Final Hardening Changes Since Prior Audit

- `EventContentHtmlSanitizer` in the events package is package-local and no longer imports host `App\` support code.
- Taxonomy snapshot backfill now caches term snapshots per run and also repairs Account Profile flat taxonomy snapshots.
- Account Profile public filter query now uses `taxonomy_terms_flat` with tenant migration.
- Event detail query reuses preloaded occurrences instead of loading selected occurrence and occurrence list separately.
- Flutter selected occurrence remapping now uses occurrence end date/time.
- Discovery profile cards and nearby row items expose named semantic buttons: `Abrir perfil <name>`.
- Immersive tabs now use `InkWell` plus explicit semantics so Playwright can click real `button` targets instead of text/coordinate fallbacks.
- Playwright web navigation policy blocks coordinate `mouse.click` usage in release-gating specs.
- Playwright web navigation policy now also blocks dropdown selection helpers that fall back to text-click or keyboard option selection instead of semantic `option`/`menuitem` locators.
- Mutation runner supports deterministic manifest-backed shards through `NAV_WEB_SHARD`; ad-hoc `NAV_WEB_GREP_EXTRA` is blocked unless explicitly allowed outside release evidence.
- Event occurrence mutation seed now creates deterministic nearby physical hosts instead of reusing arbitrary tenant candidates that may fall outside the active agenda radius.
- Rich text sanitization now lives in the neutral `Belluga\RichText` package and host/events sanitizers are thin wrappers.
- Account Profile raw rich text payloads are byte-limited before DOM parsing to avoid oversized sanitizer input.
- Taxonomy snapshot repair updates flat projections independently from display snapshots.
- Tenant-admin event form default type/venue hydration was moved out of widget build and into controller dependency loading.
- Web build preserves the `web-app` Playwright harness files while replacing generated Flutter runtime files.
- Mutation shard selection is validated before execution against `navigation_mutation_shards.json`.
- Account-scoped Event update now carries `_account_context_id` into the canonical write service.
- Programming item `place_ref` now validates that the physical-host Account Profile belongs to the active account context.
- Runtime legacy taxonomy term summary resolution now caches taxonomy/term lookups per resolver instance and has query-count guardrail coverage.
- Tenant-admin rich-text editor now delegates imported/output HTML cleanup to the shared `SafeRichHtml` policy.
- Legacy Map filter rule catalog construction now uses the canonical discovery filter catalog builder path instead of maintaining a second catalog builder.

## Validation Evidence

Backend:

- Laravel affected suite: `./scripts/delphi/run_laravel_tests_safe.sh $(cat /tmp/belluga_changed_laravel_tests.txt)`
- Result: `302 passed (1765 assertions)`.
- Post-Round-02 CRUD/security recut: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php`
- Result: `127 passed (769 assertions)`.
- Post-Round-02 performance guardrail recut: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventQueryPerformanceGuardrailTest.php`
- Result: `5 passed (39 assertions)`.

Flutter unit/widget:

- Broad affected suite: `fvm flutter test $(cat /tmp/belluga_changed_flutter_tests.txt)`
- Result: `705 passed`.
- Post-Round-02 focused recut: `fvm flutter test test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart packages/belluga_discovery_filters/test/discovery_filter_core_test.dart test/application/rich_text/safe_rich_html_test.dart`
- Result: `60 passed`.

Flutter analyzer:

- Command: `fvm dart analyze --format machine`
- Final result: exit code `0`, no analyzer output.

Web build:

- Command: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev`
- Result: success.
- Post-Round-02 served bundle freshness: local `web-app/main.dart.js` and public `https://guarappari.belluga.space/main.dart.js?cachebust=quality-hardening-20260425-r02` both resolved to SHA-256 `d263c9c13bbea020c54cf9ea92fb75b35e7de63372b55ed7c1256c4580a67004`.
- Harness preservation check after build: `package.json`, `package-lock.json`, `playwright.config.js`, and `tests/navigation.spec.js` remained present in `../web-app`.
- Local `../web-app/version.json`: `{"app_name":"belluga_now","version":"0.0.1","build_number":"2","package_name":"belluga_now"}`
- Public `https://guarappari.belluga.space/version.json`: same payload.

Web navigation readonly:

- Command: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly`
- Post-Round-02 result: `9 passed (3.3m)`.

Web navigation mutation:

- The same canonical runner was executed in deterministic shards using `NAV_WEB_SHARD`, with no product-test retries and the same dev tenant/landlord targets.
- Manifest selection was validated before each shard. A full list validation selected exactly `18` expected mutation tests.
- APD + Account Profile rich text: `NAV_WEB_SHARD=apd`, `3 passed (1.3m)`.
- Public filters + Event rich text: `NAV_WEB_SHARD=filters`, `4 passed (2.0m)`.
- Map admin filter config: `NAV_WEB_SHARD=map-admin`, `1 passed (25.7s)`.
- Occurrence location/repeated hydration shard: `NAV_WEB_SHARD=occurrences`, `2 passed (1.5m)`.
- Occurrence FAB/multi-occurrence end-to-end shard: `NAV_WEB_SHARD=occurrence-fab`, `1 passed (2.7m)`.
- Admin/media/type-taxonomy/agenda final shard: `NAV_WEB_SHARD=admin-final`, `7 passed (5.2m)`.
- Unique mutation coverage across shards: all `18` manifest-declared mutation specs.

Android/ADB:

- `adb devices -l` showed no connected devices.
- `fvm flutter devices` showed only Linux desktop and Chrome.
- `fvm flutter emulators` found no emulator sources.
- Android integration remains blocked by environment, not passed. For this audit target, web navigation is accepted for behavior that has no specified Android/Web divergence.

## Post-Round-03 Resolution Evidence

Round 03 findings were resolved after this package was first prepared. The current review target includes the following additional changes and evidence:

- Event management occurrence pagination was extracted into `EventManagementOccurrenceQuery`.
- Flutter discovery filter catalog DTO parsing now delegates to the package parser.
- Tenant-admin rich-text limit guidance now derives display labels from `maxContentBytes`.
- Account-scoped event updates now require route-account affinity before creator override can authorize mutation.
- Public account-profile and agenda list APIs reject oversized page sizes and query services clamp direct calls defensively.
- Release-gating Playwright policy now blocks `force:true` clicks and coordinate `mouse.click` usage; changed mutation specs no longer use `force:true`.
- Taxonomy display snapshot readonly spec now respects the public `per_page=50` cap and scans up to `25` pages to preserve runtime dataset coverage.

Post-Round-03 validation:

- Laravel affected suite: `./scripts/delphi/run_laravel_tests_safe.sh $(cat /tmp/belluga_changed_laravel_tests_round03_resolved.txt)` -> `339 passed (2037 assertions)`.
- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no analyzer output.
- Flutter affected suite: `fvm flutter test $(cat /tmp/belluga_changed_flutter_tests_round03_resolved.txt)` -> `689 passed`.
- Web build: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev` -> success.
- Bundle freshness: local `web-app/index.html`, `https://belluga.space/`, and `https://guarappari.belluga.space/` expose `__WEB_BUILD_SHA__=80dd09e6`.
- Web navigation policy guard: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=dev NAV_ADMIN_EMAIL=dummy@example.invalid NAV_ADMIN_PASSWORD=dummy node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` -> passed.
- Web readonly navigation: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `9 passed (1.8m)`.
- Web mutation navigation: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev bash tools/flutter/run_web_navigation_smoke.sh mutation` with runtime-only tenant-admin credentials -> `18 passed (11.7m)`.
- Diff hygiene: `git diff --check` passed in root, `flutter-app`, and `laravel-app`.
- Changed JS/CJS specs under `tools/flutter/web_app_tests` passed `node --check`.

Post-Round-03 resolution record:

- `round-03-adjudication-and-resolution.md` and the session `round-03/resolution.md` are recorded as `resolved`; no blocker remains before opening the next no-context audit round.

## Post-Round-04 Resolution Evidence

Round 04 findings were resolved after the first Round 04 no-context audit. The current review target includes these additional hardening changes:

- Required new source/harness files are now included in `git diff dev` via intent-to-add instead of remaining invisible untracked files.
- Flutter taxonomy batch term loading now has an explicit `TenantAdminTaxonomiesBatchTermsRepositoryContract` collaborator boundary. Production DI registers the concrete taxonomy repository under both the canonical taxonomy contract and the batch contract; a sequential adapter exists only as compatibility fallback for non-batch fakes/custom repositories.
- Public `/events` index now rejects page sizes above `InputConstraints::PUBLIC_PAGE_SIZE_MAX` and controller clamping uses the same public cap outside admin/account management contexts.
- Taxonomy snapshot repair now logs per-document failures, records failure samples in the summary, throws from the queue job when failed documents exist, and returns non-zero from the artisan repair command when any tenant reports failed repair documents.
- `EventCrudControllerTest` candidate search was made deterministic by using the current fixture slug rather than a broad `main` query that could page out the target venue after accumulated in-class fixtures.

Post-Round-04 validation:

- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no analyzer output.
- Flutter focused suite: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` -> `93 passed`.
- Laravel affected suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php tests/Feature/Taxonomies/TaxonomyTermDisplaySnapshotsTest.php` -> `137 passed (811 assertions)`.
- Laravel syntax: `docker exec -w /var/www belluga_now_docker-app-1 sh -lc 'php -l ...'` over the touched Laravel files/tests -> no syntax errors.
- Diff hygiene: `git diff --check` passed in root, `flutter-app`, `laravel-app`, and `web-app`.
- New-file review inclusion: `git diff --name-only --diff-filter=A` in root lists `tools/flutter/web_app_tests/navigation_mutation_shards.json` and `tools/flutter/web_app_tests/web_navigation_shards.cjs`; the same command in Laravel lists `ValidatesAccountProfileRichText.php`, the tenant migration, `EventManagementOccurrenceQuery.php`, and the `belluga_rich_text` package files.

Post-Round-04 resolution record:

- Session `round-04/resolution.md` is recorded as `resolved`; no blocker remains before opening the next no-context audit round.

## Post-Round-05 Resolution Evidence

Round 05 findings were resolved after the latest no-context audit. The current review target includes these additional hardening changes:

- Required new Flutter source and generated web bundle assets are included in tracked review state via intent-to-add.
- Public account-profile index now uses `AccountProfilePublicIndexRequest`, validates all consumed public query keys, and passes only validated input into `publicPaginate`.
- Account-scoped management occurrence pagination now narrows by occurrence profile/location snapshots in the initial `$match` before `$group`, while retaining the joined event match as the authority check.
- Release-gating dropdown helpers now require semantic `option` or `menuitem` targets. Text-click and keyboard option selection fallbacks inside `selectDropdownOption` are blocked by `guard_web_navigation_policy.cjs`.
- Flutter `SafeRichHtml` now unwraps unsupported containers while removing dangerous `script/style` content, aligning its preview/import behavior with the PHP shared DOM sanitizer.

Post-Round-05 validation:

- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no analyzer output.
- Flutter focused suite: `fvm flutter test test/presentation/tenant_admin/shared/tenant_admin_rich_text_editor_test.dart test/presentation/tenant_admin/events/tenant_admin_events_controller_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` -> `97 passed`.
- Laravel focused suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php tests/Feature/Events/EventQueryPerformanceGuardrailTest.php` -> `64 passed (306 assertions)`.
- Laravel syntax: `docker exec -w /var/www belluga_now_docker-app-1 sh -lc 'php -l ...'` over the touched Laravel files/tests -> no syntax errors.
- JS syntax: `node --check tools/flutter/web_app_tests/guard_web_navigation_policy.cjs && node --check tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js && node --check tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js` -> passed.
- Web navigation policy guard: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=orchestrator NAV_ADMIN_EMAIL=dummy@example.test NAV_ADMIN_PASSWORD=dummy node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` -> passed.
- Diff hygiene: `git diff --check` passed in root, `flutter-app`, `laravel-app`, `web-app`, `foundation_documentation`, and `delphi-ai`.
- New-file review inclusion: `git diff --name-only --diff-filter=A` in `flutter-app` lists `lib/application/tenant_admin/discovery_filters/tenant_admin_taxonomies_sequential_batch_terms_repository.dart`; the same command in Laravel lists `AccountProfilePublicIndexRequest.php` plus prior required new Laravel files; the same command in `web-app` lists the two store badge assets and `canvaskit/wimp.*`.

Post-Round-05 resolution record:

- Session `round-05/resolution.md` is recorded as `resolved`; the only residual item is Android execution, explicitly retained as accepted debt because no device/emulator exists locally and no Round 05 finding introduced Android-specific behavior.

## Post-Round-06 Resolution Evidence

Round 06 findings were resolved before opening the next no-context audit. The current review target includes these additional hardening changes:

- `EventManagementOccurrenceQuery` now composes specific-date and future temporal predicates by intersection instead of allowing later constraints to overwrite earlier ones.
- Event/account relevance is denormalized into `account_context_ids` on event and occurrence records, so account-scoped occurrence queries no longer fan out through all profile ids for an account.
- Public agenda and account-profile filter inputs now enforce bounded list sizes, reject unknown public filter keys where applicable, and cap public geo radius input. Direct query-service radius input is clamped defensively.
- Rich text sanitizer behavior is now backed by a shared cross-stack fixture stored inside the Laravel test fixtures and consumed by both PHP and Flutter tests.
- The admin discovery filter rule catalog now uses a deterministic taxonomy ordering and explicit `200` terms-per-taxonomy budget.
- The Playwright navigation policy guard now scans semantic anti-patterns globally instead of depending on a helper name. Release-gating web tests no longer accept legacy profile-card fallback names or keyboard/text option-selection fallbacks.

Post-Round-06 validation:

- Laravel rich-text fixture recut: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` -> `8 passed (60 assertions)`.
- Laravel focused suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventQueryPerformanceGuardrailTest.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` -> `99 passed (439 assertions)`.
- Flutter focused suite: `fvm flutter test test/application/rich_text/safe_rich_html_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` -> `48 passed`.
- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no analyzer output.
- Web navigation policy guard: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=orchestrator NAV_ADMIN_EMAIL=dummy@example.test NAV_ADMIN_PASSWORD=dummy node ../tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` -> passed.

Post-Round-06 resolution record:

- Session `round-06/resolution.md` is recorded as `accepted-debt` only because Android/device execution remains unavailable locally. All non-Android round 06 findings were resolved before this package refresh.
- The next generated effective `round-package.md` includes `round-06/resolution.md`; reviewers should not re-raise Android absence as a hidden omission unless the reviewed diff makes a new Android-specific release claim.

## Post-Round-07 Resolution Evidence

Round 07 findings were resolved before opening the next no-context audit. The current review target includes these additional hardening changes:

- The shared cross-stack rich-text fixture is now included in tracked review state via Laravel intent-to-add, so PHP and Flutter sanitizer evidence is reproducible from `git diff dev`.
- Legacy event and occurrence records now have a tenant migration that backfills `account_context_ids` and creates account-context management indexes.
- Event write/sync code now preserves existing account context ids, merges route account context, and mirrors event account context into occurrence documents.
- Public agenda/event-stream geo coordinates now reject out-of-range latitude/longitude values at request validation; direct query-service use defensively normalizes out-of-range coordinates before geo aggregation.
- The triple-audit merge tool now adds Delphi deterministic core to `sys.path`, making merge execution reliable from downstream repositories.

Post-Round-07 validation:

- Laravel syntax: `docker exec -w /var/www belluga_now_docker-app-1 sh -lc 'php -l ...'` over the Round 07 touched Laravel files/tests -> no syntax errors.
- Laravel focused suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventQueryPerformanceGuardrailTest.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/AccountProfiles/AccountProfileRichTextFidelityTest.php` -> `49 passed (248 assertions)`.
- Laravel event CRUD suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` -> `129 passed (774 assertions)`.
- Flutter rich-text suite: `fvm flutter test test/application/rich_text/safe_rich_html_test.dart` -> `3 tests`.
- Web navigation policy guard: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=orchestrator NAV_ADMIN_EMAIL=dummy@example.test NAV_ADMIN_PASSWORD=dummy node ../tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` -> passed.
- Endpoint performance heuristic: `bash ../delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo ../laravel-app --path packages/belluga/belluga_events/src/Application/Events/EventQueryService.php --path packages/belluga/belluga_events/src/Application/Events/EventManagementOccurrenceQuery.php` -> no high/medium findings.
- Diff visibility: `git diff --name-status dev -- tests/Fixtures/shared_rich_text/safe_rich_html_fixtures.json packages/belluga/belluga_events/database/migrations/2026_04_25_000600_backfill_event_account_context_ids.php ...` shows the shared fixture and backfill migration as added.
- Diff hygiene: `git diff --check` passed in `laravel-app`, `flutter-app`, and `delphi-ai`.

Post-Round-07 resolution record:

- Session `round-07/resolution.md` is recorded as `resolved`; no Round 07 blocker remains before opening the next no-context audit round.
- The next generated effective `round-package.md` must include `round-07/resolution.md` so reviewers can distinguish resolved findings from unresolved omissions.

## Post-Round-08 Resolution Evidence

Round 08 findings were resolved before opening the next no-context audit. The current review target includes these additional hardening changes:

- Event/account-context derivation is centralized in package-local `EventAccountContextResolver`, used by both aggregate event writes and occurrence projection sync.
- Event write payloads now have explicit caps for tags, categories, total taxonomy terms, and unique taxonomy terms through request rules and `EventPayloadFanoutGuard`.
- Map POI polygon discovery scopes now have bounded ring/point budgets, nested coordinate validation, and defensive capability normalization for out-of-range point/polygon coordinates.
- Release-gating Playwright dropdown selection is centralized in `tools/flutter/web_app_tests/support/semantic_dropdown.js`; the navigation policy guard now blocks local `selectDropdownOption` redefinitions outside that shared helper.
- The web navigation harness now has deterministic negative regression tests for coordinate click, force click, credential fallback, text/keyboard dropdown fallback, local dropdown helper duplication, unknown shards, missing/unexpected shard titles, and blocked raw grep.

Post-Round-08 validation:

- PHP syntax: `docker exec -w /var/www belluga_now_docker-app-1 sh -lc 'php -l ...'` over touched Round 08 Laravel files/tests -> no syntax errors.
- Node syntax: `node --check` over touched web-harness scripts/specs/support files -> passed.
- Laravel Event CRUD suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` -> `133 passed (790 assertions)`.
- Laravel Event Query Performance Guardrail suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventQueryPerformanceGuardrailTest.php` -> `8 passed (60 assertions)`.
- Web navigation harness negative suite: `node tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` -> passed.
- Web navigation policy guard: `NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=orchestrator NAV_ADMIN_EMAIL=dummy@example.test NAV_ADMIN_PASSWORD=dummy node tools/flutter/web_app_tests/guard_web_navigation_policy.cjs` -> passed.
- Endpoint performance heuristic: `bash ../delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo ../laravel-app --path packages/belluga/belluga_events/src/Application/Events/EventQueryService.php --path packages/belluga/belluga_events/src/Application/Events/EventManagementOccurrenceQuery.php --path packages/belluga/belluga_events/src/Application/Events/EventAccountContextResolver.php` -> no high/medium findings.
- Diff hygiene: `git diff --check` passed in `laravel-app`, on touched root web-harness files, and on touched audit artifact files.

Post-Round-08 resolution record:

- Session `round-08/resolution.md` is recorded as `resolved`; no Round 08 blocker remains before opening the next no-context audit round.
- The next generated effective `round-package.md` must include `round-08/resolution.md` so reviewers can distinguish resolved findings from unresolved omissions.

## Post-Round-09 Resolution Evidence

Round 09 findings were resolved before opening the next no-context audit. The current review target includes these additional hardening changes:

- Event create/update request validation now uses package-local `EventWriteRules` as the single canonical create/update rule matrix.
- Event write payload preparation and fanout post-validation are shared through `InteractsWithEventWritePayload`, removing duplicated FormRequest wiring.
- Public event SSE replay is bounded in the aggregation pipeline by `InputConstraints::PUBLIC_STREAM_DELTA_LIMIT` before materializing deltas.
- Public page depth is capped at request validation and defensively clamped in query services for agenda, public events, public account-profile index, and public account-profile near.
- `TenantAdminEventFormScreen.build` now consumes a `_TenantAdminEventFormViewModel` built by a private stream scope, separating stream aggregation from scaffold/section rendering.
- `AgendaAndEventsControllerTest` now hard-cleans event fixtures in `setUp` so stream replay tests do not inherit soft-deleted deltas from prior tests.

Post-Round-09 validation:

- PHP syntax: `docker compose exec -T app php -l ...` over touched Round 09 Laravel files/tests -> no syntax errors.
- Laravel Agenda/Event Stream suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php` -> `36 passed (134 assertions)`.
- Laravel Event CRUD suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` -> `134 passed (793 assertions)`.
- Laravel Account Profiles suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` -> `57 passed (214 assertions)`.
- Laravel Event Query Performance Guardrail suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventQueryPerformanceGuardrailTest.php` -> `8 passed (60 assertions)`.
- Flutter event form widget suite: `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` -> `26 passed`.
- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no analyzer output.
- Endpoint performance heuristic: `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --path packages/belluga/belluga_events/src/Application/Events/EventQueryService.php --path app/Application/AccountProfiles/AccountProfileQueryService.php --path packages/belluga/belluga_events/src/Application/Events/EventManagementOccurrenceQuery.php` -> no high/medium findings.
- Diff hygiene: `git diff --check` passed in `laravel-app` and `flutter-app`.

Post-Round-09 resolution record:

- Session `round-09/resolution.md` is recorded as `resolved`; no Round 09 blocker remains before opening the next no-context audit round.
- The next generated effective `round-package.md` must include `round-09/resolution.md` so reviewers can distinguish resolved findings from unresolved omissions.

## Post-Round-10 Resolution Evidence

Round 10 findings were resolved before opening the next no-context audit. The current review target includes these additional hardening changes:

- `web-app/.github/workflows/navigation-validation.yml` no longer merges generated publish PRs with `github.token`; it requires a repo-scoped token and fails fast when unavailable.
- Public Account Profile filter validation is centralized in `AccountProfilePublicFilterRules`.
- Account Profile public query page-size normalization is centralized in `AccountProfileQueryService`, including the public-near default.
- Admin discovery filter row rendering is centralized in `TenantAdminFilterCatalogRow` and reused by both filter-surface and legacy settings UIs.
- Primary occurrence programação edits now commit through `TenantAdminEventOccurrenceEditorDraft` methods instead of manual screen-level list mutation.
- Web navigation viewport detection now treats actual viewport intersection as visible, avoiding false negatives on Flutter Web semantic nodes whose bounding-box center can sit outside the viewport while the tappable card is visible.
- Participant-only programação card assertions now allow Flutter's container + chip semantics while still blocking fabricated fallback-title duplication.

Post-Round-10 validation:

- Readiness: `bash delphi-ai/verify_context.sh` -> `Environment Verified: PACED-Ready`.
- Laravel Account Profiles suite: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` -> `59 passed (216 assertions)`.
- Flutter focused suite: `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` -> `66 passed`.
- Flutter analyzer: `fvm dart analyze --format machine` -> exit `0`, no output.
- Web build: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev` -> success.
- Bundle freshness: local `web-app/main.dart.js`, `https://guarappari.belluga.space/main.dart.js?cachebust=round10-20260425`, and `https://belluga.space/main.dart.js?cachebust=round10-20260425` all resolved to SHA-256 `24898463e5c6cd7fe73d6275248dc25dd47e1eb05242c66cc9b1265b3b905d42`.
- Web readonly navigation: canonical runner against public dev landlord/tenant domains -> `9 passed (3.9m)`.
- Web mutation occurrence shard recut: `NAV_WEB_SHARD=occurrence-fab` -> `1 passed (2.9m)`.
- Web mutation full release-gating suite: canonical runner against public dev landlord/tenant domains with runtime-only tenant-admin credentials -> `18 passed (14.6m)`.
- Web navigation harness policy regression test: `node tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` -> passed.
- JS syntax: `node --check` over touched web navigation harness files -> passed.
- Diff hygiene: `git diff --check` passed at root, `flutter-app`, `laravel-app`, and `web-app`.
- Credential hygiene: current `tools/flutter/web_app_smoke_runner/test-results` contains only `.last-run.json`; restricted scan over current test-results, current audit package, and touched code/docs found no runtime credential values.

Post-Round-10 resolution record:

- Session `round-10/resolution.md` is ready to be recorded as `resolved`; no Round 10 blocker remains before opening the next no-context audit round.
- The next generated effective `round-package.md` must include `round-10/resolution.md` so reviewers can distinguish resolved findings from unresolved omissions.

## Post-Round-11 Gate Calibration And Resolution

Round 11 exposed a methodology issue: the prior loop was treating zero findings as the close condition, which allowed marginal elegance/performance observations to extend the audit indefinitely. The audit skill was recalibrated so the release gate closes on zero unresolved blocking findings, with lane-specific blocker definitions:

- Performance blocks only concrete severe server/runtime risks such as unbounded scans, request loops where one endpoint/query is required, exact lookup through page walking, high-cardinality in-memory filtering, fetch-all scheduler reconciliation, or resource-exhaustion/security exposure.
- Elegance blocks only structural remnants that contradict the canonical direction and create real drift, duplicate old/new paths likely to diverge, package-first/domain boundary violations, or elegance issues that also carry correctness/performance/security risk.
- Test-quality blocks missing or invalid evidence for final behavior, CRUD/mutation, backend semantics, required navigation/integration gates, real-backend coverage, CI execution, or mocks/fallbacks that hide production behavior.

Round 11 resolution:

- `TQ-R11-001` was the only release blocker. It was fixed by wiring stage mutation navigation runtime credentials from GitHub Actions secrets into `.github/workflows/orchestration-ci-cd.yml`.
- `tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` now statically verifies the stage mutation workflow keeps `STAGE_NAV_ADMIN_EMAIL` and `STAGE_NAV_ADMIN_PASSWORD` wired.
- `R11-ELEGANCE-001`, `R11-ELEGANCE-002`, and `PERFSEC-R11-001` were accepted as non-blocking debt under the calibrated gate.
- Session `round-11/resolution.md` is recorded as `accepted-debt`; no unresolved Round 11 blocker remains.

Post-Round-11 validation:

- Workflow syntax: `.github/workflows/orchestration-ci-cd.yml` parsed successfully with PyYAML.
- Web navigation harness policy: `node tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` -> passed.
- Delphi script syntax: `python3 -m py_compile skills/audit-protocol-triple-review/scripts/triple_audit_session.py tools/subagent_review_merge.py` -> passed.
- Delphi self-check: `bash self_check.sh` -> 0 failures; Cline/public Codex mirrors synchronized.
- Downstream adherence sync: `bash delphi-ai/verify_adherence_sync.sh` -> passed.
- Secret provisioning: GitHub Actions secrets `STAGE_NAV_ADMIN_EMAIL` and `STAGE_NAV_ADMIN_PASSWORD` are present in `belluga/belluga_now_docker`.

## Known Review Risks

- The diff is intentionally broad and spans multiple repos. Auditors should inspect package boundaries and not only test outcomes.
- Web bundle output in `web-app` is generated and should not be reviewed as hand-written code, except for deployment/build consistency.
- Playwright shard support is a harness capability. It must not be interpreted as reducing coverage; each shard still runs through `tools/flutter/run_web_navigation_smoke.sh` and is validated against the committed manifest.
- `NAV_WEB_GREP_EXTRA` must not be used to hide failed tests in promotion evidence; release-gating shard evidence must use `NAV_WEB_SHARD`.
- Android integration is still unavailable locally.
- Post-Round-03 mutation Playwright evidence requires runtime tenant-admin credentials and passed in this local shell; credentials must remain runtime-only and must not be committed into specs or docs.
- Earlier navigation tests had a coordinate-based click workaround because direct clicks failed on some Flutter Web targets. That history can be inspected in the `dev` baseline if useful. Current mutation evidence passed without coordinate fallback; if a future Flutter Web target cannot be clicked semantically, auditors should distinguish a product semantics/tappability issue from a narrowly justified harness exception.
- Round 05 tightened dropdown selection policy. If a mutation test now fails because a Flutter Web dropdown exposes no semantic `option` or `menuitem`, treat that as a product semantics/tappability issue unless a narrow, documented non-release exception is approved.
- Round 06 accepted Android/device execution as explicit debt only. Web/Laravel/Flutter unit-widget evidence must not be interpreted as Android compatibility proof.

## Suggested Diff Commands

Root:

```bash
git -C /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker diff dev -- tools/flutter
```

Flutter:

```bash
git -C /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app diff dev -- .
```

Laravel:

```bash
git -C /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app diff dev -- .
```

Docs:

```bash
git -C /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation status --short
```

Web bundle:

```bash
git -C /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app diff origin/dev --stat
```

## Auditor Instructions

Run three independent lanes:

- Elegance/Clean Code: identify mixed patterns, duplicated logic, package-boundary violations, naming/API shape issues, and avoidable complexity.
- Performance/Security: identify unbounded queries, N+1 loops, unsafe payload fanout, expensive UI loops, over-fetching, stale scheduler/job mutation paths, and security/tenant-scope risks.
- Test Quality: identify false-green tests, over-mocked behavior, missing negative cases, non-deterministic seeds, insufficient navigation/integration evidence, and gaps around web/mobile parity.

Return only JSON compatible with the triple-audit result schema.
