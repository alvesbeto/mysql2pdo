---
type: Document
title: mysql shim method registry
description: Agent-facing catalog of every mysql_* shim and class helpers; line anchors in src/mysql2pdo.class.php and src/mysql2pdo.func.php.
generated:
  at: 2026-11-03T18:15:00-03:00
  by: process:agent
verified:
  - at: 2026-11-03T18:15:00-03:00
    by: process:agent
    note: Removed mysql_is_result / mysql_free_result_if_needed; line anchors from src/.
  - at: 2026-11-03T18:50:00-03:00
    by: process:agent
    note: Parity vs php.net ref.mysql.php — 48 official mysql_* + mysql_multi_query_script (49 globals); line anchors unchanged.
tags: [mysql-shim, api, behavior]
sources:
  - id: api-human
    title: docs/API.md
    path: docs/API.md
  - id: shim-contract
    title: specs/shim-behavior.md
    path: .agents/specs/shim-behavior.md
---

# mysql shim method registry

**49** global functions in `src/mysql2pdo.func.php`: all **48** symbols from the [PHP mysql extension reference](https://www.php.net/manual/en/ref.mysql.php), plus **`mysql_multi_query_script`** (Corbee extension). Each delegates to **`mysql2pdo::mysql_*`**, which forwards to **`Mysql2pdo\Shim\{Class}::invoke()`** (e.g. `MysqlQuery` for `mysql_query`). Implementation uses **`Mysql2pdo\Driver\PdoExecutor`** (PDO). Class-only helpers: `Mysql2pdo\ResolveLink`, `Mysql2pdo\LinkContext`, global `mysql2pdo::$currObj`.

Human-long signatures: [docs/API.md](../../../docs/API.md). Invariants: [specs/shim-behavior.md](../../specs/shim-behavior.md). **Registry “PDO / behavior” column:** historical label; since **3.0.0** read as **PDO / behavior**.

**Legend — Func column:** `—` = thin delegate; `warn` = extra `trigger_error`; `dep` = `E_USER_DEPRECATED`; `guard` = pre-check before class.

---

## Class helpers (no global function)

| Symbol | Location | PDO / behavior |
|--------|----------|-------------------|
| `mysql2pdo::$currObj` | global facade | Implicit link; backed by `LinkContext` |
| `LinkContext::$isClosing` (via API) | `Mysql2pdo\LinkContext` | Set during `mysql_close`; affects `mysql_real_escape_string` fallback |
| `ResolveLink::invoke($link)` | `Mysql2pdo\ResolveLink` | Returns `$link` if `ShimLink`, else current link, else `null`; used by `mysql_errno` / `mysql_error` |
| `mysql2pdo::resolveLink($link)` | facade → `ResolveLink` | Same as above |

---

## Connection and session

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_connect` | 57 | 58 | Reuses valid `$currObj` when `$new_link` false; else `PdoExecutor::connect` with `$client_flags` (SSL env); **no** implicit charset | — |
| `mysql_pconnect` | 498 | 285 | Always new `p:` connect; **no** reuse of valid `$currObj`; always `self::$currObj = $link`; `$new_link`/`$client_flags` unused in class; failure: `error_log` + **echo** in class | dep |
| `mysql_select_db` | 589 | 332 | `PdoExecutor::selectDb`; if explicit `$link` is connected, sets `$currObj`; empty `$dbname` → false | — |
| `mysql_close` | 37 | 45 | `PdoExecutor::closeLink` (null PDO); `$isClosing` guard; func early `true` if link invalid | guard |
| `mysql_ping` | 511 | 291 | `SELECT 1` + free result, not `SELECT 1 via PdoExecutor` | — |

---

## Queries

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_query` | 534 | 297 | `PdoExecutor::query`; func validates then calls class with resolved `$link`; class requires `host_info`; func adds **`trigger_error`** on bad link, class does not | warn |
| `mysql_unbuffered_query` | 652 | 361 | `PdoExecutor::query(..., unbuffered PDO query)`; `empty($link)` → `$currObj`; **no** empty-query or connect guards (unlike `mysql_query`) | — |
| `mysql_db_query` | 108 | 84 | `PdoExecutor::selectDb` then `PdoExecutor::query` | — |
| `mysql_multi_query_script` | 160 | 108 | **Extension:** `PdoExecutor::multiQueryScript`, drain results, false on any errno | — |

---

## Result set navigation

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_fetch_array` | 215 | 119 | `PdoExecutor::fetchArray`; `null` row → `false` | warn |
| `mysql_fetch_assoc` | 226 | 128 | `PdoExecutor::fetchAssoc`; `null` → `false`; class skips warning if invalid result | warn |
| `mysql_fetch_row` | 268 | 154 | `PdoExecutor::fetchRow`; `null` → `false` | warn |
| `mysql_fetch_object` | 253 | 149 | `PdoExecutor::fetchObject` (+ optional ctor `$params`); `null` → `false` | — |
| `mysql_fetch_field` | 237 | 137 | Seeks with loop when `$field_offset` set, then `PdoExecutor::fetchFieldDirect` | — |
| `mysql_fetch_lengths` | 248 | 143 | `PdoExecutor::fetchLengths` | — |
| `mysql_num_rows` | 489 | 276 | `PdoExecutor::numRows` | warn |
| `mysql_num_fields` | 480 | 267 | `PdoExecutor::numFields` | warn |
| `mysql_data_seek` | 86 | 69 | `PdoExecutor::dataSeek` | warn |
| `mysql_free_result` | 358 | 199 | Invalid result → `true` (no-op) | guard |
| `mysql_result` | 568 | 323 | Seek row; column name or `table.column`; numeric offset; else first column | warn |

---

## Field metadata

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_field_name` | 317 | 175 | `PdoExecutor::fetchFieldDirect` → `name` | — |
| `mysql_field_type` | 339 | 193 | Maps column type ids via PDO `getColumnMeta` | — |
| `mysql_field_len` | 310 | 169 | `PdoExecutor::fetchFieldDirect` → `max_length` | — |
| `mysql_field_flags` | 278 | 163 | Bit decode from column meta flags; lowercase string | — |
| `mysql_field_table` | 329 | 187 | `PdoExecutor::fetchFieldDirect` → `table` | — |
| `mysql_field_seek` | 324 | 181 | `PdoExecutor::fieldSeek` | — |

---

## Errors, counts, server info

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_errno` | 183 | 96 | `resolveLink`; no link → `0` | — |
| `mysql_error` | 193 | 102 | `resolveLink`; no link → `''` | — |
| `mysql_affected_rows` | 15 | 33 | Requires valid `ShimLink` with `host_info`; else `false` | — |
| `mysql_insert_id` | 412 | 238 | `PdoExecutor::insertId` | — |
| `mysql_info` | 403 | 232 | Best-effort write-query strings on `ShimLink::$lastInfoString`; `''` after SELECT | — |
| `mysql_stat` | 621 | 343 | `PdoExecutor::stat` | — |
| `mysql_get_client_info` | 369 | 208 | `PdoExecutor::getClientInfo($currObj)` | — |
| `mysql_get_host_info` | 376 | 214 | `PdoExecutor::getHostInfo` | — |
| `mysql_get_proto_info` | 385 | 220 | `PdoExecutor::getProtoInfo` | — |
| `mysql_get_server_info` | 394 | 226 | `PdoExecutor::getServerInfo` | — |
| `mysql_client_encoding` | 28 | 39 | `PdoExecutor::characterSetName` | — |
| `mysql_thread_id` | 643 | 355 | `PdoExecutor::threadId` | — |

---

## Escaping and charset

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_real_escape_string` | 555 | 318 | `PdoExecutor::realEscapeString`; if closing/invalid link → `addslashes` | — |
| `mysql_escape_string` | 203 | 113 | Uses `$currObj` or `addslashes`; insecure legacy | dep |
| `mysql_set_charset` | 612 | 337 | `PdoExecutor::setCharset` | — |

---

## Catalog / administration (legacy)

| Symbol | Class L | Func L | Backend / notes | Func |
|--------|---------|--------|-----------------|------|
| `mysql_create_db` | 70 | 63 | ``CREATE DATABASE `$name` `` via `PdoExecutor::query`; bool from errno | — |
| `mysql_drop_db` | 119 | 90 | ``DROP DATABASE `$name` `` | — |
| `mysql_list_dbs` | 421 | 244 | `SHOW DATABASES` → `ShimResult` or false | — |
| `mysql_list_tables` | 465 | 261 | ``SHOW TABLES FROM `$database` `` | — |
| `mysql_list_fields` | 441 | 249 | ``SHOW COLUMNS FROM `$table` `` — **`$database_name` unused in SQL** | — |
| `mysql_list_processes` | 456 | 255 | Returns **`PdoExecutor::threadId`** (scalar), not a result set | — |
| `mysql_db_name` | 95 | 78 | Seek row; first column of row | — |
| `mysql_tablename` | 630 | 349 | Same pattern as `mysql_db_name` | — |

---

## Global constants (`mysql2pdo.func.php`)

Defined when shim loads (legacy `MYSQL_*` names, values from shim): `MYSQL_BOTH`, `MYSQL_NUM`, `MYSQL_ASSOC`, `MYSQL_CLIENT_COMPRESS`, `MYSQL_CLIENT_SSL`, `MYSQL_CLIENT_INTERACTIVE`, `MYSQL_CLIENT_IGNORE_SPACE` (L11–L31).

**Migration helpers (not php.net):** `is_resource_custom`, `get_resource_type_custom` (end of `mysql2pdo.func.php`).

---

## Maintenance

When adding or changing a symbol:

1. Edit `src/`.
2. Update this registry (class/func line numbers and notes).
3. Update [shim-behavior.md](../../specs/shim-behavior.md) if invariants change.
4. Update [docs/API.md](../../../docs/API.md) for human API.
5. Run [runbooks/validate.md](../../runbooks/validate.md).
6. Note in [log.md](../log.md) if the delivery is notable.

For ambiguous names, grep `src/` or use `path::symbol` in the registry tables.
