# TODO (v0.2.1+9): Android Instagram App-First Site Fallback

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** user request on 2026-06-10: create a TODO for `v0.2.1+9` covering the Android/Instagram direct URL behavior discussed in-session.
- **Approval scope:** approved for TODO/documentation creation only. No code, runtime, deploy, or behavior change is authorized by this approval.
- **Implementation authority:** pending explicit plan approval (`APROVADO`) before any implementation work starts.

## Context
The reported production/runtime symptom is:

- Android Instagram in-app browser reaches the tenant public site URL.
- The current installed-app handoff can work when the app is installed.
- When the site/browser fallback is needed, the public site does not open as expected.

Investigation evidence gathered before this TODO creation showed the current direct Android public route behavior:

- Android/Instagram User-Agent against `https://guarappari.belluga.space/` returned `302` to `/open-app?path=%2F&store_channel=web_direct&platform_target=android&fallback=promotion`.
- Normal browser User-Agent against the same URL returned the public Flutter shell with `200`.
- Following the Android redirect chain led from `/` to `/open-app?...fallback=promotion` and then to `/baixe-o-app?redirect=%2F`.
- Local source evidence points to `laravel-app/app/Http/Controllers/TenantPublicShellController.php` redirecting Android public-shell requests through `/open-app`, and `laravel-app/packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php` owning the intent/fallback URL assembly.

The product decision clarified in-session is:

- If the app is installed, direct public URLs should still open the app.
- If the app cannot open, is blocked by Instagram, or is not installed, the browser fallback for direct public URLs should open the original public site URL.
- Explicit conversion surfaces such as app CTAs, protected action gates, and promotion flows should keep their promotion/store fallback behavior.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-1-plus9-android-instagram-app-first-site-fallback`
- **Why this is the right current slice:** the issue is a bounded web-to-app handoff semantics bug affecting Android direct public URLs opened from Instagram, not a broad redesign of promotion, install attribution, or deferred deep links.
- **Direct-to-TODO rationale:** the desired behavior is already explicit: keep installed-app opening for direct URLs while changing the failed-handoff fallback from promotion/store to the original public site.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: local discoveries may stay here only while they preserve the same objective of Android direct public URL app-first handoff with site fallback.
- If implementation would change CTA, gate, store attribution, deferred install, iOS, QR-authenticated web, or dynamic app-link policy beyond this direct URL case, update/split the TODO and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Android`, `Instagram`, `Laravel`, `Deep-Link`, `Tenant-Public`, `User-Visible`, `Runtime-Validated`
- **Next exact step:** run plan review for this TODO and request explicit `APROVADO` before any code changes.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `implementation`
- **Why this state now:** the TODO contract is created and active, but implementation is intentionally not started because current approval only covers documentation.
- **Exit condition:** implementation and validation evidence are completed, then the TODO moves to `review`, `promotion_lane`, or `completed` according to the evidence status.

## Scope
- [ ] Define the canonical behavior for Android direct public web URLs as app-first with original-site fallback.
- [ ] Preserve installed-app opening for Android direct public URLs when the app can consume the handoff.
- [ ] Change the direct public URL fallback so failed/blocked/not-installed handoff opens the original normalized public route, not `/baixe-o-app`, Play Store, or another promotion route.
- [ ] Preserve explicit CTA and gate semantics: `web_cta`, `web_gate`, promotion buttons, install buttons, and protected action flows may continue to use promotion/store fallback.
- [ ] Preserve invite/deep-link attribution parameters that already belong to explicit invite or CTA flows.
- [ ] Add focused Laravel coverage for Android/Instagram direct route behavior and `/open-app` fallback URL assembly.
- [ ] Add focused regression coverage proving CTA/gate/promotion fallback behavior did not change.
- [ ] Collect runtime evidence on Android with app installed and with fallback required, including Instagram in-app browser or the closest approved reproducible lane.
- [ ] Update canonical policy/module documentation if implementation refines public web-to-app fallback semantics.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold.
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: local implementation is materially complete, but TODO remains in `active/` because review, CI-equivalent, or runtime validation is still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `laravel-app:<pending>`, `foundation_documentation:<current>`
- **Promotion lane path:** `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `laravel-app: dev; foundation_documentation: main`
- **Production-ready threshold for this TODO:** `laravel-app: stage or main as applicable with Android runtime evidence`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `Laravel Android direct URL app-first site fallback` | `pending` | `pending` | `pending` | `pending` | `planned` |
| `foundation documentation / TODO evidence` | `current` | `n/a` | `n/a` | `pending` | `drafted` |

## Out of Scope
- [ ] iOS Universal Links or iOS deferred-capture behavior.
- [ ] QR-authenticated web, authenticated web session bootstrap, or login continuation.
- [ ] Redesigning `/baixe-o-app`, promotion UI, app-store CTA copy, or install attribution beyond preserving current explicit CTA/gate behavior.
- [ ] Dynamic custom-domain App Links pipeline or `.well-known` association generation.
- [ ] Android store publication, Play Store listing readiness, or first-install deferred-link validation except where a regression test is needed to prove this TODO did not break those flows.
- [ ] Broad anonymous web access policy changes.
- [ ] Flutter UI redesign unless implementation discovers client-side CTA channel labels need a narrow compatibility update.

## Relationship To Existing v0.2.1+9 Android TODO
- Existing owner for Android store/deferred/runtime closure: `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`.
- This TODO owns a narrower product correction: direct public Android URL fallback behavior in Instagram/in-app browser contexts.
- If implementation proves both TODOs must be merged, the contracts must be reconciled explicitly instead of silently folding this behavior into the broader runtime TODO.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** Laravel redirect/fallback semantics, direct public route detection, `web_direct` fallback target handling, focused backend tests, focused policy wording, and Android runtime evidence.
- **Must update or split the TODO:** changes to CTA/gate fallback product policy, store/deferred attribution redesign, iOS behavior, QR-authenticated web, custom-domain App Links pipeline, or broader tenant-public web routing policy.

## Definition of Done
- [ ] `DOD-01` Android direct public tenant URLs still attempt installed-app opening when the app can consume the handoff.
- [ ] `DOD-02` Failed, blocked, or not-installed Android direct public URL handoff falls back to the original normalized public site URL.
- [ ] `DOD-03` Direct public URL fallback does not route the user to `/baixe-o-app`, Play Store, or promotion fallback unless the user entered through an explicit CTA/gate/promotion path.
- [ ] `DOD-04` Explicit `/open-app` CTA/gate flows preserve their current promotion/store fallback behavior.
- [ ] `DOD-05` Laravel tests freeze the distinction between `web_direct` and explicit CTA/gate channels.
- [ ] `DOD-06` Runtime evidence proves the installed-app path opens the app on Android.
- [ ] `DOD-07` Runtime evidence proves the fallback-required path opens the public site in the browser, including Instagram in-app browser where feasible.
- [ ] `DOD-08` Canonical policy/module docs are updated if implementation changes the documented web-to-app fallback contract.

## Validation Steps
- [ ] Add or update fail-first Laravel coverage showing the current Android/Instagram direct public URL fallback incorrectly resolves to promotion instead of the original public URL.
- [ ] Add focused coverage for `WebToAppPromotionService` proving direct-web fallback URL assembly uses the original public target route.
- [ ] Add focused coverage for `TenantPublicShellController` proving Android public shell navigation remains app-first when configured.
- [ ] Add regression coverage proving `web_cta`, `web_gate`, and `fallback=promotion` continue to route through promotion/store fallback.
- [ ] Run focused Laravel deep-link/open-app tests.
- [ ] Run adjacent public shell/metadata tests touched by the controller path.
- [ ] Probe local or published tenant route with Android Instagram User-Agent and verify redirect/fallback chain.
- [ ] On Android with the app installed, open the direct public URL from Instagram or an approved equivalent and verify the app opens.
- [ ] On Android with app absent, app handoff blocked, or equivalent fallback simulation, verify the public site opens to the original route.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | Android direct public tenant URLs still attempt installed-app opening. | `test+runtime` | `planned Laravel test + Android device proof` | `local Laravel + Android device` | `planned` | Must prove the proposal does not degrade installed-app behavior. |
| `DOD-02` | `Definition of Done` | Failed/blocked/not-installed direct handoff falls back to original public site URL. | `test+runtime` | `planned Laravel test + browser fallback proof` | `local Laravel + Instagram/browser` | `planned` | Core bug fix. |
| `DOD-03` | `Definition of Done` | Direct public URL fallback avoids `/baixe-o-app` and store routes. | `test+curl` | `planned curl redirect-chain evidence` | `local or published tenant` | `planned` | Should inspect `Location` and Android intent fallback URL. |
| `DOD-04` | `Definition of Done` | CTA/gate fallback remains promotion/store. | `regression-test` | `planned ApiV1OpenAppRedirectTest coverage` | `local Laravel` | `planned` | Avoids breaking conversion surfaces. |
| `DOD-05` | `Definition of Done` | Tests freeze `web_direct` versus CTA/gate distinction. | `test` | `planned focused Laravel suite` | `local Laravel` | `planned` | Must be explicit, not inferred from broad tests. |
| `DOD-06` | `Definition of Done` | Installed-app path opens the app. | `device-runtime` | `planned ADB/Instagram evidence` | `Android device` | `planned` | Use real Instagram when feasible. |
| `DOD-07` | `Definition of Done` | Fallback-required path opens the public site in browser. | `browser-runtime` | `planned Instagram/browser evidence` | `Android Instagram/browser` | `planned` | Must prove public site is usable after failed app handoff. |
| `DOD-08` | `Definition of Done` | Docs reflect any refined fallback contract. | `documentation` | `planned policy/module diff` | `foundation_documentation` | `planned` | Only required if implementation changes documented canon. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel + android-runtime + documentation`
- **Expected supporting profiles:** `assurance-tester-quality`, `devops-release-validation`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto-tech-lead` | `operational-coder` | This session froze the product behavior and created the TODO contract only. | `foundation_documentation/todos/active/v0.2.1+9/**` | `created` |
| `operational-coder` | `assurance-tester-quality` | Device/browser proof is required before delivery. | `Laravel tests + Android runtime lane` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `consolidated`
- **Why this level:** code change is likely narrow, but correctness depends on Android intent/browser fallback behavior and Instagram in-app browser runtime proof.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Primary policy doc:** `foundation_documentation/policies/web_to_app_promotion_policy.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/policies/web_to_app_promotion_policy.md` web-to-app direct URL fallback semantics
  - `foundation_documentation/modules/flutter_client_experience_module.md` tenant-public web/app continuation notes if needed
- **Module decision consolidation targets (required):**
  - Preserve invite/deep-link attribution decisions in `invite_and_social_loop_module.md`
  - Preserve anonymous/onboarding web-to-app posture in `onboarding_flow_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` Android direct public URLs must remain app-first when the app is installed and can open the target.
- [x] `D-02` `web_direct` fallback must open the original normalized public site route when app opening fails or is blocked.
- [x] `D-03` `web_direct` must not use promotion/store fallback as its default browser fallback.
- [x] `D-04` Explicit CTA/gate/promotion flows keep their current promotion/store fallback behavior.
- [x] `D-05` OS-level verified App Links that intercept before Laravel are acceptable and remain outside this backend fallback correction.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `foundation_documentation/policies/web_to_app_promotion_policy.md` | Shared web-to-app promotion, attribution, and fallback policy. | `Supersede (Intentional)` only for Android `web_direct` browser fallback target if current canon points direct URLs to promotion. | Policy file exists and must be updated if behavior is implemented. |
| `foundation_documentation/modules/invite_and_social_loop_module.md` | Invite/web-to-app attribution and continuation must preserve user intent. | `Preserve` | Direct public site fallback must not drop invite or target route intent where present. |
| `foundation_documentation/modules/onboarding_flow_module.md` | Anonymous exploration and web-to-app posture are controlled, not forced-auth by default. | `Preserve` | Direct public site fallback supports anonymous public browsing instead of forcing install promotion. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The implementation must differentiate entry channel semantics, not remove Android app handoff entirely.
- [x] `D-02` The narrowest acceptable implementation is a direct-web fallback-mode refinement.
- [x] `D-03` Existing install conversion paths remain valid when the user explicitly enters a conversion surface.
- [x] `D-04` The original public path and safe query parameters must be normalized before being used as a browser fallback URL.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Android intent fallback URL is the right technical place to control the failed-handoff browser destination for `/open-app`. | Current redirect chain uses `/open-app` and `WebToAppPromotionService` owns intent/fallback assembly. | The controller or client-side handoff path may need to own a fallback-mode parameter instead. | `High` | `Keep as Assumption` |
| `A-02` | Instagram may allow the app handoff but still needs a usable browser fallback when the handoff fails or is blocked. | Reported behavior says the intent can work, while site fallback is the failure point. | Device proof may reveal an Instagram-specific limitation requiring a no-intent fallback path for some user agents. | `Medium` | `Keep as Assumption` |
| `A-03` | Explicit CTA/gate channels should continue to optimize toward install/open conversion. | In-session product clarification only objected to direct URL opening site fallback, not CTA/gate promotion behavior. | Product approval must split direct public browsing from conversion surfaces more explicitly. | `High` | `Promote to Decision` |
| `A-04` | Laravel-only implementation is likely sufficient. | Current baseline points to `TenantPublicShellController` and `WebToAppPromotionService`. | A narrow Flutter web handoff helper update may be needed for channel naming parity. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `laravel-app/app/Http/Controllers/TenantPublicShellController.php`
- `laravel-app/packages/belluga/belluga_deep_links/src/Application/WebToAppPromotionService.php`
- `laravel-app/packages/belluga/belluga_deep_links/src/Http/Web/Controllers/OpenAppRedirectController.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`
- `laravel-app/tests/Feature/Tenants/PublicWebMetadataShellTest.php`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-instagram-app-first-site-fallback.md`

### Ordered Steps
1. Reconfirm the current baseline with focused Laravel tests and a curl probe using Android Instagram User-Agent.
2. Add fail-first tests for Android direct public URL fallback resolving to the original public site route.
3. Add or update tests that preserve installed-app handoff and CTA/gate promotion fallback.
4. Implement the smallest backend fallback-mode change that differentiates `web_direct` from explicit CTA/gate channels.
5. Update policy/module documentation only where the direct-web fallback contract needs canonical wording.
6. Run focused Laravel suites and adjacent public shell metadata tests.
7. Collect Android runtime evidence with app installed and with fallback required.
8. Update the completion evidence matrix before any delivery or promotion claim.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** current behavior is already frozen by Laravel redirect tests, and the product decision requires preserving installed-app opening while changing only the direct URL fallback.
- **Fail-first target(s):**
  - `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php`
  - `laravel-app/tests/Feature/Tenants/PublicWebMetadataShellTest.php`

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Android direct public URL installed-app handoff | App opening is a user-visible native/browser boundary. | `android-only` | `Android device / Instagram or approved equivalent` | `no` | `yes` | Device proof with app installed. | n/a |
| Android direct public URL fallback to site | Browser fallback is the reported failing flow. | `android-only` | `Android Instagram/browser` | `no` | `yes` | Curl redirect chain + browser/device fallback proof. | n/a |
| CTA/gate promotion fallback | Regression-sensitive conversion path. | `shared-android-web` | `Laravel tests; runtime if touched beyond backend assembly` | `no` | `no` | Focused regression tests. | Runtime proof may be waived only if implementation does not change CTA/gate code paths. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / focused open-app redirect tests` | Core backend handoff contract. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php` | `Local-Implemented` | `planned` | `planned command` | Run from `laravel-app`. |
| `laravel-app / public shell metadata tests` | Direct public route controller path is in scope. | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/PublicWebMetadataShellTest.php` | `Local-Implemented` | `planned` | `planned command` | Adjacent regression suite. |
| `runtime / Android Instagram installed-app` | Must prove app still opens when installed. | `manual Instagram URL open or approved ADB/browser equivalent` | `promotion` | `planned` | `planned runtime packet` | Prefer real Instagram evidence. |
| `runtime / Android fallback-to-site` | Must prove reported browser fallback failure is fixed. | `manual Instagram/browser fallback proof + curl redirect-chain probe` | `promotion` | `planned` | `planned runtime packet` | Must show original public route opens. |

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/main_instructions.md` | Root instruction source required before downstream work. | Delphi identity, evidence-first execution, and no unauthorized implementation. | Code changes under documentation-only approval. | This TODO is documentation-only until explicit `APROVADO`. |
| `delphi-ai/workflows/docker/profile-selection-method.md` | Required to declare profile and technical scope before task work. | Operational-coder ownership with assurance/runtime support. | Treating Android runtime proof as optional. | Profile/scope fields and handoff rows are included. |
| `r0/wf-docker-todo-driven-execution-method/SKILL.md` | User requested a tactical TODO. | TODO as execution contract with scope, DOD, validation, and approval boundary. | Creating an unbounded note or starting implementation. | File follows active TODO contract structure. |
| `foundation_documentation/todos/README.md` | Governs TODO creation and template usage. | Active-lane naming and template-derived structure. | Creating a TODO outside the v0.2.1+9 lane. | File was created under `todos/active/v0.2.1+9/` from the TODO template. |
| `foundation_documentation/policies/web_to_app_promotion_policy.md` | Canonical policy for web-to-app promotion and fallback behavior. | Shared attribution and explicit CTA/gate semantics. | Silent policy drift in backend-only code. | Any implemented fallback semantic refinement must update policy if canon changes. |
| `foundation_documentation/modules/invite_and_social_loop_module.md` | Invite and social loops depend on preserving web-to-app target intent. | Invite attribution and continuation target. | Dropping code/path/query during fallback. | Tests must preserve normalized target intent. |
| `foundation_documentation/modules/onboarding_flow_module.md` | Anonymous web/app continuation posture intersects public fallback behavior. | Anonymous public browsing where approved. | Turning direct public URLs into forced install promotion. | Direct fallback opens public site instead of promotion for `web_direct`. |
| `r0/rule-laravel-shared-core-instructions-always-on/SKILL.md` | Planned implementation touches Laravel controller/service/tests. | Laravel conventions, package boundaries, and focused tests. | Ad hoc redirect logic outside owned services. | Before implementation, load Laravel rules and keep changes in controller/service contracts. |

## Pipeline/Copilot P1/P2 Preflight
| Check | Planned Handling | Status | Notes |
| --- | --- | --- | --- |
| `P1/P2 review gate` | Run before delivery or promotion claim if required by lane. | `planned` | No delivery claim is made by this TODO creation. |

## Rule-Spirit Anti-Pattern Hunt
| Pattern | Planned Check | Status | Notes |
| --- | --- | --- | --- |
| `removing app handoff to fix browser fallback` | Verify direct public URLs remain app-first. | `planned` | Explicitly blocked by `DOD-01`. |
| `breaking CTA/gate conversion behavior` | Verify CTA/gate fallback remains promotion/store. | `planned` | Explicitly blocked by `DOD-04`. |
| `backend policy drift` | Verify policy/module docs are updated if semantics change. | `planned` | Explicitly blocked by `DOD-08`. |

## Promotion Finding Routing Ledger
| Finding ID | Severity | Classification | Routing Decision | Same TODO / Split Rationale | Status | Approval / Follow-up Reference |
| --- | --- | --- | --- | --- | --- | --- |
| `n/a` | `n/a` | `n/a` | `n/a` | `No promotion findings exist at TODO creation time.` | `n/a` | `n/a` |
