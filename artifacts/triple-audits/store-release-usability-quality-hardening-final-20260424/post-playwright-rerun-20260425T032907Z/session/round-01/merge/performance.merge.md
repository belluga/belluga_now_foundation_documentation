# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/post-playwright-rerun-20260425T032907Z/session/round-01/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `high`

## Axis Summary
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`

## Recommended Paths
- `Resolve the eager-rendering risk, regenerate the bounded package with untracked production files included, and rerun the performance lane.`

## Merged Findings
### F-CA8F3149 [high] Audit package omits untracked production files imported by tracked changes
- **Reviewers:** performance
- **Category:** `operational_fit`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Regenerate the bounded package with untracked production files included or stage them before creating the audit diff, then rerun the performance lane.
- **Rationale:** The performance lane cannot cleanly validate discovery-filter runtime behavior because flutter-status.txt lists untracked production code under lib/presentation/shared/discovery_filters/ and tenant_admin_discovery_filters_settings_canonicalizer.dart, while changed tracked controllers import those files. The full patch does not include their contents, hiding core caching/debounce/persistence/loading orchestration.

### F-C88D7985 [medium] Event detail date/programming UI eagerly renders bounded-but-large collections
- **Reviewers:** performance
- **Category:** `performance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Convert vertical programming/date content to lazy sliver/ListView.builder composition or cap/collapse visible rows, and use ListView.builder for the horizontal date selector.
- **Rationale:** The new event detail date/programming UI eagerly builds all occurrences and programming items using Column/spread maps and a horizontal SingleChildScrollView Row. Backend constraints allow up to 120 occurrences, 240 programming items, and 480 programming references, so one valid event can create hundreds of cards/chips/profile image widgets at once. Evidence cited by reviewer: EventDatesSection, EventProgrammingSection, and _ProgrammingDateSelector use eager maps/loops, while Laravel constants allow large totals.

## Reviewer Summaries
### performance
- **Assessment:** Not clean. The bounded artifacts show one concrete Flutter eager-rendering risk, and the audit package omits untracked production files needed to validate shared discovery-filter loading behavior.
- **Recommended path:** `Resolve the eager-rendering risk, regenerate the bounded package with untracked production files included, and rerun the performance lane.`
- **Performance:** `regresses`
- **Elegance:** `mixed`
- **Structural soundness:** `regresses`
- **Operational fit:** `regresses`
- **Findings:**
  - [medium] PERF-01 Event detail date/programming UI eagerly renders bounded-but-large collections: The new event detail date/programming UI eagerly builds all occurrences and programming items using Column/spread maps and a horizontal SingleChildScrollView Row. Backend constraints allow up to 120 occurrences, 240 programming items, and 480 programming references, so one valid event can create hundreds of cards/chips/profile image widgets at once. Evidence cited by reviewer: EventDatesSection, EventProgrammingSection, and _ProgrammingDateSelector use eager maps/loops, while Laravel constants allow large totals.
  - [high] PERF-02 Audit package omits untracked production files imported by tracked changes: The performance lane cannot cleanly validate discovery-filter runtime behavior because flutter-status.txt lists untracked production code under lib/presentation/shared/discovery_filters/ and tenant_admin_discovery_filters_settings_canonicalizer.dart, while changed tracked controllers import those files. The full patch does not include their contents, hiding core caching/debounce/persistence/loading orchestration.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

