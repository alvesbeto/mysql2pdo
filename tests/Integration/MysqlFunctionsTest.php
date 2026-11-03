<?php

declare(strict_types=1);

namespace Mysql2pdo\Tests\Integration;

use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\Driver\ShimResult;
use Mysql2pdo\Tests\Support\IntegrationTestCase;
use Mysql2pdo\Tests\Support\TestDatabase;
use stdClass;

/**
 * One integration test per global mysql_* function (49) plus resolveLink helper.
 */
final class MysqlFunctionsTest extends IntegrationTestCase
{
    public function test_mysql_connect(): void
    {
        $this->assertInstanceOf(ShimLink::class, $this->link);
        $again = mysql_connect(
            TestDatabase::hostWithPort(),
            TestDatabase::user(),
            TestDatabase::password()
        );
        $this->assertSame($this->link, $again);
    }

    public function test_mysql_pconnect(): void
    {
        $this->expectUserDeprecationMessage('mysql_pconnect() is deprecated. Use PDO with persistent connections instead.');
        $p = mysql_pconnect(
            TestDatabase::hostWithPort(),
            TestDatabase::user(),
            TestDatabase::password()
        );
        $this->assertInstanceOf(ShimLink::class, $p);
        $this->assertTrue($p->isConnected());
    }

    public function test_mysql_select_db(): void
    {
        $this->assertFalse(mysql_select_db('', $this->link));
        $this->assertTrue(mysql_select_db(TestDatabase::database(), $this->link));
    }

    public function test_mysql_close(): void
    {
        $this->assertTrue(mysql_close($this->link));
    }

    public function test_mysql_ping(): void
    {
        $this->assertTrue(mysql_ping($this->link));
    }

    public function test_mysql_query(): void
    {
        $result = mysql_query('SELECT COUNT(*) AS c FROM mysql2pdo_fn', $this->link);
        $this->assertInstanceOf(ShimResult::class, $result);
        $row = mysql_fetch_assoc($result);
        $this->assertSame(2, (int) $row['c']);
        mysql_free_result($result);
    }

    public function test_mysql_unbuffered_query(): void
    {
        $result = mysql_unbuffered_query(
            'SELECT id, label FROM mysql2pdo_fn ORDER BY id',
            $this->link
        );
        $this->assertInstanceOf(ShimResult::class, $result);
        $row = mysql_fetch_assoc($result);
        $this->assertSame('alpha', $row['label']);
        mysql_free_result($result);
    }

    public function test_mysql_db_query(): void
    {
        $result = mysql_db_query(
            TestDatabase::database(),
            'SELECT 42 AS answer',
            $this->link
        );
        $this->assertInstanceOf(ShimResult::class, $result);
        $row = mysql_fetch_row($result);
        $this->assertSame([42], $row);
        mysql_free_result($result);
    }

    public function test_mysql_multi_query_script(): void
    {
        $ok = mysql_multi_query_script(
            $this->link,
            'SELECT 1 AS a; SELECT 2 AS b;'
        );
        $this->assertTrue($ok);
    }

    public function test_mysql_fetch_array(): void
    {
        $result = $this->fixtureSelectResult();
        $row = mysql_fetch_array($result, MYSQL_BOTH);
        $this->assertSame('alpha', $row['label']);
        $this->assertSame('alpha', $row[1]);
        mysql_free_result($result);
    }

    public function test_mysql_fetch_assoc(): void
    {
        $result = $this->fixtureSelectResult();
        $row = mysql_fetch_assoc($result);
        $this->assertArrayHasKey('id', $row);
        $this->assertSame('alpha', $row['label']);
        mysql_free_result($result);
    }

    public function test_mysql_fetch_row(): void
    {
        $result = $this->fixtureSelectResult();
        $row = mysql_fetch_row($result);
        $this->assertSame('alpha', $row[1]);
        mysql_free_result($result);
    }

    public function test_mysql_fetch_object(): void
    {
        $result = $this->fixtureSelectResult();
        $obj = mysql_fetch_object($result, stdClass::class);
        $this->assertInstanceOf(stdClass::class, $obj);
        $this->assertSame('alpha', $obj->label);
        mysql_free_result($result);
    }

    public function test_mysql_fetch_field(): void
    {
        $result = $this->fixtureSelectResult();
        $field = mysql_fetch_field($result, 1);
        $this->assertIsObject($field);
        $this->assertSame('label', $field->name);
        mysql_free_result($result);
    }

    public function test_mysql_fetch_lengths(): void
    {
        $result = $this->fixtureSelectResult();
        mysql_fetch_assoc($result);
        $lengths = mysql_fetch_lengths($result);
        $this->assertIsArray($lengths);
        $this->assertNotEmpty($lengths);
        mysql_free_result($result);
    }

    public function test_mysql_num_rows(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertSame(2, mysql_num_rows($result));
        mysql_free_result($result);
    }

    public function test_mysql_num_fields(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertSame(3, mysql_num_fields($result));
        mysql_free_result($result);
    }

    public function test_mysql_data_seek(): void
    {
        $result = $this->fixtureSelectResult();
        mysql_fetch_assoc($result);
        $this->assertTrue(mysql_data_seek($result, 0));
        $row = mysql_fetch_assoc($result);
        $this->assertSame('alpha', $row['label']);
        mysql_free_result($result);
    }

    public function test_mysql_free_result(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertTrue(mysql_free_result($result));
        $this->assertFalse(mysql_free_result($result));
    }

    public function test_mysql_result(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertSame('alpha', mysql_result($result, 0, 'label'));
        $this->assertSame('beta', mysql_result($result, 1, 'label'));
        mysql_free_result($result);
    }

    public function test_mysql_field_name(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertSame('label', mysql_field_name($result, 1));
        mysql_free_result($result);
    }

    public function test_mysql_field_type(): void
    {
        $result = $this->fixtureSelectResult();
        $type = mysql_field_type($result, 0);
        $this->assertContains($type, ['int', 'string', 'real', 'unknown']);
        mysql_free_result($result);
    }

    public function test_mysql_field_len(): void
    {
        $result = $this->fixtureSelectResult();
        $len = mysql_field_len($result, 1);
        $this->assertIsInt($len);
        mysql_free_result($result);
    }

    public function test_mysql_field_flags(): void
    {
        $result = $this->fixtureSelectResult();
        $flags = mysql_field_flags($result, 0);
        $this->assertIsString($flags);
        $this->assertStringContainsString('primary_key', $flags);
        mysql_free_result($result);
    }

    public function test_mysql_field_table(): void
    {
        $result = $this->fixtureSelectResult();
        $table = mysql_field_table($result, 1);
        $this->assertSame('mysql2pdo_fn', $table);
        mysql_free_result($result);
    }

    public function test_mysql_field_seek(): void
    {
        $result = $this->fixtureSelectResult();
        $this->assertTrue(mysql_field_seek($result, 2));
        mysql_free_result($result);
    }

    public function test_mysql_errno(): void
    {
        $this->assertSame(0, mysql_errno($this->link));
        @mysql_query('SELEC 1', $this->link);
        $this->assertNotSame(0, mysql_errno($this->link));
    }

    public function test_mysql_error(): void
    {
        $this->assertSame('', mysql_error($this->link));
        @mysql_query('SELEC 1', $this->link);
        $this->assertNotSame('', mysql_error($this->link));
    }

    public function test_mysql_affected_rows(): void
    {
        mysql_query("UPDATE mysql2pdo_fn SET label = 'gamma' WHERE id = 1", $this->link);
        $this->assertSame(1, mysql_affected_rows($this->link));
    }

    public function test_mysql_insert_id(): void
    {
        mysql_query("INSERT INTO mysql2pdo_fn (label, amount) VALUES ('new', 3)", $this->link);
        $this->assertGreaterThan(0, mysql_insert_id($this->link));
    }

    public function test_mysql_info(): void
    {
        $this->assertSame('', mysql_info($this->link));
    }

    public function test_mysql_stat(): void
    {
        $stat = mysql_stat($this->link);
        $this->assertIsString($stat);
        $this->assertNotSame('', $stat);
    }

    public function test_mysql_get_client_info(): void
    {
        $info = mysql_get_client_info();
        $this->assertIsString($info);
        $this->assertNotSame('', $info);
    }

    public function test_mysql_get_host_info(): void
    {
        $info = mysql_get_host_info($this->link);
        $this->assertIsString($info);
        $this->assertStringContainsString('TCP/IP', $info);
    }

    public function test_mysql_get_proto_info(): void
    {
        $this->assertGreaterThan(0, mysql_get_proto_info($this->link));
    }

    public function test_mysql_get_server_info(): void
    {
        $info = mysql_get_server_info($this->link);
        $this->assertIsString($info);
        $this->assertNotSame('', $info);
    }

    public function test_mysql_client_encoding(): void
    {
        $enc = mysql_client_encoding($this->link);
        $this->assertIsString($enc);
        $this->assertNotSame('', $enc);
    }

    public function test_mysql_thread_id(): void
    {
        $this->assertGreaterThan(0, mysql_thread_id($this->link));
    }

    public function test_mysql_real_escape_string(): void
    {
        $raw = "a'b\\c";
        $escaped = mysql_real_escape_string($raw, $this->link);
        mysql_query("INSERT INTO mysql2pdo_fn (label, amount) VALUES ('{$escaped}', 0)", $this->link);
        $result = mysql_query(
            'SELECT label FROM mysql2pdo_fn ORDER BY id DESC LIMIT 1',
            $this->link
        );
        $row = mysql_fetch_row($result);
        $this->assertSame([$raw], $row);
        mysql_free_result($result);
    }

    public function test_mysql_escape_string(): void
    {
        $this->expectUserDeprecationMessage('mysql_escape_string() is deprecated and insecure. Use mysql_real_escape_string() or prepared statements instead.');
        $escaped = mysql_escape_string("x'y");
        $this->assertIsString($escaped);
    }

    public function test_mysql_set_charset(): void
    {
        $this->assertTrue(mysql_set_charset('utf8mb4', $this->link));
        $this->assertSame('utf8mb4', mysql_client_encoding($this->link));
    }

    public function test_mysql_create_db(): void
    {
        $name = 'mysql2pdo_tmp_' . bin2hex(random_bytes(4));
        $this->assertTrue(mysql_create_db($name, $this->link));
        $this->assertTrue(mysql_drop_db($name, $this->link));
    }

    public function test_mysql_drop_db(): void
    {
        $name = 'mysql2pdo_tmp_' . bin2hex(random_bytes(4));
        mysql_create_db($name, $this->link);
        $this->assertTrue(mysql_drop_db($name, $this->link));
    }

    public function test_mysql_list_dbs(): void
    {
        $result = mysql_list_dbs($this->link);
        $this->assertInstanceOf(ShimResult::class, $result);
        $this->assertGreaterThan(0, mysql_num_rows($result));
        mysql_free_result($result);
    }

    public function test_mysql_list_tables(): void
    {
        $result = mysql_list_tables(TestDatabase::database(), $this->link);
        $this->assertInstanceOf(ShimResult::class, $result);
        mysql_free_result($result);
    }

    public function test_mysql_list_fields(): void
    {
        $result = mysql_list_fields(TestDatabase::database(), 'mysql2pdo_fn', $this->link);
        $this->assertInstanceOf(ShimResult::class, $result);
        $this->assertGreaterThan(0, mysql_num_fields($result));
        mysql_free_result($result);
    }

    public function test_mysql_list_processes(): void
    {
        $thread = mysql_list_processes($this->link);
        $this->assertSame(mysql_thread_id($this->link), $thread);
    }

    public function test_mysql_db_name(): void
    {
        $result = mysql_list_dbs($this->link);
        $name = mysql_db_name($result, 0);
        $this->assertIsString($name);
        $this->assertNotSame('', $name);
        mysql_free_result($result);
    }

    public function test_mysql_tablename(): void
    {
        $result = mysql_list_tables(TestDatabase::database(), $this->link);
        $found = false;
        $rows = mysql_num_rows($result);
        for ($i = 0; $i < $rows; $i++) {
            if (mysql_tablename($result, $i) === 'mysql2pdo_fn') {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
        mysql_free_result($result);
    }

    public function test_mysql2pdo_resolve_link(): void
    {
        $this->assertSame($this->link, \mysql2pdo::resolveLink($this->link));
        $this->assertSame($this->link, \mysql2pdo::resolveLink(null));
    }
}
