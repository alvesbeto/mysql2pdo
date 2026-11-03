## 2026-11-03: Integration tests + Docker MySQL

PHPUnit suite in `tests/Integration/`; `scripts/run-tests.sh` starts `docker-compose.test.yml`, runs tests, always `down -v`.

## 2026-11-03: PDO driver (3.0.0)

Replaced legacy driver with PDO (`Mysql2pdo\Driver\*`). Package `alvesbeto/mysql2pdo`; namespace/facade **`Mysql2pdo` / `mysql2pdo`**; link helper **`ResolveLink`**. Breaking for class **`mysql2i`** or non-shim link checks.

## 2026-11-03: Namespace `Mysql2pdo\` (no vendor prefix)

PSR-4 path `src/Mysql2pdo/`; dropped `Alvesbeto\` segment from namespace (2.0.0 docs).

## 2026-11-03: Split shim into namespaced classes (2.0.0)

One `Mysql2i\Shim\*` class per `mysql_*`; `LinkContext` + `ResolveLink`; global `mysql2i` facade unchanged. PSR-4 autoload; TECH, API, README, CHANGELOG updated.

## 2026-11-03: php.net parity audit (docs)

Verified **48/48** functions from [ref.mysql.php](https://www.php.net/manual/en/ref.mysql.php) in `src/`; **49** globals including `mysql_multi_query_script`. Fixed registry intro (**44** → **48 + extension**); README, API, shim-behavior, CHANGELOG 1.0.6.

## 2026-11-03: Remove `mysql_is_result` / `mysql_free_result_if_needed`

Dropped from `src/` (not historical `mysql_*`). Global count remains **48** php.net + **`mysql_multi_query_script`**; CHANGELOG 1.0.5.

## 2026-11-03: Remove `mysql_is_valid_link`

Dropped from `src/` (not historical `mysql_*`). Specs, registry, README, API, CHANGELOG 1.0.4 updated. Link checks: consumer bootstrap / `$link instanceof \Mysql2pdo\Driver\ShimLink`.

## 2026-11-03: Remove Graphify from repo

Removed skill, rule, `.graphifyrc`, `graphify-out/`. Agent memory stays in SDD + OKF ([project-memory-model](concepts/project-memory-model.md), method registry, specs).

## 2026-11-03: Shim purity — no implicit charset

Removed `set_charset('utf8mb4')` from connect/pconnect in `src/`; docs and specs updated. See CHANGELOG 1.0.2.

## 2026-11-03: Human API notes (query / pconnect / unbuffered)

Same func-vs-class and connection semantics documented in [docs/API.md](../../docs/API.md) and [README.md](../../README.md).

## 2026-11-03: Query layer docs (func vs class)

Documented `mysql_query` dual validation, `mysql_pconnect` vs `mysql_connect`, and unguarded `mysql_unbuffered_query` in [shim-behavior.md](../specs/shim-behavior.md) and [method registry](concepts/mysql-shim-method-registry.md).

## 2026-11-03: Full project memory + Graphify

Graphify CLI was integrated then removed (2026-11-03). OKF bundle retained: [project-memory-model](concepts/project-memory-model.md), [mysql-shim-method-registry](concepts/mysql-shim-method-registry.md), promotion map, taxonomy, specs routers, capability map `agent-tooling`.

## 2026-11-03: Agent SDD / OKF layout

Added `.agents/` (index map, specs routers, shim-behavior contract, skills mysql2i + sdd, ADRs 0001–0005, runbooks, knowledge bundle). Symlinks `.cursor`, `.claude`, root `AGENTS.md`.
