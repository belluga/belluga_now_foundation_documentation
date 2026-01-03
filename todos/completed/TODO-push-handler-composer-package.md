# TODO (V1): Publishable Push Handler Package (Composer)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Completed  
**Owners:** Delphi (Laravel)  
**Objective:** Make `belluga_push_handler` publishable via Composer with Laravel auto‑discovery so routes load without manual provider registration.

---

## Scope
- Add a `composer.json` to `laravel-app/packages/belluga/belluga_push_handler/` with proper package metadata and Laravel auto‑discovery.
- Wire the local app to load the package via Composer path repository for development.
- Ensure package routes appear in `php artisan route:list` after discovery.

## Out of Scope
- Publishing to Packagist or a VCS registry.
- Breaking changes to existing route paths or middleware.
- Refactors inside push handler controllers/services.

## Definition of Done
- [x] ✅ Production‑Ready Package `composer.json` exists with PSR‑4 autoload and `extra.laravel.providers`.
- [x] ✅ Production‑Ready Local app uses Composer path repository to load the package.
- [x] ✅ Production‑Ready `php artisan route:list` shows the push routes (e.g., `/api/v1/settings/push/credentials`).

## Validation Steps
- [x] ✅ Production‑Ready `composer dump-autoload` (via Docker) completes successfully.
- [x] ✅ Production‑Ready `php artisan route:list | rg "push|credentials"` shows push routes.

## Decisions
- Use Composer auto‑discovery as the publishable path (no manual provider registration).

## Questions to Close
- None.

## References
- `laravel-app/packages/belluga/belluga_push_handler/src/PushHandlerServiceProvider.php`
- `laravel-app/packages/belluga/belluga_push_handler/routes/push_handler.php`
