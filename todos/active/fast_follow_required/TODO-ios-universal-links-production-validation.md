# TODO (Fast Follow): iOS Universal Links Production Validation (Guarappari)

**Classification note (2026-04-17, refreshed 2026-05-06):** this work remains sequenced after the Android-first gate and is itself the direct technical execution authority for the mandatory iOS fast-follow lane. The older sequencing-only wrappers have been retired as redundant.
**Scope authority note (2026-04-18):** canonical product posture already lives in `foundation_documentation/policies/web_to_app_promotion_policy.md`, `foundation_documentation/endpoints_mvp_contracts.md`, and the Android store-release web-to-app TODO. This TODO does not own policy invention. It owns only the iOS-specific delivery specialization: Universal Links/AASA production validation plus the iOS deferred-capture implementation path under the already-approved package-owned deep-link architecture.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active. Product posture is frozen, but the iOS lane is not yet execution-safe enough for a no-context agent because the local iOS specialization boundary and shared-contract guardrails were still implicit. This TODO now owns that boundary explicitly.  
**Owners:** Delphi (Flutter/Product) + Backend Team + iOS Runtime Validation
**Goal:** Complete iOS-specific production verification for Universal Links/AASA and deliver deferred deep-link capture for iOS under the consolidated architecture (`Laravel package resolver + Flutter consumer`) outside the Android launch gate but inside the mandatory fast-follow release sequence.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Fast-Follow`, `Policy-Frozen`, `iOS-Specialization`
- **Next exact step:** freeze the iOS-local execution boundary first: confirm the shared contracts that must remain unchanged, then let execution choose the concrete iOS deferred-capture mechanism and production validation lane inside that fixed boundary.

## No-Context Handoff Boundaries

- **Frozen here:** Android-first sequencing, anonymous-web promotion/read-only posture, anonymous-app baseline, QR-only authenticated web, OTP-only authenticated app, canonical `/open-app` ownership, canonical deferred resolver ownership, route-intent preservation rule, and telemetry semantics already approved elsewhere must not be reopened here.
- **Not owned here:** Android runtime/store/deferred closure remains in `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`, while the delivered Android-first baseline is archived in `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md`. QR-authenticated web remains in `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`. iOS push/App Store readiness remains in `foundation_documentation/todos/active/fast_follow_required/TODO-ios-push-and-app-store-review-readiness.md`. Any broader participation/check-in semantics remain outside this TODO.
- **Executor rule:** treat this TODO as an iOS implementation/validation packet, not as a place to redesign shared web-to-app policy or shared deep-link contracts. If execution discovers a necessary shared-contract change, update the canonical policy/contracts in the same change set and record that propagation explicitly instead of silently redefining them here.

## Decision Baseline (Frozen 2026-04-18)

- [x] `D-01` iOS Universal Links and iOS deferred deep-link capture are mandatory fast-follow after the Android-first release gate; they are not speculative VNext.
- [x] `D-02` This TODO does not reopen V1 surface rules. Anonymous web stays promotion/read-only, anonymous app baseline stays as already approved, authenticated web remains QR-only, and authenticated app remains OTP-only.
- [x] `D-03` Shared deep-link architecture stays package-owned end to end: backend/package owns association payloads, store/open-app handoff, and deferred first-open resolver contract; Flutter consumes the resolver result.
- [x] `D-04` iOS delivery must preserve the same canonical continuation semantics already approved for Android/web: preserve invite attribution and valid redirect-path intent, and fall back deterministically to `/` only when no valid continuation intent can be resolved.
- [x] `D-05` iOS delivery must preserve canonical telemetry semantics (`store_channel`, attribution-loss/failure reasons, captured vs not-captured outcomes) rather than inventing iOS-only product meanings.
- [x] `D-06` The concrete iOS deferred-capture mechanism is implementation-local to this TODO as long as it honors `D-03` through `D-05`. Selecting that mechanism does not require reopening product policy by itself.
- [x] `D-07` If iOS delivery requires any shared-contract refinement in `web_to_app_promotion_policy.md` or `endpoints_mvp_contracts.md`, that refinement is propagation of an approved architecture, not permission to change the approved product baseline.

## References
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/todos/completed/TODO-v1-invite-deeplink-identity-first-delivery.md`
- `foundation_documentation/todos/completed/TODO-v1-deeplink-host-resolved-well-known.md`
- `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`

---

## Dependencies & Sequencing

- [ ] `DEP-01` `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md` remains the archival authority for the delivered Android-first baseline, and `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md` remains the active owner of the unfinished Android runtime closure. This TODO specializes that baseline for iOS and must not diverge from it.
- [x] `DEP-02` This lane is already frozen as mandatory fast-follow rather than optional backlog.
- [x] `DEP-03` `foundation_documentation/todos/active/fast_follow_required/TODO-qr-login-web-auth.md` is a sibling fast-follow lane, not a blocker for iOS Universal Links / deferred-capture delivery.

## Scope
- Validate installed-app iOS Universal Link open behavior for the Guarappari production hosts (`https://guarappari.com.br/invite?...` and `https://guarappari.booraagora.com.br/invite?...`).
- Validate browser fallback behavior when app is not installed.
- Implement and validate iOS deferred deep-link capture strategy under the fixed package+Flutter contract for first-open attribution continuity.
- Validate `/.well-known/apple-app-site-association` returns canonical non-empty `applinks.details` payload for the Guarappari production hosts.
- Record durable evidence artifacts and close iOS-deferred MVP checklist items.

## Out of Scope
- Android App Links validation (already closed in MVP stream).
- New deep-link route design.
- New invite product behavior.
- Any change to V1 web promotion/read-only boundaries (remains governed by `TODO-store-release-web-to-app-conversion-gate.md` archival baseline, the split Android runtime follow-up TODO, and `web_to_app_promotion_policy.md`).
- Reopening shared route-intent preservation semantics, anonymous-vs-authenticated boundary rules, or telemetry meaning already frozen by the canonical policy/contracts.

---

## Implementation Tasks
- [ ] ⚪ Provision/confirm canonical iOS app identifiers and credentials in tenant settings (`app_ios`, `team_id`, `paths`).
- [ ] ⚪ Validate `https://guarappari.belluga.space/.well-known/apple-app-site-association` returns canonical `appID` (`<TEAM_ID>.<BUNDLE_ID>`) and expected route paths.
- [ ] ⚪ Run manual installed-app Universal Link validation on iOS runtime (device/simulator supported by lane).
- [ ] ⚪ Run manual not-installed fallback validation preserving original invite query parameters.
- [ ] ⚪ Choose and implement the concrete iOS deferred-capture mechanism inside the fixed shared contract boundary: Laravel package resolves link-token/deferred payload; Flutter consumes resolver result on first open.
- [ ] ⚪ If execution needs shared-contract refinement, propagate it explicitly into `web_to_app_promotion_policy.md` and/or `endpoints_mvp_contracts.md` in the same change set, without reopening the approved product baseline.
- [ ] ⚪ Document explicit iOS failure modes and telemetry mapping (`store_channel`, attribution-loss reason, captured vs not_captured) under the already-approved shared semantics.
- [ ] ⚪ Persist evidence artifact in `foundation_documentation/artifacts/` and link it from this TODO.
- [ ] ⚪ Back-propagate closure references into MVP TODOs that deferred iOS verification.

---

## Acceptance Criteria
- [ ] ⚪ iOS Universal Link opens native app when installed.
- [ ] ⚪ Browser fallback works when app is absent and preserves deep-link query context.
- [ ] ⚪ iOS deferred deep-link capture is delivered under the consolidated package+Flutter contract without redefining shared web-to-app policy.
- [ ] ⚪ iOS first-open continuation preserves approved intent semantics: invite attribution and valid redirect-path intent survive when captured; deterministic `/` fallback occurs only when no valid continuation intent can be resolved.
- [ ] ⚪ iOS observability reuses the canonical shared telemetry semantics with explicit iOS evidence for success/failure/not-captured cases.
- [ ] ⚪ AASA payload is canonical and non-empty for guarappari production host.
- [ ] ⚪ MVP TODOs that deferred iOS are updated with explicit closure evidence references.

## Validation Steps

- [ ] Backend/package automated: AASA payload generation and iOS app-link configuration remain canonical for the production host.
- [ ] Flutter automated: iOS deferred-capture consumer path preserves canonical continuation semantics and deterministic fallback behavior under the shared resolver contract.
- [ ] Manual iOS runtime: installed-app Universal Link opens the app for invite/deep-link targets.
- [ ] Manual iOS runtime: not-installed fallback preserves the expected query context in browser/store handoff.
- [ ] Manual iOS runtime: first open after install resolves to invite or preserved redirect-path intent when captured, and falls back deterministically to `/` when not captured.
- [ ] Data/telemetry validation: iOS success/failure/not-captured events land with the canonical property shapes already defined for the shared funnel.

## No-Context Executor Notes

- **Do not reopen:** anonymous web posture, anonymous app baseline, QR web login rule, OTP app login rule, route-intent preservation semantics, canonical `/open-app` ownership, or shared telemetry meaning.
- **Implementation-local choice allowed:** the concrete iOS deferred-capture mechanism and iOS-specific integration path may be chosen during execution as long as they honor the already-approved shared contracts.
- **Propagation rule:** if a shared policy/endpoint/module doc needs clarification because of iOS execution, update it in the same change set. Do not leave the final truth implicit in this TODO alone.
