# PRD: Compat improvements (PDO peer libraries)

## Objective

Align mysql2pdo with practical patterns from Leonid-SLV/mysql2pdo and MySQL2PDO: qualified `mysql_result`, real PDO release on `mysql_close`, `$new_link` / SSL `$client_flags`, Unix socket via env, migration helpers, and best-effort `mysql_info`.

## Scope / out of scope

**In:** Driver + shims + tests + docs + registry (1.0.x).

**Out:** Non-standard `mysql_connect(..., cert path)` 4th argument; SSL integration test without env certs; changing default `localhost` TCP behavior without `MYSQL2PDO_UNIX_SOCKET`.

## Consumer impact (semver, ShimLink vs legacy handles)

Minor release: `mysql_close` now nulls PDO (apps that ping after close see failure — correct). `$new_link true` opens a second link instead of reusing `$currObj`. `mysql_info` may return non-empty after writes. No signature changes.

## Success criteria

- `composer test` green (extended assertions for connect/close/result/info).
- `composer validate --strict` OK.
- [specs/shim-behavior.md](../../specs/shim-behavior.md) updated.

## Boundaries

- PDO only in `src/` (ADR-0006).
- No git write by agent.

## Open questions

None — socket default remains 127.0.0.1 unless env set.
