<?php

// MySQL XML DataSet

// mysqldump --xml -t -u [username] --password=[password] [database] > /path/to/file.xml

/*
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class MyTestCase extends TestCase
{
    use TestCaseTrait;

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet('/path/to/file.xml');
    }
}
*/

// guestbook test
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
 
class GuestBookTest extends TestCase{
    use TestCaseTrait;
 
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;
 
    // only instantiate PHPUnit\DbUnit\Database\Connection once per test
    private $conn = null;
 
    final public function getConnection() {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }
 
        return $this->conn;
    }
 
    public function getDataSet() {
        return $this->createFlatXmlDataSet('./tests/guestbook_fixture.xml');
    }
 
    public function testRowCount() {
        $this->assertSame(2, $this->getConnection()->getRowCount('guestbook'), "Pre-Condition");
    }
 
    public function testAddGuest() {
 
        $guestbook = new GuestBook();
        $guestbook->addGuest("George", "AP, India", "4545");
 
        $queryTable = $this->getConnection()->createQueryTable(
            'guestbook', 'SELECT id, name, address, phone FROM guestbook'
        );
 
        $expectedTable = $this->createFlatXmlDataSet("./tests/guestbook_expected.xml")->getTable("guestbook");
 
        $this->assertTablesEqual($expectedTable, $queryTable);
 
    }
}