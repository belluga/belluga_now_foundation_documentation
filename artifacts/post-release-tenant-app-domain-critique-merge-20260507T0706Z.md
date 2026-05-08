# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-critique-dispatch-20260507T0706Z.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Continue the audit gate with revisions: add or cite direct current-tenant read-role denial evidence, and either harden or explicitly defer the adjacent tenant-domain same-ability borrowed-token risk before closure claims are finalized.`

## Merged Findings
### F-36192EBD [medium] Read route lacks direct current-tenant role-denial coverage
- **Reviewers:** no-context-paced-reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a focused negative test where the token has `tenant-domains:read` but the current tenant role lacks read permission, including a borrowed-read-from-other-tenant variant if role union remains canonical.
- **Rationale:** The app-domain tests cover missing tenant access, missing Sanctum read ability, wrong-tenant access, and borrowed update ability for mutations. They do not directly prove that `GET /admin/api/v1/appdomains` denies a token carrying `tenant-domains:read` when the principal's current-tenant role lacks `tenant-domains:read`. Route-list evidence proves middleware attachment, but the behavioral regression suite does not isolate the read-side role check that the replacement canonical rule now requires.

### F-3870B1B7 [medium] Borrowed-token fix is appdomain-only while adjacent tenant-domain routes keep weaker same-ability semantics
- **Reviewers:** no-context-paced-reviewer
- **Category:** `security`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before RR-AUTH-02 closure, either extend current-tenant role ability enforcement and borrowed-token tests to `/admin/api/v1/domains`, or explicitly record that adjacent same-ability domain-route risk is out of scope under the broader route-matrix TODO and limit RR-AUTH-02 claims to `/admin/api/v1/appdomains`.
- **Rationale:** The package resolves the borrowed-token issue for `/admin/api/v1/appdomains` by adding `CheckCurrentTenantRoleAbility`, but the same route file still shows `/admin/api/v1/domains` routes protected only by `auth:sanctum`, `CheckTenantAccess`, and Sanctum `tenant-domains:*` abilities. Because the authentication service unions tenant-role permissions into tokens, the same cross-tenant borrowed-ability pattern appears applicable to adjacent web-domain mutation routes. The package's residual guardrail classification names identity routes as the unrelated file-level debt, but does not classify this same-ability tenant-domain residual risk.

### F-BB1D3BCE [low] Authorized mutation is not exercised through the real tenant-admin login token path
- **Reviewers:** no-context-paced-reviewer
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add or cite one authorized app-domain store or delete assertion using a tenant-admin login token for a principal whose current tenant role has `tenant-domains:update`.
- **Rationale:** The checkpoint proves read with a token returned by tenant-admin login, but authorized store/delete app-domain tests use manually created Sanctum tokens. Since the consumer matrix claims the existing tenant-admin consumer contract remains unchanged, one mutation path should prove that a real login-issued token for a role with `tenant-domains:update` still satisfies both Sanctum ability and current-tenant role middleware.

## Reviewer Summaries
### no-context-paced-reviewer
- **Assessment:** The RR-AUTH-02 app-domain checkpoint is directionally sound for the bounded appdomains surface, but it is not approval-clean without tighter read-route role evidence and explicit classification of adjacent same-ability tenant-domain residual risk.
- **Recommended path:** `Continue the audit gate with revisions: add or cite direct current-tenant read-role denial evidence, and either harden or explicitly defer the adjacent tenant-domain same-ability borrowed-token risk before closure claims are finalized.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] CRIT-RR-AUTH-02-001 Borrowed-token fix is appdomain-only while adjacent tenant-domain routes keep weaker same-ability semantics: The package resolves the borrowed-token issue for `/admin/api/v1/appdomains` by adding `CheckCurrentTenantRoleAbility`, but the same route file still shows `/admin/api/v1/domains` routes protected only by `auth:sanctum`, `CheckTenantAccess`, and Sanctum `tenant-domains:*` abilities. Because the authentication service unions tenant-role permissions into tokens, the same cross-tenant borrowed-ability pattern appears applicable to adjacent web-domain mutation routes. The package's residual guardrail classification names identity routes as the unrelated file-level debt, but does not classify this same-ability tenant-domain residual risk.
  - [medium] CRIT-RR-AUTH-02-002 Read route lacks direct current-tenant role-denial coverage: The app-domain tests cover missing tenant access, missing Sanctum read ability, wrong-tenant access, and borrowed update ability for mutations. They do not directly prove that `GET /admin/api/v1/appdomains` denies a token carrying `tenant-domains:read` when the principal's current-tenant role lacks `tenant-domains:read`. Route-list evidence proves middleware attachment, but the behavioral regression suite does not isolate the read-side role check that the replacement canonical rule now requires.
  - [low] CRIT-RR-AUTH-02-003 Authorized mutation is not exercised through the real tenant-admin login token path: The checkpoint proves read with a token returned by tenant-admin login, but authorized store/delete app-domain tests use manually created Sanctum tokens. Since the consumer matrix claims the existing tenant-admin consumer contract remains unchanged, one mutation path should prove that a real login-issued token for a role with `tenant-domains:update` still satisfies both Sanctum ability and current-tenant role middleware.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

