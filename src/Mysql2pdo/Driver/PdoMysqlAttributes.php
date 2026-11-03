<?php

namespace Mysql2pdo\Driver;

use PDO;

/**
 * MySQL-specific PDO driver attribute keys (PHP 8.5+ moved constants to Pdo\Mysql).
 */
final class PdoMysqlAttributes
{
    public static function multiStatements(): int
    {
        if (class_exists(\Pdo\Mysql::class)) {
            return \Pdo\Mysql::ATTR_MULTI_STATEMENTS;
        }

        return PDO::MYSQL_ATTR_MULTI_STATEMENTS;
    }

    public static function useBufferedQuery(): int
    {
        if (class_exists(\Pdo\Mysql::class)) {
            return \Pdo\Mysql::ATTR_USE_BUFFERED_QUERY;
        }

        return PDO::MYSQL_ATTR_USE_BUFFERED_QUERY;
    }
}
