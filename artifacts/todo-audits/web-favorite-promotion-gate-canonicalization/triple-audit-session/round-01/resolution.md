# Triple Audit Round 01 Resolution

Derived artifact. Non-authoritative. Record Delphi adjudication, resolution decisions, validation evidence, and remaining blockers before opening another audit round.

## Status

Choose one when recording with `record-resolution`:

- `resolved`: all material findings were fixed and required validation passed.
- `accepted-debt`: remaining findings are explicitly accepted as non-blocking debt with owner/rationale.
- `blocked`: required evidence or fixes are still blocked; `next-round` must not proceed.

## Adjudication

- Lane recommendations do not conflict materially. `approve` and `accept` both mean the bounded TODO can proceed with no blocking finding.
- Performance returned clean. Elegance and test-quality returned only `low` findings.
- None of the findings contradict the canonical implementation direction: favorite gates use the app-promotion modal backed by `AppPromotionScreenController`, favorite mutation fails closed before auth, anonymous web opens modal first, and runtime navigation evidence passed.
- The remaining findings are valid maintainability/completeness observations and are accepted as non-blocking debt for this TODO.

## Resolution Matrix

| Finding | Decision | Resolution / Rationale | Evidence |
| --- | --- | --- | --- |
| `elegance-001` | `accepted-debt` | The legacy `AppPromotionDialog` class remains outside this TODO's bounded favorite-gate scope. Source scan confirms no favorite-gate path still calls `AppPromotionDialog.show`; removing the class globally would expand scope into unrelated product surfaces. | `rg -n "AppPromotionDialog\\.show|Continue pelo app|Para favoritar perfis e receber novidades|Entrar para favoritar|/open-app" flutter-app/lib/presentation/shared flutter-app/lib/presentation/tenant_public tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js -S`; bounded package `Known Non-Scope`. |
| `elegance-002` | `accepted-debt` | The modal/full route share controller-backed store/action widgets while serving different UX contexts. The remaining modal vs route shape is intentional compact-modal composition, not duplicated business logic. | `app_promotion_modal.dart`, `app_promotion_store_actions.dart`, `app_promotion_brand_icon.dart`, `app_promotion_download_experience.dart`; analyzer and rule matrix passed. |
| `TQ-001` | `accepted-debt` | Playwright covers Account Profile and Discovery, while event linked-profile behavior is covered by focused controller and screen tests. This is enough for the reported regression; adding event runtime Playwright is useful completeness debt but not a release blocker. | 169-test focused suite passed; final 56-test focused rerun passed; `favorite_auth_gate_runtime.diagnostic.spec.js` passed against rebuilt web bundle. |
| `TQ-002` | `accepted-debt` | Modal tests assert store target rendering and controller fallback; production uses `AppPromotionScreenController` and shared store action widget. Explicit telemetry/URI parity assertions would strengthen future contract coverage but are not required to prove the current modal-first/store-target bugfix. | `app_promotion_modal_test.dart`; `AppPromotionStoreActions` uses controller URI/action path; source scan shows no ad hoc favorite modal bypass. |

## Validation Evidence

- Commands run:
  - `fvm flutter test --no-pub test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_modal_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` passed with 169 tests.
  - `fvm flutter test --no-pub test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_modal_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` passed with 56 tests after final analyzer-fix iteration.
  - `fvm dart analyze --format machine` passed.
  - `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` passed.
  - `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` passed.
  - `NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` passed.
- Passed/failed/blocked gates:
  - Passed: focused Flutter tests, analyzer, analyzer rule matrix, web build, source-owned Playwright diagnostic, triple audit reviewer schema registration and merge.
  - Failed/blocked: none.
- Runtime/navigation evidence:
  - Source-owned Playwright diagnostic validated anonymous web favorite modal-first behavior on Account Profile and Discovery against the rebuilt `https://guarappari.belluga.space` bundle.

## Open Blockers

- `none` if fully resolved.

## Accepted Non-Blocking Debt

- `elegance-001`: owner/surface `app-promotion module`; future cleanup can remove or fully canonicalize legacy `AppPromotionDialog` outside favorite-gate scope.
- `elegance-002`: owner/surface `app-promotion compact modal/full route`; accepted because shared controller/store widgets prevent business-logic drift.
- `TQ-001`: owner/surface `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`; future completeness pass can add event linked-profile runtime navigation.
- `TQ-002`: owner/surface `app_promotion_modal_test.dart`; future contract-hardening pass can assert telemetry/URI parity explicitly.

## Next Audit Package Requirements

- Include this resolution artifact in the next bounded package.
- Include any accepted-debt decisions so the next no-context reviewers can distinguish unresolved gaps from explicitly accepted risk.
- Do not open the next round while status is `blocked`.
- No next round is required for this TODO because the round has no unresolved blocking finding; remaining findings are accepted as non-blocking debt.
