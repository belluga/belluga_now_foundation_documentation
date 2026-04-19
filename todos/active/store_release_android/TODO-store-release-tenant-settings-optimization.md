# TODO (Store Release): Tenant Settings Query Optimization (Materialized Settings)

**Classification note (2026-04-18):** this moved from VNext into the Android store-release lane because app bootstrap and tenant-admin settings now depend on hot read paths that should not keep growing by live aggregation during release hardening.

**Current-state clarification (2026-04-18):** `belluga_settings` and the settings-kernel routes are already real runtime infrastructure. The remaining gap is not “settings do not exist”; it is that consumer read paths still aggregate live state instead of reading from one materialized snapshot/document.

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend Team + Platform  
**Objective:** Prevent tenant settings read paths from growing into expensive multi‑query joins by materializing a static settings document and rebuilding it asynchronously when source data changes.
**Note:** The ideas below are a starting point to ensure we remember the scaling challenge; we still need to choose the best specific approach when we tackle this VNext item.

---

## Scope
- Define a **materialized tenant settings** document (single read per tenant).
- Move settings aggregation into **background jobs** triggered on data changes.
- Establish cache + invalidation rules for settings consumers (AppData + UI registry).
- Document fallback behavior when rebuild fails (serve last valid settings).

## Out of Scope
- MVP endpoint changes.
- Full settings UI redesign.

---

## Proposed Architecture

### A) Materialized settings document
- Single collection per tenant (e.g., `tenant_settings_snapshot`).
- Contains precomputed settings used by AppData (branding, telemetry, map UI settings, profile type registry, taxonomies).

### B) Rebuild strategy
- Changes to any upstream config (branding, taxonomies, registries, flags) enqueue a **rebuild job**.
- Job recomputes the full settings doc and replaces the snapshot atomically.
- Maintain `version`, `built_at`, and `source_change_ids` for traceability.

### C) Read path
- AppData fetch reads **only** the snapshot document.
- Client uses cache‑first + async refresh (fail‑soft with last cached snapshot).

### D) Failure handling
- If rebuild fails, keep last known snapshot and surface alert for ops.
- Avoid blocking tenant reads on rebuild completion.

---

## Decisions to Close
- Location and schema for the snapshot document.
- Job trigger sources (which configs should enqueue rebuild).
- Cache TTL policy for clients and backend.
- Observability (logging + metrics) for rebuild latency and failures.
