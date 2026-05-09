# TODO (Store Release): Home Distance And Origin Refresh Regression

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual QA on 2026-05-02 found that Home agenda/distance behavior is not respecting the canonical radius/origin contract anymore:

- reducing the selected radius can leave farther events visible;
- changing the resolved profile/location origin does not force Home to reload from the new canonical origin;
- the current behavior looks like stale cached results are surviving a tighter radius or origin change instead of the repository issuing an authoritative refresh.

This is a release-surface regression because Home is the tenant-public entry route and radius/origin are already promoted behaviors under `HOM-05`, `HOM-06`, and `HOM-07`. The fix must preserve repository ownership and canonical location-origin resolution. It must not be patched with screen-local filtering hacks, route restarts, or parallel caches.

This TODO does **not** reopen the broader post-release identity-backed proximity program. It owns only the current-release regression: Home must immediately reflect the selected radius and current canonical origin. Manual QA on 2026-05-03 also froze one bounded product refinement here: when Home seeds the initial selected radius from the user-to-tenant-default-origin distance, that seed must honor a minimum floor of `10 km` before the normal tenant-configured clamp logic is applied.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `store-release-home-distance-origin-refresh`
- **Why this is the right current slice:** this is one bounded regression in the existing Home user flow: change radius or origin, and the Home agenda must re-query/render against that exact canonical state without stale leftovers.
- **Direct-to-TODO rationale:** safe. The expected behavior is already frozen in promoted Home/location contracts; the work is restoration and hardening, not new product discovery.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Regression`, `Store-Release-Blocker`, `Flutter`, `Potential-Cross-Stack`, `Home`, `Radius-Origin`, `Repository-Ownership`, `User-Flow-Impact`
- **Next exact step:** reproduce the regression with fail-first focused coverage, identify whether the fault is in Flutter repository invalidation, canonical origin propagation, or backend query semantics, then repair the authoritative refresh path.

## Contract Boundary
- This TODO owns Home agenda behavior after radius changes and canonical origin changes.
- It owns the refresh/invalidation path from `/profile` or any canonical origin update into Home agenda re-query behavior.
- It may absorb the minimum Laravel correction if backend request/query semantics are wrong for `origin_lat`, `origin_lng`, or `max_distance_meters`.
- It must not solve the regression with widget-local state filters, route restarts, or controller-to-controller relays.
- It does not own the broader post-release identity-backed proximity-preference roadmap or reusable reference-location core.

## References
- `foundation_documentation/todos/completed/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/active/post_release_hardening/TODO-store-release-proximity-preferences-and-location-origin.md`
- `foundation_documentation/modules/tenant_home_composer_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/map_poi_module.md`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/tenant_home_composer_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
- **Decision promotion targets:**
  - `tenant_home_composer_module.md` Home radius/origin refresh behavior and repository ownership.
  - `agenda_and_action_planner_module.md` backend-owned agenda filter execution and origin/radius query semantics.
  - `flutter_client_experience_module.md` canonical location-origin and user-flow refresh behavior if the Flutter consumer contract needs clarification.

## Scope
- [ ] Reproduce the stale-distance regression with fail-first focused coverage.
- [ ] Reproduce the stale-origin regression where a profile/location-origin change does not force Home to reload from the new canonical origin.
- [ ] Verify the exact authoritative Home request path for `origin_lat`, `origin_lng`, and `max_distance_meters`.
- [ ] Ensure Home agenda re-queries or invalidates authoritative repository state when the selected radius changes.
- [ ] Ensure Home agenda re-queries or invalidates authoritative repository state when the canonical origin changes.
- [ ] Ensure decreasing the radius removes farther items instead of leaving stale results from a previous wider query.
- [x] Ensure the initial Home radius seeded from tenant-default-origin distance applies a lower bound of `10 km` before the standard tenant bounds clamp.
- [ ] Ensure the fix preserves repository-owned state and canonical `LocationOriginService` semantics.

## Out of Scope
- [ ] The broader post-release identity-backed proximity preference program.
- [ ] New profile/location UI beyond what is already owned by `/profile`.
- [ ] Map-specific “search this area” redesign or broader map/filter UX.
- [ ] Replacing Home MVP client composition with a backend Home composer endpoint.
- [ ] Any unrelated Home favorites, invite, or discovery behavior.

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-01` Home agenda visible results are authoritative backend/repository state for the currently selected radius and canonical resolved origin; stale wider-radius results must not survive after a tighter selection.
- [ ] `D-02` Radius changes and canonical origin changes are repository-refresh boundaries, not widget-local filtering boundaries.
- [ ] `D-03` Home must use the canonical resolved origin from `LocationOriginService` and related persisted/profile-backed settings; it must not keep querying from a stale previous origin after the source of truth changes.
- [ ] `D-04` If the current repository contract cannot express the needed invalidation/reload semantics, the contract must be corrected at the repository boundary rather than patched inside the Home UI.
- [ ] `D-05` This TODO restores current release behavior only and does not widen into the broader post-release proximity-preferences roadmap.
- [x] `D-06` Home's auto-seeded radius from tenant-default-origin distance must never initialize below `10 km`; this is a product floor on the seed value, not a widget-only display tweak.

## Acceptance Criteria
- [ ] Reducing Home radius updates visible agenda results in the same running app session and no farther-than-radius stale items remain.
- [ ] Changing the canonical origin used by Home triggers a new Home agenda load against that origin in the same running app session.
- [ ] Home continues to consume repository-owned agenda/origin state and does not depend on sibling-controller relays, route restarts, or widget-local duplicate caches.
- [ ] Focused fail-first automated coverage exists for both radius-tightening and origin-change regressions.
- [x] Focused automated coverage exists for the `10 km` minimum on tenant-default-origin radius seeding.

## Definition of Done
- [ ] All acceptance criteria have concrete evidence in the Completion Evidence Matrix.
- [ ] Focused Flutter tests pass for repository/controller/widget refresh behavior.
- [ ] Focused Laravel tests pass if backend query/filter semantics are touched.
- [ ] `fvm dart analyze --format machine` passes or unrelated diagnostics are explicitly isolated.
- [ ] Device/runtime evidence is recorded for the final radius/origin behavior when focused tests alone are insufficient.

## Validation Steps
- [ ] Flutter automated: reducing radius refreshes Home agenda from repository-owned state and removes items now outside the selected radius.
- [ ] Flutter automated: canonical origin changes trigger Home agenda refresh from the new origin without route restart.
- [x] Flutter automated: tenant-default-origin radius seeding applies the `10 km` minimum floor before persisted/query refresh behavior continues.
- [ ] Architecture scan: no controller relay or widget-local duplicate source-of-truth is introduced.
- [ ] Laravel automated (if touched): agenda query/filter semantics honor `origin_lat`, `origin_lng`, and `max_distance_meters` exactly as requested.
- [ ] Device/runtime final: current Home behavior on the release flavor proves radius tightening and origin change both refresh the visible list correctly.
