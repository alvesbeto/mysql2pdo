---
title: "Technical architecture"
description: "ROUTING: Read for stack, layout, autoload, and verify commands. Skip for a one-line comment fix in src/."
tags: [tech, architecture]
---

# Technical architecture

## Runtime

| Item | Value |
|------|--------|
| PHP | `^8.0` (`composer.json`) |
| Extension | `ext-pdo` + MySQL PDO driver (required) |
| Package type | `library` |

No Docker or database in this repo. Consumers bring their own MySQL/MariaDB.

## Layout

| Path | Role |
|------|------|
| `src/mysql2pdo.class.php` | Global facade `mysql2pdo` + PSR-4 fallback autoloader for manual installs |
| `src/Mysql2pdo/` | Namespaced implementation (`LinkContext`, `ResolveLink`, `Driver\*`, `Shim\*`) |
| `src/Mysql2pdo/Shim/*.php` | One `final` class per shim; public entrypoint `::invoke()` |
| `src/mysql2pdo.func.php` | Global `mysql_*` functions + `MYSQL_*` constants |
| `docs/API.md` | Human API reference (not duplicated in `.agents/`) |
| `README.md` | Install and usage |
| `.agents/` | Agent context (OKF + SDD; method registry in knowledge) |

## Autoload

```json
"psr-4": { "Mysql2pdo\\": "src/Mysql2pdo/" },
"classmap": ["src/mysql2pdo.class.php"],
"files": ["src/mysql2pdo.func.php"]
```

Composer PSR-4 loads namespaced classes. The global `mysql2pdo` facade stays in the classmap for drop-in compatibility. `mysql2pdo.class.php` registers a fallback PSR-4 autoloader when the package is included without Composer.

**Namespaced use (optional):** `Mysql2pdo\Shim\MysqlQuery::invoke($sql, $link)` — same behavior as `mysql_query()` / `mysql2pdo::mysql_query()` without the procedural-layer extras.

Class loads on first `mysql2pdo::` or `Mysql2pdo\…` use; global functions load at autoload bootstrap.

## Encapsulation

`PDO` / shim handles appear **only** under `src/`. See [ADR-0006](../adrs/ADR-0006-pdo-driver-in-src.md).

## Verify

Full commands: [../runbooks/validate.md](../runbooks/validate.md).

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2pdo.class.php
php -l src/mysql2pdo.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2pdo'));"
```

## Tests

Integration suite under `tests/Integration/MysqlFunctionsTest.php`: **one test per global `mysql_*` (49)** plus `mysql2pdo::resolveLink()`.

```bash
composer test
```

Orchestration: [../scripts/run-tests.sh](../scripts/run-tests.sh) — `trap` ensures `docker compose down -v` even when PHPUnit fails.

Agent memory: [../knowledge/index.md](../knowledge/index.md). Module map: [CAPABILITY_MAP.md](CAPABILITY_MAP.md).
