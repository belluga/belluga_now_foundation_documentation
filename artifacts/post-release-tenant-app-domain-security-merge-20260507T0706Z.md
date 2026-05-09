# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-security-dispatch-20260507T0706Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close RR-AUTH-02 as globally tenant-route compliant. Close it only as appdomains-scoped after either addressing the findings below or explicitly accepting them in the TODO-local audit record, then run the pending final Laravel CI-equivalent suite. Keep the tenant-access audit exit 2 delegated to the route-matrix/backlog TODO unless a route-level deterministic exemption is introduced.`

## Merged Findings
### F-3F9DD88B [medium] App-link trust mutation is authorized by a broad tenant-domains:update ability
- **Reviewers:** no-context-paced-security-adversarial-reviewer
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before closure, either document that tenant-domains:update intentionally owns app-link trust mutation and audit current role assignments for that implication, or introduce a narrower app-domain/app-link ability with route, catalog, docs, and regression-test updates.
- **Rationale:** Store/delete of Android package identifiers and iOS bundle identifiers now requires current-tenant tenant-domains:update, which is a major improvement over the prior route gap. However, these identifiers feed public Android assetlinks.json and Apple AASA trust payloads, so reusing a general domain-management update ability may grant mobile deep-link trust mutation to roles intended only for ordinary tenant domain administration. OWASP least privilege calibration supports either proving this equivalence in role design or splitting the privilege. Android and Apple association payloads are trust-bearing surfaces: https://developer.android.com/training/app-links/configure-assetlinks and https://developer.apple.com/documentation/xcode/supporting-associated-domains.

### F-2170469C [low] Residual tenant-access audit exit 2 is correctly scoped but remains manually classified
- **Reviewers:** no-context-paced-security-adversarial-reviewer
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep RR-AUTH-02 closure explicitly appdomains-only and attach the exact residual route list plus delegated TODO. For future closure quality, upgrade the guard to route-level reporting or an explicit route-level waiver mechanism so exit 2 classifications are machine-verifiable.
- **Rationale:** The file-level audit still exits 2 because tenant_api_v1.php contains authenticated identity routes such as auth/logout, auth/token_validate, and /me without CheckTenantAccess. The appdomains route matrix itself is in-scope compliant, so the package's out-of-scope classification is sound for RR-AUTH-02. The operational weakness is that the deterministic guard remains file-level and cannot itself distinguish appdomains pass from residual identity-route debt.

### F-EF272F9B [low] Denied iOS app-link non-mutation is inferred rather than directly exercised
- **Reviewers:** no-context-paced-security-adversarial-reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add at least one denied iOS store/delete case for a missing or borrowed update ability that snapshots apple-app-site-association before and after the 403.
- **Rationale:** The tests directly prove authorized iOS AASA updates and directly prove denied Android assetlinks/domain-state non-mutation. Because authorization middleware runs before platform-specific controller logic, the current implementation should deny iOS mutations equivalently. Still, the package claims Android/iOS trust-payload preservation, and no denied request uses platform=ios while snapshotting apple-app-site-association before and after denial.

## Reviewer Summaries
### no-context-paced-security-adversarial-reviewer
- **Assessment:** The in-scope RR-AUTH-02 implementation is security-positive and likely closes the original app-domain tenant breakout: app-domain routes now layer auth:sanctum, CheckTenantAccess, Sanctum token abilities, and current-tenant role ability checks; tests cover missing auth, missing tenant access, missing token ability, wrong tenant, borrowed token update ability, authorized Android/iOS payload updates, and denied Android/domain-state non-mutation. Calibration anchors used: OWASP Authorization Cheat Sheet https://cheatsheetseries.owasp.org/cheatsheets/Authorization_Cheat_Sheet.html and Laravel Sanctum token ability docs https://laravel.com/docs/sanctum#token-abilities. The remaining concerns are closure-quality issues around least-privilege ability granularity, explicit iOS denial coverage, and deterministic handling of the residual tenant-access audit exit 2.
- **Recommended path:** `Do not close RR-AUTH-02 as globally tenant-route compliant. Close it only as appdomains-scoped after either addressing the findings below or explicitly accepting them in the TODO-local audit record, then run the pending final Laravel CI-equivalent suite. Keep the tenant-access audit exit 2 delegated to the route-matrix/backlog TODO unless a route-level deterministic exemption is introduced.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] SEC-RR-AUTH-02-REV-001 App-link trust mutation is authorized by a broad tenant-domains:update ability: Store/delete of Android package identifiers and iOS bundle identifiers now requires current-tenant tenant-domains:update, which is a major improvement over the prior route gap. However, these identifiers feed public Android assetlinks.json and Apple AASA trust payloads, so reusing a general domain-management update ability may grant mobile deep-link trust mutation to roles intended only for ordinary tenant domain administration. OWASP least privilege calibration supports either proving this equivalence in role design or splitting the privilege. Android and Apple association payloads are trust-bearing surfaces: https://developer.android.com/training/app-links/configure-assetlinks and https://developer.apple.com/documentation/xcode/supporting-associated-domains.
  - [low] SEC-RR-AUTH-02-REV-002 Denied iOS app-link non-mutation is inferred rather than directly exercised: The tests directly prove authorized iOS AASA updates and directly prove denied Android assetlinks/domain-state non-mutation. Because authorization middleware runs before platform-specific controller logic, the current implementation should deny iOS mutations equivalently. Still, the package claims Android/iOS trust-payload preservation, and no denied request uses platform=ios while snapshotting apple-app-site-association before and after denial.
  - [low] SEC-RR-AUTH-02-REV-003 Residual tenant-access audit exit 2 is correctly scoped but remains manually classified: The file-level audit still exits 2 because tenant_api_v1.php contains authenticated identity routes such as auth/logout, auth/token_validate, and /me without CheckTenantAccess. The appdomains route matrix itself is in-scope compliant, so the package's out-of-scope classification is sound for RR-AUTH-02. The operational weakness is that the deterministic guard remains file-level and cannot itself distinguish appdomains pass from residual identity-route debt.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

