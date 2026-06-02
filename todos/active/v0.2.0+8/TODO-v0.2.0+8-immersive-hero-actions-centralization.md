# Immersive Hero Actions Centralization

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant-public immersive detail screens currently need one shared hero action model instead of rebuilding favoritar/compartilhar/WhatsApp/convidar controls separately in Event, Account Profile, and Static Asset screens. The event hero invite button must open the canonical invite route so existing route guards own web/app auth behavior.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** one cohesive tenant-public UX slice: centralize immersive hero action composition and apply it to the three current immersive detail surfaces.
- **Direct-to-TODO rationale:** the request is bounded to existing Flutter immersive detail routes and does not require new backend schema, API, or broad product decomposition.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` below define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: Delphi may absorb local discoveries only while they remain inside the same primary objective and the same main approval/review/promotion conversation.
- If any assumption or plan step changes `Scope`, `Out of Scope`, `Definition of Done`, required validation semantics, public contract, or frozen decisions, update this TODO contract first and request renewed approval before execution continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Validated`
- **Qualifiers:** `Recovery`
- **Next exact step:** Await explicit commit/push/promotion authority or the user's next validation request.

## Recovery Blocker Notes
- **Blocker:** Code and validation were started before this governing TODO was created and approved.
- **Why it stopped normal flow:** TODO-driven execution requires contract refinement, frozen baseline, approval, rule ingestion, and `todo_authority_guard.py` before implementation.
- **Resolution:** User approved the `Option A` recovery contract with `APROVADO` on `2026-05-31T18:22:12-03:00`; the recovery path decision was previously closed by user-delegated triple-audit consensus.
- **Owner / source:** User approval authority.
- **Last confirmed truth:** User requested centralized immersive behavior with open-hero vertical actions, collapsed primary + more actions, event invite routing to the invite screen/guard, and shared behavior across Event, Account Profile, and Static Asset where semantically supported.
- **Scope extension accepted from user message:** On `2026-05-31`, user requested more engaging invite/share messages with human-readable day and time while the share payload work was already under test-quality remediation.
- **Scope extension accepted from user message:** On `2026-05-31`, user requested the Event immersive hero height be changed from 80% to 65% of viewport height; Account Profile immersive hero remains at 50%.
- **Regression remediation accepted from user validation:** On `2026-05-31`, user reported that Account Profile favorite was leaving the current page instead of toggling and direct WhatsApp opened the web link on a device with WhatsApp installed. The remediation preserves the approved hero-action semantics: authenticated Account Profile favorite toggles in place, and direct WhatsApp attempts the native app URI before `wa.me` fallback.
- **Visual regression remediation accepted from user validation:** On `2026-05-31`, user reported an unwanted divider/shadow line between the Account Profile hero CTA and the tabs. Root cause was the shared immersive tab header using fixed elevation before it overlapped content. The remediation keeps the header flat in the expanded hero state and restores elevation only when the header is pinned/overlapping content.
- **Favorite toggle regression remediation accepted from user validation:** On `2026-06-02`, user reported that favorite buttons should have the same behavior wherever account profiles are favoritable, including Account Profile detail, Discovery, event linked-profile lists, and nested/grouped linked-profile tabs. The remediation establishes one shared account-profile favorite auth gate, requires all account-profile favorite toggle controllers to return `requiresAuthentication` for anonymous users before mutation, maps anonymous web users to the canonical app-promotion modal without automatic app reopening, and preserves the non-web login redirect/pending-action replay path.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `flutter-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `web-app:chore/node24-navigation-workflow-20260519`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Immersive hero actions | `flutter-app:04ba7216`, `web-app:7b30a02`, docs commit pending | `not opened yet` | `not opened yet` | `not applicable` | `Local implementation committed locally; push/root gitlink update in progress. Promotion remains a separate full v0.2.0+8 package step.` |

## Approval
- **Status:** `approved`
- **Approved by:** `user`
- **Approval evidence:** User message `APROVADO` on `2026-05-31T18:22:12-03:00`, after user-delegated triple-audit consensus adopted `Option A`.
- **Approval scope:** `Option A` recovery contract: continue from the current uncommitted pre-TODO Flutter/web diff as the recovery candidate for immersive hero action centralization, bounded strictly by this TODO's `Scope`, `Out of Scope`, `Definition of Done`, `Validation Steps`, and derived audit floor.
- **Explicit exclusions:** No backend/API/schema changes; no new Static Asset favorite semantics; no invite composer redesign; no account-profile favorite authorization changes; no unrelated immersive visual redesign; no commit, push, promotion, or delivery claim before delivery gates and deterministic guards.
- **Renewed approval trigger:** any scope, route guard, domain semantics, Static Asset favorite, backend/API, or validation requirement change.

## Scope
- [x] Add one shared `ImmersiveDetailScreen` hero action contract for primary and secondary actions.
- [x] Render expanded hero actions as a right-side vertical rail.
- [x] Render collapsed hero actions as primary action plus a `more` secondary-actions menu.
- [x] Event detail declares primary `Convidar`, secondary `Compartilhar`, and direct `WhatsApp`.
- [x] Event `Convidar` navigates to `InviteShareRoute` and lets the canonical route guard handle web/app auth behavior.
- [x] Account Profile detail declares primary `Favoritar` when the profile type is favoritable, plus secondary `Compartilhar` and direct `WhatsApp`.
- [x] Static Asset detail declares share and WhatsApp actions only unless a future domain contract approves Static Asset favorites.
- [x] Share and WhatsApp payload generation reuses shared public-share launcher behavior where practical.
- [x] Event invite/share messages use more engaging Portuguese copy with human-readable day and time.
- [x] Event immersive hero uses 65% of viewport height.
- [x] Account Profile web-authenticated favorite action toggles in place instead of triggering the web handoff/promotion path.
- [x] Direct WhatsApp share attempts the native WhatsApp URI before falling back to `wa.me` and then system share.
- [x] Shared immersive tab header does not draw a divider/shadow line between the expanded hero and tabs; elevation is applied only once the header overlaps content.
- [x] Account Profile favorite toggle surfaces use the same auth/toggle contract across hero/footer, Discovery cards, and event linked-profile/nested-group tabs.
- [x] Add focused tests for expanded/collapsed central action behavior and event invite route navigation.

## Out of Scope
- [x] Backend invite API changes remain out of scope.
- [x] New Static Asset favorite semantics remain out of scope.
- [x] Redesigning the invite composer screen remains out of scope.
- [x] Changing account-profile favorite authorization rules remains out of scope.
- [x] Changing immersive hero visual/layout beyond action placement remains out of scope.
- [x] Promotion, commit, or push before TODO approval and delivery gates remains out of scope.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** local Flutter model/widget/test extraction needed to keep hero actions centralized and route/auth behavior canonical.
- **Must update or split the TODO:** any new backend contract, Static Asset favorite ownership, new invite composer behavior, route guard policy change, or visual redesign unrelated to hero action controls.

## Definition of Done
- [x] `DOD-01` `ImmersiveDetailScreen` exposes a centralized hero action contract and no current immersive screen needs to hand-build app bar action layout for these controls.
- [x] `DOD-02` Expanded hero shows individual action buttons; collapsed hero shows the primary action and a `more` menu for secondary actions.
- [x] `DOD-03` Event detail uses `Convidar` as the primary action and opens `InviteShareRoute` rather than generating an invite/share link from the hero button.
- [x] `DOD-04` Event/account/static public share and WhatsApp actions produce public route payloads, not ad hoc local duplicate strings.
- [x] `DOD-05` Static Asset does not gain favorite UI or state without a domain-approved favorite contract.
- [x] `DOD-06` Focused Flutter tests cover the central expanded/collapsed action behavior and event invite navigation.
- [x] `DOD-07` Flutter analyzer and web build remain green after implementation.
- [x] `DOD-08` Event invite/share copy renders a human-readable day/time instead of raw `DateTime.toString()` output.
- [x] `DOD-09` Event immersive hero uses 65% of viewport height while Account Profile immersive hero remains at 50%.
- [x] `DOD-10` Hero action regressions reported during manual validation are covered by tests: Account Profile favorite toggles in place for authenticated web users, and direct WhatsApp attempts native app launch before web/system fallback.
- [x] `DOD-11` Expanded immersive hero does not render a divider/shadow line before the tabs; pinned tab headers retain separation when they overlap content.
- [x] `DOD-12` Account Profile favorite buttons share one behavior contract: authenticated users toggle in place; anonymous web users see the canonical app-promotion modal without phone OTP login UI or automatic app reopening; anonymous non-web users keep the canonical login redirect/pending-action replay path.

## Validation Steps
- [x] `VAL-01` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`
- [x] `VAL-02` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`
- [x] `VAL-03` `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`
- [x] `VAL-04` `fvm flutter test test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`
- [x] `VAL-05` `fvm flutter analyze`
- [x] `VAL-06` `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`
- [x] `VAL-07` Manual/browser smoke on representative tenant-public routes after the approved implementation is published to the local validation web target: `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef`.
- [x] `VAL-08` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` as part of the focused suite covering human-readable invite/share copy.
- [x] `VAL-09` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` after event hero-height extension.
- [x] `VAL-10` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` after Account Profile favorite and direct WhatsApp regression remediation.
- [x] `VAL-11` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; visual browser check on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; readonly browser smoke after expanded-hero tab-divider remediation.
- [x] `VAL-12` Favorite toggle behavior validation: focused RED/GREEN controller/widget tests for Account Profile detail, Discovery, and event linked-profile lists; full touched-suite Flutter run; `fvm dart analyze --format machine`; analyzer rule matrix; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; source-owned Playwright diagnostic `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Add one shared `ImmersiveDetailScreen` hero action contract for primary and secondary actions. | `code+test` | `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/models/immersive_hero_action.dart`; `ImmersiveDetailScreen.heroActions`; focused suite `00:49 +142` | `local` | `passed` | Event, Account Profile, and Static Asset consumers migrated to the shared action contract. |
| `SCOPE-02` | `Scope` | Render expanded hero actions as a right-side vertical rail. | `widget test` | `test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; focused suite `00:49 +142` | `local` | `passed` | Tests assert expanded hero action rail behavior. |
| `SCOPE-03` | `Scope` | Render collapsed hero actions as primary action plus a `more` secondary-actions menu. | `widget test` | `test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; focused suite `00:49 +142` | `local` | `passed` | Tests assert collapsed primary action and `immersiveHeroMoreAction`. |
| `SCOPE-04` | `Scope` | Event detail declares primary `Convidar`, secondary `Compartilhar`, and direct `WhatsApp`. | `code+test` | `test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; focused suite `00:49 +142` | `local` | `passed` | Event hero action composition covered by event detail tests. |
| `SCOPE-05` | `Scope` | Event `Convidar` navigates to `InviteShareRoute` and lets the canonical route guard handle web/app auth behavior. | `navigation test+code inspection+web provenance` | Event detail route navigation test asserts `InviteShareRoute`; `flutter-app/lib/application/router/modular_app/modules/invite_share_module.dart:21-27`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `web-app/index.html` contains `window.__WEB_BUILD_SHA__ = "92cb17ec"`; source-owned Playwright runner `tools/flutter/run_web_navigation_smoke.sh readonly` using specs under `tools/flutter/web_app_tests/` returned `16 passed (3.3m)` against `https://belluga.space` and `https://guarappari.belluga.space` | `local/web/browser` | `passed` | Route remains guarded by `TenantRouteGuard` and `AuthRouteGuard`; browser-facing web bundle provenance is recorded for the served domain. |
| `SCOPE-06` | `Scope` | Account Profile detail declares primary `Favoritar` when the profile type is favoritable, plus secondary `Compartilhar` and direct `WhatsApp`. | `widget test` | `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`; focused suite `00:49 +142` | `local` | `passed` | Capability-aware favorite and secondary share/WhatsApp behavior covered. |
| `SCOPE-07` | `Scope` | Static Asset detail declares share and WhatsApp actions only unless a future domain contract approves Static Asset favorites. | `widget test` | `test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`; focused suite `00:49 +142` | `local` | `passed` | Static Asset has no favorite action. |
| `SCOPE-08` | `Scope` | Share and WhatsApp payload generation reuses shared public-share launcher behavior where practical. | `unit+widget test` | `test/presentation/shared/sharing/public_share_launcher_test.dart`; consumer tests; focused suite `00:49 +142` | `local` | `passed` | Event, Account Profile, and Static Asset share actions delegate through `PublicShareLauncher`. |
| `SCOPE-09` | `Scope` | Event invite/share messages use more engaging Portuguese copy with human-readable day and time. | `unit+widget test` | `test/application/sharing/event_invite_share_payload_test.dart`; invite share screen tests; event detail tests; focused suite `00:49 +142` | `local` | `passed` | Tests assert weekday/month/time copy and absence of raw ISO date fragments. |
| `SCOPE-10` | `Scope` | Event immersive hero uses 65% of viewport height. | `code+widget test+build` | `heroViewportHeightFactor: 0.65` in `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen.dart`; event detail suite `00:13 +49: All tests passed!`; `fvm flutter analyze`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `local/web bundle` | `passed` | Account Profile remains `heroViewportHeightFactor: 0.5` in `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`. |
| `SCOPE-11` | `Scope` | Add focused tests for expanded/collapsed central action behavior and event invite route navigation. | `test+navigation evidence` | Focused suite command below returned `00:49 +142: All tests passed!`; event detail test asserts route navigation to `InviteShareRoute` and `createShareCodeCalls == 0` | `local` | `passed` | Central widget and event route-navigation tests included. |
| `SCOPE-12` | `Scope` | Account Profile web-authenticated favorite action toggles in place instead of triggering the web handoff/promotion path. | `regression widget test` | `test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`; initial RED compile failure on missing `isWebRuntime`; final focused suite `00:52 +145: All tests passed!` | `local` | `passed` | `web authenticated favorite action toggles instead of leaving the page` asserts favorite stream mutation and no router push/replace. |
| `SCOPE-13` | `Scope` | Direct WhatsApp share attempts the native WhatsApp URI before falling back to `wa.me` and then system share. | `unit+consumer widget tests` | `test/presentation/shared/sharing/public_share_launcher_test.dart`; Account Profile and Static Asset consumer tests; initial RED failures on missing native URI/fallback path; final focused suite `00:52 +145: All tests passed!` | `local` | `passed` | `whatsapp://send` is attempted before `https://wa.me`; system share remains final fallback when URL launches fail. |
| `SCOPE-14` | `Scope` | Shared immersive tab header does not draw a divider/shadow line between the expanded hero and tabs; elevation is applied only once the header overlaps content. | `red/green widget test+visual browser check` | RED: `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart --plain-name "tab header is flat before it overlaps content and elevated when pinned"` failed because the flat-state `Material` elevation was `4.0`; GREEN: targeted test passed `+1`, full shared widget suite returned `00:02 +13: All tests passed!`; visual Playwright capture `/tmp/account-profile-tabs-raw2.png` on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa` showed no line between CTA and tabs. | `local/web/browser` | `passed` | Root cause fixed in `ImmersiveHeaderDelegate`: elevation is `0.0` while `overlapsContent == false` and `4.0` once pinned/overlapping. |
| `SCOPE-15` | `Scope` | Account Profile favorite toggle surfaces use the same auth/toggle contract across hero/footer, Discovery cards, and event linked-profile/nested-group tabs. | `red/green controller+widget tests+runtime diagnostic` | RED: `fvm flutter test --no-pub test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart --name "toggleFavorite requires authentication for anonymous users"` failed because anonymous users returned `toggled`; corrective RED: `fvm flutter test --no-pub test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart --name "web anonymous favorite action promotes app instead of phone login"` first failed because the shared gate mounted phone OTP UI, then failed again when it auto-opened the app without showing the promotion modal; GREEN: `fvm flutter test --no-pub test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` returned `00:37 +153`; source-owned Playwright spec `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` through runner `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=FAV-GATE-RUNTIME bash tools/flutter/run_web_navigation_smoke.sh readonly` returned `1 passed (34.6s)`. | `local/web/browser` | `passed` | Shared gate `AccountProfileFavoriteAuthGate` owns only runtime choice: web anonymous shows `AppPromotionDialog` and only the modal CTA can open the app; non-web uses canonical login redirect with action replay; controllers reject anonymous mutation with `requiresAuthentication`. |
| `DOD-01` | `Definition of Done` | `DOD-01` `ImmersiveDetailScreen` exposes a centralized hero action contract and no current immersive screen needs to hand-build app bar action layout for these controls. | `code+test+browser navigation` | `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/models/immersive_hero_action.dart`; `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/navigation.spec.js` returned `16 passed`. | `local/web/browser` | `passed` | Event, Account Profile, and Static Asset consume the shared hero action contract. |
| `DOD-02` | `Definition of Done` | `DOD-02` Expanded hero shows individual action buttons; collapsed hero shows the primary action and a `more` menu for secondary actions. | `widget+browser navigation` | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/account_profile_detail.spec.js` returned `16 passed`. | `local/web/browser` | `passed` | Central shared widget tests assert expanded rail and collapsed primary/more action behavior. |
| `DOD-03` | `Definition of Done` | `DOD-03` Event detail uses `Convidar` as the primary action and opens `InviteShareRoute` rather than generating an invite/share link from the hero button. | `widget route test+browser navigation` | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; route assertion covers `InviteShareRoute`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/navigation.spec.js` returned `16 passed`. | `local/web/browser` | `passed` | Event primary action remains route-owned by `InviteShareRoute` and does not generate a share code from the hero. |
| `DOD-04` | `Definition of Done` | `DOD-04` Event/account/static public share and WhatsApp actions produce public route payloads, not ad hoc local duplicate strings. | `unit+consumer widget+browser navigation` | `fvm flutter test test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Shared public route payload builders are used for Event, Account Profile, and Static Asset share/WhatsApp actions. |
| `DOD-05` | `Definition of Done` | `DOD-05` Static Asset does not gain favorite UI or state without a domain-approved favorite contract. | `widget+browser navigation` | `fvm flutter test test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/navigation.spec.js` returned `16 passed`. | `local/web/browser` | `passed` | Static Asset remains share/WhatsApp only. |
| `DOD-06` | `Definition of Done` | `DOD-06` Focused Flutter tests cover the central expanded/collapsed action behavior and event invite navigation. | `widget navigation test` | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; event detail test asserts `InviteShareRoute` navigation; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Central action behavior and event route navigation have focused tests plus browser navigation smoke. |
| `DOD-07` | `Definition of Done` | `DOD-07` Flutter analyzer and web build remain green after implementation. | `analyzer+build` | `fvm dart analyze --format machine` exited `0` with no findings; `fvm flutter analyze` returned `No issues found!`; `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh` passed; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` rebuilt `../web-app`. | `local/web build` | `passed` | The generated-font constant naming issue was corrected; analyzer, rule matrix, and web build passed after the favorite-gate changes. |
| `DOD-08` | `Definition of Done` | `DOD-08` Event invite/share copy renders a human-readable day/time instead of raw `DateTime.toString()` output. | `unit+widget+browser navigation` | `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Tests assert Portuguese weekday/month/time copy and no raw ISO timestamp fragments. |
| `DOD-09` | `Definition of Done` | `DOD-09` Event immersive hero uses 65% of viewport height while Account Profile immersive hero remains at 50%. | `code+widget+browser navigation` | Event detail test suite passed; `heroViewportHeightFactor: 0.65` in Event detail and `heroViewportHeightFactor: 0.5` in Account Profile detail; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Event and Account Profile hero height factors remain distinct by immersive surface. |
| `DOD-10` | `Definition of Done` | `DOD-10` Hero action regressions reported during manual validation are covered by tests: Account Profile favorite toggles in place for authenticated web users, and direct WhatsApp attempts native app launch before web/system fallback. | `red/green widget+unit+browser navigation` | `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/account_profile_detail.spec.js` returned `16 passed`. | `local/web/browser` | `passed` | Account Profile authenticated favorite does not push/replace routes; WhatsApp attempts `whatsapp://send` before `https://wa.me`. |
| `DOD-11` | `Definition of Done` | `DOD-11` Expanded immersive hero does not render a divider/shadow line before the tabs; pinned tab headers retain separation when they overlap content. | `red/green widget+visual browser` | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; visual Playwright capture `/tmp/account-profile-tabs-raw2.png`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Flat expanded header and elevated pinned header are both covered. |
| `DOD-12` | `Definition of Done` | `DOD-12` Account Profile favorite buttons share one behavior contract: authenticated users toggle in place; anonymous web users see the canonical app-promotion modal without phone OTP login UI or automatic app reopening; anonymous non-web users keep the canonical login redirect/pending-action replay path. | `red/green controller+widget+Playwright` | `fvm flutter test --no-pub test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` returned `00:37 +153`; `fvm dart analyze --format machine` exited `0` with no findings; analyzer rule matrix passed with configured `58` lint codes; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` rebuilt `../web-app`; source-owned Playwright spec `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` ran through project runner `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=FAV-GATE-RUNTIME bash tools/flutter/run_web_navigation_smoke.sh readonly` with `.env.local.navigation` target `NAV_TENANT_URL=https://guarappari.belluga.space`, `NAV_DEPLOY_LANE=dev`, returning `1 passed (34.6s)`. | `local/web/browser` | `passed` | Widget tests assert web anonymous favorites show `Continue pelo app`, do not show `Entrar para favoritar`, do not mutate favorites, and do not push `/baixe-o-app` or `/auth/login`; authenticated web users still toggle in place; event linked-profile lists use the same shared gate handler; Playwright asserts the modal and rejects immediate `/open-app`. |
| `VAL-01` | `Validation Steps` | `VAL-01` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` | `widget test+browser navigation` | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` returned `00:02 +13`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Shared immersive widget tests and browser smoke completed. |
| `VAL-02` | `Validation Steps` | `VAL-02` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `widget test+browser navigation` | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` returned `00:13 +49`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Event detail screen suite and browser smoke completed. |
| `VAL-03` | `Validation Steps` | `VAL-03` `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` | `widget test+browser navigation` | `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` returned `1 passed`. | `local/web/browser` | `passed` | Account Profile detail widget and favorite runtime behavior completed. |
| `VAL-04` | `Validation Steps` | `VAL-04` `fvm flutter test test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart` | `widget test+browser navigation` | `fvm flutter test test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Static Asset screen tests and browser smoke completed. |
| `VAL-05` | `Validation Steps` | `VAL-05` `fvm flutter analyze` | `analyzer` | `fvm flutter analyze` returned `No issues found!`; `fvm dart analyze --format machine` exited `0`; analyzer rule matrix passed. | `local` | `passed` | Full-app analyzer evidence recorded after implementation. |
| `VAL-06` | `Validation Steps` | `VAL-06` `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `web build` | `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` returned `Flutter web bundle available at: ../web-app (lane: dev)`. | `local/web build` | `passed` | Local-public web bundle was rebuilt from source. |
| `VAL-07` | `Validation Steps` | `VAL-07` Manual/browser smoke on representative tenant-public routes after the approved implementation is published to the local validation web target: `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef`. | `browser navigation smoke` | `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/navigation.spec.js`, `tools/flutter/web_app_tests/account_profile_detail.spec.js`, and `tools/flutter/web_app_tests/directions_brand_visual.spec.js` returned `16 passed`. | `https://belluga.space` and `https://guarappari.belluga.space` | `passed` | Browser smoke covers tenant-public event, partner, and static route families after web build. |
| `VAL-08` | `Validation Steps` | `VAL-08` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` as part of the focused suite covering human-readable invite/share copy. | `unit+widget+browser navigation` | `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Human-readable invite/share copy covered by focused tests and browser smoke. |
| `VAL-09` | `Validation Steps` | `VAL-09` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` after event hero-height extension. | `widget test+browser navigation` | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` returned `00:13 +49`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Event hero-height extension remains covered. |
| `VAL-10` | `Validation Steps` | `VAL-10` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` after Account Profile favorite and direct WhatsApp regression remediation. | `focused suite+browser navigation` | Exact focused suite returned `00:52 +145`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed`. | `local/web/browser` | `passed` | Account Profile favorite and WhatsApp regression remediation remains covered. |
| `VAL-11` | `Validation Steps` | `VAL-11` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; visual browser check on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; readonly browser smoke after expanded-hero tab-divider remediation. | `widget+visual browser navigation` | Shared widget suite returned `00:02 +13`; visual Playwright capture `/tmp/account-profile-tabs-raw2.png`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/account_profile_detail.spec.js` returned `16 passed`. | `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa` | `passed` | Visual browser check and readonly browser smoke completed after tab-divider remediation. |
| `VAL-12` | `Validation Steps` | `VAL-12` Favorite toggle behavior validation: focused RED/GREEN controller/widget tests for Account Profile detail, Discovery, and event linked-profile lists; full touched-suite Flutter run; `fvm dart analyze --format machine`; analyzer rule matrix; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; source-owned Playwright diagnostic `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js`. | `red/green tests+analyzer+web build+Playwright` | RED controller test failed on anonymous favorite returning `toggled`; corrective RED web-mandate test failed on local phone OTP modal/no promotion; second corrective RED failed because the web gate auto-opened the app instead of showing the promotion modal; GREEN full focused suite returned `00:37 +153`; `fvm dart analyze --format machine` exited `0` with no findings; rule matrix passed with configured `58` lint codes; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` rebuilt `../web-app`; source-owned Playwright spec `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` ran through project runner `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=FAV-GATE-RUNTIME bash tools/flutter/run_web_navigation_smoke.sh readonly` with `.env.local.navigation` target `NAV_TENANT_URL=https://guarappari.belluga.space`, `NAV_DEPLOY_LANE=dev`, returning `1 passed (34.6s)`. | `local/web/browser` | `passed` | Account Profile detail, Discovery, and event linked-profile widget tests assert app-promotion modal, absence of phone login UI, no anonymous mutation, and no route push; Playwright diagnostic requires the modal and blocks immediate `/open-app`. |

## Account Profile Favorite Toggle Surface Audit
- **Real toggle surfaces:** Account Profile detail hero action and footer fallback (`accountProfileFavoriteAction`, `accountProfileFavoriteFooterButton`); Discovery account profile cards (`discoveryFavoriteButton_<id>`); Event linked-profile cards, including profile groups/nested event tabs (`linkedProfileFavoriteButton_<id>`).
- **Non-toggle favorite surface:** Tenant home favorites strip is a navigation/listing surface for existing favorites; it does not mutate favorite state and remains outside the shared toggle gate.
- **No favorite toggle in map deck:** `poi_details_deck.dart` exposes share/invite affordances. The remaining web handoff there is event invite behavior, not account-profile favorite.
- **Centralized UI gate:** `flutter-app/lib/presentation/shared/favorites/account_profile_favorite_auth_gate.dart` delegates anonymous web to the canonical app-promotion modal and uses the canonical login redirect/pending-action replay path off-web.
- **Controller contract:** `AccountProfileDetailController.toggleFavorite`, `DiscoveryScreenController.toggleFavorite`, and `ImmersiveEventDetailController.toggleLinkedProfileFavorite` return `requiresAuthentication` before repository mutation when the user is anonymous.

### Bug Fix Evidence Loop
| Mandatory Question | Answer |
| --- | --- |
| Do we already have tests that cover this behavior across all stages up to UI display? | `false-green`. Existing tests covered some authenticated Account Profile behavior and individual favorite streams, but did not enforce anonymous-web behavior across Account Profile, Discovery, and event linked-profile lists. |
| Did we inspect current real database/backend payloads to verify compatibility with current parsing and rendering assumptions? | Backend payload shape was not the root cause. The failure was UI/controller auth routing for account-profile favorite actions. Runtime validation targets the current local tenant web bundle and live tenant routes to prove anonymous web reaches the app-promotion modal instead of phone OTP UI or automatic app reopening. |
| If existing tests should cover this bug, which exact test(s) failed? If none failed, why were they insufficient? | No existing test failed because the previous contract allowed anonymous controller mutation and did not exercise anonymous web favorite taps for all visible surfaces. |
| If tests do not cover the failure, which new tests must be created before implementing the fix? | Added/updated controller and widget tests for Account Profile detail, Discovery, and event linked-profile favorite buttons; added source-owned Playwright diagnostic for Account Profile detail and Discovery runtime web taps. |
| Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? | `rule-candidate`: web favorite gates must not instantiate `AuthLoginController`, `AuthPhoneOtpForm`, or any phone-login UI. The statically recognizable pattern is phone-OTP auth imports/widgets inside shared favorite/web gate code. Existing analyzer coverage did not forbid this action-specific web-login duplication shape. |

### Stage Coverage Matrix
| Stage | Status | Evidence |
| --- | --- | --- |
| Backend/API | `covered-by-contract` | No backend mutation should be attempted for anonymous users; repository mutation is now blocked at controller stage before backend call. |
| DTO/Repository | `covered` | Repository favorite stream remains the state source for authenticated toggles; tests assert no mutation for anonymous controller outcomes and mutation for authorized outcomes. |
| Controller | `covered` | Account Profile, Discovery, and Event linked-profile controllers return `requiresAuthentication` for anonymous users. |
| UI | `covered` | Account Profile detail, Discovery grid, and event linked-profile widget tests assert anonymous web app-promotion modal/no phone login UI/no route push, and non-web login redirect remains covered through pending-action flow tests. |
| Runtime Web | `covered` | Diagnostic Playwright spec taps Account Profile detail and Discovery favorite buttons against the rebuilt local-public tenant web bundle and verifies the app-promotion modal with no phone login UI and no automatic `/open-app`; `1 passed (34.6s)`. |

### Historical Hero Delivery Evidence Snapshot
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | `DOD-01` `ImmersiveDetailScreen` exposes a centralized hero action contract and no current immersive screen needs to hand-build app bar action layout for these controls. | `code+test+browser smoke` | Shared model/widget diff; focused widget/consumer tests; source-owned Playwright runner `tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/navigation.spec.js`, `tools/flutter/web_app_tests/account_profile_detail.spec.js`, and `tools/flutter/web_app_tests/directions_brand_visual.spec.js` returned `16 passed (3.3m)` | `local/browser` | `passed` | `ImmersiveHeroAction`, `ImmersiveDetailScreen.heroActions`, and migrated Event/Account/Static Asset consumers; browser smoke covered tenant-public route boot/navigation on the served web bundle. |
| `DOD-02` | `Definition of Done` | `DOD-02` Expanded hero shows individual action buttons; collapsed hero shows the primary action and a `more` menu for secondary actions. | `widget test` | Focused suite command below returned `00:49 +142` | `local` | `passed` | Covers expanded rail and collapsed primary plus `immersiveHeroMoreAction`. |
| `DOD-03` | `Definition of Done` | `DOD-03` Event detail uses `Convidar` as the primary action and opens `InviteShareRoute` rather than generating an invite/share link from the hero button. | `widget/navigation test` | Focused suite command below returned `00:49 +142` | `local` | `passed` | Asserts `InviteShareRoute` and no invite-code creation from hero invite. |
| `DOD-04` | `Definition of Done` | `DOD-04` Event/account/static public share and WhatsApp actions produce public route payloads, not ad hoc local duplicate strings. | `code+test+browser smoke` | Focused suite with Event, Account Profile, Static Asset, invite share screen, and public share launcher tests; source-owned Playwright runner `tools/flutter/run_web_navigation_smoke.sh readonly`; ad hoc static route smoke for `https://guarappari.belluga.space/static/pw-static-share-1779940401325?smoke=1780265275264` | `local/browser` | `passed` | Event share asserts public event route and `createShareCodeCalls == 0`; Account/Static Asset WhatsApp and Static Asset share payloads have direct tests; browser route smoke confirmed static public route document/API load and no console errors. |
| `DOD-05` | `Definition of Done` | `DOD-05` Static Asset does not gain favorite UI or state without a domain-approved favorite contract. | `code inspection+test+browser smoke` | Static Asset screen/test; focused suite command below; static browser route smoke for `https://guarappari.belluga.space/static/pw-static-share-1779940401325?smoke=1780265275264` returned document `200`, `GET /api/v1/static_assets/pw-static-share-1779940401325` `200`, and `consoleErrorCount: 0` | `local/browser` | `passed` | Static Asset declares share/WhatsApp only and public static route loaded on the served web bundle. |
| `DOD-06` | `Definition of Done` | `DOD-06` Focused Flutter tests cover the central expanded/collapsed action behavior and event invite navigation. | `test` | `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | `local` | `passed` | `00:49 +142: All tests passed!` |
| `DOD-07` | `Definition of Done` | `DOD-07` Flutter analyzer and web build remain green after implementation. | `test+build` | `fvm flutter analyze`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `local/web bundle` | `passed` | Analyzer rerun after event hero-height extension: `No issues found! (ran in 115.1s)`; build rerun produced `../web-app`. |
| `DOD-08` | `Definition of Done` | `DOD-08` Event invite/share copy renders a human-readable day/time instead of raw `DateTime.toString()` output. | `unit+widget test` | `test/application/sharing/event_invite_share_payload_test.dart`; invite share screen tests; event detail tests; `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/test-quality-audit-claude-followup.md` | `local` | `passed` | Unit/widget tests assert `sábado, 14 de março às 20h30`, event detail `segunda-feira, 16 de março às 9h`, invite share screen `sexta-feira, 13 de março às 20h`, and no raw `2026-` date output. |
| `DOD-09` | `Definition of Done` | `DOD-09` Event immersive hero uses 65% of viewport height while Account Profile immersive hero remains at 50%. | `code+widget test+build` | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `fvm flutter analyze`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `local/web bundle` | `passed` | Event suite includes `event detail uses sixty-five percent immersive hero height` and expects `SliverAppBar.expandedHeight == 520` for an 800px viewport; Account Profile code remains `heroViewportHeightFactor: 0.5`. |
| `DOD-10` | `Definition of Done` | `DOD-10` Hero action regressions reported during manual validation are covered by tests: Account Profile favorite toggles in place for authenticated web users, and direct WhatsApp attempts native app launch before web/system fallback. | `regression tests+build+source-owned browser smoke` | `fvm flutter test ...` focused suite returned `00:52 +145: All tests passed!`; `fvm dart analyze --format machine` exited `0` with no machine findings; `fvm flutter analyze` returned `No issues found! (ran in 192.5s)`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` returned `Flutter web bundle available at: ../web-app (lane: dev)`; `web-app/index.html` has `window.__LANDLORD_HOST__ = "belluga.space"` and `window.__WEB_BUILD_SHA__ = "92cb17ec"`; source-owned runner `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly` using specs under `tools/flutter/web_app_tests/` returned `16 passed (4.7m)` against `https://belluga.space` and `https://guarappari.belluga.space` | `local/web/browser` | `passed` | Remediation keeps anonymous web handoff boundary and public share fallback chain explicit; relevant browser specs include `account_profile_detail.spec.js`, `deeplink_contract.spec.js`, `otp_auth_public.spec.js`, and `navigation.spec.js`. |
| `DOD-11` | `Definition of Done` | `DOD-11` Expanded immersive hero does not render a divider/shadow line before the tabs; pinned tab headers retain separation when they overlap content. | `regression test+build+source-owned browser smoke` | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` returned `00:02 +13: All tests passed!`; `fvm dart analyze --format machine` exited `0` with no findings; `fvm flutter analyze` returned `No issues found! (ran in 116.2s)`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` returned `Flutter web bundle available at: ../web-app (lane: dev)` and `web-app/index.html` contains `window.__LANDLORD_HOST__ = "belluga.space"` plus `window.__WEB_BUILD_SHA__ = "92cb17ec"`; source-owned runner `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/account_profile_detail.spec.js`, `tools/flutter/web_app_tests/navigation.spec.js`, and `tools/flutter/web_app_tests/directions_brand_visual.spec.js` returned `16 passed (3.2m)` against `https://belluga.space` and `https://guarappari.belluga.space`; visual Playwright capture on `/parceiro/qa-discovery-tag-longa` showed the expanded header flat before the tab bar. | `local/web/browser` | `passed` | Keeps the scroll-pinned separation behavior while removing the pre-tab shadow reported in manual validation. |
| `VAL-01` | `Validation Steps` | `VAL-01` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` | `test+browser smoke` | Focused suite command below returned `00:49 +142`; source-owned Playwright runner `tools/flutter/run_web_navigation_smoke.sh readonly` returned `16 passed (3.3m)` after web build provenance `__WEB_BUILD_SHA__=92cb17ec` | `local/browser` | `passed` | Included in expanded focused suite and paired with browser smoke for the shared route surface. |
| `VAL-02` | `Validation Steps` | `VAL-02` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` | `test+browser smoke` | Focused suite command below returned `00:49 +142`; source-owned Playwright specs under `tools/flutter/web_app_tests/` ran through `tools/flutter/run_web_navigation_smoke.sh readonly` after web build provenance `__WEB_BUILD_SHA__=92cb17ec` | `local/browser` | `passed` | Included in expanded focused suite and paired with tenant-public event route browser smoke/provenance. |
| `VAL-03` | `Validation Steps` | `VAL-03` `fvm flutter test test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart` | `test+browser smoke` | Focused suite command below returned `00:49 +142`; `tools/flutter/web_app_tests/account_profile_detail.spec.js` ran through `tools/flutter/run_web_navigation_smoke.sh readonly` against `https://guarappari.belluga.space` | `local/browser` | `passed` | Included in expanded focused suite and paired with account profile detail browser smoke. |
| `VAL-04` | `Validation Steps` | `VAL-04` `fvm flutter test test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart` | `test+browser smoke` | Focused suite command below returned `00:49 +142`; static route smoke for `https://guarappari.belluga.space/static/pw-static-share-1779940401325?smoke=1780265275264` returned document `200`, API `200`, and `consoleErrorCount: 0` after web build provenance `__WEB_BUILD_SHA__=92cb17ec` | `local/browser` | `passed` | Included in expanded focused suite and paired with static route browser smoke. |
| `VAL-05` | `Validation Steps` | `VAL-05` `fvm flutter analyze` | `static` | `fvm flutter analyze` | `local` | `passed` | Rerun after event hero-height extension: `No issues found! (ran in 115.1s)`. |
| `VAL-06` | `Validation Steps` | `VAL-06` `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `build` | `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `web bundle` | `passed` | Rerun after event hero-height extension; `Flutter web bundle available at: ../web-app (lane: dev)`. |
| `VAL-07` | `Validation Steps` | `VAL-07` Manual/browser smoke on representative tenant-public routes after the approved implementation is published to the local validation web target: `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef`. | `runtime+build provenance` | Build/publish proof: `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`, `web-app/index.html` has `window.__LANDLORD_HOST__ = "belluga.space"` and `window.__WEB_BUILD_SHA__ = "92cb17ec"`; source-owned Playwright runner: `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly`; specs under `tools/flutter/web_app_tests/`; static route smoke for `/static/pw-static-share-1779940401325` | `browser` | `passed` | Readonly suite passed `16 passed (3.3m)` against `https://belluga.space` and `https://guarappari.belluga.space`; Static Asset route smoke returned document `200`, public static asset API `200`, and `consoleErrorCount: 0`. |
| `VAL-08` | `Validation Steps` | `VAL-08` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` as part of the focused suite covering human-readable invite/share copy. | `unit+widget test+browser smoke` | Focused suite command below returned `00:49 +142`; source-owned Playwright runner `tools/flutter/run_web_navigation_smoke.sh readonly` ran after web build provenance `__WEB_BUILD_SHA__=92cb17ec` | `local/browser` | `passed` | Included in expanded focused suite; second Claude test-quality audit verdict `READY`; browser smoke proves the refreshed web bundle served after the copy implementation. |
| `VAL-09` | `Validation Steps` | `VAL-09` `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart` after event hero-height extension. | `widget test+browser smoke` | `fvm flutter test test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly` | `local/browser` | `passed` | Event suite `00:13 +49: All tests passed!` directly validates 65% event hero height; readonly browser smoke after rebuild passed `16 passed (2.5m)` against `https://belluga.space` and `https://guarappari.belluga.space`. |
| `VAL-10` | `Validation Steps` | `VAL-10` `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` after Account Profile favorite and direct WhatsApp regression remediation. | `red/green regression+ci-equivalent` | RED: focused tests failed on missing `PublicShareLauncher.webWhatsappUriForText`, missing `AccountProfileDetailScreen.isWebRuntime`, and Static Asset assertion requiring two URL launch attempts but receiving one `wa.me`; GREEN: the exact `VAL-10` command returned `00:52 +145: All tests passed!`; static gates and source-owned browser smoke evidence recorded in `DOD-10` and the Local CI-Equivalent Suite Matrix. | `local/web/browser` | `passed` | Covers both user-reported regressions and keeps Event/Invite shared action behavior green. |
| `VAL-11` | `Validation Steps` | `VAL-11` `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart`; visual browser check on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; readonly browser smoke after expanded-hero tab-divider remediation. | `red/green regression+ci-equivalent` | RED: targeted shared-header test failed on flat-state elevation `4.0`; GREEN: targeted shared-header test passed `+1`, full shared widget suite returned `00:02 +13: All tests passed!`, format returned `0 changed`, `fvm dart analyze --format machine` exited `0`, `fvm flutter analyze` returned `No issues found! (ran in 116.2s)`, build returned `Flutter web bundle available at: ../web-app (lane: dev)`, and `web-app/index.html` contains `window.__LANDLORD_HOST__ = "belluga.space"` plus `window.__WEB_BUILD_SHA__ = "92cb17ec"`; source-owned Playwright runner `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly` using `tools/flutter/web_app_tests/account_profile_detail.spec.js`, `tools/flutter/web_app_tests/navigation.spec.js`, and `tools/flutter/web_app_tests/directions_brand_visual.spec.js` returned `16 passed (3.2m)` against `https://belluga.space` and `https://guarappari.belluga.space`; ad hoc visual capture `/tmp/account-profile-tabs-raw2.png` on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa` showed no line before tabs. | `local/web/browser` | `passed` | Directly covers the user-reported line before the tab bar and preserves pinned header elevation. |

## Recovery Notes
- A pre-TODO implementation diff currently exists in the working tree. It must not be committed or pushed until this TODO is approved and deterministic guards pass.
- Pre-TODO validation already run: focused Flutter tests passed, `fvm flutter analyze` passed, and `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` passed. This evidence is recovery context only; final delivery rows must be filled after approved execution/gates.
- Post-approval validation already run: `fvm dart format --set-exit-if-changed ...` passed with `0 changed`; expanded focused Flutter suite passed with `00:49 +142: All tests passed!`; `fvm flutter analyze` passed with `No issues found! (ran in 127.7s)` and was rerun after the event hero-height extension with `No issues found! (ran in 115.1s)`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` passed and wrote `../web-app`, then was rerun after the event hero-height extension; readonly Playwright passed `16 passed (3.3m)` after the earlier build and was rerun after the event hero-height extension with `16 passed (2.5m)`; Static Asset route smoke passed for `https://guarappari.belluga.space/static/pw-static-share-1779940401325?smoke=...`; event detail suite after hero-height extension passed `00:13 +49: All tests passed!`; `bash delphi-ai/tools/test_orchestration_status_report.sh ...` returned `Overall outcome: promotion-ready` for the recorded test stages and decision adherence only.
- Regression-remediation validation after user manual report: RED focused tests failed on the missing native WhatsApp fallback API, missing `isWebRuntime` injection for Account Profile web favorite, and one-attempt `wa.me` behavior; GREEN focused suite passed with `00:52 +145: All tests passed!`; `fvm dart analyze --format machine` returned no machine findings; `fvm flutter analyze` returned `No issues found! (ran in 192.5s)`; `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` passed; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` rebuilt `../web-app`; readonly browser smoke passed `16 passed (4.7m)`.
- Expanded-hero tab-divider remediation validation after user manual report: RED targeted shared-header test failed while `Material` elevation stayed `4.0` before overlap; GREEN targeted test passed `+1`; full shared widget suite passed `00:02 +13`; format returned `0 changed`; `fvm dart analyze --format machine` exited `0`; `fvm flutter analyze` returned `No issues found! (ran in 116.2s)`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` rebuilt `../web-app`; visual Playwright capture `/tmp/account-profile-tabs-raw2.png` on `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa` showed no line between CTA and tabs; readonly browser smoke passed `16 passed (3.2m)`.
- Commit-preparation rerun on `2026-05-31`: focused suite passed `01:25 +146`; `fvm dart analyze --format machine` exited `0`; rule matrix passed with configured `57` lint codes detected; `fvm flutter analyze` returned `No issues found! (ran in 304.8s)`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` rebuilt the web bundle after Flutter commit `04ba7216`; `web-app/index.html` contains `window.__WEB_BUILD_SHA__ = "04ba7216"`; source-owned readonly browser smoke passed `16 passed (3.2m)` with runtime provenance `buildSha:"04ba7216"`; web bundle committed as `web-app:7b30a02`.
- Account-profile favorite toggle auth-gate validation on `2026-06-02`: RED controller test failed because anonymous Account Profile favorite returned `toggled`; GREEN focused commands passed for Account Profile, Discovery, and event linked-profile controllers/widgets; full touched-suite command returned `153 passed`; analyzer rule matrix passed with configured `58` lint codes; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` rebuilt the local-public web bundle; Playwright diagnostic `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` returned `1 passed (24.4s)`.
- Web-mandate correction on `2026-06-02`: user corrected that anonymous web has no phone login and must promote the app. Corrective RED widget test failed because `AccountProfileFavoriteAuthGate` mounted phone OTP UI; second corrective RED failed because the web gate auto-opened the app instead of showing the modal; GREEN focused Account Profile, Discovery, and event linked-profile web-anonymous tests passed after the shared gate removed OTP/login code and delegated web to `AppPromotionDialog.show`; full focused suite returned `00:37 +153`; `fvm dart analyze --format machine` exited `0` with no findings; analyzer rule matrix passed; web build passed; Playwright diagnostic returned `1 passed (34.6s)` and rejects immediate `/open-app`.

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `flutter`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log (Update when execution crosses profile boundaries)
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | User-visible public navigation/actions require focused test and runtime validation. | Flutter widget tests, analyzer, web build, browser smoke | `completed`; focused suite `00:49 +142`, analyzer, web build, readonly browser smoke, static route smoke, and second Claude test-quality audit verdict `READY`. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** visible cross-surface Flutter UX and navigation behavior across three immersive routes, but no backend/API/schema change.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/events_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` section `2.1` tenant-public immersive/detail route behavior.
  - `events_module.md` section `5.1` event detail client behavior if stable.
  - `account_profile_catalog_module.md` section `4` public account-profile detail if stable.
- **Module decision consolidation targets (required):**
  - Same as planned decision promotion targets; no consolidation before local validation and approval.

## Decision Queue Closed
- [x] `D-01` Recovery decision: user-delegated triple-audit consensus adopted `Option A`, continuing from the current uncommitted pre-TODO implementation diff as the recovery candidate. User approval is recorded in the `Approval` section.

## Decisions (Resolved Before Freeze)
- [x] `D-01` Continue from the current uncommitted pre-TODO implementation diff as a useful recovery candidate, based on user-delegated triple-audit consensus and user `APROVADO`. Final implementation authority still requires `todo_authority_guard.py` before additional code changes, commit, push, or delivery claim.
- [x] `D-02` Static Asset favorite remains out of scope because current domain authority does not approve Static Asset favorite semantics.
- [x] `D-03` Event hero primary action is `Convidar`; public/system share and WhatsApp are secondary share actions.
- [x] `D-04` Account Profile hero primary action is `Favoritar` only when profile type capability allows it; share and WhatsApp are secondary.
- [x] `D-05` Invite action routes to the canonical invite composer route so route guards own web/app authentication behavior.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `flutter_client_experience_module.md` tenant-public auth and route guard baseline | Auth Wall applies to restricted actions; route-based guards and app-promotion boundaries own web auth behavior. | `Preserve` | Section `2.1 Domain Rules`. |
| `flutter_client_experience_module.md` immersive detail contracts | Tenant-public immersive routes share structure/back behavior and are pure consumers of resolved theme. | `Preserve` | Section `2.1 Domain Rules`. |
| `events_module.md EVS-UI-01` | Event detail renders dynamic linked-profile tabs and stable `Sobre` / `Como Chegar` behavior. | `Preserve` | Events module section `4`. |
| `account_profile_catalog_module.md PCO-13` | Account Profile favorite ids are user-linked Flutter state and must flow through repository-owned streams. | `Preserve` | Account Profile module section `7`. |
| `invite_and_social_loop_module.md INV-PD-17/19` | Invite composer is unified, action-first, and route-owned. | `Preserve` | Invite module section `2.4`. |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Recovery path adopted as `Option A` by user-delegated triple-audit consensus and approved by user `APROVADO`; `todo_authority_guard.py` remains required before additional implementation work.
- [x] `D-02` Static Asset does not expose favorite semantics in this TODO.
- [x] `D-03` Event hero actions are `Convidar`, `Compartilhar`, and direct `WhatsApp`.
- [x] `D-04` Account Profile hero actions are capability-aware `Favoritar`, `Compartilhar`, and direct `WhatsApp`.
- [x] `D-05` `Convidar` navigates to `InviteShareRoute` and preserves canonical guard ownership.

## Questions To Close
- [x] `Q-01` Recovery path selection closed by user-delegated audit consensus and user `APROVADO`: `Option A` adopted.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current request is one bounded direct-to-TODO Flutter slice. | User requested a specific hero action behavior and centralization across current immersive routes. | Need feature brief/story split before implementation. | `High` | `Keep as Assumption` |
| `A-02` | Static Asset favorite is not approved. | Domain authority says Static Asset does not carry favorite semantics by itself. | Backend/domain changes would be required. | `High` | `Keep as Assumption` |
| `A-03` | Event invite route already has canonical guard ownership. | Confirmed by code inspection: `flutter-app/lib/application/router/modular_app/modules/invite_share_module.dart:23` path `/convites/compartilhar`; `flutter-app/lib/application/router/modular_app/modules/invite_share_module.dart:25` guards `[TenantRouteGuard(), AuthRouteGuard()]`. | Need route guard work or backend policy changes if this route registration changes. | `High` | `Confirmed` |

## Execution Plan (Required Before `APROVADO`)

### Touched Surfaces
- `flutter-app/lib/presentation/shared/widgets/immersive_detail_screen/**`
- `flutter-app/lib/presentation/shared/sharing/**`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/immersive_event_detail/**`
- `flutter-app/lib/presentation/tenant_public/partners/account_profile_detail_screen.dart`
- `flutter-app/lib/presentation/tenant_public/static_assets/static_asset_detail_screen.dart`
- Focused Flutter widget tests for the above.
- `web-app/main.dart.js` after web build.

### Ordered Steps
1. Approve recovery path and rule ingestion.
2. Run `todo_authority_guard.py` before any additional implementation.
3. Complete or revise the existing uncommitted implementation inside the approved boundary.
4. Run focused tests, analyzer, and web build.
5. Perform browser smoke on tenant-public Event, Account Profile, and Static Asset detail routes.
6. Fill completion evidence, decision adherence, CI-equivalent matrix, and final deterministic guards before any commit/push/promotion claim.

### Test Strategy
- **Strategy:** `test-after` for the already-started recovery diff; future revisions must be test-first where a missing behavior is discovered.
- **Why:** implementation exists before this TODO due to process drift; recovery requires tests to become contract evidence before delivery.
- **Fail-first target(s) (when required):** central widget expanded/collapsed action tests and event invite navigation test must fail if the old action model is restored.

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Event hero invite/share/WhatsApp | Visible public route actions and navigation | `shared-android-web` | `Playwright readonly or manual browser smoke` | `no` | `yes` | Event detail browser smoke plus widget navigation tests | `none` |
| Account Profile hero favorite/share/WhatsApp | Visible public route actions and auth-sensitive favorite | `shared-android-web` | `Playwright readonly or manual browser smoke` | `yes` | `yes` | Account Profile detail browser smoke plus focused Account Profile tests must preserve existing repository/controller favorite stream behavior. | `none` |
| Static Asset share/WhatsApp | Visible public route actions | `shared-android-web` | `Playwright readonly or manual browser smoke` | `no` | `yes` | Static Asset detail browser smoke | `none` |
| Event invite/share copy | Public invite conversion message content | `shared-android-web` | `unit/widget + browser smoke` | `no` | `yes` | Event invite payload unit tests, Event detail share test, InviteShare screen share test, readonly browser smoke after rebuild | `none` |
| Shared expanded/collapsed action rendering | Visible scroll-dependent UI | `shared-android-web` | `widget + browser smoke` | `no` | `no` | Central widget tests and browser smoke | `none` |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / focused widget+unit tests` | Shared widget, three route consumers, invite share copy, shared launcher, Account Profile web favorite, and WhatsApp native fallback changed. | `fvm flutter test test/application/sharing/event_invite_share_payload_test.dart test/presentation/shared/sharing/public_share_launcher_test.dart test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/static_assets/static_asset_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | `Local-Implemented` | `passed` | `00:52 +145: All tests passed!` | Expanded focused suite after Account Profile favorite and WhatsApp regression remediation. |
| `flutter-app / shared immersive header regression` | User-visible divider/shadow line before tabs changed in shared immersive header. | `fvm flutter test test/presentation/common/widgets/immersive_detail_screen/immersive_detail_screen_test.dart` | `Local-Implemented` | `passed` | Targeted RED/green test for `tab header is flat before it overlaps content and elevated when pinned`; final shared widget suite returned `00:02 +13: All tests passed!`. | Guards flat expanded state and pinned overlap elevation in `ImmersiveHeaderDelegate`. |
| `flutter-app / analyzer` | Flutter source changed. | `fvm flutter analyze` | `Local-Implemented` | `passed` | Previous regression remediation: `fvm flutter analyze` returned `No issues found! (ran in 192.5s)`, `fvm dart analyze --format machine` exited `0`, and `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` returned `success: configured 57 lint codes were detected.` Latest tab-divider remediation: `fvm dart analyze --format machine` exited `0`; `fvm flutter analyze` returned `No issues found! (ran in 116.2s)`. | Full-app analyzer and plugin matrix remain green after the latest shared header change. |
| `flutter-app -> web-app build` | Web runtime bundle changed. | `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev` | `Local-Implemented` | `passed` | `Flutter web bundle available at: ../web-app (lane: dev)` | Rerun after Account Profile favorite/WhatsApp remediation and again after expanded-hero tab-divider remediation; updates derived `web-app`. |
| `browser runtime smoke` | User-visible web route behavior changed. | `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly`; ad hoc Playwright smoke for `https://guarappari.belluga.space/static/pw-static-share-1779940401325?smoke=...`; visual Playwright capture for `https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa`. | `promotion` | `passed` | Readonly suite rerun after regression remediation: `16 passed (4.7m)`; readonly suite rerun after tab-divider remediation: `16 passed (3.2m)`; prior Static route smoke: document `200`, public static asset API `200`, `consoleErrorCount: 0`; visual capture `/tmp/account-profile-tabs-raw2.png` showed no line before tabs. | Covers representative `/parceiro/:slug`, `/agenda/evento/:slug` directions/deeplink surfaces from readonly suite plus explicit `/static/:assetRef` and the user-reported Account Profile route. |
| `flutter-app / favorite toggle auth gate regression` | Account-profile favorite buttons exist in multiple tenant-public surfaces; authenticated users must toggle in place, anonymous web users must see the app-promotion modal without phone login UI or automatic app reopening, and anonymous non-web users must follow canonical login redirect/replay. | `fvm flutter test --no-pub test/presentation/tenant_public/partners/controllers/account_profile_detail_controller_test.dart test/presentation/tenant_public/partners/account_profile_detail_screen_test.dart test/presentation/tenant_public/discovery/discovery_screen_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart`; `fvm dart analyze --format machine`; `bash ${PACED_GLOBAL_ANALYZER_PLUGIN_DIR:-tool/belluga_analysis_plugin}/bin/validate_rule_matrix.sh`; `CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA=FAV-GATE-RUNTIME bash tools/flutter/run_web_navigation_smoke.sh readonly`. | `Local-Implemented` | `passed` | Full touched-suite command returned `00:37 +153`; `fvm dart analyze --format machine` exited `0` with no findings; rule matrix detected `58` configured lint codes; web build completed; `tools/flutter/web_app_tests/favorite_auth_gate_runtime.diagnostic.spec.js` returned `1 passed (34.6s)`. | Covers Account Profile hero/footer through shared handler, Discovery card favorite, and event linked-profile/nested/grouped favorites through the linked profile section handler. |

### Runtime / Rollout Notes
- No migrations or backend deploy required.
- Web bundle must be rebuilt/published for local browser validation.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`)
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Plan Review Attestation
- **Completed during recovery audit:** yes.
- **Known high issue preserved:** `PROC-01` remains the governing recovery issue and blocks commit/push/delivery until user approval and downstream gates are complete.
- **Security note:** `InviteShareRoute` guard ownership verified in `flutter-app/lib/application/router/modular_app/modules/invite_share_module.dart:25`.
- **Testing note:** Account Profile favorite is an auth-sensitive mutation action; validation must preserve existing controller/repository stream behavior.

### Issue Cards
- **Issue ID:** `PROC-01`
  - **Severity:** `high`
  - **Evidence:** Implementation diff exists before TODO approval.
  - **Why it matters now:** It violates TODO-driven execution and blocks commit/push/delivery claims until recovered or reverted.
  - **Option A (Recommended):** Keep current uncommitted diff as recovery candidate, obtain `APROVADO`, rerun guards and validations, then fill delivery gates.
    - **Effort:** `low`
    - **Risk:** `medium`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `improves`
  - **Option B (Alternative):** Revert Delphi's uncommitted implementation and restart cleanly after `APROVADO`.
    - **Effort:** `medium`
    - **Risk:** `low`
    - **Blast radius:** `local`
    - **Maintenance burden:** `low`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `neutral`
    - **Structural soundness impact:** `improves`
  - **Option C (Do Nothing):** Continue without TODO recovery.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `process`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A if user approves the current implementation direction; otherwise Option B.

### Failure Modes & Edge Cases
- [x] Event hero invite must not bypass canonical auth/route guard behavior.
- [x] Static Asset must not expose unsupported favorite affordance.
- [x] Collapsed action menu must not hide the primary action.
- [x] Share/WhatsApp must not generate an invite code or mutate backend state.

### Residual Unknowns / Risks
- [x] Browser smoke target/URLs selected after approval and bundle publication: `https://belluga.space` and `https://guarappari.belluga.space`.

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-immersive-hero-actions-centralization.md`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-audit-escalation-20260531.json`; fingerprint `c232cc5b3764`; outcome `go`.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-surface Flutter UX and navigation behavior. |
| `blast_radius` | `cross-module` | Touches shared immersive widget plus Event, Account Profile, Static Asset, and Invite route behavior. |
| `behavioral_change_or_bugfix` | `yes` | Changes user-visible hero actions and event invite/share behavior. |
| `changes_public_contract` | `yes` | Tenant-public route action semantics and auth-visible invite routing are affected. |
| `touches_auth_or_tenant` | `yes` | Event invite action must preserve canonical tenant-public auth/route guard behavior. |
| `touches_runtime_or_infra` | `no` | No backend, queue, worker, infra, or runtime script behavior is in scope. |
| `touches_tests` | `yes` | Focused widget/navigation tests are in scope. |
| `critical_user_journey` | `yes` | Invite/share/favorite actions are public conversion and retention surfaces. |
| `release_or_promotion_critical` | `yes` | Work is on the current v0.2.0+8 promotion lane. |
| `high_severity_plan_review_issue` | `yes` | `PROC-01` is a high-severity process recovery issue. |
| `explicit_three_lane_request` | `yes` | User explicitly requested crossing auditors until consensus on whether the current diff is useful for the recovery path. |

## Derived Audit Floor
| Gate | Decision | Lifecycle Gate | Workflow | Depth / Policy | Reason Codes | Status |
| --- | --- | --- | --- | --- | --- | --- |
| Critique | `required` | `before_aprovado` | `wf-docker-independent-critique-method` | `expanded` | `CRITIQUE-BASELINE-ALWAYS`, `CRITIQUE-EXPANDED-RISK-SIGNALS` | `completed` |
| Test Quality Audit | `required` | `before_completed` | `wf-docker-independent-test-quality-audit-method` | `full` | `TQA-TESTS-TOUCHED`, `TQA-BEHAVIOR-OR-BUGFIX`, `TQA-PUBLIC-CONTRACT`, `TQA-CRITICAL-JOURNEY`, `TQA-RELEASE-CRITICAL` | `completed; second audit verdict READY` |
| Final Review | `required` | `before_completed` | `wf-docker-independent-final-review-method` | `expanded` | `FINAL-BASELINE-ALWAYS`, `FINAL-EXPANDED-RISK-SIGNALS` | `completed; Claude verdict READY` |
| Triple Review | `required` | `before_completed` | `audit-protocol-triple-review` | `additive_only` | `TRIPLE-EXPLICIT` | `recovery_decision_completed; no material recovery-decision change after delivery remediation` |
| Security Review | `required` | `before_completed` | `security-adversarial-review` | `none` | `SEC-AUTH-OR-TENANT` | `completed; low risk; passed` |
| Performance/Concurrency | `recommended` | `per_pcv1_gate_deadlines` | `wf-docker-performance-concurrency-validation-method` | `none` | `PCV-RELEASE-SENSITIVE` | `completed; passed` |
| Verification Debt | `required` | `before_completed` | `verification-debt-audit` | `none` | `VDA-MEDIUM-BIG-OR-RELEASE` | `completed; outcome none` |

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** `audit_escalation_guard.py` returned `go`; required before `APROVADO` with expanded depth due to cross-module, public-contract, auth/tenant, critical-journey, release-critical, and high-severity process-recovery signals.
- **Impact signals in scope:** `cross-module blast radius|public contract|auth/tenant|critical user journey|release-critical lane|high-severity issue card`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline|scope boundary|assumptions preview|execution plan summary|issue cards|residual risks|existing blockers`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `no; using authenticated Claude CLI as requested external reviewer`
- **Canonical multi-lane audit protocol (when required):** `required before completed; additive only; not a substitute for this pre-APROVADO critique`
- **Audit session / round evidence (when protocol used):** `none`
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|risk`
- **Critique status:** `completed`
- **Findings summary:** Claude returned `not_ready` with five findings: `F-01` unresolved recovery decision and baseline inconsistency, `F-02` blank Plan Review Gate, `F-03` unverified invite route guard assumption, `F-04` Account Profile mutation lane set to `maybe`, and `F-05` missing minimum browser-smoke route targets. `F-02` through `F-05` were integrated immediately; `F-01` is now integrated by the user-delegated recovery-decision triple audit that adopted `Option A`.
- **Evidence / reference:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-claude-todo-audit-20260531.md`

### External Critique Finding Resolution
| Finding | Severity | Resolution Status | Contract Change |
| --- | --- | --- | --- |
| `F-01` | `high` | `Integrated` | Recovery path closed by user-delegated triple-audit consensus: `Option A`; formal TODO approval recorded in the Approval section. |
| `F-02` | `high` | `Integrated` | Completed Plan Review Gate attestation and preserved `PROC-01` as the high-severity recovery issue. |
| `F-03` | `medium` | `Integrated` | Confirmed `InviteShareRoute` uses `[TenantRouteGuard(), AuthRouteGuard()]` at `flutter-app/lib/application/router/modular_app/modules/invite_share_module.dart:25`; updated `A-03`. |
| `F-04` | `medium` | `Integrated` | Resolved Account Profile mutation lane to `yes` with validation rationale for favorite stream preservation. |
| `F-05` | `low` | `Integrated` | Added minimum browser-smoke route families `/agenda/evento/:slug`, `/parceiro/:slug`, and `/static/:assetRef`. |

## Recovery Decision Triple Audit
- **Trigger:** user explicitly requested TODO audit, validation of whether the current diff is useful, and cross-auditor consensus.
- **Session:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-diff-usefulness-triple-audit-20260531T204311Z/session.json`
- **Package:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-diff-usefulness-triple-audit-package-20260531.md`
- **Round summary:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-diff-usefulness-triple-audit-20260531T204311Z/round-01/round-summary.md`
- **Resolution:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-diff-usefulness-triple-audit-20260531T204311Z/round-01/resolution.md`
- **Consensus:** `Option A`
- **Lane recommendations:** Elegance `Option A`; Performance `Option A`; Test Quality `Option A`.
- **Blocking findings:** `none`
- **Accepted non-blocking debt:** legacy loading-key special case, Key-as-action-identity polish, Account Profile primary-rule naming, future retirement of legacy share fallback, minor bounded allocation/luminance costs, Account/Profile and Static Asset share payload assertion hardening, WhatsApp URL/fallback unit coverage, PublicShareLauncher unit coverage.
- **Adopted decision:** continue from the current uncommitted diff as the useful recovery candidate. This does not authorize commit/push/delivery; formal `APROVADO`, `todo_authority_guard.py`, validation, and delivery gates still apply.

## Test Quality Audit
- **Initial audit:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/test-quality-audit-claude.md`; verdict `NOT_READY`.
- **Material findings integrated:** `staticAssetWhatsappAction` coverage, Static Asset share tap/payload coverage, `accountProfileWhatsappAction` coverage, `PublicShareLauncher` unit tests, `ImmersiveHeroAction` active/loading/fallback tests, and human-readable event invite/share copy tests.
- **Second audit:** `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/test-quality-audit-claude-followup.md`; verdict `READY`.
- **Accepted low residual debt:** timezone-service-aware branch for the copy formatter, extra assertion on Static Asset WhatsApp fallback body, and empty event name/location copy branches. These do not block delivery because the primary human-readable copy contract and share action payload paths are directly covered.

## Rules Acknowledgement / Ingestion (Required After `APROVADO`)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-docker-shared-todo-driven-execution-model-decision/SKILL.md` | Tactical implementation with code/tests/docs. | Approval, authority guard, delivery gates. | Commit/push/delivery claims before guards. | Blocks work until recovery approval. |
| `/home/elton/Dev/repos/delphi-ai/skills/wf-docker-todo-driven-execution-method/SKILL.md` | Umbrella TODO execution state machine. | Lane, contract, approval, execution, delivery, closeout phases. | Skipping phases silently. | Recovery must move through approval before more code. |
| `/home/elton/Dev/repos/delphi-ai/skills/flutter-architecture-adherence/SKILL.md` | Flutter presentation/controller/widget code changed. | Controllers own state; widgets present actions. | Repository/service DI from screens/widgets. | Keep shared widget pure and screen consumers declarative. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | User-visible navigation/actions changed. | Contract-specific tests. | Aggregate-only evidence. | Add central widget and route tests. |
| `/home/elton/Dev/repos/delphi-ai/skills/frontend-race-condition-validation/SKILL.md` | Public action buttons and navigation can be repeated. | Avoid duplicate/unsafe async action regressions. | Hidden mutation on share actions. | Confirm share is side-effect-free except external launch/share. |

## Delivery Gates (Fill Before Delivery Claim)
### Decision Adherence Validation
| Decision ID | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `adherent` | `foundation_documentation/artifacts/tmp/immersive-hero-actions-diff-usefulness-triple-audit-20260531T204311Z/round-01/resolution.md`; Approval section | `Option A` adopted and approved. |
| `D-02` | `adherent` | Static Asset screen and test; focused suite passed | Static Asset exposes share/WhatsApp only. |
| `D-03` | `adherent` | Event detail screen/test; focused suite passed | Event hero declares invite/share/WhatsApp. |
| `D-04` | `adherent` | Account Profile screen/test; focused suite passed `00:52 +145` after regression remediation | Account favorite remains capability-aware; authenticated web favorite toggles in place; share/WhatsApp secondary. |
| `D-05` | `adherent` | `InviteShareRoute` test and route guard inspection | Invite pushes canonical route; route has `TenantRouteGuard` and `AuthRouteGuard`. |
| `D-06` | `adherent` | `ImmersiveHeaderDelegate` code and shared widget test `tab header is flat before it overlaps content and elevated when pinned`; browser capture on `/parceiro/qa-discovery-tag-longa` | Shared tab header stays flat in expanded hero state and regains elevation only once pinned/overlapping. |

### Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `diff/static/test evidence` | P1/P2 defects before delivery claim | `passed` | `git -C flutter-app diff --check`; focused suite `00:52 +145`; shared widget suite `00:02 +13`; `fvm dart analyze --format machine`; `fvm flutter analyze`; `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`; `BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev`; readonly browser smoke `16 passed (3.2m)`; `bash delphi-ai/tools/test_orchestration_status_report.sh ...` previously returned `Overall outcome: promotion-ready`; `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/rule-spirit-flutter-final.json` | No P1/P2 found. Review-level findings only: hard-coded fixture domains in tests/app-data fixtures and existing modal `Navigator.pop` dismiss paths. | Latest remediation removes the expanded-header pre-tab shadow through shared header state, without adding backend mutation or route ownership. |

### Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `TODO-driven recovery and centralization drift` | Flutter architecture, tests, public share, route guard ownership, fixture leakage, manual navigation patterns | `passed` | `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/rule-spirit-flutter-final.json` | 34 review-level findings; max active severity `review`; no `warning`/`blocker`; no P1/P2. | Accepted review-only fixture-domain and modal-dismiss findings; no delivery blocker. |

### Security Review
| Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- |
| `Auth/tenant guard, public share URI, WhatsApp URL, system share fallback` | `passed` | `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/security-review.md` | No material finding; risk level `low`; attack simulation `not_needed`. | Invite route remains guarded by `TenantRouteGuard` and `AuthRouteGuard`; share actions do not introduce backend mutation or credential exposure. |

### Performance / Concurrency Review
| Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- |
| `Hero actions, share launchers, invite route navigation, copy formatter` | `passed` | `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/performance-concurrency-review.md` | No material performance/concurrency issue. | Event hero invite avoids share-code generation; share/WhatsApp are side-effect-free relative to backend state. |

### Verification Debt Audit
| Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- |
| `TODO evidence completeness and inline debt hygiene` | `passed` | `bash delphi-ai/tools/verification_debt_audit.sh --todo foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-immersive-hero-actions-centralization.md --scan-git-modified`; `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/verification-debt-audit.md` | Outcome heuristic `none`; inline code TODO debt classification `none`. | No unresolved verification debt found for the local implementation slice. |

### Independent Final Review
| Reviewer | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- |
| `Claude CLI no-context final review` | `passed` | `foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/final-review-claude.md` | Verdict `READY`; no blocker. Low residual items: run completion guard before delivery claim, accepted legacy share fallback, accepted timezone-service-aware copy test gap, informational dialog-local `Navigator.pop` scan findings. | Completion guard is being run as a deterministic process gate; residual low items are accepted or already tracked in test-quality/triple-review evidence. |

### Deterministic Delivery Guards
| Guard | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- |
| `todo_authority_guard.py --require-delivery-gates` | `passed` | `python3 delphi-ai/tools/todo_authority_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-immersive-hero-actions-centralization.md --require-delivery-gates --json-output foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/todo-authority-guard-delivery.json` | `Overall outcome: go`; violations none. | Authority/process guard satisfied for delivery-gate evidence. |
| `todo_completion_guard.py --require-delivery` | `passed` | `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-immersive-hero-actions-centralization.md --require-delivery --json-output foundation_documentation/artifacts/tmp/immersive-hero-actions-delivery-gates-20260531/todo-completion-guard.json` | `Overall outcome: go`; violations none. | Completion evidence guard satisfied for the local validation claim. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Disposition reason:** local validation gates are complete for the hero slice, but the full v0.2.0+8 package still needs the consolidated manual/navigation matrix and the newly approved Event cold-start deep-link bugfix lane before a single promotion-lane move.
- **Next path/status action:** keep this TODO in `foundation_documentation/todos/active/v0.2.0+8/` after commit/push; move the full v0.2.0+8 package together only after the consolidated matrix is green and the promotion-lane workflow is invoked.
- **Closeout path:** commit and push on the current reconcile branches, then promote with the full v0.2.0+8 package through the approved promotion lane.
