# TODO (VNext): belluga_form_validation Package Hardening & Publish Path

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owners:** Flutter Team  
**Objective:** Harden the internal `belluga_form_validation` package after V1 adoption so it can become a stable, reusable package boundary and, if still desired, be published outside the app repository later.

---

## A) Scope
- Audit the V1 package after multiple form adoptions and remove tenant-admin-first assumptions.
- Stabilize the package public API:
  - exception types
  - target resolver contracts
  - resolved error state API
  - reusable widgets/helpers
- Expand package documentation:
  - README examples for multiple feature types
  - migration/adoption checklist
  - error-mapping conventions
- Broaden adoption beyond Tenant Admin Account Create.
- Restore the intended first-error navigation behavior: current pre-MVP forms still do not scroll/navigate to the first invalid field after validation is applied.
- Decide the long-term publish target:
  - remain internal local package
  - move to shared mono-repo package lane
  - publish externally/private registry

## B) Out of Scope
- Initial V1 onboarding adoption work.
- Backend validation contract redesign.
- Cross-ecosystem publishing decision unless the package is already hardened and stable.

## C) Tasks
- [ ] ⚪ Review V1 package API after at least 2-3 adopters.
- [ ] ⚪ Remove feature-specific assumptions from package surface and docs.
- [ ] ⚪ Add broader test coverage for wildcard/group/global resolution across multiple forms.
- [ ] ⚪ Add package examples for dynamic/repeatable forms.
- [ ] ⚪ Fix the current package/app integration bug where validation does not scroll or navigate to the first invalid target after applying errors.
- [ ] ⚪ Decide package distribution target and required metadata/versioning.
- [ ] ⚪ If publishing remains desired, add the necessary changelog, license/ownership decision, and release process documentation.

## D) Definition of Done
- [ ] ⚪ Package API is stable enough to support multiple app areas without ad-hoc changes.
- [ ] ⚪ First-invalid-target navigation/scroll behavior works deterministically in real adopted forms.
- [ ] ⚪ README is sufficient for future adoption work without rediscovering the architecture.
- [ ] ⚪ Publish/no-publish path is explicitly decided.
- [ ] ⚪ If publish is approved, the package has the minimum release metadata/process to do it safely.

## E) Notes
- This VNext TODO depends on the V1 package-establishment slice in `foundation_documentation/todos/completed/TODO-flutter-forms-422-validation-wrapper.md`.
- Do not pull this scope into the V1 onboarding slice unless a blocker makes it strictly necessary.
