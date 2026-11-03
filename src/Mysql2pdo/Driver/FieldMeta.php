<?php

namespace Mysql2pdo\Driver;

/**
 * Column metadata compatible with legacy mysql_fetch_field().
 */
final class FieldMeta
{
    public function __construct(
        public string $name,
        public string $table,
        public int $max_length,
        public int $type,
        public int $flags,
    ) {
    }
}
