# Tenant Public Invite, Flyer Time, and Hero Cover Media Presets

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The current v0.2.0+8 public-event package has four connected UX/media issues that must be handled as one contract instead of separate local fixes:

- The production invite screen is visually functional but not yet aesthetically strong enough for event conversion. It is an invite surface, not the immersive event hero.
- The invite screen must not depend on the event image/poster for critical information. Event title, date/time, venue, related profiles, and decision actions are UI-owned/accessibility-owned content rendered over a deterministic readable surface; the image is emotional context only.
- The invite screen currently risks repeating only the venue account profile as event metadata. Related account profiles must come from the canonical event relationship surface, not from the venue fallback, so artists/exhibitors/hosts/other linked profiles can appear correctly.
- Several required visual/data behaviors already exist in the public event card and event detail flows. This TODO must converge invite presentation onto those existing event contracts instead of designing an independent invite-only event summary. Known existing anchors include `VenueEventResume.counterpartProfiles`, `EventDetails`, `UpcomingEventCard`, the event detail profile-group/tab builder, and the current `InviteEventHero` reuse of `CarouselCard`.
- The centralized event time label improved technical ownership but remains visually too formal/system-like. The approved product direction is a more natural flyer-style label for invite/promotional contexts, such as `Qua, 10 jun · 8h`, while preserving range-capable labels only where the surface is genuinely about planning or agenda comprehension.
- The event invite/share copy has a canonical builder today, but the current implementation mixed two contracts: real invite links (`/invite?code=...`) and neutral public event sharing (`/agenda/evento/...`). That produced conceptually wrong invite messages with `Detalhes` and `Como chegar` links inside a conversion path that should send the recipient back to the invitation funnel.
- The approved product direction is context-aware by identity and intent:
  - authenticated/app users invite when they share an event; system share and WhatsApp should create or reuse the share-code invite link and send `/invite?code=...`;
  - anonymous web users may share the public event page, but cannot create or materialize invite links; their share uses `/agenda/evento/:slug?occurrence=...` with neutral copy;
  - WhatsApp is a delivery channel, not a separate semantic action. It inherits the current intent: invite for authenticated/app users, neutral public share for anonymous web users.
- Invite copy must drive the recipient to `Responder ao convite:` and must not include map, `Como chegar`, or separate `Detalhes` links. Public event share copy may use neutral `Ver evento:` language, but must not pretend the sender invited the recipient.
- The previous `TODO-v0.2.0+8-cover-crop-560x512.md` intentionally applied one global `TenantAdminImageSlot.cover` ratio. The later public hero sizing analysis invalidated that assumption for event and account-profile heroes.
- Tenant-admin upload/crop widgets should not only enforce the right ratio; they should also show the operator the visual safe area/respiro so important poster content is not placed under public UI overlays.

This TODO consolidates the product decision and implementation boundary for invite presentation, event flyer-style time language, and tenant-admin hero-cover upload/crop presets. It stays on the current v0.2.0+8 reconcile lane and must promote with the same package.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `tenant-public-invite-flyer-time-hero-cover-media-presets`
- **Why this is the right current slice:** the four issues share the same user-visible event conversion surface and media contract. Fixing crop ratios without invite/time layout would still leave the event invite visually weak; fixing invite/time without crop guidance would keep producing bad artwork.
- **Direct-to-TODO rationale:** the user supplied concrete product direction, the relevant Flutter/admin surfaces are known, and this is a bounded v0.2.0+8 refinement rather than a new module.

## Contract Boundary
- This TODO defines the approved public invite aesthetic direction, context-aware event time display values, canonical event invite/share copy, and tenant-admin event/account-profile hero cover upload/crop presets with visible safe-area guidance.
- This TODO supersedes the final media-contract assumption from `TODO-v0.2.0+8-cover-crop-560x512.md` for event and account-profile hero cover usage. The old TODO remains historical evidence for the prior local validation, but `560/512` must no longer be treated as the final launch contract for those two hero contexts.
- Implementation must not begin before explicit `APROVADO`, frozen decision baseline, rule ingestion, and `todo_authority_guard.py` returning `Overall outcome: go`.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Feature`, `Flutter`, `Tenant-Public`, `Tenant-Admin`, `Media`, `UX`, `Share-Invite-Link-Contract-Pending`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through the declared promotion lane; the current package-wide mimic loop has not reopened this scope.

## Scope
- [x] Redesign/refine the invite screen visual hierarchy in `tenant_public/invites`, using the Stitch study only as historical directional input. Do not continue image-generation work for this TODO unless explicitly requested again.
- [x] Inventory and reuse the existing event card/detail presentation contracts before changing invite UI. The invite flow should consume/extract shared event summary components/projections rather than duplicating equivalent title/time/location/profile rendering.
- [x] Preserve and reuse the current event-card/event-screen distinction between venue metadata and counterpart/related profiles. `VenueEventResume.counterpartProfiles` is the initial known projection anchor because it already excludes `partyType/profileType == venue`.
- [x] Fix the current invite-specific gap where `InviteEventHero` builds a `VenueEventResume` with `linkedAccountProfiles: const []`; that prevents existing event summary components from showing counterpart profiles.
- [x] Ensure all critical invite information is rendered by Flutter UI components over a deterministic readable layer, independent of cover image availability, crop, brightness, or text embedded in the artwork.
- [x] Ensure the event cover/poster image is decorative/emotional context only; no event title, date/time, venue, related profile, accept/decline, or details action may rely on reading pixels from the image.
- [x] Ensure invite metadata lists/summarizes canonical related account profiles for the event, not only the venue profile. The venue remains location metadata and must not be duplicated as the only profile row unless it is explicitly the only canonical related profile.
- [x] Preserve the event-related-profile fallback contract: events with newly grouped related profiles use group data; existing events without groups still surface legacy linked profiles instead of showing an empty or venue-only profile summary.
- [x] Reuse or extract the event detail grouped-profile summarization logic used by `_buildDynamicProfileTabs` / `_groupLinkedProfilesByType` instead of creating a second invite-only grouping rule.
- [x] Preserve invite behavior: close, view details, accept, decline, auth gate, route guards, and invite decision semantics must remain unchanged unless already governed elsewhere.
- [x] Add/adjust canonical event time display values for public contexts so widgets consume model/projection values rather than assembling raw date/time strings.
- [x] Establish a start-focused flyer label for invite/public promotional contexts. Target language: `Qua, 10 jun · 8h` for events where start time is the important conversion signal.
- [x] Preserve compact range labels for agenda/planning contexts where end time is useful. Target language may include the end time, for example `Qua, 10 jun · 8h às 10h`, but must still be model/projection-owned rather than widget-local.
- [x] Treat end time as contextual: omit it by default in invite/promotional surfaces unless event semantics or an approved planning surface require duration/end-time comprehension.
- [x] Split the canonical event invitation payload from the neutral public event share payload. Do not let one builder force invite language onto anonymous public shares or public event details/map links into invite messages.
- [x] Authenticated/app event share, WhatsApp share, and invite actions must create or reuse the canonical share-code invite link and send `/invite?code=...`.
- [x] Anonymous web event share and WhatsApp share may remain available, but must use the neutral public event URL `/agenda/evento/:slug?occurrence=...`.
- [x] Invite copy must include grouped participant/profile lines using the same group labels/order as event tabs when `profileGroups` exists, with the legacy linked-profile-by-type fallback when groups are absent.
- [x] Invite copy must label the link as `Responder ao convite:` and must not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links.
- [x] Public event share copy must be neutral, must not use inviter-specific language, and must label the public URL as `Ver evento:`.
- [x] Preserve direct invite composer semantics and guards: anonymous web users cannot create share codes and must keep the canonical app-promotion path for invite-only actions.
- [x] Remove/avoid duplicate subject/message assembly in call sites. Call sites should choose the canonical invitation payload or public share payload according to identity/intent and then share the resulting subject/message.
- [x] Replace event/account-profile hero cover upload usage with dedicated media slots/presets instead of one global `cover` slot.
- [x] Event hero cover preset must target the recommended `5:7` ratio, with source guidance around `1800x2520` ideal and `1440x2016` minimum.
- [x] Account profile hero cover preset must target the recommended near-square portrait ratio `15:16`, with source guidance around `1800x1920` ideal and `1440x1536` minimum.
- [x] Tenant-admin crop/upload UI must show a safe-area/respiro overlay for each hero preset.
- [x] Tenant-admin crop/upload UI must render translucent interface/respiro zones inside the active crop rectangle, using the crop widget overlay surface so the guide moves/resizes with the crop instead of floating over the modal viewport.
- [x] Keep avatar, type visual, map filter, public web metadata, branding, and unrelated static asset/media slots unchanged unless a focused code dependency requires a mechanical enum update.

## Delivery Status Semantics
- `Pending`: contract exists but implementation is not authorized.
- `Local-Implemented`: implementation and required local validations passed on the current reconcile lane.
- `Lane-Promoted`: merged through the declared lane threshold.
- `Production-Ready`: final promotion threshold and confidence gates complete.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `flutter-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `foundation_documentation:reconcile/v0.2.0-plus8-cross-stack-20260526`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage/main promotion package`

## Out of Scope
- [ ] Backend media storage/CDN changes.
- [ ] Reprocessing or recropping already uploaded production images.
- [ ] Changing invite API semantics, invite decision rules, auth guards, or share-code lifecycle.
- [ ] Redesigning the immersive event detail hero in this TODO.
- [ ] Creating new tenant-public routes or scopes/subscopes.
- [ ] Changing static asset hero ratio unless explicitly approved after implementation discovery.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** enum/preset additions, crop service metadata updates, upload-field copy updates, focused tests, public invite layout polish, and event time label getters that remain subordinate to this same invite/media/time objective.
- **Must update or split the TODO:** backend schema/storage mutation, invite mutation/auth policy change, new route/scope, broad admin media package extraction, or a full redesign of agenda/event detail surfaces beyond time display consumption.

## Definition of Done
- [x] `DOD-01` Invite screen presents a stronger event-invite aesthetic while preserving existing invite behavior.
- [x] `DOD-01A` Invite screen remains fully readable and actionable when the cover image is busy, missing, low-contrast, or contains misleading embedded text; critical information is UI-rendered, not artwork-dependent.
- [x] `DOD-01B` Invite screen event-profile metadata uses the canonical related account profiles/groups for the event and does not collapse to venue-only metadata when other linked profiles exist.
- [x] `DOD-01C` Invite presentation reuses or extracts existing event card/detail projection/component behavior; no second invite-only title/time/location/profile formatter is introduced where an event summary contract already exists.
- [x] `DOD-02` Invite/promotional event surfaces use a start-focused flyer time label such as `Qua, 10 jun · 8h` instead of formal/system labels where end time is not important.
- [x] `DOD-03` Agenda/planning surfaces can still use range labels such as `Qua, 10 jun · 8h às 10h` when end time matters, without widgets locally calculating final date/time language.
- [x] `DOD-03A` Event invitation messages use the canonical invitation payload, include natural flyer time, grouped participant/profile lines when available, and use only the `/invite?code=...` link under `Responder ao convite:`.
- [x] `DOD-03B` Neutral public event share messages use the canonical public share payload, include natural flyer time and the public event URL under `Ver evento:`, and do not use invite/inviter language.
- [x] `DOD-03C` Event share call sites choose payload by identity and intent: authenticated/app users generate or reuse invite links; anonymous web users share only the public event URL; WhatsApp follows the same rule as the triggering action.
- [x] `DOD-04` Event cover upload/crop uses the event hero preset ratio and shows a safe-area/respiro guide.
- [x] `DOD-05` Account profile cover upload/crop uses the account-profile hero preset ratio and shows a safe-area/respiro guide.
- [x] `DOD-06` The prior global `560/512` cover assumption is no longer used for event/account-profile hero cover uploads.
- [x] `DOD-07` Existing non-target image slots remain unchanged.
- [x] `DOD-08` Focused tests, analyzer, rule matrix, web build, and at least one source-owned browser/device navigation validation pass before any delivery claim.

## Validation Steps
- [x] Add fail-first or behavior-first Flutter tests for event time display variants: start-focused flyer/promotional label and range-capable agenda/planning label.
- [x] Add fail-first or behavior-first tests for canonical invitation payload copy, including grouped participants, no participants, `Responder ao convite:`, `/invite?code=...`, and absence of `Detalhes`, `Como chegar`, `/mapa`, and venue POI links.
- [x] Add fail-first or behavior-first tests for neutral public event share payload copy, including `Ver evento:`, `/agenda/evento/...`, and absence of invite/inviter language.
- [x] Add widget/runtime tests for authenticated/app event share and WhatsApp generating/reusing invite links, plus anonymous web event share and WhatsApp using public event URLs without share-code creation.
- [x] Add widget tests for invite screen visual/time contract, deterministic readable content layer, related-account-profile summary, and preserved accept/decline/details behavior.
- [x] Add navigation/runtime validation using an event whose venue differs from multiple related account profiles, proving the invite screen shows/summarizes the related profiles and does not repeat venue as the only profile metadata.
- [x] Add widget/unit tests for tenant-admin image crop ratio presets and safe-area guide rendering.
- [x] Run focused Flutter suite for invite/time/media upload surfaces.
- [x] Run `fvm dart analyze --format machine`.
- [x] Run the Flutter rule matrix.
- [x] Build web with the project canonical script before browser validation.
- [x] Run source-owned Playwright/navigation validation against the refreshed local-public bundle for invite screen and at least one public event path.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | Invite screen presents a stronger event-invite aesthetic while preserving behavior. | widget + runtime | `fvm flutter test --no-pub ... invite_flow_screen_test.dart invite_share_screen_test.dart`; `run_web_navigation_smoke.sh mutation` raw grep for invite metadata. | Flutter/widget + `https://guarappari.belluga.space` | `passed` | Focused suite passed with 202 tests; source-owned Playwright selected invite landing metadata spec. |
| `DOD-01A` | `Definition of Done` | Invite screen critical information is UI-rendered and independent of cover readability. | widget + runtime | `invite_flow_screen_test.dart`; `invite_share_screen_test.dart`; Playwright invite landing metadata. | Flutter/widget + browser | `passed` | Critical title/time/location/actions are Flutter text/actions; cover is decorative context. |
| `DOD-01B` | `Definition of Done` | Invite screen shows canonical related account profile metadata instead of venue-only fallback when related profiles exist. | widget + runtime + seeded data | `event_related_profile_groups_test.dart`; `invite_share_screen_test.dart`; `INVITE-SESSION-CONTEXT invite landing exposes dynamic share metadata`. | Flutter/widget + browser mutation | `passed` | `InviteModel` now carries linked profiles/profile groups/venue id; runtime invite metadata spec passed. |
| `DOD-01C` | `Definition of Done` | Invite presentation reuses/extracts existing event card/detail summary behavior instead of duplicating it. | source audit + widget | `lib/application/schedule/event_related_profile_groups.dart`; `InviteEventHero`; event detail dynamic tabs; focused 202-test suite. | Flutter source + widget tests | `passed` | Grouping lives once in application helper and is consumed by event detail tabs and invite/share copy. |
| `DOD-02` | `Definition of Done` | Invite/promotional surfaces use start-focused flyer time label. | unit + widget + runtime | `event_invite_share_payload_test.dart`; `venue_event_resume_test.dart`; `invite_share_screen_test.dart`; Playwright invite metadata. | Flutter/widget + browser | `passed` | `flyerLabel` is model/projection-owned and share/invite call sites consume it. |
| `DOD-03` | `Definition of Done` | Agenda/planning range labels remain available and centralized. | unit + widget | `venue_event_resume_test.dart`; `upcoming_event_card_test.dart`; `my_events_carousel_card_test.dart`; `event_poi_detail_card_test.dart`; `event_live_now_card_test.dart`. | Flutter widget/unit | `passed` | Agenda/map/home/live cards consume centralized range/detail labels. |
| `DOD-03A` | `Definition of Done` | Event invitation messages use only the `/invite?code=...` link under `Responder ao convite:` and omit `Detalhes`, `Como chegar`, `/mapa`, and venue POI links. | unit + widget + browser | `fvm flutter test --no-pub test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `fvm flutter analyze --no-pub`; canonical web build; `tools/flutter/run_web_navigation_smoke.sh mutation` passed 27/27. | Flutter/widget/browser | `passed` | Payload, invite share fallback, immersive share, immersive WhatsApp, and browser boundary specs passed. |
| `DOD-03B` | `Definition of Done` | Neutral public event share messages use `Ver evento:` with `/agenda/evento/...` and no invite/inviter language. | unit + widget + browser | `event_invite_share_payload_test.dart`; `immersive_event_detail_screen_test.dart` anonymous-web share case; `event_share_boundary.spec.js` captured `navigator.share` payload after canonical web build. | Flutter/widget/browser | `passed` | Anonymous web share keeps `/agenda/evento/:slug?occurrence=...`, zero share-code creation, neutral copy, and `Ver evento:` in browser runtime. |
| `DOD-03C` | `Definition of Done` | Event share call sites choose payload by identity and intent: authenticated/app users generate or reuse invite links; anonymous web users share public event URLs; WhatsApp follows the triggering action. | source audit + widget + browser | Focused command passed with authenticated share, authenticated WhatsApp, anonymous web share, invite composer guard, and invite share footer/system fallback coverage; `tools/flutter/run_web_navigation_smoke.sh mutation` passed 27/27 after build. | Flutter source + widget + browser | `passed` | `EventInviteSharePayloadBuilder.buildInvitation` and `buildPublicShare` are explicit payload entry points; browser confirms anonymous web share creates no share code. |
| `DOD-04` | `Definition of Done` | Event cover upload/crop uses event hero preset and safe guide. | unit + widget + admin tests | `tenant_admin_image_ingestion_service_test.dart`; `tenant_admin_image_crop_sheet_test.dart`; `tenant_admin_event_form_screen_test.dart`. | Flutter/admin widget/unit | `passed` | Event cover uses `TenantAdminImageSlot.eventHeroCover` at `5:7` with safe-area guide. |
| `DOD-05` | `Definition of Done` | Account profile cover upload/crop uses account hero preset and safe guide. | unit + widget + admin tests | `tenant_admin_image_ingestion_service_test.dart`; `tenant_admin_image_crop_sheet_test.dart`; account create/profile create/profile edit tests. | Flutter/admin widget/unit | `passed` | Account/profile cover uses `TenantAdminImageSlot.accountProfileHeroCover` at `15:16` with safe-area guide. |
| `DOD-06` | `Definition of Done` | `560/512` no longer drives event/account-profile hero cover uploads. | test + source audit | Ingestion/crop tests plus source usage of `eventHeroCover` and `accountProfileHeroCover`. | Flutter source + tests | `passed` | Legacy `cover` remains only for preserved shared/static cover contexts. |
| `DOD-07` | `Definition of Done` | Non-target slots unchanged. | unit/widget regression | `tenant_admin_image_ingestion_service_test.dart` non-cover slot preservation matrix. | Flutter unit/widget | `passed` | Avatar, logos/icons, map filter, type visual, and public web default image retain prior ratios. |
| `DOD-08` | `Definition of Done` | Local CI-equivalent passes. | command evidence | Analyzer, rule matrix, focused 202-test suite, canonical web build, source-owned Playwright, diff hygiene. | Flutter/web | `passed` | Bundle served with `__WEB_BUILD_SHA__=88227417` on both local-public domains. |
| `SCOPE-01` | `Scope` | Redesign/refine the invite screen visual hierarchy in `tenant_public/invites`, using the Stitch study only as historical directional input. Do not continue image-generation work for this TODO unless explicitly requested again. | widget + runtime | Invite flow/share focused tests and Playwright invite metadata spec passed. | Flutter/widget + browser | `passed` | No new image generation was performed. |
| `SCOPE-02` | `Scope` | Inventory and reuse the existing event card/detail presentation contracts before changing invite UI. The invite flow should consume/extract shared event summary components/projections rather than duplicating equivalent title/time/location/profile rendering. | source audit + tests | `VenueEventResume`, `InviteEventHero`, `UpcomingEventCard`, and shared grouping helper covered in focused suite. | Flutter source + tests | `passed` | Summary behavior is centralized through existing projections plus application grouping helper. |
| `SCOPE-03` | `Scope` | Preserve and reuse the current event-card/event-screen distinction between venue metadata and counterpart/related profiles. `VenueEventResume.counterpartProfiles` is the initial known projection anchor because it already excludes `partyType/profileType == venue`. | unit + widget | `venue_event_resume_test.dart`, `event_related_profile_groups_test.dart`, and event detail tests passed. | Flutter tests | `passed` | Venue remains location metadata; linked profiles are grouped separately. |
| `SCOPE-04` | `Scope` | Fix the current invite-specific gap where `InviteEventHero` builds a `VenueEventResume` with `linkedAccountProfiles: const []`; that prevents existing event summary components from showing counterpart profiles. | source + widget | `InviteFromEventFactory`, `InviteModel`, `InviteEventHero`, and invite share tests passed. | Flutter source + tests | `passed` | Invite route now preserves linked profiles and profile groups. |
| `SCOPE-05` | `Scope` | Ensure all critical invite information is rendered by Flutter UI components over a deterministic readable layer, independent of cover image availability, crop, brightness, or text embedded in the artwork. | widget + runtime | Invite flow/share tests and Playwright invite landing metadata passed. | Flutter/widget + browser | `passed` | Title, time, location, actions, and profile summaries are text/action widgets. |
| `SCOPE-06` | `Scope` | Ensure the event cover/poster image is decorative/emotional context only; no event title, date/time, venue, related profile, accept/decline, or details action may rely on reading pixels from the image. | widget + source audit | Invite flow/share tests plus source review of invite hero/card composition passed. | Flutter source + widget | `passed` | No critical event data is sourced from image pixels. |
| `SCOPE-07` | `Scope` | Ensure invite metadata lists/summarizes canonical related account profiles for the event, not only the venue profile. The venue remains location metadata and must not be duplicated as the only profile row unless it is explicitly the only canonical related profile. | unit + widget + runtime | `event_related_profile_groups_test.dart`, `invite_share_screen_test.dart`, and Playwright invite metadata passed. | Flutter/widget + browser | `passed` | Venue id is used for directions and exclusion, not profile summary source. |
| `SCOPE-08` | `Scope` | Preserve the event-related-profile fallback contract: events with newly grouped related profiles use group data; existing events without groups still surface legacy linked profiles instead of showing an empty or venue-only profile summary. | unit + widget | `event_related_profile_groups_test.dart` covers custom groups and legacy fallback; event detail tests passed. | Flutter tests | `passed` | Grouped and legacy event shapes are both covered. |
| `SCOPE-09` | `Scope` | Reuse or extract the event detail grouped-profile summarization logic used by `_buildDynamicProfileTabs` / `_groupLinkedProfilesByType` instead of creating a second invite-only grouping rule. | source audit + tests | `lib/application/schedule/event_related_profile_groups.dart` consumed by event detail and invite/share; focused suite passed. | Flutter source + tests | `passed` | The old local grouping rule is centralized in application layer. |
| `SCOPE-10` | `Scope` | Preserve invite behavior: close, view details, accept, decline, auth gate, route guards, and invite decision semantics must remain unchanged unless already governed elsewhere. | widget + runtime | Invite flow/share tests and source-owned Playwright specs `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` and `tools/flutter/web_app_tests/event_share_boundary.spec.js` passed through `tools/flutter/run_web_navigation_smoke.sh mutation`. | Flutter/widget + browser route guard | `passed` | Route guards and invite decision semantics remain unchanged. |
| `SCOPE-11` | `Scope` | Add/adjust canonical event time display values for public contexts so widgets consume model/projection values rather than assembling raw date/time strings. | unit + widget | `EventScheduleDisplay`, `VenueEventResume`, POI/card/invite tests passed. | Flutter tests | `passed` | Widgets consume model/projection labels. |
| `SCOPE-12` | `Scope` | Establish a start-focused flyer label for invite/public promotional contexts. Target language: `Qua, 10 jun · 8h` for events where start time is the important conversion signal. | unit + widget | Payload, invite, and projection tests passed. | Flutter tests | `passed` | `flyerLabel` is start-focused. |
| `SCOPE-13` | `Scope` | Preserve compact range labels for agenda/planning contexts where end time is useful. Target language may include the end time, for example `Qua, 10 jun · 8h às 10h`, but must still be model/projection-owned rather than widget-local. | unit + widget | Venue/event resume, agenda card, home card, map card, and live card tests passed. | Flutter tests | `passed` | Planning surfaces retain range-capable labels. |
| `SCOPE-14` | `Scope` | Treat end time as contextual: omit it by default in invite/promotional surfaces unless event semantics or an approved planning surface require duration/end-time comprehension. | unit + widget | Payload and invite tests use flyer label; agenda/planning tests use range/detail labels. | Flutter tests | `passed` | End time is not forced into promotional share copy. |
| `SCOPE-15` | `Scope` | Split canonical invitation payload from neutral public event share payload. | unit + widget + browser | Focused reopened suite; `fvm flutter analyze --no-pub`; canonical web build; Playwright mutation 27/27. | Flutter/widget/browser | `passed` | `buildInvitation` and `buildPublicShare` are explicit separate contracts; no remaining `EventInviteSharePayloadBuilder.build(...)` call sites in `lib`/targeted tests. |
| `SCOPE-16` | `Scope` | Keep grouped participant/profile lines in invitation copy using event tab group labels/order with legacy fallback. | unit + widget | Focused command above. | Flutter tests | `passed` | Invitation payload and invite share fallback assert grouped participant lines from canonical profile groups. |
| `SCOPE-17` | `Scope` | Authenticated/app share and WhatsApp produce `/invite?code=...`; anonymous web share and WhatsApp produce `/agenda/evento/...`. | widget + browser | Focused command above; `event_share_boundary.spec.js` captures anonymous web public share payload and zero share-code creation. | Flutter/widget/browser | `passed` | Authenticated immersive share and WhatsApp create share code and use `/invite?code=CODE123`; anonymous web share uses public route and creates no share code. |
| `SCOPE-18` | `Scope` | Invitation copy removes `Detalhes`, `Como chegar`, `/mapa`, and venue POI links; neutral public share uses `Ver evento:` only. | unit + widget + browser | Focused command above; Playwright mutation 27/27 after canonical web build. | Flutter/widget/browser | `passed` | Tests assert stale details/map labels are absent from invitation copy and public share uses `Ver evento:`. |
| `SCOPE-19` | `Scope` | Remove/avoid duplicate share subject/message assembly in call sites such as invite share screen fallback paths; call sites should use the canonical builder output. | source audit + tests | `EventInviteSharePayloadBuilder` call sites and focused suite passed. | Flutter source + tests | `passed` | System share, WhatsApp fallback, footer preview, and event share reuse one builder. |
| `SCOPE-SHARE-01-EXACT` | `Scope` | Split the canonical event invitation payload from the neutral public event share payload. Do not let one builder force invite language onto anonymous public shares or public event details/map links into invite messages. | source audit + unit/widget + browser | Focused reopened suite passed with 80 tests; `fvm flutter analyze --no-pub` passed; canonical web build passed; Playwright mutation passed 27/27. | Flutter/widget/browser | `passed` | Separate `buildInvitation` and `buildPublicShare` contracts are used by touched call sites. |
| `SCOPE-SHARE-02-EXACT` | `Scope` | Authenticated/app event share, WhatsApp share, and invite actions must create or reuse the canonical share-code invite link and send `/invite?code=...`. | widget | `immersive_event_detail_screen_test.dart` authenticated share and authenticated WhatsApp cases; `invite_share_screen_test.dart` invite share fallback. | Flutter/widget | `passed` | Tests assert share-code creation and `https://tenant.test/invite?code=CODE123` or `SHARE-CODE`. |
| `SCOPE-SHARE-03-EXACT` | `Scope` | Anonymous web event share and WhatsApp share may remain available, but must use the neutral public event URL `/agenda/evento/:slug?occurrence=...`. | unit/widget + browser | `event_invite_share_payload_test.dart`; anonymous web share widget test; `event_share_boundary.spec.js` captured `navigator.share` payload. | Flutter/widget/browser | `passed` | Browser payload contains `/agenda/evento/${routeRef}?occurrence=${occurrenceId}` and zero `/api/v1/invites/share` POSTs. |
| `SCOPE-SHARE-04-EXACT` | `Scope` | Invite copy must include grouped participant/profile lines using the same group labels/order as event tabs when `profileGroups` exists, with the legacy linked-profile-by-type fallback when groups are absent. | unit/widget | `event_invite_share_payload_test.dart`; `invite_share_screen_test.dart`; existing `event_related_profile_groups_test.dart` evidence in focused suite. | Flutter tests | `passed` | Payload and invite fallback assert grouped participant lines such as `Bandas: ...`; shared grouping helper preserves legacy fallback. |
| `SCOPE-SHARE-05-EXACT` | `Scope` | Invite copy must label the link as `Responder ao convite:` and must not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links. | unit/widget | `event_invite_share_payload_test.dart`; `invite_share_screen_test.dart`; `immersive_event_detail_screen_test.dart`. | Flutter tests | `passed` | Tests assert `Responder ao convite:` and absence of `Detalhes`, `Como chegar`, `/mapa`. |
| `SCOPE-SHARE-06-EXACT` | `Scope` | Public event share copy must be neutral, must not use inviter-specific language, and must label the public URL as `Ver evento:`. | unit/widget + browser | `event_invite_share_payload_test.dart`; anonymous web share widget test; `event_share_boundary.spec.js` browser capture. | Flutter/widget/browser | `passed` | Tests assert `Ver evento:` and absence of `Convite para`, `te convidou`, and `Responder ao convite:`. |
| `SCOPE-SHARE-07-EXACT` | `Scope` | Preserve direct invite composer semantics and guards: anonymous web users cannot create share codes and must keep the canonical app-promotion path for invite-only actions. | widget + browser/web Playwright | `immersive_event_detail_screen_test.dart` anonymous invite composer modal; source-owned Playwright `tools/flutter/web_app_tests/event_share_boundary.spec.js` via `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation` after canonical web build. | Flutter/widget + browser/web | `passed` | Anonymous web invite action opens contextual app-promotion modal and does not POST `/api/v1/invites/share`; domains served `__WEB_BUILD_SHA__=88227417`. |
| `SCOPE-SHARE-08-EXACT` | `Scope` | Remove/avoid duplicate subject/message assembly in call sites. Call sites should choose the canonical invitation payload or public share payload according to identity/intent and then share the resulting subject/message. | source audit + tests | `rg` found no remaining `EventInviteSharePayloadBuilder.build(...)` in `lib`/targeted tests; focused reopened suite and analyzer passed. | Flutter source + tests | `passed` | Call sites choose `buildInvitation` or `buildPublicShare` and share returned subject/message. |
| `SCOPE-20` | `Scope` | Replace event/account-profile hero cover upload usage with dedicated media slots/presets instead of one global `cover` slot. | source + tests | Event/account/profile admin call sites use dedicated slots; crop/ingestion/admin tests passed. | Flutter/admin tests | `passed` | Global `cover` no longer drives event/account hero uploads. |
| `SCOPE-21` | `Scope` | Event hero cover preset must target the recommended `5:7` ratio, with source guidance around `1800x2520` ideal and `1440x2016` minimum. | unit + widget | Ingestion/crop/event form tests passed. | Flutter/admin tests | `passed` | Slot spec sets aspect `5/7` and max `1800x2520`. |
| `SCOPE-22` | `Scope` | Account profile hero cover preset must target the recommended near-square portrait ratio `15:16`, with source guidance around `1800x1920` ideal and `1440x1536` minimum. | unit + widget | Ingestion/crop/account/profile tests passed. | Flutter/admin tests | `passed` | Slot spec sets aspect `15/16` and max `1800x1920`. |
| `SCOPE-23` | `Scope` | Tenant-admin crop/upload UI must show a safe-area/respiro overlay for each hero preset. | widget | `tenant_admin_image_crop_sheet_test.dart` passed. | Flutter/widget | `passed` | Hero slots expose and render safe-area guide. |
| `SCOPE-24` | `Scope` | Tenant-admin crop/upload UI must render translucent interface/respiro zones inside the active crop rectangle, using the crop widget overlay surface so the guide moves/resizes with the crop instead of floating over the modal viewport. | widget + crop package contract | `fvm flutter test --no-pub test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart`; `fvm flutter test --no-pub test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart`. | Flutter/widget | `passed` | Tests wait for `crop_your_image` ready state, assert `Crop.overlayBuilder` is present, verify guide keys for top/bottom/lateral/focus zones, and check guide geometry follows event `5:7` and account/profile `15:16` crop aspect ratios; non-hero slots have no guide. |
| `SCOPE-25` | `Scope` | Keep avatar, type visual, map filter, public web metadata, branding, and unrelated static asset/media slots unchanged unless a focused code dependency requires a mechanical enum update. | unit + source audit | Non-cover slot preservation tests passed; public web metadata slot remains `TenantAdminImageSlot.publicWebDefaultImage` with unchanged dimensions. | Flutter tests + public web metadata source audit | `passed` | Non-target slot ratios and MIME outputs remain unchanged. |
| `DOD-01-EXACT` | `Definition of Done` | `DOD-01` Invite screen presents a stronger event-invite aesthetic while preserving existing invite behavior. | widget + runtime | Invite flow/share tests and Playwright invite metadata passed. | Flutter/widget + browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-01A-EXACT` | `Definition of Done` | `DOD-01A` Invite screen remains fully readable and actionable when the cover image is busy, missing, low-contrast, or contains misleading embedded text; critical information is UI-rendered, not artwork-dependent. | widget + runtime | Invite tests and Playwright invite metadata passed. | Flutter/widget + browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-01B-EXACT` | `Definition of Done` | `DOD-01B` Invite screen event-profile metadata uses the canonical related account profiles/groups for the event and does not collapse to venue-only metadata when other linked profiles exist. | unit + widget + runtime | Grouping and invite tests plus Playwright passed. | Flutter/widget + browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-01C-EXACT` | `Definition of Done` | `DOD-01C` Invite presentation reuses or extracts existing event card/detail projection/component behavior; no second invite-only title/time/location/profile formatter is introduced where an event summary contract already exists. | source audit + tests + browser navigation | Shared grouping helper and projection tests passed; source-owned Playwright `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` passed through `tools/flutter/run_web_navigation_smoke.sh mutation` against `https://guarappari.belluga.space`. | Flutter source + tests + browser | `passed` | Runtime invite landing exercised the reused metadata path. |
| `DOD-02-EXACT` | `Definition of Done` | `DOD-02` Invite/promotional event surfaces use a start-focused flyer time label such as `Qua, 10 jun · 8h` instead of formal/system labels where end time is not important. | unit + widget | Payload/projection/invite tests passed. | Flutter tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-03-EXACT` | `Definition of Done` | `DOD-03` Agenda/planning surfaces can still use range labels such as `Qua, 10 jun · 8h às 10h` when end time matters, without widgets locally calculating final date/time language. | unit + widget | Agenda/planning card and projection tests passed. | Flutter tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-03A-EXACT` | `Definition of Done` | `DOD-03A` Event invitation messages use only the `/invite?code=...` link under `Responder ao convite:` and omit `Detalhes`, `Como chegar`, `/mapa`, and venue POI links. | unit + widget + browser | Focused reopened share/invite suite passed; `fvm flutter analyze --no-pub` passed; canonical web build passed; Playwright mutation passed 27/27. | Flutter/widget/browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-03B-EXACT` | `Definition of Done` | `DOD-03B` Neutral public event share messages use `Ver evento:` with `/agenda/evento/...` and no invite/inviter language. | source audit + tests + browser | Focused reopened share/invite suite passed; `fvm flutter analyze --no-pub` passed; canonical web build passed; Playwright mutation passed 27/27. | Flutter source + tests + browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-03C-EXACT` | `Definition of Done` | `DOD-03C` Event share call sites choose payload by identity and intent: authenticated/app users generate or reuse invite links; anonymous web users share public event URLs; WhatsApp follows the triggering action. | source audit + widget + browser | Focused reopened share/invite suite passed; `fvm flutter analyze --no-pub` passed; canonical web build passed; Playwright mutation passed 27/27. | Flutter source + tests + browser | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-SHARE-03A-FULL-EXACT` | `Definition of Done` | `DOD-03A` Event invitation messages use the canonical invitation payload, include natural flyer time, grouped participant/profile lines when available, and use only the `/invite?code=...` link under `Responder ao convite:`. | unit/widget + browser/web Playwright | `event_invite_share_payload_test.dart`; `invite_share_screen_test.dart`; `immersive_event_detail_screen_test.dart`; source-owned Playwright `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` and `tools/flutter/web_app_tests/event_share_boundary.spec.js` via `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation` after canonical web build. | Flutter tests + browser/web | `passed` | Tests assert flyer time, grouped participants, `Responder ao convite:`, and `/invite?code=...`; Playwright validates real invite/public boundaries on `https://guarappari.belluga.space` served with `__WEB_BUILD_SHA__=88227417`. |
| `DOD-SHARE-03B-FULL-EXACT` | `Definition of Done` | `DOD-03B` Neutral public event share messages use the canonical public share payload, include natural flyer time and the public event URL under `Ver evento:`, and do not use invite/inviter language. | unit/widget + browser | `event_invite_share_payload_test.dart`; anonymous web widget test; Playwright share payload capture. | Flutter/widget/browser | `passed` | Tests assert flyer time, public URL, `Ver evento:`, and no invite language. |
| `DOD-SHARE-03C-FULL-EXACT` | `Definition of Done` | `DOD-03C` Event share call sites choose payload by identity and intent: authenticated/app users generate or reuse invite links; anonymous web users share only the public event URL; WhatsApp follows the same rule as the triggering action. | widget + browser/web Playwright + build provenance | `immersive_event_detail_screen_test.dart`; source-owned Playwright `tools/flutter/web_app_tests/event_share_boundary.spec.js` via `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation`; build/publish proof `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`, `docker compose restart nginx`, `curl` confirmed `__WEB_BUILD_SHA__=88227417` on `https://belluga.space` and `https://guarappari.belluga.space`. | Flutter/widget + browser/web | `passed` | Authenticated widget paths generate invite links; anonymous web Playwright share uses public URL and creates no share code; WhatsApp channel inherits semantics in widget coverage. |
| `DOD-04-EXACT` | `Definition of Done` | `DOD-04` Event cover upload/crop uses the event hero preset ratio and shows a safe-area/respiro guide. | unit + widget | Ingestion/crop/event form tests passed. | Flutter/admin tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-05-EXACT` | `Definition of Done` | `DOD-05` Account profile cover upload/crop uses the account-profile hero preset ratio and shows a safe-area/respiro guide. | unit + widget | Ingestion/crop/account/profile tests passed. | Flutter/admin tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-06-EXACT` | `Definition of Done` | `DOD-06` The prior global `560/512` cover assumption is no longer used for event/account-profile hero cover uploads. | source + tests | Dedicated slot source usage and crop tests passed. | Flutter/admin tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-07-EXACT` | `Definition of Done` | `DOD-07` Existing non-target image slots remain unchanged. | unit | Non-target slot preservation tests passed. | Flutter tests | `passed` | Exact DOD evidence row for guard matching. |
| `DOD-08-EXACT` | `Definition of Done` | `DOD-08` Focused tests, analyzer, rule matrix, web build, and at least one source-owned browser/device navigation validation pass before any delivery claim. | command evidence | Focused 202-test suite passed; `fvm dart analyze --format machine` passed; `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` passed; canonical web build `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` passed; `docker compose restart nginx`; `https://belluga.space` and `https://guarappari.belluga.space` served `__WEB_BUILD_SHA__=88227417`; source-owned Playwright specs `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` and `tools/flutter/web_app_tests/event_share_boundary.spec.js` passed through `tools/flutter/run_web_navigation_smoke.sh mutation`; device validation not applicable because the criterion allows browser/device and browser is the selected runtime lane. | Flutter/web/browser | `passed` | Exact DOD evidence row for guard matching with browser Playwright and build provenance. |
| `VAL-01` | `Validation Steps` | Add fail-first or behavior-first Flutter tests for event time display variants: start-focused flyer/promotional label and range-capable agenda/planning label. | unit/widget | `venue_event_resume_test.dart`, `upcoming_event_card_test.dart`, `my_events_carousel_card_test.dart`, `event_live_now_card_test.dart` passed. | Flutter tests | `passed` | Covers flyer and planning labels. |
| `VAL-02` | `Validation Steps` | Add fail-first or behavior-first tests for invitation payload and neutral public share payload, including link-label split, identity/intent split, and absence of stale map/details links in invite copy. | unit/widget + browser navigation | Fail-first compile failure recorded before implementation: missing `buildInvitation`/`buildPublicShare`; focused reopened suite passed after implementation; `fvm flutter analyze --no-pub` passed; Playwright mutation passed 27/27 after canonical web build. | Flutter tests + browser | `passed` | Browser boundary captures anonymous public share payload and confirms zero share-code creation. |
| `VAL-SHARE-01-EXACT` | `Validation Steps` | Add fail-first or behavior-first tests for canonical invitation payload copy, including grouped participants, no participants, `Responder ao convite:`, `/invite?code=...`, and absence of `Detalhes`, `Como chegar`, `/mapa`, and venue POI links. | fail-first + unit/widget + browser/web Playwright | Fail-first compile failure recorded for missing new builder methods; `event_invite_share_payload_test.dart`, `invite_share_screen_test.dart`, and `immersive_event_detail_screen_test.dart` passed after implementation; source-owned Playwright `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` and `tools/flutter/web_app_tests/event_share_boundary.spec.js` ran through the project navigation runner after canonical web build. | Flutter tests + browser/web | `passed` | Tests cover grouped participants, stale map/details absence, `/invite?code=...`, and real-domain invite/share boundaries. |
| `VAL-SHARE-02-EXACT` | `Validation Steps` | Add fail-first or behavior-first tests for neutral public event share payload copy, including `Ver evento:`, `/agenda/evento/...`, and absence of invite/inviter language. | fail-first + unit/widget/browser | Fail-first compile failure recorded for missing `buildPublicShare`; unit/widget tests and Playwright share payload capture passed. | Flutter tests + browser | `passed` | Browser payload contains public event URL and no invitation language. |
| `VAL-SHARE-03-EXACT` | `Validation Steps` | Add widget/runtime tests for authenticated/app event share and WhatsApp generating/reusing invite links, plus anonymous web event share and WhatsApp using public event URLs without share-code creation. | widget + browser/web Playwright + build provenance | Authenticated share and WhatsApp widget tests passed; anonymous web share widget test passed; source-owned Playwright `tools/flutter/web_app_tests/event_share_boundary.spec.js` passed via `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation`; build proof `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`, `docker compose restart nginx`, and `curl` confirmed `__WEB_BUILD_SHA__=88227417` on both browser-facing domains. | Flutter/widget + browser/web | `passed` | WhatsApp channel inherits authenticated invitation semantics in widget coverage; anonymous web browser share uses public URL and creates no share code. |
| `VAL-03` | `Validation Steps` | Add widget tests for invite screen visual/time contract, deterministic readable content layer, related-account-profile summary, and preserved accept/decline/details behavior. | widget + browser navigation | `invite_flow_screen_test.dart` and `invite_share_screen_test.dart` passed in focused suite; source-owned Playwright invite landing metadata spec passed through `tools/flutter/run_web_navigation_smoke.sh mutation`. | Flutter/widget + browser | `passed` | Preserves invite behavior and validates runtime landing metadata. |
| `VAL-04` | `Validation Steps` | Add navigation/runtime validation using an event whose venue differs from multiple related account profiles, proving the invite screen shows/summarizes the related profiles and does not repeat venue as the only profile metadata. | browser/runtime | Source-owned Playwright invite metadata spec passed after refreshed bundle. | Browser mutation | `passed` | Runtime covered invite landing metadata. |
| `VAL-05` | `Validation Steps` | Add widget/unit tests for tenant-admin image crop ratio presets and safe-area guide rendering. | unit/widget | Ingestion and crop sheet tests passed. | Flutter/admin tests | `passed` | Covers `5:7`, `15:16`, and safe guide. |
| `VAL-06` | `Validation Steps` | Run focused Flutter suite for invite/time/media upload surfaces. | command | `fvm flutter test --no-pub ...` focused suite passed with 202 tests. | Flutter tests | `passed` | Initial stale hook cache was regenerated with `fvm flutter pub get`; rerun passed. |
| `VAL-07` | `Validation Steps` | Run `fvm dart analyze --format machine`. | command | `fvm dart analyze --format machine` passed with no output. | Flutter analyzer | `passed` | Analyzer violations fixed before final run. |
| `VAL-08` | `Validation Steps` | Run the Flutter rule matrix. | command | `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` completed with exit code 0. Output confirmed 58 lint codes detected and 59 total distinct codes emitted. | Flutter analyzer plugin | `passed` | Concrete rule matrix execution evidence. |
| `VAL-09` | `Validation Steps` | Build web with the project canonical script before browser validation. | command + browser provenance | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` passed before Playwright; `docker compose restart nginx`; browser-facing `https://belluga.space` and `https://guarappari.belluga.space` served `__WEB_BUILD_SHA__=88227417`; subsequent source-owned Playwright specs `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js` and `tools/flutter/web_app_tests/event_share_boundary.spec.js` passed through `tools/flutter/run_web_navigation_smoke.sh mutation`. | Flutter web build + browser Playwright | `passed` | Build provenance recorded before browser validation. |
| `VAL-10` | `Validation Steps` | Run source-owned Playwright/navigation validation against the refreshed local-public bundle for invite screen and at least one public event path. | browser/runtime | `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation` selected 27 tests and passed 27/27 in 15.9m. | Browser Playwright mutation | `passed` | Targeted `https://belluga.space` and `https://guarappari.belluga.space` after web build SHA `88227417`; `event_share_boundary.spec.js` captured anonymous web public share payload. |

## External Dependency Readiness
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| Stitch MCP / generated images | Optional visual exploration input for invite screen direction. | `healthy-after-retry` | `2026-06-02` | Created project `projects/13562287476921255961` and screen `144fcb4979bf409797d380b9b4ac63a6`; first invalid call was corrected. | Historical input only. No further image-generation work is required for the approved contract unless explicitly requested again. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `strategic-cto-tech-lead` for UX/media contract approval; `assurance-tester-quality` for evidence quality.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `strategic-cto-tech-lead` | `operational-coder` | UX/media/time decisions need user approval before implementation. | invite screen, time model/projection, tenant-admin media crop/upload | planned |
| `operational-coder` | `assurance-tester-quality` | Manual gaps were found by user, so automated/runtime evidence must be stricter. | tests, Playwright/device/browser validation | planned |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `consolidated planning review before APROVADO; status update after tests are written; status update after runtime validation`
- **Why this level:** crosses public invite UI, domain/projection display labels, tenant-admin media upload/crop widgets, and browser-visible validation, but it remains inside one event conversion/media presentation objective.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` media/tenant-public invite presentation contracts
  - `tenant_admin_module.md` admin media upload/crop guidance
- **Module decision consolidation targets (required):**
  - public invite/time language contract and hero-cover media slot/preset contract after implementation proves the shape.

## Decisions (Resolved Before Freeze)
- [x] `D-IFM-01` The screenshot surface is the invite screen, not the immersive event hero.
- [x] `D-IFM-02` The invite design direction should combine the readability/hierarchy of the Stitch `Editorial Poster` study with the clear date chip from `Ticket Pass`; do not copy a full glassmorphism-heavy redesign blindly.
- [x] `D-IFM-03` Public invite/promotional event labels are start-focused by default, using natural flyer-style language such as `Qua, 10 jun · 8h`. End time appears only in planning/agenda contexts or where the event semantics make duration important, using a centralized range label such as `Qua, 10 jun · 8h às 10h`.
- [x] `D-IFM-04` Event and account-profile hero covers need dedicated media presets/slots. A single global `cover` ratio is no longer the launch contract for those contexts.
- [x] `D-IFM-05` Crop/upload UI must expose safe-area/respiro guidance visually, not only in helper text.
- [x] `D-IFM-06` Existing non-target image slots preserve their current ratios unless a mechanical enum migration requires explicit test-covered handling.
- [x] `D-IFM-07` Invite critical information is UI-owned, not image-owned. Cover/poster artwork can support mood, but title, time, venue, related profile summary, and actions must remain readable when the artwork fails.
- [x] `D-IFM-08` Invite event-profile metadata must be sourced from canonical event related profiles/groups with legacy fallback, not inferred from venue-only data.
- [x] `D-IFM-09` Design exploration images are no longer an active workstream for this TODO; implementation should proceed from deterministic UI contract and runtime data behavior.
- [x] `D-IFM-10` Invite should not solve event summary display independently. Existing event card/screen flows are the authority for event title, schedule, venue, counterpart profile, and grouped-profile behavior unless implementation discovery proves a specific reusable extraction is needed.
- [x] `D-IFM-11` Superseded on 2026-06-03: event invite/share copy should not be one shared details/map contract. Grouped participants remain required for invitation copy, but `Como chegar`, venue POI, and separate details links are no longer part of the invite message.
- [x] `D-IFM-12` Superseded on 2026-06-03: venue POI links are not part of invitation copy. Public map navigation remains a UI/action concern outside this invite-share message contract unless explicitly approved later.
- [x] `D-IFM-13` The tenant-admin hero crop guide represents final composition, not a separate horizontal "safe area": translucent top/bottom interface bands and lateral respiro are rendered inside the crop rectangle through `crop_your_image`'s crop overlay surface, so the guide moves/resizes with the crop.
- [x] `D-IFM-14` Authenticated/app event share, WhatsApp share, and invite actions semantically mean invitation and must create or reuse a share-code invite link at `/invite?code=...`.
- [x] `D-IFM-15` Anonymous web event share and WhatsApp share remain allowed for acquisition, but they are neutral public shares using `/agenda/evento/:slug?occurrence=...`; anonymous web users cannot create share codes.
- [x] `D-IFM-16` Invitation copy uses `Responder ao convite:` for the link and must not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links.
- [x] `D-IFM-17` Public event share copy uses neutral `Ver evento:` language and must not say or imply that the sender invited the recipient.
- [x] `D-IFM-18` WhatsApp is a channel, not a semantic action. It inherits invite semantics for authenticated/app users and public-share semantics for anonymous web users.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `flutter_client_experience_module#Tenant-Public Desktop Web Mobile-Frame Contract` | Public web frame max width is `430` logical px for framed public routes. | Preserve | Module doc loaded. |
| `flutter_client_experience_module#Invite Web-to-App Conversion Contract` | Invite flows preserve route/auth/promotion boundaries. | Preserve | Module doc loaded. |
| `tenant_admin_module#Admin Form Validation Baseline` | Media validation groups bind `avatar`, `cover` to `media`. | Preserve | Module doc loaded. |
| `TODO-v0.2.0+8-cover-crop-560x512#D-CVR-03` | Shared `TenantAdminImageSlot.cover` was frozen as `560/512` for all cover consumers. | Supersede (Intentional) for event and account-profile hero cover uploads only | Current hero sizing analysis and user request. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-IFM-01` The implementation treats the target as the invite screen and does not spend this TODO redesigning immersive hero layout.
- [x] `D-IFM-02` Invite visual implementation uses an editorial poster card plus clear date-chip hierarchy and preserves accept/decline/details behavior.
- [x] `D-IFM-03` Event time model/projection exposes context labels: start-focused flyer/promotional label (`Qua, 10 jun · 8h`) and range-capable agenda/planning label (`Qua, 10 jun · 8h às 10h`), with widgets forbidden from assembling final date/time copy locally.
- [x] `D-IFM-04` Event hero upload/crop uses a dedicated `5:7` preset with safe guide.
- [x] `D-IFM-05` Account profile hero upload/crop uses a dedicated `15:16` preset with safe guide.
- [x] `D-IFM-06` `560/512` remains only for explicitly preserved legacy/static/shared cover uses, not for event/account-profile hero cover uploads.
- [x] `D-IFM-07` Invite screen renders title, flyer time, venue, related profile summary, details, accept, and decline as deterministic UI content over a readable layer regardless of cover state.
- [x] `D-IFM-08` Invite screen related-profile summary consumes the same canonical event related-profile grouping/fallback used by event detail tabs; venue is location metadata, not the profile-list source.
- [x] `D-IFM-09` `InviteEventHero` / invite flow no longer passes empty linked profiles when real event relationship data is available, and any new invite summary widget is extracted from or aligned with existing event summary components.
- [x] `D-IFM-10` Invitation and public-share payloads are separate canonical contracts. The invitation payload produces `Responder ao convite:` + `/invite?code=...`; the public share payload produces `Ver evento:` + `/agenda/evento/...`.
- [x] `D-IFM-11` Share call sites choose payload by identity and intent: authenticated/app users invite; anonymous web users share public event URLs; WhatsApp follows the same semantic as the triggering action.
- [x] `D-IFM-13` Hero crop guide is drawn inside the active crop rectangle as composition guidance, with translucent interface/respiro zones that move/resize with the crop.
- [x] `D-IFM-14` The previous map/details invitation-copy behavior is intentionally superseded and cannot be considered delivery evidence for the reopened share-link contract.

## Questions To Close
- [x] `Q-01` Confirm that the proposed consolidated direction is approved for implementation on the current v0.2.0+8 reconcile lane.

## Package-First Assessment
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search image`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search media`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search crop`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search invite`
- Query executed: `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search share`
- Relevant packages found:
  - none for image/media/crop/invite/share.
  - `[Ecosystem] event_tracker_handler` found for `event`, but it is analytics-only and not relevant to UI, crop, or invite presentation.
- READMEs read: `n/a` because no relevant proprietary package was found.
- Decision: local implementation in Flutter tenant-admin/shared image upload/crop surfaces and tenant-public invite/time surfaces.
- Tier: Local host implementation.
- Rationale: the behavior is project-specific presentation/media guidance tied to Bóora tenant-public and tenant-admin surfaces, not a reusable package boundary yet.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Event and account-profile hero image uploads are the only cover contexts that must change now. | User request refers to event/account hero sizing; static asset was not requested. | Static asset preset may need a separate approved follow-up. | High | Promote to Decision |
| `A-02` | Existing Flutter domain/projection time-label contract can be extended without backend API changes. | Current `EventScheduleDisplay` constructs labels from existing start/end values. | Backend contract would need separate approval. | High | Keep as Assumption |
| `A-03` | Safe-area guidance can be implemented in Flutter crop/upload UI without modifying server-side media storage. | Crop sheet and ingestion service already own ratio/resize behavior locally. | Backend media metadata changes would require split scope. | High | Keep as Assumption |
| `A-04` | Invite route payload currently has or can hydrate the same related-profile projection consumed by event detail tabs. | Event detail already renders grouped/legacy related profiles in tenant-public surfaces; current `InviteEventHero` manually constructs `VenueEventResume` with `linkedAccountProfiles: const []`. | Backend/API projection adjustment may be required and must stay inside this TODO only if it is a focused read-model fix. | Medium | Keep as Assumption |
| `A-05` | Venue POI links can be built from `EventModel.venue.id`, but they are not part of the invitation/public-share message contract after the 2026-06-03 decision. | Account profile detail already navigates to `/mapa?poi=account_profile:{accountProfile.id}` and `CityMapRoute` accepts `@QueryParam('poi')`; user rejected map/details links in invite share copy. | If a future product decision wants map links in share copy, it needs a new explicit scope because this TODO now removes them from invitation copy. | High | Promote to Decision |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `flutter-app/lib/domain/schedule/event_schedule_display.dart`
- `flutter-app/lib/domain/**/event*` projection/model getters that expose schedule labels
- `flutter-app/lib/application/sharing/event_invite_share_payload.dart`
- `flutter-app/lib/application/invites/invite_from_event_factory.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/widgets/**`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_event_hero.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_footer.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart`
- `flutter-app/lib/presentation/tenant_public/widgets/event_details.dart`
- `flutter-app/lib/presentation/tenant_public/widgets/upcoming_event_card.dart`
- `flutter-app/lib/domain/venue_event/projections/venue_event_resume.dart`
- `flutter-app/lib/presentation/tenant_public/events/**` related-profile projection/tab helpers, only to reuse canonical profile-summary/grouping behavior.
- `flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet.dart`
- `flutter-app/lib/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service.dart`
- `flutter-app/lib/presentation/tenant_admin/**` event/account-profile cover upload call sites
- focused Flutter tests under `flutter-app/test/**`
- source-owned Playwright specs under `tools/flutter/web_app_tests/**` if runtime validation needs new assertions

### Ordered Steps
1. Add/update tests for `EventScheduleDisplay` to define flyer/start-focused and planning/range labels, including concrete examples for same-day events (`Qua, 10 jun · 8h` and `Qua, 10 jun · 8h às 10h`) plus at least one edge case where the event crosses day boundaries or has no end time.
2. Update model/projection getters so invite/promotional and agenda/planning contexts consume distinct calculated labels.
3. Audit `InviteEventHero`, `EventDetails`, `UpcomingEventCard`, `VenueEventResume`, and event detail grouped-profile helpers to identify the smallest reusable event summary contract.
4. Reuse/extract that contract so invite metadata, invite share hero, event cards, and event detail stay aligned for title, schedule, venue, and counterpart profile summaries.
5. Split canonical payload generation into invitation and neutral public-share contracts. Invitation consumes flyer time, grouped participant/profile summaries, and `/invite?code=...`; neutral public share consumes flyer time and `/agenda/evento/...`.
6. Update invite share, external-contact WhatsApp fallback, and immersive event share call sites to choose the payload by identity and intent: authenticated/app users invite; anonymous web users share public event URLs; WhatsApp follows the triggering action.
7. Update invite card layout/time/profile consumption while preserving behavior tests for close/details/accept/decline/auth and ensuring deterministic readable UI content independent of image state.
8. Introduce explicit media slot/preset metadata for event hero cover and account-profile hero cover.
9. Update crop sheet and ingestion service to use preset ratio, max dimensions, and safe-area guide metadata.
10. Update tenant-admin event and account-profile create/edit upload call sites to use the dedicated presets.
11. Add widget/unit coverage for ratios, safe-area guide rendering, related profile summary, image-independent readability, share copy, reuse/no-duplication, and unchanged non-target slots.
12. Build web and run runtime navigation validation against the refreshed local-public bundle.

### Test Strategy
- **Strategy:** `test-first` where assertions are concrete; `test-after` only for visual-polish deltas that require implementation before exact layout assertions.
- **Why:** user manually found prior gaps; this delivery must define failing behavior before implementation where practical.
- **Fail-first target(s):**
  - `EventScheduleDisplay` flyer label returns start-focused copy.
  - `EventScheduleDisplay` planning label keeps end time only in range/planning contexts and handles missing end time without inventing duration.
  - Canonical invitation payload uses flyer time, grouped participant/profile lines, `Responder ao convite:`, and `/invite?code=...`.
  - Canonical invitation payload omits `Detalhes`, `Como chegar`, `/mapa`, and venue POI links even when venue id exists.
  - Canonical neutral public event share payload uses flyer time, neutral copy, `Ver evento:`, and `/agenda/evento/...`.
  - Invite share footer/system share/WhatsApp external fallback and immersive event share choose invitation or public-share payload by identity and intent.
  - Invite screen renders event title/time/actions from UI fields when cover image is missing/busy and does not use image text as source of truth.
  - Invite screen with venue A and related profiles B/C/D shows/summarizes B/C/D via canonical related-profile data instead of repeating venue-only metadata.
  - `InviteEventHero` / invite summary path receives real linked profiles or a hydrated event summary projection instead of constructing `VenueEventResume` with `linkedAccountProfiles: const []`.
  - Source audit prevents a duplicate invite-only event counterpart formatter when `VenueEventResume.counterpartProfiles` / shared extracted helper can cover the behavior.
  - Event/account cover presets do not equal `560/512`.
  - Crop sheet renders a safe-area guide for hero presets.
  - Invite card consumes the flyer label and keeps accept/decline/details affordances.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Invite screen aesthetic/time label | visible public conversion UI | shared-android-web | Playwright mutation + widget | no | yes | `invite_share_screen_test.dart`; `invite_flow_screen_test.dart`; `INVITE-SESSION-CONTEXT invite landing exposes dynamic share metadata` passed after web build | n/a |
| Invite related-profile summary | visible public conversion metadata; avoids venue-only incorrect profile listing | shared-android-web | Playwright mutation + widget | no | yes | `event_related_profile_groups_test.dart`; `invite_share_screen_test.dart`; Playwright invite metadata spec passed | n/a |
| Invite image-independent readability | visible public conversion UI; cover may be busy/missing | shared-android-web | widget + Playwright | no | yes or fixture | Focused invite widget tests plus Playwright invite landing metadata spec prove UI-owned event data/actions | n/a |
| Event invitation copy | external conversion text; authenticated/app users must send a materializable invite link without stale details/map exits | shared-android-web | unit/widget + Playwright mutation | yes | yes | planned: payload tests, invite share tests, immersive event share tests, Playwright share boundary asserting `/invite?code=...` and absence of `Detalhes`/`Como chegar`/`/mapa` | n/a |
| Anonymous public event share copy | acquisition flow; anonymous web users can share public event pages without creating invite codes | web-only | Playwright mutation + widget | no | yes | planned: anonymous web event share/WhatsApp navigation proving `/agenda/evento/...`, neutral copy, and zero share-code creation | n/a |
| Event/account cover crop upload | admin visible media mutation/readback setup | shared-android-web | widget/unit/admin form tests | yes | fixture-backed | `tenant_admin_image_ingestion_service_test.dart`; `tenant_admin_image_crop_sheet_test.dart`; admin event/account/profile form tests passed | Browser file-picker mutation not needed after widget/admin form coverage for ratio/crop-safe guide. |
| Time display centralization | domain/projection consumed by multiple public surfaces | shared-android-web | widget/unit + Playwright | no | yes | Time label unit/widget suite plus Playwright invite/event specs passed on refreshed bundle | n/a |
| Non-target slot preservation | structure/regression | n/a | n/a | no | no | `tenant_admin_image_ingestion_service_test.dart` non-cover slot preservation matrix passed | no user-visible changed flow for untouched slots. |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / focused invite-time-share-media suite` | Touches domain labels, canonical share copy, invite UI, tenant-admin image crop/upload. | `fvm flutter test --no-pub test/application/sharing/event_invite_share_payload_test.dart test/domain/schedule/event_related_profile_groups_test.dart test/domain/venue_event/projections/venue_event_resume_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/widgets/upcoming_event_card_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/my_events_carousel_card_test.dart test/presentation/tenant/map/screens/map_screen/widgets/event_poi_detail_card_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_content_resolver_test.dart test/presentation/tenant_public/widgets/event_live_now_card_test.dart test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart test/presentation/tenant_admin/events/tenant_admin_event_form_screen_test.dart test/presentation/tenant_admin/accounts/tenant_admin_account_create_screen_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_profile_create_screen_test.dart test/presentation/tenant_admin/screens/tenant_admin_account_profile_edit_screen_test.dart` | Local-Implemented | passed | Passed with 202 tests on 2026-06-02 after `fvm flutter pub get` regenerated stale native hook cache. | Initial run was harness-blocked by `Invalid SDK hash`; rerun after pub get passed with no lock diff. |
| `flutter-app / reopened invite-public-share payload suite` | Reopened 2026-06-03 link contract changes invitation vs public-share semantics and invalidates the previous details/map copy evidence. | `fvm flutter test --no-pub test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | Local-Implemented | passed | Focused reopened suite passed with 80 tests; fail-first run failed before implementation because `EventInviteSharePayloadBuilder.buildInvitation` and `buildPublicShare` did not exist; `fvm flutter analyze --no-pub` passed with no issues. | Asserts `Responder ao convite:` + `/invite?code=...` for authenticated/app invite shares, `Ver evento:` + `/agenda/evento/...` for anonymous web public shares, authenticated WhatsApp invite semantics, and no `Detalhes`/`Como chegar`/`/mapa` in invitation messages. |
| `web navigation / reopened invite-public-share boundary` | Browser-visible share behavior diverges by identity; user specifically wants no manual gaps. | `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation` after canonical web build. | Local-Implemented | passed | Playwright mutation selected 27 tests and passed 27/27 in 15.9m against refreshed `https://guarappari.belluga.space`; `event_share_boundary.spec.js` captured anonymous web public share payload and zero share-code creation. | Authenticated/app-equivalent invitation share code path is covered by focused widget tests; browser lane covers anonymous web public share path and invite guard boundaries. |
| `flutter-app / post-clarification crop guide suite` | User clarified the translucent areas must be part of the crop itself across all crop scenarios. | `fvm flutter test --no-pub test/presentation/tenant_admin/shared/utils/tenant_admin_image_ingestion_service_test.dart test/presentation/tenant_admin/shared/widgets/tenant_admin_image_crop_sheet_test.dart` | Local-Implemented | passed | Passed with 28 tests on 2026-06-02 after updating the crop guide overlay. | `tenant_admin_image_crop_sheet_test.dart` now verifies event `5:7`, account/profile `15:16`, all non-hero crop slots, guide keys for top/bottom/lateral/focus zones, and guide aspect ratio inside `crop_your_image` overlay. |
| `flutter-app / analyzer` | Flutter/domain/presentation changes. | `cd flutter-app && fvm dart analyze --format machine` | Local-Implemented | passed | Passed with no output after moving grouped-profile summary to `application/schedule` and encapsulating invite venue id as value object. | Full-app analyze is canonical. |
| `flutter rule matrix` | Architecture-rule coverage. | `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | Local-Implemented | passed | Command completed with exit code 0 on 2026-06-02. Output confirmed 58 lint codes detected and 59 total distinct codes emitted. | Confirms analyzer plugin matrix remains calibrated. |
| `flutter web build` | Public invite/event and web-admin runtime visible changes. | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | Local-Implemented | passed | Passed from `flutter-app`; bundle available at `../web-app`; `docker compose restart nginx`; `https://belluga.space` and `https://guarappari.belluga.space` served `__WEB_BUILD_SHA__=88227417`. | This is the canonical project wrapper, not raw `flutter build web`. |
| `web navigation` | Browser-visible invite/time/admin behavior. | `NAV_DEPLOY_LANE=dev NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_WORKERS=1 NAV_LANDLORD_URL=https://belluga.space NAV_TENANT_URL=https://guarappari.belluga.space tools/flutter/run_web_navigation_smoke.sh mutation` after canonical web build. | Local-Implemented | passed | Source-owned Playwright mutation selected 27 tests and passed 27/27 in 15.9m against refreshed local-public bundle. | Covered invite landing dynamic metadata, event share boundary, anonymous public share payload, and adjacent mutation lane flows. |

### Runtime / Rollout Notes
- No database migration expected.
- Existing uploaded media is not recropped automatically.
- Operators must recrop/upload new media to benefit from the new preset and safe-area guide.

## Plan Review Gate
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** high
  - **Evidence:** `TODO-v0.2.0+8-cover-crop-560x512.md#D-CVR-03` applied one global cover slot.
  - **Why it matters now:** event and account-profile heroes have different real viewport ratios; one shared cover ratio guarantees bad crop guidance.
  - **Option A (Recommended):** introduce explicit media presets/slots per hero context.
    - **Effort:** medium
    - **Risk:** medium
    - **Blast radius:** module
    - **Maintenance burden:** low
    - **Performance impact:** neutral
    - **Elegance impact:** improves
    - **Structural soundness impact:** improves
  - **Option B (Alternative):** keep global cover ratio and rely on operator judgment.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** user-visible
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Option C (Do Nothing):** keep `560/512`.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** user-visible
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Recommendation:** Option A.

### Failure Modes & Edge Cases
- [x] Busy posters may remain unreadable if upload UI does not show safe areas.
- [x] Operators may upload existing event posters that cannot fit the new hero ratio without manual composition.
- [x] Time labels may become too short for agenda planning if one label is reused everywhere.
- [x] Invite visual polish may accidentally weaken accept/decline auth/decision behavior if tests do not cover actions.

### Residual Unknowns / Risks
- [ ] Exact admin runtime upload validation may require manual evidence if Playwright cannot operate file picker/auth reliably in the current harness.

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md` | This is a tactical TODO that must not implement before approval. | Approval, frozen decisions, evidence, delivery guards. | Code edits before `APROVADO`. | Run authority guard after approval. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Flutter domain, presentation, and widget surfaces are in scope. | Controller/domain boundaries and analyzer rule matrix. | Widget-owned business/state shortcuts. | Use model/projection labels; keep widgets consumers. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | Prior manual validation found gaps. | Behavior-first tests and runtime evidence. | Source-scan-only validation. | Add unit/widget/browser evidence. |
| `/home/elton/Dev/repos/delphi-ai/skills/package-first-verification/SKILL.md` | New/changed reusable media logic might belong to packages. | Query package registry before implementation. | Duplicating existing package. | No relevant package found; local implementation planned. |
| `/home/elton/Dev/repos/delphi-ai/skills/stitch-mcp-design-workflow/SKILL.md` | User requested visual studies. | Isolated exploration project and report project/screen IDs. | Mutating main design project blindly. | Stitch study is directional only. |

## Approval
- **Status:** `approved`
- **Approved by:** user
- **Approved at:** `2026-06-02 21:25:05 -03`
- **Approval evidence:** chat phrase: `Gostei. Pode seguir.`
- **Approval scope:** Implement the v0.2.0+8 invite/time/share/media preset contract on the current reconcile lane: reuse/extract existing event card/detail summary behavior; render invite critical data through deterministic UI; introduce centralized flyer/planning schedule labels; revise canonical event invite/share copy with grouped participants and optional venue POI `Como chegar`; update event/account-profile cover crop presets and safe-area guidance; add focused unit/widget/runtime evidence. Exclusions remain: no new route/scope, no invite/auth semantic changes, no broad backend/API/storage change beyond focused read-model hydration needed for existing event summary data, and no static asset hero ratio change.
- **Renewed approval required if:** backend/API/storage changes beyond focused read-model hydration, invite/auth semantics change, new route/scope is introduced, static asset hero ratio becomes in-scope, or implementation deviates from event/account-profile hero media presets plus invite/time/share presentation.

## Reopened Approval Gate
- **Status:** `approved`
- **Direction approval evidence:** chat agreement on 2026-06-03: authenticated/app users invite with `/invite?code=...`; anonymous web users can share public event URLs; WhatsApp inherits the current semantic.
- **Implementation approval evidence:** user approval on 2026-06-03: `OK. Então pode seguir com o TODO que alinhamos, sobre share/invite.`
- **Reopened approval scope to request:** split invitation and neutral public-share payloads; remove `Detalhes`, `Como chegar`, `/mapa`, and venue POI links from invitation messages; use `Responder ao convite:` for `/invite?code=...`; use `Ver evento:` for anonymous public event share; update share/WhatsApp call sites, tests, canonical web build, and Playwright runtime evidence on the current v0.2.0+8 reconcile lane.

## Implementation Evidence Summary
- Added canonical event-related-profile grouping under `flutter-app/lib/application/schedule/`, shared by event detail tabs and invite/share copy.
- Extended invite event data with linked account profiles, profile groups, and venue account profile id as a domain value object.
- Centralized event schedule labels so invite/promotional contexts use `flyerLabel` while agenda/planning contexts keep range-capable labels.
- Historical before 2026-06-03 reopening: updated `EventInviteSharePayloadBuilder` and call sites so share and invite used the same builder; invite added inviter context, and both could include grouped participants and `Como chegar`. This evidence is superseded for invitation/public-share link semantics and must be replaced before promotion.
- Added dedicated tenant-admin media slots for event hero cover (`5:7`) and account/profile hero cover (`15:16`) with crop-sheet safe-area guide.
- Refined the hero crop guide so it renders through `crop_your_image.overlayBuilder` inside the active crop rectangle, with translucent top/bottom interface bands, lateral respiro zones, and focus guidance that move/resize with the crop.
- Build/runtime evidence used the canonical web wrapper and refreshed local-public domains with `__WEB_BUILD_SHA__=88227417`.

## Decision Adherence Validation
| Decision ID | Decision | Status | Evidence / Notes |
| --- | --- | --- | --- |
| `D-IFM-01` | Target is invite screen, not immersive hero redesign. | `adherent` | Invite flow/share screen tests and Playwright invite metadata validate invite surface; immersive changes are limited to shared event share/grouping consumption. |
| `D-IFM-02` | Invite visual hierarchy follows approved readable invite direction. | `adherent` | Invite critical data remains UI-rendered; widget/runtime evidence exercises route behavior. |
| `D-IFM-03` | Distinct flyer/promotional and agenda/planning schedule labels. | `adherent` | `EventScheduleDisplay`, `VenueEventResume`, cards, POI resolver, invite, and event detail tests passed. |
| `D-IFM-04` | Event hero upload/crop uses dedicated `5:7` preset. | `adherent` | `TenantAdminImageSlot.eventHeroCover`; ingestion/crop/admin event form tests passed. |
| `D-IFM-05` | Account/profile hero upload/crop uses dedicated `15:16` preset. | `adherent` | `TenantAdminImageSlot.accountProfileHeroCover`; ingestion/crop/admin account/profile tests passed. |
| `D-IFM-06` | `560/512` remains only for preserved legacy/static/shared cover uses. | `adherent` | Non-cover/legacy preservation tests passed; event/account profile call sites use dedicated slots. |
| `D-IFM-07` | Invite critical information is UI-owned, not image-owned. | `adherent` | Invite widget/runtime evidence validates title/time/location/actions independent of cover image text. |
| `D-IFM-08` | Invite related-profile summary uses canonical groups/fallback and not venue-only source. | `adherent` | `EventRelatedProfileGroups` helper, invite model hydration, grouping tests, and invite metadata runtime passed. |
| `D-IFM-09` | Invite summary path receives real linked profiles/profile groups. | `adherent` | `InviteFromEventFactory` and `InviteEventHero` now preserve linked profile/group data; focused tests passed. |
| `D-IFM-10` | Invitation and public-share payloads are separate canonical contracts. | `passed` | `EventInviteSharePayloadBuilder.buildInvitation` and `buildPublicShare` are separate entry points; focused reopened suite, analyzer, canonical web build, and Playwright mutation 27/27 passed. |
| `D-IFM-11` | Share call sites choose payload by identity and intent. | `passed` | Authenticated immersive share/WhatsApp create `/invite?code=...`; anonymous web share uses `/agenda/evento/...` without share-code creation; browser runtime captures the public share payload. |
| `D-IFM-14` | Previous map/details invitation-copy behavior is intentionally superseded. | `adherent` | TODO contract updated to remove the old behavior from current delivery evidence. |
| `D-IFM-13` | Hero crop guide zones live inside the active crop rectangle and move/resize with the crop. | `adherent` | `tenant_admin_image_crop_sheet_test.dart` waits for the crop editor ready state and verifies the guide appears through `Crop.overlayBuilder`, with event/account aspect-ratio geometry and no guide on non-hero slots. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Invite/time/share/media preset diff + CI-equivalent suite | CI/Copilot priority blocker regressions: stale web bundle, analyzer architecture violation, duplicate share message assembly, venue-only profile metadata, unsafe crop ratio regression, or missing browser evidence. | `passed` | Focused 202-test suite; `fvm dart analyze --format machine`; rule matrix; canonical web build; source-owned Playwright mutation; diff hygiene; rule-spirit scan. | No P1/P2 found. Initial analyzer violations were fixed before delivery evidence. | Architecture fix moved grouping summary to `application/schedule`, converted invite venue id to value object, and split public media spec classes. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Flutter architecture + invite/share/media centralization | Parallel invite/share formatters, domain primitive leakage, duplicate grouped-profile logic, widget-owned schedule formatting, unsafe media preset reuse, weak browser evidence. | `passed` | `bash delphi-ai/tools/rule_spirit_anti_pattern_scan.sh --repo . --stack flutter --json-output foundation_documentation/artifacts/tmp/invite-flyer-time-hero-cover-media-presets/rule-spirit-flutter.json --path ...` | Findings `0`; max active severity `none`. | No bypass found in touched production paths. |

## Deterministic Guard Evidence
| Guard | Command | Status | Evidence / Notes |
| --- | --- | --- | --- |
| Authority guard | `python3 delphi-ai/tools/todo_authority_guard.py foundation_documentation/todos/promotion_lane/v0.2.0+8/TODO-v0.2.0+8-invite-flyer-time-and-hero-cover-media-presets.md` | `passed` | Reran after evidence update: `Overall outcome: go`. |
| Completion guard | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/promotion_lane/v0.2.0+8/TODO-v0.2.0+8-invite-flyer-time-and-hero-cover-media-presets.md` | `passed` | Reran after exact evidence rows: `Overall outcome: go`. |
| Closeout guard | `python3 delphi-ai/tools/todo_closeout_guard.py foundation_documentation/todos/promotion_lane/v0.2.0+8/TODO-v0.2.0+8-invite-flyer-time-and-hero-cover-media-presets.md --repo .` | `passed` | Reran after disposition update: `Overall outcome: go`. |

## Manual Validation Matrix
| ID | Surface | Steps | Expected Result |
| --- | --- | --- | --- |
| `MAN-IFM-01` | Public invite | Open an invite link for an event with a venue and linked profiles/groups. | Title, flyer time, venue, participants, details, accept, decline, and share actions render as UI content, not dependent on cover artwork. |
| `MAN-IFM-02` | Invite share | Trigger share/WhatsApp from invite route as authenticated/app user. | Message uses inviter context when available, natural flyer time, grouped participants, `Responder ao convite:`, and `/invite?code=...`; it does not include `Detalhes`, `Como chegar`, `/mapa`, or venue POI links. |
| `MAN-IFM-03` | Anonymous public event share | Open a public event as anonymous web user and use share/WhatsApp. | Message is neutral, uses `Ver evento:`, includes `/agenda/evento/:slug?occurrence=...`, does not create a share code, and does not say the sender invited the recipient. |
| `MAN-IFM-03A` | Authenticated public event share | Open a public event as authenticated/app user and use share/WhatsApp. | Message follows invitation semantics and sends `/invite?code=...`, not the public event URL. |
| `MAN-IFM-04` | Event detail tabs | Open an event with custom profile groups and legacy linked profiles. | Custom groups render by configured group labels/order; legacy events fallback by type label instead of empty/venue-only tabs. |
| `MAN-IFM-05` | Tenant admin event cover | Create/edit an event and upload cover. | Crop sheet uses event hero ratio `5:7`; top/bottom translucent interface bands, lateral respiro, and focus label are inside the crop and follow crop movement/resizing. |
| `MAN-IFM-06` | Tenant admin account/profile cover | Create/edit account/profile cover. | Crop sheet uses account/profile hero ratio `15:16`; top/bottom translucent interface bands, lateral respiro, and focus label are inside the crop and follow crop movement/resizing. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** the 2026-06-03 invite/public-share link contract is implemented and validated on the current v0.2.0+8 reconcile lane, and the current package-wide mimic loop has not reopened this scope; this TODO should promote with the same package instead of creating a parallel version.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Post-commit/push evidence:** focused reopened suite passed with 80 tests, analyzer passed, canonical web build passed, both local-public domains served `__WEB_BUILD_SHA__=88227417`, Playwright mutation passed 27/27 in 15.9m, and authority guard passed.
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and promote through the declared lane path.
