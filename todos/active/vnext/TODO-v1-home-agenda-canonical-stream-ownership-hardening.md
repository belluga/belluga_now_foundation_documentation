# TODO (V1): Home Agenda Canonical Stream Ownership Hardening

**Status:** Active
**Primary Module Anchor:** `foundation_documentation/modules/tenant_home_composer_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/agenda_and_action_planner_module.md`
**Complexity:** medium
**Checkpoint Policy:** one architecture checkpoint before final validation
**Next exact step:** Freeze the Home/Agenda controller-boundary rules (widget-controller privacy, no controller-to-controller dependencies, repository-owned shared settings, single scroll truth), then choose whether Home radius/compact behavior remains widget-local or must be promoted before structural refactor starts.

## 1. Context
Home agenda is intermittently losing events after cross-surface navigation, especially after Map interactions and repeated re-entry. Evidence review showed that the current runtime architecture allows the Home controller to mix three concerns that must stay separate:
- canonical backend-backed agenda state,
- controller-local projection/filtering,
- global origin/radius side effects shared with other surfaces.

The current model is overcomplicated and violates single-writer ownership. The repository exposes both a canonical cache snapshot and a second stream that the controller rewrites for local invite filtering. The same repository also exposes shared paged scratch state consumed by multiple controllers. This makes the Home agenda vulnerable to cross-controller interference and global-origin drift.

This TODO simplifies the lane back to the intended model:
- one canonical Home agenda stream, owned by the repository,
- only repository methods fed by backend results may write that canonical stream,
- controller-local filters must stay local and never mutate repository-owned event streams,
- cross-surface scratch pagination must not be shared as if it were canonical state,
- query result envelopes/helpers must not leak raw-construction shortcuts outside repository ownership.

Follow-up intake added on `2026-04-08`: changing the radius from the Home bottom sheet must visibly reconcile through the same repository-backed Home refresh path, and the radius control itself must enter a loading state while that refresh is unsettled.

## 2. Scope
In scope:
- `lib/application/router/modular_app/modules/home_module.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/tenant_home_screen.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/controllers/tenant_home_controller.dart`
- `lib/domain/repositories/schedule_repository_contract.dart`
- `lib/infrastructure/repositories/schedule_repository.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_section.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_body.dart`
- `lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/home_agenda_app_bar.dart`
- `lib/presentation/tenant_public/widgets/date_grouped_event_list.dart`
- `lib/presentation/tenant_public/schedule/screens/event_search_screen/controllers/event_search_screen_controller.dart`
- `tool/belluga_analysis_plugin/docs/rules.md`
- `tool/belluga_analysis_plugin/**`
- any directly affected home-agenda tests
- focused doc sync for Home/Agenda module contracts

Out of scope:
- broader VNext proximity-preference redesign
- event-search UX redesign
- backend/API schema changes
- map POI query behavior except where it indirectly mutates Home global origin semantics

## 3. Decision Baseline (Frozen)
- `D-01` Home agenda must have exactly one repository-owned canonical event stream for the Home aggregate. Duplicate “canonical vs cached” stream surfaces are forbidden.
- `D-02` Only repository methods whose source of truth is backend data may write the canonical Home agenda stream.
- `D-03` Controllers must never write filtered/projected event lists back into repository-owned canonical streams.
- `D-04` Invite/confirmed filtering is a controller-local projection layered on top of the canonical Home agenda stream and the canonical invite/confirmed streams; it must not mutate the event source.
- `D-05` Home agenda cache, if retained, is an implementation detail of the repository and must not create a second public source of truth or a public cache snapshot type for the same aggregate.
- `D-06` Shared paged scratch state (`pagedEventsStreamValue`, page counters, transient page results) must not be used as the canonical reconstruction source for Home agenda snapshots when multiple controllers can issue different queries.
- `D-07` Home controller restore/re-entry behavior may depend on repository-owned canonical Home agenda state plus its own local UI filters only; it must not reconstruct truth from another controller’s scratch query state.
- `D-08` Global location-origin persistence must not silently invalidate Home agenda semantics without an explicit, controller-owned reconciliation path that preserves the Home aggregate contract.
- `D-09` If a surface wants to change the Home agenda’s canonical events, it must do so through the backend-backed repository refresh path for the Home query, never by directly publishing local lists.
- `D-10` Query/read-model page envelopes coming from backend pagination are repository-internal only. Non-repository layers may receive already-materialized domain items, but they must not receive or fabricate repository pagination envelopes (`has_more`, cursor wrappers, `fromRaw` helpers).
- `D-11` Controllers must not accept `StreamValue` parameters. Stream mutation must stay explicit at controller-owned field call sites or semantic setters so ownership remains visible and delegated streams cannot be mutated through generic helpers.
- `D-12` Repository contracts must not expose raw pagination controls (`page`, `pageSize`, `cursor`, `limit`, etc.), delegated pagination state (`hasMore*`), or default helper logic that knows backend page sizes. If pagination exists, only repository implementations may own the bookkeeping and any helper/result types.
- `D-13` Repository-owned pagination helpers must remain private to the repository implementation. They must not be delegated through public helper methods, support abstractions, overridable hooks, or test doubles that expose page slicing semantics outside the repository boundary.
- `D-14` Home radius changes are controller-owned intents that must settle through the canonical repository-backed Home refresh path before the radius interaction is considered complete.
- `D-15` While a Home radius-triggered refresh is in flight, the radius control must expose explicit loading feedback instead of appearing idle.
- `D-16` Widget controllers are private to the widget that owns them and its subtree. Parent screens, sibling widgets, and other controllers must not resolve, import, or coordinate widget-controller state.
- `D-17` Controllers must never depend on other controllers for shared state, orchestration, or mutation. Shared state flows through repository contracts only.
- `D-18` If a setting is shared, persisted, or needed by more than one controller/surface, it is repository-owned and published as a repository `StreamValue`; no controller may act as relay for that setting.
- `D-19` Widget-local state may stay in a widget controller only while it remains internal to that widget boundary. Once screen-level coordination is required, the state must be promoted to the screen controller or to the repository according to ownership semantics.
- `D-20` Scroll-derived behavior must observe the same scroll source that moves the real list/content. Deriving compact/pagination/reset behaviors from a different controller or proxy signal is forbidden.
- `D-21` Borrowed UI controllers (`ScrollController`, `TextEditingController`, `FocusNode`, etc.) passed into a widget are owned by the caller. The receiving widget/controller must not dispose them or create a competing controller for the same behavior.

## 4. Objective Rules Extracted From This Bug
- `R-01` One aggregate, one public canonical `StreamValue`.
- `R-02` Repository-owned canonical streams are single-writer; the writer is the repository, not controllers/widgets.
- `R-03` Controller-local filtering/projection state must terminate in controller-local streams, never in repository-owned canonical streams.
- `R-04` Cache is not a second public truth. Repository cache/query state may exist only as private implementation state; public `*CacheSnapshot` wrappers for the Schedule/Home/Agenda lane are forbidden.
- `R-05` Scratch pagination state is query-scoped and ephemeral; it must not be shared across unrelated controllers as if it were aggregate state.
- `R-06` Cross-surface global settings (for example origin mode/radius) may influence future backend refreshes, but they must not directly overwrite or masquerade as canonical aggregate event data.
- `R-07` Controller helper methods must not accept `StreamValue` parameters; explicit field-site mutation or semantic setters are required so ownership stays visible.
- `R-08` Repository page-envelope knowledge is private. Controllers/widgets may consume materialized domain items returned by repository queries, but backend pagination metadata (`has_more`, page-envelope builders, raw query-result wrappers) must be ingested and terminated inside the repository.
- `R-09` Repository contracts expose semantic intents, not pagination mechanics. Replace public `getXPage(page, pageSize, ...)`, `hasMore...`, or `loadNext...Page()` APIs with aggregate- or feature-semantic methods such as `loadHomeAgenda()`, `loadMoreHomeAgenda()`, `loadEventSearch()`, `loadMoreEventSearch()`, or `loadConfirmedEvents()`.
- `R-10` Public domain `Paged*Result` / page-envelope wrappers are forbidden. If a helper result object is still useful, it must stay private to the repository implementation file.
- `R-11` Repository-private pagination knowledge must not leak through delegated helpers. Even in support/test code, configure repository behavior semantically (query inputs / returned items), and keep page slicing/bookkeeping internal to the repository implementation or fake implementation.
- `R-12` Controllers may emit `loadMore` intent, but they must not read repository-owned pagination state back out. Stopping conditions belong to controller-local observation (`no growth` / `empty next slice`) or to repository no-op behavior, never to `hasMore...` delegates.
- `R-13` Widget controllers are subtree-private. A screen or parent widget must not resolve a controller defined under its descendant widget boundary.
- `R-14` Controller-to-controller dependencies are forbidden. Shared settings/state must be mediated by repository contracts, never by controller relay.
- `R-15` Repository-owned settings streams are the only valid source of truth for shared or persisted UI settings (for example radius preferences reused by multiple surfaces).
- `R-16` Widget-local UI state is valid only while it remains private to that widget boundary. If another controller/surface needs it, promote the state rather than leaking the widget controller upward.
- `R-17` Exactly one scroll truth may drive a given behavior. Compact state, pagination, and other scroll reactions must observe the same scroll source that moves the real list.
- `R-18` Borrowed UI controllers are read/write dependencies, not ownership transfers. The callee must not dispose or shadow them with a competing controller for the same behavior.
- `R-19` Widget controllers used for complex subtrees should be widget-scoped/factory-managed by default. Module-scoped singleton registration is a deviation unless the subtree lifetime is intentionally module-wide and still does not leak upward.

## 5. Plan
1. Freeze and document the widget-controller privacy / controller-to-controller / repository-owned shared-setting / single-scroll-truth boundary rules before changing Home structure.
2. Refactor the Home agenda repository contract to expose one canonical Home agenda stream and demote/remove duplicate public cache surfaces where possible.
3. Remove controller writes into repository-owned Home agenda streams; keep invite/confirmed filtering as controller-local projection.
4. Eliminate shared paged scratch from `ScheduleRepository` where it only serves screen-local flows; if Event Search still needs paging, repository owns the query state and exposes only semantic first/next intents.
5. Remove generic `StreamValue` parameter helpers from controllers and add analyzer coverage for this bypass pattern.
6. Remove exported/raw page-envelope or cache-snapshot contracts from the Schedule lane (`PagedEventsResult`, `HomeAgendaCacheSnapshot`), keeping backend pagination/query knowledge fully internal to the repository and returning only materialized domain items through semantic load/load-more intents.
7. Remove delegated/support helpers that still expose repository pagination semantics from tests/fakes; test doubles must be configured semantically rather than by page bookkeeping.
8. Reconcile origin/radius behavior so Home refreshes are explicit, repository-backed, and visibly acknowledged on the radius control while refresh is in flight.
9. Add focused RED coverage for:
   - cross-controller interference via shared repository scratch state,
   - map/home origin drift,
   - controller-local filtering not mutating canonical Home agenda state,
   - controller-local event-search state remaining local after scratch removal,
   - fake/support repository implementations not leaking pagination semantics back out,
   - widget-controller privacy / borrowed-controller ownership regressions in Home Agenda.
10. Run targeted tests, analyzer, and final validation.

## 6. Risks / Notes
- Event Search currently shares the same repository paged scratch state; this lane may require a query-scoped split or a Home-specific fetch path to avoid false coupling.
- Home cache restore behavior currently depends on effective origin comparison. We must preserve the intended UX benefit of fast re-entry without reintroducing a second source of truth.
- This bug is also a governance problem, not just a one-off implementation mistake; the extracted rules should be treated as portable guardrails for other repositories/controllers.
- The Home/Agenda radius-button investigation revealed a second governance gap beyond stream ownership: widget-controller privacy and scroll-signal ownership were under-specified. Structural refactor must respect those boundaries before any controller unification attempt.

## 7. Delivery Outcome (Target)
- Home agenda consumes one repository-owned canonical event stream.
- Home invite/confirmed filtering is local-only and no longer mutates repository aggregate state.
- Cross-surface navigation cannot blank Home agenda due to shared scratch query state.
- Home radius changes trigger the canonical repository-backed refresh path and surface an explicit loading state until refresh settlement.
- Event Search no longer depends on repository-owned scratch/page streams for screen-local display state.
- Schedule query flows no longer expose public page-envelope contracts or raw-construction shortcuts outside repository ownership.
- Architecture docs explicitly record the single-writer/single-source rule for repository-owned aggregate streams.

## 8. Rule / Workflow Sources Used
- `delphi-ai/main_instructions.md`
- `delphi-ai/skills/bug-fix-evidence-loop/SKILL.md`
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `tool/belluga_analysis_plugin/lib/src/rules/controller_delegated_streamvalue_write_forbidden_rule.dart`
- `tool/belluga_analysis_plugin/lib/src/rules/controller_streamvalue_model_ownership_forbidden_rule.dart`
- `foundation_documentation/todos/completed/TODO-v1-flutter-architecture-rules-consolidation-and-custom-lint.md`
