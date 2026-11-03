<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_list_fields().
 */
final class MysqlListFields
{
    public static function invoke($database_name, $table_name, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        $db = str_replace('`', '``', $database_name);
        $table = str_replace('`', '``', $table_name);
        $query = 'SELECT * FROM `' . $db . '`.`' . $table . '` LIMIT 0';
        $r = PdoExecutor::query($link, $query);

        if (PdoExecutor::errno($link) === 0) {
            return $r;
        }

        return false;
    }
}
