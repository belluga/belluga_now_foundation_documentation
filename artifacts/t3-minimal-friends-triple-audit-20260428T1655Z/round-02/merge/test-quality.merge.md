# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `needs_resolution: add account-profile keyed backend feature coverage for direct invite acceptance, duplicate/supersession, direct-confirmation supersession, and share materialization/acceptance before treating the non-ADB gate as clean.`

## Merged Findings
### F-9D134AB2 [high] Canonical account-profile recipient lifecycle is not tested beyond direct invite creation
- **Reviewers:** test-quality-round-02
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add failing backend feature tests that create invites with receiver_account_profile_id and then verify accept, duplicate candidate supersession, direct-confirmation supersession, idempotency replay, share materialization, and share acceptance are keyed by the canonical account-profile recipient. Update implementation only after those tests fail on the legacy receiver_user_id path.
- **Rationale:** The release contract requires direct invite contracts, persisted invite state, and share materialization/acceptance to use receiver_account_profile_id as the canonical recipient identity. Round-02 evidence adds StoreReleaseSocialGraphTest coverage for direct invite creation at lines 357-381, but the broader validated InvitesFlowTest acceptance/share cases still send and assert receiver_user_id, for example acceptance/supersession at lines 162-205 and share materialization at lines 610-668. The changed InviteMutationService paths that choose winners and supersede candidates still query by receiver_user_id at lines 260-265 and 688-700. This leaves the critical backend contract semantics unproven and lets legacy user-keyed behavior continue passing while the release contract can regress for account-profile recipients.

### F-5E0216D3 [medium] Flutter group-management tests prove calls, not final visible CRUD state
- **Reviewers:** test-quality-round-02
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend the Flutter group-management tests with a stateful fake repository and widget/controller assertions for rename, member replacement, delete removal, and refresh-visible membership pruning so the tests fail if the dedicated screen only dispatches repository calls without updating the rendered management state.
- **Rationale:** The dedicated group surface is required to support create, rename, delete, and membership editing. The widget test only exercises creation from the screen at contact_group_management_screen_test.dart lines 20-41. The controller test invokes create, rename, member update, and delete at lines 29-46, but its fake repository records method arguments without mutating returned group state, so it would still pass if the user-visible list never reflected rename/delete/member changes after refresh. This is weaker than the CRUD/mutation-flow evidence expected for the final management surface, though backend privacy and mutation behavior have stronger feature coverage.

## Reviewer Summaries
### test-quality-round-02
- **Assessment:** Non-clean. The added T3 tests cover several round-01 gaps, but the backend invite identity migration is not proven at the account-profile acceptance/share-materialization boundary, and existing validation still codifies receiver_user_id behavior for canonical invite lifecycle paths.
- **Recommended path:** `needs_resolution: add account-profile keyed backend feature coverage for direct invite acceptance, duplicate/supersession, direct-confirmation supersession, and share materialization/acceptance before treating the non-ADB gate as clean.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-R02-001 Canonical account-profile recipient lifecycle is not tested beyond direct invite creation: The release contract requires direct invite contracts, persisted invite state, and share materialization/acceptance to use receiver_account_profile_id as the canonical recipient identity. Round-02 evidence adds StoreReleaseSocialGraphTest coverage for direct invite creation at lines 357-381, but the broader validated InvitesFlowTest acceptance/share cases still send and assert receiver_user_id, for example acceptance/supersession at lines 162-205 and share materialization at lines 610-668. The changed InviteMutationService paths that choose winners and supersede candidates still query by receiver_user_id at lines 260-265 and 688-700. This leaves the critical backend contract semantics unproven and lets legacy user-keyed behavior continue passing while the release contract can regress for account-profile recipients.
  - [medium] TQ-R02-002 Flutter group-management tests prove calls, not final visible CRUD state: The dedicated group surface is required to support create, rename, delete, and membership editing. The widget test only exercises creation from the screen at contact_group_management_screen_test.dart lines 20-41. The controller test invokes create, rename, member update, and delete at lines 29-46, but its fake repository records method arguments without mutating returned group state, so it would still pass if the user-visible list never reflected rename/delete/member changes after refresh. This is weaker than the CRUD/mutation-flow evidence expected for the final management surface, though backend privacy and mutation behavior have stronger feature coverage.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

