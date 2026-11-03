<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\ResolveLink;

/**
 * Shim for mysql_error().
 */
final class MysqlError
{
    public static function invoke($link = null)
    {
        $resolved = ResolveLink::invoke($link);
        if ($resolved instanceof ShimLink) {
            return PdoExecutor::error($resolved);
        }

        return '';
    }
}
