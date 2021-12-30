<?php
require __DIR__ . '/bootstrap.php';

global $apidb;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$apidb = new Database();
$apidb->connect();

print_r($apidb);