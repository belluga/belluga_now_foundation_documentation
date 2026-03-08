# TODO — Flutter Landlord Admin: total visual adherence to reference admin repo
**Version:** 1.1
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Active
**Owners:** Flutter Team
**Objective:** Apply the reference admin visual strategy on landlord/tenant-admin surfaces, preserving contracts/flows and keeping one remaining navigation-integrity validation gate.

## scope
- Apply the visual strategy from `faisal-kabir/flutter-admin-panel` to the landlord admin surfaces, preserving all current contracts/fields/flows.
- Restrict theme/scaffold changes to landlord scope only (Tenant Admin), without changing global app theme.
- Replace the current card-heavy shell composition with the reference-style layout hierarchy:
  - flat top header container (not card)
  - flat content workspace wrapper
  - flat navigation surfaces (desktop rail container / mobile nav container)
  - section surfaces as single-layer containers (no nested card shells)
- Update the shared local package `belluga_admin_ui` widgets to match reference visual language (surface containers, action buttons, typography/spacing rhythm) and keep one widget per file.
- Apply the same visual foundation across settings hub and the most visible tenant-admin content wrappers (list/form scaffolds) so screens look coherent under one admin system.
- Keep Material 3 widget usage and behavior; only adjust scoped tokens/composition/styling.
- Enforce clear settings hierarchy:
  - Hub is summary/navigation surface only (no edit-like controls mixed with section navigation).
  - Remove section-level pill CTAs (`Configurar`) from settings hub.
  - Use tappable section surfaces/rows for navigation affordance.
  - Ensure detail screens preserve parent context (`Configurações`) while showing current subsection.
- Add stable Web automation anchors for critical landlord/tenant-admin flows (without changing business behavior):
  - login fields/actions
  - tenant scope switch action
  - settings visual identity entrypoint
  - branding editor color/save actions

## out_of_scope
- No backend/API/contract/schema changes.
- No route semantics changes or navigation flow redesign.
- No changes outside landlord/tenant-admin scope.
- No feature additions in settings forms; only visual/system adherence.
- No API payload or controller contract changes for settings forms.
- No auth/business logic changes for login flow; only testability hooks.

## definition_of_done
- Tenant Admin shell no longer renders header/workspace/navigation as stacked `Card` containers.
- Settings hub adopts reference hierarchy: flat page background + single-level section surfaces + restrained accent usage.
- `belluga_admin_ui` widgets used by settings are updated to reference style and consumed by tenant-admin settings screens.
- Tenant Admin visual tokens (background/surface/accent/radius/spacing/text emphasis) are reference-aligned and applied only inside scoped landlord theme.
- Existing fields/contracts/routes remain unchanged and functional.
- No architecture violations introduced (screen/controller boundaries preserved).
- Settings hierarchy is unambiguous:
  - Hub cards do not look like inline forms.
  - No duplicated action hierarchy (card-level and row-level for the same intent).
  - Detail screens communicate parent-child context.
- Browser automation can target critical flows deterministically using semantics identifiers (no coordinate-only fallback).

## execution_checklist
- [x] ✅ Production‑Ready Define scoped visual tokens in tenant-admin theme based on reference repo hierarchy (bg/surface/outline/accent/spacing/radius).
- [x] ✅ Production‑Ready Refactor tenant-admin shell composition to remove card stacking and align header/workspace/nav structure with reference layout.
- [x] ✅ Production‑Ready Refactor `belluga_admin_ui` settings primitives (section shell/action/color row/integration row) to reference visual behavior.
- [x] ✅ Production‑Ready Apply updated package widgets to tenant-admin settings hub and remove residual local visual divergences.
- [x] ✅ Production‑Ready Harmonize shared tenant-admin content wrappers (list/form page containers) to the same visual system where needed.
- [x] ✅ Production‑Ready Normalize typography hierarchy to reference cadence (remove duplicated page H1 on settings hub, rebalance scoped theme weight overrides, and align section/CTA/list text sizes between flutter-app and `belluga_admin_ui` package).
- [x] ✅ Production‑Ready Align Settings typography hierarchy with tenant-admin baseline screens (Accounts/Assets): normalize section titles to `titleMedium`, reduce oversized CTA/row text, and keep visual rhythm consistent across modules.
- [x] ✅ Production‑Ready Remove residual nested surfaces inside Settings hub sections (especially visual identity color rows) so each section keeps a single container layer and inner content avoids card-like wrappers.
- [x] ✅ Production‑Ready Remove duplicated route headlines from tenant-admin root screens where shell header already provides the page title (e.g., Events and Static Assets list screens).
- [x] ✅ Production‑Ready Remove remaining nested-surface effect by flattening shell workspace wrapper and telemetry integration rows (no inner bordered tiles inside section surfaces).
- [x] ✅ Production‑Ready Standardize Accounts/Assets list headers and controls (filters, search toggle/field, and manage-types action) under one shared pattern aligned with the reference repo visual strategy.
- [x] ✅ Production‑Ready Refine Accounts/Assets list cards to a common reference-aligned rhythm (metadata hierarchy, chip treatment, spacing, and actions) without changing routes/contracts.
- [ ] 🟡 Provisional Validate navigation and UI integrity (accounts/assets FAB + card/detail entrypoints) after layout refactor.
- [x] ✅ Production‑Ready Refactor Settings hub cards to summary mode only (replace edit-like segmented/slider/field previews with compact read-only summaries).
- [x] ✅ Production‑Ready Remove section-level `Configurar` CTAs from settings hub and use card/row tap affordances aligned with reference hierarchy.
- [x] ✅ Production‑Ready Eliminate duplicated settings actions in technical integrations (single interaction level per entrypoint).
- [x] ✅ Production‑Ready Add explicit settings parent-child context in shell header/title resolution for settings subroutes.
- [x] ✅ Production‑Ready Rebalance detail-screen section headers/content so subsection identity is clear without losing `Configurações` context.
- [x] ✅ Production‑Ready Run focused tests/analyze for settings shell hierarchy and update checklist status to Production‑Ready.
- [x] ✅ Production‑Ready Add shell breadcrumb + mobile back affordance for non-root in-shell routes (settings subpages and admin sublists) to improve navigation context and return path.
- [x] ✅ Production‑Ready Replace shell global header in settings subroutes with scoped section app bar (section title + back), keeping shell header only on hub/list root pages.
- [x] ✅ Production‑Ready Replace branding hex text inputs with proper color picker fields (M3 interaction) while preserving existing `#RRGGBB` contract values in controller/repository payloads.
- [x] ✅ Production‑Ready Derive `TenantAdminScopeTheme` primary/secondary accents from Environment `theme_data_settings` (same source-of-truth strategy used by tenant theme), keeping landlord-only scoped surfaces/typography.
- [x] ✅ Production‑Ready Add Flutter Web semantics identifiers on critical auth/settings widgets (login fields/button/admin CTA, tenant switch, visual identity hub card, branding color/save controls).
- [x] ✅ Production‑Ready Add/adjust browser navigation validation to use semantics identifiers for critical settings flow (enable semantics + deterministic selectors).
- [x] ✅ Production‑Ready Run targeted validation (Flutter analyze/tests + Playwright against `belluga.space`) and record results.

### Provisional Notes
- Accounts and shell navigation integrity are covered by existing automated tests in this run.
- Static assets list behavior already has integration coverage for filtering/search (`integration_test/feature_admin_static_assets_test.dart`), but it still lacks explicit list-route entrypoint assertions (FAB + card tap navigation).
- Upgrade to Production‑Ready by adding a widget test for static-assets list interactions (FAB + card tap -> expected route push).

## validation_steps
- `fvm flutter analyze`
- `fvm flutter test test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart`
- `fvm flutter test test/presentation/tenant_admin/accounts/tenant_admin_accounts_list_screen_test.dart`
- `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`
- `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_hierarchy_test.dart` (if missing, create focused coverage)
- Manual landlord admin visual check:
  - Header/workspace/nav are not card-stacked.
  - Settings sections follow single-layer container hierarchy.
  - Accent and spacing are consistent with reference admin visual language.
- Browser validation (real URL):
  - `docker run ... mcr.microsoft.com/playwright:v1.58.2-jammy ...` against `https://belluga.space`
  - Login via UI fields/actions (no direct auth request injection).
  - Navigate to settings + visual identity, mutate branding color, save, and verify reflected value on hub.

### validation_run_notes
- `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` ✅
- `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart` ✅
- `timeout 420s fvm flutter analyze` ✅
- Focused settings hierarchy assertions were folded into `tenant_admin_settings_screen_test.dart` (no separate hierarchy test file required in this pass).
- Safe clean executed before tests: moved `build/test_cache` to `foundation_documentation/artifacts/tmp/manual-test-cache/`.
- `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/widgets/tenant_admin_shell_header_test.dart` ✅
- Rerun for scoped settings app-bar pass:
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` ✅ (scoped section app bar/back keys covered across subroutes; technical integrations assert uses `skipOffstage: false` because initial autofocus scroll can virtualize top widgets)
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/widgets/tenant_admin_shell_header_test.dart` ✅
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart` ✅
  - `timeout 420s fvm flutter analyze` ✅
- Color-picker + environment-theme pass:
  - `fvm flutter pub get` ✅ (`flutter_colorpicker` promoted to direct dependency)
  - Safe clean executed before tests: moved `build/test_cache` to `foundation_documentation/artifacts/tmp/manual-test-cache/`.
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` ✅
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/widgets/tenant_admin_shell_header_test.dart` ✅
  - `timeout 420s fvm flutter test --reporter expanded test/presentation/tenant_admin/shell/tenant_admin_shell_screen_test.dart` ✅
  - `timeout 420s fvm flutter analyze` ✅
- Web semantics + persistence navigation pass:
  - `fvm dart analyze lib/presentation/tenant_admin/shared/widgets/tenant_admin_color_picker_field.dart lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_screen.dart` ✅
  - `./scripts/build_web.sh` ✅ (bundle refreshed into `web-app`; known wasm-dry-run warnings unchanged)
  - `docker run --rm --network host --user 1001:1001 -e HOME=/tmp -e npm_config_cache=/tmp/.npm -v /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app:/work -w /work mcr.microsoft.com/playwright:v1.58.2-jammy bash -lc \"npm ci --no-audit --no-fund >/tmp/npm-install.log 2>&1 || (cat /tmp/npm-install.log && exit 1); node tests/tmp_admin_semantics_flow.cjs\"` ✅
  - Result snapshot:
    - `Primary before: #1C3FDF`
    - `Primary mutated + persisted: #E53935`
    - `Primary reflected on hub: #E53935`
    - Tenant-scope network assertions:
      - `POST 200 https://guarappari.belluga.space/admin/api/v1/branding/update`
      - `GET 200 https://guarappari.belluga.space/api/v1/environment?_ts=...` (refresh after save)
  - Ownership hygiene note:
    - Browser validations in Docker must run with `--user 1001:1001` (or equivalent host UID:GID) to avoid root-owned files in `web-app/test-results` and `web-app/node_modules` that can break `./scripts/build_web.sh` rsync cleanup.

## decisions
- Reference source for visual strategy: `https://github.com/faisal-kabir/flutter-admin-panel` (layout rhythm, surface hierarchy, restrained accents, spacing/typography emphasis).
- Preserve Material 3 controls and current functional contracts while changing only scoped visual composition.
- Implement reusable visual primitives in `belluga_admin_ui` first, then consume in tenant-admin screens.
- For settings hierarchy, prioritize interaction semantics from the reference admin pattern over literal section composition from the mobile mock: summary hub + explicit detail context.
- Context note: `submodule_flutter-app_summary.md` commit hash diverges from current `flutter-app` HEAD; implementation/validation in this TODO is grounded on current local submodule state.
- For browser E2E stability on Flutter Web, `ValueKey` remains Flutter-test-only; we will expose critical selectors through `Semantics(identifier: ...)` hooks.
