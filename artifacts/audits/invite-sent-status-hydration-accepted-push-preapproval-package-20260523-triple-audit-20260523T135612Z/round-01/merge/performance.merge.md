# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Update the TODO/package before implementation with bounded query/load behavior and test evidence for the new hydration path.`

## Merged Findings
### F-79CBE632 [high] Sent-invite hydration lacks enforceable bounded query and load-amplification criteria
- **Reviewers:** performance-lane-auditor
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Before implementation, add explicit acceptance criteria and fail-first tests: Laravel must fetch by authenticated inviter plus tenant plus event/occurrence using a direct bounded query path, eager-load recipient profile/avatar data, and include a query-count or equivalent regression guard. Flutter must hydrate via one occurrence-scoped backend contract, not list/page walking, and must dedupe/cache in-flight hydration so UI rebuilds, stream subscriptions, restart recovery, and push reconciliation do not multiply backend calls.
- **Rationale:** The package correctly identifies that sent-status hydration must avoid unbounded event-wide scans, N+1 profile/avatar lookups, and Flutter page-walking, but the planned contract and fail-first tests do not make those constraints enforceable. The new Laravel read contract returns recipient identity, display name, avatar URL, status, sent time, and responded time, which can regress into event-wide invite scans plus per-recipient profile/avatar lookups. Flutter getSentInvitesForOccurrence() is planned to hydrate from backend, but the TODO does not specify cache/in-flight dedupe or a single direct occurrence-scoped call, leaving room for stream/listener/rebuild-driven load amplification.

## Reviewer Summaries
### performance-lane-auditor
- **Assessment:** Needs resolution. The TODO/package must define enforceable performance acceptance criteria and tests for the sent-invite status hydration path: direct indexed backend lookup, eager-loaded recipient identity/avatar data, no event-wide scans, no N+1, and no Flutter page-walking or repeated hydration amplification.
- **Recommended path:** `Update the TODO/package before implementation with bounded query/load behavior and test evidence for the new hydration path.`
- **Performance:** `mixed`
- **Elegance:** `not_evaluated`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [high] PERF-001 Sent-invite hydration lacks enforceable bounded query and load-amplification criteria: The package correctly identifies that sent-status hydration must avoid unbounded event-wide scans, N+1 profile/avatar lookups, and Flutter page-walking, but the planned contract and fail-first tests do not make those constraints enforceable. The new Laravel read contract returns recipient identity, display name, avatar URL, status, sent time, and responded time, which can regress into event-wide invite scans plus per-recipient profile/avatar lookups. Flutter getSentInvitesForOccurrence() is planned to hydrate from backend, but the TODO does not specify cache/in-flight dedupe or a single direct occurrence-scoped call, leaving room for stream/listener/rebuild-driven load amplification.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.
