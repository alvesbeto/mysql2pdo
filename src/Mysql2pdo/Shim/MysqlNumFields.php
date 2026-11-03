<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_num_fields().
 */
final class MysqlNumFields
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        return PdoExecutor::numFields($result);
    }
}
