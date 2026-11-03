<?php

namespace Mysql2pdo\Shim;

use Mysql2pdo\Driver\ShimResult;

/**
 * Shared guards for mysql_* shims (single place for func + class callers).
 */
final class ShimGuards
{
    public static function requireResult(mixed $result, string $functionName, bool $triggerWarning = true): ?ShimResult
    {
        if ($result instanceof ShimResult) {
            return $result;
        }

        if ($triggerWarning) {
            trigger_error(
                $functionName . '(): supplied resource is not a valid MySQL result resource',
                E_USER_WARNING
            );
        }

        return null;
    }
}
