<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_real_escape_string().
 */
final class MysqlRealEscapeString
{
    public static function invoke($escapestr, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof ShimLink) || LinkContext::isClosing()) {
            return addslashes($escapestr);
        }

        return PdoExecutor::realEscapeString($link, $escapestr);
    }
}
