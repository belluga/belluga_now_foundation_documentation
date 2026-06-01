# TODO (Fast Follow Bugfix): Event Cold-Start Deep Link Routing

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production validation found an app-link parity regression:

- Deep link to Account Profile works with the app cold and with the app already open.
- Deep link to Event detail works when the app is already open, but does not work when the app is cold.

This is a production-facing continuation failure. It is not a visual issue and it must not be absorbed into the immersive hero/action delivery. The bug belongs to the native app-link/startup routing path for tenant-public Event detail URLs.

Known initial evidence from local inspection before this TODO was opened:

- Android generated app-link path prefixes include `/agenda/evento`.
- Guarappari flavor app-link hosts include `guarappari.com.br`, `guarappari.belluga.space`, `guarappari.belluga.app`, and `guarappari.booraagora.com.br`.
- Flutter startup routing passes non-root deep links through `AppStartupNavigationCoordinator`.
- Account Profile route `/parceiro/:slug` is covered and works in production.
- Event route `/agenda/evento/:slug` exists under `ScheduleModule`, but the current cold-start test coverage does not prove the full installed-app event deep-link path.

These are clues, not root cause. Implementation must reproduce the cold-start failure first and then add fail-first coverage for the failing stage.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `event-cold-start-deep-link-routing`
- **Why this is the right current slice:** the symptom is one bounded routing/runtime bug: Event detail URLs do not survive cold app startup while Account Profile URLs do.
- **Direct-to-TODO rationale:** the production report is concrete, route-specific, and already separated from the approved hero action work.

## Contract Boundary
- This TODO owns Android installed-app cold-start routing for tenant-public Event detail deep links.
- It covers Flutter startup/deep-link routing, Android app-link manifest generation, native intent validation, and Laravel well-known/app-link association only if the failing evidence points there.
- It must preserve Account Profile, Static Asset, Invite, Map, and Home app-link behavior.
- It must not change Event invite semantics, immersive hero actions, event detail content layout, or unrelated web route behavior.
- It must not create a parallel promotion lane; any fix lands on the current approved branch stack and promotes with the active package.

## Delivery Status Canon
- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Fast-Follow`, `Production-Visible`, `Android`, `Flutter`, `Web-to-App`, `Deep-Link`, `Regression-Fix`
- **Next exact step:** reproduce the Event cold-start failure on the current Guarappari build, then add fail-first coverage for the exact failing stage before implementation.
- **Promotion lane path:** `dev -> stage -> main`

## Scope
- [ ] Reproduce Event detail deep link with the app not running and record expected vs actual outcome.
- [ ] Run the same cold-start and warm-start matrix for Account Profile as the known-good comparator.
- [ ] Identify whether the failure occurs in Android verification/intent resolution, generated manifest path matching, Flutter startup route resolution, route guard/bootstrap sequencing, or event-detail hydration.
- [ ] Add fail-first tests for the failing stage.
- [ ] Add parity coverage proving `/agenda/evento/:slug` and `/parceiro/:slug` both survive cold app startup.
- [ ] Preserve existing warm-start Event behavior.
- [ ] Preserve anonymous web direct route behavior for `/agenda/evento/:slug`.
- [ ] Record real-device or equivalent ADB evidence after the fix.

## Out of Scope
- [ ] Redesigning Event detail UI or immersive hero behavior.
- [ ] Changing invite/share/favorite semantics.
- [ ] Changing public Event payload shape unless the root cause proves payload bootstrapping is the failing stage.
- [ ] Broad route-path refactor.
- [ ] iOS Universal Links; iOS remains owned by the iOS fast-follow TODO unless the same root cause is proven shared.

## Definition of Done
- [ ] A deterministic reproduction record exists for the current broken Event cold-start path.
- [ ] At least one automated test fails on the pre-fix behavior and passes after the fix.
- [ ] Real-device or ADB evidence proves Event detail opens from a cold app state for the current Guarappari build.
- [ ] The comparator Account Profile deep link still opens from a cold app state.
- [ ] Warm-start Event deep link behavior remains green.
- [ ] Browser/web direct Event URL behavior remains green.
- [ ] The root cause is stated at the exact failing stage and tied to code/test evidence.
- [ ] No remaining false-green coverage gap exists for the cold-start Event route.

## Validation Steps
- [ ] RED: reproduce cold-start Event failure with an installed Guarappari build.
- [ ] RED: add a failing automated test for the cold-start Event route stage that is actually broken.
- [ ] GREEN: focused Flutter route/startup/platform test suite passes after implementation.
- [ ] GREEN: generated Android app-link manifest test proves `/agenda/evento` remains in the Guarappari app-link path set.
- [ ] GREEN: Account Profile and Event app-link parity tests pass.
- [ ] GREEN: build the current app/web artifact with the canonical project script when the touched surface requires it.
- [ ] GREEN: run device/browser smoke for:
  - Account Profile cold start: `/parceiro/:slug`
  - Event cold start: `/agenda/evento/:slug`
  - Event warm start: `/agenda/evento/:slug`
  - Tenant web direct route: `/agenda/evento/:slug`

## Mandatory Bug-Fix Questions
| Question | Initial Answer |
| --- | --- |
| Do we already have tests that cover this behavior across all stages up to UI display? | No. Current evidence covers pieces of the chain, and Account Profile works, but there is no end-to-end cold-start Event deep-link proof. |
| Did we inspect current real database/backend payloads to verify compatibility with current parsing and rendering assumptions? | Not yet. The first implementation step must inspect the real Event URL/payload used for reproduction and verify whether route hydration or payload loading is involved. |
| If existing tests should cover this bug, which exact test(s) failed? If none failed, why were they insufficient? | None failed yet. Existing tests are insufficient because they do not compare cold-start native app-link handling for Event and Account Profile under the same build/host conditions. |
| If tests do not cover the failure, which new tests must be created before implementing the fix? | Add cold-start route/startup parity coverage for `/agenda/evento/:slug` vs `/parceiro/:slug`, generated app-link manifest coverage for Event paths, and a device/ADB smoke that launches the Event URL from a not-running app state. |
| Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? | Pending root cause. If the issue is route/app-link manifest parity drift, add a deterministic manifest/router parity guard. If it is runtime sequencing or OS intent behavior, prefer targeted regression tests over a static analyzer rule. |

## Coverage Matrix
| Stage | Current Status | Required Closure |
| --- | --- | --- |
| Host association / `assetlinks.json` | `unknown` | Verify production/local-public host association payload includes the Guarappari app id and signing fingerprint for the tested host. |
| Android manifest path matching | `partial` | Generated manifest test must prove `/agenda/evento` is included for Guarappari and remains in the merged app-link surface. |
| Native intent cold start | `missing` | ADB or real-device smoke must prove Event URL launches the installed app from a not-running state. |
| Flutter startup deep-link builder | `partial` | Test must prove non-root Event route is preserved through startup, not collapsed to root/home/bootstrap. |
| AutoRoute module matching | `missing` | Test must prove `/agenda/evento/:slug` resolves under cold-start router state as reliably as `/parceiro/:slug`. |
| Route guard/bootstrap sequencing | `unknown` | If failing, add regression coverage that guards do not drop or replace the Event target during cold bootstrap. |
| Event detail hydration/render | `unknown` | If failing, add payload/controller/widget coverage for loading the event after route restoration. |
| Account Profile comparator | `runtime-covered-by-report` | Keep it as a control in automated and device evidence to prevent fixing Event by weakening app-link routing globally. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one planning/reproduction checkpoint before code changes unless the first failing test makes the fix mechanically obvious.
- **Why this level:** the symptom is one route family, but the chain crosses Android app links, Flutter startup routing, guards, and public Event detail hydration.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter/android`
- **Expected supporting profiles:** `assurance-tester-quality`, `operational-devops` if production/stage app-link association or promotion validation is touched.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why The Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Cold-start deep-link bugs are false-green prone and require route/device evidence. | Flutter route tests, Android manifest, ADB/browser smoke | `planned` |

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets:**
  - Flutter client experience module: Event detail app-link cold-start parity with Account Profile.
  - Onboarding flow module: web-to-app continuation preserves tenant-public Event route intent if the root cause touches promotion/deep-link continuation.
- **Module decision consolidation targets:** same as planned promotion targets after root cause is confirmed.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Event detail app links must cold-start to the intended Event detail route on Android, matching Account Profile app-link behavior.
- [x] `D-02` Startup/bootstrap routing must preserve valid tenant-public route intent; unresolved continuation may fall back only through explicitly approved policy.
- [x] `D-03` Generated native app-link config and Flutter route tables must stay parity-checked for tenant-public direct route families.
- [x] `D-04` The fix must not weaken existing Account Profile, Static Asset, Invite, Map, Home, or web direct-route behavior.

## Execution Plan
### Touched Surfaces To Inspect First
- `flutter-app/android/app/build.gradle.kts`
- `flutter-app/android/keystores/guarappari.properties`
- `flutter-app/lib/application/application_contract.dart`
- `flutter-app/lib/application/startup/app_startup_navigation_coordinator.dart`
- `flutter-app/lib/application/router/modular_app/modules/schedule_module.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/test/platform/deep_link_platform_config_test.dart`
- `flutter-app/test/application/startup/app_startup_navigation_coordinator_test.dart`
- `tools/flutter/web_app_tests/deeplink_contract.spec.js`

### Ordered Steps
1. Capture the exact production URL, host, installed package id, app version/build, and observed failure mode.
2. Reproduce with ADB from a not-running app state and repeat the comparator `/parceiro/:slug`.
3. Classify the failing stage using logs, generated manifest, route resolution, and payload traces.
4. Add RED automated coverage at the failing stage.
5. Implement the minimal fix inside the current branch stack.
6. Run focused Flutter/platform tests, analyzer, and device/browser smoke.
7. Update this TODO's coverage matrix and module decision targets with the confirmed root cause.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Native/startup parity for Event vs Account Profile cold-start routing.
  - Manifest/router parity for `/agenda/evento`.
  - Guard/bootstrap preservation of valid Event route intent if route replacement is the failing stage.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / platform app-link config tests` | Android manifest/path generation may own the failure. | `fvm flutter test test/platform/deep_link_platform_config_test.dart` | `Local-Implemented` | `planned` | pending | Must assert Event path parity explicitly. |
| `flutter-app / startup/router tests` | Flutter cold-start route preservation may own the failure. | `fvm flutter test test/application/startup/app_startup_navigation_coordinator_test.dart` plus route-specific tests added by this TODO | `Local-Implemented` | `planned` | pending | Must include `/agenda/evento/:slug`, not only `/parceiro/:slug`. |
| `flutter-app / Event detail focused tests` | Event detail hydration/render may own the failure if route arrives but UI does not. | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `Local-Implemented` | `planned` | pending | Required if the fix touches Event detail/controller. |
| `flutter-app / analyzer + rule matrix` | Flutter routing/startup changes must remain architecture-clean. | `fvm dart analyze --format machine` and `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `Local-Implemented` | `planned` | pending | Required for any Flutter source change. |
| `flutter-app / Android device cold-start smoke` | The reported bug is native installed-app cold-start behavior. | ADB or device smoke command recorded with tested URL/build/package id | `Local-Validated` | `planned` | pending | Must compare `/parceiro/:slug` and `/agenda/evento/:slug`. |
| `belluga_now_docker / source-owned web smoke` | Web direct route must remain green if web/app route logic changes. | `bash tools/flutter/run_web_navigation_smoke.sh readonly` after canonical build when web-visible routing changes | `Local-Validated` | `planned` | pending | Use project-owned runner and record bundle provenance. |

## Approval
- **Status:** `approved`
- **Approved by:** user in chat
- **Approved at:** `2026-05-31T22:47:50-03:00`
- **Approval evidence:** user message `event cold start TODO: APROVADO. Pode seguir com ele.`
- **Approval scope:** reproduce and fix Android Event cold-start deep-link routing, with fail-first coverage and v0.2.0+8 matrix validation.
- **Renewed approval required if:** root cause requires broader route-path redesign, backend Event payload contract changes, iOS Universal Links work, or unrelated Event/detail UX changes.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is an approved tactical bugfix TODO. | Approved scope, frozen decisions, fail-first evidence, delivery gates. | Implementing from chat-only context or skipping guards. | Run authority/delivery guards and keep evidence in this TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | Production-visible bug with likely false-green coverage. | Deterministic reproduction, stage coverage matrix, RED test before fix, root-cause statement. | Treating warm-start success or Account Profile success as proof for Event cold-start. | Reproduce first, classify the failing stage, then implement minimal fix. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | New or repaired tests are required to close the manual-validation gap. | Behavior assertions across manifest/startup/router/device stages. | Status-only tests, test-after-only coverage when fail-first is practical. | Add fail-first coverage for the actual failing stage and preserve parity cases. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-orchestration-suite/SKILL.md` | Closure requires Flutter/platform/device/browser evidence. | Canonical execution owners, build provenance, required-stage status map. | Calling blocked/manual-only evidence passed without waiver. | Use focused suites plus device/browser smoke and stage accounting. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Likely Flutter startup/router changes. | Controller/router ownership, analyzer/rule matrix, no DTO/service leakage into presentation. | Direct ad hoc navigation, route guard bypass, local-only startup hacks. | Run analyzer and rule matrix for any Flutter source change. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md` | The defect is route cold-entry behavior even if route files are only inspected. | Scope/subscope ownership, cold-entry classification, route guard semantics. | Synthetic history seeding or undefined route ownership. | If route files change, document cold-entry behavior and regenerate/audit routes as needed. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-smell-async-navigation/SKILL.md` | Startup/guard fixes may involve async navigation boundaries. | Router/guard-owned navigation decisions and lifecycle-safe awaits. | UI post-await navigation or context use after async gaps. | Audit any changed async navigation path before delivery. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-device-test-runner/SKILL.md` | The reported failure is Android installed-app cold-start behavior. | ADB/device readiness, force-stop before cold-start proof, durable evidence. | Claiming native cold-start fixed from unit/widget tests alone. | Use ADB/device smoke where available and record exact package/URL/build. |
