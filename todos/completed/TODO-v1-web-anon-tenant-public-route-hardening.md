# TODO (V1): Web Anonymous Tenant-Public Route Hardening

**Status:** Completed  
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`  
**Secondary Module Anchor:** `foundation_documentation/modules/map_poi_module.md`  
**Complexity:** medium  
**Checkpoint Policy:** one review checkpoint before approval

## 1. Context
The current tenant-public Flutter route map does not fully enforce the V1 web-to-app policy for unauthenticated web sessions.

The intended V1 posture is:
- web anonymous tenant-public is a constrained public surface,
- identity-owned or trust-gated routes must not remain reachable by direct URL,
- some legacy/technical routes need explicit classification instead of remaining implicitly exposed.

The current audit found three classes of routes:
1. routes that should remain publicly accessible on anonymous web,
2. routes that should be blocked or promotion-gated on anonymous web,
3. routes whose product status is still unclear and must be aligned before implementation.

This TODO exists to freeze the route classification and then harden guards so direct URL access cannot bypass the V1 policy.

## 2. References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-web-to-app-policy.md`
- `foundation_documentation/policies/web_to_app_promotion_policy.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/todos/completed/TODO-v1-location-permission-screen-minimal-alignment.md`

## 3. Scope
In scope:
- tenant-public route audit and classification for anonymous web
- direct-URL hardening for routes that must not remain reachable anonymously
- guard strategy for:
  - public allowlisted routes
  - promotion-gated routes
  - internal/location fallback routes that remain public but should be unified later
- test coverage for anonymous web route access behavior

Out of scope:
- implementing QR-code web login
- redesigning route IA beyond the explicit routes listed here
- broader IA redesign beyond unifying the location gate surface

## 4. Current Audit Snapshot

### 4.1 Public on anonymous web (current approved direction)
- `/`
- `/privacy-policy`
- `/descobrir`
- `/parceiro/:slug`
- `/agenda/evento/:slug`
- `/mapa`
- `/mapa/poi`
- `/invite?code=...`
- `/convites?code=...` (compatibility invite landing only)
- `/location/permission`
- `/baixe-o-app`

### 4.2 Must not remain directly reachable on anonymous web
- `/agenda`
- `/menu`
- `/profile`
- `/workspace`
- `/workspace/:accountSlug`
- `/workspace/:accountSlug/events/create`
- `/convites` without `code`
- `/convites/compartilhar`
- `/auth/login`
- `/auth/recover_password`
- `/auth/create-password`

### 4.3 Product status now frozen for implementation
- blocked non-identity routes on anonymous web use canonical fallback to `/`
- `/invite` without valid `code` uses canonical fallback to `/`
- `/convites` without valid `code` uses canonical fallback to `/`
- `/location/permission` remains publicly reachable on anonymous web in V1
- `/location/not-live` is treated as leftover legacy and should be removed instead of preserved as a second public state
- before implementation, the unified location-gate layout must be validated via Stitch and only then translated faithfully into Flutter

## 5. Decision Baseline (Frozen So Far)
- `D-01` Anonymous web tenant-public V1 is not an open-by-default route space; direct URL access must respect the same policy boundary as in-app navigation.
- `D-02` Public anonymous web allowlist currently includes Home, Discovery, Event Detail, Account Profile Detail, Invite preview, Map, and the location gate surfaces.
- `D-03` Invite landing compatibility must be explicit: `/invite` and `/convites` remain public only when acting as invite landing with valid `code`; missing/invalid `code` must fall back to `/`.
- `D-04` Identity-owned routes (`/profile`, `/workspace*`) must not remain directly reachable on anonymous web.
- `D-05` Auth continuation routes (`/auth/*`) must not act as anonymous web fallback for tenant-public V1.
- `D-06` `/location/permission` remains publicly reachable on anonymous web as the canonical location gate surface.
- `D-07` `/menu` is legacy for this scope and should be removed together with widgets/routes that are not reused elsewhere.
- `D-08` `/agenda` remains technically alive in code, but anonymous web must treat it as blocked for this V1 hardening slice.
- `D-09` Blocked non-identity routes on anonymous web must use canonical fallback to `/`, not promotion handoff.
- `D-10` `/invite` and `/convites` without valid `code` must use canonical fallback to `/`.
- `D-11` `/location/not-live` is leftover legacy and should be removed together with route wiring/screens that are not reused elsewhere.
- `D-12` The canonical public location gate must be visually validated in Stitch before Flutter implementation, and Flutter should follow that approved layout faithfully.
- `D-13` The approved visual/copy baseline for the canonical location gate is tracked in `TODO-v1-location-permission-screen-minimal-alignment.md`.

## 6. Implementation Preconditions
- Synchronize route-hardening docs with the final allowlist/blocklist/fallback matrix before code changes.
- Generate and review the canonical public location-gate layout in Stitch before converging/removing legacy location routing in Flutter.

## 7. Proposed Implementation Shape
1. Keep `TenantRouteGuard` consistently applied across tenant-public routes that currently lack host/scope enforcement.
2. Introduce explicit anonymous-web route classification instead of relying on ad hoc per-screen behavior.
3. Split anonymous-web denial behavior into two policies:
   - promotion-gated for identity/auth-owned routes,
   - canonical fallback for routes that simply do not belong to the anonymous web public surface.
4. Remove legacy `/menu` route wiring and any menu-only widgets that are no longer reused elsewhere.
5. Keep `/location/permission` as the canonical public location gate and remove legacy `/location/not-live` routing/screens that no longer belong to the product surface.
6. Add route tests for direct URL anonymous-web access to every classified route.

## 8. Risks / Notes
- Some modules still reflect earlier assumptions where Map remained public and Agenda/Menu were treated as normal tenant-public routes; those docs must stay synchronized with the final classification before code changes.
- `/convites` and `/invite` currently share the same Flutter screen/module, so guard behavior must not accidentally break invite-preview compatibility while blocking inbox-style access.
- `location/not-live` now has explicit product status as legacy debt to remove, not a state to preserve.

## 9. Delivery Outcome
- `TenantRouteGuard` was applied consistently to the audited tenant-public routes touched in this slice.
- Anonymous-web fallback/promotion guards were introduced so direct URL access now respects the same V1 boundary as in-app navigation.
- `/menu` route wiring and menu-only presentation files were removed.
- `/agenda` now falls back to `/` for anonymous web while `/agenda/evento/:slug` remains public.
- `/invite` and `/convites` now require valid `code` to remain public on anonymous web; missing/invalid `code` falls back to `/`.
- `/location/permission` remains the single public location gate; `/location/not-live` was removed.
- Route/module/guard tests now lock the anonymous-web allowlist and denial behavior.

## 10. Rule / Workflow Sources Used
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-route-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
