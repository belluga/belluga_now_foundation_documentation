# TODO (Fast Follow Regression): Tenant-Admin Account/Profile Lifecycle Integrity

## Title
Fast Follow Regression: Tenant-Admin Account/Profile Lifecycle Integrity

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Tenant-admin account creation was previously centralized through `POST /admin/api/v1/account_onboardings` so account, default Admin role, and the 1:1 Account Profile are created in one transaction. The local Guarapari Admin runtime now shows accounts without active profiles, which violates that completed contract and renders the tenant-admin detail screen's invariant-broken state.

Runtime evidence captured locally on `2026-05-29`:

- Tenant `guarappari`: `57` accounts total, `45` accounts without an active profile.
- `29` missing-profile accounts have `playwright-*` prefixes; additional families include `pw-sr-d-*` and `runtime-invite-account`.
- Example from the reported screen:
  - account `playwright-cover-1779940401325` created at `2026-05-28 03:53:21`;
  - related profile created at `2026-05-28 03:53:22`;
  - related profile soft-deleted at `2026-05-28 03:53:57`;
  - account remains live as `tenant_owned`.

Root-cause evidence:

- Creation still uses `/admin/api/v1/account_onboardings` and the backend transaction boundary.
- Playwright mutation specs create `tenant_owned` accounts through onboarding, then cleanup by calling `DELETE /admin/api/v1/account_profiles/{id}` only.
- `AccountProfileManagementService::delete()` currently soft-deletes the profile without checking whether it is the last active profile for a live account.
- The UI is correctly exposing the invariant breach; it is not the source of the corruption.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `tenant-admin-account-profile-lifecycle-integrity`
- **Why this is the right current slice:** this is one release-confidence regression: direct Account Profile deletion and test cleanup can violate the already-approved onboarding-only invariant.
- **Direct-to-TODO rationale:** the contract, runtime evidence, and affected code paths are concrete. A separate feature brief would add ceremony without reducing ambiguity.

## Contract Boundary
- This TODO restores the existing tenant-admin invariant: a live tenant-admin Account must not be left without an active Account Profile by normal API or validation harness behavior.
- It covers backend lifecycle guardrails, Playwright mutation cleanup, and backend-owned local repair/audit of the already-corrupted local tenant data.
- It must not reintroduce standalone profile creation as a tenant-admin UI remediation path.
- It must not use direct database inserts/updates as the fix or repair path.
- If repair policy expands beyond local/test-seed cleanup and safe restoration of soft-deleted profiles, this TODO must be updated and re-approved.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Fast-Follow`, `Regression-Fix`, `Data-Integrity`, `Cross-Stack`, `Release-Confidence`, `Backend-Guarded`, `Runtime-Repaired-With-Approved-Residuals`, `Playwright-Cleanup-Hardened`
- **Next exact step:** run deterministic delivery guards and decide promotion-lane movement; keep the unrelated `occurrence-fab` Como Chegar route-provider UI failure out of this lifecycle TODO unless the user expands scope.

## Scope
- [x] Add backend regression tests proving direct deletion of the last active Account Profile for a live account is rejected and leaves persisted state unchanged.
- [x] Add backend regression tests proving both `delete()` and `forceDelete()` cannot remove the last active/restorable Account Profile for a live account outside the named aggregate deletion boundary.
- [x] Add backend concurrency regression evidence proving last-profile protection is atomic for concurrent direct deletes targeting the same account aggregate.
- [x] Add backend regression tests proving account aggregate delete remains the valid destructive lifecycle and still deletes/removes related profiles through account ownership rules.
- [x] Add a service-level backend guard so `delete` and `forceDelete` cannot remove the last active/restorable profile from a live account outside the named `AccountAggregateDeletionBoundary`.
- [x] Add backend-owned audit/repair command or service path for accounts without active profiles, with fail-closed local-environment controls, explicit tenant scoping, dry-run default, execute confirmation, bounded/chunked indexed queries, idempotent behavior, and structured residual reporting.
- [x] Add backend tests for every repair policy branch: dry-run/execute parity, safe test-seed aggregate deletion, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion.
- [x] Repair the local `guarappari` corrupted state only through that backend-owned command/service after dry-run evidence is reviewed.
- [x] Replace Playwright mutation cleanup that deletes only `account_profiles/{id}` after onboarding with one canonical Playwright cleanup helper that uses captured onboarding account identifiers and account aggregate cleanup.
- [x] Add/source-scan regression evidence that affected Playwright specs no longer create account-without-profile records as cleanup residue.
- [x] Add Frontend / Consumer Matrix evidence for changed account-profile delete semantics, including admin UI compatibility/error handling and Playwright harness consumer updates.
- [x] Capture before/after local runtime evidence for `guarappari`: missing-profile count must return to zero or every residual row must have an explicit non-test remediation note.
- [x] Update module documentation if the stable lifecycle guard is not already explicit enough in the tenant-admin/account-profile contract.

## Delivery Status Semantics
- `Pending`: contract exists but implementation is not authorized under this expanded TODO yet.
- `Local-Implemented`: backend guard, repair path, and Playwright cleanup are implemented and locally validated.
- `Lane-Promoted`: implementation is merged through the lane threshold.
- `Production-Ready`: required lane targets and runtime confidence gates are complete.

## Blocker Notes
- **Blocker:** no lifecycle implementation blocker remains at local implementation level.
- **Why not blocked now:** backend guard, repair path, Playwright cleanup helper, local repair execution, source scans, and scoped browser shards have been validated.
- **Residual non-scope blocker:** `NAV_WEB_SHARD=occurrence-fab` fails at `navigation.mutation.event_occurrences.spec.js:3410` because the Como Chegar UI lacks a button named `Outros`; this happens after lifecycle-relevant setup/navigation and did not create new missing-profile rows.
- **Owner / source:** lifecycle TODO owner; unrelated Como Chegar UI failure remains outside this TODO unless scope is expanded.
- **Last confirmed truth:** creation transaction still works; direct profile deletion is now guarded; affected Playwright cleanup no longer deletes onboarding-created profiles directly; local corrupted test seeds were repaired or explicitly reported as policy residuals.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:reconcile/v0.2.0-plus8-cross-stack-20260526`, `laravel-app:reconcile/v0.2.0-plus8-cross-stack-20260526`, `flutter-app:reconcile/v0.2.0-plus8-cross-stack-20260526`
- **Promotion lane path:** `feature/reconcile -> dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage` plus approved production promotion follow-through if requested

## Promotion Evidence (Required Before `Lane-Promoted` / `Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Lifecycle guard + repair + Playwright cleanup | local working tree on reconcile branches | `pending` | `pending` | `pending` | `local implemented; promotion pending` |

## Out of Scope
- [ ] New tenant-admin account creation UX.
- [ ] Reintroducing tenant-admin standalone profile-create remediation UI.
- [ ] Direct database writes for repair.
- [ ] Broad account/profile lifecycle redesign unrelated to preventing and repairing missing-profile accounts.
- [ ] Production repair execution unless separately requested and approved with environment-specific evidence.
- [ ] Permanent deletion of non-test business accounts without explicit operator approval.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** local repair policy refinements for the reproduced missing-profile rows; helper extraction for Playwright cleanup; small doc clarification for lifecycle invariants.
- **Must update or split the TODO:** new user-facing repair workflow; schema migration; production data repair; changing account ownership policy; broad profile-type deletion policy redesign.

## Definition of Done
- [x] `DOD-01` Backend rejects direct deletion of the last active profile for a live account with deterministic validation semantics.
- [x] `DOD-02` Backend rejects direct `forceDelete()` of both the last active profile and the last already-soft-deleted/restorable profile for a live account, with response-contract assertions and persisted-state assertions proving no permanent purge occurred.
- [x] `DOD-03` Last-profile protection is atomic under concurrent direct deletes targeting the same live account aggregate.
- [x] `DOD-04` Account aggregate deletion still supports valid account-owned destructive cleanup through a named boundary and does not regress unmanaged account deletion behavior.
- [x] `DOD-05` Playwright mutation tests that create accounts through onboarding cleanup via one canonical account aggregate cleanup helper that uses captured account identifiers.
- [x] `DOD-06` Local `guarappari` missing-profile count is repaired through backend-owned command/service evidence, not direct DB mutation.
- [x] `DOD-07` The repair/audit path reports tenant, account, profile, action, skipped rows, dry-run/executed mode, policy branch, and residual reason clearly enough to be reviewed before and after execution.
- [x] `DOD-08` Consumer-surface evidence explicitly accounts for admin UI delete semantics, Playwright cleanup consumers, and any intentional backend-only/CLI-only waivers.
- [x] `DOD-09` Module docs or TODO evidence explicitly preserve the lifecycle rule that missing-profile tenant-admin state is invalid and backend-owned.

## Validation Steps
- [x] `VAL-01` RED: focused Laravel test fails before guard because `DELETE /admin/api/v1/account_profiles/{id}` currently soft-deletes the last profile.
- [x] `VAL-02` RED/GREEN: focused Laravel test covers direct `forceDelete()` rejection for both last-active-profile removal and already-soft-deleted/restorable-profile purge attempts, asserting response contract and unchanged persisted state for each rejected mutation.
- [x] `VAL-03` GREEN: focused Laravel concurrency test proves simultaneous direct deletes cannot leave a live account with zero active profiles.
- [x] `VAL-04` GREEN: focused Laravel account aggregate delete tests pass after guard and prove only the named boundary can bypass last-profile protection.
- [x] `VAL-05` GREEN: repair command/service tests cover dry-run/execute parity, safe test-seed delete, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion.
- [x] `VAL-06` GREEN: repair command/service dry-run reports current corrupted local `guarappari` rows.
- [x] `VAL-07` GREEN: repair command/service execute repairs local `guarappari`, then dry-run reports only approved residuals.
- [x] `VAL-08` GREEN: Playwright source scan proves affected cleanup helpers no longer delete only profile ids for profiles created by onboarding and use the canonical helper.
- [x] `VAL-09` GREEN: deterministic targeted Playwright mutation shard evidence covers affected cleanup families after implementation; `occurrence-fab` has a non-scope UI failure after lifecycle setup and did not recreate orphan rows.
- [x] `VAL-10` GREEN: post-validation backend invariant query proves no live local `guarappari` account lacks an active profile, except explicitly approved residuals.
- [x] `VAL-11` GREEN: `git diff --check` passes for root and touched subrepos.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Add backend regression tests proving direct deletion of the last active Account Profile for a live account is rejected and leaves persisted state unchanged. | test | `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-local-implementation-evidence-20260529.md`; `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest` | backend | passed | RED returned 200 while the assertion required 422; GREEN asserts response and persisted state. |
| `SCOPE-02` | `Scope` | Add backend regression tests proving both `delete()` and `forceDelete()` cannot remove the last active/restorable Account Profile for a live account outside the named aggregate deletion boundary. | test | `AccountProfileLifecycleIntegrityTest` | backend | passed | Covers soft delete, active force-delete, and trashed/restorable force-delete. |
| `SCOPE-03` | `Scope` | Add backend concurrency regression evidence proving last-profile protection is atomic for concurrent direct deletes targeting the same account aggregate. | test | `AccountProfileLifecycleIntegrityTest::test_concurrent_direct_profile_deletes_cannot_orphan_live_account` | backend | passed | Concurrent process regression passed in focused safe runner. |
| `SCOPE-04` | `Scope` | Add backend regression tests proving account aggregate delete remains the valid destructive lifecycle and still deletes/removes related profiles through account ownership rules. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountControllerTest` | backend | passed | 23 tests, 85 assertions. |
| `SCOPE-05` | `Scope` | Add a service-level backend guard so `delete` and `forceDelete` cannot remove the last active/restorable profile from a live account outside the named `AccountAggregateDeletionBoundary`. | code/test | `AccountProfileManagementService`; `AccountProfileLifecycleIntegrityTest` | backend | passed | Guard is service-level and route-independent. |
| `SCOPE-06` | `Scope` | Add backend-owned audit/repair command or service path for accounts without active profiles, with fail-closed local-environment controls, explicit tenant scoping, dry-run default, execute confirmation, bounded/chunked indexed queries, idempotent behavior, and structured residual reporting. | code/test/runtime | `AccountMissingProfileRepairService`; `accounts:missing-profiles:repair` | backend/local runtime | passed | Local/testing only, tenant-explicit, dry-run-first, confirm-required, chunk clamped, JSON output. |
| `SCOPE-07` | `Scope` | Add backend tests for every repair policy branch: dry-run/execute parity, safe test-seed aggregate deletion, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountMissingProfileRepairCommandTest` | backend | passed | 6 tests, 53 assertions; structurally unreachable multiple-profile branch covered by fail-closed policy shape. |
| `SCOPE-08` | `Scope` | Repair the local `guarappari` corrupted state only through that backend-owned command/service after dry-run evidence is reviewed. | runtime | `accounts:missing-profiles:repair guarappari --execute --confirm=repair-missing-profiles:guarappari --chunk=100` | local runtime | passed | 23 safe test seeds repaired/deleted through service; no direct DB mutation. |
| `SCOPE-09` | `Scope` | Replace Playwright mutation cleanup that deletes only `account_profiles/{id}` after onboarding with one canonical Playwright cleanup helper that uses captured onboarding account identifiers and account aggregate cleanup. | code/source | Playwright helper `tools/flutter/web_app_tests/support/account_onboarding_cleanup.js`; `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-local-implementation-evidence-20260529.md` | web harness | passed | Affected specs cleanup via `cleanupOnboardedAccount(s)` by account slug. |
| `SCOPE-10` | `Scope` | Add/source-scan regression evidence that affected Playwright specs no longer create account-without-profile records as cleanup residue. | command/browser | `rg -n "deleteAccountProfile\\(" tools/flutter/web_app_tests -S`; Playwright shards | web harness | passed | Scan returned no output; post-browser dry-run found no new invalid rows. |
| `SCOPE-11` | `Scope` | Add Frontend / Consumer Matrix evidence for changed account-profile delete semantics, including admin UI compatibility/error handling and Playwright harness consumer updates. | doc/test | Consumer Matrix; `AccountControllerTest`; Playwright shards | cross-stack | passed | UI/harness/CLI consumers classified; helper validated in browser shards. |
| `SCOPE-12` | `Scope` | Capture before/after local runtime evidence for `guarappari`: missing-profile count must return to zero or every residual row must have an explicit non-test remediation note. | runtime | repair dry-run before, execute, and post-browser dry-run | local runtime | passed | 45 invalid before; 22 approved residuals after with explicit residual reasons. |
| `SCOPE-13` | `Scope` | Update module documentation if the stable lifecycle guard is not already explicit enough in the tenant-admin/account-profile contract. | doc/review | This TODO evidence and preserved module baseline | docs | passed | Existing invariant is preserved; TODO evidence records the lifecycle rule explicitly. |
| `DOD-01` | `Definition of Done` | `DOD-01` Backend rejects direct deletion of the last active profile for a live account with deterministic validation semantics. | test/code/browser | `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-local-implementation-evidence-20260529.md`; `AccountProfileLifecycleIntegrityTest`; Playwright post-run invariant dry-run | backend/browser | passed | RED proved prior 200; GREEN returns deterministic validation and unchanged state; Playwright reruns did not recreate orphans. |
| `DOD-02` | `Definition of Done` | `DOD-02` Backend rejects direct `forceDelete()` of both the last active profile and the last already-soft-deleted/restorable profile for a live account, with response-contract assertions and persisted-state assertions proving no permanent purge occurred. | test/code | `AccountProfileLifecycleIntegrityTest` force-delete cases | backend | passed | Covers active last-profile and soft-deleted/restorable purge. |
| `DOD-03` | `Definition of Done` | `DOD-03` Last-profile protection is atomic under concurrent direct deletes targeting the same live account aggregate. | test/code | `AccountProfileLifecycleIntegrityTest` concurrency case | backend | passed | Account-keyed touch occurs inside tenant transaction. |
| `DOD-04` | `Definition of Done` | `DOD-04` Account aggregate deletion still supports valid account-owned destructive cleanup through a named boundary and does not regress unmanaged account deletion behavior. | test/code | `AccountManagementService`; `AccountControllerTest` | backend | passed | Aggregate delete/force-delete continues through account service boundary. |
| `DOD-05` | `Definition of Done` | `DOD-05` Playwright mutation tests that create accounts through onboarding cleanup via one canonical account aggregate cleanup helper that uses captured account identifiers. | source/browser | Playwright helper diff, Playwright source scan, Playwright shards `apd`, `admin-final`, `occurrences`, `invite-session`; evidence ledger | browser harness | passed | Canonical helper deletes account aggregate by slug. |
| `DOD-06` | `Definition of Done` | `DOD-06` Local `guarappari` missing-profile count is repaired through backend-owned command/service evidence, not direct DB mutation. | runtime | repair command dry-run/execute/after | local runtime | passed | 23 safe seeds removed via service; 22 explicit residuals remain. |
| `DOD-07` | `Definition of Done` | `DOD-07` The repair/audit path reports tenant, account, profile, action, skipped rows, dry-run/executed mode, policy branch, and residual reason clearly enough to be reviewed before and after execution. | runtime/test | `AccountMissingProfileRepairCommandTest`; command JSON | backend/local runtime | passed | JSON includes action, branch, linked data, execution mode, and residual reason. |
| `DOD-08` | `Definition of Done` | `DOD-08` Consumer-surface evidence explicitly accounts for admin UI delete semantics, Playwright cleanup consumers, and any intentional backend-only/CLI-only waivers. | doc/test | Consumer Matrix, Playwright browser shards, and backend CLI repair evidence ledger | cross-stack | passed | UI, Playwright harness, and CLI surfaces are classified. |
| `DOD-09` | `Definition of Done` | `DOD-09` Module docs or TODO evidence explicitly preserve the lifecycle rule that missing-profile tenant-admin state is invalid and backend-owned. | doc/review | This TODO decision adherence | docs | passed | Rule is explicit in contract and decision adherence. |
| `VAL-01` | `Validation Steps` | `VAL-01` RED: focused Laravel test fails before guard because `DELETE /admin/api/v1/account_profiles/{id}` currently soft-deletes the last profile. | test/browser | `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-local-implementation-evidence-20260529.md`; Playwright post-run invariant dry-run | backend/browser | passed | RED returned 200 while the assertion required 422; browser reruns later proved no new orphan rows. |
| `VAL-02` | `Validation Steps` | `VAL-02` RED/GREEN: focused Laravel test covers direct `forceDelete()` rejection for both last-active-profile removal and already-soft-deleted/restorable-profile purge attempts, asserting response contract and unchanged persisted state for each rejected mutation. | test/browser | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest`; Playwright post-run invariant dry-run | backend/browser | passed | 4 tests, 18 assertions; browser reruns did not bypass the invariant. |
| `VAL-03` | `Validation Steps` | `VAL-03` GREEN: focused Laravel concurrency test proves simultaneous direct deletes cannot leave a live account with zero active profiles. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest` | backend | passed | Concurrency case folded into lifecycle class. |
| `VAL-04` | `Validation Steps` | `VAL-04` GREEN: focused Laravel account aggregate delete tests pass after guard and prove only the named boundary can bypass last-profile protection. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountControllerTest` | backend | passed | 23 tests, 85 assertions. |
| `VAL-05` | `Validation Steps` | `VAL-05` GREEN: repair command/service tests cover dry-run/execute parity, safe test-seed delete, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion. | test | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountMissingProfileRepairCommandTest` | backend | passed | 6 tests, 53 assertions. |
| `VAL-06` | `Validation Steps` | `VAL-06` GREEN: repair command/service dry-run reports current corrupted local `guarappari` rows. | runtime | `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --chunk=100` | local runtime | passed | Before execute: scanned 57, invalid 45, would-delete 23, residual 22. |
| `VAL-07` | `Validation Steps` | `VAL-07` GREEN: repair command/service execute repairs local `guarappari`, then dry-run reports only approved residuals. | runtime | execute command plus after dry-run | local runtime | passed | After execute/browser: scanned 34, invalid 22, residual 22, would-delete 0. |
| `VAL-08` | `Validation Steps` | `VAL-08` GREEN: Playwright source scan proves affected cleanup helpers no longer delete only profile ids for profiles created by onboarding and use the canonical helper. | command/source | Playwright scan `rg -n "deleteAccountProfile\\(" tools/flutter/web_app_tests -S`; Playwright helper ledger | web harness | passed | No output; helper usage present in affected specs. |
| `VAL-09` | `Validation Steps` | `VAL-09` GREEN: deterministic targeted Playwright mutation shard evidence covers affected cleanup families after implementation; `occurrence-fab` has a non-scope UI failure after lifecycle setup and did not recreate orphan rows. | browser/runtime | Playwright canonical runner shards and post-run repair dry-run in evidence ledger | local/browser | passed | `apd` 3/3, `admin-final` 8/8, `occurrences` 2/2, `invite-session` 3/3; non-scope failure recorded. |
| `VAL-10` | `Validation Steps` | `VAL-10` GREEN: post-validation backend invariant query proves no live local `guarappari` account lacks an active profile, except explicitly approved residuals. | runtime/browser | post-browser `accounts:missing-profiles:repair guarappari --chunk=100`; Playwright shard evidence ledger | local runtime/browser | passed | Still 22 approved residuals; no new orphan rows. |
| `VAL-11` | `Validation Steps` | `VAL-11` GREEN: `git diff --check` passes for root and touched subrepos. | command | `git diff --check`; `git -C laravel-app diff --check` | local | passed | Both exited 0. |

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| Local Docker Laravel runtime | Needed for repair dry-run/execute and runtime proof. | healthy | 2026-05-29 | `verify_context.sh`, Docker-backed tinker/audit queries | Use safe local runtime only. |
| Browser mutation target | Needed for Playwright shard evidence. | healthy | 2026-05-29 | canonical runner with `admin@guarappari.test` local credentials | `occurrence-fab` has unrelated Como Chegar UI assertion failure; lifecycle shards passed and post-run invariant held. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`; `operational-devops` only if runtime topology or promotion evidence changes
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Expanded severity needs explicit test-quality review before delivery. | Laravel tests, Playwright cleanup assertions, CI matrix | planned |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one checkpoint before renewed implementation approval.
- **Why this level:** the root fix is bounded, but it crosses Laravel lifecycle semantics, local runtime repair, and Playwright mutation cleanup. The defect is data-integrity and release-confidence relevant.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets (module sections):**
  - `tenant_admin_module.md` account onboarding and account profile lifecycle API sections.
  - `account_profile_catalog_module.md` account profile lifecycle/projection notes if lifecycle guard is not already durable there.
- **Module decision consolidation targets (required):**
  - Tenant-admin onboarding-only and missing-profile invariant contract.

## Decision Pending (Resolve Before Freeze)
- [x] `D-04` Local repair policy: dry-run first; execute only with explicit local tenant scope and confirmation; delete known test-seed account aggregates only when the account is live `tenant_owned`, tenant matches, slug/prefix matches an allowed harness family (`playwright-*`, `pw-sr-d-*`, `runtime-invite-account`), and linked public/business data checks affirmatively pass; restore exactly one latest soft-deleted profile only for non-test rows when the profile type exists and is not deleted; skip/report missing profile type, multiple soft-deleted profiles, linked-data ambiguity, foreign tenant, deleted account, existing active profile after lock, skipped/unsupported relation checks, or any linked-data check that does not affirmatively pass.
- [x] `D-08` Playwright cleanup depth: fix all current `account_onboardings` cleanup sites in `tools/flutter/web_app_tests`, not only the reported cover test.
- [x] `D-09` Atomicity: last-profile `delete()`/`forceDelete()` guard must run inside a tenant transaction with account-keyed locking or an equivalent conditional mutation so two concurrent direct deletes cannot leave a live account with zero active profiles.
- [x] `D-10` Aggregate boundary: the only bypass for last-profile removal is a named `AccountAggregateDeletionBoundary` owned by account aggregate deletion service flow; direct profile callers cannot opt into it.
- [x] `D-11` Repair command safety: repair command is local-only by default, dry-run by default, tenant-explicit, bounded/chunked, idempotent, structured, and refuses production/stage or missing confirmation unless a separate TODO approves that environment.
- [x] `D-12` Consumer coverage: account-profile delete semantic changes require a Frontend / Consumer Matrix row for admin UI, Playwright harness, and CLI/operator repair surfaces before approval.
- [x] `D-13` Force-delete semantics: forceDelete handling distinguishes active-profile removal, already-soft-deleted profile purge, aggregate account deletion, and repair/restoration workflows; it must not purge the last restorable profile for a live account outside the aggregate boundary.
- [x] `D-14` Canonical harness cleanup: onboarding-created accounts must be cleaned up through one canonical Playwright helper using captured account identifiers or session-owned metadata, never list/page discovery or profile-only deletion.

## Decisions (Resolved Before Freeze)
- [x] `D-01` Direct Account Profile deletion must not leave a live account with zero active profiles.
- [x] `D-02` Account aggregate deletion remains the valid destructive lifecycle for deleting accounts and their profiles together.
- [x] `D-03` Creation transaction is not the suspected failure path; the immediate fix must target destructive profile deletion and cleanup.
- [x] `D-05` Missing-profile tenant-admin UI remains an invariant-breach display, not a normal profile-create remediation branch.
- [x] `D-06` Direct DB repair is forbidden for this incident.
- [x] `D-07` Test harness cleanup that mutates real local tenant data must be subject to product invariants, even when cleanup runs in `finally`.
- [x] `D-15` Round 01 audit findings are additive, not contradictory; all blocking findings harden the TODO contract before renewed approval.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `tenant_admin_module:onboarding` | Manual tenant-admin account/profile create uses `/admin/api/v1/account_onboardings`; legacy standalone creates return `409`. | Preserve | `foundation_documentation/modules/tenant_admin_module.md` account onboarding/API sections |
| `completed/TODO-account-profile-transaction-unified-create` | Account without profile is invariant-broken and backend-repair owned. | Preserve | `foundation_documentation/todos/completed/TODO-account-profile-transaction-unified-create.md` |
| `flutter_client_experience_module` | Tenant-admin UI exposes invariant-broken state for missing profiles. | Preserve | Flutter screen/test evidence |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Direct Account Profile deletion must not leave a live account with zero active profiles.
- [x] `D-02` Account aggregate deletion remains the valid destructive lifecycle for deleting accounts and their profiles together.
- [x] `D-03` Backend guard must apply at service level so all controller paths and harness calls share the invariant.
- [x] `D-04` Approved local repair policy must be executed only through backend-owned command/service, never direct DB mutation.
- [x] `D-05` Affected Playwright mutation cleanup must use invariant-safe aggregate cleanup for profiles/accounts created by onboarding.
- [x] `D-06` Validation must prove both prevention of new corruption and repair of current local corruption.
- [x] `D-07` Last-profile protection must be atomic under concurrent direct deletes.
- [x] `D-08` Account aggregate deletion bypass must be named, internal to the account aggregate service boundary, and regression-tested against direct caller misuse.
- [x] `D-09` Repair command must be fail-closed: explicit tenant, local-only default, dry-run default, execute confirmation, bounded/chunked indexed queries, idempotent output, and structured residual report.
- [x] `D-10` Consumer-surface alignment must be explicit before implementation: admin UI, Playwright harness, and CLI/operator command surfaces are classified and evidenced.
- [x] `D-11` Playwright cleanup must centralize onboarding-created account cleanup through a canonical helper using captured identifiers.

## Questions To Close
- [x] Confirm `D-04` repair policy: delete known test-seed account aggregates only when linked-data checks affirmatively pass; restore a latest soft-deleted profile only for non-test accounts that are otherwise safe; skip/report ambiguous or unsupported rows.
- [x] Confirm this TODO lane: `fast_follow_required` as a release-confidence blocker rather than `post_release_hardening`.
- [x] Authorize the required no-context subagent critique, or explicitly waive it before renewed `APROVADO`.
- [x] Audit-confirm round 01 hardening decisions `D-09` through `D-14` as approval-ready contract additions.
- [x] Audit-confirm round 02 blocker fixes: linked-data checks are fail-closed and forceDelete tests require response-contract plus persisted-state assertions.

## Assumptions Preview (Required Before Plan Review)
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The reported corruption was caused by profile delete after transactional onboarding, not by partial create. | Account/profile timestamps show profile created then soft-deleted; Playwright cleanup calls profile delete. | Need inspect create transaction or media failure path again. | High | Keep as Assumption |
| `A-02` | Blocking deletion of the last active profile for a live account will not break valid account aggregate delete. | Account aggregate delete uses account service relation delete and can carry an explicit bypass/transaction context if needed. | Need route account aggregate delete through a safe service boundary. | Medium | Promote to Decision |
| `A-03` | Test-seed missing-profile accounts may be safely deleted from local runtime when prefix and ownership evidence match known harness families. | Prefix grouping shows `playwright-*`, `pw-sr-d-*`, and `runtime-invite-account`; these are created by web mutation specs. | Must restore/report instead of delete. | Medium | Promote to Decision Pending D-04 |
| `A-04` | A service-level repair command can restore/delete without direct DB mutation. | Laravel commands/services already exist in `routes/console.php`; account/profile services own lifecycle logic. | Need create a one-off command wrapping service behavior. | High | Keep as Assumption |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `laravel-app/app/Application/AccountProfiles/AccountProfileManagementService.php`
- `laravel-app/app/Application/Accounts/AccountManagementService.php`
- `laravel-app/routes/console.php` or a dedicated Laravel application service for audit/repair command
- `laravel-app/tests/Feature/Accounts/AccountProfileLifecycleIntegrityTest.php` or equivalent focused lifecycle test file
- `laravel-app/tests/Feature/Accounts/AccountProfileLifecycleConcurrencyTest.php` or equivalent focused concurrency test file
- `laravel-app/tests/Feature/Accounts/AccountMissingProfileRepairCommandTest.php` or equivalent focused repair command test file
- `laravel-app/tests/Feature/Accounts/AccountOnboardingsControllerTest.php`
- `laravel-app/tests/Feature/Accounts/AccountControllerTest.php`
- `tools/flutter/web_app_tests/support/*` for the canonical onboarding-created account cleanup helper
- `tools/flutter/web_app_tests/account_profile_detail.spec.js`
- `tools/flutter/web_app_tests/account_profile_rich_text.mutation.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.tenant_admin.spec.js`
- `tools/flutter/web_app_tests/navigation.mutation.event_occurrences.spec.js`
- `tools/flutter/web_app_tests/ensure_public_taxonomy_validation_fixture.cjs`
- `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`
- module docs only if current invariant is not explicit enough

### Ordered Steps
1. Reconfirm current local invalid rows with a dry audit query before implementation.
2. Add RED Laravel feature tests for direct `delete()` and `forceDelete()` last-profile rejection with persisted-state assertions.
3. Add RED/GREEN concurrency regression proving simultaneous direct deletes cannot leave a live account with zero active profiles.
4. Add Laravel feature test proving account aggregate delete remains valid only through the named `AccountAggregateDeletionBoundary`.
5. Implement atomic service-level delete/forceDelete guard and the named aggregate-delete boundary inside the account aggregate service transaction.
6. Add audit/repair service/command with fail-closed local-only defaults, explicit tenant, dry-run default, execute confirmation, bounded/chunked indexed queries, idempotent behavior, and structured residual output.
7. Add backend tests for every repair policy branch and post-run invariant assertion.
8. Create or reuse one canonical Playwright cleanup helper for onboarding-created accounts using captured account identifiers/session metadata.
9. Route all affected Playwright onboarding cleanup sites through the canonical helper and remove profile-only cleanup for those created aggregates.
10. Run focused Laravel tests and repair branch tests.
11. Run Playwright cleanup source scan and deterministic targeted mutation shard; if shard cannot execute, keep the TODO blocked with concrete runner evidence.
12. Run repair dry-run, execute local repair, then run after dry-run and post-validation invariant query to prove zero invalid rows or explicit approved residuals.
13. Fill consumer matrix, delivery evidence, test-quality audit, final review, triple review, and closeout disposition.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** this is a reproduced bug with false-green test coverage.
- **Fail-first target(s):**
  - `AccountProfileLifecycleIntegrityTest::test_direct_profile_delete_rejects_last_active_profile_for_live_account`
  - `AccountProfileLifecycleIntegrityTest::test_direct_profile_force_delete_rejects_last_active_profile_for_live_account_with_response_and_state_assertions`
  - `AccountProfileLifecycleIntegrityTest::test_direct_profile_force_delete_rejects_last_soft_deleted_restorable_profile_for_live_account_with_response_and_state_assertions`
  - `AccountProfileLifecycleConcurrencyTest::test_concurrent_direct_profile_deletes_cannot_orphan_live_account`
  - a focused account aggregate delete regression proving the named boundary does not block valid account deletion and is not reusable by direct profile callers
  - `AccountMissingProfileRepairCommandTest` branch tests for dry-run/execute parity, safe test-seed delete, safe restore, ambiguous skip/report, missing profile type skip/report, linked-data skip/report, and post-run invariant assertion

### Frontend / Consumer Matrix (Required Before `APROVADO`)
| Producer / Semantic Change | Consumer Surface | Expected Handling | Evidence Required | Status |
| --- | --- | --- | --- | --- |
| `DELETE /admin/api/v1/account_profiles/{id}` rejects last active/restorable profile for live accounts. | Tenant Admin UI account/profile management | Existing UI must either not expose profile-only deletion for the last profile or must surface the backend rejection without offering standalone profile-create remediation. | Backend response-contract tests and persisted-state assertions. | passed |
| Account aggregate delete remains valid through named boundary. | Tenant Admin account lifecycle and Playwright cleanup harness | Consumers that own account aggregates must call the account aggregate lifecycle, not direct profile deletion. | `AccountControllerTest` plus canonical Playwright helper source scan. | passed |
| Missing-profile repair command. | CLI/operator local repair surface only | No frontend consumer; command is local-only by default, tenant-explicit, dry-run-first, execute-confirmed, and structured. | `AccountMissingProfileRepairCommandTest` plus dry-run/execute/after output; backend-only/CLI-only waiver recorded. | passed |
| Playwright onboarding-created account cleanup. | Web mutation harness | Specs must use canonical helper with captured account identifiers/session metadata; no list/page discovery or profile-only deletion. | Source scan plus shards `apd`, `admin-final`, `occurrences`, and `invite-session`; `occurrence-fab` non-scope UI failure recorded. | passed |

### Flow Evidence Planning Matrix (Required Before `APROVADO`)
| Criterion / Flow | Why Flow-Impacting | Platform Parity (`android-only|web-only|shared-android-web|divergent-android-web|n/a`) | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Direct profile delete guard | Backend mutation can corrupt tenant-admin detail UI. | shared-android-web | backend + Playwright mutation source evidence | yes | yes | Laravel feature tests + source scan | n/a |
| Playwright cleanup correction | Browser mutation harness writes local tenant data. | web-only | Playwright mutation or bounded runner evidence | yes | yes | affected spec runner/source scan | n/a |
| Local repair command | Runtime state affects manual Guarapari Admin validation. | web-only | local runtime command evidence | yes | yes | Docker artisan dry-run/execute/after audit | n/a |
| Account aggregate delete remains valid | Backend mutation path used for cleanup and admin lifecycle. | shared-android-web | backend tests | yes | no | Laravel feature tests | Browser lane not required unless UI delete flow changed; no UI delete flow is changed. |
| Last-profile concurrent delete guard | Concurrent backend mutation can bypass naive check-then-delete. | shared-android-web | backend concurrency tests | yes | no | Laravel concurrency regression + transaction/locking evidence | Browser lane not required for backend race. |
| Consumer delete semantics | Backend `DELETE account_profiles` semantic changes can affect admin UI/harness expectations. | web-only | backend + Flutter/source review | yes | yes | Consumer Matrix evidence + targeted UI/harness proof | n/a |

### Local CI-Equivalent Suite Matrix (Required Before `APROVADO` and Before Delivery Claim)
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / focused lifecycle integrity tests` | Backend delete/forceDelete guard. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest` | Local-Implemented | passed | 4 tests, 18 assertions | Response contract and unchanged persisted state asserted. |
| `laravel-app / focused concurrency tests` | Atomicity of last-profile guard. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountProfileLifecycleIntegrityTest` | Local-Implemented | passed | 4 tests, 18 assertions | Concurrency test is folded into the lifecycle class and passed. |
| `laravel-app / focused account aggregate delete tests` | Named aggregate deletion boundary. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountControllerTest` | Local-Implemented | passed | 23 tests, 85 assertions | Aggregate delete remains valid for unmanaged accounts through account service boundary. |
| `laravel-app / focused repair command tests` | Repair policy branches and dry-run/execute parity. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountMissingProfileRepairCommandTest` | Local-Implemented | passed | 6 tests, 53 assertions | Covers restore, safe test-seed delete, linked-data skip, missing type skip, no-restorable skip, and confirmation guard. |
| `tools/flutter / Playwright mutation cleanup source scan` | Web mutation specs caused runtime corruption. | `rg -n "deleteAccountProfile\\(" tools/flutter/web_app_tests -S`; `rg -n "account_onboardings" tools/flutter/web_app_tests -S` | Local-Implemented | passed | no `deleteAccountProfile(` output; onboarding call sites reviewed | Avoids markdown pipe in command cell; helper usage is visible in affected specs. |
| `tools/flutter / targeted Playwright mutation shards` | User-facing browser mutation harness must not corrupt local runtime. | `NAV_WEB_SHARD=apd/admin-final/occurrences/invite-session/occurrence-fab bash tools/flutter/run_web_navigation_smoke.sh mutation` | Local-Implemented | passed | `apd` 3/3; `admin-final` 8/8; `occurrences` 2/2; `invite-session` 3/3; `occurrence-fab` selected 1 and failed non-scope at Como Chegar `Outros` assertion | Post-run repair dry-run stayed at 22 approved residuals; no new lifecycle orphan. |
| `local runtime / post-validation invariant query` | Proves validation did not recreate orphan accounts. | `docker compose exec -T app php artisan accounts:missing-profiles:repair guarappari --chunk=100` | Local-Implemented | passed | after Playwright: scanned 34, invalid 22, residual 22, would-delete 0 | Residuals are explicit policy skips: linked-data-present rows plus one no-restorable runtime invite account. |
| `root/subrepos / diff hygiene` | Multiple repos touched. | `git diff --check`; `git -C laravel-app diff --check` | Local-Implemented | passed | both commands exited 0 | `flutter-app` dirty state is unrelated and was not touched for this TODO. |

### Runtime / Rollout Notes
- Local repair must run only against local Docker runtime after dry-run evidence.
- Production or stage repair is not authorized by this TODO without environment-specific approval.
- If the backend guard blocks cleanup of existing Playwright data before repair, repair command must support safe aggregate deletion of known test-seed accounts.

## Plan Review Gate (Review of the Execution Plan; required for `medium|big`; abbreviated for low-risk `small`)
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-01`
  - **Severity:** high
  - **Evidence:** `AccountProfileManagementService::delete()` deletes without last-profile invariant check; Playwright cleanup deletes profile ids after onboarding.
  - **Why it matters now:** a normal API call can corrupt tenant-admin account state and invalidate manual QA/runtime validation.
  - **Option A (Recommended):** enforce invariant at backend service layer and make account aggregate delete the only valid aggregate removal path.
    - **Effort:** medium
    - **Risk:** low
    - **Blast radius:** cross-stack
    - **Maintenance burden:** low
    - **Performance impact:** neutral
    - **Elegance impact:** improves
    - **Structural soundness impact:** improves
  - **Option B (Alternative):** only fix Playwright cleanup and leave backend permissive.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** local
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Option C (Do Nothing):** keep UI invariant breach and rely on manual cleanup.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** cross-stack
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Recommendation:** Option A, because the backend must protect product invariants regardless of test harness behavior.

- **Issue ID:** `DATA-01`
  - **Severity:** high
  - **Evidence:** local `guarappari` has `45` live accounts without active profiles.
  - **Why it matters now:** manual Guarapari Admin validation sees invalid rows and can mask or contaminate validation of unrelated deliveries.
  - **Option A (Recommended):** add backend-owned dry-run/execute repair command with test-seed aggregate deletion and safe soft-deleted-profile restoration policy.
    - **Effort:** medium
    - **Risk:** medium
    - **Blast radius:** local runtime
    - **Maintenance burden:** low
    - **Performance impact:** neutral
    - **Elegance impact:** improves
    - **Structural soundness impact:** improves
  - **Option B (Alternative):** run ad hoc tinker/manual database updates.
    - **Effort:** low
    - **Risk:** high
    - **Blast radius:** runtime data
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Option C (Do Nothing):** leave local rows corrupted.
    - **Effort:** none
    - **Risk:** high
    - **Blast radius:** manual validation
    - **Maintenance burden:** high
    - **Performance impact:** neutral
    - **Elegance impact:** regresses
    - **Structural soundness impact:** regresses
  - **Recommendation:** Option A.

### Failure Modes & Edge Cases
- [ ] Account has multiple active profiles: direct delete may be allowed only if at least one active profile remains.
- [ ] Account itself is being deleted: aggregate lifecycle must not be blocked by the direct-profile guard.
- [ ] Profile is already soft-deleted: force-delete policy must not make the account impossible to repair.
- [ ] Profile type used by a soft-deleted profile was also deleted by test cleanup: repair command must skip/report if restore would leave invalid profile type.
- [ ] Test-seed account has public data or linked events: aggregate deletion must either cascade safely or skip/report.
- [ ] Non-test missing-profile account has multiple soft-deleted profiles: repair command must skip/report instead of guessing.

### Residual Unknowns / Risks
- [ ] Exact repair policy for `runtime-invite-account` and `pw-sr-d-*` should be confirmed before execute mode.
- [ ] Targeted Playwright mutation shard may require fresh web bundle/runtime preflight; blocked evidence must be classified honestly.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)
- **Needed:** `no`
- **Why ambiguity remains:** `n/a`; recommended path follows the original aggregate invariant and avoids weaker harness-only fixes.
- **Opinion count:** `0`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `yes via Independent No-Context Critique Gate`; audit escalation raised the approval floor.
- **Required lenses:** `correctness|performance|elegance|structural-soundness|operational-fit`

| Reviewer | Recommendation | Performance view | Elegance view | Structural soundness view | Resolution | Evidence |
| --- | --- | --- | --- | --- | --- | --- |
| `self-review` | Backend service guard + backend-owned repair + Playwright cleanup correction. | Neutral; bounded checks on mutation paths only. | Improves by restoring aggregate ownership. | Improves by preventing harness and API bypass of invariant. | Integrated | This TODO plan review. |
| `Claude CLI pre-approval review` | `ready_for_aprovado`; no blocking findings. | No remaining performance blocker; bounded/chunked/indexed/local-only repair contract is adequate. | No remaining elegance blocker; canonical helper and aggregate boundary are explicit. | No remaining structural blocker; audit-loop clean outcome is defensible. | Integrated as external review evidence; non-blocking checkbox hygiene deferred to approval recording. | `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-preapproval-claude-review-20260529.md` |

## Audit Trigger Matrix
- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` on 2026-05-29 -> `Overall outcome: go`, fingerprint `c780145c63ac`.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Cross-stack lifecycle regression. |
| `blast_radius` | `cross-stack` | Laravel lifecycle, local runtime repair, Playwright harness. |
| `behavioral_change_or_bugfix` | `yes` | Regression fix. |
| `changes_public_contract` | `no` | Restores existing invariant; validation semantics may be clarified. |
| `touches_auth_or_tenant` | `yes` | Tenant-admin account/profile mutation paths. |
| `touches_runtime_or_infra` | `yes` | Local runtime repair command execution. |
| `touches_tests` | `yes` | Laravel and Playwright tests/harness. |
| `critical_user_journey` | `yes` | Tenant-admin account lifecycle and validation. |
| `release_or_promotion_critical` | `yes` | Blocks trust in mutation validation matrix. |
| `high_severity_plan_review_issue` | `yes` | `ARCH-01`, `DATA-01`. |
| `explicit_three_lane_request` | `no` | Not requested. |

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)
- **Critique decision:** `required`
- **Why this decision:** audit escalation guard fingerprint `c780145c63ac` requires expanded critique before `APROVADO` because the TODO is medium, cross-stack, release-critical, tenant/runtime-sensitive, and has high-severity issue cards.
- **Impact signals in scope:** `cross-module blast radius|auth/tenant mutation|runtime repair|high-severity issue card`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline|approved scope boundary|assumptions preview|execution plan summary|issue cards|residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer if guard requires`
- **Subagent mandate (when available):** `required; subagent tooling is available, but spawn policy requires an explicit user request before use`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/session.json`
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|risk`
- **Critique status:** `clean_after_round_03_adjudication`
- **Findings summary:** round 1 and round 2 blockers were integrated into the contract; round 3 returned zero findings, with only non-material `recommended_path` wording differences adjudicated as clean. Claude CLI follow-up returned `ready_for_aprovado` with no blocking findings and only non-blocking approval-recording hygiene notes.
- **Evidence / reference:** `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-merge-20260529.md`; `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-merge-20260529-round02.md`; `foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-merge-20260529-round03.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/resolution.md`; `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-preapproval-claude-prompt-20260529.md`; `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-preapproval-claude-review-20260529.md`
- **Waiver authority / reference (required if waived):** `n/a`

## Approval
- **Approved by:** user in chat on `2026-05-29T16:29:40-03:00` with phrase `APROVADO`.
- **Approval scope:** implement the audited TODO contract only: Laravel account/profile lifecycle tests and atomic delete/forceDelete guard; named account aggregate deletion boundary; local-only fail-closed missing-profile audit/repair command/service; local `guarappari` repair through that backend path after dry-run evidence; canonical Playwright cleanup helper and affected onboarding-created account cleanup call sites; source-scan/runtime validation evidence; module documentation only if current invariant wording is insufficient.
- **Execution not authorized by approval:** `Direct DB repair; production/stage repair; new UI repair flow; unrelated lifecycle redesign.`
- **Renewed approval required when:** repair policy changes, production/stage repair is requested, schema/route contract changes, or validation obligations are waived.

## Rules Acknowledgement / Ingestion (Required After `APROVADO` and Before Execution)
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `/home/elton/Dev/repos/delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | Reproduced runtime bug with false-green coverage. | Reproduction, failing test, root cause, after evidence. | Closing on aggregate suite claims. | Test-first backend fix and explicit evidence. |
| `/home/elton/Dev/repos/delphi-ai/skills/backend-concurrency-idempotency-validation/SKILL.md` | Destructive tenant mutation can corrupt persisted state. | Domain invariant and mutation policy. | Smoke-only mutation proof. | Validate deletion policy and residual concurrency risk. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | Laravel and web harness tests need correction. | Behavior-specific assertions and real backend evidence where needed. | Status-only tests or harness bypasses. | Focused tests plus runtime matrix. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md` | Laravel implementation requires TODO authority and approval. | APROVADO, rule ingestion, guards. | Further code changes before renewed approval. | Run authority guard after approval. |
| `delphi-ai/workflows/docker/todo-execution-boundary-method.md` | Implementation must stay inside approved boundary. | No hidden scope expansion. | Production repair or schema redesign without approval. | Stop on approval-material drift. |

## Decision Adherence Validation (Mandatory Before Delivery)
| Decision ID | Status (`Adherent`/`Exception`) | Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `AccountProfileLifecycleIntegrityTest`; `AccountProfileManagementService` guard | Direct profile delete/force-delete can no longer leave live account without active/restorable profile. |
| `D-02` | `Adherent` | `AccountManagementService` named aggregate boundary; `AccountControllerTest` | Account aggregate deletion remains the valid destructive lifecycle. |
| `D-03` | `Adherent` | Service-level guard in `AccountProfileManagementService` | Controller and harness callers share the same invariant. |
| `D-04` | `Adherent` | `accounts:missing-profiles:repair` dry-run/execute/after evidence | Repair executed through backend command/service only; no direct DB repair. |
| `D-05` | `Adherent` | `account_onboarding_cleanup.js` and affected Playwright specs | Onboarding-created accounts cleanup by account slug and aggregate delete. |
| `D-06` | `Adherent` | Laravel tests, source scan, Playwright shards, post-run repair dry-run | Prevention and current local repair both evidenced; 22 residuals are explicit policy skips. |

## Module Decision Consistency Validation (1-1 Mandatory Before Delivery)
| Module Decision Ref | Planned Handling | Delivery Status (`Preserved|Superseded (Approved)|Regression`) | Evidence | Notes |
| --- | --- | --- | --- | --- |
| `tenant_admin_module:onboarding` | Preserve | Preserved | `AccountOnboardingsControllerTest` 8 tests, 43 assertions | Creation transaction remains canonical; fix targets destructive lifecycle. |
| `TODO-account-profile-transaction-unified-create` | Preserve | Preserved | Lifecycle guard, repair command, and this TODO evidence | Missing-profile state remains invalid and backend-owned. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `implemented diff + validation evidence` | lifecycle guard bypasses, missing repair safety, harness cleanup false-green | passed | `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-implementation-claude-review-20260529.md` | Claude CLI returned `ready_for_delivery`; no backend correctness blockers. | Non-scope `occurrence-fab` Como Chegar UI failure captured separately; lifecycle evidence accepted. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `onboarding-only tenant-admin invariant` | Any path that deletes/creates profile independently and leaves live account invalid. | passed | `rg -n "deleteAccountProfile\\(" tools/flutter/web_app_tests -S`; lifecycle service guard review | No remaining `deleteAccountProfile(` helper/call; backend service rejects direct last-profile delete/force-delete. | Canonical aggregate cleanup helper is the harness path. |
| `test-creation-standard` | Tests passing while cleanup corrupts state or only asserting HTTP status. | passed | `AccountProfileLifecycleIntegrityTest`; `AccountMissingProfileRepairCommandTest`; `test_quality_audit.sh` | No hard bypass markers; backend tests assert response contracts and persisted state. | Static audit flags existing Playwright status-only seed assertions as residual harness risk, not a lifecycle blocker. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Disposition reason:** local implementation and validation are complete; promotion/closeout guard run remains pending in the current working tree.
- **Post-commit/push status:** `pending`
- **Next path/status action:** run completion/closeout guards, then decide whether to promote through `dev` or leave active until user requests promotion.

## Security Risk Assessment (Mandatory Before Delivery)
- **Risk level:** `medium`
- **Why this risk level:** touches authenticated tenant-admin destructive mutation and repair command.
- **Attack surface in scope:** tenant-admin auth/abilities, tenant isolation, destructive account/profile lifecycle.
- **Attack simulation decision:** `bounded-review-complete`
- **Review evidence:** service-level guard tests, local-only command guard, explicit tenant scoping, execute confirmation, linked-data skip policy, Claude CLI implementation review.
- **Residual security risk:** low for this lifecycle slice; production/stage repair remains unauthorized without a separate TODO.

## Performance & Concurrency Risk Assessment (Mandatory Before Delivery)
- **Policy schema version:** `pcv-1`
- **Global sensitivity level:** `medium`
- **Why this level:** destructive mutation and repair command need domain invariant and race policy.
- **Current delivery stage at review time:** `Local-Implemented`

| Lane ID | Lane | Trigger Result | Trigger Severity | Trigger Reason Code | Gate Deadline | Minimum Evidence Rule | State | Residual Risk | Uncertainty Reason Code |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `endpoint-performance-scrutiny` | `not_needed` | `low` | `EPS-DATA-PATH-CHANGED` | `before_local_implemented` | `EPS-E1` | `not_applicable` | `none` | `none` |
| `FRC` | `frontend-race-condition-validation` | `recommended` | `medium` | `FRC-DUPLICATE-MUTATION` | `before_local_implemented` | `FRC-POLICY` | `passed` | `low` | `occurrence-fab` non-scope UI assertion failure remains outside lifecycle cleanup. |
| `BCI` | `backend-concurrency-idempotency-validation` | `required` | `high` | `BCI-NON-IDEMPOTENT-WRITE` | `before_local_implemented` | `BCI-INV` | `passed` | `low` | Concurrent direct delete regression passed inside `AccountProfileLifecycleIntegrityTest`. |
| `RLS` | `runtime-load-stress-validation` | `not_needed` | `low` | `RLS-BATCH-OR-BULK-PATH-CHANGED` | `before_local_implemented` | `RLS-E1` | `not_applicable` | `none` | `none` |

## Verification Debt Assessment (Required Before `Completed`; mandatory audit for `medium|big` or when debt signals exist)
- **Audit outcome:** `accepted_residuals`
- **Why this outcome:** local runtime still has 22 missing-profile rows, all explicitly skipped by the approved repair policy because linked data is present or no restorable profile exists.
- **Inline code TODO debt:** none introduced for this lifecycle slice.
- **Evidence / audit artifact:** post-run `accounts:missing-profiles:repair guarappari --chunk=100`; Claude CLI implementation review.
- **Accepted residual debt:** 22 Guarapari rows remain as policy residuals; unrelated Como Chegar `Outros` UI failure remains outside this TODO.

## Independent Test Quality Audit Gate (Deterministic Floor From Audit Escalation)
- **Audit decision:** `required`
- **Why this decision:** audit escalation guard requires full test-quality audit because tests are touched, this is a behavior bugfix, critical journey, and release-confidence scope.
- **Trigger signals in scope:** `changed test logic|bugfix/regression|behavior-defining change|critical-user-journey|non-trivial validation risk`
- **Required evidence matrix (when architectural):** `Laravel feature|Playwright real-backend mutation|runtime repair evidence`
- **Package mode:** `bounded-summary`
- **Audit status:** `clean_after_round_03_adjudication`
- **Findings summary:** round 01 required forceDelete and repair branch coverage; round 02 required stronger forceDelete assertion specificity; round 03 returned zero test-quality findings after those requirements were added.
- **Evidence / reference:** `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/merge/test-quality.merge.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-02/merge/test-quality.merge.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/merge/test-quality.merge.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/resolution.md`
- **Waiver authority / reference (required if waived):** `n/a`

## Independent No-Context Final Review Gate (Deterministic Floor From Audit Escalation)
- **Final review decision:** `required`
- **Why this decision:** audit escalation guard requires expanded final review before completion for medium cross-stack data-integrity regression.
- **Impact signals in scope:** `cross-module blast radius|auth/tenant mutation|runtime repair|high-severity issue card`
- **Package mode:** `bounded-summary`
- **Review isolation mode:** `fresh no-context auxiliary reviewer if required`
- **Final review status:** `claude_cli_ready_for_delivery`
- **Findings summary:** Claude CLI found no lifecycle blockers after updated browser evidence. It classified the `occurrence-fab` `Outros` failure as unrelated route-provider UI debt because lifecycle-relevant setup passed and post-run repair dry-run showed no new orphan accounts.
- **Evidence / reference:** `foundation_documentation/artifacts/claude-cli-reviews/tenant-admin-account-profile-lifecycle-implementation-claude-review-20260529.md`
- **Waiver authority / reference (required if waived):** `n/a`

## Canonical Triple Review Gate
- **Triple review decision:** `required`
- **Why this decision:** audit escalation guard requires additive triple review before completion because the TODO is release-critical, cross-stack, tenant/runtime-sensitive, and has high-severity issue cards.
- **Canonical protocol:** `audit-protocol-triple-review`
- **Status:** `clean_after_round_03_adjudication`
- **Evidence:** `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/session.json`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/progress.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-01/resolution.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-02/resolution.md`; `foundation_documentation/artifacts/triple-audits/tenant-admin-account-profile-lifecycle-20260529/round-03/resolution.md`

## Delivery Confidence Gate (Required for `Production-Ready`)
- [ ] **Lane promotion evidence complete:** pending.
- [ ] **Runtime impact classified:** medium.
- [ ] **Every `pcv-1` lane with `Gate Deadline = before_production_ready` is gate-satisfying:** pending.
- [ ] **Operational checks run (if runtime-impacting):** pending.
- [ ] **Lane artifacts recorded and hashed:** pending.
- [ ] **Confidence stated:** pending.
- [ ] **Release readiness outcome:** pending.

## Module Consolidation Gate (Required Before `Completed`)
- [ ] Canonical module docs updated if stable lifecycle invariant is missing or ambiguous.
- [ ] Decision promotion ledger or trace added if module docs change.
- [ ] TODO/module cross-links updated before closeout.

## Commands (Run Locally)
- `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- `python3 delphi-ai/tools/subagent_review_dispatch.py --review-kind critique --package foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-package-20260529.md --todo-path foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md --goal "Pre-approval critique for tenant-admin account/profile lifecycle integrity TODO" --markdown-output foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-dispatch-20260529.md --json-output foundation_documentation/artifacts/tenant-admin-account-profile-lifecycle-critique-dispatch-20260529.json`
- `python3 delphi-ai/tools/todo_authority_guard.py foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-tenant-admin-account-profile-lifecycle-integrity.md`
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountOnboardingsControllerTest`
- `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter AccountControllerTest`
- `rg -n "account_onboardings|deleteAccountProfile\\(" tools/flutter/web_app_tests -S`
- `git diff --check`
