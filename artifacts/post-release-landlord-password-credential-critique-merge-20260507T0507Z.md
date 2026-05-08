# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-landlord-password-credential-critique-dispatch-20260507T0507Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Do not close RR-AUTH-01 until the legacy-only backfill semantics are explicitly documented as an operator repair decision and the browser/runtime evidence distinguishes landlord-auth success from downstream local-public 502 failures.`

## Merged Findings
### F-31AEBD39 [medium] Legacy-only normalization needs explicit operator-intent semantics
- **Reviewers:** no-context RR-AUTH-01 independent critique reviewer
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** A landlord password credential repair command that converts legacy-only password state into canonical credentials must emit per-record legacy-only classification and require explicit non-dry-run operator intent; tests must assert legacy-only login fails before repair and succeeds only after the repair path intentionally creates the credential.
- **Rationale:** The package states that legacy-only users must fail login without a password credential, but the backfill evidence reports legacy_only_normalized=2. That may be acceptable as an explicit operator repair path, but the bounded package does not prove that legacy-only normalization is gated, reviewed, or documented as intentional credential creation rather than silent broadening of auth semantics.

### F-337C6300 [low] Runtime evidence should preserve auth success separately from downstream failures
- **Reviewers:** no-context RR-AUTH-01 independent critique reviewer
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** When auth-route probes require browser-like headers or mutation shards fail downstream, closure evidence must record the exact landlord-auth success signal separately from unrelated runtime failures.
- **Rationale:** Real admin login evidence is strong enough for auth repair, but runtime validation is partly entangled with Cloudflare browser-signature behavior and downstream local-public 502 failures. The package concludes these are not landlord credential regressions, which is plausible, but closure evidence should preserve that distinction explicitly so future reviewers do not treat blocked mutation shards as clean end-to-end coverage.

### F-06397B62 [low] Forbidden legacy state removal evidence is summary-level
- **Reviewers:** no-context RR-AUTH-01 independent critique reviewer
- **Category:** `adherence`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Closure evidence for removing forbidden runtime state should include a deterministic assertion or command output proving no repaired landlord user persists top-level password/password_type after all mutation and repair paths.
- **Rationale:** The package says top-level password/password_type must be removed from repaired landlord-user state and write paths, but the bounded evidence is summary-level only. Without code or schema evidence in the authorized package, this reviewer can only accept the claim conditionally through the listed tests and dry-run clean result.

## Reviewer Summaries
### no-context RR-AUTH-01 independent critique reviewer
- **Assessment:** conditional_pass_with_findings
- **Recommended path:** `Do not close RR-AUTH-01 until the legacy-only backfill semantics are explicitly documented as an operator repair decision and the browser/runtime evidence distinguishes landlord-auth success from downstream local-public 502 failures.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] RR-AUTH-01-CRIT-001 Legacy-only normalization needs explicit operator-intent semantics: The package states that legacy-only users must fail login without a password credential, but the backfill evidence reports legacy_only_normalized=2. That may be acceptable as an explicit operator repair path, but the bounded package does not prove that legacy-only normalization is gated, reviewed, or documented as intentional credential creation rather than silent broadening of auth semantics.
  - [low] RR-AUTH-01-CRIT-002 Runtime evidence should preserve auth success separately from downstream failures: Real admin login evidence is strong enough for auth repair, but runtime validation is partly entangled with Cloudflare browser-signature behavior and downstream local-public 502 failures. The package concludes these are not landlord credential regressions, which is plausible, but closure evidence should preserve that distinction explicitly so future reviewers do not treat blocked mutation shards as clean end-to-end coverage.
  - [low] RR-AUTH-01-CRIT-003 Forbidden legacy state removal evidence is summary-level: The package says top-level password/password_type must be removed from repaired landlord-user state and write paths, but the bounded evidence is summary-level only. Without code or schema evidence in the authorized package, this reviewer can only accept the claim conditionally through the listed tests and dry-run clean result.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

