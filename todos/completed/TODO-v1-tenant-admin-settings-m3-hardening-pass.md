# TODO (V1): Tenant Admin Settings — Material 3 Hardening Pass

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`
**Status:** Completed  
**Owner:** Flutter Team  
**Date:** 2026-02-20

## Objective
Apply strict Material 3 behavior and visuals to Tenant Admin Settings hub while preserving hierarchy strategy, routes, fields, and contracts.

## Scope
- Flutter presentation-only updates in `tenant_admin/settings` hub layout.
- Keep multi-screen routing and existing data bindings.
- Enforce M3 primitives and semantics on top area, cards, labels, and action hierarchy.

## Out of scope
- API/domain contract changes.
- Route architecture changes.
- Dedicated screen behavior changes beyond visual consistency tweaks.

## Decisions
- [x] ✅ Production‑Ready No colored app bar.
- [x] ✅ Production‑Ready Single top hierarchy source (avoid duplicate large titles).
- [x] ✅ Production‑Ready Remove non-M3 custom label overlays that break legibility.

## Plan
### Phase 1 — Top Hierarchy Cleanup
- [x] ✅ Production‑Ready Remove duplicate content title in hub and keep one clear top hierarchy.
- [x] ✅ Production‑Ready Keep tenant context in a single, consistent place (no competing duplicate context labels).

### Phase 2 — M3 Component Hardening
- [x] ✅ Production‑Ready Replace fragile custom hex label treatment with M3-consistent label/value presentation.
- [x] ✅ Production‑Ready Rebalance CTA emphasis (`Configurar`) so action weight is clear without visual competition.
- [x] ✅ Production‑Ready Ensure cards/rows/buttons use M3-consistent spacing, shapes, and typography.

### Phase 3 — Validation
- [x] ✅ Production‑Ready Update impacted widget tests.
- [x] ✅ Production‑Ready `fvm flutter analyze`
- [x] ✅ Production‑Ready `fvm flutter test test/presentation/tenant_admin/settings/tenant_admin_settings_screen_test.dart`

### Phase 4 — Strategy Reinforcement (Hub as Decision Surface)
- [x] ✅ Production‑Ready Remove full-edit style controls from hub where they blur multi-screen strategy.
- [x] ✅ Production‑Ready Keep hub cards as concise preview + clear `Configurar` CTA.
- [x] ✅ Production‑Ready Preserve dedicated screens as the full editing surfaces.
- [x] ✅ Production‑Ready Re-run `fvm flutter analyze` and settings widget tests after this reinforcement pass.

### Phase 5 — Visual Parity Pass (Reference-Like)
- [x] ✅ Production‑Ready Reintroduce inline `Tema` segmented control and `Raio do mapa` slider in the first card to match the target composition.
- [x] ✅ Production‑Ready Keep `Identidade Visual` with side color swatches + bordered hex fields, without label overlap artifacts.
- [x] ✅ Production‑Ready In `Integrações Técnicas`, keep row-based integrations with right-aligned `Configurar` pills per row (Firebase + Telemetry preview rows).
- [x] ✅ Production‑Ready Keep Material 3 constraint: neutral app bar surface (no colored app bar), while matching the rest of the reference hierarchy.
- [x] ✅ Production‑Ready Re-run `fvm flutter analyze` and settings widget tests after parity updates.

### Phase 6 — Widget Extraction From Reference Patterns
- [x] ✅ Production‑Ready Extract reusable admin widgets inspired by `flutter-admin-panel` composition patterns (card shell, section header treatment, row item + action pill).
- [x] ✅ Production‑Ready Implement reusable widgets under `lib/presentation/tenant_admin/shared/widgets/` and apply them in settings hub.
- [x] ✅ Production‑Ready Ensure style consistency (radius, spacing, typography) across all three hub cards using the new widgets.
- [x] ✅ Production‑Ready Keep behavior/contracts unchanged; only presentation/widget structure improves.
- [x] ✅ Production‑Ready Re-run `fvm flutter analyze` and settings widget tests after extraction pass.

### Phase 7 — One Widget Per File Compliance
- [x] ✅ Production‑Ready Split `tenant_admin_hub_widgets.dart` into one widget per file under `lib/presentation/tenant_admin/shared/widgets/`.
- [x] ✅ Production‑Ready Keep optional barrel export file only for imports; no widget class declarations in the barrel.
- [x] ✅ Production‑Ready Update settings hub imports and keep behavior unchanged.
- [x] ✅ Production‑Ready Re-run `fvm flutter analyze` and settings widget tests after split.

## Definition of done
- [x] ✅ Production‑Ready Hub follows hierarchy strategy with strict M3 visual/interaction semantics.
- [x] ✅ Production‑Ready No duplicate top headings or tenant context duplication.
- [x] ✅ Production‑Ready No broken/overlapping field labels.
- [x] ✅ Production‑Ready Analyzer/tests pass for touched scope after Phase 7.
