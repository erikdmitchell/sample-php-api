<?php

class DB_Films extends DB {

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
     * Insert.
     *
     * @access public
     * @param mixed $data
     * @return void
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
     * @param mixed  $row_id
     * @param array  $data (default: array())
     * @param string $where (default: '')
     * @return int
     */
    public function update( $row_id, $data = array(), $where = '' ) {
        $result = parent::update( $row_id, $data, $where );

        if ( $result ) {
            $this->set_last_changed( $row_id );
        }

        return $result;
    }

    /*
    public function exists( $value = '', $field = 'id' ) {
        $columns = $this->get_columns();

        if ( ! array_key_exists( $field, $columns ) ) {
            return false;
        }

        return (bool) $this->get_column_by( 'id', $field, $value );
    }
    */

    /**
     * Set last changed.
     *
     * @access public
     * @param int $row_id (default: 0)
     * @return void
     */
    public function set_last_changed( $row_id = 0 ) {
        global $db;

        if ( false === $db->update( $this->table_name, array( 'last_updated' => date( 'Y-m-d H:i:s' ) ), array( $this->primary_key => $row_id ) ) ) {
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
        echo "create table: $this->table_name<br>";
        $sql = 'CREATE TABLE ' . $this->table_name . ' (
        id int(11) unsigned NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL DEFAULT '',
        year int(4) NOT NULL,
        actor varchar(80) NOT NULL DEFAULT '',
        director varchar(80) NOT NULL DEFAULT '',
        image varchar(255) NOT NULL DEFAULT '',
        date_created datetime NOT NULL,
        last_updated datetime NOT NULL,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';

        // insert.
    }

}
