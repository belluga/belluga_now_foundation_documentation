# TODO (VNext): RAW -> DTO -> Domain Parse Hardening

**Status:** Active (`Planning`)  
**Owners:** Flutter Team + Backend Team  
**Objective:** Eliminate silent parse regressions by adding exhaustive unit coverage for RAW -> DTO and DTO -> Domain/Projection conversion paths, including edge/error semantics.
**Complexity:** `big`

## Goal
Guarantee that schema drift, nullability mismatches, malformed payloads, optional-field regressions, and partial backend responses fail in deterministic, tested ways instead of surfacing as silent UI empty states or swallowed controller errors.

## Scope
1. Build a coverage matrix for every active RAW -> DTO and DTO -> Domain/Projection path in `flutter-app`.
2. Add unit tests for:
   - happy path,
   - optional-null path,
   - missing-field path,
   - malformed-type path,
   - incompatible-shape path,
   - explicit fail-fast expectations where parsing must reject invalid data.
3. Standardize parse semantics per path:
   - optional fields tolerated only when contract allows,
   - required fields fail deterministically,
   - silent fallback behavior documented and tested.
4. Add regression coverage for known weak spots already observed in production-like flows:
   - agenda/event card parsing,
   - artist/friend/avatar parsing,
   - tenant-admin settings payload parsing,
   - events/admin list payload parsing,
   - environment/bootstrap payload parsing.
5. Promote resulting contract decisions back into canonical docs and submodule summaries.

## Out Of Scope
- Broad product logic changes unrelated to parsing contracts.
- Backend backward compatibility shims for obsolete payloads.
- Rewriting all repositories/controllers in this TODO unless required by contract clarification.

## Why Now
Recent regressions showed that a single nullability mismatch in conversion (`artist.avatar_url = null`) can surface as an empty Home agenda while the backend is healthy. The current test suite is too path-specific and does not systematically protect conversion boundaries.

## Definition Of Done
- Every active conversion path has an explicit test inventory.
- Critical user journeys no longer rely on incidental coverage for parser safety.
- Edge/error cases are asserted, not ignored.
- Parse rules are documented as contract decisions, not left implicit in implementation.
- New regressions of the same class are expected to fail in unit tests before UI/integration symptoms appear.

## Initial Workstreams
### WS-01 Coverage Inventory
- [ ] Enumerate every active RAW -> DTO entry point.
- [ ] Enumerate every DTO -> Domain/Projection mapper.
- [ ] Mark each path as `covered`, `partially covered`, or `uncovered`.

### WS-02 Contract Classification
- [ ] Define required vs optional fields per path from authoritative docs/backend contracts.
- [ ] Identify paths currently relying on permissive fallback behavior.
- [ ] Flag contract mismatches that require backend doc clarification.

### WS-03 Unit Hardening
- [ ] Add missing unit tests for high-risk event/agenda/admin/environment payloads.
- [ ] Add edge/error assertions for null, missing, malformed, and cross-shape values.
- [ ] Ensure test names encode the contract and expected parser behavior.

### WS-04 Governance
- [ ] Reflect parser contract decisions in `foundation_documentation/`.
- [ ] Update `submodule_flutter-app_summary.md` once the hardening lands.
- [ ] Audit whether any custom lint or static rule can enforce unsafe parse patterns.

## Seed Defect Inventory
- `artist.avatar_url = null` caused Home agenda to render empty despite successful API payload.
- `map_ui: []` payload caused namespace contamination in tenant-admin settings update flow.
- Similar risks likely exist anywhere `URIValue.parse`, enum coercion, or direct map indexing assume happy-path types.

## Validation Strategy
- `flutter test` targeted unit suites per conversion family.
- `flutter analyze` on touched parsing/mapping paths.
- Optional promotion gates later: selected integration tests for journeys previously broken only by parse issues.

## Execution Rule
This TODO is planning-only for now. No execution begins until a separate approval is given after the current regression-fix promotion is completed.
