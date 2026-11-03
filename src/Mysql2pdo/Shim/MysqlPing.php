<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_ping().
 */
final class MysqlPing
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof ShimLink)) {
            return false;
        }

        return PdoExecutor::ping($link);
    }
}
