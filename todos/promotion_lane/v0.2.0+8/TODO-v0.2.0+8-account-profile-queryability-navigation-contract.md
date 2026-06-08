# Title
VNext: Account Profile Queryability and Public Navigation Contract

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Manual validation found a public Event related-profile card that looked non-linkable but still navigated to `/parceiro/:slug`, producing `Algo deu errado`. The concrete reproduction used `https://guarappari.belluga.space/agenda/evento/pw-event-share-boundary-store-release-5?occurrence=6a203315dcd27475bd0ce16d`, tab `Outro Grupo`, card `PW EVG Alpha Dois 1780398795933`.

Backend investigation showed the profile itself existed and was active/public, but its `profile_type` was not present in the current public profile-type catalog. The Event public projection still emitted the profile in `profile_groups[]` with a slug, and Flutter treated any slug as navigable.

The deeper architecture finding is not only card clickability. Account Profile type capabilities currently blur several independent concepts:
- whether a profile type is **queryable/listable/selectable** in operational lists and candidate pickers;
- whether a profile can have a **public direct detail page** (`/parceiro/:slug`);
- whether it is **publicly discoverable** in tenant-public discovery/search surfaces;
- whether it is **POI/map enabled**.

The target model should follow the same separation of concerns as WordPress-style content registration flags: list/query eligibility and public route navigability are independent restrictions. A type may be direct-route navigable without being listable, and a type may be listable in a participant context without having a public detail route. The system must not rely on developers remembering this rule in every query.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `account-profile-queryability-navigation-contract`
- **Parent validation finding:** `PTODO-009` in `foundation_documentation/artifacts/v0.2.0-plus8/pre-todo-manual-validation-findings-v0.2.0-plus8.md`
- **Why this is the right current slice:** the observed broken public navigation is a symptom of a broader query/capability contract gap. Delivering this slice establishes the capability model, performatic query gateway, deterministic Laravel guard, and end-to-end tests that prevent future list/selectors from leaking non-queryable profiles.
- **Direct-to-TODO rationale:** the user explicitly requested this as a complete TODO for orchestration by an AI without prior chat context. A separate feature brief would duplicate the findings and decisions captured here.

## Contract Boundary
- This TODO defines **WHAT** must be delivered and what counts as done.
- `Assumptions Preview` and `Execution Plan` define **HOW** Delphi currently intends to deliver this contract.
- This TODO is **bounded but elastic**: local discoveries about exact gateway naming, allowlist storage, cache invalidation, or guard implementation may stay inside this TODO when they preserve the same objective.
- If work changes the capability meanings, public contract, guard policy, performance posture, or required validation semantics, update this TODO and request renewed approval before implementation continues.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `round-01 triple audit adjudicated; accepted non-blocking debt recorded in resolution.md; 2026-06-08 package-level mimic revalidation passed`
- **Next exact step:** keep this TODO in `promotion_lane/` until the v0.2.0+8 package-level promotion/CI-equivalent closeout consumes the local evidence and accepted-debt notes.

## Active Work State
- **Work state:** `promotion_lane`
- **Why this state now:** the TODO has local implementation, adjudicated audit evidence, and the current package-wide mimic loop explicitly revalidated both backend and canonical runtime coverage with no reopened finding.
- **Exit condition:** only authorized lane follow-through remains.

## Scope
- [ ] Establish explicit Account Profile type capability semantics for queryability/listability and public navigability.
- [ ] Add or normalize a capability key for list/query eligibility, recommended name `is_queryable`.
- [ ] Add or normalize a capability key for public direct detail-route eligibility, recommended name `is_publicly_navigable` or `has_public_detail_page`.
- [ ] Keep `is_publicly_discoverable`, `is_poi_enabled`, `is_inviteable`, `is_favoritable`, and other existing capabilities independent unless a dependency is explicitly registered in the capability catalog.
- [ ] Define effective capability rules through the existing Account Profile type capability catalog/registry, not ad hoc array reads.
- [ ] Build a canonical Laravel Account Profile query gateway/scope set for:
  - queryable/listable profiles;
  - public discoverable profiles;
  - public direct-detail navigable profiles;
  - public POI/map profiles;
  - event/occurrence related-profile selectable candidates;
  - Account Profile nested-group selectable candidates;
  - invite/social candidate surfaces where applicable.
- [ ] Ensure non-queryable profiles are excluded from all operational lists, searches, dropdowns, selectors, candidate resolvers, public tabs/lists, map/filter lists, and discovery lists.
- [ ] Preserve readback/repair/audit access to non-queryable profiles only through explicit audited paths.
- [ ] Ensure public detail route resolution uses the public navigability contract, not public discovery/listability by accident.
- [ ] Ensure public card payloads expose an explicit navigation contract, recommended `can_open_public_detail` and/or `public_detail_url`.
- [ ] Ensure Flutter never infers card navigability only from `slug`.
- [ ] Make non-navigable but queryable participant cards render without tap/chevron/link semantics while still allowing semantic display when the surface explicitly permits participant display.
- [ ] Suppress non-queryable linked profiles from public Event/Occurrence/Account Profile nested-group lists, even if stale legacy data still references them.
- [ ] Add deterministic Laravel guardrails that prevent raw `AccountProfile` list/candidate queries from bypassing the gateway.
- [ ] Add allowlisted raw-query audit reporting for repair/audit/admin-readback/seed/test paths, with required review instructions printed by the guard itself.
- [ ] Add a matrix of backend, Flutter, and runtime tests proving the capability combinations behave correctly.
- [ ] Repair or clean existing validation-environment references that violate the new queryability contract, or make public projections suppress them until cleanup is executed.

## Delivery Status Semantics
- `Pending`: no implementation has started.
- `Local-Implemented`: implementation exists locally and all in-scope local evidence rows are passed.
- `Lane-Promoted`: implementation has crossed the approved lane threshold.
- `Production-Ready`: final lane threshold and delivery gates are satisfied.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `docker:<current v0.2.0+8 lane>`, `laravel-app:<current v0.2.0+8 lane>`, `flutter-app:<current v0.2.0+8 lane>`; confirm with `git status -sb` before implementation.
- **Promotion lane path:** current v0.2.0+8 promotion lane, no parallel branch/version.
- **Lane-promoted threshold for this TODO:** `dev` unless the active promotion lane defines a stricter threshold.
- **Production-ready threshold for this TODO:** same threshold as the v0.2.0+8 package.

## Promotion Evidence (Required Before Lane-Promoted / Production-Ready)
| Scope Item | Local Branch/Commit | PR to lane threshold | PR to `stage` | PR to `main` | Current Status |
| --- | --- | --- | --- | --- | --- |
| Account Profile queryability/navigation contract | `reconcile/v0.2.0-plus8-cross-stack-20260526 (local worktree)` | `pending` | `pending` | `pending` | `Local-Implemented` |

## Out of Scope
- [ ] Solving `PTODO-010` / `EVG-05` selected-occurrence tabs stuck on the route-entry occurrence. That remains owned by the event/occurrence profile-group route rehydration bug.
- [ ] Adding public pages for every existing profile type by default.
- [ ] Treating a global Eloquent scope as the sole defense. A global scope may be considered only if it does not hide repair/audit/readback paths, but this TODO requires an explicit gateway and guard either way.
- [ ] Replacing Event profile groups or Account Profile nested groups.
- [ ] Changing raw Account identity into a public render contract.
- [ ] Broad production data migration beyond the cleanup/repair needed to remove or suppress invalid validation-environment references.
- [ ] Weakening capability independence by making all capabilities depend on public discovery or favoritable status.

## Bounded But Elastic Guardrails
- **May stay inside this TODO:** exact capability names, gateway class/method names, request-scoped vs persistent cache details, allowlist file format, guard implementation location, and legacy reference cleanup when they preserve the same queryability/navigation objective.
- **Must update or split the TODO:** new public people discovery, new profile page product behavior, a generic framework package, unrelated event occurrence tab rehydration fixes, or new admin UX beyond the selector/readback/repair needs of this contract.

## Definition of Done
- [x] `DOD-01` Account Profile type capability catalog contains explicit independent entries for queryability/listability and public navigability, with dependency metadata and defaults documented in code/tests.
- [x] `DOD-02` Non-queryable types are excluded from all operational list/selector/candidate surfaces covered by this TODO.
- [x] `DOD-03` Public detail route eligibility uses the public navigability contract and direct slug lookup remains indexed/direct.
- [x] `DOD-04` Public discovery, map/POI, invite/social, nested-group, and event/occurrence candidate surfaces consume canonical gateway methods instead of local capability logic.
- [x] `DOD-05` Backend public profile-card projections include an explicit navigability contract (`can_open_public_detail` and/or `public_detail_url`) for linked Account Profile cards.
- [x] `DOD-06` Flutter linked Account Profile cards navigate only when the backend/domain contract says they can open public detail.
- [x] `DOD-07` Queryable-but-not-publicly-navigable profiles can render as non-clickable participant cards where the surface semantically permits display.
- [x] `DOD-08` Non-queryable profiles do not render in public participant/group/list surfaces, even when stale legacy references exist.
- [x] `DOD-09` Laravel deterministic guard blocks raw `AccountProfile` list/candidate queries outside the canonical gateway or explicit allowlist.
- [x] `DOD-10` Allowlisted raw-query findings are still reported with audit instructions and cannot silently pass as unreviewed bypasses.
- [x] `DOD-11` Performance validation proves capability filtering is type-set based (`whereIn profile_type`) and not per-profile in-memory filtering or N+1 capability reads.
- [x] `DOD-12` Existing validation data that created the observed broken card is repaired or suppressed so it no longer creates a clickable/erroring public route.

## Validation Steps
- [x] `VAL-01` Laravel capability catalog unit tests prove `is_queryable` and public navigability are independent and dependency metadata is explicit.
- [x] `VAL-02` Laravel feature tests prove non-queryable profiles are absent from event related-profile selector, occurrence selector, nested account group selector, public discovery, map filter/type options, public event profile groups, and Account Profile nested public groups.
- [x] `VAL-03` Laravel feature tests prove queryable + non-publicly-navigable profiles can be emitted as non-clickable participant cards when the public surface permits participant display.
- [x] `VAL-04` Laravel direct slug detail tests prove public navigable profiles resolve and non-publicly-navigable profiles do not resolve, independently from list queryability.
- [x] `VAL-05` Laravel guardrail test/source scan fails on raw `AccountProfile` operational queries and prints blocked finding resolution instructions.
- [x] `VAL-06` Laravel guardrail test/source scan reports allowlisted raw queries with required audit instructions and fails when a new allowlisted finding lacks a reason/baseline review.
- [x] `VAL-07` Flutter DTO/domain tests decode `can_open_public_detail` / `public_detail_url`.
- [x] `VAL-08` Flutter widget/navigation tests prove clickable linked-profile cards push `PartnerDetailRoute`, while non-clickable cards do not push and do not show link/chevron semantics.
- [x] `VAL-09` Public web Playwright navigation reproduces the previously broken event/group scenario and verifies the invalid non-queryable profile no longer navigates to an erroring `/parceiro/:slug`.
- [x] `VAL-10` Admin Playwright mutation validates that non-queryable types/profiles cannot be selected for event/occurrence/nested groups through normal selectors.
- [x] `VAL-11` Local CI-equivalent suite matrix runs for Laravel, Flutter tests/analyzer, Flutter rule matrix, web build, and required Playwright lanes.

## Completion Evidence Matrix (Required Before Delivery Claim)
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `DOD-01` | `Definition of Done` | Capability catalog contains independent queryability and public navigability entries. | `test|code` | `AccountProfileTypesControllerTest.php`; capability catalog code | `backend` | `passed-local` | Includes independent default-scope assertions for `is_queryable` and `is_publicly_navigable`. |
| `DOD-02` | `Definition of Done` | Non-queryable excluded from operational lists/selectors/candidates. | `test|runtime` | `EventCrudControllerTest.php`; `AccountProfilesControllerTest.php`; `QRY-RUNTIME` browser spec | `backend|browser` | `passed-local` | Admin related-profile selector and public projections both validated. |
| `DOD-03` | `Definition of Done` | Public detail route uses public navigability. | `test` | `AccountProfilesControllerTest.php`; `QRY-RUNTIME` browser spec | `backend|browser` | `passed-local` | Direct slug lookup remains direct and guarded by navigability. |
| `DOD-04` | `Definition of Done` | Consumers use gateway methods instead of local capability logic. | `code|guard` | `account_profile_queryability_guardrails.php`; resolver/nested-group code review; triple-audit resolution | `backend` | `passed-local` | Accepted debt remains only for future stronger evidence, not for duplicate query owners in bounded paths. |
| `DOD-05` | `Definition of Done` | Public projections expose card navigability contract. | `test` | `AccountProfilesControllerTest.php`; `EventCrudControllerTest.php` | `backend` | `passed-local` | Explicit `can_open_public_detail` / `public_detail_path` asserted. |
| `DOD-06` | `Definition of Done` | Flutter cards navigate only when contract allows. | `test|runtime` | Focused Flutter tests; `QRY-RUNTIME` browser spec | `flutter|browser` | `passed-local` | Positive visible-profile navigation and negative non-navigable participant behavior validated. |
| `DOD-07` | `Definition of Done` | Queryable non-navigable profiles render as non-clickable participants where allowed. | `test|runtime` | `EventCrudControllerTest.php`; public browser runtime | `backend|flutter` | `passed-local` | Public event payload and runtime behavior both validated. |
| `DOD-08` | `Definition of Done` | Non-queryable profiles do not render publicly from stale links. | `test|runtime` | `QRY-RUNTIME` browser diagnostic; `AgendaAndEventsControllerTest.php` | `backend|browser` | `passed-local` | Stale-link diagnostic path now green in the canonical local-only browser harness. |
| `DOD-09` | `Definition of Done` | Guard blocks raw operational queries. | `test|guard` | `AccountProfileQueryabilityGuardrailsTest.php`; guard script runtime | `backend` | `passed-local` | Negative raw-query fixture fails with resolution instructions. |
| `DOD-10` | `Definition of Done` | Allowlisted findings are reported and audited. | `test|guard` | `AccountProfileQueryabilityGuardrailsTest.php`; guard script runtime | `backend` | `passed-local` | Allowlisted report includes audit checklist and stale-baseline detection. |
| `DOD-11` | `Definition of Done` | Filtering is performatic and type-set based. | `test|review|performance` | Type-set provider + query-owner code review; focused Laravel tests; round-01 resolution | `backend` | `passed-local` | Accepted non-blocking debt: no query-log assertion yet, but bounded paths are DB-push-down. |
| `DOD-12` | `Definition of Done` | Broken validation data no longer creates erroring public navigation. | `runtime` | `NAV_DEPLOY_LANE=local NAV_RUNTIME_DB_MUTATION_ALLOWED=1 ... run_web_navigation_smoke.sh diagnostic` | `browser` | `passed-local` | Original broken card scenario revalidated via the local-only seeded stale-reference diagnostic. |

## External Dependency Readiness
| Dependency | Why It Matters | Status | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `guarappari.belluga.space validation tenant` | Required for Playwright/manual public and admin runtime validation. | `ready` | `2026-06-04` | `Playwright mutation harness + backend environment probe` | Served web bundle SHA matched the local build; runtime browser assertions passed. |
| `Android device / ADB` | Optional unless Flutter public card behavior diverges by platform or TODO delivery policy requires device parity. | `waived-for-bounded-local-implementation` | `2026-06-04` | `Shared Flutter/web contract review` | This TODO's blocked regression was reproduced and closed on shared web/runtime surfaces; device parity can be consumed at package-level closeout if required. |

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `cross-stack`
- **Expected supporting profiles:** `assurance-tester-quality`; `strategic-cto-tech-lead` if project constitution or module-level invariants require direct update beyond TODO/module consolidation.
- **Scope-check command:** `python3 delphi-ai/tools/profile_scope_check.py --profile operational-coder`

### Handoff Log
| From Profile | To Profile | Why the Handoff Exists | Touched Surfaces | Status / Evidence |
| --- | --- | --- | --- | --- |
| `operational-coder` | `assurance-tester-quality` | Capability/query guard plus runtime matrix needs independent test-quality scrutiny before delivery. | Laravel guard/tests, Flutter tests, Playwright lanes | `completed`; `round-01 triple audit recorded accepted-debt resolution` |

## Complexity
- **Level (`small|medium|big`):** `big`
- **Checkpoint policy:** `section-by-section review checkpoint before APROVADO`
- **Why this level:** the slice changes backend capability semantics, query access paths, public payload contracts, Flutter navigation behavior, deterministic guardrails, and runtime validation across admin/public surfaces.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/tenant_admin_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
  - `foundation_documentation/modules/events_module.md`
- **Planned decision promotion targets:**
  - `foundation_documentation/modules/account_profile_catalog_module.md` sections for profile type capabilities, public catalog/detail contracts, nested groups, and cross-module consumers.
  - `foundation_documentation/modules/tenant_admin_module.md` sections for admin selectors/authoring behavior.
  - `foundation_documentation/modules/flutter_client_experience_module.md` sections for public card navigation and route contracts.
- **Module decision consolidation targets:**
  - Account Profile Catalog module capability/queryability decisions.
  - Flutter Client Experience card-navigation contract.
  - Tenant Admin selector eligibility contract.

## Decision Pending
- [ ] `D-QRY-01` Confirm final capability key names: recommended `is_queryable` and `is_publicly_navigable`; alternative `has_public_detail_page` for navigability.
- [ ] `D-QRY-02` Confirm whether admin repair/readback should show invalid stale references inline with a repair warning or only through a dedicated repair/audit command.

## Decisions (Resolved Before Freeze)
- [ ] `D-QRY-03` Queryability/listability is independent from public direct-detail navigability.
- [ ] `D-QRY-04` `is_queryable=false` excludes the profile type from all operational lists, dropdowns, selectors, candidate resolvers, public lists/tabs, discovery, map/filter lists, and invite/social candidate lists.
- [ ] `D-QRY-05` `is_publicly_navigable=true` controls whether `/parceiro/:slug` can resolve and whether UI cards may expose link/tap/chevron behavior.
- [ ] `D-QRY-06` `is_publicly_discoverable=true` controls public discovery/listing and must be effective only for queryable types.
- [ ] `D-QRY-07` `is_poi_enabled=true` controls map/POI participation, but non-queryable types remain excluded from map lists/filter choices.
- [ ] `D-QRY-08` Queryable + non-publicly-navigable profiles may render as non-clickable participant rows/cards when the public surface explicitly permits participant display.
- [ ] `D-QRY-09` Non-queryable stale links are suppressed from public projections rather than rendered as disabled public cards.
- [ ] `D-QRY-10` Laravel uses a canonical query gateway/scope set and capability type-set cache; operational callers must not assemble capability filters locally.
- [ ] `D-QRY-11` Deterministic guards must block bypasses outside the gateway and must report allowlisted findings with audit instructions.
- [ ] `D-QRY-12` Guard diagnostics must follow PACED deterministic-resolution discipline: they must say what failed, why it failed, how to fix it, and how to audit exceptions.

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `PCO-14` | Account Profile nested groups render only public eligible linked profiles and are capability-gated by `has_nested_profile_groups`. | `Preserve` | `account_profile_catalog_module.md` nested group section. |
| `PCO-15 candidate` | Queryability/public navigability separation is not yet a durable module decision. | `Supersede/Add` | This TODO must promote final decisions after approval/delivery. |
| `FCX-EventProfileCards` | Current module text says event linked-profile cards keep direct `/parceiro/:slug` navigation and missing slug is a payload failure. | `Supersede (Intentional)` | This TODO refines navigation: slug is insufficient; backend must expose public navigability. |
| `Tenant Admin selectors` | Existing module has admin catalog behavior but no universal queryability selector contract. | `Add` | This TODO must consolidate final selector contract. |

## Decision Baseline (Frozen Before Implementation)
- [ ] `D-QRY-BASE-01` Freeze capability key names and defaults.
- [ ] `D-QRY-BASE-02` Freeze gateway method names and required consumers.
- [ ] `D-QRY-BASE-03` Freeze allowlist categories and audit output shape.
- [ ] `D-QRY-BASE-04` Freeze public payload field names for card navigability.

## Questions To Close
- [ ] Confirm final capability key names (`is_queryable`, `is_publicly_navigable` / `has_public_detail_page`).
- [ ] Confirm admin stale-reference UX: inline repair indication vs dedicated repair/audit surface.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-QRY-01` | Account Profile type capabilities are the correct source for queryability and public navigability. | Existing `AccountProfileTypeCapabilityCatalog` and `TenantProfileType` capability fields. | A per-profile or policy-based model would be needed. | `High` | `Keep as Assumption` |
| `A-QRY-02` | Filtering by allowed profile types is the performatic path because capabilities live on profile types, not each profile. | Current public catalog scopes use `TenantProfileType` type lists and `whereIn('profile_type', ...)`. | Need a different query/index design. | `High` | `Keep as Assumption` |
| `A-QRY-03` | A global Eloquent scope alone is too risky because repair/audit/readback paths must see invalid or non-queryable data. | Current repair, registry, social, map, and event integration code uses direct `AccountProfile` reads. | Global scope may be safe if every bypass is explicit, but still requires guard policy. | `Medium` | `Keep as Assumption` |
| `A-QRY-04` | Flutter can preserve card design while making navigation conditional from domain payload fields. | Existing card widgets already own tap/chevron behavior. | Requires a broader shared card abstraction first. | `High` | `Keep as Assumption` |
| `A-QRY-05` | Existing invalid validation data can be cleaned or suppressed without production migration concerns. | User confirmed this is a test environment and stale invalid records may be cleaned. | Need a migration/repair plan with production-safe behavior. | `Medium` | `Keep as Assumption` |

## Execution Plan (Required Before APROVADO)
Execution planning describes **HOW** Delphi intends to deliver the TODO contract. It must stay subordinate to the contract.

### Touched Surfaces
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `laravel-app/app/Application/AccountProfiles/AccountProfileTypeCapabilityCatalog.php`
- `laravel-app/app/Application/AccountProfiles/**Query*`
- `laravel-app/app/Models/Tenants/TenantProfileType.php`
- `laravel-app/app/Models/Tenants/AccountProfile.php`
- `laravel-app/app/Integration/Events/AccountProfileResolverAdapter.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/app/Application/DiscoveryFilters/**`
- `laravel-app/app/Integration/DiscoveryFilters/**`
- `laravel-app/app/Integration/MapPois/**`
- `laravel-app/app/Application/Social/**`
- `laravel-app/scripts/architecture_guardrails.php`
- `laravel-app/tests/Unit/Guardrails/**`
- `laravel-app/tests/Feature/**`
- `flutter-app/lib/domain/schedule/**`
- `flutter-app/lib/domain/partners/**`
- `flutter-app/lib/infrastructure/dal/**`
- `flutter-app/lib/presentation/tenant_public/**`
- `flutter-app/lib/presentation/tenant_admin/**`
- `flutter-app/test/**`
- `tools/flutter/web_app_tests/**`

### Ordered Steps
1. Confirm final capability names and stale-reference admin UX.
2. Add fail-first Laravel capability catalog tests for independent `is_queryable` and public navigability.
3. Add fail-first Laravel feature tests for queryability exclusion across selectors/lists/public projections.
4. Add fail-first Laravel direct-detail tests for public navigability.
5. Add fail-first guardrail tests/source-scan expectations for raw `AccountProfile` query bypasses and allowlisted audit output.
6. Implement canonical type-set provider/gateway/scopes with request memoization and cache invalidation on profile-type changes.
7. Migrate operational selectors/lists/candidates to gateway methods.
8. Add public projection fields for card navigability.
9. Add Flutter DTO/domain support for explicit card navigability.
10. Update Flutter card widgets to use explicit navigation contract and negative no-navigation behavior.
11. Repair/suppress the known invalid validation data path and re-run the original browser reproduction.
12. Run focused tests, full local CI-equivalent matrix, Playwright runtime lanes, guard audits, and delivery gates.

### Test Strategy
- **Strategy:** `test-first`
- **Why:** the observed bug is a false-green contract gap. The fix must be defined by failing tests before implementation.
- **Fail-first targets:** Laravel capability catalog tests, Laravel event selector/public projection tests, Laravel guardrail tests, Flutter DTO/card navigation tests, Playwright reproduction of the broken card.

### PACED Deterministic Guard Requirements
- Before creating a new canonical helper, inspect `delphi-ai/tools/manifest.md`. Prefer extending project-local `laravel-app/scripts/architecture_guardrails.php` or adding a project-local guard invoked by it unless the behavior is generalized into Delphi/PACED.
- The guard must produce deterministic categories:
  - `Blocked findings`: operational raw queries that must move to gateway/scopes.
  - `Allowlisted findings requiring audit`: permitted raw reads that still require reviewer scrutiny.
  - `Resolution instructions`: exact next action for each finding type.
  - `Allowlist policy`: valid reason categories, owner, and why moving code into an allowlisted path is not sufficient.
  - `Performance expectation`: type-set filtering, bounded queries, no in-memory per-profile capability filtering.
- Valid allowlist reasons:
  - `canonical_gateway`
  - `repair_readback`
  - `audit_readback`
  - `admin_readback`
  - `migration`
  - `seeder`
  - `test_fixture`
- Invalid allowlist use must fail:
  - broad folder allowlist without reason;
  - `admin_readback` used for operational selector/list;
  - missing owner/rationale;
  - new allowlisted finding not recorded in the baseline/snapshot or TODO evidence.
- Required audit checklist printed for allowlisted findings:
  1. Confirm this is not a user-facing selector/list/candidate path.
  2. Confirm it cannot leak non-queryable profiles to public/admin choices.
  3. Confirm the query is bounded and indexable.
  4. Confirm the path has a functional or guard test proving downstream behavior.
  5. Confirm the code was not moved into the allowlisted path merely to bypass the rule.

### Performance Contract
- Resolve allowed type sets once per tenant/request and optionally persistent-cache them by tenant + capability version.
- Invalidate persistent cache on `TenantProfileType` create/update/delete and registry-seeder changes.
- Apply allowed sets through direct query constraints such as `whereIn('profile_type', $allowedTypes)`.
- Preserve direct indexed slug/id lookup for detail/readback paths.
- Do not fetch broad Account Profile collections and filter capability eligibility in memory.
- Do not query `TenantProfileType` once per Account Profile.
- Verify or add indexes for the common predicates:
  - `profile_type`
  - `is_active`
  - `visibility`
  - `slug`
  - map/location fields where map participation is filtered.

### Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Event related-profile selector excludes non-queryable profiles | Admin selector/mutation | `shared-android-web` | `Playwright mutation + Laravel feature` | `yes` | `yes` | Admin creates/edits event and cannot select non-queryable profile. | `n/a` |
| Occurrence related-profile selector excludes non-queryable profiles | Admin selector/mutation | `shared-android-web` | `Flutter widget + Laravel feature` | `yes` | `yes` | Occurrence editor candidate list test and backend validation. | `n/a` |
| Nested Account Profile selector excludes non-queryable profiles | Admin selector/mutation | `shared-android-web` | `Playwright mutation or widget + Laravel feature` | `yes` | `yes` | Account profile group editor selection test. | `n/a` |
| Public event groups suppress non-queryable stale links | Public visible navigation | `shared-android-web` | `Playwright readonly + Laravel feature` | `no` | `yes` | Original broken event/card scenario. | `n/a` |
| Queryable non-navigable card renders without navigation | Public visible navigation | `shared-android-web` | `Flutter widget + Playwright readonly if web-visible fixture exists` | `no` | `yes` | Negative route-push assertion. | `n/a` |
| Public navigable card opens `/parceiro/:slug` | Public navigation | `shared-android-web` | `Flutter widget + Playwright readonly` | `no` | `yes` | Positive route-push assertion and browser route load. | `n/a` |
| Guard blocks raw query bypasses | Structure/process | `n/a` | `n/a` | `no` | `no` | Unit guard test and script output. | Structure-only; no runtime UI lane required. |
| Guard reports allowlisted findings with audit instructions | Structure/process | `n/a` | `n/a` | `no` | `no` | Unit guard test and script output snapshot. | Structure-only; no runtime UI lane required. |

### Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app` focused capability/queryability tests | Backend capability semantics, selectors, public projections, and guardrails change. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh <focused tests>` | `Local-Implemented` | `passed-local` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfileTypesControllerTest.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Guardrails/AccountProfileQueryabilityGuardrailsTest.php` | `272 passed (1456 assertions)` in the bounded recut. |
| `laravel-app` full/CI-equivalent suite | Backend public/admin contracts change broadly. | project-owned Laravel CI-equivalent command | `promotion` | `pending-package-closeout` | `pending` | Held for the package-wide v0.2.0+8 promotion pass. |
| `flutter-app` focused DTO/widget/navigation tests | Public card navigation and admin selector behavior change. | `cd flutter-app && fvm flutter test --no-pub <focused tests>` | `Local-Implemented` | `passed-local` | `cd flutter-app && fvm flutter test --no-pub test/infrastructure/dal/laravel_account_profiles_backend_test.dart test/presentation/tenant_public/discovery/widgets/discovery_account_profile_visuals_test.dart test/presentation/tenant/home/screens/tenant_home_screen/widgets/favorite_section/controllers/favorites_section_controller_origin_flow_test.dart`; `cd flutter-app && fvm flutter test --no-pub test/presentation/tenant/map/screens/map_screen/controllers/map_screen_controller_test.dart --plain-name "account profile poi"` | Positive/negative navigation surfaces passed. |
| `flutter-app` analyzer | Flutter domain/DTO/UI changes. | `cd flutter-app && fvm dart analyze --format machine` | `Local-Implemented` | `bounded-pass` | `cd flutter-app && fvm dart analyze <bounded touched files>` | Repo-wide analyzer still has unrelated lane debt outside this TODO; bounded touched files are clean. |
| `flutter-app` rule matrix | Flutter rule/route/card changes may interact with analyzer plugin. | `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `Local-Implemented` | `passed-local` | `cd flutter-app && bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` | `success: expected 58 lint codes were detected.` |
| `flutter web build` | Browser validation requires refreshed bundle. | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output` | `runtime validation` | `passed-local` | `cd flutter-app && CLEAN_OUTPUT=1 BUILD_HEARTBEAT_SECONDS=30 bash scripts/build_web.sh ../web-app dev --clean-output`; `docker compose restart nginx` | Served `main.dart.js` SHA matched the local build. |
| `Playwright admin/public lanes` | User-visible selector/card behavior requires real navigation evidence. | `tools/flutter/run_web_navigation_smoke.sh readonly` plus local-only `diagnostic` for seeded stale-reference/runtime repair coverage | `promotion` | `passed-local` | `NAV_DEPLOY_LANE=local NAV_RUNTIME_DB_MUTATION_ALLOWED=1 NAV_WEB_ALLOW_RAW_GREP=1 NAV_WEB_GREP_EXTRA='QRY-RUNTIME' bash tools/flutter/run_web_navigation_smoke.sh diagnostic` | Promotion consumes the carried-forward local diagnostic evidence for the stale-reference path and may rerun readonly/public lanes separately. |

### Runtime / Rollout Notes
- Existing validation data with non-queryable stale links should be cleaned or suppressed before manual validation is repeated.
- If this TODO adds persistent cache, rollout must include cache invalidation and a safe command to clear stale cache.
- If direct public navigability is enabled for a non-queryable type, it must still remain absent from lists/selectors/discovery/map filters.

## Plan Review Gate
### Review Sections
- [x] Architecture
- [x] Code Quality
- [x] Tests
- [x] Performance
- [x] Security
- [x] Elegance
- [x] Structural Soundness

### Issue Cards
- **Issue ID:** `ARCH-QRY-01`
  - **Severity:** `high`
  - **Evidence:** `AccountProfileResolverAdapter::queryRelatedAccountProfileCandidates` and `LinkedProfileCategorySection` currently show local query/navigation assumptions.
  - **Why it matters now:** Local filtering and local navigation inference create false-green tests and broken public routes.
  - **Option A (Recommended):** canonical gateway + explicit payload navigability + deterministic guard.
    - **Effort:** `high`
    - **Risk:** `medium`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `low`
    - **Performance impact:** `improves`
    - **Elegance impact:** `improves`
    - **Structural soundness impact:** `improves`
  - **Option B:** add local filters only to Event selectors and public cards.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `local`
    - **Maintenance burden:** `high`
    - **Performance impact:** `neutral`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Option C:** do nothing and clean test data only.
    - **Effort:** `low`
    - **Risk:** `high`
    - **Blast radius:** `cross-module`
    - **Maintenance burden:** `high`
    - **Performance impact:** `unknown`
    - **Elegance impact:** `regresses`
    - **Structural soundness impact:** `regresses`
  - **Recommendation:** Option A. The user explicitly requires deterministic process guarantees, not another remembered convention.

### Failure Modes & Edge Cases
- [ ] A profile type is queryable but not publicly navigable: it may display as a participant only where allowed and must not navigate.
- [ ] A profile type is publicly navigable but not queryable: direct route may resolve, but the profile must not appear in lists/selectors/discovery/map filters.
- [ ] A stale event/group reference points to a non-queryable profile: public projections suppress it; repair/audit can still see it.
- [ ] A developer adds a new selector and uses raw `AccountProfile::query()`: guard blocks with resolution instructions.
- [ ] A developer hides bypass in an allowlisted path: allowlisted finding report forces audit and baseline review.
- [ ] Capability cache gets stale after profile-type update: invalidation/clear path must be tested.
- [ ] Empty allowed type set: query must fail closed and return no candidates, not all candidates.

### Residual Unknowns / Risks
- [ ] Final capability key names need approval before freeze.
- [ ] Exact stale-reference admin UX needs approval before freeze.
- [ ] Some existing consumers may intentionally use Account Profile readback and will need allowlist classification.

## Audit Trigger Matrix
| Trigger | Classification | Rationale | Required Gate |
| --- | --- | --- | --- |
| Backend capability/query contract | `required` | Cross-module backend behavior and selector eligibility change. | `Independent No-Context Critique` |
| Deterministic guard/script behavior | `required` | Process enforcement is part of the deliverable. | `Rule-Spirit Anti-Pattern Hunt` |
| User-visible public/admin flow | `required` | Runtime card/selector behavior changes. | `Test Quality Audit + Playwright evidence` |
| Performance-sensitive query path | `required` | Avoid N+1/broad in-memory filtering. | `Endpoint Performance Scrutiny` |
| Security/tenant leakage | `recommended` | Query gateway must preserve tenant boundaries. | `Security review if touched code crosses tenant access` |

## No-Context Orchestration Readiness
- **Ready for no-context worker dispatch:** `yes after APROVADO`.
- **Worker package minimum:** this TODO, `PTODO-009` notes, Account Profile Catalog module, Tenant Admin module, Flutter Client Experience module, Event groups TODO for related profile context, code pointers listed in `Touched Surfaces`, original reproduction route, and guard output requirements.
- **Orchestrator-owned checks:** ensure the worker does not implement local one-off filters only, does not use a global scope as the sole enforcement mechanism, records guard allowlist audit output, and validates the original broken route/card scenario.

## Approval
- **Approved by:** explicit user implementation instruction in the current v0.2.0+8 lane conversation.
- **Approved at:** `2026-06-04`
- **Approval evidence:** implementation approved by the user's 2026-06-04 instruction to execute the remaining findings with `TODO DRIVEN` orchestration and quality gates, after restating the concrete gaps: tabs not aggregating as expected, `Outro Grupo` still linking, and missing WP-like capabilities.
- **Approval reference:** thread-local approval on `2026-06-04`; user instruction: "Eu quero que você faça essas implementações usando o TODO DRIVEN e sua skill de orquestração, garantindo todas as entregas com qualidade."
- **Approval scope:** queryability/listability and public-navigability capability foundation for Account Profile types; canonical selector/query gating for related-profile and nested-group candidates; suppression of non-queryable profiles from public event/account surfaces; explicit public card navigability contract so Flutter stops inferring navigation from `slug`; and deterministic Laravel/PACED guardrails required to keep this contract from regressing. Taxonomy canonicalization/refactor remains outside this TODO.
- **Renewal required:** `yes if capability names, public contract, guard policy, or stale-reference UX changes after approval`

## Rules Acknowledgement / Ingestion
| Source | Why It Applies Now | Must Preserve | Must Avoid | Execution Impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | This is a big tactical TODO and no implementation may happen before `APROVADO`. | Contract/refinement/approval/delivery gates. | Code changes before approval. | Approval and authority guard required before execution. |
| `delphi-ai/main_instructions.md` deterministic-resolution discipline | The guard is required to teach/audit, not just pass/fail. | Diagnostic output with resolution instructions. | Silent allowlists or vague violations. | Guard output shape is part of DoD. |
| `/home/elton/Dev/repos/delphi-ai/skills/endpoint-performance-scrutiny/SKILL.md` | Query path quality and direct indexed lookups are central to this TODO. | Direct indexed slug/id lookups and type-set filtering. | Broad fetch + in-memory filtering. | Add performance/query-shape validation. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | The bug is a false-green gap and requires fail-first tests. | Semantic assertions and negative-path coverage. | Status-only tests. | Tests before implementation. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-quality-audit/SKILL.md` | Broad cross-stack test matrix is required. | Audit for weak tests and bypasses. | Mock-only confidence for runtime behavior. | Required before delivery. |

## Decision Adherence (Required Before Delivery)
| Decision | Implementation Evidence | Status | Notes |
| --- | --- | --- | --- |
| `D-QRY-03..12` | `bounded package + round-01 resolution + focused/backend/browser evidence` | `satisfied-local` | Remaining accepted debt is non-blocking and recorded in the triple-audit resolution. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| `no-context review` | Capability separation, query gateway, guard policy, performance, UI contract | `completed-local` | `foundation_documentation/artifacts/todo-audits/account-profile-queryability-navigation-contract/triple-audit-session/round-01/*` | `no blocking findings` | Round 01 adjudicated as accepted non-blocking debt; no further bounded audit round required for local closeout. |

## Rule-Spirit Anti-Pattern Hunt
| Surface | Anti-Pattern Checked | Status | Evidence | Findings | Resolution |
| --- | --- | --- | --- | --- | --- |
| Laravel selectors/lists | Local capability filters, raw queries, broad allowlists | `completed-local` | Guard script + focused code review + round-01 resolution | `non-blocking debt only` | Canonical query owners are centralized; allowlist completeness is enforced; upper-bound allowlist cap is accepted debt. |
| Flutter cards | Navigation inferred from slug only | `completed-local` | Focused Flutter tests + browser runtime + round-01 resolution | `non-blocking debt only` | Primary public card/event surfaces now honor explicit navigability; map POI fallback remains accepted debt for a future slice. |
| Tests | Representative-only coverage instead of matrix coverage | `completed-local` | Focused/backend/browser matrix + triple audit | `non-blocking debt only` | Remaining debt is documented in the audit resolution; required bounded regression paths are now covered. |

## TODO Closeout Disposition
- **Disposition:** `keep-active`
- **Reason:** `Local implementation, focused validation, and triple-audit adjudication are complete, but the TODO remains on the active lane until the package-wide v0.2.0+8 promotion/CI-equivalent packet absorbs this evidence and accepted-debt ledger.`
- **Closeout target:** `promote with the package-wide v0.2.0+8 lane after consolidated CI-equivalent and promotion evidence`
- **Do not close while:** `package-level promotion evidence is still pending or accepted-debt notes have not been carried into the lane closeout packet.`
