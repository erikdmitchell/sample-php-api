<?php

use PHPUnit\Framework\TestCase;

class APIDBTest extends TestCase {
    
    protected function setUp(): void {
        if (!extension_loaded('mysqli')) {
            $this->markTestSkipped(
              'The MySQLi extension is not available.'
            );
        }
    }
    
    public function testConnection(): void {
$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$db = new APIDB($dbuser, $dbpass, $dbname);


        $firstclass=new FirstClass();
        $encoded=$firstclass->encodeString("string");
        $this->assertSame($encoded, "120rwp");
        
        
    }        

}