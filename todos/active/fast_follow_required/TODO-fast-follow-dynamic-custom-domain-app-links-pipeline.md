# TODO (Fast Follow): Dynamic Custom-Domain Verified App Links Pipeline

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. This TODO owns the durable pipeline for verified native app links on tenant custom domains at scale. It does not block the current one-tenant Android release, because that launch can use an explicit manual build-time domain list in the existing flavor settings.
**Owners:** Delphi (Flutter/Android), Backend Team, DevOps/Release Pipeline
**Goal:** make tenant custom domains open the installed app directly as verified Android App Links by promoting tenant-owned domains into the native build/release pipeline, while Laravel continues to serve dynamic `assetlinks.json` for the active host.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

Tenants can use the canonical Belluga subdomain and may also add their own public web domain. Laravel can already serve host-resolved association files (`/.well-known/assetlinks.json`) dynamically, but Android App Links still require each verified host to be inside the APK manifest scope at build time. A domain added after an APK is installed cannot become a verified direct-open App Link from `assetlinks.json` alone.

This TODO exists to close that gap with a release-aware pipeline after the initial single-tenant launch: when a tenant custom domain must support direct native opening, the domain must be captured in build-time native link configuration, included in generated Android manifest artifacts, released, and validated against Laravel's dynamic association endpoint.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** the requirement is a single technical/product capability: direct native opening for tenant custom domains after domain onboarding.
- **Direct-to-TODO rationale:** the product constraint is already known and narrow. The ambiguity is implementation sequencing, not product discovery.

## Contract Boundary

- This TODO defines **WHAT** must be delivered and what counts as done.
- Future execution planning defines **HOW** the pipeline generates and validates native link configuration.
- Any change that weakens the direct custom-domain open requirement must update this TODO and the canonical web-to-app policy before implementation.

## Delivery Status Canon

- **Current delivery stage:** `Pending`
- **Qualifiers:** `Fast-Follow`, `Cross-Stack`, `Pipeline-Required`, `Native-Release-Required`
- **Next exact step:** after the one-tenant launch, replace the interim manual flavor-property domain list with a pipeline-managed native link registry.

## Scope

- [ ] Add a canonical build-time native link domain registry per tenant/flavor/lane.
- [ ] Include canonical Belluga domains and approved tenant custom domains in generated Android App Link manifest entries.
- [ ] Keep Laravel as the canonical dynamic `assetlinks.json` provider for every host included in the native build.
- [ ] Mark tenant custom domains with native-link status (`not_requested`, `pending_native_release`, `released_pending_verification`, `verified`, `failed`) or equivalent release-state tracking.
- [ ] Add release/pipeline validation that fails when a domain is in the native-link registry but Laravel cannot serve valid association payload for that host.
- [ ] Validate direct installed-app opening from at least one custom tenant domain on Android 12+ real device.
- [ ] Define iOS parity handling or explicitly hand off custom-domain Universal Links to the iOS fast-follow TODO.

## Out of Scope

- [ ] Claiming runtime-dynamic custom-domain verification without a new native release.
- [ ] Treating `android:host="*"` plus `autoVerify=true` as verified App Links.
- [ ] Replacing Laravel dynamic association endpoints.
- [ ] Reopening the web-to-app promotion policy or `/open-app` handoff contract.

## Interim Compatibility Decision

- [ ] For the initial one-tenant Android release, a manual build-time list in the existing flavor properties file is acceptable.
- [ ] The manual list must be explicit, lane/flavor scoped, and understood to require a rebuild/reinstall/release for every added custom domain.
- [ ] The manual list must not live as hardcoded domain XML in `android/app/src/main/AndroidManifest.xml`; it should be read from tenant build settings and materialized into generated native artifacts.
- [ ] Runtime tenant settings may expose domain state, but Android verification must consume a build-time snapshot.
- [ ] Current interim flavor source is `flutter-app/android/keystores/guarappari.properties`, using `appLinkHosts` for the custom production host and the tenant subdomain hosts needed across active test/release lanes.

## Definition of Done

- [ ] Tenant custom domains that require direct native opening are represented in a versioned build-time native link registry.
- [ ] Android build generates or selects manifest entries for every approved host without hand-editing `android/app/src/main/AndroidManifest.xml` per domain.
- [ ] Laravel serves valid `assetlinks.json` for every host included in the generated Android manifest.
- [ ] Release pipeline blocks or clearly marks native-link release as unsafe when registry, manifest, tenant domain state, and association payload diverge.
- [ ] Real Android device validation proves direct `https://<custom-domain>/<allowed-path>` opens the installed app without relying on `/open-app`.
- [ ] Documentation explains that adding a new custom domain requires native release propagation before verified direct app opening is guaranteed.

## Validation Steps

- [ ] Android static/platform test verifies generated manifest hosts include the configured custom domain list and do not rely on a single hardcoded host.
- [ ] Gradle/CI validation runs manifest merge for affected flavors and inspects merged manifest output.
- [ ] Backend/Laravel validation requests `https://<custom-domain>/.well-known/assetlinks.json` and verifies package + signing fingerprint for the released artifact.
- [ ] Real-device Android validation on API 31+ verifies direct custom-domain link opens `com.guarappari.app/.MainActivity`.
- [ ] Negative validation proves a domain absent from the build-time registry does not claim verified direct-open support.

## Execution Lane Tracking

- **Local implementation branches:** `<pending>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence

| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Dynamic custom-domain native link pipeline | `<pending>` | `<pending>` | `<pending>` | `<pending>` | `pending` |

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Planned decision promotion targets:**
  - Web-to-app promotion policy: native verified custom-domain rule.
  - Flutter client experience module: Android App Links build/release contract.
  - Tenant admin module: custom-domain native-link status semantics.
- **Module decision consolidation targets:**
  - Same as planned decision promotion targets.

## Decisions (Resolved Before Freeze)

- [ ] `D-01` Laravel dynamic `assetlinks.json` is necessary but insufficient for arbitrary custom-domain direct App Links unless the host is also in the native manifest scope.
- [ ] `D-02` Direct app opening for tenant custom domains is a required capability and must be release/pipeline-backed.
- [ ] `D-03` The interim manual list is permitted only as build-time configuration and requires rebuild/release for changes.

## Questions To Close

- [ ] Should the short-term registry live in an existing per-tenant build settings file or in a new non-secret native link config file?
- [ ] Should iOS custom-domain Universal Links be delivered in this TODO or handed off to `TODO-ios-universal-links-production-validation.md`?
- [ ] What is the minimum acceptable release-state UI/API for tenant admins when a custom domain is live on web but not yet native-link verified?

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Android App Links cannot be expanded to arbitrary new hosts by `assetlinks.json` alone after the APK is installed. | Android App Links documentation: dynamic rules cannot expand manifest scope; `IntentFilter` host/authority must match. | Pipeline/native-release work may be unnecessary. | High | Keep as Assumption |
| `A-02` | Laravel can serve association payloads dynamically for tenant hosts once tenant/domain/settings state is correct. | Existing Laravel web routes and deep-link package contract for `/.well-known/assetlinks.json`. | Backend association generation must be added before pipeline validation can pass. | High | Keep as Assumption |

## Execution Plan

### Touched Surfaces

- Android native manifest generation/build settings.
- Laravel deep-link association validation and domain state.
- Tenant admin domain/native-link status documentation.
- Release pipeline validation scripts.

### Ordered Steps

1. Freeze the build-time domain registry format and ownership.
2. Implement generated Android manifest entries per flavor from the registry.
3. Add validation that merged manifests contain expected hosts and no stale hosts.
4. Add Laravel association validation against configured hosts.
5. Add release-state tracking for custom domains that require native release propagation.
6. Run real-device Android direct-link validation on a custom domain.
7. Consolidate final decisions into module/policy docs.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** the failure mode is deterministic: a custom domain not present in the manifest cannot be verified/opened directly.
- **Fail-first targets:** manifest-generation test for a custom domain from build settings, Laravel association validation for that host, and ADB/direct-link validation once runtime is available.

### Runtime / Rollout Notes

- Adding a new verified custom domain requires a native build and release propagation before Android direct-open behavior can be guaranteed.
- Until release propagation completes, `/open-app` remains the safe handoff path for installed-app opening from dynamic domains.
