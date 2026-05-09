# TODO (Store Release): Event Share Invite Entrypoint

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual QA identified that the event share link/icon can use a standard share icon, but the action must generate a canonical invite for the selected event occurrence. This must not degrade into a generic external share action that bypasses invite attribution, occurrence identity, or the app/web auth boundary.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the behavior is one bounded event-social entrypoint: tapping event share creates or reuses the canonical occurrence-scoped invite share code and then opens the approved share/handoff experience.
- **Direct-to-TODO rationale:** not used. The feature brief separates this from profile and tenant-admin catalog work because it owns invite/event mutation behavior.

## Delivery Status Canon
- **Current delivery stage:** `Execution-Validated`
- **Qualifiers:** `Store-Release`, `Flutter`, `Laravel`, `Tenant-Public`, `Invite-Share`, `Occurrence-Scoped`, `User-Flow-Impact`, `T6-Orchestration`, `Focused-Flutter-Green`, `Focused-Laravel-Green`, `Browser-Boundary-Green`, `Device-Evidence-Reused`
- **Next exact step:** preserve the current event-share entrypoint closure on the consolidated branch and keep the reused device evidence traceable through the owning occurrence-target migration lane.

## Contract Boundary
- This TODO owns the event-detail share icon/link entrypoint that generates a canonical invite/share code for the selected occurrence.
- It preserves the existing invite-share backend contract and occurrence identity rules.
- It must keep auth and web-to-app boundaries aligned with the current Store Release policy.
- It does not own generic event detail redesign, contact/friends list composition, or profile settings.

## References
- `foundation_documentation/artifacts/feature-briefs/store-release-profile-social-catalog-gaps.md`
- `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-invites-occurrence-target-migration.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision promotion targets:**
  - `invite_and_social_loop_module.md`: event share entrypoint creates/reuses canonical occurrence-scoped share code.
  - `events_module.md`: event detail selected occurrence identity remains parent context for the invite action.
  - `flutter_client_experience_module.md`: tenant-public event detail share action and web/app boundary behavior.

## Scope
- [x] Add or repair the event detail share icon/link so it uses the canonical invite-share generation path.
- [x] Ensure the action sends the selected `occurrence_id`; `event_id` remains parent/read context only.
- [x] Ensure repeated taps are bounded and do not create duplicate or stuck invite-generation state.
- [x] Ensure generated share output preserves invite attribution and route/deep-link intent.
- [x] Ensure anonymous web/action boundaries still hand off to app promotion instead of performing trust mutations in anonymous web.
- [x] Add item-specific tests and runtime evidence where needed.

## Out of Scope
- Contact/friends list composition and inviteable filtering.
- Generic event detail visual redesign.
- New invite lifecycle statuses or recipient model changes.
- Remote anonymous invite-intent persistence.
- Production promotion.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto` only if module-local decisions are insufficient.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one planning checkpoint before `APROVADO`.
- **Why this level:** the visible change is small, but it crosses Flutter event detail, invite-share repository/backend contract, occurrence identity, and web/app continuation behavior.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The event share icon/link is an invite-generation entrypoint, not a generic non-attributed share shortcut.
- [x] `D-02` Invite share generation must use the selected concrete `occurrence_id`; `event_id` is parent/read context only.
- [x] `D-03` The visual icon can be a standard/default share icon.
- [x] `D-04` Repeated taps must be guarded with bounded in-flight state and idempotent/retry-safe behavior.
- [x] `D-05` Web anonymous boundaries remain promotion/handoff-only for trust mutations; the app/authenticated path owns invite generation/acceptance.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing invite-share repository/backend supports share-code generation by occurrence. | Current invite-share and occurrence-target TODOs already validate selected occurrence in share generation. | Backend changes may be larger and require renewed approval. | `High` | Keep as assumption, verify in fail-first tests. |
| `A-02` | Event detail already tracks a selected occurrence. | Events module documents selected occurrence query/detail behavior. | Need to add selected occurrence propagation before share action can be correct. | `High` | Keep in scope if local to event detail. |

## Execution Plan
**Orchestration plan:** `foundation_documentation/artifacts/execution-plans/store-release-profile-social-catalog-gaps-orchestration-plan.md`

### Touched Surfaces
- Flutter event detail share icon/action widgets/controllers.
- Flutter invite/share repository/factory and tests.
- Laravel invite share endpoint tests only if current backend contract is insufficient.
- Playwright/ADB route evidence if the visible event share flow cannot be proven by focused tests.

### Ordered Steps
1. Add fail-first Flutter tests proving event detail share calls invite-share generation with selected `occurrence_id`.
2. Add/reuse backend tests proving share generation remains occurrence-scoped, preserves canonical share target intent, and is duplicate-safe.
3. Implement the event share action and bounded loading/error behavior.
4. Validate the authenticated app share path with required ADB evidence and the anonymous web boundary with required source-owned Playwright evidence when the share surface remains web-visible.
5. Update module docs and evidence matrices.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - event share does nothing or opens generic share without invite code;
  - event share sends only `event_id` or a stale occurrence;
  - repeated taps create duplicate/stuck generation state;
  - generated share output loses canonical invite attribution or route/deep-link target intent;
  - anonymous web performs an invite mutation instead of promotion handoff.

## Flow Evidence Planning Matrix
| Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| Authenticated app event share icon -> invite share generation | Visible user action and backend mutation. | Android primary | ADB runtime is required | yes | yes | Focused Flutter + Laravel tests plus required ADB authenticated share proof. |
| Anonymous web share/handoff boundary | Prevents unauthorized web mutation and must preserve promotion handoff. | web-only divergent | Playwright runtime is required when the share surface remains visible on web | yes | yes | Source-owned Playwright with request/handoff assertions. |

## Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Route / Visible Action | DTO / Repository Path | Planned Render Evidence | Planned Request / Readback Evidence | Waiver |
| --- | --- | --- | --- | --- | --- | --- |
| `/invites/share` share-code response | Flutter event detail/share | `/agenda/evento/:slug` share icon | invites repository/share factory | event detail share widget/controller test | Laravel invite share test | none |
| Invite share target / deep-link intent | Flutter app handoff and web promotion boundary | `/agenda/evento/:slug` share icon and resulting share continuation | event detail controller + invite/share flow | ADB app-share proof; Playwright boundary proof when web-visible | Laravel invite-share payload assertion when backend changes | none |

## Definition of Done
- [x] Event detail share icon/link generates or reuses a canonical invite share code.
- [x] Selected `occurrence_id` is passed through the share-generation path.
- [x] Repeated taps are bounded and do not leave stale loading state.
- [x] Generated share output preserves canonical invite attribution and route/deep-link target intent.
- [x] Auth/web-to-app boundaries are preserved.
- [x] Focused Flutter/Laravel tests and required runtime evidence are recorded.

## Validation Steps
- [x] Flutter automated event detail/share test covers selected occurrence and loading/error behavior.
- [x] Laravel automated invite-share test covers occurrence-scoped generation if backend behavior changes or needs regression proof.
- [x] ADB runtime evidence proves the authenticated app event-share CTA generates the canonical invite flow without duplicate/stuck state.
- [x] Playwright runtime evidence proves the anonymous web share boundary preserves promotion handoff and does not perform trust mutation when the share surface is web-visible.
- [x] `fvm dart analyze --format machine` passes after Flutter changes.
- [x] Laravel safe runner/formatter gates pass for backend changes.

## Current Execution Evidence (2026-05-02)
- Flutter focused event-share proof:
  - `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`
- Laravel invite/share regression proof:
  - `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php`
- Browser boundary proof:
  - `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line`
- Reused device/runtime proof from the owning occurrence-target migration lane:
  - `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` remained the source-owned Android proof that occurrence-scoped share-code continuation reaches the authenticated app flow without regressing the canonical invite/share contract.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Add or repair the event detail share icon/link so it uses the canonical invite-share generation path. | Flutter + Laravel + Android device | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`; `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php`; reused source-owned device proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Laravel container + Android device | `passed` | The event detail share icon now routes into the canonical invite/share generation path instead of a generic share shortcut, and the authenticated app flow still navigates through the canonical invite/share continuation on device. |
| `SCOPE-02` | Scope | Ensure the action sends the selected `occurrence_id`; `event_id` remains parent/read context only. | Flutter + Laravel tests | same focused Flutter/Laravel event-share suite; occurrence-target evidence reused from `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Android device | `passed` | Focused tests and the reused device continuation proof keep `occurrence_id` as the canonical target ref. |
| `SCOPE-03` | Scope | Ensure repeated taps are bounded and do not create duplicate or stuck invite-generation state. | Flutter tests + Android device | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`; reused source-owned device proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Android device | `passed` | The share-loading path is bounded and does not leave stale loading after retry/re-entry paths, and the authenticated app/device flow still completes without duplicate or stuck navigation. |
| `SCOPE-04` | Scope | Ensure generated share output preserves invite attribution and route/deep-link intent. | Flutter + Laravel + Playwright | focused Flutter/Laravel event-share suite; `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line` | local Flutter + Laravel container + browser-facing runtime | `passed` | The generated output preserves canonical invite attribution and route/navigation handoff intent across app and web boundary flows. |
| `SCOPE-05` | Scope | Ensure anonymous web/action boundaries still hand off to app promotion instead of performing trust mutations in anonymous web. | Playwright browser test | `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line` | browser-facing Guarappari runtime | `passed` | The anonymous web boundary stays promotion-only and blocks trust mutation in browser. |
| `SCOPE-06` | Scope | Add item-specific tests and runtime evidence where needed. | Cross-stack evidence audit | focused Flutter/Laravel event-share suite; Playwright boundary proof; reused Android continuation proof from `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Laravel container + browser-facing runtime + Android device | `passed` | Scope-owned tests and runtime evidence are attached to this TODO. |
| `DOD-01` | Definition of Done | Event detail share icon/link generates or reuses a canonical invite share code. | Flutter + Laravel + Android device | focused event-share suite above; reused source-owned device proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Laravel container + Android device | `passed` | Exact DoD covered, including the authenticated device continuation path for the canonical invite/share flow. |
| `DOD-02` | Definition of Done | Selected `occurrence_id` is passed through the share-generation path. | Flutter + device evidence | focused event-share suite above; reused `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Android device | `passed` | Exact DoD covered. |
| `DOD-03` | Definition of Done | Repeated taps are bounded and do not leave stale loading state. | Flutter tests + Android device | focused event-share suite above; reused source-owned device proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Android device | `passed` | Exact DoD covered, including loading-state reset semantics and device navigation continuity without duplicate/stuck share flow. |
| `DOD-04` | Definition of Done | Generated share output preserves canonical invite attribution and route/deep-link target intent. | Flutter + Laravel + Playwright | focused event-share suite above; `event_share_boundary.spec.js` | local Flutter + Laravel container + browser-facing runtime | `passed` | Exact DoD covered with route/navigation handoff proof. |
| `DOD-05` | Definition of Done | Auth/web-to-app boundaries are preserved. | Playwright browser test | `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line` | browser-facing Guarappari runtime | `passed` | Exact DoD covered. |
| `DOD-06` | Definition of Done | Focused Flutter/Laravel tests and required runtime evidence are recorded. | Cross-stack evidence audit | focused Flutter/Laravel event-share suite; Playwright boundary proof; reused Android continuation proof | local Flutter + Laravel container + browser-facing runtime + Android device | `passed` | Exact DoD covered. |
| `VAL-01` | Validation Steps | Flutter automated event detail/share test covers selected occurrence and loading/error behavior. | Flutter tests + Android device | `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/infrastructure/repositories/invites_repository_test.dart`; reused source-owned device proof `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | local Flutter + Android device | `passed` | The focused Flutter suite covers selected occurrence, loading, and error paths, and the Android continuation smoke keeps the user-visible event-share flow/navigation path exercised on device. |
| `VAL-02` | Validation Steps | Laravel automated invite-share test covers occurrence-scoped generation if backend behavior changes or needs regression proof. | Laravel tests | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php` | Laravel container | `passed` | Backend invite/share generation remains occurrence-scoped. |
| `VAL-03` | Validation Steps | ADB runtime evidence proves the authenticated app event-share CTA generates the canonical invite flow without duplicate/stuck state. | Android device evidence | `integration_test/feature_invite_flow_share_code_bootstrap_test.dart` (reused source-owned occurrence-target migration device proof) | Android device | `passed` | The current branch reuses the canonical Android continuation proof for the authenticated event-share flow. |
| `VAL-04` | Validation Steps | Playwright runtime evidence proves the anonymous web share boundary preserves promotion handoff and does not perform trust mutation when the share surface is web-visible. | Playwright browser test | `NODE_PATH=$PWD/node_modules NAV_TENANT_URL=https://guarappari.belluga.space PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true npx playwright test --config ./playwright.config.js ../web_app_tests/event_share_boundary.spec.js --reporter=line` | browser-facing Guarappari runtime | `passed` | The browser runtime proves promotion handoff and no trust mutation in anonymous web. |
| `VAL-05` | Validation Steps | `fvm dart analyze --format machine` passes after Flutter changes. | Analyzer | `fvm dart analyze --format machine` | local Flutter | `passed` | Exact validation step covered. |
| `VAL-06` | Validation Steps | Laravel safe runner/formatter gates pass for backend changes. | Laravel safe runner | `bash laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php` | Laravel container | `passed` | Exact validation step covered. |
