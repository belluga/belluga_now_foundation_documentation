# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/invite-sent-status-hydration-accepted-push-post-implementation-round02-triple-audit-20260523T1600Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve TQA-BLK-001 with a focused negative regression test and rerun audit; keep real-device and full CI-equivalent execution as promotion gates.`

## Merged Findings
### F-39CF7E05 [high] Accepted-push account-profile fallback removal is not regression-protected
- **Reviewers:** test_quality_audit
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a negative test in invites_repository_push_payload_test.dart: seed an existing pending sent status with the same user id, apply an invite_accepted payload with accepted_by_user_id but no accepted_by_account_profile_id, and assert the status remains unchanged and no accepted upsert occurs. Rerun the focused Flutter suite.
- **Rationale:** The accepted-push mutation tests covered payloads with accepted_by_account_profile_id present, while InvitePushPayloadDecoder still accepts user-id-only invite_accepted payloads and the no-account-profile no-op guard in InvitesRepository was not directly exercised. A future reintroduction of user-id fallback or OR-style recipient matching could mutate an unrelated existing sent invite status while the existing tests still pass.

### F-A18F787A [low] Real-device invite acceptance and full CI-equivalent evidence remain promotion gates
- **Reviewers:** test_quality_audit
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** After TQA-BLK-001 is resolved, keep device acceptance and full CI-equivalent execution as explicit promotion gates.
- **Rationale:** The bounded package listed focused local Laravel, Flutter, and analyzer evidence, while real-device invite acceptance proof and full CI-equivalent execution were explicitly classified as promotion/delivery gates and not claimed complete.

## Reviewer Summaries
### test_quality_audit
- **Assessment:** Not closure-ready for test quality. The bounded tests meaningfully cover sent-status hydration, filtered merge, same-key dedupe, backend sent-status semantics, profile metrics, duplicate CTA disablement, accepted tap routing, and foreground accepted-push presentation. The remaining blocker is a missing negative regression test for the prior account-profile matching fallback risk.
- **Recommended path:** `Resolve TQA-BLK-001 with a focused negative regression test and rerun audit; keep real-device and full CI-equivalent execution as promotion gates.`
- **Performance:** `acceptable`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] TQA-BLK-001 Accepted-push account-profile fallback removal is not regression-protected: The accepted-push mutation tests covered payloads with accepted_by_account_profile_id present, while InvitePushPayloadDecoder still accepts user-id-only invite_accepted payloads and the no-account-profile no-op guard in InvitesRepository was not directly exercised. A future reintroduction of user-id fallback or OR-style recipient matching could mutate an unrelated existing sent invite status while the existing tests still pass.
  - [low] TQA-GATE-001 Real-device invite acceptance and full CI-equivalent evidence remain promotion gates: The bounded package listed focused local Laravel, Flutter, and analyzer evidence, while real-device invite acceptance proof and full CI-equivalent execution were explicitly classified as promotion/delivery gates and not claimed complete.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
