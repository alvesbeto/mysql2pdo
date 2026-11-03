<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_tablename().
 */
final class MysqlTablename
{
    public static function invoke($result, $row, $field = null)
    {
        $result = ShimGuards::requireResult($result, 'mysql_tablename', false);
        if ($result === null) {
            return false;
        }

        PdoExecutor::dataSeek($result, $row);

        $f = PdoExecutor::fetchArray($result);
        if (!is_array($f)) {
            return false;
        }

        return $f[0] ?? false;
    }
}
