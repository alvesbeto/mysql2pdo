<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_close().
 */
final class MysqlClose
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        LinkContext::setClosing(true);

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno) {
            return true;
        }

        LinkContext::setCurrObj(null);

        return true;
    }
}
