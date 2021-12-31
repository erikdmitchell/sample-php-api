<?php
require __DIR__ . '/bootstrap.php';

global $apidb;

$dbuser = 'wp';
$dbpass = 'wp';
$dbname = 'nonwp';

$apidb = new Database();
$apidb->connect();

/*
echo '<pre>';
print_r($apidb);
echo '</pre>';
*/

$testdb = new DB();

/*
echo '<pre>';
print_r($testdb);
echo '</pre>';
*/

/*
if ($testdb->table_exists()){
    echo 'table exists<br>';
} else {
    echo 'table does not exist<br>';   
}
*/


/*
$insert_test_data = array(
    'name' => 'Dr. No',
    'year' => 1962,
    'actor' => 'Sean Connery',
    'director' => 'Terence Young',
    'image' => 'https =>//upload.wikimedia.org/wikipedia/en/4/43/Dr._No_-_UK_cinema_poster.jpg'
);
$test_row_id = $testdb->insert($insert_test_data);
*/
//$test_row_id = 2;
/*
$update_test_id = 1; //(prev result);
$update_test_data = array(
    'image' => 'https://upload.wikimedia.org/wikipedia/en/4/43/Dr._No_-_UK_cinema_poster.jpg'
);

echo $testdb->update($update_test_id, $update_test_data);
*/

/*
echo '<br />get()<br />';
print_r($testdb->get($test_row_id));
*/

/*
echo '<br />get_by()<br />';
print_r($testdb->get_by( 'name', 'Dr. No'));
*/

/*
echo '<br />get_column()<br />';
print_r($testdb->get_column( 'director', $test_row_id));
*/

/*
echo '<br />get_column_by()<br />';
print_r($testdb->get_column_by( 'actor', 'year', '1962'));
*/

// main db class SELECT.
/*
$table = 'films';
$rows = '*';
$join = null;
$where = array('name' => 'Dr. No');
$order = null;
$limit = null;
//$select_return = $apidb->select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
$apidb->select($table, $rows, $join, $where, $order, $limit);
print_r($apidb->get_result());
*/

//echo $testdb->delete($test_row_id);