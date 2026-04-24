# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-recut-quality-20260424/store-release-usability-recut-quality-triple-audit-20260424T160027Z/round-01/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `Address the medium findings before promotion. The low findings can be resolved in the same cleanup pass if touched, or explicitly tracked as bounded follow-up if promotion pressure is high.`

## Merged Findings
### F-4C65F75D [medium] Interactive chip semantics are replaced without equivalent tap actions
- **Reviewers:** elegance
- **Category:** `operational_fit`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `paced`
- **Candidate rule id:** `n/a`
- **Suggested action:** Either stop excluding the native FilterChip semantics, or add equivalent Semantics actions such as onTap, focusable, selected/toggled state, and disabled handling to every replacement wrapper.
- **Rationale:** Several chip wrappers add an outer Semantics node and wrap the native FilterChip or tappable chip body in ExcludeSemantics, but the replacement Semantics does not consistently provide an onTap action. This is concrete in the taxonomy term chips in the discovery filter package and the tenant-admin taxonomy selection chips. The primary discovery chips already provide a semantic onTap, which highlights the intended pattern. Without the equivalent action, assistive technology can receive a button-like label/state while the actionable native chip semantics are hidden.

### F-72EC712F [medium] Event form screen now owns too much occurrence and programming orchestration
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Extract occurrence/programming editing into dedicated widgets plus a small form-state coordinator or controller helper. Keep the screen mostly declarative, and centralize reusable validation and submit mapping away from the widget class.
- **Rationale:** The event form screen grew substantial UI-local logic for occurrence editing, programming item validation, time sorting, profile linking/unlinking, modal flow, UTC submit conversion, and taxonomy submit filtering. Some of this overlaps with controller and backend validation responsibilities. This concentration makes the launch form harder to reason about and increases the chance that future occurrence or programming changes diverge between UI, controller, and API contract behavior.

### F-FDB23FB5 [low] Occurrence location override remnants contradict the recut model
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Delete the unused occurrence override resolver and simplify the sync location helper signature, or isolate the future override path behind an explicit documented TODO rather than leaving contradictory live code.
- **Rationale:** The recut now rejects occurrence-level location and place_ref payloads, and the UI tests assert occurrence-level location UI remains absent. However EventManagementService still contains a resolver for occurrence location overrides, and EventOccurrenceSyncService accepts occurrence input in resolveEffectiveLocationPayload while ignoring it and forcing event-level location. This dead transition code is not a current behavior bug, but it weakens structural clarity around a sensitive model decision.

### F-3CB1E53E [low] Discovery filter catalog derivation is split across controller and package service
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move type-driven catalog derivation into an application service or provider registered with the discovery-filter package, leaving the controller to authorize/request/serialize only.
- **Rationale:** The new DiscoveryFiltersController directly derives home.events and discovery.account_profiles catalogs from EventType, TenantProfileType, Taxonomy, and TaxonomyTerm models while the discovery filter package service handles configured surface definitions. That split leaves surface derivation policy in the HTTP controller instead of a reusable application/catalog provider boundary, making future surfaces likely to duplicate query and normalization logic.

## Reviewer Summaries
### elegance
- **Assessment:** The recut appears behaviorally aligned with the bounded package, but it is not structurally clean enough to call the elegance lane clean. The main risks are accessibility semantics introduced by custom chip wrappers and large UI/controller seams that now own domain-like occurrence and discovery-filter orchestration.
- **Recommended path:** `Address the medium findings before promotion. The low findings can be resolved in the same cleanup pass if touched, or explicitly tracked as bounded follow-up if promotion pressure is high.`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] ELEGANCE-001 Interactive chip semantics are replaced without equivalent tap actions: Several chip wrappers add an outer Semantics node and wrap the native FilterChip or tappable chip body in ExcludeSemantics, but the replacement Semantics does not consistently provide an onTap action. This is concrete in the taxonomy term chips in the discovery filter package and the tenant-admin taxonomy selection chips. The primary discovery chips already provide a semantic onTap, which highlights the intended pattern. Without the equivalent action, assistive technology can receive a button-like label/state while the actionable native chip semantics are hidden.
  - [medium] ELEGANCE-002 Event form screen now owns too much occurrence and programming orchestration: The event form screen grew substantial UI-local logic for occurrence editing, programming item validation, time sorting, profile linking/unlinking, modal flow, UTC submit conversion, and taxonomy submit filtering. Some of this overlaps with controller and backend validation responsibilities. This concentration makes the launch form harder to reason about and increases the chance that future occurrence or programming changes diverge between UI, controller, and API contract behavior.
  - [low] ELEGANCE-003 Discovery filter catalog derivation is split across controller and package service: The new DiscoveryFiltersController directly derives home.events and discovery.account_profiles catalogs from EventType, TenantProfileType, Taxonomy, and TaxonomyTerm models while the discovery filter package service handles configured surface definitions. That split leaves surface derivation policy in the HTTP controller instead of a reusable application/catalog provider boundary, making future surfaces likely to duplicate query and normalization logic.
  - [low] ELEGANCE-004 Occurrence location override remnants contradict the recut model: The recut now rejects occurrence-level location and place_ref payloads, and the UI tests assert occurrence-level location UI remains absent. However EventManagementService still contains a resolver for occurrence location overrides, and EventOccurrenceSyncService accepts occurrence input in resolveEffectiveLocationPayload while ignoring it and forcing event-level location. This dead transition code is not a current behavior bug, but it weakens structural clarity around a sensitive model decision.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

