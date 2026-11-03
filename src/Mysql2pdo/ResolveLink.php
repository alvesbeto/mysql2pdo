<?php

namespace Mysql2pdo;

use Mysql2pdo\Driver\PdoExecutor;
use Mysql2pdo\Driver\ShimLink;

/**
 * Normalizes a legacy mysql_* link to the PDO-backed shim handle.
 */
final class ResolveLink
{
    /**
     * @param mixed $link
     */
    public static function invoke($link = null): ?ShimLink
    {
        if ($link instanceof ShimLink) {
            return $link;
        }

        $curr = LinkContext::getCurrObj();
        if ($curr instanceof ShimLink) {
            return $curr;
        }

        return null;
    }

    /**
     * @param mixed $link
     */
    public static function isValidLink($link): bool
    {
        return PdoExecutor::isLink($link);
    }
}
