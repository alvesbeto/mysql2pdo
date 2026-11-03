---
name: mysql2pdo
description: Default procedure for alvesbeto/mysql2pdo. PDO-backed mysql_* shim; strict scope in src/; read shim-behavior before editing.
---

# mysql2pdo

Map: [../../index.md](../../index.md).

## Scope

- Change **only** files the user named or the error implicates (usually `src/mysql2pdo.*`, `src/Mysql2pdo/**`).
- Do not edit `vendor/`, consumer apps, or Webcred from this repo unless explicitly requested.

## Before coding

1. [specs/shim-behavior.md](../../specs/shim-behavior.md) if touching `src/`.
2. [knowledge/concepts/mysql-shim-method-registry.md](../../knowledge/concepts/mysql-shim-method-registry.md) if touching a public `mysql_*` symbol.
3. [rules/php.md](../../rules/php.md) for PHP rules ([ADR-0006](../../adrs/ADR-0006-pdo-driver-in-src.md): PDO only under `src/`).
4. Grep for an existing method before adding a shim.

## Shim rules

- Business apps call **`mysql_*` only**; this package owns **PDO** inside `src/Mysql2pdo/Driver/` (`PdoExecutor`, `ShimLink`, `ShimResult`).
- Link checks in apps: `$link instanceof \Mysql2pdo\Driver\ShimLink` (not legacy driver handles).
- Keep guards (`class_exists('mysql2pdo')` / `function_exists('mysql_affected_rows')`) intact.
- New public surface → update `docs/API.md`, `specs/shim-behavior.md`, method registry, `CHANGELOG.md`.

## Progress

```
Task Progress:
- [ ] 1. Identify strict scope (file + lines)
- [ ] 2. Open index.md → one row only
- [ ] 3. If ambiguous or breaking → sdd skill first; stop after PRD approval
- [ ] 4. Surgical edit in src/
- [ ] 5. runbooks/validate.md + composer test (when behavior touches DB shims)
- [ ] 6. Update spec/docs/registry if behavior changed; note in knowledge/log if notable; no git write
```

## When to escalate to SDD

- Semver-major or consumer-breaking shim semantics.
- Multi-file release (CI, Packagist, docs, src, tests) with unclear order.

Use [../sdd/SKILL.md](../sdd/SKILL.md).
