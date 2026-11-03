<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_db_name().
 */
final class MysqlDbName
{
    public static function invoke($result, $row, $field = null)
    {
        $result = ShimGuards::requireResult($result, 'mysql_db_name', false);
        if ($result === null) {
            return false;
        }

        PdoExecutor::dataSeek($result, $row);

        $f = PdoExecutor::fetchRow($result);

        return is_array($f) ? ($f[0] ?? false) : false;
    }
}
