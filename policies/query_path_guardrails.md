# Query Path Guardrails

## Purpose

Define non-negotiable rules for production runtime query paths across Flutter and Laravel so the product never relies on catalog preloads, page-by-page scans, or in-memory filtering/sorting when a direct or indexed query should exist.

These guardrails exist to prevent a specific class of regressions already observed in Belluga:

- screen/controller init paths preloading large datasets before first paint,
- page-by-page fetch loops followed by local filtering for a single entity,
- slug/id resolution against already-fetched lists instead of direct backend lookup,
- backend services loading collections and then filtering/sorting in PHP instead of using indexed queries,
- client or server “temporary fallbacks” that become permanent performance debt.

## Scope

These rules apply to:

- Flutter controllers, repositories, routes, and screen-critical flows,
- Laravel controllers, query services, integration adapters, and other request-path services,
- any request/response path executed during production runtime user flows.

These rules do **not** target:

- offline rebuild jobs,
- projection rebuilds,
- migrations/backfills,
- one-shot operational scripts,
- bounded admin/export tools explicitly documented as non-interactive.

## Core Principle

If a direct/indexed query path does not exist, the correct action is to add the correct contract/query/index.  
Fallback scans, preload loops, and local filtering are not acceptable substitutes.

## Rules

### `QPG-01` Direct lookup only for unique keys

Lookups by unique key (`id`, `slug`, `occurrence_id`, or equivalent) must use a direct backend query path.

Forbidden:

- scanning already-loaded client lists for a missing entity when a direct repository/backend method should exist,
- fetching multiple pages and comparing `slug`/`id` locally,
- server-side collection loads followed by `firstWhere`, `filter`, or manual comparisons for a unique-key lookup.

Required:

- a dedicated backend query path,
- a dedicated repository method,
- direct request-path use of that method.

### `QPG-02` No `fetchAll*` in production runtime

Production runtime flows must not depend on `fetchAll*`, `loadAll*`, or equivalent unbounded catalog preloads.

Forbidden:

- screen/controller `init()` calling `fetchAll*`,
- repository `init()` performing multipage network fetches,
- runtime form/controller initialization calling `loadAll*`,
- loading an entire dataset before rendering a paginated surface.

Required:

- first paint must depend only on the minimum page/section needed,
- secondary sections may load independently if bounded and explicitly scoped.

### `QPG-03` No local pagination over full collections

Production pagination must not be implemented as:

- `fetchAll() + sublist()`,
- `fetchAll() + filter() + sublist()`,
- or any equivalent “load everything then page locally” strategy.

If a surface is paginated, the backend/repository contract must be paginated natively.

### `QPG-04` No request-path collection scans with in-memory filter/sort

Production runtime request paths must not call `get()`, `cursor()`, `toList()`, or equivalent to load broader collections and then apply:

- `filter`,
- `where`,
- `sort`,
- `sortBy`,
- `firstWhere`,
- deduplication,
- or identity resolution

in PHP/Dart when the database/query layer should own that work.

Allowed only when:

- the path is explicitly non-interactive, and
- the code is documented as job/projection/backfill flow.

### `QPG-05` No “temporary” fallback query logic

If the correct contract/query does not exist yet, the code must not introduce:

- local page loops,
- local slug scans,
- redundant catalog caches,
- or partial runtime fallbacks

to compensate.

The missing contract is the bug and must be fixed at the source.

### `QPG-06` Production runtime search must have an explicit strategy

Search on large collections must declare an explicit strategy:

- indexed search,
- text-search service,
- or another documented canonical path.

Using `like`/`regex` without a documented search strategy is blocked by default.

### `QPG-07` One MVP exception only

The only approved exception today is:

- public text search for `Account Profile` in MVP.

Even this exception remains constrained:

- backend-only,
- paginated,
- never used for exact `slug/id` lookup,
- never implemented via client/runtime preload,
- never followed by broad in-memory post-filtering/post-sorting outside the backend query path,
- treated as temporary MVP debt until canonical indexed search is delivered.

No other exception is approved.

### `QPG-08` Contracts must not normalize the wrong path

Abstract contracts, repository mixins, or base classes must not embed expensive fallback defaults that normalize the wrong architecture.

Forbidden:

- abstract `fetchPage()` methods implemented by default via `fetchAll()` and local slicing,
- mixins that silently preload all entities to simulate pagination.

Required:

- page contracts stay page-native,
- list-all contracts stay explicitly bounded and out of production runtime paths.

### `QPG-09` Batch iteration must stay out of request paths

Full-collection iteration with `cursor()`/streaming is acceptable only for:

- jobs,
- projection rebuilds,
- reconciliation,
- migration/backfill,
- offline/admin operations explicitly documented as such.

These flows must not be reused as production runtime request handlers.

### `QPG-10` Time values must stay canonical

Presentation code must not directly reinterpret raw backend `DateTime` values when a canonical timezone conversion path already exists.

Required:

- domain/projection values expose canonical/localized dates, or
- presentation uses the canonical `TimezoneConverter`.

Forbidden:

- ad-hoc `toLocal()`, `toUtc()`, or raw formatting on backend timestamps in feature presentation when the canonical path exists.

## Review Checklist

Every production runtime query-path change must answer:

1. Is this lookup/page/search resolved directly by the backend contract?
2. Is the database/query layer doing the filtering/sorting?
3. Is the path index-backed or explicitly documented as the MVP `Account Profile` text-search exception?
4. Did we avoid `fetchAll*` / `loadAll*` in production runtime entirely?
5. Did we avoid default abstract-contract fallbacks that simulate pagination locally?

If any answer is `no`, the change is not ready.
