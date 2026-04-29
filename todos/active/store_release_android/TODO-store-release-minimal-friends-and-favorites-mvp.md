# TODO (Store Release): Minimal Contacts, Favorites, And Friends MVP

**Classification note (2026-04-17):** this lane is release-critical. The Android store release must ship a real contacts/favorites/friends core; this is not optional fast-follow work and it is no longer treated as a negotiable extraction exercise.

**Scope authority note (2026-04-17):** this TODO is the direct delivery authority for the store-release friends/favorites subset. It promotes the business-core behavior that cannot wait for the full `belluga_connections` package, while keeping the broader package convergence and non-release surfaces in `foundation_documentation/todos/active/vnext/TODO-vnext-connections-package.md`.

**Contact-management note (2026-04-18):** release-facing contact management is not deferred to the package TODO. This store-release lane owns the required product behavior for `Contatos`, `contact_match` acquisition/composition, invite-facing contact grouping, and the user-visible contact-management rules needed by `/convites/compartilhar` and adjacent release surfaces. The VNext package TODO owns only later extraction/convergence into a dedicated package boundary.

**Historical filename note (2026-04-17):** this artifact remains stored under the historical `friends-and-favorites` filename for continuity, but the canonical release scope is now the explicit `contact_match -> favorite -> friend` people-relationship baseline plus private `contact_groups` for invite organization.

**Canonical state note (2026-04-17):** `phone_hash` exists only to identify a matched person. Within this release lane, `contact_match` acquisition is owned by explicit `/contacts/import`: that match makes the person visible in `Contatos` and already invite-eligible. `favorite` is the explicit relation on the matched person's personal Account Profile. `friend` is derived from reciprocal favorites. `contact_groups` are user-private, tag-like organization over all in-app inviteable recipients and do not alter privacy or friendship semantics; this includes inviteables reached through `contact_match`, `favorite_by_you`, `favorited_you`, and `friend`. Unmatched local contacts may surface only through the app-local external-share branch; they are not part of the canonical inviteable list and are not groupable. Onboarding-driven late identity-materialization reconciliation plus its derived reflection surfaces (`Talvez você conheça`, informational "contact entered the app") are tracked separately in `foundation_documentation/todos/active/vnext/TODO-vnext-onboarding-identity-reconciliation-reflection.md`.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. Account-profile favorites and contact-import invite targeting already exist, but the release-critical contact/favorite/friend contract, user-private contact-group organization, and viewer-scoped exposure rules are not yet implemented as a coherent MVP lane.
**Owners:** Delphi (Product/Flutter) + Backend Team
**Goal:** ship the store-release contacts/favorites/friends core needed for invites, social proof, and privacy-safe profile exposure without widening into the full `belluga_connections` platform.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Framing Source & Story Slice

- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/mvp-with-app-backlog-realignment.md`
- **Primary story ID:** `ST-04`
- **Why this is the right current slice:** it captures the business-core people relationship layer required at launch while explicitly keeping broad platformization out of the Android gate.
- **Direct-to-TODO rationale:** safe. Business direction is already frozen: the release needs real contacts/favorites/friends behavior, but the existing TODO did not yet express that behavior concretely enough for execution.

## Contract Boundary

- This TODO owns the store-release contacts/favorites/friends core.
- It is explicitly smaller than the full `belluga_connections` package.
- It must deliver real behavior, not just scoping language.
- It is still the business owner for release-facing contact management, even where the eventual package boundary remains deferred.
- If execution starts pulling in broad people discovery, chat, generic social feed, or workspace analytics, stop and split that work back into VNext.

## Delivery Status Canon

- **Current delivery stage:** `Local-Implemented-Consumer-Gaps-Reopened`
- **Qualifiers:** `Business-Core`, `Cross-Stack`, `Release-Critical`, `Consumer-Gap-Reopened`, `Invite-Share-Regression`, `Refresh-Action-Required`
- **Next exact step:** execute the Home Favorites refresh child TODO and close the `/convites/compartilhar` regression where the sharing CTA remains stuck in the visible loading label `Gerando...`; add an explicit user action to refresh the friends/inviteable list before promotion. Keep ADB/device contact-permission smoke deferred to the consolidated ADB phase.

## References

- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/artifacts/execution-plans/store-release-wave2-social-consumer-gaps-orchestration-plan.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-home-favorites-refresh-regression.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-onboarding-identity-reconciliation-reflection.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-connections-package.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-invite-friends-polish.md`

## UX Study References (Exploratory Only, 2026-04-18)

- **Stitch project:** `Quóa` (`projects/2795929412847449154`)
- **Invite-composer study A:** `Compartilhar Convite` (`projects/2795929412847449154/screens/8aa13946072e477f84852ea0ab9057f3`)
- **Invite-composer study B:** `Compartilhar Convite (Power User)` (`projects/2795929412847449154/screens/40c74fa8c1ff4eb8ac075f3aa592fe45`)
- **Dedicated group-management study:** `Gestão de Grupos de Contato` (`projects/2795929412847449154/screens/89473a18a1ec41ea8f00e97570f7db36`)
- **Explicit non-reference:** `Convite Nativo` (`projects/2795929412847449154/screens/d8696f1d50244b0da6651545ec42f529`) is the received-invite accept/decline surface and must not be used as the baseline for `/convites/compartilhar` or `contact_groups` management.
- These studies are exploratory inputs only. They do not freeze UX decisions by themselves, do not override the business contract in this TODO, and must be revisited during implementation.
- The implementation phase should use these studies as starting material for refinement, then finalize the actual UX against the frozen business rules in `D-16` through `D-20`.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Decision promotion targets:**
  - viewer-scoped people exposure, contact-group rules, inviteable reasons, and friend semantics in `invite_and_social_loop_module.md`
  - Flutter release-surface responsibilities in `flutter_client_experience_module.md`

## Decision Baseline (Frozen 2026-04-17)

- [x] `D-01` This capability is store-release business core, not optional fast-follow.
- [x] `D-02` The release must ship a real people relationship core: `contact_match` acquisition, favorites on personal Account Profiles, reciprocal friend derivation, and viewer-scoped exposure. Existing non-personal account-profile favorites alone are not sufficient.
- [x] `D-03` Reciprocal friend remains the MVP friend model: `friend` is derived when two users favorite each other's personal Account Profiles. No separate friend-request workflow is introduced in this lane.
- [x] `D-04` Viewer-scoped exposure is part of the release contract for invite/social-proof surfaces. The MVP levels are `aggregate_only`, `capped_profile`, and `full_profile`.
- [x] `D-05` `/convites/compartilhar` is owned by this TODO as the release invite-targeting surface. The old polish-only lane is historical only.
- [x] `D-06` `POST /contacts/import` remains the canonical acquisition path for contact-match suggestions and invite targeting. Raw contact PII must not be stored server-side.
- [x] `D-07` Favorites on personal Account Profiles carry the people relationship semantics in this lane. Favorites on non-personal account profiles remain bookmark/affinity signals and must not derive friend state or richer people exposure by themselves.
- [x] `D-08` Personal-profile favorite semantics and reciprocal friend derivation depend on stable authenticated identity and therefore must align with the phone-OTP baseline. This TODO must not invent anonymous people-graph semantics.
- [x] `D-09` The full `belluga_connections` package remains VNext authority for package convergence, broad people discovery, richer graph consumers, and non-release surfaces. Store release still owns the release-critical contact-management behavior and may deliver the minimal business contract without full package extraction.
- [x] `D-10` The approved viewer-scoped exposure baseline promoted from the module lane is:
  - `public` target user -> `full_profile` on permitted invite/social-proof surfaces;
  - `friends_only` target user + target previously favorited the viewer's personal Account Profile -> `full_profile`;
  - `friends_only` target user + unilateral `contact_match(viewer -> target)` -> at most `capped_profile`;
  - direct invite counterparty context -> at most `capped_profile`, unless another approved rule grants more;
  - otherwise -> `aggregate_only`.
- [x] `D-11` `contact_groups` are user-private, tag-like organization over all in-app inviteable recipients. The same recipient may belong to multiple groups, and groups do not change privacy, favorite state, or friend state. This includes inviteables reached through `contact_match`, `favorite_by_you`, `favorited_you`, and `friend`. Unmatched local contacts are not groupable.
- [x] `D-12` Bulk invite selection across multiple contact groups must deduplicate the effective recipient set before invite creation and before quota counting by canonical recipient identity.
- [x] `D-13` `account_profile_type.capabilities.is_inviteable` is the canonical gating capability for invite surfaces. This applies beyond personal profiles and is the reason `favorite_by_you` / `favorited_you` become meaningful inviteable reasons for public favoritable profiles.
- [x] `D-14` `discoverable_by_contacts` is a separate privacy axis from `privacy_mode`, defaults to `true`, and controls whether imported contact hashes may materialize `contact_match`. The backend/data model may carry this before the privacy-settings UI exists.
- [x] `D-15` The store-release inviteable reason baseline is `contact_match | favorite_by_you | favorited_you | friend`. These reasons may coexist on one canonical recipient without creating duplicate list rows.
- [x] `D-16` `/convites/compartilhar` default presentation is one unified deduplicated inviteable list. Relation-type filters use the same chip/filter interaction pattern as Discovery and allow narrowing by the preserved inviteable reasons.
- [x] `D-17` Unmatched local contacts are a native-app-only external-share branch. They may use the invite code through WhatsApp direct-share when available or system-share fallback, but they are not part of the canonical inviteable list, relation filters, `contact_groups`, or web.
- [x] `D-18` `/convites/compartilhar` is action-first, not selection-first. Person rows prioritize immediate invite/share actions; group rows prioritize `Convidar grupo` / `Convidar todos` with optional drill-in for member selection. A home-style horizontal group rail is not part of this screen baseline.
- [x] `D-19` Onboarding-driven late identity-materialization reconciliation and its derived reflection surfaces (`Talvez você conheça`, informational "contact entered the app") are intentionally split out of this release lane and tracked by `TODO-vnext-onboarding-identity-reconciliation-reflection.md`. This TODO preserves only the explicit `/contacts/import` acquisition baseline.
- [x] `D-20` `contact_groups` require CRUD in V1, but group creation/rename/delete belongs to dedicated group-visualization or friends-management surfaces rather than `/convites/compartilhar`. Exact UX may be refined through Stitch studies without reopening the business contract.
- [x] `D-21` When a grouped recipient ceases to be inviteable, V1 removes that recipient from `contact_groups` automatically instead of retaining a disabled or hidden stale membership.
- [x] `D-22` Direct invite recipient identity is an approved breaking launch cutover to the recipient `Account Profile` surface. Pre-production user-targeted invite contracts, stored invite edges, and share-to-invite materialization/acceptance flows must be adapted to `receiver_account_profile_id`; backward compatibility with `receiver_user_id` is not required because invites, favorites, and friends have not been released to production.
- [x] `D-24` Favorites, invites, and friends are first-production capabilities in this release. The release lane must not preserve backward compatibility for old favorite/invite/friend data shapes, `receiver_user_id` invite targeting, user-only contact matches, or pre-release favorite stream/cache behavior.
- [x] `D-23` Future account-workspace memberships may authorize different acting users on behalf of the same Account Profile, but that authorization layer must not redefine canonical invite recipient identity. Response/acceptance permissions remain future workspace policy and must not be baked implicitly into the current recipient model.
- [x] `D-25` Audit, Claude, PR, and promotion reviews for this TODO must carry the zero-backward-compatibility premise. Compatibility requests for first-production favorites, invites, friends, contact groups, or contact-match inviteable behavior are out of scope and non-blocking unless they identify an independent launch risk unrelated to preserving pre-release contracts.

## Current Implementation Snapshot (Repository Scan 2026-04-17)

- **Account-profile favorites are already real:** Flutter uses `GET/POST/DELETE /favorites` for account-profile favorites, backed by the registry-based favorites lane.
  - Flutter evidence: `lib/infrastructure/dal/dao/laravel_backend/favorite_backend/laravel_favorite_backend.dart`
  - Laravel evidence: `../laravel-app/packages/belluga/belluga_favorites`
- **`/convites/compartilhar` already exists:** it is an authenticated invite-targeting surface and currently derives suggestions from contact import plus sent-invite status.
  - Route evidence: `lib/application/router/modular_app/modules/invites_module.dart`
  - Controller evidence: `lib/presentation/tenant_public/invites/screens/invite_share_screen/controllers/invite_share_screen_controller.dart`
- **Contact import already exists:** `POST /contacts/import` is implemented today and is used for invite targeting/matching.
  - Flutter evidence: `lib/infrastructure/dal/dao/laravel_backend/invites_backend/laravel_invites_backend.dart`
  - Laravel evidence: `../laravel-app/routes/api/packages/project_tenant_public_api_v1/invites.php`
- **A real friends graph does not exist yet:** `FriendsRepository` is still deterministic placeholder state and is not backed by a dedicated backend relationship graph.
  - Evidence: `lib/infrastructure/repositories/friends_repository.dart`
- **Personal-profile favorite semantics are not implemented as a release path yet:** current favorites mutation/query code is account-profile-scoped, but does not yet express the additional people semantics required for matched personal profiles, reciprocal-friend derivation, or viewer-scoped exposure.
- **User-private contact groups are not implemented as a first-class release surface yet:** there is no current tag-like contact grouping flow or documented bulk-dedup invite behavior wired into `/convites/compartilhar`.
- **Explicit registry inviteability is now frozen in docs, but implementation remains pending:** canonical bootstrap/profile-type contracts now define `is_inviteable` alongside existing capabilities, yet runtime registry payloads and consumers still need the actual implementation cut.
- **Contact discovery privacy is now frozen in docs, but implementation remains pending:** the contract now distinguishes `privacy_mode` from `discoverable_by_contacts`, but the data model/runtime flow still need the persisted field and behavior.
- **Unified inviteable list semantics are now frozen in docs, but implementation remains pending:** `/convites/compartilhar` exists, but the default “all together, deduplicated” presentation plus Discovery-style relation filters still need the actual product delivery.
- **External-contact share branch is now frozen in docs, but implementation remains pending:** the release contract now distinguishes in-app inviteables from app-local unmatched contacts, but the native auxiliary share branch (app-only, non-groupable, non-web) still needs explicit implementation.
- **Action-first composer semantics are now frozen in docs, but implementation/UX exploration remain pending:** current invite share UI is still a simple list and does not yet reflect the direct-action group/person behavior now defined for `/convites/compartilhar`.
- **Contact-group CRUD ownership is now frozen in docs, but implementation/UX exploration remain pending:** V1 requires create/rename/delete plus membership management, but that CRUD belongs to dedicated group/friends-management surfaces rather than `/convites/compartilhar`; exact UX is still pending study.
- **Quóa exploratory studies now exist for the pending UX work, but are intentionally non-authoritative until implementation:** use `Compartilhar Convite`, `Compartilhar Convite (Power User)`, and `Gestão de Grupos de Contato` only as exploratory references; revisit and refine them during execution instead of treating them as closed design decisions.
- **The received-invite decision study is intentionally excluded from this lane's UX baseline:** `Convite Nativo` is not a reference for the invite composer or group-management surfaces.
- **Contact-group membership cleanup is now frozen in docs, but implementation remains pending:** when a grouped recipient ceases to be inviteable, V1 must remove that recipient from group memberships automatically.
- **Invite recipient identity, invite persistence, and share-to-invite conversion are still user-centric in current implementation/contracts and now require explicit launch cutover:** release delivery must move direct invites, stored invite edges, duplicate/credited-acceptance semantics, and share-code materialization/acceptance to canonical `Account Profile` targets. This is a pre-production cutover, not a production migration; backward compatibility with raw `User` targeting is not required.
- **Onboarding-driven identity-materialization reflection was split into follow-up ownership:** late reconciliation after canonical identity materialization, future `Talvez você conheça`, and advisory "contact entered the app" notifications are no longer owned by this release TODO and now live in `TODO-vnext-onboarding-identity-reconciliation-reflection.md`.
- **Anonymous-account-profile favorites still have an implementation gap against the frozen policy baseline:** store-release web-to-app policy now allows anonymous app favorites, but current Flutter/Laravel favorites mutation paths still assume authenticated mutation for the existing account-profile lane. That alignment remains owned by `TODO-store-release-web-to-app-conversion-gate.md`.
- **Favorites also have zero backward-compatibility burden:** account-profile favorites, personal-profile favorites, friend derivation, Home Favorites streams, and favorite inviteable reasons are first-production release capabilities. Pre-release local storage/cache/API behavior may be replaced outright when it conflicts with the launch contract.
- **Review and promotion rule:** any reviewer, Claude run, PR comment, or promotion note asking for backward compatibility in favorites, invites, friends, contact groups, or contact-match inviteable behavior must be treated as out of scope for this release lane unless it raises an independent security, integrity, data-loss, tenant-isolation, or release-regression risk.

## Scope

- [ ] Deliver a canonical authenticated favorite edge on personal Account Profiles for the release.
- [ ] Deliver reciprocal friend derivation from mutual favorites on personal Account Profiles.
- [ ] Deliver viewer-scoped people exposure resolution for release surfaces using `aggregate_only | capped_profile | full_profile`.
- [ ] Keep `POST /contacts/import` as the source for contact-match acquisition, contacts-list composition, and invite targeting.
- [ ] Make `contact_match` enough to place a person in `Contatos` and allow invite targeting without requiring favorite first.
- [ ] Deliver the release-facing contact-management rules and surfaces needed for `Contatos`, inviteable composition, and private group organization without waiting for a dedicated package extraction.
- [ ] Introduce user-private `contact_groups` with tag semantics so the same contact may belong to multiple groups without changing social state.
- [ ] Keep `contact_groups` scoped to in-app inviteable recipients only; unmatched local contacts must stay outside groups.
- [ ] Deliver `contact_groups` CRUD in dedicated group-visualization or friends-management surfaces, not inside `/convites/compartilhar`.
- [ ] Introduce `account_profile_type.capabilities.is_inviteable` as the canonical type gate for invite surfaces.
- [ ] Introduce `discoverable_by_contacts` with backend/default `true` so hash discovery is explicit and separable from `privacy_mode`.
- [ ] Introduce viewer-scoped `inviteable_reasons` so one recipient may carry `contact_match`, `favorite_by_you`, `favorited_you`, and `friend` without duplicate rows.
- [ ] Cut over direct invite recipient identity to `Account Profile` and retire `receiver_user_id` targeting from the release contract.
- [ ] Upgrade `/convites/compartilhar` so it becomes the proper release-facing invite-friends/share surface for this social core, not just a thin contact-import shell.
- [ ] Ensure `/convites/compartilhar` sharing CTA actions always leave the visible loading label `Gerando...` after success, handled error, retryable error, or navigation/re-entry.
- [ ] Expose an explicit `Atualizar lista de amigos` / refresh action on `/convites/compartilhar` that reloads the backend-computed inviteable list without requiring route restart.
- [ ] Separate native-app unmatched external-share targets from the canonical in-app inviteable list and keep them out of relation filters, contact groups, and web.
- [ ] Preserve and clearly separate non-personal account-profile favorites from the personal-profile favorite/friend lane.
- [ ] Wire the minimal social-proof outputs needed by the release-facing invite/event/profile surfaces that depend on “friend” semantics.

## Out of Scope

- [ ] Full `belluga_connections` package extraction/convergence.
- [ ] Generic people discovery product, social feed, messaging/chat, or follower platform work.
- [ ] Workspace analytics/dashboards for connections.
- [ ] Broad profile-privacy redesign outside the frozen exposure rules above.
- [ ] Replacing the OTP identity baseline or moving people-relationship semantics onto anonymous identities.
- [ ] Onboarding-driven late identity-materialization reconciliation and its derived reflection surfaces; these are tracked by `TODO-vnext-onboarding-identity-reconciliation-reflection.md`.

## Dependencies & Sequencing

- [ ] `DEP-01` `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md` remains a hard dependency for stable authenticated user identity and contact-match reliability.
- [ ] `DEP-02` `foundation_documentation/todos/active/store_release_android/TODO-store-release-web-to-app-conversion-gate.md` still owns anonymous app favorites alignment for account-profile favorites and must remain consistent with this TODO.
- [x] `DEP-03` `foundation_documentation/todos/active/vnext/TODO-vnext-connections-package.md` remains the authority for everything beyond this minimal store-release contract.

## Execution Tracks

### A) Backend People Relationship Core
- [ ] Introduce canonical authenticated favorite ownership on personal Account Profiles.
- [ ] Implement reciprocal friend derivation from mutual personal-profile favorites.
- [ ] Implement `discoverable_by_contacts` with backend/default `true` so contact discovery is explicit and independently governable from `privacy_mode`.
- [ ] Implement viewer-scoped exposure resolution for release surfaces using the frozen `aggregate_only | capped_profile | full_profile` contract.
- [ ] Keep the implementation Mongo-safe and hot-path friendly; do not require request-path graph traversal for release reads.

### B) Contact Match, Groups, And Invite Surface
- [ ] Preserve `POST /contacts/import` as the canonical contact-match acquisition path.
- [ ] Make matched contacts render as invite-eligible `Contatos` even before favorite is created.
- [ ] Keep `Contatos` and release-facing contact management as explicit store-release behavior, not as a package-only follow-up concern.
- [ ] Make inviteable eligibility depend on both viewer-scoped relation reasons and `profile_type.capabilities.is_inviteable`.
- [ ] Introduce private `contact_groups` with tag semantics for contact organization and bulk invite selection.
- [ ] Cut over duplicate detection, credited-acceptance semantics, direct-invite recipient resolution, persisted invite edges, and share-code materialization/acceptance to canonical `receiver_account_profile_id` behavior.
- [ ] Remove grouped recipients automatically when they cease to be inviteable.
- [ ] Make multi-group invite selection deduplicate recipients by canonical resolved recipient before quota counting and invite creation.
- [ ] Make `/convites/compartilhar` consume the release relationship model explicitly: in-app contact matches, group membership, favorite/friend state where available, sent-invite status, inviteable reasons, and viewer-scoped resumes.
- [ ] Make `/convites/compartilhar` reloadable by the user through an explicit refresh action for friends/inviteables, with deterministic loading/error/empty behavior.
- [ ] Prevent duplicate or stuck invite generation state: repeated share taps must be guarded, errors must release the in-flight state, and re-entering the screen must not inherit stale `Gerando...`.
- [ ] Keep unmatched local contacts on a separate native-app auxiliary share branch using invite codes, without promoting them into canonical inviteable rows.
- [ ] Ensure invite-target suggestions remain privacy-safe and deterministic.

### C) Flutter Release Surfaces
- [ ] Keep non-personal account-profile favorites working and visually distinct from the people relationship lane.
- [ ] Introduce the release-approved `contact_match -> favorite -> friend` data to the relevant invite/social-proof surfaces without collapsing everything into one generic list.
- [ ] Render `/convites/compartilhar` with one default deduplicated in-app inviteable list plus Discovery-style relation filter chips sourced from backend-preserved inviteable reasons.
- [ ] Add an explicit refresh control for the inviteable/friends list that calls the controller/repository refresh path and updates the visible list state.
- [ ] Ensure each invite/share row exposes a bounded in-flight state per target or action, and that the visible sharing CTA cannot remain indefinitely stuck on `Gerando...`.
- [ ] Keep unmatched external contacts, when surfaced on native app, in a separate auxiliary share branch outside the filtered in-app inviteable list.
- [ ] Keep `/convites/compartilhar` action-first: person rows support immediate invite/share, and group rows support immediate `Convidar grupo` / `Convidar todos` plus optional drill-in for member selection.
- [ ] Keep group CRUD out of `/convites/compartilhar`; use dedicated group/friends-management surfaces, with detailed UX to be refined through Stitch studies.
- [ ] Revisit the exploratory Quóa studies during implementation and finalize the actual invite-composer/group-management UX without reopening the frozen business contract.
- [ ] Ensure auth boundaries remain explicit: non-personal account-profile favorites may follow the separate anonymous-favorites policy lane, but personal-profile favorite/friend actions must depend on authenticated identity.

### D) Documentation And Contract Consolidation
- [ ] Promote the resulting friend/exposure decisions into the canonical module docs named above.
- [ ] Update any contradictory backlog language that still treats this lane as optional or as mere extraction.
- [ ] Keep `belluga_connections` VNext scope clearly separated after the release subset is promoted.

## Acceptance Criteria

- [ ] Authenticated users can create and remove favorites on personal Account Profiles through the canonical release path.
- [ ] Mutual personal-profile favorites produce deterministic friend derivation with no separate request workflow.
- [ ] Release invite/social-proof surfaces resolve people visibility through the frozen viewer-scoped exposure levels.
- [ ] A matched contact is visible in `Contatos` and inviteable without prior favorite.
- [ ] Release-facing contact management for `Contatos` and private contact organization is delivered as product behavior even if package convergence remains deferred.
- [ ] `favorite_by_you` and `favorited_you` can make a profile inviteable when its type is `is_inviteable=true`, without implying `Contato`.
- [ ] Contact groups behave as private tags over in-app inviteable recipients, allow multi-group membership, may combine relations such as `contact_match`, `favorite_by_you`, `favorited_you`, and `friend`, and deduplicate recipients before invite creation/quota counting.
- [ ] Users can create, rename, and delete contact groups through dedicated management surfaces without turning `/convites/compartilhar` into a management screen.
- [ ] Direct invite contracts, persisted invite state, and share-materialized invite flows treat the recipient surface as `Account Profile`; `receiver_user_id` payloads are not part of the release contract.
- [ ] `/convites/compartilhar` shows one default unified deduplicated in-app list and supports Discovery-style relation filters without duplicating recipients across sections.
- [ ] `/convites/compartilhar` includes a visible action to refresh the friends/inviteable list and updates the list without leaving/reopening the screen.
- [ ] Invite/share CTA state is bounded: `Gerando...` appears only while the action is actually in flight and is cleared after success, handled failure, retry, or screen re-entry.
- [ ] Unmatched local contacts, when surfaced on app, use a per-contact external share action and do not appear inside `contact_groups`, relation filters, or web invite surfaces.
- [ ] Group targets support direct `Convidar grupo` / `Convidar todos` plus optional drill-in/member selection without forcing selection-first UX.
- [ ] When a grouped recipient ceases to be inviteable, V1 removes that recipient from all groups automatically.
- [ ] `/convites/compartilhar` behaves as the real release invite-friends/share surface for this lane, not as a generic unfinished shell.
- [ ] Non-personal account-profile favorites continue to function and remain separate from the personal-profile favorite/friend semantics.
- [ ] No raw contact phone/email values are stored server-side as part of this lane.

## Definition of Done

- [ ] The store-release contacts/favorites/friends core is explicitly implemented as a business-complete MVP slice.
- [ ] The release no longer depends on the broad VNext package TODO to explain mandatory social behavior.
- [ ] The release no longer depends on the broad VNext package TODO to justify required contact-management behavior for `Contatos` and `/convites/compartilhar`.
- [ ] The relationship between `contact_match`, `discoverable_by_contacts`, personal-profile favorites, reciprocal friends, inviteable reasons, contact groups, non-personal account-profile favorites, and viewer-scoped exposure is explicit in code and docs.
- [ ] Remaining non-release package work stays clearly in `TODO-vnext-connections-package.md`.

## Validation Steps

- [ ] Backend automated: contact import -> match -> invite eligibility without favorite -> personal-profile favorite -> reciprocal friend derivation -> exposure resolution.
- [ ] Backend automated: multi-group invite selection deduplicates recipients by canonical resolved recipient before quota counting and duplicate-invite handling.
- [ ] Backend automated: duplicate invite prevention, credited-acceptance semantics, and share-materialized invite creation are keyed by canonical recipient Account Profile identity.
- [ ] Backend automated: when a grouped recipient ceases to be inviteable, the system removes that recipient from all `contact_groups` automatically.
- [ ] Backend automated: `discoverable_by_contacts=false` blocks hash-only discovery while `privacy_mode` alone does not disable contact discovery when the flag remains `true`.
- [ ] Backend automated: release surfaces do not overexpose `friends_only` identities outside the frozen rules.
- [ ] Flutter automated: `/convites/compartilhar` renders the new release relationship states deterministically and preserves invite-send behavior.
- [ ] Flutter automated: the default inviteable list is deduplicated and relation filter chips narrow by backend-provided inviteable reasons using the Discovery interaction pattern.
- [ ] Flutter automated: `/convites/compartilhar` refresh action reloads the friends/inviteable list, updates loading/error/empty/content state, and preserves active relation filters deterministically.
- [ ] Flutter automated: sharing CTA does not remain stuck in `Gerando...` after repository success, handled error, thrown error, rapid repeat taps, or screen re-entry.
- [ ] Flutter automated: unmatched external share targets, when present on native app, remain outside the filtered in-app inviteable list, outside `contact_groups`, and absent on web invite surfaces.
- [ ] Flutter automated: current invite UX remains stable after the backend/API cutover to `Account Profile`-based recipient identity.
- [ ] Flutter automated: group CRUD lives on dedicated management surfaces rather than `/convites/compartilhar`, and grouped recipients that cease to be inviteable disappear from group membership after refresh.
- [ ] Flutter automated: non-personal account-profile favorites remain stable and are not conflated with the people relationship lane.
- [ ] Manual smoke: authenticated user imports contacts, sees matched contacts in `Contatos`, refreshes the friends/inviteable list from `/convites/compartilhar`, invites a matched contact without favoriting first, verifies `Gerando...` clears after success/error on the sharing CTA, sees `favorite_by_you` / `favorited_you` entries become inviteable when the target type is `is_inviteable=true`, observes reciprocal-friend behavior when applicable, creates/uses groups through dedicated management surfaces, sees groups contain mixed in-app inviteable relations without duplicate recipients, confirms recipients that cease to be inviteable are removed automatically from groups, sees unmatched local contacts stay on the separate native auxiliary share branch, then uses relation filter chips on the unified in-app inviteable list and sees privacy-safe resume rendering on the approved release surface.

## Execution Lane Tracking

- **Local implementation branches:** `flutter-app:<planned>`, `laravel-app:<planned>`, `belluga_now_docker:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Local Delivery Notes (2026-04-28)

- **Implemented backend contract:** `POST /contacts/import` now returns profile-scoped inviteable contact matches when a discoverable inviteable personal Account Profile exists. Any current user-only match behavior is pre-release residue and must be removed or routed outside the canonical inviteable list before release closure.
- **Implemented backend contract:** `GET /contacts/inviteables` returns a backend-computed unified list deduplicated by `receiver_account_profile_id`, merging `contact_match`, `favorite_by_you`, `favorited_you`, and reciprocal `friend`.
- **Implemented backend contract:** `GET|POST|PATCH|DELETE /contact-groups` stores user-private groups over inviteable `receiver_account_profile_id` members and prunes members that cease to be inviteable.
- **Implemented invite recipient cutover:** direct invite creation accepts `receiver_account_profile_id` and persists it on invite edges. Any remaining `receiver_user_id`/`contact_hash` invite-write path is pre-release residue and current tests must be updated to the launch contract instead of preserving it.
- **Implemented Flutter contract:** `/convites/compartilhar` consumes the backend-computed inviteable list, preserves `receiver_account_profile_id`, sends direct invites by account profile identity, and exposes Discovery-style relation filter chips for `contact_match`, `favorite_by_you`, `favorited_you`, and `friend`.
- **Implemented Flutter group management:** added a dedicated contact-group management surface outside `/convites/compartilhar`, with create/rename/delete and membership editing over backend inviteable `receiver_account_profile_id` recipients.
- **Corrected Flutter delivery gate:** the contact-group surface was reworked to satisfy domain value-object, repository payload, controller ownership, route, analyzer, and web-build gates. Worker/subagent closure rules were updated so future checkpoints cannot be accepted without the applicable analyzer/build evidence for their owned slice.
- **Resolved audit blockers:** T3 triple-audit rounds 01 through 04 findings were resolved locally and recorded under `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-*/resolution.md`.
- **Triple audit gate:** round 05 returned zero findings across elegance, performance, and test-quality lanes. The runner classified a non-material `recommended_path_conflict`; Delphi adjudicated it as resolved in `foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/round-05/resolution.md` because all lanes recommended proceeding.
- **Claude CLI auxiliary review:** the round 05 attempt was blocked by account limit until the reset window (`6pm America/Sao_Paulo`). Per user instruction on 2026-04-28, Claude CLI is treated as a gate only when available and returning a substantive response; tool unavailability is recorded as operational evidence but does not block advancing. Evidence is recorded in `foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-05.md`.
- **Deferred runtime evidence:** ADB/device contact-permission smoke remains intentionally deferred to the consolidated ADB phase per orchestration plan.
- **Post-local QA consumer gap (2026-04-29):** user QA found that app-side favorite mutations do not refresh the Home Favorites section. The core social/favorites implementation remains useful, but promotion is blocked until the Home consumer refresh gap is closed through `TODO-store-release-home-favorites-refresh-regression.md`.
- **Post-local QA invite-share regression (2026-04-29):** user QA found that entering the invite screen can leave the sharing CTA stuck on the visible loading label `Gerando...`. This blocks promotion of `/convites/compartilhar` until the controller/repository in-flight state is bounded and covered by race/error/re-entry tests.
- **Post-local QA refresh requirement (2026-04-29):** `/convites/compartilhar` must expose a visible action to refresh the friends/inviteable list. This is release-facing contact management, not visual-only invite polish.

## Post-Local QA Regression Matrix (2026-04-29)

| Task / Behavior | Failure Observed | Required Automated Evidence | Runtime / Manual Evidence | Owner TODO |
| --- | --- | --- | --- | --- |
| Sharing CTA bounded in-flight state | Entering the invite screen leaves the sharing button stuck in `Gerando...`. | Flutter controller/widget tests for success, handled error, thrown error, rapid repeat tap, and screen re-entry clearing in-flight state. | ADB final: open invite screen, generate/share invite, verify `Gerando...` clears and retry remains possible. | This TODO |
| Refresh friends/inviteables action | User has no explicit way to refresh the friends list from `/convites/compartilhar`. | Flutter controller/widget tests proving refresh calls backend inviteables, updates loading/error/empty/content state, and preserves filters. | ADB final: tap `Atualizar lista de amigos`, observe visible refresh and updated inviteable list. | This TODO |
| Refresh action and send action race safety | Refresh during invite generation could overwrite state or leave duplicate loading flags. | Frontend race-condition matrix: refresh while send is in flight, repeated refresh, repeated send, and stale response ordering. | ADB/manual replay if automated race probe cannot run on device before final phase. | This TODO |

## Completion Evidence Matrix (Local, Non-ADB)

| Criterion | Evidence | Status |
| --- | --- | --- |
| Contact import returns profile-scoped matches and respects `discoverable_by_contacts` | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php tests/Feature/Invites/InvitesFlowTest.php tests/Feature/Favorites/FavoritesControllerTest.php` | Passed 2026-04-28: 52 tests, 358 assertions |
| Unified inviteable list merges contact/favorite/friend reasons without duplicates | `Tests\\Feature\\Invites\\StoreReleaseSocialGraphTest::test_inviteable_contacts_merge_contact_match_favorites_and_friend_reasons_without_duplicates` | Passed |
| Contact groups dedupe and prune stale recipients | `Tests\\Feature\\Invites\\StoreReleaseSocialGraphTest::test_contact_groups_dedupe_members_and_prune_recipients_that_cease_to_be_inviteable` | Passed |
| Contact group CRUD/privacy is owner-scoped and validates caps | `Tests\\Feature\\Invites\\StoreReleaseSocialGraphTest::test_contact_group_crud_is_owner_private_and_validated` | Passed |
| Direct invites target account-profile recipient identity | Laravel `StoreReleaseSocialGraphTest` + Flutter `invites_repository_test.dart` | Passed |
| `/convites/compartilhar` consumes backend inviteables and preserves profile identity | `fvm flutter test test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/repositories/invites_repository_push_payload_test.dart test/presentation/tenant/invites/screens/contact_group_management/controllers/contact_group_management_controller_test.dart test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/widgets/invite_share_relation_filter_chips_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/common/auth/screens/auth_login_screen/auth_login_controller_contract_test.dart` | Passed 2026-04-28: 24 tests; reopened 2026-04-29 for stuck share CTA and explicit refresh action coverage |
| `/convites/compartilhar` sharing CTA clears `Gerando...` | Pending focused Flutter controller/widget/race tests for success/error/re-entry. | `Blocked/Pending` |
| `/convites/compartilhar` refreshes friends/inviteables list by user action | Pending focused Flutter controller/widget tests for explicit refresh action and state transitions. | `Blocked/Pending` |
| Dedicated group-management Flutter surface | `contact_group_management_controller_test.dart` and `contact_group_management_screen_test.dart` | Passed |
| Flutter architecture/analyzer gate | `fvm dart analyze --format machine` | Passed 2026-04-28, no diagnostics |
| Flutter web build gate | `bash scripts/build_web.sh ../web-app dev`; `sha256sum ../web-app/main.dart.js` | Passed 2026-04-28; `main.dart.js` SHA-256 `f499dd08b42f71c4f11292828c1628a2d312d4f0b2fee42ad1061e7299dde584` |
| PHP style gate | `docker compose exec -T app ./vendor/bin/pint --test ...` over T3 PHP files | Passed 2026-04-28: 6 files |
| Exact lookup anti-pattern audit | `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --path ...InviteablePeopleService.php --path ...InviteIdentityGatewayAdapter.php --path ...InviteMutationService.php --path ...InviteShareService.php` | Passed 2026-04-28: no high or medium findings |
| Triple audit round 05 | `python3 delphi-ai/skills/audit-protocol-triple-review/scripts/triple_audit_session.py merge --session foundation_documentation/artifacts/t3-minimal-friends-triple-audit-20260428T1655Z/session.json` + `round-05/resolution.md` | Zero findings across all lanes; non-material recommended-path conflict adjudicated resolved |
| Claude CLI auxiliary review | `timeout 300s claude -p ... > foundation_documentation/artifacts/claude-cli-reviews/T3-minimal-friends-cli-review-round-05.md` | Operationally unavailable 2026-04-28: account limit until `6pm America/Sao_Paulo`; non-blocking per user instruction unless the CLI returns substantive findings |

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Personal-profile favorite edge + reciprocal friend derivation | `local` | `pending` | `pending` | `pending` | `Local-Implemented; Home Favorites consumer gap reopened; ADB deferred` |
| Viewer-scoped exposure enforcement on release invite/social-proof surfaces | `local` | `pending` | `pending` | `pending` | `Local-Implemented for inviteable/contact surfaces; Home Favorites consumer gap reopened; ADB deferred` |
| `/convites/compartilhar` + contact-group bulk invite convergence | `local` | `pending` | `pending` | `pending` | `Local-Implemented but QA-reopened; sharing CTA stuck in Gerando... and explicit friends-list refresh action remain pending; analyzer/tests/web build from 2026-04-28 no longer close this row` |

## Profile Scope & Handoffs

- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity

- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint`
- **Why this level:** the scope is deliberately bounded, but it still crosses backend ownership, Flutter invite/favorites surfaces, identity/auth baseline, and privacy/exposure semantics.
