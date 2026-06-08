# Bounded UX Audit Package: Canonical Event Action Surface

## Package Status
- Artifact type: derived audit package.
- Product status: proposal only; no implementation authorized by this package.
- Scope: tenant-public Flutter/web event hero action surfaces, invite entrypoint, share actions, WhatsApp shortcut, and app-promotion/favorite gate modal family.
- Out of scope: backend invite semantics, invite lifecycle persistence, publication settings contract changes, QR login, phone OTP, and direct implementation.

## User Problem
The current favorite/app-promotion modal now feels acceptable, but `Convidar` still opens a different-looking flow. The user wants one canonical format and asked for a market-informed comparison to decide which interaction model is more intuitive.

## Current Product/Code Shape
- `AppPromotionModal` is an `AlertDialog`-style compact modal under `flutter-app/lib/presentation/shared/promotion/screens/app_promotion_screen/widgets/app_promotion_modal.dart`.
- `AppPromotionDownloadExperience` shares store/action components with that modal, but the modal surface itself is visually distinct from invite surfaces.
- Event hero actions are centralized in `ImmersiveDetailScreen` and event-specific action definitions are in `ImmersiveEventDetailScreen._buildHeroActions`.
- Event hero currently exposes:
  - `Convidar` -> `_openInviteFlow(event)` -> `context.router.push(InviteShareRoute(invite: invite))`.
  - `Compartilhar` -> native/system share.
  - `WhatsApp` -> direct WhatsApp-or-system-share path.
- `/convites/compartilhar` is a full invite composer route. It supports in-app inviteable recipients, phone contact branch, share-code generation, sent-statuses, contact refresh, group management entry, and external share.
- `InviteExternalContactsSheet` is a separate bottom-sheet surface inside the invite module.

## Canonical Product Constraints
- Web anonymous hard/action gates must resolve to app-promotion UX, not phone login.
- Authenticated web may act normally.
- Native app keeps phone OTP and invite composer capability.
- Invite is a social/product action, not just a public link share.
- Public share is distribution of a link and should prefer system/native share where possible.
- WhatsApp is a direct channel shortcut.
- Event invite target remains occurrence-scoped.

## Market/Platform Reference Summary
- Apple Activity Views: share actions are commonly launched from a share/action button and delegate to system-provided share/action extensions.
- MDN Web Share API: `navigator.share()` invokes the device native sharing mechanism; it requires user activation and support varies by browser.
- Material Bottom Sheets: modal bottom sheets are mobile-first alternatives to menus/simple dialogs, suited for action lists/grids, long item names, subtext, and icons; on larger screens alternatives like dialogs/menus may be better.
- Eventbrite user docs: event sharing is exposed through a share icon and shares a link/social message, distinct from deeper event-management/invite workflows.
- Mercado Livre-inspired observation from user: hero actions can be compact, vertical, and direct; when collapsed, a primary action plus more menu is intuitive.

## Proposal Under Audit
Establish a shared `TenantPublicActionSheet` / `BellugaActionSheet` family as the canonical event/account/action-gate surface.

### Surface Model
- Mobile/app and mobile-framed web: modal bottom sheet.
- Wide desktop web: centered/inset dialog or anchored action sheet using the same anatomy and tokens.
- One visual anatomy across promotion, invite, public share overflow, and small contextual decisions:
  - drag handle or close affordance where appropriate;
  - compact entity summary card;
  - short title and optional one-line support text;
  - action rows/buttons with icon/brand, label, and optional subtext;
  - explicit primary action;
  - dismiss behavior consistent across surfaces.

### Event Hero First Click Behavior
- Expanded hero actions remain:
  - `Convidar` primary;
  - `Compartilhar`;
  - `WhatsApp`.
- `Compartilhar` remains immediate system/native share.
- `WhatsApp` remains immediate WhatsApp shortcut/fallback.
- `Convidar` should not jump directly into a visually separate composer. It opens the canonical action sheet with:
  - mini event/occurrence card;
  - primary action: `Convidar pessoas do app`;
  - secondary action: `Enviar pelo WhatsApp`;
  - secondary action: `Compartilhar link`;
  - optional action: `Abrir lista completa` / `Ver todos`, routing to `/convites/compartilhar`.
- The full `/convites/compartilhar` route remains the advanced composer for recipient filtering, app inviteable list, phone contacts, group management, status hydration, and larger workflows.

### Promotion Gate Behavior
- The already-approved promotion modal should become the promotion variant of the same canonical action sheet family.
- Content still comes from `AppPromotionScreenController` and `AppPublicationSettings`.
- Anonymous web favorite/confirm/invite gates show the promotion variant; no phone-login UI on web.
- Native unauthenticated app invite/favorite gates keep canonical login redirect/phone OTP where applicable.

### Why Not Make Invite Just Another Share Sheet?
- Invite has domain state: recipient eligibility, sent statuses, occurrence identity, invite edge, contact privacy, group management, and authenticated mutation.
- Flattening invite into a generic share sheet would blur product semantics and make it harder to distinguish "I invited someone" from "I shared a public link."

### Why Not Keep Full Route as First Click?
- It is correct for the advanced composer but too heavy as the first hero action.
- It creates visual drift against the promotion gate and the event hero action model.
- It makes the user's first decision feel like navigation rather than contextual action.

## Proposed Decision
Adopt the shared action-sheet family as the canonical first-touch surface. Keep routes for advanced flows. Treat `Convidar`, `Compartilhar`, `WhatsApp`, and app-promotion gates as different action payloads inside one surface system, not as separate visual systems.

## Risks To Audit
1. Whether a bottom-sheet-first model conflicts with web desktop behavior or the mobile-frame contract.
2. Whether keeping immediate `Compartilhar`/`WhatsApp` but making `Convidar` open a sheet creates inconsistency.
3. Whether promotion/favorite gate should be converted from `AlertDialog` to action-sheet family after the user already approved its current shape.
4. Whether this would over-abstract and force complex invite composer content into a compact modal.
5. Whether tests can deterministically validate this UX contract without brittle visual assertions.

## Auditor Questions
### Elegance
- Does the shared action-sheet family reduce architectural/UI drift, or does it create an over-general component?
- Is the proposed split between first-touch sheet and full invite composer clean?
- Is this consistent with the existing immersive centralization direction?

### Performance
- Does this proposal introduce likely runtime/performance risk?
- Should the first-touch sheet avoid eager hydration of contacts/friends/statuses?
- What data should be lazy-loaded only after routing to the full composer?

### Test Quality
- What deterministic tests are required before implementation is considered safe?
- Which assertions should cover behavior without relying only on screenshots?
- What navigation/runtime cases must be covered for app, web anonymous, web authenticated, mobile viewport, and desktop mobile-frame?

## Frontend / Consumer Matrix
No backend producer surface is created or changed by this proposal. Consumer impact is Flutter tenant-public UI only.

| Surface | Consumer | Required Evidence If Implemented |
| --- | --- | --- |
| Event hero `Convidar` | Tenant-public Flutter/web | Widget + navigation test proving canonical sheet appears first and full composer route opens only from explicit sheet action. |
| Event hero `Compartilhar` | Tenant-public Flutter/web | Existing share-launcher tests or focused tests proving immediate system share remains intact. |
| Event hero `WhatsApp` | Tenant-public Flutter/web/native | Focused launcher tests proving direct WhatsApp/fallback behavior remains intact. |
| Favorite/app-promotion gate | Tenant-public web | Widget + Playwright tests proving canonical promotion variant appears and no phone-login or auto-open app happens before explicit CTA. |
| Full invite composer | Tenant-public app/native, authenticated web if allowed | Existing composer tests plus regression that it remains reachable and keeps recipient/status behavior. |

## Non-Authoritative Recommendation
Proceed with a TODO that designs and implements `TenantPublicActionSheet` as a shared presentation surface, then migrates first-touch event invite and promotion modal to that surface without changing invite backend semantics.
