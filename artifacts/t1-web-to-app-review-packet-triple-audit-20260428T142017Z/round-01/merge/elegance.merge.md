# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t1-web-to-app-review-packet-triple-audit-20260428T142017Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Fix before passing T1: make invite-code extraction subject to the same relative-path guard as the continuation allowlist, then add focused tests for absolute and scheme-relative invite URLs across resolveWebPromotionPath and buildTenantPromotionUri.`

## Merged Findings
### F-95B4CBA3 [high] Blocking: external invite URLs bypass the redirect allowlist
- **Reviewers:** Elegance
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move the relative-path rejection into resolveWebPromotionShareCode or route all invite-code extraction through a single guarded helper. Add tests proving absolute and scheme-relative /invite?code=... inputs fall back for both resolveWebPromotionPath and buildTenantPromotionUri.
- **Rationale:** resolveWebPromotionPath calls resolveWebPromotionShareCode before the relative-url guard in _resolveAllowedPromotionRedirectPath, and buildTenantPromotionUriFromAppContext uses the same share-code helper before allowlist resolution. Because resolveWebPromotionShareCode does not reject uri.hasScheme, uri.hasAuthority, or scheme-relative input, a redirectPath such as https://evil.example/invite?code=CODE123 is canonicalized as a valid /invite handoff instead of falling back to /. This violates the frozen contract requirement that external paths fall back, and it is an elegance/structural problem because invite handling now has a separate less-strict path from the canonical continuation allowlist.

## Reviewer Summaries
### Elegance
- **Assessment:** The implementation is structurally close, but the redirect allowlist has a blocking ordering flaw: invite-code canonicalization can happen before external URL rejection. That contradicts the frozen contract's external-path fallback requirement and creates drift between the public resolver and the private allowlisted resolver.
- **Recommended path:** `Fix before passing T1: make invite-code extraction subject to the same relative-path guard as the continuation allowlist, then add focused tests for absolute and scheme-relative invite URLs across resolveWebPromotionPath and buildTenantPromotionUri.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] ELEGANCE-001 Blocking: external invite URLs bypass the redirect allowlist: resolveWebPromotionPath calls resolveWebPromotionShareCode before the relative-url guard in _resolveAllowedPromotionRedirectPath, and buildTenantPromotionUriFromAppContext uses the same share-code helper before allowlist resolution. Because resolveWebPromotionShareCode does not reject uri.hasScheme, uri.hasAuthority, or scheme-relative input, a redirectPath such as https://evil.example/invite?code=CODE123 is canonicalized as a valid /invite handoff instead of falling back to /. This violates the frozen contract requirement that external paths fall back, and it is an elegance/structural problem because invite handling now has a separate less-strict path from the canonical continuation allowlist.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

