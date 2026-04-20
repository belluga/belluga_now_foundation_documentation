# Title
Store Release Phone OTP Auth And Contact Match

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Upstream Baseline Note
The generic landlord/tenant auth-method governance baseline is now delivered in `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md`. This downstream Belluga release TODO still requires verified phone identity for contact matching, but it must now consume that frozen generic contract instead of redefining platform auth policy.

## Context
The release-critical friends/invites loop depends on deterministic contact matching. The current Belluga release direction is to use phone-based contact hashes so imported address books can resolve existing users without storing raw contact data. That only becomes effective if the resolved tenant-public authenticated identity for the release tenant is anchored on a verified phone number. The current codebase still exposes tenant-public email/password auth in Flutter and Laravel, while the invite flow already assumes app-owned anonymous identity and later authenticated upgrade/merge. The generic Laravel baseline, however, must remain capable of multiple authentication methods under landlord/tenant governance rather than collapsing into a Belluga-only rule.
This TODO also absorbs the former standalone auth-entry polish slice: the MVP no longer needs separate sign-in/sign-up screens, but the replacement phone-entry and OTP verification surfaces must still ship with clear hierarchy, readable validation and backend-error feedback, explicit loading states, and keyboard-safe/mobile-safe layout behavior.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Why this is the right current slice:** this is one bounded publication-critical contract slice: define the MVP tenant-public identity baseline that makes contact-hash matching, invite attribution preservation, and auth-wall progression coherent.
- **Direct-to-TODO rationale (required when `Feature brief = direct-to-todo`):** the business need and the product direction are explicit; the immediate task is to freeze the contract and dependencies, not to run broader discovery.

## Contract Boundary
- This TODO defines the Belluga tenant-public identity contract for phone-based authentication and contact matching after generic auth-method governance is in place.
- It preserves the existing anonymous-first app flow and only changes the authenticated upgrade path.
- It depends on upstream landlord/tenant auth-method governance and therefore must not redefine generic platform capability rules inside this artifact.
- It does not authorize authenticated web expansion, QR login, or broad connections-platform rollout.
- If execution broadens into generic social graph, workspace analytics, or web-authenticated scope, stop and split that work into fast-follow or VNext lanes.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Implementation-Ready`
- **Qualifiers:** `Business-Core`, `Cross-Stack`, `Release-Critical`, `Upstream-Baseline-Ready`
- **Next exact step:** resume Belluga-specific backend/Flutter auth cutover against `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md` and the frozen contract below.

## Upstream Baseline Status
- Upstream baseline: `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md`
- Current state: the generic auth-method governance contract is merged to `dev` and no longer blocks this TODO's local planning or execution.
- Consumption rule: Belluga-specific OTP work must now consume the frozen generic settings/runtime contract instead of reopening platform-level auth-governance decisions.

## Dependencies & Sequencing
- [x] `DEP-00` `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md` is now satisfied as the frozen upstream baseline for Belluga-specific OTP execution.

## Scope
- [ ] Replace Belluga tenant-public release behavior from legacy email/password entry to effective `phone-first OTP`.
- [ ] Own the former auth-entry polish scope inside the new phone-entry and OTP verification screens instead of tracking visual quality in a separate sign-in/sign-up TODO.
- [ ] Keep `POST /api/v1/anonymous/identities` and the anonymous-first invite conversion flow as the pre-auth foundation.
- [ ] Define verified phone as the canonical tenant-public identity anchor used for contact-directory matching.
- [ ] Define OTP delivery as backend-dispatched, provider-agnostic on the client, with WhatsApp as the preferred send channel and SMS as fallback.
- [ ] Define backend-owned phone normalization plus hardened contact-hash materialization for imported contacts and verified users.
- [ ] Define anonymous-to-authenticated merge requirements so invite attribution/history survives phone verification.
- [ ] Remove Belluga tenant-public release dependence on email/password and keep email/social login disabled for Belluga store-release behavior.
- [ ] Keep landlord/admin authentication out of scope and unchanged.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `belluga_now_docker:<planned>`, `flutter-app:<planned>`, `laravel-app:<planned>`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `stage`

## Promotion Evidence (Required Before `🟣 Lane-Promoted` / `✅ Production-Ready`)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Contract freeze + documentation alignment | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Backend phone OTP + merge + dispatcher integration | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Flutter auth cutover to phone OTP | `pending` | `pending` | `pending` | `pending` | `Pending` |
| Contact-hash hardening + invite/history preservation validation | `pending` | `pending` | `pending` | `pending` | `Pending` |

## Out of Scope
- [ ] Landlord/admin authentication changes.
- [ ] Authenticated web, QR login, or workspace-web session bridging.
- [ ] Generic landlord/tenant auth-method governance; that upstream baseline is owned by `TODO-store-release-landlord-tenant-auth-method-governance.md`.
- [ ] Enabling email login, social login, or MFA in Belluga release behavior beyond the single phone OTP challenge.
- [ ] Broad `belluga_connections` package rollout beyond the minimal release-critical contact/friend dependency.
- [ ] Replacing the anonymous-first invite acceptance path with forced auth-before-preview behavior.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** tenant-public auth contract updates, backend/Flutter phone OTP implementation, contact-hash hardening, invite merge preservation, and release-critical doc/test updates that remain inside this one auth objective.
- **Must update or split the TODO:** authenticated web/QR login, generic people discovery, broader connections/package scope, or landlord/admin auth redesign.

## Definition of Done
- [ ] Belluga tenant-public MVP identity baseline resolves through effective `phone-first OTP` configuration.
- [ ] Phone-entry and OTP verification screens absorb the former auth-entry polish baseline: clear CTA hierarchy, readable validation/backend-error states, explicit in-flight feedback, and keyboard-safe/mobile-safe layout.
- [ ] Anonymous-first invite conversion remains preserved and its merge-to-authenticated behavior is explicit.
- [ ] Contact matching depends on normalized verified phone identity with backend-owned hardened hash materialization rather than raw phone storage in matching flows.
- [ ] Belluga tenant-public Flutter/Laravel release behavior no longer depends on email/password.
- [ ] Web promotion-only/auth-boundary rules remain unchanged and are explicitly preserved.

## Validation Steps
- [x] TODO is linked from `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`.
- [x] Dependency edge to `TODO-store-release-minimal-friends-and-favorites-mvp.md` is explicit.
- [x] Upstream auth-governance baseline is delivered in `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md` and exposes the effective Belluga tenant-public auth-method contract.
- [ ] Backend feature tests cover OTP challenge, OTP verify, cooldown/TTL/rate-limit behavior, anonymous-to-authenticated merge, and contact-hash matching after verification.
- [ ] Flutter tests cover phone-entry -> OTP verify -> authenticated state transition, auth-wall continuation, and anonymous invite conversion continuity.
- [ ] Flutter tests and manual smoke cover phone-entry/OTP validation errors, backend-error readability, loading/disabled CTA behavior, and keyboard-safe small-width layout.
- [ ] Legacy Belluga tenant-public email/password routes/UI/tests are either removed or explicitly quarantined from store-release behavior.

## External Dependency Readiness (Required When External Systems Matter)
| Dependency | Why It Matters | Status (`unknown|healthy|degraded|failing|rate-limited|stale`) | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| WhatsApp/SMS OTP delivery provider | OTP dispatch cannot ship without an approved outbound provider path and fallback channel behavior. | `unknown` | `n/a` | `not yet assessed in this TODO` | Backend dispatcher contract stays provider-agnostic; launch fallback policy must be frozen before implementation approval. |

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `strategic-cto`, `operational-devops`
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section`
- **Why this level:** this is a cross-stack public auth contract change that affects backend endpoints, Flutter repositories/routes/screens, onboarding semantics, invite attribution preservation, contact matching, and release risk.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs (if any):**
  - `foundation_documentation/modules/onboarding_flow_module.md`
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Planned decision promotion targets (module sections):**
  - `flutter_client_experience_module.md` tenant-public authorization requirements + API endpoint definitions
  - `onboarding_flow_module.md` entry path + partial identity capture semantics
  - `invite_and_social_loop_module.md` Sanctum + identity requirement and `/contacts/import` contract language
- **Module decision consolidation targets (required):**
  - `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Domain Rules` + `2.2 API Endpoint Definitions`
  - `foundation_documentation/modules/onboarding_flow_module.md` section `2. Entry Paths`
  - `foundation_documentation/modules/invite_and_social_loop_module.md` section `4 APIs & Events` + `Sanctum + Identity Requirement`

## References
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`

## Decisions (Resolved Before Freeze)
- [x] `D-01` After upstream auth-method governance closes, Belluga tenant-public MVP authenticated identity baseline resolves through effective `phone-first OTP`; email/password and social login remain disabled for Belluga store-release behavior.
- [x] `D-02` OTP delivery is backend-dispatched and Flutter remains provider-agnostic; WhatsApp is the preferred channel and SMS is the fallback channel.
- [x] `D-03` The app keeps the existing anonymous-first invite flow and upgrades/merges into authenticated identity only after successful phone verification, preserving invite attribution and history.
- [x] `D-04` Verified phone identity is normalized server-side to canonical `E.164` form before storage, lookup, or matching.
- [x] `D-05` Contact matching must rely on a backend-owned hardened phone hash/HMAC materialization strategy; plain unsalted `SHA` of the phone number is not the canonical long-term primitive.
- [x] `D-06` Tenant-public web remains promotion-only/read-only in V1; authenticated web and QR login stay outside this TODO and remain fast-follow work.
- [x] `D-07` OTP challenges use `6` digits plus backend-enforced TTL, resend cooldown, rate limits, and one active challenge per phone number.
- [x] `D-08` Landlord/admin authentication is a separate surface and is not changed by this tenant-public MVP auth cutover.

## Module Decision Baseline Snapshot (Required Before APROVADO)
| Module Decision Ref | Current Module Decision | Planned Handling (`Preserve|Supersede (Intentional)|Out of Scope`) | Evidence |
| --- | --- | --- | --- |
| `flutter_client_experience_module.md` tenant-public authorization split | app owns trust-action conversion; web remains promotion-only | `Preserve` | tenant-public auth/hard-gate contract in module section `2.1` |
| `onboarding_flow_module.md` invite acceptance path minimal identity capture | capture is currently `name + email/phone` | `Supersede (Intentional)` | onboarding entry path section `2.1` |
| `invite_and_social_loop_module.md` Sanctum + identity requirement | app may mint anonymous identity; share acceptance is anonymous-first | `Preserve` | invite module section `Sanctum + Identity Requirement` |
| `invite_and_social_loop_module.md` `/contacts/import` hashed contacts | hashed contacts already drive contact matching and inviteable acquisition | `Preserve` with hardening clarification | invite module section `4 APIs & Events` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Belluga tenant-public MVP auth must resolve through effective `phone_otp` configuration once upstream auth-method governance closes; Belluga release behavior must not fall back to email/password.
- [x] `D-02` Anonymous-first invite acceptance remains intact and upgrades via merge after verification.
- [x] `D-03` Contact matching is only canonical after backend-normalized phone identity and hardened hash materialization are in place.
- [x] `D-04` Web remains promotion-only; this TODO must not widen into authenticated web/QR scope.
- [x] `D-05` Provider selection/secrets remain backend/admin-owned, not Flutter-owned.
- [x] `D-06` `Resend` is an implementation-owned interface choice, not a product-level open decision. Delivery may use a dedicated resend endpoint or an idempotent alias of challenge creation, but it must preserve the same public semantics: one active challenge per phone, backend-enforced TTL, resend cooldown, and rate limits.

## Open External Readiness Input
- [ ] Confirm which provider path is launch-authoritative for WhatsApp OTP, and record the explicit SMS fallback when provider approval/templates are not ready in time. This is release-readiness input, not a product-definition gap for the auth flow itself.

## Verified Repository Assumptions
| Assumption ID | Assumption | Evidence | If False | Confidence (`High|Medium|Low`) | Handling (`Keep as Assumption|Promote to Decision|Block`) |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The existing anonymous-first invite conversion flow remains the correct pre-auth foundation; this TODO only changes the authenticated upgrade path. | roadmap + invite module explicitly preserve anonymous identity and anonymous share acceptance in app. | The scope broadens into onboarding/invite redesign rather than auth cutover. | `High` | `Keep as Assumption` |
| `A-02` | Backend already materializes `phone_hashes` for users and `/contacts/import` already matches on phone hash. | `tests/Feature/Invites/InvitesFlowTest.php` covers `phone_hashes` materialization and phone contact import matches. | The release-critical matching path would need foundational backend work beyond this planned auth slice. | `High` | `Keep as Assumption` |
| `A-03` | Generic auth-method governance is now established upstream, but tenant-public Flutter and Laravel auth still require a Belluga-specific phone-OTP cutover. | `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md` + current Flutter `AuthRepositoryContract` and Laravel auth backend/routes still expose email/password login/register. | The remaining auth cutover is smaller than expected and this TODO can be narrowed during planning. | `High` | `Keep as Assumption` |
| `A-04` | Landlord/admin authentication can remain separate without weakening the tenant-public MVP contract. | tenant-public auth routes live separately from admin auth routes and current product direction only changes tenant-public conversion. | This TODO would widen into cross-scope auth redesign and should be split. | `High` | `Keep as Assumption` |

## Execution Plan (Required Before `APROVADO`)
### Touched Surfaces
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-landlord-tenant-auth-method-governance.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-signin-signup-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-android.md`
- `foundation_documentation/todos/active/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/onboarding_flow_module.md`
- `foundation_documentation/modules/invite_and_social_loop_module.md`
- `foundation_documentation/system_roadmap.md`
- `flutter-app/lib/**` auth/repository/router/screens/tests
- `laravel-app/routes/api/**`, auth controllers/services/tests, dispatcher integration surfaces

### Ordered Steps
1. Keep the dependency edge explicit from the store-release orchestrator, the minimal friends/favorites lane, and `TODO-store-release-landlord-tenant-auth-method-governance.md`.
2. Use the delivered upstream auth-governance TODO as the frozen baseline before making code changes under this downstream lane.
3. Implement the canonical doc/API contract changes already frozen here (`phone OTP`, anonymous merge preservation, hardened contact-hash language, provider ownership, web boundary preservation).
4. Introduce backend tenant-public phone OTP contract (`challenge`, `verify`, and resend semantics), including TTL/cooldown/rate limits, merge behavior, and token issuance.
5. Implement backend dispatcher integration with provider-agnostic service boundaries, WhatsApp-preferred send routing, and SMS fallback behavior.
6. Refactor Flutter tenant-public auth repositories/routes/screens from legacy email/password entry to phone + OTP entry while preserving auth-wall redirect semantics, anonymous invite continuity, and the absorbed auth-entry polish baseline.
7. Update backend + Flutter tests first around OTP flows, merge behavior, rate-limit/error handling, contact-match continuity, and phone-entry/OTP screen state quality.
8. Remove or quarantine legacy Belluga tenant-public email/password surfaces from store-release behavior and verify no remaining route/UX entry depends on them.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** auth behavior, invite-attribution preservation, and contact-matching semantics are contract-defining and regression-prone.
- **Fail-first target(s) (when required):**
  - Laravel feature tests for OTP challenge/verify, merge of anonymous user into verified phone user, resend/cooldown/rate-limit behavior, and post-verification contact matching.
  - Flutter repository/controller/router tests for phone-entry + OTP verify flow, auth-wall continuation, anonymous invite continuity, absorbed auth-entry state quality, and removal of tenant-public email/password dependency.

### Runtime / Rollout Notes
- OTP provider secrets, channel templates, sender identities, and resend/cooldown policy remain backend/admin-owned.
- Launch readiness depends on external provider approval/readiness; that dependency must be verified before implementation approval or explicitly handled with a fallback policy.
- Existing release telemetry should add OTP funnel milestones (`otp_challenge_started`, `otp_verified`, `auth_merge_completed`) without breaking the current invite conversion funnel.
