# TODO (Fast Follow Bugfix): Public Event Detail Occurrence Cover Canonicalization

## Title
Fast Follow Bugfix: Public Event Detail Occurrence Cover Canonicalization

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production event `https://guarappari.com.br/agenda/evento/arraia-apaexonados?occurrence=6a147bd30a65fb8d0f0d00d9` renders the Venue cover image in the app/page hero instead of the Event cover.

Runtime evidence captured on `2026-05-25`:

- Public HTML metadata is correct: `og:image` resolves to `/api/v1/media/events/6a147bd30a65fb8d0f0d00d8/cover?v=1779733759`.
- Direct event cover media route is healthy: `/api/v1/media/events/6a147bd30a65fb8d0f0d00d8/cover` returns `200 image/jpeg`.
- Public event-detail API for the same route returns the selected occurrence payload with `thumb: null`, empty `linked_account_profiles`, and Venue media present; Flutter then correctly falls back to Venue media because the API payload does not carry the Event cover.

The prior completed TODO `foundation_documentation/todos/completed/TODO-fast-follow-canonical-event-account-image-resolution-guardrails.md` did promote the canonical resolver implementation to stage, but its validation matrix covered invite payloads, public HTML metadata, account metadata, and static Laravel guardrails. It did not cover the public event-detail API path with an explicit `occurrence` query after cover upload.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `public-event-detail-occurrence-cover-canonicalization`
- **Why this is the right current slice:** one production-visible regression in a single documented contract: public event detail must expose the Event cover as the first hero image source even when the selected read model is an occurrence.
- **Direct-to-TODO rationale:** the failing route, payload shape, root cause, and regression test boundary are already concrete; separate feature decomposition would add no useful discovery.

## Contract Boundary
- This TODO restores the existing event hero image contract for public event-detail payloads.
- It may touch Laravel event query/media/occurrence sync code and Laravel feature tests.
- It may update module docs only if the implementation clarifies the existing contract.
- It must not introduce a new public route, new media storage architecture, or a new Flutter fallback contract.

## Delivery Status Canon
- **Current delivery stage:** `Production-Ready`
- **Qualifiers:** `Fast-Follow`, `Production-Visible`, `Laravel`, `Regression-Fix`, `Canonical-Image-Contract`
- **Next exact step:** no promotion-lane action remains for this TODO; production `main` deploy completed green on `2026-05-25`.

## Execution Lane Tracking
- **Local implementation branches:** `belluga_now_docker:fix/public-event-detail-occurrence-cover-20260525`, `laravel-app:fix/public-event-detail-occurrence-cover-20260525`, `foundation_documentation:fix/public-event-detail-occurrence-cover-20260525`
- **Promotion lane path:** `dev -> stage -> main`
- **Lane-promoted threshold for this TODO:** `dev`
- **Production-ready threshold for this TODO:** `main`

## Scope
- [x] Add Laravel regression coverage for `GET /api/v1/events/{slug/id}?occurrence={occurrence_id}` after an event cover upload.
- [x] Ensure public event detail returns the Event cover in `data.thumb.data.url` when the selected occurrence lacks or has stale mirrored `thumb`.
- [x] Overlay parent Event `thumb` into selected occurrence detail reads so existing stale occurrence records are safe immediately after deploy.
- [x] Mirror only parent Event `thumb` into occurrence records after cover upload/remove so future reads stay coherent without overwriting occurrence-owned fields.
- [x] Preserve existing occurrence-owned semantics for date/time, programming, related profiles, and location.
- [x] Preserve the canonical image priority `event.thumb -> linked_account_profiles -> venue`.
- [x] Record why the previous canonical resolver delivery had false-green coverage for this route.

## Out of Scope
- Flutter UI/rendering changes.
- Broad `belluga_media` migration or host-agnostic media URL hardening.
- Reworking invite push image resolution or public HTML metadata, which are already passing for this event.
- Data repair scripts for historical occurrences unless code-only fallback cannot safely handle current production data.
- Changing multiple-occurrence selection behavior, occurrence route aliases, programming aggregation, or related-profile ordering.

## Definition of Done
- [x] A fail-first Laravel feature test proves a single-occurrence event created/updated with cover upload returns `data.thumb.data.url` from `/api/v1/media/events/{event_id}/cover` on the public detail endpoint when queried with `occurrence`.
- [x] A regression assertion proves Venue media does not win while Event cover exists.
- [x] The implementation fixes existing stale occurrence payloads at read time and synchronizes occurrence `thumb` after media mutation with coverage for both paths.
- [x] Existing public metadata behavior remains unchanged and still resolves the Event cover.
- [x] Existing event occurrence detail tests still pass.
- [x] The TODO records the false-green reason from the previous delivery and the new coverage that closes it.

## Validation Steps
- [x] Run the new focused regression test before implementation and confirm it fails for the current behavior.
- [x] Run the new focused regression test after implementation and confirm it passes.
- [x] Run focused event feature tests covering public detail occurrence selection, cover upload, and cover removal.
- [x] Run the Laravel safe runner for the touched test file or a broader focused suite before claiming `Local-Implemented`.

## Production Evidence Snapshot
| Evidence | Result | Interpretation |
| --- | --- | --- |
| Public event route HTML metadata | `og:image` is `/api/v1/media/events/6a147bd30a65fb8d0f0d00d8/cover?v=1779733759` | Parent Event cover exists and metadata path is correct. |
| Event cover media route | `200 image/jpeg`, `content-length: 160294`, `last-modified: Mon, 25 May 2026 18:29:19 GMT` | Stored Event cover is valid and publicly servable. |
| Public detail API payload | `data.thumb: null`, Venue `cover_url` present, `linked_account_profiles: []` | UI fallback chooses Venue because API did not expose Event cover. |
| Event shape | one selected occurrence `6a147bd30a65fb8d0f0d00d9` for Event `6a147bd30a65fb8d0f0d00d8` | Bug is not multi-occurrence-specific; it is occurrence-first detail read with stale/missing mirrored cover. |

## Current Code-Cross Findings
- `EventQueryService::formatEventDetail()` formats the selected occurrence first, then restores parent `event_id` and parent `slug`, but does not restore parent Event `thumb`.
- `EventQueryService::formatEvent()` serializes `thumb` from whichever model instance it receives.
- `EventOccurrenceSyncService::syncFromEvent()` mirrors `event.thumb` into occurrence documents when sync runs.
- `EventsController::store()` and `EventsController::update()` call `EventManagementService` before `EventMediaService::applyUploads()`, so occurrence sync can run before the cover upload writes parent `event.thumb`.
- Existing cover upload tests assert the management response and media route, not a subsequent public detail read through `?occurrence=...`.

## Mandatory Bug-Fix Questions
| Question | Answer |
| --- | --- |
| Do we already have tests that cover this behavior across all stages up to UI display? | No. Resolver, upload, metadata, and Flutter fallback pieces are individually tested, but the public detail API with selected occurrence after media mutation is missing. |
| Did we inspect current real database/backend payloads to verify compatibility with current parsing and rendering assumptions? | Yes. Production API/browser evidence shows `thumb:null` on the selected occurrence payload while Event cover media and HTML metadata are correct. |
| If existing tests should cover this bug, which tests failed? If none failed, why were they insufficient? | None failed. `EventHeroImageResolverTest` assumes `thumb` is already present, `PublicWebMetadataShellTest` covers HTML metadata, and `EventCrudControllerTest` covers upload/admin response/media route without reading public detail through occurrence. |
| If tests do not cover the failure, which new tests must be created before implementing the fix? | Laravel feature coverage that creates or updates an event with one occurrence and cover upload, then calls `GET /api/v1/events/{eventRef}?occurrence={occurrence_id}` and asserts `data.thumb.data.url` is the Event cover route, not Venue media. |
| Is the root cause also an architectural deviation pattern that could be prevented earlier by analyzer-enforced rule coverage? | `no-rule-needed` for now. The defect depends on mutation ordering and persisted occurrence snapshot freshness, not a reliably static code shape. Integration coverage is the correct guard. |

## Coverage Matrix
| Stage | Current Status | Required Closure |
| --- | --- | --- |
| Event cover storage/media route | `covered` | Existing upload/media assertions stay green. |
| Parent Event metadata resolver | `covered` | Existing public metadata tests stay green. |
| Occurrence snapshot after cover mutation | `false-green` | New test must prove public reads see Event cover after create/update cover upload. |
| Public event-detail API with `occurrence` query | `missing` | New feature test asserts `data.thumb.data.url` and venue-not-winning semantics. |
| Flutter fallback/rendering | `covered-by-contract` | No Flutter change in this TODO; Flutter remains a consumer of API payload. |

## Complexity
- **Level (`small|medium|big`):** `small`
- **Checkpoint policy:** consolidated planning review; explicit `APROVADO` still required before implementation.
- **Why this level:** the fix is narrow, bounded to Laravel event detail/media sync behavior and one focused regression test path.

## Profile Scope & Handoffs
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-tester-quality`
- **Handoff log:** no cross-profile handoff required unless validation reveals broader Flutter or media-architecture changes.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/events_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Planned decision promotion targets:**
  - Preserve existing Events module event-image decision; update only if implementation clarifies occurrence detail payload obligations.
  - Preserve Flutter client event hero fallback contract; no Flutter rendering change is planned.
- **Module decision consolidation targets:** `foundation_documentation/modules/events_module.md#5.1-read-model-api-contracts`

## Module Decision Baseline Snapshot
| Module Decision Ref | Current Module Decision | Planned Handling | Evidence |
| --- | --- | --- | --- |
| `events_module event image resolution` | Public event-image resolution order is backend-owned by the canonical Laravel event hero resolver: `event.thumb`, then `linked_account_profiles`, then `venue` media. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `events_module occurrence detail` | Selected occurrence determines effective date/time, related profiles, and programming content. | `Preserve` | `foundation_documentation/modules/events_module.md` |
| `flutter_client_experience event hero fallback` | Flutter consumes event thumb first, then linked profiles, then venue media, and must not fall back to legacy artists. | `Preserve` | `foundation_documentation/modules/flutter_client_experience_module.md` |

## Decision Baseline (Frozen Before Implementation)
- [x] `D-01` Public event-detail payloads must expose the parent Event cover as `data.thumb` for hero-image selection even when the selected read model is an occurrence.
- [x] `D-02` Occurrence selection continues to own date/time, programming, and occurrence-scoped related-profile semantics; this fix must not collapse detail reads back to the parent Event payload.
- [x] `D-03` Venue media remains a fallback only after Event `thumb` and non-venue linked account profile media are absent.
- [x] `D-04` Existing stale occurrence records in production must be handled by the runtime read path; relying only on future writes is insufficient.
- [x] `D-05` Cover upload/remove must mirror only the parent Event `thumb` onto occurrence records after media mutation, avoiding full occurrence resync unless renewed approval allows broader mirror behavior.
- [x] `D-06` The regression guard belongs in Laravel feature coverage, not in a static analyzer rule.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Production has parent Event `thumb` but selected occurrence `thumb` is stale or null. | HTML metadata and media route are correct; detail API payload showed `thumb:null`. | Need inspect production DB write history before code-only read fix can be trusted. | `High` | `Promote to Decision D-04` |
| `A-02` | Restoring parent Event `thumb` into the selected occurrence detail payload is compatible with the existing module contract. | Events module says Event image order starts at `event.thumb`; occurrence detail section does not grant occurrences independent cover ownership. | Would require a broader product decision about occurrence-owned covers. | `High` | `Keep as Assumption` |
| `A-03` | A thumb-only occurrence mirror after cover upload/remove is safer than a full occurrence resync because it preserves occurrence-owned fields. | `EventOccurrenceSyncService` already treats `thumb` as a mirrored parent field; occurrence detail owns programming/date/profile semantics separately. | If even thumb-only bulk update has model/serialization risk, keep read-time parent thumb overlay and document future data hygiene separately. | `High` | `Promote to Decision D-05` |

## Execution Plan
### Touched Surfaces
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Controllers/EventsController.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventMediaService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventOccurrenceSyncService.php`
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`

### Ordered Steps
1. Add a fail-first feature test for public event detail with a single occurrence after cover upload.
2. Confirm the test fails because `data.thumb` is missing/null on the selected occurrence payload.
3. Implement the minimal fix: preserve parent Event `thumb` in detail payload for selected occurrence reads.
4. Add a narrow post-media-mutation mirror update for occurrence `thumb` only, preserving occurrence-owned fields.
5. Add/adjust coverage for cover removal and stale occurrence fallback.
6. Run focused Laravel event tests through the safe runner.
7. Update this TODO with completion evidence and decision adherence.

### Test Strategy
- **Strategy:** `test-first`
- **Fail-first target:** public detail response `data.thumb.data.url` for `GET /api/v1/events/{eventRef}?occurrence={occurrence_id}` after multipart cover upload must be the Event cover media route, not Venue media or `null`.
- **Negative assertion:** Venue `cover_url` must not equal the selected hero thumb while Event cover exists.

### Runtime / Rollout Notes
- Existing production stale occurrence documents must be safe after deploy without requiring immediate manual DB repair.
- A later one-time reconciliation may still be useful for data hygiene, but it is not sufficient as the primary fix for this user-visible path.

## Plan Review Gate
### Review Sections
| Area | Assessment |
| --- | --- |
| Architecture | Parent Event image ownership is already canonical; selected occurrence detail should not shadow the Event cover with stale null. |
| Code Quality | Prefer a small helper/overlay in `EventQueryService` and avoid duplicating image fallback chains. |
| Tests | Feature-level regression is required because unit resolver tests cannot catch persisted snapshot ordering. |
| Performance | Read-time parent thumb overlay is O(1); no new query is required because parent Event is already loaded. |
| Security | No auth, upload authorization, or media access policy change is planned. |
| Elegance | The smallest coherent fix is to keep occurrence-owned fields for occurrence semantics while restoring parent-owned mirrored media. |
| Structural Soundness | Do not reimplement the full hero fallback chain in Flutter or another Laravel consumer; keep this as payload correctness plus mirror consistency. |

### Failure Modes & Edge Cases
- Cover uploaded after occurrence sync leaves old records stale.
- Cover removed after occurrence sync leaves stale cover on occurrence unless read-time overlay or resync handles clear semantics.
- Multi-occurrence detail must still select the requested occurrence and preserve occurrence-specific programming/location/profile data.
- Event without cover should continue to fall back to linked profiles or Venue.

## Flow Evidence Planning Matrix
| Criterion / Flow | Why Flow-Impacting | Platform Parity | Required Runtime Lane | Mutation Lane Required? | Backend Real-Data Required? | Planned Evidence | Non-Applicability Rationale |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Public event detail hero image payload | Payload consumed by tenant-public Flutter/Web detail UI. | `shared-android-web` | `Laravel feature` | `yes` | `yes-local-fixture` | `EventCrudControllerTest` creates/updates cover, reads public detail with `occurrence`, and asserts `data.thumb.data.url`. | Browser/device lane is not required for this TODO because Flutter already follows payload fallback contract and no Flutter code changes are planned. |

## Audit Trigger Matrix
- **Canonical method:** `wf-docker-audit-escalation-method`
- **Guard command:** `python3 delphi-ai/tools/audit_escalation_guard.py --todo foundation_documentation/todos/active/fast_follow_required/TODO-fast-follow-public-event-detail-occurrence-cover-canonicalization.md`
- **Latest TEACH evidence / artifact:** `Overall outcome: go`; fingerprint `e5c6ea9342f3`; guard rerun on `2026-05-25`.

| Trigger | Value | Notes |
| --- | --- | --- |
| `complexity` | `small` | Narrow Laravel regression fix. |
| `blast_radius` | `local` | Touched behavior is one event detail payload path plus tests. |
| `behavioral_change_or_bugfix` | `yes` | Production-visible regression fix. |
| `changes_public_contract` | `yes` | Public API payload semantics change from observed `thumb:null` to documented Event cover value on occurrence detail. |
| `touches_auth_or_tenant` | `no` | Public read semantics only; no auth/tenant access policy change. |
| `touches_runtime_or_infra` | `no` | No worker, queue, deploy, or infra surface. |
| `touches_tests` | `yes` | Adds Laravel feature regression coverage. |
| `critical_user_journey` | `yes` | Public event detail hero image is user-visible. |
| `release_or_promotion_critical` | `yes` | Regression affects production public event rendering. |
| `high_severity_plan_review_issue` | `no` | No unresolved high-severity plan issue; failure mode is concrete. |
| `explicit_three_lane_request` | `no` | User requested implementation, not a three-lane external audit. |

## Independent No-Context Critique Gate
- **Critique decision:** `required`
- **Why this decision:** audit floor marked critique required because this is a production-visible, release-critical public detail payload bugfix with tests touched.
- **Impact signals in scope:** `production-visible public detail payload`
- **Package mode:** `bounded-summary`
- **Package minimum contents:** `frozen baseline, approved scope boundary, assumptions preview, execution plan summary, failure modes`
- **Critique isolation mode:** `bounded no-context self-review`
- **Subagent mandate (when available):** `not used; multi-agent tool policy in this session requires an explicit user request before spawning subagents/delegation`
- **Canonical multi-lane audit protocol (when required):** `not applicable for Local-Implemented evidence`
- **Audit session / round evidence (when protocol used):** `not applicable for Local-Implemented evidence`
- **Critique lenses:** `correctness|performance|elegance|structural-soundness|risk`
- **Critique status:** `findings_integrated`
- **Findings summary:** `CRIT-01 integrated: classify the payload fix as public-contract-affecting for audit purposes. CRIT-02 integrated: avoid broad occurrence resync; require read-time parent thumb overlay plus narrow thumb-only mirror after media mutation. CRIT-03 integrated: cover removal must be covered because stale occurrence cover would be the inverse bug.`
- **Evidence / reference:** `bounded self-review recorded in this section`
- **Deviation authority / reference:** `none used`

## Derived Audit Floor Decisions
| Audit | Decision | Gate | Workflow | Status |
| --- | --- | --- | --- | --- |
| Critique | `required` | `before_aprovado` | `wf-docker-independent-critique-method` | `passed via bounded self-review; subagent use requires explicit user delegation request` |
| Test-quality audit | `required` | `before_completed` | `wf-docker-independent-test-quality-audit-method` | `passed for Local-Implemented; file-wide medium heuristic reviewed as non-blocking for this patch` |
| Final review | `required` | `before_completed` | `wf-docker-independent-final-review-method` | `passed for Local-Implemented` |
| Triple review | `required` | `before_completed` | `audit-protocol-triple-review` | `planned` |
| Security review | `recommended` | `before_completed` | `security-adversarial-review` | `focused public-payload risk assessment passed for Local-Implemented` |
| Performance/concurrency | `recommended` | `per_pcv1_gate_deadlines` | `wf-docker-performance-concurrency-validation-method` | `focused no-new-query/no-broad-sync review passed for Local-Implemented` |
| Verification debt | `required` | `before_completed` | `verification-debt-audit` | `no inline code TODO debt in touched implementation; TODO completion guard passed` |

## Approval
- **Approved by:** `user on 2026-05-25 with approval phrase "APROVADO"`
- **Approval scope:** `Implement the bounded Laravel regression fix described in this TODO: public event detail occurrence cover canonicalization, fail-first feature tests, read-time parent thumb overlay, and thumb-only occurrence mirror after cover upload/remove.`
- **Execution not authorized by approval:** `Flutter rendering changes, broad media architecture migration, new API fields/routes, production data repair script, broader occurrence-owned cover semantics, auth/tenant policy changes.`
- **Renewed approval required if:** implementation requires Flutter changes, media architecture migration, new API fields, production data repair script, or broader occurrence-owned cover semantics.

## Rules Acknowledgement / Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `delphi-ai/rules/core/todo-driven-execution-model-decision.md` | Laravel code/test/docs changes require TODO authority and approval. | `APROVADO` before code edits; criterion-specific evidence before delivery. | Implementing before approval. | Wait for approval after TODO creation. |
| `delphi-ai/workflows/docker/todo-driven-execution-method.md` | This TODO is the governing execution contract. | Phase routing, approval, delivery gates. | Closing with aggregate test evidence only. | Keep evidence in this TODO as work proceeds. |
| `/home/elton/Dev/repos/delphi-ai/skills/bug-fix-evidence-loop/SKILL.md` | Production regression with false-green tests. | Real payload evidence, fail-first test, architecture prevention assessment. | Treating existing green tests as sufficient. | New regression test is mandatory. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-creation-standard/SKILL.md` | New Laravel feature regression coverage is required. | Explicit semantic assertions, safe runner evidence. | Status-only assertions. | Test must assert payload image URL semantics. |
| `/home/elton/Dev/repos/delphi-ai/skills/test-orchestration-suite/SKILL.md` | Delivery confidence depends on focused Laravel test execution. | Stage statuses as passed/failed/not-run. | Claiming broad safety from a test command that did not execute. | Record exact commands/results in Completion Evidence Matrix. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | Add Laravel regression coverage for `GET /api/v1/events/{slug/id}?occurrence={occurrence_id}` after an event cover upload. | `Laravel feature integration test` | Added `EventCrudControllerTest::test_public_event_detail_occurrence_uses_event_cover_after_create_upload`; RED failed before implementation; GREEN passed in focused run. | `local Laravel` | `passed` | Covers public detail with selected occurrence after cover upload. |
| `SCOPE-02` | `Scope` | Ensure public event detail returns the Event cover in `data.thumb.data.url` when the selected occurrence lacks or has stale mirrored `thumb`. | `code+test` | `EventQueryService::formatEventDetail()` overlays parent Event `thumb`; stale occurrence test passed in focused and full file runs. | `local Laravel` | `passed` | Protects missing/stale occurrence thumb. |
| `SCOPE-03` | `Scope` | Overlay parent Event `thumb` into selected occurrence detail reads so existing stale occurrence records are safe immediately after deploy. | `code+test` | `test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale` manually clears occurrence `thumb` and still receives Event cover. | `local Laravel` | `passed` | Existing production stale records do not require immediate DB repair. |
| `SCOPE-04` | `Scope` | Mirror only parent Event `thumb` into occurrence records after cover upload/remove so future reads stay coherent without overwriting occurrence-owned fields. | `code+test` | `EventOccurrenceSyncService::mirrorThumbFromEvent()` updates `thumb` only; full EventCrud suite passed, including occurrence-owned programming/profile preservation. | `local Laravel` | `passed` | Avoids full occurrence resync after media mutation. |
| `SCOPE-05` | `Scope` | Preserve existing occurrence-owned semantics for date/time, programming, related profiles, and location. | `test` | Full `EventCrudControllerTest.php` passed `153 tests, 922 assertions`, including occurrence detail selection, programming, profiles, and location assertions. | `local Laravel` | `passed` | Existing occurrence contract stayed green. |
| `SCOPE-06` | `Scope` | Preserve the canonical image priority `event.thumb -> linked_account_profiles -> venue`. | `test+review` | New test asserts Event cover is used and not equal to Venue `cover_url`; code review confirms no new ordered fallback chain. | `local Laravel` | `passed` | Venue remains fallback only after Event cover is absent. |
| `SCOPE-07` | `Scope` | Record why the previous canonical resolver delivery had false-green coverage for this route. | `todo evidence` | Context, Mandatory Bug-Fix Questions, and Coverage Matrix in this TODO record that the missing `route` coverage was the public detail API read with `?occurrence=...`. | `docs` | `passed` | Documents the false-green gap from the previous delivery. |
| `DOD-01` | `Definition of Done` | A fail-first Laravel feature test proves a single-occurrence event created/updated with cover upload returns `data.thumb.data.url` from `/api/v1/media/events/{event_id}/cover` on the public detail endpoint when queried with `occurrence`. | `Laravel feature integration test` | RED focused regression command for the public detail `endpoint` failed before implementation because `data.thumb.data.url` was empty; GREEN focused rerun passed `3 tests, 14 assertions`; full touched file passed `153 tests, 922 assertions`. | `local Laravel` | `passed` | Directly reproduces and fixes the production failure path with local mutation/readback. |
| `DOD-02` | `Definition of Done` | A regression assertion proves Venue media does not win while Event cover exists. | `Laravel feature integration test` | `EventCrudControllerTest::test_public_event_detail_occurrence_uses_event_cover_after_create_upload` asserts event media route is present and not equal to `data.venue.cover_url`; focused suite passed `8 tests, 64 assertions`. | `local Laravel` | `passed` | Negative assertion prevents venue fallback from masking event cover. |
| `DOD-03` | `Definition of Done` | The implementation fixes existing stale occurrence payloads at read time and synchronizes occurrence `thumb` after media mutation with coverage for both paths. | `code+Laravel feature integration test` | `EventQueryService::formatEventDetail()` overlays parent `thumb`; `EventOccurrenceSyncService::mirrorThumbFromEvent()` mirrors thumb only; `EventMediaService::applyUploads()` calls mirror after media mutation; stale and remove-cover tests passed. | `local Laravel` | `passed` | Covers both stale production records and future upload/remove writes. |
| `DOD-04` | `Definition of Done` | Existing public metadata behavior remains unchanged and still resolves the Event cover. | `Laravel feature integration test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=PublicWebMetadataShellTest tests/Feature/Tenants/PublicWebMetadataShellTest.php` passed `11 tests, 72 assertions`. | `local Laravel` | `passed` | OG/meta path remains resolver-owned and green. |
| `DOD-05` | `Definition of Done` | Existing event occurrence detail tests still pass. | `Laravel feature integration test` | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` passed `153 tests, 922 assertions`. | `local Laravel` | `passed` | Full event CRUD/detail file covers adjacent occurrence contracts. |
| `DOD-06` | `Definition of Done` | The TODO records the false-green reason from the previous delivery and the new coverage that closes it. | `todo evidence` | Context, Mandatory Bug-Fix Questions, Coverage Matrix, and Completion Evidence Matrix in this TODO name the public detail route coverage gap. | `docs` | `passed` | Prior delivery missed public detail API with selected occurrence after media mutation. |
| `VAL-01` | `Validation Steps` | Run the new focused regression test before implementation and confirm it fails for the current behavior. | `test` | RED safe-runner focused regression command failed with `Failed asserting that two strings are not identical` because `thumbUrl` was `''`. | `local Laravel` | `passed` | Fail-first confirmed. |
| `VAL-02` | `Validation Steps` | Run the new focused regression test after implementation and confirm it passes. | `test` | GREEN safe-runner command above passed `3 tests, 14 assertions`. | `local Laravel` | `passed` | Regression fixed. |
| `VAL-03` | `Validation Steps` | Run focused event feature tests covering public detail occurrence selection, cover upload, and cover removal. | `test` | Focused event command with cover/detail/remove/multipart filters passed `8 tests, 64 assertions`. | `local Laravel` | `passed` | Covers adjacent event media + occurrence selection behavior. |
| `VAL-04` | `Validation Steps` | Run the Laravel safe runner for the touched test file or a broader focused suite before claiming `Local-Implemented`. | `test` | Full `EventCrudControllerTest.php` safe-runner passed `153 tests, 922 assertions`. | `local Laravel` | `passed` | File-level local CI-equivalent evidence. |
| `VAL-05` | `Validation Steps` | Run Laravel architecture guardrails after service injection change. | `static guardrail` | `docker compose exec -T app composer run architecture:guardrails` passed with `[ARCH-GUARDRAILS] PASS`. | `local Laravel` | `passed` | Confirms no package architecture violation from new service dependency. |

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / focused event cover detail regression` | Directly guards production bug. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=public_event_detail_occurrence_uses_event_cover_after_create_upload tests/Feature/Events/EventCrudControllerTest.php` | `Local-Implemented` | `passed` | RED failed before implementation on this test; combined GREEN rerun of the three new regression tests passed `3 tests, 14 assertions`. | Fail-first and post-fix evidence recorded. |
| `laravel-app / focused event cover removal regression` | Guards inverse stale occurrence cover after removal. | Same focused command above. | `Local-Implemented` | `passed` | `test_event_update_remove_cover_clears_occurrence_detail_thumb` passed in GREEN run. | Covered by same focused command with explicit assertions. |
| `laravel-app / focused event CRUD/detail file` | Covers adjacent event detail and cover upload behavior. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Events/EventCrudControllerTest.php` | `Local-Implemented` | `passed` | `PASS: 153 tests, 922 assertions`. | Full touched test file green. |
| `laravel-app / public metadata` | Confirms OG/meta path remains unchanged. | `./laravel-app/scripts/delphi/run_laravel_tests_safe.sh --filter=PublicWebMetadataShellTest tests/Feature/Tenants/PublicWebMetadataShellTest.php` | `Local-Implemented` | `passed` | `PASS: 11 tests, 72 assertions`. | Metadata was correct in production and stayed green. |
| `laravel-app / architecture guardrails` | New service injection touches package architecture. | `docker compose exec -T app composer run architecture:guardrails` | `Local-Implemented` | `passed` | `[ARCH-GUARDRAILS] PASS - no architecture violations found.` | Static package guardrail green. |

## Decision Adherence Validation
| Decision ID | Validation Method | Status | Notes |
| --- | --- | --- | --- |
| `D-01` | `Adherent` | `EventCrudControllerTest::test_public_event_detail_occurrence_uses_event_cover_after_create_upload` asserts `data.thumb.data.url` contains `/api/v1/media/events/{event_id}/cover`. | Parent Event cover appears in occurrence detail payload. |
| `D-02` | `Adherent` | Full `EventCrudControllerTest.php` passed `153 tests, 922 assertions`, including occurrence detail selection and programming/profile tests. | Occurrence selection semantics preserved. |
| `D-03` | `Adherent` | New negative assertion checks Venue `cover_url` is not the detail thumb while Event cover exists. | No fallback order duplication introduced. |
| `D-04` | `Adherent` | `test_public_event_detail_occurrence_uses_parent_event_cover_when_occurrence_thumb_is_stale` manually nulls occurrence `thumb` and still reads Event cover. | Existing stale production records are protected by read-time overlay. |
| `D-05` | `Adherent` | `EventOccurrenceSyncService::mirrorThumbFromEvent()` updates only `thumb`, timestamps, and `updated_from_event_at`; remove-cover regression asserts occurrence detail clears. | No broad occurrence resync after media mutation. |
| `D-06` | `Adherent` | No analyzer rule added; TODO records `no-rule-needed` because root cause is mutation ordering/persisted snapshot freshness. | Integration coverage is the guard. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Local-only implementation | CI/Copilot-style P1/P2 failure modes in bounded local diff. | `passed` | `git -C laravel-app diff --check`; local tests and architecture guardrails listed above. | `none` | GitHub/Copilot PR preflight is for the later PR creation step; no local P1/P2 finding found. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Canonical image resolver ownership | Check for reintroduced local ordered hero fallback chain. | `passed` | `EventQueryService::formatEventDetail()` overlays raw parent `thumb`; no ordered fallback chain was added outside resolver ownership. | `none` | Fix corrects payload source, not hero fallback selection logic. |
| Occurrence mirror consistency | Check for broad occurrence resync after media mutation. | `passed` | `EventOccurrenceSyncService::mirrorThumbFromEvent()` is thumb-only; stale-read and remove-cover tests passed. | `none` | Current production stale records are handled at read time; future writes mirror thumb. |

## Security Risk Assessment
- **Risk level:** `low`
- **Why this risk level:** The public event detail payload changes from stale/null `thumb` to the already-public parent Event cover URL. No auth, tenant access, upload authorization, or media route policy changed.
- **Attack surface in scope:** public read payload only.
- **Attack simulation decision:** `recommended by audit floor; satisfied by focused review`
- **Review evidence:** checked touched diff for auth/tenant/policy changes; none present. Public metadata and architecture guardrails remain green.
- **Residual security risk:** `none identified`

## Performance & Concurrency Risk Assessment
- **Policy schema version:** `pcv-1`
- **Global sensitivity level:** `low`
- **Why this level:** Read-time overlay is O(1) on an already-loaded parent Event. Media mutation adds one bounded `update where event_id = ?` against occurrence mirrors for that Event only.
- **Current delivery stage at review time:** `Local-Implemented`

| Lane ID | Lane | Trigger Result | Trigger Severity | Trigger Reason Code | Gate Deadline | Minimum Evidence Rule | State | Residual Risk | Uncertainty Reason Code |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `EPS` | `endpoint-performance-scrutiny` | `recommended` | `low` | `EPS-DATA-PATH-CHANGED` | `before_local_implemented` | `EPS-E1` | `passed` | `none` | `none` |
| `FRC` | `frontend-race-condition-validation` | `not-applicable` | `low` | `not-applicable` | `before_local_implemented` | `not-applicable` | `not_applicable` | `none` | `none` |
| `BCI` | `backend-concurrency-idempotency-validation` | `not-applicable` | `low` | `not-applicable` | `before_local_implemented` | `not-applicable` | `not_applicable` | `none` | `none` |
| `RLS` | `runtime-load-stress-validation` | `not-applicable` | `low` | `not-applicable` | `before_local_implemented` | `not-applicable` | `not_applicable` | `none` | `none` |

## Test Quality Audit
- **Outcome:** `medium heuristic, reviewed as non-blocking for this patch`
- **Evidence:** `bash delphi-ai/tools/test_quality_audit.sh --path laravel-app/tests/Feature/Events/EventCrudControllerTest.php` found no hard bypass markers, no test-only support route usage, no no-exception-only assertions, and no mock fallback in the new tests.
- **Finding disposition:** existing file-wide `Sanctum::actingAs` and status-assertion signals are legacy suite patterns. The new tests include semantic assertions for `data.thumb.data.url`, occurrence identity, venue-not-winning behavior, stale occurrence fallback, and remove-cover clearing, so the regression cannot pass on status alone.

## Verification Debt Audit
- **Outcome:** `none`
- **Evidence:** `bash delphi-ai/tools/verification_debt_audit.sh --todo ... --path <touched files>` returned `Outcome heuristic: none` and `Inline code TODO debt classification: none`.
- **Accepted residual debt:** none for the touched implementation. Triple-review and PR review are later promotion/closeout gates before moving this TODO out of active/promotion flow.

## Final Review
- **Status:** `passed for Local-Implemented`
- **Correctness:** regression was reproduced before fix and covered by three focused tests after fix.
- **Performance:** no new read query; write mirror is bounded by `event_id`.
- **Elegance / Structural Soundness:** no new duplicated fallback chain; occurrence-specific semantics stay in occurrence payload while parent-owned media is restored from parent Event.
- **Residual risk:** production/main promotion is outside this approved stage lane; stage now carries and deploys the fix.

## Stage Promotion Closeout
| Surface | Evidence | Result |
| --- | --- | --- |
| Laravel source PR `fix -> dev` | `belluga/belluga_now_backend#226`; merged `2026-05-25`; merge SHA `cb70f953ab89c789ec071e29a0d9c861d442dd36` | `passed` |
| Laravel lane PR `dev -> stage` | `belluga/belluga_now_backend#227`; merged `2026-05-25`; stage SHA `3dfa950f6342513d1ef77f354a6cf897a70b9399` | `passed` |
| Laravel CI evidence | PR #226, post-merge `dev` run `26420275013`, PR #227, and post-merge `stage` run `26420723602` all passed required Laravel checks. | `passed` |
| Docker bot gitlink PR | `belluga/belluga_now_docker#759`; merged `bot/next-version -> dev`; dev SHA `375eff5046c8c77165d36f2186652af344975841` | `passed` |
| Docker lane PR `dev -> stage` | `belluga/belluga_now_docker#760`; merged `2026-05-25`; stage SHA `4186dd8e45bc6c06a6bd108961dfcb6b789d50e1` | `passed` |
| Docker stage deploy/smoke | Orchestration run `26421132863`; `Deploy Stage` passed in `15m57s`, including web provenance, real navigation smoke, mutation navigation smoke, and successful revision marking. | `passed` |
| Stage completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario laravel-only --docker-repo belluga/belluga_now_docker --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go` |
| Docker gitlink alignment | Completion guard verified `laravel-app` on Docker `stage` points to `3dfa950f6342513d1ef77f354a6cf897a70b9399`, matching Laravel `stage`. | `passed` |

## Main Promotion Closeout
| Surface | Evidence | Result |
| --- | --- | --- |
| Laravel lane PR `stage -> main` | `belluga/belluga_now_backend#228`; merged `2026-05-25`; main merge SHA `f54fea3b49c8cd483fb53926ec5704c6f580cee6`; contains Laravel stage SHA `3dfa950f6342513d1ef77f354a6cf897a70b9399`. | `passed` |
| Laravel main CI evidence | Post-merge `main` run `26422879126`; required Laravel CI completed `success`. | `passed` |
| Docker lane PR `stage -> main` | `belluga/belluga_now_docker#761`; merged `2026-05-25`; Docker main merge SHA `256331ef2b3a04f274304ae4be050bb2c2ffdd20`; source stage SHA `4186dd8e45bc6c06a6bd108961dfcb6b789d50e1`. | `passed` |
| Docker production deploy/smoke | Orchestration run `26423499545`; `Preflight Validation` passed in `2m24s`; `Deploy Production` passed in `5m44s`, including deployed SHA validation, public endpoint probes, web provenance, mutation hard-block assertion, production real navigation smoke, and successful revision marking. | `passed` |
| Docker gitlink alignment on main | Docker `main` `laravel-app` gitlink is `3dfa950f6342513d1ef77f354a6cf897a70b9399`; completion guard accepted it as aligned to Laravel main by ancestry. | `passed` |
| Main completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane main --scenario laravel-only --docker-repo belluga/belluga_now_docker --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go` |
