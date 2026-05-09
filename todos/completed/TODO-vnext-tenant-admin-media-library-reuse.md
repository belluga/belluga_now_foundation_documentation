# TODO (VNext): Tenant Admin Media Library Reuse

**Status:** Completed  
**Owners:** Backend Team + Delphi (Flutter) + Product  
**Date:** `2026-04-18`

## Closure Note
The original TODO overstated the boundary by treating future gallery/library capability as part of the reuse problem. The intended reuse concern is already materially delivered through the shared tenant-admin image-source/picker flow used across admin media slots.

## Confirmed Evidence
- Shared tenant-admin image source sheet exists and is reused across admin flows:
  - `../flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_image_source_sheet.dart`
- Shared canonical upload field exists for scoped admin media slots:
  - `../flutter-app/lib/presentation/tenant_admin/shared/widgets/tenant_admin_canonical_image_upload_field.dart`
- The shared picker/source flow is already consumed by multiple admin surfaces instead of being reimplemented per screen:
  - `../flutter-app/lib/presentation/tenant_admin/settings/screens/tenant_admin_settings_visual_identity_screen.dart`
  - `../flutter-app/lib/presentation/tenant_admin/static_assets/screens/tenant_admin_static_asset_create_screen.dart`
  - `../flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_event_form_screen.dart`

## Residual Note
- Future gallery/library capability remains separate and should not be conflated with this closed reuse boundary.
- Public/admin multi-image gallery work remains tracked in:
  - `foundation_documentation/todos/active/vnext/TODO-vnext-account-profile-media-gallery.md`
