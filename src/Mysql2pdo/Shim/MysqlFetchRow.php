<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_fetch_row().
 */
final class MysqlFetchRow
{
    public static function invoke($result)
    {
        $row = PdoExecutor::fetchRow($result);

        return $row === false ? false : $row;
    }
}
