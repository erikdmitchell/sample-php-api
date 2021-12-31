<?php
require __DIR__ . '/bootstrap.php';

global $apidb;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$apidb = new Database();
$apidb->connect();

echo '<pre>';
print_r($apidb);
echo '</pre>';

$testdb = new DB();

echo '<pre>';
print_r($testdb);
echo '</pre>';

if ($testdb->table_exists()){
    echo 'table exists<br>';
} else {
    echo 'table does not exist<br>';   
}