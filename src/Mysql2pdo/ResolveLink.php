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

    /**
     * Connected link suitable for data operations (matches legacy mysql_query class checks).
     *
     * @param mixed $link
     */
    public static function operationalLink($link = null): ?ShimLink
    {
        $resolved = self::invoke($link);
        if (!$resolved instanceof ShimLink || $resolved->connect_errno !== 0 || $resolved->host_info === null) {
            return null;
        }

        return $resolved;
    }

    /**
     * Resolve link for mysql_query(): reconnect same handle after mysql_close() when credentials are stored.
     *
     * @param mixed $link
     */
    public static function ensureConnectedForQuery($link = null): ?ShimLink
    {
        if ($link === null) {
            $link = LinkContext::getCurrObj();
        }

        if ($link instanceof ShimLink) {
            if ($link->isConnected()) {
                return $link;
            }

            if (PdoExecutor::reconnect($link)) {
                LinkContext::setCurrObj($link);

                return $link;
            }

            $current = LinkContext::getCurrObj();
            if ($current instanceof ShimLink && $current !== $link && $current->isConnected()) {
                return $current;
            }

            PdoExecutor::markIdleConnectionError($link);

            return null;
        }

        $current = LinkContext::getCurrObj();
        if ($current instanceof ShimLink && $current->isConnected()) {
            return $current;
        }

        if ($current instanceof ShimLink && PdoExecutor::reconnect($current)) {
            LinkContext::setCurrObj($current);

            return $current;
        }

        return null;
    }
}
