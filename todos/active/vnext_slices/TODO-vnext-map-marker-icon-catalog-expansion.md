# TODO (VNext): Map Marker Icon Catalog Expansion

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Flutter Team + Product + Design  
**Objective:** Expand marker icon coverage (including optional custom font/icon pack) without breaking persisted V1 icon tokens already stored in POI/filter configuration.

---

## References
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-map-icon-color-config.md`

## Scope (VNext)
- Expand icon catalog beyond MVP groups for domains currently under-covered (for example nightlife, café/bakery, mobility/transport, family/kids, sports variants).
- Evaluate introducing custom font/icon pack for stronger semantic and brand coverage.
- Keep token compatibility strict:
  - existing V1 keys remain valid forever;
  - new icons are additive only.

## Decisions to Close
- [ ] ⚪ Confirm if VNext will keep Material-only glyphs or introduce custom icon font/pack.
- [ ] ⚪ Define catalog governance (who can add new keys, naming convention, review process).
- [ ] ⚪ Define rollout policy for legacy alias cleanup (if any) while preserving backward compatibility.
- [ ] ⚪ Define visual QA baseline per group (contrast, readability, map pin legibility).

## Out of Scope
- Replacing existing persisted keys.
- Runtime migration requiring destructive rewrite of stored marker tokens.
- Non-map icon systems unrelated to POI/filter marker visuals.

---

## Tasks
- [ ] ⚪ Expand enum-backed catalog with new additive canonical keys.
- [ ] ⚪ Keep/extend alias map for legacy key compatibility.
- [ ] ⚪ If custom font is approved, add glyph-source adapter while preserving `storage_key`.
- [ ] ⚪ Add/update tests validating:
  - legacy key resolution;
  - new key resolution;
  - fallback behavior for unknown keys.
- [ ] ⚪ Update admin icon picker groups and labels with new catalog entries.

## Acceptance Criteria
- [ ] ⚪ Existing persisted tokens from V1 render identically after VNext catalog changes.
- [ ] ⚪ New icons are available in picker without changing existing payload contracts.
- [ ] ⚪ Unknown tokens still resolve to deterministic generic fallback.

## Definition of Done
- [ ] ⚪ Catalog expansion is additive and backward-compatible.
- [ ] ⚪ Resolver + picker + tests are aligned and green.
- [ ] ⚪ Documentation reflects final VNext catalog governance.
