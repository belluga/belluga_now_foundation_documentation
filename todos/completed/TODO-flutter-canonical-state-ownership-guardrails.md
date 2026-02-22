# TODO (V1): Flutter Canonical State Ownership Guardrails + Skill Sync

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`  
**Status:** Completed  
**Owners:** Flutter Architecture / Delphi Tooling  
**Completion Date:** 2026-02-21  
**Objective (Completed Scope):** Align and synchronize canonical adherence instructions across Codex, Cline, and Antigravity surfaces, with automated parity verification integrated into readiness checks.

---

## A) Delivered
- [x] ✅ Production‑Ready Synchronize `flutter-architecture-adherence` between canonical and Cline copies.
- [x] ✅ Production‑Ready Synchronize Cline overlap skills (`9/9`) with canonical `delphi-ai/skills/*` content.
- [x] ✅ Production‑Ready Synchronize Antigravity adherence rule/workflow wording through `delphi-ai/rules/*` + `delphi-ai/workflows/*` and generated `.agent/*` parity.
- [x] ✅ Production‑Ready Add automatic adherence parity script: `delphi-ai/tools/verify_adherence_sync.sh`.
- [x] ✅ Production‑Ready Wire parity verification into readiness flow: `delphi-ai/tools/verify_context.sh`.
- [x] ✅ Production‑Ready Update architecture wording to enforce:
  - Controller as the only presentation data ingress gate.
  - Repository ownership for canonical shared state.
  - Service/DAL as technical adapters only (no canonical state ownership).

## B) Validation Run Notes
- `bash ../delphi-ai/tools/verify_adherence_sync.sh` ✅
- `bash delphi-ai/tools/verify_context.sh` ✅  
  Note: known environment permission warnings persist for `laravel-app/.agent/rules/*` sync, without blocking adherence verification.

## C) Follow-up
- Execution of skills and app-level refactors is intentionally out of scope for this session and will run in a separate process.
