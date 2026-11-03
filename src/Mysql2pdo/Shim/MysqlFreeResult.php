<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_free_result().
 */
final class MysqlFreeResult
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        return PdoExecutor::freeResult($result);
    }
}
