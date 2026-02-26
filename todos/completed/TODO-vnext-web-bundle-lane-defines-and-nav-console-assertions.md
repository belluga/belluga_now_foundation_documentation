# TODO (VNext): Enforce Lane-Safe Web Defines + Console Error Assertions in Navigation Smoke

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owner:** Delphi
**Date:** 2026-02-25

## Goal
Remove unsafe web build defaulting to dev lane defines and strengthen navigation smoke tests to fail on browser console errors.

## Context / Evidence
- `tools/flutter/build_web_bundle.sh` currently defaults to `flutter-app/config/defines/dev.json` when `FLUTTER_DART_DEFINE_FILE` is unset.
- This can compile stage/main bundles with dev constants/host metadata when callers omit env wiring.
- `installFailureCollectors` captures `consoleErrors`, but navigation tests only assert `runtimeErrors` and `failedRequests`.

## Scope
- Update `tools/flutter/build_web_bundle.sh` to avoid implicit dev fallback and resolve defines file by explicit input:
  - Priority: `FLUTTER_DART_DEFINE_FILE` (explicit) > lane-derived defines file.
  - Lane-derived resolution should use lane signals (`FLUTTER_WEB_LANE`, `DEPLOY_LANE`, `TARGET_BRANCH`, `GITHUB_REF_NAME`).
  - If no explicit file and no resolvable lane file, fail fast with actionable error.
- Update source-owned navigation smoke tests to assert `consoleErrors` is empty:
  - `tools/flutter/web_app_tests/navigation.spec.js`
- Keep generated/runtime mirror aligned for local/manual runs:
  - `web-app/tests/navigation.spec.js`

## Out of Scope
- Changes to route contracts, Flutter app runtime logic, or Laravel endpoints.
- Expanding navigation smoke coverage beyond current assertions.
- Modifying deploy/rollback lane mapping logic.

## Decisions
- Explicit define file remains supported and highest priority.
- Implicit default to `dev.json` will be removed.
- Lane-aware fallback is allowed only through explicit lane signal -> `config/defines/<lane>.json`.
- Console error logs (`message.type() === 'error'`) are treated as smoke test failures.

## Definition of Done
- [x] ✅ Production‑Ready `build_web_bundle.sh` no longer defaults silently to `config/defines/dev.json` when define file is unset.
- [x] ✅ Production‑Ready Build script resolves lane-specific defines from lane signal env vars or fails fast with clear message.
- [x] ✅ Production‑Ready Navigation smoke tests assert `consoleErrors` in both source-owned and mirrored test files.
- [x] ✅ Production‑Ready Syntax validations pass for changed shell/js files.

## Validation Steps
- [x] ✅ Production‑Ready `bash -n tools/flutter/build_web_bundle.sh`
- [x] ✅ Production‑Ready `node --check tools/flutter/web_app_tests/navigation.spec.js`
- [x] ✅ Production‑Ready `node --check web-app/tests/navigation.spec.js`
- [x] ✅ Production‑Ready `rg -n "consoleErrors" tools/flutter/web_app_tests/navigation.spec.js web-app/tests/navigation.spec.js`
- [x] ✅ Production‑Ready `rg -n "FLUTTER_WEB_LANE|FLUTTER_DART_DEFINE_FILE|config/defines" tools/flutter/build_web_bundle.sh`
