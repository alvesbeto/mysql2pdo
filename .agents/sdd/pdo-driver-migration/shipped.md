# PRD: PDO driver migration (archived)

**Status:** Shipped in **1.0.0**. This file records the SDD outcome; do not reopen without a new PRD.

## Objective

Replace the internal **mysqli** implementation with **PDO** while keeping the global `mysql_*` API and consumer call patterns.

## Scope

- `src/Mysql2pdo/Driver/*` (`PdoExecutor`, `ShimLink`, `ShimResult`, `PdoMysqlAttributes`)
- All `Mysql2pdo\Shim\*` delegates
- Package `alvesbeto/mysql2pdo`, namespace `Mysql2pdo`, facade `mysql2pdo`
- `ResolveLink` (formerly `ResolveMysqliLink`)
- Integration tests: `composer test` (Docker MySQL, volume removed after run)

## Out of scope

- Changing global function names (`mysql_*`)
- Prepared-statement wrapper for apps

## Consumer impact

- Requires `ext-pdo` + MySQL driver (not `ext-mysqli`)
- Connection/result handles: `\Mysql2pdo\Driver\ShimLink` / `ShimResult`
- Facade: `mysql2pdo` (not `mysql2i`)

## Success criteria

- All 48 php.net `mysql_*` + `mysql_multi_query_script` covered by integration tests
- No `mysqli_*` in `src/`
- [ADR-0006](../adrs/ADR-0006-pdo-driver-in-src.md) accepted; [ADR-0004](../adrs/ADR-0004-legacy-driver-superseded.md) superseded

## Promoted artifacts

- [specs/shim-behavior.md](../specs/shim-behavior.md)
- [specs/TECH.md](../specs/TECH.md)
- [knowledge/concepts/mysql-shim-method-registry.md](../knowledge/concepts/mysql-shim-method-registry.md)
- [CHANGELOG.md](../../CHANGELOG.md) 1.0.0
