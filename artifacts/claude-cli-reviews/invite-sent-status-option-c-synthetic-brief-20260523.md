# Claude CLI Synthetic Audit Brief - Invite Sent Status Option C

## Task
Run a release-blocker audit using only this brief. Do not inspect the repository. Report only blockers that would prevent promotion of this slice. If no blocker is present from the facts below, say so explicitly.

## Approved Contract
- `GET /contacts/inviteables` is the paginated/current-page inviteable row actionability source. When called with `occurrence_id`, returned rows include nullable `sent_invite_status` for that row only.
- `GET /invites/sent-summary` is the exact occurrence-level summary source for event/footer/share widgets. Counters are computed across the full authenticated-inviter occurrence slice; preview is bounded.
- `GET /invites/sent-statuses` is targeted hydration and push reconciliation only. It no longer returns `data.summary`, preventing accidental use as an exact summary source.
- Flutter must use distinct repository paths for targeted recipient status, exact occurrence summary, and inviteable-row actionability.
- Invite accepted push must refresh targeted recipient status and exact summary for the affected occurrence and present a visible invite-specific foreground signal.

## Implementation Facts
- Laravel added `SentInviteSummaryController` and route `GET /invites/sent-summary`.
- Laravel `SentInviteStatusQueryService` now separates `fetch()` targeted statuses from `fetchSummary()` exact counters.
- Laravel `SentInviteStatusQueryService::previewLimit()` clamps preview size between `1` and `MAX_SUMMARY_PREVIEW_LIMIT=10`, and `fetchSummary()` applies `limit($previewLimit)` before loading preview edges.
- Laravel `ContactInviteablesController` uses `InviteablePeopleService::inviteablePageFor()` for occurrence-context requests and enriches only the current page with `SentInviteStatusQueryService::statusMapForRecipients()`.
- Laravel no longer returns `data.summary` from `GET /invites/sent-statuses`.
- Flutter added `SentInviteSummary` domain model and `InviteSentSummaryRequest`.
- Flutter repository has separate methods for `refreshSentInvitesForOccurrence()`, `refreshSentInviteSummaryForOccurrence()`, and inviteable row hydration.
- Flutter event detail init refreshes exact sent invite summary for the selected occurrence.
- Flutter invite share screen consumes exact `SentInviteSummary` and non-null empty fallback state.
- Flutter invite accepted push coordinator refreshes affected sent invite occurrence and exact summary, and accepted push tap opens event destination.
- Flutter `InviteAwarePushMessagePresenter` shows visible invite-specific foreground signal for accepted invite copy.

## Audit Findings Already Resolved
- Performance blocker: initial inviteable path loaded full inviteable list before slicing. Fixed by page service plus bounded source row limit.
- Elegance blocker: row-bounded `sent-statuses.data.summary` competed with exact summary. Fixed by removing that summary from `sent-statuses`.
- Test quality blocker: missing service-boundary proof. Fixed with test asserting occurrence-context inviteables use `inviteablePageFor()` and not `inviteableItemsFor()`.

## Validation Evidence
- Triple audit round 02: Elegance, Performance, and Test Quality lanes returned clean with zero findings.
- Laravel full CI equivalent: `./scripts/delphi/run_laravel_tests_safe.sh --fail-on-warning --display-warnings` passed with 1523 tests and 7382 assertions.
- Laravel focused tests passed for exact summary >200, current-page row actionability, bounded page service, and sent statuses.
- Laravel exact summary test creates `205` sent invites and asserts exact counters remain `205` while `data.preview` is limited to `5` and `metadata.preview_limit=5`.
- Laravel architecture guardrails passed.
- Laravel exact lookup anti-pattern audit passed for touched endpoint/service paths.
- Flutter CI equivalent script: `bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build-invite-status` passed with status `0`.
- Flutter CI equivalent included rule matrix, analyzer, and 1626 tests.
- Flutter canonical local web publish/build script: `BUILD_HEARTBEAT_SECONDS=30 bash ./scripts/build_web.sh ../web-app dev` passed.
- `build_web.sh` output target was `web-app`, lane `dev`; `web-app/main.dart.js` SHA-256 is `f66dbc1a959473c10a9b6b685dfef59f7d52476e90cc36ceffb9432b5654f0d6`.
- Flutter focused tests passed for invite repository summary/status separation, backend request paths, invite share controller/screen summary, event detail summary refresh, accepted push reconciliation, and accepted push visible foreground signal.

## Required Output
- `status`: `clean` or `blocked`
- `blockers`: list of release blockers only
- `residual_risks`: list of non-blocking risks only
- `decision`: one sentence
