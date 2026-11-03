# alvesbeto/mysql2pdo

**Legacy `mysql_*` functions for PHP 8+, implemented on `ext-pdo` (MySQL driver).**

PHP removed the old `mysql` extension in 7.0. Many applications still call `mysql_connect`, `mysql_query`, `mysql_fetch_assoc`, and the rest. This Composer library registers those global functions again and delegates to **PDO** under the hood, so you can run on PHP 8 without rewriting every query at once.

The shim implements all **48** functions from the [official PHP mysql reference](https://www.php.net/manual/en/ref.mysql.php), plus **`mysql_multi_query_script`** for batched SQL.

- **Repository:** [github.com/alvesbeto/mysql2pdo](https://github.com/alvesbeto/mysql2pdo)
- **API reference:** [docs/API.md](docs/API.md)

This is a **compatibility shim**, not a reimplementation of the C extension.

---

## Requirements

| Requirement | Notes |
|-------------|--------|
| PHP | `^8.0` (see `composer.json`) |
| Extension | `pdo` + MySQL driver (`pdo_mysql` on most Linux builds) |
| Database server | Any MySQL or MariaDB version your PDO MySQL driver supports |

You must install and enable `ext-pdo` and the MySQL PDO driver in `php.ini`. This package does not ship a database server or Docker setup.

---

## Installation

### From Packagist (recommended)

```bash
composer require alvesbeto/mysql2pdo
```

After the package is submitted to [Packagist](https://packagist.org/packages/alvesbeto/mysql2pdo), that is the normal way to install it. Prefer a tagged release when available, for example:

```bash
composer require alvesbeto/mysql2pdo:^1.0
```

**Not on Packagist yet?** Point Composer at the GitHub repository, then require `dev-main`:

```bash
composer config repositories.mysql2pdo vcs https://github.com/alvesbeto/mysql2pdo
composer require alvesbeto/mysql2pdo:dev-main
```

### From a local clone (path repository)

Use this when the package lives next to your app in a monorepo or you are hacking on mysql2pdo itself.

Add to the **consumer** project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../mysql2pdo",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "alvesbeto/mysql2pdo": "@dev"
    }
}
```

Then run:

```bash
composer update alvesbeto/mysql2pdo
```

Adjust `url` to the relative or absolute path of this repository.

### Without Composer (discouraged)

Load the two source files **in this order**:

```php
require_once '/path/to/mysql2pdo/src/mysql2pdo.class.php';
require_once '/path/to/mysql2pdo/src/mysql2pdo.func.php';
```

---

## Quick start

1. Load autoload **before** any `mysql_*` call (typically in your bootstrap):

```php
require_once __DIR__ . '/vendor/autoload.php';
```

2. Use the API like PHP 5 did. Connection and result handles are **`ShimLink`** and **`ShimResult` objects**, not `resource` types:

```php
$link = mysql_connect('127.0.0.1', 'user', 'password');
if (!$link) {
    die('Connection failed: ' . mysql_error());
}

mysql_select_db('my_database', $link);

$result = mysql_query('SELECT id, name FROM customers LIMIT 10', $link);
while ($row = mysql_fetch_assoc($result)) {
    echo $row['name'] . "\n";
}
mysql_free_result($result);
```

Successful `mysql_connect` / `mysql_pconnect` do **not** change charset. Use `mysql_set_charset($charset, $link)` or `mysql_query('SET NAMES …', $link)` like you would on PHP 5.

### Default connection (`mysql2pdo::$currObj`)

After `mysql_connect`, many functions accept an optional `$link`. If you omit it, they use the last connection stored in `mysql2pdo::$currObj` (same idea as classic `mysql_*`):

```php
$link = mysql_connect($host, $user, $pass);
mysql_query("SET time_zone = '-03:00'"); // uses $link via $currObj
```

If your app keeps the link in a variable (for example `$conMy01_0`), pass it explicitly: `mysql_query($sql, $conMy01_0)`.

Calling `mysql_select_db($database, $link)` with an explicit connection also updates `$currObj`, so the next `mysql_query($sql)` without a link uses that same connection.

**`mysql_connect` vs `mysql_pconnect`:** `mysql_connect` reuses a valid `$currObj` when `$new_link` is false; pass `$new_link = true` for a second connection. `mysql_pconnect` always opens a new `p:` connection and always sets `$currObj` (see [docs/API.md](docs/API.md)).

**SSL / socket:** set `$client_flags` to `MYSQL_CLIENT_SSL` and provide `MYSQL2PDO_SSL_*` env vars, or set `MYSQL2PDO_UNIX_SOCKET` for plain `localhost`. See [docs/API.md](docs/API.md).

**Legacy `is_resource($link)`:** use `$link instanceof \Mysql2pdo\Driver\ShimLink` or the helpers **`is_resource_custom()`** / **`get_resource_type_custom()`** (MySQL2PDO-style migration).

**`mysql_query` warnings:** the global function emits an extra PHP warning when there is no connection; `mysql2pdo::mysql_query()` logs only. **`mysql_unbuffered_query`** does not perform those connection checks—use it only when the link is known good.

---

## Migrating from copied shim files

If you previously shipped copies of `mysql2pdo.class.php` and `mysql2pdo.func.php` inside the project (common in legacy codebases):

```php
// Before
require_once __DIR__ . '/classes/config/mysql2pdo.class.php';
require_once __DIR__ . '/classes/config/mysql2pdo.func.php';

// After Composer install
require_once __DIR__ . '/vendor/autoload.php';
```

Remove the old copies once autoload is in place to avoid loading the shim twice.

**Encapsulation:** only this package talks to PDO (`PdoExecutor`). Application code should keep using `mysql_*`.

---

## How Composer autoload works

| Mechanism | File | What it loads |
|-----------|------|----------------|
| `classmap` | `src/mysql2pdo.class.php` | Global facade `mysql2pdo` (+ fallback autoload for `Mysql2pdo\…`) |
| `psr-4` | `src/Mysql2pdo/` | One class per shim under `Mysql2pdo\Shim\` |
| `files` | `src/mysql2pdo.func.php` | Global `mysql_*` functions and `MYSQL_*` constants |

If the shim is included twice in one request, guards skip redefinition:

- Class: `class_exists('mysql2pdo', false)`
- Functions: `function_exists('mysql_affected_rows')` (first function in the block)

---

## Extensions beyond classic `mysql_*`

| Symbol | Purpose |
|--------|---------|
| `mysql_multi_query_script($link, $script)` | Run a multi-statement SQL script via PDO, draining result sets |
| `mysql2pdo::resolveLink($link)` | Normalize a link to `ShimLink` (advanced / internal) |

See [docs/API.md](docs/API.md) for the full function list and return types.

### Namespaced API (maintainers / new code)

Namespace **`Mysql2pdo`**. Each legacy function has a dedicated class with a single static entrypoint:

```php
use Mysql2pdo\Shim\MysqlQuery;
use Mysql2pdo\ResolveLink;

// Same PDO backend as mysql_query(); skips procedural trigger_error on bad link
MysqlQuery::invoke('SELECT 1', $link);

ResolveLink::invoke($link); // ShimLink|null
```

Global **`mysql_*`** and **`mysql2pdo::`** remain the default for legacy apps.

---

## Behavior to know before production

### Connection handles and `is_resource()`

On PHP 8, `$link` and query results are shim objects. Replace `is_resource($link)` with `$link instanceof \Mysql2pdo\Driver\ShimLink` (and your app’s connection bootstrap). Before freeing, guard with a truthy `$result`; **`mysql_free_result($result)`** is a safe no-op when the result is invalid (same pattern as classic apps that skipped `is_resource` checks).

### `mysql_close()`

For long-running scripts and shutdown handlers, **`mysql_close()` does not tear down the socket**. It usually returns `true` without aggressively closing. The connection ends when the PHP request finishes. Do not rely on it to free the connection mid-request.

### Deprecated helpers

| Function | What happens |
|----------|----------------|
| `mysql_escape_string()` | Emits `E_USER_DEPRECATED`; use `mysql_real_escape_string()` or prepared statements |
| `mysql_pconnect()` | Emits `E_USER_DEPRECATED`; always new `p:` connect (does not reuse `$currObj` like `mysql_connect`); may echo connect errors |

### Query helpers

| Topic | Detail |
|-------|--------|
| `mysql_query()` | Global: `error_log` + `trigger_error` if no connection; class method: log only, stricter `host_info` check |
| `mysql_unbuffered_query()` | No empty-query or connection guards in the shim—unlike `mysql_query()` |

### Security

This shim **does not add prepared statements**. `mysql_real_escape_string()` uses PDO quoting when the link is valid; otherwise it falls back to `addslashes()` (legacy adapter behavior).

For new code, prefer **PDO prepared statements** or an ORM. Do not grow new SQL built from string concatenation.

### Constants

If not already defined, the shim defines `MYSQL_BOTH`, `MYSQL_NUM`, `MYSQL_ASSOC`, and the `MYSQL_CLIENT_*` flags (legacy numeric values).

---

## Developing this package

From the repository root:

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2pdo.class.php
php -l src/mysql2pdo.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2pdo'));"
```

Expected output: `bool(true) bool(true)`.

### Integration tests

```bash
composer test
```

Uses Docker (MariaDB on port **3307**), database `mysql2pdo_test`, and removes the container and volume when finished. Default output is **testdox** (one line per test) plus a **deprecations** section at the end.

```bash
composer test:ci          # compact summary for CI
composer test -- --debug  # maximum PHPUnit detail
```

**Maintainers and coding agents:** start at [AGENTS.md](AGENTS.md) (index: [.agents/index.md](.agents/index.md)). Shim contract: [.agents/specs/shim-behavior.md](.agents/specs/shim-behavior.md).

---

## License

MIT — see [LICENSE](LICENSE).

## Credits

Implementation evolved from the **mysql2i** adapter used in legacy Beto Souza projects. Source comments credit contributors for parts of `mysql_field_flags` / `mysql_field_type` (php.net / Stack Overflow).
