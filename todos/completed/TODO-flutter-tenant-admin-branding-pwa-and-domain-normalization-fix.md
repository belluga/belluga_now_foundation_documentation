# TODO: Tenant Admin Branding + Domain Normalization Regression Fix
**Status:** Completed

## Scope
- Fix P2: `pwaIconUrl` must be populated from environment branding payload.
- Fix P1: selected tenant domain normalization must preserve explicit scheme/port.

## Tasks
- [x] Patch `tenant_admin_settings_repository.dart` to map `pwaIconUrl` from environment payload (with robust key fallbacks).
- [x] Patch tenant selection normalization to preserve explicit origin and host:port values.
- [x] Ensure tenant matching logic still resolves selected tenant with/without scheme.
- [x] Add/update tests for both regressions.
- [x] Run focused tests.
