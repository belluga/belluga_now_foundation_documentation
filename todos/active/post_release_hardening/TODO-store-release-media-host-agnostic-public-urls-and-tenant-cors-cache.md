# Title
Store Release: Media Host-Agnostic Public URLs And Tenant CORS Cache

## Artifact Identity
- **Artifact type:** `tactical_execution_contract`

## Context
Production exposed a media compatibility gap: some persisted media references still carry absolute tenant hosts from a prior public domain, which can make browsers request media from a different tenant host than the current public origin. The urgent hotfix is to allow public media CORS only across domains registered for the same tenant. The definitive fix is to stop persisting host-bound media URLs for tenant-owned media and make public URL materialization derive the host from the current request/domain context.

## Classification Note
- **Post-release hardening reclassification:** on `2026-04-30`, this TODO remained active but moved out of the current Android release gate into `active/post_release_hardening/`. Execute after release unless a new explicit business decision promotes it back into the release gate.

## Framing Source & Story Slice
- **Feature brief:** `direct-to-todo`
- **Primary story ID:** `n/a`
- **Direct-to-TODO rationale:** This is one bounded hardening slice: make media public references host-agnostic, then decide whether CORS should stay runtime-derived or be cached/rebuilt from tenant domain changes.

## Delivery Status Canon (Required)
- **Current delivery stage:** `Pending`
- **Qualifiers:** `Post-Release-Hardening`, `Release-Gate-Deferred`, `Cross-Stack`, `Backend-Contract`, `Performance-Sensitive`, `Security-Sensitive`
- **Next exact step:** analyze current persisted media URL fields and define the canonical storage/materialization contract before implementation approval.

## Scope
- [ ] Stop persisting tenant media URLs with absolute hosts for account profiles, static assets, event covers, type assets, branding/public-web media, map filter images, and any other `belluga_media`-owned public image flow.
- [ ] Persist a host-agnostic canonical media reference or path plus version metadata, then materialize absolute URLs from the current request/domain context at API response time.
- [ ] Backfill or normalize existing persisted absolute media URLs so old hosts no longer leak into public payloads.
- [ ] Re-evaluate public media CORS after URL normalization, including whether runtime tenant-domain checks remain sufficient or whether a static/cache rebuild on domain add/remove is justified.
- [ ] Ensure tenant-domain allowlists remain derived from canonical tenant domain data, never hardcoded per tenant.

## Out of Scope
- [ ] CDN/provider migration.
- [ ] Non-image binary asset redesign.
- [ ] Visual Flutter changes unrelated to consumed media URL contracts.

## Definition of Done
- [ ] Public payloads no longer depend on persisted absolute media hosts for tenant-owned media.
- [ ] Existing persisted absolute media URLs are migrated or normalized without breaking current media resolution.
- [ ] Public media CORS behavior is documented and either runtime-derived from tenant domains or backed by a deterministic rebuild/cache invalidation mechanism.
- [ ] Tests cover old-host payload compatibility, current-host materialization, tenant-domain CORS allow/deny behavior, and migration/backfill safety.
- [ ] Foundation docs identify the canonical media URL storage/materialization rule.

## Validation Steps
- [ ] Add fail-first Laravel tests proving an old absolute media host no longer leaks into API payloads after normalization/materialization.
- [ ] Add Laravel tests for public media CORS with same-tenant custom/default domains and rejected foreign origins.
- [ ] Add migration/backfill tests or deterministic data audit evidence for existing absolute media URL fields.
- [ ] Run focused Laravel media/security suites and any affected Flutter/web contract tests.

## Execution Lane Tracking (Required)
- **Local implementation branches:** `pending`
- **Promotion lane path:** `feature -> dev -> stage`
- **Lane-promoted threshold for this TODO:** `stage`
- **Production-ready threshold for this TODO:** `main`

## Profile Scope & Handoffs (Required Before `APROVADO`)
- **Primary execution profile:** `operational-coder`
- **Active technical scope:** `laravel`
- **Expected supporting profiles:** `assurance-tester-quality`, `operational-devops`

## Complexity
- **Level (`small|medium|big`):** `medium`
- **Checkpoint policy:** `full plan review before execution approval`
- **Why this level:** The behavior is conceptually narrow, but it touches persisted data, public API payloads, CORS/security, and media performance.

## Canonical Module Anchors (Required Before APROVADO)
- **Primary module doc:** `foundation_documentation/submodule_laravel-app_summary.md`
- **Secondary module docs:**
  - `foundation_documentation/modules/system_architecture_principles.md`
  - `foundation_documentation/endpoints_mvp_contracts.md`
- **Module decision consolidation targets:**
  - `foundation_documentation/submodule_laravel-app_summary.md#media--public-asset-ownership`

## Decisions To Freeze
- [ ] `D-MEDIA-URL-01` Tenant-owned media references should be persisted host-agnostically; absolute public URLs should be response materialization, not stored truth.
- [ ] `D-MEDIA-CORS-01` Public media CORS allowlists must be derived from tenant domains. Runtime lookup is the safe default; static/cache rebuild requires explicit invalidation and failure-mode design.

## Notes
- This TODO is linked to `TODO-store-release-belluga-media-canonical-image-flow-hardening.md` and should either satisfy or supersede the host-bound public URL portions of that broader hardening slice.
