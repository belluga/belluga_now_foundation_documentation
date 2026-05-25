---

## Invite Sent Status Contract — Round 01 Audit Assessment

### Recommended Target

**Option C** — split read models — is the correct long-term target architecture:

- `/contacts/inviteables` (paginated, enriched with `sent_invite_status` per row for composer)
- New occurrence-summary contract (exact aggregate counters + preview avatars for event detail/footer)
- `GET /invites/sent-statuses` narrowed to targeted `recipient_account_profile_ids[]` refresh and push reconciliation only

Option B alone is incomplete (has no summary answer and overloads a hot endpoint); Option A is the shortest path to green but conflates two read models.

---

### Is Current Option A Promotion-Blocking?

**Conditional.** Two separate verdicts:

| Use Case | Classification | Reasoning |
|---|---|---|
| Composer call with `recipient_account_profile_ids[]` filter | **Non-blocking** | Correct, targeted, already green. Promote as-is. |
| Event-detail/footer unfiltered summary | **BLOCKER** (conditional) | If the `summary` object derived from returned rows is surfaced to the user as authoritative occurrence-level counters (e.g. "12 accepted"), it silently truncates at 200. A user who sent 250 invites sees wrong numbers. Must be resolved or explicitly accepted with evidence before promotion. |

The performance overfetch (up to 200 rows for a footer that needs only counters + 5 avatars) is **non-blocking debt** at current scale but is a concrete argument for the Option C vNext split.

---

### What Should Be vNext

| Item | Priority |
|---|---|
| Paginated `/contacts/inviteables` enriched with per-row `sent_invite_status` (Option C leg 1) | vNext P1 — eliminates composer two-step |
| Occurrence-summary contract with exact aggregate counters (Option C leg 2) | vNext P1 — fixes truncation root cause |
| Narrow `GET /invites/sent-statuses` scope to targeted refresh only (Option C leg 3) | vNext P1 — retire it as a pseudo-list |
| Option B (status in inviteables without summary contract) | Out-of-scope — incomplete by itself |

---

### Required Tests / Evidence Before Current Promotion

| Test | Classification |
|---|---|
| Integration test: filtered `recipient_account_profile_ids[]` call returns correct `sent_invite_status` and `blocks_reinvite` per recipient across all status transitions | **BLOCKER** — must exist |
| Integration test: push `invite_accepted` triggers hydration for only the affected occurrence + recipient (no unbounded refetch) | **BLOCKER** — must exist |
| Evidence for event-detail summary behavior at >200 sent invites (either: a test proving counters are exact, OR explicit documented acceptance that summary is row-bounded/approximate at this scale) | **BLOCKER** — one or the other must be on record |
| Unit test: `blocks_reinvite` logic covers every declared status transition | **BLOCKER** — correctness gate |
| Load/query test for unfiltered call at high occurrence volume | Non-blocking debt — log and track |
| Contract test for Option C summary endpoint shape | Out-of-scope — vNext |

---

### Summary

Option A (filtered path) is **not** promotion-blocking and can ship. The unfiltered summary path **is** a blocker until either (a) a test proves counters are exact regardless of volume, or (b) the team explicitly accepts and documents that the summary is row-bounded and approximate, with a vNext tracking item for the Option C summary contract. Option B should be dropped as a standalone candidate. Option C is the declared vNext target.
