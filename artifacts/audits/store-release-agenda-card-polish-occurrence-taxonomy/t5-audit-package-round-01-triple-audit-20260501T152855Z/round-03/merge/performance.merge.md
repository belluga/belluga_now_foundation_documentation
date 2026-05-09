# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/store-release-agenda-card-polish-occurrence-taxonomy/t5-audit-package-round-01-triple-audit-20260501T152855Z/round-03/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the performance lane yet. Push occurrence_ids into the earliest possible backend predicate, including the $geoNear query when geo is active and the initial $match/search path when geo is inactive, then add regression evidence for occurrence_ids combined with origin_lat/origin_lng and with search.`

## Merged Findings
### F-070B9EB2 [high] Pending occurrence-id agenda queries still run broad geo/search work before exact id filtering
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Fold occurrence_ids into the earliest backend predicate: add _id $in candidates to $geoNear.query when geo is active, and to the initial non-geo $match when geo is inactive. Keep the later match only if needed as a defensive duplicate. Add focused tests for /agenda?occurrence_ids[] combined with origin_lat/origin_lng and for search plus occurrence_ids, and cover the stream pipeline if that path remains active for pending-only EventSearch.
- **Rationale:** Flutter pending-only EventSearch derives pending occurrence ids and passes them to /agenda, but it also passes origin/radius for the normal search surface. In EventQueryService, buildAgendaPipeline adds occurrence_ids only after $geoNear/search/taxonomy stages, and buildStreamPipeline does the same for the realtime stream. That means the common pending-only path can still scan all published geolocated occurrences in radius, or all regex search matches, before narrowing to the small occurrence id set. The Laravel regression only covers occurrence_ids without geo params, so it does not prove the actual EventSearch path is bounded by the id predicate.

## Reviewer Summaries
### performance
- **Assessment:** The round-02 client page-walking fix is mostly present, but the backend occurrence-id filter is still applied too late in the agenda and stream aggregation pipelines. Under the actual Flutter EventSearch path, pending-only queries carry occurrence_ids together with geo parameters, so the server can still evaluate broad geo/search work before intersecting the small pending occurrence-id set.
- **Recommended path:** `Do not close the performance lane yet. Push occurrence_ids into the earliest possible backend predicate, including the $geoNear query when geo is active and the initial $match/search path when geo is inactive, then add regression evidence for occurrence_ids combined with origin_lat/origin_lng and with search.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-R03-001 Pending occurrence-id agenda queries still run broad geo/search work before exact id filtering: Flutter pending-only EventSearch derives pending occurrence ids and passes them to /agenda, but it also passes origin/radius for the normal search surface. In EventQueryService, buildAgendaPipeline adds occurrence_ids only after $geoNear/search/taxonomy stages, and buildStreamPipeline does the same for the realtime stream. That means the common pending-only path can still scan all published geolocated occurrences in radius, or all regex search matches, before narrowing to the small occurrence id set. The Laravel regression only covers occurrence_ids without geo params, so it does not prove the actual EventSearch path is bounded by the id predicate.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

