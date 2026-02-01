# TODO: Laravel Controller-Service Adherence Round 2

## Goal
Refactor remaining Laravel API controllers to be thin (no direct model queries or domain formatting). Controllers must delegate to Query/Management/Application services. Continue loop until adherence recheck is clean.

## Scope
- Remove direct model queries from controllers:
  - AccountUserController
  - AccountUserCredentialController
  - AccountController (accountUserManage)
  - AccountRolesTemplatesController (show/update/destroy)
  - AccountProfileMediaController (findProfileOrFail)
  - ProfileControllerTenant (generateToken/resetPassword telemetry lookups)
- Add/extend query services and update controllers accordingly.

## Non-Goals
- No changes to routes or request/response contracts.
- No feature changes beyond controller-service adherence.

## Tasks
- [x] Implement missing query/service methods for the controllers above.
- [x] Update controllers to use services only (no model queries).
- [x] Recheck adherence until clean.
- [x] Run full Laravel test suite and fix regressions.

## Acceptance Criteria
- All controllers listed have no direct model queries.
- Adherence recheck returns clean.
- Full Laravel test suite passes.
