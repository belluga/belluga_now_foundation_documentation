# TODO (VNext): Tenant Admin Firebase App Config Import

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Active  
**Owners:** Flutter Team + Laravel Team + Tenant Admin / Operations  
**Objective:** allow tenant-admin/runtime operators to populate the Firebase app configuration surface by uploading a canonical `google-services.json`, with deterministic parsing, validation, and preview before persistence.

---

## Context

The current tenant-admin Firebase settings surface expects manual entry of:

- `projectId`
- `appId`
- `apiKey`
- `messagingSenderId`
- `storageBucket`

That works for the current fast-follow invite/push lane, but it is operationally brittle and easy to mis-key. For Android-first tenants such as `guarappari`, the canonical source of those public app values already exists in `google-services.json`.

This TODO exists to improve the admin/runtime setup UX without blocking the current delivery lane. The current feature work can proceed with manual entry; this TODO is the future ergonomic hardening of that configuration path.

## Scope

- Add a tenant-admin import flow for `google-services.json`.
- Parse and map the canonical Firebase app values into the existing Firebase admin fields:
  - `project_info.project_id` -> `projectId`
  - `client[].client_info.mobilesdk_app_id` -> `appId`
  - `client[].api_key[].current_key` -> `apiKey`
  - `project_info.project_number` -> `messagingSenderId`
  - `project_info.storage_bucket` -> `storageBucket`
- Validate that the imported Android app package matches the intended tenant app identity (for example `com.guarappari.app`) before allowing persistence.
- Show a human-readable preview/confirmation step before save.
- Preserve the current manual-entry fallback for operators who do not use the import path.

## Out of Scope

- Import of Firebase Admin SDK service-account JSON / private server credentials.
- iOS `GoogleService-Info.plist` ingestion.
- General multi-platform Firebase project orchestration.
- Auto-provisioning Firebase projects or apps from Belluga admin.

## Tasks

- [ ] ⚪ Pending — Define the canonical backend import endpoint/validation contract for `google-services.json`.
- [ ] ⚪ Pending — Add Laravel parsing/validation for the uploaded JSON and return normalized preview fields.
- [ ] ⚪ Pending — Add Flutter tenant-admin UI for file selection/upload, preview, confirmation, and field hydration.
- [ ] ⚪ Pending — Add validation that rejects malformed files, missing required keys, ambiguous multi-client payloads, or package-name mismatch against the target tenant app identity.
- [ ] ⚪ Pending — Preserve manual field editing as a supported fallback path.
- [ ] ⚪ Pending — Add regression tests for valid import, invalid JSON, missing keys, and package mismatch.

## Acceptance Criteria

- [ ] ⚪ Pending — A valid `google-services.json` can populate the Firebase public app settings without manual copy/paste.
- [ ] ⚪ Pending — Operators see the parsed values before save and can cancel if the file targets the wrong app/project.
- [ ] ⚪ Pending — The import path fails clearly when the package/app does not match the intended tenant app identity.
- [ ] ⚪ Pending — Manual entry remains available and unchanged as fallback.
- [ ] ⚪ Pending — The import flow does not imply or perform server-credential import.

## Notes

- `google-services.json` is only a source for the public Firebase app configuration used by the client bootstrap.
- FCM server delivery credentials remain a separate concern and should continue to use the existing credential surface until a distinct future owner explicitly replaces it.
- This TODO is intentionally non-blocking for the current invite/push fast-follow lane.
