# Round 01 Adjudication And Resolution

Derived artifact. Non-authoritative.

## Adjudication

Round 01 merged as `needs_adjudication` because the lanes recommended different resolution paths. The findings were not substantively contradictory:

- Elegance identified duplicated rich-text sanitizer policy and build-time controller mutations in the event form.
- Performance/security identified unbounded raw Account Profile rich-text input before DOM parsing and stale flat taxonomy snapshot repair.
- Test quality identified web CI/harness drift, Android/ADB blockage, non-deterministic mutation shard evidence, and metadata-only mutation tagging.

All non-environment findings were accepted and treated as `needs_resolution`.

## Resolution Applied

- Introduced neutral `Belluga\RichText\SafeRichTextHtmlSanitizer` package and converted host/events sanitizers to wrappers.
- Added pre-sanitizer raw byte validation for Account Profile `bio` and `content`.
- Updated taxonomy snapshot repair so `taxonomy_terms_flat` is repaired even when display snapshots are already current.
- Moved event form default venue/type hydration out of widget build and into controller dependency loading.
- Restored and preserved the `web-app` Playwright harness across Flutter web builds.
- Replaced ad-hoc mutation grep evidence with `NAV_WEB_SHARD` and `navigation_mutation_shards.json`.
- Retagged the metadata-only mutation matrix test as `@metadata`.

## Verification

- Laravel affected suite: `302 passed (1765 assertions)`.
- Flutter analyzer: `fvm dart analyze --format machine`, exit `0`.
- Flutter affected suite: `705 passed`.
- Web build: `CLEAN_OUTPUT=1 scripts/build_web.sh ../web-app dev`, success; Playwright harness files preserved.
- Web readonly navigation: `9 passed (3.2m)`.
- Web mutation navigation, manifest-backed shards:
- `apd`: `3 passed (1.2m)`.
- `filters`: `4 passed (1.9m)`.
- `map-admin`: `1 passed (31.2s)`.
- `occurrences`: `2 passed (1.6m)`.
- `occurrence-fab`: `1 passed (2.8m)`.
- `admin-final`: `7 passed (4.9m)`.

## Environment Waiver

Android/ADB remains blocked by local environment:

- `adb devices -l`: no connected devices.
- `fvm flutter devices`: Linux desktop and Chrome only.
- `fvm flutter emulators`: no emulator sources.

This is not recorded as Android pass. For this audit target, web navigation evidence is accepted only for behavior without a specified Android/Web divergence.

