I now have the full picture — implementation, tests, and all round resolutions. Here is the external re-review.

---

## Re-Review: W2A Home Favorites Refresh — Post-Resolution

**Reviewer scope:** Unresolved blocking release risks only. ADB/CI deferral is treated as intentional. No backward-compatibility requests for first-production favorite behavior.

---

### Overall Decision

**APPROVED — no unresolved blocking release risks.**

BLOCK-1 is genuinely closed. The separation is correctly implemented in code, not just claimed in prose. All remaining items are correctly classified as accepted debt or out-of-scope.

---

### Blocking Findings

**None.**

BLOCK-1 verification (source-confirmed, `lib/infrastructure/repositories/account_profiles_repository.dart:154–196`):

The three distinct error boundaries are in place:

- Persistence try/catch (lines 155–173): rolls back optimistic local state, sets `persistenceSucceeded = false`, returns early via guard at line 174–176.
- Home refresh try/catch (lines 177–182): `_favoriteRepository?.refreshFavoriteResumes()` is called only after `persistenceSucceeded = true`; failure is logged and never triggers rollback.
- Telemetry try/catch (lines 183–195): same pattern, isolated from both persistence and Home refresh.

The null-safe `?.` call on line 178 is consistent with `_resolveFavoriteRepositoryOrNull()` returning null when `FavoriteRepositoryContract` is not registered — no crash path.

Test coverage matches implementation:
- `toggleFavorite does not refresh Home favorite resumes when persistence fails` — asserts `fetchFavoriteResumesCallCount == 0` and local state rolled back.
- `toggleFavorite keeps persisted state when Home favorite resume refresh fails` — asserts `refreshFavoriteResumesCallCount == 1` and local favorite state preserved (line 418–423).

---

### Non-Blocking Findings

**DEBT-2 (GetIt registration order) — accepted, correctly classified.**
`_resolveFavoriteRepositoryOrNull()` resolves `FavoriteRepositoryContract` at construction time. If registration order ever changes (lazy init, module restructuring), `_favoriteRepository` silently becomes null and Home refresh is permanently suppressed with no diagnostic signal beyond `debugPrint`. This is a real latent risk if registration order changes, but not a current launch blocker. Mitigation: asserting registration presence in `AccountProfilesRepository` constructor or at module init would harden this if a second mutation surface appears.

**Test counter indirection (minor, observation only).**
The main refresh test (`toggleFavorite refreshes canonical favorite resumes consumed by Home after mutations`, line 259) asserts `fetchFavoriteResumesCallCount` as a proxy for the refresh having executed, rather than `refreshFavoriteResumesCallCount` directly. This works because `super.refreshFavoriteResumes()` calls back into `fetchFavoriteResumes()` polymorphically, and the stream value assertion (lines 304–308) provides direct behavioral proof. Not a blocking issue — the test is meaningful.

**`_generateMongoId()` microsecond collision (test-only, observation only).**
The ID generator at line 909–916 uses `DateTime.now().microsecondsSinceEpoch` and takes the first 24 hex characters. Rapid sequential calls within the same microsecond could return the same ID. This is test-infrastructure risk only with no production surface.

---

### Evidence Gaps

**None blocking release.**

The one gap worth noting for completeness: there is no automated test covering the path where `FavoriteRepositoryContract` is _not_ registered in GetIt at construction time (the silent null path). The current test suite always pre-registers it (lines 285–286, 354–355). This gap does not block release — it is the defensive no-op path — but it is the untested surface for DEBT-2.
