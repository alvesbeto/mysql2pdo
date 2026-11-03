<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\ResolveLink;

/**
 * Shim for mysql_query().
 */
final class MysqlQuery
{
    public static function invoke($query, $link = null)
    {
        if (empty($query) || !is_string($query)) {
            trigger_error('mysql_query(): Query cannot be empty', E_USER_WARNING);

            return false;
        }

        $link = ResolveLink::ensureConnectedForQuery($link);
        if (!$link instanceof ShimLink) {
            error_log('MySQL Connection Error: No MySQL connection');

            return false;
        }

        return PdoExecutor::query($link, $query);
    }
}
