# TODO (Bugfix): Inviteables App People Performance, UI Semantics, and Repository Cache

**Status:** Promotion-Lane / Ready for stage promotion. Implementation, focused validation, ADB evidence, post-code audits, accepted-debt adjudication, completion guard, commits, and branch publication are complete.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Delivery Status Canon
- **Current delivery stage:** `Promotion-Lane / Ready for stage promotion`
- **Qualifiers:** `post-code audit accepted-debt`
- **Next exact step:** start promotion-lane movement toward `stage`.

## Context
- The invite-share `APP` pane shows `Carregando pessoas do app...` for several seconds again.
- Runtime nginx evidence shows `/api/v1/contacts/inviteables` returning quickly while several `/api/v1/contacts/import` chunk requests continue for seconds. This is not an acceptable architecture: a client-side request loop over a long/high-cardinality list is a documented performance anti-pattern and must not be normalized as the fix.
- The screen currently exposes relation filter chips such as `Todos` and `Contatos`, and card labels such as `Contato no Belluga`. These labels create an artificial split that does not match the product concept for the app-people area.
- Inviteables state must not be held through controller shortcuts. Canonical shared/cache-backed inviteables state must live in repository-owned persistent cache/state, with controllers only delegating and projecting it.
- The current occurrence-scoped inviteables path couples two concerns: the reusable app-people directory and the per-event/per-occurrence sent-invite status overlay. This prevents useful inviteables caching and forces repeated broad extraction of app-people data whenever the user opens a different occurrence.
- Original Flutter code contained `_chunkContactImportItems(...)` in `InvitesRepository`, creating sequential `POST /contacts/import` request loops for large contact lists. That path escaped existing pagination/request-loop guardrails and is now prevented by this TODO.
- The expected backend strategy is asynchronous materialization: when a user enters/imports contact hashes, the write/materialization path resolves matches and updates each user's inviteables projection. Reading inviteables must query that final projection/read model. The current GET does not perform raw `hash -> user` matching against `email_hashes`/`phone_hashes`, but it still builds/enriches the final inviteable recipient payload at request time from `contact_hash_directory`, `favorite_edges`, profiles, users, and capabilities.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `inviteables-app-people-fast-and-canonical`
- **Why this is one slice:** the visible regression, wrong UI taxonomy, and cache/source-of-truth concern all affect the same invite-share app-people list and must be fixed coherently to avoid another local workaround.
- **Direct-to-TODO rationale:** the user-visible regression is already diagnosis-bounded and approved as one tactical bugfix; a separate feature brief would duplicate the frozen scope.

## Contract Boundary
- This TODO covers the invite-share app-people inviteables flow on Flutter plus the backend/API split required to decouple inviteables from per-occurrence invite statuses.
- The target is not to redesign the invite system. The target is to restore fast, truthful rendering of app inviteables with canonical repository-owned cache semantics and correct UI language.
- No backward compatibility is required. Old dual paths, controller-owned cache shortcuts, occurrence-scoped inviteables reads, and client-side chunk walkers may be hard-cut when they conflict with the canonical architecture.
- The projection-backed read path must not keep a safety fallback that silently reconstructs inviteables from `contact_hash_directory`, favorites, profile/user lookups, or capability reads during `/contacts/inviteables`.

## Code-Cross / External Audit Verdict
- **Pertinent with nuance:** the current `GET /api/v1/contacts/inviteables` does not perform raw `hash -> user` matching against `email_hashes` / `phone_hashes`. That raw match is in the import path.
- **Confirmed current import path:** `ContactImportService::import()` calls `InviteIdentityGatewayAdapter::matchImportedContacts(...)`, which queries `AccountUser.email_hashes` / `phone_hashes` and persists `matched_user_id` plus `match_snapshot` into `contact_hash_directory`.
- **Confirmed current read-path problem:** `InviteablePeopleService::inviteableItemsFor()` reads `contact_hash_directory`, `favorite_edges`, personal profiles, users, and profile-type capabilities, then merges/dedupes/exposes/sorts in PHP on every inviteables read. This is final inviteable-recipient payload assembly/enrichment in request path, not a projection-backed read.
- **Confirmed projection gap:** `contact_hash_directory` is only a viewer-scoped contact-match input/partial cache. It is missing the final inviteable recipient payload, consolidated reasons/source tags, profile exposure result, favorite/friend state, sort key, and materialization metadata needed for a canonical inviteables read model.
- **Auditor convergence:** Architecture, Performance, and Claude CLI all converged that the TODO must require a final materialized inviteables projection/read model. The wording must avoid saying the GET performs raw hash matching; the correct blocker is request-time final payload assembly/enrichment from intermediate sources.

## Package-First Assessment
- **Query executed:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search invite`
- **Relevant packages found:** none by registry query.
- **Full inventory checked:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --all`
- **Relevant existing local code:** `laravel-app/packages/belluga/belluga_invites` is the current package-owned invite domain even though it is not listed by the registry query. This TODO must extend that package for invite-owned models/migrations/materialization and use host adapters only where account/profile/favorite integration is host-specific.
- **Decision:** extend existing invite package and host integration services; do not create a new package.
- **Rationale:** this is invite/social-loop domain behavior, not a reusable generic utility.
- **Test-quality implication:** the existing Flutter repository test currently codifies chunk fanout as expected behavior. That test must be inverted or replaced so request-loop chunking cannot remain a green path.

## Architecture Cutover Baseline
- **Canonical GET path:** `/api/v1/contacts/inviteables` -> `ContactInviteablesController` -> projection reader -> `inviteable_people_projection` only.
- **Old assembler fate:** the current read-time assembler in `InviteablePeopleService::inviteableItemsFor()` must be converted into a projection reader or split so any source assembly exists only behind the write/materialization boundary. Controllers and GET services must not call any source assembler.
- **Single writer boundary:** projection writes are owned by one canonical idempotent materializer/refresher service. Producer hooks for import, registration, favorites, profile/privacy/capability, and user activation may call or enqueue that service, but must not mutate projection rows independently.
- **Projection schema contract:** `inviteable_people_projection` is viewer-scoped and must include owner/viewer user id, receiver user id when available, receiver account-profile id, display/avatar/profile payload needed by the app pane, source tags/reasons, favorite/friend state or status facts needed for card semantics, exposure/inviteability result, sort key, materialization metadata, and indexes that make `(owner_user_id, sort_name, receiver_account_profile_id)` and exact owner/profile lookups bounded.
- **Status overlay contract:** the occurrence overlay uses the existing `GET /api/v1/invites/sent-statuses` contract unless implementation proves it insufficient. Required query shape is `occurrence_id`, optional `event_id`, optional `recipient_account_profile_ids[]`; recipient identifiers are account profile IDs; response envelope is `data.event_id`, `data.occurrence_id`, `data.items[]`, and `metadata`. Flutter must not invent a divergent status contract.
- **Presentation mapping:** internal invite lifecycle states map to app-card copy at one presentation/domain mapping boundary. `superseded` must render as `Convidado` and must not leak internal lifecycle or private confirmation context.

## Materialization / Cutover Contract
- Contact import materialization is bounded to the importing user and matched-contact rows affected by that import. It must not recompute all inviteables from all raw contacts during the route-critical app-pane flow.
- Registration/OTP materialization is bounded to existing `contact_hash_directory` owners whose imported hashes match the newly verified user identity, plus the new user where applicable.
- Favorite/friend materialization is bounded to the two affected principals and their affected profile edge; no tenant-wide scan is allowed.
- Profile/privacy/capability/user activation changes must resolve impacted owners by indexed relations, then materialize in bounded batches. Full tenant rebuild is allowed only as an explicit backfill/maintenance command, never inside GET or a normal request handler.
- Synchronous materialization is allowed only when bounded by the affected matched rows/edges for that event. If work would scale with total raw contacts, all tenant profiles, or all users, it must run through a durable job or explicit backfill command.
- Materialization writes must be idempotent and concurrency-safe using upsert/conflict resolution, versioning, queue serialization, or an equivalent row-level consistency strategy.
- Hard cutoff requires a bootstrap/backfill gate: before the endpoint depends exclusively on projection data in a lane, existing matched contacts/favorites must be backfilled into `inviteable_people_projection`, and a readiness check must prove projection coverage for existing inviteable sources.
- GET must stay projection-only even when projection data is stale or missing. Stale/missing projection is a materialization/backfill defect, not a reason to reconstruct from intermediate sources during read.

## Scope
- [x] Measure the real slow path separately:
  - `/api/v1/contacts/inviteables`
  - `/api/v1/contacts/import`
  - `/api/v1/invites/sent-summary`
  - Flutter controller publication/loading sequence.
- [x] Verify and correct the backend responsibility boundary:
  - raw contact hash matching belongs to import/sync and must stay out of `/api/v1/contacts/inviteables`;
  - final inviteables materialization belongs to async/materialized write paths triggered by contact import, identity reconciliation, favorite/friend changes, profile/capability/privacy changes, and account-user activation/deactivation;
  - `/api/v1/contacts/inviteables` must read a precomputed inviteables projection/read model for the user;
  - `contact_hash_directory` remains matching input/partial match cache, not the final inviteables read model;
  - inviteables read cost must be proportional to the number of inviteables returned/page size, not to the number of raw contacts/hashes imported or to multi-collection payload assembly/enrichment.
- [x] Hard-cut old read/merge fallback paths instead of preserving backward-compatible behavior that can hide stale projection bugs.
- [x] Implement or wire the single canonical inviteables materializer/refresher boundary and prevent decentralized projection mutation from producer-specific hooks.
- [x] Add projection backfill/bootstrap plus readiness gate so existing matched contacts/favorites are visible after hard cutoff.
- [x] Freeze/update module/API documentation for `inviteable_people_projection`, `/contacts/inviteables`, and the existing `/invites/sent-statuses` overlay before code is considered locally deliverable.
- [x] Optimize or decouple the app-people pane so inviteables render from repository cache/backend without waiting for contact-import refresh chunks.
- [x] Remove the client-side chunked contact-import request loop from the invite-share route-critical flow. The target contract must be one bounded semantic repository intent, with batching/backpressure owned server-side or by an explicit durable/sync contract rather than UI-driven sequential request loops.
- [x] Add a guardrail so this does not return:
  - focused tests must fail if invite-share app-pane initialization performs multiple import requests for one contact refresh;
  - static/lint/heuristic audit must flag repository request loops/chunk walkers where one semantic endpoint/contract is expected;
  - endpoint-performance review must classify this as `request-loop/high-cardinality fanout`, not as harmless pagination.
- [x] Add request-budget tests for the invite-share critical path using a large contact fixture (`1200+` contact hashes/items):
  - app inviteables initialization may perform at most `1` semantic inviteables request;
  - occurrence status overlay may perform at most `1` semantic status request for the current visible/known recipients;
  - route-critical app-pane initialization must perform `0` chunked `POST /contacts/import` calls;
  - if contact import/sync is explicitly triggered outside the route-critical app-pane flow, it must be one semantic request or durable async sync intent, never `N` requests proportional to contact count.
- [x] Use this exact regression as the rule validation fixture:
  - current shape: `_chunkContactImportItems(...)` in `flutter-app/lib/infrastructure/repositories/invites_repository.dart`;
  - current behavior: one semantic contact refresh fans out into multiple sequential `POST /api/v1/contacts/import` calls;
  - expected rule result: the analyzer/custom-lint/heuristic guard fails until this pattern is removed or redesigned into an approved semantic sync contract.
- [x] Split inviteables from occurrence invite status:
  - inviteables are fetched/cached by an independent inviteables repository and are not occurrence-scoped;
  - invite statuses are fetched by the invites repository using the current event/occurrence and the visible/known recipient account profile IDs;
  - the card renders the inviteable immediately and shows a bounded loading state for that occurrence status until the status overlay arrives.
- [x] Keep contact import/background matching opportunistic and non-blocking for the app-people pane.
- [x] Remove the incorrect app-pane relation filter chips currently shown as `Todos` / `Contatos` when they do not express a useful product distinction.
- [x] Remove or replace the misleading `Contato no Belluga` card label from app inviteables.
- [x] Introduce/extract an inviteables repository path with repository-owned persistent cache/state. The existing `InvitesRepository` remains responsible for invite send/status/summary operations.
- [x] Expand card status semantics so the status overlay can represent richer states, including sent/waiting and confirmation/acceptance, instead of only a generic "convidado" state.
- [x] Add tests that fail on the observed regression:
  - app inviteables are published from cache/backend while contact import refresh is still running;
  - app inviteables initialization does not perform chunked contact-import request loops;
  - request counts remain within the defined budget with `1200+` contact hashes/items;
  - inviteables read does not assemble/enrich final recipients from `contact_hash_directory`, `favorite_edges`, user/profile lookups, and capabilities as the normal read path;
  - inviteables read never performs raw hash matching against `email_hashes` / `phone_hashes`;
  - inviteables read returns from materialized/projection data even when the user has a large contact directory;
  - inviteables pagination is cursor/page-size bounded over the projection and does not expand source rows by offset;
  - materializer is idempotent and updates/removes projection rows when contact matches, favorites, friend state, profile capability/privacy, or active-user state changes;
  - profile privacy/capability revocation removes a previously visible inviteable from projection before the next GET returns;
  - concurrent materialization triggers for the same viewer converge to one consistent projection without lost updates;
  - existing matched contacts/favorites become visible after the backfill/readiness gate without relying on future user writes;
  - app inviteables are reused across occurrences without refetching the full inviteables dataset;
  - occurrence status is fetched separately and can be loading per card without blocking the inviteable row;
  - repeated screen entry does not drop visible app inviteables into loading/empty because contact import is still running;
  - UI no longer renders the rejected `Todos` / `Contatos` app-pane filter and `Contato no Belluga` label;
  - `superseded` invite status renders as `Convidado`, not as internal lifecycle terminology or as a privacy-leaking confirmation by another inviter;
  - repository owns canonical inviteables stream/cache and controller only delegates.

## Out of Scope
- [x] Confirmed out of scope: Changing invite business rules.
- [x] Confirmed out of scope: Changing direct invite acceptance semantics.
- [x] Confirmed out of scope: Redesigning the phone agenda pane.
- [x] Confirmed out of scope: Home/Discovery taxonomy aggregation.
- [x] Confirmed out of scope: Push delivery or push handler behavior.

## Execution Lane Tracking
- **Local implementation branches:** `laravel-app:fix/invite-sent-status-hydration-accepted-push-20260523`, `flutter-app:fix/invite-sent-status-hydration-accepted-push-20260523`, `foundation_documentation:fix/invite-share-contact-import-empty-state-flicker-20260521`
- **Promotion lane path:** `dev -> stage`
- **Lane-promoted threshold for this TODO:** `stage`
- **Production-ready threshold for this TODO:** `stage`

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** backend projection materialization, Flutter inviteables repository/cache split, occurrence status overlay split, request-budget tests, and guardrail updates needed to block the exact chunk/request-loop regression.
- **Must update or split the TODO:** new invite business rules, new contact import product UX, new push behavior, Home/Discovery taxonomy work, or a new backend sync lifecycle visible outside this invite-share app-people flow.

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` The app-people pane must not wait for contact import chunks before showing available app inviteables.
- [x] `D-02` Contact import may refresh in background, but it cannot blank, block, or downgrade already-known inviteables.
- [x] `D-03` The app-people pane must use product language for app users, not contact-origin language.
- [x] `D-04` Inviteables canonical shared state must be owned by a dedicated inviteables repository and persistent/cache-backed. Controller-owned `StreamValue` is allowed only for local UI projection/delegation, not as the source of truth.
- [x] `D-05` Invite status is not part of the inviteables directory. Status is an occurrence overlay owned by the invites repository and loaded independently.
- [x] `D-06` Inviteable cards may render immediately with a per-card/per-list status loading affordance while event/occurrence status is loading.
- [x] `D-07` Rich status must include confirmation/acceptance state, not only sent/waiting invite state.
- [x] `D-08` `superseded` remains an internal lifecycle/control status. In inviteable cards, it renders as `Convidado` because the person was invited; it must not expose `superseded` copy or imply private confirmation details from another inviter.
- [x] `D-09` The materialized inviteables read-model boundary is required. Additional low-level backend endpoint optimization is required only if measured evidence shows the projection-backed endpoint itself remains a material contributor after the repository/API split.
- [x] `D-10` Client-side chunk walking over contacts/import payloads is forbidden in this flow. If large imports are required, the contract must be redesigned as a bounded semantic sync/import operation with server-side ownership or explicit async lifecycle.
- [x] `D-11` Existing guardrails are insufficient if they only catch repository pagination arguments or exact lookup page-walking; this TODO must add coverage for repository-owned request loops/chunk fanout.
- [x] `D-12` `/contacts/inviteables` is a read-model query over materialized inviteables. It must not use read-time multi-collection final payload assembly/enrichment from `contact_hash_directory`, `favorite_edges`, profiles, users, and capabilities as the normal path.
- [x] `D-13` Contact hash import/sync and favorite/profile relation changes are materialization pipeline concerns. If materialization is stale, the read endpoint may return the last known projection plus freshness metadata in the future, but it must not compensate by doing final inviteables payload assembly/enrichment inline.
- [x] `D-14` This TODO uses hard cutoff/no backward compatibility: remove obsolete paths rather than preserving them as fallbacks when they conflict with projection-backed reads or repository-owned cache.
- [x] `D-15` Registration/import/favorite/profile/capability/user-state changes are responsible for keeping the projection current; GET may read the projection and optionally page it, but must not repair projection staleness through source assembly.
- [x] `D-16` Existing source assembly code must be retired from the read path. If retained, it is write-side materializer input only and must be unreachable from `/contacts/inviteables`.
- [x] `D-17` Projection writes are centralized through one idempotent materializer/refresher boundary; producer hooks call/enqueue that boundary instead of writing projection rows directly.
- [x] `D-18` Hard cutoff requires explicit backfill/readiness proof before lane delivery, because existing matched contacts must not disappear until a future write happens.
- [x] `D-19` The status overlay contract is the existing `GET /api/v1/invites/sent-statuses` with `recipient_account_profile_ids[]`; Flutter and Laravel must stay aligned to that contract.
- [x] `D-20` First-time import with no existing projection may show a bounded bootstrap/loading affordance, but once materialization completes the app-people pane must not regress to empty/loading across navigation.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The multi-second loading is dominated by contact import request-loop fanout and publication sequencing, not only by the inviteables endpoint. | nginx log shows quick inviteables GET followed by multiple contact import chunk POSTs over several seconds. | Implement backend query optimization first and still remove the request-loop anti-pattern from route-critical initialization. | High | Validate with timings before code changes. |
| `A-02` | Current in-memory inviteables `StreamValue` in `InvitesRepository` is not enough as persistent cache for cold/repeated screen entry. | Code shows persistent cache for imported contact matches, but inviteables are held in repository memory by occurrence. | If durable cache already exists elsewhere, wire the controller to it and add regression tests. | Medium | Verify repository/storage paths before implementation. |
| `A-03` | Removing app-pane relation chips is preferable to relabeling them, because the current distinction is product noise. | User explicitly rejected `Todos` / `Contatos`; app pane should represent app users, not local contact taxonomy. | If a meaningful future filter exists, it should be introduced under a separate product decision. | High | Treat removal as in scope for this bugfix. |
| `A-04` | A lightweight per-occurrence status request keyed by recipient account profile IDs is cheaper than refetching occurrence-scoped inviteables. | Current design repeats all inviteable data extraction to obtain short status data. | If the status endpoint is itself slow or error-prone, optimize that endpoint without rejoining it to inviteables. | High | Validate with endpoint-performance scrutiny. |
| `A-05` | Existing analyzer rules do not fully block the current `_chunkContactImportItems` request-loop shape. | The code exists despite rules for repository pagination controls and endpoint anti-pattern scrutiny. | If a rule already exists but missed this path due to scope/config, fix the rule coverage rather than adding a parallel guard. | High | Inspect lint rule scope during implementation. |
| `A-06` | The current backend read path incorrectly assembles/enriches final inviteables at request time instead of querying a dedicated inviteables projection. | `InviteablePeopleService::inviteableItemsFor` reads `ContactHashDirectory`, `FavoriteEdge`, profiles, users, and capabilities during inviteables GET. | If a projection already exists but is not wired, wire the endpoint to it; otherwise introduce/repair the projection as the canonical read model. | High | Validated by code/database inspection and external audits. |
| `A-07` | Raw `hash -> user` matching is already separated into import/sync and must remain there. | `ContactImportService::import()` calls `InviteIdentityGatewayAdapter::matchImportedContacts()`, which queries `AccountUser.email_hashes` / `phone_hashes`, then stores `matched_user_id` in `contact_hash_directory`. | If implementation later moves raw matching into GET, tests must fail as a regression. | High | Encode as backend guard/test. |
| `A-08` | Synchronous projection refresh is safe only when bounded to the rows/edges affected by the triggering event. | Auditor feedback identified unbounded synchronous refresh as a way to move the performance cliff from GET to import. | If a trigger requires work proportional to total raw contacts, all profiles, or all users, use durable job/backfill semantics instead. | High | Promote to materialization contract. |

## Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Real Backend Required? | Planned Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| App inviteables render without waiting for contact import | User-visible loading regression in invite-share | shared-android-web, but contacts import is Android-heavy | ADB real-backend integration required before `Local-Implemented`/promotion | no | yes, no mock fallback | Device integration proof against real configured backend plus controller/widget tests. |
| Contact import request-loop prevented | Prevents reintroducing the high-cardinality chunk anti-pattern | Flutter repository/backend | automated guard + endpoint scrutiny | no | no | Repository tests plus static/lint/heuristic audit for chunk/request-loop paths. |
| Request budget with large agenda | Prevents hidden fanout under realistic contact volume | Flutter repository/controller | automated tests | no | no | Fake backend/adapter asserts inviteables <= 1, status <= 1, import does not scale with 1200+ contacts. |
| Inviteables cache survives occurrence changes | Reusing app-people data is the core performance fix | shared | ADB real-backend integration required before `Local-Implemented`/promotion | no | yes, no mock fallback | Repository/controller tests plus device proof opening two occurrences against real backend. |
| Occurrence status loads independently | User sees contact row immediately and only status waits | shared | ADB real-backend integration required before `Local-Implemented`/promotion | no | yes, no mock fallback | Tests for status loading overlay and device proof of later status arrival against real backend. |
| Wrong app-pane chips and labels removed | User-visible UI semantics | shared-android-web | widget evidence sufficient, ADB smoke preferred | no | no | Flutter widget assertions for absence of rejected copy. |
| Repository-owned inviteables cache | Architecture and repeated-entry behavior | shared | automated tests | no | no | Repository/controller tests proving cache source and no controller-owned canonical shortcut. |
| Backend endpoint remains performant | Query/load path | backend | Laravel performance/feature tests | no | tenant test data or seeded fixture | Timed service/feature test with large contact directory; endpoint scrutiny for unbounded scans. |
| Inviteables read model boundary | Prevents read-time final payload assembly/enrichment from raw relation sources | backend | Laravel service/feature tests | no | seeded fixture | Test proves `1200+` raw contacts do not increase read query/fanout and inviteables are served from materialized projection. |
| Projection backfill/cutover | Hard cutoff would otherwise hide existing matched contacts until future writes | backend/runtime | Laravel command/service tests plus deployment readiness evidence | no | seeded fixture | Backfill test proves existing sources populate projection before GET cutover. |
| Privacy revocation | Projection row can leak a user after capability/privacy changes | backend/security | Laravel feature/service test + security review | no | seeded fixture | Test proves revocation removes projection row and next GET excludes it. |
| Concurrent materialization | Multiple write triggers can race on the same viewer projection | backend/concurrency | Laravel concurrency/idempotency test or deterministic upsert test | yes | seeded fixture | Test proves final projection converges after simultaneous triggers. |
| Superseded status display | Prevents leaking internal lifecycle/privacy-sensitive confirmation context | shared | widget/controller evidence | no | no | Widget/controller test proves `superseded` renders as `Convidado`. |

## Frontend / Consumer Matrix
| Producer / State Surface | Consumer | Expected Path | Planned Evidence |
| --- | --- | --- | --- |
| Inviteables repository cache/state | Flutter invite-share app pane | dedicated inviteables repository stream/cache -> controller projection -> screen | repository + controller tests |
| `/api/v1/contacts/inviteables` | Flutter inviteables repository | event-agnostic Laravel backend -> DAO -> repository decoder -> inviteable domain projection | Laravel endpoint test + Flutter repository test |
| Inviteables projection/read model | Laravel `/contacts/inviteables` + Flutter inviteables repository | async materialization writes projection from contact import, favorites, profiles/capabilities/privacy, and user activation state; read endpoint serves projection | Laravel materialization/read tests + endpoint-performance scrutiny |
| `/api/v1/invites/sent-statuses` | Flutter invites repository | `occurrence_id`, optional `event_id`, optional `recipient_account_profile_ids[]` -> status map/overlay keyed by account profile ID | Laravel endpoint test + Flutter repository/controller test |
| Contact import refresh | Flutter phone agenda and background matching | repository import cache -> optional app-pane enrichment | controller tests proving non-blocking behavior |
| Contact import contract | Flutter contacts/inviteables sync | one semantic import/sync intent, no UI-driven chunk walker | repository test + static/lint guard + endpoint-performance review |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app` focused tests | Flutter controller/screen/repository behavior changes | `fvm flutter test` focused repository/DAL/controller/widget/module suites | `Local-Implemented` | passed | Repository/DAL/guard suite: `39` tests; controller/widget/module suite: `86` tests | Covers repository split, UI copy removal, status overlay, repeated entry, and send-button behavior. |
| `flutter-app` real-backend device/navigation evidence | The route-critical app-pane, repeated-entry cache, occurrence switch/reuse, and independent sent-status overlay can fail only in production wiring even when unit/controller tests pass | `fvm flutter drive ... -d 192.168.15.9:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json` | `Local-Implemented` | passed | `feature_invite_share_surface_contract_test.dart`, `feature_invite_share_cold_cache_persistence_test.dart`, `feature_profile_surface_contract_test.dart`, `feature_inviteables_real_backend_contract_e2e_test.dart` | Device final 9 used. Real-backend contract output proves `page=1&page_size=50`. |
| `flutter-app` analyzer architecture gate | Flutter architecture/cache ownership changes | `fvm dart analyze --format machine` | `Local-Implemented` | passed | `cd flutter-app && fvm dart analyze --format machine` | No diagnostics. |
| `flutter-app` custom lint/rule matrix | Guardrail must catch repository request-loop/chunk fanout or validated equivalent | `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `Local-Implemented` | passed | `success: 57 lint codes were detected` | Command: `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh`. |
| `laravel-app` focused tests | Backend endpoint/query may change | `run_laravel_tests_safe.sh StoreReleaseSocialGraphTest with inviteable projection filter set` | `Local-Implemented` | passed | `11 tests / 68 assertions` | Docker-backed safe runner; filter set includes inviteable contacts, projection, bounded contact import materialization, favorite materialization, discoverability revocation, and backfill. |
| `laravel-app` backend real local environment | Projection/backfill/security tests must not pass via mock fallback | Docker-backed safe runner with tenant Mongo fixture | `Local-Implemented` | passed | Same Laravel safe-runner focused suite | Exercises tenant models/projection materialization in Docker-backed test lane. |
| CI equivalent for touched repos | Promotion readiness | Focused local CI-equivalent matrix plus post-code audits | before promotion lane | passed | Triple audit round 02 recorded `accepted-debt`; Claude CLI recorded no blockers | Local promotion-readiness evidence is complete; remote promotion lane still runs its own CI checks. |

## Execution Plan
1. Capture precise timings from real/dev flow and local service path for inviteables/import/summary, classifying contact import chunking as a request-loop anti-pattern instead of acceptable background work.
2. Inspect the Laravel inviteables read path and identify whether the expected materialized inviteables projection exists, is stale, or is missing. The current code-cross indicates it is missing and GET is assembling/enriching final inviteables from intermediate sources.
3. Freeze the exact projection schema/indexes and status overlay endpoint contract in module/API docs before implementation code proceeds.
4. Add fail-first backend tests proving `/contacts/inviteables` reads materialized inviteables, does not cross-match large raw contact directories inline, and does not repair stale/missing projection from valid intermediate sources.
5. Add fail-first backend tests proving the single materializer is idempotent, bounded, concurrency-safe, and responds to contact import, registration, favorite/friend changes, profile/capability/privacy changes, and user activation/deactivation.
6. Add fail-first backend tests proving backfill/readiness populates existing matched contacts/favorites and privacy revocation removes projection rows before next GET.
7. Add fail-first Flutter tests for non-blocking app inviteables, repeated-entry with background refresh in flight, rejected UI copy, and `superseded -> Convidado`.
8. Add fail-first guard coverage for the current `_chunkContactImportItems` request-loop shape and invert/replace the existing test that currently expects chunk fanout.
9. Extract a dedicated inviteables repository contract/implementation for app-people directory state and persistent cache.
10. Repair or introduce the materialization/read-model boundary for inviteables.
11. Redesign contact import invocation so invite-share app-pane initialization does not drive a long client-side chunk loop.
12. Implement the smallest architecture-correct fix:
  - dedicated repository-owned persistent inviteables cache/state;
  - backend inviteables read endpoint backed by materialized projection;
  - invites repository status overlay by event/occurrence;
  - controller delegates refresh and only owns UI projection/loading;
  - app pane publication is independent from background contact import and status overlay refresh;
  - remove rejected labels/filters.
13. Adjust backend/API usage so event-agnostic inviteables and occurrence status are separate; add query/index/test fixes if measurements show either endpoint is still slow.
14. Run focused tests, analyzer, architecture checks, anti-pattern audit, and required CI-equivalent commands.
15. Run final audit gates before marking Local-Implemented.

## Validation Gates
- [x] Fail-first tests added before implementation.
- [x] Endpoint-performance scrutiny completed with measured timings or accepted bounded-request substitute evidence.
- [x] Endpoint-performance scrutiny explicitly reports no repository request-loop/chunk fanout in the invite-share route-critical path.
- [x] Laravel tests prove `/contacts/inviteables` reads a materialized/projection model and does not perform final inviteables payload assembly/enrichment from `contact_hash_directory`/favorites/profile/capability sources during normal read.
- [x] Laravel tests prove raw hash matching remains outside `/contacts/inviteables` and only occurs in import/sync/materialization paths using query-log inspection, model/DAO interception, or equivalent evidence that no `email_hashes` / `phone_hashes` query ran during GET.
- [x] Laravel tests prove projection pagination is bounded by projection page/cursor and does not expand intermediate source rows by offset.
- [x] Laravel tests prove materialization is idempotent, bounded, centralized through one writer boundary, and updates/removes projection rows for contact, favorite/friend, profile/capability/privacy, and active-user changes.
- [x] Laravel tests prove stale/missing projection is not repaired by `/contacts/inviteables` through source assembly fallback.
- [x] Laravel tests prove backfill/readiness populates existing matched contacts/favorites before projection-only cutover.
- [x] Laravel tests prove privacy/capability revocation removes projection rows and next GET excludes the revoked profile.
- [x] Laravel tests prove concurrent materialization triggers for the same viewer converge without lost updates.
- [x] Backend performance tests assert bounded query count/query shape, required projection indexes, no N+1 enrichment, and no fallback reconstruction from intermediate sources.
- [x] Request-budget tests pass with `1200+` contact hashes/items and fail against the current chunked implementation.
- [x] Request-budget tests instrument repository/backend calls and assert route-critical app-pane initialization performs `0` chunked contact-import calls, at most `1` inviteables request, and at most `1` current-occurrence status overlay request.
- [x] Flutter architecture adherence checked: no controller-owned canonical inviteables cache.
- [x] Flutter architecture adherence checked: inviteables repository and invites repository have separate responsibilities.
- [x] Static/lint/heuristic guard added or existing guard fixed so `_chunkContactImportItems`-style request loops cannot silently return.
- [x] The current `_chunkContactImportItems` regression is included as a failing fixture/case for the adjusted rule and passes only after the guard recognizes the anti-pattern.
- [x] Test-quality audit confirms tests would fail on the current regression.
- [x] Flutter widget/controller/integration coverage proves repeated entry keeps cached app inviteables visible while background contact refresh is running.
- [x] Real-backend Flutter device/navigation evidence proves invite-share app-pane initial render while contact import is running, repeated screen entry, occurrence switch/reuse without full inviteables refetch, and independent sent-status overlay behavior. This gate cannot pass with mocks; if device/backend lane cannot run, delivery stops.
- [x] `superseded` display is tested as `Convidado` without leaking internal lifecycle terminology.
- [x] Completion evidence matrix filled before status changes.
- [x] `todo_completion_guard.py` returns `Overall outcome: go` before promotion-lane movement.

## Complexity
- **Level:** `medium`
- **Checkpoint policy:** approval before implementation; renewed approval if measurements show a larger backend redesign is required.
- **Reason:** one user-visible flow, but it crosses Flutter controller/repository architecture, persistent cache semantics, and possibly Laravel endpoint performance.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:** inviteables/read-model contract, invite-share app-people UI semantics, and Flutter repository ownership notes.
- **Module decision consolidation targets:** invite/social loop API/read-model definitions plus Flutter client experience state ownership guidance.

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md --json-output foundation_documentation/artifacts/tmp/inviteables-app-people-audit-escalation.json`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/tmp/inviteables-app-people-audit-escalation.json`; guard returned `status: ready`, fingerprint `d31ae42d3bd9`, `Overall outcome: go`.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Copy from the TODO Complexity section. |
| `blast_radius` | `cross-stack` | Laravel read model/API behavior plus Flutter repository/controller/UI behavior. |
| `behavioral_change_or_bugfix` | `yes` | User-visible loading/regression and wrong UI semantics. |
| `changes_public_contract` | `yes` | `/contacts/inviteables` read semantics, projection schema, and status overlay consumer contract are in scope. |
| `touches_auth_or_tenant` | `yes` | Tenant-scoped inviteables, profile exposure/privacy, and authenticated user contact relations are in scope. |
| `touches_runtime_or_infra` | `yes` | Materialization pipeline and route-critical request fanout/runtime performance are in scope. |
| `touches_tests` | `yes` | Backend, Flutter, and guardrail tests are required. |
| `critical_user_journey` | `yes` | Direct invite/share app-people flow is release-critical for the social loop. |
| `release_or_promotion_critical` | `yes` | This must be promotable without reintroducing stuck/slow invite behavior. |
| `high_severity_plan_review_issue` | `yes` | Current architecture permits high-cardinality request fanout and read-time source assembly on a hot path. |
| `explicit_three_lane_request` | `yes` | User explicitly required TODO audit and prior alignment required triple audit plus Claude CLI. |

## Independent No-Context Critique Gate
- **Critique decision:** `required`
- **Why this decision:** TEACH audit floor derived `CRITIQUE-BASELINE-ALWAYS` and `CRITIQUE-EXPANDED-RISK-SIGNALS` for a medium, cross-stack, public-contract, tenant/runtime, test-touching, critical-journey, release-critical bugfix.
- **Impact signals in scope:** `cross-stack blast radius`, `public contract/schema/api`, `tenant/privacy`, `runtime/materialization`, `critical user journey`, `release-critical`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** frozen baseline, approved scope boundary, code-cross/auditor verdict, execution plan summary, flow evidence matrix, CI-equivalent matrix, residual risks.
- **Critique isolation mode:** `fresh no-context auxiliary reviewers`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol:** `audit-protocol-triple-review`
- **Audit session / round evidence:** `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/session.json`; round 02 recorded `accepted-debt` with no blockers.
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|test-quality|risk`
- **Critique status:** `post_code_accepted_debt_no_blockers`
- **Findings summary:** preimplementation audit blockers were integrated before implementation. Post-code round 01 Test Quality blockers were resolved in code and evidence. Post-code round 02 returned no blockers; remaining findings were accepted as non-blocking promotion/runtime evidence debt.
- **Evidence / reference:** `foundation_documentation/artifacts/tmp/inviteables-app-people-audit-escalation.json`; `foundation_documentation/artifacts/audits/inviteables-app-people-todo-preimplementation-20260524/session.json`; `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/session.json`; `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/round-02/round-summary.md`; `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/round-02/resolution.md`; `foundation_documentation/artifacts/claude-cli-reviews/inviteables-app-people-postcode-round02-claude-review-20260524.json`

### Derived Audit Floor
- **Critique:** `required` before implementation continuation.
- **Security review:** `required` before completion via `security-adversarial-review` because tenant/privacy/auth surfaces are touched.
- **Performance/concurrency:** `required` via PCV gate deadlines because the TODO is explicitly about runtime/materialization/request fanout.
- **Verification debt:** `required` before completion because this is medium, cross-stack, and release-critical.
- **Test-quality audit:** `required` before completion because tests/public contract/critical journey are touched.
- **Final review:** `required` before completion.
- **Triple review:** `required` and additive only; it does not replace critique.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| C-01 | Scope | Measure the real slow path separately: | endpoint review plus ADB runtime | `foundation_documentation/artifacts/validation/inviteables-app-people-endpoint-performance-20260524.md` | Android final 9 and Laravel test lane | passed | ADB real-backend output proves `page=1&page_size=50`. |
| C-02 | Scope | Verify and correct the backend responsibility boundary: | code plus tests | `InviteablePeopleService::inviteableItemsFor`, `inviteablePageFor`, and Laravel projection tests | Laravel | passed | GET reads `InviteablePeopleProjection`; source assembly is materializer input only. |
| C-03 | Scope | Hard-cut old read/merge fallback paths instead of preserving backward-compatible behavior that can hide stale projection bugs. | controller test | `test_inviteable_contacts_paged_query_uses_bounded_page_service` | Laravel | passed | Controller always calls page service; no default full read branch. |
| C-04 | Scope | Implement or wire the single canonical inviteables materializer/refresher boundary and prevent decentralized projection mutation from producer-specific hooks. | service implementation plus hooks | `InviteablePeopleProjectionService`, `InvitesIntegrationServiceProvider`, `ContactImportService` | Laravel | passed | Hooks call one projection service. |
| C-05 | Scope | Add projection backfill/bootstrap plus readiness gate so existing matched contacts/favorites are visible after hard cutoff. | command and test | `invites:inviteable-people-projection:backfill`; backfill test in `StoreReleaseSocialGraphTest.php` | Laravel | passed | Existing sources populate projection before projection-only reads. |
| C-06 | Scope | Freeze/update module/API documentation for `inviteable_people_projection`, `/contacts/inviteables`, and the existing `/invites/sent-statuses` overlay before code is considered locally deliverable. | documentation update | `modules/invite_and_social_loop_module.md`; `endpoints_mvp_contracts.md` | Foundation docs | passed | Docs now state projection-only bounded GET. |
| C-07 | Scope | Optimize or decouple the app-people pane so inviteables render from repository cache/backend without waiting for contact-import refresh chunks. | Flutter tests plus ADB | `feature_invite_share_cold_cache_persistence_test.dart`; controller/widget tests | Flutter Android final 9 | passed | App pane reads inviteables repository; contact refresh is opportunistic. |
| C-08 | Scope | Remove the client-side chunked contact-import request loop from the invite-share route-critical flow. The target contract must be one bounded semantic repository intent, with batching/backpressure owned server-side or by an explicit durable/sync contract rather than UI-driven sequential request loops. | guard test plus obsolete scan | `test/architecture/invite_contact_import_request_loop_guard_test.dart` | Flutter | passed | Removed chunk symbols from production code. |
| C-09 | Scope | Add a guardrail so this does not return: | static guard and audits | `validate_rule_matrix.sh`; exact lookup audits; test-quality audits | Flutter and Laravel | passed | Rule matrix passed; 57 lint codes detected. |
| C-10 | Scope | Add request-budget tests for the invite-share critical path using a large contact fixture (`1200+` contact hashes/items): | request-budget and large-fixture tests | `test_contact_import_materialization_is_bounded_to_imported_profiles`; Flutter repository tests | Laravel and Flutter | passed | Laravel seeds `1200` existing rows and imports one matched contact. |
| C-11 | Scope | Use this exact regression as the rule validation fixture: | guard fixture | `invite_contact_import_request_loop_guard_test.dart` | Flutter | passed | Guard searches for removed `_chunkContactImportItems` symbols. |
| C-12 | Scope | Split inviteables from occurrence invite status: | repository and DAL tests | `inviteables_repository_test.dart`; `invites_repository_test.dart`; `laravel_invites_backend_test.dart` | Flutter | passed | Inviteables GET is event-agnostic; status overlay uses sent-statuses. |
| C-13 | Scope | Keep contact import/background matching opportunistic and non-blocking for the app-people pane. | ADB and controller tests | `feature_invite_share_cold_cache_persistence_test.dart`; `invite_share_screen_controller_test.dart` | Android final 9 | passed | Cached app inviteables do not disappear while contact work is running. |
| C-14 | Scope | Remove the incorrect app-pane relation filter chips currently shown as `Todos` / `Contatos` when they do not express a useful product distinction. | UI code removal and widget tests | deleted `invite_share_relation_filter_chips.dart`; widget tests | Flutter | passed | Rejected chips removed. |
| C-15 | Scope | Remove or replace the misleading `Contato no Belluga` card label from app inviteables. | widget tests and obsolete scan | widget/controller tests; rejected-copy scan | Flutter | passed | Rejected label absent. |
| C-16 | Scope | Introduce/extract an inviteables repository path with repository-owned persistent cache/state. The existing `InvitesRepository` remains responsible for invite send/status/summary operations. | repository implementation and architecture tests | `InviteablesRepositoryContract`; `InviteablesRepository`; analyzer | Flutter | passed | Repository owns app-people StreamValue/cache. |
| C-17 | Scope | Expand card status semantics so the status overlay can represent richer states, including sent/waiting and confirmation/acceptance, instead of only a generic "convidado" state. | domain mapping tests | `FriendResumeWithStatus`; invite-share controller tests | Flutter | passed | `superseded` renders as `Convidado`; acceptance status is represented. |
| C-18 | Scope | Add tests that fail on the observed regression: | focused test suites and ADB | Laravel suite, Flutter repository/DAL/guard suite, Flutter controller/widget/module suite, ADB real-backend/cold-cache tests | Cross-stack | passed | Covers slow loading, empty-state flicker, chunk fanout, UI copy, and projection-only regression. |

## Implementation Evidence Matrix (Current)
| Gate / Requirement | Evidence | Status |
| --- | --- | --- |
| Flutter analyzer architecture gate | `cd flutter-app && fvm dart analyze --format machine` | Passed, no diagnostics |
| Flutter repository/DAL/request-loop guard | `cd flutter-app && fvm flutter test test/infrastructure/repositories/inviteables_repository_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/dal/laravel_invites_backend_test.dart test/architecture/invite_contact_import_request_loop_guard_test.dart` | Passed, `39` tests |
| Flutter controller/widget/module scope | `cd flutter-app && fvm flutter test test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart test/presentation/tenant/invites/screens/contact_group_management/controllers/contact_group_management_controller_test.dart test/presentation/tenant/invites/screens/contact_group_management/contact_group_management_screen_test.dart test/presentation/tenant/profile/screens/profile_screen/profile_screen_test.dart test/application/router/modules/invite_share_module_test.dart` | Passed, `86` tests |
| Laravel projection/read-model focused suite | `cd laravel-app && ./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php --filter='inviteable_contacts|projection|contact_import_materializes|contact_import_materialization_is_bounded|favorite_materialization|discoverability_revocation|backfill'` | Passed, `11` tests / `68` assertions |
| Android device evidence on requested device | `cd flutter-app && fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_invite_share_surface_contract_test.dart -d 192.168.15.9:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Passed, `4` tests |
| Android cold-cache / contact-cache split on requested device | `cd flutter-app && fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_invite_share_cold_cache_persistence_test.dart -d 192.168.15.9:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Passed, `2` tests |
| Android profile surface on requested device | `cd flutter-app && fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_profile_surface_contract_test.dart -d 192.168.15.9:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Passed, `4` tests |
| Android real-backend inviteables bounded contract | `cd flutter-app && fvm flutter drive --no-pub --driver=test_driver/integration_test.dart --target=integration_test/feature_inviteables_real_backend_contract_e2e_test.dart -d 192.168.15.9:5555 --flavor guarappari --dart-define-from-file=config/defines/integration.tenant.json --dart-define=DISABLE_PUSH=true --no-dds --device-timeout 60` | Passed; output `domain=guarappari.belluga.space path=/api/v1/contacts/inviteables query=page=1&page_size=50 recipients=0` |
| Device runner checklist | `foundation_documentation/artifacts/tmp/flutter-device-runner/test-run-progress.md` | All three touched integration tests marked `[x]` for device `192.168.15.9:5555` |
| Endpoint performance review | `foundation_documentation/artifacts/validation/inviteables-app-people-endpoint-performance-20260524.md` | Completed; projection-only read path and expected indexes recorded |
| Obsolete path scan | `rg -n "fetchInviteableContactsForOccurrence|InviteableContactsRequest|fetchInviteableRecipientsForOccurrence|inviteableRecipientsStreamValueForOccurrence|setInviteableRecipientsForOccurrence|inviteableRecipientsByOccurrence|_chunkContactImportItems|_maxContactImportItemsPerRequest|InviteShareRelationFilterChips|Contato no Belluga|Nenhum contato convidável para este filtro|sessionVersion|session_version" ...` | Only guard-test references to removed chunk symbols remain |
| Exact lookup anti-pattern audit | Flutter modified scan: no high findings; Laravel modified scan: no high/medium findings | Passed for production paths; one medium Flutter finding is test-only `firstWhere` in contact-group controller test |
| Test-quality heuristic audit | Restricted scan over changed tests and Laravel invite suite | Medium heuristic only; no hard bypass/test-only routes/mock fallback. Findings are expected test harness patterns (`Sanctum::actingAs`, DI registration in widget/integration tests, payload asserts adjacent to `assertOk`). |
| Flutter rule matrix | `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | Passed; expected `57` lint codes detected |
| Triple audit round 01 resolution | `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/round-01/resolution.md` | Recorded `resolved`; Test Quality blockers TQ-01/TQ-02/TQ-03 fixed before round 02 |
| Triple audit round 02 | `foundation_documentation/artifacts/audits/inviteables-app-people-postcode-20260524/round-02/round-summary.md`; `round-02/resolution.md`; Claude CLI round 02 review | Recorded `accepted-debt`; no blockers |
| Security/privacy review | `foundation_documentation/artifacts/validation/inviteables-app-people-security-review-20260524.md` | Passed; no blocking tenant/privacy issue |
| Verification debt audit | `bash delphi-ai/tools/verification_debt_audit.sh --todo foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md --scan-git-modified` | Passed; outcome heuristic `none`, inline code TODO debt `none` |

## Open Delivery Gates
- [x] Required post-code audit/final-review loop recorded clean or accepted-debt.
- [x] Required security/privacy review recorded for tenant/profile exposure and projection pruning.
- [x] Required performance/concurrency review recorded for materialization/upsert/backfill boundaries.
- [x] Canonical module docs updated for the stable inviteables projection and Flutter repository ownership decisions.
- [x] `python3 delphi-ai/tools/todo_completion_guard.py foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md` returns `Overall outcome: go`.
