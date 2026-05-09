# Audit Asset Quality Comparison — Claude CLI vs Triple Audit

**Artifact type:** derived comparison, non-authoritative  
**Date:** 2026-05-06  
**Purpose:** compare saved external-audit assets for finding relevance, false-positive behavior, root-cause depth, operational reliability, and downstream usefulness.

## Scope Examined

Primary packets reviewed:

- `foundation_documentation/artifacts/claude-cli-reviews/W2A-triple-audit-vs-claude-comparison-20260429.md`
- `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-review-20260429.md`
- `foundation_documentation/artifacts/claude-cli-reviews/W2A-home-favorites-refresh-claude-final-20260429.md`
- `foundation_documentation/artifacts/claude-cli-reviews/W2A-invite-occurrence-contact-presence-claude-review-20260429.md`
- `foundation_documentation/artifacts/claude-cli-reviews/W2A-invite-share-regression-claude-review-20260429.md`
- `foundation_documentation/artifacts/T2-phone-otp-auth-contact-match-review-packet.md`
- `foundation_documentation/artifacts/t2-phone-otp-auth-contact-match-review-packet-triple-audit-20260428T160322Z/round-01/round-summary.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T2-phone-otp-auth-contact-match-cli-review.md`
- `foundation_documentation/artifacts/T3-minimal-friends-review-packet.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-01/round-summary.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-02/round-summary.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-03/round-summary.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-04/round-summary.md`
- `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-05/round-summary.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-02.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-03.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-04.md`
- `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-05.md`

## Comparison Criteria

1. **Finding relevance:** did the audit catch a real blocker or materially useful debt?
2. **False-positive behavior:** did it raise issues that later proved non-material or purely package-bounded?
3. **Root-cause depth:** did it explain why the issue exists, not only that something looks risky?
4. **Operational reliability:** did the review actually run and produce usable output?
5. **Downstream usefulness:** did the finding drive a real fix, evidence improvement, or a durable rule/TODO?

## Case 1 — W2A Home Favorites Refresh

### What Triple Audit caught

- The strongest finding was **test-quality**: the original test did not prove backend-backed read-model behavior after persistence.
- This improved the evidence floor and forced a stronger test shape.

### What Claude caught

- Claude found the strongest **runtime bug**:
  - `refreshFavoriteResumes()` lived inside the persistence rollback `try/catch`;
  - a Home refresh failure could roll back a favorite mutation that had already persisted successfully.
- Claude also separated:
  - persistence failure,
  - Home refresh failure,
  - telemetry latency coupling.

### Quality judgement

- **Most relevant blocking finding:** Claude.
- **Most relevant test-fidelity finding:** Triple audit.
- **Net result:** complementary, not duplicative.

### Takeaway

For bounded Flutter/runtime slices, Claude showed stronger sensitivity to real code-path bugs that survive package-level evidence. Triple audit strengthened the test floor first.

## Case 2 — T2 Phone OTP Auth / Contact Match

### What Triple Audit caught

- It did **not** raise a structural or performance blocker.
- It did raise a **test-quality gate**:
  - missing stronger real-backend/UI proof,
  - missing CI evidence,
  - missing explicit contact-match continuity proof.

This was directionally useful, but broad.

### What Claude caught

- No usable finding comparison was possible.
- Saved artifact shows **operational unavailability**:
  - command produced no review content before timeout.

### What the audit stack missed

The packet itself later records a real post-review closure:

- missing tenant-admin frontend consumer for `outbound_integrations`
- later fixed and documented with `TAD-14`

That means:

- triple audit did **not** catch that cross-surface completion gap in the original pass;
- Claude did not help because it never produced a usable review.

### Quality judgement

- **Triple audit:** useful but incomplete; mostly evidence-gate oriented.
- **Claude:** unavailable, therefore no quality score on findings for this case.
- **Most important missed gap:** missing tenant-admin consumer for a release-critical backend settings namespace.

### Takeaway

For cross-stack delivery slices, triple audit can still miss “backend landed but operator surface incomplete” gaps when the packet boundary is too implementation-centric. Availability failures make Claude unusable as a guaranteed backstop.

## Case 3 — T3 Minimal Friends / Favorites / Contacts

### What Triple Audit caught

Across rounds 01–05, triple audit surfaced a real chain of blockers:

1. missing dedicated contact-group management surface;
2. duplicate placeholder-row rendering in invite share;
3. unbounded/batch-unsafe backend paths;
4. identity split between `receiver_account_profile_id` and `receiver_user_id`;
5. inviteability-vs-ownership conflation in profile-keyed lifecycle;
6. missing negative authorization and widget-level evidence.

These were not cosmetic. They materially shaped the implementation.

### What Claude caught

Claude behavior was mixed:

- initial review: timed out;
- round 02: timed out;
- round 05: account-limit blocked;
- round 03 and round 04: produced substantive reviews.

When Claude did run:

- round 03 provided strong end-to-end verification of the profile-keyed recipient lifecycle and judged prior blockers resolved;
- round 04 gave a clean pass with only low-severity debt notes.

### Quality judgement

- **Triple audit** was the main engine of discovery in T3.
- **Claude** was strongest as a post-fix independent validator, not as the primary discovery mechanism.
- Claude did not surface a stronger new blocker than triple audit in T3.

### Notable nuance

Claude round 03 showed strong adversarial reasoning in its validation of the fix:

- deliberate corruption of `receiver_user_id`;
- proving lifecycle still scoped by `receiver_account_profile_id`.

That was high-quality verification, but it came after triple audit had already driven the correction path.

### Takeaway

For large multi-round backend/identity slices, triple audit outperformed Claude on **discovery continuity** and **issue sequencing**. Claude was valuable later for confidence, but too unreliable to carry the loop.

## Comparative Assessment

| Dimension | Triple Audit | Claude CLI |
| --- | --- | --- |
| Discovery of structural drift in multi-round slices | Strong | Mixed |
| Discovery of concrete runtime/code-path bug in bounded slice | Mixed | Strong |
| Test-quality scrutiny | Strong | Moderate |
| Root-cause narrative depth | Moderate-strong | Strong when it runs |
| Operational reliability | Strong | Weak-mixed |
| Usefulness as mandatory gate | Stronger | Weaker |
| Usefulness as additive independent check | Strong | Strong |

## False-Positive / Overreach Notes

### Triple Audit

- Tends to be stricter about **evidence completeness** and package-boundary cleanliness.
- This is usually productive, but it can over-index on:
  - missing UI proof,
  - deferred ADB/device proof,
  - package-bounded non-code gaps,
without necessarily finding the highest-impact runtime bug.

### Claude

- When it runs, the findings are often more **surgical** and **causal**.
- In the reviewed assets, Claude did not show a major false-positive pattern.
- Its bigger weakness was **availability**, not overreach.

## Overall Conclusion

1. **Triple audit is the stronger default audit engine** for complex TODOs with several layers of behavior, evidence, and follow-up rounds.
2. **Claude is the stronger opportunistic bug hunter** for bounded runtime slices and the stronger independent validator after a fix lands.
3. **Claude is not reliable enough to be the only external gate**:
   - timeout in T2,
   - timeout in T3 initial,
   - timeout in T3 round 02,
   - account-limit block in T3 round 05.
4. **The best observed pattern remains the combined one**:
   - triple audit first to pressure evidence, architecture, performance, and test shape;
   - Claude after that as an independent runtime/code-path pass.
5. Neither stack should be treated as complete by itself:
   - triple audit missed the W2A Home rollback bug;
   - the audit flow around T2 missed the tenant-admin consumer gap;
   - Claude could not consistently be counted on to run.

## Recommended Use Pattern

### Use triple audit as primary when:

- the TODO is cross-stack;
- multiple rounds are likely;
- the main risk is incomplete evidence, structural drift, or query/test shape.

### Use Claude as additive when:

- the slice is bounded enough for a direct code-path read;
- you want a no-context runtime-bug hunter;
- or you want a post-fix independent re-review.

### Do not rely on Claude for critical path gating when:

- the lane cannot tolerate timeout/account-limit operational failure;
- the slice needs sustained multi-round continuity.

## Practical Rule For Future Sessions

- **Primary gate:** triple audit.
- **Secondary independent gate:** Claude, when available.
- **If Claude is unavailable:** do not block the lane by default; record the operational miss and continue with the stronger deterministic/test/triple-audit evidence.
