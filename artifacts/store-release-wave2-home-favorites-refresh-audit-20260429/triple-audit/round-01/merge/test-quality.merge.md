# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/store-release-wave2-home-favorites-refresh-audit-20260429/triple-audit/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Strengthen the repository regression test so the favorite-resume refresh derives from the same fake backend state mutated by favoriteAccountProfile/unfavoriteAccountProfile, or otherwise assert the refresh happens strictly after successful persistence. Add failed-persistence coverage that proves no canonical Home refresh is emitted when the backend mutation rolls back.`

## Merged Findings
### F-C97F7BFC [high] [blocking] Refresh regression test proves a method call, not the backend-backed Home behavior
- **Reviewers:** codex-test-quality-audit
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Use a backend-coupled fake or ordered spy: favorite-resume fetches should observe the same favorite backend state changed by the mutation, and the test should fail if refreshFavoriteResumes runs before successful persistence. Include the unfavorite case and a backend-failure case that asserts rollback without Home refresh.
- **Rationale:** The new test at account_profiles_repository_test.dart:262 registers a tracking FavoriteRepository whose fetchFavoriteResumes returns manually staged data independent of _StubFavoriteBackend state. A mutant that refreshes favorite resumes before favoriteAccountProfile/unfavoriteAccountProfile persists could still pass, even though production would fetch stale /favorites data and leave Home unchanged. This misses the mutation-order/backend-contract semantics required by the dispatch.

### F-9ED242B4 [medium] [accepted-debt unless production closure is claimed] CI execution evidence is not included in the bounded package
- **Reviewers:** codex-test-quality-audit
- **Category:** `operational_fit`
- **Formalizable hint:** `no`
- **Candidate rule level:** `none`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before production-ready closure, attach the required CI run evidence or explicitly keep this package classified as local-only evidence with CI deferred to a named later gate.
- **Rationale:** The package records focused local Flutter tests, analyzer, diff hygiene, and web build results, but it does not include CI execution evidence. That is acceptable only if this round is scoped to local implementation audit; it is not enough for a production-ready promotion claim under the dispatch's CI-execution gate language.

## Reviewer Summaries
### codex-test-quality-audit
- **Assessment:** Blocking test-quality risk remains. The implementation uses the canonical favorites repository path and the local evidence covers the added refresh call, but the new regression test does not model the real mutation-to-read-model contract tightly enough to prove Home refreshes from post-persistence backend state.
- **Recommended path:** `Strengthen the repository regression test so the favorite-resume refresh derives from the same fake backend state mutated by favoriteAccountProfile/unfavoriteAccountProfile, or otherwise assert the refresh happens strictly after successful persistence. Add failed-persistence coverage that proves no canonical Home refresh is emitted when the backend mutation rolls back.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-01 [blocking] Refresh regression test proves a method call, not the backend-backed Home behavior: The new test at account_profiles_repository_test.dart:262 registers a tracking FavoriteRepository whose fetchFavoriteResumes returns manually staged data independent of _StubFavoriteBackend state. A mutant that refreshes favorite resumes before favoriteAccountProfile/unfavoriteAccountProfile persists could still pass, even though production would fetch stale /favorites data and leave Home unchanged. This misses the mutation-order/backend-contract semantics required by the dispatch.
  - [medium] TQA-02 [accepted-debt unless production closure is claimed] CI execution evidence is not included in the bounded package: The package records focused local Flutter tests, analyzer, diff hygiene, and web build results, but it does not include CI execution evidence. That is acceptable only if this round is scoped to local implementation audit; it is not enough for a production-ready promotion claim under the dispatch's CI-execution gate language.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

