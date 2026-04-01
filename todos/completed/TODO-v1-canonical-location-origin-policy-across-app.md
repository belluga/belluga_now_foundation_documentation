# TODO (V1): Canonical Location-Origin Policy Across App

**Status:** Completed
**Primary Module Anchor:** `foundation_documentation/modules/flutter_client_experience_module.md`
**Secondary Module Anchor:** `foundation_documentation/modules/map_poi_module.md`
**Complexity:** medium
**Checkpoint Policy:** one review checkpoint before implementation

## 1. Context
The app now persists `LocationOriginSettings` locally through `AppDataRepository`, but the selection rule is still not canonical across tenant-public surfaces.

Current gaps:
- Home owns the `outside-range` decision inline in `TenantHomeAgendaController`;
- Map still uses live user location whenever permission exists, even if the user is outside the tenant-served range;
- map soft-gate notice currently only uses the `unavailable` message path, not the `outsideRange` message path;
- the selection rule is not centralized in a reusable domain contract, so new surfaces can drift again.

Product decision now requires the location-origin selection criterion to be canonical and reflected across the app:
- if the user is within the tenant-served radius, use live location;
- if the user is outside the tenant-served radius, use fixed tenant reference;
- if location is unavailable, use fixed tenant reference;
- the resulting mode/reason must be persisted locally for MVP and be readable by other surfaces.

Canonical modeling decision:
- the resolved location context should expose both:
  - `mode`: where the effective coordinate comes from
  - `reason`: why that mode was selected
- this is preferred over a single flattened enum because user-facing copy depends directly on the reason.

## 2. Scope
In scope:
- canonical location-origin selection contract in Flutter domain/application layer
- local persistence path through `AppDataRepositoryContract`
- Home consuming the canonical policy instead of inline selection logic
- Map consuming the canonical persisted/policy result instead of diverging from Home
- top map notice copy using the correct reason (`outsideRange` vs `unavailable`)
- test updates for canonical origin selection behavior
- module/doc sync

Out of scope:
- backend/DB-backed settings persistence
- profile-owned UI to edit location mode
- manual map-picked location origin
- auth/account profile settings UI

## 3. Decision Baseline (Frozen)
- `D-01` The location-origin selection rule must be canonical across tenant-public surfaces; Home must not be the only owner of the decision.
- `D-02` The selected origin mode/reason remains persisted through `AppDataRepositoryContract` for MVP local/device-only behavior.
- `D-03` The boundary for `outsideRange` is the tenant-configured maximum radius (`appData.mapRadiusMaxMeters`), not a hardcoded constant.
- `D-04` Canonical selection outcome must be expressible as persisted `LocationOriginSettings` so downstream surfaces can read the same decision.
- `D-05` Map must honor the canonical selection result even when location permission exists; being outside the served range is sufficient to switch to fixed tenant reference.
- `D-06` When the map is running under fixed tenant reference because the user is outside the served range, the notice/snackbar must use the `outsideRange` copy path, not the `unavailable` path.
- `D-07` The canonical resolved-location model must carry both `mode` and `reason`.
- `D-08` Recommended `mode` set:
  - `user_live_location`
  - `tenant_default_location`
  - `user_fixed_location`
- `D-09` Recommended `reason` set:
  - `live`
  - `outsideRange`
  - `unavailable`
  - `userPreference`
- `D-10` `reason` is the authoritative input for user-facing copy/messages across Home, Map, and future surfaces.
- `D-11` A dedicated domain/application policy object or service should own selection logic; controllers may orchestrate calls and persistence, but must not duplicate the distance/range decision inline.
- `D-12` User-facing transient notices (for example, map snackbar/banner) are `reason`-driven and optional. The UI must check whether the resolved `reason` has an associated message; if no message is defined for that `reason`, no transient notice is shown.
- `D-13` `live` should be treated as a valid `reason` in the canonical model, but it does not imply a snackbar/banner by default.
- `D-14` `userPreference` may already have a defined message in the canonical message resolver. Approved baseline copy:
  - `Estamos usando uma localização fixa definida por você nas configurações. Você pode alterar para usar sua localização atual nas configurações.`
- `D-15` MVP copy resolution remains local/client-side with hardcoded fallback strings owned by Flutter.
- `D-16` The message-resolution shape should already anticipate future backend/environment-driven copy, so replacing the source later does not require redefining the `reason -> message` contract.
- `D-17` VNext will extend this policy with explicit user override (`prefer fixed location even when inside range`) and profile-owned editing; MVP remains automatic.

## 4. Implementation Direction
1. Introduce a canonical location-origin policy contract/helper that accepts:
   - fresh user coordinate
   - tenant default origin
   - tenant max radius
2. Make it return a resolved location context plus a persistable `LocationOriginSettings` outcome.
3. Keep persistence in `AppDataRepositoryContract`.
4. Refactor Home to use the canonical policy result instead of inline `haversine` branching.
5. Refactor Map to read/apply the same canonical result for runtime origin selection and top notice copy.
6. Use the shared `reason` value to resolve copy/messages consistently.
7. Make snackbar/banner rendering conditional on `reason -> message` lookup rather than hardcoded per-screen branching.
8. Lock with tests at:
   - policy level
   - Home
   - Map

## 5. Delivery Outcome
- Introduced canonical `LocationOriginService` + `LocationOriginResolution`/`LocationOriginSettings` model with `mode + reason`.
- Replaced inline effective-origin selection in Home, Map, Discovery, Event Search, Schedule repository, and account-profile backend adapter with the canonical service.
- Added a dedicated analyzer rule to block inline canonical location-origin resolution outside the allowlisted canonical files.
- Preserved MVP local/device-only persistence in `AppDataRepository`, including reason-driven message lookup with optional notice rendering.
- Updated map soft-gate/fixed-origin notices to be `reason`-driven (`outsideRange`, `unavailable`, `userPreference`) rather than screen-specific branching.

## 6. Rule / Workflow Sources To Follow
- `delphi-ai/skills/flutter-architecture-adherence/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-documentation-contracts-always-on/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-controller-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-domain-workflow-glob/SKILL.md`
- `delphi-ai/skills/rule-flutter-flutter-repository-workflow-glob/SKILL.md`
- `delphi-ai/skills/test-creation-standard/SKILL.md`
