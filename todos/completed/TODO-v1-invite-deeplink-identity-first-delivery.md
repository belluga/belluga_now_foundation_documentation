# TODO (V1): Invite Deep Link Identity-First Delivery (Guarappari)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed (MVP scope closed; iOS production validation moved to VNext)  
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
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-ios-universal-links-production-validation.md`

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
  - `foundation_documentation/endpoints_mvp_contracts.md`
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
- Enforce **identity-first** attendance confirmation for event surfaces reached from invite/public flows: anonymous viewers must not persist attendance.
- Treat invite acceptance and event attendance confirmation as the same canonical attendance confirmation write, with invite acceptance adding inviter attribution/reference.
- Preserve deep-link context (`/invite?code=...`) across login redirect and resume resolution after auth.
- Remove non-deterministic invite resolution paths and guarantee consistent route outcome for `code` entry.
- Replace invite duplicate-closing semantics with explicit supersession semantics: `superseded` is business-outcome closure, while `suppressed` remains policy/governance closure.
- Add Android App Links readiness for `guarappari.belluga.space`.
- Add iOS Universal Links readiness for `guarappari.belluga.space`.
- Ensure web fallback continues preserving `code` when app open fails.

## Out of Scope Restatement
- Multi-tenant rollout beyond `guarappari` in this stream.
- Invite domain redesign outside share-code acceptance/auth gating.
- Marketing attribution model redesign beyond preserving inviter principal continuity.
- New social surfaces or web inbox parity.

## VNext Handoff (iOS)
- iOS runtime/manual verification was intentionally moved to `TODO-vnext-ios-universal-links-production-validation.md`.
- MVP closure for this TODO is based on Android/Web production readiness plus deterministic backend/Flutter test coverage.

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
  - Evidence: planning baseline (pre-policy v1.2) allowed anonymous-token web acceptance; user decision requires authenticated identity for acceptance.
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

- `I-06` `critical`
  - Evidence: event detail confirmation CTA remained callable for anonymous viewers, which allows attendance persistence outside the approved identity boundary.
  - Why now: front-only prevention is insufficient; anonymous confirmation is a canonical product/security violation and must be blocked full-stack.
  - Option A (**Recommended**): enforce identity gate in Flutter CTA flow and reject anonymous confirmation in backend attendance endpoints, both covered by regression tests.
    Effort `medium`; risk `low`; blast radius `medium`; maintenance `low`.
  - Option B: block only in Flutter. Effort `low`; risk `critical`; blast radius `high`; maintenance `high`.
  - Option C: rely only on backend rejection. Effort `low`; risk `medium`; blast radius `medium`; maintenance `medium`.

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
- `D-01.a` Invite acceptance is attendance confirmation with inviter attribution; it must obey the same identity/auth boundary as generic event confirmation.
- `D-02` Deep-link auth redirect must preserve original `/invite?code=...` context end-to-end.
- `D-03` For invalid/consumed code, final behavior follows product-approved fallback policy (currently: silent home fallback).
- `D-04` `/convites` and `/convites/compartilhar` remain authenticated tenant routes; `/invite?code=...` is preview-first (tenant-guarded, no forced login) and requires authentication only when user decides to accept/decline.
- `D-05` Android App Links for `guarappari.belluga.space` must be configured in app manifest and verified with `assetlinks.json`.
- `D-06` iOS Universal Links for `guarappari.belluga.space` must be configured via associated domains + AASA.
- `D-07` Web/infra must serve `/.well-known/assetlinks.json` and `/.well-known/apple-app-site-association` from canonical deployment path.
- `D-08` Rollout scope is guarappari-only; no other tenant hosts/package IDs are modified in this stream.
- `D-09` Test coverage is mandatory with unit + integration tests for all deterministic acceptance criteria; OS-level app-open verification remains manual evidence.
- `D-10` Event attendance confirmation is also identity-first: anonymous viewers may see public event surfaces, but cannot persist attendance; Flutter must redirect to auth and backend must reject anonymous attendance writes.
- `D-11` Invite duplicate resolution uses `status = superseded` with explicit `supersession_reason`; `suppressed` remains reserved for policy/governance closure and must not be reused for business-outcome supersession.
- `D-12` Generic authenticated event confirmation must never auto-credit a pending inviter; when pending invites exist for the same target, direct confirmation remains non-attributed and supersedes those invites with `supersession_reason = direct_confirmation`.

---

## Module Decision Baseline Snapshot
- `INV-PD-05`: native app is direct contract owner for invite actions.
- `INV-PD-06`: web exception boundary is narrow and code-bound.
- `FCX-02`: route/scope ownership must be explicit and guarded.
- `ATT-01`: attendance confirmation is backend-authoritative and identity-bound regardless of entry surface; invite acceptance is an attributed attendance confirmation.
- `INV-PD-10`: invite terminal semantics distinguish `superseded` from `suppressed`.
- `Web-to-App policy v1.2`: identity-first acceptance is canonical (anonymous-token acceptance removed).

---

## Module Decision Consistency Matrix (Planned)
| Decision | Module Reference | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `D-01` | Web-to-App policy section `3.1`, `4.0`, `4.1` | `Preserve` | policy v1.2 already enforces identity-first acceptance and removes anonymous-token acceptance. |
| `D-02` | `INV-PD-05`, `INV-PD-06`, FCX task hooks (`/invites/share/{code}/materialize` -> `/invites/{invite_id}/accept|decline`) | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` §4.4, `flutter_client_experience_module.md` §2.1 |
| `D-03` | No prior canonical decision explicitly fixes invalid-code UX | `Out of Scope` (no prior baseline) | requires explicit TODO-owned execution note + later module promotion if kept. |
| `D-04` | FCX route matrix + tenant user guard expectation | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` §2.0 |
| `D-05` | Web-to-App promotion requirement | `Preserve` | `foundation_documentation/artifacts/tmp/web_to_app_invite_handoff_2026-03-14.md` + policy alignment target |
| `D-06` | Web-to-App promotion requirement | `Preserve` | same as above; iOS inclusion explicitly approved by product |
| `D-07` | Runtime ingress serving obligations | `Preserve` | `system_architecture_principles.md` P-18 (ingress parity) |
| `D-08` | User scope directive | `Preserve` | session decision: guarappari-only |
| `D-09` | Test governance for delivery readiness | `Preserve` | session decision: maximize automated assertions (unit/integration) against acceptance criteria |
| `D-10` | `ATT-01` identity-bound attendance rule | `Supersede (Intentional)` | active TODO now freezes `attendance confirmation` wording instead of `attendance commitment` for this flow |
| `D-11` | `INV-19` invite terminal semantics | `Supersede (Intentional)` | canonical invite status vocabulary now distinguishes `superseded` (business outcome) from `suppressed` (policy closure) |
| `D-12` | `INV-19` + `ATT-01` direct confirmation semantics | `Preserve` | generic attendance confirmation remains allowed but must stay non-attributed and supersede pending invites with `direct_confirmation` |
| `D-13` | `INV-PD-05`, `INV-PD-06`, `ATT-01` share-code decision sequencing | `Preserve` | authenticated share-code entry must materialize a canonical invite edge first; accept/decline then use only `/invites/{invite_id}/accept|decline` |

---

## Implementation Tasks

### A) Policy + Backend
- [x] ✅ Update web-to-app canonical docs to supersede anonymous acceptance and require authenticated identity for share-code acceptance.
- [x] ✅ Replace share-code acceptance-by-code contract with `POST /api/v1/invites/share/{code}/materialize`, which creates/reuses the canonical invite edge without deciding acceptance.
- [x] ✅ Add backend preview endpoint `GET /api/v1/invites/share/{code}` for unauthenticated `/invite?code=...` landing context.
- [x] ✅ Add/adjust backend tests for anonymous rejection + authenticated share-code materialization success, plus canonical invite accept/decline after materialization.
- [x] ✅ Enforce backend identity-state guard on event attendance writes (`POST /events/{event_id}/attendance/confirm` and unconfirm path where applicable) with explicit regression coverage for anonymous rejection.
- [x] ✅ Replace invite duplicate-closing contract/implementation from `closed_duplicate` to `superseded` + `supersession_reason`, preserving `suppressed` for policy-only closure.
- [x] ✅ Ensure invite acceptance supersedes competing pending invites with `supersession_reason = other_invite_credited` and canonical response field `superseded_invite_ids`.
- [x] ✅ Ensure direct authenticated event confirmation supersedes pending invites for the same target with `supersession_reason = direct_confirmation` and does not set `credited_acceptance` on any invite edge.
- [x] ✅ Add backend regression coverage proving direct event confirmation with pending invites never auto-credits a pending inviter.

### B) Flutter (Route/Guard/Flow)
- [x] ✅ Update invite route guard split: keep auth guard on `/convites` and `/convites/compartilhar`, allow anonymous preview on `/invite`.
- [x] ✅ Ensure login redirect preserves full invite deep-link query context.
- [x] ✅ Ensure signup redirect also preserves and replays the original deep link query context.
- [x] ✅ Refactor invite flow resolution to deterministic state machine; no accidental route loss.
- [x] ✅ Disable automatic share-code acceptance during invite bootstrap; authenticated users must explicitly choose `Aceitar`/`Recusar` on invite UI.
- [x] ✅ Materialize authenticated share-code entry into a canonical pending invite during bootstrap before rendering decision UI.
- [x] ✅ Remove decision-time share-code fallbacks in Flutter; `Aceitar`/`Recusar` must require `invite_id` and use only canonical invite accept/decline repository calls.
- [x] ✅ Implement product-approved invalid/consumed-code behavior (`silent home fallback`).
- [x] ✅ Add/refresh unit/widget/integration tests for preview-first invite flow (anonymous CTA + login/signup redirect replay) and guarded authenticated flows.
- [x] ✅ Block anonymous event attendance confirmation in Flutter event-detail/public surfaces and redirect to login with current deep link preserved.
- [x] ✅ Ensure generic Flutter event-confirm flow never attempts implicit invite attribution or invite mutation; direct confirmation remains a backend attendance-confirm call only.

### C) Android App Links (Guarappari)
- [x] ✅ Add HTTPS App Link `intent-filter` (`autoVerify=true`) for `guarappari.belluga.space` invite routes.
- [x] ✅ Persist typed Android app identifier (`app_android`) + release SHA-256 fingerprint (`settings.app_links.android`) as canonical source for `/.well-known/assetlinks.json`.
- [x] ✅ Capture verification evidence (`adb` app-links checks).

### D) iOS Universal Links (Guarappari)
- [x] ✅ Configure associated domains entitlements for `guarappari.belluga.space`.
- [x] ✅ Persist typed iOS bundle identifier (`app_ios`) + `team_id`/`paths` (`settings.app_links.ios`) as canonical source for `/.well-known/apple-app-site-association`.
- [x] ✅ Moved iOS runtime verification evidence capture to `TODO-vnext-ios-universal-links-production-validation.md`.

### E) Infra / Web Fallback
- [x] ✅ Ensure nginx/runtime serves both `.well-known` files in production lane.
- [x] ✅ Keep web fallback preserving `code` when app open fails.

### F) Test Delivery Track
- [x] ✅ Add backend automated coverage for share-code materialization boundary (authenticated success + anonymous rejection).
- [x] ✅ Add Flutter unit tests for invite guard, deep-link parameter preservation, and deterministic code-resolution outcomes.
- [x] ✅ Add Flutter integration test for deep-link -> login -> return-to-original-link -> resolution flow.
- [x] ✅ Add Flutter regression tests proving authenticated share-code bootstrap materializes an invite before rendering decision CTA, and that accept/decline no longer call share-code endpoints at decision time.
- [x] ✅ Add backend feature tests and Flutter controller/widget tests proving anonymous users cannot confirm attendance and authenticated users still can.
- [x] ✅ Add backend/package regression tests proving invite terminal states use `superseded` with explicit reason and never overload `suppressed`.
- [x] ✅ Add backend/package regression tests for both canonical `supersession_reason` values: `other_invite_credited` and `direct_confirmation`.
- [x] ✅ Add backend/package regression tests proving direct event confirmation with pending invites creates no implicit credited invite.
- [x] ✅ Add Flutter controller/widget tests proving generic event confirmation does not invoke invite-accept/share-accept paths.
- [x] ✅ Add Flutter integration regression test for anonymous event confirm -> signup -> authenticated confirm, asserting a single immersive route instance (back returns to agenda, no duplicate event stack).
- [x] ✅ Add Playwright readonly smoke coverage for `/manifest.json` + `/.well-known/*` contracts on landlord/tenant URLs (edge/runtime path, non-HTML fallback).
- [x] ✅ Capture manual evidence for Android installed-app open behavior and fallback when app is not installed.
- [x] ✅ Moved iOS manual evidence capture to `TODO-vnext-ios-universal-links-production-validation.md`.

---

## Acceptance Criteria
- [x] ✅ Anonymous identity cannot materialize or accept a share-code invite; authenticated user can materialize the canonical pending invite and then decide through standard invite accept/decline flows.
- [x] ✅ `/invite?code=...` no longer forces immediate login; it renders invite-first UI with CTA for authentication.
- [x] ✅ Invite UI action contract: authenticated users see `Recusar` + `Aceitar` with swipe affordance; unauthenticated users see only `Entre para Aceitar ou Recusar`.
- [x] ✅ Login flow round-trips back to original deep link with `code` preserved.
- [x] ✅ Signup flow also round-trips back to original deep link with `code` preserved.
- [x] ✅ Authenticated `/invite?code=...` no longer auto-accepts or jumps to Home; it keeps explicit invite decision UI (`Recusar`/`Aceitar`).
- [x] ✅ Authenticated `/invite?code=...` materializes the canonical invite edge before showing decision UI; decision-time logic no longer depends on share-code accept or local-only decline dismissal.
- [x] ✅ Invite deep-link resolution no longer drops context unpredictably.
- [x] ✅ Anonymous viewer cannot persist event attendance from public/invite-derived event surfaces; authenticated user can, with login redirect preserving current deep link when needed.
- [x] ✅ Invite terminal semantics are explicit: `superseded` covers business-outcome loss with reason metadata, while `suppressed` remains reserved for policy/governance closure; `closed_duplicate` no longer appears in canonical contracts.
- [x] ✅ Invite acceptance supersedes competing invites with `supersession_reason = other_invite_credited`.
- [x] ✅ Direct authenticated event confirmation with pending invites supersedes those invites with `supersession_reason = direct_confirmation` and does not implicitly credit any inviter.
- [x] ✅ Android App Link opens native app for guarappari when installed.
- [x] ✅ iOS Universal Link runtime validation moved to `TODO-vnext-ios-universal-links-production-validation.md`.
- [x] ✅ `.well-known/assetlinks.json` and `.well-known/apple-app-site-association` are deployable via canonical infra path.

---

## Test Strategy Decision (Frozen Before Implementation)
- Automated tests are required to cover acceptance criteria whenever behavior is deterministic inside backend/app boundaries.
- Mandatory automated suites for this stream:
  - Backend feature/integration: authenticated acceptance success and anonymous acceptance rejection.
  - Backend feature/integration: authenticated attendance confirmation success and anonymous attendance rejection.
  - Backend feature/integration: invite acceptance supersedes competing invites with `other_invite_credited`.
  - Backend feature/integration: direct event confirmation with pending invites supersedes them with `direct_confirmation` and produces no implicit credited invite.
  - Flutter unit/widget: guard/auth redirect contract, deep-link `code` preservation, and deterministic resolution outcomes.
  - Flutter unit/widget: anonymous attendance-confirm CTA redirects to auth and does not persist attendance.
  - Flutter unit/widget: generic event confirmation does not invoke invite acceptance paths.
  - Flutter integration (`integration_test`): end-to-end deep-link auth round-trip inside app runtime.
- Manual-only criteria (cannot be reliably asserted in CI due to OS/domain-association dependencies):
  - Android App Link opening native app when installed.
  - iOS Universal Link opening native app when installed.
  - Browser fallback behavior when app is absent, preserving `code`.
- Delivery is blocked if automated suites are missing for deterministic criteria.

---

## Validation Steps
- [x] ✅ Backend feature/integration tests: share materialize authenticated success + anonymous rejection.
- [x] ✅ Flutter unit/widget tests: invite guard/auth redirect + code handling outcomes.
- [x] ✅ Flutter integration tests: deep-link auth round-trip + share-code bootstrap preview/explicit-decision paths (`-d flutter-tester`).
- [x] ✅ `fvm flutter analyze`.
- [x] ✅ `fvm dart run custom_lint`.
- [x] ✅ Android manual: browser click on `/invite?code=...` with app installed and not installed.
- [x] ✅ iOS manual runtime validation moved to `TODO-vnext-ios-universal-links-production-validation.md`.
- [x] ✅ Web fallback: code preserved through login handoff (`AuthRouteGuard` + auth-login round-trip integration test).
- [x] ✅ Backend feature tests: anonymous attendance confirm rejected; authenticated confirm still succeeds.
- [x] ✅ Flutter tests: anonymous event-detail confirm redirects to login and does not call persistence path.
- [x] ✅ Backend/package tests: invite acceptance returns `superseded_invite_ids` and persists `supersession_reason = other_invite_credited` on competing invites.
- [x] ✅ Backend/package tests: direct event confirmation with pending invites persists `supersession_reason = direct_confirmation` and leaves all invite edges uncredited.
- [x] ✅ Flutter tests: generic event confirmation does not call invite accept/share-accept paths even when invite data exists in memory.
- [x] ✅ Flutter integration tests: event confirm auth round-trip via signup keeps a single event-detail route (one back returns to `/agenda`) and preserves confirmed state.
- [x] ✅ Playwright readonly smoke: landlord/tenant `/.well-known/assetlinks.json`, `/.well-known/apple-app-site-association`, and `/manifest.json` return JSON contract payloads (no SPA HTML fallback) through runtime URLs.

---

## Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`, `laravel-app/tests/Feature/Invites/InvitesFlowTest.php` | Anonymous identity rejection + authenticated share-code materialization success validated in backend test suite. |
| `D-02` | `Adherent` | `flutter-app/lib/application/router/guards/auth_route_guard.dart`, `flutter-app/test/application/router/guards/auth_route_guard_test.dart`, `flutter-app/integration_test/feature_invite_deeplink_auth_roundtrip_test.dart` | Redirect path normalization + query preservation asserted in unit/integration tests. |
| `D-03` | `Adherent` | `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart` | Product-approved silent-home fallback remains deterministic when invite list is empty. |
| `D-04` | `Adherent` | `flutter-app/lib/application/router/modular_app/modules/invites_module.dart`, `flutter-app/test/application/router/modules/invites_module_test.dart`, `flutter-app/test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart` | `/invite` is tenant-guarded preview-first; `/convites` and `/convites/compartilhar` remain tenant+auth guarded. |
| `D-05` | `Adherent` | `flutter-app/android/app/src/main/AndroidManifest.xml`, `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`, `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`, `flutter-app/test/platform/deep_link_platform_config_test.dart`, `foundation_documentation/artifacts/tmp/android_app_links_validation_2026-03-19.md` | App Links wiring + endpoint payload contracts + manual ADB validation are complete (`get-app-links` verified, installed-app deep link opens `MainActivity`, non-installed deep link falls back to browser while preserving URL). |
| `D-06` | `Deferred (VNext)` | `flutter-app/ios/Runner/Runner.entitlements`, `flutter-app/ios/Runner.xcodeproj/project.pbxproj`, `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`, `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`, `flutter-app/test/platform/deep_link_platform_config_test.dart`, `foundation_documentation/todos/active/vnext_slices/TODO-vnext-ios-universal-links-production-validation.md` | Universal Links wiring + payload source + regression tests are complete in code; runtime/manual iOS validation is explicitly tracked in VNext. |
| `D-07` | `Adherent` | `docker/nginx/prod.conf.template`, `docker/nginx/local.conf.template`, `laravel-app/routes/web.php`, `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`, `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`, `laravel-app/tests/Api/v1/Admin/ApiV1WellKnownAssociationAdminTest.php`, `flutter-app/test/platform/deep_link_platform_config_test.dart`, `tools/flutter/web_app_tests/deeplink_contract.spec.js` | Canonical runtime serving path is endpoint-based (no public static shadow files), host-resolved, and regression-tested for non-HTML payloads in both unit/feature and edge-level Playwright smoke layers. |
| `D-08` | `Adherent` | `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`, `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_app_links_section.dart` | Scope remains guarappari-only in configured credentials/paths (`com.guarappari.app`, guarappari invite paths/domains). |
| `D-09` | `Adherent` | `laravel-app/tests/Feature/Invites/InvitesFlowTest.php`, `flutter-app/test/application/router/**`, `flutter-app/test/presentation/tenant/invites/screens/invite_flow_screen/**`, `flutter-app/test/platform/deep_link_platform_config_test.dart`, `flutter-app/integration_test/feature_invite_deeplink_auth_roundtrip_test.dart`, `flutter-app/integration_test/feature_invite_flow_share_code_bootstrap_test.dart` | Automated backend/unit/widget/integration coverage executed successfully, including regression for preview-first auth round-trip without auto-accept/home fallback; only OS-level manual checks remain pending. |
| `D-10` | `Adherent` | `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`, `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/**`, `flutter-app/integration_test/feature_event_confirm_signup_backstack_regression_test.dart`, `laravel-app/tests/Feature/Events/EventAttendanceControllerTest.php` | Anonymous attendance writes are blocked in backend and Flutter, with regression coverage for redirect/no-write behavior and signup round-trip stack integrity. |
| `D-11` | `Adherent` | `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/endpoints_mvp_contracts.md`, `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`, `laravel-app/tests/Feature/Invites/InvitesFlowTest.php` | Package, API payloads, docs, and tests are aligned on `superseded` + `supersession_reason`; `closed_duplicate` no longer appears in canonical contracts. |
| `D-12` | `Adherent` | `foundation_documentation/modules/invite_and_social_loop_module.md`, `laravel-app/app/Application/Events/AttendanceCommitmentService.php`, `laravel-app/tests/Feature/Events/EventAttendanceControllerTest.php`, `laravel-app/tests/Feature/Invites/InvitesFlowTest.php`, `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/**` | Direct confirmation remains non-attributed, supersedes pending invites with `direct_confirmation`, and generic Flutter confirm never calls invite accept/share-accept paths. |

## Execution Evidence (2026-03-19)
- Android App Links verification evidence captured in [android_app_links_validation_2026-03-19.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/android_app_links_validation_2026-03-19.md) (`com.guarappari.app`, domain `guarappari.belluga.space` verified, installed-app open -> `MainActivity`, non-installed fallback -> Chrome).
- WSL-safe device integration attempts for invite-critical files remained `blocked` by harness transport instability (`WebSocketChannelException: Connection closed before full header was received`) before product assertions:
  - `foundation_documentation/artifacts/tmp/flutter-device-runner/feature_invite_deeplink_auth_roundtrip_test_20260319_071918.expanded.log`
  - `foundation_documentation/artifacts/tmp/flutter-device-runner/feature_invite_flow_share_code_bootstrap_test_20260319_072357.expanded.log`

## Module Decision Consistency Validation
| Decision | Delivery Status | Evidence | Notes |
| --- | --- | --- | --- |
| `INV-PD-05` | `Preserved` | `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php` + `InvitesFlowTest` | Native/app conversion ownership preserved; web acceptance remains narrow and policy-bounded. |
| `INV-PD-06` | `Preserved` | `flutter-app/lib/application/router/guards/auth_route_guard.dart`, `integration_test/feature_invite_deeplink_auth_roundtrip_test.dart` | Deep-link continuity preserved through auth redirect with `code` round-trip. |
| `FCX-02` | `Preserved` | `flutter-app/lib/application/router/modular_app/modules/invites_module.dart` + route guard tests | Route ownership explicit and guard-enforced for invite entry/share flows. |
| `ATT-01` | `Preserved` | `foundation_documentation/modules/invite_and_social_loop_module.md`, `flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/**`, `laravel-app/tests/Feature/Events/EventAttendanceControllerTest.php` | Attendance confirmation is backend-authoritative and identity-bound across invite-derived and generic public surfaces. |
| `INV-PD-10` | `Preserved` | `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/endpoints_mvp_contracts.md`, `laravel-app/tests/Feature/Invites/InvitesFlowTest.php` | Supersession semantics are canonical in docs and implemented in package/API/test layers. |
| `Web-to-App policy (v1.1 -> v1.2)` | `Superseded (Approved)` | `foundation_documentation/policies/web_to_app_promotion_policy.md` (v1.2) | Anonymous acceptance removed; identity-first acceptance is canonical. |
