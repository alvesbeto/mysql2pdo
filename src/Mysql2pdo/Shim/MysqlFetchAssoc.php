<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_fetch_assoc().
 */
final class MysqlFetchAssoc
{
    public static function invoke($result)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        $row = PdoExecutor::fetchAssoc($result);

        return $row === false ? false : $row;
    }
}
