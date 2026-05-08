# RR-AUTH-02 App-Link Integrity Evidence - 2026-05-07

## Scope

This artifact records the concrete validation evidence for the RR-AUTH-02 criterion: "Verify Android and iOS app-link integrity surfaces remain correct after authorized mutation."

## Command Evidence

- **Command:** `./scripts/delphi/run_laravel_tests_safe.sh tests/Feature/Tenants/TenantAppDomainControllerTest.php tests/Feature/Tenants/TenantDomainControllerTest.php`
- **Result:** `31 passed`, `145 assertions`, duration `20.62s`.
- **Execution context:** `laravel-app` principal checkout after RR-AUTH-02 staged set freeze.

## Android Evidence

- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php:390` validates authorized Android app-domain store payload through `/.well-known/assetlinks.json`.
- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php:418` validates authorized Android app-domain delete payload through `/.well-known/assetlinks.json`.
- Denied Android mutation paths assert non-mutation in the same focused suite.

## iOS Evidence

- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php:441` validates authorized iOS app-domain store payload through `/.well-known/apple-app-site-association`.
- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php:466` validates authorized iOS app-domain delete payload through `/.well-known/apple-app-site-association`.
- `laravel-app/tests/Feature/Tenants/TenantAppDomainControllerTest.php:154` and `:193` validate denied iOS store/delete non-mutation of Apple association payload.

## Conclusion

Android Asset Links and iOS Universal Links payload integrity remain correct after authorized mutation, and denied mutation preserves existing association payload state.
