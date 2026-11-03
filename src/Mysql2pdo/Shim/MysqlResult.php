<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_result().
 */
final class MysqlResult
{
    public static function invoke($result, $row, $field = null)
    {
        $result = ShimGuards::requireResult($result, 'mysql_result', true);
        if ($result === null) {
            return false;
        }

        PdoExecutor::dataSeek($result, $row);

        if ($field !== null && $field !== '') {
            if (is_numeric($field)) {
                PdoExecutor::fieldSeek($result, (int) $field);
                $f = PdoExecutor::fetchArray($result);

                return is_array($f) ? ($f[(int) $field] ?? $f[0] ?? false) : false;
            }

            $count = PdoExecutor::numFields($result);
            for ($i = 0; $i < $count; $i++) {
                $finfo = PdoExecutor::fetchFieldDirect($result, $i);
                if ($finfo === null) {
                    continue;
                }
                if (!self::fieldMatches($field, $finfo->name, $finfo->table)) {
                    continue;
                }
                $f = PdoExecutor::fetchAssoc($result);
                if (!is_array($f)) {
                    return false;
                }

                return self::valueFromRow($f, $field, $finfo->name);
            }

            return false;
        }

        $f = PdoExecutor::fetchArray($result);
        if (!is_array($f)) {
            return false;
        }

        return $f[0] ?? false;
    }

    private static function fieldMatches(string $field, string $name, string $table): bool
    {
        if ($field === $name) {
            return true;
        }
        if ($table !== '' && $field === $table . '.' . $name) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $row
     */
    private static function valueFromRow(array $row, string $field, string $columnName): mixed
    {
        if (array_key_exists($field, $row)) {
            return $row[$field];
        }
        if (array_key_exists($columnName, $row)) {
            return $row[$columnName];
        }

        return false;
    }
}
