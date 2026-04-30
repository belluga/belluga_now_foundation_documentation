# Title
Store Release Phone OTP Auth And Contact Match

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Upstream Baseline Note
The generic landlord/tenant auth-method governance baseline is now delivered in `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`. This downstream Belluga release TODO still requires verified phone identity for contact matching, but it must now consume that frozen generic contract instead of redefining platform auth policy.

## Context
The release-critical friends/invites loop depends on deterministic contact matching. The current Belluga release direction is to use phone-based contact hashes so imported address books can resolve existing users without storing raw contact data. That only becomes effective if the resolved tenant-public authenticated identity for the release tenant is anchored on a verified phone number. The current codebase still exposes tenant-public email/password auth in Flutter and Laravel, while the invite flow already assumes app-owned anonymous identity and later authenticated upgrade/merge. The generic Laravel baseline, however, must remain capable of multiple authentication methods under landlord/tenant governance rather than collapsing into a Belluga-only rule.
This TODO also absorbs the former standalone auth-entry polish slice: the MVP no longer needs separate sign-in/sign-up screens, but the replacement phone-entry and OTP verification surfaces must still ship with clear hierarchy, readable validation and backend-error feedback, explicit loading states, and keyboard-safe/mobile-safe layout behavior.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this is one bounded publication-critical contract slice: define the MVP tenant-public identity baseline that makes contact-hash matching, invite attribution preservation, and auth-wall progression coherent.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the business need and the product direction are explicit; the immediate task is to freeze the contract and dependencies, not to run broader discovery.

## Contract Boundary
- This TODO defines the Belluga tenant-public identity contract for phone-based authentication and contact matching after generic auth-method governance is in place.
- It preserves the existing anonymous-first app flow and only changes the authenticated upgrade path.
- It depends on upstream landlord/tenant auth-method governance and therefore must not redefine generic platform capability rules inside this artifact.
- It does not authorize authenticated web expansion, QR login, or broad connections-platform rollout.
- If execution broadens into generic social graph, workspace analytics, or web-authenticated scope, stop and split that work into fast-follow or VNext lanes.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated-Production-Error-Handling-Closed`
- **Qualifiers:** `Business-Core`, `Cross-Stack`, `Release-Critical`, `Upstream-Baseline-Ready`, `Product-UX-Gap-Closed`, `Browser-Runtime-Validated`, `External-Provider-Readiness-Open`, `Production-Error-Sanitization-Closed`, `ADB-Visual-Stepper-Validated`
- **Next exact step:** commit/push the local checkpoint, then run the promotion-lane audit and manual live-provider checks without reopening OTP product decisions unless new runtime evidence contradicts the closed production-error guard.

## Upstream Baseline Status
- Upstream baseline: `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
- Current state: the generic auth-method governance contract is merged to `dev` and no longer blocks this TODO's local planning or execution.
- Consumption rule: Belluga-specific OTP work must now consume the frozen generic settings/runtime contract instead of reopening platform-level auth-governance decisions.

## Dependencies & Sequencing
- [x] `DEP-00` `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md` is now satisfied as the frozen upstream baseline for Belluga-specific OTP execution.

## Scope
- [x] Replace Belluga tenant-public release behavior from legacy email/password entry to effective `phone-first OTP`.
- [x] Own the former auth-entry polish scope inside the new phone-entry and OTP verification screens instead of tracking visual quality in a separate sign-in/sign-up TODO.
- [x] Keep `POST /api/v1/anonymous/identities` and the anonymous-first invite conversion flow as the pre-auth foundation.
- [x] Define verified phone as the canonical tenant-public identity anchor used for contact-directory matching.
- [x] Define OTP delivery as backend-dispatched, provider-agnostic on the client, with WhatsApp as the preferred send channel and SMS as fallback.
- [x] Model tenant-admin OTP delivery settings as one primary WhatsApp webhook plus one optional SMS secondary channel; do not expose a generic `Webhook OTP`, a `Use WhatsApp webhook for OTP` switch, or an `OTP Channel` dropdown in the release UI.
- [x] Let tenant-public OTP challenge default to WhatsApp and expose SMS only as an explicit secondary "other ways" send option when SMS is configured.
- [x] Replace the legacy login-derived phone/OTP UI with a release-grade OTP flow: country picker, country-aware phone mask/validation, separated OTP character boxes, paste-to-fill behavior, resend/cooldown states, and clear channel affordances.
- [x] Define backend-owned phone normalization plus hardened contact-hash materialization for imported contacts and verified users.
- [x] Define anonymous-to-authenticated merge requirements so invite attribution/history survives phone verification.
- [x] Remove Belluga tenant-public release dependence on email/password and keep email/social login disabled for Belluga store-release behavior.
- [x] Keep landlord/admin authentication out of scope and unchanged.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<planned>`, `flutter-app:<planned>`, `laravel-app:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Contract freeze + documentation alignment | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Backend phone OTP + merge + dispatcher integration | `local` | `pending` | `pending` | `pending` | `Local-Implemented; ADB/provider-live smoke deferred; legacy password routes quarantined by effective auth-method middleware` |
| Flutter auth cutover to phone OTP | `local` | `pending` | `pending` | `pending` | `Local-Implemented; release-grade country/masked phone entry, segmented OTP entry, and secondary SMS channel affordance covered by focused Flutter tests and Android device evidence` |
| Contact-hash hardening + invite/history preservation validation | `local` | `pending` | `pending` | `pending` | `Local-Implemented; triple audit accepted non-blocking final-lane debt` |
| Tenant-admin outbound webhook configuration surface | `local` | `pending` | `pending` | `pending` | `Local-Validated; simplified WhatsApp webhook + optional SMS secondary URL covered by focused tests and runtime Playwright mutation shard` |

## Out of Scope
- [ ] Landlord/admin authentication changes.
- [ ] Authenticated web, QR login, or workspace-web session bridging.
- [ ] Generic landlord/tenant auth-method governance; that upstream baseline is owned by `TODO-store-release-landlord-tenant-auth-method-governance.md`.
- [ ] Enabling email login, social login, or MFA in Belluga release behavior beyond the single phone OTP challenge.
- [ ] Broad `belluga_connections` package rollout beyond the minimal release-critical contact/friend dependency.
- [ ] Replacing the anonymous-first invite acceptance path with forced auth-before-preview behavior.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** tenant-public auth contract updates, backend/Flutter phone OTP implementation, contact-hash hardening, invite merge preservation, and release-critical doc/test updates that remain inside this one auth objective.
- **Must update or split the TODO:** authenticated web/QR login, generic people discovery, broader connections/package scope, or landlord/admin auth redesign.

## Definition of Done
- [x] Belluga tenant-public MVP identity baseline resolves through effective `phone-first OTP` configuration.
- [x] Phone-entry and OTP verification screens absorb the former auth-entry polish baseline: clear CTA hierarchy, readable validation/backend-error states, explicit in-flight feedback, and keyboard-safe/mobile-safe layout.
- [x] Tenant-admin technical integrations expose the release product model for outbound OTP only: `Webhook WhatsApp`, `Secondary OTP Channel com SMS`, and conditional `URL SMS`.
- [x] OTP secondary-channel behavior is explicit end-to-end: WhatsApp is the default challenge channel, SMS is available only when configured, and public UI surfaces `Outras formas` only for that configured fallback.
- [x] Playwright source-owned web evidence covers every web-visible/admin-critical row in the matrix below; Android-only OTP UI closure remains ADB/device evidence in the final consolidated device phase.
- [x] Anonymous-first invite conversion remains preserved and its merge-to-authenticated behavior is explicit.
- [x] Contact matching depends on normalized verified phone identity with backend-owned hardened hash materialization rather than raw phone storage in matching flows.
- [x] Belluga tenant-public Flutter/Laravel release behavior no longer depends on email/password.
- [x] Web promotion-only/auth-boundary rules remain unchanged and are explicitly preserved.

## Validation Steps
- [x] TODO is linked from `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`.
- [x] Dependency edge to `TODO-store-release-minimal-friends-and-favorites-mvp.md` is explicit.
- [x] Upstream auth-governance baseline is delivered in `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md` and exposes the effective Belluga tenant-public auth-method contract.
- [x] Backend feature tests cover OTP challenge, OTP verify, cooldown/TTL/rate-limit behavior, anonymous-to-authenticated merge, and contact-hash matching after verification.
- [x] Flutter tests cover phone-entry -> OTP verify -> authenticated state transition, auth-wall continuation, and anonymous invite conversion continuity.
- [x] Flutter tests plus applicable browser/device runtime evidence cover the redesigned phone-entry/OTP validation errors, backend-error readability, loading/disabled CTA behavior, keyboard-safe small-width layout, country mask behavior, separated OTP boxes, paste-to-fill, resend/cooldown state, and secondary SMS affordance.
- [x] Legacy Belluga tenant-public email/password routes/UI/tests are either removed or explicitly quarantined from store-release behavior.
- [x] Tenant-admin technical integrations expose simplified `outbound_integrations` WhatsApp/SMS OTP settings and save/read back the derived backend settings namespace without leaking legacy `Webhook OTP`, `Use WhatsApp webhook for OTP`, or `OTP Channel` controls.
- [x] Playwright matrix in this TODO is implemented under `tools/flutter/web_app_tests/**`, executed only through `tools/flutter/run_web_navigation_smoke.sh readonly|mutation`, and recorded as browser-stage evidence after the current web bundle is published.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Replace Belluga tenant-public release behavior from legacy email/password entry to effective `phone-first OTP`. | Flutter implementation and tests | `lib/presentation/shared/auth/screens/auth_login_screen/auth_login_screen.dart`; `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` | Local Flutter widget/runtime; Android device smoke `192.168.15.9:5555` | passed | Tenant-public auth renders `AuthPhoneOtpExperience`; landlord/admin auth remains on the legacy scaffold. |
| `SCOPE-02` | Scope | Own the former auth-entry polish scope inside the new phone-entry and OTP verification screens instead of tracking visual quality in a separate sign-in/sign-up TODO. | Flutter implementation and visual decision note | `lib/presentation/shared/auth/screens/auth_login_screen/widgets/auth_phone_otp_experience.dart`; selected Stitch direction recorded in Reopened Product/UX Closure Notes | Local Flutter widget/runtime; Android device smoke `192.168.15.9:5555` | passed | The release screen uses a new stepper/panel OTP structure, not the previous login page composition. |
| `SCOPE-03` | Scope | Keep `POST /api/v1/anonymous/identities` and the anonymous-first invite conversion flow as the pre-auth foundation. | Backend/Flutter contract evidence | `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md`; Wave 2 social TODO guard-clean evidence | Local Laravel/Flutter tests and Android invite/friends dependency lane | passed | OTP upgrade preserves the anonymous identity foundation consumed by invites and social matching. |
| `SCOPE-04` | Scope | Define verified phone as the canonical tenant-public identity anchor used for contact-directory matching. | Backend contract and docs | `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md`; module docs updated for phone-hash materialization | Local Laravel feature tests | passed | Verified phone normalization and backend-owned matching materialization remain backend responsibilities. |
| `SCOPE-05` | Scope | Define OTP delivery as backend-dispatched, provider-agnostic on the client, with WhatsApp as the preferred send channel and SMS as fallback. | Backend queued dispatch and Flutter contract | Laravel OTP queue/webhook tests in `T2-phone-otp-auth-contact-match-review-packet.md`; Flutter repository/controller tests | Local Laravel queue tests; Local Flutter tests | passed | Flutter sends channel intent only; queued backend job owns provider/webhook dispatch. |
| `SCOPE-06` | Scope | Model tenant-admin OTP delivery settings as one primary WhatsApp webhook plus one optional SMS secondary channel; do not expose a generic `Webhook OTP`, a `Use WhatsApp webhook for OTP` switch, or an `OTP Channel` dropdown in the release UI. | Flutter admin UI and runtime Playwright | `test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`; `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js`; `scripts/build_web.sh ../web-app dev`; `NAV_WEB_SHARD=otp-auth ... tools/flutter/run_web_navigation_smoke.sh mutation` | Local Flutter widget tests; Playwright mutation runtime; refreshed `../web-app` bundle | passed | Admin surface exposes `Webhook WhatsApp`, `Secondary OTP Channel com SMS`, and conditional `URL SMS`; runtime mutation shard passed on 2026-04-30. |
| `SCOPE-07` | Scope | Let tenant-public OTP challenge default to WhatsApp and expose SMS only as an explicit secondary "other ways" send option when SMS is configured. | Flutter controller/UI tests | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` | Local Flutter widget/controller tests; Android device evidence | passed | Default request is WhatsApp; SMS is hidden behind `Outras formas` only when SMS fallback exists; tenant-public web remains a promotion boundary. |
| `SCOPE-08` | Scope | Replace the legacy login-derived phone/OTP UI with a release-grade OTP flow: country picker, country-aware phone mask/validation, separated OTP character boxes, paste-to-fill behavior, resend/cooldown states, and clear channel affordances. | Flutter implementation and focused tests | `AuthPhoneOtpExperience`; `AuthPhoneOtpForm`; `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` | Local Flutter widget tests; Android device smoke `192.168.15.9:5555` | passed | `phone_form_field` and `pinput` cover country phone input and segmented code entry; test verifies full six-digit fill. |
| `SCOPE-09` | Scope | Define backend-owned phone normalization plus hardened contact-hash materialization for imported contacts and verified users. | Backend contract and tests | `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md`; Laravel OTP/contact tests listed there | Local Laravel feature tests | passed | Matching flow does not rely on raw phone storage in Flutter. |
| `SCOPE-10` | Scope | Define anonymous-to-authenticated merge requirements so invite attribution/history survives phone verification. | Backend merge tests and module docs | `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md`; invite/social Wave 2 guard-clean TODO | Local Laravel/Flutter tests | passed | Anonymous identity merge remains explicit in OTP verify and invite continuity evidence. |
| `SCOPE-11` | Scope | Remove Belluga tenant-public release dependence on email/password and keep email/social login disabled for Belluga store-release behavior. | Flutter UI quarantine and Laravel route quarantine | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`; Laravel password-route quarantine tests in Local Delivery Notes | Local Flutter tests; Local Laravel tests | passed | Tenant-public release UI has no email/password fields; legacy password routes are gated by effective auth method. |
| `SCOPE-12` | Scope | Keep landlord/admin authentication out of scope and unchanged. | Flutter route/layout split | `lib/presentation/shared/auth/screens/auth_login_screen/auth_login_screen.dart`; focused auth login tests | Local Flutter widget tests | passed | Landlord/admin branch still renders the original auth scaffold. |
| `DOD-01` | Definition of Done | Belluga tenant-public MVP identity baseline resolves through effective `phone-first OTP` configuration. | Flutter/Laravel contract tests | `T2-phone-otp-auth-contact-match-review-packet.md`; `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` | Local Laravel/Flutter tests; Android device smoke `192.168.15.9:5555` | passed | Effective Belluga tenant-public behavior is phone OTP. |
| `DOD-02` | Definition of Done | Phone-entry and OTP verification screens absorb the former auth-entry polish baseline: clear CTA hierarchy, readable validation/backend-error states, explicit in-flight feedback, and keyboard-safe/mobile-safe layout. | Flutter implementation, widget tests, analyzer, ADB smoke | `AuthPhoneOtpExperience`; `ButtonLoading`; `fvm dart analyze --format machine`; direct `fvm flutter drive ... feature_auth_login_navigates_to_intended_route_test.dart -d 192.168.15.9:5555` | Local Flutter widget tests; Android device `192.168.15.9:5555` | passed | New structure has explicit primary CTA, error container, disabled/loading button behavior, scroll/keyboard-safe layout, and no legacy login page scaffold. |
| `DOD-03` | Definition of Done | Tenant-admin technical integrations expose the release product model for outbound OTP only: `Webhook WhatsApp`, `Secondary OTP Channel com SMS`, and conditional `URL SMS`. | Flutter admin tests and runtime Playwright | `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`; `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js`; `NAV_WEB_SHARD=otp-auth ... tools/flutter/run_web_navigation_smoke.sh mutation` | Local Flutter tests; Playwright mutation runtime; refreshed `../web-app` bundle | passed | Legacy controls are absent and the admin mutation shard passed after the Firebase/Push endpoint path fix. |
| `DOD-04` | Definition of Done | OTP secondary-channel behavior is explicit end-to-end: WhatsApp is the default challenge channel, SMS is available only when configured, and public UI surfaces `Outras formas` only for that configured fallback. | Flutter controller/UI tests and backend contract tests | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`; Laravel OTP auth/webhook tests listed below | Local Flutter tests; Local Laravel tests; Android device evidence | passed | Tests cover WhatsApp default, hidden SMS label before popup, explicit SMS challenge, and missing-SMS rejection; tenant-public web remains a promotion boundary. |
| `DOD-05` | Definition of Done | Playwright source-owned web evidence covers every web-visible/admin-critical row in the matrix below; Android-only OTP UI closure remains ADB/device evidence in the final consolidated device phase. | Playwright runtime validation | `node --check tools/flutter/web_app_tests/otp_auth_public.spec.js`; `node --check tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js`; `bash scripts/build_web.sh ../web-app dev`; `NAV_WEB_SHARD=otp-auth ... tools/flutter/run_web_navigation_smoke.sh mutation`; `tools/flutter/run_web_navigation_smoke.sh readonly` | Playwright source/shard/runtime; refreshed `../web-app` bundle; Android device `192.168.15.9:5555` | passed | Runtime credentials were supplied; mutation shard passed with `1 passed (20.7s)` and readonly rerun passed with `10 passed (3.0m)` on 2026-04-30. |
| `DOD-06` | Definition of Done | Anonymous-first invite conversion remains preserved and its merge-to-authenticated behavior is explicit. | Backend/Flutter tests and Wave 2 social guard | `T2-phone-otp-auth-contact-match-review-packet.md`; `TODO-store-release-minimal-friends-and-favorites-mvp.md` guard-clean evidence | Local Laravel/Flutter tests; Android invite/friends dependency lane | passed | This TODO consumes the guard-clean social dependency without reopening zero-backward-compatibility scope. |
| `DOD-07` | Definition of Done | Contact matching depends on normalized verified phone identity with backend-owned hardened hash materialization rather than raw phone storage in matching flows. | Backend contract and module docs | `T2-phone-otp-auth-contact-match-review-packet.md`; invite/social module docs | Local Laravel feature tests | passed | Contact matching remains backend-owned and phone-hash based. |
| `DOD-08` | Definition of Done | Belluga tenant-public Flutter/Laravel release behavior no longer depends on email/password. | Flutter UI tests and Laravel route quarantine | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`; Laravel quarantine tests in Local Delivery Notes | Local Flutter/Laravel tests | passed | Tenant-public release auth has no password entry and legacy password APIs are gated. |
| `DOD-09` | Definition of Done | Web promotion-only/auth-boundary rules remain unchanged and are explicitly preserved. | Playwright readonly source and route guard tests | `tools/flutter/web_app_tests/otp_auth_public.spec.js` `@readonly OTP-WEB-BOUNDARY-01`; `npx playwright test --grep '@readonly.*OTP-WEB-BOUNDARY' --list`; `scripts/build_web.sh ../web-app dev` | Playwright readonly source/list; refreshed `../web-app` bundle | passed | `/auth/login` web boundary remains app promotion/handoff and does not expose OTP. |
| `VAL-01` | Validation Steps | TODO is linked from `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`. | Documentation link audit | Store-release parent TODO reference plus this TODO | Foundation docs | passed | Parent linkage preserved. |
| `VAL-02` | Validation Steps | Dependency edge to `TODO-store-release-minimal-friends-and-favorites-mvp.md` is explicit. | Documentation dependency audit | References and Dependencies sections in this TODO | Foundation docs | passed | T3 social dependency is explicit and guard-clean in promotion lane. |
| `VAL-03` | Validation Steps | Upstream auth-governance baseline is delivered in `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md` and exposes the effective Belluga tenant-public auth-method contract. | Documentation and completed TODO evidence | Completed upstream TODO plus Local Delivery Notes | Foundation docs | passed | Belluga OTP consumes the frozen generic auth-method governance baseline. |
| `VAL-04` | Validation Steps | Backend feature tests cover OTP challenge, OTP verify, cooldown/TTL/rate-limit behavior, anonymous-to-authenticated merge, and contact-hash matching after verification. | Laravel feature tests | Laravel test commands recorded in `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md` | Local Laravel Docker/test DB | passed | Prior T2 backend packet remains the source of record for this local gate. |
| `VAL-05` | Validation Steps | Flutter tests cover phone-entry -> OTP verify -> authenticated state transition, auth-wall continuation, and anonymous invite conversion continuity. | Flutter tests | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart test/infrastructure/repositories/auth_repository_signup_test.dart` | Local Flutter tests | passed | Focused controller/repository coverage plus Wave 2 invite continuity evidence. |
| `VAL-06` | Validation Steps | Flutter tests plus applicable browser/device runtime evidence cover the redesigned phone-entry/OTP validation errors, backend-error readability, loading/disabled CTA behavior, keyboard-safe small-width layout, country mask behavior, separated OTP boxes, paste-to-fill, resend/cooldown state, and secondary SMS affordance. | Flutter tests, analyzer, web build, Playwright runtime, ADB smoke | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`; `fvm dart analyze --format machine`; `scripts/build_web.sh ../web-app dev`; `tools/flutter/web_app_tests/otp_auth_public.spec.js`; `tools/flutter/run_web_navigation_smoke.sh readonly`; direct `fvm flutter drive ... feature_auth_login_navigates_to_intended_route_test.dart -d 192.168.15.9:5555` | Local Flutter tests; browser Playwright runtime boundary; refreshed `../web-app` bundle; Android device `192.168.15.9:5555` | passed | Widget coverage includes country-aware phone field, segmented OTP, full six-digit fill, backend error surface, disabled/loading CTA, and SMS fallback affordance; tenant-public web remains a promotion boundary. |
| `VAL-07` | Validation Steps | Legacy Belluga tenant-public email/password routes/UI/tests are either removed or explicitly quarantined from store-release behavior. | Flutter UI tests, Playwright boundary, Laravel route quarantine | `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart`; `tools/flutter/web_app_tests/otp_auth_public.spec.js` `@readonly OTP-WEB-BOUNDARY-01`; direct ADB `fvm flutter drive ... feature_auth_login_navigates_to_intended_route_test.dart -d 192.168.15.9:5555`; Laravel quarantine evidence in Local Delivery Notes | Local Flutter/Laravel tests; browser Playwright source/list; Android device `192.168.15.9:5555` | passed | Tenant-public UI has no email/password fields; backend password routes reject under Belluga phone OTP config. |
| `VAL-08` | Validation Steps | Tenant-admin technical integrations expose simplified `outbound_integrations` WhatsApp/SMS OTP settings and save/read back the derived backend settings namespace without leaking legacy `Webhook OTP`, `Use WhatsApp webhook for OTP`, or `OTP Channel` controls. | Flutter admin repository/widget tests and Playwright mutation runtime | `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart`; `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js`; `scripts/build_web.sh ../web-app dev`; `NAV_WEB_SHARD=otp-auth ... tools/flutter/run_web_navigation_smoke.sh mutation` | Local Flutter tests; Playwright mutation runtime; refreshed `../web-app` bundle | passed | Repository payload keeps compatibility fields derived from the product model while rendered UI hides legacy controls; runtime mutation passed after endpoint alignment. |
| `VAL-09` | Validation Steps | Playwright matrix in this TODO is implemented under `tools/flutter/web_app_tests/**`, executed only through `tools/flutter/run_web_navigation_smoke.sh readonly mutation`, and recorded as browser-stage evidence after the current web bundle is published. | Playwright runtime validation | `node --check` for OTP specs; shard validation; `scripts/build_web.sh ../web-app dev`; `NAV_WEB_SHARD=otp-auth ... tools/flutter/run_web_navigation_smoke.sh mutation`; `tools/flutter/run_web_navigation_smoke.sh readonly` | Playwright source/shard/runtime; refreshed `../web-app` bundle | passed | Runtime credentials were supplied on 2026-04-30; mutation passed with `1 passed (20.7s)` and readonly passed with `10 passed (3.0m)` against `https://guarappari.belluga.space`. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| WhatsApp/SMS OTP delivery provider | OTP dispatch cannot ship without an approved outbound provider path and fallback channel behavior. | `degraded` | `2026-04-29` | `local contract/tests only` | Provider path is frozen as queued outbound webhooks configured in tenant admin. Live receiving URLs/provider/template approval remain release-readiness inputs and are not proven by local tests. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`, `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** this is a cross-stack public auth contract change that affects backend endpoints, Flutter repositories/routes/screens, onboarding semantics, invite attribution preservation, contact matching, and release risk.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` tenant-public authorization requirements + API endpoint definitions
  - `onboarding_flow_module.md` entry path + partial identity capture semantics
  - `invite_and_social_loop_module.md` Sanctum + identity requirement and `/contacts/import` contract language
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Domain Rules` + `2.2 API Endpoint Definitions`
  - `foundation_documentation/modules/onboarding_flow_module.md` section `2. Entry Paths`
  - `foundation_documentation/modules/invite_and_social_loop_module.md` section `4 APIs & Events` + `Sanctum + Identity Requirement`

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` After upstream auth-method governance closes, Belluga tenant-public MVP authenticated identity baseline resolves through effective `phone-first OTP`; email/password and social login remain disabled for Belluga store-release behavior.
- [x] `D-02` OTP delivery is backend-dispatched and Flutter remains provider-agnostic; WhatsApp is the preferred channel and SMS is the fallback channel.
- [x] `D-03` The app keeps the existing anonymous-first invite flow and upgrades/merges into authenticated identity only after successful phone verification, preserving invite attribution and history.
- [x] `D-04` Verified phone identity is normalized server-side to canonical `E.164` form before storage, lookup, or matching.
- [x] `D-05` Contact matching must rely on a backend-owned hardened phone hash/HMAC materialization strategy; plain unsalted `SHA` of the phone number is not the canonical long-term primitive.
- [x] `D-06` Tenant-public web remains promotion-only/read-only in V1; authenticated web and QR login stay outside this TODO and remain fast-follow work.
- [x] `D-07` OTP challenges use `6` digits plus backend-enforced TTL, resend cooldown, rate limits, and one active challenge per phone number.
- [x] `D-08` Landlord/admin authentication is a separate surface and is not changed by this tenant-public MVP auth cutover.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `flutter_client_experience_module.md` tenant-public authorization split | app owns trust-action conversion; web remains promotion-only | `Preserve` | tenant-public auth/hard-gate contract in module section `2.1` |
| `onboarding_flow_module.md` invite acceptance path minimal identity capture | capture is currently `name + email/phone` | `Supersede (Intentional)` | onboarding entry path section `2.1` |
| `invite_and_social_loop_module.md` Sanctum + identity requirement | app may mint anonymous identity; share acceptance is anonymous-first | `Preserve` | invite module section `Sanctum + Identity Requirement` |
| `invite_and_social_loop_module.md` `/contacts/import` hashed contacts | hashed contacts already drive contact matching and inviteable acquisition | `Preserve` with hardening clarification | invite module section `4 APIs & Events` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Belluga tenant-public MVP auth must resolve through effective `phone_otp` configuration once upstream auth-method governance closes; Belluga release behavior must not fall back to email/password.
- [x] `D-02` Anonymous-first invite acceptance remains intact and upgrades via merge after verification.
- [x] `D-03` Contact matching is only canonical after backend-normalized phone identity and hardened hash materialization are in place.
- [x] `D-04` Web remains promotion-only; this TODO must not widen into authenticated web/QR scope.
- [x] `D-05` Provider selection/secrets remain backend/admin-owned, not Flutter-owned.
- [x] `D-06` `Resend` is an implementation-owned interface choice, not a product-level open decision. Delivery may use a dedicated resend endpoint or an idempotent alias of challenge creation, but it must preserve the same public semantics: one active challenge per phone, backend-enforced TTL, resend cooldown, and rate limits.

## Decision Baseline Addendum (Reopened OTP/Admin UX Refinement)
- [x] `D-09` Tenant-admin release UI expresses OTP delivery as `Webhook WhatsApp` plus optional `Secondary OTP Channel com SMS`; `URL SMS` appears only when the secondary channel is enabled.
- [x] `D-10` Legacy/internal settings fields may remain as backend compatibility storage, but Flutter must derive them from the product model instead of exposing `Webhook OTP`, `Use WhatsApp webhook for OTP`, or `OTP Channel` as release controls.
- [x] `D-11` Tenant-public OTP challenge defaults to WhatsApp. SMS is an explicit secondary send action shown as `Outras formas` only when SMS is configured and available for the tenant.
- [x] `D-12` Phone entry must use a maintained country-aware phone input/mask/validation package unless package-first verification finds a stronger already-owned package; OTP entry must use a maintained segmented-code widget/package unless a local implementation is demonstrably lower-risk and equally accessible.
- [x] `D-13` Source-owned Playwright is required for the admin/web-visible quality gate, but Android-only public-auth UI acceptance still requires final ADB/device proof because tenant-public web remains promotion-boundary-first.

## Open External Readiness Input
- [x] Confirm which provider path is launch-authoritative for WhatsApp OTP: queued outbound webhook dispatch through `outbound_integrations`, configured from tenant-admin technical integrations.
- [ ] Confirm live receiving URLs/provider/template approval before production readiness. This is release-readiness input, not a product-definition gap for the auth flow itself.

## Verified Repository Assumptions
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The existing anonymous-first invite conversion flow remains the correct pre-auth foundation; this TODO only changes the authenticated upgrade path. | roadmap + invite module explicitly preserve anonymous identity and anonymous share acceptance in app. | The scope broadens into onboarding/invite redesign rather than auth cutover. | `High` | `Keep as Assumption` |
| `A-02` | Backend already materializes `phone_hashes` for users and `/contacts/import` already matches on phone hash. | `tests/Feature/Invites/InvitesFlowTest.php` covers `phone_hashes` materialization and phone contact import matches. | The release-critical matching path would need foundational backend work beyond this planned auth slice. | `High` | `Keep as Assumption` |
| `A-03` | Generic auth-method governance is now established upstream, but tenant-public Flutter and Laravel auth still require a Belluga-specific phone-OTP cutover. | `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md` + current Flutter `AuthRepositoryContract` and Laravel auth backend/routes still expose email/password login/register. | The remaining auth cutover is smaller than expected and this TODO can be narrowed during planning. | `High` | `Keep as Assumption` |
| `A-04` | Landlord/admin authentication can remain separate without weakening the tenant-public MVP contract. | tenant-public auth routes live separately from admin auth routes and current product direction only changes tenant-public conversion. | This TODO would widen into cross-scope auth redesign and should be split. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/completed/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-signin-signup-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/system_roadmap.md`
- `flutter-app/lib/**` auth/repository/router/screens/tests
- `laravel-app/routes/api/**`, auth controllers/services/tests, dispatcher integration surfaces

### Ordered Steps
1. Keep the dependency edge explicit from the store-release orchestrator, the minimal friends/favorites lane, and `TODO-store-release-landlord-tenant-auth-method-governance.md`.
2. Use the delivered upstream auth-governance TODO as the frozen baseline before making code changes under this downstream lane.
3. Implement the canonical doc/API contract changes already frozen here (`phone OTP`, anonymous merge preservation, hardened contact-hash language, provider ownership, web boundary preservation).
4. Introduce backend tenant-public phone OTP contract (`challenge`, `verify`, and resend semantics), including TTL/cooldown/rate limits, merge behavior, and token issuance.
5. Implement backend dispatcher integration with provider-agnostic service boundaries, WhatsApp-preferred send routing, and SMS fallback behavior.
6. Refactor Flutter tenant-public auth repositories/routes/screens from legacy email/password entry to phone + OTP entry while preserving auth-wall redirect semantics, anonymous invite continuity, and the absorbed auth-entry polish baseline.
7. Update backend + Flutter tests first around OTP flows, merge behavior, rate-limit/error handling, contact-match continuity, and phone-entry/OTP screen state quality.
8. Remove or quarantine legacy Belluga tenant-public email/password surfaces from store-release behavior and verify no remaining route/UX entry depends on them.
9. Rework tenant-admin technical integrations so the visible release settings model is WhatsApp primary webhook plus optional SMS secondary URL, while preserving backend compatibility fields behind the controller/repository boundary.
10. Extend the OTP challenge contract only if needed to allow an explicit secondary SMS challenge request; default challenge semantics must remain WhatsApp-first.
11. Replace the legacy login-derived public phone/OTP presentation with the redesigned flow: country selector/mask, segmented OTP, paste-to-fill, retry/cooldown, clear channel copy, and keyboard-safe layout.
12. Add source-owned Playwright specs and mutation shard entries from the matrix below before using browser evidence as release-gating proof.
13. Build/publish the current Flutter web bundle before Playwright execution and re-probe the browser-facing target for current-bundle provenance.
14. Run triple review and Claude CLI comparison after the TODO delivery packet is updated, with divergences treated as gates under the current orchestration rule.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** auth behavior, invite-attribution preservation, and contact-matching semantics are contract-defining and regression-prone.
- **Fail-first target(s) (when required):**
  - Laravel feature tests for OTP challenge/verify, merge of anonymous user into verified phone user, resend/cooldown/rate-limit behavior, and post-verification contact matching.
  - Flutter repository/controller/router tests for phone-entry + OTP verify flow, auth-wall continuation, anonymous invite continuity, absorbed auth-entry state quality, and removal of tenant-public email/password dependency.

### Reopened Refinement Task Matrix
| Task ID | Task | Primary Owner Surface | Required Evidence Before Closure |
| --- | --- | --- | --- |
| `T2R-ADM-01` | Simplify tenant-admin outbound OTP settings to WhatsApp webhook + optional SMS secondary URL. | Flutter tenant-admin settings UI + settings repository encoder/decoder. | Widget/repository tests plus Playwright `PW-OTP-ADM-01..03`. |
| `T2R-BE-01` | Preserve queued OTP webhook delivery and, if needed, support explicit `delivery_channel=sms` challenge requests without synchronous delivery. | Laravel OTP auth service, delivery resolver, queued job, tenant settings validation. | Laravel feature/unit tests for WhatsApp default, SMS secondary, missing SMS config rejection, exact URL-with-query dispatch, queue worker queue coverage. |
| `T2R-FL-01` | Redesign phone entry around country picker, country-aware mask/validation, and WhatsApp default CTA. | Flutter tenant-public auth presentation/controller. | Flutter widget/controller tests; Playwright conditional row `PW-OTP-PUBLIC-01` if browser-renderable; final ADB/device proof. |
| `T2R-FL-02` | Redesign OTP entry around separated code boxes, paste-to-fill, resend/cooldown state, and backend error readability. | Flutter tenant-public auth presentation/controller. | Flutter widget/controller tests; Playwright conditional rows `PW-OTP-PUBLIC-02..03` if browser-renderable; final ADB/device proof. |
| `T2R-FL-03` | Surface `Outras formas` only when SMS secondary is configured and send the SMS challenge path explicitly. | Flutter auth repository/controller + Laravel challenge contract. | Backend contract tests, Flutter repository/controller tests, Playwright conditional row `PW-OTP-PUBLIC-04`, final ADB/device proof. |

### Frontend / Consumer Matrix Addendum
| Producer / Contract | Expected Consumer | Visible Route / Action | DTO/Repository Boundary | Planned Evidence |
| --- | --- | --- | --- | --- |
| `outbound_integrations.whatsapp.webhook_url` | Flutter tenant-admin | `/admin` technical integrations, row `Webhook WhatsApp` | Tenant-admin settings decoder/encoder + repository save flow | Widget/repository tests; Playwright `PW-OTP-ADM-01`; mutation readback `PW-OTP-ADM-02`. |
| `outbound_integrations.otp.webhook_url` | Flutter tenant-admin as SMS secondary URL | Conditional `URL SMS` field shown only when `Secondary OTP Channel com SMS` is enabled | Tenant-admin settings decoder/encoder + repository save flow | Widget/repository tests; Playwright `PW-OTP-ADM-03`. |
| `outbound_integrations.otp.use_whatsapp_webhook` and `outbound_integrations.otp.delivery_channel` | Internal compatibility derived by Flutter/Laravel, not visible release controls | No direct release UI control | Controller/repository derives from WhatsApp-primary/SMS-secondary product model | Tests assert legacy labels/controls are absent; request payload/readback asserts derived values remain coherent. |
| `POST /api/v1/auth/otp/challenge` optional secondary channel intent | Flutter tenant-public auth | Phone entry default WhatsApp send and `Outras formas` SMS action when configured | Auth backend DAO + repository + controller | Laravel feature tests; Flutter repository/controller tests; Playwright conditional `PW-OTP-PUBLIC-02` and `PW-OTP-PUBLIC-04`; ADB final. |
| `DeliverPhoneOtpWebhookJob` on `otp` queue | External webhook receiver / operations | No direct UI; indirectly triggered by OTP challenge | Laravel delivery service + queue worker entrypoint | Laravel queue/config guardrails; optional live webhook smoke; Playwright observes only the user-facing challenge state, not job delivery closure. |
| Tenant-public `/auth/*` on web | Web promotion boundary | Browser must not widen into authenticated web OTP unless explicitly approved | Route guard / app promotion surface | Playwright readonly `PW-OTP-WEB-BOUNDARY-01`. |

### Flow Evidence Planning Matrix Addendum
| Criterion | Flow Impact Reason | Platform Parity | Runtime Lane | Mutation Required | Planned Evidence |
| --- | --- | --- | --- | --- | --- |
| Admin can configure WhatsApp OTP webhook without confusing duplicate controls. | Admin setup controls live delivery path used by OTP jobs. | `web-only` tenant-admin | Playwright `mutation` | Yes | `PW-OTP-ADM-01`, `PW-OTP-ADM-02`, widget/repository tests. |
| Admin can enable SMS secondary and save/read back `URL SMS`. | Secondary channel availability controls public `Outras formas`. | `web-only` tenant-admin | Playwright `mutation` | Yes | `PW-OTP-ADM-03`, backend settings validation. |
| Public phone entry captures country-aware E.164 phone numbers. | Contact matching and OTP delivery depend on normalized phone identity. | `android-primary`; conditional web-rendered shared widget | Flutter widget/controller + final ADB; Playwright conditional | Yes if browser-renderable | `PW-OTP-PUBLIC-01` when applicable; ADB final remains required. |
| Public OTP entry supports segmented input, paste-to-fill, and readable errors. | OTP conversion is a release-critical auth funnel. | `android-primary`; conditional web-rendered shared widget | Flutter widget/controller + final ADB; Playwright conditional | Yes if browser-renderable | `PW-OTP-PUBLIC-02`, `PW-OTP-PUBLIC-03` when applicable; ADB final remains required. |
| SMS fallback is visible only when configured and sends explicit SMS challenge. | Avoids false affordances and wrong provider dispatch. | `shared-android-web` if auth route renders on web; otherwise Android + backend | Laravel + Flutter + Playwright conditional + ADB | Yes | `PW-OTP-PUBLIC-04` when applicable; Laravel feature tests always required. |
| Tenant-public web remains promotion-boundary-first for auth-owned routes. | Prevents accidental widening into authenticated web. | `web-only` tenant-public | Playwright `readonly` | No | `PW-OTP-WEB-BOUNDARY-01`. |

### Playwright Test Matrix (Source-Owned)
Preconditions for every Playwright evidence row:
- Test sources live under `tools/flutter/web_app_tests/**`; `web-app` remains a compiled bundle output only.
- Execute only through `tools/flutter/run_web_navigation_smoke.sh readonly|mutation`.
- Build/publish the current Flutter web bundle first with `scripts/build_web.sh ../web-app <lane>` from `flutter-app`, then re-probe the browser-facing target for current-bundle provenance before reading failures as product failures.
- Mutation browser evidence must run on a non-`main` target with `NAV_DEPLOY_LANE != main`, `NAV_TENANT_URL`, `NAV_ADMIN_EMAIL`, and `NAV_ADMIN_PASSWORD` set by the runtime environment.
- Add an `otp-auth` entry to `tools/flutter/web_app_tests/navigation_mutation_shards.json` for deterministic mutation selection.
- Specs must satisfy `tools/flutter/web_app_tests/guard_web_navigation_policy.cjs`: semantic locators only, no coordinate clicks, no `click({ force: true })`, no committed credential fallback, centralized dropdown helper use.

| ID | Task Source | Planned Spec / Tag | Lane | Setup | Browser Actions | Required Assertions | Complement / Limit |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `PW-OTP-ADM-01` | `T2R-ADM-01` | `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js` / `@mutation OTP Auth admin exposes WhatsApp primary and optional SMS fallback without legacy controls` | `mutation` | Login through `support/tenant_admin_auth.js`; open tenant-admin technical integrations. | Inspect outbound webhook settings section. | Shows `Webhook WhatsApp` and `Secondary OTP Channel com SMS`; hides `Webhook OTP`, `Usar webhook WhatsApp para OTP`, and `Canal OTP`; no browser/runtime/console failures. | Runtime mutation passed on 2026-04-30 against `https://guarappari.belluga.space`. |
| `PW-OTP-ADM-02` | `T2R-ADM-01`, `T2R-BE-01` | same spec / same mutation title | `mutation` | Uses routed settings response on approved non-main tenant to avoid mutating live settings while still exercising the real Flutter admin screen. | Fill WhatsApp webhook with a URL containing query string; enable SMS secondary; fill `URL SMS`; save. | Captured PATCH payload preserves WhatsApp/SMS query-string URLs and derives compatibility fields as `otp.use_whatsapp_webhook=true`, `otp.delivery_channel=whatsapp`, TTL `10`, cooldown `60`, attempts `5`. | Backend exact-URL job delivery remains covered by Laravel tests/live receiver smoke. |
| `PW-OTP-ADM-03` | `T2R-ADM-01`, `T2R-FL-03` | same spec / same mutation title | `mutation` | Same as `PW-OTP-ADM-02`. | Enable `Secondary OTP Channel com SMS`; verify `URL SMS`; save. | SMS toggle controls `URL SMS`; no legacy channel dropdown appears; API payload exposes values needed by public auth. | Sets up/validates prerequisite for `Outras formas`. |
| `PW-OTP-WEB-BOUNDARY-01` | `D-06`, `D-13` | `tools/flutter/web_app_tests/otp_auth_public.spec.js` / `@readonly OTP-WEB-BOUNDARY-01` | `readonly` | Tenant URL only. | Open auth-owned tenant-public web paths such as `/auth/login` or a documented auth-gated web action. | Web remains app-promotion/handoff boundary and does not expose authenticated web OTP as a new capability. | Runtime readonly passed on 2026-04-30 against `https://guarappari.belluga.space`; this is a boundary guard, not mobile OTP UI proof. |

Rows previously planned as tenant-public browser mutation proof for the phone/OTP form were retired after runtime validation confirmed the release web boundary is correct. Tenant-public phone/OTP behavior remains covered by Flutter widget/controller/repository tests plus Android device evidence; Playwright now covers only the web boundary and the admin-visible settings surface for this TODO.

Playwright source evidence added on 2026-04-29:
- `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js` covers rendered admin controls and outbound PATCH payload compatibility.
- `tools/flutter/web_app_tests/otp_auth_public.spec.js` covers tenant-public web auth-boundary readonly preservation; phone-first public OTP remains an app/mobile behavior for this release.
- `tools/flutter/web_app_tests/navigation_mutation_shards.json` now includes deterministic shard `otp-auth`.
- `node --check` passed for both OTP specs; shard validation selects the admin OTP mutation test and readonly validation selects `@readonly OTP-WEB-BOUNDARY-01 tenant-public web auth remains app promotion boundary`.

### Reopened Visual Redesign Gap (2026-04-29)

- **User QA result:** the prior phone-entry and OTP-entry implementation was functionally acceptable but visually unacceptable for release. The local checkpoint now replaces that legacy-derived structure with a dedicated modern OTP experience.
- **Functional baseline to preserve:** phone country selection/mask/validation, E.164 challenge payload, WhatsApp default, optional SMS fallback, segmented six-digit OTP entry, paste-to-fill capability, resend/cooldown handling, disabled/loading states, and backend error display.
- **Visual baseline replaced:** tenant-public OTP now renders through `AuthPhoneOtpExperience`, not the old login page composition.
- **Design requirement evidence:** user-approved Stitch direction was the compact modern OTP flow with stepper + single focused form panel; implementation follows that selected direction using the app Theme rather than local one-off colors.
- **Execution boundary:** this remained a visual/layout redesign of tenant-public OTP auth and did not reopen admin outbound settings, OTP backend delivery, contact-hash matching, anonymous merge, or authenticated web scope.

### Reopened Visual Task Matrix
| Task ID | Task | Primary Owner Surface | Required Evidence Before Closure |
| --- | --- | --- | --- |
| `T2R-FL-04` | Generate modern phone-entry and OTP-entry layout proposals with Stitch, then select the release direction. | Stitch design workflow + Flutter tenant-public auth surface. | User-approved selected direction recorded here: compact modern OTP flow with stepper + focused panel. |
| `T2R-FL-05` | Replace the current phone-entry layout with the approved modern layout while preserving country-aware phone behavior. | Flutter tenant-public auth presentation. | `AuthPhoneOtpExperience`; focused widget/controller tests; ADB auth navigation smoke. |
| `T2R-FL-06` | Replace the current OTP-entry layout with the approved modern layout while preserving segmented input, paste, resend/cooldown, channel context, and errors. | Flutter tenant-public auth presentation. | `AuthPhoneOtpExperience`; `AuthPhoneOtpForm`; focused widget/controller tests including full six-digit fill; ADB auth navigation smoke. |

### Reopened Visual Test Matrix Derivation Loop

The reopened visual work must derive test coverage per task, not from a single aggregate UI smoke. For each visual task:

1. Start from the specific functional behavior the visual change could break.
2. Preserve or add the focused widget/controller assertion first.
3. Add a visual/runtime row for the affected state variant.
4. Run the smallest focused suite after the task, then rerun the full OTP focused suite before closure.
5. Treat browser/device unavailable states as `blocked`, not passed.

| Task | Behavior At Risk | Required Test Evidence | Visual / Runtime Evidence | Status |
| --- | --- | --- | --- | --- |
| `T2R-FL-04` Stitch design selection | Implementation starts without approved visual target. | n/a | Selected-direction note recorded in this TODO. | `passed` |
| `T2R-FL-05` phone-entry replacement | Country selection, mask, validation, E.164 payload, WhatsApp default CTA. | Flutter widget/controller/repository tests for phone challenge. | Direct ADB auth navigation smoke on `192.168.15.9:5555`; Playwright source/list validation. | `passed` |
| `T2R-FL-06` OTP-entry replacement | Segmented input, paste-to-fill, verify validation, errors, resend/cooldown, SMS fallback. | Flutter widget/controller tests for OTP state variants and channel actions. | Direct ADB auth navigation smoke on `192.168.15.9:5555`; Playwright source/list validation. | `passed` |
| `T2R-FL-06` race/disabled states | Double send/resend/verify creates duplicate challenge or incoherent state. | Frontend race-condition validation + controller tests. | Browser mutation for the app OTP form is not applicable under the release web boundary; controller/loading-state tests and Android device evidence remain local proof. | `passed` |

### Runtime / Rollout Notes
- OTP provider secrets, channel templates, sender identities, and resend/cooldown policy remain backend/admin-owned.
- Launch readiness depends on external provider approval/readiness; that dependency must be verified before implementation approval or explicitly handled with a fallback policy.
- Existing release telemetry should add OTP funnel milestones (`otp_challenge_started`, `otp_verified`, `auth_merge_completed`) without breaking the current invite conversion funnel.

## Local Delivery Notes (2026-04-28)

- **Implemented backend contract:** added tenant-public phone OTP challenge/verify endpoints with backend-owned phone normalization, one active challenge per phone, TTL, resend cooldown, max attempts, token issuance, anonymous identity merge, and contact-hash materialization.
- **Implemented queued webhook delivery:** OTP dispatch is backend-owned, provider-agnostic to Flutter, and sent by `DeliverPhoneOtpWebhookJob` through tenant settings for WhatsApp and optional OTP-specific webhook URLs.
- **Implemented Flutter contract:** tenant-public auth entry now uses phone/OTP challenge and verification state instead of the Belluga email/password release path.
- **Validation evidence:** `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md` records Laravel OTP feature tests, webhook delivery unit, tenant-aware queue test, settings schema tests, Flutter focused tests, and analyzer pass.
- **Triple audit gate:** `foundation_documentation/artifacts/t2-phone-otp-auth-contact-match-review-packet-triple-audit-20260428T160322Z/round-01/resolution.md` records `accepted-debt` only for final ADB/device proof, CI/promotion evidence, and future process-level fail-first capture; no local-delivery blocker remains.
- **Legacy password-route quarantine:** `laravel-app/app/Http/Middleware/EnsureTenantPublicAuthMethod.php` now gates tenant-public password auth routes by the effective `tenant_public_auth` contract. The Belluga `phone_otp` effective configuration returns an `auth_method` validation error for `/auth/login`, `/auth/register/password`, `/auth/password_token`, `/auth/password_reset`, and authenticated `/profile/password`, while preserving the generic platform's password capability when `password` remains effective.
- **Quarantine validation evidence:** `bash scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PasswordRegistrationControllerTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` passed with `7` tests / `21` assertions; `docker compose exec -T app ./vendor/bin/pint --test app/Http/Middleware/EnsureTenantPublicAuthMethod.php routes/api/public_tenant_maybe_api_v1.php tests/Feature/Tenants/PasswordRegistrationControllerTest.php` passed.
- **Claude CLI auxiliary review:** `foundation_documentation/artifacts/claude-cli-reviews/T2-phone-otp-auth-contact-match-cli-review.md` records earlier operational unavailability. The later consolidated runtime Claude review at `foundation_documentation/artifacts/claude-cli-reviews/store-release-final-runtime-cli-review-20260428.md` returned one actionable T2 gap (`Legacy Belluga tenant-public email/password routes/UI/tests...` unchecked); that gap is now resolved by the route middleware and validation evidence above.

## Local Gap Closure Notes (2026-04-29)

- **Closed tenant-admin configuration gap:** added the missing Flutter/admin consumer for `outbound_integrations` with hub discoverability, technical integration section, repository contract methods, encoder/decoder support, controller save flow, and widget/repository coverage.
- **Validation evidence:** `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` passed with `77/77` tests.
- **Analyzer evidence:** `fvm dart analyze --format machine` exits `2` because of pre-existing T2/T3 OTP/invite architecture debts; no diagnostics remain in the outbound webhook settings files.
- **Process evidence:** `foundation_documentation/artifacts/store-release-backend-front-gap-analysis-20260429.md` records why the previous gate missed this backend-without-frontend gap and the recommended consumer-matrix rule.
- **Reopened product/UX correction:** user QA found that the first admin consumer was technically present but product-incoherent for OTP delivery settings, and that the public phone/OTP UI remained too close to the legacy login flow. The reopened matrix above supersedes the previous local-implemented closure claims for admin outbound settings and public OTP UI quality until the Playwright/device gates are complete.
- **Design exploration gap:** no Stitch-generated phone/OTP screen images were produced before the first functional implementation. `T2R-FL-04` now requires a real Stitch-backed layout exploration and recorded screen/image references before the replacement visual implementation starts.

## Reopened Product/UX Closure Notes (2026-04-29)

- **Admin UX corrected:** the technical integrations screen now exposes `Webhook WhatsApp`, `Secondary OTP Channel com SMS`, and conditional `URL SMS`; legacy visible controls `Webhook OTP`, `Usar webhook WhatsApp para OTP`, and `Canal OTP` are removed from the rendered admin surface.
- **Public OTP functional core corrected:** tenant-public auth now uses `phone_form_field` for country-aware phone entry and `pinput` for six-character OTP entry; WhatsApp is the primary channel and SMS is an optional secondary channel only when the environment exposes an OTP webhook URL.
- **Public OTP visual release gap closed locally:** user QA on 2026-04-29 rejected the previous visual/layout direction as too close to the legacy login flow. The current local checkpoint replaces it with `AuthPhoneOtpExperience`, preserving functional behavior while using the approved modern OTP direction.
- **Contract corrected:** Flutter sends explicit `delivery_channel` to `POST /api/v1/auth/otp/challenge`; backend accepts `whatsapp|sms`, defaults to WhatsApp, uses `otp.webhook_url` for SMS, and rejects missing SMS configuration.
- **Validation evidence:** `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart test/infrastructure/repositories/auth_repository_signup_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart` passed with `90/90` tests after the ValueObject/analyzer fix; the focused OTP test was rerun after adding the full six-digit fill assertion and passed `8/8`.
- **Analyzer evidence:** `fvm dart analyze --format machine` passed cleanly after the reopened correction.
- **Web build evidence:** `bash scripts/build_web.sh ../web-app dev` completed and produced the current web bundle in `../web-app`.
- **Playwright matrix evidence:** source-owned OTP specs and shard `otp-auth` were added; `node --check` passed, shard validation selected the admin OTP mutation test, and readonly listing selected `OTP-WEB-BOUNDARY-01`.
- **Runtime credential follow-up (2026-04-30):** runtime credentials were provided and the canonical readonly web navigation runner passed against `https://guarappari.belluga.space`: `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space NAV_DEPLOY_LANE=dev PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_WEB_WORKERS=1 bash tools/flutter/run_web_navigation_smoke.sh readonly` -> `10 passed (3.5m)`, including `OTP-WEB-BOUNDARY-01`.
- **Runtime mutation intermediate finding (2026-04-30):** after runtime credentials were supplied, the first canonical `NAV_WEB_SHARD=otp-auth` pass exposed a runtime/admin settings gap: the technical integrations page rendered `FormApiFailure(status=404, message=Resource you are looking for was not found.)` while loading settings. The public mutation assertion was also reclassified because tenant-public `/auth/login` on web correctly renders app promotion instead of the in-app phone OTP form; phone/OTP UI behavior remains covered by Flutter tests and Android/device evidence, not by tenant-public web mutation.
- **Runtime mutation closure (2026-04-30):** the admin 404 root cause was the Flutter tenant-admin settings repository using `/admin/api/v1/settings/firebase|push` for tenant-scoped Firebase/Push settings while the runtime backend exposes those endpoints at `/api/v1/settings/firebase|push`. The repository now uses the tenant public API origin for those two package-owned settings endpoints. The web mutation matrix was also corrected so tenant-public phone/OTP form behavior is not asserted on the web release boundary; web keeps `OTP-WEB-BOUNDARY-01`, while phone/OTP form behavior remains covered by Flutter/widget/Android evidence.
- **Runtime mutation evidence (2026-04-30):** `fvm flutter test test/infrastructure/repositories/tenant_admin_settings_repository_test.dart` passed with `37/37`; `fvm dart analyze --format machine` passed cleanly; `bash scripts/build_web.sh ../web-app dev` passed; `NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space NAV_DEPLOY_LANE=dev PLAYWRIGHT_IGNORE_HTTPS_ERRORS=true NAV_WEB_WORKERS=1 NAV_WEB_SHARD=otp-auth NAV_ADMIN_EMAIL=<runtime-secret> NAV_ADMIN_PASSWORD=<runtime-secret> bash tools/flutter/run_web_navigation_smoke.sh mutation` passed with `1 passed (20.7s)`; readonly rerun passed with `10 passed (3.0m)`.
- **Backend runtime-equivalent evidence (2026-04-30):** `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPhoneOtpAuthTest.php tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php tests/Unit/Application/Auth/PhoneOtpWebhookDeliveryServiceTest.php tests/Unit/Queue/TenantAwareQueueJobsTest.php tests/Feature/Settings/SettingsKernelControllerTest.php` passed with `45` tests / `249` assertions. This proves WhatsApp default dispatch, explicit SMS dispatch, query-string preservation for webhook URLs, queued `otp` jobs, fake HTTP webhook delivery, OTP telemetry, and settings-kernel outbound integration contracts.

## Reopened Production Error-Handling Blocker (2026-04-29)

- **User QA blocker:** OTP is not production-ready while raw backend/API exception payloads can be displayed to end users.
- **Observed failures:** `Reenviar codigo` can display raw API error output; entering an incorrect OTP code can display raw API error output instead of a field-appropriate code message; phone-entry copy says `WhatsApp e o canal principal desta etapa.` instead of the approved production copy; the two-step indicator is visually heavy on Android.
- **Required closure:** sanitize OTP request/resend/verify failures, map wrong-code failures to the OTP code field, keep user-facing messages concise and production-safe, replace the WhatsApp helper copy with `Enviaremos o código para seu número WhatsApp.`, rework the mobile stepper, and capture before/after ADB screenshot evidence.
- **Before screenshot:** `/tmp/otp-current-adb.png` captured from device `192.168.15.9:5555` on 2026-04-29 shows the current oversized stepper and old WhatsApp copy.

### Package-First Assessment

- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app --search "form"`
- **Relevant packages found:** `[Local] belluga_form_validation` — used directly for OTP validation state.
- **READMEs read:** `flutter-app/packages/belluga_form_validation/README.md`
- **Decision:** use `belluga_form_validation` via `FormValidationControllerAdapter` in `AuthLoginControllerContract`.
- **Tier:** `Local`
- **Rationale:** the package is the canonical local surface for field/global form validation and prevents duplicating ad-hoc validation state for production OTP errors.

### Production Error-Handling Closure

- **Fix scope:** OTP request/resend/verify failures no longer reuse raw unknown-error rendering. Request/resend failures resolve to concise global form messages; wrong-code verification failures resolve to the OTP code field through `belluga_form_validation`.
- **User-facing messages:** wrong code renders `Código inválido. Confira os 6 dígitos e tente novamente.`; resend/request failure renders `Não conseguimos enviar o código agora. Tente novamente em instantes.`; WhatsApp copy renders `Enviaremos o código para seu número WhatsApp.`.
- **Stepper closure:** Android phone-entry stepper now renders as two compact theme-driven pills (`Telefone`, `Código`) instead of the heavy dot/line plus `Passo 1 de 2` label.
- **Fail-first evidence:** `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart --plain-name otp` initially failed because the OTP field error state did not exist.
- **Focused GREEN evidence:** `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart --plain-name otp` passed with `6/6` OTP-selected tests.
- **Full focused evidence:** `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` passed with `11/11` tests.
- **Repository continuity evidence:** `fvm flutter test test/infrastructure/repositories/auth_repository_signup_test.dart` passed with `4/4` tests.
- **Analyzer evidence:** `fvm dart analyze --format machine` passed cleanly after moving validation target IDs into the existing controller contract to satisfy architecture lint.
- **Android build/install evidence:** `fvm flutter build apk --debug --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true` built `build/app/outputs/flutter-apk/app-guarappari-debug.apk`; `adb -s 192.168.15.9:5555 install -r build/app/outputs/flutter-apk/app-guarappari-debug.apk` installed successfully.
- **After screenshot:** `/tmp/otp-after-adb.png` captured from device `192.168.15.9:5555` on 2026-04-29 shows the corrected phone-entry screen with the compact stepper and approved WhatsApp copy.

### Post-Login Hydration Follow-Up

- **QA clarification:** repeated OTP login with the same phone initially suggested possible user recreation, but later evidence showed confirmations and favorites persisted and became visible in backend-backed surfaces after delay. This TODO does not record "new user per login" as a closed diagnosis.
- **Auth handoff fix:** the Flutter shell now starts `PostAuthIdentityHydrationCoordinator` after app initialization. When auth transitions to a registered identity, it refreshes identity-owned streams needed immediately after login: Home favorite resumes, account-profile favorite IDs, confirmed occurrence IDs, and pending invites.
- **Favorite stale-state guard:** `AccountProfilesRepository.refreshFavoriteAccountProfileIds` clears stale favorite IDs when the current identity has no backend favorites, preventing cross-identity ghost state.
- **Hydration race guard:** coordinator tests now cover all four registered-identity refresh consumers and the logout/anonymous reset while a hydration is still in flight, proving the same registered user is rehydrated after the reset instead of being skipped by the per-user loop guard.
- **Focused evidence:** `fvm flutter test test/application/auth/post_auth_identity_hydration_coordinator_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart test/application/application_contract_test.dart` passed with `20/20` tests after the race guard; `fvm dart analyze --format machine` passed cleanly; `bash scripts/build_web.sh ../web-app dev` passed and refreshed the derived web bundle.
- **Focused rerun evidence (2026-04-30):** `fvm flutter test test/application/auth/post_auth_identity_hydration_coordinator_test.dart test/infrastructure/repositories/account_profiles_repository_test.dart` passed with `15/15`, confirming the post-auth hydration coordinator and stale favorite clearing remain green after the checkpoint.

### Bug-Fix Evidence Matrix

| Stage | Coverage Status | Evidence |
| --- | --- | --- |
| API/raw backend failure payload | `covered-by-negative-fake` | Tests inject raw `Exception` strings containing endpoint path, JSON `errors`, and `trace` payloads. |
| Repository/controller translation | `covered` | Controller tests assert raw OTP verify failures become field validation and raw resend failures become global validation. |
| Form validation state | `covered` | `belluga_form_validation` adapter owns OTP validation state; tests assert `errorForField(code)` and `errorsForGlobal()`. |
| UI rendering | `covered` | Widget test asserts sanitized wrong-code and resend messages render and raw API fragments do not render. |
| Android visual | `covered` | Before/after ADB screenshots: `/tmp/otp-current-adb.png`, `/tmp/otp-after-adb.png`. |

### Architecture Prevention Assessment

- **Assessment:** `no-rule-needed` for the product error itself because the leak depends on runtime exception contents and backend payload shape.
- **Existing rule signal used:** the analyzer did catch the attempted extra public validation-target class (`MULTI_PUBLIC_CLASS_FILE_WARNING`), so the architecture guard remains effective for code-shape drift.
- **Process recommendation:** keep this covered by test matrix/bug-fix evidence loop rather than a new static analyzer rule; the static boundary cannot reliably distinguish safe from unsafe runtime exception text without high false-positive risk.
