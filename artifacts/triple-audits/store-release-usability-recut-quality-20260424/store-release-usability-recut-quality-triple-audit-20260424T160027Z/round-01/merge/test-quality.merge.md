# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-recut-quality-20260424/store-release-usability-recut-quality-triple-audit-20260424T160027Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Address the medium browser integration gap before promotion, or explicitly narrow the evidence claim to restored-selection rendering plus unit/controller coverage. Keep the existing unit/widget/API tests; add one live browser click-through proof for Home and one for Discovery that selects a primary filter/taxonomy through UI and asserts the resulting backend request or visible filtered outcome.`

## Merged Findings
### F-E00685D2 [medium] Home/Discovery browser tests bypass the real filter selection integration path
- **Reviewers:** test-quality-audit-no-context
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add live browser assertions that click the seeded Home and Discovery primary filters through the UI, optionally click a taxonomy term, and wait for the real schedule/account-profile API request containing the expected type/taxonomy query values. Keep direct storage seeding only for a separate restored-state test.
- **Rationale:** The Playwright filter checks seed selected primary filters by writing encrypted FlutterSecureStorage entries, reload the app, then assert panel text. That proves restored-selection rendering and taxonomy compatibility, but it does not prove that a user can click a primary filter, persist the selection through the app wiring, and trigger the expected backend query. Unit/widget/controller tests cover parts of this, but the live browser evidence can still pass if screen wiring between user tap, selection persistence, and request dispatch regresses.

### F-ADF7C217 [low] Navigation matrix declaration test can inflate behavioral pass counts
- **Reviewers:** test-quality-audit-no-context
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep the matrix check as metadata validation, but ensure evidence reports distinguish it from behavioral browser coverage. Prefer excluding declaration-only tests from release behavior pass counts.
- **Rationale:** The added Playwright test for the NAV-01..NAV-23 matrix validates that a constant list contains ids, titles, and proof text. That is useful metadata hygiene, but it is not product behavior and can be misleading if counted alongside mutation/browser behavior passes.

## Reviewer Summaries
### test-quality-audit-no-context
- **Assessment:** The changed tests are mostly effective: they include meaningful payload assertions, negative UI assertions, real API/browser flows, and no direct skip/only bypasses in the reviewed diffs. The main test-quality weakness is that the public Home/Discovery filter browser proof shortcuts the user selection path by injecting FlutterSecureStorage state, so the real click-to-selection-to-backend-query integration is not proven end-to-end.
- **Recommended path:** `Address the medium browser integration gap before promotion, or explicitly narrow the evidence claim to restored-selection rendering plus unit/controller coverage. Keep the existing unit/widget/API tests; add one live browser click-through proof for Home and one for Discovery that selects a primary filter/taxonomy through UI and asserts the resulting backend request or visible filtered outcome.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] TQA-01 Home/Discovery browser tests bypass the real filter selection integration path: The Playwright filter checks seed selected primary filters by writing encrypted FlutterSecureStorage entries, reload the app, then assert panel text. That proves restored-selection rendering and taxonomy compatibility, but it does not prove that a user can click a primary filter, persist the selection through the app wiring, and trigger the expected backend query. Unit/widget/controller tests cover parts of this, but the live browser evidence can still pass if screen wiring between user tap, selection persistence, and request dispatch regresses.
  - [low] TQA-02 Navigation matrix declaration test can inflate behavioral pass counts: The added Playwright test for the NAV-01..NAV-23 matrix validates that a constant list contains ids, titles, and proof text. That is useful metadata hygiene, but it is not product behavior and can be misleading if counted alongside mutation/browser behavior passes.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

