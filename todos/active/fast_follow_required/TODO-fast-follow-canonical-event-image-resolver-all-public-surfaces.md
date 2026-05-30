# TODO (Fast Follow Bugfix): Canonical Event Image Resolver Across Public Surfaces

## Title
Fast Follow Bugfix: Canonical Event Image Resolver Across Public Surfaces

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
The previous event-cover fix corrected the public event detail route for
`/agenda/evento/arraia-apaexonados?occurrence=6a147bd30a65fb8d0f0d00d9`, but it did not establish canonical image resolution for every public event surface.

Runtime evidence captured on `2026-05-25` after production `main` deploy:

- Public detail page now renders the Event cover correctly.
- Authenticated production `GET /api/v1/agenda?page=1&page_size=50` still returns the same event card payload with:
  - `event_id: 6a147bd30a65fb8d0f0d00d8`
  - `occurrence_id: 6a147bd30a65fb8d0f0d00d9`
  - `thumb: null`
  - `venue.cover_url: https://guarappari.com.br/api/v1/media/account-profiles/69ccdc9a46e4e6c13a0495a9/cover?v=1777601029`
- Flutter `VenueEventResume.resolvePreferredImageUri()` then follows its current fallback chain and chooses Venue media because the backend payload did not expose the Event image for the card surface.

Code evidence:

- `EventHeroImageResolver` exists and defines the intended order: Event `thumb`, linked account profiles, then venue/location media.
- Current usage is limited to `PublicWebMetadataService` and `InviteTargetReadAdapter`.
- `EventQueryService` formats public agenda/detail payloads directly and does not use `EventHeroImageResolver`.
- The previous fix only overlaid parent Event `thumb` inside `formatEventDetail()`, leaving public agenda/list/card occurrence payloads stale.
- Recalibration on `2026-05-25`: the current Flutter fallback is downstream defensive behavior. The backend must send a complete event image contract. The canonical correction belongs in Laravel: every backend event-image decision must delegate to the same resolver.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `canonical-event-image-resolver-public-surfaces`
- **Why this is the right current slice:** one production-visible regression proves the canonical resolver was not wired into all backend public event image providers. The desired behavior is one coherent backend contract: card, hero, invite, and OG image selection must come from the same resolver decision.
- **Direct-to-TODO rationale:** the failing production payload, missing code path, and regression boundary are concrete; separate feature decomposition would add no useful discovery.

## Contract Boundary
- This TODO establishes the Laravel canonical event image decision as the single source for public event image URLs.
- It covers Laravel public event payloads and every Laravel adapter/service that supplies event images to cards, hero/detail payloads, invites, and OG metadata.
- It may touch Laravel event query/resolver/guardrail/tests only.
- Flutter is explicitly out of implementation scope for this TODO. Flutter may keep its defensive fallback, but backend payloads must not require Flutter to infer the correct event image from Venue fallback data.
- It must preserve existing media storage, upload authorization, event cover media routes, invite image contract, and OG metadata behavior.
- It must not introduce a new storage architecture, new image upload flow, new public route, or unrelated card redesign.

## Delivery Status Canon
- **Current delivery stage:** `Local-Implemented`
- **Qualifiers:** `Fast-Follow`, `Production-Visible`, `Laravel`, `Backend-Only`, `Regression-Fix`, `Canonical-Image-Contract`
- **Next exact step:** promote the Laravel feature branch through the approved promotion lane when requested.
- **Promotion lane path:** `feature -> dev -> stage -> main`

## Scope
- [x] Add Laravel fail-first coverage proving public agenda/card payloads use the canonical Event image when an occurrence has stale or missing `thumb`.
- [x] Ensure public event detail, public agenda, and any full public event payload expose a canonical event image URL resolved by `EventHeroImageResolver`.
- [x] Preserve `thumb` as the Event cover payload for compatibility, while adding/using a canonical image field for UI consumers.
- [x] Cover single-occurrence and multi-occurrence event payloads with intentionally different Event, linked Account Profile, and Venue image URLs so the selected winner is unambiguous.
- [x] Cover each fallback step in the resolver order: Event cover, linked Account Profile cover, linked Account Profile avatar, Venue cover, Venue hero/avatar/logo only when higher-priority Venue candidates are absent, and `null` when no valid candidate exists.
- [x] Ensure all backend surfaces that request or expose an Event image use `EventHeroImageResolver` as the only image-selection authority.
- [x] Keep invite and OG image behavior routed through the same Laravel resolver.
- [x] Add a static/guardrail test that fails if any Laravel public Event image payload builder or adapter bypasses `EventHeroImageResolver` again.
- [x] Record why the previous delivery was still false-green after the detail fix.

## Out of Scope
- Replacing `belluga_media` storage.
- Changing upload/crop behavior.
- Changing Venue or Account Profile image resolution outside the event-image fallback chain.
- Redesigning event cards or immersive hero UI.
- Flutter DTO/domain/projection changes.
- Production data repair scripts unless read-time/payload-level canonicalization cannot protect stale records.
- Broad admin-management behavior unless it consumes the same public event read model.

## Definition of Done
- [x] `GET /api/v1/agenda` returns a canonical event image for the production-equivalent stale-occurrence case and does not force Flutter to fall back to Venue while Event cover exists.
- [x] `GET /api/v1/events/{event}?occurrence={occurrence}` and public metadata/OG continue to resolve to the same canonical Event cover for this case.
- [x] Single-occurrence and multi-occurrence tests assert the exact returned image URL for every resolver fallback scenario, with different fixture URLs for Event cover, linked Account Profile cover/avatar, and Venue media.
- [x] Invite image and OG image tests remain green and continue to use `EventHeroImageResolver`.
- [x] A Laravel guardrail blocks adding another backend public Event image fallback chain outside the resolver.
- [x] Production-equivalent runtime evidence is captured after implementation and before promotion.

## Validation Steps
- [x] RED: Laravel agenda/card regression test fails before implementation because `thumb`/canonical image is missing and Venue would win.
- [x] GREEN: Laravel focused event agenda/detail/metadata tests pass after implementation.
- [x] GREEN: Laravel canonical-image guardrail test passes and proves `EventQueryService` depends on or delegates to `EventHeroImageResolver`.
- [x] GREEN: Laravel invite image tests pass and prove invite image selection still delegates to `EventHeroImageResolver`.
- [x] Run Laravel focused CI-equivalent suites for every touched file before claiming local completion.

### Required Fallback Scenario Matrix
Each scenario must use different fixture URLs for every available candidate and must assert the exact returned image URL in the backend payload. "Returned image" covers the canonical image field and the compatibility `thumb` payload when the surface exposes it.

| Scenario ID | Occurrence Shape | Available Candidates | Expected Returned Image |
| --- | --- | --- | --- |
| `IMG-01` | Single occurrence | Event cover + linked Account Profile cover/avatar + Venue cover/hero/avatar/logo | Event cover |
| `IMG-02` | Multiple occurrences, selected occurrence has stale or missing occurrence `thumb` | Parent Event cover + linked Account Profile cover/avatar + Venue cover | Parent Event cover |
| `IMG-03` | Single occurrence | No Event cover; linked Account Profile cover + linked Account Profile avatar + Venue cover | Linked Account Profile cover |
| `IMG-04` | Single occurrence | No Event cover; no linked Account Profile cover; linked Account Profile avatar + Venue cover | Linked Account Profile avatar |
| `IMG-05` | Single occurrence | No Event cover; no linked Account Profile media; Venue cover + Venue hero/avatar/logo | Venue cover |
| `IMG-06` | Single occurrence | No Event cover; no linked Account Profile media; no Venue cover; Venue hero + Venue avatar/logo | Venue hero |
| `IMG-07` | Single occurrence | No Event cover; no linked Account Profile media; no Venue cover/hero; Venue avatar + Venue logo | Venue avatar |
| `IMG-08` | Single occurrence | No Event cover; no linked Account Profile media; no Venue cover/hero/avatar; Venue logo | Venue logo |
| `IMG-09` | Single occurrence | No Event cover; no linked Account Profile media; no Venue media | `null` canonical image and no fabricated fallback |
| `IMG-10` | Single occurrence with `event_parties` metadata instead of `linked_account_profiles` | Non-venue party metadata cover/avatar + Venue party metadata cover + Venue cover | Non-venue linked Account Profile metadata cover; party type `venue` is ignored as a linked-profile candidate |

## Mandatory Bug-Fix Questions
| Question | Answer |
| --- | --- |
| Do we already have tests that cover this behavior across all stages up to UI display? | No. Detail, metadata, invite resolver, and Flutter local fallback have coverage, but no test proved public agenda/card payloads use the canonical resolver after stale occurrence reads. |
| Did we inspect current real database/backend payloads to verify compatibility with current parsing and rendering assumptions? | Yes. Production agenda payload for `Arraiá APAExonados` returned `thumb:null` and Venue media, proving the card path is still stale. |
| If existing tests should cover this bug, which tests failed? If none failed, why were they insufficient? | None failed. The previous tests covered `formatEventDetail()` and metadata/invite surfaces but not the agenda/list payload that cards consume. The static guardrail only prevents local fallback chains; it did not require `EventQueryService` to delegate to the resolver. |
| If tests do not cover the failure, which new tests must be created before implementing the fix? | Laravel agenda/detail canonical image tests for stale single-occurrence and multi-occurrence payloads, explicit resolver fallback tests for Event cover, linked Account Profile cover/avatar, Venue cover/hero/avatar/logo, invite/metadata continuity tests where needed, and a resolver-usage guardrail for public backend event-image payload builders/adapters. |
| Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? | `rule-candidate`. The resolver existed but key backend public payload builders did not depend on it. A Laravel static guardrail can require backend event-image payload builders/adapters to delegate to `EventHeroImageResolver` and forbid reintroducing independent fallback chains. |

## Coverage Matrix
| Stage | Current Status | Required Closure |
| --- | --- | --- |
| Event cover storage/media route | `covered` | Existing upload/media tests stay green. |
| Canonical Laravel event image resolver | `partial` | Resolver must be used by payload builders, not only metadata/invite adapters; fallback tests must assert the exact winner for each priority step. |
| Public event detail API | `patched-but-not-canonical` | Detail must expose canonical image field from resolver and preserve Event cover `thumb`; selected occurrence fixtures must prove single- and multi-occurrence behavior. |
| Public agenda/card API | `false-green` | New test must prove stale occurrence cards get Event cover/canonical URL, not Venue fallback, for both single- and multi-occurrence shapes. |
| Invite image | `covered` | Existing resolver-backed invite tests stay green. |
| Public HTML metadata/OG | `covered` | Existing resolver-backed metadata tests stay green. |
| Flutter DTO/domain projection | `out-of-scope` | No implementation needed in this TODO; Flutter remains a consumer of backend payloads and may keep defensive fallback only. |
| Flutter UI rendering | `backend-contract-dependent` | The card must receive the correct Event image from Laravel; UI verification may be runtime evidence after backend deploy, not Flutter code change. |

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** one planning review checkpoint before approval.
- **Why this level:** the defect is one conceptual backend behavior but spans multiple Laravel public event image providers and adds a resolver-usage guardrail.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel/backend`
- **Supporting profiles:** `assurance-tester-quality`, `operational-devops` for promotion only after local completion
- **Handoff log:** none yet; implementation waits for `APROVADO`.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:** none for implementation scope.
- **Planned decision promotion targets:**
  - Events module: public event image resolution is resolver-owned and applies to detail, agenda/card, invite, and OG surfaces.
- **Module decision consolidation targets:** `foundation_documentation/modules/events_module.md#5.1-read-model-api-contracts`

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` `EventHeroImageResolver` is the canonical Laravel authority for public Event image URL resolution.
- [x] `D-02` Public Event payloads that can feed a UI image must expose a resolver-produced canonical image URL.
- [x] `D-03` Public occurrence payloads must use parent Event image authority for Event-owned covers, even when occurrence snapshots are stale.
- [x] `D-04` Backend public event-image providers/adapters must not implement independent image fallback chains outside `EventHeroImageResolver`.
- [x] `D-05` Client-side fallback behavior is defensive only; Laravel must provide complete event-image payloads.
- [x] `D-06` Test fixtures must assign distinct URLs to Event cover, linked Account Profile cover/avatar, and Venue media so each scenario proves the resolver returned the intended image rather than any available image.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | The public card is fed by `/api/v1/agenda` or equivalent EventQueryService occurrence payloads. | Production agenda payload reproduced the exact stale image shape; Flutter event search converts EventModel to VenueEventResume. | Need identify another card source and extend coverage there too. | `High` | `Promote to Decision D-02` |
| `A-02` | Adding a canonical image URL field is compatible with existing consumers without changing Flutter code in this slice. | Existing clients tolerate extra JSON fields; preserving `thumb` as Event cover compatibility lets current clients stop falling to Venue. | If false, keep only the `thumb` compatibility overlay in this slice. | `High` | `Promote to Decision D-02` |
| `A-03` | Read-time parent Event enrichment for stale occurrences can be batched to avoid N+1 list queries. | Agenda already slices occurrence results; parent Event IDs can be collected and loaded in one query before formatting. | Need pipeline lookup or narrower compatibility overlay. | `Medium` | `Keep as Assumption` |

## Execution Plan
### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventHeroImageResolver.php` if normalization support is needed
- `laravel-app/app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php`
- `laravel-app/tests/Feature/Events/AgendaAndEventsControllerTest.php`
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `laravel-app/tests/Unit/Events/EventHeroImageResolverTest.php`
- `laravel-app/tests/Unit/Events/EventQueryServiceTest.php`
- `laravel-app/tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php`

### Ordered Steps
1. Add RED Laravel agenda regression for stale occurrence `thumb` with parent Event cover and Venue media present.
2. Add RED Laravel guardrail proving public Event payload builders delegate to `EventHeroImageResolver`.
3. Add RED Laravel resolver/payload tests for the required fallback scenario matrix (`IMG-01` through `IMG-10`).
4. Add/adjust Laravel detail/metadata/invite continuity tests only where needed to prove all backend event-image surfaces share the resolver.
5. Implement Laravel canonical payload enrichment: batch-load parent Event image context for occurrence lists, preserve Event cover `thumb`, and set a resolver-produced canonical image field.
6. Run focused Laravel tests.
7. Re-run production-equivalent payload probe on the local/runtime target before promotion.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first targets:**
  - Agenda payload for stale occurrence returns Event cover/canonical image and not Venue image.
  - Single-occurrence and multi-occurrence fixtures return the exact expected image URL for every required fallback scenario.
  - Guardrail fails while Laravel public event-image providers/adapters do not reference/delegate to `EventHeroImageResolver`.
  - Detail, invite, and OG resolver-backed behavior remains green after EventQueryService adopts the canonical resolver.

### Runtime / Rollout Notes
- Backend should keep `thumb` compatibility so currently deployed Flutter/web card consumers stop falling to Venue as soon as Laravel is deployed.
- Explicit Flutter adoption of the canonical image field is not part of this TODO. The backend must send correct event-image payloads without relying on client fallback changes.

## Plan Review Gate
| Area | Assessment |
| --- | --- |
| Architecture | The previous implementation violated the intended Laravel resolver ownership. The correct shape is resolver-owned canonical URL plus compatibility payloads, not separate backend fallback logic per public event-image provider. |
| Code Quality | Avoid N+1 parent lookups; enrich occurrence lists in batch or in the aggregation pipeline. |
| Tests | Must include Laravel payload semantics, the required fallback matrix with distinct fixture URLs, and resolver-usage guardrails; status-only tests are insufficient. |
| Performance | Agenda/list enrichment must remain bounded by page size and avoid per-card queries. |
| Security | No auth/media access policy change is planned; public image URLs already exist. |
| Elegance | Preserve `thumb` as Event cover payload and expose canonical image URL from one backend resolver decision. |
| Structural Soundness | Add a guardrail that catches resolver existence without usage, the exact failure mode here. |

## Approval
- **Approved by:** user in chat
- **Approved at:** `2026-05-25T20:47:47-03:00`
- **Approval phrase/reference:** `APROVADO`
- **Approval scope:** backend-only Laravel canonical event image resolver implementation and tests described in this TODO.
- **Explicit exclusions:** Flutter code/tests, storage architecture changes, upload/crop flow changes, unrelated card UI redesign.
- **Renewed approval required if:** implementation needs Flutter changes, a new public route/API contract beyond canonical image field/`thumb` compatibility, production data repair scripts, or any unrelated admin-management behavior.

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Laravel code/test changes require tactical TODO authority and `APROVADO`. | No code edits before approval; criterion-specific delivery evidence. | Implementing under chat-only mandate. | Stop at TODO/planning until approval. |
| `/home/elton/Dev/repos/delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | Production-visible false-green regression. | Real payload evidence, fail-first tests, architecture-prevention assessment. | Treating current green checks as sufficient. | RED tests are mandatory. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | New Laravel regression/guardrail tests are required. | Semantic assertions on selected image URL and resolver usage. | Status-only or post-fix-only tests. | Tests must fail before implementation. |
| `/home/elton/Dev/repos/delphi-ai/skills/rule-laravel-shared-todo-driven-execution-model-decision/SKILL.md` | Laravel-scoped implementation requires TODO authority and approval. | Backend-only execution boundary and Laravel safe runner validation. | Cross-stack implementation drift without renewed approval. | Flutter code is out of scope unless the TODO is explicitly re-approved. |

## Audit Trigger Matrix
- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-canonical-event-image-resolver-all-public-surfaces.md`
- **Latest evidence:** Initial run required the matrix; matrix added during Local-Implemented evidence completion.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `medium` | Backend-only but public contract and guardrail change. |
| `blast_radius` | `cross-module` | Events read model plus one account-profile agenda consumer. |
| `behavioral_change_or_bugfix` | `yes` | Production-visible stale agenda/card image regression. |
| `changes_public_contract` | `yes` | Public event payload now exposes resolver-produced `hero_image_url` and correct Event-cover `thumb` compatibility. |
| `touches_auth_or_tenant` | `no` | No auth, ability, tenant resolution, or media authorization change. |
| `touches_runtime_or_infra` | `no` | No worker, queue, deploy, ingress, or infrastructure surface changed. |
| `touches_tests` | `yes` | Adds/updates Laravel feature/unit/guardrail tests. |
| `critical_user_journey` | `yes` | Tenant-public event cards and detail hero images are user-visible. |
| `release_or_promotion_critical` | `yes` | Regression is visible on production public event surfaces. |
| `high_severity_plan_review_issue` | `no` | No unresolved high-severity plan issue. |
| `explicit_three_lane_request` | `no` | User requested implementation, not a three-lane external audit. |

## Derived Audit Floor Decisions
| Audit | Decision | Gate | Workflow | Status |
| --- | --- | --- | --- | --- |
| Critique | `required` | `before_aprovado` | `wf-docker-independent-critique-method` | `late bounded self-review completed after audit matrix guard; no scope or design change found` |
| Test-quality audit | `required` | `before_completed` | `wf-docker-independent-test-quality-audit-method` | `completed for Local-Implemented; medium heuristic reviewed as non-blocking for new semantic tests` |
| Final review | `required` | `before_completed` | `wf-docker-independent-final-review-method` | `completed for Local-Implemented` |
| Triple review | `required` | `before_completed` | `audit-protocol-triple-review` | `completed`: Round 01 found `PERF-001` and `TQ-001`; both were fixed. Round 02 returned clean across Elegance, Performance, and Test Quality with zero findings; runner recommendation-string conflict adjudicated as non-material. |
| Security review | `recommended` | `before_completed` | `security-adversarial-review` | `focused public-payload review completed for Local-Implemented` |
| Performance/concurrency | `recommended` | `per_pcv1_gate_deadlines` | `wf-docker-performance-concurrency-validation-method` | `focused batch-query/no-N+1 review completed for Local-Implemented` |
| Verification debt | `required` | `before_completed` | `verification-debt-audit` | `completed for Local-Implemented; inline code debt none` |

## Independent Critique Gate
- **Critique decision:** `required`
- **Timing note:** The audit matrix was added during Local-Implemented evidence completion, so the deterministic critique requirement was discovered after `APROVADO`. This is a process-order miss, not an implementation scope change; the user-approved backend-only scope remains unchanged.
- **Package mode:** `bounded self-review`
- **Critique result:** `passed`
- **Findings:** none requiring code change, renewed approval, or scope split.
- **Review summary:** The implemented shape preserves the approved architecture because `EventQueryService` delegates canonical image selection to `EventHeroImageResolver`, restores `thumb` compatibility from the parent Event for stale occurrences, and batches parent Event loading for occurrence lists. The code does not add a parallel fallback chain and does not alter auth, media storage, upload, or Flutter surfaces.

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Add Laravel fail-first coverage proving public agenda/card payloads use the canonical Event image when an occurrence has stale or missing `thumb`. | `Laravel feature test` | RED safe runner for `AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image` failed with `thumb.data.url` null; GREEN final focused suite passed. | `local Docker Laravel feature integration route test` | `passed` | Reproduces the production card payload path for `/api/v1/agenda`. |
| `SCOPE-02` | `Scope` | Ensure public event detail, public agenda, and any full public event payload expose a canonical event image URL resolved by `EventHeroImageResolver`. | `code plus feature tests` | `EventQueryService` injects `EventHeroImageResolver`; `formatEvent()` and `formatEventDetail()` set `hero_image_url`; agenda and detail tests assert the exact URL. | `local Docker Laravel API route tests` | `passed` | Covers public detail and agenda payloads through the same resolver. |
| `SCOPE-03` | `Scope` | Preserve `thumb` as the Event cover payload for compatibility, while adding/using a canonical image field for UI consumers. | `Laravel feature test` | Agenda single and multi occurrence tests assert `items.0.thumb.data.url` equals parent Event cover and `items.0.hero_image_url` equals the same Event cover. | `local Docker Laravel feature integration route test` | `passed` | Current card consumers receive Event cover through the existing `thumb` field and the new canonical field. |
| `SCOPE-04` | `Scope` | Cover single-occurrence and multi-occurrence event payloads with intentionally different Event, linked Account Profile, and Venue image URLs so the selected winner is unambiguous. | `Laravel feature and unit tests` | `AgendaAndEventsControllerTest` covers single and multi occurrence stale snapshots for Event cover, parent linked profile cover, and parent linked profile avatar; `EventHeroImageResolverTest` covers distinct `single-*`, `multi-*`, and `img-*` URLs. | `local Docker Laravel feature and PHPUnit tests` | `passed` | Fixture URLs differ for Event cover, Account Profile cover/avatar, and Venue media. |
| `SCOPE-05` | `Scope` | Cover each fallback step in the resolver order: Event cover, linked Account Profile cover, linked Account Profile avatar, Venue cover, Venue hero/avatar/logo only when higher-priority Venue candidates are absent, and `null` when no valid candidate exists. | `unit test` | `EventHeroImageResolverTest::test_resolves_all_documented_fallback_scenarios_with_distinct_urls` passed. | `local PHPUnit` | `passed` | Covers `IMG-01` and `IMG-03` through `IMG-10`; `IMG-02` is covered by the multi-occurrence feature test. |
| `SCOPE-06` | `Scope` | Ensure all backend surfaces that request or expose an Event image use `EventHeroImageResolver` as the only image-selection authority. | `static guardrail` | `CanonicalImageResolutionGuardrailTest` passed and checks `EventQueryService`, `PublicWebMetadataService`, and `InviteTargetReadAdapter` for resolver delegation. | `local PHPUnit guardrail` | `passed` | Protects backend public Event image surfaces from bypassing the resolver. |
| `SCOPE-07` | `Scope` | Keep invite and OG image behavior routed through the same Laravel resolver. | `feature tests` | Invite preview test passed `1 test, 9 assertions`; PublicWebMetadataShell event metadata tests passed in the focused suite. | `local Docker Laravel feature route tests` | `passed` | Invite and OG adapters continue to use `EventHeroImageResolver`. |
| `SCOPE-08` | `Scope` | Add a static/guardrail test that fails if any Laravel public Event image payload builder or adapter bypasses `EventHeroImageResolver` again. | `static guardrail` | RED `CanonicalImageResolutionGuardrailTest` identified `EventQueryService`; GREEN guardrail passed after injection and delegation. | `local PHPUnit guardrail` | `passed` | Catches resolver existence without usage. |
| `SCOPE-09` | `Scope` | Record why the previous delivery was still false-green after the detail fix. | `todo evidence` | Context, Mandatory Bug-Fix Questions, Coverage Matrix, RED agenda evidence, and RED guardrail evidence record that detail was fixed while agenda/list card payloads still bypassed the resolver. | `foundation documentation` | `passed` | The production false-green path is documented. |
| `DOD-01` | `Definition of Done` | `GET /api/v1/agenda` returns a canonical event image for the production-equivalent stale-occurrence case and does not force Flutter to fall back to Venue while Event cover exists. | `Laravel feature integration route test` | `AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image` and `::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image` passed in the final focused suite. | `local Docker Laravel /api/v1/agenda route` | `passed` | Tests assert `thumb.data.url`, `hero_image_url`, and Venue losing to Event cover. |
| `DOD-02` | `Definition of Done` | `GET /api/v1/events/{event}?occurrence={occurrence}` and public metadata/OG continue to resolve to the same canonical Event cover for this case. | `Laravel feature integration test` | `EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale` passed; PublicWebMetadataShell event metadata tests passed. | `local Docker Laravel event detail and metadata integration test` | `passed` | Detail asserts `data.hero_image_url === data.thumb.data.url`. |
| `DOD-03` | `Definition of Done` | Single-occurrence and multi-occurrence tests assert the exact returned image URL for every resolver fallback scenario, with different fixture URLs for Event cover, linked Account Profile cover/avatar, and Venue media. | `Laravel feature and unit tests` | Agenda single/multi Event-cover tests passed; agenda single profile-cover test passed; agenda multi profile-avatar test passed; `EventHeroImageResolverTest::test_resolves_all_documented_fallback_scenarios_with_distinct_urls` passed. | `local Docker Laravel feature and PHPUnit tests` | `passed` | Exact URL winners are asserted for Event cover, Account Profile cover/avatar, Venue cover/hero/avatar/logo, and `null`. |
| `DOD-04` | `Definition of Done` | Invite image and OG image tests remain green and continue to use `EventHeroImageResolver`. | `Laravel feature tests` | `InvitesFlowTest::test_share_preview_resolves_without_authentication` passed; PublicWebMetadataShell event metadata tests passed; guardrail checks both adapters. | `local Docker Laravel invite and metadata routes` | `passed` | Same resolver supports invite and OG image selection. |
| `DOD-05` | `Definition of Done` | A Laravel guardrail blocks adding another backend public Event image fallback chain outside the resolver. | `unit guardrail plus integration test coverage` | `CanonicalImageResolutionGuardrailTest::test_public_event_image_payload_providers_delegate_to_event_hero_image_resolver` passed. | `local PHPUnit guardrail and local Docker Laravel integration test coverage` | `passed` | Guardrail checks resolver delegation for public event image payload providers; agenda/detail integration tests prove the public payload behavior. |
| `DOD-06` | `Definition of Done` | Production-equivalent runtime evidence is captured after implementation and before promotion. | `local integration runtime-equivalent tests` | Safe-runner feature tests execute HTTP endpoints against local Docker Laravel plus local MongoDB, including auth context and tenant runtime. | `local Docker Laravel API runtime` | `passed` | Production-domain probe belongs to deploy validation after promotion. |
| `VAL-01` | `Validation Steps` | RED: Laravel agenda/card regression test fails before implementation because `thumb`/canonical image is missing and Venue would win. | `test-first integration test evidence` | RED safe-runner output: `Failed asserting that null is identical to 'https://example.org/single-event-cover.jpg'` for the single-occurrence agenda test. | `local Docker Laravel feature integration test` | `passed` | Captured before code implementation. |
| `VAL-02` | `Validation Steps` | GREEN: Laravel focused event agenda/detail/metadata tests pass after implementation. | `integration test evidence` | Final focused safe-runner suite passed `20 tests, 90 assertions`; full agenda/unit command passed `54 tests, 211 assertions`. | `local Docker Laravel integration test safe runner` | `passed` | Includes agenda single/multi Event cover, linked profile cover/avatar fallbacks, detail, metadata, profile agenda consumer, resolver unit, query service, account-profile cap, and guardrail tests. |
| `VAL-03` | `Validation Steps` | GREEN: Laravel canonical-image guardrail test passes and proves `EventQueryService` depends on or delegates to `EventHeroImageResolver`. | `unit guardrail` | `CanonicalImageResolutionGuardrailTest` passed in the final focused suite and checks `EventQueryService.php` for `EventHeroImageResolver` plus `resolveFromPayload`. | `local PHPUnit guardrail` | `passed` | Structural prevention for the exact false-green mode. |
| `VAL-04` | `Validation Steps` | GREEN: Laravel invite image tests pass and prove invite image selection still delegates to `EventHeroImageResolver`. | `feature test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='test_share_preview_resolves_without_authentication' tests/Feature/Invites/InvitesFlowTest.php` passed `1 test, 9 assertions`. | `local Docker Laravel invite route test` | `passed` | Invite adapter remains resolver-backed. |
| `VAL-05` | `Validation Steps` | Run Laravel focused CI-equivalent suites for every touched file before claiming local completion. | `test and static evidence` | Pint touched files passed; `composer run architecture:guardrails` passed; focused safe-runner passed `20 tests, 90 assertions`; invite safe-runner passed `1 test, 9 assertions`; full agenda/unit safe-runner passed `54 tests, 211 assertions`. | `local Docker Laravel CI-equivalent surface` | `passed` | Exact CI-equivalent commands are listed in the Local CI matrix. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / RED agenda regression` | Proves false-green before implementation. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='EventHeroImageResolverTest&#124;CanonicalImageResolutionGuardrailTest&#124;AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image&#124;AgendaAndEventsControllerTest::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image&#124;EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale'` | `Local-Implemented` | `passed` | Pre-fix run failed on `AgendaAndEventsControllerTest.php:191` with `thumb.data.url` null. | RED evidence captured before implementation. |
| `laravel-app / RED linked profile fallback` | Proves stale occurrence payloads were still able to choose Venue before parent Event linked Account Profile media. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AgendaAndEventsControllerTest::test_agenda_single_occurrence_uses_parent_linked_profile_cover_before_venue_when_event_cover_is_missing&#124;AgendaAndEventsControllerTest::test_agenda_multi_occurrence_uses_parent_linked_profile_avatar_before_venue_when_cover_candidates_are_missing'` | `Local-Implemented` | `passed` | Pre-fix run failed with `single-parent-venue-cover.jpg` selected instead of `single-parent-profile-cover.jpg`. | RED evidence captured during final local review. |
| `laravel-app / RED guardrail` | Proves static prevention gap. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='CanonicalImageResolutionGuardrailTest'` | `Local-Implemented` | `passed` | Pre-fix run identified that `EventQueryService.php` bypassed `EventHeroImageResolver`. | RED evidence captured before implementation. |
| `laravel-app / final focused suite` | Direct touched behavior after external Round 01 fixes. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='EventHeroImageResolverTest&#124;CanonicalImageResolutionGuardrailTest&#124;EventQueryServiceTest&#124;AgendaAndEventsControllerTest::test_agenda_single_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image&#124;AgendaAndEventsControllerTest::test_agenda_multi_occurrence_stale_thumb_returns_parent_event_cover_as_canonical_image&#124;AgendaAndEventsControllerTest::test_agenda_single_occurrence_uses_parent_linked_profile_cover_before_venue_when_event_cover_is_missing&#124;AgendaAndEventsControllerTest::test_agenda_multi_occurrence_uses_parent_linked_profile_avatar_before_venue_when_cover_candidates_are_missing&#124;EventCrudControllerTest::test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties&#124;PublicWebMetadataShellTest::test_event_public_route_injects_event_metadata_with_event_party_profile_cover_fallback&#124;PublicWebMetadataShellTest::test_event_public_route_prefers_linked_account_profiles_image_over_artists_projection&#124;PublicWebMetadataShellTest::test_event_public_route_ignores_legacy_artists_projection_for_event_image_resolution'` | `Local-Implemented` | `passed` | Passed `20 tests, 90 assertions`. | Covers agenda Event cover, profile cover/avatar, detail, account-profile endpoint image semantics and cap, metadata, unit resolver, query service, and guardrail. |
| `laravel-app / account-profile public agenda post-audit` | Resolves Round 01 `PERF-001` and `TQ-001`. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_venue_occurrences&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_caps_agenda_occurrences_to_public_page_size&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_future_artist_occurrences&#124;AccountProfilesControllerTest::test_public_account_profile_show_by_slug_includes_agenda_occurrences_for_capability_enabled_poi_profile_via_linked_event_parties' tests/Feature/AccountProfiles/AccountProfilesControllerTest.php` | `Local-Implemented` | `passed` | Passed `4 tests, 24 assertions`. | Endpoint-level image assertion proves Event cover wins over Venue media; cap test proves public page-size bound before formatting. |
| `laravel-app / full agenda file` | Agenda/card endpoint is the production regression surface. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php tests/Feature/Events/AgendaAndEventsControllerTest.php` | `Local-Implemented` | `passed` | Passed `54 tests, 211 assertions` for agenda file plus unit tests. | Also preserved geo/filter/stream agenda behavior. |
| `laravel-app / invite continuity` | Invite image uses same resolver. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter='test_share_preview_resolves_without_authentication' tests/Feature/Invites/InvitesFlowTest.php` | `Local-Implemented` | `passed` | Passed `1 test, 9 assertions`. | Confirms invite image contract remains green after the EventQueryService change. |
| `laravel-app / formatting` | Touched PHP files. | `docker compose exec -T app ./vendor/bin/pint --test app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php tests/Feature/AccountProfiles/AccountProfilesControllerTest.php packages/belluga/belluga_events/src/Application/Events/EventQueryService.php tests/Feature/Events/AgendaAndEventsControllerTest.php tests/Feature/Events/EventCrudControllerTest.php tests/Unit/Events/EventHeroImageResolverTest.php tests/Unit/Events/EventQueryServiceTest.php tests/Unit/Guardrails/CanonicalImageResolutionGuardrailTest.php` | `Local-Implemented` | `passed` | Pint returned `PASS` for `8 files`. | Includes account-profile endpoint test changes from external audit resolution. |
| `laravel-app / architecture guardrails` | Constructor dependency and package boundary changed. | `docker compose exec -T app composer run architecture:guardrails` | `Local-Implemented` | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | Static architecture safety. |
| `foundation / external triple audit` | Required independent audit gate. | `audit-protocol-triple-review` session at `foundation_documentation/artifacts/triple-audits/canonical-event-image-resolver-20260525/triple-audit-session/session.json` | `before_completed` | `passed` | Round 01 found and resolved `PERF-001` and `TQ-001`; Round 02 Elegance, Performance, and Test Quality returned clean with zero findings; Round 02 resolution recorded recommendation-string conflict as non-material. | External auditors were run after user challenge and before claiming completed audit gate. |

## Decision Adherence Validation
| Decision ID | Status | Supporting Evidence | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `EventQueryService` now injects `EventHeroImageResolver`; guardrail requires resolver usage. | Canonical Laravel authority is used by query payloads. |
| `D-02` | `Adherent` | `formatEvent()` and `formatEventDetail()` set top-level `hero_image_url` via resolver; tests assert exact URL. | Public payloads expose resolver-produced URL. |
| `D-03` | `Adherent` | Agenda single/multi occurrence tests null the occurrence `thumb` and still receive parent Event cover. | Stale occurrence snapshots are protected at read time. |
| `D-04` | `Adherent` | `CanonicalImageResolutionGuardrailTest` checks EventQueryService, PublicWebMetadataService, and InviteTargetReadAdapter delegate to `EventHeroImageResolver`. | Backend providers cannot bypass resolver without failing tests. |
| `D-05` | `Adherent` | No Flutter files touched; backend `thumb` compatibility is restored. | Client fallback remains defensive only. |
| `D-06` | `Adherent` | New tests use distinct `single-*`, `multi-*`, and `img-*` URLs for every candidate. | Prevents coincidental winner. |

## Module Decision Consistency Validation
| Module Decision / Contract | Status | Evidence | Notes |
| --- | --- | --- | --- |
| `events_module.md §5.1 public event-image order` | `Preserved` | Module states Event image order is `event.thumb -> linked_account_profiles -> venue` and backend-owned by canonical resolver. | Implementation delegates to `EventHeroImageResolver` and tests prove order. |
| `EVS-OCC-01 occurrence-first discovery/detail` | `Preserved` | Full agenda suite passed; detail selected occurrence test remains green. | Parent Event media authority does not change occurrence identity/date/programming semantics. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Bounded local self-review | CI/Copilot P1/P2 failure modes across implemented diff, tests, and guardrails. | `passed` | `git -C laravel-app diff --check`; Pint; architecture guardrails; focused and full agenda tests. | `none` | Clean: no P1/P2 finding in local review. GitHub/Copilot review belongs to promotion PR. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Canonical resolver ownership | Search for local ordered fallback chains or resolver bypass in touched backend event-image providers. | `passed` | `CanonicalImageResolutionGuardrailTest`; code review of `EventQueryService`. | `none` | No new backend fallback chain; resolver owns selected URL. |
| Performance / N+1 avoidance | Check agenda/list formatting does not load parent Event per card. | `passed` | `EventQueryService::formatEvents()` batch-loads parent Events for occurrence lists. | `none` | Account profile agenda consumer now uses `formatEvents()` too. |
| Generic rule-spirit scan | Broad scanner over `laravel-app`. | `passed` | `bash delphi-ai/tools/rule_spirit_anti_pattern_scan.sh --repo laravel-app --stack laravel` returned exit 0, max active severity `warning`. | Existing repository-wide warnings outside touched diff. | Clean: no P1/P2 finding in touched diff. |

## Security Risk Assessment
- **Risk level:** `low`
- **Why this risk level:** The change exposes already-public event/media URLs through public read payloads. It does not change auth, tenant resolution, upload authorization, media route access, or mutation policy.
- **Attack surface in scope:** public event read payloads only.
- **Review evidence:** touched diff contains no route, middleware, auth, policy, media storage, or upload authorization changes; architecture guardrails passed.
- **Residual security risk:** `none identified`

## Performance & Concurrency Risk Assessment
- **Risk level:** `low`
- **Why this level:** Agenda/list parent Event context is loaded in one bounded query per page slice. Single-detail formatting uses the already-loaded parent Event. Account-profile agenda now applies the public page-size cap before materializing results and formats that bounded set with batched parent context.
- **Concurrency impact:** read-only payload resolution; no new writes or locks.
- **Residual performance risk:** `low`; `formatEvents()` should remain the preferred list formatter for occurrence lists to avoid N+1 regressions.

## Test Quality Audit
- **Outcome:** `medium heuristic, reviewed as non-blocking for this patch`
- **Evidence:** `bash delphi-ai/tools/test_quality_audit.sh --path <touched Laravel test files>` reported no hard bypass markers, no test-only support routes, no no-exception-only assertions. It flagged existing suite-wide `Sanctum::actingAs` and status assertion patterns in large legacy files.
- **Finding disposition:** non-blocking for this patch because the new/changed tests assert semantic payload values: exact `thumb.data.url`, exact `hero_image_url`, occurrence identity, Venue-not-winning behavior, resolver fallback winners, and guardrail delegation. The new tests do not pass on HTTP status alone.

## Verification Debt Audit
- **Outcome:** `completed for Local-Implemented`
- **Evidence:** `bash delphi-ai/tools/verification_debt_audit.sh --todo ... --path <touched files>` completed with `Outcome heuristic: none` and `Inline code TODO debt classification: none`.
- **Accepted residual debt:** none for the local implementation stage.

## Final Review
- **Status:** `passed for Local-Implemented`
- **Correctness:** The production failure path was reproduced in RED (`thumb.data.url` null), then fixed with agenda single/multi occurrence assertions and resolver guardrails.
- **Performance:** Parent Event enrichment is batched for list formatting; no per-card query added to agenda. External Performance Round 01 identified an account-profile public fetch-all path; it is fixed with a public page-size cap before `get()` and was accepted clean in Round 02.
- **Elegance / Structural Soundness:** The fix centralizes Event image selection in `EventHeroImageResolver` instead of adding another ad hoc image fallback chain.
- **External triple audit:** Round 01 findings resolved; Round 02 returned clean across Elegance, Performance, and Test Quality with zero findings.
- **Residual risk:** Real production-domain probe is a promotion/deploy lane activity; local implementation evidence is complete.

## TODO Closeout Disposition
- **Disposition:** `remain-active-local-implemented`
- **Reason:** Code is implemented and locally validated, but promotion has not yet been requested/executed for this new Laravel branch.
- **Next exact step:** promote `laravel-app` branch `fix/canonical-event-image-resolver-20260525` through `feature -> dev -> stage -> main` when authorized.
