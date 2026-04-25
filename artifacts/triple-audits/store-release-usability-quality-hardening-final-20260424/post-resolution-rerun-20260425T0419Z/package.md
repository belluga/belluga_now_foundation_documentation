# Store Release Usability Quality Hardening - Post-Resolution Audit Package

## Scope

This package compares `dev` against the current `orchestrator/store-release-usability-wave` worktree after resolving the first audit round findings.

Covered delivery areas:
- Laravel event occurrence/programming persistence, scheduled mutation guards, discovery filter catalogs, taxonomy term batch loading, rich text sanitization, and event/account/static type taxonomy preloading.
- Flutter public/admin event occurrence and programming UI, public Home/Discovery/Map filters, rich text rendering, tenant-admin type/taxonomy editors, shared discovery-filter orchestration, and route/navigation behavior.
- Web navigation harness changes required to validate the final-domain runtime without accepting flaky retries.

## Round 01 Findings Resolved

- `ELEGANCE-001` / `PERF-02`: the previous package omitted new production files because they were untracked. The relevant Flutter and Laravel source files are now added to the index and included in `diffs/flutter-vs-dev.patch` and `diffs/laravel-vs-dev.patch`.
- `PERF-01`: public event programming no longer eagerly builds every programming row in the first frame. It renders the initial bounded set and expands explicitly; the occurrence date selector now uses a horizontal builder.
- `TQ-01`: Android/ADB remains unavailable in this environment. Current project method allows final-domain web navigation as the visible behavior proof when the changed behavior is shared and no platform-specific Android code path is touched. This package records ADB as blocked, not passed.

## Diff Artifacts

- `docker-status.txt`
- `flutter-status.txt`
- `laravel-status.txt`
- `diffs/docker-vs-dev.stat`
- `diffs/docker-vs-dev.files`
- `diffs/docker-current-complete.patch`
- `diffs/flutter-vs-dev.stat`
- `diffs/flutter-vs-dev.files`
- `diffs/flutter-vs-dev.patch`
- `diffs/laravel-vs-dev.stat`
- `diffs/laravel-vs-dev.files`
- `diffs/laravel-vs-dev.patch`

## Completeness Notes

- Flutter status has no `??` source paths after staging the new discovery-filter shared mixin and tenant-admin canonicalizer.
- Laravel status has no `??` source paths after staging the shared rich-text sanitizer.
- The package patch now includes `lib/presentation/shared/discovery_filters/public_discovery_filter_controller_mixin.dart`, `lib/application/tenant_admin/settings/tenant_admin_discovery_filters_settings_canonicalizer.dart`, and `app/Support/RichText/SafeRichTextHtmlSanitizer.php`.

## Validation Evidence

- `fvm dart analyze --format machine`: passed with exit 0 after the performance fix.
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart --plain-name "event detail programming renders large schedules progressively"`: 1 passed.
- `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`: 25 passed.
- `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev`: succeeded and rebuilt the dev web bundle served by the local domains.
- `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly`: 9 passed.
- Focused no-retry Playwright rerun for the formerly flaky event occurrence scenario: 1 passed in 2.8m.
- `NAV_ADMIN_EMAIL=<ephemeral> NAV_ADMIN_PASSWORD=<ephemeral> NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh mutation`: 19 passed in 12.1m, zero flaky.
- Ephemeral `playwright-nav-*` tenant admin users were removed after mutation validation.

## Device Evidence

- `adb devices -l`: no attached Android devices.
- `fvm flutter devices`: only Linux desktop and Chrome web were available.
- No Android integration test is claimed as passed in this package.

## Auditor Focus

Please review against the complete current diff, not the previous package. Pay particular attention to:
- Whether the event programming performance fix is adequate given backend fanout caps.
- Whether discovery-filter shared orchestration is now reviewable and avoids redundant fetch/persist loops.
- Whether the test evidence is sufficient under the shared-behavior web evidence rule, while explicitly preserving the ADB blocked limitation.
