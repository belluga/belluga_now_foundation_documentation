# TODO: push_handler Release 0.1.2

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Flutter)  
**Objective:** Publish a clean 0.1.2 release with version bump and updated changelog.

---

## Scope
- Update `push_handler` package version to `0.1.2`.
- Update `push_handler` changelog to reflect the release highlights and fixes.

## Out of Scope
- Any new features or refactors beyond version/changelog updates.
- App-side changes in `flutter-app`.

## Definition of Done
- [x] ✅ Production‑Ready `pubspec.yaml` in `push_handler` reflects version `0.1.2`.
- [x] ✅ Production‑Ready `CHANGELOG.md` includes a new entry for `0.1.2` with accurate release notes.
- [x] ✅ Production‑Ready No other files modified in `push_handler` for this release task.

## Validation Steps
- [x] ✅ Production‑Ready Manual diff review of `push_handler/pubspec.yaml` + `push_handler/CHANGELOG.md`.

## Decisions
- Changelog bullets for `0.1.2`:
  - Add `enableDebugLogs` to `PushTransportConfig` and persist it in secure storage.
  - Gate push debug logging across repository init/queue, background entrypoint/reporting, and action reporting.
  - Add `test` dev dependency for package tests.
