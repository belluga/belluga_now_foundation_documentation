# TODO (V1): Code Cleanup — Unused Widgets & Contracts

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.  
**Status:** Active  
**Owner:** Delphi  
**Date:** 2026-01-19

## Objective
Remove unused widgets, DTOs, repository methods, and mock payloads to reduce maintenance cost and prevent stale contracts.

## Scope (MVP)
- Identify unused UI widgets/components and remove them.
- Remove unused DTOs/models/contracts that are not referenced by the current UI flows.
- Remove unused mock payloads/fields if not part of MVP contracts.
- Update tests to reflect removals (no workarounds; ensure failures reveal real issues).

## Candidates (Initial List)
- Agenda summary (schedule summary) DTOs + repository methods + mock backend (`fetchSummary`), if unused by current UI.
- Any unused event/agenda widgets that are not referenced by schedule or home flows.
- `lib/presentation/prototypes/map_debug/map_debug_screen.dart` (no route or usage; remove if still unused).

## Tasks
- [ ] ⚪ Pending Confirm runtime usage for `fetchSummary` across controllers/routes/screens before removal.
- [ ] ⚪ Pending If `fetchSummary` is unused, remove contract + implementations + tests/mocks tied only to summary flow.
- [ ] ⚪ Pending Confirm whether `lib/presentation/prototypes/map_debug/map_debug_screen.dart` is intentionally kept as a local prototype.
- [ ] ⚪ Pending If map debug screen is not intentional, remove file and any dead imports/tests.
- [ ] ⚪ Pending Run dead-code pass for unused agenda/event widgets and remove only those without active route/controller ownership.
- [ ] ⚪ Pending Run/update tests affected by removals (no skip/fallback masking).

## Out of Scope
- Refactors that change public APIs without a feature decision.
- Performance rewrites or architectural migrations.

## Success Criteria
- No unused summary/agenda artifacts remain in MVP codebase.
- Tests pass with clean contracts and reduced dead code.

## Validation
- [ ] ⚪ Pending `rg`/route audit proves removed artifacts are not referenced by active flows.
- [ ] ⚪ Pending `fvm flutter analyze` passes clean.
- [ ] ⚪ Pending Targeted tests for affected modules pass with no skip/workaround.
