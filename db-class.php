<?php

class DB {

    /**
     * The name of our database table
     *
     * @access  public
     * @since   0.1.0
     */
    public $table_name;

    /**
     * The version of our database table
     *
     * @access  public
     * @since   0.1.0
     */
    public $version;

    /**
     * The name of the primary column
     *
     * @access  public
     * @since   0.1.0
     */
    public $primary_key;

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->table_name  = 'films';
        $this->primary_key = 'id';
        $this->version     = '0.1.0';
    }

    /**
     * Get table columns.
     *
     * @access public
     * @return void
     */
    public function get_columns() {
        return array(
            'id' => '%d',
            'name' => '%s',
            'year' => '%d',
            'actor' => '%s',
            'director' => '%s',
            'image' => '%s',
            'date_created' => '%s',
            'last_updated' => '%s',                                    
        );
    }

    /**
     * Column defaults.
     *
     * @access public
     * @return void
     */
    public function get_column_defaults() {
        return array(
            'name' => '',
            'year' => '',
            'actor' => '',
            'director' => '',
            'image' => '', 
            'date_created' => date( 'Y-m-d H:i:s' ),
            'last_updated' => date( 'Y-m-d H:i:s' ),
        );
    }

    /**
     * Retrieve a row by the primary key
     *
     * @access  public
     * @since   0.1.0
     * @return  object
     */
    public function get( $row_id ) {
        global $apidb;
       
        return $apidb->get_row( sprintf( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve a row by a specific column / value
     *
     * @access  public
     * @since   0.1.0
     * @return  object
     */
    public function get_by( $column, $value ) {
        global $apidb;

        //$column = esc_sql( $column ); // $apidb->esc_string( $column );

        return $apidb->get_row( sprintf( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $value ) );
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @access  public
     * @since   0.1.0
     * @return  string
     */
    public function get_column( $column, $row_id ) {
        global $apidb;

        //$column = esc_sql( $column ); // $apidb->esc_string( $column );

        return $apidb->get_var( sprintf( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve a specific column's value by the the specified column / value
     *
     * @access  public
     * @since   0.1.0
     * @return  string
     */
    public function get_column_by( $column, $column_where, $column_value ) {
        global $apidb;

        //$column_where = esc_sql( $column_where ); // $apidb->esc_string( $column_where );
        //$column = esc_sql( $column ); // $apidb->esc_string( $column );

        return $apidb->get_var( sprintf( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
    }

    /**
     * Insert a new row
     *
     * @access  public
     * @since   0.1.0
     * @return  int
     */
    public function insert( $data ) {
        global $apidb;

        // Set default values
        $data = parse_args( $data, $this->get_column_defaults() );

        // Initialise column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case( $data );

        // White list columns
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        $apidb->insert( $this->table_name, $data );

        return $apidb->insert_id();
    }

    /**
     * Update a row
     *
     * @access  public
     * @since   0.1.0
     * @return  bool
     */
    public function update( $row_id, $data = array(), $where = '' ) {
        global $apidb;

        // Row ID must be positive integer
        $row_id = intval( $row_id );

        if ( empty( $row_id ) ) {
            return false;
        }

        if ( empty( $where ) ) {
            $where = $this->primary_key;
        }

        // Initialise column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case( $data );

        // White list columns
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        if ( false === $apidb->update( $this->table_name, $data, array( $where => $row_id ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Delete a row identified by the primary key
     *
     * @access  public
     * @since   0.1.0
     * @return  bool
     */
    public function delete( $row_id = 0 ) {
        global $apidb;

        // Row ID must be positive integer
        $row_id = absint( $row_id );

        if ( empty( $row_id ) ) {
            return false;
        }

        if ( false === $apidb->query( sprintf( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the table exists
     *
     * @since  0.1.0
     * 
     * @return bool If the table name exists
     */
    public function table_exists() {
        global $apidb;

        return $apidb->table_exists( $this->table_name );
    }

}
