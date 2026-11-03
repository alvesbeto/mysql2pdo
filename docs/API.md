# API reference — alvesbeto/mysql2pdo

All functions below are registered in the global scope after autoload (or after `require` of `mysql2pdo.func.php`). Implementation delegates to static methods on **`mysql2pdo`** in `src/mysql2pdo.class.php`.

**Coverage:** every function listed in the [PHP mysql extension manual](https://www.php.net/manual/en/ref.mysql.php) (**48** symbols) is implemented, plus **`mysql_multi_query_script`** (multi-statement scripts; not part of the original extension).

Signatures follow the historical PHP `mysql_*` documentation unless noted otherwise.

---

## Connection and database

### `mysql_connect($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)`

Opens a connection via PDO. Reuses `mysql2pdo::$currObj` when a valid connection already exists and **`$new_link` is false**. Pass **`$new_link = true`** to open a separate link. Does **not** set charset; use `mysql_set_charset()` or your app’s SQL if needed.

**`$client_flags`:** when **`MYSQL_CLIENT_SSL`** is set, PDO SSL options are read from environment variables:

| Variable | Purpose |
|----------|---------|
| `MYSQL2PDO_SSL_CA` | CA file path |
| `MYSQL2PDO_SSL_CERT` | Client certificate |
| `MYSQL2PDO_SSL_KEY` | Client private key |
| `MYSQL2PDO_SSL_VERIFY` | `0` or `1` for server cert verification |

Other flags (`MYSQL_CLIENT_COMPRESS`, etc.) are ignored.

**Host / socket:** plain `localhost` uses TCP **`127.0.0.1`** unless **`MYSQL2PDO_UNIX_SOCKET`** is set. You can also use `host:/path/to.sock` or `host:3306` in the `$host` string.

**Returns:** `\Mysql2pdo\Driver\ShimLink|false`

### `mysql_pconnect(...)`

Persistent connection (`p:` host prefix). Does **not** set charset automatically.

Unlike `mysql_connect`, **`mysql_pconnect` does not reuse an existing valid `$currObj`** — it always opens a new persistent connection and assigns `$currObj` (even when connect fails). Honors **`$client_flags`** (SSL env as above). **`$new_link` is ignored.** On connect failure the class may **`echo`** the error message in addition to logging.

### `mysql_select_db($dbname, $link = null)`

Selects a database with `USE` via PDO. When `$link` is a connected `ShimLink`, updates `mysql2pdo::$currObj` so later calls without `$link` use that connection (legacy behavior). Returns `false` if the link or database name is empty.

### `mysql_close($link = null)`

Clears the current link when appropriate and **releases the PDO handle** on the `ShimLink` (`pdo` and `host_info` become null). Returns **`true`** even when the link was already invalid (shutdown-handler friendly). After close, `mysql_ping` returns false.

### `mysql_ping($link = null)`

Checks the connection by running `SELECT 1`.

---

## Queries

### Global `mysql_query()` vs `mysql2pdo::mysql_query()`

Both validate empty queries (`E_USER_WARNING`). When the connection is missing or invalid:

- **`mysql_query()`** (global): writes to `error_log` and emits **`trigger_error('mysql_query(): No MySQL connection')`**, then returns `false` without calling the class when the link check fails in the func layer.
- **`mysql2pdo::mysql_query()`**: writes to `error_log` only (no `trigger_error`); also requires `isset($link->host_info)` on the ShimLink instance.

The global function resolves `$link === null` to `mysql2pdo::$currObj`, validates, then calls the class with that link.

### `mysql_query($query, $link = null)`

Executes SQL via PDO. See **Global vs class** above for warning differences.

### `mysql_unbuffered_query($query, $link = null)`

Unbuffered read via `PDO::MYSQL_ATTR_USE_BUFFERED_QUERY`. The global function is a thin delegate. The class uses `$currObj` when `$link` is empty but performs **no** empty-query or connection checks (unlike `mysql_query`). Invalid links return `false`.

### `mysql_db_query($database, $query, $link = null)`

Selects `$database` and runs `$query`.

### `mysql_multi_query_script($link, $script)`

**Package extension:** runs a multi-statement script, frees intermediate result sets, returns `false` on ShimLink error.

---

## Result sets

| Function | Notes |
|----------|--------|
| `mysql_fetch_array($result, $resulttype = MYSQL_BOTH)` | `null` becomes `false` |
| `mysql_fetch_assoc($result)` | same |
| `mysql_fetch_row($result)` | validates `ShimResult` in the func layer |
| `mysql_fetch_object($result, $class_name = 'stdClass', $params = [])` | |
| `mysql_fetch_field($result, $field_offset = null)` | |
| `mysql_fetch_lengths($result)` | |
| `mysql_num_rows($result)` | |
| `mysql_num_fields($result)` | |
| `mysql_data_seek($result, $offset)` | |
| `mysql_free_result($result)` | safe no-op when result is invalid |
| `mysql_result($result, $row, $field = null)` | fetch by row index or column name |

---

## Field metadata

| Function |
|--------|
| `mysql_field_name($result, $field_offset)` |
| `mysql_field_type($result, $field_offset)` |
| `mysql_field_len($result, $field_offset)` |
| `mysql_field_flags($result, $field_offset)` |
| `mysql_field_table($result, $field_offset)` |
| `mysql_field_seek($result, $fieldnr)` |

---

## Errors and server info

| Function | Typical return |
|----------|----------------|
| `mysql_errno($link = null)` | ShimLink error code; `0` without a link |
| `mysql_error($link = null)` | string; `''` without a link |
| `mysql_affected_rows($link = null)` | |
| `mysql_insert_id($link = null)` | |
| `mysql_info($link = null)` | Best-effort `Records:` / `Rows matched:` after write queries; `''` after SELECT |
| `mysql_stat($link = null)` | |
| `mysql_get_client_info()` | |
| `mysql_get_host_info($link = null)` | |
| `mysql_get_proto_info($link = null)` | |
| `mysql_get_server_info($link = null)` | |
| `mysql_client_encoding($link = null)` | |

---

## Escaping and charset

### `mysql_real_escape_string($escapestr, $link = null)`

Preferred for escaping with an active connection.

### `mysql_escape_string($escapestr)`

Legacy escape **without** an active link (uses `$currObj` when set, otherwise `addslashes()`). Prefer **`mysql_real_escape_string()`** when connected.

### `mysql_set_charset($charset, $link = null)`

---

## Catalog / administration (legacy)

| Function |
|--------|
| `mysql_create_db($database_name, $link = null)` |
| `mysql_drop_db($database, $link = null)` |
| `mysql_list_dbs($link = null)` → `SHOW DATABASES` |
| `mysql_list_tables($database, $link)` |
| `mysql_list_fields($database_name, $table_name, $link = null)` → `SHOW COLUMNS` |
| `mysql_list_processes($link = null)` → legacy quirk: returns `mysql_thread_id` |
| `mysql_db_name($result, $row, $field = null)` |
| `mysql_tablename($result, $row, $field = null)` |
| `mysql_thread_id($link = null)` |

---

## Class `mysql2pdo` (advanced)

Public property:

- **`mysql2pdo::$currObj`** — current ShimLink connection

Static methods mirror `mysql_*` functions (same names with an `mysql_` prefix on the method, e.g. `mysql2pdo::mysql_query`).

**Note:** Calling the class directly skips procedural-layer extras (for example the extra `trigger_error` on failed `mysql_query()`). Prefer globals for legacy apps that expect PHP 5 warning behavior.

Helper methods:

- **`mysql2pdo::resolveLink($link = null): ?ShimLink`**
- **`mysql2pdo::mysql_multi_query_script($link, $script): bool`**

Direct class usage (no global function):

```php
$link = mysql2pdo::mysql_connect('127.0.0.1', 'user', 'pass');
mysql2pdo::mysql_select_db('app', $link);
$res = mysql2pdo::mysql_query('SELECT 1', $link);
```

---

## Manual load order

1. `src/mysql2pdo.class.php` (loads `Mysql2pdo\…` via built-in autoloader)
2. `src/mysql2pdo.func.php`

With Composer, only `vendor/autoload.php` is required.

---

## Migration helpers (not in php.net)

### `is_resource_custom($var): bool`

Returns true for a connected `ShimLink` or a non-freed `ShimResult` (replacement for `is_resource()` on legacy mysql handles).

### `get_resource_type_custom($var): string`

Returns `'mysql link'`, `'mysql result'`, or `'unknown'`.

---

## Namespace `Mysql2pdo`

| Type | Class | Role |
|------|-------|------|
| Driver | `Driver\PdoExecutor`, `Driver\ShimLink`, `Driver\ShimResult` | PDO backing store |
| Session | `LinkContext` | Current connection + close guard (backs `mysql2pdo::$currObj`) |
| Helper | `ResolveLink` | Same as `mysql2pdo::resolveLink()` |
| Shim | `Shim\MysqlConnect`, `Shim\MysqlQuery`, … | One class per function; call `::invoke(...)` with the same parameters as the global |

Example:

```php
use Mysql2pdo\Shim\MysqlSelectDb;

MysqlSelectDb::invoke('my_database', $link);
```

Procedural globals still apply func-layer warnings and deprecations where documented below.

---
