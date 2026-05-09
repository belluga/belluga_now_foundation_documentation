# TODO (Post Release Hardening): Account Profile Agenda Capability Relationship Resolution

**Completed note (2026-05-06):** this slice was delivered. The active backend now resolves public-profile agenda by capability plus canonical occurrence relationship, including `place_ref` and linked/event-party participation, and the Laravel feature coverage for those cases is present in `laravel-app/tests/Feature/AccountProfiles/AccountProfilesControllerTest.php`. This file remains only as audit history.

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Closure Status
- **Status:** `Completed`
- **Disposition:** `Delivered and retained for audit history`

## Context
Production validation on `2026-05-04` found a public-profile agenda regression for account profiles of type `federacao`.

Observed runtime:
- The tenant environment bootstrap includes `federacao` in `profile_types`.
- `federacao.capabilities.has_events=true`.
- The public profile detail route resolves normally.
- The public profile detail payload returns `agenda_occurrences: []`, so Flutter has no upcoming events to render.

Evidence collected during investigation:
- Profile type fixture confirms `federacao` is `is_poi_enabled=true` and `has_events=true`.
- Production public detail for `confederacao-brasileira-do-desporto-universitario` returns `agenda_occurrences: []`.
- `AccountProfileAgendaOccurrencesService` currently routes every `is_poi_enabled=true` profile through `place_ref` matching only.
- This is too narrow for POI-enabled profiles that participate in event occurrences as linked/event-party profiles instead of the canonical venue/place owner.

This TODO exists to restore the intended public-profile agenda behavior without reopening unrelated profile-type, event-detail, or discovery surfaces. The contract is capability-driven: if an account profile type has agenda capability and the profile is canonically related to future occurrences, those occurrences belong in the public-profile agenda regardless of whether the relationship is `place_ref`, linked/event-party participation, or another canonical occurrence relationship.

## Delivery Status Canon
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Laravel`, `Flutter-Readback`, `Regression`, `Runtime-Validated`
- **Next exact step:** add fail-first Laravel coverage for capability-enabled account profiles across distinct canonical relationship modes, then correct agenda occurrence resolution and validate public profile runtime.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `post-release-account-profile-agenda-linked-profile-resolution`
- **Direct-to-TODO rationale:** one bounded regression slice restores previously intended behavior for public profiles with agenda capability when canonical occurrence relationships exist.

## Canonical Module Anchors
- **Primary module doc:** `foundation_documentation/modules/account_profile_catalog_module.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/agenda_and_action_planner_module.md`
  - `foundation_documentation/modules/flutter_client_experience_module.md`
- **Decision consolidation targets:**
  - `account_profile_catalog_module.md`
  - `agenda_and_action_planner_module.md`

## Scope
- [ ] Add fail-first Laravel coverage proving account profiles with agenda capability expose agenda occurrences when canonically related through distinct occurrence relationship modes, including `place_ref` and linked/event-party participation.
- [ ] Preserve venue/place-owner agenda behavior for `place_ref`-backed profiles as one valid relationship mode, not a special-case exception.
- [ ] Correct `AccountProfileAgendaOccurrencesService` so agenda resolution is driven by profile capabilities plus canonical occurrence relationships, not by hardcoded profile types or an `is_poi_enabled=true => place_ref-only` assumption.
- [ ] Validate the public account-profile detail payload returns `agenda_occurrences` for the affected capability-enabled profile class.
- [ ] Validate Flutter public profile detail renders upcoming events once the payload is corrected.

## Out of Scope
- [ ] Reworking event authoring semantics or event-party data contracts.
- [ ] Reopening profile type capability design.
- [ ] Reclassifying this as a discovery/map/filter issue.
- [ ] Reopening public-profile tab IA beyond restoring agenda rendering from canonical payload.

## Decision Baseline
- [x] `D-01` `has_events=true` on a public profile type means public profile detail must expose agenda when canonical occurrence relationships exist, independent of the specific account-profile type name.
- [x] `D-02` Venue/place-owner agenda matching via `place_ref` remains valid, but it is only one valid canonical relationship mode.
- [x] `D-03` Agenda resolution must not depend on hardcoded profile-type names, and `is_poi_enabled` does not imply `place_ref-only` agenda ownership.
- [x] `D-04` Public-profile agenda must be sourced from backend canonical occurrence relationships, not improvised in Flutter.

## Root-Cause Snapshot
- **Backend payload reality:** public profile detail is returning `agenda_occurrences: []` for the affected `federacao` profile.
- **Current code path:** `AccountProfileAgendaOccurrencesService::forProfile()` chooses:
  - `place_ref` matching when `isPoiEnabled(profile_type)=true`
  - `event_parties.party_ref_id` matching otherwise
- **Failure mode:** agenda resolution is branching on profile-type flags instead of the full set of canonical occurrence relationships. That excludes valid profiles whenever their real occurrence relationship does not match the single branch chosen by the service.

## Validation Strategy
- [ ] Laravel feature tests covering at least:
  - a capability-enabled profile related through `place_ref`
  - a capability-enabled profile related through linked/event-party participation
  - preservation against unrelated/non-capability profiles
- [ ] Targeted payload probe against tenant runtime or equivalent deterministic test fixture proving `agenda_occurrences` are non-empty for the affected capability-enabled profile class.
- [ ] Flutter public profile test proving agenda modules/tabs render when corrected payload arrives.
- [ ] `fvm dart analyze --format machine` for Flutter surfaces touched by any follow-up readback/test change.

## Local CI-Equivalent Suite Matrix
This TODO is not ready for `Local-Implemented`, promotion-lane movement, or any promotable claim until every in-scope row below has been executed locally and passed on the final execution state. Targeted reruns remain diagnostic only.

| Repository / CI Surface | Why In Scope | Local CI-Equivalent Command | Required Before | Status | Evidence Artifact / Command | Notes |
| --- | --- | --- | --- | --- | --- | --- |
| `laravel-app / Laravel CI` | Agenda resolution for capability-enabled account profiles is a backend payload/query contract. | `bash -lc 'cd laravel-app && composer run architecture:guardrails && APP_ENV=testing APP_URL=http://nginx APP_HOST=nginx APP_KEY=base64:GmmALtgdmR+nNYciHr0ynX/QoqHXmoXXtbwHVNWg8Pk= APP_FAKER_LOCALE=pt_BR DB_CONNECTION_LANDLORD=landlord DB_CONNECTION_TENANTS=tenant DB_URI=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_LANDLORD=mongodb://localhost:27017/landlord_test?replicaSet=rs0&directConnection=true DB_URI_TENANTS=mongodb://localhost:27017/tenants_test?replicaSet=rs0&directConnection=true DB_DATABASE=landlord_test DB_DATABASE_LANDLORD=landlord_test DB_DATABASE_TENANTS=tenants_test php artisan test --fail-on-warning --display-warnings'` | `Local-Implemented` | `planned` | `laravel-app/.github/workflows/ci.yml` mirrored locally | Must include the repo-owned full suite, not only the new agenda-focused feature tests. |
| `flutter-app / Validate and Build Web` | The frozen contract requires Flutter public-profile readback validation in addition to backend payload repair. | `bash -lc 'cd flutter-app && bash scripts/local_validate_and_build_web_ci_equivalent.sh /tmp/flutter-web-ci-build'` | `Local-Implemented` | `planned` | `flutter-app/scripts/local_validate_and_build_web_ci_equivalent.sh` run log + `/tmp/flutter-web-ci-build` artifact | This row stays mandatory because the TODO requires a Flutter public-profile validation artifact, even if the code delta is small. |

## Investigation Record
- Production bootstrap includes `federacao` in `profile_types`.
- Production public detail for `confederacao-brasileira-do-desporto-universitario` returned:
  - `profile_type = federacao`
  - `agenda_occurrences = []`
- Service under investigation:
  - `laravel-app/app/Application/AccountProfiles/AccountProfileAgendaOccurrencesService.php`

## Store Release Relationship
This is a post-release regression hardening slice. The store release completed, but the public profile agenda contract is not fully honored for capability-enabled account profiles when their canonical occurrence relationship is not the single branch currently chosen by the backend. Fixing it now reduces future false assumptions around `is_poi_enabled`, `place_ref`, and public agenda availability.
