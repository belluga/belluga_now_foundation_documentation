# TODO (V1): Canonical Back Navigation Governance Cutover

**Status:** Active
**Current delivery stage:** `Pending`
**Qualifiers:** `Approval-Pending`
**Next exact step:** Freeze this tactical contract with the user and obtain explicit `APROVADO` before implementing the broader back-governance cutover.
**Owners:** Flutter Team
**Objective:** Establish one canonical Flutter back-navigation contract for the real route/shell surfaces already drifting today so system/browser back, visible back buttons, and shared-shell controls stop inventing local behavior and stop causing reload-like empty-stack failures on web.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** section-by-section planning freeze before approval + final decision-adherence review before closure.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `docs`, `web-validation`

**Direct-to-TODO rationale:** safe. This is already one bounded cross-surface Flutter/navigation governance slice with one primary objective: cut over the current ad-hoc back behavior to one canonical contract. It touches multiple screens/shells, but they all belong to the same approval and validation conversation.
**Last confirmed truth:** `2026-04-10` repository audit confirms drift still exists outside the already-seeded immersive-detail shell fix: `TenantHomeScreen`, `AppPromotionScreen`, `InviteFlowCoordinator`, `TenantAdminShellScreen`, and reusable widgets such as `AgendaAppBar`, `BackButtonBelluga`, and `TenantAdminFormLayout` still embed raw `pop/maybePop` route policy. `guarappari.belluga.space` currently responds `HTTP/2 200`, and `../web-app` exists as the local `build_web.sh` publish target.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scopes:** `tenant_public`, `tenant_admin`
- **Subscope:** `n/a`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug`, `/parceiro/:slug`, `/static/:assetRef` via `ImmersiveDetailScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted public detail |
| `/` via `TenantHomeScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | tenant home / app root |
| `/invite`, `/convites`, invite flow coordinator surfaces | tenant host | `tenant` | `tenant_public` | `n/a` | preview-first / app flow |
| `/baixe-o-app` via `AppPromotionScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | canonical promotion boundary |
| `/admin` header-driven shell via `TenantAdminShellScreen` | tenant host | `tenant` | `tenant_admin` | `n/a` | tenant-admin shell |
| Reusable back-owning widgets (`AgendaAppBar`, `BackButtonBelluga`, `TenantAdminFormLayout`) | shared | `tenant` | `tenant_public` / `tenant_admin` | `n/a` | reusable UI boundary only |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for scope matrix, anonymous web allowlist, tenant-public safe-back contract, route hydration discipline, and presentation DI boundaries. It already freezes the tenant-public safe-back order but does not yet define the generalized app-wide contract for admin/promotional/shell surfaces.
- `invite_and_social_loop_module.md`: authoritative for invite route behavior and web-to-app boundaries; invite flow back must not silently regress preview-first / promotion-first policy.
- `tenant_admin_module.md`: authoritative for tenant-admin route ownership; header back behavior must not invent shell policy outside route governance.
- `onboarding_flow_module.md`: authoritative where promotion boundary dismissal and route-based handoff semantics are involved.

### Decision Consolidation Targets

- Promote the finalized canonical back-governance contract into `flutter_client_experience_module.md`.
- Promote tenant-admin shell-specific back behavior into `tenant_admin_module.md` only if this slice freezes durable shell-root fallback semantics there.
- Promote invite/promotion-specific back semantics into secondary module docs only where they become durable route contracts.
- Resolve the current active VNext ledger `foundation_documentation/todos/active/vnext_slices/TODO-vnext-centralized-back-navigation-governance.md` by either superseding or closing it once this immediate cutover lands.

---

## References

- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-safe-back-navigation.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-centralized-back-navigation-governance.md`
- `lib/application/router/support/tenant_public_safe_back.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/tenant_home_screen.dart`
- `lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart`
- `lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`
- `lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `lib/presentation/tenant_public/widgets/back_button_belluga.dart`
- `lib/presentation/tenant_admin/shared/widgets/tenant_admin_form_layout.dart`
- `scripts/build_web.sh`
- `test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- `test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`
- `test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart`
- `test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`
- `test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart`
- `test/presentation/tenant_public/schedule/widgets/agenda_app_bar_test.dart`

---

## Scope

- Establish one canonical back-governance model for immediate real surfaces, using:
  - `BackSurfaceKind = rootOpenable | internalOnly | overlay`
  - `NoHistoryOutcome = fallback(route) | delegateToShell(sectionRoot) | requestExit | noop`
  - one shared dispatcher/policy boundary that owns the canonical order:
    1. consume route-local state if explicitly owned,
    2. `canPop() -> pop()` when history exists,
    3. execute deterministic `noHistoryOutcome`
- Absorb the already-local immersive-detail typed-policy seed into that broader contract instead of leaving it as a tenant-public-only island.
- Normalize the concrete route/shell surfaces already identified as drift:
  - `ImmersiveDetailScreen` consumers
  - `TenantHomeScreen`
  - `AppPromotionScreen`
  - `InviteFlowCoordinator`
  - `TenantAdminShellScreen`
  - reusable shared back widgets/layouts that currently hide route policy defaults
- Ensure shared widgets become pure back boundaries (`onBack` / explicit policy input) instead of silently calling `maybePop/pop`.
- Add targeted Flutter tests covering visible back and system/browser back where applicable.
- Run local analyzer/tests, publish web locally through `scripts/build_web.sh`, and validate navigation in browser plus Playwright against `https://guarappari.belluga.space`.

## Out of Scope

- Route-path redesign or URL contract changes.
- New deep-link capabilities, browser-history redesign, or general navigation stack redesign outside Flutter route governance.
- Replacing valid modal/dialog close semantics that are true overlay behavior only.
- Backend/API/schema changes.
- New analyzer rules that attempt to infer semantic back correctness from AST before the route classification metadata is frozen.

---

## Module Decision Baseline Snapshot

- `FCX-SCOPE-01`: route/screen ownership must stay inside the canonical environment/scope matrix in `flutter_client_experience_module.md`.
- `FCX-BACK-01`: tenant-public discovery/public-detail/public-map already require a centralized safe-back contract in `flutter_client_experience_module.md`.
- `FCX-ROUTE-01`: internal-only routes must be explicitly classified and must not rely on implicit direct-open assumptions under the route-driven hydration contract.
- `VNEXT-BACK-01`: the active VNext ledger already freezes the three canonical route classes `root-openable`, `internal-only`, and `modal/overlay`; this immediate TODO operationalizes that contract now instead of deferring it.

---

## Decision Baseline (Frozen)

- `D-01`: The canonical model for this cutover uses exactly three surface kinds: `rootOpenable`, `internalOnly`, and `overlay`.
- `D-02`: `requestExit` is a `NoHistoryOutcome`, not a fourth route class. Tenant-home root behavior is therefore modeled as `rootOpenable + requestExit`, not `appRootExit`.
- `D-03`: Shared shells and reusable back widgets are routing boundaries only. They may wire system/visible/shared-shell back to one explicit policy, but they may not invent fallback behavior through default raw `maybePop/pop`.
- `D-04`: The canonical order for governed route back remains:
  1. consume route-local state when explicitly owned,
  2. `canPop() -> pop()` when real history exists,
  3. execute deterministic `NoHistoryOutcome`
- `D-05`: `ImmersiveDetailScreen` remains in scope as the already-approved seed. The current typed `backPolicy` implementation must either survive as part of the generalized contract or be migrated cleanly into it with no visible regression.
- `D-06`: Immediate normalization targets for this TODO are `ImmersiveDetailScreen`, `TenantHomeScreen`, `AppPromotionScreen`, `InviteFlowCoordinator`, `TenantAdminShellScreen`, `AgendaAppBar`, `BackButtonBelluga`, and `TenantAdminFormLayout`.
- `D-07`: Overlays and dialog-local close buttons stay out of scope unless they currently own route policy for root-openable/internal-only surfaces.
- `D-08`: Delivery is incomplete unless it includes:
  - focused Flutter tests,
  - `fvm dart analyze --format machine`,
  - `scripts/build_web.sh` publish to the local web artifact repo,
  - manual browser validation,
  - Playwright validation against `https://guarappari.belluga.space`
- `D-09`: The architecture must remain analyzer-friendly by making structure explicit first. Early Rules may require explicit metadata/policy usage and ban default raw back in shared widgets, but they must not guess semantic fallback correctness from AST alone.

### Module Coherence

| Decision | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | `Aligned` | `Preserve` | `TODO-vnext-centralized-back-navigation-governance.md` already freezes the three route classes |
| `D-02` | `Supersede` | `Intentional` | external critique rejected `app_root_exit` as a route class; tenant-home proves it is a no-history terminal outcome |
| `D-03` | `Supersede` | `Intentional` | current shared widgets still hide raw `maybePop/pop`; this slice removes that ownership smell |
| `D-04` | `Aligned` | `Preserve` | tenant-public safe-back contract in `flutter_client_experience_module.md` |
| `D-05` | `Aligned` | `Preserve` | current immersive-detail typed policy is already locally implemented and validated |
| `D-06` | `Supersede` | `Intentional` | broadens the prior tenant-public-only lane into current admin/promotion/invite/shared-widget cutover |
| `D-07` | `Aligned` | `Preserve` | modal/overlay class remains separate |
| `D-08` | `Supersede` | `Intentional` | this cutover adds required browser + Playwright validation on the deployed local web artifact target |
| `D-09` | `Aligned` | `Preserve` | external critique converged on structural enforcement first, semantic lint later |

---

## Current Findings

### `F-01` Promotion boundary still dead-ends on empty stack

- `AppPromotionScreen` still falls back to raw `context.router.pop()` when `canPop == false` and the redirect is not auth-owned.
- Evidence: `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- Impact: direct-open `/baixe-o-app` or promotion-boundary closes can still feel like a reload/empty-root failure on web.

### `F-02` Tenant home already has canonical ordering but no shared governance boundary

- `TenantHomeScreen` correctly consumes local scroll state and then requests exit confirmation, but that behavior is screen-local and not modeled under the general contract yet.
- Evidence: `lib/presentation/tenant_public/home/screens/tenant_home_screen/tenant_home_screen.dart`
- Impact: the app has a real `requestExit` outcome today, but no reusable canonical representation for it.

### `F-03` Invite flow remains locally correct but structurally isolated

- `InviteFlowCoordinator` already does `canPop -> pop -> TenantHomeRoute`, but it owns this directly.
- Evidence: `lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart`
- Impact: correct-enough behavior exists, but no shared contract prevents future drift.

### `F-04` Tenant-admin shell still owns raw route policy

- `TenantAdminShellScreen` header back performs `canPop -> pop -> replace(destination.route)` directly in the shell.
- Evidence: `lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`
- Impact: tenant-admin still bypasses any canonical back policy and remains vulnerable to further branching inside the shell.

### `F-05` Shared widgets still hide raw `maybePop/pop`

- `AgendaAppBar`, `BackButtonBelluga`, and `TenantAdminFormLayout` still embed default `maybePop/pop`.
- Evidence: corresponding files under `lib/presentation/**`
- Impact: even with route-level governance, shared components can reintroduce implicit back behavior by omission.

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | A generalized back dispatcher/policy can absorb the current immersive-detail typed-policy seed without re-breaking the already-fixed event detail browser/system back behavior. | The current local `ImmersiveDetailScreen` change already routes visible + system back through one explicit policy. | We would need an adapter layer to preserve the existing `ImmersiveDetailBackPolicy` API while introducing the broader contract. | `High` | `Keep as Assumption` |
| `A-02` | The immediate drift surfaces listed in `D-06` are sufficient for this cutover; pure overlay/modal close buttons can stay out of scope. | Repository scan shows most remaining raw `pop/maybePop` calls are modal/dialog-local and not final route policy. | Scope would expand materially and require a split TODO for overlay governance. | `Medium` | `Keep as Assumption` |
| `A-03` | `scripts/build_web.sh ../web-app dev` is the correct local publish step for browser/Playwright validation of this slice. | `scripts/build_web.sh` exists, `../web-app` exists, and the user explicitly named this validation path. | We would need a different output target or lane-specific publish contract before closing validation. | `High` | `Keep as Assumption` |
| `A-04` | `https://guarappari.belluga.space` remains the correct browser/Playwright validation host during this TODO. | The host currently responds `HTTP/2 200`, and the user explicitly stated it points to the local publish via cloudflared. | External validation would have to be blocked or downgraded until tunnel/domain readiness is restored. | `Medium` | `Keep as Assumption` |
| `A-05` | `project_constitution.md` remains unavailable in this checkout, so module + scope policy docs continue as the governing project anchors for this slice. | Current repo bootstrap and prior session evidence confirm the file is absent. | A newly restored constitution would need to be reviewed before implementation proceeds. | `High` | `Keep as Assumption` |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/active/mvp_slices/TODO-v1-canonical-back-navigation-governance-cutover.md`
- `foundation_documentation/artifacts/dependency-readiness.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- secondary module docs only if durable route-specific back rules are frozen there
- `lib/application/router/support/**` back-governance helpers/specs/dispatcher
- `lib/presentation/shared/widgets/**` reusable back boundaries
- `lib/presentation/tenant_public/**` targeted route/shell screens
- `lib/presentation/tenant_admin/**` targeted shell/layout screens
- targeted widget tests

### Ordered Steps

1. Establish the generalized back-governance primitives in Flutter source:
   - surface kind
   - no-history outcome
   - shared dispatcher/policy boundary
2. Reconcile the existing immersive-detail typed `backPolicy` seed with that generalized contract.
3. Normalize tenant-public route roots:
   - tenant home
   - promotion boundary
   - invite flow
4. Normalize tenant-admin shell/header behavior under the same canonical model.
5. Remove default raw back behavior from reusable widgets/layouts in scope so they require explicit back ownership.
6. Add/expand targeted widget tests for visible/system back and no-history outcomes.
7. Promote the finalized canonical rule into module docs and resolve the active VNext governance ledger accordingly.
8. Run validation:
   - focused Flutter tests
   - `fvm dart analyze --format machine`
   - `scripts/build_web.sh ../web-app dev`
   - local browser verification
   - Playwright verification against `https://guarappari.belluga.space`

### Test Strategy

- `test-first where coverage exists naturally; otherwise targeted test-before-refactor per surface`
- Existing widget-test files already exist for `app_promotion`, `tenant_home`, `invite_flow`, `tenant_admin_shell`, `agenda_app_bar`, and immersive details, so new route-policy behavior should be locked by tests before or while each surface is normalized.

### Runtime / Rollout Notes

- This slice changes only Flutter/docs/web artifact output; no backend migration is expected.
- Browser/Playwright validation is part of Definition of Done, not an optional smoke step.
- If validation on `guarappari.belluga.space` is blocked by tunnel/domain readiness, the TODO must remain explicitly `Blocked` or `Provisional`; local-only evidence is not enough for closure because the user requested deployed-browser validation.

---

## Plan Review Gate

### Issue Card `ARCH-01` - mixing route class with app lifecycle outcome will make the contract brittle

- **Severity:** `high`
- **Evidence:** tenant home already behaves as `consume local -> confirm exit -> pop`, and external critique converged that `app_root_exit` is not a route class.
- **Why it matters now:** if `requestExit` becomes a route class, the taxonomy will fork again instead of stabilizing.
- **Option A (Recommended):** keep three surface kinds and model exit as `NoHistoryOutcome.requestExit`.
- **Option B:** introduce a fourth route class such as `appRootExit`.
- **Option C:** keep ad-hoc exit behavior local to home-like screens.
- **Recommendation:** `A`

### Issue Card `ARCH-02` - putting fallback inference inside reusable shells/widgets will recreate the original smell

- **Severity:** `high`
- **Evidence:** `BackButtonBelluga`, `AgendaAppBar`, `TenantAdminFormLayout`, and the former `ImmersiveDetailScreen` default all embedded implicit back behavior.
- **Why it matters now:** a canonical contract fails if shared UI can silently bypass it by omission.
- **Option A (Recommended):** shared UI receives explicit `onBack` or policy input only.
- **Option B:** let reusable widgets keep `maybePop/pop` defaults for convenience.
- **Option C:** fix only route pages and leave shared widgets alone.
- **Recommendation:** `A`

### Issue Card `ARCH-03` - analyzer enforcement can turn noisy if it tries to infer semantic correctness too early

- **Severity:** `medium`
- **Evidence:** external critique converged that structural rules are analyzer-friendly, while semantic fallback correctness is not yet low-noise.
- **Why it matters now:** premature lint breadth would create false positives and weaken the contract instead of strengthening it.
- **Option A (Recommended):** enforce structure first (`explicit spec/policy`, `no raw default back in shared widgets`), defer semantic correctness to tests until metadata is frozen.
- **Option B:** add broad semantic lint rules immediately.
- **Option C:** avoid all rule discussion in this slice.
- **Recommendation:** `A`

### Failure Modes & Edge Cases

- a route root intended as `requestExit` accidentally falls back to a route instead of showing its terminal behavior
- promotion close still empties the stack on direct-open web
- system/browser back and visible back diverge again on one normalized surface
- a shared widget keeps a hidden raw `maybePop/pop` default and bypasses the canonical dispatcher
- `build_web.sh` publishes successfully but deployed-browser behavior on `guarappari.belluga.space` still differs from local widget tests

### Residual Unknowns / Risks

- The exact durable metadata/home for the generalized spec may require one more small naming pass during implementation.
- `project_constitution.md` is still absent, so module + scope policy docs remain the authoritative anchors for this slice.

---

## Additional Architectural Opinions

- **Needed:** `yes`
- **Why ambiguity remains:** broad route-governance cutover across tenant-public + tenant-admin + shared widgets warranted independent critique.
- **Opinion count:** `2`
- **Package mode:** `bounded-summary`
- **Subagent mandate:** `yes`
- **Required lenses:** `correctness`, `performance`, `elegance`, `structural-soundness`, `enforceability`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `Noether` | keep `rootOpenable/internalOnly/overlay`, move exit to `NoHistoryOutcome`, keep UI boundary thin, avoid policy-per-scope design | runtime cost negligible | better if surface kind is separated from no-history action | stronger because the model stays small and reusable | `Integrated` | external critique on 2026-04-10 |
| `Dalton` | keep the model small, treat `requestExit` as outcome not class, enforce structure first and normalize real roots before broad lint | runtime cost irrelevant compared to routing/shell work | cleaner if shared widgets stop owning implicit back | stronger because analyzer rules stay structural at first | `Integrated` | external critique on 2026-04-10 |

---

## Independent No-Context Critique Gate

- **Critique decision:** `required`
- **Why this decision:** cross-scope route-governance cutover, shared-shell blast radius, public web/browser behavior, and rule/enforcement implications
- **Impact signals in scope:** `cross-module blast radius`, `public route behavior`, `intentional module supersede`
- **Package mode:** `bounded-summary`
- **Critique isolation mode:** `fresh no-context auxiliary reviewers`
- **Critique status:** `findings_integrated`
- **Findings summary:** both reviewers converged on the same corrections:
  - keep only three surface kinds
  - treat exit as `NoHistoryOutcome`, not route class
  - keep shared UI as thin wiring only
  - defer semantic lint breadth until route metadata/spec is explicit
- **Evidence / reference:** reviewer outputs captured in-session on `2026-04-10`

---

## Approval Gate

Implementation must not begin until the user replies with the explicit token: **APROVADO**.
