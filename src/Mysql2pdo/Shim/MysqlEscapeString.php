<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_escape_string().
 */
final class MysqlEscapeString
{
    public static function invoke($escapestr)
    {
        $link = LinkContext::getCurrObj();

        if (!$link) {
            return addslashes($escapestr);
        }

        if (!$link instanceof \Mysql2pdo\Driver\ShimLink) {
            return addslashes($escapestr);
        }

        return PdoExecutor::realEscapeString($link, $escapestr);
    }
}
