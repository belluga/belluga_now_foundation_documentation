# Event Cold-Start Deep-Link Evidence

- **TODO:** `TODO-fast-follow-event-cold-start-deep-link-routing`
- **Created at:** 2026-06-01
- **Primary symptom:** Event detail app link opens when the app is already warm, but production validation reported cold-start failure. Account Profile app link opens cold and warm.

## Before State

Expected:
- Android installed app cold-starts into `ImmersiveEventDetailRoute` for `https://guarappari.com.br/agenda/evento/show-rock?occurrence=occ-1`, preserving the `occurrence` query.

Actual:
- User production report: Account Profile direct link cold/warm succeeds; Event detail direct link succeeds only when the app is already warm.
- Earlier local ADB replay was blocked because WSL had no attached Android device; the later connected `moto_e13` replay on the current Guarapari APK did not reproduce the production failure.

## Stage Evidence

| Stage | Status | Evidence |
| --- | --- | --- |
| Laravel web-to-app contract | `passed` | `./scripts/delphi/run_laravel_tests_safe.sh tests/Api/v1/Tenants/Branding/ApiV1OpenAppRedirectTest.php tests/Api/v1/Tenants/Branding/ApiV1DeferredDeepLinkResolverTest.php` -> 13 tests / 169 assertions. |
| Android manifest/path parity | `passed-source` | `fvm flutter test test/platform/deep_link_platform_config_test.dart` included in the focused Flutter command below; Guarapari app-link path set includes `/agenda/evento` beside `/parceiro`. |
| Flutter startup route parity | `passed` | `fvm flutter test test/application/startup/app_startup_navigation_coordinator_test.dart test/platform/deep_link_platform_config_test.dart` -> 14 tests; new test preserves `/agenda/evento/show-rock?occurrence=occ-1`. |
| Browser public direct-route and navigation matrix | `passed` | Post-ADB CI Equivalent `web_navigation_readonly` -> 16/16 against bundle `e7cec479`. |
| Browser mutation matrix | `passed` | Post-ADB CI Equivalent `web_navigation_mutation` -> 27/27 in 16.0m. |
| Native installed-app cold start | `passed-device` | ADB force-stop/start on `moto_e13` proved Event cold start, Account Profile cold start, and Event warm start. Generic production-host replay without explicit package opened Chrome first, then delivered into `com.guarappari.app` and rendered the correct app screens. |
| Post-ADB CI Equivalent | `passed` | `./scripts/delphi/run_reconcile_validation.sh --scope big --intent "v0.2.0+8 post-ADB deep link CI-equivalent"` -> all required stages passed; report `foundation_documentation/artifacts/v0.2.0-plus8/reconcile-validation-status-20260601-post-adb-deep-link-ci-equivalent.md`. |

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

If the production report used a preview host, empty `assetlinks.json` on that host remains a plausible host-association explanation for automatic Android App Links. The current production-host ADB replay without an explicit package opened Chrome first, then Chrome delivered the link into `com.guarappari.app`; both Event and Account Profile rendered correctly from a not-running app state.

## Executed Native Smoke

```bash
cd flutter-app
adb -s 192.168.15.2:5555 shell am force-stop com.guarappari.app
adb -s 192.168.15.2:5555 shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.belluga.space/agenda/evento/pw-event-share-boundary-store-release' \
  com.guarappari.app
adb -s 192.168.15.2:5555 shell am force-stop com.guarappari.app
adb -s 192.168.15.2:5555 shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.belluga.space/parceiro/qa-discovery-tag-longa' \
  com.guarappari.app
adb -s 192.168.15.2:5555 shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.belluga.space/agenda/evento/pw-event-share-boundary-store-release' \
  com.guarappari.app
adb -s 192.168.15.2:5555 shell am force-stop com.guarappari.app
adb -s 192.168.15.2:5555 shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.com.br/agenda/evento/pw-event-share-boundary-store-release'
adb -s 192.168.15.2:5555 shell am force-stop com.guarappari.app
adb -s 192.168.15.2:5555 shell am start -W \
  -a android.intent.action.VIEW \
  -c android.intent.category.BROWSABLE \
  -d 'https://guarappari.com.br/parceiro/qa-discovery-tag-longa'
```

Artifacts:

- `foundation_documentation/artifacts/v0.2.0-plus8/adb-event-deeplink-20260601/event-cold-start-after-permission.png`
- `foundation_documentation/artifacts/v0.2.0-plus8/adb-event-deeplink-20260601/account-cold-start.png`
- `foundation_documentation/artifacts/v0.2.0-plus8/adb-event-deeplink-20260601/event-warm-start.png`
- `foundation_documentation/artifacts/v0.2.0-plus8/adb-event-deeplink-20260601/event-cold-start-generic-prod.png`
- `foundation_documentation/artifacts/v0.2.0-plus8/adb-event-deeplink-20260601/account-cold-start-generic-prod.png`

## Current Conclusion

The source-level, backend continuation, browser, and native installed-app chains are green for Event app links on the current Guarapari build. The original production symptom was not reproducible after installing the current APK on the connected Android device: Event cold start, Account Profile cold start, Event warm start, and generic production-host handoff all landed on the expected app screens.
