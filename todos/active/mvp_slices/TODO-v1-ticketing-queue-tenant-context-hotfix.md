# TODO (V1): Ticketing Queue Tenant Context Hotfix (Immediate)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`.  
**Status:** ✅ Production-Ready (Delivered 2026-03-20)  
**Owners:** Laravel Team  
**Priority:** Immediate  
**Objective:** Eliminate tenant-aware queue failures for `ProcessTicketOutboxJob` by enforcing tenant-scoped dispatch from scheduler.

---

## References
- `laravel-app/config/multitenancy.php`
- `laravel-app/routes/console.php`
- `laravel-app/packages/belluga/belluga_ticketing/src/Jobs/ProcessTicketOutboxJob.php`
- `laravel-app/packages/belluga/belluga_ticketing/src/Models/Tenants/TicketOutboxEvent.php`
- `laravel-app/app/Integration/Events/TenantExecutionContextAdapter.php`
- Runtime symptom observed in production worker logs:
  - `CurrentTenantCouldNotBeDeterminedInTenantAwareJob` for `Belluga\Ticketing\Jobs\ProcessTicketOutboxJob` (`tenantId` missing).

---

## A) Problem Statement
- Queue tenancy is enabled by default (`queues_are_tenant_aware_by_default=true`), so tenant-aware jobs require `tenantId` in payload/context.
- `ProcessTicketOutboxJob` is currently scheduled globally (`Schedule::job(new ProcessTicketOutboxJob)->everyMinute();`) without explicit tenant dispatch context.
- Result: worker tries to process a tenant-aware job without `tenantId`, generating runtime errors and risking ticket outbox delays.

---

## B) Locked Decisions (Immediate Lane)
- [x] ✅ Production-Ready Keep `queues_are_tenant_aware_by_default=true` (no global relaxation).
- [x] ✅ Production-Ready Do **not** classify `ProcessTicketOutboxJob` as non-tenant-aware.
- [x] ✅ Production-Ready Replace global scheduling with tenant-scoped dispatch (`runForEachTenant` + dispatch inside tenant context).
- [x] ✅ Production-Ready Keep ticket outbox storage tenant-isolated (`UsesTenantConnection`) as the source of truth.

---

## C) Implementation Tasks

### C1) Scheduler dispatch hardening
- [x] ✅ Production-Ready Replace global `Schedule::job(new ProcessTicketOutboxJob)` with tenant-scoped scheduler flow in `routes/console.php`.
- [x] ✅ Production-Ready Ensure dispatch occurs while tenant is current (so `tenantId` is serialized into job context).
- [x] ✅ Production-Ready Keep the schedule cadence at every minute and add explicit scheduler name for observability.

### C2) Runtime safety and observability
- [x] ✅ Production-Ready Implement **run-level** tenant telemetry for ticket outbox processing (follow-up lane, now delivered 2026-03-20):
  - `laravel-app/packages/belluga/belluga_ticketing/src/Jobs/ProcessTicketOutboxJob.php`
  - Add one summary log per job run (not per event) with:
    - `tenant_id`
    - `processed_count`
    - `failed_count`
    - `batch_size`
  - Keep payload-safe logging only (do not log event payload/body, secrets, or PII).
  - Preserve existing per-event `processed/failed` logs as operational detail.
- [x] ✅ Production-Ready Confirm no tenant-context queue errors remain in worker logs after rollout.

### C3) Tests and guardrails
- [x] ✅ Production-Ready Add/extend scheduler bootstrap tests to assert ticketing scheduling path is tenant-scoped (no direct global `Schedule::job(new ProcessTicketOutboxJob)` pattern).
- [x] ✅ Production-Ready Keep `schedule:list` bootstrap passing after refactor.
- [x] ✅ Production-Ready Add queue behavior test coverage proving ticket outbox job receives tenant context in scheduled dispatch path.

---

## D) Definition of Done
- [x] ✅ Production-Ready No `CurrentTenantCouldNotBeDeterminedInTenantAwareJob` errors for `ProcessTicketOutboxJob` in stage/main after deploy.
- [x] ✅ Production-Ready Outbox processing continues for tenant queues with no regression in throughput.
- [x] ✅ Production-Ready Tests covering scheduler tenant-scoped dispatch are green.
- [x] ✅ Production-Ready Scheduler contract in `routes/console.php` reflects tenant-scoped dispatch only.
- [x] ✅ Production-Ready Outbox summary telemetry is emitted once per run with `tenant_id` + aggregate counters.

---

## E) Validation Plan
- [x] ✅ Production-Ready `php artisan schedule:list` boots successfully.
- [x] ✅ Production-Ready Targeted test suite for scheduler + ticketing queue path.
- [x] ✅ Production-Ready Post-deploy log validation on worker for 15+ minutes without missing-tenant job failures.
- [x] ✅ Production-Ready Add/extend test assertions to verify summary telemetry shape (`tenant_id`, `processed_count`, `failed_count`) and absence of sensitive payload fields.

---

## F) Delivery Evidence (2026-03-20)
- Scheduler tenant fan-out is active in `laravel-app/routes/console.php`:
  - `ProcessTicketOutboxJob::dispatch();` inside `runForEachTenant(...)`
  - schedule name `ticketing:outbox:process` with `everyMinute()->withoutOverlapping()`.
- Guardrail tests are present and aligned:
  - `laravel-app/tests/Feature/Events/SchedulerBootstrapTest.php`
  - `laravel-app/tests/Feature/Queue/TenantAwareSchedulerRuntimeTest.php`
  - `laravel-app/tests/Unit/Queue/TenantAwareQueueJobsTest.php`
- Tenant-context fix commit lineage:
  - `ef82cb3` is contained in `origin/dev`, `origin/stage`, and `origin/main`.
- Deployment evidence:
  - `belluga_now_docker` stage run `23324424977` concluded `success`.
  - `belluga_now_docker` main run `23324542236` concluded `success`.
- Runtime worker evidence (stage/main, last 30m at validation time):
  - repeated `Belluga\\Ticketing\\Jobs\\ProcessTicketOutboxJob .. RUNNING` / `DONE`
  - no `CurrentTenantCouldNotBeDeterminedInTenantAwareJob` entries detected.
