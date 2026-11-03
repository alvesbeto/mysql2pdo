<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_num_rows().
 */
final class MysqlNumRows
{
    public static function invoke($result)
    {
        $result = ShimGuards::requireResult($result, 'mysql_num_rows');
        if ($result === null) {
            return false;
        }

        return PdoExecutor::numRows($result);
    }
}
