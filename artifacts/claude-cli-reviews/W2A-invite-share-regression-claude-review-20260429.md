---

## External Critical Review — Wave 2A Invite Share Regression Audit

**Reviewer basis:** Independent source-code read of all 7 modified files on the branch, cross-checked against package claims and test evidence.

---

### Overall Decision

**PROCEED.** No blocking release risks identified. One non-blocking evidence gap noted (scope declaration mismatch). All material behavioral claims in the package are verified by the implementation.

---

### Blocking Findings

None.

---

### Non-Blocking Findings

#### NB-1 — Scope declaration mismatch in package.md

The package's "Changed Source Files" lists 5 files, but the branch has 7 modified files. `lib/infrastructure/repositories/account_profiles_repository.dart` and `test/infrastructure/repositories/account_profiles_repository_test.dart` are co-resident on this branch and excluded from the audit scope without explanation.

The change is substantive: `toggleFavorite` was refactored to (a) gate telemetry behind a `persistenceSucceeded` flag (correcting a pre-existing bug where telemetry fired even on backend failure) and (b) invoke `_favoriteRepository?.refreshFavoriteResumes()` post-mutation to keep Home's canonical favorites stream in sync. Test coverage was added and runs in the focused Wave 2A suite. The change is behaviorally correct and an improvement.

**Why non-blocking:** test passes, the change is additive/corrective, no regression introduced. However the package's zero-context assumption means a future reviewer inheriting this resolution may not know these files were touched. The scope declaration should name them or explicitly exclude them with rationale.

#### NB-2 — Share text uses raw `DateTime.toString()` formatting

`InviteShareFooter` (line 77–79) interpolates `TimezoneConverter.utcToLocal(invite.eventDateTime)` directly into the share message string. If the return type is `DateTime`, `.toString()` produces `2026-03-13 17:00:00.000`, which is technically correct but user-unfriendly in a share message. Not a correctness or security concern; deferred share-sheet smoke (Wave 2D) will surface this visually.

#### NB-3 — No test for concurrent `reloadShareCode()` duplicate-call guard

The `_isShareCodeLoading` guard is implemented and correct, but the controller tests only cover the serial retry path (fail → reload → succeed). The concurrent path (call `reloadShareCode()` while a load is in flight) is not covered. Given the share code is loaded once on init and retried only on tap, the scenario is low-probability in production.

---

### Evidence Gaps

| Gap | Classification |
|---|---|
| `account_profiles_repository.dart` not in package scope declaration | Evidence gap — non-blocking (has test coverage, passes suite) |
| Concurrent `reloadShareCode()` guard untested | Accepted debt |
| ADB/native share-sheet smoke | Intentionally deferred (Wave 2D) |
| CI/promotion lanes | Intentionally deferred |

---

### Verification of Package Claims (confirmed)

- `isShareCodeLoadingStreamValue` defaults `true`, clears on failure ✓ (controller:51, 317–335)
- `Gerando...` → `Tentar novamente` → `Compartilhar` three-state CTA ✓ (footer:71–99, widget test lines 119–152)
- `refreshFriends()` drops duplicate while in-flight via `_isInviteablesRefreshing` guard ✓ (controller:227–229, controller test lines 443–480)
- All StreamValues disposed in `onDispose()` ✓ (controller:411–421)
- Navigation owned by screen (`_openGroupManagement`, context.router) — controller never touches `BuildContext` ✓
- No DTO leakage to UI; domain projections used throughout ✓
- Triple audit Round 01 returning zero findings is consistent with independent code read
