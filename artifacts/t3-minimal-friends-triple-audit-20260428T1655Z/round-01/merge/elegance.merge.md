# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `Resolve the high-severity Flutter blockers before advancing this TODO gate: implement the dedicated contact-group management surface or formally revise/accept that debt with gate-owner approval, and remove the duplicate placeholder-row rendering from the invite share screen. Then decouple the new backend-computed inviteable query from the legacy contact-import flow and tighten the backend package boundary for contact-group/inviteable endpoints.`

## Merged Findings
### F-37FF23C2 [high] Invite share screen duplicates the backend-deduplicated recipient list
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Remove recipient padding based on real data. Render backend inviteables exactly once, and use a separate empty/loading/skeleton state that does not reuse real recipient identities. Add a widget test proving a single backend recipient appears once before and after relation filtering.
- **Rationale:** The screen renders _paddedFriends(filteredFriends), and _paddedFriends repeats real friends until there are 20 rows. Those placeholder rows still display the same recipient name/avatar/match label through InviteShareFriendCard, only disabling the CTA. This directly violates the frozen contract that /convites/compartilhar shows one deduplicated inviteable row per canonical recipient and does not duplicate recipients across the UI.

### F-81481D2B [high] Dedicated Flutter contact-group management is still absent
- **Reviewers:** elegance
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Implement the dedicated group/friends-management Flutter surface with GET/POST/PATCH/DELETE /contact-groups wiring, membership editing by receiver_account_profile_id, and widget/controller/repository tests; otherwise formally revise this TODO gate and record the accepted debt before advancing.
- **Rationale:** The TODO acceptance criteria require users to create, rename, and delete contact groups through dedicated management surfaces, and the Flutter validation matrix requires group CRUD coverage outside /convites/compartilhar. The local delivery notes explicitly mark that surface as pending. I found backend CRUD only; Flutter has no contact-group backend contract, repository methods, domain model, route, screen, or tests. This is not polish: it leaves a V1 product behavior in the release-critical TODO undelivered.

### F-43C7F004 [medium] The new inviteable query is still gated by the old contact-import flow
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Treat contact import as opportunistic acquisition/refresh, then always fetch /contacts/inviteables when possible. Adjust tests so import failure does not hide existing backend inviteables from favorite/friend/contact-group flows.
- **Rationale:** InviteShareScreenController calls importContacts before fetchInviteableRecipients, and the safe wrapper clears the whole list if importContacts throws. The test suite codifies this by expecting empty suggestions when import fails. That preserves the old contact-import shell as a hard prerequisite for the new backend-computed inviteable list, even though favorites/favorited_you/friend inviteables should be fetchable independently.

### F-FD98C3D1 [medium] Contact-group backend ownership is split across app-level social code and the invites package
- **Reviewers:** elegance
- **Category:** `architecture`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either move contact-group/inviteable endpoint ownership into the belluga_invites package behind app-level gateways, or document App\Application\Social as the canonical cross-package integration boundary and reuse/shared-standardize the invite error-handling contract.
- **Rationale:** The route file mixes Belluga\Invites package controllers for invite/contact-import flows with App\Http controllers for contact inviteables and contact groups. ContactGroupController also reimplements the package's InviteDomainException envelope while App\Application\Social owns services/models that depend on invite-package concepts. This is a package-coherence drift risk: the same invite/social API family now has parallel implementation roots likely to diverge.

## Reviewer Summaries
### elegance
- **Assessment:** Not clean for the elegance gate. The backend slice largely follows the frozen contact_match/favorite/friend direction, but the Flutter surface still has two release-blocking structural gaps: the dedicated contact-group management surface is absent, and /convites/compartilhar re-duplicates the backend-deduplicated inviteable list with placeholder rows. The known Flutter group-management gap does block this TODO gate under the current TODO/module contracts.
- **Recommended path:** `Resolve the high-severity Flutter blockers before advancing this TODO gate: implement the dedicated contact-group management surface or formally revise/accept that debt with gate-owner approval, and remove the duplicate placeholder-row rendering from the invite share screen. Then decouple the new backend-computed inviteable query from the legacy contact-import flow and tighten the backend package boundary for contact-group/inviteable endpoints.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] ELEGANCE-001 Dedicated Flutter contact-group management is still absent: The TODO acceptance criteria require users to create, rename, and delete contact groups through dedicated management surfaces, and the Flutter validation matrix requires group CRUD coverage outside /convites/compartilhar. The local delivery notes explicitly mark that surface as pending. I found backend CRUD only; Flutter has no contact-group backend contract, repository methods, domain model, route, screen, or tests. This is not polish: it leaves a V1 product behavior in the release-critical TODO undelivered.
  - [high] ELEGANCE-002 Invite share screen duplicates the backend-deduplicated recipient list: The screen renders _paddedFriends(filteredFriends), and _paddedFriends repeats real friends until there are 20 rows. Those placeholder rows still display the same recipient name/avatar/match label through InviteShareFriendCard, only disabling the CTA. This directly violates the frozen contract that /convites/compartilhar shows one deduplicated inviteable row per canonical recipient and does not duplicate recipients across the UI.
  - [medium] ELEGANCE-003 The new inviteable query is still gated by the old contact-import flow: InviteShareScreenController calls importContacts before fetchInviteableRecipients, and the safe wrapper clears the whole list if importContacts throws. The test suite codifies this by expecting empty suggestions when import fails. That preserves the old contact-import shell as a hard prerequisite for the new backend-computed inviteable list, even though favorites/favorited_you/friend inviteables should be fetchable independently.
  - [medium] ELEGANCE-004 Contact-group backend ownership is split across app-level social code and the invites package: The route file mixes Belluga\Invites package controllers for invite/contact-import flows with App\Http controllers for contact inviteables and contact groups. ContactGroupController also reimplements the package's InviteDomainException envelope while App\Application\Social owns services/models that depend on invite-package concepts. This is a package-coherence drift risk: the same invite/social API family now has parallel implementation roots likely to diverge.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

