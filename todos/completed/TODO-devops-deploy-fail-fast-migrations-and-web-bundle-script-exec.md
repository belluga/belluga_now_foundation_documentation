# TODO (DEVOPS): Fail-Fast Deploy on Migration Errors + Executable Web Bundle Script

**Status:** Completed  
**Owners:** DevOps  
**Date:** 2026-02-18

## Objective
- Ensure deploy fails if Laravel migrations fail (no silent success with unapplied schema changes).
- Ensure `tools/flutter/build_web_bundle.sh` is executable as documented.

## Deliverable
- `belluga_now_docker`:
  - `.github/scripts/deploy_stage_over_ssh.sh` updated to explicitly return non-zero when:
    - `docker compose up` fails
    - `wait_for_laravel_artisan` fails
    - landlord migrations fail
    - tenant migrations fail (when tenants exist)
  - `tools/flutter/build_web_bundle.sh` committed as executable (`100755`).

## Evidence
- Commit: `belluga_now_docker@1e2228f` `🚀 fix(deploy): fail fast when compose up / migrations fail`

## Notes
This closes the badge findings:
- Stop deploy when migration commands fail.
- Make the new web bundle script executable.

