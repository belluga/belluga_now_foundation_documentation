Here is the critical review focused solely on release-blocking issues.

---

## External Critical Review — Store Release Wave 2: Invite External Contacts Delta

### VERDICT: No release blockers found.

---

### Finding 1 — Dead constructor parameter (non-blocking)

**File:** `lib/infrastructure/repositories/invites_repository.dart:46–50`
**Severity:** Warning — non-blocking

`FriendsRepositoryContract? friendsRepository` is accepted in the constructor but silently dropped; it is never stored or used. The import at line 38 is kept only for this dead parameter. This is not introduced by this delta (the change log cites only hash helper reuse), but the modified file carries it. Not a crash, not a data risk. Clean-up candidate after release.

---

### Finding 2 — Widget test fake's `createShareCode` relaxes `occurrenceId` to optional (non-blocking)

**File:** `test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart:263–264`
**Severity:** Info — non-blocking

The widget test's `_FakeInvitesRepository.createShareCode` declares `occurrenceId` as nullable/optional, while the real contract and the controller-test fake both declare it `required`. Dart permits this (widening an override is legal), so it compiles and the test passes. The assertion at line 152 (`expect(invitesRepository.lastShareCodeOccurrenceId, 'occurrence-1')`) correctly captures that the controller passes the ID. However, the fake's relaxed signature means a future caller could accidentally omit `occurrenceId` and the widget test would still compile without warning. No runtime defect now.

---

### Finding 3 — No widget test for `externalTargets.isNotEmpty && shareUri == null` (non-blocking)

**File:** `lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart:158–187`
**Severity:** Info — non-blocking

The screen correctly suppresses `InvitePhoneContactsEntry` when the share code failed (`shareUri == null`), even if unmatched contacts exist. No widget test exercises this path. The logic is a single conditional guard and is correct. Low regression risk; acceptable to defer coverage to a follow-up.

---

### Finding 4 — `_buildExternalShareTargets` hash intersection is one-direction (non-blocking, justified)

**File:** `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart:421–425`
**Severity:** Info — non-blocking

```dart
final matchedHashes = importedMatches
    .map((match) => match.contactHash.trim())
    .where((hash) => hash.isNotEmpty)
    .toSet();
```

Each `InviteContactMatch` contributes exactly one hash. If the backend ever returns a match with an empty `contactHash`, that contact's local entry is **not** excluded from external targets — the matched user would appear in both the Belluga inviteable list and the external share list simultaneously. The `.where(hash.isNotEmpty)` guard is defensive rather than assertive. The backend should always return non-empty hashes for matched contacts; the scenario is theoretical. Acceptable defensive design, not a functional bug under normal backend behaviour.

---

### Finding 5 — Hash normalization coverage for 10–11 digit variants (non-blocking, verified)

**File:** `lib/application/invites/invite_contact_import_hashes.dart:42–52`
**Severity:** Info — verified correct

The loop snapshots `variants` via `List<String>.from(variants)` before iterating. This means newly added variants (the `55`-prefixed or stripped forms) are not re-processed. Cross-checking: for a full 13-digit number (`5527999997777`), the loop strips to 11 digits (`27999997777`); for an 11-digit number, the loop adds the `55`-prefixed 13-digit form. Both generate two-way hashes in one pass. The intersection logic in the controller therefore correctly identifies cross-format matches. No asymmetry bug.

---

### Summary

| # | Location | Severity | Blocking? |
|---|---|---|---|
| 1 | `invites_repository.dart:46–50` | Warning | No — pre-existing dead param |
| 2 | `invite_share_screen_test.dart:263` | Info | No — Dart-legal override relaxation |
| 3 | `invite_share_screen.dart:158–187` | Info | No — path logic correct, coverage gap |
| 4 | `invite_share_screen_controller.dart:421–425` | Info | No — defensive guard, backend contract holds |
| 5 | `invite_contact_import_hashes.dart:42–52` | Info | No — hash symmetry verified |

The structural separation between the canonical Belluga inviteable list and the external-share branch is clean. The web-runtime exclusion gate is correctly placed and test-covered. The hash normalization algorithm is shared without duplication. No issue meets the release-blocking bar.

[claude_exit_status=0]
