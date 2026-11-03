<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_array().
 */
final class MysqlFetchArray
{
    public static function invoke($result, $resulttype = 3)
    {
        $result = ShimGuards::requireResult($result, 'mysql_fetch_array');
        if ($result === null) {
            return false;
        }
        $row = PdoExecutor::fetchArray($result, $resulttype);

        return $row === false ? false : $row;
    }
}
