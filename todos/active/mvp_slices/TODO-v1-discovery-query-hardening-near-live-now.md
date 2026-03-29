# TODO (V1): Discovery Query Hardening (Radius Priority + Near + Live Now)

**Status:** Active
**Owners:** Delphi (Flutter + Laravel)
**Complexity:** `medium`
**Checkpoint Policy:** one implementation checkpoint + final adherence review.

---

## Module Anchors
- **Primary:** `foundation_documentation/modules/flutter_client_experience_module.md`
- **Secondary:** `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/accounts_module.md`

## Context
User approved current Discovery UI and requested hardening in three areas:
1. Home radius must follow **user preference first**, otherwise **tenant default** (no hardcoded fallback behavior).
2. Discovery `Próximos a você` currently returns empty despite nearby profiles.
3. `Tocando agora` must be backed by a real query for events happening now with involved artists.

## Scope
- Flutter + Laravel implementation for the three issues above.
- Add tests across layers to cover root causes and prevent regressions.

Out of scope:
- New admin UX.
- Non-discovery redesign.

---

## Decision Baseline (Frozen)
- `D-01` Radius resolution priority is **user preference > tenant default**.
- `D-02` Discovery near query remains real backend GEO query; no local fallback/hardcode.
- `D-03` Account Profiles near aggregation parsing must support Mongo aggregate row IDs robustly.
- `D-04` `Tocando agora` must come from event-occurrence data (currently-live window), including artists in response.
- `D-05` Changes require regression tests in Flutter and Laravel covering failure paths and happy paths.

## Plan
- [ ] ⚪ Radius priority
  - [ ] ⚪ Update radius source-of-truth initialization so user preference is honored first.
  - [ ] ⚪ Ensure Home uses resolved preference/default without hardcoded behavior.
  - [ ] ⚪ Add/update Flutter tests for radius priority and request payload.
- [ ] ⚪ Near endpoint hardening
  - [ ] ⚪ Fix ID extraction/parsing in Laravel near query aggregation result handling.
  - [ ] ⚪ Add Laravel tests that cover aggregate payload variants (`id` and `_id`).
  - [ ] ⚪ Add/adjust Flutter integration/unit tests validating near list display path.
- [ ] ⚪ Live now query
  - [ ] ⚪ Define/implement backend query contract for events happening now including artists.
  - [ ] ⚪ Wire Flutter Discovery `Tocando agora` to this query.
  - [ ] ⚪ Add Laravel + Flutter tests for now-window behavior and artists projection.

## Validation
- [ ] ⚪ Laravel targeted test suite for account profiles near + events live-now.
- [ ] ⚪ Flutter targeted tests for Discovery controller + Home radius behavior.
- [ ] ⚪ Manual smoke on Discovery: `Tocando agora`, `Perto de você`, Home radius behavior.

## Decision Adherence Validation (to fill before delivery)
- `D-01`: Pending
- `D-02`: Pending
- `D-03`: Pending
- `D-04`: Pending
- `D-05`: Pending
