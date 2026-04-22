# Title
Store Release: Proximity Preferences and Location-Origin Persistence

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Reclassified on:** `2026-04-18`
- **Previous lane:** `foundation_documentation/todos/active/vnext/TODO-vnext-proximity-preferences-and-location-origin.md`
- **Why this moved into store release:** the MVP/local baseline is already real and release-relevant. The remaining gap is now launch hardening: establish canonical identity-backed persistence for the user's Home proximity preference and explicit location-origin override.

## Context
The current runtime already has a canonical `LocationOriginService`, local/device persistence for Home radius, and local/device persistence for Home location-origin settings. That baseline is intentionally limited: it is local-first, Home-only, and automatic. This TODO exists for the next layer only: move proximity preference ownership to an identity-backed contract that survives anonymous-to-authenticated progression, and expose a profile-owned editor for a first-class manual "Minha localização" origin.

This slice must preserve the delivered V1 behavior while defining the launch-ready follow-up contract for users planning around a future place, not only their current live coordinate.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** the remaining work is one cohesive contract-definition slice: freeze identity ownership, merge semantics, editor ownership, and rollout boundary before orchestration starts.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the problem is already narrow and bounded by an existing local baseline plus one explicit release follow-up objective.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation. Secondary modules may still be touched when they are subordinate to that same slice.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update the TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Release-Critical`, `Cross-Stack`, `Reopened-Dependency-Gap`, `Blocked-By-Reference-Location`
- **Next exact step:** reconcile this TODO after the reference-location blocker closes its disabled-resolution payload and cross-stack evidence gaps.

## Scope
- [ ] Define one canonical identity-owned preference model for Home proximity settings (`max_distance_meters` + location-origin mode/payload).
- [ ] Persist that preference for both authenticated users and anonymous/device-bound identities, with explicit anonymous-to-authenticated merge semantics.
- [ ] Define the Profile-owned surface where the user can view and edit the active origin/radius preference.
- [ ] Consume the blocker-defined reusable reference-location core so the release contract supports `live_device_location` plus a fixed-reference lane whose mandatory first source is `manual_coordinate` and whose schema is already compatible with future `entity_reference` sources.
- [ ] Freeze the stored fixed-reference shape through the blocker-owned schema: exact coordinate snapshot plus optional label/provenance metadata, with `manual_coordinate` mandatory now and `account_profile/hotel` compatibility preserved for the next source lane.
- [ ] Define backend/local sync semantics so the current device-local baseline becomes a mirror/seed instead of a competing source of truth.
- [ ] Keep the first consumer surface bounded to **Home only** while documenting how copy/messages migrate toward backend-provided contract fields with deterministic Flutter fallback.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Blocker Notes
- [ ] `DEP-01` This TODO is blocked by `foundation_documentation/todos/active/store_release_android/TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md`, which was reopened on 2026-04-22 because the disabled-resolution payload and cross-stack evidence are incomplete.
- [ ] Orchestration must resume only after the blocker proves read/write disable semantics for entity-backed references and the required cross-stack test floor.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863; flutter-app: reconcile/delphi-store-release-20260420 @ fa31acca`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Identity-backed Home proximity preference contract + merge path + profile editor baseline | `laravel-app: reconcile/delphi-store-release-20260420 @ f89e863; flutter-app: reconcile/delphi-store-release-20260420 @ fa31acca` | `laravel-app: PR #158 (merged to dev 2026-04-21); flutter-app: PR #236 (merged to dev 2026-04-20)` | `<pending>` | `n/a` | `Reopened; dependent blocker incomplete` |

## Retroactive Audit Finding (2026-04-22)

- **Audit outcome:** `reopened`
- **Reason:** the TODO depends on the reference-location blocker as delivered, but that blocker was reopened because the disabled-resolution payload is not yet demonstrably implemented.
- **Recovered evidence:** the identity-backed preference path has meaningful implementation evidence in `lib/infrastructure/repositories/proximity_preferences_repository.dart`, `lib/infrastructure/dal/dao/laravel_backend/proximity_preferences_backend/laravel_proximity_preferences_backend.dart`, `lib/infrastructure/dal/dto/proximity_preference_dto.dart`, `lib/infrastructure/repositories/auth_repository.dart`, `lib/presentation/tenant_public/profile/screens/profile_screen/controllers/profile_screen_controller.dart`, and `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`.
- **Blocking gap:** this TODO cannot satisfy the DoD item requiring the blocker-owned reusable fixed-reference core and dependent-capability blocker to be satisfied until `TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md` returns to guard-green status.
- **Required closure before promotion:** rerun the dependency check after the blocker closes, add direct repository/merge tests where missing, and add a Completion Evidence Matrix with one row per DoD/Validation criterion.

## Out of Scope
- [ ] Replacing the delivered V1 local/device persistence path before the identity-backed path exists.
- [ ] Changing the automatic MVP outside-range fallback already governed by `TODO-v1-home-location-origin-reference-mode.md`.
- [ ] Applying the persisted location-origin behavior to Discovery, Map, generic Event Search, or other geo consumers in this slice.
- [ ] Full trip-planning flows beyond proximity preferences.
- [ ] New public IA or unrelated profile-area redesign.

## Dependencies & Sequencing
- [ ] `DEP-01` `foundation_documentation/todos/active/store_release_android/TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md` must deliver the reusable fixed-reference core, entity provenance schema, and dependent-capability semantics before this TODO can close.
- [ ] `DEP-02` The first user-facing rollout for this TODO may stay manual-coordinate first, but it must not bypass the blocker-owned entity-reference contract and test floor.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** identity-owned persistence contracts, anonymous merge rules, local-cache sync, Profile-area editor ownership, backend/environment copy fields, Home-only consumer wiring, and downstream adoption of the blocker-defined fixed-reference core needed for the same release conversation.
- **Must update or split the TODO:** broader geo-consumer unification, new trip-planning capabilities, shipping a generic entity-picker/account-profile hotel flow as a first-class surface, or any expansion that makes Discovery/Map/Search a first-class delivery objective in the same slice.

## Definition of Done
- [ ] The V1 vs release-follow-up boundary is explicit: V1 remains local/device-only until this slice lands, and the next layer is identity-backed.
- [ ] One canonical ownership model exists for authenticated + anonymous proximity preference persistence and merge.
- [ ] Manual map-picked location is defined as a first-class user-owned origin, not only a fallback.
- [ ] The reusable fixed-reference core and dependent-capability blocker are satisfied and consumed rather than redefined locally inside this TODO.
- [ ] The first rollout boundary remains **Home only**.
- [ ] Profile ownership, sync semantics, and copy ownership path are explicit enough to drive bounded implementation work later.

## Validation Steps
- [ ] Manual doc review against `foundation_documentation/todos/completed/TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md` and `foundation_documentation/todos/completed/TODO-v1-canonical-location-origin-policy-across-app.md`.
- [ ] Repo audit against current Flutter local persistence/code paths (`LocationOriginService`, `AppDataRepository`, current tests) so the release follow-up contract does not overwrite delivered MVP behavior.
- [ ] Manual doc review against `foundation_documentation/modules/map_poi_module.md` and `foundation_documentation/modules/agenda_and_action_planner_module.md` to keep radius/origin semantics coherent with existing backend-owned geo filtering.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `none required for backlog capture` | This TODO is being normalized as planning/execution truth only. | `healthy` | `2026-04-20` | Local repository + documentation inspection | `n/a` |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Identity merge, local-cache fallback, and Home-only regression protection need explicit test-hardening review during execution. | `flutter-app`, `laravel-app`, tests | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `full Plan Review Gate before execution approval + one post-validation checkpoint`
- **Why this level:** the slice is conceptually cohesive, but it crosses Flutter + Laravel contracts, anonymous/authenticated identity semantics, and a critical Home journey.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets (module sections):**
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/tenant_home_composer_module.md`
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-PROX-00` The delivered V1 baseline remains valid: Home radius and location-origin persistence stay local/device-side until the identity-backed layer from this TODO is implemented. Module refs: `HOM-05`, `HOM-06`, `AGD-04`, `AGD-06`.
- [x] `D-PROX-01` The release-follow-up preference model must be identity-backed and survive anonymous-to-authenticated merge; local-only persistence is no longer the target architecture for this next layer. Module refs: `HOM-06`, `TODO-v1-home-agenda-radius-persistence-and-sheet-polish.md`.
- [x] `D-PROX-02` The editor for these preferences belongs to the Profile area, not to the Home header/info affordance. Module ref: `No Prior Decision` (already stated by this TODO's current product framing).
- [x] `D-PROX-03` A manual map-picked place is a first-class primary origin for Home proximity experiences; it is not a fallback-only mode. Module refs: `TODO-v1-canonical-location-origin-policy-across-app.md` (`D-17` follow-up) + `map_poi_module.md` shared location contract.
- [x] `D-PROX-04` Backend persistence for these preferences is an identity-owned application aggregate in the host app, not a `belluga_settings` tenant/landlord settings-kernel namespace. Rationale: settings kernel is tenant/landlord-scoped configuration, while this slice is user/anonymous identity state that must merge across identity progression. Module refs: `project_constitution.md` reuse doctrine + `TODO-v1-settings-kernel-package.md`.
- [x] `D-PROX-05` Anonymous preference data must live in a dedicated proximity-preference aggregate linked to the anonymous identity, not as ad hoc fields on the anonymous identity core document. The same aggregate model must later transfer/merge to authenticated ownership. Module ref: `No Prior Decision`.
- [x] `D-PROX-06` Live-device mode persists only the selected mode flag; the backend must not store the user's last live coordinate as durable preference state. Runtime resolution remains local/device-driven, while manual-map mode persists the chosen fixed coordinate payload. Module refs: `LocationOriginService` current behavior + privacy/minimality principle for identity state.
- [x] `D-PROX-07` Backend state is authoritative once present, but device-local persistence remains the runtime mirror/seed for offline continuity and anonymous-first bootstrapping until sync occurs. Module refs: `HOM-05`, `HOM-06`, `AppDataRepository` tests, `onboarding_flow_module.md` anonymous-first posture.
- [x] `D-PROX-08` Manual-map mode persists the exact user-selected coordinate. This precision is intentional because the feature models explicit trip-planning/user-chosen reference places rather than passive live-location history. Module ref: `No Prior Decision` (user decision captured on 2026-04-20).
- [x] `D-PROX-09` Persisted radius preference remains distinct from tenant-configured `map_ui.radius` bounds/defaults; user preference selects within tenant-owned constraints and never rewrites tenant configuration. Module refs: `HOM-05`, `AGD-06`, `tenant_admin_module.md`, `system_roadmap.md` (`/api/v1/environment`).
- [x] `D-PROX-10` This release lane will move location-origin copy/messages toward backend-provided contract fields where useful, but Flutter must preserve a deterministic fallback path during rollout or partial-backend adoption. Module refs: `flutter_client_experience_module.md` + current TODO baseline.
- [x] `D-PROX-11` The reusable fixed-reference core is now blocker-owned by `TODO-store-release-reference-location-core-and-dependent-capability-guardrails.md`; this TODO consumes that shared contract instead of redefining package boundary, source taxonomy, or dependent-capability semantics locally. Module refs: `project_constitution.md` + blocker TODO.
- [x] `D-PROX-12` Entity-backed fixed references use generic provenance metadata (`source_kind`, `entity_namespace`, `entity_type`, `entity_id`, label/source display metadata, coordinate snapshot`). The first approved downstream source shape is `account_profile/hotel`, but the main rollout may keep that CTA deferred. Module refs: blocker TODO `D-REF-03` / `D-REF-04`.
- [x] `D-PROX-13` Entity-backed fixed references must honor the blocker-owned dependent-capability rule: effective reference eligibility is disabled whenever `is_poi_enabled=false`, including when resolving previously stored references. Module refs: blocker TODO `D-REF-05` / `D-REF-06`.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `HOM-05` | Home Agenda radius persists as a local/device preference and is Home-only in V1. | `Preserve` | `foundation_documentation/modules/tenant_home_composer_module.md` |
| `HOM-06` | Home surfaces canonical location-origin result while effective origin remains locally persisted/device-side in MVP. | `Preserve` | `foundation_documentation/modules/tenant_home_composer_module.md` |
| `AGD-04` | `LocationOriginService` owns canonical effective-origin selection and locally persists `mode + reason` in MVP. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` |
| `AGD-06` | Persisted radius preference is Home-only in V1 and does not automatically retune other consumers. | `Preserve` | `foundation_documentation/modules/agenda_and_action_planner_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-PROX-00` Preserve the delivered local/device MVP baseline until the identity-backed layer exists.
- [x] `D-PROX-01` Identity-backed persistence + anonymous-to-authenticated merge is mandatory for the next layer.
- [x] `D-PROX-02` Editor ownership stays in the Profile area.
- [x] `D-PROX-03` Manual map-picked origin is a first-class primary mode.
- [x] `D-PROX-04` This is identity-owned application state, not tenant settings-kernel configuration.
- [x] `D-PROX-05` Anonymous/authenticated persistence should use the same dedicated preference aggregate model.
- [x] `D-PROX-06` Live-device mode stores only the mode, not a durable live-coordinate snapshot.
- [x] `D-PROX-07` Backend is authoritative; local persistence remains the mirror/seed.
- [x] `D-PROX-08` Manual-map mode stores the exact user-selected coordinate.
- [x] `D-PROX-09` Radius preference remains distinct from tenant bounds/defaults.
- [x] `D-PROX-10` Backend-provided copy is allowed, but Flutter fallback remains mandatory during rollout.
- [x] `D-PROX-11` Reusable fixed-reference core/package boundary and dependent-capability semantics are owned by the blocker TODO.
- [x] `D-PROX-12` Entity-backed fixed references use the blocker-owned generic provenance schema, with `account_profile/hotel` as the first approved downstream source shape.
- [x] `D-PROX-13` Entity-backed fixed references become disabled when `is_poi_enabled` prerequisites are not satisfied, including for previously stored references.

## Questions To Close
- [ ] Which existing Profile-area route/shell should host the new editor without opening a wider IA redesign?
- [ ] Can the first orchestration slice reuse current anonymous bootstrap timing safely, or does the sync/write path need explicit first-run sequencing guards?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Current `LocationOriginService` + `AppDataRepository` behavior is the canonical local mirror baseline and must remain the fallback/runtime seed during rollout. | `lib/infrastructure/services/location_origin_service.dart`; `test/infrastructure/repositories/app_data_repository_location_origin_test.dart`; `HOM-06`; `AGD-04` | The delivery would need a deeper baseline re-audit before planning implementation. | `High` | `Keep as Assumption` |
| `A-02` | Anonymous identity bootstrap already exists in the current app/runtime and can back a dedicated preference aggregate without inventing a new identity posture. | `onboarding_flow_module.md`; `invite_and_social_loop_module.md`; completed anonymous-token alignment TODOs | The slice would need a different ownership/merge story and likely split by identity posture. | `Medium` | `Keep as Assumption` |
| `A-03` | Profile-area ownership can be introduced without redefining Home IA or generic map/search ownership in the same release slice. | Existing TODO framing + `TODO-v1-canonical-location-origin-policy-across-app.md` deferred follow-up note (`D-17`) | The slice would need a broader product/IA framing before implementation. | `Medium` | `Keep as Assumption` |
| `A-04` | The first orchestration slice can keep hotel/entity reference selection UX deferred while still consuming the blocker-owned reusable core and dependent-capability tests underneath. | User guidance on `2026-04-20` + blocker TODO scope. | The release slice would need a broader UX/surface rollout before orchestration starts. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/modules/**`
- `flutter-app/lib/domain/**`
- `flutter-app/lib/infrastructure/**`
- `flutter-app/lib/presentation/**`
- `laravel-app/app/**`
- `laravel-app/routes/**`
- `laravel-app/tests/**`

### Ordered Steps
1. Inventory current Flutter/Laravel ownership of Home radius, location-origin persistence, and anonymous/authenticated identity progression.
2. Consume the blocker-frozen reusable fixed-reference core, including exact-coordinate snapshot rules, generic provenance metadata, and dependent-capability semantics for future entity references.
3. Freeze the exact aggregate/API contract for identity-backed proximity preferences, including merge semantics for manual-coordinate fixed references in the first rollout.
4. Define merge, sync, and fallback semantics so local/device persistence becomes a mirror/seed rather than a competing source of truth, while preserving disabled resolution behavior for future entity-backed references.
5. Define the Profile-area editor boundary and Home-only rollout wiring without broadening other geo consumers or prematurely forcing the hotel/entity selection UX into the first surface.
6. Promote module/docs contract changes and package the slice into bounded implementation tracks for orchestration.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** identity merge, local-cache fallback, and Home-only behavior boundaries are easy to regress silently without explicit fail-first coverage.
- **Fail-first target(s) (when required):** Laravel feature tests for anonymous/authenticated preference ownership + merge, and Flutter repository/controller tests for local-mirror fallback and Home-only consumer behavior.

### Runtime / Rollout Notes
- Local/device persistence remains the runtime fallback until backend sync completes.
- Anonymous-to-authenticated merge must be deterministic and idempotent.
- Backend-provided copy fields must not break Flutter fallback messaging when absent.

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
- **Issue ID:** `ARCH-PROX-01`
  - **Severity:** `high`
  - **Evidence:** Current canon explicitly keeps persistence local/device-side for MVP only and defers identity-backed ownership + profile editing to this follow-up slice; the settings-kernel baseline is tenant/landlord scoped, not user/identity scoped.
  - **Why it matters now:** if this slice lands on the wrong ownership model, the project will either entangle user preference state with tenant configuration or create a second, incompatible identity-preference path that is hard to merge later.
  - **Option A (Recommended):** Use one identity-owned proximity-preference aggregate that serves anonymous and authenticated identities through explicit merge semantics, while keeping tenant config (`map_ui`) separate.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Reuse tenant settings-kernel surfaces or inline fields on existing identity documents opportunistically.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** Keep local/device-only persistence as the effective long-term model.
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

- **Issue ID:** `ARCH-PROX-02`
  - **Severity:** `medium`
  - **Evidence:** Existing canon deliberately keeps the current persisted radius and effective-origin behavior Home-only; broader geo consumers remain separate.
  - **Why it matters now:** implementation can easily smuggle Discovery/Map/Search alignment into the same slice and blow up orchestration scope.
  - **Option A (Recommended):** Keep the rollout strictly Home-only and defer other geo consumers to a dedicated follow-up after this identity-backed baseline lands.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Opportunistically align Map/Discovery/Event Search while touching the shared location-origin contract.
    - **Effort:** `high`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `mixed`
    - **Structural soundness impact:** `mixed`
  - **Option C (Do Nothing):** Leave scope ambiguous and decide per touched screen during implementation.
    - **Effort:** `none`
    - **Risk:** `high`
    - **Blast radius:** `cross-stack`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** `Option A`

### Failure Modes & Edge Cases
- [ ] Backend identity-backed preference and local/device preference diverge without deterministic precedence or merge behavior.
- [ ] Manual-map mode leaks into generic geo consumers even though the release slice is Home-only.
- [ ] Backend persists live user coordinates as durable preference state, creating unnecessary privacy risk and unstable semantics.
- [ ] Anonymous-to-authenticated merge drops the user's selected radius or fixed manual point.

### Residual Unknowns / Risks
- [ ] Exact-coordinate persistence increases privacy sensitivity and must remain isolated to explicit manual-map mode rather than bleeding into live-location history.
- [ ] Profile-area hosting surface for the editor is not yet frozen.

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
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `f37acaea8184` (`2026-04-20`)

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-checks the TODO complexity section. |
| `blast_radius` | `cross-stack` | Flutter + Laravel + identity progression are all in scope. |
| `behavioral_change_or_bugfix` | `yes` | This slice changes persisted preference behavior and formalizes new user-controlled semantics. |
| `changes_public_contract` | `yes` | Backend/API/DTO/editor contract changes are part of the slice. |
| `touches_auth_or_tenant` | `yes` | Anonymous/authenticated identity merge is core to the contract. |
| `touches_runtime_or_infra` | `no` | No queue/runtime/infra surface is currently required by the planning contract. |
| `touches_tests` | `yes` | Merge, fallback, and consumer-boundary tests are mandatory. |
| `critical_user_journey` | `yes` | Home proximity/origin behavior is a launch-critical tenant-public journey. |
| `release_or_promotion_critical` | `yes` | This TODO sits in the store-release lane. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-PROX-01` is high severity. |
| `explicit_three_lane_request` | `no` | Triple external audit is not explicitly required right now. |

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
- **Why this decision:** The TEACH audit floor classified this TODO as expanded-risk because it changes identity-owned behavior, auth/tenant semantics, public contracts, and a critical release journey.
- **Impact signals in scope:** `cross-module blast radius`, `public contract/schema/api`, `auth/tenant`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline`, `approved scope boundary`, `assumptions preview`, `execution plan summary`, `issue cards`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `pending post-implementation`
- **Critique lenses:** `correctness`, `structural-soundness`, `risk`
- **Critique status:** `not_run`
- **Findings summary:** `none yet`
- **Evidence / reference:** `n/a`
