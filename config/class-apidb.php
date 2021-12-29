<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * DB class.
 */
class APIDB {

    protected $connection;
    
	protected $query;

    protected $show_errors = true;

	public $insert_id = 0;    

    //protected $query_closed = true;

	public $query_count = 0;
    
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param string $dbuser (default: 'root')
	 * @param string $dbpassword (default: '')
	 * @param string $dbname (default: '')
	 * @param string $dbhost (default: 'localhost')
	 * @param string $charset (default: 'utf8')
	 * @return void
	 */
	public function __construct( $dbuser = 'root', $dbpass = '', $dbname = '', $dbhost = 'localhost', $charset = 'utf8' ) {  	
		$this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		
		if ($this->connection->connect_error) {
			$this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
		}
		
		$this->connection->set_charset($charset);       
    }

	private function _gettype($var) {
	    if (is_string($var)) return 's';
	    if (is_float($var)) return 'd';
	    if (is_int($var)) return 'i';
	    return 'b';
	}

	public function _escape( $data ) {

	}

	public function query( $query ) {

        $stmt = $this->connection->prepare( $query );
        $stmt->execute($query);
        
        return $stmt;
	}

	public function insert( $table, $data, $format = null ) {
        // Insert a row/s in a Database Table
        public function Insert( $statement = "" , $parameters = [] ){
            try{
				
                $this->executeStatement( $statement , $parameters );
                return $this->connection->lastInsertId();
				
            }catch(Exception $e){
                throw new Exception($e->getMessage());   
            }		
        }
	}
	
        // Select a row/s in a Database Table
        public function Select( $statement = "" , $parameters = [] ){
            try{
				
                $stmt = $this->executeStatement( $statement , $parameters );
                return $stmt->fetchAll();
				
            }catch(Exception $e){
                throw new Exception($e->getMessage());   
            }		
        }		  	    	

	public function get_var( $query = null, $x = 0, $y = 0 ) {

	}

	public function get_row( $query = null, $output = OBJECT, $y = 0 ) {

	}

	public function update( $table, $data, $where, $format = null, $where_format = null ) {
        // Update a row/s in a Database Table
        public function Update( $statement = "" , $parameters = [] ){
            try{
				
                $this->executeStatement( $statement , $parameters );
				
            }catch(Exception $e){
                throw new Exception($e->getMessage());   
            }		
        }	
	}

	public function delete( $table, $column, $value ) {
    	return $this->query( "DELETE FROM $table WHERE $column = $value" );
	}	

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }			

}



class xyz {
  /**
  * Open the connection to your database.
  */
  function open() {
    $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
  }

  /**
  * Close the connection to your database.
  */
  function close() {
    $this->connection->close();
  }

  /**
  *
  * Execute your query
  *
  * @param string $query - your sql query
  * @return the result of the executed query 
  */
  function query($query) {
    return $this->connection->query($query);
  }

  /**
  * Escape your parameters to prevent SQL Injections! Usage: See documentation (link at the top of the file)
  *
  * @param string $string - your parameter to escape
  * @return the escaped string 
  */
  function escape($string) {
    return $this->connection->escape_string($string);
  }
}

