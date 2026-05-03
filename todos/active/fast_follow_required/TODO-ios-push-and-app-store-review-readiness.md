# TODO (Fast Follow): iOS Push and App Store Review Readiness

**Classification note (2026-05-03):** this work is mandatory fast follow after the Android-first gate. It is distinct from `TODO-ios-universal-links-production-validation.md`: that sibling owns Universal Links/AASA/deferred deep-link continuation, while this TODO owns iOS push capability delivery plus App Store publication-readiness items that are currently not execution-safe.
**Scope authority note (2026-05-03):** this TODO does not reopen product policy. It specializes iOS runtime and review readiness for the already-approved app baseline: push capability delivery, privacy strings, native capabilities/entitlements, and App Store review evidence.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active.  
**Owners:** Delphi (Flutter/Product) + Backend Team + iOS Runtime Validation
**Goal:** Establish a publish-safe iOS lane for push notifications and Apple review readiness, including native capabilities, permission justification, privacy declarations, and review packet evidence.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Fast-Follow`, `iOS-Specialization`, `Review-Critical`
- **Next exact step:** freeze the iOS release-readiness checklist as executable authority, then implement the native/runtime gaps in one coherent iOS lane instead of treating them as ad hoc release polish.

## No-Context Handoff Boundaries

- **Frozen here:** Android-first sequencing, anonymous-web promotion/read-only posture, QR-only authenticated web, OTP-only authenticated app, and the shared web-to-app contracts approved in `web_to_app_promotion_policy.md` must not be reopened here.
- **Not owned here:** Universal Links/AASA/deferred-capture validation remains in `TODO-ios-universal-links-production-validation.md`. QR-authenticated web remains in `TODO-qr-login-web-auth.md`.
- **Executor rule:** treat this TODO as the iOS publication-readiness authority for native capabilities and review evidence. If execution discovers a shared policy or contract gap, propagate it explicitly in the same change set instead of silently redefining it here.

## Decision Baseline (Frozen 2026-05-03)

- [x] `D-01` iOS push is mandatory fast follow and not optional backlog.
- [x] `D-02` App Store review readiness is part of the iOS fast-follow lane, not a separate operational afterthought.
- [x] `D-03` Permission strings must describe the app’s real user-facing purpose in plain language and match runtime behavior; placeholder/template text is not acceptable for submission.
- [x] `D-04` Only permissions and capabilities that are truly exercised in iOS runtime may remain enabled; unused or speculative native entitlements are not allowed into release.
- [x] `D-05` Third-party SDK presence alone is not enough to justify review answers by guesswork; App Store privacy answers must be derived from the actual runtime/data flows we ship.

## References

- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-obligatory.md`
- `foundation_documentation/todos/active/fast_follow_required/TODO-ios-universal-links-production-validation.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `ios/Runner/Info.plist`
- `ios/Runner/Runner.entitlements`
- `ios/Runner.xcodeproj/project.pbxproj`
- `pubspec.yaml`
- `.flutter-plugins-dependencies`
- `lib/application/application_contract.dart`
- `lib/infrastructure/services/push/push_gatekeeper.dart`
- `lib/presentation/tenant_public/profile/screens/profile_screen/profile_screen.dart`

---

## Current Audit Snapshot (2026-05-03)

- [x] `A-01` iOS runtime currently declares `NSLocationWhenInUseUsageDescription` and `NSContactsUsageDescription`.
- [x] `A-02` iOS runtime now declares production Universal Link hosts in `com.apple.developer.associated-domains` (`applinks:guarappari.com.br`, `applinks:guarappari.booraagora.com.br`).
- [x] `A-03` iOS runtime currently does **not** declare `aps-environment`.
- [x] `A-04` The Flutter app ships `firebase_messaging`, `push_handler`, `permission_handler`, `flutter_contacts`, `geolocator`, and `image_picker` on iOS.
- [x] `A-05` The app uses `image_picker` for both `ImageSource.camera` and `ImageSource.gallery`, so camera/photo-library privacy keys are required.
- [x] `A-06` `Info.plist` / Xcode project still carry template identity surfaces (`Flutter Laravel Backend Boilerplate`, `com.example.flutterLaravelBackendBoilerplate`) unless overridden elsewhere during release build.
- [x] `A-07` No `Podfile` is currently present in the Flutter iOS workspace, so the canonical native control point for permission-handler macros and plugin-level iOS build customization is absent.

## Scope

- Deliver iOS push-notification capability and runtime readiness for the shipped app.
- Canonicalize iOS permission declarations and user-facing justification strings for every actually-used sensitive API.
- Canonicalize iOS app identity surfaces required for submission (`bundle identifier`, display name, signing/capabilities readiness).
- Produce App Store review evidence and privacy-declaration inputs based on real runtime/plugin behavior.

## Out of Scope

- Android push/runtime readiness.
- Reopening shared product rules or deep-link semantics.
- Implementing non-iOS-only product features unrelated to publication/readiness.

---

## Implementation Tasks

- [ ] ⚪ Establish the canonical iOS native build control surface (`Podfile` or equivalent approved native configuration point) so permission/plugin settings are explicit and reproducible.
- [ ] ⚪ Confirm whether `push_handler` / `firebase_messaging` on iOS will request notification authorization in the shipped runtime path; document the exact trigger and owner.
- [ ] ⚪ If push is enabled in the shipped iOS path, add and validate the required Push Notifications capability / `aps-environment` entitlement and any required background mode decisions.
- [ ] ⚪ Canonicalize iOS app identity for release: remove template display name / template bundle identifier and wire the real release-owned values.
- [ ] ⚪ Add the missing iOS privacy keys required by actual runtime behavior, including camera/photo-library access for `image_picker`.
- [ ] ⚪ Reword existing permission strings (`contacts`, `location`, and any newly added keys) so they are precise, user-facing, and App Review-safe.
- [ ] ⚪ Audit whether any plugin currently shipped on iOS is unused in release runtime and should be removed or fenced off before submission.
- [ ] ⚪ Produce the App Store privacy-input checklist for shipped SDKs and data flows (for example diagnostics/analytics/push/contact data where applicable).
- [ ] ⚪ Produce App Review notes covering test access, tenant/runtime context, and any iOS-specific review caveats.
- [ ] ⚪ Persist an evidence artifact in `foundation_documentation/artifacts/` and back-link it from this TODO.

---

## Acceptance Criteria

- [ ] ⚪ iOS push runtime is either fully implemented and capability-complete for submission, or explicitly removed from the shipped iOS surface until it is complete.
- [ ] ⚪ Every sensitive iOS API used by the shipped app has the required `Info.plist` key with a clear, app-specific purpose string.
- [ ] ⚪ No template iOS identity surfaces remain in the release-ready project configuration.
- [ ] ⚪ Native capabilities/entitlements on iOS match actual runtime usage and do not contain speculative or stale configuration.
- [ ] ⚪ The App Store privacy questionnaire can be answered from audited evidence rather than assumptions.
- [ ] ⚪ App Review notes and reviewer access instructions are prepared for the iOS submission lane.

## Validation Steps

- [ ] Local/code audit: confirm all iOS-sensitive plugins shipped in `.flutter-plugins-dependencies` and classify them as used, gated, or removable.
- [ ] Flutter/native audit: confirm each runtime-sensitive permission path has the matching `Info.plist` key and a release-safe string.
- [ ] Native configuration audit: confirm push capability / entitlements / bundle identity surfaces are canonical and non-template.
- [ ] Manual iOS runtime: validate permission prompts for contacts, location, camera, gallery, and notifications only appear in the intended contextual flows.
- [ ] Submission readiness audit: derive App Store privacy answers and App Review notes from the validated runtime behavior.

## No-Context Executor Notes

- `image_picker` usage already makes camera/photo-library keys a release blocker on iOS.
- `firebase_messaging` is present and push is business-critical, but the shipped iOS entitlement/capability path is not yet established here.
- The absence of a checked-in `Podfile` is a concrete readiness concern until the native control path is made explicit.
