# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-04/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close as clean until occurrence update payloads reject duplicate occurrence identities and inconsistent occurrence_id/occurrence_slug pairs before normalization/sync. Add validation or a domain guard plus a regression test proving duplicate or mismatched identities fail deterministically instead of aliasing the same occurrence document or colliding with the unique slug index.`

## Merged Findings
### F-0096D4F9 [high] Occurrence identity sync does not enforce one-to-one identity mapping
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `event-occurrence-update-identities-unique-and-consistent`
- **Suggested action:** Before preserving/syncing occurrence payloads, normalize all supplied occurrence_id/id/occurrence_slug identities into a single identity map, reject duplicate identities, and when both id and slug are present verify they refer to the same stored occurrence for the event. Cover duplicate-id, duplicate-slug, and mismatched id/slug update payloads with Laravel feature tests.
- **Rationale:** EventManagementService resolves existing occurrence payloads independently for each incoming row, and EventOccurrenceSyncService resolves each row to an EventOccurrence independently. If an update payload repeats the same occurrence_id or sends an occurrence_id paired with another row's occurrence_slug, multiple logical rows can alias the same stored document or attempt to persist a slug that belongs to a different occurrence. EventWriteRules only checks shape/length and does not require distinct occurrence_id, distinct occurrence_slug, or id/slug pair consistency. This leaves a correctness hole in the newly canonical identity-safe update contract: a malformed or stale client payload can silently collapse rows, overwrite the last aliased row, soft-delete the other occurrence, or trip the unique occurrence_slug index instead of producing a deterministic validation error.

## Reviewer Summaries
### elegance
- **Assessment:** The round-03 fixes close the specific index-fallback and early occurrence-id query regressions: mixed identified/unidentified occurrence rows no longer bind new rows to existing documents by index, generated slugs avoid claimed/existing slugs, and occurrence-id filters are placed in the initial match or geoNear query. One material identity-safety gap remains: the update path still treats occurrence identities as independent hints rather than enforcing a one-to-one identity map.
- **Recommended path:** `Do not close as clean until occurrence update payloads reject duplicate occurrence identities and inconsistent occurrence_id/occurrence_slug pairs before normalization/sync. Add validation or a domain guard plus a regression test proving duplicate or mismatched identities fail deterministically instead of aliasing the same occurrence document or colliding with the unique slug index.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R04-001 Occurrence identity sync does not enforce one-to-one identity mapping: EventManagementService resolves existing occurrence payloads independently for each incoming row, and EventOccurrenceSyncService resolves each row to an EventOccurrence independently. If an update payload repeats the same occurrence_id or sends an occurrence_id paired with another row's occurrence_slug, multiple logical rows can alias the same stored document or attempt to persist a slug that belongs to a different occurrence. EventWriteRules only checks shape/length and does not require distinct occurrence_id, distinct occurrence_slug, or id/slug pair consistency. This leaves a correctness hole in the newly canonical identity-safe update contract: a malformed or stale client payload can silently collapse rows, overwrite the last aliased row, soft-delete the other occurrence, or trip the unique occurrence_slug index instead of producing a deterministic validation error.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

