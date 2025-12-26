# TODO (MVP): Simplify Screen Tree

**Status:** Draft
**Owner:** Delphi + Flutter Team
**Source:** User request (Home adjust)

---

## Scope
- [x] Promote the Provisional Home as the official Home (remove deprecated Home screen).
- [x] Keep the Provisional Home layout: `NestedScrollView`, main sticky Home AppBar, Favorites strip, “Meus Eventos” carousel, sticky Agenda header, and Agenda list.
- [x] Ensure “Meus Eventos” shows confirmed events that are live now or upcoming; hide the carousel when empty.
- [x] Keep Agenda list behavior identical to the Agenda screen and show the Agenda empty widget when empty.
- [x] Remove action buttons from the main Home AppBar.
- [x] Use a shared Agenda AppBar widget between Home and Agenda with per-screen action configuration.
- [x] Keep architecture rules: screen controllers only at screen level; complex widget controllers are widget-scoped and factory-registered; widgets resolve their own controllers via GetIt.
- [x] Favorites section uses its widget-scoped controller and is not tied to screen controllers.
- [x] Home Agenda helpers live under `widgets/agenda_section/` with their own controller under `widgets/agenda_section/controllers/`.
- [x] Remove `HomeAgendaScope`; Agenda widgets resolve controller via GetIt.
- [x] Remove `onEventTap` from the My Events carousel; cards handle their own navigation.
- [x] Remove Agenda from bottom navigation; Agenda screen uses back navigation. If it is the root, back goes to Profile.
- [x] System back button always routes to Home; if already on Home, scroll to top; if already at top, show exit confirmation dialog.
- [x] Update bottom navigation to three tabs (Home/Mapa/Menu) and fix indices across tabs.
- [x] Reorganize widgets/assets tied to the deprecated Home screen (e.g., favorites widgets) to the Home official context.
- [x] Update Home/Agenda docs to match the official Home layout.
- [x] Update/add tests impacted by these changes.
- [x] Remove the legacy `TenantHomeController` and move its responsibilities into the Provisional Home flow (single controller for Home).
- [x] Remove unused `TenantTabsRoute` and related tab scaffolding since it is not referenced by routes.
- [x] Add integration test that navigates Home → Map → Home → Menu → Agenda and validates back navigation flow without controller disposal errors.
- [x] Gate Sentry init via dart-define for integration tests.
- [x] Stabilize integration test assertions (wait for Home UI before tapping).
- [x] Close invite swipe overlay when present before Home assertions.
- [ ] Stub network images in integration test to avoid host lookup failures.
- [x] Rename provisional Home to official names (TenantHomeScreen/TenantHomeController + folder path).
- [x] Move Home ScrollController into TenantHomeController and expose via getter.
- [x] Replace WillPopScope with PopScope on Home.
- [x] Replace WillPopScope with PopScope in Menu, Agenda, and Map prototype screens.
- [x] Update PopScope callbacks to use onPopInvokedWithResult.
- [x] Make HomeAgendaAppBar resolve its controller via GetIt (remove manual wiring).
- [x] Add AgendaAppBar controller parameter to centralize wiring and reduce call-site noise.
- [x] Bring back the pending invites widget under the Favorites strip on Home.

## Out of Scope
- New backend contracts or repository changes.
- Visual redesign of event cards or Agenda list items beyond reuse.
- Changes to Invite logic or event filtering beyond the “confirmed + live/upcoming” rule.
- Additional “Simplify Screen Tree” steps beyond the provisional Home in this task.

## Decisions Needed / Questions to Close
1. [x] Keep the provisional naming for now; rename only after the initiative is complete.
2. [x] Confirm removal of `TenantHomeController` and keep a single Home controller in the provisional flow.

## Definition of Done
- Home uses `NestedScrollView` with two sticky headers.
- Favorites strip appears beneath the main Home AppBar.
- “Meus Eventos” shows only confirmed events that are live now or upcoming and hides when empty.
- Agenda list renders identically to the Agenda screen content and shows its empty widget when empty.
- Provisional Home becomes the official Home; deprecated Home screen removed.
- Home route points to the official Home implementation.
- Agenda is not part of bottom navigation and uses a back button; root back goes to Profile.
- System back always routes to Home; Home back scrolls to top; already at top shows exit dialog.
- Bottom navigation is limited to Home/Mapa/Menu with correct indices.
- Favorites widgets are located under the official Home context, not the deprecated screen folder.
- Widget test covers My Events card tap → detail navigation.
- Documentation updated (`foundation_documentation/screens/home.md` and `foundation_documentation/screens/modulo_agenda.md`).
- Analyzer clean (`fvm flutter analyze`).

## Validation Steps
- Manual: verify scroll behavior (two sticky headers), layout order, and section visibility.
- Manual: verify confirmed events filter for “Meus Eventos.”
- Manual: compare Agenda list rendering to Agenda screen.
- Manual: verify back behavior (system back → Home, Home back → top → exit dialog).
- Tooling: `fvm flutter analyze`.
