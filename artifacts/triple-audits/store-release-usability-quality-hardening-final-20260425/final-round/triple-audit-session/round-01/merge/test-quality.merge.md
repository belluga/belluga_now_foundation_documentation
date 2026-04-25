# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `Repair the web navigation CI harness first, then rerun release-gating web validation through CI or the same canonical runner with a deterministic shard manifest. Treat Android integration as blocked until a target device lane executes the changed mobile integration scope, and separate fake-harness widget coverage from real integration evidence.`

## Merged Findings
### F-8F7B475F [high] Web navigation CI still invokes a deleted web-app Playwright harness
- **Reviewers:** test-quality-audit-lane
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either restore the web-app Playwright harness files or update the workflow to invoke the canonical root runner with committed dependencies and explicit suite selection. Add a CI preflight that fails when the workflow references a missing package/test harness.
- **Rationale:** The web-app workflow still runs `npm ci` and `npm run test:navigation`, but the working tree deletes `package.json`, `package-lock.json`, `playwright.config.js`, and `tests/navigation.spec.js`. The package evidence instead cites local execution through `tools/flutter/run_web_navigation_smoke.sh`, so the release-gating CI path is no longer equivalent to the tested path and will either fail as infrastructure or stop proving the canonical navigation specs.

### F-E0CBCFEA [high] Mobile integration evidence remains blocked while changed integration tests are fake-harness coverage
- **Reviewers:** test-quality-audit-lane
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Reclassify fake-harness `integration_test` files as non-contract widget/runtime harness coverage, then run the changed mobile integration scope on an Android device against the approved non-production backend before release signoff.
- **Rationale:** The audit package explicitly says Android/ADB had no connected device and Android integration remains blocked, not passed. Several changed files under `integration_test/` use fake repositories and local GetIt registrations, so they validate widget/controller wiring but not mobile runtime behavior against the Laravel contracts. That is insufficient for a store-release usability hardening claim on mobile.

### F-4AC3E100 [medium] Mutation shard evidence is filtered by unrestricted grep without a deterministic coverage manifest
- **Reviewers:** test-quality-audit-lane
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Replace arbitrary `NAV_WEB_GREP_EXTRA` with named shard IDs backed by a committed manifest. Have the runner print and validate the matched test titles, fail if any required `@mutation` title is omitted, and store the manifest with the validation evidence.
- **Rationale:** The package states the full unsharded mutation runner terminated without Playwright failure summary, then local evidence was gathered through `NAV_WEB_GREP_EXTRA` shards. The runner appends the raw environment value to the Playwright grep expression, but there is no declared shard catalog, matched-test manifest, or fail-closed check proving every `@mutation` test was selected exactly once. The package manually asserts all 19 specs ran, but the harness does not enforce that claim.

### F-019E6572 [medium] Mutation pass counts include a declaration-only metadata test
- **Reviewers:** test-quality-audit-lane
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Retag declaration-only tests as metadata or guardrail tests outside `@mutation`, and report behavioral mutation coverage separately from coverage-matrix consistency checks.
- **Rationale:** One `@mutation` Playwright test only verifies that the NAV-01..NAV-23 matrix is declared with titles and proof text. It does not exercise product UI, API, persistence, navigation, or contract behavior, but it contributes to the mutation suite pass count reported in the shard evidence. This makes the coverage count easier to overstate.

## Reviewer Summaries
### test-quality-audit-lane
- **Assessment:** Not delivery-ready from a test-quality and operational evidence perspective. The added backend, Flutter widget, and Playwright tests contain many real assertions and the local evidence is broad, but the release-gating web CI is disconnected from the moved/deleted test harness, Android/mobile integration remains explicitly blocked, and the sharded mutation evidence is not machine-verifiable.
- **Recommended path:** `Repair the web navigation CI harness first, then rerun release-gating web validation through CI or the same canonical runner with a deterministic shard manifest. Treat Android integration as blocked until a target device lane executes the changed mobile integration scope, and separate fake-harness widget coverage from real integration evidence.`
- **Performance:** `mixed`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-001 Web navigation CI still invokes a deleted web-app Playwright harness: The web-app workflow still runs `npm ci` and `npm run test:navigation`, but the working tree deletes `package.json`, `package-lock.json`, `playwright.config.js`, and `tests/navigation.spec.js`. The package evidence instead cites local execution through `tools/flutter/run_web_navigation_smoke.sh`, so the release-gating CI path is no longer equivalent to the tested path and will either fail as infrastructure or stop proving the canonical navigation specs.
  - [high] TQ-002 Mobile integration evidence remains blocked while changed integration tests are fake-harness coverage: The audit package explicitly says Android/ADB had no connected device and Android integration remains blocked, not passed. Several changed files under `integration_test/` use fake repositories and local GetIt registrations, so they validate widget/controller wiring but not mobile runtime behavior against the Laravel contracts. That is insufficient for a store-release usability hardening claim on mobile.
  - [medium] TQ-003 Mutation shard evidence is filtered by unrestricted grep without a deterministic coverage manifest: The package states the full unsharded mutation runner terminated without Playwright failure summary, then local evidence was gathered through `NAV_WEB_GREP_EXTRA` shards. The runner appends the raw environment value to the Playwright grep expression, but there is no declared shard catalog, matched-test manifest, or fail-closed check proving every `@mutation` test was selected exactly once. The package manually asserts all 19 specs ran, but the harness does not enforce that claim.
  - [medium] TQ-004 Mutation pass counts include a declaration-only metadata test: One `@mutation` Playwright test only verifies that the NAV-01..NAV-23 matrix is declared with titles and proof text. It does not exercise product UI, API, persistence, navigation, or contract behavior, but it contributes to the mutation suite pass count reported in the shard evidence. This makes the coverage count easier to overstate.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

