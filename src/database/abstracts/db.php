<?php
/**
 * Abstract database class
 *
 * @package PHPAPI
 * @version 0.1.0
 */

namespace Mitchell\API\Database\Abstracts;

use Mitchell\API\Config\Database;

/**
 * Abstract DB class.
 *
 * @abstract
 */
abstract class DB {

    /**
     * The name of our database table
     *
     * @var mixed
     * @access  public
     * @since   0.1.0
     */
    public $table_name;

    /**
     * The version of our database table
     *
     * @var mixed
     * @access  public
     * @since   0.1.0
     */
    public $version;

    /**
     * The name of the primary column
     *
     * @var mixed
     * @access  public
     * @since   0.1.0
     */
    public $primary_key;

    /**
     * Get things started
     *
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {}

    /**
     * Whitelist of columns
     *
     * @access  public
     * @since   0.1.0
     * @return  array
     */
    public function get_columns() {
        return array();
    }

    /**
     * Default column values
     *
     * @access  public
     * @since   0.1.0
     * @return  array
     */
    public function get_column_defaults() {
        return array();
    }

    /**
     * Retrieve a row by the primary key
     *
     * @access  public
     * @param mixed $row_id int.
     * @since   0.1.0
     * @return  object
     */
    public function get( $row_id ) {
        return Database::getInstance()->get_row( sprintf( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @access  public
     * @param mixed $column string.
     * @param mixed $value string.
     * @since   0.1.0
     * @return  object
     */
    public function get_by( $column, $value ) {

        // $column = esc_sql( $column ); // $apidb->esc_string( $column );

        return Database::getInstance()->get_row( sprintf( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $value ) );
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @access  public
     * @param mixed $column string.
     * @param mixed $row_id int.
     * @since   0.1.0
     * @return  string
     */
    public function get_column( $column, $row_id ) {

        // $column = esc_sql( $column ); // $apidb->esc_string( $column );

        return Database::getInstance()->get_var( sprintf( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve a specific column's value by the the specified column / value
     *
     * @access  public
     * @param mixed $column string.
     * @param mixed $column_where string.
     * @param mixed $column_value string.
     * @since   0.1.0
     * @return  string
     */
    public function get_column_by( $column, $column_where, $column_value ) {

        // $column_where = esc_sql( $column_where ); // $apidb->esc_string( $column_where );
        // $column = esc_sql( $column ); // $apidb->esc_string( $column );

        return Database::getInstance()->get_var( sprintf( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
    }
    
    public function query( $query = '' ) {
        // clean query?        
        $result = database()->sql($query);
        
        $results = database()->get_result();
        //$results['num_rows'] = database()->num_rows();

        return $results;
    }

    /**
     * Insert a new row
     *
     * @access  public
     * @param mixed $data array.
     * @since   0.1.0
     * @return  int
     */
    public function insert( $data ) {

        // Set default values.
        $data = parse_args( $data, $this->get_column_defaults() );

        // Initialise column format array.
        $column_formats = $this->get_columns();

        // Force fields to lower case.
        $data = array_change_key_case( $data );

        // White list columns.
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data.
        $data_keys      = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        Database::getInstance()->insert( $this->table_name, $data );

        return Database::getInstance()->insert_id();
    }

    /**
     * Update a row
     *
     * @access  public
     * @param mixed  $row_id int.
     * @param array  $data (default: array()).
     * @param string $where (default: '').
     * @since   0.1.0
     * @return  bool
     */
    public function update( $row_id, $data = array(), $where = '' ) {

        // Row ID must be positive integer.
        $row_id = intval( $row_id );

        if ( empty( $row_id ) ) {
            return false;
        }

        if ( empty( $where ) ) {
            $where = $this->primary_key;
        }

        // Initialise column format array.
        $column_formats = $this->get_columns();

        // Force fields to lower case.
        $data = array_change_key_case( $data );

        // White list columns.
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data.
        $data_keys      = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        if ( false === Database::getInstance()->update( $this->table_name, $data, array( $where => $row_id ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Delete a row identified by the primary key
     *
     * @access  public
     * @param int    $row_id (default: 0).
     * @param string $where (default: '').
     * @since   0.1.0
     * @return  bool
     */
    public function delete( $row_id = 0, $where = '' ) {

        // Row ID must be positive integer.
        $row_id = intval( $row_id );

        if ( empty( $row_id ) ) {
            return false;
        }

        if ( false === Database::getInstance()->delete( $this->table_name, $this->primary_key . ' = ' . $row_id ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given table exists
     *
     * @since  0.1.0
     * @return bool
     */
    public function table_exists() {
        return Database::getInstance()->table_exists( $this->table_name );
    }

}
