# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/post-release-tenant-app-domain-test-quality-dispatch-20260507T0706Z.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close RR-AUTH-02 until the final Laravel CI-equivalent suite is executed and recorded. Add or waive two focused test gaps before closure: a behavior-level GET denial case where the token carries tenant-domains:read but the current tenant role lacks tenant-domains:read, and an Apple AASA before/after non-mutation assertion for denied iOS mutation paths. Preserve red/fail-first evidence if available; otherwise downgrade the TODO wording from fail-first/TDD evidence to regression coverage evidence.`

## Merged Findings
### F-338300CC [high] Final Laravel CI-equivalent suite remains unexecuted
- **Reviewers:** no-context-paced-test-quality-auditor
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Run the final Laravel CI-equivalent suite through the canonical local-safe/CI-equivalent runner, record the exact command and result, and keep RR-AUTH-02 open until that gate passes or is explicitly blocked with a waiver.
- **Rationale:** The governing TODO still leaves the final Laravel CI-equivalent suite unchecked, and the package lists it as a remaining closure gate. Targeted and expanded safe-runner suites are useful evidence, but they are explicitly not the final CI-equivalent gate required by the orchestration plan.

### F-F7105CF2 [medium] GET current-tenant role ability denial lacks behavior-level coverage
- **Reviewers:** no-context-paced-test-quality-auditor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a focused GET denial test with token ability tenant-domains:read and a current tenant role missing tenant-domains:read, preferably using the same borrowed cross-tenant fixture shape used for update denial.
- **Rationale:** The read denial test covers a missing Sanctum read ability while the tenant role has read permission. The borrowed cross-tenant behavior test covers update mutation, not read. Route-list evidence shows CheckCurrentTenantRoleAbility on GET, but the feature suite would be stronger if it proved a token carrying tenant-domains:read cannot read identifiers when the current tenant role lacks tenant-domains:read.

### F-AFE9EACD [medium] Fail-first/TDD evidence is claimed but not preserved
- **Reviewers:** no-context-paced-test-quality-auditor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Attach red-run output or a clear fail-first transcript if it exists. If not, update the evidence language to say regression coverage was added, not that fail-first/TDD evidence was proven.
- **Rationale:** The TODO marks fail-first authz tests complete, but the bounded evidence records only passing outcomes. The referenced worker commit also combines route and test changes, so this audit cannot verify that the tests failed against the original weak route before implementation.

### F-9BF9C9ED [medium] Denied Apple AASA payload preservation is inferred rather than asserted
- **Reviewers:** no-context-paced-test-quality-auditor
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add an appleAssociationPayload helper and assert before/after equality for denied iOS store/delete, or add a dedicated borrowed cross-tenant iOS denied mutation case that verifies AASA payload non-mutation directly.
- **Rationale:** Denied mutation tests assert typed app-domain state and Android assetlinks before/after, while authorized iOS store/delete tests assert the Apple association payload. There is no denied iOS mutation or before/after AASA payload assertion, so the package's iOS denied-preservation claim depends on inference from app-domain state rather than direct payload evidence.

## Reviewer Summaries
### no-context-paced-test-quality-auditor
- **Assessment:** Mixed and not closure-ready. The app-domain tests have materially stronger assertions than the pre-hardening baseline: no skip/only/test-support/mocking bypass markers were found, denied mutations assert non-mutation for typed app-domain state plus Android assetlinks, borrowed cross-tenant update ability is covered, and principal checkout validation used the safe Laravel runner. Remaining quality gaps are evidence-side and boundary-side: fail-first/TDD evidence is asserted but not preserved, read-side current-tenant role denial is only structurally proven, denied Apple AASA payload preservation is inferred rather than asserted, and the final Laravel CI-equivalent suite is still open.
- **Recommended path:** `Do not close RR-AUTH-02 until the final Laravel CI-equivalent suite is executed and recorded. Add or waive two focused test gaps before closure: a behavior-level GET denial case where the token carries tenant-domains:read but the current tenant role lacks tenant-domains:read, and an Apple AASA before/after non-mutation assertion for denied iOS mutation paths. Preserve red/fail-first evidence if available; otherwise downgrade the TODO wording from fail-first/TDD evidence to regression coverage evidence.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-RR-AUTH-02-001 Final Laravel CI-equivalent suite remains unexecuted: The governing TODO still leaves the final Laravel CI-equivalent suite unchecked, and the package lists it as a remaining closure gate. Targeted and expanded safe-runner suites are useful evidence, but they are explicitly not the final CI-equivalent gate required by the orchestration plan.
  - [medium] TQA-RR-AUTH-02-002 Fail-first/TDD evidence is claimed but not preserved: The TODO marks fail-first authz tests complete, but the bounded evidence records only passing outcomes. The referenced worker commit also combines route and test changes, so this audit cannot verify that the tests failed against the original weak route before implementation.
  - [medium] TQA-RR-AUTH-02-003 GET current-tenant role ability denial lacks behavior-level coverage: The read denial test covers a missing Sanctum read ability while the tenant role has read permission. The borrowed cross-tenant behavior test covers update mutation, not read. Route-list evidence shows CheckCurrentTenantRoleAbility on GET, but the feature suite would be stronger if it proved a token carrying tenant-domains:read cannot read identifiers when the current tenant role lacks tenant-domains:read.
  - [medium] TQA-RR-AUTH-02-004 Denied Apple AASA payload preservation is inferred rather than asserted: Denied mutation tests assert typed app-domain state and Android assetlinks before/after, while authorized iOS store/delete tests assert the Apple association payload. There is no denied iOS mutation or before/after AASA payload assertion, so the package's iOS denied-preservation claim depends on inference from app-domain state rather than direct payload evidence.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

