# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/store-release-wave2-invite-external-contacts-audit-20260429/triple-audit/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `Require a fix before release: make external targets appear only after a successful contact-import classification pass, and centralize the WhatsApp phone target normalization so local Brazilian numbers produce a valid international wa.me target or fall back to system share.`

## Merged Findings
### F-81352A51 [high] WhatsApp handoff bypasses canonical phone normalization
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move per-contact WhatsApp target normalization into a shared invite/contact application helper or reuse the existing phone variant logic with an explicit preferred E.164-style target. Add a focused test for a local Brazilian phone number and for fallback behavior when no valid direct WhatsApp target can be built.
- **Rationale:** The screen builds wa.me targets by stripping non-digits from the contact phone, while the shared invite contact normalization helper already knows that local Brazilian phone inputs need country-code variants. A local contact formatted as (27) 99999-9999 can produce wa.me/27999999999 instead of an international 55-prefixed target; launchUrl can still succeed by opening the URL, so the system-share fallback may never run. That breaks the primary per-contact external share action for a common local contact shape.

### F-A981D03A [high] External contacts fail open when contact import fails during initial load
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Represent contact-import failure separately from a successful zero-match result. On suppressed import failure, leave externalContactShareTargetsStreamValue empty and add focused coverage proving local contacts are not exposed externally unless import classification succeeded.
- **Rationale:** On initial load, _importContactsOpportunistically suppresses import failures and returns an empty match list. _buildExternalShareTargets then treats that empty list as authoritative and exposes every local contact with a phone or email as unmatched. This contradicts the canonical split where external targets are only unmatched contacts after backend import classification, and it can duplicate or misroute Belluga contacts into the external-share branch.

## Reviewer Summaries
### elegance
- **Assessment:** Blocked. The external-contact branch is structurally close to the accepted shape, but it currently fails open when contact import is suppressed on initial load, allowing unverified local contacts to enter the external-share path. A second blocking correctness risk remains in the per-contact WhatsApp handoff because it duplicates phone normalization outside the shared contact normalization helper.
- **Recommended path:** `Require a fix before release: make external targets appear only after a successful contact-import classification pass, and centralize the WhatsApp phone target normalization so local Brazilian numbers produce a valid international wa.me target or fall back to system share.`
- **Performance:** `acceptable`
- **Elegance:** `regresses`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] ELEGANCE-001 External contacts fail open when contact import fails during initial load: On initial load, _importContactsOpportunistically suppresses import failures and returns an empty match list. _buildExternalShareTargets then treats that empty list as authoritative and exposes every local contact with a phone or email as unmatched. This contradicts the canonical split where external targets are only unmatched contacts after backend import classification, and it can duplicate or misroute Belluga contacts into the external-share branch.
  - [high] ELEGANCE-002 WhatsApp handoff bypasses canonical phone normalization: The screen builds wa.me targets by stripping non-digits from the contact phone, while the shared invite contact normalization helper already knows that local Brazilian phone inputs need country-code variants. A local contact formatted as (27) 99999-9999 can produce wa.me/27999999999 instead of an international 55-prefixed target; launchUrl can still succeed by opening the URL, so the system-share fallback may never run. That breaks the primary per-contact external share action for a common local contact shape.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

