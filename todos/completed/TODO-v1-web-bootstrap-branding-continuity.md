# TODO (V1): Web Bootstrap Branding Continuity

**Closure note (2026-04-17):** this slice is already delivered in the product and no longer needs active backlog ownership. The current web bootstrap result is considered satisfactory, so this TODO is retained as historical implementation context only.

**Status:** Completed
**Current delivery stage:** `Completed`
**Qualifiers:** `Historical-Reference`, `Delivered`
**Next exact step:** none; use this file only as historical context if bootstrap continuity needs future revision.
**Owners:** Flutter Team
**Objective:** Establish one continuous tenant-branding handoff for web bootstrap so the HTML splash, progress indicator, and Flutter `InitScreen` keep the same logo family, branded background, and readable loading affordances until the first real Flutter frame is visible.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before implementation + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `web`

**Direct-to-TODO rationale:** safe. The request is one bounded shared-bootstrap slice with one primary user-facing objective, no intended backend/API/schema change, and one coherent implementation boundary spanning `web/index.html`, `web/flutter_bootstrap.js`, and the shared Flutter `InitScreen`.
**Last confirmed truth:** `2026-04-13` repository scan confirms the current HTML splash hardcodes `logo-light.png` for both logo layers, the splash can be removed before a visibly branded Flutter frame is on screen, the progress bar reuses the same branding color family as the splash background, and `InitScreen` still renders `mainIcon*` rather than `mainLogo*`, creating an asset-family jump during bootstrap.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| shared HTML splash before Flutter boot | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous/bootstrap |
| shared tenant-public route family during first paint (`/`, `/descobrir`, `/parceiro/:slug`, `/agenda/evento/:slug`, `/invite`, `/convites`, `/baixe-o-app`, `/mapa`, `/mapa/poi`, `/location/permission`, `/profile`) | tenant host web | `tenant` | `tenant_public` | `n/a` | existing route guards unchanged |
| Flutter `InitRoute` / `InitScreen` | tenant host web runtime | `tenant` | `tenant_public` | `n/a` | existing startup route ownership unchanged |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for environment bootstrap, tenant-public web posture, and shared startup behavior. It currently lacks an explicit bootstrap visual continuity contract for HTML splash + Flutter init.
- `invite_and_social_loop_module.md`: authoritative for invite landing and app-promotion posture. This TODO must not widen web capabilities or change invite/auth semantics.
- `onboarding_flow_module.md`: authoritative where app-promotion and invite/onboarding handoff semantics are affected. No handoff change is intended in this slice.

### Decision Consolidation Targets

- Promote the finalized bootstrap visual continuity rule into `foundation_documentation/modules/flutter_client_experience_module.md`.
- Do not update secondary module docs unless implementation reveals a real invite/onboarding boundary change. None is intended in this slice.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-pre-mvp-web-small-fixes-intake.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `flutter-app/web/index.html`
- `flutter-app/web/flutter_bootstrap.js`
- `flutter-app/lib/presentation/shared/init/screens/init_screen/init_screen.dart`

---

## Scope

- Resolve the HTML splash logo from the canonical tenant branding contract instead of a stale/hardcoded single-logo assumption.
- Keep the tenant brand background continuous across HTML splash and the first real Flutter init frame on web.
- Align the Flutter `InitScreen` branding asset family with the HTML splash so there is no logo-to-icon jump during handoff.
- Make the bootstrap progress bar readable against both bright and dark tenant brand backgrounds.
- Keep route ownership, invite/auth/promotion behavior, and backend branding contracts unchanged.

## Out of Scope

- Backend/API/schema changes to environment branding payloads.
- Tenant-admin branding editor changes.
- Redesign of non-bootstrap tenant-public screens.
- Any expansion of web auth or web trust-action behavior.
- Landlord-specific visual redesign beyond preserving shared bootstrap compatibility.

---

## Rule/Workflow Sources

- `../delphi-ai/main_instructions.md`
- `../foundation_documentation/policies/scope_subscope_governance.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md`
- `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`

### Must Preserve

- `FCX-BOOT-01`: environment/bootstrap ownership remains backend-driven through `/api/v1/environment`.
- `FCX-BOOT-02`: tenant-public web remains promotion/read-only; no invite/auth semantic change is allowed.
- `FCX-BOOT-03`: `InitScreen` remains presentation-only; controller/repository boundaries stay unchanged.
- `FCX-BOOT-04`: scope ownership remains `tenant / tenant_public`.

### Must Avoid

- backend contract drift disguised as a visual fix;
- introducing controller/business logic into `InitScreen`;
- widening the splash fix into unrelated route/shell redesigns;
- claiming continuity without verifying both analyzer and runtime bootstrap behavior.

---

## Current Implementation Snapshot

- `web/index.html` hardcodes `logo-light.png` for both splash image layers and does not explicitly align that choice with runtime theme/branding state.
- The HTML splash currently removes itself as soon as Flutter host nodes appear, which can happen before a visibly branded Flutter frame is ready.
- The HTML progress bar track/fill both collapse visually on some tenant backgrounds because the track alpha is too faint and the fill reuses the same brand-primary family as the page background.
- `InitScreen` currently renders `mainIconLightUrl` / `mainIconDarkUrl` instead of `mainLogoLightUrl` / `mainLogoDarkUrl`.
- `InitScreen` already uses `appData.mainColor` for its screen background, so the visible blank flash is a handoff-timing/bootstrap-layer problem rather than a missing init-screen background color rule.

---

## Decision Baseline (Frozen)

- `D-01`: This slice is limited to shared bootstrap continuity in `web/index.html`, `web/flutter_bootstrap.js`, and Flutter `InitScreen`. No backend/API/schema changes are allowed.
- `D-02`: The HTML splash and Flutter `InitScreen` must use the same branding asset family (`main_logo_*` / canonical logo endpoints), not logo in one layer and icon in the next.
- `D-03`: Splash teardown must be first-paint-driven and fade-based. DOM host-node presence alone is not a sufficient readiness signal.
- `D-04`: The brand background must remain visually continuous from HTML splash through the first visible Flutter init frame.
- `D-05`: Bootstrap progress indicators must be contrast-safe against both bright and dark tenant brand backgrounds; the fill may not visually disappear into the page background.
- `D-06`: Fallback behavior must remain deterministic when runtime branding is unavailable; the loader may fall back to canonical fixed branding endpoints or local static splash assets, but not to undefined/broken references.
- `D-07`: Delivery requires documentation sync, targeted Flutter widget coverage for `InitScreen` branding selection, analyzer validation, and a local web build smoke.

### Module Coherence

| Decision | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | `Aligned` | `Preserve` | `flutter_client_experience_module.md` environment/bootstrap contract |
| `D-02` | `Supersede` | `Intentional` | current module docs define branding/bootstrap ownership but not the HTML-to-Flutter asset-family continuity rule |
| `D-03` | `Supersede` | `Intentional` | current docs do not freeze splash teardown semantics |
| `D-04` | `Supersede` | `Intentional` | current docs do not freeze background continuity across HTML splash + Flutter init |
| `D-05` | `Supersede` | `Intentional` | current docs do not freeze contrast-safe bootstrap progress behavior |
| `D-06` | `Aligned` | `Preserve` | canonical environment/bootstrap contract + fixed branding endpoints |
| `D-07` | `Aligned` | `Preserve` | Flutter analyzer/test discipline in module + repo rules |

### Module Decision Consistency Matrix

| Module Decision | Planned Handling | Evidence |
| --- | --- | --- |
| `FCX-BOOT-01` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.2 API Endpoint Definitions` |
| `FCX-BOOT-02` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Domain Rules` |
| `FCX-BOOT-03` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1.1 Presentation DI Matrix (Canonical)` |

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The tenant branding payload and canonical fixed asset endpoints already provide enough information to choose the correct light/dark splash logo without a backend change. | `AppDataDTO` already parses `main_logo_light_url` / `main_logo_dark_url` and falls back to `/logo-light.png` / `/logo-dark.png`. | This TODO would become backend-coupled and exceed the intended slice. | `High` | `Keep as Assumption` |
| `A-02` | The visible blank flash is caused by splash-removal timing rather than the absence of a branded Flutter init background. | `InitScreen` already paints `appData.mainColor`; the HTML splash can still disappear before that frame is visibly on screen. | We would need a wider app-shell/theme bootstrap investigation. | `Medium` | `Keep as Assumption` |
| `A-03` | A small `InitScreen` widget test is sufficient to guard the logo-family continuity on the Flutter side. | There is existing controller coverage for `InitScreen`, but no widget-level asset-selection test yet. | Delivery would rely entirely on manual/runtime confidence for the Flutter half of the handoff. | `High` | `Keep as Assumption` |
| `A-04` | Local web build smoke plus analyzer is enough for this slice; no backend/manual external dependency is required before local validation. | The change is bootstrap-presentation-only and does not alter contracts or external services. | Validation would need to defer on an external runtime dependency. | `High` | `Keep as Assumption` |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-pre-mvp-web-small-fixes-intake.md`
- `foundation_documentation/todos/completed/TODO-v1-web-bootstrap-branding-continuity.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `flutter-app/web/index.html`
- `flutter-app/web/flutter_bootstrap.js` if needed for progress continuity only
- `flutter-app/lib/presentation/shared/init/screens/init_screen/init_screen.dart`
- targeted Flutter widget tests for `InitScreen`

### Ordered Steps

1. Update canonical docs/intake to freeze the bootstrap continuity contract and route the issue into this dedicated lane.
2. Patch the HTML splash so it uses the canonical branding logo selection, continuity-safe background variables, and contrast-safe progress styling.
3. Patch splash teardown so it fades only after a paint-safe readiness signal rather than disappearing on raw host-node detection.
4. Align Flutter `InitScreen` to the same logo family and fallback direction as the HTML splash.
5. Add targeted Flutter widget coverage for `InitScreen` branding selection.
6. Run analyzer, targeted tests, and a local web build smoke, then record results.

### Test Strategy

- `test-after`

### Runtime / Rollout Notes

- No backend rollout is expected.
- If implementation reveals that runtime branding data is insufficient to choose/fetch the correct splash asset deterministically, stop and split the work into a backend-coupled lane instead of silently inventing a client-only fallback contract.

---

## Plan Review Gate

### Issue Card `P-01` - asset-family drift makes the handoff look broken even when branding loads correctly

- Severity: `high`
- Evidence: HTML splash currently uses `logo-light.png` for both layers, while Flutter `InitScreen` uses `mainIcon*`.
- Why it matters now: even a correct environment bootstrap still produces a visible logo-to-icon jump.
- Option A: patch only the HTML splash logo.
- Option B: patch only Flutter `InitScreen`.
- Option C (recommended): patch both sides so the same logo family survives the handoff.

### Issue Card `P-02` - splash teardown timing causes a visible wrong-color flash

- Severity: `high`
- Evidence: the DOM splash currently removes itself on host-node detection, which can happen before the first visibly branded Flutter frame.
- Why it matters now: this is the direct source of the “blank/wrong background before main color appears again” complaint.
- Option A: keep immediate removal and only recolor the background.
- Option B (recommended): keep the splash until a paint-safe readiness path and fade it out.
- Option C: leave the splash longer with a fixed timeout.

### Issue Card `P-03` - progress bar contrast collapses on some tenant backgrounds

- Severity: `medium`
- Evidence: the current progress bar fill reuses the same branding color family as the splash background and the track alpha is too faint.
- Why it matters now: in the reported screenshot the bar almost disappears.
- Option A (recommended): derive contrast-safe track/fill/text tokens from the resolved brand background.
- Option B: hardcode a white bar for all tenants.
- Option C: remove the progress bar entirely.

### Failure Modes & Edge Cases

- branding payload arrives late and the splash briefly shows fallback assets before switching;
- a very bright tenant primary color still causes insufficient progress contrast;
- splash fade timing regresses error/bootstrap-failure visibility;
- Flutter init fallback asset does not match the HTML fallback direction.

### Residual Unknowns / Risks

- HTML splash behavior has no direct automated test harness in this repository, so runtime confidence still depends on local web build smoke.
- Shared bootstrap changes affect all tenant-public web entrypoints, not one isolated screen; regressions would be highly visible.
