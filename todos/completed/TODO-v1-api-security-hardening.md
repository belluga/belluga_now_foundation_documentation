# TODO (V1): API Security Hardening (Platform Baseline)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team / Platform Security
**Objective:** Establish a platform-wide API security hardening baseline that is production-safe with balanced friction (rate limiting, anti-abuse controls, replay protection, challenge/recovery policy, and abuse-signal governance) and consumed consistently by all packages.

> Origin: extracted from ticketing `TKT-41` to avoid domain-local security policy drift and keep API security decisions centralized inside MVP execution.

---

## Scope
- Define global API anti-abuse policy model and inheritance hierarchy.
- Define canonical protection levels (`L1 Core`, `L2 Balanced`, `L3 High Protection`) and where they apply.
- Define edge-vs-application security responsibility split for Cloudflare-fronted traffic.
- Define replay/idempotency baseline across critical mutation endpoints.
- Define challenge/soft-block/hard-block lifecycle and recovery windows.
- Define abuse-signal audit/privacy policy (what is captured, retention, masking, and access).
- Define package integration contract so domains (ticketing, settings, events, future modules) consume the same guard semantics.
- Define endpoint risk matrix + rollout path (`observe_mode` -> enforced mode) to control false positives before hard enforcement.

---

## Out of Scope
- Product-specific commercial rules (pricing, promotions, entitlement transitions).
- Provider/gateway-specific fraud systems.
- Frontend anti-bot UX implementation details.

---

## Standards/Exception Reference (Locked)
- `foundation_documentation/todos/active/vnext/TODO-v1-ticketing-package-integration.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-checkout-package-integration.md`
- `foundation_documentation/project_mandate.md`

---

## Pending Decisions (Proposed Here, Pending Validation)
- [x] ✅ Production‑Ready `SEC-01` Global policy ownership and contract model.
  - Proposed decision:
    - API security baseline is platform-owned and consumed through shared contracts/policies by all domains.
    - Shared contract is additive and transport-stable: no path redesign required, only policy metadata/headers/error taxonomy harmonization.
    - Cloudflare is the mandatory edge layer; Laravel remains source-of-truth for business/security decisions (auth, abilities, tenant access, idempotency/replay, deterministic rejection mapping).
  - Validation gate:
    - No package defines a conflicting protection-level universe for shared API entrypoints.

- [x] ✅ Production‑Ready `SEC-02` Threshold profile model and hierarchy.
  - Proposed decision:
    - Canonical levels are centrally defined:
      - `L1 Core`: low-friction baseline for low-risk/public/read-heavy endpoints.
      - `L2 Balanced`: default production baseline for most authenticated APIs and non-financial writes.
      - `L3 High Protection`: critical mutation operations (purchase/reservation/check-in/auth recovery/admin-sensitive writes).
    - Resolution hierarchy is `system_default -> tenant_override -> endpoint_override`.
    - Overrides can only increase protection (`L1 -> L2 -> L3`) and cannot weaken below global minimum controls.
    - Global default level is `L2 Balanced`.
    - Cloudflare responsibilities:
      - edge DDoS/WAF/bot controls and coarse IP/ASN throttling.
    - Laravel responsibilities:
      - principal/account-scoped throttling, mutation safety, and deterministic business/security rejection semantics.
  - Validation gate:
    - Effective level resolution is deterministic, auditable, and monotonic (no downgrade path).

- [x] ✅ Production‑Ready `SEC-03` Replay/idempotency baseline.
  - Proposed decision:
    - Idempotency key + replay-window validation is mandatory for `L3 High Protection` critical mutations.
    - Idempotency is selectively required for `L2 Balanced` writes that can duplicate side effects.
    - Idempotency is optional for `L1 Core` unless route risk explicitly requires it.
    - Deterministic rejection/error contract for missing/replayed/expired/malformed idempotency inputs.
  - Validation gate:
    - Replay simulations do not duplicate side effects and do not break legitimate retries.

- [x] ✅ Production‑Ready `SEC-04` Challenge/block lifecycle.
  - Proposed decision:
    - Progressive lifecycle: `observe -> warn -> soft_block -> temporary_hard_block`.
    - `L1 Core` uses telemetry-only or coarse throttle responses (no interactive challenge by default).
    - `L2 Balanced` allows soft-block + cooldown with deterministic recovery.
    - `L3 High Protection` allows challenge step + temporary hard-block under repeated high-confidence abuse.
    - Cloudflare challenges/WAF actions are the first line for edge abuse; Laravel escalation remains mandatory for principal-aware abuse and replay/idempotency violations.
    - Policy must preserve legitimate retry paths and define support override/unblock procedures.
  - Validation gate:
    - False-positive handling path is explicit, operationally supportable, and measurable.

- [x] ✅ Production‑Ready `SEC-05` Abuse-signal privacy and retention.
  - Proposed decision:
    - Define minimal signal schema with pseudonymization-by-default for principal/device/network identifiers.
    - Define tiered retention:
      - short-lived raw/high-cardinality abuse signals,
      - longer-lived aggregated counters/metrics.
    - Enforce role-based access and audit logs for abuse-signal reads.
  - Validation gate:
    - Auditability and privacy requirements are both satisfied without over-collecting PII.

- [x] ✅ Production‑Ready `SEC-06` Cross-domain integration contract.
  - Proposed decision:
    - Expose a stable shared contract (for example `AbuseGuardContract`) and canonical error taxonomy for all modules.
    - Contract includes deterministic machine-readable decisions and metadata (`retry_after`, `correlation_id`, `cf_ray_id` when present, block/challenge reason codes).
    - Standard headers/error envelope must be shared across ticketing/events/settings/checkout.
  - Validation gate:
    - Ticketing/events/settings/checkout can consume the same guard contract without local policy drift.

---

## Tasks
- [x] ✅ Production‑Ready Define canonical anti-abuse policy schema, settings namespaces, and level definitions (`L1/L2/L3`).
- [x] ✅ Production‑Ready Build endpoint risk matrix and assign every covered route to `L1`, `L2`, or `L3`.
- [x] ✅ Production‑Ready Define and document level resolution algorithm (`system_default -> tenant_override -> endpoint_override`) with monotonic override constraints.
- [x] ✅ Production‑Ready Define Cloudflare edge policy mapping (WAF/challenge/rate-limit) aligned to endpoint levels and avoid duplicated conflicting throttles.
- [x] ✅ Production‑Ready Implement shared guard contract + middleware/service wiring.
- [x] ✅ Production‑Ready Implement replay/idempotency verifier and deterministic error mapping aligned to level policy.
- [x] ✅ Production‑Ready Implement progressive challenge/block lifecycle engine and recovery logic.
- [x] ✅ Production‑Ready Implement abuse-signal store, retention jobs, and access controls.
- [x] ✅ Production‑Ready Enforce Cloudflare-only origin path (origin firewall/allowlist) and trusted proxy/header parsing (`CF-Connecting-IP`) in Laravel runtime.
- [x] ✅ Production‑Ready Add traceability propagation (`CF-Ray` -> API logs/telemetry) linked with `correlation_id`.
- [x] ✅ Production‑Ready Implement `observe_mode` instrumentation and rollout controls before enforcement.
- [x] ✅ Production‑Ready Extend Laravel architecture guardrail/lint checks to enforce endpoint security level mapping + mandatory `L3` idempotency/replay controls.
- [x] ✅ Production‑Ready Provide package integration guide and migration notes for ticketing/events/checkout.
- [x] ✅ Production‑Ready Synchronize README and foundation docs with final baseline.

---

## Validation Steps
- [x] ✅ Production‑Ready Cross-domain contract tests (ticketing + events + settings + checkout) against the same guard semantics.
- [x] ✅ Production‑Ready Replay/idempotency stress tests on critical mutation endpoints.
- [x] ✅ Production‑Ready Rate-limit/challenge tests validating abuse mitigation without unacceptable false positives.
- [x] ✅ Production‑Ready Origin-path validation: direct-to-origin access blocked; Cloudflare path remains healthy.
- [x] ✅ Production‑Ready Header-trust validation: spoofed client IP headers rejected/ignored unless request comes through trusted proxy chain.
- [x] ✅ Production‑Ready Privacy/audit tests validating retention and access controls.
- [x] ✅ Production‑Ready Architecture guardrail/lint validation proving policy violations fail checks (local + CI).
- [x] ✅ Production‑Ready Rollout validation: observe-mode metrics reviewed (false positives, latency overhead, challenge rate) before enforcement.
- [x] ✅ Production‑Ready `php artisan test` (full Laravel suite).

---

## Definition of Done
- [x] ✅ Production‑Ready A single platform security baseline exists and is referenced by domain packages.
- [x] ✅ Production‑Ready Every in-scope endpoint is mapped to `L1`, `L2`, or `L3`; default is `L2`.
- [x] ✅ Production‑Ready Edge/app split is explicit: Cloudflare covers edge controls while Laravel enforces principal-aware and mutation-safety controls.
- [x] ✅ Production‑Ready Level, replay, challenge, and privacy policies are deterministic and documented.
- [x] ✅ Production‑Ready Observe-mode evidence exists before enforce-mode rollout.
- [x] ✅ Production‑Ready Laravel architecture guardrail/lint checks enforce critical security policy invariants in CI.
- [x] ✅ Production‑Ready Ticketing `TKT-41` advanced hardening is closed via this TODO (traceability: `SEC-00` + global baseline extraction completed).
- [x] ✅ Production‑Ready Documentation and tests prove no cross-package policy drift.

---

## Implementation Snapshot (2026-03-08)

### Delivered (this cycle)
- `laravel-app_api-security-hardening/config/api_security.php`
  - Canonical `L1/L2/L3` policy, generated route risk matrix/overrides, tenant override support, lifecycle windows, abuse-signal policy, and Cloudflare edge mapping.
- `laravel-app_api-security-hardening/app/Http/Middleware/ApiSecurityHardening.php`
  - Global middleware with deterministic security envelope (`code`, `message`, `retry_after`, `correlation_id`, `cf_ray_id`), monotonic level resolution (`system -> tenant -> endpoint`), replay/idempotency controls, trusted-proxy spoof checks, Cloudflare origin lock, lifecycle escalation (`warn -> challenge_required -> soft_blocked -> hard_blocked`), and abuse-signal persistence.
- `laravel-app_api-security-hardening/app/Application/Security/ApiAbuseSignalRecorder.php`
  - Pseudonymized abuse-signal recorder with raw + aggregate persistence, retention pruning, and reporting helpers.
- `laravel-app_api-security-hardening/app/Models/Landlord/ApiAbuseSignal.php`
- `laravel-app_api-security-hardening/app/Models/Landlord/ApiAbuseSignalAggregate.php`
- `laravel-app_api-security-hardening/database/migrations/landlord/2026_03_08_000700_create_api_abuse_signal_collections.php`
  - New collections + indexes + TTL retention for raw/aggregate abuse signals.
- `laravel-app_api-security-hardening/app/Http/Api/v1/Controllers/Security/ApiAbuseSignalsController.php`
- `laravel-app_api-security-hardening/routes/api/project_landlord_admin_api_v1.php`
- `laravel-app_api-security-hardening/config/abilities.php`
  - Access-controlled security read APIs (`security-signals:read`, `security-signals:read-raw`) with read-audit logging.
- `laravel-app_api-security-hardening/routes/console.php`
  - Added abuse-signal prune/report commands and scheduled retention cleanup.
- `laravel-app_api-security-hardening/scripts/architecture_guardrails.php`
  - Extended `LAR-API-SECURITY-BASELINE` guardrails for risk matrix/overrides, monotonic level invariants, Cloudflare trust policy, and `L3` idempotency expectations.
- `laravel-app_api-security-hardening/tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php`
  - Expanded coverage for replay/idempotency, lifecycle gates, origin lock, trusted-proxy spoof rejection, tenant monotonic overrides, cross-domain policy parity, and limiter fail-open/fail-closed behavior.
- `laravel-app_api-security-hardening/tests/Feature/Security/ApiAbuseSignalsControllerTest.php`
  - Coverage for aggregate/raw access controls, summary output, and audit logging.
- `laravel-app_api-security-hardening/phpunit.xml`
  - Added `Feature-Security` suite wiring.

### Validation Evidence (this cycle)
- Targeted formatting/lint:
  - `./vendor/bin/pint --test` on all changed hardening files => PASS (13 files).
- Guardrails:
  - `php scripts/architecture_guardrails.php` => PASS
- Feature tests:
  - `php artisan test tests/Feature/Security/ApiSecurityHardeningMiddlewareTest.php tests/Feature/Security/ApiAbuseSignalsControllerTest.php` => PASS (20 tests)
- Full Laravel suite:
  - `php artisan test` (CI-parity env, `APP_LOCALE=en`) => PASS (`843 passed`, `0 failed`)
- Local+Cloudflare confidence gate:
  - `RUN_ID=api-security-confidence-local-cloudflare-20260308-1812 ./scripts/security_confidence_pack.sh` => PASS
  - Artifacts: `foundation_documentation/artifacts/tmp/api-security-confidence-local-cloudflare-20260308-1812/summary.txt` (`gate_result=PASS`)
  - `RUN_ID=api-security-confidence-local-cloudflare-20260308-2 ./scripts/security_confidence_pack.sh` => PASS
  - Artifacts: `foundation_documentation/artifacts/tmp/api-security-confidence-local-cloudflare-20260308-2/summary.txt` (`gate_result=PASS`)
- Flutter security-envelope alignment:
  - `fvm flutter analyze` => PASS
  - `fvm flutter test` (full suite) => PASS (`331 passed`, `0 failed`)

### Closure Notes
- This TODO is now the canonical closure for ticketing `TKT-41` advanced hardening baseline extraction (`SEC-00`).
- Cloudflare is treated as mandatory edge; Laravel remains source-of-truth for principal-aware enforcement and deterministic API rejection semantics.

---

## Decision Log
- `SEC-00`: Decided. API security hardening is platform-wide and extracted from ticketing-local pending decision `TKT-41`.
- `SEC-01`: Approved and implemented (platform-owned contract; additive/no route redesign).
- `SEC-02`: Approved and implemented for `system_default -> tenant_override -> endpoint_override` (global `L2`; monotonic escalation only).
- `SEC-03`: Approved and implemented (`L3` mandatory idempotency + deterministic rejection taxonomy).
- `SEC-04`: Approved and implemented (lifecycle escalation + recovery windows + deterministic headers/envelope).
- `SEC-05`: Approved and implemented (pseudonymized abuse-signal model, retention, access control, and read audit).
- `SEC-06`: Approved and implemented (shared guard envelope/headers consumed by tenant-admin repositories and form error parser alignment).
