# TODO: Flutter Performance Smell Cleanup (Autonomous)

## Goal
Eliminate all flagged Flutter performance smells (mounted checks, async navigation, build side‑effects, layout hotspots, list performance, image/media) while keeping architecture adherence intact.

## Scope
- Flutter app only (`flutter-app/`).
- Performance smells identified by the Flutter performance smell skills.
- No backend changes unless explicitly required by documented contracts.

## Out of Scope
- New features or UI redesigns beyond required refactors.
- Behavior changes not needed for performance or architecture compliance.

## Definition of Done
- All smell categories are clean (no remaining flags).
- No FutureBuilder/StreamBuilder introduced.
- No controller/navigation/DI rule regressions.
- `fvm flutter analyze` is clean.
- Device integration tests run per `flutter-device-test-runner` and are green **or** explicitly marked as permission‑blocked (see Validation Steps).

## Rules / Guardrails
- Controllers own async work and state (StreamValue only).
- Widgets never navigate after await; navigation must be sync and UI‑owned.
- UI must not create/own StreamValue, repositories, or controllers.
- Keep ephemeral UI state only for tiny, local, UI‑only widgets.
- No fallbacks when backend is down (if encountered, note and stop).

## Smell Categories to Clean
### 1) Mounted checks / async gaps
- Remove `mounted`/`context.mounted` checks by moving async work to controllers.
- Convert navigation-after-await into controller decision + sync UI navigation.
- Audit all occurrences from `rg -n "\bmounted\b|context\.mounted" flutter-app/lib/presentation`.

### 2) Async navigation in UI
- Remove `await context.router.push(...)` patterns in widgets/screens.
- UI should call controller for decisions, then sync navigate.
- Audit `rg -n "await .*context\.router|await .*router\.|await .*Navigator" flutter-app/lib/presentation`.

### 3) Build side‑effects
- No repo/network/telemetry in `build`/`didChangeDependencies`.
- Move into controller init or guarded stream updates.
- Manual scan of screens with heavy `build` logic.

### 4) Layout hotspots
- Reduce `LayoutBuilder` usage in hot paths when not required.
- Extract heavy subtrees, add `RepaintBoundary` when needed.
- Audit `rg -n "IntrinsicHeight|IntrinsicWidth|LayoutBuilder" flutter-app/lib/presentation`.

### 5) List performance
- Replace `ListView(children: ...)` with builders when list can grow.
- Ensure pagination is controller‑driven, keys used where needed.
- Audit `rg -n "ListView\(|GridView\(" flutter-app/lib/presentation`.

### 6) Image/media
- Ensure caching/placeholder/error handling for network images.
- Avoid re‑decoding or re‑building images; memoize where needed.
- Audit `rg -n "NetworkImage|ImageProvider" flutter-app/lib/presentation`.

## Execution Plan
1) Re‑run smell scans and build a concrete checklist of files.
2) Fix mounted/async navigation smells first (highest risk).
3) Address build side‑effects, then layout/list, then image/media.
4) After each batch: `fvm flutter analyze`.
5) Run device integration tests (single‑file) until green.

## Progress Log
- ✅ Mounted checks + async navigation: removed widget-level `_isDisposed` guards and async navigation-after-await patterns; navigation now sync and controller-driven.
- ✅ Widget stream subscriptions: removed `.stream.listen` in widgets/screens; using `StreamValueBuilder` + controller-owned intents only.
- ✅ Event detail + map deck refactors: accept-invite flow now controller-driven; POI deck uses StreamValueBuilder without subscriptions.
- ✅ Layout hotspots: `LayoutBuilder` usages reviewed; no intrinsic layouts. Kept only where layout sizing required.
- ✅ List performance: `ListView` usages reviewed; all are small, bounded UI sections (no large dynamic lists).
- ✅ Image/media: introduced `BellugaNetworkImage` with default placeholder/error handling and replaced most `Image.network` usages; remaining direct `Image.network` in account profile edit now include loading placeholders + error handling for controller error state updates.
- 🟡 Device tests (guarappari): running through `.agent/test-run-progress.md` checklist; permission grants remain unavailable until app install per run.

## Validation Steps
- `fvm flutter analyze` clean.
- Device integration tests: run per `.agent/test-run-progress.md` checklist.
- If a test cannot proceed due to permission prompts (no grants), do **not** change behavior based on that failure; mark the TODO entry as `🟡 Provisional` with a `?` and note which permission blocked it so it can be rerun when grants are available.
- Confirm no new architecture violations (adherence rules).
