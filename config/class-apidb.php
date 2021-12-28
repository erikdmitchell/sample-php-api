<?php 

/**
 * DB class.
 */
class APIDB {

    /**
     * connection
     * 
     * @var mixed
     * @access protected
     */
    protected $connection;
    
	/**
	 * query
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $query;
    
    /**
     * show_errors
     * 
     * (default value: TRUE)
     * 
     * @var mixed
     * @access protected
     */
    protected $show_errors = TRUE;
    
    /**
     * query_closed
     * 
     * (default value: TRUE)
     * 
     * @var mixed
     * @access protected
     */
    protected $query_closed = TRUE;
	
	/**
	 * query_count
	 * 
	 * (default value: 0)
	 * 
	 * @var int
	 * @access public
	 */
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
    
    /**
     * query function.
     * 
     * @access public
     * @param mixed $query
     * @return void
     */
    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
        
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->errno) {
				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
            $this->query_closed = FALSE;
			$this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }    	    

	/**
	 * fetchAll function.
	 * 
	 * @access public
	 * @param mixed $callback (default: null)
	 * @return void
	 */
	public function fetchAll($callback = null) {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
		return $result;
	}

	/**
	 * fetchArray function.
	 * 
	 * @access public
	 * @return void
	 */
	public function fetchArray() {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				$result[$key] = $val;
			}
		}
        $this->query->close();
        $this->query_closed = TRUE;
		return $result;
	}

	/**
	 * close function.
	 * 
	 * @access public
	 * @return void
	 */
	public function close() {
		return $this->connection->close();
	}

    /**
     * numRows function.
     * 
     * @access public
     * @return void
     */
    public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	/**
	 * affectedRows function.
	 * 
	 * @access public
	 * @return void
	 */
	public function affectedRows() {
		return $this->query->affected_rows;
	}

    /**
     * lastInsertID function.
     * 
     * @access public
     * @return void
     */
    public function lastInsertID() {
    	return $this->connection->insert_id;
    }

    /**
     * error function.
     * 
     * @access public
     * @param mixed $error
     * @return void
     */
    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

	/**
	 * _gettype function.
	 * 
	 * @access private
	 * @param mixed $var
	 * @return void
	 */
	private function _gettype($var) {
	    if (is_string($var)) return 's';
	    if (is_float($var)) return 'd';
	    if (is_int($var)) return 'i';
	    return 'b';
	}

}