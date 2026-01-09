# TODO (V1): Push Onboarding Dynamic Steps (Backend + App + Plugin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend (Laravel), Flutter App, push_handler Plugin  
**Objective:** Deliver a generic, push-driven onboarding flow that can be changed via backend payloads without app code changes, while keeping push_handler agnostic.

---

## Scope
- Extend the push payload schema to support dynamic onboarding steps with:
  - stable `slug` per step (no index-based IDs)
- message-level `closeOnLastStepAction` (honored on last step only; default false)
- step-level `dismissible` to control whether "pular" is allowed on each step (replaces message-level allowDismiss)
  - `gate` for step advancement (generic gatekeeper callback)
  - `onSubmit` for step answer persistence (generic app-side handler)
  - `selector` step type with dynamic `option_source` and layout variants
- Implement backend schema validation + storage + examples in Laravel push message endpoints.
- Add a delivery-scoped `message_instance_id` (UUID/ULID) generated per send request (not per message model).
- Implement app-side rendering and option resolution in Flutter.
- Implement push_handler UI hooks (generic) without domain knowledge (no favorites logic in plugin).
- Add app-side push action dispatcher to execute `custom_action` handlers (permissions, settings, etc.).
- Wire `custom_action` handling into push presentation flow so gate steps can trigger permission prompts.
- Add a manual test push payload that exercises two gates: one dismissible (friends list) and one non‑dismissible (geolocation).
- Adjust push onboarding UI layout:
  - Remove top back button.
  - Add bottom-left back button labeled "voltar".
  - Move "pular" to top-right; it must skip only the current step (when allowed).
  - Remove global/push-level skip triggered by "pular".
  - Remove bottom "pular" and the bottom-right ">" button.
  - Add default "Continuar" button below content when no other CTA/button exists.
- Use Theme.of(context) styles in push_handler UI (no hard-coded colors or typography).
- Gate pre-render check: before rendering any step with a gate, run gatekeeper and skip the step immediately if already satisfied (avoid visible blink).
- Back navigation must skip already-satisfied gates (prevent looping when returning to previous steps).
- Option source resolver uses `method` to return `OptionItem` (or child options); methods may return static data for testing until backend wiring is ready.

## Out of Scope
- Telemetry/Mixpanel changes.
- Real-time experiments platform or analytics storage in backend (only use step/action metrics already tracked).
- Web app changes.

---

## Target Payload Schema (Push Message Payload Template)

### High-level
```
payload_template: {
  layoutType: "fullScreen" | "popup" | "bottomModal" | "snackBar" | "actionButton",
  closeOnLastStepAction: true | false,
  steps: [ ...step objects... ]
}
```

### Step object (generic, agnostic)
```
{
  "slug": "string",                  // stable identifier
  "type": "copy" | "cta" | "question" | "selector",
  "title": "string",
  "body": "string | html | markdown", // allow sanitized HTML or Markdown (images supported)
  "image": { "path": "url", "width": 0, "height": 0 } | null,
  "buttons": [ ...button objects... ],
  "dismissible": true|false,          // controls "pular" for this step
  "gate": { "type": "string", "onFail": {...} } | null,
  "onSubmit": { "action": "string", "store_key": "string" } | null,
  "config": { ...type-specific config... }
}
```

### Button object (generic)
```
{
  "label": "string",
  "action": {
    "type": "route" | "external" | "custom",
    "route_key": "string",
    "path_parameters": { "key": "value" },
    "query_parameters": { "key": "value" },
    "url": "https://...",
    "custom_action": "string"         // interpreted by app
  },
  "show_loading": true|false,
  "color": "#RRGGBB"
}
```

### Question step config (generic)
```
{
  "question_type": "single_select" | "multi_select" | "text",
  "option_source": {
    "type": "method",
    "name": "getFavorites" | "getTags" | "getMapPois",
    "params": { ... },
    "cache_ttl_sec": 3600
  },
  "options": [ { "id": "string", "label": "string", "image": "url" } ], // optional static fallback
  "min_selected": 1,
  "max_selected": 3,
  "layout": "row" | "grid" | "list" | "tags",
  "grid_columns": 2,
  "store_key": "preferences.tags"
}
```

### Selector step config (generic)
```
{
  "option_source": { ...same as question... },
  "min_selected": 0,
  "max_selected": 0,
  "layout": "row" | "grid" | "list" | "tags",
  "grid_columns": 2,
  "store_key": "favorites.items"
}
```

### Gate (generic)
```
{
  "type": "string",        // e.g. "notifications_permission"
  "onFail": {
    "toast": "string",
    "fallback_step": "slug"
  }
}
```

### OnSubmit (generic)
```
{ "action": "save_response", "store_key": "preferences.tags" }
```
Answer payload (app-side, not sent by backend):
```
{
  "step_slug": "string",
  "value": "string | number | bool | list",
  "metadata": { ... }
}
```

---

## Telemetry (Plugin Event Emission)

### Goal
- `push_handler` emits structured events without tracking logic.
- `event_tracker_handler` subscribes (or not) and maps to analytics backends.

### Event Emitter Contract (push_handler)
- Provide one mechanism (choose one):
  - `Stream<PushEvent> onPushEvent`, or
  - `void Function(PushEvent event)? onPushEvent`
- Emission must be synchronous with UI actions when possible.

### Event Payload (PushEvent)
- `type`: `delivered | opened | step_viewed | button_tap | dismissed | submit | gate_blocked | error`
- `push_id`: message identifier
- `message_instance_id`: unique per delivery/send (nullable if not provided)
- `step_slug`: current step slug (nullable)
- `step_type`: `copy | cta | question | selector` (nullable)
- `button_key`: if action came from a button
- `action_type`: `route | external | custom` (nullable)
- `route_key`: if action is `route`
- `timestamp`: ISO8601 UTC
- `app_state`: `foreground | background`
- `source`: `notification_tap | background_delivery | in_app`
- `metadata`: freeform map (app can extend)

### Emission Points
- On delivery enqueue: `delivered`
- On open/present: `opened`
- On step render: `step_viewed`
- On button tap: `button_tap`
- On close/dismiss: `dismissed`
- On submit confirmation: `submit`
- On gate fail: `gate_blocked`
- On internal errors: `error`

### event_tracker_handler Subscription
- The app registers a listener and forwards events to tracking backends.
- Mapping is external to `push_handler` and must not add domain logic in plugin.

---

## Display Pipeline Integration Tests (Flutter + Plugin)

### Goal
Validate the end‑to‑end display pipeline: **push ID → fetch payload → render steps** without relying on real FCM delivery.

### Test Hook (Debug/Test Only)
- Provide a test‑only injection entrypoint:
  - `PushHandler.debugInjectMessageId(String messageId)` or similar.
- The hook must follow the **real** code path used by production:
  - call the transport client to fetch payload (or a mock HTTP server with fixtures)
  - render the push content UI with the fetched payload

### Test Fixtures (Payload Variants)
- **Copy Step**
  - `type: copy`, `body` as Markdown with image
- **CTA Step**
  - `type: cta`, `dismissible: true` and `dismissible: false`
  - `closeOnLastStepAction: true` and `closeOnLastStepAction: false`
- **Question Step**
  - `question_type: single_select`, `layout: row`
  - `question_type: multi_select`, `layout: grid`, `min_selected`/`max_selected`
  - `question_type: text`
- **Selector Step**
  - `layout: list`, `layout: tags`
  - dynamic `option_source` (mocked by `optionsBuilder`)
- **Gate Step**
  - gate with fail toast and fallback step

### Integration Assertions
- Renders correct step `slug` and title/body.
- Buttons appear with correct labels and close behavior.
- Gate blocks progress until gatekeeper returns true; re‑evaluates on resume.
- `onStepSubmit` called with expected `AnswerPayload` shape for each step type.
- Telemetry events emitted for each interaction (`opened`, `step_viewed`, `button_tap`, `submit`, `dismissed`).

### Manual Test Payload (Two Gates)
Use this payload to validate two gates (location required, contacts dismissible) plus selectors.
```
{
  "internal_name": "boora_onboarding_dynamic_2026_01_08_manual",
  "title_template": "Bóora! Bem-vindo",
  "body_template": "Vamos personalizar sua experiência.",
  "type": "transactional",
  "active": true,
  "audience": { "type": "all" },
  "delivery": {
    "scheduled_at": null
  },
  "delivery_deadline_at": "2026-02-08T12:00:00Z",
  "payload_template": {
    "layoutType": "fullScreen",
    "closeOnLastStepAction": true,
    "title": "Bem-vindo ao Bóora",
    "body": "Responda algumas etapas rápidas para personalizar seu app.",
    "image": {
      "path": "https://guarappari.com.br/assets/push/hero.png",
      "width": 720,
      "height": 480
    },
    "steps": [
      {
        "slug": "intro",
        "type": "cta",
        "title": "Começar",
        "body": "Vamos configurar suas preferências.",
        "dismissible": false
      },
      {
        "slug": "gate_location",
        "type": "cta",
        "title": "Ative sua localização",
        "body": "Precisamos da sua localização para mostrar o mapa perto de você.",
        "dismissible": false,
        "gate": {
          "type": "location_permission",
          "onFail": { "toast": "Ative a localização para continuar." }
        },
        "buttons": [
          {
            "label": "Permitir localização",
            "action": { "type": "custom", "custom_action": "request_location_permission" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "gate_friends",
        "type": "cta",
        "title": "Convide amigos",
        "body": "Ative a permissão de contatos para sugerirmos amigos.",
        "dismissible": true,
        "gate": {
          "type": "contacts_permission",
          "onFail": { "toast": "Sem permissão, você pode continuar." }
        },
        "buttons": [
          {
            "label": "Permitir contatos",
            "action": { "type": "custom", "custom_action": "request_contacts_permission" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "favorites_selector",
        "type": "selector",
        "title": "Escolha seus favoritos",
        "body": "Selecione pelo menos 3 favoritos para personalizar seu app.",
        "dismissible": false,
        "config": {
          "min_selected": 3,
          "max_selected": 8,
          "layout": "grid",
          "grid_columns": 2,
          "option_source": {
            "type": "method",
            "name": "getFavorites"
          }
        },
        "gate": {
          "type": "favorites_min_selected",
          "min_selected": 3,
          "onFail": { "toast": "Selecione pelo menos 3 favoritos." }
        },
        "buttons": [
          {
            "label": "Escolher favoritos",
            "action": { "type": "custom", "custom_action": "open_favorites_selector" },
            "show_loading": true
          }
        ]
      },
      {
        "slug": "map_poi_selector",
        "type": "selector",
        "title": "O que voce procura?",
        "body": "Selecione os tipos de lugares que deseja ver no mapa.",
        "dismissible": true,
        "config": {
          "min_selected": 1,
          "max_selected": 6,
          "layout": "tags",
          "option_source": {
            "type": "method",
            "name": "getTags",
            "params": {
              "include": ["praias", "restaurantes", "experiencias_no_mar", "trilhas"]
            }
          }
        },
        "gate": {
          "type": "selection_min",
          "min_selected": 1,
          "onFail": { "toast": "Selecione ao menos 1 tipo." }
        }
      },
      {
        "slug": "finish",
        "type": "cta",
        "title": "Tudo pronto",
        "body": "Voce ja pode explorar o app.",
        "dismissible": false,
        "buttons": [
          {
            "label": "Abrir mapa",
            "action": {
              "type": "route",
              "route_key": "map",
              "path_parameters": {}
            }
          }
        ]
      }
    ]
  }
}
```

---

## Laravel (Backend) Implementation

### Data Model + Validation
- `payload_template.steps[*].slug` is required, string, max 64, unique within the steps array. No fallback.
- `payload_template.steps[*].type` required and must be one of: `copy`, `cta`, `question`, `selector`.
- `payload_template.closeOnLastStepAction` optional boolean (applies only to last-step actions).
- `payload_template.steps[*].dismissible` optional boolean (controls step-level skip).
- `payload_template.steps[*].gate` optional object with:
  - `type` required string if present
  - `onFail.toast` optional string
  - `onFail.fallback_step` optional string (must match a step slug if provided)
- `payload_template.steps[*].onSubmit` optional object with:
  - `action` required string if present (e.g., `save_response`)
  - `store_key` required string if present
- `payload_template.steps[*].buttons` optional array with:
  - `label` required string
  - `action.type` required `route|external|custom`
  - `action.route_key|path_parameters|query_parameters` required when `route`
  - `action.url` required when `external`
  - `action.custom_action` required when `custom`
  - `show_loading` optional boolean
- `payload_template.steps[*].config` optional object validated by `type`:
  - `question`: validate `question_type`, `option_source.type == method` + `name` (or `options` fallback), `min_selected`, `max_selected`, `layout`, `grid_columns`, `store_key`
  - `selector`: validate `option_source.type == method` + `name` (or `options` fallback), `min_selected`, `max_selected`, `layout`, `grid_columns`, `store_key`
- Ensure `min_selected <= max_selected` when both provided.
- Ensure `grid_columns` only when `layout=grid`.
- Simplify validation:
  - Remove legacy `option_source` type variants (`static`, `endpoint`, `tags`, `query`).
  - Require `option_source.type == method` and `option_source.name`.
  - Allow `options` array only as static fallback (no validation of remote URLs or queries).

### Response Shape
- `/push/messages/{id}` and `/push/messages/{id}/data` must return the new step objects (slug-based) as stored.
- No changes to existing push handler response wrapper (`ok`, `payload`).

### Documentation
- Update `laravel-app/packages/belluga/belluga_push_handler/README.md` with:
  - new step schema
  - examples for question and selector
  - note that `gate` and `onSubmit` are app-handled

---

## Tasks (Execution Checklist)

### Backend (Laravel)
- [x] ✅ Production‑Ready Add validation rules for `payload_template.steps[*]` with required `slug`, `type`, `config` by type, and `gate`/`onSubmit` objects.
- [x] ✅ Production‑Ready Reject payloads without `slug` (no fallback).
- [x] ✅ Production‑Ready Validate `gate.onFail.fallback_step` matches an existing slug.
- [x] ✅ Production‑Ready Validate `question/selector` configs (layout, grid_columns, min/max, option_source).
- [x] ✅ Production‑Ready Validate `payload_template.steps[*].buttons` (label, action.type, route/external/custom requirements, show_loading).
- [x] ✅ Production‑Ready Update push message README examples to include onboarding steps and selector samples.
- [x] ✅ Production‑Ready Update validation to require `option_source.type == method` + `name`, and allow `options` fallback only.
- [x] ✅ Production‑Ready Update README/examples to show method-based `option_source` (no query/tags/endpoint).
- [x] ✅ Production‑Ready Generate a `message_instance_id` per send (UUID/ULID), include it in payload meta, and persist in `push_delivery_logs`.
- [x] ✅ Production‑Ready Add message-level optional deadline field (e.g., `delivery_deadline_at`) to push message schema/model.
- [x] ✅ Production‑Ready Validate `delivery_deadline_at` when present (datetime, not in the past).
- [x] ✅ Production‑Ready Define per-delivery TTL policy by message type (transactional vs promotional) and expose defaults in config.
- [x] ✅ Production‑Ready Compute `expires_at` at send time as `min(delivery_deadline_at, now + ttl)`; if no deadline, use `now + ttl`.
- [x] ✅ Production‑Ready Enforce FCM max TTL (<= 28 days) on computed `expires_at` and return a clear validation error when exceeded.
- [x] ✅ Production‑Ready Update delivery pipeline to use computed `expires_at` (not model field) and persist it to `push_delivery_logs`.
- [x] ✅ Production‑Ready Add tests for TTL computation (deadline cap, no deadline, FCM max).
- [x] ✅ Production‑Ready Update README with TTL policy and `delivery_deadline_at` semantics.

**Provisional Notes (Backend TTL):**
- None.

### push_handler Plugin
- [x] ✅ Production‑Ready Extend step model to require `slug` and include `dismissible`, `gate`, `onSubmit`, `config`.
- [x] ✅ Production‑Ready Add `AnswerPayload` class and pass to `onStepSubmit`.
- [x] ✅ Production‑Ready Implement `gatekeeper` callback + recheck on app resume after actions.
- [x] ✅ Production‑Ready Implement `optionsBuilder` callback + `OptionItem` (label optional if custom widget provided).
- [x] ✅ Production‑Ready Enforce `min_selected`/`max_selected` across question/selector UI.
- [x] ✅ Production‑Ready Add telemetry emitter (`PushEvent`) and emit at defined points.
- [x] ✅ Production‑Ready Add debug/test hook: inject message ID and follow normal fetch/render pipeline.
- [x] ✅ Production‑Ready Update plugin README + CHANGELOG + version bump when complete.
- [x] ✅ Production‑Ready Use `message_instance_id` for dedupe keys (fallback to message id) and include it in emitted events.
- [x] ✅ Production‑Ready Remove top back button from push UI.
- [x] ✅ Production‑Ready Add bottom-left back button labeled "voltar".
- [ ] ⚪ Pending Device back button maps to "voltar"; on first step do nothing.
- [x] ✅ Production‑Ready Move "pular" to top-right and wire it to step-only skip.
- [x] ✅ Production‑Ready Remove global skip behavior (no full push dismissal on "pular").
- [x] ✅ Production‑Ready Remove bottom "pular" and bottom-right ">" button.
- [x] ✅ Production‑Ready Add default "Continuar" below content when no CTA exists.
- [x] ✅ Production‑Ready Replace custom colors/styles with Theme-derived styles across push UI.

### Flutter App
- [x] ✅ Production‑Ready Implement gatekeeper mapping for `gate.type` values.
- [x] ✅ Production‑Ready Handle contacts permission gate aliases (`friends_permission`, `contacts_permission`).
- [x] ✅ Production‑Ready Implement optionsBuilder sources (method-based resolver + static fallback).
- [x] ✅ Production‑Ready Wire `onStepSubmit` to persistence handler (store_key mapping).
- [x] ✅ Production‑Ready Render layouts (row/grid/list/tags) with HTML/Markdown body support.
- [x] ✅ Production‑Ready Subscribe to push telemetry events and forward to event_tracker_handler.
- [x] ✅ Production‑Ready Add debug/test route or entrypoint for message ID injection.
- [x] ✅ Production‑Ready Add `PushActionDispatcher` service that maps `custom_action` to app behaviors.
- [x] ✅ Production‑Ready Implement `request_location` action using geolocator permission flow.
- [x] ✅ Production‑Ready Implement `request_friends_access` action using the invite share friends permission logic.
- [x] ✅ Production‑Ready If contacts permission is permanently denied, send user to app settings (toast otherwise).
- [x] ✅ Production‑Ready Wire dispatcher into the push flow (buttons with `custom_action`).
- [x] ✅ Production‑Ready Capture `message_instance_id` from payload meta and forward it with action reports/telemetry.
- [ ] ⚪ Pending Move dynamic option resolution behind a controller (no repo calls from infra/services).
- [x] ✅ Production‑Ready Replace `option_source` schema with method-based resolver (`type=method`, `name=...`) across backend validation + docs.
- [x] ✅ Production‑Ready Add `PushOptionsController` that resolves method names via repositories (e.g., `getFavorites`, `getTags`) and returns `OptionItem` list.
- [x] ✅ Production‑Ready Wire `ApplicationContract`/DI to use controller-backed optionsBuilder (controller -> repositories).
- [x] ✅ Production‑Ready Update tests to cover method-based option_source resolution (favorites + tags).

### Tests
- [x] ✅ Production‑Ready Unit tests for step parsing and config validation (plugin).
- [x] ✅ Production‑Ready Widget tests for layouts, min/max selection, dismissible, closeOnLastStepAction.
- [x] ✅ Production‑Ready Integration tests using debug injection hook to validate display pipeline.
- [x] ✅ Production‑Ready Telemetry event assertions for each interaction type.
- [x] ✅ Production‑Ready Add coverage to verify dedupe uses `message_instance_id` when available.

---

## push_handler Plugin (Generic UI + Hooks)

### Contracts and Types
- Add support for `slug` in step model (no reliance on step index for identity).
- Add `dismissible` per step to control step skip. Default false when missing.
- Add `gate` object per step and `onSubmit` object per step. Do not interpret domain details.
- Add optional callbacks (new in repository/presenter):
  - `Future<bool> Function(StepPayload step)? gatekeeper`
  - `Future<void> Function(AnswerPayload answer)? onStepSubmit`
  - `Future<List<OptionItem>> Function(OptionSource source)? optionsBuilder`
  - `OptionItem` fields:
    - `value` (answer payload)
    - `label` (optional if `customWidgetBuilder` provided)
    - `subtitle` (optional)
    - `image` (optional)
    - `customWidgetBuilder` (optional)

### UI Behavior
- When rendering a step:
  - If the step has a `gate`, disable navigation until `gatekeeper(step)` returns true.
  - Re-run `gatekeeper` when returning to the app (resume) after any step action (open settings, navigate, popup, etc.).
  - After a step CTA button action completes, immediately re-run `gatekeeper(step)`; if it passes, advance to the next step, otherwise keep the step and apply `onFail` (toast and/or fallback step).
  - If a button has `show_loading: true`, it must display a spinner and disable itself while the action is running (including `custom_action` callbacks).
  - If a button is tapped on the last step and `closeOnLastStepAction == true`, close the push UI.
  - On non-last steps, `closeOnLastStepAction` is ignored (push stays open).
- For question/selector:
  - Load options using `optionsBuilder` if `option_source` is present.
  - If `options` list is provided, treat it as a static fallback.
  - Enforce `min_selected` / `max_selected`.
  - Call `onStepSubmit(AnswerPayload)` on confirmation.

### Metrics
- Use `step.slug` for action reporting (opened, step_viewed, clicked, dismissed).
- Keep existing action payload; extend to include `step_slug` in metadata.

### Backwards Compatibility
- No backward compatibility for missing slug (reject payloads).
- If `closeOnLastStepAction` missing, default to false (last step only).
- If `gate` missing, treat as pass.

---

## Flutter App Implementation

### Gatekeeper
- Implement a `gatekeeper` function that checks app state:
  - `notifications_permission`: true if granted.
  - `favorites_min_selected` (alias `selection_min`): validate stored selection length against `min_selected` in step config.
  - Generic: allow future gates by mapping `gate.type` to app-side checks.
- If gate fails and `onFail.toast` exists, show toast.
- If `onFail.fallback_step` exists, navigate to that step slug.

### Options Builder
- Implement `optionsBuilder` mapping for dynamic sources:
  - `type: "method"` + `name` -> app-provided resolver (controller -> repository)
  - `options` list only as static fallback
- Enforce `cache_ttl_sec` where provided.

### Selector / Question Rendering
- Render layout based on `layout`:
  - `row`: horizontal chips
  - `list`: vertical list
  - `grid`: grid with `grid_columns`
  - `tags`: chip list
- Enforce `min_selected` / `max_selected` before allowing next.

### Preferences / Favorites
- Use generic `store_key` paths:
  - `preferences.tags`
  - `favorites.items`
- Map `store_key` to app persistence (user preferences endpoint or local store).

### CTA and Copy
- Render `body` as sanitized HTML or Markdown (images supported); no raw HTML without allowlist.
- `closeOnLastStepAction` default false on last step unless explicitly true; ignored elsewhere.
 - `custom_action` must be forwarded to the app (push_handler remains agnostic). The app may open a bottom sheet (e.g., favorites selector) and the gate will re-check on completion.
 - `open_favorites_selector` is the canonical `custom_action` for the favorites bottom sheet.

---

## Definition of Done
- [x] ✅ Production‑Ready Backend validation enforces new step schema.
- [x] ✅ Production‑Ready Backend README updated with examples.
- [x] ✅ Production‑Ready push_handler supports step slugs, dismissible, gatekeeper, optionsBuilder, onStepSubmit.
- [x] ✅ Production‑Ready Flutter app implements gatekeeper + optionsBuilder + selector rendering.
- [x] ✅ Production‑Ready Step actions and metrics use slugs (no index-based analytics).
- [x] ✅ Production‑Ready End-to-end push onboarding flow works with:
  - dynamic options from backend
  - gatekeeper blocking progression
  - closeOnLastStepAction behavior
- [x] ✅ Production‑Ready push_handler emits PushEvent telemetry and event_tracker_handler can subscribe.
- [x] ✅ Production‑Ready `custom_action` buttons can trigger app permission prompts.
- [x] ✅ Production‑Ready Permission gate feedback handles permanent denial (open settings) and denial (toast).
- [x] ✅ Production‑Ready Manual test payload with two gates is ready for validation.
- [x] ✅ Production‑Ready Push onboarding UI layout matches new navigation rules (top-right step skip, bottom-left voltar, default continuar).
- [x] ✅ Production‑Ready Push UI uses Theme.of(context) styles only (no hard-coded colors).

## Recent Decisions (Sync Notes)
- [x] ✅ Production‑Ready Replace `gate.required` with step-level `dismissible`; skip allowed only when step is dismissible (message-level `allowDismiss` removed).
- [x] ✅ Production‑Ready Gate behavior is consistent for all gates: check on step entry (auto-advance if granted), re-check after action; only difference is whether the step is dismissible.
- [x] ✅ Production‑Ready Permission handling: simple denial shows toast; permanent denial opens app settings; no automatic settings open on simple denial.
- [x] ✅ Production‑Ready Contacts permission uses `permission_handler` for request/status; FlutterContacts used only for fetching contacts.
- [x] ✅ Production‑Ready Device back button maps to “voltar”; on first step it does nothing.
- [x] ✅ Production‑Ready Rename message-level `closeOnTap` to `closeOnLastStepAction` (applies only to last-step actions), remove step-level `closeOnTap`.
- [x] ✅ Production‑Ready If `custom_action` is empty or unhandled, advance to next step and show a toast (no blocking, no log spam).
- [x] ✅ Production‑Ready Unknown/empty `custom_action` advances to next step and shows toast (no “Unhandled custom action” log spam).
- [x] ✅ Production‑Ready Add aliases so payload `custom_action` values work across app + plugin (`request_location_permission` / `request_contacts_permission`).
- [x] ✅ Production‑Ready Delivery expiration is calculated at send time (per-delivery TTL), with optional message-level deadline to cap delivery expiration (delivery expires at `min(message_deadline, now + ttl)`).
- [x] ✅ Production‑Ready Switch `option_source` to method-based resolver (`type=method`, `name=...`) and remove query/tags/endpoint from schema.


## Validation Steps
- [x] ✅ Production‑Ready Push payload with multi-step question renders and collects responses.
- [x] ✅ Production‑Ready Gatekeeper blocks next step until condition is met.
- [x] ✅ Production‑Ready closeOnLastStepAction false keeps push UI open after CTA.
- [x] ✅ Production‑Ready closeOnLastStepAction true closes on final CTA.
- [x] ✅ Production‑Ready Step removal/reorder does not break analytics (slugs stable).
- [x] ✅ Production‑Ready A/B payload variants (different steps) render correctly without app changes.
- [x] ✅ Production‑Ready PushEvent emission observed for delivered/opened/step_viewed/button_tap/submit/dismissed.

## Notes for Implementers
- Keep push_handler agnostic: no favorites logic inside plugin.
- All domain-specific behavior lives in Flutter app via callbacks.
- Backend is allowed to define payload variants per segment.
