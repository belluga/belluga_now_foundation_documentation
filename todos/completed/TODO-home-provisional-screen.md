# TODO (MVP): Provisional Home Screen (Nested Scroll)

**Status:** Draft
**Owner:** Delphi + Flutter Team
**Source:** User request (Home adjust)

---

## Scope
- Create a provisional Home screen layout based on `NestedScrollView`.
- Preserve the existing Home AppBar (sticky) as the primary sliver header.
- Place the Favorites Strip directly below the main AppBar.
- Add a “My Events” carousel showing events with confirmed presence that are live now or upcoming.
- Add a second sticky header matching the current Agenda screen AppBar style.
- Under the second sticky header, render the Agenda event list exactly as in the Agenda screen.
- Make this provisional screen the initial Home entry point (replace current Home screen routing/usage).
- Update Home/Agenda screen documentation to reflect the provisional layout.

## Out of Scope
- New backend contracts or repository changes.
- Visual redesign of event cards or Agenda list items beyond reuse.
- Changes to Invite logic or event filtering beyond the “confirmed + live/upcoming” rule.

## Decisions Needed / Questions to Close
1. Which existing Agenda AppBar should be mirrored for the second sticky header? (Provide file path.)
2. Should the “My Events” carousel reuse the current “Acontecendo Agora” carousel card or a different card from Agenda?
3. How should empty states behave for the “My Events” carousel and the Agenda list (hide sections vs. show empty messages)?
4. Confirm whether the provisional Home replaces the existing Home screen file or should be a new screen routed as Home.

## Definition of Done
- Home uses `NestedScrollView` with two sticky headers.
- Favorites Strip appears directly beneath the main Home AppBar.
- “My Events” carousel shows only confirmed events that are live now or upcoming.
- Second sticky header matches Agenda AppBar (as specified).
- Agenda list renders identically to the Agenda screen content.
- Documentation updated (`foundation_documentation/screens/home.md` and any related Agenda doc).
- Analyzer clean (`fvm flutter analyze`).

## Validation Steps
- Manual: verify scroll behavior (two sticky headers), layout order, and section visibility.
- Manual: verify confirmed events filter for “My Events.”
- Manual: compare Agenda list rendering to Agenda screen.
- Tooling: `fvm flutter analyze`.
