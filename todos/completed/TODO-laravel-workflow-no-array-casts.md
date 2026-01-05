# TODO: Laravel Workflow — No Array Casts for MongoDB Types

## Context
We need to enforce the new “no array/object casts” rule in the Laravel codebase by removing remaining Eloquent array casts on MongoDB-backed models.

## Scope
- [x] Update the relevant Laravel workflow to require no array casts for MongoDB-native fields.
- [x] Capture the rationale and where it applies (models like tenant settings and push message config).
- [x] Search for any remaining `array` casts and list findings before updating the workflow.
- [x] Remove array/object casts from MongoDB-backed models listed in Findings.
- [x] Confirm no remaining array/object casts in targeted models.

## Delivery Stages
- [ ] **Provisional** (unblocks dependencies; requires revisit before production-ready)
- [x] **Production-Ready** (complete, hardened, ready for release)

## Provisional Notes (Required if Provisional)
- **Missing for production-ready:** Remove casts and confirm MongoDB persistence is stable without them.
- **Revisit criteria:** Targeted models updated and verified; no array/object casts remain.
- **Dependencies unblocked:** Push message routes/types refactor and tenant settings persistence.

## Out of Scope
- [ ] New schema/migration changes beyond removing casts.
- [ ] API contract changes.

## Decisions
- [x] Target workflow file to update: `delphi-ai/workflows/laravel/create-domain-method.md`.
- [x] Exact text for the “no array casts” rule:
  "Do not add Eloquent casts for arrays or objects on MongoDB-backed models; leave these fields uncast so the MongoDB driver persists native BSON types."
## Decisions (Implementation)
- [ ] Which models are in scope for cast removal (default: all listed under Findings).

## Findings (Array Casts)
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/PushMessage.php`: `audience`, `delivery`, `payload_template`, `fcm_options`, `template_defaults`, `metrics`
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/TenantPushSettings.php`: `push_message_types`, `push_message_routes`, `firebase`, `push`
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/PushMessageAction.php`: `metadata`, `context`
- `laravel-app/app/Models/Tenants/Account.php`: `settings`

## Definition of Done
- [x] Workflow updated with a clear, enforceable “no array casts for MongoDB types” rule.
- [x] Any linked rule file updated if required by workflow-definition policy (not required for this change).
- [x] Remaining `array` casts identified and recorded in the TODO.
- [x] Array/object casts removed from in-scope models.
- [x] Verification pass confirms no remaining array/object casts in the target files.

## Tests
- **Tests required?** Yes (targeted).
- **If yes, tiering:** Local-only.
- **Test plan:** Run focused tests for push settings/messages if available; otherwise document what could not be run.

## Commands (Run Locally)
- `rg "=> 'array'|=> \"array\"|\\barray\\b'\\s*=>" laravel-app/packages/belluga/belluga_push_handler/src/Models`

## Files Expected (Optional)
- `delphi-ai/workflows/laravel/create-domain-method.md`
- `delphi-ai/workflows/laravel/create-api-endpoint-method.md`
