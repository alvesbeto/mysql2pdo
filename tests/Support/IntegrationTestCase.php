<?php

declare(strict_types=1);

namespace Mysql2pdo\Tests\Support;

use Mysql2pdo\Driver\ShimLink;
use Mysql2pdo\Driver\ShimResult;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected ShimLink $link;

    protected function setUp(): void
    {
        $link = TestDatabase::connect();
        $this->assertNotFalse($link, 'mysql_connect failed: ' . mysql_error());
        $this->link = $link;
        $this->recreateFixtureTable();
    }

    protected function tearDown(): void
    {
        @mysql_query('DROP TABLE IF EXISTS mysql2pdo_fn', $this->link);
    }

    protected function recreateFixtureTable(): void
    {
        mysql_query('DROP TABLE IF EXISTS mysql2pdo_fn', $this->link);
        $this->assertTrue((bool) mysql_query(
            'CREATE TABLE mysql2pdo_fn (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                label VARCHAR(64) NOT NULL,
                amount DECIMAL(10,2) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB',
            $this->link
        ));
        $this->assertTrue((bool) mysql_query(
            "INSERT INTO mysql2pdo_fn (label, amount) VALUES ('alpha', 10.50), ('beta', 2.00)",
            $this->link
        ));
    }

    /**
     * @return ShimResult|false
     */
    protected function fixtureSelectResult(): ShimResult|false
    {
        return mysql_query(
            'SELECT id, label, amount FROM mysql2pdo_fn ORDER BY id',
            $this->link
        );
    }
}
