# TODO (V1): Push Onboarding Dynamic Steps (Backend + App + Plugin)

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [x] ✅ Production‑Ready`.
**Status:** Active  
**Owners:** Backend (Laravel), Flutter App, push_handler Plugin  
**Objective:** Deliver a generic, push-driven onboarding flow that can be changed via backend payloads without app code changes, while keeping push_handler agnostic.

---

## Scope
- Extend the push payload schema to support dynamic onboarding steps with:
  - stable `slug` per step (no index-based IDs)
  - per-step `buttons` and `closeOnTap` (default true)
  - `gate` for step advancement (generic gatekeeper callback)
  - `onSubmit` for step answer persistence (generic app-side handler)
  - `selector` step type with dynamic `option_source` and layout variants
- Implement backend schema validation + storage + examples in Laravel push message endpoints.
- Implement app-side rendering and option resolution in Flutter.
- Implement push_handler UI hooks (generic) without domain knowledge (no favorites logic in plugin).

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
  allowDismiss: "true" | "false",
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
  "closeOnTap": true|false,           // default true if omitted
  "gate": { "type": "string", "required": true|false, "onFail": {...} } | null,
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
  "color": "#RRGGBB"
}
```

### Question step config (generic)
```
{
  "question_type": "single_select" | "multi_select" | "text",
  "option_source": {
    "type": "static" | "endpoint" | "tags" | "query",
    "params": { ... },
    "cache_ttl_sec": 3600
  },
  "options": [ { "id": "string", "label": "string", "image": "url" } ], // for static
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
  "required": true|false,
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
- `message_instance_id`: unique per delivery/tap if available
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
  - `type: cta`, `closeOnTap: true` and `closeOnTap: false`
- **Question Step**
  - `question_type: single_select`, `layout: row`
  - `question_type: multi_select`, `layout: grid`, `min_selected`/`max_selected`
  - `question_type: text`
- **Selector Step**
  - `layout: list`, `layout: tags`
  - dynamic `option_source` (mocked by `optionsBuilder`)
- **Gate Step**
  - `gate.required: true` with fail toast and fallback step

### Integration Assertions
- Renders correct step `slug` and title/body.
- Buttons appear with correct labels and close behavior.
- Gate blocks progress until gatekeeper returns true; re‑evaluates on resume.
- `onStepSubmit` called with expected `AnswerPayload` shape for each step type.
- Telemetry events emitted for each interaction (`opened`, `step_viewed`, `button_tap`, `submit`, `dismissed`).

---

## Laravel (Backend) Implementation

### Data Model + Validation
- `payload_template.steps[*].slug` is required, string, max 64, unique within the steps array. No fallback.
- `payload_template.steps[*].type` required and must be one of: `copy`, `cta`, `question`, `selector`.
- `payload_template.steps[*].closeOnTap` optional boolean. Default handling happens in Flutter.
- `payload_template.steps[*].gate` optional object with:
  - `type` required string if present
  - `required` optional boolean
  - `onFail.toast` optional string
  - `onFail.fallback_step` optional string (must match a step slug if provided)
- `payload_template.steps[*].onSubmit` optional object with:
  - `action` required string if present (e.g., `save_response`)
  - `store_key` required string if present
- `payload_template.steps[*].config` optional object validated by `type`:
  - `question`: validate `question_type`, `option_source` or `options` (static), `min_selected`, `max_selected`, `layout`, `grid_columns`, `store_key`
  - `selector`: validate `option_source`, `min_selected`, `max_selected`, `layout`, `grid_columns`, `store_key`
- Ensure `min_selected <= max_selected` when both provided.
- Ensure `grid_columns` only when `layout=grid`.

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
- [ ] Add validation rules for `payload_template.steps[*]` with required `slug`, `type`, `config` by type, and `gate`/`onSubmit` objects.
- [ ] Reject payloads without `slug` (no fallback).
- [ ] Validate `gate.onFail.fallback_step` matches an existing slug.
- [ ] Validate `question/selector` configs (layout, grid_columns, min/max, option_source).
- [ ] Update push message README examples to include onboarding steps and selector samples.

### push_handler Plugin
- [ ] Extend step model to require `slug` and include `closeOnTap`, `gate`, `onSubmit`, `config`.
- [ ] Add `AnswerPayload` class and pass to `onStepSubmit`.
- [ ] Implement `gatekeeper` callback + recheck on app resume after actions.
- [ ] Implement `optionsBuilder` callback + `OptionItem` (label optional if custom widget provided).
- [ ] Enforce `min_selected`/`max_selected` across question/selector UI.
- [ ] Add telemetry emitter (`PushEvent`) and emit at defined points.
- [ ] Add debug/test hook: inject message ID and follow normal fetch/render pipeline.
- [ ] Update plugin README + CHANGELOG + version bump when complete.

### Flutter App
- [ ] Implement gatekeeper mapping for `gate.type` values.
- [ ] Implement optionsBuilder sources (static, tags, endpoint/query allowlist).
- [ ] Wire `onStepSubmit` to persistence handler (store_key mapping).
- [ ] Render layouts (row/grid/list/tags) with HTML/Markdown body support.
- [ ] Subscribe to push telemetry events and forward to event_tracker_handler.
- [ ] Add debug/test route or entrypoint for message ID injection.

### Tests
- [ ] Unit tests for step parsing and config validation (plugin).
- [ ] Widget tests for layouts, min/max selection, closeOnTap.
- [ ] Integration tests using debug injection hook to validate display pipeline.
- [ ] Telemetry event assertions for each interaction type.

---

## push_handler Plugin (Generic UI + Hooks)

### Contracts and Types
- Add support for `slug` in step model (no reliance on step index for identity).
- Add `closeOnTap` per step. Default true when missing.
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
  - If `gate.required == true`, disable navigation until `gatekeeper(step)` returns true.
  - Re-run `gatekeeper` when returning to the app (resume) after any step action (open settings, navigate, popup, etc.).
  - If a button is tapped and `closeOnTap == true`, close the push UI.
  - If `closeOnTap == false`, keep UI in the stack.
- For question/selector:
  - Load options using `optionsBuilder` if `option_source` is present.
  - If `option_source.type == static`, use `options` list from payload.
  - Enforce `min_selected` / `max_selected`.
  - Call `onStepSubmit(AnswerPayload)` on confirmation.

### Metrics
- Use `step.slug` for action reporting (opened, step_viewed, clicked, dismissed).
- Keep existing action payload; extend to include `step_slug` in metadata.

### Backwards Compatibility
- No backward compatibility for missing slug (reject payloads).
- If `closeOnTap` missing, default to true.
- If `gate` missing, treat as pass.

---

## Flutter App Implementation

### Gatekeeper
- Implement a `gatekeeper` function that checks app state:
  - `notifications_permission`: true if granted.
  - Generic: allow future gates by mapping `gate.type` to app-side checks.
- If gate fails and `onFail.toast` exists, show toast.
- If `onFail.fallback_step` exists, navigate to that step slug.

### Options Builder
- Implement `optionsBuilder` mapping for dynamic sources:
  - `type: "tags"` -> backend tags endpoint
  - `type: "endpoint"` -> allowlisted URL
  - `type: "query"` -> backend query endpoint
  - `type: "static"` -> use payload options
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
- `closeOnTap` default true on last step unless explicitly false.

---

## Definition of Done
- [ ] ⚪ Backend validation enforces new step schema.
- [ ] ⚪ Backend README updated with examples.
- [ ] ⚪ push_handler supports step slugs, closeOnTap, gatekeeper, optionsBuilder, onStepSubmit.
- [ ] ⚪ Flutter app implements gatekeeper + optionsBuilder + selector rendering.
- [ ] ⚪ Step actions and metrics use slugs (no index-based analytics).
- [ ] ⚪ End-to-end push onboarding flow works with:
  - dynamic options from backend
  - gatekeeper blocking progression
  - closeOnTap behavior
- [ ] ⚪ push_handler emits PushEvent telemetry and event_tracker_handler can subscribe.

## Validation Steps
- [ ] ⚪ Push payload with multi-step question renders and collects responses.
- [ ] ⚪ Gatekeeper blocks next step until condition is met.
- [ ] ⚪ closeOnTap false keeps push UI open after CTA.
- [ ] ⚪ closeOnTap true closes on final CTA.
- [ ] ⚪ Step removal/reorder does not break analytics (slugs stable).
- [ ] ⚪ A/B payload variants (different steps) render correctly without app changes.
- [ ] ⚪ PushEvent emission observed for delivered/opened/step_viewed/button_tap/submit/dismissed.

## Notes for Implementers
- Keep push_handler agnostic: no favorites logic inside plugin.
- All domain-specific behavior lives in Flutter app via callbacks.
- Backend is allowed to define payload variants per segment.
