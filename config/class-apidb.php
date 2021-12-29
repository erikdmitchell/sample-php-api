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

/*
	public function escape( $string ) {
        return $this->connection->escape_string($string);
	}
*/

    // test
	public function query( $query ) {
        return $this->connection->query($query);
	}
	



//printf("New record has ID %d.\n", $mysqli->insert_id);

	
    // test
	public function insert( $table, $data, $format = null ) {
        return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );   	
    }
    
    // test
	public function replace( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
	}    
	
    public function select() {}

    // test
	/**
	 * Updates a row in the table.
	 *
	 * Examples:
	 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
	 *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
	 *
	 * @since 2.5.0
	 *
	 * @see wpdb::prepare()
	 * @see wpdb::$field_types
	 * @see wp_set_wpdb_vars()
	 *
	 * @param string       $table        Table name.
	 * @param array        $data         Data to update (in column => value pairs).
	 *                                   Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                                   Sending a null value will cause the column to be set to NULL - the corresponding
	 *                                   format is ignored in this case.
	 * @param array        $where        A named array of WHERE clauses (in column => value pairs).
	 *                                   Multiple clauses will be joined with ANDs.
	 *                                   Both $where columns and $where values should be "raw".
	 *                                   Sending a null value will create an IS NULL comparison - the corresponding
	 *                                   format will be ignored in this case.
	 * @param array|string $format       Optional. An array of formats to be mapped to each of the values in $data.
	 *                                   If string, that format will be used for all of the values in $data.
	 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                   If omitted, all values in $data will be treated as strings unless otherwise
	 *                                   specified in wpdb::$field_types.
	 * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
	 *                                   If string, that format will be used for all of the items in $where.
	 *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
	 *                                   If omitted, all values in $where will be treated as strings.
	 * @return int|false The number of rows updated, or false on error.
	 */
	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		if ( ! is_array( $data ) || ! is_array( $where ) ) {
			return false;
		}

		$data = $this->process_fields( $table, $data, $format );
		if ( false === $data ) {
			return false;
		}
		$where = $this->process_fields( $table, $where, $where_format );
		if ( false === $where ) {
			return false;
		}

		$fields     = array();
		$conditions = array();
		$values     = array();
		foreach ( $data as $field => $value ) {
			if ( is_null( $value['value'] ) ) {
				$fields[] = "`$field` = NULL";
				continue;
			}

			$fields[] = "`$field` = " . $value['format'];
			$values[] = $value['value'];
		}
		foreach ( $where as $field => $value ) {
			if ( is_null( $value['value'] ) ) {
				$conditions[] = "`$field` IS NULL";
				continue;
			}

			$conditions[] = "`$field` = " . $value['format'];
			$values[]     = $value['value'];
		}

		$fields     = implode( ', ', $fields );
		$conditions = implode( ' AND ', $conditions );

		$sql = "UPDATE `$table` SET $fields WHERE $conditions";

		$this->check_current_query = false;
		return $this->query( $this->prepare( $sql, $values ) );	
	}

    // test
	public function delete( $table, $column, $value ) {
    	return $this->query( "DELETE FROM $table WHERE $column = $value" );
	}	

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

    // test
	function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
		$this->insert_id = 0;

		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ), true ) ) {
			return false;
		}

		$data = $this->process_fields( $table, $data, $format );
		
		if ( false === $data ) {
			return false;
		}

		$formats = array();
		$values  = array();
		foreach ( $data as $value ) {
			if ( is_null( $value['value'] ) ) {
				$formats[] = 'NULL';
				continue;
			}

			$formats[] = $value['format'];
			$values[]  = $value['value'];
		}

		$fields  = '`' . implode( '`, `', array_keys( $data ) ) . '`';
		$formats = implode( ', ', $formats );

		$sql = "$type INTO `$table` ($fields) VALUES ($formats)";

		$this->check_current_query = false;
		return $this->query( $this->prepare( $sql, $values ) );
	} 
	
	/**
	 * Processes arrays of field/value pairs and field formats.
	 *
	 * This is a helper method for wpdb's CRUD methods, which take field/value pairs
	 * for inserts, updates, and where clauses. This method first pairs each value
	 * with a format. Then it determines the charset of that field, using that
	 * to determine if any invalid text would be stripped. If text is stripped,
	 * then field processing is rejected and the query fails.
	 *
	 * @param string $table  Table name.
	 * @param array  $data   Field/value pair.
	 * @param mixed  $format Format for each field.
	 * @return array|false An array of fields that contain paired value and formats.
	 *                     False for invalid values.
	 */
	protected function process_fields( $table, $data, $format ) {
		$data = $this->process_field_formats( $data, $format );
		if ( false === $data ) {
			return false;
		}

		$data = $this->process_field_charsets( $data, $table );
		if ( false === $data ) {
			return false;
		}

		$data = $this->process_field_lengths( $data, $table );
		if ( false === $data ) {
			return false;
		}

/*
		$converted_data = $this->strip_invalid_text( $data );

		if ( $data !== $converted_data ) {
			return false;
		}
*/

		return $data;
	}

	/**
	 * Prepares arrays of value/format pairs as passed to wpdb CRUD methods.
	 *
	 * @param array $data   Array of fields to values.
	 * @param mixed $format Formats to be mapped to the values in $data.
	 * @return array Array, keyed by field names with values being an array
	 *               of 'value' and 'format' keys.
	 */
	protected function process_field_formats( $data, $format ) {
		$formats          = (array) $format;
		$original_formats = $formats;

		foreach ( $data as $field => $value ) {
			$value = array(
				'value'  => $value,
				'format' => '%s',
			);

			if ( ! empty( $format ) ) {
				$value['format'] = array_shift( $formats );
				if ( ! $value['format'] ) {
					$value['format'] = reset( $original_formats );
				}
			} elseif ( isset( $this->field_types[ $field ] ) ) {
				$value['format'] = $this->field_types[ $field ];
			}

			$data[ $field ] = $value;
		}

		return $data;
	}

	/**
	 * Adds field charsets to field/value/format arrays generated by wpdb::process_field_formats().
	 *
	 * @param array  $data  As it comes from the wpdb::process_field_formats() method.
	 * @param string $table Table name.
	 * @return array|false The same array as $data with additional 'charset' keys.
	 *                     False on failure.
	 */
	protected function process_field_charsets( $data, $table ) {
		foreach ( $data as $field => $value ) {
			if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
				/*
				 * We can skip this field if we know it isn't a string.
				 * This checks %d/%f versus ! %s because its sprintf() could take more.
				 */
				$value['charset'] = false;
			} else {
				$value['charset'] = $this->get_col_charset( $table, $field );
				if ( is_wp_error( $value['charset'] ) ) {
					return false;
				}
			}

			$data[ $field ] = $value;
		}

		return $data;
	}

	/**
	 * For string fields, records the maximum string length that field can safely save.
	 *
	 * @param array  $data  As it comes from the wpdb::process_field_charsets() method.
	 * @param string $table Table name.
	 * @return array|false The same array as $data with additional 'length' keys, or false if
	 *                     any of the values were too long for their corresponding field.
	 */
	protected function process_field_lengths( $data, $table ) {
		foreach ( $data as $field => $value ) {
			if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
				/*
				 * We can skip this field if we know it isn't a string.
				 * This checks %d/%f versus ! %s because its sprintf() could take more.
				 */
				$value['length'] = false;
			} else {
				$value['length'] = $this->get_col_length( $table, $field );
				if ( is_wp_error( $value['length'] ) ) {
					return false;
				}
			}

			$data[ $field ] = $value;
		}

		return $data;
	}	   			

}
