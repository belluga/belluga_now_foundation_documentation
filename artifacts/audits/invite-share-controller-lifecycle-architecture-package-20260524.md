# Invite Share Controller Lifecycle Architecture Audit Package - 2026-05-24

## Scope
Evaluate the correct architecture for `InviteShareScreenController` after the occurrence-scoped repository change.

This is an analysis package only. No implementation has been applied for this round.

## Trigger
The current Flutter branch introduced `sessionVersion` guards inside `InviteShareScreenController` to prevent stale async completions from a previous invite/occurrence mutating the current screen state.

User challenged this as likely architectural debt:
- Inviteables canonical state is now repository-owned by occurrence key.
- Controller should delegate to the repository-owned `StreamValue` for the current occurrence.
- A screen controller should be ephemeral per screen instance and should not need session identity to protect against reuse.

## Relevant Current Code Evidence
- `lib/application/router/modular_app/modules/invites_module.dart` registers:
  - `InviteFlowScreenController` as `registerLazySingleton`.
  - `InviteShareScreenController` as `registerLazySingleton`.
  - `ContactGroupManagementController` as `registerLazySingleton`.
- `lib/presentation/tenant_public/invites/routes/invite_share_route.dart` wraps `InviteShareScreen` in `ModuleScope<InvitesModule>`.
- `lib/presentation/tenant_public/invites/screens/invite_share_screen/invite_share_screen.dart` resolves the controller with `GetIt.I.get<InviteShareScreenController>()` and currently does not call `_controller.onDispose()` in `dispose`.
- `get_it_modular_with_auto_route` `registerFactory` only calls `GetIt.I.registerFactory` and unregisters the type on module dispose; it does not auto-dispose created instances.
- Existing ephemeral examples:
  - `TenantHomeAgendaController` is `registerFactory` and `HomeAgendaSection.dispose()` calls `_controller.onDispose()`.
  - `FavoritesSectionController` is `registerFactory` and `FavoritesSectionBuilder.dispose()` calls `_controller.onDispose()`.
  - `InvitesBannerBuilderController` is `registerFactory` and `InvitesBannerBuilder.dispose()` calls `_controller.onDispose()`.
- Existing route/screen singletons exist for long-lived surfaces, e.g. `TenantHomeController`, `MapScreenController`, `InviteFlowScreenController`, and tenant-admin shared section controllers.

## Relevant Architecture Rules
- Canonical shared state must be owned by repositories.
- `StreamValue` in controllers is valid only for local screen/stage state or pure delegation of repository-owned canonical streams.
- Screens resolve same-feature controllers through `GetIt`.
- Feature/module controllers must be registered in their owning module with `registerFactory` or `registerLazySingleton`.
- Controller state must not become an ad-hoc shared global store.
- Controllers that own `StreamValue`/resources implement `Disposable`; factory-created instances require an explicit screen/widget disposal path if the DI framework does not auto-dispose instances.

## Current Repository Direction
- `InvitesRepositoryContract` now exposes occurrence-scoped inviteables:
  - `inviteableRecipientsStreamValueForOccurrence(occurrenceId)`.
  - `setInviteableRecipientsForOccurrence(...)`.
  - `inviteableRecipientsForOccurrence(...)`.
- Concrete repository stores `Map<String, StreamValue<List<InviteableRecipient>?>>`.
- The repository is now the canonical owner of inviteable recipient state per occurrence.

## Current Controller Direction
The controller still owns local UI/projection streams:
- `friendsSuggestionsStreamValue`.
- `sentInvitesStreamValue`.
- `sentInviteSummaryStreamValue`.
- `shareCodeStreamValue`.
- pane/filter/loading/failure/in-flight streams.

The controller also contains:
- `_inviteShareSessionVersion`.
- `_shareCodeLoadingSessionVersion`.
- `_phoneContactsRefreshSessionVersion`.
- `_isCurrentInviteShareContext(sessionVersion, occurrenceId)`.
- send keys scoped with `session:<version>|occurrence:<id>|recipient`.

## Architectural Options To Audit

### Option A - Keep lazy singleton and keep `sessionVersion`
Pros:
- Minimal code churn.
- Guards stale futures when one singleton is reused for different invite occurrences.

Cons:
- Treats a route/screen controller as shared state.
- `sessionVersion` becomes a compensating mechanism for controller reuse.
- Higher risk of local screen streams surviving across route instances.
- Contradicts the user's expected repository-owned occurrence state boundary.

### Option B - Make `InviteShareScreenController` a factory-created screen instance and remove `sessionVersion`
Expected shape:
- Change `InviteShareScreenController` registration to `registerFactory`.
- `InviteShareScreen` resolves one controller instance in its `State`.
- `InviteShareScreen.dispose()` calls `_controller.onDispose()`.
- Remove all `sessionVersion` fields/parameters.
- Keep `_isDisposed` and occurrence checks only where async callbacks can complete after dispose or where current invite identity must be confirmed.
- Direct invite in-flight keys should be scoped by occurrence + recipient, not session.
- Repository remains the only canonical owner of occurrence inviteables.

Pros:
- Aligns controller lifetime with screen lifetime.
- Eliminates session identity as an architectural crutch.
- Matches factory/dispose patterns already used in home subcontrollers.

Risks:
- Tests that registered singleton controllers may need adjustment.
- If Flutter reuses the same `State` while `widget.invite` changes, `didUpdateWidget` must either recreate/reinit safely or route identity must guarantee replacement.
- Need to ensure disposal does not break existing ModuleScope lifecycle.

### Option C - Move more derived state into repository and make controller mostly a delegating facade
Expected shape:
- Repository owns not only raw inviteables but also occurrence-scoped derived list/state needed by the screen.
- Controller exposes repository streams directly or through thin getters.

Pros:
- Strongest canonical-state centralization.

Cons:
- Risks moving UI projection/filter state into repository.
- Repository may start owning presentation concerns such as selected pane, selected reason, local contact fallback labels, and loading UX.
- Larger refactor with more blast radius than needed for this blocker.

## Questions For Auditors
1. Is `sessionVersion` justified after repository occurrence-scoping, or is it masking an incorrect singleton controller lifecycle?
2. Which option is the simplest safe architecture for this slice?
3. Which local state should remain in the controller versus repository?
4. What tests are mandatory to prove the chosen architecture?
5. Are there any promotion-blocking risks in switching this controller to factory + explicit dispose?
