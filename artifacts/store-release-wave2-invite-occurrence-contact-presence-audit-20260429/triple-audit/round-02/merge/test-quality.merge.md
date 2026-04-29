# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-02/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Continue with targeted test/code closure before release promotion: add failing tests for same-event different-occurrence received invites and visible feed date/time context, then harden Flutter confirmed-occurrence decoding so stale or missing contract fields fail loudly or surface an explicit error instead of clearing state.`

## Merged Findings
### F-DC3931DE [high] Received invite and feed occurrence context is still not proven
- **Reviewers:** test-quality-no-context-round-02
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add fail-first coverage for a multi-occurrence event where two pending invites share event_id but differ by occurrence_id; assert selected event detail shows only the selected occurrence invite. Add backend and Flutter feed/read assertions that occurrence date/time/context shown to the user comes from the selected occurrence.
- **Rationale:** The TODO still marks invite feed/read occurrence rendering as planned and Flutter received-invite context as incomplete, while the exercised tests do not close that behavior. Backend feed coverage asserts target_ref.occurrence_id but not occurrence date/time/context. Flutter repository/decoder tests assert occurrence_id parsing, but not visible occurrence date/time semantics.

### F-499DBC45 [high] Confirmed-occurrence consumer contract can fail silently
- **Reviewers:** test-quality-no-context-round-02
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Update the DAL test fixture to canonical confirmed_occurrence_ids and add repository tests for canonical decode plus malformed/missing-field behavior. Prefer throwing or surfacing an explicit contract error for missing confirmed_occurrence_ids.
- **Rationale:** The Flutter backend test for fetchConfirmedOccurrenceIds still uses an old data.event_ids fixture while only asserting auth/path behavior. The repository implementation reads confirmed_occurrence_ids, but if that field is absent it clears confirmedOccurrenceIdsStream and returns.

## Reviewer Summaries
### test-quality-no-context-round-02
- **Assessment:** Not delivery-ready for promotion closure. Focused tests cover many mutation and DTO paths and no skip/test.only or test-support route bypass was found, but two behavior-defining consumer contract gaps remain: received-invite/feed occurrence context is not proven, and confirmed-occurrence response handling can still mask a stale backend contract.
- **Recommended path:** `Continue with targeted test/code closure before release promotion: add failing tests for same-event different-occurrence received invites and visible feed date/time context, then harden Flutter confirmed-occurrence decoding so stale or missing contract fields fail loudly or surface an explicit error instead of clearing state.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-R2-01 Received invite and feed occurrence context is still not proven: The TODO still marks invite feed/read occurrence rendering as planned and Flutter received-invite context as incomplete, while the exercised tests do not close that behavior. Backend feed coverage asserts target_ref.occurrence_id but not occurrence date/time/context. Flutter repository/decoder tests assert occurrence_id parsing, but not visible occurrence date/time semantics.
  - [high] TQA-R2-02 Confirmed-occurrence consumer contract can fail silently: The Flutter backend test for fetchConfirmedOccurrenceIds still uses an old data.event_ids fixture while only asserting auth/path behavior. The repository implementation reads confirmed_occurrence_ids, but if that field is absent it clears confirmedOccurrenceIdsStream and returns.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

