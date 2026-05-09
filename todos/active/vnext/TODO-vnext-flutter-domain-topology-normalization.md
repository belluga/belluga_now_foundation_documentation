# TODO (VNext): Flutter Domain Topology Normalization

**Status:** Active  
**Stage:** `Backlog`  
**Owners:** Flutter Team, Delphi  
**Date:** 2026-04-18  
**Objective:** Reconcile the Flutter `lib/domain/**` topology with the canonical business model so domain entities, module-demanded projections, helpers, and view-carrier models stop sharing the same semantic layer.

## Why

- Current documentation now treats `domain_entities.md` as the canonical business model and explicitly refuses to canonize the present Flutter `lib/domain/**` topology by accident.
- The current Flutter layer has drift beyond normal projection usage:
  - helpers entered the `domain` tree,
  - module/read projections were modeled as if they were root domain entities,
  - some view-shaped carriers remained in the same semantic layer as business nouns.
- This is acceptable as current implementation reality, but it must not become the approved target architecture.
- After store delivery, the team will need a dedicated analysis/normalization moment to organize future deliveries on a cleaner semantic boundary.

## Canonical Direction

- Business entities in Flutter must reflect the project's canonical domain model rather than whatever happened to accumulate under `lib/domain/**`.
- Module-demanded read models/projections are valid, but they must be explicitly classified as projections/read models instead of being silently treated as core entities.
- Helpers, formatting carriers, and view-shaped state objects must not live indistinguishably beside canonical business aggregates/value objects.
- Repository/infrastructure parsing may still materialize projections for consumers, but those projections must be named and placed according to their real role.

## Primary Questions To Resolve

- Which current Flutter models are true business-domain entities aligned with `foundation_documentation/domain_entities.md`?
- Which current models are really:
  - module read models,
  - route/view projections,
  - helper/adapter carriers,
  - temporary legacy compatibility shells?
- Which module-owned projections should remain in Flutter, and which should instead become stricter backend-owned read contracts?
- What directory/package conventions should separate:
  - canonical entities/value objects,
  - module projections,
  - presentation-only carriers,
  - support/helpers?
- Which migrations can be done incrementally without destabilizing the shipped post-store baseline?

## Expected Outputs

- A documented classification of the current `lib/domain/**` tree by semantic role.
- A target topology proposal for Flutter domain/model organization.
- A migration plan that sequences refactors by risk and by module ownership.
- Explicit module-by-module follow-up TODOs only where the normalization work is too large for one bounded slice.
- Guardrails so future work does not reintroduce helper/projection drift into the canonical domain layer.

## Explicit Non-Goals For Current Lane

- This TODO does **not** authorize a broad Flutter domain refactor during the current store-release delivery window.
- This TODO does **not** redefine the canonical business model away from the current code-backed/project-doc baseline.
- This TODO does **not** require every projection to disappear from Flutter; it requires correct classification and boundary discipline.
- This TODO does **not** assume the current Laravel/API contract already removes every projection need from Flutter.

## Dependencies / Timing

- Run after the app-store delivery lane is complete and the team opens the planned post-release analysis window.
- Use the then-current `domain_entities.md`, relevant module docs, and real Flutter/Laravel contracts as the authority set.
- Re-check whether any store-release or fast-follow work changed the effective backend read-model boundaries before freezing the normalization plan.

## Ecosystem Impact Analysis

- **Current classification:** `Project-Local`
- **Why:** this normalization is about reconciling the Bóora!/Belluga Now Flutter client topology with this project's canonical docs and current backend contracts. It may later inform Delphi/Flutter guidance, but the first required work is downstream/project-specific.

## Success Condition

This TODO is complete when the team has a deliberate, approved normalization path for Flutter domain topology that:

- keeps canonical business entities aligned with project authority,
- preserves legitimate module/read projections where needed,
- removes semantic ambiguity between domain, projection, helper, and view-model layers,
- and prevents the current drift from being treated as the expected long-term architecture.
