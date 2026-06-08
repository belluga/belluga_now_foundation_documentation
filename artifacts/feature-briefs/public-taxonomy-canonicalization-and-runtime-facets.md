# Feature Brief: Public Taxonomy Canonicalization and Runtime Facets

## Artifact Role
- **Why this brief exists now:** the problem is broader than one endpoint bug. It spans canonical taxonomy ownership, event legacy `tags` drift, Home/Discovery filter contracts, and compatibility consumers that must not regress while the current `v0.2.0+8` package is being promoted.
- **What this brief is not:** canonical module authority, a tactical TODO by itself, or approval to implement.

## Source Idea / Request
- User request: create the taxonomy-refactor TODO in the current version lane, not `vnext`, and include the Home/Discovery filter research because this is one of the highest-frequency query surfaces in the app. The TODO must be audited with the triple external-review loop before approval.

## Problem / Desired Outcome
- **Problem:**
  - Account Profiles already behave close to the canonical taxonomy model (`taxonomy_terms` snapshots + machine-key filtering), but Events still persist/query/live on active legacy `tags[]` in multiple paths.
  - Home Agenda and public Discovery filters currently surface type/taxonomy options from static catalog logic instead of the actual current result universe, so users can select options that return zero results.
  - Event-level and occurrence-level taxonomy propagation is inconsistent across public surfaces because some consumers read `taxonomy_terms`, others still read `tags`, and some UI paths improvise local chip/facet derivations.
  - Some touched filter surfaces hide the resolved human label until the user selects the option, reducing scanability and making the default state look incomplete.
- **Desired outcome:**
  - `taxonomy_terms` becomes the canonical source of truth for public taxonomy semantics.
  - Home Agenda and public Discovery expose backend-owned runtime facets over the full filtered universe before pagination, not static full-tenant catalogs.
  - Legacy `tags` becomes an explicit compatibility projection or is removed from touched paths, but it is no longer the source of truth for new writes or public filter logic.
  - Touched Home Agenda and public Discovery filter options render the resolved human label in the default unselected state and preserve that label after selection.
  - Public event/account chips and filters become consistent, performant, and deterministically testable.
- **Why now:** the user already identified product-visible drift and explicitly wants this scope pulled into the current release lane rather than left in deferred backlog.

## Constraints / Non-Goals
- **Constraints:**
  - Must land in the current `v0.2.0+8` lane; no parallel version/TODO owner may remain in `active/vnext/`.
  - Must preserve current approved occurrence-first event semantics, backend-owned geo filtering, and account-profile public catalog/queryability boundaries.
  - Must be performance-safe for Home Agenda, which is a high-frequency public query path.
  - Must include explicit runtime validation planning for admin authoring, web navigation, and app/device behavior where the user-visible contract changes.
- **Non-goals:**
  - Map filter UX redesign.
  - Taxonomy registry/admin redesign.
  - Text search expansion for agenda/events.
  - Unrelated share/invite copy or immersive layout work.

## Canonical Touchpoints
- **Constitution impact:** `none` — this is module/TODO level, not project-constitution level.
- **Roadmap impact:** `possible` — current-lane absorption of a previously deferred owner TODO changes execution ordering, but not the long-term product roadmap.
- **Primary module candidates:** `foundation_documentation/modules/events_module.md`
- **Secondary module candidates:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/account_profile_catalog_module.md`
  - `foundation_documentation/modules/map_poi_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`

## Evidence / References
- `foundation_documentation/todos/active/vnext/TODO-vnext-home-and-discovery-taxonomy-aggregation-contract.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/agenda_and_action_planner_module.md`
- `foundation_documentation/modules/account_profile_catalog_module.md`
- `foundation_documentation/modules/map_poi_module.md`
- `laravel-app/app/Application/DiscoveryFilters/DiscoveryFilterPublicCatalogService.php`
- `laravel-app/packages/belluga/belluga_events/src/Application/Events/EventQueryService.php`
- `laravel-app/app/Application/AccountProfiles/AccountProfileQueryService.php`
- `flutter-app/lib/presentation/shared/discovery_filters/public_discovery_filter_controller_mixin.dart`
- `flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart`
- `flutter-app/lib/presentation/tenant_public/discovery/controllers/discovery_screen_controller.dart`
- `flutter-app/lib/domain/schedule/event_model.dart`
- `flutter-app/lib/infrastructure/dal/dto/schedule/event_dto.dart`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling |
| --- | --- | --- | --- | --- |
| `AMB-TAX-01` | Should legacy event `tags[]` remain in touched public payloads during `v0.2.0+8`, and if so only as a derived compatibility field? | Changes DTO/domain compatibility and how broad the consumer cutover must be. | Flutter `EventModel` and invite/share flows still read `tags`; Laravel Events still accepts/queries `tags`. | `carry as TODO assumption` |
| `AMB-TAX-02` | Should Home and Discovery expose the exact same facet envelope shape, or only the same semantics? | Affects controller reuse, DTO shape, and whether the refactor can stay bounded. | User explicitly described `data/types/taxonomies`, but allowed another name/structure if the semantics are correct. | `carry as TODO assumption` |
| `AMB-TAX-03` | Does this slice include map compatibility verification only, or map facet-contract redesign too? | Prevents this TODO from silently absorbing a second high-risk query program. | User clarified the current filter request is about Home/Discovery, not map. | `resolve now` |

## Story Decomposition
| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Canonicalize public taxonomy ownership and deliver runtime facets for Home Agenda + public Discovery without empty-result filter options. | `events_module` | `agenda_and_action_planner_module`, `account_profile_catalog_module`, `flutter_client_experience_module` | Events no longer depend on raw `tags` as source of truth for touched paths; Home/Discovery consume backend-owned runtime facets over the current universe; public taxonomy chips/filters stay consistent. | Laravel + Flutter + Playwright + ADB matrix | `create-now` | Requires careful compatibility plan for legacy `tags` consumers. | This is the requested current slice. |
| `ST-02` | Redesign public Map filter facet generation around the same universe semantics. | `map_poi_module` | `events_module`, `account_profile_catalog_module` | Map filters stop using current catalog aggregation semantics and become runtime-faceted by map universe. | Laravel + Flutter map + Playwright map | `defer` | Separate product/query surface; user explicitly deprioritized it for this TODO. | Keep only negative-regression compatibility inside `ST-01`. |
| `ST-03` | Remove every remaining legacy `tags` compatibility field from all downstream consumers after canonical cutover. | `events_module` | `map_poi_module`, `flutter_client_experience_module`, share/invite consumers | No touched consumer still reads `tags` anywhere in public/runtime contracts. | Repo-wide source scan + full runtime matrix | `split-further` | Too broad for the current lane unless current implementation proves the consumer set is already small. | May become a later hardening TODO if needed. |

## Retire This Brief When
- `foundation_documentation/todos/active/v0.2.0+8/TODO-v0.2.0+8-public-taxonomy-canonicalization-and-runtime-facets.md` exists as the active owner contract and this brief no longer carries unresolved scope ambiguity.
