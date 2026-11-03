<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_tablename().
 */
final class MysqlTablename
{
    public static function invoke($result, $row, $field = null)
    {
        if (!$result || !($result instanceof ShimResult)) {
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
