# TODO (V1): Tenant-Public UI Polish Batch - Auth, Profile, Events, Invite

**Superseded note (2026-04-17):** this batch organizer was generated as a temporary wrapper for a subset of the broader tenant-public polish stream. The concrete execution authority now lives in the screen-specific TODOs under `foundation_documentation/todos/active/store_release_android/`.

**Status:** Completed
**Current delivery stage:** `Completed`
**Qualifiers:** `Superseded-By-Screen-TODOs`, `Historical-Reference`
**Next exact step:** Use the concrete screen TODOs for any remaining auth/profile/events/invite polish work.
**Owners:** Flutter Team
**Objective:** Deliver a bounded Flutter-only visual-polish batch across the tenant-public auth, authenticated profile, events, invite decision, and invite-share/friends surfaces, preserving current route/controller/API semantics while correcting stale UX copy, weak visual hierarchy, and bare loading/empty-state presentation.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`

**Direct-to-TODO rationale:** safe. This request is already one bounded Flutter-only story slice inside the existing tenant-public polish stream, the affected routes already exist, no constitution/roadmap changes are intended, and the current need is execution discipline rather than broader feature framing.
**Last confirmed truth:** `2026-04-08` repository scan confirms a live parent polish stream already exists, `/profile` is currently guarded by `AuthRouteGuard`, auth header copy still contains stale boilerplate text, and the target screens rely on minimal loading/empty-state presentation that can be improved without backend changes.

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/auth/login` | tenant app / tenant web boundary | `tenant` | `tenant_public` | `n/a` | native auth flow in app; web resolves through existing auth/promotion boundary rules |
| `/profile` | tenant app / tenant web boundary | `tenant` | `tenant_public` | `n/a` | `AuthRouteGuard()` in app; web identity boundary remains route-based |
| `/agenda` | tenant | `tenant` | `tenant_public` | `n/a` | public agenda surface with current filter/history behavior |
| `/invite?code=...` | tenant app / tenant web landing | `tenant` | `tenant_public` | `n/a` | anonymous-first preview/decision in app; promotion/read-only on web |
| `/convites` | tenant | `tenant` | `tenant_public` | `n/a` | tenant user invite-share/friends flow |

---

## Module Anchors

- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/onboarding_flow_module.md`, `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/invite_and_social_loop_module.md`

### Canonical Coverage Status

- `flutter_client_experience_module.md`: authoritative for route ownership, controller-first presentation, auth/profile boundaries, invite landing behavior, and tenant-public safe-back/navigation invariants. No partial migration flag is declared for the touched surfaces.
- `onboarding_flow_module.md`: authoritative for auth/onboarding continuity and invite-to-auth handoff behavior. No partial migration flag is declared for the touched surfaces.
- `events_module.md`: authoritative for agenda route semantics and MVP filter constraints. No partial migration flag is declared for the touched surfaces.
- `invite_and_social_loop_module.md`: authoritative for invite preview/accept/share semantics and web-vs-app boundaries. No partial migration flag is declared for the touched surfaces.

### Decision Consolidation Targets

- Promote durable tenant-public presentation or route-boundary decisions to `foundation_documentation/modules/flutter_client_experience_module.md` only if this batch changes enduring UX semantics rather than tactical polish.
- Promote auth/onboarding-specific copy or boundary decisions to `foundation_documentation/modules/onboarding_flow_module.md` only if a contract-level auth/onboarding rule changes.
- Promote agenda or invite module docs only if the final implementation reveals a real contract/doc drift instead of a pure presentation cleanup.

---

## References

- `foundation_documentation/todos/completed/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-phone-otp-auth-and-contact-match.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-v1-screen-user-profile-polish.md`
- `foundation_documentation/todos/completed/TODO-v1-screen-events-polish.md`
- `foundation_documentation/todos/active/store_release_android/TODO-v1-screen-invite-polish.md`
- `foundation_documentation/todos/promotion_lane/store_release_android/TODO-store-release-minimal-friends-and-favorites-mvp.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `flutter-app/lib/presentation/shared/auth/screens/auth_login_screen/auth_login_screen.dart`
- `flutter-app/lib/presentation/tenant_public/profile/screens/profile_screen/profile_screen.dart`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/event_search_screen.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/invite_flow_screen.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart`

---

## Scope (Bounded Batch)

- Polish the tenant-public auth entry surface (`/auth/login`) by improving hierarchy, spacing, and state readability while preserving current field semantics, CTA destinations, and controller flow.
- Correct stale/boilerplate auth-facing copy that is visibly unrelated to the Belluga/Bóora tenant-public experience.
- Polish the authenticated profile surface (`/profile`) for signed-in users only: improve header hierarchy, section readability, edit affordances, and loading/content clarity without changing profile persistence or route ownership.
- Polish the events surface (`/agenda`) by improving header affordance, empty/loading presentation, and list framing without changing current filter/history/pagination semantics.
- Polish the invite decision surface (`/invite?code=...`) by improving hero hierarchy, loading readiness feedback, and CTA clarity while preserving anonymous-first acceptance behavior and web promotion fallback.
- Polish the invite-share/friends surface (`/convites`) by improving hero-summary-to-friends-list hierarchy, placeholder behavior, and footer separation while preserving contacts/import/share semantics.
- Keep all touched screens pure UI consumers of controller-owned state; no repository/service access from screens/widgets.

## Out of Scope

- Backend/API/schema/route contract changes.
- Account Profile detail polish; that remains governed by `TODO-v1-screen-public-account-profile-detail-polish.md`.
- Discovery polish; that was already completed in `TODO-v1-screen-discovery-polish.md`.
- Any attempt to introduce a signed-out inline profile surface under `/profile`.
- New auth capabilities, new invite capabilities, new agenda filters, or route restructuring.
- Theme-system redesign or importing external palette values.

---

## Module Decision Baseline Snapshot

- `FCX-AUTH-01`: unauthenticated app access to `/profile` continues to native auth/login; unauthenticated web access to `/profile` resolves to the app-promotion boundary. Evidence: `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Authorization Requirements`.
- `EVS-FILTER-01`: MVP agenda/events listing does not accept text-search contract changes. Evidence: `foundation_documentation/modules/events_module.md` decision `EVS-FILTER-01`.
- `INV-11`: web invite behavior in V1 is promotion/read-only only; app owns anonymous-first invite acceptance and trust-action mutations. Evidence: `foundation_documentation/modules/invite_and_social_loop_module.md` decision `INV-11`.

---

## Decision Baseline (Frozen)

- `D-01`: This batch is Flutter-only visual polish; no backend/API/schema changes are allowed.
- `D-02`: In-scope routes are limited to `/auth/login`, signed-in `/profile`, `/agenda`, `/invite?code=...`, and `/convites`.
- `D-03`: `/profile` remains an authenticated route. Signed-out inline profile UI is intentionally out of scope because current canonical behavior is redirect-to-auth, not render-in-place.
- `D-04`: Existing controller/route semantics, CTA destinations, and mutation flows remain unchanged; only presentation quality and copy clarity may change.
- `D-05`: Auth entry copy may be corrected when it is stale or unrelated to the current tenant-public product, but must remain within the existing auth/onboarding boundary.
- `D-06`: Events polish must not introduce or rely on any new server-side search/filter contract.
- `D-07`: Invite decision and invite-share polish must preserve anonymous-first app behavior and web promotion/read-only behavior exactly as currently documented.
- `D-08`: Colors remain theme-driven; no hardcoded external design palette imports.
- `D-09`: Loading, empty, error, and content states must be visually explicit wherever the current screen already exposes those states.
- `D-10`: Delivery requires `fvm dart analyze --format machine` plus targeted Flutter tests for the changed UI paths and a manual smoke pass across all touched routes.

### Module Coherence

| Decision | Module Coherence | Change Intent | Evidence |
| --- | --- | --- | --- |
| `D-01` | `Aligned` | `Preserve` | `flutter_client_experience_module.md` section `2.1.1`; existing polish TODO network |
| `D-02` | `Aligned` | `Preserve` | route ownership in `flutter_client_experience_module.md` section `2.0 Scope/Subscope Ownership` + current route files |
| `D-03` | `Aligned` | `Preserve` | `flutter_client_experience_module.md` section `2.1 Authorization Requirements`; `flutter-app/lib/application/router/modular_app/modules/profile_module.dart` |
| `D-04` | `Aligned` | `Preserve` | controller-first rules in `flutter_client_experience_module.md` section `2.1.1` |
| `D-05` | `Aligned` | `Preserve` | onboarding/auth scope in `onboarding_flow_module.md`; current auth screen remains the same route/flow |
| `D-06` | `Aligned` | `Preserve` | `events_module.md` decision `EVS-FILTER-01` |
| `D-07` | `Aligned` | `Preserve` | `invite_and_social_loop_module.md` decision `INV-11` and share-code sections |
| `D-08` | `Aligned` | `Preserve` | archived parent polish TODO and Flutter theme usage contract |
| `D-09` | `Aligned` | `Preserve` | parent polish TODO + Flutter presentation responsibility |
| `D-10` | `Aligned` | `Preserve` | `flutter-architecture-adherence` analyzer/testing discipline |

### Module Decision Consistency Matrix

| Module Decision | Planned Handling | Evidence |
| --- | --- | --- |
| `FCX-AUTH-01` | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Authorization Requirements` |
| `EVS-FILTER-01` | `Preserve` | `foundation_documentation/modules/events_module.md` line containing `EVS-FILTER-01` |
| `INV-11` | `Preserve` | `foundation_documentation/modules/invite_and_social_loop_module.md` line containing `INV-11` |

---

## Assumptions Preview

| ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The current auth widgets already expose enough state to improve hierarchy, copy, and feedback without controller changes. | `auth_login_screen.dart`, `auth_login_canva_content.dart`, `auth_login_form.dart` are already controller-driven and UI-local. | The batch would need controller/API changes and exceed the approved scope. | `High` | `Keep as Assumption` |
| `A-02` | Signed-out profile rendering should not be implemented in this batch because `/profile` is currently auth-guarded and canonically redirect-based. | `profile_module.dart` uses `AuthRouteGuard()`; `flutter_client_experience_module.md` says unauthenticated app access to `/profile` continues to auth/login. | We would need a new route/auth decision, which exceeds visual polish scope. | `High` | `Keep as Assumption` |
| `A-03` | The events screen can be visually improved without touching schedule query behavior or widening MVP filter/search semantics. | `event_search_screen.dart` exposes UI-local app-bar/empty-state structure; `events_module.md` freezes filter contract. | This batch would need a contract correction TODO, not a visual polish pass. | `Medium` | `Keep as Assumption` |
| `A-04` | Invite share/friends layout can be improved within current controller data shape, even though friend placeholders are currently padded in the screen layer. | `invite_share_screen.dart` and `invite_share_screen_controller.dart` already expose the required data; placeholder padding is presentation-local. | We would need repository/controller refactoring that expands the blast radius. | `Medium` | `Keep as Assumption` |
| `A-05` | No external dependency beyond local analyzer/test execution is required to validate this batch before implementation. | This is presentation-only Flutter work with no network/runtime contract edits. | Delivery would need a dependency-readiness artifact and pause for external system health. | `High` | `Keep as Assumption` |

---

## Execution Plan

### Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-tenant-public-ui-polish-batch-auth-profile-events-invite.md`
- `flutter-app/lib/presentation/shared/auth/screens/auth_login_screen/**`
- `flutter-app/lib/presentation/tenant_public/profile/screens/profile_screen/**`
- `flutter-app/lib/presentation/tenant_public/schedule/screens/event_search_screen/**`
- `flutter-app/lib/presentation/tenant_public/schedule/widgets/agenda_app_bar.dart`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_flow_screen/**`
- `flutter-app/lib/presentation/tenant_public/invites/screens/invite_share_screen/**`
- targeted Flutter widget tests for the touched screens/widgets

### Ordered Steps

1. Add or update targeted widget tests for the most brittle polish points:
   - auth headline/copy and CTA hierarchy,
   - authenticated profile header/section presentation,
   - agenda empty/loading/header states,
   - invite decision hero CTA/loading readiness,
   - invite-share summary/list/footer hierarchy.
2. Refine auth screen composition and copy using existing controller-owned state only.
3. Refine the authenticated profile screen and header while preserving the existing edit/save/logout flow.
4. Refine agenda empty/loading/header presentation without changing filter/history semantics.
5. Refine invite decision and invite-share layouts using only existing controller outputs.
6. Run `fvm dart analyze --format machine`, targeted tests, then perform manual smoke across the touched routes.

### Test Strategy

- `test-first`

### Fail-First Targets

- auth screen still renders stale boilerplate headline/copy that does not match the tenant-public product context;
- profile widget still assumes a signed-out inline state instead of preserving the current auth-guard boundary;
- events screen empty/loading states remain bare centered widgets with no clearer visual structure after the batch;
- invite decision surface remains visually ambiguous between primary decision CTA and secondary actions;
- invite-share surface still reads as a flat list with weak separation between hero, summary, friends list, and footer actions.

### Runtime / Rollout Notes

- No backend rollout or migration steps are expected.
- If implementation reveals a route/auth contract change request, stop, update/split the TODO, and request renewed approval instead of silently widening the batch.

---

## Plan Review Gate

### Issue Card `P-01` - stale profile TODO text conflicts with the actual auth-guarded `/profile` route

- Severity: `high`
- Evidence: `flutter-app/lib/application/router/modular_app/modules/profile_module.dart`; `foundation_documentation/modules/flutter_client_experience_module.md` section `2.1 Authorization Requirements`
- Why it matters now: implementing a signed-out inline profile variant would silently create new route behavior, which is outside the user’s “small visual fixes” request.
- Option A: keep the batch aligned to the current auth-guarded route and polish only the signed-in profile state.
- Option B: add a signed-out inline profile variant anyway.
- Option C: drop profile from the batch completely.
- Option analysis:
  - `A`: medium effort, low risk, lowest blast radius, lowest maintenance burden. Recommended.
  - `B`: higher effort, high auth-boundary risk, higher blast radius, creates hidden contract drift.
  - `C`: lower effort, low risk, but leaves an obvious in-scope screen untouched.
- Recommended option: `A`.

### Issue Card `P-02` - multi-screen polish can drift into hidden behavior changes

- Severity: `high`
- Evidence: multiple routes/screens across auth, events, and invite flows; existing parent polish TODO warns against scope creep.
- Why it matters now: these screens sit on top of different module contracts, so “just a visual tweak” can accidentally become a behavior or contract change.
- Option A: keep the batch strictly visual and controller-first, stopping if a change needs controller/contract work.
- Option B: allow opportunistic controller cleanups during the same batch.
- Option C: split into one TODO per screen before starting.
- Option analysis:
  - `A`: medium effort, low risk, controlled blast radius, good maintenance profile. Recommended.
  - `B`: medium-high effort, medium-high risk, growing blast radius, weak approval discipline.
  - `C`: higher planning overhead, lower implementation concurrency, but strongest isolation.
- Recommended option: `A`.

### Issue Card `P-03` - broad shared-widget refactors would raise regression risk beyond what this batch needs

- Severity: `medium`
- Evidence: touched surfaces can be improved mostly inside screen-local widgets; shared invite/auth widgets are only partially involved.
- Why it matters now: a polish pass can become a pseudo-redesign if too much is centralized at once.
- Option A: keep changes mostly screen-local, extracting shared UI only when duplication is immediate and obvious.
- Option B: use this batch to redesign shared primitives first.
- Option C: avoid shared-widget touches entirely, even when duplicated.
- Option analysis:
  - `A`: medium effort, low-medium risk, balanced blast radius, best maintainability. Recommended.
  - `B`: high effort, high regression risk, broad blast radius, over-scoped for this request.
  - `C`: low effort, low risk, but leaves repeated low-quality patterns in place.
- Recommended option: `A`.

### Failure Modes & Edge Cases

- auth copy is corrected visually but the CTA hierarchy still feels secondary to the legacy header block;
- profile polish accidentally weakens the current edit/save feedback or logout affordance;
- agenda polish accidentally implies unsupported search/filter behavior beyond the existing contract;
- invite web fallback loses its clear promotion/read-only distinction;
- invite-share placeholder cards become visually misleading instead of obviously non-actionable.

### Residual Unknowns / Risks

- The events screen still appears to carry legacy search affordances relative to module docs; this batch will not resolve that deeper contract drift.
- The profile screen contains dormant signed-out UI artifacts (`AnonymousProfileCard`) that may deserve deletion or a later contract revisit, but not in this polish batch.
- Invite-share placeholder padding may reveal a deeper product decision gap if the visual cleanup shows the current placeholder strategy is fundamentally weak.

### Independent Critique Gate

- Decision: `required` because this is a `medium` batch with multiple module boundaries and a `high` severity planning issue.
- Constraint: no delegated/subagent critique is available in this turn because the user did not request parallel sub-agents.
- Bounded no-context self-critique outcome: preserve the current auth/route contracts, keep the batch visual-only, and avoid shared-architecture rewrites. Findings from that critique are integrated into `P-01..P-03`.

---

## Must Preserve

- controller-owned state and keys only; no repository/service resolution inside screens/widgets.
- current route ownership and auth/invite boundaries documented in the module anchors.
- theme-driven styling rather than fixed external palette values.
- existing CTA destinations, invite mutations, and agenda navigation continuity.

## Must Avoid

- introducing a new signed-out `/profile` behavior.
- silently widening events search/filter semantics.
- changing anonymous-first invite acceptance or web promotion/read-only behavior.
- local widget state additions that should live in controllers.

---

## Acceptance Criteria

- auth entry surface has clearer hierarchy and corrected product-relevant copy with unchanged behavior.
- authenticated profile surface reads more clearly and preserves current edit/logout flows.
- agenda surface has clearer header/empty/loading presentation with unchanged navigation and filter behavior.
- invite decision and invite-share surfaces have clearer primary action hierarchy and improved loading/empty-state readability with unchanged semantics.
- no backend/API/route-contract changes are introduced.

## Definition of Done

- all touched UI changes are implemented within the approved route/contract boundary.
- decision adherence is recorded against `D-01..D-10`.
- `fvm dart analyze --format machine` is clean.
- targeted Flutter tests for changed polish points are green.
- manual smoke evidence is recorded for `/auth/login`, `/profile`, `/agenda`, `/invite?code=...`, and `/convites`.

## Validation Steps

- manual smoke: auth happy path, validation error state, and keyboard-safe layout.
- manual smoke: authenticated profile view, edit-sheet flow, avatar sheet, and logout.
- manual smoke: agenda default, empty/loading states, history toggle, and event entrypoint continuity.
- manual smoke: invite preview/decision flow in app plus web promotion fallback behavior.
- manual smoke: invite-share/friends list, summary, share footer, and placeholder readability.
- automated: `fvm dart analyze --format machine` and targeted Flutter tests for changed widgets/screens.
