# TODO (V1): Boundary Route Dismissal Coherence

**Status:** Active
**Current delivery stage:** `Validation Passed`
**Qualifiers:** `Provisional`
**Next exact step:** Publish the validated checkpoint on feature branches and keep this TODO available only for residual boundary-flow follow-up if new edge cases surface.
**Owners:** Flutter Team
**Objective:** Establish one coherent dismissal/back contract for boundary routes that interrupt or redirect navigation flows, so location-permission and app-promotion/pre-MVP screens never dead-end, never bypass AutoRoute semantics, and never diverge between visible back, system/device back, and no-history fallback behavior.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** freeze contract first, then implementation, then external review, then validation.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `docs`, `device-validation`

**Direct-to-TODO rationale:** safe. This is one bounded Flutter navigation slice focused on boundary/gate flows only. The structural route-governance cutover is already published on its own feature branch; this TODO covers the remaining coherent-dismissal contract for route interruptions and promotion boundaries.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/location/permission` | tenant host / app runtime | `tenant` | `tenant_public` | `n/a` | map location gate boundary |
| `/baixe-o-app` | tenant host / web boundary | `tenant` | `tenant_public` | `n/a` | app-promotion boundary |
| auth-gated redirect flows landing on promotion | tenant host / web boundary | `tenant` | `tenant_public` | `n/a` | anonymous web promotion handoff |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/map_poi_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`

---

## References

- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-canonical-back-navigation-governance-cutover.md`
- `lib/application/router/guards/any_location_route_guard.dart`
- `lib/application/router/guards/auth_route_guard.dart`
- `lib/application/router/guards/web_anonymous_promotion_guard.dart`
- `lib/application/router/modular_app/modules/initialization_module.dart`
- `lib/application/router/modular_app/modules/app_promotion_module.dart`
- `lib/application/router/support/canonical_route_governance.dart`
- `lib/application/router/support/route_redirect_path.dart`
- `lib/presentation/shared/location_permission/screens/location_permission_screen/location_permission_screen.dart`
- `lib/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_screen.dart`

---

## Scope

- Reassess whether `/location/permission` should remain a special result-return boundary or should be partially absorbed into the canonical route-policy model.
- Freeze one dismissal contract for boundary routes:
  - when real history exists,
  - when the route was entered as a redirect/gate,
  - when the route is root-opened with no prior history.
- Ensure visible back and system/device back converge on the same semantic outcome for location-permission and app-promotion flows.
- Preserve AutoRoute as the navigation authority; do not introduce browser-history seeding or manual stack fabrication as a fix.
- Add automated coverage for the boundary cases that were previously untested:
  - `home -> mapa -> location permission -> back`
  - root-open `/location/permission` -> back fallback
  - promotion route close/system back with auth-owned redirect
  - promotion success-state dismissal with and without stack
- Validate the final behavior through Flutter tests and route/browser/device checks relevant to these flows.

## Out of Scope

- New URL contracts or route-path redesign.
- Backend/API changes.
- Deferred deep-link redesign.
- Tenant-admin / landlord navigation changes unrelated to boundary flows.
- Generic browser-history seeding.

---

## Definition of Done

- Location-permission and app-promotion/pre-MVP flows use one coherent dismissal contract with explicit no-history behavior.
- No boundary route can trap the user in a dead-end state when visible back or system/device back is invoked.
- The implementation stays AutoRoute-native and does not bypass the router with ad-hoc navigation state.
- Focused Flutter tests cover both route families, including stack and no-stack cases.
- Module docs are updated where the durable contract changed.

---

## Decision Baseline (Frozen)

- `D-01`: Boundary/gate routes may have dismissal semantics different from ordinary route-pop, but they still must expose one coherent contract across visible back and system/device back.
- `D-02`: AutoRoute remains the canonical navigation authority; fixes may not depend on synthetic browser-history seeding or manual ancestry fabrication.
- `D-03`: Promotion boundary behavior must remain redirect-aware: no-history dismissal may resolve to invite preview or home according to the redirect contract.
- `D-04`: Location-permission behavior must remain map-target-aware: grant/continue resumes the guarded target, while dismiss/cancel must resolve coherently to prior history or deterministic fallback.
- `D-05`: If a route remains exempt from generic canonical route-back governance, that exemption must still be backed by an explicit boundary-flow contract and tests.
- `D-06`: Tenant-public root switching triggered by visible in-app navigation must stay AutoRoute-native. Forward user navigation that must preserve the predecessor route in browser/device history (for example `Home -> Mapa` and `Home -> Perfil`) must use ordinary AutoRoute `push(...)`; returning to an already-existing root such as Home may reuse AutoRoute stack navigation (`navigate(...)`). `replaceAll(...)` resets are not allowed for this tenant-public bottom-nav flow.
- `D-07`: Warm tenant-public map entry may not rely on unresolved `guard -> redirectUntil(...)` alone, because that model does not commit a browser-history entry before the permission boundary on web. For visible user-initiated map entry, Flutter must push `/location/permission` explicitly as a normal boundary route; in that warm flow, the permission screen reports `granted|continueWithoutLocation|cancelled` through the injected boundary callback, and the caller-owned flow replaces the boundary with `/mapa` for the success/soft-gate outcomes or pops back on cancel. Route guards remain authoritative for direct URL/deep-link entry.

---

## Validation Steps

1. Freeze the boundary-flow contract after code + docs inspection and external critique.
2. Implement the contract in Flutter routes/screens/guards without bypassing AutoRoute.
3. Run focused Flutter tests for permission and promotion boundary flows.
4. Run `fvm dart analyze --format machine`.
5. Run browser/device validation for the affected flows.
