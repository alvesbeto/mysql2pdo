<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimResult;

/**
 * Shim for mysql_fetch_object().
 */
final class MysqlFetchObject
{
    public static function invoke($result, $class_name = 'stdClass', $params = [])
    {
        if (!$result || !($result instanceof ShimResult)) {
            return false;
        }

        if (empty($params)) {
            $row = PdoExecutor::fetchObject($result, $class_name);
        } else {
            $row = PdoExecutor::fetchObject($result, $class_name, $params);
        }

        return $row === false ? false : $row;
    }
}
