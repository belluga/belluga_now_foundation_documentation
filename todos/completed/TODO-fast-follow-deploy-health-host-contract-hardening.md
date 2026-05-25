# TODO (Fast Follow): Deploy Health Host Contract Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Production-Ready. The fail-closed `DEPLOY_HEALTH_HOST` contract was frozen under external audit, implemented in the root CI scripts, validated locally, promoted through `dev` and `stage`, and included in the successful Docker `stage -> main` production promotion.

## Title
Fast Follow: Deploy Health Host Contract Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- The canonical root promotion workflows already inject `DEPLOY_HEALTH_HOST` explicitly for:
  - stage deploy
  - stage rollback
  - main deploy
  - main rollback
- Despite that, both deploy scripts still carry a fallback branch that resolves the health-check host from `APP_URL`:
  - `.github/scripts/deploy_stage_over_ssh.sh`
  - `.github/scripts/rollback_remote.sh`
- The current deploy script goes one step farther and falls back again to `localhost` if host extraction still fails.
- A local unpublished micro-diff exists that only changes the fallback read source from root `.env` to `laravel-app/.env`, but that does **not** answer the larger contract question: whether `APP_URL` should remain a valid fallback at all.
- The current main-promotion candidate is already proven through `dev -> stage`; this follow-up exists to decide and, if approved, implement the correct host-resolution contract intentionally rather than accidentally mixing local residue into `main`.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-deploy-health-host-contract-hardening`
- **Why this is the right current slice:** this is one bounded CI/runtime contract slice with one primary outcome: define and harden the canonical health-host resolution contract for deploy/rollback probes before the next promotion cycle absorbs any implicit fallback behavior.
- **Direct-to-TODO rationale:** the problem is already concrete, bounded to two root scripts plus verification, and the primary ambiguity is architectural contract choice rather than story decomposition breadth.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic** only inside the same root CI/runtime host-resolution contract.
- If the chosen path changes the promotion boundary, the required verification surface, or introduces a second independently testable runtime behavior, the TODO must be updated before implementation continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Docker`, `CI/CD`, `Release-Safety`, `External-Audit-Required`
- **Next exact step:** none for this TODO; the contract is now present in `main`.
- **Promotion lane path:** `dev -> stage -> main`

## Promotion Evidence
- **Local branch/commit:** `fix/deploy-health-host-contract-hardening-20260521 @ 7d73813a9850ea79ba497d502465030da9014f60`
- **Promotion to `dev`:** `belluga_now_docker#735`
- **Promotion to `stage`:** `belluga_now_docker#736`
- **Stage health proof:** `belluga_now_docker` run `26255277697` completed `success` and `github_promotion_completion_guard.sh --lane stage --scenario docker-only` returned `Overall outcome: go`
- **Stage tuple:** `dev=02f9d8f08a47e407e75699e3a4714a8318982bad`, `stage=4615cbc94ca9c80a75ec11f9294e82356fe40932`
- **Promotion to `main`:** included in Docker PR `#737` (`stage -> main`, merged `2026-05-22`, merge commit `0c8e3527f420597adbc83df3bf917075e95599de`) and preserved through Docker PR `#751` (`stage -> main`, merged `2026-05-23`, merge commit `2b8d9c0832542a1ad93212022c1140769cd0e380`).
- **Main proof:** production run `26320227463` completed `success`, including preflight, production deploy, public-edge/provenance, mutation hard-block, readonly smoke, and successful-release marking.
- **Docs sync note:** promotion of this TODO document remains local-only in this cycle because the current promotion contract forbids remote docs promotion.

## Scope
- [x] Decide the canonical health-host resolution contract for deploy and rollback scripts.
- [x] Eliminate ambiguous or unsafe fallback behavior from the canonical CI path if the audit confirms it is non-canonical.
- [x] Keep deploy and rollback host-resolution behavior aligned unless an explicitly approved divergence is required.
- [x] Add deterministic verification so the chosen contract cannot silently regress.
- [x] Record whether the local unpublished `.env -> laravel-app/.env` micro-diff should be discarded, absorbed, or superseded by the chosen contract.

## Out of Scope
- [ ] Reworking unrelated deploy/rollback logic, SSH transport, web-app pinning, or rollback proof semantics.
- [ ] Changing stage/main navigation target resolution itself.
- [ ] Broad refactors of `.github/workflows/orchestration-ci-cd.yml` outside the exact host-resolution contract required by this slice.

## Definition of Done
- [x] The canonical deploy/rollback health-host contract is frozen with explicit rationale.
- [x] The chosen contract is implemented consistently across the touched scripts.
- [x] Unsafe implicit fallback behavior is either removed or explicitly narrowed to an approved manual-only path.
- [x] Deterministic local verification proves the chosen contract and guards regression.
- [x] The local unpublished micro-diff residue is resolved intentionally rather than left as checkout drift.

## Validation Steps
- [x] Root CI deterministic guard passes.
- [x] Shell syntax passes for touched scripts.
- [x] A focused local contract proof demonstrates the chosen fallback/fail-closed behavior.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-devops`
- **Active technical scope:** `docker`
- **Expected supporting profiles:** `assurance-tester-quality`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-devops` | `assurance-tester-quality` | Independent TODO audit and release-evidence challenge on the contract options before implementation. | `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-deploy-health-host-contract-hardening.md`, audit packet, root deploy/rollback scripts | `completed` via triple audit rounds `01-04` + Claude CLI final pass |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the code delta should stay small, but the slice changes protected deployment semantics and therefore needs an explicit contract decision plus audit-backed verification.

## Canonical Module Anchors (Required Before `APROVADO`)
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary canonical anchors:**
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-stage-proof-and-promotion-lane-readiness.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/project_constitution.md` if this slice establishes a durable root CI/runtime contract.
  - `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-stage-proof-and-promotion-lane-readiness.md` only for traceability if the decision materially affects later `main` promotion handling.
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/system_architecture_principles.md` section covering explicit runtime contract / no-hardcoded fallback posture.

## Decisions (Resolved Through TODO Audit)
- [x] `D-01` The canonical CI-owned deploy/rollback path must require `DEPLOY_HEALTH_HOST` and fail closed when it is missing. `APP_URL` and `localhost` are not valid implicit fallbacks in the protected promotion path. Missing host must surface an explicit operator-facing instruction to define the canonical lane domain/URL (`stage`/`main` landlord target) instead of guessing.
- [x] `D-02` Deploy and rollback must share the **exact same** protected-path host-resolution contract; parity is mandatory.
- [x] `D-03` The current local unpublished `.env -> laravel-app/.env` residue is **not** the architectural answer. It must be discarded or superseded by the strict fail-closed implementation instead of being promoted on its own.
- [x] `D-04` If a future operator-only escape hatch is needed, it must be introduced later as a separate explicit manual contract with its own TODO, flag, and verification; it is not part of this protected-path slice.

## Module Decision Baseline Snapshot (Required Before `APROVADO`)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `system_architecture_principles.md §1.5` | Hardcoded fallbacks are not allowed; when the authoritative runtime source is unavailable, the system should surface an explicit error. | `Preserve` | `foundation_documentation/modules/system_architecture_principles.md:28` |
| `project_constitution.md §4` | Root orchestration owns Docker runtime topology and cross-repo promotion coordination. | `Preserve` | `foundation_documentation/project_constitution.md:72` |
| `TODO-fast-follow-stage-proof-and-promotion-lane-readiness.md` latest stage proof | The current `dev -> stage` candidate is already green; no new unproven root CI delta should be silently mixed into `main`. | `Preserve` | `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-stage-proof-and-promotion-lane-readiness.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Protected CI deploy/rollback path requires `DEPLOY_HEALTH_HOST`; missing host is a hard error.
- [x] `D-02` No implicit fallback to `APP_URL`.
- [x] `D-03` No implicit fallback to `localhost`.
- [x] `D-04` Deploy and rollback share identical protected-path behavior.
- [x] `D-05` This TODO does **not** clear a `stage -> main` promotion by itself; it defines and implements the contract, then proves it locally and, only if later requested, through the lane.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The canonical workflow does not rely on `APP_URL` fallback because `DEPLOY_HEALTH_HOST` is injected at all four stage/main deploy/rollback call sites. | `.github/workflows/orchestration-ci-cd.yml` stage/main deploy/rollback env blocks | The fallback is still part of a real CI row and cannot be treated as dormant. | `High` | `Keep as Assumption` |
| `A-02` | Falling back to `APP_URL`, and especially to `localhost` in deploy, is structurally weaker than fail-closed behavior for a protected promotion lane. | Current script logic plus the explicit-runtime-contract baseline in module principles | A supported operator/manual path may still require a non-fail-closed alternative. | `Medium` | `Promote to Decision` |
| `A-03` | The existing local residue (`.env -> laravel-app/.env`) is a consistency fix, but not necessarily the correct long-term contract. | Local diff and current script helpers already reading `laravel-app/.env` elsewhere | If the contract intentionally keeps `APP_URL` fallback, the consistency fix may still be the correct immediate implementation. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `.github/scripts/deploy_stage_over_ssh.sh`
- `.github/scripts/rollback_remote.sh`
- `.github/scripts/verify_environment_ci.sh`
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-deploy-health-host-contract-hardening.md`
- bounded audit packet under `foundation_documentation/artifacts/tmp/`

### Ordered Steps
1. Freeze the bounded audit packet with the current facts and decision options.
2. Run the TODO audit lane to resolve `D-01` and `D-02`.
3. Update the TODO with the frozen decision baseline and explicit acceptance cases.
4. Request explicit `APROVADO`.
5. Implement only the approved protected-path contract in both scripts.
6. Add/update deterministic verification for the chosen contract.
7. Run the local CI-equivalent matrix and completion guard before any delivery-stage change.

### Test Strategy
- **Strategy:** `test-after` for script contract + deterministic guard augmentation
- **Why:** this slice is shell/workflow contract hardening, so the first gate is architectural decision freeze plus deterministic verification rather than application TDD.

### Acceptance Cases (Frozen Before `APROVADO`)
- `AC-01` `deploy_stage_over_ssh.sh` exits non-zero with a descriptive error when `DEPLOY_HEALTH_HOST` is unset in the protected CI path, before any SSH attempt. The error must explicitly instruct the operator/workflow to define the canonical lane domain/URL for the current lane instead of relying on fallback.
- `AC-02` `rollback_over_ssh.sh` / `rollback_remote.sh` exit non-zero with a descriptive error when `DEPLOY_HEALTH_HOST` is unset in the protected CI path, before any rollback runtime action. The error must explicitly instruct the operator/workflow to define the canonical lane domain/URL for the current lane instead of relying on fallback.
- `AC-03` Protected-path code no longer derives health host from `APP_URL`.
- `AC-04` Protected-path code no longer derives health host from `localhost`.
- `AC-05` Deploy and rollback enforce the same contract and error semantics.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Root deploy/rollback health-host resolution contract | CI/runtime only; not a user-visible app flow | `n/a` | `n/a` | `no` | `no` | deterministic shell/guard proof + targeted local script contract proof | No app/web/admin runtime flow is directly changed by this slice. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / root deterministic CI guard` | The slice changes protected deploy/rollback script semantics. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh` | Confirmed the new fail-closed contract and fallback bans are enforced by the root guard. |
| `belluga_now_docker / shell syntax` | Touched scripts are shell entrypoints in protected CI. | `bash -n .github/scripts/deploy_stage_over_ssh.sh .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash -n .github/scripts/deploy_stage_over_ssh.sh .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh` | Minimum syntax gate passed on all touched shell entrypoints plus the deterministic verifier. |
| `belluga_now_docker / focused host-contract proof` | The fix changes fallback/fail-closed behavior and needs direct proof. | `custom local shell proof: missing or malformed DEPLOY_HEALTH_HOST must fail before SSH/runtime in deploy and rollback` | `Local-Implemented` | `passed` | `deploy_stage_over_ssh.sh` negative proof (missing + invalid host), `rollback_over_ssh.sh` negative proof (missing + invalid host), and direct `rollback_remote.sh` negative proof (unset + blank + invalid host) | Proved hard fail before SSH/scp/runtime action and explicit operator-facing lane-domain / invalid-host error text with no APP_URL or localhost fallback. |

### Frontend / Consumer Matrix (Required Before `APROVADO`)
| Producer Surface | Expected Consumer | Visible Route / Action | Planned Evidence | Waiver |
| --- | --- | --- | --- | --- |
| `deploy/rollback health-host resolution contract` | `internal-only` | `n/a` | root deterministic guard + local script contract proof | Frontend consumer not applicable. |

## Plan Review Gate
- Architecture review is required because the remaining ambiguity is contract-level, not implementation-local.
- Triple audit is required before `APROVADO` to resolve `D-01`/`D-02`.

## Audit Trigger Matrix
| Audit Surface | Trigger | Status |
| --- | --- | --- |
| `independent critique` | protected deploy/rollback contract change | `required` |
| `triple_review` | user explicitly requested TODO-driven audits to define the best plan | `required` |
| `test-quality audit` | contract proof path must be explicit before delivery claims | `required` |

## Audit Outcome Snapshot
- Triple audit session: `foundation_documentation/artifacts/tmp/deploy-health-host-contract-audit-20260521/package-triple-audit-20260521T205853Z/session.json`
- Triple audit round `01` consensus:
  - protected CI path should be `fail-closed`;
  - deploy/rollback parity is mandatory;
  - the local unpublished fallback-source residue must not be promoted as the answer.
- Claude CLI supplemental review agreed that:
  - this is **not** a blocker for promoting the already stage-proven candidate unchanged;
  - but this follow-up should implement the fail-closed contract as a separate small slice rather than preserving fallback.
- Triple audit rounds `02` and `03` surfaced two real implementation gaps (`ELEG-R02-001`, `perf-ops-003`, `perf-ops-004`); all were fixed in-code and closed with recorded resolutions.
- Triple audit round `04` closed with all three lanes `clean` and zero findings.
- Claude CLI final review on the round `04` package returned:
  - `blocker_for_stage: none`
  - `blocker_for_main: none`
  - `protected_path_fallback_assessment: fully hardened`

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | Scope | Decide the canonical health-host resolution contract for deploy and rollback scripts. | audit + docs | `D-01..D-04` in this TODO, triple audit rounds `01-04`, Claude CLI final pass | local audit package | passed | The frozen contract is explicit: required `DEPLOY_HEALTH_HOST`, no implicit fallback, parity mandatory. |
| `SCOPE-02` | Scope | Eliminate ambiguous or unsafe fallback behavior from the canonical CI path if the audit confirms it is non-canonical. | code + deterministic guard | `.github/scripts/deploy_stage_over_ssh.sh`, `.github/scripts/rollback_over_ssh.sh`, `.github/scripts/rollback_remote.sh`, `bash .github/scripts/verify_environment_ci.sh` | root CI scripts | passed | Protected-path `APP_URL` fallback and deploy `localhost` fallback were removed; the verifier now blocks their reintroduction. |
| `SCOPE-03` | Scope | Keep deploy and rollback host-resolution behavior aligned unless an explicitly approved divergence is required. | code + negative proof | deploy wrapper negative proof (missing + invalid host), rollback wrapper negative proof (missing + invalid host), rollback remote negative proof (unset + blank + invalid host) | local shell proofs | passed | All three entrypoints now fail closed on missing/malformed host input before SSH/runtime work. |
| `SCOPE-04` | Scope | Add deterministic verification so the chosen contract cannot silently regress. | deterministic guard | `bash .github/scripts/verify_environment_ci.sh` | local deterministic guard | passed | Verifier now checks no-fallback messaging, no protected-path `APP_URL` fallback, no deploy `localhost` fallback, no rollback generic env gate, and explicit invalid-host rejection. |
| `SCOPE-05` | Scope | Record whether the local unpublished `.env -> laravel-app/.env` micro-diff should be discarded, absorbed, or superseded by the chosen contract. | docs | `D-03`, audit package `package.md`, audit resolutions | local docs/audit package | passed | The residue is explicitly superseded by the fail-closed contract and not promoted on its own. |
| `DOD-01` | Definition of Done | The canonical deploy/rollback health-host contract is frozen with explicit rationale. | docs + audit | `D-01..D-05`, round `01` consensus, round `04` clean closure | local TODO + audit package | passed | The contract is frozen and externally audited. |
| `DOD-02` | Definition of Done | The chosen contract is implemented consistently across the touched scripts. | code | `.github/scripts/deploy_stage_over_ssh.sh`, `.github/scripts/rollback_over_ssh.sh`, `.github/scripts/rollback_remote.sh` | root CI scripts | passed | Missing and malformed host validation is aligned across deploy, rollback wrapper, and rollback remote. |
| `DOD-03` | Definition of Done | Unsafe implicit fallback behavior is either removed or explicitly narrowed to an approved manual-only path. | code + audit | code diff above + Claude final `protected_path_fallback_assessment: fully hardened` | root CI scripts | passed | No implicit fallback remains in the protected path; no manual escape hatch was introduced in this slice. |
| `DOD-04` | Definition of Done | Deterministic local verification proves the chosen contract and guards regression. | ci-equivalent | `bash -n ...`, `bash .github/scripts/verify_environment_ci.sh`, wrapper/remote negative proofs | local shell proofs | passed | Syntax, deterministic guard, and focused fail-closed proofs all passed. |
| `DOD-05` | Definition of Done | The local unpublished micro-diff residue is resolved intentionally rather than left as checkout drift. | docs + code | `D-03`, package `package.md`, absence of `.env -> laravel-app/.env` fallback promotion | local docs + root diff | passed | The old residue was replaced by a broader explicit contract, not carried as accidental dirt. |
| `VAL-01` | Validation Steps | Root CI deterministic guard passes. | deterministic guard | `bash .github/scripts/verify_environment_ci.sh` | local deterministic guard | passed | Returned `OK: CI environment invariants validated.` after the final parity fixes. |
| `VAL-02` | Validation Steps | Shell syntax passes for touched scripts. | syntax | `bash -n .github/scripts/deploy_stage_over_ssh.sh .github/scripts/rollback_over_ssh.sh .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh` | local shell | passed | All touched shell entrypoints parse cleanly. |
| `VAL-03` | Validation Steps | A focused local contract proof demonstrates the chosen fallback/fail-closed behavior. | negative proof | deploy wrapper negative proof (missing + invalid host), rollback wrapper negative proof (missing + invalid host), rollback remote negative proof (unset + blank + invalid host) | local shell proofs | passed | Every acceptance path now exits non-zero with the explicit contract message before any SSH/SCP/runtime action. |
