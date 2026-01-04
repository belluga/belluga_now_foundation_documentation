# TODO (V1): Push Device Token Upsert + Invalidations

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Laravel)  
**Objective:** Ensure device token registration upserts by `device_id`, and mark tokens invalid when push providers return NOT_FOUND to avoid retrying stale tokens.

---

## Scope
- On device register, upsert by `device_id`: update token if device exists; create new row if not.
- On push send, if provider response includes `NOT_FOUND`, mark the token as invalid/inactive and exclude it from future sends.
- Keep existing behavior for other error codes (do not mark invalid unless explicitly NOT_FOUND).

## Out of Scope
- Introducing new provider types or changing provider contracts.
- Altering audience/eligibility logic for push messages.
- Reworking device id generation on the client.

## Definition of Done
- [x] ✅ Production‑Ready Registering the same `device_id` updates the existing token row.
- [x] ✅ Production‑Ready Registering a new `device_id` creates a new token row.
- [x] ✅ Production‑Ready Sending to a NOT_FOUND token marks it invalid and prevents future sends.
- [x] ✅ Production‑Ready Non‑NOT_FOUND errors do not invalidate tokens.
- [x] ✅ Production‑Ready Automated tests cover token reactivation, invalidation, and filtering in sends.

## Provisional Notes
- Implementation updates `devices` entries with `is_active` and `invalidated_at` on register/invalidate and filters inactive tokens during resolution.
- Send controllers invalidate tokens when delivery response includes `error_code=NOT_FOUND`.

## Validation Steps
- [x] ✅ Production‑Ready Reinstall app (token changes) and confirm the existing `device_id` row updates token.
- [x] ✅ Production‑Ready Send to an invalid/stale token and confirm it is flagged and skipped on subsequent sends.
- [x] ✅ Production‑Ready Send to valid token continues to deliver and remains active.

## Decisions
- Treat provider `NOT_FOUND` as a signal that the token is invalid (likely uninstall/reinstall).
- Use an "invalid/disabled" status flag rather than deleting rows to preserve audit history.

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/Services/FcmHttpV1Client.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Http/Controllers/Tenant/PushDeviceController.php`
- `laravel-app/packages/belluga/belluga_push_handler/src/Models/Tenants/PushDevice.php`
