# Title
Store Release: Reference-Location Core and Dependent Capability Guardrails

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Created on:** `2026-04-20`
- **Why this exists now:** the release-follow-up proximity work now depends on a reusable fixed-reference core and on the first dependent-capability rule in the project. This TODO freezes that narrower blocker before the broader proximity-preferences slice proceeds to orchestration.

## Context
The current runtime already has a canonical `LocationOriginService` and a local/device-first origin baseline, but the next release layer now needs a reusable fixed-reference contract that can outlive the first manual-map editor and later support entity-backed references such as a hotel Account Profile. That reusable contract must remain user-owned preference state rather than becoming Account Profile-owned state.

This blocker also captures the first explicit capability dependency case in the project: an entity type can only be a valid fixed-reference source when it is already POI-capable. The new reference-source capability therefore cannot behave as an independent toggle; it must become disabled whenever `is_poi_enabled=false`, both when configuring the type and when resolving stored references later.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** the open work is now a small but high-leverage contract freeze: define the reusable reference-location core, the generic provenance schema, and the dependent-capability rule so the main proximity TODO can resume from a deterministic baseline.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the broader feature has already been narrowed; this blocker exists only because a new cross-cutting contract surfaced during pre-orchestration refinement.

## Contract Boundary
- This TODO defines **WHAT** must be frozen before the main proximity-preferences TODO proceeds.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this blocker.
- This TODO is **bounded but elastic** only inside the reusable reference-location contract, dependent capability rule, and their tests. If the work expands into end-user selection UX, broader trip-planning, or generic entity-picking IA, split or update the downstream TODO instead of stretching this blocker.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `Release-Critical`, `Cross-Stack`, `Blocker-Delivered`, `Principal-Checkout-Reconcile`
- **Next exact step:** keep the blocker available in the principal-checkout reconcile state for manual validation together with the consumer proximity slice, then move both TODOs to `promotion_lane/`.

## Scope
- [ ] Define the reusable fixed-reference core as `package/lib-first`: local package or library boundary now, extraction-ready/global candidate later, while keeping host-app persistence/merge ownership outside the package.
- [ ] Freeze the canonical fixed-reference source taxonomy so the reusable core supports at least `manual_coordinate` and `entity_reference`.
- [ ] Freeze the canonical entity-reference provenance shape using generic fields, not account-profile-specific semantics: `source_kind`, `entity_namespace`, `entity_type`, `entity_id`, plus user-facing label/source metadata and coordinate snapshot.
- [ ] Keep the user preference user-owned: saving a hotel as origin creates a user-owned fixed reference that may point to an entity source, but the Account Profile does not own the preference.
- [ ] Introduce `is_reference_location_enabled` as a dependent profile-type capability whose effective value is `false` whenever `is_poi_enabled=false`.
- [ ] Freeze both write-time and read-time dependency semantics so impossible capability combinations and previously stored entity references resolve as disabled when prerequisites are not satisfied.
- [ ] Define the minimum contract-test floor for Laravel + Flutter so capability dependency, reference eligibility, stored-reference disable semantics, and provenance mapping are all protected before the broader proximity TODO executes.
- [ ] Keep the first delivered consumer narrow: the main proximity TODO may still ship manual-coordinate UI first, but the reusable core must already be compatible with future `account_profile/hotel` entity references.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863; flutter-app: reconcile/delphi-store-release-20260420 @ fa31acca`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Reusable reference-location core contract + dependent capability rule + test floor | `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863; flutter-app: reconcile/delphi-store-release-20260420 @ fa31acca` | `laravel-app: PR #158 (merged to dev 2026-04-21); flutter-app: PR #236 (merged to dev 2026-04-20)` | `<pending>` | `n/a` | `Lane-Promoted` |

## Out of Scope
- [ ] Shipping the full Account Profile hotel CTA/editor UX in this blocker.
- [ ] Reworking the current live-device runtime selection policy beyond the already-frozen proximity baseline.
- [ ] Extracting a globally versioned external package outside this repository during this blocker.
- [ ] Broad map/discovery/search adoption of the new reference-location core in the same slice.
- [ ] Account Profile ownership changes; the new relation remains user-owned preference state.

## Dependencies & Sequencing
- [x] `DEP-01` This TODO is a hard blocker for `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-proximity-preferences-and-location-origin.md`.
- [ ] `DEP-02` If package naming or extraction strategy changes beyond a local incubated package/library boundary, update this TODO before implementation starts.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** reusable reference-location contract shape, provenance schema, capability dependency semantics, package-first boundary, and required cross-stack test inventory.
- **Must update or split the TODO:** end-user entity-picker UX, generic travel-planning flows, or broader consumer rollout beyond the current proximity-follow-up lane.

## Definition of Done
- [ ] The reusable reference-location core boundary is frozen as package/lib-first with host-owned persistence clearly separated.
- [ ] The canonical provenance schema for entity-backed fixed references is explicit and generic.
- [ ] `is_reference_location_enabled` is defined as dependent on `is_poi_enabled`, with deterministic write-time and read-time disable semantics.
- [ ] Stored entity references that later lose prerequisites resolve as disabled/ineligible rather than silently continuing as active references.
- [ ] Contract-level tests are specified for Laravel registry management/query behavior and Flutter/domain mapping/consumption.
- [ ] The main proximity-preferences TODO is updated to consume this blocker rather than redefining the reusable core.

## Validation Steps
- [ ] Manual doc review against `foundation_documentation/modules/system_architecture_principles.md` and `foundation_documentation/modules/map_poi_module.md` so the new dependent capability preserves the existing POI-authority model.
- [ ] Repo audit against `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`, `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryService.php`, and `flutter-app/lib/domain/app_data/location_origin_settings.dart` so the blocker stays anchored to existing capability and origin surfaces.
- [ ] Package-first verification evidence captured from `delphi-ai/tools/query_packages.sh` before implementation begins.
- [ ] Manual test-plan review covering registry write/read behavior, stored-reference disable behavior, and Flutter/domain mapping before `APROVADO`.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `none required for backlog capture` | This blocker is freezing contracts and validation expectations only. | `healthy` | `2026-04-20` | Local repository + documentation inspection | `n/a` |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | The first dependent-capability rule must be test-hardened on both registry and consumer resolution paths before the main proximity slice proceeds. | `flutter-app`, `laravel-app`, tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** `abbreviated plan review + one post-validation checkpoint`
- **Why this level:** the blocker is intentionally narrow, but it is still cross-stack because it freezes a reusable contract, registry capability semantics, and mandatory tests.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/map_poi_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-REF-00` The new fixed-reference contract is `package/lib-first`: build it as a local incubated reusable boundary now, with host-app persistence/merge orchestration remaining outside the package. Module refs: `project_constitution.md` package-capable doctrine + package-first verification evidence from `query_packages.sh`.
- [x] `D-REF-01` Fixed reference state remains user-owned preference state. When a user saves a hotel origin, the user preference points to the hotel as source provenance; the Account Profile does not own the user's origin setting. Module refs: `tenant_home_composer_module.md`, `agenda_and_action_planner_module.md`.
- [x] `D-REF-02` The reusable fixed-reference core must support `manual_coordinate` and `entity_reference`. Live-device mode remains outside that fixed-reference submodel and stays governed by the main proximity TODO. Module refs: `AGD-04`, `HOM-06`.
- [x] `D-REF-03` Entity-backed fixed references use generic provenance metadata, not `integration_type`: `source_kind`, `entity_namespace`, `entity_type`, `entity_id`, plus label/source display metadata and coordinate snapshot. The first sanctioned downstream shape is `entity_namespace=account_profile`, `entity_type=hotel`. Module refs: `system_architecture_principles.md` typed registry model + user decision captured on `2026-04-20`.
- [x] `D-REF-04` Entity-backed fixed references persist an exact coordinate snapshot alongside provenance so distance calculations remain deterministic and the UI can still show relation to the source entity. Module refs: main proximity decision `D-PROX-08` + user discussion on hotel provenance display.
- [x] `D-REF-05` `is_reference_location_enabled` is a dependent capability; its effective value is `false` whenever `is_poi_enabled=false`. Admin surfaces must disable the option in that state, and backend/query layers must never expose an effective enabled result while the prerequisite is disabled. Module refs: `MAP-08`, `tenant_admin_module.md`, `AccountProfileRegistryManagementService.php`.
- [x] `D-REF-06` Stored entity references whose prerequisite capability later becomes false resolve as disabled/ineligible at read time; they must not silently remain active via stale coordinate fallback. Module refs: `map_poi_module.md` capability-driven disable semantics + user decision captured on `2026-04-20`.
- [x] `D-REF-07` Manual-coordinate fixed references are not gated by Account Profile type capabilities. The dependency rule applies only to entity-backed reference sources. Module refs: `D-PROX-08`, `agenda_and_action_planner_module.md`.
- [x] `D-REF-08` This blocker must ship with explicit tests for registry write normalization/guardrails, registry read exposure, source eligibility evaluation, stored-reference disable semantics, and Flutter/domain mapping of disabled reference metadata. Module refs: `test-creation-standard`, `test-quality-audit`.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `MAP-08` | Disabling `is_poi_enabled` for a type hard-deletes affected POI projections. | `Preserve` | `foundation_documentation/modules/map_poi_module.md` |
| `HOM-06` | Home surfaces canonical location-origin result while effective origin remains locally persisted/device-side in MVP. | `Preserve` | `foundation_documentation/modules/tenant_home_composer_module.md` |
| `AGD-04` | `LocationOriginService` owns canonical effective-origin selection and locally persists `mode + reason` in MVP. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-REF-00` Build the reusable fixed-reference core as a local package/library-first boundary.
- [x] `D-REF-01` Fixed reference state is user-owned even when the source points to another entity.
- [x] `D-REF-02` The reusable core supports `manual_coordinate` and `entity_reference`.
- [x] `D-REF-03` Entity reference provenance uses `source_kind` + `entity_namespace` + `entity_type` + `entity_id`, not `integration_type`.
- [x] `D-REF-04` Entity-backed references persist exact coordinate snapshot plus source provenance metadata.
- [x] `D-REF-05` `is_reference_location_enabled` is effectively disabled whenever `is_poi_enabled=false`.
- [x] `D-REF-06` Stored entity references become disabled/ineligible when prerequisites later fail.
- [x] `D-REF-07` Manual-coordinate references are outside the dependent-capability rule.
- [x] `D-REF-08` Cross-stack tests for the dependency rule are mandatory in this blocker.
- [x] `D-REF-09` The authoritative contract home for the reusable fixed-reference core and provenance/disabled-resolution schema is the location-origin section in `foundation_documentation/modules/agenda_and_action_planner_module.md` (`Section 3.4`, promoted during implementation of this blocker); other module docs must reference that section instead of redefining the contract.
- [x] `D-REF-10` Entity-reference resolution must preserve coordinate snapshot + provenance and expose the minimal cross-stack disabled contract: `reference_status`, `reference_status_reason`, and `blocked_capability_key` when prerequisite failure disables the stored reference.

## Questions To Close
- [x] No additional product decision is currently blocking this narrower contract freeze; downstream UI-hosting decisions remain owned by the main proximity TODO.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Account Profile type registries remain the canonical owner for POI capability semantics and are the correct first home for the new dependent capability. | `system_architecture_principles.md`; `tenant_admin_module.md`; `AccountProfileRegistryManagementService.php` | The blocker would need to widen into a broader registry-ownership reframe. | `High` | `Keep as Assumption` |
| `A-02` | No existing local/ecosystem package already owns this reusable contract, so a new local incubated package/library boundary is justified. | `query_packages.sh --search "location"|"map"|"identity"|"settings"` returned no matching package owner on `2026-04-20`. | The blocker should be rewritten to adopt the existing package instead of creating a new core. | `High` | `Keep as Assumption` |
| `A-03` | The first user-facing release slice can still ship manual-coordinate flows first while carrying the generic entity-reference core and tests underneath. | Main proximity TODO scope + current user guidance on hotel capability being useful now even if CTA is not first priority. | The main proximity TODO would need to widen into a larger multi-surface rollout before orchestration. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/**`
- `foundation_documentation/todos/active/store_release_android/**`
- `flutter-app/lib/domain/**`
- `flutter-app/lib/infrastructure/**`
- `flutter-app/test/**`
- `laravel-app/app/Application/AccountProfiles/**`
- `laravel-app/tests/**`

### Ordered Steps
1. Record package-first evidence and freeze the reusable boundary for fixed-reference logic.
2. Freeze the canonical source taxonomy and entity-reference provenance schema, and name the single authoritative contract home required by `D-REF-09` (`agenda_and_action_planner_module.md`, `Section 3.4`).
3. Freeze dependent-capability semantics for selection, persistence, and read-time resolution, including the minimal disabled-resolution payload required by `D-REF-10`.
4. Define the fail-first test inventory across Laravel and Flutter.
5. Update the main proximity TODO so orchestration consumes this blocker instead of redefining it.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the first dependent-capability rule can regress silently if write-path, read-path, and consumer-path expectations are not all enforced before implementation.
- **Fail-first target(s) (when required):** Laravel feature/unit tests for capability dependency and stored-reference disable semantics, plus Flutter/domain/repository tests for disabled reference exposure and provenance mapping.

### Runtime / Rollout Notes
- Entity-backed fixed references must degrade to `disabled` when their source type no longer satisfies prerequisites.
- Disabled entity references must retain provenance plus the minimal status/reason payload so downstream consumers can explain why the stored reference is no longer eligible.
- Manual-coordinate references remain independent of source-type capabilities.
- The main proximity TODO may keep hotel selection UX deferred, but it must not redefine the reusable contract later.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [ ] Architecture
- [ ] Code Quality
- [ ] Tests
- [ ] Performance
- [ ] Security
- [ ] Elegance
- [ ] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-REF-01`
  - **Severity:** `high`
  - **Evidence:** The codebase already treats `is_poi_enabled` as an authoritative type capability for POI participation. Adding a new independent reference-source toggle would allow impossible source types to appear eligible and would leave stored entity references semantically stale when the prerequisite is later disabled.
  - **Why it matters now:** this is the first capability-dependency case in the project. If the dependency rule is not frozen now, the main proximity TODO will orchestrate against an invalid core and the resulting behavior will be hard to unwind later.
  - **Option A (Recommended):** Define `is_reference_location_enabled` as an effective dependent capability (`enabled` only when `is_poi_enabled=true`) and require disabled read-resolution for stored entity references whose prerequisites later fail.
    - **Effort:** `small`
    - **Risk:** `low`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Treat reference eligibility as an independent capability and rely on UI discipline to avoid impossible combinations.
    - **Effort:** `small`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Defer the reusable core and capability rule until after manual-coordinate delivery starts.
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] A type is saved with `is_reference_location_enabled=true` while `is_poi_enabled=false`, and downstream queries still expose it as eligible.
- [ ] A user saves a hotel as fixed origin, the hotel type later loses POI/reference eligibility, and the stored origin still resolves as active instead of disabled.
- [ ] The core hardcodes `account_profile` semantics instead of using the generic provenance fields needed for future entity sources.
- [ ] The UI cannot explain why a fixed origin is disabled because the source provenance or the minimal disabled status/reason payload was not preserved alongside the coordinate snapshot.

## Package-First Assessment
- **Canonical method:** `package-first-verification`
- **Evidence date:** `2026-04-20`
- **Commands run:**
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "location"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "map"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "identity"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --search "settings"`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root .. --all`
  - `bash ../delphi-ai/tools/query_packages.sh --project-root . --all`
- **Result:** no existing local/ecosystem package currently owns reusable reference-location logic.
- **Decision impact:** create a local incubated reusable boundary now instead of embedding the contract directly in host-only app code.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `no`
- **Required lenses:** `correctness`, `structural-soundness`

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo <todo-path> [--json-output <artifact-path>]`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `0b58ea563e14` (`2026-04-20`)

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `small` | Matches the intentionally narrow blocker scope. |
| `blast_radius` | `cross-stack` | Registry capability semantics, domain contract, and downstream consumers all change together. |
| `behavioral_change_or_bugfix` | `yes` | This slice changes effective eligibility/resolution behavior for fixed-reference sources. |
| `changes_public_contract` | `yes` | Capability exposure, fixed-reference schema, and disabled-resolution semantics are contract-visible. |
| `touches_auth_or_tenant` | `yes` | The capability lives in tenant-owned type registries and influences identity-owned user preference behavior. |
| `touches_runtime_or_infra` | `no` | No queue/runtime/infra work is required by this blocker. |
| `touches_tests` | `yes` | Cross-stack contract tests are mandatory. |
| `critical_user_journey` | `yes` | Invalid reference eligibility would directly compromise Home origin behavior in the release lane. |
| `release_or_promotion_critical` | `yes` | This blocker exists only to unblock store-release delivery. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-REF-01` is high severity. |
| `explicit_three_lane_request` | `no` | Triple external audit is not explicitly requested; it may still be derived as required. |

### Derived Audit Floor
- `Critique`: `required` before `APROVADO` via `wf-docker-independent-critique-method`.
- `Security review`: `required` before completion via `security-adversarial-review`.
- `Performance/concurrency`: `recommended` via `wf-docker-performance-concurrency-validation-method`.
- `Verification debt`: `required` before completion via `verification-debt-audit`.
- `Test-quality audit`: `required` before completion via `wf-docker-independent-test-quality-audit-method`.
- `Final review`: `required` before completion via `wf-docker-independent-final-review-method`.
- `Triple review`: `required` before completion via `audit-protocol-triple-review` and additive only; it does not replace critique.

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** the blocker changes a reusable cross-stack contract, introduces the first dependent-capability rule in the project, and affects a release-critical journey.
- **Impact signals in scope:** `cross-module blast radius`, `public contract/schema/api`, `tenant capability / identity preference boundary`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `assumptions preview`, `execution plan summary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `pending post-implementation`
- **Critique lenses:** `correctness`, `structural-soundness`, `risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** integrated two critique corrections before execution approval:
  - named `agenda_and_action_planner_module.md` as the single authoritative contract home for the reusable fixed-reference core (`D-REF-09`; ordered step `2`)
  - froze the minimal disabled-resolution payload for preserved provenance plus disabled reason signaling (`D-REF-10`; ordered step `3`)
- **Evidence / reference:** `.delphi_orchestration/orch-20260420/reviews/reference-location/critique/merge.md`

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `foundation_documentation/project_constitution.md` | The blocker is freezing whether this capability belongs in a reusable boundary now. | Package-capable boundaries when reuse is plausible. | Hardcoding a reusable contract straight into host-only code. | Anchors the package/lib-first decision. |
| `foundation_documentation/modules/system_architecture_principles.md` | The new capability attaches to the typed profile registry model. | Registry-owned capability authority. | Inventing ad hoc subtype inheritance or freeform metadata. | Keeps the rule inside canonical capability governance. |
| `foundation_documentation/modules/map_poi_module.md` | `is_poi_enabled` already has authoritative downstream semantics. | POI capability as a real enabling/disabling contract. | Treating `is_poi_enabled` as advisory-only. | Provides the precedent for dependent-capability enforcement. |
| `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php` | This is the current write-path surface for Account Profile type capabilities. | Deterministic capability normalization/merge behavior. | UI-only enforcement without backend guardrails. | Points to the first implementation owner for the dependency rule. |
| `flutter-app/lib/domain/app_data/location_origin_settings.dart` | The main proximity TODO will extend the current Flutter-side origin contract. | Current local/device baseline until the next layer lands. | Replacing the baseline without a frozen shared core. | Keeps the downstream consumer work anchored to existing origin models. |
