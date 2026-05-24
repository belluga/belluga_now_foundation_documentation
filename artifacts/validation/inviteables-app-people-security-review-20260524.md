# Security / Privacy Review: Inviteables App People Projection

- Security risk level: `medium`
- Attack simulation decision: `not_needed`
- Created at: 2026-05-24
- TODO: `foundation_documentation/todos/active/fast_follow_required/TODO-bugfix-inviteables-app-people-performance-ui-cache.md`

## Attack Surfaces Reviewed
- `GET /api/v1/contacts/inviteables`
- `POST /api/v1/contacts/import`
- `GET /api/v1/invites/sent-statuses`
- `inviteable_people_projection` tenant collection
- Flutter invite-share app-people pane and sent-status overlay

## Trust Boundaries
- Authenticated tenant `AccountUser` identity is required for inviteables reads.
- Viewer ownership key is `owner_user_id`; projection rows are viewer-scoped.
- Recipient surface is `receiver_account_profile_id`; raw contact data is not returned.
- Profile exposure is carried as `profile_exposure_level`; avatar/image data must respect exposure rules already enforced by the materializer.

## Threat-Intel Sources Used
- None. This review did not involve a new dependency, CVE-sensitive component, agent prompt-ingestion surface, or externally supplied executable content. The relevant risks are tenant isolation, IDOR, stale projection leakage, and route-critical over-fetching, all validated from local code/tests and project contracts.

## Findings
| Issue ID | Severity | Status | Evidence | Resolution |
| --- | --- | --- | --- | --- |
| SEC-01 tenant breakout through inviteables read | medium | passed | `ContactInviteablesController` resolves `$request->user()` and passes the authenticated tenant user to `InviteablePeopleService`; read query is constrained by `owner_user_id`. | No blocker. |
| SEC-02 stale privacy/capability projection leakage | medium | passed | Laravel focused suite covers discoverability/capability revocation pruning and next GET exclusion. | No blocker. |
| SEC-03 raw contact/PII leakage | medium | passed | API returns hashes/relation metadata only; raw phone/email/contact display data remains app-local. `POST /contacts/import` stores hashes and match snapshots. | No blocker. |
| SEC-04 internal invite lifecycle leakage | low | passed | Flutter mapping keeps `superseded` as `Convidado` in inviteable card presentation. | No blocker. |
| SEC-05 request-loop abuse / over-fetch amplification | medium | passed | Client chunk fanout is removed; GET is page-bounded with default `50` and max `100`; route-critical ADB request shows `page=1&page_size=50`. | No blocker. |

## Residual Security Risk
- No blocking tenant/privacy issue remains for this TODO.
- Accepted runtime debt: Mongo `explain()` and seeded non-empty ADB payload semantics may be collected during promotion if promotion gates require deeper runtime evidence.

## Promotion Candidates
- Consider a future static rule that fails public tenant GET endpoints when a viewer-scoped projection read lacks an explicit owner/principal filter.
