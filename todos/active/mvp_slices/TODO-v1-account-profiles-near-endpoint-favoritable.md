# TODO (V1): Tenant Public Account Profiles Near Endpoint (Favoritable-Only)

## 1) Context
Enable a tenant-public GEO query directly in Account Profiles so Discovery `Próximos a você` can consume account profiles without depending on map POI endpoints.

## 2) Scope
- Add tenant-public endpoint: `GET /api/v1/account_profiles/near`.
- Endpoint must require `origin_lat` and `origin_lng`.
- Endpoint must return only `is_favoritable=true` profile types from tenant registry.
- Optional filters: `max_distance_meters`, `search`, `profile_type` (intersected with favoritable set), `page`, `page_size`.
- Return `distance_meters` per item.

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

## 5) Implementation Plan
- [x] ✅ Production‑Ready — Add request validation + controller method + route for `/account_profiles/near`.
- [x] ✅ Production‑Ready — Add query-service geospatial method with favoritable intersection logic.
- [x] ✅ Production‑Ready — Add/extend feature tests for happy path, favoritable enforcement, missing-origin validation, and public-visibility enforcement.
- [ ] 🟡 Provisional — Run targeted test suite (`php` binary unavailable in current shell; execution deferred to CI/host with PHP runtime).

## 6) Decision Adherence Validation
- D-01: Adherent — route added on tenant-public Account Profiles surface (`/api/v1/account_profiles/near`).
- D-02: Adherent — favoritable-only restriction enforced in query service for both list and near.
- D-03: Adherent — near query uses Mongo `$geoNear` on `account_profiles.location`.
- D-04: Adherent — response keeps account-profile payload and adds `distance_meters` with page metadata.
