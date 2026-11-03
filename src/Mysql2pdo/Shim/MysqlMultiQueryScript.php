<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\ResolveLink;

/**
 * Shim for mysql_multi_query_script().
 */
final class MysqlMultiQueryScript
{
    public static function invoke($link, $script)
    {
        $resolved = ResolveLink::invoke($link);
        if (!($resolved instanceof ShimLink)) {
            return false;
        }

        return PdoExecutor::multiQueryScript($resolved, $script);
    }
}
