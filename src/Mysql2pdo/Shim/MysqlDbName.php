<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_db_name().
 */
final class MysqlDbName
{
    public static function invoke($result, $row, $field = null)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        PdoExecutor::dataSeek($result, $row);

        $f = PdoExecutor::fetchRow($result);

        return is_array($f) ? ($f[0] ?? false) : false;
    }
}
