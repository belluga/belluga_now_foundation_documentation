# TODO (Store Release): Web-to-App Conversion Gate

**Scope authority note (2026-04-17):** canonical policy definition now lives in `foundation_documentation/policies/web_to_app_promotion_policy.md` plus the promoted module contracts. This TODO no longer owns baseline policy authoring; it owns the remaining Android-release closure gates for that policy: replace the current pre-MVP guard experience with real app promotion, preserve redirect intent through promotion/deferred flow, and run real-device store/deferred validation. Cross-flow funnel-metrics validation is tracked in `TODO-store-release-funnel-metrics-validation.md`, but any missing event implementation remains owned by the concrete flow TODO that needs it.

**Contract correction note (2026-04-30):** canonical policy `1.7` supersedes this TODO's older "anonymous accept" wording. The boundary is **web anonymous**, not web globally; QR-authenticated web follows the normal authenticated posture. App anonymous invite preview/session context remains allowed, but explicit invite accept/decline is a trust mutation requiring authenticated identity. Historical evidence rows that mention anonymous share acceptance must be interpreted as superseded unless revalidated against the current `401 auth_required` anonymous-accept contract.

**Classification note (2026-04-16):** this TODO was migrated into `active/store_release_android/` because it owns the real open device/store/deferred-link closure gates for Android publication confidence. Cross-flow release metrics proof now lives in the dedicated sibling funnel-validation TODO.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active. Canonical policy, route hardening, backend deep-link packageization, and most Flutter implementation are already delivered. Remaining store-release closure is now narrow but real: (1) replace the current `testerWaitlist` guard experience on `/baixe-o-app` with real app promotion/store handoff, (2) preserve redirect intent beyond invite-only flows so event/detail and guarded-route intent survives install/open, and (3) run real-device install/store/deferred validation. Telemetry matrix/KPI proof remains required for release, but it is now tracked in a dedicated sibling TODO.
**Owners:** Delphi (Product/Flutter) + Backend Team + Web Team + Data Team  
**Goal:** close the Android store-release web-to-app conversion gate by validating the preview-first invite funnel end to end while keeping anonymous web strictly showcase/read-only, preserving route intent across promotion, and enforcing trust mutations behind authenticated identity.

---

## Delivery Status Canon

- **Current delivery stage:** `Manual-Publication-Validated; Installed-App-Pre-Guard-Deep-Link-Intent-Validated-Local`
- **Qualifiers:** `Policy-Frozen`, `Cross-Stack`, `Local-Gate-Closed`, `Store-Deferred-Runtime-Waived`, `Publication-Settings-Automated-Validated`, `Manual-Stage-Validated`, `Installed-App-Deep-Link-Guard-Reopened`, `Android-Intent-Handoff-Fixed-Local`, `Pre-Guard-Action-Handoff-Fixed-Local`, `ADB-Device-Intent-Revalidated`
- **Next exact step:** user/manual stage revalidation may now focus on the actual Discovery card/favorite UI tap behavior; local ADB validation already proves the installed Android app receives the preserved invite, attendance, account-profile favorite, and invite-sharing targets before the Guard fallback.

## Scope

- [x] ✅ Installed-app hard-gate handoff opens the preserved target deep link when the app is already installed instead of falling into the Guard first.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `T1-LOCAL-01` | Local Delivery Notes | Local implementation of real app-promotion/store handoff, continuation preservation, and anonymous favorites alignment is complete for the non-store local gate. | Implementation packet and review/audit evidence | `foundation_documentation/artifacts/t1-web-to-app-review-packet-triple-audit-20260428T142017Z/round-02/round-summary.md`; Local Delivery Notes below | Local Flutter/Laravel tests; browser evidence from prior lane; Android store/deferred proof waived for local checkpoint | passed | Guard-readable row added because this legacy TODO uses lettered sections rather than exact `Scope`/`Definition of Done` headings. |
| `T1-RUNTIME-01` | Runtime Waiver | Real Play Store/install/deferred-link validation remains outside this local checkpoint until the promotion/runtime lane can prove external store behavior. | Approved runtime waiver | User `APROVADO` on 2026-04-29; orchestration plan records ADB/store rows as final-runtime inputs | Android device/store external runtime | waived | This is not a `Production-Ready` waiver; it only permits local checkpoint closure without claiming Play Store deferred proof. |
| `T1-PUB-01` | Additive Publication Settings | Tenant admin can define whether Android and iOS publication targets are active and, when active, the platform store URL. Promotion/open-app UI consumes these dynamic active targets instead of assuming every configured platform should be shown. | Flutter admin settings tests, Laravel settings tests, promotion UI tests, analyzer, web build, manual stage validation | `fvm flutter test test/domain/tenant_admin/settings/tenant_admin_app_links_settings_test.dart test/infrastructure/dal/dto/app_data_dto_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`; `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Feature/Settings/SettingsKernelControllerTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php`; `fvm dart analyze --format machine`; `bash scripts/build_web.sh ../web-app dev`; user manual validation on 2026-04-30 | Tenant admin settings, backend settings environment, promotion route/UI, refreshed web bundle, stage runtime | passed | Automated local closure passed on 2026-04-30: Flutter settings/promotion `99/99`, Laravel impacted `57/57`, analyzer clean, web build clean. User manually validated the real `Publicação` settings area and confirmed promotion/open-app surfaces only offer active platform targets with configured store URLs. |
| `T1-INSTALLED-APP-01` | Scope | Installed-app hard-gate handoff opens the preserved target deep link when the app is already installed instead of falling into the Guard first. | Backend automated + manual device QA | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`; `fvm flutter test test/platform/deep_link_platform_config_test.dart test/presentation/shared/promotion/support/web_installed_app_handoff_test.dart test/application/router/support/route_redirect_path_test.dart test/presentation/shared/widgets/app_promotion_dialog_test.dart`; `ANDROID_SERIAL=192.168.15.9:5555 ANDROID_OPEN_APP_BASE_URL=https://guarappari.belluga.space tool/validate_android_app_link_intents.sh`; ADB warm-start screenshots `/tmp/guarappari_warm_event_after_defines.png`, `/tmp/guarappari_warm_partner_after_defines.png`, `/tmp/guarappari_chrome_open_app_partner_warm.png` | Laravel test container + Android installed-app/stage runtime | passed-local-adb | Local fix sends Android web hard-gate clicks through `/open-app?fallback=promotion`: installed apps receive the preserved target directly (`favorite` -> partner, invite accept/attendance confirm -> occurrence), and the browser Guard remains fallback when the app is absent. On 2026-04-30 BRT, Codex installed the lane-defined GuarAppari debug APK on `moto_e13` (`192.168.15.9:5555`) and validated App Link resolution for all configured hosts plus `/open-app` intent redirects for invite accept, attendance confirmation, account-profile favorite, and invite sharing. Warm direct intents navigated to event and partner detail, and Chrome `/open-app` navigated from web to the partner detail. The remaining manual focus is the actual Discovery card/favorite tap UI semantics, not Android intent resolution. |

## No-Context Handoff Boundaries

- **Frozen here:** `W2A-D01` through `W2A-D15` are the governing product baseline for this TODO. Delivery must not reopen anonymous web posture, anonymous app baseline, authenticated web allowance, or the login-method split (`QR` on web, `OTP` in app).
- **Not owned here:** QR-authenticated web session bootstrap, continuation, and logout belong to `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`.
- **Primary canonical anchors:** `foundation_documentation/policies/web_to_app_promotion_policy.md` and `foundation_documentation/modules/flutter_client_experience_module.md`.
- **Secondary canonical anchors:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/system_roadmap.md`, and `foundation_documentation/endpoints_mvp_contracts.md`.
- **Executor rule:** treat this TODO as a release-closure packet, not as a place to redesign the broader authenticated-web product. Only the remaining Android publication blockers listed below are in scope.

## Decision Baseline (Consolidated 2026-03-29)

- [x] ✅ `W2A-D01` MVP deferred deep link capture is Android-only (Play Store install path). iOS deferred capture is mandatory fast-follow after the Android gate.
- [x] ✅ `W2A-D02` Store targets must be dynamic per tenant for both Android and iOS (backend-resolved contract; no hardcoded store URLs in web/app clients).
- [x] ✅ `W2A-D03` Deferred pipeline ownership is split: Laravel package resolves redirect/attribution contract; Flutter captures first-open Android signal and consumes resolver result.
- [x] ✅ `W2A-D04` When first-open deferred capture does not resolve an invite `code`, fallback route is canonical tenant home (`/`).
- [x] ✅ `W2A-D05` `store_channel` is required on deferred-funnel telemetry surfaces (`web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`).
- [x] ✅ `W2A-D06` Web hard-gate handoff target is deterministic and context-aware: invite-landing context (`/invite` or `/convites`) with valid `code` keeps `/invite?code=...`; direct detail routes and guard-triggered targets preserve the requested redirect path when continuation intent is valid; only unresolved continuation falls back to `/`.
- [x] ✅ `W2A-D07` Backend deep-link architecture is package-owned end-to-end: existing host logic for `.well-known` app association, `settings.app_links` registry/validation, web promotion handoff resolution, and deferred first-open resolver must live in `belluga_deep_links` (host app only wires routes/adapters/contracts).
- [x] ✅ `W2A-D08` Web identity boundaries must converge on one route-based promotion surface: unauthenticated web route gates (for example `/profile`) and action hard-gates (`favorite`, `send_invite`, attendance boundary) resolve to the same Flutter promotion route/screen, not modal-vs-route divergence.
- [x] ✅ `W2A-D09` The canonical promotion route must support explicit Android + iOS store targeting, and `/open-app` must accept `platform_target=android|ios` override so desktop/unknown web can open the intended tenant-dynamic store target. The promotion screen must consume runtime environment branding and may adaptively render a single Android/iOS CTA when browser platform inference is reliable, with dual-badge fallback otherwise. Apple badge/artwork usage must follow Apple’s published App Store Marketing Guidelines.
- [x] ✅ `W2A-D10` Anonymous web keeps the current read-only/public posture, but hard/auth gates must promote to the app-promotion/store route instead of the old pre-MVP tester-waitlist form.
- [x] ✅ `W2A-D11` Authenticated web is allowed, but login is exclusively QR-based from an already promoted app identity; web-native email/password/social login is not part of this contract.
- [x] ✅ `W2A-D12` Authenticated app upgrade is exclusively phone-OTP; this conversion TODO must stay aligned with `TODO-store-release-phone-otp-auth-and-contact-match.md`.
- [x] ✅ `W2A-D13` Deferred handoff must preserve the originally requested route intent, not only invite share-code context; canonical `/` fallback is valid only when no continuation intent can be resolved.
- [x] ✅ `W2A-D14` Anonymous app usage is not a blanket Auth Wall surface. Invite preview/session context, feed browsing, map browsing, and favorites may stay anonymous; invite accept/decline and other explicitly restricted trust actions require authenticated identity.
- [x] ✅ `W2A-D15` Tenant store publication is a dynamic settings/environment contract: Android and iOS each expose an active flag plus store URL. Promotion surfaces and `/open-app` resolution must only offer active platform targets with URLs, and admin settings must make publication state explicit.

---

## References
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/system_roadmap.md` (Web-to-App Promotion Policy section)
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/completed/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-funnel-metrics-validation.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md` (deferred authenticated workspace scopes)
- `foundation_documentation/todos/active/vnext/TODO-vnext-event-checkin.md` (physical check-in feature delivery)
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md` (iOS deferred deep link/runtime validation)

---

## Current Release Blockers

- [x] ✅ **Promotion boundary release readiness:** the canonical `/baixe-o-app` route now defaults to the real app-promotion/store-handoff experience. Android store/deferred external proof remains a runtime-lane input.
- [x] ✅ **Redirect-intent preservation:** local implementation now preserves safe public/detail and allowed auth-owned app continuation paths through Flutter `/open-app`, backend store-referrer payload, deferred resolver `target_path`, and Flutter first-open routing. Real-device store/deferred validation remains in the consolidated ADB phase.
- [x] ✅ **Anonymous favorites implementation gap:** discovery, account-profile detail, and immersive linked-profile favorite actions were aligned with anonymous identity support in the local checkpoint; full external promotion/runtime proof remains in the promotion lane.
- [x] ✅ **Publication settings additive scope:** local automated delivery is complete and user manually validated the stage `Publicação` area plus promotion/open-app active-target filtering on 2026-04-30.
- [x] ✅ **Installed-app deep-link guard:** local Android intent handoff is fixed for both promotion store/open clicks and pre-Guard hard-gate action clicks. ADB/device validation on `moto_e13` proves invite accept, attendance confirmation, account-profile favorite, and invite sharing targets resolve to the installed app before Guard fallback; direct UI finger-tap validation remains useful only for Discovery card/favorite semantics.

---

## A) V1 Policy Baseline (Progressive Profiling)

### A1) Web (Unauthenticated): 100% showcase/read-only
- [x] ✅ Event landing is read-only in tenant-public runtime posture (`kIsWeb` gates + browser request logs in manual lane).
- [x] ✅ Invite landing is promotion-gated in Flutter web UI with no accept/decline actions; note: `main.dart.js` string presence of legacy symbols is treated as bundle residue, not runtime authorization path.
- [x] ✅ Promotion CTA uses canonical copy `Baixe o App para Confirmar`; dynamic tenant store/open wiring is backend-resolved via `/open-app` + `settings.app_links`, and the release target is the app-promotion/store experience rather than the temporary pre-MVP waitlist form.
- [x] ✅ Web does not mint anonymous identity for invite conversion path.
- [x] ✅ Web preserves invite attribution plus the originally requested valid redirect path through promotion/open-app handoff; unsafe or unsupported paths still fall back to `/`.
- [x] ✅ Trust/auth gates on web tenant-public surfaces hand off to app promotion (no web login continuation).
- [x] ✅ Web hard/auth gates converge on one route-based promotion screen instead of mixed modal/route behavior.
- [x] ✅ Identity-owned web route `/profile` follows the same V1 rule: unauthenticated web access promotes the app instead of continuing to web auth.
- [x] ✅ Tenant-public Home on web hides identity-dependent convenience affordances while unauthenticated (direct `Account Workspace` entry from Home and the Agenda invite/confirmed filter); they are not part of the V1 promotion lane.
- [x] ✅ Map browsing posture remains read-only for web tenant-public flow.

### A2) Web (Authenticated): QR-only promoted session
- [x] ✅ Authenticated web behavior is no longer product-undefined: once the user has a QR-authenticated web session, the user is in the normal authenticated web posture for that surface.
- [x] ✅ Web login is exclusively QR-based from an already promoted app identity; web-native email/password/social login stays out of scope.
- [ ] ⚪ QR login delivery and authenticated web session bootstrap are owned by `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`, not by this Android release gate.

### A3) App (Flutter): progressive profiling conversion surface
- [ ] 🟡 Deferred deep link preserves invite `code` and route intent across install and first open on Android in Flutter implementation; local backend intent handoff now targets the installed app directly, keeps Play Store fallback for explicit store/open CTA clicks, and uses Guard fallback for pre-Guard hard-gate action clicks when the app is absent. Manual device/stage revalidation is still pending after the prior Guard failure.
- [x] ✅ First open consumes backend `target_path` and restores the intended routed target locally (`/invite?code=...` for invite context, otherwise the preserved valid redirect path); Android physical store/install validation remains final-phase.
- [ ] ⚪ iOS deferred deep-link capture is explicitly sequenced into fast-follow (`TODO-ios-universal-links-production-validation.md`).
- [x] ✅ App creates/resumes anonymous identity (device-bound) before anonymous invite decision.
- [x] ✅ Anonymous decision UI allows accept/decline without immediate auth and now uses canonical backend share-accept flow for `share:{code}` ids.
- [x] ✅ Anonymous app baseline is explicit in the contract: invite preview, invite accept/decline, feed browsing, map browsing, and favorites may continue without forced login.
- [x] ✅ Authenticated app upgrade is owned by the phone-OTP contract (`TODO-store-release-phone-otp-auth-and-contact-match.md`); email/social login are outside this release policy.
- [ ] 🟡 Post-accept anonymous app baseline (feed browsing, map browsing, favorites) still needs full validation pass on top of the invite preview/decision flow.
- [ ] 🟡 Favorite toggles/readback still have an implementation gap against the baseline today; delivery must remove current auth gating and prove anonymous favorites persist and render correctly after bootstrap.
- [x] ✅ Identity-owned app route `/profile` remains auth-gated in native runtime; anonymous app access continues to auth/login, not promotion fallback.

### A4) V1 restricted actions (Auth Wall required in app)
- [x] ✅ Identity-owned app routes such as `/profile` require authenticated identity.
- [x] ✅ Send invites (`Bora?`) require authenticated identity.
- [x] ✅ Presence/check-in boundaries require authenticated identity when attempted from app trust surfaces.
- [x] ✅ Physical check-in feature delivery remains deferred to VNext (`TODO-vnext-event-checkin.md`); web still treats these attempts as app-promotion gates in V1.
- [x] ✅ Favorites are not blanket Auth Wall actions in this contract; anonymous app usage may include favorites.

Rationale: maximize top-of-funnel invite conversion with lowest entry friction while preserving trust actions behind authenticated identity.

---

## B) Team Implementation Tracks

### B1) Web Team (Showcase + Promotion)
- [x] ✅ Remove web invite acceptance flow and web conversion mutation from tenant-public runtime behavior.
- [ ] 🟡 Replace the current pre-MVP tester-waitlist guard experience on `/baixe-o-app` with the canonical app-promotion/store handoff UX.
- [ ] 🟡 Ensure install/open links preserve attribution `code` through store redirection using dynamic tenant store targets (Android+iOS) — automated wiring is closed; real-device store destination remains manual.
- [x] ✅ Ensure install/open links preserve requested redirect path when promotion comes from direct detail routes or guard-triggered targets, not only invite landing context.
- [x] ✅ Deterministic handoff target rule is locally closed: invite attribution, safe public/detail paths, and allowed app auth-owned paths are preserved; invalid paths fall back to `/`.
- [x] ✅ Web map remains read-only in V1 posture.
- [x] ✅ Tenant-public hard-gate redirects to web login were replaced by app promotion/open-app handoff.

### B2) App/Flutter Team (Progressive Profiling + Deferred Deep Linking)
- [ ] 🟡 Guarantee deferred deep link capture on first open for Android (Play Store install path) via Android Install Referrer bridge + first-open dedupe gate + backend resolver endpoint (`POST /api/v1/deep-links/deferred/resolve`).
- [x] ✅ Route first-open user to the resolved continuation target: invite flow for preserved `code`, or the requested redirect path when promotion started from event/detail or a guarded route; fallback to `/` only when no valid intent is resolved.
- [x] ✅ Anonymous acceptance UX is implemented and wired to canonical backend share-code acceptance contract.
- [ ] 🟡 Post-accept anonymous app baseline works end to end; feed browsing, map browsing, favorites, and restricted-action boundaries still need full regression validation.
- [ ] 🟡 Align favorites with the anonymous app baseline: current Flutter controllers still auth-gate favorite toggles in discovery, account-profile detail, and immersive linked-profile flows; delivery must remove that gate and validate persistence/readback under anonymous identity support.
- [x] ✅ Implement Auth Wall interception for hard-gate actions.
- [x] ✅ Ensure web platform hard-gate interception routes to installed-app handoff before app promotion fallback on implemented Flutter tenant-public surfaces (`favorite`, `send_invite` guard, invite accept, attendance boundary).
- [x] ✅ Replace remaining modal-only promotion handoffs on web hard-gate actions with the canonical promotion route/screen. The route boundary is closed; the active rendered release experience and redirect-preservation behavior still require release completion.
- [x] ✅ Route redirect after Auth Wall exists; pending-intent action replay is explicitly implemented via telemetry payload capture.

### B3) Backend Team (Anonymous Acceptance + Identity Merge)
- [x] ✅ Canonical anonymous share acceptance contract established via `POST /api/v1/invites/share/{code}/accept` and validated by backend feature tests.
- [x] ✅ Establish backend package contract for dynamic tenant store routing + first-open deep-link resolver (Android MVP, iOS-ready contract), and migrate existing deep-link assets/settings logic (`.well-known` + `settings.app_links` guards/registry) from host app into the package.
- [x] ✅ Preserve inviter attribution and canonical side effects on anonymous acceptance (`credited_acceptance` + idempotent accept coverage).
- [x] ✅ Anti-spam/rate-limit protections are enforced for share acceptance/creation paths (`429` + cooldown quota tests).
- [x] ✅ Anonymous -> authenticated merge now preserves invite ownership records (edges/feed projection/outbox) with explicit registration-contract proof.
- [x] ✅ Duplicate acceptance artifacts are guarded by idempotency replay tests on share-accept and standard accept paths.

---

## C) Deferred Deep Link Requirements (Critical)

- [ ] 🟡 Share link builder emits `/invite?code=...`; broader external link contract must also preserve redirect-path intent for event/detail and guard-triggered promotion targets.
- [ ] 🟡 Android install path must preserve invite attribution and redirect-path intent until first open (deferred deep link contract for V1).
- [ ] ⚪ iOS deferred capture path is fast-follow required; MVP iOS keeps installed-app universal link behavior + deterministic fallback UX.
- [x] ✅ Store/open targets are backend-resolved dynamically per tenant for Android + iOS (`settings.app_links.android.enabled`, `settings.app_links.android.store_url`, `settings.app_links.ios.enabled`, `settings.app_links.ios.store_url`), and user manually validated stage target filtering on 2026-04-30.
- [x] ✅ Web/app handoff URI selection is deterministic for both invite attribution and redirect-path intent; unsupported targets still collapse to `/`.
- [x] ✅ `/open-app` accepts explicit `platform_target` override for multi-store promotion surfaces while preserving existing deterministic handoff rules.
- [ ] ⚪ First open must emit deterministic capture result:
  - [ ] 🟡 Captured invite attribution: route to invite flow and resolve invite card for that `code`.
  - [ ] 🟡 Captured redirect-path intent: restore the originally requested app route when the promotion came from event/detail or a guarded route.
  - [ ] 🟡 Not captured: emit failure telemetry and route deterministically to `/` (never blank state).
- [x] ✅ App links/universal links contract includes invite routes (`/invite*`, `/convites*`) and is validated by platform config tests.
- [x] ✅ Store-channel bridge used for deferred path is documented with failure modes (`store_channel`, attribution-loss reasons).

---

## D) Tracking & KPI (Inverted Funnel)

### D1) Funnel stages (V1)
- [ ] ⚪ Landing
- [ ] ⚪ App Install
- [ ] ⚪ Deferred Deep Link Captured
- [ ] 🟡 Anonymous Accept (Swipe)
- [ ] 🟡 Auth Wall Triggered
- [ ] 🟡 Signup Completed

### D2) Required events
- [x] ✅ `web_invite_landing_opened`
- [x] ✅ `web_open_app_clicked` (properties: `store_channel`, `platform_target=android|ios`)
- [x] ✅ `web_install_clicked` (properties: `store_channel`, `platform_target=android|ios`)
- [x] ✅ `app_deferred_deep_link_captured` (properties: `code`, `tenant_id`, `event_id?`, `platform=android` in V1; `ios` when fast-follow deferred capture is enabled)
- [x] ✅ `app_deferred_deep_link_capture_failed` (properties: `failure_reason`, `store_channel?`, `platform=android` in V1; `ios` when fast-follow deferred capture is enabled)
- [x] ✅ `app_anonymous_invite_accepted` (properties: `code`, `event_id`, `inviter_kind`, `inviter_id`)
- [x] ✅ `app_auth_wall_triggered` (properties include the restricted `action_type` that required auth; physical check-in interception tracking is VNext)
- [x] ✅ `app_signup_completed` (properties: `source=auth_wall|direct`)

### D3) KPI set
- [ ] ⚪ Landing -> Install rate
- [ ] ⚪ Install -> Deferred Deep Link Captured rate
- [ ] ⚪ Deferred Deep Link Captured -> Anonymous Accept rate
- [ ] ⚪ Anonymous Accept -> Auth Wall Triggered rate
- [ ] ⚪ Auth Wall Triggered -> Signup Completed rate

---

## E) Guardrails

- [x] ✅ Web invite surfaces are strictly read-only in V1 (runtime behavior).
- [x] ✅ Web does not mint anonymous identities for invite conversion.
- [x] ✅ Anonymous acceptance is app-only/device-bound and merge history ownership proof is covered.
- [x] ✅ Restricted actions remain auth-gated on implemented app surfaces; favorites are not treated as blanket authenticated-only by this contract.
- [ ] ⚪ Anonymous favorites behavior is fully aligned with the canonical baseline across toggle, readback, and post-login/non-login continuity.
- [x] ✅ Web tenant-public hard gates do not continue to web auth; they promote app handoff.
- [x] ✅ Physical check-in feature stays in VNext delivery scope.
- [ ] ⚪ Any expansion of web conversion behavior requires explicit contract update and roadmap/TODO sync.

---

## F) Definition of Done

- [x] ✅ Web invite landing has promotion-only CTA and no accept path.
- [x] ✅ Web hard-gate actions in tenant-public surfaces (`favorite`, `send_invite`, invite accept, attendance boundary attempts) resolve to installed-app/open-app handoff before promotion fallback, not web login.
- [ ] ⚪ Active `/baixe-o-app` experience is real app promotion/store handoff for the Android gate, not the legacy pre-MVP waitlist form.
- [ ] ⚪ Deferred deep link is captured on first open and resolves to the intended continuation target (invite and/or preserved redirect path).
- [ ] ⚪ V1 release gate: Android deferred deep-link capture is closed; iOS deferred capture remains fast-follow with documented fallback behavior.
- [x] ✅ Anonymous app acceptance is implemented in app flow and validated against canonical share-accept backend contract.
- [ ] 🟡 Anonymous app baseline beyond the invite decision flow is validated explicitly: feed browsing, map browsing, and favorites continue without forced login until a restricted action is attempted.
- [ ] ⚪ Anonymous favorites are exercisable without forced login and reflect correctly in favorites read surfaces for the current anonymous identity.
- [x] ✅ Auth Wall is triggered for the implemented restricted actions; favorites are not treated as blanket auth-gated by this contract and physical check-in remains VNext.
- [x] ✅ Identity merge path at registration preserves invite ownership artifacts (`InviteEdge`, `InviteFeedProjection`, `InviteOutboxEvent`) through merge.
- [ ] 🟡 Tracking events are emitted in app/web runtime, but the release funnel-metrics proof remains open in `TODO-store-release-funnel-metrics-validation.md`.

---

## G) Validation

- [ ] 🟡 Web manual: invite landing renders read-only + the release-approved app-promotion experience with `code` propagation (browser run confirms read-only + CTA copy; active release experience + store/open destination still need final real-device validation).
- [ ] 🟡 Web manual: tenant-public hard-gate attempts (`favorite`, `send_invite`, invite accept, attendance boundary) never open web login; on Android web they attempt installed-app handoff first and use promotion Guard only as fallback (browser run confirms no `/auth/login` fallback and no web mutation writes, with deterministic handoff routing behavior still pending final real-device store validation).
- [ ] ⚪ App manual: install via invite link -> first open -> invite card -> anonymous accept -> navigate feed/map/favorites -> restricted action triggers auth wall.
- [ ] ⚪ App manual: anonymous user can favorite/unfavorite from discovery, account-profile detail, and immersive linked-profile surfaces without forced login, and favorites read surfaces reflect the same anonymous identity state.
- [ ] ⚪ App/manual web-to-app redirect validation: direct event/detail route or guarded route on web -> promotion -> install/open -> intended route is restored in app (with native auth continuation when the target requires it).
- [x] ✅ Backend automated: anonymous accept path, idempotent replay, anti-spam/rate-limit, and merge ownership preservation are covered.
- [x] ✅ Flutter automated: auth wall interception, invite flow behavior, deferred-link capture (backend resolver contract), and deterministic route override/fallback guards are covered.
- [ ] ⚪ Flutter automated: anonymous favorite toggle/readback coverage for discovery, account-profile detail, and immersive linked-profile entrypoints.
- [ ] ⚪ Data validation: event stream integrity for inverted funnel and deduplication checks, coordinated through `TODO-store-release-funnel-metrics-validation.md`.

### G3) Manual QA Update (2026-04-30)

- [x] ✅ Web Boundary is validated for the behavior originally requested in this TODO.
- [x] ✅ Additive publication settings are locally implemented and covered by Flutter/Laravel tests: tenant admin exposes `Publicação` with `Android ativo`, `URL Android`, `iOS ativo`, and `URL iOS`.
- [x] ✅ Promotion surfaces consume publication settings dynamically in automated tests. A platform with `active=false` or an empty URL is not offered as a live store CTA.
- [x] ✅ Stage/manual validation passed for the real admin `Publicação` surface and promotion/open-app active-target filtering on 2026-04-30.
- [x] ✅ Installed-app hard-gate handoff is locally fixed and ADB/device revalidated: with the app already installed, `/open-app` invite accept, attendance confirmation, account-profile favorite, and invite sharing targets resolve to the Android app before the Guard; the Guard remains fallback for absent app, and explicit store/open CTA clicks still preserve Play Store fallback. The actual Discovery card/favorite finger-tap semantics remain the next manual QA focus.

### G4) Latest Automated Evidence (2026-04-30)

- [x] ✅ Flutter settings/promotion publication gate: `fvm flutter test test/domain/tenant_admin/settings/tenant_admin_app_links_settings_test.dart test/infrastructure/dal/dto/app_data_dto_test.dart test/infrastructure/repositories/tenant_admin_settings_repository_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` passed with `99/99`.
- [x] ✅ Flutter analyzer: `fvm dart analyze --format machine` passed cleanly after replacing primitive domain fields with Value Objects.
- [x] ✅ Flutter web build: `bash scripts/build_web.sh ../web-app dev` passed and refreshed the derived web bundle.
- [x] ✅ Laravel publication/open-app/settings gate: `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Feature/Settings/SettingsKernelControllerTest.php tests/Api/v1/Tenants/Branding/ApiV1EnvironmentApiTest.php` passed with `57/57` tests and `335` assertions.
- [x] ✅ Laravel installed-app Android intent handoff: `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php` passed with `7` tests and `77` assertions. The Android `/open-app` response now uses `intent://tenant/...#Intent;scheme=...;package=...;S.browser_fallback_url=...;end`; explicit store/open CTA clicks keep Play Store referrer fallback, while pre-Guard action clicks can request `fallback=promotion` so absent-app browsers fall back to `/baixe-o-app?redirect=...`.
- [x] ✅ Flutter pre-Guard action handoff gate: `fvm flutter test test/presentation/shared/widgets/app_promotion_dialog_test.dart test/application/router/support/route_redirect_path_test.dart` passed with `33/33`; `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/map/screens/map_screen/widgets/event_poi_detail_card_test.dart` passed with `80/80`; `fvm dart analyze --format machine` passed clean.

### G1) Latest Automated Evidence (2026-03-30)
- [x] ✅ Flutter: `fvm dart analyze --format machine` (clean), `fvm flutter test test/application/router/guards/auth_route_guard_test.dart` (4 passed), `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_effects_test.dart` (3 passed), `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` (15 passed).
- [x] ✅ Flutter (web hard-gate regression): `fvm flutter test test/application/router/guards/auth_route_guard_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart` (13 passed), plus analyzer clean after web-gate redirection adjustments.
- [x] ✅ Backend: `docker compose exec -T -e DB_URI=<...> -e DB_URI_LANDLORD=<...> -e DB_URI_TENANTS=<...> app php artisan test tests/Feature/Invites/InvitesFlowTest.php --filter="share_accept|share_materialize_rejects_anonymous_user|share_preview_resolves_without_authentication"` (4 passed, including canonical anonymous share accept + idempotent replay).
- [x] ✅ Flutter deferred-link capture gate (Android, backend-resolver contract): `fvm flutter test test/infrastructure/repositories/deferred_link_repository_test.dart test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart test/presentation/shared/init/screens/init_screen/init_screen_test.dart` (12 passed, including resolver-backed first-open `/invite?code=...` override + deterministic fallback behavior guard).
- [x] ✅ Flutter promotion handoff resolver gate: `fvm flutter test test/presentation/shared/widgets/app_promotion_dialog_test.dart` (passed; verifies `/open-app` invite context, event/detail continuation, auth-owned app continuation, and invalid-path fallback).
- [x] ✅ Flutter web hard-gate promotion path resolver gate: `fvm flutter test test/application/router/support/route_redirect_path_test.dart` (passed; verifies invite code preservation, public/detail route preservation, map query preservation, auth-owned filtering, and unsafe fallback).
- [x] ✅ Backend `/open-app` + deferred resolver redirect-intent gate: `docker compose exec -T -e DB_URI='mongodb://mongo:27017/landlord_test?replicaSet=rs0' -e DB_URI_LANDLORD='mongodb://mongo:27017/landlord_test?replicaSet=rs0' -e DB_URI_TENANTS='mongodb://mongo:27017/tenants_test?replicaSet=rs0' app php artisan test tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php` (9 passed, 52 assertions).
- [x] ✅ Flutter deferred first-open target-path gate: `fvm flutter test test/infrastructure/repositories/deferred_link_repository_test.dart test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart test/application/router/support/route_redirect_path_test.dart test/presentation/shared/widgets/app_promotion_dialog_test.dart` (45 passed).
- [x] ✅ Android build lane smoke (compile): `./gradlew :app:compileBellugaDebugKotlin` (BUILD SUCCESSFUL, validates Install Referrer bridge wiring compiles in Belluga flavor).
- [x] ✅ Backend deep-link package gate: `docker compose exec -T -e DB_URI=<...> -e DB_URI_LANDLORD=<...> -e DB_URI_TENANTS=<...> app php artisan test tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php tests/Api/v1/Admin/ApiV1WellKnownAssociationAdminTest.php tests/Feature/Settings/SettingsKernelControllerTest.php tests/Unit/Settings/SettingsPackageBindingsTest.php` (38 passed, covering packageized `.well-known`, `settings.app_links` guard/registry, `/open-app` resolver behavior, and deferred resolver endpoint).
- [x] ✅ Flutter analyzer gate: `fvm dart analyze --format machine` (clean).
- [x] ✅ Flutter web invite flow + promotion gates: `fvm flutter test test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/application/router/support/route_redirect_path_test.dart test/presentation/shared/widgets/app_promotion_dialog_test.dart` (19 passed).
- [x] ✅ Backend merge ownership proof: `docker compose exec -T -e DB_URI=<...> -e DB_URI_LANDLORD=<...> -e DB_URI_TENANTS=<...> app php artisan test tests/Api/v1/Tenants/Auth/T1PasswordRegistrationTest.php` (9 passed, includes invite ownership migration across `InviteEdge`, `InviteFeedProjection`, `InviteOutboxEvent`).
- [x] ✅ Backend anti-spam/idempotency proof: `docker compose exec -T -e DB_URI=<...> -e DB_URI_LANDLORD=<...> -e DB_URI_TENANTS=<...> app php artisan test tests/Feature/Invites/InvitesFlowTest.php --filter="share_(accept_by_code_allows_anonymous_user_and_uses_canonical_acceptance|accept_replays_by_idempotency_key_without_creating_duplicate_edges|daily_limit_rejection_returns_structured_429_payload|target_cooldown_rejection_returns_retry_metadata|cooldown_rejection_does_not_consume_daily_share_quota_counter)"` (5 passed).
- [x] ✅ Web artifact regeneration + scan: `./scripts/build_web.sh` completed; generated `web-app/main.dart.js` still contains legacy symbol strings but runtime/browser behavior and route guards enforce read-only + app-promotion policy.
- [x] ✅ Route-based promotion boundary + store-target override regression gate (2026-03-31): `fvm flutter test test/application/router/guards/auth_route_guard_test.dart test/application/router/modules/profile_module_test.dart test/application/router/support/route_redirect_path_test.dart test/presentation/shared/widgets/app_promotion_dialog_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart` (33 passed) + `fvm dart analyze --format machine` (clean).
- [x] ✅ Backend `/open-app` explicit platform override gate (2026-03-31): `../laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php` (4 passed) + `docker compose exec -T app php vendor/bin/pint --test packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php packages/belluga/belluga_deep_links/src/Http/Web/Controllers/OpenAppRedirectController.php tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php` (PASS).
- [ ] 🟡 Manual validations are now in progress (`H6`): web browser lane executed; app install/deferred lane and store deep-link destination validation remain open.

### G2) Latest Manual Evidence (2026-03-30)
- [x] ✅ Web invite landing (`/invite?code=TESTCODE123`) renders promotion-only surface with canonical CTA copy `Baixe o App para Confirmar` (no accept/decline UI).
- [x] ✅ Web hard-gate browser runs (`favorite`/attendance boundary attempts) did not navigate to `/auth/login`.
- [x] ✅ Web hard-gate browser runs did not emit invite/favorite/presence mutation writes (`POST /api/v1/invites/*`, `POST /api/v1/favorites`, attendance confirm mutation not observed in request log for the tested flows).
- [x] ✅ Browser-observed hard-gate handoff route remains deterministic; local contract now preserves valid non-invite continuation paths and falls back only for unsupported/unsafe contexts.
- [ ] ⚪ Store/open-app destination verification is still pending on real device/browser where external app-store/app-scheme launch can be asserted end-to-end.

---

## H) Completion Plan (To Reach ✅ Production-Ready)

### H0) Consolidated Execution Order (Single Thread)
1. **Replace the current `/baixe-o-app` tester-waitlist guard experience with the real app-promotion/store handoff path.**
   - Required: release path uses the app-promotion/store experience, not the legacy pre-MVP waitlist variant.
   - Exit criteria: the active guard boundary matches the intended Android publication flow.
2. **Extend handoff preservation from invite-only context to full continuation intent.**
   - Required: event/detail routes and guard-triggered redirects survive promotion + deferred first-open resolution, not only invite share-code context.
   - Exit criteria: manual/device validation can prove route restoration beyond `/invite?code=...`.
3. **Run H6 manual install/deferred matrix (Android MVP).**
   - Required: real device/browser where external store launch + first-open attribution can be asserted.
   - Exit criteria: invite link or guarded/detail-route promotion -> install/open -> deferred capture -> intended target -> anonymous accept/continuation -> restricted action -> auth wall.
4. **Finalize the sibling telemetry matrix/KPI validation lane on top of H6 evidence.**
   - Required: telemetry sink/query access for funnel verification.
   - Exit criteria: end-to-end event integrity + dedupe checks for `web_open_app_clicked`, `web_install_clicked`, deferred capture events, anonymous accept, auth wall, signup.
5. **Perform final DoD sweep (Section F) only after steps 1-4.**
   - Rule: no checkbox in F is closed without linked evidence in G1/G2.

### H1) Phase 0 — Contract Lock (Backend + Flutter + Web + Data)
- [x] ✅ Lock canonical anonymous-share acceptance contract:
  - Recommended: add `POST /api/v1/invites/share/{code}/accept` for anonymous acceptance and keep `POST /invites/{invite_id}/accept` canonical for materialized invite ids.
- [x] ✅ Lock dynamic tenant store-routing + first-open resolver contract (Laravel package + Flutter consumer boundary), including package-owned migration boundaries for pre-existing deep-link components (`.well-known` + `settings.app_links`).
- [x] ✅ Lock idempotency and attribution requirements (`invite_id`, `code`, `inviter_kind`, `inviter_id`, `receiver_identity_state`).
- [x] ✅ Lock identity-merge expected behavior for accepted invites (post-merge invite ownership preservation contract).

### H2) Phase 1 — Backend Delivery (Critical Path)
- [x] ✅ Implement and test anonymous share acceptance canonicalization (no `share:{code}` mismatch at decision time).
- [x] ✅ Create `belluga_deep_links` package and migrate existing host deep-link surfaces (`DeepLinkAssociationService`, app-links patch guard/registry wiring) into package-owned services/contracts.
- [x] ✅ Preserve inviter attribution + canonical side effects/notifications for anonymous acceptance.
- [x] ✅ Add anti-spam/rate-limit protections specific to anonymous accept/share path.
- [x] ✅ Validate merge behavior preserves accepted invite history/attribution after signup.
- [x] ✅ Add race-safe guards against duplicate accept artifacts on retry/merge.

### H3) Phase 2 — Flutter Delivery (After Backend Contract Lock)
- [x] ✅ Wire app anonymous accept flow to backend canonical share-accept contract.
- [ ] 🟡 Implement deferred deep link capture on Android store install -> first open (Install Referrer bridge wired; awaiting lane/manual closure).
- [ ] 🟡 Route first open deterministically to invite card for captured `code`; deterministic fallback when not captured (Android in V1, iOS fallback only).
- [x] ✅ Replace Flutter Web hard-gate auth/login redirects in tenant-public flows with installed-app/open-app handoff before app promotion fallback.
- [x] ✅ Implement pending-intent replay after Auth Wall (redirect restoration exists; explicit action replay explicitly handled via payload restoration).
- [x] ✅ Align web CTA copy in Flutter surfaces to canonical text `Baixe o App para Confirmar` and wire real store/open links.

### H4) Phase 3 — Web Delivery (Source-Level, Not Artifact-Only)
- [x] ✅ Remove invite conversion mutation paths from web runtime behavior (tenant-public policy).
- [x] ✅ Keep invite landing strictly preview + promotion CTA with `code` propagation.
- [x] ✅ Validate map/web trust-action surfaces remain read-only in V1 and hard gates hand off to app promotion (no web login continuation).
- [x] ✅ Publish source/runtime evidence and generated artifact scan note.

### H5) Phase 4 — Data/Telemetry + KPI Funnel (Tracked In Sibling TODO)
- [x] ✅ Emit required events end-to-end in runtime instrumentation: `web_invite_landing_opened`, `web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`, `app_anonymous_invite_accepted`, `app_auth_wall_triggered`, `app_signup_completed`.
- [x] ✅ Add deduplication/idempotency strategy for funnel events (accept/auth wall/signup).
- [ ] ⚪ Validate KPI pipeline stages: Landing -> Install -> Deferred Capture -> Anonymous Accept -> Auth Wall -> Signup via `TODO-store-release-funnel-metrics-validation.md`.

### H6) Phase 5 — Final Validation and DoD Closure
- [ ] ⚪ Run manual validation matrix (Web/App first-open/install/deep-link/auth-wall flow).
- [x] ✅ Run automated suites (Backend + Flutter targeted suites executed; telemetry sink validation remains open).
- [ ] ⚪ Close DoD checkboxes only with linked evidence (tests/logs/screens/queries) per item.

## Local Delivery Notes (2026-04-28)

- **Implemented non-ADB T1 closure:** `/baixe-o-app` now defaults to the real `appDownload` promotion/store-handoff experience, while the tester waitlist remains only as an explicit override.
- **Implemented continuation hardening:** promotion handoff now preserves valid invite, public detail, map, and auth-owned app continuation paths, while rejecting external/scheme/blocked redirects and bounding auth redirect unwrapping.
- **Implemented installed-app Android handoff hardening:** `/open-app` now emits Android intent URLs for published Android targets when a package identifier is known. Explicit store/open CTA clicks keep the existing Play Store referrer/deferred-install fallback. Pre-Guard hard-gate action clicks now call `/open-app?fallback=promotion`, so installed apps receive the preserved target directly and absent-app browsers fall back to the Guard route instead of making the Guard the first step.
- **Implemented pre-Guard action targeting:** web `favorite` clicks target the partner profile, invite accept targets the invite occurrence, attendance confirm targets the selected occurrence, and `send_invite` keeps the current event target.
- **Implemented anonymous favorites alignment:** discovery, public Account Profile detail, and immersive linked-profile favorite actions no longer force auth, without changing restricted action gates.
- **Triple audit gate:** `foundation_documentation/artifacts/t1-web-to-app-review-packet-triple-audit-20260428T142017Z/round-02/round-summary.md` is clean with zero findings across elegance, performance, and test-quality.
- **Claude CLI auxiliary review:** `foundation_documentation/artifacts/claude-cli-reviews/T1-web-to-app-cli-review.md` returned no blockers and listed only accepted debt; the later round-02 attempt hit account limits and is operationally non-blocking under the 2026-04-28 user instruction.
- **Deferred runtime evidence:** real Play Store/install/deferred-link validation remains intentionally deferred to the consolidated ADB phase.
