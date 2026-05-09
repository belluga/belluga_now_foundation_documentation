# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-04/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Add focused regression assertions before closing the audit gate: assert all occurrence_slug values remain unique after inserting an unidentified occurrence before identified rows, and add no-geo stream occurrence_ids coverage through the pipeline helper and preferably the /events/stream endpoint.`

## Merged Findings
### F-C40DD8F9 [high] occurrence_ids stream filtering lacks no-geo backend evidence
- **Reviewers:** test-quality-audit-round-04
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a non-geo buildStreamPipelineForTest assertion that the first $match contains the requested _id $in predicate, and add or extend an /events/stream?occurrence_ids[]=... feature test to prove unrelated occurrence deltas are excluded.
- **Rationale:** The pipeline regression test at tests/Feature/Events/AgendaAndEventsControllerTest.php:826-873 covers geo agenda, search agenda, and geo stream, but it does not assert the non-geo stream initial $match. Existing stream endpoint tests at lines 982-1127 do not exercise occurrence_ids at all. Since EventStreamRequest inherits AgendaIndexRequest, occurrence_ids is part of the stream contract, and a non-geo stream regression could escape the current focused suite.

### F-2192214A [high] Inserted-occurrence slug uniqueness is not covered by the regression test
- **Reviewers:** test-quality-audit-round-04
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend test_event_update_inserting_unidentified_occurrence_preserves_existing_identity_rows to assert that insertedOccurrence, freshFirst, and freshSecond have three distinct occurrence_slug values, and ideally verify public slug alias selection still resolves the intended occurrence.
- **Rationale:** The round package says the round-03 fix includes avoiding existing or claimed occurrence slugs when a new unidentified occurrence is inserted before existing occurrences. The regression test at tests/Feature/Events/EventCrudControllerTest.php:1744 checks that the inserted document is new and that existing identity rows keep their payloads, but its assertions at lines 1829-1840 never compare occurrence_slug values. A regression that reuses the old index-derived slug while preserving ids and payloads could still pass this test if no database unique index catches it.

## Reviewer Summaries
### test-quality-audit-round-04
- **Assessment:** Not delivery-ready from the test-quality lens. The focused tests are behavior-oriented and no hard bypass markers were found, but two round-03 fix claims are not fully protected: inserted occurrence slug uniqueness is not asserted, and occurrence_ids stream coverage misses the non-geo stream contract branch.
- **Recommended path:** `Add focused regression assertions before closing the audit gate: assert all occurrence_slug values remain unique after inserting an unidentified occurrence before identified rows, and add no-geo stream occurrence_ids coverage through the pipeline helper and preferably the /events/stream endpoint.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-R04-001 Inserted-occurrence slug uniqueness is not covered by the regression test: The round package says the round-03 fix includes avoiding existing or claimed occurrence slugs when a new unidentified occurrence is inserted before existing occurrences. The regression test at tests/Feature/Events/EventCrudControllerTest.php:1744 checks that the inserted document is new and that existing identity rows keep their payloads, but its assertions at lines 1829-1840 never compare occurrence_slug values. A regression that reuses the old index-derived slug while preserving ids and payloads could still pass this test if no database unique index catches it.
  - [high] TQ-R04-002 occurrence_ids stream filtering lacks no-geo backend evidence: The pipeline regression test at tests/Feature/Events/AgendaAndEventsControllerTest.php:826-873 covers geo agenda, search agenda, and geo stream, but it does not assert the non-geo stream initial $match. Existing stream endpoint tests at lines 982-1127 do not exercise occurrence_ids at all. Since EventStreamRequest inherits AgendaIndexRequest, occurrence_ids is part of the stream contract, and a non-geo stream regression could escape the current focused suite.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

