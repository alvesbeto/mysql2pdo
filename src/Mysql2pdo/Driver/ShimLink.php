<?php

namespace Mysql2pdo\Driver;

use PDO;

/**
 * Legacy mysql_* connection handle (PDO-backed).
 */
final class ShimLink
{
    public int $connect_errno = 0;

    public string $connect_error = '';

    public ?string $host_info = null;

    public int $lastErrno = 0;

    public string $lastError = '';

    public int $lastAffectedRows = 0;

    public string $lastInfoString = '';

    public ?PDO $pdo = null;

    public function __construct(
        public readonly string $host,
        public readonly string $username,
    ) {
    }

    public function isConnected(): bool
    {
        return $this->pdo !== null && $this->connect_errno === 0 && $this->host_info !== null;
    }
}
