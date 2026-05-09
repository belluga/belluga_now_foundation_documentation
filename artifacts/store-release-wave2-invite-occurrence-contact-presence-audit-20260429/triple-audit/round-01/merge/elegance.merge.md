# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/store-release-wave2-invite-occurrence-contact-presence-audit-20260429/triple-audit/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve occurrence identity as required across backend/feed/Flutter DTOs, require account-profile recipient identity for authenticated share materialization, and move invite transport payload construction to typed DAL request objects.`

## Merged Findings
### F-08CB8EF8 [high] Invite target pipeline still accepted nullable occurrence and event-level fallback
- **Reviewers:** elegance-structural-soundness
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make occurrence identity required/non-empty in projection rebuild, feed payloads, DTOs, result models, decoder tests, and payload handling. Remove event group fallback.
- **Rationale:** Round 01 still allowed nullable occurrence IDs in Laravel feed projection and Flutter invite DTO/result decoding, leaving event-scoped grouping fallbacks in the launch path.

### F-41DD191C [high] Flutter invite repository still owned raw transport payload maps
- **Reviewers:** elegance-structural-soundness
- **Category:** `architecture`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `repository_raw_payload_map_forbidden`
- **Suggested action:** Introduce typed DAL/backend request DTOs and assemble raw target_ref, recipient, and contact maps only at the backend adapter boundary.
- **Rationale:** Round 01 built share, direct invite, and contact import raw maps in the repository while the backend contract accepted Map payloads, bypassing the DAL/request boundary.

### F-35D17CEE [high] Authenticated share materialization still fell back to receiver_user_id identity
- **Reviewers:** elegance-structural-soundness
- **Category:** `adherence`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Require or bootstrap a personal Account Profile for authenticated share materialization and use receiver_account_profile_id for persistence/query ownership. Keep receiver_user_id only as internal actor/audit metadata.
- **Rationale:** Round 01 share materialization could persist and query authenticated receiver edges without receiver_account_profile_id, preserving two recipient identity models.

## Reviewer Summaries
### elegance-structural-soundness
- **Assessment:** Not structurally clean for round 01. Occurrence and account-profile cutover existed on happy paths, but legacy nullable occurrence, receiver-user fallback, and raw repository payload construction remained active.
- **Recommended path:** `Resolve occurrence identity as required across backend/feed/Flutter DTOs, require account-profile recipient identity for authenticated share materialization, and move invite transport payload construction to typed DAL request objects.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEG-01 Invite target pipeline still accepted nullable occurrence and event-level fallback: Round 01 still allowed nullable occurrence IDs in Laravel feed projection and Flutter invite DTO/result decoding, leaving event-scoped grouping fallbacks in the launch path.
  - [high] ELEG-02 Authenticated share materialization still fell back to receiver_user_id identity: Round 01 share materialization could persist and query authenticated receiver edges without receiver_account_profile_id, preserving two recipient identity models.
  - [high] ELEG-03 Flutter invite repository still owned raw transport payload maps: Round 01 built share, direct invite, and contact import raw maps in the repository while the backend contract accepted Map payloads, bypassing the DAL/request boundary.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

