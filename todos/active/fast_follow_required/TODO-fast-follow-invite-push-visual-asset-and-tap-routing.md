# TODO (Fast Follow): Invite Push Visual Asset and Tap Routing

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [ ] ✅ Production-Ready`.
**Status:** Provisional / evidence reconciliation required. The invite-push UX hardening code was promoted, but this TODO cannot remain in `promotion_lane` or be marked completed until the unchecked fallback/documentation criteria are reconciled or explicitly split/waived.
**Owners:** Delphi (Flutter/Laravel) + Tenant Admin / Operations
**Goal:** make invite notifications look intentional on-device and route directly into the specific invite context, without reopening the already-validated delivery pipeline.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context

Invite delivery is now functionally working:

- local backend reaches the real FCM provider;
- real device tokens receive the notification in foreground/background;
- invite pushes are accepted by the provider and the app already reacts to invite payload arrival.

Two product-facing gaps remain:

1. the Android notification still presents the wrong visual contract, with the current iconography collapsing into a solid circular/block artifact instead of a clean invite-specific notification surface;
2. tapping an invite push currently routes through the generic Push Handler surface, but invite notifications should open the specific invite flow/screen for that exact invite instead.

These are not delivery-pipeline failures anymore. They are invite-push UX contract gaps that need a dedicated follow-up slice.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** delivery is already validated, so the remaining work is a bounded product UX correction on top of the existing invite push path.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the operator supplied the concrete runtime defects and the boundary is already diagnosis-bounded: notification asset fidelity plus invite-specific notification tap routing.

## Contract Boundary
- This TODO defines **WHAT** must be corrected in the invite push UX after delivery.
- It does **not** reopen FCM credentialing, device registration, queue delivery, or the generic push pipeline unless a subordinate defect is discovered inside the same invite-push objective.
- If the fix requires a new tenant setting, media contract, or route contract, that change is in scope only insofar as it serves the invite-push UX objective.

## Delivery Status Canon
- **Current delivery stage:** `Provisional`
- **Qualifiers:** `Fast-Follow`, `Cross-Stack`, `Production-Visible`, `Decision-Frozen`
- **Next exact step:** reconcile completion evidence for stale/superseded/ended-event fallback and visual-surface documentation, or split the remaining fallback/documentation criteria into a fresh follow-up before any completion claim.

## Scope
- [x] Define and implement a dedicated Android `small icon` asset contract for invite notifications so the system icon no longer collapses into the current solid circular/block artifact.
- [x] Define and implement the canonical `rich image` contract for invite pushes as the resolved canonical event image for that invite type.
- [x] Carry the canonical inviter-avatar contract for invite pushes as resolved runtime data, but do not block this slice on native remote-notification rendering if the current Android surface cannot display a large-icon/avatar cleanly.
- [x] Ensure backend invite push payload composition uses the canonical invite notification visual contract instead of the current visually broken source.
- [x] Ensure tapping `invite_received` / invite-specific notifications routes directly to the specific invite screen/context for that invite, not the generic Push Handler default UI.
- [ ] Define and implement the canonical invite push fallback navigation when the target invite is no longer renderable (`stale`, `superseded`, or equivalent terminal/non-actionable states).
- [ ] Preserve the already-proven invite push delivery path while changing only the visual/tap UX behavior required for invite notifications.
- [ ] Document the runtime/operator contract for the invite push visual surfaces, including which pieces are app-bundled assets versus resolved runtime media, and how flavor-specific Android notification assets are organized.

## Delivery Status Semantics
- `Pending`: no meaningful delivery milestone has been reached yet.
- `Local-Implemented`: work is implemented in a local branch and validated locally.
- `Lane-Promoted`: work has been merged through the declared lane threshold (usually `dev`).
- `Production-Ready`: final required lane threshold is complete and confidence gates are satisfied.
- `Provisional`: delivery is intentionally partial/incomplete but useful for unblocking dependent work.
- `Blocked`: work cannot currently proceed; `Blocker Notes` become mandatory.

## Execution Lane Tracking
- **Local implementation branches:** `flutter-app: fix/invite-push-visual-and-tap-routing-20260520`, `laravel-app: fix/invite-push-visual-and-tap-routing-20260520`, `foundation_documentation: fix/invite-push-visual-and-tap-routing-20260520`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `main`

## Promotion Evidence
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Invite push visual asset contract | `fix/invite-push-visual-and-tap-routing-20260520` | `belluga_now_front#327`, `belluga_now_backend#218`, `belluga_now_docker#727/#730` | `belluga_now_front#326`, `belluga_now_backend#217`, `belluga_now_docker#729` | `<pending>` | `Promoted through stage` |
| Invite push tap routing to specific invite | `fix/invite-push-visual-and-tap-routing-20260520` | `belluga_now_front#327`, `belluga_now_backend#218`, `belluga_now_docker#727/#730` | `belluga_now_front#326`, `belluga_now_backend#217`, `belluga_now_docker#729` | `<pending>` | `Promoted through stage` |

## Out of Scope
- [ ] Reopening direct invite push delivery, FCM credential setup, or queue-worker reliability that were already validated in the previous slice.
- [ ] Redesigning generic push notifications for non-invite message types.
- [ ] Broad tenant-branding redesign outside what is strictly required for invite notification visual correctness.
- [ ] Replacing the entire Push Handler package/app integration if invite-specific routing can be solved through the current contract boundaries.
- [ ] Reopening invite domain policy, occurrence targeting, invite acceptance rules, or share-code delivery semantics.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** invite-payload shape adjustments, Flutter routing glue, tenant settings/media wiring, Android notification composition changes, app-bundled Android asset work, and focused docs/runtime validation subordinate to invite-push UX.
- **Must update or split the TODO:** broad push-handler product redesign, non-invite notification redesign, or a larger tenant-branding/settings program beyond invite push needs.

## Definition of Done
- [x] Invite notifications no longer render the current broken “solid ball/block” visual artifact on the real Android notification surface.
- [x] The Android `small icon` is a dedicated app-bundled notification asset, separate from launcher/branding imagery, and renders cleanly on-device.
- [x] The invite notification uses a resolved canonical event image for the invite-specific rich visual surface.
- [x] The invite push contract preserves the resolved inviter avatar as canonical runtime data; native Android remote-notification rendering may omit that avatar in this slice if the current surface cannot display it cleanly without reopening notification composition.
- [ ] The canonical source/shape rules for all invite push visual surfaces are documented and enforced by the implementation, including flavor compatibility rules for Android-bundled notification assets.
- [x] Tapping an invite push from background opens the specific invite flow/screen for that exact invite instead of the generic Push Handler default UI.
- [ ] If the invite target is stale/superseded/non-renderable, the push tap falls back deterministically: try the canonical event destination first; if the ended-event route cannot open in the current app behavior, fall back to Home.
- [ ] The corrected invite push tap flow works without regressing the already-proven foreground/background receipt behavior.
- [x] Validation is only considered green when the real push shows resolved event imagery, resolved inviter identity imagery, and correct end-to-end receipt/tap behavior on-device.

## Validation Steps
- [x] Local backend validation for invite-push payload composition after the visual contract change, including canonical event image resolution and inviter-avatar resolution.
- [x] Flutter validation for invite notification tap routing into the exact invite context.
- [x] Real-device Android notification smoke proving the dedicated `small icon` renders cleanly in the system notification UI.
- [x] Real-device Android notification smoke proving the invite-specific rich image is the resolved canonical event image.
- [x] Real-device Android notification smoke proving the inviter identity image is the resolved inviter avatar.
- [x] Real-device Android background-tap smoke proving the notification opens the specific invite route/screen.
- [ ] Real-device/runtime validation covering stale/superseded invite navigation fallback.
- [ ] Runtime validation covering an invite whose backing event has already ended, documenting whether the current event route renders it; if not, the push tap must fall back to Home for this TODO instead of redefining ended-event rendering semantics.
- [x] Regression smoke proving the device still receives the invite push in foreground/background after the UX changes.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/tenant_admin_module.md`
- **Planned decision promotion targets:**
- Invite & Social Loop module: invite notification visual/tap semantics
- Flutter Client Experience module: notification tap routing contract
- Tenant Admin module: invite notification media/settings contract if a new admin-managed asset is introduced

## Complexity & Execution Profile
- **Complexity:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `Operational / DevOps` only if Android notification composition/runtime packaging requires build-variant or manifest wiring beyond normal app code.

## Decision Baseline (Frozen)
- [x] `D-01` Android invite notifications must use a dedicated app-bundled `small icon` asset, separate from launcher/branding imagery, designed for monochrome system-notification rendering.
- [x] `D-07` Android `small icon` assets must remain flavor/build bundled, following the same compatibility discipline as app icons. They are not runtime-uploaded admin media.
- [x] `D-08` If tenant/admin settings participate in invite-push visual configuration, they may only choose among build-compatible bundled notification-icon keys and/or runtime-resolved media surfaces (`rich image`, inviter avatar). Settings must not imply arbitrary runtime replacement of the Android `small icon`.
- [x] `D-02` Invite notifications must use the resolved canonical event image as the invite-specific `rich image` surface, using the same backend-owned canonical image resolution chain as the invite/event preview contract.
- [x] `D-03` Invite notifications must carry the resolved inviter avatar as canonical runtime data. Native Android remote-notification rendering of a large identity image is not blocking for this slice if the system surface does not render it cleanly.
- [x] `D-04` Invite notifications must bypass the generic Push Handler default route and open the specific invite flow/screen for the targeted invite.
- [x] `D-05` If the targeted invite is stale, superseded, or otherwise non-renderable as an invite flow, push tap must fall back to the canonical event destination for that invite.
- [x] `D-06` Ended-event rendering semantics are not being redesigned in this TODO. The implementation must test the current ended-event behavior; if the current event route cannot open an ended event, push tap falls back to Home instead of introducing a new ended-event screen contract here.

## Module Decision Baseline Snapshot
- `No prior decision` found that conflicts with `D-01`..`D-04`; this TODO establishes the canonical invite-notification visual/tap contract for later module promotion.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current broken invite notification visual comes from Android notification asset/composition mismatch rather than FCM delivery failure. | Real device already receives the push; backend and FCM are proven, but the on-device visual is wrong. | The implementation may need a different Android notification composition path rather than only a different asset. | `High` | `Keep as Assumption` |
| `A-02` | Invite payloads already carry enough identity to route into a specific invite flow, or can do so with a bounded payload extension. | Invite reception and app-side reaction are already proven; only the tap destination is wrong. | The route contract may require an additive payload/domain projection change before Flutter can open the exact invite. | `Medium` | `Keep as Assumption` |
| `A-03` | The current active TODO `TODO-fast-follow-invite-push-live-reflection-and-share-metadata.md` should not be reopened for this slice because delivery/reflection and share metadata are a different bounded objective. | Delivery path is already proven; the new defects are about invite notification UX after receipt. | If implementation reveals shared unresolved root cause with that TODO, this slice may need cross-reference or consolidation. | `Medium` | `Keep as Assumption` |
| `A-04` | The existing FCM/runtime path can be extended or swapped in a bounded way to satisfy the inviter-avatar requirement without reopening the whole push platform. | Current schema/runtime already supports `icon`, `image`, app-side handling, and invite data. | If native remote notification surfaces cannot satisfy the avatar requirement, Android local-notification composition becomes part of this TODO. | `Medium` | `Keep as Assumption` |
| `A-05` | The app already has a canonical event destination that can be used as the first fallback when an invite-specific route is no longer renderable. | Existing invite payload already carries `event_id` / `occurrence_id`; event/public route surfaces already exist. | The fallback contract may require a bounded route/payload extension before runtime validation can pass. | `Medium` | `Keep as Assumption` |

## Execution Plan

### Touched Surfaces
- `laravel-app` invite push payload composition and any settings/media contract touched by the invite notification asset
- `flutter-app` push payload routing / invite-specific notification handling
- `tenant admin` settings/media surface only if a new explicit invite-push asset must be operator-managed
- `foundation_documentation` module/todo updates for the canonical contract

### Ordered Steps
1. Implement the dedicated Android `small icon` asset contract and wire the notification runtime to use it, preserving compatibility with the current flavor/build asset model used by app icons.
2. Implement canonical invite `rich image` resolution from the backend-owned event image chain.
3. Implement inviter-avatar resolution for invite notifications and preserve it in the payload/runtime contract; native Android omission of a large identity image is acceptable for this slice.
4. Inspect the current push tap path and implement the canonical invite-specific route handoff plus stale/superseded fallback logic.
5. Validate the ended-event fallback branch and freeze the observed current behavior instead of broadening event-rendering scope.
6. Re-run real-device foreground/background/tap validation and document the result.

### Test Strategy
- **Strategy:** `test-after + runtime-first`
- **Why:** the defects are user-visible notification-surface/runtime issues that require real-device proof in addition to focused automated coverage.
- **Fail-first target(s) (when required):** focused tests should be added where practical, but the decisive evidence is Android runtime notification appearance and tap-routing behavior on a real device.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Android small icon renders correctly | `visible UI` | `android-only` | `ADB integration` | `yes` | `yes` | Real-device notification screenshot/log evidence after real invite send | `n/a` |
| Invite rich image resolves to canonical event image | `visible UI` | `android-only` | `ADB integration` | `yes` | `yes` | Real-device notification screenshot/log evidence with resolved event image | `n/a` |
| Inviter avatar resolves in notification experience | `visible UI` | `android-only` | `ADB integration` | `yes` | `yes` | Real-device notification screenshot/log evidence with resolved inviter avatar | `n/a` |
| Tapping invite notification opens the specific invite screen | `CRUD/mutation` | `android-only` | `ADB integration` | `yes` | `yes` | Real-device background notification tap smoke using canonical invite payload | `n/a` |
| Stale/superseded invite falls back to event | `navigation fallback` | `android-only` | `ADB integration` | `yes` | `yes` | Real-device/runtime validation with non-renderable invite target | `n/a` |
| Ended-event fallback resolves deterministically | `navigation fallback` | `android-only` | `ADB integration` | `yes` | `yes` | Runtime proof for ended event: event route if renderable, else Home fallback | `n/a` |
| Existing invite push receipt path still works after UX corrections | `payload consumed by UI` | `android-only` | `ADB integration` | `yes` | `yes` | Foreground/background receipt regression smoke on the same device/runtime | `n/a` |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `flutter-app / Validate and Build Web` | Flutter routing/payload handling will change and this repo’s CI surface must stay green even if the runtime proof is Android-only. | `<planned>` | `Local-Implemented` | `planned` | `<pending>` | Exact command depends on final touched Flutter surfaces. |
| `laravel-app / Laravel CI` | Invite push payload/media composition may change in backend invite/push code. | `<planned>` | `Local-Implemented` | `planned` | `<pending>` | Exact command depends on final touched Laravel surfaces. |

### Runtime / Rollout Notes
- Real-device Android validation is mandatory because the primary defects are notification-surface and notification-tap runtime behaviors.
- If a new invite-push asset setting is introduced later, the operator/runtime contract must explicitly define:
  - which surfaces are runtime-managed (`rich image`, inviter avatar, optional icon selector key),
  - which surfaces remain build/flavor bundled (`small icon`),
  - and acceptable file shape/alpha/background expectations so the notification does not regress back to a solid artifact.

## Local Evidence
- Dedicated Android `small icon` added and wired as the default notification icon for the `guarappari` Android flavor.
- The canonical contract is now explicit: `small icon` is build/flavor bundled like the app icon family; runtime/admin settings, if introduced, must operate only on compatible selectors or runtime media surfaces, not arbitrary Android notification-icon uploads.
- Backend invite push payload now carries:
  - canonical event image via `notification.image` / `android.notification.image`
  - inviter identity data via `inviter_name` / `inviter_avatar_url`
  - invite-specific IDs for direct routing (`invite_id`, `event_id`, `occurrence_id`)
- `push_handler` default interactive presentation is now substituted via `presenterOverride`, so invite pushes without interactive steps/buttons no longer surface the generic Push Handler UI.
- Real-device ADB validation on `192.168.15.9:5555` confirmed:
  - background notification posted with the new small icon
  - notification tap opened the invite-specific screen showing the invite hero plus `Ver detalhes`, `Recusar`, and `Aceitar`
  - receipt still works in foreground/background after the UX change

## Module Decision Consistency Matrix
| Module Decision / Surface | Status | Handling | Evidence |
| --- | --- | --- | --- |
| Invite push visual/tap semantics are not yet canonically frozen in module docs | `Aligned` | `Preserve now; promote after implementation` | This TODO establishes the frozen baseline before module-sync delivery. |
| Flutter invite route must own invite-specific user-flow entry | `Aligned` | `Preserve` | Existing invite route surfaces already exist; this TODO changes which push path enters them. |

## Plan Review Gate
- **Status:** `pending`
- **Notes:** no code implementation starts until the contract above is approved. Validation is fail-closed: no green outcome without resolved event image, resolved inviter avatar, and exact invite tap-routing on a real device.
