<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_assoc().
 */
final class MysqlFetchAssoc
{
    public static function invoke($result)
    {
        $result = ShimGuards::requireResult($result, 'mysql_fetch_assoc');
        if ($result === null) {
            return false;
        }

        $row = PdoExecutor::fetchAssoc($result);

        return $row === false ? false : $row;
    }
}
