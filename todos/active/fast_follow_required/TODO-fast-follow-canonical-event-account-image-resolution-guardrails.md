# TODO (Fast Follow): Canonical Event and Account Image Resolution Guardrails

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Local-Implemented / CI Equivalent green on 2026-05-23.
**Owners:** Delphi (Laravel)
**Goal:** centralize event/account image resolution in Laravel and add guardrails so invite pushes, public metadata, and future backend consumers cannot silently reimplement fallback order.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context

An invite push image regression was identified after runtime testing: the event has a cover image and linked account imagery, but the invite notification resolved the location image. The immediate root cause is duplicated fallback logic in invite target resolution: it looked only at legacy `thumb.url` / `thumb.uri` and venue media, while the real event cover upload is stored as `thumb.data.url`.

The broader architecture issue is that multiple Laravel consumers can locally remember or reimplement fallback order. That is fragile. Event image resolution and account-profile image resolution must be owned by central resolvers, and consumers must either call those resolvers or consume fields already resolved by them.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this is one bounded backend hardening slice discovered from a production-visible invite push image bug.
- **Direct-to-TODO rationale:** the defect, desired contract, and validation path are concrete: centralize image resolution and add executable Laravel guardrails preventing duplicated fallback chains.

## Contract Boundary
- This TODO defines **WHAT** must be corrected in Laravel image-resolution authority.
- It does **not** redesign media storage, uploads, image proxying, Flutter rendering, Android notification composition, or tenant branding.
- It may update backend payload/metadata consumers only where they currently derive event/account image fallback locally.
- It must not change the approved visual semantics:
  - event hero/rich image: `event.thumb` first, then `linked_account_profiles` in canonical order, then `venue` media;
  - account profile hero/background surfaces: `cover > avatar > type visual` where type visual exists on the consuming surface;
  - compact/avatar-specific account surfaces are out of scope unless a current Laravel consumer is already trying to derive a hero image.

## Delivery Status Canon
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Fast-Follow`, `Laravel`, `Architecture-Guardrail`, `Production-Visible`, `Cross-Module`, `Approved`
- **Next exact step:** package the Laravel implementation for branch/commit and promotion-lane handling when requested.

## Scope
- [x] Define the resolver payload contracts for event and account-profile image resolution so callers know the required loaded array shape.
- [x] Introduce or complete a canonical Laravel account-profile hero image resolver before event resolver integration, so account media priority is not duplicated.
- [x] Introduce or complete a canonical Laravel event image resolver for event hero/rich-image use cases, delegating linked account profile media selection to the account-profile resolver in event-safe mode.
- [x] Ensure invite target resolution consumes the canonical event image result instead of maintaining local fallback order.
- [x] Ensure tenant-public event metadata consumes the canonical event image resolver instead of maintaining local fallback order or legacy `artists` fallback.
- [x] Ensure tenant-public account-profile metadata consumes the canonical account-profile image resolver instead of maintaining local `cover_url -> avatar_url` fallback order.
- [x] Add Laravel guardrail tests that fail when event/account image fallback chains are reintroduced in non-resolver consumers.
- [x] Preserve existing formatter/query services that merely normalize or expose `avatar_url` / `cover_url`; the guardrail must not block legitimate projection/serialization of fields.
- [x] Update module documentation with the durable central resolver contract after implementation evidence is green.

## Out of Scope
- [ ] Flutter image rendering changes.
- [ ] Android notification `small_icon` / rich image UI changes already owned by the invite push visual TODO.
- [ ] Media upload/storage migration or host-agnostic media URL hardening.
- [ ] Reworking map POI visual projection rules.
- [ ] Replacing every raw `avatar_url` / `cover_url` field read in Laravel; projection and serialization code may legitimately carry those fields without choosing fallback order.

## Resolver Payload Contracts
- **Event resolver input:** an already-loaded event or formatted event payload array. The resolver may read only these keys and must not perform database reads:
  - `thumb.data.url`, then legacy-compatible `thumb.url`, then `thumb.uri`;
  - `linked_account_profiles[]` in canonical event order, each profile carrying account media fields accepted by `AccountProfileHeroImageResolver`;
  - if `linked_account_profiles` is absent, raw `event_parties[]` may be used only to derive non-venue account profile metadata from `event_parties[].metadata`;
  - `venue.cover_url`, `venue.hero_image_url`, `venue.avatar_url`, `venue.logo_url` as the final event fallback tier.
- **Event resolver exclusion:** `artists` is never read; `event_parties[].party_type=venue` is skipped when deriving linked profile candidates, so location/venue account imagery cannot win as a linked account profile.
- **Account-profile resolver input:** an already-loaded account profile payload array. The resolver may read only these keys and must not perform database reads:
  - `cover_url`, then `avatar_url`;
  - optional image-backed type visual only when the caller explicitly enables it and the payload exposes a usable image URL such as `visual.image_url` or `type_asset_url`.
- **Delegation model:** `EventHeroImageResolver` delegates profile media selection to `AccountProfileHeroImageResolver` with type-visual fallback disabled, preserving the event contract `thumb -> linked profiles -> venue`.
- **Null policy:** resolvers return `null` when no valid URL exists. Consumers may then use their own surface-level default image only as a separate default/fallback input, not by reimplementing event/account media ordering.

## Guardrail Boundary
- **Allowed fallback ownership:** only canonical resolver classes may build ordered candidate lists from multiple event/account media fields:
  - `Belluga\Events\Application\Events\EventHeroImageResolver`
  - `App\Application\AccountProfiles\AccountProfileHeroImageResolver`
- **Allowed serialization/projection behavior:** formatter, query, mapper, adapter, and projection services may expose or normalize raw `thumb`, `avatar_url`, `cover_url`, `venue`, and `linked_account_profiles` fields, but must not choose the first usable image among those fields for an event/account hero surface.
- **Guardrail scan target:** presentation/composition consumers, including public metadata, push composition, invite target/snapshot composition, and future non-resolver consumers under `app/Application`, `app/Integration`, and `packages/belluga/*/src/Application`.
- **Guardrail trigger shape:** ordered first-present candidate chains combining event media sources (`thumb`, `linked_account_profiles`, `event_parties`, `artists`, `venue`) or account hero sources (`cover_url`, `avatar_url`, `visual`, `type_asset_url`) in non-resolver consumers.

## Definition of Done
- [x] Event image resolution for invite push payloads returns `thumb.data.url` before account and venue/location imagery.
- [x] Event image resolution ignores legacy `artists` as a fallback source and does not treat `party_type=venue` as a linked account profile fallback.
- [x] Public event metadata uses the same canonical event image resolution as invite/event preview semantics.
- [x] Public account-profile metadata uses a central account-profile image resolver for hero metadata image selection.
- [x] A Laravel guardrail test blocks local event-image fallback chains in app/package consumers outside canonical resolver ownership.
- [x] A Laravel guardrail test blocks local account-profile hero-image fallback chains in app/package consumers outside canonical resolver ownership.
- [x] Existing invite share preview and invite push payload tests prove the production-shaped `thumb.data.url` fixture.
- [x] Existing public metadata tests prove linked account profile imagery still beats venue imagery and legacy `artists` does not participate in event hero image selection.
- [x] Module docs record the resolver ownership rule so future work does not rely only on tests.

## Validation Steps
- [x] Fail-first or regression test proves the previous invite path returned venue/location image for production-shaped `thumb.data.url`.
- [x] Unit tests for canonical event image resolver cover `thumb.data.url`, linked profiles, `party_type=venue` exclusion, and venue fallback.
- [x] Unit tests for canonical account-profile image resolver cover hero `cover > avatar` behavior and empty-string normalization.
- [x] Feature tests for invite push payload/share preview assert the resolved event cover image.
- [x] Feature tests for tenant-public event/account metadata assert the resolver-owned image contract.
- [x] Guardrail tests pass and include clear failure messages instructing developers to use the canonical resolvers.
- [x] Laravel targeted test suite passes via `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh`.
- [x] Laravel CI-equivalent scope is either executed and green or explicitly classified before promotion.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets:**
  - Events module: backend resolver authority for event hero/rich image resolution.
  - Flutter Client Experience module: preserve existing event/account public visual contracts from backend metadata.
  - Account Profile Catalog module: account-profile hero metadata resolver ownership.
  - Invite & Social Loop module: invite push rich image consumes canonical event image resolution.

## Complexity & Execution Profile
- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `Laravel backend`
- **Expected supporting profiles:** none unless promotion/CI reveals operational blockers.
- **Checkpoint cadence:** one planning/audit checkpoint before approval; final evidence checkpoint before Local-Implemented.

## Decision Baseline (Frozen)
- [x] `D-01` Event hero/rich-image resolution must be centralized in a Laravel resolver owned by the events domain/package; consumers must not reimplement fallback order.
- [x] `D-02` Event image resolution order is `thumb.data.url` / normalized event thumb first, then linked account profiles resolved via the central account-profile resolver in canonical order, then venue/location media; legacy `artists` is not a fallback source.
- [x] `D-03` When deriving linked profiles from raw `event_parties`, `party_type=venue` must be excluded so location imagery cannot win as account-profile fallback.
- [x] `D-04` Account-profile hero metadata resolution must be centralized in a Laravel resolver; public metadata consumers must not reimplement `cover > avatar` locally.
- [x] `D-05` Guardrail tests are required and must block duplicated ordered media fallback chains while allowing normal DTO/projection serialization of raw media fields.

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `events_module` event image resolution | Public event-image resolution order is deterministic: `event.thumb` first, then `linked_account_profiles`, then `venue` media; runtime callers must not fall back to legacy `artists`. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `flutter_client_experience_module` Event Hero Fallback Contract | Tenant-public event hero/image resolution uses `event.thumb`, linked account profiles, then venue media; Flutter must not fall back to legacy `artists`. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `flutter_client_experience_module` Account Profile Visual Resolution Contract | Account profile surfaces resolve media by surface family; hero/background uses `cover > avatar > type visuals`. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |
| `invite_and_social_loop_module` invite notification/media contract | Invite push rich image must be the resolved canonical event image. | `Preserve` | `foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-invite-push-visual-asset-and-tap-routing.md` |

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The invite bug is caused by duplicated fallback reading legacy `thumb.url` instead of production-shaped `thumb.data.url`. | Existing local RED evidence showed expected `https://example.org/thumb.jpg` but actual venue `https://example.org/hero.jpg` in invite push payload. | Need deeper event storage/media investigation before resolver work can close. | `High` | `Keep as Assumption` |
| `A-02` | Public event metadata has the same drift risk because it owns an event fallback chain locally. | `PublicWebMetadataService::eventMetadata()` currently lists `thumb`, linked profiles, `artists`, and venue candidates. | The guardrail would be incomplete if this consumer remains local. | `High` | `Promote to Decision` |
| `A-03` | Account-profile public metadata is in scope because it chooses `cover_url -> avatar_url` locally for a hero metadata surface. | `PublicWebMetadataService::accountProfileMetadata()` currently owns that fallback order. | Future account-profile visual drift can repeat the same bug class. | `High` | `Promote to Decision` |
| `A-04` | Query/formatter/projection services must remain allowed to expose raw `avatar_url` / `cover_url` fields but must not choose hero fallback order outside resolvers. | Existing Laravel services normalize and serialize these fields for API payloads and read models. | An overbroad guardrail would block legitimate DTO code; an underbroad guardrail would allow fallback drift. | `High` | `Promote to Decision` |

## Execution Plan

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/*`
- `laravel-app/app/Application/AccountProfiles/*`
- `laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php`
- `laravel-app/app/Integration/Invites/InviteTargetReadAdapter.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Targets/InviteTargetResolverService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Contracts/InviteTargetReadContract.php`
- Laravel tests under `tests/Unit`, `tests/Feature`, and `tests/Unit/Guardrails`
- Module documentation listed above

### Ordered Steps
1. Reconcile the paused local diagnostic changes into this TODO boundary after approval.
2. Add the account-profile hero image resolver first, including the payload contract and null policy.
3. Complete the event resolver integration so linked profile media selection delegates to the account-profile resolver and invite/public event metadata consume one canonical event image result.
4. Replace public account metadata local fallback with the account-profile resolver.
5. Add focused unit/feature tests for resolver semantics and affected payload/metadata consumers.
6. Add guardrail tests using the structural boundary above: only resolvers may own ordered image fallback chains; serializers may expose fields but not select hero images.
7. Run targeted Laravel tests, then the applicable Laravel CI-equivalent suite.
8. Update module docs and completion evidence after tests pass.

### Test Strategy
- **Strategy:** `test-first where practical + regression test-after for paused diagnostic changes`
- **Fail-first targets:**
  - invite push payload with production-shaped `thumb.data.url` must fail on current duplicate fallback by returning venue media;
  - guardrail test must fail if a non-allowlisted consumer reintroduces ordered event/account image fallback candidates.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Required? | Real Backend Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Invite push payload image | Backend payload feeds Android notification rich image. | `android-consumed/backend-owned` | `Laravel feature` | `yes` | `local-safe` | Invite feature test asserting `notification.image` / data image from event cover. | Real-device rendering was already covered by the invite push TODO; this slice hardens backend selection. |
| Public event metadata image | Browser/social metadata consumes backend-resolved image. | `web-metadata` | `Laravel feature` | `no` | `local-safe` | Tenant public metadata feature test asserting canonical event image. | No Playwright required because HTML metadata is Laravel-rendered and asserted directly. |
| Public account metadata image | Browser/social metadata consumes backend-resolved image. | `web-metadata` | `Laravel feature` | `no` | `local-safe` | Tenant public metadata feature test asserting central account-profile resolver result. | No Playwright required because HTML metadata is Laravel-rendered and asserted directly. |
| Guardrails | Prevents future drift, not a user flow itself. | `backend-architecture` | `Laravel unit` | `no` | `no` | Guardrail unit test scanning PHP files. | Runtime evidence not applicable to static architectural guard. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / account resolver` | Covers centralized account hero image fallback. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=AccountProfileHeroImageResolverTest tests/Unit/AccountProfiles/AccountProfileHeroImageResolverTest.php` | `Local-Implemented` | `green` | `PASS: 3 tests, 4 assertions` | Covers `cover > avatar` and explicit type visual fallback gate. |
| `laravel-app / event resolver` | Covers centralized event image fallback and venue-party exclusion. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=EventHeroImageResolverTest tests/Unit/Events/EventHeroImageResolverTest.php` | `Local-Implemented` | `green` | `PASS: 3 tests, 3 assertions` | Sequential rerun passed after avoiding concurrent safe-runner database migration noise. |
| `laravel-app / invite payload + share preview` | Covers production-shaped `thumb.data.url` in invite consumers. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='test_send_invite_authors_and_dispatches_invite_push_when_runtime_is_ready|test_share_preview_resolves_without_authentication' tests/Feature/Invites/InvitesFlowTest.php` | `Local-Implemented` | `green` | `PASS: 2 tests, 27 assertions` | Push payload and share preview both assert `https://example.org/thumb.jpg`. |
| `laravel-app / public metadata` | Covers public event/account metadata consumers. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=PublicWebMetadataShellTest tests/Feature/Tenants/PublicWebMetadataShellTest.php` | `Local-Implemented` | `green` | `PASS: 11 tests, 72 assertions` | Includes event-party profile fallback and legacy `artists` non-participation. |
| `laravel-app / static guardrail` | Prevents duplicated image fallback chains outside resolvers. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=CanonicalImageResolutionGuardrailTest tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php` | `Local-Implemented` | `green` | `PASS: 1 test, 1 assertion` | Guardrail allowlists only canonical resolvers. |
| `laravel-app / architecture guardrails` | Confirms package/app architecture constraints after binding changes. | `docker compose exec -T app composer run architecture:guardrails` | `Local-Implemented` | `green` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | Covers package decoupling/binding guardrails. |
| `laravel-app / Laravel CI-equivalent` | Backend package/app code changes will run through Laravel CI. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `Local-Implemented or promotion-ready` | `green` | `PASS: 1510 tests, 7296 assertions; Duration: 1058.78s; exit code 0` | Full local safe runner completed green on 2026-05-23. |

## Plan Review Gate
- **Status:** `approved`
- **Architecture:** central resolvers are the correct direction; local fallback chains in consumers are the failure mode.
- **Code Quality:** guardrails must avoid broad regex bans that block legitimate serialization of `avatar_url` / `cover_url`.
- **Tests:** unit resolver tests plus feature consumer tests plus static guardrail tests are required.
- **Performance:** resolver operates on already-loaded payload arrays; no new database reads should be introduced.
- **Security:** no new media access policy or upload surface is introduced.
- **Independent critique:** Claude CLI completed and found three approval blockers; all were integrated into this TODO before asking for `APROVADO`.

## Independent Critique Gate
| Finding | Severity | Resolution |
| --- | --- | --- |
| `B-1` Payload completeness contract absent. | `High / approval blocker` | Integrated `Resolver Payload Contracts` with explicit event/account keys, no-DB-read rule, exclusion rule, and null policy. |
| `B-2` Cross-resolver dependency undefined. | `High / approval blocker` | Integrated delegation model: event resolver delegates linked profile media selection to account-profile resolver with type visual disabled; account resolver is sequenced first. |
| `B-3` Guardrail exemption unenforceable. | `Medium-High / approval blocker` | Integrated structural guardrail boundary: only canonical resolvers may own ordered fallback chains; serializers may expose fields but not select hero images. |
| `R-1` Account resolver scoped too narrowly. | `Non-blocking risk` | Scope changed from public-metadata-only resolver to general account-profile hero resolver, with public metadata as the first consumer. |
| `R-2` Invite push and share preview may be distinct code paths. | `Non-blocking risk` | Validation keeps both invite push payload and share preview assertions. |
| `R-3` `party_type=venue` exclusion appeared only in tests. | `Non-blocking risk` | Exclusion moved into resolver payload contract and decision `D-03`. |
| `R-4` Null-resolution handling policy absent. | `Non-blocking risk` | Added resolver null policy: return `null`; consumers may only apply surface-level default image, not media fallback order. |

## Local Evidence
- **Paused diagnostic evidence:** local RED invite test showed production-shaped `thumb.data.url` falling through to venue media before resolver centralization.
- **Implementation evidence:** account-profile and event image resolvers were introduced, invite/public metadata consumers now delegate to canonical resolvers, and guardrail/doc updates were added.
- **Harness note:** an initial parallel targeted safe-runner attempt produced Mongo migration noise due concurrent test harness execution; sequential targeted reruns and the full safe runner passed.

## Completion Evidence Matrix
| DoD / Validation Item | Evidence | Status |
| --- | --- | --- |
| Invite push payload resolves `thumb.data.url` before venue/location imagery. | `InvitesFlowTest` targeted filter passed; fixture uses production-shaped `thumb.data.url` and asserts push `notification.image`. | `complete` |
| Event resolver ignores `artists` and excludes `party_type=venue` from linked profile fallback. | `EventHeroImageResolverTest` and `PublicWebMetadataShellTest` passed; docs updated in `events_module`. | `complete` |
| Public event metadata consumes canonical event resolver. | `PublicWebMetadataShellTest` passed; `PublicWebMetadataService` delegates to `EventHeroImageResolver`. | `complete` |
| Public account metadata consumes canonical account-profile resolver. | `AccountProfileHeroImageResolverTest` and `PublicWebMetadataShellTest` passed; `PublicWebMetadataService` delegates to `AccountProfileHeroImageResolver`. | `complete` |
| Guardrail blocks non-resolver image fallback chains. | `CanonicalImageResolutionGuardrailTest` passed and full Laravel suite passed. | `complete` |
| Module docs record resolver ownership. | Updated `events_module`, `flutter_client_experience_module`, `account_profile_catalog_module`, and `invite_and_social_loop_module`. | `complete` |
| CI-equivalent is green. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` passed: `1510 passed (7296 assertions)`, `1058.78s`, exit code `0`. | `complete` |

## Decision Adherence Validation
| Decision | Evidence | Status |
| --- | --- | --- |
| `D-01` Event hero/rich-image resolution centralized. | `EventHeroImageResolver` owns event fallback order; invite and public metadata consumers delegate. | `adherent` |
| `D-02` Event order is thumb, linked profiles, venue; no `artists`. | Unit/feature tests passed; public metadata test proves legacy `artists` does not participate. | `adherent` |
| `D-03` Raw `event_parties` skips `party_type=venue`. | Event resolver unit test covers non-venue party metadata before venue while venue party is skipped. | `adherent` |
| `D-04` Account-profile hero metadata centralized. | `AccountProfileHeroImageResolver` owns `cover > avatar` plus explicit type visual gate; public metadata delegates. | `adherent` |
| `D-05` Guardrails required and scoped. | `CanonicalImageResolutionGuardrailTest` passed; architecture guardrails and full safe runner passed. | `adherent` |
