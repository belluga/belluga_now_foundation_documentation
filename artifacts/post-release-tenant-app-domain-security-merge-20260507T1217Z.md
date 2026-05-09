# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-security-dispatch-20260507T1217Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Proceed only as bounded RR-AUTH-02 appdomains/domains closure after recording the residual route-audit debt and the broad tenant-domains:update launch decision. Before final closure, reconcile module-doc wording for appdomains token ability requirements and rerun or explain the targeted suite flake observed during this review. Do not claim global tenant-route compliance while laravel_tenant_access_guardrails_audit.sh still exits 2 for unrelated authenticated identity routes.`

## Merged Findings
### F-CE7CC35B [medium] Targeted domains/appdomains validation was not fully reproducible in this review
- **Reviewers:** RR-AUTH-02-security-adversarial-no-context
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Rerun the combined bounded suite from a clean local-safe state and record the result, or classify the observed MongoDB index/dropDatabase failure as validation-environment flake with a concrete follow-up owner. Keep the isolated borrowed-read pass as supporting evidence, not as a replacement for the package's combined-suite claim.
- **Rationale:** The package claims the combined appdomain plus domain feature suite passed as 31 tests and 145 assertions. Re-running the same bounded suite locally produced 30 passing tests and one TenantDomainControllerTest failure caused by a MongoDB index build/dropDatabase CommandException, while the isolated borrowed-read test passed afterward. This does not prove an authorization bypass, but it weakens closure evidence for the adjacent /domains hardening and should not be ignored because RR-AUTH-02 relies on that suite as security regression proof.

### F-25C4C06F [low] Residual route-audit debt is correctly scoped but still manually classified
- **Reviewers:** RR-AUTH-02-security-adversarial-no-context
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep RR-AUTH-02 closure scoped to appdomains and adjacent domains only, attach the exact residual identity-route list, and evolve the guard toward route-level reporting or explicit route-level waiver metadata in the broader route-drift TODO.
- **Rationale:** The static tenant access guard still exits 2 for tenant_api_v1.php because authenticated identity routes such as auth/logout, auth/token_validate, and /me lack CheckTenantAccess. The appdomain and adjacent /domains route matrices are in-scope compliant, so this is not an RR-AUTH-02 breakout. The residual weakness is operational: the deterministic guard is file-level and cannot machine-verify route-level waivers or route-family classification.

### F-A8432C9D [low] Broad tenant-domains:update remains an accepted launch risk rather than a mechanically separated privilege
- **Reviewers:** RR-AUTH-02-security-adversarial-no-context
- **Category:** `residual_risk`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Close RR-AUTH-02 with this risk explicitly accepted for launch, and create a future ability-catalog review trigger if tenant roles need web-domain-only administration separate from app-link trust mutation.
- **Rationale:** Using tenant-domains:update for app-domain identifiers is now documented as intentional and is not a closure blocker for this launch slice. However, app-domain identifiers drive Android assetlinks and Apple AASA trust payloads, so the ability grants mobile app-link trust mutation as well as ordinary tenant web-domain mutation. OWASP least-privilege calibration supports keeping this explicit until role design proves the two operations are equivalent for all non-root tenant-admin roles.

### F-D0C47E1D [low] Appdomain module contract under-documents Sanctum token ability enforcement
- **Reviewers:** RR-AUTH-02-security-adversarial-no-context
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Update the appdomain endpoint Auth/Middleware rows to mirror /domains wording: auth:sanctum + CheckTenantAccess + abilities tenant-domains:read|update + current-tenant role ability tenant-domains:read|update.
- **Rationale:** The route implementation and package replacement rule require both Sanctum token abilities and current-tenant role abilities. The tenant_admin_module appdomain endpoint rows state auth:sanctum, CheckTenantAccess, and current-tenant role ability, but unlike the adjacent /domains rows they do not explicitly name the Sanctum abilities middleware. Laravel Sanctum documentation treats token abilities as route-enforced scopes, and OWASP authorization guidance favors explicit permission validation on every request; the implementation is stronger than the doc wording.

## Reviewer Summaries
### RR-AUTH-02-security-adversarial-no-context
- **Assessment:** Security-positive and mostly closure-ready for the bounded app-domain and adjacent domain-management scope. The implementation now layers auth:sanctum, CheckTenantAccess, Sanctum token abilities, and a current-tenant role ability check on appdomains and domains routes. This directly addresses tenant breakout and borrowed-token ability abuse, and tests now cover borrowed read/update denial plus Android and iOS denied non-mutation. Residual risk is not an obvious exploitable appdomain breakout; it is closure-quality debt around documented ability breadth, contract wording, deterministic route-audit granularity, and one locally observed validation-suite reproducibility failure.
- **Recommended path:** `Proceed only as bounded RR-AUTH-02 appdomains/domains closure after recording the residual route-audit debt and the broad tenant-domains:update launch decision. Before final closure, reconcile module-doc wording for appdomains token ability requirements and rerun or explain the targeted suite flake observed during this review. Do not claim global tenant-route compliance while laravel_tenant_access_guardrails_audit.sh still exits 2 for unrelated authenticated identity routes.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] SEC-RR-AUTH-02-NC-001 Targeted domains/appdomains validation was not fully reproducible in this review: The package claims the combined appdomain plus domain feature suite passed as 31 tests and 145 assertions. Re-running the same bounded suite locally produced 30 passing tests and one TenantDomainControllerTest failure caused by a MongoDB index build/dropDatabase CommandException, while the isolated borrowed-read test passed afterward. This does not prove an authorization bypass, but it weakens closure evidence for the adjacent /domains hardening and should not be ignored because RR-AUTH-02 relies on that suite as security regression proof.
  - [low] SEC-RR-AUTH-02-NC-002 Appdomain module contract under-documents Sanctum token ability enforcement: The route implementation and package replacement rule require both Sanctum token abilities and current-tenant role abilities. The tenant_admin_module appdomain endpoint rows state auth:sanctum, CheckTenantAccess, and current-tenant role ability, but unlike the adjacent /domains rows they do not explicitly name the Sanctum abilities middleware. Laravel Sanctum documentation treats token abilities as route-enforced scopes, and OWASP authorization guidance favors explicit permission validation on every request; the implementation is stronger than the doc wording.
  - [low] SEC-RR-AUTH-02-NC-003 Broad tenant-domains:update remains an accepted launch risk rather than a mechanically separated privilege: Using tenant-domains:update for app-domain identifiers is now documented as intentional and is not a closure blocker for this launch slice. However, app-domain identifiers drive Android assetlinks and Apple AASA trust payloads, so the ability grants mobile app-link trust mutation as well as ordinary tenant web-domain mutation. OWASP least-privilege calibration supports keeping this explicit until role design proves the two operations are equivalent for all non-root tenant-admin roles.
  - [low] SEC-RR-AUTH-02-NC-004 Residual route-audit debt is correctly scoped but still manually classified: The static tenant access guard still exits 2 for tenant_api_v1.php because authenticated identity routes such as auth/logout, auth/token_validate, and /me lack CheckTenantAccess. The appdomain and adjacent /domains route matrices are in-scope compliant, so this is not an RR-AUTH-02 breakout. The residual weakness is operational: the deterministic guard is file-level and cannot machine-verify route-level waivers or route-family classification.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

