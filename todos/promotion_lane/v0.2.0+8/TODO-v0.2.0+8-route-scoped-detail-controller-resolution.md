# TODO: Route-Scoped Detail Controller Resolution

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual validation of nested Account Profile navigation exposed a stack ownership bug:

- Flow: `Descobrir` -> `Du Jorge` -> first profile in `Integrantes` -> back.
- Observed failure: the visible screen could mix child Account Profile hero state with parent Account Profile tab/fallback state.
- Immediate mitigation already localizes Account Profile detail model state, but the deeper architectural risk remains: stackable detail routes still let screens, descendant widgets, and route-related overlays resolve controllers from global `GetIt` factories without a deterministic route-instance boundary.

The canonical solution must use AutoRoute route composition to establish one controller scope per route instance. Descendant widgets, sheets, modals, invite flows, and other route-related UI must resolve the controller for the current route instance, never a sibling route or a fresh/global factory instance. Passing controllers directly through route/screen constructors is explicitly rejected for this slice.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `route-scoped-detail-controller-resolution`
- **Why this is the right current slice:** the issue is one bounded Flutter architecture correction for stackable tenant-public detail routes and their descendants.
- **Direct-to-TODO rationale:** the user provided a concrete route-stack bug, rejected ad hoc controller passing, requested AutoRoute best-practice alignment, and required stricter approval criteria around GetIt scope correctness.

## Contract Boundary
- This TODO owns route-instance controller resolution for current tenant-public stackable detail surfaces.
- It must use AutoRoute-compatible route wrapping/resolver composition as the canonical route boundary.
- It may add a project-local analyzer/deterministic rule when useful to prevent recurrence in the covered surfaces.
- It must not create a parallel branch or promotion lane; implementation lands on the current v0.2.0+8 reconcile lane.

## Delivery Status Canon
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Architecture`, `Flutter`, `Route`, `Tenant-Public`, `Regression-Fix`, `Analyzer-Rule-Candidate`, `User-Visible`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through authorized lane follow-through; local implementation is complete and the current package-wide mimic loop has not reopened this scope.
- **Promotion lane path:** current v0.2.0+8 package lane, no parallel version.

## Scope
- [x] Establish a reusable route-instance scope primitive for Flutter detail routes.
- [x] Wrap stackable tenant-public detail ResolverRoutes with the route-instance scope while preserving RouteModelResolver hydration and ModuleScope behavior.
- [x] Migrate current tenant-public stackable detail screens to resolve their same-feature detail controller from the route scope instead of direct global `GetIt.I.get`.
- [x] Provide a canonical helper for route-scoped modal/dialog/sheet rendering so overlays opened from a detail route keep resolving the same route-instance controller.
- [x] Add tests proving parent and child Account Profile detail routes use distinct controller instances and that back restores the parent instance/state.
- [x] Add tests proving descendant widgets and route-owned modals resolve the route-local controller instance, not a fresh or sibling instance.
- [x] Add a project-local analyzer/deterministic rule or equivalent guard that blocks direct global `GetIt` detail-controller resolution inside the covered stackable detail route surfaces.
- [x] Update Flutter module documentation to specialize the Presentation DI Matrix for stackable route-detail controllers.

## Out of Scope
- [ ] Global replacement of every screen/controller `GetIt` lookup in the app.
- [ ] Tenant-admin form/controller lifecycle refactors, except if a touched test helper needs a local harness adaptation.
- [ ] Backend/API/schema changes.
- [ ] Passing controllers through route or screen constructors as the canonical solution.
- [ ] Using `GetIt.pushNewScope` as a topmost global-scope workaround that can contaminate sibling/parent route rebuilds.
- [ ] Redesigning immersive UI, tabs, hero actions, or invite product semantics.

## Definition of Done
- [x] `DOD-01` Current tenant-public stackable detail route instances have deterministic per-route controller identity.
- [x] `DOD-02` Parent Account Profile route, child Account Profile route, and restored parent after back are proven with controller identity/state assertions.
- [x] `DOD-03` Descendant widgets that need the detail controller resolve the current route scope.
- [x] `DOD-04` A modal/dialog/sheet opened from a detail route resolves the originating route scope.
- [x] `DOD-05` Event and Static Asset immersive detail routes preserve existing behavior while adopting the same canonical route scope pattern where they own detail controllers.
- [x] `DOD-06` Analyzer/rule matrix or an explicit deterministic guard fails on direct global `GetIt.I.get<...DetailController>()` in covered stackable detail surfaces.
- [x] `DOD-07` Route contract audit shows no new required non-URL args or classifies any generated route args explicitly.
- [x] `DOD-08` Flutter analyzer, focused tests, rule matrix, and required v0.2.0+8 validation lanes pass before delivery claim.

## Validation Steps
- [x] RED/GREEN route-scope support tests for nested scope identity, disposal, and modal scope capture.
- [x] RED/GREEN Account Profile detail navigation test for parent -> child -> back with controller identity assertions.
- [x] RED/GREEN descendant/modal test proving route-local controller resolution.
- [x] Focused consumer tests for Account Profile, Event, and Static Asset detail screens.
- [x] `fvm dart analyze --format machine`.
- [x] `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` if analyzer/plugin or rule matrix changes.
- [x] Route contract audit on `lib/application/router/app_router.gr.dart`.
- [x] Web build and source-owned navigation smoke if route/router generated code or browser-visible behavior changes.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality` for test-quality/rule validation; `strategic-cto-tech-lead` only if documentation changes require project-level constitution updates.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1.1 Presentation DI Matrix` and `2.1.2 Route-Driven Hydration Contract`.
- **Module decision consolidation targets:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** one plan/audit checkpoint before approval.
- **Why this level:** the implementation is Flutter-only, but it changes route/controller ownership for stackable public detail screens and adds guardrail coverage.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-RS-01` AutoRoute route wrapping/resolver composition owns the route-instance controller boundary.
- [x] `D-RS-02` Route-local controller lookup is inherited from the route subtree; global `GetIt` remains a factory/registration source, not the UI lookup surface for covered stackable detail controllers.
- [x] `D-RS-03` Covered screens and descendants must not pass detail controllers through route/screen constructors.
- [x] `D-RS-04` Route-owned modal/dialog/sheet helpers must capture and re-expose the originating route scope.
- [x] `D-RS-05` Initial hard cutoff targets tenant-public stackable detail surfaces: Account Profile detail, Event immersive detail, and Static Asset detail.
- [x] `D-RS-06` Analyzer/deterministic enforcement is project-local because the covered surface is project-specific; any future PACED-level generalization requires separate Delphi-scope approval.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | AutoRoute route wrapping is the right extension point for route-local dependency boundaries. | AutoRoute exposes `AutoRouteWrapper`/wrapped route composition; current detail routes already use resolver route wrappers. | Need a different route composition primitive before implementation. | `High` | `Keep as Assumption` |
| `A-02` | The immediate user-visible regression is bounded to stackable tenant-public detail routes. | Reproduced flow is Account Profile parent/child detail; Event and Static Asset share the immersive/detail route pattern. | Scope may need renewed approval for admin/workspace route families. | `Medium` | `Keep as Assumption` |
| `A-03` | Project-local analyzer coverage can target the covered route-detail surfaces without breaking unrelated legacy screens. | Existing `belluga_analysis_plugin` already has project-specific UI/controller DI rules and a rule matrix runner. | Use a narrower deterministic script first and defer analyzer integration. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `flutter-app/lib/application/router/support/**`
- `flutter-app/lib/presentation/tenant_public/**/routes/**`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/lib/presentation/tenant_public/static_assets/static_asset_detail_screen.dart`
- `flutter-app/test/application/router/support/**`
- Focused tenant-public detail screen/widget/navigation tests.
- `flutter-app/tool/belluga_analysis_plugin/**` if analyzer enforcement is adopted.
- `foundation_documentation/modules/flutter_client_experience_module.md`

### Ordered Steps
1. Run `todo_authority_guard.py` after approval.
2. Add fail-first support tests for route scope identity, child-scope separation, disposal, and modal capture.
3. Implement route-instance scope primitives and an AutoRoute/ResolverRoute-compatible wrapper.
4. Migrate covered tenant-public detail route classes and screens to the route-scoped lookup.
5. Add/strengthen Account Profile nested route navigation tests with controller identity assertions.
6. Add/strengthen modal/descendant lookup tests.
7. Add project-local guardrail enforcement for direct global detail-controller lookup in covered surfaces.
8. Update Flutter module documentation and run focused validation, analyzer, rule matrix, route audit, and required build/smoke lanes.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Route scope returns the same controller inside one route and a distinct controller inside a nested detail route.
  - Dialog/modal opened from a parent or child detail route resolves that route's controller.
  - Static guard catches direct `GetIt.I.get<...DetailController>()` in covered detail surfaces.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / route scope support tests` | New route-scope primitive must prove identity/disposal/modal capture. | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/application/router/support/route_instance_scope_test.dart` | `Local-Implemented` | `passed` | `00:01 +2: All tests passed!` | Proves one instance per route store, nested isolation, disposal, and dialog/bottom-sheet scope capture. |
| `flutter-app / Account Profile controller tests` | Analyzer required replacing direct Account Profile model stream ownership while preserving profile detail projections. | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart` | `Local-Implemented` | `passed` | `00:00 +7: All tests passed!` | Controller now exposes `AccountProfileDetailState` and no longer owns a model payload stream. |
| `flutter-app / Account Profile detail focused tests` | Parent/child/back route identity bug originated here. | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` | `Local-Implemented` | `passed` | `00:13 +50: All tests passed!` | Includes nested linked profile route controller identity assertions and parent state restoration. |
| `flutter-app / Static Asset detail focused tests` | Static Asset detail adopts the same route-scoped resolver pattern. | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart` | `Local-Implemented` | `passed` | `00:02 +5: All tests passed!` | Preserves share/back/Como Chegar behavior after route-scoped lookup. |
| `flutter-app / Event detail focused tests` | Event immersive detail adopts the same route-scoped resolver pattern. | `fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `Local-Implemented` | `passed` | `00:16 +49: All tests passed!` | Preserves hero, invite route, share, programming, Como Chegar, route prompt, and back behavior. |
| `flutter-app / analyzer` | Flutter architecture and route source changes. | `fvm dart analyze --format machine` | `Local-Validated` | `passed` | exited `0`; only informational duplicate `constant_identifier_names` for generated Boora icon `invitation_outlined` | Architecture warnings introduced during implementation were resolved before this pass. |
| `flutter-app / rule matrix` | New analyzer rule and existing resolver-route rule changed. | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `Local-Validated` | `passed` | command exited `0`; output included `success: detected 58 configured lint codes` and `total distinct codes emitted: 59` | Includes negative fixture for direct global detail-controller `GetIt` lookup. |
| `flutter-app / route contract audit` | Route wrappers/generated contracts may change. | Route contract `rg` audit against `lib/application/router/app_router.gr.dart`. | `Local-Validated` | `passed` | no matches for required non-URL route args | No new required non-URL route args. |
| `flutter-app / direct detail-controller GetIt audit` | Covered screens must not resolve route detail controllers directly from global GetIt. | Direct detail-controller GetIt `rg` audit across covered tenant-public detail screen folders. | `Local-Validated` | `passed` | no production matches | Test harness and analyzer fixture are the only intentional direct lookup references outside production covered surfaces. |
| `docker / web build` | Browser-visible route/detail behavior changed. | `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev` | `Promotion-Package-Validated` | `passed` | built in `104.1s`; bundle available at `web-app` lane `dev` | Uses canonical local-public bundle path. |
| `docker / source-owned web APD readonly smoke` | Browser runtime must prove Account Profile detail/back no longer reopens stale detail and APD expectations account for nested groups. | APD readonly smoke via `tools/flutter/run_web_navigation_smoke.sh readonly` with `NAV_WEB_GREP_EXTRA` selecting `NAV-APD-01` and `NAV-APD-02`. | `Promotion-Package-Validated` | `passed` | rerun passed `2 passed (39.1s)` | First run caught stale APD empty-state expectation for profiles with `nested_profile_groups`; source-owned test was corrected and rerun green. |

## Approval
- **Status:** `approved`
- **Approved by:** user in chat
- **Approved at:** `2026-06-01T21:29:15-03:00`
- **Approval evidence:** user message `APROVADO`
- **Approval scope:** implement the route-scoped detail controller contract for current tenant-public stackable detail surfaces on the active v0.2.0+8 reconcile lane, including AutoRoute-compatible route wrapping/resolver composition, route-local controller lookup for screens/descendants/modals, project-local guardrail coverage, documentation update, and focused validation.
- **Renewed approval required if:** implementation expands beyond tenant-public stackable detail routes, changes backend/API/schema, alters product semantics, or introduces a broader global DI migration.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Approved tactical TODO execution requires explicit approval, frozen decisions, and guard pass before code edits. | Approved boundary, frozen `D-RS-*` decisions, test-first plan, delivery evidence. | Chat-only implementation, hidden scope expansion, missing rule ingestion. | Run `todo_authority_guard.py` before editing Flutter source and record evidence before delivery. |
| `delphi-ai/workflows/docker/todo-execution-boundary-method.md` | Implementation is starting after `APROVADO`. | Route-scoped objective and renewal triggers. | Editing outside the approved lane or bypassing guards. | Execute only after `Overall outcome: go`. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | The slice touches Flutter presentation, routes, controller lookup, and analyzer rules. | Controllers own state/effects; presentation stays repository/service/DTO-free; analyzer/rule matrix gate. | Direct repository/service resolution, per-file ignores, controller passing through route constructors. | Keep route-local controller lookup as the only same-feature UI controller access for covered detail routes. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md` | Route files and AutoRoute resolver composition are in scope. | `tenant_public` ownership, RouteModelResolver hydration, generated-route contract audit, no synthetic history. | Undefined subscopes, manual ancestry/history fabrication, unclassified required non-URL args. | Preserve current route paths and resolver hydration while wrapping route instances. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The bug is false-green prone and needs behavior-defining regression tests. | Fail-first route identity, descendant, and modal assertions; honest CI status. | Status-only tests or implementation-only assertions that do not prove controller identity. | Add route support tests and focused detail navigation/widget tests before claiming closure. |
| `foundation_documentation/policies/scope_subscope_governance.md` | Route/screen ownership must be declared before route changes. | `EnvironmentType=tenant`, `main scope=tenant_public`, no new subscope. | Introducing route/subscope ownership drift. | Document ownership and audit generated routes. |

## Decision Adherence
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-RS-01` | `RouteScopedResolverRoute<TModel, TModule>` wraps AutoRoute pages with `ModuleScope<TModule>` and `RouteInstanceScope` while preserving resolver params/buildScreen. | passed | No controller constructor passing or synthetic route/history workaround was introduced. |
| `D-RS-02` | Covered screens use `RouteInstanceScope.read<T>(context)`/`RouteInstanceScope.get<T>(context)`; production direct global `GetIt` audit returned no matches for covered detail controllers. | passed | Global `GetIt` remains the factory source inside `RouteInstanceStore`, not the screen lookup surface. |
| `D-RS-03` | Static Asset test harness was migrated off controller constructor injection; route/screen constructors do not carry detail controller args. | passed | The canonical boundary is inherited route scope. |
| `D-RS-04` | `showRouteScopedDialog` and `showRouteScopedModalBottomSheet` capture and re-expose the originating store. | passed | Route-scope support test proves dialog and bottom-sheet lookup return the parent route controller instance. |
| `D-RS-05` | Account Profile, Event immersive, and Static Asset detail routes extend `RouteScopedResolverRoute`. | passed | Hard cutoff stayed in tenant-public stackable detail surfaces. |
| `D-RS-06` | Project-local analyzer rule `route_scoped_detail_controller_getit_forbidden` and rule matrix fixture were added. | passed | Enforcement stayed in project-local analyzer plugin/docs. |

## Security / Tenant Boundary Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Tenant-public route-scoped controller resolution | No authentication, authorization, backend API, tenant resolution, or cross-tenant data path changed. | passed | Diff review; focused widget tests; source-owned APD readonly smoke against `https://guarappari.belluga.space`. | Scope ownership remains `EnvironmentType=tenant`, `main scope=tenant_public`, no new subscope. |

## Performance / Concurrency Review
| Surface | Review Focus | Status | Evidence | Notes |
| --- | --- | --- | --- | --- |
| Route instance store | Per-route cache should avoid fresh controller creation on descendant/modal lookups and dispose route-owned controllers on pop. | passed | `route_instance_scope_test.dart` and Account Profile nested route identity test. | `RouteInstanceStore` caches one instance per type and calls `Disposable.onDispose()` in reverse creation order. |
| Account Profile detail state | Analyzer-required projection state must not reintroduce repository fetches or global model stream ownership. | passed | `account_profile_detail_controller_test.dart`; analyzer exit `0`. | Removed unused `loadAccountProfile(slug)` direct async repository fetch and replaced model stream with `AccountProfileDetailState`. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| v0.2.0+8 route-scoped controller package | CI/Copilot failure modes: route resolver recognition, direct GetIt regressions, model-stream analyzer warnings, stale browser APD expectation with nested groups. | passed | Focused Flutter tests, `fvm dart analyze --format machine`, `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`, route/direct-GetIt audits, web build, APD readonly smoke. | none | Analyzer initially flagged wrapper recognition and controller model-stream ownership; APD smoke initially flagged stale empty-state fixture. Both were corrected and rerun green. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter route/controller architecture | Searched for direct global detail-controller `GetIt`, controller constructor passing, `GetIt.pushNewScope`, route args that are not URL-hydratable, and overlays resolving outside originating route scope. | passed | Direct `rg` audits; route contract audit; route-scope support tests; Account Profile nested route identity test. | no active bypass found | Route scope is AutoRoute-composed and inherited; overlay helpers re-expose the same store. |
| Test false-green resistance | Browser smoke must catch real APD route behavior and nested group data shape, not stale empty-state assumptions. | passed | APD readonly smoke failed first on stale `Du Jorge` empty-state expectation, then passed `2 passed (39.1s)` after source-owned test correction. | stale APD fixture expectation found | `isMinimalNoSections` now excludes any detail payload with `nested_profile_groups`. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| SCOPE-01 | Scope | Establish a reusable route-instance scope primitive for Flutter detail routes. | unit/widget test + analyzer | `RouteInstanceStore`, `RouteInstanceScope`; `route_instance_scope_test.dart` | Flutter widget test | passed | Includes nested isolation, disposal, and overlay capture. |
| SCOPE-02 | Scope | Wrap stackable tenant-public detail ResolverRoutes with the route-instance scope while preserving RouteModelResolver hydration and ModuleScope behavior. | code + analyzer + route audit | `partner_detail_route.dart`, `immersive_event_detail_route.dart`, `static_asset_detail_route.dart`; route contract audit no matches | Flutter route/router code | passed | `RouteScopedResolverRoute` preserves resolver hydration and ModuleScope. |
| SCOPE-03 | Scope | Migrate current tenant-public stackable detail screens to resolve their same-feature detail controller from the route scope instead of direct global `GetIt.I.get`. | code + direct GetIt audit + browser route smoke | `RouteInstanceScope.read/get` in Account Profile, Event, Static Asset screens; direct GetIt audit no matches; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter presentation + local-public browser route runtime | passed | Screen constructors do not accept detail controllers; NAV-APD route smoke passed `2 passed (39.1s)`. |
| SCOPE-04 | Scope | Provide a canonical helper for route-scoped modal/dialog/sheet rendering so overlays opened from a detail route keep resolving the same route-instance controller. | widget test + code | `showRouteScopedDialog`; `showRouteScopedModalBottomSheet`; `route_instance_scope_test.dart` | Flutter overlay/widget test | passed | Dialog and bottom sheet resolve the same route-local controller. |
| SCOPE-05 | Scope | Add tests proving parent and child Account Profile detail routes use distinct controller instances and that back restores the parent instance/state. | widget/navigation test | `account_profile_detail_screen_test.dart` | Flutter widget/navigation test | passed | `nested linked profile routes keep isolated detail controller instances` asserts distinct controllers, child disposal, and parent state restoration. |
| SCOPE-06 | Scope | Add tests proving descendant widgets and route-owned modals resolve the route-local controller instance, not a fresh or sibling instance. | widget test | `route_instance_scope_test.dart` | Flutter widget test | passed | Modal and bottom-sheet builders resolve the originating route store. |
| SCOPE-07 | Scope | Add a project-local analyzer/deterministic rule or equivalent guard that blocks direct global `GetIt` detail-controller resolution inside the covered stackable detail route surfaces. | analyzer rule matrix | `route_scoped_detail_controller_getit_forbidden_rule.dart`; rule matrix command exited `0` | Project-local analyzer plugin | passed | Fixture contains forbidden `GetIt.I.get<AccountProfileDetailController>()` and allowed `RouteInstanceScope.get`. |
| SCOPE-08 | Scope | Update Flutter module documentation to specialize the Presentation DI Matrix for stackable route-detail controllers. | documentation update | `flutter_client_experience_module.md` sections `2.1.1`, `2.1.2`, `FCX-16` | Foundation documentation | passed | Decision consolidated as `FCX-16`. |
| DOD-01 | Definition of Done | `DOD-01` Current tenant-public stackable detail route instances have deterministic per-route controller identity. | widget/navigation tests + browser route smoke | `route_instance_scope_test.dart`; `account_profile_detail_screen_test.dart`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget tests + local-public browser route runtime | passed | Route store caches one instance per type per route; NAV-APD route smoke passed `2 passed (39.1s)`. |
| DOD-02 | Definition of Done | `DOD-02` Parent Account Profile route, child Account Profile route, and restored parent after back are proven with controller identity/state assertions. | widget/navigation test + browser route smoke | `account_profile_detail_screen_test.dart` -> `00:13 +50: All tests passed!`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget/navigation test + local-public browser route runtime | passed | Parent controller survives child pop; child controller is disposed; NAV-APD-01 route back-stack smoke passed. |
| DOD-03 | Definition of Done | `DOD-03` Descendant widgets that need the detail controller resolve the current route scope. | code + tests + browser route smoke | `RouteInstanceScope.get/read`; Account Profile nested route test; direct GetIt audit; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter presentation + tests + local-public browser route runtime | passed | No production direct global detail-controller lookup remains in covered surfaces; NAV-APD route smoke passed. |
| DOD-04 | Definition of Done | `DOD-04` A modal/dialog/sheet opened from a detail route resolves the originating route scope. | widget test + browser route smoke | `route_instance_scope_test.dart` -> `00:01 +2: All tests passed!`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget test + local-public browser route runtime | passed | Dialog and bottom sheet resolved parent route store instance; APD route smoke covered the final domain bundle. |
| DOD-05 | Definition of Done | `DOD-05` Event and Static Asset immersive detail routes preserve existing behavior while adopting the same canonical route scope pattern where they own detail controllers. | focused Flutter tests + browser route smoke | Event detail `00:16 +49`; Static Asset `00:02 +5`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget tests + local-public browser route runtime | passed | Existing hero/share/back/Como Chegar/prompt behavior remained green; final route-scoped bundle passed APD browser route smoke. |
| DOD-06 | Definition of Done | `DOD-06` Analyzer/rule matrix or an explicit deterministic guard fails on direct global `GetIt.I.get<...DetailController>()` in covered stackable detail surfaces. | analyzer rule matrix + browser route smoke | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` command exited `0`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Analyzer fixture matrix + local-public browser route runtime | passed | Rule matrix passed after adding negative fixture; APD route smoke passed against final bundle. |
| DOD-07 | Definition of Done | `DOD-07` Route contract audit shows no new required non-URL args or classifies any generated route args explicitly. | static route audit + browser route smoke | generated router route-contract audit; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter generated router + local-public browser route runtime | passed | No matches; APD route smoke passed against final bundle. |
| DOD-08 | Definition of Done | `DOD-08` Flutter analyzer, focused tests, rule matrix, and required v0.2.0+8 validation lanes pass before delivery claim. | local CI-equivalent rows + browser route smoke | Local CI-Equivalent Suite Matrix above; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter + local-public web route runtime | passed | Analyzer exited `0`; web build passed; APD readonly route smoke passed. |
| VAL-01 | Validation Steps | RED/GREEN route-scope support tests for nested scope identity, disposal, and modal scope capture. | fail-first + green test + browser route smoke | Missing support file failed before implementation; green `route_instance_scope_test.dart` passed; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget test + local-public browser route runtime | passed | Recorded fail-first in session; final green row above; APD route smoke passed. |
| VAL-02 | Validation Steps | RED/GREEN Account Profile detail navigation test for parent -> child -> back with controller identity assertions. | fail-first + green test | Initial focused assertion failed because child controller was not disposed; final Account Profile suite passed. | Flutter widget/navigation test | passed | Proves the user-reported route-stack failure mode. |
| VAL-03 | Validation Steps | RED/GREEN descendant/modal test proving route-local controller resolution. | widget test + browser route smoke | `route_instance_scope_test.dart`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget test + local-public browser route runtime | passed | Overlay path validated; final APD route smoke passed. |
| VAL-04 | Validation Steps | Focused consumer tests for Account Profile, Event, and Static Asset detail screens. | Flutter tests + browser route smoke | Account Profile, Event, Static Asset focused suites; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter widget tests + local-public browser route runtime | passed | 50 + 49 + 5 scenarios green after final changes; APD route smoke passed. |
| VAL-05 | Validation Steps | `fvm dart analyze --format machine`. | static analysis | `fvm dart analyze --format machine` | Flutter app | passed | Exit `0`; only informational generated-font constant lint emitted. |
| VAL-06 | Validation Steps | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` if analyzer/plugin or rule matrix changes. | analyzer fixture matrix | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` command exited `0` | Analyzer plugin fixture | passed | Configured lint-code coverage detected. |
| VAL-07 | Validation Steps | Route contract audit on `lib/application/router/app_router.gr.dart`. | static route audit + browser route smoke | generated router route-contract audit; `tools/flutter/web_app_tests/account_profile_detail.spec.js` via `tools/flutter/run_web_navigation_smoke.sh readonly` | Flutter generated router + local-public browser route runtime | passed | No new required non-URL args; APD route smoke passed. |
| VAL-08 | Validation Steps | Web build and source-owned navigation smoke if route/router generated code or browser-visible behavior changes. | web build + Playwright | `CLEAN_OUTPUT=1 bash flutter-app/scripts/build_web.sh web-app dev`; source-owned spec `tools/flutter/web_app_tests/account_profile_detail.spec.js`; runner `tools/flutter/run_web_navigation_smoke.sh readonly` | Local-public browser route runtime on `https://guarappari.belluga.space` lane `dev` with refreshed `web-app` bundle | passed | Build passed in `104.1s`; APD route smoke passed `2 passed (39.1s)`. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** local implementation and validation are complete, and the current package-wide mimic loop kept this TODO clean with no reopened findings; only authorized lane follow-through remains.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it through the current v0.2.0+8 package promotion.
