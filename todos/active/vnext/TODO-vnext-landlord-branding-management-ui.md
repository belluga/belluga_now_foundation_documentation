# TODO (VNext): Landlord Branding Management UI

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Authority Note
- This TODO is the deferred owner for **landlord-side branding management UI**. It exists so landlord runtime-branding gaps do not get mixed into local-public hardening or tenant-admin branding work.

## Context
Current branding management flows are tenant-admin oriented. During local-public smoke rescue, landlord-local favicon behavior surfaced as an unresolved product/contract question:

- `https://belluga.space/favicon.ico` may not have a landlord-managed branding asset configured locally.
- The current runtime contract already distinguishes:
  - browser favicon route: `/favicon.ico`
  - PWA/installable icon routes: `/icon/...`
  - runtime branding metadata and public shell ownership

The unresolved issue is not just “serve a file”. The missing product/system owner is a **landlord branding management UI** that can explicitly manage landlord favicon, icon, default image, and related public-shell branding inputs instead of leaving landlord-local behavior dependent on seed state or opaque fallback.

This TODO owns that deferred landlord management surface.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `vnext-landlord-branding-management-ui`
- **Direct-to-TODO rationale:** the deferred boundary is already clear and should stay explicitly owned rather than leaking through post-release hardening notes.

## Contract Boundary
- This TODO owns landlord-facing branding management UI and its runtime-management contract.
- It includes landlord defaults/fallback ownership for favicon/public shell branding.
- It does not own tenant-admin branding editors except where shared runtime branding primitives must be converged deliberately.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `VNext`, `Deferred-Owner`, `Landlord`, `Branding`, `Runtime-Metadata`
- **Next exact step:** when landlord workspace/admin VNext execution becomes current, define the landlord branding-management surface and its runtime persistence/readback contract.

## Scope
- [ ] Define the landlord-side branding management surface.
- [ ] Define landlord ownership of:
  - favicon
  - browser/tab icon defaults
  - landlord public-shell logo/icon assets
  - landlord public-web metadata defaults where applicable
- [ ] Decide canonical fallback behavior when landlord branding assets are absent.
- [ ] Ensure favicon/PWA/default-image contracts stay separate and explicit.
- [ ] Add runtime/browser validation coverage for landlord-managed branding once the UI/contract exists.

## Out of Scope
- [ ] Treating absent landlord-local favicon seed state as an immediate post-release bug.
- [ ] Reopening tenant-admin branding delivery already handled elsewhere.
- [ ] Using post-release hardening TODOs as the long-term owner for landlord branding product decisions.

## References
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/todos/completed/TODO-tenant-admin-settings-branding-editor.md`
- `foundation_documentation/todos/completed/tenant-public-branding-metadata-fallback.md`
- `foundation_documentation/todos/active/vnext/TODO-vnext-account-workspace.md`

## Definition of Done
- [ ] Landlord branding-management UI owner is implemented or decomposed into current execution slices.
- [ ] Favicon/default/fallback rules are explicit and no longer inferred from local seed state.
- [ ] Browser validation around landlord branding uses the final contract, not assumptions.
