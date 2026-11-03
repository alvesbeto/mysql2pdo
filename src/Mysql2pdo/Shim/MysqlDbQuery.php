<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_db_query().
 */
final class MysqlDbQuery
{
    public static function invoke($database, $query, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        PdoExecutor::selectDb($link, $database);

        return PdoExecutor::query($link, $query);
    }
}
