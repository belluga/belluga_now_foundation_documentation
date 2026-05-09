# Store Release Usability Quality Hardening Final Audit Package

Derived artifact. Non-authoritative. Review the current working tree against the local `dev` branch.

## Scope
- Laravel: event occurrence/programming persistence, scheduled mutation guards, discovery filter catalogs, taxonomy term batch loading, rich-text sanitization/limits, event/account/static type taxonomy preloading.
- Flutter: public/admin event occurrence/programming UI, public filters for Home/Discovery/Map, rich-text rendering/editing, tenant-admin type/taxonomy editors, shared discovery-filter orchestration, route/navigation behavior.
- Web navigation tests: mutation/read-only Playwright coverage for the final browser-facing domains.

## Diff Inputs
- Docker/tools status: `docker-status.txt`
- Flutter status: `flutter-status.txt`
- Laravel status: `laravel-status.txt`
- Docker/tools diff stat/files: `diffs/docker-vs-dev.stat`, `diffs/docker-vs-dev.files`
- Flutter diff stat/files/full patch: `diffs/flutter-vs-dev.stat`, `diffs/flutter-vs-dev.files`, `diffs/flutter-vs-dev.patch`
- Laravel diff stat/files/full patch: `diffs/laravel-vs-dev.stat`, `diffs/laravel-vs-dev.files`, `diffs/laravel-vs-dev.patch`
- Current uncommitted Playwright fix patch: `diffs/docker-current-uncommitted.patch`

## Validation Evidence
- Flutter analyzer: `fvm dart analyze --format machine` exited 0 with no machine diagnostics.
- Flutter focused changed suite: `160 tests passed`.
- Flutter changed-file suite: `54 changed Flutter test files`, `701 tests passed`.
- Web build: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev` succeeded and updated the browser-facing bundle.
- Playwright readonly: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `9 passed (3.2m)`.
- Playwright mutation focused rerun after card-click fix: `navigation.mutation.event_occurrences.spec.js` filtered to `tenant-admin event occurrence FAB persists second occurrence and public detail selects it` -> `1 passed (2.9m)`.
- Playwright mutation full rerun after card-click fix: `NAV_ADMIN_EMAIL/NAV_ADMIN_PASSWORD` ephemeral non-main tenant-admin, `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_DEPLOY_LANE=dev NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh mutation` -> `19 passed (12.1m)`.
- ADB/device integration: blocked by environment, not passed. `adb connect 192.168.15.9:5555` -> `No route to host`; `adb connect 192.168.15.5:5555` -> `Connection refused`; `fvm flutter devices` listed only Linux and Chrome. Shared Android/Web visible behavior is covered by Playwright final-domain navigation.

## Known Recent Fix To Re-Audit
- The previous mutation lane had one flaky failure in `NAV-01 Agenda card opens selected occurrence URL`. Trace showed the real agenda API response contained the seeded occurrences, but the helper used only raw coordinate clicks. The helper now tries semantic/locator activation (`button`, `group`, then title) before coordinate fallback, and the focused plus full mutation lanes pass without flaky status.

## Audit Request
Run no-context review in three independent lanes:
- Elegance: mixed patterns, unnecessary complexity, duplicated orchestration, package-boundary leakage, naming/API shape risks.
- Performance: N+1 queries, oversized payloads, slow admin taxonomy loading, repeated hydration, stream/cache risks, broad scans/jobs.
- Test Quality: false positives, overbroad assertions, missing negative coverage, flaky navigation, mutation safety, evidence gaps.

Return only findings that are actionable against this diff. If no findings, return a clean lane result.
