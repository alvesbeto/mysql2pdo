<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_field_seek().
 */
final class MysqlFieldSeek
{
    public static function invoke($result, $fieldnr)
    {
        return PdoExecutor::fieldSeek($result, $fieldnr);
    }
}
