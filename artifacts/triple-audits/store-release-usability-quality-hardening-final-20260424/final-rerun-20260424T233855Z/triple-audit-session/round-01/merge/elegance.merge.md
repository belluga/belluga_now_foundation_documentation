# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/final-rerun-20260424T233855Z/triple-audit-session/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the two medium findings before treating the final quality-hardening pass as clean. The dead backend branch can be removed in the same cleanup. The ADB blocker remains visible package risk, but the available bounded evidence is sufficient for this elegance-focused conclusion.`

## Merged Findings
### F-95641BAE [medium] Selection repair preserves stale taxonomy terms for known empty catalogs
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Only preserve unknown selected terms when the taxonomy option is absent or termsTruncated is true. If a taxonomy option exists, is not truncated, and has an empty terms list, drop all selected terms for that taxonomy and add a focused test for the empty-untruncated case.
- **Rationale:** DiscoveryFilterSelectionRepair treats an allowed taxonomy with termOptions.terms.isEmpty the same as an unknown or explicitly truncated catalog by setting allowedTerms to null. For a known taxonomy group returned with no terms and terms_truncated=false, stale persisted terms survive repair and DiscoveryFilterQueryPayload.compile will still send them to the backend, producing confusing empty results instead of self-healing the selection.

### F-B83CB64E [medium] Cached taxonomy-term transitions can be overwritten by older async loads
- **Reviewers:** elegance
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Invalidate in-flight loads for every selected-type cache-key transition, including empty and cache-hit paths, or compare the active cache key again immediately before publishing fetched terms. Add a controller test that switches event type while a previous taxonomy batch load is unresolved.
- **Rationale:** TenantAdminEventsController increments _taxonomyTermsLoadSerial only when it starts a new batch request. Transitions to an empty allowed-taxonomy set or to a fully cached taxonomy set publish new stream state without invalidating an already in-flight request. That older request can then complete with the same serial and overwrite taxonomyTermsBySlugStreamValue with terms for a previously selected event type.

### F-A842F0B9 [low] Unused occurrence-location override branch contradicts the accepted contract
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Remove the unused private method and the now-unused rootPayload parameter plumbing unless occurrence-level location overrides are intentionally reintroduced through validation, normalization, sync, and tests as a coherent contract.
- **Rationale:** EventManagementService still defines resolveOccurrenceLocationOverride even though occurrence location and place_ref are explicitly rejected in normalizeOccurrences and the sync service hardcodes has_location_override=false. Keeping the unused method leaves a false architectural seam in the core write path and makes the contract harder to reason about.

## Reviewer Summaries
### elegance
- **Assessment:** Mixed. The bounded package moves in the right direction with dedicated drafts, repository contracts, capped catalog loading, and clearer event/taxonomy boundaries, but there are still localized structural issues that can preserve stale user state or mislead future maintainers.
- **Recommended path:** `Resolve the two medium findings before treating the final quality-hardening pass as clean. The dead backend branch can be removed in the same cleanup. The ADB blocker remains visible package risk, but the available bounded evidence is sufficient for this elegance-focused conclusion.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] ELEGANCE-001 Selection repair preserves stale taxonomy terms for known empty catalogs: DiscoveryFilterSelectionRepair treats an allowed taxonomy with termOptions.terms.isEmpty the same as an unknown or explicitly truncated catalog by setting allowedTerms to null. For a known taxonomy group returned with no terms and terms_truncated=false, stale persisted terms survive repair and DiscoveryFilterQueryPayload.compile will still send them to the backend, producing confusing empty results instead of self-healing the selection.
  - [medium] ELEGANCE-002 Cached taxonomy-term transitions can be overwritten by older async loads: TenantAdminEventsController increments _taxonomyTermsLoadSerial only when it starts a new batch request. Transitions to an empty allowed-taxonomy set or to a fully cached taxonomy set publish new stream state without invalidating an already in-flight request. That older request can then complete with the same serial and overwrite taxonomyTermsBySlugStreamValue with terms for a previously selected event type.
  - [low] ELEGANCE-003 Unused occurrence-location override branch contradicts the accepted contract: EventManagementService still defines resolveOccurrenceLocationOverride even though occurrence location and place_ref are explicitly rejected in normalizeOccurrences and the sync service hardcodes has_location_override=false. Keeping the unused method leaves a false architectural seam in the core write path and makes the contract harder to reason about.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

