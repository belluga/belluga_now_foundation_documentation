# TODO (V1): Tenant Admin Account Form UX Improvements (Image Upload Feedback + Crop)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Flutter  
**Date:** 2026-02-18

## Context
When the user selects an image from the device (avatar/cover) in the Tenant Admin Account form flow, there is no visible feedback and the UI appears to freeze until the image preparation/upload completes.

This is a UX + perceived stability issue, and it also hints we may be doing heavy image decode/resize work on the UI thread (especially noticeable on web).

Also observed: the crop step is not appearing (regression/bug). For avatar and cover, we need a fixed aspect ratio crop UI.

## Scope
- Cover all targets that use the shared tenant-admin image flow:
  - Account Profile avatar (1:1)
  - Account Profile cover (16:9)
  - Static Assets (if they reuse the same image sheet/widget)
- Add an explicit "image is being processed/uploaded" state to the flows (and any reused image widgets/sheets involved).
- Show an indeterminate loading indicator while preparing/uploading the image.
- Prevent the UI from looking stuck:
  - Disable the image action buttons while busy.
  - Keep the rest of the form responsive where safe (no navigation after await; no `Navigator.*`).
- Ensure image preparation does not block the main thread:
  - Move resize/compress/crop preparation to a background isolate when feasible (or yield to event loop on web).
- Fix the crop flow so it reliably appears after selecting a device image:
  - Avatar crop: fixed 1:1
  - Cover crop: fixed 16:9

## Out of Scope (for this TODO)
- Changing backend contracts/endpoints.
- Adding URL-based image ingestion (we intentionally do not save user-provided URLs).
- New image features (multiple images, galleries, etc.).

## Questions To Close
None. Approved and decided.

## Decisions
- Use the existing `StreamValue` patterns:
  - Busy/loading state is controller-owned (ephemeral form state), exposed as `StreamValue<bool>`.
  - Widgets stay pure UI and only consume controller `StreamValue`s.
- Use AutoRoute-only navigation (`context.router.*`).
- Aspect ratios are fixed per use case (avatar 1:1, cover 16:9).
- Platform: web + mobile parity.
- UX: inline spinner on the image component + disable the image actions while busy.
- Crop UI: adopt a web-compatible crop UI (`crop_your_image`) for the device-image flow.

## Implementation Tasks
- [x] ✅ Production‑Ready Identify the image upload entrypoints for Account form (avatar/cover) and map the call chain.
- [x] ✅ Production‑Ready Introduce a `StreamValue<bool>` busy flag in the relevant controller(s) for image ingestion/upload.
- [x] ✅ Production‑Ready Update the UI widgets/sheets to render an indeterminate progress indicator when busy.
- [x] ✅ Production‑Ready Ensure image preparation (decode/resize/compress) does not block the UI thread:
  - Prefer isolate-based processing for heavy work.
  - Add safe yields on web if isolate is not viable for a specific step.
- [x] ✅ Production‑Ready Diagnose and fix why crop UI is not appearing.
- [x] ✅ Production‑Ready Enforce fixed crop aspect ratio per target (avatar 1:1, cover 16:9).
- [x] ✅ Production‑Ready Add tests:
  - Widget test: when busy flag is true, the loader is visible and the image action is disabled.
  - Unit test (if feasible): controller toggles busy state correctly around ingestion/upload.
  - Widget test: after selecting an image, crop UI is shown (or the crop step is reached) for avatar and cover.

## Definition Of Done
- [x] ✅ Production‑Ready Selecting an image shows immediate visual feedback (spinner/progress) and the UI no longer appears frozen.
- [x] ✅ Production‑Ready While busy, repeated taps do not enqueue duplicate uploads.
- [x] ✅ Production‑Ready After selecting an image, crop UI appears and uses the correct fixed aspect ratio for avatar and cover.
- [x] ✅ Production‑Ready No `Navigator.*` usage introduced (AutoRoute-only).
- [x] ✅ Production‑Ready `fvm flutter analyze` clean.
- [x] ✅ Production‑Ready Added/updated tests are green.

## Validation Steps
- `fvm flutter analyze`
- `fvm flutter test test/presentation/tenant_admin/...` (targeted tests added/updated by this TODO)
