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

- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Metrics-Evidence`
- **Next exact step:** carry the explicit final-phase obligations into the consolidated runtime lane: ADB/device execution, web runtime/Playwright proof, and external telemetry sink/query readback.

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

- [x] Freeze the Android-release funnel-metrics matrix with event name, concrete flow owner, required properties, and validation owner.
- [x] Validate release-critical web/app events and their required properties for:
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
- [x] Record any missing event/property/query gap as an explicit release blocker, waiver, or follow-up owner.
- [x] Route missing event implementation back to the concrete flow TODO that owns the behavior.
- [x] Promote any stable release-facing metrics/tracker rule that is still missing from canonical docs.

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

- [x] Freeze the release-critical validation matrix from current code/runtime ownership.
- [x] Capture required properties and concrete flow owner for each event.
- [x] Mark each event as `covered`, `partially covered`, or `missing` based on current evidence.

### B) Runtime + Sink Validation

- [x] Validate runtime emission for the release-critical journeys.
- [ ] Validate sink/query readback for the KPI set.
- [x] Confirm deduplication/identity-merge interpretation is sufficient for release judgment.

### C) Release Decision Capture

- [x] Record blocker/waiver/follow-up handling for any telemetry gap.
- [x] Route implementation gaps back to the corresponding flow TODOs.
- [x] Promote any confirmed rule drift into canonical docs before closing.

## Acceptance Criteria

- [x] One explicit Android-release funnel-metrics validation matrix exists with owner + required properties per event.
- [x] Release-critical event journeys are validated in runtime evidence and/or automated evidence.
- [ ] KPI readback path is confirmed workable for release judgment.
- [x] Any remaining gap is explicitly classified as blocker, waiver, or post-release follow-up with owner.

## Definition of Done

- [x] Android store release has a frozen funnel-metrics validation matrix for the critical funnel.
- [ ] The required KPI set can be read and trusted well enough for release decisions.
- [x] No hidden telemetry gap remains implied by "it should already be firing".

## Validation Steps

- [x] Code/test audit for release-critical event ownership and required properties.
- [x] Automated evidence where available for event names/properties on touched flows.
- [ ] Manual or sink-level validation for web-to-app, OTP, merge, and first-favorite milestones.
- [x] Documented KPI readback proof or explicit waiver if a query surface is temporarily limited.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `foundation_documentation:<current lane>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Validation matrix freeze | `local-uncommitted` | `pending` | `pending` | `pending` | `Local-Implemented` |
| Runtime event/property validation | `local-uncommitted` | `pending` | `pending` | `pending` | `Local-Implemented; ADB/web runtime deferred` |
| KPI sink/query validation | `blocked-by-external-sink-readback` | `pending` | `pending` | `pending` | `Final-phase required` |
| Release blocker/waiver capture | `local-uncommitted` | `pending` | `pending` | `pending` | `Local-Implemented` |

---

## Local Implementation Candidate Notes (2026-04-28)

**Checkpoint status:** local implementation gate passed. This is not a `Production-Ready` claim because ADB/device runtime execution and external telemetry query readback remain deferred to the consolidated final runtime phase.

**Code changes made in owning surfaces discovered by this validation lane:**

- Flutter deferred capture telemetry now always includes `store_channel`, using `unknown` when the Android/native resolver does not provide a concrete store channel.
- Flutter anonymous invite acceptance preserves the active share `code` even when the preview/materialized invite id no longer carries the `share:` prefix.
- Flutter web invite landing telemetry now emits `code` when a share code is present, in addition to `has_code` and `store_channel=web`.
- Laravel telemetry envelopes now support pre-auth events through an explicit actor instead of dropping events when `userId` is null.
- Laravel OTP challenge telemetry now emits `otp_challenge_started` with actor `{type: phone_otp_challenge, id: challenge_id}`, `delivery_channel`, and phone-hash target context.
- Laravel OTP verification telemetry has direct queue-envelope evidence for both `otp_verified` and `auth_merge_completed`.

### Frozen Android Release Funnel Metrics Matrix

| Event | Concrete owner | Required properties | Local evidence | Current classification |
| --- | --- | --- | --- | --- |
| `web_invite_landing_opened` | Flutter invite landing controller | `store_channel=web`, `has_code`, `code` when present | Source audit: `InviteFlowScreenController.trackWebLanding`; web runtime/Playwright deferred | Covered locally by source; runtime deferred |
| `web_open_app_clicked` | Flutter web promotion telemetry | `store_channel=web`, `platform_target` | `test/application/telemetry/web_promotion_telemetry_test.dart` | Covered |
| `web_install_clicked` | Flutter web promotion telemetry | `store_channel=web`, `platform_target` | `test/application/telemetry/web_promotion_telemetry_test.dart` | Covered |
| `app_deferred_deep_link_captured` | Flutter startup/init deferred-link path | `code`, `platform=android`, `store_channel` | `test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart`; `test/infrastructure/repositories/deferred_link_repository_test.dart` | Covered |
| `app_deferred_deep_link_capture_failed` | Flutter startup/init deferred-link path | `platform=android`, `failure_reason`, `store_channel` | `test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart`; `test/infrastructure/repositories/deferred_link_repository_test.dart` | Covered |
| `app_anonymous_invite_accepted` | Flutter invite flow controller | `event_id`, `source=invite_flow`, `code` when share-code entry exists | `test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` | Covered |
| `app_auth_wall_triggered` | Flutter auth route guard / auth wall telemetry | `action_type`, `redirect_path` where available | `test/application/router/guards/auth_route_guard_test.dart` | Covered |
| `app_signup_completed` | Flutter auth login effects / auth wall telemetry | `source`, plus auth-wall context when present | `test/presentation/common/auth/screens/auth_login_screen/auth_login_effects_test.dart` | Covered |
| `otp_challenge_started` | Laravel `PhoneOtpAuthController::challenge` + `TelemetryEmitter` | `challenge_id`, `delivery_channel`, pre-auth actor, no empty `user_id` metadata | `tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php` | Covered |
| `otp_verified` | Laravel `PhoneOtpAuthController::verify` | `user_id`, `identity_state`, user actor/target | `tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php`; `tests/Feature/Auth/TenantPhoneOtpAuthTest.php` | Covered |
| `auth_merge_completed` | Laravel `PhoneOtpAuthController::verify` | `user_id`, `source_count`, `source_kind=anonymous` | `tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php`; `tests/Feature/Auth/TenantPhoneOtpAuthTest.php` | Covered |
| `favorite_artist_toggled` | Flutter account profiles repository | `account_profile_id`, `is_favorite` | `test/infrastructure/repositories/account_profiles_repository_test.dart` | Covered |

### Completion Evidence Matrix (Local Gate)

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `T4-MATRIX` | Local Gate | Freeze explicit Android-release funnel metrics matrix | Documentation | Matrix in this TODO plus promoted module docs | Foundation docs | passed | Promoted into `invite_and_social_loop_module.md`, `onboarding_flow_module.md`, and `flutter_client_experience_module.md`. |
| `T4-EVENTS` | Local Gate | Validate release-critical event/property coverage | Automated tests | Flutter target suite listed in `T4-funnel-metrics-review-packet.md` | Local Flutter VM/widget/controller | passed | 36 tests passed. |
| `T4-OTP` | Local Gate | Validate OTP telemetry queue dispatch and pre-auth envelope semantics | Automated tests | Laravel safe runner listed in `T4-funnel-metrics-review-packet.md` | Local Laravel Docker/test DB | passed | 10 tests and 52 assertions passed. |
| `T4-STATIC` | Local Gate | Static analysis / formatting | Analyzer and formatter | `fvm dart analyze --format machine`; Pint touched PHP files | Local Flutter/Laravel | passed | Analyzer exited 0 with no diagnostics; Pint passed. |
| `T4-SINK` | Local Gate | Sink/query readback for KPI set | Queue dispatch proof plus explicit final-phase dependency | `tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php`; `DEP-04` | Local queue proof; external sink final phase | waived | Local-gate waiver approval: APROVADO orchestration defers external sink/query readback to final runtime; this is not a `Production-Ready` waiver. |
| `T4-ADB` | Local Gate | ADB/device runtime validation | Deferred runtime validation | Final consolidated ADB/device lane | Android device | waived | Local-gate waiver approval: APROVADO orchestration defers ADB/device execution to reduce WSL/device instability risk. |
| `DOD-01` | Definition of Done | Android store release has a frozen funnel-metrics validation matrix for the critical funnel. | Documentation and review packet | This TODO; `foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md` | Foundation docs | waived | Structure-only waiver/deviation with approval: APROVADO local gate treats matrix freeze as documentation-only; device/browser flow proof is tracked in runtime rows. |
| `DOD-02` | Definition of Done | The required KPI set can be read and trusted well enough for release decisions. | Local join-key/property proof; final sink readback pending | Event matrix; KPI readback interpretation; `DEP-04` | Local proof plus external sink final phase | waived | Local-gate waiver approval: APROVADO orchestration accepts local property/join-key proof now; external sink/query readback remains required before release closure. |
| `DOD-03` | Definition of Done | No hidden telemetry gap remains implied by "it should already be firing". | Gap audit and fixes | Review packet; triple audit session | Local code/test audit | passed | Fixed missing pre-auth OTP dispatch, deferred `store_channel`, anonymous accept `code`, and web landing `code`; remaining runtime/sink gaps are explicit. |
| `VAL-01` | Validation Steps | Code/test audit for release-critical event ownership and required properties. | Review packet plus triple audit | `foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md`; `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/session.json` | Local audit | passed | Three-lane audit returned zero findings; adjudication resolved non-material wording conflict. |
| `VAL-02` | Validation Steps | Automated evidence where available for event names/properties on touched flows. | Automated tests | Flutter target suite; Laravel target suite | Local Flutter/Laravel | passed | Flutter 36 tests; Laravel 10 tests and 52 assertions. |
| `VAL-03` | Validation Steps | Manual or sink-level validation for web-to-app, OTP, merge, and first-favorite milestones. | Deferred runtime/sink validation | Final ADB/web/sink lane | Android device, browser, external telemetry sink | waived | Local-gate waiver approval: APROVADO orchestration intentionally leaves manual/device/browser/sink validation to the consolidated final runtime phase. |
| `VAL-04` | Validation Steps | Documented KPI readback proof or explicit waiver if a query surface is temporarily limited. | Documented readback interpretation and dependency | KPI readback interpretation below; `DEP-04` | External telemetry query surface | waived | Structure-only waiver/deviation with approval: APROVADO local gate documents temporary query limitation; sink/query readback remains required before `Production-Ready`. |

### KPI Readback Interpretation

The local candidate can compute the required release KPI edges from emitted properties once the sink/query surface is available:

- landing -> open/install: `web_invite_landing_opened.code` + web promotion CTA events with `platform_target`.
- open/install -> deferred capture: `store_channel` and share `code` carried across web/open/install and app deferred capture.
- deferred capture -> anonymous accept: share `code` retained in app deferred capture and anonymous invite acceptance.
- anonymous accept -> auth wall -> signup: auth wall telemetry preserves restricted action context and signup source.
- OTP challenge -> verified/merged: Laravel queue envelopes carry challenge, verification, and merge milestones.
- verified/merged -> first favorite: registered identity can be joined to `favorite_artist_toggled.account_profile_id` once sink identity association is queryable.

### Review Gate Notes

- Independent triple audit completed in `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/session.json`.
- Round 01 merged as `needs_adjudication` only due non-material `recommended_path_conflict`; all three lanes had zero findings. Resolution recorded as `resolved` in `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/round-01/resolution.md`.
- Claude CLI review attempt is recorded at `foundation_documentation/artifacts/claude-cli-reviews/T4-funnel-metrics-cli-review.md`; the CLI returned usage-limit unavailability, so it is not a substantive gate under the current orchestration decision.
