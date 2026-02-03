# Documentation: TODO — Account Profile Image Preview Stability
**Version:** 1.0
**Status:** Draft

## 1. Objective
Keep the local preview visible after upload until the remote image URL is confirmed healthy, and show a clear error placeholder when the remote image fails.

## 2. Scope
- Account Profile edit screen:
  - Preserve local preview after auto-save.
  - Swap to remote image only after it loads successfully.
  - Show explicit error placeholder when the remote image fails.
- Account detail preview (if applicable): keep layout stable with error placeholder.

## 3. Out of Scope
- Backend changes.
- Image compression or upload flow changes.

## 4. Decisions
- Use remote image load success to decide when to clear the local preview.
- On remote error, display a visible error placeholder (not silent fallback).

## 5. Risks / Open Questions
- If remote never loads, local preview remains until user leaves screen.

## 6. Definition of Done
- After upload, UI does not revert to placeholder until remote image is verified.
- Remote image error is clearly shown and layout remains stable.
- `fvm flutter analyze` passes.

## 7. Validation Steps
- Upload avatar/cover and confirm preview persists until remote loads.
- Force 404 and confirm error placeholder is shown.
- Run `fvm flutter analyze`.

