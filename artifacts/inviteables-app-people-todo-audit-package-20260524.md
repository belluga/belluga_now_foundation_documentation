# Inviteables App People TODO Audit Package - 2026-05-24

## Scope
This is a pre-implementation audit package for:

`foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md`

The review target is the TODO contract and planned architecture, not final implementation.

## User-Approved Direction
- No backward compatibility is required.
- Hard cutoff is allowed for obsolete paths that conflict with the canonical architecture.
- The app-people inviteables pane must render from repository-owned cache/backend state and must not be blocked by contact-import chunk loops.
- `superseded` remains internal and renders as `Convidado` in inviteable cards.
- Existing `Todos` / `Contatos` chips and `Contato no Belluga` app-card label are rejected.

## Current Code Evidence
- Flutter has `_chunkContactImportItems(...)` in `flutter-app/lib/infrastructure/repositories/invites_repository.dart`, and existing repository tests codify chunk fanout.
- Current invite-share app-pane behavior can briefly show inviteables and then drop to empty/loading while contact import refresh continues.
- Laravel raw hash matching currently belongs to `ContactImportService::import()` through `InviteIdentityGatewayAdapter::matchImportedContacts(...)`.
- Laravel `/contacts/inviteables` does not do raw `hash -> user` matching today, but `InviteablePeopleService::inviteableItemsFor()` assembles/enriches final inviteable payload at read time from `contact_hash_directory`, `favorite_edges`, profiles, users, and profile-type capabilities.
- `contact_hash_directory` is not the final read model; it is an intermediate matched-contact source.

## Frozen Decisions To Audit
- `/contacts/inviteables` must read a materialized inviteables projection/read model.
- GET must not silently repair projection staleness by reconstructing inviteables from intermediate sources.
- Import/registration/favorite/profile/capability/user-state changes must refresh/materialize the projection.
- Flutter must split reusable inviteables directory/cache from occurrence invite-status overlay.
- Inviteables cache/source of truth belongs in repository state, not controller local state.
- Client-side request-loop/chunk fanout over contacts/import is forbidden in the invite-share route-critical flow.
- Tests/guardrails must fail against the current `_chunkContactImportItems` request-loop regression.

## Audit Trigger Floor
`audit_escalation_guard.py` returned `Overall outcome: go` with fingerprint `d31ae42d3bd9`.

Derived floor:
- critique: required;
- security review: required before completion;
- performance/concurrency: required;
- verification debt: required;
- test-quality audit: required;
- final review: required;
- triple review: required and additive only.

## Questions For Reviewers
1. Does the TODO correctly distinguish raw hash matching from request-time final payload assembly/enrichment?
2. Is the materialized projection/read-model boundary sufficiently explicit and safe, especially with hard cutoff/no backward compatibility?
3. Does the Flutter split between inviteables repository/cache and occurrence status overlay cover the observed repeated-entry empty-state/loading regression?
4. Are the requested tests strong enough to fail on the current buggy behavior, including `1200+` contact fixture request budgets?
5. Are there missing blockers in the TODO before implementation continues?

## Expected Reviewer Output
Return findings only when they are concrete blockers or high-value non-blocking debt.
Classify each finding as:
- `blocking`
- `accepted-debt-candidate`
- `out-of-scope`

For each finding include:
- finding id;
- severity;
- evidence;
- why it matters before implementation;
- recommended TODO change.
