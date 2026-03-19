# TODO (V1): Invite Test Hardening and Stage Compatibility (Superseded)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Superseded (`Archived`)  
**Superseded on:** 2026-03-18  
**Superseded by:** `foundation_documentation/todos/active/mvp_slices/TODO-v1-invite-mvp-launch-safety.md`  
**Reason:** Previous execution stream mixed valid hardening intent with harmful patterns; replaced by clean-scope reset TODO.
**Owners:** Delphi (Flutter/Product) + Backend Team + Infra/CI Team  
**Goal:** Raise invite-flow confidence from controller/UI regression coverage to contract-accurate, stage-verified compatibility coverage across Flutter, Laravel, and web entry points.
**2026-03-18 correction:** stage-only backend `test-support` approach was rolled back; this TODO now enforces canonical-API parity across environments.

---

## References
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invite-deeplink-identity-first-delivery.md`
- `foundation_documentation/system_roadmap.md`

---

## Execution Governance

- **Primary module anchor:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module anchors:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/policies/scope_subscope_governance.md`
- **Planned promotion targets:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
  - `foundation_documentation/system_roadmap.md`
  - `foundation_documentation/submodule_flutter-app_summary.md`
  - `foundation_documentation/submodule_laravel-app_summary.md`

## Applicable Rules/Workflows (Declared Before Approval)
- `delphi-ai/main_instructions.md`
- `skills/wf-docker-todo-driven-execution-method/SKILL.md`
- `skills/rule-docker-shared-foundation-docs-sync-model-decision/SKILL.md`
- `skills/test-creation-standard/SKILL.md`
- `skills/flutter-architecture-adherence/SKILL.md`
- `skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `skills/rule-laravel-shared-foundation-docs-sync-model-decision/SKILL.md`
- `skills/wf-laravel-create-api-endpoint-method/SKILL.md`

---

## Scope Restatement
- Reclassify the current invite test landscape honestly so fake-repository UI flows are not treated as real compatibility evidence.
- Add Flutter repository/decoder contract coverage for invite preview, materialize, accept, and decline payloads, including malformed and terminal-state payloads.
- Reuse canonical invite APIs/fixtures for stage compatibility validation without adding environment-exclusive backend test endpoints.
- Create real compatibility suites against `stage`:
  - Flutter app-runtime compatibility (`integration_test` against stage backend).
  - Web/browser compatibility (Playwright against stage invite entry/auth/fallback surfaces).
- Integrate environment-specific gates so `dev`, `stage`, and `main` no longer claim the same level of invite confidence.
- Remove stale invite test semantics that conflict with the canonical identity-first/materialize-first contract.

## Out of Scope Restatement
- Real iOS device validation and Universal Link verification.
- New invite product behaviors outside the already approved identity-first/materialize-first model.
- Non-invite deep-link compatibility work except where required to preserve invite entry/auth/fallback correctness.
- Main-environment mutation-based invite testing.

---

## Definition of Done
- Invite test taxonomy is explicit and honest across Flutter/Laravel/Web.
- No fake/UI-flow test is presented as proof of real Flutter↔Laravel compatibility.
- Flutter has repository/decoder tests covering critical invite payloads and drift/error paths.
- A real compatibility suite exists for invite critical paths against the online `stage` environment.
- CI/promotion flow distinguishes `dev`, `stage`, and `main` gates for invite confidence.
- Canonical module docs and endpoint contracts are synchronized with the new testing architecture.
- Stage suites cover the correct tenant-resolution modes for each surface: host/domain for web, app/mobile resolution for Flutter runtime.
- Compatibility artifacts are recorded under `foundation_documentation/artifacts/tmp/<run-id>/...` for each required real-backend gate.

## Validation Steps
1. Run focused Laravel invite/attendance contract tests through the canonical safe runner.
2. Run focused Flutter repository/controller/widget tests for invite flows.
3. Run the new Flutter real-backend compatibility suite against stage.
4. Run the new Playwright/web compatibility suite against stage.
5. Verify no environment-exclusive backend invite test endpoints are required by the stage suite.
6. Verify the suites exercise the intended tenant-resolution mode for each surface (`host` on web, app/mobile resolution on Flutter runtime).
7. Record gate status per environment (`passed|blocked|failed`) with evidence and saved artifacts.
8. Update module docs and submodule summaries with the stabilized testing architecture.

---

## Complexity Classification + Checkpoint Policy
- **Complexity:** `big`
- **Checkpoint policy:** section-by-section
  1. TODO + decisions + module coherence
  2. Test taxonomy and coverage hardening
  3. Stage compatibility harness
  4. Real compatibility suites
  5. CI/gate integration + final validation

---

## Module Decision Baseline Snapshot

| Baseline ID | Source | Summary |
| --- | --- | --- |
| `INV-PD-05` | `modules/invite_and_social_loop_module.md` | Direct native mutations are canonical invite mutations by `invite_id`; explicit selection is mandatory when needed. |
| `INV-PD-06` | `modules/invite_and_social_loop_module.md` | Web invite entry remains narrow and code-bound; richer decisions hand off to the app. |
| `D-IF-01` | `TODO-v1-invite-deeplink-identity-first-delivery.md` | Authenticated share-code entry must materialize a canonical invite edge before any decision UI. |
| `D-IF-02` | `TODO-v1-invite-deeplink-identity-first-delivery.md` | Anonymous users cannot materialize or accept invites, and attendance remains identity-gated. |
| `FCE-INV-01` | `modules/flutter_client_experience_module.md` | Flutter invite hooks must resolve preview first, then materialize, then use canonical `accept|decline` endpoints only. |

---

## Decision Pending

None. The remaining choices in this stream are implementation details, not unresolved product or contract decisions.

---

## Decision Baseline (Frozen)

- `D-01` Flutter tests using fake repository/router/controller doubles do **not** count as real Flutter↔Laravel compatibility; they count only as UI-flow integration/regression tests.
- `D-02` Invite compatibility-critical coverage requires a real suite against the online `stage` backend.
- `D-03` `stage` may use mutation and deterministic fixtures; `main` remains read-only smoke only.
- `D-04` Flutter must add repository/decoder tests for `preview`, `materialize`, `accept`, and `decline`, including malformed payloads and terminal-state variants.
- `D-05` Legacy `acceptShareCode` semantics must be removed from invite doubles, mocks, and suite vocabulary.
- `D-06` Environment gates are split explicitly:
  - `dev`: local-safe Laravel + Flutter unit/widget/repository tests;
  - `stage`: real compatibility suites;
  - `main`: read-only smoke only.
- `D-07` Stage compatibility must not depend on environment-exclusive backend test endpoints; evidence must come from canonical invite APIs and controlled fixture data.
- `D-08` The real stage compatibility stack is split by responsibility:
  - Flutter `integration_test`/runtime compatibility against `stage`;
  - Playwright/browser coverage for stage invite entry/auth/fallback;
  - OS-level app-link/universal-link validation remains manual.
- `D-09` A required gate that cannot execute is reported as `blocked`, never `passed`.

---

## Module Coherence Gate

### Module Decision Consistency Matrix (Planned)

| Decision ID | Module Coherence | Change Intent | Evidence | Planned Handling |
| --- | --- | --- | --- | --- |
| `D-01` | `Aligned` | `Preserve` | `modules/flutter_client_experience_module.md` §2.1, §2.2; `modules/invite_and_social_loop_module.md` §2.4 | Reclassify current Flutter invite "integration" tests as UI-flow coverage only. |
| `D-02` | `Aligned` | `Preserve` | `modules/flutter_client_experience_module.md` §2.1; `endpoints_mvp_contracts.md` `/invites/share/{code}` + `/materialize` | Add a real stage suite that exercises the canonical backend contract. |
| `D-03` | `Aligned` | `Preserve` | promotion policy from current release workflow; `main` remains no-mutation | Restrict mutation-backed compatibility to `stage` only. |
| `D-04` | `Aligned` | `Preserve` | `endpoints_mvp_contracts.md` invite endpoints; `modules/flutter_client_experience_module.md` §2.1.1 | Add repository/decoder coverage on the Flutter transport boundary. |
| `D-05` | `Aligned` | `Preserve` | `TODO-v1-invite-deeplink-identity-first-delivery.md` decisions baseline `D-13`; module docs no longer expose share-code acceptance as terminal mutation | Remove stale share-code decision semantics from doubles/tests. |
| `D-06` | `Aligned` | `Preserve` | `test-creation-standard` gate split; promotion model | Wire environment-specific invite confidence gates. |
| `D-07` | `Aligned` | `Preserve` | architecture parity rule across lanes/environments | Keep stage validation on canonical backend surfaces; no stage-only invite test APIs. |
| `D-08` | `Aligned` | `Preserve` | invite web boundary remains narrow (`INV-PD-06`); app/runtime coverage belongs in Flutter | Split web/browser and app/runtime compatibility by tool and scope. |
| `D-09` | `Aligned` | `Preserve` | `test-creation-standard` execution status contract | Report blocked gates honestly in CI/reporting. |

---

## Plan Review Gate

### Issue Cards

- `IQ-01` `high`
  - Evidence: current Flutter invite `integration_test` files use fake repository/router/controller doubles rather than real HTTP/backend contracts.
  - Why now: the current label overstates confidence and can mask real Flutter↔Laravel drift.
  - Option A: keep them as-is and continue calling them compatibility coverage. Effort `none`; risk `high`; blast radius `high`; maintenance `low`.
  - Option B: relabel only. Effort `low`; risk `medium`; blast radius `medium`; maintenance `low`.
  - Option C (**Recommended**): relabel honestly and add a real stage suite. Effort `medium`; risk `low`; blast radius `high`; maintenance `medium`.

- `IQ-02` `high`
  - Evidence: CI/promotion does not currently enforce a real invite compatibility gate against an online backend.
  - Why now: a green pipeline can still ship a broken invite contract.
  - Option A: leave real compatibility as manual-only. Effort `low`; risk `high`; blast radius `high`; maintenance `medium`.
  - Option B: add local-only compatibility. Effort `medium`; risk `medium`; blast radius `medium`; maintenance `medium`.
  - Option C (**Recommended**): add a stage-backed invite compatibility gate and keep `main` read-only. Effort `medium`; risk `low`; blast radius `high`; maintenance `medium`.

- `IQ-03` `medium`
  - Evidence: Flutter invite repository/decoder paths for preview/materialize/accept/decline have minimal direct test coverage and currently tolerate silent payload loss in parts of the decoder path.
  - Why now: malformed payloads can degrade to incorrect UI outcomes without a hard failure signal.
  - Option A: rely on controller/widget tests only. Effort `none`; risk `medium`; blast radius `high`; maintenance `low`.
  - Option B: add repository tests only. Effort `medium`; risk `low-medium`; blast radius `medium`; maintenance `medium`.
  - Option C (**Recommended**): add repository/decoder coverage and harden critical-path semantics where silent degradation is unacceptable. Effort `medium`; risk `low`; blast radius `high`; maintenance `medium`.

- `IQ-04` `low`
  - Evidence: stale `acceptShareCode` semantics remain in invite doubles and test vocabulary after the product contract moved to materialize-first + canonical accept/decline.
  - Why now: stale semantics cause future test drift and confusion.
  - Option A: ignore residue. Effort `none`; risk `low-medium`; blast radius `medium`; maintenance `medium`.
  - Option B (**Recommended**): remove the residue while hardening the suite taxonomy. Effort `low`; risk `low`; blast radius `low`; maintenance `low`.
  - Option C: remove residue and add a dedicated lint/grep gate. Effort `low-medium`; risk `low`; blast radius `low`; maintenance `medium`.

### Failure Modes & Edge Cases
- Flutter repository tests pass but the real stage payload drifts in an untested optional/terminal field.
- Web preview/auth/fallback works while app-runtime materialize/accept/decline is broken, or vice versa.
- CI reports a non-executed required gate as success.
- Stage mutation assertions compare Home UI against non-Home agenda requests and create false negatives.
- The compatibility suite depends on brittle manual seed data instead of deterministic run-scoped fixtures.
- Stage execution is falsely blocked because runtime/bootstrap code misclassifies `belluga.app` as production even though the production landlord domain is `booraagora.com.br`.

### Uncertainty Register
- **Assumptions:** stage has stable tenant resolution for `guarappari` and seeded invite data is available through canonical APIs.
- **Unknowns:** final shape of the harness entrypoints/services; whether Flutter runtime compatibility should run exclusively via `flutter-tester` or through an additional device lane outside CI.
- **Confidence:** `high` on the problem framing and required gates; `medium` on the exact implementation shape until the existing stage/runtime scripts are inspected.

---

## Test Taxonomy (Target State)

| Test Class | Purpose | Counts as Real Compatibility? | Required Environment |
| --- | --- | --- | --- |
| Laravel feature/package tests | Canonical invite business and contract enforcement | `Yes` for backend contract only | local-safe + CI |
| Flutter controller/widget/UI-flow with fakes | Presentation regression and state-machine behavior | `No` | local-safe + CI |
| Flutter repository/decoder tests | Transport/contract alignment on Flutter boundary | `No` (boundary only) | local-safe + CI |
| Flutter real-backend compatibility suite | App-runtime compatibility against canonical backend | `Yes` | `stage` |
| Playwright/browser invite suite | Web preview/auth/fallback compatibility | `Yes` for browser boundary | `stage` |
| Manual OS deep-link validation | App/OS association behavior | `Manual evidence only` | Android/iOS device |

---

## Critical Paths That Must Be Proven
- [ ] ⚪ Anonymous `/invite?code=...` renders preview-first UI without mutation.
- [ ] ⚪ Anonymous materialize/accept/decline attempts are rejected by the real backend even if the client regresses.
- [ ] ⚪ Login preserves invite redirect and returns to the invite flow.
- [ ] ⚪ Signup preserves invite redirect and returns to the invite flow.
- [ ] ⚪ Authenticated share-code entry materializes before decision UI.
- [ ] ⚪ Rematerialization reuses canonical invite identity and does not duplicate invite edges.
- [ ] ⚪ `Aceitar` uses only canonical `acceptInvite(inviteId)`.
- [ ] ⚪ `Recusar` uses only canonical `declineInvite(inviteId)`.
- [ ] ⚪ Closing without decision keeps the invite pending and visible in the canonical surfaces.
- [ ] ⚪ A credited invite acceptance supersedes competing pending invites with `other_invite_credited`.
- [ ] ⚪ Direct event confirmation supersedes pending invites without crediting an inviter.
- [ ] ⚪ Invalid/expired share codes resolve to deterministic rejected/fallback outcomes across web and app entry.
- [ ] ⚪ Web/browser invite coverage proves host-based tenant resolution and payload serving on the stage domain.
- [ ] ⚪ Flutter runtime invite coverage proves the intended app/mobile tenant resolution path used by the client in stage.
- [ ] ⚪ Payload incompatibility or malformed transport data is surfaced deterministically and covered by automated tests.
- [ ] ⚪ Required invite confidence gates report honest `passed|blocked|failed` status by environment.

---

## Implementation Tasks

### A) Documentation + Taxonomy
- [ ] ⚪ Create and maintain a canonical invite test taxonomy in module docs and this TODO.
- [ ] ⚪ Record the new stage compatibility strategy in the relevant module/summary docs.
- [ ] ⚪ Reclassify current Flutter invite `integration_test` coverage according to the new taxonomy.

### B) Flutter Boundary Hardening
- [ ] ⚪ Add repository/decoder tests for invite preview/materialize/accept/decline payloads and terminal states.
- [ ] ⚪ Remove stale `acceptShareCode` semantics from doubles, mocks, and test narratives.
- [ ] ⚪ Keep/adjust controller/widget tests so they assert business-state outcomes while remaining honest about scope.

### C) Laravel Compatibility Data Contract
- [ ] ⚪ Define deterministic invite fixture strategy without introducing environment-exclusive APIs.
- [ ] ⚪ Ensure fixture bootstrap/verification uses canonical services/contracts rather than raw inserts.
- [ ] ⚪ Add cleanup/state inspection via canonical flows so compatibility suites can assert deterministic runs.
- [ ] ⚪ Ensure stage assertions distinguish web host/domain resolution from app/mobile resolution where invite flows depend on tenant context.

### D) Real Compatibility Suites
- [ ] ⚪ Create Flutter invite compatibility tests against `stage` covering the critical invite journey.
- [ ] ⚪ Cover terminal invite scenarios in real compatibility suites: invalid/expired code, rematerialize/no-duplication, credited acceptance supersession, and direct-confirmation supersession.
- [ ] ⚪ Create Playwright/browser invite stage tests covering preview/auth/fallback on the web boundary.
- [ ] ⚪ Capture explicit gate status and artifacts for each required stage suite.
- [ ] ⚪ Align Flutter integration bootstrap/runtime guards with the real landlord lane mapping (`stage = belluga.app`, `production = booraagora.com.br`) so stage compatibility can run without bypass hacks.

### E) CI / Promotion Gates
- [ ] ⚪ Wire `dev` invite gates to backend local-safe + Flutter unit/widget/repository suites.
- [ ] ⚪ Wire `stage` invite gates to the real compatibility suites.
- [ ] ⚪ Keep `main` invite validation read-only and explicitly weaker than `stage` for mutation-backed confidence.
- [ ] ⚪ Publish canonical run commands/artifact locations so local execution and pipeline execution stay equivalent.
- [ ] ⚪ Use the canonical source-owned runners for browser and Flutter compatibility execution rather than ad-hoc `web-app` or manual command variants.

---

## Decision Adherence Validation

_To be completed before delivery._

| Decision ID | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Pending` |  |  |
| `D-02` | `Pending` |  |  |
| `D-03` | `Pending` |  |  |
| `D-04` | `Pending` |  |  |
| `D-05` | `Pending` |  |  |
| `D-06` | `Pending` |  |  |
| `D-07` | `Pending` |  |  |
| `D-08` | `Pending` |  |  |
| `D-09` | `Pending` |  |  |

## Module Decision Consistency Validation

_To be completed before delivery._

| Baseline ID | Delivery Status | Evidence | Notes |
| --- | --- | --- | --- |
| `INV-PD-05` | `Pending` |  |  |
| `INV-PD-06` | `Pending` |  |  |
| `D-IF-01` | `Pending` |  |  |
| `D-IF-02` | `Pending` |  |  |
| `FCE-INV-01` | `Pending` |  |  |
