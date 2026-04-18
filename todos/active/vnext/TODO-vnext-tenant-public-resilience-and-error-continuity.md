# TODO (VNext): Tenant Public Resilience and Error Continuity

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production-Ready`  
**Status:** Active  
**Owners:** Flutter Team, Laravel Team  
**Objective:** Establish a canonical resilience model so tenant-public app surfaces remain usable and recoverable under transient auth/backend/network failures.

---

## Context
- MVP fixed tenant-public auth guard mismatch for first-page requests (`AccountUser` token scope + tenant guard handling).
- Remaining risk is systemic continuity: if first page fails (auth refresh race, transient 5xx/timeout, degraded backend), parts of Discovery/Home can remain blocked or ambiguous to users.
- This scope was intentionally superseded from `TODO-v1-admin-discovery-map-small-fixes.md` (`J.2`) to avoid shipping ad-hoc retries and to design a coherent VNext resilience baseline.

## Scope
- Define canonical tenant-public bootstrap/error-state contract for first-page and pagination failures.
- Standardize retry policy (attempt budget, backoff, jitter, and retry eligibility matrix by error class).
- Standardize user-facing fallback states (`loading`, `retryable error`, `non-retryable error`, `degraded mode`) across Discovery and Home agenda.
- Align backend error taxonomy for tenant-public endpoints (`401/403/429/5xx/timeout`) with deterministic client handling.
- Add observability and diagnostics requirements (correlation id, error class tagging, retry counters).
- Add regression tests that assert continuity behavior, not just happy-path data rendering.

## Out of Scope
- Search performance/index architecture changes (tracked in `TODO-vnext-search-performance-hardening.md`).
- New product features unrelated to resilience/continuity.
- Map icon/color architecture refactor.

## Tasks
- [ ] ⚪ Pending — Define resilience decision matrix for tenant-public controllers (Discovery/Home): when to retry, when to stop, when to degrade, and when to force re-auth.
- [ ] ⚪ Pending — Implement a shared Flutter resilience primitive for paginated first-page flows (instead of per-controller custom retry logic).
- [ ] ⚪ Pending — Apply the resilience primitive to Discovery first-page bootstrap and Home agenda first-page bootstrap.
- [ ] ⚪ Pending — Define and implement backend error-shape normalization for tenant-public endpoints required by the resilience matrix.
- [ ] ⚪ Pending — Add regression test suites (Flutter + Laravel) covering transient failures, auth edge cases, and deterministic recovery paths.
- [ ] ⚪ Pending — Add runbook/diagnostic notes for production triage of tenant-public degraded states.

## Acceptance Criteria
- [ ] ⚪ Pending — Discovery/Home never remain in infinite loading after first-page failure.
- [ ] ⚪ Pending — Retry behavior is deterministic and bounded (no hidden infinite retries).
- [ ] ⚪ Pending — Users always receive an actionable state (`retry`, `sign-in required`, or degraded-mode explanation).
- [ ] ⚪ Pending — Regression tests fail if controllers regress to non-terminating load states or inconsistent recovery.
- [ ] ⚪ Pending — Observability artifacts allow root-cause classification without ad-hoc reproduction.

## Traceability
- Supersedes V1 tactical gap: `TODO-v1-admin-discovery-map-small-fixes.md` → Workstream `J.2`.
