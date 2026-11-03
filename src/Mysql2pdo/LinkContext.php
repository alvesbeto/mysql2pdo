<?php

namespace Mysql2pdo;

/**
 * Session state for the legacy mysql_* shim (current link and close guard).
 */
final class LinkContext
{
    private static bool $isClosing = false;

    public static function getCurrObj(): mixed
    {
        return \mysql2pdo::$currObj ?? null;
    }

    public static function setCurrObj(mixed $link): void
    {
        \mysql2pdo::$currObj = $link;
    }

    public static function isClosing(): bool
    {
        return self::$isClosing;
    }

    public static function setClosing(bool $value): void
    {
        self::$isClosing = $value;
    }
}
