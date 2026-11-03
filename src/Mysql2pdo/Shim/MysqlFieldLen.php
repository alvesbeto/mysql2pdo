<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_field_len().
 */
final class MysqlFieldLen
{
    public static function invoke($result, $field_offset)
    {
        $fieldInfo = PdoExecutor::fetchFieldDirect($result, $field_offset);
        if ($fieldInfo === null) {
            return false;
        }

        return $fieldInfo->max_length;
    }
}
