Now I have everything I need to write the review.

---

## Independent Critical Review — Store Release Wave 2A Home Favorites Refresh

**Package:** `store-release-wave2-home-favorites-refresh-audit-20260429`
**Branch:** `orchestration/store-release-wave2-social-consumer-gaps-20260429`
**Date:** 2026-04-29

---

### Overall Decision

**CONDITIONAL BLOCK** — one blocking finding must be resolved before merge. All other findings are accepted-debt or evidence gaps and do not block independently.

---

### Blocking Findings

#### [BLOCK-1] `refreshFavoriteResumes()` is inside the backend persistence try-catch — spurious rollback on Home refresh failure

**File:** `lib/infrastructure/repositories/account_profiles_repository.dart:154–180`

```dart
try {
  await _favoriteBackend.unfavoriteAccountProfile(normalizedProfileId);
  // OR favoriteAccountProfile(...)
  await _telemetryRepository.logEvent(...);
  await _favoriteRepository?.refreshFavoriteResumes();  // ← inside catch scope
} catch (error) {
  // rolls back _favoriteAccountProfileIds and stream          ← triggered by refresh failure too
}
```

The rollback guard was designed for backend persistence failure. The `refreshFavoriteResumes()` call is a Home UI stream invalidation — a distinct network operation. If it throws (e.g., transient network failure after the toggle already succeeded), the catch block executes and rolls back local state: the profile ID is removed/re-added in `_favoriteAccountProfileIds` and the stream is re-emitted, showing the profile as unfavorited in the UI even though the backend mutation succeeded. The error message "Failed to persist favorite mutation" is also misleading.

**Impact:** UI shows wrong favorite state after any transient failure of the Home stream refresh, requiring an app restart to correct. First-production behavior; no prior state to recover to.

**Minimum fix:** Move `refreshFavoriteResumes()` outside the try-catch, or wrap it in a separate swallowing try-catch that does not trigger the rollback.

**Test gap:** The test stub `_TrackingFavoriteRepository.fetchFavoriteResumes()` never throws, so the test suite cannot detect this scenario.

---

### Non-Blocking Findings

#### [DEBT-1] Invite share screen changes are co-shipped but not declared in "Changed Source Files"

Five files in the working tree are modified outside the stated package scope:
- `lib/…/invite_share_screen/controllers/invite_share_screen_controller.dart`
- `lib/…/invite_share_screen/invite_share_screen.dart`
- `lib/…/invite_share_screen/widgets/invite_share_footer.dart`
- `test/…/invite_share_screen_controller_test.dart`
- `test/…/invite_share_screen_test.dart`

The changes themselves are architecturally sound (concurrency guards, loading states, retry, proper dispose, no controller-in-widget, VoidCallback not controller reference). They are included in the 26-test passing suite. This is a package-scope declaration gap, not a code quality issue. Accepted-debt: package metadata should list these files to close audit traceability.

#### [DEBT-2] Static GetIt resolution at construction time — silent miss if registration order is wrong

`_resolveFavoriteRepositoryOrNull()` is called in the initializer list at construction. If `AccountProfilesRepository` is constructed before `FavoriteRepositoryContract` is registered, `_favoriteRepository` is `null` forever and refresh never fires. Graceful degradation (no crash), but a silent runtime miss. Non-blocking because DI registration order is assumed stable and the safe `isRegistered` check prevents crash.

#### [DEBT-3] Telemetry is awaited before Home refresh — unnecessary latency coupling

`await _telemetryRepository.logEvent(...)` precedes `await _favoriteRepository?.refreshFavoriteResumes()`. If telemetry is slow, the Home section refresh is delayed. No blocking impact; both could be parallelized or the refresh could be fire-and-forget with its own error boundary in a future cleanup.

---

### Evidence Gaps

| Gap | Severity |
|---|---|
| No test for rollback-on-refresh-failure: `refreshFavoriteResumes()` never throws in the stub | Supports BLOCK-1 above |
| Invite source files not declared in package "Changed Source Files" — unclear which TODO governs them | Package traceability gap; tests pass |
| `FavoriteRepositoryContract.refreshFavoriteResumes()` default implementation not visible in this package — test infers its behavior indirectly via counter | Inferred from analyzer + test pass; not independently verified here |
| ADB/device smoke deferred to Wave 2D | Accepted by orchestration policy; automated suite is sufficient for this lane |
