---
title: "Shim behavior contract"
description: "ROUTING: Read before editing src/mysql2pdo*.php. Defines invariants agents must not break silently."
tags: [behavior, mysql-shim]
---

# Shim behavior contract

Source of truth for code: `src/mysql2pdo.class.php` (facade), `src/Mysql2pdo/Shim/*.php` (one class per `mysql_*` implementation), `src/mysql2pdo.func.php`. This page is the **agent-facing** summary.

**Per-method catalog** (48 php.net `mysql_*` + extension `mysql_multi_query_script`; line anchors, func-layer guards): [../knowledge/concepts/mysql-shim-method-registry.md](../knowledge/concepts/mysql-shim-method-registry.md).

## Connection

| Invariant | Detail |
|-----------|--------|
| Current link | `mysql2pdo::$currObj` set by `mysql_connect` / `mysql_pconnect` |
| After `mysql_select_db` | When `$link` is passed and valid, updates `$currObj` (legacy implicit `mysql_*` calls) |
| Charset | **No implicit charset** on connect/pconnect — same as classic `mysql_*` (consumer uses `mysql_set_charset()` or SQL) |
| Reuse | `mysql_connect` returns existing valid `$currObj` without opening a second link |
| `mysql_pconnect` | Does **not** reuse `$currObj`; opens PDO with `ATTR_PERSISTENT`; always assigns `$currObj` (even on failure); `$new_link` / `$client_flags` ignored in class; connect failure may `echo` error in class |

## Procedural vs class (`mysql_query`)

| Layer | Invalid / missing link | Empty query |
|-------|------------------------|-------------|
| `mysql_query()` func | `error_log` + **`trigger_error`** (No MySQL connection), `false` — does not call class | `trigger_error` (both layers) |
| `mysql2pdo::mysql_query()` class | `error_log` only, `false`; also requires `isset($link->host_info)` | same warning |

Func resolves `$link === null` to `$currObj`, validates, then calls class with an explicit `$link`. Direct class use skips func warnings.

## `mysql_unbuffered_query`

Func is a **thin delegate** (no extra guards). Class sets `$link = $currObj` when empty, then PDO unbuffered query (`MYSQL_ATTR_USE_BUFFERED_QUERY = false`) with **no** connect/query empty checks (unlike `mysql_query`). Invalid link returns `false`.

## `mysql_close`

Does not aggressively close the socket; returns `true` for compatibility with shutdown handlers. Connection ends with the PHP request.

## Errors and links

| Function | Behavior |
|----------|----------|
| `mysql_errno` / `mysql_error` | Resolve link via `resolveLink`; safe defaults when no link |
| `mysql_query` | Func: warning + `error_log` when link invalid; class: `error_log` only + `host_info` check — see **Procedural vs class** above |

Link validity for application code: use bootstrap-owned `$link` / `$currObj`, or `$link instanceof \Mysql2pdo\Driver\ShimLink` — not a dedicated shim helper.

## Extensions (beyond classic mysql)

| API | Purpose |
|-----|---------|
| `mysql_multi_query_script($link, $script)` | Multi-statement scripts via PDO (`nextRowset`) |
| `mysql2pdo::resolveLink` | Normalize link for error helpers (returns `ShimLink|null`) |

## Deprecations (func layer)

- `mysql_escape_string` → `E_USER_DEPRECATED`
- `mysql_pconnect` → `E_USER_DEPRECATED`

## Guards

- Class: `if (!class_exists('mysql2pdo', false))`
- Functions: `if (!function_exists('mysql_affected_rows'))`

## Out of scope for this package

- Prepared statements wrapper
- Connection config from env files

When adding a **new** public `mysql_*` shim, update [../../docs/API.md](../../docs/API.md), [../knowledge/concepts/mysql-shim-method-registry.md](../knowledge/concepts/mysql-shim-method-registry.md), and this file in the same change.
