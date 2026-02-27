# TODO (V1): Laravel Packages Multitenancy README + Skill Sync

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed
**Owners:** Backend Team
**Objective:** Make tenant/landlord migration classification explicit and mandatory in package READMEs and in the Laravel package creation workflow skill.

---

## Scope
- Update package READMEs to explicitly document tenant vs landlord migration classification and required configuration steps.
- Cover all current Belluga Laravel packages in this repository:
  - `belluga_events`
  - `belluga_settings`
  - `belluga_push_handler`
- Update the Laravel package creation workflow skill so future packages must classify scope (`tenant`, `landlord`, or mixed) before defining migrations/routes/bindings.
- Add a concise checklist in the skill for Spatie Multitenancy compatibility.

## Out of Scope
- Functional code changes in package runtime behavior.
- New migrations, route changes, or schema refactors.
- Frontend or Flutter documentation.

## Pending Decisions
- [x] ✅ Production‑Ready `D1` Scope: apply README instruction to all current Belluga packages, not only events.
- [x] ✅ Production‑Ready `D2` Skill target: update `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md` as the canonical package-creation workflow.

## Tasks
- [x] ✅ Production‑Ready Add “Multitenancy classification” section to `laravel-app/packages/belluga/belluga_events/README.md`.
- [x] ✅ Production‑Ready Add “Multitenancy classification” section to `laravel-app/packages/belluga/belluga_settings/README.md`.
- [x] ✅ Production‑Ready Add “Multitenancy classification” section to `laravel-app/packages/belluga/belluga_push_handler/README.md`.
- [x] ✅ Production‑Ready Update `delphi-ai/skills/wf-laravel-create-package-method/SKILL.md` with mandatory tenant/landlord classification gate and Spatie migration-path checklist.
- [x] ✅ Production‑Ready Review wording consistency across all four files.

## Validation Steps
- [x] ✅ Production‑Ready `rg -n "Multitenancy Classification|tenant_migration_paths|landlord|tenant" laravel-app/packages/belluga/*/README.md`
- [x] ✅ Production‑Ready `rg -n "tenant|landlord|tenant_migration_paths|Spatie|classification" /home/elton/Dev/repos/delphi-ai/skills/wf-laravel-create-package-method/SKILL.md`

## Execution Notes
- Ownership/permission blocker for `belluga_settings/README.md` was resolved and the section was applied.

## Definition of Done
- [x] ✅ Production‑Ready All three package READMEs explicitly state how to classify migrations as tenant vs landlord and where to wire each path.
- [x] ✅ Production‑Ready Laravel package creation skill explicitly enforces this decision before implementation.
- [x] ✅ Production‑Ready Guidance is consistent with current architecture (tenant-isolated DBs + Spatie multitenancy migration flow).
