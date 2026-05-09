# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-07/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `Resolve the account_context_ids backfill/index gap before release signoff. Also bound agenda/event-stream coordinates and include the shared sanitizer fixture in tracked review state before treating the security evidence as reproducible.`

## Merged Findings
### F-04FBFC91 [high] Account-scoped event reads depend on account_context_ids without a backfill or supporting indexes
- **Reviewers:** round-07-performance-security-no-context-auditor
- **Category:** `performance`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a tenant migration or fail-visible repair job to backfill account_context_ids on events and event_occurrences from place_ref, event_parties, and programming items. Add indexes for account_context_ids plus the relevant deleted_at/starts_at/date_time_start ordering predicates. Add regression coverage for legacy records missing the denormalized field and for the indexed aggregate shape.
- **Rationale:** The new account-scoped paths filter exclusively on account_context_ids in EventQueryService::applyAccountFiltersToQuery and in EventManagementOccurrenceQuery before grouping. EventManagementService/EventOccurrenceSyncService only populate that field on write/sync. Local migration inspection found no tenant migration or repair job that backfills account_context_ids for existing events/event_occurrences, and no events/event_occurrences index covering the new hot predicate. Existing account-owned events from before this change can disappear from account-scoped management lists, and newly synced high-volume tenants can still scan broad occurrence sets before grouping.

### F-7A214881 [medium] Shared rich-text sanitizer parity fixture is untracked
- **Reviewers:** round-07-performance-security-no-context-auditor
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add the fixture to tracked review state and rerun the sanitizer parity tests from a clean checkout/package view. Keep the fixture in the bounded package because it is security-relevant evidence, not incidental local data.
- **Rationale:** Both Laravel and Flutter sanitizer tests read tests/Fixtures/shared_rich_text/safe_rich_html_fixtures.json, but git status shows tests/Fixtures/shared_rich_text/ as untracked and it is absent from the dev diff. The package claims cross-stack sanitizer fixture coverage as security evidence, but a clean checkout or CI package will not contain the fixture, making the evidence non-reproducible and potentially failing the tests that are supposed to guard sanitizer parity.

### F-09B23D18 [medium] Public agenda and event stream geo inputs still accept out-of-range coordinates
- **Reviewers:** round-07-performance-security-no-context-auditor
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add between:-90,90 and between:-180,180 validation to agenda/event-stream coordinates, mirror defensive bounds in EventQueryService for direct service calls, and add negative tests for /agenda and /events/stream invalid coordinates.
- **Rationale:** AgendaIndexRequest now caps page size, filter list sizes, and radius, but origin_lat/origin_lng are only numeric|required_with. EventStreamRequest inherits the same request rules. EventQueryService then casts any numeric coordinate and sends it directly to Mongo $geoNear. Unlike AccountProfileNearRequest, invalid latitude/longitude values are not bounded to [-90,90] and [-180,180], so malformed public requests can reach aggregation as invalid coordinates and cause avoidable runtime failures instead of deterministic 422 responses.

## Reviewer Summaries
### round-07-performance-security-no-context-auditor
- **Assessment:** Not clean. The diff contains substantial performance/security hardening, but local inspection against dev still found material release risks: account-scoped event reads now depend on a new denormalized account_context_ids field without a backfill/index migration, public agenda geo inputs still accept out-of-range coordinates, and the shared rich-text sanitizer fixture used as security parity evidence is untracked.
- **Recommended path:** `Resolve the account_context_ids backfill/index gap before release signoff. Also bound agenda/event-stream coordinates and include the shared sanitizer fixture in tracked review state before treating the security evidence as reproducible.`
- **Performance:** `regresses`
- **Elegance:** `acceptable`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [high] R07-PERFSEC-001 Account-scoped event reads depend on account_context_ids without a backfill or supporting indexes: The new account-scoped paths filter exclusively on account_context_ids in EventQueryService::applyAccountFiltersToQuery and in EventManagementOccurrenceQuery before grouping. EventManagementService/EventOccurrenceSyncService only populate that field on write/sync. Local migration inspection found no tenant migration or repair job that backfills account_context_ids for existing events/event_occurrences, and no events/event_occurrences index covering the new hot predicate. Existing account-owned events from before this change can disappear from account-scoped management lists, and newly synced high-volume tenants can still scan broad occurrence sets before grouping.
  - [medium] R07-PERFSEC-002 Public agenda and event stream geo inputs still accept out-of-range coordinates: AgendaIndexRequest now caps page size, filter list sizes, and radius, but origin_lat/origin_lng are only numeric|required_with. EventStreamRequest inherits the same request rules. EventQueryService then casts any numeric coordinate and sends it directly to Mongo $geoNear. Unlike AccountProfileNearRequest, invalid latitude/longitude values are not bounded to [-90,90] and [-180,180], so malformed public requests can reach aggregation as invalid coordinates and cause avoidable runtime failures instead of deterministic 422 responses.
  - [medium] R07-PERFSEC-003 Shared rich-text sanitizer parity fixture is untracked: Both Laravel and Flutter sanitizer tests read tests/Fixtures/shared_rich_text/safe_rich_html_fixtures.json, but git status shows tests/Fixtures/shared_rich_text/ as untracked and it is absent from the dev diff. The package claims cross-stack sanitizer fixture coverage as security evidence, but a clean checkout or CI package will not contain the fixture, making the evidence non-reproducible and potentially failing the tests that are supposed to guard sanitizer parity.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

