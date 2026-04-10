# TODO (V1): Account Profile Type Display Label Metadata

**Status legend:** `- [ ] ⚪ Pending` · `- [ ] 🟡 Provisional` · `- [ ] 🟧 Local-Implemented` · `- [ ] 🟣 Lane-Promoted` · `- [x] ✅ Production-Ready`.
**Status:** Completed (`active-lane cleanup synced on 2026-04-09 after delivery confirmation`)
**Current delivery stage:** `Completed`
**Qualifiers:** `Blocker-Resolved`, `Automated-Validated`, `Closure-Synced`
**Next exact step:** None. Archived to `todos/completed` on `2026-04-09`.
**Owners:** Flutter Team, Laravel Team
**Objective:** Extend the account-profile type registry contract so runtime consumers are no longer limited to a single generic `label`. The registry must expose structured display metadata sufficient for singular and plural usage in MVP public surfaces, while preserving backward compatibility for current `label` consumers and keeping the runtime path bootstrap-driven rather than request-time aggregated.
**Promotion lane path:** `dev -> stage -> main`
**Complexity:** `medium`
**Checkpoint Policy:** one planning checkpoint before approval + final decision-adherence review before delivery.
**Primary execution profile:** `Operational / Coder`
**Active technical scope:** `flutter`, `laravel`

---

## Scope Ownership

- **EnvironmentType:** `tenant`
- **Main scope:** `tenant_public`
- **Subscope:** `n/a`

| Route | Host Context | EnvironmentType | Main Scope | Subscope | Guard/Identity |
| --- | --- | --- | --- | --- | --- |
| `/agenda/evento/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/parceiro/:slug` | tenant | `tenant` | `tenant_public` | `n/a` | `TenantRouteGuard()` |
| `/admin/catalogo/tipos-de-perfil` | tenant | `tenant` | `tenant_admin` | `n/a` | `TenantAdminGuard()` |

---

## Module Anchors

- **Primary:** `../foundation_documentation/modules/partner_catalog_and_offer_module.md`
- **Secondary:** `../foundation_documentation/modules/tenant_admin_module.md`, `../foundation_documentation/modules/events_module.md`, `../foundation_documentation/modules/flutter_client_experience_module.md`

### Canonical Coverage Status

- `partner_catalog_and_offer_module.md`: authoritative for account-profile type semantics exposed to public consumers.
- `tenant_admin_module.md`: authoritative for tenant-admin profile-type registry CRUD contracts.
- `events_module.md`: authoritative for immersive event-detail consumer expectations once tabs become type/category-driven.
- `flutter_client_experience_module.md`: authoritative for bootstrap-driven runtime contracts and shared UI vocabulary.

### Decision Consolidation Targets

- Promote durable registry contract changes to `../foundation_documentation/modules/partner_catalog_and_offer_module.md` and `../foundation_documentation/modules/tenant_admin_module.md`.
- Promote only stable client-consumption/runtime implications to `../foundation_documentation/modules/flutter_client_experience_module.md` and `../foundation_documentation/modules/events_module.md`.

---

## References

- `foundation_documentation/todos/active/vnext_slices/TODO-vnext-account-profile-types.md`
- `../foundation_documentation/modules/partner_catalog_and_offer_module.md`
- `../foundation_documentation/modules/tenant_admin_module.md`
- `../foundation_documentation/modules/events_module.md`
- `lib/domain/partners/profile_type_definition.dart`
- `lib/infrastructure/dal/dto/app_data_dto.dart`
- `lib/infrastructure/dal/dto/tenant_admin/tenant_admin_account_profiles_response_decoder.dart`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Environment/EnvironmentResolverService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`

---

## Scope

- Extend the account-profile type registry/read model so a profile type exposes structured display-label metadata with at least:
  - singular display label
  - plural display label
- Preserve the current single `label` contract for compatibility during the MVP transition, while making the richer display metadata available to public/runtime consumers.
- Extend the environment/bootstrap payload so Flutter can consume the richer label metadata without request-time aggregation.
- Extend tenant-admin profile-type contracts so the richer label metadata can be authored and updated at the registry source of truth.
- Extend Flutter domain/DTO parsing for `ProfileTypeDefinition` and tenant-admin type-management flows to carry the richer label metadata cleanly.
- Keep the runtime path bootstrap-driven; do not decorate event/account-profile payloads via request-time aggregation just to resolve singular/plural labels.

## Out of Scope

- Full multilanguage catalogs or user/device language switching.
- Replacing the existing `label` field everywhere in one breaking cut.
- Additional new profile types or capability redesign.
- Taxonomy label summaries or taxonomy catalog changes.
- Reworking immersive event detail itself; that is tracked in the dependent main TODO.

---

## Decision Baseline (Frozen)

- `D-01`: The current single `label` field is insufficient for immersive event-detail category tabs because tab labels need plural semantics while identity surfaces still need singular/default semantics.
- `D-02`: Runtime consumers must continue to resolve profile-type display metadata from the bootstrap/registry path; request-time aggregation in event/account-profile queries is not acceptable.
- `D-03`: The registry contract must expose richer display metadata additively and keep current `label` consumers backward-compatible through the MVP transition.
- `D-04`: The richer display metadata must support at least `singular` and `plural`; exact storage key naming is implementation-local as long as the contract is explicit and additive.
- `D-05`: This lane is not full i18n. It is pt-BR-first metadata shaping that future multilanguage can supersede cleanly.
- `D-06`: Tenant-admin profile-type management remains the source of truth for these labels; public runtime consumers must not improvise pluralization client-side from slug or singular label.
- `D-07`: The immersive event-detail lane will consume the plural label for dynamic category tabs once this blocker lands.

**Last confirmed truth:** `2026-04-04` profile-type registry payloads now expose additive `labels.singular` / `labels.plural` in Laravel environment bootstrap and tenant-admin CRUD, while legacy `label` remains the singular compatibility alias; Flutter domain/DTO consumers parse both forms.

---

## Plan Review Gate (Medium)

### Issue Card P-01 — A single `label` cannot serve both identity and category-tab semantics
- Severity: `high`
- Evidence: current `ProfileTypeDefinition` and registry consumers only carry one `label`; immersive event-detail tabs need plural category names.
- Why now: the approved `Line Up -> dynamic category tabs` direction introduces a concrete public surface that needs plural labels.
- Option A (rejected): derive plural labels heuristically in Flutter from the current singular label.
- Option B (recommended): add structured display-label metadata to the registry/bootstrap contract and keep `label` as a temporary compatibility alias.
- Tradeoff:
  - A: brittle, language-specific, and conflicts with future i18n.
  - B: additive, bootstrap-driven, and source-of-truth aligned.

### Issue Card P-02 — Registry metadata must stay performant
- Severity: `medium`
- Evidence: profile types already ship through environment/bootstrap; they are low-cardinality registry data.
- Why now: a naive alternative would be request-time aggregation on public endpoints.
- Option A (recommended): enrich the existing bootstrap/registry contract once and keep public runtime reads cheap.
- Option B: aggregate or decorate each event/account-profile query with type label variants.
- Tradeoff:
  - A: cheapest runtime path.
  - B: repeated work on hot public reads for low-cardinality data that already has a bootstrap channel.

### Issue Card P-03 — Admin/source-of-truth drift risk
- Severity: `medium`
- Evidence: if public bootstrap and tenant-admin registry contracts diverge, runtime display labels will drift from authored source of truth.
- Why now: the blocker only pays off if registry management and runtime bootstrap stay aligned.
- Option A (recommended): extend tenant-admin CRUD + environment/bootstrap in the same lane.
- Option B: patch bootstrap only and defer admin/source-of-truth alignment.
- Tradeoff:
  - A: coherent contract.
  - B: short-lived drift and hidden debt.

## Failure Modes & Edge Cases

- Existing consumers that only read `label` must continue to behave during transition.
- Registry entries missing plural label must fail validation or degrade deterministically; runtime should not silently pluralize from slug.
- Tenant-admin create/update flows must not accept incompatible partial metadata that breaks bootstrap consumers.

## Uncertainty Register

- Assumption: account-profile types remain low-cardinality and bootstrap-appropriate, so additive label metadata does not create meaningful payload pressure.
- Confidence: `high`

---

## Touched Surfaces

- `foundation_documentation/todos/completed/TODO-v1-account-profile-type-display-label-metadata.md`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/Environment/EnvironmentResolverService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/app/Application/AccountProfiles/AccountProfileRegistryManagementService.php`
- `/home/elton/Dev/repos/belluga-ecosystem/belluga_now_docker/laravel-app/tests/**` profile-type/environment coverage
- `lib/domain/partners/profile_type_definition.dart`
- `lib/infrastructure/dal/dto/app_data_dto.dart`
- `lib/infrastructure/dal/dto/tenant_admin/tenant_admin_account_profiles_response_decoder.dart`
- `lib/presentation/tenant_admin/**` profile-type management flows if they own the create/update forms
- `test/**` bootstrap/profile-type parsing coverage

## Ordered Steps

1. Add fail-first Laravel + Flutter contract tests for richer profile-type display metadata.
2. Extend the Laravel registry/source-of-truth contract with structured singular/plural display-label metadata while preserving `label` compatibility.
3. Extend the environment/bootstrap response and tenant-admin profile-type CRUD/read contracts.
4. Extend Flutter DTO/domain parsing and any touched admin/public consumers that need the new metadata.
5. Run focused Laravel + Flutter suites and `fvm dart analyze --format machine`.
6. Promote stable contract decisions into canonical module docs.

## Test Strategy

- `test-first`

## Fail-First Targets

- Environment/bootstrap payload still exposes only a single `label`.
- Tenant-admin profile-type read/create/update payloads omit the richer display-label metadata.
- Flutter `ProfileTypeDefinition` cannot parse or expose singular/plural labels.
- Legacy `label` consumers break during the metadata extension.

## Definition of Done

- Profile-type registry contract exposes richer singular/plural display-label metadata additively.
- Environment/bootstrap and tenant-admin profile-type contracts are aligned.
- Flutter can parse and expose the richer display-label metadata without request-time aggregation.
- Existing `label` consumers remain compatible.
- Focused Laravel + Flutter tests and `fvm dart analyze --format machine` pass.

## Validation Steps

- `bash ../laravel-app/scripts/delphi/run_laravel_tests_safe.sh <profile-type/environment focused tests>`
- `fvm flutter test <profile-type bootstrap/admin focused tests>`
- `fvm dart analyze --format machine`
