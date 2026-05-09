# Title
Post-Release Hardening: Funnel Metrics Sink Readback and Runtime Verification

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Created on:** `2026-05-03`
- **Source split:** `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-funnel-metrics-validation.md`
- **Why this exists:** the pre-publication T4 subset is already locally validated and can move through promotion, but the remaining proof requires published/runtime conditions and real telemetry-sink readback that cannot be closed honestly before release.
- **Release-gate status:** not a blocker for the current Android publication cut unless a new business decision explicitly promotes it back into the release gate.

## Context
The release lane already froze the funnel matrix, repaired the missing event/property gaps, and validated the locally testable parts of the acquisition -> continuation -> identity -> first-social-loop funnel. What remains is the hard part that only makes sense after publication or in production-like runtime conditions:

- confirm that the published app/web journeys emit the expected events in the real sink,
- confirm the final event naming observed in Mixpanel for authenticated invite acceptance,
- confirm KPI readback and joinability using real sink data rather than only local join-key interpretation,
- replay the key journeys on published builds and verify that the sink sees them end to end.

This TODO owns that post-release closure and nothing broader.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-02-post-release-observability`
- **Why this is the right current slice:** the remaining work is a bounded runtime-observability follow-up, not product implementation.
- **Direct-to-TODO rationale:** safe. The unresolved work is already narrow, explicitly identified, and depends on production-like runtime evidence rather than open product definition.

## Contract Boundary
- This TODO owns post-release funnel-metrics runtime replay and sink/query readback only.
- It validates the published reality of the already-delivered funnel contracts.
- It does not own broad analytics strategy, provider replacement, or new product instrumentation unless runtime verification exposes a concrete bug.
- If runtime verification finds an implementation defect, route the fix back to the owning flow TODO/module and keep this TODO as the validation owner.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Telemetry-Sink`, `Runtime-Verification`, `Mixpanel-Readback`, `Cross-Stack`
- **Next exact step:** once the published runtime and sink access are available, execute the journey matrix below and capture real Mixpanel readback for each missing milestone.

## References
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-funnel-metrics-validation.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`

## Current Known Snapshot (2026-05-03)
- **Observed in Mixpanel screenshot:** `app_auth_wall_triggered`, `favorite_artist_toggled`
- **Observed but not closure-grade:** `App Session`, `First App Open`, `app_init`, `app_lifecycle`, `screen_view`, `section_viewed`, `invite_opened`, `agenda_radius_changed`, `map_catalog_filter_applied`, `map_filter_cleared`, `map_location_resolved`, `poi_opened`
- **Still missing in the screenshot/readback:** `web_invite_landing_opened`, `web_open_app_clicked`, `web_install_clicked`, `app_deferred_deep_link_captured`, `app_deferred_deep_link_capture_failed`, `app_invite_acceptance_requested`, terminal authenticated invite acceptance (`app_invite_accepted` or backend-equivalent `invite.accepted`), `app_signup_completed`, `otp_challenge_started`, `otp_verified`, `auth_merge_completed`

## Scope
- [ ] Confirm which terminal acceptance event name actually lands in Mixpanel for authenticated invite acceptance (`app_invite_accepted`, `invite.accepted`, or a mapped equivalent).
- [ ] Replay the critical published journeys and confirm sink arrival for:
  - `web_invite_landing_opened`
  - `web_open_app_clicked`
  - `web_install_clicked`
  - `app_deferred_deep_link_captured`
  - `app_deferred_deep_link_capture_failed`
  - `app_invite_acceptance_requested`
  - terminal authenticated invite acceptance
  - `app_signup_completed`
  - `otp_challenge_started`
  - `otp_verified`
  - `auth_merge_completed`
  - `favorite_artist_toggled`
- [ ] Validate that real sink payloads preserve the expected join keys for the release KPI chain.
- [ ] Validate deduplication and identity-merge interpretation against real sink behavior, not only local test doubles.
- [ ] Record any runtime-only telemetry gap as either:
  - concrete bug to fix in the owning flow, or
  - accepted observation limitation with explicit owner and query workaround.

## Out of Scope
- [ ] New analytics strategy or dashboard programs beyond the release funnel.
- [ ] Replacing Mixpanel or redesigning provider contracts.
- [ ] Reopening the already-delivered release funnel matrix unless runtime evidence proves the contract itself was wrong.

## Dependencies & Sequencing
- [ ] `DEP-01` Published app/web runtime must be available for replay.
- [ ] `DEP-02` Mixpanel project access with real event inspection/query capability must be available.
- [ ] `DEP-03` The promotion-lane T4 artifact remains the authority for the delivered pre-publication contract; this TODO only validates published behavior.

## Definition of Done
- [ ] Every remaining missing funnel milestone is either observed in the real sink or explicitly classified with owner/reason.
- [ ] The terminal authenticated invite-acceptance event name is unambiguous in Mixpanel.
- [ ] Real KPI joinability is proven from sink data, not only from local property interpretation.
- [ ] Any runtime-only discrepancy is routed to the owning flow TODO and revalidated here after the fix.

## Validation Steps
- [ ] Run browser/public-web replay for invite landing and promotion CTA events.
- [ ] Run published-app replay for deferred capture, invite-accept request, auth wall, signup, OTP, and first-favorite milestones.
- [ ] Inspect Mixpanel event stream/readback and capture evidence for each milestone.
- [ ] Reconcile event naming and join-key reality against the promotion-lane T4 matrix.

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Why:** the product contract is already frozen, but published-runtime replay plus sink inspection still spans web, app, backend terminal events, and analytics readback.
