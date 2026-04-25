# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-06/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Tighten the APD web helper before treating the web navigation lane as final proof of the new named profile-card semantics. Keep Android marked as blocked/accepted debt unless a real device or emulator run is added before Android-specific release claims.`

## Merged Findings
### F-A0EE6713 [medium] APD web navigation test still accepts legacy profile-card accessible names
- **Reviewers:** round-06-test-quality-no-context-auditor
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Remove the legacy profile-name/group fallback from the release-gating APD helper, or gate it behind a non-release exception. The web test should require the canonical `getByRole('button', { name: /Abrir perfil <name>/ })` target when validating this hardened behavior.
- **Rationale:** The package states Discovery profile cards and nearby row items now expose named semantic buttons as `Abrir perfil <name>`, and Flutter widget tests assert that exact semantic label. However, the release-gating APD Playwright helper first searches for `Abrir perfil <prefix>` and then falls back to a generic role button named only by the profile prefix or a grouped button. A deployed regression that removes the canonical `Abrir perfil` label but leaves a profile-name-only button could still pass this navigation test, weakening the web lane as evidence for the announced accessibility/tappability contract.

### F-7BBC87E8 [low] Android remains blocked rather than independently validated
- **Reviewers:** round-06-test-quality-no-context-auditor
- **Category:** `residual_risk`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `none`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep Android explicitly marked as blocked/accepted debt in release evidence, and run the credential-gated real tenant-admin login plus relevant affected integration coverage on a real device or emulator before making Android-specific release confidence claims.
- **Rationale:** The package correctly reports no connected Android device or emulator and does not claim Android passed. Because the hardening includes Flutter UI/navigation changes that are expected to ship in store contexts, the absence of Android integration evidence remains a platform residual risk even though the package accepts web navigation as sufficient for behavior without specified Android/Web divergence.

## Reviewer Summaries
### round-06-test-quality-no-context-auditor
- **Assessment:** Conditionally acceptable. The changed tests mostly exercise real behavior and contracts: backend feature tests assert payload semantics beyond status codes, Flutter widget/unit tests cover the changed controller and semantics behavior, and web navigation evidence uses live deployed surfaces with deterministic shards and no detected skip/only/coordinate/force-click bypasses. The main test-quality weakness is that one release-gating APD helper still permits legacy semantic names, so it would not fail on the exact canonical accessible-name regression the hardening package says was fixed. Android execution remains an explicit residual platform gap, not a false pass.
- **Recommended path:** `Tighten the APD web helper before treating the web navigation lane as final proof of the new named profile-card semantics. Keep Android marked as blocked/accepted debt unless a real device or emulator run is added before Android-specific release claims.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] R06-TQ-01 APD web navigation test still accepts legacy profile-card accessible names: The package states Discovery profile cards and nearby row items now expose named semantic buttons as `Abrir perfil <name>`, and Flutter widget tests assert that exact semantic label. However, the release-gating APD Playwright helper first searches for `Abrir perfil <prefix>` and then falls back to a generic role button named only by the profile prefix or a grouped button. A deployed regression that removes the canonical `Abrir perfil` label but leaves a profile-name-only button could still pass this navigation test, weakening the web lane as evidence for the announced accessibility/tappability contract.
  - [low] R06-TQ-02 Android remains blocked rather than independently validated: The package correctly reports no connected Android device or emulator and does not claim Android passed. Because the hardening includes Flutter UI/navigation changes that are expected to ship in store contexts, the absence of Android integration evidence remains a platform residual risk even though the package accepts web navigation as sufficient for behavior without specified Android/Web divergence.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

