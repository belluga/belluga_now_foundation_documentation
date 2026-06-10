# TODO (v0.2.1+9): Android Web-to-App Store and Deferred Runtime Validation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Approval
- **Approved by:** explicit user request on `2026-06-08` to split the old promotion-lane web-to-app packet, archive the delivered slice, and reopen only the residual Android runtime/store/deferred validation in `v0.2.1+9`.
- **Approval scope:** create a narrow active TODO for browser/device/store/deferred closure. On `2026-06-10`, the approved Home initial-open refinement was absorbed into `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md` because it is structurally a startup/bootstrap boundary concern.

## Context
This TODO was split out of `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md` on `2026-06-08`.

The product behavior is no longer the main problem:

- `origin/main` already uses the real app-promotion/store-handoff boundary on `/baixe-o-app`.
- `origin/main` already filters promotion/open-app targets to active publication settings.
- `origin/main` already preserves safe continuation through `/open-app` and deferred resolver `target_path`.
- Historical ADB evidence already proved the installed-app handoff contract for the implemented warm-start flows.

What still lacks closure is the final runtime truth after the startup/bootstrap rule is implemented in the current-package owner TODO:

1. validate the absorbed Home/startup exception on real runtime once it lands,
2. real browser/device verification of the current store/open destinations,
3. real absent-app install -> first-open deferred continuation proof,
4. final browser/device validation of representative hard-gate entrypoints, especially Home post-entry protected actions and the Discovery/public favorite/card tap semantics called out by the prior packet.

This TODO exists so that remaining work is explicit, narrow, and honest.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `v0-2-1-plus9-android-web-to-app-runtime-validation`
- **Why this is the right current slice:** the missing work is no longer a broad cross-stack implementation packet; it is a bounded Android runtime/store/deferred validation lane on top of already-promoted behavior.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the user explicitly requested the split and the remaining scope is already concrete from the old TODO's open runtime rows.

## Contract Boundary
- This TODO owns the residual Android runtime/store/deferred validation lane for the already-promoted web-to-app behavior.
- The Home initial-open exception is now implemented/owned by `TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`; this TODO only validates that absorbed contract on the post-version external runtime lane.
- It may drive small subordinate fixes needed to resolve a concrete runtime defect discovered during that validation, but it does not reopen the broader product boundary or redesign the conversion experience.
- If a real defect is found, the fix belongs in the owning repository/module while this TODO remains the validation owner for the closure lane.
- This TODO does **not** own iOS Universal Links, QR-authenticated web, telemetry sink/readback hardening, or a new promotion UX design.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Runtime-Validation`, `Android`, `Store`, `Deferred-Install`, `Depends-On-v0.2.0+8-Bootstrap-Startup-Owner`, `Split-From-Completed-2026-06-08`
- **Next exact step:** wait for the current-package bootstrap/startup owner TODO to land, then run the real browser/device matrix against the current published Android target and capture evidence for the absorbed Home first-access behavior, store destination, install referrer continuity, first-open restoration, and representative hard-gate entrypoints.

## Active Work State (Required While TODO Remains In `active/`)
- **Work state:** `review`
- **Why this state now:** implementation ownership for the absorbed startup rule now lives in the current-package bootstrap/startup TODO; this lane is validation-only unless runtime proof exposes a concrete defect that must be routed back to the owning repo.
- **Exit condition:** all runtime rows below are either passed with evidence or converted into a narrower defect owner without leaving ambiguous debt behind.

## Scope
- [ ] `SCOPE-01` Validate the absorbed guard exception from `TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md` so tenant-public Home initial open no longer auto-opens the promotion modal.
- [ ] `SCOPE-02` Validate `/baixe-o-app` and promotion CTAs on real browser/device lanes and confirm they open the intended dynamic Android store/open destinations.
- [ ] `SCOPE-03` Validate absent-app Android install flow from representative web sources (`/invite`, public detail route, guarded-route promotion) through Play Store/install -> first open -> deterministic continuation restore.
- [ ] `SCOPE-04` Validate installed-app `/open-app` handoff from current live web surfaces reaches the intended in-app target before Guard fallback.
- [ ] `SCOPE-05` Validate representative public hard-gate UI interactions in runtime, with explicit focus on Home post-entry protected actions plus Discovery favorite/card tap semantics.
- [ ] `SCOPE-06` Record the exact closure outcome: full pass, or a precisely scoped defect/follow-up owner for any runtime discrepancy discovered.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Active Work State Semantics
- `implementation`: the TODO is still gaining or changing implementation/test evidence.
- `review`: implementation is already materially present, but final runtime validation and any last-mile defect triage are still open.
- `blocked`: execution is paused on an explicit blocker; `Blocker Notes` are mandatory.
- `n/a once moved out of active`: use after the TODO moves to `promotion_lane/` or `completed/`.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `flutter-app:origin/main reviewed`, `laravel-app:origin/main reviewed`, `foundation_documentation:<current>`
- **Promotion lane path:** `flutter-app: dev -> stage -> main`, `laravel-app: dev -> stage -> main`, `foundation_documentation: main`
- **Lane-promoted threshold for this TODO:** `runtime proof captured or concrete defect owner opened`
- **Production-ready threshold for this TODO:** `runtime/browser/device matrix passed on the approved target`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `browser/device runtime validation` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `possible Flutter/Laravel fix if runtime fails` | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |
| `foundation documentation evidence` | `<current>` | `n/a` | `n/a` | `<pending>` | `drafted` |

## Out of Scope
- [ ] Broad reopening of the canonical web-to-app promotion policy or changing the already-promoted `/baixe-o-app` contract beyond runtime validation of the absorbed Home initial-open exception.
- [ ] iOS Universal Links or iOS deferred deep-link capture.
- [ ] QR-authenticated web bootstrap/session work.
- [ ] Mixpanel sink/readback closure beyond using existing telemetry as supporting evidence.
- [ ] Net-new anonymous-favorites feature work unless runtime validation exposes a concrete current defect.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** browser/device validation, ADB/install checks, Play Store/deferred continuity evidence, validation of the absorbed Home initial-open exception, and any small fix directly required to make the approved Android runtime behave as documented.
- **Must update or split the TODO:** policy redesign, new promotion UX, iOS delivery, QR-authenticated web, or broad analytics/sink work.

## Definition of Done
- [ ] `DOD-01` Real runtime evidence proves the absorbed tenant-public Home initial-open exception is working: first access no longer auto-opens the promotion modal and does not interrupt the read-only experience.
- [ ] `DOD-02` Real browser/device evidence proves `/baixe-o-app` and the current open/install CTAs resolve to the intended dynamic Android destinations.
- [ ] `DOD-03` Real absent-app install flow preserves invite `code` or safe redirect intent through install and first open, landing deterministically in the app.
- [ ] `DOD-04` Real installed-app handoff from live web hard-gate surfaces reaches the intended in-app target before Guard fallback.
- [ ] `DOD-05` Home post-entry protected actions and Discovery/public favorite/card tap semantics are runtime-verified on the current build, or a concrete defect owner is opened with exact failing evidence.
- [ ] `DOD-06` The final result is explicit: pass and archive, or split/fix a concrete defect with no ambiguous residual debt.

## Validation Steps
- [ ] `VAL-01` Verify the exact browser-facing runtime target and published build used for the run before collecting evidence.
- [ ] `VAL-02` Verify the absorbed Home initial-open no-modal exception from the current-package bootstrap/startup owner before final runtime capture.
- [ ] `VAL-03` Run the web/browser matrix for Home initial open, invite landing, public detail-route promotion, and guarded-route promotion.
- [ ] `VAL-04` Run the absent-app Android install matrix for install via promotion -> first open -> continuation restoration.
- [ ] `VAL-05` Run the installed-app Android matrix for warm-start `/open-app` and representative hard-gate UI taps.
- [ ] `VAL-06` Capture screenshots/logs/artifacts and classify the outcome as pass or concrete defect.

## Completion Evidence Matrix (Required Before Delivery Claim)
Every `Definition of Done` item and every `Validation Steps` item must have a concrete evidence row before this TODO can claim `Local-Implemented`, move to `promotion_lane/`, move to `completed/`, or claim `Production-Ready`.

| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` Real runtime evidence proves the absorbed tenant-public Home initial-open exception is working: first access no longer auto-opens the promotion modal and does not interrupt the read-only experience. | `runtime` | `<planned Home first-open browser/device evidence>` | `browser + Android device` | `planned` | Implementation ownership moved to the current-package bootstrap/startup TODO; this lane validates the result. |
| `DOD-02` | `Definition of Done` | `DOD-02` Real browser/device evidence proves `/baixe-o-app` and the current open/install CTAs resolve to the intended dynamic Android destinations. | `runtime` | `<planned Playwright/browser run + device screenshots/logs>` | `browser + Android device` | `planned` | Must prove the current dynamic destination, not only local code intent. |
| `DOD-03` | `Definition of Done` | `DOD-03` Real absent-app install flow preserves invite `code` or safe redirect intent through install and first open, landing deterministically in the app. | `runtime` | `<planned Play Store/install referrer + first-open evidence>` | `Android device + store` | `planned` | This is the main residual gap from the archived super-packet. |
| `DOD-04` | `Definition of Done` | `DOD-04` Real installed-app handoff from live web hard-gate surfaces reaches the intended in-app target before Guard fallback. | `runtime` | `<planned ADB/browser handoff evidence>` | `browser + Android device` | `planned` | Must exercise current live surfaces, not only backend tests. |
| `DOD-05` | `Definition of Done` | `DOD-05` Home post-entry protected actions and Discovery/public favorite/card tap semantics are runtime-verified on the current build, or a concrete defect owner is opened with exact failing evidence. | `runtime` | `<planned Home guarded-action + Discovery/public UI runtime evidence>` | `browser + Android device` | `planned` | Protects the exception without relaxing the actual protected-action boundary. |
| `DOD-06` | `Definition of Done` | `DOD-06` The final result is explicit: pass and archive, or split/fix a concrete defect with no ambiguous residual debt. | `review` | `<planned closeout update in this TODO>` | `foundation docs` | `planned` | The lane must end with a precise owner/outcome. |
| `VAL-01` | `Validation Steps` | `VAL-01` Verify the exact browser-facing runtime target and published build used for the run before collecting evidence. | `review` | `<planned build/publish verification note>` | `runtime URL` | `planned` | Avoids collecting evidence against a stale bundle/host. |
| `VAL-02` | `Validation Steps` | `VAL-02` Verify the landed Home initial-open no-modal exception from the current-package bootstrap/startup owner before final runtime capture. | `test+review` | `<planned focused browser/device verification of the landed Home guard exception>` | `browser + Android device` | `planned` | This lane validates the absorbed behavior; it does not own its implementation. |
| `VAL-03` | `Validation Steps` | `VAL-03` Run the web/browser matrix for Home initial open, invite landing, public detail-route promotion, and guarded-route promotion. | `runtime` | `<planned Playwright/manual browser evidence>` | `browser` | `planned` | Must validate the absorbed Home exception plus the existing representative promotion entrypoints. |
| `VAL-04` | `Validation Steps` | `VAL-04` Run the absent-app Android install matrix for install via promotion -> first open -> continuation restoration. | `runtime` | `<planned device/store evidence>` | `Android device + store` | `planned` | The prior TODO explicitly left this open. |
| `VAL-05` | `Validation Steps` | `VAL-05` Run the installed-app Android matrix for warm-start `/open-app` and representative hard-gate UI taps. | `runtime` | `<planned ADB/device/browser evidence>` | `browser + Android device` | `planned` | Complements the historical warm-start proof with current live-surface evidence. |
| `VAL-06` | `Validation Steps` | `VAL-06` Capture screenshots/logs/artifacts and classify the outcome as pass or concrete defect. | `review` | `<planned artifact set + TODO update>` | `foundation docs + runtime artifacts` | `planned` | The closure decision must be evidence-backed. |

## External Dependency Readiness (Required When External Systems Matter)

| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `Published tenant browser target` | Browser evidence must hit the real bundle/host used for the Android promotion flow. | `unknown` | `n/a` | `<planned publish + browser verification>` | Confirm the served bundle before any runtime claim. |
| `Android device lane` | Real install/open/deferred proof requires a stable device lane. | `unknown` | `n/a` | `<planned ADB/device check>` | If unavailable, record blocker truthfully instead of pretending closure. |
| `Play Store / install-referrer path` | Deferred first-open continuity depends on the external install path. | `unknown` | `n/a` | `<planned store/install run>` | If the external path is unstable, capture the exact blocker and keep the TODO active. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `assurance-tester-quality`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `operational-coder`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile assurance-tester-quality`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | The implementation slice was absorbed into the current-package bootstrap/startup owner; this TODO now carries only runtime validation and defect classification for the external Android/browser lane. | `foundation_documentation/todos/active/v0.2.1+9/**`, published runtime targets, `flutter-app/**` or `laravel-app/**` only if a concrete validation defect is exposed | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `consolidated`
- **Why this level:** the residual scope is narrow, but it depends on real browser/device/store behavior across multiple repos and can still uncover a cross-stack last-mile bug.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` promotion/open-app runtime notes if any new truth is discovered
  - `invite_and_social_loop_module.md` invite-preview / promotion-boundary runtime notes if any current doc is inaccurate
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`
  - `invite_and_social_loop_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` This TODO does not reopen the approved Android web-to-app promotion boundary broadly; it validates the current promoted behavior in real runtime and carries only one approved narrow Home-entry exception.
- [x] `D-02` The core missing closure is external runtime/store/deferred proof, not a missing baseline implementation packet.
- [x] `D-03` If runtime validation reveals a real defect, the fix belongs to the owning Flutter/Laravel surface, while this TODO remains the closure owner for the validation lane.
- [x] `D-04` iOS and QR-authenticated web remain sibling TODOs and must not be reabsorbed here.
- [x] `D-05` Tenant-public Home initial open is the only approved exception to the current guard-triggered promotion interruption rule: first access stays read-only and uninterrupted, while explicit protected actions from Home still trigger the canonical promotion boundary. Implementation ownership now lives in `TODO-v0.2.0+8-tenant-public-bootstrap-and-startup-boundary-hardening.md`.

## Module Decision Baseline Snapshot (Required Before APROVADO)
- | Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
- | --- | --- | --- | --- |
- | `web_to_app_promotion_policy.md` | Anonymous web remains promotion/read-only and `/open-app` preserves safe continuation semantics. | `Preserve` | `foundation_documentation/policies/web_to_app_promotion_policy.md` |
- | `web_to_app_promotion_policy.md#3.1` | Hard/auth gates promote the app on anonymous web; Home convenience affordances stay within unauthenticated posture. | `Preserve` | `foundation_documentation/policies/web_to_app_promotion_policy.md` lines `53-68` |
- | `flutter_client_experience_module.md#2.1` | Action-gated web boundaries may render the canonical compact promotion modal backed by the shared controller. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` lines `76-79` |
- | `invite_and_social_loop_module.md` tactical ledger note for web-to-app conversion | The old TODO promoted invite-preview/promotion-boundary rules but left Android runtime closure open. | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Runtime evidence must be collected against the real browser/device/store path, not inferred from local tests.
- [x] `D-02` Tenant-public Home initial open is a narrow exception: first paint must remain uninterrupted by a promotion modal, but Home stays read-only and protected actions still promote the app. This TODO validates the shipped runtime behavior after the current-package owner implements it.
- [x] `D-03` Discovery/public favorite/card tap semantics remain part of this runtime closure because the prior packet explicitly left them as a useful final manual focus.
- [x] `D-04` The narrowest acceptable closeout is either full runtime proof or a clearly scoped defect owner; "probably already works" is not enough.

## Assumptions Preview (Required Before Plan Review)

| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current published Android/browser target can still serve the promotion flow needed for final validation. | The former packet already used published tenant/browser targets plus ADB evidence; the split is driven by missing proof, not known host retirement. | The TODO becomes blocked on environment/runtime readiness rather than product behavior. | `Medium` | `Keep as Assumption` |
| `A-02` | The installed-app handoff behavior seen in historical ADB evidence still matches `origin/main` code and can be revalidated without reopening the product contract. | `origin/main` still carries the routing tests and preserved `/open-app` contract. | A real regression bug owner must be opened. | `Medium` | `Keep as Assumption` |
| `A-03` | The absorbed Home initial-open exception will remain narrow in runtime and will not require this validation lane to reopen the broader anonymous-web policy. | User direction on `2026-06-08` defines the exception as narrow and unique, and implementation ownership now lives in the current-package bootstrap/startup TODO. | This validation lane may need to reopen policy review instead of only classifying a runtime defect. | `Medium` | `Keep as Assumption` |
| `A-04` | Discovery/public favorite/card tap runtime validation is still representative enough to close the lingering UI ambiguity from the previous packet. | The former TODO's delivery canon explicitly named Discovery card/favorite UI tap behavior as the remaining manual focus. | The TODO will need a narrower follow-up or a different representative surface. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract above. It must stay subordinate to the contract.

### Touched Surfaces
- `foundation_documentation/todos/active/v0.2.1+9/TODO-v0.2.1+9-android-web-to-app-store-and-deferred-runtime-validation.md`
- published tenant/browser runtime targets used by the Android promotion flow
- Android device lane / ADB evidence surface
- `flutter-app/**` or `laravel-app/**` only if runtime validation exposes a concrete defect after the absorbed Home-entry exception lands

### Ordered Steps
1. Verify the absorbed Home initial-open no-modal exception has landed from the current-package bootstrap/startup owner.
2. Verify the exact published host/build used for the browser evidence lane.
3. Run the browser matrix for Home initial open, invite landing, detail-route promotion, and guarded-route promotion.
4. Run the absent-app Android install/deferred matrix and capture first-open restoration evidence.
5. Run the installed-app Android handoff matrix for warm-start `/open-app` plus representative hard-gate UI taps.
6. If a failure appears, classify it as a concrete bug, route the fix to the owning repo, and keep this TODO as the closure owner until the rerun passes.

### Test Strategy
- **Strategy:** `test-after`
- **Why:** the remaining lane is dominated by runtime/browser/device proof of already-owned behavior, plus defect classification if the published Android/browser flow still diverges.
- **Fail-first target(s) (when required):** `Only if runtime validation exposes a concrete defect that needs a subordinate code fix in the owning repo`

### Flow Evidence Planning Matrix (Required Before `APROVADO`)

| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home initial open without promotion modal | Visible first-access tenant-public behavior and explicit exception to the current guard interruption | `shared-android-web` | `both` | `no` | `yes` | `<planned browser + Android device evidence>` | `Implementation is owned by the current-package bootstrap/startup TODO; this row validates the resulting runtime behavior.` |
| `/baixe-o-app` destination + CTA behavior | Visible promotion boundary and store/open destination | `divergent-android-web` | `both` | `no` | `yes` | `<planned browser + Android device evidence>` | `n/a` |
| absent-app install -> first open continuation | Deferred install/runtime continuation | `android-only` | `ADB integration` | `no` | `yes` | `<planned Play Store/install referrer evidence>` | `n/a` |
| installed-app hard-gate handoff | Visible guarded-route continuity | `shared-android-web` | `both` | `no` | `yes` | `<planned browser + Android device evidence>` | `n/a` |
| Home protected actions + Discovery/public favorite/card tap semantics | User-visible representative hard-gate semantics after the initial-entry exception | `shared-android-web` | `both` | `yes` | `yes` | `<planned runtime evidence or defect artifact>` | `n/a` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app focused guard/promotion lane` | Runtime validation may still require targeted Flutter evidence for the absorbed Home-entry exception or any fix discovered during the external proof lane. | `n/a at contract-authoring stage` | `Local-Implemented` | `n/a` | Exact Flutter CI-equivalent commands must be added only if this validation lane exposes a concrete code fix. | No code implementation is being claimed in this documentation update. |
| `Runtime/browser/device validation lane` | Final acceptance still depends on browser/device/store runtime evidence. | `n/a at contract-authoring stage` | `promotion` | `n/a` | Runtime evidence rows in this TODO become mandatory once execution begins. | This TODO is still `Pending`; runtime acceptance remains mandatory before closeout. |

### Runtime / Rollout Notes
- The runtime target must be the actual published browser/device host used for the Android promotion path.
- If the absent-app store/install path cannot be exercised reliably, keep the TODO active and record the exact external blocker.

## Pipeline/Copilot P1/P2 Preflight

| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Split-opening preflight` | This TODO opens as a residual runtime-validation lane, not as a new code-change PR. | `n/a` | User-approved split on `2026-06-08`; no PR/Copilot review surface exists yet. | `none` | Add real preflight evidence only if runtime validation exposes a code fix or promotion candidate. |

## Rule-Spirit Anti-Pattern Hunt

| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `Residual-lane honesty` | Prevent the old super-packet from being archived wholesale while external runtime/store/deferred proof is still missing. | `passed` | Split from `foundation_documentation/todos/completed/TODO-store-release-web-to-app-conversion-gate.md` on `2026-06-08` | `no findings` | The residual work remains explicit here instead of hidden behind a completed label. |

## Rules Acknowledgement / Ingestion

| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/README.md` | The user asked for a truthful split between completed and still-active work. | Honest lane ownership and explicit runtime debt. | Reopening backlog or leaving a dead promotion-lane owner. | Keep this TODO narrow and active until runtime closure is proven. |
| `/home/elton/Dev/repos/delphi-ai/skills/verification-debt-audit/SKILL.md` | The remaining work is pure evidence debt around runtime/store/deferred behavior. | Explicitly track what is still unproven. | Treating `origin/main` presence as equivalent to runtime proof. | Require browser/device/store evidence before closeout. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-closeout-promotion-method/SKILL.md` | This TODO is the active half of a closeout split. | Keep the archival slice closed and this residual lane explicit. | Recombining both concerns into another stale super-packet. | Route any discovered bug to the owning repo, but keep closure ownership here. |
