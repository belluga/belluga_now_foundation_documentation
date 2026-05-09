# TODO (V1): Tenant-Public Web Desktop Mobile Frame

**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Automated-Validated`, `Manual-Browser-Smoke-Pending`
**Next exact step:** Run manual browser smoke on the representative wide desktop tenant-public routes, then close the TODO if no framing exceptions are found.
**Owners:** Flutter Team
**Objective:** Establish a shared tenant-public desktop-web frame so wide browser viewports render the existing mobile-first Belluga/Bóora routes inside a centered mobile-width boundary instead of stretching the app full width.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + one final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`

**Direct-to-TODO rationale:** safe. This request is already one bounded Flutter presentation slice with one primary user-facing objective, no intended backend/API change, and one shared implementation boundary likely living at the app/router shell instead of multiple unrelated screen patches.
**Last confirmed truth:** `2026-04-09` repository scan confirms tenant-public web routes currently share the same Flutter mobile-first screens on browser, `MaterialApp.router` has no route-aware desktop-web width wrapper, and the pre-MVP web intake ledger explicitly requires dedicated tactical TODOs for cross-route shared fixes like this one.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route / Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/` | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted tenant-public home |
| `/descobrir` | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted discovery |
| `/parceiro/:slug` | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted public detail |
| `/static/:assetRef` | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted public detail |
| `/agenda/evento/:slug` | tenant host web | `tenant` | `tenant_public` | `n/a` | anonymous allowlisted immersive detail |
| `/invite?code=...` and `/convites?code=...` | tenant host web | `tenant` | `tenant_public` | `n/a` | preview-first, read-only, app handoff |
| `/baixe-o-app` | tenant host web | `tenant` | `tenant_public` | `n/a` | canonical app-promotion boundary |
| `/mapa` and `/mapa/poi` | tenant host web | `tenant` | `tenant_public` | `n/a` | location-gated public map |
| `/location/permission` | tenant host web | `tenant` | `tenant_public` | `n/a` | shared location gate continuation |
| `/profile` | tenant host web boundary | `tenant` | `tenant_public` | `n/a` | web identity boundary still resolves through app-promotion flow |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/invite_and_social_loop_module.md`, `foundation_documentation/modules/map_poi_module.md`, `foundation_documentation/modules/onboarding_flow_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for tenant-public route ownership, anonymous web allowlist, auth/promotion boundaries, and controller-first presentation rules. No desktop-web mobile-frame rule is currently frozen for shared tenant-public routes.
- `invite_and_social_loop_module.md`: authoritative for invite landing read-only web posture and app handoff behavior; the frame must not widen web invite capabilities.
- `map_poi_module.md`: authoritative for public map behavior; the frame must not silently redesign map semantics or location-gating behavior.
- `onboarding_flow_module.md`: authoritative where app-promotion and boundary handoff semantics are affected.

### Decision Consolidation Targets

- Promote the finalized desktop-web mobile-frame rule into `foundation_documentation/modules/flutter_client_experience_module.md` because it becomes an enduring tenant-public web presentation contract.
- Promote secondary module docs only if implementation reveals route-specific exceptions or a real canonical conflict in invite/map/onboarding surfaces.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-pre-mvp-web-small-fixes-intake.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `flutter-app/lib/application/application_contract.dart`
- `flutter-app/lib/application/router/modular_app/modules/home_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/discovery_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/schedule_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/invites_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/map_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/app_promotion_module.dart`
- `flutter-app/lib/application/router/modular_app/modules/initialization_module.dart`

---

## Scope

- Add one shared Flutter presentation wrapper that constrains wide tenant-public web routes to a centered mobile-width content frame.
- Keep the current route graph, guards, controller ownership, and screen widget trees intact; this is a boundary/layout change, not a screen redesign.
- Preserve current behavior on native mobile and on narrow web viewports where the available width is already mobile-sized.
- Keep the existing pre-MVP web posture intact: read-only/promotional web, app handoff for trust/auth gates, no web mutation expansion.
- Apply the frame only to the shared tenant-public/public-detail/promotion/location-boundary surfaces covered by this TODO, not to landlord, tenant-admin, or workspace shells.

## Out of Scope

- Dedicated desktop layouts, desktop navigation chrome, or two-column redesigns for tenant-public routes.
- Backend/API/schema/route ownership changes.
- Reworking screen-local content hierarchy beyond what is strictly needed to fit inside the shared frame.
- Narrowing tenant-admin, landlord-area, or workspace shells.
- Introducing special desktop-only behavior that diverges from the mobile route/widget contract.

---

## Module Decision Baseline Snapshot

- `FCX-SCOPE-01`: tenant-public route ownership and web allowlist are already fixed by `flutter_client_experience_module.md` sections `2.0 Scope/Subscope Ownership` and `2.1 Domain Rules`.
- `FCX-PRES-01`: screens remain pure UI consumers of controller-owned state under `flutter_client_experience_module.md` section `2.1.1 Presentation DI Matrix (Canonical)`.
- `INV-11`: invite web behavior in V1 stays promotion/read-only; app owns invite acceptance and trust-action mutations. Evidence: `foundation_documentation/modules/invite_and_social_loop_module.md` decision `INV-11`.

---

## Decision Baseline (Frozen)

- `D-01`: This slice is Flutter presentation-only. No backend/API/schema/route-contract changes are allowed.
- `D-02`: The desktop-web mobile frame applies only to the tenant-public route family and shared promotion/location boundary surfaces listed in this TODO.
- `D-03`: Tenant-admin, landlord, and workspace shells remain full-width/out of scope.
- `D-04`: The shared wrapper must preserve existing widget/controller boundaries by wrapping route children rather than redesigning each screen independently.
- `D-05`: On web, when an in-scope route has more width available than a mobile viewport, the rendered app content must be centered inside a shared `430` logical-pixel mobile-width max boundary instead of stretching to the full browser width.
- `D-06`: When the viewport is already narrower than that boundary, the route must continue to use the full available width with no special desktop treatment.
- `D-07`: Existing route guards, invite/promotion web boundaries, and location-gating behavior must remain unchanged.
- `D-08`: Delivery must include a shared route-classification test, analyzer validation, and manual browser smoke on representative tenant-public routes.
- `D-09`: The finalized durable rule must be promoted into `flutter_client_experience_module.md` before closure.

### Module Coherence

| Decision | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | `Aligned` | `Preserve` | `flutter_client_experience_module.md` section `2.1.1`; current request is layout-only |
| `D-02` | `Aligned` | `Preserve` | scope policy + tenant-public allowlist in `flutter_client_experience_module.md` |
| `D-03` | `Aligned` | `Preserve` | scope matrix in `flutter_client_experience_module.md` section `2.0` |
| `D-04` | `Aligned` | `Preserve` | presentation DI matrix in `flutter_client_experience_module.md` section `2.1.1` |
| `D-05` | `Supersede` | `Intentional` | current module docs define route ownership and allowlist but do not yet freeze a desktop-web mobile-frame policy |
| `D-06` | `Aligned` | `Preserve` | mobile-first product posture and current responsive behavior |
| `D-07` | `Aligned` | `Preserve` | `INV-11`, tenant-public allowlist, and location-gating contracts |
| `D-08` | `Aligned` | `Preserve` | Flutter analyzer/test discipline in workspace and architecture rules |
| `D-09` | `Aligned` | `Preserve` | TODO-driven execution rule for durable canonical truth |

### Module Decision Consistency Matrix

| Module Decision | Planned Handling | Evidence |
| --- | --- | --- |
| `FCX-SCOPE-01` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` sections `2.0`, `2.1` |
| `FCX-PRES-01` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1.1` |
| `INV-11` | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` |

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `MaterialApp.router` can host a route-aware wrapper without changing route ownership or controller resolution. | `application_contract.dart` already reads `widget.appRouter.topRoute` and `currentPath` for web telemetry/module support. | We would need to wrap individual route pages instead, expanding touched surfaces. | `Medium` | `Keep as Assumption` |
| `A-02` | A shared width frame is enough to fix the "full-width ugly version" problem without redesigning individual in-scope screens. | The reported problem is the root-level width expansion; several routes already rely on local components sized for mobile-first content. | The work would expand into route-by-route visual redesign and exceed this slice. | `Medium` | `Keep as Assumption` |
| `A-03` | Existing map/detail/promotion screens can remain functionally correct when constrained by a shared outer mobile-width frame on web. | Those routes already contain several local max-width/mobile-oriented components and are part of the same mobile-first tenant-public surface. | One or more routes would need explicit route-level exceptions or a narrower route set before implementation. | `Medium` | `Keep as Assumption` |
| `A-04` | No external dependency beyond local Flutter analyzer/tests and manual browser smoke is required. | This is a presentation-only Flutter change with no backend contract edits. | Delivery would need external runtime dependency coordination before completion. | `High` | `Keep as Assumption` |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-pre-mvp-web-small-fixes-intake.md`
- `foundation_documentation/todos/active/concluded_but_active/TODO-v1-tenant-public-web-desktop-mobile-frame.md`
- `flutter-app/lib/application/application_contract.dart`
- one new shared presentation/widget helper for route-aware web framing
- targeted Flutter tests for route classification + frame behavior
- `foundation_documentation/modules/flutter_client_experience_module.md`

### Ordered Steps

1. Implement a shared route-aware web frame helper that decides whether the current top route belongs to the tenant-public mobile-frame set.
2. Wire that helper into the shared app boundary so in-scope web routes are centered inside a mobile-width max frame while non-scoped routes remain untouched.
3. Add targeted tests proving:
   - in-scope tenant-public routes are framed on wide web widths;
   - non-scoped routes (admin/workspace/landlord) remain unframed;
   - narrow widths remain unchanged.
4. Promote the finalized durable presentation rule into `flutter_client_experience_module.md`.
5. Run analyzer and targeted tests, then perform manual browser smoke on representative in-scope routes.

### Test Strategy

- `test-after`

### Runtime / Rollout Notes

- No backend rollout or migration steps are expected.
- If implementation shows that one in-scope route needs a deliberate exception (for example a map-only full-bleed requirement), stop, update the TODO/module decision, and request renewed approval instead of silently widening or narrowing the scope.

---

## Plan Review Gate

### Issue Card `P-01` - a global frame could accidentally narrow admin/workspace/landlord surfaces

- Severity: `high`
- Evidence: `application_contract.dart` is the shared app root; admin and tenant-public routes coexist under the same `MaterialApp.router`.
- Why it matters now: a naive global wrapper would solve the public web issue but regress operational/admin web shells that are not part of the request.
- Option A: use a route-aware allowlist for only the tenant-public/promotion/location-boundary route set.
- Option B: narrow the entire web app globally.
- Option C: patch individual tenant-public screens one by one.
- Recommended option: `A` because it preserves scope and keeps the blast radius controlled.

### Issue Card `P-02` - route-by-route patches would create drift and increase maintenance

- Severity: `medium`
- Evidence: the issue affects Home, Discovery, public detail, invite, promotion, map, and boundary routes that currently share a common app shell.
- Why it matters now: patching each screen separately risks inconsistent widths and repeated future regressions.
- Option A: fix the issue once at the shared app/router boundary.
- Option B: patch only the currently visible problem screens.
- Option C: create desktop-specific layouts for each route.
- Recommended option: `A` because the user asked for a consistent mobile boundary across desktop web, not a screen-by-screen redesign.

### Failure Modes & Edge Cases

- a route classifier misses one tenant-public path and leaves it full width;
- the shared frame accidentally wraps a route that should remain full width;
- map or immersive detail visuals feel cramped enough to require an explicit exception decision;
- browser resize transitions cause layout jumps or unstable centering.

### Residual Unknowns / Risks

- The current canonical docs do not yet define the desktop-web mobile-frame rule, so documentation promotion is required for closure.
- The repo currently has no `foundation_documentation/project_constitution.md`; if later work depends on constitution-level system rules for this behavior, that gap must be handled outside this bounded Flutter slice.

### Independent Critique Gate

- Decision: `recommended` because this is a `medium` shared-surface presentation slice but stays inside one codebase and one primary implementation boundary.
- Constraint: no delegated/subagent critique is available in this turn because the user did not request parallel sub-agents.
- Bounded self-critique outcome: prefer one route-aware shared frame over route-by-route patches and keep the decision reversible through a narrow allowlist/exclusion model.

---

## Approval Gate

Implementation must not begin until the user replies with the explicit token: **APROVADO**.
