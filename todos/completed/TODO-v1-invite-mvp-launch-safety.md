# TODO (V1): Invite MVP Launch Safety (No Regression on Invitations)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.
**Status:** Completed (Delivered to Stage)
**Owners:** Platform + Flutter + Laravel
**Objective:** Establish release-grade safety for the MVP invite flow so launch does not introduce invitation regressions.

---

## Context
This TODO supersedes the contaminated execution stream from:
- `foundation_documentation/todos/completed/TODO-v1-invite-test-hardening-and-stage-compatibility-superseded.md`

Useful hardening intent is preserved; harmful patterns are explicitly banned.

---

## Scope
1. Enforce canonical request parity in web mutation assertions (UI compared only to Home-equivalent query semantics).
2. Validate critical invite paths with real confidence for MVP launch:
   - anonymous invite preview,
   - auth redirect preservation,
   - materialize-first authenticated flow,
   - canonical accept/decline behavior.

## Out of Scope
- Any stage-only or environment-exclusive backend test endpoint (`/test-support`, etc.).
- New invite product features beyond MVP contract.
- Environment divergence for test convenience.

---

## Definition of Done
- Mutation assertion uses canonical Home-equivalent agenda query semantics.
- Invite-critical flows have deterministic pass/fail evidence aligned with MVP behavior.

---

## Decision Consolidation

### Invite MVP-Specific Decisions (This TODO Only)
- `INV-01` Mutation parity for this journey must bind to the canonical Home-equivalent agenda query.
- `INV-02` MVP invite launch safety requires explicit evidence for preview → auth redirect → materialize → decision flow.

### Generic Flow Governance (Moved to Skills/Instructions)
- `skills/test-orchestration-suite/SKILL.md` (`GF-01..GF-06`)
- `skills/test-quality-audit/SKILL.md` (`GF-01..GF-05`)
- `skills/github-stage-promotion-orchestrator/SKILL.md` (promotion enforcement for flaky/canonical/shared-lane constraints)

---

## Implementation Tasks

### Invite MVP-Specific Hardening
- [x] ✅ Patch web mutation assertion to filter only canonical Home-equivalent agenda requests.
- [x] ✅ Run invite-critical compatibility checks and record evidence for MVP launch readiness.

---

## Validation Steps
1. Run `bash tools/flutter/run_web_navigation_smoke.sh readonly` with explicit stage domains.
2. Run `bash tools/flutter/run_web_navigation_smoke.sh mutation` with explicit stage domains.
3. Validate invite-critical path assertions against stage-compatible environment evidence.
4. Confirm generic flow guardrails from skills are respected during execution and promotion reporting.

---

## Notes
- Environment mismatches (`.app` vs `.space`, missing NAV vars, folder ownership) are operational signals; treat as `blocked` evidence, not product defects.
- If a run is `blocked`, resolve harness/environment first and only then interpret product assertions.
- Launch decision for MVP invite safety depends on deterministic evidence, not a green-by-retry signal.

---

## Evidence Log

### 2026-03-18 (Chunk 1)
- `readonly` web smoke against stage domains passed:
  - `NAV_DEPLOY_LANE=stage NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarapari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh readonly`
- `mutation` web smoke against stage domains passed (with `--fail-on-flaky-tests` enabled in runner):
  - `NAV_DEPLOY_LANE=stage NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarapari.belluga.space bash tools/flutter/run_web_navigation_smoke.sh mutation`
- Invite integration/device checks were `blocked` by local Android harness instability, not by invite product assertion:
  - Gradle build did not yield discoverable `.apk` for integration run.
  - `adb uninstall` failed with `DELETE_FAILED_INTERNAL_ERROR`.

### 2026-03-19 (Chunk 1 closeout)
- Promotion completed up to stage:
  - PR `#378` (`fix/mutation-canonical-home-parity -> dev`)
  - PR `#379` (`dev -> stage`)
  - stage head after promotion: `a231c40fb773e8550bb76f1a52a2082e8132182c`
- Stage post-merge pipeline succeeded (deploy + smoke):
  - `https://github.com/belluga/belluga_now_docker/actions/runs/23276715367`
- Invite-critical deterministic local checks passed (no device harness dependency):
  - `flutter test test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart`
  - `flutter test test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`
  - `flutter test test/infrastructure/repositories/invites_repository_test.dart`

### 2026-03-19 (Chunk 2 harness stabilization)
- Device runner hardening implemented in Flutter repo:
  - `tool/run_integration_test_wsl.sh` now supports:
    - current Flutter flag semantics (`--no-dds` by default; `FLUTTER_INTEGRATION_USE_DDS=true` to opt in),
    - deterministic push-disable define (`FLUTTER_INTEGRATION_DISABLE_PUSH=true` default),
    - per-command ADB timeout (`ADB_COMMAND_TIMEOUT_SECONDS`, default `8`),
    - end-to-end runner timeout (`FLUTTER_INTEGRATION_RUN_TIMEOUT_SECONDS`, default `0` disabled).
- Integration bootstrap consistency patch:
  - `integration_test/support/integration_test_bootstrap.dart` now seeds the VM golden comparator stream once per process to reduce per-file drift.
- Device evidence remains `blocked` for MVP confidence expansion beyond current deterministic suite:
  - `bash tool/run_integration_test_wsl.sh integration_test/feature_invite_auth_roundtrip_decision_ui_regression_test.dart`
  - observed unstable harness failures across retries (`VM Service timed out` and intermittent VM stream subscription failure), without invite product assertion failure evidence.
- Deterministic invite baseline remained green after harness adjustments:
  - `flutter test test/presentation/tenant/invites/screens/invite_flow_screen/controllers/invite_flow_controller_test.dart test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart test/infrastructure/repositories/invites_repository_test.dart`
