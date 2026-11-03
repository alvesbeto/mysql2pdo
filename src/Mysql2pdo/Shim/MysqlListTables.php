<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_list_tables().
 */
final class MysqlListTables
{
    public static function invoke($database, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        $query = 'SHOW TABLES FROM `' . str_replace('`', '``', $database) . '`';
        $r = PdoExecutor::query($link, $query);

        if (PdoExecutor::errno($link) === 0) {
            return $r;
        }

        return false;
    }
}
