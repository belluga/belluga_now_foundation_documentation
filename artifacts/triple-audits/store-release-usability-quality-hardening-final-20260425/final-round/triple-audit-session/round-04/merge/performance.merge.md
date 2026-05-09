# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-04/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `acceptable`
- **Operational fit:** `mixed`

## Recommended Paths
- `Resolve the two findings before treating the round as clean. Keep the current implementation direction, but add route-aware public event page-size enforcement and fail-visible taxonomy repair semantics so performance and operational release evidence cannot be accidentally overstated.`

## Merged Findings
### F-E1B881CB [medium] Taxonomy snapshot repair can report failed documents while the job and command still succeed
- **Reviewers:** round-04-performance-security
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Make repair failures fail-visible: log collection/model/id/error context, have the queued job throw or mark failure when `totals.failed > 0`, and have the CLI return nonzero unless an explicit `--allow-partial` mode is introduced. Add a negative test proving partial repair failure cannot be recorded as successful release evidence.
- **Rationale:** `TaxonomySnapshotBackfillService` catches every per-document `Throwable` and increments `failed`, but `RepairTaxonomyTermSnapshotsJob` ignores the returned summary and the console command returns exit code 0 even when `totals.failed` is nonzero. The release package depends on this repair path for taxonomy display labels and account-profile flat taxonomy projections; silent partial failure can leave stale slugs/projections in production without queue failure, nonzero CLI evidence, or actionable logs.

### F-311EEB82 [medium] Public events index still accepts 100-item pages instead of the public 50-item cap
- **Reviewers:** round-04-performance-security
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Apply a route-aware public cap using `InputConstraints::PUBLIC_PAGE_SIZE_MAX` for tenant-public `/events`, while preserving the admin/account 100-item cap only where needed. Add a regression test that public `/events?page_size=51` is rejected or clamped according to the chosen contract.
- **Rationale:** The package adds public page-size guardrails for agenda and account-profile lists, but the public `/events` route still uses `EventIndexRequest` with `page_size|max:100` and `EventsController::index` clamps to 100. `EventCrudControllerTest` also asserts `base_api_tenant events?page_size=100` succeeds. That route returns event management-format payloads with occurrence, linked-profile, taxonomy, and programming data, so public callers can still request twice the intended public page budget and amplify response/formatting cost.

## Reviewer Summaries
### round-04-performance-security
- **Assessment:** Mixed. The current package resolves the prior critical tenant-scope, rich-text, occurrence-query, and Playwright actionability concerns, and I did not find a new direct tenant-boundary or credential-exposure blocker. Two bounded release risks remain: public event listing still has a higher page-size ceiling than the new public list guardrail pattern, and taxonomy snapshot repair can silently complete with failed documents.
- **Recommended path:** `Resolve the two findings before treating the round as clean. Keep the current implementation direction, but add route-aware public event page-size enforcement and fail-visible taxonomy repair semantics so performance and operational release evidence cannot be accidentally overstated.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `acceptable`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] PERFSEC-R04-001 Public events index still accepts 100-item pages instead of the public 50-item cap: The package adds public page-size guardrails for agenda and account-profile lists, but the public `/events` route still uses `EventIndexRequest` with `page_size|max:100` and `EventsController::index` clamps to 100. `EventCrudControllerTest` also asserts `base_api_tenant events?page_size=100` succeeds. That route returns event management-format payloads with occurrence, linked-profile, taxonomy, and programming data, so public callers can still request twice the intended public page budget and amplify response/formatting cost.
  - [medium] PERFSEC-R04-002 Taxonomy snapshot repair can report failed documents while the job and command still succeed: `TaxonomySnapshotBackfillService` catches every per-document `Throwable` and increments `failed`, but `RepairTaxonomyTermSnapshotsJob` ignores the returned summary and the console command returns exit code 0 even when `totals.failed` is nonzero. The release package depends on this repair path for taxonomy display labels and account-profile flat taxonomy projections; silent partial failure can leave stale slugs/projections in production without queue failure, nonzero CLI evidence, or actionable logs.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

