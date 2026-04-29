# PACED Subagent Review Merge: test_quality_audit

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/dispatch/test-quality.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `not_evaluated`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`

## Recommended Paths
- `needs_resolution: keep this gate open until the missing group-management surface is implemented or explicitly accepted as debt, then add focused backend feature tests and Flutter widget/controller tests that fail on the missing behaviors before rerunning this audit lane.`

## Merged Findings
### F-46B6105F [high] Flutter relation-filter tests do not prove the inviteable list is narrowed
- **Reviewers:** TEST-QUALITY lane reviewer
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a widget test for InviteShareScreen with at least two inviteable recipients carrying disjoint reasons. Assert the default list is unified, tapping each relation chip hides non-matching rows, and Todos restores the full list.
- **Rationale:** The screen filters rows in InviteShareScreen._filterFriends, but the tests only assert that selecting a chip updates callback/controller state. A no-op or removed filter would still pass because no test pumps the screen with multiple recipients and verifies that the visible list changes by backend-provided inviteable_reasons.

### F-4EF5715A [high] Dedicated Flutter group-management UI remains unimplemented and untested
- **Reviewers:** TEST-QUALITY lane reviewer
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Implement the dedicated group/friends-management UI or record an explicit accepted-debt decision for this release gate. Add Flutter tests for group create, rename, delete, membership edit, and stale-member disappearance after refresh.
- **Rationale:** The TODO requires users to create, rename, delete, and manage contact groups through dedicated management surfaces, and the packet calls this a known gate gap. The current Flutter changed/tested surfaces only cover /convites/compartilhar inviteable rows and relation chips; there is no group-management screen/controller/widget test proving create/rename/delete or membership editing. This is missing final user-visible behavior, not just missing test organization.

### F-3A84C423 [high] Backend social-graph tests miss negative privacy and relation-derivation cases
- **Reviewers:** TEST-QUALITY lane reviewer
- **Category:** `tests`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add table-style backend feature tests for contact_match only, favorite_by_you only, favorited_you only, reciprocal friend, friends_only exposure rules, non-inviteable profile suppression, non-personal inviteable profile favorites, and the legacy no-personal-profile contact match fallback.
- **Rationale:** The social-graph test proves one happy reciprocal-favorite merge and one discoverability-off contact import case. It does not prove that unilateral favorite_by_you or favorited_you does not become friend, that friends_only exposure upgrades only under approved rules, that non-inviteable profiles are suppressed, that non-personal inviteable profile favorites stay separate from personal friend semantics, or that legacy user-only contact matches still work when no personal profile exists. These are contract/privacy behaviors called out by the TODO and bounded package.

### F-AC8226F6 [high] Backend contact-group CRUD and owner-privacy semantics are not fully proven
- **Reviewers:** TEST-QUALITY lane reviewer
- **Category:** `tests`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extend the backend feature test with PATCH rename, PATCH membership replacement, DELETE 204 plus absent-on-list, cross-owner 404, invalid empty name, and rejection/pruning of non-inviteable member IDs on both create and update.
- **Rationale:** StoreReleaseSocialGraphTest covers create, member dedupe, list, and stale-recipient pruning, but it does not exercise PATCH rename, PATCH membership replacement, DELETE, validation failures, or cross-owner access denial. Those are changed CRUD/mutation endpoints and user-private semantics in the TODO scope, so the current evidence can miss broken update/delete behavior or group leakage between users.

## Reviewer Summaries
### TEST-QUALITY lane reviewer
- **Assessment:** Not delivery-ready. The current tests cover some backend social graph and profile-scoped invite payload behavior, but they do not prove several TODO-critical behaviors: dedicated group-management UI, actual Flutter relation filtering, full backend contact-group CRUD/privacy semantics, and key social-graph negative/privacy cases.
- **Recommended path:** `needs_resolution: keep this gate open until the missing group-management surface is implemented or explicitly accepted as debt, then add focused backend feature tests and Flutter widget/controller tests that fail on the missing behaviors before rerunning this audit lane.`
- **Performance:** `not_evaluated`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] TQ-01 Dedicated Flutter group-management UI remains unimplemented and untested: The TODO requires users to create, rename, delete, and manage contact groups through dedicated management surfaces, and the packet calls this a known gate gap. The current Flutter changed/tested surfaces only cover /convites/compartilhar inviteable rows and relation chips; there is no group-management screen/controller/widget test proving create/rename/delete or membership editing. This is missing final user-visible behavior, not just missing test organization.
  - [high] TQ-02 Flutter relation-filter tests do not prove the inviteable list is narrowed: The screen filters rows in InviteShareScreen._filterFriends, but the tests only assert that selecting a chip updates callback/controller state. A no-op or removed filter would still pass because no test pumps the screen with multiple recipients and verifies that the visible list changes by backend-provided inviteable_reasons.
  - [high] TQ-03 Backend contact-group CRUD and owner-privacy semantics are not fully proven: StoreReleaseSocialGraphTest covers create, member dedupe, list, and stale-recipient pruning, but it does not exercise PATCH rename, PATCH membership replacement, DELETE, validation failures, or cross-owner access denial. Those are changed CRUD/mutation endpoints and user-private semantics in the TODO scope, so the current evidence can miss broken update/delete behavior or group leakage between users.
  - [high] TQ-04 Backend social-graph tests miss negative privacy and relation-derivation cases: The social-graph test proves one happy reciprocal-favorite merge and one discoverability-off contact import case. It does not prove that unilateral favorite_by_you or favorited_you does not become friend, that friends_only exposure upgrades only under approved rules, that non-inviteable profiles are suppressed, that non-personal inviteable profile favorites stay separate from personal friend semantics, or that legacy user-only contact matches still work when no personal profile exists. These are contract/privacy behaviors called out by the TODO and bounded package.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

