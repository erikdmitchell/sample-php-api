<?php
/**
 * Films database class
 *
 * @package PHPAPI
 * @version 0.1.0
 */

/**
 * Films class.
 *
 * @extends DB
 */
class Films extends DB {

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
     * @return array
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
     * @return array
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
     * Insert.
     *
     * @access public
     * @param mixed $data array.
     * @return bool
     */
    public function insert( $data ) {
        $result = parent::insert( $data );

        if ( $result ) {
            $this->set_last_changed( $result );
        }

        return $result;
    }


    /**
     * Update.
     *
     * @access public
     * @param mixed  $row_id int.
     * @param array  $data (default: array()).
     * @param string $where (default: '').
     * @return int
     */
    public function update( $row_id, $data = array(), $where = '' ) {
        $result = parent::update( $row_id, $data, $where );

        if ( $result ) {
            $this->set_last_changed( $row_id );
        }

        return $result;
    }

    /**
     * Set last changed.
     *
     * @access public
     * @param int $row_id (default: 0).
     * @return bool
     */
    public function set_last_changed( $row_id = 0 ) {
        global $apidb;

        if ( false === $apidb->update( $this->table_name, array( 'last_updated' => date( 'Y-m-d H:i:s' ) ), array( $this->primary_key => $row_id ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Create the table
     *
     * @since 0.1.0
     */
    public function create_table() {
        global $apidb;

        $sql = "CREATE TABLE {$this->table_name} (
        id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL DEFAULT '',
        year int(4) NOT NULL,
        actor varchar(80) NOT NULL DEFAULT '',
        director varchar(80) NOT NULL DEFAULT '',
        image varchar(255) NOT NULL DEFAULT '',
        date_created datetime NOT NULL,
        last_updated datetime NOT NULL,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if (!$this->table_exists()) {
            $apidb->sql( $sql );
        }
    }

}
