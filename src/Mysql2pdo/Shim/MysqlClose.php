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
        if ($link === null) {
            $link = LinkContext::getCurrObj();
        }

        if (!($link instanceof ShimLink) || $link->connect_errno !== 0) {
            return true;
        }

        LinkContext::setClosing(true);

        if (!$link->isConnected()) {
            if (LinkContext::getCurrObj() === $link) {
                LinkContext::setCurrObj(null);
            }

            return true;
        }

        if (LinkContext::getCurrObj() === $link) {
            LinkContext::setCurrObj(null);
        }

        PdoExecutor::closeLink($link);

        return true;
    }
}
