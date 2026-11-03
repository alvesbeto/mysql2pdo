<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_field_flags().
 */
final class MysqlFieldFlags
{
    public static function invoke($result, $field_offset)
    {
        $field = PdoExecutor::fetchFieldDirect($result, $field_offset);
        if ($field === null) {
            return '';
        }

        return PdoExecutor::fieldFlagsString($field->flags);
    }
}
