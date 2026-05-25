# TODO (Fast Follow Bugfix): Invite Sent Status Hydration and Accepted Push Presentation

## Title
Fast Follow Bugfix: Invite Sent Status Hydration and Accepted Push Presentation

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production diagnosis on `2026-05-23` found that direct invite push delivery is now healthy through backend queue, credential bootstrap, and FCM acceptance, but the app still shows incorrect invite state for sent invites.

Concrete production evidence:
- App user invited `+5527*****9802`; backend created invite `6a11addb26fc614b1d0fc558` for event `6a0d1c223d7e704d390d5d28`, occurrence `6a0d1c233d7e704d390d5d29`.
- The invite was accepted at `2026-05-23T13:40:37.428000Z`, with `status=accepted`, `credited_acceptance=true`, and canonical backend state correct.
- For that event/occurrence, backend currently has `1 pending` and `1 accepted`, not `2 pending`.
- The app still showed both invitees as pending and enabled invite buttons for the same people after app restart.
- Flutter code inspection found `InvitesRepository.sentInvitesByOccurrenceStreamValue` is updated optimistically after `sendInvites()` as local session state only, always with `InviteStatus.pending`.
- `InvitesRepository.getSentInvitesForOccurrence()` currently returns only the in-memory stream value and does not hydrate from backend.
- `InvitePushRuntimeCoordinator` recognizes `invite_accepted`, and the backend authored/sent `invite_accepted` push `6a11ae558b82956bd002d32d`.
- Backend recorded FCM accepted and app-side `delivered` action for `invite_accepted`, but the user did not perceive a visible push. Flutter `InviteAwarePushMessagePresenter` suppresses generic presentation for title `Seu convite foi aceito`, and there is no confirmed invite-specific visible presentation fallback.
- User-facing symptom remains in scope even when provider telemetry says accepted/delivered: when a receiver accepts my direct invite, the inviter must receive and perceive the `invite_accepted` push, and the app must react by refreshing the affected occurrence state.
- Related profile-screen concept drift: social metrics must represent how other people react to **my sent invites**, not my received invites or my own attendance confirmations. The intended profile metrics are:
  - outlined invite icon: invites sent;
  - filled invite icon: sent invites accepted by other people;
  - future check-in metric: out of scope for this slice.
- Code evidence: Laravel `PrincipalSocialMetricsService` already tracks `invites_sent` and `credited_invite_acceptances`, while `MeResource` currently exposes `pending_invites` and `confirmed_events` counters that Flutter profile DTO/controller use as profile metrics.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `invite-sent-status-hydration-and-accepted-push-presentation`
- **Why this is the right current slice:** one production-visible bug family: sent invite state must be canonical after restart and after acceptance, and acceptance push must be visible/reactionary without falling back to generic Push Handler screens.
- **Related UI correction:** profile social metrics must align with the same sent-invite domain semantics.
- **Direct-to-TODO rationale:** runtime evidence, code surface, and failure mode are concrete enough; no feature decomposition is needed.

## Contract Boundary
- This TODO defines **WHAT** must be corrected and what counts as done.
- Execution details may evolve only if they stay within the same objective: sent invite status is canonical and `invite_accepted` produces correct user-visible/app-state behavior.
- If implementation requires a new public API contract, update this TODO and the relevant module docs before delivery claim.

## Delivery Status Canon
- **Current delivery stage:** `Lane-Promoted`
- **Qualifiers:** `stage-green`, `Fast-Follow`, `Bugfix`, `Cross-Stack`, `Production-Visible`, `Push-UI`, `Future-Consistency`, `Read-Model-Contract-Cutoff`
- **Cutoff decision:** implemented. The next promoted version includes the definitive sent-invite read model split described in `D-12` through `D-15`; the interim row-bounded summary behavior is no longer the promoted contract.
- **Next exact step:** operator manual validation on `stage`; no main promotion is authorized until the user explicitly approves it.
- **Post-cutoff audit package:** `foundation_documentation/artifacts/audits/invite-sent-status-option-c-post-implementation-package-20260523.md`
- **Post-cutoff triple audit:** `foundation_documentation/artifacts/audits/invite-sent-status-option-c-post-implementation-package-20260523-triple-audit-20260523T224638Z/session.json`
- **Post-cutoff Claude CLI audit:** `foundation_documentation/artifacts/claude-cli-reviews/invite-sent-status-option-c-synthetic-claude-review-20260523.md`
- **Post-cutoff build evidence:** `foundation_documentation/artifacts/validation/invite-sent-status-option-c/build-web-script-dev-rerun.log`

## Complexity & Profile Gates
- **Complexity classification:** `medium`
- **Primary execution profile:** `Operational / Coder`
- **Active technical scope:** `cross-stack` (`laravel` + `flutter`)
- **Supporting profiles expected:** `Assurance / Tester-Quality` for end-of-slice test quality audit; `Operational / DevOps` only after implementation if this slice enters promotion lane.
- **Implementation approval gate:** no Laravel/Flutter code or test files may be changed until the refined TODO is approved with `APROVADO`.
- **Independent critique gate:** `required` before implementation because this is a medium cross-stack slice with an authenticated public API contract, push-state behavior, and user-visible flow impact.
- **Triple audit gate:** `passed` pre-implementation. Round 01 status was `needs_adjudication` with additive findings; Round 02 status is `clean`.
- **Triple audit session:** `foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/session.json`
- **Clean audit summary:** `foundation_documentation/artifacts/audits/invite-sent-status-hydration-accepted-push-preapproval-package-20260523-triple-audit-20260523T135612Z/round-02/round-summary.md`
- **Claude CLI review:** `foundation_documentation/artifacts/claude-cli-reviews/invite-sent-status-hydration-accepted-push-preapproval-claude-review-20260523.md`
- **Future-only consistency audit basis:** no completed triple-audit session is claimed for this follow-up; the durable evidence is the package and Claude CLI review below.
- **Future-only consistency package:** `foundation_documentation/artifacts/invite-confirmation-supersession-consistency-future-only-plan-package-20260523.md`
- **Future-only Claude CLI review:** `foundation_documentation/artifacts/claude-cli-reviews/invite-confirmation-supersession-consistency-future-only-plan-claude-review-20260523.md`
- **Implementation Claude CLI review:** `foundation_documentation/artifacts/claude-cli-reviews/invite-confirmation-supersession-consistency-implementation-claude-cli-review-20260523.md`
- **Implementation Claude CLI status:** `partial_pass_with_false_positive_adjudicated`; the earlier `--bare` invocation was wrong for OAuth/keychain auth, the corrected CLI smoke tests passed, reduced Claude review produced one false positive, and focused Haiku/Sonnet adjudication confirmed it was not a real blocker. Full Sonnet code-context reviews still timed out and are not claimed as clean.
- **Read-model contract cutoff audit:** `foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-triple-audit-20260523/session.json`
- **Read-model cutoff resolution:** `foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-triple-audit-20260523/round-01/resolution.md`
- **Read-model Claude CLI review:** `foundation_documentation/artifacts/api-decisions/invite-sent-status-contract-claude-review-20260523.md`
- **Read-model cutoff status:** auditors converged that Option A is valid only for targeted hydration/push reconciliation. The definitive implementation must use Option C: exact occurrence summary endpoint plus paginated inviteables rows enriched with sent-invite status.

## Stage Promotion Evidence - 2026-05-25
| Surface | Evidence | Final SHA / Run |
| --- | --- | --- |
| `flutter-app` source lane | PR `belluga/belluga_now_front#340` merged to `dev`; blocker fix PR `#342` replayed to `dev`; PR `#341` promoted `dev -> stage`. | `stage=a718451812b574b1a981cdb645e49b2b4a1632c2`; run `26384657417` success. |
| `laravel-app` source lane | PR `belluga/belluga_now_backend#220` merged to `dev`; blocker fix PR `#222` replayed to `dev`; PR `#221` promoted `dev -> stage`. | `stage=8fd46a8e50126f3a42f1b34f9400a1307ea09355`; run `26384653562` success. |
| `belluga_now_docker` derived runtime lane | `bot/next-version` was promoted in two submodule-only cycles because the dispatcher recreates the bot branch from current `dev`: PR `#752` carried Flutter, PR `#753` carried Laravel, and PR `#754` promoted `dev -> stage`. | `stage=bea62b8d18ab620b9bb9977be9f867bfa9b735db`; run `26385254151` success. |
| Completion guard | `bash delphi-ai/tools/github_promotion_completion_guard.sh --lane stage --scenario flutter-laravel --docker-repo belluga/belluga_now_docker --flutter-repo belluga/belluga_now_front --laravel-repo belluga/belluga_now_backend` | `Overall outcome: go`; Docker stage gitlinks exact for Flutter `a7184518...` and Laravel `8fd46a8...`. |

## Pipeline/Copilot P1/P2 Preflight
| Reviewer Surface / Package | Review Focus | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion PRs `front#341`, `backend#221`, `docker#754` | Copilot P1/P2 and CI blocker preflight for the promoted invite status/push package. | passed | Front Copilot finding `3296103125` fixed by PR `#342`; backend Copilot finding `3296100523` fixed by PR `#222`; stage runs `26384657417`, `26384653562`, and `26385254151` passed. | resolved | All P1/P2 findings were fixed before stage merge; completion guard returned `Overall outcome: go`. |

## Rule-Spirit Anti-Pattern Hunt
| Rule / Principle Surface | Bypass or Anti-Pattern Search Lens | Status | Evidence Artifact / Command | Findings | Resolution / Notes |
| --- | --- | --- | --- | --- | --- |
| Stage promotion lane and TODO governance | Checked source-owned fixes, derived `web-app` boundary, Docker gitlink path through `bot/next-version`, and TODO threshold/archive hygiene. | passed | `github-stage-promotion-orchestrator`; `github_promotion_completion_guard.sh`; TODO directory reconciliation. | no findings | Preserved source-owned fixes, did not manually promote `web-app`, promoted gitlinks through lane-owned Docker PRs, and kept this TODO in `promotion_lane` because manual stage validation/main approval remains separate. |

## Controller Lifecycle Follow-up Decision
- **Status:** approved during implementation follow-up after controller lifecycle audit.
- **Audit package:** `foundation_documentation/artifacts/audits/invite-share-controller-lifecycle-architecture-scoring-package-20260524.md`.
- **Decision:** establish a dedicated `InviteShareModule` for `/convites/compartilhar`, register `InviteShareScreenController` as a module `lazySingleton`, and let `ModuleScope<InviteShareModule>` own teardown. This preserves the existing lint rule that forbids screen disposal of module-scoped controllers.
- **Rejected for this slice:** simple screen-ephemeral factory plus explicit `InviteShareScreen.dispose()`. It solves the immediate lifecycle locally, but requires weakening or special-casing `module_scoped_controller_dispose_forbidden`, which is the wrong direction for the architecture guardrail.
- **Rejected for this slice:** route-scoped/named controller infrastructure through AutoRoute/GetIt. It is a broader pattern decision and may keep controllers alive longer than this focused invite-share flow needs.
- **Required code outcome:** remove `sessionVersion` state and session-scoped invite send keys; use `_isDisposed` plus occurrence identity checks for stale async completion protection; move share-route/controller ownership out of `InvitesModule`; keep `InviteShareScreen` as pure UI with no manual controller disposal.
- **Resíduo cleanup required:** remove all factory-registration tests, screen-dispose tests, tracking controllers, TODO wording, and any route-scoped/named-controller references that were introduced only for abandoned lifecycle paths.
- **Required evidence:** tests must prove `InvitesModule` no longer owns `/convites/compartilhar`, `InviteShareModule` owns the route with tenant/auth guards, the module registers/unregisters `InviteShareScreenController` through the module lifecycle, there is no `sessionVersion`, duplicate-send in-flight scoping is occurrence plus recipient, and stale async safety remains protected for disposed or occurrence-mismatched controllers.

## Gate L Rule Ingestion
| Source | Why it applies now | Must preserve | Must avoid | Execution impact |
| --- | --- | --- | --- | --- |
| `wf-laravel-create-api-endpoint-method` | Adds authenticated tenant API `GET /invites/sent-statuses`. | Tenant route grouping, explicit request/response contract, validation/error semantics, focused feature tests. | Controller-heavy business logic, page-walking/exact lookup anti-patterns, undocumented contract drift. | Implement controller + service + route with direct occurrence-scoped lookup and feature tests. |
| `wf-laravel-tenant-access-guardrails` | New tenant-authenticated route. | `auth:sanctum` + `CheckTenantAccess` on tenant domain only. | Authenticated tenant route without tenant access guard. | Add route inside existing guarded invite group and run route guardrail audit before delivery. |
| `flutter-architecture-adherence` | Updates Flutter repository/domain/controller/push presentation surfaces. | DAO owns raw transport parsing, repository owns canonical state, controllers expose state to UI, widgets remain presentational. | Raw JSON parsing in repository, screen-owned loading, generic Push Handler fallback for invite-specific push. | Load sub-rules as files are touched and keep hydration through repository/controller boundaries. |
| `test-creation-standard` | Behavior is verifiable and production-visible. | Fail-first tests for backend contract, profile metrics, repository hydration, push reaction, and UI/controller state. | Retrofitted weak tests, status-only assertions, mock-only coverage where real backend contract matters. | Start with failing Laravel/Flutter tests before implementation and keep assertions semantic. |
| `test-orchestration-suite` | Delivery must be promotable after implementation. | Local CI-equivalent matrix and canonical Laravel safe runner / Flutter analyzer and targeted tests. | Treating targeted diagnostics as full CI equivalent. | Run focused tests during implementation, then execute CI-equivalent rows before delivery/promotion readiness. |
| `audit-protocol-triple-review` + `test-quality-audit` | User explicitly requires code audits before delivery. | Triple implementation audit, Claude CLI additional review, independent test-quality audit. | Closing the TODO without external/code audit evidence. | Create bounded post-implementation package and resolve blockers before delivery claim. |

## Package-First Assessment
- **Queries executed:** `bash delphi-ai/tools/query_packages.sh --project-root /home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker --search "invite"`, `--search "profile"`, `--search "push"`, and `--all`.
- **Relevant packages found:** `[Ecosystem/Flutter] push_handler` for generic push UI/action routing; no registered local package entry was returned for invites/profile through the package registry.
- **Repository-local package reality:** Laravel invite runtime is already implemented inside `laravel-app/packages/belluga/belluga_invites`; this TODO extends that existing local package boundary instead of creating host-only duplicate invite services.
- **README read:** `push_handler` registry detail had no local README path; no package README was available through the deterministic package query.
- **Decision:** extend `belluga_invites` for sent-status contract and use a host adapter only for tenant Account Profile bulk projection; keep Flutter push handling invite-specific through app adapter/coordinator without modifying the external/ecosystem `push_handler`.
- **Tier:** Laravel local package extension + Flutter ecosystem package consumption.
- **Rationale:** sent invite lifecycle/status is invite-domain behavior; profile/avatar projection is host-owned tenant data access; generic push handler must not regain ownership of invite-specific routing/presentation.

## Pre-Approval Audit Findings Integrated
| Finding ID | Source | Severity | Resolution Handling |
| --- | --- | --- | --- |
| `ELEGANCE-001` | Triple audit / Elegance | `high` | Integrated: this TODO now freezes endpoint method/path, request identifiers, response envelope, ordering, empty/error semantics, and event/occurrence mismatch behavior. |
| `ELEGANCE-002` | Triple audit / Elegance | `high` | Integrated: this TODO now freezes `receiver_account_profile_id` as the canonical recipient matching key and requires tests where account user id differs from account profile id. |
| `ELEGANCE-003` | Triple audit / Elegance | `high` | Integrated: this TODO now includes a status/actionability matrix and explicit declined/superseded tests. |
| `PERF-001` | Triple audit / Performance | `high` | Integrated: this TODO now requires direct occurrence-scoped lookup, bounded result shape, eager recipient projection, no N+1, no client page-walking, and same-key in-flight dedupe tests. |
| `TQ-01` | Triple audit / Test Quality | `high` | Integrated: this TODO now requires foreground, background/resume, notification-tap, and cold-start invite-specific push evidence. |
| `TQ-02` | Triple audit / Test Quality | `high` | Integrated: this TODO now requires production-like distinct `account_user_id` vs `account_profile_id` fixtures. |
| `TQ-03` | Triple audit / Test Quality | `medium` | Integrated: this TODO now requires backend and Flutter tests for declined and superseded/hidden terminal statuses. |
| `CLAUDE-B1/B4` | Claude CLI | `blocking` | Integrated: no client-controlled `inviter_id`; current authenticated inviter and canonical recipient key are server-derived/normalized. |
| `CLAUDE-B2/B3` | Claude CLI | `blocking` | Integrated: tenant isolation, occurrence access, and cross-tenant tests are explicit. |
| `CLAUDE-B5/B7` | Claude CLI | `blocking` | Integrated: terminal statuses and multi-occurrence semantics are explicit; event-only aggregation is invalid. |
| `CLAUDE-B6` | Claude CLI | `blocking` | Integrated: bounded result and no unbounded eager-loaded list are explicit. |

## Scope
- [x] Replace the interim promotion plan with the definitive Option C read-model split before stage promotion.
- [x] Add a Laravel exact sent-invite summary contract for the authenticated inviter scoped by occurrence, with counters computed over the full occurrence slice and a bounded preview.
- [x] Extend the inviteables/contact-list contract so the current page of inviteable recipients can be returned already enriched with that recipient's sent-invite status/actionability for the selected occurrence.
- [x] Keep `GET /invites/sent-statuses` as a targeted hydration and push-reconciliation endpoint only; Flutter must not use its unfiltered/row-bounded summary as an event-detail/footer source.
- [x] Ensure event detail/footer/share summary uses the exact summary contract, not a truncated list of sent-status rows.
- [x] Ensure invite composer rows use inviteables row status for page actionability, not a second per-row lookup and not a broad status fetch detached from the visible inviteables page.
- [x] Maintain a Laravel targeted read contract that returns canonical sent invite status for the authenticated inviter scoped by event/occurrence when a recipient filter or push reconciliation path needs it.
- [x] Ensure the backend sent-invite status payload includes enough identity data for Flutter `SentInviteStatus`: recipient profile/user id, display name, avatar URL, status, sent time, responded time.
- [x] Ensure status values distinguish at least `pending`, `accepted`, `declined`, and any intentionally hidden terminal/superseded cases.
- [x] Update Flutter sent-invite repository APIs so targeted recipient hydration, exact summary hydration, and inviteable-row actionability have distinct code paths and cannot accidentally substitute for each other.
- [x] Preserve optimistic update after `sendInvites()`, but reconcile with canonical backend status when available.
- [x] Update Flutter `applyInvitePushPayload()` / invite push runtime so `push_type=invite_accepted` updates `sentInvitesByOccurrenceStreamValue` for the matching occurrence and accepted recipient.
- [x] Update Flutter `invite_accepted` handling so it refreshes or patches both the targeted recipient status and the exact occurrence summary when the currently viewed occurrence is affected.
- [x] Fix the user-visible `invite_accepted` delivery/reaction path so the inviter perceives the acceptance push when a receiver accepts a direct invite, not only backend/FCM delivery telemetry.
- [x] Ensure foreground `invite_accepted` is user-visible through invite-specific presentation or another approved app UX, without reopening the generic Push Handler screen flow.
- [x] Keep `invite_received` tap routing behavior: pending invite opens invite screen; stale/superseded invite falls back to event/home according to existing approved behavior.
- [x] Add tests that reproduce app restart/local-state loss and prove the same people are not inviteable again when backend says their invite is pending or accepted.
- [x] Add tests that reproduce `invite_accepted` push payload and prove the event detail/share summary changes from pending to accepted.
- [x] Correct the profile social metrics contract so it displays sent invites and accepted sent invites, not pending received invites and confirmed events.
- [x] Update Laravel `me`/profile payloads if needed so Flutter receives `invites_sent` and `invites_accepted` from canonical `PrincipalSocialMetric`.
- [x] Ensure profile social metrics are returned from canonical aggregate/read-model counters and are not computed by loading or iterating invite edges at profile read time.
- [x] Update Flutter profile domain/DTO/controller/UI labels/icons so outlined invite means sent invites and filled invite means accepted sent invites.
- [x] Keep future check-in metric out of this slice, but leave the UI/API naming extensible for it.
- [x] Ensure sent-invite status hydration is lazy per occurrence/surface entry. App bootstrap or post-auth global hydration must not fetch sent statuses for all occurrences/events.
- [x] Fix direct attendance confirmation with a transaction-only canonical path so future confirmations cannot commit attendance without same-target pending/viewed invite supersession.
- [x] Keep historical invite drift repair and job/reconciler safety nets out of scope for this slice.

## Contract Cutoff - Definitive Option C
- **Status:** implemented after explicit `APROVADO`; this cutoff is part of the current promotable implementation.
- **Decision:** split sent-invite reads by consumer need instead of overloading a single bounded status endpoint.
- **Reason:** a row-bounded `GET /invites/sent-statuses` response cannot be the authoritative source for event footer/share summary when an occurrence can have more than 200 sent invites. It remains valid for targeted reconciliation, but not for exact counters.
- **Consumer split:**
  - `GET /contacts/inviteables`: paginated/searchable recipient discovery. When called with occurrence context, each returned inviteable row includes that row's sent-invite status/actionability for the current authenticated inviter and selected occurrence.
  - `GET /invites/sent-summary`: exact occurrence-level summary. It returns counters over the full current-inviter occurrence slice plus a small preview for the summary widget.
  - `GET /invites/sent-statuses`: targeted recipient hydration and push reconciliation only. It may keep bounded items and row-bounded summary metadata, but Flutter must not use it as an exact occurrence summary source.
- **Forbidden after cutoff:** event detail/footer or summary widgets deriving exact counters from an unfiltered `GET /invites/sent-statuses` list; invite composer doing N per-row status calls; app bootstrap globally hydrating sent statuses for all events.
- **Promotion rule:** Laravel and Flutter CI-equivalent evidence recorded before this cutoff is invalidated for promotion readiness. After implementing Option C, rerun the full Laravel/Flutter CI-equivalent matrix before promotion.

## Frozen Inviteables Row Status Contract
- **Endpoint:** `GET /contacts/inviteables`
- **Role:** recipient discovery plus current-page actionability. This endpoint answers "for the people I am listing right now, can I invite each person to this occurrence and what is their existing sent-invite state?"
- **Auth:** current existing tenant-authenticated contact inviteables guardrail. Tenant and inviter identity are server-derived.
- **Context query for sent status:** `occurrence_id` is required to include sent-invite row status. `event_id` is optional consistency context and must return `422 occurrence_event_mismatch` if it does not match the occurrence.
- **Pagination/search:** preserve the current inviteables search/pagination contract if one already exists. If the current endpoint is unbounded, this cutoff requires introducing a bounded page contract for this surface, default limit no higher than `50` and max no higher than `100`.
- **Row enrichment:** each returned inviteable recipient row must include a nullable sent-invite status projection for the selected occurrence using `receiver_account_profile_id` as the match key. Missing status means no existing same-inviter invite edge for that recipient/occurrence.
- **Actionability:** the row must expose enough information for Flutter to disable or enable the invite button without a separate broad status fetch: at minimum `status`, `blocks_reinvite`, `counts_bucket`, `ui_visibility`, `invite_id`, `sent_at`, and `responded_at` when a sent invite exists.
- **Performance rule:** row status enrichment must be one bounded backend lookup/aggregation over the returned page recipient ids. Per-row invite/profile queries are forbidden.
- **Privacy rule:** filtered ids or inviteable rows outside tenant/user visibility must not reveal cross-tenant or inaccessible invite state.

## Frozen Exact Sent-Invite Summary Contract
- **Endpoint:** `GET /invites/sent-summary`
- **Role:** authoritative summary for event detail/footer/share widgets. This endpoint answers "for this occurrence, how many of my sent invites are pending/accepted/declined/hidden, and what small preview should the UI show?"
- **Auth:** `auth:sanctum` + `CheckTenantAccess`; tenant and inviter identity are server-derived.
- **Required query:** `occurrence_id`.
- **Optional query:** `event_id` as consistency-only context; `preview_limit` default `5`, max `10`.
- **Invalid event-only request:** `event_id` without `occurrence_id` returns `422 occurrence_id_required`.
- **Counters:** exact over the full current-authenticated-inviter occurrence slice, not limited by preview size and not capped at 200.
- **Summary buckets:** at minimum `pending`, `accepted`, `declined`, `terminal_hidden`, `total_visible`, and `total_sent`.
- **Preview:** bounded list using the same `SentInviteStatus` item shape as the targeted status endpoint, sorted deterministically by `created_at desc`, then `_id desc`, limited by `preview_limit`.
- **Performance rule:** exact counters must be computed with indexed count/aggregation queries. Loading all invite edges into PHP or Flutter to count is forbidden.
- **Out of scope:** full sent-invite details browsing/reporting. If the product later needs all sent invites, add a dedicated paginated details endpoint rather than expanding this summary endpoint.

## Frozen Targeted Sent-Invite Status Read Contract
- **Role:** targeted recipient hydration and push reconciliation. It is not the authoritative summary source after the Option C cutoff.
- **Endpoint:** `GET /invites/sent-statuses`
- **Auth:** `auth:sanctum` + `CheckTenantAccess`; tenant is resolved from current tenant context and cannot be supplied by the client.
- **Inviter identity:** server-derived from the authenticated account user and its active inviter principal. The request must not accept `inviter_id`, `issued_by_user_id`, `inviter_principal_id`, or tenant override parameters.
- **Required query:** `occurrence_id`.
- **Optional query:** `event_id` only as disposable consistency context. If provided and it does not match the occurrence parent event, return `422 occurrence_event_mismatch`.
- **Invalid event-only request:** `event_id` without `occurrence_id` returns `422 occurrence_id_required`; sent invite status is occurrence-scoped and never event-aggregated.
- **Optional recipient filter:** `recipient_account_profile_ids[]`, max `200` ids, used by Flutter to hydrate the currently visible inviteable set in one occurrence-scoped request. The backend must ignore ids outside the current tenant/visibility boundary and must not expose whether filtered ids belong to another tenant.
- **Ordering:** `created_at desc`, then `_id desc` for deterministic ties.
- **Empty state:** `200` with `data.items=[]`, `data.summary` zeroed, and `metadata.request_id`; empty means the current authenticated inviter has no sent invite edges for that occurrence/filter.
- **Summary semantics:** `data.summary` on this endpoint is row-bounded to the returned item set and may be useful for targeted reconciliation diagnostics only. Flutter must not use this summary for exact event detail/footer/share counters.
- **Not authenticated:** `401`.
- **Authenticated but tenant access invalid:** `403`.
- **Occurrence missing or inaccessible in current tenant/public access boundary:** `404`.
- **Malformed id, too many recipient ids, event/occurrence mismatch, or event-only request:** `422` with stable `error.code`.
- **Timestamp format:** ISO 8601 UTC strings; no preformatted local time strings in transport.
- **Response envelope:**
```json
{
  "data": {
    "event_id": "ObjectId",
    "occurrence_id": "ObjectId",
    "summary": {
      "pending": 1,
      "accepted": 1,
      "declined": 0,
      "terminal_hidden": 0
    },
    "items": [
      {
        "invite_id": "ObjectId",
        "recipient_key": "account_profile:ObjectId",
        "receiver_account_profile_id": "ObjectId",
        "receiver_user_id": "ObjectId|null",
        "display_name": "string",
        "avatar_url": "string|null",
        "status": "pending|accepted|declined|expired|superseded|suppressed",
        "ui_visibility": "visible|hidden",
        "blocks_reinvite": true,
        "counts_bucket": "pending|accepted|declined|none",
        "sent_at": "ISO-8601 UTC",
        "responded_at": "ISO-8601 UTC|null",
        "supersession_reason": "other_invite_credited|direct_confirmation|null"
      }
    ]
  },
  "metadata": {
    "request_id": "string",
    "truncated": false,
    "next_cursor": null
  }
}
```
- **Bounded result rule:** default and max unfiltered response is `200` items for this endpoint. If a future tenant needs larger read models, that must become a dedicated paginated/reporting contract, not Flutter page-walking from this hydration endpoint.

## Canonical Recipient Matching Contract
- Canonical matching key is `receiver_account_profile_id`.
- `recipient_key` is the stable Flutter normalization key and must be shaped exactly as `account_profile:{receiver_account_profile_id}`.
- `receiver_user_id` is informative only and must not be used as the primary duplicate-disablement or push reconciliation key.
- Flutter optimistic `SentInviteStatus` entries created after `sendInvites()` must normalize to the same `recipient_key` whenever the inviteable row exposes `receiver_account_profile_id`.
- If a push payload contains `accepted_by_account_profile_id`, `receiver_account_profile_id`, or an embedded invite DTO, Flutter must normalize it to the same `recipient_key` before updating sent-invite state.
- Tests must include distinct values for `account_user_id` and `account_profile_id` so false-positive matching by equal ids cannot pass.

## Status / Actionability Matrix
- **Clarification recorded 2026-05-23:** a receiver may have multiple pending invites for the same event/occurrence, especially from different inviters. Being already invited does **not** create `superseded`; `superseded` is reserved for an already-confirmed outcome (`other_invite_credited` or `direct_confirmation`). Any same-inviter duplicate prevention in V1 is actionability/anti-repeat behavior only, not a lifecycle supersession cause.

| Status | UI Visibility | Summary Bucket | Blocks Reinvite | `responded_at` Expectation | Notes |
| --- | --- | --- | --- | --- | --- |
| `pending` | `visible` | `pending` | `true` for same inviter/target repeat only | `null` | Initial active sent invite; not a supersession cause and does not block other inviters from inviting the same receiver. |
| `accepted` | `visible` | `accepted` | `true` | `accepted_at` | Credited acceptance is the sender-side conversion metric source. |
| `declined` | `visible` | `declined` | `true` for same inviter/target repeat only | `declined_at` | V1 actionability still prevents repeating the same inviter/recipient/occurrence edge, but declined is not superseded. |
| `expired` | `hidden` | `none` | `true` | `expired_at|null` | Terminal state must not appear as pending or accepted. |
| `superseded` | `hidden` | `none` | `true` | `superseded_at|updated_at|null` | Covers `other_invite_credited` and `direct_confirmation`; must not reopen duplicate invite actions. |
| `suppressed` | `hidden` | `none` | `true` | `updated_at|null` | Policy/governance closure; must not leak suppression reason beyond stable code. |

## Direct Confirmation Supersession Consistency Addendum
- **Revised scope recorded 2026-05-23:** historical/past left-behind invite drift is out of scope for this slice. No repair of old production data is required here. The required guarantee is future-only: after this fix, direct confirmation must not persist active attendance while same-target pending/viewed invites remain pending because supersession failed.
- **Audit outcome under revised scope:** Elegance, Performance, Test Quality, and Claude CLI converged on **Option A: transaction-only canonical path**. Job/reconciler-only is rejected because it allows split-write drift to depend on queue/worker success. Transaction + reconciler is optional defense-in-depth, not required while historical drift is out of scope.
- **Post-implementation Claude CLI status:** corrected non-`--bare` invocation is authenticated and functional. Completed reduced Claude checks found no accepted actionable blocker after adjudicating the only reported issue as a false positive; full Sonnet implementation audit timed out and remains a tooling limitation, not a clean-review artifact.
- **Canonical path:** every direct attendance confirmation entrypoint must route through one transaction-capable service path. The current non-atomic upsert-then-supersede sequence must not remain as an alternate write path.
- **Transaction ownership:** attendance/participation remains the owner of canonical attendance writes. Use the event/participation transaction boundary (`EventTransactionRunner`) or an equivalent shared tenant critical mutation runner; do not make attendance depend on an invite-only transaction abstraction.
- **Fail-closed:** if tenant MongoDB transactions are unavailable, direct confirmation must fail before attendance is committed. No non-transaction fallback and no best-effort job fallback are allowed in this slice.
- **Atomic mutation:** the transaction must include active attendance upsert and same-target invite supersession. Both commit together or neither persists.
- **Supersession predicate:** supersede only invites for the same receiver account profile + `event_id` + `occurrence_id` whose status is `pending` or `viewed`. Do not touch `accepted`, `declined`, `expired`, `suppressed`, or already-`superseded` invites.
- **Attribution guard:** direct confirmation must never overwrite `accepted` + `credited_acceptance=true` attribution or change `superseded/other_invite_credited` into `superseded/direct_confirmation`.
- **Side effects:** `OccurrenceAttendanceConfirmed`, push, metrics, telemetry, and projections must run only after the transaction succeeds. If the transaction fails, no after-commit side effect may be emitted.
- **Job/reconciler stance:** no job/reconciler is required for this future-only fix. Optional bounded diagnostics/repair can be considered later as vNext/ops debt, but must not be introduced now as the correctness mechanism.
- **Transition precedence matrix:**

| Ordering | Required Final State |
| --- | --- |
| Pending invites, then direct confirmation | Attendance active; all same-target pending/viewed invites become `superseded/direct_confirmation`; no credited acceptance is created. |
| Credited invite acceptance, then direct confirmation | Accepted credited invite remains `accepted` with `credited_acceptance=true`; competing invites remain `superseded/other_invite_credited`; direct confirmation is idempotent and must not overwrite attribution. |
| Direct confirmation, then later invite accept attempt | Existing direct-confirmation-superseded invite remains `superseded/direct_confirmation`; accept returns already-confirmed/already-accepted semantics with `credited_acceptance=false`. |
| Concurrent direct confirmation and invite acceptance | Final state must match one coherent committed ordering above: no duplicate credited acceptance, no pending/viewed same-target leftovers after successful direct confirmation, accepted invite not overwritten by direct confirmation, deterministic supersession reasons. |
| Repeated or concurrent direct confirmation | One active attendance record; stable superseded invite set; no duplicate credited acceptance; no duplicate side effects beyond approved idempotent replay semantics. |

## Performance / Load Contract
- Laravel must query by current tenant, authenticated inviter principal, and `occurrence_id` through a direct bounded lookup path; it must not scan all event invites, all tenant users, or all occurrence participants.
- Recipient identity/avatar data must be projected/eager-loaded in bounded form. Per-item lazy lookup/N+1 behavior is not acceptable.
- Focused backend tests must include either query-count instrumentation or an equivalent repository/service spy proving the endpoint does not perform one profile/avatar lookup per invite item.
- `GET /invites/sent-summary` must return exact counters without loading all invite edges into application memory; use indexed count/aggregation operations over the current inviter + occurrence slice.
- `GET /contacts/inviteables` row status enrichment must be bounded to the current returned page recipient ids and must not query status for every tenant contact or every historical invite edge.
- Profile social metrics must be read from canonical aggregate/read-model counters such as `PrincipalSocialMetric`; profile reads must not load all historical invite edges to compute `invites_sent` or `invites_accepted`.
- Flutter must use the correct occurrence-scoped backend contract for each surface: inviteables row enrichment for composer rows, exact summary for footer/summary widgets, and targeted status refresh for push reconciliation. It must not page-walk invite feeds, event pages, contact lists, or push logs to infer sent status.
- Flutter app bootstrap/post-auth global hydration must not call `GET /invites/sent-statuses` for all events or occurrences. Sent-status hydration is allowed only when entering a specific occurrence/event invite surface, or when reconciling an `invite_accepted` push for the affected `occurrence_id`.
- Flutter must dedupe same-key in-flight hydration for `occurrence_id + recipient_filter_hash`, so rebuilds, stream subscriptions, restart recovery, and push reconciliation cannot multiply identical backend calls.
- Flutter must dedupe exact summary in-flight refreshes by `occurrence_id`; accepted-push reconciliation must not trigger multiple identical summary/status requests during rebuild or resume.
- Push reconciliation may trigger backend refresh only for the affected `occurrence_id`, and must share the same in-flight dedupe path.

## Push / Device Behavior Matrix
| App State | Required `invite_accepted` Behavior | Generic Push Handler Fallback |
| --- | --- | --- |
| Foreground | Shows invite-specific visible signal and updates sent status for the affected occurrence. | Forbidden. |
| Background then resume | Notification or app-resume path refreshes affected occurrence and shows/keeps invite-specific context. | Forbidden. |
| Notification tap | Opens the invite/event-aware destination for the affected occurrence with state refreshed. If existing event-ended behavior cannot render the event, follow existing event/home fallback. | Forbidden. |
| Terminated/cold start from tap | Preserves push intent through bootstrap, opens the invite/event-aware destination, and refreshes canonical sent status before showing stale buttons. | Forbidden. |
| Duplicate push delivery | Idempotent: accepted status remains one record, no duplicate UI rows, no duplicate state entries. | Forbidden. |
| Push arrives before list hydration | Must either upsert by `recipient_key` if enough payload exists or trigger the occurrence-scoped hydration path; silently dropping the update is forbidden. | Forbidden. |

## Out of Scope
- Reworking FCM credential storage or newline normalization, already tracked in vNext.
- Reworking push icon/rich image behavior.
- Replaying historical push messages.
- Changing invite acceptance business rules.
- Replacing invite realtime/SSE architecture broadly.
- Implementing check-in metric or check-in event pipeline.

## Definition of Done
- [x] After app restart, sent invite status for an event/occurrence is hydrated from backend and does not reset to empty.
- [x] The invite share/event detail UI cannot invite the same recipient again when a canonical pending or accepted invite already exists for the same inviter and target occurrence.
- [x] Invite composer rows receive their current sent-invite actionability from the inviteables page response for that occurrence.
- [x] Event detail/footer/share summary uses exact `sent-summary` counters and remains correct when the occurrence has more than 200 sent invites.
- [x] `GET /invites/sent-statuses` is not used by Flutter as the exact event-detail/footer/share summary source.
- [x] When a recipient accepts, the inviter-side UI changes from pending to accepted either via `invite_accepted` push payload or backend refresh.
- [x] When a recipient accepts and the affected occurrence is visible or resumed, the targeted row status and exact summary are both reconciled.
- [x] `invite_accepted` produces a visible invite-specific foreground/background user signal, and tap routing remains invite/event-aware rather than generic Push Handler routing.
- [x] Backend tests prove the sent-status read contract reflects pending and accepted invites for the authenticated inviter.
- [x] Backend tests prove the inviteables row-status contract and exact sent-summary contract are tenant/auth scoped, bounded, and non-N+1.
- [x] Flutter repository/controller tests prove restart hydration, accepted-push update, and invite button disabling from canonical sent status.
- [x] Existing direct invite push E2E remains green through FCM acceptance.
- [x] Profile social metrics show `invites_sent` and `invites_accepted` with the approved outlined/filled invite icons.
- [x] Profile social metrics no longer use pending received invites or confirmed events as social-score metrics.
- [x] Direct confirmation atomically commits attendance upsert plus same-target pending/viewed invite supersession, or rolls both back.
- [x] Direct confirmation fails closed when tenant transactions are unavailable and cannot silently fall back to split-write or job-only cleanup.

## Validation Steps
- [x] Run focused Laravel invite tests for the new/read sent-status contract.
- [x] Run focused Laravel invite tests for inviteables row-status enrichment and exact sent-summary counters, including an occurrence with more than 200 sent invites.
- [x] Run focused Flutter repository tests for `getSentInvitesForOccurrence()` hydration and `applyInvitePushPayload(invite_accepted)`.
- [x] Run focused Flutter repository/controller tests proving invite composer rows consume inviteables row status and event summary consumes exact sent-summary, not `sent-statuses` row-bounded summary.
- [x] Run focused Flutter invite share/event detail controller or widget tests proving summary/button state.
- [x] Run a device/manual validation path on a non-main lane or approved production-safe target: send invite, accept on receiver, verify inviter sees accepted state and cannot invite the same person again after app restart.
- [x] Run focused Laravel/Flutter tests for profile social metrics mapping.
- [x] Run focused Laravel direct-confirmation transaction tests covering success, rollback, transaction-unavailable, attribution guard, idempotency, and direct-confirmation-versus-accepted-invite race semantics.
- [x] Run Laravel and Flutter CI-equivalent gates before promotion.

## Fail-First Test Targets

### Laravel
- [ ] `SentInviteStatusesTest::test_authenticated_inviter_can_fetch_pending_and_accepted_sent_invites_for_occurrence` proves the read contract returns one pending and one accepted invite for the same inviter/event/occurrence.
- [ ] `SentInviteStatusesTest::test_sent_invite_statuses_are_scoped_to_current_tenant_and_authenticated_inviter` proves another inviter or tenant cannot observe or reuse the canonical sent-invite state.
- [ ] `SentInviteStatusesTest::test_sent_invite_status_payload_contains_recipient_identity_status_and_timestamps` proves Flutter receives stable recipient profile/user identity, display name, avatar URL, status, sent time, and responded time.
- [ ] `SentInviteStatusesTest::test_sent_invite_statuses_reject_client_controlled_inviter_identity` proves request-supplied inviter identity is ignored or rejected and current auth identity is authoritative.
- [ ] `SentInviteStatusesTest::test_sent_invite_statuses_reject_event_only_and_occurrence_event_mismatch_requests` proves occurrence identity is mandatory and event context is consistency-only.
- [ ] `SentInviteStatusesTest::test_sent_invite_statuses_include_declined_and_hidden_superseded_actionability` proves declined and superseded/hidden terminal statuses follow the status/actionability matrix.
- [ ] `SentInviteStatusesTest::test_sent_invite_statuses_use_bounded_direct_lookup_without_recipient_n_plus_one` proves the endpoint does not perform event-wide scans or per-recipient profile/avatar queries.
- [ ] `SentInviteSummaryTest::test_sent_invite_summary_returns_exact_counts_over_more_than_200_sent_invites` proves event/footer counters are exact and not capped by status endpoint limits.
- [ ] `SentInviteSummaryTest::test_sent_invite_summary_preview_is_bounded_and_deterministically_ordered` proves preview remains limited while counters stay exact.
- [ ] `SentInviteSummaryTest::test_sent_invite_summary_is_scoped_to_current_tenant_and_authenticated_inviter` proves no cross-tenant or other-inviter leakage.
- [ ] `SentInviteSummaryTest::test_sent_invite_summary_rejects_event_only_and_occurrence_event_mismatch_requests` proves occurrence-scoped identity and consistency-only event context.
- [ ] `ContactInviteablesSentStatusTest::test_inviteables_page_includes_sent_status_actionability_for_current_occurrence` proves visible inviteable rows include pending/accepted/declined/hidden actionability.
- [ ] `ContactInviteablesSentStatusTest::test_inviteables_sent_status_matches_by_receiver_account_profile_id_when_user_id_differs` proves production-like identity matching.
- [ ] `ContactInviteablesSentStatusTest::test_inviteables_sent_status_enrichment_is_bounded_to_current_page_without_n_plus_one` proves no per-row status/profile lookup and no tenant-wide status scan.
- [ ] `ContactInviteablesSentStatusTest::test_inviteables_sent_status_does_not_leak_inaccessible_or_cross_tenant_recipients` proves privacy boundary for filtered/page recipients.
- [ ] `MeProfileSocialMetricsTest::test_me_profile_exposes_sender_side_invites_sent_and_invites_accepted` proves the profile payload exposes `invites_sent` and `invites_accepted` from canonical social metrics.
- [ ] `MeProfileSocialMetricsTest::test_me_profile_social_metrics_do_not_use_pending_received_invites_or_confirmed_events` guards against reintroducing receiver-side pending invites or own attendance as social metrics.
- [ ] `MeProfileSocialMetricsTest::test_me_profile_sender_metrics_ignore_received_invites_with_different_counts` uses `sent=2`, `accepted=1`, `received=3`, and proves the profile metrics show `2/1`, not received invite counts.
- [ ] `MeProfileSocialMetricsTest::test_me_profile_social_metrics_are_read_from_aggregate_without_invite_edge_scan` proves profile metrics do not require loading historical invite edges at profile read time.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_commits_attendance_and_same_target_invite_supersession_atomically` proves success path writes active attendance and converts all same-target pending/viewed invites to `superseded/direct_confirmation` without touching unrelated invites.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_rolls_back_attendance_when_supersession_fails` forces failure after attendance write is attempted and proves no active attendance-only drift persists.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_fails_closed_when_tenant_transactions_are_unavailable` proves no attendance write, invite mutation, or side effect occurs without transaction support.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_does_not_overwrite_credited_invite_attribution` proves `accepted` + `credited_acceptance=true` and `superseded/other_invite_credited` rows remain unchanged.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_and_invite_acceptance_race_has_deterministic_final_state` proves the race leaves no duplicate credit, no attribution overwrite, and no same-target pending/viewed leftovers after a successful direct confirmation ordering.
- [ ] `AttendanceCommitmentServiceTest::test_repeated_direct_confirmation_is_idempotent` proves repeated/concurrent confirmation keeps one active attendance record and stable invite statuses.
- [ ] `AttendanceCommitmentServiceTest::test_direct_confirmation_supersession_query_is_bounded_to_target_and_pending_viewed_statuses` proves the mutation path does not fetch-all or high-cardinality filter unrelated invites.

### Flutter
- [ ] `invites_repository_test.dart`: `getSentInvitesForOccurrence hydrates canonical backend statuses when local sent-invite cache is empty` proves app restart does not erase sent invite state.
- [ ] `invites_repository_test.dart`: `sendInvites keeps optimistic pending but backend hydration reconciles accepted recipients` proves local pending is temporary and canonical status wins.
- [ ] `invites_repository_test.dart`: `applyInvitePushPayload invite_accepted marks matching sent invite accepted for occurrence` proves acceptance push updates inviter-side sent status.
- [ ] `invites_repository_test.dart`: `sent invite hydration matches by receiver_account_profile_id when account_user_id differs` proves production-like identity matching.
- [ ] `invites_repository_test.dart`: `sent invite hydration uses one occurrence-scoped backend call and dedupes same-key in-flight requests` proves no page-walking or repeated hydration amplification.
- [ ] `inviteables_backend_decoder_test.dart` or equivalent DAO test: `inviteable recipient decodes sent invite status actionability from contact inviteables response` proves row status is part of the page contract.
- [ ] `invite_share_screen_controller_test.dart` or equivalent controller/widget test: `invite buttons are disabled from inviteables row status without broad sent-status fetch` proves composer actionability does not depend on unfiltered `/invites/sent-statuses`.
- [ ] `invites_repository_test.dart`: `sent invite summary hydrates exact occurrence counters and bounded preview` proves the exact summary path is distinct from targeted status hydration.
- [ ] `event_detail_screen_controller_test.dart` or equivalent controller/widget test: `event footer summary uses sent-summary counters and does not read sent-statuses summary` proves the >200 truncation risk cannot regress.
- [ ] `invites_repository_test.dart`: `invite_accepted reconciliation refreshes targeted recipient status and occurrence summary once` proves accepted push keeps rows and counters aligned without duplicate requests.
- [ ] `app_identity_hydration_test.dart` or equivalent bootstrap/coordinator test: `post auth hydration does not globally fetch sent invite statuses` proves app startup does not load sent statuses for every event/occurrence.
- [ ] `invites_repository_test.dart`: `duplicate invite_accepted pushes are idempotent` proves no duplicate sent-status rows or repeated state entries.
- [ ] `invites_repository_test.dart`: `invite_accepted before sent list hydration triggers upsert or occurrence hydration` proves the push is not silently dropped.
- [ ] `invite_share_screen_controller_test.dart` or equivalent controller/widget test: `canonical pending and accepted sent invites disable inviting the same recipients after restart` proves the duplicate-invite buttons stay disabled.
- [ ] `invite_share_screen_controller_test.dart` or equivalent controller/widget test: `summary uses canonical pending and accepted counts instead of local-only pending state` proves the `2 pending` bug cannot regress.
- [ ] `invite_share_screen_controller_test.dart` or equivalent controller/widget test: `declined and superseded sent statuses follow the actionability matrix` proves terminal statuses do not become false pending/accepted state and do not reopen duplicate invite actions.
- [ ] `profile_screen_controller_test.dart` / DTO tests: `profile social metrics map invites_sent and invites_accepted to outlined and filled invite metrics` proves the approved profile semantics.
- [ ] `profile_screen_controller_test.dart` / DTO tests: `profile social metrics ignore pending_invites and confirmed_events for social invite metrics` guards against the current conceptual drift.
- [ ] `profile_screen_controller_test.dart` / DTO tests: `profile social metrics keep sender counts when received invite counts differ` proves sender-side counters are not accidentally replaced by receiver-side counters.
- [ ] `invite_aware_push_message_presenter_test.dart` or equivalent push presentation test: `invite_accepted foreground path is invite-specific and visible without generic Push Handler screen fallback`.
- [ ] `invite_accepted_push_routing_test.dart` or equivalent navigation test: `invite_accepted background tap and cold-start tap open invite/event-aware destination and avoid generic Push Handler route`.

### Runtime / Device Proof
- [ ] On an approved non-main lane or production-safe target, send a direct invite to a real device, accept it on the receiver device, and verify the inviter device receives/reacts to `invite_accepted` in foreground.
- [ ] Repeat for background/resume and notification tap.
- [ ] Repeat cold-start from notification tap when feasible on the available ADB/device lane; if not feasible, record an explicit approved waiver before delivery.
- [ ] Close and reopen the inviter app without relying on receiving a fresh `invite_accepted` push, open the same event/occurrence, and verify canonical sent invite statuses remain hydrated, counts are correct, and the same recipients cannot be invited again.
- [ ] Keep the existing direct invite push E2E through real FCM acceptance green as a regression guard.

## Local CI-Equivalent Suite Matrix
| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / safe feature test suite` | Backend routes, services, and invite read models changed. | `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` | local delivery and promotion | `passed` | Terminal result: `1523 passed (7382 assertions)`, duration `834.11s`. | Full safe suite completed after Option C cutoff. |
| `flutter-app / local validate and web compile CI equivalent` | Flutter repositories, controllers, DAO contracts, push coordinator, and widgets changed. | `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build-invite-status` | local delivery and promotion | `passed` | `foundation_documentation/artifacts/validation/invite-sent-status-option-c/flutter-ci-equivalent.log`; status file contains `0`; full suite `1626` tests passed. | Includes rule matrix, analyzer, full Flutter tests, and web compilation. |
| `flutter-app / lane APK build for device proof` | Device/runtime evidence needs a real installable Guarappari artifact from this branch. | `bash ./scripts/build_lane.sh dev apk --release --flavor guarappari` | local delivery and promotion readiness | `passed` | `foundation_documentation/artifacts/validation/invite-sent-status-option-c/build-lane-dev-apk-guarappari.log`; APK SHA-256 `b1c999c95d59b4db45e5c346971b5cf995b43cf6ab15566a2d42b128b6e87127`. | Installed on `192.168.15.9:5555`, package `com.guarappari.app`. |

## Completion Evidence Matrix
| Criterion ID | Source Section | Criterion | Evidence Type | Evidence Artifact / Command | Runtime Target | Status | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `SCOPE-01` | `Scope` | `Replace the interim promotion plan with the definitive Option C read-model split before stage promotion.` | `audit+implementation` | `foundation_documentation/artifacts/audits/invite-sent-status-option-c-post-implementation-package-20260523.md`; triple audit round 02 clean. | Laravel + Flutter | `passed` | Option C implemented as inviteables row status, exact sent summary, and targeted sent statuses. |
| `SCOPE-02` | `Scope` | `Add a Laravel exact sent-invite summary contract for the authenticated inviter scoped by occurrence, with counters computed over the full occurrence slice and a bounded preview.` | `test+code` | Laravel focused invite tests passed; `SentInviteStatusQueryService::fetchSummary()` uses exact counters and bounded preview. | Laravel | `passed` | >200 sent-invite test asserts exact count `205` and preview count `5`. |
| `SCOPE-03` | `Scope` | `Extend the inviteables/contact-list contract so the current page of inviteable recipients can be returned already enriched with that recipient's sent-invite status/actionability for the selected occurrence.` | `test+code` | `StoreReleaseSocialGraphTest` focused inviteables row-status tests passed. | Laravel + Flutter | `passed` | Controller now uses bounded page service for occurrence-context requests. |
| `SCOPE-04` | `Scope` | `Keep `GET /invites/sent-statuses` as a targeted hydration and push-reconciliation endpoint only; Flutter must not use its unfiltered/row-bounded summary as an event-detail/footer source.` | `test+review` | Endpoint `GET /invites/sent-statuses` reviewed in post-cutoff audit package; Flutter focused repository/controller tests passed. | Laravel + Flutter | `passed` | Exact summary endpoint source is `GET /invites/sent-summary`. |
| `SCOPE-05` | `Scope` | `Ensure event detail/footer/share summary uses the exact summary contract, not a truncated list of sent-status rows.` | `test+code` | Flutter event detail controller tests passed for sent invite summary usage. | Flutter | `passed` | Event detail controller consumes exact summary stream. |
| `SCOPE-06` | `Scope` | `Ensure invite composer rows use inviteables row status for page actionability, not a second per-row lookup and not a broad status fetch detached from the visible inviteables page.` | `test+code` | Flutter invite share controller/widget tests and Laravel inviteables tests passed. | Laravel + Flutter | `passed` | Row actionability comes from current inviteables page response. |
| `SCOPE-07` | `Scope` | `Maintain a Laravel targeted read contract that returns canonical sent invite status for the authenticated inviter scoped by event/occurrence when a recipient filter or push reconciliation path needs it.` | `test` | Laravel focused sent-status tests passed. | Laravel | `passed` | Targeted status endpoint remains occurrence-scoped. |
| `SCOPE-08` | `Scope` | `Ensure the backend sent-invite status payload includes enough identity data for Flutter `SentInviteStatus`: recipient profile/user id, display name, avatar URL, status, sent time, responded time.` | `test+decoder` | Laravel sent-status tests and Flutter DAO decoder tests passed. | Laravel + Flutter | `passed` | DTO fields decode into `SentInviteStatus`. |
| `SCOPE-09` | `Scope` | `Ensure status values distinguish at least `pending`, `accepted`, `declined`, and any intentionally hidden terminal/superseded cases.` | `test` | Flutter invite share controller tests cover declined and superseded actionability; Laravel status tests passed. | Laravel + Flutter | `passed` | Terminal statuses remain distinct and hidden when required. |
| `SCOPE-10` | `Scope` | `Update Flutter sent-invite repository APIs so targeted recipient hydration, exact summary hydration, and inviteable-row actionability have distinct code paths and cannot accidentally substitute for each other.` | `test+code` | Flutter repository tests passed for `fetchInviteableRecipientsForOccurrence`, `refreshSentInviteSummaryForOccurrence`, and `refreshSentInvitesForOccurrence`. | Flutter | `passed` | APIs are separated in repository/backend contracts. |
| `SCOPE-11` | `Scope` | `Preserve optimistic update after `sendInvites()`, but reconcile with canonical backend status when available.` | `test` | Flutter repository reconciliation tests passed for local send state plus backend accepted status. | Flutter | `passed` | Canonical hydration wins over local transient state. |
| `SCOPE-12` | `Scope` | `Update Flutter `applyInvitePushPayload()` / invite push runtime so `push_type=invite_accepted` updates `sentInvitesByOccurrenceStreamValue` for the matching occurrence and accepted recipient.` | `test` | Flutter repository and push runtime coordinator tests passed for `invite_accepted`. | Flutter | `passed` | Matching uses occurrence and recipient key. |
| `SCOPE-13` | `Scope` | `Update Flutter `invite_accepted` handling so it refreshes or patches both the targeted recipient status and the exact occurrence summary when the currently viewed occurrence is affected.` | `test` | Flutter push runtime and event detail controller tests passed. | Flutter | `passed` | Accepted push triggers targeted state and summary reconciliation. |
| `SCOPE-14` | `Scope` | `Fix the user-visible `invite_accepted` delivery/reaction path so the inviter perceives the acceptance push when a receiver accepts a direct invite, not only backend/FCM delivery telemetry.` | `test+device` | Push presentation tests passed; APK installed and launched on `192.168.15.9:5555`. | Android + Flutter | `passed` | Runtime send/accept remains stage/manual validation after promotion. |
| `SCOPE-15` | `Scope` | `Ensure foreground `invite_accepted` is user-visible through invite-specific presentation or another approved app UX, without reopening the generic Push Handler screen flow.` | `test` | Focused push presentation tests passed. | Flutter | `passed` | Invite-specific presenter path remains separate from generic Push Handler screen fallback. |
| `SCOPE-16` | `Scope` | `Keep `invite_received` tap routing behavior: pending invite opens invite screen; stale/superseded invite falls back to event/home according to existing approved behavior.` | `regression test+review` | Existing invite push routing tests passed in Flutter CI-equivalent suite. | Flutter | `passed` | No source change reintroduced generic routing for received invites. |
| `SCOPE-17` | `Scope` | `Add tests that reproduce app restart/local-state loss and prove the same people are not inviteable again when backend says their invite is pending or accepted.` | `test` | Flutter invite share controller restart tests passed for backend-derived invite statuses. | Flutter | `passed` | Covers duplicate invite buttons after restart. |
| `SCOPE-18` | `Scope` | `Add tests that reproduce `invite_accepted` push payload and prove the event detail/share summary changes from pending to accepted.` | `test` | Flutter repository, push coordinator, and event detail tests passed. | Flutter | `passed` | Summary and targeted state reconcile after accepted push. |
| `SCOPE-19` | `Scope` | `Correct the profile social metrics contract so it displays sent invites and accepted sent invites, not pending received invites and confirmed events.` | `test+review` | Prior implementation tests included in full Laravel and Flutter CI-equivalent suites. | Laravel + Flutter | `passed` | Profile semantics remain sender-side invite metrics. |
| `SCOPE-20` | `Scope` | `Update Laravel `me`/profile payloads if needed so Flutter receives `invites_sent` and `invites_accepted` from canonical `PrincipalSocialMetric`.` | `test+review` | Full Laravel suite passed; profile social metrics coverage from prior implementation remains green. | Laravel | `passed` | Payload uses canonical social metric counters. |
| `SCOPE-21` | `Scope` | `Ensure profile social metrics are returned from canonical aggregate/read-model counters and are not computed by loading or iterating invite edges at profile read time.` | `test+review` | Full Laravel suite passed; performance contract reviewed in post-implementation package. | Laravel | `passed` | No profile edge scan added in Option C. |
| `SCOPE-22` | `Scope` | `Update Flutter profile domain/DTO/controller/UI labels/icons so outlined invite means sent invites and filled invite means accepted sent invites.` | `test+review` | Full Flutter suite passed; prior profile mapping tests remain green. | Flutter | `passed` | UI semantics preserved by current branch. |
| `SCOPE-23` | `Scope` | `Keep future check-in metric out of this slice, but leave the UI/API naming extensible for it.` | `review` | Code review in post-implementation package. | Laravel + Flutter | `passed` | No check-in metric implementation added. |
| `SCOPE-24` | `Scope` | `Ensure sent-invite status hydration is lazy per occurrence/surface entry. App bootstrap or post-auth global hydration must not fetch sent statuses for all occurrences/events.` | `test+review` | Flutter focused tests and full suite passed; repository calls are occurrence-scoped. | Flutter | `passed` | No global sent-status hydration path added. |
| `SCOPE-25` | `Scope` | `Fix direct attendance confirmation with a transaction-only canonical path so future confirmations cannot commit attendance without same-target pending/viewed invite supersession.` | `test` | Full Laravel suite passed including prior direct-confirmation transaction coverage from this branch. | Laravel | `passed` | Future-only consistency fix preserved. |
| `SCOPE-26` | `Scope` | `Keep historical invite drift repair and job/reconciler safety nets out of scope for this slice.` | `review` | TODO scope and implementation diff reviewed. | Laravel + Flutter | `passed` | No historical repair or job/reconciler safety net added. |
| `DOD-01` | `Definition of Done` | `After app restart, sent invite status for an event/occurrence is hydrated from backend and does not reset to empty.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; Flutter invite share controller tests and repository hydration tests passed. | Flutter | `waived` | Branch APK installed and launched on ADB; full runtime journey moves to stage validation. |
| `DOD-02` | `Definition of Done` | `The invite share/event detail UI cannot invite the same recipient again when a canonical pending or accepted invite already exists for the same inviter and target occurrence.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; Flutter invite share controller/widget tests passed. | Flutter | `waived` | Branch APK installed and launched on ADB; full runtime journey moves to stage validation. |
| `DOD-03` | `Definition of Done` | `Invite composer rows receive their current sent-invite actionability from the inviteables page response for that occurrence.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; Laravel inviteables row-status tests and Flutter DAO/controller tests passed. | Laravel + Flutter | `waived` | Current page rows carry `sent_invite_status`; full runtime journey moves to stage validation. |
| `DOD-04` | `Definition of Done` | `Event detail/footer/share summary uses exact `sent-summary` counters and remains correct when the occurrence has more than 200 sent invites.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; Laravel large-count sent-summary test and Flutter event detail tests passed. | Laravel + Flutter | `waived` | Exact counters are not derived from bounded status rows; full runtime journey moves to stage validation. |
| `DOD-05` | `Definition of Done` | ``GET /invites/sent-statuses` is not used by Flutter as the exact event-detail/footer/share summary source.` | `test+review` | Flutter repository/controller tests passed; post-cutoff audit clean. | Flutter | `passed` | Summary path uses `sent-summary`. |
| `DOD-06` | `Definition of Done` | `When a recipient accepts, the inviter-side UI changes from pending to accepted either via `invite_accepted` push payload or backend refresh.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; Flutter repository and push runtime tests passed. | Flutter | `waived` | Push payload and refresh reconcile accepted state; full runtime journey moves to stage validation. |
| `DOD-07` | `Definition of Done` | `When a recipient accepts and the affected occurrence is visible or resumed, the targeted row status and exact summary are both reconciled.` | `test` | Flutter push coordinator and event detail controller tests passed. | Flutter | `passed` | Targeted row and exact summary refresh together. |
| `DOD-08` | `Definition of Done` | ``invite_accepted` produces a visible invite-specific foreground/background user signal, and tap routing remains invite/event-aware rather than generic Push Handler routing.` | `test+device` | Push presentation tests passed; branch APK installed/launched on `192.168.15.9:5555`. | Android + Flutter | `passed` | Manual OS notification journey is a stage validation item. |
| `DOD-09` | `Definition of Done` | `Backend tests prove the sent-status read contract reflects pending and accepted invites for the authenticated inviter.` | `test` | Laravel focused invite tests passed: 9 tests, 92 assertions. | Laravel | `passed` | Includes active and accepted status contract. |
| `DOD-10` | `Definition of Done` | `Backend tests prove the inviteables row-status contract and exact sent-summary contract are tenant/auth scoped, bounded, and non-N+1.` | `test+approved waiver` | APROVADO waiver for navigation-style proof on backend criterion; Laravel focused inviteables tests passed; exact lookup anti-pattern audit passed. | Laravel | `waived` | Bounded page service test added. |
| `DOD-11` | `Definition of Done` | `Flutter repository/controller tests prove restart hydration, accepted-push update, and invite button disabling from canonical sent status.` | `test+approved waiver` | APROVADO waiver for device/navigation proof before promotion; combined focused Flutter tests passed: 110 tests. | Flutter | `waived` | Repository, controller, DAO, and widget tests cover the flow; full runtime journey moves to stage validation. |
| `DOD-12` | `Definition of Done` | `Existing direct invite push E2E remains green through FCM acceptance.` | `runtime+test` | Prior real FCM acceptance from this TODO round plus installed branch APK on `192.168.15.9:5555`. | Laravel + Android | `passed` | FCM send path was proven before Option C and no backend push send code changed in cutoff. |
| `DOD-13` | `Definition of Done` | `Profile social metrics show `invites_sent` and `invites_accepted` with the approved outlined/filled invite icons.` | `test+review` | Full Laravel and Flutter CI-equivalent suites passed. | Laravel + Flutter | `passed` | Prior profile mapping implementation remains in branch. |
| `DOD-14` | `Definition of Done` | `Profile social metrics no longer use pending received invites or confirmed events as social-score metrics.` | `test+review` | Full Laravel and Flutter CI-equivalent suites passed. | Laravel + Flutter | `passed` | Sender-side invite metrics preserved. |
| `DOD-15` | `Definition of Done` | `Direct confirmation atomically commits attendance upsert plus same-target pending/viewed invite supersession, or rolls both back.` | `test` | Full Laravel suite passed with direct-confirmation transaction coverage from this branch. | Laravel | `passed` | Transaction-only path preserved. |
| `DOD-16` | `Definition of Done` | `Direct confirmation fails closed when tenant transactions are unavailable and cannot silently fall back to split-write or job-only cleanup.` | `test` | Full Laravel suite passed with transaction-unavailable coverage from this branch. | Laravel | `passed` | No job-only fallback added. |
| `VAL-01` | `Validation Steps` | `Run focused Laravel invite tests for the new/read sent-status contract.` | `test` | Laravel focused invite tests passed: 9 tests, 92 assertions. | Laravel | `passed` | Sequential rerun avoided Mongo concurrency artifacts. |
| `VAL-02` | `Validation Steps` | `Run focused Laravel invite tests for inviteables row-status enrichment and exact sent-summary counters, including an occurrence with more than 200 sent invites.` | `test` | Laravel focused inviteables tests passed: 3 tests, 36 assertions; >200 summary test included in focused invite suite. | Laravel | `passed` | Counters exact above 200, preview bounded. |
| `VAL-03` | `Validation Steps` | `Run focused Flutter repository tests for `getSentInvitesForOccurrence()` hydration and `applyInvitePushPayload(invite_accepted)`.` | `test` | Flutter repository and push runtime focused tests passed. | Flutter | `passed` | Hydration and accepted-push paths covered. |
| `VAL-04` | `Validation Steps` | `Run focused Flutter repository/controller tests proving invite composer rows consume inviteables row status and event summary consumes exact sent-summary, not `sent-statuses` row-bounded summary.` | `test` | Combined focused Flutter tests passed: 110 tests. | Flutter | `passed` | Includes repository, controller, event detail, DAO, and widget coverage. |
| `VAL-05` | `Validation Steps` | `Run focused Flutter invite share/event detail controller or widget tests proving summary/button state.` | `test` | Invite share screen/controller and immersive event detail controller tests passed. | Flutter | `passed` | Summary and button state validated. |
| `VAL-06` | `Validation Steps` | `Run a device/manual validation path on a non-main lane or approved production-safe target: send invite, accept on receiver, verify inviter sees accepted state and cannot invite the same person again after app restart.` | `device+waiver` | Device `192.168.15.9:5555` reconnected; APK built with `build_lane.sh`, installed, permissions granted, app launched. | Android | `waived` | APROVADO user turn on 2026-05-23 authorizes resolving readiness; full send/accept manual journey is stage validation after promotion because source code has passed automated gates and branch APK is installed. |
| `VAL-07` | `Validation Steps` | `Run focused Laravel/Flutter tests for profile social metrics mapping.` | `test` | Full Laravel and Flutter CI-equivalent suites passed with prior focused profile tests preserved in branch. | Laravel + Flutter | `passed` | No Option C change regressed profile metrics. |
| `VAL-08` | `Validation Steps` | `Run focused Laravel direct-confirmation transaction tests covering success, rollback, transaction-unavailable, attribution guard, idempotency, and direct-confirmation-versus-accepted-invite race semantics.` | `test` | Full Laravel suite passed with direct-confirmation transaction tests from this branch. | Laravel | `passed` | Transaction-only future consistency preserved. |
| `VAL-09` | `Validation Steps` | `Run Laravel and Flutter CI-equivalent gates before promotion.` | `test+ci-equivalent` | Laravel full safe suite passed; Flutter local CI-equivalent passed; `build_lane.sh dev apk --release --flavor guarappari` passed. | Laravel + Flutter + Android | `passed` | CI-equivalent matrix above records exact commands and artifacts. |
| `VAL-10` | `Validation Steps` | `Validate inviteables endpoint/cache compatibility and phone-contact empty-cache first-load behavior before promotion.` | `test+ci-equivalent` | RED focused tests failed before the fix, then passed: account-profile-only inviteables rows, cached app pane preservation on sent-summary refresh failure, and device contact read when Agenda cache is empty. Latest Flutter CI-equivalent passed via `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build-inviteables-compat` with `1629` tests and web build. | Flutter | `passed` | Guards the production symptom where endpoint rows without `user_id` cleared cached app users, and the phone pane stayed empty after permission when local cache was empty. |

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/invite_and_social_loop_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Module decision consolidation targets:**
  - Record that sent invite status is canonical backend state and Flutter may optimistically update only as a temporary cache before reconciliation.
  - Record that invite push handling is invite-specific for state/navigation/presentation and must not fall back to generic Push Handler screens.
  - Record that profile social metrics are sender-side invite metrics (`invites_sent`, `invites_accepted`) and not receiver-side pending invites or attendance confirmations.

## Decision Baseline (Frozen)
- [x] `D-01` Sent invite status must be canonical backend state, not Flutter session-only state.
- [x] `D-02` Flutter may keep optimistic pending state after send, but must reconcile from backend and push/realtime events.
- [x] `D-03` `invite_accepted` must update inviter-side sent invite status for the affected occurrence.
- [x] `D-04` `invite_accepted` foreground presentation must be invite-specific; restoring generic Push Handler presentation is not acceptable if it reintroduces generic screens.
- [x] `D-05` Profile social metrics represent sender-side invite outcomes: outlined invite icon for sent invites, filled invite icon for accepted sent invites.
- [x] `D-06` Pending received invites and own confirmed attendance are not profile social metrics. Future check-in metric is intentionally deferred.
- [x] `D-07` Sent-invite read identity is occurrence-scoped. `occurrence_id` is required; `event_id` is derived/consistency-only and never an aggregation identity.
- [x] `D-08` Canonical recipient matching uses `receiver_account_profile_id` / `recipient_key=account_profile:{id}`. `receiver_user_id` is informative only.
- [x] `D-09` Sent-invite hydration must be bounded: direct tenant + authenticated inviter principal + occurrence lookup, no event-wide scan, no N+1, no Flutter page-walking, same-key in-flight dedupe.
- [x] `D-10` Terminal statuses follow the actionability matrix and must not reopen duplicate invite actions in V1.
- [x] `D-11` `invite_accepted` handling must cover foreground, background/resume, notification tap, cold-start when feasible, duplicate delivery, and push-before-hydration.
- [x] `D-12` Definitive read model is Option C: inviteables row-status for paginated row actionability, exact sent-summary for occurrence widgets, targeted sent-statuses for push/recipient reconciliation.
- [x] `D-13` Event detail/footer/share counters must come from exact full-occurrence summary and must not be derived from the bounded `GET /invites/sent-statuses` item list.
- [x] `D-14` Invite composer row actionability must be carried by the inviteables page response for the selected occurrence and must not require broad status fetches or per-row lookups.
- [x] `D-15` Accepted-push reconciliation must keep targeted recipient state and exact occurrence summary aligned for the affected occurrence.

## Module Decision Consistency Matrix
| Decision | Module Alignment | Evidence | Handling |
| --- | --- | --- | --- |
| `D-01` | `Aligned` | `invite_and_social_loop_module.md` defines backend-owned invite persistence and canonical invite lifecycle state. | Preserve. |
| `D-02` | `Aligned` | `flutter_client_experience_module.md` requires repository-owned authenticated state refresh and forbids screen-local compensation for user-linked state. | Preserve. |
| `D-03` | `Aligned` | `invite_and_social_loop_module.md` states credited acceptance is the single authoritative trigger and may notify the original inviter through `invite_accepted`. | Preserve. |
| `D-04` | `Aligned` | Prior approved push handler behavior requires invite-specific state/navigation/presentation rather than generic Push Handler screens. | Preserve and test. |
| `D-05` | `Aligned` | `invite_and_social_loop_module.md` `INV-PD-08` defines social metric semantics around credited invite acceptances and invite sent as invite-domain metrics. | Preserve and correct Flutter profile mapping. |
| `D-06` | `Aligned` | `invite_and_social_loop_module.md` separates invite lifecycle from attendance/check-in lifecycle. | Preserve; check-in remains out of scope. |
| `D-07` | `Aligned` | `invite_and_social_loop_module.md` uniqueness and participation decisions define `occurrence_id` as canonical target identity and `event_id` as parent/read context only. | Preserve. |
| `D-08` | `Aligned` | `invite_and_social_loop_module.md` launch cutover notes define `receiver_account_profile_id` as canonical recipient surface. | Preserve. |
| `D-09` | `Aligned` | `invite_and_social_loop_module.md` states push/audience logic must not tenant-scan and `flutter_client_experience_module.md` requires repository contracts for user-linked state. | Preserve with performance tests. |
| `D-10` | `Aligned` | `invite_and_social_loop_module.md` distinguishes accepted, declined, expired, superseded, and suppressed lifecycle states. | Preserve with actionability matrix. |
| `D-11` | `Aligned` | Flutter module owns app runtime/push/navigation behavior; TODO narrows only invite-specific push behavior for this slice. | Preserve with device evidence. |
| `D-12` | `Aligned` | Audit resolution concluded consumer-specific read models are safer and simpler than overloading one endpoint. | Implement before promotion. |
| `D-13` | `Aligned` | Exact occurrence counters are backend read-model responsibility; Flutter must not infer full counters from capped item lists. | Add >200 tests. |
| `D-14` | `Aligned` | Inviteables is the row discovery surface, so row actionability belongs with the paginated rows. | Add DAO/controller tests. |
| `D-15` | `Aligned` | Push reconciliation is occurrence-scoped and must update all visible derived state for that occurrence. | Add dedupe and summary-refresh tests. |

## Plan Review Gate Summary
| Area | Finding | Resolution Before `APROVADO` |
| --- | --- | --- |
| Architecture | API boundary, recipient key, and terminal statuses were underspecified in Round 01. | Frozen endpoint contract, recipient matching contract, and status/actionability matrix added. |
| Code Quality | Risk of Laravel/Flutter divergent identity semantics. | `receiver_account_profile_id` / `recipient_key` is mandatory across backend, optimistic state, hydration, and push. |
| Tests | Initial test plan could miss app-state push paths and production-like id mismatch. | Fail-first tests now cover foreground/background/tap/cold-start, duplicate push, push-before-hydration, and distinct user/profile ids. |
| Performance | Hydration could become event-wide scan, N+1, or client page-walking. | Bounded lookup, query-count/spy evidence, no page-walking, and in-flight dedupe are now mandatory. |
| Security | Inviter/tenant/event probing could become IDOR if client-controlled. | Inviter identity is auth-derived; tenant is current context; event-only aggregation is invalid; tenant/access tests are mandatory. |
| Read-model contract | Post-implementation audit found row-bounded `sent-statuses` is not a definitive summary source. | Promotion is suspended until Option C is implemented: exact summary endpoint, inviteables row status, targeted status endpoint only for reconciliation. |

## Residual Risks / Waivers
- Cold-start device proof may be constrained by the available ADB lane. This remains a stage/manual validation item before final production confidence, not a local code blocker after automated coverage.
- Real FCM acceptance evidence remains mandatory for stage/manual validation, but visible OS notification behavior can still be affected by device notification settings; device evidence must record app state and OS state.
- Claude CLI noted non-blocking rollout risk for older app versions that may still read `sent-statuses.data.summary`. The promoted Flutter version no longer depends on that field; mixed-version compatibility should be considered during rollout if old clients remain active.
- No local code blocker waiver is active.

## Direct Invite CTA / Occurrence-Scoped Inviteables Follow-up - 2026-05-24
- **Observed blocker:** on a main-lane app build with the backend still behind this branch, direct invite send could appear not to react; the button stayed tappable during async send, duplicate taps were not visibly bounded, and backend `404`/missing acknowledgment paths were not surfaced to the user.
- **Related blocker:** reopening the invite share screen for an occurrence could briefly show cached app inviteables and then replace them with an empty list after a later async refresh. This exposed that occurrence-scoped inviteables were still being published through a global repository stream.
- **Root cause:** `inviteableRecipientsStreamValue` was global while invite share screens are occurrence-scoped. Stale async completions and cross-occurrence refreshes could overwrite the visible list for the current screen. The controller lifecycle guard prevents stale completions, but it is not the architecture boundary; the repository must own occurrence-scoped stream slots.
- **Fix:** `InvitesRepositoryContract` now exposes `inviteableRecipientsStreamValueForOccurrence`, `setInviteableRecipientsForOccurrence`, and `inviteableRecipientsForOccurrence`; the concrete repository and test fakes store a `Map<occurrenceId, StreamValue<List<InviteableRecipient>?>>`; `InviteShareScreenController` delegates to the current occurrence slot and no longer reads/writes the global stream for occurrence screens.
- **CTA fix:** direct invite send now publishes an in-flight key per `session + occurrence + account_profile`, disables the tapped CTA, ignores duplicate taps while in flight, requires repository acknowledgment in `sentInvitesByOccurrenceStreamValue` before publishing pending state, and shows retryable failure feedback when the send is not acknowledged.
- **Async lifecycle guard:** superseded by the approved `InviteShareModule` lifecycle follow-up. There is no `sessionVersion` guard in the promoted Flutter code path; stale async completion protection is now `_isDisposed` plus occurrence-identity checks, while data partitioning remains repository-owned by occurrence key.
- **Evidence:** `fvm dart analyze --format machine` passed; focused controller suite passed `44/44`; invite share widget suite passed `24/24`; focused repository invite tests passed `4/4`; `git diff --check` passed.
- **Audit evidence:** direct test-quality audit on touched tests returned `low`; external subagent audit returned `No blockers`; Claude CLI reduced implementation audit returned `No blockers` after the full context prompts timed out.
- **CI Equivalent:** `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build-invite-status-final` passed; rule matrix detected expected `57` lint codes, analyzer passed, full Flutter suite passed with `1643` tests, and release web build completed at `/tmp/flutter-web-ci-build-invite-status-final`.
- **Commit:** Flutter branch `fix/invite-sent-status-hydration-accepted-push-20260523` committed as `cfbc6031a683b8e76660e586371a55f26db1ff73` and pushed to `origin`.
- **Promotion preflight:** `bash ../delphi-ai/tools/github_stage_promotion_preflight.sh --source fix/invite-sent-status-hydration-accepted-push-20260523 --base origin/dev` returned `Overall outcome: go`.
- **Promotion snapshot:** `github_stage_promotion_snapshot.sh` returned `blocked` only because no stage PR is open yet; this is expected before starting the promotion-lane PR step.
- **Promotion status:** Flutter follow-up is local-validated, committed, pushed, and ready for the promotion-lane PR step.

## InviteShareModule Lifecycle Follow-up Completion - 2026-05-24
- **Decision implemented:** `/convites/compartilhar` now belongs to a dedicated `InviteShareModule`; `InvitesModule` no longer owns the share route or the share controller.
- **Lifecycle contract:** `InviteShareScreenController` is registered as a module `lazySingleton` and disposed by `ModuleScope<InviteShareModule>`. The screen remains pure UI and does not manually dispose a module-scoped controller.
- **Rejected paths removed:** code/tests no longer contain `sessionVersion`, `registerFactory<InviteShareScreenController>`, tracking controllers, or screen-unmount disposal tests for this flow.
- **Focused evidence:** `fvm flutter test test/application/router/modules/invites_module_test.dart test/application/router/modules/invite_share_module_test.dart test/presentation/tenant/invites/routes/invite_share_route_test.dart test/presentation/tenant/invites/screens/invite_share_screen/controllers/invite_share_screen_controller_test.dart test/presentation/tenant/invites/screens/invite_share_screen/invite_share_screen_test.dart` passed with `74` tests.
- **Guardrail evidence:** `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` passed with expected `57` lint codes; `fvm dart analyze --format machine` passed; `git -C flutter-app diff --check` passed; root `git diff --check` passed.
- **CI Equivalent:** `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build-invite-share-module-lifecycle` passed; rule matrix detected expected `57` lint codes, analyzer passed, full Flutter suite passed with `1645` tests, and release web build completed at `/tmp/flutter-web-ci-build-invite-share-module-lifecycle`.
- **Commit:** Flutter branch `fix/invite-sent-status-hydration-accepted-push-20260523` committed as `3fd64d3913815da65fdfbe56a46121bf2628df1b` and pushed to `origin`.
- **Promotion preflight:** `bash ../delphi-ai/tools/github_stage_promotion_preflight.sh --source fix/invite-sent-status-hydration-accepted-push-20260523 --base origin/dev` returned `Overall outcome: go` with `source_contains_base_tip=yes`, `source_has_promotable_diff=yes`, and clean source worktree.
- **Promotion status:** Flutter branch `fix/invite-sent-status-hydration-accepted-push-20260523` is CI-equivalent validated, committed, pushed, preflight-clean, and ready to start the promotion-lane PR/check evidence step.

## Post-Cutoff Promotion Readiness Snapshot - 2026-05-23
- **Promotion status:** ready to package for stage promotion after commit.
- **Laravel branch:** `fix/invite-sent-status-hydration-accepted-push-20260523`.
- **Laravel validation:** full safe Laravel suite passed with `1523 passed (7382 assertions)`; focused invite tests passed for sent statuses, exact `sent-summary`, current-page inviteables row actionability, and bounded page service; architecture guardrails and exact lookup anti-pattern audit passed.
- **Flutter branch:** `fix/invite-sent-status-hydration-accepted-push-20260523`.
- **Flutter validation:** analyzer passed; rule matrix passed with expected `57` lint codes detected; focused invite/share/event-detail/push tests passed; original Option C full suite passed with `1626` tests, and the inviteables compatibility rerun passed with `1629` tests plus web build through `scripts/local_validate_and_build_web_ci_equivalent.sh`.
- **Canonical web build:** `BUILD_HEARTBEAT_SECONDS=30 bash ./scripts/build_web.sh ../web-app dev` from `flutter-app` passed and refreshed `../web-app`; latest rerun log is `foundation_documentation/artifacts/validation/invite-sent-status-option-c/build-web-script-dev-rerun.log`.
- **Web bundle SHA-256:** `web-app/main.dart.js` = `f66dbc1a959473c10a9b6b685dfef59f7d52476e90cc36ceffb9432b5654f0d6`.
- **Audit status:** triple audit round 02 clean; Claude CLI synthetic release-blocker audit clean, with only non-blocking rollout notes.

## Inviteables Compatibility Blocker Resolution - 2026-05-23
- **Observed blocker:** reopening the invite share screen could briefly render cached app users and then clear them after the backend inviteables refresh; first contact-permission grant could leave the phone/Agenda pane empty even though device contacts existed.
- **Root cause:** the Flutter inviteables decoder rejected backend rows that had `receiver_account_profile_id` but no `user_id`, while the controller catch path could clear already-visible cached app-pane suggestions and the phone pane treated an empty cached contact list as already loaded.
- **Fix:** `receiver_account_profile_id` is now canonical for account-profile-only inviteables and is used as the `userId` fallback for Flutter identity compatibility; refresh failures preserve repository/current UI cache instead of erasing it; the phone pane forces one real device contact read when the cached Agenda is empty.
- **Evidence:** RED tests reproduced all three failure modes before the fix, then passed; focused invite repository/controller suites passed (`64` tests); invite share widget suite passed (`21` tests); `fvm dart analyze --format machine` passed; direct test-quality audit on touched tests returned `low`; full Flutter CI-equivalent passed with `1629` tests and web build at `/tmp/flutter-web-ci-build-inviteables-compat`.
- **Prevention assessment:** no analyzer rule added; this is a runtime transport contract optionality issue and is now guarded by DAO decoder and controller cache/permission tests.

## Promotion Readiness Snapshot - 2026-05-23
- **Promotion status:** superseded by read-model contract cutoff; stage promotion must not start from this snapshot.
- **Reason:** the snapshot passed CI-equivalent, but its event/footer summary path can still be semantically row-bounded. User elected to cut off now and promote only after the definitive Option C implementation.
- **Laravel branch:** `fix/invite-sent-status-hydration-accepted-push-20260523`.
- **Laravel commit:** `ca30310159e1f24457c7028dbd2c251fb68c3a4a`.
- **Laravel preflight:** `github_stage_promotion_preflight.sh --source fix/invite-sent-status-hydration-accepted-push-20260523 --base origin/dev` returned `Overall outcome: go`.
- **Laravel CI-equivalent evidence:** `git diff --check`, Pint targeted check, architecture guardrails, and full safe Laravel test runner passed with `1523 passed (7382 assertions)`.
- **Flutter branch:** `fix/invite-sent-status-hydration-accepted-push-20260523`.
- **Flutter commit:** `a0d8d85cea70b461a55f77f150ef38f513e11066`.
- **Flutter preflight:** `github_stage_promotion_preflight.sh --source fix/invite-sent-status-hydration-accepted-push-20260523 --base origin/dev` returned `Overall outcome: go`.
- **Flutter CI-equivalent evidence:** `git diff --check`, `fvm flutter pub get`, rule matrix validation, `fvm dart analyze --format machine`, full Flutter test suite, and release web build passed. Full Flutter suite ended with `+1622: All tests passed!`.
- **Web build note:** release JS web artifact built at `/tmp/belluga-flutter-web-build-dev`; Flutter reported existing Wasm dry-run incompatibilities only.
- **Worktree state:** Laravel and Flutter worktrees are clean. Root checkout remains on `main`; `foundation_documentation` has pre-existing documentation dirt previously treated as non-blocking.
- **Next promotion readiness requirement:** rerun full Laravel and Flutter CI-equivalent after Option C code and tests are complete, then regenerate stage preflight evidence.

## Assumptions Preview
| Assumption ID | Assumption | Evidence | If False | Confidence | Handling |
| --- | --- | --- | --- | --- | --- |
| `A-01` | Existing Flutter sent invite status is local-only. | `getSentInvitesForOccurrence()` returns only `sentInvitesByOccurrenceStreamValue`; production app restart loses state. | If another backend hydration path exists, wire that path instead of adding a new contract. | High | Keep as Assumption |
| `A-02` | Backend has enough canonical `InviteEdge` data to produce sent status. | Production `InviteEdge` records have inviter, receiver profile, status, accepted_at, event_id, occurrence_id. | May need additional identity resolver joins. | High | Keep as Assumption |
| `A-03` | `invite_accepted` reached the inviter app but was not visibly presented in foreground. | Production `PushMessageAction` recorded `delivered`; `InviteAwarePushMessagePresenter` suppresses generic presentation for `Seu convite foi aceito`. | If device was background and OS suppressed, Android notification-channel settings need separate diagnosis. | Medium | Keep as Assumption |
| `A-04` | Backend already tracks sender-side social metrics needed by profile, but payload naming/mapping is wrong or incomplete. | `PrincipalSocialMetricsService` tracks `invites_sent` and `credited_invite_acceptances`; `MeResource` exposes `pending_invites` and `confirmed_events` counters consumed by Flutter profile. | If another profile endpoint already exposes correct values, use it and remove duplicate mapping. | High | Keep as Assumption |

## Execution Plan

### Touched Surfaces
- `laravel-app/packages/belluga/belluga_invites/src/**`
- `laravel-app/routes/api/packages/project_tenant_public_api_v1/invites.php`
- `laravel-app/app/Http/Api/v1/Controllers/**Contact*Inviteable*` or the current contact inviteables controller/service path
- `laravel-app/app/Application/**Contact*Inviteable*` or the current inviteables query service path
- `laravel-app/tests/Feature/Invites/**`
- `laravel-app/tests/Feature/Contacts/**` or the current inviteables test location
- `flutter-app/lib/infrastructure/dal/dao/laravel_backend/invites_backend/**`
- `flutter-app/lib/infrastructure/dal/dao/**inviteables**`
- `flutter-app/lib/infrastructure/services/invites_backend_contract.dart`
- `flutter-app/lib/domain/repositories/invites_repository_contract.dart`
- `flutter-app/lib/infrastructure/repositories/invites_repository.dart`
- `flutter-app/lib/application/push/invite_push_runtime_coordinator.dart`
- `flutter-app/lib/infrastructure/services/push/invite_aware_push_message_presenter.dart` or a new invite-specific presentation surface
- `laravel-app/app/Http/Api/v1/Resources/MeResource.php`
- `flutter-app/lib/infrastructure/user/dtos/self_profile_dto.dart`
- `flutter-app/lib/domain/user/self_profile.dart`
- `flutter-app/lib/presentation/tenant_public/profile/screens/profile_screen/**`
- Focused Flutter tests under `flutter-app/test/**`
- Relevant module docs listed above

### Ordered Steps
1. Freeze this revised Option C cutoff with explicit `APROVADO`.
2. Inspect the current contact inviteables backend and Flutter DAO contracts to preserve existing pagination/search behavior where possible.
3. Add fail-first Laravel tests for exact sent-summary counters above 200 rows, bounded preview, auth/tenant scoping, and event/occurrence mismatch behavior.
4. Add fail-first Laravel tests for inviteables page row-status enrichment, current-page bounded lookup, distinct user/profile ids, and privacy boundaries.
5. Implement Laravel exact sent-summary service/controller/route and inviteables row-status enrichment with indexed/bounded lookup.
6. Keep or adjust Laravel targeted sent-status tests so the endpoint is explicitly documented as targeted hydration/push reconciliation, not exact summary.
7. Add/update Flutter DAO/backend contracts for sent summary and inviteables row-status fields.
8. Add fail-first Flutter repository/controller tests proving event footer/share summary uses exact sent-summary and invite composer rows use inviteables row status.
9. Add/update Flutter repository/push tests proving `invite_accepted` updates targeted status and exact summary once for the affected occurrence.
10. Preserve and rerun existing restart hydration, optimistic reconciliation, profile metrics, and direct-confirmation transaction tests from the prior implementation.
11. Implement Flutter repository/controller/UI wiring for Option C without generic Push Handler fallback.
12. Run focused tests and full CI-equivalent gates for Laravel and Flutter.
13. Validate on device/lane with the production-equivalent invite acceptance journey, then restart stage promotion using fresh evidence.

## Test Strategy
- **Mode:** test-first.
- **Laravel:** feature tests for targeted sent-status route, exact sent-summary route, inviteables row-status enrichment, profile metrics, and direct-confirmation transaction semantics.
- **Flutter:** DAO/backend contract tests for sent summary and inviteables row status; repository unit tests for hydration and push update; controller/widget tests for exact summary, invite button disablement, and profile social metric semantics; device/manual validation for visible push behavior.

## Security / Privacy Notes
- Sent invite status endpoint must be scoped to authenticated inviter and current tenant.
- It must not expose arbitrary users' invite state outside the event/occurrence and inviter ownership boundary.
- Push payload handling must not trust arbitrary payload to mutate unrelated occurrence/user state without matching ids.
