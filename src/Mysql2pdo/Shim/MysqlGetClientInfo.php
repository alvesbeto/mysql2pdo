<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_get_client_info().
 */
final class MysqlGetClientInfo
{
    public static function invoke($link = null)
    {
        return PdoExecutor::getClientInfo();
    }
}
