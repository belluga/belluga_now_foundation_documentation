# Endpoint Performance Review

- Endpoint: /api/v1/contacts/inviteables
- Access Pattern: bounded-list
- Created At: 2026-05-24 19:30:31 UTC

## Canonical Lookup Path
- Backend query path: `ContactInviteablesController` -> `InviteablePeopleService::inviteableItemsFor()` / `inviteablePageFor()` -> `InviteablePeopleProjection`.
- Client/repository path: `InviteablesRepository.fetchInviteableRecipients()` -> `LaravelInvitesBackend.fetchInviteableContacts(InviteableContactsRequest(page: 1, pageSize: 50))` -> `GET /api/v1/contacts/inviteables?page=1&page_size=50`.
- Direct exact-lookup contract exists: n/a; this endpoint is a bounded-list/read-model query, not exact lookup.

## Lookup Keys
- Primary read key: `owner_user_id`.
- Stable ordering: `sort_name`, then `receiver_account_profile_id`.
- Exact projection upsert key: `owner_user_id + receiver_account_profile_id`.

## Expected Index / Constraint Support
- Unique: `{ owner_user_id: 1, receiver_account_profile_id: 1 }`.
- Bounded list: `{ owner_user_id: 1, sort_name: 1, receiver_account_profile_id: 1 }`.
- Reverse impact lookup: `{ receiver_user_id: 1, owner_user_id: 1 }`.
- Backfill/freshness support: `{ materialized_at: -1, _id: 1 }`.

## Forbidden Fallback Patterns
- page-walk exact lookup through paginated list endpoint
- broad fetch plus in-memory filter for exact key
- client-side slug/id match after multi-page list traversal

## Evidence
- Heuristic audit output:
  - `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo flutter-app --scan-git-modified`: no high findings; one medium `firstWhere` in a contact-group controller test only, not production path.
  - `bash delphi-ai/tools/exact_lookup_anti_pattern_audit.sh --repo laravel-app --scan-git-modified`: no high or medium findings.
  - Obsolete symbol scan finds `_chunkContactImportItems` / `_maxContactImportItemsPerRequest` only inside the guard test that asserts they are absent.
- Query / bounded-path evidence:
  - Laravel focused suite passed: `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Invites/StoreReleaseSocialGraphTest.php --filter='inviteable_contacts|projection|contact_import_materializes|contact_import_materialization_is_bounded|favorite_materialization|discoverability_revocation|backfill'` => `11 passed (68 assertions)`.
  - Covered cases include projection-only GET without contact-hash matching, default and explicit bounded page service usage, contact import materialization, bounded imported-profile materialization with `1200` existing source rows, favorite materialization, discoverability revocation prune, and explicit backfill.
  - Flutter repository/DAL/guard suite passed: `fvm flutter test test/infrastructure/repositories/inviteables_repository_test.dart test/infrastructure/repositories/invites_repository_test.dart test/infrastructure/dal/laravel_invites_backend_test.dart test/architecture/invite_contact_import_request_loop_guard_test.dart` => `39 passed`.
  - Android ADB evidence on `192.168.15.9:5555` passed for invite-share surface, cold-cache split, profile surface, and the real-backend inviteables contract.
  - Real-backend ADB output: `INVITEABLES_REAL_BACKEND_E2E domain=guarappari.belluga.space path=/api/v1/contacts/inviteables query=page=1&page_size=50 recipients=0`.
  - Flutter rule matrix passed: `bash tool/belluga_analysis_plugin/bin/validate_rule_matrix.sh` detected the expected `57` lint codes.
- Residual risk:
  - This review proves the route-critical read path no longer rebuilds inviteables from source collections or performs client-side contact import request loops.
  - Deep Mongo `explain()` evidence was not captured in this pass; index coverage is asserted by migration/tests and should be rechecked during promotion/stage runtime if query planner regressions are suspected.
