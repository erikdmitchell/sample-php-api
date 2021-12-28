<?php
require __DIR__ . '/bootstrap.php';

global $db;

$dbhost = 'localhost';
$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$db = new APIDB($dbhost, $dbuser, $dbpass, $dbname);



 