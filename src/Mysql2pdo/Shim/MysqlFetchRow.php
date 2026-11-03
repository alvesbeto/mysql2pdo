<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_row().
 */
final class MysqlFetchRow
{
    public static function invoke($result)
    {
        $result = ShimGuards::requireResult($result, 'mysql_fetch_row');
        if ($result === null) {
            return false;
        }
        $row = PdoExecutor::fetchRow($result);

        return $row === false ? false : $row;
    }
}
