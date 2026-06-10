# TODO: Fast Follow - Tenant-Public Location Permission and Origin Bootstrap Boundary

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The `v0.2.0+8` tenant-public bootstrap fix closed the current release blocker, and the external Claude `fable` audit on `2026-06-10` confirmed the chosen direction should be kept. The same audit also surfaced a non-blocking but real boundary gap: tenant-public startup/location flows still allow permission ownership to drift outside the canonical `/location/permission` boundary.

Concrete residuals:
- Home initial open can still trigger browser geolocation prompt from agenda-origin warm-up instead of through the canonical permission route.
- `resolveUserLocation()` still defaults `requestPermissionIfNeeded=true`, which makes accidental prompting easier in non-owning callers.
- the web post-grant reentry contract is not fully ratified in docs/code (`conditional` wording vs effectively `always reload on web after permission grant`).
- the first-grant flow still uses a timing heuristic while waiting for location publication.
- the web map-entry helper still carries a top-level mutable mutex and hard-coded `/mapa` reentry shape instead of a router-scoped continuation contract.

These are not release blockers for `v0.2.0+8` because the current bootstrap root-cause fix is green and the first permission-granted map entry no longer fails. They still require an explicit fast-follow owner because they affect startup clarity, permission ownership, and future regressions.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-fast-follow-tenant-public-location-permission-origin-boundary`
- **Why this is the right current slice:** this is one bounded continuation of the tenant-public bootstrap work, focused on who owns permission prompting and how origin readiness is completed after permission grant.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the review already isolated the remaining contract drift and named the touched ownership surfaces; a separate feature brief would only restate the same evidence.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- It owns tenant-public location-permission ownership and post-grant origin-bootstrap continuation for public Home/Map startup flows.
- It must preserve the already-approved `v0.2.0+8` bootstrap direction: anonymous-authenticated public reads remain canonical, and same-origin document reentry is still allowed on web if that remains the chosen ownership boundary after this cleanup.
- It does **not** own anonymous-token self-healing after server invalidation; that is routed separately into post-release hardening.
- It does **not** reopen identity-backed proximity preferences, manual reference locations, or broader geo-capability product work.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** freeze the canonical rule for who may request geolocation in tenant-public startup flows and whether web post-grant reentry is always-on or explicitly conditional.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** this owner was opened directly from the `v0.2.0+8` Claude/Fable review as a real but non-blocking follow-up slice.
- **Exit condition:** non-owning consumers can no longer prompt geolocation implicitly, origin readiness no longer relies on timer heuristics, and browser/runtime evidence proves the chosen post-grant contract.

## Scope
- [ ] Freeze one canonical tenant-public permission-request owner for browser geolocation prompts.
- [ ] Ensure non-owning consumers such as Home agenda warm-up and map origin resolution cannot request permission implicitly.
- [ ] Change location-resolution defaults so permission prompting is explicit opt-in, not ambient behavior.
- [ ] Replace the current post-grant timing heuristic with a deterministic origin-readiness signal or equivalent owner-driven continuation contract.
- [ ] Ratify and implement the web post-grant reentry rule, including continuation/argument preservation if reentry remains the chosen owner boundary.
- [ ] Add regression coverage for Home initial open, permission-granted map entry, and route-owned continuation semantics.

## Out of Scope
- [ ] Canonical 401-driven anonymous-token invalidation/reissue after a previously ready session becomes stale.
- [ ] Identity-backed proximity preference persistence or profile-owned reference-location editing.
- [ ] Reopening anonymous web policy into raw unauthenticated public reads.

## Definition of Done
- [ ] Home initial open no longer triggers browser geolocation prompt outside the canonical permission boundary unless that behavior is explicitly approved and documented.
- [ ] Location/origin resolution no longer requests permission by default from non-owning callers.
- [ ] The post-grant continuation path no longer depends on a time-based heuristic to guess when origin publication completed.
- [ ] Browser/runtime evidence proves the chosen web post-grant contract and preserves the correct continuation target.
- [ ] Tests fail if a non-owning public flow regresses into implicit prompting or unstable post-grant continuation.

## Validation Steps
- [ ] Add fail-first Flutter tests around permission ownership and post-grant continuation.
- [ ] Run focused browser/runtime proof for Home initial open and permission-granted map entry on the served tenant-public bundle.
- [ ] Reconcile any module/policy wording drift so docs and implementation state the same post-grant rule.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<pending>`, `flutter-app:<pending>`, `foundation_documentation:main`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `tenant-public location-permission and origin-bootstrap boundary` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** location-permission owner cleanup, continuation/reentry ownership, route argument preservation, origin-readiness signaling, browser/runtime proofs, and module/policy wording sync required for the same boundary.
- **Must update or split the TODO:** anonymous-token self-healing, proximity-preference product expansion, or broader startup/auth boundary redesign outside location-permission ownership.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Browser/runtime proof must validate the real permission-grant path after the ownership cleanup. | `flutter-app`, `tools/flutter/web_app_tests/**` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the slice is cohesive but crosses startup routing, geolocation ownership, runtime/browser behavior, and regression tests.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant-public startup/location permission ownership`
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`
  - `agenda_and_action_planner_module.md`

## Decisions (Resolved Before Freeze)
- [ ] `D-01` This TODO is routed from the `v0.2.0+8` bootstrap/Fable review as `follow-up-fast-follow`, not `release-blocker`.

## Questions To Close
- [ ] Should web post-grant reentry remain always-on after permission grant, or should it become explicitly conditional once origin publication can be observed deterministically?
- [ ] What is the narrowest canonical owner for origin-readiness signaling after permission grant: route-level continuation, service-level readiness stream, or a dedicated startup coordinator?

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The remaining issue is ownership drift, not a need to reopen the already-approved anonymous-authenticated bootstrap architecture. | Claude `fable` review verdict `direction=keep` after the `v0.2.0+8` bootstrap fix. | The TODO would need to reopen release-blocker architecture instead of follow-up cleanup. | `High` | `Keep as Assumption` |
| `A-02` | The Home prompt path and the first-grant map path share one permission/origin ownership boundary. | Fable findings on agenda warm-up prompting, default permission opt-in, heuristic wait, and web reentry. | The work may need to split into two narrower TODOs. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/application/startup/**`
- `flutter-app/lib/infrastructure/repositories/user_location_repository.dart`
- `flutter-app/lib/infrastructure/services/location_origin_service.dart`
- `flutter-app/lib/presentation/shared/init/**`
- `flutter-app/lib/presentation/tenant_public/home/**`
- `flutter-app/lib/presentation/tenant_public/map/**`
- `tools/flutter/web_app_tests/**`
- `foundation_documentation/modules/**`

### Ordered Steps
1. Freeze the canonical permission-request owner and the approved web post-grant contract.
2. Remove implicit permission prompting from non-owning callers and make permission request opt-in only.
3. Replace heuristic post-grant waiting with deterministic continuation/origin readiness.
4. Add fail-first tests and served-bundle runtime proof.
5. Promote the finalized rule into module/policy truth.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** permission/request-order regressions are easy to hide behind successful end states unless the failing order is pinned explicitly.
- **Fail-first target(s) (when required):** Home initial-open prompt ownership and permission-granted map entry continuation/order.

### Runtime / Rollout Notes
- No rollout flag expected.
- The final rule must stay fail-closed: no public flow may silently issue location-dependent requests before canonical permission/origin ownership is satisfied.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Root instruction source for downstream TODO work. | Evidence-first execution and no implementation before explicit approval. | Treating this follow-up TODO as auto-authorized. | This TODO remains documentation/routing only until explicit `APROVADO`. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | Required to declare profile and scope before task work. | Operational-coder ownership with browser/runtime assurance support. | Treating location-permission runtime proof as optional. | The TODO declares `flutter` scope with an assurance handoff. |
| `r0/wf-docker-todo-driven-execution-method/SKILL.md` | User requested canonical TODO routing for non-blocking review findings. | Tactical TODO structure, approval gate, and delivery-gate discipline. | Converting review findings into vague notes instead of executable owners. | This follow-up owner is bounded and awaits approval before implementation. |
| `foundation_documentation/todos/README.md` | Governs follow-up lane placement and review-finding classification. | `follow-up-fast-follow` routing under `active/fast_follow_required/followup/`. | Parking a real fast-follow issue only in chat or in the source TODO. | This finding is split into the approved follow-up bucket. |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Startup, public navigation, and permission-owned public flows must remain canonical. | Route-owned startup and permission boundaries. | Hidden geolocation prompting from non-owning public consumers. | Final implementation must promote the rule into module truth. |
| `foundation_documentation/modules/agenda_and_action_planner_module.md` | Canonical location-origin behavior and origin ownership live here. | Deterministic location-origin selection semantics. | Reintroducing timer-based or ambient permission/request behavior as de facto canon. | The follow-up must converge permission and origin ownership with this module. |
