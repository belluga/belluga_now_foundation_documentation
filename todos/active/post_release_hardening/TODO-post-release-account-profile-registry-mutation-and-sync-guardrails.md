# TODO (Post Release Hardening): Account Profile Registry Mutation and Sync Guardrails

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The architectural drift review confirmed two destructive registry behaviors in the current account-profile type runtime:

- rename/delete mutation paths can cascade or remove types without dependency-aware protection;
- `tenant:profile-registry:sync-v1` can wipe tenant-dynamic registry state and recreate only V1 defaults.

These behaviors conflict with the registry-driven canonical model and can silently orphan linked/default profile-type semantics or erase in-use tenant configuration.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-03`
- **Why this is the right current slice:** this TODO freezes mutation and sync guardrails without absorbing the broader personal/default semantic resolution owned by the sibling linked-type TODO.

## Contract Boundary
- This TODO owns rename/delete/in-use dependency policy for account-profile types.
- It owns non-destructive sync/repair semantics for `tenant:profile-registry:sync-v1` or its successor path.
- It owns the tests and deterministic guardrails that prevent destructive registry drift from silently recurring.
- It does **not** own the full public/admin profile-type editing UX beyond what is required to express the mutation policy.

## Drift Guardrail Requirement
- This TODO belongs to the registry mutation / destructive-sync drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixture set proving the guardrail against current code.

## Violated Canonical Rule
- Tenant-dynamic profile-type registries must not allow destructive mutation or sync paths that orphan linked/default semantics or erase in-use tenant state without explicit dependency-aware policy.

## Replacement Canonical Rule
- Registry mutation must be dependency-aware and fail closed when linked/default or in-use types would be invalidated.
- Sync/repair paths may repair missing defaults and canonical capability drift, but they must not wipe tenant-custom registry state as their normal operating model.

## Strongest Objective PACED Guardrail
- Laravel feature/unit tests for rename/delete/in-use dependency cases and sync command behavior.
- A deterministic command-path regression test for `tenant:profile-registry:sync-v1` or its replacement.
- Architecture-guard or command-level assertions preventing destructive full-registry wipe as the default sync strategy.

## Real Drift Fixtures
- `laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`
- `laravel-app/app/Application/AccountProfiles/AccountProfileRegistrySeeder.php`
- `laravel-app/routes/console.php`
- Drift-review findings `ARCH-DRIFT-003` and `ARCH-DRIFT-004`

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Laravel`, `Registry`, `Guardrails`
- **Next exact step:** freeze rename/delete/in-use dependency semantics and sync repair behavior before planning any implementation batch.

## Scope
- [ ] Define the dependency-aware mutation policy for linked/default and in-use profile types.
- [ ] Define non-destructive sync/repair behavior for tenant profile-type registries.
- [ ] Harden the current command/service paths so destructive wipe-and-reseed is no longer the default repair model.
- [ ] Add regression tests and deterministic guard coverage using the current destructive code paths as fixtures.

## Out of Scope
- [ ] Broad profile-type UX redesign.
- [ ] Unrelated profile-type capability additions.
- [ ] Public route/copy semantics.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** linked/default dependency counting, in-use delete blocking, sync repair semantics, test coverage, and command guardrails.
- **Must update or split the TODO:** large admin IA refactors or unrelated registry feature expansion.

## Definition of Done
- [ ] Rename/delete rules are explicit for linked/default and in-use profile types.
- [ ] Sync/repair behavior no longer destroys tenant-dynamic registry state by default.
- [ ] Real destructive drift fixtures are represented in tests or deterministic command guards.
- [ ] Canonical module docs state the mutation/sync contract clearly.

## Validation Steps
- [ ] Add fail-first Laravel coverage for destructive rename/delete/sync cases.
- [ ] Run targeted registry service/command tests.
- [ ] Run the final Laravel CI-equivalent suite required by the execution plan.
- [ ] Validate that any replacement sync/repair path preserves tenant custom registry state outside approved repair semantics.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the code surface is concentrated, but the mutation policy touches tenant configuration safety and must not regress registry-driven runtime behavior.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/domain_entities.md`
- **Planned decision promotion targets (module sections):**
  - `account_profile_catalog_module.md` registry mutation/dependency policy
  - `tenant_admin_module.md` admin registry management guardrails
- **Module decision consolidation targets (required):**
  - `account_profile_catalog_module.md`

## Dependencies & Sequencing
- [ ] Align this TODO with `TODO-post-release-account-profile-default-personal-type-resolution.md` before implementation approval so linked/default type semantics and mutation blocks use the same contract.
