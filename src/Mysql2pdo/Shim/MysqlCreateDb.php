<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_create_db().
 */
final class MysqlCreateDb
{
    public static function invoke($database_name, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        $query = 'CREATE DATABASE `' . str_replace('`', '``', $database_name) . '`';
        PdoExecutor::query($link, $query);

        if (PdoExecutor::errno($link) === 0) {
            return true;
        }

        return false;
    }
}
