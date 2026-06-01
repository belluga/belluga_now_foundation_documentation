# Event Cold-Start Deep-Link Evidence

- **TODO:** `TODO-fast-follow-event-cold-start-deep-link-routing`
- **Created at:** 2026-06-01
- **Primary symptom:** Event detail app link opens when the app is already warm, but production validation reported cold-start failure. Account Profile app link opens cold and warm.

## Before State

Expected:
- Android installed app cold-starts into `ImmersiveEventDetailRoute` for `https://guarappari.com.br/agenda/evento/show-rock?occurrence=occ-1`, preserving the `occurrence` query.

Actual:
- User production report: Account Profile direct link cold/warm succeeds; Event detail direct link succeeds only when the app is already warm.
- Local ADB replay is blocked because WSL has no attached Android device: `adb devices -l` returned no devices.

## Stage Evidence

| Stage | Status | Evidence |
| --- | --- | --- |
| Laravel web-to-app contract | `passed` | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php` -> 13 tests / 169 assertions. |
| Android manifest/path parity | `passed-source` | `fvm flutter test test/platform/deep_link_platform_config_test.dart` included in the focused Flutter command below; Guarapari app-link path set includes `/agenda/evento` beside `/parceiro`. |
| Flutter startup route parity | `passed` | `fvm flutter test test/application/startup/app_startup_navigation_coordinator_test.dart test/platform/deep_link_platform_config_test.dart` -> 14 tests; new test preserves `/agenda/evento/show-rock?occurrence=occ-1`. |
| Browser public direct-route and navigation matrix | `passed` | `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && bash tools/flutter/run_web_navigation_smoke.sh readonly` -> 16/16 in 4.3m against bundle `04ba7216`. |
| Browser mutation matrix | `passed` | `source ~/.nvm/nvm.sh && nvm use 24.13.1 >/dev/null && NAV_ADMIN_PASSWORD='<tenant-admin password>' bash tools/flutter/run_web_navigation_smoke.sh mutation` -> 26/26. |
| Native installed-app cold start | `blocked` | No attached ADB device. Must still run force-stop/start comparison for Event and Account Profile before closing native DoD. |

## Runtime/Tunnel Classification

Two readonly attempts using the prior two-worker default failed with Cloudflare `502 Bad gateway` / Host Error around the same timestamp:

- tenant `/home` reload received Cloudflare 502;
- authenticated `GET /api/v1/account_profiles/qa-discovery-tag-longa` received Cloudflare 502.

These failures did not come from product assertions and were not visible as application-level assertion failures. The public-tunnel navigation smoke was serialized by default afterward because the gate validates navigation correctness and build provenance, not load behavior. The serialized canonical command passed `16/16`.

One additional harness false-negative was found after serialization: the direct Android handoff test missed a real `/open-app` 302 because the helper listened at browser-context level while the redirect was a page navigation response. The helper now waits on `page.waitForResponse`; the targeted deeplink shard passed `1/1`, then the full readonly matrix passed `16/16`.

## Host Association Spot Check

- `https://guarappari.com.br/.well-known/assetlinks.json` returns `com.guarappari.app` with the Guarapari signing fingerprint.
- `https://guarappari.booraagora.com.br/.well-known/assetlinks.json` returns `com.guarappari.app` with the Guarapari signing fingerprint.
- `https://guarappari.belluga.space/.well-known/assetlinks.json` returns `[]`.
- `https://guarappari.belluga.app/.well-known/assetlinks.json` returns `[]`.

If the production report used a preview host, empty `assetlinks.json` on that host is a plausible cold-start explanation. If the report used `guarappari.com.br`, device-level replay remains required to determine whether the installed build, Android verification state, or Flutter cold-route handoff owns the failure.

## Remaining Native Smoke

```bash
cd flutter-app
adb devices -l
adb -s <serial> shell am force-stop com.guarappari.app
adb -s <serial> shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.com.br/agenda/evento/show-rock?occurrence=occ-1' \
  com.guarappari.app
adb -s <serial> shell am force-stop com.guarappari.app
adb -s <serial> shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.com.br/parceiro/profile-slug' \
  com.guarappari.app
```

## Current Conclusion

The source-level and backend continuation chain is green for Event app links. The original native production symptom is not closed until a real Android device proves cold-start Event parity with Account Profile on the installed Guarapari build.
