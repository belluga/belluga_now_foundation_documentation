# TODO (Fast Follow): Fail-Closed Pipeline Healthy Final State

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Provisional. The synchronized healthy-state implementation reached `main` and the forward production proof is green, but this governing TODO is not closed yet because its merged evidence matrix still needs reconciliation and `todo_completion_guard.py --require-delivery` is currently `no-go`.
**Owners:** Delphi, DevOps/Platform
**Goal:** establish and then deliver a fail-closed CI/CD process where any failure on `dev`, `stage`, or `main` promotion flow preserves or restores the last known healthy version instead of leaving a mixed or degraded runtime serving traffic.
**Execution mandate:** implementation and promotion have reached `main` after explicit operator authorization. The remaining work is TODO-quality closure: reconcile row-level completion evidence, decide whether a live rollback drill is mandatory or explicitly non-applicable for this closure, and rerun the deterministic completion guard before moving this TODO to `completed`.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

- A recent `main` promotion failed and left a broken version running instead of preserving the prior healthy deployment.
- Existing rollback logic is already known to be revision-incoherent for `web-app` runtime content.
- Existing deploy/rollback logic still depends on host-local rebuilds during recovery, which means rollback itself can fail under disk/runtime pressure.
- Existing promotion/smoke flow can trigger rollback from post-deploy validation failure, but the current process does not yet prove the final runtime is healthy after rollback completes.
- `stage` is therefore the canonical proof field for real deploy/promotion behavior; local work must close logic and contract gaps before any `stage` proof cycle begins.

## Incident Evidence

- `2026-05-04`: production promotion incident documented in `TODO-post-release-docker-rollback-runtime-web-fidelity.md`; rollback left the just-promoted web runtime live instead of restoring the prior healthy runtime.
- `2026-05-15`: `stage` push run `25902136464` failed after mutation smoke and triggered external rollback. The rollback log shows:
  - rollback target root revision `582c3fe36781c51e469cec4db5739a560d808d63`;
  - `web-app` first checked out at target gitlink `621596f02be5f973f33afb4e965923f458b28a78`;
  - then overridden to lane head `eec7d733cde2936bf637ccb2f14fb053e9a265b5`;
  - workflow terminated with `ERROR: stage mutation navigation smoke failed; external rollback completed.`
- `2026-05-17`: production success run `25978450690` proves the successful-release record still stores only the root SHA (`.last_successful_revision` = `1ac66d8454b5b98b198e425ecaf6dfbdd44221ec`) even though forward deploy lane-overrides `web-app`.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `FF-PIPELINE-HEALTHY-FINAL-STATE`
- **Why this is the right current slice:** the immediate need is one bounded release-safety investigation and hardening contract: define the invariant, enumerate failure modes, run external audits, and decide the minimum architecture/process changes required before implementation proceeds.
- **Direct-to-TODO rationale:** the problem statement and risk are already concrete; the ambiguity is in the correct fail-closed architecture and execution order, not in product discovery.

## Contract Boundary

- This TODO defines **WHAT** must be true for the pipeline to be considered fail-closed.
- This TODO also owns the bounded investigation package, external audit loop, and resulting decision baseline.
- Child implementation can land in existing related TODOs or new follow-up TODOs, but no implementation path may contradict the invariant frozen here.

## Implementation Style Guardrail

- The implementation target is **simplification without functionality loss**.
- Fixes must prefer tightening or correcting existing workflow/script paths over adding parallel orchestration surfaces.
- Prefer deletion, consolidation, or narrower conditions before introducing new helpers, new state files, or new branches of control flow.
- Any new abstraction, metadata file, or operational step must justify itself by removing more complexity/risk than it introduces.
- Clean-code standard for this initiative:
  - smallest coherent change set that closes the named matrix gap;
  - explicit ownership and naming;
  - no duplicate proof paths when one shared contract can cover the same behavior;
  - no behavior-preserving refactor sprawl during safety work unless it directly reduces operational complexity.
- Matrix-evidence freshness rule:
  - passing matrix rows on candidate version `X` does **not** remain valid after a material workflow/script/runtime change required for version `X+1`;
  - any material change that can affect an already-passed row invalidates prior evidence for that row;
  - before claiming readiness, rerun the full applicable matrix against the current candidate state instead of relying on stale earlier passes.

## Simplified Implementation Blueprint

The preferred implementation shape for this initiative is:

1. **One trusted release tuple surface**
   - extend the current successful-release record instead of creating parallel rollback ledgers;
   - one canonical tuple schema shared by forward success-marking, rollback targeting, and post-rollback proof.
2. **One bootstrap-context signal**
   - derive bootstrap-vs-unexpected-`initialize=403` from the smallest reliable state signal available;
   - do not create multiple bootstrap flags unless the single-signal model proves insufficient.
3. **One shared proof contract**
   - reuse the same lane-aware probe/provenance/navigation proof model for forward success and rollback recovery;
   - prefer parameterized reuse over separate forward-vs-rollback proof implementations.
4. **One operator recovery entry point**
   - `CP-01` and similar mixed-state recovery cases must converge on one documented incident path, not lane-specific ad hoc recovery playbooks.
5. **One future immutable deploy contract**
   - Track 4 should converge to one coherent artifact identity / selection model per lane, not multiple catalogs or provenance ledgers.

## Delivery Status Canon

- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Fast-Follow`, `Docker`, `CI/CD`, `Release-Safety`, `Fail-Closed`, `Main-Incident-Driven`, `External-Audit-Required`
- **Next exact step:** reconcile the merged Completion Evidence Matrix and Local CI-Equivalent Suite Matrix, then rerun `python3 delphi-ai/tools/todo_completion_guard.py --require-delivery foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`; do not move this TODO to `completed` until it returns `Overall outcome: go`.
- **Evidence rule:** no `ready for promotion` claim is valid unless the matrix evidence was generated after the latest material CI/deploy/rollback change set in scope.

## Current Local Checkpoint

- Track 1 is materially wired locally:
  - successful-release record is now a release tuple;
  - rollback no longer falls back to `HEAD~1`;
  - rollback restores exact runtime `web-app` content instead of lane head.
- Track 2 is materially wired locally:
  - unexpected forward-path `initialize=403` on previously healthy `stage|main` lanes now fails closed;
  - bootstrap-only success remains gated to lanes without a trusted prior healthy tuple.
- Track 3 is materially wired locally:
  - both rollback executors now flow into explicit post-rollback proof paths;
  - rollback proof rejects untrusted targets and drifted internal rollback revisions;
  - degraded-state wording was hardened from best-effort ambiguity to explicit incident/degraded outcomes.
- Local `stage`-campaign preparation is now materially wired too:
  - `TENANT_DATABASE_PREFIX` exists for isolated destructive validation data;
  - the temporary `stage` landlord domain candidate is `https://belluga.online`.
- The next uncertainty is no longer architecture or local deterministic proof. It is real deploy evidence on the canonical `stage` proof field through the lane-owned promotion flow.
- `2026-05-18`: real `stage` run `26046088556` on the temporary `belluga.online` target confirmed two additional execution truths:
  - forward provenance still needs bounded retry tolerance for transient origin-probe timeouts;
  - rollback proof must reject restoration of a tuple produced under an older landlord-host contract (`belluga.app`) once the temporary `stage` campaign has moved the lane contract to `belluga.online`.
- `2026-05-18`: therefore the canonical sequencing is now explicit:
  - first obtain a fresh healthy trusted tuple on the temporary `belluga.online` `stage` target;
  - only then count destructive rollback-proof rows, because earlier trusted tuples are not semantically valid for that temporary-domain campaign.
- `2026-05-22`: operator direction changed the closure requirement: the audited `web-app` lane runtime topology fix must be delivered in sync with this TODO and must contribute to closing this TODO, not run as an independent approval/closure path. Reconciliation showed that a web-only fix is insufficient because the immutable Docker `app`, `worker`, and `scheduler` artifact rollback track is still the structural blocker for full healthy-final-state closure.
- `2026-05-22`: stage validation target was repointed for the upcoming proof cycle:
  - `STAGE_SSH_HOST=201.54.9.139`
  - `STAGE_NAV_LANDLORD_URL=https://belluga.online`
  - `STAGE_NAV_TENANT_URL=https://guarappari.belluga.online`
  - `STAGE_SSH_KNOWN_HOSTS` regenerated from `ssh-keyscan -p 22 201.54.9.139`
  - verification snapshot: TCP `22` open; direct origin `Host: belluga.online` `/api/v1/initialize` returned `200`; public edge `https://belluga.online/api/v1/initialize` returned `200`.
- `2026-05-22`: GitHub Actions configuration hygiene was tightened:
  - kept pinned SSH known-host material in secrets by operator decision and removed the competing repository variables: `STAGE_SSH_KNOWN_HOSTS`, `MAIN_SSH_KNOWN_HOSTS`;
  - moved non-sensitive stage navigation identity to visible variable `STAGE_NAV_ADMIN_EMAIL=admin@bellugasolutions.com.br` and deleted the stale repository secret with the same name;
  - recorded `SSH_KNOWN_HOSTS` as host-derived public trust material generated from `ssh-keyscan`, not an operator-chosen value; stage fingerprints pinned for `201.54.9.139`: RSA `SHA256:remAPLOLpep52r1XrN5IXuoYKOSx+Qf7/f2R8OoPewg`, ECDSA `SHA256:SpTZffLOVO1XLi/uND14vHcjK+/guqdLIMYVUFnZKJY`, ED25519 `SHA256:IPKXrD1LVX7ad5Yg0jJrkmgDivjgU4TZb3xVOdsl6Q8`;
  - kept sensitive values as secrets: `STAGE_NAV_ADMIN_PASSWORD`, SSH private keys, repository tokens, support secrets;
  - provisioned `WEB_APP_REPO=belluga/belluga_now_web` as visible variable for the upcoming lane-resolved web runtime authority work;
  - workflow and policy test were updated so non-sensitive navigation identity is consumed from `vars.*`, pinned known-host material remains in `secrets.*`, and `verify_environment_ci.sh` guards against regression to competing authority surfaces.
- `2026-05-22`: operator approved the frozen implementation baseline: `APROVADO com GHCR, nginx imutável e fallback zero`.
- `2026-05-22`: local implementation checkpoint after approval:
  - workflow now resolves protected `web-app` runtime from visible `WEB_APP_REPO` + lane, including the metadata compatibility gate; root `web-app` gitlink is no longer accepted as protected runtime authority;
  - protected deploy jobs publish immutable GHCR digest refs for the Laravel runtime image and the separate `nginx` image before remote mutation;
  - `docker-compose.yml` accepts digest-ref image inputs for `app`, `worker`, `scheduler`, and `nginx`, while local builds remain available outside protected deploy/rollback paths;
  - deploy, success-marker, and rollback scripts require complete release tuples and immutable `ghcr.io/*@sha256:*` refs, reject `:latest`, and run protected recovery through `docker compose up --no-build`;
  - rollback no longer falls back to `HEAD~1`, root `web-app` gitlink, branch tips, host-local rebuild, mutable tags, or incomplete legacy tuples for protected recovery authority;
  - `.dockerignore` was hardened so build context excludes local `.env`, composer cache, vendor/runtime cache paths, Flutter/web docs, and Git metadata; current Docker context observed in preflight is `6.37MB`.
- `2026-05-22`: promotion lane checkpoint:
  - Docker PR `#740` merged to `dev`; post-merge run `26304666033` passed.
  - Docker PR `#741` merged `dev -> stage`; stage SHA is `f3ae85b22cd9b6bd5c98a002967ea223542c50b3`.
  - Stage deploy run `26304853660` passed preflight and successfully executed the new `Publish stage runtime images to GHCR` step.
  - Stage deploy then failed before remote runtime mutation at `Resolve stage navigation targets` because `STAGE_NAV_LANDLORD_URL=https://belluga.online` conflicted with the checked lane artifact contract `flutter-app/config/defines/stage.json => https://belluga.app` and `web-app@stage` host `belluga.app`.
  - This was a correct fallback-zero hard gate; no deploy/rollback mutation was attempted after the mismatch.
- `2026-05-22`: the lane artifact/domain authority was then aligned for the temporary validation campaign and proved end-to-end:
  - Flutter/web `stage` runtime now intentionally serves `https://belluga.online` for the sandbox campaign;
  - Docker PR `#745` advanced `origin/stage` to `86d85e07b41ef16aaad8df375491a14cebb672a4`;
  - stage run `26311555749` passed deploy, provenance, readonly smoke, mutation smoke, and successful-release marking on `201.54.9.139`;
  - public proof confirmed `https://belluga.online` and `https://tenant.belluga.online` return the expected landlord/tenant environment payloads and deployed `build_metadata.json` reports `flutter_git_sha=ca74b3b7`, `source_branch=stage`.
- `2026-05-22`: historical run evidence identifies the real serving `stage` target before the sandbox repoint:
  - run `25902136464` on `2026-05-15` used `DEPLOY_SSH_HOST=169.150.1.122`;
  - the same run resolved real `stage` navigation targets as `https://belluga.app` and `https://guarappari.belluga.app`;
  - the current cutback must therefore restore artifacts and vars together; changing only one side would be an intentional hard-gate failure, not a valid proof attempt.
- `2026-05-22`: live real-stage probes confirm the historical target remains reachable and domain-coherent:
  - TCP `169.150.1.122:22` is open and keyscan fingerprints are RSA `SHA256:t4vuOL0nvns4xjZ/Ny1Wn6+LJ1e1Idzuipz3Ti0eka0`, ECDSA `SHA256:y564xgU6+RxWNZ8OTwhogEcc4D9xsfEXPHryDnbj6yI`, ED25519 `SHA256:YZd2fkQavzur5GFzKBLIsExHSBesGTg6K5jbYxiDc9I`;
  - `https://belluga.app/api/v1/environment` returns landlord `main_domain=https://belluga.app`;
  - `https://guarappari.belluga.app/api/v1/environment` returns tenant `main_domain=https://guarappari.belluga.app`;
  - the real host currently serves prior web build `1b780c30` with `window.__LANDLORD_HOST__=belluga.app`, while current `origin/stage` artifacts are the sandbox build `ca74b3b7` with `window.__LANDLORD_HOST__=belluga.online`.
- `2026-05-22`: real `stage` cutback and proof are now complete on the synchronized healthy-state candidate:
  - Flutter `stage` restored the real landlord domain and published derived `web-app@stage` metadata `flutter_git_sha=135a13e9`, `source_branch=stage`;
  - Docker stage variables were restored to the real target: `STAGE_SSH_HOST=169.150.1.122`, `STAGE_NAV_LANDLORD_URL=https://belluga.app`, `STAGE_NAV_TENANT_URL=https://guarappari.belluga.app`, `STAGE_NAV_ADMIN_EMAIL=admin@bellugasolutions.com.br`, and `STAGE_SSH_KNOWN_HOSTS` was regenerated for the real host;
  - stale/invalid `bot/next-version` was deleted and regenerated through the promotion-lane workflow; final bot diff was `flutter-app` gitlink only;
  - Docker PRs `#746` (`bot/next-version -> dev`), `#747` (topology-only `dev` contains `stage`), and `#748` (`dev -> stage`) completed successfully;
  - stage run `26315736532` completed successfully with preflight, immutable GHCR runtime image publication, deploy over SSH, exact deployed web runtime SHA check, public-edge probes, deployed web provenance, readonly navigation smoke, mutation navigation smoke, and successful-release marking;
  - real public proof now shows `https://belluga.app/build_metadata.json` and `https://guarappari.belluga.app/build_metadata.json` returning `flutter_git_sha=135a13e9`, `source_branch=stage`, with `window.__LANDLORD_HOST__="belluga.app"`;
  - `github_promotion_completion_guard.sh --lane stage --scenario flutter-only --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front` returned `Overall outcome: go`.
- `2026-05-22`: Docker PR `#737` completed `stage -> main` after healthy final-state hardening; merge commit `0c8e3527f420597adbc83df3bf917075e95599de`.
- `2026-05-23`: Docker PRs `#749`, `#750`, and `#751` delivered the first trusted tuple bootstrap follow-up through `dev -> stage -> main`; production run `26320227463` passed preflight, production deploy, exact runtime SHA validation, public-edge/provenance, main mutation hard-block, readonly navigation smoke, and successful-release marking.
- `2026-05-23`: TODO closure guard for this governing TODO remains `no-go` because this document still lacks row-level completion evidence for every merged DoD/validation item and still has unresolved rollback-drill/non-applicability evidence. This is a TODO documentation/evidence blocker, not a current production deploy failure.

## References

- [foundation_documentation/todos/active/fast_follow_required/TODO-post-release-docker-rollback-runtime-web-fidelity.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-post-release-docker-rollback-runtime-web-fidelity.md)
- [foundation_documentation/todos/active/fast_follow_required/TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md)
- [foundation_documentation/todos/active/vnext/TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/vnext/TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md)
- [foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-web-app-lane-runtime-topology.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-web-app-lane-runtime-topology.md)
- [foundation_documentation/todos/active/vnext/TODO-vnext-deploy-pre-migration-backup.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/active/vnext/TODO-vnext-deploy-pre-migration-backup.md)
- [foundation_documentation/artifacts/tmp/post-release-deploy-rollback-safety-claude-review-20260514.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/artifacts/tmp/post-release-deploy-rollback-safety-claude-review-20260514.md)
- [foundation_documentation/todos/completed/TODO-devops-single-gate-lane-promotion.md](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/foundation_documentation/todos/completed/TODO-devops-single-gate-lane-promotion.md)
- [.github/workflows/orchestration-ci-cd.yml](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/workflows/orchestration-ci-cd.yml)
- [.github/scripts/deploy_stage_over_ssh.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/deploy_stage_over_ssh.sh)
- [.github/scripts/rollback_over_ssh.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/rollback_over_ssh.sh)
- [.github/scripts/handle_source_promotion_status_callback.sh](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/.github/scripts/handle_source_promotion_status_callback.sh)

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/system_architecture_principles.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:**
  - `system_architecture_principles.md`

## Scope

- [x] Freeze the governing invariant for pipeline safety: after any failure or rollback path, the final serving runtime must remain or become the last known healthy version.
- [x] Build a complete failure-scenario matrix across source promotion, deploy preflight, migration, runtime mutation, provenance, readonly smoke, mutation smoke, rollback, and post-rollback verification.
- [x] Run a deep no-context external audit loop on the current pipeline design using:
  - triple external audit (`elegance`, `performance`, `test-quality`)
  - ClaudeCLI independent critique
- [x] Decide whether the minimum acceptable fix is achievable inside the current model or whether immutable artifacts / stronger deployment topology are mandatory.
- [x] Define the mandatory post-failure verification contract, including what must be rerun after rollback before the pipeline can claim the environment is healthy.
- [x] Classify the existing related TODOs into:
  - immediate tactical fixes
  - structural/vnext prerequisites
  - newly required follow-up slices if the current TODO set is incomplete

## Out of Scope

- [ ] Shipping the entire architecture refactor in the same slice as the investigation.
- [ ] Reopening product behavior outside deployment/promotion/runtime safety.
- [ ] Accepting “rollback exited 0” as sufficient evidence of safety without runtime-health proof.

## Invariant To Freeze

- [ ] `I-01` A failed deploy must not leave a newer broken version serving traffic.
- [ ] `I-02` A rollback must not produce a mixed revision/runtime state.
- [ ] `I-03` A rollback that cannot prove a healthy final runtime is itself a failure condition.
- [ ] `I-04` Post-deploy smoke/probe failure handling must preserve the previous healthy version, not merely attempt best-effort recovery.
- [ ] `I-05` This invariant applies to any promotion branch flow that can mutate a serving environment, not only `main`.

## Definition of Done

- [x] A canonical failure-scenario matrix exists with exact trigger -> current behavior -> healthy expected result -> required guard/remediation.
- [x] Triple external audit findings are recorded and adjudicated.
- [x] ClaudeCLI critique is recorded and integrated into the decision baseline.
- [x] A frozen decision baseline states the minimum fail-closed architecture/process needed to guarantee healthy final state.
- [x] The relationship between this umbrella TODO and the narrower rollback/artifact/backup TODOs is explicit.
- [ ] External auditors confirm the matrix covers every current workflow path that can fail or mutate a serving environment.
- [ ] The next implementation slices are unambiguous enough to execute without reopening the core invariant debate.
- [ ] The synchronized `web-app` runtime authority and Docker immutable artifact rollback matrix is integrated into this governing TODO.
- [ ] The merged plan explicitly proves that neither the `web-app` sub-contract nor the immutable Docker artifact track can close independently while this healthy-state TODO remains open.
- [ ] The current `nginx` build surface is either included in the immutable rollback path or explicitly waived by audit-backed rationale proving it cannot block recovery.

## Validation Steps

- [x] `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md --json-output <artifact>`
- [x] Run the bounded triple external audit session and record merge/resolution artifacts.
- [x] Run ClaudeCLI critique against the same bounded package.
- [x] Verify the final decision set is consistent with current code and existing active deploy/rollback TODOs.
- [x] Run the merged synchronized matrix through external audit before `APROVADO`.
- [x] Resolve the merged synchronized matrix audit blockers before `APROVADO`.
- [ ] Verify CI-equivalent coverage maps every merged matrix row to a local command or an explicit real-lane promotion proof.
- [ ] Verify the completion guard accepts the merged TODO contract before any `promotion_lane` or closure claim.

## Execution Lane Tracking

- **Primary execution profile:** `Operational / DevOps`
- **Active technical scope:** `docker`
- **Implementation repos expected:** `belluga_now_docker` (primary), `flutter-app` and `laravel-app` only if remediation changes the promotion/runtime contract.
- **Promotion lane path for eventual implementation:** `dev -> stage -> main`

## Related TODO Mapping

- **Immediate tactical repair candidate:**
  - `TODO-post-release-docker-rollback-runtime-web-fidelity.md`
- **Immediate tactical follow-up required by this investigation:**
  - `TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md`
  - `TODO-post-release-rollback-final-state-verification-and-degraded-state-escalation.md`
- **Structural/vnext tracks already open:**
  - `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`
  - `TODO-vnext-deploy-pre-migration-backup.md`
- **Merged current execution sub-contract:**
  - `TODO-fast-follow-web-app-lane-runtime-topology.md` is the audited web runtime authority sub-contract. It is not an independent implementation approval candidate while this TODO is being closed.
- **Promoted structural blocker for synchronized closure:**
  - `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` must be promoted from deferred/vnext planning into the synchronized healthy-state closure scope if this TODO is to close now.
- **Gap resolution:** the previous TODO set was insufficient; the new explicit post-rollback health-verification slice is mandatory.

## Implementation Sequencing Note

- Tracks 1-3 are immediate safety hardening slices that reduce exposure inside the current host-rebuild model.
- `TODO-post-release-docker-rollback-runtime-web-fidelity.md` must land before or concurrently with `TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md`; otherwise the hardened forward gate can increase rollback invocation frequency while rollback target selection and runtime-web restoration are still defective.
- `TODO-fast-follow-web-app-lane-runtime-topology.md` must be merged as the Track 4 web-runtime authority slice: it removes root `web-app` gitlink authority, resolves `web-app` by lane through visible `WEB_APP_REPO`, records exact `WEB_APP_RUNTIME_SHA`, and preserves final-healthy gates.
- `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` is no longer optional/deferred if this TODO is to close in the same delivery. It is the Docker image artifact slice: `app`, `worker`, and `scheduler` rollback must restore immutable promoted artifacts rather than rebuilding from source on the target host.
- No implementation slice may claim complete closure of the invariant frozen here until both the web runtime authority slice and the Docker image immutable artifact rollback slice are implemented, validated, and promoted together.

## Merged Execution Scope Matrix

This is the governing execution matrix for the synchronized closure requested on `2026-05-22`. It supersedes any independent approval path in the `web-app` sub-contract or the previous vnext-only artifact track.

| Track | Source Contract | Primary Surfaces | Required Outcome | Blocks `APROVADO` If Missing | Blocks Closure If Missing |
| --- | --- | --- | --- | --- | --- |
| `HFS-01` final healthy gates | this TODO, Tracks 1-3 | `.github/workflows/orchestration-ci-cd.yml`, `mark_successful_revision_over_ssh.sh`, rollback proof steps | Existing initialized/trusted-tuple/public-edge/provenance/navigation/mutation/taxonomy/main-hard-block gates remain intact. | yes | yes |
| `WEB-01` web repo authority | `TODO-fast-follow-web-app-lane-runtime-topology.md` | workflow env/outputs, `check_web_flutter_metadata.sh`, deploy/rollback/provenance scripts | Protected runtime resolves `web-app` through visible `WEB_APP_REPO` + `DEPLOY_LANE`, not root gitlink or `.gitmodules`. | yes | yes |
| `WEB-02` web SHA immutability | `TODO-fast-follow-web-app-lane-runtime-topology.md` | preflight, deploy, rollback, `.last_successful_revision` tuple | Preflight resolves one `WEB_APP_RUNTIME_SHA`; deploy and rollback consume that exact SHA and never float to branch tip. | yes | yes |
| `IMG-01` Docker image identity | promoted `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` | `docker-compose.yml`, deploy/rollback scripts, image build/publish path | `app`, `worker`, and `scheduler` consume immutable GHCR image digest references recorded before serving mutation. | yes | yes |
| `IMG-02` rollback without rebuild | promoted `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md` | `deploy_stage_over_ssh.sh`, `rollback_remote.sh`, `rollback_over_ssh.sh` | Rollback restores recorded immutable image digest references; rollback does not run `docker compose build` for required runtime services. | yes | yes |
| `IMG-03` `nginx` build surface | discovered current code surface | `docker-compose.yml`, `docker/nginx/Dockerfile`, deploy/rollback scripts | `nginx` is included in the immutable GHCR artifact path and release tuple; no waiver is used. | yes | yes |
| `MAN-01` unified release manifest/tuple | this TODO + merged tracks | successful-release marker, rollback target capture, remote diagnostics | Release state records root SHA, web SHA, Docker image identities, lane, authority/version markers, and recorded timestamp. | yes | yes |
| `PROOF-01` forward and rollback proof | this TODO | public-edge/provenance/navigation/mutation gates for `stage|main` | Forward success and rollback recovery use the same lane-appropriate proof contract before success is accepted. | yes | yes |
| `DOC-01` canonical rule promotion | constitution/module docs | `project_constitution.md`, module docs or runbook notes | Stable runtime authority and artifact rollback decisions are promoted out of tactical TODO-only memory before closure. | no | yes |

## Runtime Artifact Authority Matrix

| Runtime Component | Forward Deploy Authority | Rollback Authority | Forbidden Fallbacks | Required Tuple / Manifest Fields |
| --- | --- | --- | --- | --- |
| Root orchestration | promoted root SHA from lane flow | last known healthy `ROOT_SHA` | `HEAD~1`, host current checkout, branch re-resolution after target selection | `ROOT_SHA`, `DEPLOY_LANE`, `RECORDED_AT` |
| `web-app` runtime | exact `WEB_APP_RUNTIME_SHA` resolved from `${{ vars.WEB_APP_REPO }}` + `origin/${DEPLOY_LANE}` during preflight | recorded `WEB_APP_RUNTIME_SHA` | `.gitmodules`, root gitlink, local `web-app`, `git ls-tree <root> web-app`, branch tip during deploy/rollback | `WEB_APP_RUNTIME_SHA`, `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha`, `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1` |
| Laravel `app` image | immutable GHCR digest reference produced before remote serving mutation | recorded last-known-good GHCR digest reference | host `docker compose build` during deploy/rollback, mutable `latest`, host source rebuild as image authority | `APP_IMAGE`, `APP_IMAGE_DIGEST`, `IMAGE_AUTHORITY=ghcr-digest-v1` |
| Laravel `worker` image | same immutable GHCR digest reference as `app`, recorded independently for consumer clarity | recorded last-known-good GHCR digest reference | host `docker compose build` during deploy/rollback, mutable `latest`, implicit reuse without recorded identity | `WORKER_IMAGE`, `WORKER_IMAGE_DIGEST`, `IMAGE_AUTHORITY=ghcr-digest-v1` |
| Laravel `scheduler` image | same immutable GHCR digest reference as `app`, recorded independently for consumer clarity | recorded last-known-good GHCR digest reference | host `docker compose build` during deploy/rollback, mutable `latest`, implicit reuse without recorded identity | `SCHEDULER_IMAGE`, `SCHEDULER_IMAGE_DIGEST`, `IMAGE_AUTHORITY=ghcr-digest-v1` |
| `nginx` image | immutable GHCR digest reference produced before remote serving mutation | recorded last-known-good GHCR digest reference | rollback-time build, waiver, mutable `latest` | `NGINX_IMAGE`, `NGINX_IMAGE_DIGEST`, `IMAGE_AUTHORITY=ghcr-digest-v1` |
| Lane config/domain | lane-specific GitHub env/secrets/variables and checked-in defines | prior healthy lane tuple plus current lane config compatibility checks | implicit fallback domains, stale host injection, unvalidated env defaults | `DEPLOY_LANE`, domain/provenance proof outputs |

## Failure Closure Matrix

| Failure Family | Mutation Reached? | Required Terminal State | Required Recovery Mechanism | Evidence Required Before Closure |
| --- | --- | --- | --- | --- |
| PR/preflight/control-plane failure before deploy eligibility | no | `T0 no-runtime-mutation` | fail before remote mutation | CI log proves no deploy job/runtime mutation ran |
| Remote failure before serving cutover | no | `T0 no-runtime-mutation` | hard runtime-mutation marker boundary | deploy script evidence distinguishes pre-cutover from post-cutover failure |
| Forward deploy failure after serving mutation on previously healthy lane | yes | `T2 previous-healthy-version-proven` or `T3 explicit-incident-degraded` | rollback by recorded root/web/image tuple plus proof | rollback proof runs lane public-edge/provenance/navigation/mutation or main hard-block gates |
| External proof failure after deploy success | yes | `T2` or `T3` | external rollback by recorded tuple/manifest | workflow triggers rollback and does not accept success marker before proof |
| Rollback restore fails | yes | `T3 explicit-incident-degraded` | no pseudo-success; diagnostics captured | CI fails with incident wording and diagnostics artifact |
| Rollback restore exits zero but proof fails | yes | `T3 explicit-incident-degraded` | proof failure remains terminal failure | post-rollback proof failure blocks recovery claim |
| First deploy/no previous healthy tuple | yes | `T4 bootstrap-only-non-serving` only when explicit bootstrap contract applies, otherwise `T3` | bootstrap gate, never inferred from skipped smoke | bootstrap branch proves no prior healthy tuple and non-serving allowance |
| Cancellation after mutation | yes | `T3` unless successor run proves `T1` or `T2` | successor-run handoff or incident escalation | cancellation path modeled and documented in CI evidence |
| Stale tuple/domain/runtime-authority mismatch | yes or no | `T3` until a fresh trusted tuple is established | reject stale tuple and require fresh branch-appropriate proof | rollback/provenance checks reject mismatched domain, web SHA, image identity, or authority version |

## Local CI-Equivalent Suite Matrix

Every row marked `required before local delivery` must pass on the candidate version after the last material workflow/script/runtime change. Targeted reruns are diagnostic only.

| CI / Proof Surface | Why In Scope | Local Equivalent Command | Required Timing | Status |
| --- | --- | --- | --- | --- |
| Docker orchestration invariant guard | Main CI calls this in preflight and it owns final-healthy invariants. | `bash .github/scripts/verify_environment_ci.sh` | required before local delivery and promotion | passed `2026-05-22`: `OK: CI environment invariants validated.` |
| Shell syntax for touched runtime scripts | Deploy/rollback correctness depends on shell scripts. | `find .github/scripts -name '*.sh' -print0 \| xargs -0 -n1 bash -n` | required before local delivery | passed `2026-05-22`: touched script `bash -n` set returned `0`. |
| Lane web metadata compatibility | Web runtime authority change must preserve `flutter_git_sha`, `source_branch`, and host gates. | `bash .github/scripts/check_web_flutter_metadata.sh stage` and `bash .github/scripts/check_web_flutter_metadata.sh main` with the new resolver inputs configured | required before local delivery | passed `2026-05-22`: `stage` resolved `belluga/belluga_now_web@40cdc57e5a6a5420a295a0077d4ec9ea610831d4` with host `belluga.app`; `main` resolved `a16dd8c5c06d0a5caa2ac0893c5d56e006e96ca4` with host `booraagora.com.br`. |
| Runtime build/artifact preflight | Current CI validates runtime builds; synchronized work changes artifact/build semantics. | `bash .github/scripts/preflight_promotion_runtime_builds.sh stage` and `bash .github/scripts/preflight_promotion_runtime_builds.sh main`; deploy jobs publish immutable GHCR digests via `.github/scripts/publish_runtime_images_to_ghcr.sh` | required before local delivery | passed `2026-05-22`: stage and main preflight built Laravel `runtime-deps`, final Laravel runtime candidate, and `nginx`; context observed at `6.37MB`. |
| Negative fallback-zero guard | Approved baseline forbids branch/gitlink/local-checkout/mutable-tag/host-build fallback for protected runtime authority. | `bash .github/scripts/verify_environment_ci.sh` plus shell syntax checks over touched scripts | required before local delivery | passed `2026-05-22`: guard rejects root `web-app` gitlink authority, mutable branch content loads, rollback-time `docker compose build`, mutable `latest`, and incomplete tuple/image authority. |
| Web provenance check contract | Success and rollback proof depend on served metadata matching the recorded runtime artifact. | `bash .github/scripts/check_deployed_web_provenance.sh stage` / `main` during real lane proof | required during promotion proof, not purely local | passed in stage run `26319685277` and main run `26320227463`; both deploy jobs ran deployed web provenance before accepting success-marker. |
| Stage forward real proof | `stage` is the mutation proof field. | GitHub `Deploy Stage` run: public-edge probe, provenance, readonly smoke, mutation smoke, success marker | required before promoting beyond `stage` | passed on real stage run `26319685277`, including deploy, runtime SHA validation, public-edge probe, provenance, readonly smoke, mutation smoke, and success-marker. |
| Stage rollback real proof | Healthy-state closure requires rollback proof, not only forward success. | Induced or natural rollback path on `stage` followed by public-edge, provenance, readonly smoke, mutation smoke | required before closure | pending reconciliation: no rollback was triggered in the green stage/main runs; document must either add a real rollback drill/proof or record an explicit approved non-applicability decision before completion. |
| Main forward proof | `main` must remain read-only/mutation-hard-blocked. | GitHub `Deploy Production` run: public-edge probe, provenance, readonly smoke, mutation hard-block | required only when user authorizes main promotion | passed in production run `26320227463`, including public-edge/provenance, main mutation hard-block, readonly navigation smoke, and success-marker. |
| Completion guard | TODO cannot move to promotion/complete without deterministic guard acceptance. | `python3 delphi-ai/tools/todo_completion_guard.py --require-delivery foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md` | required before promotion-lane or closure claim | blocked on `2026-05-23`; guard reports missing Completion Evidence Matrix rows and unresolved checklist/evidence items. |

## Flow Evidence Planning Matrix

| Criterion | Flow Impact Reason | Platform / Lane | Mutation Requirement | Real Backend Required | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- |
| Forward `stage` deploy remains healthy | Live tenant-public/admin runtime can be affected by failed deploy. | browser-facing `stage` | yes | yes | public-edge probe + web provenance + `tools/flutter/run_web_navigation_smoke.sh readonly` + `tools/flutter/run_web_navigation_smoke.sh mutation` in CI | n/a |
| `stage` rollback restores prior healthy runtime | Recovery path must prove user-visible runtime after failure. | browser-facing `stage` | yes | yes | post-rollback public-edge probe + restored web SHA + provenance + readonly + mutation smoke | n/a |
| `main` deploy remains healthy | Production runtime must not serve a broken mixed release. | browser-facing `main` | no mutation allowed | yes | public-edge probe + provenance + readonly smoke + mutation hard-block assertion | n/a |
| `main` rollback restores prior healthy runtime | Production rollback must prove restored read-only surface and mutation block. | browser-facing `main` | no mutation allowed | yes | post-rollback public-edge probe + restored web SHA + provenance + readonly smoke + mutation hard-block assertion | n/a |
| Docker artifact rollback avoids host rebuild | Recovery behavior is runtime-infra, not user UI, but directly controls live availability. | `stage` first, `main` after authorization | depends on lane | yes | deploy/rollback logs prove recorded image digest restore and absence of rollback-time `docker compose build` for required runtime services | No separate app UI test can prove image identity; logs + live proof are required together. |

## Frontend / Consumer Matrix

| Producer / Contract Surface | Expected Consumer | Visible Route / Action | DTO / Runtime Boundary | Planned Render / Discoverability Evidence | Planned Request / Readback Evidence | Waiver |
| --- | --- | --- | --- | --- | --- | --- |
| Served `web-app` artifact metadata | Browser/web runtime, CI provenance | tenant public/admin browser entrypoints | `build_metadata.json`, `index.html`, provenance script | Playwright readonly/mutation smoke on lane domain | `check_deployed_web_provenance.sh` and remote web SHA check | none |
| Laravel `app` container image | Flutter app, web runtime, API clients | API-backed tenant/public/admin flows | Docker image digest + Laravel source SHA | public-edge/API readiness and navigation smoke | deploy/rollback logs + health/provenance gates | none |
| Laravel `worker` image | async jobs, push/scheduler adjacent runtime | background processing, no direct route | Docker image digest + queue worker command | service status in deploy/rollback diagnostics | container identity/log evidence | UI route n/a because this is background runtime |
| Laravel `scheduler` image | scheduled jobs | background scheduler, no direct route | Docker image digest + scheduler command | service status in deploy/rollback diagnostics | container identity/log evidence | UI route n/a because this is background runtime |
| `nginx` image | browser/web/API ingress | all public HTTP routes | Immutable GHCR image digest | public-edge probe and navigation smoke | container identity/log evidence | none; `nginx` is immutable in the approved baseline |
| `WEB_APP_REPO` repository variable | GitHub Actions operator/config | no user route | workflow variable lookup | CI invariant proves `${{ vars.WEB_APP_REPO }}` use | preflight failure evidence for absent/malformed value | no frontend consumer |

## Approval And Closure Gate Matrix

| Gate | Required Before `APROVADO` | Required Before Local Delivery | Required Before `stage` Promotion | Required Before Closure |
| --- | --- | --- | --- | --- |
| Merged matrix present in governing TODO | yes | yes | yes | yes |
| External audit on merged matrix | yes | n/a | n/a | yes |
| Open `nginx` build-surface decision resolved or explicitly blocked | yes | yes | yes | yes |
| No code implementation started before approval | yes | n/a | n/a | n/a |
| Local CI-equivalent matrix passes after final material change | no | yes | yes | yes |
| Stage forward proof passes on candidate version | no | no | yes | yes |
| Stage rollback proof passes on candidate version | no | no | yes | yes |
| Main proof | no | no | no, unless user authorizes main | yes only for main closure |
| Canonical docs updated for stable runtime authority decisions | no | before TODO closure if decisions stabilize | before final closure if promotion claims depend on them | yes |

## Pending Decision Checks Before `APROVADO`

- `PDC-01`: frozen by operator approval. Minimal immutable image topology is one Laravel runtime image reused by `app`, `worker`, and `scheduler`, plus a separate immutable `nginx` image.
- `PDC-02`: frozen by operator approval. `GHCR` is the canonical registry/namespace authority for protected runtime artifacts; absence of publish/pull credentials or digest output is a hard fail.
- `PDC-03`: frozen by operator approval. The existing `.last_successful_revision` tuple is extended; no parallel rollback ledger is introduced.
- `PDC-04`: frozen by operator approval. Current protected `stage|main` deploy/rollback paths use fallback zero: missing web/image/domain/lane authority aborts instead of falling back to branch tips, gitlinks, local checkout state, mutable tags, `HEAD~1`, or host-local `docker compose build`.

## Decision Baseline (Frozen After Operator Approval)

- `AGR-01`: immutable image topology is frozen as:
  - Laravel runtime image: one GHCR digest reference consumed by `app`, `worker`, and `scheduler`;
  - `nginx` runtime image: one separate GHCR digest reference consumed by `nginx`;
  - root source checkout and `web-app` runtime SHA remain separate tuple fields because current compose mounts source and web content at runtime.
- `AGR-02`: artifact authority is GHCR. Canonical image names are derived from `github.repository` and must resolve to digest references, not mutable tags. The deploy tuple records both the full digest references and bare digest values.
- `AGR-03`: `nginx` is an immutable artifact. No waiver is accepted for this TODO.
- `AGR-04`: the release tuple schema extends `.last_successful_revision` with required protected-lane fields:
  - `ROOT_SHA`
  - `WEB_APP_RUNTIME_SHA`
  - `WEB_APP_RUNTIME_AUTHORITY=lane-resolved-sha`
  - `RUNTIME_TOPOLOGY_VERSION=web-app-lane-sha-v1`
  - `DEPLOY_LANE`
  - `APP_IMAGE`, `APP_IMAGE_DIGEST`
  - `WORKER_IMAGE`, `WORKER_IMAGE_DIGEST`
  - `SCHEDULER_IMAGE`, `SCHEDULER_IMAGE_DIGEST`
  - `NGINX_IMAGE`, `NGINX_IMAGE_DIGEST`
  - `IMAGE_AUTHORITY=ghcr-digest-v1`
  - `RECORDED_AT`
- `AGR-05`: protected `stage|main` rollback requires a complete tuple. Legacy root-only or root+web-only markers may identify historical state for diagnostics, but they are not trusted rollback authority for the healthy flow.
- `AGR-06`: immutable-artifact preflight is two-layered:
  - PR/preflight keeps local Docker build validation for Laravel runtime and `nginx`;
  - protected deploy jobs publish GHCR images before remote mutation and pass only digest references to remote deploy/rollback scripts.
- `AGR-07`: negative fail-closed evidence is mandatory in `verify_environment_ci.sh`: it must reject protected rollback-time `docker compose build`, mutable `latest` image authority, missing tuple image markers, and regression to gitlink/branch fallback for protected web runtime.
- `AGR-08`: the protected web runtime resolver replaces root gitlink authority. `WEB_APP_REPO + DEPLOY_LANE` resolves one `WEB_APP_RUNTIME_SHA` before remote deploy; deploy, success-marking, rollback, and proof consume the recorded SHA.

## Merged Matrix Audit Round 01

- **Audit package:** `foundation_documentation/artifacts/tmp/healthy-final-state-merged-matrix-audit-20260522/package.md`
- **Triple audit session:** `foundation_documentation/artifacts/tmp/healthy-final-state-merged-matrix-audit-20260522/triple-audit-20260522T165753Z/session.json`
- **Round summary:** `foundation_documentation/artifacts/tmp/healthy-final-state-merged-matrix-audit-20260522/triple-audit-20260522T165753Z/round-01/round-summary.md`
- **Resolution artifact:** `foundation_documentation/artifacts/tmp/healthy-final-state-merged-matrix-audit-20260522/triple-audit-20260522T165753Z/round-01/resolution.md`
- **Claude CLI result:** `foundation_documentation/artifacts/tmp/healthy-final-state-merged-matrix-audit-20260522/claude-cli-result.json`
- **Round result:** `blocked before APROVADO`.
- **Adjudication:** the runner classified the round as `needs_adjudication` because reviewer recommended-path wording differed, but Delphi adjudication found the recommendations additive, not contradictory. Elegance, Performance, Test Quality, and Claude CLI all converged on the same blocker class: the matrix is directionally correct but not approval-ready until core decisions become frozen contract.

| Finding Family | Sources | Blocking Decision |
| --- | --- | --- |
| Architecture decisions still pending | `ELEG-01`, `PERF-001`, `TQ-HFS-02`, `BLK-01` | `PDC-01` and `PDC-02` must be converted from pending checks into frozen contract decisions before `APROVADO`. |
| `nginx` artifact surface unresolved | `ELEG-02`, `PERF-001`, `TQ-HFS-02`, `BLK-01` | Because current deploy/rollback builds `nginx`, approval must either include immutable `nginx` image identity or record an explicit waiver proving rollback no longer rebuilds or depends on it. |
| Manifest/schema unresolved | `PERF-002`, `TQ-HFS-01`, `BLK-04` | `MAN-01` must define a concrete parsed tuple/manifest schema, required fields, authority/version markers, atomic write timing, and readback/compare semantics. |
| Legacy/current fail-closed behavior unresolved | `ELEG-03`, `PERF-003`, `BLK-02` | Missing required web/image/nginx-or-waiver/lane/authority markers must abort deploy/rollback with no fallback to `HEAD`, branch tips, gitlinks, `.gitmodules`, local checkout state, mutable tags, or `docker compose build`. |
| Immutable-artifact preflight underspecified | `PERF-004`, `TQ-HFS-03` | The existing build preflight is insufficient; the approved plan must define the immutable-artifact preflight or exact replacement criteria before implementation. |
| Negative fail-closed evidence missing | `TQ-HFS-03` | CI-equivalent must include negative tests for missing web authority, floating branch fallback, missing image identity, mutable tag use, stale host state, and legacy tuples without recorded authority. |
| Web authority implementation surface ambiguous | `BLK-03` | The TODO must cite current web resolution code paths or explicitly state `WEB-01/WEB-02` is net-new and identify old gitlink-based paths to remove. |

### Audit-Gated Contract Resolutions Required Before `APROVADO`

- `AGR-01`: freeze the immutable image topology.
- `AGR-02`: freeze the artifact registry/namespace and image naming/digest authority.
- `AGR-03`: freeze whether `nginx` is an immutable artifact or record an explicit waiver.
- `AGR-04`: freeze the release tuple/manifest schema with exact required fields and authority/version markers.
- `AGR-05`: freeze missing-authority and legacy rollback behavior as fail-closed, with no fallback to mutable or host-local authority.
- `AGR-06`: freeze immutable-artifact preflight criteria and local CI-equivalent proof commands.
- `AGR-07`: add mandatory negative fail-closed evidence rows for forbidden fallbacks.
- `AGR-08`: identify current web authority paths or state the new resolver replaces all protected gitlink-based runtime authority.

## Adjudicated Findings

- `F-01` Rollback is revision-incoherent for `web-app`, and the current successful-release record is insufficient because it stores only root SHA while forward deploy lane-overrides `web-app`.
- `F-02` The workflow treats rollback exit and local readiness probes as terminal recovery signals even though they are not equivalent to final-state proof.
- `F-03` Both rollback executors must satisfy one shared proof contract:
  - internal rollback inside `.github/scripts/deploy_stage_over_ssh.sh`
  - external rollback via `.github/scripts/rollback_over_ssh.sh`
- `F-04` The current host-local rebuild rollback path cannot guarantee fail-closed recovery under disk/runtime pressure; immutable artifact restoration remains mandatory for full `stage`/`main` closure.
- `F-05` `service may remain degraded` is not an acceptable terminal automation state; it is an explicit incident/degraded failure outcome.
- `F-06` The forward-path successful-release marker gate is not fail-closed: on `stage` and `main`, an `initialize=403` branch can still write the successful-release marker without branch-appropriate smoke success, which is invalid on previously healthy lanes.

## Minimum Decision Baseline

- `D-06` Successful-release state must be captured as a release tuple, not root SHA only. At minimum this tuple must include root revision plus actual deployed `web-app` runtime revision.
- `D-07` Rollback must restore that exact successful release tuple and must not consult current lane head for any serving component.
- `D-08` After any rollback path, the pipeline must rerun the same branch-appropriate external proof surface required for forward success:
  - public-edge probe;
  - provenance check against the restored release tuple;
  - readonly navigation smoke on live lanes;
  - mutation smoke on `stage`;
  - mutation remains blocked on `main`.
- `D-09` Local rollback readiness checks (`/api/v1/initialize` or root-health fallback) are necessary but not sufficient. They prove boot/readiness only, not release-safe final state.
- `D-10` Any rollback that cannot complete the shared proof contract must end in explicit incident/degraded failure. The workflow may not report recovery, pseudo-success, or best-effort closure.
- `D-11` Full fail-closed closure for `stage`/`main` remains blocked on immutable artifact restoration instead of host-local rebuild rollback.
- `D-12` Forward-path successful-release marking must be gated by the same branch-appropriate forward proof contract. Unexpected `initialize=403` on a previously healthy lane must fail closed; only an explicit bootstrap-only contract may bypass smoke and still claim a non-serving `T4` outcome.
- `D-13` Remediation must simplify the current delivery model where possible; do not add complexity unless it is necessary to preserve the invariant frozen here.
- `D-14` Functional behavior must be preserved except where current behavior is itself the fail-closed defect being corrected.
- `D-15` The current synchronized closure must merge `TODO-fast-follow-web-app-lane-runtime-topology.md` into this TODO as the web artifact authority slice. That sub-contract is required but not sufficient by itself.
- `D-16` The current synchronized closure must also implement the immutable Docker image artifact rollback path for `app`, `worker`, and `scheduler`. A web-only runtime SHA model cannot close this TODO because host-local rebuild rollback remains a known degraded-state risk.
- `D-17` One approval and one completion claim must cover the merged healthy-state scope. Independent promotion/closure of the web runtime authority TODO is forbidden while this TODO remains open for synchronized closure.

## Canonical Scenario Matrix

Coverage rule:
- every current workflow path must terminate in exactly one of the states below;
- any path that mutates a live lane must end either in a proven healthy terminal state or in explicit incident/degraded failure;
- `rollback exited 0` is never sufficient terminal evidence by itself.
- this matrix version enumerates named terminal workflow families, not every compound composition of multiple families chained together; compound paths inherit the terminal expectations of their final applicable family unless a future extension promotes them into explicit rows.

Terminal states:
- `T0 no-runtime-mutation`: workflow failed or succeeded without mutating a serving environment.
- `T1 new-healthy-version-proven`: the promoted version is live and proved through the forward proof contract.
- `T2 previous-healthy-version-proven`: rollback restored the prior healthy release tuple and re-proved it through the shared post-rollback proof contract.
- `T3 explicit-incident-degraded`: the pipeline could not prove a safe final state; human intervention is required.
- `T4 bootstrap-only-non-serving`: explicit first-deploy/bootstrap allowance only when no previous healthy revision exists and the lane is not yet a serving healthy environment. Outside that case, the path must resolve to `T2` or `T3`.

Proof surfaces:
- `FP-stage`: public-edge probe + provenance + readonly smoke + mutation smoke.
- `FP-main`: public-edge probe + provenance + readonly smoke + main mutation-hard-block.
- `RP-stage`: restored release tuple + public-edge probe + provenance + readonly smoke + mutation smoke.
- `RP-main`: restored release tuple + public-edge probe + provenance + readonly smoke + main mutation-hard-block.

| ID | Trigger / Path | Mutation Reached? | Expected Terminal State | Required Validation | Current Workflow Status |
| --- | --- | --- | --- | --- | --- |
| `MX-01` | `pull_request` to `dev|stage|main` fails lane policy | `no` | `T0` | none | `covered` |
| `MX-02` | any event fails preflight/control-plane checks before remote deploy eligibility | `no` | `T0` | none | `covered` |
| `MX-03` | promotion PR to `stage|main` fails lane-alignment and enters source-promotion preparation path | `no` | `T0` | none | `covered` |
| `MX-04` | push to `dev` passes preflight | `no` | `T0` | none | `covered` |
| `MX-05` | `pull_request` to `dev` passes lane policy and preflight, then terminates with no deploy job | `no` | `T0` | none | `covered` |
| `MX-06` | `pull_request` to `stage|main` passes lane policy and all applicable preflight/source-promotion checks, then terminates with no deploy job | `no` | `T0` | none | `covered` |
| `MX-07` | `workflow_dispatch` completes the preflight-only path and terminates with no deploy job | `no` | `T0` | none | `covered` |
| `MX-08` | any workflow run is canceled before remote runtime mutation begins (`cancel-in-progress` or manual cancel during PR/preflight/control-plane deploy setup) | `no` | `T0` | none | `covered` |
| `CP-01` | prior `stage|main` run ended degraded or mixed after rollback failure / stale success-marker state, and a subsequent forward deploy reruns against that host state | `yes` | `T3` until a trusted tuple and branch-appropriate proof re-establish `T1` or `T2` | explicit incident recovery contract; trusted tuple restoration; branch-appropriate proof | `missing: current workflow can inherit stale marker / host HEAD / lane-head web-app drift across runs; this compound family is now explicitly named because it is a realistic production chain` |
| `ST-01` | push to `stage`, initialized lane, remote deploy succeeds, all forward gates pass | `yes` | `T1` | `FP-stage` | `partial: success proof exists, but successful-release record is root-only instead of full release tuple` |
| `ST-02A` | push to `stage`, explicit first-deploy/bootstrap path, post-deploy initialize returns `403` and no previous healthy revision exists | `yes` | `T4` | explicit bootstrap contract | `missing: current workflow has no explicit bootstrap-only guardrail; it infers success from skipped proof` |
| `ST-02B` | push to `stage`, previously healthy lane, post-deploy initialize returns `403` / lane appears unexpectedly uninitialized | `yes` | `T2` | `RP-stage` | `invalid current success path: workflow can skip proof and still mark success` |
| `ST-02C` | push to `stage`, initialize `403` branch continues to successful-release marker and that marker write fails | `yes` | `T4` for explicit bootstrap path, otherwise `T2` | bootstrap contract or `RP-stage`, plus control-plane incident handling | `missing: current workflow can terminate on marker-write failure after skipped proof branch` |
| `ST-03` | stage deploy job fails before any remote runtime mutation (SSH/token/known-hosts/control-plane) | `no` | `T0` | none | `covered` |
| `ST-03A` | stage remote deploy script fails before serving cutover inside remote deploy (`repo/env/prebuild/build` before `app/nginx` replacement) | `no` | `T0` | none | `gap: deploy script currently sets runtime-mutation state early, so pre-cutover vs post-cutover is not yet a hard guarantee` |
| `ST-04` | stage previously healthy lane: remote deploy script fails after runtime mutation; internal rollback succeeds | `yes` | `T2` | `RP-stage` | `gap: internal rollback has no shared post-rollback proof and rollback tuple can be mixed` |
| `ST-05` | stage remote deploy script fails after runtime mutation; internal rollback fails or restored state cannot be proved | `yes` | `T3` | explicit incident/degraded handling | `gap: script/workflow still allow \"service may remain degraded\" as terminal state` |
| `ST-05A` | stage first-live-deploy / no-previous-healthy lane fails after live mutation before a forward proof is completed or reproved (`internal` or `external` rollback family, including post-deploy workflow/probe/provenance/smoke failures) | `yes` | `T3`, unless an explicit bootstrap-only non-serving fallback is preserved and proved as `T4` | bootstrap contract or explicit incident/degraded handling | `missing: current rollback paths fall back to host HEAD / HEAD~1 semantics and cannot prove a canonical bootstrap-safe final state` |
| `ST-06` | stage previously healthy lane: workflow step fails after remote deploy success but before proof family completes (`setup-node`, `npm ci`, browser install, host routing, warmup) | `yes` | `T2` | `RP-stage` | `gap: no rollback trigger for these post-deploy workflow failures` |
| `ST-07` | stage previously healthy lane: initialize preflight fails unexpectedly after remote deploy | `yes` | `T2` | `RP-stage` | `gap: external rollback runs, but no post-rollback proof exists` |
| `ST-08` | stage previously healthy lane: public-edge probe fails | `yes` | `T2` | `RP-stage` | `gap: external rollback runs, but no post-rollback proof exists` |
| `ST-09` | stage previously healthy lane: provenance check fails | `yes` | `T2` | `RP-stage` | `gap: external rollback runs, but no post-rollback proof exists` |
| `ST-10` | stage previously healthy lane: readonly smoke fails | `yes` | `T2` | `RP-stage` | `gap: external rollback runs, but no post-rollback proof exists` |
| `ST-11` | stage previously healthy lane: mutation smoke fails | `yes` | `T2` | `RP-stage` | `gap: external rollback runs, but no post-rollback proof exists` |
| `ST-12` | stage external rollback exits non-zero during target selection, pre-restore checks, or restore/build failure | `yes` | `T3` | explicit incident/degraded handling | `partial: workflow fails, but degraded handling is not canonical enough` |
| `ST-13` | stage external rollback exits zero but post-rollback proof fails or is skipped | `yes` | `T3` | explicit incident/degraded handling after failed `RP-stage` | `missing: no such proof rerun exists today; healthy-marker absence can still collapse into this row because rollback degrades to host HEAD / HEAD~1 semantics` |
| `ST-14` | stage forward proof passes but successful-release marker write fails | `yes` | `T1` | `FP-stage` | `gap: runtime is already proved healthy, but rollback bookkeeping is stale and pipeline must fail non-promotable as a control-plane incident` |
| `ST-15A` | stage run is canceled after live mutation but before forward proof completes, or during rollback / rollback-proof (`cancel-in-progress` or manual cancel) | `yes` | `T3` unless explicit successor-run handoff proves final state | successor-run `FP-stage`/`RP-stage` or incident handling | `missing: cancellation is not modeled as a post-mutation terminal path today` |
| `ST-15B` | stage run is canceled after `FP-stage` already completed but before successful-release marker bookkeeping completes | `yes` | `T1` | `FP-stage` | `gap: runtime is already proved healthy, but pipeline/control-plane termination can still leave rollback bookkeeping stale` |
| `ST-16` | stage wrapper/control-plane failure occurs after remote mutation or remote success (`tee` failure, SSH stream loss, missing success marker) | `yes` | `T3` unless explicit successor-run handoff proves `T1` or `T2` | successor-run `FP-stage`/`RP-stage` or incident handling | `missing: local wrapper failures after remote mutation are outside current rollback predicates` |
| `MN-01` | push to `main`, initialized lane, remote deploy succeeds, all forward gates pass | `yes` | `T1` | `FP-main` | `partial: success proof exists, but successful-release record is root-only instead of full release tuple` |
| `MN-02A` | push to `main`, explicit first-deploy/bootstrap path, post-deploy initialize returns `403` and no previous healthy revision exists | `yes` | `T4` | explicit bootstrap contract | `missing: current workflow has no explicit bootstrap-only guardrail; it infers success from skipped proof` |
| `MN-02B` | push to `main`, previously healthy lane, post-deploy initialize returns `403` / lane appears unexpectedly uninitialized | `yes` | `T2` | `RP-main` | `invalid current success path: workflow can skip proof and still mark success` |
| `MN-02C` | push to `main`, initialize `403` branch continues to successful-release marker and that marker write fails | `yes` | `T4` for explicit bootstrap path, otherwise `T2` | bootstrap contract or `RP-main`, plus control-plane incident handling | `missing: current workflow can terminate on marker-write failure after skipped proof branch` |
| `MN-03` | main deploy job fails before any remote runtime mutation (SSH/token/known-hosts/control-plane) | `no` | `T0` | none | `covered` |
| `MN-03A` | main remote deploy script fails before serving cutover inside remote deploy (`repo/env/prebuild/build` before `app/nginx` replacement) | `no` | `T0` | none | `gap: deploy script currently sets runtime-mutation state early, so pre-cutover vs post-cutover is not yet a hard guarantee` |
| `MN-04` | main previously healthy lane: remote deploy script fails after runtime mutation; internal rollback succeeds | `yes` | `T2` | `RP-main` | `gap: internal rollback has no shared post-rollback proof and rollback tuple can be mixed` |
| `MN-05` | main remote deploy script fails after runtime mutation; internal rollback fails or restored state cannot be proved | `yes` | `T3` | explicit incident/degraded handling | `gap: script/workflow still allow \"service may remain degraded\" as terminal state` |
| `MN-05A` | main first-live-deploy / no-previous-healthy lane fails after live mutation before a forward proof is completed or reproved (`internal` or `external` rollback family, including post-deploy workflow/probe/provenance/hard-block/readonly failures) | `yes` | `T3`, unless an explicit bootstrap-only non-serving fallback is preserved and proved as `T4` | bootstrap contract or explicit incident/degraded handling | `missing: current rollback paths fall back to host HEAD / HEAD~1 semantics and cannot prove a canonical bootstrap-safe final state` |
| `MN-06` | main previously healthy lane: workflow step fails after remote deploy success but before readonly proof completes (`setup-node`, `npm ci`, browser install, host routing, warmup) | `yes` | `T2` | `RP-main` | `gap: no rollback trigger for these post-deploy workflow failures` |
| `MN-06A` | main previously healthy lane: initialize preflight fails unexpectedly after remote deploy | `yes` | `T2` | `RP-main` | `gap: external rollback runs, but no post-rollback proof exists` |
| `MN-07` | main previously healthy lane: public-edge probe fails | `yes` | `T2` | `RP-main` | `gap: external rollback runs, but no post-rollback proof exists` |
| `MN-08` | main previously healthy lane: provenance check fails | `yes` | `T2` | `RP-main` | `gap: external rollback runs, but no post-rollback proof exists` |
| `MN-09` | main previously healthy lane: mutation-hard-block assertion fails unexpectedly | `yes` | `T2` | `RP-main` | `gap: this step can fail after live mutation and currently does not trigger rollback` |
| `MN-10` | main previously healthy lane: readonly smoke fails | `yes` | `T2` | `RP-main` | `gap: external rollback runs, but no post-rollback proof exists` |
| `MN-11` | main external rollback exits non-zero during target selection, pre-restore checks, or restore/build failure | `yes` | `T3` | explicit incident/degraded handling | `partial: workflow fails, but degraded handling is not canonical enough` |
| `MN-12` | main external rollback exits zero but post-rollback proof fails or is skipped | `yes` | `T3` | explicit incident/degraded handling after failed `RP-main` | `missing: no such proof rerun exists today; healthy-marker absence can still collapse into this row because rollback degrades to host HEAD / HEAD~1 semantics` |
| `MN-13` | main forward proof passes but successful-release marker write fails | `yes` | `T1` | `FP-main` | `gap: runtime is already proved healthy, but rollback bookkeeping is stale and pipeline must fail non-promotable as a control-plane incident` |
| `MN-15A` | main run is canceled after live mutation but before forward proof completes, or during rollback / rollback-proof (`cancel-in-progress` or manual cancel) | `yes` | `T3` unless explicit successor-run handoff proves final state | successor-run `FP-main`/`RP-main` or incident handling | `missing: cancellation is not modeled as a post-mutation terminal path today` |
| `MN-15B` | main run is canceled after `FP-main` already completed but before successful-release marker bookkeeping completes | `yes` | `T1` | `FP-main` | `gap: runtime is already proved healthy, but pipeline/control-plane termination can still leave rollback bookkeeping stale` |
| `MN-16` | main wrapper/control-plane failure occurs after remote mutation or remote success (`tee` failure, SSH stream loss, missing success marker) | `yes` | `T3` unless explicit successor-run handoff proves `T1` or `T2` | successor-run `FP-main`/`RP-main` or incident handling | `missing: local wrapper failures after remote mutation are outside current rollback predicates` |

## Complexity

- **Level (`small|medium|big`):** `big`
- **Why:** the slice spans release-critical CI/CD behavior, rollback semantics, runtime integrity, and possible topology change across multiple repos/environments.
- **Checkpoint policy:** bounded package checkpoint, audit-floor checkpoint, external-audit checkpoint, and final decision-baseline checkpoint before any implementation approval.

## Additional Architectural Opinions (Required When Path Remains Materially Unclear)

- **Needed:** `yes`
- **Why ambiguity remains:** the open question is not whether the current pipeline is unsafe; it is what minimum architecture/process change is sufficient to make it fail-closed without overcorrecting blindly.
- **Opinion count:** `2+`
- **Package mode:** `bounded-summary`
- **Subagent mandate (when available):** `yes`
- **Required lenses:** `correctness`, `structural-soundness`, `operational-safety`

## Audit Trigger Matrix

Populate this matrix before critique or delivery-side audit decisions are treated as authoritative.
Use exact trigger names and exact enum values only.

- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md [--json-output <artifact-path>]`
- **Latest TEACH evidence / artifact:** `audit_escalation_guard.py` => `status: ready`, `Overall outcome: go`, fingerprint `1baddb315b30` (`2026-05-22`, rerun after merged synchronized matrix insertion); terminal capture from `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `big` | Release-safety architecture and process investigation. |
| `blast_radius` | `cross-stack` | Docker orchestration with Flutter/Laravel provenance and deploy/runtime coupling. |
| `behavioral_change_or_bugfix` | `yes` | The target outcome changes failure handling semantics. |
| `changes_public_contract` | `no` | No product/API contract is being changed in the investigation slice itself. |
| `touches_auth_or_tenant` | `no` | Not auth/tenant semantics. |
| `touches_runtime_or_infra` | `yes` | Core runtime/deploy/rollback/promotion behavior is the subject. |
| `touches_tests` | `yes` | Smoke, rollback, and verification contract are in scope. |
| `critical_user_journey` | `yes` | Broken live runtime after failed promotion is release-critical. |
| `release_or_promotion_critical` | `yes` | This TODO exists because a recent `main` promotion left broken runtime live. |
| `high_severity_plan_review_issue` | `yes` | Healthy-final-state invariant is currently violated. |
| `explicit_three_lane_request` | `yes` | User explicitly requested deep external auditors. |

### Derived Audit Floor

- `Critique`: `required` before `APROVADO` via `wf-docker-independent-critique-method`.
- `Security review`: `not_needed`.
- `Performance/concurrency`: `required` via `wf-docker-performance-concurrency-validation-method`.
- `Verification debt`: `required` before completion via `verification-debt-audit`.
- `Test-quality audit`: `required` before completion via `wf-docker-independent-test-quality-audit-method`.
- `Final review`: `required` before completion via `wf-docker-independent-final-review-method`.
- `Triple review`: `required` before completion via `audit-protocol-triple-review` and additive only; it does not replace critique.

## Independent No-Context Critique Gate (Deterministic Floor From Audit Escalation)

- **Critique decision:** `required`
- **Why this decision:** the TEACH audit floor classifies this TODO as expanded-risk because it is `big`, `cross-stack`, `runtime/infra`, `test-touching`, `critical-user-journey`, `release-or-promotion-critical`, and includes a high-severity plan-review issue.
- **Impact signals in scope:** `release-critical`, `runtime/infra`, `rollback`, `post-failure safety`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen invariant`, `failure matrix`, `current code evidence`, `existing TODO mapping`, `candidate remediation classes`, `residual risks`
- **Critique isolation mode:** `fresh no-context auxiliary reviewer`
- **Subagent mandate (when available):** `yes`
- **Canonical multi-lane audit protocol (when required):** `audit-protocol-triple-review`
- **Audit session / round evidence (when protocol used):** `foundation_documentation/artifacts/tmp/fail-closed-pipeline-healthy-final-state-triple-audit-20260517T191846Z/round-01/round-summary.md`, `.../round-01/resolution.md`
- **Critique lenses:** `correctness`, `structural-soundness`, `risk`
- **Critique status:** `completed`
- **Findings summary:** `blocking; current pipeline cannot guarantee healthy final state, requires release-tuple rollback fidelity, shared post-rollback proof, and immutable artifact restoration for full stage/main closure`
- **Evidence / reference:** `foundation_documentation/artifacts/tmp/fail-closed-pipeline-healthy-final-state-claude-critique-20260517T191846Z.md`, `foundation_documentation/artifacts/tmp/fail-closed-pipeline-healthy-final-state-triple-audit-20260517T191846Z/round-01/round-summary.md`
