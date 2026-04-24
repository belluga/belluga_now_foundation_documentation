# Store Release Usability Recut Quality Audit Package

- **Artifact kind:** `triple_audit_bounded_package`
- **Authoritative:** `false`
- **Purpose:** bounded external audit package for implementation quality review after validated external behavior.
- **Audit target:** quality of the current Store Release usability recut implementation, especially structural elegance, performance, and test quality risks caused by several implementation/reconciliation passes.
- **Out of scope:** product behavior redesign, promotion to `dev`, unrelated backlog, and broad documentation history not tied to the recut implementation.

## Review Instructions

Review the implementation diff against the active development baseline and identify maintainability, architecture, performance, and test-quality concerns that should be improved before promotion.

Prioritize:

- Gambiarras or layered fixes that solve symptoms but leave mixed patterns.
- N+1, cache, hydration, or query-shape risks.
- Client/backend contract mismatches.
- Tests that assert implementation details rather than user-visible or contract behavior.
- Duplicated logic between Flutter UI/controller/domain/repository layers or Laravel service/controller/query layers.
- Any issue likely to create regressions in Event admin, Event occurrence/programming, rich text limits, typed taxonomy filters, or public Home/Discovery filters.

Do not reopen already validated product decisions unless the implementation quality creates a concrete risk.

## Repository Baselines

| Repository | Comparison | Current branch | Current HEAD |
| --- | --- | --- | --- |
| `flutter-app` | `origin/dev...HEAD` | `orchestrator/store-release-usability-wave` | `08dea143 fix(store-release): close flutter usability recut` |
| `laravel-app` | `origin/dev...HEAD` | `orchestrator/store-release-usability-wave` | `588c725 fix(store-release): close laravel usability recut` |
| `belluga_now_docker` | `origin/dev...HEAD` | `orchestrator/store-release-usability-wave` | `837c139 test(web): close store release navigation recut` |
| `foundation_documentation` | `origin/main...HEAD` | `delphi/docs-reconcile-store-release-20260419` | `6f9a688 docs(store-release): record usability recut checkpoint` |

## Diff Files

Primary code and test diffs:

- `diffs/flutter-app.diffstat.txt`
- `diffs/flutter-app.patch`
- `diffs/laravel-app.diffstat.txt`
- `diffs/laravel-app.patch`
- `diffs/docker-root.diffstat.txt`
- `diffs/docker-root.patch`

Documentation/evidence diff, supporting only:

- `diffs/foundation-documentation.diffstat.txt`
- `diffs/foundation-documentation.patch`

## Implementation Areas

Flutter implementation areas:

- `lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart`
- `lib/infrastructure/repositories/tenant_admin/tenant_admin_taxonomies_repository.dart`
- `lib/domain/repositories/tenant_admin_taxonomies_batch_terms_repository_contract.dart`
- `lib/domain/tenant_admin/tenant_admin_taxonomy_terms_by_taxonomy_id.dart`
- `lib/domain/tenant_admin/tenant_admin_taxonomy_terms_for_taxonomy_id.dart`
- `packages/belluga_discovery_filters/**`
- tenant-admin rich text limit surfaces and static asset edit/create screens.

Laravel implementation areas:

- `app/Application/Taxonomies/TaxonomyTermManagementService.php`
- `app/Http/Api/v1/Controllers/TaxonomyTermsController.php`
- `routes/api/tenant_api_v1.php`
- `app/Integration/DiscoveryFilters/EventDiscoveryFilterEntityProvider.php`
- `packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`
- `packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- Event and Static Asset validation requests/constraints.

Web navigation specs:

- `tools/flutter/web_app_tests/discovery_filters.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`

## Validated External Behavior

The external behavior was already validated enough for the user to request this quality audit instead of more product validation:

- Event admin taxonomy section must only show taxonomies allowed by the selected Event Type.
- Event admin taxonomy term loading must use one backend batch endpoint and not per-taxonomy request loops.
- Empty allowed taxonomy groups must not render as title-only blocks.
- Event Type changes must reload compatible taxonomy terms.
- Event programming and occurrence data must persist and reload.
- Single-occurrence programming remains available in the root event editor; after adding another date, programming moves into occurrence editing.
- Rich text surfaces use the 100KB guidance/validation contract.
- Home/Discovery public filters use the simplified type-primary and taxonomy-secondary behavior.

## Executed Validation Evidence

Recent focused validation:

- `fvm flutter test test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart` passed `26 tests`.
- `fvm flutter test test/infrastructure/repositories/tenant_admin_taxonomies_repository_test.dart` passed `5 tests`.
- `fvm dart analyze --format machine` exited `0`.
- `../delphi-ai/scripts/laravel/run_laravel_tests_safe.sh tests/Feature/Taxonomies/TaxonomyRegistryControllerTest.php` passed `4 tests, 22 assertions`.
- `docker compose exec -T app bash -lc 'cd /var/www && ./vendor/bin/pint --dirty'` passed.
- `bash scripts/build_web.sh ../web-app dev` passed.
- Served Web bundle hash matched local bundle after the latest fix: `f391922b121a498d1d7bfc1d72a275298844a16a04dec3e2cdc930b970c82991`.

Earlier recut validation also included Event CRUD, Map discovery filter catalog, Static Asset rich-text limit tests, and focused Playwright mutation specs recorded in the TODO artifacts.

## Reviewer Output Requirement

Return JSON compatible with `schemas/subagent_review_result.schema.json`.

Findings should include:

- severity: `low`, `medium`, or `high`;
- affected paths;
- concrete rationale tied to the diff;
- suggested action;
- category and formalizable hints where applicable.

If no finding is material in your lane, return an empty `findings` array with a concise assessment.
