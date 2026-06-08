# Final Proposal: Canonical Tenant-Public Action Surface

## Decision
Adopt one canonical tenant-public action surface family for contextual actions, promotion gates, and event invite first-touch.

This proposal approves the product/UX direction only. Implementation remains blocked until a tactical TODO includes the mandatory test gates listed below.

## Canonical Surface
Create a shared presentation surface family tentatively named `TenantPublicActionSheet`.

### Ownership
- Location: shared presentation ownership, for example `flutter-app/lib/presentation/shared/action_sheets/`.
- The shared component owns:
  - adaptive shell;
  - handle/close affordance;
  - title/support text slots;
  - optional entity summary slot;
  - action row/button layout;
  - spacing, shape, typography, and semantic keys.
- Feature modules own:
  - promotion content and store actions;
  - event occurrence summary;
  - invite action labels and routing;
  - share/WhatsApp launcher callbacks;
  - auth/promotion gate decisions.
- The shared component must not become a business `switch` over promotion/invite/share variants.

## Platform Adaptation
- Mobile native and mobile-framed web: modal bottom sheet.
- Wide desktop web: centered/inset dialog or anchored sheet using the same anatomy and keys.
- The active tenant-public mobile-frame contract remains intact; `/mapa` full-width exception is unaffected.

## Event Hero Behavior
Expanded hero actions remain visually centralized:
- `Convidar` is primary.
- `Compartilhar` is secondary.
- `WhatsApp` is secondary/direct channel.

### Interaction-Depth Rule
- `Convidar` opens the canonical action surface first because it is a multi-path, domain-stateful product action.
- `Compartilhar` remains immediate system/native share because it is a single-intent public link action.
- `WhatsApp` remains immediate WhatsApp/fallback share because it is a single-intent channel shortcut.

This asymmetry is intentional, not drift.

## Convidar Sheet Content
Tapping `Convidar` on an event opens the canonical surface with:
- compact event/occurrence summary;
- primary action: `Convidar pessoas do app`;
- direct channel action: `Enviar pelo WhatsApp`;
- public distribution action: `Compartilhar link`;
- mandatory advanced action: `Abrir lista completa` or `Ver todos`, routing to `/convites/compartilhar`.

The first-touch sheet must not hydrate contacts, inviteable recipients, sent statuses, or contact refresh on open. It renders from already available event/occurrence/promotion state. Recipient/status/contact hydration stays in the full composer or in explicit follow-up actions.

## Full Invite Composer
`/convites/compartilhar` remains the advanced composer route.

It continues to own:
- in-app inviteable list;
- sent-status hydration;
- phone contacts branch;
- contact refresh;
- group management entry;
- share-code generation;
- invite send/status behavior.

The implementation must not change backend invite semantics, share-code lifecycle, recipient eligibility, occurrence-scoped invite identity, or invite mutation contracts unless a separate TODO explicitly owns that scope and adds backend/controller tests.

## Promotion Gate
The currently approved web app-promotion modal should migrate only as the promotion variant of the same canonical surface family.

Rules:
- `AppPromotionScreenController` remains source of truth for app name, icon, preferred platform, store targets, and promotion URI.
- `AppPublicationSettings` remains source for Android/iOS/both/no-store target rendering.
- Anonymous web gates never show phone-login UI.
- The first click does not auto-open the app or store; explicit CTA is required.
- Native unauthenticated app behavior keeps its canonical auth/login route where applicable.

## Mandatory Implementation Test Gates
Implementation is not approved until a TODO includes and passes these gates.

### Widget / Controller Tests
1. `Convidar` hero action:
   - tap `immersiveHeroInviteAction`;
   - assert canonical action surface is visible;
   - assert full composer route is not pushed immediately;
   - tap `Abrir lista completa` / `Ver todos`;
   - assert `InviteShareRoute` is pushed.
2. `WhatsApp` hero action:
   - tap WhatsApp action;
   - assert WhatsApp/fallback launcher is invoked directly;
   - assert canonical action surface is not opened.
3. `Compartilhar` hero action:
   - tap share action;
   - assert system share launcher is invoked directly;
   - assert canonical action surface is not opened.
4. Promotion variant:
   - assert promotion gate uses canonical action surface keys/anatomy;
   - assert active store target rendering still covers Android-only, iOS-only, both, no explicit config, and no active targets;
   - assert web anonymous context does not render phone-login copy.
5. Lazy-load contract:
   - opening event invite first-touch surface does not call inviteable recipient, sent-status, phone contact, or contact refresh hydration.

### Runtime / Playwright Tests
1. Web anonymous promotion runtime:
   - anonymous user clicks a favorite/action-gated public surface;
   - canonical promotion surface appears;
   - no `/auth/login`, no phone-login UI, no `/open-app`, no auto-open app/store before explicit CTA.
2. Event invite runtime:
   - event detail opens;
   - `Convidar` opens canonical surface first;
   - explicit full-composer action navigates to composer;
   - back behavior returns to event detail without route/controller bleed.
3. Mobile viewport and desktop mobile-frame:
   - canonical surface remains readable and within frame;
   - desktop adaptation keeps the same semantic keys and action ordering.

### CI-Equivalent Gates
The implementation TODO must list exact commands before delivery. Minimum expected gates:
- focused Flutter tests for event hero actions, promotion surface, and lazy-load contract;
- `fvm dart analyze --format machine`;
- rule matrix validation;
- correct web build script for `../web-app`;
- source-owned Playwright runtime smoke for the web gates above.

## Audit Adjudication Summary
- Elegance: accepts direction with documented UX tradeoff and migration constraints.
- Performance: accepts direction if first-touch sheet is lazy and shared ownership is explicit.
- Test Quality: blocks implementation until the test gates above are encoded and passing.

Final consensus: approve the canonical action-surface direction; implementation requires a TODO with these gates.
