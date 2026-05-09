# Orchestration Checkpoint Manifest: Post-Release OTP and Linked Profile Agenda Hardening

## Artifact Identity
- **Artifact type:** `orchestration_checkpoint_manifest`
- **Checkpoint status:** `wip_checkpoint`
- **Created:** `2026-05-04`
- **Governing workflow / skill:** `delphi-ai/workflows/docker/subagent-worktree-reconciliation-method.md`
- **Authority boundary:** governing TODOs and canonical module docs remain authoritative.

## Scope
| ID | Governing TODO | Included in checkpoint | Delivery stage after checkpoint |
| --- | --- | --- | --- |
| `PRH-OTP` | `foundation_documentation/todos/active/fast_follow_required/TODO-post-release-phone-otp-concurrency-and-idempotency-hardening.md` | `yes` | `Pending` |
| `PRH-FEDERACAO` | `foundation_documentation/todos/active/fast_follow_required/TODO-post-release-account-profile-agenda-linked-profile-resolution.md` | `yes` | `Pending` |

## Repository Checkpoint SHAs
No worker checkpoint commits exist yet. This checkpoint freezes the starting topology before implementation begins.

| Repository | Branch | Commit SHA | Push target | Included | Notes |
| --- | --- | --- | --- | --- | --- |
| `docker-root` | `reconcile/post-release-otp-linked-profile-hardening-20260504` | `7965abff8e8a956367762f422f9d7fd82efde923` | `origin/reconcile/post-release-otp-linked-profile-hardening-20260504` | `yes` | Reconciliation worktree created from `origin/main`; submodule SHAs still match deployed `main` until worker checkpoints are merged. |
| `flutter-app` | `worker/post-release-otp-admin-settings-20260504` | `352b67230bf69c635fe048d9a2a2ca7554df2a83` | `origin/worker/post-release-otp-admin-settings-20260504` | `yes` | Worker worktree created from `origin/main`; no implementation commit yet. |
| `flutter-app` | `worker/post-release-profile-readback-20260504` | `352b67230bf69c635fe048d9a2a2ca7554df2a83` | `origin/worker/post-release-profile-readback-20260504` | `yes` | Readback worker worktree created once Flutter public-profile validation was confirmed as an explicit deliverable. |
| `laravel-app` | `worker/post-release-otp-backend-20260504` | `4ef91be37b53abdcedd007a1642809543242df39` | `origin/worker/post-release-otp-backend-20260504` | `yes` | Worker worktree created from `origin/main`; no implementation commit yet. |
| `laravel-app` | `worker/post-release-linked-profile-agenda-20260504` | `4ef91be37b53abdcedd007a1642809543242df39` | `origin/worker/post-release-linked-profile-agenda-20260504` | `yes` | Worker worktree created from `origin/main`; no implementation commit yet. |
| `foundation_documentation` | `docs/store-release-agenda-card-polish-occurrence-taxonomy-20260501` | `037da3e0c7ab2c0b48055d077396b68599cc7aca` | `origin/docs/store-release-agenda-card-polish-occurrence-taxonomy-20260501` | `yes` | Current docs workspace carries the approved plan/TODO deltas; no checkpoint commit yet. |
| `web-app` | `submodule-detached-at-root-main` | `621596f02be5f973f33afb4e965923f458b28a78` | `n/a` | `no` | Excluded unless a later runtime/browser evidence path explicitly needs a publish artifact owned by a follow-up lane. |

## Evidence Summary
| Area | Evidence | Status |
| --- | --- | --- |
| `completion guards` | `python3 delphi-ai/tools/orchestration_plan_completion_guard.py --plan foundation_documentation/artifacts/execution-plans/post-release-otp-linked-profile-hardening-orchestration-plan.md` -> `Overall outcome: go` | `passed` |
| `tests` | `not started` | `n/a` |
| `runtime/browser/device` | `not started` | `n/a` |
| `build/publish freshness` | `not started` | `n/a` |

## Exclusions / Dirty Surfaces
| Path / Repository | Reason Excluded | Follow-up |
| --- | --- | --- |
| `web-app generated output` | `generated artifact` | `leave dirty unless a later publish lane owns it` |
| `current principal checkout outside reconciliation worktree` | `legacy branch state unrelated to this approved execution topology` | `ignore; use reconciliation/worker worktrees only for code execution` |

## Branch Lifecycle Decision
- **Next exact step:** `spawn worker implementation on the frozen worktrees, then merge checkpoints into the reconciliation branch`
- **Same-branch continuation allowed:** `yes`
- **Why:** `this is the first execution checkpoint inside the approved orchestration wave`

## Frozen Execution Recipes
- **Laravel CI equivalent:** `bash scripts/local_laravel_ci_equivalent.sh`
- **Flutter Validate and Build Web equivalent:** `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build`
- **Worktree recipe frozen before execution:**
  - root reconciliation worktree from `origin/main`
  - `laravel-app` workers for `OTP backend` and `agenda capability resolution`
  - `flutter-app` worker for `OTP admin settings`
  - `flutter-app` readback worker for public-profile agenda validation

## Frozen OTP Dependency Decisions
- **`PRH-OTP-DEP-01` Safe concurrency probe workflow**
  - Use `bash delphi-ai/tools/backend_concurrency_probe.sh`.
  - Probe only the local reconciliation-owned Laravel runtime on `http://127.0.0.1:8000`.
  - Freeze concurrency levels to `5`, `10`, and `20`.
  - Use tenant-scoped test data with outbound delivery faked or captured safely; no production/stage probe target is allowed for acceptance evidence.
- **`PRH-OTP-DEP-02` Atomicity mechanism**
  - `verify` success path: claim the challenge exactly once with Mongo compare-and-swap semantics (`findOneAndUpdate`/conditional update) before issuing any credential.
  - `verify` invalid-code path: use atomic `$inc`-style attempt mutation plus deterministic lock transition on the same challenge document.
  - `challenge` issuance path: use a phone-scoped uniqueness guarantee for pending challenges (partial unique index or equivalent duplicate-key-safe strategy) together with conditional supersede/create handling; do not rely on app-level read-modify-write alone.
- **`PRH-OTP-DEP-03` Reviewer-access policy freeze**
  - Dedicated allowlisted review phone only.
  - Review credential stored as hash only in backend-private tenant settings.
  - Admin/operator UX may accept cleartext helper input solely to generate the hash; cleartext is never persisted or read back.
  - Review phone resolves through the normal phone-auth identity flow and remains revocable by disabling the resolved user.
  - Review path remains rate-limited, audit-logged, and documented in English for Play Console app access.

## Frozen OTP Settings Contract
- **Tenant-admin namespace:** `phone_otp_review_access`
- **Persisted fields:** `phone_e164`, `code_hash`
- **Readback rule:** admin values may return `phone_e164` and `code_hash`; no cleartext review code is ever returned.
- **Hash helper contract:** a tenant-admin helper endpoint may accept transient cleartext `code` and return generated `code_hash` without persisting the cleartext value.
- **Environment/public payload rule:** `phone_otp_review_access` must never be serialized into tenant public environment/bootstrap payloads.

## Notes
- Runtime acceptance for both slices must prove the merged reconciliation state, not a previously published production or mirror state.
- `federacao` remains only the production repro case. The capability-driven agenda contract is generic and must not be hardcoded by type name.
