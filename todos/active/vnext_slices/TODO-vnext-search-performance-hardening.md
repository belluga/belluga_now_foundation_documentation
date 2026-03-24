# TODO (VNext): Search Performance Hardening

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Active  
**Owners:** Laravel Team, Flutter Team  
**Objective:** Replace MVP regex-contains text search with an indexed strategy that keeps behavior (`contains`/partial) while reducing query cost.

---

## Context
- MVP currently uses regex-based `contains` search (`%term%`) for accounts, account profiles, static assets, events, and map textual filters.
- This is acceptable for MVP behavior but can degrade performance/cost at scale.

## Scope
- Define canonical search strategy for VNext:
  - Option A: materialized normalized prefix/token fields + regular indexes.
  - Option B: Mongo Atlas Search (`autocomplete` / edge n-gram equivalent).
- Keep compatibility with geospatial constraints (`$geoNear` first-stage rule).
- Add measurable performance gates and regression coverage.

## Tasks
- [ ] ⚪ Pending — Benchmark current regex-contains search on representative tenant datasets (p50/p95 latency + scan ratio).
- [ ] ⚪ Pending — Choose canonical indexed search architecture (A or B) with explicit cost/performance tradeoff.
- [ ] ⚪ Pending — Implement tenant-aware migrations/index bootstrap for chosen strategy.
- [ ] ⚪ Pending — Migrate query services (`accounts`, `account_profiles`, `static_assets`, `events`, `map`) off regex-contains hot paths.
- [ ] ⚪ Pending — Add regression + performance tests and rollout safeguards (feature flag/canary if needed).

## Acceptance Criteria
- [ ] ⚪ Pending — Partial search behavior remains equivalent to MVP user experience.
- [ ] ⚪ Pending — Queries use indexed plans in targeted routes (no broad collection scans on hot paths).
- [ ] ⚪ Pending — Measured p95 latency and infrastructure cost are improved versus MVP baseline.

