I now have enough evidence to produce the audit. Let me synthesize.

---

## External Critical Audit — Belluga Store Release Final Runtime Gate

**Scope reviewed:** T2 (Phone OTP Auth + Contact Match), T3 (Minimal Friends + Favorites MVP), T4 (Funnel Metrics Validation), ADB device runtime evidence, triple-audit resolution records.
**Audit mode:** read-only. No files modified.
**Evidence access:** all referenced files readable and inspected directly.

---

### Summary

The local implementation evidence is honestly represented and internally consistent. All three ADB log files are genuine (real timestamps, real GPU/JNI noise, actual test pass lines, multiple retry artifacts visible from the WSL/ADB instability). The triple audit resolutions track substantive iterations — T3's five-round cycle closed a real architectural blocker (owner/eligibility split). No fabrication or evidence manipulation was detected.

Four concerns are elevated below. Three are **blocking for production-ready** but correctly classified as deferred per orchestration. One is a **silent gap** not previously named in any triple-audit finding.

---

### Blocking Findings

#### BLK-01 — T2 OTP Provider Status is Unknown and Release-Critical

The TODO explicitly lists `WhatsApp/SMS OTP delivery provider` with status `unknown`. The open external readiness item is: *"Confirm which provider path is launch-authoritative for WhatsApp OTP, and record the explicit SMS fallback when provider approval/templates are not ready."* This has no owner, no date, and no fallback policy frozen. Without a working provider, no end user can receive an OTP on a real device at launch. The backend webhook dispatcher is correctly provider-agnostic, but that just means this gap is invisible to all automated tests — including the ADB test.

**Classification: Blocking for production-ready.** Currently acknowledged in the TODO but not yet resolved. Not a local-gate blocker (the orchestration deferred it), but it cannot go to `Production-Ready` without resolution.

#### BLK-02 — T2 ADB Test Uses a Fake Auth Repo; No Real OTP Backend Round-Trip on Device

The auth-login ADB test (`feature_auth_login_navigates_to_intended_route_test.dart`) uses `_FakePhoneOtpAuthRepository`, which returns a hardcoded challenge and accepts code `123456`. It correctly proves that:
- the phone field appears (`loginPhoneField`);
- the password field is absent (`loginPasswordField → findsNothing`);
- OTP step appears after phone submission;
- redirect to `/agenda` succeeds.

It does **not** test a live `POST /api/v1/auth/otp/challenge` or `POST /api/v1/auth/otp/verify` round-trip on device. The test-run-progress note acknowledges this: *"T2 phone OTP has no source-owned integration_test file for live provider delivery smoke."*

**Classification: Blocking for production-ready.** The UI/redirect path is confirmed. The real backend OTP contract is not exercised on device. This is an explicit gap, but the orchestration accepted it. A live OTP smoke against the staging backend (even without real WhatsApp delivery, using a test phone + backend inspection) remains open before `Production-Ready` is honest.

#### BLK-03 — T4 Telemetry Sink/Query Readback (DEP-04) Has No Owner or Date

`DEP-04` is waived for the local gate but required before production. The completion evidence matrix lists it as `waived` with the note *"external sink/query readback remains required before Production-Ready."* No TODO names who queries the sink, when, with what tool, or what acceptable query results look like. The KPI readback interpretation section describes *how* edges could be computed but does not prove the sink is queryable.

**Classification: Blocking for production-ready.** The local waiver is correctly structured. The gap is: no explicit owner, verification method, or deadline is named anywhere in the three TODOs or review packets.

---

### Silent Gap (Not Previously Named by Triple Audits)

#### GAP-01 — T2 Validation Step Unchecked; Scope of Email/Password Quarantine Unverified

The T2 TODO `Validation Steps` includes an unchecked item:

```
- [ ] Legacy Belluga tenant-public email/password routes/UI/tests are either removed or explicitly quarantined from store-release behavior.
```

This was never resolved in the triple audit (the R01 resolution matrix does not mention it). The ADB test does confirm the Flutter UI has no password field. However, the Laravel backend email/password registration and login routes are not confirmed to be route-guarded or removed for the Belluga tenant release. The packet lists them as "removed or quarantined" without naming which routes and whether they are blocked at the tenant middleware level or only hidden in the UI.

**Classification: Should be explicitly resolved before promotion to `dev`.** This is the only unchecked Validation Step in T2. The triple-audit did not name it as a finding.

---

### Accepted Debt

The following are correctly classified as non-blocking by the orchestration; I confirm that classification.

| ID | Item | Where Recorded |
|---|---|---|
| AD-01 | ADB/device contact-permission smoke for T3 | T3 TODO deferred status; R05 resolution; ADB progress notes |
| AD-02 | Web runtime/Playwright proof for `web_invite_landing_opened` | T4 packet; T4 TODO VAL-03 waiver |
| AD-03 | CI/promotion evidence for all three TODOs | All three TODO promotion evidence tables (all pending) |
| AD-04 | Behavior-specific fail-first capture in T2 | T2 triple audit TQA-004 accepted-debt |
| AD-05 | T2 provider-drift guardrail (Flutter-side drift from backend settings) | T2 triple audit ELEGANCE-002 |
| AD-06 | Claude CLI unavailability (T2, T3 R05, T4 R01) | CLI review artifacts; all three note it was not substantive |
| AD-07 | T3 DoD checkboxes unchecked in the TODO file | Local-Implemented status is canonical; DoD items are aspirational targets not yet promoted to `Production-Ready` |

AD-07 warrants a note: the T3 TODO has 15+ unchecked `- [ ]` items in both Scope and Acceptance Criteria sections even though delivery status is `Local-Implemented`. This is by design in this project's governance model (checkboxes are not ticked at `Local-Implemented`; they require promotion evidence). However it could mislead a reader who expects checked boxes to reflect actual code delivery. The triple audits did not probe this.

---

### Triple-Audit vs. This Audit — Relevance Comparison

| Area | Triple Audit Classification | This Audit's View |
|---|---|---|
| T2 device/UI proof as debt | Correctly classified `accepted-debt` | Confirmed, with added nuance: fake-repo scope should be explicit in the next release gate summary |
| T2 challenge index evidence | `resolved` (packet updated) | Confirmed correct |
| T2 contact-match test evidence | `resolved` (TQA-003) | Confirmed correct; test exists and is named |
| T2 unchecked email/password quarantine step | **Not named by any triple-audit round** | New finding (GAP-01 above) |
| T3 owner/eligibility split (R04 blocker) | `resolved` with full code changes | Confirmed; this was a real architectural fix, not a paperwork resolution |
| T3 R05 zero findings | Correctly merged as `resolved` after non-material path conflict | Confirmed |
| T4 sink/query as accepted debt | Correctly waived with `DEP-04` | Confirmed, but DEP-04 lacks an owner — added BLK-03 |
| T4 pre-auth telemetry actor model | No finding across all three lanes | Confirmed low-risk: `TelemetryEmitter` filters null/empty metadata; envelope semantics are tested |
| ADB evidence authenticity | Not in scope of triple audit | Verified genuine by this audit (real log content, retry artifacts, non-zero line counts) |

---

### Required User Decisions

1. **OTP provider selection** (BLK-01): Name the launch-authoritative WhatsApp OTP provider, whether templates are approved, and the SMS fallback trigger policy before promoting T2 to `Production-Ready`. This does not block `dev` promotion but it blocks launch.

2. **T2 legacy email/password quarantine** (GAP-01): Explicitly close the unchecked validation step. State whether legacy Laravel routes are tenant-middleware-blocked, admin-only, or still accessible via direct API call. This is the only unchecked validation step in T2 and is not tracked by the triple audit.

3. **T4 DEP-04 ownership** (BLK-03): Assign an owner and a method for external telemetry sink/query readback. The current waivers are structurally correct but leave no one accountable for completing this before `Production-Ready`.

4. **Promotion sequencing**: All three TODOs are `Local-Implemented` with no PRs merged to `dev`. The PRs must be opened and merged before any TODO can advance. No blocker was found preventing this, but it has not started.

5. **T3 DoD checkbox policy**: Confirm whether the unchecked DoD items represent pending work (requires re-evaluation of T3 status) or are intentionally deferred to `Production-Ready`. The current governance reading is the latter; make this explicit so future reviewers don't confuse status.

6. **Real OTP device smoke scope** (BLK-02): Before `Production-Ready`, decide whether the live OTP round-trip will be a staging-backend test (no real WhatsApp, backend inspection of challenge code), a provider-smoke on a real number, or accepted as production-deferred risk. Document the decision in the T2 TODO.
