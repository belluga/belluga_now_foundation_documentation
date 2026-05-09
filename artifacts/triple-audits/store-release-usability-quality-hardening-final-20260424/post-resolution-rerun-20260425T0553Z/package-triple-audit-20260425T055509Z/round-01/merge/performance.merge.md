# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/post-resolution-rerun-20260425T0553Z/package-triple-audit-20260425T055509Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Needs resolution: make snapshot repair query-bounded, prove public taxonomy filters use an indexed or flat-key path, and add focused guardrails before closing the performance lane.`

## Merged Findings
### F-576AC780 [high] Taxonomy snapshot repair reintroduces per-document resolver fanout
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Build a per-run snapshot cache or batch resolver keyed by taxonomy type/value, use it for all nested replacements, and add a query-count or spy guard showing resolver lookups stay constant or chunk-bounded as repaired document count grows.
- **Rationale:** TaxonomySnapshotBackfillService streams documents with cursor(), but repairRootTaxonomyModel calls taxonomyTermSummaryResolver->resolve($terms) for each matching model, and repairEventLikeModel can call the same resolver repeatedly for root terms plus nested venue/place_ref/event_parties/linked_account_profiles/artists payloads. Because the resolver is collection-backed, a taxonomy rename or repair over account_profiles, static_assets, events, event_occurrences, and map_pois can perform database work proportional to document count and embedded reference count. The included guard only proves cursor semantics, not bounded resolver queries.

### F-7E766EEB [high] Public account-profile taxonomy filters lack index or flat-key proof
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either maintain/query an indexed flat taxonomy key for account profiles, analogous to map_pois taxonomy_terms_flat, or add explicit compound/multikey index coverage plus a guardrail test that verifies bounded/index-backed behavior for public index and near taxonomy filters.
- **Rationale:** AccountProfileQueryService adds public taxonomy filters by generating elemMatch predicates against taxonomy_terms for both publicPaginate and publicNear. The package includes correctness and budget tests, but no index migration, flat taxonomy_terms key, explain-style guard, or performance test proving this new public discovery path avoids collection scans. This is user-facing traffic and can be exercised repeatedly by discovery filters.

### F-9ACBA050 [medium] Event detail occurrence selection loads the same occurrence set twice
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Have the selection helper return both the selected occurrence and the loaded collection, or pass a preloaded collection into resolveEventOccurrences, then add a query-count guard for a multi-occurrence programming detail request.
- **Rationale:** formatEventDetail first calls resolveSelectedOccurrence, which loads all EventOccurrence rows for the event, then calls resolveEventOccurrences without passing that preloaded collection, causing another full occurrence load for the same detail response. The fanout is capped, but this is a hot public detail path and scales with the maximum schedule/programming size.

## Reviewer Summaries
### performance
- **Assessment:** Mixed. The package resolves the prior visible programming fanout issue and adds useful batch catalog loading, but the performance lane is not clean because broad taxonomy repair and public taxonomy filtering still lack bounded-query proof.
- **Recommended path:** `Needs resolution: make snapshot repair query-bounded, prove public taxonomy filters use an indexed or flat-key path, and add focused guardrails before closing the performance lane.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-RERUN-001 Taxonomy snapshot repair reintroduces per-document resolver fanout: TaxonomySnapshotBackfillService streams documents with cursor(), but repairRootTaxonomyModel calls taxonomyTermSummaryResolver->resolve($terms) for each matching model, and repairEventLikeModel can call the same resolver repeatedly for root terms plus nested venue/place_ref/event_parties/linked_account_profiles/artists payloads. Because the resolver is collection-backed, a taxonomy rename or repair over account_profiles, static_assets, events, event_occurrences, and map_pois can perform database work proportional to document count and embedded reference count. The included guard only proves cursor semantics, not bounded resolver queries.
  - [high] PERF-RERUN-002 Public account-profile taxonomy filters lack index or flat-key proof: AccountProfileQueryService adds public taxonomy filters by generating elemMatch predicates against taxonomy_terms for both publicPaginate and publicNear. The package includes correctness and budget tests, but no index migration, flat taxonomy_terms key, explain-style guard, or performance test proving this new public discovery path avoids collection scans. This is user-facing traffic and can be exercised repeatedly by discovery filters.
  - [medium] PERF-RERUN-003 Event detail occurrence selection loads the same occurrence set twice: formatEventDetail first calls resolveSelectedOccurrence, which loads all EventOccurrence rows for the event, then calls resolveEventOccurrences without passing that preloaded collection, causing another full occurrence load for the same detail response. The fanout is capped, but this is a hot public detail path and scales with the maximum schedule/programming size.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

