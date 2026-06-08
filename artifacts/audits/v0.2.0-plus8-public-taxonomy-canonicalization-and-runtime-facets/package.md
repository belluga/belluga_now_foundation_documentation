# Bounded Audit Package: v0.2.0+8 Public Taxonomy Canonicalization and Runtime Facets

## Package Role
- **Artifact type:** `bounded_audit_package`
- **Authority:** `derived / non-authoritative`
- **Purpose:** feed the triple external TODO audit for `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-public-taxonomy-canonicalization-and-runtime-facets.md`

## Scope Under Review
Review the TODO contract and its supporting brief only. This is a planning/audit package, not an implementation review.

Primary artifacts:
- `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-public-taxonomy-canonicalization-and-runtime-facets.md`
- `foundation_documentation/artifacts/feature-briefs/public-taxonomy-canonicalization-and-runtime-facets.md`

Canonical anchors:
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/map_poi_module.md`

## Why This TODO Exists
- Event taxonomy behavior is structurally inconsistent today: active legacy `tags[]` still participates in write/read/query flows while `taxonomy_terms` already exists.
- Home Agenda and public Discovery currently expose filter choices from static catalog logic rather than the actual filtered universe, producing empty-result options.
- The user explicitly requested moving the deferred taxonomy/filter owner from `active/vnext/` into the current `v0.2.0+8` lane because this must be promoted with the ongoing package, and requested a triple external audit before approval.

## Frozen Product Direction Already Chosen
- This TODO must live in `foundation_documentation/todos/active/v0.2.0+8/`, not `active/vnext/`.
- Home Agenda and public Discovery need runtime facets over the current universe, not full-tenant static catalogs.
- The refactor is performance-sensitive because Home is one of the highest-frequency public queries in the app.
- Map filter redesign is out of scope for this slice; only compatibility/non-regression remains in scope.
- Occurrence-owned taxonomy overrides remain replacement semantics, not merged semantics.

## Key Code/Contract Findings Feeding the TODO
1. **Account Profiles are already close to the canonical model**
   - Structured taxonomy snapshots are already approved/documented.
   - Filtering uses machine keys (`type`, `value`, `type:value`) and public catalog scope.
2. **Events still have active legacy `tags[]`**
   - Event write rules accept both `tags` and `taxonomy_terms`.
   - Event query/filter logic still normalizes and filters on `tags`.
   - Flutter event DTO/domain/share consumers still read `tags`.
3. **Public filter catalogs are static-by-surface today**
   - Home Agenda and public Discovery currently load catalog metadata/options from static catalog services and selected taxonomy definitions, not from the actual universe of the filtered query result.
4. **User-visible drift already exists**
   - Public event/account taxonomy propagation is inconsistent.
   - The user explicitly wants filter options that cannot return results removed from the UI.

## Frontend / Consumer Matrix
| Producer Surface | Expected Consumer | Visible Route / Action | DTO / Runtime Boundary | Planned Render / Discoverability Evidence | Planned Request / Readback Evidence | Waiver |
| --- | --- | --- | --- | --- | --- | --- |
| Event canonical taxonomy write/read/query contract | Public event cards, event detail, sharing/invite payloads, admin event readback | `/agenda`, `/agenda/evento/:slug`, admin event form | Laravel Events payloads -> Flutter event DTO/domain/controllers | Playwright readonly event-detail + Home validations; Flutter DTO/controller tests; device evidence tied to named integration tests where applicable | Laravel feature/unit tests; Playwright mutation for admin event authoring | none |
| Home Agenda runtime facet contract | Public Home Agenda filters/chips | `/` Home Agenda filter interactions | Laravel agenda/query payload -> Flutter Home controller/widgets | Playwright readonly + ADB `integration_test/feature_home_agenda_eligible_events_query_contract_e2e_test.dart` and `integration_test/feature_agenda_filters_regression_test.dart` | Laravel feature/query tests | none |
| Public Discovery runtime facet contract | Public Discovery filters/chips | `/descobrir` filter interactions | Laravel account/discovery payload -> Flutter Discovery controller/widgets | Playwright readonly + ADB dedicated Discovery runtime-facets integration test (new if no current device test covers it) | Laravel feature/unit tests | none |
| Canonical taxonomy compatibility posture (`tags[]` derived or removed) | Flutter DTO/domain/share consumers and compatibility projections | Event cards/detail/share payload generation | Laravel payload -> Flutter DTO/domain/share | Flutter DTO/domain tests, public readonly smoke | Laravel compatibility tests | none |
| PACED taxonomy/facet guardrails | Developer workflow / CI-equivalent validation | guard failure output, rule matrix, reconcile wrapper | Repo scripts/rules | n/a | Guard/script test output and reconcile wrapper report | `structure-only` for the guard UI itself; no separate browser route required |
| Map/event compatibility regression | Map/event shared projection consumers touched indirectly by the refactor | Existing map/event readonly flows only if affected | Laravel shared projection -> Flutter/web consumers | Readonly smoke if touched; otherwise explicit waiver rationale | Laravel regression coverage | allowed only if no user-visible delta is introduced |

## Review Questions For Auditors
1. Does the TODO freeze the right objective without hiding major scope creep?
2. Is the performance posture explicit and strong enough for a high-frequency public query surface?
3. Is the validation matrix strong enough to prevent the user from discovering gaps manually again?
4. Are there structural shortcuts, compatibility ambiguities, or missing guardrails that would make the TODO unsafe to approve?

## Explicit Out-of-Scope Reminder
- Do not expand this audit into implementation review of unrelated map redesign, taxonomy-registry UX, text search, or share/invite content work.
