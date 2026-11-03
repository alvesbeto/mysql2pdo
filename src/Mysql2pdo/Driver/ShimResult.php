<?php

namespace Mysql2pdo\Driver;

use PDOStatement;

/**
 * Legacy mysql_* result handle (PDO-backed).
 */
final class ShimResult
{
    /** @var list<array<int|string, mixed>> */
    public array $rows = [];

    public int $rowOffset = 0;

    public int $fieldOffset = 0;

    /** @var list<FieldMeta> */
    public array $fields = [];

    public bool $freed = false;

    public ?PDOStatement $stmt = null;

    public bool $buffered = true;
}
