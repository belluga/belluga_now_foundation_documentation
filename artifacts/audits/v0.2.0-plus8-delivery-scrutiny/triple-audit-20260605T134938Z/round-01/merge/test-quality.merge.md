# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/v0.2.0-plus8-delivery-scrutiny/triple-audit-20260605T134938Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `block`

## Merged Findings
### F-971BF993 [high] public-taxonomy-canonicalization-and-runtime-facets is at Pending stage with zero associated test coverage
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Treat this as an absolute blocker for promotion-lane readiness. The TODO must reach at minimum Local-Implemented with its own test matrix before a lane-level test-quality assessment can pass. Do not aggregate this TODO's state into any green readiness signal until tests exist and pass.
- **Rationale:** This TODO is explicitly labeled release-blocking and remains at Pending stage. No test for its acceptance criteria can exist yet because implementation has not started. The lane cannot achieve meaningful test-quality closure while a release-blocking TODO has no implementation and therefore no tests. Any lane-level green signal that ignores this TODO is structurally false.

### F-D0200A06 [high] discovery_filters.spec.js carries status-only assertions that cannot catch visible-label regression
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add DOM-level assertions that confirm each filter chip/option renders its resolved human-readable label before any user selection event. Drive the browser to the Discovery and Home filter surfaces without interacting and assert visible text content for each filter dimension. These assertions must fail on the pre-fix state to be valid regression guards.
- **Rationale:** The test quality audit scan explicitly flagged status-only assertion hints in the modified browser spec. The user reported that filter labels remained blank/placeholder until selection, meaning the regression is a DOM-visible-label failure. A status-only assertion (e.g., response key present, HTTP 200) passes whether or not the resolved human label is rendered in the DOM before selection. The current spec modification does not demonstrate it asserts the pre-selection visible label state that was confirmed broken.

### F-750664A4 [high] Synthetic fixtures in event_related_profile_groups_test.dart cannot reproduce admin chip-count readback mismatch
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add an integration test or diagnostic spec that performs the full admin authoring cycle via real API endpoints: create group with N profiles, persist, fetch readback, assert rendered chip count matches N. This must be driven through the same API surface the admin UI uses, not a test-owned fixture builder.
- **Rationale:** The confirmed regression is: admin surface reports 4 profiles selected, but the group editor renders only 2 chips. This failure requires the real admin authoring cycle — admin creates/edits a group with 4 profiles, persists via the actual API path, then re-reads the group state for rendering. Synthetic fixtures inject pre-shaped domain objects and bypass the persistence and readback steps entirely. Any fixture that correctly supplies 2 or 4 profiles will green-pass the test regardless of whether the real authoring path is broken.

### F-E870402C [high] No end-to-end test covers public event aggregate tab after admin occurrence + profile-group authoring
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a browser or integration test that: (1) authors an occurrence with at least one profile group via the admin API path, (2) loads the public event route for that event, (3) asserts the aggregate tab/group heading is visible. This test must fail before the fix and pass after to qualify as valid regression coverage.
- **Rationale:** The user confirmed that the public event page still shows missing aggregate tabs after admin editing. The path that must be validated is: admin creates occurrence → assigns profile groups to occurrence → public event route is loaded → aggregate group/tab appears. The identified tests (immersive_event_detail_screen_test.dart, event_related_profile_groups_test.dart) use synthetic domain objects and do not exercise the persistence-to-public-render cycle. This is a final-behavior gap as defined by the gate calibration.

### F-FF535AC2 [high] API-level seeding in event_profile_groups_runtime.diagnostic.spec.js bypasses the admin authoring path that produced the regression
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend the diagnostic spec to include at least one scenario where the group is authored through the admin UI form (or the same HTTP payload the admin form sends) rather than a pre-shaped API call. Assert the readback state and public event tab presence after this authored-path write.
- **Rationale:** The diagnostic spec seeds profile-group state directly at API level. The chip-count/readback mismatch and the missing public aggregate tab are both downstream of the admin editor persisting groups through the full UI-to-API-to-store-to-readback path. If the defect lives in the admin editor's write path, field merging, or the group membership serializer, API seeding injects already-correct state and the spec will permanently pass while the actual admin authoring path remains broken.

### F-5ABB4743 [high] ADB/device navigation evidence still pending for event-profile-groups despite confirmed real-device back-navigation failure
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before promotion-lane entry, require at minimum one recorded device test run (ADB or physical device) that navigates the implicated profile-group routes, asserts non-navigable profiles do not respond to tap, and completes back-navigation without state corruption. Record session output as a formal CI-equivalent artifact post-addendum.
- **Rationale:** The event-profile-groups-canonical-consistency TODO explicitly carries ADB/device navigation as pending. The user confirmed broken back-navigation semantics on real device/runtime routes — some profiles remained clickable when they should not navigate. Device navigation failures are not reproducible by browser or unit tests alone; they require the native navigation stack. Treating device evidence as paperwork rather than a blocking gap misclassifies a confirmed regression category.

### F-C125FE1F [medium] nested-account-profile-groups parent TODO at Implementation-Ready while child is treated as delivery-complete
- **Reviewers:** Claude Test Quality
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Reconcile the parent and child TODO DOD matrices explicitly. Either formally close the parent by mapping each of its acceptance criteria to a child delivery artifact, or identify any parent-scope criteria that remain undelivered and untest. Do not accept the lane as closed while this split is unresolved.
- **Rationale:** The structural split where the child TODO (event-profile-groups-canonical-consistency) carries the delivery claim while the parent TODO (nested-account-profile-groups) remains at Implementation-Ready creates a coverage coherence gap. Tests written against the child scope may not cover the parent's full acceptance criteria. If the parent TODO's DOD includes behaviors that were not re-scoped into the child, those behaviors have no test coverage and no delivery claim.

### F-B7BF0519 [medium] Stale 2026-05-28 CI-equivalent artifact over-reused across multiple TODOs whose addenda postdate it
- **Reviewers:** Claude Test Quality
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require each TODO that has an open or recently closed addendum to produce a fresh CI-equivalent artifact timestamped after the addendum's last substantive change. The 2026-05-28 artifact should be explicitly retired as primary evidence for any TODO with post-2026-05-28 addendum activity.
- **Rationale:** Multiple TODOs cite the same reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md artifact as their CI-equivalent evidence. User-reported regressions and addenda were filed after that date. Validation evidence collected before an addendum was opened is not valid evidence that the addendum's acceptance criteria are met. Tests that were green at 2026-05-28 may be green against an older code state that did not include the addendum changes.

### F-744579BB [medium] Non-navigable public profile card coverage does not demonstrate runtime surface suppression
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a browser-level assertion that, given a profile with non-navigable flag set, the rendered card either has no tap handler, displays no navigation affordance, or explicitly navigates nowhere. This assertion must be driven against the rendered DOM/widget state, not the API response.
- **Rationale:** The queryability tests (account_profile_queryability_runtime.diagnostic.spec.js and AccountProfilesControllerTest.php) validate that the backend correctly marks profiles as non-queryable or non-navigable in API responses. They do not demonstrate that the frontend actually suppresses or disables navigation for those cards in the rendered UI. The confirmed regression was that non-navigable profiles still appeared clickable. Backend-correct flags are necessary but not sufficient; the UI rendering path must also be covered.

### F-DAE62399 [medium] Full-universe aggregation assertion absent from Home and Discovery filter test suites
- **Reviewers:** Claude Test Quality
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add assertions that verify filter option counts/values remain stable (full-universe) when the result set is narrowed by an unrelated filter dimension. For example: apply a date range filter, then assert that event-type filter options still enumerate the full catalog. Pair this with a backend test that verifies the aggregation query shape does not include a WHERE clause derived from unrelated active filters.
- **Rationale:** The user reported empty-result filter options appearing in production, which directly implies the filter option universe is being computed from a paginated or filtered result set rather than the full backend universe. The current test suite for tenant_home_agenda_controller and discovery_screen_controller asserts that filter keys are present in API responses, but does not assert that the option set is independent of the current pagination window or result subset. A test that queries with a narrow filter and then checks the available filter options may still pass even if the options are scoped to the filtered result.

## Reviewer Summaries
### Claude Test Quality
- **Assessment:** The test suite validates shape and contract existence but does not protect against any of the five confirmed user-reported regressions. The modified browser spec (discovery_filters.spec.js) carries status-only assertion hints rather than DOM-visible-label assertions, synthetic fixtures in event-group domain/widget tests cannot reproduce the admin chip-count readback mismatch, and API-level seeding in diagnostic specs bypasses the exact admin authoring path where the regression was observed. One release-blocking TODO (public-taxonomy-canonicalization-and-runtime-facets) is still at Pending stage with no associated test coverage. Device/ADB navigation evidence for event-profile-groups remains pending despite a confirmed user-observed back-navigation failure. The aggregate effect is that the lane could pass its current test matrix while all five user-reported failures remain reproducible in production. This is a blocking test-quality state.
- **Recommended path:** `block`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-01 discovery_filters.spec.js carries status-only assertions that cannot catch visible-label regression: The test quality audit scan explicitly flagged status-only assertion hints in the modified browser spec. The user reported that filter labels remained blank/placeholder until selection, meaning the regression is a DOM-visible-label failure. A status-only assertion (e.g., response key present, HTTP 200) passes whether or not the resolved human label is rendered in the DOM before selection. The current spec modification does not demonstrate it asserts the pre-selection visible label state that was confirmed broken.
  - [high] TQ-02 Synthetic fixtures in event_related_profile_groups_test.dart cannot reproduce admin chip-count readback mismatch: The confirmed regression is: admin surface reports 4 profiles selected, but the group editor renders only 2 chips. This failure requires the real admin authoring cycle — admin creates/edits a group with 4 profiles, persists via the actual API path, then re-reads the group state for rendering. Synthetic fixtures inject pre-shaped domain objects and bypass the persistence and readback steps entirely. Any fixture that correctly supplies 2 or 4 profiles will green-pass the test regardless of whether the real authoring path is broken.
  - [high] TQ-03 API-level seeding in event_profile_groups_runtime.diagnostic.spec.js bypasses the admin authoring path that produced the regression: The diagnostic spec seeds profile-group state directly at API level. The chip-count/readback mismatch and the missing public aggregate tab are both downstream of the admin editor persisting groups through the full UI-to-API-to-store-to-readback path. If the defect lives in the admin editor's write path, field merging, or the group membership serializer, API seeding injects already-correct state and the spec will permanently pass while the actual admin authoring path remains broken.
  - [high] TQ-04 ADB/device navigation evidence still pending for event-profile-groups despite confirmed real-device back-navigation failure: The event-profile-groups-canonical-consistency TODO explicitly carries ADB/device navigation as pending. The user confirmed broken back-navigation semantics on real device/runtime routes — some profiles remained clickable when they should not navigate. Device navigation failures are not reproducible by browser or unit tests alone; they require the native navigation stack. Treating device evidence as paperwork rather than a blocking gap misclassifies a confirmed regression category.
  - [high] TQ-05 public-taxonomy-canonicalization-and-runtime-facets is at Pending stage with zero associated test coverage: This TODO is explicitly labeled release-blocking and remains at Pending stage. No test for its acceptance criteria can exist yet because implementation has not started. The lane cannot achieve meaningful test-quality closure while a release-blocking TODO has no implementation and therefore no tests. Any lane-level green signal that ignores this TODO is structurally false.
  - [high] TQ-06 No end-to-end test covers public event aggregate tab after admin occurrence + profile-group authoring: The user confirmed that the public event page still shows missing aggregate tabs after admin editing. The path that must be validated is: admin creates occurrence → assigns profile groups to occurrence → public event route is loaded → aggregate group/tab appears. The identified tests (immersive_event_detail_screen_test.dart, event_related_profile_groups_test.dart) use synthetic domain objects and do not exercise the persistence-to-public-render cycle. This is a final-behavior gap as defined by the gate calibration.
  - [medium] TQ-07 Full-universe aggregation assertion absent from Home and Discovery filter test suites: The user reported empty-result filter options appearing in production, which directly implies the filter option universe is being computed from a paginated or filtered result set rather than the full backend universe. The current test suite for tenant_home_agenda_controller and discovery_screen_controller asserts that filter keys are present in API responses, but does not assert that the option set is independent of the current pagination window or result subset. A test that queries with a narrow filter and then checks the available filter options may still pass even if the options are scoped to the filtered result.
  - [medium] TQ-08 Stale 2026-05-28 CI-equivalent artifact over-reused across multiple TODOs whose addenda postdate it: Multiple TODOs cite the same reconcile_validation_status_20260528_012558_full_ci_equivalent_atlas_runtime.md artifact as their CI-equivalent evidence. User-reported regressions and addenda were filed after that date. Validation evidence collected before an addendum was opened is not valid evidence that the addendum's acceptance criteria are met. Tests that were green at 2026-05-28 may be green against an older code state that did not include the addendum changes.
  - [medium] TQ-09 Non-navigable public profile card coverage does not demonstrate runtime surface suppression: The queryability tests (account_profile_queryability_runtime.diagnostic.spec.js and AccountProfilesControllerTest.php) validate that the backend correctly marks profiles as non-queryable or non-navigable in API responses. They do not demonstrate that the frontend actually suppresses or disables navigation for those cards in the rendered UI. The confirmed regression was that non-navigable profiles still appeared clickable. Backend-correct flags are necessary but not sufficient; the UI rendering path must also be covered.
  - [medium] TQ-10 nested-account-profile-groups parent TODO at Implementation-Ready while child is treated as delivery-complete: The structural split where the child TODO (event-profile-groups-canonical-consistency) carries the delivery claim while the parent TODO (nested-account-profile-groups) remains at Implementation-Ready creates a coverage coherence gap. Tests written against the child scope may not cover the parent's full acceptance criteria. If the parent TODO's DOD includes behaviors that were not re-scoped into the child, those behaviors have no test coverage and no delivery claim.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

