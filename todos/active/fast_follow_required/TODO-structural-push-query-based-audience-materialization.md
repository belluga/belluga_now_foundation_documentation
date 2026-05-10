# TODO (Fast Follow): Structural Push Device Authority and Query-Based Recipient Resolution

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. This TODO owns the structural correction required both for the current invite-push promotion and for future expansion into multi-recipient audiences such as profile followers.
**Owners:** Delphi (Laravel) + Runtime / Integration
**Goal:** replace scan-style recipient discovery and embedded-device push storage with a query-first `push_devices` authority, fix the direct-recipient provider batching contract, and establish the server-managed channel/topic foundation future fan-out triggers must use instead of per-send audience scans.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The current push runtime already works for direct invite delivery, but its recipient resolution and storage model are too generic for both the current delivery lane and broader trigger expansion:

- `PushRecipientResolver` currently iterates tenant users in chunks and asks `PushAudienceEligibilityService` to filter them in memory;
- for `audience.type = users`, this still means scanning users instead of resolving the concrete target set directly by query;
- active push tokens currently live in embedded `AccountUser.devices`, which is the wrong authority for a push-targeted, delivery-centric pipeline;
- future triggers such as `event for followed profile`, `today's events for followers`, or `account profile manager push to own followers` should not rely on tenant-wide scan semantics;
- the problem is structural, not just feature-local. Even if a specific trigger authors a single `PushMessage`, the current core path is the wrong abstraction for fan-out audiences;
- the current FCM transport also expands a delivery batch into per-token raw HTTP v1 requests, which is not an acceptable steady-state for the approved push batching posture;
- this is now part of the current delivery-critical performance posture, because the invite push lane should not be promoted while still depending on the legacy scan-style explicit-user path.

This TODO intentionally does **not** approve or implement a new follower/event trigger yet. It only prepares the push subsystem so those future triggers can be added on top of a sound audience-resolution model.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** the structural audience bug is now diagnosis-bounded and should be solved before more trigger classes accumulate on top of it.
- **Direct-to-TODO rationale:** the required work is architectural correction and validation, not product discovery.

## Contract Boundary

- This TODO defines **WHAT** the push subsystem must support structurally for query-first audience resolution.
- It does not define the business trigger for `event created`, `today's event`, or any specific follower-based notification.
- It does not reopen worker topology.
- It may define the structural channel/topic subscription foundation future triggers depend on.
- It does reopen device registration persistence, because `push_devices` becomes the authoritative push-delivery store now.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Structural`, `Backend-Only`, `Push-Foundation`, `Future-Trigger-Prerequisite`, `Topic-Channel-Foundation`
- **Next exact step:** finish the isolated Laravel CI-equivalent suite on top of the already-implemented `push_devices` cutover, then push the reconcile branch with the structural evidence frozen.

## Complexity / Execution Profile

- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `laravel + runtime validation + foundation docs`

## Canonical Module Anchors

- **Primary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_analytics_capability.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Decision Baseline (Frozen 2026-05-09)

- [x] `D-01` Targeted push audiences must be resolved by query/materialization, not by tenant-wide user scanning plus in-memory eligibility checks.
- [x] `D-02` `push_devices` becomes the authoritative push-delivery store now; `AccountUser.devices` is not an acceptable long-term or transitional push authority for this lane.
- [x] `D-03` Explicit `users` audiences must resolve directly against `push_devices`, not by loading `AccountUser` documents and extracting embedded device arrays in PHP.
- [x] `D-04` Query batching must align to delivery-oriented push targets. Use stable keyset pagination by `_id`, not offset pagination.
- [x] `D-05` Query output must be projection-minimal. Only fields required for delivery/runtime bookkeeping should be loaded.
- [x] `D-06` Future semantic audiences such as `followers of profile` must not rely on tenant-wide scan. When the audience is stable and subscribable, prefer channel/topic delivery; otherwise materialize a concrete recipient set by query before entering the generic push-delivery pipeline.
- [x] `D-07` The generic push subsystem should remain responsible for queueing, batching, provider delivery, telemetry, and token invalidation, not for discovering high-level business audiences by scan.
- [x] `D-08` This TODO does not approve a new `event created -> followers` trigger. Trigger policy will be handled by a later TODO after the structural foundation is complete.
- [x] `D-09` Because the installed base with real tokens is currently minimal, this lane may cut over cleanly without heavy legacy push-token backfill; fresh app registration becomes the recovery path.
- [x] `D-10` A delivery batch of up to `500` recipients may not fan out into up to `500` outbound provider requests as the steady-state implementation. Query budgets and provider-request budgets must both be explicit and testable.
- [x] `D-11` `channel` is the product-semantic audience contract; `topic` is the concrete FCM transport target that may implement a channel.
- [x] `D-12` Direct/private transactional messages such as `invite_received` remain direct-recipient deliveries and must use bounded provider batching, not topic fan-out.
- [x] `D-13` Stable recurring audiences such as `favorites/followers of account profile` and `confirmed attendees of occurrence` must be modeled as channels backed by server-managed FCM topic memberships, not by client-only topic subscription.
- [x] `D-14` Channel membership side effects must be driven by canonical domain events or equivalent transactional activity events, not by controller/UI glue code.
- [x] `D-15` Topic names for non-public semantic channels must be opaque stable derivations, not easily guessable raw identifiers.
- [x] `D-16` Canonical post-commit domain/activity events may fan out to multiple downstream effects at once, including topic membership sync, push authoring, telemetry, and social-metric refreshes; those effects must share the same authoritative source event rather than re-deriving business meaning independently.
- [x] `D-17` The direct-recipient transport contract is budgeted: for `<= 500` concrete recipients, the delivery layer must emit one bounded provider batch request plus the required auth-token request, not one provider send request per token.

## Scope

- Audit the current push recipient-resolution and device-storage path and document where tenant-wide iteration or embedded-device coupling still occurs.
- Introduce `push_devices` as the authoritative store for push-device registration, invalidation, and recipient resolution.
- Replace explicit `users` audience resolution with direct `push_devices` queries.
- Define the canonical extension seam future domain triggers must use to materialize semantic audiences into concrete recipient sets.
- Define when future domain triggers should use stable channel/topic delivery instead of per-send recipient materialization.
- Introduce the structural membership foundation for favorite/profile and occurrence-confirmation channels so future triggers can publish without re-solving subscription topology.
- Define the canonical event-source rule for push-side effects: canonical post-commit domain/activity events are the source for channel membership, push authoring, telemetry, and metric refresh, while controllers/UI remain orchestration-only.
- Ensure the invite-delivery path and any existing targeted push paths can use the improved structure without regression.
- Add focused tests proving no full-tenant scan or embedded-device extraction is required for explicit user audiences, and that request/query budgets stay within the approved optimized envelope.

## Out of Scope

- Implementing `event created`, `today's events`, or `followers of profile` as business triggers.
- Redesigning push message content authoring UX.
- Reopening Firebase/FCM configuration, worker infra, or Flutter push runtime behavior.
- Approving new business triggers such as followers/event notifications.

## References

- `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushMessageService.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Jobs/SendPushMessageJob.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushRecipientResolver.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushMessageAudienceService.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/FcmHttpV1Client.php`
- `laravel-app/app/Application/Push/PushAudienceEligibilityService.php`
- `laravel-app/app/Integration/Push/PushUserGatewayAdapter.php`
- `laravel-app/app/Models/Tenants/AccountUser.php`
- `laravel-app/app/Domain/Identity/AnonymousIdentityMerger.php`
- `laravel-app/app/Application/Events/AttendanceCommitmentService.php`
- `laravel-app/app/Http/Api/v1/Controllers/EventAttendanceController.php`
- `laravel-app/packages/belluga/belluga_favorites/src/Application/Favorites/FavoritesCommandService.php`
- `laravel-app/packages/belluga/belluga_favorites/src/Models/Tenants/FavoriteEdge.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventManagementService.php`

## Package-First Assessment

- Query executed: `bash /home/elton/Dev/repos/delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app --search "push"`
- Relevant packages found:
  - [Local] `none returned by deterministic registry query for Laravel host packages` — work remains inside the existing host-owned `belluga_push_handler` package surfaces already listed in References.
  - [Ecosystem] `push_handler` (Flutter/pub package) — unrelated to this Laravel-side recipient-resolution change.
- Potential external dependency:
  - [External] `kreait/firebase-php` — candidate server-side Firebase Admin SDK for tenant-dynamic topic subscription management because the official Firebase REST topic-management path is deprecated for new development.
- READMEs read: `none from package query output for Laravel local package reuse`
- Decision: modify the existing Laravel push package/runtime surfaces in place and, if server-managed topic membership is required, adopt the external PHP Admin SDK rather than using deprecated Firebase REST topic-management endpoints.
- Tier: `local host package surface + external SDK if required`
- Rationale: the structural defect is inside the current `belluga_push_handler` recipient-resolution path, while secure topic membership for favorites/occurrence channels requires a supported server-side Firebase management path instead of deprecated REST topic-management calls.

## Current Audit Snapshot (2026-05-09)

- [x] `A-01` `PushRecipientResolver` currently calls `chunkUsers(...)` and evaluates eligibility per user, even for explicit `audience.type = users`.
- [x] `A-02` `PushAudienceEligibilityService` only understands `users` and `all`, but its `users` path still relies on the outer user scan to find matches.
- [x] `A-03` `PushDeliveryService` already handles batching to FCM correctly once a concrete token set is available.
- [x] `A-04` The direct invite push path already materializes the logical target user ID before calling `PushMessageService`; the remaining inefficiency is in the core resolver/storage path.
- [x] `A-05` `AccountUser.devices` is also touched by identity/auth flows, so the cutover must be explicit about where push-device truth lives rather than quietly dual-writing forever.
- [x] `A-06` Future follower-style triggers can derive recipients by query from `favorite_edges`, so the structural need is real and query-backed.

## Local Implementation Evidence (2026-05-10)

- `push_devices` authority introduced in `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/PushDevice.php` and `laravel-app/packages/belluga/belluga_push_handler/database/migrations/2026_05_10_000100_create_push_devices_collection.php`.
- Device registration, token invalidation, direct lookup, account-scope sync, and merge-time reassignment now cut over in `laravel-app/app/Integration/Push/PushUserGatewayAdapter.php`.
- Push audience resolution no longer scans tenant users for explicit `users` audiences:
  - `laravel-app/packages/belluga/belluga_push_handler/src/Services/PushRecipientResolver.php`
  - `laravel-app/packages/belluga/belluga_push_handler/src/Jobs/SendPushMessageJob.php`
- The explicit-user path now streams projection-minimal delivery targets from `push_devices` via stable keyset pagination by `_id`, using delivery-aligned batches of `500` and avoiding offset pagination.
- The provider transport remains a live blocker inside this TODO: `laravel-app/packages/belluga/belluga_push_handler/src/Services/FcmHttpV1Client.php` still fans a batch into per-token raw HTTP v1 POSTs, which must be redesigned before promotion readiness can be claimed.
- The channel/topic posture is now part of the active foundation in this lane:
  - direct invite delivery stays on direct-recipient batching;
  - stable favorite/profile and occurrence-confirmation audiences should move to server-managed topic memberships driven by canonical domain events.
- The canonical side-effect posture is now part of the active foundation in this lane:
  - canonical post-commit domain/activity events are the only approved source for push, topic-membership churn, telemetry, and social-metric refresh;
  - future trigger TODOs must bind to those canonical events rather than to controller/UI entrypoints.
- Identity/account lifecycle now keeps `push_devices.account_ids` authoritative enough for account-scoped push without falling back to `AccountUser.devices`:
  - `laravel-app/app/Application/Accounts/AccountManagementService.php`
  - `laravel-app/app/Application/Accounts/AccountUserService.php`
  - `laravel-app/app/Domain/Identity/AnonymousIdentityMerger.php`
- Canonical architectural guidance was promoted in:
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`

## Focused Validation Evidence (2026-05-10)

- Query-topology audit:
  - `bash /home/elton/Dev/repos/delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app --path app/Integration/Push/PushUserGatewayAdapter.php --path packages/belluga/belluga_push_handler/src/Services/PushRecipientResolver.php --path packages/belluga/belluga_push_handler/src/Jobs/SendPushMessageJob.php`
  - Result: no high or medium findings.
- Architecture guard:
  - `docker compose -f /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/docker-compose.yml exec -T app php scripts/architecture_guardrails.php`
  - Result: `PASS`.
- Focused Laravel proof used isolated Mongo databases to avoid cross-suite drop contention:
  - `tests/Feature/Push/PushMessageFlowTest.php`
  - `tests/Api/v1/Tenants/Auth/ApiV1PushRegisterTest.php`
  - `tests/Feature/Invites/InvitesFlowTest.php`
  - `tests/Api/v1/Tenants/Auth/T1PasswordRegistrationTest.php`
  - `tests/Api/v1/Tenants/Auth/T2PasswordRegistrationTest.php`
- Key structural proofs now covered:
  - explicit `users` audience resolves by `push_devices` without tenant-wide scan;
  - account-scoped explicit audiences exclude foreign-account users at query time;
  - large explicit audiences stay on query-first path with stable keyset batching;
  - account attach/detach sync updates `push_devices.account_ids`;
  - removing last account access deactivates push devices;
  - unmaterialized semantic audiences fail closed instead of scanning generically.
- Remaining open proof:
  - provider transport budget is still unresolved because the current FCM client uses raw per-token HTTP v1 fan-out.

## Implementation Tasks

- [ ] 🟧 Local-Implemented Produce a drift-free audit of the current push recipient-resolution and push-device persistence path and identify the exact scan-style / embedded-device steps that must be eliminated.
- [ ] 🟧 Local-Implemented Add the authoritative `push_devices` model/collection and cut device registration, invalidation, and direct token lookup over to it.
- [ ] 🟧 Local-Implemented Replace explicit-user audience resolution with a `push_devices` query path that pages by stable keyset cursor and delivery-aligned target batches.
- [ ] 🟧 Local-Implemented Ensure query output is projection-minimal and does not load full user/device documents when only delivery targets are needed.
- [ ] 🟧 Local-Implemented Define and document the extension seam future follower/profile/event triggers must use to materialize semantic audiences before push authoring.
- [ ] ⚪ Pending Implement the server-managed channel/topic foundation for stable recurring audiences such as `favorites/followers of profile` and `confirmed attendees of occurrence`, keeping it distinct from direct/private transactional pushes such as invites.
- [ ] 🟧 Local-Implemented Verify the current invite-delivery path remains compatible with the new storage/resolution model and does not regress.
- [ ] 🟧 Local-Implemented Add focused tests that fail if explicit-user audiences still require tenant-wide user scanning, embedded-device extraction, or unstable offset-style pagination.
- [ ] ⚪ Pending Replace the current per-token FCM HTTP v1 fan-out with the approved provider batch/multicast transport contract for up to `500` recipients, or explicitly redesign the provider layer that owns that contract.
- [ ] ⚪ Pending Add budget tests that assert both the database query count and the outbound provider-request count for bounded recipient batches, including the rule that `<= 500` direct recipients produce one provider send batch request rather than one request per recipient.
- [ ] ⚪ Pending Add channel/topic naming, membership sync, and domain-event/listener wiring so future stable audiences publish to channels instead of rematerializing recipients on every send.
- [ ] ⚪ Pending Add canonical event/listener wiring that allows the same source event to drive push, telemetry, and social-metric refresh side effects without duplicating trigger logic.
- [ ] 🟧 Local-Implemented Back-link the final structural evidence artifact from this TODO.

## Acceptance Criteria

- [ ] 🟧 Local-Implemented Explicit user audiences no longer require tenant-wide chunk iteration to resolve recipients.
- [ ] 🟧 Local-Implemented Push-device registration, lookup, and invalidation no longer depend on `AccountUser.devices` as runtime authority.
- [ ] 🟧 Local-Implemented The push subsystem preserves its existing queue/batching/provider responsibilities while moving audience discovery for targeted users onto direct `push_devices` queries.
- [ ] 🟧 Local-Implemented The current invite push path still works after the structural change.
- [ ] ⚪ Pending The codebase/doc set exposes a clear extension point for future semantic audiences such as `followers of profile`, including when to prefer topic/channel delivery over direct-recipient materialization.
- [ ] 🟧 Local-Implemented Future trigger TODOs can depend on this TODO instead of re-solving recipient resolution ad hoc.
- [ ] ⚪ Pending A bounded recipient batch honors an explicit provider-request budget instead of expanding into one raw outbound request per token, with `<= 500` direct recipients proving one bounded provider send batch request.

## Validation Steps

- [ ] 🟧 Local-Implemented Query lane: prove `audience.type = users` resolves recipients from `push_devices` via stable keyset pagination rather than `chunkUsers(...)` or offset pagination.
- [ ] 🟧 Local-Implemented Device-authority lane: prove register/invalidate/unregister/token lookup use `push_devices` as runtime truth.
- [ ] 🟧 Local-Implemented Invite compatibility lane: prove direct invite push still authors and dispatches correctly after the structural change.
- [ ] ⚪ Pending Budget lane: prove recipient-resolution queries stay within the approved database-query budget and provider delivery stays within the approved outbound-request budget for `<= 500` recipients, including one provider batch send request for the full direct-recipient chunk.
- [ ] ⚪ Pending Topic/channel lane: prove the structural split between direct/private transactional pushes and stable server-managed channels/topics, and record the canonical membership/event rules future triggers must use.
- [ ] ⚪ Pending Canonical event lane: prove the implemented side effects hang off canonical post-commit domain/activity events rather than controller/UI entrypoints, and that the same source event can safely feed push plus telemetry/metric-refresh consumers.
- [ ] ⚪ Pending Laravel suite lane: run the relevant push/invite-focused Laravel tests plus the Laravel CI-equivalent suite.
- [ ] 🟧 Local-Implemented Docs lane: record the approved extension seam future trigger TODOs must use.
