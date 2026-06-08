# v0.2.0+8 Manual Validation Checklist

## Context
- **Package:** `v0.2.0+8`
- **Environment:** `https://guarappari.belluga.space`
- **Created at:** `2026-06-03`
- **Status legend:** `[ ]` pending, `[x]` validated, `[!]` issue found
- **Note:** map-filter items are pre-marked as validated based on user confirmation that all map-filter topics were already checked manually.

## Map Filters
- [x] `MAP-01` Admin settings exposes the map filters editor through the configuration menu.
  - Evidence/notes:
- [x] `MAP-02` Public map filter labels use the backend-saved label, not a stale/default/frontend label.
  - Evidence/notes:
- [x] `MAP-03` Public map filter colors/icons/logos use backend-saved visuals when no marker override exists.
  - Evidence/notes:
- [x] `MAP-04` Public map filter colors/icons/logos use marker override visuals when override exists.
  - Evidence/notes:
- [x] `MAP-05` Public map filtering works with and without override, including event type filters and baseline primary filters.
  - Evidence/notes:

## Directions And Reference Point
- [x] `DIR-01` In an account profile, open `Como Chegar` and confirm Google Maps, Waze, Uber, and 99 appear with brand assets.
  - Evidence/notes:
- [x] `DIR-02` In an event detail, open `Como Chegar` and confirm the same branded directions action set appears without duplicated Google Maps entry.
  - Evidence/notes:
- [x] `DIR-03` Tap `Usar como ponto de referência` in an account profile and confirm a modal opens before applying the reference point.
  - Evidence/notes:
- [x] `DIR-04` Confirm the reference-point modal says `Todas as distâncias serão calculadas a partir desse local:` and shows the reduced account profile card.
  - Evidence/notes:
- [x] `DIR-05` Confirm `Usar como Ponto de Referência` applies the reference point only after confirmation.
  - Evidence/notes:

## Immersive Hero, Actions, And Gates
- [x] `HERO-01` Open `parceiro/qa-discovery-tag-longa` and confirm the account hero height/fade reads well with cover image.
  - Evidence/notes:
- [x] `HERO-02` Open a public event detail and confirm event hero height/fade reads well with cover image.
  - Evidence/notes: 2026-06-04 Playwright mobile capture on `https://guarappari.belluga.space/agenda/evento/pw-event-share-boundary-store-release-5?occurrence=6a17bada93ba592fce055f5d` showed the title/time/location remaining readable over a busy cover and the fade dying into the page surface as expected.
- [x] `HERO-03` Confirm there is no extra divider/line between hero content and tabs.
  - Evidence/notes: 2026-06-04 Playwright mobile capture on the same event route showed no stray divider between hero content and the tab row.
- [x] `HERO-04` With hero expanded, confirm action buttons are top-aligned and vertical.
  - Evidence/notes:
- [x] `HERO-05` With hero collapsed, confirm primary action plus secondary/more action layout behaves correctly.
  - Evidence/notes:
- [x] `HERO-06` Tap favorite on account/profile cards and nested/event profile cards; confirm it toggles or opens the canonical gate without reloading/reopening the app.
  - Evidence/notes:
- [x] `HERO-07` In anonymous web, tap favorite and confirm the canonical app-promotion modal appears, not phone login.
  - Evidence/notes:
- [x] `HERO-08` In anonymous web, tap confirm presence and confirm the contextual app-promotion modal appears.
  - Evidence/notes:
- [x] `HERO-09` In anonymous web, tap invite and confirm the contextual app-promotion modal appears.
  - Evidence/notes:

## Nested Account Profile Groups
- [x] `NEST-01` In Admin, edit an account profile type with nested-account capability enabled and confirm linked-account groups are available.
  - Evidence/notes:
- [x] `NEST-02` In Admin, edit an account profile type without nested-account capability and confirm linked-account groups are not available.
  - Evidence/notes:
- [x] `NEST-03` Create a new account/profile for a capable type and confirm linked-account groups are available in the create flow, not only update.
  - Evidence/notes:
- [x] `NEST-04` In the linked-account group editor, confirm account selection is searchable.
  - Evidence/notes:
- [x] `NEST-05` In the linked-account group editor, confirm account selection can be filtered by account/profile type.
  - Evidence/notes:
- [x] `NEST-06` Public account detail shows configured linked-account groups as tabs with the configured group label/order.
  - Evidence/notes:
- [x] `NEST-07` Navigate account -> linked account -> back and confirm the route/controller state returns to the original parent account without mixed content.
  - Evidence/notes:

## Event And Occurrence Profile Groups
- [x] `EVG-01` In Admin, create/edit an event with grouped related profiles, for example `Bandas` and `Expositores`.
  - Evidence/notes:
- [x] `EVG-02` Put different profile types in the same group and confirm the public event tab uses the group label, not the profile type plural label.
  - Evidence/notes:
- [x] `EVG-03` Open an event that already existed without groups and confirm legacy related profiles still render by fallback grouping.
  - Evidence/notes:
- [x] `EVG-04` Create or edit an event with multiple occurrences and confirm each occurrence keeps the correct selected occurrence, programming, and location while the public profile tabs remain the aggregate event set.
  - Evidence/notes: 2026-06-04 Playwright mobile navigation on `manual-v0208-evg-multi-ocorrencias` confirmed the selected occurrence changed date/programming/location while the tab set remained `Palco Bandas` + `Vila Expositores` on both occurrences.
- [x] `EVG-05` In public event detail, switch occurrences and confirm programming/date/URL change while profile tabs/cards remain the aggregate event set.
  - Evidence/notes:
    - Previous selected-occurrence-tab expectation was superseded on 2026-06-03.
    - Accepted behavior: all event-level and occurrence-owned groups/accounts appear in one aggregate public tab set.
    - Switching occurrence must update route/date/programming only; it must not narrow, rebuild, or swap the public profile tabs.
    - 2026-06-04 Playwright mobile navigation on `https://guarappari.belluga.space/agenda/evento/manual-v0208-evg-multi-ocorrencias?occurrence=6a20530b46b796c1e20637d3` and `...?occurrence=6a20530b46b796c1e20637d7` confirmed URL/date/programming changes while tabs stayed aggregate.

## Invite, Share, WhatsApp, And Time Language
- [x] `INV-01` Open an invite link and confirm title, flyer time, venue, participants, accept/decline/details actions are UI-rendered and readable independent of cover image content.
  - Evidence/notes: Revalidated on 2026-06-04 after the invite read-model/UI fix. Focused Laravel invite flow tests passed with grouped `profile_groups` + `linked_account_profiles`, focused Flutter invite DTO/screen/share suites passed, `fvm dart analyze --format machine` passed, canonical web build `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` produced `__WEB_BUILD_SHA__=88227417`, and source-owned Playwright mutation `NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='invite landing exposes dynamic share metadata' ... bash tools/flutter/run_web_navigation_smoke.sh mutation` passed in `24.3s`. The browser spec now validates the real invite preview payload plus successful Flutter invite-route bootstrap on the served bundle instead of relying on an unstable headless `role=img` assumption.
- [ ] `INV-02` In authenticated/app context, share an event through system share and confirm message uses `Responder ao convite:` plus `/invite?code=...`.
  - Evidence/notes: Manual/device validation still pending. Focused Flutter tests passed on 2026-06-04, but no attached device/app context was available in this turn to clear this manually.
- [ ] `INV-03` In authenticated/app context, share an event through WhatsApp and confirm message uses the same invitation semantics and opens WhatsApp/app path correctly.
  - Evidence/notes: Manual/device validation still pending. Focused Flutter tests passed on 2026-06-04, but no attached device/app context was available in this turn to clear this manually.
- [x] `INV-04` In anonymous web, share a public event and confirm message uses `Ver evento:` plus `/agenda/evento/:slug?occurrence=...`.
  - Evidence/notes:
- [ ] `INV-05` Confirm invitation messages do not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links.
  - Evidence/notes: Automated coverage exists in focused Flutter tests, but this remains manually pending in authenticated/app share flows for this turn.
- [x] `INV-06` Confirm public anonymous share does not say `Convite para`, `te convidou`, or `Responder ao convite:`.
  - Evidence/notes:
- [x] `INV-07` Confirm event/time labels use informal flyer language, for example `Dom, 7 jun · 0h47`, and avoid formal/raw date strings.
  - Evidence/notes:
- [x] `INV-08` Confirm agenda/planning surfaces still show end time only where useful, without awkward formal formatting.
  - Evidence/notes: 2026-06-04 Playwright mobile capture on tenant home/agenda showed compact labels such as `0h47 às 2h47` and start-only labels such as `0h47` under the date header, without repeating the full date context on same-day cards.

## Media Crop And Safe Areas
- [x] `MEDIA-01` In Admin, upload event cover and confirm crop ratio is vertical `5:7`.
  - Evidence/notes:
- [x] `MEDIA-02` In Admin, upload account/profile cover and confirm crop ratio is `15:16`.
  - Evidence/notes:
- [x] `MEDIA-03` Confirm translucent safe-area/respiro zones are inside the crop rectangle, not fixed over the modal viewport.
  - Evidence/notes:
- [x] `MEDIA-04` Move/resize the crop and confirm the safe-area guide moves/resizes together with the crop.
  - Evidence/notes:
- [x] `MEDIA-05` Confirm unrelated image slots, such as avatar, type visual, map filter visual, and public web default image, keep their expected behavior.
  - Evidence/notes:

## Deep Links And Route Scope
- [ ] `DL-01` On Android with app cold, open Account Profile deep link and confirm it opens the correct detail screen.
  - Evidence/notes: Blocked in this turn. `adb devices -l` returned no attached device on 2026-06-04.
- [ ] `DL-02` On Android with app already open, open Account Profile deep link and confirm it opens the correct detail screen.
  - Evidence/notes: Blocked in this turn. `adb devices -l` returned no attached device on 2026-06-04.
- [ ] `DL-03` On Android with app cold, open Event deep link and confirm it opens the correct event detail/occurrence.
  - Evidence/notes: Blocked in this turn. `adb devices -l` returned no attached device on 2026-06-04.
- [ ] `DL-04` On Android with app already open, open Event deep link and confirm it opens the correct event detail/occurrence.
  - Evidence/notes: Blocked in this turn. `adb devices -l` returned no attached device on 2026-06-04.
- [x] `DL-05` Navigate between stacked account/event details and back; confirm route-scoped controllers do not cross-contaminate screen state.
  - Evidence/notes:

## Final Sign-Off
- [ ] `SIGN-01` No manual blocker remains open.
  - Evidence/notes:
- [ ] `SIGN-02` Any issue found has a bug/TODO created before promotion.
  - Evidence/notes:
- [ ] `SIGN-03` Package is manually approved for promotion.
  - Evidence/notes:
