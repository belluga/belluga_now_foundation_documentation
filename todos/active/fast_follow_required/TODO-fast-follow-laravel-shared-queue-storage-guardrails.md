# TODO (Fast Follow Bugfix): Laravel Shared Queue Storage Guardrails

## Title
Fast Follow Bugfix: Laravel Shared Queue Storage Guardrails

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The production push outage diagnosis is no longer an active push-delivery blocker. The latest runtime evidence recorded during the 2026-05-23 investigation proved:

- post-deploy direct invite pushes no longer remain indefinitely in `scheduled`;
- real production `invite_received` push messages moved to terminal `sent` after the FCM credential was corrected;
- `push_delivery_logs` were materialized, stale tokens were invalidated, and the operator confirmed device receipt;
- the later `invite_accepted` push to the inviter was accepted by FCM and recorded a delivered action;
- the 56 observed tenant-local `jobs` were legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob` payloads, not current `SendPushMessageJob` payloads;
- those jobs failed after manual replay because current code uses `Belluga\\MapPois\\Jobs\\UpsertMapPoiFromAccountProfileJob`, so replaying serialized legacy jobs is not a valid recovery path.

The remaining code risk is narrower and still real: Mongo queue storage can be misconfigured to follow the current tenant database. Current code still resolves `MONGODB_QUEUE_CONNECTION` with fallback to `DB_CONNECTION`, and tests currently accept that fallback. Tenant-aware jobs must carry tenant identity in payload/context, but their physical queue storage must be shared/landlord-owned so the worker can consume them reliably.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `laravel-shared-queue-storage-guardrails`
- **Why this is the right current slice:** one bounded Laravel runtime guardrail: prevent tenant-aware queued jobs from being physically stored in tenant queue collections.
- **Direct-to-TODO rationale:** the runtime evidence and code surface are concrete; no feature decomposition is required.

## Contract Boundary
- This TODO defines the shared-queue guardrail and tests.
- It does not reopen direct invite push delivery, FCM credentials, notification payloads, Android notification rendering, or generic push routing.
- Historical production cleanup remains manual/operational unless a separate approved data-repair TODO is opened.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Fast-Follow`, `Laravel`, `Queue-Guardrail`, `Runtime-Hardening`, `Narrowed`
- **Next exact step:** implement failing tests for queue connection fallback and tenant-current dispatch, then change queue configuration to fail closed when Mongo queue storage resolves to tenant-owned storage.

## Scope
- [x] Record that the post-deploy push delivery pipeline is healthy and no longer reproduces the original `scheduled`/no-log failure.
- [x] Record that the 56 tenant-local jobs were stale legacy Map POI jobs, not current push jobs.
- [x] Record that stale serialized Map POI jobs must not be replayed as a code fix.
- [ ] Change Laravel queue configuration so Mongo queue storage has an explicit shared/landlord default and cannot silently follow tenant `DB_CONNECTION`.
- [ ] Fail closed when `QUEUE_CONNECTION=mongodb` resolves to `tenant` or the configured tenant database connection.
- [ ] Update `.env.example` so the canonical Mongo queue connection points to the shared queue surface.
- [ ] Add guardrail tests that reject tenant-owned Mongo queue storage.
- [ ] Add regression coverage proving tenant-current dispatch of representative tenant-aware jobs writes to shared queue storage and leaves tenant `jobs` empty.
- [ ] Update invite push queue coverage only as a regression proof that push still exits `scheduled`; do not treat push delivery as the primary open bug in this TODO.

## Out of Scope
- Replacing Mongo queue with Redis/SQS.
- Reworking Spatie tenant-aware job semantics.
- Replaying historical serialized queue jobs.
- Restoring legacy `App\\Jobs\\MapPois\\UpsertMapPoiFromAccountProfileJob` aliases only to process stale data.
- Changing push message authoring, FCM payloads, icon/image, or notification tap routing.
- FCM private-key newline normalization, already tracked in `foundation_documentation/todos/active/vnext/TODO-vnext-fcm-private-key-newline-normalization.md`.

## Definition of Done
- [ ] Mongo queue configuration defaults to shared/landlord storage and cannot follow the active tenant database.
- [ ] Unsafe queue env combinations fail closed with actionable messages.
- [ ] `.env.example` documents the safe queue connection values.
- [ ] Tests prove representative tenant-aware jobs are stored in shared queue storage when a tenant context is active.
- [ ] Tests prove tenant `jobs` remains empty for the representative dispatch path.
- [ ] Focused push/invite coverage still proves direct invite push exits `scheduled` with a terminal state or delivery log.

## Validation Steps
- [ ] Run focused queue config guardrail tests.
- [ ] Run focused tenant-aware queue storage regression tests.
- [ ] Run focused invite push regression tests if touched.
- [ ] Run the Laravel CI-equivalent safe runner before claiming `Local-Implemented`.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | `Record that the post-deploy push delivery pipeline is healthy and no longer reproduces the original scheduled/no-log failure.` | `runtime evidence` | Existing 2026-05-23 production evidence in superseded push/jobs ledger and promotion-lane push TODOs. | `main` | `passed` | Kept as historical context; not active implementation. |
| `SCOPE-02` | `Scope` | `Record that the 56 tenant-local jobs were stale legacy Map POI jobs, not current push jobs.` | `runtime evidence` | User-provided exported jobs + production failed_jobs diagnosis. | `main` | `passed` | No current `SendPushMessageJob` tenant-local evidence. |
| `SCOPE-03` | `Scope` | `Record that stale serialized Map POI jobs must not be replayed as a code fix.` | `decision` | This TODO context/out-of-scope. | `docs` | `passed` | Recovery path, if needed, is current projection rebuild tooling. |
| `DOD-01` | `Definition of Done` | `Mongo queue configuration defaults to shared/landlord storage and cannot follow the active tenant database.` | `code+test` | `planned` | `local Laravel` | `planned` | Must replace current fallback behavior. |
| `DOD-02` | `Definition of Done` | `Unsafe queue env combinations fail closed with actionable messages.` | `test` | `planned` | `local Laravel` | `planned` | Includes `MONGODB_QUEUE_CONNECTION=tenant`. |
| `DOD-03` | `Definition of Done` | `.env.example documents the safe queue connection values.` | `docs` | `planned` | `laravel-app` | `planned` | Must match runtime config. |
| `DOD-04` | `Definition of Done` | `Tests prove representative tenant-aware jobs are stored in shared queue storage when a tenant context is active.` | `test` | `planned` | `local Laravel` | `planned` | Representative jobs should include push and at least one non-push tenant-aware job. |
| `DOD-05` | `Definition of Done` | `Tests prove tenant jobs remains empty for the representative dispatch path.` | `test` | `planned` | `local Laravel` | `planned` | Guards recurrence of tenant-local queues. |
| `DOD-06` | `Definition of Done` | `Focused push/invite coverage still proves direct invite push exits scheduled with a terminal state or delivery log.` | `test` | `planned` | `local Laravel` | `planned` | Regression only; direct push delivery is not reopened as active bug. |

## Current Code-Cross Findings
- `laravel-app/config/queue.php` still uses `env('MONGODB_QUEUE_CONNECTION', $databaseConnection)`.
- `laravel-app/tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php` still has `test_falls_back_to_primary_database_connection_when_mongodb_queue_connection_is_not_explicitly_set()`, which codifies the unsafe fallback as expected behavior.
- `laravel-app/.env.example` currently sets `QUEUE_CONNECTION=mongodb` and `MONGODB_QUEUE_CONNECTION=mongodb`; the default must remain explicit and safe.
- `laravel-app/tests/Unit/Queue/TenantAwareQueueJobsTest.php` proves jobs implement `TenantAware`, but does not prove physical shared queue storage.

## Decision Baseline
- [x] `D-01` Tenant-aware jobs must be physically stored in shared/landlord queue storage.
- [x] `D-02` Tenant identity belongs in job payload/context, not in queue database selection.
- [x] `D-03` `MONGODB_QUEUE_CONNECTION=tenant` is invalid and must fail closed.
- [x] `D-04` Historical legacy Map POI queue documents must not be replayed automatically.
- [x] `D-05` Current push delivery is healthy enough that this TODO is a queue guardrail, not a broad push outage TODO.
- [x] `D-06` Any new post-deploy push regression must open or use a specific push TODO, not this queue-storage guardrail.

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| `laravel shared queue guardrail` | `pending` | `pending` | `pending` | `pending` | `pending` |
| `foundation TODO cleanup` | `foundation_documentation:main working tree` | `n/a` | `n/a` | `pending` | `in progress` |
