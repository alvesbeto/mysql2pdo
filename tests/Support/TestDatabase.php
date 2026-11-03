<?php

declare(strict_types=1);

namespace Mysql2pdo\Tests\Support;

use Mysql2pdo\Driver\ShimLink;

/**
 * Reads MYSQL_TEST_* env vars set by scripts/run-tests.sh.
 */
final class TestDatabase
{
    public static function hostWithPort(): string
    {
        $host = getenv('MYSQL_TEST_HOST') ?: '127.0.0.1';
        $port = getenv('MYSQL_TEST_PORT') ?: '3307';

        return $host . ':' . $port;
    }

    public static function user(): string
    {
        return getenv('MYSQL_TEST_USER') ?: 'root';
    }

    public static function password(): string
    {
        return getenv('MYSQL_TEST_PASSWORD') ?: 'test';
    }

    public static function database(): string
    {
        return getenv('MYSQL_TEST_DATABASE') ?: 'mysql2pdo_test';
    }

    /**
     * @return ShimLink|false
     */
    public static function connect(): ShimLink|false
    {
        $link = mysql_connect(self::hostWithPort(), self::user(), self::password());
        if ($link === false) {
            return false;
        }

        if (!mysql_select_db(self::database(), $link)) {
            return false;
        }

        return $link;
    }
}
