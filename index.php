<?php
/**
 * Main file
 *
 * @package PHPAPI
 * @version 0.1.0
 */

define( 'API_ROOT_PATH', __DIR__ . '/src/' );

require_once __DIR__ . '/autoload.php';

$app = new Mitchell\API\App\App;

echo '<h1>Welcome to the App</h1>';
echo '<pre>';
print_r($app);
echo '</pre>';

global $apidb;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$apidb = new Mitchell\API\Config\Database;
$apidb->connect();

echo '<pre>';
print_r($apidb);
echo '</pre>';

//$testdb = new Mitchell\API\Database\Abstracts\DB; //-- do not use

$films_db = new Mitchell\API\Database\Films;

echo '<pre>';
print_r($films_db);
echo '</pre>';

/**
 * How do we include functions.php?
 * How do we use our install file?
 */
 
// class API :)