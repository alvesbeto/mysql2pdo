<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_field().
 */
final class MysqlFetchField
{
    public static function invoke($result, $field_offset = null)
    {
        $offset = $field_offset ?? 0;
        $field = PdoExecutor::fetchFieldDirect($result, $offset);
        if ($field === null) {
            return false;
        }

        return (object) [
            'name' => $field->name,
            'table' => $field->table,
            'max_length' => $field->max_length,
            'type' => $field->type,
            'flags' => $field->flags,
        ];
    }
}
