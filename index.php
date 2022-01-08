<?php
/**
 * Main file
 *
 * @package PHPAPI
 * @version 0.1.0
 */

define( 'API_ROOT_PATH', __DIR__ . '/src/' );

require_once __DIR__ . '/autoload.php';

include_once( API_ROOT_PATH . 'app/functions.php' );

$app = new Mitchell\API\App\App;

echo '<h1>Welcome to the App</h1>';

/*
echo '<pre>';
print_r($app);
echo '</pre>';
*/

// global $apidb;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

/*
$apidb = new Mitchell\API\Config\Database;
$apidb->connect();
*/


// Retrieve the singleton client instance.
$db = Mitchell\API\Config\Database::getInstance( array('db_name' => 'nonwp') );

// Make use of our client instance.
// $results = $db->connect();


/*
echo '<pre>';
print_r($db);
echo '</pre>';
*/

$films_db = new Mitchell\API\Database\Films;

/*
echo '<pre>';
print_r($films_db);
echo '</pre>';
*/

/**
 * How do we include functions.php?
 * How do we use our install file?
 */

// class API :)

echo '<h2>Tests</h2>';

/*
if ($films_db->table_exists()) {
    echo 'films db table exists<br>';
} else {
    echo 'films db table does not exist<br>';
}
*/

/*
$insert_test_data = array(
    'name' => 'Dr. No',
    'year' => 1962,
    'actor' => 'Sean Connery',
    'director' => 'Terence Young',
    'image' => 'https://upload.wikimedia.org/wikipedia/en/4/43/Dr._No_-_UK_cinema_poster.jpg'
);
*/

// echo $test_row_id = $films_db->insert($insert_test_data);

// $test_row_id = 3;

/*
$update_test_data = array(
    'year' => '1962'
);

echo $films_db->update($test_row_id, $update_test_data);
*/
echo '<pre>';

/*
echo '<br />get()<br />';
print_r($films_db->get($test_row_id));
*/

/*
echo '<br />get_by()<br />';
print_r($films_db->get_by( 'name', 'Dr. No'));
*/

/*
echo '<br />get_column()<br />';
print_r($films_db->get_column( 'director', $test_row_id));
echo '<p>';
*/
/*
echo '<br />get_column_by()<br />';
print_r($films_db->get_column_by( 'actor', 'year', '1962'));
echo '<p>';
*/
/*
// main db class SELECT.
$table = 'films';
$rows = '*';
$join = null;
$where = array('name' => 'Dr. No');
$order = null;
$limit = null;
// $select_return = $apidb->select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
$db->select($table, $rows, $join, $where, $order, $limit);
print_r($db->get_result());
echo '</pre>';
// echo $testdb->delete($test_row_id);
*/
