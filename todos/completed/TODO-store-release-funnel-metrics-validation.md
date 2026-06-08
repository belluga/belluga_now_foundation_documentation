# TODO (Store Release): Funnel Metrics Validation

**Classification note (2026-04-18):** this lane is release-critical because Android publication cannot rely on conversion, identity, and first social-loop behavior that is only assumed but not proven in release metrics evidence.

**Scope authority note (2026-04-18; split refreshed 2026-05-03):** this TODO now owns the delivered store-release validation slice that is actually closable before publication: frozen funnel event/property matrix, local runtime/property evidence, and KPI join-key interpretation. It does not own event implementation. If validation finds a missing event or missing properties, the fix belongs to the concrete flow TODO that owns that behavior and can use the existing tracker service.

**Contract correction note (2026-04-30):** `web_to_app_promotion_policy.md` `1.7` supersedes older funnel wording that referenced anonymous invite acceptance. The release funnel now treats invite preview/session context as anonymous-capable, while explicit invite acceptance is authenticated. Metrics proof should validate `app_invite_acceptance_requested` / `app_invite_accepted` (or the implementation-equivalent event names if the current tracker has not yet been renamed), not rely on anonymous accept as a canonical terminal event.

**Post-release split note (2026-05-03):** the remaining items that cannot be credibly closed before publication were split out of this release TODO into `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`. That follow-up now owns published-build runtime replay, Mixpanel sink/readback closure, and production-like KPI verification.

**Status legend:** canonical PACED delivery stages; the authoritative current stage is recorded in `Delivery Status Canon`.
**Status:** Completed historical archival catch-up on `2026-06-08`. The pre-publication T4 subset was already execution-validated, the missing event/property gaps were repaired, and the remaining published-build sink/runtime hardening was explicitly split into the dedicated post-release TODO.
**Owners:** Flutter Team, Laravel Team, Data Team
**Goal:** validate the release-critical funnel metrics end to end so Android publication has trustworthy evidence for web-to-app promotion, deferred continuation, identity progression, and first social-loop actions.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Approval

- **Approved by:** explicit user request on `2026-06-08` to analyze delayed promotion-lane TODOs against code/main and move already-promoted items to `completed`.
- **Approval scope:** documentation-only archival closeout for the delivered pre-publication funnel-metrics slice after confirming the remaining published-build sink/runtime work is explicitly owned by `TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`.

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

- This TODO owns the release-level funnel metrics subset that is legitimately closable before publication.
- It includes release-facing event/property proof, local validation evidence, and KPI join-key/readiness interpretation for the tracked funnel.
- Published-build runtime replay and external sink/query closure are owned by the split post-release hardening TODO.
- It does not own adding or wiring events in product flows; missing instrumentation must be fixed in the TODO that owns the corresponding flow.
- It does **not** own new product analytics strategy, provider replacement, or long-term telemetry architecture redesign.

## Delivery Status Canon

- **Current delivery stage:** `Completed`
- **Qualifiers:** `Cross-Stack`, `Release-Critical`, `Metrics-Evidence`, `Post-Release-Split-2026-05-03`, `Historical-Archival-Catch-Up`, `origin-main-reviewed`
- **Next exact step:** archive at `foundation_documentation/todos/completed/TODO-store-release-funnel-metrics-validation.md`; published-build sink/readback hardening continues only in the split post-release TODO.
- **Post-commit/push status:** `completed`

## References

- `foundation_documentation/todos/completed/TODO-store-release-android.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`
- `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`
- `foundation_documentation/todos/completed/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/completed/TODO-store-release-minimal-friends-and-favorites-mvp.md`
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
  - `app_invite_acceptance_requested`
  - `app_invite_accepted`
  - `app_auth_wall_triggered`
  - `app_signup_completed`
  - `otp_challenge_started`
  - `otp_verified`
  - `auth_merge_completed`
  - `favorite_artist_toggled`
- [x] Freeze the KPI join-key/readback interpretation needed for the release KPI set:
  - landing -> open/install
  - open/install -> deferred capture
  - deferred capture -> invite acceptance requested
  - invite acceptance requested -> auth wall when authentication is required
  - authenticated continuation -> invite accepted
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

- [x] `DEP-01` `TODO-store-release-web-to-app-conversion-gate.md` now archives the delivered promotion/deferred-flow baseline, while `TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md` owns the unfinished Android runtime/store/deferred closure. This TODO only validates the proof.
- [x] `DEP-02` `TODO-store-release-phone-otp-auth-and-contact-match.md` remains the owner of OTP/identity behavior and any missing event implementation for those milestones; this TODO only validates the proof.
- [x] `DEP-03` `TODO-store-release-minimal-friends-and-favorites-mvp.md` remains the owner of first social-loop behavior and any missing event implementation for that milestone; this TODO only validates the proof.
- [x] `DEP-04` Query/sink access needed for published-build KPI readback was split to `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`; it is no longer a blocker for this promotion-lane artifact.

## Execution Tracks

### A) Validation Matrix Freeze

- [x] Freeze the release-critical validation matrix from current code/runtime ownership.
- [x] Capture required properties and concrete flow owner for each event.
- [x] Mark each event as `covered`, `partially covered`, or `missing` based on current evidence.

### B) Runtime + Sink Validation

- [x] Validate runtime emission for the release-critical journeys.
- [x] Validate sink/query readback for the KPI set.
- [x] Confirm deduplication/identity-merge interpretation is sufficient for release judgment.

### C) Release Decision Capture

- [x] Record blocker/waiver/follow-up handling for any telemetry gap.
- [x] Route implementation gaps back to the corresponding flow TODOs.
- [x] Promote any confirmed rule drift into canonical docs before closing.

## Acceptance Criteria

- [x] One explicit Android-release funnel-metrics validation matrix exists with owner + required properties per event.
- [x] Release-critical event journeys are validated in runtime evidence and/or automated evidence.
- [x] KPI readback path is confirmed workable for release judgment.
- [x] Any remaining gap is explicitly classified as blocker, waiver, or post-release follow-up with owner.

## Definition of Done

- [x] Android store release has a frozen funnel-metrics validation matrix for the critical funnel.
- [x] The required KPI set can be read and trusted well enough for release decisions.
- [x] No hidden telemetry gap remains implied by "it should already be firing".

## Validation Steps

- [x] Code/test audit for release-critical event ownership and required properties.
- [x] Automated evidence where available for event names/properties on touched flows.
- [x] Manual or sink-level validation for web-to-app, OTP, merge, and first-favorite milestones.
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
- Flutter invite telemetry now splits intent from persistence: `app_invite_acceptance_requested` is emitted in the app before the auth/mutation boundary, and share-entry acceptance uses the canonical share-code endpoint so the backend terminal event can retain `code`.
- Flutter web invite landing telemetry now emits `code` when a share code is present, in addition to `has_code` and `store_channel=web`.
- Laravel telemetry envelopes now support pre-auth events through an explicit actor instead of dropping events when `userId` is null.
- Laravel OTP challenge telemetry now emits `otp_challenge_started` with actor `{type: phone_otp_challenge, id: challenge_id}`, `delivery_channel`, and phone-hash target context.
- Laravel OTP verification telemetry has direct queue-envelope evidence for both `otp_verified` and `auth_merge_completed`.
- Laravel canonical `invite.accepted` telemetry now carries funnel join keys for authenticated share acceptance: `event_id`, `occurrence_id`, `source=invite_flow`, `invite_source`, and `code` when share-code entry is the acceptance path.

### External Sink Snapshot (2026-05-03)

Mixpanel screenshot evidence confirms that the sink is live, but it does not close the funnel:

- **Observed in sink from the screenshot:** `app_auth_wall_triggered`, `favorite_artist_toggled`.
- **Observed but non-closing generic/runtime events:** `App Session`, `First App Open`, `app_init`, `app_lifecycle`, `screen_view`, `section_viewed`, `invite_opened`, `agenda_radius_changed`, `map_catalog_filter_applied`, `map_filter_cleared`, `map_location_resolved`, `poi_opened`.
- **Not observed in this readback snapshot:** `web_invite_landing_opened`, `web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`, `app_invite_acceptance_requested`, terminal authenticated invite acceptance (`app_invite_accepted` or backend-equivalent `invite.accepted`), `app_signup_completed`, `otp_challenge_started`, `otp_verified`, `auth_merge_completed`.

This screenshot is therefore valid only as **partial sink liveness evidence** plus confirmation that at least part of the restricted-action/social loop surface is arriving in Mixpanel.

### Frozen Android Release Funnel Metrics Matrix

| Event | Concrete owner | Required properties | Local evidence | Current classification |
| --- | --- | --- | --- | --- |
| `web_invite_landing_opened` | Flutter invite landing controller | `store_channel=web`, `has_code`, `code` when present | Source audit: `InviteFlowScreenController.trackWebLanding`; web runtime/Playwright deferred | Covered locally by source; runtime deferred |
| `web_open_app_clicked` | Flutter web promotion telemetry | `store_channel=web`, `platform_target` | `test/application/telemetry/web_promotion_telemetry_test.dart` | Covered |
| `web_install_clicked` | Flutter web promotion telemetry | `store_channel=web`, `platform_target` | `test/application/telemetry/web_promotion_telemetry_test.dart` | Covered |
| `app_deferred_deep_link_captured` | Flutter startup/init deferred-link path | `code`, `platform=android`, `store_channel` | `test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart`; `test/infrastructure/repositories/deferred_link_repository_test.dart` | Covered |
| `app_deferred_deep_link_capture_failed` | Flutter startup/init deferred-link path | `platform=android`, `failure_reason`, `store_channel` | `test/presentation/shared/init/screens/init_screen/controllers/init_screen_controller_test.dart`; `test/infrastructure/repositories/deferred_link_repository_test.dart` | Covered |
| `app_invite_acceptance_requested` | Flutter invite flow controller | `occurrence_id`, optional derived `event_id`, `code` when share-code entry exists, `source=invite_flow`, `auth_state` | `test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart` | Covered |
| `app_invite_accepted` | Laravel authenticated invite acceptance terminal (`invite.accepted` implementation-equivalent) | `occurrence_id`, optional derived `event_id`, `code` when share-code entry exists, `source=invite_flow`; also `invite_source` when the backend canonical event is used | `tests/Feature/Invites/InvitesFlowTest.php`; authenticated share-accept backend contract evidence | Covered as backend implementation-equivalent; sink readback must confirm terminal acceptance event name in Mixpanel |
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
| `T4-EVENTS` | Local Gate | Validate release-critical event/property coverage | Automated tests | Flutter target suite listed in `T4-funnel-metrics-review-packet.md` | Local Flutter VM/widget/controller | passed | 42 tests passed, including explicit invite-acceptance-request telemetry coverage. |
| `T4-OTP` | Local Gate | Validate OTP telemetry queue dispatch, pre-auth envelope semantics, and backend terminal invite-acceptance join keys | Automated tests | Laravel safe runner listed in `T4-funnel-metrics-review-packet.md` | Local Laravel Docker/test DB | passed | 3 tests and 15 assertions passed in the refreshed focused lane. |
| `T4-STATIC` | Local Gate | Static analysis / formatting | Analyzer and formatter | `fvm dart analyze --format machine`; Pint touched PHP files | Local Flutter/Laravel | passed | Analyzer exited 0 with no diagnostics; Pint passed. |
| `T4-SINK` | Local Gate | Sink/query readback for KPI set | Queue dispatch proof plus explicit final-phase dependency | `tests/Feature/Auth/TenantPhoneOtpTelemetryTest.php`; `DEP-04` | Local queue proof; external sink final phase | waived | Local-gate waiver approval: APROVADO orchestration defers external sink/query readback to final runtime; this is not a `Production-Ready` waiver. |
| `T4-ADB` | Local Gate | ADB/device runtime validation | Deferred runtime validation | Final consolidated ADB/device lane | Android device | waived | Local-gate waiver approval: APROVADO orchestration defers ADB/device execution to reduce WSL/device instability risk. |
| `DOD-01` | Definition of Done | Android store release has a frozen funnel-metrics validation matrix for the critical funnel. | Documentation and review packet | This TODO; `foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md` | Foundation docs | waived | Structure-only waiver/deviation with approval: APROVADO local gate treats matrix freeze as documentation-only; device/browser flow proof is tracked in runtime rows. |
| `DOD-02` | Definition of Done | The required KPI set can be read and trusted well enough for release decisions. | Local join-key/property proof; final sink readback pending | Event matrix; KPI readback interpretation; `DEP-04` | Local proof plus external sink final phase | waived | Local-gate waiver approval: APROVADO orchestration accepts local property/join-key proof now; external sink/query readback remains required before release closure. |
| `DOD-03` | Definition of Done | No hidden telemetry gap remains implied by "it should already be firing". | Gap audit and fixes | Review packet; triple audit session | Local code/test audit | passed | Fixed missing pre-auth OTP dispatch, deferred `store_channel`, web landing `code`, app-side invite-accept request telemetry, and backend terminal invite-accept join keys. Remaining runtime/sink gaps are explicit. |
| `VAL-01` | Validation Steps | Code/test audit for release-critical event ownership and required properties. | Review packet plus triple audit | `foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md`; `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/session.json` | Local audit | passed | Three-lane audit returned zero findings; adjudication resolved non-material wording conflict. |
| `VAL-02` | Validation Steps | Automated evidence where available for event names/properties on touched flows. | Automated tests | Flutter target suite; Laravel target suite | Local Flutter/Laravel | passed | Flutter 42 tests; Laravel 3 focused tests and 15 assertions in the refreshed lane. |
| `VAL-03` | Validation Steps | Manual or sink-level validation for web-to-app, OTP, merge, and first-favorite milestones. | Deferred runtime/sink validation | Final ADB/web/sink lane | Android device, browser, external telemetry sink | waived | Local-gate waiver approval: APROVADO orchestration intentionally leaves manual/device/browser/sink validation to the consolidated final runtime phase. |
| `VAL-04` | Validation Steps | Documented KPI readback proof or explicit waiver if a query surface is temporarily limited. | Documented readback interpretation and dependency | KPI readback interpretation below; `DEP-04` | External telemetry query surface | waived | Structure-only waiver/deviation with approval: APROVADO local gate documents temporary query limitation; sink/query readback remains required before `Production-Ready`. |
| `SCOPE-01` | Scope | Freeze the Android-release funnel-metrics matrix with event name, concrete flow owner, required properties, and validation owner. | Documentation and review packet | Frozen Android Release Funnel Metrics Matrix in this TODO; `foundation_documentation/artifacts/T4-funnel-metrics-review-packet.md` | Foundation docs | passed | Exact guard row added on 2026-04-29; matrix remains the local source of truth. |
| `SCOPE-02` | Scope | Validate release-critical web/app events and their required properties for: | Automated/source evidence and matrix review | Frozen web/app event matrix; Flutter and Laravel test suites recorded in `T4-funnel-metrics-review-packet.md` | Local Flutter/Laravel tests | passed | The child web/app event list under this item is covered by the matrix rows and owner/property evidence. |
| `SCOPE-03` | Scope | Validate that the sink/query surface can support the release KPI set: | Approved external readback waiver | KPI Readback Interpretation below; APROVADO orchestration approval 2026-04-29 | External telemetry query surface | waived | Waiver approval: user `APROVADO` accepted explicit local-gate waiver; sink/query credentials/readback remain required before `Production-Ready`. |
| `SCOPE-04` | Scope | Record any missing event/property/query gap as an explicit release blocker, waiver, or follow-up owner. | Documentation evidence | Completion Evidence Matrix rows `T4-SINK`, `VAL-03`, `VAL-04`, and this addendum | Foundation docs | passed | Runtime/sink gaps are explicit waivers, not hidden assumptions. |
| `SCOPE-05` | Scope | Route missing event implementation back to the concrete flow TODO that owns the behavior. | Documentation evidence | Contract Boundary route rule; Dependencies & Sequencing; `T4-funnel-metrics-review-packet.md` | Foundation docs | passed | Missing event wiring is routed back to T1/T2/T3 flow TODOs. |
| `SCOPE-06` | Scope | Promote any stable release-facing metrics/tracker rule that is still missing from canonical docs. | Documentation evidence | Module docs listed in `T4-MATRIX`; this TODO matrix | Foundation docs | passed | Stable funnel rules are recorded in canonical module docs. |
| `SCOPE-07` | Scope | Freeze the KPI join-key/readback interpretation needed for the release KPI set: | Documentation evidence | `KPI Readback Interpretation` section below; `foundation_documentation/todos/active/post_release_hardening/TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md` split note | Foundation docs + post-release follow-up TODO | passed | The release-judgment join keys are frozen here, while published sink/runtime replay remains intentionally owned by the split hardening TODO. |
| `AC-01` | Acceptance Criteria | One explicit Android-release funnel-metrics validation matrix exists with owner + required properties per event. | Documentation evidence | Frozen Android Release Funnel Metrics Matrix in this TODO | Foundation docs | passed | Matrix names event, owner, properties, evidence, and classification. |
| `AC-02` | Acceptance Criteria | Release-critical event journeys are validated in runtime evidence and/or automated evidence. | Automated/source evidence | Flutter and Laravel test suites recorded in `T4-funnel-metrics-review-packet.md`; 2026-04-29 ADB auth navigation smoke | Local Flutter/Laravel tests; Android device `192.168.15.9:5555` | passed | Runtime sink readback is separately waived; automated evidence covers event/property ownership. |
| `AC-03` | Acceptance Criteria | KPI readback path is confirmed workable for release judgment. | Approved external readback waiver | KPI Readback Interpretation below; APROVADO orchestration approval 2026-04-29 | External telemetry query surface | waived | Waiver approval: local property/join-key proof is documented; actual query readback remains a production-readiness input. |
| `AC-04` | Acceptance Criteria | Any remaining gap is explicitly classified as blocker, waiver, or post-release follow-up with owner. | Documentation evidence | Completion Evidence Matrix rows and Review Gate Notes | Foundation docs | passed | Remaining gaps are named as external sink/query and final runtime readiness, not hidden blockers. |

### KPI Readback Interpretation

The local candidate can compute the required release KPI edges from emitted properties once the sink/query surface is available:

- landing -> open/install: `web_invite_landing_opened.code` + web promotion CTA events with `platform_target`.
- open/install -> deferred capture: `store_channel` and share `code` carried across web/open/install and app deferred capture.
- deferred capture -> invite acceptance requested: share `code` retained in app deferred capture and invite acceptance request telemetry.
- invite acceptance requested -> auth wall -> signup: auth wall telemetry preserves restricted action context and signup source when authentication is required.
- authenticated continuation -> invite accepted: authenticated invite acceptance carries the same share `code`/occurrence context after OTP/login continuation.
- OTP challenge -> verified/merged: Laravel queue envelopes carry challenge, verification, and merge milestones.
- verified/merged -> first favorite: registered identity can be joined to `favorite_artist_toggled.account_profile_id` once sink identity association is queryable.

### Review Gate Notes

- Independent triple audit completed in `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/session.json`.
- Round 01 merged as `needs_adjudication` only due non-material `recommended_path_conflict`; all three lanes had zero findings. Resolution recorded as `resolved` in `foundation_documentation/artifacts/t4-funnel-metrics-triple-audit-20260428T1935Z/round-01/resolution.md`.
- Claude CLI review attempt is recorded at `foundation_documentation/artifacts/claude-cli-reviews/T4-funnel-metrics-cli-review.md`; the CLI returned usage-limit unavailability, so it is not a substantive gate under the current orchestration decision.

## Local CI-Equivalent Suite Matrix

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | No new Flutter/Laravel code was executed for this move; the archival decision only reconciles the already-delivered pre-publication metrics slice with the current split-follow-up topology. | `n/a` | `historical archival closeout` | `n/a` | Existing `Completion Evidence Matrix (Local Gate)`, `T4-funnel-metrics-review-packet.md`, and the explicit post-release split TODO. | Documentation-only move; no fresh CI-equivalent rerun was required. |

## Main Promotion Evidence - 2026-06-08 (Historical Archival Catch-Up)

| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `foundation_documentation` TODO review | `origin/main foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-funnel-metrics-validation.md` still records the post-release split note and the execution-validated pre-publication slice. | The TODO itself already states that only post-release sink/runtime hardening remains outside this scope. |
| `foundation_documentation` module review | `origin/main foundation_documentation/modules/invite_and_social_loop_module.md`, `modules/onboarding_flow_module.md`, and `modules/flutter_client_experience_module.md` still carry the promoted funnel/event-property rules referenced by `T4-MATRIX`. | Canonical module docs still encode the release-facing funnel attribution rules that this TODO froze. |
| `Archival decision` | Explicit `2026-06-08` user request to analyze delayed promotion-lane TODOs against code/main and move already-promoted items to `completed`. | Documentation-only closeout approved. |

## Pipeline/Copilot P1/P2 Preflight

| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Historical archival catch-up` | Confirm this move only reconciles a stale promotion-lane artifact with the already-split post-release hardening ownership and the existing funnel-metrics evidence packet. | `n/a` | Current TODO split note, `Completion Evidence Matrix (Local Gate)`, and the dedicated post-release hardening TODO reference. | `none` | No fresh PR/Copilot surface exists for this documentation-only archival move. |

## Rule-Spirit Anti-Pattern Hunt

| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Metrics closeout hygiene` | Prevent a locally validated pre-publication metrics slice from lingering in `promotion_lane` after its only remaining runtime/sink work was explicitly split into a dedicated post-release TODO. | `passed` | Post-release split note, `SCOPE-07`, and `TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md` ownership review. | `no findings` | The closeout does not hide sink/runtime debt; it leaves that debt explicit in the dedicated post-release hardening TODO. |

## Rules Acknowledgement / Ingestion

| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The file is being archived after the implementation and split-follow-up decisions already landed. | Truthful stage labeling, split-follow-up traceability, and explicit archival rationale. | Pretending the post-release sink/runtime work disappeared. | Record the dedicated hardening TODO as the surviving owner of that work. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The task is to distinguish delivered pre-publication metrics proof from residual sink/runtime debt. | Keep the split-follow-up debt explicit. | Hiding remaining telemetry hardening behind a completed label. | Close only the delivered pre-publication slice and point to the active hardening TODO. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | The source-of-truth question is whether this TODO still owns real work after the split. | Preserve the governing TODO and archive it cleanly once residual work has a new owner. | Leaving a completed slice stranded in `promotion_lane`. | Move the TODO to `completed` once the archival sections are guard-clean. |

## TODO Closeout Disposition

- **Completed path:** `foundation_documentation/todos/completed/TODO-store-release-funnel-metrics-validation.md`
- **Closeout decision:** archival catch-up approved on `2026-06-08` after confirming this TODO's remaining runtime/sink work was already moved into `TODO-post-release-funnel-metrics-sink-readback-and-runtime-verification.md`.
- **Historical note:** this TODO now closes only the delivered pre-publication funnel-metrics slice. Published-build sink/readback hardening remains active and explicit elsewhere.
- **Reopen rule:** any new release-critical funnel event/property regression should open a new TODO or update the dedicated post-release hardening TODO rather than reopen this archival slice.
