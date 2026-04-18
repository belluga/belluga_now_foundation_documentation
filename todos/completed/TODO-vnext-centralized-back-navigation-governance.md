# TODO (VNext): Centralized Back Navigation Governance

**Authority note (2026-04-18):** this TODO is the primary deferred owner for app-wide back-navigation policy. One-off route regressions may use separate fix TODOs only when they explicitly defer broader governance ownership to this lane rather than acting as parallel policy owners.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Completed (`superseded by delivered canonical cutover and promoted module contract`)
**Owners:** Flutter Team
**Objective:** Promote back navigation to a first-class architecture rule so every Flutter route uses a shared, deterministic back contract instead of ad-hoc `pop/removeLast` logic.

**Resolution note (2026-04-18):** this ledger is no longer an active `vnext` owner. The delivered cutover now lives in `foundation_documentation/todos/completed/TODO-v1-canonical-back-navigation-governance-cutover.md`, and the canonical promoted contract lives in `foundation_documentation/modules/flutter_client_experience_module.md` (`FCX-07`). This file is retained only as the historical deferred-owner precursor that froze the route classes and early policy shape before delivery.

---

## Context

- MVP fixed the highest-risk `tenant_public` dead-end routes through a shared safe-back helper and an approved fallback matrix for:
  - `/agenda`
  - `/agenda/evento/:slug`
  - `/parceiro/:slug`
  - `/mapa`
  - `/mapa/poi`
- That MVP lane was intentionally narrow. It solved real production-risk surfaces, but it did **not** convert the entire app into one centralized navigation-governance model.
- Invite flows already show the next architectural gap:
  - `/invite` and `/convites` use acceptable local `canPop -> pop -> TenantHomeRoute` behavior,
  - `/convites/compartilhar` still depends on implicit AppBar back behavior,
  - those routes were not audited under the same centralized rule.
- The product direction is now explicit: centralization of back behavior must become a durable app-wide rule, not a one-off fix.

---

## Scope

- Define one canonical Flutter back-navigation contract for route surfaces.
- Classify route surfaces by back-governance requirements:
  - `root-openable`
  - `internal-only`
  - `modal/sheet/dialog`
- Require explicit ownership of:
  - local-state consumption,
  - stack-preserving return,
  - no-history fallback.
- Expand the audit beyond the MVP tenant-public lane to include at minimum:
  - invite surfaces (`/invite`, `/convites`, `/convites/compartilhar`)
  - remaining tenant-public routes not yet normalized
  - tenant-admin routes with custom back behavior
  - any shared shells/components that currently own raw back behavior
- Define which route classes may still use simplified `maybePop/pop` semantics and under what documented conditions.
- Evaluate whether the rule should be guarded by analyzer/lint or project-level route review gates.

## Out of Scope

- Breaking route-path redesign.
- URL-contract changes.
- New deep-link product capabilities.
- Immediate implementation across every route in the same lane.
- Browser-history redesign outside Flutter route-governance responsibilities.

---

## Decision Baseline (Frozen)

- `D-01`: Back navigation is an architecture concern, not a per-screen stylistic choice.
- `D-02`: Any route that can be entered as root, via URL, deep link, refresh, or indirect push entry must use a centralized safe-back contract.
- `D-03`: The canonical order remains:
  1. consume route-local state when the screen explicitly owns it
  2. `canPop() -> pop()` when real stack history exists
  3. explicit route fallback when no history exists
- `D-04`: System back, visible back buttons, and shared-shell back controls on the same route must delegate to the same policy.
- `D-05`: Raw `pop()` / `removeLast()` is not acceptable as final route-decision logic on root-openable surfaces.
- `D-06`: Every audited root-openable route must declare a deterministic no-history fallback.
- `D-07`: Internal-only routes may use a simplified contract only if they are explicitly classified and cannot become root-opened accidentally without a safe fallback.
- `D-08`: Modal/sheet/dialog surfaces remain a separate class and may use `maybePop` semantics where appropriate.
- `D-09`: Invite flows are explicitly included in the next audit; current MVP acceptance does not exempt them from future normalization.
- `D-10`: This VNext lane must decide whether enforcement remains documentation/process-only or becomes analyzer/lint-backed.

---

## Proposed Route Classes

### A. Root-Openable
- Examples:
  - tenant-public discovery/detail/map/event routes
  - invite entry surfaces
  - any web-allowlisted direct URL route
- Requirements:
  - centralized back contract mandatory
  - explicit no-history fallback mandatory
  - parity across system back and visible back mandatory

### B. Internal-Only
- Examples:
  - routes that are only reachable from an existing in-app flow and are not meant to survive direct open independently
- Requirements:
  - explicit classification required
  - if accidental direct-open is possible, fallback strategy must still be defined

### C. Modal / Overlay
- Examples:
  - sheets, dialogs, transient overlays
- Requirements:
  - may use `maybePop`
  - do not inherit root-route fallback responsibilities

---

## Target Audit Surfaces

- Tenant Public
  - invite routes
  - remaining profile/home/discovery/detail entrypoints not normalized under the shared helper
- Tenant Admin
  - shell back behaviors
  - nested route returns
  - subpage back affordances
- Shared Widgets / Shells
  - any reusable screen shell or container with its own back button
  - any component still calling `context.router.pop()` as route policy instead of route action

---

## Invite Feedback Snapshot (Promoted)

- `/invite` and `/convites`
  - current local behavior is acceptable for MVP
  - still should be normalized under the centralized rule in VNext
- `/convites/compartilhar`
  - current behavior is less robust because it relies on implicit AppBar back semantics
  - should be explicitly classified as `internal-only` or migrated to the centralized safe-back rule with a declared fallback (likely `InviteFlowRoute`)

---

## Tasks

- [x] ✅ Production-Ready — Canonical app-wide back-navigation rule was promoted through the delivered cutover and module contract.
- [x] ✅ Production-Ready — Route classes were frozen and operationalized in the delivered cutover.
- [x] ✅ Production-Ready — Tenant-public, invite, tenant-admin, shared-shell, and route-governance audit fronts were absorbed by the delivered cutover.
- [x] ✅ Production-Ready — The finalized contract was promoted into canonical docs and runtime governance helpers.

---

## Acceptance Criteria

- [x] ✅ Production-Ready — Every audited route class now has a documented back-governance rule through the delivered cutover and promoted module contract.
- [x] ✅ Production-Ready — Root-openable routes no longer depend on implicit stack assumptions inside the canonical governance path.
- [x] ✅ Production-Ready — Invite flows and shared shells are covered by the delivered cutover contract.
- [x] ✅ Production-Ready — The project now has one promoted source of truth for back behavior (`flutter_client_experience_module.md` + delivered cutover).

---

## Definition of Done

- [x] ✅ Production-Ready — Canonical route-governance rule is written and approved.
- [x] ✅ Production-Ready — Route classification matrix is documented for the relevant app surfaces.
- [x] ✅ Production-Ready — Invite and tenant-admin route audits were absorbed by the delivered cutover.
- [x] ✅ Production-Ready — The structural enforcement path is frozen in the promoted contract/cutover trail.
