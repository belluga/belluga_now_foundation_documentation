# TODO (Post Release Hardening): Account-Scoped Token / Ability Binding

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The security pass in the architectural drift review found that account-scoped abilities are copied into Sanctum tokens without being bound strongly enough to the currently selected account context.

This creates a broken-function-authorization risk for users who belong to more than one account with different role/ability levels.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-05`
- **Why this is the right current slice:** this TODO isolates one exact authorization drift: account-scoped permissions must not leak across account boundaries through token reuse.

## Contract Boundary
- This TODO owns the account-binding model for account-scoped auth tokens and/or middleware recomputation.
- It owns mixed-role multi-account regression coverage.
- It does **not** own unrelated tenant-public OTP behavior or broader identity redesign.

## Drift Guardrail Requirement
- This TODO belongs to the account-authorization / cross-scope token drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixture set from current token issuance and access checks.

## Violated Canonical Rule
- Account-scoped routes must authorize against the current account context, not only against abilities copied from another account membership into a token.

## Replacement Canonical Rule
- Account-scoped access must be bound to the selected `account_id` or revalidated against current-account membership/ability at request time so permissions from Account A cannot satisfy Account B routes accidentally.

## Strongest Objective PACED Guardrail
- Laravel feature/unit tests for mixed-role multi-account users.
- Regression tests for token issuance, route access, and current-account mismatch.
- Deterministic middleware/policy coverage proving the current account context is enforced.

## Real Drift Fixtures
- `laravel-app/routes/api/account_api_v1.php`
- `laravel-app/routes/api/project_account_api_v1.php`
- `laravel-app/routes/api/packages/project_account_api_v1/push_handler.php`
- `laravel-app/bootstrap/app.php`
- `laravel-app/app/Http/Middleware/CheckUserAccess.php`
- `laravel-app/app/Application/Auth/AccountAuthenticationService.php`
- `laravel-app/app/Application/Auth/TenantScopedAccessTokenService.php`
- Drift-review finding `SEC-DRIFT-002`

## Selected Canonical Model
- **Model:** hybrid account binding.
- Account-user bearer tokens are stamped with `account_id` at issue/stamp time.
- Account-scoped routes reject bearer tokens with missing or mismatched `account_id` against `Account::current()`.
- Sanctum token abilities are only an authorization ceiling.
- The token ceiling is wildcard-aware for account-workspace resources: literal `*`, exact ability strings, and resource wildcards such as `account-users:*`, `events:*`, and `push-messages:*` can satisfy the ceiling only before live current-account role permissions are revalidated.
- `AccountUser::tokenCan()` revalidates live current-account role permissions only when the `account` middleware establishes account-scoped auth context, so stale copied abilities from Account A cannot satisfy Account B routes and ambient tenant-level `Account::current()` state cannot create false denials.
- Account-scoped token issuance fails closed when an account-scoped ability is requested without an explicit/current/single-access account binding; it must not create reusable unbound account-workspace bearer tokens.
- Account-prefixed package routes that depend on `Account::current()` must use the `account` middleware or an equivalent fail-closed binding guard; RR-AUTH-03 explicitly reconciles push message `data` and `actions` routes to this rule.
- The implementation remains Laravel-local in the host app account-auth/token/middleware surfaces; no reusable package boundary is introduced for this slice.

## Delivery Status Canon
- **Current delivery stage:** `Triple audit accepted-debt closure recorded; Claude fourth-auditor operational failure recorded; RR-AUTH-03 closure-ready`
- **Qualifiers:** `Post-Release-Hardening`, `Laravel`, `Security`, `Account-Auth`
- **Worker code checkpoint reconciled in principal:** `cd0da71806f9459ae8daa4b4821d9ec434c643ac` (`RR-AUTH-03 bind account tokens to account authorization context`)
- **Fix worker checkpoint reconciled in principal:** `41286b0296a8ea375081aba1047f8fe6fe84022b` (`fix: scope account permission revalidation to account routes`)
- **Token-ceiling / issuer fix worker reconciled in principal:** staged principal delta from `Gauss` subagent checkpoint; no standalone Laravel commit yet.
- **Audit-blocker correction worker reconciled in principal:** staged principal delta from `Poincare` subagent checkpoint; no standalone Laravel commit yet.
- **Second correction worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-worker-dispatch-20260507T1606Z.md` (`Averroes`, `019e0333-b583-7822-80d1-eab6c264aee4`)
- **Second correction ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-ledger-20260507T1624Z.md`
- **Runtime invariant worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-worker-dispatch-20260507T1636Z.md` (`Euclid`, `019e034d-f937-7cf0-8f3a-3938d144bba3`)
- **Runtime invariant ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-ledger-20260507T1655Z.md`
- **Clean bounded rerun ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-clean-bounded-rerun-ledger-20260507T194907Z.md`
- **Fresh critique/final correction ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-critique-final-correction-ledger-20260507T2029Z.md`
- **Fresh audit normalization ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-fresh-audit-normalization-ledger-20260507T2045Z.md`
- **Test-quality follow-up ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-test-quality-followup-ledger-20260507T2059Z.md`
- **Audit-floor acceptance ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-audit-floor-acceptance-ledger-20260507T2108Z.md`
- **Single-baseline rerun ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-single-baseline-rerun-ledger-20260507T2152Z.md`
- **Triple-audit session:** `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package-triple-audit-20260507T211322Z/session.json`
- **Triple-audit round-01 resolution:** `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package-triple-audit-20260507T211322Z/round-01/resolution.md`
- **Triple-audit round-02 resolution:** `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package-triple-audit-20260507T211322Z/round-02/resolution.md`
- **Claude fourth-auditor record:** `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-03-account-token-binding-claude-comparison-20260507T2202Z.md`
- **Next exact step:** advance the orchestration lane to `RR-AUTH-04`, carrying `PERF-RR-AUTH-03-001` as accepted low debt and retaining the Claude operational-failure record for later auditor-performance comparison.

## Current Authority Summary

- **Governing live closure baseline:** this TODO delivery status, completion evidence rows `TODO-local audit-floor reviews`, `Triple audit`, and `Claude fourth-auditor comparison`, the `Fresh Audit Normalization Ledger` section below, the `Single-Baseline Rerun Ledger` section below, and the RR-AUTH-03 hardening package `Current Proof Snapshot`.
- **Historical provenance only:** older blocker ledgers through `20260507T2029Z` remain required traceability, but their open/pending status cells are not the current RR-AUTH-03 closure decision once the normalization ledger supersedes them.

## Package-First Assessment
- **Queries executed:**
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "account auth token"`
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "sanctum ability"`
  - `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "authorization"`
- **Relevant packages found:** none.
- **READMEs read:** none; no matching package entries were returned.
- **Decision:** implement locally in `laravel-app` account-auth/token/middleware surfaces.
- **Tier:** host application implementation.
- **Rationale:** RR-AUTH-03 hardens existing application-owned account-scoped authorization paths rather than introducing reusable package capability.

## Scope
- [x] Decide the canonical account-binding model for account-scoped access.
- [x] Harden the issuing and/or checking path so permissions cannot bleed across accounts.
- [x] Add mixed-role multi-account regression coverage using the current code paths as fixtures.
- [x] Promote the stable authorization contract into canonical docs.

## Out of Scope
- [ ] Tenant-public OTP challenge/verify behavior.
- [ ] General role/ability redesign outside account-binding semantics.
- [ ] Unrelated tenant-admin authorization cleanup.

## Definition of Done
- [x] A user cannot satisfy Account B route authorization with Account A abilities.
- [x] Mixed-role multi-account regression scenarios are automated.
- [x] Canonical docs describe the selected account-binding model.
- [x] Real drift fixtures are covered by tests.

## Validation Steps
- [x] Add Laravel regression tests for multi-account permission bleed.
- [x] Run targeted account-auth suites.
- [x] Run the final Laravel CI-equivalent suite required by the execution plan.

## Test Strategy Provenance

- **Authority-surface strategy:** `brownfield regression coverage`
- Preserved fail-first/red-run artifacts are not available in the current RR-AUTH-03 authority surface because the slice was reconciled from an already-mutated local worker/orchestrator lane before the canonical packet was normalized.
- RR-AUTH-03 therefore claims named regression coverage plus focused rerun evidence, not preserved TDD/red-run provenance.

## Frontend / Consumer Matrix

| Producer Surface | Consumer Status | Evidence / Waiver |
| --- | --- | --- |
| Account-user token issuance / stamp path | `consumer contract clarified` | Existing authenticated account/workspace consumers continue to receive bearer tokens, but account-user tokens now carry server-stamped `account_id`. No Flutter request/response schema expansion is required for this slice. |
| Account-scoped Laravel routes using `Account::current()` | `consumer implemented + evidenced` | Account-scoped consumers now depend on fail-closed current-account token matching. Missing or mismatched token `account_id` rejects before copied ability strings can authorize a different account. |
| Sanctum token abilities | `consumer contract clarified` | Abilities remain a ceiling only. Consumers must not infer account authorization from ability strings alone; live current-account role permissions are revalidated only inside account-scoped auth context. |
| Flutter module canonical docs | `promoted` | `foundation_documentation/modules/flutter_client_experience_module.md` now carries the account-bound bearer token domain rule and FCX decision row. |

## Local CI-Equivalent Suite Matrix

| Lane | Command / Evidence | Status | Outcome |
| --- | --- | --- | --- |
| Worker tenant token suite | worker-local `TenantPublicAccountTokenScopeTest` lane | `passed` | `9 passed`. |
| Worker account auth unit suite | worker-local `AccountAuthenticationServiceTest` lane | `passed` | `4 passed`. |
| Worker account API/controller suite | worker-local `AccountUserControllerTest` lane | `passed` | `5 passed`. |
| Worker account boundary event suite | worker-local `EventCrudControllerTest --filter=account_auth_boundary` lane | `passed` | `1 passed`. |
| Principal targeted RR-AUTH-03 suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Accounts/AccountUserControllerTest.php` | `passed` | `19 passed`, `40 assertions`, `30.28s`. |
| Principal targeted RR-AUTH-03 suite after route-binding fix | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Accounts/AccountUserControllerTest.php` | `passed` | serial rerun passed with `19 passed`, `40 assertions`, `42.87s`. |
| Principal focused push regression after route-binding fix | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter='account_push_message_data_and_actions_reject|tenant_cross_tenant_data_and_actions_return_not_found'` | `passed` | serial rerun passed with `3 passed`, `12 assertions`, `16.38s`; earlier parallel runner overlap was discarded as harness contamination because concurrent `dropDatabase` operations collided. |
| Principal targeted RR-AUTH-03 suite after token-ceiling/issuer fix | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Accounts/AccountUserControllerTest.php` | `passed` | orchestrator serial rerun passed with `25 passed`, `55 assertions`, `43.77s`. |
| Principal focused push regression after token-ceiling/issuer fix | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter='account_push_message_data_and_actions|tenant_cross_tenant_data_and_actions_return_not_found'` | `passed` | orchestrator serial rerun passed with `4 passed`, `16 assertions`, `14.42s`. |
| Principal post-1521Z audit-blocker correction suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php` | `passed` | orchestrator serial rerun passed with `153 passed`, `484 assertions`, `126.39s`. |
| Principal stale ambient account request-path regression after 1552Z correction | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter=test_tenant_push_route_accepts_account_bound_token_despite_stale_ambient_account` | `passed` | orchestrator serial rerun passed with `1 passed`, `5 assertions`, `7.58s`. |
| Principal focused RR-AUTH-03 suite after 1552Z correction | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` | `passed` | orchestrator serial rerun passed with `154 passed`, `489 assertions`, `101.75s`. |
| Principal direct runtime invariant regression after 1624Z audit | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter='direct_account_user_create_token|no_current_account_issuance_after_stale_account_context'` | `passed` | orchestrator serial rerun passed with `3 passed`, `11 assertions`, `10.09s`. |
| Principal focused RR-AUTH-03 suite after runtime invariant fix | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php tests/Feature/Push/PushMessageFlowTest.php tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php` | `passed` | orchestrator serial rerun passed with `157 passed`, `500 assertions`, `141.44s`. |
| Clean bounded account middleware tranche | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Accounts/Middleware/T1A1Test.php tests/Api/v1/Accounts/Middleware/T1A2Test.php tests/Api/v1/Accounts/Middleware/T2A1Test.php tests/Api/v1/Accounts/Middleware/T2A2Test.php` | `passed` | clean RR-AUTH-03 validation tree rerun passed with `56 passed`, `112 assertions`, `14.57s`. |
| Principal account boundary event regression | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php --filter=account_auth_boundary` | `passed` | `1 passed`, `5 assertions`, `7.81s`. |
| Principal architecture guardrails | `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed` | `[ARCH-GUARDRAILS] PASS`. |
| Principal architecture guardrails final evidence | architecture guardrails lane | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| Clean bounded architecture guardrails | `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed` | clean RR-AUTH-03 validation tree rerun passed with `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| Principal Pint check | `docker compose exec -T app ./vendor/bin/pint --test <9 changed files>` | `passed` | `PASS`, `9 files`. |
| Principal Pint check after token-ceiling/issuer fix | `docker compose exec -T app ./vendor/bin/pint --test <9 changed files>` | `passed` | `PASS`, `9 files`. |
| Principal Pint check after 1521Z audit-blocker correction | `docker compose exec -T app ./vendor/bin/pint --test <6 changed files>` | `passed` | `PASS`, `6 files`. |
| Principal Pint check after 1552Z correction | `docker compose exec -T app ./vendor/bin/pint --test <10 changed files>` | `passed` | `PASS`, `10 files`. |
| Principal Pint check after runtime invariant fix | `docker compose exec -T app ./vendor/bin/pint --test <10 changed files>` | `passed` | `PASS`, `10 files`. |
| Principal diff hygiene | `git diff --check` and `git diff --cached --check` | `passed` | no whitespace errors reported. |
| Legacy combined account API auth/middleware batch | worker-local legacy combined batch | `blocked` | Blocked by fixture/harness issues classified by the code worker as non-product failures. |
| Integrated Laravel CI-equivalent suite | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `passed` | `1383 passed`, `6554 assertions`, `794.12s`; includes the current dirty Laravel tree with unrelated `RR-AUTH-01` changes present, so this is supporting integrated-state evidence only after the clean bounded rerun. |
| Clean bounded Laravel CI-equivalent suite | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | `passed` | clean RR-AUTH-03 validation tree rerun passed with `1368 passed`, `6373 assertions`, `712.78s`; this is the bounded full-suite attribution record that addresses `VDA-005`. |
| Principal post-2011Z critique/final correction token + event route suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter='test_validated_issuer_context_cannot_be_opened_outside_token_service|test_agenda_accepts_current_tenant_account_token|test_account_event_candidates_route_accepts_persisted_bearer_token_bound_to_current_account|test_account_event_candidates_route_rejects_persisted_bearer_token_bound_to_another_account'` | `passed` | `4 passed`, `10 assertions`, `15.14s`. |
| Principal post-2052Z test-quality follow-up token + event route suite | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php --filter='test_validated_issuer_context_cannot_be_opened_outside_token_service|test_agenda_accepts_current_tenant_account_token|test_account_event_candidates_route_accepts_persisted_bearer_token_bound_to_current_account|test_account_event_candidates_route_rejects_persisted_bearer_token_bound_to_another_account|test_account_event_candidates_route_rejects_persisted_bearer_token_without_candidate_ability'` | `passed` | `5 passed`, `11 assertions`, `19.55s`; this is the current route-specific persisted-token event proof slice, including the low-ceiling denial case. |
| Principal post-2011Z critique/final correction push regression | `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Push/PushMessageFlowTest.php --filter='account_push_message_data_and_actions'` | `passed` | `4 passed`, `13 assertions`, `16.75s`. |
| Principal architecture guardrails after 2011Z critique/final correction | `docker compose exec -T app php scripts/architecture_guardrails.php` | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` |
| Principal Pint check after 2011Z critique/final correction | `docker compose exec -T app ./vendor/bin/pint --test app/Models/Tenants/AccountUser.php tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php` | `passed` | `PASS`, `2 files`. |

## Completion Evidence Matrix

| Requirement | Status | Evidence |
| --- | --- | --- |
| Decide canonical account-binding model | `local_implemented` | Hybrid account binding selected and promoted above. |
| Prevent Account A abilities from authorizing Account B routes | `local_implemented` | `TenantScopedAccessTokenService`, `CheckUserAccess`, and `AccountUser::tokenCan()` reconciled in base commit `cd0da71806f9459ae8daa4b4821d9ec434c643ac`, with fix commit `41286b0296a8ea375081aba1047f8fe6fe84022b` scoping account permission revalidation to account routes; push message `data/actions` routes now use `account` middleware and reject unbound or wrong-account bearer tokens; the token-ceiling/issuer fix adds wildcard-aware ceiling semantics, fail-closed account-scoped issuance, strict ID normalization, and next-request role-downgrade evidence; targeted, focused push, event-boundary, architecture, Pint, diff-hygiene, and full-suite evidence passed. |
| Automate mixed-role multi-account regressions | `local_implemented` | `tests/Feature/Auth/TenantPublicAccountTokenScopeTest.php`, `tests/Unit/Application/Auth/AccountAuthenticationServiceTest.php`, and related account/controller boundary tests passed in worker and principal evidence. |
| Cover real drift fixtures | `local_implemented` | Issuance/stamp path, current-account middleware path, package push `data/actions` routes, and live permission revalidation path are covered by targeted auth/account/push/event boundary tests plus integrated full-suite validation. |
| Promote canonical docs | `local_implemented` | `foundation_documentation/modules/flutter_client_experience_module.md`, bounded package, and worker checkpoint artifact updated. |
| TODO-local audit-floor reviews | `passed_current_baseline` | The fresh `20260507T2102Z` critique/security/verification-debt/test-quality/final-review acceptance rerun is recorded in `foundation_documentation/artifacts/post-release-account-token-binding-audit-floor-acceptance-ledger-20260507T2108Z.md`. RR-AUTH-03 no longer has a TODO-local audit-floor blocker on the current baseline; the downstream triple-audit and Claude experiment records are now captured below. |
| Final Laravel CI-equivalent suite | `passed` | Synthetic single-baseline RR-AUTH-03 validation tree rerun passed with `1368 passed`, `6373 assertions`, duration `1019.08s`, recorded in `foundation_documentation/artifacts/post-release-account-token-binding-single-baseline-rerun-ledger-20260507T2152Z.md`. The earlier integrated dirty-tree run (`1383 passed`, `6554 assertions`, `794.12s`) remains supporting integrated-state evidence only, and the older `20260507T194907Z` external clean-tree rerun remains provenance only because it predates the issuer-boundary correction now required by the bounded package. |
| Triple audit | `accepted_debt_closure` | Round 01 resolved the stale external-baseline objection on the synthetic single baseline, and round 02 recorded accepted non-blocking debt for `PERF-RR-AUTH-03-001`. See `foundation_documentation/artifacts/post-release-account-token-binding-hardening-package-triple-audit-20260507T211322Z/progress.md`, `round-01/resolution.md`, and `round-02/resolution.md`. |
| Claude fourth-auditor comparison | `operational_failure_recorded` | `foundation_documentation/artifacts/claude-cli-reviews/RR-AUTH-03-account-token-binding-claude-comparison-20260507T2202Z.md` records bounded experiment unavailability. The CLI returned `Not logged in · Please run /login`, so no substantive Claude findings were produced. |

## Decision Adherence
- The fix follows the replacement canonical rule by combining token `account_id` binding with request-time current-account permission revalidation.
- The implementation does not broaden Sanctum abilities; copied ability strings remain insufficient without matching current-account context.
- The slice stays inside account-auth/token/middleware surfaces and does not absorb tenant-public OTP, public reset, or unrelated tenant-admin authorization redesign.
- The canonical client-facing impact is documented as an authorization contract, not a Flutter schema or route change.

## Security / Performance / Concurrency / Test-Quality / Verification Debt Notes
- **Security:** the checkpoint closes the known broken-function-authorization path where a multi-account user could reuse Account A abilities against Account B context, including the previously audited push `data/actions` package-route bypass; audit gates remain pending before closure.
- **Performance:** live permission revalidation is bounded to account-scoped authorization checks after the `account` middleware establishes the context flag and uses existing current-account/account-user context; no broad permission scan or cross-account recomputation path is documented for this slice.
- **Concurrency:** token stamping and request-time current-account comparison are deterministic per request; remaining audit should confirm role changes revoke effective access on the next request through live `tokenCan()` revalidation.
- **Test quality:** targeted mixed-role tests cover issuance, mismatch rejection, controller/account route behavior, and event account boundary behavior; the legacy combined batch remains blocked by fixture/harness issues and is not counted as product failure evidence.
- **Verification debt:** `VDA-002` is currently normalized to the deterministic narrower equivalent backed by the clean middleware tranche plus `LAR-ACCOUNT-ROUTE-BINDING`; `VDA-005` is currently normalized to the synthetic single-baseline `1368 passed`, `6373 assertions`, `1019.08s` rerun as the sole closure-grade RR-AUTH-03 full-suite attribution record; and the inline debt scan across the touched Laravel files returned `none`. TODO-local audit-floor artifacts are closed, triple audit is closed with accepted low debt, and the Claude comparison operational-failure record is captured. RR-AUTH-03 can now advance to the next orchestration lane while retaining `PERF-RR-AUTH-03-001` as explicit non-blocking debt.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-security-adversarial`, `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the defect is conceptually narrow but sits on a sensitive authorization boundary with real multi-account blast radius.

## Audit Trigger Matrix
Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/post_release_hardening/TODO-post-release-account-scoped-token-ability-binding.md`
- **Latest TEACH evidence / artifact:** `foundation_documentation/artifacts/audit-floors/post-release-account-token-binding-audit-floor-20260507T2035Z.json`

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Matches the TODO Complexity section. |
| `blast_radius` | `cross-stack` | Laravel auth contract with Flutter consumer and shared documentation impact. |
| `behavioral_change_or_bugfix` | `yes` | Hardens behavior for account-scoped token authorization and fixes permission bleed risk. |
| `changes_public_contract` | `yes` | Clarifies account-bound bearer token authorization semantics visible to API consumers. |
| `touches_auth_or_tenant` | `yes` | Covers Sanctum abilities, account context, tenant/account authorization, and token binding. |
| `touches_runtime_or_infra` | `no` | No queue, worker, runtime topology, deploy, or infrastructure surface is in scope. |
| `touches_tests` | `yes` | Mixed-role multi-account regression and targeted Laravel validation evidence are part of this TODO. |
| `critical_user_journey` | `yes` | Account-scoped authenticated access is launch-critical and business-critical. |
| `release_or_promotion_critical` | `yes` | The TODO remains blocked from completion/promotion until audit-floor gates are resolved. |
| `high_severity_plan_review_issue` | `no` | No current high-severity Plan Review issue is recorded in this TODO. |
| `explicit_three_lane_request` | `yes` | The TODO delivery status explicitly requires the approved triple-audit/Claude comparison record. |

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)

| Field | Value |
| --- | --- |
| `guard_status` | `ready` |
| `guard_outcome` | `go` |
| `guard_evidence` | `foundation_documentation/artifacts/audit-floors/post-release-account-token-binding-audit-floor-20260507T2035Z.json` |
| `critique_required` | `required expanded` |
| `critique_status` | `2102Z_clean_current_baseline` |
| `critique_artifact` | `foundation_documentation/artifacts/post-release-account-token-binding-audit-floor-acceptance-ledger-20260507T2108Z.md` |
| `resolution_status` | `audit_floor_clean_triple_audit_pending` |

- Audit floor decisions recorded from the guard output: critique `required expanded`; security review `required`; verification-debt audit `required`; test-quality audit `required full`; independent final review `required expanded`; triple review `required additive_only`; performance/concurrency `recommended`.
- Do not mark this TODO complete or archive it from the active lane until required audit-floor gates are resolved or explicitly waived by the approval authority.

## Post-Fix Audit Resolution Ledger

- **Resolution ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-audit-resolution-ledger-20260507T1521Z.md`
- **Source audit pass:** 20260507T1424Z TODO-local critique, security, verification-debt, test-quality, and final-review merge artifacts.
- **Resolution status:** code/test blockers are staged in the principal Laravel checkout; audit closure is not claimed until a fresh post-resolution no-context pass is clean or records accepted debt.

| Finding | Severity | Resolution Classification | Evidence |
| --- | --- | --- | --- |
| `CRIT-001` / `FR-001` wildcard token ceiling rejects account-workspace wildcard roles | `high` | `resolved_pending_fresh_audit` | `AccountUser::tokenCan()` uses wildcard-aware ceiling semantics through `tokenAbilityCeilingAllows()`; tests cover resource wildcard and literal wildcard account/event/push authorization. |
| `CRIT-002` implicit arbitrary account binding without selected context | `medium` | `partially_resolved_pending_fresh_audit` | `AccountAuthenticationService` no longer chooses an arbitrary first account; it resolves a no-current-account fallback only when exactly one accessible account exists. |
| `SEC-RR-AUTH-03-001` / `VDA-003` unbound account-scoped bearer token issuance | `medium` | `resolved_pending_fresh_audit` | `TenantScopedAccessTokenService` throws when account-scoped abilities or literal `*` cannot resolve an account context; unit coverage asserts the fail-closed path. |
| `SEC-RR-AUTH-03-002` loose authorization ID comparisons | `low` | `resolved_pending_fresh_audit` | Account membership and permission selection normalize IDs and use strict comparison / `hash_equals`. |
| `VDA-004` / `TQA-RR-AUTH-03-001` next-request role downgrade evidence | `medium/low` | `resolved_pending_fresh_audit` | `TenantPublicAccountTokenScopeTest` now issues a create-capable token, downgrades live role permission, and proves the next account-scoped request is rejected. |
| `VDA-001` unresolved audit gates | `high` | `open` | Fresh post-resolution audit-floor, triple audit, and Claude comparison remain the next gate. |
| `VDA-002` legacy combined account API auth/middleware batch blocked | `high` | `open_verification_debt` | Remains pending repair, narrower equivalent, or approval-authority waiver. |
| `VDA-005` full-suite attribution includes unrelated dirty tree | `medium` | `open_verification_debt` | Existing full-suite evidence validates the integrated local state; clean bounded attribution still needs rerun, explicit scope record, or waiver before final closure. |

## Post-Resolution Audit Blocker Ledger

- **Audit pass:** `20260507T1521Z`
- **Audit artifacts:** critique, security, verification-debt, test-quality, and final-review dispatch/result/merge packets under `foundation_documentation/artifacts/post-release-account-token-binding-*20260507T1521Z.*`
- **Worker assigned:** `Poincare` subagent, code/test correction only.
- **Correction ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-audit-correction-ledger-20260507T1552Z.md`
- **Closure posture:** RR-AUTH-03 remains active; do not proceed to triple audit or Claude comparison until fresh audit-floor rerun accepts the corrections or records accepted debt.

| Finding | Severity | Status | Required Resolution |
| --- | --- | --- | --- |
| `RR-AUTH-03-SEC-001` explicit/current inaccessible `account_id` can mint account-scoped token | `medium` | `corrected_pending_fresh_audit` | `TenantScopedAccessTokenService` validates resolved account id against normalized user access ids for account-scoped abilities before token creation/stamping. |
| `RR-AUTH-03-NC-002` / `VDA-003` no-current-account login semantics under-specified | `medium` | `corrected_pending_fresh_audit` | `AccountAuthenticationService::login` is fail-closed except exact-one accessible account fallback, with direct login-path coverage. |
| `RR-AUTH-03-NC-001` push `data/actions` do not prove ability ceiling/live revalidation | `medium` | `corrected_pending_fresh_audit` | Push `data/actions` routes now require `abilities:push-messages:read`; regression rejects account-bound tokens lacking read ability. |
| `TQA-RR-AUTH-03-POST-001` persisted-token negative ceiling not directly tested | `high` | `corrected_pending_fresh_audit` | Added persisted account-bound token denial coverage where token abilities are lower than live role permissions. |
| `TQA-RR-AUTH-03-POST-002` wildcard issuer fail-close matrix incomplete | `medium` | `corrected_pending_fresh_audit` | Added unresolved account-context fail-close matrix for `account-users:view`, `account-users:*`, `events:*`, `push-messages:*`, and literal `*`. |
| `TQA-RR-AUTH-03-POST-003` stale account-context false-denial coverage not deterministic | `medium` | `corrected_pending_fresh_audit` | Added stale current inaccessible account issuer coverage; the tenant-public ambient false-denial concern remains a fresh-audit question for adequacy. |
| `VDA-004` membership-removal next-request revocation unclassified | `medium` | `corrected_pending_fresh_audit` | Added membership-removal next-request denial coverage. |
| `VDA-002` legacy combined account API auth/middleware batch blocked | `high` | `open_verification_debt` | Repair/run the batch, define a narrower equivalent, or obtain approval-authority waiver before final closure. |
| `VDA-005` full-suite attribution includes unrelated dirty tree | `medium` | `open_verification_debt` | Rerun on clean bounded baseline, record accepted integrated baseline, or obtain approval-authority waiver before final closure. |

## Post-Correction Audit Blocker Ledger

- **Audit pass:** `20260507T1552Z`
- **Audit artifacts:** critique, security, verification-debt, test-quality, and final-review dispatch/result/merge packets under `foundation_documentation/artifacts/post-release-account-token-binding-*20260507T1552Z.*`
- **Critique lane:** clean for this bounded pass.
- **Worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-worker-dispatch-20260507T1606Z.md`
- **Correction ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-second-correction-ledger-20260507T1624Z.md`
- **Closure posture:** RR-AUTH-03 remains active; triple audit and Claude fourth-auditor comparison are blocked until a fresh audit-floor rerun accepts the corrections or records accepted debt.

| Finding | Severity | Status | Required Resolution |
| --- | --- | --- | --- |
| `RR-AUTH-03-SEC-POST-001` public `stampAccountId()` fail-open escape hatch | `medium` | `corrected_pending_fresh_audit` | `stampAccountId()` is now private and fail-closed for blank account context. |
| `FR-RR-AUTH-03-POST-003` issuer fail-close service-local only | `medium` | `corrected_pending_fresh_audit` | Project architecture guardrails now fail production `app` code that directly issues `AccountUser` tokens outside `TenantScopedAccessTokenService`. |
| `TQA-RR-AUTH-03-POST-004` / `VDA-POST-003` stale ambient current-account request-path gap | `medium` | `corrected_pending_fresh_audit` | `PushMessageFlowTest` now exercises a tenant-public request path with an Account A-bound token and stale ambient Account B outside `account` middleware context. |
| `RR-AUTH-03-SEC-POST-002` account-scoped route ability resource catalog drift | `low` | `corrected_pending_fresh_audit` | Architecture guardrails now compare account-prefixed route ability resources against the token-binding catalog. |
| `VDA-002` / `FR-RR-AUTH-03-POST-001` legacy account auth/middleware batch | `high` | `narrower_equivalent_corrected_pending_fresh_audit` | Architecture guardrails now enforce account-prefixed route ability middleware uses `account` middleware on the route or enclosing group. |
| `VDA-005` / `FR-RR-AUTH-03-POST-002` full-suite clean attribution | `medium` | `open_verification_debt` | Rerun on a clean bounded baseline, explicitly accept the integrated dirty-tree baseline, or obtain approval-authority waiver before final closure. |

## Fresh Audit Blocker Ledger

- **Audit pass:** `20260507T1624Z`
- **Audit artifacts:** critique, security, verification-debt, test-quality, and final-review dispatch/result/merge packets under `foundation_documentation/artifacts/post-release-account-token-binding-*20260507T1624Z.*`
- **Test-quality lane:** no material blocker; broad-suite attribution remains residual verification debt.
- **Verification-debt lane:** accepts `VDA-002` as resolved by deterministic narrower equivalent; keeps `VDA-005` open.
- **Runtime invariant worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-worker-dispatch-20260507T1636Z.md`
- **Closure posture:** RR-AUTH-03 remains active; triple audit and Claude fourth-auditor comparison are blocked until a fresh audit-floor rerun accepts the runtime invariant corrections and VDA-005 is resolved or explicitly waived.

| Finding | Severity | Status | Required Resolution |
| --- | --- | --- | --- |
| `RR-AUTH-03-SEC-001` direct `AccountUser::createToken()` runtime boundary | `high` | `worker_required` | Make direct account-scoped `AccountUser::createToken()` fail closed unless called through a validated issuer context; add a negative direct-call regression. |
| `RR-AUTH-03-SEC-002` account route/resource guardrail proof | `high` | `worker_required_or_evidence_required` | Provide deterministic inventory/guard evidence for account-prefixed route middleware and account-scoped ability resource coverage. |
| `RR-AUTH-03-SEC-003` stale ambient `Account::current()` semantics | `medium` | `worker_required_or_evidence_required` | Add explicit sequential stale-context coverage or objective evidence that existing tests close the invariant. |
| `RR-AUTH-03-SEC-004` membership-removal and mixed-role matrix | `medium` | `worker_required_or_evidence_required` | Ensure role downgrade, membership removal, wrong-account same ability, and read/write asymmetry are test-backed and named in closure evidence. |
| `RR-AUTH-03-CRIT-001` / `RR-AUTH-03-FR-001` legacy auth/middleware equivalent acceptance | `medium/high` | `evidence_required` | Record explicit acceptance rationale for the deterministic narrower equivalent or repair and run the legacy batch. |
| `RR-AUTH-03-CRIT-002` / `VDA-005` / `RR-AUTH-03-FR-002` full-suite clean attribution | `medium/high` | `open_verification_debt` | Rerun on a clean bounded RR-AUTH-03 baseline, explicitly accept integrated dirty-tree evidence, or obtain approval-authority waiver. |
| `RR-AUTH-03-FR-003` route-binding/tokenCan confirmations framed as pending questions | `medium` | `evidence_required` | Promote route inventory and tokenCan behavior confirmations into evidenced audit results instead of pending questions. |

## Runtime Invariant Correction Ledger

- **Audit source:** `20260507T1624Z`
- **Worker dispatch:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-worker-dispatch-20260507T1636Z.md`
- **Correction ledger:** `foundation_documentation/artifacts/post-release-account-token-binding-runtime-invariant-ledger-20260507T1655Z.md`
- **Closure posture:** RR-AUTH-03 remains active; triple audit and Claude fourth-auditor comparison are blocked until a fresh audit-floor rerun accepts the runtime invariant correction and `VDA-005` is resolved or explicitly waived.

| Finding | Severity | Status | Required Resolution |
| --- | --- | --- | --- |
| `RR-AUTH-03-SEC-001` direct `AccountUser::createToken()` runtime boundary | `high` | `corrected_pending_fresh_audit` | Direct account-scoped token creation now fails closed unless a validated issuer context is present; `TenantScopedAccessTokenService` is the validated issuer path. |
| `RR-AUTH-03-SEC-002` account route/resource guardrail proof | `high` | `corrected_pending_fresh_audit` | Architecture guardrails pass after runtime invariant correction and continue to enforce production issuer discipline plus account route/resource alignment. |
| `RR-AUTH-03-SEC-003` stale ambient `Account::current()` semantics | `medium` | `corrected_pending_fresh_audit` | Sequential stale-context regressions passed in the focused suite. |
| `RR-AUTH-03-SEC-004` membership-removal and mixed-role matrix | `medium` | `corrected_pending_fresh_audit` | Focused suite covers role downgrade, membership removal, wrong-account same-ability, missing/removed binding, and read/write asymmetry. |
| `RR-AUTH-03-CRIT-001` / `RR-AUTH-03-FR-001` legacy auth/middleware equivalent acceptance | `medium/high` | `evidenced_pending_fresh_audit` | Deterministic narrower equivalent remains the closure candidate: account-prefixed route ability middleware must be paired with `account` middleware on the route or enclosing group. |
| `RR-AUTH-03-CRIT-002` / `VDA-005` / `RR-AUTH-03-FR-002` full-suite clean attribution | `medium/high` | `open_verification_debt` | Rerun on a clean bounded RR-AUTH-03 baseline, explicitly accept integrated dirty-tree evidence, or obtain approval-authority waiver. |
| `RR-AUTH-03-FR-003` route-binding/tokenCan confirmations framed as pending questions | `medium` | `evidenced_pending_fresh_audit` | Route inventory and token ceiling behavior are documented as guardrail/test evidence for fresh audit review. |

## Single-Baseline Rerun Ledger

- **Rerun ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-single-baseline-rerun-ledger-20260507T2152Z.md`
- **Authoritative validation tree:** synthetic `/tmp/rr-auth-03-clean` inside `belluga_now_docker-app-1`
- **Helper overlay source only:** `/home/elton/Dev/repos/belluga-ecosystem/worktrees/rr-auth-03-clean-env-20260507T182803Z/laravel-app`
- **Closure posture:** round-01 triple-audit adjudication proved the earlier external clean tree was stale. The synthetic single-baseline rerun now supersedes it as the closure-grade RR-AUTH-03 attribution record and is the package authority for round 02.

| Finding | Severity | Status | Evidence |
| --- | --- | --- | --- |
| `RR-AUTH-03-CRIT-001` / `RR-AUTH-03-FR-001` legacy auth/middleware equivalent acceptance | `medium/high` | `resolved_current_baseline` | The current deterministic narrower equivalent remains the clean middleware tranche (`56 passed`, `112 assertions`, `14.57s`) plus `LAR-ACCOUNT-ROUTE-BINDING`; round-01 triple-audit adjudication did not reopen this lane. |
| `RR-AUTH-03-CRIT-002` / `VDA-005` / `RR-AUTH-03-FR-002` full-suite clean attribution | `medium/high` | `resolved_single_baseline` | The synthetic single-baseline Laravel CI-equivalent suite rerun passed with `1368 passed`, `6373 assertions`, `1019.08s`, resolving the stale external-tree objection. The earlier dirty-tree suite remains supporting integrated-state evidence only. |
| `RR-AUTH-03-SEC-001` through `RR-AUTH-03-SEC-004` runtime invariant / guardrail / stale-context / mixed-role matrix | `high/medium` | `resolved_single_baseline` | Synthetic single-baseline architecture guardrails passed, and the focused `5 passed`, `11 assertions`, `9.00s` issuer/event rerun plus the full-suite rerun preserved the formerly corrected auth/media and account middleware proofs without reopening product drift. |
| `RR-AUTH-03-FR-003` route-binding/tokenCan confirmations | `medium` | `resolved_single_baseline` | Guardrail evidence plus the synthetic single-baseline rerun preserve account route binding and wildcard-aware token ceiling behavior as the current closure candidate. |

## Fresh Critique / Final Review Correction Ledger

- **Correction ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-critique-final-correction-ledger-20260507T2029Z.md`
- **Audit source:** `20260507T2011Z` critique, security, verification-debt, test-quality, and final-review packet set.
- **Closure posture:** critique/final blockers are corrected locally, but RR-AUTH-03 remains active until a fresh TODO-local audit-floor rerun accepts the `20260507T2029Z` corrected baseline.

| Finding | Severity | Status | Evidence |
| --- | --- | --- | --- |
| `RR-AUTH-03-CRIT-2011-001` / `RR-AUTH-03-FR-001` service-owned issuer boundary | `high/medium` | `corrected_pending_fresh_audit` | `AccountUser::withValidatedAccountScopedTokenIssuerContext()` now rejects callers unless `TenantScopedAccessTokenService` appears in the near caller stack; direct outside invocation regression passed. |
| `RR-AUTH-03-CRIT-2011-002` persisted bearer-token route-shape proof for `account_profile_candidates` | `medium` | `corrected_pending_fresh_audit` | `TenantPublicAccountTokenScopeTest` now proves the persisted same-account allow path and wrong-account reject path for `accounts/{slug}/events/account_profile_candidates`. |
| `20260507T2011Z` security / verification-debt / test-quality lanes | `supporting` | `clean_pending_fresh_audit_rerun` | Security stayed clean, verification debt accepted `VDA-002` and `VDA-005`, and test quality stayed clean on the pre-correction package. The authoritative fresh rerun still needs to be merged against the corrected package. |

## Fresh Audit Normalization Ledger

- **Normalization ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-fresh-audit-normalization-ledger-20260507T2045Z.md`
- **Active audit-floor anchor:** `foundation_documentation/artifacts/audit-floors/post-release-account-token-binding-audit-floor-20260507T2035Z.json`
- **Source audit pass:** fresh `20260507T2035Z` critique, security, verification-debt, test-quality, and final-review packet set.
- **Closure posture:** RR-AUTH-03 remains blocked at the TODO-local audit floor, but the current blocker is normalized packet authority plus current-baseline evidence presentation, not a newly reproduced runtime drift regression. Historical `VDA-002` / `VDA-005` states are provenance only; the current-baseline positions below supersede them for the next rerun.

| Finding | Severity | Current Position | Evidence / Resolution |
| --- | --- | --- | --- |
| `CRIT-RR-AUTH-03-001` / `FR-RR-AUTH-03-001` / `TQA-RR-AUTH-03-20260507-001` pending-question framing | `high` | `normalized_current_baseline_position_recorded` | The bounded package now promotes issuer-boundary, route proof, revocation matrix, deterministic narrower equivalent, and clean-bounded attribution into one explicit current proof snapshot instead of future-question wording. |
| `CRIT-RR-AUTH-03-002` changed-surface / guardrail evidence omission | `medium` | `normalized_current_baseline_position_recorded` | `laravel-app/scripts/architecture_guardrails.php` is now surfaced as an RR-AUTH-03 changed/evidence surface, including `LAR-ACCOUNT-ROUTE-BINDING` as the deterministic route inventory guard. |
| `SEC-RR-AUTH-03-ROUTE-GUARD-INVENTORY` | `high` | `proved_current_baseline` | The authoritative route inventory proof is the passing `LAR-ACCOUNT-ROUTE-BINDING` guardrail plus the clean bounded rerun; do not restate this as an unresolved generic route question on the next packet. |
| `SEC-RR-AUTH-03-ISSUER-BOUNDARY` | `high` | `proved_current_baseline` | `AccountUser::withValidatedAccountScopedTokenIssuerContext()` now rejects callers unless `TenantScopedAccessTokenService` appears in the near caller stack, and the direct outside-caller regression remains part of the focused `4 passed`, `10 assertions` rerun. |
| `SEC-RR-AUTH-03-REVOCATION-MATRIX` | `medium` | `proved_current_baseline` | The current packet explicitly names role downgrade, membership removal, wrong-account, removed binding, stale ambient account, and read/write asymmetry coverage across `TenantPublicAccountTokenScopeTest.php` and `PushMessageFlowTest.php`. |
| `VDA-RR-AUTH-03-002` / `TQA-RR-AUTH-03-20260507-003` deterministic narrower equivalent state drift (`VDA-002`) | `medium` | `accepted_current_baseline_position_pending_reaudit` | The current RR-AUTH-03 position is the clean middleware tranche plus `LAR-ACCOUNT-ROUTE-BINDING` deterministic narrower equivalent. Older “blocked legacy batch” wording is historical only. |
| `VDA-RR-AUTH-03-004` / `TQA-RR-AUTH-03-20260507-002` clean-bounded attribution drift (`VDA-005`) | `medium` | `authoritative_current_baseline_record` | The synthetic single-baseline `1368 passed`, `6373 assertions`, `1019.08s` suite recorded in `foundation_documentation/artifacts/post-release-account-token-binding-single-baseline-rerun-ledger-20260507T2152Z.md` is the sole closure-grade RR-AUTH-03 full-suite attribution record. The dirty-tree `1383 passed`, `6554 assertions`, `794.12s` suite remains supporting integrated-state evidence only, and the older `20260507T194907Z` external clean-tree rerun is provenance only. |
| `VDA-RR-AUTH-03-003` inline debt scan missing from authority surface | `medium` | `resolved_in_current_baseline_packet` | Inline `TODO|FIXME|HACK|TBD|XXX` scan across the RR-AUTH-03 touched Laravel files returned no matches on `2026-05-07`, and the bounded package now records that result explicitly. |
| `TQA-RR-AUTH-03-NORM-001` fail-first provenance gap | `high` | `normalized_to_brownfield_regression_coverage` | The authoritative RR-AUTH-03 packet now records that preserved fail-first/red-run artifacts are unavailable in the current authority surface and therefore claims named brownfield regression coverage instead of preserved TDD provenance. |
| `TQA-RR-AUTH-03-NORM-002` route-specific persisted-token negative proof gap | `medium` | `proved_current_baseline` | `foundation_documentation/artifacts/post-release-account-token-binding-test-quality-followup-ledger-20260507T2059Z.md` records the new persisted-token low-ceiling denial proof for `accounts/{slug}/events/account_profile_candidates`, validated by the focused `5 passed`, `11 assertions`, `19.55s` rerun. |

## Audit-Floor Acceptance Ledger

- **Acceptance ledger artifact:** `foundation_documentation/artifacts/post-release-account-token-binding-audit-floor-acceptance-ledger-20260507T2108Z.md`
- **Source audit pass:** fresh `20260507T2102Z` critique, security, verification-debt, test-quality, and final-review packet set.
- **Closure posture:** RR-AUTH-03 is closure-ready on the current baseline. Triple audit is closed with accepted non-blocking debt (`PERF-RR-AUTH-03-001`), the Claude fourth-auditor experiment is recorded as operationally unavailable because the local CLI session is not logged in, and no substantive blocker remains before advancing the orchestration lane to `RR-AUTH-04`.

| Lane | Outcome | Notes |
| --- | --- | --- |
| Critique | `clean` | No remaining critique blocker on the current normalized packet. |
| Security | `clean_with_low_non_blocking_caveat` | Near-caller-stack issuer-boundary validation is accepted as non-blocking for RR-AUTH-03 and retained only as future hardening guidance. |
| Verification debt | `pass_with_low_residual` | Distinct normalized IDs now separate the VDA-002 and VDA-005 rows; inline debt remains `none`. |
| Test quality | `clean` | Route-specific persisted-token low-ceiling denial proof plus brownfield regression-coverage wording resolve the last evidence-presentation blockers. |
| Final review | `clean` | No substantive runtime/product blocker remains on the bounded package. |

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` account-auth/runtime authorization contract
  - `onboarding_flow_module.md` account token issuance semantics if required
- **Module decision consolidation targets (required):**
  - `flutter_client_experience_module.md`
