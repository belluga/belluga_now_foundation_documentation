# TODO: Tenant-Public Bootstrap and Startup Boundary Hardening

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user `APROVADO` on `2026-06-10` for implementation under this contract.
- **Approval scope:** implement the current-package owner TODO for tenant-public bootstrap/startup hardening, including the first direct public-route startup rule: cold direct entry into any anonymous-readable tenant-public route must not surface the promotion boundary before user interaction. On Android direct public entry, installed-app handoff may still happen through the canonical `/open-app` handoff boundary; when the app is absent, blocked, or the handoff fails, the browser fallback must remain on the original public route instead of promotion. This absorbed scope covers representative public direct-entry routes such as tenant Home, invite landing, account-profile detail, and event detail; those routes are examples of the rule, not its boundary definition.
- **Exact exclusions:** no reopening of anonymous-web policy beyond the approved first-route exception; no broad Android store/deferred campaign expansion beyond the absorbed startup correction; no fallback to raw unauthenticated tenant-public reads; no redesign of `/baixe-o-app` or explicit CTA/gate conversion UX.
- **Renewed approval required when:** implementation would broaden anonymous-web capability, reopen raw unauthenticated reads, change explicit CTA/gate promotion semantics, or absorb iOS/QR-auth/store-runtime scope.

## Context
`v0.2.0+8` is blocked by a structural tenant-public bootstrap problem, not by an isolated `agenda` bug.

Current evidence already proves:
- tenant-public browsing must remain `anonymous-authenticated`, not raw unauthenticated public reads;
- some served sessions reached protected tenant-public reads without the canonical anonymous identity bootstrap;
- the current request boundary helper can trigger `AuthRepository.init()` from inside protected public requests;
- `AuthRepository.init()` currently owns too much: token/storage restore, stale-token reset, anonymous identity issuance, and post-auth side effects such as proximity-preferences sync;
- the first permission-granted map entry can still fail when bootstrap/order is wrong, even though warm-entry and targeted contract tests are green;
- the startup/open-app decision that had been planned in `v0.2.1+9` is structurally coupled to this bootstrap boundary: startup must deterministically choose between keeping the user on the public HTML/Flutter surface or sending the user into app-promotion/open-app, and the first direct public-route entry is part of that contract;
- the former Android/Instagram concern exposed that Laravel public-shell `web_direct` Android entry currently resolves its failed/no-app browser fallback to promotion instead of the original public route, which violates the desired first-route startup contract.

This TODO exists to establish the canonical ownership boundary for tenant-public identity readiness and startup routing before promotion continues.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-0-plus8-tenant-public-bootstrap-boundary`
- **Why this is the right current slice:** the blocker is concrete, current-package critical, and already narrowed to one cohesive cross-stack boundary: tenant-public bootstrap/readiness plus first-route startup decision ownership.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user explicitly requested that the bootstrap structural fix absorb the startup/open-app initial-entry rule that had been parked in `v0.2.1+9`, then clarified that the exception is not Home-only but applies to the first direct public-route entry before any interaction.

## Contract Boundary
- This TODO owns the canonical tenant-public identity-readiness boundary for protected public requests and the startup decision boundary that depends on it.
- It owns the current-package fix for the first permission-granted map entry only insofar as that failure depends on bootstrap ordering, identity readiness, startup sequencing, or canonical origin handoff.
- It also owns delivery-channel/bootstrap-asset corrections when cache, fingerprint, or service-worker behavior determine whether the published runtime executes the canonical bootstrap path at all, but each absorbed correction must record the exact bootstrap-path causality it closes so unrelated cache/asset work is rejected at the gate.
- It absorbs implementation ownership of the startup rule that had previously been parked in `v0.2.1+9`: cold direct entry into any anonymous-readable tenant-public route must not surface the promotion boundary before user interaction. The named routes in this TODO are representative examples, not an exhaustive whitelist.
- It owns the current-package correction for direct public web entry on Android (`web_direct`) because failed/no-app browser fallback is part of the same first-route startup boundary. Direct entry may still attempt installed-app handoff, but that attempt must reuse the canonical `/open-app` handoff boundary; only the failure fallback destination changes. When that handoff cannot complete, the browser fallback must resolve to the original public route instead of promotion. Explicit CTA/gate flows remain promotion-owned.
- It does **not** own a broad Android store/install/deferred runtime campaign beyond the startup correction absorbed here.
- It does **not** authorize reopening tenant-public anonymous web policy broadly. Guarded routes and guarded actions still promote the app; only the initial direct public-route startup behavior is being absorbed here because it is bootstrap-owned.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `none`
- **Next exact step:** implement the Laravel/Flutter/bootstrap changes inside this owner TODO under the tightened startup boundary contract, then close them with focused runtime/test evidence.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** execution authority is active and the bootstrap/map boundary is being ratified with local/runtime evidence, but the absorbed first-route startup rule, final doc ownership sync, and external review are not fully closed yet.
- **Exit condition:** bootstrap/startup ownership is implemented, runtime evidence proves the first-entry map path and representative first direct public-route behavior, and the TODO is ready to move into `promotion_lane/v0.2.0+8/`.

## Execution Notes
- `2026-06-10` local implementation ratified the narrow tenant-public identity-readiness split so protected public request helpers no longer depend on broad `AuthRepository.init()` side effects.
- `2026-06-10` removed the mutable `LocationPermissionGateRuntime` bypass and replaced the first-grant web flow with route-owned gate results plus document reentry support for the permission-granted map transition.
- `2026-06-10` focused local validation passed for the touched bootstrap/map slices:
  - `fvm dart analyze --format machine`
  - `fvm flutter test --no-pub test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
  - focused suites for `auth_repository_identity_bootstrap`, route guards, `tenant_public_map_entry_flow`, tenant-public backends, and the location-permission controller
  - `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`
- `2026-06-10` served-bundle runtime probe against `https://guarappari.belluga.space` proved the original blocker moved to green for the permission-granted path:
  - click `Permitir localização` transitions from `/location/permission` to `/mapa`
  - the first `/api/v1/map/pois` request includes `origin_lat` and `origin_lng`
  - the first POI response is `200`, JSON-decodable, and returns non-empty `stacks`
  - the public `Não foi possível carregar os pontos de interesse` banner does not appear
- `2026-06-10` external Claude CLI `fable` audit was attempted after the local/runtime proof, but the OAuth-authenticated CLI hit its session limit before review completion (`You've hit your session limit · resets 4:40pm (America/Sao_Paulo)`). No API-key fallback was available, so the audit remains pending rather than being downgraded to `--bare`.
- `2026-06-11` user clarified that the startup exception is broader than Home: the first direct public-route entry must remain free of promotion UI before any interaction, while direct Android entry may still attempt installed-app handoff. The absorbed Android/Instagram concern is specifically the failed/no-app browser fallback resolving to promotion instead of the original public route.
- `2026-06-11` Claude CLI `fable` review follow-ups were incorporated into the owner contract: the exception now has an explicit cold direct-entry criterion, Android direct entry must reuse the canonical `/open-app` handoff boundary, and delivery-channel/bootstrap-asset fixes must name the specific bootstrap-path causality they close.

## Pre-Landed Local Baseline To Ratify During Execution
The closeout tracker already captured bootstrap-related source remediations on `2026-06-10`. They are not yet a delivery claim. This TODO owns the decision to ratify, revise, or replace them under one current-package contract so ingress/build/bootstrap fixes do not float outside tactical ownership.

| Baseline Slice | Surfaces Already Touched Locally | Candidate Scope Link | Ratification Requirement |
| --- | --- | --- | --- |
| `sync-header removal + protected consumer async path` | `flutter-app/lib/infrastructure/dal/dao/laravel_backend/shared/tenant_public_auth_headers.dart`, `flutter-app/lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`, `flutter-app/lib/infrastructure/dal/dao/laravel_backend/invites_backend/laravel_invites_backend.dart`, `flutter-app/lib/infrastructure/dal/dao/laravel_backend/proximity_preferences_backend/laravel_proximity_preferences_backend.dart` | `SCOPE-01`, `SCOPE-02`, `SCOPE-03` | Keep only if execution proves these surfaces now consume one canonical readiness boundary without hidden side effects. |
| `map origin fail-closed hardening` | `flutter-app/lib/infrastructure/dal/dao/laravel_backend/map/laravel_map_poi_http_service.dart`, `flutter-app/lib/infrastructure/repositories/city_map_repository.dart`, `flutter-app/lib/infrastructure/repositories/poi_repository.dart`, `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/controllers/map_screen_controller.dart` | `SCOPE-04` | Keep only if execution proves first-entry map requests wait for canonical origin/identity readiness and do not regress the explicit `continue without location` path. |
| `bootstrap delivery-channel hardening` | `flutter-app/web/flutter_bootstrap.js`, `docker/nginx/prod.conf.template`, `delphi-ai/scripts/flutter/build_web.sh` | `SCOPE-03`, `SCOPE-04` | Keep only if execution proves stale bundles cannot bypass canonical anonymous bootstrap, bootstrap asset URLs rotate deterministically when the bundle changes, and each retained change names the specific bootstrap-path causality it closes. |

## Scope
- [ ] `SCOPE-01` Establish one canonical tenant-public identity-readiness owner for protected public reads and writes that require the anonymous/authenticated bearer.
- [ ] `SCOPE-02` Split identity readiness from unrelated side effects so protected request boundaries no longer depend on a broad `AuthRepository.init()` with incidental post-auth behavior.
- [ ] `SCOPE-03` Route all protected tenant-public consumers through the canonical readiness boundary instead of per-surface bootstrap compensation, and ratify or replace any already-landed bootstrap-delivery changes required so the published runtime actually executes that boundary.
- [ ] `SCOPE-04` Fix the first permission-granted map entry so `/map/filters` and `/map/pois` are issued only after identity readiness and canonical origin resolution are both satisfied, with explicit fail-closed behavior for under-scoped first requests.
- [ ] `SCOPE-05` Absorb and implement the startup decision rule that had previously been parked in `v0.2.1+9`: the first direct tenant-public route entry must stay free of promotion UI before user interaction.
- [ ] `SCOPE-06` Apply `SCOPE-05` to representative public direct-entry routes, including tenant Home, invite landing, account-profile detail, event detail, and same-class anonymous-readable public-shell routes that meet the cold direct-entry criterion. On Android `web_direct`, preserve installed-app handoff when possible by reusing the canonical `/open-app` boundary, but route failed/blocked/no-app browser fallback to the original public route instead of promotion.
- [ ] `SCOPE-07` Preserve the existing route-gated and action-gated anonymous-web promotion contract everywhere else: guarded routes/actions and explicit CTA/gate promotion flows still resolve to canonical app-promotion/open-app behavior.

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
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `flutter-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `laravel-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `foundation_documentation:main`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `current-package blocker fixed and merged through dev`
- **Production-ready threshold for this TODO:** `stage/main promotion plus runtime/browser proof on the approved target`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `bootstrap/startup boundary implementation` | `reconcile/v0.2.0-plus8-cross-stack-20260526@<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `direct public-route startup fallback implementation` | `reconcile/v0.2.0-plus8-cross-stack-20260526@<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `bootstrap delivery-channel ratification (cache/fingerprint/service-worker)` | `reconcile/v0.2.0-plus8-cross-stack-20260526@<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending-ratification` |
| `map first-entry runtime proof` | `reconcile/v0.2.0-plus8-cross-stack-20260526@<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Out of Scope
- [ ] Full Android Play Store/install-referrer/deferred first-open validation after the bootstrap contract lands.
- [ ] iOS deferred/universal-link validation.
- [ ] QR-authenticated web bootstrap/session work.
- [ ] Redesign of `/baixe-o-app`, promotion copy, or store UX beyond the absorbed first-route startup rule.
- [ ] Reopening anonymous web into a general login-capable surface.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** identity-readiness refactor, request-boundary centralization, startup decision ownership, direct public-route handoff suppression before interaction, map first-entry bootstrap ordering, focused Laravel/Flutter/browser tests, ingress/bootstrap cache rules, service-worker cleanup behavior, and build fingerprint rotation, but every delivery-channel/bootstrap-asset change must be justified by a named bootstrap-path causality in this TODO.
- **Must update or split the TODO:** broader web-to-app policy redesign, store/deferred external runtime campaigns, iOS work, QR-auth web, or any change that widens anonymous web capability beyond the already approved contract.

## Definition of Done
- [ ] `DOD-01` Protected tenant-public requests no longer own ad hoc bootstrap semantics; they consume one canonical identity-readiness boundary.
- [ ] `DOD-02` Anonymous identity readiness is separated from unrelated post-auth/bootstrap side effects strongly enough that request boundaries do not trigger incidental sync/hydration work.
- [ ] `DOD-03` First permission-granted map entry does not emit protected map requests before identity readiness and canonical origin resolution complete.
- [ ] `DOD-04` Cold direct entry into any anonymous-readable tenant-public route no longer surfaces the promotion boundary before user interaction; on Android `web_direct`, installed-app handoff still reuses the canonical `/open-app` boundary, but failed/blocked/no-app browser fallback returns to the original public route across representative public routes.
- [ ] `DOD-05` Guarded routes, guarded actions, and explicit CTA/gate promotion flows outside that first-route exception still use the canonical promotion/open-app boundary.

## Validation Steps
- [ ] `VAL-01` Add fail-first coverage proving protected tenant-public request boundaries do not proceed without the canonical identity-readiness gate.
- [ ] `VAL-02` Add fail-first coverage proving the absorbed first-route startup rule: initial anonymous web public-route entry stays in place while guarded routes/actions and explicit CTA/gate promotion still promote.
- [ ] `VAL-03` Add or tighten fail-first coverage for the permission-granted map entry path so first-request order is explicit and deterministic.
- [ ] `VAL-04` Run focused Flutter/Laravel suites for auth repository, startup routing, map/bootstrap ordering, and affected tenant-public backends.
- [ ] `VAL-05` Run browser/runtime evidence against the served bundle to prove request order and startup behavior on the actual target host. For `DOD-03`, the authoritative lane must include the real permission-grant path; if headless grant remains inconclusive, escalate to scripted manual/device-backed evidence with captured request/response order. Warm pre-granted evidence alone cannot close `DOD-03`.
- [ ] `VAL-06` Re-run local CI-equivalent surfaces that cover the touched Flutter/Laravel/browser scope before any promotion claim.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` Protected tenant-public requests no longer own ad hoc bootstrap semantics; they consume one canonical identity-readiness boundary. | `test+review` | `<planned focused backend/client tests + source review>` | `local` | `planned` | Must name the shared owner surface explicitly. |
| `DOD-02` | `Definition of Done` | `DOD-02` Anonymous identity readiness is separated from unrelated post-auth/bootstrap side effects strongly enough that request boundaries do not trigger incidental sync/hydration work. | `test+review` | `<planned auth/bootstrap unit + integration coverage>` | `local` | `planned` | This is the core architecture correction. |
| `DOD-03` | `Definition of Done` | `DOD-03` First permission-granted map entry does not emit protected map requests before identity readiness and canonical origin resolution complete. | `runtime+test` | `<planned focused map tests + served-bundle permission-grant proof with request/response order; escalate to device/manual capture if headless grant remains inconclusive>` | `browser (+device/manual if needed)` | `planned` | Warm pre-granted evidence alone is insufficient; closure must prove the real permission-grant path. |
| `DOD-04` | `Definition of Done` | `DOD-04` The first direct tenant-public route entry no longer surfaces the promotion boundary before user interaction; on Android `web_direct`, installed-app handoff may still occur, but failed/blocked/no-app browser fallback returns to the original public route across representative public routes. | `runtime+test` | `<planned startup/public-entry browser test + focused Laravel/Flutter route evidence>` | `browser` | `planned` | Absorbed from `v0.2.1+9` and refined by the 2026-06-11 user clarification. |
| `DOD-05` | `Definition of Done` | `DOD-05` Guarded routes, guarded actions, and explicit CTA/gate promotion flows outside that first-route exception still use the canonical promotion/open-app boundary. | `runtime+test` | `<planned browser/runtime guarded-route, action-gate, and CTA evidence>` | `browser+device if needed` | `planned` | Ensures the exception does not weaken the broader contract. |
| `VAL-01` | `Validation Steps` | `VAL-01` Add fail-first coverage proving protected tenant-public request boundaries do not proceed without the canonical identity-readiness gate. | `test` | `<planned Flutter/Laravel test additions>` | `local` | `planned` | Coverage must model request order, not only final success. |
| `VAL-02` | `Validation Steps` | `VAL-02` Add fail-first coverage proving the absorbed first-route startup rule: representative initial anonymous web public-route entries stay in place while guarded routes/actions and explicit CTA/gate promotion still promote. | `test` | `<planned startup/public-route route tests>` | `local` | `planned` | Must encode the policy difference explicitly. |
| `VAL-03` | `Validation Steps` | `VAL-03` Add or tighten fail-first coverage for the permission-granted map entry path so first-request order is explicit and deterministic. | `test` | `<planned map/bootstrap tests>` | `local` | `planned` | Required to pin root cause. |
| `VAL-04` | `Validation Steps` | `VAL-04` Run focused Flutter/Laravel suites for auth repository, startup routing, map/bootstrap ordering, and affected tenant-public backends. | `test` | `<planned command matrix below>` | `local` | `planned` | Must include touched suites, not only one slice. |
| `VAL-05` | `Validation Steps` | `VAL-05` Run browser/runtime evidence against the served bundle to prove request order and startup behavior on the actual target host. | `runtime` | `<planned Playwright/runtime probe plus scripted manual/device-backed fallback if the permission-grant prompt cannot be reproduced headlessly>` | `browser (+device/manual if needed)` | `planned` | Must prove served-bundle truth and the real permission-grant path, not only warm re-entry. |
| `VAL-06` | `Validation Steps` | `VAL-06` Re-run local CI-equivalent surfaces that cover the touched Flutter/Laravel/browser scope before any promotion claim. | `test+build` | `<planned CI-equivalent commands>` | `local` | `planned` | Required before moving back toward promotion. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `Published tenant web host` | Browser/runtime proof must hit the actual served bundle that reproduces or closes the bootstrap/startup defect. | `unknown` | `n/a` | `<planned host fingerprint + browser probe>` | Do not accept local-only source truth for runtime closure. |
| `ADB/device lane` | Some guarded-route continuation checks may still need device parity once web/browser behavior is fixed. | `unknown` | `n/a` | `<planned ADB check if needed>` | Keep as supporting lane only if the final contract differs between browser and device. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto` | `operational-coder` | Contract framing is complete and implementation will touch Flutter/Laravel bootstrap and startup surfaces after approval. | `flutter-app/**`, `laravel-app/**`, `foundation_documentation/**` | `planned` |
| `operational-coder` | `assurance-tester-quality` | Final closure depends on browser/runtime proof against the served bundle. | `tools/flutter/web_app_tests/**`, published tenant host, runtime probes | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** one cohesive architectural slice spans Flutter, Laravel, browser/runtime validation, and first-route startup policy, but it remains one current-package blocker rather than a broad program.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` authorization/startup boundary notes
  - `map_poi_module.md` first-entry bootstrap/readiness notes if the final rule becomes more explicit
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`
  - `map_poi_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Tenant-public public reads remain `anonymous-authenticated`; this TODO must not solve the problem by reopening raw unauthenticated reads.
- [x] `D-02` Protected request boundaries may depend only on a canonical identity-readiness owner, not on broad bootstrap methods with incidental side effects.
- [x] `D-03` The current-package fix must prefer cause-root ownership over per-surface compensation. `agenda`, `map`, `discovery`, `invites`, and similar consumers may reuse a shared gate/client, but they must not each invent startup/bootstrap behavior.
- [x] `D-04` The first direct tenant-public route entry is absorbed into this TODO because it is a startup-boundary decision; it must not surface the promotion boundary on startup before user interaction.
- [x] `D-05` The first-route exception does not weaken the broader anonymous-web rule: guarded routes and guarded actions still resolve to the canonical promotion/open-app boundary.
- [x] `D-06` The absorbed first-route startup correction now lives entirely inside this owner TODO; no parallel implementation owner remains for the same boundary.
- [x] `D-07` For the web permission-granted map entry, same-origin fresh-document reentry is the canonical boundary when browser geolocation is not yet reliable on the pre-grant SPA document. This is not a mutable compatibility singleton. It is the current web startup/document owner for finishing canonical location bootstrap after permission grant.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `FCX auth/startup policy` | Anonymous web hard/auth gates promote app; action gates may use the canonical compact promotion modal; first direct public-route entry belongs to the anonymous read-only baseline. | `Supersede (Intentional)` | `foundation_documentation/modules/flutter_client_experience_module.md:76-99` |
- | `INV web-to-app rule` | Guarded routes on anonymous web hand off to canonical app-promotion and `/open-app`; install/open must preserve intent. | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md:497-503` |
- | `policy web-to-app hard gate` | Hard/auth gate on tenant-public web resolves to canonical app-promotion/open-app, not anonymous web login. | `Preserve` | `foundation_documentation/policies/web_to_app_promotion_policy.md:53-118` |
- | `No Prior Decision` | No canonical module decision currently defines a dedicated identity-readiness owner distinct from broader auth/bootstrap side effects. | `Supersede (Intentional)` | Current root-cause analysis in `EPHEMERAL-v0208-canonical-closeout-before-review-20260608.md` and repo code review. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The final architecture must expose one canonical tenant-public identity-readiness boundary.
- [x] `D-02` Request-layer helpers must not remain hidden owners of broad auth/bootstrap side effects.
- [x] `D-03` First-entry map correctness depends on both identity readiness and canonical origin readiness.
- [x] `D-04` Startup must not auto-promote cold direct entry into any anonymous-readable tenant-public route on first open, but Android direct entry may still attempt installed-app handoff through the canonical `/open-app` boundary before falling back to the original public route.
- [x] `D-05` The first-route exception must not erode guarded-route/action promotion behavior elsewhere.
- [x] `D-06` Web permission-grant completion is allowed to re-enter the same-origin document when that is the only reliable way to complete canonical location bootstrap after browser permission grant; any future simplification must preserve the same route-owned/bootstrap-owned ownership and the same first-request guarantees.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The dominant coupling problem is not endpoint-specific; it is the lack of a dedicated tenant-public identity-readiness owner. | `tenant_public_auth_headers.dart` calls `authRepository.init()`, while `AuthRepository.init()` owns token restore, stale reset, anonymous issuance, and sync side effects. | The fix may shrink to a simpler startup-order bug instead of a structural boundary correction. | `High` | `Keep as Assumption` |
| `A-02` | First direct public-route interruption belongs to the same startup boundary as the bootstrap defect and should be absorbed now instead of staying in a later external-validation TODO. | User explicit request on `2026-06-10`, then clarification on `2026-06-11` that the exception is not Home-only and applies before any interaction on representative public direct-entry routes. | Ownership stays split and the same boundary will be changed twice in separate lanes. | `High` | `Keep as Assumption` |
| `A-03` | The first-entry map failure still has a browser/runtime proof burden even after local focused tests. | Manual runtime contradiction and closeout tracker notes remain authoritative. | The TODO may overinvest in browser proof when the remaining failure is already fully unit-covered. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `flutter-app/lib/infrastructure/repositories/auth_repository.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/shared/tenant_public_auth_headers.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/invites_backend/laravel_invites_backend.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/proximity_preferences_backend/laravel_proximity_preferences_backend.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/map/laravel_map_poi_http_service.dart`
- tenant-public protected backends/clients that currently depend on the current helper or on now-removed synchronous token/header access
- `flutter-app/lib/application/startup/**`
- `flutter-app/lib/presentation/shared/init/**`
- `flutter-app/lib/presentation/shared/promotion/**` or the current startup gate owner
- `flutter-app/lib/presentation/tenant_public/map/**`
- `flutter-app/web/flutter_bootstrap.js`
- `docker/nginx/prod.conf.template`
- `delphi-ai/scripts/flutter/build_web.sh`
- `tools/flutter/web_app_tests/map_permission_grant_runtime.readonly.spec.js`
- `laravel-app/app/Http/Controllers/TenantPublicShellController.php`
- `laravel-app/packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php`
- `laravel-app/packages/belluga/belluga_deep_links/src/Http/Web/Controllers/OpenAppRedirectController.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`
- `laravel-app/tests/Feature/Tenants/PublicWebMetadataShellTest.php`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`

### Ordered Steps
1. Define the canonical identity-readiness boundary and the minimal responsibilities it owns.
2. Split bootstrap side effects so request boundaries can depend on readiness without pulling incidental sync/hydration work.
3. Route affected protected tenant-public consumers through the shared boundary.
4. Implement the absorbed first-route startup rule in the startup/promotion path, including `web_direct` failed/no-app fallback semantics.
5. Repair the first-entry map path against the final readiness/origin contract.
6. Revalidate local suites, then runtime/browser proof, then reconcile TODO ownership/docs.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this is a user-visible regression/blocker with already known race/order failure modes.
- **Fail-first target(s) (when required):** protected tenant-public request readiness, anonymous web first-route no-promotion path, Android `web_direct` fallback-to-public-route path, permission-granted map first-entry request order.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Protected tenant-public bootstrap before first read | Controls whether first public screens render or 401/fail. | `shared-android-web` | `both` | `no` | `yes` | focused tests + browser/runtime request-order probe | `n/a` |
| First direct public-route entry stays free of promotion UI | Startup route decision is visible and policy-owned. | `web-only` | `Playwright readonly` | `no` | `yes` | startup/public-route browser proof on served bundle | `n/a` |
| Android `web_direct` preserves app-first but falls back to public route | The absorbed exception must not break installed-app handoff while removing promotion fallback from first direct entry. | `android-only` | `browser + device` | `no` | `yes` | Laravel redirect tests + Android/browser redirect-chain proof | `n/a` |
| Guarded route/action and explicit CTA still promote app | The absorbed exception must not weaken the broader web boundary. | `web-only` | `Playwright readonly` | `no` | `yes` | guarded-route/action/CTA browser proof | `n/a` |
| First permission-granted map entry | Current package blocker with first-request ordering risk. | `shared-android-web` | `both` | `no` | `yes` | focused map tests + served-bundle permission-grant probe; if headless permission grant remains inconclusive, mandatory manual/device-backed capture with request/response order | `n/a` |
| Bootstrap delivery-channel freshness | Published runtime can stay stale and silently bypass the intended bootstrap path if cache/service-worker/fingerprint behavior drifts. | `web-only` | `Playwright readonly` | `no` | `yes` | source review + published bundle fingerprint + asset-cache/runtime probe | `n/a` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app focused bootstrap/startup/map suites` | Primary implementation surface is Flutter bootstrap/startup/map behavior. | `fvm flutter test --no-pub test/infrastructure/repositories/auth_repository_identity_bootstrap_test.dart test/application/startup/app_startup_navigation_coordinator_test.dart test/application/router/support/tenant_public_map_entry_flow_test.dart test/presentation/shared/location_permission/controllers/location_permission_controller_test.dart test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart` | `Local-Implemented` | `planned` | `<pending>` | Final command list may narrow/expand once touched tests are finalized. |
| `flutter-app analyze` | Bootstrap/startup refactor must stay analyzer-clean. | `fvm dart analyze --format machine` | `Local-Implemented` | `planned` | `<pending>` | Required. |
| `flutter-app web build` | Startup/home/browser proof depends on the served bundle and its rotated bootstrap asset URLs. | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | `Local-Implemented` | `planned` | `<pending>` | Required before browser proof and before ratifying delivery-channel bootstrap fixes. |
| `browser/runtime navigation proof` | Closure depends on served-bundle truth, not only local tests. | `bash tools/flutter/run_web_navigation_smoke.sh readonly` | `promotion` | `planned` | `<pending>` | Use focused spec/tag selection for the absorbed startup + map paths. |
| `laravel-app targeted tests` | First-route startup now includes canonical `web_direct` fallback behavior in Laravel. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Feature/Tenants/PublicWebMetadataShellTest.php` | `Local-Implemented` | `planned` | `<pending>` | Required for the absorbed Android/public-entry slice. |

### Runtime / Rollout Notes
- The browser lane must prove the currently served bundle fingerprint before any closure claim.
- The permission-grant map blocker cannot close on warm/pre-granted entry alone. If automated headless grant continues to stall on `/location/permission`, the authoritative runtime proof must switch to scripted manual/device-backed capture rather than downgrading the proof standard.
- If startup or map behavior diverges between browser and device after the shared contract is fixed, add the minimal ADB/device lane necessary to prove the divergence instead of assuming parity.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** `high`
  - **Evidence:** [tenant_public_auth_headers.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/dal/dao/laravel_backend/shared/tenant_public_auth_headers.dart:26), [auth_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/auth_repository.dart:92)
  - **Why it matters now:** protected request boundaries currently pull a broad bootstrap method with incidental side effects, which hides structural bugs and couples unrelated behavior.
  - **Option A (Recommended):** introduce a dedicated tenant-public identity-readiness owner and make request boundaries depend on it.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** keep the helper but shrink `AuthRepository.init()` until it is effectively the readiness gate.
    - **Effort:** `medium`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `neutral`
  - **Option C (Do Nothing):** keep request-triggered broad bootstrap and patch individual failing surfaces.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`

- **Issue ID:** `ARCH-02`
  - **Severity:** `high`
  - **Evidence:** the former `v0.2.1+9` startup-validation TODO and the former Instagram direct-entry TODO had split ownership around first-route startup versus Android direct-web fallback even though both are startup-boundary logic.
  - **Why it matters now:** leaving first-route behavior in parallel TODOs would split one startup boundary across multiple lanes and force rework.
  - **Option A (Recommended):** absorb the first-route startup rule plus Android `web_direct` fallback semantics into the current bootstrap TODO and retire overlapping TODO owners.
    - **Effort:** `low`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** keep split ownership and coordinate the same startup surface across both TODOs.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C (Do Nothing):** defer the first-route startup rule and keep the current package blocker isolated to map/bootstrap only.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `module`
    - **Maintenance burden:** `medium`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`

### Failure Modes & Edge Cases
- The first request could remain ordered correctly in unit tests but still fail in the served bundle because startup or browser permission timing differs.
- A naive first-route exception could accidentally weaken guarded-route/action promotion behavior and silently expand anonymous web capability.
- Refactoring `AuthRepository.init()` could break authenticated bootstrap, stale-token cleanup, or post-auth hydration if the ownership split is not explicit.

### Residual Unknowns / Risks
- Whether the final shape should be a dedicated new gate/service or a smaller `AuthRepository` split with the same public API remains an implementation decision, but the architectural owner boundary is fixed by this TODO.
- Device parity for the first-entry map path may still require an ADB lane after browser proof if startup/origin timing diverges between browser and installed app.

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `foundation_documentation/policies/web_to_app_promotion_policy.md` | The absorbed first-route startup rule must stay inside the approved anonymous-web promotion posture. | Hard/auth gates still promote app; `/open-app` remains canonical for explicit promotion and successful app-first handoff. | Treating the first-route exception as a general relaxation of anonymous web. | Startup and guarded-route behavior must be split explicitly. |
| `foundation_documentation/modules/flutter_client_experience_module.md` | Canonical Flutter startup, auth, promotion, and map behavior live here. | Anonymous app baseline, action-gated modal path, route-gated promotion path, and startup/navigation ownership. | Per-screen ad hoc promotion/bootstrap branching or hidden request-layer ownership. | Implementation must converge on one startup/readiness boundary. |
| `foundation_documentation/modules/invite_and_social_loop_module.md` | Preserves continuation and web-to-app intent rules across guarded routes. | Promotion/open-app continuity and invite/detail intent preservation. | Losing continuation context while fixing startup behavior. | Route/startup changes must not break invite/deep-link continuity. |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is a medium cross-stack execution slice with a real tactical TODO owner. | TODO authority, approval gate, and delivery-gate discipline. | Implementing from the tracker or from memory instead of the governing TODO. | Code changes begin only after explicit `APROVADO`. |

## Promotion Finding Routing Ledger
| Finding ID | Severity | Classification | Routing Decision | Same TODO / Split Rationale | Status | Approval / Follow-up Reference |
| --- | --- | --- | --- | --- | --- | --- |
| `FABLE-BOOTSTRAP-01` | `n/a` | `by-design/no-action` | `retain current architecture` | The narrow tenant-public identity-readiness split is the accepted root-cause fix for `v0.2.0+8`; no follow-up owner is needed for this finding itself. | `closed` | `Claude fable review 2026-06-10; direction=keep` |
| `FABLE-BOOTSTRAP-02` | `n/a` | `by-design/no-action` | `retain same-origin fresh-document reentry` | Web post-grant fresh-document reentry is accepted by design as the current document owner boundary; only the remaining wording/continuation cleanup is routed out. | `closed` | `Claude fable review 2026-06-10; routed residuals only` |
| `FABLE-BOOTSTRAP-03` | `medium` | `follow-up-fast-follow` | `split` | The remaining issue is permission/origin ownership drift after the release fix, not a blocker for this TODO’s promoted architecture. | `opened` | `foundation_documentation/todos/active/fast_follow_required/followup/TODO-fast-follow-tenant-public-location-permission-and-origin-bootstrap-boundary.md` |
| `FABLE-BOOTSTRAP-04` | `high` | `follow-up-fast-follow` | `split` | Home initial geolocation prompting outside `/location/permission` is user-visible and real, but the current release blocker already closed; keep as immediate fast-follow. | `opened` | `foundation_documentation/todos/active/fast_follow_required/followup/TODO-fast-follow-tenant-public-location-permission-and-origin-bootstrap-boundary.md` |
| `FABLE-BOOTSTRAP-05` | `medium` | `follow-up-fast-follow` | `split` | `requestPermissionIfNeeded=true` default belongs to the same permission-owner cleanup and should not be patched ad hoc inside this closed release slice. | `opened` | `foundation_documentation/todos/active/fast_follow_required/followup/TODO-fast-follow-tenant-public-location-permission-and-origin-bootstrap-boundary.md` |
| `FABLE-BOOTSTRAP-06` | `medium` | `follow-up-hardening` | `split` | Stale anonymous-token recovery after a previously ready session is real but not a blocker for the current package once bootstrap root cause is fixed. | `opened` | `foundation_documentation/todos/active/post_release_hardening/hardening/TODO-post-release-tenant-public-anonymous-identity-readiness-self-healing-and-boundary-pruning.md` |
| `FABLE-BOOTSTRAP-07` | `low` | `follow-up-fast-follow` | `split` | Router-scoped reentry ownership and argument preservation belong to the same fast-follow permission/origin boundary cleanup. | `opened` | `foundation_documentation/todos/active/fast_follow_required/followup/TODO-fast-follow-tenant-public-location-permission-and-origin-bootstrap-boundary.md` |
| `FABLE-BOOTSTRAP-08` | `low` | `follow-up-fast-follow` | `split` | The 350ms wait heuristic is boundary debt in the first-grant continuation path and should close with the permission/origin owner cleanup. | `opened` | `foundation_documentation/todos/active/fast_follow_required/followup/TODO-fast-follow-tenant-public-location-permission-and-origin-bootstrap-boundary.md` |
| `FABLE-BOOTSTRAP-09` | `low` | `follow-up-hardening` | `split` | Dead `bootstrapIfEmpty` configurability is boundary pruning, not current-package blocker work. | `opened` | `foundation_documentation/todos/active/post_release_hardening/hardening/TODO-post-release-tenant-public-anonymous-identity-readiness-self-healing-and-boundary-pruning.md` |
| `FABLE-BOOTSTRAP-10` | `low` | `follow-up-hardening` | `split` | Startup-ordering was accepted by design, but the requested fail-first invariant belongs in the shared-boundary hardening owner. | `opened` | `foundation_documentation/todos/active/post_release_hardening/hardening/TODO-post-release-tenant-public-anonymous-identity-readiness-self-healing-and-boundary-pruning.md` |
| `FABLE-BOOTSTRAP-11` | `low` | `by-design/no-action` | `retain current TODO owner` | The current bootstrap TODO already freezes the absorbed startup slice and runtime/delivery-channel scope explicitly enough; no extra split is required unless later review reopens overlap. | `closed` | `Current TODO contract boundary + ledger on 2026-06-10` |
