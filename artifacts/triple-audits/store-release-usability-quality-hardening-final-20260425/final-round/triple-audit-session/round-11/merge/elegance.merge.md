# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-11/dispatch/elegance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`

## Recommended Paths
- `needs_resolution`

## Merged Findings
### F-CA19ED0D [medium] DiscoveryScreenController bypasses the mixin's disposed-state write guard after async restore
- **Reviewers:** elegance
- **Category:** `structural_soundness`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Route all discovery-filter StreamValue mutations through writePublicDiscoveryFilterState or an equivalent controller-level alive guard, especially after awaits in init/restore paths, and keep both mixin consumers on the same lifecycle discipline.
- **Rationale:** PublicDiscoveryFilterControllerMixin establishes writePublicDiscoveryFilterState to prevent writes after disposal, and TenantHomeAgendaController uses its own alive guard when restoring persisted filter selection. DiscoveryScreenController.init awaits loadPersistedPublicDiscoveryFilterSelection and then writes directly to discoveryFilterSelectionStreamValue. If the controller is disposed during that await, the restored-selection write can target a disposed StreamValue. The inconsistency weakens the shared mixin pattern and leaves one of the two consumers with a lifecycle race.

### F-94AF275E [medium] Canonical discovery-filter row still depends on legacy map/settings types
- **Reviewers:** elegance
- **Category:** `elegance`
- **Formalizable hint:** `partial`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Move the shared row/visual editor contract to a neutral tenant-admin filter visual component or introduce a discovery-filter visual value object that both the legacy map settings surface and canonical discovery-filter surface can adapt to, so discovery_filters no longer imports settings/map-specific aggregate types.
- **Rationale:** TenantAdminFilterCatalogRow lives under the discovery_filters presentation surface and is used by the new canonical discovery-filter editor, but it imports tenant_admin_settings.dart and accepts TenantAdminMapFilterMarkerOverride directly. TenantAdminDiscoveryFilterSurfaceScreen also adapts discovery-filter catalog items into TenantAdminMapFilterCatalogItem only to call the map-filter visual sheet. This removes row-rendering duplication, but it preserves an inverted dependency from the canonical discovery-filter surface back into legacy map/settings concepts, making the new abstraction less reusable and keeping map-specific language in the generic filter-editing path.

## Reviewer Summaries
### elegance
- **Assessment:** The current diff is largely directionally sound, but two structural seams remain below the quality bar for a final elegance lane: the canonical discovery-filter UI is still coupled to legacy map/settings concepts, and one public discovery controller bypasses the guarded state-write pattern introduced by the shared mixin.
- **Recommended path:** `needs_resolution`
- **Performance:** `acceptable`
- **Elegance:** `mixed`
- **Structural soundness:** `mixed`
- **Operational fit:** `acceptable`
- **Findings:**
  - [medium] R11-ELEGANCE-001 Canonical discovery-filter row still depends on legacy map/settings types: TenantAdminFilterCatalogRow lives under the discovery_filters presentation surface and is used by the new canonical discovery-filter editor, but it imports tenant_admin_settings.dart and accepts TenantAdminMapFilterMarkerOverride directly. TenantAdminDiscoveryFilterSurfaceScreen also adapts discovery-filter catalog items into TenantAdminMapFilterCatalogItem only to call the map-filter visual sheet. This removes row-rendering duplication, but it preserves an inverted dependency from the canonical discovery-filter surface back into legacy map/settings concepts, making the new abstraction less reusable and keeping map-specific language in the generic filter-editing path.
  - [medium] R11-ELEGANCE-002 DiscoveryScreenController bypasses the mixin's disposed-state write guard after async restore: PublicDiscoveryFilterControllerMixin establishes writePublicDiscoveryFilterState to prevent writes after disposal, and TenantHomeAgendaController uses its own alive guard when restoring persisted filter selection. DiscoveryScreenController.init awaits loadPersistedPublicDiscoveryFilterSelection and then writes directly to discoveryFilterSelectionStreamValue. If the controller is disposed during that await, the restored-selection write can target a disposed StreamValue. The inconsistency weakens the shared mixin pattern and leaves one of the two consumers with a lifecycle race.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

