# TODO (V1): Screen Polish - Discovery

**Status legend:** `- [ ] ⚪ Pending` · `- [x] ✅ Production-Ready`.
**Status:** Completed
**Owners:** Flutter Team
**Objective:** Polish the tenant-public discovery screen while preserving backend contracts and the approved Stitch-inspired hierarchy.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-targeted-visual-polish.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-account-profile-ui.md`
- `foundation_documentation/policies/scope_subscope_governance.md`

## Scope (Single Screen)
- Keep top structure with `Tocando agora` + `Perto de você` (no textual Hero block).
- Keep `Descubra` heading and category chips immediately below.
- Keep search mode behavior that hides top sections and category chrome.
- Improve loading stability and visual quality for this screen.

## Out of Scope
- Backend/API contract changes.
- New discovery features beyond approved MVP behavior.

## Decision Baseline (Frozen)
- `D-01`: This TODO keeps discovery behavior aligned with current contracts; visual polish is Flutter-side and API-compatible.
- `D-02`: Discovery hierarchy keeps `Tocando agora` + `Perto de você` and does not include textual Hero.
- `D-03`: Feed heading remains `Descubra`, with single-select category chips directly below.
- `D-04`: Search-active mode hides `Tocando agora`, `Perto de você`, `Curadores`, and also hides `Descubra` + chips.
- `D-05`: `Tocando agora` uses highlighted card style; with multiple live items, it renders as carousel.
- `D-06`: `Tocando agora` is artist-driven for MVP: render only when live-now payload contains at least one valid artist; keep section hidden when there are no artists.
- `D-07`: `Perto de você` items must show distance badge on the avatar/list tile.
- `D-08`: Loading behavior must avoid full-screen flicker/jitter during search/filter transitions and when revisiting the screen.
- `D-09`: Theme-driven colors only.

## Tasks
- [x] ✅ Production-Ready Polish discovery visual hierarchy (header, filters, cards, spacing).
- [x] ✅ Production-Ready Keep top structure with `Tocando agora` + `Perto de você`.
- [x] ✅ Production-Ready Keep heading text as `Descubra`.
- [x] ✅ Production-Ready Keep chips directly below `Descubra` with single-select behavior.
- [x] ✅ Production-Ready Keep `Tocando agora` as highlighted card and carousel when multiple live entries exist.
- [x] ✅ Production-Ready Keep `Tocando agora` hidden when live-now payload has no artists.
- [x] ✅ Production-Ready Keep search mode hiding `Tocando agora`/`Perto de você`/`Curadores`.
- [x] ✅ Production-Ready Keep search mode hiding `Descubra` + chips and showing pre-query prompt-only state.
- [x] ✅ Production-Ready Ensure `Perto de você` displays distance badges with consistent readability.
- [x] ✅ Production-Ready Refine loading transitions to avoid full-screen jitter/flicker.

## Reliability Hardening (Completed)
- [x] ✅ Production-Ready DAL `live_now_only` forwarding coverage.
- [x] ✅ Production-Ready DTO compatibility coverage for current live-now payload shape.
- [x] ✅ Production-Ready Repository forwarding/mapping coverage for `liveNowOnly`.
- [x] ✅ Production-Ready Controller regression for late `ScheduleRepository` DI registration.
- [x] ✅ Production-Ready Widget rendering test for `Tocando agora` section visibility.

## Acceptance Criteria
- [x] ✅ Production-Ready Discovery hierarchy is clear and matches approved MVP behavior.
- [x] ✅ Production-Ready Search mode behavior is visually coherent and consistent.
- [x] ✅ Production-Ready Loading/search/filter transitions are stable.
- [x] ✅ Production-Ready `Tocando agora` renders whenever live-now payload contains at least one artist-backed live item.
- [x] ✅ Production-Ready `Tocando agora` remains hidden when live-now payload has no artists.
- [x] ✅ Production-Ready `Perto de você` visibly communicates distance per item.

## Definition of Done
- [x] ✅ Production-Ready All tasks and acceptance criteria are checked with evidence.
- [x] ✅ Production-Ready Reliability hardening coverage remains green.
- [x] ✅ Production-Ready Manual evidence includes discovery default, search-active, and revisit-with-refresh behaviors.

## Validation Steps
- [x] ✅ Production-Ready Manual smoke: hierarchy (`Tocando agora`, `Perto de você`, `Descubra`, chips).
- [x] ✅ Production-Ready Manual smoke: `Tocando agora` hidden state when live-now has no artists.
- [x] ✅ Production-Ready Manual smoke: search-active and pre-query states.
- [x] ✅ Production-Ready Manual smoke: loading stability during filter/search changes.
- [x] ✅ Production-Ready Manual smoke: distance badge readability in `Perto de você`.
- [x] ✅ Production-Ready Automated: discovery controller/widget/DTO/repository/DAL suites are green.
