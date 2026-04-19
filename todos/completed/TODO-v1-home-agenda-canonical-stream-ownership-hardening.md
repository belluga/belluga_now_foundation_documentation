# TODO (V1): Home Agenda Canonical Stream Ownership Hardening

**Status:** Completed (`authority reconciled and closed on 2026-04-18`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Single-Writer-Repository-Owned`, `Rule-Governed`, `Closure-Synced`
**Next exact step:** None.
**Owners:** Flutter Team, Architecture Governance
**Objective:** Canonicalize Home Agenda aggregate ownership so Home uses one repository-owned canonical stream, controller-local projections remain local, query pagination stays repository-internal, and Home radius refreshes settle through the canonical repository-backed path with explicit loading feedback.

---

## Closure Note

This lane is no longer an active delivery owner. The repo already reflects the intended end state:

- Home Agenda exposes one public canonical repository stream: `homeAgendaStreamValue`.
- repository cache/query state is private implementation state, not a second public source of truth.
- Home controller invite/confirmed filtering publishes only local display state and does not write event lists back into repository-owned streams.
- search/query pagination state is private to repository query state rather than shared public scratch state.
- Home radius changes reconcile through the repository-backed refresh path and expose explicit loading feedback while unsettled.

The remaining governance extracted from this bug is already codified as local rules and analyzer-enforced guardrails where appropriate. There is no evidence-based reason to keep this TODO active as a separate structural-hardening lane.

## Last Confirmed Truth

As of `2026-04-18`, the current repo supports closure:

- [schedule_repository_contract.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/domain/repositories/schedule_repository_contract.dart:15) exposes one canonical Home agenda stream plus semantic repository intents (`loadHomeAgenda`, `loadMoreHomeAgenda`) rather than raw pagination controls.
- [schedule_repository.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/infrastructure/repositories/schedule_repository.dart:31) keeps `_homeAgendaState` and event-search query state private and publishes only the canonical Home aggregate stream.
- [tenant_home_agenda_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart:625) derives controller-local display state from canonical repository state instead of writing back into repository event streams.
- [tenant_home_agenda_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart:740) and [tenant_home_agenda_controller.dart](/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/flutter-app/lib/presentation/tenant_public/home/screens/tenant_home_screen/widgets/agenda_section/controllers/tenant_home_agenda_controller.dart:907) implement radius refresh settlement with explicit loading feedback.
- Canonical module decisions `HOM-07`, `HOM-08`, `AGD-07`, `AGD-08`, `AGD-10`, `FCX-08`, `FCX-09`, and `FCX-10` already preserve the ownership boundary in project authority.
- Analyzer/plugin rule surfaces already cover the main regression families involved in this bug, including controller-to-controller dependency, descendant widget-controller resolution leakage, widget-controller singleton leakage, controller `StreamValue` parameter misuse, and repository pagination-control leakage.

## Outcome Summary

- One aggregate, one public canonical repository stream for Home Agenda.
- Controller-local invite/confirmed filtering remains local display-state projection only.
- Repository pagination/query helpers stay repository-internal.
- Radius-triggered Home refresh is canonical and visibly acknowledged in the UI.
- Future regressions are governed by module rules plus analyzer guardrails, not by a still-open tactical TODO.

## Historical Context

The active TODO remained open after the structural fix and rule promotions had already landed. During authority reconciliation, it had become a stale umbrella rather than a real remaining delivery lane. This closure records the current truth and prevents the old TODO from continuing to act as false active authority.
