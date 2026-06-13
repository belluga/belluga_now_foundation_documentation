# TODO: Post-Release Event Occurrence Orphan Root-Cause Audit and Write-Path Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
During the `v0.2.0+8` closeout review, public agenda/runtime validation surfaced orphan `EventOccurrence` rows whose parent `Event` no longer resolved. The immediate release-safe correction already landed at the read path: public agenda/stream now hard-cut orphan occurrences instead of exposing broken public detail routes, with focused coverage in [AgendaAndEventsControllerTest.php](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php).

That read-side hard cut is necessary, but it is not the final answer. We must still determine whether the orphan rows came from:

- historical residue created before the canonical transactional event aggregate write path was delivered; or
- an active bypass that still creates or preserves inconsistent `Event` / `EventOccurrence` state after the completed transactional-consistency cutover.

The completed authority for the canonical write model already exists in [TODO-store-release-event-occurrence-transactional-consistency-and-reconcile-removal.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/completed/TODO-store-release-event-occurrence-transactional-consistency-and-reconcile-removal.md). This follow-up exists because runtime evidence proved we still need a root-cause audit and, if needed, additional guardrails and cleanup.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-post-release-event-occurrence-orphan-root-cause`
- **Why this is the right current slice:** the product-visible blocker is already fail-closed at the public read path, so the remaining work is a bounded data-integrity and canonical-write-path audit with a narrow remediation surface.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the observed defect is already concrete, the potential causes are tightly bounded, and the prior completed TODO defines the expected canonical architecture we must verify against.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns the root-cause audit for orphan `EventOccurrence` rows that survive or appear after the canonical transactional consistency cutover.
- It owns the decision and implementation needed to resolve whichever of the two allowed explanations is true:
  - `legacy_residue`: orphan rows are historical leftovers from pre-cutover behavior, manual DB mutation, or non-runtime maintenance paths; or
  - `active_bypass`: a current runtime/admin/repair path still bypasses canonical aggregate ownership and can create inconsistent `Event` / `EventOccurrence` state.
- It may absorb targeted cleanup/repair, writer hardening, structural tests, and architectural guards needed to close that exact integrity gap.
- It does **not** reopen the completed transactional-consistency architecture unless the audit proves that architecture is still bypassed in a live path.
- It does **not** widen into general event-domain cleanup, broad scheduler redesign, or unrelated agenda/discovery UI work.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the classification method for orphan rows and the audit perimeter for all code paths that can create, repair, delete, or reconcile `Event` / `EventOccurrence` state.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** this is a newly opened post-release hardening owner routed from a real runtime finding; the architecture baseline exists, but the cause classification and exact remediation still need to be proven.
- **Exit condition:** every orphan row in the inspected tenant set is classified, the responsible path is either cleaned up or hardened, and tests/guards prove the canonical integrity contract.

## Execution Notes
- `2026-06-12` canonical delete-path validation was rerun locally against the authoritative Laravel feature tests and stayed green:
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter='test_event_delete_soft_deletes|test_event_delete_rolls_back_when_occurrence_soft_delete_fails_mid_flight'`
  - `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php --filter='test_agenda_excludes_orphan_occurrences_and_only_returns_resolvable_public_detail_rows|test_occurrence_ids_are_applied_in_initial_agenda_and_stream_pipeline_stages'`
- `2026-06-12` shared-runtime investigation on `https://guarappari.belluga.space` proved that the problematic `manual-v0208-convite-com-grupos` and `manual-v0208-evg-multi-ocorrencias` fixtures were internally drifted before cleanup:
  - admin detail payloads returned `profile_groups=[]` at the root and occurrence level;
  - occurrence `own_linked_account_profiles` counts were `0`;
  - the same payloads still exposed programming items linked to Account Profiles such as `Manual v0208 Banda Azul` and `Manual v0208 Expositor Sol`.
- `2026-06-12` those two drifted shared fixtures were then removed through the canonical admin mutation boundary, not by direct database edits:
  - `DELETE /admin/api/v1/events/manual-v0208-convite-com-grupos` -> `200`, subsequent admin `GET` -> `404`
  - `DELETE /admin/api/v1/events/manual-v0208-evg-multi-ocorrencias` -> `200`, subsequent admin `GET` -> `404`
- Current interpretation: this is positive evidence that the shared contradiction seen in public event-group validation came from stale/inconsistent fixture state, not from a newly observed failure of the canonical transactional delete path itself. This does **not** yet close the broader orphan-occurrence audit because remote DB-level orphan inventory still has not been classified.

## Scope
- [ ] Build a deterministic tenant-scoped orphan inventory method that identifies `EventOccurrence` rows whose parent `Event` does not resolve.
- [ ] Audit all current runtime/admin/repair/delete/reconcile paths that can mutate or remove `Event` / `EventOccurrence` state and classify each path as `canonical_owner`, `approved_exception`, or `bypass`.
- [ ] Determine whether the observed orphan rows are `legacy_residue`, `active_bypass`, or a mixed result, with evidence attached to each classification.
- [ ] If an active bypass exists, route that path through the canonical aggregate owner (`EventAggregateWriteService` + `EventTransactionRunner`) or otherwise hard-fail it.
- [ ] If the current orphan population is historical residue only, deliver the narrow cleanup/repair/audit path needed to remove it without normalizing a workaround architecture.
- [ ] Preserve the public read-side hard cut so agenda/stream/detail never expose unresolved orphan rows while root-cause work proceeds.
- [ ] Add regression tests and structural guards proving that canonical runtime writes cannot silently leave orphan occurrences behind.
- [ ] Add a dedicated `cutover-integrity` review step so this TODO cannot close with query-time stitching, pseudo-canonical shadow fields, or “repair-only” architecture posing as the final model.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: local implementation is materially complete, but the TODO remains in `active/` because package-wide review, Copilot-mimic, CI-equivalent, final validation, or explicit promotion-readiness scrutiny is still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `laravel-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `event occurrence orphan root-cause audit and write-path hardening` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Out of Scope
- [ ] Replacing the current public read hard cut with a permissive fallback that keeps exposing broken detail routes.
- [ ] Broad event-domain refactors unrelated to orphan occurrence causality.
- [ ] UI redesign in public agenda/event surfaces beyond the already-landed fail-closed read behavior.
- [ ] Normalizing direct DB edits, manual shell deletes, or ad hoc maintenance scripts as acceptable steady-state runtime behavior.
- [ ] Reopening the completed transactional-consistency TODO unless this audit proves a live path still bypasses that architecture.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** orphan inventory query/command, targeted cleanup/repair, writer rerouting to canonical aggregate ownership, structural tests, architecture guards, and module doc updates required to freeze the final integrity rule.
- **Must update or split the TODO:** broader event-domain consistency work beyond orphan occurrences, unrelated scheduler/runtime topology redesign, or cross-aggregate integrity policy that deserves its own owner.

## Definition of Done
- [ ] Every orphan `EventOccurrence` in the inspected tenant/runtime set is classified as `legacy_residue`, `active_bypass`, or `approved_exception`, with evidence.
- [ ] If an `active_bypass` exists, the exact path is fixed or fail-closed so it cannot keep creating orphan rows.
- [ ] If the current orphan set is only `legacy_residue`, there is an explicit cleanup/repair path and a recorded reason why the current canonical runtime cannot reproduce the defect.
- [ ] Public agenda/stream/detail payloads continue to exclude orphan occurrences and cannot expose broken public detail routes.
- [ ] Tests and structural guards prove the canonical write/delete/repair paths do not leave orphan occurrences behind unnoticed.
- [ ] The final conclusion is promoted into the canonical event-domain authority so later sessions do not rediscover the same ambiguity.

## Validation Steps
- [ ] Add fail-first Laravel coverage that reproduces the orphan-risk path or proves the audited current path cannot create orphan rows when executed canonically.
- [ ] Run the orphan-inventory query/command against the local/dev tenant dataset and capture counts plus sample IDs by classification.
- [ ] Run focused Laravel suites for public agenda/stream filtering, canonical event aggregate write ownership, rollback semantics, and any touched repair/delete path.
- [ ] Run `cutover_integrity_audit` and record whether any proposed cleanup or compatibility path is truly canonical or merely a hidden workaround.

## Completion Evidence Matrix (Required Before Delivery Claim)
Every `Definition of Done` item and every `Validation Steps` item must have a concrete evidence row before the TODO can claim `Local-Implemented`, move to `promotion_lane/`, move to `completed/`, or claim `Production-Ready`.

Evidence must be real and criterion-specific. Aggregate summaries such as "tests passed" are supporting notes only; they do not replace a row proving the exact criterion. If a criterion names a UI control, route, endpoint, schema, browser/device journey, integration test, migration, or runtime behavior, the evidence must name that same artifact or record an approved waiver/deviation.

For any user-visible, interactive, or user-flow-impacting criterion, the evidence row must name the integration/device test or navigation/browser test that exercises that exact item. This includes visible UI (screen, admin/public surface, map, list/detail, form, field, button/FAB, tab, filter, search, chip/tag, selection state, scroll/sticky behavior, loading/empty/error state), and also non-visual implementation criteria that can affect a user journey. CRUD/mutation is a strong signal, but not the boundary: field refactors, DTO/domain/payload shape changes, backend validation, request/response projections, query/filter semantics, settings/capabilities, and read models must be assessed case by case. When such a change feeds an admin/public screen, save/readback flow, list/detail surface, or persisted user state, default to requiring runtime flow evidence unless the TODO records why the touched surface cannot affect user-observable behavior. In Flutter scope, `integration test` means device execution via ADB; web browser coverage is `navigation test` and is Playwright against the final browser-facing domain. Implementation code locations, analyzer output, screenshots, unit tests, or widget tests are valid implementation/supporting evidence and should be recorded, but they do not replace final flow acceptance evidence. If the item is structure-only and has no visible/runtime/user-flow behavior, record an explicit approved waiver/deviation explaining why integration/device or navigation/browser coverage is not applicable.

Platform parity rule: if Android and Web exercise the same visible behavior through the same contract, one final runtime lane is sufficient (`integration/device` via ADB or `navigation/browser` via Playwright). If Android and Web behavior differs materially for the criterion, record and pass both lanes before delivery. Subagent/worker-local evidence may stop at code, unit, widget, package, and targeted tests; the orchestrator may accept delivery only after the consolidated branch has the required final runtime lane(s).

For browser/web-visible behavior, Playwright is the canonical browser navigation evidence whenever the downstream repository exposes a Playwright web suite. The evidence row must name the source-owned Playwright spec and the runner command, typically `tools/flutter/web_app_tests/**` executed through the project-owned navigation runner. Browser evidence must first publish the current checkout with the project-defined build/publish command and output target from `foundation_documentation` or dependency-readiness notes, confirm the browser-facing domain is serving that refreshed bundle, and then run Playwright against the real configured domain (for example `NAV_LANDLORD_URL` / `NAV_TENANT_URL` when that topology applies). For web CRUD/mutation, the Playwright `mutation` lane on an approved non-`main` target is required; a `readonly` web smoke is not enough.

For any criterion that includes user-flow CRUD or mutation behavior (create, edit, update, save, delete, reorder, submit, persist, or equivalent), the integration/device or navigation/browser evidence must exercise the local mutation path against the approved non-main validation target. A read-only navigation, mocked local filter, or backend-only assertion is not enough when the change can affect a user-facing save or readback flow. For non-CRUD refactors, the TODO must still assess whether changed fields/contracts/projections affect user flows; if yes, the same runtime evidence rule applies.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `Every orphan EventOccurrence in the inspected tenant/runtime set is classified as legacy_residue, active_bypass, or approved_exception, with evidence.` | `review` | `<pending orphan inventory artifact + classification ledger>` | `backend` | `planned` | `The audit must prove where each orphan came from, not only count rows.` |
| `VAL-01` | `Validation Steps` | `Add fail-first Laravel coverage that reproduces the orphan-risk path or proves the audited current path cannot create orphan rows when executed canonically.` | `test` | `<pending focused Laravel fail-first test command>` | `local` | `planned` | `This must pin the actual causality, not only the read-side symptom.` |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `Local/dev tenant dataset with current event/occurrence records` | The audit must classify real orphan rows, not just hypothetical code paths. | `unknown` | `n/a` | `<pending tenant-scoped orphan inventory query>` | Do not freeze the remediation shape before the real dataset is classified. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | The final conclusion must be challenged for workaround architecture and weak test closure because the public symptom is already masked by a read-side hard cut. | `laravel-app`, tests, documentation | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the remediation may be small, but the causality audit spans runtime data, canonical write ownership, repair/delete paths, and architectural guardrails.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Planned decision promotion targets (module sections):**
  - `events_module.md` event aggregate consistency / occurrence ownership notes
  - `agenda_and_action_planner_module.md` public agenda payload integrity notes
- **Module decision consolidation targets (required):**
  - `events_module.md`

## Decision Pending (Resolve Before Freeze)
- [ ] `D-01` Are the currently observed orphan rows exclusively `legacy_residue`, or does at least one active runtime/admin/repair path still bypass canonical aggregate ownership?
- [ ] `D-02` If the current orphan set is only historical residue, should the cleanup remain a targeted manual/repair command or become an auditable recurring detection surface without automatic rewrite?

## Decisions (Resolved Before Freeze)
- [ ] `D-01` Public read paths must remain fail-closed and must not re-expose orphan occurrences as a workaround while investigation and remediation proceed. (`No Prior Decision`)
- [ ] `D-02` This TODO must prove root cause before choosing remediation shape; “probably old residue” is not sufficient closure, and “probably an active bypass” is not sufficient to reopen architecture. (`No Prior Decision`)

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `events aggregate consistency` | Event/EventOccurrence consistency is owned by canonical transaction-backed aggregate writes, not by periodic reconcile or partial-shape repair. | `Preserve` | `foundation_documentation/todos/completed/TODO-store-release-event-occurrence-transactional-consistency-and-reconcile-removal.md` |
- | `public agenda integrity` | Public agenda/detail should not expose broken event detail routes. | `Preserve` | `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php` |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-01` The public hard cut for orphan occurrences is preserved while root cause is investigated and remediated.
- [ ] `D-02` The final architecture must either prove current runtime write safety plus historical cleanup, or close an active bypass at the canonical write boundary. Query-time stitching and tolerance layers are not acceptable final architecture.

## Questions To Close
- [ ] Which production-like mutation/deletion/repair paths can still materially affect `EventOccurrence` rows after the completed transactional consistency cutover?
- [ ] Do any current operational commands or maintenance flows legitimately need an approved exception outside the canonical aggregate writer, and if so, how will that exception be bounded and guarded?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The currently observed orphan rows may be historical residue created before the completed transactional-consistency cutover. | The orphan symptom surfaced during later public-read hardening, while the canonical write-path authority and tests already exist in the completed TX0 TODO. | We are dealing with an active bypass and must treat it as a current write-path defect, not only cleanup debt. | `Medium` | `Keep as Assumption` |
| `A-02` | If a live bypass exists, it is likely a narrow path outside the canonical aggregate writer rather than a total failure of the approved TX0 architecture. | `EventAggregateWriteOwnershipTest.php` and the completed TX0 TODO already pin the intended owner path. | The remediation may need to reopen a broader architecture front rather than patch one path. | `Medium` | `Keep as Assumption` |
| `A-03` | The public read hard cut is the correct temporary containment and should remain even if the root cause proves to be historical residue only. | The public test now explicitly rejects orphan agenda rows instead of exposing broken detail routes. | We would need a stronger containment contract before continuing any promotion or acceptance that depends on those reads. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/**`
- `laravel-app/packages/belluga/belluga_events/src/Application/Transactions/**`
- `laravel-app/tests/Feature/Events/**`
- `laravel-app/tests/Unit/Events/**`
- `laravel-app/routes/console.php` or any current repair/audit command surface if touched
- `foundation_documentation/modules/events_module.md`

### Ordered Steps
1. Build the orphan inventory and classification method on real tenant data.
2. Audit every current write/delete/repair/reconcile path that can affect `Event` / `EventOccurrence` integrity.
3. Add fail-first tests for any suspected active bypass or missing invariant.
4. Implement the narrowest canonical remediation:
   - cleanup + proof of non-reproducibility if residue only; or
   - writer reroute/fail-close if active bypass exists.
5. Re-run focused integrity suites and promote the final conclusion into module truth.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the defect can be masked by the existing public read hard cut; without fail-first evidence we could “pass green” while leaving the causality unresolved.
- **Fail-first target(s) (when required):** orphan-causing mutation/delete/repair path, or an invariant test proving the audited canonical path cannot leave orphan rows behind.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Public agenda/stream must exclude orphan rows | Broken rows would surface invalid public detail/navigation behavior. | `shared-android-web` | `Playwright readonly` | `no` | `yes` | existing + refreshed agenda runtime/browser proof plus focused Laravel tests | `n/a` |
| Event aggregate mutation/delete/repair cannot leave orphan rows | This is the likely causality boundary and may affect future admin/public save/readback flows. | `shared-android-web` | `n/a unless API contract or UI mutation flow changes` | `yes if admin/API mutation flow is touched` | `yes` | focused Laravel mutation tests; add browser/admin mutation lane only if the fix changes user-facing mutation flow | `If remediation stays fully internal to canonical backend ownership, backend mutation evidence is primary.` |
| Orphan cleanup/repair path | Cleanup must not become a hidden workaround architecture. | `n/a` | `n/a` | `yes` | `yes` | repair/audit command evidence + cutover-integrity review | `No direct UI behavior unless the remediation changes an exposed flow.` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app focused events integrity suites` | Root-cause and remediation live in the event aggregate boundary. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Events/EventAggregateWriteOwnershipTest.php` | `Local-Implemented` | `planned` | `<pending>` | Expand if the audit touches additional repair/delete paths. |
| `browser/runtime readonly navigation proof` | Public read containment must remain true on the served bundle if payload behavior changes. | `bash tools/flutter/run_web_navigation_smoke.sh readonly` | `promotion` | `planned` | `<pending>` | Required only if the remediation changes browser-visible payload behavior or routing closure evidence. |

### Runtime / Rollout Notes
- If the audit proves `legacy_residue`, cleanup must stay explicit and auditable; do not hide it inside a silent runtime reconcile.
- If the audit proves `active_bypass`, fail closed rather than layering compensating readers or shadow projections.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
Review the `Assumptions Preview` and `Execution Plan` against architecture, code quality, tests, performance, security, elegance, and structural soundness before approval.
Treat brittle workarounds and structural shortcuts as explicit negative findings: ad hoc patches, layered patches over unresolved defects, contract bypasses, opportunistic duplication, hidden coupling, or other avoidable structural debt.

### Review Sections
- [ ] Architecture
- [ ] Code Quality
- [ ] Tests
- [ ] Performance
- [ ] Security
- [ ] Elegance
- [ ] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** `high`
  - **Evidence:** orphan public agenda rows were observed in runtime while the canonical transactional write architecture was already expected to prevent them.
  - **Why it matters now:** a read-side hard cut can hide whether the write model is truly safe, which risks carrying a silent data-integrity defect forward.
  - **Option A (Recommended):** classify real orphan rows first, then remediate only the proven causality path.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** assume historical residue and only clean current rows.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** keep only the public hard cut and do not investigate cause.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`

- **Issue ID:** `CUT-01`
  - **Severity:** `high`
  - **Evidence:** the most tempting closure shape is a cleanup/reconcile-style workaround that preserves symptom masking without proving writer safety.
  - **Why it matters now:** this TODO touches canonical cutover integrity; it can easily “pass tests” while leaving non-canonical compensations behind.
  - **Option A (Recommended):** require explicit `cutover-integrity` review before any delivery claim.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** rely on ordinary test and code review only.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** `n/a`
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Root instruction source for downstream TODO work. | Cause-root ownership and no tactical drift hidden behind symptom patches. | Treating runtime residue as resolved just because public reads are masked. | The TODO must prove causality before it claims closure. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | Required to declare profile and scope before task work. | `operational-coder` ownership with Laravel focus and assurance handoff. | Starting implementation without explicit scope. | The TODO is opened under `laravel` scope with bounded runtime/doc spillover. |
| `r0/wf-docker-todo-driven-execution-method/SKILL.md` | Governs tactical TODO ownership, approval, delivery gates, and closeout. | Explicit owner boundary and review/promotion discipline. | Keeping this as an informal note in another TODO. | This follow-up now has its own executable owner contract. |
| `foundation_documentation/todos/README.md` | Governs follow-up-hardening routing and cutover-integrity requirements. | Post-release hardening placement and explicit cutover review. | Quietly accepting workaround architecture. | The TODO stays under `active/post_release_hardening/hardening/` and includes explicit cutover-integrity review. |
| `foundation_documentation/todos/completed/TODO-store-release-event-occurrence-transactional-consistency-and-reconcile-removal.md` | Defines the canonical architecture this audit must verify against. | Transaction-owned aggregate consistency and manual-only repair posture. | Reintroducing periodic reconcile or partial-shape rewrite paths. | The audit must treat that completed TODO as the baseline, not rediscover architecture from scratch. |
