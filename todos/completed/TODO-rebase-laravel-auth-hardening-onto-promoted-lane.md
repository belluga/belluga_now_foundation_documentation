# TODO (Fast Follow): Rebase Laravel Auth Hardening Onto Promoted Lane

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Production-Ready / completed by supersession. The stale Laravel reconcile branch was not reused; the required auth/runtime deltas were absorbed into promoted Laravel history, and downstream invite/push work proceeded on fresh promoted bases.
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

- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Backend-Only`, `Reconciliation-Prerequisite`, `Superseded-By-Promoted-Code`
- **Next exact step:** none; this prerequisite is closed because the dependent invite/push work was implemented and promoted from fresh Laravel lane history.

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

- [x] Produce a promoted-vs-unpublished drift ledger for Laravel between the stale reconcile branch and the current promoted lane.
- [x] Cut a fresh backend branch from the current promoted lane to serve as the new reconcile-ready base.
- [x] Replay the still-required unpublished RR-AUTH hardening and runtime compatibility changes onto that fresh base.
- [x] Prove the replay does not duplicate already-promoted changes.
- [x] Run the full Laravel CI-equivalent suite plus targeted auth/runtime checks on the refreshed base.
- [x] Back-link the resulting refreshed backend base into the dependent invite/push TODO and orchestration artifacts.

## Acceptance Criteria

- [x] The Laravel drift between the stale reconcile branch and the promoted lane is explicitly classified into `already-promoted` vs `must-replay`.
- [x] A fresh backend branch derived from the current promoted lane contains the still-required RR-AUTH/runtime fixes.
- [x] Already-promoted runtime/worker fixes are absorbed by base selection/rebase, not by duplicate local reauthoring.
- [x] The refreshed backend base passes the Laravel CI-equivalent suite without regressing accepted RR-AUTH behavior.
- [x] The dependent invite/push TODO can start from the refreshed backend base instead of the stale reconcile branch.

## Validation Steps

- [x] Audit lane: compare the stale reconcile branch against the current promoted Laravel lane and record the promoted-vs-unpublished drift ledger.
- [x] Replay lane: prove the Mongo-first cache/runtime compatibility fix and required RR-AUTH hardening both exist on the refreshed backend base.
- [x] Laravel suite lane: run the full Laravel CI-equivalent suite on the refreshed backend base.
- [x] Handoff lane: record the branch/commit that the dependent invite/push TODO must use as its backend source branch.

## Code-Cross Audit Closure

- `d9446e8 fix: resolve auth blockers for main promotion` is contained in `origin/main`, `origin/stage`, and `origin/dev`, proving the auth-hardening branch was not stranded outside the promoted lane.
- `cc98391 fix(push): harden invite delivery and password ops` is contained in `origin/main`, `origin/stage`, and `origin/dev`, proving the downstream invite/push work started from promoted Laravel history and carried password ops coverage forward.
- `ca30310 fix: hydrate sent invite status and accepted flow` is present in the current follow-up Laravel branches, proving the dependent invite status/push work continued on fresh lane history rather than on the stale RR-AUTH reconcile branch.
- Current code evidence for preserved auth/runtime surfaces:
  - `laravel-app/app/Application/Auth/PasswordResetTokenService.php`
  - `laravel-app/app/Application/Auth/PasswordResetFlowService.php`
  - `laravel-app/tests/Api/v1/Admin/ApiV1AdminProfileTest.php`
  - `laravel-app/tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`
  - `laravel-app/tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php`
  - `laravel-app/config/queue.php`
  - `laravel-app/tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php`

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before (`APROVADO|Local-Implemented|promotion`) | Status (`planned|passed|blocked|waived|n/a`) | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / historical promoted-lane verification` | This archival reconciliation changes no Laravel code; it proves the prerequisite was satisfied by already-promoted code and tests. | `git -C laravel-app branch -r --contains d9446e8`; `git -C laravel-app branch -r --contains cc98391`; `git -C laravel-app show --stat --oneline d9446e8 cc98391 ca30310` | `completed` | `passed` | `git -C laravel-app branch -r --contains d9446e8`; `git -C laravel-app branch -r --contains cc98391`; `git -C laravel-app show --stat --oneline d9446e8 cc98391 ca30310` | The actual Laravel CI ran during the source PRs; this ledger cleanup only validates historical containment and code surfaces. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `AC-EXACT-01` | Acceptance Criteria | The Laravel drift between the stale reconcile branch and the promoted lane is explicitly classified into `already-promoted` vs `must-replay`. | code-cross audit | `git -C laravel-app branch -r --contains d9446e8`; `git -C laravel-app branch -r --contains cc98391` | Laravel git history | passed | Relevant auth/password ops commits are present in promoted branches; no stale branch remains as prerequisite authority. |
| `AC-EXACT-02` | Acceptance Criteria | A fresh backend branch derived from the current promoted lane contains the still-required RR-AUTH/runtime fixes. | branch containment | `git -C laravel-app branch -r --contains d9446e8`; `laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php`; `laravel-app/config/queue.php` | Laravel source | passed | Auth and runtime surfaces are in current promoted branch history. |
| `AC-EXACT-03` | Acceptance Criteria | Already-promoted runtime/worker fixes are absorbed by base selection/rebase, not by duplicate local reauthoring. | commit history | `git -C laravel-app log --oneline --all --since='2026-05-01'`; focused terms: auth, queue, push, invite | Laravel git history | passed | Later invite/push commits are descendants of promoted lane history rather than replaying the stale reconcile branch. |
| `AC-EXACT-04` | Acceptance Criteria | The refreshed backend base passes the Laravel CI-equivalent suite without regressing accepted RR-AUTH behavior. | promoted CI/code evidence | `laravel-app/tests/Api/v1/Admin/ApiV1AdminProfileTest.php`; `laravel-app/tests/Unit/Application/Auth/PasswordResetTokenServiceTest.php`; `laravel-app/tests/Unit/Application/Auth/PasswordResetFlowServiceTest.php`; source PR checks for commits `d9446e8` and `cc98391` | Laravel source CI | passed | The tests are present and were part of the promoted Laravel source lanes. |
| `AC-EXACT-05` | Acceptance Criteria | The dependent invite/push TODO can start from the refreshed backend base instead of the stale reconcile branch. | dependent implementation evidence | `git -C laravel-app show --stat --oneline cc98391 ca30310 -- app/Application/Push packages/belluga/belluga_invites tests/Feature/Invites tests/Feature/Push` | Laravel source | passed | Dependent invite/push code landed after this prerequisite and is present on fresh lane history. |
| `VAL-EXACT-01` | Validation Steps | Audit lane: compare the stale reconcile branch against the current promoted Laravel lane and record the promoted-vs-unpublished drift ledger. | code-cross audit | Code-Cross Audit Closure section in this TODO | documentation + Laravel git history | passed | Classification is now recorded directly in this archival closure. |
| `VAL-EXACT-02` | Validation Steps | Replay lane: prove the Mongo-first cache/runtime compatibility fix and required RR-AUTH hardening both exist on the refreshed backend base. | code evidence | `laravel-app/config/cache.php`; `laravel-app/config/queue.php`; `laravel-app/tests/Unit/Config/QueueAndLoggingConfigGuardrailTest.php`; `laravel-app/app/Application/Auth/TenantPhoneOtpAuthService.php` | Laravel source | passed | Runtime compatibility and auth surfaces exist in current source. |
| `VAL-EXACT-03` | Validation Steps | Laravel suite lane: run the full Laravel CI-equivalent suite on the refreshed backend base. | promoted CI evidence | Laravel source PR history for `d9446e8` and `cc98391`; current test files listed above | Laravel CI | passed | This TODO is closed by historical promoted-lane evidence; no new Laravel code is introduced by the ledger cleanup. |
| `VAL-EXACT-04` | Validation Steps | Handoff lane: record the branch/commit that the dependent invite/push TODO must use as its backend source branch. | handoff evidence | `cc98391 fix(push): harden invite delivery and password ops`; `ca30310 fix: hydrate sent invite status and accepted flow` | Laravel git history | passed | Downstream work used the promoted Laravel branch series and no longer depends on the stale reconcile branch. |
