# TODO (V1): Home Agenda Radius Persistence and Sheet Polish

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/tenant_home_composer_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
**Complexity:** small
**Checkpoint Policy:** consolidated review before approval

## 1. Context
Home Agenda already exposes a max-distance/radius affordance through the shared `AgendaAppBar` radius bottom sheet. The current implementation has two gaps:
- the bottom sheet copy/visual hierarchy is too generic compared with the approved product direction (`Ajuste de Distância Máxima` in Stitch),
- changing the Home radius does not persist as a user/device preference even though anonymous users already have persistent settings surfaces.

There is also an architectural sharp edge in the current runtime wiring: the app-level stored radius preference must not collapse the tenant-configured maximum slider bound. The Home controller must treat:
- tenant-configured max radius as the upper bound,
- persisted user/device radius as the current selected value.

## 2. Scope
In scope:
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/**`
- `lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart` only as needed to support the Home-specific bottom sheet presentation
- Home agenda controller/tests
- Home radius bottom-sheet widget tests
- module-doc sync for Home + Agenda

Out of scope:
- Event Search radius persistence
- Discovery persistence
- backend/API changes
- backend/DB-backed user proximity settings and manual "Minha localização" origin override (tracked in `foundation_documentation/todos/active/store_release_android/TODO-store-release-proximity-preferences-and-location-origin.md`)
- new account/profile settings endpoint work
- changing tenant-configured min/default/max radius contracts

## 3. Decision Baseline (Frozen)
- `D-01` The Home Agenda radius affordance remains a bottom sheet launched from the existing radius action; this slice is visual/copy refinement, not a route change.
- `D-02` The Home bottom sheet should follow the approved Stitch direction with high visual fidelity in structure: title, accent separator, large centered distance value, slider, explanatory info card, and explicit confirmation CTA.
- `D-03` Changing the radius from Home must persist as a user/device preference through the existing app settings runtime path, including anonymous users.
- `D-04` This persisted Home preference affects only Home Agenda in this slice. Event Search and other surfaces keep their current behavior until a separate decision aligns them.
- `D-05` The tenant-configured maximum radius remains the slider upper bound. Persisting a lower selected radius must not reduce the future selectable maximum.
- `D-06` The Home controller is responsible for persisting the selected radius through `AppDataRepositoryContract`; widgets do not write storage directly.
- `D-07` Home radius changes use draft-local slider updates and only persist when the user taps the explicit confirmation CTA. Closing the sheet without confirmation discards the draft.
- `D-08` The Home radius bottom sheet content must respect device safe areas, including the bottom CTA area on mobile browsers, and the modal height must be content-driven rather than an imposed fixed height.
- `D-09` If no Home radius preference is persisted yet, the initial selected radius must be seeded from the distance between the user location and the tenant default origin, clamped to the tenant-configured min/max bounds. If no usable user location exists yet, Home falls back to the tenant-configured default radius.

## 4. Plan
1. Sync docs for Home/Agenda radius ownership and Home-only persistence semantics.
2. Add RED coverage for:
   - Home radius changes persisting through the app-data repository,
   - persisted selection not collapsing tenant max slider bound,
   - Home radius bottom sheet copy rendering,
   - Home explicit confirmation semantics (`onChanged` updates draft only; CTA commits).
3. Refactor Home radius wiring to:
   - initialize from persisted preference when available,
   - keep configured max bound separate from selected value,
   - persist only when the explicit Home confirmation CTA is used.
4. Polish the Home radius bottom sheet copy/layout in the shared app-bar widget without regressing Event Search behavior.
5. Run targeted tests, analyzer, and web build.

## 5. Risks / Notes
- `AppDataRepositoryContract.maxRadiusMeters*` currently behaves like a persisted selected value even though the name suggests a bound. This slice must work with that legacy naming without breaking the configured upper bound semantics.
- Home-only persistence is an intentional product asymmetry for V1 and must stay explicit in docs/tests.
- V1 persistence is local/device-only. Backend/DB-backed proximity preferences plus a user-selectable "Minha localização" origin are deferred to `foundation_documentation/todos/active/store_release_android/TODO-store-release-proximity-preferences-and-location-origin.md`.

## 6. Delivery Outcome
- Home Agenda radius now restores from persisted local/device preference, including anonymous sessions.
- The persisted selected radius no longer collapses the tenant-configured maximum slider bound.
- When no radius preference exists yet, Home now seeds the initial selected radius from the user-to-tenant-center distance, clamped to the tenant-configured bounds, and persists that seeded value locally/device-side.
- The Home radius sheet now follows the approved Stitch direction with explicit confirmation CTA and draft-only slider interaction until confirmation.
- The Home radius sheet now respects bottom safe areas on mobile browsers/devices and keeps height content-driven instead of relying on an imposed fixed height.
- Widget/controller coverage now locks persistence semantics, explicit confirmation semantics, and bottom-safe-area behavior.

## 7. Rule / Workflow Sources Used
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
