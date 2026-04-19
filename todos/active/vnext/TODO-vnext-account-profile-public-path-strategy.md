# TODO (VNext): Account Profile Public Path Strategy

**Status:** Active  
**Stage:** `Backlog`  
**Owners:** Laravel Team, Flutter Team  
**Objective:** Define the canonical public-path/permalink strategy for Account Profiles so slug mutability, type-aware routing, and future permanent-link behavior do not drift into ad hoc link semantics.

## Why

- This TODO intentionally supersedes the older "slug projection resync" framing rather than conflicting with it.
- "Resync" is only one possible downstream consequence if the canonical link remains slug-derived; it is no longer assumed as the problem statement.
- Basic slug mutation already exists in current admin/runtime surfaces, so the unresolved question is no longer "projection resync by default" as a standalone problem.
- The real open decision is the public link contract:
  - slug-only path,
  - type-aware/dynamic path,
  - or another stable permanent-link strategy that survives future type/slug evolution.
- The module discussion already raised that public links may need to remain stable even if type semantics or slug semantics evolve later.

## Required Follow-Up

- Freeze the canonical public-path strategy for Account Profiles.
- Decide whether public routes remain:
  - slug-only,
  - type-aware,
  - or a stable permanent-link shape that is independent from future type changes.
- Define redirect/alias behavior when slug changes.
- If the chosen contract remains slug-derived or type-aware, absorb the old "resync" concern here by defining the downstream refresh/materialization policy required to keep public path data coherent.
- Define whether any downstream read-model refresh is required by the chosen path strategy or whether canonical link stability should be solved by alias/permalink semantics instead.
- Add fail-first coverage for the chosen public-path contract once implementation starts.

## Explicit Non-Goals For Current Lane

- This TODO does **not** weaken the current runtime contract.
- This TODO does **not** assume that automatic projection resync is the right answer; it exists to decide the path strategy first.
- Current V1 remains strict where public payloads require canonical slug/path data to be present.
