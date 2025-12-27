# TODO (Upstream): Profile Endpoint (/api/v1/me)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Backend Team (Upstream)  
**Objective:** Deliver a boilerplate-generic `/api/v1/me` endpoint for tenant apps.

---

**scope:** Implement `/api/v1/me` in the upstream Laravel boilerplate using the MVP contract in `foundation_documentation/endpoints_mvp_contracts.md`, including user level, privacy mode, social score, counters, and role claims.  
**out_of_scope:** Tenant-specific fields, partner discovery payloads, or any project-only claims.  
**definition_of_done:** Endpoint is available in upstream, protected by Sanctum, returns the full contract schema with stable field names and enums, and includes `tenant_id` for tenant-scoped calls.  
**validation_steps:** Contract tests validate schema + enum values; a sample authenticated request returns all required fields.

---

## References
- `foundation_documentation/endpoints_mvp_contracts.md`
- `foundation_documentation/system_roadmap.md`

---

## A) Backend Tasks

### A1) Endpoint contract
- [x] ✅ Production‑Ready Implement `GET /api/v1/me` per MVP contract.
- [x] ✅ Production‑Ready Return `tenant_id` for tenant-scoped calls.
- [x] ✅ Production‑Ready Enforce enum defaults (`user_level`, `privacy_mode`) as documented.

### A2) Auth + abilities
- [x] ✅ Production‑Ready Require `auth:sanctum`.
- [x] ✅ Production‑Ready Ability checks beyond `auth:sanctum` are not required for `/api/v1/me` because the endpoint is self‑scoped (returns only the authenticated user).

---

## B) Acceptance Criteria

- [x] ✅ Production‑Ready `/api/v1/me` returns the documented schema (no missing keys).
- [x] ✅ Production‑Ready Contract tests pass in upstream boilerplate.

**Completion Notes**
- completion_metadata: branch=env-routes-fix, commit=31632d0880f4c48562891f019b51c24b976e6e77
