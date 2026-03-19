# TODO (VNext): Align Mutation Agenda Assertion with Canonical Home Query

**Status:** Completed  
**Owners:** Web QA / Platform  
**Date:** 2026-03-18

## Problem
`@mutation tenant agenda UI state matches tenant agenda API payload` raised false negatives by asserting Home empty-state against any successful `/api/v1/agenda` response captured during bootstrap.

In stage, auxiliary agenda fetches can return items that do not represent the Home feed state. This made the gate fail even when Home was correctly empty.

## Resolution
Scope the mutation parity assertion to the canonical Home agenda request only:
- `page_size=10`
- `past_only=0` (or absent default)
- `confirmed_only=0` (or absent default)
- no search query

Keep origin and payload assertions tied to this canonical request set before deciding whether Home empty-state must disappear.

## Evidence
- File: `tools/flutter/web_app_tests/navigation.spec.js`
- Test: `@mutation tenant agenda UI state matches tenant agenda API payload`
