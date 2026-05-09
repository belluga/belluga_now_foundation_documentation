---

## Round 04 External Critical Review — T3 Minimal Contacts / Favorites / Friends

**Reviewer role:** No-context external auditor. No files modified. Code read only.
**Scope:** Round 03 blocker resolutions in the five bounded files listed in the review packet.

---

## Verdict

**PASS — gate can proceed.**

All three round 03 blockers are genuinely resolved with working code and adequate test evidence. No new blocking findings were introduced by the round 03 changes.

---

## Blocking Findings

**None.**

---

## Non-Blocking Debt

### NB-1 — `_nameController` owned by screen State, not controller
**File:** `contact_group_management_screen.dart:32`
`_ContactGroupManagementScreenState` holds `final _nameController = TextEditingController()`. Per architecture rules, `TextEditingController` must live in the feature controller. This is pre-existing debt — not introduced by round 03 — and the round 03 fix correctly targeted only the dialog controller. Acceptable as-is for T3, but should be migrated to `ContactGroupManagementController` before any architectural hardening pass.

### NB-2 — `recipientForUserId` bypasses the pre-fetched capabilities map
**File:** `InviteablePeopleService.php:365`
`$this->profileIsInviteable($profile)` is called with no `$capabilitiesByType` argument, falling back to a per-call `TenantProfileType` DB query (line 517–522). The bulk-loading pattern used in `inviteableItemsFor` is not applied here. No correctness issue on this single-user lookup path, but it is an O(1) extra query per `recipientForUserId` call. Not blocking; note for a future query-optimization pass.

### NB-3 — Contact-hash blocked-path assertion is incomplete
**File:** `StoreReleaseSocialGraphTest.php:611–619`
The `contact_hash` suppression sub-test asserts `created` count is 0 and `blocked.0.reason` is `suppressed`, but does not assert `blocked.0.receiver_user_id` or `blocked.0.receiver_account_profile_id`. The positive suppression behavior is proven by the code path (hash resolves to `receiver_user_id` → `resolveUserRecipient` → `recipientForUserId` → null), but the assertion surface is thinner than the `receiver_user_id` path directly above it. Low risk; note for test hygiene.

### NB-4 — `_selectedProfileIds` as mutable State field
**File:** `contact_group_management_screen.dart:33`
`_selectedProfileIds = <String>{}` is a mutable field in the State class. Allowed as a constrained ephemeral UI concern (create-panel chip selection, single-route lifespan, no cross-controller leakage), so it falls within the Soft NO exception. Flagged only for completeness — not a bug or an architecture violation given the constraint.

---

## Comparison Notes Against Round 03 Blockers

| Round 03 Blocker | Claimed Resolution | Code Verified | Test Verified |
|---|---|---|---|
| `ELEGANCE-R03-001`: `recipientForUserId` ignoring profile inviteability | `recipientForUserId` returns null when profile exists but `profileIsInviteable` is false; allows null-profile legacy fallback | **Confirmed** — lines 364–375 implement exactly this two-branch guard | `test_legacy_user_and_contact_hash_recipient_paths_respect_profile_inviteability` covers non-inviteable profile suppression (blocked) and no-profile legacy path (allowed with `receiver_account_profile_id=null`) |
| `TQ-R03-001`: `edgeBelongsToReceiver` allowing stale `receiver_user_id` actor on profile-keyed edge | Method now requires `receiverAccountProfileId` match when edge carries `receiver_account_profile_id`; pure user-id match is only accepted when edge has no profile id | **Confirmed** — lines 760–771: if `$edgeProfileId !== null`, returns false unless `$receiverAccountProfileId !== null && $edgeProfileId === $receiverAccountProfileId` | `test_account_profile_recipient_rejects_legacy_receiver_user_actor_mismatch` proves 404 on accept/decline by the legacy actor, edge stays `pending`, and canonical profile owner accepts successfully |
| `TQ-R03-002`: Contact-group widget test lacked create/rename/edit-members/delete evidence | Expanded widget test exercises all four operations with visible state assertions; `_RenameGroupDialog` converted to `StatefulWidget` owning its own `TextEditingController` | **Confirmed** — `_RenameGroupDialog` is `StatefulWidget` with `late final _controller = TextEditingController(text: widget.initialName)` and proper `dispose()` | Widget test lines 33–67 walk create → rename-dialog → edit-members-bottom-sheet → delete, each with repository-capture assertions and `find.text` / `findsOneWidget` / `findsNothing` state checks |

All three resolutions match what was claimed. The implementation is internally consistent, the authorization split (profile-keyed vs. user-keyed) is correctly applied in both the inviteability service and the mutation service, and the dialog lifecycle fix does not push business state into the widget layer.
