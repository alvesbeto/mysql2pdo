# Changelog

All notable changes to **alvesbeto/mysql2pdo** (PDO-backed `mysql_*` shim).

## [1.0.3] - 2026-11-03

### Changed

- Removed **`E_USER_DEPRECATED`** from `mysql_pconnect()` and `mysql_escape_string()` — this library replaces the removed `mysql_*` extension without nagging legacy callers.

### Fixed

- **`mysql_close()`** idempotent: closes PDO only when the link is still connected; repeated close, invalid handles, and `mysql_close()` with no open link return `true` without warnings.

## [1.0.2] - 2026-11-03

### Changed

- Removed references to proprietary consumer products and internal naming patterns from public and agent docs; generic consumer wording only.

## [1.0.1] - 2026-11-03

### Changed

- README aligned with 1.0.0 behavior (`mysql_close`, Packagist install, migration helpers).
- Release runbook: SSH remote, tags, Packagist sync (`.agents/runbooks/release.md`).

## [1.0.0] - 2026-11-03

First stable release on **PDO** (`ext-pdo`). Package name `alvesbeto/mysql2pdo`; namespace `Mysql2pdo\`; facade `mysql2pdo`.

### Added

- **48** php.net `mysql_*` functions plus **`mysql_multi_query_script`**.
- Driver: `PdoExecutor`, `ShimLink`, `ShimResult`; shims under `Mysql2pdo\Shim\*`.
- **`mysql2pdo::resolveLink()`** / `Mysql2pdo\ResolveLink`.
- PHPUnit integration tests (`composer test`, Docker MySQL on port 3307).
- **`$new_link`** on `mysql_connect` (separate PDO connection when true).
- **SSL:** `MYSQL_CLIENT_SSL` + env `MYSQL2PDO_SSL_CA`, `MYSQL2PDO_SSL_CERT`, `MYSQL2PDO_SSL_KEY`, `MYSQL2PDO_SSL_VERIFY`.
- **Unix socket:** env `MYSQL2PDO_UNIX_SOCKET` for plain `localhost` (default remains TCP `127.0.0.1`).
- **Migration helpers:** `is_resource_custom()`, `get_resource_type_custom()`.
- **`mysql_info()`** best-effort strings after INSERT/UPDATE/DELETE (PDO limits documented).

### Changed

- Handles are **`ShimLink` / `ShimResult` objects**, not `resource`.
- **`mysql_close()`** releases the underlying PDO handle.
- **`mysql_result()`** supports qualified columns (`table.column`).
- PHP **8.5**-safe MySQL PDO attributes via `PdoMysqlAttributes`.

### Requirements

- PHP `^8.0`, `ext-pdo` (MySQL driver).

### Notes

- Earlier git tags `v1.0.x`–`v2.0.0` on this repository referred to the pre-PDO / mysqli lineage before this package identity. **Packagist stable installs should use `^1.0` from this release forward.**
