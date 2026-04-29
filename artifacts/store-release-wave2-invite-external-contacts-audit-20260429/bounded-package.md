# Store Release Wave 2 Invite External Contacts Audit Package

## Package Identity

- **Package:** `store-release-wave2-invite-external-contacts-audit-20260429`
- **Scope:** Flutter `/convites/compartilhar` delta for unmatched local phone contacts.
- **Governing TODO:** `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- **Execution plan:** `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`
- **Zero backward compatibility premise:** invites, favorites, friends, contact groups, and contact-match inviteable behavior are first-production release capabilities. Reviewers must not request compatibility with pre-release shapes unless they identify an independent launch risk.
- **ADB policy:** final native-device contact/share smoke remains deferred to the consolidated Wave 2D phase.

## Accepted Product / UX Decision

- The accepted invite composer layout is the Stitch Study C hierarchy: default Belluga inviteable list first, then a compact `Contatos do telefone` entry that opens a drill-in/bottom sheet for unmatched local contacts.
- Stitch is only a layout/hierarchy reference. The Flutter implementation must use the existing Theme/colorScheme and local component conventions, not copy Stitch colors or local decorative styles.
- Unmatched local contacts are native-app-only external-share targets. They must not enter the canonical in-app inviteable list, relation filters, `contact_groups`, or web runtime.

## Package-First Assessment

- `bash delphi-ai/tools/query_packages.sh --project-root .. --search invite` returned 0 package candidates.
- `bash delphi-ai/tools/query_packages.sh --project-root .. --search contacts` returned 0 package candidates.
- `bash delphi-ai/tools/query_packages.sh --project-root .. --search share` returned 0 package candidates.
- Existing dependencies are reused: `share_plus` for system share fallback and `url_launcher` for WhatsApp direct handoff.

## Changed Surfaces

### Flutter Source

- `lib/application/invites/invite_contact_import_hashes.dart`
  - Shared application helper for the same contact-hash normalization used by import payloads and local unmatched-contact exclusion.
- `lib/application/invites/invite_contact_phone_normalization.dart`
  - Shared phone normalization for contact-import hash variants and preferred WhatsApp `wa.me` targets.
- `lib/infrastructure/repositories/invites_repository.dart`
  - Reuses the shared hash helper so repository import payloads and controller local exclusion use one normalization algorithm.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_external_contact_share_target.dart`
  - Presentation view model for app-local external-share targets.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
  - Adds `externalContactShareTargetsStreamValue`.
  - Exposes unmatched local contacts only on non-web runtime.
  - Excludes any local contact whose hash was returned by backend import as an in-app match.
  - Treats contact-import failure as unavailable classification and leaves external targets empty instead of failing open.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart`
  - Renders compact `Contatos do telefone` entry only when unmatched targets and a valid share URI exist.
  - Opens the external-contact bottom sheet.
  - Uses normalized WhatsApp direct handoff when a phone exists, with `SharePlus` system-share fallback.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_phone_contacts_entry.dart`
  - Compact Theme-based entry point for local contacts.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_external_contacts_sheet.dart`
  - Theme-based drill-in list with per-contact external share action.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_footer.dart`
  - Removes local purple/black color styling in favor of Theme tokens.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/widgets/invite_share_friend_card.dart`
  - Removes local green/orange/blue/white CTA styling in favor of Theme tokens.

### Flutter Tests

- `test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart`
  - Adds mobile-only unmatched local contact exposure test.
  - Adds web-runtime exclusion test.
  - Adds import-classification failure test proving external contacts do not fail open.
- `test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart`
  - Adds widget coverage for compact entry, drill-in, WhatsApp action, and unchanged Belluga invite rows.
  - Adds widget coverage that taps the external share action, asserts normalized `wa.me` command dispatch, and asserts system-share fallback.

## Frontend / Consumer Matrix

| Producer / Contract Surface | Expected Consumer | Evidence | Waiver |
| --- | --- | --- | --- |
| Contact import hash normalization | Flutter invite repository and invite-share controller local matching | Shared helper used by repository import payload and controller local unmatched exclusion; repository hash tests remain covered in expanded suite. | none |
| Contact phone normalization | Contact import hash variants and external WhatsApp target | Shared helper normalizes local BR phone `(27) ...` to `55...` for `wa.me`; widget test asserts normalized URI. | none |
| Unmatched local contact targets | `/convites/compartilhar` mobile runtime only | Controller test exposes exactly one unmatched target after backend import matches another contact; web-runtime test returns empty. | Web intentionally excluded by `D-17`. |
| Contact import failure classification | `/convites/compartilhar` external branch | Controller test proves local contacts are not exposed externally when import classification fails. | none |
| External contact UI branch | Invite composer screen | Widget test proves compact `Contatos do telefone` entry, no local contact in canonical list before drill-in, bottom sheet reveals contact and `WhatsApp` action. | ADB native share sheet deferred to Wave 2D. |
| External contact share command | Invite composer screen | Widget tests tap the external action and assert normalized `wa.me` dispatch plus `SharePlus` fallback. | ADB native share sheet deferred to Wave 2D. |
| Canonical Belluga inviteable list | Relation filters and direct invite rows | Widget test keeps two `Convidar` rows after bottom sheet, proving external contacts did not become in-app inviteable rows. | none |
| Theme adherence | Invite share card/footer/external-contact widgets | Analyzer gate passes after removing local hard-coded CTA/icon colors and direct Navigator use. | none |

## Test Matrix

| Task / Behavior | Automated Evidence | Browser/Web Evidence | ADB / Device Evidence | Status |
| --- | --- | --- | --- | --- |
| Unmatched local contacts appear only in native external branch | `fvm flutter test test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` | Web build passes; web runtime controller test excludes targets. | Final Wave 2D contact-permission/share smoke. | `local-passed / ADB-deferred` |
| Contact import failure does not expose unclassified locals | Focused controller test `does not expose external phone contacts when import classification fails`. | n/a | Final Wave 2D contact refresh smoke. | `local-passed / ADB-deferred` |
| Matched contacts stay in Belluga inviteable list, not external branch | Same focused controller test; fake backend returns contact hash for matched contact and only unmatched contact is exposed externally. | n/a | Final Wave 2D contact refresh smoke. | `local-passed / ADB-deferred` |
| External branch does not add `Convidar` rows or relation-filter rows | Widget test expects two `Convidar` rows after opening external sheet. | n/a | Final Wave 2D visual smoke. | `local-passed / ADB-deferred` |
| External share action dispatches correctly | Widget tests assert normalized `wa.me/5527...` launch, invite URL payload, external launch mode, and system-share fallback when direct launch fails. | n/a | Final Wave 2D native share sheet smoke. | `local-passed / ADB-deferred` |
| Invite/share occurrence/contact regressions remain stable | Expanded Flutter suite: 84 tests across invites, occurrence, presence, Home Favorites, event detail, and invite share. | `bash scripts/build_web.sh ../web-app dev` passed after Round 01 fixes; `web-app` is derived and not committed. | Final Wave 2D. | `local-passed / ADB-deferred` |
| Flutter architecture/analyzer gate | `fvm dart analyze --format machine` initially caught three issues; after fixes it passed with no diagnostics. | n/a | n/a | `passed` |

## Validation Evidence

- Fail-first evidence: initial focused test run failed before implementation because `InviteShareScreenController(isWebRuntime: ...)` and `externalContactShareTargetsStreamValue` did not exist.
- Focused invite-share suite: `fvm flutter test test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` passed: 18 tests.
- Expanded Wave 2 Flutter suite: `fvm flutter test test/application/invites/invite_from_event_factory_test.dart test/infrastructure/dal/laravel_user_events_backend_test.dart test/infrastructure/repositories/user_events_repository_test.dart test/infrastructure/dal/dao/invites/invites_response_decoder_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/home/screens/tenant_home_screen/controllers/tenant_home_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/favorites_section_builder_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/controllers/immersive_event_detail_controller_test.dart test/presentation/tenant_public/schedule/screens/immersive_event_detail/immersive_event_detail_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` passed: 84 tests.
- Analyzer: `fvm dart analyze --format machine` passed after resolving application/domain placement, public-class split, and router close policy.
- Web build: `bash scripts/build_web.sh ../web-app dev` passed in 969.8s before Round 01 fixes and passed again in 133.0s after the fixes; output is derived.
- Round 01 triple audit resolution: `triple-audit/round-01/resolution.md` records `ELEGANCE-001`, `ELEGANCE-002`, and `TQ-01` as resolved.

## Audit Questions

1. Is the external-contact branch structurally separated from canonical in-app inviteables, relation filters, and contact groups?
2. Is the shared hash helper placed at the correct layer and reused without introducing domain primitive-rule violations?
3. Does the UI implementation follow the accepted Study C hierarchy while staying Theme-based?
4. Are the tests capable of catching the original backend-without-front and matched-vs-unmatched contact gaps?
5. Are any remaining risks true release blockers rather than non-blocking polish or backward-compatibility requests?
