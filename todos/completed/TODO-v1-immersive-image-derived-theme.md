# TODO (V1): Immersive Image-Derived Theme

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active
**Current delivery stage:** `Production-Ready`
**Qualifiers:** `Validated`
**Next exact step:** No implementation work pending; keep this lane as the canonical baseline for immersive image-derived theming.
**Owners:** Flutter Team
**Objective:** Derive immersive detail theming from the hero image for both Event Detail and Account Profile Detail, using one shared mechanism with deterministic fallback and minimal blast radius.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Surface | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |

---

## References

- `lib/application/extensions/color_scheme_generator.dart`
- `lib/presentation/shared/widgets/image_palette_theme.dart`
- `lib/presentation/shared/widgets/immersive_detail_screen/immersive_detail_screen.dart`
- `lib/presentation/tenant_public/schedule/routes/immersive_event_detail_route.dart`
- `lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`
- `lib/presentation/tenant_public/partners/routes/partner_detail_route.dart`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-public-account-profile-detail-polish.md`

---

## Problem Statement

- The current immersive shell does **not** derive theme from hero imagery.
- Event Detail already passes through `ImagePaletteTheme`, but that widget currently just reuses the fallback/app theme instead of extracting palette.
- Account Profile Detail does not use the shared image-theme path at all.
- Result: immersive surfaces visually ignore the strongest identity signal they already have, which is the hero image.

---

## Scope

- Implement a real image-derived `ColorScheme` path for immersive detail surfaces.
- Use the existing `ColorSchemeGenerator` as the shared derivation mechanism.
- Apply the same shared mechanism to:
  - Event Detail
  - Account Profile Detail
- Keep deterministic fallback behavior when image is missing or extraction fails.
- Preserve current route contracts, tab behavior, CTA behavior, and data contracts.

## Out of Scope

- Reworking the whole Account Profile Detail hierarchy.
- New backend/API fields.
- Replacing the shared immersive shell.
- New route behavior or resolver changes.
- Non-immersive screens.

---

## Current Technical Truth

- `ImmersiveDetailScreen` consumes the active `Theme.of(context).colorScheme`; it does not derive palette internally.
- `ImagePaletteTheme` exists but currently just reapplies the fallback/app `ColorScheme`.
- `ColorSchemeGenerator.fromImageProvider(...)` already exists and is unused.
- Event Detail has a theme-injection extension point already.
- Account Profile Detail currently does not.

---

## Decision Baseline (Frozen)

- `D-01` (`Preserve`): image-derived theming must be implemented through one shared Flutter mechanism, not duplicated ad hoc per screen.
- `D-02` (`Preserve`): `ImmersiveDetailScreen` remains a consumer of resolved theme; it should not own image loading/palette extraction internally.
- `D-03` (`Preserve`): the existing `ColorSchemeGenerator` is the canonical derivation entrypoint unless a concrete blocker is found.
- `D-04` (`Preserve`): missing image, decode failure, or low-confidence extraction must fall back deterministically to the current app theme.
- `D-05` (`Preserve`): Event Detail and Account Profile Detail must converge on the same image-derived theme mechanism in the same lane.

---

## Plan Review Gate

### Issue Card `IIDT-P1` — Shared route/theme gap exists but the derivation helper is already present

- **Severity:** `high`
- **Evidence:** `lib/application/extensions/color_scheme_generator.dart`, `lib/presentation/shared/widgets/image_palette_theme.dart`
- **Why now:** the design target is already agreed, and the codebase has the extraction primitive but not the wiring.
- **Option A (recommended):** wire the existing generator into the shared image-theme wrapper and onboard both Event + Account Profile routes to it.
  Effort: `medium`
  Risk: `low-medium`
  Blast radius: `shared immersive route composition`
  Maintenance burden: `low`
- **Option B:** implement separate local theming logic in each route/screen.
  Effort: `medium`
  Risk: `medium-high`
  Blast radius: `duplicated cross-feature logic`
  Maintenance burden: `high`
- **Option C:** keep the current static theme behavior.
  Effort: `none`
  Risk: `high`
  Blast radius: `all immersive detail surfaces remain visually disconnected from hero imagery`
  Maintenance burden: `low`
- **Recommended:** `A`

### Issue Card `IIDT-P2` — Palette derivation is asynchronous

- **Severity:** `medium`
- **Evidence:** `ColorSchemeGenerator.fromImageProvider(...)` is async.
- **Why now:** route composition needs a safe async boundary that does not create build side effects or controller leakage.
- **Option A (recommended):** let the shared image-theme wrapper own a memoized async theme resolution with deterministic fallback.
  Effort: `medium`
  Risk: `low-medium`
  Blast radius: `shared wrapper only`
  Maintenance burden: `medium`
- **Option B:** push palette extraction into screen controllers.
  Effort: `medium-high`
  Risk: `medium`
  Blast radius: `feature controllers + shared contract`
  Maintenance burden: `medium-high`
- **Option C:** block image-derived theme until a synchronous extractor exists.
  Effort: `none`
  Risk: `high`
  Blast radius: `goal remains unimplemented`
  Maintenance burden: `low`
- **Recommended:** `A`

---

## Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-immersive-image-derived-theme.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/screens/modulo_agenda.md`
- `foundation_documentation/screens/PartnerLandingPage.md`
- `lib/application/extensions/color_scheme_generator.dart` only if hardening is required
- `lib/presentation/shared/widgets/image_palette_theme.dart`
- `lib/presentation/tenant_public/schedule/routes/immersive_event_detail_route.dart`
- `lib/presentation/tenant_public/partners/routes/partner_detail_route.dart`
- `lib/presentation/tenant_public/partners/account_profile_detail_screen.dart` only if route/screen composition needs a theme handoff
- targeted Flutter tests for shared wrapper and route composition
- `test/presentation/shared/widgets/image_palette_theme_test.dart`
- `test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart`
- `test/presentation/tenant_public/partners/routes/partner_detail_route_test.dart`

---

## Execution Plan

1. Add fail-first Flutter coverage around shared image-theme resolution/fallback and route onboarding.
2. Convert the shared image-theme wrapper into a real palette-resolving adapter using `ColorSchemeGenerator`.
3. Keep Event Detail on the shared wrapper, but make the wrapper actually resolve image-derived schemes.
4. Onboard Account Profile Detail to the same wrapper when a cover image exists.
5. Verify fallback behavior for missing image / extraction failure.
6. Run analyzer + targeted tests and fill decision-adherence evidence.

### Test Strategy

- `test-first`

### Fail-First Targets

- Event Detail with hero image no longer uses the unchanged fallback scheme path.
- Account Profile Detail with cover image uses the same shared image-theme wrapper path as Event Detail.
- Missing image still renders without crashing and preserves fallback theme.

---

## Validation Steps

1. Flutter targeted tests for shared image-theme wrapper behavior
2. Flutter targeted tests for Event + Account Profile route composition
3. `fvm dart analyze --format machine`
4. Manual smoke on immersive Event + Account Profile routes with and without images

---

## Route Contract Audit

- `app_router.gr.dart` audit result: no new required non-URL args were introduced for the touched routes.
- `ImmersiveEventDetailRoute`: required arg remains only `eventSlug`.
- `PartnerDetailRoute`: required arg remains only `slug`.

---

## Validation Evidence

- `passed`: `fvm flutter test test/presentation/shared/widgets/image_palette_theme_test.dart test/presentation/tenant_public/partners/routes/partner_detail_route_test.dart test/presentation/tenant_public/schedule/routes/immersive_event_detail_route_test.dart`
- `passed`: `fvm flutter test test/application/extensions/color_scheme_generator_test.dart test/presentation/shared/widgets/image_palette_theme_test.dart`
- `passed`: `fvm flutter test --platform chrome test/application/extensions/color_scheme_generator_test.dart test/presentation/shared/widgets/image_palette_theme_test.dart`
- `passed`: `fvm dart analyze --format machine`
- `passed`: manual smoke on immersive Event + Account Profile routes with and without hero imagery confirmed both derived-theme and deterministic fallback paths

---

## Bug-Fix Evidence Loop Addendum

### Reproduction Record (`before`)

- Real UI symptom: immersive Event + Account Profile surfaces still looked like the base dark theme even after onboarding `ImagePaletteTheme`.
- Automated evidence gap found: earlier tests only proved `Theme.of(context).colorScheme` mutation through an injected resolver; they did **not** prove that a real `Scaffold`/Material subtree picked up the derived theme.

### Mandatory Question Gate

1. **Did we already have tests that cover this behavior across all stages up to UI display?**
   - No. Coverage existed only for route onboarding and injected resolver state, not for real theme application on widget surfaces.
2. **Did we inspect current real database/backend payloads to verify compatibility with current parsing and rendering assumptions?**
   - Yes. The model/route path already exposed `coverUri`/`thumb` correctly; the failure was downstream in theming application, not payload shape.
3. **If existing tests should cover this bug, which exact test(s) failed? If none failed, why were they insufficient?**
   - None failed before the new assertions. They were insufficient because they never asserted `scaffoldBackgroundColor`/actual themed subtree behavior.
4. **If tests do not cover the failure, which new tests must be created before implementing the fix?**
   - A real generator test using concrete image bytes.
   - A widget test asserting that `ImagePaletteTheme` updates `scaffoldBackgroundColor` for a descendant `Scaffold`.
5. **Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? Why or why not?**
   - `no-rule-needed`. The defect is runtime theme propagation detail inside a valid shared widget pattern; it is not a statically recognizable architecture violation with acceptable false-positive risk.

### Coverage Matrix

| Stage | Status | Notes |
| --- | --- | --- |
| `API / payload` | `covered` | `coverUri` / `thumb` reach the route correctly. |
| `DTO / repository / domain` | `covered` | Existing account profile + event route tests prove media-aware route composition. |
| `Shared theme wrapper` | `false-green -> covered` | Old tests only checked `colorScheme`; new test checks `Scaffold` background propagation. |
| `UI display surface` | `covered` | `ImagePaletteTheme` now proves real themed subtree behavior through descendant `Scaffold`. |

### Root Cause

- `ImagePaletteTheme` originally used `Theme.of(context).copyWith(colorScheme: scheme)`, which changed the raw `ColorScheme` but preserved derived `ThemeData` fields like `scaffoldBackgroundColor`.
- Result: immersive surfaces could still render with the base dark scaffold/background theme even when a derived palette existed.

### Architecture Prevention Assessment

- `no-rule-needed`

---

## Decision Adherence Validation

| Decision | Status | Evidence |
| --- | --- | --- |
| `D-01` | `Adherent` | `ImagePaletteTheme` now owns the shared async derivation path used by both immersive routes. |
| `D-02` | `Adherent` | `ImmersiveDetailScreen` remains unchanged as theme consumer; extraction stays outside the shell. |
| `D-03` | `Adherent` | `ImagePaletteTheme` defaults to `ColorSchemeGenerator.fromImageProvider(...)`. |
| `D-04` | `Adherent` | Wrapper falls back to ambient/app theme on missing image or resolver failure; covered by `image_palette_theme_test.dart`. |
| `D-05` | `Adherent` | `/agenda/evento/:slug` and `/parceiro/:slug` both route through `ImagePaletteTheme` when media exists; covered by route tests. |

---

## Module Decision Consistency Validation

| Canonical Source | Status | Evidence |
| --- | --- | --- |
| `flutter_client_experience_module.md` tenant-public route ownership for `/agenda/evento/:slug` and `/parceiro/:slug` | `Preserved` | No route ownership or required-arg contract changed; only shared theming composition changed. |
| `flutter_client_experience_module.md` route-driven hydration contract | `Preserved` | Hydration remains route/resolver-owned; the new wrapper changes only presentation theming after resolution. |

---

## Delivery Notes

- Shared wrapper bug fixed: the themed subtree now receives the inner `BuildContext`, so `Theme.of(context).colorScheme` reflects the derived palette instead of the parent theme.
- Shared wrapper bug fixed: the themed `ThemeData` now also updates derived fields like `scaffoldBackgroundColor`, so immersive surfaces no longer keep the base dark scaffold when a palette is resolved.
- Event Detail keeps its existing `ImagePaletteTheme` onboarding, now backed by real palette extraction.
- Account Profile Detail now uses the same shared wrapper when `coverUri` exists; missing cover keeps the plain route/screen path.
