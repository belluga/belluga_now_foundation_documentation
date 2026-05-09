# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-06/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the public input-budget finding before final release signoff. Treat the account-scoped occurrence fanout as a release-blocking fix unless launch data guarantees small account profile counts and that guarantee is recorded as explicit accepted debt. The taxonomy catalog truncation can be resolved either by surfacing it as a deliberate product budget or by aligning frontend/backend limits.`

## Merged Findings
### F-4D1AE359 [high] Public filter endpoints still allow unbounded query-work inputs
- **Reviewers:** round-06-performance-operational-no-context-auditor
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add explicit max counts for agenda categories, tags, and taxonomy filters, mirror the public account-profile taxonomy/type budgets on publicNear, restrict filter keys with array:profile_type,taxonomy, and cap public account-profile near radius with a shared constraint or tenant radius setting. Add negative tests that oversized arrays and oversized distance fail validation.
- **Rationale:** The package hardens public page sizes, but several public filter dimensions remain uncapped. AgendaIndexRequest accepts unbounded categories, tags, and taxonomy arrays; EventQueryService then converts categories/tags into regex arrays and expands each taxonomy term into three $or branches. AccountProfileNearRequest also lacks a count cap for profile_type/filter.profile_type, accepts arbitrary filter keys, and allows max_distance_meters with no upper bound; AccountProfileQueryService passes that distance directly into $geoNear. A small response page can therefore still force oversized request parsing, aggregation construction, and broad geospatial scans.

### F-CB704AB5 [medium] Account-scoped occurrence pagination still fans out through every account profile id
- **Reviewers:** round-06-performance-operational-no-context-auditor
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Prefer a denormalized account_context_id/account_id on occurrence party/place snapshots with a supporting index, or introduce bounded chunking with measured query-count limits. Extend the performance guardrail with a high-profile-count account fixture so the aggregate shape and input fanout remain bounded.
- **Rationale:** The occurrence pagination improvement moves profile filtering before $group, but account-scoped requests first load all profile ids for the account and inject them into $in predicates for occurrence and joined event matching. For accounts with many venues/artists, the pipeline document and match work can grow with account profile count rather than page size. The current guardrail test uses only a tiny account fixture, so it does not prove operational behavior under realistic high-fanout accounts.

### F-189C86BF [low] Admin discovery filter catalog silently truncates taxonomy coverage
- **Reviewers:** round-06-performance-operational-no-context-auditor
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either align the frontend constants with the canonical backend/foundation limits or return/display cap metadata so omitted taxonomy groups and terms are explicit. Use deterministic ordering by product priority or slug before truncation and add a test proving overflow behavior is intentional and visible.
- **Rationale:** TenantAdminDiscoveryFilterRuleCatalogRepository limits relevant taxonomies to the first 20 and terms to 50 per taxonomy. This is a useful performance budget, but the truncation is silent and depends on repository taxonomy order. Backend validation allows up to 20 allowed taxonomies per type and the batch endpoint supports 200 terms per group, so the frontend catalog can omit configurable terms/taxonomies without an explicit product signal to the admin or an aligned documented budget.

## Reviewer Summaries
### round-06-performance-operational-no-context-auditor
- **Assessment:** Not clean. The bounded package shows substantial performance and operational hardening, especially around page-size caps, occurrence pagination, query guardrails, shard determinism, and semantic navigation policy. However, local inspection still found public request surfaces where response size is bounded but query/input work remains unbounded, plus an account-scoped occurrence path that can still fan out poorly for high-profile-count accounts.
- **Recommended path:** `Resolve the public input-budget finding before final release signoff. Treat the account-scoped occurrence fanout as a release-blocking fix unless launch data guarantees small account profile counts and that guarantee is recorded as explicit accepted debt. The taxonomy catalog truncation can be resolved either by surfacing it as a deliberate product budget or by aligning frontend/backend limits.`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] R06-PERF-001 Public filter endpoints still allow unbounded query-work inputs: The package hardens public page sizes, but several public filter dimensions remain uncapped. AgendaIndexRequest accepts unbounded categories, tags, and taxonomy arrays; EventQueryService then converts categories/tags into regex arrays and expands each taxonomy term into three $or branches. AccountProfileNearRequest also lacks a count cap for profile_type/filter.profile_type, accepts arbitrary filter keys, and allows max_distance_meters with no upper bound; AccountProfileQueryService passes that distance directly into $geoNear. A small response page can therefore still force oversized request parsing, aggregation construction, and broad geospatial scans.
  - [medium] R06-PERF-002 Account-scoped occurrence pagination still fans out through every account profile id: The occurrence pagination improvement moves profile filtering before $group, but account-scoped requests first load all profile ids for the account and inject them into $in predicates for occurrence and joined event matching. For accounts with many venues/artists, the pipeline document and match work can grow with account profile count rather than page size. The current guardrail test uses only a tiny account fixture, so it does not prove operational behavior under realistic high-fanout accounts.
  - [low] R06-PERF-003 Admin discovery filter catalog silently truncates taxonomy coverage: TenantAdminDiscoveryFilterRuleCatalogRepository limits relevant taxonomies to the first 20 and terms to 50 per taxonomy. This is a useful performance budget, but the truncation is silent and depends on repository taxonomy order. Backend validation allows up to 20 allowed taxonomies per type and the batch endpoint supports 200 terms per group, so the frontend catalog can omit configurable terms/taxonomies without an explicit product signal to the admin or an aligned documented budget.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

