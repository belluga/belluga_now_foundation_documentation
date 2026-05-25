# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Revise the TODO/package before implementation to freeze the sent-invite API contract, canonical recipient matching rules, and status/actionability semantics, then add fail-first tests that force those contracts across backend, Flutter hydration, optimistic state, and invite_accepted push handling.`

## Merged Findings
### F-A47BD8A5 [high] Terminal and superseded status semantics are named but not actioned
- **Reviewers:** elegance-lane-auditor
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a status/actionability matrix to the TODO covering display, summary counts, duplicate-invite disabling, reinvite eligibility, and responded_at expectations for pending, accepted, declined, superseded, stale, and hidden terminal statuses. Add Laravel payload tests and Flutter repository/controller tests for at least one non-accepted terminal case and one superseded/stale case.
- **Rationale:** The package requires pending, accepted, declined, and hidden terminal/superseded statuses, and it warns that stale/superseded statuses must not re-enable invite buttons incorrectly. However, the fail-first targets only cover pending and accepted. Without an explicit status actionability matrix, backend and Flutter can independently infer whether declined, superseded, stale, or hidden terminal records are displayed, counted, block reinvite, or allow reinvite.

### F-A9672943 [high] Sent-invite read contract lacks a frozen API boundary
- **Reviewers:** elegance-lane-auditor
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before APROVADO, define the exact sent-invite status endpoint contract including route, method, required identifiers, response schema, status ordering, empty response, 401/403/404/422 behavior, and occurrence/event mismatch handling. Add backend feature tests and Flutter repository tests that assert that exact shape rather than only the broad business outcome.
- **Rationale:** The package says to add or expose an authenticated read contract, but does not freeze method/path, event-versus-occurrence parameter ownership, response envelope, ordering, empty-state behavior, auth failure semantics, occurrence/event mismatch behavior, or tenant-not-found behavior. That is a pre-implementation contract gap: Laravel and Flutter can satisfy the broad intent while coupling to an ad hoc shape that later drifts from the canonical package-first direction.

### F-82F128F1 [high] Canonical recipient identity matching is acknowledged but not specified or tested
- **Reviewers:** elegance-lane-auditor
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Define a single recipient matching contract, including required backend fields and Flutter normalization rules for profile id, user id, invite id, event id, and occurrence id. Add fail-first tests where local inviteable recipient identity and backend account/profile identity differ, proving hydration and invite_accepted push still update the same sent-invite record and keep duplicate invite actions disabled.
- **Rationale:** The package flags account profile id versus account user id as a risk and says the payload should include recipient profile/user identity, but it does not define the canonical matching key shared by backend status hydration, InviteableRecipient identity, optimistic send state, duplicate-invite disabling, and invite_accepted push handling. This can recreate the production failure in a subtler form: accepted backend state exists, but Flutter cannot reconcile it to the local recipient row or push payload.

## Reviewer Summaries
### elegance-lane-auditor
- **Assessment:** The planned direction is structurally sound, but the pre-implementation contract is not approval-ready. The package identifies the right canonical-backend direction, yet leaves key API, identity, and status semantics under-specified enough that Laravel and Flutter could implement divergent interpretations.
- **Recommended path:** `Revise the TODO/package before implementation to freeze the sent-invite API contract, canonical recipient matching rules, and status/actionability semantics, then add fail-first tests that force those contracts across backend, Flutter hydration, optimistic state, and invite_accepted push handling.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] ELEGANCE-001 Sent-invite read contract lacks a frozen API boundary: The package says to add or expose an authenticated read contract, but does not freeze method/path, event-versus-occurrence parameter ownership, response envelope, ordering, empty-state behavior, auth failure semantics, occurrence/event mismatch behavior, or tenant-not-found behavior. That is a pre-implementation contract gap: Laravel and Flutter can satisfy the broad intent while coupling to an ad hoc shape that later drifts from the canonical package-first direction.
  - [high] ELEGANCE-002 Canonical recipient identity matching is acknowledged but not specified or tested: The package flags account profile id versus account user id as a risk and says the payload should include recipient profile/user identity, but it does not define the canonical matching key shared by backend status hydration, InviteableRecipient identity, optimistic send state, duplicate-invite disabling, and invite_accepted push handling. This can recreate the production failure in a subtler form: accepted backend state exists, but Flutter cannot reconcile it to the local recipient row or push payload.
  - [high] ELEGANCE-003 Terminal and superseded status semantics are named but not actioned: The package requires pending, accepted, declined, and hidden terminal/superseded statuses, and it warns that stale/superseded statuses must not re-enable invite buttons incorrectly. However, the fail-first targets only cover pending and accepted. Without an explicit status actionability matrix, backend and Flutter can independently infer whether declined, superseded, stale, or hidden terminal records are displayed, counted, block reinvite, or allow reinvite.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
