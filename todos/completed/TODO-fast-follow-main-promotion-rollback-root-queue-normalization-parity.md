# TODO (Fast Follow): Restore Rollback Root Queue Normalization Parity For Mongo Lanes Before Docker Main Promotion

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Production-Ready. Code + deterministic verification are complete, the structure-only runtime waiver is recorded, the completion guard passed, and the fix was carried through the Docker lane to `main`.

## Title
Fast Follow: Restore Rollback Root Queue Normalization Parity For Mongo Lanes Before Docker Main Promotion

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Docker PR `belluga/belluga_now_docker#737` is not currently the active source leg because Flutter/Laravel must reach `main` first.
- Even so, external blocker anticipation reviewed the existing Docker PR comments to avoid merge-late surprises when the root leg returns to the queue.
- The bounded audit confirmed a real rollback parity regression in [.github/scripts/rollback_remote.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/rollback_remote.sh:244):
  - `normalize_queue_env_for_mongo()` now rewrites only `mongodb*`, `landlord`, or `tenant`
  - it no longer normalizes root `.env` values `database` or empty
  - later Laravel env normalization still rewrites empty/database to `mongodb` for Mongo lanes
- Result: rollback can restore a Mongo deployment with an invalid root queue driver, breaking rollback parity and potentially queue processing after recovery.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-main-promotion-rollback-root-queue-normalization-parity`
- **Why this is the right current slice:** one bounded Docker rollback-contract blocker with one concrete parity defect to restore before the root leg can safely advance to `main`.
- **Direct-to-TODO rationale:** the defect is already concrete, bounded to rollback normalization semantics, and externally audited.

## Contract Boundary
- This TODO covers only root rollback queue normalization parity for Mongo lanes.
- This TODO includes:
  - restoring root `.env` normalization behavior for `database`/empty queue values on Mongo lanes
  - proving rollback leaves root and Laravel queue settings aligned
- This TODO does **not** include unrelated rollback/refactor work or the separate policy-test timeout debt item.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Docker`, `Main-Promotion-Blocker`, `Rollback-Parity`, `External-Audit-Confirmed`
- **Next exact step:** none for this TODO; the rollback parity blocker is closed in `main`.

## Scope
- [x] Restore Mongo-lane root `.env` rollback normalization parity for `database` and empty queue values.
- [x] Add regression evidence that rollback leaves root and Laravel queue settings aligned on Mongo lanes.
- [x] Re-run the relevant root CI-equivalent verification before this slice can claim `Local-Implemented`.

## Out of Scope
- [ ] The separate CI policy-test timeout debt item from PR `#737`.
- [ ] Unrelated deploy/rollback refactors.
- [ ] Any `main` promotion retry before the root Docker leg is back in canonical order.

## Definition of Done
- [x] Root `.env` rollback normalization for Mongo lanes no longer leaves `QUEUE_CONNECTION=database` or empty when rollback completes.
- [x] Root and Laravel rollback normalization semantics are aligned for the audited Mongo-lane cases.
- [x] Regression evidence proves the parity contract named in this TODO.

## Validation Steps
- [x] Add/adjust focused regression proof for root queue normalization on Mongo lanes.
- [x] Run the relevant root CI-equivalent verification for the touched rollback surface.
- [x] Capture blocker-closure evidence against the same rollback parity defect identified in PR `#737`.

## Execution Lane Tracking
- **Local implementation branches:** `belluga_now_docker:<pending>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `stage`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| rollback root queue normalization parity | `fix/main-promotion-rollback-root-queue-normalization-parity-20260521 @ 02c214a75cffc749f89fbbc2b968c8b7d03dfd17` | `belluga_now_docker#738` | `belluga_now_docker#739` | `belluga_now_docker#737`, preserved through `#751` | `Production-Ready`; production run `26320227463` completed green |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** one bounded rollback normalization contract with targeted verification.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary canonical anchors:**
  - `foundation_documentation/project_constitution.md`
  - `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md`

## Decisions (Resolved Through Audit)
- [x] `D-01` Rollback parity for Mongo lanes is part of the release-safety contract and is blocker-class when it can restore an invalid root queue driver.
- [x] `D-02` This blocker belongs to the Docker source lane and must be replayed through `dev -> stage -> main` before the root leg can safely close.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The correct rollback contract is parity with the deploy-side root normalization semantics for Mongo lanes. | Audit packet compared rollback root behavior against deploy and Laravel normalization paths. | The rollback contract would need an explicitly different rule set and broader operator review. | `High` | `Keep as Assumption` |
| `A-02` | A focused deterministic script proof is enough to close this blocker locally. | The bug is pure shell/env normalization logic and already isolated to `rollback_remote.sh`. | If live rollback behavior diverges from the deterministic proof, the fix boundary would need to widen to SSH/runtime evidence. | `Medium` | `Keep as Assumption` |
| `A-03` | Extending `verify_environment_ci.sh` with an item-specific parity proof is the right CI-equivalent surface for this slice. | The existing root CI-equivalent already owns deterministic shell invariants for deploy/rollback scripts. | A dedicated additional CI job/script would need to be introduced and mirrored locally. | `High` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces
- `.github/scripts/rollback_remote.sh`
- `.github/scripts/prove_rollback_queue_parity.sh`
- `.github/scripts/verify_environment_ci.sh`
- `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-rollback-root-queue-normalization-parity.md`

### Ordered Steps
1. Freeze the parity contract in this TODO and keep the scope limited to Mongo-lane root queue normalization during rollback.
2. Restore root normalization semantics so rollback cannot leave `QUEUE_CONNECTION` as empty/`database` on Mongo lanes when the queue path is effectively Mongo-backed.
3. Add a focused deterministic parity proof to the root CI-equivalent guard.
4. Run the root CI-equivalent matrix and reconcile evidence back into this TODO.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the defect is a shell contract regression already isolated by audit; the deterministic proof is the authoritative regression lane for this slice.

### Acceptance Cases
- `AC-01` Rollback root normalization rewrites empty `QUEUE_CONNECTION` to `mongodb` when the root lane is Mongo-backed.
- `AC-02` Rollback root normalization rewrites `QUEUE_CONNECTION=database` to `mongodb` when the root lane is Mongo-backed and the queue DB path is empty or Mongo-backed.
- `AC-03` Root rollback normalization no longer diverges from the Laravel-side Mongo queue normalization semantics for the audited cases.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Root rollback queue normalization for Mongo lanes | Release-safety/runtime contract; bad rollback can leave queues misconfigured after recovery. | `n/a` | `deterministic-shell` | `no` | `no` | Focused shell parity proof invoked from `verify_environment_ci.sh`. | This slice changes runtime rollback contract, not a user-facing app flow. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / shell syntax` | Touched surfaces are protected rollback/verifier shell entrypoints. | `bash -n .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh .github/scripts/prove_rollback_queue_parity.sh` | `Local-Implemented` | `passed` | `bash -n .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh .github/scripts/prove_rollback_queue_parity.sh` | Minimum parse gate passed for the rollback script, deterministic verifier, and parity proof helper. |
| `belluga_now_docker / focused rollback parity proof` | The fix must prove root/Laravel alignment for the audited Mongo-lane cases. | `bash .github/scripts/prove_rollback_queue_parity.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/prove_rollback_queue_parity.sh` | The proof covers empty and `database` queue cases and asserts both root and Laravel end on `mongodb`. |
| `belluga_now_docker / root deterministic CI guard` | The fix must become part of the canonical root CI-equivalent. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `passed` | `bash .github/scripts/verify_environment_ci.sh` | The canonical root CI-equivalent now runs the parity proof and returned `OK: CI environment invariants validated.` |

### Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Visible Route / Action | Planned Evidence | Waiver |
| --- | --- | --- | --- | --- |
| rollback root queue normalization contract | `internal-only` | `n/a` | deterministic parity proof in `verify_environment_ci.sh` | Frontend consumer not applicable. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-EXACT-01` | Scope | Restore Mongo-lane root `.env` rollback normalization parity for `database` and empty queue values. | code + deterministic proof | `.github/scripts/rollback_remote.sh`; `bash .github/scripts/prove_rollback_queue_parity.sh` | local root scripts + shell proof | passed | The rollback script now normalizes both audited cases and the proof asserts root `QUEUE_CONNECTION=mongodb` in each one. |
| `SCOPE-EXACT-02` | Scope | Add regression evidence that rollback leaves root and Laravel queue settings aligned on Mongo lanes. | deterministic proof | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | The helper asserts both root and `laravel-app/.env` end on `QUEUE_CONNECTION=mongodb` for the audited Mongo-lane cases. |
| `SCOPE-EXACT-03` | Scope | Re-run the relevant root CI-equivalent verification before this slice can claim `Local-Implemented`. | ci-equivalent | `bash -n .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh .github/scripts/prove_rollback_queue_parity.sh`; `bash .github/scripts/verify_environment_ci.sh` | local root shell + CI-equivalent | passed | Syntax and the canonical root CI-equivalent both passed. |
| `SCOPE-01` | Scope | Restore root Mongo-lane rollback normalization for empty and `database` queue values. | code | `.github/scripts/rollback_remote.sh` | local root scripts | passed | `rollback_remote.sh` now reads root `DB_CONNECTION`/`DB_QUEUE_CONNECTION` and normalizes empty or unsafe `database` queue values back to `mongodb`. |
| `SCOPE-02` | Scope | Add regression evidence that root and Laravel queue settings stay aligned for the audited cases. | deterministic proof | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | The helper proves parity for both the empty queue case and the `QUEUE_CONNECTION=database` with Mongo-safe queue DB case. |
| `DOD-EXACT-01` | Definition of Done | Root `.env` rollback normalization for Mongo lanes no longer leaves `QUEUE_CONNECTION=database` or empty when rollback completes. | deterministic proof + approved structure-only waiver | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | The proof explicitly covers both empty and `database` root queue cases and ends with `mongodb`. Approved structure-only waiver: no device/browser navigation test applies because this is rollback shell/env normalization; the deterministic integration-style shell proof is the runtime contract evidence. |
| `DOD-EXACT-02` | Definition of Done | Root and Laravel rollback normalization semantics are aligned for the audited Mongo-lane cases. | deterministic proof | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | Both files end on `QUEUE_CONNECTION=mongodb` for the audited cases. |
| `DOD-EXACT-03` | Definition of Done | Regression evidence proves the parity contract named in this TODO. | deterministic proof + ci-equivalent | `bash .github/scripts/prove_rollback_queue_parity.sh`; `bash .github/scripts/verify_environment_ci.sh` | local shell proof + root CI-equivalent | passed | The dedicated proof is wired into the canonical verifier and both passed. |
| `DOD-01` | Definition of Done | Root rollback no longer leaves `QUEUE_CONNECTION=database` or empty on the audited Mongo lanes. | deterministic proof + approved structure-only waiver | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | Root `.env` ended as `mongodb` in both audited cases. Approved structure-only waiver: no device/browser navigation test applies because this is rollback shell/env normalization; deterministic integration-style shell proof is the runtime contract evidence. |
| `DOD-02` | Definition of Done | Root and Laravel rollback normalization semantics are aligned for the audited Mongo-lane cases. | deterministic proof | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | The helper asserts both root and `laravel-app/.env` finish with `QUEUE_CONNECTION=mongodb`. |
| `VAL-EXACT-01` | Validation Steps | Add/adjust focused regression proof for root queue normalization on Mongo lanes. | deterministic proof | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | Added and executed the focused rollback parity helper. |
| `VAL-EXACT-02` | Validation Steps | Run the relevant root CI-equivalent verification for the touched rollback surface. | ci-equivalent | `bash -n .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh .github/scripts/prove_rollback_queue_parity.sh`; `bash .github/scripts/verify_environment_ci.sh` | local root shell + CI-equivalent | passed | Syntax gate and root CI-equivalent both passed. |
| `VAL-EXACT-03` | Validation Steps | Capture blocker-closure evidence against the same rollback parity defect identified in PR `#737`. | blocker-closure evidence | `bash .github/scripts/prove_rollback_queue_parity.sh` | local shell proof | passed | The proof directly exercises the defect class described in PR `#737` and now returns green. |
| `VAL-01` | Validation Steps | Focused regression proof passes. | syntax + proof | `bash -n .github/scripts/rollback_remote.sh .github/scripts/verify_environment_ci.sh .github/scripts/prove_rollback_queue_parity.sh && bash .github/scripts/prove_rollback_queue_parity.sh` | local shell | passed | Syntax and focused parity proof both passed. |
| `VAL-02` | Validation Steps | Root CI-equivalent verification passes. | ci-equivalent | `bash .github/scripts/verify_environment_ci.sh` | local root CI-equivalent | passed | Returned `OK: CI environment invariants validated.` with the new parity proof wired in. |

## References
- `belluga/belluga_now_docker#737`
- [rollback_remote.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/rollback_remote.sh:244)
- [TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-main-promotion-copilot-style-blocker-anticipation-round-01.md:1)
- [package.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/package.md:1)
- [round-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/main-promotion-copilot-style-blocker-anticipation-round-01/triple-audit-20260521T235900Z/round-01/round-summary.md:1)
