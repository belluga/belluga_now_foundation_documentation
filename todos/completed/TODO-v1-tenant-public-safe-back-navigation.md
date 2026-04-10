# TODO (V1): Tenant-Public Safe Back Navigation

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed (`centralized safe-back contract delivered; active-lane cleanup synced on 2026-04-09`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Approval-Granted`, `Docs-Promoted`, `Automated-Validated`, `Promotion-Dismiss-Generalized`, `Closure-Synced`
**Next exact step:** None. Archived to `todos/completed` on `2026-04-09`.
**Owners:** Flutter Team
**Objective:** Establish a centralized, deterministic safe-back policy for tenant-public flows so direct URL entry, deep links, push entry, and any empty-stack return path never leave the user at a dead end or an empty root.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before implementation + one final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `docs`

**Last confirmed truth:** `2026-04-05` the shared tenant-public safe-back helper, route-specific fallbacks, and promotion-boundary dismissal behavior were implemented and documented; focused Flutter validation plus analyzer evidence are recorded below, and no unresolved product work remains in this lane.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()`, `WebAnonymousFallbackGuard()` |
| `/agenda/evento/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/mapa` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()`, `AnyLocationRouteGuard()` |
| `/mapa/poi` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()`, `AnyLocationRouteGuard()` |
| `/profile` (web unauthenticated redirect path) | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()`, `AuthRouteGuard()` -> `AppPromotionRoute` |
| `/baixe-o-app` (promotion boundary) | tenant | `tenant` | `tenant_public` | `n/a` | canonical promotion surface |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/map_poi_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/partner_catalog_and_offer_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for route ownership, navigation stack integrity, anonymous/public route behavior, and shared tenant-public UI contracts.
- `map_poi_module.md`: authoritative for `/mapa` and `/mapa/poi`, including direct-open POI behavior and map fallback resilience.
- `events_module.md`: authoritative for event-detail entry semantics and event-first discovery continuity.
- `partner_catalog_and_offer_module.md`: authoritative for `/parceiro/:slug` public-detail semantics.

### Decision Consolidation Targets

- Promote the centralized tenant-public safe-back contract and approved fallback matrix into `foundation_documentation/modules/flutter_client_experience_module.md`.
- Promote map-specific route fallback semantics into `foundation_documentation/modules/map_poi_module.md`.
- Promote the approved `/agenda` root-back clarification into `foundation_documentation/screens/modulo_agenda.md`.
- Promote partner/event detail fallback semantics into their module anchors only after product approval freezes them as durable behavior.

---

## References

- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/screens/modulo_agenda.md`
- `foundation_documentation/todos/completed/TODO-v1-route-url-only-hydration-hardening.md`
- `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart`
- `flutter-app/lib/presentation/tenant_public/discovery/discovery_screen.dart`
- `flutter-app/lib/presentation/shared/init/screens/init_screen/init_screen.dart`
- `flutter-app/lib/application/router/guards/auth_route_guard.dart`
- `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- `flutter-app/lib/application/router/modular_app/module_settings.dart`
- `flutter-app/test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- `flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- `flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- `flutter-app/test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`

---

## Scope

- Freeze one centralized tenant-public safe-back policy for public routes that may be entered with no prior stack history.
- Cover the real runtime entry modes already present in the app:
  - direct URL / refresh,
  - deep links,
  - push entry through `pushPath(...)`,
  - init-time path override through `replacePath(...)`,
  - normal in-app push entry where history does exist.
- Unify system back, visible back buttons, and shared-shell back actions around the same policy.
- Define the route-specific fallback matrix for the current high-risk tenant-public surfaces:
  - `/agenda`,
  - `/agenda/evento/:slug`,
  - `/parceiro/:slug`,
  - `/mapa`,
  - `/mapa/poi`.
- Define promotion-boundary dismissal behavior when auth-owned web routes redirect to `AppPromotionRoute`, so closing promotion never leaves the user at an empty shell/root.
- Preserve local-state-first back consumption where it already exists and is correct.
- Define explicit validation targets for no-history entry, stack-preserving return, and fallback determinism.

## Out of Scope

- Route-path redesign or URL-contract changes.
- Adding `returnTo` query params or route extras in this lane unless the shared-helper strategy fails validation.
- Tenant-admin navigation redesign.
- Browser-only history or shell redesign outside the tenant-public flows listed here.
- Backend/API changes.
- Any code implementation in this TODO authoring turn.

---

## Current Shared Reuse Baseline

- There is **no existing shared tenant-public safe-back helper** today.
- `DiscoveryScreen` is the best existing tenant-public reference pattern:
  - it consumes local screen state first,
  - then tries stack removal,
  - then falls back to a deterministic route.
- `ImmersiveDetailScreen` is already the shared shell used by multiple public detail surfaces, so it is the narrowest shared place to extend for detail-route fallback behavior.

### Evidence

- Discovery local-state-first + stack-first fallback: `flutter-app/lib/presentation/tenant_public/discovery/discovery_screen.dart:100-110`
- Shared immersive shell raw `pop()` today: `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart:163-171`

---

## Current Findings

### Shared immersive details still depend on implicit history

- `ImmersiveDetailScreen` wires the shared back button to raw `context.router.pop()`.
- Evidence: `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart:163-171`
- Impact: direct-open `/agenda/evento/:slug` and `/parceiro/:slug` can leave the user with no safe destination.

### Map always hard-resets to Home instead of honoring valid prior history

- `MapScreen` intercepts system back and replaces all routes with `TenantHomeRoute`.
- Its visible back button also hard-resets to `TenantHomeRoute`.
- Evidence: `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart:62-69`, `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart:103-116`
- Impact: the map avoids empty-root failure, but it breaks source continuity whenever a valid previous route exists.

### Agenda root is internally inconsistent today

- `EventSearchScreen` system/hardware back falls back to `TenantHomeRoute`.
- Its header back uses `ProfileRoute` when there is no history.
- Evidence: `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart:50-57`, `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart:188-194`
- Existing doc already says that if Agenda is the root, back should go to `Perfil`.
- Evidence: `foundation_documentation/screens/modulo_agenda.md:15`

### Direct-open entry is a real runtime behavior, not a hypothetical edge case

- Init may boot directly into a route path with `replacePath(initialPath)`.
- Push entry uses `pushPath(...)`.
- Evidence: `flutter-app/lib/presentation/shared/init/screens/init_screen/init_screen.dart:142-159`, `flutter-app/lib/application/router/modular_app/module_settings.dart:152-170`
- Impact: any tenant-public direct-entry surface must be correct even with an empty route stack.

### Existing route/module policy already expects deterministic public behavior

- Flutter module SLO explicitly includes navigation stack integrity.
- Public map routes already preserve the originally requested target through the location soft gate.
- Map module already assigns Flutter the responsibility for resilient fallback behavior on guarded/internal flows.
- Evidence: `foundation_documentation/modules/flutter_client_experience_module.md:35`, `foundation_documentation/modules/flutter_client_experience_module.md:68-69`, `foundation_documentation/modules/map_poi_module.md:55-64`

### Promotion boundary can still dead-end after auth-owned web redirects

- `AuthRouteGuard` redirects unauthorized web access to auth-owned routes such as `/profile` into `AppPromotionRoute`.
- `AppPromotionScreen` close currently uses raw `pop()`, which can leave no meaningful route when the promotion boundary was reached through `redirectUntil(...)` without real tenant-public history.
- Evidence: `flutter-app/lib/application/router/guards/auth_route_guard.dart`, `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- Impact: closing the promotion boundary after tapping `Profile` on web can still leave a blank/empty state instead of a deterministic fallback.

---

## Complexity Classification + Checkpoint Policy

- **Complexity:** `medium`
- **Checkpoint policy:** one planning checkpoint before approval + final decision-adherence review before delivery.

---

## Plan Review Gate (Medium)

### Issue Card `NAV-01` — Shared immersive shell still allows dead-end back on direct-open detail routes

- **Severity:** `high`
- **Evidence:** `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart:163-171`
- **Why now:** one raw `pop()` in the shared shell risks both event detail and partner detail.
- **Option A:** patch individual callers only.
  - Effort: `low`
  - Risk: `high`
  - Blast radius: `medium`
  - Maintenance burden: `high`
- **Option B (recommended):** add a shared tenant-public safe-back helper and let shared detail routes declare route-specific fallbacks.
  - Effort: `medium`
  - Risk: `medium`
  - Blast radius: `medium`
  - Maintenance burden: `low`
- **Option C:** redesign route contracts around `returnTo`/extra params.
  - Effort: `medium-high`
  - Risk: `medium`
  - Blast radius: `high`
  - Maintenance burden: `medium`

### Issue Card `NAV-02` — Agenda root disagrees with its own documented fallback

- **Severity:** `medium`
- **Evidence:** `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart:50-57`, `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart:188-194`, `foundation_documentation/screens/modulo_agenda.md:15`
- **Why now:** users get different destinations depending on whether they use system back or the visible header back.
- **Option A:** keep the inconsistency.
  - Effort: `none`
  - Risk: `medium`
  - Blast radius: `low`
  - Maintenance burden: `medium`
- **Option B (recommended):** unify system and header back under the documented root fallback.
  - Effort: `low`
  - Risk: `low`
  - Blast radius: `low`
  - Maintenance burden: `low`
- **Option C:** change the doc to match current system-back behavior.
  - Effort: `low`
  - Risk: `medium`
  - Blast radius: `low`
  - Maintenance burden: `medium`

### Issue Card `NAV-03` — Map back is safe from emptiness but not stack-aware

- **Severity:** `medium`
- **Evidence:** `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart:62-69`, `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart:103-116`
- **Why now:** the current hard reset protects root-opened map entry, but it regresses continuity for in-app entry and ignores the stack-first rule we already want elsewhere.
- **Option A:** preserve unconditional reset to Home.
  - Effort: `none`
  - Risk: `medium`
  - Blast radius: `low`
  - Maintenance burden: `medium`
- **Option B (recommended):** make map follow the same centralized policy: stack first, fallback second.
  - Effort: `low-medium`
  - Risk: `low`
  - Blast radius: `low-medium`
  - Maintenance burden: `low`
- **Option C:** remove explicit back affordances and rely on browser/system behavior only.
  - Effort: `low`
  - Risk: `high`
  - Blast radius: `medium`
  - Maintenance burden: `medium`

---

## Failure Modes & Edge Cases

- Direct-open `/agenda/evento/:slug` with no prior stack.
- Direct-open `/parceiro/:slug` with no prior stack.
- Direct-open `/mapa` or `/mapa/poi` after location soft-gate resumes the requested target.
- Push entry into a detail route through `pushPath(...)` when the app is already alive but the destination is effectively the only route in the active router stack.
- Normal in-app navigation where a valid previous route does exist and must still win over the fallback matrix.
- Discovery-style local state consumption must still run before fallback navigation.
- Guarded fallback destinations may themselves redirect; the safe-back policy must remain deterministic and non-empty.
- System back and visible back on the same route must land in the same destination under the same stack conditions.

---

## Assumptions Preview

| ID | Assumption | Evidence | If false | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The user-facing failure is primarily caused by no-history direct-entry into routes that use raw `pop()` or conflicting fallback logic. | `immersive_detail_screen.dart:163-171`, `map_screen.dart:62-69`, `event_search_screen.dart:50-57`, `init_screen.dart:142-159`, `module_settings.dart:152-170` | A broader router-stack instrumentation change would be needed. | `high` | Keep as assumption. |
| `A-02` | One shared helper/policy can fix the tenant-public lane without changing route URLs or adding new params. | Discovery already proves the desired precedence order is viable in tenant-public. | The lane would expand into route-contract redesign. | `medium` | Keep as assumption. |
| `A-03` | Discovery local-state-first behavior is correct and must be preserved as the reference order of operations. | `flutter-app/lib/presentation/tenant_public/discovery/discovery_screen.dart:100-110` | The helper would need opt-out branches and the policy would no longer be universal. | `high` | Keep as assumption. |
| `A-04` | `/parceiro/:slug` should land on `DiscoveryRoute` when opened as root and the user presses back with no history. | Discovery is the closest tenant-public browse surface for partner detail. | The fallback mapping must be changed before code approval. | `medium` | Promote to decision. |
| `A-05` | `/agenda/evento/:slug` should land on `EventSearchRoute` when opened as root and the user presses back with no history. | Event detail semantically belongs to the agenda discovery surface. | The fallback mapping must change to `DiscoveryRoute` or `TenantHomeRoute`. | `medium` | Keep as explicit product ambiguity pending approval. |
| `A-06` | Auth-owned web redirects into the promotion boundary should still reuse the centralized safe-back principle: `pop` if meaningful history exists, otherwise deterministic fallback to `TenantHomeRoute` for `/profile`. | The current bug reproduces after `/profile -> AppPromotionRoute` on web, and user-approved fallback is Home. | Promotion dismissal would remain an empty-stack edge case outside the shared contract. | `high` | Promote to decision. |

---

## Module Decision Baseline Snapshot

| Baseline ID | Canonical source | Relevant prior decision | Planned handling | Evidence |
| --- | --- | --- | --- | --- |
| `M-01` | `flutter_client_experience_module.md` | Navigation stack integrity is an SLO; route ownership must stay deterministic. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md:35`, `foundation_documentation/modules/flutter_client_experience_module.md:44-69` |
| `M-02` | `flutter_client_experience_module.md` + `map_poi_module.md` | Public map routes preserve the originally requested target through the location gate; map fallback behavior must remain resilient. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md:68-69`, `foundation_documentation/modules/map_poi_module.md:55-64` |
| `M-03` | `modulo_agenda.md` | Agenda root fallback is `Perfil` when the screen is the root. | `Preserve` | `foundation_documentation/screens/modulo_agenda.md:15` |
| `M-04` | `TODO-v1-route-url-only-hydration-hardening.md` | Direct-open/refresh-sensitive public routes must behave deterministically and never rely on constructor-time history. | `Preserve` | `foundation_documentation/todos/completed/TODO-v1-route-url-only-hydration-hardening.md:6`, `foundation_documentation/todos/completed/TODO-v1-route-url-only-hydration-hardening.md:104-121` |
| `M-05` | diagnostic scan `2026-04-05` | No canonical no-history fallback exists yet for `/agenda/evento/:slug` or `/parceiro/:slug`. | `Define` | audit evidence in this TODO |

---

## Decision Baseline (Frozen)

- `D-01`: The centralized tenant-public safe-back policy is `local state first if the screen owns it -> pop/remove previous route if history exists -> route-specific fallback if no history exists`.
- `D-02`: The safe-back policy must live in one shared tenant-public helper/policy, not duplicated inline across screens.
- `D-03`: System back, visible back buttons, and shared-shell back actions on the same route must delegate to the same policy.
- `D-04`: Shared tenant-public detail shells must no longer use raw `pop()`; they must accept an explicit fallback destination.
- `D-05`: `/agenda` root fallback remains `ProfileRoute` when there is no previous stack entry.
- `D-06`: `/mapa` root fallback remains `TenantHomeRoute` when there is no previous stack entry.
- `D-07`: `/mapa/poi` root fallback remains `CityMapRoute` when there is no previous stack entry.
- `D-08`: `/parceiro/:slug` root fallback is proposed as `DiscoveryRoute`.
- `D-09`: `/agenda/evento/:slug` root fallback is `EventSearchRoute`.
- `D-10`: Upstream guards and the location permission route retain ownership of pre-screen redirect behavior; the safe-back policy applies only once the target screen is active.
- `D-11`: Discovery-style local-state consumption must remain valid and must not be regressed by the shared helper.
- `D-12`: The narrowest robust fix is shared-helper-based. New route params, extra objects, or `returnTo` semantics are explicitly deferred unless the helper strategy fails validation.
- `D-13`: Focused widget/controller validation is mandatory for no-history direct-open, stack-preserving return, and back-affordance parity on touched screens.
- `D-14`: When an auth-owned tenant-public web route redirects unauthenticated users into `AppPromotionRoute`, closing that promotion boundary must not rely on raw `pop()` alone; it follows the same stack-first/fallback-second principle.
- `D-15`: For the approved scope, auth-owned tenant-public web redirects that land in `AppPromotionRoute` dismiss stack-first when meaningful history exists and otherwise fall back deterministically to `TenantHomeRoute`. `/profile` is the confirmed reproducer; the shared helper covers the auth-owned redirect family rather than a single hardcoded path.

## Module Coherence Gate (Planned)

| Decision ID | Module coherence | Change intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | Aligned | Preserve | `M-01`, `M-04` |
| `D-02` | Aligned | Preserve | `M-01` |
| `D-03` | Aligned | Preserve | `M-01`, `M-03` |
| `D-04` | Aligned | Preserve | `M-01`, `M-04` |
| `D-05` | Aligned | Preserve | `M-03` |
| `D-06` | Aligned | Preserve | `M-02` |
| `D-07` | Aligned | Preserve | `M-02`, `M-04` |
| `D-08` | Aligned | Define | `M-05` |
| `D-09` | Provisionally aligned | Define | `M-05` |
| `D-10` | Aligned | Preserve | `M-02` |
| `D-11` | Aligned | Preserve | Discovery reference behavior |
| `D-12` | Aligned | Preserve | narrowest-change principle of this lane |
| `D-13` | Aligned | Preserve | `M-01`, `M-04` |
| `D-14` | Aligned | Preserve | `M-01`, centralized safe-back principle extended to promotion dismissal |
| `D-15` | Aligned | Define | user-approved `/profile` fallback to Home |

## Module Decision Consistency Matrix (Planned 1-1)

- `M-01` -> `Preserve` by `D-01`, `D-02`, `D-03`, `D-04`, `D-13`.
- `M-02` -> `Preserve` by `D-06`, `D-07`, `D-10`.
- `M-03` -> `Preserve` by `D-03`, `D-05`.
- `M-04` -> `Preserve` by `D-01`, `D-04`, `D-07`, `D-13`.
- `M-05` -> `Define` by `D-08`, `D-09`.
- `M-01` -> `Preserve` by `D-14`, `D-15` for promotion dismissal parity.

---

## Current Fallback Matrix Proposal

| Route surface | Entry modes in scope | If previous history exists | If no previous history exists | Current proposal status | Notes |
| --- | --- | --- | --- | --- | --- |
| `/agenda` | direct URL, deep link, push, normal in-app entry | `pop/remove previous route` | `ProfileRoute` | `Frozen` | Must match both system back and header back. |
| `/agenda/evento/:slug` | direct URL, deep link, push, normal in-app entry | `pop/remove previous route` | `EventSearchRoute` | `Frozen` | Approved human resolution on `2026-04-05`. |
| `/parceiro/:slug` | direct URL, deep link, push, normal in-app entry | `pop/remove previous route` | `DiscoveryRoute` | `Proposed and frozen for planning` | Chosen as the nearest browse surface for partner detail. |
| `/mapa` | direct URL, deep link, push, normal in-app entry | `pop/remove previous route` | `TenantHomeRoute` | `Frozen` | Upstream location gate still resumes `/mapa` first when applicable. |
| `/mapa/poi` | direct URL, deep link, push, normal in-app entry | `pop/remove previous route` | `CityMapRoute` | `Frozen` | Preserves POI-detail-to-map continuity on root-opened detail. |

### Fallback Matrix Rules

- The route-specific fallback only executes when there is genuinely no previous route to remove/pop.
- If a fallback destination is itself guarded or redirected by canonical guards, that guard owns the next deterministic destination.
- The helper must not invent a second custom fallback cascade beyond the declared route-specific safe destination.
- Map location gating remains upstream: the location flow must still resume the originally requested map target before any later back action applies this matrix.

---

## Resolved Product Approval

- `2026-04-05`: human approval granted for the last pending product choice.
- Approved no-history fallback for `/agenda/evento/:slug`: `EventSearchRoute`.
- The fallback matrix is now fully frozen for this lane.

---

## Execution Plan Summary

### Narrowest Robust Fix Strategy

Implement one shared tenant-public safe-back helper and apply it only to the current high-risk surfaces. Preserve local-state-first behavior where it already exists, keep route URLs unchanged, and avoid introducing new route-contract complexity unless validation proves the helper approach insufficient.

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-tenant-public-safe-back-navigation.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/screens/modulo_agenda.md`
- `flutter-app/lib/application/router/support/**` or equivalent shared navigation-support surface
- `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`
- `flutter-app/lib/presentation/tenant_public/map/screens/map_screen/map_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart`
- `flutter-app/lib/application/router/guards/auth_route_guard.dart`
- focused Flutter tests under `flutter-app/test/presentation/common/widgets/immersive_detail_screen/**`
- focused Flutter tests under `flutter-app/test/presentation/tenant_public/schedule/**`
- focused Flutter tests under `flutter-app/test/presentation/tenant/map/**`
- focused Flutter tests under `flutter-app/test/presentation/shared/promotion/**`

### Ordered Steps

1. Freeze and approve the fallback matrix, especially `/agenda/evento/:slug`.
2. Add fail-first tests for no-history direct-open and stack-preserving return on the touched surfaces.
3. Introduce the shared tenant-public safe-back helper/policy.
4. Migrate `ImmersiveDetailScreen` to delegate back through the helper with route-specific fallbacks.
5. Unify `/agenda` system back and header back under the same helper and approved fallback.
6. Apply the helper to `/mapa` and `/mapa/poi` so map becomes stack-first and fallback-second.
7. Extend promotion-boundary dismissal for auth-owned web redirects (`/profile` first) so `AppPromotionScreen` closes stack-first with `TenantHomeRoute` fallback.
8. Run focused Flutter tests plus analyzer, then promote durable decisions into canonical docs.

### Test Strategy

- `test-first`

---

## Validation Targets (Explicit)

- Direct-open `/agenda/evento/:slug` with no history must never dead-end.
- Direct-open `/parceiro/:slug` with no history must never dead-end.
- Direct-open `/mapa` and `/mapa/poi` with no history must land on their approved safe destination when back is triggered.
- In-app entry with valid history must still return to the actual previous route before any fallback mapping is used.
- `/agenda` system back and header back must behave identically under the same stack conditions.
- Closing `AppPromotionRoute` after unauthenticated web access to `/profile` must never leave an empty shell; it must return to Home when no valid history exists.
- Discovery local-state reset behavior must remain unchanged by the new shared helper.
- The helper must work for both system back and visible/shared-shell back affordances.

## Definition of Done

- The tenant-public lane has one documented centralized safe-back contract.
- The fallback matrix for the touched public routes is explicit and approved.
- Direct-open/no-history behavior is deterministic and non-empty for every route in this TODO.
- Valid prior history always wins over fallback routing.
- `/agenda` matches its documented root fallback across both system and visible back.
- Auth-owned promotion-boundary dismissal no longer dead-ends on web root-opened `/profile`.
- Canonical docs and focused tests are updated before merge.
- `fvm dart analyze --format machine` passes after implementation.

## Validation Steps (Planned)

- `fvm flutter test flutter-app/test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- `fvm flutter test flutter-app/test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- `fvm flutter test flutter-app/test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- `fvm flutter test flutter-app/test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- `fvm flutter test flutter-app/test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- `fvm flutter test flutter-app/test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen_test.dart`
- `fvm flutter test <new focused widget tests for event-search back parity>`
- `fvm flutter test <new focused widget tests for map-screen safe-back>`
- `fvm flutter test <new focused widget tests for promotion dismiss fallback>`
- `fvm dart analyze --format machine`

## Validation Result Snapshot (`2026-04-05`)

- Passed: `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- Passed: `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- Passed: `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`
- Passed: `fvm flutter test test/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller_test.dart`
- Passed: `fvm flutter test test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart`
- Passed: `fvm flutter test test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart`
- Passed: `fvm dart analyze --format machine`

## Decision Adherence Validation

| Decision ID | Status | Evidence |
| --- | --- | --- |
| `D-01` | `Adherent` | shared helper + adopting screens/widgets/tests landed; validation snapshot passed |
| `D-02` | `Adherent` | `flutter-app/lib/application/router/support/tenant_public_safe_back.dart` |
| `D-03` | `Adherent` | Agenda `PopScope` + header back, Map `PopScope` + visible back, immersive shell host callbacks now converge on one helper path |
| `D-04` | `Adherent` | `ImmersiveDetailScreen` now accepts host-owned `onBackPressed`; event + partner detail use route-specific helper calls |
| `D-05` | `Adherent` | `EventSearchScreen` now falls back to `ProfileRoute`; focused Agenda tests passed |
| `D-06` | `Adherent` | `CityMapRoutePage` passes `TenantHomeRoute` fallback into `MapScreen`; focused Map tests passed |
| `D-07` | `Adherent` | `PoiDetailsRoutePage` passes `CityMapRoute` fallback into `MapScreen`; focused Map tests passed |
| `D-08` | `Adherent` | `AccountProfileDetailScreen` uses shared helper with `DiscoveryRoute` fallback; focused partner-detail test passed |
| `D-09` | `Adherent` | `ImmersiveEventDetailScreen` uses shared helper with `EventSearchRoute`; focused event-detail test passed |
| `D-10` | `Adherent` | no guard or location-gate routing contract was changed; docs promoted only for active-screen back semantics |
| `D-11` | `Adherent` | `discovery_screen_controller_test.dart` passed, preserving local-state-first behavior |
| `D-12` | `Adherent` | no `returnTo` params or route-contract redesign introduced; helper-based implementation only |
| `D-13` | `Adherent` | validation snapshot covers immersive shell, event detail, partner detail, agenda, map, discovery, and analyzer |

## Module Decision Consistency Validation

| Module baseline | Delivery status | Evidence |
| --- | --- | --- |
| `M-01` | `Preserved` | centralized safe-back helper and stack-first behavior now implemented without altering scope ownership |
| `M-02` | `Preserved` | map location gate remains upstream; active map back semantics now match approved fallback matrix |
| `M-03` | `Preserved` | Agenda root fallback now matches `Perfil` across system/header back and screen doc |
| `M-04` | `Preserved` | direct-open public routes now have explicit no-history behavior instead of implicit stack assumptions |
| `M-05` | `Preserved` | new canonical fallback rules for `/agenda/evento/:slug` and `/parceiro/:slug` are now approved and promoted |
