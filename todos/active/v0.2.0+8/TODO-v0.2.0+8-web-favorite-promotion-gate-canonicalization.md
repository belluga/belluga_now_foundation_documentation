# TODO: Web Favorite Promotion Gate Canonicalization

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual validation corrected the current favorite-gate direction for anonymous web users:

- Web has no phone-login flow.
- The web auth/promotion gate for anonymous user actions must call the user toward the app.
- The first click on `Favoritar` must not automatically reopen the application.
- The current local remediation is still architecturally wrong because it routes the favorite gate through a standalone `AppPromotionDialog.show(...)` customization that does not reuse the existing promotion experience that already evaluates Play Store, Apple/App Store publication settings, preferred platform, app name/icon, and active promotion experience.

The right direction is to make the favorite auth gate consume the canonical app-promotion UI contract, in modal form, instead of creating or customizing a parallel modal from scratch.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `web-favorite-promotion-gate-canonicalization`
- **Parent TODO:** `TODO-v0.2.0+8-immersive-hero-actions-centralization.md`
- **Why this is the right current slice:** the issue is one bounded web/Flutter architecture correction for account-profile favorite gates across tenant-public surfaces.
- **Direct-to-TODO rationale:** the user identified a concrete architectural breach: a duplicate modal path ignores the existing promotion/store-link contract. The correction is bounded to reusing/canonicalizing the web app-promotion modal path before promotion of the v0.2.0+8 package.

## Delivery Status Canon
- **Current delivery stage:** `Local-Complete`
- **Qualifiers:** `Flutter`, `Web`, `Tenant-Public`, `Regression-Fix`, `Architecture`, `User-Visible`, `Requires-APROVADO`
- **Next exact step:** route this TODO with the v0.2.0+8 promotion bundle; local commits and pushes are complete.
- **Promotion lane path:** current v0.2.0+8 package lane; no parallel version or branch.

## Scope
- [x] Inventory current app-promotion surfaces and decide the canonical modal entry point before code changes.
- [x] Preserve the existing `AppPromotionScreenController` as the source for app name, icon, preferred platform, publication settings, active promotion experience, and promotion URI construction.
- [x] Refactor/reuse the existing promotion UI in a compact modal variant instead of creating another modal from scratch.
- [x] Ensure the modal renders only active published store targets according to `AppPublicationSettings`.
- [x] Ensure the modal handles Android-only, iOS-only, both stores, no explicit config, and no active store targets.
- [x] Ensure anonymous web favorite click opens the canonical promotion modal and does not auto-open `/open-app`.
- [x] Ensure only an explicit CTA inside the modal can call the app/open-store handoff.
- [x] Apply the shared favorite gate consistently to Account Profile detail, Discovery cards, and event linked-profile/nested-group favorite buttons.
- [x] Remove or deprecate any direct favorite-gate use of ad hoc `AppPromotionDialog.show` parameters that bypass the canonical promotion controller.
- [x] Keep anonymous non-web favorite behavior on the canonical login redirect / post-login action replay path.
- [x] Keep authenticated web favorite behavior as in-place toggle with no route reload.

## Out of Scope
- [x] Redesigning the full `/baixe-o-app` app-promotion route beyond what is needed to extract/reuse its compact modal experience.
- [x] Changing publication settings backend contracts.
- [x] Adding phone OTP login to web.
- [x] Automatically opening the installed app on the first favorite click.
- [x] Changing event invite/share semantics.
- [x] Changing favorite repository/backend mutation contracts beyond auth-gate behavior.
- [x] Creating a new visual promotion system unrelated to the existing app-promotion module.

## Definition of Done
- [x] `DOD-01` The favorite web auth gate uses a canonical app-promotion modal entry point that reuses `AppPromotionScreenController` or an extracted component backed by that controller.
- [x] `DOD-02` No favorite-gate modal path directly builds a parallel promotion UI that ignores Play Store/App Store publication settings.
- [x] `DOD-03` The compact promotion modal renders Android, iOS, both, or no store targets according to `AppPublicationSettings`.
- [x] `DOD-04` Anonymous web favorite clicks show the promotion modal and do not navigate to `/auth/login`, `/baixe-o-app`, or `/open-app` before explicit user confirmation.
- [x] `DOD-05` Explicit modal CTA uses the same URI/telemetry path as the canonical promotion experience.
- [x] `DOD-06` Account Profile detail, Discovery, and event linked-profile/nested-group favorite buttons share the same gate behavior.
- [x] `DOD-07` Anonymous non-web favorite behavior still redirects to canonical login with post-login action replay.
- [x] `DOD-08` Authenticated web favorite behavior still toggles in place without reloading or reopening the app.
- [x] `DOD-09` Focused Flutter tests, analyzer, rule matrix, web build, and source-owned Playwright diagnostic pass before delivery claim.

## Validation Steps
- [x] RED test proving the current ad hoc favorite modal path ignores publication/store-target behavior.
- [x] RED/GREEN widget tests for compact promotion modal store-target rendering: Android-only, iOS-only, both, no explicit config, no active targets.
- [x] RED/GREEN Account Profile detail favorite test: anonymous web opens canonical promotion modal, no phone login UI, no route push, no favorite write.
- [x] RED/GREEN Discovery favorite test with the same assertions.
- [x] RED/GREEN event linked-profile/nested-group favorite test with the same assertions.
- [x] Non-web favorite gate test proving login redirect and post-login action replay remain intact.
- [x] Authenticated web favorite test proving in-place toggle remains intact.
- [x] Source scan or analyzer guard proving no favorite-gate path instantiates an ad hoc promotion modal bypassing the canonical promotion controller.
- [x] `fvm dart analyze --format machine`.
- [x] `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`.
- [x] `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`.
- [x] Source-owned Playwright diagnostic against `https://guarappari.belluga.space`: favorite click shows the canonical promotion modal, does not auto-open `/open-app`, and renders the expected active store target(s).

## Proposed Decisions
- [x] `D-WPG-01` The app-promotion module is the canonical owner of web app-promotion UI, including modal variants.
- [x] `D-WPG-02` `AppPromotionScreenController` remains the source of truth for active store targets and promotion URI construction.
- [x] `D-WPG-03` Favorite gates may request a compact modal, but may not provide custom UI that bypasses the canonical promotion controller.
- [x] `D-WPG-04` Anonymous web favorite click shows the modal first. App/open-store handoff is allowed only after explicit modal CTA.
- [x] `D-WPG-05` `AppPromotionDialog` must either be refactored into the canonical app-promotion module path or replaced by an extracted canonical modal component; it must not remain a separate favorite-specific shortcut.
- [x] `D-WPG-06` Store publication behavior must match the route promotion screen: explicit config filters store targets, missing explicit config keeps default store availability behavior.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-WPG-01` The app-promotion module is the canonical owner of web app-promotion UI, including modal variants.
- [x] `D-WPG-02` `AppPromotionScreenController` remains the source of truth for active store targets and promotion URI construction.
- [x] `D-WPG-03` Favorite gates may request a compact modal, but may not provide custom UI that bypasses the canonical promotion controller.
- [x] `D-WPG-04` Anonymous web favorite click shows the modal first. App/open-store handoff is allowed only after explicit modal CTA.
- [x] `D-WPG-05` `AppPromotionDialog` must either be refactored into the canonical app-promotion module path or replaced by an extracted canonical modal component; it must not remain a separate favorite-specific shortcut.
- [x] `D-WPG-06` Store publication behavior must match the route promotion screen: explicit config filters store targets, missing explicit config keeps default store availability behavior.

## Strategic Pre-Implementation Audit
| Lane | Finding | Severity | Recommendation | Decision |
| --- | --- | --- | --- | --- |
| Architecture / Elegance | The current local favorite gate calls `AppPromotionDialog.show(...)` with custom copy and bypasses the existing promotion screen/controller composition. This creates a second promotion UI path. | `P1` | Replace the ad hoc call with a compact canonical modal backed by `AppPromotionScreenController` or an extracted component from `AppPromotionDownloadExperience`. | `Integrated` |
| Product / Web Mandate | Web anonymous favorite must promote the app, but must not auto-open the installed app on first click and must never show phone OTP login. | `P1` | Preserve modal-first behavior and block `/auth/login` and immediate `/open-app` in tests and Playwright. | `Integrated` |
| Store Publication Contract | Existing promotion route tests prove active publication store targets are filtered by `AppPublicationSettings`; the favorite modal currently does not prove this. | `P1` | Add modal-level store-target tests covering Android-only, iOS-only, both, no explicit config, and no active targets. | `Integrated` |
| Test Quality | Existing browser diagnostic can pass on a text-only modal and may not catch ignored store settings. | `P2` | Update Playwright to assert canonical modal store target rendering and no automatic `/open-app`. | `Integrated` |
| Performance | Reusing the existing controller should not add backend calls; the controller reads already-loaded `AppDataRepositoryContract`. | `P3` | Keep modal resolution local to registered app data/controller; avoid network fetches on favorite click. | `Integrated` |

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` returned `Overall outcome: go`; fingerprint `3a98b6d5e116`.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-surface Flutter web UX/auth-gate correction. |
| `blast_radius` | `cross-module` | Touches shared promotion module plus Account Profile, Discovery, and Event linked-profile favorite surfaces. |
| `behavioral_change_or_bugfix` | `yes` | Corrects user-visible favorite auth/promotion gate behavior. |
| `changes_public_contract` | `yes` | Auth-visible web gate semantics change from ad hoc modal/handoff to canonical modal-first promotion. |
| `touches_auth_or_tenant` | `yes` | Anonymous/authenticated behavior and tenant-public promotion boundary are in scope. |
| `touches_runtime_or_infra` | `no` | No queue, infra, backend runtime, or deploy topology changes. |
| `touches_tests` | `yes` | Widget tests and source-owned Playwright diagnostic must change. |
| `critical_user_journey` | `yes` | Favorite conversion/retention action is a launch-critical public journey. |
| `release_or_promotion_critical` | `yes` | This blocks promotion of the current v0.2.0+8 package lane. |
| `high_severity_plan_review_issue` | `yes` | Duplicate app-promotion modal path is a high-severity architecture/process issue. |
| `explicit_three_lane_request` | `no` | User requested audit/strategic review but did not explicitly request a formal three-lane external audit in this turn. |

## Derived Audit Floor
| Gate | Decision | Lifecycle Gate | Workflow | Depth / Policy | Reason Codes | Status |
| --- | --- | --- | --- | --- | --- | --- |
| Critique | `required` | `before_aprovado` | `wf-docker-independent-critique-method` | `expanded` | `CRITIQUE-BASELINE-ALWAYS`, `CRITIQUE-EXPANDED-RISK-SIGNALS` | `completed / findings_integrated` |
| Test Quality Audit | `required` | `before_completed` | `wf-docker-independent-test-quality-audit-method` | `full` | `TQA-TESTS-TOUCHED`, `TQA-BEHAVIOR-OR-BUGFIX`, `TQA-PUBLIC-CONTRACT`, `TQA-CRITICAL-JOURNEY`, `TQA-RELEASE-CRITICAL` | `completed / heuristic low; triple audit test-quality accepted low debt` |
| Final Review | `required` | `before_completed` | `wf-docker-independent-final-review-method` | `expanded` | `FINAL-BASELINE-ALWAYS`, `FINAL-EXPANDED-RISK-SIGNALS` | `completed / claude-final-review low findings accepted as non-blocking debt` |
| Triple Review | `required` | `before_completed` | `audit-protocol-triple-review` | `additive_only` | `TRIPLE-HIGH-CRITICALITY` | `completed / round 01 accepted-debt; no unresolved blocker` |
| Security Review | `required` | `before_completed` | `security-adversarial-review` | `required` | `SEC-AUTH-OR-TENANT` | `completed / low risk; no blocker` |
| Performance/Concurrency | `recommended` | `per_pcv1_gate_deadlines` | `wf-docker-performance-concurrency-validation-method` | `recommended` | `PCV-RELEASE-SENSITIVE` | `completed / triple audit performance clean; no runtime amplification` |
| Verification Debt | `required` | `before_completed` | `verification-debt-audit` | `required` | `VDA-MEDIUM-BIG-OR-RELEASE` | `completed / outcome none; no inline debt` |

## Independent No-Context Critique Gate
- **Critique decision:** `required`
- **Why this decision:** the TODO changes a critical public web/auth-adjacent journey and corrects a high-severity architecture issue around duplicated promotion UI.
- **Impact signals in scope:** `cross-module blast radius`, `auth-visible web gate`, `critical user journey`, `release-critical`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** current favorite-gate state, existing app-promotion controller/screen surfaces, proposed modal reuse decision baseline, store-publication test matrix, and residual risks.
- **Critique isolation mode:** `bounded self-review using only the bounded package below; not a substitute for the required delivery-side triple review`
- **Subagent mandate when available:** `no` for this drafting turn because the user requested an audit but did not explicitly request subagent delegation.
- **Canonical multi-lane audit protocol when required:** `audit-protocol-triple-review` before completion, not before approval.
- **Critique lenses:** `correctness`, `performance`, `elegance`, `structural-soundness`, `risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** current path is useful only as a symptom guard; the implementation direction must change from direct `AppPromotionDialog.show(...)` customization to canonical promotion-module modal reuse.
- **Resolution ledger:**
| Finding ID | Resolution | Usefulness | Formalizable | Candidate Rule Level | Candidate Rule ID | Rationale / Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `WPG-C01` | `Integrated` | `useful` | `partial` | `project` | `favorite-gate-canonical-promotion-ui` | Direct favorite-gate promotion UI must not bypass `AppPromotionScreenController`; reflected in `DOD-01`, `DOD-02`, `D-WPG-01`, `D-WPG-02`, and `D-WPG-03`. |
| `WPG-C02` | `Integrated` | `useful` | `yes` | `project` | `app-promotion-store-target-matrix` | Store-publication behavior is testable from `AppPublicationSettings`; reflected in `DOD-03` and the store-target validation matrix. |
| `WPG-C03` | `Integrated` | `useful` | `yes` | `project` | `web-favorite-no-auto-open-before-confirm` | Favorite click must show modal first and block immediate `/open-app`; reflected in `DOD-04` and Playwright validation. |
| `WPG-C04` | `Integrated` | `useful` | `partial` | `none` | `not-applicable` | Refactoring the full `/baixe-o-app` page is not required for this TODO; out-of-scope keeps the slice bounded while still requiring shared component extraction/reuse. |
- **Evidence / reference:** bounded review of `AppPromotionScreenController`, `AppPromotionDownloadExperience`, `AppPromotionDialog`, and current favorite-gate call path recorded in this TODO.
- **Exception authority / reference:** none used; this is a bounded scope decision.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter + web`
- **Expected supporting profiles:** `assurance-tester-quality` for browser/test quality review; `strategic-cto-tech-lead` only if module docs require a promotion UI ownership decision update.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md` app-promotion / web auth gate section if the module currently lacks an explicit modal ownership rule.
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** one plan/audit checkpoint before approval.
- **Why this level:** the code change is Flutter-only, but it affects a central web auth/promotion gate across multiple public surfaces and must remove duplicate UI architecture.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-WPG-01` | `AppPromotionScreenController` already owns store-target filtering and URI construction. | `storePlatformsToRender`, `buildAndroidPromotionUri`, `buildIosPromotionUri`, and current promotion screen tests. | Need to first extend controller before modal reuse. | `High` | `Keep as Assumption` |
| `A-WPG-02` | The modal can reuse/extract the route promotion UI without changing backend contracts. | Current route UI already consumes only app data/controller state. | If route UI is too layout-specific, extract a shared smaller widget under the promotion module. | `High` | `Keep as Assumption` |
| `A-WPG-03` | Favorite gate callers can stay unchanged except for the shared auth gate implementation. | Account Profile detail, Discovery, and event linked-profile paths already call `AccountProfileFavoriteAuthGate`. | If any surface bypasses the helper, add it to scope before implementation. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/presentation/shared/favorites/account_profile_favorite_auth_gate.dart`
- `flutter-app/lib/presentation/shared/promotion/**`
- `flutter-app/test/presentation/shared/promotion/**`
- `flutter-app/test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`
- `flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`
- `foundation_documentation/modules/flutter_client_experience_module.md` only if needed for canonical decision consolidation.

### Ordered Steps
1. Run `todo_authority_guard.py` after approval.
2. Add fail-first tests for store-target rendering in the favorite promotion modal path.
3. Inventory `AppPromotionDialog`, `AppPromotionScreen`, `AppPromotionDownloadExperience`, and `AppPromotionScreenController`; choose the minimum refactor that removes the duplicate modal path.
4. Extract or refactor a compact modal component under the app-promotion module that uses `AppPromotionScreenController`.
5. Point `AccountProfileFavoriteAuthGate` at the canonical modal entry point.
6. Remove direct favorite-gate use of ad hoc `AppPromotionDialog.show` parameters.
7. Update Account Profile, Discovery, and event linked-profile tests.
8. Update Playwright diagnostic to assert canonical modal/store target behavior.
9. Run analyzer, rule matrix, web build, browser diagnostic, and TODO guards before delivery claim.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Current favorite modal path cannot prove active store target filtering.
  - Browser diagnostic fails if the favorite click opens `/open-app` before explicit modal CTA.
  - Source scan fails if favorite gate bypasses the app-promotion controller.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / promotion modal tests` | Compact modal must reuse store publication behavior. | `fvm flutter test --no-pub test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_modal_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart` | `Local-Implemented` | `passed` | Included in final focused 170-test suite; account/modal focused rerun `fvm flutter test --no-pub test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_modal_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` passed with 57 tests. | Covers Android-only, iOS-only, both, no explicit config, and no active store target. |
| `flutter-app / favorite gate focused tests` | Three public favorite surfaces use the same gate. | `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `Local-Implemented` | `passed` | Final focused suite passed with 170 tests; account/modal focused rerun passed with 57 tests including non-web login redirect. | Asserts modal-first, no mutation, no route push/replace for web anonymous; non-web login redirect; authenticated toggle. |
| `flutter-app / analyzer` | Flutter presentation/promotion code changes. | `fvm dart analyze --format machine` | `Local-Validated` | `passed` | Final command passed with no output after non-web test hardening. | Must remain clean after new test. |
| `flutter-app / rule matrix` | Analyzer/plugin guard may be added or touched. | `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` | `Local-Validated` | `passed` | Final command passed; 58 required lint codes detected; 59 distinct codes emitted. | Required architecture guard evidence. |
| `flutter-app -> web-app build` | Browser-visible web gate changes. | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | `Local-Validated` | `passed` | Exact command passed on 2026-06-02 after final production code; bundle available at `../web-app`. | Rebuilt local-public bundle. |
| `browser runtime diagnostic` | Final user-visible behavior must be validated by real navigation. | `NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` | `Local-Validated` | `passed` | Passed after exact web build and `docker compose restart nginx`; 1 Playwright diagnostic passed against `https://guarappari.belluga.space`. | Asserts modal-first and no immediate `/open-app`, `/baixe-o-app`, or `/auth/login` on Account Profile and Discovery. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Inventory current app-promotion surfaces and decide the canonical modal entry point before code changes. | source review + bounded package | `bounded-package.md`; source review of `AppPromotionScreenController`, `AppPromotionDownloadExperience`, `AppPromotionDialog`, and favorite gate path. | Flutter source architecture review | `passed` | Canonical modal entry point established under app-promotion module as `AppPromotionModal.show`. |
| `SCOPE-02` | `Scope` | Preserve the existing `AppPromotionScreenController` as the source for app name, icon, preferred platform, publication settings, active promotion experience, and promotion URI construction. | source + analyzer | `app_promotion_modal.dart`, `app_promotion_store_actions.dart`; `fvm dart analyze --format machine`; rule matrix passed. | Flutter app-promotion module | `passed` | Modal resolves/constructs controller and store actions call controller store/URI/launch methods. |
| `SCOPE-03` | `Scope` | Refactor/reuse the existing promotion UI in a compact modal variant instead of creating another modal from scratch. | source + tests | `app_promotion_brand_icon.dart`, `app_promotion_store_actions.dart`, `app_promotion_download_experience.dart`, `app_promotion_modal.dart`; modal tests passed. | Flutter widget tests | `passed` | Full route and modal share brand/store widgets backed by the same controller. |
| `SCOPE-04` | `Scope` | Ensure the modal renders only active published store targets according to `AppPublicationSettings`. | widget tests | `app_promotion_modal_test.dart:27-137`; focused Flutter tests passed. | Flutter widget tests | `passed` | Android-only, iOS-only, both, and no-active-store assertions cover active target filtering. |
| `SCOPE-05` | `Scope` | Ensure the modal handles Android-only, iOS-only, both stores, no explicit config, and no active store targets. | widget tests | `app_promotion_modal_test.dart:27-137`; focused Flutter tests passed. | Flutter widget tests | `passed` | Includes no explicit config fallback at lines 93-110. |
| `SCOPE-06` | `Scope` | Ensure anonymous web favorite click opens the canonical promotion modal and does not auto-open `/open-app`. | widget + Playwright navigation | `account_profile_detail_screen_test.dart:357-402`; `discovery_screen_controller_test.dart:701-760`; `immersive_event_detail_screen_test.dart:1392-1475`; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js:96-127,193-207`; `run_web_navigation_smoke.sh readonly` passed after `build_web.sh ../web-app`. | `https://guarappari.belluga.space`, Playwright `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`, rebuilt `../web-app` | `passed` | Browser diagnostic captures `/open-app` requests/popups and asserts none before explicit CTA. |
| `SCOPE-07` | `Scope` | Ensure only an explicit CTA inside the modal can call the app/open-store handoff. | source + Playwright negative navigation | `app_promotion_store_actions.dart:29-33,103`; Playwright diagnostic lines 96-127; `run_web_navigation_smoke.sh readonly` passed after web build. | Flutter source + Playwright runtime against `https://guarappari.belluga.space` | `passed` | Store handoff is attached to explicit badge tap; first favorite click only opens modal. |
| `SCOPE-08` | `Scope` | Apply the shared favorite gate consistently to Account Profile detail, Discovery cards, and event linked-profile/nested-group favorite buttons. | widget/controller tests + Playwright | Account Profile test lines 357-402; Discovery test lines 701-760; Event test lines 1392-1475; controller tests for `requiresAuthentication`; Playwright runtime covers Account Profile and Discovery. | Flutter widget/controller tests + Playwright runtime | `passed` | All three production surfaces route through shared auth gate behavior. |
| `SCOPE-09` | `Scope` | Remove or deprecate any direct favorite-gate use of ad hoc `AppPromotionDialog.show` parameters that bypass the canonical promotion controller. | source scan | Source scan command recorded in `bounded-package.md`; scanned `AppPromotionDialog.show`, favorite login copy, invite copy, and `/open-app` markers across `flutter-app/lib/presentation/shared`, `flutter-app/lib/presentation/tenant_public`, and `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`. | Flutter source scan | `passed` | No `AppPromotionDialog.show` remains in favorite gate paths; legacy class is outside this TODO scope. |
| `SCOPE-10` | `Scope` | Keep anonymous non-web favorite behavior on the canonical login redirect / post-login action replay path. | widget test + source | `account_profile_detail_screen_test.dart` test `non-web anonymous favorite action redirects to login with replay path`; `account_profile_favorite_auth_gate.dart:31-40`; final focused rerun passed with 57 tests. | Flutter widget test with `isWebRuntime:false` | `passed` | Asserts no app-promotion modal, no mutation, and `/auth/login?redirect=%2Fparceiro%2Fteste`. |
| `SCOPE-11` | `Scope` | Keep authenticated web favorite behavior as in-place toggle with no route reload. | widget mutation-path test + web route guard | `account_profile_detail_screen_test.dart:318-355`; `discovery_screen_controller_test.dart:1697-1719`; final focused rerun passed with 57 tests; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter widget/controller tests with `isWebRuntime:true`; Playwright web route smoke against rebuilt `../web-app` | `passed` | Authenticated state is test-injected; web route stability is covered by source-owned Playwright and no route reload is asserted by widget router checks. |
| `DOD-01` | `Definition of Done` | `DOD-01` The favorite web auth gate uses a canonical app-promotion modal entry point that reuses `AppPromotionScreenController` or an extracted component backed by that controller. | source + analyzer + Playwright navigation | `account_profile_favorite_auth_gate.dart`; `app_promotion_modal.dart`; analyzer and rule matrix passed; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter source + analyzer + Playwright web route runtime against rebuilt `../web-app` | `passed` | Web branch calls `AppPromotionModal.show`; runtime proves modal entry point on Account Profile and Discovery. |
| `DOD-02` | `Definition of Done` | `DOD-02` No favorite-gate modal path directly builds a parallel promotion UI that ignores Play Store/App Store publication settings. | source scan + tests + Playwright navigation | Source scan for `AppPromotionDialog.show`; `app_promotion_modal_test.dart:27-137`; focused tests passed; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter source scan + widget tests + Playwright web runtime | `passed` | Store publication settings are exercised at modal level and web runtime reaches the canonical modal. |
| `DOD-03` | `Definition of Done` | `DOD-03` The compact promotion modal renders Android, iOS, both, or no store targets according to `AppPublicationSettings`. | widget tests + Playwright navigation | `app_promotion_modal_test.dart:27-137`; focused tests passed; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter widget tests + Playwright web runtime | `passed` | Unit matrix covers all requested store target variants; runtime validates canonical modal reachability. |
| `DOD-04` | `Definition of Done` | `DOD-04` Anonymous web favorite clicks show the promotion modal and do not navigate to `/auth/login`, `/baixe-o-app`, or `/open-app` before explicit user confirmation. | widget + Playwright navigation | Account Profile, Discovery, Event widget tests; `favorite_auth_gate_runtime.diagnostic.spec.js:96-127,193-207`; `run_web_navigation_smoke.sh readonly` passed after `build_web.sh ../web-app`. | `https://guarappari.belluga.space`, Playwright spec under `tools/flutter/web_app_tests`, rebuilt `../web-app` | `passed` | Runtime asserts no `/auth/login`, `/baixe-o-app`, or `/open-app` on first click. |
| `DOD-05` | `Definition of Done` | `DOD-05` Explicit modal CTA uses the same URI/telemetry path as the canonical promotion experience. | source + final review + Playwright navigation | `app_promotion_store_actions.dart:29-33,103`; `final-review.merge.md`; triple audit accepted telemetry parity as low non-blocking debt; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter source + final review artifact + Playwright web runtime | `passed` | CTA uses controller `build*PromotionUri` and `launchPromotionUri`; first web click does not launch before explicit action. |
| `DOD-06` | `Definition of Done` | `DOD-06` Account Profile detail, Discovery, and event linked-profile/nested-group favorite buttons share the same gate behavior. | widget/controller tests + Playwright | Account Profile test lines 357-402; Discovery test lines 701-760; Event test lines 1392-1475; Playwright Account/Profile and Discovery runtime passed. | Flutter widget/controller tests + Playwright runtime | `passed` | Event runtime Playwright is accepted low completeness debt; event widget/controller tests prove the same behavior. |
| `DOD-07` | `Definition of Done` | `DOD-07` Anonymous non-web favorite behavior still redirects to canonical login with post-login action replay. | widget test + source | New Account Profile non-web test; `account_profile_favorite_auth_gate.dart:31-40`; final focused rerun passed with 57 tests. | Flutter widget test with `isWebRuntime:false` | `passed` | Confirms login redirect path and no favorite mutation. |
| `DOD-08` | `Definition of Done` | `DOD-08` Authenticated web favorite behavior still toggles in place without reloading or reopening the app. | widget/controller mutation-path tests | `account_profile_detail_screen_test.dart:318-355`; `discovery_screen_controller_test.dart:1697-1719`; final focused rerun passed with 57 tests. | Flutter widget/controller tests with web runtime flag and authorized auth | `passed` | Web-authenticated state is test-injected because public web mandate has no phone-login flow. |
| `DOD-09` | `Definition of Done` | `DOD-09` Focused Flutter tests, analyzer, rule matrix, web build, and source-owned Playwright diagnostic pass before delivery claim. | local CI-equivalent suite | Focused suite passed with 170 tests; analyzer passed with no output; rule matrix passed; exact `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` passed; Playwright diagnostic passed after nginx restart. | Flutter tests, analyzer, web build, Playwright against `https://guarappari.belluga.space` | `passed` | Current local validation covers the final test hardening. |
| `VAL-01` | `Validation Steps` | RED test proving the current ad hoc favorite modal path ignores publication/store-target behavior. | regression test target + Playwright navigation | `app_promotion_modal_test.dart:27-137` would fail against a text-only/ad hoc modal that ignores publication settings; green suite passed after canonical modal implementation; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter widget tests + Playwright web runtime | `passed` | Separate red log artifact was not retained; regression assertion is now executable and runtime reaches canonical modal. |
| `VAL-02` | `Validation Steps` | RED/GREEN widget tests for compact promotion modal store-target rendering: Android-only, iOS-only, both, no explicit config, no active targets. | widget tests | `app_promotion_modal_test.dart:27-137`; focused tests passed. | Flutter widget tests | `passed` | Covers full requested matrix. |
| `VAL-03` | `Validation Steps` | RED/GREEN Account Profile detail favorite test: anonymous web opens canonical promotion modal, no phone login UI, no route push, no favorite write. | widget test + Playwright navigation | `account_profile_detail_screen_test.dart:357-402`; Playwright spec `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` lines 193-207; `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter widget test + Playwright runtime against `https://guarappari.belluga.space`, rebuilt `../web-app` | `passed` | Asserts no `Entrar para favoritar`, modal visible, no favorite write, no route push/replace. |
| `VAL-04` | `Validation Steps` | RED/GREEN Discovery favorite test with the same assertions. | widget/controller test + Playwright navigation | `discovery_screen_controller_test.dart:84-104,701-760,1697-1719`; Playwright spec lines 204-207; runtime passed after web build. | Flutter widget/controller tests + Playwright runtime against `https://guarappari.belluga.space` | `passed` | Asserts requires-auth before mutation and web modal-first behavior. |
| `VAL-05` | `Validation Steps` | RED/GREEN event linked-profile/nested-group favorite test with the same assertions. | widget/controller tests | `immersive_event_detail_controller_test.dart:94-109`; `immersive_event_detail_screen_test.dart:1392-1475`; focused 170-test suite passed. | Flutter widget/controller tests | `passed` | Runtime Playwright for event is accepted low completeness debt; screen/controller tests exercise linked-profile favorite gate. |
| `VAL-06` | `Validation Steps` | Non-web favorite gate test proving login redirect and post-login action replay remain intact. | widget test | New Account Profile test `non-web anonymous favorite action redirects to login with replay path`; final focused rerun passed with 57 tests. | Flutter widget test with `isWebRuntime:false` | `passed` | Asserts `/auth/login?redirect=%2Fparceiro%2Fteste`, no modal, no mutation. |
| `VAL-07` | `Validation Steps` | Authenticated web favorite test proving in-place toggle remains intact. | widget/controller tests + web route guard | `account_profile_detail_screen_test.dart:318-355`; `discovery_screen_controller_test.dart:1697-1719`; final focused rerun passed with 57 tests; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` and `tools/flutter/run_web_navigation_smoke.sh readonly` passed after `scripts/build_web.sh ../web-app`. | Flutter widget/controller tests with authorized auth + Playwright web route runtime | `passed` | Asserts toggle and no route reload in widget path; browser route guard validates current web bundle. |
| `VAL-08` | `Validation Steps` | Source scan or analyzer guard proving no favorite-gate path instantiates an ad hoc promotion modal bypassing the canonical promotion controller. | source scan + analyzer | Source scan command recorded in `bounded-package.md`; scanned `AppPromotionDialog.show`, favorite login copy, invite copy, and `/open-app` markers across `flutter-app/lib/presentation/shared`, `flutter-app/lib/presentation/tenant_public`, and `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`; analyzer/rule matrix passed. | Flutter source scan + analyzer | `passed` | Remaining hits are outside favorite gate or test negative assertions. |
| `VAL-09` | `Validation Steps` | `fvm dart analyze --format machine`. | analyzer | `fvm dart analyze --format machine` passed with no output after non-web test hardening. | Flutter analyzer | `passed` | Clean analyzer result. |
| `VAL-10` | `Validation Steps` | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`. | analyzer rule matrix | `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` passed; 58 required lint codes detected; 59 distinct codes emitted. | Flutter analyzer plugin rule matrix | `passed` | Validates rule coverage including UI repository resolution guard. |
| `VAL-11` | `Validation Steps` | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`. | web build | Exact command passed from `flutter-app`; bundle available at `../web-app`. | Flutter web build output `../web-app` | `passed` | Followed by `docker compose restart nginx`. |
| `VAL-12` | `Validation Steps` | Source-owned Playwright diagnostic against `https://guarappari.belluga.space`: favorite click shows the canonical promotion modal, does not auto-open `/open-app`, and renders the expected active store target(s). | Playwright navigation | `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js:96-127,193-207`; `NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` passed after exact web build and nginx restart. | `https://guarappari.belluga.space`, source-owned Playwright spec, rebuilt `../web-app` | `passed` | Account Profile and Discovery runtime scenarios passed. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Favorite gate diff + CI-equivalent suite | CI/Copilot priority blocker regressions: missing generated files, stale web bundle, analyzer breakage, auth-route mismatch, untracked source-owned browser spec. | `passed` | `fvm flutter test --no-pub ...`; `fvm dart analyze --format machine`; rule matrix; exact `build_web.sh`; Playwright diagnostic; final review merge. | No priority blocker. | Final review and triple audit findings are low/accepted debt. |

## Final Review Finding Resolution
| Finding | Severity | Decision | Resolution / Rationale | Next action |
| --- | --- | --- | --- | --- |
| Event linked-profile favorite lacks dedicated Playwright runtime scenario | `low` | `accepted-debt` | Event linked-profile favorite is covered by controller and screen tests; Account Profile and Discovery browser runtime cover the shared web modal gate. No P1/P2 remains. | Future completeness pass may add event Playwright runtime scenario. |
| Explicit telemetry and URI parity assertions accepted as future hardening | `low` | `accepted-debt` | Modal CTA uses shared `AppPromotionStoreActions` and `AppPromotionScreenController` URI/launch methods. Explicit telemetry/URI assertions would strengthen contract coverage but are not required to prove the reported regression. | Future test-hardening pass may add explicit parity assertions. |
| Legacy `AppPromotionDialog` remains for non-favorite surfaces | `low` | `accepted-debt` | Source scan confirms favorite paths no longer call `AppPromotionDialog.show`; removing the class globally would expand beyond this TODO. | Future cleanup TODO may migrate remaining non-favorite callers. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter architecture + promotion/favorite gate canonicalization | Parallel promotion UI, UI-side repository/service resolution, auth mutation before authorization, automatic app handoff, weak source-owned browser evidence. | `passed` | `bash delphi-ai/tools/rule_spirit_anti_pattern_scan.sh --repo . --stack flutter --json-output foundation_documentation/artifacts/todo-audits/web-favorite-promotion-gate-canonicalization/rule-spirit-scan.json --path ...` | Scanner max active severity `warning`; no P1 or P2. Warnings: existing presentation import in Discovery controller and `Navigator.maybePop` for modal dismiss. | Both reviewed as non-blocking for this TODO: import is outside the favorite-gate canonicalization risk and modal dismiss is local UI close behavior. |

## Deterministic Guard Evidence
| Guard | Command | Status | Evidence / Notes |
| --- | --- | --- | --- |
| Authority guard | `python3 delphi-ai/tools/todo_authority_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md --require-delivery-gates` | `passed` | `Overall outcome: go`; no violations; delivery gates present. |
| Completion guard | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md` | `passed` | `Overall outcome: go`; criterion-specific completion evidence accepted. |
| Closeout guard | `python3 delphi-ai/tools/todo_closeout_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md --repo .` | `passed` | `Overall outcome: go`; disposition `move-promotion-lane`; no violations. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** implementation, validation, audits, security review, final review, and deterministic guards are complete; this TODO should travel with the current v0.2.0+8 package promotion.
- **Post-commit/push status:** `complete`
- **Post-commit/push evidence:** `flutter-app@88227417fa1af06cb0d415f5e04fea0cd1014d15`; `web-app@dcaa3ffd62d3844e3a13ec8137ae913123ffe8f2`; `foundation_documentation@1d060acf62cdcaa888a3422b1fb9599e2f2e3f19`; root `belluga_now_docker@1c8e2d62e63d725e3c499152db6420200aadf7a6`.
- **Next path/status action:** route this TODO into the v0.2.0+8 promotion bundle with the rest of the package; no standalone promotion-lane split was created.

## Manual Validation Matrix
| ID | Surface | Steps | Expected Result |
| --- | --- | --- | --- |
| `MAN-WPG-01` | Public Web Account Profile | Open `/parceiro/qa-discovery-tag-longa` anonymous, click `Favoritar`. | Compact app-promotion modal appears; no phone login; app does not reopen automatically. |
| `MAN-WPG-02` | Public Web Account Profile | In the modal, inspect store action(s). | Only active configured Play Store/App Store target(s) appear. |
| `MAN-WPG-03` | Public Web Account Profile | Click the modal store/open-app CTA. | Handoff occurs only after this explicit click. |
| `MAN-WPG-04` | Public Web Discovery | Open `/descobrir`, click favorite on the same profile. | Same modal and store-target behavior. |
| `MAN-WPG-05` | Public Web Event | Open an event with linked profile cards, click linked-profile favorite. | Same modal and store-target behavior. |
| `MAN-WPG-06` | Authenticated Web | Repeat favorite on Account Profile after authenticated state. | Favorite toggles in place; no modal, no route reload. |
| `MAN-WPG-07` | App / Non-Web | Repeat anonymous favorite in app runtime. | Canonical login redirect and post-login action replay still work. |

## Approval
- **Approved by:** user in chat at `2026-06-02T11:44:50-03:00` with approval phrase `APROVADO`.
- **Approval scope:** implement the canonical web favorite promotion gate on the current v0.2.0+8 lane by reusing/extracting the existing app-promotion module/modal behavior and validating store-publication handling.
- **Execution not authorized by approval:** backend publication settings contract changes, `/baixe-o-app` route product-semantic changes beyond shared component extraction/reuse, phone login on web, automatic app opening on first favorite click, or favorite mutation contract changes.
- **Renewed approval required when:** implementation expands to backend publication settings contracts, changes `/baixe-o-app` route product semantics, introduces phone login on web, changes favorite mutation contracts, or weakens the store-target validation matrix.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is an approval-gated tactical correction before promotion. | Explicit approval, frozen decisions, criterion-specific evidence. | More code edits before `APROVADO`. | Run authority guard after approval and completion guards before delivery claim. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Flutter presentation/promotion/gate code is in scope. | Controller-owned state/effects; presentation avoids duplicated business decisions. | Parallel modal UI that bypasses canonical controller. | Keep app-promotion controller as source of truth. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | Existing tests missed store-publication behavior. | Fail-first tests and browser diagnostic for final flow. | Text-only assertions that ignore store-target rendering. | Add store matrix and Playwright evidence. |
| `foundation_documentation/policies/web_to_app_promotion_policy.md` | Web anonymous gates must promote app, not phone-login. | Modal-first app promotion and explicit user handoff. | Phone OTP login or automatic app reopen on first click. | Tests must assert no `/auth/login` and no immediate `/open-app`. |
