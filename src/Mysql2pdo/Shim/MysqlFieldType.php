<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_field_type().
 */
final class MysqlFieldType
{
    public static function invoke($result, $field_offset)
    {
        $field = PdoExecutor::fetchFieldDirect($result, $field_offset);
        if ($field === null) {
            return null;
        }

        return PdoExecutor::legacyFieldType($field->type);
    }
}
