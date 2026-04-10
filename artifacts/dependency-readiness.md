# Dependency Readiness Register

**Status:** Active
**Purpose:** Record external-system readiness that can affect tactical TODO execution realism. This register is non-authoritative and does not replace per-TODO validation.

| Dependency | Why It Matters | Status | Last Verified | Verification Method | Adjustment / Workaround |
| --- | --- | --- | --- | --- | --- |
| `https://guarappari.belluga.space` | Target host for deployed-browser and Playwright validation of the back-governance cutover. | `healthy` | `2026-04-10` | `curl -I -L --max-time 20 https://guarappari.belluga.space` returned `HTTP/2 200` | If this host stops responding during execution, keep TODO open as `Blocked` or `Provisional`; local-only browser evidence is not enough. |
| `../web-app` local artifact repo | Required output target for `scripts/build_web.sh` before browser/Playwright validation. | `healthy` | `2026-04-10` | local filesystem check confirmed `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/web-app` exists | If publish target changes, update the active TODO before continuing validation. |
| `scripts/build_web.sh` local publish path | Required to rebuild and publish the Flutter web bundle for this validation lane. | `healthy` | `2026-04-10` | script present and reviewed locally | Use `scripts/build_web.sh ../web-app dev`; if the command fails, treat browser/Playwright validation as blocked until publish is restored. |
| Playwright MCP browser lane | Required for end-to-end browser validation after publish. | `unknown` | `2026-04-10` | not yet exercised in this TODO | If MCP/browser automation fails, record the blocker explicitly and keep manual browser validation separate from automation status. |
