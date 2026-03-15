# TODO (V1): Invite Deep Link Identity-First Delivery (Guarappari)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter/Product) + Backend Team + Web/Infra Team  
**Goal:** Ensure invite deep links (`/invite?code=...`) resolve with identity-first behavior, deterministic routing, and platform deep-link readiness (Android + iOS) for `guarappari`.

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/artifacts/tmp/web_to_app_invite_handoff_2026-03-14.md`

---

## Execution Governance

- **Primary module anchor:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module anchors:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Planned promotion targets:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

## Applicable Rules/Workflows (Declared Before Approval)
- `delphi-ai/main_instructions.md`
- `skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `skills/flutter-architecture-adherence/SKILL.md`
- `skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`

---

## Scope Restatement
- Enforce **identity-first** invite acceptance: share-code acceptance requires authenticated user identity, not anonymous identity.
- Preserve deep-link context (`/invite?code=...`) across login redirect and resume resolution after auth.
- Remove non-deterministic invite resolution paths and guarantee consistent route outcome for `code` entry.
- Add Android App Links readiness for `guarappari.belluga.space`.
- Add iOS Universal Links readiness for `guarappari.belluga.space`.
- Ensure web fallback continues preserving `code` when app open fails.

## Out of Scope Restatement
- Multi-tenant rollout beyond `guarappari` in this stream.
- Invite domain redesign outside share-code acceptance/auth gating.
- Marketing attribution model redesign beyond preserving inviter principal continuity.
- New social surfaces or web inbox parity.

---

## Complexity Classification + Checkpoint Policy
- **Complexity:** `big`
- **Checkpoint policy:** section-by-section
  1. Policy + backend auth boundary
  2. Flutter routing/guard + invite flow state handling
  3. Android/iOS deep-link platform setup
  4. Infra `/.well-known` hosting + validation evidence

---

## Plan Review Gate

### Issue Cards

- `I-01` `critical`
  - Evidence: `foundation_documentation/policies/web_to_app_promotion_policy.md` currently allows anonymous-token web acceptance; user decision now requires login for acceptance.
  - Why now: policy/contract mismatch will reintroduce non-sync acceptance paths and attribution divergence.
  - Option A: keep anonymous acceptance and patch sync later. Effort `low`; risk `critical`; blast radius `high`; maintenance `high`.
  - Option B (**Recommended**): require authenticated identity for share accept now and supersede policy/docs accordingly. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `low`.
  - Option C: dual-mode acceptance by tenant flag. Effort `high`; risk `high`; blast radius `high`; maintenance `high`.

- `I-02` `high`
  - Evidence: Flutter invite flow currently accepts code then may silently fall to Home when pending list is empty.
  - Why now: deep-link journeys lose determinism and troubleshooting visibility.
  - Option A (**Recommended**): enforce deterministic controller outcome state and explicit route handling contract; retain product-approved end behavior for invalid code.
    Effort `medium`; risk `low`; blast radius `medium`; maintenance `low`.
  - Option B: keep current fallback. Effort `none`; risk `high`; blast radius `medium`; maintenance `medium`.
  - Option C: add ad-hoc widget `if`s only. Effort `low`; risk `medium`; blast radius `medium`; maintenance `high`.

- `I-03` `high`
  - Evidence: Android manifest lacks HTTPS App Link `intent-filter` and `autoVerify` entries.
  - Why now: web-to-app open cannot reliably route to native app when installed.
  - Option A (**Recommended**): add canonical App Link intent-filter for guarantied host/path scope.
    Effort `medium`; risk `medium`; blast radius `low`; maintenance `low`.
  - Option B: rely on browser + manual open-app CTA only. Effort `low`; risk `high`; blast radius `medium`; maintenance `medium`.
  - Option C: custom scheme-only deep link. Effort `low`; risk `medium`; blast radius `medium`; maintenance `medium`.

- `I-04` `high`
  - Evidence: no `assetlinks.json`/`apple-app-site-association` delivery path is versioned.
  - Why now: without hosted files, App/Universal Links verification fails in production.
  - Option A (**Recommended**): deliver versioned `.well-known` artifacts + nginx serving rule.
    Effort `medium`; risk `medium`; blast radius `medium`; maintenance `low`.
  - Option B: manual server-only setup outside repo. Effort `low`; risk `high`; blast radius `high`; maintenance `high`.
  - Option C: postpone until post-MVP. Effort `none`; risk `high`; blast radius `medium`; maintenance `medium`.

- `I-05` `high`
  - Evidence: iOS project lacks associated domains/entitlements configuration baseline.
  - Why now: user requested iOS inclusion in this same stream.
  - Option A (**Recommended**): include iOS universal-link setup in this stream with concrete app identifier inputs.
    Effort `medium`; risk `medium`; blast radius `low`; maintenance `low`.
  - Option B: defer iOS. Effort `low`; risk `medium`; blast radius `medium`; maintenance `medium`.
  - Option C: partial placeholder-only iOS config. Effort `low`; risk `high`; blast radius `medium`; maintenance `high`.

### Failure Modes & Edge Cases
- `code` valid but invite already accepted by same authenticated user.
- Login redirect loses query params and drops `code` on return.
- Android app installed but domain verification fails and browser opens web instead.
- iOS AASA cached stale; universal links not refreshed after deploy.
- Web fallback strips query string when store/open-app handoff fails.
- Tenant mismatch (non-guarappari host using guarappari code).

### Uncertainty Register
- Assumption: `guarappari` remains the only tenant in rollout scope for this stream.
- Assumption: backend can enforce identity-state gate on share accept without schema migration.
- Unknown: final iOS app identifiers required for AASA (`TeamID.BundleID`) in guarappari lane.
- Unknown: whether product wants silent-home fallback for invalid/consumed code as permanent behavior (it conflicts with a prior explicit-state direction).
- Confidence: `medium`

---

## Decision Baseline (Frozen)
- `D-01` Share-code acceptance requires authenticated identity; anonymous identities cannot accept invites.
- `D-02` Deep-link auth redirect must preserve original `/invite?code=...` context end-to-end.
- `D-03` For invalid/consumed code, final behavior follows product-approved fallback policy (currently: silent home fallback).
- `D-04` `/convites` invite entry route must be guarded for authenticated tenant user flow consistency.
- `D-05` Android App Links for `guarappari.belluga.space` must be configured in app manifest and verified with `assetlinks.json`.
- `D-06` iOS Universal Links for `guarappari.belluga.space` must be configured via associated domains + AASA.
- `D-07` Web/infra must serve `/.well-known/assetlinks.json` and `/.well-known/apple-app-site-association` from canonical deployment path.
- `D-08` Rollout scope is guarappari-only; no other tenant hosts/package IDs are modified in this stream.
- `D-09` Test coverage is mandatory with unit + integration tests for all deterministic acceptance criteria; OS-level app-open verification remains manual evidence.

---

## Module Decision Baseline Snapshot
- `INV-PD-05`: native app is direct contract owner for invite actions.
- `INV-PD-06`: web exception boundary is narrow and code-bound.
- `FCX-02`: route/scope ownership must be explicit and guarded.
- `Web-to-App policy v1.1`: currently allows anonymous-token acceptance (candidate supersede by D-01).

---

## Module Decision Consistency Matrix (Planned)
| Decision | Module Reference | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `D-01` | Web-to-App policy section `3.1`, `4.0`, `4.1` | `Supersede (Intentional)` | policy currently allows anonymous-token acceptance; user-approved direction requires login-only acceptance. |
| `D-02` | `INV-PD-06`, FCX task hooks (`/invites/share/{code}/accept`) | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §4.4, `flutter_client_experience_module.md` §2.1 |
| `D-03` | No prior canonical decision explicitly fixes invalid-code UX | `Out of Scope` (no prior baseline) | requires explicit TODO-owned execution note + later module promotion if kept. |
| `D-04` | FCX route matrix + tenant user guard expectation | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` §2.0 |
| `D-05` | Web-to-App promotion requirement | `Preserve` | `foundation_documentation/artifacts/tmp/web_to_app_invite_handoff_2026-03-14.md` + policy alignment target |
| `D-06` | Web-to-App promotion requirement | `Preserve` | same as above; iOS inclusion explicitly approved by product |
| `D-07` | Runtime ingress serving obligations | `Preserve` | `system_architecture_principles.md` P-18 (ingress parity) |
| `D-08` | User scope directive | `Preserve` | session decision: guarappari-only |
| `D-09` | Test governance for delivery readiness | `Preserve` | session decision: maximize automated assertions (unit/integration) against acceptance criteria |

---

## Implementation Tasks

### A) Policy + Backend
- [x] ✅ Update web-to-app canonical docs to supersede anonymous acceptance and require authenticated identity for share-code acceptance.
- [x] ✅ Enforce backend identity-state guard on `POST /api/v1/invites/share/{code}/accept` (reject anonymous identities with deterministic auth error).
- [x] ✅ Add/adjust backend tests for anonymous rejection + authenticated success in share-code acceptance.

### B) Flutter (Route/Guard/Flow)
- [x] ✅ Add route guard coverage for invite entry flow requiring authenticated tenant identity.
- [x] ✅ Ensure login redirect preserves full invite deep-link query context.
- [x] ✅ Refactor invite flow resolution to deterministic state machine; no accidental route loss.
- [x] ✅ Implement product-approved invalid/consumed-code behavior (`silent home fallback`).
- [x] ✅ Add/refresh unit/widget tests for guarded deep-link flow and code-resolution outcomes.

### C) Android App Links (Guarappari)
- [x] ✅ Add HTTPS App Link `intent-filter` (`autoVerify=true`) for `guarappari.belluga.space` invite routes.
- [x] ✅ Provide `assetlinks.json` for `com.guarappari.app` with release fingerprint.
- [ ] 🟡 Capture verification evidence (`adb` app-links checks).

### D) iOS Universal Links (Guarappari)
- [x] ✅ Configure associated domains entitlements for `guarappari.belluga.space`.
- [x] ✅ Provide `apple-app-site-association` for guarappari app target.
- [ ] 🟡 Capture verification evidence (device/simulator link-open path + domain association logs when available).

### E) Infra / Web Fallback
- [x] ✅ Ensure nginx/runtime serves both `.well-known` files in production lane.
- [x] ✅ Keep web fallback preserving `code` when app open fails.

### F) Test Delivery Track
- [x] ✅ Add backend automated coverage for share-code accept boundary (authenticated success + anonymous rejection).
- [x] ✅ Add Flutter unit tests for invite guard, deep-link parameter preservation, and deterministic code-resolution outcomes.
- [x] ✅ Add Flutter integration test for deep-link -> login -> return-to-original-link -> resolution flow.
- [ ] 🟡 Capture manual evidence for Android/iOS installed-app open behavior and fallback when app is not installed.

---

## Acceptance Criteria
- [x] ✅ Anonymous identity cannot accept invite share code; authenticated user can.
- [x] ✅ Login flow round-trips back to original deep link with `code` preserved.
- [x] ✅ Invite deep-link resolution no longer drops context unpredictably.
- [ ] 🟡 Android App Link opens native app for guarappari when installed.
- [ ] 🟡 iOS Universal Link opens native app for guarappari when installed.
- [x] ✅ `.well-known/assetlinks.json` and `.well-known/apple-app-site-association` are deployable via canonical infra path.

---

## Test Strategy Decision (Frozen Before Implementation)
- Automated tests are required to cover acceptance criteria whenever behavior is deterministic inside backend/app boundaries.
- Mandatory automated suites for this stream:
  - Backend feature/integration: authenticated acceptance success and anonymous acceptance rejection.
  - Flutter unit/widget: guard/auth redirect contract, deep-link `code` preservation, and deterministic resolution outcomes.
  - Flutter integration (`integration_test`): end-to-end deep-link auth round-trip inside app runtime.
- Manual-only criteria (cannot be reliably asserted in CI due to OS/domain-association dependencies):
  - Android App Link opening native app when installed.
  - iOS Universal Link opening native app when installed.
  - Browser fallback behavior when app is absent, preserving `code`.
- Delivery is blocked if automated suites are missing for deterministic criteria.

---

## Validation Steps
- [x] ✅ Backend feature/integration tests: share accept authenticated success + anonymous rejection.
- [x] ✅ Flutter unit/widget tests: invite guard/auth redirect + code handling outcomes.
- [x] ✅ Flutter integration test: deep-link auth round-trip with preserved `code` (`--flavor guarappari`).
- [x] ✅ `fvm flutter analyze`.
- [x] ✅ `fvm dart run custom_lint`.
- [ ] 🟡 Android manual: browser click on `/invite?code=...` with app installed and not installed.
- [ ] 🟡 iOS manual: universal link open with app installed and not installed.
- [x] ✅ Web fallback: code preserved through login handoff (`AuthRouteGuard` + auth-login round-trip integration test).

---

## Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`, `laravel-app/tests/Feature/Invites/InvitesFlowTest.php` | Anonymous identity rejection + authenticated success validated in backend test suite. |
| `D-02` | `Adherent` | `flutter-app/lib/application/router/guards/auth_route_guard.dart`, `flutter-app/test/application/router/guards/auth_route_guard_test.dart`, `flutter-app/integration_test/feature_invite_deeplink_auth_roundtrip_test.dart` | Redirect path normalization + query preservation asserted in unit/integration tests. |
| `D-03` | `Adherent` | `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart` | Product-approved silent-home fallback remains deterministic when invite list is empty. |
| `D-04` | `Adherent` | `flutter-app/lib/application/router/modular_app/modules/invites_module.dart`, `flutter-app/test/application/router/modules/invites_module_test.dart` | Invite entry/share routes require `TenantRouteGuard` + `AuthRouteGuard`. |
| `D-05` | `In Progress` | `flutter-app/android/app/src/main/AndroidManifest.xml`, `flutter-app/.well-known/assetlinks.json`, `flutter-app/test/platform/deep_link_platform_config_test.dart` | App Links wiring + artifacts + config tests complete; device verification pending credentials/fingerprint finalization. |
| `D-06` | `In Progress` | `flutter-app/ios/Runner/Runner.entitlements`, `flutter-app/ios/Runner.xcodeproj/project.pbxproj`, `flutter-app/.well-known/apple-app-site-association`, `flutter-app/test/platform/deep_link_platform_config_test.dart` | Universal Links wiring + artifacts + config tests complete; device verification pending Apple team/bundle credential finalization. |
| `D-07` | `Adherent` | `docker/nginx/prod.conf.template`, `docker/nginx/local.conf.template`, `laravel-app/public/.well-known/*`, `flutter-app/test/platform/deep_link_platform_config_test.dart` | Canonical runtime serving path versioned and test-asserted. |
| `D-08` | `Adherent` | `flutter-app/.well-known/*`, `laravel-app/public/.well-known/*` | Scope remains guarappari-only (`com.guarappari.app`, guarappari invite paths/domains). |
| `D-09` | `Adherent` | `laravel-app/tests/Feature/Invites/InvitesFlowTest.php`, `flutter-app/test/application/router/**`, `flutter-app/test/platform/deep_link_platform_config_test.dart`, `flutter-app/integration_test/feature_invite_deeplink_auth_roundtrip_test.dart` | Automated backend/unit/integration coverage executed successfully; only OS-level manual checks remain pending. |

## Module Decision Consistency Validation
| Decision | Delivery Status | Evidence | Notes |
| --- | --- | --- | --- |
| `INV-PD-05` | `Preserved` | `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php` + `InvitesFlowTest` | Native/app conversion ownership preserved; web acceptance remains narrow and policy-bounded. |
| `INV-PD-06` | `Preserved` | `flutter-app/lib/application/router/guards/auth_route_guard.dart`, `integration_test/feature_invite_deeplink_auth_roundtrip_test.dart` | Deep-link continuity preserved through auth redirect with `code` round-trip. |
| `FCX-02` | `Preserved` | `flutter-app/lib/application/router/modular_app/modules/invites_module.dart` + route guard tests | Route ownership explicit and guard-enforced for invite entry/share flows. |
| `Web-to-App policy v1.1` | `Superseded (Approved)` | `foundation_documentation/policies/web_to_app_promotion_policy.md` (v1.2) | Anonymous acceptance removed; identity-first acceptance is canonical. |
