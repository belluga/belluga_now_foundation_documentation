# TODO (V1): Push Handler Release Bump

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Delphi (Flutter/Plugin)  
**Objective:** Publish plugin changes with updated changelog, README, and version number.

---

## Scope
- Update `CHANGELOG.md` with the latest push presentation fixes and queue behavior.
- Update `README.md` to document presentation gate/mode and latest-only queue behavior.
- Bump plugin version in `pubspec.yaml` (and lockfile if required).

## Out of Scope
- Functional code changes (already delivered).
- Flutter app or backend changes.

## Definition of Done
- [x] ✅ Production‑Ready CHANGELOG entry added for the new version.
- [x] ✅ Production‑Ready README documents presentation gate/mode and latest-only queue semantics.
- [x] ✅ Production‑Ready Plugin version updated in `pubspec.yaml` (and `pubspec.lock` if needed).

## Validation Steps
- [x] ✅ Production‑Ready `fvm flutter analyze` passes for the plugin package.

## Decisions
- Keep plug'n'play default behavior; gate/mode is optional.
- Latest-only queue replaces older queued pushes by design.
