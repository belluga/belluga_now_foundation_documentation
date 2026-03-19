# TODO (VNext): iOS Universal Links Production Validation (Guarappari)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active
**Owners:** Delphi (Flutter/Product) + Backend Team + iOS Runtime Validation
**Goal:** Complete iOS-specific production verification for Universal Links and AASA payload fidelity, outside MVP launch-critical Android/Web closure.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-invite-deeplink-identity-first-delivery.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-deeplink-host-resolved-well-known.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `laravel-app/app/Application/Branding/DeepLinkAssociationService.php`
- `laravel-app/tests/Api/v1/Tenants/Branding/ApiV1WellKnownAssociationTest.php`

---

## Scope
- Validate installed-app iOS Universal Link open behavior for `https://guarappari.belluga.space/invite?...`.
- Validate browser fallback behavior when app is not installed.
- Validate `/.well-known/apple-app-site-association` returns canonical non-empty `applinks.details` payload for guarappari.
- Record durable evidence artifacts and close iOS-deferred MVP checklist items.

## Out of Scope
- Android App Links validation (already closed in MVP stream).
- New deep-link route design.
- New invite product behavior.

---

## Implementation Tasks
- [ ] ⚪ Provision/confirm canonical iOS app identifiers and credentials in tenant settings (`app_ios`, `team_id`, `paths`).
- [ ] ⚪ Validate `https://guarappari.belluga.space/.well-known/apple-app-site-association` returns canonical `appID` (`<TEAM_ID>.<BUNDLE_ID>`) and expected route paths.
- [ ] ⚪ Run manual installed-app Universal Link validation on iOS runtime (device/simulator supported by lane).
- [ ] ⚪ Run manual not-installed fallback validation preserving original invite query parameters.
- [ ] ⚪ Persist evidence artifact in `foundation_documentation/artifacts/` and link it from this TODO.
- [ ] ⚪ Back-propagate closure references into MVP TODOs that deferred iOS verification.

---

## Acceptance Criteria
- [ ] ⚪ iOS Universal Link opens native app when installed.
- [ ] ⚪ Browser fallback works when app is absent and preserves deep-link query context.
- [ ] ⚪ AASA payload is canonical and non-empty for guarappari production host.
- [ ] ⚪ MVP TODOs that deferred iOS are updated with explicit closure evidence references.

