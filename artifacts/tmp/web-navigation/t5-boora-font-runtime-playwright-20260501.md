# T5 Boora Font Runtime Probe - 2026-05-01

## Target
- Local web runtime: `http://127.0.0.1:8081/`
- Build command: `LANDLORD_DOMAIN=https://belluga.space FLUTTER_DART_DEFINE_FILE=config/defines/local.override.json ../tools/flutter/build_web_bundle.sh ../web-app`
- Runtime font asset: `/assets/assets/fonts/BooraIcons-133da979.ttf`

## Evidence
- `../web-app/assets/FontManifest.json` registers:
  - `BooraIcons -> assets/fonts/BooraIcons-133da979.ttf`
  - `Boora -> assets/fonts/BooraIcons-133da979.ttf`
- Local nginx served the runtime font with:
  - bytes: `31860`
  - SHA-256: `133da9796f41585b80fcacccc58ab1043cc5c4d3bf7781589185da52490c40bf`
- Playwright canvas probe loaded the font through `FontFace` and drew codepoints:
  - `0xf000`
  - `0xf034`
  - `0xf037`
  - `0xf038`
- Canvas alpha pixels:
  - `BooraIcons`: `9711`
  - `Boora` legacy alias: `9711`

## Cache Finding
The public `https://guarappari.belluga.space/assets/assets/fonts/BooraIcons.ttf`
path still returned Cloudflare cached old bytes (`5360`, `cf-cache-status: HIT`,
`cache-control: public, max-age=31536000, immutable`). The Flutter runtime asset
was therefore moved to the hash-prefixed filename `BooraIcons-133da979.ttf` so a
font update produces a new URL and does not depend on manual CDN purge.
