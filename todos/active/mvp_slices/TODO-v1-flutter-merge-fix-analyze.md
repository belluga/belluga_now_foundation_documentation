# TODO (V1): Flutter Merge Fix — Analyzer Errors (Account Events Wireup)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Resolve post-merge analyzer errors in Flutter while preserving the account-events wireup changes.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-events-and-agenda-frontend.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`

---

## A) Scope
- Fix compile/analyzer errors introduced by the recent merge on `account-events-wireup`.
- Keep behavior unchanged unless required to satisfy contract updates.
- Align fakes/mocks/tests with updated contracts.

---

## B) Tasks

### B1) AppData + DTO fixes
- [x] ✅ Production-Ready Fix factory access errors in `lib/domain/app_data/app_data.dart` (remove instance member use inside factory; restore/define `resolvedHostname` and `resolvedHref`).
- [x] ✅ Production-Ready Initialize `profileTypes` in `lib/infrastructure/dal/dto/app_data_dto.dart` and ensure constructor/`fromJson` match the field list.

### B2) Partners repository alignment
- [x] ✅ Production-Ready Add missing import for `debugPrint` and resolve `AppData` type usage in `lib/infrastructure/repositories/partners_repository.dart`.
- [x] ✅ Production-Ready Remove unused private helper if still dead after fixes.

### B3) Invite flow screen error
- [x] ✅ Production-Ready Fix missing `_showOfflineAcceptToast` in `lib/presentation/tenant/invites/screens/invite_flow_screen/invite_flow_screen.dart` (restore helper or remove call with equivalent UX).

### B4) Schedule backend/repository contract drift (tests + fakes)
- [x] ✅ Production-Ready Update schedule backend test fakes to implement `fetchEventDetail` and `watchEventsStream`.
- [x] ✅ Production-Ready Update `fetchEventsPage` overrides to include new filter arguments (`categories`, `tags`, `taxonomy`, `origin_lat`, `origin_lng`, `max_distance_meters`, `confirmed_only`).
- [x] ✅ Production-Ready Update schedule repository fakes to implement `watchEventsStream` and updated `getEventsByDate/getEventsPage` signatures.

### B5) Cleanup
- [x] ✅ Production-Ready Remove unused import in `lib/application/router/modular_app/module_settings.dart`.

---

## C) Out of Scope
- New features or UX changes not required to satisfy the updated contracts.
- Backend API changes.

---

## D) Definition of Done
- [x] ✅ Production-Ready `fvm flutter analyze` is clean.
- [x] ✅ Production-Ready All updated fakes/mocks compile with the new contract signatures.
- [x] ✅ Production-Ready No runtime behavior changes beyond the fixes above.

---

## E) Validation Steps
- [x] ✅ Production-Ready `fvm flutter analyze`
- [ ] ⚪ Targeted unit tests for updated fakes (if they exist in affected test files).
