# TODO (Fast Follow): QR Login And Authenticated Web

**Classification note (2026-04-17):** this TODO is the direct execution authority for the mandatory QR-login/authenticated-web lane under `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`. It no longer exists only to state intent; it now freezes the delivery baseline so an independent no-context executor can implement the lane without reopening the anonymous-web policy.

**Scope authority note (2026-04-17):** canonical product posture already lives in `foundation_documentation/policies/web_to_app_promotion_policy.md` plus the promoted module contracts. This TODO does not own policy invention. It owns the fast-follow implementation contract for explicit QR session bootstrap, authenticated web continuation, and logout behavior after the Android web-to-app release gate closes.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active. Product posture is frozen, but there is still no dedicated QR bridge/session-bootstrap delivery lane in code. The repository still carries legacy email/password web auth surfaces while anonymous web guards promote to app. This TODO now owns the cross-stack alignment needed to make authenticated web real without weakening the anonymous web boundary.
**Owners:** Delphi (Product/Flutter) + Backend Team + Web Team
**Goal:** deliver QR-only authenticated web as a deliberate fast-follow capability: anonymous web remains promotion/read-only, explicit user-initiated QR login bootstraps a normal authenticated web session from an already promoted app identity, and logout cleanly returns the browser to the anonymous boundary.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Framing Source & Story Slice

- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-07`
- **Why this is the right current slice:** it converts a business-defined capability into one bounded execution packet instead of leaving authenticated web scattered across policy notes, roadmap remarks, and Android-release TODO references.
- **Direct-to-TODO rationale:** safe. Product posture is already approved; what remained missing was execution authority, dependency clarity, and a concrete validation matrix.

## Contract Boundary

- This TODO authorizes the implementation contract for QR-based authenticated web.
- It does not reopen whether authenticated web is allowed.
- It does not widen anonymous web beyond promotion/read-only before login.
- It does not collapse into generic account-workspace rollout or broader web-product redesign.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Business-Defined`, `Policy-Frozen`, `Sequenced-After-Android`
- **Next exact step:** freeze the QR bootstrap/session lifecycle against the current implementation snapshot below, then hand execution to a cross-stack no-context agent after `TODO-store-release-web-to-app-conversion-gate.md` reaches closure.

## Decision Baseline (Frozen 2026-04-17)

- [x] `D-01` QR login/web auth is business-defined and must not be tracked as speculative work.
- [x] `D-02` This lane is mandatory fast-follow after Android-first publication; it is not removed from scope.
- [x] `D-03` Authenticated web uses the normal logged-in web posture for that surface; QR governs only how the web session is established.
- [x] `D-04` Web-native email/password/social login is forbidden in this lane.
- [x] `D-05` Anonymous web hard/auth gates continue to promote the app; QR login is an explicit user-initiated web session bootstrap path, not the fallback for anonymous route/action guards.
- [x] `D-06` App identity remains phone-OTP only. QR login may only attach to an already promoted app identity and must resume through native OTP when the app-side approver is not yet authenticated.
- [x] `D-07` Successful QR bootstrap must preserve the originally requested authenticated route intent (for example `/profile`) and then keep the browser in the normal authenticated web posture while the session is active.
- [x] `D-08` Web logout must clear the authenticated web session and return the browser to the anonymous promotion/read-only boundary, including re-hiding authenticated-only convenience affordances.
- [x] `D-09` Broader account-workspace expansion remains separate. This TODO only needs the minimum authenticated-web delivery set required to prove the session contract and representative guarded surfaces.

## References

- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`

## Module Anchors & Decision Consolidation Targets

- **Primary module anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module anchors:** `foundation_documentation/policies/web_to_app_promotion_policy.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/system_roadmap.md`, `foundation_documentation/endpoints_mvp_contracts.md`
- **Decision consolidation targets:** if execution refines route/session/endpoint specifics, consolidate them back into the policy plus the affected module/endpoint docs in the same change set. Do not leave the final contract living only inside this TODO.

## Current Implementation Snapshot (Repository Scan 2026-04-17)

- **Anonymous web hard-gates already promote to app:** `/auth/login` and `/auth/recover_password` are tenant-scoped and guarded by `WebAnonymousPromotionGuard`, which preserves redirect intent but does not continue anonymous web through login.
  - Evidence: `lib/application/router/modular_app/modules/auth_module.dart`
  - Tests: `test/application/router/modules/tenant_public_route_hardening_modules_test.dart`, `test/application/router/guards/web_anonymous_promotion_guard_test.dart`
- **Legacy email/password auth still exists in the app/web auth repository contract:** current infra still implements `loginWithEmailPassword` and `signUpWithEmailPassword`.
  - Evidence: `lib/infrastructure/repositories/auth_repository.dart`
- **Authenticated-vs-anonymous web UI behavior already exists in at least one representative surface:** Home agenda hides the invite filter on anonymous web and reveals it again once auth exists.
  - Evidence: `test/presentation/tenant/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller_test.dart`
- **No dedicated QR session-bootstrap route/controller exists yet in the active tenant-public delivery lane:** current route map still points `/auth/login` at the legacy login flow; this TODO is the authority that will replace or alias that experience without changing `D-05`.

## Dependencies & Sequencing

- [ ] `DEP-01` `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` must close first. QR-authenticated web cannot start from a stale tester-waitlist promotion boundary.
- [ ] `DEP-02` `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` remains the authority for authenticated app identity. QR web login depends on that app-side identity path and must not fork a second native auth method.
- [x] `DEP-03` `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md` already treats this lane as mandatory fast-follow rather than speculative VNext.
- [x] `DEP-04` iOS universal-links/deferred-capture validation is a sibling fast-follow lane, not a blocker for QR-authenticated web delivery.

## Scope

- [ ] Deliver the QR-based bridge that turns an explicit web login attempt into an authenticated web session approved by an already promoted app identity.
- [ ] Deliver authenticated web session bootstrap, continuation, expiry/error handling, and logout lifecycle for the normal logged-in web posture.
- [ ] Replace or alias legacy web login entry so QR is the exclusive authenticated-web login path without changing anonymous hard-gate behavior.
- [ ] Preserve originally requested authenticated route intent after QR bootstrap.
- [ ] Freeze and execute the minimum representative validation set for route, action, and convenience-affordance behavior under an active web session.
- [ ] Keep this lane explicitly sequenced after Android release closure.

## Out of Scope

- [ ] Expanding anonymous web beyond promotion/read-only.
- [ ] Retargeting anonymous route/action hard gates to web login instead of app promotion.
- [ ] Web-native email/password/social login.
- [ ] Replacing app phone-OTP with QR or any alternate app authentication method.
- [ ] Broader account-workspace rollout or generic authenticated-web product expansion beyond the minimum representative delivery set.
- [ ] Reversing the Android-first release sequencing.

## Execution Tracks

### A) Backend Session Bridge
- [ ] Define and implement the QR challenge lifecycle for web session bootstrap: creation, polling/completion, expiry, single-use enforcement, and explicit cancellation/error semantics.
- [ ] Define how an authenticated app identity approves the challenge and how a not-yet-authenticated app identity is routed through OTP before approval resumes.
- [ ] Issue the authenticated web session through canonical backend contracts and ensure logout invalidates or detaches the web session deterministically.
- [ ] Add telemetry/audit coverage for challenge created, challenge approved, challenge expired/cancelled, session established, and logout.

### B) Web Bootstrap and Routing
- [ ] Implement an explicit user-invoked QR login entry surface for web while preserving `D-05`:
  - anonymous hard-gate routes/actions still resolve to app promotion;
  - the QR surface is deliberate login entry, not guard fallback.
- [ ] Replace or alias the current legacy login experience so QR is the only web-auth session bootstrap path.
- [ ] Restore the originally requested authenticated route after successful QR bootstrap (representative proof target: `/profile`).
- [ ] While the session is active, representative authenticated web route/action flows must stop redirecting back to app promotion.
- [ ] Logout must return the browser to the anonymous read-only/promotion boundary.

### C) App Approval Flow
- [ ] Implement the app-side QR approval flow that consumes the browser challenge.
- [ ] Require authenticated app identity before approval completes; if the user is still anonymous in app, continue through the phone-OTP flow first and then resume approval.
- [ ] Return deterministic success/failure/expired states back to the browser session bootstrap flow.

### D) Validation and Evidence
- [ ] Add targeted automated coverage for QR challenge lifecycle, authenticated web session establishment, route restoration, and logout boundary reset.
- [ ] Add manual validation evidence for the cross-device/web-app handshake and the representative authenticated web behaviors listed in Section `Validation Steps`.
- [ ] Persist final evidence references in this TODO and any superseded authority notes in the canonical policy/module docs.

## Definition of Done

- [ ] QR login/authenticated web is tracked as an explicit active delivery lane with no speculative wording left in authoritative docs.
- [ ] QR is the only documented and implemented authenticated-web login path.
- [ ] Anonymous web hard-gates still promote to app after this lane ships.
- [ ] Successful QR bootstrap restores the intended authenticated route and keeps the browser in the normal authenticated posture while the session remains valid.
- [ ] Representative authenticated web action behavior is proven without re-promotion during the active session.
- [ ] Logout returns the browser to the anonymous promotion/read-only boundary and hides authenticated-only affordances again.
- [ ] Android-release dependency and app phone-OTP dependency remain explicit.

## Validation Steps

- [x] This TODO is linked from `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`.
- [ ] Legacy references that treated QR login as merely possible are corrected where needed.
- [ ] Automated minimum set:
  - QR challenge creation/approval/expiry/cancellation coverage.
  - Restoring an originally requested authenticated route such as `/profile` after session establishment.
  - Logout clearing the session and returning the browser to the anonymous boundary.
- [ ] Manual minimum set:
  - Anonymous browser on a read-only page deliberately opens the QR login entry surface.
  - Browser session bootstrap succeeds only after app-side approval from an authenticated app identity.
  - Previously guarded route target (for example `/profile`) restores after QR login and no longer promotes the app while the session is active.
  - Representative authenticated web action path (at minimum one trust action such as `favorite`) executes without promotion while session is active.
  - Authenticated-only convenience affordance (current representative target: Home agenda invite filter) is visible only while the web session is active and disappears again after logout.

## No-Context Executor Notes

- **Do not reopen:** whether authenticated web is allowed, whether anonymous web should stay promotion/read-only, or whether app auth remains OTP-only. Those are already frozen.
- **Implementation-local choice allowed:** whether the QR login surface reuses `/auth/login` or introduces an alias may be decided in execution, but the end result must preserve `D-05` and must retire email/password as a web-auth bootstrap path.
- **Representative behavior is enough:** this TODO does not require proving every authenticated web screen in one pass. It requires enough route/action/affordance evidence to show the session contract is real and coherent.

## Profile Scope & Handoffs

- **Primary execution profile:** `strategic-cto`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`, `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile strategic-cto`

## Complexity

- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** product posture is already approved, but delivery still spans route governance, backend session bootstrap, app approval flow, and cross-device validation.
