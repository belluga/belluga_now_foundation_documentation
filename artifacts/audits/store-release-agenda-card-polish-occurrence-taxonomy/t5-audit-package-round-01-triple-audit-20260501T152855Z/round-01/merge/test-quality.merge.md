# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve high findings before delivery.`

## Merged Findings
### F-B8A75694 [high] Programming end_time authoring is not proven through the admin occurrence UI
- **Reviewers:** Test Quality Auditor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `admin-occurrence-programming-end-time-widget-coverage`
- **Suggested action:** Add a focused widget test that enters time and end_time in the occurrence programming sheet and asserts the submitted draft carries endTime.
- **Rationale:** DTO/backend coverage alone does not prove that the occurrence editor field exists, accepts input, and reaches the submitted draft.

### F-5ABE4DB5 [high] EventSearch invite filter semantics changed without focused controller evidence
- **Reviewers:** Test Quality Auditor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `event-search-invite-filter-cycle-controller-coverage`
- **Suggested action:** Add controller tests proving none -> Convites pendingOnly -> Confirmados confirmedOnly -> none, and that confirmedOnly initializes backend query state.
- **Rationale:** The filter split between pending received invites and confirmed attendance needs a focused EventSearch controller test, not only Home Agenda evidence.

### F-3A884457 [high] Backend update coverage does not assert new occurrence fields
- **Reviewers:** Test Quality Auditor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `event-occurrence-update-preserve-clear-coverage`
- **Suggested action:** Add update tests for omitted-field preservation and explicit-empty clearing of owned occurrence profiles, taxonomy terms, and programming items including end_time.
- **Rationale:** Create tests are insufficient because PATCH/update can accidentally clear or fail to preserve occurrence programming end_time, taxonomy overrides, and owned profiles.

## Reviewer Summaries
### Test Quality Auditor
- **Assessment:** The test plan is close, but three behavior paths lacked direct evidence: admin UI authoring of programming end time, EventSearch invite filter semantics, and backend update/PATCH behavior for the new occurrence fields.
- **Recommended path:** `Resolve high findings before delivery.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-T5-001 Programming end_time authoring is not proven through the admin occurrence UI: DTO/backend coverage alone does not prove that the occurrence editor field exists, accepts input, and reaches the submitted draft.
  - [high] TQ-T5-002 EventSearch invite filter semantics changed without focused controller evidence: The filter split between pending received invites and confirmed attendance needs a focused EventSearch controller test, not only Home Agenda evidence.
  - [high] TQ-T5-003 Backend update coverage does not assert new occurrence fields: Create tests are insufficient because PATCH/update can accidentally clear or fail to preserve occurrence programming end_time, taxonomy overrides, and owned profiles.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

