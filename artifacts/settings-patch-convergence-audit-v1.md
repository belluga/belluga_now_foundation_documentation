# Settings PATCH Convergence Audit (V1)

**Date:** 2026-02-26  
**Scope:** `laravel-app` (`routes/` + `packages/*/routes`)  
**Method:** route inventory using `rg -n "Route::patch\(|->patch\(|'PATCH'"`

## Canonical Rule Reviewed
- For **settings namespace PATCH contracts**, canonical shape is direct object payload (no envelope), field-presence merge, nullable-only clear via `null`, deterministic `422` for invalid clear/type.
- For **non-settings resource PATCH endpoints**, resource-specific contracts are allowed and classified as explicit exceptions to the settings-kernel canonical PATCH contract.

## Inventory

| Endpoint | Owner | Status | Notes |
|---|---|---|---|
| `PATCH /api/v1/settings/values/{namespace}` | `belluga_settings` tenant | `converged` | Direct object payload, no envelope, field-presence merge, nullable clear semantics validated. |
| `PATCH /admin/api/v1/settings/values/{namespace}` | `belluga_settings` landlord | `converged` | Same canonical semantics in landlord scope. |
| `PATCH /admin/api/v1/{tenant_slug}/settings/values/{namespace}` | `belluga_settings` landlord on-behalf tenant | `converged` | Same canonical semantics, tenant scope via context adapter. |
| `PATCH /api/v1/settings/push` | `belluga_push_handler` tenant | `converged` | Direct object payload (`max_ttl_days`, `throttles`), explicit disallowed-key errors, field-presence semantics. |
| `PATCH /api/v1/settings/firebase` | `belluga_push_handler` tenant | `converged` | Direct object payload for firebase fields, explicit disallowed-key errors, field-presence semantics. |
| `PATCH /admin/api/v1/{tenant_slug}/settings/push` | `belluga_push_handler` landlord on-behalf tenant | `converged` | Same convergence rules as tenant endpoint. |
| `PATCH /admin/api/v1/{tenant_slug}/settings/firebase` | `belluga_push_handler` landlord on-behalf tenant | `converged` | Same convergence rules as tenant endpoint. |
| `PATCH /api/v1/settings/push/route_types` | `belluga_push_handler` tenant | `exception` | Collection patch-by-key contract (list payload), domain-specific for route catalog merge semantics. |
| `PATCH /api/v1/settings/push/message_types` | `belluga_push_handler` tenant | `exception` | Collection patch-by-key contract (list payload), domain-specific for message-type catalog merge semantics. |
| `PATCH /api/v1/push_messages/{push_message_id}` | `belluga_push_handler` account scope | `exception` | Resource update endpoint (message aggregate), not namespace settings PATCH contract. |
| `PATCH /api/v1/tenant/push_messages/{push_message_id}` | `belluga_push_handler` tenant scope | `exception` | Resource update endpoint (message aggregate), not namespace settings PATCH contract. |
| `PATCH /api/v1/profile/password` (tenant maybe) | core | `exception` | Resource action endpoint (`password` mutation), not settings namespace semantics. |
| `PATCH /api/v1/profile` (tenant maybe) | core | `exception` | User profile resource patch, contract is aggregate-specific. |
| `PATCH /api/v1/profile/emails` (tenant maybe) | core | `exception` | Collection mutation endpoint (emails list semantics). |
| `PATCH /api/v1/profile/phones` (tenant maybe) | core | `exception` | Collection mutation endpoint (phones list semantics). |
| `PATCH /api/v1/accounts/{account_slug}/users/{user_id}` | core | `exception` | Account user resource update contract, not settings namespace. |
| `PATCH /api/v1/accounts/{account_slug}/roles/{role_id}` | core | `exception` | Role template resource update contract, not settings namespace. |
| `PATCH /api/v1/account/{account_slug}` (tenant admin) | core | `exception` | Account aggregate update contract, not settings namespace. |
| `PATCH /api/v1/organizations/{organization_id}` | core | `exception` | Organization aggregate update contract, not settings namespace. |
| `PATCH /api/v1/account_profiles/{account_profile_id}` | core | `exception` | Account profile aggregate update contract, not settings namespace. |
| `PATCH /api/v1/account_profile_types/{profile_type}` | core | `exception` | Registry resource update contract, not settings namespace. |
| `PATCH /api/v1/static_profile_types/{profile_type}` | core | `exception` | Registry resource update contract, not settings namespace. |
| `PATCH /api/v1/taxonomies/{taxonomy_id}` | core | `exception` | Taxonomy resource update contract, not settings namespace. |
| `PATCH /api/v1/taxonomies/{taxonomy_id}/terms/{term_id}` | core | `exception` | Nested taxonomy-term resource update contract, not settings namespace. |
| `PATCH /api/v1/static_assets/{static_asset_id}` | core | `exception` | Static asset aggregate update contract, not settings namespace. |
| `PATCH /api/v1/roles/{role_id}` (tenant) | core | `exception` | Role resource update contract, not settings namespace. |
| `PATCH /admin/api/v1/profile/password` | core | `exception` | Landlord profile password action endpoint. |
| `PATCH /admin/api/v1/profile` | core | `exception` | Landlord profile resource patch, not settings namespace. |
| `PATCH /admin/api/v1/profile/emails` | core | `exception` | Collection mutation endpoint (emails list semantics). |
| `PATCH /admin/api/v1/profile/phones` | core | `exception` | Collection mutation endpoint (phones list semantics). |
| `PATCH /admin/api/v1/tenants/{tenant_slug}` | core | `exception` | Tenant aggregate update contract, not settings namespace. |
| `PATCH /admin/api/v1/users/{user_id}` | core | `exception` | Landlord user aggregate update contract, not settings namespace. |
| `PATCH /admin/api/v1/roles/{role_id}` (landlord) | core | `exception` | Landlord role aggregate update contract, not settings namespace. |
| `PATCH /api/v1/events/{event_id}` (tenant admin project route) | project events | `exception` | Event aggregate update contract, not settings namespace. |
| `PATCH /api/v1/accounts/{account_slug}/events/{event_id}` (project account route) | project events | `exception` | Account-scoped event aggregate update contract, not settings namespace. |

## Conclusion
- All settings-kernel canonical endpoints are converged.
- Legacy push settings wrappers (`/settings/push`, `/settings/firebase`) are converged to direct payload + field-presence semantics.
- Remaining PATCH endpoints are explicit non-settings contracts (resource/collection mutations) and are tracked as intentional exceptions.
- No untracked PATCH divergence remains for the settings-kernel canonical contract.
