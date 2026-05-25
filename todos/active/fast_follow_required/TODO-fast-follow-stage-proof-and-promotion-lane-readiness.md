# TODO (Fast Follow): Stage Delivery And Promotion-Lane Readiness

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Provisional / superseded by completed promotion. The temporary `stage` sandbox proof and real serving `stage` proof completed, and the operator later authorized `main`; this TODO now remains open only for evidence reconciliation or archival.
**Current delivery stage:** `Provisional`
**Owners:** Delphi, DevOps/Platform
**Goal:** close the local logic/contract slices first, then promote through the real `dev -> stage` path, prove the applicable fail-closed matrix on the real `stage` environment, and finish with `stage` healthy on the bugfix version. Stop before any `main` promotion until explicit user authorization.
**Next exact step:** reconcile or archive this TODO; the later `main` promotion was explicitly approved and completed.
**Sequencing dependency:** this TODO depends on the fail-closed hardening tracks remaining aligned with `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`; the final `stage` state must validate the same contract, not a looser side path.

## Real Stage Cutback Evidence

- `2026-05-22`: Flutter `stage` was restored to the real landlord domain:
  - Flutter `stage` SHA: `135a13e91248b77ddae12692757abfcad1f876d4`.
  - Derived `web-app@stage` metadata: `flutter_git_sha=135a13e9`, `source_branch=stage`.
  - Local lane proof used `bash scripts/build_lane.sh stage web --release --no-tree-shake-icons --no-pub --no-wasm-dry-run -o build/lane-stage-web`; `build_lane.sh` is the lane-aware local proof path, while `web-app` remains a derived CI artifact.
- `2026-05-22`: Docker promotion lane was completed to real `stage`:
  - `bot/next-version -> dev` PR `#746` merged after stale bot branch regeneration; diff was `flutter-app` gitlink only.
  - topology-only Docker reconciliation PR `#747` merged with empty tree diff so `dev` contained current `stage`.
  - Docker `dev -> stage` PR `#748` merged; `origin/stage` advanced to `21dbb27bea4c68be4d4ea94f31f6754d6e5dd668`.
  - stage run `26315736532` passed preflight, GHCR runtime image publication, SSH deploy, deployed `web-app` runtime SHA validation, public environment probes, web provenance, readonly navigation smoke, mutation navigation smoke, and successful-release marking.
  - completion guard passed: `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-only --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front` returned `Overall outcome: go`.
- `2026-05-22`: real public proof after deploy:
  - `https://belluga.app/build_metadata.json` returns `flutter_git_sha=135a13e9`, `source_branch=stage`.
  - `https://guarappari.belluga.app/build_metadata.json` returns `flutter_git_sha=135a13e9`, `source_branch=stage`.
  - `https://belluga.app/` injects `window.__LANDLORD_HOST__ = "belluga.app"; window.__WEB_BUILD_SHA__ = "135a13e9"`.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

- `stage` remains the only canonical proof field for the real deploy/promotion path.
- The current promotion model does not pre-pin Docker gitlinks on a working bugfix branch. Gitlink movement into `dev` happens later through the lane-owned remote `bot/next-version -> dev` flow.
- Because of that model, delivery must be established by:
  - closing local logic and contract gaps first;
  - then using the real `dev -> stage` path plus `stage` for the proof cycle;
  - not by inventing a parallel pseudo-lane.
- The current live `stage` environment must remain available while destructive validation happens.
- The accepted operational strategy for that is:
  - keep the validation flow on the real `stage` lane;
  - temporarily point the validation candidate at an isolated host and temporary landlord domain;
  - keep the currently live `stage` host/domain serving the old version until the validation campaign is complete.
- Rejected path:
  - no manual provenance shortcut;
  - no fake promotion lane;
  - no “deploy candidate outside the lane and call it equivalent” workaround.
- The local hardening slices explicitly in scope before the real `stage` cycle are:
  - release tuple;
  - forward success gate;
  - rollback proof;
  - degraded-state handling.

## Current Implementation Checkpoint

- Local candidate now records a trusted successful-release tuple instead of root SHA only.
- Local candidate now fails closed on unexpected forward-path `initialize=403` for previously healthy `stage|main` lanes.
- Local candidate now wires shared post-rollback proof for both rollback executors:
  - internal rollback inside `deploy_stage_over_ssh.sh`
  - external rollback via `rollback_over_ssh.sh`
- Local candidate now treats rollback-proof failure as explicit degraded/incident state instead of best-effort degraded wording.
- Local Laravel candidate now supports `TENANT_DATABASE_PREFIX` with fallback `tenant_`, so destructive `stage` validation can isolate tenant DB names without reopening the multitenant contract.
- Local Flutter candidate now points `flutter-app/config/defines/stage.json` at the temporary landlord domain `https://belluga.online` for the destructive validation campaign.
- Temporary validation host `201.54.9.139` now accepts the `stage` CI SSH key via `ubuntu` and has the minimum runtime tooling installed (`git`, `docker`, `docker compose`) plus the canonical deploy path `/srv/belluga_now_docker`.
- The first fully live `stage` proof run against the temporary target exposed two real blockers that must be fixed in the canonical workflow before the matrix can close:
  - the workflow currently leaves runner-managed landlord/tenant host overrides in place when later “public-edge” probes execute, so rollback proof can hit the origin certificate instead of true public DNS;
  - the temporary validation environment does not yet provision the canonical public account-profile taxonomy fixture required by the readonly suite, so the readonly smoke fails for missing validation data rather than for a product/runtime regression.
- The next fully live `stage` proof run against the temporary target (`26046088556`) exposed two additional constraints that are now part of the canonical contract:
  - forward provenance must tolerate bounded transient origin-probe timeouts; the runtime exposed stable `build_metadata.json` and `index.html` immediately afterward, so the provenance proof needs deterministic retries before declaring failure;
  - rollback proof on the temporary landlord domain may only count once the trusted healthy tuple was produced under that same landlord-host contract. Restoring a tuple that still serves `window.__LANDLORD_HOST__=belluga.app` is correctly rejected when the temporary `stage` campaign expects `belluga.online`.
- The latest fully live `stage` proof run against the temporary target (`26053825498`) proved that the remaining blocker is now entirely on the forward candidate, not on rollback wiring:
  - deploy, initialize, public-edge probe, forward provenance, and readonly smoke all passed;
  - mutation smoke narrowed to two failures:
    - `NAV-APD-07..08` was selecting event-capable POI types that were not guaranteed to be publicly discoverable/favoritable under the public catalog contract;
    - the technical-integrations outbound section allowed editing before the initial remote settings load completed, so late fetch hydration could overwrite local draft values before save.
- The current local candidate fixes both of those forward blockers:
  - APD seed selection now requires public-catalog-compatible POI types and creates the fallback type with `is_publicly_discoverable=true`;
  - the Flutter technical-integrations screen now withholds editable sections until the initial remote load completes, preventing late rehydration from clobbering draft outbound webhook edits.
- Remaining pre-`stage` work on this TODO is no longer “build the contract”; it is:
  - keep the unrelated local Flutter dirt out of the promotion candidate;
  - run the real Flutter `dev -> stage` promotion so the lane-owned Docker submodule sync may legally move the stage pin into Docker `dev`;
  - merge that lane-owned Docker sync into `dev`;
  - rerun the real Docker `dev -> stage` promotion on the temporary target;
  - establish a healthy `belluga.online` trusted tuple;
  - then use a later destructive rerun to close the rollback-proof rows against that new healthy tuple.
- The lane-topology blocker discovered on `2026-05-18` has now been cleared through topology-only reconcile PRs:
  - Docker: `reconcile/dev-contains-stage-docker-20260518` merged as PR `#628` with zero content diff and no gitlink-rule relaxation.
  - Laravel: `reconcile/dev-contains-stage-laravel-20260518` merged as PR `#205` with zero content diff.
  - Flutter: `reconcile/dev-contains-stage-flutter-20260518` merged as PR `#308` with zero content diff.
  - canonical `github_stage_promotion_preflight.sh --source origin/dev --base origin/stage` now returns `Overall outcome: go` in all three repos.
- Real `stage` validation against the temporary target has now progressed into the rollback rows:
  - run `26060547111` attempt `1` proved a fully healthy forward path on the temporary `stage` target, including deploy, public-edge probe, forward provenance, readonly smoke, mutation smoke, and successful trusted-tuple marking under `belluga.online`;
  - that run established the first trusted healthy tuple on the temporary target for the bugfix candidate:
    - `ROOT_SHA=7fe2da34c5dd8bc621f2e1ad6cfdbccfb2ac98be`
    - `DEPLOY_LANE=stage`
  - run `26060547111` attempt `2` then applied deliberate destructive sabotage after live runtime replacement and exposed a new canonical workflow gap:
    - the remote deploy step had already mutated runtime on the host;
    - the deploy step still failed without emitting the remote success marker;
    - the workflow classified the result as “failed before runtime mutation”;
    - no external rollback executor ran;
    - no rollback-proof executor ran;
    - the temporary `stage` target was left down until manual canonical rollback restored the trusted tuple.
- Because that destructive finding required a material CI/workflow fix, every matrix row affected by post-mutation deploy-failure handling is now stale until rerun on the new candidate. No pre-fix pass may be carried forward for those rows.
- The current Docker candidate now addresses that exact gap locally:
  - `deploy_stage_over_ssh.sh` persists a remote `DEPLOY_RUNTIME_MUTATED` marker to the deploy log and exports `runtime_mutated` through `GITHUB_OUTPUT`;
  - `orchestration-ci-cd.yml` now treats `deploy_remote.outcome == failure && runtime_mutated == true && internal_rollback_status != success` as a rollback-required path for both `stage` and `main`;
  - the failure epilogues now distinguish:
    - internal rollback success/failure;
    - external rollback success/failure after live mutation;
    - genuine pre-mutation deploy failure;
  - `verify_environment_ci.sh` now hard-blocks regression of that wiring.
- Real `stage` validation has now also covered the pre-mutation transport-failure branch on the temporary target:
  - run `26066420178` on SHA `d5943a2484c7a9f0157eb2b6e25615fd6fd76eda` failed in `Capture stage rollback target revision` while the temporary target stalled during SSH banner exchange;
  - that row classified correctly as a pre-mutation failure:
    - deploy never started;
    - no rollback executor was invoked;
    - no rollback-proof executor was invoked;
    - the job failed closed without claiming a healthy outcome.
- That same run exposed a new workflow hardening gap:
  - `Capture stage rollback target revision` and remote diagnostics were using direct `ssh` without bounded `ConnectTimeout`;
  - the job therefore hung for minutes before failing, which is operationally correct but too slow/non-deterministic for the matrix.
- The current Docker candidate now fixes that transport-timeout gap locally:
  - both rollback-target capture steps in `orchestration-ci-cd.yml` now use `-o ConnectTimeout=5 -o ConnectionAttempts=1`;
  - `collect_remote_deploy_diagnostics.sh` now uses the same bounded SSH options;
  - `verify_environment_ci.sh` now hard-blocks regression of those timeout guards.
- Real `stage` validation has already replayed that same row with the timeout fix:
  - run `26066865339` on SHA `a1d663b5516efa5d374e8fdab58aecad2917f3cd` failed in the same step, but now failed fast in ~5 seconds instead of hanging for minutes;
  - this closes the bounded pre-mutation transport-failure row for the matrix.
- The SSH-banner availability blocker on `201.54.9.139` is now cleared:
  - after host reboot, SSH access returned and the temporary target remained recoverable;
  - a manual host-side `docker compose build nginx && docker compose up -d app nginx && docker compose restart nginx` proved the runtime path itself can restore the public edge;
  - that manual recovery does **not** count as matrix evidence, but it narrows the remaining bug to the canonical external rollback transport/execution path.
- The newest destructive finding is now more precise than “rollback proof failed”:
  - run `26066865339` attempt `3` proved the sabotage landed after live runtime restart, so the row is a valid post-mutation destructive attempt;
  - external rollback reported success, but the temporary target finished without any `nginx` container and with port `80` unreachable;
  - remote inspection showed `docker compose up -d --no-build app nginx` fails from that degraded state unless `nginx` is rebuilt first;
  - the live run log stopped after rollback cleanup / disk-budget lines and never emitted `INFO: starting rollback core runtime services (app, nginx)...`, which makes the inline SSH heredoc transport itself suspect.
- The current local Docker candidate addresses that rollback transport suspicion directly:
  - `rollback_over_ssh.sh` no longer embeds the remote rollback body as a giant inline `EOF_REMOTE` heredoc;
  - it now ships a versioned `.github/scripts/rollback_remote.sh` to the host and executes that file explicitly;
  - `verify_environment_ci.sh` now hard-blocks regression back to the inline heredoc shape.
- `2026-05-19`: post-delivery audit of the current candidate invalidated the “confirmed robust” claim and reopened the TODO:
  - triple audit round `ci-final-audit-20260519T105626Z` found a shared blocker in the web smoke harness;
  - local repro confirmed `tools/flutter/run_web_navigation_smoke.sh` was swallowing non-zero exit codes because `run_with_timeout()` captured `$?` after `if ! timeout ...`;
  - therefore every `stage` matrix row whose green outcome depended on `run_web_navigation_smoke.sh` is stale until rerun on the fixed candidate;
  - the same audit also surfaced three non-blocking follow-ups:
    - `verify_environment_ci.sh` only partially enforced the new SSH keepalive contract;
    - this TODO still referenced a non-existent canonical module anchor;
    - `ci_pipeline_surface_audit.sh` is heuristic/advisory for this deploy-proof topology and must not remain phrased as a blocking validation gate.
- `2026-05-19`: fresh ClaudeCLI review was attempted with the bounded final packet, but the local Claude CLI is currently unauthenticated (`Not logged in · Please run /login`), so the Claude leg is presently blocked on tool auth rather than on packet scope.
- `2026-05-19`: once ClaudeCLI auth was restored, the bounded final packet `ci-final-audit-20260519T220100Z` confirmed the same shared blocker as the triple audit:
  - the clean audit snapshot still shipped `web-app/.github/workflows/navigation-validation.yml` with stale action/runtime surfaces;
  - the root deterministic guard still did not inspect the `web-app` gitlink workflow tree;
  - therefore the bounded audit was blocked by stale delivered CI cleanup, not by a regression in deploy/runtime proof.
- `2026-05-19`: the local candidate now fixes the reopened audit blocker and the related cleanup items:
  - `tools/flutter/run_web_navigation_smoke.sh` now preserves the real non-zero exit status from timeout-wrapped Playwright commands;
  - `tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` now contains a regression that extracts the real `run_with_timeout()` implementation and proves timeout/non-zero propagation behavior;
  - `verify_environment_ci.sh` now runs that regression test and fully asserts the SSH keepalive/connect contract instead of checking only `ServerAliveInterval=15`;
  - root GitHub workflows now use current action majors / Node runtime (`actions/checkout@v5`, `actions/setup-node@v5`, `actions/upload-artifact@v6`, `node-version: 24`) to eliminate the current Node 20 warning surface in this repository;
  - `ci_pipeline_surface_audit.sh` was recalibrated to inspect directly referenced local scripts, so it now returns `Overall outcome: ready` for the orchestration topology instead of a false-negative `blocked`.
- `2026-05-19`: the first post-audit rerun on `stage` (`26102297542`, SHA `09f747f6bc47c86aff479b637f25538db5ba15a4`) isolated the remaining blockers to harness/transport gaps rather than app regressions:
  - `event_rich_text.mutation.spec.js` failed because `openSeededEventFromAdminList()` still required an overly narrow semantic-card contract even while the seeded admin card was visibly present in the real Events UI;
  - `navigation.mutation.event_occurrences.spec.js` failed in `NAV-08` because the `Como Chegar` confirmation still relied on exact standalone viewport text counting for `Ver no mapa`, while the real visible map card renders that label inside a richer combined semantic/text surface;
  - `tenant_admin_profile_type_plural.mutation.spec.js` failed because page-scoped `waitForResponse()` on the PATCH path is brittle under Flutter page/context churn during save and reopen;
  - external rollback still proved transport-fragile during long rebuilds: the host ultimately restored the previous healthy tuple, but the SSH session died mid-rollback build (`Broken pipe` / banner timeout), so the workflow could not produce authoritative rollback-proof evidence for that row.
- `2026-05-19`: the local equivalent loop has now closed the first remaining readonly/runtime gap before any new lane attempt:
  - the landlord readonly failure was traced to a real contract hole, not a flaky probe:
    - landlord featured-instance cards load tenant branding assets cross-origin;
    - `PublicTenantMediaCors` did not classify `/logo-dark.png` and sibling branding routes as public media;
    - even for public-media routes, `Origin: https://belluga.online` was not considered an allowed first-party origin when the request host was a tenant domain;
  - the Laravel candidate now fixes that contract locally:
    - branding asset paths (`/logo-*.png`, `/icon-*.png`, `/favicon.ico`, `/icon/*`) are inside the public-media CORS surface;
    - the configured landlord root host is now allowed as a first-party origin for tenant public-media reads;
  - the local Laravel CI-equivalent evidence for that fix is green:
    - `docker compose exec -T app bash -lc 'set -a && source .env.testing && set +a && php artisan test tests/Feature/Security/PublicMediaCorsTest.php'`
    - result: `9 passed (39 assertions)`.
  - the compose-edge runtime sanity check also confirms the fix is not test-kernel-only:
    - request: `curl -sSI -H 'Host: guarappari.belluga.space' -H 'Origin: https://belluga.space' http://127.0.0.1:8081/logo-dark.png`
    - observed header: `Access-Control-Allow-Origin: https://belluga.space`.
- `2026-05-19`: the residual Node 20 warning surface is now explicitly in scope for closure before the final audit:
  - root workflows were already moved to Node 24-capable action majors during the reopened audit fix;
  - remaining warning sources were identified in submodule-owned workflows:
    - `web-app/.github/workflows/navigation-validation.yml`
    - `flutter-app/.github/workflows/web-artifact-publish.yml`
    - `laravel-app/.github/workflows/ci.yml`
  - those workflow files are now part of the current candidate and must be locally syntax-checked before the next lane attempt.
  - the local workflow candidate now upgrades the remaining submodule-owned actions/runtime pins:
    - `web-app/.github/workflows/navigation-validation.yml`
      - `actions/checkout@v6`
      - `actions/setup-node@v6`
      - `node-version: '24'`
      - `actions/upload-artifact@v7`
    - `flutter-app/.github/workflows/web-artifact-publish.yml`
      - `actions/checkout@v6`
      - `actions/upload-artifact@v7`
      - `actions/download-artifact@v8`
    - `laravel-app/.github/workflows/ci.yml`
      - `actions/checkout@v6`
      - `actions/cache@v5`
    - each of the three workflows now also opts into `FORCE_JAVASCRIPT_ACTIONS_TO_NODE24=true`.
  - local syntax evidence for those workflow edits is green:
    - `python3` + `yaml.safe_load()` over all three modified workflow files returned `OK`.
- `2026-05-19`: the current local root candidate now closes the last stale-snapshot CI blocker before any new push or audit rerun:
  - root `HEAD` is `056067f919e57c753255b945ce18897db61c2f1d`;
  - the candidate now points `web-app` at `eb0d15ead26733e3da749b44d69975ae8c2bb943`;
  - `web-app/.github/workflows/navigation-validation.yml` now also upgrades `peter-evans/repository-dispatch` to `@v4`, eliminating the remaining stale JavaScript action runtime surface in that workflow;
  - `verify_environment_ci.sh` now materializes and scans `web-app/.github/workflows` from the `HEAD` gitlink alongside the Flutter and Laravel workflow trees, so the deterministic root guard can no longer miss a stale submodule-owned warning surface;
  - the exact local CI-equivalent rerun on that candidate is green:
    - `bash .github/scripts/verify_environment_ci.sh`
    - result: `OK: CI environment invariants validated.`
    - `python3` + `yaml.safe_load()` over:
      - `.github/workflows/orchestration-ci-cd.yml`
      - `laravel-app/.github/workflows/ci.yml`
      - `flutter-app/.github/workflows/web-artifact-publish.yml`
      - `web-app/.github/workflows/navigation-validation.yml`
    - result: `YAML OK` for all four files.
    - `bash delphi-ai/tools/ci_pipeline_surface_audit.sh --repo . --expect docker --expect flutter --expect laravel`
    - result: `Overall outcome: ready`.
- `2026-05-19`: the local CI-equivalent rerun for the current final candidate is green and therefore cleared the gate for the next real `stage` lane attempt:
  - root deterministic invariants:
    - `bash .github/scripts/verify_environment_ci.sh`
    - result: `OK: CI environment invariants validated.`
  - smoke harness truthfulness regression:
    - `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs`
    - result: `pass 1 / fail 0`
  - Laravel first-party landlord-media contract:
    - `docker compose exec -T app bash -lc 'set -a && source .env.testing && set +a && php artisan test tests/Feature/Security/PublicMediaCorsTest.php'`
    - result: `9 passed (39 assertions)`
  - workflow syntax for the remaining Node 24 warning cleanup:
    - `laravel-app/.github/workflows/ci.yml`
    - `flutter-app/.github/workflows/web-artifact-publish.yml`
    - `web-app/.github/workflows/navigation-validation.yml`
    - result: `yaml.safe_load()` returned `OK` for all three files.
- `2026-05-19`: the reopened audit candidate is now fully repackaged through the real lane instead of remaining a local-only branch set:
  - root `bot/next-version -> dev` PR `#690` merged the Laravel app pin and advanced `origin/dev` to `e2f54142ad47e2343e0d9df00e7622e70492f93c`;
  - root `bot/next-version -> dev` PR `#691` merged the Flutter app pin and advanced `origin/dev` to `7e45bc8602bb23e5e37b677a7901d3fbc964fef2`;
  - root topology-only PR `#692` repaired `origin/dev` ancestry against `origin/stage` without content drift and advanced `origin/dev` to `cdc9e01ac3a548ea0ec6cdb465d6bede40dbd55c`;
  - canonical preflight returned `Overall outcome: go` on the resulting `origin/dev` vs `origin/stage` shape.
- `2026-05-19`: the fresh real Docker lane replay is now recorded on the current candidate generation:
  - guarded `dev -> stage` PR `#693` merged cleanly;
  - `origin/stage` advanced to `19c3514d51bb1459b2067e0186800d3c9a62a3bf`;
  - post-merge `stage` run `26124523256` attempt `1` completed fully green on that SHA.
- `2026-05-19`: the fresh happy-path proof on the current candidate generation is now canonical:
  - run `26124523256` attempt `1` passed deploy, public-edge probe, provenance, readonly smoke, mutation smoke, and successful-release marking on the temporary target;
  - the trusted tuple currently recorded on `201.54.9.139` is:
    - `ROOT_SHA=19c3514d51bb1459b2067e0186800d3c9a62a3bf`
    - `WEB_APP_RUNTIME_SHA=99ce4902a83a4b7e41420eca58615762c9d14048`
    - `DEPLOY_LANE=stage`
    - `RECORDED_AT=2026-05-19T21:11:25Z`
- `2026-05-19`: the remaining destructive post-mutation row is now closed on that same candidate generation:
  - run `26124523256` attempt `2` deliberately killed the live non-tty SSH transport after runtime replacement on `201.54.9.139`;
  - `Deploy pinned stage stack over SSH` failed at `2026-05-19T21:33:26Z` after the target had already rotated `app`/`nginx`, so this is canonical post-mutation evidence rather than a pre-mutation transport row;
  - the workflow then executed external rollback successfully, completed rollback proof successfully, passed restored readonly + mutation navigation smoke, and ended at `Fail stage deploy after rollback` exactly as designed;
  - the host finished healthy on the trusted tuple:
    - `ROOT_SHA=19c3514d51bb1459b2067e0186800d3c9a62a3bf`
    - `WEB_APP_RUNTIME_SHA=99ce4902a83a4b7e41420eca58615762c9d14048`
    - `DEPLOY_LANE=stage`
    - `RECORDED_AT=2026-05-19T21:11:25Z`
- Consequence:
  - the current remaining work is no longer matrix closure; it is the final audit/review pass on a candidate that now has both fresh happy-path and destructive rollback-backed evidence.
- Consequence:
  - the happy-path rerun required by the `2026-05-19` wrapper/audit fixes is now closed on the current candidate generation;
  - the destructive post-mutation rollback-backed row is now also closed on that same candidate generation;
  - no stale matrix evidence remains for this TODO on the current candidate generation.
- `2026-05-19`: the refreshed bounded pre-push audit packet on the exact local candidate is now clean:
  - packet: `ci-final-audit-20260519T222511Z`
  - root candidate: `056067f919e57c753255b945ce18897db61c2f1d`
  - `web-app` gitlink: `eb0d15ead26733e3da749b44d69975ae8c2bb943`
  - triple audit round `02` returned zero findings in elegance, performance, and test-quality;
  - the round-02 `needs_adjudication` classification was non-material only (recommended-path wording drift with zero findings) and was resolved without opening another fix loop;
  - Claude on the same packet returned `Verdict: CLEAN` with `No blocking findings.`
  - residual notes are non-blocking only:
    - dead `dev` branch inside the nested lane case of `web-app/.github/workflows/navigation-validation.yml`;
    - pre-existing inline `/etc/hosts` mutation scoped to that submodule workflow and not covered by the root orchestration no-inline-hosts rule.
- `2026-05-19`: the first real publication attempt of that “clean” packet surfaced two additional integration gaps, so the candidate was reopened before any new push:
  - root PR `#694` proved that `verify_environment_ci.sh` still had a blind spot: when `rg` scanned a mixed path set and one submodule lacked `.github/workflows`, `rg` returned exit code `2` even if the `web-app` tree contained real matches, and the guard therefore treated stale runtime surfaces as “no match”;
  - the principal `web-app` submodule checkout/pin was also stale (`eb0d15ead26733e3da749b44d69975ae8c2bb943`) relative to clean `web-app/origin/dev` (`9cebc14886d87351126c29a6d8fbf71dfa76091c`), so the root candidate was still proving against an obsolete delivered workflow snapshot;
  - the current local head `2ef6aa28ac0671cb8fa09da0a4e8b05a660e05bf` fixes both issues:
    - `verify_environment_ci.sh` now filters nonexistent workflow paths before regex scanning and still supports the explicit grep fallback when `rg` is unavailable;
    - `.github/workflows/submodule-sync-pr.yml` now treats `web-app` as a first-class supported protected submodule alongside `flutter-app` and `laravel-app`;
    - the root gitlink now points `web-app` at clean `origin/dev` SHA `9cebc14886d87351126c29a6d8fbf71dfa76091c`;
  - proof on the exact local candidate is now stronger than the earlier packet:
    - negative proof: after the helper fix but before the gitlink repin, `bash .github/scripts/verify_environment_ci.sh` failed as expected on stale `peter-evans/repository-dispatch@v3` surfaces in the old `web-app` gitlink snapshot;
    - positive proof: after the repin, both `bash .github/scripts/verify_environment_ci.sh` and `VERIFY_ENV_FORCE_GREP_FALLBACK=1 bash .github/scripts/verify_environment_ci.sh` returned `OK: CI environment invariants validated.`
    - structural proof: `python3` + `yaml.safe_load()` returned `OK` for:
      - `.github/workflows/orchestration-ci-cd.yml`
      - `.github/workflows/submodule-sync-pr.yml`
      - `laravel-app/.github/workflows/ci.yml`
      - `flutter-app/.github/workflows/web-artifact-publish.yml`
      - `web-app/.github/workflows/navigation-validation.yml`
      - `web-app/.github/workflows/dispatch-docker-sync.yml`
      - `web-app/.github/workflows/lane-auto-promotion.yml`
    - topology proof: `bash /home/elton/Dev/repos/delphi-ai/tools/ci_pipeline_surface_audit.sh --repo . --expect docker --expect flutter --expect laravel` returned `Overall outcome: ready`.
- `2026-05-19`: the reopened CI-hardening candidate was then published through the canonical lane-owned route:
  - root `bot/next-version -> dev` PR `#695` (`ci: harden web workflow runtime enforcement`) merged cleanly;
  - `origin/dev` advanced to `679845b32d3d5a2ccc48c9090268a6d5a5862461`;
  - the published bounded packet at `/tmp/belluga_now_docker_published_20260519_bot/foundation_documentation/artifacts/tmp/ci-final-audit-20260519T232853Z/review-packet.md` remained clean under triple audit plus Claude.
- `2026-05-20`: lane topology was repaired again without content drift so the new `dev` tip legally contained the current `stage` tip before the final promotion:
  - root topology-only PR `#696` (`chore(topology): make dev contain stage tip`) merged cleanly;
  - the reconciliation merge was ancestry-only against `origin/stage` with zero tree diff versus `origin/dev`;
  - `origin/dev` advanced to `86dd7fc08b94a94a186cbe4c79e78f16dd281fb5`;
  - canonical preflight again returned `Overall outcome: go`.
- `2026-05-20`: the final real `dev -> stage` promotion for this TODO landed successfully:
  - guarded PR `#697` (`promote: dev to stage`) merged cleanly;
  - `origin/stage` advanced to `c6e470fd76313f64a85535270bee9ddfd26b6143`;
  - post-merge `stage` run `26133152708` completed fully green in `19m45s`;
  - `Deploy Stage` passed deploy, public-edge probe, provenance, readonly smoke, mutation smoke, and successful-release marking without entering rollback;
  - public checks on `https://belluga.online` and `https://tenant.belluga.online` returned `HTTP/2 200` while the mutation smoke was still running and after the run closed.
- Consequence:
  - the lane-facing replay is now complete and green on `stage`;
  - this TODO's `through stage` mandate is satisfied on the canonical lane-owned workflow;
  - no further execution is authorized here beyond preserving evidence and waiting for any later explicit `main` decision.
- `2026-05-20`: the post-delivery final promotion-safety audit on the exact published `stage` snapshot reopened one blocker against the stronger “safe in all supported scenarios” claim:
  - bounded packet: `/tmp/belluga_now_docker_stage_gate_audit_20260520/foundation_documentation/artifacts/tmp/ci-stage-gate-audit-20260520T023035Z/review-packet.md`
  - triple-audit round summary: `/tmp/belluga_now_docker_stage_gate_audit_20260520/foundation_documentation/artifacts/tmp/ci-stage-gate-audit-20260520T023035Z/triple-audit/round-01/round-summary.md`
  - recorded adjudication: `/tmp/belluga_now_docker_stage_gate_audit_20260520/foundation_documentation/artifacts/tmp/ci-stage-gate-audit-20260520T023035Z/triple-audit/round-01/resolution.md`
  - blocker `TQ-001`: `.github/workflows/orchestration-ci-cd.yml` still allows `Mark stage revision as successful after navigation smoke` to run when `stage_initialize_preflight.outputs.initialized != 'true'` and no trusted tuple exists yet, even though public-edge probe, provenance, taxonomy fixture, readonly smoke, and mutation smoke are all skipped on that branch;
  - effect: the current workflow can still stamp a green `stage` success on an initialize-403 bootstrap branch without the full proof stack, so the broader promotion-safe signoff remains blocked until that branch is made fail-closed or explicitly untrusted.
- `2026-05-22`: the sandbox replay for the current follow-up candidate is green:
  - Docker PR `#744` merged the readonly-smoke external telemetry guard into `dev`;
  - Docker PR `#745` merged `dev -> stage`;
  - `origin/stage` advanced to `86d85e07b41ef16aaad8df375491a14cebb672a4`;
  - post-merge stage run `26311555749` completed successfully on the temporary `belluga.online` target;
  - `Deploy Stage` passed deploy, public-edge probe, deployed-web provenance, public taxonomy fixture, readonly smoke, mutation smoke, and successful-release marking;
  - public proof after the run confirmed `https://belluga.online/build_metadata.json` exposes `flutter_git_sha=ca74b3b7`, `source_branch=stage`, and `https://belluga.online` / `https://tenant.belluga.online` return the expected landlord/tenant environment payloads.
- Consequence:
  - the current CI/runtime candidate is trusted on the temporary sandbox target;
  - restoring the real serving `stage` is now a domain/artifact/variable cutback task, not an additional sandbox blocker;
  - because the current Flutter/web `stage` artifacts intentionally serve `belluga.online`, changing only Docker vars to `belluga.app` would correctly fail the fallback-zero domain gate.
- `2026-05-22`: real serving `stage` readiness probe before cutback:
  - TCP `169.150.1.122:22` is open;
  - `ssh-keyscan` fingerprints for `169.150.1.122`: RSA `SHA256:t4vuOL0nvns4xjZ/Ny1Wn6+LJ1e1Idzuipz3Ti0eka0`, ECDSA `SHA256:y564xgU6+RxWNZ8OTwhogEcc4D9xsfEXPHryDnbj6yI`, ED25519 `SHA256:YZd2fkQavzur5GFzKBLIsExHSBesGTg6K5jbYxiDc9I`;
  - `https://belluga.app/api/v1/environment` returns landlord `main_domain=https://belluga.app`;
  - `https://guarappari.belluga.app/api/v1/environment` returns tenant `main_domain=https://guarappari.belluga.app`, `tenant_id=698b1d187078c98e75074205`;
  - the currently served real-stage web bundle is older (`build_metadata.flutter_git_sha=1b780c30`, `window.__LANDLORD_HOST__=belluga.app`) while `web-app@origin/stage` now points to the sandbox artifact (`flutter_git_sha=ca74b3b7`, `window.__LANDLORD_HOST__=belluga.online`), so the cutback must rebuild/republish web `stage` after restoring Flutter `stage` domain authority.

## Temporary Stage Validation Strategy

- Destructive validation is allowed, but it must not take down the currently serving `stage`.
- The chosen strategy is:
  - temporary landlord domain for the `stage` validation candidate via `flutter-app/config/defines/stage.json`;
  - temporary `stage` infra target via `STAGE_*` deploy vars/secrets;
  - isolated landlord DB parameters and tenant DB prefix on the validation host.
- This is intentionally **not** a new pseudo-lane. It is still the canonical `stage` flow, exercised against temporary validation infra.
- Because landlord domain participates in navigation/provenance proof, restoring the real `stage` landlord domain later counts as a validation-surface change. Before final delivery to the real serving `stage`, the domain-sensitive `stage` proof subset must be rerun on the restored real target.
- Consequence:
  - if provenance, smoke, or rollback evidence is not produced by the actual lane-owned `stage` workflow against the temporary validation target, it does not count toward matrix completion.

## Temporary Stage Campaign Coordinates

- Temporary validation SSH host: `201.54.9.139`
- Temporary validation landlord domain: `https://belluga.online`
- Temporary validation tenant URL: `https://tenant.belluga.online`
- SSH user / deploy path / general runner contract stay aligned with the normal `stage` target unless explicit evidence forces a divergence.
- `STAGE_SSH_KNOWN_HOSTS` can now be refreshed from the captured host key for `201.54.9.139`.
- Temporary target bootstrap is complete; the current blocker is no longer infra. The active blocker is candidate promotion of the latest forward fixes so the next real `stage` run can attempt a first healthy `belluga.online` tuple.
- Validation host data isolation requirements:
  - isolated landlord DB parameters;
  - `TENANT_DATABASE_PREFIX=prestage_tenant_` on the temporary validation host;
  - destructive seed/setup is allowed on the temporary host because it is outside the currently serving `stage` runtime.

## Temporary Stage Cutover Checklist

- GitHub vars/secrets to redirect temporarily for the destructive `stage` campaign:
  - `STAGE_SSH_HOST=201.54.9.139`
  - refresh `STAGE_SSH_KNOWN_HOSTS` for the temporary host (`201.54.9.139`)
  - `STAGE_NAV_LANDLORD_URL=https://belluga.online`
  - `STAGE_NAV_TENANT_URL=https://tenant.belluga.online`
- Keep the standard `stage` contract unchanged unless evidence forces divergence:
  - SSH user remains the normal `stage` user;
  - deploy path remains the normal `stage` deploy path;
  - stage SSH key reuse is allowed.
- Validation-host environment requirements before the destructive campaign:
  - landlord DB points at isolated validation data;
  - `TENANT_DATABASE_PREFIX=prestage_tenant_`;
  - tenant and landlord seed/setup may be created freely on the temporary validation target.
- Exit checklist before declaring readiness for the real serving `stage`:
  - restore the real `stage` landlord domain / host targeting;
  - rerun the domain-sensitive proof subset on the restored real target;
  - only then treat the later real `dev -> stage` landing as promotion-ready.

## Validation Host Minimum Provisioning Contract

- Network / access:
  - SSH reachable on port `22`;
  - `STAGE_SSH_KNOWN_HOSTS` captured from the real temporary host key;
  - Cloudflare / DNS already resolves `belluga.online` and `tenant.belluga.online`.
- Runtime tools on host:
  - `git`
  - `docker`
  - `docker compose` v2
- Deploy path contents at the normal `stage` path:
  - repository checkout under the expected deploy path;
  - root `.env` provisioned for the temporary landlord domain;
  - `laravel-app/.env` provisioned for isolated validation data.
- Minimum root `.env` expectations:
  - `DOMAIN=belluga.online`
  - same port/profile contract as the normal `stage` target unless evidence forces divergence.
- Minimum `laravel-app/.env` expectations:
  - `DB_URI_LANDLORD`
  - `DB_DATABASE_LANDLORD`
  - `DB_URI_TENANTS`
  - `DB_DATABASE_TENANTS`
  - `TENANT_DATABASE_PREFIX=prestage_tenant_`
  - `LOG_MONGODB_DATABASE`
- Provisioning simplification rule:
  - do not introduce a new generic bootstrap lane or parallel environment model just for this host;
  - if host bootstrap is needed, keep it as the smallest explicit provisioning checklist compatible with the existing `stage` deploy contract.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `FF-STAGE-DELIVERY-READINESS`
- **Direct-to-TODO rationale:** the canonical proof field (`stage`), the fail-closed matrix, and the promotion-lane boundaries are already concrete; the work is now execution and hardening, not product discovery.

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/project_constitution.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision consolidation targets:**
  - `foundation_documentation/project_constitution.md`

## Scope

- [x] Implement the minimal release tuple contract needed for forward success, rollback targeting, and post-rollback proof.
- [x] Implement the forward success-marker gate so unexpected `initialize=403` can no longer bypass proof on previously healthy lanes.
- [x] Implement the shared rollback proof / degraded-state handling contract so rollback result quality is explicit and fail-closed.
- [x] Validate locally everything that is logic/contract-shaped before touching `stage`.
- [x] Define the smallest honest first `stage` proof subset to run, then expand to the full applicable matrix.
- [x] Execute the real promotion path required to land the bugfix on `stage`.
- [x] Exercise the real deploy/runtime-mutation matrix rows on `stage` only after the current local change set is coherent.
- [x] Finish with `stage` serving the bugfix version in a healthy state.
- [ ] Leave the repositories and operational notes ready for a later `main` promotion:
  - no missing workflow contract assumptions;
  - no unresolved sequencing ambiguity;
  - no stale matrix evidence from earlier intermediate candidates.

## Out of Scope

- [ ] Declaring `main` safe before the `stage` matrix and the related hardening tracks are proved.
- [ ] Triggering the real `stage -> main` promotion.
- [ ] Reintroducing a parallel proof lane just to mimic `stage`.

## Decision Baseline

- [x] `D-01` `stage` is the canonical proof field for the real promotion flow.
- [x] `D-02` Local implementation must close logic and contract gaps before `stage` is used as proof.
- [x] `D-03` Candidate readiness must still respect the canonical Docker gitlink promotion model; no fake early pinning is introduced just to make testing easier.
- [x] `D-03A` `bot/next-version` is lane-owned and must only be touched by the real promotion lane when that flow reaches the sync step; it is not a manual bugfix lane.
- [x] `D-04` The target is no longer “readiness only”; `stage` must actually finish on the bugfix version and remain healthy.
- [x] `D-05` `main` remains blocked until explicit authorization, even if `stage` is healthy.
- [x] `D-06` Matrix evidence is version-sensitive: if a later fix changes CI/deploy/rollback behavior, earlier passed rows that could be affected must be rerun on the current candidate before readiness can be claimed.

## Definition of Done

- [x] The local logic/contract slices in scope are implemented and validated.
- [x] The real `dev -> stage` path needed for this bugfix has been executed.
- [x] The fail-closed matrix rows that require real deploy/runtime mutation have been exercised on `stage` with recorded evidence.
- [x] Forward proof, rollback proof, and degraded/incident handling behave consistently with the governing matrix on the real proof field.
- [x] `stage` ends healthy on the bugfix version.
- [ ] Workflow/runtime assumptions needed for the later `main` promotion are documented and resolved.
- [x] All claimed-passing matrix evidence was produced after the latest material CI/deploy/rollback change in scope; no stale pre-change pass is being carried forward.

## Validation Steps

- [x] `bash delphi-ai/tools/ci_pipeline_surface_audit.sh --repo . --expect docker --expect flutter --expect laravel`
- [x] `bash .github/scripts/verify_environment_ci.sh`
- [x] Local/static validation of the tuple / gate / rollback-proof logic changes.
- [x] `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs`
- [x] Evidence of the real lane-owned promotion flow for this bugfix, including the `dev -> stage` path and the `bot/next-version` sync only if/when that step is naturally exercised by the flow.
- [x] `stage` evidence for the targeted real deploy/runtime-mutation matrix rows.
- [x] After each material workflow/script/runtime change, rerun the full applicable matrix subset and replace stale prior evidence instead of appending only new downstream rows.

## Current Local Evidence Checkpoint

- `2026-05-18`: `bash .github/scripts/verify_environment_ci.sh` passed on the current candidate after rollback-proof wiring.
- `2026-05-18`: shell syntax passed for:
  - `.github/scripts/deploy_stage_over_ssh.sh`
  - `.github/scripts/rollback_over_ssh.sh`
  - `.github/scripts/mark_successful_revision_over_ssh.sh`
  - `.github/scripts/check_deployed_web_provenance.sh`
  - `.github/scripts/verify_environment_ci.sh`
- `2026-05-18`: `.github/workflows/orchestration-ci-cd.yml` parsed successfully via local YAML load after the rollback-proof additions.
- `2026-05-18`: `laravel-app/tests/Unit/Application/LandlordTenants/TenantLifecycleServiceTest.php` passed after adding configurable `TENANT_DATABASE_PREFIX`, including the explicit override case `prestage_tenant_`.
- `2026-05-18`: `flutter-app/config/defines/stage.json` parsed successfully after switching the temporary validation landlord domain to `https://belluga.online`.
- `2026-05-18`: `node --check tools/flutter/web_app_tests/account_profile_detail.spec.js` passed after hardening APD seed selection to require public-catalog-compatible POI profile types and fallback discoverability.
- `2026-05-18`: `flutter-app/test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart` passed in full after gating technical-integrations editing behind the initial remote load completion and adding the dedicated regression for late outbound-settings hydration.
- `2026-05-18`: `fvm dart analyze --format machine` at `flutter-app/` root completed with exit code `0` on the current candidate after the technical-integrations screen/test changes.
- `2026-05-19`: `bash delphi-ai/tools/ci_pipeline_surface_audit.sh --repo . --expect docker --expect flutter --expect laravel` now returns `Overall outcome: ready` after the script-level topology inspection fix.
- `2026-05-19`: post-audit local remediation completed and passed:
  - `bash -n tools/flutter/run_web_navigation_smoke.sh .github/scripts/verify_environment_ci.sh .github/scripts/deploy_stage_over_ssh.sh .github/scripts/rollback_over_ssh.sh`
  - YAML parse passed for:
    - `.github/workflows/orchestration-ci-cd.yml`
    - `.github/workflows/lane-promotion-pr.yml`
    - `.github/workflows/submodule-sync-pr.yml`
    - `.github/workflows/source-promotion-status-callback.yml`
  - `node --test tools/flutter/web_app_tests/navigation_harness_policy_test.cjs` passed with the new timeout/exit-propagation regression
  - `bash .github/scripts/verify_environment_ci.sh` passed
  - `bash delphi-ai/tools/ci_pipeline_surface_audit.sh --repo . --expect docker --expect flutter --expect laravel` now returns `Overall outcome: ready`
- `2026-05-18`: the applicable local subset was rerun on the full current candidate state after the tenant-prefix and temporary-domain changes; the deterministic rows passed and only the known heuristic false negatives from `ci_pipeline_surface_audit.sh` remain advisory.
- `2026-05-18`: temporary validation host `201.54.9.139` reached the minimum infrastructure contract:
  - SSH reachable on port `22`;
  - `~/.ssh/belluga_stage_ci` authenticates as `ubuntu`;
  - `STAGE_SSH_KNOWN_HOSTS` material can be captured from `ssh-keyscan`;
  - `docker` / `docker compose` installed;
  - `/srv/belluga_now_docker` created and owned by `ubuntu`.
- `2026-05-18`: the temporary validation target now has:
  - the baseline `/srv/belluga_now_docker` checkout copied from the currently serving `stage` host;
  - root `.env` mutated to `DOMAIN=belluga.online`;
  - `laravel-app/.env` mutated to `APP_URL=https://belluga.online`, `SESSION_DOMAIN=.belluga.online`, isolated landlord/log DB names, and `TENANT_DATABASE_PREFIX=prestage_tenant_`.
- `2026-05-18`: the temporary validation baseline is now running and healthy enough to serve as a rollback target:
  - `sudo docker compose build app worker scheduler nginx` succeeded on `201.54.9.139`;
  - `app`, `nginx`, `worker`, and `scheduler` are up under `/srv/belluga_now_docker`;
  - `http://127.0.0.1/api/v1/initialize` with `Host: belluga.online` moved from `403` to `200` after initialization;
  - public `https://belluga.online/` and `https://tenant.belluga.online/` both return `200`;
  - `.last_successful_revision` on the temporary host is now a trusted tuple:
    - `ROOT_SHA=4b9120e8c1d023b396f5fa552eec3022f48f5f7e`
    - `WEB_APP_RUNTIME_SHA=ed83a964e37ba8a4cf712814884795cdb43a25b3`
    - `DEPLOY_LANE=stage`
- `2026-05-18`: the temporary validation environment is initialized with an isolated landlord + tenant baseline:
  - landlord domain `belluga.online`;
  - tenant `tenant.belluga.online`;
  - admin seeded for later smoke alignment on the temporary `stage` target.
- `2026-05-18`: real `stage` run `26046088556` advanced materially farther on the temporary target:
  - forward deploy completed;
  - public-edge probe passed with the new managed host-override lifecycle;
  - warmup passed;
  - forward provenance failed by transient timeout (`curl: (28)`) even though diagnostics collected immediately afterward fetched both `/build_metadata.json` and `/index.html` successfully;
  - external rollback completed and restored the prior trusted tuple;
  - rollback public-edge probe, warmup, and metadata provenance fetch all passed;
- `2026-05-18`: real `stage` run `26060547111` attempt `1` completed fully green on the temporary target and established the first trusted healthy tuple for the bugfix candidate under the `belluga.online` contract.
- `2026-05-18`: real `stage` run `26060547111` attempt `2` deliberately sabotaged the target after live runtime mutation and exposed the current blocker:
  - the runtime was already replaced on host;
  - the deploy step failed without a remote success marker;
  - no internal rollback markers were emitted;
  - the workflow skipped external rollback and rollback proof, misclassifying the failure as pre-mutation;
  - the temporary target had to be restored manually with the canonical `rollback_over_ssh.sh` flow.
- `2026-05-18`: after restoring the target manually, the local Docker candidate was updated to persist `runtime_mutated` as a durable remote marker, wire external rollback on deploy-step failure after mutation, and reclassify the failure epilogues accordingly.
- `2026-05-18`: local validation reran on that updated candidate and passed again:
  - `bash -n` on the touched deploy/rollback/provenance/verification scripts;
  - YAML parse of `.github/workflows/orchestration-ci-cd.yml`;
  - `bash .github/scripts/verify_environment_ci.sh`.
- `2026-05-18`: real `stage` run `26063868138` attempt `1` completed fully green on the current hardening candidate and established the latest trusted healthy tuple for the temporary `belluga.online` campaign:
  - `ROOT_SHA=a1ba69775241f69a0da68b082c7a466f66eddb0e`
  - `DEPLOY_LANE=stage`
- `2026-05-18`: real `stage` run `26063868138` attempt `2` was used as the first destructive rerun on that same healthy tuple. The injected fault landed too early (during remote build, before runtime restart), so it does **not** count as the stale post-mutation row. It still exposed a narrower canonical gap:
  - the deploy job failed with `ssh_status=255` / `client_loop: send disconnect: Broken pipe`;
  - no `DEPLOY_RUNTIME_MUTATED=1` line had been persisted to the local deploy log before the transport died;
  - `internal_rollback_status` stayed `not_attempted`;
  - the workflow therefore skipped rollback and rollback proof and again classified the failure as pre-mutation.
- `2026-05-18`: the local Docker candidate now emits deploy-state markers at transition time, not only in the remote `EXIT` trap:
  - `DEPLOY_RUNTIME_MUTATED=0` is emitted before prebuild cleanup;
  - `DEPLOY_RUNTIME_MUTATED=1` is emitted immediately before core runtime replacement;
  - `internal_rollback_status=attempting|success|failure|skipped_*` is emitted when each transition happens;
  - this hardening is specifically intended to preserve rollback classification evidence even when the SSH transport dies after runtime mutation but before the remote shell exits cleanly.
- `2026-05-18`: because the post-mutation deploy-failure wiring changed materially after run `26060547111` attempt `2`, all rollback-path matrix evidence that depends on that wiring must be rerun on the current candidate before this TODO may claim readiness.
  - rollback proof then correctly failed because the restored tuple still served `window.__LANDLORD_HOST__=belluga.app`, while the temporary validation campaign now expects `belluga.online`.
- `2026-05-18`: conclusion from run `26046088556`:
  - the managed public-vs-origin host override fix is validated;
  - the temporary-domain campaign now requires a fresh healthy baseline tuple produced under `belluga.online` before rollback rows can count toward matrix completion;
  - the immediate code gap is bounded retry behavior in `check_deployed_web_provenance.sh`, not a relaxation of the provenance contract.
- `2026-05-18`: first real `stage` deploy attempt on temporary target failed before runtime mutation because the host user `ubuntu` lacked Docker daemon access (`permission denied /var/run/docker.sock`). This is environment drift on the temporary validation host, not a source/CI contract regression. After fixing host access, rerun the same `stage` workflow on the same SHA; previous runtime evidence remains invalid because the deploy path did not reach smoke/provenance.
- `2026-05-18`: the topology-only reconcile path was executed without weakening CI:
  - Docker reconcile PR `#628` merged into `dev` via an ancestry-only `-s ours` merge against `origin/stage`, preserving zero content diff and avoiding any manual `web-app` gitlink mutation.
  - Laravel reconcile PR `#205` and Flutter reconcile PR `#308` merged into `dev` with zero content diff, fixing ancestry without fake accessory commits.
  - After `git fetch --prune origin`, canonical preflight now reports `Overall outcome: go` for:
    - Docker `origin/dev=d86fbdfaa6e27e99247ace04d6639d81e6f3f29d` vs `origin/stage=4b9120e8c1d023b396f5fa552eec3022f48f5f7e`
    - Laravel `origin/dev=154a99750ac13f41d1a07d95853c44bff2221b1d` vs `origin/stage=320de06f6848e019d117f5e2202b372a05d55e07`
    - Flutter `origin/dev=844c481371ec10718e62d7b064631a3637c821d4` vs `origin/stage=fd501b5826c4d772531bc72edd2a69155e55d341`
  - Remaining gate before opening the Flutter `dev -> stage` PR: wait for the post-merge `dev` run for PR `#308` to finish green.
- `2026-05-18`: the real app promotion legs are now open:
  - Laravel `dev -> stage` PR `#206` created with expected SHA `154a99750ac13f41d1a07d95853c44bff2221b1d`;
  - Flutter `dev -> stage` PR `#309` created with expected SHA `844c481371ec10718e62d7b064631a3637c821d4`;
  - no review comments or review decisions are present yet on either PR;
  - checks are running and the lane remains blocked on full green completion before merge.
- `2026-05-18`: the app promotion legs have now landed in `stage`:
  - Laravel PR `#206` merged as `ba930f0ffd922910a93476137683a70df8419496`;
  - Flutter PR `#309` merged as `5999f0939ab15df308aeb77ef291ae3a2f80db2f`;
  - Laravel `stage` post-merge CI finished green and successfully ran `Dispatch Docker Submodule Sync`.
- `2026-05-18`: the Docker lane-owned sync branch is now moving again:
  - `origin/bot/next-version` currently points to `0d875e61b748da8135f608e2ee390e628911dec8`;
  - that commit is a partial sync from the Laravel `stage` merge (`🔗 chore(submodules): sync laravel-app pin to ba930f0`);
  - do not open the Docker PR yet; wait for the Flutter `stage` post-merge run to finish so the branch can converge on the combined app pins.
- `2026-05-18`: the first fully live `stage` run on the temporary validation target reached real deploy/provenance/readonly territory on attempt `2` of run `26042065113` and exposed two matrix blockers:
  - readonly failure was caused by missing canonical public account-profile validation data, not by a deploy/runtime regression:
    - `account_profile_detail.spec.js` failed `NAV-APD-01`, `NAV-APD-02..06`, and `NAV-APD-12` with “Seed at least one public Account Profile ...” expectations;
    - `taxonomy_display_snapshots.spec.js` failed because no public account-profile taxonomy snapshot existed where display label/name differed from raw value;
    - mutation smoke correctly did not run because readonly had already failed.
  - rollback proof restored the trusted tuple successfully, but the later public-edge rollback probe failed because the runner still had landlord/tenant `/etc/hosts` overrides pointing at the origin, so the supposed public HTTPS probe hit the origin’s self-signed certificate instead of real public DNS.
- `2026-05-18`: these findings change the execution contract:
  - do not treat inline `/etc/hosts` edits as acceptable workflow structure;
  - do not “hand-seed” validation data outside the repo;
  - fix both issues as durable, repo-owned pipeline contracts and rerun the affected matrix subset afterward.

## Rollout Shape

1. **Local contract phase**
   - implement and validate tuple/gate/rollback-proof/degraded-state logic;
   - rerun the local applicable matrix subset after each material change.
2. **Stage proof phase**
   - if canonical preflight reports that `origin/dev` does not contain `origin/stage`, repair lane topology first through the canonical allowed sync path; do not bypass the preflight;
   - execute the real `dev -> stage` path for the bugfix;
   - first use the temporary landlord-domain / isolated-host `stage` campaign for destructive validation;
   - allow destructive seeding and failure-path testing on that temporary validation target because it is isolated from the currently serving `stage`;
   - run the smallest honest first `stage` proof subset;
   - expand to the full applicable `stage` matrix as the local slices stabilize;
   - rerun affected rows whenever a later change could invalidate earlier evidence.
3. **Stage-delivered stop**
   - restore the real `stage` landlord domain / infra target if they were temporarily redirected for destructive validation;
   - rerun the domain-sensitive `stage` proof subset on the real serving target;
   - consolidate evidence;
   - ensure `stage` remains healthy on the bugfix version;
   - stop before triggering `main`.

## Related TODO Mapping

- Governing invariant and matrix:
  - `TODO-fast-follow-fail-closed-pipeline-healthy-final-state.md`
- Track 1: release tuple / rollback fidelity:
  - `TODO-post-release-docker-rollback-runtime-web-fidelity.md`
- Track 2: forward marker gate / unexpected `initialize=403`:
  - `TODO-post-release-forward-success-marker-gate-and-unexpected-initialize-403-handling.md`
- Track 3: shared rollback proof / degraded-state escalation:
  - `TODO-post-release-rollback-final-state-verification-and-degraded-state-escalation.md`
- Track 4: immutable artifact model:
  - `TODO-vnext-deploy-artifact-rollforward-and-rollback-path.md`
