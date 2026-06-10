# TODO: Post-Release Tenant-Public Anonymous Identity Readiness Self-Healing and Boundary Pruning

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The `v0.2.0+8` bootstrap boundary fix established the correct architectural split: protected tenant-public consumers now depend on a narrower anonymous-identity readiness path instead of broad `AuthRepository.init()` side effects. The external Claude `fable` review on `2026-06-10` explicitly marked that direction as correct, but it also surfaced residual hardening debt:

- once readiness succeeds, the current session can remain "sticky ready" even if the server later invalidates the anonymous token, leaving the app without a canonical self-heal path;
- the boundary still exposes dead configurability (`bootstrapIfEmpty`) that no real caller uses;
- the startup ordering contract is correct by design, but it should be pinned by fail-first tests at the shared boundary instead of relying on reviewer memory.

These are not release blockers for `v0.2.0+8`. They are still real architecture hardening items and should be handled as a bounded post-release TODO instead of remaining mixed into release-specific bootstrap work.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-tenant-public-anonymous-identity-self-healing`
- **Why this is the right current slice:** the review isolated one cohesive boundary-hardening front: canonical self-healing when the anonymous identity becomes stale after readiness, plus removal of dead boundary configuration.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the current release review already supplied the precise residuals and the bounded owner boundary.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns the shared tenant-public anonymous-identity self-healing boundary after readiness has already succeeded once.
- It owns removal of dead readiness-boundary configurability that no real caller exercises.
- It may absorb the shared boundary tests needed to pin startup ordering and stale-token recovery.
- It does **not** reopen the `v0.2.0+8` release blocker, the already-approved bootstrap split, or broader permission/location-origin ownership cleanup.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the one canonical stale-anonymous-token recovery rule and the exact shared boundary that owns it.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** this owner was opened directly from the `v0.2.0+8` Claude/Fable review as non-blocking hardening work.
- **Exit condition:** the shared boundary self-heals one stale anonymous-token failure path deterministically, dead configurability is removed, and regression tests pin the contract.

## Scope
- [ ] Add one canonical 401-driven invalidation/reset plus single reissue/retry path for tenant-public anonymous identity readiness.
- [ ] Ensure the recovery lives at the shared boundary instead of being reimplemented by agenda/map/invite/favorites consumers.
- [ ] Remove dead `bootstrapIfEmpty`-style configurability once the boundary behavior is frozen.
- [ ] Add fail-first coverage for post-readiness stale-token recovery and for auth-init/startup ordering invariants that must remain true by design.

## Out of Scope
- [ ] Reopening raw unauthenticated tenant-public reads.
- [ ] Reworking Home/map location-permission ownership or post-grant origin readiness.
- [ ] Broad tenant-public resilience/error-taxonomy redesign beyond the narrow stale-anonymous-token self-heal path.

## Definition of Done
- [ ] A previously ready anonymous tenant-public session can recover once from a shared-boundary stale-token/401 condition without per-consumer bootstrap drift.
- [ ] The shared boundary owns the retry/invalidation rule; individual consumers no longer need to remember how to recover.
- [ ] Dead readiness-boundary configuration is removed or proven intentionally necessary.
- [ ] Tests pin both the stale-token recovery rule and the startup-order invariant that the review accepted by design.

## Validation Steps
- [ ] Add fail-first tests for stale anonymous-token recovery at the shared boundary.
- [ ] Add or tighten tests proving auth initialization/readiness occurs before dependent tenant-public flows continue.
- [ ] Run focused Flutter tests for the shared auth/readiness boundary and impacted protected public consumers.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<pending>`, `flutter-app:<pending>`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `tenant-public anonymous identity self-healing and boundary pruning` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** shared-boundary invalidation/reissue logic, dead flag pruning, contract tests, and narrow documentation updates needed to state the recovery rule clearly.
- **Must update or split the TODO:** broader error-taxonomy/resilience design, permission/location ownership, or any policy change that would widen anonymous-web capability.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | The self-heal path needs hostile regression coverage so stale-token recovery cannot drift back into per-consumer workarounds. | `flutter-app`, tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `consolidated`
- **Why this level:** one shared boundary owns the remaining work, even though the contract is cross-cutting.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant-public anonymous identity readiness and recovery`
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`

## Decisions (Resolved Before Freeze)
- [ ] `D-01` This TODO is routed from the `v0.2.0+8` bootstrap/Fable review as `follow-up-hardening`, not `release-blocker`.

## Questions To Close
- [ ] Should the shared boundary retry only the first 401 after a previously ready session, or should it differentiate by endpoint/error class before reissue?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current release fix already established the correct readiness owner; what remains is recovery and cleanup, not a fresh ownership redesign. | Claude `fable` review marked the readiness split as acceptable and `direction=keep`. | The TODO would need to move back into release-blocker architecture instead of post-release hardening. | `High` | `Keep as Assumption` |
| `A-02` | A single shared invalidation/reissue rule is enough to cover the stale-anonymous-token case without a broader resilience program. | The concrete review gap is a sticky-ready session after server invalidation, not a generalized retry-matrix absence. | The work would need to merge into the broader tenant-public resilience program. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/infrastructure/repositories/auth_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/shared/tenant_public_auth_headers.dart`
- affected protected tenant-public backends/clients that consume the shared readiness boundary
- `flutter-app/test/**`
- `foundation_documentation/modules/**`

### Ordered Steps
1. Freeze the canonical stale-anonymous-token recovery rule at the shared boundary.
2. Remove dead readiness-boundary configurability.
3. Add fail-first recovery/order tests and focused shared-boundary suites.
4. Promote the finalized rule into module truth.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** stale-token recovery can look green in happy paths while remaining structurally broken until a real 401-after-ready case is simulated.
- **Fail-first target(s) (when required):** shared-boundary stale-token recovery and startup-order invariants.

### Runtime / Rollout Notes
- No rollout flag expected.
- The self-heal rule must remain narrow and canonical; consumers must not gain local fallback logic as a side effect of this work.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Root instruction source for downstream TODO work. | Narrow, explicit ownership and no implementation before approval. | Smuggling post-release hardening into implicit execution. | This TODO remains a routed hardening owner until explicit `APROVADO`. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | Required to declare profile and scope before task work. | Operational-coder ownership with test-hardening support. | Treating shared-boundary recovery as controller-local work. | The TODO stays `flutter`-scoped with assurance support. |
| `r0/wf-docker-todo-driven-execution-method/SKILL.md` | Governs tactical TODO creation and follow-up classification. | Explicit owner boundary, approval gate, and delivery discipline. | Leaving self-healing debt as an informal note in the release TODO. | This hardening slice is separated cleanly from the release blocker TODO. |
| `foundation_documentation/todos/README.md` | Governs post-release hardening routing for non-blocking review findings. | `follow-up-hardening` placement under `active/post_release_hardening/hardening/`. | Treating a real architecture debt item as `by-design` just because it is not a blocker. | The finding is preserved as an executable hardening owner. |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Anonymous public identity readiness and startup/auth boundary behavior must remain centralized. | Shared readiness boundary ownership. | Per-consumer stale-token recovery or hidden bootstrap drift. | Final implementation must consolidate the recovery contract into module truth. |
| `foundation_documentation/modules/onboarding_flow_module.md` | Anonymous identity bootstrap posture constrains what self-healing may do. | Anonymous-authenticated public posture. | Reopening raw unauthenticated reads while adding recovery behavior. | The self-heal path must preserve current anonymous identity posture. |
