<?php
/**
 * Main API class
 *
 * @package PHPAPI
 * @version 0.1.0
 */

namespace Mitchell\API\API;

use Mitchell\API\Database\Films;

/**
 * API class.
 */
class API {

    public function __construct() {}

    public function create() {
        //global $db;

		/*
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

		include_once '../config/database.php';
		include_once '../class/users.php';

		$database = new DB();
		$db = $database->getConnection();
		*/

		$data = json_decode( file_get_contents( 'php://input' ) );
		print_r( $data );
		/*
		$item->name = $data->name;
		$item->email = $data->email;
		$item->age = $data->age;
		$item->profile = $data->profile;
		$item->created = date('Y-m-d H:i:s');

		if($item->createUser()){
        echo 'User created.';
		} else{
        echo 'User was not created.';
		}
		*/

    }

    public function get() {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Content-Type: application/json; charset=UTF-8' );

$f = new Films();
$x = $f->get_films();
print_r($x);
echo database()->num_rows();
/*


		$stmt      = $items->getUsers();
		$itemCount = $stmt->rowCount();

		echo json_encode( $itemCount );

		if ($itemCount > 0) {

			$userArr              = array();
			$userArr['body']      = array();
			$userArr['itemCount'] = $itemCount;

			while ($row = $stmt->fetch( PDO::FETCH_ASSOC )) {
				extract( $row );
				$e = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'age' => $age,
                'profile' => $profile,
                'created' => $created
				);

				array_push( $userArr['body'], $e );
			}
			echo json_encode( $userArr );
		} else {
			http_response_code( 404 );
			echo json_encode(
                array('message' => 'Data not found.')
			);
		}
*/
    }

    public function update() {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Content-Type: application/json; charset=UTF-8' );
		header( 'Access-Control-Allow-Methods: POST' );
		header( 'Access-Control-Max-Age: 3600' );
		header( 'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With' );

		include_once '../config/database.php';
		include_once '../class/users.php';

		$database = new DB();
		$db       = $database->getConnection();

		$item = new User( $db );

		$data = json_decode( file_get_contents( 'php://input' ) );

		$item->id = $data->id;

		// employee values
		$item->name    = $data->name;
		$item->email   = $data->email;
		$item->age     = $data->age;
		$item->profile = $data->profile;
		$item->created = date( 'Y-m-d H:i:s' );

		if ($item->updateEmployee()) {
			echo json_encode( 'User record updated.' );
		} else {
			echo json_encode( 'User record could not be updated.' );
		}
    }

    public function delete() {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Content-Type: application/json; charset=UTF-8' );
		header( 'Access-Control-Allow-Methods: POST' );
		header( 'Access-Control-Max-Age: 3600' );
		header( 'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With' );

		include_once '../config/database.php';
		include_once '../class/users.php';

		$database = new DB();
		$db       = $database->getConnection();

		$item = new User( $db );

		$data = json_decode( file_get_contents( 'php://input' ) );

		$item->id = $data->id;

		if ($item->deleteUser()) {
			echo json_encode( 'User deleted.' );
		} else {
			echo json_encode( 'Not deleted' );
		}
    }





    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput( $data, $httpHeaders = array()) {
        header_remove( 'Set-Cookie' );

        if (is_array( $httpHeaders ) && count( $httpHeaders )) {
            foreach ($httpHeaders as $httpHeader) {
                header( $httpHeader );
            }
        }

        echo $data;
        exit;
    }

    function errors() {

                $strErrorDesc   = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';

            $strErrorDesc   = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode( array('error' => $strErrorDesc) ),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

	protected function error( $code, $message, $http_status_code = 400, $data = array() ) {
		// throw new WC_Data_Exception( $code, $message, $http_status_code, $data );
	}
}
