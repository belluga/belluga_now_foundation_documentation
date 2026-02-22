# TODO (DEVOPS): Unblock Web-App Stage -> Main Promotion Conflicts

**Status:** Completed  
**Owners:** DevOps  
**Date:** 2026-02-18

## Problem
`belluga_now_web` had frequent merge conflicts promoting `stage -> main` because `main` contained independent web bundle publishes, so generated artifacts diverged (`index.html`, `main.dart.js`, service worker, metadata).

## Resolution
For the immediate unblock, `belluga_now_web:main` was aligned to exactly match `belluga_now_web:stage`, removing all diffs and conflicts for promotion.

## Evidence
- `belluga_now_web` commit on `stage`: `1556216` (`Merge pull request #98 from belluga/ci/flutter-web-stage`)
- After alignment, `main` and `stage` compare as identical (`ahead_by=0`, `behind_by=0`).

## Follow-Up (Policy)
To prevent recurrence, avoid direct bundle publish commits to `main`. Prefer promotion-only semantics (`dev -> stage -> main`) for the deploy repo.

