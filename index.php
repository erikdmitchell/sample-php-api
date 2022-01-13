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


// Load core packages and the autoloader.
/*
require __DIR__ . '/src/Autoloader.php';
require __DIR__ . '/src/Packages.php';
*/

/*
if ( ! \Automattic\WooCommerce\Autoloader::init() ) {
	return;
}
\Automattic\WooCommerce\Packages::init();
*/

$app = new Mitchell\API\App\App;

echo '<h1>Welcome to the App</h1>';

echo '<a href="routes.php">Routes</a>';
echo '<br>';
echo '<a href="tests.php">Tests</a>';

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

use Mitchell\API\Database\Films;

        $films_class = new Films();
        $films = $films_class->get_films(array('id' => 3));
print_r($films);        

?>
<h2>Rutes</h2>

<ul>
    <li><a href="routes.php?films">Films</a>
    <li><a href="routes.php?film&id=3">Film (ID: 3)</a>    
</ul>