# TODO (MVP): Home Adjust

**Status:** Draft
**Owner:** Delphi + Flutter Team
**Source:** `foundation_documentation/todos/active/mvp_slices/TODO-mvp-ui-polish.md` (A) Home

---

## Scope
- Remove the Home search entry point from the Favorites strip ("Procurar" chip) to match MVP simplification.
- Remove the Home alert surface/banner (confirm which widget is the intended alert surface; likely the invites banner if used on Home).
- Clarify and align the "My Events" / "Meus eventos confirmados" action to open the first confirmed event directly (fallback to Agenda list when none).
- Update Home-related documentation to reflect the simplified surface.

## Out of Scope
- New routes, new backend contracts, or repository changes.
- Redesign of Agenda or Invite flows beyond the tap behavior.
- Visual restyling outside the targeted removals.

## Decisions Needed / Questions to Close
1. Which "alert surface" should be removed from Home? (If it is `InvitesBannerBuilder`, confirm where it is mounted today.)
2. Where is "My Events" currently surfaced for this change: Home, Menu ("Meus eventos confirmados"), or another entry point?
3. If there are no confirmed events, should the tap open the Agenda list filtered to confirmed events or show a neutral empty state?

## Definition of Done
- Home no longer shows a search entry point in Favorites.
- Home alert surface is removed (target confirmed).
- "My Events" tap opens the first confirmed event when available and falls back to the confirmed-only Agenda list when empty.
- `foundation_documentation/screens/home.md` and any related screen docs are updated before code changes.
- Analyzer remains clean (`fvm flutter analyze`).

## Validation Steps
- Manual: open Home, verify Favorites strip no longer shows search chip; verify no alert banner is shown.
- Manual: tap "My Events" entry and confirm navigation behavior for both empty and non-empty confirmed lists.
- Tooling: `fvm flutter analyze`.
