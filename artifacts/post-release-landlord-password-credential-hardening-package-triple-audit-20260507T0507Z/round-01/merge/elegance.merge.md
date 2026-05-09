# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-landlord-password-credential-hardening-package-triple-audit-20260507T0507Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Do not close the elegance lane until the residual LandlordUser password-write canonicalization is either collapsed into the canonical application/domain service boundary or explicitly constrained as a non-runtime compatibility guard with tests proving it cannot overwrite, broaden, or diverge from subject-specific credential synchronization.`

## Merged Findings
### F-F31F0562 [high] Residual model-level password canonicalization keeps a second credential mutation boundary alive
- **Reviewers:** RR-AUTH-01 Elegance lane no-context reviewer
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make one canonical mutation boundary responsible for landlord password credential writes. Prefer rejecting or stripping legacy password fields at the model boundary while delegating all credential creation/update/pruning to the application/domain service, or document and test the model hook as a narrow compatibility guard that cannot write credentials except through the same canonical service path and cannot run on unrelated saves.
- **Rationale:** The package says LandlordUser saving now canonicalizes attempted password writes into credentials and strips legacy password state, while LandlordUserAccessService and profile/create/register flows also own canonical credential synchronization. That is not just naming polish: the package also records that this model-level behavior previously overwrote canonical credentials with stale legacy hash state until guarded by dirty-field logic. Leaving the model save path as a silent transformer for legacy password writes preserves an old/new path seam that can diverge from the service-level subject synchronization rule and carries correctness/security risk for the exact auth source-of-truth being hardened.

### F-B9535117 [medium] Stable source-of-truth rule is not shown as synchronized into canonical module documentation
- **Reviewers:** RR-AUTH-01 Elegance lane no-context reviewer
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before closure, record the exclusive credential authority rule and the forbidden legacy password/password_type runtime state in the relevant canonical module documentation, or explicitly include evidence that the governing TODO is the accepted canonical documentation surface for this release gate.
- **Rationale:** The package defines a replacement canonical rule that credentials(provider=password).secret_hash is the exclusive landlord password authority, but the documentation section only lists a canonical module note conditionally and the current evidence does not show that the stable rule has been synchronized into canonical admin/consumer module documentation. For a source-of-truth migration, leaving the rule only in the tactical package/TODO increases future drift risk even if the runtime implementation is currently repaired.

## Reviewer Summaries
### RR-AUTH-01 Elegance lane no-context reviewer
- **Assessment:** The package establishes the right canonical direction: password credentials are the exclusive landlord password authority, legacy password state is removed, login is credential-subject-specific, and validation is anchored to the real split-brain regression. The remaining elegance concern is that the package still describes model-level canonicalization of attempted legacy password writes alongside service-level credential helpers, which keeps an alternate mutation boundary alive in the same surface that already caused a stale-hash regression.
- **Recommended path:** `Do not close the elegance lane until the residual LandlordUser password-write canonicalization is either collapsed into the canonical application/domain service boundary or explicitly constrained as a non-runtime compatibility guard with tests proving it cannot overwrite, broaden, or diverge from subject-specific credential synchronization.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [high] RR-AUTH-01-ELEGANCE-001 Residual model-level password canonicalization keeps a second credential mutation boundary alive: The package says LandlordUser saving now canonicalizes attempted password writes into credentials and strips legacy password state, while LandlordUserAccessService and profile/create/register flows also own canonical credential synchronization. That is not just naming polish: the package also records that this model-level behavior previously overwrote canonical credentials with stale legacy hash state until guarded by dirty-field logic. Leaving the model save path as a silent transformer for legacy password writes preserves an old/new path seam that can diverge from the service-level subject synchronization rule and carries correctness/security risk for the exact auth source-of-truth being hardened.
  - [medium] RR-AUTH-01-ELEGANCE-002 Stable source-of-truth rule is not shown as synchronized into canonical module documentation: The package defines a replacement canonical rule that credentials(provider=password).secret_hash is the exclusive landlord password authority, but the documentation section only lists a canonical module note conditionally and the current evidence does not show that the stable rule has been synchronized into canonical admin/consumer module documentation. For a source-of-truth migration, leaving the rule only in the tactical package/TODO increases future drift risk even if the runtime implementation is currently repaired.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

