<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_lengths().
 */
final class MysqlFetchLengths
{
    public static function invoke($result)
    {
        return PdoExecutor::fetchLengths($result);
    }
}
