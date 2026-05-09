# TODO (Fast Follow): Rebase Laravel Auth Hardening Onto Promoted Lane

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. This TODO owns the backend rebase/replay step required before new invite/push work can proceed on a fresh Laravel base.
**Owners:** Delphi (Laravel) + Runtime / Integration
**Goal:** carry the RR-AUTH Laravel hardening and runtime-compatibility fixes forward onto the current promoted backend lane without reauthoring changes that are already in `main`, so downstream invite/push work starts from a fresh, validated base.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The current Laravel reconciliation branch for `RR-AUTH` is not a safe implementation base for new work:

- it is behind the promoted lane by a large set of unrelated merges;
- it contains unpublished RR-AUTH hardening that still needs to survive;
- some runtime fixes needed for local/browser validation now exist only on the reconciliation branch;
- at least one relevant runtime worker fix is already promoted, so replay must distinguish promoted drift from unpublished reconcile-only deltas.

Before the invite/push TODO is implemented, the backend must be re-based conceptually onto the latest promoted lane and the still-required RR-AUTH/backend-runtime improvements must be replayed there without regression.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-00`
- **Why this is the right current slice:** backend freshness is a prerequisite for trustworthy execution of the dependent invite/push TODO.
- **Direct-to-TODO rationale:** the problem is already diagnosis-bounded; the missing work is controlled replay/integration plus validation.

## Contract Boundary

- This TODO defines **WHAT** must be preserved and proven during the Laravel rebase/replay step.
- It does not authorize unrelated feature work, schema redesign, or user-facing invite/push implementation beyond what is needed to refresh the backend base.
- It must preserve already-promoted changes rather than reauthor them inside a stale branch.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Fast-Follow`, `Backend-Only`, `Reconciliation-Prerequisite`
- **Next exact step:** inventory promoted-vs-unpublished backend drift, cut a fresh reconcile-ready backend branch from the promoted lane, replay the still-required RR-AUTH/runtime fixes, and rerun the full Laravel CI-equivalent suite.

## Complexity / Execution Profile

- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `laravel + runtime validation + foundation docs handoff`

## Canonical Module Anchors

- **Primary module docs:**
  - `foundation_documentation/modules/auth_identity_access_module.md`
  - `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`

## Decision Baseline (Frozen 2026-05-09)

- [x] `D-01` The stale `laravel-app/reconcile/post-release-rule-related-auth-identity-20260506` branch must not be reused directly as the implementation base for new invite/push work.
- [x] `D-02` The fresh backend base must start from the latest promoted Laravel lane and replay only the still-required unpublished RR-AUTH/runtime deltas.
- [x] `D-03` Changes that are already promoted in `main`/`stage` must be classified as promoted drift and must not be reauthored locally.
- [x] `D-04` The refreshed backend base must preserve accepted RR-AUTH hardening plus the Mongo-first cache/runtime compatibility fix needed for truthful local validation.
- [x] `D-05` Completion of this TODO is the backend prerequisite for the dependent invite/push/share-metadata TODO.

## Scope

- Audit the current Laravel reconcile branch against the latest promoted lane.
- Classify promoted drift vs reconcile-only deltas that still need to survive.
- Cut a fresh backend branch from the promoted lane and replay the needed changes there.
- Revalidate the resulting backend base with the full Laravel CI-equivalent suite and targeted auth/runtime tests.
- Record the resulting handoff for the dependent invite/push TODO.

## Out of Scope

- New invite/push/share-metadata implementation.
- Flutter tenant-admin work.
- Promotion to `stage` or `main`.

## Implementation Tasks

- [ ] ⚪ Produce a promoted-vs-unpublished drift ledger for Laravel between the stale reconcile branch and the current promoted lane.
- [ ] ⚪ Cut a fresh backend branch from the current promoted lane to serve as the new reconcile-ready base.
- [ ] ⚪ Replay the still-required unpublished RR-AUTH hardening and runtime compatibility changes onto that fresh base.
- [ ] ⚪ Prove the replay does not duplicate already-promoted changes.
- [ ] ⚪ Run the full Laravel CI-equivalent suite plus targeted auth/runtime checks on the refreshed base.
- [ ] ⚪ Back-link the resulting refreshed backend base into the dependent invite/push TODO and orchestration artifacts.

## Acceptance Criteria

- [ ] ⚪ The Laravel drift between the stale reconcile branch and the promoted lane is explicitly classified into `already-promoted` vs `must-replay`.
- [ ] ⚪ A fresh backend branch derived from the current promoted lane contains the still-required RR-AUTH/runtime fixes.
- [ ] ⚪ Already-promoted runtime/worker fixes are absorbed by base selection/rebase, not by duplicate local reauthoring.
- [ ] ⚪ The refreshed backend base passes the Laravel CI-equivalent suite without regressing accepted RR-AUTH behavior.
- [ ] ⚪ The dependent invite/push TODO can start from the refreshed backend base instead of the stale reconcile branch.

## Validation Steps

- [ ] Audit lane: compare the stale reconcile branch against the current promoted Laravel lane and record the promoted-vs-unpublished drift ledger.
- [ ] Replay lane: prove the Mongo-first cache/runtime compatibility fix and required RR-AUTH hardening both exist on the refreshed backend base.
- [ ] Laravel suite lane: run the full Laravel CI-equivalent suite on the refreshed backend base.
- [ ] Handoff lane: record the branch/commit that the dependent invite/push TODO must use as its backend source branch.

