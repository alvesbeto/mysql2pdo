#!/usr/bin/env bash
# Starts test MySQL (named volume), runs PHPUnit, always removes container + volume on exit.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

COMPOSE=(docker compose -f docker-compose.test.yml)

cleanup() {
  "${COMPOSE[@]}" down -v --remove-orphans 2>/dev/null || true
}

trap cleanup EXIT INT TERM HUP

"${COMPOSE[@]}" up -d --wait

export MYSQL_TEST_HOST="${MYSQL_TEST_HOST:-127.0.0.1}"
export MYSQL_TEST_PORT="${MYSQL_TEST_PORT:-3307}"
export MYSQL_TEST_USER="${MYSQL_TEST_USER:-root}"
export MYSQL_TEST_PASSWORD="${MYSQL_TEST_PASSWORD:-test}"
export MYSQL_TEST_DATABASE="${MYSQL_TEST_DATABASE:-mysql2pdo_test}"

if [[ ! -x vendor/bin/phpunit ]]; then
  composer install --no-interaction
fi

DEFAULT_PHPUNIT_ARGS=(--testdox)

echo "MySQL: ${MYSQL_TEST_HOST}:${MYSQL_TEST_PORT} / ${MYSQL_TEST_DATABASE}"
vendor/bin/phpunit "${DEFAULT_PHPUNIT_ARGS[@]}" "$@"
