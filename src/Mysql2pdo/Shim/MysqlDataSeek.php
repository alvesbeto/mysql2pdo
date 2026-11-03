<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_data_seek().
 */
final class MysqlDataSeek
{
    public static function invoke($result, $offset)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        return PdoExecutor::dataSeek($result, $offset);
    }
}
