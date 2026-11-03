<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_connect().
 */
final class MysqlConnect
{
    public static function invoke($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        $curr = LinkContext::getCurrObj();
        if ($curr instanceof ShimLink && $curr->isConnected()) {
            return $curr;
        }

        $link = PdoExecutor::connect($host, $username, $passwd);
        LinkContext::setCurrObj($link);
        LinkContext::setClosing(false);

        return $link;
    }
}
