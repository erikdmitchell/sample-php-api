<?php
require __DIR__ . '/bootstrap.php';

global $db;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$db = new Database();
$db->connect();

print_r($db);