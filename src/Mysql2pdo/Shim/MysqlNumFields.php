<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_num_fields().
 */
final class MysqlNumFields
{
    public static function invoke($result)
    {
        $result = ShimGuards::requireResult($result, 'mysql_num_fields');
        if ($result === null) {
            return false;
        }

        return PdoExecutor::numFields($result);
    }
}
