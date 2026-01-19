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

## Out of Scope
- Refactors that change public APIs without a feature decision.
- Performance rewrites or architectural migrations.

## Success Criteria
- No unused summary/agenda artifacts remain in MVP codebase.
- Tests pass with clean contracts and reduced dead code.
