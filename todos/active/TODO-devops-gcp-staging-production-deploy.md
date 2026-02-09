# TODO: AWS Lightsail Staging + Production Deploy (GitHub Actions + Docker Compose)
**Version:** 1.0
**Owner:** Delphi
**Date:** 2026-02-04

## Goal
Establish the simplest possible CI/CD deployment flow for two environments (staging + production) hosted on AWS Lightsail, using GitHub Actions to deploy Docker Compose stacks with minimal operational burden.

## Scope
- Define the deployment process and CI/CD workflow for:
  - Staging VPS (branch `stage`)
  - Production VPS (branch TBD)
- Use SSH-based deploy from GitHub Actions.
- Keep runtime stack aligned with existing `docker-compose.yml`.
- Prefer managed services to reduce ops burden (MongoDB Atlas).
- Document required GitHub Secrets and server prerequisites.

## Out of Scope
- Editing DNS or provisioning real domains (tracked as pending decisions).
- Changing application code in submodules.
- Re-architecting Docker services or ingress beyond minimal deploy needs.
- Migrating data between environments.

## Decisions (Confirmed)
- Hosting provider: **AWS Lightsail**.
- Simplicity is top priority; avoid complex infra services.
- Cloudflare will be used as a CDN/proxy.
- Two separate VPS instances (staging + production).
- Flutter should connect via **domain** for all non-local environments (staging + production).
- No release versioning/tags in initial workflow (branch-based deploy only).
- Production deploy branch name: `main`.
- Dev branch exists for local team management only (no server deployments).
- Database hosting: MongoDB Atlas.
- Flutter web bundle built in CI and committed to `web-app` (same environment level).
- Web bundle includes metadata linking it to the Flutter commit SHA.
- Deploys are triggered only by the orchestration repository (`belluga_now_docker`) branch updates.
- Delivery will run in phases:
  - Phase 1: build + compatibility validation only (no server deployment).
  - Phase 2: enable staging/production deployment with SSH secrets and host keys.
- Branch lanes are mapped by environment across repositories (`dev`, `stage`, `main`) and enforced in docker-repo CI by submodule branch alignment checks.
- Compatibility gate is bidirectional:
  - new `laravel-app` commit must pass against current `web-app` commit;
  - new `web-app` commit must pass against current `laravel-app` commit.

## Decisions (Pending)
- Domain names for staging and production.
- SSH host keys, hostnames, and deploy paths for staging/production (deferred until deploy phase).

## Assumptions
- Servers will have Docker + Docker Compose installed.
- GitHub Actions can SSH into both servers using deploy keys.
- No local tunnel is used in this project; stage/production use hosted domains.

## Deliverables
- GitHub Actions workflow file(s) for staging + production deploy.
- Documentation updates (README or foundation docs) for:
  - Deployment process
  - Required secrets
  - Server setup checklist
- Phase 1 workflow deliverables:
  - Docker-repo preflight checks on `dev`/`stage`/`main` with no deploy job.
  - Submodule lane alignment + web/flutter metadata compatibility gates.
  - Explicit deferral of SSH secret setup until Phase 2.

## Planned Changes (Detailed, No Implementation Yet)
### Flutter integration tests against local backend (CI)
- Add runtime overrides so integration tests can target a local backend via domain:
  - `LANDLORD_DOMAIN` (string) to replace `belluga.app` in tests.
  - `API_SCHEME` (string) to allow `http` in CI (default remains `https`).
- Update `BellugaConstants` or `AppDataBackend` to read the overrides via `--dart-define`.
  - Default behavior must remain unchanged for production (domain + https).
  - Integration tests must be able to pass `--dart-define=LANDLORD_DOMAIN=local.test` and `--dart-define=API_SCHEME=http`.
- Ensure `AppDataBackend` can accept a `baseUrl` override (or derive it from the defines).
- Update integration test bootstrap to use the real backend when desired (no mock swap).

### CI domain routing for local backend
- In GitHub Actions runners, map the chosen test domain to localhost:
  - Example: add `127.0.0.1 local.test` to `/etc/hosts`.
- Ensure Laravel resolves tenant via domain (or `X-App-Domain` if required).

### Laravel CI (Mongo local)
- CI must start a local MongoDB service with replica set enabled (to match production expectations).
- Use CI env vars to point Laravel to local Mongo (not Atlas) for tests.
- Ensure migrations or tenant seed steps run before integration tests.

### Docker repo compatibility gate (pre-deploy)
- Deploy pipeline should validate that the `web-app` bundle metadata references the same `flutter-app` commit.
- Fail deploy if metadata does not match, to prevent cross-repo drift.
- Deploy pipeline in docker repo must run compatibility checks for the exact submodule pair pinned in the PR/branch.
- Submodule repositories (`laravel-app`, `web-app`) produce candidate versions; they do not deploy directly.
- If either candidate fails compatibility tests in the docker repo, deployment is blocked until the pair is green.
- `dev` lane runs validation only (no deployment job). `stage` and `main` lanes run validation + deployment.
- During Phase 1, all branches (`dev`, `stage`, `main`) run validation only.
- Deployment jobs are enabled only in Phase 2 after secrets/hosts are defined.

### Web bundle metadata
- Web build pipeline should emit a metadata file (e.g., `web-app/build_metadata.json`) containing:
  - `flutter_git_sha`
  - `build_time_utc`
  - `source_branch`
- Docker repo will compare `flutter_git_sha` to the pinned `flutter-app` submodule commit.

## Definition of Done
- [ ] ⚪ Pending `dev` branch runs CI validation only and never deploys.
- [ ] ⚪ Pending Phase 1 complete: `dev`/`stage`/`main` run validation-only checks with no deploy jobs.
- [ ] ⚪ Pending Phase 2 complete: staging deploy runs automatically on `stage` branch.
- [ ] ⚪ Pending Phase 2 complete: production deploy runs on the agreed production branch (or manual approval).
- [ ] ⚪ Pending Phase 2 complete: only docker-repo branch updates trigger deployments (`stage` -> staging, `main` -> production).
- [ ] ⚪ Pending GitHub Environments configured with required secrets.
- [ ] ⚪ Pending Server prerequisites documented (Docker, Compose, repo path, SSH keys).
- [ ] ⚪ Pending Rollback guidance documented (revert commit + redeploy).
- [ ] ⚪ Pending Deploy pipeline validates `web-app` metadata matches the pinned `flutter-app` commit.
- [ ] ⚪ Pending Deploy pipeline blocks on failed Laravel/Web compatibility for the pinned submodule pair.

## Validation Steps
- [ ] ⚪ Pending Push to `dev` and verify CI runs preflight checks only (no deploy jobs).
- [ ] ⚪ Pending Push to `stage` and verify CI runs validation-only checks (no deploy jobs).
- [ ] ⚪ Pending Push to `main` and verify CI runs validation-only checks (no deploy jobs).
- [ ] ⚪ Pending Phase 2: run staging deploy workflow and confirm containers are up.
- [ ] ⚪ Pending Phase 2: run production deploy workflow and confirm containers are up.
- [ ] ⚪ Pending Phase 2: verify NGINX responds with the expected environment.
- [ ] ⚪ Pending Validate that a Laravel-only submodule bump fails deployment if current web version is incompatible.
- [ ] ⚪ Pending Validate that a web-only submodule bump fails deployment if current Laravel version is incompatible.

## Questions to Close
1. Domain names for staging and production (when available).

## Notes
- Domains are intentionally deferred; local tunnel setup is out of scope for this project.
- If Atlas is used, `laravel-app/.env` must point to Atlas connection string per environment (not committed).
- 2026-02-09: Phase 2 implementation started in `.github/workflows/orchestration-ci-cd.yml` with `Deploy Stage` job (push to `stage` after preflight).
- 2026-02-09: Added `.github/scripts/deploy_stage_over_ssh.sh` for idempotent remote deploy using pinned submodule SHAs.
- 2026-02-09: Runtime validation on real stage host completed (containers healthy + `/api/v1/environment` returning 200 from host after deploy run `21845018042`).
- 2026-02-09: Public ingress validation (`belluga.app`) remains pending firewall/security-group confirmation for inbound `80/443`.
