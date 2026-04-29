# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Repair stale integration fixtures, add multi-occurrence invite and share-code occurrence assertions, and keep ADB/device evidence explicit as a deferred consolidated gate if not run in this round.`

## Merged Findings
### F-B47604DD [high] Share materialization/acceptance and visible received-invite occurrence context were weakly asserted
- **Reviewers:** test-quality-reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Assert target_ref.occurrence_id and persisted InviteEdge.occurrence_id on share create, materialize, accept, and replay paths; add Flutter visible occurrence coverage or document the remaining ADB gate.
- **Rationale:** Round 01 did not consistently assert share materialized/accepted response and persisted edge occurrence identity, leaving the share-code root cause under-protected.

### F-CFBCB272 [high] Runtime and integration evidence for the reopened QA symptoms was deferred
- **Reviewers:** test-quality-reviewer
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Repair the fixture to include a selected occurrence and keep the ADB scenarios as explicit consolidated device-gate evidence until they can be run.
- **Rationale:** Round 01 listed exact ADB/device symptoms as deferred and one modified integration fixture asserted parent event id instead of selected occurrence id.

### F-A6D0482A [high] Occurrence-scoped duplicate and credited-acceptance behavior was not tested across two occurrences of the same event
- **Reviewers:** test-quality-reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add tests with one event and two occurrences proving duplicates and supersession are scoped to occurrence, not parent event.
- **Rationale:** The invite tests used helper target refs that always selected the first occurrence, so event-keyed duplicate or credited-winner bugs could still pass.

## Reviewer Summaries
### test-quality-reviewer
- **Assessment:** Not delivery-ready in round 01. Focused unit and feature evidence covered part of the cutover, but runtime/integration evidence and occurrence-specific backend assertions were incomplete.
- **Recommended path:** `Repair stale integration fixtures, add multi-occurrence invite and share-code occurrence assertions, and keep ADB/device evidence explicit as a deferred consolidated gate if not run in this round.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQA-01 Runtime and integration evidence for the reopened QA symptoms was deferred: Round 01 listed exact ADB/device symptoms as deferred and one modified integration fixture asserted parent event id instead of selected occurrence id.
  - [high] TQA-02 Occurrence-scoped duplicate and credited-acceptance behavior was not tested across two occurrences of the same event: The invite tests used helper target refs that always selected the first occurrence, so event-keyed duplicate or credited-winner bugs could still pass.
  - [high] TQA-03 Share materialization/acceptance and visible received-invite occurrence context were weakly asserted: Round 01 did not consistently assert share materialized/accepted response and persisted edge occurrence identity, leaving the share-code root cause under-protected.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

