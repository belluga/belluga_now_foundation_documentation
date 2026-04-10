# TODO (V1): Map Location Origin Feedback

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Local-Implemented`
**Qualifiers:** `Automated-Validated`, `Web-Outside-Range-Validated`, `Web-Inside-Range-Burst-Validated`, `Remaining-Manual-Smoke-Pending`
**Next exact step:** Run the remaining manual smoke on denied flow, fixed/manual reference flow, and technical-unavailable copy parity for the `Você` action, then promote the finalized contract into canonical module docs.
**Owners:** Flutter Team
**Objective:** Replace the current sticky/early map location notice with a centralized location-origin feedback model for the `Você` action, using the existing `LocationResolutionPhase` and `LocationOriginSettings` contracts as the single source of truth.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one decision-adherence checkpoint before implementation + one targeted bug-verification checkpoint before closure.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/map_poi_module.md`
- **Secondary:** `foundation_documentation/modules/flutter_client_experience_module.md`, `foundation_documentation/screens/modulo_mapa_e_mobilidade.md`

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-visuals.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-frontend.md`
- `foundation_documentation/todos/completed/TODO-v1-tenant-public-safe-back-navigation.md`

---

## Scope
- Define the authoritative map-local feedback model for the `Você` / location action.
- Use the existing `LocationResolutionPhase` and `LocationOriginSettings` contracts to derive the visual/actionable state.
- Eliminate the current bug where the map publishes a false `unavailable` notice immediately after permission grant and leaves it stuck.
- Define badge, enablement, target recenter behavior, and transient notice behavior for all location-origin outcomes.
- Keep this work inside the `map visuals` lane; no backend/API changes.

## Out of Scope
- Redesign of the standalone permission screen copy/layout.
- Redesign of user settings for choosing fixed/manual location.
- New backend contracts for location origin.
- Docker/runtime orchestration changes unless the root cause unexpectedly leaves Flutter scope.

---

## Existing Contracts To Preserve
- `LocationResolutionPhase` remains the authoritative resolution-progress contract:
  - `unknown`
  - `resolving`
  - `resolved`
  - `permissionDenied`
  - `unavailable`
- `LocationOriginSettings` remains the authoritative effective-origin contract:
  - `userLiveLocation`
  - `tenantDefaultLocation`
  - `userFixedLocation`
- `LocationOriginReason` remains the authoritative reason taxonomy:
  - `live`
  - `outsideRange`
  - `unavailable`
  - `userPreference`
- This slice must project UI state from those contracts; it must not invent a parallel domain model.

---

## Root Cause Summary (Frozen)
- After permission is granted, the permission screen exits immediately and returns to the map.
- During map init, POI loading and best-effort location refresh run in parallel.
- The map can resolve origin and publish a transient `unavailable` notice before the newly granted live location reaches the repository streams.
- Once published, the current map notice does not automatically reconcile itself when the effective origin later resolves successfully.
- Therefore the bug is:
  - a timing/race condition at entry,
  - followed by a sticky UI state because feedback is not re-derived from the final resolved origin.

---

## Decision Baseline (Frozen)
- `D-01`: The `Você` action is a persistent location-origin status surface, not just a recenter button.
- `D-02`: The map must derive location feedback from the existing contracts only:
  - `LocationResolutionPhase`
  - `LocationOriginSettings`
- `D-03`: `Permission granted` is not treated as `origin resolved`.
- `D-04`: No transient map notice is emitted while the map is still in a pre-terminal resolution phase and no stable actionable origin is available yet.
- `D-05`: When a stable actionable origin already exists, the `Você` action may remain active even while a live-location attempt continues in the background.
- `D-06`: `userFixedLocation` has precedence over generic loading/error presentation and is not treated as an error state.
- `D-07`: If `LocationOriginSettings.usesUserFixedLocation == true`, the `Você` action shows a `home`-style badge and stays actionable, even if live location is being resolved in parallel.
- `D-08`: If `LocationOriginSettings.usesUserLiveLocation == true`, the `Você` action shows the live-location badge and remains actionable.
- `D-09`: If `LocationOriginSettings.usesTenantDefaultLocation == true` and `reason == outsideRange`, the `Você` action shows an alert badge and remains actionable.
- `D-10`: `permissionDenied` and technical `unavailable` are distinct states with different user-facing copy, even if they share the same error badge family.
- `D-11`: `permissionDenied` copy asks the user to authorize location.
- `D-12`: Technical `unavailable` copy explains that live location could not be determined now and the map is using a reference/fallback origin.
- `D-13`: The loading badge is shown while the map is still resolving location and there is no stable actionable origin yet; in that state the `Você` action remains disabled.
- `D-14`: Transition from `loading` to a terminal state is what authoritatively triggers the transient map notice.
- `D-15`: The transient location notice lives on the map screen, not on the permission screen.
- `D-16`: The transient location notice:
  - auto-dismisses after `10s`,
  - has explicit dismiss affordance,
  - dismisses on meaningful map interaction such as pan / click outside.
- `D-17`: Alert/error states may re-trigger the transient notice when the user taps the `Você` action again.
- `D-18`: Tapping the `Você` action must move the camera to the currently effective resolved origin for the active state:
  - live user coordinate when using live location,
  - user fixed/manual coordinate when using user preference,
  - tenant-default/fallback effective origin when using fallback states.
- `D-19`: The success/healthy state should not spam repeated notices on every rebuild; notice emission is tied to meaningful transition or explicit retry/tap behavior.
- `D-20`: This slice is sequentially subordinate to `TODO-v1-map-visuals.md`, but its frozen behavior is authoritative for the location action and transient notice semantics.

---

## Authoritative UI State Projection

### State A — Loading Without Stable Origin
- Inputs:
  - `phase in {unknown, resolving}`
  - no stable actionable `LocationOriginSettings`
- UI:
  - `Você` action disabled
  - loading badge visible
  - no transient notice yet

### State B — Live Location In Range
- Inputs:
  - `settings.usesUserLiveLocation == true`
- UI:
  - `Você` action enabled
  - live-location badge
  - transition from loading triggers success notice once

### State C — User Fixed / Manual Reference
- Inputs:
  - `settings.usesUserFixedLocation == true`
  - `reason == userPreference`
- UI:
  - `Você` action enabled
  - `home` badge
  - this is not treated as error
  - may remain visible even while live lookup continues in background

### State D — Outside Range
- Inputs:
  - `settings.usesTenantDefaultLocation == true`
  - `reason == outsideRange`
- UI:
  - `Você` action enabled
  - alert badge
  - notice explains the map is using the supported-area reference instead of the current live location

### State E — Permission Denied
- Inputs:
  - `phase == permissionDenied`
- UI:
  - `Você` action enabled
  - error badge
  - notice copy asks the user to authorize location
  - tap retries the resolution path / re-shows permission-oriented notice if unresolved

### State F — Technical Unavailable
- Inputs:
  - `phase == unavailable`
  - and/or effective origin uses fallback with `reason == unavailable`
- UI:
  - `Você` action enabled
  - error badge
  - notice copy explains technical unavailability / fallback origin usage
  - tap retries centering/resolution and may re-show the technical notice

---

## Interaction Rules

### `Você` Action Enablement
- Disabled only when:
  - the app is still resolving, and
  - there is no stable actionable origin to use yet.
- Enabled when any stable actionable origin already exists:
  - live location,
  - fixed/manual location,
  - outside-range fallback,
  - unavailable fallback,
  - permission-denied retry state.

### `Você` Action Tap Behavior
- Live origin:
  - center on live user coordinate
- Fixed/manual origin:
  - center on fixed/manual coordinate
- Outside-range fallback:
  - center on the effective fallback origin
  - re-show alert notice if tapped again
- Permission denied:
  - attempt retry/resolve flow
  - re-show permission notice if still unresolved
- Technical unavailable:
  - attempt retry/resolve flow
  - if still unavailable, center on effective fallback origin and re-show technical notice

### Transient Notice
- Appears when the map exits loading into a terminal state with a newly resolved authoritative outcome.
- Does not appear immediately on permission-screen acceptance before the map has resolved the effective origin.
- Dismisses when:
  - `10s` elapse,
  - user taps dismiss,
  - user meaningfully pans the map,
  - user clicks/taps outside according to the final component behavior.
- If the user explicitly taps `Você` while in alert/error states, the notice may be re-triggered.

### Retry/Reconciliation
- The feedback model must re-derive from the final resolved origin state rather than latching the first fallback result forever.
- Post-permission entry must not remain stuck in `unavailable` if live location resolves shortly afterwards.

---

## Tasks
- [ ] ⚪ Promote this location-origin feedback model as the authoritative map UX for the `Você` action.
- [ ] ⚪ Introduce a controller-level UI projection derived from `LocationResolutionPhase` + `LocationOriginSettings`.
- [ ] ⚪ Replace the current sticky soft-location notice behavior with transition-based transient notice behavior.
- [ ] ⚪ Implement persistent badge semantics for loading, live, fixed/manual, outside-range, permission-denied, and technical-unavailable states.
- [ ] ⚪ Implement enable/disable rules for the `Você` action based on actionable-origin availability, not permission alone.
- [ ] ⚪ Implement tap behavior so the `Você` action recenters to the effective origin for the active state.
- [ ] ⚪ Ensure permission-denied and technical-unavailable states have distinct copy.
- [ ] ⚪ Ensure `userFixedLocation` is never rendered as an error and remains actionable.
- [ ] ⚪ Ensure post-permission map entry reconciles to the final resolved state instead of latching early fallback feedback.
- [ ] ⚪ Add targeted controller/widget/manual validation for the race-condition entry flow and all terminal origin states.
- [ ] ⚪ Promote the finalized contract into `map_poi_module.md` and `modulo_mapa_e_mobilidade.md` after implementation approval.

---

## Acceptance Criteria
- [ ] ⚪ The map no longer emits a false sticky `unavailable` notice immediately after permission grant when live location subsequently resolves.
- [ ] ⚪ The `Você` action reflects the authoritative effective origin state via persistent badge semantics.
- [ ] ⚪ The `Você` action is disabled only when there is no actionable origin and resolution is still pending.
- [ ] ⚪ `userFixedLocation` is visually distinct from error and remains actionable.
- [ ] ⚪ Permission-denied and technical-unavailable states use different messages.
- [ ] ⚪ Alert/error states can re-trigger the transient notice when the user taps the `Você` action.
- [ ] ⚪ The transient notice auto-dismisses after `10s` and also dismisses on meaningful map interaction.
- [ ] ⚪ Centering behavior follows the effective resolved origin rather than assuming live location in all cases.

---

## Definition of Done
- [ ] ⚪ All tasks and acceptance criteria are checked with evidence.
- [ ] ⚪ Decision adherence is recorded against `D-01..D-20`.
- [ ] ⚪ `fvm dart analyze --format machine` is clean for the touched Flutter result.
- [ ] ⚪ Targeted controller/widget/manual validation covers post-permission race entry and all location-origin terminal states.
- [ ] ⚪ Canonical docs are updated after implementation approval.

---

## Validation Steps
- [ ] ⚪ Manual smoke: deny permission, enter map, tap `Você`, confirm permission-specific notice.
- [ ] ⚪ Manual smoke: grant permission from the permission screen, enter map, confirm no false sticky `unavailable` notice if live location resolves.
- [ ] ⚪ Manual smoke: simulate/force outside-range live location and confirm alert badge + fallback notice.
- [ ] ⚪ Manual smoke: configure user fixed location, enter map, confirm `home` badge and actionable centering behavior.
- [ ] ⚪ Manual smoke: technical unavailable path uses fallback notice copy distinct from permission-denied copy.
- [ ] ⚪ Manual smoke: transient notice dismisses after `10s`, on dismiss action, and on meaningful map interaction.
- [ ] ⚪ Manual smoke: repeated tap on `Você` in alert/error states re-triggers the appropriate transient notice.

---

## Execution Outcome Snapshot (`2026-04-05`)

- `MapScreenController` now projects location-origin UI state from `LocationResolutionPhase + LocationOriginSettings` instead of latching early fallback copy.
- The map-local `Você` action now owns a persistent badge state for `loading`, `live`, `fixed/manual`, `outside range`, `permission denied`, and `technical unavailable`.
- Transition-driven transient notices replaced the previous sticky notice behavior; notices now auto-dismiss after `10s` and can be dismissed by map interaction.
- `userFixedLocation` now keeps the action enabled with a `home` semantic and no longer degrades to a generic error presentation while live resolution happens in parallel.
- Reconciliation reload ownership is now centralized in one path, preventing duplicate `Atualizando pontos...` churn when `resolveUserLocation()` completion overlaps with phase-listener reconciliation.
- Automated controller coverage now includes:
  - bursts of nearby live updates in the same semantic origin state,
  - overlapping `resolveUserLocation()` completion vs listener-driven reconciliation,
  - assertions on loading/message/fetching churn rather than fetch count alone.
- Local web runtime validation on `https://guarappari.belluga.space/mapa` with geolocation forced inside tenant range remained stable before and after a manual burst of nearby geolocation updates; no visible repeating `Atualizando...` loop was observed.
- Manual/browser validation already confirmed the approved `outside range` semantics on web: permission granted outside the supported area resolves to the alert state instead of a false sticky `unavailable` error.
- Controller and widget coverage was added for:
  - unresolved/loading entry,
  - reconciliation from early fallback to live resolution,
  - fixed/manual badge state,
  - `FabMenu` enablement/disablement semantics.
