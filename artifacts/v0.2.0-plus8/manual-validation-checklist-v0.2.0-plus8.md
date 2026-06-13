# v0.2.0+8 Manual Validation Checklist

## Context
- **Package:** `v0.2.0+8`
- **Environment:** `https://guarappari.belluga.space`
- **Created at:** `2026-06-03`
- **Last aligned at:** `2026-06-09`
- **Status legend:** `[ ]` pending, `[.]` automated evidence available, `[x]` manually validated, `[!]` issue found
- **Note:** from this pass onward, `[x]` is reserved for explicit manual validation. Items with automated validation evidence are marked as `[.]`.
- **Note:** prior manual marks were cleared for a fresh manual pass.
- **Alignment note:** this checklist was reconciled against the current authoritative branch `reconcile/v0.2.0-plus8-cross-stack-20260526` at SHA `88d0dc47eaa0`, the package-wide CI Equivalent report `foundation_documentation/artifacts/tmp/reconcile_validation_status_20260608_123206.md`, and the active `promotion_lane/v0.2.0+8` TODO set.

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

## Map Runtime Resilience
- [!] `MAP-06` Open the app and go directly to the map with location permission already granted; confirm the map shell stays usable, filters render, and POIs load or retry with a bounded policy without requiring you to leave and re-enter the screen.
  - Evidence/notes: 2026-06-09 manual runtime validation contradicted current package closure claims. The first map entry showed `Nao foi possivel carregar os pontos de interesse.`, no visible POIs, and no filters; leaving the map and entering again recovered the expected shell. This remains a current-package blocker. The no-coordinate contract is still mandatory: normal permission-granted entry must not issue its first POI request without a resolved coordinate, and only the explicit `continue without location` path may use tenant fallback. But the earlier focused Flutter RED that seemed to prove a fallback-origin race used a coordinate outside the tenant radius, so it was not valid proof of the live bug; that RED has been corrected and is now green. Closure rule: this item is not resolved by symptom-only retry, and it is not closed by the green unit contract alone. We still need authoritative runtime/browser evidence that isolates why first-entry auth/bootstrap/filters/POIs fail in the served bundle.

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
- [x] `DIR-06` From the focused map POI card, tap `Traçar rota` and confirm it opens the same canonical chooser/origin-resolution flow used by `Como Chegar`.
  - Evidence/notes: Manually revalidated after the earlier contradiction. The served runtime now opens the canonical chooser/origin-selection flow as expected.

## Immersive Hero, Actions, And Gates
- [x] `HERO-01` Open `parceiro/qa-discovery-tag-longa` and confirm the account hero height/fade reads well with cover image.
  - Evidence/notes:
- [x] `HERO-02` Open a public event detail and confirm event hero height/fade reads well with cover image.
  - Evidence/notes: 2026-06-04 Playwright mobile capture on `https://guarappari.belluga.space/agenda/evento/pw-event-share-boundary-store-release-5?occurrence=6a17bada93ba592fce055f5d` showed the title/time/location remaining readable over a busy cover and the fade dying into the page surface as expected.
- [x] `HERO-03` Confirm there is no extra divider/line between hero content and tabs.
  - Evidence/notes:
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
- [.] `NEST-03` In the canonical account+profile onboarding create flow, choose a capable type and confirm linked-account groups are available there too, not only in update.
  - Evidence/notes: Automated evidence is green through the dedicated create-screen widget test plus the authoritative `admin-final` Playwright mutation covering `/admin/accounts/create` capability gating. The earlier manual `!` was reclassified as stale false-red after revalidation.
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
  - Evidence/notes: 2026-06-04 Playwright mobile navigation on `manual-v0208-evg-multi-ocorrencias` confirmed the selected occurrence changed date/programming/location while the tab set remained `Palco Bandas` + `Vila Expositores` on both occurrences. The shared `manual-v0208-*` fixtures used for that historical proof were retired on 2026-06-12 after drift investigation showed inconsistent admin payload state (`profile_groups=[]` while programming still referenced linked profiles). Future manual reruns of this item require reseeded canonical fixtures, not those retired slugs.
- [!] `EVG-05` In public event detail, switch occurrences from `Programação` and confirm both click and drag interactions update programming/date/URL without hard reloading while profile tabs/cards remain the aggregate event set.
  - Evidence/notes:
    - Previous selected-occurrence-tab expectation was superseded on 2026-06-03.
    - Accepted behavior: all event-level and occurrence-owned groups/accounts appear in one aggregate public tab set.
    - Switching occurrence must update route/date/programming only; it must not narrow, rebuild, swap the public profile tabs, or hard reload the page.
    - 2026-06-09 manual runtime validation contradicted the earlier browser evidence: both clicking the Programação date selector and dragging it laterally still reload the page in the served bundle.

## Queryability And Public Navigation Contract
- [x] `QRY-01` In Admin selectors used by nested account groups and event/occurrence profile groups, confirm non-queryable profile types/profiles do not appear as selectable candidates.
  - Evidence/notes: Automated coverage is green through Laravel feature tests plus Playwright mutation validating admin selectors against the canonical queryability gateway. Manual review should confirm the real tenant-admin UI no longer leaks hidden/non-queryable candidates.
- [.] `QRY-02` In a public event or account detail that mixes visible and restricted related profiles, confirm only allowed profiles render publicly and no stale hidden profile leaks into tabs/cards.
  - Evidence/notes: This covers the previously broken stale-reference scenario that produced clickable cards leading to `Algo deu errado`.
  - Manual note: this is no longer authorable through the normal Admin UI because hidden/non-queryable profiles are correctly excluded from selectors. Manual validation only applies if a pre-seeded inconsistent fixture already exists; otherwise rely on the automated mutation/runtime diagnostic that injects the stale hidden reference and verifies public suppression.
- [x] `QRY-03` When a related profile is allowed to appear as a participant but is not publicly navigable, confirm the card/row is non-clickable and does not navigate to `/parceiro/:slug`.
  - Evidence/notes: Automated coverage passed for backend contract (`can_open_public_detail` / `public_detail_path`) and Flutter/runtime consumption; use the previously problematic `Outro Grupo` style scenario as the manual spot-check.
- [x] `QRY-04` In public Discovery and other public listing surfaces, confirm non-queryable/non-publicly-discoverable profile types do not appear as visible public options or cards.
  - Evidence/notes: Keep this separate from the map-filter validation already approved; this is about the broader public navigation/listability contract.

## Taxonomies And Runtime Facets
- [x] `TAX-01` In Home Agenda, confirm type/taxonomy filters show their resolved human label before selection and keep the same readable label after selection.
  - Evidence/notes: This specifically covers the earlier regression where filters rendered as icon-only until selected.
- [x] `TAX-02` In Home Agenda, confirm the backend facet universe only shows type/taxonomy options that are valid for the current filtered universe, rather than options that immediately lead to empty results.
  - Evidence/notes: Review at least one case where page-1 items alone would not be enough to infer all valid options.
- [x] `TAX-03` In public Discovery, confirm the same runtime-facet behavior: labels are visible by default, and the option set reflects the current universe rather than a page-local or static catalog-only list.
  - Evidence/notes:
- [x] `TAX-04` In public event/detail surfaces, confirm taxonomy chips display canonical labels instead of slugs/raw legacy tags.
  - Evidence/notes: Focus on immersive event hero, cards, and any touched share/invite preview surfaces that expose event taxonomy.
- [x] `TAX-05` For an event with occurrence-level taxonomy override, switch occurrences and confirm the visible chips/taxonomy summary reflect the selected occurrence semantics rather than merging stale parent-event terms.
  - Evidence/notes:

## Invite, Share, WhatsApp, And Time Language
- [x] `INV-01` Open an invite link and confirm title, flyer time, venue, participants, accept/decline/details actions are UI-rendered and readable independent of cover image content.
  - Evidence/notes: Revalidated on 2026-06-04 after the invite read-model/UI fix. Focused Laravel invite flow tests passed with grouped `profile_groups` + `linked_account_profiles`, focused Flutter invite DTO/screen/share suites passed, `fvm dart analyze --format machine` passed, canonical web build `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` produced `__WEB_BUILD_SHA__=88227417`, and source-owned Playwright mutation `NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='invite landing exposes dynamic share metadata' ... bash tools/flutter/run_web_navigation_smoke.sh mutation` passed in `24.3s`. The browser spec now validates the real invite preview payload plus successful Flutter invite-route bootstrap on the served bundle instead of relying on an unstable headless `role=img` assumption.
- [x] `INV-02` In authenticated/app context, share an event through system share and confirm message uses `Responder ao convite:` plus `/invite?code=...`.
  - Evidence/notes: Manual/device validation still pending. Focused Flutter tests and package-level CI Equivalent are green; this remains open only for final human sign-off in authenticated/app context.
- [x] `INV-03` In authenticated/app context, share an event through WhatsApp and confirm message uses the same invitation semantics and opens WhatsApp/app path correctly.
  - Evidence/notes: Manual/device validation still pending. Focused Flutter tests and package-level CI Equivalent are green; this remains open only for final human sign-off in authenticated/app context.
- [x] `INV-04` In anonymous web, share a public event and confirm message uses `Ver evento:` plus `/agenda/evento/:slug?occurrence=...`.
  - Evidence/notes:
- [x] `INV-05` Confirm invitation messages do not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links.
  - Evidence/notes: Automated coverage exists in focused Flutter tests, but this remains manually pending in authenticated/app share flows for final sign-off.
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
- [.] `DL-01` On Android with app cold, open Account Profile deep link and confirm it opens the correct detail screen.
  - Evidence/notes: ADB/device validation passed. The package evidence now includes Android cold-start replay for Account Profile plus the later package-level CI Equivalent on authoritative SHA `88d0dc47eaa0`.
- [.] `DL-02` On Android with app already open, open Account Profile deep link and confirm it opens the correct detail screen.
  - Evidence/notes: ADB/device validation passed in the same package evidence set; warm-start route parity remained green after the final package sweep.
- [.] `DL-03` On Android with app cold, open Event deep link and confirm it opens the correct event detail/occurrence.
  - Evidence/notes: ADB/device validation passed. This was the explicit event cold-start bugfix and remained green through the final package-level CI Equivalent and browser deep-link contract coverage.
- [.] `DL-04` On Android with app already open, open Event deep link and confirm it opens the correct event detail/occurrence.
  - Evidence/notes: ADB/device validation passed; warm Event launch and contract parity remained green in the final package evidence.
- [.] `DL-05` Navigate between stacked account/event details and back; confirm route-scoped controllers do not cross-contaminate screen state.
  - Evidence/notes:

## Final Sign-Off
- [ ] `SIGN-01` No manual blocker remains open.
  - Evidence/notes:
- [ ] `SIGN-02` Any issue found has a bug/TODO created before promotion.
  - Evidence/notes:
- [ ] `SIGN-03` Package is manually approved for promotion.
  - Evidence/notes:
