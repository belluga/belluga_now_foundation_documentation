# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-11/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `needs_resolution: wire runtime-only tenant-admin credentials into the stage mutation navigation CI step, or explicitly split that step out of release-gating evidence until credentials are available.`

## Merged Findings
### F-EA68CA34 [high] Stage mutation navigation gate omits credentials required by the hardened harness
- **Reviewers:** test-quality-round-11
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add NAV_ADMIN_EMAIL and NAV_ADMIN_PASSWORD from appropriate GitHub secrets to the stage mutation navigation smoke env, with masking and no committed fallback, and add a workflow/static guard that any non-main mutation smoke invocation supplies the credentials required by the harness.
- **Rationale:** tools/flutter/web_app_tests/guard_web_navigation_policy.cjs:32 hard-blocks every mutation run unless NAV_ADMIN_EMAIL and NAV_ADMIN_PASSWORD are present, and tools/flutter/run_web_navigation_smoke.sh:58 runs that guard before listing or executing tests. However .github/workflows/orchestration-ci-cd.yml:418 invokes the stage mutation smoke with only lane, URLs, and HTTPS settings; it does not pass the required runtime credentials. A read-only reproduction with NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=stage and no credentials exits 1 with the guard error. Because .github/workflows/orchestration-ci-cd.yml:457 only marks stage successful when stage_navigation_mutation_smoke succeeds, the release gate cannot reproduce the package's local '18 passed' mutation evidence in CI.

## Reviewer Summaries
### test-quality-round-11
- **Assessment:** Not clean. The navigation mutation harness now correctly fails closed without runtime tenant-admin credentials, but the stage CI mutation smoke still invokes that harness without NAV_ADMIN_EMAIL/NAV_ADMIN_PASSWORD, so the promotion gate cannot reproduce the locally reported mutation evidence.
- **Recommended path:** `needs_resolution: wire runtime-only tenant-admin credentials into the stage mutation navigation CI step, or explicitly split that step out of release-gating evidence until credentials are available.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-R11-001 Stage mutation navigation gate omits credentials required by the hardened harness: tools/flutter/web_app_tests/guard_web_navigation_policy.cjs:32 hard-blocks every mutation run unless NAV_ADMIN_EMAIL and NAV_ADMIN_PASSWORD are present, and tools/flutter/run_web_navigation_smoke.sh:58 runs that guard before listing or executing tests. However .github/workflows/orchestration-ci-cd.yml:418 invokes the stage mutation smoke with only lane, URLs, and HTTPS settings; it does not pass the required runtime credentials. A read-only reproduction with NAV_WEB_TEST_TYPE=mutation NAV_DEPLOY_LANE=stage and no credentials exits 1 with the guard error. Because .github/workflows/orchestration-ci-cd.yml:457 only marks stage successful when stage_navigation_mutation_smoke succeeds, the release gate cannot reproduce the package's local '18 passed' mutation evidence in CI.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

