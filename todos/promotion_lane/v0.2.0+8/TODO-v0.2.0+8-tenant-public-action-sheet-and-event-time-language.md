# Tenant Public Action Surface and Event Time Language

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The v0.2.0+8 tenant-public package already centralized immersive hero actions and route-scoped detail controllers, but follow-up validation exposed three connected UX/architecture gaps:

- App-promotion/favorite gates and guarded event actions still need one canonical tenant-public action surface family, but event `Convidar` must not introduce a separate first-touch invite modal. The final corrected direction is direct invite-flow boundary handling: authorized users enter the invite composer route, while anonymous web users see the contextual app-promotion surface.
- Expanded immersive hero action buttons render too far from the top because the shared `ImmersiveDetailScreen` positions the expanded action rail below the collapsed toolbar band instead of aligning it with the top action plane.
- Event/occurrence date-time copy is not human-friendly across multiple surfaces, not only the immersive hero. Examples such as `QUI, 11 • 12:01 às QUI, 11 • 14:01` repeat date context unnecessarily and are hard to scan in hero, agenda cards, profile/event cards, POI/map cards, and invite/share surfaces. The correct architecture is for the event/occurrence model or public projection to expose canonical human-ready calculated display values; widgets consume those values instead of calculating local strings.

This TODO complements `TODO-v0.2.0+8-immersive-hero-actions-centralization.md`; it does not create a parallel promotion version. It lands on the current v0.2.0+8 reconcile lane and must promote with the rest of the approved TODO package.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `tenant-public-action-surface-and-event-time-language`
- **Parent / related TODOs:**
  - `TODO-v0.2.0+8-immersive-hero-actions-centralization.md`
  - `TODO-v0.2.0+8-route-scoped-detail-controller-resolution.md`
  - `TODO-v0.2.0+8-web-favorite-promotion-gate-canonicalization.md`
- **Audit source:** `foundation_documentation/artifacts/ux-audits/canonical-event-action-surface/final-proposal.md`
- **Why this is the right current slice:** these are user-visible tenant-public interaction and readability corrections sharing the same presentation contract: canonical action surfaces, shared immersive action placement, and shared event time language.
- **Direct-to-TODO rationale:** the user requested disciplined TODO-driven execution after triple audit, and the scope now crosses multiple Flutter public UI consumers.

## Contract Boundary
- This TODO owns tenant-public action surface canonicalization, expanded immersive action rail top alignment, and human-readable event/occurrence time labels across public event consumers. The time-language source of truth is the event/occurrence model/projection calculated value, not individual widgets.
- It must preserve existing route guards, app-promotion mandate, invite composer behavior, route-scoped controller resolution, and v0.2.0+8 promotion lane.
- It must not implement before explicit `APROVADO`, frozen decision baseline, rule ingestion, and `todo_authority_guard.py` returning `Overall outcome: go`.

## Delivery Status Canon
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Feature`, `Flutter`, `Tenant-Public`, `UX`, `User-Visible`, `Requires-APROVADO`, `Cross-Surface-Time-Language`
- **Next exact step:** carry this TODO from `promotion_lane/v0.2.0+8/` through authorized lane follow-through; local implementation is complete and the current package-wide mimic loop has not reopened this scope.
- **Promotion lane path:** current v0.2.0+8 package lane, no parallel version.

## Scope
- [x] Remove the generic event-invite action sheet surface and establish `AppPromotionModal` as the canonical app-promotion/action-gate modal surface.
- [x] Route event `Convidar` directly to the canonical invite-flow boundary: authorized users push `InviteShareRoute`; anonymous web users open the contextual `AppPromotionModal`; no first-touch invite sheet is rendered.
- [x] Keep event `Compartilhar` and direct `WhatsApp` as immediate single-intent share actions; they must not open the canonical action surface first.
- [x] Migrate app-promotion/favorite web gate UI to the same canonical action surface family while preserving `AppPromotionScreenController` and `AppPublicationSettings` as the source of truth for store targets.
- [x] Preserve the web mandate: anonymous web gates promote the app, do not show phone-login UI, and do not auto-open app/store before explicit CTA.
- [x] Align expanded immersive hero action buttons to the shared top action plane, visually aligned with the back button/top controls, instead of rendering below the collapsed toolbar band.
- [x] Keep collapsed immersive action behavior intact: primary action plus secondary `more` menu.
- [x] Expose canonical human-ready calculated time display values from the event/occurrence public model or projection.
- [x] Keep any lower-level formatter/helper internal to model/projection construction; widgets must not call it to assemble final event time labels.
- [x] Replace ad hoc event/occurrence time strings across public surfaces found by source audit, including immersive event hero, agenda/upcoming cards, live-now cards, my-events/event carousel cards, map/POI event detail cards, linked event/profile cards when applicable, and invite/share presentation surfaces.
- [x] Use context-aware labels: avoid repeating weekday/date when start and end are on the same day; include both dates only for cross-day ranges; render whole hours as `20h` and nonzero minutes as `20h30` / `12h01`.
- [x] Preserve existing invite/share payload semantics while improving human readability from canonical calculated model/projection values where applicable.
- [x] Add deterministic tests and runtime navigation validation proving the behavior in real UI, not only by code inspection.

## Out of Scope
- [ ] Backend invite API, share-code lifecycle, invite recipient eligibility, or event occurrence identity changes.
- [ ] New auth policy or route guard semantics.
- [ ] Replacing the full invite composer route; `/convites/compartilhar` remains the advanced invite flow.
- [ ] Tenant-admin date/time formatting unless a public consumer depends on the same model/projection display contract.
- [ ] Redesigning the entire immersive hero layout beyond action rail top alignment.
- [ ] Static Asset favorite semantics.
- [ ] Any new promotion lane, branch, or version parallel to the current v0.2.0+8 package.

## Definition of Done
- [x] `DOD-01` Event `Convidar` does not open a first-touch invite sheet; authorized users push `InviteShareRoute` directly, and anonymous web users open the contextual app-promotion modal without creating share codes.
- [x] `DOD-02` Event `Compartilhar` invokes the share launcher directly and does not open the canonical action sheet.
- [x] `DOD-03` Event `WhatsApp` invokes the WhatsApp/native-fallback launcher directly and does not open the canonical action sheet.
- [x] `DOD-04` App-promotion/favorite web gate renders through the canonical action surface family and still respects store-target settings.
- [x] `DOD-05` Anonymous web favorite/action gates never render phone-login UI and never auto-open app/store before an explicit CTA.
- [x] `DOD-06` Expanded immersive hero action rail aligns with the top controls in the expanded state; collapsed state still uses the primary-plus-more AppBar contract.
- [x] `DOD-07` Event/occurrence public models or projections expose canonical human-ready calculated time display values.
- [x] `DOD-08` Same-day ranges do not repeat the date/weekday for the end time.
- [x] `DOD-09` Cross-day ranges include both date contexts in a readable format.
- [x] `DOD-10` Whole-hour and nonzero-minute formatting are both covered.
- [x] `DOD-11` Agenda, immersive hero, event cards, map/POI cards, and invite/share surfaces covered by the source audit render the model/projection display values instead of local calculations.
- [x] `DOD-12` Focused Flutter tests, analyzer, rule matrix, web build, and source-owned Playwright/runtime validation pass before delivery claim.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-TPA-01` `AppPromotionModal` is the canonical app-promotion/action-gate modal; the former generic action-sheet surface is removed so the rejected event invite sheet cannot be reconstructed through a shared shell.
- [x] `D-TPA-02` Shared presentation owns only the shell/anatomy/spacing/semantic keys; feature modules own promotion, invite, share, WhatsApp, and store-target content/actions.
- [x] `D-TPA-03` `Convidar` enters the invite-flow boundary directly because the first-touch invite sheet was rejected as a parallel modal; `Compartilhar` and `WhatsApp` remain immediate direct actions.
- [x] `D-TPA-04` The full invite composer remains the route for recipient hydration, sent status, contacts, group management, share-code generation, and invite mutations.
- [x] `D-TPA-05` Web anonymous promotion uses the existing app-promotion controller/settings and the canonical surface shell; it must not duplicate phone-login code.
- [x] `D-TPA-06` Expanded hero action rail placement is owned by `ImmersiveDetailScreen`, not event/account/static consumers.
- [x] `D-TPA-07` Tenant-public event/occurrence time display is model/projection-owned, centralized, and context-aware; public widgets must not build final labels from raw local `DateFormat` fragments.
- [x] `D-TPA-08` Agenda-card labels may be shorter than hero labels when the agenda section header already provides the date context.
- [x] `D-TPA-09` If implementation needs a reusable formatter/helper, it is an internal collaborator used to construct calculated model/projection fields; it is not the public widget contract.

## Behavior Contract
### Canonical Action Surface
- `AppPromotionModal` has stable semantic/test keys for title, body, close control, and platform/store actions.
- Mobile/native and mobile-framed web use the modal bottom presentation owned by `AppPromotionModal`.
- Wide desktop web may later use a centered/inset dialog if implemented inside the same modal contract, but no generic event-invite sheet shell remains.
- Event invite must not render a first-touch action sheet. It must reuse the canonical invite-flow/auth boundary directly and must not hydrate inviteable recipients, sent statuses, phone contacts, contact refresh, or create share codes before the authorized composer route is reached.

### Expanded Hero Action Rail
- Expanded rail buttons align to the same top action plane as the back/top controls.
- The rail must remain inside the visible hero safe area and must not overlap the status bar, back button, hero title, or tab header.
- The adjustment must be shared so Event, Account Profile, and Static Asset immersive screens do not each solve placement differently.

### Event Time Language
- Event/occurrence public models or projections expose canonical calculated display values for the contexts consumers need, for example detail/hero range and agenda-card compact range.
- Widgets consume those model/projection values directly. They may choose layout/truncation, but they must not reconstruct the final date/time language from raw `DateTime` or local `DateFormat` fragments.
- Same-day range example: `quinta, 11 de junho · 12h01-14h01`.
- Cross-day range example: `quinta, 11 de junho · 22h até sexta, 12 de junho · 2h`.
- Agenda-card range under an existing day header may use compact context: `12h01-14h01`.
- Start-only event example: `quinta, 11 de junho · 12h01`.
- Whole-hour example: `20h`; nonzero-minute example: `20h30`.
- Public copy must avoid repeated labels such as `QUI, 11 • 12:01 às QUI, 11 • 14:01` when start/end share the same date.

## Automated Test Matrix
### Flutter Domain / Model
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `FL-TIME-01` | Same-day start/end with nonzero minutes. | Event/occurrence model exposes one date context and start/end times. | Domain/model unit test. |
| `FL-TIME-02` | Same-day start/end on whole hours. | Model display value renders `20h-22h`, not `20:00`. | Domain/model unit test. |
| `FL-TIME-03` | Cross-day start/end. | Model display value includes both date contexts. | Domain/model unit test. |
| `FL-TIME-04` | Start-only event. | Model display value renders date + start time without dangling range punctuation. | Domain/model unit test. |
| `FL-TIME-05` | Agenda-context same-day range. | Model/projection exposes compact time-only copy when day header owns date context. | Domain/model unit test. |
| `FL-TIME-06` | Locale pt_BR. | Model display values use human-readable Portuguese weekday/month labels. | Domain/model unit test. |
| `FL-TIME-07` | Widget consumer audit. | Public widgets consume model/projection display values and do not assemble final labels with local `DateFormat`. | Static audit or analyzer/source-scan test. |

### Flutter Widget / Controller
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `FL-ACTION-01` | Tap event hero `Convidar` while authorized. | `InviteShareRoute` is pushed directly and the first-touch invite sheet is absent. | Event detail widget/navigation test. |
| `FL-ACTION-02` | Tap event hero `Convidar` while anonymous on web. | Contextual `AppPromotionModal` appears directly, no first-touch invite sheet is rendered, no login phone UI appears, and no share code is created. | Event detail widget/navigation test. |
| `FL-ACTION-03` | Tap event hero `Compartilhar`. | Share launcher called directly; canonical sheet not visible. | Event detail widget test. |
| `FL-ACTION-04` | Tap event hero `WhatsApp`. | WhatsApp/native fallback launcher called directly; canonical sheet not visible. | Event detail widget test. |
| `FL-ACTION-05` | Anonymous web favorite/action gate. | Canonical promotion surface appears; phone-login UI absent; no auto-open. | Promotion/favorite widget test. |
| `FL-ACTION-06` | Store targets Android-only, iOS-only, both, none. | Canonical promotion surface preserves settings-driven buttons. | Promotion widget tests. |
| `FL-ACTION-07` | Tap event hero `Convidar` anonymously on web. | The direct promotion gate does not hydrate recipients/statuses/contacts and does not create share codes. | Controller/lazy-load test. |
| `FL-HERO-01` | Expanded immersive hero with three actions. | First rail action top aligns with back/top control band within a deterministic tolerance. | Shared immersive widget test. |
| `FL-HERO-02` | Collapsed immersive hero. | Primary plus `more` action contract remains unchanged. | Shared immersive widget test. |
| `FL-TIME-UI-01` | Immersive event hero same-day occurrence. | Hero label renders the model/projection same-day human text. | Event detail widget test. |
| `FL-TIME-UI-02` | Agenda/upcoming card same-day occurrence under day header. | Card renders the model/projection compact readable time. | Upcoming event card/widget test. |
| `FL-TIME-UI-03` | Live-now and my-events cards. | Cards render the model/projection display value. | Widget tests. |
| `FL-TIME-UI-04` | Map/POI event detail card. | POI card renders the model/projection display value. | POI resolver/card tests. |
| `FL-TIME-UI-05` | Invite/share surfaces. | Invite/share copy consumes canonical calculated values where applicable and remains engaging. | Invite/share widget/unit tests. |

### Runtime / Browser / Device
| ID | Scenario | Expected Behavior | Evidence Target |
| --- | --- | --- | --- |
| `RT-ACTION-01` | Web anonymous favorite/action gate. | Canonical promotion surface appears, no `/auth/login`, no phone-login UI, no `/open-app` before explicit CTA. | Source-owned Playwright readonly runtime proof. |
| `RT-ACTION-02` | Event detail hero `Convidar` on anonymous web. | Contextual app-promotion modal opens directly; the removed first-touch invite sheet and legacy promotion route are absent; no share code is created. | Source-owned Playwright navigation test. |
| `RT-HERO-01` | Event, Account Profile, and Static Asset expanded heroes. | Action rail aligns near the top action plane and does not overlap hero content across mobile viewport. | Source-owned Playwright screenshot/position assertion. |
| `RT-TIME-01` | Agenda and event detail in real browser. | Human-readable time labels match model/projection display expectations and avoid repeated same-day date context. | Source-owned Playwright text assertion. |

## Manual Validation Matrix
| ID | Surface | Steps | Expected Result |
| --- | --- | --- | --- |
| `MAN-TPA-01` | Public Event Hero | Open an event detail with expanded hero. | Right-side action buttons align with the top controls, not halfway down the hero. |
| `MAN-TPA-02` | Public Event Hero | Tap `Convidar` as anonymous web user. | Contextual app-promotion modal opens directly with `Convide pessoas pelo app`; no intermediate invite sheet appears. |
| `MAN-TPA-03` | Public Event Hero | Tap `Compartilhar` and `WhatsApp`. | Each action launches its direct share path without opening any invite sheet. |
| `MAN-TPA-04` | Public Account/Profile Favorite Web Anonymous | Tap favorite anonymously on web. | Canonical app-promotion surface appears; no phone login and no automatic app/store open. |
| `MAN-TPA-05` | Agenda | Open agenda list with same-day events. | Cards show readable time ranges without repeated date/weekday. |
| `MAN-TPA-06` | Event Detail | Open same-day and cross-day events. | Same-day label has one date context; cross-day label has both contexts. |
| `MAN-TPA-07` | Map/POI | Open event POI/detail card if available. | Time text follows the same human-language pattern. |
| `MAN-TPA-08` | Invite/Share | Open invite/share flows. | Copy remains readable, with day and time suitable for users. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / focused domain, action, and public time UI suite` | New canonical time display values, direct invite-boundary behavior, promotion modal shell, and public widget consumers. | `cd flutter-app && fvm flutter test --no-pub --exclude-tags=stage-compatibility --dart-define-from-file=config/defines/dev.json test/application/sharing/event_invite_share_payload_test.dart test/domain/venue_event/projections/venue_event_resume_test.dart test/presentation/tenant/map/screens/map_screen/widgets/shared/poi_content_resolver_test.dart test/presentation/tenant/map/screens/map_screen/widgets/event_poi_detail_card_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/shared/promotion/screens/app_promotion_screen/app_promotion_modal_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant_public/widgets/upcoming_event_card_test.dart test/presentation/tenant_public/widgets/event_live_now_card_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/my_events_carousel_card_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` | `Local-Implemented` | `passed` | Focused suite passed with `183` tests after removing the first-touch invite sheet, deleting the generic action-sheet shell, and moving the modal body into `AppPromotionModal`; focused hard-cutoff subset also passed `71` tests. | Covers direct authorized `InviteShareRoute`, anonymous web `Convide pessoas pelo app`, removed invite sheet/source scan, direct share/WhatsApp, promotion modal shell, store settings, and time labels. |
| `flutter-app / analyzer` | Flutter presentation/domain changes are analyzer-rule sensitive. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Validated` | `passed` | Analyzer command passed clean with exit `0`. | No analyzer output after final Dart changes. |
| `flutter-app / rule matrix` | Shared presentation and domain changes must keep the architecture plugin active. | `cd flutter-app && bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` | `Local-Validated` | `passed` | Exact stdout recorded: `Rule matrix validation passed; validated 58 lint-code fixtures; total distinct codes emitted: 59.` | Confirms architecture plugin rule activation after final Dart changes. |
| `docker / web build` | User-visible local-public web bundle must reflect the Flutter source changes. | `FLUTTER_DART_DEFINE_FILE=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/config/defines/dev.json tools/flutter/build_web_bundle.sh` | `Promotion-Package-Validated` | `passed` | Web build passed and synced bundle to `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app` after the generic sheet removal. | Build emitted known wasm dry-run warnings for existing SSE `dart:html` and Mixpanel JS interop surfaces; release build completed. |
| `docker / source-owned Playwright favorite/promotion readonly` | Shared app-promotion modal changes must be validated on the browser-facing tenant after rebuild. | `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_WORKERS=1 NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh readonly` | `Promotion-Package-Validated` | `passed` | Targeted readonly source-owned smoke passed `1/1` after the current `web-app` rebuild and updated `tools/flutter/web_app_tests/favorite_auth_gate_runtime.readonly.spec.js`. | Ran against `https://guarappari.belluga.space`; validates anonymous account/discovery favorite actions show `Escolha seus favoritos pelo app`, keep the app-promotion modal, and do not auto-open app/login routes. |
| `docker / source-owned Playwright targeted mutation` | Event direct invite-boundary and confirm-attendance web promotion behavior require real browser navigation on the mutation lane. | `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='INVITE-SESSION-CONTEXT' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation` | `Promotion-Package-Validated` | `passed` | Targeted event mutation passed `1/1`; invite session-context mutation passed `2/2`; favorite readonly diagnostic passed `1/1`, all after the current `web-app` rebuild. | Proves `Confirmar Presença` opens `Confirme presença pelo app`, `Convidar` opens `Convide pessoas pelo app` directly, no legacy promotion route, and no share-code is created before the guarded composer boundary. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Remove the generic event-invite action sheet surface and establish `AppPromotionModal` as the canonical app-promotion/action-gate modal surface. | source + widget tests | `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/widgets/app_promotion_modal.dart`; focused Flutter suite; source scan. | `flutter-app` | `passed` | Former generic action-sheet source files are removed; `AppPromotionModal` owns the modal body and store actions directly. |
| `SCOPE-02` | `Scope` | Route event `Convidar` directly to the canonical invite-flow boundary: authorized users push `InviteShareRoute`; anonymous web users open the contextual `AppPromotionModal`; no first-touch invite sheet is rendered. | widget + web Playwright mutation route validation | `immersive_event_detail_screen_test.dart`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation`; `InviteShareRoute` route assertion in widget tests. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Widget tests assert direct authorized `InviteShareRoute` and anonymous web `Convide pessoas pelo app`; browser mutation passed after rebuild. |
| `SCOPE-03` | `Scope` | Keep event `Compartilhar` and direct `WhatsApp` as immediate single-intent share actions; they must not open the canonical action surface first. | widget tests | `immersive_event_detail_screen_test.dart` in focused Flutter suite. | `flutter-app` | `passed` | Share and WhatsApp remain direct actions. |
| `SCOPE-04` | `Scope` | Migrate app-promotion/favorite web gate UI to the same canonical action surface family while preserving `AppPromotionScreenController` and `AppPublicationSettings` as the source of truth for store targets. | source + widget tests | `app_promotion_modal.dart`; `app_promotion_modal_test.dart`; favorite web gate widget tests. | `flutter-app` | `passed` | Promotion modal reuses canonical sheet and settings-backed store actions. |
| `SCOPE-05` | `Scope` | Preserve the web mandate: anonymous web gates promote the app, do not show phone-login UI, and do not auto-open app/store before explicit CTA. | widget + web readonly/mutation Playwright runtime | `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.readonly.spec.js`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `bash tools/flutter/run_web_navigation_smoke.sh readonly`; `bash tools/flutter/run_web_navigation_smoke.sh mutation` after `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Web runtime preserves promotion boundary for favorite and `Confirmar Presença`; no legacy phone-login or immediate store/app open. |
| `SCOPE-06` | `Scope` | Align expanded immersive hero action buttons to the shared top action plane, visually aligned with the back button/top controls, instead of rendering below the collapsed toolbar band. | shared widget test | `immersive_detail_screen_test.dart`. | `flutter-app` | `passed` | Shared hero rail now uses top safe-area plane. |
| `SCOPE-07` | `Scope` | Keep collapsed immersive action behavior intact: primary action plus secondary `more` menu. | shared widget test | `immersive_detail_screen_test.dart`. | `flutter-app` | `passed` | Collapsed contract still renders primary plus secondary menu. |
| `SCOPE-08` | `Scope` | Expose canonical human-ready calculated time display values from the event/occurrence public model or projection. | domain tests | `event_schedule_display.dart`; `event_model.dart`; `event_occurrence_option.dart`; `venue_event_resume_test.dart`. | `flutter-app` | `passed` | `detailScheduleLabel` and `agendaScheduleLabel` are model/projection-owned. |
| `SCOPE-09` | `Scope` | Keep any lower-level formatter/helper internal to model/projection construction; widgets must not call it to assemble final event time labels. | source audit + analyzer | Source scan for public widgets; analyzer. | `flutter-app` | `passed` | Public widgets consume calculated labels. |
| `SCOPE-10` | `Scope` | Replace ad hoc event/occurrence time strings across public surfaces found by source audit, including immersive event hero, agenda/upcoming cards, live-now cards, my-events/event carousel cards, map/POI event detail cards, linked event/profile cards when applicable, and invite/share presentation surfaces. | widget tests + source audit | Focused Flutter suite across listed surfaces. | `flutter-app` | `passed` | Public final labels now come from domain/projection values. |
| `SCOPE-11` | `Scope` | Use context-aware labels: avoid repeating weekday/date when start and end are on the same day; include both dates only for cross-day ranges; render whole hours as `20h` and nonzero minutes as `20h30` / `12h01`. | domain tests | `venue_event_resume_test.dart`. | `flutter-app` | `passed` | Same-day, cross-day, whole-hour, and minute cases are covered. |
| `SCOPE-12` | `Scope` | Preserve existing invite/share payload semantics while improving human readability from canonical calculated model/projection values where applicable. | unit + widget tests | `event_invite_share_payload_test.dart`; invite/share widget tests. | `flutter-app` | `passed` | Share payload now receives canonical schedule label. |
| `SCOPE-13` | `Scope` | Add deterministic tests and runtime navigation validation proving the behavior in real UI, not only by code inspection. | local CI-equivalent | Focused Flutter suite, analyzer, rule matrix, web build, targeted readonly smoke, targeted mutation smoke. | `flutter-app`, `web-app`, `https://guarappari.belluga.space` | `passed` | Focused Flutter suite, analyzer, rule matrix, web build, favorite readonly, event mutation, and invite session-context mutation all passed after hard cutoff. |
| `DOD-01` | `Definition of Done` | `DOD-01` Event `Convidar` does not open a first-touch invite sheet; authorized users push `InviteShareRoute` directly, and anonymous web users open the contextual app-promotion modal without creating share codes. | widget + web build + source-owned Playwright mutation | `immersive_event_detail_screen_test.dart`; `FLUTTER_DART_DEFINE_FILE=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/config/defines/dev.json tools/flutter/build_web_bundle.sh` synced `web-app`; source-owned Playwright spec `tools/flutter/web_app_tests/event_share_boundary.spec.js`; runner command `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation` against `https://guarappari.belluga.space` mutation lane. | `flutter-app`, rebuilt `web-app`, web target `https://guarappari.belluga.space` | `passed` | Widget/browser validation proves direct gate/route behavior and no share-code creation before the guarded composer boundary after refreshed web build provenance. |
| `DOD-02` | `Definition of Done` | `DOD-02` Event `Compartilhar` invokes the share launcher directly and does not open the canonical action sheet. | widget + web Playwright navigation | `immersive_event_detail_screen_test.dart`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation` after `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Widget test proves direct launcher and no sheet; browser navigation proves the `Compartilhar` hero action remains independent from invite-boundary handling. |
| `DOD-03` | `Definition of Done` | `DOD-03` Event `WhatsApp` invokes the WhatsApp/native-fallback launcher directly and does not open the canonical action sheet. | widget + web Playwright navigation | `immersive_event_detail_screen_test.dart`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation` after `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Widget test proves direct WhatsApp/native-fallback launcher and no sheet; browser navigation proves the `WhatsApp` hero action remains independent from invite-boundary handling. |
| `DOD-04` | `Definition of Done` | `DOD-04` App-promotion/favorite web gate renders through the canonical action surface family and still respects store-target settings. | widget + web Playwright readonly/mutation runtime | `app_promotion_modal_test.dart`; `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.readonly.spec.js`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_WORKERS=1 NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh readonly`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='INVITE-SESSION-CONTEXT' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation`; `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | `AppPromotionModal` renders `app_promotion_modal_body`, keeps store settings, and browser evidence passed for favorite, event invite, and invite fallback. |
| `DOD-05` | `Definition of Done` | `DOD-05` Anonymous web favorite/action gates never render phone-login UI and never auto-open app/store before an explicit CTA. | widget + web Playwright readonly/mutation runtime | `immersive_event_detail_screen_test.dart`; `account_profile_detail_screen_test.dart`; `invite_flow_screen_test.dart`; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.readonly.spec.js`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`; `bash tools/flutter/run_web_navigation_smoke.sh readonly`; `bash tools/flutter/run_web_navigation_smoke.sh mutation`; `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Web runtime asserts contextual promotion modal boundary for favorite (`Escolha seus favoritos pelo app`), `Confirmar Presença` (`Confirme presença pelo app`), direct event invite (`Convide pessoas pelo app`), and invite-flow fallback (`Aceite convites pelo app`); no phone-login, `/auth/login`, `/open-app`, `/baixe-o-app`, or app/store handoff from the CTA. |
| `DOD-06` | `Definition of Done` | `DOD-06` Expanded immersive hero action rail aligns with the top controls in the expanded state; collapsed state still uses the primary-plus-more AppBar contract. | shared widget test | `immersive_detail_screen_test.dart` in the focused Flutter suite. | `flutter-app` | `passed` | Shared `ImmersiveDetailScreen` owns the placement. |
| `DOD-07` | `Definition of Done` | `DOD-07` Event/occurrence public models or projections expose canonical human-ready calculated time display values. | domain tests + widget tests + web Playwright navigation | `event_schedule_display.dart`; `event_model.dart`; `event_occurrence_option.dart`; `venue_event_resume_test.dart`; focused widget suite; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `bash tools/flutter/run_web_navigation_smoke.sh mutation` after `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Public contract is `detailScheduleLabel` / `agendaScheduleLabel` from model/projection values and the refreshed browser event route renders the updated event detail surface. |
| `DOD-08` | `Definition of Done` | `DOD-08` Same-day ranges do not repeat the date/weekday for the end time. | domain + widget tests | `venue_event_resume_test.dart`; public widget tests in the focused suite. | `flutter-app` | `passed` | Example covered: `quarta, 1 de abril · 7h-10h` and compact `7h-10h`. |
| `DOD-09` | `Definition of Done` | `DOD-09` Cross-day ranges include both date contexts in a readable format. | domain tests | `venue_event_resume_test.dart`. | `flutter-app` | `passed` | Cross-day label includes both start and end date contexts. |
| `DOD-10` | `Definition of Done` | `DOD-10` Whole-hour and nonzero-minute formatting are both covered. | domain tests | `venue_event_resume_test.dart`. | `flutter-app` | `passed` | Covered `20h-22h`, `7h30-9h45`, and start-only cases. |
| `DOD-11` | `Definition of Done` | `DOD-11` Agenda, immersive hero, event cards, map/POI cards, and invite/share surfaces covered by the source audit render the model/projection display values instead of local calculations. | widget tests + source audit + web Playwright navigation | Focused Flutter suite; source scan for `detailScheduleLabel`, `agendaScheduleLabel`, `eventDateDetailLabel`; `tools/flutter/web_app_tests/event_share_boundary.spec.js`; `bash tools/flutter/run_web_navigation_smoke.sh mutation` after `tools/flutter/build_web_bundle.sh` synced `web-app`. | `flutter-app`, `web-app` rebuilt bundle, `https://guarappari.belluga.space` browser | `passed` | Public surfaces no longer assemble final user-facing event range strings from local `DateFormat` fragments; browser navigation exercised the refreshed immersive event route. |
| `DOD-12` | `Definition of Done` | `DOD-12` Focused Flutter tests, analyzer, rule matrix, web build, and source-owned Playwright/runtime validation pass before delivery claim. | local CI-equivalent + web build + source-owned Playwright runtime | Local CI-Equivalent Suite Matrix above; `fvm dart analyze --format machine`; `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh`; `FLUTTER_DART_DEFINE_FILE=/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/config/defines/dev.json tools/flutter/build_web_bundle.sh` synced `web-app`; source-owned Playwright specs `tools/flutter/web_app_tests/event_share_boundary.spec.js`, `tools/flutter/web_app_tests/favorite_auth_gate_runtime.readonly.spec.js`, and `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`; runner commands `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='T6-EVENT-SHARE' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation`, `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='FAV-GATE-RUNTIME' NAV_WEB_WORKERS=1 NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh readonly`, and `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='INVITE-SESSION-CONTEXT' NAV_WEB_SUITE_TIMEOUT_SECONDS=420 bash tools/flutter/run_web_navigation_smoke.sh mutation` against `https://guarappari.belluga.space`. | `flutter-app`, rebuilt `web-app`, web target `https://guarappari.belluga.space` browser | `passed` | Focused Flutter suite, analyzer, rule matrix, web build, event mutation, favorite readonly, and invite session-context mutation passed after hard cutoff. |

## Decision Adherence Validation
| Decision ID | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-TPA-01` | `adherent` | `AppPromotionModal` owns the modal shell directly; the former generic action-sheet directory is removed. | Generic sheet hard cutoff applied and validated. |
| `D-TPA-02` | `adherent` | Feature modules inject sheet summary/actions; shared shell owns anatomy only. | No business switch was added to the shared shell. |
| `D-TPA-03` | `adherent` | Event invite widget tests and targeted Playwright mutation. | `Convidar` now directly enters the invite boundary; share/WhatsApp stay direct. |
| `D-TPA-04` | `adherent` | Full-composer action still pushes `InviteShareRoute`. | Composer behavior remains route-owned. |
| `D-TPA-05` | `adherent` | `AppPromotionModal` still consumes app-promotion controller/settings-backed store actions. | No phone-login modal was introduced on web. |
| `D-TPA-06` | `adherent` | `ImmersiveDetailScreen` placement changed and tested. | Event/account/static consumers share the rail placement. |
| `D-TPA-07` | `adherent` | `EventScheduleDisplay` plus model/projection getters. | Widgets consume calculated values. |
| `D-TPA-08` | `adherent` | Agenda labels use `agendaScheduleLabel`. | Compact labels are allowed under day headers. |
| `D-TPA-09` | `adherent` | Formatter is internal to domain/projection construction. | Widgets do not call the formatter to assemble final range labels. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `generated-source-and-analyzer-preflight` | Missing generated/source file or analyzer breakage. | `passed` | `fvm dart analyze --format machine`; `validate_rule_matrix.sh`; new source files tracked in `flutter-app`. | No analyzer or architecture-rule blocker. | Analyzer and rule matrix are clean. |
| `stale-web-runtime-preflight` | Browser-visible code not reflected in local-public bundle. | `passed` | `tools/flutter/build_web_bundle.sh`; targeted readonly and mutation smokes after rebuild. | No stale runtime blocker. | `web-app` bundle was regenerated after Flutter source changes and served to source-owned Playwright. |
| `auth-boundary-regression-preflight` | Anonymous web action could bypass promotion/auth or create invite share-code. | `passed` | Updated `event_share_boundary.spec.js`; targeted mutation run after rebuild. | No auth-boundary blocker. | `Confirmar Presença` opens promotion; direct event invite opens `Convide pessoas pelo app`; no share-code creation is allowed before the guarded composer boundary. |
| `ux-contract-drift-preflight` | Canonical sheet or time-language contract could fragment across surfaces. | `passed` | Focused widget suite and source audit in Completion Evidence Matrix. | No contract-drift blocker. | Shared shell and model/projection labels are the only public contract for touched paths. |
| `copilot-review-readiness-preflight` | Review could fail due to unclear evidence or broad untracked changes. | `passed` | This TODO evidence matrix plus `git status` review after implementation. | No review-readiness blocker. | Generated `web-app` changes are isolated as bundle output; source changes are in `flutter-app`, docs, and source-owned Playwright spec. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `canonical-action-surface` | Parallel modal/login UI. | `passed` | `AppPromotionModal`, event invite direct-boundary implementation and tests. | No parallel modal/login UI found in active Flutter/web surfaces. | Promotion uses `AppPromotionModal` and existing store settings; no phone-login modal was introduced for web; `Confirmar Presença`, direct event invite, and invite-flow fallback use contextual app-promotion surfaces; legacy promotion dialog, generic action-sheet shell, and event invite first-touch sheet are removed from `flutter-app/lib`. |
| `shared-shell-boundary` | Business logic inside shared shell. | `passed` | `app_promotion_modal.dart`. | No generic shared shell remains. | The remaining modal shell is promotion-owned and store-settings backed; event invite no longer has a reusable sheet shell. |
| `model-owned-time-language` | Widget-local final time formatting in touched public surfaces. | `passed` | Public event hero, agenda/upcoming, live-now, my-events, profile, POI/map, and invite/share widgets. | No widget-local final range formatting found for touched public labels. | Remaining `DateFormat` usage is grouping/programming/admin or tests, not the final public range strings covered by this TODO. |
| `web-auth-boundary` | Direct route/auth bypass on web invite and confirm attendance. | `passed` | `event_share_boundary.spec.js` runtime mutation. | No route/auth bypass found. | Anonymous web `Confirmar Presença` and `Convidar` open contextual promotion surfaces and create no share-code. |
| `false-green-resistance` | Tests that pass only by source inspection or no-exception behavior. | `passed` | Focused Flutter suite plus readonly smoke plus targeted mutation. | No false-green test shape found for touched behavior. | Behavior is asserted in widget tests and real browser navigation. |

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is a tactical TODO requiring approval before implementation. | Approval, frozen decisions, guard pass, evidence matrix. | Implementation before `APROVADO`. | Run `todo_authority_guard.py` after approval. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Shared Flutter presentation, controllers, route guards, and tests are in scope. | Controllers own state/effects; presentation stays dependency-clean. | Business switches inside shared shells; duplicated modal/login code. | Keep `AppPromotionModal` promotion-owned and remove the generic action sheet shell. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-screen-workflow-glob/SKILL.md` | Tenant-public screens/widgets will be touched. | `tenant_public` ownership and scope policy. | Undefined subscopes or ad hoc route/screen ownership. | Reference `foundation_documentation/policies/scope_subscope_governance.md`. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md` | Event/occurrence model or projection fields will change. | Domain entity/projection documentation before code and DTO/prototype alignment when applicable. | Widget-only formatter fixes that bypass the model. | Update domain/module docs if Flutter domain files change. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-flutter-flutter-contract-alignment-always-on/SKILL.md` | Public model/projection contracts must stay aligned with canonical entities and downstream consumers. | DTO/model mapping and repository/DAO boundaries. | API-facing drift or raw-map parsing in repositories. | Record any backend/API dependency if model changes require upstream payload support. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The gaps were found manually and need false-green-resistant tests. | Behavior-first widget/runtime tests. | Code-inspection-only validation. | Add fail-first focused tests and runtime navigation. |

## TODO Closeout Disposition
- **Disposition:** `move-promotion-lane`
- **Disposition reason:** local implementation, focused validation, web build, Playwright runtime checks, and deterministic delivery guards are complete, and the current package-wide mimic loop kept this TODO clean with no reopened findings; only authorized lane follow-through remains.
- **Post-commit/push status:** `ready-for-promotion-lane`
- **Next path/status action:** move this TODO into `foundation_documentation/todos/promotion_lane/v0.2.0+8/` and carry it through the current v0.2.0+8 package promotion.

## Approval
- **Status:** `approved`
- **Approved by:** `user`
- **Approved at:** `2026-06-02T15:33:02-03:00`
- **Approval evidence:** User message `OK. APROVADO.`
- **Approval scope:** Implement the tenant-public action surface and event time-language contract on the current v0.2.0+8 reconcile lane: canonical `AppPromotionModal` for app-promotion/action gates, removal of the generic action sheet shell, direct event `Convidar` invite-flow boundary without first-touch invite sheet, direct share/WhatsApp behavior, app-promotion/favorite web gate using the canonical surface without phone-login or auto-open, expanded immersive hero action rail top alignment, model/projection-owned human-ready event/occurrence time display values, public widget consumption of those values, focused tests, analyzer/rule matrix, web build, and source-owned runtime validation.
- **Renewed approval required if:** implementation changes backend/API/schema, route guard/auth policy, invite mutation semantics, full invite composer behavior, or expands beyond tenant-public public-event/action/time-language surfaces.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality` for tests/runtime validation; `strategic-cto-tech-lead` if UX contract decisions need further audit.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
- **Primary runtime scope:** `EnvironmentType=tenant`, main scope `tenant_public`.
- **Scope policy reference:** `foundation_documentation/policies/scope_subscope_governance.md`

## Complexity
- **Level:** `big`
- **Checkpoint policy:** plan/audit checkpoint before approval and delivery-gate reconciliation before any closeout.
- **Why this level:** the slice changes a shared action surface, shared immersive placement, and public event time language across multiple widgets and browser-visible flows.
