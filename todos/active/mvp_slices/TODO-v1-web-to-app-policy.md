# TODO (V1): Web-to-App Policy + Progressive Profiling

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active. All non-manual engineering tracks were advanced on 2026-03-30 (backend deep-link packageization, `/open-app` resolver, deferred resolver API, Flutter web hard-gates + telemetry wiring, merge ownership preservation for invites, anti-spam/idempotency evidence). Remaining closure lanes are manual/external: real-device store/deferred validation and data-pipeline KPI verification.
**Owners:** Delphi (Product/Flutter) + Backend Team + Web Team + Data Team  
**Goal:** invert invite conversion from auth-first to anonymous-first while keeping web strictly showcase/read-only and enforcing trust actions behind an Auth Wall.

---

## Decision Baseline (Consolidated 2026-03-29)

- [x] ✅ `W2A-D01` MVP deferred deep link capture is Android-only (Play Store install path). iOS deferred capture remains VNext.
- [x] ✅ `W2A-D02` Store targets must be dynamic per tenant for both Android and iOS (backend-resolved contract; no hardcoded store URLs in web/app clients).
- [x] ✅ `W2A-D03` Deferred pipeline ownership is split: Laravel package resolves redirect/attribution contract; Flutter captures first-open Android signal and consumes resolver result.
- [x] ✅ `W2A-D04` When first-open deferred capture does not resolve an invite `code`, fallback route is canonical tenant home (`/`).
- [x] ✅ `W2A-D05` `store_channel` is required on deferred-funnel telemetry surfaces (`web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`).
- [x] ✅ `W2A-D06` Web hard-gate handoff target is deterministic and context-aware: only invite-landing context (`/invite` or `/convites`) with valid `code` keeps `/invite?code=...`; all other contexts hand off to `/`.
- [x] ✅ `W2A-D07` Backend deep-link architecture is package-owned end-to-end: existing host logic for `.well-known` app association, `settings.app_links` registry/validation, web promotion handoff resolution, and deferred first-open resolver must live in `belluga_deep_links` (host app only wires routes/adapters/contracts).
- [x] ✅ `W2A-D08` Web identity boundaries must converge on one route-based promotion surface: unauthenticated web route gates (for example `/profile`) and action hard-gates (`favorite`, `send_invite`, attendance boundary) resolve to the same Flutter promotion route/screen, not modal-vs-route divergence.
- [x] ✅ `W2A-D09` The canonical promotion route must support explicit Android + iOS store targeting, and `/open-app` must accept `platform_target=android|ios` override so desktop/unknown web can open the intended tenant-dynamic store target. The promotion screen must consume runtime environment branding and may adaptively render a single Android/iOS CTA when browser platform inference is reliable, with dual-badge fallback otherwise. Apple badge/artwork usage must follow Apple’s published App Store Marketing Guidelines.

---

## References
- `foundation_documentation/system_roadmap.md` (Web-to-App Promotion Policy section)
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-first-release.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-parking-lot.md` (deferred workspace/check-in scopes)
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-event-checkin.md` (physical check-in feature delivery)
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-ios-universal-links-production-validation.md` (iOS deferred deep link/runtime validation)

---

## A) V1 Policy Baseline (Progressive Profiling)

### A1) Web (Unauthenticated): 100% showcase/read-only
- [x] ✅ Event landing is read-only in tenant-public runtime posture (`kIsWeb` gates + browser request logs in manual lane).
- [x] ✅ Invite landing is promotion-gated in Flutter web UI with no accept/decline actions; note: `main.dart.js` string presence of legacy symbols is treated as bundle residue, not runtime authorization path.
- [x] ✅ Promotion CTA uses canonical copy `Baixe o App para Confirmar`; dynamic tenant store/open wiring is backend-resolved via `/open-app` + `settings.app_links`.
- [x] ✅ Web does not mint anonymous identity for invite conversion path.
- [x] ✅ Web propagates `code` only from invite context (`/invite|/convites`) and falls back to `/` for all other contexts.
- [x] ✅ Trust/auth gates on web tenant-public surfaces hand off to app promotion (no web login continuation).
- [x] ✅ Web hard/auth gates converge on one route-based promotion screen instead of mixed modal/route behavior.
- [x] ✅ Identity-owned web route `/profile` follows the same V1 rule: unauthenticated web access promotes the app instead of continuing to web auth.
- [x] ✅ Tenant-public Home on web hides identity-dependent convenience affordances while unauthenticated (direct `Account Workspace` entry from Home and the Agenda invite/confirmed filter); they are not part of the V1 promotion lane.
- [x] ✅ Map browsing posture remains read-only for web tenant-public flow.

### A2) Web (Authenticated): workspace-only (VNext/backlog)
- [ ] ⚪ Defer authenticated workspace capabilities (event management, memberships/team management, invite metrics dashboards) to `foundation_documentation/todos/active/vnext_slices/TODO-vnext-parking-lot.md`; out of V1 DoD scope.
- [x] ✅ Keep tenant-public conversion/trust actions out of web-auth continuation; web auth is not a fallback for invite/feed/map hard gates in V1.
- [ ] ⚪ If/when authenticated web is introduced via app-approved QR flow, hidden tenant-public Home affordances may reappear for authenticated sessions without changing the V1 hard-gate policy.

### A3) App (Flutter): progressive profiling conversion surface
- [ ] 🟡 Deferred deep link preserves invite `code` across install and first open on Android in Flutter implementation; pending end-to-end/manual closure for V1 gate.
- [ ] 🟡 First open with invite `code` renders the invite card directly on routed `/invite?code=...`; Android install-path capture is implemented in Flutter and awaiting manual lane closure.
- [ ] ⚪ iOS deferred deep-link capture is explicitly deferred to VNext (`TODO-vnext-ios-universal-links-production-validation.md`).
- [x] ✅ App creates/resumes anonymous identity (device-bound) before anonymous invite decision.
- [x] ✅ Anonymous decision UI allows accept/decline without immediate auth and now uses canonical backend share-accept flow for `share:{code}` ids.
- [ ] 🟡 Post-accept anonymous navigation is available; read-only trust-action posture is partially enforced and still needs full validation pass.
- [x] ✅ Identity-owned app route `/profile` remains auth-gated in native runtime; anonymous app access continues to auth/login, not promotion fallback.

### A4) V1 hard-gate actions (Auth Wall required)
- [x] ✅ Favorite actions (artists, venues, events).
- [x] ✅ Send invites (`Bora?`).
- [x] ✅ Presence/check-in boundaries require authenticated identity when attempted from app trust surfaces.
- [x] ✅ Physical check-in feature delivery remains deferred to VNext (`TODO-vnext-event-checkin.md`); web still treats these attempts as app-promotion gates in V1.

Rationale: maximize top-of-funnel invite conversion with lowest entry friction while preserving trust actions behind authenticated identity.

---

## B) Team Implementation Tracks

### B1) Web Team (Showcase + Promotion)
- [x] ✅ Remove web invite acceptance flow and web conversion mutation from tenant-public runtime behavior.
- [x] ✅ Invite landing promotion UX + canonical CTA copy are closed.
- [ ] 🟡 Ensure install/open links preserve attribution `code` through store redirection using dynamic tenant store targets (Android+iOS) — automated wiring is closed; real-device store destination remains manual.
- [x] ✅ Deterministic handoff target rule is enforced (`/invite?code=...` only in invite context, else `/`).
- [x] ✅ Web map remains read-only in V1 posture.
- [x] ✅ Tenant-public hard-gate redirects to web login were replaced by app promotion/open-app handoff.

### B2) App/Flutter Team (Progressive Profiling + Deferred Deep Linking)
- [ ] 🟡 Guarantee deferred deep link capture on first open for Android (Play Store install path) via Android Install Referrer bridge + first-open dedupe gate + backend resolver endpoint (`POST /api/v1/deep-links/deferred/resolve`).
- [ ] 🟡 Route first-open user directly to invite flow for resolved `code` and fallback to `/` when not resolved (resolver-backed in Flutter repository/controller tests; pending manual install lane closure).
- [x] ✅ Anonymous acceptance UX is implemented and wired to canonical backend share-code acceptance contract.
- [ ] 🟡 Post-accept anonymous navigation works; read-only behavior still needs full regression validation.
- [x] ✅ Implement Auth Wall interception for hard-gate actions.
- [x] ✅ Ensure web platform hard-gate interception routes to app promotion handoff instead of web login fallback on implemented Flutter tenant-public surfaces (`favorite`, `send_invite` guard, attendance boundary).
- [x] ✅ Replace remaining modal-only promotion handoffs on web hard-gate actions with the canonical promotion route/screen and explicit store selection UX.
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

- [ ] 🟡 Share link builder emits `/invite?code=...`; broader external link contract still needs explicit verification.
- [ ] 🟡 Android install path must preserve `code` until first open (deferred deep link contract for V1).
- [ ] ⚪ iOS deferred capture path is VNext; MVP iOS keeps installed-app universal link behavior + deterministic fallback UX.
- [ ] 🟡 Store/open targets are backend-resolved dynamically per tenant for Android + iOS (`settings.app_links.android.store_url`, `settings.app_links.ios.store_url`), pending end-to-end manual store destination validation.
- [x] ✅ Web/app handoff URI selection is deterministic and context-aware (invite-landing context + valid `code` => `/invite?code=...`, all others => `/`) via `/open-app` resolver path.
- [x] ✅ `/open-app` accepts explicit `platform_target` override for multi-store promotion surfaces while preserving existing deterministic handoff rules.
- [ ] ⚪ First open must emit deterministic capture result:
  - [ ] 🟡 Captured: route to invite flow and resolve invite card for that `code`.
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
- [x] ✅ `app_deferred_deep_link_captured` (properties: `code`, `tenant_id`, `event_id?`, `platform=android` in V1; `ios` when VNext deferred capture is enabled)
- [x] ✅ `app_deferred_deep_link_capture_failed` (properties: `failure_reason`, `store_channel?`, `platform=android` in V1; `ios` when VNext deferred capture is enabled)
- [x] ✅ `app_anonymous_invite_accepted` (properties: `code`, `event_id`, `inviter_kind`, `inviter_id`)
- [x] ✅ `app_auth_wall_triggered` (properties: `action_type=favorite|send_invite`; physical check-in interception tracking is VNext)
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
- [x] ✅ Trust actions are auth-gated on implemented app surfaces (`favorite`, `send_invite`, attendance boundary).
- [x] ✅ Web tenant-public hard gates do not continue to web auth; they promote app handoff.
- [x] ✅ Physical check-in feature stays in VNext delivery scope.
- [ ] ⚪ Any expansion of web conversion behavior requires explicit contract update and roadmap/TODO sync.

---

## F) Definition of Done

- [x] ✅ Web invite landing has promotion-only CTA and no accept path.
- [x] ✅ Web hard-gate actions in tenant-public surfaces (`favorite`, `send_invite`, attendance boundary attempts) resolve to app promotion/open-app path, not web login.
- [ ] ⚪ Deferred deep link is captured on first open and resolves to the intended invite flow.
- [ ] ⚪ V1 release gate: Android deferred deep-link capture is closed; iOS deferred capture remains VNext with documented fallback behavior.
- [x] ✅ Anonymous app acceptance is implemented in app flow and validated against canonical share-accept backend contract.
- [x] ✅ Auth Wall is triggered for all implemented V1 hard-gate actions (`favorite`, `send_invite`); physical check-in remains VNext.
- [x] ✅ Identity merge path at registration preserves invite ownership artifacts (`InviteEdge`, `InviteFeedProjection`, `InviteOutboxEvent`) through merge.
- [ ] 🟡 Tracking events are emitted in app/web runtime, but KPI funnel validation in telemetry sink remains open.

---

## G) Validation

- [ ] 🟡 Web manual: invite landing renders read-only + install/open CTA with `code` propagation (browser run confirms read-only + CTA copy; store/open deep-link destination still needs real-device validation).
- [ ] 🟡 Web manual: tenant-public hard-gate attempts (`favorite`, `send_invite`, attendance boundary) never open web login; they always show/open app promotion handoff (browser run confirms no `/auth/login` fallback and no web mutation writes, with deterministic handoff routing behavior still pending final real-device store validation).
- [ ] ⚪ App manual: install via invite link -> first open -> invite card -> anonymous accept -> navigate feed/map -> hard-gate action (`favorite` or `send_invite`) triggers auth wall.
- [x] ✅ Backend automated: anonymous accept path, idempotent replay, anti-spam/rate-limit, and merge ownership preservation are covered.
- [x] ✅ Flutter automated: auth wall interception, invite flow behavior, deferred-link capture (backend resolver contract), and deterministic route override/fallback guards are covered.
- [ ] ⚪ Data validation: event stream integrity for inverted funnel and deduplication checks.

### G1) Latest Automated Evidence (2026-03-30)
- [x] ✅ Flutter: `fvm dart analyze --format machine` (clean), `fvm flutter test test/application/router/guards/auth_route_guard_test.dart` (4 passed), `fvm flutter test test/presentation/common/auth/screens/auth_login_screen/auth_login_effects_test.dart` (3 passed), `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` (15 passed).
- [x] ✅ Flutter (web hard-gate regression): `fvm flutter test test/application/router/guards/auth_route_guard_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart` (13 passed), plus analyzer clean after web-gate redirection adjustments.
- [x] ✅ Backend: `docker compose exec -T -e DB_URI=<...> -e DB_URI_LANDLORD=<...> -e DB_URI_TENANTS=<...> app php artisan test tests/Feature/Invites/InvitesFlowTest.php --filter="share_accept|share_materialize_rejects_anonymous_user|share_preview_resolves_without_authentication"` (4 passed, including canonical anonymous share accept + idempotent replay).
- [x] ✅ Flutter deferred-link capture gate (Android, backend-resolver contract): `fvm flutter test test/infrastructure/repositories/deferred_link_repository_test.dart test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart test/presentation/shared/init/screens/init_screen/init_screen_test.dart` (12 passed, including resolver-backed first-open `/invite?code=...` override + deterministic fallback behavior guard).
- [x] ✅ Flutter promotion handoff resolver gate: `fvm flutter test test/presentation/shared/widgets/app_promotion_dialog_test.dart` (3 passed, deterministic `/open-app` URI selection with invite context preserving `code` and non-invite contexts falling back to `/` without `code` propagation).
- [x] ✅ Flutter web hard-gate promotion path resolver gate: `fvm flutter test test/application/router/support/route_redirect_path_test.dart` (7 passed, context-aware rule preserves `code` only on invite routes and falls back to `/` for non-invite hard-gate contexts, with explicit `shareCode` extraction coverage).
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
- [x] ✅ Browser-observed hard-gate handoff route remained deterministic for non-invite contexts (`/` fallback), matching the current URI resolver policy.
- [ ] ⚪ Store/open-app destination verification is still pending on real device/browser where external app-store/app-scheme launch can be asserted end-to-end.

---

## H) Completion Plan (To Reach ✅ Production-Ready)

### H0) Consolidated Execution Order (Single Thread)
1. **Run H6 manual install/deferred matrix (Android MVP).**
   - Required: real device/browser where external store launch + first-open attribution can be asserted.
   - Exit criteria: invite link -> install/open -> deferred capture -> invite card -> anonymous accept -> hard-gate -> auth wall.
2. **Finalize H5 data pipeline validation on top of H6 evidence.**
   - Required: telemetry sink/query access for funnel verification.
   - Exit criteria: end-to-end event integrity + dedupe checks for `web_open_app_clicked`, `web_install_clicked`, deferred capture events, anonymous accept, auth wall, signup.
3. **Perform final DoD sweep (Section F) only after steps 1-2.**
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
- [x] ✅ Replace Flutter Web hard-gate auth/login redirects in tenant-public flows with app promotion/open-app handoff.
- [x] ✅ Implement pending-intent replay after Auth Wall (redirect restoration exists; explicit action replay explicitly handled via payload restoration).
- [x] ✅ Align web CTA copy in Flutter surfaces to canonical text `Baixe o App para Confirmar` and wire real store/open links.

### H4) Phase 3 — Web Delivery (Source-Level, Not Artifact-Only)
- [x] ✅ Remove invite conversion mutation paths from web runtime behavior (tenant-public policy).
- [x] ✅ Keep invite landing strictly preview + promotion CTA with `code` propagation.
- [x] ✅ Validate map/web trust-action surfaces remain read-only in V1 and hard gates hand off to app promotion (no web login continuation).
- [x] ✅ Publish source/runtime evidence and generated artifact scan note.

### H5) Phase 4 — Data/Telemetry + KPI Funnel
- [x] ✅ Emit required events end-to-end in runtime instrumentation: `web_invite_landing_opened`, `web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`, `app_anonymous_invite_accepted`, `app_auth_wall_triggered`, `app_signup_completed`.
- [x] ✅ Add deduplication/idempotency strategy for funnel events (accept/auth wall/signup).
- [ ] ⚪ Validate KPI pipeline stages: Landing -> Install -> Deferred Capture -> Anonymous Accept -> Auth Wall -> Signup.

### H6) Phase 5 — Final Validation and DoD Closure
- [ ] ⚪ Run manual validation matrix (Web/App first-open/install/deep-link/auth-wall flow).
- [x] ✅ Run automated suites (Backend + Flutter targeted suites executed; telemetry sink validation remains open).
- [ ] ⚪ Close DoD checkboxes only with linked evidence (tests/logs/screens/queries) per item.
