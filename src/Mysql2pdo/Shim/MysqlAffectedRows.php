<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_affected_rows().
 */
final class MysqlAffectedRows
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno || !isset($link->host_info)) {
            return -1;
        }

        return PdoExecutor::affectedRows($link);
    }
}
