# Invite Share Controller Lifecycle Architecture Scoring Package - 2026-05-24

## Scope
Score architectural options for `InviteShareScreenController` lifecycle after the occurrence-scoped inviteables repository change and after verifying the actual `ModuleScope` / GetIt behavior.

This is an analysis package only. No implementation should be applied by auditors.

## Current Branch Context
- Flutter branch: `fix/invite-sent-status-hydration-accepted-push-20260523`.
- Prior implementation introduced occurrence-scoped inviteables in `InvitesRepository`.
- Prior implementation also introduced `sessionVersion` guards inside `InviteShareScreenController` to protect stale async completions when the singleton controller is reused across invite occurrences.

## User Challenge
The user challenged `sessionVersion` as likely architectural debt:
- The repository owns occurrence-scoped `StreamValue`s for inviteables.
- A screen controller should be ephemeral per screen instance.
- The controller should delegate to the repository occurrence slot instead of carrying session identity.
- The user also raised that module lifecycles may dispose adequately registered controllers when the module closes, and asked whether invite share should be promoted to its own module.

## Verified Framework Behavior
Package: `get_it_modular_with_auto_route-2.2.1`.

`ModuleSettingsContract.registerSubModule<T extends ModuleContract>(T module)`:
- Registers each module globally as a lazy singleton in GetIt.
- Stores it in `childModules` for route collection.

`ModuleScope<T extends ModuleContract>`:
- Calls `widget.module.init()` in `initState`.
- Calls `widget.module.dispose()` in `dispose`.
- `widget.module` is resolved as `GetIt.I.get<T>()`.

`ModuleContract.registerFactory<T>`:
- Calls `GetIt.I.registerFactory<T>(factoryFunc)`.
- Adds a dispose action that only unregisters the type.
- It does not auto-dispose factory-created instances.

`ModuleContract.registerLazySingleton<T>`:
- Calls `GetIt.I.registerLazySingleton<T>(factoryFunc)`.
- Adds a dispose action that unregisters the type.
- GetIt unregister will dispose an already-created instance if it implements `Disposable` or has a dispose function.

`InviteShareScreenController`:
- Declared as `class InviteShareScreenController with Disposable`.
- Therefore, if registered as lazy singleton and the owning module actually disposes, GetIt should call `onDispose()`.

## Current Code Evidence
- `InvitesModule` currently registers:
  - `InviteFlowScreenController` as `registerLazySingleton`.
  - `InviteShareScreenController` as `registerLazySingleton`.
  - `ContactGroupManagementController` as `registerLazySingleton`.
- `InvitesModule` currently owns routes:
  - `/convites`
  - `/invite`
  - `/convites/compartilhar`
  - `/convites/grupos`
- `InviteShareRoutePage` wraps `InviteShareScreen` in `ModuleScope<InvitesModule>`.
- `InviteShareScreen` resolves `_controller = GetIt.I.get<InviteShareScreenController>()`.
- `InviteShareScreen` currently does not explicitly call `_controller.onDispose()` in `dispose()`.

## Important Lifecycle Implications
- The current `InvitesModule` is broad. A lazy singleton share controller belongs to the whole invites module, not only to the share screen.
- A dedicated `InviteShareModule` would narrow the module scope and make module disposal more meaningful for the share flow.
- Because modules are globally registered by concrete type, a dedicated module lazy singleton still gives one controller instance per module type, not necessarily one controller per concurrently opened screen instance.
- A factory controller gives screen-instance isolation, but factory instances require explicit screen/widget disposal because `ModuleScope` only unregisters the type.

## Repository Direction
`InvitesRepositoryContract` now exposes occurrence-scoped inviteables:
- `inviteableRecipientsStreamValueForOccurrence(occurrenceId)`.
- `setInviteableRecipientsForOccurrence(...)`.
- `inviteableRecipientsForOccurrence(...)`.

Concrete repository stores `Map<String, StreamValue<List<InviteableRecipient>?>>`.

The repository is now the canonical owner of inviteable recipient state per occurrence.

## Controller State Today
The controller owns local UI/projection streams:
- `friendsSuggestionsStreamValue`.
- `sentInvitesStreamValue`.
- `sentInviteSummaryStreamValue`.
- `shareCodeStreamValue`.
- pane/filter/loading/failure/in-flight streams.

The controller also contains session guards:
- `_inviteShareSessionVersion`.
- `_shareCodeLoadingSessionVersion`.
- `_phoneContactsRefreshSessionVersion`.
- `_isCurrentInviteShareContext(sessionVersion, occurrenceId)`.
- send keys scoped with `session:<version>|occurrence:<id>|recipient`.

## Scoring Task
Each auditor must assign a score from 1 to 10 for every option below.

Scoring rubric:
- 10 = best fit for this architecture and current delivery risk.
- 7-8 = viable, with manageable caveats.
- 5-6 = possible but carries avoidable architecture or delivery risk.
- 1-4 = poor fit or likely to preserve/block serious debt.

For each score, provide one concise reason and identify mandatory tests/guards if the option is chosen.

## Options To Score

### Option A - Keep current broad `InvitesModule` lazy singleton and keep `sessionVersion`
Shape:
- Keep `InviteShareScreenController` in `InvitesModule`.
- Keep `registerLazySingleton`.
- Keep `sessionVersion` stale-completion guards.

Pros:
- Minimal code churn.
- Existing singleton has module disposal when the broad module closes.

Cons:
- Controller remains shared across all invite share route instances within the broad invites module.
- `sessionVersion` compensates for reuse instead of fixing lifecycle.
- Local UI streams can survive route changes until the broad module closes.

### Option B - Keep current broad `InvitesModule`, change share controller to factory, explicit screen dispose, remove `sessionVersion`
Shape:
- `InvitesModule.registerFactory<InviteShareScreenController>`.
- `InviteShareScreen` resolves one controller instance in its `State`.
- `InviteShareScreen.dispose()` calls `_controller.onDispose()`.
- Remove `sessionVersion`.
- Keep `_isDisposed` and occurrence identity checks where async callbacks can complete after dispose.
- Direct invite in-flight keys are scoped by occurrence + recipient.

Pros:
- Aligns controller lifetime with screen lifetime.
- Avoids session identity.
- Matches existing factory/dispose patterns in other screen/section controllers.

Risks:
- Factory instances are not auto-disposed by `ModuleScope`; explicit screen disposal is mandatory.
- If Flutter reuses the same State while `widget.invite` changes, route key or `didUpdateWidget` guard is mandatory.

### Option C - Create dedicated `InviteShareModule`, register share controller as lazy singleton, remove `sessionVersion`
Shape:
- Move `/convites/compartilhar` to a new `InviteShareModule`.
- Wrap share route with `ModuleScope<InviteShareModule>`.
- Register `InviteShareScreenController` as `registerLazySingleton` in the dedicated module.
- Rely on module closure to call GetIt unregister and controller `onDispose()`.
- Remove `sessionVersion`.

Pros:
- Better conceptual boundary than the broad `InvitesModule`.
- Module lifecycle can dispose the singleton if the route module closes.
- Avoids manual screen dispose for singleton.

Risks:
- Still one singleton per module type, not guaranteed one controller per concurrently open screen instance.
- Depends on route/module lifecycle being exactly one active share flow.
- Requires module reorganization and route ownership updates.

### Option D - Create dedicated `InviteShareModule`, register share controller as factory, explicit screen dispose, remove `sessionVersion`
Shape:
- Move `/convites/compartilhar` to a new `InviteShareModule`.
- Register `InviteShareScreenController` as `registerFactory`.
- Screen owns exactly one resolved instance and calls `onDispose()`.
- Remove `sessionVersion`.
- Keep occurrence-scoped repository state canonical.

Pros:
- Best conceptual module boundary plus per-screen lifecycle isolation.
- Avoids singleton reuse and avoids `sessionVersion`.
- Keeps repository/controller responsibilities separated.

Risks:
- More code churn than Option B.
- Factory instances still need explicit screen disposal.
- Must ensure route registration/order and module bootstrapping remain correct.

### Option E - Move most derived state into repository and make controller a thin delegating facade
Shape:
- Repository owns not only occurrence inviteables but also most occurrence-derived presentation state.
- Controller exposes repository streams mostly unchanged.

Pros:
- Strongest centralization of shared state.

Cons:
- Risks moving UI projection/filter/loading/pane concerns into repository.
- Larger refactor with broader blast radius.
- Could make repository a presentation-state store instead of canonical data owner.

## Questions For Auditors
1. Score Options A-E from 1 to 10.
2. Which option is the recommended implementation path for the current TODO?
3. Is a dedicated `InviteShareModule` required now, or is it a valid follow-up?
4. Is any use of `sessionVersion` still justified after these facts?
5. Which tests/guards are mandatory before promotion if the recommended option is implemented?
