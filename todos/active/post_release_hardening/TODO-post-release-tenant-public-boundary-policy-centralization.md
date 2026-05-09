# TODO (Post Release Hardening): Tenant Public Boundary Policy Centralization

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Store Release validation exposed a recurring implementation risk: route-level boundaries are partially centralized in guards, but action-level boundaries are still repeated inside tenant-public screens with local `kIsWeb`, auth-state checks, and promotion/auth navigation decisions.

The canonical policy is already defined in `foundation_documentation/policies/web_to_app_promotion_policy.md`: the boundary is **web anonymous**, not web as a whole. QR-authenticated web follows the normal authenticated posture for the surface. Anonymous web hard gates promote to the app; anonymous app may preview and browse allowed surfaces, but trust mutations such as invite accept/decline require authenticated identity before execution.

This hardening TODO centralizes that policy after Store Release so future screens cannot drift into case-by-case boundary handling.

## Drift Guardrail Requirement
- This TODO is an auth/boundary drift-prevention slice.
- Before migrating scattered local checks, execution must freeze:
  - the violated boundary rule,
  - the executable replacement rule,
  - and the strongest objective PACED guardrail available so new tenant-public screens cannot reintroduce local boundary drift after remediation.
- The currently observed local `kIsWeb` / auth-state drift cases must be included in the validation set for that guardrail so the policy proves it catches the real patterns already seen in the repo.

## Delivery Status Canon
- **Current delivery stage:** `Post-Release-Backlog`
- **Qualifiers:** `Hardening`, `Architecture-Guard`, `Flutter`, `Tenant-Public`, `Post-Store-Release`, `Not-Store-Release-Blocker`
- **Next exact step:** after Store Release promotion, create the executable boundary policy and migrate action-gated tenant-public screens to it.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-tenant-public-boundary-centralization`
- **Direct-to-TODO rationale:** this is one bounded hardening slice: centralize already-approved boundary decisions and prevent future local guard drift. It does not introduce a new product capability.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/invite_and_social_loop_module.md`
  - `foundation_documentation/policies/web_to_app_promotion_policy.md`
- **Decision consolidation targets:**
  - `flutter_client_experience_module.md` tenant-public auth/promotion boundary and presentation DI/ownership rules.
  - `invite_and_social_loop_module.md` invite/web/app boundary references.
  - `web_to_app_promotion_policy.md` executable boundary matrix source.

## Scope
- [ ] Create one executable tenant-public boundary policy for action gates.
- [ ] Model boundary decisions by runtime surface, auth state, and action type.
- [ ] Cover at minimum:
  - invite preview accept/decline action.
  - projected invite card accept action in event detail.
  - event attendance confirmation CTA.
  - send invite/share invite action.
  - favorite toggle action.
  - identity-owned route/action handoff.
- [ ] Provide one navigation adapter/helper so screens do not manually build app-promotion or auth-login redirects.
- [ ] Migrate existing tenant-public action gates away from local `kIsWeb` + auth checks.
- [ ] Add unit tests for the boundary decision matrix.
- [ ] Add widget/navigation smoke tests proving representative screens call the centralized path.
- [ ] Add a static guard/action that fails when tenant-public screens bypass the centralized boundary path for auth/promotion decisions.

## Out of Scope
- [ ] Changing Store Release product behavior.
- [ ] Reopening QR-login delivery.
- [ ] Reopening deferred deep-link delivery.
- [ ] Reclassifying this hardening as a Store Release blocker unless a concrete release regression is found.
- [ ] Changing backend invite acceptance semantics except where a client contract mismatch is discovered.

## Decision Baseline
- [x] `D-01` The canonical boundary is **web anonymous**, not web globally.
- [x] `D-02` QR-authenticated web follows the normal authenticated posture for the surface.
- [x] `D-03` Anonymous web hard/auth gates route to canonical app promotion, preserving valid redirect/invite context.
- [x] `D-04` Anonymous app may preview invite context and browse allowed surfaces, but trust mutations such as invite accept/decline require authenticated identity before execution.
- [x] `D-05` Tenant-public screens must not own divergent promotion/auth boundary logic after this hardening is implemented.
- [x] `D-06` This TODO is post-release hardening and is not a Store Release blocker by itself.

## Proposed Executable Matrix
| Runtime | Auth state | Action class | Expected decision |
| --- | --- | --- | --- |
| Web | Anonymous | Public read/preview | `allow` |
| Web | Anonymous | Trust/auth mutation | `appPromotion(redirectPath)` |
| Web | Authenticated | Trust/auth mutation | `allow` |
| App | Anonymous | Public read/preview | `allow` |
| App | Anonymous | Invite accept/decline | `nativeAuth(redirectPath)` |
| App | Anonymous | Send invite / profile / attendance-checkin | `nativeAuth(redirectPath)` |
| App | Anonymous | Favorites where baseline allows anonymous use | `allow` |
| App | Authenticated | Trust/auth mutation | `allow` |

## Implementation Plan Preview
1. Add domain/application boundary types such as `TenantPublicBoundaryAction`, `TenantPublicBoundaryRuntime`, `TenantPublicBoundaryDecision`.
2. Add `TenantPublicBoundaryPolicy` with a pure, testable decision method.
3. Add `TenantPublicBoundaryNavigator` or equivalent UI adapter for route changes and telemetry side effects.
4. Replace local action gates in Discovery, Account Profile Detail, Event Detail, Invite Flow, and Map POI surfaces.
5. Add static guard script/action for disallowed local boundary patterns outside the centralized files.
6. Promote guard into analyzer plugin only if the script proves stable enough and false-positive risk is acceptable.

## Validation Strategy
- [ ] Unit tests cover every row of the executable matrix.
- [ ] Widget/navigation tests cover representative action surfaces.
- [ ] Static guard fails on direct tenant-public action-gate usage of:
  - `buildWebPromotionBoundaryPath`.
  - direct `AppPromotionRoute`.
  - manual `/auth/login?redirect=`.
  - local `if (kIsWeb)` branches that decide auth/promotion behavior.
- [ ] `fvm dart analyze --format machine` passes.
- [ ] Existing web/app navigation smoke suites remain green.

## Store Release Relationship
This TODO documents a hardening follow-up discovered during Store Release validation. The current release can proceed with the already implemented route guards and targeted action fixes, provided the release-specific tests pass. This TODO exists to prevent future boundary drift after release, not to delay the current store submission.
