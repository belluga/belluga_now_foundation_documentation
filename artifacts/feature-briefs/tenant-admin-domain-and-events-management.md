# Tenant Admin Domain And Events Management

## Artifact Role
- **Why this brief exists now:** The request combines two tenant-admin improvements that touch different contracts and can be delivered independently: tenant web-domain management and tenant-admin event operations hardening. The brief is needed to choose the current bounded slice and keep missing contract work from leaking into implementation by accident.
- **What this brief is not:** canonical module documentation, project constitution, system roadmap, tactical TODO, or implementation authority.

## Source Idea / Request
- Add Tenant Domain management and Improve Event Tenant Admin Management.

## Problem / Desired Outcome
- **Problem:** Tenant Admin currently manages typed mobile app identifiers through `/admin/api/v1/appdomains`, but tenant web domains are only partially exposed in Laravel write/delete endpoints and are not documented or surfaced in Flutter settings. Tenant Admin event operations already support backend filtering, but the Flutter management screen still lacks explicit venue and related-account-profile filters, still presents a low-signal event list, and the current event-management path still hardcodes dynamic account-profile types instead of treating them as runtime data. `artists`, `artistIds`, and `artistProfiles` are the confirmed examples in the current codebase, but the underlying problem is broader than one specific profile type.
- **Desired outcome:** Tenant admins can manage active web domains in the admin surface without conflating them with mobile app identifiers, and event operators can search, filter by venue or canonical related account profile, and inspect the event list with clearer operational context only after the touched event-management path stops hardcoding dynamic account-profile types.
- **Why now:** The user explicitly requested both capabilities, and both map to the same tenant-admin product area, but only one bounded execution slice should be opened first.

## Constraints / Non-Goals
- **Constraints:** Preserve the approved split between `settings.app_links` credentials and typed mobile `appdomains`; keep tenant-admin event filtering backend-owned; treat venue filtering as location/account-profile filtering and related-profile filtering as canonical `event_parties` / `linked_account_profiles` behavior; treat any hardcoded dynamic account-profile type reference in the touched event-management path as a blocker that must be removed before the new filters/UX are added; update foundation documentation before or with code changes; do not infer new behavior from the missing `project_constitution.md`; avoid coupling admin management to runtime bootstrap payloads unless the module contract requires it.
- **Non-goals:** Public events/agenda search, event-form payload changes, event-type registry work, tenant-domain recycle-bin UI, or broader tenant bootstrap/runtime resolver refactors.

## Canonical Touchpoints
- **Constitution impact:** `possible` - `project_constitution.md` is absent, so module docs are the current authority; no constitution change is proposed in this slice.
- **Roadmap impact:** `none` - this is an implementation/contract-hardening slice inside existing tenant-admin capability boundaries.
- **Primary module candidates:** `foundation_documentation/modules/tenant_admin_module.md`
- **Secondary module candidates:** `foundation_documentation/modules/events_module.md`, `foundation_documentation/modules/flutter_client_experience_module.md`

## Evidence / References
- `foundation_documentation/modules/tenant_admin_module.md`
- `foundation_documentation/modules/events_module.md`
- `foundation_documentation/modules/flutter_client_experience_module.md`
- `foundation_documentation/policies/scope_subscope_governance.md`
- `foundation_documentation/todos/active/mvp_slices/TODO-v1-event-parties-canonicalization-and-legacy-migration.md`
- `laravel-app/routes/api/tenant_api_v1.php`
- `laravel-app/app/Http/Api/v1/Controllers/DomainController.php`
- `laravel-app/app/Application/Tenants/TenantDomainManagementService.php`
- `laravel-app/packages/belluga/belluga_events/src/Http/Api/v1/Requests/EventIndexRequest.php`
- `laravel-app/tests/Feature/Events/EventCrudControllerTest.php`
- `flutter-app/lib/presentation/tenant_admin/settings/widgets/tenant_admin_settings_app_links_section.dart`
- `flutter-app/lib/presentation/tenant_admin/events/controllers/tenant_admin_events_controller.dart`
- `flutter-app/lib/presentation/tenant_admin/events/screens/tenant_admin_events_screen.dart`

## Ambiguities To Resolve Before TODO
| ID | Ambiguity | Why It Matters | Current Evidence | Handling (`resolve now|carry as TODO assumption|block`) |
| --- | --- | --- | --- | --- |
| `AMB-01` | Tenant web domains have write/delete/restore endpoints but no documented active-list read contract. | Flutter cannot safely render delete/manage actions without stable IDs and an admin read source. | `routes/api/tenant_api_v1.php` exposes `POST/DELETE/restore/force-delete` for `/admin/api/v1/domains`, while `tenant_admin_module.md` documents only `appdomains`. | `resolve now` |
| `AMB-02` | It is unclear whether "Tenant Domain management" should include recycle-bin restore flows. | Restore/force-delete would require deleted-domain read semantics, not just active-domain CRUD. | Laravel exposes restore/force-delete writes, but no deleted-domain listing contract is documented or surfaced in Flutter. | `resolve now` |
| `AMB-03` | `project_constitution.md` is missing. | It changes which source of truth is authoritative when roadmap-level assumptions would otherwise be inferred. | The file is absent; module docs and scope governance docs are present. | `carry as TODO assumption` |
| `AMB-04` | Event management now needs explicit venue and related account-profile filters, but there is no current admin-list query contract for them. | The backend and Flutter list/filter contracts need widening, and the filter inputs must not depend on any hardcoded dynamic account-profile type. | `EventIndexRequest` only accepts `search/status/archived/temporal`; the active event-parties TODO marks artist-shaped ownership as legacy. | `resolve now` |
| `AMB-05` | The touched event-management path still contains live hardcoded dynamic account-profile type references even though `event_parties` is intended to replace them. | Adding new event-management UX on top of artist-shaped DTOs/payloads would deepen the wrong abstraction instead of fixing it. | Backend admin payload still emits `artists`; Flutter tenant-admin decoder/model still use `artistIds` / `artistProfiles`; the active event-parties TODO treats that ownership model as legacy. | `resolve now` |

## Story Decomposition
Treat each row as a candidate delivery slice. A tactical TODO should normally map to one primary story slice, not to the entire table.

| Story ID | Story / User Value | Primary Module | Secondary Modules | Acceptance Boundary | Candidate Validation Signal | Candidate TODO Decision (`create-now|defer|split-further|merge-with-other`) | Dependencies / Blockers | Notes |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `ST-01` | Tenant admins can manage active web domains and operate the event list with search, explicit venue/related-profile filters, clearer operational metadata, and no hardcoded dynamic account-profile types in the touched event-management path. | `tenant_admin_module.md` | `events_module.md`, `flutter_client_experience_module.md` | Active web-domain read/create/delete is documented and surfaced in Tenant Admin; event management exposes backend-owned search, explicit venue and related-account-profile filters using canonical contracts, richer list cards, and the touched event-management read/model/filter surface no longer depends on hardcoded dynamic account-profile types. | Laravel feature tests for domains + event filters; Flutter repository/controller/screen tests; analyzer; manual tenant-admin smoke. | `create-now` | Missing active-domain read contract and missing admin event filter contract must be added and documented. | Preserves `appdomains` as mobile identifiers; preserves canonical event-party ownership and does not absorb deleted-domain restore UI. |
| `ST-02` | Tenant admins can review deleted domains and restore or permanently remove them. | `tenant_admin_module.md` | `flutter_client_experience_module.md` | Deleted-domain list/read semantics exist and Flutter exposes restore/force-delete flows safely. | Laravel deleted-domain tests; Flutter recycle-bin UI tests; manual restore/force-delete smoke. | `defer` | Blocked by missing deleted-domain read/list contract and higher UI scope. | Keep out of the current slice. |

## Retire This Brief When
- The active tactical TODO for `ST-01` is approved and becomes the authoritative execution contract for this request.
