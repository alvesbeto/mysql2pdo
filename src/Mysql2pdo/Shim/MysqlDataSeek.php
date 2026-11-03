<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_data_seek().
 */
final class MysqlDataSeek
{
    public static function invoke($result, $offset)
    {
        $result = ShimGuards::requireResult($result, 'mysql_data_seek');
        if ($result === null) {
            return false;
        }

        return PdoExecutor::dataSeek($result, $offset);
    }
}
