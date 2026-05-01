# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-02/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the elegance gate until occurrence identity is carried through schedule normalization and used by the sync layer for existing occurrence upserts, with index fallback only for genuinely new occurrences and a regression test covering reorder plus omitted occurrence-owned fields.`

## Merged Findings
### F-1C4A99D5 [high] Occurrence update identity is resolved, then discarded before persistence
- **Reviewers:** no-context-elegance-round-02
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Carry occurrence_id and occurrence_slug through the normalized schedule payload and teach EventOccurrenceSyncService to upsert existing occurrences by id or slug before falling back to occurrence_index for new rows. Add a Laravel regression test that creates two occurrences with different owned taxonomy/programming values, updates them in a date order that changes their sorted indexes while omitting owned arrays, and asserts each original occurrence id keeps its own data.
- **Rationale:** Flutter now sends occurrence_id and occurrence_slug for full-form saves, and EventManagementService uses those fields to resolve omitted occurrence-owned payloads. However, the normalized occurrence rows keep only date, owned parties, taxonomy, and programming fields before sorting, and EventOccurrenceSyncService still finds existing documents only by occurrence_index. If an existing multi-occurrence event is reordered by date, the old index-based sync can write one occurrence's preserved taxonomy/programming/linked-profile data onto another occurrence document while public detail and invite flows still address occurrences by id/slug. This is duplicate old/new identity handling in the same update path and can create data drift inside the approved occurrence-owned taxonomy contract.

## Reviewer Summaries
### no-context-elegance-round-02
- **Assessment:** The bounded package is mostly coherent, but one occurrence-update path now mixes identity-based preservation with index-based persistence. That creates a real structural drift risk for occurrence-owned taxonomy, programming, and linked-profile data when an existing multi-occurrence event is reordered.
- **Recommended path:** `Do not close the elegance gate until occurrence identity is carried through schedule normalization and used by the sync layer for existing occurrence upserts, with index fallback only for genuinely new occurrences and a regression test covering reorder plus omitted occurrence-owned fields.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R02-001 Occurrence update identity is resolved, then discarded before persistence: Flutter now sends occurrence_id and occurrence_slug for full-form saves, and EventManagementService uses those fields to resolve omitted occurrence-owned payloads. However, the normalized occurrence rows keep only date, owned parties, taxonomy, and programming fields before sorting, and EventOccurrenceSyncService still finds existing documents only by occurrence_index. If an existing multi-occurrence event is reordered by date, the old index-based sync can write one occurrence's preserved taxonomy/programming/linked-profile data onto another occurrence document while public detail and invite flows still address occurrences by id/slug. This is duplicate old/new identity handling in the same update path and can create data drift inside the approved occurrence-owned taxonomy contract.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

