<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\LinkContext;

/**
 * Shim for mysql_set_charset().
 */
final class MysqlSetCharset
{
    public static function invoke($charset, $link = null)
    {
        if (empty($link)) {
            $link = LinkContext::getCurrObj();
        }

        if (!$link instanceof ShimLink) {
            return false;
        }

        return PdoExecutor::setCharset($link, $charset);
    }
}
