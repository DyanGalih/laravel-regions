# Technical Decisions (`docs/memory/`)

This file stores durable technical and implementation decisions. For governance-level decisions or project standards, see `.specify/memory/DECISIONS.md`.

## Entry Lifecycle

Each decision follows this lifecycle:

```
Active → Needs Review → Superseded → (pruned)
```

- **Active**: The decision is current and must be honored by all features and AI agents.
- **Needs Review**: Implementation reality or new context suggests this decision may be outdated. It should still be honored until reviewed and explicitly changed.
- **Superseded**: A newer decision has replaced this one. Keep it for historical context until the next audit, then consider pruning.
- **Pruned**: During an audit, remove superseded entries that no longer provide historical value. This keeps the file focused.

### When to change status

| Current Status | Change To    | When                                                                                                       |
| -------------- | ------------ | ---------------------------------------------------------------------------------------------------------- |
| Active         | Needs Review | Verified implementation or tests contradict the decision, or recurring features follow a different pattern |
| Active         | Superseded   | A newer decision explicitly replaces this one                                                              |
| Needs Review   | Active       | Team confirms the decision still holds after review                                                        |
| Needs Review   | Superseded   | Team confirms a replacement decision                                                                       |
| Superseded     | _(remove)_   | Audit finds no remaining historical value                                                                  |

### Rules

- Never delete an Active decision without replacing or superseding it.
- Never silently ignore a decision. If it feels wrong, mark it Needs Review and resolve it.
- Keep at most 3–5 Superseded entries for context. Prune older ones during audits.

---

## Template

### YYYY-MM-DD - Decision title

**Status**
Active | Superseded | Needs review

**Why this is durable**
What cross-feature choice is likely to matter again?

**Decision**
What was decided and what boundary does it create?

**Tradeoffs**
What was gained, what was made harder, and when should this be reconsidered?

**Future mistake prevented**
What likely incorrect approach does this rule out?

**Evidence**
Diff, tests, review, incident, or repeated implementation evidence.

**Where to look next**
Files, modules, or specs future maintainers should inspect.

---

### 2026-05-15 - Regional Data ID Strategy

**Status**
Active

**Why this is durable**
Regional data (Provinces to Villages) uses standardized official codes (BPS/Kemendagri) that are hierarchical and stable across Indonesian government datasets.

**Decision**
Use official administrative codes as Primary Keys (bigint) rather than auto-incrementing integers for all regional tables (indonesia_provinces, indonesia_regencies, indonesia_districts, indonesia_villages).

**Tradeoffs**
- Gained: Perfect interoperability with other regional datasets; idempotent seeding (upserts are trivial); hierarchical lookups via prefixing.
- Made harder: Manual ID management during seeding; loss of standard auto-increment sequence (not needed for this static dataset).
- Reconsider: If moving to a dataset with unstable or non-numeric IDs.

**Future mistake prevented**
Prevents data duplication and complex mapping logic that occurs when using arbitrary auto-increment IDs for standardized hierarchical data.

**Evidence**
Identified during 001-region-api planning as the optimal pattern for 80k+ records.

**Where to look next**
specs/001-region-api/plan.md, src/Models/*.php

---

### 2026-05-15 - Auth-Agnostic Package Middleware

**Status**
Active

**Why this is durable**
Laravel libraries intended for external consumption must not assume the presence of specific auth guards (e.g., Sanctum) but should provide a hook for consumers to apply them.

**Decision**
Package API routes must use a configurable middleware group defined in config/region.php. The default value should be ['api'], allowing consumers to override it with ['api', 'auth:sanctum'] without modifying package core files.

**Tradeoffs**
- Gained: Extreme flexibility for consumers; decoupled security logic.
- Made harder: Initial setup requires documenting the middleware configuration.

**Future mistake prevented**
Prevents hardcoding auth middleware which would break installations in apps using custom or no authentication.

**Evidence**
Required by laravel-region Security Constitution to support diverse consumer environments.

**Where to look next**
config/region.php, routes/api.php

---

### 2026-05-15 - Multi-Database Prefix Strategy

**Status**
Active

**Why this is durable**
Packages that provide migrations must avoid table name collisions in the consumer's database, especially for generic terms like 'provinces' or 'villages'.

**Decision**
All regional tables must be resolved via config('region.table_prefix') in both Migrations and Models. The default prefix is indonesia_. In Models, this is handled by overriding getTable(). In Migrations, the table name is concatenated in the up() and down() methods.

**Tradeoffs**
- Gained: Collision-free installations; support for multiple regional packages in one app.
- Made harder: Eloquent's default table naming convention is bypassed.

**Future mistake prevented**
Prevents Table already exists errors during migration when a consumer already has a provinces table from another package or their own app logic.

**Evidence**
Implemented across all 4 regional models and migrations in 001-region-api.

**Where to look next**
src/Models/*.php, database/migrations/*.stub

---

### 2026-05-15 - Invokable Single-Action Controllers

**Status**
Active

**Why this is durable**
Multi-method controllers often lead to 'God Classes' and hidden dependencies. Enforcing invokable classes ensures each entry point has one responsibility.

**Decision**
All package controllers must implement the __invoke method. One controller per route action.

**Tradeoffs**
- Gained: Strict SRP, easier testing, modular routing.
- Made harder: Slightly more files in the Http/Controllers directory.

**Future mistake prevented**
Prevents business logic leakage and bloated controllers that are difficult to refactor.

**Evidence**
Implemented in ListProvincesController, SearchRegenciesController, etc.

---

### 2026-05-15 - Mandatory Top-Level Import Discipline

**Status**
Active

**Why this is durable**
Inline class resolution and local imports hide dependencies and make code analysis harder for both humans and AI agents.

**Decision**
All class imports must be declared at the top of the PHP file via 'use' statements. Local method imports are P0 violations.

**Tradeoffs**
- Gained: Full visibility of file dependencies at a glance.
- Made harder: None.

**Future mistake prevented**
Prevents dependency sprawl and 'shadow' dependencies that are missed during refactors.

**Evidence**
Applied globally across src/ and tests/ during the DyanGalih migration.

---

### 2026-05-15 - Mandatory camelCase DTO Properties

**Status**
Active

**Why this is durable**
Internal database naming (snake_case) often leaks into the API surface, making it feel less 'premium' and consistent with standard JavaScript/TypeScript frontend conventions.

**Decision**
All PHP class properties and DTO fields must use camelCase. Database mapping must be handled explicitly via #[MapInputName(SnakeCaseMapper::class)] to preserve internal snake_case compatibility.

**Tradeoffs**
- Gained: Consistent, frontend-friendly API surface; strict typing.
- Made harder: Slightly more verbose DTO declarations (requires attribute).

**Future mistake prevented**
Prevents 'naming leakage' where internal database column changes affect the public API JSON contract.

**Evidence**
Implemented in all Data objects under src/Data/ and codified in Architecture Constitution v1.2.0.
