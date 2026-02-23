# TODO (V1): Definitive Fix for Queue Misconfiguration and Log Explosion
**Version:** 1.0
**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Completed
**Owners:** Laravel + DevOps
**Objective:** Eliminate the root cause of production log explosion by preventing invalid queue driver combinations on MongoDB stacks and enforcing safe log rotation defaults.

## Problem Statement (Confirmed Evidence)
- Production disk reached critical usage due to `/var/www/storage/logs/laravel.log` growing to ~2.3GB.
- Worker loop emitted repeated queue errors:
  - `Call to a member function getAttribute() on null`
  - stack points to `Illuminate\\Queue\\DatabaseQueue` with MongoDB connection.
- Current Laravel config uses:
  - `DB_CONNECTION=mongodb`
  - `QUEUE_CONNECTION=database`
  - `DB_QUEUE_CONNECTION` unset
- This causes Laravel to use SQL `database` queue driver on MongoDB connection instead of MongoDB queue connector.

## Scope (This TODO)
- `laravel-app/config/queue.php`
- `laravel-app/config/logging.php`
- `laravel-app/.env.example`
- Laravel tests for config guardrails (new unit test file)

## Out of Scope
- Full queue architecture redesign (Redis/SQS migration).
- Changing business job semantics.
- Pipeline/governance redesign.

## Planned Changes (Minimal + Robust)
- [x] ✅ Production-Ready `laravel-app/config/queue.php`
  - Add explicit `mongodb` queue connection using package driver.
  - Harden default resolution so MongoDB deployments cannot silently keep unsafe `database` queue default.
  - Keep existing `database` connection for non-Mongo contexts.

- [x] ✅ Production-Ready `laravel-app/.env.example`
  - Set `QUEUE_CONNECTION=mongodb`.
  - Add explicit Mongo queue env knobs (`MONGODB_QUEUE_CONNECTION`, `MONGODB_QUEUE_COLLECTION`, `MONGODB_QUEUE_RETRY_AFTER`).

- [x] ✅ Production-Ready `laravel-app/config/logging.php` + `laravel-app/.env.example`
  - Set safe rotation defaults (`daily`) for stacked logs to avoid unbounded single-file growth.
  - Keep configurability via env, but with production-safe defaults.

- [x] ✅ Production-Ready `laravel-app/tests/Unit/*` (new)
  - Add regression tests that verify:
    - MongoDB deployments resolve to MongoDB queue connector.
    - Unsafe MongoDB + `database` queue combination is guarded.
    - Logging defaults resolve to rotating strategy.

## Operational Mitigation Already Applied (2026-02-23)
- Truncated oversized `laravel.log` on production host.
- Pruned Docker build cache, freeing disk (`/` from ~92% to ~55%).
- Updated production `laravel-app/.env` runtime values:
  - `QUEUE_CONNECTION=mongodb`
  - `LOG_STACK=daily`
  - `LOG_DAILY_DAYS=14`
  - `LOG_LEVEL=info`
- Restarted worker/scheduler and confirmed no new log growth in the verification window.

## Definition of Done
1. Laravel config no longer allows MongoDB stack to run with SQL database queue driver by default.
2. `.env.example` reflects Mongo queue + rotating logs defaults.
3. Regression tests cover this guardrail logic.
4. Existing Laravel test suite remains green (or no regression in touched scope if full suite unavailable).
5. No pipeline file changes required for this fix.

## Validation Plan
1. Local config sanity:
   - `php -r` or unit tests confirm resolved queue driver is `mongodb` under MongoDB settings.
2. Laravel tests:
   - Run targeted unit tests + relevant suite.
3. Runtime smoke (post-deploy):
   - Worker starts without `DatabaseQueue.php` null access error.
   - `storage/logs/laravel*.log` remains bounded/rotated.

## Closure Evidence (2026-02-23)
- Queue guardrails are implemented in `laravel-app/config/queue.php`:
  - Mongo-aware default queue resolution.
  - Explicit `mongodb` queue connection.
  - fail-closed runtime exception on unsafe `DB_CONNECTION=mongodb` + `QUEUE_CONNECTION=database` without dedicated SQL `DB_QUEUE_CONNECTION`.
- Safe logging defaults are implemented in `laravel-app/config/logging.php` (`LOG_STACK=daily`) and reflected in `laravel-app/.env.example` (`LOG_LEVEL=info`, `LOG_DAILY_DAYS=14`).
- Mongo queue defaults are reflected in `laravel-app/.env.example` (`QUEUE_CONNECTION=mongodb`, `MONGODB_QUEUE_*`).
- Regression tests exist in `laravel-app/tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php` covering queue resolution/guard and logging defaults.
