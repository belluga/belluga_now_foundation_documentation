---

## T3 Minimal Contacts/Favorites/Friends — Round 03 External Critical Review

**Reviewed:** 2026-04-28  
**Scope:** Round 02 resolution artifacts plus all changed surfaces listed in the round 03 packet  
**Role:** No-context independent auditor

---

## Verdict

**PASS — no blocking findings. Round 02 blockers are genuinely resolved.**

All four blockers (`ELEGANCE-R02-001`, `PERF-R02-001`, `TQ-R02-001`, `TQ-R02-002`) are verified closed by code inspection and consistent with the recorded test evidence. The implementation matches the resolution claims without observable regressions in scope.

---

## Blocking Findings

**None.**

Detailed verification per blocker:

**ELEGANCE-R02-001 — Account-profile recipient lifecycle**

Verified resolved across all lifecycle paths:

- `acceptForUserIdWithoutReplay()`: `applyReceiverScope()` called with profile ID for credited-winner lookup (line 267) and supersession (line 398–403). The `edgeBelongsToReceiver()` guard (line 760–771) prefers `receiver_account_profile_id` when both sides carry one.
- `supersedePendingInvitesForDirectConfirmation()`: correctly calls `recipientAccountProfileIdForUserId()` before delegating to `supersedePendingInvites()` (lines 228–231).
- `InviteShareService::findExistingInviteEdge()`: resolves recipient profile before applying `applyReceiverScope()` (lines 524–536). `createMaterializedInviteEdge()` stores `receiver_account_profile_id` on the new edge (line 566). `materializedInviteState()` uses `applyReceiverScope()` for the credited-winner existence check (line 613).
- The `receiver_user_id` field is retained as acting-user/audit compatibility only; it is never the primary scope key when a profile ID is available.

**PERF-R02-001 — Contact-import batching**

Verified resolved. `InviteIdentityGatewayAdapter::matchImportedContacts()` performs two bulk `whereIn` queries (email, phone) and then calls `contactMatchPayloadsFor()` once with all matches (lines 103–143). `InviteablePeopleService::contactMatchPayloadsFor()` collects all target user IDs upfront, loads personal profiles in a single `personalProfilesByUserId()` batch (line 249–253), loads capabilities in a single `capabilitiesByProfileType()` batch (line 254), and then iterates using pre-loaded maps. No per-match DB queries remain in the hot path.

**TQ-R02-001 — Backend test evidence**

Three purpose-built tests in `StoreReleaseSocialGraphTest`:

- `test_account_profile_recipient_acceptance_supersedes_competing_invites_by_profile_identity()`: deliberately corrupts `receiver_user_id` on the second invite to an unrelated user, then accepts the first invite as the true receiver and asserts the second invite appears in `superseded_invite_ids` with `status=superseded` and `supersession_reason=other_invite_credited`. This is an adversarial proof that scoping is by profile ID, not user ID.
- `test_account_profile_recipient_direct_confirmation_supersedes_by_profile_identity()`: same corruption technique applied to direct-confirmation supersession path.
- `test_share_materialization_uses_account_profile_recipient_identity()`: proves share materialize scopes by profile ID (credited-winner check and edge-reuse lookup), stores `receiver_account_profile_id` on the new edge, and asserts idempotency replay returns the same invite even after corrupting `receiver_user_id` on the materialized edge.

**TQ-R02-002 — Flutter group-management tests**

Both files use stateful repository doubles (real `List<InviteContactGroup>` mutated in-memory). The controller test asserts `groupsStreamValue.value` contents at every stage of the create/rename/update/delete cycle — not just call counts. The screen test asserts `find.text('Tribo')` in the widget tree after a create interaction. These are visible-state assertions, not intent-only checks.

---

## Non-blocking Debt

**NB-01 — `profileIsInviteable()` fallback query in single-recipient resolution paths**

`recipientForAccountProfileId()` (`InviteablePeopleService:336`) calls `profileIsInviteable($profile)` with no `$capabilitiesByType`, triggering a live `TenantProfileType::query()->where('type', ...)->first()` per call (lines 512–519 fallback branch). This is one query per resolution call on the non-hot-path (recipient resolution at invite-send time). `contactMatchPayloadsFor()` passes the pre-loaded map correctly so the batch import path is unaffected. Pre-existing; acceptable for T3 volume but should be addressed before high-throughput recipient-resolution phases.

**NB-02 — Potential duplicate `recipientAccountProfileIdForUserId()` call in decline path**

`declineForUserIdWithoutReplay()` fetches `$actingReceiverAccountProfileId` at the top (line 463). If the accepted/declined edge has no stored `receiver_account_profile_id`, `groupHasOtherPending()` → `receiverAccountProfileIdForEdge()` → `recipientAccountProfileIdForUserId()` fires a second gateway call for the same user (lines 745–755 → 773–786). Minor, pre-existing.

**NB-03 — Controller test fake omits `materializeShareCode` from the contract surface**

`_FakeInvitesRepository` in `contact_group_management_controller_test.dart` does not implement `materializeShareCode`, while the screen test fake does (line 116). Tests pass — consistent with a non-abstract default in the contract — but the controller fake drifts from the full contract surface. Low risk for T3; creates a consistency maintenance burden in subsequent rounds when the contract grows.

**NB-04 — Flutter screen test covers only the create widget path**

Rename, member-update, and delete interactions are tested at the controller layer only. Widget-level coverage of those paths is absent. Adequate for T3 (the controller test provides behavioral evidence), but this gap should be tracked before T4 UX hardening.

**NB-05 — `generateCode()` 5-attempt collision loop**

`InviteShareService::generateCode()` performs up to five individual existence queries before throwing (lines 340–353). Collision probability is negligible at current scale but the loop is unbounded in terms of queries. Pre-existing; below T3 threshold.

---

## Comparison Notes Against Round 02

| Round 02 Blocker | Round 03 Status | Confidence |
|---|---|---|
| `ELEGANCE-R02-001` — profile ID recipient scope incomplete | Closed | High — all lifecycle paths verified in code and test |
| `PERF-R02-001` — per-match profile/capability resolution in hot path | Closed | High — batch architecture confirmed, anti-pattern audit consistent |
| `TQ-R02-001` — no proof of profile-keyed supersession under mismatched user ID | Closed | High — adversarial `receiver_user_id` corruption technique is appropriate proof |
| `TQ-R02-002` — group-management tests were intent-only | Closed | High — stateful fakes and widget-tree assertions present |

The key verification technique — deliberately corrupting `receiver_user_id` to an unrelated user ID after storing the correct `receiver_account_profile_id`, then asserting that profile-keyed lifecycle transitions still fire — is a valid and sufficient adversarial proof for the resolution claims. It directly falsifies the prior round's concern about implicit user-ID fallback leakage.

No new blockers were introduced by the resolution. The remaining non-blocking debt items are pre-existing patterns that post-date the T3 scope boundary or are appropriate deferrals to the consolidated ADB phase.
