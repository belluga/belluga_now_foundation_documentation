# TODO (Post Release Hardening): Local-Public Docker Runtime and Web Smoke Revalidation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Classification Note
- **Created on:** `2026-05-06`
- **Source:** local-public Docker recovery + rescued web navigation smoke follow-up
- **Why this exists:** browser/runtime validation exposed a mixed failure surface that can be lost easily if not captured together: the local-public Docker stack became unhealthy, which directly caused `530/1033` on `belluga.space`, and the rescued browser suite also exposed web/runtime gaps that still need clean revalidation once the local stack is healthy again.
- **Release-gate status:** not a blocker for the already-completed release promotion, but mandatory post-release hardening before trusting local-public browser validation as a stable source of truth again.

## Context
While rescuing orphaned web smoke coverage in `belluga_now_docker`, the canonical browser target for local-public validation (`https://belluga.space` and `https://guarappari.belluga.space`) stopped serving correctly and returned Cloudflare `530 / 1033`.

Investigation showed this was not one single product bug:

1. **Local Docker runtime drift**
   - `nginx`, `scheduler`, and `worker` were failing with Docker Desktop bind-mount state corruption:
     - `error mounting ... to /var/www ... no such file or directory`
   - the compose file still binds the canonical repo path (`./laravel-app:/var/www`), and recreating those services from the current compose recovered them.
   - this points to stale WSL/Docker Desktop bind-mount state after runtime recycle/disconnect, not a confirmed repo-code regression.

2. **Local tunnel invocation drift**
   - `cloudflared` was being started without loading `.env.local.tunnel`.
   - `docker compose` plain/profile-only startup therefore passed an empty `--token` and `cloudflared` looped on:
     - `Incorrect Usage: flag needs an argument: -token`
   - this directly produced the observed `530 / 1033`.
   - the documented/canonical local-tunnel path (`make up-dev-tunnel` / `docker compose --env-file .env --env-file .env.local.tunnel ...`) restores service and returns `HTTP 200` on both local-public domains.

3. **Post-recovery validation findings**
   - local-public readonly revalidation on `belluga.space` / `guarappari.belluga.space` passed `11/12`.
   - the landlord favicon question was reclassified out of this TODO and moved to `TODO-vnext-landlord-branding-management-ui.md` because it depends on deferred landlord branding-management ownership rather than current hardening.
   - the rescued mutation shards no longer fail on tunnel/runtime startup, but are currently blocked by a dedicated landlord auth hardening slice tracked in `TODO-post-release-landlord-password-credential-source-of-truth-hardening.md`.

This TODO owns restoring trust in local-public runtime validation and then reclassifying the remaining browser/runtime gaps against a healthy local-public target.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-local-public-runtime-revalidation`
- **Direct-to-TODO rationale:** this is one bounded hardening slice: restore deterministic local-public runtime validation and re-run the browser/runtime matrix against the current codebase.

## Contract Boundary
- This TODO owns local-public Docker/tunnel hardening and browser-smoke revalidation only.
- It does not own unrelated product features.
- If reruns expose real app/backend bugs, route the fix to the owning stack/repo and keep this TODO as the validation owner until the matrix is green or explicitly classified.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Docker`, `Local-Public`, `Cloudflared`, `Web-Smoke`, `Runtime-Revalidation`
- **Next exact step:** codify the local-public startup invariants, then rerun the canonical readonly/mutation smoke lanes against `belluga.space` / `guarappari.belluga.space` and classify every remaining failure as stale-test, infra drift, or real runtime bug.

## References
- `README.md`
- `Makefile`
- `docker-compose.yml`
- `tools/flutter/run_web_navigation_smoke.sh`
- `tools/flutter/setup_local_navigation_env.sh`
- `tools/flutter/web_app_tests/deeplink_contract.spec.js`
- `tools/flutter/web_app_tests/otp_auth_public.spec.js`
- `tools/flutter/web_app_tests/navigation.spec.js`
- `tools/flutter/web_app_tests/invite_session_context.mutation.spec.js`
- `tools/flutter/web_app_tests/otp_auth_admin.mutation.spec.js`
- `tools/flutter/web_app_tests/navigation_mutation_shards.json`

## Current Known Snapshot (2026-05-06)
- **Confirmed local Docker causes**
  - `nginx`, `scheduler`, `worker` recovered after recreate from current compose.
  - `cloudflared` fails when `.env.local.tunnel` is not loaded and succeeds when started through the documented local-tunnel command.
- **Confirmed local-public recovery proof**
  - `curl -I https://belluga.space` -> `HTTP/2 200`
  - `curl -I https://guarappari.belluga.space` -> `HTTP/2 200`
- **Confirmed validation blockers**
  - local-public mutation login is blocked by the dedicated landlord auth consistency defect captured in `TODO-post-release-landlord-password-credential-source-of-truth-hardening.md`.
- **Findings now cleared on local-public**
  - `/open-app` Android intent checks passed on local-public readonly rerun
  - tenant-public `/auth/login` promotion-boundary check passed on local-public readonly rerun
  - previous tenant readonly navigation timeout did not reproduce on local-public rerun
  - landlord favicon ownership/fallback was intentionally split to `TODO-vnext-landlord-branding-management-ui.md`

## Scope
- [ ] Establish the canonical local-public startup path as an enforceable runtime invariant.
- [ ] Prevent silent `cloudflared` startup with empty token input.
- [ ] Re-run canonical browser validation against healthy local-public targets:
  - `readonly`
  - `mutation`
  - rescued `otp-auth`
  - rescued `invite-session`
  - rescued deep-link/open-app checks
- [ ] Classify each remaining browser/runtime failure or validation blocker as:
  - stale test/spec,
  - local infra/runtime drift,
  - real Flutter bug,
  - real Laravel/runtime/config bug.
- [ ] Route real code bugs back to the owning repo with explicit follow-up TODO or implementation slice.
- [ ] Document the final local-public bootstrap/runbook so future browser validation cannot silently aim at the wrong target or start an incomplete tunnel profile.

## Out of Scope
- [ ] Reopening completed release promotions.
- [ ] Treating transient local-public runtime incidents as production regressions without evidence.
- [ ] Promoting rescued browser specs wholesale without current-runtime validation.
- [ ] Redesigning Cloudflare tunnel architecture beyond what is needed to restore deterministic local-public validation.

## Dependencies & Sequencing
- [ ] `DEP-01` Local Docker Desktop / WSL bind mounts must remain stable after restart/recreate.
- [ ] `DEP-02` `.env.local.tunnel` must be present and loaded for local-public tunnel runs.
- [ ] `DEP-03` The current Flutter web bundle must be published locally before browser reruns.
- [ ] `DEP-04` Browser reruns must target `belluga.space` / `guarappari.belluga.space`, not `stage` or `main`.

## Definition of Done
- [ ] The local-public stack can be started/restarted deterministically without falling back to incomplete tunnel startup.
- [ ] The cause of the original `530 / 1033` is fully documented and reproducible.
- [ ] The rescued browser lanes are rerun on a healthy local-public target.
- [ ] Every remaining failure or env blocker is explicitly classified and routed.
- [ ] The local-public bootstrap/runbook is updated so future smoke execution uses the correct command/env contract.

## Validation Steps
- [ ] Verify local stack startup via the documented tunnel command and confirm `HTTP 200` for both local-public domains.
- [ ] Run the canonical browser smoke runner against local-public:
  - `bash tools/flutter/run_web_navigation_smoke.sh readonly`
  - `bash tools/flutter/run_web_navigation_smoke.sh mutation`
- [ ] Run the rescued shard lanes against local-public with explicit `NAV_*` targets.
- [ ] Wait for `TODO-post-release-landlord-password-credential-source-of-truth-hardening.md` to repair landlord mutation login, then rerun the blocked mutation shards with the canonical admin credential.
- [ ] Compare results against the current `main` and `stage` runtime only when needed to distinguish local-public drift from broader runtime bugs.
- [ ] Capture the exact startup/run command in the final evidence so future sessions do not repeat the empty-token tunnel path.

## Local CI-Equivalent Suite Matrix
This TODO is not ready for `Local-Implemented`, promotion-lane movement, or any promotable claim until every in-scope row below has been executed locally and passed on the final execution state. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `belluga_now_docker / Orchestration CI/CD :: Preflight Validation` | Local-public startup hardening changes live in the Docker orchestration repo and must preserve environment/script invariants. | `bash .github/scripts/verify_environment_ci.sh` | `Local-Implemented` | `planned` | `.github/scripts/verify_environment_ci.sh` run log | Required when startup/runbook changes touch repo-managed Docker/runtime surfaces. |
| `belluga_now_docker / stage navigation smoke surface (repo-owned Playwright runner)` | This TODO exists specifically to restore trust in the repo-owned browser smoke runner on the healthy local-public target. | `bash -lc \"NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' NAV_DEPLOY_LANE='dev' bash tools/flutter/run_web_navigation_smoke.sh readonly && NAV_LANDLORD_URL='https://belluga.space' NAV_TENANT_URL='https://guarappari.belluga.space' NAV_DEPLOY_LANE='dev' bash tools/flutter/run_web_navigation_smoke.sh mutation\"` | `Local-Implemented` | `planned` | readonly/mutation runner logs on local-public | Mutation reruns may remain blocked by the dedicated landlord auth TODO until that slice is repaired. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Why:** the slice is bounded, but it crosses local runtime bootstrap, tunnel ingress, browser smoke harness, and runtime contract classification across Docker, Flutter, and Laravel boundaries.
