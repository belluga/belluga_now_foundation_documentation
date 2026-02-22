# TODO (WEB): Stabilize Navigation Validation Bootstrap Timing

**Status:** Completed  
**Owners:** Web QA / DevOps  
**Date:** 2026-02-18

## Problem
Playwright navigation validation intermittently failed during bootstrap because `expect(body).toBeVisible()` used a short default timeout, causing false negatives while the app was still initializing.

## Resolution
Increase the `body` visibility expectation timeout to 20s for bootstrap validation.

## Evidence
- Commit: `belluga_now_web@e90ce96` `🧪 test(web): allow 20s for body visibility during bootstrap`

