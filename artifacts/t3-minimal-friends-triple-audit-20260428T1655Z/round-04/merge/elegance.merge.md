# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-04/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve before closing the round by separating actor/profile ownership resolution from invite-recipient eligibility. Keep non-inviteable profile suppression for new direct/contact-hash recipients, but use an ownership resolver that returns the acting user's canonical personal profile id independent of inviteability when authorizing accept/decline and profile-scoped lifecycle mutations.`

## Merged Findings
### F-607923EC [high] Profile-keyed invite authorization now depends on inviteability instead of ownership
- **Reviewers:** Elegance reviewer
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a distinct identity-gateway method for resolving the acting user's receiver/account-profile ownership identity without applying profileIsInviteable. Use that method in edgeBelongsToReceiver(), receiverAccountProfileIdForEdge(), direct-confirmation supersession, and share materialization lookup paths. Leave resolveUserRecipient() as the eligibility-aware resolver for new receiver_user_id and contact_hash recipient creation.
- **Rationale:** InviteMutationService obtains the acting receiver profile id through recipientAccountProfileIdForUserId(), which calls identityGateway->resolveUserRecipient(). The Laravel adapter delegates that to InviteablePeopleService::recipientForUserId(), and the round 03 fix makes that method return null when the user's personal profile exists but is not currently inviteable. For an existing edge with receiver_account_profile_id, edgeBelongsToReceiver() then requires a non-null matching profile id and returns false, causing the canonical profile owner to receive invite_not_found on accept or decline. Inviteability is the correct gate for creating new recipients, but it is not a stable ownership predicate for existing profile-keyed invite mutations.

## Reviewer Summaries
### Elegance reviewer
- **Assessment:** Not clean. The round 03 fixes resolve the stale receiver_user_id actor case for the tested happy path, but they also route profile-keyed authorization through the inviteability-oriented recipient resolver. That conflates ownership with current recipient eligibility and can deny the legitimate canonical profile owner access to an existing profile-keyed invite.
- **Recommended path:** `Resolve before closing the round by separating actor/profile ownership resolution from invite-recipient eligibility. Keep non-inviteable profile suppression for new direct/contact-hash recipients, but use an ownership resolver that returns the acting user's canonical personal profile id independent of inviteability when authorizing accept/decline and profile-scoped lifecycle mutations.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-R04-001 Profile-keyed invite authorization now depends on inviteability instead of ownership: InviteMutationService obtains the acting receiver profile id through recipientAccountProfileIdForUserId(), which calls identityGateway->resolveUserRecipient(). The Laravel adapter delegates that to InviteablePeopleService::recipientForUserId(), and the round 03 fix makes that method return null when the user's personal profile exists but is not currently inviteable. For an existing edge with receiver_account_profile_id, edgeBelongsToReceiver() then requires a non-null matching profile id and returns false, causing the canonical profile owner to receive invite_not_found on accept or decline. Inviteability is the correct gate for creating new recipients, but it is not a stable ownership predicate for existing profile-keyed invite mutations.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

