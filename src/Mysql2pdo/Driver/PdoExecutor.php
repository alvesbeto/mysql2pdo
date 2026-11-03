<?php

namespace Mysql2pdo\Driver;

use PDO;
use PDOException;
use PDOStatement;

/**
 * PDO implementation backing the legacy mysql_* API.
 */
final class PdoExecutor
{
    private static string $lastConnectError = 'Connection failed';
    /** @var array<string, int> */
    private static array $legacyFieldTypeIds = [
        'int' => 1,
        'real' => 2,
        'string' => 253,
        'blob' => 252,
        'timestamp' => 7,
        'date' => 10,
        'time' => 11,
        'datetime' => 12,
        'year' => 13,
        'unknown' => 255,
    ];

    public static function isLink(mixed $link): bool
    {
        return $link instanceof ShimLink && $link->isConnected();
    }

    public static function isLinkInstance(mixed $link): bool
    {
        return $link instanceof ShimLink;
    }

    public static function isResult(mixed $result): bool
    {
        return $result instanceof ShimResult && !$result->freed;
    }

    /**
     * @return ShimLink|false
     */
    public static function connect(
        string $host,
        string $username,
        string $passwd,
        bool $persistent = false,
        int $client_flags = 0,
    ): ShimLink|false {
        $hostForDsn = $host;
        if (str_starts_with($hostForDsn, 'p:')) {
            $hostForDsn = substr($hostForDsn, 2);
            $persistent = true;
        }

        $link = new ShimLink($host, $username);
        $link->connectionPassword = $passwd;
        $link->connectionPersistent = $persistent;
        $link->connectionClientFlags = $client_flags;

        if (!self::attachPdo($link, $hostForDsn, $username, $passwd, $persistent, $client_flags)) {
            return false;
        }

        return $link;
    }

    /**
     * Re-open PDO on an existing handle after mysql_close() (legacy apps often close mid-request).
     */
    public static function reconnect(ShimLink $link): bool
    {
        if ($link->isConnected()) {
            return true;
        }

        if ($link->connect_errno !== 0 || $link->connectionPassword === null) {
            return false;
        }

        $hostForDsn = $link->host;
        $persistent = $link->connectionPersistent;
        if (str_starts_with($hostForDsn, 'p:')) {
            $hostForDsn = substr($hostForDsn, 2);
            $persistent = true;
        }

        if (!self::attachPdo(
            $link,
            $hostForDsn,
            $link->username,
            $link->connectionPassword,
            $persistent,
            $link->connectionClientFlags
        )) {
            return false;
        }

        if ($link->selectedDatabase !== null && $link->selectedDatabase !== '') {
            self::selectDb($link, $link->selectedDatabase);
        }

        return $link->isConnected();
    }

    public static function markIdleConnectionError(ShimLink $link): void
    {
        if ($link->lastError !== '') {
            return;
        }

        $link->lastErrno = 2006;
        $link->lastError = 'MySQL server has gone away';
    }

    private static function attachPdo(
        ShimLink $link,
        string $hostForDsn,
        string $username,
        string $passwd,
        bool $persistent,
        int $client_flags,
    ): bool {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PdoMysqlAttributes::multiStatements() => true,
        ];
        if ($persistent) {
            $options[PDO::ATTR_PERSISTENT] = true;
        }
        self::applyClientFlags($options, $client_flags);

        try {
            $dsn = self::buildDsn($hostForDsn);
            $pdo = new PDO($dsn, $username, $passwd, $options);
        } catch (PDOException $e) {
            self::setConnectFailure($link, (int) $e->getCode(), $e->getMessage());

            return false;
        }

        $info = $pdo->errorInfo();
        if ($info[0] !== '00000' && $info[0] !== null && $info[0] !== '') {
            self::setConnectFailure($link, (int) ($info[1] ?? 2002), (string) ($info[2] ?? 'Connection failed'));

            return false;
        }

        $link->pdo = $pdo;
        $link->connect_errno = 0;
        $link->connect_error = '';
        $link->host_info = self::formatHostInfo($hostForDsn);
        $link->lastErrno = 0;
        $link->lastError = '';
        self::syncError($link);

        return true;
    }

    public static function closeLink(ShimLink $link): void
    {
        if ($link->pdo !== null) {
            $link->pdo = null;
        }
        $link->host_info = null;
    }

    public static function connectError(): string
    {
        return self::$lastConnectError;
    }

    public static function selectDb(ShimLink $link, string $dbname): bool
    {
        if (!$link->pdo || $link->connect_errno) {
            return false;
        }
        $dbname = str_replace('`', '``', $dbname);
        $ok = $link->pdo->exec('USE `' . $dbname . '`');
        self::syncError($link);

        if ($ok !== false) {
            $link->selectedDatabase = $dbname;
        }

        return $ok !== false;
    }

    /**
     * @return ShimResult|true|false
     */
    public static function query(ShimLink $link, string $query, bool $buffered = true): ShimResult|bool
    {
        if (!$link->pdo && !self::reconnect($link)) {
            self::markIdleConnectionError($link);

            return false;
        }

        if (!$buffered) {
            $link->pdo->setAttribute(PdoMysqlAttributes::useBufferedQuery(), false);
        }

        $stmt = $link->pdo->query($query);

        if (!$buffered) {
            $link->pdo->setAttribute(PdoMysqlAttributes::useBufferedQuery(), true);
        }

        if ($stmt === false) {
            self::syncError($link);

            return false;
        }

        self::syncError($link);
        $link->lastAffectedRows = $stmt->rowCount();

        if ($stmt->columnCount() === 0) {
            $link->lastInfoString = self::buildInfoString($query, $link->lastAffectedRows);

            return true;
        }

        $link->lastInfoString = '';

        return self::resultFromStatement($stmt, $buffered);
    }

    public static function errno(ShimLink $link): int
    {
        if (!$link->pdo) {
            return $link->connect_errno ?: 0;
        }
        self::syncError($link);

        return $link->lastErrno;
    }

    public static function error(ShimLink $link): string
    {
        if (!$link->pdo) {
            if ($link->lastError !== '') {
                return $link->lastError;
            }
            if ($link->connect_error !== '') {
                return $link->connect_error;
            }
            if ($link->connect_errno === 0 && $link->host_info === null) {
                return 'MySQL server has gone away';
            }

            return 'No MySQL connection';
        }
        self::syncError($link);

        return $link->lastError;
    }

    public static function affectedRows(ShimLink $link): int
    {
        if (!$link->pdo) {
            return -1;
        }

        return $link->lastAffectedRows;
    }

    public static function insertId(ShimLink $link): int
    {
        if (!$link->pdo) {
            return 0;
        }

        return (int) $link->pdo->lastInsertId();
    }

    public static function realEscapeString(ShimLink $link, string $escapestr): string
    {
        if (!$link->pdo) {
            return addslashes($escapestr);
        }
        $quoted = $link->pdo->quote($escapestr);
        if ($quoted === false) {
            return addslashes($escapestr);
        }

        return substr($quoted, 1, -1);
    }

    public static function setCharset(ShimLink $link, string $charset): bool
    {
        if (!$link->pdo) {
            return false;
        }
        $ok = $link->pdo->exec('SET NAMES ' . $link->pdo->quote($charset));

        return $ok !== false;
    }

    public static function characterSetName(ShimLink $link): string
    {
        if (!$link->pdo) {
            return '';
        }
        $stmt = $link->pdo->query('SELECT @@character_set_connection');
        if ($stmt === false) {
            return '';
        }
        $row = $stmt->fetch(PDO::FETCH_NUM);

        return is_array($row) ? (string) ($row[0] ?? '') : '';
    }

    public static function getClientInfo(): string
    {
        return 'mysqlnd PDO';
    }

    public static function getHostInfo(ShimLink $link): string
    {
        return $link->host_info ?? '';
    }

    public static function getServerInfo(ShimLink $link): string
    {
        if (!$link->pdo) {
            return '';
        }
        $version = $link->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);

        return is_string($version) ? $version : '';
    }

    public static function getProtoInfo(ShimLink $link): int
    {
        if (!$link->pdo) {
            return 0;
        }
        $stmt = $link->pdo->query("SHOW VARIABLES LIKE 'protocol_version'");
        if ($stmt === false) {
            return 10;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($row['Value']) ? (int) $row['Value'] : 10;
    }

    public static function threadId(ShimLink $link): int
    {
        if (!$link->pdo) {
            return 0;
        }
        $stmt = $link->pdo->query('SELECT CONNECTION_ID()');
        if ($stmt === false) {
            return 0;
        }
        $row = $stmt->fetch(PDO::FETCH_NUM);

        return is_array($row) ? (int) ($row[0] ?? 0) : 0;
    }

    public static function info(ShimLink $link): string
    {
        if (!$link->pdo) {
            return '';
        }

        return $link->lastInfoString;
    }

    public static function stat(ShimLink $link): string|false
    {
        if (!$link->pdo) {
            return false;
        }
        $stmt = $link->pdo->query('SHOW STATUS');
        if ($stmt === false) {
            return false;
        }
        $parts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (!is_array($row)) {
                continue;
            }
            $name = $row['Variable_name'] ?? null;
            $value = $row['Value'] ?? null;
            if ($name !== null && $value !== null) {
                $parts[] = $name . ': ' . $value;
            }
        }

        return $parts !== [] ? implode('  ', $parts) : false;
    }

    public static function ping(ShimLink $link): bool
    {
        if (!$link->pdo) {
            return false;
        }
        $result = self::query($link, 'SELECT 1');
        if ($result instanceof ShimResult) {
            self::freeResult($result);

            return true;
        }

        return $result === true;
    }

    public static function multiQueryScript(ShimLink $link, string $script): bool
    {
        if (!$link->pdo) {
            return false;
        }

        $stmt = $link->pdo->query($script);
        if ($stmt === false) {
            self::syncError($link);

            return false;
        }

        do {
            if ($stmt instanceof PDOStatement && $stmt->columnCount() > 0) {
                while ($stmt->fetch()) {
                }
            }
            if (self::errno($link) !== 0) {
                return false;
            }
        } while ($stmt instanceof PDOStatement && $stmt->nextRowset());

        return self::errno($link) === 0;
    }

    public static function numRows(ShimResult $result): int
    {
        if (!$result->buffered && $result->stmt !== null) {
            return 0;
        }

        return count($result->rows);
    }

    public static function numFields(ShimResult $result): int
    {
        return count($result->fields);
    }

    public static function dataSeek(ShimResult $result, int $offset): bool
    {
        if ($result->freed || !$result->buffered) {
            return false;
        }
        if ($offset < 0 || $offset > count($result->rows)) {
            return false;
        }
        $result->rowOffset = $offset;

        return true;
    }

    public static function fieldSeek(ShimResult $result, int $fieldnr): bool
    {
        if ($result->freed || $fieldnr < 0 || $fieldnr >= count($result->fields)) {
            return false;
        }
        $result->fieldOffset = $fieldnr;

        return true;
    }

    public static function fetchFieldDirect(ShimResult $result, int $fieldOffset): ?FieldMeta
    {
        return $result->fields[$fieldOffset] ?? null;
    }

    /**
     * @return array<int|string, mixed>|false
     */
    public static function fetchArray(ShimResult $result, int $resulttype = 3): array|false
    {
        $row = self::fetchRowInternal($result);
        if ($row === false) {
            return false;
        }

        return self::shapeRow($row, $resulttype);
    }

    /**
     * @return array<string, mixed>|false
     */
    public static function fetchAssoc(ShimResult $result): array|false
    {
        $row = self::fetchRowInternal($result);
        if ($row === false) {
            return false;
        }

        return self::shapeRow($row, 1);
    }

    /**
     * @return list<mixed>|false
     */
    public static function fetchRow(ShimResult $result): array|false
    {
        $row = self::fetchRowInternal($result);
        if ($row === false) {
            return false;
        }

        return self::shapeRow($row, 2);
    }

    public static function fetchObject(ShimResult $result, string $class_name = 'stdClass', array $params = []): object|false
    {
        $row = self::fetchRowInternal($result);
        if ($row === false) {
            return false;
        }
        $assoc = self::shapeRow($row, 1);
        if ($params === []) {
            return (object) $assoc;
        }

        $ref = new \ReflectionClass($class_name);
        $obj = $ref->newInstanceWithoutConstructor();
        foreach ($assoc as $key => $value) {
            $obj->$key = $value;
        }

        return $obj;
    }

    /**
     * @return list<int>|false
     */
    public static function fetchLengths(ShimResult $result): array|false
    {
        $row = self::currentRow($result);
        if ($row === false) {
            return false;
        }
        $lengths = [];
        foreach (array_values($row) as $value) {
            $lengths[] = is_string($value) ? strlen($value) : 0;
        }

        return $lengths;
    }

    public static function freeResult(ShimResult $result): bool
    {
        if ($result->freed) {
            return false;
        }
        $result->freed = true;
        $result->rows = [];
        if ($result->stmt !== null) {
            $result->stmt->closeCursor();
            $result->stmt = null;
        }

        return true;
    }

    public static function legacyFieldType(int $typeId): ?string
    {
        $map = [
            1 => 'int',
            2 => 'int',
            3 => 'int',
            4 => 'real',
            5 => 'real',
            7 => 'timestamp',
            8 => 'int',
            9 => 'int',
            10 => 'date',
            11 => 'time',
            12 => 'datetime',
            13 => 'year',
            16 => 'int',
            252 => 'blob',
            253 => 'string',
            254 => 'string',
            246 => 'real',
            0 => 'real',
        ];

        return $map[$typeId] ?? 'unknown';
    }

    public static function fieldFlagsString(int $flagsNum): string
    {
        $flags = [
            1 => 'NOT_NULL',
            2 => 'PRI_KEY',
            4 => 'UNIQUE_KEY',
            8 => 'MULTIPLE_KEY',
            16 => 'BLOB',
            32 => 'UNSIGNED',
            64 => 'ZEROFILL',
            128 => 'BINARY',
            256 => 'ENUM',
            512 => 'AUTO_INCREMENT',
            1024 => 'TIMESTAMP',
            2048 => 'SET',
        ];
        $result = [];
        foreach ($flags as $n => $t) {
            if ($flagsNum & $n) {
                $result[] = $t;
            }
        }
        $return = implode(' ', $result);
        $return = str_replace('PRI_KEY', 'PRIMARY_KEY', $return);

        return strtolower($return);
    }

    private static function setConnectFailure(ShimLink $link, int $errno, string $error): void
    {
        self::$lastConnectError = $error;
        $link->connect_errno = $errno > 0 ? $errno : 2002;
        $link->connect_error = $error;
        $link->host_info = null;
        $link->pdo = null;
    }

    private static function buildDsn(string $host): string
    {
        $port = null;
        $socket = null;
        $hostHadColonExtra = false;
        if (str_contains($host, ':')) {
            [$hostPart, $extra] = explode(':', $host, 2);
            if ($extra !== '' && ctype_digit($extra)) {
                $host = $hostPart;
                $port = (int) $extra;
            } elseif ($extra !== '') {
                $host = $hostPart;
                $socket = $extra;
                $hostHadColonExtra = true;
            }
        }

        if ($host === 'localhost' && $socket === null && !$hostHadColonExtra && $port === null) {
            $envSocket = getenv('MYSQL2PDO_UNIX_SOCKET');
            if ($envSocket !== false && $envSocket !== '') {
                $socket = $envSocket;
            } else {
                $host = '127.0.0.1';
            }
        } elseif ($host === 'localhost' && $socket === null) {
            $host = '127.0.0.1';
        }

        $dsn = 'mysql:host=' . $host;
        if ($port !== null) {
            $dsn .= ';port=' . $port;
        }
        if ($socket !== null) {
            $dsn .= ';unix_socket=' . $socket;
        }

        return $dsn;
    }

    /**
     * @param array<int, mixed> $options
     */
    private static function applyClientFlags(array &$options, int $client_flags): void
    {
        $sslFlag = defined('MYSQL_CLIENT_SSL') ? MYSQL_CLIENT_SSL : 2048;
        if (($client_flags & $sslFlag) === 0) {
            return;
        }

        $ca = getenv('MYSQL2PDO_SSL_CA');
        if ($ca !== false && $ca !== '') {
            $options[PdoMysqlAttributes::sslCa()] = $ca;
        }
        $cert = getenv('MYSQL2PDO_SSL_CERT');
        if ($cert !== false && $cert !== '') {
            $options[PdoMysqlAttributes::sslCert()] = $cert;
        }
        $key = getenv('MYSQL2PDO_SSL_KEY');
        if ($key !== false && $key !== '') {
            $options[PdoMysqlAttributes::sslKey()] = $key;
        }
        $verify = getenv('MYSQL2PDO_SSL_VERIFY');
        if ($verify !== false && $verify !== '') {
            $options[PdoMysqlAttributes::sslVerifyServerCert()] = (int) $verify;
        }
    }

    private static function buildInfoString(string $query, int $rowCount): string
    {
        $normalized = ltrim($query);
        if ($normalized === '') {
            return '';
        }
        $upper = strtoupper($normalized);
        if (str_starts_with($upper, 'INSERT') || str_starts_with($upper, 'REPLACE')) {
            return sprintf('Records: %d Duplicates: 0 Warnings: 0', max(0, $rowCount));
        }
        if (str_starts_with($upper, 'UPDATE')) {
            $n = max(0, $rowCount);

            return sprintf('Rows matched: %d Changed: %d Warnings: 0', $n, $n);
        }
        if (str_starts_with($upper, 'DELETE')) {
            return sprintf('Records: %d', max(0, $rowCount));
        }

        return '';
    }

    private static function formatHostInfo(string $host): string
    {
        if ($host === '127.0.0.1' || $host === 'localhost') {
            return '127.0.0.1 via TCP/IP';
        }

        return $host . ' via TCP/IP';
    }

    private static function syncError(ShimLink $link): void
    {
        if (!$link->pdo) {
            return;
        }
        $info = $link->pdo->errorInfo();
        $link->lastErrno = (int) ($info[1] ?? 0);
        $link->lastError = (string) ($info[2] ?? '');
    }

    private static function resultFromStatement(PDOStatement $stmt, bool $buffered): ShimResult
    {
        $result = new ShimResult();
        $result->buffered = $buffered;
        $result->stmt = $stmt;
        $result->fields = self::readFieldMeta($stmt);

        if ($buffered) {
            $result->rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result->stmt = null;
            $stmt->closeCursor();
        }

        return $result;
    }

    /**
     * @return list<FieldMeta>
     */
    private static function readFieldMeta(PDOStatement $stmt): array
    {
        $fields = [];
        $count = $stmt->columnCount();
        for ($i = 0; $i < $count; $i++) {
            $meta = @$stmt->getColumnMeta($i);
            if (!is_array($meta)) {
                $fields[] = new FieldMeta('column' . $i, '', 0, self::$legacyFieldTypeIds['unknown'], 0);
                continue;
            }
            $name = (string) ($meta['name'] ?? 'column' . $i);
            $table = (string) ($meta['table'] ?? '');
            $maxLen = (int) ($meta['len'] ?? 0);
            $typeId = self::resolveTypeId($meta);
            $flagsNum = self::resolveFlagsNum($meta);
            $fields[] = new FieldMeta($name, $table, $maxLen, $typeId, $flagsNum);
        }

        return $fields;
    }

    /**
     * @param array<string, mixed> $meta
     */
    private static function resolveTypeId(array $meta): int
    {
        if (isset($meta['native_type']) && is_int($meta['native_type'])) {
            return $meta['native_type'];
        }
        $native = strtoupper((string) ($meta['native_type'] ?? ''));
        $byName = [
            'LONG' => 1,
            'VAR_STRING' => 253,
            'STRING' => 254,
            'BLOB' => 252,
            'DOUBLE' => 5,
            'FLOAT' => 4,
            'DATETIME' => 12,
            'DATE' => 10,
            'TIME' => 11,
            'TIMESTAMP' => 7,
            'YEAR' => 13,
        ];

        return $byName[$native] ?? self::$legacyFieldTypeIds['unknown'];
    }

    /**
     * @param array<string, mixed> $meta
     */
    private static function resolveFlagsNum(array $meta): int
    {
        $flagsNum = 0;
        $flags = $meta['flags'] ?? [];
        if (!is_array($flags)) {
            return 0;
        }
        $map = [
            'not_null' => 1,
            'primary_key' => 2,
            'unique_key' => 4,
            'multiple_key' => 8,
            'blob' => 16,
            'unsigned' => 32,
            'zerofill' => 64,
            'binary' => 128,
            'enum' => 256,
            'auto_increment' => 512,
            'timestamp' => 1024,
            'set' => 2048,
        ];
        foreach ($flags as $flag) {
            if (is_string($flag) && isset($map[$flag])) {
                $flagsNum |= $map[$flag];
            }
        }

        return $flagsNum;
    }

    /**
     * @return array<string, mixed>|false
     */
    private static function fetchRowInternal(ShimResult $result): array|false
    {
        if ($result->freed) {
            return false;
        }

        if ($result->buffered) {
            if (!isset($result->rows[$result->rowOffset])) {
                return false;
            }
            $row = $result->rows[$result->rowOffset];
            $result->rowOffset++;

            return $row;
        }

        if ($result->stmt === null) {
            return false;
        }
        $row = $result->stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $row : false;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<int|string, mixed>
     */
    private static function shapeRow(array $row, int $resulttype): array
    {
        if ($resulttype === 1) {
            return $row;
        }
        $values = array_values($row);
        if ($resulttype === 2) {
            return $values;
        }

        $both = [];
        $i = 0;
        foreach ($row as $key => $value) {
            $both[$i] = $value;
            $both[$key] = $value;
            $i++;
        }

        return $both;
    }

    /**
     * @return array<string, mixed>|false
     */
    private static function currentRow(ShimResult $result): array|false
    {
        if ($result->freed) {
            return false;
        }
        if ($result->buffered) {
            $index = max(0, $result->rowOffset - 1);
            if (!isset($result->rows[$index])) {
                return false;
            }

            return $result->rows[$index];
        }
        if ($result->stmt === null) {
            return false;
        }
        $row = $result->stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $row : false;
    }
}
