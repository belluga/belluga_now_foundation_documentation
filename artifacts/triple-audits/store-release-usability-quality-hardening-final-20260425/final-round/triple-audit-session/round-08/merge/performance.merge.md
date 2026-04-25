# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-08/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `needs_resolution: add explicit cardinality and shape limits to the remaining event write arrays, then add negative request tests and a focused recut for event CRUD/performance guardrails.`

## Merged Findings
### F-D0593AF6 [medium] Map POI polygon discovery scope accepts unbounded coordinate arrays
- **Reviewers:** round-08-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Define polygon coordinate budgets and nested coordinate rules, enforce them in EventStoreRequest/EventUpdateRequest, defensively reject or truncate invalid polygon payloads before projection, and add negative tests for oversized polygon rings and out-of-range polygon coordinates.
- **Rationale:** The event store/update request rules require capabilities.map_poi.discovery_scope.polygon.coordinates to be an array with min:1, but there is no maximum ring or point count and no nested coordinate validation comparable to point/range validation. MapPoiProjectionService later normalizes and computes a centroid by iterating the first ring, so a large persisted polygon can create expensive write/projection/refresh work and oversized capability payloads.

### F-4C5FC9B1 [medium] Event write taxonomy/tags/categories arrays remain unbounded while taxonomy writes now trigger resolver work
- **Reviewers:** round-08-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Introduce explicit event-write caps for tags, categories, taxonomy_terms, and unique taxonomy terms, preferably in Belluga\Events\Support\Validation\InputConstraints, enforce them in store/update requests, and add negative EventCrudControllerTest coverage for oversized arrays.
- **Rationale:** EventStoreRequest and EventUpdateRequest bound occurrence fanout and rich text, but still validate tags, categories, and taxonomy_terms only as arrays with per-item string length. EventManagementService now validates taxonomy_terms and resolves snapshots before persisting, so an authenticated tenant-admin request can submit a very large taxonomy_terms array and force large validation/resolver/database work. This is inconsistent with the package's public filter bounds and write fanout hardening.

## Reviewer Summaries
### round-08-performance-security
- **Assessment:** Not clean for the performance/security lane. The package resolves the prior public-query, account-context, sanitizer, and navigation-policy issues, but two authenticated event-write surfaces still accept unbounded or weakly bounded payload fanout that can drive database resolver work or persisted projection work.
- **Recommended path:** `needs_resolution: add explicit cardinality and shape limits to the remaining event write arrays, then add negative request tests and a focused recut for event CRUD/performance guardrails.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] R08-PERFSEC-001 Event write taxonomy/tags/categories arrays remain unbounded while taxonomy writes now trigger resolver work: EventStoreRequest and EventUpdateRequest bound occurrence fanout and rich text, but still validate tags, categories, and taxonomy_terms only as arrays with per-item string length. EventManagementService now validates taxonomy_terms and resolves snapshots before persisting, so an authenticated tenant-admin request can submit a very large taxonomy_terms array and force large validation/resolver/database work. This is inconsistent with the package's public filter bounds and write fanout hardening.
  - [medium] R08-PERFSEC-002 Map POI polygon discovery scope accepts unbounded coordinate arrays: The event store/update request rules require capabilities.map_poi.discovery_scope.polygon.coordinates to be an array with min:1, but there is no maximum ring or point count and no nested coordinate validation comparable to point/range validation. MapPoiProjectionService later normalizes and computes a centroid by iterating the first ring, so a large persisted polygon can create expensive write/projection/refresh work and oversized capability payloads.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

