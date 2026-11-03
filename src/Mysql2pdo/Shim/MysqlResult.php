<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_result().
 */
final class MysqlResult
{
    public static function invoke($result, $row, $field = null)
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        PdoExecutor::dataSeek($result, $row);
        if (!empty($field)) {
            PdoExecutor::fieldSeek($result, 0);
            $count = PdoExecutor::numFields($result);
            for ($i = 0; $i < $count; $i++) {
                $finfo = PdoExecutor::fetchFieldDirect($result, $i);
                if ($finfo !== null && $field == $finfo->name) {
                    $f = PdoExecutor::fetchAssoc($result);

                    return is_array($f) ? ($f[$field] ?? false) : false;
                }
            }
        }

        $f = PdoExecutor::fetchArray($result);
        if (!is_array($f)) {
            return false;
        }

        return $f[0] ?? false;
    }
}
