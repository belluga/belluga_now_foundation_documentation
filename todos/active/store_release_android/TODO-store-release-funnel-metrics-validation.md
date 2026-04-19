# TODO (Store Release): Funnel Metrics Validation

**Classification note (2026-04-18):** this lane is release-critical because Android publication cannot rely on conversion, identity, and first social-loop behavior that is only assumed but not proven in release metrics evidence.

**Scope authority note (2026-04-18):** this TODO owns the store-release validation slice for cross-flow funnel metrics and sink/query integrity. It does not own event implementation. If validation finds a missing event or missing properties, the fix belongs to the concrete flow TODO that owns that behavior and can use the existing tracker service.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. Core telemetry runtime is already live, but store release still needs explicit funnel-metrics proof and sink/query evidence for the release-critical acquisition and identity funnel.
**Owners:** Flutter Team, Laravel Team, Data Team
**Goal:** validate the release-critical funnel metrics end to end so Android publication has trustworthy evidence for web-to-app promotion, deferred continuation, identity progression, and first social-loop actions.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The broad telemetry architecture review is no longer the right owner for the remaining work. What still matters for Android release is narrower and operational:

- which release-critical events must fire,
- which properties each event must carry,
- whether those events actually arrive in the sink/query surface,
- whether the KPI set needed for release judgment can be read back with confidence.

This lane exists to freeze and validate that evidence without reopening settled telemetry-provider or DI debates and without creating a false “telemetry implementation owner” for flows that already know how to emit via the shared tracker service.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the remaining work is a bounded release-validation lane derived from already-frozen store-release and telemetry decisions.
- **Direct-to-TODO rationale:** safe. The objective is concrete, release-facing, and already decomposed from the retired telemetry umbrella.

## Contract Boundary

- This TODO owns release-level funnel metrics validation only.
- It includes release-facing event/readback proof, required-property proof, sink/query proof, and release-readiness interpretation for the tracked funnel.
- It does not own adding or wiring events in product flows; missing instrumentation must be fixed in the TODO that owns the corresponding flow.
- It does **not** own new product analytics strategy, provider replacement, or long-term telemetry architecture redesign.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Metrics-Evidence`
- **Next exact step:** freeze the Android release funnel-metrics matrix from current flow ownership, then validate runtime/readback evidence for the defined KPI set.

## References

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/todos/completed/TODO-v1-telemetry-frontend.md`
- `foundation_documentation/todos/completed/TODO-vnext-telemetry-architecture-review.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision promotion targets:**
  - `invite_and_social_loop_module.md` release funnel-metrics expectations for web-to-app conversion
  - `onboarding_flow_module.md` identity/continuation metrics expectations
  - `flutter_client_experience_module.md` client/runtime tracker-usage expectations if any net-new truth is confirmed

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this is release-readiness validation for this project's current Android launch gate, tied to its specific promotion, OTP, and favorites milestones.
- **Reuse doctrine note:** the validation pattern may later inform ecosystem analytics governance, but this lane is not itself a reusable package candidate.

## Decision Baseline (Frozen 2026-04-18)

- [x] `D-01` The broad telemetry architecture review is closed; remaining Android-release metrics proof must live in a dedicated store-release validation lane.
- [x] `D-02` Release judgment requires validated metrics for acquisition, deferred continuation, identity progression, and first social-loop action, not just "events exist in code".
- [x] `D-03` The validation matrix must name the concrete flow owner, required properties, and validation evidence for each release-critical event.
- [x] `D-04` Sink/query validation is part of scope; release metrics are not complete if runtime emits events but the KPI surface cannot read them back reliably.
- [x] `D-05` Missing event wiring discovered here must be implemented in the corresponding flow TODO through the existing tracker service, not by treating this TODO as a telemetry feature owner.

## Scope

- [ ] Freeze the Android-release funnel-metrics matrix with event name, concrete flow owner, required properties, and validation owner.
- [ ] Validate release-critical web/app events and their required properties for:
  - `web_invite_landing_opened`
  - `web_open_app_clicked`
  - `web_install_clicked`
  - `app_deferred_deep_link_captured`
  - `app_deferred_deep_link_capture_failed`
  - `app_anonymous_invite_accepted`
  - `app_auth_wall_triggered`
  - `app_signup_completed`
  - `otp_challenge_started`
  - `otp_verified`
  - `auth_merge_completed`
  - `favorite_artist_toggled`
- [ ] Validate that the sink/query surface can support the release KPI set:
  - landing -> open/install
  - open/install -> deferred capture
  - deferred capture -> anonymous accept
  - anonymous accept -> auth wall
  - auth wall -> signup
  - OTP challenge -> verified
  - verified/merged -> first favorite
- [ ] Record any missing event/property/query gap as an explicit release blocker, waiver, or follow-up owner.
- [ ] Route missing event implementation back to the concrete flow TODO that owns the behavior.
- [ ] Promote any stable release-facing metrics/tracker rule that is still missing from canonical docs.

## Out of Scope

- [ ] Replacing Mixpanel or changing telemetry provider contracts.
- [ ] Generic telemetry taxonomy redesign outside the release-critical funnel.
- [ ] Building a permanent cross-product analytics program/dashboard beyond what release validation requires.
- [ ] Non-release telemetry polish that does not affect Android publication judgment.

## Dependencies & Sequencing

- [x] `DEP-01` `TODO-store-release-web-to-app-conversion-gate.md` remains the owner of promotion/deferred-flow product behavior and any missing event implementation for that flow; this TODO only validates the proof.
- [x] `DEP-02` `TODO-store-release-phone-otp-auth-and-contact-match.md` remains the owner of OTP/identity behavior and any missing event implementation for those milestones; this TODO only validates the proof.
- [x] `DEP-03` `TODO-store-release-minimal-friends-and-favorites-mvp.md` remains the owner of first social-loop behavior and any missing event implementation for that milestone; this TODO only validates the proof.
- [ ] `DEP-04` Query/sink access needed for KPI readback must be available before this TODO can close.

## Execution Tracks

### A) Validation Matrix Freeze

- [ ] Freeze the release-critical validation matrix from current code/runtime ownership.
- [ ] Capture required properties and concrete flow owner for each event.
- [ ] Mark each event as `covered`, `partially covered`, or `missing` based on current evidence.

### B) Runtime + Sink Validation

- [ ] Validate runtime emission for the release-critical journeys.
- [ ] Validate sink/query readback for the KPI set.
- [ ] Confirm deduplication/identity-merge interpretation is sufficient for release judgment.

### C) Release Decision Capture

- [ ] Record blocker/waiver/follow-up handling for any telemetry gap.
- [ ] Route implementation gaps back to the corresponding flow TODOs.
- [ ] Promote any confirmed rule drift into canonical docs before closing.

## Acceptance Criteria

- [ ] One explicit Android-release funnel-metrics validation matrix exists with owner + required properties per event.
- [ ] Release-critical event journeys are validated in runtime evidence and/or automated evidence.
- [ ] KPI readback path is confirmed workable for release judgment.
- [ ] Any remaining gap is explicitly classified as blocker, waiver, or post-release follow-up with owner.

## Definition of Done

- [ ] Android store release has a frozen funnel-metrics validation matrix for the critical funnel.
- [ ] The required KPI set can be read and trusted well enough for release decisions.
- [ ] No hidden telemetry gap remains implied by "it should already be firing".

## Validation Steps

- [ ] Code/test audit for release-critical event ownership and required properties.
- [ ] Automated evidence where available for event names/properties on touched flows.
- [ ] Manual or sink-level validation for web-to-app, OTP, merge, and first-favorite milestones.
- [ ] Documented KPI readback proof or explicit waiver if a query surface is temporarily limited.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `foundation_documentation:<current lane>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Validation matrix freeze | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Runtime event/property validation | `pending` | `pending` | `pending` | `pending` | `Pending` |
| KPI sink/query validation | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Release blocker/waiver capture | `pending` | `pending` | `pending` | `pending` | `Pending` |
