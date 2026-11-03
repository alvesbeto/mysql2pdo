# Validate package

Run from repo root:

```bash
composer validate --strict
composer dump-autoload -o
php -l src/mysql2pdo.class.php
php -l src/mysql2pdo.func.php
php -r "require 'vendor/autoload.php'; var_dump(function_exists('mysql_connect'), class_exists('mysql2pdo'));"
```

Expect: syntax OK and `bool(true) bool(true)`.

Integration tests (Docker + ephemeral volume):

```bash
composer test
# testdox; MySQL em 127.0.0.1:3307

composer test:ci
# Resumo compacto (--no-progress), mesmo ciclo Docker

composer test -- --filter test_mysql_connect
# Argumentos extras vão direto ao PHPUnit
```

Requires Docker, `pdo_mysql`, and port **3307** free. The script always runs `docker compose down -v` on exit (success or failure).

Before release (maintainer): tag, Packagist, smoke test in a consuming app if integrated.
