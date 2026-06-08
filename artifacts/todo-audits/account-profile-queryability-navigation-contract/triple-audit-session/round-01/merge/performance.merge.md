# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/todo-audits/account-profile-queryability-navigation-contract/triple-audit-session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `acceptable`
- **Operational fit:** `acceptable`

## Recommended Paths
- `advance`

## Merged Findings
### F-9E74B1B9 [high] Type-set filtering push-down not directly verified in package evidence
- **Reviewers:** claude-cli-performance-r01
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a query-count or query-log assertion in AccountProfileQueryServiceTest to confirm that the capability scope reduces the DB query predicate, not a post-hydration collection filter.
- **Rationale:** The bounded package asserts that filtering is type-set pushed into queries rather than broad fetch + in-memory filtering, but the evidence provided does not include query-level assertions (e.g., EXPLAIN output, query log assertions, or test stubs that verify the WHERE clause shape). The test names confirm behavioral exclusion but do not confirm that the exclusion happens at the DB layer vs. a post-fetch PHP filter. For current cardinalities this is likely non-risky, but if AccountProfiles grow to high cardinality or the query service is reused in a list/aggregate path, an unverified in-memory filter would become a concrete performance risk.

### F-68144284 [high] Map widget layer (poi_base_card, poi_detail_card_builder, etc.) capability decisions not explicitly audited
- **Reviewers:** claude-cli-performance-r01
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** A grep/static check asserting that touched map widget files do not reference any local queryability field or slug-only navigation inference would close this gap definitively.
- **Rationale:** Several Flutter map POI widgets are listed as touched surfaces. The evidence confirms the map controller test passes for 'account profile poi' but does not confirm that the individual widget files do not contain local is_queryable or slug-inference logic that would duplicate the backend contract. If any widget performs local capability inference it creates a dual-authority path that can diverge silently.

### F-B6246A4B [high] FavoritesQueryService snapshot builder touched but no query-bounding evidence provided
- **Reviewers:** claude-cli-performance-r01
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a test or query-log assertion in the favorites snapshot builder path confirming that only queryable/navigable profiles are fetched, not a full profile set filtered post-hydration.
- **Rationale:** AccountProfileFavoriteSnapshotBuilder and FavoritesQueryService are listed as touched surfaces. Favorites snapshot builders are a historically common location for fetch-all or high-cardinality in-memory enrichment patterns. The evidence confirms the favorites section controller origin flow test passes but does not confirm the snapshot builder path is query-bounded (e.g., it fetches only capability-qualified profiles, not all then filters). At current scale this is non-blocking, but the path warrants a note.

### F-8750021B [high] Bundle hash verified but the verification step is manual and ephemeral
- **Reviewers:** claude-cli-performance-r01
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `none`
- **Candidate rule id:** `n/a`
- **Suggested action:** Promote the hash comparison to a post-deploy verification script callable from CI so bundle integrity is not solely reliant on manual author verification.
- **Rationale:** The bundle hash comparison between local build and served asset is a strong operational verification step. However, it is manual and not captured in a repeatable CI artifact. If the deployment step is repeated or a cache invalidation occurs, the hash check is not automatically re-run. This is not a blocking risk for this delivery, but the verification step should be promoted to a repeatable script or CI gate for future deployments.

### F-1AD6269D [high] Allowlist audit output reviewed but allowlist breadth not challenged in evidence
- **Reviewers:** claude-cli-performance-r01
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** The AccountProfileQueryabilityGuardrailsTest should assert an upper bound on allowlist entries (e.g., <= N) and verify each entry has a non-empty owner and rationale field to prevent silent accumulation.
- **Rationale:** The guardrail script passes and produces an allowlisted findings report with audit checklist output, but the bounded package does not expose the contents of the allowlist or assert a maximum cardinality. An unconstrained allowlist risks becoming a convenience bypass accumulator over time. The current evidence confirms the mechanism works but does not bound its scope.

## Reviewer Summaries
### claude-cli-performance-r01
- **Assessment:** The bounded package demonstrates a well-structured canonical approach to queryability/navigability separation with centralized enforcement. The layered evidence (Laravel feature/unit tests, Flutter unit tests, browser runtime diagnostic, guardrail script) is coherent and covers the primary regression vector. No concrete severe blocking findings identified. Several non-blocking debt items are noted for structural honesty.
- **Recommended path:** `advance`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `acceptable`
- **Operational fit:** `acceptable`
- **Findings:**
  - [high] F-01 Type-set filtering push-down not directly verified in package evidence: The bounded package asserts that filtering is type-set pushed into queries rather than broad fetch + in-memory filtering, but the evidence provided does not include query-level assertions (e.g., EXPLAIN output, query log assertions, or test stubs that verify the WHERE clause shape). The test names confirm behavioral exclusion but do not confirm that the exclusion happens at the DB layer vs. a post-fetch PHP filter. For current cardinalities this is likely non-risky, but if AccountProfiles grow to high cardinality or the query service is reused in a list/aggregate path, an unverified in-memory filter would become a concrete performance risk.
  - [high] F-02 Allowlist audit output reviewed but allowlist breadth not challenged in evidence: The guardrail script passes and produces an allowlisted findings report with audit checklist output, but the bounded package does not expose the contents of the allowlist or assert a maximum cardinality. An unconstrained allowlist risks becoming a convenience bypass accumulator over time. The current evidence confirms the mechanism works but does not bound its scope.
  - [high] F-03 Map widget layer (poi_base_card, poi_detail_card_builder, etc.) capability decisions not explicitly audited: Several Flutter map POI widgets are listed as touched surfaces. The evidence confirms the map controller test passes for 'account profile poi' but does not confirm that the individual widget files do not contain local is_queryable or slug-inference logic that would duplicate the backend contract. If any widget performs local capability inference it creates a dual-authority path that can diverge silently.
  - [high] F-04 Bundle hash verified but the verification step is manual and ephemeral: The bundle hash comparison between local build and served asset is a strong operational verification step. However, it is manual and not captured in a repeatable CI artifact. If the deployment step is repeated or a cache invalidation occurs, the hash check is not automatically re-run. This is not a blocking risk for this delivery, but the verification step should be promoted to a repeatable script or CI gate for future deployments.
  - [high] F-05 FavoritesQueryService snapshot builder touched but no query-bounding evidence provided: AccountProfileFavoriteSnapshotBuilder and FavoritesQueryService are listed as touched surfaces. Favorites snapshot builders are a historically common location for fetch-all or high-cardinality in-memory enrichment patterns. The evidence confirms the favorites section controller origin flow test passes but does not confirm the snapshot builder path is query-bounded (e.g., it fetches only capability-qualified profiles, not all then filters). At current scale this is non-blocking, but the path warrants a note.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

