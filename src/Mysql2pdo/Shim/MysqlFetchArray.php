<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_fetch_array().
 */
final class MysqlFetchArray
{
    public static function invoke($result, $resulttype = 3)
    {
        if (!($result instanceof ShimResult)) {
            trigger_error('mysql_fetch_array(): supplied resource is not a valid MySQL result resource', E_USER_WARNING);

            return false;
        }
        $row = PdoExecutor::fetchArray($result, $resulttype);

        return $row === false ? false : $row;
    }
}
