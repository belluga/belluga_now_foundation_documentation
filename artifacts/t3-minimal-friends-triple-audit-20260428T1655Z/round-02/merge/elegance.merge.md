# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the canonical recipient identity split before closing this gate. Migrate invite acceptance, duplicate-winner lookup, supersession, direct-confirmation closure, and the related evidence to receiver_account_profile_id wherever an invite has that canonical recipient, keeping receiver_user_id only as acting-user/audit compatibility data where explicitly needed.`

## Merged Findings
### F-0DFCFF81 [high] Canonical account-profile recipient migration is only partial
- **Reviewers:** elegance-round-02
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `invite-recipient-identity-must-close-by-account-profile`
- **Suggested action:** Introduce a recipient-surface resolver for invite mutation closure that derives the canonical receiver_account_profile_id for each edge and uses it for duplicate winner lookup, supersession, direct-confirmation closure, and acceptance authorization when present. Update tests so direct invites, share materialization, share accept, and replay/idempotency cases assert profile-keyed closure rather than receiver_user_id-only behavior.
- **Rationale:** The TODO and module docs freeze receiver_account_profile_id as the canonical recipient identity and state that legacy user-targeted acceptance behavior must be migrated before release closure. The changed InviteMutationService still accepts and closes invite state by user id: acceptForUserIdWithoutReplay checks receiver_user_id, existing credited winners are queried by receiver_user_id, supersedePendingInvites and groupHasOtherPending also operate on receiver_user_id. The referenced InvitesFlowTest evidence still asserts receiver_user_id behavior across direct, materialized, and share-accept flows. That leaves two structural paths likely to diverge: profile-targeted creation versus user-targeted acceptance/crediting.

## Reviewer Summaries
### elegance-round-02
- **Assessment:** Not clean for the elegance gate. The dedicated group-management surface and backend inviteable batching resolve the round 01 surface-level blockers, but the bounded package still carries a material old/new recipient-identity split: creation can target receiver_account_profile_id while acceptance, supersession, duplicate-credit closure, and validation evidence remain centered on receiver_user_id.
- **Recommended path:** `Resolve the canonical recipient identity split before closing this gate. Migrate invite acceptance, duplicate-winner lookup, supersession, direct-confirmation closure, and the related evidence to receiver_account_profile_id wherever an invite has that canonical recipient, keeping receiver_user_id only as acting-user/audit compatibility data where explicitly needed.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R02-001 Canonical account-profile recipient migration is only partial: The TODO and module docs freeze receiver_account_profile_id as the canonical recipient identity and state that legacy user-targeted acceptance behavior must be migrated before release closure. The changed InviteMutationService still accepts and closes invite state by user id: acceptForUserIdWithoutReplay checks receiver_user_id, existing credited winners are queried by receiver_user_id, supersedePendingInvites and groupHasOtherPending also operate on receiver_user_id. The referenced InvitesFlowTest evidence still asserts receiver_user_id behavior across direct, materialized, and share-accept flows. That leaves two structural paths likely to diverge: profile-targeted creation versus user-targeted acceptance/crediting.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

