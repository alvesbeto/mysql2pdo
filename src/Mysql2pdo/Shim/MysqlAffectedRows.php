<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;
use Mysql2pdo\ResolveLink;

/**
 * Shim for mysql_affected_rows().
 */
final class MysqlAffectedRows
{
    public static function invoke($link = null)
    {
        $link = ResolveLink::operationalLink($link);
        if (!$link instanceof ShimLink) {
            return -1;
        }

        return PdoExecutor::affectedRows($link);
    }
}
