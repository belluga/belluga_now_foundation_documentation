# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Do not close the T3 round yet. Add the missing targeted tests first; no production change is implied unless those tests expose a defect.`

## Merged Findings
### F-CF6CFE85 [high] Profile-keyed invite authorization lacks a negative legacy-user regression test
- **Reviewers:** test-quality-t3-round-03
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a backend feature test where the unrelated legacy receiver_user_id actor attempts accept and decline against a profile-keyed invite and receives not found/unauthorized behavior with the invite still pending; then assert the canonical profile owner can complete the mutation.
- **Rationale:** StoreReleaseSocialGraphTest intentionally creates a mismatch by setting a competing invite receiver_user_id to an unrelated user, then only proves the canonical profile owner can accept. This would not catch an implementation that incorrectly allowed the mismatched legacy receiver_user_id actor to accept or decline the same profile-keyed invite. The dispatch scope explicitly includes acting-user authorization while preserving receiver_account_profile_id lifecycle semantics.

### F-1F003F2C [medium] Contact-group screen CRUD coverage stops at create
- **Reviewers:** test-quality-t3-round-03
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend the contact-group widget test with a stateful fake that implements updateContactGroup and deleteContactGroup, then tap the rename, edit-members, and delete controls and assert the visible name, member count, and removal state after each mutation.
- **Rationale:** The widget test only exercises creating a group and visible insertion. Rename, member-edit, and delete are only covered through direct controller calls, so broken screen wiring for the dialog, bottom sheet, or delete button would still pass. The round package asks whether visible/refreshed CRUD behavior is proven, and the screen-level evidence is incomplete.

## Reviewer Summaries
### test-quality-t3-round-03
- **Assessment:** Not clean. The package has strong positive backend coverage for profile-keyed lifecycle and share materialization, and the Flutter controller tests now use stateful doubles. However, it still misses negative authorization coverage for the legacy receiver_user_id mismatch case and widget-level evidence for the full contact-group CRUD screen flow.
- **Recommended path:** `Do not close the T3 round yet. Add the missing targeted tests first; no production change is implied unless those tests expose a defect.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQ-R03-001 Profile-keyed invite authorization lacks a negative legacy-user regression test: StoreReleaseSocialGraphTest intentionally creates a mismatch by setting a competing invite receiver_user_id to an unrelated user, then only proves the canonical profile owner can accept. This would not catch an implementation that incorrectly allowed the mismatched legacy receiver_user_id actor to accept or decline the same profile-keyed invite. The dispatch scope explicitly includes acting-user authorization while preserving receiver_account_profile_id lifecycle semantics.
  - [medium] TQ-R03-002 Contact-group screen CRUD coverage stops at create: The widget test only exercises creating a group and visible insertion. Rename, member-edit, and delete are only covered through direct controller calls, so broken screen wiring for the dialog, bottom sheet, or delete button would still pass. The round package asks whether visible/refreshed CRUD behavior is proven, and the screen-level evidence is incomplete.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

