<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_drop_db().
 */
final class MysqlDropDb
{
    public static function invoke($database, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        $query = 'DROP DATABASE `' . str_replace('`', '``', $database) . '`';
        PdoExecutor::query($link, $query);

        if (PdoExecutor::errno($link) === 0) {
            return true;
        }

        return false;
    }
}
