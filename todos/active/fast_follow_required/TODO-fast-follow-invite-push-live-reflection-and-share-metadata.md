# TODO (Fast Follow): Invite Push Delivery, Live Reflection, and Share Metadata

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Active. This TODO owns the missing delivery loop for direct invite notifications and the public-share metadata contract for invite landing URLs.
**Owners:** Delphi (Flutter/Laravel) + Tenant Admin / Operations
**Goal:** make direct invites behave as a complete product loop: direct recipient receives push, an already-open app reflects the new invite without manual refresh, and `/invite?code=...` resolves production-safe share metadata/OG image for external sharing.

---

## Artifact Identity

- **Artifact type:** `tactical_execution_contract`

## Context

The current invite stack is functionally incomplete for production behavior:

- direct invites are persisted, but invite creation is not bridged into the push subsystem;
- the backend already exposes invite SSE deltas, but Flutter does not consume `/invites/stream`;
- mobile Flutter can already upsert invite payloads when a compatible push arrives, but that path is only partial because the push is never authored automatically by invite creation;
- web invite landing (`/invite?code=...`) already renders OG/Twitter metadata through the tenant public shell, but the current invite preview path still permits placeholder/generic metadata instead of a guaranteed share-safe branded preview.

Firebase/admin configuration is also a real dependency, but it is not the only blocker. Even with correct Firebase credentials and tenant settings, the product loop remains incomplete until the backend and client gaps below are implemented.

## Framing Source & Story Slice

- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `ST-01`
- **Why this is the right current slice:** this is one cohesive production capability: inbound invite delivery and immediate reflection for the recipient, including the public invite-share surface that should advertise the same invite context correctly.
- **Direct-to-TODO rationale:** scope is already diagnosis-bounded and technically specific; the missing work is implementation and validation, not product discovery.

## Contract Boundary

- This TODO defines **WHAT** the invite delivery/share loop must do and what counts as done.
- It does not reopen the approved invite domain model, occurrence-scoped invite targeting, or the Android-first web-to-app posture.
- It may update canonical docs/contracts where current behavior is under-specified, but it must not silently change invite policy.

## Delivery Status Canon

- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Fast-Follow`, `Cross-Stack`, `Config-Dependent`, `Production-Visible`
- **Next exact step:** provision the tenant Firebase runtime/credential inputs, then implement the backend invite-to-push bridge, the Flutter invite realtime consumer, and the public invite metadata hardening in one bounded lane.

## Complexity / Execution Profile

- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `laravel + flutter + foundation docs + tenant admin runtime validation`

## Canonical Module Anchors

- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Planned decision promotion targets:**
  - Invite & Social Loop module: delivery + reflection semantics for inbound invites
  - Flutter Client Experience module: realtime/push reflection posture
  - Tenant Admin module: Firebase/push operational readiness expectations

## Scope

- Add the canonical backend bridge so `POST /invites` can trigger a recipient-targeted `invite_received` push when tenant push is configured and the recipient has an eligible registered device token.
- Deliver app-open automatic reflection for new inbound invites without requiring manual reload.
- Ensure `/invite?code=...` share URLs resolve production-safe metadata and OG image/title/description for external sharing.
- Freeze the minimum tenant-admin / Firebase operational checklist required for the feature to work end-to-end.
- Produce evidence that separates configuration blockers from development blockers.

## Out of Scope

- General web push enablement. Current Flutter web runtime explicitly skips push initialization.
- Reopening invite targeting/attribution policy or the occurrence-scoped invite model.
- Broad iOS multi-app Firebase redesign. Current runtime/admin schema remains Android-first unless a dedicated iOS TODO reopens it.
- Reworking generic push authoring UX beyond what is required for deterministic invite delivery.

## References

- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/endpoints_mvp_contracts.md`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteMutationService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Application/Mutations/InviteShareService.php`
- `laravel-app/packages/belluga/belluga_invites/src/Http/Api/v1/Controllers/InviteRealtimeStreamController.php`
- `laravel-app/app/Application/PublicWeb/PublicWebMetadataService.php`
- `laravel-app/app/Http/Controllers/TenantPublicShellController.php`
- `laravel-app/app/Application/PublicWeb/FlutterWebShellRenderer.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/TenantFirebaseSettingsRequest.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Requests/PushCredentialRequest.php`
- `flutter-app/lib/application/application_contract.dart`
- `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
- `flutter-app/lib/infrastructure/repositories/push/push_payload_upsert_mixin.dart`
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/schedule_backend/laravel_schedule_backend.dart`
- `flutter-app/test/infrastructure/repositories/invites_repository_push_payload_test.dart`

## Decision Baseline (Frozen 2026-05-09)

- [x] `D-01` Direct invite send must be able to emit a recipient-targeted push automatically; authoring push messages manually in admin is not an acceptable product substitute for the invite path.
- [x] `D-02` Live reflection for inbound invites must not depend solely on full-screen reload. Mobile may reflect through push payload delivery; web and any non-push surface must reflect through invite SSE.
- [x] `D-03` Public invite-share metadata (`/invite?code=...`) must resolve tenant/event/inviter preview data from a canonical backend-owned invite preview context and must not ship placeholder/example image URLs.
- [x] `D-04` Firebase settings/credentials and tenant push enablement are mandatory runtime dependencies, but missing config is not the sole blocker; the backend invite bridge and Flutter realtime consumption are separate implementation gaps.
- [x] `D-05` The invite preview title/description/image used for share metadata should be treated as the canonical preview source for invite-related public surfaces and push composition, so web share and push payloads do not drift.
- [x] `D-06` Current web runtime remains push-disabled by design; therefore invite live reflection on web must close through the existing SSE infrastructure instead of waiting for web push.
- [x] `D-07` Both Firebase public app config and FCM server credentials must remain tenant-dynamic and admin-managed. Local JSON files may be used only as operator input sources during this lane, never as a durable runtime configuration mechanism.

## Current Audit Snapshot (2026-05-09)

- [x] `A-01` Direct invite creation in `InviteMutationService` persists invite edges and rebuilds feed projection, but does not create/send a push message.
- [x] `A-02` Backend invite SSE already exists through `GET /invites/stream` and `InviteRealtimeStreamController`.
- [x] `A-03` Flutter has generic SSE infrastructure and currently uses it for events, not for invites.
- [x] `A-04` Flutter mobile already has a partial invite push-upsert path: push messages arriving on `messageStream` can call `InvitesRepository.applyInvitePushPayload(...)`.
- [x] `A-05` Flutter web explicitly skips push registration (`[Push] Web registration skipped; Firebase web config/VAPID not configured.`), so web cannot rely on push for invite reflection.
- [x] `A-06` Public invite shell metadata already routes `/invite?code=...` through `PublicWebMetadataService::inviteMetadata(...)`, but invite preview still falls back to generic values, including `https://example.com/invite-preview.jpg` when event image resolution is absent.
- [x] `A-07` Tenant push runtime requires: `push.enabled = true`, valid Firebase public app settings, valid FCM server credential, and device token registration.
- [x] `A-08` The current Firebase admin schema is one app-config surface (`apiKey`, `appId`, `projectId`, `messagingSenderId`, `storageBucket`), which is sufficient for the current Android-first push lane but is not a general multi-platform Firebase configuration model.
- [x] `A-09` The current tenant-admin Flutter settings UI is drifted from the live backend contract:
  - Firebase save posts a `firebase` envelope, but `/settings/firebase` now requires direct payload keys.
  - Push save posts a `push` envelope, but `/settings/push` now requires direct payload keys.
  - The UI does not expose the dedicated `enable/disable` push actions even though the backend has explicit endpoints for them.
- [x] `A-10` FCM server credentials are already tenant-dynamic in the backend through `PUT /push/credentials`, but the current tenant-admin UI does not expose that credential surface.

## Implementation Tasks

- [ ] ⚪ Add a backend invite-delivery bridge from direct invite creation into the push subsystem, with deterministic recipient resolution and `invite_received` type/route semantics.
- [ ] ⚪ Repair tenant-admin Firebase settings save so it uses the direct `/settings/firebase` payload contract instead of the stale `firebase` envelope.
- [ ] ⚪ Repair tenant-admin push settings save so it uses the direct `/settings/push` payload contract instead of the stale `push` envelope.
- [ ] ⚪ Add tenant-admin enable/disable controls for push so operators can reach the backend-owned `POST /settings/push/enable` and `POST /settings/push/disable` actions.
- [ ] ⚪ Extend the tenant-admin push settings model/controller/UI to load, show, and persist the backend `enabled` state coherently with the environment snapshot/status surfaces.
- [ ] ⚪ Add tenant-admin CRUD/supporting UI for tenant-scoped FCM server credentials (`project_id`, `client_email`, `private_key`) against the existing `/push/credentials` backend contract.
- [ ] ⚪ Ensure the admin/runtime flow treats local JSON files only as import/copy source during setup; the persisted source of truth must remain the tenant-owned backend credential/settings store.
- [ ] ⚪ Define the canonical invite push payload shape so Flutter can deep-link to the intended invite flow and upsert the invite into the pending-invites state without extra fetch ambiguity.
- [ ] ⚪ Add or reuse a Flutter invite realtime consumer for `/invites/stream` and wire it into `InvitesRepository` so app-open reflection works even when push is unavailable or delayed.
- [ ] ⚪ Harden public invite-share metadata so `/invite?code=...` returns real preview-safe `title`, `description`, and `image` values and never falls back to placeholder/example image URLs in production runtime.
- [ ] ⚪ Decide and implement the canonical preview-data owner used by both public invite metadata and invite-related push payload composition.
- [ ] ⚪ Freeze the tenant-admin operational checklist for push readiness: Firebase public settings, FCM credential, push enabled state, message-type/route expectations, and device registration proof.
- [ ] ⚪ Add focused tests for the missing pieces instead of closing only on manual admin inspection.
- [ ] ⚪ Back-link the final evidence artifact from this TODO.

## Acceptance Criteria

- [ ] ⚪ Sending a direct invite to an eligible recipient produces a backend-authored `invite_received` push message and dispatches delivery when tenant push is configured and the recipient has a registered token.
- [ ] ⚪ Tenant-admin can successfully save Firebase settings and push settings against the current backend contract without `422` envelope errors.
- [ ] ⚪ Tenant-admin can explicitly enable and disable push from the current UI and the resulting `enabled` state is reflected in environment/status snapshots.
- [ ] ⚪ Tenant-admin can create/update the tenant-scoped FCM server credential from the current UI without relying on ad hoc DB edits or external one-off scripts.
- [ ] ⚪ On mobile, when the app is already open and a compatible invite push arrives, the recipient sees the invite reflected without a manual app reload.
- [ ] ⚪ On web, and as a general fallback when push is unavailable, the invite list/related surfaces can reflect new inbound invite changes from `/invites/stream`.
- [ ] ⚪ `/invite?code=...` returns production-safe OG/Twitter metadata with a real, publicly reachable image and invite-specific copy.
- [ ] ⚪ Firebase/tenant-admin configuration requirements are documented and validated as runtime prerequisites rather than left implicit.
- [ ] ⚪ End-to-end evidence clearly distinguishes “feature missing” from “environment misconfigured.”

## Validation Steps

- [ ] Laravel test lane: prove direct invite creation authors/dispatched invite push only when runtime prerequisites are satisfied and stays deterministic when prerequisites are missing.
- [ ] Tenant-admin settings lane: prove Firebase/push saves no longer send stale envelopes and that enable/disable actions work against the live backend endpoints.
- [ ] Tenant-admin credentials lane: prove the UI can write the tenant FCM server credential through `/push/credentials` and that the stored credential is then consumed by the FCM delivery path.
- [ ] Flutter test lane: prove invite push payload upserts the repository and that invite SSE updates drive the same repository/screen state.
- [ ] Public web metadata lane: assert `/invite?code=...` HTML contains invite-specific OG/Twitter tags with non-placeholder image URLs.
- [ ] Runtime lane (mobile): validate device registration + direct invite send + push delivery + app-open reflection on a real Android device or production-equivalent push runtime.
- [ ] Runtime lane (web): validate `/invite?code=...` share page metadata and invite reflection behavior through the browser-facing domain.
- [ ] Tenant-admin readiness lane: validate `settings/firebase`, `settings/push/credentials`, `settings/push/enable`, and device-token registration against the target tenant.

## Assumptions Preview

| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | `GET /invites/stream` already emits enough delta information for Flutter to trigger invite refresh/upsert without inventing a new realtime backend. | `endpoints_mvp_contracts.md`, `InviteRealtimeStreamController.php`, existing invite outbox/projection flow. | A new invite realtime contract or richer delta payload would need to be added before Flutter can reflect reliably. | High | Keep as Assumption |
| `A-02` | The current Flutter partial push-upsert path is sufficient if the backend delivers a stable invite push payload. | `application_contract.dart`, `InvitesRepository.applyInvitePushPayload`, existing test coverage. | The client may require additional repository/bootstrap work beyond payload delivery. | High | Keep as Assumption |
| `A-03` | The public invite metadata issue is not only tenant branding config; there is a real implementation gap because invite preview currently allows generic/example fallbacks. | `PublicWebMetadataService::inviteMetadata`, `InviteShareService::preview` placeholder fallback. | The TODO may need to split “config-only metadata” from “implementation metadata.” | High | Keep as Assumption |
| `A-04` | Current tenant admin push settings can support the direct-invite notification path without a schema redesign. | Existing push settings namespaces, `invite_received` tests/config surfaces in push handler. | A schema/config model change would be required before implementing the bridge. | Medium | Keep as Assumption |

## Execution Plan

### Touched Surfaces

- `laravel-app/packages/belluga/belluga_invites/**`
- `laravel-app/packages/belluga/belluga_push_handler/**`
- `laravel-app/app/Application/PublicWeb/**`
- `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
- `flutter-app/lib/infrastructure/services/sse/**`
- `flutter-app/lib/application/application_contract.dart`
- relevant tests in Laravel + Flutter + web navigation/browser evidence

### Ordered Steps

1. Freeze the operational prerequisites and collect the tenant Firebase/FCM inputs.
2. Implement the backend invite-to-push bridge and invite payload composition.
3. Implement/attach Flutter invite SSE consumption and reconcile it with the existing push-upsert path.
4. Harden invite public metadata to use canonical invite preview data with real public image resolution.
5. Add focused tests for backend push creation, Flutter realtime reflection, and invite shell metadata.
6. Run runtime validation with real tenant config and browser/device evidence.

### Test Strategy

- **Strategy:** `test-first`
- **Why:** the missing behavior is explicitly contract-shaped and observable at API/client boundaries.
- **Fail-first targets:**
  - direct invite send does not dispatch `invite_received` push today;
  - Flutter does not consume invite SSE today;
  - `/invite?code=...` metadata allows generic/example image fallback today.

### Runtime / Rollout Notes

- The feature must be validated against a tenant whose Firebase public settings, server credential, and push enablement are actually configured.
- Current web runtime does not support push; invite live reflection there must close through SSE.
- The current Firebase settings schema is Android-first. If iOS push delivery becomes in-scope for the same tenant-admin surface, that must be handled explicitly rather than assumed from this TODO.
