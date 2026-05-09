# PACED Subagent Review Merge: critique

## Merge Identity
- **Artifact kind:** `subagent_review_merge`
- **Authoritative:** `false`
- **Edit policy:** `derived_merge_packet`
- **Dispatch path:** `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/triple-audits/store-release-usability-quality-hardening-final-20260425/final-round/triple-audit-session/round-11/dispatch/performance.dispatch.json`
- **Review count:** `1`
- **Highest finding severity:** `medium`

## Axis Summary
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`

## Recommended Paths
- `needs_resolution: add a bounded recurring event-projection orphan cleanup path or extend the existing scheduled cleanup payload with event-safe deletion semantics before release signoff.`

## Merged Findings
### F-9E0A6F52 [medium] Scheduled Map POI cleanup no longer has an event orphan safety net
- **Reviewers:** performance-security-round-11
- **Category:** `security`
- **Formalizable hint:** `yes`
- **Candidate rule level:** `project`
- **Candidate rule id:** `n/a`
- **Suggested action:** Add a recurring bounded event orphan cleanup strategy, preferably by teaching the source reader/service to enumerate recently soft-deleted event ids and scheduling CleanupOrphanedMapPoisJob for event with a cutoff, or by extending the refresh service to fail-closed for missing/deleted event refs without waiting for active_window_end_at. Add scheduler-runtime coverage that the event projection safety net is actually dispatched.
- **Rationale:** The scheduled orphan cleanup dispatches only account_profile and static refs at routes/console.php:362-365, and the scheduler runtime test locks that payload at tests/Feature/Queue/TenantAwareSchedulerRuntimeTest.php:57-59. Event projections are public when is_active is true (MapPoiProjectionService.php:235 and MapPoiQueryService.php:1057-1059), while RefreshExpiredEventMapPoisJob only inspects active event POIs whose active_window_end_at has already passed (ExpiredEventMapPoiRefreshService.php:22-29). If the direct EventDeleted -> DeleteMapPoiByRefJob path is missed, delayed, or exhausted, a deleted event with a current/future active window can remain query-visible until expiry, and inactive orphan event projections are never swept by the scheduled payload. This is an operational fit and stale-public-data risk in the release hardening scope.

## Reviewer Summaries
### performance-security-round-11
- **Assessment:** One medium operational/security gap remains in the scheduled Map POI cleanup path. The broader performance/security hardening is generally sound, but event map projections now depend on direct delete jobs plus expired-active refresh, leaving no recurring bounded safety net for stale active event projections when async deletion is missed or permanently fails.
- **Recommended path:** `needs_resolution: add a bounded recurring event-projection orphan cleanup path or extend the existing scheduled cleanup payload with event-safe deletion semantics before release signoff.`
- **Performance:** `mixed`
- **Elegance:** `acceptable`
- **Structural soundness:** `mixed`
- **Operational fit:** `mixed`
- **Findings:**
  - [medium] PERFSEC-R11-001 Scheduled Map POI cleanup no longer has an event orphan safety net: The scheduled orphan cleanup dispatches only account_profile and static refs at routes/console.php:362-365, and the scheduler runtime test locks that payload at tests/Feature/Queue/TenantAwareSchedulerRuntimeTest.php:57-59. Event projections are public when is_active is true (MapPoiProjectionService.php:235 and MapPoiQueryService.php:1057-1059), while RefreshExpiredEventMapPoisJob only inspects active event POIs whose active_window_end_at has already passed (ExpiredEventMapPoiRefreshService.php:22-29). If the direct EventDeleted -> DeleteMapPoiByRefJob path is missed, delayed, or exhausted, a deleted event with a current/future active window can remain query-visible until expiry, and inactive orphan event projections are never swept by the scheduled payload. This is an operational fit and stale-public-data risk in the release hardening scope.

## Exact Next Step
Record reviewer resolutions in the governing TODO using the machine-checkable resolution table or equivalent gate ledger, then extract the derived resolution packet and decide whether another bounded review pass is still required.

