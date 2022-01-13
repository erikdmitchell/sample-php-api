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

use Mitchell\API\API\API; // what a shitty namespace!

$api = new API();

if (isset($_REQUEST['films'])) {  
    $api->get('films');
}

if (isset($_REQUEST['film'])) {  
    $api->get('film', $_REQUEST['id']);
}




// Accept only numbers as parameter. Other characters will result in a 404 error
/*
Route::add('/foo/([0-9]*)/bar', function($var1) {
  navi();
  echo $var1.' is a great number!';
});

// Crazy route with parameters
Route::add('/(.*)/(.*)/(.*)/(.*)', function($var1,$var2,$var3,$var4) {
  navi();
  echo 'This is the first match: '.$var1.' / '.$var2.' / '.$var3.' / '.$var4.'<br>';
});
*/

/*
echo '<pre>';
print_r($app);
echo '</pre>';
*/



// Accept only numbers as parameter. Other characters will result in a 404 error
/*
Route::add('/foo/([0-9]*)/bar', function($var1) {
  navi();
  echo $var1.' is a great number!';
});

// Crazy route with parameters
Route::add('/(.*)/(.*)/(.*)/(.*)', function($var1,$var2,$var3,$var4) {
  navi();
  echo 'This is the first match: '.$var1.' / '.$var2.' / '.$var3.' / '.$var4.'<br>';
});
*/