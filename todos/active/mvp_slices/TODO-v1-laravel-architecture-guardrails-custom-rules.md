# TODO (V1): Laravel Architecture Guardrails via Automated Rules

**Status:** Production-Ready  
**Owner:** Laravel Team  
**Scope:** `laravel-app` static architecture guardrails + CI gate

## Objective
Establish automated Laravel architecture rules (custom guardrails) equivalent in spirit to the Flutter custom lint initiative, so architectural drift is detected automatically in local runs and CI.

## Classification
- complexity: `small`
- checkpoint_policy: consolidated

## Approval
- User approved implementation in-session: “Great. Go ahead and implement it, then.”

## Scope
- Add a deterministic architecture guardrail script in `laravel-app`.
- Enforce initial static rules:
  - tenant authenticated routes with ability middleware must include `CheckTenantAccess`.
  - ability strings referenced in route/settings code must exist in `config/abilities.php`.
  - Mongo-backed models must not use `array|json|object` casts.
  - package `src/**` must not depend on `App\\` namespace.
  - package tenant migration directories must be registered in `config/multitenancy.php` tenant paths.
- Wire the guardrail script into Laravel CI as a blocking step.

## Out of Scope (this slice)
- Refactor all existing architectural violations outside what is required for the initial guardrail pass.
- Introduce PHPStan/Larastan/Deptrac in this same slice.
- Alter business logic/endpoint behavior unrelated to guardrail compliance.

## Decision Baseline (Frozen)
- D-01: Guardrails are script-based and deterministic (no external analyzer dependency in this slice).
- D-02: CI gate is blocking for the selected P1 static architecture rules.
- D-03: Ability catalog (`config/abilities.php`) is authoritative for ability-string validation.
- D-04: Mongo model cast ban (`array|json|object`) is enforced at guardrail level.
- D-05: Package boundaries must remain decoupled from `App\\` namespace in `packages/*/*/src/**`.

## Delivery Stages
- [x] ✅ Production-Ready Implement architecture guardrail checker script in `laravel-app`.
- [x] ✅ Production-Ready Add command entrypoint for local execution.
- [x] ✅ Production-Ready Integrate the guardrail command into `.github/workflows/ci.yml` (blocking).
- [x] ✅ Production-Ready Run local validation and fix only required violations for green guardrail gate.
- [x] ✅ Production-Ready Finalize with decision adherence evidence.

## Validation Plan
- Local: run architecture guardrail command and confirm zero violations.
- CI: workflow includes architecture guardrail step before test suite.
- Regression safety: run targeted Laravel tests affected by any minimal fix required by the guardrail introduction.

## Validation Evidence
- Local guardrail run:
  - `docker exec belluga_now_docker-app-1 php /var/www/scripts/architecture_guardrails.php`
  - Result: PASS (zero violations).
- Local composer entrypoint:
  - `docker exec belluga_now_docker-app-1 sh -lc 'cd /var/www && composer run architecture:guardrails'`
  - Result: PASS.
- Regression safety (targeted):
  - `docker exec belluga_now_docker-app-1 sh -lc 'cd /var/www && APP_URL=http://nginx APP_HOST=nginx php artisan test tests/Feature/Ticketing/TicketingAdmissionFlowTest.php --stop-on-failure --stop-on-warning'`
  - Result: PASS (`20 passed`).

## Decision Adherence Validation
| Decision | Status | Evidence | Notes |
| --- | --- | --- | --- |
| D-01 | Adherent | `laravel-app/scripts/architecture_guardrails.php` | Script-based deterministic guardrails implemented without external analyzer dependency. |
| D-02 | Adherent | `laravel-app/.github/workflows/ci.yml` (`Run Laravel architecture guardrails`) | CI now runs guardrails as blocking step before full suite. |
| D-03 | Adherent | `laravel-app/scripts/architecture_guardrails.php` (`LAR-ABILITY-CATALOG` checks) + `laravel-app/config/abilities.php` | Ability catalog used as authoritative source for middleware/settings ability references. |
| D-04 | Adherent | `laravel-app/scripts/architecture_guardrails.php` (`LAR-MONGO-CAST-BAN`) + `packages/belluga/belluga_ticketing/src/Models/Tenants/TicketUnitAuditEvent.php` | Mongo cast-ban enforced; one violation removed for compliance. |
| D-05 | Adherent | `laravel-app/scripts/architecture_guardrails.php` (`LAR-PACKAGE-BOUNDARY`) | Package `src/**` no longer allowed to reference `App\\` namespace by guardrail. |
