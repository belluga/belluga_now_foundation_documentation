# TODO (V1): Tenant Public Account Profiles Near Endpoint (Favoritable-Only)

## 1) Context
Enable a tenant-public GEO query directly in Account Profiles so Discovery `Próximos a você` can consume account profiles without depending on map POI endpoints.

## 2) Scope
- Add tenant-public endpoint: `GET /api/v1/account_profiles/near`.
- Endpoint must require `origin_lat` and `origin_lng`.
- Endpoint must return only `is_favoritable=true` profile types from tenant registry.
- Endpoint must return only profiles that are POI-eligible / location-surface-eligible for `Próximos a você`; profiles without POI eligibility must not be emitted.
- Optional filters: `max_distance_meters`, `search`, `profile_type` (intersected with favoritable set), `page`, `page_size`.
- Return `distance_meters` per item.
- Items must be returned nearest-first by computed distance.

Out of scope:
- Flutter wiring changes.
- Map POI contract changes.
- Admin route changes.

## 3) Complexity
- `small`
- Checkpoint policy: single consolidated review.

## 4) Decision Baseline (Frozen)
- D-01 (`Preserve`): Account Profile near endpoint lives under tenant public Account Profiles API surface.
- D-02 (`Preserve`): Favoritable-only restriction is enforced server-side by tenant profile type registry.
- D-03 (`Preserve`): Geo truth uses Mongo geospatial query over `account_profiles.location` (`2dsphere` index).
- D-04 (`Preserve`): Response remains account-profile-shaped, augmented with `distance_meters` and paged metadata.
- D-05 (`Preserve`): Near endpoint is POI-backed-only for Discovery `Próximos a você`; non-POI profiles are excluded server-side as an eligibility rule.
- D-06 (`Preserve`): Near endpoint ordering is nearest-first by computed distance.
- D-07 (`Preserve`): `POI-backed only` does not change the V1 geo truth; distance continues to be computed from `account_profiles.location` with the indexed geospatial query.

## 5) Implementation Plan
- [x] ✅ Production‑Ready — Add request validation + controller method + route for `/account_profiles/near`.
- [x] ✅ Production‑Ready — Add query-service geospatial method with favoritable intersection logic.
- [x] ✅ Production‑Ready — Add/extend feature tests for happy path, favoritable enforcement, missing-origin validation, and public-visibility enforcement.
- [x] ✅ Production‑Ready — Run targeted test suite (`./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/AccountProfiles/AccountProfilesControllerTest.php tests/Unit/Application/Accounts/AccountProfileQueryServiceTest.php ...` green).

## 6) Decision Adherence Validation
- D-01: Adherent — route added on tenant-public Account Profiles surface (`/api/v1/account_profiles/near`).
- D-02: Adherent — favoritable-only restriction enforced in query service for both list and near.
- D-03: Adherent — near query uses Mongo `$geoNear` on `account_profiles.location`.
- D-04: Adherent — response keeps account-profile payload and adds `distance_meters` with page metadata.
- D-05: Adherent — `publicNear()` now narrows to `nearEligibleProfileTypes()` (`is_favoritable=true` + `is_poi_enabled=true`) and feature coverage excludes a favoritable-but-non-POI type.
- D-06: Adherent — near query remains sorted by `distance_meters` ascending and feature coverage proves nearest-first payload order.
- D-07: Adherent — V1 geo truth remains `account_profiles.location` in `$geoNear`; POI-backed-only is enforced as a type-registry eligibility intersection, not as a distance-source replacement.
