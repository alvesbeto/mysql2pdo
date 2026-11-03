<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_pconnect().
 */
final class MysqlPconnect
{
    public static function invoke($host = '', $username = '', $passwd = '', $new_link = false, $client_flags = 0)
    {
        $link = PdoExecutor::connect('p:' . $host, $username, $passwd, true, (int) $client_flags);
        if (!$link) {
            $errorMsg = PdoExecutor::connectError();
            error_log('MySQL Persistent Connection Error: ' . $errorMsg);
            echo $errorMsg . '<br>';
        }
        LinkContext::setCurrObj($link);

        return $link;
    }
}
