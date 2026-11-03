<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_select_db().
 */
final class MysqlSelectDb
{
    public static function invoke($dbname, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        } elseif ($link instanceof ShimLink && !$link->connect_errno) {
            LinkContext::setCurrObj($link);
        }

        if (!$link || !($link instanceof ShimLink)) {
            return false;
        }

        if (empty($dbname)) {
            return false;
        }

        try {
            return PdoExecutor::selectDb($link, $dbname);
        } catch (\Exception $e) {
            return false;
        }
    }
}
