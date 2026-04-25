# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/final-clean-rerun-20260425T011637Z/triple-audit-session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the unbounded public account-profile taxonomy filter path, bulk the event programming profile/place resolution path, and replace or cache the public catalog per-taxonomy term query loop. Re-run the existing Laravel performance guardrails plus targeted regression tests for these specific limits and resolver call counts.`

## Merged Findings
### F-486A5737 [high] Public account profile taxonomy list filters can grow an unbounded Mongo expression
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Apply a service-level cap using an explicit public taxonomy filter max before building publicTaxonomyExpression, reject excess entries consistently for list and near endpoints, and add a public account_profiles feature test that submits over-limit taxonomy filters and receives validation instead of constructing the query.
- **Rationale:** AccountProfileQueryService adds taxonomy filter parsing for publicPaginate, then publicTaxonomyExpression builds an $and/$or tree with one $elemMatch per submitted taxonomy value. The bounded diff shows max validation added for AccountProfileNearRequest, but the public account_profiles list path itself has no service-level cap before expression construction. A large public query can therefore inflate request parsing, expression generation, and Mongo planning/scanning cost even though normal Flutter selections are small.

### F-77210564 [medium] Valid event programming payloads can still trigger per-item resolver fanout
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Precollect unique profile and place ids across all occurrences, resolve them in bulk once, pass lookup maps into programming item normalization, and add a guardrail test that asserts resolver calls stay bounded for multi-occurrence programming payloads.
- **Rationale:** EventPayloadFanoutGuard bounds total programming items and references, but EventManagementService resolves programming linked profiles and place_ref location profiles inside resolveProgrammingItems for each item. With the current valid limits, one create/update can still drive up to hundreds of resolver calls instead of one bulk resolution pass over unique account_profile_ids and place_ref ids.

### F-7DF3B2DE [medium] Public discovery filter catalogs still use bounded per-taxonomy term queries
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace the public catalog term loop with a single aggregate/batch load equivalent to listBatch, or add tenant/surface cache with explicit invalidation on taxonomy/type changes. Add a test or instrumentation guard that the public catalog performs one bounded term load rather than one load per taxonomy group.
- **Rationale:** DiscoveryFilterPublicCatalogService bounds taxonomy groups and total terms, which prevents unbounded fanout, but boundedTermsByTaxonomyId still performs one TaxonomyTerm query per taxonomy id. With the configured max this can be about 20 term queries per public catalog request, and the client can request separate home and discovery catalogs during normal navigation. That is operationally heavier than the admin batch path already introduced in the same package.

## Reviewer Summaries
### performance
- **Assessment:** Mixed. The package materially improves several performance surfaces with bounded event fanout, management occurrence aggregation, and admin taxonomy batch loading, but it still leaves public query-shape and valid-payload fanout risks that should be resolved before treating the lane as clean.
- **Recommended path:** `Resolve the unbounded public account-profile taxonomy filter path, bulk the event programming profile/place resolution path, and replace or cache the public catalog per-taxonomy term query loop. Re-run the existing Laravel performance guardrails plus targeted regression tests for these specific limits and resolver call counts.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-001 Public account profile taxonomy list filters can grow an unbounded Mongo expression: AccountProfileQueryService adds taxonomy filter parsing for publicPaginate, then publicTaxonomyExpression builds an $and/$or tree with one $elemMatch per submitted taxonomy value. The bounded diff shows max validation added for AccountProfileNearRequest, but the public account_profiles list path itself has no service-level cap before expression construction. A large public query can therefore inflate request parsing, expression generation, and Mongo planning/scanning cost even though normal Flutter selections are small.
  - [medium] PERF-002 Valid event programming payloads can still trigger per-item resolver fanout: EventPayloadFanoutGuard bounds total programming items and references, but EventManagementService resolves programming linked profiles and place_ref location profiles inside resolveProgrammingItems for each item. With the current valid limits, one create/update can still drive up to hundreds of resolver calls instead of one bulk resolution pass over unique account_profile_ids and place_ref ids.
  - [medium] PERF-003 Public discovery filter catalogs still use bounded per-taxonomy term queries: DiscoveryFilterPublicCatalogService bounds taxonomy groups and total terms, which prevents unbounded fanout, but boundedTermsByTaxonomyId still performs one TaxonomyTerm query per taxonomy id. With the configured max this can be about 20 term queries per public catalog request, and the client can request separate home and discovery catalogs during normal navigation. That is operationally heavier than the admin batch path already introduced in the same package.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

