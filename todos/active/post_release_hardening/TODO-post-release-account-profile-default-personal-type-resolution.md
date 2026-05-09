# TODO (Post Release Hardening): Account Profile Default / Personal Type Resolution

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The architectural drift review confirmed that production backend bootstrap and social flows still identify the personal/default Account Profile type through the magic string `profile_type = personal`, while the canonical model says profile types are registry-driven and tenant-dynamic.

This drift is not only naming residue. It changes how personal profiles are created, how invite/social flows discover personal identities, and how ownership/privacy invariants are enforced for user-owned personal profiles.

## Framing Source & Story Slice
- **Feature brief:** `foundation_documentation/artifacts/feature-briefs/rule-related-todo-orchestration.md`
- **Primary story ID:** `ST-02`
- **Why this is the right current slice:** this TODO freezes only the semantic contract for the linked personal/default profile type and removes the hardcoded runtime assumption. Registry mutation/sync guardrails remain a sibling TODO so the scope does not collapse into one oversized registry lane.

## Contract Boundary
- This TODO owns the canonical source and resolution contract for the tenant-linked personal/default Account Profile type.
- It owns how bootstrap, invite/social, and related runtime flows resolve that type without hardcoded string checks.
- It owns the ownership/privacy alignment needed for the personal/default type contract.
- It does **not** own registry rename/delete/sync mutation policy in full; that belongs to the sibling registry-guardrails TODO.
- It does **not** own account-claim UX, account workspace rollout, or broad public profile taxonomy redesign.

## Drift Guardrail Requirement
- This TODO belongs to the dynamic-contract / registry drift family.
- Before remediation is approval-clean, execution must freeze:
  - the violated canonical rule,
  - the replacement canonical rule,
  - the strongest objective PACED guardrail available,
  - and the real drift fixture set that proves the guardrail against the current repo state.

## Violated Canonical Rule
- Personal/default Account Profile identity must be resolved through tenant/account-linked registry-aware semantics, not through a hardcoded `profile_type` enum/string in production logic.

## Replacement Canonical Rule
- The personal/default Account Profile type must be resolved through an explicit linked-type contract defined in canonical docs and consumed by runtime services.
- Production code may not infer personal/default semantics from `profile_type == 'personal'` once the linked-type contract exists.

## Strongest Objective PACED Guardrail
- Laravel feature/unit coverage for bootstrap + social resolution paths.
- Regression fixture tests proving no production path requires `profile_type == 'personal'`.
- A deterministic repository scan or architecture-guard check that fails when production code reintroduces direct hardcoded personal-type checks outside approved migration/test surfaces.

## Real Drift Fixtures
- `laravel-app/app/Application/AccountProfiles/AccountProfileBootstrapService.php`
- `laravel-app/app/Application/Social/InviteablePeopleService.php`
- `laravel-app/app/Models/Tenants/AccountProfile.php`
- Drift-review findings `ARCH-DRIFT-001` and `ARCH-DRIFT-002`

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Laravel`, `Docs`, `Registry`, `Identity`
- **Next exact step:** freeze where the linked personal/default type lives, then define fail-first bootstrap/social tests before code changes begin.

## Scope
- [ ] Freeze the canonical home of the linked personal/default Account Profile type.
- [ ] Remove production reliance on `profile_type == 'personal'` for bootstrap/social resolution.
- [ ] Align personal/default bootstrap semantics with canonical `user_owned` and default-privacy expectations.
- [ ] Add regression tests covering bootstrap, social/invite resolution, and legacy tenant migration/backfill expectations.
- [ ] Promote the stable contract into canonical module docs.

## Out of Scope
- [ ] Registry rename/delete/sync mutation rules beyond what is strictly needed to consume the linked-type contract.
- [ ] Account-claim/self-management rollout.
- [ ] Public route/copy changes tied to profile-type terminology.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** linked-type storage decision, bootstrap/social resolution updates, ownership/privacy contract alignment, migration/backfill notes, and guardrail tests.
- **Must update or split the TODO:** broad registry admin UX redesign, account-workspace capability rollout, or unrelated profile-type capability redesign.

## Definition of Done
- [ ] Canonical docs state exactly where the personal/default linked type is stored and how runtime resolves it.
- [ ] Production bootstrap/social logic no longer depends on a hardcoded personal type string.
- [ ] Personal/default bootstrap semantics align with canonical ownership/privacy rules.
- [ ] Real drift fixtures are represented in automated regression coverage or deterministic guard checks.

## Validation Steps
- [ ] Add fail-first Laravel tests for hardcoded-personal drift scenarios.
- [ ] Run targeted Laravel bootstrap/social suites.
- [ ] Run the local Laravel CI-equivalent suite required by the final execution plan.
- [ ] Re-scan production code for direct `profile_type == 'personal'` assumptions outside approved exception surfaces.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | The replacement contract must be test-hardened against the current runtime drift fixtures. | `laravel-app`, `foundation_documentation` | `planned` |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `one checkpoint before approval`
- **Why this level:** the fix is tightly bounded, but it touches identity semantics, runtime bootstrap behavior, and canonical registry rules.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/domain_entities.md`
- **Planned decision promotion targets (module sections):**
  - `account_profile_catalog_module.md` linked/default profile-type contract
  - `domain_entities.md` Account / Account Profile ownership invariants if needed
- **Module decision consolidation targets (required):**
  - `account_profile_catalog_module.md`

## Dependencies & Sequencing
- [ ] Coordinate with `TODO-post-release-account-profile-registry-mutation-and-sync-guardrails.md` so the linked-type contract and mutation policy do not diverge.
- [ ] Reuse this TODO's contract in any later account-claim or social/invite follow-up instead of redefining personal-profile semantics locally.
