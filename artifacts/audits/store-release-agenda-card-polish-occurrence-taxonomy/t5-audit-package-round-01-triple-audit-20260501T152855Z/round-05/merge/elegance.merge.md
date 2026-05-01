# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-05/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Fix before closing the gate: canonicalize each supplied occurrence identity to the existing occurrence document key, reject duplicate canonical targets regardless of whether the row used occurrence_id, id, or occurrence_slug, add a regression test for id-vs-slug alias duplication, then rerun the focused Laravel identity suite.`

## Merged Findings
### F-F676FF28 [high] Occurrence identity validation still allows duplicate canonical targets via id/slug aliasing
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** In assertOccurrenceIdentityConsistency, resolve every provided occurrence_id/id/occurrence_slug to a canonical existing occurrence id when existing occurrences are present, track those canonical ids, and reject any duplicate canonical reference even if the raw fields differ. Add a regression where two update rows target the same stored occurrence using occurrence_id in one row and occurrence_slug in another, expecting a 422 validation error.
- **Rationale:** The claimed one-to-one identity gate only tracks duplicate raw occurrence_id values and duplicate raw occurrence_slug values independently, then validates mismatched pairs when both fields are on the same row. It does not reject a payload where one row supplies occurrence_id for an existing occurrence and another row supplies that same occurrence's occurrence_slug. Both rows can resolve to the same existing document before sync, so the later row can overwrite the earlier row's occurrence data instead of producing two deterministic occurrence rows. That is a structural remnant of non-canonical identity resolution and carries correctness risk for mixed update payloads.

## Reviewer Summaries
### elegance
- **Assessment:** Not clean. The round-04 identity work closes duplicate-id, duplicate-slug, unknown-identity, and mismatched id/slug cases, but it still does not enforce canonical one-to-one mapping when two payload rows reference the same existing occurrence through different identity fields.
- **Recommended path:** `Fix before closing the gate: canonicalize each supplied occurrence identity to the existing occurrence document key, reject duplicate canonical targets regardless of whether the row used occurrence_id, id, or occurrence_slug, add a regression test for id-vs-slug alias duplication, then rerun the focused Laravel identity suite.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R05-001 Occurrence identity validation still allows duplicate canonical targets via id/slug aliasing: The claimed one-to-one identity gate only tracks duplicate raw occurrence_id values and duplicate raw occurrence_slug values independently, then validates mismatched pairs when both fields are on the same row. It does not reject a payload where one row supplies occurrence_id for an existing occurrence and another row supplies that same occurrence's occurrence_slug. Both rows can resolve to the same existing document before sync, so the later row can overwrite the earlier row's occurrence data instead of producing two deterministic occurrence rows. That is a structural remnant of non-canonical identity resolution and carries correctness risk for mixed update payloads.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

