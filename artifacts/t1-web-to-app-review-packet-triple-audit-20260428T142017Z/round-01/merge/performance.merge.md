# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t1-web-to-app-review-packet-triple-audit-20260428T142017Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Fix the redirect helper before closing T1: reject scheme/authority before share-code extraction, bound or iterate auth redirect unwrapping with a small depth/length cap, and add focused regression tests for both cases. The app-download default and anonymous favorite controller changes do not show material performance blockers in this bounded package.`

## Merged Findings
### F-AFBD0E9C [high] Blocking: External absolute invite URLs bypass the redirect allowlist
- **Reviewers:** performance
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Reject parsed redirect paths with scheme, authority, or scheme-relative form before calling resolveWebPromotionShareCode, and add regression coverage for absolute external /invite URLs in both resolveWebPromotionPath and buildTenantPromotionUri behavior.
- **Rationale:** resolveWebPromotionPath extracts a share code before the new absolute-URL rejection runs. An input such as an absolute external URL whose path is /invite and has code will still canonicalize to /invite?code=... instead of falling back to /. The frozen contract says external paths must fall back, and the implementation summary claims absolute URLs are blocked.

### F-77B1502B [high] Blocking: Auth redirect unwrapping is unbounded
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Rewrite the auth unwrap as an iterative loop with a small maximum depth and optional input-length cap, falling back to / or null when exceeded. Add a regression test for a nested auth redirect over the cap.
- **Rationale:** _resolveAllowedPromotionRedirectPath recursively unwraps /auth redirect query values without a depth or length cap. Because redirectPath is user-controlled handoff input, a deeply nested /auth/login?redirect=... chain can force repeated parsing and recursion until stack exhaustion or a stalled web runtime.

## Reviewer Summaries
### performance
- **Assessment:** T1 should not pass as-is. The bounded diff does not introduce broad N+1 or high-cardinality data access, but the redirect allowlist still has a contract/security bypass for absolute invite URLs and an unbounded recursive auth-redirect unwrap that can become a runtime resource-exhaustion path.
- **Recommended path:** `Fix the redirect helper before closing T1: reject scheme/authority before share-code extraction, bound or iterate auth redirect unwrapping with a small depth/length cap, and add focused regression tests for both cases. The app-download default and anonymous favorite controller changes do not show material performance blockers in this bounded package.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] PERF-T1-001 Blocking: External absolute invite URLs bypass the redirect allowlist: resolveWebPromotionPath extracts a share code before the new absolute-URL rejection runs. An input such as an absolute external URL whose path is /invite and has code will still canonicalize to /invite?code=... instead of falling back to /. The frozen contract says external paths must fall back, and the implementation summary claims absolute URLs are blocked.
  - [high] PERF-T1-002 Blocking: Auth redirect unwrapping is unbounded: _resolveAllowedPromotionRedirectPath recursively unwraps /auth redirect query values without a depth or length cap. Because redirectPath is user-controlled handoff input, a deeply nested /auth/login?redirect=... chain can force repeated parsing and recursion until stack exhaustion or a stalled web runtime.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

