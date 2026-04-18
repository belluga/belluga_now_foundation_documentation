# TODO (VNext): Centralized Back Navigation Governance

**Authority note (2026-04-18):** this TODO is the primary deferred owner for app-wide back-navigation policy. One-off route regressions may use separate fix TODOs only when they explicitly defer broader governance ownership to this lane rather than acting as parallel policy owners.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`
**Status:** Active
**Owners:** Flutter Team
**Objective:** Promote back navigation to a first-class architecture rule so every Flutter route uses a shared, deterministic back contract instead of ad-hoc `pop/removeLast` logic.

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

- [ ] ⚪ Pending — Define the canonical app-wide back-navigation rule in module docs.
- [ ] ⚪ Pending — Classify Flutter routes into `root-openable`, `internal-only`, or `modal/overlay`.
- [ ] ⚪ Pending — Audit tenant-public routes still outside the centralized helper.
- [ ] ⚪ Pending — Audit invite routes and freeze explicit fallback behavior for each one.
- [ ] ⚪ Pending — Audit tenant-admin routes with custom back behavior.
- [ ] ⚪ Pending — Audit shared screen shells/components for raw `pop/removeLast` ownership smells.
- [ ] ⚪ Pending — Decide whether analyzer/lint enforcement is required for route-policy violations.
- [ ] ⚪ Pending — Promote the final rule and route-classification matrix into canonical docs.

---

## Acceptance Criteria

- [ ] ⚪ Pending — Every audited route class has a documented back-governance rule.
- [ ] ⚪ Pending — Root-openable routes no longer depend on implicit stack assumptions.
- [ ] ⚪ Pending — Invite flows are explicitly classified and no longer rely on ambiguous route-policy ownership.
- [ ] ⚪ Pending — Shared shells no longer hide raw route-policy decisions in reusable widgets.
- [ ] ⚪ Pending — The project has one durable source of truth for back behavior, not multiple lane-local conventions.

---

## Definition of Done

- [ ] ⚪ Pending — Canonical route-governance rule is written and approved.
- [ ] ⚪ Pending — Route classification matrix is documented for the relevant app surfaces.
- [ ] ⚪ Pending — Invite and tenant-admin route audits are completed or explicitly split into follow-up TODOs.
- [ ] ⚪ Pending — Enforcement decision (process-only vs lint-backed) is frozen.
