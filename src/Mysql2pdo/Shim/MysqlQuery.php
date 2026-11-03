<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_query().
 */
final class MysqlQuery
{
    public static function invoke($query, $link = null)
    {
        if (empty($query) || !is_string($query)) {
            trigger_error('mysql_query(): Query cannot be empty', E_USER_WARNING);

            return false;
        }

        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link || !($link instanceof ShimLink) || $link->connect_errno || !isset($link->host_info)) {
            $errorMsg = $link && ($link instanceof ShimLink) ? $link->connect_error : 'No MySQL connection';
            error_log('MySQL Connection Error: ' . $errorMsg);

            return false;
        }

        return PdoExecutor::query($link, $query);
    }
}
