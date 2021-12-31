<?php

class Database{
	/* 
	 * Create variables for credentials to MySQL database
	 * The variables have been declared as private. This
	 * means that they will only be available with the 
	 * Database class
	 */
	private $db_host = "localhost";  // Change as required
	private $db_user = "wp";  // Change as required
	private $db_pass = "wp";  // Change as required
	private $db_name = "nonwp";	// Change as required	
	
	/*
	 * Extra variables that are required by other function such as boolean con variable
	 */
	private $connected = false; // Check to see if the connection is active
    private $connection = ""; // This will be our mysqli object
	private $result = array(); // Any results from a query will be stored here
    private $query = "";// used for debugging process with SQL return
    private $numResults = "";// used for returning the number of rows

    public $insert_id = 0;
	
	// Function to make connection to database
	public function connect(){
		if(!$this->connected){
			$this->connection = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name);  // mysql_connect() with variables defined at the start of Database class
            
            if($this->connection->connect_errno > 0){
                array_push($this->result,$this->connection->connect_error);
                return false; // Problem selecting database return FALSE
            }else{
                $this->connected = true;
                return true; // Connection has been made return TRUE
            } 
        } 
            
        return true; // Connection has already been made return TRUE  	
	}
	
	// Function to disconnect from the database
    public function disconnect(){
    	// If there is a connection to the database
    	if($this->connected){
    		// We have found a connection, try to close it, otherwise return false.
    		if($this->connection->close()){
    			$this->connected = false; // We have successfully closed the connection, set the connection variable to false
				
				return true; // Return true that we have closed the connection
			}else{
				return false;
			}
		}
    }
	
	public function sql($sql){
		$query = $this->connection->query($sql);
        
        $this->query = $sql; // Pass back the SQL
        
		if($query){
			$this->numResults = $query->num_rows; // If the query returns >= 1 assign the number of rows to numResults
			
			// Loop through the query results by the number of rows returned
			for($i = 0; $i < $this->numResults; $i++){
				$r = $query->fetch_array();
               	$key = array_keys($r);
               	for($x = 0; $x < count($key); $x++){
               		// Sanitizes keys so only alphavalues are allowed
                   	if(!is_int($key[$x])){
                   		if($query->num_rows >= 1){
                   			$this->result[$i][$key[$x]] = $r[$key[$x]];
						}else{
							$this->result = null;
						}
					}
				}
			}
			
			return true; // Query was successful
		}else{
			array_push($this->result,$this->connection->error);
			
			return false; // No rows where returned
		}
	}
	
	public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){
		// Create query from the variables passed to the function
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($join != null){
			$q .= ' JOIN '.$join;
		}
        if($where != null){
        	$q .= ' WHERE '.$where;
		}
        if($order != null){
            $q .= ' ORDER BY '.$order;
		}
        if($limit != null){
            $q .= ' LIMIT '.$limit;
        }
        
        $this->query = $q; // Pass back the SQL
		
		// Check to see if the table exists
        if($this->table_exists($table)){
        	// The table exists, run the query
        	$query = $this->connection->query($q);    
		
			if($query){
				// If the query returns >= 1 assign the number of rows to numResults
				$this->numResults = $query->num_rows;
				// Loop through the query results by the number of rows returned
				for($i = 0; $i < $this->numResults; $i++){
					$r = $query->fetch_array();
                	$key = array_keys($r);
                	for($x = 0; $x < count($key); $x++){
                		// Sanitizes keys so only alphavalues are allowed
                    	if(!is_int($key[$x])){
                    		if($query->num_rows >= 1){
                    			$this->result[$i][$key[$x]] = $r[$key[$x]];
							}else{
								$this->result[$i][$key[$x]] = null;
							}
						}
					}
				}
				
				return true; // Query was successful
			}else{	
				array_push($this->result,$this->connection->error);
				
				return false; // No rows where returned
			}
      	}
    
        return false; // Table does not exist
    }
	
	// Function to insert into the database
    public function insert($table,$params=array()){
    	// Check to see if the table exists
    	 if($this->table_exists($table)){
    	 	$sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES ("' . implode('", "', $params) . '")';
            $this->query = $sql; // Pass back the SQL
            // Make the query to insert to the database
            if($ins = $this->connection->query($sql)){
            	array_push($this->result,$this->connection->insert_id);
            	
            	$this->insert_id = $this->connection->insert_id;
                
                return true; // The data has been inserted
            }else{
            	array_push($this->result,$this->connection->error);
                
                return false; // The data has not been inserted
            }
        }
        
        return false; // Table does not exist
    }
	
	//Function to delete table or row(s) from database
    public function delete($table,$where = null){
    	// Check to see if table exists
    	 if($this->table_exists($table)){
    	 	// The table exists check to see if we are deleting rows or table
    	 	if($where == null){
                $delete = 'DROP TABLE '.$table; // Create query to delete table
            }else{
                $delete = 'DELETE FROM '.$table.' WHERE '.$where; // Create query to delete rows
            }
            
            // Submit query to database
            if($del = $this->connection->query($delete)){
            	array_push($this->result,$this->connection->affected_rows);
                $this->query = $delete; // Pass back the SQL
                return true; // The query exectued correctly
            }else{
            	array_push($this->result,$this->connection->error);
               	return false; // The query did not execute correctly
            }
        }
            
        return false; // The table does not exist
    }
	
	// Function to update row in database
    public function update($table,$params=array(),$where=array()){
		$fields     = array();
		$conditions = array();
		$values     = array();
		        
    	// Check to see if table exists
    	if($this->table_exists($table)){
    		// Create Array to hold all the columns to update
            $args=array();
			foreach($params as $field=>$value){
				// Seperate each column out with it's corresponding value
				$args[]=$field.'="'.$value.'"';
			}
			
		foreach ( $where as $field => $value ) {
			if ( is_null( $value ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}

			$conditions[] = "`$field` = " . $value;
		}

		$conditions = implode( ' AND ', $conditions );
		
			// Create the query
			$sql='UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$conditions;
		
			// Make query to database
            $this->query = $sql; // Pass back the SQL
            
            if($query = $this->connection->query($sql)){
            	array_push($this->result,$this->connection->affected_rows);
            	
            	return true; // Update has been successful
            }else{
            	array_push($this->result,$this->connection->error);
            	
                return false; // Update has not been successful
            }
        }
        
        return false; // The table does not exist
    }
    
	/**
	 * Retrieves one row from the database.
	 *
	 * Executes a SQL query and returns the row from the SQL result.
	 *
	 * @since 0.1.0
	 *
	 * @param string|null $query SQL query.
	 * @param int $y Optional. Row to return. Indexed from 0.
	 *
	 * @return array|object|null|void
	 */    
	public function get_row( $query = null, $y = 0 ) {
		if ( $query ) {
			$this->sql( $query );
		} else {
			return null;
		}

		if ( ! isset( $this->result[ $y ] ) ) {
			return null;
		}

        return $this->result[ $y ] ? $this->result[ $y ] : null;
	}
	
	/**
	 * Retrieves one variable from the database.
	 *
	 * Executes a SQL query and returns the value from the SQL result.
	 * If the SQL result contains more than one column and/or more than one row,
	 * the value in the column and row specified is returned. If $query is null,
	 * the value in the specified column and row from the previous SQL result is returned.
	 *
	 * @since 0.71
	 *
	 * @param string|null $query Optional. SQL query. Defaults to null, use the result from the previous query.
	 * @param int $x Optional. Column of value to return. Indexed from 0.
	 * @param int $y Optional. Row of value to return. Indexed from 0.
	 *
	 * @return string|null Database query result (as string), or null on failure.
	 */
	public function get_var( $query = null, $x = 0, $y = 0 ) {
		if ( $query ) {
			$this->sql( $query );
		}

		// Extract var out of cached results based on x,y vals.
		if ( ! empty( $this->result[ $y ] ) ) {
			$values = array_values( $this->result[ $y ] );
		}

		// If there is a value return it, else return null.
		return ( isset( $values[ $x ] ) && '' !== $values[ $x ] ) ? $values[ $x ] : null;
	}	   
	
	// Check if table exists for use with queries
	public function table_exists($table){
		$tablesInDb = $this->connection->query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb){
        	if($tablesInDb->num_rows == 1){
                return true; // The table exists
            }else{
            	array_push($this->result,$table." does not exist in this database");
                return false; // The table does not exist
            }
        }
    }
	
	// Public function to return the data to the user
    public function get_result(){
        $val = $this->result;
        $this->result = array();
        return $val;
    }

    //Pass the SQL back for debugging
    public function get_sql(){
        $val = $this->query;
        $this->query = array();
        return $val;
    }

    //Pass the number of rows back
    public function num_rows(){
        $val = $this->numResults;
        $this->numResults = array();
        return $val;
    }

    // Escape your string
    public function escape_string($data){
        return $this->connection->real_escape_string($data);
    }
    
    public function insert_id() {
        $val = $this->insert_id;
        $this->insert_id = array();
        return $val;        
    }
} 
