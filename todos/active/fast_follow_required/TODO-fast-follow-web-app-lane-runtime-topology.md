# TODO (Fast Follow): Resolve Web-App Runtime By Lane With Explicit Repo Variable

**Status legend:** `- [ ] Pending` - `- [ ] Local-Implemented` - `- [ ] Lane-Promoted` - `- [ ] Production-Ready`.
**Status:** Active / code-cross blocker found. Most audited `WEB-*` runtime authority wiring reached `main`, but this standalone TODO must remain active because real code still contradicts fallback-zero in `check_remote_web_runtime_sha_over_ssh.sh`: when `EXPECTED_WEB_APP_RUNTIME_SHA` is absent, it falls back to the local `web-app` checkout instead of failing closed.

## Title
Fast Follow: Resolve Web-App Runtime By Lane With Explicit Repo Variable

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
- Docker/root promotion must preserve linear lane promotion and descendancy: the root candidate promoted to `main` should be the same root package already promoted through `stage`, not a main-only repin commit.
- `web-app` is a derived Flutter artifact repository. Its runtime domain is lane-specific:
  - `web-app/stage` serves the stage artifact and injects the stage host.
  - `web-app/main` serves the production artifact and injects the production host.
- Current Docker/root scripts still treat the `web-app` submodule gitlink as the authoritative runtime web pin.
- That contaminates `stage -> main` promotion because the `stage` root candidate necessarily pins a `web-app` artifact that may be correct for stage but wrong for production.
- The prior fail-closed pipeline hardening contract remains governing. This TODO fixes the current web runtime authority mismatch, but by itself cannot close the healthy-final-state invariant because Docker `app`, `worker`, and `scheduler` rollback still depend on host-local rebuilds until the immutable deploy artifact track is implemented.
- Operator direction on 2026-05-22: this work must be delivered in sync with the healthy-final-state closure. Therefore this TODO is no longer an independent implementation approval candidate; it is carried as the detailed `WEB-*` sub-contract under the governing healthy-final-state matrix.
- The corrected runtime contract should be lane-resolved, not root-gitlink-resolved:
  - deploy `stage` uses `web-app/origin/stage`
  - deploy `main` uses `web-app/origin/main`
  - rollback uses the exact previously recorded `WEB_APP_RUNTIME_SHA`
- The lane must know which repository contains the generated `web-app` artifact through a visible GitHub Actions repository variable, not through a secret and not through `.gitmodules`.
- The root repository may keep a local `web-app` checkout/submodule only as auxiliary developer tooling during the transition, but no protected deploy, preflight, provenance, or rollback path may treat the root `web-app` gitlink as runtime authority.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `fast-follow-web-app-lane-runtime-topology`
- **Direct-to-TODO rationale:** the issue is one bounded CI/runtime topology correction discovered during Docker `stage -> main` promotion: remove the ambiguous `web-app` root gitlink authority and make runtime resolution explicit by lane while preserving rollback fidelity.

## Contract Boundary
- This TODO covers the Docker/root CI and runtime topology that resolves web runtime artifacts for protected deploys.
- This TODO includes:
  - removing `web-app` root gitlink/submodule state as protected runtime authority
  - sourcing the `web-app` repository slug from the visible GitHub Actions repository variable `WEB_APP_REPO`
  - replacing root-gitlink web runtime resolution with lane-aware `web-app` repository resolution
  - preserving fail-closed validation of `flutter_git_sha` compatibility and lane host injection
  - preserving fail-closed validation that `build_metadata.source_branch` matches the lane for lane-resolved artifacts
  - recording the exact effective `WEB_APP_RUNTIME_SHA` used by deploys
  - ensuring rollback restores the recorded web runtime SHA instead of recalculating from a branch
  - updating deterministic CI-equivalent invariants that currently enforce the old gitlink contract
  - preserving the fail-closed pipeline healthy-final-state invariant as the parent safety contract
- This TODO does **not** include:
  - changing Flutter build output semantics
  - changing Laravel runtime behavior
  - changing public domains or lane definitions
  - weakening production mutation hard-blocking
  - bypassing web provenance checks
  - claiming full structural closure of immutable artifact rollforward/rollback
  - closing `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md` without the Docker image artifact rollback path required by `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Fast-Follow`, `Docker`, `CI-Runtime-Topology`, `Main-Promotion-Blocker`, `Rollback-Sensitive`, `Audit-Required`
- **Next exact step:** remove the local-checkout fallback from `check_remote_web_runtime_sha_over_ssh.sh`, add a deterministic invariant in `verify_environment_ci.sh` proving `EXPECTED_WEB_APP_RUNTIME_SHA` is mandatory, then reconcile this TODO against the governing healthy-state completion evidence.

## Code-Cross Audit Checkpoint

- `2026-05-24`: real-code inspection found that `.github/scripts/check_remote_web_runtime_sha_over_ssh.sh` still computes `expected_web_runtime_sha` from `git -C "${repo_root}/web-app" rev-parse HEAD` when `EXPECTED_WEB_APP_RUNTIME_SHA` is empty.
- That path violates this TODO's explicit Scope item: `Remove local-checkout fallback from check_remote_web_runtime_sha_over_ssh.sh`; `EXPECTED_WEB_APP_RUNTIME_SHA` becomes mandatory.
- `bash .github/scripts/verify_environment_ci.sh` currently passes despite this fallback, so the deterministic guard is incomplete and must be extended before this TODO can move to `completed`.
- Related implemented surfaces remain real and should be preserved, not redone:
  - `.github/scripts/resolve_web_app_runtime_sha.sh` hard-gates `DEPLOY_LANE` to `stage|main` and resolves `WEB_APP_REPO` from `vars`.
  - `.github/workflows/orchestration-ci-cd.yml` passes `WEB_APP_REPO: ${{ vars.WEB_APP_REPO }}` and propagates resolved runtime SHA into deploy/proof steps.
  - `.github/scripts/mark_successful_revision_over_ssh.sh` records `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha` and `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1`.

## Complexity Policy
- **Complexity:** `big`
- **Checkpoint cadence:** section-by-section review checkpoints before approval, because the change spans preflight, deploy, rollback, remote runtime proof, and deterministic CI invariants.

## Primary Execution Profile
- **Primary profile:** `Operational / DevOps`
- **Active technical scope:** `docker`, `ci-cd`, `runtime-deploy`
- **Supporting profiles expected:** `Operational / QA` for regression/evidence review; `Strategic / CTO-Tech-Lead` is required before closure because this is a stable cross-stack runtime authority rule that must be promoted out of the tactical TODO.

## Scope
- [ ] Remove `web-app` root gitlink/submodule state as runtime authority for protected deploys, preflight, provenance, and rollback.
- [ ] Introduce a single authoritative resolver for effective web runtime SHA by lane.
- [ ] Source the web repository slug from the visible GitHub Actions repository variable `WEB_APP_REPO`; secrets are allowed only for authentication tokens, not for the repo slug.
- [ ] Bind workflow env for the repo slug from `${{ vars.WEB_APP_REPO }}` and add deterministic invariant coverage that rejects `${{ secrets.WEB_APP_REPO }}` or `.gitmodules` as repo-slug authority in protected runtime paths.
- [ ] Verify or document provisioning of the GitHub Actions repository variable `WEB_APP_REPO` for the Docker/root repository; absence must be an operator-visible configuration failure, not an implicit fallback.
- [ ] Fail closed when `WEB_APP_REPO` is absent, empty, malformed, or not an `owner/repo` slug.
- [ ] Forbid `.gitmodules`, local submodule config, or local `web-app` checkout as fallback sources for the protected runtime repo slug.
- [ ] Hard-gate `DEPLOY_LANE` to `stage|main` before resolving any web runtime branch; arbitrary branch names must fail closed.
- [ ] Make preflight resolve `origin/${DEPLOY_LANE}` to exactly one immutable `WEB_APP_RUNTIME_SHA`.
- [ ] Propagate `WEB_APP_REPO`, `DEPLOY_LANE`, and `WEB_APP_RUNTIME_SHA` from preflight into deploy, rollback, provenance, and proof steps through explicit workflow outputs/env; consumers must fail closed if the value is absent or differs from the preflight output.
- [ ] Make `check_web_flutter_metadata.sh` validate the exact resolved web artifact SHA instead of the root gitlink or a moving branch.
- [ ] Make protected deploy checkout the exact preflight-resolved `WEB_APP_RUNTIME_SHA`; deploy must not re-resolve `origin/${DEPLOY_LANE}`.
- [ ] Make protected deploy prove the exact `WEB_APP_RUNTIME_SHA` is fetchable and checkoutable on the remote host before mutating root revision, submodules, containers, `.env`, or successful-release state.
- [ ] Keep web repo fetch/checkout bounded: fetch the lane tip or exact SHA with finite timeout/retry behavior, avoid full-history clones where possible, and do not run `git submodule update --recursive` for `web-app` as runtime authority.
- [ ] Make successful-release tuple capture the effective web runtime SHA used by the deploy.
- [ ] Extend the successful-release tuple schema to record `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha` and `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1` alongside `ROOT_SHA`, `WEB_APP_RUNTIME_SHA`, `DEPLOY_LANE`, and `RECORDED_AT`.
- [ ] Make rollback restore `WEB_APP_RUNTIME_SHA` from the tuple and fail if it is absent or invalid.
- [ ] Make remote web runtime SHA validation compare against the effective expected SHA, not a local submodule checkout.
- [ ] Update `verify_environment_ci.sh` to enforce the new no-root-web-gitlink-authority contract and prevent regressions back to root-gitlink runtime resolution.
- [ ] Preserve the existing final-healthy CI contract: stage/main success marking must still require initialized environment, trusted successful-release tuple, exact remote web runtime SHA match, public-edge probe, provenance proof, and all existing lane-specific navigation/mutation/taxonomy gates.
- [ ] Preserve existing terminal fail-closed handling: any failure in runtime web SHA proof, public-edge, provenance, rollback proof, navigation, mutation hard-block, taxonomy, or bootstrap trust gates must still end as explicit degraded/incident unless a proved rollback restores the previous healthy tuple.
- [ ] Update workflow fallback/capture paths that currently derive rollback web SHA from `git ls-tree <root> web-app`.
- [ ] Add explicit web-app repo clone/fetch before `checkout_web_runtime_ref` in forward deploy and rollback scripts, authenticated with `SUBMODULES_REPO_TOKEN`.
- [ ] Remove local-checkout fallback from `check_remote_web_runtime_sha_over_ssh.sh`; `EXPECTED_WEB_APP_RUNTIME_SHA` becomes mandatory.
- [ ] If the physical root `web-app` checkout/submodule is retained during this patch, update readiness and CI checks so it is never fetched or interpreted as protected runtime authority.
- [ ] Update operator/developer documentation for inspecting lane web artifacts and validating exact `WEB_APP_RUNTIME_SHA` through `WEB_APP_REPO`, independent of any root submodule checkout.
- [ ] Promote the stable cross-stack runtime authority decision into canonical project documentation before TODO closure.
- [ ] Merge this TODO into `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md` as the web runtime authority sub-contract before implementation approval.
- [ ] Preserve the relationship with `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`: this TODO closes the current lane-resolved web runtime authority gap, but does not claim full final-healthy invariant closure until the Docker image immutable artifact rollback path is complete.

## Out Of Scope
- [ ] Rebuilding Flutter artifacts.
- [ ] Rewriting the web artifact publish workflow unless an audit proves it is necessary for the lane-resolved runtime contract.
- [ ] Creating main-only Docker repin commits.
- [ ] Removing `web-app` repository or changing its branch model.
- [ ] Changing app/backend feature behavior.

## Definition Of Done
- [ ] No protected runtime path derives the web repo slug or web runtime SHA from `.gitmodules`, local submodule config, local `web-app`, or `git ls-tree <root> web-app`.
- [ ] GitHub Actions repository variable `WEB_APP_REPO` is the canonical visible repo-slug source for web runtime resolution.
- [ ] Workflow YAML uses `${{ vars.WEB_APP_REPO }}` for the repo slug and deterministic invariants reject `${{ secrets.WEB_APP_REPO }}` as slug authority.
- [ ] `WEB_APP_REPO` provisioning/check instructions are documented for operators, and missing/empty/malformed values fail before runtime mutation.
- [ ] `DEPLOY_LANE` is accepted only as `stage|main` in protected runtime resolution paths.
- [ ] Preflight resolves and validates one exact `WEB_APP_RUNTIME_SHA`, and deploy consumes that exact SHA without re-reading a branch tip.
- [ ] The preflight `WEB_APP_RUNTIME_SHA` is transported as an explicit workflow output/env to deploy, rollback, provenance, and proof steps; absence or divergence fails closed.
- [ ] Protected deploy proves the exact web runtime SHA is fetchable/checkoutable on the remote host before mutating root revision, submodules, containers, `.env`, or release tuple state.
- [ ] Web runtime fetch/checkout is bounded by finite timeout/retry behavior and does not require full-history clone or recursive submodule update for `web-app`.
- [ ] `stage` validation fails closed if `web-app/stage` has incompatible `flutter_git_sha` or host injection.
- [ ] `main` validation fails closed if `web-app/main` has incompatible `flutter_git_sha` or host injection.
- [ ] `stage` and `main` validation fail closed if `build_metadata.source_branch` does not match the lane for the resolved artifact.
- [ ] Protected deploy records `.last_successful_revision` as a `KEY=VALUE` tuple containing exact keys `ROOT_SHA`, `WEB_APP_RUNTIME_SHA`, `WEB_APP_RUNTIME_AUTHORITY`, `RUNTIME_TOPOLOGY_VERSION`, `DEPLOY_LANE`, and `RECORDED_AT`.
- [ ] Rollback restores the recorded web runtime SHA and does not float to branch tip.
- [ ] Current lane-resolved releases require `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha`, `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1`, and `WEB_APP_RUNTIME_SHA`; historical fallback to a root gitlink is allowed only for target revisions that predate this tuple authority and contain the historical `web-app` tree entry.
- [ ] Remote runtime verification proves the deployed `web-app` checkout matches the expected effective SHA.
- [ ] Docker/root `stage -> main` promotion can remain a normal root promotion without a main-only `web-app` repin commit.
- [ ] Post-deploy provenance proves the remote served web artifact matches the preflight `WEB_APP_RUNTIME_SHA` before writing or accepting a successful-release tuple.
- [ ] No code path in preflight, deploy, rollback, workflow rollback capture, or CI invariants derives protected web runtime authority from `git ls-tree <root> web-app` for current releases.
- [ ] Existing final-healthy branch gates remain intact: success marking for stage/main is still blocked unless initialize, trusted tuple, remote web SHA, public-edge, provenance, and lane-specific validation/smoke gates pass.
- [ ] Existing terminal degraded/incident fail-closed branches remain intact for stage/main and include rollback-proof web runtime SHA mismatch handling.
- [ ] The implementation ships as one atomic commit or uninterrupted patch set with no committed intermediate state that mixes root-gitlink and lane-resolved runtime authority.
- [ ] Stable runtime authority decisions are reflected in canonical project documentation before TODO closure.
- [ ] Delivery notes explicitly state compatibility with the fail-closed pipeline healthy-final-state TODO and do not mark the broader final-healthy invariant fully closed.

## Validation Steps
- [ ] Negative proof: validation fails when `web-app/main` metadata references an incompatible Flutter SHA.
- [ ] Negative proof: validation fails when `web-app/main` injects a host different from `config/defines/main.json`.
- [ ] Negative proof: validation fails when the resolved `web-app/main` artifact declares `source_branch` other than `main`.
- [ ] Negative proof: validation fails when `web-app/stage` metadata references an incompatible Flutter SHA.
- [ ] Negative proof: validation fails when `web-app/stage` injects a host different from `config/defines/stage.json`.
- [ ] Negative proof: validation fails when the resolved `web-app/stage` artifact declares `source_branch` other than `stage`.
- [ ] Negative proof: preflight fails when `WEB_APP_REPO` is missing, empty, or malformed.
- [ ] Negative proof: deterministic workflow invariant fails if the protected runtime repo slug is bound from `${{ secrets.WEB_APP_REPO }}` instead of `${{ vars.WEB_APP_REPO }}`.
- [ ] Negative proof: protected runtime resolution fails when `DEPLOY_LANE` is not `stage|main`.
- [ ] Negative proof: deploy/rollback scripts fail if rollback tuple lacks `WEB_APP_RUNTIME_SHA`.
- [ ] Negative proof: deploy/rollback scripts fail if a current lane-resolved tuple lacks `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha` or `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1`.
- [ ] Negative proof: `check_remote_web_runtime_sha_over_ssh.sh` fails closed when `EXPECTED_WEB_APP_RUNTIME_SHA` is absent or empty, even if local `web-app` exists.
- [ ] Negative proof: branch tip moves after preflight; deploy still uses the preflight-resolved `WEB_APP_RUNTIME_SHA`.
- [ ] Negative proof: the preflight-resolved `WEB_APP_RUNTIME_SHA` becomes unreachable to the remote deploy because of force-push, permission, or network failure; result is fail-closed before successful tuple acceptance or runtime mutation.
- [ ] Negative proof: workflow rollback-capture fails closed when a current lane-resolved release tuple is missing `WEB_APP_RUNTIME_SHA`.
- [ ] Negative proof: root gitlink, `.gitmodules`, or local `web-app` points to a divergent SHA; preflight/deploy/rollback/provenance still use `WEB_APP_REPO` + preflight `WEB_APP_RUNTIME_SHA`, and any fallback to those local paths fails CI-equivalent.
- [ ] Negative proof: final-healthy invariants fail if stage/main success marking drops initialized, trusted tuple, remote web SHA, public-edge, provenance, navigation/mutation/taxonomy, or mutation hard-block conditions.
- [ ] Negative proof: terminal fail-closed invariants fail if stage/main degraded/incident handling no longer triggers on web SHA, public-edge, provenance, rollback-proof, navigation, mutation, taxonomy, or bootstrap trust failures.
- [ ] Positive proof: validation accepts `web-app/main` when SHA compatibility and host match, independent of any root `web-app` gitlink.
- [ ] Positive proof: validation accepts `web-app/stage` when SHA compatibility and host match.
- [ ] Positive proof: rollback of a legacy historical revision can still use the historical root gitlink entry when the old target revision predates tuple authority and contains that entry.
- [ ] Positive proof: rollback of a current lane-resolved revision succeeds only with `WEB_APP_RUNTIME_SHA` in the release tuple.
- [ ] Positive proof: workflow success-stamp still records only after all final-healthy gates pass, including remote web runtime SHA and provenance checks.
- [ ] CI-equivalent passes locally: `bash .github/scripts/verify_environment_ci.sh`.
- [ ] Relevant shell syntax passes: `bash -n` for every touched `.github/scripts/*.sh`.
- [ ] Promotion preflight evidence is rerun after implementation before lane promotion.

## Decision Baseline (Pending Approval Freeze)
- **D-01:** Remove `web-app` root gitlink/submodule state as runtime authority. Physical submodule removal is allowed only if all readiness/local-tooling paths are migrated in the same patch set; it is not required for the runtime contract.
- **D-02:** Branch refs are discovery only. Preflight resolves `origin/${DEPLOY_LANE}` to exactly one immutable `WEB_APP_RUNTIME_SHA`; deploy consumes that exact SHA and must not re-resolve the branch.
- **D-03:** Record and rollback by exact `WEB_APP_RUNTIME_SHA`; rollback must never float to a branch.
- **D-04:** Keep `flutter_git_sha`, `source_branch`, and lane host injection as hard gates for lane-resolved `stage` and `main` artifacts.
- **D-05:** Preserve Docker/root lane descendancy; do not create a main-only root repin solely for `web-app`.
- **D-06:** The web repo slug must come from the visible GitHub Actions repository variable `WEB_APP_REPO`; secrets are only for tokens, and `.gitmodules`/local checkout fallback is forbidden for protected runtime paths.
- **D-07:** Legacy rollback targets may use the historical root gitlink only when the target revision predates tuple authority `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha` / `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1` and itself contains the `web-app` tree entry. Current lane-resolved release tuples without `WEB_APP_RUNTIME_SHA` fail closed with an operator-visible error.
- **D-08:** Use one canonical resolver interface for web runtime SHA and metadata lookup. CI, preflight, deploy, rollback, workflow capture, and provenance checks must not duplicate divergent lane/SHA resolution logic.
- **D-09:** Compatibility predicate: the resolved web artifact must declare `source_branch == DEPLOY_LANE`, inject the host from `flutter-app/config/defines/${DEPLOY_LANE}.json`, and declare `flutter_git_sha` that is equal to the pinned/promoted `flutter-app` SHA or resolves as an ancestor of that pinned/promoted SHA via `git merge-base --is-ancestor <artifact_flutter_git_sha> <pinned_or_promoted_flutter_sha>` against fetched local Flutter history. Non-resolvable or non-ancestor metadata fails.
- **D-10:** Runtime authority migration and all script/invariant updates must ship atomically in one implementation commit or one uninterrupted patch set; no intermediate committed state may partially switch protected deploys between gitlink and lane-resolved authority.
- **D-11:** Existing final-healthy branch guarantees are preserved. This TODO may replace the source of expected web runtime SHA, but must not weaken success-mark gating, rollback-proof gating, public-edge/provenance/navigation/mutation/taxonomy gates, production mutation hard-blocking, or terminal degraded/incident fail-closed branches.
- **D-12:** This TODO is subordinate to `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`. It may close the current `web-app` runtime authority gap, but it must not claim complete final-healthy invariant closure or replace the structural immutable artifact rollback path tracked in vnext.
- **D-13:** Because the operator requires synchronized delivery and closure of the healthy-final-state TODO, this TODO is merged into the healthy-final-state execution contract together with the immutable Docker image artifact rollback work. A web-only implementation is insufficient for that closure claim.

## Assumptions Preview
| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| A-01 | `web-app` lane branches are the intended runtime artifact sources. | `web-app` publish branches exist for `stage` and `main`; current `web-app/main` injects `booraagora.com.br`, while current `web-app/stage` injects `belluga.app`. | Need a different runtime artifact resolver, likely artifact registry or release tag. | High | Keep as assumption pending audit. |
| A-02 | Docker/root descendancy must be preserved through `stage -> main`. | User explicitly rejected main-only root repin because it breaks descendancy. | Repin-based approach could be acceptable. | High | Promote to decision D-05. |
| A-03 | Rollback safety can be preserved by storing `WEB_APP_RUNTIME_SHA`. | Existing tuple already records `WEB_APP_RUNTIME_SHA`; rollback scripts already consume a SHA path, but still have gitlink fallbacks. | Need a separate release manifest or artifact lock. | Medium | Audit focus. |
| A-04 | Runtime authority can be removed from the gitlink without necessarily deleting the local checkout/submodule in the first patch. | User challenged full submodule removal because the lane still needs a repo source; `WEB_APP_REPO` as a visible variable provides that source. | If local tooling remains too ambiguous, physical submodule removal may be included only after all readiness paths are migrated. | High | Promote to decision D-01/D-06. |
| A-05 | `web-app` lane branches are protected against force-push and publish failures are visible before deploy. | Required for a lane-resolved artifact model; SHA pinning still protects deploy against ordinary branch movement after preflight. | A force-push could orphan the preflight SHA, causing deploy to fail closed. | Medium | Keep as assumption; verify by deploy fetch of exact SHA. |
| A-06 | GitHub repository variable `WEB_APP_REPO` is preferable to a secret for the repo slug. | Repo slug is non-sensitive and should be visible/maintainable; token remains secret. | If variable is unavailable, implementation must add a committed CI config instead of using a secret for slug authority. | High | Promote to decision D-06. |
| A-07 | The existing final-healthy CI gates must remain authoritative. | `verify_environment_ci.sh` currently asserts stage/main success marking requires initialized state, trusted tuple, exact remote web SHA, public-edge, provenance, and lane-specific smoke/validation gates; terminal fail-closed blocks cover web SHA/provenance/public-edge/rollback proof/navigation/mutation/taxonomy failures. | Weakening any of these would conflict with the prior CI health hardening objective. | High | Promote to decision D-11 and validation steps. |
| A-08 | `SUBMODULES_REPO_TOKEN` is the approved token for runtime web repo clone/fetch from protected deploy scripts. | Existing deploy scripts already require `SUBMODULES_REPO_TOKEN`; repo slug is non-secret via `WEB_APP_REPO`. | If token scope is insufficient, implementation must stop and request operator secret update, not fall back to a slug secret or unauthenticated access. | Medium | Keep as assumption and validate during protected fetch proof. |
| A-09 | Canonical docs must record the stable runtime authority rule. | `project_constitution.md` states stable cross-stack decisions must be promoted into canonical docs before a tactical TODO closes. | Leaving the rule only in the TODO creates future drift after archival. | High | Promote to scope and DoD. |
| A-10 | The fail-closed healthy-final-state TODO remains the parent safety invariant. | `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md` states no implementation slice may claim full invariant closure until the immutable-artifact rollback path exists. | This TODO could overclaim safety if it treats lane-resolved git SHA runtime as the whole immutable artifact architecture. | High | Promote to decision D-12 and closure notes. |
| A-11 | Synchronized closure requires more than web runtime SHA authority. | `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` requires immutable Docker `app/worker/scheduler` artifacts because current deploy/rollback rebuilds on the host. | Merging only this TODO would still conflict with the healthy-final-state blocker. | High | Promote to decision D-13 and merge plan. |

## Execution Plan (Draft)
1. Add/adjust deterministic proof harnesses for lane-resolved web artifact validation, preflight-to-deploy SHA immutability, and rollback tuple behavior.
2. Introduce/centralize helper logic for resolving the visible `WEB_APP_REPO` variable, lane branch, exact runtime SHA, metadata content, and index content.
3. Remove all protected runtime reads from `git ls-tree <root> web-app`, `.gitmodules`, local submodule config, and local `web-app` fallback paths.
4. Update workflow YAML to bind runtime repo slug from `${{ vars.WEB_APP_REPO }}` and pass `WEB_APP_REPO`, `DEPLOY_LANE`, and preflight `WEB_APP_RUNTIME_SHA` through explicit outputs/env.
5. Update metadata preflight to hard-gate `DEPLOY_LANE`, resolve `origin/${DEPLOY_LANE}` once, read `build_metadata.json` and `index.html` from that exact SHA, validate `source_branch`/host/Flutter SHA, and publish `WEB_APP_RUNTIME_SHA` as a workflow output/env for deploy.
6. Update protected deploy to validate/fetch/checkout the exact preflight `WEB_APP_RUNTIME_SHA` on the remote before runtime mutation, with bounded fetch/timeout/retry behavior.
7. Update success tuple capture, remote SHA validation, internal rollback, and explicit rollback to use recorded tuple authority keys and exact `WEB_APP_RUNTIME_SHA`.
8. Update workflow rollback-target capture so current lane-resolved tuples without runtime SHA/authority/version fail closed instead of deriving from removed gitlink authority; retain historical fallback only for target revisions that predate tuple authority and contain the old gitlink.
9. Update `verify_environment_ci.sh` invariants to ban root-gitlink runtime resolution, enforce `vars.WEB_APP_REPO`, require lane-resolved forward deploy plus SHA-exact rollback, and preserve final-healthy success/terminal-fail-closed gates.
10. Update canonical project documentation and operator/developer runbook notes for `WEB_APP_REPO`, runtime authority, tuple schema, validation, and compatibility with the parent fail-closed pipeline invariant.
11. Reconcile this scope into the healthy-final-state TODO before implementation approval.
12. Run local CI-equivalent and promotion preflight before requesting lane promotion.

## Test Strategy
- **Strategy:** test-first where feasible.
- **Fail-first targets:**
  - local proof that currently fails because `check_web_flutter_metadata.sh` depends on `git ls-tree HEAD web-app`
  - local proof that rollback tuple fallback still derives from `git ls-tree <root> web-app`
  - CI invariant that currently requires `git ls-tree HEAD web-app` in deploy scripts
  - proof that a lane branch movement between preflight and deploy does not change the deployed web runtime SHA
  - proof that final-healthy success-mark and terminal fail-closed invariants remain intact after replacing gitlink SHA authority
- **CI-equivalent matrix:** to be finalized after audit, but must include `bash .github/scripts/verify_environment_ci.sh` and targeted proof scripts wired into it.

## Audit Requirements
- External audit required before approval:
  - Elegance/architecture audit: validate removal of root submodule contract and lane resolver shape.
  - Performance/runtime audit: validate fetch/checkout behavior, branch/SHA resolution, and no unbounded or flaky network pattern.
  - Test-quality audit: validate negative/positive proof matrix and CI-equivalent coverage.
  - Claude CLI comparison: validate whether the TODO scope is sufficient and whether any blocker is missing.

## Audit Evidence And Integrated Findings
- Triple-audit package:
  - [package.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/package.md:1)
- Triple-audit session:
  - [session.json](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/triple-audit-20260522T130000Z/session.json:1)
- Triple-audit round summary:
  - [round-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/triple-audit-20260522T130000Z/round-01/round-summary.md:1)
- Claude CLI result:
  - [claude-cli-result.json](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/claude-cli-result.json:1)
- Reformulation audit summary:
  - [reformulation-audit-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/reformulation-audit-summary.md:1)
- Integrated blockers:
  - `ELEGANCE/PERF/TQ`: preflight must resolve one immutable `WEB_APP_RUNTIME_SHA`; deploy must consume it and never re-resolve branch tip.
  - `ELEGANCE/PERF/TQ`: rollback must use recorded SHA; root gitlink/branch fallbacks are forbidden for current lane-resolved releases.
  - `ELEGANCE`: resolution logic must be centralized to avoid old/new authority drift.
  - `ELEGANCE/TQ`: compatibility predicate must be explicit for `flutter_git_sha`, `source_branch`, and lane host.
  - `CLAUDE`: web repo slug must have a canonical source after protected runtime stops using `.gitmodules`/gitlink authority.
  - `CLAUDE`: deploy/rollback must explicitly clone/fetch `web-app` before checkout after runtime authority migration.
  - `CLAUDE`: `check_remote_web_runtime_sha_over_ssh.sh` must require `EXPECTED_WEB_APP_RUNTIME_SHA`; no local checkout fallback.
  - `CLAUDE`: workflow rollback-capture must fail closed when current lane-resolved tuple lacks `WEB_APP_RUNTIME_SHA`.
  - `CLAUDE`: implementation must be atomic to avoid committed intermediate broken topology.
  - `REFORM-AUDIT`: `WEB_APP_REPO` must be a visible repository variable bound via `${{ vars.WEB_APP_REPO }}`; workflow invariants must reject `${{ secrets.WEB_APP_REPO }}` for slug authority.
  - `REFORM-AUDIT`: `DEPLOY_LANE` must be hard-gated to `stage|main`.
  - `REFORM-AUDIT`: protected deploy must prove the exact preflight SHA is fetchable remotely before any runtime mutation.
  - `REFORM-AUDIT`: current release tuple must include authority/version markers so legacy gitlink fallback cannot apply to lane-resolved releases.
  - `REFORM-AUDIT`: validation must cover `stage` and `main` negative cases plus divergent local gitlink fallback regression.
  - `REFORM-AUDIT`: prior final-healthy CI guarantees must be preserved; this TODO replaces SHA authority source only, not success/rollback/fail-closed gates.

## Decision Pending
- [x] DP-01: Confirm whether `web-app` should be physically removed from root or only removed as runtime authority.
  - Resolution: remove root gitlink/submodule state as runtime authority now; physical submodule removal is optional and only allowed if all readiness/local-tooling paths are migrated atomically.
- [x] DP-02: Confirm whether forward deploy should resolve `origin/${DEPLOY_LANE}` directly or resolve branch tip to SHA during preflight and pass that SHA through deploy.
  - Resolution: resolve exact SHA during preflight and pass that SHA through deploy.
- [x] DP-03: Confirm whether legacy `.last_successful_revision` records without `WEB_APP_RUNTIME_SHA` should hard-fail rollback proof immediately or allow one-time migration handling.
  - Resolution: current lane-resolved releases hard-fail without `WEB_APP_RUNTIME_SHA`; legacy historical revisions may still use their committed gitlink entry as migration compatibility.
- [x] DP-04: Confirm canonical source for the `web-app` repository slug.
  - Resolution: use visible GitHub Actions repository variable `WEB_APP_REPO`; do not use a secret for the repo slug.

## Completion Evidence Matrix
| Criterion | Evidence Type | Command / Artifact | Status | Notes |
| --- | --- | --- | --- | --- |
| TODO externally audited before approval | triple audit + Claude CLI + reformulation audit | [round-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/triple-audit-20260522T130000Z/round-01/round-summary.md:1), [reformulation-audit-summary.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/web-app-lane-runtime-topology-audit-20260522/reformulation-audit-summary.md:1) | passed | Approval blockers were identified and integrated before `APROVADO`. |
| Audit blockers integrated into TODO contract | TODO update | this section + decisions D-06..D-13 | passed | External findings converted into scope, DoD, validation, decisions, assumptions, and execution plan before approval request. |
| Merge required for synchronized healthy-state closure | TODO reconciliation | `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md` + `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` | passed | Web runtime authority is now represented as the `WEB-*` sub-contract in the governing healthy-state matrix, but still cannot close independently. |

## References
- [.github/scripts/check_web_flutter_metadata.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/check_web_flutter_metadata.sh:1)
- [.github/scripts/deploy_stage_over_ssh.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/deploy_stage_over_ssh.sh:1)
- [.github/scripts/rollback_remote.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/rollback_remote.sh:1)
- [.github/scripts/check_remote_web_runtime_sha_over_ssh.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/check_remote_web_runtime_sha_over_ssh.sh:1)
- [.github/scripts/verify_environment_ci.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/verify_environment_ci.sh:1)
- [.github/workflows/orchestration-ci-cd.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/orchestration-ci-cd.yml:1)
- [TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md:1)
