<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;

/**
 * Shim for mysql_fetch_object().
 */
final class MysqlFetchObject
{
    public static function invoke($result, $class_name = 'stdClass', $params = [])
    {
        $result = ShimGuards::requireResult($result, 'mysql_fetch_object', false);
        if ($result === null) {
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
