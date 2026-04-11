# TODO (V1): Canonical Back Navigation Governance Cutover

**Status:** Active
**Current delivery stage:** `Local-Validated`
**Qualifiers:** `Provisional`
**Next exact step:** Consolidate documentation/rule wording around the finalized architecture (`root-only startup override + native warm browser back + centralized no-history fallback`) and prepare promotion-ready packaging.
**Owners:** Flutter Team
**Objective:** Establish one canonical Flutter back-navigation contract for the real route/shell surfaces already drifting today so system/browser back, visible back buttons, and shared-shell controls stop inventing local behavior and stop causing reload-like empty-stack failures on web, while keeping warm browser/device back native whenever real history exists and centralizing deterministic no-history fallback only for root-opened routes.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `big`
**Checkpoint Policy:** section-by-section planning freeze before approval + final decision-adherence review before closure.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `docs`, `web-validation`

**Direct-to-TODO rationale:** safe. This remains one bounded cross-surface Flutter/navigation governance slice with one primary objective: replace ad-hoc back behavior with one router-native canonical contract. It touches multiple screens/shells, but they still belong to one approval, evidence, and validation conversation.
**Last confirmed truth:** `2026-04-11` reassessment plus implementation/validation converged on the final architecture now present in source: AutoRoute `meta` stamps governed families across tenant-public, tenant-admin, landlord-area root, account-workspace, and identity-boundary routes; current-route back dispatch is centralized in `canonical_route_governance.dart`; `RouteBackScope` preserves native browser/device back on web when real history exists; and startup routing is limited to one root-scoped `AppStartupNavigationCoordinator` override through `deepLinkBuilder` for invite/deferred-link bootstrap only. Generic browser-history seeding was intentionally removed. Focused route-matrix tests pass, local `build_web.sh` publish succeeds, Playwright validates warm browser back on `https://guarappari.belluga.space`, Android Chrome remote-debug validation confirms warm device-back without reload for `home -> event` and `home -> discovery -> partner`, and Android app device validation passes through `flutter drive` after the known `streamListen/VmServiceProxyGoldenFileComparator` harness defect on the direct `flutter test -d` lane.
**Provisional Notes:** Architectural ambiguity is resolved and local validation is green; remaining work is packaging/promotion follow-through, not further local back-policy redesign.

---

## Scope Ownership

- **EnvironmentType:** `landlord`, `tenant`
- **Main scopes:** `tenant_public`, `tenant_admin`, `landlord_area`
- **Subscope:** `account_workspace`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug`, `/parceiro/:slug`, `/static/:assetRef` via `ImmersiveDetailScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted public detail |
| `/` via `TenantHomeScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | tenant home / app root |
| `/privacy-policy`, `/descobrir`, `/profile`, `/agenda`, `/mapa`, `/mapa/poi` public route families | tenant host | `tenant` | `tenant_public` | `n/a` | governed tenant-public route roots/details |
| `/invite`, `/convites`, invite flow coordinator surfaces | tenant host | `tenant` | `tenant_public` | `n/a` | preview-first / app flow |
| `/baixe-o-app` via `AppPromotionScreen` | tenant host | `tenant` | `tenant_public` | `n/a` | canonical promotion boundary |
| `/auth/*` identity-boundary screens when reached inside app/native flows | app/native + guarded web continuation | `tenant` | `tenant_public` | `n/a` | identity boundary only; does not widen the anonymous web allowlist |
| `/admin` header-driven shell via `TenantAdminShellScreen` | tenant host | `tenant` | `tenant_admin` | `n/a` | tenant-admin shell |
| `/workspace`, `/workspace/{account_slug}`, `/workspace/{account_slug}/events/create` | tenant host | `tenant` | `tenant_public` | `account_workspace` | workspace root/scoped/create-event entry surfaces |
| landlord-area root via `LandlordHomeScreen` | landlord flavor / landlord host admin entry | `landlord` | `landlord_area` | `n/a` | landlord-area root consumer of canonical back policy |
| Reusable back-owning widgets (`AgendaAppBar`, `BackButtonBelluga`, `TenantAdminFormLayout`) | shared | `landlord` / `tenant` | `tenant_public` / `tenant_admin` / `landlord_area` | `account_workspace` / `n/a` | reusable UI boundary only |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/tenant_admin_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/modules/tenant_home_composer_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for scope matrix, anonymous web allowlist, tenant-public safe-back contract, route hydration discipline, and presentation DI boundaries. It already freezes the tenant-public safe-back order but does not yet define the generalized app-wide contract for admin/promotional/shell surfaces.
- `invite_and_social_loop_module.md`: authoritative for invite route behavior and web-to-app boundaries; invite flow back must not silently regress preview-first / promotion-first policy.
- `tenant_admin_module.md`: authoritative for tenant-admin route ownership; header back behavior must not invent shell policy outside route governance.
- `onboarding_flow_module.md`: authoritative where promotion boundary dismissal and route-based handoff semantics are involved.
- `tenant_home_composer_module.md`: authoritative for tenant-home root semantics plus governed transitions into `account_workspace`; any home/workspace ancestry promoted by this slice must remain coherent there.

### Decision Consolidation Targets

- Promote the finalized canonical back-governance contract into `flutter_client_experience_module.md`.
- Promote tenant-admin shell-specific back behavior into `tenant_admin_module.md` only if this slice freezes durable shell-root fallback semantics there.
- Promote invite/promotion-specific back semantics into secondary module docs only where they become durable route contracts.
- Promote tenant-home/workspace transition semantics into `tenant_home_composer_module.md` only where this slice freezes durable ancestry or workspace-entry behavior.
- Resolve the current active VNext ledger `foundation_documentation/todos/active/vnext_slices/TODO-vnext-centralized-back-navigation-governance.md` by either superseding or closing it once this immediate cutover lands.

---

## References

- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-safe-back-navigation.md`
- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-centralized-back-navigation-governance.md`
- `lib/application/application_contract.dart`
- `lib/application/router/support/canonical_route_family.dart`
- `lib/application/router/support/canonical_route_governance.dart`
- `lib/application/router/support/canonical_route_history_state.dart`
- `lib/application/router/support/canonical_route_meta.dart`
- `lib/application/router/support/tenant_admin_safe_back.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/tenant_home_screen.dart`
- `lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/invite_flow_coordinator.dart`
- `lib/presentation/tenant_admin/shell/tenant_admin_shell_screen.dart`
- `lib/presentation/landlord_area/home/screens/landlord_home_screen/landlord_home_screen.dart`
- `lib/presentation/account_workspace/screens/account_workspace_placeholder_screen.dart`
- `lib/presentation/shared/auth/screens/auth_login_screen/auth_login_screen.dart`
- `lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `lib/presentation/tenant_public/widgets/back_button_belluga.dart`
- `lib/presentation/tenant_admin/shared/widgets/tenant_admin_form_layout.dart`
- `scripts/build_web.sh`
- `test/application/router/support/tenant_admin_safe_back_test.dart`
- `test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- `test/presentation/tenant/home/screens/tenant_home_screen/tenant_home_screen_test.dart`
- `test/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen_test.dart`
- `test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart`
- `test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `test/presentation/tenant_public/legal/screens/tenant_privacy_policy_screen_test.dart`
- `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`
- `test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- `test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`

---

## Scope

- Establish one canonical back-governance model for immediate real surfaces, using:
  - `BackSurfaceKind = rootOpenable | internalOnly | overlay`
  - `NoHistoryOutcome = fallback(route) | delegateToShell(sectionRoot) | requestExit | noop`
  - one shared dispatcher/policy boundary that owns the canonical order:
    1. consume route-local state if explicitly owned,
    2. `canPop() -> pop()` when history exists,
    3. execute deterministic `noHistoryOutcome`
- Keep startup routing limited to root-scoped invite/deferred-link bootstrap overrides through `deepLinkBuilder`; do not synthesize generic browser history for governed routes.
- Freeze deterministic route-family no-history outcomes for root-opened routes instead of synthesizing cold-entry ancestry chains in browser history.
- Absorb the already-local immersive-detail typed-policy seed into that broader contract instead of leaving it as a tenant-public-only island.
- Move canonical route semantics out of screens/route pages and into AutoRoute `meta` plus one centralized route-family governance registry.
- Extend the same router-native contract beyond the original tenant-public pilot so landlord-area root, account-workspace entry routes, tenant-admin shell families, and identity-boundary auth flows consume the same canonical governance where they expose route-level/shared-chrome back behavior.
- Treat missing `canonicalRouteMeta(...)` on a governed route as invalid route configuration to be surfaced by tests/rules, not something silently repaired by route-name fallback logic.
- Treat warm browser/device back as native AutoRoute/browser authority whenever real history exists. The centralized dispatcher remains responsible for visible app back, system-back fallback, and no-history outcomes only.
- Normalize the concrete route/shell surfaces already identified as drift:
  - `ImmersiveDetailScreen` consumers
  - `TenantHomeScreen`
  - tenant-public root/detail screens that now consume `buildCanonicalCurrentRouteBackPolicy(...)`
  - `AppPromotionScreen`
  - `InviteFlowCoordinator`
  - `TenantAdminShellScreen`
  - `LandlordHomeScreen`
  - `AccountWorkspacePlaceholderScreen`
  - auth boundary screens that own route-level/shared-chrome back
  - reusable shared back widgets/layouts that currently hide route policy defaults
- Normalize tenant-admin shell ownership so section/root/internal route semantics come from router metadata/registry instead of duplicated route-name sets and leaf-level path-repair workarounds.
- Ensure shared widgets become pure back boundaries (`onBack` / explicit policy input) instead of silently calling `maybePop/pop`.
- Add targeted Flutter tests covering visible back and system/browser back where applicable, including cold-entry/deeplink coverage for all governed families rather than only the event detail slice.
- Run local analyzer/tests, publish web locally through `scripts/build_web.sh`, and validate navigation in browser plus Playwright against `https://guarappari.belluga.space`.

## Out of Scope

- Route-path redesign or URL contract changes.
- New deep-link capabilities or general navigation stack redesign outside Flutter route governance.
- Server-side entry-document redesigns or multi-document redirect/bootstrap chains. This TODO remains inside the Flutter/browser-history integration boundary.
- Replacing valid modal/dialog close semantics that are true overlay behavior only.
- Backend/API/schema changes.
- New analyzer rules that attempt to infer semantic back correctness from AST before the route classification metadata is frozen.
- Global `includePrefixMatches=true` enablement as a blunt solution. Prefix ancestry is valid only where URL topology matches canonical product ancestry.
- Cross-domain SSO redesign or auth/product-flow redesign beyond making the existing identity-boundary screens consume the centralized back policy.

---

## Definition of Done

- Every governed route family in scope resolves its back semantics from AutoRoute `meta` plus the centralized canonical registry; route-name tables are no longer semantic authority.
- `deepLinkBuilder` is limited to root-scoped startup override for invite/deferred-link bootstrap and does not synthesize browser history for governed routes.
- Governed screens/shells across tenant-public, tenant-admin, landlord-area root, account-workspace entry routes, and identity-boundary auth consumers use `RouteBackScope`/canonical helpers instead of inventing local fallback policy.
- Focused tests cover the governed route matrix, including cold-entry/deeplink and no-history outcomes where relevant.
- Local `fvm dart analyze --format machine`, focused Flutter tests, local web publish, manual browser/device validation, and Playwright validation on `https://guarappari.belluga.space` all pass or are explicitly blocked with bounded residual risk.
- Canonical module docs are updated with durable route/back contracts, and the follow-up Rule proposal is recorded with clear false-positive boundaries.

## Validation Steps

1. Stabilize focused test harnesses so fake route data exposes `RouteData.meta`, `toPageRouteInfo()`, and `root.currentPath/pathState` consistently.
2. Run focused Flutter tests for the governed route matrix, including:
   - tenant-public root/detail families
   - invite/promotion flows
   - tenant-admin dashboard/root/internal families
   - landlord-area root and account-workspace entry families
   - identity-boundary auth screens that now consume canonical back policy
3. Run `fvm dart analyze --format machine`.
4. Run the implementation-level external elegance/performance review loop and integrate any required corrections before broader validation continues.
5. Publish with `scripts/build_web.sh ../web-app dev`.
6. Manually validate, for every governed route family that can be entered that way:
   - warm in-app navigation forward/back
   - cold URL/deeplink open
   - visible app back
   - browser/device back on Android
   - browser history gesture/swipe-back semantics where warm navigation already created real history
7. Run Playwright validation against `https://guarappari.belluga.space`.
8. Record residual risk explicitly if iOS physical gesture validation cannot be obtained in this environment.

## Implementation Snapshot

- AutoRoute-native route-family governance now exists in source via `canonical_route_family.dart`, `canonical_route_meta.dart`, `canonical_route_history_state.dart`, and `canonical_route_governance.dart`.
- App ingress/startup wiring now uses `AppStartupNavigationCoordinator` through `deepLinkBuilder`, limited to root-scoped startup override rather than generic history seeding.
- Governed route metadata has been stamped across tenant-public, tenant-admin, landlord-area, account-workspace, promotion, invite, and auth route modules.
- Screen/shell cutover is materially in place for tenant-public detail/root screens, promotion, invite flow, tenant-admin shell, landlord-area root, account-workspace placeholder, and identity-boundary auth screens.
- External review corrections already integrated in source:
  - route classification is now meta-only,
  - `tenant_admin_safe_back.dart` is a thin compatibility adapter only,
  - unconditional post-frame history flush was removed.
- Remaining delivery work is concentrated in test-harness migration, full route-matrix validation, module-doc promotion, and Rule proposal framing.

## Module Decision Baseline Snapshot

- `FCX-SCOPE-01`: route/screen ownership must stay inside the canonical environment/scope matrix in `flutter_client_experience_module.md`.
- `FCX-BACK-01`: tenant-public discovery/public-detail/public-map already require a centralized safe-back contract in `flutter_client_experience_module.md`.
- `FCX-ROUTE-01`: internal-only routes must be explicitly classified and must not rely on implicit direct-open assumptions under the route-driven hydration contract.
- `VNEXT-BACK-01`: the active VNext ledger already freezes the three canonical route classes `root-openable`, `internal-only`, and `modal/overlay`; this immediate TODO operationalizes that contract now instead of deferring it.
- `HOM-03`: tenant-home scope ownership remains `tenant_public`, and workspace entry remains a governed transition rather than a locally invented back path.
- `HOM-04`: tenant-public home and adjacent flows must preserve governed transitions to `account_workspace` without weakening public/home route semantics.

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
  - manual browser validation covering governed routes and cold-entry/deeplink browser back,
  - Playwright validation against `https://guarappari.belluga.space`
- `D-11`: Synthetic cold-entry browser-history ancestry is not canonical product behavior in the current scope. Root-opened routes must rely on deterministic no-history fallback, not fabricated browser history.
- `D-12`: The canonical no-history event rule is `TenantHomeRoute (/)`; warm in-app or browser-native history still wins whenever it exists.
- `D-09`: The architecture must remain analyzer-friendly by making structure explicit first. Early Rules may require explicit metadata/policy usage and ban default raw back in shared widgets, but they must not guess semantic fallback correctness from AST alone.
- `D-10`: The first objective Rule derived from this cutover is structural, not semantic. It enforces ownership shape (`RouteBackScope` / typed back helpers / injected back handlers / shared section registry) and explicitly exempts result-return or overlay-only gates until they are classified or migrated.
- `D-13`: AutoRoute route definitions become the authoritative semantic source through `meta`-backed route-family governance. Screens/routes may provide only route-local state consumers and pure UI concerns; they may not declare canonical fallback ancestry inline.
- `D-14`: `deepLinkBuilder` remains mandatory at router ingress only for root-scoped startup override (for example invite/deferred-link bootstrap), not for generic browser-history synthesis.
- `D-15`: Multi-stage cold-entry ancestry seeding is out of scope for the finalized architecture. If revisited later, it must be treated as explicit product behavior rather than a back-policy side effect.
- `D-16`: URL prefix relationships may still inform no-history fallback for naturally nested families (for example `/mapa/poi -> /mapa`), but they are not a license to synthesize browser history.
- `D-17`: Warm browser/device back remains native when real history exists; centralized canonical governance complements that path via visible back, system-back fallback, and no-history outcomes.
- `D-18`: This cutover is no longer a tenant-public-only pilot. The same router-native contract now governs tenant-public, tenant-admin, landlord-area root, account-workspace entry routes, and identity-boundary auth consumers where they expose route-level/shared-chrome back behavior.
- `D-19`: Missing `canonicalRouteMeta(...)` on a governed route is invalid configuration and must fail fast in runtime/tests; route-name fallback is forbidden as semantic authority.
- `D-20`: `tenant_admin_safe_back.dart` remains only a compatibility adapter onto the canonical registry; route families, section roots, and no-history semantics may not be redefined there.

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
| `D-11` | `Supersede` | `Intentional` | cold-entry ancestry is now treated as explicit canon instead of implicit stack residue |
| `D-12` | `Supersede` | `Intentional` | user confirmation plus implementation now freeze event no-history fallback as `home (/)`, while warm history remains stack-first |
| `D-09` | `Aligned` | `Preserve` | external critique converged on structural enforcement first, semantic lint later |
| `D-13` | `Supersede` | `Intentional` | external architecture review converged that route semantics must move out of screens/helpers and into router metadata/registry |
| `D-14` | `Supersede` | `Intentional` | external review rejected non-AutoRoute ingress handling, but the final architecture narrowed `deepLinkBuilder` to root startup override instead of history seeding |
| `D-15` | `Supersede` | `Intentional` | synthetic cold-entry browser ancestry was removed from current scope after architecture reassessment |
| `D-16` | `Supersede` | `Intentional` | prefix relationships remain useful for fallback classification, not for browser-history synthesis |
| `D-17` | `Supersede` | `Intentional` | final validated design keeps warm browser/device back native and limits centralized governance to visible back + no-history fallback |
| `D-18` | `Supersede` | `Intentional` | user-directed reassessment expanded the contract from a tenant-public pilot into a generalized governed-route architecture |
| `D-19` | `Supersede` | `Intentional` | external implementation review rejected retaining route-name fallback as a second source of truth |
| `D-20` | `Supersede` | `Intentional` | tenant-admin shell policy tables were intentionally collapsed into the central registry plus a thin compatibility adapter |

---

## Current Findings

### `F-01` Router-native governance spine is already implemented in source

- AutoRoute `meta` stamping, centralized route-family descriptors, and root-scoped startup coordination through `deepLinkBuilder` are now present.
- Evidence: `lib/application/application_contract.dart`, `lib/application/router/modular_app/modules/**`, `lib/application/router/support/canonical_route_*.dart`.
- Impact: the TODO must now track verification and consolidation work, not keep treating the architecture choice as unresolved.

### `F-02` Meta-only classification is now a hard contract

- Governed route classification throws if `canonicalRouteMeta(...)` is missing; route-name fallback has been removed as semantic authority.
- Evidence: `lib/application/router/support/canonical_route_governance.dart`.
- Impact: tests and future route additions must become meta-aware; missing metadata is now a real regression candidate and a good Rule target.

### `F-03` Tenant-admin route-name duplication has been structurally collapsed

- `tenant_admin_safe_back.dart` now delegates into the canonical registry instead of owning independent policy tables.
- Evidence: `lib/application/router/support/tenant_admin_safe_back.dart`.
- Impact: admin shell drift risk is materially lower, and future admin work must not rebuild section semantics outside the registry.

### `F-04` Focused tests are still behind the new runtime contract

- Several test harnesses still use shallow fake routers/route matches that do not expose `root`, `pathState`, or `toPageRouteInfo()` correctly for canonical governance.
- Evidence: current failing/edited tests under `test/application/router/support/**` and `test/presentation/**`.
- Impact: validation is currently blocked on test-harness migration, not on product architecture.

### `F-05` Route-matrix validation remains incomplete

- The user-expanded acceptance criteria now require all governed routes, not only event detail, to be validated for warm navigation, cold entry/deeplink, and back behavior.
- Evidence: current TODO scope vs in-progress focused test edits.
- Impact: closure is invalid until the full governed route matrix is exercised.

### `F-06` Browser/device back still needs end-to-end evidence

- History seeding is wired in code, but Android browser/device back and browser gesture/back-stack behavior across cold entry still need empirical validation after publish.
- Evidence: no completed manual/Playwright evidence bundle yet for `https://guarappari.belluga.space`.
- Impact: delivery remains provisional until browser/device behavior is verified across the route matrix.

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Focused test harnesses can be upgraded to the new meta/root-aware contract without requiring another product-architecture change. | Current failures are concentrated in fake router/route-match gaps (`root`, `pathState`, `toPageRouteInfo()`), not in the production policy implementation. | The TODO would need a renewed architecture pass or split if the runtime contract itself proves untestable. | `Medium` | `Keep as Assumption` |
| `A-02` | The immediate governed families now stamped with `canonicalRouteMeta(...)` are the correct closure boundary for this cutover; true overlay-only flows can still remain outside scope. | Current route-family registry and module scan cover the real route/shell surfaces that own back semantics today. | Scope would expand materially and require a follow-up TODO for overlay/result-return governance. | `Medium` | `Keep as Assumption` |
| `A-03` | `scripts/build_web.sh ../web-app dev` is still the correct local publish step for browser/Playwright validation of this slice. | `scripts/build_web.sh` exists, `../web-app` exists, and the user explicitly named this validation path. | We would need a different output target or lane-specific publish contract before closing validation. | `High` | `Keep as Assumption` |
| `A-04` | `https://guarappari.belluga.space` remains the correct browser/Playwright validation host during this TODO. | The host currently responds `HTTP/2 200`, and the user explicitly stated it points to the local publish via cloudflared. | External validation would have to be blocked or downgraded until tunnel/domain readiness is restored. | `Medium` | `Keep as Assumption` |
| `A-05` | `project_constitution.md` remains unavailable in this checkout, so module + scope policy docs continue as the governing project anchors for this slice. | The file is still absent in this checkout. | A newly restored constitution would need to be reviewed before implementation proceeds. | `High` | `Keep as Assumption` |
| `A-06` | If physical iOS gesture validation is unavailable locally, browser-history semantics validated through publish host + Playwright + Android/browser evidence will still allow progress, but only with explicitly recorded residual risk. | Current environment instructions include Android/browser validation, but no guaranteed iOS physical device path is present. | Closure would need either additional device access or explicit user acceptance of the residual risk. | `Low` | `Keep as Assumption` |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/active/mvp_slices/TODO-v1-canonical-back-navigation-governance-cutover.md`
- `foundation_documentation/artifacts/dependency-readiness.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- secondary module docs only where durable route-specific back rules are frozen there (`tenant_admin`, `invite/social`, `onboarding`, `tenant_home_composer`)
- `lib/application/application_contract.dart`
- `lib/application/router/modular_app/modules/**`
- `lib/application/router/support/**` back-governance helpers/specs/dispatcher
- `lib/presentation/shared/widgets/**` reusable back boundaries
- `lib/presentation/shared/auth/**`
- `lib/presentation/shared/promotion/**`
- `lib/presentation/tenant_public/**` governed route/shell screens
- `lib/presentation/tenant_admin/**` targeted shell/layout screens
- `lib/presentation/landlord_area/**`
- `lib/presentation/account_workspace/**`
- focused widget/controller/router tests for the governed route matrix
- validation artifacts under `foundation_documentation/artifacts/tmp/<run-id>/...` when browser/Playwright evidence is captured

### Ordered Steps

1. Finish the focused test-harness migration so fake route data exposes the same root/meta contract now required in runtime:
   - fake `RootStackRouter` support where `root.currentPath/pathState` is read
   - fake `RouteMatch.toPageRouteInfo()` support
   - canonical `meta` stamping in route-data test helpers
2. Expand focused tests to the full governed route matrix:
   - tenant-public root/detail families
   - invite/promotion flows
   - identity-boundary auth flows
   - landlord-area root and account-workspace entry families
   - tenant-admin dashboard/root/internal families
3. Run the implementation-level external elegance/performance review on the current diff, then integrate any final corrections before reopening broader validation.
4. Run local validation:
   - focused Flutter tests
   - `fvm dart analyze --format machine`
5. Publish with `scripts/build_web.sh ../web-app dev`.
6. Run manual browser/device validation across the governed route matrix:
   - warm navigation push/pop
   - cold URL/deeplink entry
   - visible app back
   - Android browser/device back
   - browser gesture/history back where same-document seeding applies
7. Run Playwright verification against `https://guarappari.belluga.space`.
8. Promote the finalized canonical rule into module docs, resolve the active VNext governance ledger accordingly, and frame the follow-up structural Rule so it catches real regressions without false positives on overlays/result-return flows.

### Working Coverage Matrix (Implementation + Validation Target)

- Tenant-public root/detail families:
  - `tenantHome`
  - `tenantPrivacyPolicy`
  - `discoveryRoot`
  - `partnerDetail`
  - `staticAssetDetail`
  - `profileRoot`
  - `eventSearch`
  - `immersiveEventDetail`
  - `cityMap`
  - `poiDetail`
- Invite/promotion families:
  - `inviteFlow`
  - `inviteEntry`
  - `inviteShare`
  - `appPromotion`
- Identity-boundary auth families:
  - `authLogin`
  - `recoveryPassword`
  - `authCreateNewPassword`
- Landlord/workspace families:
  - `landlordHome`
  - `accountWorkspaceHome`
  - `accountWorkspaceScoped`
  - `accountWorkspaceCreateEvent`
- Tenant-admin families:
  - `tenantAdminDashboard`
  - `tenantAdminEventsRoot`
  - `tenantAdminEventsInternal`
  - `tenantAdminAccountsRoot`
  - `tenantAdminAccountsInternal`
  - `tenantAdminAssetsRoot`
  - `tenantAdminAssetsInternal`
  - `tenantAdminSettingsRoot`
  - `tenantAdminSettingsInternal`

Coverage notes:
- Validation must cover both warm in-app navigation and cold-entry/deeplink entry for every governed family that can be opened that way.
- The exact seeded ancestry remains family-specific and is owned by `canonical_route_governance.dart`; this TODO must validate those concrete chains rather than rely on route-name inference.
- Prefix ancestry is valid only for families whose URL topology already expresses the canonical parent chain; it must not override product-defined ancestry such as `home > event`.
- Startup/deferred-link bootstrap stack seeding remains a deliberate exception, not the generic solution for browser-history cold entry.

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

## Implementation-Level Review Loop

- **Status:** `pending`
- **Why this loop is mandatory now:** user explicitly required an external elegance/performance review of the implemented result before analyzer/tests/build/browser validation continue.
- **Package mode:** `bounded-summary`
- **Required lenses:** `correctness`, `performance`, `elegance`, `structural-soundness`, `unnecessary-retained-complexity`
- **Exit criteria:** either the reviewers converge that the current implementation is the best path to validate, or material corrections are integrated and this TODO/baseline are refreshed before validation resumes.

---

## Approval Gate

- **Approval status:** `already approved in-session on 2026-04-10`
- **Execution constraint from this point forward:** implementation may continue only within this refreshed baseline. Any material architecture/scope change discovered during the pending implementation-level review loop or validation loop must update this TODO and obtain renewed **APROVADO** before proceeding beyond the approved boundary.
