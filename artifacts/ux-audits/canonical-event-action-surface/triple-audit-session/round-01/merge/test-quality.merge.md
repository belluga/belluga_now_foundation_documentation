# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/ux-audits/canonical-event-action-surface/triple-audit-session/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `block implementation until concrete test matrix is part of the TODO`

## Merged Findings
### F-9FD5A477 [high] WhatsApp hero action lacks dedicated direct-launch coverage
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require widget/controller test proving WhatsApp hero action invokes the WhatsApp/fallback launcher directly and does not open the canonical sheet.
- **Rationale:** The proposal depends on WhatsApp remaining an immediate shortcut/fallback, but current test coverage was reported as missing for this behavior.

### F-D2CF7040 [high] Web anonymous runtime gate needs Playwright coverage
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require source-owned Playwright coverage for web anonymous desktop/mobile-frame favorite or action gate behavior.
- **Rationale:** The critical operational contract is web anonymous gate -> promotion surface, with no phone login and no auto-open app before CTA. Widget tests alone are insufficient for browser/runtime behavior.

### F-21D6A433 [high] Sheet-first Convidar navigation contract is untested
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require test: tap Convidar -> canonical action sheet visible -> tap full composer action -> InviteShareRoute pushed; fail if route is pushed immediately.
- **Rationale:** Current tests validate direct navigation to InviteShareRoute. The proposed behavior needs the inverse: first open canonical sheet, then route only after explicit action.

### F-0F2B8583 [high] Promotion variant must prove shared action-sheet anatomy
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require promotion gate test asserting the canonical action-sheet component/keys are used and phone-login UI is absent on web anonymous.
- **Rationale:** The proposal purpose is visual/surface canonicalization. Existing modal tests validate content but not shared surface anatomy.

### F-4C19EE74 [high] CI execution evidence for runtime navigation must be explicit
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Implementation TODO must list exact focused Flutter, analyzer, rule matrix, build web, and Playwright commands before delivery.
- **Rationale:** Integration/runtime tests are only valuable if the correct runner includes them. The proposal must name the commands/CI-equivalent path.

### F-5333A12E [low] System share tests may rely on fake launcher only
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `none`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep fake-launcher assertions for determinism and add runtime/browser smoke only where platform behavior is release-critical.
- **Rationale:** Fake launcher validates payload formatting but not platform/plugin behavior. This is acceptable for focused unit coverage but weaker than runtime proof.

### F-A1C57BD6 [low] Invite backend mutation coverage is outside proposal but will matter if composer logic changes
- **Reviewers:** test-quality-claude
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `none`
- **Candidate rule id:** `n/a`
- **Suggested action:** Keep backend invite semantics out of this TODO; if touched, require mutation/contract tests.
- **Rationale:** The UX proposal does not change backend invite semantics, but any implementation that changes send/share-code/status behavior must add backend/controller coverage.

## Reviewer Summaries
### test-quality-claude
- **Assessment:** blocking for implementation until test evidence is specified
- **Recommended path:** `block implementation until concrete test matrix is part of the TODO`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-001 WhatsApp hero action lacks dedicated direct-launch coverage: The proposal depends on WhatsApp remaining an immediate shortcut/fallback, but current test coverage was reported as missing for this behavior.
  - [high] TQ-002 Sheet-first Convidar navigation contract is untested: Current tests validate direct navigation to InviteShareRoute. The proposed behavior needs the inverse: first open canonical sheet, then route only after explicit action.
  - [high] TQ-003 Promotion variant must prove shared action-sheet anatomy: The proposal purpose is visual/surface canonicalization. Existing modal tests validate content but not shared surface anatomy.
  - [high] TQ-004 Web anonymous runtime gate needs Playwright coverage: The critical operational contract is web anonymous gate -> promotion surface, with no phone login and no auto-open app before CTA. Widget tests alone are insufficient for browser/runtime behavior.
  - [high] TQ-005 CI execution evidence for runtime navigation must be explicit: Integration/runtime tests are only valuable if the correct runner includes them. The proposal must name the commands/CI-equivalent path.
  - [low] TQ-006 System share tests may rely on fake launcher only: Fake launcher validates payload formatting but not platform/plugin behavior. This is acceptable for focused unit coverage but weaker than runtime proof.
  - [low] TQ-007 Invite backend mutation coverage is outside proposal but will matter if composer logic changes: The UX proposal does not change backend invite semantics, but any implementation that changes send/share-code/status behavior must add backend/controller coverage.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

