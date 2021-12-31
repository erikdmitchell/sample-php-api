<?php
/*
use PHPUnit\Framework\TestCase;

class MyTest extends TestCase {
    public function testCalculate() {
        $this->assertEquals(2, 1 + 1);
    }
}
*/

/*
  To Do:
  
  DB Schema - If you are using a persistent database (not Sqlite Memory) you can easily setup the database once with tools such as phpMyAdmin for MySQL and re-use the database for every test-run.  
    
*/

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class MyDBTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = new PDO('sqlite::memory:');
        return $this->createDefaultDBConnection($pdo, ':memory:');
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/guestbook-seed.xml');
    }
}

//use PHPUnit\Framework\TestCase;
//use PHPUnit\DbUnit\TestCaseTrait;

abstract class MyApp_Tests_DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('sqlite::memory:');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
    }
}


use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

abstract class Generic_Tests_DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
}

// MySQL XML DataSet

// mysqldump --xml -t -u [username] --password=[password] [database] > /path/to/file.xml

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

// db dataset

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class MySqlGuestbookTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $database = 'my_database';
        $user = 'my_user';
        $password = 'my_password';
        $pdo = new PDO('mysql:...', $user, $password);
        return $this->createDefaultDBConnection($pdo, $database);
    }

    public function testGuestbook()
    {
        $dataSet = $this->getConnection()->createDataSet();
        // ...
    }

    public function testFilteredGuestbook()
    {
        $tableNames = ['guestbook'];
        $dataSet = $this->getConnection()->createDataSet($tableNames);
        // ...
    }
}

// create dataset
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ConnectionTest extends TestCase
{
    use TestCaseTrait;

    public function testCreateDataSet()
    {
        $tableNames = ['guestbook'];
        $dataSet = $this->getConnection()->createDataSet();
    }
}

// create query table
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ConnectionTest extends TestCase
{
    use TestCaseTrait;

    public function testCreateQueryTable()
    {
        $tableNames = ['guestbook'];
        $queryTable = $this->getConnection()->createQueryTable('guestbook', 'SELECT * FROM guestbook');
    }
}

// get row count
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class ConnectionTest extends TestCase
{
    use TestCaseTrait;

    public function testGetRowCount()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('guestbook'));
    }
}

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
 
        $expectedTable = $this->createFlatXmlDataSet("./tests/guestbook_expected.xml")
                              ->getTable("guestbook");
 
        $this->assertTablesEqual($expectedTable, $queryTable);
 
    }
}