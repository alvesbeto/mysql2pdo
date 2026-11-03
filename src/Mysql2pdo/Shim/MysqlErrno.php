<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;
use Mysql2pdo\ResolveLink;

/**
 * Shim for mysql_errno().
 */
final class MysqlErrno
{
    public static function invoke($link = null)
    {
        $resolved = ResolveLink::invoke($link);
        if ($resolved instanceof ShimLink) {
            return PdoExecutor::errno($resolved);
        }

        return 0;
    }
}
