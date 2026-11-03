<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_list_dbs().
 */
final class MysqlListDbs
{
    public static function invoke($link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno) {
            return false;
        }

        $query = 'SHOW DATABASES';
        $r = PdoExecutor::query($link, $query);

        if (PdoExecutor::errno($link) === 0) {
            return $r;
        }

        return false;
    }
}
