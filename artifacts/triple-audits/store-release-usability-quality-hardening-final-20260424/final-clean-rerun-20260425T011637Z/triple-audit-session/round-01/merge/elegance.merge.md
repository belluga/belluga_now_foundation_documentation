# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260424/final-clean-rerun-20260425T011637Z/triple-audit-session/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `Resolve the structural duplication before closing the elegance lane. Prefer narrow extractions of shared coordinators/codecs/projection helpers over a broad rewrite.`

## Merged Findings
### F-743DD545 [medium] Event occurrence programming projection rules are split across write, sync, and read services
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `single-occurrence-programming-projection-boundary`
- **Suggested action:** Extract canonical occurrence programming and party projection helpers, or a dedicated occurrence read-model formatter, and reuse it from sync/query paths. Keep EventQueryService focused on query selection and pagination rather than owning projection normalization.
- **Rationale:** Programming items, linked account profile summaries, taxonomy snapshot normalization, place_ref handling, ordering, and event-party merge behavior now appear in multiple forms across EventManagementService, EventOccurrenceSyncService, and EventQueryService. EventQueryService also absorbs occurrence aggregation, selected-occurrence resolution, detail formatting, and profile aggregation. This creates a high drift risk for the occurrence read model.

### F-FDFD8008 [medium] Discovery filter surface orchestration is duplicated across public controllers
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `shared-discovery-filter-surface-orchestration`
- **Suggested action:** Extract an application-level discovery filter surface coordinator parameterized by surface key, policy, persistence token, catalog repository, and query adapter. Keep host controllers responsible only for mapping the compiled payload into account-profile or schedule repository calls.
- **Rationale:** The patch adds nearly identical catalog loading, persisted-selection snapshot conversion, repair, equality comparison, persistence, and panel visibility flow to both DiscoveryScreenController and TenantHomeAgendaController. The surface key, policy, and repository query adapter vary, but the lifecycle is the same. This weakens the boundary around the new belluga_discovery_filters package because future changes to repair or persistence semantics must be updated in multiple host controllers.

### F-7BB85AA3 [medium] Discovery filter settings canonicalization is split between infrastructure and presentation
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `single-discovery-filter-settings-codec`
- **Suggested action:** Move discovery filter settings decoding, canonical surface normalization, and legacy map_ui fallback into a single application/domain codec used by the repository and admin controller. Let presentation operate on typed surface models instead of owning raw settings-map migration.
- **Rationale:** TenantAdminSettingsResponseDecoder normalizes discovery_filters surfaces and backfills legacy map_ui filters, while TenantAdminDiscoveryFiltersSettings repeats legacy map fallback and mutates the raw API JSON shape for save. This places contract migration logic in both infrastructure and presentation, making future settings-shape changes easy to apply in only one path.

### F-09581433 [medium] Account profile rich text sanitization depends on an Event package support class
- **Reviewers:** elegance
- **Category:** `architecture`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `no-cross-domain-event-sanitizer-reuse`
- **Suggested action:** Promote the sanitizer and rich-text limits to a neutral shared rich-text support service or package, then have event and account-profile code depend on that neutral abstraction. Keep the Flutter SafeRichHtml grammar explicitly aligned with the shared contract.
- **Rationale:** AccountProfileRichTextSanitizer delegates to Belluga\Events\Support\EventContentHtmlSanitizer. That makes account profile content behavior depend on an event-domain utility whose name and ownership are event-specific. The result is a stale abstraction and a weak package boundary for a shared rich-text grammar.

## Reviewer Summaries
### elegance
- **Assessment:** Mixed. The package is feature-complete and heavily validated, but the elegance gate is not clean: several new seams still duplicate orchestration, JSON canonicalization, and event occurrence projection rules across layers.
- **Recommended path:** `Resolve the structural duplication before closing the elegance lane. Prefer narrow extractions of shared coordinators/codecs/projection helpers over a broad rewrite.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] ELEGANCE-001 Discovery filter surface orchestration is duplicated across public controllers: The patch adds nearly identical catalog loading, persisted-selection snapshot conversion, repair, equality comparison, persistence, and panel visibility flow to both DiscoveryScreenController and TenantHomeAgendaController. The surface key, policy, and repository query adapter vary, but the lifecycle is the same. This weakens the boundary around the new belluga_discovery_filters package because future changes to repair or persistence semantics must be updated in multiple host controllers.
  - [medium] ELEGANCE-002 Account profile rich text sanitization depends on an Event package support class: AccountProfileRichTextSanitizer delegates to Belluga\Events\Support\EventContentHtmlSanitizer. That makes account profile content behavior depend on an event-domain utility whose name and ownership are event-specific. The result is a stale abstraction and a weak package boundary for a shared rich-text grammar.
  - [medium] ELEGANCE-003 Event occurrence programming projection rules are split across write, sync, and read services: Programming items, linked account profile summaries, taxonomy snapshot normalization, place_ref handling, ordering, and event-party merge behavior now appear in multiple forms across EventManagementService, EventOccurrenceSyncService, and EventQueryService. EventQueryService also absorbs occurrence aggregation, selected-occurrence resolution, detail formatting, and profile aggregation. This creates a high drift risk for the occurrence read model.
  - [medium] ELEGANCE-004 Discovery filter settings canonicalization is split between infrastructure and presentation: TenantAdminSettingsResponseDecoder normalizes discovery_filters surfaces and backfills legacy map_ui filters, while TenantAdminDiscoveryFiltersSettings repeats legacy map fallback and mutates the raw API JSON shape for save. This places contract migration logic in both infrastructure and presentation, making future settings-shape changes easy to apply in only one path.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

