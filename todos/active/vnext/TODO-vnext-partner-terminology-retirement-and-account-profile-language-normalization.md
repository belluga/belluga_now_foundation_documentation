# TODO (VNext): Partner Terminology Retirement and Account Profile Language Normalization

**Status:** Active  
**Stage:** `Backlog`  
**Owners:** Delphi, Foundation Documentation Team, Flutter Team, Laravel Team  
**Date:** 2026-04-18  
**Objective:** Retire `partner` as canonical internal project terminology and normalize foundation docs, module names, contracts, and implementation language around `Account`, `Account Profile`, and registry-driven `Profile Type`.

**Feature framing support:** `foundation_documentation/artifacts/feature-briefs/account-profile-module-family-reconciliation.md`

**Topology decision status (2026-04-18):** the first module-family topology decision is frozen in `foundation_documentation/todos/active/vnext/TODO-vnext-account-profile-module-family-topology-decision.md`: keep one real public account-profile catalog module with deferred capability-first `offer` planning, treat `partner_admin_module.md` as the legacy-named planning surface for future `account_workspace`, and treat `partner_analytics_module.md` as a capability-planning surface rather than a default future standalone module.

## Why

- The current canonical business model no longer recognizes `partner` as a valid current domain noun.
- `partner` is legacy residue from the period before `Account` and `Account Profile` took their current form.
- Keeping `partner` in canonical docs now creates semantic drift:
  - it implies a separate entity family that should no longer exist,
  - it obscures the real current model (`Account` + `Account Profile` + `Profile Type`),
  - it risks leaking legacy vocabulary back into new TODOs, modules, and code.
- This cleanup is important, but it is not a store-release blocker; it belongs in the post-store-release VNext normalization window.

## Current Audit Snapshot

The following surfaces still carry legacy `partner` terminology and should be reconciled:

- Top-level authority/docs:
  - `project_constitution.md`
  - `project_mandate.md`
  - `submodule_flutter-app_summary.md`
  - `endpoints_mvp_contracts.md`
  - `policies/web_to_app_promotion_policy.md`
- Module docs:
  - `modules/partner_catalog_and_offer_module.md`
  - `modules/partner_admin_module.md`
  - `modules/partner_analytics_module.md`
  - `modules/flutter_client_experience_module.md`
  - `modules/system_architecture_principles.md`
  - `modules/task_and_reminder_module.md`
- Supporting legacy/planning surfaces:
  - `mock_roadmap.md`

## Canonical Direction

- Internal canonical language must use:
  - `Account`
  - `Account Profile`
  - `Profile Type`
  - `Static Asset`
  - `Event`
- `partner` must not remain as:
  - a root entity,
  - a preferred canonical internal label,
  - a module name that implies a separate business aggregate family.
- When a surface still needs to describe business-facing operator profiles, it should do so through `Account Profile` and the relevant `Profile Type` rather than through `partner`.

## Main Workstreams

### 1. Canonical Documentation Cleanup

- Remove `partner` as canonical internal terminology from top-level docs and module docs.
- Replace references with the precise current noun:
  - `Account`
  - `Account Profile`
  - `Profile Type`
  - `Account Workspace`
  - `Account Profile Analytics`
- Reconcile system-wide wording so future docs stop reintroducing legacy language.

### 2. Module Surface Normalization

- Decide and execute the future canonical names/boundaries for:
  - `partner_catalog_and_offer_module.md`
  - `partner_admin_module.md`
  - `partner_analytics_module.md`
- Determine whether those files should be:
  - renamed,
  - split,
  - absorbed into canonical successor authorities or capability homes such as `account_workspace`.
- Ensure `project_constitution.md` module map and dependency references stay aligned after the rename/boundary cleanup.

### 3. Contract and Implementation Vocabulary Alignment

- Audit Flutter/Laravel code and generated/shared docs for internal `partner*` naming that should now align to Account Profile language.
- Distinguish:
  - legacy internal terminology that must be retired,
  - public-facing copy/route aliases that may still exist temporarily for product continuity.
- Prevent new code/docs from using `partner` as if it were a canonical entity family.

### 4. Public Route / Product Copy Decision

- Evaluate whether public-facing route/copy such as `/parceiro/:slug` is:
  - a temporary external/product alias,
  - a long-lived product-language choice,
  - or also due for later retirement.
- Evaluate canonical public path permanence rules before coupling URLs to `profile_type` or any future type-derived segment.
  - A type-derived dynamic path may improve semantics/discoverability, but it also risks breaking permanence when an Account Profile changes type.
  - Alternatives to evaluate include:
    - keeping one permanent type-agnostic canonical path,
    - supporting type-shaped aliases that resolve to a type-agnostic canonical path,
    - or another strategy that preserves stable external links even when profile classification changes.
- Coordinate this with `TODO-vnext-route-paths-refactor.md` rather than changing public URLs/copy implicitly inside a terminology cleanup.

## Required Decisions

- Freeze and later execute the `ST-01` topology decision already recorded in `TODO-vnext-account-profile-module-family-topology-decision.md`:
  - keep one real public account-profile catalog module (`partner_catalog_and_offer_module.md` future rename pending),
  - treat deferred `offer`/commercial as capability-first until implementation proves whether module promotion is warranted,
  - rename `partner_admin_module.md` into future `account_workspace`,
  - treat `partner_analytics_module.md` as a capability-planning surface rather than a default future standalone module.
- Which existing `partner` usages are acceptable only as temporary public/product-facing aliases, and which must be removed from internal/project authority immediately in the VNext cleanup?
- Should public account-profile URLs remain type-agnostic for permanence, or should any type-shaped route be treated only as a resolvable alias rather than the canonical link?

## Expected Outputs

- Updated top-level docs with no misleading canonical `partner` entity language.
- A normalized module map and renamed/restructured module files where needed.
- A migration list for code/docs vocabulary updates that depend on the same decision.
- Clear separation between internal canonical language and any intentionally preserved public-facing aliases.

## Explicit Non-Goals For Current Lane

- This TODO does **not** force immediate route-path or product-copy breaking changes during the store-release lane.
- This TODO does **not** reopen the already-established `Account` / `Account Profile` canonical model.
- This TODO does **not** assume every existing `partner*` code symbol can or should be renamed in one pass.
- This TODO does **not** absorb the broader Flutter domain-topology normalization work; that remains owned by `TODO-vnext-flutter-domain-topology-normalization.md`.

## Related TODOs

- `foundation_documentation/todos/active/vnext/TODO-vnext-flutter-domain-topology-normalization.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-route-paths-refactor.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this is a downstream normalization of Bóora!/Belluga Now documentation and implementation vocabulary. The cleanup may later inform broader guidance, but the concrete residue, module names, and public-route implications are project-specific first.

## Success Condition

This TODO is complete when the project no longer treats `partner` as canonical internal domain language, and all relevant authority/module surfaces consistently describe the current model through `Account`, `Account Profile`, and `Profile Type`, with any remaining public-facing aliases kept only by explicit decision.
